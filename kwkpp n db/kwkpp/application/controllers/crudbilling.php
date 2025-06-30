<?php

require_once('basecontroller.php');

class Crudbilling extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Crudbilling()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='3')
	{
		    
    redirect("crudbilling/filteredgrid");

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
    $this->load->model ('DateTime_model');
    $this->rapyd->uri->keep_persistence();
    
    //filter
    $filter = new DataFilter("Carian Bil Ahli (Pendaftaran & Tukar Penama Sahaja)");
    $filter->db->select("*");
    $filter->db->from("kwkpp_billing");
    $filter->db->join("kwkpp_ahli_khairat","kwkpp_ahli_khairat.ahliKhairatID=kwkpp_billing.ahliKhairatID","LEFT");
	$filter->db->join("kwkpp_jenis_bil","kwkpp_jenis_bil.jenisBilCode=kwkpp_billing.jenisBilCode","LEFT");
	$filter->db->where("kwkpp_billing.jenisBilCode!='".$this->config->item('sumbangan_bil_code')."'");
	
	$filter->noPekerja = new inputField("No Pekerja", "noPekerja");

	$filter->bulan = new dropdownField("Bulan", "bulan");
    $filter->bulan->option("","");  
	$filter->bulan->options($this->DateTime_model->month_mm());//($this->config->item('bulan'));
	
/*	$curr_year=date("Y");
	$curr_year_minus5 = $curr_year-5;
	$curr_year_plus5 = $curr_year+5;
	for($y=$curr_year_minus5;$y<=$curr_year_plus5;$y++)
	{$ddmenu[$y] = $y;}
*/
	$filter->tahun = new dropdownField("Tahun", "tahun");
    $filter->tahun->option("","");  
	$filter->tahun->options($this->DateTime_model->year5_yyyy());
	//$filter->tahun->insertValue = date('Y');

	$filter->jenisBilCode = new dropdownField("Jenis Bil", "kwkpp_billing.jenisBilCode");
    $filter->jenisBilCode->option("",""); 
    $filter->jenisBilCode->options("select jenisBilCode, concat(jenisBilCode, ' - ', jenisBilDesc) from kwkpp_jenis_bil where jenisBilCode!='".$this->config->item('sumbangan_bil_code')."' order by jenisBilCode");

	$filter->buttons("reset","search");    
    $filter->build();
    
	//$id="<#ahliKhairatID#>";//<#ahliKhairatID#>

    $uri = "crudbilling/dataedit/show/<#billingID#>";
	$uri_bayar = "crudpaybill/dataedit/create/<#ahliKhairatID#>/<#tahun#>/<#bulan#>/<#jenisBilCode#>/<#amaun_bil#>";
    //$uri_byr = $uri_bayar.$id;
    //grid
    $grid = new DataGrid("Senarai Bil");
    //$grid->use_function("callback_test");
    $grid->order_by("tahun desc,bulan desc, nama asc, kwkpp_billing.jenisBilCode","asc");
    $grid->per_page = 25;
    $grid->use_function("substr");
//echo $grid->output('bulan');
	$grid->column("Tahun", "tahun");
	$grid->column("Bulan", "bulan");
	$grid->column_detail("No Pekerja", "noPekerja", $uri);
	$grid->column_detail("Nama <font class=\"alert\">(klik nama untuk bayar)</font>", "nama", $uri_bayar);
	$grid->column("Kod Bil", "jenisBilCode");
	$grid->column("Keterangan Bil", "jenisBilDesc");
	$grid->column("Amaun Bil", "amaun_bil");
	

    $grid->add("crudbilling/dataedit/create");
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
	$this->load->model('Ahli_khairat_model');
	$this->load->model ('DateTime_model');
    $edit = new DataEdit("Bil Ahli (Pendaftaran & Tukar Penama)", "kwkpp_billing");
    $edit->back_uri = "crudbilling/filteredgrid";

	$edit->ahliKhairatID = new dropdownField("Nama Ahli (No Pekerja)", "ahliKhairatID");
    $edit->ahliKhairatID->option("","");
	$edit->ahliKhairatID->options($this->Ahli_khairat_model->all_ahli_khairat());
	$edit->ahliKhairatID->rule = "required";
	//$edit->ahliKhairatID->when = array("show","create");
	if ($this->uri->segment(5)==="modify" || $this->uri->segment(3)==="modify" || $this->uri->segment(5)==="update" || $this->uri->segment(3)==="update")
	{
	$edit->ahliKhairatID->mode = "readonly";
	}

	$edit->bulan = new dropdownField("Bulan", "bulan");
    $edit->bulan->option("","");  
	$edit->bulan->options($this->DateTime_model->month_mm());//($this->config->item('bulan'));
	$edit->bulan->insertValue = "bulan";
	$edit->bulan->rule = "trim|required|numeric|min_length[2]|max_length[2]";

	$edit->tahun = new dropdownField("Tahun", "tahun");
    $edit->tahun->option("","");  
	$edit->tahun->options($this->DateTime_model->year5_yyyy());
	$edit->tahun->insertValue = "tahun";
    $edit->tahun->rule = "trim|required|numeric|min_length[4]|max_length[4]";

	$edit->jenisBilID = new dropdownField("Jenis Bil", "jenisBilCode");
	$edit->jenisBilID->option("","");
	$edit->jenisBilID->options("SELECT jenisBilCode, concat(jenisBilCode, ' - ', jenisBilDesc) FROM kwkpp_jenis_bil where jenisBilCode!='".$this->config->item('sumbangan_bil_code')."' order by jenisBilCode");
	$edit->jenisBilID->rule = "required";
	
/*	if($this->uri->segment(5)==="show" OR $this->uri->segment(3)==="show")
	{
    $edit->jenisBilID->options("SELECT jenisBilCode, concat(jenisBilCode, ' - ', jenisBilDesc) FROM kwkpp_jenis_bil order by jenisBilCode");
	}
	else
	{}
	
	*/
	$edit->amaun_bil = new inputField("Amaun Bil", "amaun_bil");
    $edit->amaun_bil->rule = "trim|required|numeric|max_length[9]";

    if ($this->uri->segment(4)==="1"){
      $edit->buttons("modify", "save", "undo", "back");
    } else {
      $edit->buttons("modify", "save", "undo", "delete", "back");
    }
    
    $edit->build();
    $data["edit"] = $edit->output;
     
    //enddataedit//


    $this->_render("dataedit", $data, 
                    array(
                      array("file"=>THISFILE, "id"=>"dataedit", "title"=>"dataedit"),
                    )
                  );
  }
  else
  {redirect("auth/login");}

  }
  

}

?>