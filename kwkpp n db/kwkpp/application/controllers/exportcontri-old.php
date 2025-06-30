<?php

require_once('basecontroller.php');

class Exportcontri extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Exportcontri()
	{

		parent::BaseController(); 
		$this->load->helper(array('form'));//, 'url'

	}



  ##### index #####
	function index()
	{	
		if(is_logged() && get_role()<='3')
		{
		$this->load->model('Ahli_khairat_model');


		$this->db->select('*')->from('kwkpp_sumbangan')->orderby("bulan", "desc");
		$dbres = $this->db->get();
		$ddmenu = array();
		foreach ($dbres->result_array() as $tablerow) {
		  $ddmenu[$tablerow['bulan']] = $tablerow['bulan'];
		}
		$data['bulan'] = $ddmenu;

		$this->db->select('*')->from('kwkpp_sumbangan')->orderby("tahun", "desc");
		$dbres = $this->db->get();
		$ddmenu = array();
		foreach ($dbres->result_array() as $tablerow) {
		  $ddmenu[$tablerow['tahun']] = $tablerow['tahun'];
		}
		$data['tahun'] = $ddmenu;

		$data['ahliKhairatID'] = $this->Ahli_khairat_model->all_ahli_khairat_dropdown();

		$this->_render('exportcontri_view', $data);
		
		}else{redirect("auth/login");}
	}

  function mysql_excel()
  {
	if(is_logged() && get_role()<='3')
	{
    
	//database

	if($_POST['ahliKhairatID']=='0')
	$ahliKhairatID="";
	else
	$ahliKhairatID="and s.ahliKhairatID='".$_POST['ahliKhairatID']."'";
	$result=mysql_query("select ak.noPekerja, ak.nama, s.tahun, s.bulan, s.amaun from kwkpp_sumbangan s	left join kwkpp_ahli_khairat ak on (ak.ahliKhairatID=s.ahliKhairatID) where s.tahun='$_POST[tahun]' and s.bulan='$_POST[bulan]' and s.jenisBilCode='".$this->config->item('sumbangan_bil_code')."' $ahliKhairatID order by ak.nama, ak.noPekerja, s.tahun, s.bulan, s.amaun");


// Get data records from table. order by id asc


// Functions for export to excel.
function xlsBOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}
function xlsEOF() {
echo pack("ss", 0x0A, 0x00);
return;
}
function xlsWriteNumber($Row, $Col, $Value) {
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}
function xlsWriteLabel($Row, $Col, $Value ) {
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;
}
$date = date("d-m-Y");
$filename = "SUMBANGAN_SETAKAT_" . $date . ".xls ";
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-Disposition: attachment;filename=$filename");
header("Content-Transfer-Encoding: binary ");

xlsBOF();

/*
Make a top line on your excel sheet at line 1 (starting at 0).
The first number is the row number and the second number is the column, both are start at '0'
*/

xlsWriteLabel(0,0,"SUMBANGAN AHLI KWKPP BAGI BULAN $_POST[bulan] TAHUN $_POST[tahun] SETAKAT $date");

// Make column labels. (at line 3)
xlsWriteLabel(2,0,"No Pekerja");
xlsWriteLabel(2,1,"Nama");
xlsWriteLabel(2,2,"Tahun");
xlsWriteLabel(2,3,"Bulan");
xlsWriteLabel(2,4,"Amaun");

$xlsRow = 3;
$total_amaun=0;
// Put data records from mysql by while loop.
while($row=mysql_fetch_array($result)){

xlsWriteNumber($xlsRow,0,$row['noPekerja']);
xlsWriteLabel($xlsRow,1,$row['nama']);
xlsWriteLabel($xlsRow,2,$row['tahun']);
xlsWriteLabel($xlsRow,3,$row['bulan']);
xlsWriteLabel($xlsRow,4,$row['amaun']);

$total_amaun+=$row['amaun'];
$xlsRow++;
}

if($xlsRow>3)
{
xlsWriteLabel($xlsRow,3,"Jumlah");
xlsWriteLabel($xlsRow,4,$total_amaun);
}
xlsEOF();
exit();
unlink($filename);

	}else{redirect("auth/login");}
  }

}

?>