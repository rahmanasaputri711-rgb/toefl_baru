@extends('layouts.admin')
@section('title','Input Gambar/Email')
@section('page-title','Input Gambar / Email')
@section('breadcrumb','Admin / Reading Builder / Image Email')
@section('content')
<p style="color:var(--muted)">Coming soon — halaman upload gambar/email + soal pilihan ganda.</p>
<a href="{{ route('admin.reading-builder.paket', $paket->id) }}" class="btn btn-outline">Kembali</a>
@endsection
