<?php

require_once('basecontroller.php');

class importbeneficiariesfee extends BaseController {

  var $data_type = null;   
  var $data = null;


	function importbeneficiariesfee()
	{

		parent::BaseController(); 
		$this->load->helper(array('form', 'url'));

	}



  ##### index #####
	function index()
	{	
		if(is_logged() && get_role()<='3')
		{
	
			$this->_render('upload_form2', $data=array('error' => ' ' ));
		
		}else{redirect("auth/login");}
	}

	function do_read()
	{	
		
		if(is_logged() && get_role()<='3')
		{
		ini_set("memory_limit","128M");
		$config['upload_path'] = 'uploads/';
		$config['allowed_types'] = 'csv';
		$config['max_size']	= '5000';
		//if(isset($f_name))
		//$config['file_name'] = null;
		//if($f_name!=0)
		//$this->upload->do_upload->field = 'userfile';
		//$config['field'] 
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';
		$a = null;
		$staffno = null;
		//$status_ahli = null;
		$this->load->library('upload', $config);

			if (!$this->upload->do_upload())
			{
				$error = array('error' => $this->upload->display_errors());
				$this->_render('upload_form2', $error);
			}	
			elseif(strtolower($this->upload->file_ext) == '.csv')
			{	
				$i=0;
				$data = array('upload_data' => $this->upload->data());
				$file = $config['upload_path'].$this->upload->file_name;
				if(!isset($f_name))
				$filename = $this->upload->file_name;
				else
				{
				$filename = $f_name;
				$file_path=str_replace(":", "/", $file_path);
				$file = $file_path;
				}

				$fh = fopen($file, "rt");
				$back_url = "&nbsp;".anchor('importbeneficiariesfee', '<< Import dari fail lain');

				$a.="$back_url<p>&nbsp;Nama Fail: <strong>$filename</strong></p><table><tr><td class='tableheader'>No</td><td class='tableheader'>No Pekerja</td><td class='tableheader'>Nama</td><td class='tableheader'>Tahun</td><td class='tableheader'>Bulan</td><td class='tableheader'>Penama</td><td class='tableheader'>Nota</td><td class='tableheader'>Status Ahli</td></tr>";
				while ($line = fgetcsv($fh,  ",")) { 
				$i++;
				/*To get rid the php notice that occured for column that have null value and replace with html space(&nbsp;)*/
				if(!isset($line[0]))
				$line[0]="&nbsp;";
				if(!isset($line[1]))
				$line[1]="&nbsp;";
				if(!isset($line[2]))
				$line[2]="&nbsp;";
				if(!isset($line[3]))
				$line[3]="&nbsp;";
				if(!isset($line[5]))
				$line[5]="&nbsp;";
				if(!isset($line[6]))
				$line[6]="&nbsp;";
				
				/*To get rid the php notice that occured for column that have null value and replace with html space(&nbsp;)*/
	if($i<>1)
	{
		if($line[1]=="&nbsp;")
		{$staffno="";}
		else
		{$staffno=$line[1];}

		if($line[6]=="&nbsp;" || $line[6]=="")
		{$line[6]="-";}
	//	else
	//	{$staffno=$line[6];}

	$query = $this->db->query("select * from kwkpp_ahli_khairat where noPekerja='$staffno'");

	if ($query->num_rows() > 0)
	{
		foreach ($query->result() as $row)
		{
		  $staffno = $row->noPekerja;
		  $khairatID = $row->ahliKhairatID;
		  $status_ahli = $row->statusAhli;
		  $remark = "Ahli";
		  $font_color = "";

		}
	}
	else
	{
	$staffno = "Tidak Wujud";
	$khairatID = "0";
	$status_ahli = "Bukan Ahli";
	$remark = "Bukan Ahli";
	$font_color = "#DD0000";//"#FF0000";
	}

				$line3 = explode('-', $line[3]);
				if(!isset($line3[0]))
				$line3[0]="&nbsp;";
				if(!isset($line3[1]))
				$line3[1]="&nbsp;";

/*check valid year
As a four-digit number in the range 1901  to 2155.*/
			if($line3[0]<'1901' && $line3[0]>'2155')
			{$remark = "Bukan Ahli";}
/*check valid month */
			if($line3[1]!='01' || $line3[1]!='02' || $line3[1]!='03' || $line3[1]!='04' || $line3[1]!='05' || $line3[1]!='06' || $line3[1]!='07' || $line3[1]!='08' || $line3[1]!='09' || $line3[1]!='10' || $line3[1]!='11' || $line3[1]!='12')
			{$remark = "Bukan Ahli";}
//insert data into db including ahli khairat ID
if($remark == "Ahli")
{
				$query_billID = $this->db->query("select billingID from kwkpp_billing where ahliKhairatID='".$khairatID."' and tahun='".$line3[0]."' and bulan='".$line3[1]."' and jenisBilCode='".$this->tukar_penama_bil_code."'");
				if ($query_billID->num_rows() > 0)
				{
					foreach ($query_billID->result() as $row)
					{
					  $billID = $row->billingID;
					}
				}
				else{$billID="0";}
				
				$query = "INSERT INTO kwkpp_sumbangan SET ahliKhairatID='$khairatID', billingID='".$billID."',  jenisBilCode='".$this->tukar_penama_bil_code."', tahun='$line3[0]', bulan='$line3[1]', amaun='$line[5]', jenisBayaranCode='6'";
				$result = mysql_query($query);

/*update table kwkpp_sumbangan with current billing if exist*/	//
				$trans="!";
				if($result)
				{$trans="Tiada kemaskini";}
				else
				{$trans="Kemaskini";
				
				$query_update = "UPDATE kwkpp_sumbangan set billingID='".$billID."', amaun='".$line[5]."' where ahliKhairatID='".$khairatID."' and jenisBilCode='".$this->tukar_penama_bil_code."' and tahun='".$line3[0]."' and bulan='".$line3[1]."'";
				$result_update = mysql_query($query_update);
				}

}				
				if($i % 2)
				{ $class="odd";}
				else
				{ $class="even";}

				$a.= "<tr class='$class'><td class=\"littletablerow\">".$line[0]."</td><td class=\"littletablerow\">".$line[1]."</td><td class=\"littletablerow\">".$line[2]."</td><td class=\"littletablerow\">".$line3[0]."</td><td class=\"littletablerow\">".$line3[1]."</td><td class=\"littletablerow\">".$line[5]."</td><td class=\"littletablerow\">".$line[6]."</td><td class=\"littletablerow\"><font color=\"$font_color\">".$status_ahli."</font></td></tr>";
				
				}

		}
				$a.="</table><br>$back_url";
				fclose($fh);
				unlink($file);

				$userdata = array('a'=> $a);

				$this->_render('upload_success2', $userdata);

			}
			else
			{	$file = $config['upload_path'].$this->upload->file_name;
				unlink($file);
				$error = array('error' => "<p class='alert'>Maaf, fail yang dipilih adalah bukan fail dengan 'extension' csv (Comma Delimited Files)</p>");
				$this->_render('upload_form2', $error);
			}
		}else{redirect("auth/login");}
	}

}

?>