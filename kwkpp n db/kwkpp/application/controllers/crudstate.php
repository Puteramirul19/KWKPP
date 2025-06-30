<?php

require_once('basecontroller.php');

class Crudstate extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Crudstate()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='2')
	{
		    
    redirect("crudstate/filteredgrid");

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
    $filter = new DataFilter("Carian Kod Negeri");
    $filter->db->select("*");
    $filter->db->from("kwkpp_negeri");
    //$filter->db->join("authors","authors.author_id=articles.author_id","LEFT");

    $filter->negeriDesc = new inputField("Negeri", "negeriDesc");
	    
    $filter->buttons("reset","search");    
    $filter->build();
    

    $uri = "crudstate/dataedit/show/<#negeriCode#>";
    
    //grid
    $grid = new DataGrid("Senarai Negeri");
    $grid->use_function("callback_test");
    $grid->order_by("negeriDesc","asc");
    $grid->per_page = 25;
    $grid->use_function("substr");

	$grid->column("Kod Negeri", "negeriCode");
	$grid->column_detail("Negeri", "negeriDesc", $uri);
    
    $grid->add("crudstate/dataedit/create");
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

    $edit = new DataEdit("Negeri", "kwkpp_negeri");
    $edit->back_uri = "crudstate/filteredgrid";

	$edit->negeriCode = new inputField("Kod Negeri", "negeriCode");
    $edit->negeriCode->rule = "trim|required|max_length[3]";
	if ($this->uri->segment(5)==="modify" || $this->uri->segment(3)==="modify" || $this->uri->segment(5)==="update" || $this->uri->segment(3)==="update")
	{
	$edit->negeriCode->mode = "readonly";
	}

    $edit->negeriDesc = new inputField("Negeri", "negeriDesc");
    $edit->negeriDesc->rule = "trim|required|max_length[25]";
    
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