<?php

require_once('basecontroller.php');

class Crudstafftype extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Crudstafftype()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='2')
	{
		    
    redirect("crudstafftype/filteredgrid");

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
    $filter = new DataFilter("Carian Jenis Pekerja");
    $filter->db->select("*");
    $filter->db->from("kwkpp_jenis_pekerja");
    //$filter->db->join("authors","authors.author_id=articles.author_id","LEFT");

    $filter->jenisAhliDesc = new inputField("Kod Jenis Pekerja", "jenisPekerjaCode");
	$filter->jenisBilDesc = new inputField("Keterangan Jenis Pekerja", "jenisPekerjaDesc");
	    
    $filter->buttons("reset","search");    
    $filter->build();
    

    $uri = "crudstafftype/dataedit/show/<#jenisPekerjaCode#>";
    
    //grid
    $grid = new DataGrid("Senarai Jenis Pekerja");
    //$grid->use_function("callback_test");
    $grid->order_by("jenisPekerjaCode","asc");
    $grid->per_page = 5;
    $grid->use_function("substr");

	$grid->column_detail("Kod Jenis Pekerja", "jenisPekerjaCode", $uri);
	$grid->column("Keterangan Jenis Pekerja", "jenisPekerjaDesc");
	//$grid->column("Amaun Bil", "amaunBil");

    $grid->add("crudstafftype/dataedit/create");
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

    $edit = new DataEdit("Jenis Pekerja", "kwkpp_jenis_pekerja");
    $edit->back_uri = "crudstafftype/filteredgrid";

    $edit->jenisBilCode = new inputField("Kod Jenis Pekerja", "jenisPekerjaCode");
    $edit->jenisBilCode->rule = "unique|trim|required|max_length[5]";
	if ($this->uri->segment(5)==="modify" || $this->uri->segment(3)==="modify" || $this->uri->segment(5)==="update" || $this->uri->segment(3)==="update")
	{
	$edit->jenisBilCode->mode = "readonly";
	}

    $edit->jenisBilDesc = new inputField("Keterangan Jenis Pekerja", "jenisPekerjaDesc");
    $edit->jenisBilDesc->rule = "required|trim|max_length[25]";

	//$edit->amaunBil = new inputField("Amaun Bil (MYR)", "amaunBil");
    //$edit->amaunBil->rule = "required|numeric|max_length[9]";
	//$edit->amaunBil->value = "0";
  
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
                     // array("file"=>THISFILE, "id"=>"commentsgrid", "title"=>"comments grid"),
                     // array("file"=>THISFILE, "id"=>"commentsedit", "title"=>"comments edit"),
                    )
                  );
  }
  else
  {redirect("auth/login");}

  }
  

}

?>