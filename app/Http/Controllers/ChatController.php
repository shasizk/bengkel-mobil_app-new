<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        if (! Schema::hasTable('chat_conversations') || ! Schema::hasTable('chat_messages')) {
            return view($request->routeIs('client.*') ? 'chat.client' : 'chat.admin', [
                'title' => 'Chat',
                'conversation' => null,
                'conversations' => collect(),
                'selectedConversation' => null,
                'chatTableMissing' => true,
            ]);
        }

        if ($request->routeIs('client.*')) {
            $user = auth('client')->user();

            $conversation = ChatConversation::with(['admin', 'messages.sender'])
                ->firstOrCreate(
                    ['customer_id' => $user->id],
                    ['last_message_at' => now()]
                );

            $this->markIncomingMessagesAsRead($conversation, $user->id, 'customer');

            return view('chat.client', [
                'title' => 'Client Chat',
                'conversation' => $conversation->load(['messages.sender', 'admin']),
            ]);
        }

        $admin = backend_user();
        $selectedConversation = null;

        $conversations = ChatConversation::with(['customer', 'admin', 'messages' => fn ($query) => $query->latest()->limit(1)])
            ->orderByDesc('last_message_at')
            ->orderByDesc('updated_at')
            ->get();

        $conversationId = $request->integer('conversation');

        if ($conversationId) {
            $selectedConversation = ChatConversation::with(['customer', 'admin', 'messages.sender'])
                ->findOrFail($conversationId);

            if (! $selectedConversation->admin_id) {
                $selectedConversation->update(['admin_id' => $admin->id]);
            }

            $this->markIncomingMessagesAsRead($selectedConversation, $admin->id, $admin->role);
            $selectedConversation->load(['customer', 'admin', 'messages.sender']);
        } elseif ($conversations->isNotEmpty()) {
            $selectedConversation = ChatConversation::with(['customer', 'admin', 'messages.sender'])
                ->find($conversations->first()->id);

            $this->markIncomingMessagesAsRead($selectedConversation, $admin->id, $admin->role);
            $selectedConversation->load(['customer', 'admin', 'messages.sender']);
        }

        return view('chat.admin', [
            'title' => 'Chat',
            'conversations' => $conversations,
            'selectedConversation' => $selectedConversation,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('chat_conversations') || ! Schema::hasTable('chat_messages')) {
            return redirect()->back()->with('error', 'Tabel chat belum tersedia. Jalankan migrasi terbaru dahulu.');
        }

        $validated = $request->validate([
            'conversation_id' => 'nullable|exists:chat_conversations,id',
            'message' => 'required|string|max:2000',
        ]);

        if ($request->routeIs('client.*')) {
            $sender = auth('client')->user();
            $conversation = ChatConversation::firstOrCreate(
                ['customer_id' => $sender->id],
                ['last_message_at' => now()]
            );

            $route = route('client.chat.index');
        } else {
            $sender = backend_user();
            $conversation = ChatConversation::findOrFail($validated['conversation_id']);

            if (! $conversation->admin_id) {
                $conversation->admin_id = $sender->id;
            }

            $route = backend_route('admin.chat.index', ['conversation' => $conversation->id]);
        }

        DB::transaction(function () use ($conversation, $sender, $validated) {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $sender->id,
                'sender_role' => $sender->role,
                'message' => $validated['message'],
            ]);

            $conversation->update([
                'admin_id' => $conversation->admin_id,
                'last_message_at' => now(),
            ]);
        });

        return redirect($route)->with('success', 'Pesan berhasil dikirim.');
    }

    protected function markIncomingMessagesAsRead(ChatConversation $conversation, int $viewerId, string $viewerRole): void
    {
        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', $viewerId)
            ->where('sender_role', '!=', $viewerRole)
            ->update(['read_at' => now()]);
    }
}
