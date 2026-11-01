@extends('layouts.admin')

@section('title', 'Iuran & Kas Komunitas')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Iuran & Kas Komunitas</h1>
            <p class="text-sm text-slate-500">Kelola tarif, verifikasi bukti transfer pembayaran, dan pantau arus kas masuk.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.iuran.pdf', ['bulan' => $bulanStr]) }}" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-sm inline-flex items-center gap-1.5">
                <span>📄</span> Unduh Laporan PDF
            </a>
            <span class="text-xs font-semibold text-slate-500">Periode:</span>
            <form method="GET" action="{{ route('admin.iuran') }}" class="inline">
                <input type="month" name="bulan" value="{{ $bulanStr }}" onchange="this.form.submit()"
                       class="text-xs border-slate-200 rounded-xl px-3 py-1.5 focus:ring-emerald-500">
            </form>
        </div>
    </div>

    {{-- Kartu Ringkasan Keuangan --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kas Masuk (Bulan Ini)</div>
            <div class="text-2xl font-black text-emerald-600 mt-2 font-mono">
                Rp{{ number_format($totalMasuk, 0, ',', '.') }}
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Pembayaran berstatus Lunas</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Menunggu Verifikasi</div>
            <div class="text-2xl font-black text-amber-600 mt-2 font-mono">{{ $counts['menunggu'] }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Bukti transfer masuk</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Total Lunas</div>
            <div class="text-2xl font-black text-indigo-600 mt-2 font-mono">{{ $counts['lunas'] }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Transaksi terverifikasi</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Status Free (Gratis)</div>
            <div class="text-2xl font-black text-purple-600 mt-2 font-mono">{{ $counts['free'] }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Periode promo / 2 bln free</p>
        </div>
    </div>

    {{-- Kelola Tarif & Form Input Pembayaran Manual --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Pengaturan Tarif Berlaku --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Tarif Berlaku SAC</h2>
            <div class="space-y-3">
                @foreach ($tarifList as $t)
                    <form method="POST" action="{{ route('admin.iuran.tarif.update', $t) }}" class="p-3 bg-slate-50 rounded-xl space-y-2">
                        @csrf
                        @method('PUT')
                        <div class="flex items-center justify-between text-xs font-bold text-slate-700 capitalize">
                            <span>{{ str_replace('_', ' ', $t->jenis) }}</span>
                            <span class="text-slate-400 font-normal">Mulai {{ $t->berlaku_mulai->format('d/m/Y') }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <input type="number" name="nominal" value="{{ $t->nominal }}" class="w-full text-xs border-slate-200 rounded-lg px-2.5 py-1.5 font-mono">
                            </div>
                            <div>
                                <input type="text" name="satuan" value="{{ $t->satuan }}" class="w-full text-xs border-slate-200 rounded-lg px-2.5 py-1.5">
                            </div>
                        </div>
                        <button type="submit" class="w-full py-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold text-[11px] rounded-lg transition">
                            Simpan Tarif
                        </button>
                    </form>
                @endforeach
            </div>
        </div>

        {{-- Form Catat Pembayaran Manual (Cash / Transfer Langsung) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Catat Pembayaran Manual (Tunai / Kasir)</h2>
            <form method="POST" action="{{ route('admin.iuran.manual') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Pilih Anggota Terdaftar (Opsional)</label>
                    <select name="anggota_id" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                        <option value="">-- Bukan Anggota / Peserta Baru --</option>
                        @foreach ($anggotaList as $a)
                            <option value="{{ $a->id }}">{{ $a->nama }} ({{ $a->jenis_keanggotaan }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Peserta (Jika non-anggota)</label>
                    <input type="text" name="nama_peserta" placeholder="Nama peserta trial/private..." class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Tarif / Item</label>
                    <select name="tarif_id" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                        @foreach ($tarifList as $t)
                            <option value="{{ $t->id }}">
                                {{ str_replace('_', ' ', ucfirst($t->jenis)) }} — Rp{{ number_format($t->nominal, 0, ',', '.') }} ({{ $t->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Jumlah Nominal Bayar (Rp)</label>
                    <input type="number" name="jumlah" value="40000" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal Pembayaran</label>
                    <input type="date" name="tanggal_bayar" value="{{ now()->toDateString() }}" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Periode Iuran (Khusus Iuran)</label>
                    <input type="date" name="periode" value="{{ now()->startOfMonth()->toDateString() }}" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                </div>

                <div class="sm:col-span-2">
                    <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                        + Catat Pembayaran Lunas
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Riwayat Transaksi & Verifikasi --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-2">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                Daftar Pembayaran & Verifikasi Bukti Transfer
            </h2>

            <div class="flex gap-2">
                <a href="{{ route('admin.iuran', ['status' => 'semua']) }}" class="px-3 py-1 rounded-lg text-xs font-bold {{ $status === 'semua' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600' }}">Semua</a>
                <a href="{{ route('admin.iuran', ['status' => 'menunggu']) }}" class="px-3 py-1 rounded-lg text-xs font-bold {{ $status === 'menunggu' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600' }}">Menunggu ({{ $counts['menunggu'] }})</a>
                <a href="{{ route('admin.iuran', ['status' => 'lunas']) }}" class="px-3 py-1 rounded-lg text-xs font-bold {{ $status === 'lunas' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600' }}">Lunas</a>
                <a href="{{ route('admin.iuran', ['status' => 'free']) }}" class="px-3 py-1 rounded-lg text-xs font-bold {{ $status === 'free' ? 'bg-purple-600 text-white' : 'bg-slate-100 text-slate-600' }}">Free</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Tanggal Bayar</th>
                        <th class="px-6 py-3.5">Nama Peserta / Anggota</th>
                        <th class="px-4 py-3.5">Jenis Tarif</th>
                        <th class="px-4 py-3.5">Periode / Cicilan</th>
                        <th class="px-4 py-3.5 text-right">Nominal</th>
                        <th class="px-4 py-3.5 text-center">Status</th>
                        <th class="px-4 py-3.5 text-center">Bukti</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($pembayaranList as $p)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-semibold text-xs text-slate-800">
                                {{ $p->tanggal_bayar->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $p->anggota?->nama ?: $p->nama_peserta }}</div>
                                <div class="text-xs text-slate-400">{{ $p->anggota ? $p->anggota->jenis_keanggotaan : 'Peserta Non-Akun' }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs capitalize text-slate-700 font-semibold">
                                {{ str_replace('_', ' ', $p->tarif->jenis) }}
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600">
                                <div>{{ $p->periode ? $p->periode->translatedFormat('F Y') : '-' }}</div>
                                @if ($p->cicilan_ke)
                                    <span class="text-[10px] text-amber-700 font-bold">Cicilan ke-{{ $p->cicilan_ke }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right font-mono font-bold text-slate-900">
                                Rp{{ number_format($p->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $pBadge = match ($p->status) {
                                        'lunas'    => 'bg-emerald-100 text-emerald-800',
                                        'free'     => 'bg-purple-100 text-purple-800',
                                        'menunggu' => 'bg-amber-100 text-amber-800 animate-pulse',
                                        'ditolak'  => 'bg-rose-100 text-rose-800',
                                        default    => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $pBadge }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if ($p->bukti_transfer)
                                    <a href="{{ asset('storage/' . $p->bukti_transfer) }}" target="_blank" class="text-xs text-emerald-600 hover:underline font-bold">
                                        Lihat ↗
                                    </a>
                                @else
                                    <span class="text-slate-300 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                @if ($p->status === 'menunggu')
                                    <form method="POST" action="{{ route('admin.iuran.verifikasi', $p) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="lunas">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                                            ✓ Verifikasi Lunas
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.iuran.verifikasi', $p) }}" class="inline" onsubmit="return confirm('Tolak bukti pembayaran ini?')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-xs font-bold transition">
                                            ✕ Tolak
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 italic">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                Belum ada transaksi pembayaran pada filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $pembayaranList->links() }}
        </div>
    </div>
</div>
@endsection
