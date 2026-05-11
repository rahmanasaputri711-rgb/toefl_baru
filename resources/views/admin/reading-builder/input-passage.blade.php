@extends('layouts.admin')
@section('title','Input Passage')
@section('page-title','Input Academic Passage')
@section('breadcrumb','Admin / Reading Builder / Passage')
@section('content')
<p style="color:var(--muted)">Coming soon — halaman input passage dengan form soal per nomor.</p>
<a href="{{ route('admin.reading-builder.paket', $paket->id) }}" class="btn btn-outline">Kembali</a>
@endsection
