<script>
// ════════════════════════════════════════════════════════
// DATA SOAL — 70 soal (berdiri sendiri, tidak dari bank soal)
// ════════════════════════════════════════════════════════
const SOAL = {
listening:[
  {id:1,q:'What is the main purpose of the conversation?',opts:['To book a hotel room','To change a reservation','To cancel a flight','To confirm a payment'],ans:2,exp:'Percakapan membahas pembatalan penerbangan. "Cancel" = membatalkan, jawaban C.'},
  {id:2,q:'What will the man do next?',opts:['Check the schedule','Buy a ticket','Call his friend','Change the plan'],ans:0,exp:'Pria menyebutkan akan memeriksa jadwal terlebih dahulu. Jawaban A.'},
  {id:3,q:'What are the speakers mainly discussing?',opts:['A new restaurant in town','A change in work schedule','A weekend travel plan','A meeting next Monday'],ans:2,exp:'Percakapan berpusat pada rencana perjalanan akhir pekan. Jawaban C.'},
  {id:4,q:'What problem does the woman have?',opts:['She lost her keys','She missed the bus','She forgot her homework','She cannot find her wallet'],ans:1,exp:'Wanita menjelaskan ia terlambat karena melewatkan bus pagi. Jawaban B.'},
  {id:5,q:'What does the professor suggest students do?',opts:['Read more textbooks','Practice speaking daily','Join a study group','Submit assignments early'],ans:1,exp:'Profesor menekankan latihan berbicara setiap hari. Jawaban B.'},
  {id:6,q:'What is the lecture mainly about?',opts:['Effects of climate change','Methods of ocean exploration','History of marine biology','Coral reef ecosystems'],ans:3,exp:'Kuliah berfokus pada ekosistem terumbu karang. Jawaban D.'},
  {id:7,q:'According to the woman, what is the most important thing?',opts:['Saving money','Managing time','Building connections','Staying healthy'],ans:1,exp:'Wanita menekankan manajemen waktu sebagai kunci keberhasilan. Jawaban B.'},
  {id:8,q:'What does the man imply about the exam?',opts:['It will be easy','It was canceled','It covers new material','It is postponed'],ans:2,exp:'Pria menyebutkan ujian mencakup materi baru. Jawaban C.'},
  {id:9,q:'Why is the student visiting the professor?',opts:['To get extra credit','To discuss a grade','To ask about a topic','To submit late work'],ans:1,exp:'Mahasiswa datang untuk mendiskusikan nilai yang menurutnya tidak adil. Jawaban B.'},
  {id:10,q:'What can be inferred about the new policy?',opts:['It benefits students','It is temporary','It has been rejected','It will be announced soon'],ans:3,exp:'Kebijakan baru belum diumumkan dan akan segera dipublikasikan. Jawaban D.'},
  {id:11,q:'What does the woman ask the man to do?',opts:['Fix the computer','Send an email','Call the office','Print the document'],ans:1,exp:'Wanita meminta pria mengirimkan email konfirmasi kepada klien. Jawaban B.'},
  {id:12,q:'What is the main topic of the announcement?',opts:['Library hours change','New cafeteria menu','Campus event schedule','Parking regulations'],ans:0,exp:'Pengumuman membahas perubahan jam operasional perpustakaan. Jawaban A.'},
  {id:13,q:'What does the speaker say about the research?',opts:['It is incomplete','It was published','It needs more funding','It proved the hypothesis'],ans:3,exp:'Pembicara menyebutkan penelitian berhasil membuktikan hipotesis. Jawaban D.'},
  {id:14,q:'What does the man decide to do?',opts:['Take a break','Study all night','Ask for help','Submit the work'],ans:2,exp:'Pria memutuskan untuk meminta bantuan teman yang lebih ahli. Jawaban C.'},
  {id:15,q:'Why does the woman seem surprised?',opts:['The price changed','The store was closed','The order was wrong','The item was sold out'],ans:3,exp:'Wanita terkejut mengetahui barang yang ingin dibelinya sudah habis. Jawaban D.'},
  {id:16,q:'What is the purpose of the meeting?',opts:['To review a project','To hire new staff','To discuss budget','To plan an event'],ans:0,exp:'Rapat diadakan untuk meninjau kemajuan proyek yang sedang berjalan. Jawaban A.'},
  {id:17,q:'What does the professor emphasize?',opts:['Attendance','Reading speed','Critical thinking','Memorization'],ans:2,exp:'Profesor menekankan pentingnya berpikir kritis daripada menghafal. Jawaban C.'},
  {id:18,q:'What will happen to the old building?',opts:['It will be sold','It will be renovated','It will be demolished','It will be preserved'],ans:1,exp:'Gedung lama akan direnovasi menjadi pusat kegiatan mahasiswa. Jawaban B.'},
  {id:19,q:'What does the student want to change?',opts:['Her major','Her advisor','Her schedule','Her dormitory'],ans:2,exp:'Mahasiswa ingin mengubah jadwal kuliah yang bertabrakan. Jawaban C.'},
  {id:20,q:'What is mentioned about the new transportation system?',opts:['It is more expensive','It reduces travel time','It is not yet available','It needs improvement'],ans:1,exp:'Sistem transportasi baru diklaim mengurangi waktu perjalanan. Jawaban B.'},
  {id:21,q:'What does the man think about the proposal?',opts:['It is too risky','It is well-planned','It needs revision','It will fail'],ans:1,exp:'Pria menyatakan proposal sudah direncanakan dengan sangat baik. Jawaban B.'},
  {id:22,q:'What is the woman\'s problem with the assignment?',opts:['She doesn\'t understand it','She lost her notes','She ran out of time','She forgot the topic'],ans:2,exp:'Wanita menjelaskan ia kehabisan waktu untuk menyelesaikan tugas. Jawaban C.'},
  {id:23,q:'What will the university do about the parking issue?',opts:['Build more spaces','Raise parking fees','Close some lots','Start a shuttle service'],ans:3,exp:'Universitas berencana menyediakan layanan shuttle. Jawaban D.'},
  {id:24,q:'What does the speaker mean by "cutting corners"?',opts:['Taking shortcuts','Being careful','Saving money','Working overtime'],ans:0,exp:'"Cutting corners" = mengambil jalan pintas, melakukan sesuatu tidak benar demi efisiensi. Jawaban A.'},
  {id:25,q:'What is the likely outcome of the experiment?',opts:['It will be repeated','It confirms the theory','It disproves the hypothesis','It needs more data'],ans:1,exp:'Hasil eksperimen berhasil mengkonfirmasi teori yang ada. Jawaban B.'},
],
structure:[
  {id:26,q:'If I ___ enough money, I will buy a new laptop.',opts:['have','has','had','having'],ans:0,exp:'"If + present simple → will" = conditional type 1. Subjek "I" → "have."'},
  {id:27,q:'Neither the students nor the teacher ___ prepared.',opts:['were','was','are','is'],ans:1,exp:'"Neither...nor" — kata kerja ikuti subjek terdekat "teacher" (singular) → "was."'},
  {id:28,q:'The report ___ by the committee last week.',opts:['reviewed','was reviewed','has reviewed','is reviewing'],ans:1,exp:'Passive voice past tense: "was reviewed."'},
  {id:29,q:'She is one of the best students who ___ ever studied here.',opts:['has','have','had','having'],ans:1,exp:'"Who" mengacu "students" (jamak) → "have."'},
  {id:30,q:'By the time we arrived, they ___ already left.',opts:['have','has','had','were'],ans:2,exp:'"By the time + past simple" → past perfect: "had."'},
  {id:31,q:'The policy requires that every employee ___ the training.',opts:['completes','complete','completed','completing'],ans:1,exp:'Subjunctive setelah "requires that": base form "complete."'},
  {id:32,q:'Not only ___ the exam, but she also got a scholarship.',opts:['she passed','did she pass','she did pass','passed she'],ans:1,exp:'Inverted subject-verb setelah "not only": "did she pass."'},
  {id:33,q:'The children ___ were playing outside came home at sunset.',opts:['which','who','whose','whom'],ans:1,exp:'"Who" untuk orang (the children).'},
  {id:34,q:'He suggested that she ___ the doctor immediately.',opts:['sees','see','saw','to see'],ans:1,exp:'Subjunctive setelah "suggested that": base form "see."'},
  {id:35,q:'The book ___ on the table belongs to my sister.',opts:['lay','lies','laying','lain'],ans:1,exp:'"Lies" (present simple of "lie" = berada/terletak) untuk benda tidak bergerak.'},
  {id:36,q:'Hardly ___ entered the room when the phone rang.',opts:['he had','had he','he has','has he'],ans:1,exp:'"Hardly" di awal → inversi: "had he" (past perfect inverted).'},
  {id:37,q:'The students handed in their work ___ the deadline.',opts:['in','on','at','by'],ans:3,exp:'"By the deadline" = paling lambat pada deadline.'},
  {id:38,q:'___ the bad weather, we decided to cancel the trip.',opts:['Because','Although','Despite','Since'],ans:2,exp:'"Despite" + noun phrase (tidak butuh subject + verb).'},
  {id:39,q:'The more you practice, ___ you will become.',opts:['better','the better','more better','most better'],ans:1,exp:'"The more...the more/better..." = struktur perbandingan progresif.'},
  {id:40,q:'This is the most interesting book ___ I have ever read.',opts:['which','that','what','whom'],ans:1,exp:'Setelah superlative → gunakan "that" sebagai relative pronoun.'},
  {id:41,q:'She ___ been working here for five years when she got promoted.',opts:['has','had','have','was'],ans:1,exp:'Past perfect continuous: "had been working."'},
  {id:42,q:'The new regulation ___ all employees wear ID badges.',opts:['requires','require','requiring','required'],ans:0,exp:'"The new regulation" (singular) → "requires."'},
  {id:43,q:'It was not until midnight ___ they finished the project.',opts:['when','that','which','then'],ans:1,exp:'Struktur emphatik "It was not until...that..."'},
  {id:44,q:'___ he had studied harder, he would have passed the exam.',opts:['If','Unless','Although','When'],ans:0,exp:'"If + past perfect, would have + V3" = conditional type 3.'},
  {id:45,q:'The committee decided to ___ the meeting until next week.',opts:['post','postpone','post-date','postulate'],ans:1,exp:'"Postpone" = menunda/mengundurkan jadwal.'},
],
reading:[
  {id:46,type:'fill',group:'fill',
   paragraph:'We know from drawings that have been preserved in caves for over 10,000 years that early humans performed dances as a group activity. We [mi___] think [th___] prehistoric [peo____] concentrated [on___] on [ba____] survival. [How____], it [i__] clear [fr___] the [rec____] that [dan_____] was important to them.',
   blanks:[
     {key:'mi___',ans:'might'},
     {key:'th___',ans:'those'},
     {key:'peo____',ans:'people'},
     {key:'on___',ans:'only'},
     {key:'ba____',ans:'basic'},
     {key:'How____',ans:'however'},
     {key:'i__',ans:'is'},
     {key:'fr___',ans:'from'},
     {key:'rec____',ans:'records'},
     {key:'dan_____',ans:'dancing'},
   ],
   questions:[
     {q:'Complete: "We [mi___] think"',blankKey:'mi___',ans:'might',exp:'"might" = modal verb expressing possibility. "We might think."'},
     {q:'Complete: "think [th___] prehistoric"',blankKey:'th___',ans:'those',exp:'"those" = demonstrative pronoun referring to early humans.'},
     {q:'Complete: "[peo____] concentrated"',blankKey:'peo____',ans:'people',exp:'"people" = the subject of the clause.'},
     {q:'Complete: "concentrated [on___] on basic"',blankKey:'on___',ans:'only',exp:'"only" = adverb limiting the focus to basic survival.'},
     {q:'Complete: "[ba____] survival"',blankKey:'ba____',ans:'basic',exp:'"basic" = adjective meaning fundamental or essential.'},
     {q:'Complete: "[How____], it is clear"',blankKey:'How____',ans:'however',exp:'"However" = contrast connector introducing an opposing idea.'},
     {q:'Complete: "it [i__] clear"',blankKey:'i__',ans:'is',exp:'"is" = present simple main verb of the clause.'},
     {q:'Complete: "clear [fr___] the records"',blankKey:'fr___',ans:'from',exp:'"from" = preposition showing source of evidence.'},
     {q:'Complete: "the [rec____]"',blankKey:'rec____',ans:'records',exp:'"records" = archaeological or historical evidence.'},
     {q:'Complete: "that [dan_____] was important"',blankKey:'dan_____',ans:'dancing',exp:'"dancing" = gerund as the subject of the clause.'},
   ]
  },
  {id:56,type:'notice',group:'notice',
   notice_title:'Municipal Charter',
   notice_subtitle:'OFFICIAL BANKING NOTICE',
   notice_icon:'🏦',
   notice_color:'#1E3A8A',
   notice_body:'Sign up for paperless billing statements today.\n\nSafe, convenient, easy. Enroll in paperless billing to receive monthly savings account statements in an electronic PDF document.\n\nAccess your Municipal Charter account through the mobile app and select account preferences in the upper right-hand corner to enroll.',
   questions:[
     {q:'What type of business issued the notice?',opts:['An Internet provider','A computer company','A paper company','A bank'],ans:3,exp:'The notice mentions savings account statements and billing — these are banking services. Answer: D.'},
     {q:'How can customers enroll in paperless billing?',opts:['By visiting a Municipal Charter office','By accessing the Municipal Charter website','By using the Municipal Charter app','By calling customer service'],ans:2,exp:'The notice says "Access your Municipal Charter account through the mobile app." Answer: C.'},
   ]
  },
  {id:58,type:'social',group:'social',
   post_author:'Sofia Baker',
   post_handle:'@sofiabaker',
   post_avatar:'SB',
   post_time:'Saturday 8:30 AM',
   post_body:"Every Saturday, our local farmer's market is the place to be! Fresh fruits, veggies, homemade goodies, and unique crafts await you.\n\nThe Thompson family's organic produce is a must-try, known for its quality and cordial service. Their stall is always bustling with customers eager to buy fresh, pesticide-free vegetables.\n\nDon't miss the bakery stall—get there early for the best bread and pastries, including gluten-free and vegan options. These treats sell out fast!\n\nPlus, enjoy live music while you shop. See you there! 🌿🍞🎵",
   questions:[
     {q:"What reason is given for the popularity of the Thompson family's stall?",opts:['They offer cooking tips and recipes.','They offer the lowest prices.','They provide friendly service and excellent products.','They have a beautifully decorated stall.'],ans:2,exp:'The post mentions "quality and cordial service" — cordial = friendly. Answer: C.'},
     {q:'What is the main purpose of the post?',opts:['To explain the benefits of organic farming','To describe the variety of products at the market','To compare different markets','To offer advice on starting a stall'],ans:1,exp:'The post describes foods, crafts, and music — its purpose is to showcase variety. Answer: B.'},
     {q:'Why do customers go to the bakery stall early?',opts:['To get free samples','To get bread and pastries before they run out','To meet the famous baker','To get early morning discounts'],ans:1,exp:'The post says "these treats sell out fast!" Answer: B.'},
   ]
  },
  {id:61,type:'passage',group:'mirror',
   passage:'Very young children cannot recognize themselves in a mirror; they usually achieve this milestone around 18 months of age. The ability to recognize oneself in the mirror is considered to be a key component of self-awareness and consciousness for humans. But what about animals?\n\nFor many years, scientists have known that members of the great ape family could recognize themselves in mirrors. They measured this by the "mirror test," which involved putting a colored mark on an ape\'s body, and then showing the ape its reflection in a mirror. If the ape tried to remove the mark on its own body, the scientists knew that the ape was recognizing its reflection.\n\nApes are close relatives of humans, but in recent years, scientists have discovered that other animals also pass the "mirror test." Elephants and dolphins have shown signs of self-recognition. These, like apes, are highly intelligent animals. But in a more recent experiment, a type of fish called the cleaner fish tried to scrape a mark off its body when it saw itself in the mirror. This suggests that even less intelligent animals may possess more self-awareness than previously suspected.',
   questions:[
     {q:'What is the passage mainly about?',opts:['Stages of early childhood development','Research on animal cognition','Differences between apes and dolphins','Recent experiments on fish'],ans:1,exp:'The passage discusses the mirror test applied to various animals — research on animal cognition. Answer: B.'},
     {q:'The word "milestone" is closest in meaning to:',opts:['accomplishment','distance','weight','discovery'],ans:0,exp:'"Milestone" = a significant achievement or stage of development. Answer: A.'},
     {q:"Why did scientists put colored marks on animals' bodies?",opts:["To track animals' movements","To determine whether animals recognized themselves","To tell the animals apart","To test color detection"],ans:1,exp:'The mark tested self-recognition — if the animal touched where the mark was on its own body. Answer: B.'},
     {q:'According to the passage, all of the following are true about elephants EXCEPT:',opts:['They can recognize themselves in mirrors.','They are highly intelligent.','They share qualities with apes.','They understand signs from other animals.'],ans:3,exp:'The passage does NOT mention elephants understanding signs from other animals. Answer: D.'},
     {q:'Why does the author mention cleaner fish?',opts:['To suggest a wide range of animals may have self-awareness','To imply ocean animals are highly intelligent','To demonstrate a flaw in an experiment','To give an example of an animal that does not recognize itself'],ans:0,exp:'The cleaner fish result suggests self-awareness may be more widespread than thought. Answer: A.'},
   ]
  },
  {id:66,type:'passage',group:'wright',
   passage:'A distinctively American architecture began with Frank Lloyd Wright, who had taken to heart the admonition that form should follow function and who thought of buildings not as separate architectural entities but as parts of an organic whole that included the land, the community, and the society. In a very real way the houses of colonial New England and some of the southern plantations had been functional, but Wright was the first architect to make functionalism the authoritative principle for public as well as for domestic building.\n\nAs early as 1906 he built the Unity Temple in Oak Park, Illinois, the first of those churches that did so much to revolutionize ecclesiastical architecture in the United States. Thereafter he turned his genius to such miscellaneous structures as houses, schools, office buildings, and factories, among them the famous Larkin Building in Buffalo, New York, and the Johnson Wax Company building in Racine, Wisconsin.',
   questions:[
     {q:'The phrase "taken to heart" is closest in meaning to:',opts:['Taken seriously','Criticized','Memorized','Taken offense'],ans:0,exp:'"Taken to heart" = to accept or consider something seriously. Answer: A.'},
     {q:"In what way did Wright's public buildings differ from earlier architects'?",opts:['They were built on a larger scale','Their materials came from the south','They looked more like private homes','Their designs were based on how they would be used'],ans:3,exp:'Wright applied functionalism ("form should follow function"). Answer: D.'},
     {q:'The author mentions the Unity Temple because it ...',opts:["Was Wright's first building",'Influenced the architecture of subsequent churches','Demonstrated traditional ecclesiastical architecture','Was the largest church Wright ever designed'],ans:1,exp:'The passage says it "did so much to revolutionize ecclesiastical architecture." Answer: B.'},
     {q:'The passage mentions all of the following structures were built by Wright EXCEPT:',opts:['Factories','Public buildings','Offices','Southern plantations'],ans:3,exp:'Southern plantations are mentioned as earlier examples, NOT Wright\'s works. Answer: D.'},
     {q:"Which statement best reflects one of Wright's architectural principles?",opts:['Beautiful design is more important than utility','Ecclesiastical architecture should be traditional','A building should fit into its surroundings','Public buildings need not be revolutionary'],ans:2,exp:'Wright believed buildings should be part of an "organic whole" connected to land, community, and society. Answer: C.'},
   ]
  },
]
};

