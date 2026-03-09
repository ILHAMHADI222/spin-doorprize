<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Doorprize Hallonet</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

<style>

body{
margin:0;
height:100vh;
font-family:Poppins;
overflow:hidden;
background:#000;
}

.banner{
width:100vw;
height:100vh;
background:url("bg.png") center/cover;
position:relative;
transition:0.5s;
}

.name-area{
position:absolute;
left:0;
top:0;
width:55%;
height:100%;
display:flex;
justify-content:center;
align-items:center;
}

.nama{
font-size:40px;
font-weight:800;
color:white;
text-align:center;
letter-spacing:4px;
text-shadow:0 0 20px rgba(255,255,255,0.6),0 0 40px rgba(0,0,0,0.7);
}

.modal{
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.85);
justify-content:center;
align-items:center;
z-index:999;
}

.modal-box{
background:linear-gradient(180deg,#ffffff,#f3f3f3);
padding:70px 90px;
border-radius:30px;
text-align:center;
box-shadow:0 25px 80px rgba(0,0,0,0.5);
animation:popup 0.4s ease;
}

.modal-title{
font-size:36px;
font-weight:800;
color:#ff8c00;
letter-spacing:2px;
margin-bottom:15px;
}

#winnerName{
font-size:80px;
font-weight:800;
margin:25px 0;
color:#111;
letter-spacing:4px;
text-transform:uppercase;
}

.modal-box button{
margin-top:10px;
padding:12px 35px;
border:none;
border-radius:12px;
background:#ff9800;
color:white;
font-weight:600;
font-size:16px;
cursor:pointer;
}

@keyframes popup{
0%{transform:scale(0.6);opacity:0;}
100%{transform:scale(1);opacity:1;}
}

</style>
</head>

<body>

<div class="banner" id="banner">

<div class="name-area">
<div class="nama" id="namaMitra">
Tekan Untuk Memulai
</div>
</div>

</div>

<div id="winnerModal" class="modal">
<div class="modal-box">

<div class="modal-title">🎉 SELAMAT UNTUK PEMENANG 🎉</div>
<div id="winnerName"></div>

<button onclick="tutup()">TUTUP</button>

</div>
</div>

<audio id="drum" src="audio/drum.mp3"></audio>
<audio id="win" src="audio/win.mp3"></audio>

<script>

let spinInterval=null
let isSpinning=false
let pesertaPool=[]
let dataPeserta=[]
let targetWinner=""
let currentMode="receh"

const drum=document.getElementById("drum")
const win=document.getElementById("win")
const namaElement=document.getElementById("namaMitra")
const banner=document.getElementById("banner")

/* BACKGROUND */

function setBG(no){
banner.style.backgroundImage="url(bg"+no+".png)"
}

/* KEYBOARD */

document.addEventListener("keydown",e=>{

const key=e.key.toLowerCase()

if(key==="r") mulaiSpin("receh")

if(["1","2","3","4"].includes(key)){
mulaiSpin(key)
}

if(key==="a") setBG(1)
if(key==="b") setBG(2)
if(key==="c") setBG(3)
if(key==="d") setBG(4)
if(key==="e") setBG(5)

if(e.code==="Space") stopSpin()

})

/* AMBIL PESERTA */

async function ambilPeserta(){

try{

const res=await fetch("get_peserta.php?"+Date.now())
const data=await res.json()

dataPeserta=data

/* SEMUA UNTUK ROLLING */

pesertaPool=data
.filter(p=>p.nama)
.map(p=>p.nama.trim())

targetWinner=""

/* CEK TARGET JUARA */

for(let i=0;i<data.length;i++){

let juara=String(data[i].juara || "").trim()

if(juara===currentMode){

if(currentMode==="receh" || data[i].kategori==="pelanggan"){

targetWinner=data[i].nama.trim()
break

}

}

}

}catch(err){

console.log("error fetch",err)

}

}

/* START SPIN */

async function mulaiSpin(mode){

if(isSpinning) return

currentMode=String(mode)

await ambilPeserta()

if(pesertaPool.length===0){
alert("Peserta kosong")
return
}

isSpinning=true

drum.currentTime=0
drum.loop=true
drum.play()

spinInterval=setInterval(()=>{

let random=Math.floor(Math.random()*pesertaPool.length)

namaElement.innerText=pesertaPool[random]

},40)

}

/* STOP SPIN */

function stopSpin(){

if(!isSpinning) return

clearInterval(spinInterval)

drum.pause()

isSpinning=false

let winner=namaElement.innerText

/* SAFETY JUARA */

if(["1","2","3","4"].includes(currentMode)){

let peserta=dataPeserta.find(p=>p.nama.trim()===winner)

if(!peserta || peserta.kategori!=="pelanggan"){

let pelanggan=dataPeserta.filter(p=>p.kategori==="pelanggan")

let random=pelanggan[Math.floor(Math.random()*pelanggan.length)]

winner=random.nama

}

}

/* PRIORITAS TARGET DB */

if(targetWinner!==""){
winner=targetWinner
}

namaElement.innerText=winner

document.getElementById("winnerName").innerText=winner
document.getElementById("winnerModal").style.display="flex"

win.currentTime=0
win.play()

confetti({
particleCount:250,
spread:180,
origin:{y:0.6}
})

}

/* TUTUP */

function tutup(){

let nama=document.getElementById("winnerName").innerText

fetch("hapus_pemenang.php",{
method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},
body:"nama="+encodeURIComponent(nama)
})

document.getElementById("winnerModal").style.display="none"

namaElement.innerText="Tekan Untuk Memulai"

}

</script>

</body>
</html>