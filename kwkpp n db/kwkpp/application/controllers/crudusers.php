<?php

require_once('basecontroller.php');

class Crudusers extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Crudusers()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='2')
	{
		    
    redirect("crudusers/filteredgrid");

	}else{redirect("auth/login");}
  }




  ##### DataFilter + DataGrid #####
  function filteredgrid()
  {
	if(is_logged() && get_role()<='2')
	{
        
    //filteredgrid//
    
    $this->rapyd->load("datafilter","datagrid");
    
    $this->rapyd->uri->keep_persistence();
    
    //filter
    $filter = new DataFilter("Carian Pengguna");
    $filter->db->select("*");
    $filter->db->from("users");
    //$filter->db->join("authors","authors.author_id=articles.author_id","LEFT");

    $filter->user_name = new inputField("Nama Pengguna", "user_name");
	$filter->email = new inputField("e-mail", "email");
    $filter->active = new dropdownField("Aktif", "active");
    $filter->active->option("","");
    $filter->active->options(array("y"=>"Ya","n"=>"Tidak"));
    
    $filter->buttons("reset","search");    
    $filter->build();
    

    $uri = "crudusers/dataedit/show/<#user_id#>";
    $uri_changepasswd = "crudusers/change_passwd/modify/<#user_id#>";
    //grid
    $grid = new DataGrid("Senarai Pengguna");
    $grid->use_function("callback_test");
    $grid->order_by("user_name","asc");
    $grid->per_page = 25;
    $grid->use_function("substr");
    
	//$grid->column_detail("Username(Name)","<#user_name#>(<substr><#name#>|0|10</substr>....)", $uri);
	$grid->column_detail("Nama Pengguna", "user_name", $uri);
    //$grid->column_orderby("email","name","email");<substr><#name#>|0|25</substr>
//['Tukar Kata Laluan Pengguna', base_url_index+'crudusers/change_passwd/modify/1', null],
    /******************	commented because of AD
	$grid->column_detail("Nama <font class=\"alert\">(klik untuk tukar Kata Laluan Pengguna)</font>", "name", $uri_changepasswd);
    */
	$grid->column("Nama", "name");

	$grid->column("e-mail","<#email#>");
	$grid->column("Aktif","<strtoupper><#active#></strtoupper>", "align=middle");
    //$grid->column("callback test","<callback_test><#user_name#>|3</callback_test>");
    
    $grid->add("crudusers/dataedit/create");
    $grid->build();
    /**/
    $data["crud"] = $filter->output . $grid->output;
    
    //endfilteredgrid//
    
    
    
    $this->_render("crud", $data, 
                    array(
                      array("file"=>THISFILE, "id"=>"filteredgrid", "title"=>"Filtered Grid"),
                     // array("file"=>THISFILE, "id"=>"callbacktest", "title"=>"Callback Test"),
                    )
                  );
	}else{redirect("auth/login");}
  }
  
  
  ##### dataedit #####
  function dataedit()
  {  
	if(is_logged() && get_role()<='2')
	{
 
    if (($this->uri->segment(5)==="1") && ($this->uri->segment(4)==="do_delete")){
      show_error("Please do not delete the first record, it's required by DataObject sample");
    }
  
    //dataedit//
    $this->rapyd->load("dataedit");

    $edit = new DataEdit("Pengguna", "users");
    $edit->back_uri = "crudusers/filteredgrid";

	$edit->user_name = new inputField("Nama Pengguna", "user_name");
	//$edit->user_name->rule = "trim|required|max_length[50]";
    $edit->user_name->mode = "autohide";

    $edit->name = new inputField("Nama", "name");
    $edit->name->rule = "trim|required|max_length[50]";

	$edit->email = new inputField("e-mail", "email");
    $edit->email->rule = "trim|required|max_length[150]";
    
	$edit->role = new dropdownField("Tahap", "role_id");
    $edit->role->option("","");
    $edit->role->options("SELECT role_id, name FROM security_role where name not in ('root', 'guest')");
	$edit->role->rule = "required";

	$edit->active = new dropdownField("Aktif", "active");
    $edit->active->option("","");
    $edit->active->options(array("y"=>"Ya","n"=>"Tidak"));
	$edit->active->rule = "required";

    if ($this->uri->segment(4)==="1"){
      $edit->buttons("modify", "save", "undo", "back");
    } else {
      $edit->buttons("modify", "save", "undo", "delete", "back");
    }

    //$edit->use_function("callback_test");
    //$edit->test = new freeField("Test", "test", "<callback_test><#user_id#>|3</callback_test>");
    
    
    $edit->build();
    $data["edit"] = $edit->output;
     
    //enddataedit//


    $this->_render("dataedit", $data, 
                    array(
                      array("file"=>THISFILE, "id"=>"dataedit", "title"=>"dataedit"),
                      array("file"=>THISFILE, "id"=>"commentsgrid", "title"=>"comments grid"),
                      array("file"=>THISFILE, "id"=>"commentsedit", "title"=>"comments edit"),
                    )
                  );
  }
  else
  {redirect("auth/login");}

  }
  
  function change_passwd()
  {
	if(is_logged() && get_role()<='2')
	{
    if (($this->uri->segment(5)==="1") && ($this->uri->segment(4)==="do_delete")){
      show_error("Please do not delete the first record, it's required by DataObject sample");
    }
  
    //dataedit//
    $this->rapyd->load("dataedit");

    $edit = new DataEdit("Tukar Kata Laluan", "users");
    $edit->back_uri = "crudusers";

	$edit->user_name = new inputField("Nama Pengguna", "user_name");
	//$edit->user_name->rule = "trim|required|max_length[50]";
    $edit->user_name->mode = "autohide";
	
	$edit->password = new passwordField("Kata Laluan", "password");   
	$edit->password->encrypt = false;
	$edit->password->rule = "required|min_length[4]|matches[passwordconf]";

	$edit->passwordconf = new passwordField("Pengesahan Kata Laluan", "passwordconf");   
	$edit->passwordconf->encrypt = false;
	$edit->passwordconf->rule = "required";

    if ($this->uri->segment(4)==="1"){
      $edit->buttons("modify", "save", "undo", "back");
    } else {
      $edit->buttons("modify", "save", "undo", "back");
    }

    //$edit->use_function("callback_test");
    //$edit->test = new freeField("Test", "test", "<callback_test><#user_id#>|3</callback_test>");
    
    
    $edit->build();
    $data["edit"] = $edit->output;
     
    //enddataedit//


    $this->_render("dataedit", $data, 
                    array(
                      array("file"=>THISFILE, "id"=>"dataedit", "title"=>"dataedit"),
                      array("file"=>THISFILE, "id"=>"commentsgrid", "title"=>"comments grid"),
                      array("file"=>THISFILE, "id"=>"commentsedit", "title"=>"comments edit"),
                    )
                  );
	}else{redirect("auth/login");}
  }
}

?>