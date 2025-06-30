<?php

require_once('basecontroller.php');

class Cruddecease extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Cruddecease()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='3')
	{
		    
    redirect("cruddecease/filteredgrid");

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
	
    $filter = new DataFilter("Carian Kematian");
    $filter->db->select("*, date_format(k.tarikhMati, '%d/%m/%Y') as 'dd', date_format(k.tarikhDaftarMati, '%d/%m/%Y') as 'rd', @rownum:=@rownum+1 as 'c'");
    $filter->db->from("(SELECT @rownum:=0) c, kwkpp_kematian k");
    $filter->db->join("kwkpp_ahli_khairat","kwkpp_ahli_khairat.ahliKhairatID=k.ahliKhairatID","LEFT");
    $filter->db->join("kwkpp_negeri","kwkpp_negeri.negeriCode=k.negeriID","LEFT");

    $filter->noPekerja = new inputField("No Pekerja", "noPekerja");
	$filter->noAhli = new inputField("No Kematian", "kematianID");

/*	
	$filter->tarikhDaftarMati = new dateField("Tarikh Daftar Mati Dari (dd/mm/yyyy)", "tarikhDaftarMati","d/m/Y");
	$filter->tarikhDaftarMati->clause="wherebetween1st";
	$filter->tarikhDaftarMati->operator="between";

	$filter->tarikhDaftarMati2nd = new dateField("Tarikh Daftar Mati Hingga (dd/mm/yyyy)", "tarikhDaftarMatis","d/m/Y");
	$filter->tarikhDaftarMati2nd->clause="wherebetween2nd";
*/
	$filter->tarikhDaftarMatiDari = new dateField("Tarikh Daftar Mati Dari (dd/mm/yyyy)", "tarikhDaftarMati","eu");
    $filter->tarikhDaftarMatiDari->clause="where";
    $filter->tarikhDaftarMatiDari->operator=">=";
    
    $filter->tarikhDaftarMatiHingga = new dateField("Tarikh Daftar Mati Hingga (dd/mm/yyyy)", "end_date","eu");
    //important note  end_date is not a real field on the db, is just "an alias"
    //the next param assignation set the "real" database field name.
    $filter->tarikhDaftarMatiHingga->db_name="tarikhDaftarMati";
    $filter->tarikhDaftarMatiHingga->clause="where";
    $filter->tarikhDaftarMatiHingga->operator="<=";




    $filter->buttons("reset","search");
    $filter->build();
    
    $uri = "cruddecease/dataedit/show/<#kematianID#>";
    
    //grid
    $grid = new DataGrid("Senarai Kematian");
    //$grid->use_function("callback_test");

    $grid->order_by("kematianID","desc");
    $grid->per_page = 25;
    $grid->use_function("substr");
	$grid->column("Bil","<#c#>");
    $grid->column_detail("No Pekerja","noPekerja", $uri);
	$grid->column("Nama","<#nama#>");

	$grid->column("Tarikh Mati","<#dd#>");
	$grid->column("Tarikh Daftar Mati","<#rd#>");
    $grid->column("Sebab","<#sebab#>");
    //$grid->column("No Laporan Polis","<#noLaporanPolis#>");
    $grid->column("Negeri Kematian","<#negeriDesc#>");
	$grid->column("No Kematian","<#kematianID#>");
    //$grid->column("callback test","<callback_test><#user_name#>|3</callback_test>");
  /*if(date("d")<=$this->config->item('max_date_decease'))
{}	 */
    $grid->add("cruddecease/dataedit/create");

	$grid->build();
 
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
//$this->output->enable_profiler(TRUE);  
    if (($this->uri->segment(5)==="1") && ($this->uri->segment(4)==="do_delete")){
      show_error("Please do not delete the first record, it's required by DataObject sample");
    }
/*post delete action*/
	if ($this->uri->segment(3)==="do_delete" || $this->uri->segment(5)==="do_delete")
	{
		if($this->uri->segment(3)==="do_delete")
		{$kematianID=$this->uri->segment(4);}
		elseif($this->uri->segment(5)==="do_delete")
		{$kematianID=$this->uri->segment(6);}
		
		$query = $this->db->query("SELECT ahliKhairatID FROM kwkpp_kematian WHERE kematianID='$kematianID'");
		if ($query->num_rows() > 0)
		{
		   foreach ($query->result() as $row)
		   {
			  $ahliKhairatID = $row->ahliKhairatID;
		   }
		   $query = $this->db->query("UPDATE kwkpp_ahli_khairat SET statusAhli='".$this->config->item('active_status')."' WHERE ahliKhairatID='$ahliKhairatID'");
		}
	}

    //dataedit//
    $this->rapyd->load("dataedit", "dataform");
	$this->load->model('Ahli_khairat_model');
    $edit = new DataEdit("Kematian", "kwkpp_kematian");
	
    $edit->back_uri = "cruddecease/filteredgrid";     

	$edit->ahliKhairatID = new dropdownField("Nama Ahli (No Pekerja)", "ahliKhairatID");
    $edit->ahliKhairatID->option("","");

	if($this->uri->segment(5)==="show" OR $this->uri->segment(5)==="modify" OR $this->uri->segment(5)==="update" OR $this->uri->segment(3)==="show" OR $this->uri->segment(3)==="modify" OR $this->uri->segment(3)==="update")
	{
    $edit->ahliKhairatID->options($this->Ahli_khairat_model->all_ahli_khairat());
	}
	elseif($this->uri->segment(5)==="create" OR $this->uri->segment(5)==="insert" OR $this->uri->segment(3)==="create" OR $this->uri->segment(3)==="insert")
	{
	$edit->ahliKhairatID->options($this->Ahli_khairat_model->alive_ahli_khairat());
	}
	else
	{
	$edit->ahliKhairatID->options($this->Ahli_khairat_model->all_ahli_khairat());	
	}

	$edit->ahliKhairatID->rule = "required|unique";
	if ($this->uri->segment(5)==="modify" || $this->uri->segment(3)==="modify" || $this->uri->segment(5)==="update" || $this->uri->segment(3)==="update")
	{
	$edit->ahliKhairatID->mode = "readonly";
	}

	if(is_numeric($this->uri->segment(3)))
	{$edit->ahliKhairatID->value = $this->uri->segment(3);}
/*hh:mm  H:i*/
	$edit->tarikhMati = new dateField("Tarikh Mati<br>(dd/mm/yyyy)", "tarikhMati","d/m/Y");
	$edit->tarikhMati->rule = "required";

	$edit->tarikhDaftarMati = new dateField("Tarikh Daftar Mati<br>(dd/mm/yyyy)", "tarikhDaftarMati","d/m/Y");
	$edit->tarikhDaftarMati->rule = "required";
	$edit->tarikhDaftarMati->value = date("Y-m-d H:m:s");

	$edit->sebab = new inputField("Sebab", "sebab");
    $edit->sebab->rule = "trim|max_length[50]";

/*	$edit->noLaporanPolis = new inputField("No Laporan Polis", "noLaporanPolis");
    $edit->noLaporanPolis->rule = "max_length[15]";
*/	
	$edit->negeriID = new dropdownField("Negeri Kematian", "negeriID");
    $edit->negeriID->option("","");
    $edit->negeriID->options("SELECT negeriCode, negeriDesc FROM kwkpp_negeri order by negeriDesc");

	if($this->uri->segment(5)==="show" || $this->uri->segment(3)==="show")
	{
	//$edit->free = new freeField("No Mati", "free", "kematianID"); 
	$edit->noMati = new inputField("No Kematian", "kematianID");
	}

	$edit->jumlah_ahli_aktif = new inputField("Jumlah Ahli Biasa Aktif", "jumlah_ahli_aktif");
    $edit->jumlah_ahli_aktif->rule = "required|numeric|max_length[9]";
	$edit->jumlah_ahli_aktif->when = array('show','modify');

	$edit->pendahuluan = new inputField("Pendahuluan(RM)", "pendahuluan");
    $edit->pendahuluan->rule = "required|numeric|max_length[9]";
	$edit->pendahuluan->when = array('show','modify');

	$edit->tunggakan = new inputField("Jumlah Tunggakan(RM)", "tunggakan");
    $edit->tunggakan->rule = "required|numeric|max_length[9]";
	$edit->tunggakan->when = array('show','modify');

	$edit->tuntutan = new inputField("Jumlah Tuntutan(RM)", "tuntutan");
    $edit->tuntutan->rule = "required|numeric|max_length[9]";
	$edit->tuntutan->when = array('show','modify');
	
/*	$edit->jenisBilCode = new inputField("jenisBilCode", "jenisBilCode");
	$edit->jenisBilCode->insertValue = $this->config->item('sumbangan_bil_code');
	$edit->jenisBilCode->when = array();
	
	$edit2 = new DataEdit("Kematian", "kwkpp_ahli_khairat");
	$edit2->back_uri = "cruddecease/filteredgrid";
	$edit->ahli_id = new inputField("Ahli ID", "ahliKhairatID"); 

	$edit->hidfield = new freeField("Hidden ID","hidden","<input type='text' name='hiddenID' value='ahliKhairatID'>");    
	//$edit->ahli_id->mode = "hidden";"delete",
*/
    if ($this->uri->segment(4)==="1"){
      $edit->buttons("modify", "save", "undo", "back");
    } else {
      $edit->buttons("modify", "save", "undo", "back");
    }


	$edit->use_function("callback_test");
	
    $edit->build();

    $data["edit"] = $edit->output;
     
    //enddataedit//

//print_r($edit->error_string);

if ($edit->on_success()){  
//	$this->db->query("DELETE FROM articles WHERE article_id='67'");
 if($this->input->post("password")){
	  }
  }

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