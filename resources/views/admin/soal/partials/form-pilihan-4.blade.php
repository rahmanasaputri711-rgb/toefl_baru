<div class="form-section">
    <div class="fs-title"><i class="fas fa-list-ul" style="color:var(--accent)"></i>
        {{ $label ?? 'Pilihan Jawaban' }}
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
        <div class="form-group">
            <label class="form-label">Pilihan {{ $l }}</label>
            <input type="text" name="pilihan_{{ $k }}" class="form-control"
                value="{{ old('pilihan_'.$k) }}" placeholder="Isi pilihan {{ $l }}...">
        </div>
        @endforeach
    </div>
</div>
