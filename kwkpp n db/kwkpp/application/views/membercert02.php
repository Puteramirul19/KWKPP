<html>
<head>
<title>Cetak Sijil Keahlian</title>

<style type="text/css">

body {
 background-color: #fff;
 margin: 40px;
 font-family: Arial, Lucida Grande, Verdana, Sans-serif;
 font-size: 12px;
 color: #4F5155;
}

a {
 color: #003399;
 background-color: transparent;
 font-weight: normal;
}

h1 {
 color: #444;
 background-color: transparent;
 border-bottom: 1px solid #D0D0D0;
 font-size: 16px;
 font-weight: bold;
 margin: 24px 0 2px 0;
 padding: 5px 0 6px 0;
}

code {
 font-family: Arial, Monaco, Verdana, Sans-serif;
 font-size: 12px;
 background-color: #f9f9f9;
 border: 1px solid #D0D0D0;
 color: #002166;
 display: block;
 margin: 14px 0 14px 0;
 padding: 12px 10px 12px 10px;
}

</style>
</head>
<body>

<div align="center"><!-- <h2>&nbsp;</h2> -->
	<div style="position: absolute; width: 763px; height: 76px; z-index: 3; left: 95px; top: 74px" id="layer5">
		<font size="5"><strong>Kumpulan Wang Khairat Pekerja dan Pesara TNB</strong></font><br>
		<!-- <img src="<?php echo base_url()?>application/images/logo/logotnb.png" align="absmiddle"><br> -->
		<font size="1">(8592-U)</font><br><br><br><br><p><i><u><font size="4">Sijil Keahlian</font></u></i></p></div>
</div>

<p align="center">&nbsp;</p>

<div style="position: absolute; width: 762px; height: 100px; z-index: 2; left: 95px; top: 230px" id="layer4">
<font size="3"><p style="line-height: 150%" align="justify">Adalah disahkan bahawa<br><strong><?php echo $nama ?></strong> (Nombor Pekerja: <strong><?php echo $noPekerja ?></strong> dan Nombor K/P: <strong><?php echo $noIC ?></strong>)<br>telah diterima menjadi seorang <strong><?php echo $jenisAhliDesc ?></strong> Kumpulan Wang Khairat Pekerja dan Pesara TNB<br>berkuatkuasa mulai <strong><?php echo $tarikhMasuk ?></strong> dengan Nombor Ahli: <strong><?php echo $jenisAhliCode.$ahliKhairatID ?></strong>.</p></font>
</div>

<div style="position: absolute; width: 155px; height: 100px; z-index: 1; left: 95px; top: 370px" id="layer1">
	<font size="2"><p align="center">........................<br>Tarikh</p></font>
</div>

<div style="position: absolute; width: 155px; height: 100px; z-index: 1; left: 692px; top: 370px" id="layer3">
	<font size="2"><p align="center">........................<br>Setiausaha</p></font>
</div>

<div style="position: absolute; width: 155px; height: 100px; z-index: 1; left: 497px; top: 370px" id="layer2">
	<font size="2"><p align="center">........................<br>Pengerusi</p></font>
</div>

</body>
</html>