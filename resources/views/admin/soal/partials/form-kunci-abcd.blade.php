<div class="form-section">
    <div class="fs-title"><i class="fas fa-key" style="color:var(--green)"></i>
        {{ $label ?? 'Kunci Jawaban' }} <span style="color:var(--red)">*</span>
    </div>
    <div class="jawaban-radio">
        @foreach(['a'=>'A','b'=>'B','c'=>'C','d'=>'D'] as $k=>$l)
        <div class="jr-opt">
            <input type="radio" name="jawaban_benar" id="jkunci-{{ $k }}" value="{{ $k }}"
                {{ old('jawaban_benar')===$k ? 'checked' : '' }} required>
            <label for="jkunci-{{ $k }}">{{ $l }}</label>
        </div>
        @endforeach
    </div>
</div>
