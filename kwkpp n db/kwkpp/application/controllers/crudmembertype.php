<?php

require_once('basecontroller.php');

class Crudmembertype extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Crudmembertype()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='2')
	{
		    
    redirect("crudmembertype/filteredgrid");

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
    $filter = new DataFilter("Carian Jenis Ahli");
    $filter->db->select("*");
    $filter->db->from("kwkpp_jenis_ahli");
    //$filter->db->join("authors","authors.author_id=articles.author_id","LEFT");

    $filter->jenisAhliDesc = new inputField("Jenis Ahli", "jenisAhliDesc");
	    
    $filter->buttons("reset","search");    
    $filter->build();
    

    $uri = "crudmembertype/dataedit/show/<#jenisAhliID#>";
    
    //grid
    $grid = new DataGrid("Senarai Jenis Ahli");
    $grid->use_function("callback_test");
    $grid->order_by("jenisAhliID","asc");
    $grid->per_page = 5;
    $grid->use_function("substr");

	$grid->column_detail("Kod Jenis Ahli", "jenisAhliCode", $uri);
	$grid->column("Jenis Ahli", "jenisAhliDesc");
	$grid->column("Amaun Yuran", "amaunYuran");
	$grid->column("Amaun Tuntutan Pendahuluan", "amaunTuntutanPendahuluan");
	$grid->column("Amaun Tuntutan", "amaunTuntutan");

    $grid->add("crudmembertype/dataedit/create");
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

    $edit = new DataEdit("Jenis Ahli", "kwkpp_jenis_ahli");
    $edit->back_uri = "crudmembertype/filteredgrid";

    $edit->jenisAhliCode = new inputField("Kod Jenis Ahli", "jenisAhliCode");
    $edit->jenisAhliCode->rule = "trim|required|max_length[3]";
	if ($this->uri->segment(5)==="modify" || $this->uri->segment(3)==="modify" || $this->uri->segment(5)==="update" || $this->uri->segment(3)==="update")
	{
	$edit->jenisAhliCode->mode = "readonly";
	}

    $edit->jenisAhliDesc = new inputField("Jenis Ahli", "jenisAhliDesc");
    $edit->jenisAhliDesc->rule = "trim|required|max_length[25]";

	$edit->amaunYuran = new inputField("Amaun Yuran(MYR)", "amaunYuran");
    $edit->amaunYuran->rule = "required|numeric|max_length[9]";
	$edit->amaunYuran->value = "0";

	$edit->amaunTuntutanPendahuluan = new inputField("Amaun Tuntutan Pendahuluan(MYR)", "amaunTuntutanPendahuluan");
    $edit->amaunTuntutanPendahuluan->rule = "required|numeric|max_length[9]";
	$edit->amaunTuntutanPendahuluan->value = "0";

	$edit->amaunTuntutan = new inputField("Amaun Tuntutan (MYR)", "amaunTuntutan");
    $edit->amaunTuntutan->rule = "required|numeric|max_length[9]";
	$edit->amaunTuntutan->value = "0";
  
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