// ════════════════════════════════════════════════════════
// LISTENING TIMELINE
// ════════════════════════════════════════════════════════
const LT = (()=>{
  const tl = [];
  let t = 30;
  for(let i=0;i<25;i++){
    const dur = 18 + Math.floor(Math.random()*8);
    tl.push({start:t, end:t+dur, resume:t+dur+12});
    t += dur + 12 + 5;
  }
  return tl;
})();
const LISTEN_TOTAL = 18*60;

// ════════════════════════════════════════════════════════
// STATE
// ════════════════════════════════════════════════════════
let curSection = null;
let curIdx     = {listening:0,structure:0,reading:0};
let answers    = {listening:{},structure:{},reading:{}};
let raguSet    = {listening:{},structure:{},reading:{}};
let fillInputs = {};
let isFS       = false;
let secTimer   = 0;
let secTmrInt  = null;
let listenSec  = 0;
let listenInt  = null;
let listenQ    = -1;
let listenPhase= 'intro';
let cdRemain   = 0;
let cdInt      = null;

// Flat reading question list
const R_FLAT = [];
SOAL.reading.forEach(grp=>{
  if(grp.type==='fill'){
    grp.questions.forEach((q,qi)=> R_FLAT.push({grpIdx:SOAL.reading.indexOf(grp),qIdx:qi,id:46+R_FLAT.length,grp}));
  } else if(grp.type==='notice'){
    grp.questions.forEach((q,qi)=> R_FLAT.push({grpIdx:SOAL.reading.indexOf(grp),qIdx:qi,id:56+qi,grp}));
  } else if(grp.type==='social'){
    grp.questions.forEach((q,qi)=> R_FLAT.push({grpIdx:SOAL.reading.indexOf(grp),qIdx:qi,id:58+qi,grp}));
  } else if(grp.type==='passage'){
    grp.questions.forEach((q,qi)=> R_FLAT.push({grpIdx:SOAL.reading.indexOf(grp),qIdx:qi,id:grp.id+qi,grp}));
  }
});

