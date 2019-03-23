<?php 
date_default_timezone_set('Asia/Jakarta');
function konversiNilai($nilai){
        if($nilai>85)return 'A';
        else if($nilai>80)return 'AB';
        else if($nilai>70)return 'B';
        else if($nilai>65)return 'BC';
        else if($nilai>60)return 'C';
        else if($nilai>55)return 'CD';
        else if($nilai>50)return 'D';
        else return 'E';
}
function month($m){
	switch ($m) {
		case 1:
			return 'Januari';
			break;
		case 2:
			return 'Februari';
			break;
		case 3:
			return 'Maret';
			break;
		case 4:
			return 'April';
			break;
		case 5:
			return 'Mei';
			break;
		case 6:
			return 'Juni';
			break;
		case 7:
			return 'Juli';
			break;
		case 8:
			return 'Agustus';
			break;
		case 9:
			return 'September';
			break;
		case 10:
			return 'Oktober';
			break;
		case 11:
			return 'November';
			break;
		default:
			return 'Desember';
			break;
	}
}

?>
<html>
<head>
<style type="text/css">
#judul{
	font-weight: bold;
	text-align: center;
}
#identitas{
	font-weight: bold;
}
table, td, th {
  border: 1px solid black;
  padding: 5px;
}

table {
  border-collapse: collapse;
  width: 100%;
}
#ttd{
	text-align: right;

}
.a{
	padding-top:5px;
}
</style>
</head>
<body>
<div id="judul">
	PENILAIAN MAGANG<br>
	TAHUN {{date('Y')}}
</div><br>
<div id="identitas">
Nama : {{$pengembangan->users->name}}<br>
<div class='a'>Asal : {{$pengembangan->asal}}</div>
</div><br>
<table>
	<tr>
		<th rowspan="2">No.</th>
		<th rowspan="2"><center>Kriteria Penilaian</center></th>
		<th colspan="2"><center>Nilai</center></th>
	</tr>
	<tr>
		<td>Angka</td><td>Huruf</td>
	</tr>

	<?php
		$total = 0;
		$jumlah_data = 0;
	?>

	@foreach($penilaian as $aspek)
	<tr><td></td><th><center>{{$aspek->name}}</center></th><td></td><td></td></tr>
		@foreach($aspek->sub_aspek_nilai as $key=>$sub_aspek_nilai)
			<tr><td>{{$key+1}}</td><td>{{$sub_aspek_nilai['name']}}</td><td>{{$sub_aspek_nilai['nilai']}}</td><td>{{$sub_aspek_nilai['nilai_huruf']}}</td>
				@php ($total+=$sub_aspek_nilai['nilai'])
				@php ($jumlah_data++)
		@endforeach
	@endforeach
	<tr>
		<th colspan="2"><center>Jumlah Nilai</center></th><td>{{$total}}</td><td></td>
	</tr>
	<tr>
		<?php 
		$rata2 = round($total/$jumlah_data,2);
		?>
		<th colspan="2"><center>Rata-rata</center></th><td>{{$rata2}}</td><td>{{konversiNilai($rata2)}}</td>
	</tr>
</table><br>
<div id="ttd">
Semarang, {{date('d').' '.month(date('m')).' '.date('Y')}}<br>
<div class="a">Koordinator Pembimbing,</div><br><br><br><br>

{{$pengembangan->konstruktor ? $pengembangan->konstruktor->user->name:'-'}}
</div>
</body>

</html>