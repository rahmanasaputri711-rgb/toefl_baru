@extends('layouts.admin')
@section('title','Gambar/Email')
@section('page-title','Gambar / Email — '.$modul->rentang)
@section('breadcrumb','Admin / Paket Builder / Gambar/Email')

@section('content')
<div style="max-width:860px">
<div style="display:flex;align-items:center;gap:12px;margin-bottom:18px">
    <a href="{{ route('admin.paket-builder.paket', $modul->paket_id) }}" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <div style="font-size:16px;font-weight:800">📧 Gambar / Email</div>
        <div style="font-size:13px;color:var(--muted)">{{ $modul->paket?->nama }} · {{ $modul->rentang }}</div>
    </div>
</div>

<div id="alert" style="display:none;margin-bottom:14px"></div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:18px;align-items:start">
<div>

{{-- Upload gambar --}}
@php $passage = $modul->passages->first(); @endphp
<div class="card" style="margin-bottom:16px">
    <div class="card-header" style="padding:12px 18px">
        <h3 style="font-size:13px">
            <span style="background:var(--blue);color:#fff;width:22px;height:22px;
                border-radius:50%;display:inline-flex;align-items:center;justify-content:center;
                font-size:11px;font-weight:900;margin-right:8px">1</span>
            Upload Gambar / Screenshot
        </h3>
        @if($passage?->image_url)
        <span style="font-size:12px;color:var(--green)">✓ Sudah diupload</span>
        @endif
    </div>
    <div class="card-body" style="padding:16px 18px">
        @if($passage?->image_url)
        <img src="{{ asset('storage/'.$passage->image_url) }}"
            style="max-width:100%;border-radius:8px;margin-bottom:12px">
        @endif
        <div id="drop-zone" style="border:2px dashed var(--border);border-radius:10px;
            padding:24px;text-align:center;cursor:pointer"
            onclick="document.getElementById('inp-gambar').click()">
            <i class="fas fa-upload" style="font-size:24px;color:var(--muted);margin-bottom:8px;display:block"></i>
            <div style="font-size:13px;color:var(--muted)">
                Klik atau drag & drop gambar/screenshot
            </div>
            <div id="file-name" style="font-size:12px;color:var(--green);margin-top:6px"></div>
        </div>
        <input type="file" id="inp-gambar" accept="image/*" style="display:none"
            onchange="uploadGambar(this)">
        <div id="preview-gambar" style="margin-top:12px;display:none">
            <img id="img-preview" style="max-width:100%;border-radius:8px;max-height:300px">
        </div>
    </div>
</div>

{{-- Tambah soal --}}
<div class="card">
    <div class="card-header" style="padding:12px 18px">
        <h3 style="font-size:13px">
            <span style="background:var(--blue);color:#fff;width:22px;height:22px;
                border-radius:50%;display:inline-flex;align-items:center;justify-content:center;
                font-size:11px;font-weight:900;margin-right:8px">2</span>
            Tambah Soal Pilihan Ganda
        </h3>
        <span style="font-size:12px;color:var(--muted)">{{ $modul->soal->count() }}/{{ $modul->jumlah_target }} soal</span>
    </div>
    <div class="card-body" style="padding:16px 18px">

        <div class="form-group">
            <label class="form-label" style="font-size:12px">Nomor Soal</label>
            <select id="inp-nomor" class="form-control" style="width:100px">
                @for($n=$modul->nomor_soal_mulai; $n<=$modul->nomor_soal_selesai; $n++)
                <option value="{{ $n }}" {{ $modul->soal->pluck('nomor_dalam_paket')->contains($n)?'disabled':'' }}>
                    No.{{ $n }}{{ $modul->soal->pluck('nomor_dalam_paket')->contains($n)?' ✓':'' }}
                </option>
                @endfor
            </select>
        </div>

        <div class="form-group">
            <label class="form-label" style="font-size:12px">Pertanyaan <span style="color:var(--red)">*</span></label>
            <textarea id="inp-pertanyaan" class="form-control" rows="2"
                placeholder="cth: What is the main purpose of this email?"></textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:12px">
            @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
            <div class="form-group" style="margin:0">
                <label class="form-label" style="font-size:12px">Pilihan {{ $l }}</label>
                <input type="text" id="inp-p{{ $k }}" class="form-control" placeholder="...">
            </div>
            @endforeach
        </div>

        <div class="form-group">
            <label class="form-label" style="font-size:12px">Jawaban Benar</label>
            <div style="display:flex;gap:6px">
                @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
                <label style="flex:1;text-align:center;padding:8px;border-radius:8px;
                    border:2px solid var(--border);cursor:pointer;font-weight:800;font-size:14px;
                    transition:all .15s" id="jlbl-{{ $k }}" onclick="pilihJ('{{ $k }}')">
                    <input type="radio" name="jawaban" value="{{ $k }}" style="display:none">{{ $l }}
                </label>
                @endforeach
            </div>
        </div>

        <button onclick="simpanSoal()" class="btn btn-primary" id="btn-soal" style="width:100%">
            <i class="fas fa-plus"></i> Tambah Soal
        </button>
    </div>
