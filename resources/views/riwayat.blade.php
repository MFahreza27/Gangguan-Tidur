@extends('layouts.app')

@section('title', 'Riwayat Diagnosa')

@push('style')
    <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css') }}">
    <style>
        .patient-card {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            color: #fff;
            border-radius: 12px;
            padding: 24px 28px;
            margin-bottom: 24px;
        }
        .patient-card .label { font-size: .78rem; opacity: .75; text-transform: uppercase; letter-spacing: .5px; }
        .patient-card .value { font-size: 1rem; font-weight: 600; }

        .result-hero {
            text-align: center;
            padding: 20px 10px;
        }
        .result-hero .cf-circle {
            width: 110px; height: 110px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a73e8, #0d47a1);
            color: #fff;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            margin: 0 auto 12px;
            box-shadow: 0 6px 20px rgba(26,115,232,.35);
        }
        .result-hero .cf-circle .pct  { font-size: 1.6rem; font-weight: 700; line-height: 1; }
        .result-hero .cf-circle .lbl  { font-size: .65rem; opacity: .85; }
        .result-hero .disease-name    { font-size: 1.05rem; font-weight: 700; color: #1a73e8; }

        .disease-bar-row { margin-bottom: 10px; }
        .disease-bar-row .d-name { font-size: .85rem; color: #444; margin-bottom: 3px; }
        .disease-bar-row .progress { height: 8px; border-radius: 4px; }

        .riwayat-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 12px;
            background: #fff;
            transition: box-shadow .2s;
        }
        .riwayat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
        .riwayat-card .badge-penyakit {
            background: #e8f0fe;
            color: #1a73e8;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: .78rem;
            font-weight: 600;
        }
        .riwayat-card .pct-badge {
            font-size: .82rem;
            font-weight: 700;
            color: #fff;
            background: #1a73e8;
            border-radius: 20px;
            padding: 3px 10px;
        }
        .solusi-text {
            font-size: .82rem;
            color: #666;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush

@section('main')
<div class="content-wrapper">

    @php
        $latestRiwayat = $riwayat->isNotEmpty() ? $riwayat->first() : null;
        $diagnoses = collect($latestRiwayat->diagnoses ?? [])
            ->filter(fn($d) => ($d['hasil_cf'] ?? 0) > 0)
            ->sortByDesc('hasil_cf');
    @endphp

    {{-- Info Pasien --}}
    <div class="patient-card">
        <div class="row">
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="label">Nama Pasien</div>
                <div class="value">{{ Auth::user()->nama }}</div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="label">Instansi</div>
                <div class="value">{{ Auth::user()->instansi ?? '—' }}</div>
            </div>
            <div class="col-md-3 mb-3 mb-md-0">
                <div class="label">Diagnosa Terakhir</div>
                <div class="value">
                    {{ $latestRiwayat ? $latestRiwayat->created_at->translatedFormat('d F Y') : 'Belum ada' }}
                </div>
            </div>
            <div class="col-md-3 text-md-right">
                <a href="{{ route('diagnosa') }}" class="btn btn-light btn-sm px-3"
                   style="border-radius:20px; font-weight:600;">
                    <i class="mdi mdi-stethoscope mr-1"></i> Diagnosa Baru
                </a>
            </div>
        </div>
    </div>

    {{-- Hasil Diagnosa Terbaru --}}
    @if($latestRiwayat)
    <div class="row mb-4">
        {{-- CF Circle + Nama Penyakit --}}
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm rounded h-100">
                <div class="card-body result-hero">
                    <div class="cf-circle">
                        <span class="pct">{{ number_format($latestRiwayat->probability * 100, 1) }}%</span>
                        <span class="lbl">Keyakinan</span>
                    </div>
                    <div class="disease-name">{{ $latestRiwayat->disease }}</div>
                    <p class="text-muted mt-2" style="font-size:.78rem;">Hasil diagnosa terbaru</p>
                    <a href="{{ route('riwayat.cetak', $latestRiwayat->id) }}"
                       class="btn btn-outline-primary btn-sm mt-1 px-3" style="border-radius:20px;">
                        <i class="mdi mdi-printer mr-1"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- Bar semua penyakit --}}
        <div class="col-md-5 mb-3">
            <div class="card border-0 shadow-sm rounded h-100">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold mb-3">
                        <i class="mdi mdi-chart-bar text-primary mr-1"></i> Semua Hasil CF
                    </h6>
                    @forelse($diagnoses as $d)
                        <div class="disease-bar-row">
                            <div class="d-flex justify-content-between">
                                <span class="d-name">{{ $d['nama_penyakit'] }}</span>
                                <span style="font-size:.78rem; font-weight:700; color:#1a73e8;">
                                    {{ number_format($d['hasil_cf'] * 100, 1) }}%
                                </span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width:{{ $d['hasil_cf'] * 100 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted" style="font-size:.85rem;">Tidak ada data.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Deskripsi & Solusi --}}
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm rounded h-100">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold mb-2">
                        <i class="mdi mdi-information-outline text-info mr-1"></i> Deskripsi Penyakit
                    </h6>
                    <p style="font-size:.85rem; color:#555; line-height:1.6;">
                        {{ $latestRiwayat->penyakit->deskripsi ?? 'Tidak tersedia.' }}
                    </p>
                    <hr>
                    <h6 class="font-weight-bold mb-2">
                        <i class="mdi mdi-lightbulb-on text-warning mr-1"></i> Solusi
                    </h6>
                    <p style="font-size:.85rem; color:#555; line-height:1.6;">
                        {{ $latestRiwayat->solusi ?? 'Solusi tidak tersedia.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info rounded mb-4" style="border-left:4px solid #1a73e8;">
        <i class="mdi mdi-information-outline mr-2"></i>
        Anda belum pernah melakukan diagnosa.
        <a href="{{ route('diagnosa') }}" class="font-weight-bold ml-1">Mulai diagnosa sekarang →</a>
    </div>
    @endif

    {{-- Tabel Riwayat --}}
    <div class="card border-0 shadow-sm rounded">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="font-weight-bold mb-0">
                    <i class="mdi mdi-history text-primary mr-2"></i> Semua Riwayat Diagnosa
                </h5>
                <span class="badge badge-light text-muted px-3 py-2" style="border-radius:20px;">
                    {{ $riwayat->count() }} data
                </span>
            </div>

            @forelse($riwayat as $d)
            <div class="riwayat-card">
                <div class="d-flex align-items-start justify-content-between flex-wrap">
                    <div style="flex:1; min-width:0;">
                        <div class="d-flex align-items-center flex-wrap mb-1">
                            <span class="badge-penyakit mr-2">{{ $d->nama_penyakit ?? 'Belum Terdiagnosa' }}</span>
                            <span class="pct-badge mr-2">{{ number_format($d->probability * 100, 2) }}%</span>
                            <span class="text-muted" style="font-size:.78rem;">
                                <i class="mdi mdi-calendar-outline mr-1"></i>
                                {{ $d->created_at->translatedFormat('d F Y, H:i') }}
                            </span>
                        </div>
                        <p class="solusi-text mt-1 mb-0">{{ $d->solusi ?? 'Solusi tidak tersedia.' }}</p>
                    </div>
                    <div class="mt-2 mt-md-0 ml-3">
                        <a href="{{ route('riwayat.cetak', $d->id) }}"
                           class="btn btn-outline-primary btn-sm px-3" style="border-radius:20px; white-space:nowrap;">
                            <i class="mdi mdi-printer mr-1"></i> Cetak PDF
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class="mdi mdi-clipboard-text-outline" style="font-size:48px; opacity:.3;"></i>
                <p class="mt-2">Belum ada riwayat diagnosa.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@if(session('error'))
<script>
    Swal.fire({ icon:'error', title:'Gagal!', text:'{{ session("error") }}' });
</script>
@endif
@endsection