// ════════════════════════════════════════════════════════
// SCREEN MANAGER
// ════════════════════════════════════════════════════════
function showSc(id){
  document.querySelectorAll('.sc').forEach(s=>s.classList.remove('on'));
  const el=document.getElementById(id);
  if(el) el.classList.add('on');
  window.scrollTo({top:0,behavior:'smooth'});
}

function syncUI(){
  if(!curSection) return;
  if(curSection==='listening'){
    updateListenNav();
    updateAudioStatusBar();
    return;
  }
  if(isFS) renderFS();
  else renderDash();
}

// ════════════════════════════════════════════════════════
// FULLSCREEN
// ════════════════════════════════════════════════════════
function enterFS(){
  isFS=true;
  document.getElementById('fsMode').classList.add('on');
  document.documentElement.requestFullscreen?.().catch(()=>{});
  document.addEventListener('fullscreenchange',onFSChange);
  if(curSection) renderFS();
  else renderFSIntro('listening');
}
function exitFS(){
  isFS=false;
  document.getElementById('fsMode').classList.remove('on');
  document.exitFullscreen?.().catch(()=>{});
  document.removeEventListener('fullscreenchange',onFSChange);
  if(curSection){ showSc('sc-dash'); renderDash(); }
}
function onFSChange(){ if(!document.fullscreenElement && isFS) exitFS(); }
document.addEventListener('keydown',e=>{ if(e.key==='Escape'&&isFS) exitFS(); });

// ════════════════════════════════════════════════════════
// MULAI
// ════════════════════════════════════════════════════════
function mulaiSimulasi(){
  showSc('sc-dash');
  renderFSIntro('listening');
  enterFS();
}

function renderFSIntro(sec){
  const ICONS ={listening:'🎧',structure:'✏️',reading:'📖'};
  const LABELS={listening:'LISTENING COMPREHENSION',structure:'STRUCTURE & WRITTEN EXPRESSION',reading:'READING COMPREHENSION'};
  const CLRS  ={listening:'#2563EB',structure:'#7C3AED',reading:'#0891B2'};
  const SECTS ={listening:'Section 1 of 3',structure:'Section 2 of 3',reading:'Section 3 of 3'};
  const CNT   ={listening:25,structure:20,reading:25};
  const DUR   ={listening:'18 Menit',structure:'13 Menit',reading:'27 Menit'};
  const FN    ={listening:'startListening()',structure:"startSection('structure')",reading:"startSection('reading')"};
  
  const secNameEl = document.getElementById('fs-sec-name');
  const soalNumEl = document.getElementById('fs-soal-num');
  const leftPane = document.getElementById('fs-left');
  const rightPane = document.getElementById('fs-right');
  const centerPane = document.getElementById('fs-center');
  const tmrTxtEl = document.getElementById('fs-tmr-txt');
  
  if(secNameEl) secNameEl.textContent='Intro Section';
  if(soalNumEl) soalNumEl.textContent='—';
  if(leftPane) leftPane.innerHTML='';
  if(rightPane) rightPane.innerHTML=`<div style="padding:8px">
    <div style="font-size:11px;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.8px;margin-bottom:10px">Info Section</div>
    <div style="font-size:13px;font-weight:800;color:#0F172A;margin-bottom:6px">${LABELS[sec]}</div>
    <div style="font-size:12px;color:#64748B">${CNT[sec]} Soal | ±${DUR[sec]}</div>
    </div>`;
  if(centerPane) centerPane.innerHTML=`
    <div style="max-width:460px;margin:40px auto;text-align:center">
      <div style="font-size:56px;margin-bottom:16px">${ICONS[sec]}</div>
      <div style="font-size:11px;font-weight:700;color:${CLRS[sec]};text-transform:uppercase;letter-spacing:1.5px;margin-bottom:8px">${SECTS[sec]}</div>
      <div style="font-size:22px;font-weight:800;color:#0F172A;margin-bottom:12px">${LABELS[sec]}</div>
      <div style="font-size:13px;color:#64748B;line-height:1.7;margin-bottom:20px">
        ${sec==='listening'?'Audio 1 track penuh (±18 menit) akan berjalan otomatis. Soal muncul sesuai timeline audio.':
          sec==='structure'?'Pilih jawaban yang paling tepat untuk melengkapi kalimat secara tata bahasa.':
          'Baca setiap teks dan jawab pertanyaan berdasarkan informasi dalam teks.'}
      </div>
      <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;
          padding:12px;margin-bottom:24px;display:flex;justify-content:space-around">
        <div style="text-align:center"><div style="font-size:20px;font-weight:800;color:${CLRS[sec]}">${CNT[sec]}</div><div style="font-size:11px;color:#64748B">Soal</div></div>
        <div style="text-align:center"><div style="font-size:20px;font-weight:800;color:${CLRS[sec]}">±${DUR[sec]}</div><div style="font-size:11px;color:#64748B">Estimasi</div></div>
      </div>
      <button onclick="${FN[sec]}"
        style="width:100%;padding:13px;border-radius:10px;border:none;background:${CLRS[sec]};
        color:#fff;font-size:15px;font-weight:700;font-family:inherit;cursor:pointer;
        display:flex;align-items:center;justify-content:center;gap:9px;
        box-shadow:0 4px 14px rgba(0,0,0,.12)">
        ${FN[sec].includes('listen')?'▶ Mulai Listening':FN[sec].includes('struct')?'Mulai Structure':'Mulai Reading'}
      </button>
    </div>`;
  if(tmrTxtEl) tmrTxtEl.textContent='--:--';
}

