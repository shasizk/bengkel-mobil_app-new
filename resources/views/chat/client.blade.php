@extends('fe.master')

@section('content')
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
        color: #475569;
        margin-bottom: 6px;
        margin-left: 2px;
    }
</style>
<section class="site-section" style="padding-top: 140px; background-color: #f1f5f9; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                @if(!empty($chatTableMissing))
                    <div class="alert alert-warning mb-4">Tabel chat belum tersedia. Jalankan migrasi terbaru agar chat client-admin bisa dipakai.</div>
                @endif
                <div class="card border-0 shadow-lg overflow-hidden">
                    <div class="card-header border-0 text-white p-3" style="background-color: #1e293b;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px; background-color: #334155; font-size: 1.2rem;">
                                    A
                                </div>
                                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 rounded-circle" style="border-color: #1e293b !important;" title="Online"></span>
                            </div>
                            <div>
                                    <h5 class="mb-1 fw-bold text-white">Admin Raxy Garage</h5>
                                <small class="d-flex align-items-center gap-2" style="color: #cbd5e1;">
                                    <span class="text-success" style="font-size: 10px;">●</span> Online
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="chat-wrapper" id="chatWrapper" style="min-height:420px; max-height:420px;">
                            @forelse(($conversation?->messages ?? collect()) as $message)
                                <div class="chat-message {{ $message->sender_role === 'customer' ? 'sender' : 'receiver' }}">
                                    @if($message->sender_role !== 'customer')
                                        <div class="sender-name">{{ $message->sender->name ?? 'Admin' }}</div>
                                    @endif
                                    <div class="chat-bubble">
                                        {{ $message->message }}
                                    </div>
                                    <div class="chat-time">{{ $message->created_at->format('d M Y H:i') }}</div>
                                </div>
                            @empty
                                <div class="alert alert-light border mb-0 text-center text-muted">Belum ada pesan. Mulai chat dengan admin bengkel sekarang.</div>
                            @endforelse
                        </div>
                        <form method="POST" action="{{ route('client.chat.store') }}" class="p-4 border-top">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ $conversation?->id }}">
                            <div class="d-flex gap-2">
                                <textarea name="message" class="form-control" rows="3" placeholder="Tulis pesan untuk admin..." required></textarea>
                                <button type="submit" class="btn btn-primary px-4" {{ !empty($chatTableMissing) ? 'disabled' : '' }}>Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var chatWrapper = document.getElementById("chatWrapper");
        if (chatWrapper) {
            chatWrapper.scrollTop = chatWrapper.scrollHeight;
        }
    });
</script>
@endsection
