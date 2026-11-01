<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan & Iuran SAC — {{ $bulan->translatedFormat('F Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #059669;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 4px;
            color: #065f46;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 11px;
            color: #666;
        }
        .summary-box {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            padding: 10px 14px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        .summary-box table {
            width: 100%;
        }
        .summary-box td {
            font-size: 11px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data th, table.data td {
            border: 1px solid #ddd;
            padding: 6px 8px;
        }
        table.data th {
            background-color: #f3f4f6;
            color: #374151;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.data tr:nth-child(even) {
            background-color: #f9fafb;
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
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sunnah Archery Community (SAC)</h1>
        <p>Laporan Rekapitulasi Iuran & Kas Masuk Bulanan</p>
        <p><strong>Periode: {{ $bulan->translatedFormat('F Y') }}</strong></p>
    </div>

    <div class="summary-box">
        <table>
            <tr>
                <td><strong>Total Transaksi Terverifikasi:</strong> {{ $transaksi->where('status', 'lunas')->count() }} transaksi</td>
                <td class="text-right"><strong>Total Kas Masuk:</strong> Rp {{ number_format($totalLunas, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th class="text-center" style="width: 25px;">No</th>
                <th>Tanggal</th>
                <th>Nama Anggota / Peserta</th>
                <th>Jenis Tarif</th>
                <th>Periode Iuran</th>
                <th class="text-center">Status</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->tanggal_bayar->format('d/m/Y') }}</td>
                    <td><strong>{{ $item->anggota?->nama ?: $item->nama_peserta }}</strong></td>
                    <td>{{ ucfirst(str_replace('_', ' ', $item->tarif->jenis)) }}</td>
                    <td>{{ $item->periode ? $item->periode->translatedFormat('F Y') : '-' }}</td>
                    <td class="text-center">{{ strtoupper($item->status) }}</td>
                    <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px; color: #999;">
                        Tidak ada data transaksi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #ecfdf5; font-weight: bold;">
                <td colspan="6" class="text-right">TOTAL KAS MASUK:</td>
                <td class="text-right">Rp {{ number_format($totalLunas, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB oleh {{ auth()->user()->name }}</p>
    </div>
</body>
</html>

