<?php

require_once('basecontroller.php');

class Crudclaim extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Crudclaim()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='3')
	{
		    
    redirect("crudclaim/filteredgrid");

	}else{redirect("auth/login");}
  }

  ##### DataFilter + DataGrid #####
  function filteredgrid()
  {
	if(is_logged() && get_role()<='3')
	{
        
    //filteredgrid//
    
    $this->rapyd->load("datafilter","datagrid");
    $this->load->model('DateTime_model');
    $this->rapyd->uri->keep_persistence();
    
    //filter
    $filter = new DataFilter("Carian Tuntutan Kematian");
    $filter->db->select("*");
    $filter->db->from("kwkpp_bayaran");
    $filter->db->join("kwkpp_ahli_khairat","kwkpp_ahli_khairat.ahliKhairatID=kwkpp_bayaran.ahliKhairatID","LEFT");
    $filter->db->join("kwkpp_penama","kwkpp_penama.penamaID=kwkpp_bayaran.penamaID","LEFT");
		
	$filter->noPekerja = new inputField("No Pekerja", "noPekerja");
	
	$filter->statusBayaran = new dropdownField("Status Bayaran", "statusBayaran");
    $filter->statusBayaran->option("","");
    $filter->statusBayaran->options(array("BB"=>"Belum Bayar","DP"=>"Dalam Proses","SB"=>"Sudah Bayar"));

	$filter->buttons("reset","search");    
    $filter->build();

    $uri = "crudclaim/dataedit/show/<#bayaranID#>";
    
    //grid
    $grid = new DataGrid("Senarai Tuntutan Kematian");
    //$grid->use_function("callback_test");
    $grid->order_by("nama,noPenama","asc");
    $grid->per_page = 25;
    $grid->use_function("substr");

	$grid->column_detail("No Pekerja", "noPekerja", $uri);

	$grid->column("Nama", "nama");

	$grid->column_detail("penama", "penama", $uri);

	//$grid->column("No Penama", "noPenama");
 
	$grid->column("Peratus", "peratus");

	$grid->column("Amaun", "amaun");

	$grid->column("Status Bayaran", "statusBayaran");

    $grid->add("crudclaim/dataedit/create");
    $grid->build();
    /**/
    $data["crud"] = $filter->output . $grid->output;
    
    //endfilteredgrid//
    
    
    
    $this->_render("crud", $data, 
                    array(
                      array("file"=>THISFILE, "id"=>"filteredgrid", "title"=>"Filtered Grid"),
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
  
    //dataedit//
    $this->rapyd->load("dataedit");

	rapydlib("prototype");  

	$this->load->model('Ahli_khairat_model');
	$this->load->model('DateTime_model');

    $edit = new DataEdit("Maklumat Tuntutan Kematian", "kwkpp_bayaran");
    $edit->back_uri = "crudclaim/filteredgrid";


	$ajax_onchange = ' 
	function get_subcategories() 
	{ 
	 var url = "'.site_url('crudclaim/ajaxsubcategories').'"; 
	 var pars = "ahliKhairatID="+$F("ahliKhairatID")+"&penamaID="+$F("penamaID"); 
	 var myAjax = new Ajax.Updater("td_penamaID", url, { method: "post", parameters: pars });
	} 
	';  

	$edit->script($ajax_onchange, "create");  
	$edit->script($ajax_onchange, "modify");  
	$edit->script($ajax_onchange, "show");  

	$edit->ahliKhairatID = new dropdownField("Nama Ahli (No Pekerja)", "ahliKhairatID");
    $edit->ahliKhairatID->option("","");
	$edit->ahliKhairatID->options($this->Ahli_khairat_model->all_ahli_khairat());
	$edit->ahliKhairatID->onchange = "get_subcategories()"; 
	$edit->ahliKhairatID->rule = "required";
	
	//$edit->ahliKhairatID->mode = "readonly";
	//$edit->ahliKhairatID->style="display=none";

	$edit->penamaID = new dropdownField("Penama - No IC", "penamaID");
    $edit->penamaID->option("","");
	$edit->penamaID->options("select penamaID, concat(penama, ' - ', noICPenama) from kwkpp_penama"); //where ahliKhairatID=''
	$edit->penamaID->onclick = "get_subcategories()";
	$edit->penamaID->rule = "required";	

	if ($this->uri->segment(5)==="modify" || $this->uri->segment(3)==="modify" || $this->uri->segment(5)==="update" || $this->uri->segment(3)==="update")
	{
	$edit->ahliKhairatID->mode = "readonly";
	$edit->penamaID->mode = "readonly";
	}

	$edit->pembayarID = new dropdownField("Pembayar", "pembayarID");
    $edit->pembayarID->option("","");
	$edit->pembayarID->options("select pembayarID, pembayarDesc from kwkpp_pembayar");
//	$edit->penamaID->rule = "required";	

	$edit->tarikhBayar = new dateField("Tarikh Pembayaran<br>(dd/mm/yyyy)", "tarikhBayar","d/m/Y");
	$edit->tarikhBayar->value = date("Y-m-d H:m:s");
	//$edit->tarikhBayar->rule = "required";

	$edit->amaun = new inputField("Amaun", "amaun");
    $edit->amaun->rule = "required|numeric|trim|max_length[9]";

	$edit->bankID = new dropdownField("Bank", "bankID");
    $edit->bankID->option("","");
	$edit->bankID->options("select bankID, bankDesc from kwkpp_bank");
	/*array('show','modify');*/

	$edit->namaCek = new inputField("Nama Cek", "namaCek");
    $edit->namaCek->rule = "trim|max_length[50]";

	$edit->noCek = new inputField("No Cek", "noCek");
    $edit->noCek->rule = "trim|max_length[10]";

	$edit->tarikhHantar = new dateField("Tarikh Hantar<br>(dd/mm/yyyy)", "tarikhHantar","d/m/Y");
	$edit->tarikhHantar->value = date("Y-m-d H:m:s");
	//$edit->tarikhHantar->rule = "required";

	$edit->PIC = new inputField("PIC", "PIC");
    $edit->PIC->rule = "trim|max_length[10]";

	$edit->statusBayaran = new dropdownField("Status Bayaran", "statusBayaran");
    $edit->statusBayaran->option("","");
	$edit->statusBayaran->options(array("BB"=>"Belum Bayar","DP"=>"Dalam Proses", "SB"=>"Sudah Bayar"));
	$edit->statusBayaran->insertValue = $this->config->item('belum_bayar_code');
	$edit->statusBayaran->rule = "required";

    if ($this->uri->segment(4)==="1"){
      $edit->buttons("modify", "save", "undo", "back");
    } else {
      $edit->buttons("modify", "save", "undo", "delete", "back");
    }

    $edit->build();
    $data["edit"] = $edit->output;
     
    //enddataedit//


/*    $this->_render("dataedit", $data, 
                    array(
                      array("file"=>THISFILE, "id"=>"dataedit", "title"=>"dataedit"),
                    )
                  );
//	*/ 
	$this->_render('dataedit', $data, true);
  }
  else
  {redirect("auth/login");}

  }

  
   function ajaxsubcategories()  
   {  
     $this->rapyd->load("fields");  
     $where = "";  
     if (isset($_POST["ahliKhairatID"]) && $_POST["ahliKhairatID"]!=""){  
       $where = "WHERE ahliKhairatID = '".$_POST["ahliKhairatID"]."'";
	   $sql = "select penamaID, concat(penama, ' - ', noICPenama) from kwkpp_penama ".$where;  
     } 
	 else{
	   $_POST["ahliKhairatID"]="";

	   $sql = "select '0','Sila Pilih Ahli'";
	 }
       
   
     $penamaID = new dropdownField("No IC Penerima", "penamaID");  
     $penamaID->option("","");  
     $penamaID->options($sql);  
     $penamaID->status = "modify";  
     $penamaID->build();  
     echo $penamaID->output;  
     //return $subcategory->output;  
   }  

}

?>