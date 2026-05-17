@extends('layouts.app')

@section('title', 'SIGATUR — Dashboard')

@push('style')
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
@endpush

@section('main')
<div class="content-wrapper">

    {{-- Welcome Banner --}}
    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card rounded-0 border-0 shadow-sm"
                 style="background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%); color:#fff; padding: 30px 40px;">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h2 class="fw-bold mb-1" style="font-family:'Poppins',sans-serif; font-size:1.8rem;">
                            Selamat Datang, {{ $data1->nama }} 
                        </h2>
                        <p class="mb-0" style="opacity:.85; font-size:.95rem;">
                            Sistem Pakar Diagnosa Penyakit Gangguan Tidur &mdash; SIGATUR
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <span class="badge badge-light text-primary px-3 py-2" style="font-size:.85rem; border-radius:20px;">
                            <i class="mdi mdi-calendar mr-1"></i>
                            {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('view_admin')
    {{-- Stat Cards --}}
    <div class="row">
        @php
            $cards = [
                ['route' => 'admin.penyakit',        'icon' => 'mdi-heart-pulse',       'color' => 'danger',    'title' => 'Data Penyakit',    'count' => $penyakitCount],
                ['route' => 'admin.gejala',           'icon' => 'mdi-alert-circle',      'color' => 'warning',   'title' => 'Data Gejala',      'count' => $gejalaCount],
                ['route' => 'admin.index',            'icon' => 'mdi-account-multiple',  'color' => 'primary',   'title' => 'Data Pengguna',    'count' => $userCount],
                ['route' => 'admin.datariwayat.show', 'icon' => 'mdi-history',           'color' => 'info',      'title' => 'Riwayat Diagnosa', 'count' => $pasienCount],
                ['route' => 'admin.pertanyaan',       'icon' => 'mdi-comment-question',  'color' => 'success',   'title' => 'Data Pertanyaan',  'count' => $pertanyaanCount],
                ['route' => 'admin.solusi',           'icon' => 'mdi-lightbulb-on',      'color' => 'secondary', 'title' => 'Data Solusi',      'count' => $solusiCount],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-md-4 mb-4">
                <a href="{{ route($card['route']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm rounded h-100" style="transition: transform .15s, box-shadow .15s;"
                         onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,.12)'"
                         onmouseout="this.style.transform='';this.style.boxShadow=''">
                        <div class="card-body d-flex align-items-center p-4">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-4"
                                 style="width:56px;height:56px;min-width:56px;background:rgba(0,0,0,.05);">
                                <i class="mdi {{ $card['icon'] }} text-{{ $card['color'] }}" style="font-size:28px;"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-1" style="font-size:.82rem; font-weight:600; text-transform:uppercase; letter-spacing:.5px;">
                                    {{ $card['title'] }}
                                </p>
                                <h3 class="fw-bold text-dark mb-0">{{ $card['count'] }}</h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Chart Diagnosa per Penyakit --}}
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm rounded h-100">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-4">
                        <i class="mdi mdi-chart-bar text-primary mr-2"></i>Statistik Diagnosa per Penyakit
                    </h5>
                    <canvas id="chartDiagnosa" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm rounded h-100">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-4">
                        <i class="mdi mdi-chart-donut text-info mr-2"></i>Proporsi Diagnosa
                    </h5>
                    <canvas id="chartDonut" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @cannot('view_admin')
    {{-- User Dashboard --}}
    <div class="row">
        {{-- Card Riwayat --}}
        <div class="col-md-4 mb-4">
            <a href="{{ route('riwayat') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded"
                     onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,.12)'"
                     onmouseout="this.style.transform='';this.style.boxShadow=''"
                     style="transition:transform .15s,box-shadow .15s;">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-4"
                             style="width:56px;height:56px;min-width:56px;background:rgba(23,162,184,.1);">
                            <i class="mdi mdi-history text-info" style="font-size:28px;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1" style="font-size:.82rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">
                                Riwayat Diagnosa Anda
                            </p>
                            <h3 class="fw-bold text-dark mb-0">{{ $pasienCountuser }}</h3>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Shortcut Mulai Diagnosa --}}
        <div class="col-md-4 mb-4">
            <a href="{{ route('diagnosa') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded"
                     onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,.12)'"
                     onmouseout="this.style.transform='';this.style.boxShadow=''"
                     style="transition:transform .15s,box-shadow .15s; background:linear-gradient(135deg,#1a73e8,#0d47a1);">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-4"
                             style="width:56px;height:56px;min-width:56px;background:rgba(255,255,255,.2);">
                            <i class="mdi mdi-stethoscope" style="font-size:28px;color:#fff;"></i>
                        </div>
                        <div>
                            <p class="mb-1" style="font-size:.82rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:rgba(255,255,255,.8);">
                                Mulai Diagnosa
                            </p>
                            <p class="mb-0" style="color:#fff;font-size:.9rem;">Klik untuk memulai</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- Card Lihat PDF Riwayat --}}
        <div class="col-md-4 mb-4">
            <a href="{{ route('riwayat.pdf') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded"
                     onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,.12)'"
                     onmouseout="this.style.transform='';this.style.boxShadow=''"
                     style="transition:transform .15s,box-shadow .15s;">
                    <div class="card-body d-flex align-items-center p-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-4"
                             style="width:56px;height:56px;min-width:56px;background:rgba(40,167,69,.1);">
                            <i class="mdi mdi-file-pdf-box text-success" style="font-size:28px;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-1" style="font-size:.82rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">
                                Unduh Laporan PDF
                            </p>
                            <p class="text-dark mb-0" style="font-size:.9rem;">Cetak hasil diagnosa</p>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endcannot

</div>
@endsection

@push('script')
<script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>
@can('view_admin')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels = @json($statistik->pluck('nama_penyakit'));
    const counts = @json($statistik->pluck('jumlah'));

    const palette = [
        '#1a73e8','#e53935','#43a047','#fb8c00',
        '#8e24aa','#00acc1','#f4511e','#6d4c41',
    ];

    // Bar chart
    new Chart(document.getElementById('chartDiagnosa').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Diagnosa',
                data: counts,
                backgroundColor: palette,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { ticks: { maxRotation: 30 } }
            }
        }
    });

    // Donut chart
    new Chart(document.getElementById('chartDonut').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: counts,
                backgroundColor: palette,
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 10 } }
            },
            cutout: '65%',
        }
    });
});
</script>
@endcan
@endpush
