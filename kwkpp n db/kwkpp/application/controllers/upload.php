<?php

class Upload extends Controller {
	
	function Upload()
	{
		parent::Controller();
		$this->load->helper(array('form', 'url'));
	}
	
	function index()
	{	
		$this->load->view('upload_form', array('error' => ' ' ));
	}

	function do_upload()
	{
		$config['upload_path'] = 'uploads/';
		$config['allowed_types'] = 'gif|jpg|png|csv';
		//$config['max_size']	= '100';
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			
			$this->load->view('upload_form', $error);
		}	
		else
		{
$data = array('upload_data' => $this->upload->data());
$file = $config['upload_path'].$this->upload->file_name;

   $fh = fopen($file, "rt");
   $userdata = array('a'=> fread($fh, filesize($file)));
   fclose($fh);
   unlink($file);
   $this->load->view('upload_success', $userdata);
		}
	}
	
	function do_read()
	{

//$file = $this->Upload->do_upload->config['upload_path'].$this->upload->file_name;
$file = $this->upload->file_name;
/*   $fh = fopen($file, "rt");

   $userdata = array('a'=> fread($fh, filesize($file)));
   fclose($fh);*/
unlink($file);
	$this->load->view('upload_success');//, $userdata);
	
	}
}
?>