<?php

require_once('basecontroller.php');

class Update_member_status extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Update_member_status()
	{

		parent::BaseController(); 
		$this->load->helper(array('form', 'url'));

	}



  ##### index #####
	function index()
	{	
		if(is_logged() && get_role()<='3')
		{
	
			//return "$edit->";
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
				$back_url = anchor('importcontri', 'Import dari fail lain');

				$a.="$back_url<p>Nama Fail: <strong>$filename</strong></p><table><tr><td class='tableheader'>No</td><td class='tableheader'>No Pekerja</td><td class='tableheader'>Nama</td><td class='tableheader'>Sumbangan</td><td class='tableheader'>Nota</td><td class='tableheader'>Remark</td></tr>";
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
				if(!isset($line[4]))
				$line[4]="&nbsp;";
				if(!isset($line[5]))
				$line[5]="&nbsp;";
				if(!isset($line[6]))
				$line[6]="&nbsp;";
				if(!isset($line[7]))
				$line[7]="&nbsp;";
				if(!isset($line[8]))
				$line[8]="&nbsp;";
				if(!isset($line[9]))
				$line[9]="&nbsp;";
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
		  $class = "even";
		  //insert data into db including ahli khairat ID
	   }
	}
	else
	{
	$staffno = "not exist";
	$khairatID = "bukan ahli";
	$class = "red";
	}
				//if(even($i))
				//{}
				$a.= "<tr class='$class'><td>".$line[0]."</td><td>".$line[1]."</td><td>".$line[2]."</td><td>".$line[7]."</td><td>".$line[9]."</td><td>".$khairatID."</td><tr>";
				
				//$data = array('name' => $line[0], 'email' => $line[1], 'url' => $line[2], );

				//$str = $this->db->insert_string('table_name', $data);	
				}
					//$userdata = array('a'=> $line[0].$line[1].$line[2]."<br>");
						//  $query = "INSERT INTO daftar SET a='$a', b='$b', c='$c', d='$d', e='$e', f='$f', g='$g', h='$h', i='$i', j='$j'";
						//  $result = mysql_query($query);
						/*  if(isset($result))
						   {echo "TRUE";}
						  else
						   {echo "FALSE";}	
					   */
					   }
				$a.="</table>$back_url";
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