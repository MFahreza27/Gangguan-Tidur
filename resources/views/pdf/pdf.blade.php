<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Diagnosa — SIGATUR</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #222;
            margin: 30px 40px;
        }

        /* ── KOP SURAT ── */
        .kop {
            display: table;
            width: 100%;
            border-bottom: 3px solid #1a73e8;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .kop-logo { display: table-cell; width: 80px; vertical-align: middle; }
        .kop-logo img { width: 70px; }
        .kop-text { display: table-cell; vertical-align: middle; padding-left: 14px; }
        .kop-text h1 { font-size: 16px; color: #1a73e8; letter-spacing: 1px; margin-bottom: 2px; }
        .kop-text p  { font-size: 10px; color: #555; line-height: 1.5; }

        /* ── JUDUL ── */
        .judul {
            text-align: center;
            margin: 14px 0 10px;
        }
        .judul h2 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #ccc;
            display: inline-block;
            padding-bottom: 4px;
        }

        /* ── INFO PASIEN ── */
        .info-table { width: 100%; margin-bottom: 14px; }
        .info-table td { padding: 3px 6px; font-size: 12px; }
        .info-table td:first-child { width: 130px; font-weight: bold; color: #444; }

        /* ── TABEL HASIL ── */
        table.hasil {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.hasil th {
            background-color: #1a73e8;
            color: #fff;
            padding: 7px 10px;
            text-align: left;
            font-size: 11px;
        }
        table.hasil td {
            border: 1px solid #ddd;
            padding: 6px 10px;
            font-size: 11px;
            vertical-align: top;
        }
        table.hasil tr:nth-child(even) td { background-color: #f7f9ff; }

        /* ── PROGRESS BAR ── */
        .bar-wrap { background: #e9ecef; border-radius: 4px; height: 10px; width: 100%; }
        .bar-fill  { height: 10px; border-radius: 4px; background: #1a73e8; }

        /* ── GEJALA ── */
        .gejala-list { font-size: 10px; color: #555; margin-top: 3px; }
        .gejala-list li { margin-bottom: 2px; }

        /* ── SOLUSI ── */
        .solusi-box {
            background: #f0f7ff;
            border-left: 4px solid #1a73e8;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 11px;
            line-height: 1.6;
        }
        .solusi-box h4 { font-size: 12px; color: #1a73e8; margin-bottom: 6px; }

        /* ── TANDA TANGAN ── */
        .ttd-wrap { margin-top: 30px; text-align: right; }
        .ttd-wrap p { font-size: 11px; margin-bottom: 50px; }
        .ttd-wrap .nama { font-weight: bold; text-decoration: underline; font-size: 12px; }
        .ttd-wrap .nip  { font-size: 10px; color: #555; }

        /* ── FOOTER ── */
        .footer-line {
            border-top: 1px solid #ccc;
            margin-top: 20px;
            padding-top: 6px;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>
<body>

@if($riwayat->isNotEmpty())
@php
    $item = $riwayat->sortByDesc('created_at')->first();
    $diagnoses = $item->diagnoses ?? [];
    // Urutkan dari CF tertinggi
    usort($diagnoses, fn($a, $b) => ($b['hasil_cf'] ?? 0) <=> ($a['hasil_cf'] ?? 0));
@endphp

{{-- KOP SURAT --}}
<div class="kop">
    <div class="kop-logo">
        <img src="{{ public_path('storage/gatur.png') }}" alt="SIGATUR">
    </div>
    <div class="kop-text">
        <h1>SIGATUR</h1>
        <p>
            Sistem Pakar Diagnosa Penyakit Gangguan Tidur<br>
            Berbasis Metode Certainty Factor
        </p>
    </div>
</div>

{{-- JUDUL --}}
<div class="judul">
    <h2>Laporan Hasil Diagnosa</h2>
</div>

{{-- INFO PASIEN --}}
<table class="info-table">
    <tr>
        <td>Nama Pasien</td>
        <td>: <strong>{{ $item->user->nama ?? 'Tidak Diketahui' }}</strong></td>
    </tr>
    <tr>
        <td>Instansi</td>
        <td>: {{ $item->user->instansi ?? '-' }}</td>
    </tr>
    <tr>
        <td>Tanggal Diagnosa</td>
        <td>: {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y, H:i') }} WIB</td>
    </tr>
    <tr>
        <td>Tanggal Cetak</td>
        <td>: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</td>
    </tr>
</table>

{{-- TABEL HASIL DIAGNOSA --}}
<table class="hasil">
    <thead>
        <tr>
            <th style="width:5%">No</th>
            <th style="width:28%">Penyakit Terdiagnosa</th>
            <th style="width:15%">Nilai CF</th>
            <th style="width:20%">Probabilitas</th>
            <th style="width:32%">Gejala yang Dipilih</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($diagnoses as $i => $disease)
            @if(($disease['hasil_cf'] ?? 0) > 0)
            <tr>
                <td style="text-align:center;">{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $disease['nama_penyakit'] }}</strong><br>
                    <span style="color:#888;font-size:10px;">{{ $disease['kode_penyakit'] ?? '' }}</span>
                </td>
                <td style="text-align:center;">
                    {{ number_format($disease['hasil_cf'], 4) }}
                </td>
                <td>
                    <div class="bar-wrap">
                        <div class="bar-fill" style="width:{{ min(100, round($disease['hasil_cf'] * 100)) }}%;"></div>
                    </div>
                    <span style="font-size:10px;">{{ number_format($disease['hasil_cf'] * 100, 2) }}%</span>
                </td>
                <td>
                    @if(!empty($disease['gejala']))
                        <ul class="gejala-list">
                            @foreach($disease['gejala'] as $g)
                                <li>
                                    {{ $g['nama'] }}
                                    <span style="color:#1a73e8;">(CF: {{ number_format($g['hasil_perkalian'], 2) }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <span style="color:#aaa;">—</span>
                    @endif
                </td>
            </tr>
            @endif
        @empty
            <tr><td colspan="5" style="text-align:center;color:#aaa;">Tidak ada hasil diagnosa</td></tr>
        @endforelse
    </tbody>
</table>

{{-- SOLUSI --}}
<div class="solusi-box">
    <h4><i>Rekomendasi / Solusi:</i></h4>
    <p>{{ $item->solusi ? $item->solusi->solusi : 'Solusi tidak tersedia. Silakan konsultasikan dengan dokter.' }}</p>
</div>

{{-- TANDA TANGAN --}}
<div class="ttd-wrap">
    <p>Cirebon, {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</p>
    <p class="nama">dr. Nuke Ariyanie, Sp.S</p>
    <p class="nip">SIP: 449/SIP.DSp-544/SDK/DINKES/IX/2023</p>
</div>

{{-- FOOTER --}}
<div class="footer-line">
    Dokumen ini digenerate secara otomatis oleh Sistem SIGATUR &bull; Hanya berlaku sebagai referensi awal, bukan pengganti diagnosis medis resmi.
</div>

@else
    <p style="text-align:center; margin-top:40px; color:#aaa;">Tidak ada data riwayat diagnosa.</p>
@endif

</body>
</html>
