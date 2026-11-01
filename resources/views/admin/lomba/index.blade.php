@extends('layouts.admin')

@section('title', 'Hasil Lomba & Kejuaraan')

@section('content')
@php
    $kejuaraanMap = $kejuaraanList->mapWithKeys(fn ($k) => [
        $k->id => [
            'id' => $k->id,
            'nama_kejuaraan' => $k->nama_kejuaraan,
            'tanggal' => $k->tanggal->format('Y-m-d'),
            'tingkat' => $k->tingkat,
            'lokasi' => $k->lokasi,
            'link_ianseo' => $k->link_ianseo ?? '',
            'update_url' => route('admin.lomba.kejuaraan.update', $k),
        ]
    ]);
@endphp
<div class="space-y-6" x-data="{
    kejuaraanData: {{ Js::from($kejuaraanMap) }},
    editKejuaraanMode: false,
    editKejuaraanAction: '',
    kejuaraanForm: {
        nama_kejuaraan: '',
        tanggal: '',
        tingkat: 'lokal',
        lokasi: '',
        link_ianseo: ''
    },
    startEditKejuaraan(id) {
        const k = this.kejuaraanData[id];
        if (!k) return;
        this.editKejuaraanMode = true;
        this.editKejuaraanAction = k.update_url;
        this.kejuaraanForm = {
            nama_kejuaraan: k.nama_kejuaraan,
            tanggal: k.tanggal,
            tingkat: k.tingkat,
            lokasi: k.lokasi,
            link_ianseo: k.link_ianseo
        };
        document.getElementById('form-kejuaraan').scrollIntoView({ behavior: 'smooth', block: 'center' });
    },
    cancelEditKejuaraan() {
        this.editKejuaraanMode = false;
        this.editKejuaraanAction = '';
        this.kejuaraanForm = {
            nama_kejuaraan: '',
            tanggal: '',
            tingkat: 'lokal',
            lokasi: '',
            link_ianseo: ''
        };
    },
    editNamaLombaLangsung: false,
    renameKejuaraan: false,
    namaKejuaraanCustom: '',
    editMode: false,
    editAction: '',
    form: {
        kejuaraan_id: '{{ $kejuaraanList->first()?->id ?? '' }}',
        anggota_id: '{{ $anggotaAktif->first()?->id ?? '' }}',
        nama_tim: 'Sunnah Archery',
        kategori: '',
        jarak_meter: 18,
        skor: '',
        peringkat: 1,
        medali: ''
    },
    toggleEditNamaLomba() {
        this.editNamaLombaLangsung = !this.editNamaLombaLangsung;
        if (this.editNamaLombaLangsung) {
            const current = this.kejuaraanData[this.form.kejuaraan_id];
            this.namaKejuaraanCustom = current ? current.nama_kejuaraan : '';
            this.renameKejuaraan = true;
        } else {
            this.namaKejuaraanCustom = '';
            this.renameKejuaraan = false;
        }
    },
    startEdit(hl, actionUrl) {
        this.editMode = true;
        this.editAction = actionUrl;
        this.editNamaLombaLangsung = false;
        this.namaKejuaraanCustom = '';
        this.form = {
            kejuaraan_id: hl.kejuaraan_id,
            anggota_id: hl.anggota_id,
            nama_tim: hl.nama_tim,
            kategori: hl.kategori,
            jarak_meter: hl.jarak_meter,
            skor: hl.skor,
            peringkat: hl.peringkat,
            medali: hl.medali || ''
        };
        document.getElementById('form-hasil-lomba').scrollIntoView({ behavior: 'smooth', block: 'center' });
    },
    cancelEdit() {
        this.editMode = false;
        this.editAction = '';
        this.editNamaLombaLangsung = false;
        this.namaKejuaraanCustom = '';
        this.form = {
            kejuaraan_id: '{{ $kejuaraanList->first()?->id ?? '' }}',
            anggota_id: '{{ $anggotaAktif->first()?->id ?? '' }}',
            nama_tim: 'Sunnah Archery',
            kategori: '',
            jarak_meter: 18,
            skor: '',
            peringkat: 1,
            medali: ''
        };
    }
}">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Hasil Lomba & Kejuaraan</h1>
            <p class="text-sm text-slate-500">Kelola riwayat keikutsertaan turnamen dan rekapitulasi medali atlet SAC.</p>
        </div>
    </div>

    {{-- Rekap Medali --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-amber-50 to-amber-100/50 p-5 rounded-2xl border border-amber-200 text-center">
            <span class="text-3xl block mb-1">🥇</span>
            <div class="text-xs font-bold uppercase text-amber-800">Medali Emas</div>
            <div class="text-3xl font-black text-amber-700 mt-1 font-mono">{{ $totalMedali['emas'] }}</div>
        </div>
        <div class="bg-gradient-to-br from-slate-50 to-slate-100 p-5 rounded-2xl border border-slate-200 text-center">
            <span class="text-3xl block mb-1">🥈</span>
            <div class="text-xs font-bold uppercase text-slate-600">Medali Perak</div>
            <div class="text-3xl font-black text-slate-700 mt-1 font-mono">{{ $totalMedali['perak'] }}</div>
        </div>
        <div class="bg-gradient-to-br from-amber-100/30 to-amber-200/40 p-5 rounded-2xl border border-amber-300/60 text-center">
            <span class="text-3xl block mb-1">🥉</span>
            <div class="text-xs font-bold uppercase text-amber-900">Medali Perunggu</div>
            <div class="text-3xl font-black text-amber-800 mt-1 font-mono">{{ $totalMedali['perunggu'] }}</div>
        </div>
    </div>

    {{-- Form Tambah Kejuaraan & Tambah Hasil Lomba --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Form Tambah / Edit Kejuaraan --}}
        <div id="form-kejuaraan" class="bg-white rounded-2xl border p-6 shadow-sm transition"
             :class="editKejuaraanMode ? 'border-amber-300 ring-2 ring-amber-100' : 'border-slate-100'">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold uppercase tracking-wider"
                    :class="editKejuaraanMode ? 'text-amber-700' : 'text-slate-800'"
                    x-text="editKejuaraanMode ? '✏️ Edit Event Kejuaraan' : 'Tambah Event Kejuaraan'">
                    Tambah Event Kejuaraan
                </h2>
                <button type="button" x-show="editKejuaraanMode" @click="cancelEditKejuaraan()"
                        class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg transition">
                    ✕ Batal Edit
                </button>
            </div>
            <form method="POST" :action="editKejuaraanMode ? editKejuaraanAction : '{{ route('admin.lomba.kejuaraan.store') }}'" class="space-y-3">
                @csrf
                <template x-if="editKejuaraanMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Kejuaraan</label>
                    <input type="text" name="nama_kejuaraan" x-model="kejuaraanForm.nama_kejuaraan" required placeholder="Contoh: Kejurnas Panahan 2026..." class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" x-model="kejuaraanForm.tanggal" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tingkat</label>
                        <select name="tingkat" x-model="kejuaraanForm.tingkat" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                            <option value="lokal">Lokal</option>
                            <option value="provinsi">Provinsi</option>
                            <option value="nasional">Nasional</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Lokasi</label>
                    <input type="text" name="lokasi" x-model="kejuaraanForm.lokasi" required placeholder="Stadion / Lapangan..." class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Link Ianseo (Opsional)</label>
                    <input type="url" name="link_ianseo" x-model="kejuaraanForm.link_ianseo" placeholder="https://ianseo.net/..." class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                </div>
                <button type="submit"
                        class="w-full py-2.5 text-white rounded-xl text-xs font-bold transition"
                        :class="editKejuaraanMode ? 'bg-amber-600 hover:bg-amber-700' : 'bg-slate-800 hover:bg-slate-900'"
                        x-text="editKejuaraanMode ? '✓ Simpan Perubahan Kejuaraan' : '+ Simpan Kejuaraan'">
                    + Simpan Kejuaraan
                </button>
            </form>
        </div>

        {{-- Form Catat / Edit Hasil Lomba Anggota --}}
        <div id="form-hasil-lomba" class="bg-white rounded-2xl border p-6 shadow-sm transition"
             :class="editMode ? 'border-amber-300 ring-2 ring-amber-100' : 'border-slate-100'">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold uppercase tracking-wider"
                    :class="editMode ? 'text-amber-700' : 'text-slate-800'"
                    x-text="editMode ? '✏️ Edit Hasil Lomba Atlet' : 'Catat Hasil Lomba Atlet'">
                    Catat Hasil Lomba Atlet
                </h2>
                <button type="button" x-show="editMode" @click="cancelEdit()"
                        class="text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg transition">
                    ✕ Batal Edit
                </button>
            </div>

            <form method="POST" :action="editMode ? editAction : '{{ route('admin.lomba.hasil.store') }}'" class="space-y-3">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-slate-600">Pilih Kejuaraan</label>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="toggleEditNamaLomba()"
                                        class="text-[11px] font-bold text-amber-600 hover:text-amber-800 underline"
                                        x-text="editNamaLombaLangsung ? 'Pilih dari Daftar' : '✏️ Edit / Ketik Lomba'">
                                </button>
                                <button type="button" x-show="!editNamaLombaLangsung && form.kejuaraan_id"
                                        @click="startEditKejuaraan(form.kejuaraan_id)"
                                        class="text-[11px] font-bold text-emerald-600 hover:text-emerald-800"
                                        title="Edit detail kejuaraan ini di form sebelah kiri">
                                    Detail
                                </button>
                            </div>
                        </div>

                        <div x-show="!editNamaLombaLangsung">
                            <select name="kejuaraan_id" x-model="form.kejuaraan_id" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                                @foreach ($kejuaraanList as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kejuaraan }} ({{ $k->tanggal->format('Y') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div x-show="editNamaLombaLangsung" class="space-y-1.5">
                            <input type="text" name="nama_kejuaraan_custom" x-model="namaKejuaraanCustom"
                                   placeholder="Ketik atau ubah nama kejuaraan..."
                                   class="w-full text-sm border-amber-300 rounded-xl px-3 py-2 focus:ring-amber-500">
                            <label class="inline-flex items-center gap-1.5 text-[11px] text-slate-600 cursor-pointer">
                                <input type="checkbox" name="rename_kejuaraan" value="1" x-model="renameKejuaraan" class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                <span>Ubah nama lomba yang sedang dipilih (hilangkan centang jika ingin buat lomba baru)</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Pemanah (Anggota)</label>
                        <select name="anggota_id" x-model="form.anggota_id" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                            @foreach ($anggotaAktif as $a)
                                <option value="{{ $a->id }}">{{ $a->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Tim / Klub</label>
                        <input type="text" name="nama_tim" x-model="form.nama_tim" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Divisi / Kategori</label>
                        <input type="text" name="kategori" x-model="form.kategori" placeholder="Recurve Putra, Barebow..." required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Jarak (m)</label>
                        <input type="number" name="jarak_meter" x-model="form.jarak_meter" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Total Skor</label>
                        <input type="number" name="skor" x-model="form.skor" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Peringkat</label>
                        <input type="number" name="peringkat" x-model="form.peringkat" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Perolehan Medali</label>
                    <select name="medali" x-model="form.medali" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2">
                        <option value="">Tidak Ada Medali</option>
                        <option value="Emas">🥇 Emas</option>
                        <option value="Perak">🥈 Perak</option>
                        <option value="Perunggu">🥉 Perunggu</option>
                    </select>
                </div>

                <button type="submit"
                        class="w-full py-2.5 text-white rounded-xl text-xs font-bold transition shadow-sm"
                        :class="editMode ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700'"
                        x-text="editMode ? '✓ Simpan Perubahan Hasil Lomba' : '+ Simpan Hasil Lomba'">
                    + Simpan Hasil Lomba
                </button>
            </form>
        </div>
    </div>

    {{-- Daftar Kejuaraan & Hasil Atlet --}}
    <div class="space-y-4">
        @forelse ($kejuaraanList as $k)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50/75 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-bold text-slate-800 text-base">{{ $k->nama_kejuaraan }}</h3>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-slate-200 text-slate-700 uppercase">
                                {{ $k->tingkat }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">
                            📅 {{ $k->tanggal->translatedFormat('d M Y') }} · 📍 {{ $k->lokasi }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if ($k->link_ianseo)
                            <a href="{{ $k->link_ianseo }}" target="_blank" class="text-xs text-emerald-600 hover:underline font-semibold">
                                Lihat di Ianseo ↗
                            </a>
                        @endif
                        <button type="button" @click="startEditKejuaraan({{ $k->id }})"
                                class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-bold transition">
                            ✏️ Edit Lomba
                        </button>
                        <form method="POST" action="{{ route('admin.lomba.kejuaraan.destroy', $k) }}" class="inline" onsubmit="return confirm('Hapus kejuaraan {{ addslashes($k->nama_kejuaraan) }} beserta daftar hasilnya?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-bold transition">
                                🗑️ Hapus Lomba
                            </button>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-white text-xs uppercase font-semibold text-slate-400 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3">Nama Atlet</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3 text-center">Jarak</th>
                                <th class="px-4 py-3 text-right">Skor</th>
                                <th class="px-4 py-3 text-center">Rank</th>
                                <th class="px-4 py-3 text-center">Medali</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($k->hasilLomba as $hl)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-3 font-bold text-slate-800 text-xs">
                                        {{ $hl->anggota->nama }}
                                    </td>
                                    <td class="px-4 py-3 text-xs text-slate-600">
                                        {{ $hl->kategori }}
                                    </td>
                                    <td class="px-4 py-3 text-xs text-center text-slate-700 font-semibold">
                                        {{ $hl->jarak_meter }}m
                                    </td>
                                    <td class="px-4 py-3 text-right font-mono font-bold text-slate-800">
                                        {{ $hl->skor }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold text-xs">
                                        #{{ $hl->peringkat }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-xs">
                                        @if ($hl->medali)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-extrabold text-[11px]
                                                {{ strtolower($hl->medali) === 'emas' ? 'bg-amber-100 text-amber-800 border border-amber-300' : (strtolower($hl->medali) === 'perak' ? 'bg-slate-100 text-slate-700' : 'bg-amber-50 text-amber-900') }}">
                                                {{ $hl->medali }}
                                            </span>
                                        @else
                                            <span class="text-slate-300">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right whitespace-nowrap space-x-2">
                                        <button type="button"
                                                @click="startEdit({{ Js::from([
                                                    'kejuaraan_id' => $hl->kejuaraan_id,
                                                    'anggota_id' => $hl->anggota_id,
                                                    'nama_tim' => $hl->nama_tim,
                                                    'kategori' => $hl->kategori,
                                                    'jarak_meter' => $hl->jarak_meter,
                                                    'skor' => $hl->skor,
                                                    'peringkat' => $hl->peringkat,
                                                    'medali' => $hl->medali,
                                                ]) }}, '{{ route('admin.lomba.hasil.update', $hl) }}')"
                                                class="text-amber-600 hover:text-amber-800 text-xs font-semibold">
                                            Edit
                                        </button>
                                        <form method="POST" action="{{ route('admin.lomba.hasil.destroy', $hl) }}" class="inline" onsubmit="return confirm('Hapus hasil lomba ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs font-semibold">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-6 text-center text-slate-400 text-xs">
                                        Belum ada hasil lomba yang dicatat untuk kejuaraan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <p class="text-xs text-slate-400 text-center py-10 bg-white rounded-2xl border border-slate-100">
                Belum ada kejuaraan yang dibuat.
            </p>
        @endforelse
    </div>
</div>
@endsection
