<?php

require_once('basecontroller.php');

class importcontri extends BaseController {

  var $data_type = null;   
  var $data = null;


	function importcontri()
	{

		parent::BaseController(); 
		$this->load->helper(array('form', 'url'));

	}



  ##### index #####
	function index()
	{	
		if(is_logged() && get_role()<='3')
		{
	
			$this->_render('upload_form', $data=array('error' => ' ' ));
		
		}else{redirect("auth/login");}
	}

	function do_read()
	{
		if(is_logged() && get_role()<='3')
		{

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
		$this->load->library('upload', $config);

			if (!$this->upload->do_upload())
			{
				$error = array('error' => $this->upload->display_errors());
				$this->_render('upload_form', $error);
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
				$back_url = "&nbsp;".anchor('importcontri', '<< Import dari fail lain');

				$a.="$back_url<p>&nbsp;Nama Fail: <strong>$filename</strong></p><table><tr><td class='tableheader'>No</td><td class='tableheader'>No Pekerja</td><td class='tableheader'>Nama</td><td class='tableheader'>Tahun</td><td class='tableheader'>Bulan</td><td class='tableheader'>Sumbangan</td><td class='tableheader'>Nota</td><td class='tableheader'>Status</td></tr>";
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
					{$line[3]="&nbsp;";
					//$line3="";
					}
				//if(!isset($line[4]))
				//$line[4]="&nbsp;";
				if(!isset($line[5]))
				$line[5]="&nbsp;";
				if(!isset($line[6]))
				$line[6]="&nbsp;";
				//if(!isset($line[7]))
				//$line[7]="&nbsp;";
				
				/*To get rid the php notice that occured for column that have null value and replace with html space(&nbsp;)*/
				if($i<>1)
				{
					if($line[1]=="&nbsp;")
					{$staffno="";}
					else
					{$staffno=$line[1];}
	$query = $this->db->query("select * from kwkpp_ahli_khairat where noPekerja='$staffno'");

	if ($query->num_rows() > 0)
	{
		foreach ($query->result() as $row)
		{
		  $staffno = $row->noPekerja;
		  $khairatID = $row->ahliKhairatID;
		  $remark = "Ahli";
		  $class = "even";
		}
	}
	else
	{
	$staffno = "not exist";
	$khairatID = "bukan ahli";
	$remark = "Bukan Ahli";
	$class = "red";
	}
				//if(even($i))
				//{}

				$line3 = explode('-', $line[3]);
				if(!isset($line3[0]))
				$line3[0]="&nbsp;";
				if(!isset($line3[1]))
				$line3[1]="&nbsp;";
//insert data into db including ahli khairat ID
if($remark == "Ahli")
{
				$query_billID = $this->db->query("select billingID from kwkpp_billing where ahliKhairatID='".$khairatID."' and tahun='".$line3[0]."' and bulan='".$line3[1]."' and jenisBilCode='".$this->sumbangan_bil_code."'");
				if ($query_billID->num_rows() > 0)
				{
					foreach ($query_billID->result() as $row)
					{
					  $billID = $row->billingID;
					}
				}
				else{$billID="0";}
	
					
				
				$query = "INSERT INTO kwkpp_sumbangan SET ahliKhairatID='$khairatID', billingID='".$billID."',  jenisBilCode='".$this->sumbangan_bil_code."', tahun='$line3[0]', bulan='$line3[1]', amaun='$line[5]', jenisBayaranCode='6'";
				$result = mysql_query($query);

/*update table kwkpp_sumbangan with current billing if exist*/	
				if(!$result)
				{$trans="Tiada";}
				else
				{
				
				$query_update = "UPDATE kwkpp_sumbangan set billingID='".$billID."' where ahliKhairatID='".$khairatID."' and jenisBilCode='".$this->sumbangan_bil_code."' and tahun='".$line3[0]."' and bulan='".$line3[1]."'";
				$result_update = mysql_query($query_update);
				}

}				
				$a.= "<tr class='$class'><td>".$line[0]."</td><td>".$line[1]."</td><td>".$line[2]."</td><td>".$line3[0]."</td><td>".$line3[1]."</td><td>".$line[5]."</td><td>".$line[6]."</td><td>".$remark."</td></tr>";
				
				}

		}
				$a.="</table><br>$back_url";
				fclose($fh);
				unlink($file);

				$userdata = array('a'=> $a);

				$this->_render('upload_success', $userdata);

			}
			else
			{	$file = $config['upload_path'].$this->upload->file_name;
				unlink($file);
				$error = array('error' => "<p class='alert'>Maaf, fail yang dipilih adalah bukan fail dengan 'extension' csv (Comma Delimited Files)</p>");
				$this->_render('upload_form', $error);
			}
		}else{redirect("auth/login");}
	}

}

?>