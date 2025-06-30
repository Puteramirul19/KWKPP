<?php
require_once('basecontroller.php');

class Crudaccount extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Crudaccount()
	{

		parent::BaseController(); 
		$this->load->helper(array('form','url'));

	}



  ##### index #####
	function index()
	{	
		if(is_logged() && get_role()<='3')
		{
		ini_set('max_execution_time', 300);
		$this->load->model('ahli_khairat_model');
		$this->load->model ('DateTime_model');
		//$this->load->view('account_view');


		$data['tahun'] = $this->DateTime_model->year5_yyyy();

/*
$this->config->item('tahun');

		$this->db->select('*')->from('kwkpp_ahli_khairat')->orderby("nama", "asc");
		$dbres = $this->db->get();
		$ddmenu = array( '0'  => '',);
		foreach ($dbres->result_array() as $tablerow) {
		  $ddmenu[$tablerow['ahliKhairatID']] = $tablerow['nama']." (".$tablerow['noPekerja'].")";
		}
		$data['ahliKhairatID'] = $ddmenu;
*/
			$data['ahliKhairatID'] = $this->ahli_khairat_model->all_ahli_khairat_dropdown_without_all_members(); 
				//$this->ahli_khairat_model->all_ahli_khairat_dropdown_without_all_members();
		

		//$data['ahliKhairatID'] = array('1'=>'abc(123)');
		if(is_numeric($this->uri->segment(3)))
			{$data['selected_ahliKhairatID']=$this->uri->segment(3);}
		else
			{$data['selected_ahliKhairatID']="";}

		$this->_render('crudaccount_view', $data);
		
		}else{redirect("auth/login");}
	}

  function account()
  {
	if(is_logged() && get_role()<='3')
	{
	
	$result=mysql_query("SELECT nama,noPekerja,noAhliAB,noAhliSH FROM kwkpp_ahli_khairat where ahliKhairatID=$_POST[ahliKhairatID]");

while($row=mysql_fetch_array($result))
	{	
		$insertdata['nama']=$row['nama'];
		$insertdata['noPekerja']=$row['noPekerja'];
			
	
	if($row["noAhliAB"]<>'')
		{
		$insertdata["AB"]= "AB".$row["noAhliAB"];
		$insertdata['noAhli']="AB".$row['noAhliAB'];

		}
		else
			{
			$insertdata["AB"]="<i>[ TIADA ]</i>";
			}

	if($row["noAhliSH"]<>'')
		{
		$insertdata["SH"]= "SH".$row["noAhliSH"];
		$insertdata['noAhli']="SH".$row['noAhliSH'];

		}
	else
			{
			$insertdata["SH"]="<i>[ TIADA ]</i>";
			}

		$insertdata['tahun'] = $_POST['tahun'];	
	}
			
	$query = mysql_query("select concat(bulan,'/',tahun) as 'bulan_tahun' ,bulan,tahun ,jenisBilDesc, '-' as 'noRuj','debit', amaun_bil from kwkpp_billing left join kwkpp_jenis_bil on (kwkpp_jenis_bil.jenisBilCode=kwkpp_billing.jenisBilCode)where ahliKhairatID='$_POST[ahliKhairatID]' and tahun='$_POST[tahun]' UNION ALL select concat(bulan,'/',tahun) as 'bulan_tahun', bulan,tahun, jenisBilDesc,noRuj as 'noRuj', 'kredit', amaun from kwkpp_sumbangan left join kwkpp_jenis_bil on (kwkpp_jenis_bil.jenisBilCode=kwkpp_sumbangan.jenisBilCode)where ahliKhairatID='$_POST[ahliKhairatID]' and tahun='$_POST[tahun]' order by tahun, bulan");
	
$bil=0;
$insertdata['insert_row']='';
$insertdata['baki']='';


while($row=mysql_fetch_array($query))
{
		$bil++;			
		if ($row['debit']=='debit')	
		{
			$value='0';
			if ($bil==1)

			{
				$total=$row['amaun_bil'];
				$credit = $total;
				if($credit<0)
				{
					$credit = $credit*(-1);
					$credit = "(".$credit.")";

					$insertdata['baki']=$credit;
				}
			}
			else
			{
				$total=$total + $row['amaun_bil'];
				$credit = $total;
				if($credit<0)
				{
					$credit = $credit*(-1);
					$credit = "(".$credit.")";

					$insertdata['baki']= $credit;
				}
				else
				{
					$insertdata['baki']=$credit;
				}
			}
		
			$insertdata['insert_row'] .="<tr><td class=\"littletablerow\" colspan=\"\">".$bil."</td><td>".$row['bulan_tahun']."</td><td>".$row['jenisBilDesc']."</td><td>".$row['noRuj']."</td><td align=right>".$row['amaun_bil']."&nbsp;&nbsp;&nbsp;</td><td align=right>".$value."&nbsp;&nbsp;&nbsp;</td><td align=right>".$credit."&nbsp;&nbsp;&nbsp;</td></tr>";
		}
		
		else
		{
			$value='0';
			if ($bil==1)
			{
				$total=-$row['amaun_bil'];
				$credit = $total;

				if($credit<0)
				{
					$credit = $credit*(-1);
					$credit = "(".$credit.")";

					$insertdata['baki']=$credit;
				}
				else
				{
					$insertdata['baki']=$credit;
				}
			}

			else
			{
				$total=$total - $row['amaun_bil'];
				$credit = $total;

				if($credit<0)
				{
					$credit = $credit*(-1);
					$credit = "(".$credit.")";

					$insertdata['baki']= $credit;
				}
				else
				{
					$insertdata['baki']=$credit;
				}
			}
				
			$insertdata['insert_row'] .="<tr><td class=\"littletablerow\" colspan=\"\">".$bil."</td><td>".$row['bulan_tahun']."</td><td>".$row['jenisBilDesc']."</td><td>".$row['noRuj']."</td><td align=right>".$value."&nbsp;&nbsp;&nbsp;</td><td align=right>".$row['amaun_bil']."&nbsp;&nbsp;&nbsp;</td><td align=right>".$credit."&nbsp;&nbsp;&nbsp;</td></tr>";
		}
			
}

//no1 - baki(min)
$query = mysql_query("select * from kwkpp_billing where ahliKhairatID='$_POST[ahliKhairatID]' and tahun='$_POST[tahun]' and jenisBilCode='S1' and bulan=(select min(bulan) from kwkpp_billing where ahliKhairatID='$_POST[ahliKhairatID]' and tahun='$_POST[tahun]' and jenisBilCode='S1')");

$baki_bh = mysql_fetch_array($query);
	if ($baki_bh=='0')
		{
			$insertdata['bakiBH'] ='0';
		}		
	else
		{
			$insertdata['bakiBH'] = $baki_bh['bakiBawaHadapan'];
		}


//no2 - bantuan
$query = mysql_query("select sum(amaun_bil) from kwkpp_billing where ahliKhairatID = '$_POST[ahliKhairatID]' and tahun ='$_POST[tahun]' and jenisBilCode='S1'");

$bantuan = mysql_fetch_array($query);
   if ($bantuan=='0')
		{
			$insertdata['mati'] ='0';
		}		
	else
		{
			$insertdata['mati'] = $bantuan['sum(amaun_bil)'];
		}



//no3 - caruman
$query = mysql_query("select sum(amaun) from kwkpp_sumbangan where ahliKhairatID = '$_POST[ahliKhairatID]' and tahun ='$_POST[tahun]' and jenisBilCode='S1'");

$caruman = mysql_fetch_array($query);
   if ($caruman=='0')
		{
			$insertdata['carumanBulanan'] ='0';
		}		
	else
		{
			$insertdata['carumanBulanan'] = $caruman['sum(amaun)'];
		}


//no4 - baki (max)
$query = mysql_query("select * from kwkpp_billing where ahliKhairatID = '$_POST[ahliKhairatID]' and tahun='$_POST[tahun]' and jenisBilCode='S1' and bulan=(select max(bulan) from kwkpp_billing where ahliKhairatID = '$_POST[ahliKhairatID]' and tahun='$_POST[tahun]' and jenisBilCode='S1')");

$carryfwd = mysql_fetch_array($query);
   if ($carryfwd=='0')
		{
			$insertdata['bal'] ='0';
		}		
	else
		{
			$insertdata['bal'] = $carryfwd['bakiBawaHadapan'];
		}


	$insertdata['counter'] = $bil;
	//$this->_render('account_view', $insertdata);

	//$this->load->plugin('to_pdf');
	//$html = $this->load->view('account_view', $insertdata, true);
	//pdf_create($html, $insertdata['nama']."(".$insertdata['noAhli'].")", 'a4', 'portrait');
	
	$this->load->view('account_view_html', $insertdata);
	}
	else{redirect("auth/login");
	}
  
  mysql_connection_close(); 
}

}


?>