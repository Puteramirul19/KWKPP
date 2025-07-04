<?php

require_once('basecontroller.php');

class Crudmembers extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Crudmembers()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='2')
	{
		    
    redirect("rapyd/crudmembers/filteredgrid");

	}else{redirect("auth/login");}
  }



  ##### callback test (for DataFilter + DataGrid) #####
  function test($id,$const)
  {
    //callbacktest//
    return $id*$const;
    //endcallbacktest//
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
    $filter = new DataFilter("Cari Ahli");
    $filter->db->select("*");
    $filter->db->from("kwkpp_ahli_khairat");
    //$filter->db->join("authors","authors.author_id=articles.author_id","LEFT");

    $filter->noPekerja = new inputField("NO Pekerja", "noPekerja");
	$filter->noAhli = new inputField("No Ahli", "noAhli");
    $filter->noIC = new inputField("No IC", "noIC");
    //$filter->active->option("","");
    //$filter->active->options(array("y"=>"Yes","n"=>"No"));
    
    $filter->buttons("reset","search");    
    $filter->build();
    

    $uri = "crudmembers/dataedit/show/<#ahliKhairatID#>";
    
    //grid
    $grid = new DataGrid("Senarai Ahli");
    //$grid->use_function("callback_test");
    $grid->order_by("noAhli","asc");
    $grid->per_page = 5;
    $grid->use_function("substr");
    $grid->column_detail("noPekerja","noPekerja", $uri);
   // $grid->column_orderby("email","name","email");

	$grid->column("No Ahli","<#noAhli#>");
	$grid->column("No IC","<#noIC#>");
    $grid->column("Nama","<substr><#nama#>|0|10</substr>....");
    //$grid->column("email","<#email#> <#lastlogin#>");
	//$grid->column("active","<strtoupper><#active#></strtoupper>", "align=middle");
    //$grid->column("callback test","<callback_test><#user_name#>|3</callback_test>");
    
    $grid->add("crudmembers/dataedit/create");
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
  
  
  

  // comments datagrid 
  function comments_grid()
  {
	if(is_logged() && get_role()<='2')
	{

	//commentsgrid//
    $this->rapyd->load("datagrid");
    
    $art_id = intval($this->uri->segment(4));
    
    $grid = new DataGrid("Comments","comments");
    $grid->db->where("article_id", $art_id);

    $modify = site_url("crudmembers/comments_edit/$art_id/modify/<#comment_id#>");
    $delete = anchor("crudmembers/comments_edit/$art_id/do_delete/<#comment_id#>","delete");
    
    $grid->order_by("comment_id","desc");
    $grid->per_page = 6;
    $grid->column_detail("ID","comment_id", $modify);
    $grid->column("comment","<htmlspecialchars><substr><#comment#>|0|100</substr></htmlspecialchars>...");
    $grid->column("delete", $delete);
    $grid->add("crudmembers/comments_edit/$art_id/create");
    $grid->build();
    
    $head = $this->rapyd->get_head();    
    $this->loadiframe($grid->output, $head, "related");
    //endcommentsgrid//

	}else{redirect("auth/login");}
  }


  // comments dataedit 
  function comments_edit()
  {
	if(is_logged() && get_role()<='2')
	{
	  
    //commentsedit//
    $this->rapyd->load("dataedit");

    $art_id = intval($this->uri->segment(4));
    
    $edit = new DataEdit("Comment Detail", "comments");
    $edit->back_uri = "crudmembers/comments_grid/$art_id/list";

    $edit->aticle_id = new autoUpdateField("article_id",   $art_id);
    
    $edit->body = new textareaField("Comment", "comment");
    $edit->body->rule = "required";
    $edit->body->rows = 5;    
        
    $edit->back_save = true;
    $edit->back_cancel_save = true;
    $edit->back_cancel_delete = true;
    
    $edit->buttons("modify", "save", "undo", "delete", "back");
    $edit->build();
    
    $head = $this->rapyd->get_head();
    $this->loadiframe($edit->output, $head, "related");
    //endcommentsedit//

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

    $edit = new DataEdit("Senarai Ahli", "kwkpp_ahli_khairat");
    $edit->back_uri = "crudmembers/filteredgrid";

    $edit->noPekerja = new inputField("No Pekerja", "noPekerja");
    $edit->noPekerja->rule = "trim|required|max_length[8]";

	$edit->noAhli = new inputField("No Ahli", "noAhli");
    $edit->noAhli->rule = "trim|required|max_length[10]";
	//$edit->user_name->mode = "autohide";

	$edit->noIC = new inputField("No IC", "noIC");
    $edit->noIC->rule = "trim|required|max_length[14]";
    
	$edit->nama = new inputField("Nama", "nama");
    $edit->nama->rule = "trim|required|max_length[50]";
	
	$edit->statusAhli = new dropdownField("Status Ahli", "statusAhli");
    $edit->statusAhli->option("","");
    $edit->statusAhli->options(array("A"=>"Aktif","T"=>"Perkhidmatan Ditamatkan"));
	$edit->statusAhli->rule = "required";

	$edit->tarikhKeluar = new dateField("Tarikh Keluar", "tarikhKeluar","eu");

$edit->datefield = new dateField("Date", "datefield","eu");

   $edit->datetime = new dateField("Creation date", "created","d/m/Y");  
   $edit->datetime->insertValue = date("Y-m-d H:i:s");  
	/*$edit->role = new dropdownField("Role", "role_id");
    $edit->role->option("","");
    $edit->role->options("SELECT role_id, name FROM security_role");
	$edit->role->rule = "required";

	  

$this->ci =& get_instance();$this->ci->load->library('encrypt');
$this->encrypt->encode($this->input->post(�password�);

  	$edit->password = new passwordField("Password", "password");   
	$edit->password->encrypt = false;   
	$edit->password->rule = "min_length[4]";*/
	
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
  

}

?>