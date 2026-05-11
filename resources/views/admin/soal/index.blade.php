@extends('layouts.admin')
@section('title','Bank Soal')
@section('page-title','Bank Soal')
@section('breadcrumb','Admin / Bank Soal')

@push('styles')
<style>
.main-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:8px}
.main-card{border:1px solid var(--border);border-radius:16px;padding:28px 24px;
    text-decoration:none;color:var(--text);display:flex;flex-direction:column;
    align-items:flex-start;gap:16px;transition:all .2s;background:var(--navy-light);cursor:pointer}
.main-card:hover{transform:translateY(-4px);border-color:var(--accent);
    box-shadow:0 8px 24px rgba(0,0,0,.25)}
.main-card-ico{width:56px;height:56px;border-radius:14px;
    display:flex;align-items:center;justify-content:center;font-size:26px}
.main-card-title{font-size:17px;font-weight:800;margin-bottom:4px}
.main-card-desc{font-size:13px;color:var(--muted);line-height:1.6}
.main-card-footer{margin-top:auto;font-size:12.5px;font-weight:600;
    display:flex;align-items:center;gap:6px}
</style>
@endpush

@section('content')
<div style="max-width:900px">
    <div class="main-grid">

        <a href="{{ route('admin.paket-builder.index') }}" class="main-card">
            <div class="main-card-ico" style="background:rgba(26,86,219,.15)">📦</div>
            <div>
                <div class="main-card-title">Paket Soal</div>
                <div class="main-card-desc">
                    Kumpulan soal lengkap 1 TOEFL.<br>
                    Berisi semua kategori: Reading, Listening & Structure dalam 1 paket.
                </div>
            </div>
            <div class="main-card-footer" style="color:var(--accent)">
                Buka Paket Soal <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="{{ route('admin.soal.group') }}" class="main-card">
            <div class="main-card-ico" style="background:rgba(234,88,12,.12)">🗂️</div>
            <div>
                <div class="main-card-title">Group Soal</div>
                <div class="main-card-desc">
                    Soal dikelompokkan per kategori.<br>
                    Group Reading, Group Listening, Group Structure.
                </div>
            </div>
            <div class="main-card-footer" style="color:#fb923c">
                Buka Group Soal <i class="fas fa-arrow-right"></i>
            </div>
        </a>

        <a href="{{ route('admin.soal.modul') }}" class="main-card">
            <div class="main-card-ico" style="background:rgba(16,185,129,.12)">🧩</div>
            <div>
                <div class="main-card-title">Modul Soal</div>
                <div class="main-card-desc">
                    Tipe interaksi soal spesifik.<br>
                    Passage, Missing Letters, Gambar/Email, Structure.
                </div>
            </div>
            <div class="main-card-footer" style="color:#34d399">
                Buka Modul Soal <i class="fas fa-arrow-right"></i>
            </div>
        </a>

    </div>
</div>
@endsection
