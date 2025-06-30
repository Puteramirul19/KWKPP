<?php

require_once('basecontroller.php');

class Createmonthlycontri extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Createmonthlycontri()
	{

		parent::BaseController(); 
		$this->load->helper(array('form','url'));

	}



  ##### index #####
	function index()
	{	
		if(is_logged() && get_role()<='3')
		{
		$data['bulan'] = $this->bulan;

		$curr_year = date("Y");
		$curr_year_minus5 = $curr_year-5;
		$curr_year_plus5 = $curr_year+5;
		for($y=$curr_year_minus5;$y<=$curr_year_plus5;$y++)
		{$yyyy_array[$y] = $y;}

		$data['tahun'] = $yyyy_array;

		$this->_render('createmonthlycontri_view', $data);
		
		}else{redirect("auth/login");}
	}

  function insert_contri()
  {
	if(is_logged() && get_role()<='3' && $_POST['bulan']!='' && $_POST['tahun']!='')
	{
	
	$insertdata['back'] = "&nbsp;".anchor('createmonthlycontri', '<< Kembali');
    $insertdata['bulan'] = $_POST['bulan'];
	$insertdata['tahun'] = $_POST['tahun'];

	$query = $this->db->query("select count(*)  as 'death' from kwkpp_kematian where tarikhMati like '".$_POST['tahun']."-".$_POST['bulan']."%'");
	if ($query->num_rows() > 0)
	{
		foreach ($query->result() as $row)
		{
		  $insertdata['no_decease'] = $row->death;
		}
	}

	if($_POST['bulan']=='01')
	{
		$insertdata['prev_mm'] = '12';
		$insertdata['prev_yy'] = $_POST['tahun'] - 1;
	}
	else
	{
	$insertdata['prev_mm'] = $_POST['bulan'] - 1;
	$insertdata['prev_yy'] = $_POST['tahun'];
		if($insertdata['prev_mm']<'10')
		{$insertdata['prev_mm'] = '0'.$insertdata['prev_mm'];}
	}

		/*DELETE existing monthly death contribution for selected month n year*/
		$query_delete = "DELETE from kwkpp_billing where jenisBilCode='".$this->sumbangan_bil_code."' and tahun='".$_POST['tahun']."' and bulan='".$_POST['bulan']."'";
		$result_delete = mysql_query($query_delete);

$insertdata['insert_row'] = "<table><tr><td class='tableheader'>No</td><td class='tableheader'>No Pekerja</td><td class='tableheader'>Nama</td><td class='tableheader'>Amaun</td><td class='tableheader'>Baki Bawa Hadapan</td><td class='tableheader'>Transaksi</td></tr>";
$i=0;
$total_amaun_bulanan = 0;
$total_bakiBawaHadapan_bulanan = 0;
$query_ahliID = $this->db->query("select * from kwkpp_ahli_khairat ak where ak.statusAhli='".$this->config->item('active_status')."'");
if ($query_ahliID->num_rows() > 0)
{
	foreach ($query_ahliID->result() as $row_ahliID)
	{

	
	$query = $this->db->query("select * from kwkpp_billing b where b.ahliKhairatID='".$row_ahliID->ahliKhairatID."' and b.bulan='".$insertdata['prev_mm']."' and b.tahun='".$insertdata['prev_yy']."' and b.jenisBilCode='".$this->sumbangan_bil_code."'");
	
	$bakiBawaHadapan_bulanan=0;
		if ($query->num_rows() > 0)
		{   

			foreach ($query->result() as $row)
			{ 
				$i++;

				$total_amaun=$insertdata['no_decease']+$row->bakiBawaHadapan;
				if($total_amaun>5)
				{
				$amaun_bulanan="5.00";
				$bakiBawaHadapan_bulanan=($total_amaun-5).".00";
				}
				else
				{
				$amaun_bulanan=$total_amaun.".00";
				$bakiBawaHadapan_bulanan="0.00";
				}

				$total_amaun_bulanan += $amaun_bulanan; 
				$total_bakiBawaHadapan_bulanan += $bakiBawaHadapan_bulanan;

			}

		}
		else
		{	
			$i++;
			$total_amaun=$insertdata['no_decease'];
			if($total_amaun>5)
			{
			$amaun_bulanan="5.00";
			$bakiBawaHadapan_bulanan=($total_amaun-5).".00";
			}
			else
			{
			$amaun_bulanan=$total_amaun.".00";
			$bakiBawaHadapan_bulanan="0.00";
			}

			$total_amaun_bulanan += $amaun_bulanan;
			$total_bakiBawaHadapan_bulanan += $bakiBawaHadapan_bulanan;


		}			

		/*
		insert statement
		but if inserted
		update statement	
		*/

		/*insert new monthly death contribution*/
		$query_insert = "INSERT INTO kwkpp_billing SET ahliKhairatID='".$row_ahliID->ahliKhairatID."', jenisBilCode='".$this->sumbangan_bil_code."', tahun='".$_POST['tahun']."', bulan='".$_POST['bulan']."', mati='".$insertdata['no_decease']."', amaun_bil='".$amaun_bulanan."', bakiBawaHadapan='".$bakiBawaHadapan_bulanan."'";
		$result_insert = mysql_query($query_insert);
		if(!$result_insert)
		{$trans="Tiada";}
		else
		{
		$trans=$this->db->insert_id();//"Selesai";
		
		/*update table kwkpp_sumbangan with current billing if exist*/	
		$query_update = "UPDATE kwkpp_sumbangan set billingID='".$trans."' where ahliKhairatID='".$row_ahliID->ahliKhairatID."' and jenisBilCode='".$this->sumbangan_bil_code."' and tahun='".$_POST['tahun']."' and bulan='".$_POST['bulan']."'";
		$result_update = mysql_query($query_update);
		}

		$insertdata['insert_row'] .= "<tr><td>".$i."</td><td>".$row_ahliID->ahliKhairatID."</td><td>".$row_ahliID->nama."</td><td>".$amaun_bulanan."</td><td>".$bakiBawaHadapan_bulanan."</td><td>".$trans."</td></tr>";

	}
}
else
{
$insertdata['insert_row'] .= "<tr><td colspan=\"6\">Tiada Ahli yang Aktif</td></tr>";
}
	$total_amaun_bulanan .= ".00"; 
	$total_bakiBawaHadapan_bulanan .= ".00"; 

	$insertdata['insert_row'] .= "<tr><td colspan=\"3\" align=\"right\">Jumlah</td><td>".$total_amaun_bulanan."</td><td>".$total_bakiBawaHadapan_bulanan."</td></tr>";
	$insertdata['insert_row'] .= "</table>";
	
	$this->_render('insert_contri_view', $insertdata);
	}else{redirect("auth/login");}
  
  }


}

?>