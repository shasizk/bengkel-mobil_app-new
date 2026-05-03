@extends('be.master')

@section('Transaction')
<div class="container-fluid" style="margin-top: 30px;">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #0f172a, #0f766e);">
                    <div>
                        <h4 class="fw-bold mb-1">Form Kasir - Transaksi Baru</h4>
                        <small style="color: rgba(255,255,255,.75);">Simpan transaksi dulu, lalu tampilkan invoice dan QRIS di halaman detail pembayaran.</small>
                    </div>
                    <span>{{ date('d F Y') }}</span>
                </div>
                <form action="{{ backend_route('admin.transactions.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            {{-- BAGIAN DATA UTAMA --}}
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold small text-muted">KENDARAAN & BOOKING</label>
                                <select name="booking_id" id="booking_id" class="form-select border-primary" required>
                                    <option value="">-- Pilih Kendaraan --</option>
                                    @foreach($bookings as $b)
                                        <option
                                            value="{{ $b->id }}"
                                            data-service-total="{{ (int) $b->service_total }}"
                                            data-complaint="{{ $b->complaint }}"
                                            data-services="{{ implode(', ', $b->service_summary) }}"
                                            data-payment-preference="{{ $b->payment_preference ?? 'cash' }}"
                                            data-mekanik-id="{{ $b->mekanik_id ?? '' }}"
                                            data-mekanik-name="{{ $b->mechanic_name ?? '' }}"
                                            {{ $selected_id == $b->id ? 'selected' : '' }}
                                        >
                                            #{{ $b->id }} - {{ $b->vehicle->license_plate }} ({{ $b->vehicle->brand }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold small text-muted">MEKANIK YANG MENANGANI</label>
                                <input type="text" id="mekanik_name" class="form-control border-primary" value="-" readonly>
                                <input type="hidden" name="mekanik_id" id="mekanik_id" value="">
                                <small class="text-danger d-none" id="mekanik_warning">Booking ini belum memiliki mekanik penanggung jawab.</small>
                            </div>

                            <hr class="my-4">

                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <label class="fw-bold small text-muted">LAYANAN BOOKING</label>
                                        <div id="booking_services_text" class="fw-bold text-dark">Pilih booking untuk melihat layanan.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light border-0 h-100">
                                    <div class="card-body">
                                        <label class="fw-bold small text-muted">KELUHAN CUSTOMER</label>
                                        <div id="booking_complaint_text" class="text-dark">Belum ada data booking dipilih.</div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- BAGIAN INPUT SPAREPART --}}
                            <div class="col-12">
                                <h5 class="fw-bold mb-3 text-primary"><i class="mdi mdi-nut"></i> Item Sparepart</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="sparepartTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="45%">Nama Barang</th>
                                                <th width="15%">Qty</th>
                                                <th width="20%">Harga (Satuan)</th>
                                                <th width="20%">Subtotal</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="sparepartRows">
                                            {{-- Baris akan ditambah via JS --}}
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" id="addSparepart">
                                                        + Tambah Item Sparepart
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- BAGIAN TOTALAN --}}
                            <div class="col-md-7">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h5 class="fw-bold text-dark mb-3">Metode Pembayaran</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="small fw-bold">Pilih Metode</label>
                                                <select name="payment_method" id="payment_method" class="form-select mb-3" required>
                                                    <option value="cash">Cash (Tunai)</option>
                                                    <option value="transfer">Midtrans Transfer / Debit</option>
                                                    <option value="qris">Midtrans QRIS</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small fw-bold">Status Bayar</label>
                                                <select name="payment_status" id="payment_status" class="form-select" required>
                                                    <option value="unpaid">Belum Bayar</option>
                                                    <option value="paid">Lunas (Paid)</option>
                                                    <option value="partial">DP / Sebagian</option>
                                                </select>
                                            </div>
                                        </div>
                                    <div class="alert alert-info mb-0 mt-2" id="qris_notice" style="display:none; border-radius: 16px;">
                                            Untuk pembayaran Midtrans, status akan otomatis diset <strong>Belum Bayar</strong> sampai callback Midtrans berhasil masuk. Link Snap dan kode bayar akan muncul di halaman invoice setelah transaksi dibuat.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group mb-2">
                                    <label class="fw-bold">Biaya Jasa Service (Rp)</label>
                                    <input type="number" name="total_service" id="total_service" class="form-control form-control-lg text-end fw-bold" value="0" min="0">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="fw-bold">Total Sparepart (Rp)</label>
                                    <input type="number" name="total_sparepart" id="total_sparepart" class="form-control form-control-lg text-end fw-bold bg-light" value="0" readonly>
                                </div>
                                <div class="form-group mt-3 pt-3 border-top">
                                    <h3 class="fw-bold text-primary d-flex justify-content-between">
                                        <span>Grand Total:</span>
                                        <span id="grand_total_display">Rp 0</span>
                                    </h3>
                                    <input type="hidden" name="grand_total" id="grand_total_input">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-end py-3">
                        <a href="{{ backend_route('admin.transactions.index') }}" class="btn btn-light px-4 me-2">Batal</a>
                        <button type="submit" id="submit_transaction" class="btn btn-primary px-5 fw-bold">Simpan & Proses Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT UNTUK LOGIKA HITUNG OTOMATIS --}}
