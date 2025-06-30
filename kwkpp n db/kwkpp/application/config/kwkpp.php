<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*SYSTEM SETTING
 */// var $yy_array = year_listing();
/*SYSTEM SETTING*/
$config['system_name']	= "KUMPULAN WANG KHAIRAT PEKERJA & PESARA TNB";
$config['system_version'] = "Sistem Maklumat KWKPPTNB v1.0";
/*$config['max_date_decease'] = 10;max date user can key in decease member. eg: if set 10; user only can key in decease member from 1st to 10th of the month only - CANCEL BY USER*/
/*status ahli*/
$config['active_status'] = "AK";
/*jenis ahli*/
$config['ahli_biasa_code'] = "AB";
$config['seumur_hidup_code'] = "SH";
//$config['decease_status'] = "MD"; /*this value cant be called in dataedit*/ 
$config['sumbangan_bil_code'] = "S1"; /*this value set in basecontroller*/
$config['pendaftaran_bil_code'] = "P1"; /*this value set in basecontroller*/
$config['tukar_penama_bil_code'] = "P2"; /*this value set in basecontroller*/

/*Jenis Pekerja*/
$config['pekerja_biasa'] = "PB";

/*status tuntutan*/
$config['belum_bayar_code'] = "BB"; 
$config['dalam_proses_code'] = "DP"; 
$config['sudah_bayar_code'] = "SB"; 

//$config['bulan'] =  array("01"=>"01","02"=>"Feb","03"=>"03","04"=>"04","05"=>"05","06"=>"06","07"=>"07","08"=>"08","09"=>"09","10"=>"10","11"=>"11","12"=>"12"); 
/*this value set in basecontroller
$curr_year = date("Y");
$curr_year_minus5 = $curr_year-5;
$curr_year_plus5 = $curr_year+5;
for($y=$curr_year_minus5;$y<=$curr_year_plus5;$y++)
{$yyyy_array[$y] = $y;}

$config['tahun'] = $yyyy_array;*/

?>