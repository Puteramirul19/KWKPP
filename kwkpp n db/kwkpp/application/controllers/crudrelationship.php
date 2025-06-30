<?php

require_once('basecontroller.php');

class Crudrelationship extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Crudrelationship()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='2')
	{
		    
    redirect("crudrelationship/filteredgrid");

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
    $filter = new DataFilter("Carian Hubungan");
    $filter->db->select("*");
    $filter->db->from("kwkpp_hubungan");
    //$filter->db->join("authors","authors.author_id=articles.author_id","LEFT");

    $filter->hubunganDesc = new inputField("Hubungan", "hubunganDesc");
	    
    $filter->buttons("reset","search");    
    $filter->build();
    

    $uri = "crudrelationship/dataedit/show/<#hubunganID#>";
    
    //grid
    $grid = new DataGrid("Senarai Hubungan");
    $grid->use_function("callback_test");
    $grid->order_by("hubunganID","asc");
    $grid->per_page = 25;
    $grid->use_function("substr");

	$grid->column_detail("Kod Hubungan", "hubunganID", $uri);
	$grid->column_detail("Hubungan", "hubunganDesc", $uri);
    
    $grid->add("crudrelationship/dataedit/create");
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

    $modify = site_url("crudusers/comments_edit/$art_id/modify/<#comment_id#>");
    $delete = anchor("crudusers/comments_edit/$art_id/do_delete/<#comment_id#>","delete");
    
    $grid->order_by("comment_id","desc");
    $grid->per_page = 6;
    $grid->column_detail("ID","comment_id", $modify);
    $grid->column("comment","<htmlspecialchars><substr><#comment#>|0|100</substr></htmlspecialchars>...");
    $grid->column("delete", $delete);
    $grid->add("crudusers/comments_edit/$art_id/create");
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
    $edit->back_uri = "crudusers/comments_grid/$art_id/list";

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

    $edit = new DataEdit("Hubungan", "kwkpp_hubungan");
    $edit->back_uri = "crudrelationship/filteredgrid";

	$edit->hubunganID = new inputField("Kod Hubungan", "hubunganID");
    $edit->hubunganID->rule = "trim|required|max_length[11]";
	if ($this->uri->segment(5)==="modify" || $this->uri->segment(3)==="modify" || $this->uri->segment(5)==="update" || $this->uri->segment(3)==="update")
	{
	$edit->hubunganID->mode = "readonly";
	}
	
    $edit->hubunganDesc = new inputField("Hubungan", "hubunganDesc");
    $edit->hubunganDesc->rule = "trim|required|max_length[25]";
    
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