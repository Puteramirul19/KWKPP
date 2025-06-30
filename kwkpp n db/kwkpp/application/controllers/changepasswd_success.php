<?php
require_once('basecontroller.php');

class Changepasswd_success extends BaseController {


	function Changepasswd_success()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
  
    $this->_render("changepasswd_success", null, 
                    array(
                      array("file"=>VIEWPATH."changepasswd_success.php"),
                    )
                  );
                  
  }

}




 
?>