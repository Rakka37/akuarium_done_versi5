<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Monitoring Akuarium IoT</title>

<style>
:root{
  --bg:#020617;
  --card:#0f172a;
  --accent:#38bdf8;
  --text:#e5e7eb;
  --soft:#94a3b8;
}
*{box-sizing:border-box}
body{
  margin:0;min-height:100vh;background:var(--bg);color:var(--text);
  font-family:system-ui;display:flex;flex-direction:column;align-items:center;
}
header{max-width:900px;text-align:center;padding:20px 15px 10px}
header h1{font-size:clamp(1.1rem,3vw,1.8rem)}
header p{color:var(--soft)}
.menu{display:flex;flex-wrap:wrap;gap:8px;justify-content:center;margin:10px 0}
.menu button{
  padding:8px 14px;border-radius:10px;border:1px solid var(--accent);
  background:#020617;color:var(--text);cursor:pointer
}
.menu button.active{background:var(--accent);color:#020617;font-weight:700}
.container{
  width:100%;max-width:900px;padding:15px;
  display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px
}
.card{
  background:linear-gradient(145deg,#0f172a,#020617);
  border-radius:18px;padding:22px;box-shadow:0 15px 30px rgba(0,0,0,.4)
}
.card-title{color:var(--soft);margin-bottom:6px}
.value{font-size:2.2rem;font-weight:700;color:var(--accent)}
button.main{
  width:100%;padding:12px;border:none;border-radius:12px;
  background:var(--accent);color:#020617;font-weight:700;cursor:pointer
}
.section{width:100%;max-width:900px;padding:15px;display:none}
.section.active{display:block}
table{width:100%;border-collapse:collapse;font-size:.85rem}
th,td{padding:8px;border-bottom:1px solid #1e293b}
th{color:var(--soft);text-align:left}
footer{margin:15px 0;font-size:.75rem;color:#64748b}

.images{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
  gap:12px;
}
.images img{
  width:100%;
  border-radius:12px;
  border:2px solid #1e293b;
}


.image-box{
  background:#020617;
  border-radius:10px;
  padding:6px;
}

.viewer{
  width:100%;
  height:130px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:#000;
  border-radius:10px;
  overflow:hidden;
}

.viewer img{
  width:100%;
  cursor:pointer;
}

.viewer canvas{
  width:100%;
  height:100%;
  display:none;
}

.image-buttons{
  display:flex;
  gap:6px;
  margin-top:4px;
}

.image-buttons button{
  flex:1;
  border:none;
  border-radius:6px;
  cursor:pointer;
  font-size:11px;
  padding:4px;
}

.btn-img{background:#38bdf8;color:#000}
.btn-hist{background:#22c55e;color:#000}



.status-box{
  margin-bottom:15px;
  font-weight:bold;
}

.ok{color:#22c55e;}
.fail{color:#ef4444;}

#notif{
  position:fixed;
  top:20px;
  left:50%;
  transform:translateX(-50%);
  min-width:280px;
  padding:16px 22px;
  border-radius:16px;
  background:#020617;
  border:2px solid var(--accent);
  display:none;
  z-index:999;
  text-align:center;
}
</style>
</head>

<body>

<header>
  <h1>Monitoring Akuarium IoT</h1>
  <p>Monitoring & Kontrol Real-Time</p>
</header>

<div class="menu">
  <button class="active" onclick="openTab('monitor',this)">Monitoring</button>
  <button onclick="openTab('pakan',this)">Riwayat Pakan</button>
  <button onclick="openTab('notiflog',this)">Riwayat Notifikasi</button>
  <button onclick="openTab('gambar',this)">Gambar</button>
</div>

<!-- MONITORING -->
<div id="monitor" class="section active">
  <div class="container">

    <div class="card">
      <div class="card-title">💧 Suhu Air</div>
      <div class="value"><span id="air">--</span> °C</div>
    </div>

    <div class="card">
      <div class="card-title">🧪 Kekeruhan</div>
      <div class="value"><span id="ntu">--</span> NTU</div>
    </div>

  <div class="card">
  <div class="card-title">⚗️ pH Air</div>
  
  <div class="value">
    <span id="ph">--</span>
  </div>

  <!-- 🔥 STATUS KECIL -->
  <div id="phStatus" style="margin-top:6px;font-size:12px;color:#94a3b8">
    --
  </div>

</div>

    <div class="card">
      <div class="card-title">📏 Jarak Air</div>
      <div class="value">
        <span id="jarak">--</span>
      </div>
    
      <div id="statusJarak"
           style="margin-top:6px;font-size:12px;color:#94a3b8">
        --
      </div>
    </div>
    
    <div class="card">
      <div class="card-title">🍽️ Pakan</div>
      <button class="main" onclick="feed()">Beri Makan Manual</button>
    </div>

  </div>
</div>

<!-- HISTORY PAKAN -->
<div id="pakan" class="section">
  <table id="tblPakan"></table>
</div>

<!-- HISTORY NOTIF -->
<div id="notiflog" class="section">
  <table id="tblNotif"></table>
</div>

<!-- GAMBAR -->
<div id="gambar" class="section">
  <div class="status-box" id="kameraStatus"></div>
  <div class="images" id="imgList"></div>
</div>

<footer>© Sistem Akuarium IoT</footer>
<div id="notif"></div>

<script>

// ================= TAB =================
function openTab(id,btn){
  document.querySelectorAll('.section').forEach(s=>s.classList.remove('active'));
  document.querySelectorAll('.menu button').forEach(b=>b.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  btn.classList.add('active');
}


// ================= REALTIME SENSOR =================
function loadData(){
    fetch("get.php",{cache:"no-store"})
    .then(r=>r.json())
    .then(d=>{
    air.innerText = d.air;
    ntu.innerText = d.ntu;
    ph.innerText = d.ph;
    jarak.innerText = d.jarak + " cm";

    // STATUS JARAK AIR
    let sj = document.getElementById("statusJarak");
    if(Number(d.jarak) <= 8){
      sj.innerHTML = "🟢 PENUH";
      sj.style.color = "#22c55e";
    }
    else if(Number(d.jarak) >= 17){
      sj.innerHTML = "🔴 RENDAH";
      sj.style.color = "#ef4444";
    }
    else{
      sj.innerHTML = "🟡 NORMAL";
      sj.style.color = "#eab308";
    }

  // 🔥 STATUS PH (KECIL DI BAWAH)
  if(d.ph_status == "TURUN"){
    phStatus.innerHTML = "🟢 SENSOR MENYENTUH AIR (SEDANG UKUR)";
    phStatus.style.color = "#22c55e";
  }else{
    phStatus.innerHTML = "🔴 SENSOR TIDAK MENYENTUH AIR (ISTIRAHAT)";
    phStatus.style.color = "#ef4444";
  }

});

}

setInterval(loadData,2000);
loadData();


// ================= MANUAL FEED =================
function feed(){
  fetch("feed.php?feed=1",{cache:"no-store"});
}


// ================= FLOATING NOTIF (QUEUE) =================
let lastID = 0;
let notifQueue = [];
let showing = false;

function cekNotif(){

  fetch("last_notif.php",{cache:"no-store"})
  .then(r=>r.json())
  .then(d=>{

    if(!d) return;

    if(lastID === 0){
      lastID = d.id;
      return;
    }

    if(d.id > lastID){

      lastID = d.id;

      notifQueue.push(d.pesan);

      showNotif();

      loadHistory();
    }

  });

}

setInterval(cekNotif,1000);


// tampilkan notif bergantian
function showNotif(){

  if(showing) return;

  if(notifQueue.length === 0) return;

  showing = true;

  let pesan = notifQueue.shift();

  notif.innerText = pesan;
  notif.style.display = "block";

  setTimeout(()=>{

    notif.style.display = "none";
    showing = false;

    setTimeout(showNotif,300);

  },2500);

}


// ================= LOAD HISTORY =================
function loadHistory(){

  fetch("history_pakan.php",{cache:"no-store"})
  .then(r=>r.json())
  .then(d=>{

    let h="<tr><th>Waktu</th><th>Mode</th><th>Putaran</th><th>Keterangan</th></tr>";

    d.forEach(x=>{
      h+=`<tr>
      <td>${x.waktu}</td>
      <td>${x.mode}</td>
      <td>${x.jumlah_putaran}</td>
      <td>${x.keterangan}</td>
      </tr>`;
    });

    tblPakan.innerHTML=h;

  });


  fetch("history_notifikasi.php",{cache:"no-store"})
  .then(r=>r.json())
  .then(d=>{

    let h="<tr><th>Waktu</th><th>Jenis</th><th>Pesan</th></tr>";

    d.forEach(x=>{
      h+=`<tr>
      <td>${x.waktu}</td>
      <td>${x.jenis}</td>
      <td>${x.pesan}</td>
      </tr>`;
    });

    tblNotif.innerHTML=h;

  });

}

setInterval(loadHistory,3000);
loadHistory();


// ================= LOAD IMAGES =================
function cekKamera(){

fetch("health.php?check=1",{cache:"no-store"})
.then(r=>r.json())
.then(d=>{

 if(d.status=="ONLINE"){

   document.getElementById("kameraStatus").innerHTML =
   "<span style='color:green'>🟢 Kamera Aktif</span>";

 }else{

   document.getElementById("kameraStatus").innerHTML =
   "<span style='color:red'>🔴 Kamera Tidak Terhubung</span>";

 }

});
}

//HISTOGRAM
function drawHistogram(img,canvas){

let ctx=canvas.getContext("2d")

canvas.width=256
canvas.height=120

let temp=document.createElement("canvas")
let tctx=temp.getContext("2d")

temp.width=img.width
temp.height=img.height

tctx.drawImage(img,0,0)

let data=tctx.getImageData(0,0,temp.width,temp.height).data

let hist=new Array(256).fill(0)

for(let i=0;i<data.length;i+=4){

let gray=(data[i]+data[i+1]+data[i+2])/3
hist[Math.floor(gray)]++

}

let max=Math.max(...hist)

ctx.clearRect(0,0,256,120)

for(let i=0;i<256;i++){

let h=hist[i]/max*120

ctx.fillStyle="#38bdf8"
ctx.fillRect(i,120-h,1,h)

}

} 


// ================= LOAD IMAGES =================
function loadImages(){
    fetch("get_images.php",{cache:"no-store"})
    .then(r=>r.json())
    .then(d=>{
    
     let html="";
    
     d.forEach((i,index)=>{
    
      let file=i.path.split('/').pop()
    
      html += `
      <div class="image-box">

        <div class="viewer">

          <img id="img${index}" 
          src="https://akuariumrakka.my.id/uploads/${file}" 
          onclick="window.open(this.src)">

          <canvas id="hist${index}"></canvas>

        </div>

        <div style="font-size:12px;color:#94a3b8">${i.waktu}</div>

        <div class="image-buttons">

        <button class="btn-img"
        onclick="
        document.getElementById('img${index}').style.display='block';
        document.getElementById('hist${index}').style.display='none';
        ">Gambar</button>

        <button class="btn-hist"
        onclick="
        let img=document.getElementById('img${index}');
        let canvas=document.getElementById('hist${index}');
        img.style.display='none';
        canvas.style.display='block';
        drawHistogram(img,canvas);
        ">Histogram</button>

        </div>

      </div>
      `;
    
     });
    
     document.getElementById("imgList").innerHTML = html;
    
    });

}

setInterval(loadImages,5000);
loadImages();
setInterval(cekKamera,5000);
cekKamera();


</script>

</body>
</html>