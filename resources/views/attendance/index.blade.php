@extends('be.master')
@section('Attendance')
<div class="container">
    <div class="page-inner">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h3 class="mb-0">Presensi</h3>
                <select class="form-select form-select-sm" data-table-filter="attendance-status" style="min-width: 180px;">
                    <option value="">Semua Status</option>
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                </select>
            </div>
        @if(!empty($attendanceTableMissing))
            <div class="alert alert-warning">
                Tabel absensi mekanik <code>mechanic_attendances</code> belum tersedia. Jalankan migrasi terbaru agar fitur absensi bisa dipakai.
            </div>
        @endif
        @if(($backendAuthUser->role ?? null) === 'mekanik')
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header"><h4 class="fw-bold mb-0">Presensi Mekanik Dengan Wajah</h4></div>
                <div class="card-body">
                    <form method="POST" action="{{ backend_route('admin.attendance.store') }}" id="attendance-face-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="hadir" {{ ($todayAttendance->status ?? '') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="izin" {{ ($todayAttendance->status ?? '') === 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ ($todayAttendance->status ?? '') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label>Catatan</label>
                                <input type="text" name="notes" class="form-control" value="{{ old('notes', $todayAttendance->notes ?? '') }}" placeholder="Catatan absensi hari ini">
                            </div>
                        </div>
                        <input type="hidden" name="face_photo" id="face_photo_input" value="{{ old('face_photo', $todayAttendance->face_photo ?? '') }}">
                        <div class="row align-items-start mt-2">
                            <div class="col-12 col-md-6 mb-3">
                                <label class="fw-semibold">Kamera</label>
                                <div class="rounded-4 overflow-hidden border bg-dark">
                                    <video id="attendance-video" autoplay playsinline muted style="width:100%; max-height:320px; object-fit:cover;"></video>
                                </div>
                                <div class="small text-muted mt-2">Izinkan akses kamera, lalu ambil foto wajah sebelum simpan presensi.</div>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label class="fw-semibold">Hasil Capture</label>
                                <div class="rounded-4 overflow-hidden border bg-light d-flex align-items-center justify-content-center" style="min-height:320px;">
                                    <img id="attendance-preview" src="{{ old('face_photo', $todayAttendance->face_photo ?? '') }}" alt="Preview wajah" style="max-width:100%; max-height:320px; object-fit:cover; transform: scaleX(1); {{ old('face_photo', $todayAttendance->face_photo ?? '') ? '' : 'display:none;' }}">
                                    <div id="attendance-placeholder" class="text-muted text-center px-4" style="{{ old('face_photo', $todayAttendance->face_photo ?? '') ? 'display:none;' : '' }}">Belum ada foto wajah yang diambil.</div>
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <button type="button" class="btn btn-outline-primary" id="capture-face-button">Ambil Foto Wajah</button>
                                    <button type="button" class="btn btn-outline-secondary" id="restart-camera-button">Refresh Kamera</button>
                                </div>
                            </div>
                        </div>
                        <canvas id="attendance-canvas" class="d-none"></canvas>
                        <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                    </form>
                </div>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header"><h4 class="fw-bold mb-0">{{ ($backendAuthUser->role ?? null) === 'owner' ? 'Rekap Absensi Mekanik' : 'Riwayat Absensi' }}</h4></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead><tr><th>Tanggal</th><th>Nama Mekanik</th><th>Status</th><th>Catatan</th><th>Foto Wajah</th></tr></thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr data-search-row="1" data-search-text="{{ $attendance->user->name ?? '-' }} {{ $attendance->notes ?: '-' }} {{ $attendance->status }}" data-attendance-status="{{ $attendance->status }}">
                                    <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') }}</td>
                                    <td>{{ $attendance->user->name ?? '-' }}</td>
                                    <td><span class="badge bg-light text-dark text-uppercase">{{ $attendance->status }}</span></td>
                                    <td>{{ $attendance->notes ?: '-' }}</td>
                                    <td>
                                        @if($attendance->face_photo)
                                            <img src="{{ $attendance->face_photo }}" alt="Foto presensi {{ $attendance->user->name ?? 'mekanik' }}" style="width:72px; height:72px; object-fit:cover; border-radius:18px;">
                                        @else
                                            <span class="text-muted small">Belum ada foto</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada data absensi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@if(($backendAuthUser->role ?? null) === 'mekanik')
    @push('page-scripts')
    <script>
        (() => {
            const video = document.getElementById('attendance-video');
            const canvas = document.getElementById('attendance-canvas');
            const preview = document.getElementById('attendance-preview');
            const placeholder = document.getElementById('attendance-placeholder');
            const hiddenInput = document.getElementById('face_photo_input');
            const captureButton = document.getElementById('capture-face-button');
            const restartButton = document.getElementById('restart-camera-button');
            const form = document.getElementById('attendance-face-form');
            let stream;

            async function startCamera() {
                if (!navigator.mediaDevices?.getUserMedia) {
                    placeholder.textContent = 'Browser ini belum mendukung akses kamera.';
                    return;
                }

                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                    video.srcObject = stream;
                } catch (error) {
                    placeholder.textContent = 'Kamera tidak bisa diakses. Pastikan izin kamera sudah diberikan.';
                }
            }

            captureButton?.addEventListener('click', async () => {
                if (!video.videoWidth || !video.videoHeight) {
                    await startCamera();
                    return;
                }

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const image = canvas.toDataURL('image/png');
                hiddenInput.value = image;
                preview.src = image;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            });

            restartButton?.addEventListener('click', startCamera);

            form?.addEventListener('submit', (event) => {
                if (!hiddenInput.value) {
                    event.preventDefault();
                    alert('Ambil foto wajah terlebih dulu sebelum menyimpan presensi.');
                }
            });

            startCamera();
        })();
    </script>
    @endpush
@endif
@endsection
