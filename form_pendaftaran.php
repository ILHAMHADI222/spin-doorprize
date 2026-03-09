<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pendaftaran Peserta Doorprize</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>

*{box-sizing:border-box;}

body{
font-family:'Poppins',sans-serif;
margin:0;
min-height:100vh;
background:linear-gradient(135deg,#ff9800,#ff6a00);
display:flex;
align-items:center;
justify-content:center;
padding:40px;
}

.box{
background:white;
padding:35px;
border-radius:18px;
max-width:900px;
width:100%;
box-shadow:0 15px 40px rgba(0,0,0,0.2);
}

h2{
text-align:center;
font-weight:800;
color:#ff6a00;
}

input,select{
width:100%;
padding:10px;
margin-bottom:10px;
border-radius:8px;
border:1px solid #ddd;
font-family:Poppins;
}

button{
padding:8px 12px;
background:#ff6a00;
color:white;
border:none;
border-radius:6px;
cursor:pointer;
}

button:hover{
opacity:0.9;
}

table{
width:100%;
margin-top:20px;
border-collapse:collapse;
}

th{
background:#ff9800;
color:white;
padding:10px;
}

td{
border:1px solid #eee;
padding:8px;
text-align:center;
}

tr:nth-child(even){
background:#fafafa;
}

.hapus{
background:#e53935;
}

.save{
background:#4CAF50;
}

</style>
</head>

<body>

<div class="box">

<h2>🎁 Pendaftaran Peserta Doorprize</h2>

<input id="nama" placeholder="Nama Peserta">

<select id="kategori">
<option value="pelanggan">Pelanggan</option>
<option value="calon">Calon Pelanggan</option>
</select>

<button onclick="tambah()">Tambah Peserta</button>

<h3>Daftar Peserta</h3>

<table>

<thead>
<tr>
<th>No</th>
<th>Nama</th>
<th>Kategori</th>
<th>Setting Juara</th>
<th>Simpan</th>
<th>Aksi</th>
</tr>
</thead>

<tbody id="list"></tbody>

</table>

</div>

<script>

function load(){

fetch("get_peserta.php")
.then(res=>res.json())
.then(data=>{

let html=""
let no=1

data.forEach(p=>{

html+=`
<tr>

<td>${no++}</td>

<td>${p.nama}</td>

<td>${p.kategori}</td>

<td>

<select id="juara_${p.id}">

<option value="">-</option>

<option value="receh" ${p.juara=='receh'?'selected':''}>Receh</option>

<option value="1" ${p.juara=='1'?'selected':''}>Juara 1</option>

<option value="2" ${p.juara=='2'?'selected':''}>Juara 2</option>

<option value="3" ${p.juara=='3'?'selected':''}>Juara 3</option>

<option value="4" ${p.juara=='4'?'selected':''}>Juara 4</option>

</select>

</td>

<td>

<button class="save" onclick="simpanJuara(${p.id})">Save</button>

</td>

<td>

<button class="hapus" onclick="hapus(${p.id})">Hapus</button>

</td>

</tr>
`

})

document.getElementById("list").innerHTML=html

})

}

function tambah(){

let nama=document.getElementById("nama").value
let kategori=document.getElementById("kategori").value

fetch("tambah_peserta.php",{

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:`nama=${encodeURIComponent(nama)}&kategori=${kategori}`

})
.then(()=>{

document.getElementById("nama").value=""
load()

})

}

function simpanJuara(id){

let juara=document.getElementById("juara_"+id).value

fetch("set_juara.php",{

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:`id=${id}&juara=${juara}`

})
.then(()=>{
alert("Juara berhasil disimpan")
})

}

function hapus(id){

if(confirm("Hapus peserta ini?")){

fetch("hapus_peserta.php?id="+id)
.then(()=>load())

}

}

load()

</script>

</body>
</html>