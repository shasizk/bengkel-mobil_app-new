@extends('be.master')

@section('Chat')
<style>
    /* LINE-like chat theme */
    .chat-wrapper {
        background-color: #e8f5e9;
        background-image: linear-gradient(180deg, #eef8ef 0%, #e4f4e6 100%);
        padding: 20px;
        overflow-y: auto;
        font-family: 'DM Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        scroll-behavior: smooth;
    }

    .chat-message {
        display: flex;
        margin-bottom: 20px;
        flex-direction: column;
    }

    /* Pesan dari lawan bicara (Kiri) */
    .chat-message.receiver {
        align-items: flex-start;
    }
    .chat-message.receiver .chat-bubble {
        background-color: #ffffff;
        color: #0f172a;
        border-radius: 12px 12px 12px 2px;
        border: 1px solid #dbe4ee;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    /* Pesan dari diri sendiri (Kanan) */
    .chat-message.sender {
        align-items: flex-end;
    }
    .chat-message.sender .chat-bubble {
        background-color: #06c755;
        color: #032b14;
        border-radius: 12px 12px 2px 12px;
        border: 1px solid #15a34a;
        box-shadow: 0 2px 8px rgba(6,199,85,0.22);
    }

    .chat-bubble {
        max-width: 75%;
        padding: 12px 16px;
        font-size: 14.5px;
        line-height: 1.5;
        word-wrap: break-word;
    }

    .chat-time {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
        margin-left: 2px;
        margin-right: 2px;
    }

    .sender-name {
        font-size: 12px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 6px;
        margin-left: 2px;
    }
    .chat-sidebar-list a {
        transition: all .2s ease;
    }
    .chat-sidebar-list a:hover {
        background: #f0fdf4 !important;
    }
</style>
<div class="container-fluid px-3 px-lg-4">
    <div class="page-inner">
        @if(!empty($chatTableMissing))
            <div class="alert alert-warning">Tabel chat belum tersedia. Jalankan migrasi terbaru agar chat client-admin bisa dipakai.</div>
        @endif
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="row g-0">
                <div class="col-lg-4 border-end">
                    <div class="p-4 border-bottom" style="background: linear-gradient(180deg, #f7faf8, #ecf8ee);">
                        <h4 class="fw-bold mb-1">Inbox Client</h4>
                        <small class="text-muted">Kelola percakapan customer dengan admin dari satu panel.</small>
                    </div>
                    <div class="chat-sidebar-list">
                        @forelse($conversations as $conversation)
                            @php($lastMessage = $conversation->messages->first())
                            <a href="{{ backend_route('admin.chat.index', ['conversation' => $conversation->id]) }}" class="d-block text-decoration-none border-bottom p-3 {{ $selectedConversation?->id === $conversation->id ? 'bg-light' : 'bg-white' }}" style="{{ $selectedConversation?->id === $conversation->id ? 'background:#f0fdf4 !important;border-left:3px solid #16a34a;' : '' }}">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $conversation->customer->name }}</div>
                                        <div class="small text-muted">{{ $conversation->customer->email }}</div>
                                    </div>
                                    <small class="text-muted">{{ optional($conversation->last_message_at)->diffForHumans() }}</small>
                                </div>
                                <div class="small text-muted mt-2">{{ \Illuminate\Support\Str::limit($lastMessage?->message ?? 'Belum ada pesan.', 72) }}</div>
                            </a>
                        @empty
                            <div class="p-4 text-muted">Belum ada percakapan dari client.</div>
                        @endforelse
                    </div>
                </div>
                <div class="col-lg-8">
                    @if($selectedConversation)
                        <div class="p-4 border-bottom" style="background: #f9fffb;">
                            <h5 class="fw-bold mb-1">{{ $selectedConversation->customer->name }}</h5>
                            <small class="text-muted">Percakapan customer dengan tim admin bengkel.</small>
                        </div>
                        <div class="chat-wrapper" id="chatWrapper" style="min-height:480px; max-height:480px; border-radius:0;">
                            @forelse($selectedConversation->messages as $message)
                                <div class="chat-message {{ $message->sender_role === 'customer' ? 'receiver' : 'sender' }}">
                                    @if($message->sender_role === 'customer')
                                        <div class="sender-name">{{ $message->sender->name ?? 'User' }}</div>
                                    @endif
                                    <div class="chat-bubble">
                                        {{ $message->message }}
                                    </div>
                                    <div class="chat-time">{{ $message->created_at->format('d M Y H:i') }}</div>
                                </div>
                            @empty
                                <div class="text-center text-muted w-100 mt-4">Belum ada pesan.</div>
                            @endforelse
                        </div>
                        <form method="POST" action="{{ backend_route('admin.chat.store') }}" class="p-4 border-top bg-white">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ $selectedConversation->id }}">
                            <div class="d-flex gap-2">
                                <textarea name="message" class="form-control" rows="3" placeholder="Balas pesan client..." required></textarea>
                                <button type="submit" class="btn btn-primary px-4">Kirim</button>
                            </div>
                        </form>
                    @else
                        <div class="p-5 text-center text-muted">Pilih percakapan client untuk mulai membalas chat.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var chatWrapper = document.getElementById("chatWrapper");
        if (chatWrapper) {
            chatWrapper.scrollTop = chatWrapper.scrollHeight;
        }
    });
</script>
@endsection
