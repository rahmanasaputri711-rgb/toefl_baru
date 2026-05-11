@if(!isset($tipe) || $tipe !== 'cs')
{{-- Tidak perlu untuk click_sentence --}}
<div style="margin-bottom:14px">
    <label class="form-label" style="margin-bottom:8px">
        Pilihan Jawaban <span style="color:var(--red)">*</span>
    </label>
    @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $key=>$label)
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
        <span style="width:28px;height:28px;border-radius:50%;background:rgba(26,86,219,.15);
            color:var(--accent);font-weight:700;font-size:13px;display:flex;
            align-items:center;justify-content:center;flex-shrink:0">{{ $label }}</span>
        <input type="text" name="pilihan_{{ $key }}" class="form-control"
            placeholder="Pilihan {{ $label }}" required>
    </div>
    @endforeach
</div>
@endif
