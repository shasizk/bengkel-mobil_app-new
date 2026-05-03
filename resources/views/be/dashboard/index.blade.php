@extends('be.master')
@section('Dashboard')  
@php($role = $backendAuthUser->role ?? null)
@php($chartPayload = $chartData ?? [])

<div class="container">
  <div class="page-inner">
    <style>
      .dashboard-hero-card {
        border: 0;
        border-radius: 28px;
        background:
          radial-gradient(circle at top right, rgba(56, 189, 248, 0.24), transparent 28%),
          linear-gradient(135deg, #0f172a 0%, #12324a 55%, #0f766e 100%);
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.16);
      }
      .dashboard-chart-card {
        border: 0;
        border-radius: 26px;
        overflow: hidden;
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.08);
      }
      .dashboard-chart-card .card-header {
        border-bottom: 0;
        padding: 1.2rem 1.35rem 0;
        background: #fff;
      }
      .dashboard-chart-card .card-body {
        padding: 1.1rem 1.35rem 1.35rem;
      }
      .chart-soft-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        background: rgba(255,255,255,.14);
      }
      .chart-canvas-wrap {
        position: relative;
        height: 320px;
      }
      .chart-canvas-wrap--sm {
        position: relative;
        height: 280px;
      }
    </style>
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-2">Dashboard {{ ucfirst($role) }}</h3>
        <h6 class="op-7 mb-2">Ringkasan bengkel dari data transaksi, booking, kendaraan, dan sparepart.</h6>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round"><div class="card-body"><p class="card-category">Total Booking</p><h4 class="card-title">{{ $stats['total_bookings'] }}</h4><small class="text-muted">{{ $stats['pending_bookings'] }} pending</small></div></div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round"><div class="card-body"><p class="card-category">Sedang Dikerjakan</p><h4 class="card-title">{{ $stats['progress_bookings'] }}</h4><small class="text-muted">{{ $stats['completed_bookings'] }} selesai</small></div></div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round"><div class="card-body"><p class="card-category">{{ $role === 'owner' ? 'Total Transaksi' : 'Pemasukan Lunas' }}</p><h4 class="card-title">{{ $role === 'owner' ? $stats['transactions'] : 'Rp '.number_format($stats['paid_income'], 0, ',', '.') }}</h4><small class="text-muted">{{ $role === 'owner' ? 'Lihat detail finansial di menu Bookeeping' : $stats['transactions'].' transaksi' }}</small></div></div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round"><div class="card-body"><p class="card-category">Stok Menipis</p><h4 class="card-title">{{ $stats['low_stock'] }}</h4><small class="text-muted">{{ $stats['vehicles'] }} kendaraan terdaftar</small></div></div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-lg-8 mb-4">
        <div class="card dashboard-hero-card text-white h-100">
          <div class="card-body p-4 p-lg-5">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
              <div class="pe-lg-4">
                <span class="chart-soft-badge mb-3">Bengkel Insight</span>
                <h2 class="fw-bold mb-2">Performa booking dan pemasukan terasa lebih hidup.</h2>
                <p class="mb-0" style="max-width: 700px; color: rgba(255,255,255,.78);">
                  Grafik di bawah merangkum 6 bulan terakhir supaya admin, owner, kasir, dan mekanik bisa membaca ritme operasional bengkel dengan cepat.
                </p>
              </div>
              <div class="mt-4 mt-lg-0 text-lg-end">
                <div class="small text-uppercase mb-2" style="letter-spacing: .2em; color: rgba(255,255,255,.6);">Pending Income</div>
                <div class="fs-2 fw-bold">Rp {{ number_format($stats['pending_income'], 0, ',', '.') }}</div>
                <div style="color: rgba(255,255,255,.72);">Belum lunas dari transaksi aktif</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mb-4">
        <div class="card dashboard-chart-card h-100">
          <div class="card-header">
            <h4 class="card-title mb-1">Distribusi Role</h4>
            <p class="text-muted mb-0">Komposisi akun internal dan customer</p>
          </div>
          <div class="card-body">
            <div class="chart-canvas-wrap--sm">
              <canvas id="roleDistributionChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8 mb-4">
        <div class="card dashboard-chart-card h-100">
          <div class="card-header">
            <h4 class="card-title mb-1">Trend Booking & Pemasukan</h4>
            <p class="text-muted mb-0">Pergerakan 6 bulan terakhir dalam satu panel</p>
          </div>
          <div class="card-body">
            <div class="chart-canvas-wrap">
              <canvas id="bookingIncomeChart"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 mb-4">
        <div class="card dashboard-chart-card h-100">
          <div class="card-header">
            <h4 class="card-title mb-1">Status Booking</h4>
            <p class="text-muted mb-0">Distribusi fase pekerjaan bengkel</p>
          </div>
          <div class="card-body">
            <div class="chart-canvas-wrap--sm">
              <canvas id="bookingStatusChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-8">
        <div class="card card-round">
          <div class="card-header"><h4 class="card-title mb-0">Booking Terbaru</h4></div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead><tr><th>ID</th><th>Customer</th><th>Kendaraan</th><th>Layanan</th><th>Status</th></tr></thead>
                <tbody>
                  @forelse($recentBookings as $booking)
                    <tr>
                      <td>#{{ $booking->id }}</td>
                      <td>{{ $booking->user->name ?? '-' }}</td>
                      <td>{{ $booking->vehicle->brand ?? '-' }} {{ $booking->vehicle->model ?? '' }}</td>
                      <td>{{ $booking->services->pluck('service.service_name')->filter()->implode(', ') ?: '-' }}</td>
                      <td><span class="badge bg-light text-dark text-uppercase">{{ str_replace('_', ' ', $booking->status) }}</span></td>
                    </tr>
                  @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada booking.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="card card-round">
          <div class="card-header"><h4 class="card-title mb-0">Ringkasan Role</h4></div>
          <div class="card-body">
            <div class="d-flex justify-content-between py-2 border-bottom"><span>Admin</span><strong>{{ $staffSummary['admin'] ?? 0 }}</strong></div>
            <div class="d-flex justify-content-between py-2 border-bottom"><span>Mekanik</span><strong>{{ $staffSummary['mekanik'] ?? 0 }}</strong></div>
            <div class="d-flex justify-content-between py-2 border-bottom"><span>Kasir</span><strong>{{ $staffSummary['kasir'] ?? 0 }}</strong></div>
            <div class="d-flex justify-content-between py-2 border-bottom"><span>Owner</span><strong>{{ $staffSummary['owner'] ?? 0 }}</strong></div>
            <div class="d-flex justify-content-between py-2"><span>Customer</span><strong>{{ $staffSummary['customer'] ?? 0 }}</strong></div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-4">
        <div class="card card-round"><div class="card-header"><h4 class="card-title mb-0">Brand Paling Sering Servis</h4></div><div class="card-body">@forelse($topVehicleBrands as $item)<div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $item->brand ?: 'Tanpa brand' }}</span><strong>{{ $item->total }} booking</strong></div>@empty<div class="text-muted">Belum ada data.</div>@endforelse</div></div>
      </div>
      <div class="col-lg-4">
        <div class="card card-round"><div class="card-header"><h4 class="card-title mb-0">Layanan Terlaris</h4></div><div class="card-body">@forelse($topServices as $item)<div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $item->service_name }}</span><strong>{{ $item->total }}x</strong></div>@empty<div class="text-muted">Belum ada data.</div>@endforelse</div></div>
      </div>
      <div class="col-lg-4">
        <div class="card card-round"><div class="card-header"><h4 class="card-title mb-0">Sparepart Paling Laku</h4></div><div class="card-body">@forelse($topSpareparts as $item)<div class="d-flex justify-content-between py-2 border-bottom"><span>{{ $item->name }}</span><strong>{{ $item->total_qty }} pcs</strong></div>@empty<div class="text-muted">Belum ada data.</div>@endforelse</div></div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="card card-round">
          <div class="card-header"><h4 class="card-title mb-0">Transaksi Terbaru</h4></div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead><tr><th>ID</th><th>Pelanggan</th><th>Mekanik</th><th>Kasir</th><th>Total</th><th>Pembayaran</th></tr></thead>
                <tbody>
                  @forelse($recentTransactions as $transaction)
                    <tr>
                      <td>#TRX-{{ $transaction->id }}</td>
                      <td>{{ $transaction->booking->vehicle->user->name ?? '-' }}</td>
                      <td>{{ $transaction->mekanik->name ?? '-' }}</td>
                      <td>{{ $transaction->kasir->name ?? '-' }}</td>
                      <td>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                      <td><span class="badge bg-light text-dark text-uppercase">{{ $transaction->payment->payment_method ?? '-' }} / {{ $transaction->payment->payment_status ?? '-' }}</span></td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada transaksi.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@push('page-scripts')
