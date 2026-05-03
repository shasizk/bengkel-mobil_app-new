@extends('be.master')

@section('Rating')
<style>
    .rating-summary-chip {
        border-radius: 999px;
        padding: 8px 14px;
        font-weight: 700;
        border: 1px solid rgba(14, 116, 144, .2);
        background: linear-gradient(180deg, #f7fbff, #edf6ff);
        color: #0f4c81;
    }
    .rating-card {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 28px rgba(15, 23, 42, .06);
    }
    .rating-client-box {
        border-radius: 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    .rating-admin-box {
        border-radius: 14px;
        background: #f0fdf6;
        border: 1px solid #bbf7d0;
    }
</style>
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="mb-1">Rating Bengkel</h2>
            <p class="text-muted mb-0">Ulasan dari client yang masuk melalui halaman frontend.</p>
        </div>
        <div class="d-flex gap-3 flex-wrap align-items-center">
            <select class="form-select form-select-sm" data-table-filter="rating" style="min-width: 160px;">
                <option value="">Semua Rating</option>
                <option value="5">5 Bintang</option>
                <option value="4">4 Bintang</option>
                <option value="3">3 Bintang</option>
                <option value="2">2 Bintang</option>
                <option value="1">1 Bintang</option>
            </select>
            <select class="form-select form-select-sm" data-table-filter="replied" style="min-width: 180px;">
                <option value="">Semua Balasan</option>
                <option value="yes">Sudah Dibalas</option>
                <option value="no">Belum Dibalas</option>
            </select>
            <div class="rating-summary-chip">Rata-rata: {{ number_format($averageRating ?: 0, 1) }}/5</div>
            <div class="rating-summary-chip">Total Ulasan: {{ $ratingCount }}</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(! $hasRatingTable)
        <div class="alert alert-warning">Tabel rating belum tersedia. Jalankan migrasi untuk mengaktifkan fitur rating.</div>
    @elseif($ratings->isEmpty())
        <div class="alert alert-light border">Belum ada rating dari client.</div>
    @else
        <div class="row g-4">
            @foreach($ratings as $rating)
                <div class="col-12" data-search-item="1" data-search-text="{{ $rating->user->name ?? 'Client' }} {{ $rating->comment }} {{ $rating->admin_reply ?? '' }}" data-rating="{{ (int) $rating->rating }}" data-replied="{{ $rating->admin_reply ? 'yes' : 'no' }}">
                    <div class="rating-card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                                <div>
                                    <h5 class="mb-1">{{ $rating->user->name ?? 'Client' }}</h5>
                                    <div class="text-muted small">
                                        {{ $rating->booking ? '#'.$rating->booking->id.' - '.($rating->booking->vehicle->license_plate ?? '-') : 'Rating umum' }}
                                        • {{ $rating->created_at?->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="fw-bold text-warning" style="font-size: 18px; letter-spacing: 1px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= (int) $rating->rating ? '★' : '☆' }}
                                    @endfor
                                </div>
                            </div>

                            <div class="p-3 rating-client-box mb-3">
                                <strong class="d-block mb-1">Komentar Client</strong>
                                <span class="text-muted">{{ $rating->comment }}</span>
                            </div>

                            @if($rating->admin_reply)
                                <div class="p-3 rating-admin-box mb-3">
                                    <strong class="d-block mb-1 text-success">Balasan Admin</strong>
                                    <div class="mb-1">{{ $rating->admin_reply }}</div>
                                    <small class="text-muted">
                                        Dibalas oleh {{ $rating->responder->name ?? 'Admin' }}
                                        {{ $rating->responded_at ? '• '.$rating->responded_at->format('d/m/Y H:i') : '' }}
                                    </small>
                                </div>
                            @endif

                            @if($backendRole === 'admin')
                                <form method="POST" action="{{ backend_route('admin.ratings.reply', [$rating->id]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">{{ $rating->admin_reply ? 'Ubah Balasan Admin' : 'Balas Rating Client' }}</label>
                                        <textarea name="admin_reply" rows="3" class="form-control" placeholder="Tulis balasan profesional untuk client...">{{ old('admin_reply', $rating->admin_reply) }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">Simpan Balasan</button>
                                </form>
                            @elseif($backendRole === 'owner')
                                <div class="alert alert-secondary mb-0 py-2">Mode Owner: hanya dapat melihat rating dan balasan.</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $ratings->links() }}
        </div>
    @endif
</div>
@endsection
