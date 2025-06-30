<?php
require_once('basecontroller.php');

class Login extends BaseController {

	function Login()
	{
		parent::BaseController(); 	
	}
	
	function index()
	{
		$this->load->library('rapyd');
		$this->_render("auth", null, 
				array(
				  array("file"=>VIEWPATH."auth.php"),
				)
			  );
	}
}

?>