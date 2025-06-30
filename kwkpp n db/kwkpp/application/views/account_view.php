<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Penyata Akaun</title>
</head>
<style type="text/css">
p.leftmargin
{
margin-left: 10%
}

p.rightmargin
{
margin-right:10%
}


</style>

<body>

<blockquote>
	<table width="100%" border="0">
		<tr>
			<td height="10">
			<h2 align="right">PENYATA AKAUN</font></h3>
			</h2></td>
		</tr>
		<tr>
			<td height="5">
			<h4><font size="2">KUMPULAN WANG KHAIRAT PEKERJA DAN PESARA TNB
			</font><font size="-1">(8592-U)</h4></font></td>
		</tr>
		<tr>
			<td height="10"><font size="2">Tingkat 10, TNB Ibu Pejabat No 129, Jalan Bangsar</font></td>
		</tr>
		<tr>
			<td><span class="style29 style38"><font size="2">59200 Kuala Lumpur (Tel:03-229 66134 / 03-2296 6947) 
			</font></span></td>
		</tr>
		<tr>
			<td><hr>
			<table width="100%" border="0">
				<tr>
					<td width="103" height="10"><font size="2">NO. AHLI</font></td>
					<td width="2%">
					<div align="center">:</td>
					<td width="83%"><font size="2"><?php echo $noAhli ?></font></td>
				</tr>
				<tr>
					<td width="103" height="10"><font size="2">NO. PEKERJA</font></td>
					<td width="2%">
					<div align=center>:</div></td>
					<td width="83%"><font size="2"><?php echo $noPekerja ?></font></td>
				</tr>
				<tr>
					<td width="103"% height="10"><font size="2">NAMA</font></td>
					<td width="2%">
					<div align=center>:</div></td>
					<td width="83%"><font size="2"><?php echo $nama ?></font></td>
				</tr>
			</table></td>
		</tr>
		<tr>
			<td><hr>
			<h4 align=center>PENYATA AKAUN SETAKAT TAHUN <i> <?php echo $tahun ?></i></h4>
			</td>
		</tr>
		<tr>
			<td>
			<table width="495" border="0">
				<tr>
					<td width="16" bgcolor="#000000" align=center>
					<font color="#FFFFFF"><b>Bil</b></td>
					<td width="39" bgcolor="#000000" align=center>
					<font color="#FFFFFF"><b>Tarikh</b></td>
					<td width="134" bgcolor="#000000" align=center>
					<font color="#FFFFFF"><b>Perkara</b></td>
					<td width="28" bgcolor="#000000" align=center>
					<font color="#FFFFFF"><b>Ruj.</b></td>
					<td width="78" bgcolor="#000000" align=center>
					<font color="#FFFFFF"><b>Debit(RM)</b></td>
					<td width="88" bgcolor="#000000" align=center>
					<font color="#FFFFFF"><b>Kredit(RM)</b></td>
					<td width="74" bgcolor="#000000" align=center>
					<font color="#FFFFFF"><b>Baki(RM)</b></td>
				</tr>
        <?php echo $insert_row ?> 
        		<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>
					<div style="border-top-style: solid; border-top-width: 1px; padding-top: 1px" >
					</div></td>
				</tr>
				<tr>
					<td>
					<div align="center" class="style36 style28"><strong>
						</strong></div></td>
					<td>
					<div align="center" class="style39"></div></td>
					<td>
					<div align="center" class="style39"></div></td>
					<td>
					<div align="center" class="style39"></div></td>
					<td>
					<div align="center" class="style39"></div></td>
					<td>
					<div align="center" class="style39"></div></td>
					<td>
					<div align="right" class="style39"><b><font size="4"><i><?php echo $baki ?></i></font></b>&nbsp;&nbsp;&nbsp;&nbsp;</div>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>
					<div style="border-bottom-style: solid; border-bottom-width: 1px; padding-bottom: 1px">
					</div></td>
				</tr>
			</table></td>
		</tr>
		<tr>
			<td><font size="2">(SEMUA URUSNIAGA DALAM RINGGIT MALAYSIA)</font></font></td>
		</tr>
		<tr>
			<td>
			<table width="100%" border="0" id="table1">
				<tr>
					<td width="419">Baki dibawa kehadapan</td>
					<td>RM <?php echo $bakiBH ?></td>
				</tr>
				<tr>
					<td><b>(+)</b> Jumlah keseluruhan bantuan kematian</td>
					<td>RM <?php echo $mati ?></td>
				</tr>
				<tr>
					<td><b>(-)</b> Jumlah keseluruhan caruman bulanan</td>
					<td>RM <?php echo $carumanBulanan ?></td>
				</tr>
				<tr>
					<td>Baki setakat</td>
					<td>RM <?php echo $bal ?></td>
				</tr>
</td>
  </tr>
			</table></td>
		</tr>
		<tr>
			<td height="70">
			<table width="80%" border="0">
				<tr>
					<td width="100">&nbsp;</td>
					<td width="116">&nbsp;</td>
					<td width="99">&nbsp;</td>
					<td width="48">&nbsp;</td>
					<td width="106">&nbsp;</td>
					<td width="125">&nbsp;</td>
				</tr>
				<tr>
					<td width="100"></td>
					<td width="116"></td>
					<td width="100">
					<div align="center" class="style43">
						<div style="border-bottom-style: solid; border-bottom-width: 1px; padding-bottom: 1px">
						</div></div></td>
					<td width="48"><font size="1"></td>
					<td width="100">
					<div align="center" class="style43">
						<div style="border-bottom-style: solid; border-bottom-width: 1px; padding-bottom: 1px">
						</div></div></td>
					<td width="125"></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="style36">
					<div align="center"><font size="1">Disediakan oleh </font>
					</div></td>
					<td>&nbsp;</td>
					<td>
					<div align="center"><font size="1">Disahkan oleh </font>
					</div></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td height="10">&nbsp;</td>
					<td height="10">&nbsp;</td>
					<td>
					<div align="center"><font size="1">'Nama'</font></div></td>
					<td>&nbsp;</td>
					<td>
					<div align="center"><font size="1">'Nama'</font></div></td>
					<td height="10">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>
					<div align="center" class="style35 style36"><font size="1">'Jawatan'</font></div>
					</td>
					<td>&nbsp;</td>
					<td>
					<div align="center"><font size="1">'Jawatan'</font></div>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<table width="100%" border="0">
				<tr>
					<td><hr></td>
				</tr>
				<tr>
					<td><font size="1">*Penyata ini adalah benar setakat tarikh seperti di atas. Sila rujuk kepada pihak kami untuk sebarang pertanyaan</font></td>
				</tr>
			</table>
</blockquote>
</body>
</html>