// ════════════════════════════════════════════════════════
// TIMER PER SECTION
// ════════════════════════════════════════════════════════
const SEC_TIME={listening:18*60,structure:13*60,reading:27*60};
function startSecTimer(sec){
  clearInterval(secTmrInt);
  secTimer=SEC_TIME[sec];
  updTmr();
  secTmrInt=setInterval(()=>{
    secTimer--;
    updTmr();
    if(secTimer<=0){ clearInterval(secTmrInt); onSecEnd(sec); }
  },1000);
}
function updTmr(){
  const m=Math.floor(secTimer/60),s=secTimer%60;
  const txt=String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
  ['fs-tmr-txt','dsh-tmr-txt'].forEach(id=>{const e=document.getElementById(id);if(e)e.textContent=txt;});
  ['fs-tmr','dsh-tmr'].forEach(id=>{
    const e=document.getElementById(id);if(!e)return;
    e.className='tmr';
    if(secTimer<=60)e.classList.add('danger');
    else if(secTimer<=180)e.classList.add('warn');
  });
}
function onSecEnd(sec){
  const nextMap={listening:'structure',structure:'reading',reading:'hasil'};
  const next=nextMap[sec];
  if(next==='hasil'){ finishSim(); return; }
  alert(`Waktu ${sec} habis. Lanjut ke section berikutnya.`);
  curSection=null;
  if(isFS) renderFSIntro(next);
  else showSc('sc-dash');
}

// ════════════════════════════════════════════════════════
// LISTENING
// ════════════════════════════════════════════════════════
function startListening(){
  curSection='listening';
  listenSec=0;
  listenQ=-1;
  listenPhase='intro';
  startSecTimer('listening');
  if(!answers.listening) answers.listening={};
  
  if(isFS) {
    renderFSListening();
  } else {
    showSc('sc-dash');
    renderDashListening();
  }

  clearInterval(listenInt);
  listenInt=setInterval(()=>{
    listenSec+=0.5;
    updListenProgress();
    checkListenSync();
    if(listenSec>=LISTEN_TOTAL && listenPhase!=='done'){
      clearInterval(listenInt);
      listenPhase='done';
      setTimeout(()=>{
        if(curSection==='listening'){
          alert('Waktu Listening telah habis. Lanjut ke Structure section.');
          goNextSection('structure');
        }
      },1000);
    }
  },500);
}

function updListenProgress(){
  const pct=Math.min(100,(listenSec/LISTEN_TOTAL)*100);
  const barsSelector = isFS ? '#fs-wv .wv-b' : '#dsh-wv .wv-b';
  const bars = document.querySelectorAll(barsSelector);
  const pl = Math.floor(pct/100*bars.length);
  bars.forEach((b,i)=>b.classList.toggle('pl',i<=pl));
  
  const m=Math.floor(listenSec/60),s=Math.floor(listenSec%60);
  const txt=`${m}:${String(s).padStart(2,'0')} / 18:00`;
  const timeId = isFS ? 'fs-ap-time' : 'dsh-ap-time';
  const timeEl = document.getElementById(timeId);
  if(timeEl) timeEl.textContent=txt;
}

function checkListenSync(){
  for(let i=0;i<25;i++){
    if(listenSec>=LT[i].end && listenSec<LT[i].resume){
      if(listenPhase!=='answering'||listenQ!==i){
        listenQ=i; listenPhase='answering'; curIdx.listening=i;
        startCountdown(LT[i].resume-listenSec, i);
        syncUI();
      }
      return;
    }
  }
  for(let i=0;i<25;i++){
    if(listenSec>=LT[i].start&&listenSec<LT[i].end){
      if(listenQ!==i||listenPhase!=='question'){
        listenQ=i; listenPhase='question'; curIdx.listening=i;
        syncUI();
      }
      return;
    }
  }
  if(listenSec<LT[0].start && listenPhase!=='intro'){
    listenPhase='intro'; syncUI();
  }
}

function startCountdown(remaining, qIdx){
  clearInterval(cdInt);
  cdRemain=Math.ceil(remaining);
  updateCdUI();
  cdInt=setInterval(()=>{
    cdRemain--;
    updateCdUI();
    if(cdRemain<=0){ clearInterval(cdInt); listenPhase='question'; }
  },1000);
}

function updateCdUI(){
  const pct=(cdRemain/12)*100;
  ['fs-cd-fill','dsh-cd-fill'].forEach(id=>{const e=document.getElementById(id);if(e)e.style.width=pct+'%';});
  ['fs-cd-num','dsh-cd-num'].forEach(id=>{const e=document.getElementById(id);if(e)e.textContent=cdRemain+'s';});
  ['fs-cd-wrap','dsh-cd-wrap'].forEach(id=>{const e=document.getElementById(id);if(e)e.style.display=cdRemain>0?'block':'none';});
}

function setAudioVolume(value){
  const audio = document.getElementById('mainAudio');
  if(audio) audio.volume = parseFloat(value);
}

function pickListenAns(qIdx, optIdx){
  if(answers.listening[qIdx]!==undefined) return;
  if(listenPhase!=='answering' && listenQ!==qIdx) return;
  answers.listening[qIdx]=optIdx;
  delete raguSet.listening[qIdx];
  syncUI();
}

function updateListenNav(){
  const navHtml = buildNavHTML('listening', 25);
  if(isFS){
    const leftPane = document.getElementById('fs-left');
    if(leftPane) leftPane.innerHTML = navHtml;
  } else {
    const leftPane = document.getElementById('dsh-left');
    if(leftPane) leftPane.innerHTML = navHtml;
  }
}

function updateAudioStatusBar(){
  const pct = Math.min(100, (listenSec / LISTEN_TOTAL) * 100);
  const barsSelector = isFS ? '#fs-wv .wv-b' : '#dsh-wv .wv-b';
  const bars = document.querySelectorAll(barsSelector);
  const pl = Math.floor(pct / 100 * bars.length);
  bars.forEach((b, i) => {
    if(i <= pl) b.classList.add('pl');
    else b.classList.remove('pl');
  });
  
  const m = Math.floor(listenSec / 60);
  const s = Math.floor(listenSec % 60);
  const txt = `${m}:${String(s).padStart(2, '0')} / 18:00`;
  const timeId = isFS ? 'fs-ap-time' : 'dsh-ap-time';
  const timeEl = document.getElementById(timeId);
  if(timeEl) timeEl.textContent = txt;
}

function goToListen(sec, idx){
  if(sec!=='listening') return;
  if(idx<0||idx>=25) return;
  if(listenQ>=0 && idx<=listenQ){
    curIdx.listening = idx;
    syncUI();
  } else if(listenPhase==='done'){
    curIdx.listening = idx;
    syncUI();
  }
}

