@extends('layouts.admin')
@section('title','Buat Passage Reading')
@section('page-title','Buat Passage Reading Baru')
@section('breadcrumb','Admin / Reading / Buat Passage')

@section('content')
<div style="max-width:800px">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-book-plus" style="color:var(--accent);margin-right:8px"></i>
                Buat Passage Baru
            </h3>
        </div>
        <div class="card-body" style="padding:24px">

            {{-- Panduan --}}
            <div style="background:rgba(26,86,219,.07);border:1px solid rgba(26,86,219,.2);
                border-radius:10px;padding:14px 16px;margin-bottom:22px;font-size:13.5px;line-height:1.8">
                <div style="font-weight:700;color:var(--accent);margin-bottom:6px">
                    📋 Panduan Input Passage Reading
                </div>
                <ul style="margin:0;padding-left:18px;color:rgba(255,255,255,.7)">
                    <li>Satu passage = satu teks akademik (contoh: The Ocean's Living Lights)</li>
                    <li>Setelah passage disimpan, kamu akan diarahkan untuk menambah soal-soalnya</li>
                    <li>Setiap passage biasanya punya <strong>10 soal</strong> dengan berbagai tipe</li>
                    <li>Teks bisa berisi beberapa paragraf — pisahkan dengan enter</li>
                </ul>
            </div>

            <form action="{{ route('admin.passage.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">
                        Judul Passage <span style="color:var(--red)">*</span>
                        <small style="color:var(--muted)">(hanya untuk admin, tidak tampil ke mahasiswa)</small>
                    </label>
                    <input type="text" name="judul" class="form-control"
                        placeholder="cth: The Ocean's Living Lights"
                        value="{{ old('judul') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Tipe Paket <span style="color:var(--red)">*</span>
                    </label>
                    <select name="tipe_paket" class="form-control" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="full"     {{ old('tipe_paket')==='full'    ?'selected':'' }}>🏆 Tes Full</option>
                        <option value="simulasi" {{ old('tipe_paket')==='simulasi'?'selected':'' }}>🎯 Simulasi</option>
                        <option value="mini"     {{ old('tipe_paket')==='mini'    ?'selected':'' }}>⚡ Tes Mini</option>
                        <option value="praktik"  {{ old('tipe_paket')==='praktik' ?'selected':'' }}>📚 Praktik</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Teks Passage <span style="color:var(--red)">*</span>
                        <small style="color:var(--muted)">— tulis teks akademik lengkap, pisahkan paragraf dengan enter</small>
                    </label>
                    <textarea name="teks" class="form-control" rows="14" required
                        placeholder="Tulis atau paste teks akademik di sini...

Contoh:
From flickering plankton near the shoreline to the steady glow of deep-sea anglerfish, bioluminescence is a pervasive feature of the oceans...

Although the precise chemistry varies among groups...">{{ old('teks') }}</textarea>
                    <div style="font-size:12px;color:var(--muted);margin-top:6px">
                        <i class="fas fa-info-circle"></i>
                        Tips: Setelah disimpan, kata-kata tertentu bisa di-highlight otomatis
                        saat membuat soal tipe <strong>Vocabulary in Context</strong>.
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:6px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan & Tambah Soal
                    </button>
                    <a href="{{ route('admin.passage.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
