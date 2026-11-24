<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perkembangan Prestasi Anggota — {{ $anggota->nama }}</title>
    <style>
        @page {
            margin: 28px 32px 44px 32px;
            size: A4 portrait;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1F2937;
            margin: 0;
            padding: 0;
        }
        /* Kop Laporan */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2.5px solid #7C3AED;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .kop-table td {
            vertical-align: middle;
            padding-bottom: 10px;
        }
        .kop-title {
            font-size: 16px;
            font-weight: bold;
            color: #2E1065;
            margin: 0 0 3px 0;
        }
        .kop-subtitle {
            font-size: 11px;
            font-weight: bold;
            color: #7C3AED;
            margin: 0;
            letter-spacing: 0.5px;
        }
        /* Kotak Identitas */
        .box {
            background-color: #FAFAFB;
            border: 1px solid #E5E7EB;
            padding: 10px 14px;
            margin-bottom: 14px;
            border-radius: 6px;
        }
        .box table {
            width: 100%;
            border-collapse: collapse;
        }
        .box td {
            font-size: 11px;
            padding: 3px 0;
            vertical-align: top;
        }
        /* Kotak Ringkasan Evaluasi */
        .summary-box {
            background-color: #F5F3FF;
            border: 1px solid #DDD6FE;
            padding: 12px 14px;
            margin-bottom: 16px;
            border-radius: 6px;
        }
        .summary-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-grid td {
            padding: 4px 6px;
            font-size: 11px;
        }
        .saran-box {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #C4B5FD;
            color: #5B21B6;
            font-style: italic;
            font-size: 10.5px;
        }
        /* Judul Seksi & Tabel Data */
        h3 {
            font-size: 12px;
            color: #6D28D9;
            margin: 14px 0 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.data th, table.data td {
            border: 1px solid #DDD6FE;
            padding: 6px 8px;
            font-size: 10.5px;
        }
        table.data th {
            background-color: #F5F3FF;
            color: #6D28D9;
            text-align: left;
            font-size: 9.5px;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        /* Footer dengan Nomor Halaman */
        .footer {
            position: fixed;
            bottom: -26px;
            left: 0;
            right: 0;
            height: 20px;
            border-top: 1px solid #E5E7EB;
            padding-top: 4px;
            font-size: 9.5px;
            color: #6B7280;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .page-number:after {
            content: "Halaman " counter(page);
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo-sac.jpg');
        $logoSrc = file_exists($logoPath)
            ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
            : null;
    @endphp

    {{-- Footer Fixed di Setiap Halaman --}}
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>Dicetak dari Sistem SAC pada {{ now()->translatedFormat('d F Y') }}</td>
                <td class="text-right"><span class="page-number"></span></td>
            </tr>
        </table>
    </div>

    {{-- 1. KOP LAPORAN --}}
    <table class="kop-table">
        <tr>
            @if ($logoSrc)
                <td style="width: 52px;">
                    <img src="{{ $logoSrc }}" style="width: 42px; height: 42px; object-fit: contain; border-radius: 6px;" alt="SAC">
                </td>
            @endif
            <td>
                <div class="kop-title">Laporan Perkembangan Prestasi Anggota</div>
                <div class="kop-subtitle">Sunnah Archery Community Batam</div>
            </td>
            <td class="text-right" style="font-size: 10px; color: #6B7280;">
                <strong>Periode Evaluasi:</strong><br>
                <span style="color: #7C3AED; font-size: 12px; font-weight: bold;">{{ $bulanIni->translatedFormat('F Y') }}</span>
            </td>
        </tr>
    </table>

    {{-- 2. IDENTITAS ANGGOTA --}}
    <div class="box">
        <table>
            <tr>
                <td style="width: 22%;"><strong>Nama Anggota</strong></td>
                <td style="width: 30%;">: {{ $anggota->nama }}</td>
                <td style="width: 22%;"><strong>Jenis Keanggotaan</strong></td>
                <td style="width: 26%;">: {{ ucfirst($anggota->jenis_keanggotaan) }}</td>
            </tr>
            <tr>
                <td><strong>Periode Laporan</strong></td>
                <td>: {{ $bulanIni->translatedFormat('F Y') }}</td>
                <td><strong>Divisi / Busur</strong></td>
                <td>: {{ $anggota->jenis_kelamin === 'L' ? 'Ikhwan' : 'Akhwat' }} / {{ $anggota->jenis_busur ?: 'Standar Klub' }}</td>
            </tr>
        </table>
    </div>

    {{-- 3. RINGKASAN EVALUASI (Dari AnalisisService, Tanpa Persentase, Semua dalam Poin) --}}
    <div class="summary-box">
        <table class="summary-grid">
            <tr>
                <td style="width: 25%;">
                    <span style="color: #6B7280; font-size: 9.5px; text-transform: uppercase;">Status Perkembangan</span><br>
                    <strong style="color: #6D28D9; font-size: 13px;">{{ strtoupper($analisis['status']) }}</strong>
                </td>
                <td style="width: 25%;">
                    <span style="color: #6B7280; font-size: 9.5px; text-transform: uppercase;">Rata-rata Bulan Ini</span><br>
                    <strong style="font-size: 12px;">{{ $analisis['rata_ini'] !== null ? number_format($analisis['rata_ini'], 1) . ' poin' : '-' }}</strong>
                </td>
                <td style="width: 25%;">
                    <span style="color: #6B7280; font-size: 9.5px; text-transform: uppercase;">Rata-rata Bulan Lalu</span><br>
                    <strong style="font-size: 12px;">{{ $analisis['rata_lalu'] !== null ? number_format($analisis['rata_lalu'], 1) . ' poin' : '-' }}</strong>
                </td>
                <td style="width: 25%;">
                    <span style="color: #6B7280; font-size: 9.5px; text-transform: uppercase;">Selisih Skor</span><br>
                    <strong style="font-size: 12px;">
                        @if ($analisis['selisih'] !== null)
                            {{ ($analisis['selisih'] > 0 ? '+' : '') . number_format($analisis['selisih'], 1) }} poin
                        @else
                            -
                        @endif
                    </strong>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 6px;">
                    <strong>Jumlah Sesi Latihan Valid:</strong> {{ $analisis['jumlah_sesi'] }} sesi
                </td>
                <td colspan="2" style="padding-top: 6px;">
                    <strong>Total Volume Panah Bulan Ini:</strong> {{ $totalVolumePanah ?? 0 }} panah
                </td>
            </tr>
        </table>

        @if (!empty($analisis['saran']))
            <div class="saran-box">
                <strong>Saran Latihan:</strong> "{{ $analisis['saran'] }}"
            </div>
        @endif
    </div>

    {{-- 4. TABEL SKOR PER SESI BULAN TERSEBUT (Hanya Valid) --}}
    <h3>1. Skor Tembakan Valid ({{ $bulanIni->translatedFormat('F Y') }})</h3>
    <table class="data">
        <thead>
            <tr>
                <th class="text-center" style="width: 28px;">No</th>
                <th>Tanggal</th>
                <th class="text-center">Sesi</th>
                <th class="text-center">Jarak</th>
                <th class="text-center">Qty Panah</th>
                <th class="text-right">Total Skor</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayatSkor as $i => $skor)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $skor->tanggal->translatedFormat('d F Y') }}</td>
                    <td class="text-center">Sesi ke-{{ $skor->sesi_ke }}</td>
                    <td class="text-center">{{ $skor->jarak_meter }} m</td>
                    <td class="text-center">{{ $skor->qty_panah }} panah</td>
                    <td class="text-right" style="font-weight: bold; color: #6D28D9;">{{ $skor->total_skor }} poin</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 12px; color: #6B7280;">
                        Belum ada skor tembakan berstatus valid pada periode {{ $bulanIni->translatedFormat('F Y') }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 5. RIWAYAT KEHADIRAN BULAN TERSEBUT --}}
    <h3>2. Riwayat Kehadiran Latihan ({{ $bulanIni->translatedFormat('F Y') }})</h3>
    <table class="data">
        <thead>
            <tr>
                <th class="text-center" style="width: 28px;">No</th>
                <th>Tanggal Latihan</th>
                <th class="text-center">Jenis</th>
                <th>Lokasi</th>
                <th class="text-center">Waktu Hadir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($riwayatKehadiran ?? [] as $idx => $pr)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $pr->jadwalLatihan?->tanggal?->translatedFormat('d F Y') ?? '-' }}</td>
                    <td class="text-center">{{ ucfirst($pr->jadwalLatihan?->jenis ?? 'wajib') }}</td>
                    <td>{{ $pr->jadwalLatihan?->lokasi ?? 'Lapangan Archery SAC' }}</td>
                    <td class="text-center">{{ $pr->waktu_hadir?->format('H:i') ?? '-' }} WIB</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 12px; color: #6B7280;">
                        Belum ada catatan kehadiran pada periode {{ $bulanIni->translatedFormat('F Y') }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 6. RIWAYAT HASIL LOMBA ANGGOTA (JIKA ADA) --}}
    @if (!empty($riwayatLomba) && $riwayatLomba->isNotEmpty())
        <h3>3. Riwayat Hasil Lomba & Kejuaraan</h3>
        <table class="data">
            <thead>
                <tr>
                    <th class="text-center" style="width: 28px;">No</th>
                    <th>Nama Kejuaraan</th>
                    <th class="text-center">Tingkat</th>
                    <th>Kategori</th>
                    <th class="text-center">Jarak</th>
                    <th class="text-right">Skor</th>
                    <th class="text-center">Peringkat</th>
                    <th class="text-center">Medali</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayatLomba as $j => $hl)
                    <tr>
                        <td class="text-center">{{ $j + 1 }}</td>
                        <td>
                            <strong>{{ $hl->kejuaraan?->nama_kejuaraan ?? '-' }}</strong><br>
                            <span style="font-size: 9.5px; color: #6B7280;">{{ $hl->kejuaraan?->tanggal?->translatedFormat('d M Y') }}</span>
                        </td>
                        <td class="text-center">{{ ucfirst($hl->kejuaraan?->tingkat ?? '-') }}</td>
                        <td>{{ $hl->kategori }}</td>
                        <td class="text-center">{{ $hl->jarak_meter }} m</td>
                        <td class="text-right" style="font-weight: bold;">{{ $hl->skor }} poin</td>
                        <td class="text-center">#{{ $hl->peringkat }}</td>
                        <td class="text-center">{{ $hl->medali ?: '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