function buildListeningCenter(){
  const isAnswering = listenPhase === 'answering';
  const isIntro = listenQ < 0;
  const q = listenQ >= 0 ? SOAL.listening[listenQ] : null;
  const ans = listenQ >= 0 ? answers.listening[listenQ] : undefined;
  const letters = ['A','B','C','D'];
  
  let html = `
  <div class="audio-bar">
    <div style="display:flex;flex-direction:column;gap:3px">
      <div style="font-size:11px;font-weight:700;color:#2563EB">🎙 Audio Listening</div>
      <div style="font-size:10px;color:#64748B">No rewind · No skip · Volume only</div>
    </div>
    <input type="range" class="ap-vol" min="0" max="1" step="0.1" value="0.8" oninput="setAudioVolume(this.value)">
    <div class="wv-wrap">
      <div class="wv-inner" id="${isFS ? 'fs-wv' : 'dsh-wv'}">
        ${Array.from({length:60}).map(()=>'<div class="wv-b"></div>').join('')}
      </div>
    </div>
    <span class="ap-time" id="${isFS ? 'fs-ap-time' : 'dsh-ap-time'}">0:00 / 18:00</span>
  </div>
  <div id="${isFS ? 'fs-cd-wrap' : 'dsh-cd-wrap'}" style="display:none">
    <div class="cd-label">⏳ Waktu Menjawab: <span id="${isFS ? 'fs-cd-num' : 'dsh-cd-num'}">12s</span></div>
    <div class="cd-bar-wrap"><div class="cd-bar-fill" id="${isFS ? 'fs-cd-fill' : 'dsh-cd-fill'}" style="width:100%"></div></div>
  </div>`;
  
  if(isIntro){
    html += `<div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:12px;padding:18px;text-align:center;">
      <div style="font-size:42px;margin-bottom:10px">🎧</div>
      <div style="font-size:18px;font-weight:800;color:#1E3A8A;margin-bottom:8px">Listening Section</div>
      <div style="font-size:13px;color:#475569;line-height:1.7;">Audio akan diputar otomatis.<br>Dengarkan baik-baik sebelum menjawab.</div>
    </div>`;
    return html;
  }
  
  html += `<div style="margin-bottom:16px">
    <div style="background:#F8FAFF;border:1px solid #DBEAFE;border-radius:12px;padding:16px 18px;margin-bottom:16px">
      <div style="font-size:14px;font-weight:700;color:#1E3A8A;margin-bottom:12px">Soal ${listenQ+1} dari 25</div>
      <div style="font-size:15px;line-height:1.7;color:#0F172A">${q.q}</div>
    </div>
    <div class="opts">`;
  
  q.opts.forEach((opt,oi)=>{
    let cls='opt';
    let disabledAttr='';
    if(ans!==undefined){
      cls+=' dis';
      disabledAttr='disabled';
      if(oi===q.ans) cls+=' ok';
      else if(oi===ans) cls+=' bad';
    } else if(!isAnswering){
      cls+=' dis';
      disabledAttr='disabled';
    }
    html+=`<button class="${cls}" onclick="pickListenAns(${listenQ},${oi})" ${disabledAttr}>
      <div class="opt-c">${letters[oi]}</div><div class="opt-t">${opt}</div>
    </button>`;
  });
  
  html+=`</div>`;
  if(ans!==undefined){
    html+=`<div class="exp-box" style="margin-top:12px">${q.exp}</div>`;
  } else if(!isAnswering){
    html+=`<div class="exp-box" style="margin-top:12px;background:#FEF3C7;border-left-color:#F59E0B">
      <i class="fas fa-volume-up"></i> Dengarkan audio dengan saksama, jawab saat waktu menjawab tiba.</div>`;
  }
  html+=`</div>`;
  return html;
}

function renderFSListening(){
  const secNameEl = document.getElementById('fs-sec-name');
  const soalNumEl = document.getElementById('fs-soal-num');
  const rightPane = document.getElementById('fs-right');
  const centerPane = document.getElementById('fs-center');
  
  if(secNameEl) secNameEl.textContent='Listening Comprehension';
  if(soalNumEl) soalNumEl.textContent=listenQ>=0?`Soal ${listenQ+1} dari 25`:'Intro';
  if(rightPane) rightPane.innerHTML=buildRightInfo('listening',listenQ,25);
  if(centerPane) centerPane.innerHTML=buildListeningCenter();
}

function renderDashListening(){
  if(!curSection || curSection!=='listening') return;
  const secNameEl = document.getElementById('dsh-sec-name');
  const leftPane = document.getElementById('dsh-left');
  const rightPane = document.getElementById('dsh-right');
  const centerPane = document.getElementById('dsh-center');
  
  if(secNameEl) secNameEl.textContent='Listening Comprehension';
  if(leftPane) leftPane.innerHTML=buildNavHTML('listening',25);
  if(rightPane) rightPane.innerHTML=buildRightInfo('listening',listenQ,25);
  if(centerPane) centerPane.innerHTML=buildListeningCenter();
}

// ════════════════════════════════════════════════════════
// START SECTION (structure / reading)
// ════════════════════════════════════════════════════════
function startSection(sec){
  curSection=sec;
  curIdx[sec]=0;
  startSecTimer(sec);
  if(sec==='structure'){
    const html=buildNavHTML('structure',20);
    const fsLeft = document.getElementById('fs-left');
    const dshLeft = document.getElementById('dsh-left');
    if(fsLeft) fsLeft.innerHTML=html;
    if(dshLeft) dshLeft.innerHTML=html;
    const fsSecName = document.getElementById('fs-sec-name');
    const dshSecName = document.getElementById('dsh-sec-name');
    if(fsSecName) fsSecName.textContent='Structure & Written Expression';
    if(dshSecName) dshSecName.textContent='Structure & Written Expression';
    if(isFS){renderFS();}else{showSc('sc-dash');renderDash();}
  } else {
    const html=buildNavHTML('reading',25);
    const fsLeft = document.getElementById('fs-left');
    const dshLeft = document.getElementById('dsh-left');
    if(fsLeft) fsLeft.innerHTML=html;
    if(dshLeft) dshLeft.innerHTML=html;
    const fsSecName = document.getElementById('fs-sec-name');
    const dshSecName = document.getElementById('dsh-sec-name');
    if(fsSecName) fsSecName.textContent='Reading Comprehension';
    if(dshSecName) dshSecName.textContent='Reading Comprehension';
    if(isFS){renderFS();}else{showSc('sc-dash');renderDash();}
  }
}

// ════════════════════════════════════════════════════════
// RENDER FS / DASH
// ════════════════════════════════════════════════════════
function renderFS(){
  if(!curSection) return;
  if(curSection==='listening'){ renderFSListening(); return; }
  const idx = curSection==='reading' ? (curIdx.reading||0) : (curIdx[curSection]||0);
  const total = curSection==='structure' ? 20 : 25;
  const soalNumEl = document.getElementById('fs-soal-num');
  const rightPane = document.getElementById('fs-right');
  const centerPane = document.getElementById('fs-center');
  
  if(soalNumEl) soalNumEl.textContent=`Soal ${idx+1} dari ${total}`;
  if(rightPane) rightPane.innerHTML=buildRightInfo(curSection,idx,total);
  if(centerPane) centerPane.innerHTML=buildSoalHTML(curSection,idx);
  refreshNavHL(curSection,idx);
}

function renderDash(){
  if(!curSection) return;
  if(curSection==='listening'){ renderDashListening(); return; }
  const idx = curSection==='reading' ? (curIdx.reading||0) : (curIdx[curSection]||0);
  const total = curSection==='structure' ? 20 : 25;
  const navHtml = buildNavHTML(curSection,total);
  const leftPane = document.getElementById('dsh-left');
  const rightPane = document.getElementById('dsh-right');
  const centerPane = document.getElementById('dsh-center');
  const secNameEl = document.getElementById('dsh-sec-name');
  
  if(leftPane) leftPane.innerHTML=navHtml;
  if(rightPane) rightPane.innerHTML=buildRightInfo(curSection,idx,total);
  if(centerPane) centerPane.innerHTML=buildSoalHTML(curSection,idx);
  if(secNameEl) secNameEl.textContent = curSection==='structure' ? 'Structure & Written Expression' : 'Reading Comprehension';
}

function refreshNavHL(sec,idx){
  const html=buildNavHTML(sec,sec==='structure'?20:25);
  const leftPane = document.getElementById('fs-left');
  if(leftPane) leftPane.innerHTML=html;
}

