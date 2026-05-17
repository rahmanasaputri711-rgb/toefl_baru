<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TOEFL Prep — Polman Babel</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root{
  --primary:#2563EB;--primary-h:#1D4ED8;--primary-lt:#3B82F6;
  --navy:#0F172A;--blue-light:#EFF6FF;--blue-pale:#DBEAFE;
  --bg:#F1F5F9;--white:#FFFFFF;--border:#E2E8F0;
  --text:#1E293B;--muted:#64748B;--muted-lt:#94A3B8;
  --green:#22C55E;--red:#EF4444;--warning:#F59E0B;
  --shadow:0 4px 16px rgba(37,99,235,.10);
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;color:var(--text);background:var(--white);overflow-x:hidden}
.navbar{background:var(--white);border-bottom:1.5px solid var(--border);padding:0 6%;height:64px;
  display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:200;
  box-shadow:0 1px 4px rgba(0,0,0,.05)}
.nav-brand{display:flex;align-items:center;gap:8px;text-decoration:none}
.nav-brand-icon{width:34px;height:34px;border-radius:9px;
  background:linear-gradient(135deg,var(--primary),var(--primary-h));
  display:flex;align-items:center;justify-content:center;color:#fff;font-size:15px;font-weight:900}
.nav-brand-name{font-size:16px;font-weight:800;color:var(--navy)}
.nav-brand-name span{color:var(--primary)}
.nav-center{display:flex;align-items:center;gap:28px}
.nav-center a{font-size:14px;font-weight:600;color:var(--muted);text-decoration:none;transition:color .15s}
.nav-center a:hover{color:var(--primary)}
.nav-right{display:flex;align-items:center;gap:8px}
.btn-login{padding:8px 20px;border-radius:8px;font-size:13.5px;font-weight:600;color:var(--primary);
  border:1.5px solid var(--primary);background:transparent;text-decoration:none;transition:all .15s}
.btn-login:hover{background:var(--blue-light)}
.btn-register{padding:8px 20px;border-radius:8px;font-size:13.5px;font-weight:600;
  color:#fff;background:var(--primary);text-decoration:none;transition:all .15s}
.btn-register:hover{background:var(--primary-h)}
.hero{background:var(--white);
  background-image:linear-gradient(to bottom,transparent 30%,var(--white) 100%),
    radial-gradient(circle,rgba(148,163,184,.45) 1px,transparent 1px);
  background-size:100% 100%,28px 28px;
  padding:36px 6% 0;display:flex;align-items:flex-end;justify-content:space-between;
  gap:16px;min-height:415px;overflow:visible;position:relative}
.hero-left{flex:1;padding-bottom:48px;max-width:500px;position:relative;z-index:1}
.hero-title{font-size:40px;font-weight:800;color:var(--navy);line-height:1.18;margin-bottom:14px}
.hero-title .blue{color:var(--primary)}
.hero-desc{font-size:14.5px;color:var(--muted);line-height:1.7;margin-bottom:28px;max-width:420px}
.hero-actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center;position:relative}
.btn-hero-primary{padding:12px 28px;border-radius:10px;font-size:14.5px;font-weight:700;
  color:#fff;background:var(--primary);border:none;cursor:pointer;
  box-shadow:0 4px 14px rgba(37,99,235,.35);transition:all .2s;
  display:inline-flex;align-items:center;gap:8px;font-family:inherit;
  animation:heroPulse 2.5s ease-in-out infinite}
.btn-hero-primary:hover{background:var(--primary-h);transform:translateY(-2px);animation:none}
@keyframes heroPulse{0%,100%{box-shadow:0 4px 14px rgba(37,99,235,.35)}
  50%{box-shadow:0 4px 24px rgba(37,99,235,.6),0 0 0 6px rgba(37,99,235,.1)}}
.btn-hero-outline{padding:12px 28px;border-radius:10px;font-size:14px;font-weight:600;
  color:var(--primary);background:var(--white);border:1.5px solid var(--primary);
  text-decoration:none;transition:all .15s;display:inline-flex;align-items:center;gap:7px}
.btn-hero-outline:hover{background:var(--blue-light)}
.lets-go-wrap{position:absolute;left:0;top:56px;pointer-events:none;display:flex;flex-direction:column;align-items:flex-start}
.lets-go-tooltip{display:inline-flex;align-items:center;gap:5px;
  background:var(--navy);color:#fff;font-size:12px;font-weight:700;
  padding:5px 12px;border-radius:20px;white-space:nowrap;
  box-shadow:0 4px 12px rgba(15,23,42,.2);
  animation:tooltipBounce 1.8s ease-in-out infinite}
@keyframes tooltipBounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.hero-right{flex-shrink:0;width:500px;display:flex;align-items:flex-end;justify-content:center;
  padding-bottom:25px;position:relative;z-index:1}
.hero-right img{width:100%;max-width:500px;display:block}
#practice-wrap{overflow:hidden;max-height:0;opacity:0;
  transition:max-height .7s cubic-bezier(.16,1,.3,1),opacity .4s ease;background:var(--bg)}
#practice-wrap.open{max-height:9999px;opacity:1}
.practice-container{margin:0 4% 32px;background:var(--white);border-radius:24px;
  border:1.5px solid var(--blue-pale);box-shadow:0 8px 48px rgba(37,99,235,.12);
  overflow:hidden;position:relative}
