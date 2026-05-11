<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:14px">
    <div class="form-group">
        <label class="form-label">No. Soal <span style="color:var(--red)">*</span></label>
        <input type="number" name="nomor_soal" class="form-control"
            value="{{ $nomorBerikut ?? 1 }}" min="1" max="10" required>
    </div>
    <div class="form-group">
        <label class="form-label">Kesulitan <span style="color:var(--red)">*</span></label>
        <select name="tingkat_kesulitan" class="form-control" required>
            <option value="easy">Easy</option>
            <option value="medium" selected>Medium</option>
            <option value="hard">Hard</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Skill / Materi</label>
        <input type="text" name="skill_materi" class="form-control"
            placeholder="cth: Main Idea">
    </div>
</div>