// ════════════════════════════════════════════════════════
// BUILD NAV & INFO
// ════════════════════════════════════════════════════════
function buildNavHTML(sec, count){
  const labels = { listening:'🎧 LISTENING', structure:'✏️ STRUCTURE', reading:'📖 READING' };
  let grid = `<div style="font-size:10.5px;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.7px;margin-bottom:8px">${labels[sec]} (${count} SOAL)</div>`;
  grid += '<div class="nav-grid">';
  
  const ids = sec==='listening'?SOAL.listening.map(s=>s.id):
              sec==='structure'?SOAL.structure.map(s=>s.id):
              R_FLAT.map((r,i)=>r.id);
  const ci = sec==='reading' ? (curIdx.reading||0) : (curIdx[sec]||0);
  
  for(let i=0;i<count;i++){
    let isDone=false, isRagu=false;
    if(sec==='listening'){
      isDone = answers.listening && answers.listening[i]!==undefined;
      isRagu = raguSet.listening && raguSet.listening[i];
    } else if(sec==='structure'){
      isDone = answers.structure && answers.structure[i]!==undefined;
      isRagu = raguSet.structure && raguSet.structure[i];
    } else {
      isDone = answers.reading && answers.reading[i]!==undefined;
      isRagu = raguSet.reading && raguSet.reading[i];
    }
    const isCur = i===ci;
    let cls='nb';
    if(isCur) cls+=' cur';
    else if(isRagu) cls+=' ragu';
    else if(isDone) cls+=' done';
    grid+=`<button class="${cls}" onclick="goTo('${sec}',${i})">${ids[i]||i+1}</button>`;
  }
  grid+='</div>';
  grid+=`<div style="margin-top:10px;display:flex;flex-direction:column;gap:5px">
    <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#64748B">
      <div style="width:10px;height:10px;border-radius:50%;background:#22C55E"></div> Selesai</div>
    <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#64748B">
      <div style="width:10px;height:10px;border-radius:50%;background:#EAB308"></div> Ragu-ragu</div>
    <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#64748B">
      <div style="width:10px;height:10px;border-radius:50%;background:#94A3B8"></div> Belum</div>
  </div>`;
  return grid;
}

function buildRightInfo(sec,idx,total){
  let done=0;
  if(sec==='listening') done=Object.keys(answers.listening||{}).length;
  else if(sec==='structure') done=Object.keys(answers.structure||{}).length;
  else done=Object.keys(answers.reading||{}).length;
  const LABELS={listening:'Listening Comprehension',structure:'Structure & Written Expression',reading:'Reading Comprehension'};
  return `<div style="padding:0 0 8px">
    <div style="font-size:11px;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.7px;margin-bottom:8px">Info Section</div>
    <div style="font-size:13px;font-weight:800;color:#0F172A;margin-bottom:4px">${LABELS[sec]}</div>
    <div style="font-size:11px;color:#64748B;margin-bottom:12px">${total} Soal</div>
    <div style="font-size:11px;font-weight:600;color:#64748B;margin-bottom:4px">Progress</div>
    <div class="prog-track"><div class="prog-fill" style="width:${(done/total*100).toFixed(0)}%"></div></div>
    <div style="font-size:11px;color:#64748B;margin-top:4px">${done} / ${total}</div>
  </div>`;
}

// ════════════════════════════════════════════════════════
// BUILD SOAL HTML
// ════════════════════════════════════════════════════════
function buildSoalHTML(sec, idx){
  if(sec==='structure') return buildStructureHTML(idx);
  if(sec==='reading') return buildReadingHTML(idx);
  return '';
}

function buildStructureHTML(idx){
  const s=SOAL.structure[idx];
  const ans=answers.structure[idx];
  const letters=['A','B','C','D'];
  let html=`<div style="font-size:11px;color:#64748B;margin-bottom:12px;display:flex;align-items:center;gap:6px">
    <span style="background:#F3E8FF;border:1px solid #EDE9FE;color:#7C3AED;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700">Soal ${idx+1} dari 20</span>
  </div>
  <div style="background:#F8FAFF;border:1px solid #DBEAFE;border-radius:12px;padding:16px 18px;font-size:16px;font-weight:600;color:#0F172A;line-height:1.8;margin-bottom:16px">`;
  const blankIdx=s.q.indexOf('___');
  if(blankIdx>=0){
    const picked=ans!==undefined?s.opts[ans]:null;
    const isOk=ans===s.ans;
    const blankStyle=ans===undefined?'color:var(--blue);border-color:var(--blue)':
                     isOk?'color:#16A34A;border-color:#22C55E':'color:#DC2626;border-color:#EF4444';
    const blankText=picked||'_____';
    html+=s.q.replace('___',`<span style="display:inline-block;min-width:70px;border-bottom:2.5px solid;text-align:center;padding:0 6px;${blankStyle};font-weight:800;font-family:inherit">${blankText}</span>`);
  } else { html+=s.q; }
  html+=`</div><div class="opts">`;
  s.opts.forEach((opt,oi)=>{
    let cls='opt';
    if(ans!==undefined){cls+=' dis';if(oi===s.ans)cls+=' ok';else if(oi===ans)cls+=' bad';}
    html+=`<button class="${cls}" onclick="pickAns('structure',${idx},${oi})">
      <div class="opt-c">${letters[oi]}</div><div class="opt-t">${opt}</div>
    </button>`;
  });
  html+='</div>';
  if(ans!==undefined) html+=`<div class="exp-box" style="margin-top:8px">${s.exp}</div>`;
  html+=buildFooter('structure',idx,20);
  return html;
}

