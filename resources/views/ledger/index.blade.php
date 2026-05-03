@extends('be.master')
@section('Ledger')
@if(!empty($ledgerTableMissing))
<div class="container-fluid" style="margin-top: 30px;">
    <div class="alert alert-warning">
        Tabel Bookeeping <code>ledger_entries</code> belum tersedia, jadi data ledger belum bisa ditampilkan. Jalankan migrasi terbaru untuk mengaktifkan halaman ini.
    </div>
</div>
@endif
<div class="container-fluid" style="margin-top: 30px;">
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm text-white" style="border-radius: 24px; background: linear-gradient(135deg, #0f172a, #14532d);">
                <div class="card-body p-4 p-lg-5">
                    <p class="mb-2 text-uppercase" style="letter-spacing: .25em; color: rgba(255,255,255,.7); font-size: 12px;">Owner Finance</p>
                    <h2 class="fw-bold mb-2">Bookeeping Bengkel</h2>
                    <p class="mb-0" style="color: rgba(255,255,255,.78);">Halaman ini terpisah dari dashboard dan khusus untuk membaca arus pemasukan, pengeluaran, pembelian alat, serta ringkasan transaksi yang sudah lunas.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-md-3 mb-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><p class="text-muted mb-1">Pemasukan dari Pembayaran</p><h4 class="fw-bold">Rp {{ number_format($summary['paid_income'], 0, ',', '.') }}</h4></div></div></div>
        <div class="col-12 col-sm-6 col-md-3 mb-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><p class="text-muted mb-1">Pemasukan Manual</p><h4 class="fw-bold">Rp {{ number_format($summary['manual_income'], 0, ',', '.') }}</h4></div></div></div>
        <div class="col-12 col-sm-6 col-md-3 mb-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><p class="text-muted mb-1">Total Pengeluaran</p><h4 class="fw-bold text-danger">Rp {{ number_format($summary['expenses'], 0, ',', '.') }}</h4></div></div></div>
        <div class="col-12 col-sm-6 col-md-3 mb-3"><div class="card border-0 shadow-sm h-100"><div class="card-body"><p class="text-muted mb-1">Saldo Bersih</p><h4 class="fw-bold text-success">Rp {{ number_format($summary['balance'], 0, ',', '.') }}</h4></div></div></div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-1">Tambah Data Bookeeping</h4>
                    <p class="text-muted mb-0">Catat pemasukan manual, pengeluaran, pembelian alat, operasional, dan kebutuhan bengkel lainnya.</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <form method="POST" action="{{ backend_route('admin.ledger.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3 mb-3"><label class="fw-bold">Tanggal</label><input type="date" name="entry_date" class="form-control" value="{{ now()->toDateString() }}" required></div>
                            <div class="col-12 col-sm-6 col-md-3 mb-3"><label class="fw-bold">Tipe</label><select name="type" class="form-select" required><option value="income">Pemasukan</option><option value="expense">Pengeluaran</option></select></div>
                            <div class="col-12 col-sm-6 col-md-3 mb-3"><label class="fw-bold">Kategori</label><input type="text" name="category" class="form-control" placeholder="Contoh: beli alat, listrik, kas masuk" required></div>
                            <div class="col-12 col-sm-6 col-md-3 mb-3"><label class="fw-bold">Nominal</label><input type="number" name="amount" class="form-control" min="0" required></div>
                            <div class="col-md-12 mb-3"><label class="fw-bold">Keterangan</label><textarea name="description" class="form-control" rows="3" placeholder="Contoh: beli kompresor baru, bayar listrik bengkel, pemasukan jasa tambahan"></textarea></div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="border-radius: 999px;">Simpan Bookeeping</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-1">Ringkasan Cepat</h4>
                    <p class="text-muted mb-0">Biar owner cepat lihat sumber uang masuk dan belanja alat bengkel.</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="d-flex justify-content-between py-2 border-bottom"><span>Belanja alat & peralatan</span><strong>Rp {{ number_format($summary['equipment_expense'], 0, ',', '.') }}</strong></div>
                    <div class="d-flex justify-content-between py-2 border-bottom"><span>Total transaksi lunas</span><strong>{{ $paidTransactions->count() }}</strong></div>
                    <div class="d-flex justify-content-between py-2"><span>Total kategori expense</span><strong>{{ $expenseByCategory->count() }}</strong></div>

                    <div class="mt-4">
                        <h6 class="fw-bold">Pemasukan per Metode Bayar</h6>
                        @forelse($incomeByMethod as $item)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-uppercase">{{ $item->payment_method ?: '-' }}</span>
                                <strong>Rp {{ number_format($item->total, 0, ',', '.') }}</strong>
                            </div>
                        @empty
                            <div class="text-muted">Belum ada pembayaran lunas.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 px-4"><h4 class="fw-bold mb-0">Pengeluaran per Kategori</h4></div>
                <div class="card-body px-4 pb-4">
                    @forelse($expenseByCategory as $item)
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span>{{ $item->category }}</span>
                            <strong>Rp {{ number_format($item->total, 0, ',', '.') }}</strong>
                        </div>
                    @empty
                        <div class="text-muted">Belum ada pengeluaran tercatat.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 px-4"><h4 class="fw-bold mb-0">Tren Bulanan</h4></div>
                <div class="card-body px-4 pb-4">
                    @forelse($monthlyLedger as $item)
                        <div class="py-2 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $item->period)->translatedFormat('F Y') }}</strong>
                                <span class="text-success">Rp {{ number_format($item->income_total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <span class="text-muted">Pengeluaran</span>
                                <span class="text-danger">Rp {{ number_format($item->expense_total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">Belum ada tren bulanan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-1">Pembelian Alat / Peralatan</h4>
                    <p class="text-muted mb-0">Semua pengeluaran yang mengandung kata alat, peralatan, atau tools.</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead><tr><th>Tanggal</th><th>Kategori</th><th>Keterangan</th><th>Nominal</th><th>Input Oleh</th></tr></thead>
                            <tbody>
                                @forelse($equipmentExpenses as $entry)
                                    <tr data-search-row="1" data-search-text="{{ $entry->entry_date }} {{ $entry->category }} {{ $entry->description }} {{ $entry->amount }} {{ $entry->creator->name ?? '-' }}">
                                        <td>{{ \Carbon\Carbon::parse($entry->entry_date)->format('d M Y') }}</td>
                                        <td>{{ $entry->category }}</td>
                                        <td>{{ $entry->description ?: '-' }}</td>
                                        <td>Rp {{ number_format($entry->amount, 0, ',', '.') }}</td>
                                        <td>{{ $entry->creator->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Belum ada pembelian alat/peralatan tercatat.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold mb-1">Transaksi Lunas</h4>
                    <p class="text-muted mb-0">Pemasukan dari transaksi service yang sudah dibayar lunas.</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead><tr><th>ID</th><th>Pelanggan</th><th>Kendaraan</th><th>Metode</th><th>Total Bayar</th></tr></thead>
                            <tbody>
                                @forelse($paidTransactions as $transaction)
                                    <tr data-search-row="1" data-search-text="#TRX-{{ $transaction->id }} {{ $transaction->booking->vehicle->user->name ?? '-' }} {{ $transaction->booking->vehicle->brand ?? '-' }} {{ $transaction->booking->vehicle->license_plate ?? '-' }} {{ $transaction->payment->payment_method ?? '-' }} {{ $transaction->payment->amount_paid ?? 0 }}">
                                        <td>#TRX-{{ $transaction->id }}</td>
                                        <td>{{ $transaction->booking->vehicle->user->name ?? '-' }}</td>
                                        <td>{{ $transaction->booking->vehicle->brand ?? '-' }} {{ $transaction->booking->vehicle->license_plate ?? '' }}</td>
                                        <td class="text-uppercase">{{ $transaction->payment->payment_method ?? '-' }}</td>
                                        <td>Rp {{ number_format($transaction->payment->amount_paid ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Belum ada transaksi lunas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 px-4"><h4 class="fw-bold mb-0">Riwayat Bookeeping Lengkap</h4></div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead><tr><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Keterangan</th><th>Nominal</th><th>Input Oleh</th></tr></thead>
                            <tbody>
                                @forelse($entries as $entry)
                                    <tr data-search-row="1" data-search-text="{{ $entry->entry_date }} {{ $entry->type }} {{ $entry->category }} {{ $entry->description }} {{ $entry->amount }} {{ $entry->creator->name ?? '-' }}">
                                        <td>{{ \Carbon\Carbon::parse($entry->entry_date)->format('d M Y') }}</td>
                                        <td><span class="badge {{ $entry->type === 'income' ? 'bg-success' : 'bg-danger' }} text-uppercase">{{ $entry->type }}</span></td>
                                        <td>{{ $entry->category }}</td>
                                        <td>{{ $entry->description ?: '-' }}</td>
                                        <td>Rp {{ number_format($entry->amount, 0, ',', '.') }}</td>
                                        <td>{{ $entry->creator->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">Belum ada data bookeeping.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
