<?php

require_once('basecontroller.php');

class Crudmemberbeneficiarie extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Crudmemberbeneficiarie()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='3')
	{
		    
    redirect("crudmemberbeneficiarie/filteredgrid");

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
	if(is_logged() && get_role()<='3')
	{
     
    //filteredgrid//
    
    $this->rapyd->load("datafilter","datagrid");
    
    $this->rapyd->uri->keep_persistence();
    
    //filter
    $filter = new DataFilter("Carian Ahli dan Penerima Faedah");
    $filter->db->select("*");
    $filter->db->from("kwkpp_ahli_khairat");
    $filter->db->join("kwkpp_jenis_ahli","kwkpp_jenis_ahli.jenisAhliID=kwkpp_ahli_khairat.jenisAhliID","LEFT");
	$filter->db->join("kwkpp_penama","kwkpp_penama.ahliKhairatID=kwkpp_ahli_khairat.ahliKhairatID","LEFT");
	$filter->db->where("kwkpp_penama.ahliKhairatID<>'null'");
    $filter->noPekerja = new inputField("No Pekerja", "kwkpp_ahli_khairat.noPekerja");
	//$filter->noPekerja->clause ="=";
	//$filter->noAhli = new inputField("No Ahli", "kwkpp_ahli_khairat.noAhli");
    //$filter->noIC = new inputField("No IC", "kwkpp_ahli_khairat.noIC");
	$filter->noICPenama = new inputField("No IC Penama", "kwkpp_penama.noICPenama");
    //$filter->active->option("","");
    //$filter->active->options(array("y"=>"Yes","n"=>"No"));
    
    $filter->buttons("reset","search");
    $filter->build();
    

    $uri = "crudmemberbeneficiarie/dataedit/show/<#ahliKhairatID#>";
    $popup_uri = "crudmemberbeneficiarie/dataedit/show/<#ahliKhairatID#>";
    //grid
    $grid = new DataGrid("Senarai Ahli dan Penerima Faedah");
    //$grid->use_function("callback_test");
    $grid->order_by("kwkpp_ahli_khairat.noPekerja","asc");
    $grid->per_page = 25;
    $grid->use_function("substr");

    $grid->column_detail("No Pekerja","noPekerja", $uri);
	//$grid->column("No Ahli", "<#jenisAhliCode#><#ahliKhairatID#>");  
	$grid->column_detail("Nama","<substr><#nama#>|0|25</substr>", $uri);
	$grid->column("Penama","penama");
	$grid->column("No IC Penama","noICPenama");
    
    //$grid->column("email","<#email#> <#lastlogin#>");
	//$grid->column("active","<strtoupper><#active#></strtoupper>", "align=middle");
    //$grid->column("callback test","<callback_test><#user_name#>|3</callback_test>");$grid->column("No Ahli","<#noAhli#>");
    
   // $grid->add("crudmemberbeneficiarie/dataedit/create");
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
	if(is_logged() && get_role()<='3')
	{
 
    if (($this->uri->segment(5)==="1") && ($this->uri->segment(4)==="do_delete")){
      show_error("Please do not delete the first record, it's required by DataObject sample");
    }

/**/$ahliID = intval($this->uri->segment(6));
if($this->uri->segment(5)==="show" || $this->uri->segment(3)==="show")
	{
	$this->rapyd->load("dataobject");
	$do = new DataObject("kwkpp_ahli_khairat");
    $do->rel_one_to_one("kodJenisAhli", "kwkpp_jenis_ahli","jenisAhliID");
	$do->load($ahliID);
    $kod = $do->get_all();

	$do2 = new DataObject("kwkpp_ahli_khairat");
	$do2->load($ahliID);
	$noahli = $do2->get_all();
	
	$data["jenisAhliCode"] = $kod["kodJenisAhli"]["jenisAhliCode"].$noahli["ahliKhairatID"];

	}	

    
    $this->rapyd->load("dataedit","datagrid");
//datagrid
    $uri = "crudbeneficiaries/dataedit/modify/<#penamaID#>";
    $popup_uri = "crudmemberbeneficiarie/dataedit/show/<#ahliKhairatID#>";
    //grid
    $grid = new DataGrid("Penerima Tuntutan");

    $grid->db->select("*");
    $grid->db->from("kwkpp_ahli_khairat");
    $grid->db->join("kwkpp_jenis_ahli","kwkpp_jenis_ahli.jenisAhliID=kwkpp_ahli_khairat.jenisAhliID","LEFT");
	$grid->db->join("kwkpp_penama","kwkpp_penama.ahliKhairatID=kwkpp_ahli_khairat.ahliKhairatID","LEFT");
	$grid->db->join("kwkpp_hubungan","kwkpp_hubungan.hubunganID=kwkpp_penama.hubunganID","LEFT");	$grid->db->where("kwkpp_ahli_khairat.ahliKhairatID=$ahliID");
	$grid->db->orderby("kwkpp_penama.noPenama");

    $grid->order_by("kwkpp_ahli_khairat.noPekerja","asc");
    $grid->per_page = 5;
    $grid->use_function("substr");

    //$grid->column_detail("No Pekerja","noPekerja", $uri);

	//$grid->column("No Ahli", "<#jenisAhliCode#><#ahliKhairatID#>");  
	//$grid->column("Nama","<substr><#nama#>|0|25</substr>");
	$grid->column("Bil Penama","<#noPenama#>");
	//$grid->column("Penama","penama");
	$grid->column_detail("Penama <font class=\"alert\">(klik untuk ubah)</font>","penama", $uri);
    $grid->column("No IC Penama","<#noICPenama#>");
	$grid->column("Hubungan","hubunganDesc");
	$grid->column("Peratus","<#peratus#>");
	//$grid->column("active","<strtoupper><#active#></strtoupper>", "align=middle");
    //$grid->column("callback test","<callback_test><#user_name#>|3</callback_test>");$grid->column("No Ahli","<#noAhli#>");
    
//    $grid->add("crudmemberbeneficiarie/dataedit/create");
    $grid->build();

//dataedit//
    $edit = new DataEdit("Ahli Khairat", "kwkpp_ahli_khairat");
	
    $edit->back_uri = "crudmemberbeneficiarie/filteredgrid";
	
	//$edit2 = new DataEdit("Jenis Ahli", "kwkpp_jenis_ahli");
/*
	$edit->role = new dropdownField("Tahap", "role_id");
    $edit->role->option("","");
    $edit->role->options("SELECT role_id, name FROM security_role");
	$edit->role->rule = "required";


	$edit->staticdd = new dropdownField("Public", "public");  
    $edit->staticdd->option("y","Si");  
    $edit->staticdd->option("n","No");  
    $edit->staticdd->rule = "required";    
	


	$edit->dynamicdd = new dropdownField("Hubungan", "hubunganID");  
    $edit->dynamicdd->option("","");  
    $edit->dynamicdd->options("select hubunganID, concat(hubunganDesc, '>>', hubunganID) from kwkpp_hubungan");  
    $edit->dynamicdd->onchange = "a_custom_javascript_function();";  


  $edit->use_function("callback_test");
  $edit->test = new freeField("Test", "kod", "<callback_test><#kod#>|3</callback_test>");      
    $edit->kod_noAhli = new dropdownField("Nombor Ahli", "ahliKhairatID");//$data["jenisAhliCode"]);
	$edit->kod_noAhli->option("","");
    $edit->kod_noAhli->options("select ahliKhairatID, concat(jenisAhliCode,noAhli) from kwkpp_ahli_khairat 										ak left join kwkpp_jenis_ahli ja on (ja.jenisAhliID=ak.jenisAhliID)");
    $edit->kod_noAhli->mode = "readonly";
	
	*/
	$edit->noPekerja = new inputField("No Pekerja", "noPekerja");
    $edit->noPekerja->rule = "trim|required|max_length[10]";
if($this->uri->segment(5)==="show" || $this->uri->segment(3)==="show")
		{
$edit->free = new freeField("No Ahli","free",$data["jenisAhliCode"]);  
		}


	$edit->role = new dropdownField("Jenis Ahli", "jenisAhliID");
    $edit->role->option("","");
    $edit->role->options("SELECT jenisAhliID, jenisAhliDesc FROM kwkpp_jenis_ahli");
	$edit->role->rule = "required";
/*
if($this->uri->segment(5)!="show")
		{	
	$edit->noAhli = new inputField("No Ahli", "noAhli");//$data["author"]
    $edit->noAhli->rule = "trim|required|max_length[10]";
		}
*/
	$edit->noIC = new inputField("No IC", "noIC");
    $edit->noIC->rule = "trim|required|max_length[14]";
    
	$edit->nama = new inputField("Nama", "nama");
    $edit->nama->rule = "trim|required|max_length[50]";

	
	$edit->statusAhli = new dropdownField("Status Ahli", "statusAhli");
    $edit->statusAhli->option("","");
    $edit->statusAhli->options("select statusAhliCode, concat(statusAhliCode, ' - ', statusAhliDesc) from kwkpp_status_ahli");
	$edit->statusAhli->rule = "required";

    
    if ($this->uri->segment(4)==="1"){
      $edit->buttons( "save", "undo",  "back");//"delete","modify",
    } else {
      $edit->buttons("save", "undo", "back");//"delete","modify",
    }
    
    $edit->build();
    $data["edit"] = $edit->output . $grid->output; //$data["jenisAhliCode"] . $data["title"] . $data["author"]
     
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