function buildReadingHTML(flatIdx){
  const item=R_FLAT[flatIdx];
  if(!item) return '';
  const grp=item.grp;
  const qIdx=item.qIdx;
  const q=grp.type==='fill'?grp.questions[qIdx]:grp.questions[qIdx];
  const ans=answers.reading[flatIdx];
  const letters=['A','B','C','D'];
  const partLabels={fill:'Part 1 — Fill Missing Letters',notice:'Part 2 — Read a Notice',
    social:'Part 3 — Social Media Post',passage:grp.group==='mirror'?'Part 4 — Long Passage':'Part 5 — Long Passage'};

  let html=`<div style="font-size:11px;color:#64748B;margin-bottom:12px;display:flex;align-items:center;gap:6px">
    <span style="background:#ECFEFF;border:1px solid #CFFAFE;color:#0891B2;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700">Soal ${item.id} dari 70</span>
    <span style="font-size:11px;color:#94A3B8">${partLabels[grp.type]||''}</span>
  </div>`;

  if(grp.type==='fill'){
    if(qIdx===0){
      html+=`<div style="margin-bottom:12px;font-size:11px;font-weight:600;color:#64748B;background:#FEF9C3;border:1px solid #FDE047;border-radius:8px;padding:8px 12px">
        📝 Lengkapi setiap kata yang tidak lengkap dengan mengetik langsung pada kotak garis
      </div>`;
      let para=grp.paragraph;
      grp.blanks.forEach((bl,bi)=>{
        para=para.replace(`[${bl.key}]`, `<input type="text" id="fill-${flatIdx+bi}" data-flat="${flatIdx+bi}" data-blankidx="${bi}" data-grpblank="${bi}" data-ans="${bl.ans}" class="fill-blank" placeholder="${bl.key}" ${(fillInputs[flatIdx+bi]||answers.reading[flatIdx+bi])!==undefined?'disabled':''} value="${fillInputs[flatIdx+bi]||answers.reading[flatIdx+bi]||''}" onkeydown="onFillKey(event,${flatIdx+bi},${bi},'${bl.ans}')" oninput="onFillInput(this,${flatIdx+bi},'${bl.ans}')">`);
      });
      html+=`<div class="fill-text">${para}</div>`;
      html+=`<div style="font-size:12px;color:#64748B;margin-top:10px"><i class="fas fa-info-circle"></i> Tekan Enter setelah mengisi setiap kotak untuk konfirmasi jawaban.</div>`;
    } else {
      const bl=grp.blanks[qIdx];
      const flatI=flatIdx;
      html+=`<div style="font-size:13.5px;color:#64748B;margin-bottom:8px">Lengkapi bagian yang kosong:</div>
      <div style="background:#F8FAFF;border:1px solid #DBEAFE;border-radius:12px;padding:14px 18px;font-size:15px;font-weight:600;color:#0F172A;line-height:2;margin-bottom:14px">
        ... ${q.q.replace('Complete: ','').replace(/"/g,'')} ...
      </div>
      <div style="margin-bottom:12px">
        <label style="font-size:13px;color:#64748B;margin-bottom:6px;display:block;font-weight:600">Jawaban Anda:</label>
        <input type="text" id="fill-single-${flatI}" class="fill-blank" placeholder="Ketik jawaban..." style="width:180px;font-size:16px;padding:6px 10px" ${ans!==undefined?'disabled':''} value="${ans||''}" onkeydown="onFillKey(event,${flatI},${qIdx},'${bl.ans}')" oninput="onFillInput(this,${flatI},'${bl.ans}')">
        ${ans!==undefined?`<span style="font-size:13px;font-weight:700;color:${ans.toLowerCase().trim()===bl.ans.toLowerCase().trim()?'#16A34A':'#DC2626'};margin-left:10px">
          ${ans.toLowerCase().trim()===bl.ans.toLowerCase().trim()?'✓ Benar':'✗ Salah — Benar: '+bl.ans}
        </span>`:''}
      </div>
      ${ans!==undefined?`<div class="exp-box">${q.exp}</div>`:''}`;
    }
    html+=buildFooter('reading',flatIdx,25);
    return html;
  }

  if(grp.type==='notice'){
    const qi=grp.questions[qIdx];
    html+=`<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:12px">
      <div class="notice-card">
        <div style="font-size:11px;opacity:.7;font-weight:600;margin-bottom:6px">${grp.notice_subtitle||'OFFICIAL NOTICE'}</div>
        <div style="font-size:22px;margin-bottom:4px">${grp.notice_icon||'📋'}</div>
        <h3>${grp.notice_title}</h3>
        <p>${grp.notice_body}</p>
      </div>
      <div style="display:flex;flex-direction:column;gap:12px">
        <div class="soal-q">${qi.q}</div>
        <div class="opts">`;
    qi.opts.forEach((opt,oi)=>{
      let cls='opt';
      if(ans!==undefined){cls+=' dis';if(oi===qi.ans)cls+=' ok';else if(oi===ans)cls+=' bad';}
      html+=`<button class="${cls}" onclick="pickAns('reading',${flatIdx},${oi})">
        <div class="opt-c">${letters[oi]}</div><div class="opt-t">${opt}</div>
      </button>`;
    });
    html+=`</div>${ans!==undefined?`<div class="exp-box">${qi.exp}</div>`:''}</div></div>`;
    html+=buildFooter('reading',flatIdx,25);
    return html;
  }

  if(grp.type==='social'){
    const qi=grp.questions[qIdx];
    const pb=grp.post_body;
    html+=`<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:12px">
      <div class="social-post">
        <div class="sp-head">
          <div class="sp-avatar">${grp.post_avatar}</div>
          <div>
            <div style="font-size:13px;font-weight:700;color:#0F172A">${grp.post_author}</div>
            <div style="font-size:11px;color:#94A3B8">${grp.post_handle||''} · ${grp.post_time||''}</div>
          </div>
          <div style="margin-left:auto;background:#1DA1F2;color:#fff;font-size:10px;font-weight:700;padding:3px 8px;border-radius:12px">Follow</div>
        </div>
        <div class="sp-body">${pb}</div>
        <div class="sp-foot"><span>❤️ 128</span><span>💬 24</span><span>🔁 Share</span></div>
      </div>
      <div style="display:flex;flex-direction:column;gap:12px">
        <div class="soal-q">${qi.q}</div>
        <div class="opts">`;
    qi.opts.forEach((opt,oi)=>{
      let cls='opt';
      if(ans!==undefined){cls+=' dis';if(oi===qi.ans)cls+=' ok';else if(oi===ans)cls+=' bad';}
      html+=`<button class="${cls}" onclick="pickAns('reading',${flatIdx},${oi})">
        <div class="opt-c">${letters[oi]}</div><div class="opt-t">${opt}</div>
      </button>`;
    });
    html+=`</div>${ans!==undefined?`<div class="exp-box">${qi.exp}</div>`:''}</div></div>`;
    html+=buildFooter('reading',flatIdx,25);
    return html;
  }

  if(grp.type==='passage'){
    const qi=grp.questions[qIdx];
    html+=`<div class="split-grid">
      <div class="split-l">
        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#94A3B8;margin-bottom:10px">Passage</div>
        ${grp.passage.split('\n\n').map(p=>`<p style="margin-bottom:12px">${p}</p>`).join('')}
      </div>
      <div class="split-r">
        <div class="soal-q">${qi.q}</div>
        <div class="opts">`;
    qi.opts.forEach((opt,oi)=>{
      let cls='opt';
      if(ans!==undefined){cls+=' dis';if(oi===qi.ans)cls+=' ok';else if(oi===ans)cls+=' bad';}
      html+=`<button class="${cls}" onclick="pickAns('reading',${flatIdx},${oi})">
        <div class="opt-c">${letters[oi]}</div><div class="opt-t">${opt}</div>
      </button>`;
    });
    html+=`</div>`;
    if(ans!==undefined) html+=`<div class="exp-box">${qi.exp}</div>`;
    html+=`</div></div>`;
    html+=buildFooter('reading',flatIdx,25);
    return html;
  }
  return '';
}

function buildFooter(sec, idx, total){
  const isFirst=idx===0;
  const isLast=idx===total-1;
  const isLastSec=sec==='reading';
  const nextSecMap={listening:'structure',structure:'reading'};
  let html=`<div class="soal-footer">
    <button class="btn-sblm" onclick="goTo('${sec}',${idx-1})" ${isFirst?'disabled':''}>
      <i class="fas fa-chevron-left"></i> Sebelumnya
    </button>
    <button class="btn-ragu" onclick="toggleRagu('${sec}',${idx})">
      <i class="fas fa-question"></i> Ragu-ragu
    </button>`;
  if(isLast){
    if(isLastSec){
      html+=`<button class="btn-next" style="background:#22C55E" onclick="finishSim()">
        <i class="fas fa-check"></i> Selesaikan Simulasi
      </button>`;
    } else {
      const next=nextSecMap[sec];
      html+=`<button class="btn-next" onclick="goNextSection('${next}')">
        Lanjut ke ${next==='structure'?'Structure':'Reading'} <i class="fas fa-chevron-right"></i>
      </button>`;
    }
  } else {
    html+=`<button class="btn-next" onclick="goTo('${sec}',${idx+1})">
      Selanjutnya <i class="fas fa-chevron-right"></i>
    </button>`;
  }
  html+='</div>';
  return html;
}

// ════════════════════════════════════════════════════════
// NAVIGATION
// ════════════════════════════════════════════════════════
function goTo(sec, idx){
  if(sec==='listening'){ goToListen(sec,idx); return; }
  const total=sec==='structure'?20:25;
  if(idx<0||idx>=total) return;
  if(sec==='reading'){
    if(idx>=0 && idx<R_FLAT.length) curIdx.reading=idx;
  } else {
    curIdx[sec]=idx;
  }
  syncUI();
}

function goNextSection(next){
  curSection=null;
  clearInterval(secTmrInt);
  if(next==='hasil'){
    finishSim();
    return;
  }
  if(isFS) renderFSIntro(next);
  else showSc('sc-dash');
}

function toggleRagu(sec, idx){
  if(!raguSet[sec]) raguSet[sec]={};
  raguSet[sec][idx]=!raguSet[sec][idx];
  if(sec==='structure' && answers.structure[idx]!==undefined) raguSet[sec][idx]=false;
  else if(sec==='reading' && answers.reading[idx]!==undefined) raguSet[sec][idx]=false;
  else if(sec==='listening' && answers.listening[idx]!==undefined) raguSet[sec][idx]=false;
  syncUI();
}

// ════════════════════════════════════════════════════════
// ANSWER
// ════════════════════════════════════════════════════════
function pickAns(sec, idx, optIdx){
  if(sec==='structure'){
    if(answers.structure[idx]!==undefined) return;
    answers.structure[idx]=optIdx;
    delete raguSet.structure[idx];
    syncUI();
    if(idx<19) setTimeout(()=>goTo('structure',idx+1),900);
  } else if(sec==='reading'){
    if(answers.reading[idx]!==undefined) return;
    answers.reading[idx]=optIdx;
    delete raguSet.reading[idx];
    syncUI();
    if(idx<24) setTimeout(()=>goTo('reading',idx+1),900);
  }
}

function onFillInput(el, flatIdx, correctAns){
  el.className='fill-blank';
}

