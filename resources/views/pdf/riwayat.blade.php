<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Diagnosa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .progress-bar {
            width: 100%;
            height: 10px;
            background-color: #f3f3f3;
            position: relative;
        }
        .progress-bar span {
            position: absolute;
            height: 100%;
            background-color: #36A2EB;
        }
        /* Styling for the footer */
        .footer {
            position: absolute;
            bottom: 80px;
            width: 100%;
            text-align: center;
            font-size: 12px;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer .bold {
            font-weight: bold;
        }
        .footer .underline {
            text-decoration: underline;
        }
        /* Styling for signature space */
        .signature-space {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 300px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Riwayat Diagnosa</h1> <hr>
    </div>

    @if($riwayat->isNotEmpty())
    @php
        // Ambil data riwayat terbaru
        $latestRiwayat = $riwayat->sortByDesc('created_at')->first(); 
        // Ambil diagnosa dari riwayat terbaru
        $latestDiagnoses = $latestRiwayat->diagnoses ?? [];
        $user = auth()->user();
    @endphp
<p style="margin: 5px 0;">Nama     : <span style="font-weight: bold;">{{ $user->nama }}</span></p>
<p style="margin: 5px 0;">Instansi : <span style="font-weight: bold;">{{ $user->instansi }}</span></p>
    <p style="margin: 5px 0;">Tanggal Uji : <span style="font-weight: bold;">{{ \Carbon\Carbon::parse($latestRiwayat->created_at)->format('d F Y H:i') }}</span></p>
@endif

<table class="table">
    <thead>
        <tr>
            <th>Penyakit Terdiagnosa</th>
            <th>Probabilitas (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($latestDiagnoses as $disease)
            @if($disease['hasil_cf'] > 0)
                <tr>
                    <td>{{ $disease['nama_penyakit'] }}</td>
                    <td>{{ number_format($disease['hasil_cf'] * 100, 2) }}%</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

<h3>Solusi :</h3>
<p>{{ $latestRiwayat->solusi ? $latestRiwayat->solusi->solusi : 'Solusi tidak ditemukan' }}</p>

<!-- Footer -->
<div class="footer">
    <p class="bold">Cirebon, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
    <br><br><br><br>  
    <p class="bold underline">Dr. Fahreza, Sp.PD</p> <!-- Garis bawah di nama dokter -->
    <p class="bold">No: 123456</p>
</div>

</body>
</html>