<script>
document.getElementById('addSparepart').addEventListener('click', function() {
    const row = `
        <tr>
            <td>
                <select name="sparepart_id[]" class="form-select sparepart-select" required onchange="updatePrice(this)">
                    <option value="" data-price="0">-- Pilih Barang --</option>
                    @foreach($spareparts as $s)
                        <option value="{{ $s->id }}" data-price="{{ $s->price }}">{{ $s->name }} (Stok: {{ $s->stock }})</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="qty[]" class="form-control qty-input" value="1" min="1" onchange="calculateRow(this)"></td>
            <td><input type="number" name="price[]" class="form-control price-input" readonly></td>
            <td><input type="number" name="subtotal[]" class="form-control subtotal-input" readonly value="0"></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); calculateTotal();">X</button></td>
        </tr>
    `;
    document.getElementById('sparepartRows').insertAdjacentHTML('beforeend', row);
});

function updatePrice(select) {
    const price = select.options[select.selectedIndex].getAttribute('data-price');
    const row = select.closest('tr');
    row.querySelector('.price-input').value = price;
    calculateRow(row.querySelector('.qty-input'));
}

function calculateRow(input) {
    const row = input.closest('tr');
    const qty = row.querySelector('.qty-input').value;
    const price = row.querySelector('.price-input').value;
    const subtotal = qty * price;
    row.querySelector('.subtotal-input').value = subtotal;
    calculateTotal();
}

function calculateTotal() {
    let totalSparepart = 0;
    document.querySelectorAll('.subtotal-input').forEach(input => {
        totalSparepart += parseFloat(input.value) || 0;
    });

    const totalService = parseFloat(document.getElementById('total_service').value) || 0;
    const grandTotal = totalSparepart + totalService;

    document.getElementById('total_sparepart').value = totalSparepart;
    document.getElementById('grand_total_input').value = grandTotal;
    document.getElementById('grand_total_display').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
}

document.getElementById('total_service').addEventListener('input', calculateTotal);
document.getElementById('booking_id').addEventListener('change', updateBookingInfo);

const paymentMethodSelect = document.getElementById('payment_method');
const paymentStatusSelect = document.getElementById('payment_status');
const qrisNotice = document.getElementById('qris_notice');
const mechanicNameInput = document.getElementById('mekanik_name');
const mechanicIdInput = document.getElementById('mekanik_id');
const mechanicWarning = document.getElementById('mekanik_warning');
const submitTransactionButton = document.getElementById('submit_transaction');

function updateBookingInfo() {
    const select = document.getElementById('booking_id');
    const option = select.options[select.selectedIndex];
    const serviceTotal = option ? option.getAttribute('data-service-total') : 0;
    const complaint = option ? option.getAttribute('data-complaint') : '';
    const services = option ? option.getAttribute('data-services') : '';
    const paymentPreference = option ? option.getAttribute('data-payment-preference') : 'cash';
    const mechanicId = option ? option.getAttribute('data-mekanik-id') : '';
    const mechanicName = option ? option.getAttribute('data-mekanik-name') : '';

    document.getElementById('total_service').value = serviceTotal || 0;
    document.getElementById('booking_services_text').innerText = services || 'Pilih booking untuk melihat layanan.';
    document.getElementById('booking_complaint_text').innerText = complaint || 'Belum ada data booking dipilih.';
    paymentMethodSelect.value = paymentPreference || 'cash';

    mechanicIdInput.value = mechanicId || '';
    mechanicNameInput.value = mechanicName || '-';

    const hasMechanic = !!mechanicId;
    mechanicWarning.classList.toggle('d-none', hasMechanic);
    submitTransactionButton.disabled = !hasMechanic;

    syncPaymentFormState();
    calculateTotal();
}

updateBookingInfo();

function syncPaymentFormState() {
    const isGateway = ['qris', 'transfer'].includes(paymentMethodSelect.value);

    if (isGateway) {
        paymentStatusSelect.value = 'unpaid';
        paymentStatusSelect.classList.add('bg-light');
        qrisNotice.style.display = 'block';
    } else {
        paymentStatusSelect.classList.remove('bg-light');
        qrisNotice.style.display = 'none';
    }
}

paymentMethodSelect.addEventListener('change', syncPaymentFormState);
paymentStatusSelect.addEventListener('change', function () {
    if (['qris', 'transfer'].includes(paymentMethodSelect.value)) {
        paymentStatusSelect.value = 'unpaid';
    }
});
syncPaymentFormState();
</script>
@endsection