</div>

</div>

{{-- Soal tersimpan --}}
<div style="position:sticky;top:20px">
    <div class="card">
        <div class="card-header" style="padding:12px 16px">
            <h3 style="font-size:13px">Soal Tersimpan</h3>
            <a href="{{ route('admin.paket-builder.paket', $modul->paket_id) }}"
                class="btn btn-primary btn-sm" style="font-size:11px">
                Daftar Soal Paket →
            </a>
        </div>
        <div id="soal-list">
            @forelse($modul->soal as $s)
            <div style="display:flex;align-items:center;gap:8px;padding:9px 14px;
                border-bottom:1px solid var(--border);font-size:13px" id="si-{{ $s->id }}">
                <div style="width:26px;height:26px;border-radius:6px;background:var(--blue);
                    color:#fff;display:flex;align-items:center;justify-content:center;
                    font-size:11px;font-weight:800;flex-shrink:0">{{ $s->nomor_dalam_paket }}</div>
                <div style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12.5px">
                    {{ mb_strimwidth($s->pertanyaan??'',0,40,'...') }}
                </div>
                <button onclick="hapusSoal({{ $s->id }})"
                    style="background:none;border:none;color:var(--muted);cursor:pointer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @empty
            <div style="padding:20px;text-align:center;color:var(--muted);font-size:13px">
                Belum ada soal
            </div>
            @endforelse
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
const MODUL_ID = {{ $modul->id }};
const CSRF     = '{{ csrf_token() }}';
let curJ       = null;

function uploadGambar(input) {
    const file = input.files[0]; if (!file) return;
    document.getElementById('file-name').textContent = file.name;
    const url = URL.createObjectURL(file);
    document.getElementById('img-preview').src = url;
    document.getElementById('preview-gambar').style.display = 'block';

    const fd = new FormData();
    fd.append('gambar', file);
    fd.append('_token', CSRF);
    fetch(`/admin/paket-builder/modul/${MODUL_ID}/upload-gambar`,{method:'POST',body:fd})
        .then(r=>r.json()).then(d=>{ if(d.ok) showAlert('Gambar diupload.','success'); });
}

function pilihJ(k) {
    curJ = k;
    ['a','b','c','d'].forEach(x=>{
        const l=document.getElementById('jlbl-'+x);
        l.style.background=x===k?'var(--green)':'transparent';
        l.style.borderColor=x===k?'var(--green)':'var(--border)';
        l.style.color=x===k?'#fff':'';
    });
}

function simpanSoal() {
    const pertanyaan = document.getElementById('inp-pertanyaan').value.trim();
    if (!pertanyaan) return showAlert('Pertanyaan tidak boleh kosong.','danger');
    if (!curJ) return showAlert('Pilih jawaban benar.','danger');
    const btn = document.getElementById('btn-soal');
    btn.disabled=true; btn.textContent='Menyimpan...';
    fetch(`/admin/paket-builder/modul/${MODUL_ID}/image-email`,{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
        body:JSON.stringify({
            nomor_dalam_paket: parseInt(document.getElementById('inp-nomor').value),
            pertanyaan,
            pilihan_a:document.getElementById('inp-pa').value,
            pilihan_b:document.getElementById('inp-pb').value,
            pilihan_c:document.getElementById('inp-pc').value,
            pilihan_d:document.getElementById('inp-pd').value,
            jawaban_benar:curJ,
        }),
    }).then(r=>r.json()).then(d=>{
        if(d.ok){showAlert(d.msg,'success');location.reload();}
        else showAlert(d.msg,'danger');
    }).finally(()=>{btn.disabled=false;btn.innerHTML='<i class="fas fa-plus"></i> Tambah Soal';});
}

function hapusSoal(id) {
    if(!confirm('Hapus?'))return;
    fetch(`/admin/paket-builder/soal/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF,'Accept':'application/json'}})
        .then(r=>r.json()).then(d=>{if(d.ok)document.getElementById('si-'+id)?.remove();});
}

function showAlert(msg,type) {
    const el=document.getElementById('alert');
    const c={success:['rgba(22,163,74,.1)','rgba(22,163,74,.3)','#4ade80'],danger:['rgba(220,38,38,.1)','rgba(220,38,38,.3)','#f87171']}[type];
    el.style.cssText=`display:block;background:${c[0]};border:1px solid ${c[1]};border-radius:8px;padding:11px 14px;color:${c[2]};font-size:13px`;
    el.textContent=msg; if(type==='success')setTimeout(()=>el.style.display='none',4000);
}
</script>
@endpush
