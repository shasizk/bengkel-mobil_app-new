@extends('fe.master')

@section('content')
  @php($filledStars = (int) round($averageRating))
  <style>
    .rating-page {
      padding: 48px 0 72px;
    }

    .rating-hero {
      position: relative;
      overflow: hidden;
      border-radius: 28px;
      background: linear-gradient(135deg, #07111f 0%, #0b4a57 48%, #0f766e 100%);
      color: #fff;
      box-shadow: 0 30px 70px rgba(15, 23, 42, 0.25);
    }

    .rating-hero::before {
      content: "";
      position: absolute;
      inset: 0;
      background:
        radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 22%),
        radial-gradient(circle at bottom left, rgba(255,255,255,.10), transparent 26%);
      pointer-events: none;
    }

    .rating-panel,
    .rating-form-card,
    .review-card {
      border: 1px solid rgba(15, 23, 42, 0.08);
      border-radius: 24px;
      background: #fff;
      box-shadow: 0 22px 50px rgba(15, 23, 42, 0.08);
    }

    .rating-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 14px;
      border-radius: 999px;
      background: rgba(255,255,255,.12);
      color: #fff;
      font-weight: 700;
      letter-spacing: .02em;
    }

    .star-row {
      display: inline-flex;
      gap: 4px;
      color: #f59e0b;
      font-size: 18px;
    }

    .rating-option input {
      display: none;
    }

    .rating-option label {
      width: 50px;
      height: 50px;
      border-radius: 16px;
      border: 1px solid #dbe4ee;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: #475569;
      cursor: pointer;
      transition: all .2s ease;
      background: #fff;
    }

    .rating-option input:checked + label {
      background: linear-gradient(135deg, #0f766e, #0ea5e9);
      color: #fff;
      border-color: transparent;
      transform: translateY(-2px);
    }

    .section-title {
      letter-spacing: .04em;
      text-transform: uppercase;
      font-size: 12px;
      color: #0f766e;
      font-weight: 700;
    }
  </style>

  <div class="rating-page">
    <div class="container">
      <div class="rating-hero p-4 p-md-5 mb-4">
        <div class="position-relative">
          <div class="rating-badge mb-3">Rating Bengkel Client</div>
          <h1 class="mb-3" style="font-family: 'DM Sans', sans-serif; font-weight: 700;">Berikan penilaian untuk RAXY GARAGE</h1>
          <p class="mb-4" style="max-width: 760px; color: rgba(255,255,255,.82); font-size: 17px; line-height: 1.7;">
            Sampaikan pengalaman servis kamu dengan jujur. Rating ini membantu bengkel menjaga kualitas kerja, pelayanan, dan kenyamanan customer.
          </p>
          <div class="d-flex flex-wrap gap-3">
            <a href="#rating-form" class="btn btn-light px-4 py-2" style="border-radius: 999px; font-weight: 700;">Kasih Rating</a>
            <a href="{{ route('home') }}" class="btn btn-outline-light px-4 py-2" style="border-radius: 999px; font-weight: 700;">Kembali ke Home</a>
          </div>
        </div>
      </div>

      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="rating-panel p-4 h-100">
            <div class="section-title mb-2">Rata-rata rating</div>
            <div class="d-flex align-items-end gap-2 mb-2">
              <h2 class="mb-0" style="font-size: 44px; font-weight: 800; color: #0f172a;">{{ number_format($averageRating ?: 0, 1) }}</h2>
              <span class="mb-2 text-muted">/ 5</span>
            </div>
            <div class="star-row mb-2">
              @for($i = 1; $i <= 5; $i++)
                <span>{{ $i <= $filledStars ? '★' : '☆' }}</span>
              @endfor
            </div>
            <div class="text-muted">{{ $ratingCount }} ulasan terkirim dari client.</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="rating-panel p-4 h-100">
            <div class="section-title mb-2">Pengguna aktif</div>
            <h3 class="mb-2" style="font-weight: 800; color: #0f172a;">{{ $clientUser->name }}</h3>
            <p class="mb-0 text-muted">Rating hanya bisa dikirim oleh client yang login, jadi lebih terkontrol dan relevan untuk pengalaman servis.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="rating-panel p-4 h-100">
            <div class="section-title mb-2">Booking selesai</div>
            <h3 class="mb-2" style="font-weight: 800; color: #0f172a;">{{ $completedBookings->count() }}</h3>
            <p class="mb-0 text-muted">Kalau ada booking yang sudah selesai, kamu bisa pilih sebagai referensi rating.</p>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-5" id="rating-form">
          <div class="rating-form-card p-4 p-md-5">
            <div class="section-title mb-2">Tulis rating</div>
            <h3 class="mb-3" style="font-weight: 800; color: #0f172a;">Kasih masukan ke bengkel</h3>

            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
              <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(isset($hasRatingTable) && ! $hasRatingTable)
              <div class="alert alert-warning">Fitur rating belum aktif penuh. Jalankan migrasi terlebih dahulu.</div>
            @endif

            @if($errors->any())
              <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                  <div>{{ $error }}</div>
                @endforeach
              </div>
            @endif

            <form method="POST" action="{{ route('client.rating.store') }}">
              @csrf

              <div class="mb-3">
                <label class="form-label fw-bold">Pilih Booking Selesai</label>
                <select name="booking_id" class="form-control" style="border-radius: 14px; min-height: 48px;">
                  <option value="">Tanpa booking khusus</option>
                  @foreach($completedBookings as $booking)
                    <option value="{{ $booking->id }}" {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                      #{{ $booking->id }} - {{ $booking->vehicle->license_plate ?? '-' }} - {{ $booking->booking_date }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold d-block">Nilai Rating</label>
                <div class="d-flex flex-wrap gap-2">
                  @for($i = 5; $i >= 1; $i--)
                    <div class="rating-option">
                      <input type="radio" id="rating-{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating', 5) == $i ? 'checked' : '' }}>
                      <label for="rating-{{ $i }}">{{ $i }}</label>
                    </div>
                  @endfor
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">Komentar</label>
                <textarea name="comment" rows="5" class="form-control" placeholder="Tulis pengalaman servis kamu..." style="border-radius: 18px;">{{ old('comment') }}</textarea>
              </div>

              <button type="submit" class="btn btn-primary px-4 py-3" style="border-radius: 999px; font-weight: 700;">Kirim Rating</button>
            </form>
          </div>
        </div>

        <div class="col-lg-7">
          <div class="rating-panel p-4 p-md-5 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
              <div>
                <div class="section-title mb-2">Ulasan terbaru</div>
                <h3 class="mb-0" style="font-weight: 800; color: #0f172a;">Apa kata client</h3>
              </div>
              <span class="text-muted">{{ $ratings->count() }} rating terakhir</span>
            </div>

            <div class="row g-3">
              @forelse($ratings as $rating)
                <div class="col-12">
                  <div class="review-card p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                      <div>
                        <strong class="d-block" style="font-size: 16px; color: #0f172a;">{{ $rating->user->name ?? 'Client' }}</strong>
                        <small class="text-muted">
                          {{ $rating->booking ? '#'.$rating->booking->id.' - '.$rating->booking->vehicle->license_plate : 'Rating umum' }}
                        </small>
                      </div>
                      <div class="star-row">
                        @for($i = 1; $i <= 5; $i++)
                          <span>{{ $i <= $rating->rating ? '★' : '☆' }}</span>
                        @endfor
                      </div>
                    </div>
                    <p class="mb-0 text-muted">{{ $rating->comment }}</p>

                    @if($rating->admin_reply)
                      <div class="mt-3 p-3 rounded" style="background: #f0fdf4; border: 1px solid #c9efd9;">
                        <div class="fw-bold text-success mb-1">Balasan Admin</div>
                        <div class="text-muted">{{ $rating->admin_reply }}</div>
                      </div>
                    @endif
                  </div>
                </div>
              @empty
                <div class="col-12">
                  <div class="alert alert-light border mb-0">Belum ada rating yang masuk. Jadi yang pertama memberi ulasan.</div>
                </div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
