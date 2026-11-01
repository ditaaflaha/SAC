<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Tanda Anggota SAC — {{ $anggota->nama }}</title>
    <style>
        @page {
            margin: 0;
            size: A6 landscape;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 18px;
            background-color: #f1f5f9;
            color: #0f172a;
        }
        .card {
            position: relative;
            width: 100%;
            height: 340px;
            background-color: #ffffff;
            border-radius: 14px;
            border: 1px solid #cbd5e1;
            overflow: hidden;
        }
        /* Header hijau tua minimalis */
        .header {
            background-color: #064e3b;
            color: #ffffff;
            padding: 16px 24px;
            border-bottom: 3px solid #f59e0b; /* Gold ring accent */
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .brand-title {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1.5px;
            color: #ffffff;
            margin: 0;
        }
        .brand-subtitle {
            font-size: 8.5px;
            letter-spacing: 2px;
            color: #a7f3d0;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .id-badge {
            background-color: #047857;
            border: 1px solid #34d399;
            padding: 5px 10px;
            border-radius: 6px;
            font-family: Courier, monospace;
            font-size: 12px;
            font-weight: bold;
            color: #fef08a;
            letter-spacing: 1px;
        }
        /* Watermark Target Panahan Minimalis di kanan bawah */
        .watermark {
            position: absolute;
            right: -35px;
            bottom: -35px;
            width: 190px;
            height: 190px;
            opacity: 0.06;
        }
        /* Area Konten Utama */
        .body-area {
            padding: 22px 24px 16px 24px;
            position: relative;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .avatar-box {
            width: 78px;
            vertical-align: top;
            padding-right: 18px;
        }
        .avatar-circle {
            width: 68px;
            height: 68px;
            border-radius: 34px;
            background-color: #ecfdf5;
            border: 2px solid #10b981;
            text-align: center;
            line-height: 64px;
            font-size: 22px;
            font-weight: bold;
            color: #065f46;
        }
        .status-pill {
            margin-top: 8px;
            display: block;
            width: 68px;
            text-align: center;
            background-color: #d1fae5;
            color: #065f46;
            font-size: 8px;
            font-weight: bold;
            padding: 3px 0;
            border-radius: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .info-box {
            vertical-align: top;
        }
        .label-small {
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #64748b;
            margin-bottom: 2px;
        }
        .member-name {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 14px 0;
            letter-spacing: 0.3px;
        }
        .meta-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-grid td {
            padding: 5px 12px 5px 0;
            vertical-align: top;
        }
        .meta-value {
            font-size: 11px;
            font-weight: bold;
            color: #1e293b;
        }
        /* Footer Minimalis + Garis Warna Target Archery */
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 8px 24px 12px 24px;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-quote {
            font-size: 8.5px;
            color: #64748b;
            font-style: italic;
        }
        .footer-loc {
            font-size: 8.5px;
            font-weight: bold;
            color: #065f46;
            text-align: right;
            letter-spacing: 0.8px;
        }
        /* Bar 5 Warna Target Panahan (Putih/Abu, Hitam, Biru, Merah, Kuning Emas) */
        .target-strip {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 4px;
            border-collapse: collapse;
        }
        .target-strip td {
            padding: 0;
            height: 4px;
        }
    </style>
</head>
<body>
    @php
        $inisial = collect(explode(' ', trim($anggota->nama)))
            ->filter()
            ->take(2)
            ->map(fn ($k) => strtoupper(substr($k, 0, 1)))
            ->join('');

        // Logo resmi SAC untuk header Kartu Anggota
        $logoPath = public_path('images/logo-sac.jpg');
        $logoSrc = file_exists($logoPath)
            ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
            : 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none" stroke="#34d399" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10 C38 18, 38 46, 18 54"/><line x1="18" y1="10" x2="18" y2="54" stroke="#a7f3d0" stroke-width="2"/><line x1="10" y1="32" x2="52" y2="32" stroke="#fef08a" stroke-width="3.5"/><polyline points="44,25 52,32 44,39" stroke="#fef08a" stroke-width="3.5"/></svg>');

        // SVG watermark target face minimalis
        $targetSvg = base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" fill="none" stroke="#064e3b"><circle cx="100" cy="100" r="90" stroke-width="4"/><circle cx="100" cy="100" r="68" stroke-width="4"/><circle cx="100" cy="100" r="46" stroke-width="4"/><circle cx="100" cy="100" r="24" stroke-width="5"/><circle cx="100" cy="100" r="6" fill="#064e3b"/><line x1="20" y1="100" x2="180" y2="100" stroke-width="1.5" stroke-dasharray="4 4"/><line x1="100" y1="20" x2="100" y2="180" stroke-width="1.5" stroke-dasharray="4 4"/></svg>');
    @endphp

    <div class="card">
        {{-- Watermark Target Face Minimalis --}}
        <img src="data:image/svg+xml;base64,{{ $targetSvg }}" class="watermark" alt="">

        {{-- Header --}}
        <div class="header">
            <table class="header-table">
                <tr>
                    <td style="width: 44px; vertical-align: middle;">
                        <img src="{{ $logoSrc }}" style="width: 34px; height: 34px; border-radius: 6px; background: #fff; padding: 2px; display: block;" alt="">
                    </td>
                    <td style="vertical-align: middle;">
                        <div class="brand-title">SUNNAH ARCHERY COMMUNITY</div>
                        <div class="brand-subtitle">Official Member Identification Card</div>
                    </td>
                    <td style="text-align: right; vertical-align: middle;">
                        <span class="id-badge">SAC-{{ str_pad($anggota->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Body --}}
        <div class="body-area">
            <table class="main-table">
                <tr>
                    <td class="avatar-box">
                        <div class="avatar-circle">{{ $inisial }}</div>
                        <span class="status-pill">{{ $anggota->status_keanggotaan }}</span>
                    </td>
                    <td class="info-box">
                        <div class="label-small">Nama Pemanah</div>
                        <div class="member-name">{{ $anggota->nama }}</div>

                        <table class="meta-grid">
                            <tr>
                                <td style="width: 36%;">
                                    <div class="label-small">Keanggotaan</div>
                                    <div class="meta-value">{{ ucfirst($anggota->jenis_keanggotaan) }}</div>
                                </td>
                                <td style="width: 32%;">
                                    <div class="label-small">Divisi</div>
                                    <div class="meta-value">{{ $anggota->jenis_kelamin === 'L' ? 'Ikhwan (Putra)' : 'Akhwat (Putri)' }}</div>
                                </td>
                                <td style="width: 32%;">
                                    <div class="label-small">Bergabung</div>
                                    <div class="meta-value">{{ $anggota->tanggal_gabung ? $anggota->tanggal_gabung->translatedFormat('M Y') : '-' }}</div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding-top: 8px;">
                                    <div class="label-small">Kategori Busur</div>
                                    <div class="meta-value">{{ $anggota->jenis_busur ? ucfirst($anggota->jenis_busur) : 'Standar Komunitas (SAC)' }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Footer Minimalis + Strip 5 Warna Target Panahan --}}
        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td class="footer-quote">"Fokus, tenang, dan istiqomah menghidupkan sunnah memanah."</td>
                    <td class="footer-loc">SAC BATAM</td>
                </tr>
            </table>

            <table class="target-strip">
                <tr>
                    <td style="width: 20%; background-color: #cbd5e1;"></td>
                    <td style="width: 20%; background-color: #1e293b;"></td>
                    <td style="width: 20%; background-color: #0284c7;"></td>
                    <td style="width: 20%; background-color: #dc2626;"></td>
                    <td style="width: 20%; background-color: #f59e0b;"></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>


