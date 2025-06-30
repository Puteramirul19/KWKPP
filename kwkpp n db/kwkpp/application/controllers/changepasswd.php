<?php

require_once('basecontroller.php');

class Changepasswd extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Changepasswd()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged())
	{
		    
    //redirect("crudusers/filteredgrid");
	redirect("changepasswd/dataedit_passwd/modify/".get_user_id());

	}else{redirect("auth/login");}
  }


  ##### dataedit #####
  function dataedit_passwd()
  {  
	//$show=get_user_id();
	//$this->rapyd->uri->$show=get_user_id();
	if(is_logged())// && $this->rapyd->show==get_user_id())//=='<#user_id#>')
	{
 
    if (($this->uri->segment(5)==="1") && ($this->uri->segment(4)==="do_delete")){
      show_error("Please do not delete the first record, it's required by DataObject sample");
    }
  
    //dataedit//
    $this->rapyd->load("dataedit_passwd");


    $edit = new Dataedit_Passwd("Tukar Kata Laluan", "users");
    $edit->back_uri = "changepasswd";//"crudusers/filteredgrid";

    $edit->name = new inputField("Nama Pengguna", "user_name");
    $edit->name->mode = "readonly";
/*
	$edit->email = new inputField("Email", "email");
    $edit->email->rule = "trim|required|max_length[150]";
    
	$edit->role = new dropdownField("Role", "role_id");
    $edit->role->option("","");
    $edit->role->options("SELECT role_id, name FROM security_role");
	$edit->role->rule = "required";

	$edit->active = new dropdownField("Active", "active");
    $edit->active->option("","");
    $edit->active->options(array("y"=>"Yes","n"=>"No"));  
*/

//$this->ci =& get_instance();$this->ci->load->library('encrypt');
//$this->encrypt->encode($this->input->post(’password’);

  	$edit->password = new passwordField("Kata Laluan", "password");   
	$edit->password->encrypt = false;   
	$edit->password->rule = "required|min_length[4]|matches[passwordconf]";

	$edit->passwordconf = new passwordField("Pengesahan Kata Laluan", "passwordconf");   
	$edit->passwordconf->encrypt = false;   
	$edit->passwordconf->rule = "required";
	
/**/	
	//$password_hash = md5($password.'1@3$5^7*9)0(8&6%4#2!');
    //$this->db->where("password", $password_hash);
	 
/*  $edit->body = new editorField("Body", "body");
    $edit->body->rule = "required";
    $edit->body->rows = 10;    

    $edit->author = new dropdownField("Author", "author_id");
    $edit->author->option("","");
    $edit->author->options("SELECT author_id, firstname FROM authors");

    $r_uri = "crudusers/comments_grid/<#article_id#>/list";
    $edit->related = new iframeField("related", $r_uri, "210");
    $edit->related->when = array("show","modify");

    $edit->checkbox = new checkboxField("Public", "public", "y","n");
    
    $edit->datefield = new dateField("Date", "datefield","eu"); */
    
    if ($this->uri->segment(4)==="1"){
      $edit->buttons("modify", "save"); //, "undo", "back"
    } else {
      $edit->buttons("modify", "save"); //, "undo", "delete", "back"
    }

    //$edit->use_function("callback_test");
    //$edit->test = new freeField("Test", "test", "<callback_test><#user_id#>|3</callback_test>");
    
    
    $edit->build();
    $data["edit"] = $edit->output;
     
    //enddataedit//


   /**/ $this->_render("dataedit", $data, 
                    array(
                      array("file"=>THISFILE, "id"=>"dataedit_passwd", "title"=>"dataedit passwd"),
                      //array("file"=>THISFILE, "id"=>"commentsgrid", "title"=>"comments grid"),
                      //array("file"=>THISFILE, "id"=>"commentsedit", "title"=>"comments edit"),
                    )
                  );
  }
  else
  {
	  
	redirect("auth/login");}

  }
  

}

?>