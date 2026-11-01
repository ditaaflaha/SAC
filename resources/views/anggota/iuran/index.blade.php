@extends('layouts.anggota')

@section('title', 'Iuran Bulanan Saya')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Iuran & Keuangan Saya</h1>
        <p class="text-sm text-gray-500">Informasi kewajiban iuran bulanan dan riwayat setoran iuran komunitas.</p>
    </div>

    {{-- Kartu Status Iuran Bulan Ini --}}
    @php
        $totalLunasIni = $pembayaranBulanIni->where('status', 'lunas')->sum('jumlah');
        $isFree = $isFreePeriod || $pembayaranBulanIni->where('status', 'free')->isNotEmpty();
        $isLunas = $isFree || $totalLunasIni >= 40000;
        $isCicilan1 = ! $isLunas && $totalLunasIni > 0;
        $adaMenunggu = $pembayaranBulanIni->where('status', 'menunggu')->isNotEmpty();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Status Iuran Periode</span>
                <h2 class="text-lg font-extrabold text-gray-800 mt-1">{{ $bulanIni->translatedFormat('F Y') }}</h2>

                <div class="my-4">
                    @if ($isFree)
                        <span class="inline-block px-3.5 py-1.5 rounded-2xl bg-purple-100 text-purple-800 font-extrabold text-sm border border-purple-200">
                            🎉 GRATIS (Periode Bebas Iuran)
                        </span>
                        <p class="text-xs text-purple-700 mt-2">
                            Kamu sedang dalam masa 2 bulan gratis iuran sejak bergabung di SAC.
                        </p>
                    @elseif ($isLunas)
                        <span class="inline-block px-3.5 py-1.5 rounded-2xl bg-emerald-100 text-emerald-800 font-extrabold text-sm border border-emerald-200">
                            ✓ LUNAS
                        </span>
                        <p class="text-xs text-emerald-700 mt-2">
                            Jazakallahu khair! Iuran bulan ini sudah terverifikasi lunas.
                        </p>
                    @elseif ($adaMenunggu)
                        <span class="inline-block px-3.5 py-1.5 rounded-2xl bg-amber-100 text-amber-800 font-extrabold text-sm border border-amber-200 animate-pulse">
                            ⏳ MENUNGGU VERIFIKASI
                        </span>
                        <p class="text-xs text-amber-700 mt-2">
                            Bukti transfer sudah diunggah dan sedang diperiksa oleh admin.
                        </p>
                    @elseif ($isCicilan1)
                        <span class="inline-block px-3.5 py-1.5 rounded-2xl bg-blue-100 text-blue-800 font-extrabold text-sm border border-blue-200">
                            CICILAN 1 (Rp{{ number_format($totalLunasIni, 0, ',', '.') }})
                        </span>
                        <p class="text-xs text-blue-700 mt-2">
                            Sisa iuran yang perlu dibayar: Rp{{ number_format(40000 - $totalLunasIni, 0, ',', '.') }}.
                        </p>
                    @else
                        <span class="inline-block px-3.5 py-1.5 rounded-2xl bg-rose-100 text-rose-800 font-extrabold text-sm border border-rose-200">
                            BELUM BAYAR
                        </span>
                        <p class="text-xs text-rose-700 mt-2">
                            Iuran bulanan reguler: Rp40.000 / bulan.
                        </p>
                    @endif
                </div>
            </div>

            <div class="border-t border-gray-100 pt-3 text-xs text-gray-500">
                Tarif reguler: <strong>Rp40.000/bulan</strong> (bisa dicicil 2× Rp20.000)
            </div>
        </div>

        {{-- Info Rekening Pembayaran --}}
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-6 text-white shadow-md flex flex-col justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Rekening Resmi SAC</span>
                <div class="text-xl font-black mt-2 tracking-tight">Bank Syariah Indonesia (BSI)</div>
                <div class="mt-4 p-3 bg-white/10 rounded-2xl border border-white/10">
                    <div class="text-xs text-slate-300">Nomor Rekening:</div>
                    <div class="text-lg font-mono font-bold tracking-wider text-emerald-300 mt-0.5">712-3456-789</div>
                    <div class="text-xs text-slate-300 mt-1">a.n. Sunnah Archery Community</div>
                </div>
            </div>
            <p class="text-[11px] text-slate-400 mt-4 leading-relaxed">
                Setelah transfer, silakan upload bukti struk/screenshot pembayaran melalui form di sebelah.
            </p>
        </div>

        {{-- Form Upload Bukti Transfer --}}
        <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3">Upload Bukti Transfer</h2>
            <form method="POST" action="{{ route('anggota.iuran.bayar') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Periode Bulan Iuran</label>
                    <input type="date" name="periode" value="{{ $bulanIni->toDateString() }}" required class="w-full text-xs border-gray-200 rounded-xl px-3 py-2">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Pilihan Cicilan</label>
                        <select name="cicilan_ke" class="w-full text-xs border-gray-200 rounded-xl px-2.5 py-2">
                            <option value="">Lunas Penuh</option>
                            <option value="1">Cicilan 1</option>
                            <option value="2">Cicilan 2</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nominal (Rp)</label>
                        <input type="number" name="jumlah" value="40000" min="10000" required class="w-full text-xs border-gray-200 rounded-xl px-2.5 py-2 font-mono font-bold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Transfer</label>
                    <input type="date" name="tanggal_bayar" value="{{ now()->toDateString() }}" max="{{ now()->toDateString() }}" required class="w-full text-xs border-gray-200 rounded-xl px-3 py-2">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">File Bukti Transfer (JPG/PNG)</label>
                    <input type="file" name="bukti_transfer" accept="image/*" required class="w-full text-[11px] text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    Kirim Bukti Pembayaran 💳
                </button>
            </form>
        </div>
    </div>

    {{-- Tabel Riwayat Pembayaran --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Riwayat Pembayaran Saya</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-semibold text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5">Tanggal Bayar</th>
                        <th class="px-4 py-3.5">Periode</th>
                        <th class="px-4 py-3.5">Keterangan</th>
                        <th class="px-4 py-3.5 text-right">Jumlah</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-6 py-3.5">Bukti</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($riwayatPembayaran as $p)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-semibold text-gray-900 text-xs">
                                {{ $p->tanggal_bayar->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-4 text-xs font-bold text-gray-800">
                                {{ $p->periode ? $p->periode->translatedFormat('F Y') : '-' }}
                            </td>
                            <td class="px-4 py-4 text-xs text-gray-600">
                                {{ str_replace('_', ' ', ucfirst($p->tarif->jenis)) }}
                                @if ($p->cicilan_ke)
                                    <span class="text-amber-700 font-semibold">(Cicilan {{ $p->cicilan_ke }})</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-black text-gray-900">
                                Rp{{ number_format($p->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $sBadge = match ($p->status) {
                                        'lunas'    => 'bg-emerald-100 text-emerald-800',
                                        'free'     => 'bg-purple-100 text-purple-800',
                                        'menunggu' => 'bg-amber-100 text-amber-800 animate-pulse',
                                        'ditolak'  => 'bg-rose-100 text-rose-800',
                                        default    => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $sBadge }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                @if ($p->bukti_transfer)
                                    <a href="{{ asset('storage/' . $p->bukti_transfer) }}" target="_blank" class="text-emerald-600 hover:underline font-bold">
                                        Lihat Bukti ↗
                                    </a>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-xs">
                                Belum ada catatan riwayat pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $riwayatPembayaran->links() }}
        </div>
    </div>
</div>
@endsection

