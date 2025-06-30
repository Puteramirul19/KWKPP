<?php

require_once('basecontroller.php');

class Exportregfee extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Exportregfee()
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
	
		$this->_render('exportregfee_view', $data);
		
		}else{redirect("auth/login");}
	}

  function mysql_excel()
  {error_reporting(0);
	if(is_logged() && get_role()<='3')
	{
    
	//database

	if($_POST['ahliKhairatID']=='0')
	$ahliKhairatID="";
	else
	$ahliKhairatID="and s.ahliKhairatID='".$_POST['ahliKhairatID']."'";
	$result=mysql_query("select ak.noPekerja, ak.nama, s.tahun, s.bulan, concat(s.amaun, ' ') as 'amaun' from kwkpp_sumbangan s	left join kwkpp_ahli_khairat ak on (ak.ahliKhairatID=s.ahliKhairatID) where s.tahun='$_POST[tahun]' and s.bulan='$_POST[bulan]' and s.jenisBilCode='".$this->config->item('pendaftaran_bil_code')."' $ahliKhairatID order by ak.nama, ak.noPekerja, s.tahun, s.bulan, s.amaun");


// Get data records from table. order by id asc


$date = date("d-m-Y");
$filename = "PENDAFTARAN_SETAKAT_" . $date . ".xls ";

Header("Content-Disposition: attachment; filename=$filename"); //excel filename
header("Content-Type: application/vnd.ms-excel"); //change to .ms-excel
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
/********
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$filename");
*********/

echo "PENDAFTARAN AHLI KWKPP BAGI BULAN ".$_POST[bulan]." TAHUN ".$_POST[tahun]." SETAKAT ".$date."\n"; 
echo "\n";
echo "No Pekerja"."\t"."Nama"."\t"."Tahun"."\t"."Bulan"."\t"."Amaun"."\n";
while($row = mysql_fetch_assoc($result))
{$a=$row[amaun];//

$z = explode('.',number_format($row[amaun],2));
$data =  "$z[0]"."."."$z[1]";//number_format($a,3,'.','');implode('.',$z);//

$b=substr($a,0,-1);//number_format($a,2);//

//$fmt = numfmt_create( 'de_DE', NumberFormatter::DECIMAL );
//$data =  $row[amaun];//numfmt_format($fmt, $row[amaun]);

echo $row[noPekerja]."\t".$row[nama]."\t".$row[tahun]."\t".$row[bulan]."\t".$row[amaun]."\n";
}
?>

<?php

/*********************/

	}else{redirect("auth/login");}
  }


  function mysql_excel2()
  {error_reporting(0);
	if(is_logged() && get_role()<='3')
	{
//convert to excel
Header("Content-Disposition: attachment; filename=export_excel.xls"); //excel filename
header("Content-Type: application/vnd.ms-excel"); //change to .ms-excel
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//mysql_connect("localhost","root",""); 
//mysql_select_db("ltsdb"); 

	if($_POST['ahliKhairatID']=='0')
	$ahliKhairatID="";
	else
	$ahliKhairatID="and s.ahliKhairatID='".$_POST['ahliKhairatID']."'";
	$user_query=mysql_query("select ak.noPekerja as 'noPekerja', ak.nama, s.tahun, s.bulan, s.amaun from kwkpp_sumbangan s	left join kwkpp_ahli_khairat ak on (ak.ahliKhairatID=s.ahliKhairatID) where s.tahun='$_POST[tahun]' and s.bulan='$_POST[bulan]' and s.jenisBilCode='".$this->config->item('pendaftaran_bil_code')."' $ahliKhairatID order by ak.nama, ak.noPekerja, s.tahun, s.bulan, s.amaun");

//$user_query = mysql_query('select StaffName, StaffTel, StaffAddress from staff');

?> 
<html>
<head>
<title>Excel Spreadsheet</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<table width="300" border="1" bordercolor="#0073E6" cellspacing="0" cellpadding="0">

<tr align="center">
    <td bgcolor="#82B7EC" colspan="5">
      <font face="Arial, Helvetica, sans-serif"; size= "2"; ><strong>PENDAFTARAN AHLI KWKPP BAGI BULAN <?php echo $_POST[bulan] ?> TAHUN <?php echo $_POST[tahun] ?> SETAKAT <?php echo $date ?>
        </strong> </font> <? "\t"?>   
    </td>
</tr>	

  <tr align="center">
    <td bgcolor="#82B7EC">
      <font face="Arial, Helvetica, sans-serif"; size= "2"; ><strong>Staff Name
        </strong> </font> <? "\t"?>   
    </td>
	<td bgcolor="#82B7EC">
      <font face="Arial, Helvetica, sans-serif"; size= "2"; ><strong>Phone No.
        </strong> </font> <? "\t"?>    
    </td>
	<td bgcolor="#82B7EC">
      <font face="Arial, Helvetica, sans-serif"; size= "2"; ><strong>Address
        </strong> </font>    
    </td>
  </tr>

<?php
while($row = mysql_fetch_array($user_query))
{

?>
<tr> 
    <td width="100" align="left">
      <font face="Arial, Helvetica, sans-serif"; size= "2";  color="#000000"><? echo $row[noPekerja];"\t"?></font>
    </td> 
	<td width="100" align="left">
      <font face="Arial, Helvetica, sans-serif"; size= "2";  color="#000000"><? echo $row[nama];"\t"?></font>
    </td>
	<td width="100" align="left">
      <font face="Arial, Helvetica, sans-serif"; size= "2";  color="#000000"><? echo $row[noPekerja];?></font>
    </td>
</tr>	
<?php
}
?>
</table>
</body>
</html>
<?php
	}else{redirect("auth/login");}
  }

}



?>