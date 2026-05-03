<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — TOEFL ITP Polman</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Plus Jakarta Sans',sans-serif;background:#eef2f7;
            min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
        .wrap{width:100%;max-width:420px}
        .brand{text-align:center;margin-bottom:24px}
        .brand-logo{display:inline-flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:4px}
        .brand-icon{width:40px;height:40px;border-radius:10px;
            background:linear-gradient(135deg,#1a56db,#1e3a8a);
            display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;font-weight:900}
        .brand-text{font-size:18px;font-weight:800;color:#0f2456}
        .brand-text span{color:#1a56db}
        .brand-sub{font-size:12px;color:#64748b;margin-top:4px}
        .card{background:#fff;border-radius:20px;padding:32px;box-shadow:0 4px 24px rgba(0,0,0,.08)}
        .card-title{font-size:20px;font-weight:800;color:#0f2456;margin-bottom:4px}
        .card-sub{font-size:13px;color:#64748b;margin-bottom:24px}
        .alert{padding:11px 14px;border-radius:9px;font-size:13px;margin-bottom:16px;display:flex;align-items:center;gap:9px}
        .alert-err{background:#fff1f2;border:1px solid #fecdd3;color:#be123c}
        .alert-ok{background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d}
        .form-group{margin-bottom:18px}
        .form-label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:7px}
        .input-wrap{position:relative}
        .input-wrap i.icon{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:14px}
        .input-wrap input{width:100%;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:10px;
            padding:11px 14px 11px 40px;color:#1e293b;font-size:14px;font-family:inherit;outline:none;transition:border-color .15s}
        .input-wrap input:focus{border-color:#1a56db;background:#fff}
        .input-wrap input::placeholder{color:#9ca3af}
        .eye-btn{position:absolute;right:13px;top:50%;transform:translateY(-50%);
            background:none;border:none;cursor:pointer;color:#9ca3af;font-size:14px;padding:2px}
        .eye-btn:hover{color:#1a56db}
        .btn-submit{width:100%;background:linear-gradient(135deg,#1a56db,#1e40af);color:#fff;
            border:none;border-radius:10px;padding:13px;font-size:15px;font-weight:700;
            cursor:pointer;font-family:inherit;transition:all .15s;letter-spacing:.3px}
        .btn-submit:hover{background:linear-gradient(135deg,#1e40af,#1e3a8a);box-shadow:0 4px 12px rgba(26,86,219,.35)}
        .footer-link{text-align:center;font-size:13.5px;color:#64748b;margin-top:18px}
        .footer-link a{color:#1a56db;font-weight:700;text-decoration:none}
        .footer-link a:hover{text-decoration:underline}
        .back-link{text-align:center;margin-top:12px}
        .back-link a{font-size:12.5px;color:#9ca3af;text-decoration:none}
        .back-link a:hover{color:#64748b}
    </style>
</head>
<body>
<div class="wrap">
    <div class="brand">
        <a href="/" class="brand-logo">
            <div class="brand-icon">T</div>
            <span class="brand-text">TOEFL <span>Prep</span></span>
        </a>
        <div class="brand-sub">Politeknik Manufaktur Bandung — UPA Bahasa</div>
    </div>

    <div class="card">
        <div class="card-title">Login ke Akun Anda</div>
        <div class="card-sub">Masukkan email dan password untuk melanjutkan</div>

        @if(session('error'))
        <div class="alert alert-err"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif
        @if(session('success'))
        <div class="alert alert-ok"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        <form method="POST" action="/login-process">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <div class="input-wrap">
                    <i class="fas fa-envelope icon"></i>
                    <input type="email" name="email" placeholder="Email" required autofocus value="{{ old('email') }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock icon"></i>
                    <input type="password" name="password" placeholder="Password" required id="pw">
                    <button type="button" class="eye-btn" onclick="togglePw()">
                        <i class="fas fa-eye" id="eye-icon"></i>
                        <span style="font-size:12px;font-weight:600"> Lihat</span>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn-submit">Login</button>
        </form>

        <div class="footer-link">Belum punya akun? <a href="/register">Register</a></div>
    </div>

    <div class="back-link"><a href="/"><i class="fas fa-arrow-left" style="font-size:10px"></i> Kembali ke Beranda</a></div>
</div>
<script>
function togglePw() {
    const pw = document.getElementById('pw');
    const ic = document.getElementById('eye-icon');
    if (pw.type === 'password') { pw.type = 'text'; ic.className = 'fas fa-eye-slash'; }
    else { pw.type = 'password'; ic.className = 'fas fa-eye'; }
}
</script>
</body>
</html>