function onFillKey(e, flatIdx, blankIdx, correctAns){
  if(e.key==='Enter'){
    e.preventDefault();
    const val=e.target.value.trim();
    if(!val) return;
    submitFill(e.target, flatIdx, correctAns);
  }
}

function submitFill(el, flatIdx, correctAns){
  const val=el.value.trim();
  if(!val||answers.reading[flatIdx]!==undefined) return;
  answers.reading[flatIdx]=val;
  el.disabled=true;
  const isOk=val.toLowerCase()===correctAns.toLowerCase();
  el.className='fill-blank '+(isOk?'ok':'bad');
  delete raguSet.reading[flatIdx];
  const nextFlat=flatIdx+1;
  if(nextFlat<25 && R_FLAT[nextFlat]?.grp?.type==='fill'){
    setTimeout(()=>{
      curIdx.reading=nextFlat;
      const nextEl=document.getElementById(`fill-${nextFlat}`)||document.getElementById(`fill-single-${nextFlat}`);
      if(nextEl) nextEl.focus();
    },300);
  }
  syncUI();
}

// ════════════════════════════════════════════════════════
// FINISH
// ════════════════════════════════════════════════════════
function finishSim(){
  clearInterval(secTmrInt);
  clearInterval(listenInt);
  clearInterval(cdInt);
  if(isFS){
    isFS=false;
    const fsMode=document.getElementById('fsMode');
    if(fsMode) fsMode.classList.remove('on');
    document.exitFullscreen?.().catch(()=>{});
  }

  let bl={listening:0,structure:0,reading:0};
  SOAL.listening.forEach((s,i)=>{ if(answers.listening && answers.listening[i]===s.ans) bl.listening++; });
  SOAL.structure.forEach((s,i)=>{ if(answers.structure && answers.structure[i]===s.ans) bl.structure++; });
  R_FLAT.forEach((item,i)=>{
    if(!answers.reading) return;
    const grp=item.grp; const qIdx=item.qIdx; const ans=answers.reading[i];
    const q=grp.type==='fill'?grp.blanks[qIdx]:grp.questions[qIdx];
    const correct=grp.type==='fill'?q.ans:q.ans;
    const isOk=grp.type==='fill'?(ans!==undefined && ans.toLowerCase().trim()===correct.toLowerCase().trim()):(ans===correct);
    if(isOk) bl.reading++;
  });

  const total=bl.listening+bl.structure+bl.reading;
  const pct=Math.round((total/70)*100);
  
  const hListening=document.getElementById('h-l');
  const hStructure=document.getElementById('h-s');
  const hReading=document.getElementById('h-r');
  const hTotal=document.getElementById('h-total');
  const hPct=document.getElementById('h-pct');
  const hasilEmoji=document.getElementById('hasil-emoji');
  
  if(hListening) hListening.textContent=bl.listening+'/25';
  if(hStructure) hStructure.textContent=bl.structure+'/20';
  if(hReading) hReading.textContent=bl.reading+'/25';
  if(hTotal) hTotal.textContent=total+'/70';
  if(hPct) hPct.textContent=pct+'%';
  if(hasilEmoji) hasilEmoji.textContent=pct>=80?'🏆':pct>=60?'🎯':'💪';
  
  buildReview();
  showSc('sc-hasil');
}

function showPembahasan(){ showSc('sc-bhs'); }

function buildReview(){
  const letters=['A','B','C','D'];
  let html='';

  SOAL.listening.forEach((s,i)=>{
    const ans=answers.listening ? answers.listening[i] : undefined;
    const isOk=ans===s.ans;
    const noAns=ans===undefined;
    html+=`<div class="rev-item" data-section="listening">
      <div class="rev-head">
        <div style="width:26px;height:26px;border-radius:6px;background:${noAns?'#94A3B8':isOk?'#22C55E':'#EF4444'};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0">${s.id}</div>
        <div style="font-size:13px;font-weight:600;color:#0F172A;flex:1;line-height:1.4">${s.q}</div>
        <span style="font-size:12px;font-weight:700;color:${noAns?'#94A3B8':isOk?'#22C55E':'#EF4444'};flex-shrink:0">${noAns?'—':isOk?'✓ Benar':'✗ Salah'}</span>
      </div>
      <div class="rev-body">
        ${s.opts.map((opt,oi)=>{let cls='rev-opt';if(oi===s.ans) cls+=' ok';else if(oi===ans&&!isOk) cls+=' bad';
          return `<div class="${cls}"><strong>${letters[oi]}.</strong> ${opt}${oi===s.ans?' <em style="font-size:10px;font-weight:700">← Jawaban benar</em>':''}${oi===ans&&!isOk?' <em style="font-size:10px;font-weight:700">← Jawaban Anda</em>':''}</div>`;}).join('')}
        ${noAns?`<div style="font-size:12px;color:#94A3B8;margin-top:6px;font-style:italic">Tidak dijawab</div>`:''}
        <div class="exp-box" style="margin-top:6px">${s.exp}</div>
      </div>
    </div>`;
  });

  SOAL.structure.forEach((s,i)=>{
    const ans=answers.structure ? answers.structure[i] : undefined;
    const isOk=ans===s.ans;
    html+=`<div class="rev-item" data-section="structure">
      <div class="rev-head">
        <div style="width:26px;height:26px;border-radius:6px;background:${isOk?'#22C55E':'#EF4444'};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0">${s.id}</div>
        <div style="font-size:13px;font-weight:600;color:#0F172A;flex:1">${s.q.replace('___','[blank]')}</div>
        <span style="font-size:12px;font-weight:700;color:${isOk?'#22C55E':'#EF4444'};flex-shrink:0">${isOk?'✓':'✗'}</span>
      </div>
      <div class="rev-body">
        ${s.opts.map((opt,oi)=>{let cls='rev-opt';if(oi===s.ans)cls+=' ok';else if(oi===ans)cls+=' bad';
          return `<div class="${cls}"><strong>${letters[oi]}.</strong> ${opt}</div>`;}).join('')}
        <div class="exp-box" style="margin-top:6px">${s.exp}</div>
      </div>
    </div>`;
  });

  R_FLAT.forEach((item,i)=>{
    if(!answers.reading) return;
    const grp=item.grp; const qIdx=item.qIdx; const ans=answers.reading[i];
    const q=grp.type==='fill'?grp.blanks[qIdx]:grp.questions[qIdx];
    const correct=grp.type==='fill'?q.ans:q.ans;
    const exp=grp.type==='fill'?grp.questions[qIdx].exp:q.exp;
    const isOk=grp.type==='fill'?(ans!==undefined && ans.toLowerCase().trim()===correct.toLowerCase().trim()):(ans===correct);
    html+=`<div class="rev-item" data-section="reading">
      <div class="rev-head">
        <div style="width:26px;height:26px;border-radius:6px;background:${isOk?'#22C55E':'#EF4444'};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:#fff;flex-shrink:0">${item.id}</div>
        <div style="font-size:13px;font-weight:600;color:#0F172A;flex:1">${grp.type==='fill'?grp.questions[qIdx].q:q.q}</div>
        <span style="font-size:12px;font-weight:700;color:${isOk?'#22C55E':'#EF4444'};flex-shrink:0">${isOk?'✓':'✗'}</span>
      </div>
      <div class="rev-body">
        ${grp.type==='fill'?`<div>Jawaban Anda: <strong>${ans||'—'}</strong> | Benar: <strong>${correct}</strong></div>`:
          q.opts.map((opt,oi)=>{let cls='rev-opt';if(oi===q.ans)cls+=' ok';else if(oi===ans)cls+=' bad';
            return `<div class="${cls}"><strong>${letters[oi]}.</strong> ${opt}</div>`;}).join('')}
        <div class="exp-box" style="margin-top:6px">${exp}</div>
      </div>
    </div>`;
  });

  const revList=document.getElementById('rev-list');
  if(revList) revList.innerHTML=html;
}

function filterRev(sec, tab){
  document.querySelectorAll('.rtab').forEach(t=>t.classList.remove('on'));
  tab.classList.add('on');
  document.querySelectorAll('.rev-item').forEach(item=>{
    item.style.display=(sec==='all'||item.dataset.section===sec)?'block':'none';
  });
}

function resetSim(){
  clearInterval(secTmrInt);
  clearInterval(listenInt);
  clearInterval(cdInt);
  curSection=null;
  answers={listening:{},structure:{},reading:{}};
  raguSet={listening:{},structure:{},reading:{}};
  fillInputs={};
  listenSec=0;
  listenQ=-1;
  listenPhase='intro';
  secTimer=0;
  showSc('sc-intro');
}
</script>