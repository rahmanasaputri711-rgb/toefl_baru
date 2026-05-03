<!DOCTYPE html>
<html>
<head>
    <title>Halaman Tes</title>
</head>
<body>

<h2>Halaman Tes TOEFL</h2>
<h3>Waktu tersisa: <span id="timer">--:--</span></h3>

<div id="soal"></div>

<br>
<button onclick="submitJawaban()">Submit</button>

<script>

let sudahSubmit = false;
let waktu = 120;
let jawaban = {};

// 🔥 AUDIO CONTROL
let sudahPlay = {};
let audioSelesai = {};

// TIMER
let interval = setInterval(updateTimer, 1000);

function updateTimer(){
    let menit = Math.floor(waktu / 60);
    let detik = waktu % 60;

    document.getElementById('timer').innerText =
        `${menit}:${detik < 10 ? '0'+detik : detik}`;

    if (waktu <= 10) {
        document.getElementById('timer').style.color = 'red';
    }

    if (waktu <= 0) {
        submitJawaban();
        return;
    }

    waktu--;
}

// 🔥 PLAY AUDIO (TOEFL MODE)
function playAudio(id){

    if(sudahPlay[id]) return;

    let audio = document.getElementById('audio_' + id);
    let btn = document.getElementById('btn_' + id);

    audio.play();
    sudahPlay[id] = true;

    if(btn) btn.disabled = true;

    // ❌ tidak bisa pause
    audio.addEventListener('pause', function(){
        if(!audio.ended){
            audio.play();
        }
    });

    // ❌ tidak bisa skip
    audio.addEventListener('seeking', function(){
        audio.currentTime = 0;
    });

    // ✔ selesai
    audio.onended = function(){
        audioSelesai[id] = true;
    };
}

// 🔥 LOAD SOAL
fetch('/tes/soal')
.then(res => res.json())
.then(data => {

console.log(data);

    let html = '';
    let lastGroup = null;

    data.forEach((item, index) => {

        html += `<div style="margin-bottom:20px;">`;

        // 🎧 LISTENING
        if(item.audio_url && item.audio_url !== ''){
            html += `
                <audio controls id="audio_${item.id}">
                    <source src="/${item.audio_url}" type="audio/mpeg">
                </audio>

                <button id="btn_${item.id}" onclick="playAudio(${item.id})">
                    ▶ Play Audio
                </button>
                <br><br>
            `;
        }

        // 📖 READING (tampil sekali per group)
        if(item.group_id && item.group_id !== lastGroup){

            if(item.passage_teks){
                html += `
                <div style="background:#f1f1f1; padding:15px; margin-bottom:15px;">
                    <b>Reading Passage:</b><br><br>
                    ${item.passage_teks}
                </div>
                `;
            }

            lastGroup = item.group_id;
        }

        // 📝 SOAL
        html += `
            <p>${index+1}. ${item.pertanyaan}</p>

            <label><input type="radio" name="soal_${item.id}" value="a" onchange="pilih(${item.id}, 'a')"> ${item.pilihan_a}</label><br>
            <label><input type="radio" name="soal_${item.id}" value="b" onchange="pilih(${item.id}, 'b')"> ${item.pilihan_b}</label><br>
            <label><input type="radio" name="soal_${item.id}" value="c" onchange="pilih(${item.id}, 'c')"> ${item.pilihan_c}</label><br>
            <label><input type="radio" name="soal_${item.id}" value="d" onchange="pilih(${item.id}, 'd')"> ${item.pilihan_d}</label><br>

            <hr>
        `;

        html += `</div>`;
    });

    document.getElementById('soal').innerHTML = html;
});

// 🔥 PILIH JAWABAN
function pilih(id, value){

    let audio = document.getElementById('audio_' + id);

    // 🎧 kalau listening → harus selesai dulu
    if(audio){
        if(!audioSelesai[id]){
            alert('Dengarkan audio sampai selesai!');
            return;
        }
    }

    jawaban[id] = value;
}

// 🔥 SUBMIT
function submitJawaban(){

    if (sudahSubmit) return;
    sudahSubmit = true;

    clearInterval(interval);

    fetch('/tes/submit', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            jawaban: jawaban
        })
    })
    .then(res => res.json())
    .then(data => {

        if(data.message){
            alert(data.message);
            return;
        }

        alert(
`Skor TOEFL: ${data.toefl}
Listening: ${data.listening}
Structure: ${data.structure}
Reading: ${data.reading}`
        );

        window.location.href = '/dashboard';
    });
}

</script>

</body>
</html>