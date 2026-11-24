<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor Progres Pemanah — {{ $anggota->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #1F2937;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #7C3AED;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 4px;
            color: #6D28D9;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
            color: #6B7280;
        }
        .bio-box {
            background-color: #FAFAFB;
            border: 1px solid #E5E7EB;
            padding: 10px 14px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        .bio-box table {
            width: 100%;
        }
        .bio-box td {
            font-size: 11px;
            padding: 3px 0;
        }
        .status-box {
            background-color: #F5F3FF;
            border: 1px solid #DDD6FE;
            padding: 10px 14px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        h3 {
            color: #6D28D9;
            margin-bottom: 6px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        table.data th, table.data td {
            border: 1px solid #DDD6FE;
            padding: 6px 8px;
        }
        table.data th {
            background-color: #F5F3FF;
            color: #6D28D9;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #6B7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sunnah Archery Community (SAC)</h1>
        <p>Rapor Evaluasi Perkembangan Skor & Latihan Pemanah</p>
        <p><strong>Periode: {{ $bulanIni->translatedFormat('F Y') }}</strong></p>
    </div>

    <div class="bio-box">
        <table>
            <tr>
                <td style="width: 20%;"><strong>Nama Pemanah:</strong></td>
                <td style="width: 30%;">{{ $anggota->nama }}</td>
                <td style="width: 20%;"><strong>Jenis Keanggotaan:</strong></td>
                <td style="width: 30%;">{{ ucfirst($anggota->jenis_keanggotaan) }}</td>
            </tr>
            <tr>
                <td><strong>Jenis Kelamin:</strong></td>
                <td>{{ $anggota->jenis_kelamin === 'L' ? 'Laki-laki (Ikhwan)' : 'Perempuan (Akhwat)' }}</td>
                <td><strong>Busur:</strong></td>
                <td>{{ $anggota->jenis_busur ?: 'Belum punya' }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Gabung:</strong></td>
                <td>{{ $anggota->tanggal_gabung ? $anggota->tanggal_gabung->format('d/m/Y') : '-' }}</td>
                <td><strong>No. WhatsApp:</strong></td>
                <td>{{ $anggota->no_wa ?: '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="status-box">
        <table>
            <tr>
                <td><strong>Status Evaluasi Bulan Ini:</strong> <span style="color: #6D28D9; font-size: 13px; font-weight: bold;">{{ strtoupper($analisis['status']) }}</span></td>
                <td class="text-right"><strong>Rata-rata Skor:</strong> {{ $analisis['rata_ini'] !== null ? number_format($analisis['rata_ini'], 1) : '-' }} poin</td>
            </tr>
            @if ($analisis['saran'])
                <tr>
                    <td colspan="2" style="padding-top: 8px; font-style: italic; color: #5B21B6;">
                        Catatan & Saran Pelatih: "{{ $analisis['saran'] }}"
                    </td>
                </tr>
            @endif
        </table>
    </div>

    <h3>Riwayat Sesi Tembakan Valid</h3>
    <table class="data">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">No</th>
                <th>Tanggal</th>
                <th class="text-center">Sesi</th>
                <th class="text-center">Jarak Target</th>
                <th>Qty Anak Panah</th>
                <th class="text-right">Total Skor</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayatSkor as $i => $skor)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $skor->tanggal->format('d/m/Y') }}</td>
                    <td class="text-center">Sesi {{ $skor->sesi_ke }}</td>
                    <td class="text-center">{{ $skor->jarak_meter }} meter</td>
                    <td>{{ $skor->qty_panah }} panah</td>
                    <td class="text-right" style="font-weight: bold; color: #6D28D9;">{{ $skor->total_skor }} poin</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px; color: #999;">
                        Belum ada catatan skor valid.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak otomatis oleh Sistem SAC pada: {{ now()->translatedFormat('d F Y') }}</p>
    </div>
</body>
</html>
