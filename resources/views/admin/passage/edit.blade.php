@extends('layouts.admin')

@section('title','Edit Passage')
@section('page-title','Edit Passage')

@section('content')

<div class="card">
    <div class="card-header">
        <h3>Edit Passage</h3>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.passage.update', $passage->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Judul</label>
                <input type="text"
                    name="judul"
                    class="form-control"
                    value="{{ old('judul', $passage->judul) }}">
            </div>

            <div class="form-group">
                <label>Teks Passage</label>
                <textarea name="teks"
                    class="form-control"
                    rows="10">{{ old('teks', $passage->teks) }}</textarea>
            </div>

            <div class="form-group">
                <label>Tipe Paket</label>

                <select name="tipe_paket" class="form-control">
                    @foreach(['praktik','mini','simulasi','full'] as $t)
                        <option value="{{ $t }}"
                            {{ $passage->tipe_paket == $t ? 'selected' : '' }}>
                            {{ strtoupper($t) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan Perubahan
            </button>

        </form>

    </div>
</div>

@endsection