<script>
  (function () {
    const initDashboardCharts = function () {
      if (typeof Chart === 'undefined') {
        return;
      }

      const chartData = @json($chartPayload);
      const defaultGrid = {
        color: 'rgba(148, 163, 184, 0.16)',
        drawBorder: false,
        zeroLineColor: 'rgba(148, 163, 184, 0.16)',
      };

      const bookingIncomeCanvas = document.getElementById('bookingIncomeChart');
      if (bookingIncomeCanvas && chartData.months) {
        new Chart(bookingIncomeCanvas.getContext('2d'), {
          type: 'line',
          data: {
            labels: chartData.months,
            datasets: [
              {
                label: 'Booking',
                data: chartData.booking_trend,
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.12)',
                fill: true,
                tension: 0.38,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#0ea5e9',
              },
              {
                label: 'Pemasukan Lunas',
                data: chartData.income_trend,
                borderColor: '#14b8a6',
                backgroundColor: 'rgba(20, 184, 166, 0.10)',
                fill: true,
                tension: 0.38,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: '#14b8a6',
                yAxisID: 'y1',
              }
            ]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            tooltips: {
              mode: 'index',
              intersect: false,
            },
            hover: {
              mode: 'nearest',
              intersect: true,
            },
            legend: {
              labels: {
                usePointStyle: true,
                boxWidth: 10,
                fontColor: '#334155',
              }
            },
            scales: {
              xAxes: [{
                gridLines: { display: false },
                ticks: { fontColor: '#64748b' }
              }],
              yAxes: [
                {
                  id: 'y-booking',
                  position: 'left',
                  ticks: {
                    beginAtZero: true,
                    fontColor: '#64748b',
                    precision: 0
                  },
                  gridLines: defaultGrid
                },
                {
                  id: 'y1',
                  position: 'right',
                  ticks: {
                    beginAtZero: true,
                    fontColor: '#64748b',
                    callback: function (value) {
                      return 'Rp ' + Number(value).toLocaleString('id-ID');
                    }
                  },
                  gridLines: { display: false }
                }
              ]
            }
          }
        });
      }

      const bookingStatusCanvas = document.getElementById('bookingStatusChart');
      if (bookingStatusCanvas && chartData.booking_status_labels) {
        new Chart(bookingStatusCanvas.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: chartData.booking_status_labels,
            datasets: [{
              data: chartData.booking_status_values,
              backgroundColor: ['#38bdf8', '#818cf8', '#f59e0b', '#10b981', '#fb7185'],
              borderWidth: 0,
              hoverOffset: 6,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutoutPercentage: 68,
            legend: {
              position: 'bottom',
              labels: {
                usePointStyle: true,
                boxWidth: 10,
                padding: 18,
                fontColor: '#334155',
              }
            }
          }
        });
      }

      const roleDistributionCanvas = document.getElementById('roleDistributionChart');
      if (roleDistributionCanvas && chartData.role_labels) {
        new Chart(roleDistributionCanvas.getContext('2d'), {
          type: 'polarArea',
          data: {
            labels: chartData.role_labels,
            datasets: [{
              data: chartData.role_values,
              backgroundColor: [
                'rgba(14, 165, 233, 0.78)',
                'rgba(20, 184, 166, 0.72)',
                'rgba(245, 158, 11, 0.72)',
                'rgba(99, 102, 241, 0.72)',
                'rgba(248, 113, 113, 0.72)'
              ],
              borderWidth: 0,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scale: {
              gridLines: { color: 'rgba(148, 163, 184, 0.16)' },
              ticks: { display: false }
            },
            legend: {
              position: 'bottom',
              labels: {
                usePointStyle: true,
                boxWidth: 10,
                padding: 16,
                fontColor: '#334155',
              }
            }
          }
        });
      }
    };

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initDashboardCharts);
    } else {
      initDashboardCharts();
    }
  })();