.practice-header{padding:20px 24px 18px;border-bottom:1px solid var(--border);
  display:flex;align-items:center;gap:16px;flex-wrap:wrap;
  background:linear-gradient(135deg,#F8FAFF 0%,var(--white) 100%)}
.ph-left{flex:1;display:flex;align-items:center;gap:12px}
.ph-title{font-size:17px;font-weight:800;color:var(--navy)}
.ph-sub{font-size:13px;color:var(--muted);margin-top:2px}
.ph-progress{display:flex;align-items:center;gap:10px}
.ph-prog-label{font-size:12px;color:var(--muted);font-weight:600;white-space:nowrap}
.ph-prog-bar{width:120px;height:6px;background:#E2E8F0;border-radius:3px;overflow:hidden}
.ph-prog-fill{height:100%;background:linear-gradient(90deg,var(--primary),var(--primary-lt));
  border-radius:3px;transition:width .4s cubic-bezier(.16,1,.3,1)}
.ph-prog-num{font-size:13px;font-weight:800;color:var(--primary);white-space:nowrap}
.btn-keluar{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;
  border-radius:8px;border:1.5px solid var(--border);background:var(--white);
  font-size:12.5px;font-weight:600;color:var(--muted);cursor:pointer;transition:all .15s;font-family:inherit}
.btn-keluar:hover{border-color:var(--red);color:var(--red)}
.section-tabs{display:flex;gap:6px;padding:12px 24px;border-bottom:1px solid var(--border)}
.stab{padding:5px 14px;border-radius:20px;font-size:12px;font-weight:700;
  border:1.5px solid var(--border);color:var(--muted);background:transparent;transition:all .2s}
.stab.active-listen{background:#EFF6FF;border-color:#BFDBFE;color:#1D4ED8}
.stab.active-read{background:#F0FDF4;border-color:#BBF7D0;color:#16A34A}
.stab.active-struct{background:#FFF7ED;border-color:#FED7AA;color:#C2410C}
.practice-body{display:grid;grid-template-columns:140px 1fr 260px;min-height:480px}
.pnav{padding:20px 14px;border-right:1px solid var(--border);display:flex;flex-direction:column;
  align-items:center;gap:10px;background:linear-gradient(180deg,#F8FAFF 0%,var(--white) 100%)}
.pnav-label{font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;
  letter-spacing:1px;margin-bottom:4px}
.pnav-btn{width:48px;height:52px;border-radius:12px;border:1.5px solid var(--border);
  background:var(--white);font-size:14px;font-weight:800;color:var(--muted);
  cursor:pointer;transition:all .2s;display:flex;flex-direction:column;
  align-items:center;justify-content:center;gap:1px;font-family:inherit}
.pnav-btn:hover{border-color:var(--primary);color:var(--primary);background:var(--blue-light)}
.pnav-btn.active{background:linear-gradient(135deg,var(--primary),var(--primary-h));
  border-color:transparent;color:#fff;box-shadow:0 4px 12px rgba(37,99,235,.3)}
.pnav-btn.answered{background:#F0FDF4;border-color:#BBF7D0;color:#16A34A}
.pnav-btn.wrong-ans{background:#FEF2F2;border-color:#FECACA;color:#DC2626}
.pnav-sect{font-size:9px;font-weight:700;background:rgba(255,255,255,.3);border-radius:3px;padding:1px 4px}
.pnav-deco{margin-top:auto;opacity:.25;font-size:28px;color:var(--primary)}
.qarea{padding:24px 28px;flex:1;display:flex;flex-direction:column;border-right:1px solid var(--border)}
.q-panel{display:none;flex-direction:column;gap:16px;height:100%}
.q-panel.show{display:flex;animation:fadeSlideUp .35s ease}
@keyframes fadeSlideUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.listen-hint{font-size:12px;color:var(--muted);font-style:italic;display:flex;align-items:center;gap:6px}
.audio-player{background:linear-gradient(135deg,#EFF6FF,#DBEAFE);
  border:1px solid #BFDBFE;border-radius:14px;padding:14px 16px;
  display:flex;align-items:center;gap:14px}
.ap-play{width:42px;height:42px;border-radius:50%;background:var(--primary);
  border:none;color:#fff;font-size:15px;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  transition:all .15s;flex-shrink:0;font-family:inherit}
.ap-play:hover{background:var(--primary-h);transform:scale(1.05)}
.ap-play.playing{background:#10B981}
.waveform{flex:1;height:36px;overflow:hidden;border-radius:6px;cursor:pointer}
.wv-bars{display:flex;align-items:center;gap:2px;height:100%;padding:2px}
.wv-bar{background:rgba(37,99,235,.25);border-radius:2px;flex:1;transition:background .1s}
.wv-bar.played{background:var(--primary)}
.ap-time{font-size:12px;font-weight:700;color:var(--primary);font-family:monospace;white-space:nowrap}
.q-text{font-size:15px;font-weight:700;color:var(--navy);line-height:1.5}
.options{display:flex;flex-direction:column;gap:8px}
.opt-btn{display:flex;align-items:center;gap:12px;padding:11px 16px;
  border:1.5px solid var(--border);border-radius:12px;background:var(--white);
  cursor:pointer;transition:all .18s;text-align:left;font-family:inherit;width:100%}
.opt-btn:hover:not(.disabled){border-color:#93C5FD;background:#F0F7FF;transform:translateX(3px)}
.opt-btn.selected{border-color:var(--primary);background:#EFF6FF}
.opt-btn.correct{border-color:var(--green);background:#F0FDF4}
.opt-btn.wrong{border-color:var(--red);background:#FEF2F2}
.opt-btn.disabled{cursor:default}
.opt-circle{width:28px;height:28px;border-radius:50%;border:1.5px solid var(--border);
  display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;
  flex-shrink:0;transition:all .15s;color:var(--muted)}
.opt-btn.selected .opt-circle{background:var(--primary);border-color:var(--primary);color:#fff}
.opt-btn.correct .opt-circle{background:var(--green);border-color:var(--green);color:#fff}
.opt-btn.wrong .opt-circle{background:var(--red);border-color:var(--red);color:#fff}
.opt-text{font-size:13.5px;color:var(--text);line-height:1.4}
.reading-passage{background:#FAFBFF;border:1px solid var(--blue-pale);border-radius:12px;
  padding:16px 18px;font-size:13.5px;line-height:1.8;color:var(--text);max-height:200px;overflow-y:auto}
.reading-passage mark{background:#FEF9C3;padding:0 2px;border-radius:3px}
.struct-sentence{font-size:16px;line-height:1.8;color:var(--navy);background:var(--blue-light);
  border:1px solid #BFDBFE;border-radius:12px;padding:16px 18px;font-weight:500}
.struct-blank{display:inline-block;min-width:80px;border-bottom:2.5px solid var(--primary);
  text-align:center;color:var(--primary);font-weight:800;padding:0 4px;vertical-align:baseline}
.explanation{border-radius:12px;padding:14px 16px;display:flex;flex-direction:column;gap:6px;
  animation:fadeSlideUp .3s ease}
.explanation.correct-exp{background:#F0FDF4;border:1.5px solid #BBF7D0}
.explanation.wrong-exp{background:#FEF2F2;border:1.5px solid #FECACA}
.exp-header{display:flex;align-items:center;gap:8px;font-size:13.5px;font-weight:800}
.exp-correct{color:#16A34A}.exp-wrong{color:#DC2626}
.exp-body{font-size:13px;color:var(--text);line-height:1.6}
.q-footer{display:flex;justify-content:space-between;align-items:center;margin-top:auto;padding-top:16px}
.btn-prev{display:inline-flex;align-items:center;gap:7px;padding:10px 20px;
  border-radius:10px;border:1.5px solid var(--border);background:var(--white);
  font-size:13.5px;font-weight:600;color:var(--muted);cursor:pointer;transition:all .15s;font-family:inherit}
.btn-prev:hover:not(:disabled){border-color:var(--primary);color:var(--primary)}
.btn-prev:disabled{opacity:.4;cursor:default}
.btn-next{display:inline-flex;align-items:center;gap:7px;padding:10px 24px;
  border-radius:10px;border:none;
  background:linear-gradient(135deg,var(--primary),var(--primary-h));
  font-size:13.5px;font-weight:700;color:#fff;cursor:pointer;
  transition:all .18s;font-family:inherit;box-shadow:0 4px 12px rgba(37,99,235,.25)}
.btn-next:hover{transform:translateY(-1px)}
.rpanel{padding:18px 16px;display:flex;flex-direction:column;gap:14px;
  background:linear-gradient(180deg,#FAFBFF 0%,var(--white) 100%)}
.rcard{background:var(--white);border:1px solid var(--border);border-radius:14px;
  padding:14px;transition:box-shadow .2s}
.rcard:hover{box-shadow:var(--shadow)}
.rcard-header{display:flex;align-items:center;gap:8px;margin-bottom:8px}
.rcard-ico{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;
  justify-content:center;font-size:14px;flex-shrink:0}
.rcard-ico.purple{background:#F3E8FF;color:#7C3AED}
.rcard-ico.orange{background:#FFF7ED;color:#EA580C}
.rcard-ico.blue{background:#EFF6FF;color:#2563EB}
.rcard-title{font-size:13px;font-weight:700;color:var(--navy)}
.rcard-body{font-size:12.5px;color:var(--muted);line-height:1.65}
.tip-item{display:flex;align-items:flex-start;gap:8px;font-size:12.5px;
  color:var(--text);padding:5px 0;border-bottom:1px solid var(--border);line-height:1.4}
.tip-item:last-child{border-bottom:none;padding-bottom:0}
#result-panel{display:none;padding:32px;text-align:center;animation:fadeSlideUp .4s ease}
.result-score{display:inline-flex;align-items:center;justify-content:center;
  width:100px;height:100px;border-radius:50%;
  background:linear-gradient(135deg,var(--primary),var(--primary-h));
  font-size:30px;font-weight:800;color:#fff;box-shadow:0 8px 24px rgba(37,99,235,.3);margin-bottom:20px}
.result-breakdown{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:24px}
.rb-item{background:var(--bg);border-radius:12px;padding:14px 10px;text-align:center}
.rb-num{font-size:22px;font-weight:800;color:var(--primary);margin-bottom:2px}
.rb-lbl{font-size:11.5px;color:var(--muted);font-weight:600}
.result-ctas{display:flex;flex-direction:column;gap:10px;max-width:320px;margin:0 auto 20px}
.cta-btn{padding:11px 20px;border-radius:10px;font-size:13.5px;font-weight:700;
  text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px;
  transition:all .15s;font-family:inherit;cursor:pointer;border:none}
.cta-primary{background:var(--primary);color:#fff;box-shadow:0 4px 12px rgba(37,99,235,.25)}
.cta-primary:hover{background:var(--primary-h)}
.cta-outline{background:var(--white);color:var(--primary);border:1.5px solid var(--primary)}
.cta-ghost{background:var(--bg);color:var(--text)}
.promo-strip{background:var(--bg);border-top:1px solid var(--border);
  padding:14px 24px;display:grid;grid-template-columns:repeat(4,1fr)}
.ps-item{display:flex;align-items:center;gap:10px;padding:0 12px;border-right:1px solid var(--border)}
.ps-item:first-child{padding-left:0}
.ps-item:last-child{border-right:none}
.ps-ico{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px}
.ps-title{font-size:12.5px;font-weight:700;color:var(--navy)}
.ps-sub{font-size:11.5px;color:var(--muted)}
.section-wrap{padding:52px 6%}
.section-wrap.bg-light{background:var(--bg)}
.section-wrap.bg-white{background:var(--white)}
.section-tag{font-size:11.5px;font-weight:700;color:var(--primary);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:6px}
.section-h2{font-size:24px;font-weight:800;color:var(--navy);margin-bottom:8px}
.section-sub{font-size:14px;color:var(--muted);line-height:1.7;max-width:520px;margin-bottom:28px}
.about-card{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:36px;box-shadow:0 2px 12px rgba(15,23,42,.04)}
.about-features{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:28px}
.af-item{display:flex;align-items:flex-start;gap:14px}
.af-icon{width:46px;height:46px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:17px}
.af-icon.blue{background:#EFF6FF;color:#2563EB}
.af-icon.blue-2{background:#DBEAFE;color:#1D4ED8}
.af-icon.indigo{background:#EEF2FF;color:#4F46E5}
.af-icon.purple{background:#F3E8FF;color:#7C3AED}
.af-icon.cyan{background:#ECFEFF;color:#0891B2}
.af-icon.violet{background:#EDE9FE;color:#6D28D9}
.af-title{font-size:14.5px;font-weight:700;color:var(--navy);margin-bottom:4px}
.af-desc{font-size:13px;color:var(--muted);line-height:1.6}
.materi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px}
.materi-card{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:22px 20px;text-decoration:none;transition:all .2s;display:block}
.materi-card:hover{border-color:var(--primary);box-shadow:0 4px 16px rgba(37,99,235,.1);transform:translateY(-2px)}
.mc-top{display:flex;align-items:center;gap:12px;margin-bottom:12px}
.mc-icon{width:44px;height:44px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:18px}
.mc-title{font-size:15px;font-weight:700;color:var(--navy)}
.mc-sub{font-size:12px;color:var(--muted-lt);margin-top:1px}
.mc-desc{font-size:12.5px;color:var(--muted);line-height:1.6}
.btn-center{text-align:center}
.btn-outlined{display:inline-flex;align-items:center;gap:8px;padding:10px 28px;border-radius:8px;font-size:13.5px;font-weight:600;color:#fff;background:var(--primary);text-decoration:none;transition:all .15s}
.btn-outlined:hover{background:var(--primary-h)}
.tes-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:18px}
.tes-card{background:var(--white);border:1px solid var(--border);border-radius:14px;padding:22px 20px;display:flex;align-items:center;gap:14px;transition:all .2s}
.tes-card:hover{border-color:var(--primary)}
.tc-icon{width:44px;height:44px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:18px}
.tc-title{font-size:14.5px;font-weight:700;color:var(--navy)}
.tc-desc{font-size:12px;color:var(--muted);margin-top:2px}
.tes-note{display:flex;align-items:center;justify-content:center;gap:10px;font-size:13px;color:var(--muted)}
.tes-note-link{display:inline-flex;align-items:center;gap:5px;background:var(--primary);color:#fff;padding:6px 16px;border-radius:7px;font-size:13px;font-weight:600;text-decoration:none;transition:all .15s}
.tes-note-link:hover{background:var(--primary-h)}
.cek-skor{background:var(--white);padding:52px 6%;text-align:center;position:relative}
.cek-form{display:flex;gap:8px;max-width:460px;margin:0 auto}
.cek-input{flex:1;padding:11px 16px;border:1.5px solid var(--border);border-radius:8px;font-size:13.5px;font-family:inherit;outline:none;color:var(--text);transition:border-color .15s}
.cek-input:focus{border-color:var(--primary)}
.btn-cek{padding:11px 24px;border-radius:8px;background:var(--primary);color:#fff;font-size:13.5px;font-weight:700;border:none;cursor:pointer;font-family:inherit;transition:all .15s;white-space:nowrap}
.btn-cek:hover{background:var(--primary-h)}
.section-divider{height:1px;background:var(--border);margin:0 6%}
footer{background:var(--navy);color:rgba(255,255,255,.7);padding:40px 6% 22px}
.footer-top{display:flex;gap:48px;flex-wrap:wrap;padding-bottom:28px;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:20px}
.footer-brand{flex:0 0 260px}
.f-logo{display:flex;align-items:center;gap:8px;margin-bottom:10px}
.f-logo-icon{width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#60A5FA,var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;font-weight:900}
.f-logo-text{font-size:15px;font-weight:800;color:#fff}
.f-desc{font-size:13px;line-height:1.7}
.footer-col h4{font-size:12px;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px}
.footer-col ul{list-style:none;display:flex;flex-direction:column;gap:7px}
.footer-col a{font-size:13px;color:rgba(255,255,255,.6);text-decoration:none;transition:color .15s}
.footer-col a:hover{color:#fff}
.footer-bottom{display:flex;justify-content:space-between;align-items:center;font-size:12px;color:rgba(255,255,255,.35);flex-wrap:wrap;gap:8px}
</style>
</head>
<body>

<nav class="navbar">
  <a href="/" class="nav-brand">
    <div class="nav-brand-icon"><i class="fas fa-graduation-cap" style="font-size:14px"></i></div>
    <span class="nav-brand-name">TOEFL <span>Prep</span></span>
  </a>
  <div class="nav-center">
    <a href="#materi">Materi</a>
    <a href="#practice-wrap">Praktik</a>
    <a href="#tes">Tes</a>
    <a href="#jadwal">Jadwal</a>
  </div>
  <div class="nav-right">
    <a href="/login" class="btn-login">Login</a>
    <a href="/register" class="btn-register">Register</a>
  </div>
</nav>

<!-- AUDIO FILES — taruh di public/audio/practice/ -->
<audio id="audio1" preload="none"><source src="/audio/practice/soal1_praktik.mp3" type="audio/mpeg"></audio>
<audio id="audio2" preload="none"><source src="/audio/practice/soal2_praktik.mp3" type="audio/mpeg"></audio>

<section class="hero" style="position:relative">
  <div style="position:absolute;top:40px;left:4%;width:220px;height:220px;border-radius:50%;background:#BFDBFE;opacity:.28;pointer-events:none;z-index:0"></div>
  <div class="hero-left">
    <h1 class="hero-title">Practice &amp; Test<br><span class="blue">TOEFL</span> Online</h1>
    <p class="hero-desc">Latihan dan tes TOEFL dengan sistem modern.<br>Platform resmi UPA Bahasa Politeknik Manufaktur Negeri Bangka Belitung.</p>
    <div class="hero-actions">
      <button class="btn-hero-primary" onclick="startPractice()">
        <i class="fas fa-play-circle"></i> Start Practice
      </button>
      <a href="#jadwal" class="btn-hero-outline">
        <i class="fas fa-calendar-alt"></i> Lihat Jadwal
      </a>
      <div class="lets-go-wrap" id="letsGoWrap">
        <svg width="80" height="40" viewBox="0 0 80 40" fill="none">
          <path d="M10 8 Q40 0 55 20 Q65 32 52 36" stroke="#2563EB" stroke-width="2" stroke-dasharray="4 3" fill="none"/>
          <polygon points="48,32 56,40 58,30" fill="#2563EB"/>
        </svg>
        <div class="lets-go-tooltip"><i class="fas fa-arrow-up" style="font-size:10px"></i> Let's Go!</div>
      </div>
    </div>
  </div>
  <div class="hero-right">
    <img src="/images/hero-vector.png" alt="TOEFL Illustration"
      onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 400 300%22><rect fill=%22%23EFF6FF%22 width=%22400%22 height=%22300%22 rx=%2220%22/><text x=%22200%22 y=%22160%22 text-anchor=%22middle%22 font-size=%2280%22>🎧</text></svg>'">
  </div>
</section>

<!-- PRACTICE SECTION -->
<div id="practice-wrap">
  <div style="background:linear-gradient(to bottom,var(--white),var(--bg));height:24px"></div>
  <div class="practice-container">

    <div style="position:absolute;top:24px;right:24px;display:flex;gap:5px;pointer-events:none;z-index:1">
      <div style="width:6px;height:6px;border-radius:50%;background:#BFDBFE"></div>
      <div style="width:6px;height:6px;border-radius:50%;background:#93C5FD"></div>
      <div style="width:6px;height:6px;border-radius:50%;background:#60A5FA"></div>
    </div>

    <div class="practice-header">
      <div class="ph-left">
        <span style="font-size:22px">⭐</span>
        <div>
          <div class="ph-title">Try Free TOEFL  Practice</div>
          <div class="ph-sub">Coba 5 soal TOEFL gratis dari Listening, Reading, dan Structure.</div>
        </div>
      </div>
      <div class="ph-progress">
        <span class="ph-prog-label">Progress Soal</span>
        <div class="ph-prog-bar"><div class="ph-prog-fill" id="progressFill" style="width:20%"></div></div>
        <span class="ph-prog-num" id="progressNum">1 / 5</span>
      </div>
      <button class="btn-keluar" onclick="closePractice()">
        <i class="fas fa-sign-out-alt"></i> Keluar
      </button>
    </div>

    <div class="section-tabs">
      <div class="stab active-listen" id="stab-1">🎧 Listening</div>
      <div class="stab" id="stab-3">📖 Reading</div>
      <div class="stab" id="stab-5">✏️ Structure</div>
    </div>

    <div class="practice-body" id="practice-body">

      <!-- Left Nav -->
      <div class="pnav">
        <div class="pnav-label">Soal</div>
        <button class="pnav-btn active" id="nb-1" onclick="goToQ(1)"><span>1</span><span class="pnav-sect">L</span></button>
        <button class="pnav-btn" id="nb-2" onclick="goToQ(2)"><span>2</span><span class="pnav-sect">L</span></button>
        <button class="pnav-btn" id="nb-3" onclick="goToQ(3)"><span>3</span><span class="pnav-sect">R</span></button>
        <button class="pnav-btn" id="nb-4" onclick="goToQ(4)"><span>4</span><span class="pnav-sect">R</span></button>
        <button class="pnav-btn" id="nb-5" onclick="goToQ(5)"><span>5</span><span class="pnav-sect">S</span></button>
        <div class="pnav-deco"><i class="fas fa-headphones"></i></div>
      </div>

      <!-- Questions -->
      <div class="qarea">

        <!-- Q1 Listening -->
        <div class="q-panel show" id="qp-1">
          <div class="listen-hint"><i class="fas fa-volume-up" style="color:var(--primary)"></i> Listen carefully before answering.</div>
          <div class="audio-player">
            <button class="ap-play" id="apBtn1" onclick="toggleAudio(1)"><i class="fas fa-play" id="apIco1"></i></button>
            <div class="waveform"><div class="wv-bars" id="wvbars1"></div></div>
            <span class="ap-time" id="apTime1">0:00 / -:--</span>
          </div>
          <div class="options" id="opts-1">
            <button class="opt-btn" onclick="selectOpt(1,'a')"><div class="opt-circle">A</div><div class="opt-text">He'll correct the exams this afternoon.</div></button>
            <button class="opt-btn" onclick="selectOpt(1,'b')"><div class="opt-circle">B</div><div class="opt-text">The exam will be at noon.</div></button>
            <button class="opt-btn" onclick="selectOpt(1,'c')"><div class="opt-circle">C</div><div class="opt-text">He will collect the exams at 12:00.</div></button>
            <button class="opt-btn" onclick="selectOpt(1,'d')"><div class="opt-circle">D</div><div class="opt-text">The tests will be graded by noon.</div></button>
          </div>
          <div id="exp-1"></div>
          <div class="q-footer">
            <button class="btn-prev" disabled><i class="fas fa-arrow-left"></i> Sebelumnya</button>
            <button class="btn-next" onclick="goToQ(2)">Selanjutnya <i class="fas fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Q2 Listening -->
        <div class="q-panel" id="qp-2">
          <div class="listen-hint"><i class="fas fa-volume-up" style="color:var(--primary)"></i> Listen carefully before answering.</div>
          <div class="audio-player">
            <button class="ap-play" id="apBtn2" onclick="toggleAudio(2)"><i class="fas fa-play" id="apIco2"></i></button>
            <div class="waveform"><div class="wv-bars" id="wvbars2"></div></div>
            <span class="ap-time" id="apTime2">0:00 / -:--</span>
          </div>
          <div class="options" id="opts-2">
            <button class="opt-btn" onclick="selectOpt(2,'a')"><div class="opt-circle">A</div><div class="opt-text">Martha applied for a visa last month.</div></button>
            <button class="opt-btn" onclick="selectOpt(2,'b')"><div class="opt-circle">B</div><div class="opt-text">Martha's visa will last for only a month.</div></button>
            <button class="opt-btn" onclick="selectOpt(2,'c')"><div class="opt-circle">C</div><div class="opt-text">Martha arrived last month without her visa.</div></button>
            <button class="opt-btn" onclick="selectOpt(2,'d')"><div class="opt-circle">D</div><div class="opt-text">One month ago Martha got her visa.</div></button>
          </div>
          <div id="exp-2"></div>
          <div class="q-footer">
            <button class="btn-prev" onclick="goToQ(1)"><i class="fas fa-arrow-left"></i> Sebelumnya</button>
            <button class="btn-next" onclick="goToQ(3)">Selanjutnya <i class="fas fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Q3 Reading -->
        <div class="q-panel" id="qp-3">
          <div class="reading-passage">
            <strong>Passage (Questions 3-4)</strong><br><br>
           The human heart is divided into four chambers, each of which serves its own function in the cycle of pumping blood. The atria are the thin-walled upper chambers that gather blood as it flows from the veins between heartbeats. The ventricles are the thick-walled lower chambers that receive 
           blood from the atria and push it into the arteries with each contraction of the heart. The left atrium and ventricle work separately from those on the right. The role of the chambers on the right side of the heart is to receive oxygen-depleted blood from the body tissues and send it on to the lungs; the
            chambers on the left side of the heart then receive the oxygen-enriched blood from the lungs and send it back out to the body tissues.
          </div>
          <div class="q-text">The passage indicates that the ventricles ? </div>
          <div class="options" id="opts-3">
            <button class="opt-btn" onclick="selectOpt(3,'a')"><div class="opt-circle">A</div><div class="opt-text">Have relatively thin walls</div></button>
            <button class="opt-btn" onclick="selectOpt(3,'b')"><div class="opt-circle">B</div><div class="opt-text">Send blood to the atria</div></button>
            <button class="opt-btn" onclick="selectOpt(3,'c')"><div class="opt-circle">C</div><div class="opt-text">Are above the atria</div></button>
            <button class="opt-btn" onclick="selectOpt(3,'d')"><div class="opt-circle">D</div><div class="opt-text">Force blood into the arteries </div></button>
          </div>
          <div id="exp-3"></div>
          <div class="q-footer">
            <button class="btn-prev" onclick="goToQ(2)"><i class="fas fa-arrow-left"></i> Sebelumnya</button>
            <button class="btn-next" onclick="goToQ(4)">Selanjutnya <i class="fas fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Q4 Reading -->
        <div class="q-panel" id="qp-4">
          <div class="reading-passage">
            <strong>Passage (Questions 3-4)</strong><br><br>
            The human heart is divided into four chambers, each of which serves its own function in the cycle of pumping blood. The atria are the thin-walled upper chambers that gather blood as it flows from the veins between heartbeats. The ventricles are the thick-walled lower chambers that receive 
           blood from the atria and push it into the arteries with each contraction of the heart. The left atrium and ventricle work separately from those on the right. The role of the chambers on the right side of the heart is to receive oxygen-depleted blood from the body tissues and send it on to the lungs; the
            chambers on the left side of the heart then receive the oxygen-enriched blood from the lungs and send it back out to the body tissues.
          </div>
          <div class="q-text">According to the passage, when is blood pushed into the arteries from the ventricles?</div>
          <div class="options" id="opts-4">
            <button class="opt-btn" onclick="selectOpt(4,'a')"><div class="opt-circle">A</div><div class="opt-text">As the heart beats </div></button>
            <button class="opt-btn" onclick="selectOpt(4,'b')"><div class="opt-circle">B</div><div class="opt-text">Between heartbeats</div></button>
            <button class="opt-btn" onclick="selectOpt(4,'c')"><div class="opt-circle">C</div><div class="opt-text">Before each contraction of the heart</div></button>
            <button class="opt-btn" onclick="selectOpt(4,'d')"><div class="opt-circle">D</div><div class="opt-text">Before it is received by the atria</div></button>
          </div>
          <div id="exp-4"></div>
          <div class="q-footer">
            <button class="btn-prev" onclick="goToQ(3)"><i class="fas fa-arrow-left"></i> Sebelumnya</button>
            <button class="btn-next" onclick="goToQ(5)">Selanjutnya <i class="fas fa-arrow-right"></i></button>
          </div>
        </div>

        <!-- Q5 Structure -->
        <div class="q-panel" id="qp-5">
          <div style="font-size:12px;color:var(--muted);font-style:italic;margin-bottom:4px;display:flex;align-items:center;gap:6px">
            <i class="fas fa-pen" style="color:#EA580C"></i> Choose the word that best completes the sentence.
          </div>
          <div class="struct-sentence">
            Andy Warhol was 
            <span class="struct-blank" id="struct-blank">____</span>
            in the Pop Art movement who was known for this multi-image silkscreen paintings.
          </div>
          <div class="options" id="opts-5">
            <button class="opt-btn" onclick="selectOpt(5,'a')"><div class="opt-circle">A</div><div class="opt-text">That one of a leading figure</div></button>
            <button class="opt-btn" onclick="selectOpt(5,'b')"><div class="opt-circle">B</div><div class="opt-text">A leading figure</div></button>
            <button class="opt-btn" onclick="selectOpt(5,'c')"><div class="opt-circle">C</div><div class="opt-text">Leading figures</div></button>
            <button class="opt-btn" onclick="selectOpt(5,'d')"><div class="opt-circle">D</div><div class="opt-text">Who leads figures</div></button>
          </div>
          <div id="exp-5"></div>
          <div class="q-footer">
            <button class="btn-prev" onclick="goToQ(4)"><i class="fas fa-arrow-left"></i> Sebelumnya</button>
            <button class="btn-next" onclick="showResult()" style="background:linear-gradient(135deg,#10B981,#059669)">
              Selesai <i class="fas fa-check"></i>
            </button>
          </div>
        </div>

      </div><!-- /qarea -->

      <!-- Right Panel -->
      <div class="rpanel">
        <div class="rcard">
          <div class="rcard-header">
            <div class="rcard-ico purple"><i class="fas fa-lightbulb"></i></div>
            <div class="rcard-title">Petunjuk</div>
          </div>
          <div class="rcard-body" id="hint-text">Dengarkan audio dengan seksama, lalu pilih jawaban yang paling tepat.</div>
        </div>
        <div class="rcard">
          <div class="rcard-header">
            <div class="rcard-ico orange" id="tips-ico"><i class="fas fa-headphones"></i></div>
            <div class="rcard-title" id="tips-title">Tips Listening</div>
          </div>
          <div id="tips-list">
            <div class="tip-item"><span>🎯</span> Fokus pada ide utama percakapan.</div>
            <div class="tip-item"><span>🎵</span> Perhatikan intonasi pembicara.</div>
            <div class="tip-item"><span>📝</span> Catat informasi penting.</div>
          </div>
        </div>
        <div class="rcard" style="background:linear-gradient(135deg,#EFF6FF,#F0F9FF);border-color:#BFDBFE">
          <div class="rcard-header">
            <div class="rcard-ico blue"><i class="fas fa-info-circle"></i></div>
            <div class="rcard-title">Info Section</div>
          </div>
          <div class="rcard-body" id="section-info">Soal 1–2 adalah Listening. Dengarkan audio sebelum menjawab.</div>
        </div>
      </div>

    </div><!-- /practice-body -->

    <div id="result-panel"></div>

   

  </div>
  <div style="height:16px"></div>
</div>

<div style="height:1px;background:var(--border);margin:0 6%"></div>

<section class="section-wrap bg-light" id="tentang">
  <div class="about-card">
    <h2 class="section-h2">Tentang <span style="color:var(--primary)">TOEFL</span></h2>
    <p class="section-sub" style="margin-bottom:0">TOEFL adalah tes kemampuan bahasa Inggris yang diakui secara internasional.</p>
    <div class="about-features">
      <div class="af-item"><div class="af-icon blue"><i class="fas fa-headphones-alt"></i></div><div><div class="af-title">Mudah Digunakan</div><div class="af-desc">Antarmuka sederhana dan ramah pengguna.</div></div></div>
      <div class="af-item"><div class="af-icon blue-2"><i class="fas fa-random"></i></div><div><div class="af-title">Soal Acak</div><div class="af-desc">Soal diacak menggunakan Fisher-Yates Shuffle.</div></div></div>
      <div class="af-item"><div class="af-icon indigo"><i class="fas fa-layer-group"></i></div><div><div class="af-title">Sistem Terstruktur</div><div class="af-desc">Materi dan tes terstruktur secara sistematis.</div></div></div>
      <div class="af-item"><div class="af-icon purple"><i class="fas fa-shield-alt"></i></div><div><div class="af-title">Anti-Kecurangan</div><div class="af-desc">Deteksi perpindahan tab & penggunaan 2 layar.</div></div></div>
      <div class="af-item"><div class="af-icon cyan"><i class="fas fa-chart-line"></i></div><div><div class="af-title">Grafik Progress</div><div class="af-desc">Pantau perkembangan skor dari waktu ke waktu.</div></div></div>
      <div class="af-item"><div class="af-icon violet"><i class="fas fa-certificate"></i></div><div><div class="af-title">Sertifikat Resmi</div><div class="af-desc">Hasil tes dapat dicetak sebagai dokumen resmi.</div></div></div>
    </div>
  </div>
</section>

<section class="section-wrap bg-white" id="materi">
  <div class="section-tag">Belajar</div>
  <h2 class="section-h2">Materi Pembelajaran</h2>
  <p class="section-sub">Pelajari tiga komponen utama TOEFL ITP dengan materi yang disusun oleh tim UPA Bahasa.</p>
  <div class="materi-grid">
    <a href="/login" class="materi-card"><div class="mc-top"><div class="mc-icon af-icon blue"><i class="fas fa-headphones-alt"></i></div><div><div class="mc-title">Listening</div><div class="mc-sub">Latihan Listening</div></div></div><div class="mc-desc">Tingkatkan kemampuan memahami percakapan dalam bahasa Inggris.</div></a>
    <a href="/login" class="materi-card"><div class="mc-top"><div class="mc-icon af-icon blue-2"><i class="fas fa-book-open"></i></div><div><div class="mc-title">Reading</div><div class="mc-sub">Latihan Reading</div></div></div><div class="mc-desc">Pahami teks akademik berbahasa Inggris dan latihan membaca cepat.</div></a>
    <a href="/login" class="materi-card"><div class="mc-top"><div class="mc-icon af-icon indigo"><i class="fas fa-pen-nib"></i></div><div><div class="mc-title">Structure</div><div class="mc-sub">Latihan Structure</div></div></div><div class="mc-desc">Kuasai tata bahasa dan ekspresi tertulis dalam bahasa Inggris.</div></a>
  </div>
  <div class="btn-center"><a href="/login" class="btn-outlined"><i class="fas fa-book-open"></i> Lihat Materi</a></div>
</section>

<div class="section-divider"></div>

<section class="section-wrap bg-light" id="tes">
  <div class="section-tag">Evaluasi</div>
  <h2 class="section-h2">Latihan &amp; Tes TOEFL</h2>
  <p class="section-sub">Uji kemampuan Anda dengan tiga pilihan tes sesuai kebutuhan.</p>
  <div class="tes-grid">
    <div class="tes-card"><div class="tc-icon af-icon blue"><i class="fas fa-bolt"></i></div><div><div class="tc-title"> Test</div><div class="tc-desc">Tes Singkat</div></div></div>
    <div class="tes-card"><div class="tc-icon af-icon indigo"><i class="fas fa-flask"></i></div><div><div class="tc-title">Simulasi</div><div class="tc-desc">Tes Simulasi</div></div></div>
    <div class="tes-card"><div class="tc-icon af-icon purple"><i class="fas fa-graduation-cap"></i></div><div><div class="tc-title">Full Test</div><div class="tc-desc">Tes Lengkap</div></div></div>
  </div>
  <div class="tes-note"><span>Tes hanya untuk pengguna terverifikasi.</span><a href="#jadwal" class="tes-note-link">Lihat Jadwal <i class="fas fa-chevron-right" style="font-size:10px"></i></a></div>
</section>

<section class="section-wrap bg-white" id="jadwal">
  <div class="section-tag">Agenda</div>
  <h2 class="section-h2">Jadwal Tes Full Mendatang</h2>
  <p class="section-sub">Daftar sesi Tes Full TOEFL ITP yang dibuka untuk warga Polman.</p>
  <div style="text-align:center;padding:44px;background:var(--bg);border-radius:14px;border:1px solid var(--border)">
    <i class="fas fa-calendar-times" style="font-size:32px;color:var(--muted-lt);margin-bottom:10px;display:block"></i>
    <p style="color:var(--muted);font-size:13.5px">Belum ada jadwal tes yang dibuka.</p>
  </div>
</section>

<section class="cek-skor">
  <div class="section-tag" style="display:block;text-align:center">Verifikasi</div>
  <h2 class="section-h2" style="margin-bottom:6px">Cek Skor TOEFL Anda</h2>
  <p style="font-size:13.5px;color:var(--muted);margin-bottom:24px">Masukkan nomor pendaftaran untuk melihat hasil tes TOEFL ITP Anda.</p>
  <form class="cek-form" action="/cari-skor" method="POST">
    <input type="text" name="nomor_pendaftaran" class="cek-input" placeholder="Masukkan Email / ID Anda" required>
    <button type="submit" class="btn-cek"><i class="fas fa-search"></i> Cari Skor</button>
  </form>
</section>

<footer>
  <div class="footer-top">
    <div class="footer-brand">
      <div class="f-logo"><div class="f-logo-icon"><i class="fas fa-graduation-cap" style="font-size:13px"></i></div><span class="f-logo-text">TOEFL Prep</span></div>
      <p class="f-desc">Platform resmi UPA Bahasa Politeknik Manufaktur Negeri Bangka Belitung.</p>
    </div>
    <div class="footer-col"><h4>Platform</h4><ul><li><a href="#">Materi</a></li><li><a href="#">Latihan</a></li><li><a href="#">Jadwal Tes</a></li></ul></div>
    <div class="footer-col"><h4>Bantuan</h4><ul><li><a href="#">FAQ</a></li><li><a href="#">Kontak</a></li></ul></div>
  </div>
  <div class="footer-bottom"><span>© 2026 TOEFL Prep — Polman Babel</span><span>UPA Bahasa</span></div>
</footer>

<script>
// ══ DATA ═══════════════════════════════════════════════════
const CORRECT = {1:'d', 2:'d', 3:'d', 4:'a', 5:'b'};
const SECTION = {1:'listening', 2:'listening', 3:'reading', 4:'reading', 5:'structure'};
const EXPLANATIONS = {
  1:{
    kalimat:'"They\'ll be corrected by noon."',
    arti:'Ujian akan selesai diperiksa sebelum / pada jam 12 siang.',
    penjelasan:'Frasa "be corrected by noon" berarti ujian akan selesai diperiksa sebelum tengah hari. Jadi jawaban yang benar adalah D (The tests will be graded by noon).'
  },

  2:{
    kalimat:'"It arrived last month."',
    arti:'Visa tersebut datang bulan lalu.',
    penjelasan:'Kalimat ini menunjukkan visa Martha sudah diterima sejak bulan lalu. Jadi jawaban yang benar adalah D (One month ago Martha got her visa).'
  },

  3:{
    kalimat:'"The ventricles ... push it into the arteries."',
    arti:'Ventricles (bilik jantung) mendorong darah ke arteri.',
    penjelasan:'Pada teks disebutkan bahwa ventricles menerima darah dari atria lalu mendorongnya ke arteri. Jadi jawaban yang benar adalah D (force blood into the arteries).'
  },

  4:{
    kalimat:'"with each contraction of the heart."',
    arti:'Saat jantung berkontraksi / berdetak.',
    penjelasan:'Frasa tersebut menunjukkan darah didorong ke arteri saat jantung berdetak. Jadi jawaban yang benar adalah A (As the heart beats).'
  },

  5:{
    kalimat:'"Andy Warhol was ... in the Pop Art movement."',
    arti:'Kalimat membutuhkan noun phrase setelah kata "was".',
    penjelasan:'Karena kalimat sudah memiliki predikat "was", maka dibutuhkan pelengkap berupa noun phrase. Pilihan yang paling tepat adalah B (A leading figure).'
  }
};

const TIPS = {
  listening:{
    ico:'fa-headphones',
    title:'Tips Listening',
    color:'orange',
    items:[
      '🎯 Fokus pada ide utama percakapan.',
      '🎵 Perhatikan intonasi pembicara.',
      '📝 Dengarkan keyword penting.'
    ],
    hint:'Dengarkan audio dengan seksama sebelum memilih jawaban.',
    info:'Soal 1–2 adalah Listening. Dengarkan audio sebelum menjawab.'
  },

  reading:{
    ico:'fa-book-open',
    title:'Tips Reading',
    color:'blue',
    items:[
      '🔍 Cari kalimat kunci dalam passage.',
      '⚡ Fokus pada informasi spesifik.',
      '💡 Perhatikan hubungan antar kalimat.'
    ],
    hint:'Baca passage dengan teliti lalu cari informasi yang sesuai dengan pertanyaan.',
    info:'Soal 3–4 adalah Reading. Bacalah passage sebelum menjawab.'
  },

  structure:{
    ico:'fa-pen',
    title:'Tips Structure',
    color:'orange',
    items:[
      '📐 Perhatikan subject dan verb.',
      '✏️ Cari bentuk grammar yang lengkap.',
      '🔗 Fokus pada noun phrase dan clause.'
    ],
    hint:'Pilih jawaban yang membuat struktur kalimat menjadi benar secara grammar.',
    info:'Soal 5 adalah Structure. Perhatikan tata bahasa dan pola kalimat.'
  }
};

// ══ STATE ══════════════════════════════════════════════════
let currentQ = 1;
let answers  = {};

// ══ PRACTICE OPEN/CLOSE ════════════════════════════════════
function startPractice() {
  document.getElementById('practice-wrap').classList.add('open');
  document.getElementById('letsGoWrap').style.display = 'none';
  setTimeout(() => {
    document.getElementById('practice-wrap').scrollIntoView({behavior:'smooth',block:'start'});
  }, 100);
  generateWaveforms();
}

function closePractice() {
  // Stop all audio
  [1,2].forEach(n => {
    const a = document.getElementById('audio'+n);
    if (a) { a.pause(); a.currentTime = 0; }
    const btn = document.getElementById('apBtn'+n);
    const ico = document.getElementById('apIco'+n);
    if (btn) btn.classList.remove('playing');
    if (ico) ico.className = 'fas fa-play';
  });
  document.getElementById('practice-wrap').classList.remove('open');
  document.getElementById('letsGoWrap').style.display = 'flex';
  // Reset state
  currentQ = 1; answers = {};
  document.querySelectorAll('.q-panel').forEach(p => p.classList.remove('show'));
  document.getElementById('qp-1').classList.add('show');
  document.querySelectorAll('.pnav-btn').forEach(b => b.className = 'pnav-btn');
  document.getElementById('nb-1').classList.add('active');
  document.querySelectorAll('.opt-btn').forEach(b => b.className = 'opt-btn');
  for(let i=1;i<=5;i++){const e=document.getElementById('exp-'+i);if(e)e.innerHTML='';}
  // Reset struct blank
  const sb = document.getElementById('struct-blank');
  if (sb) { sb.textContent='____'; sb.style.color=''; sb.style.borderBottomColor=''; }
  // Reset practice body + result
  document.getElementById('practice-body').style.display = '';
  document.getElementById('result-panel').style.display = 'none';
  document.getElementById('result-panel').innerHTML = '';
  // Reset waveforms
  [1,2].forEach(n => {
    const c = document.getElementById('wvbars'+n);
    if(c) { c.innerHTML=''; }
    const t = document.getElementById('apTime'+n);
    if(t) t.textContent = '0:00 / -:--';
  });
  updateProgress(); updateTips(); updateTabs();
  window.scrollTo({top:0,behavior:'smooth'});
}

// ══ NAVIGATION ═════════════════════════════════════════════
function goToQ(n) {
  document.getElementById('qp-'+currentQ)?.classList.remove('show');
  document.getElementById('nb-'+currentQ)?.classList.remove('active');
  currentQ = n;
  document.getElementById('qp-'+n)?.classList.add('show');
  document.getElementById('nb-'+n)?.classList.add('active');
  updateProgress(); updateTips(); updateTabs();
}

function updateProgress() {
  document.getElementById('progressFill').style.width = (currentQ/5*100)+'%';
  document.getElementById('progressNum').textContent = currentQ+' / 5';
}

function updateTabs() {
  const s = SECTION[currentQ];
  document.getElementById('stab-1').className = 'stab'+(s==='listening'?' active-listen':'');
  document.getElementById('stab-3').className = 'stab'+(s==='reading' ?' active-read':'');
  document.getElementById('stab-5').className = 'stab'+(s==='structure'?' active-struct':'');
}

function updateTips() {
  const t = TIPS[SECTION[currentQ]];
  document.getElementById('hint-text').textContent    = t.hint;
  document.getElementById('tips-ico').innerHTML       = `<i class="fas ${t.ico}"></i>`;
  document.getElementById('tips-ico').className       = `rcard-ico ${t.color}`;
  document.getElementById('tips-title').textContent   = t.title;
  document.getElementById('section-info').textContent = t.info;
  document.getElementById('tips-list').innerHTML =
    t.items.map(i=>`<div class="tip-item"><span>${i.slice(0,2)}</span> ${i.slice(2)}</div>`).join('');
}

// ══ AUDIO ══════════════════════════════════════════════════
function generateWaveforms() {
  [1,2].forEach(n => {
    const c = document.getElementById('wvbars'+n);
    if (!c || c.children.length > 0) return;
    for (let i = 0; i < 60; i++) {
      const h = 15 + Math.sin(i*0.35+n)*10 + Math.random()*30;
      const bar = document.createElement('div');
      bar.className = 'wv-bar';
      bar.style.height = Math.max(8,h)+'%';
      c.appendChild(bar);
    }
  });
}

function toggleAudio(n) {
  const audio = document.getElementById('audio'+n);
  const btn   = document.getElementById('apBtn'+n);
  const ico   = document.getElementById('apIco'+n);

  if (!audio) return;

  if (!audio.paused) {
    audio.pause();
    btn.classList.remove('playing');
    ico.className = 'fas fa-play';
    return;
  }

  // Pause semua audio lain
  [1,2].forEach(x => {
    if (x !== n) {
      const a = document.getElementById('audio'+x);
      if (a && !a.paused) {
        a.pause();
        document.getElementById('apBtn'+x)?.classList.remove('playing');
        const i = document.getElementById('apIco'+x);
        if (i) i.className = 'fas fa-play';
      }
    }
  });

  audio.play().then(() => {
    btn.classList.add('playing');
    ico.className = 'fas fa-pause';
  }).catch(() => {
    // File tidak ada — tampilkan pesan
    alert('File audio belum tersedia. Taruh file di: public/audio/practice/soal'+n+'_praktik.mp3');
  });

  audio.ontimeupdate = () => {
    const cur = audio.currentTime;
    const dur = audio.duration || 0;
    const fmt = s => Math.floor(s/60)+':'+String(Math.floor(s%60)).padStart(2,'0');
    document.getElementById('apTime'+n).textContent = fmt(cur)+' / '+fmt(dur);
    const bars = document.querySelectorAll('#wvbars'+n+' .wv-bar');
    const played = Math.floor((cur/dur)*bars.length);
    bars.forEach((b,i) => b.classList.toggle('played', i <= played));
  };

  audio.onended = () => {
    btn.classList.remove('playing');
    ico.className = 'fas fa-play';
  };
}

// ══ JAWABAN ════════════════════════════════════════════════
function selectOpt(qNum, opt) {
  if (answers[qNum]) return;
  answers[qNum] = opt;

  const correct = CORRECT[qNum];
  const opts    = document.querySelectorAll('#opts-'+qNum+' .opt-btn');
  const letters = ['a','b','c','d'];
  opts.forEach((btn,i) => {
    btn.classList.add('disabled');
    if (letters[i] === correct) btn.classList.add('correct');
    if (letters[i] === opt && opt !== correct) btn.classList.add('wrong');
  });

  // Update nav button
  const nb = document.getElementById('nb-'+qNum);
  nb.classList.remove('active');
  nb.classList.add(opt === correct ? 'answered' : 'wrong-ans');
  nb.classList.add('active');

  // Fill struct blank
  if (qNum === 5) {
    const sb = document.getElementById('struct-blank');
    const words = {a:'were',b:'was',c:'are',d:'is'};
    sb.textContent = words[opt] || opt;
    sb.style.color = opt===correct ? 'var(--green)' : 'var(--red)';
    sb.style.borderBottomColor = opt===correct ? 'var(--green)' : 'var(--red)';
  }

  showExplanation(qNum, opt === correct);
}

function showExplanation(qNum, isCorrect) {
  const exp = EXPLANATIONS[qNum];
  const el  = document.getElementById('exp-'+qNum);
  const icon = isCorrect
    ? '<i class="fas fa-check-circle" style="color:var(--green)"></i>'
    : '<i class="fas fa-times-circle" style="color:var(--red)"></i>';
  el.innerHTML = `
    <div class="explanation ${isCorrect?'correct-exp':'wrong-exp'}">
      <div class="exp-header ${isCorrect?'exp-correct':'exp-wrong'}">${icon} ${isCorrect?'Benar!':'Salah — Lihat Pembahasan'}</div>
      <div class="exp-body">
        <strong>Kalimat:</strong> ${exp.kalimat}<br>
        <strong>Artinya:</strong> ${exp.arti}<br>
        <strong>Penjelasan:</strong> ${exp.penjelasan}
      </div>
    </div>`;
}

// ══ RESULT ═════════════════════════════════════════════════
function showResult() {
  if (!answers[5]) { alert('Pilih jawaban soal 5 terlebih dahulu!'); return; }

  const correct = Object.entries(answers).filter(([q,a]) => CORRECT[q]===a).length;
  const score   = Math.round((correct/5)*100);
  const by = {listening:{c:0,t:0},reading:{c:0,t:0},structure:{c:0,t:0}};
  Object.entries(answers).forEach(([q,a]) => {
    const s = SECTION[q]; by[s].t++;
    if (CORRECT[q]===a) by[s].c++;
  });
  const motiv = score>=80?'🎉 Great job! Your TOEFL skill is improving fast!'
               :score>=60?'👍 Good effort! Keep practicing to improve your score.'
               :'💪 Keep trying! Every practice makes you better.';

  document.getElementById('practice-body').style.display = 'none';
  const rp = document.getElementById('result-panel');
  rp.style.display = 'block';
  rp.innerHTML = `
    <div style="max-width:480px;margin:0 auto;text-align:center">
      <div style="font-size:56px;margin-bottom:8px">${score>=80?'🏆':score>=60?'🎯':'💪'}</div>
      <div style="font-size:22px;font-weight:800;color:var(--navy);margin-bottom:6px">You answered ${correct} out of 5 correctly!</div>
      <div style="font-size:14px;color:var(--muted);margin-bottom:20px">Score  Practice Anda</div>
      <div class="result-score">${score}<span style="font-size:14px">%</span></div>
      <div class="result-breakdown">
        <div class="rb-item"><div class="rb-num" style="color:#1D4ED8">${by.listening.c}/${by.listening.t}</div><div class="rb-lbl">🎧 Listening</div></div>
        <div class="rb-item"><div class="rb-num" style="color:#16A34A">${by.reading.c}/${by.reading.t}</div><div class="rb-lbl">📖 Reading</div></div>
        <div class="rb-item"><div class="rb-num" style="color:#EA580C">${by.structure.c}/${by.structure.t}</div><div class="rb-lbl">✏️ Structure</div></div>
      </div>
      <div style="font-size:15px;font-weight:700;color:var(--green);margin-bottom:20px">${motiv}</div>
      <div class="result-ctas">
        <a href="/login" class="cta-btn cta-primary"><i class="fas fa-play"></i> Continue Learning — Login Sekarang</a>
        <button class="cta-btn cta-outline" onclick="retryPractice()"><i class="fas fa-redo"></i> Coba Lagi</button>
        <a href="#materi" class="cta-btn" style="background:var(--bg);color:var(--text)" onclick="closePractice()"><i class="fas fa-book"></i> Pelajari Materi</a>
      </div>
    </div>`;
}

function retryPractice() { closePractice(); setTimeout(startPractice, 300); }

// Init
updateProgress(); updateTips(); updateTabs();
</script>
</body>
</html>