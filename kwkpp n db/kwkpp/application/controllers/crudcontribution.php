<?php

require_once('basecontroller.php');

class Crudcontribution extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Crudcontribution()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='2')
	{
		    
    redirect("crudcontribution/filteredgrid");

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
    $filter = new DataFilter("Carian Sumbangan Ahli");
    $filter->db->select("*");
    $filter->db->from("kwkpp_sumbangan");
    $filter->db->join("kwkpp_ahli_khairat","kwkpp_ahli_khairat.ahliKhairatID=kwkpp_sumbangan.ahliKhairatID","LEFT");

	$filter->noPekerja = new inputField("No Pekerja", "noPekerja");

	$filter->bulan = new dropdownField("Bulan", "bulan");
    $filter->bulan->option("","");  $filter->bulan->options(array("01"=>"01","02"=>"02","03"=>"03","04"=>"04","05"=>"05","06"=>"06","07"=>"07","08"=>"08","09"=>"09","10"=>"10","11"=>"11","12"=>"12"));
	$filter->bulan->insertValue = date('m');
	
	$curr_year=date("Y");
	$curr_year_minus5 = $curr_year-5;
	$curr_year_plus5 = $curr_year+5;
	for($y=$curr_year_minus5;$y<=$curr_year_plus5;$y++)
	{$ddmenu[$y] = $y;}

	$filter->tahun = new dropdownField("Tahun", "tahun");
    $filter->tahun->option("","");  
	$filter->tahun->options($ddmenu);
	$filter->tahun->insertValue = date('Y');

	//$filter->tahun = new inputField("Tahun", "tahun");
	    
    $filter->buttons("reset","search");    
    $filter->build();
    

    $uri = "crudcontribution/dataedit/show/<#sumbanganID#>";
    
    //grid
    $grid = new DataGrid("Senarai Sumbangan");
    //$grid->use_function("callback_test");
    $grid->order_by("tahun,bulan","desc");
    $grid->per_page = 10;
    $grid->use_function("substr");

	$grid->column("Tahun", "tahun");
	$grid->column("Bulan", "bulan");
	$grid->column_detail("No Pekerja", "noPekerja", $uri);
	$grid->column("Nama", "nama");
	$grid->column("Amaun", "amaun");
	
    $grid->add("crudcontribution/dataedit/create");
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

    $edit = new DataEdit("Sumbangan Ahli", "kwkpp_sumbangan");
    $edit->back_uri = "crudcontribution/filteredgrid";

	$edit->ahliKhairatID = new dropdownField("Nama Ahli (No Pekerja)", "ahliKhairatID");
    $edit->ahliKhairatID->option("","");
	$edit->ahliKhairatID->options("SELECT ahliKhairatID, concat(nama, ' (', noPekerja, ')') FROM kwkpp_ahli_khairat");
	$edit->ahliKhairatID->rule = "required";

/*	if($this->uri->segment(5)==="show" OR $this->uri->segment(5)==="modify" OR $this->uri->segment(5)==="update" OR $this->uri->segment(3)==="show" OR $this->uri->segment(3)==="modify" OR $this->uri->segment(3)==="update")
	{
    $edit->ahliKhairatID->options("SELECT ahliKhairatID, concat(nama, ' (', noPekerja, ')') FROM kwkpp_ahli_khairat");
	}
	elseif($this->uri->segment(5)==="create" OR $this->uri->segment(5)==="insert" OR $this->uri->segment(3)==="create" OR $this->uri->segment(3)==="insert")
	{
	$edit->ahliKhairatID->options("SELECT ak.ahliKhairatID, concat(ak.nama, ' (', ak.noPekerja, ')') FROM kwkpp_ahli_khairat ak where ak.ahliKhairatID not in (select k.ahliKhairatID from kwkpp_kematian k)");
	}
	$edit->ahliKhairatID->rule = "required";
*/
	$curr_year=date("Y");
	$curr_year_minus5 = $curr_year-5;
	$curr_year_plus5 = $curr_year+5;
	for($y=$curr_year_minus5;$y<=$curr_year_plus5;$y++)
	{$ddmenu[$y] = $y;}

/*	
	$edit->tahun = new dropdownField("Tahun", "tahun");
    $edit->tahun->option("","");  
	$edit->tahun->options($ddmenu);
	$edit->tahun->insertValue = "tahun";
    $edit->tahun->rule = "trim|required|numeric|min_length[4]|max_length[4]";
*/

	$edit->tahun = new inputField("Tahun", "tahun");
    $edit->tahun->rule = "trim|required|numeric|min_length[4]|max_length[4]";

	$edit->bulan = new dropdownField("Bulan", "bulan");
    $edit->bulan->option("","");  $edit->bulan->options(array("01"=>"01","02"=>"02","03"=>"03","04"=>"04","05"=>"05","06"=>"06","07"=>"07","08"=>"08","09"=>"09","10"=>"10","11"=>"11","12"=>"12"));
	$edit->bulan->insertValue = "bulan";
	$edit->bulan->rule = "trim|required|numeric|min_length[2]|max_length[2]";

	$edit->amaun = new inputField("Amaun", "amaun");
    $edit->amaun->rule = "trim|required|numeric|max_length[9]";

	$edit->jenisBayaranID = new dropdownField("Cara Pembayaran", "jenisBayaranCode");
    $edit->jenisBayaranID->option("","");
	$edit->jenisBayaranID->options("SELECT jenisBayaranCode, jenisBayaranDesc FROM kwkpp_mod_bayaran order by jenisBayaranCode");

	$edit->bankID = new dropdownField("Bank", "bankID");
    $edit->bankID->option("","");
	$edit->bankID->options("SELECT bankID, bankDesc FROM kwkpp_bank order by bankDesc");

	$edit->noRuj = new inputField("Nombor Rujukan", "noRuj");
    $edit->noRuj->rule = "trim|max_length[20]";

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