</script>
@endpush

@if(false)


<div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">Free Bootstrap 5 Admin Dashboard</h6>
              </div>
              <div class="ms-md-auto py-2 py-md-0">
                <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                <a href="#" class="btn btn-primary btn-round">Add Customer</a>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-primary bubble-shadow-small"
                        >
                          <i class="fas fa-users"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Visitors</p>
                          <h4 class="card-title">1,294</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-info bubble-shadow-small"
                        >
                          <i class="fas fa-user-check"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Subscribers</p>
                          <h4 class="card-title">1303</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-success bubble-shadow-small"
                        >
                          <i class="fas fa-luggage-cart"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Sales</p>
                          <h4 class="card-title">$ 1,345</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-secondary bubble-shadow-small"
                        >
                          <i class="far fa-check-circle"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Order</p>
                          <h4 class="card-title">576</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">User Statistics</div>
                      <div class="card-tools">
                        <a
                          href="#"
                          class="btn btn-label-success btn-round btn-sm me-2"
                        >
                          <span class="btn-label">
                            <i class="fa fa-pencil"></i>
                          </span>
                          Export
                        </a>
                        <a href="#" class="btn btn-label-info btn-round btn-sm">
                          <span class="btn-label">
                            <i class="fa fa-print"></i>
                          </span>
                          Print
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                      <canvas id="statisticsChart"></canvas>
                    </div>
                    <div id="myChartLegend"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card card-primary card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Daily Sales</div>
                      <div class="card-tools">
                        <div class="dropdown">
                          <button
                            class="btn btn-sm btn-label-light dropdown-toggle"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                          >
                            Export
                          </button>
                          <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                          >
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#"
                              >Something else here</a
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-category">March 25 - April 02</div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <h1>$4,578.58</h1>
                    </div>
                    <div class="pull-in">
                      <canvas id="dailySalesChart"></canvas>
                    </div>
                  </div>
                </div>
                <div class="card card-round">
                  <div class="card-body pb-0">
                    <div class="h1 fw-bold float-end text-primary">+5%</div>
                    <h2 class="mb-2">17</h2>
                    <p class="text-muted">Users online</p>
                    <div class="pull-in sparkline-fix">
                      <div id="lineChart"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                      <h4 class="card-title">Users Geolocation</h4>
                      <div class="card-tools">
                        <button
                          class="btn btn-icon btn-link btn-primary btn-xs"
                        >
                          <span class="fa fa-angle-down"></span>
                        </button>
                        <button
                          class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"
                        >
                          <span class="fa fa-sync-alt"></span>
                        </button>
                        <button
                          class="btn btn-icon btn-link btn-primary btn-xs"
                        >
                          <span class="fa fa-times"></span>
                        </button>
                      </div>
                    </div>
                    <p class="card-category">
                      Map of the distribution of users around the world
                    </p>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="table-responsive table-hover table-sales">
                          <table class="table">
                            <tbody>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="{{asset('be/assets/img/flags/id.png')}}"
                                      alt="indonesia"
                                    />
                                  </div>
                                </td>
                                <td>Indonesia</td>
                                <td class="text-end">2.320</td>
                                <td class="text-end">42.18%</td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="{{asset('be/assets/img/flags/us.png')}}"
                                      alt="united states"
                                    />
                                  </div>
                                </td>
                                <td>USA</td>
                                <td class="text-end">240</td>
                                <td class="text-end">4.36%</td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="{{asset('be/assets/img/flags/au.png')}}"
                                      alt="australia"
                                    />
                                  </div>
                                </td>
                                <td>Australia</td>
                                <td class="text-end">119</td>
                                <td class="text-end">2.16%</td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="{{asset('be/assets/img/flags/ru.png')}}"
                                      alt="russia"
                                    />
                                  </div>
                                </td>
                                <td>Russia</td>
                                <td class="text-end">1.081</td>
                                <td class="text-end">19.65%</td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="{{asset('be/assets/img/flags/cn.png')}}"
                                      alt="china"
                                    />
                                  </div>
                                </td>
                                <td>China</td>
                                <td class="text-end">1.100</td>
                                <td class="text-end">20%</td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="{{asset('be/assets/img/flags/br.png')}}"
                                      alt="brazil"
                                    />
                                  </div>
                                </td>
                                <td>Brasil</td>
                                <td class="text-end">640</td>
                                <td class="text-end">11.63%</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mapcontainer">
                          <div
                            id="world-map"
                            class="w-100"
                            style="height: 300px"
                          ></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="card card-round">
                  <div class="card-body">
                    <div class="card-head-row card-tools-still-right">
                      <div class="card-title">New Customers</div>
                      <div class="card-tools">
                        <div class="dropdown">
                          <button
                            class="btn btn-icon btn-clean me-0"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                          >
                            <i class="fas fa-ellipsis-h"></i>
                          </button>
                          <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                          >
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#"
                              >Something else here</a
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-list py-4">
                      <div class="item-list">
                        <div class="avatar">
                          <img
                            src="{{asset('be/assets/img/jm_denis.jpg')}}"
                            alt="..."
                            class="avatar-img rounded-circle"
                          />
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Jimmy Denis</div>
                          <div class="status">Graphic Designer</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <span
                            class="avatar-title rounded-circle border border-white"
                            >CF</span
                          >
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Chandra Felix</div>
                          <div class="status">Sales Promotion</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <img
                            src="{{asset('be/assets/img/talha.jpg')}}"
                            alt="..."
                            class="avatar-img rounded-circle"
                          />
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Talha</div>
                          <div class="status">Front End Designer</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <img
                            src="{{asset('be/assets/img/chadengle.jpg')}}"
                            alt="..."
                            class="avatar-img rounded-circle"
                          />
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Chad</div>
                          <div class="status">CEO Zeleaf</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <span
                            class="avatar-title rounded-circle border border-white bg-primary"
                            >H</span
                          >
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Hizrian</div>
                          <div class="status">Web Designer</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <span
                            class="avatar-title rounded-circle border border-white bg-secondary"
                            >F</span
                          >
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Farrah</div>
                          <div class="status">Marketing</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                      <div class="card-title">Transaction History</div>
                      <div class="card-tools">
                        <div class="dropdown">
                          <button
                            class="btn btn-icon btn-clean me-0"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                          >
                            <i class="fas fa-ellipsis-h"></i>
                          </button>
                          <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                          >
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#"
                              >Something else here</a
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                      <!-- Projects table -->
                      <table class="table align-items-center mb-0">
                        <thead class="thead-light">
                          <tr>
                            <th scope="col">Payment Number</th>
                            <th scope="col" class="text-end">Date & Time</th>
                            <th scope="col" class="text-end">Amount</th>
                            <th scope="col" class="text-end">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
@endif
@endsection
