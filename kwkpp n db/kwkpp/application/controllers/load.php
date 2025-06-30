<?php

require_once('basecontroller.php');

class Load extends BaseController {

  var $data_type = null;   
  var $data = null;


	function Load()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
	if(is_logged() && get_role()<='3')
	{
	//$this->load->file('C:\Apps\inteamadmin.sql', false);
	$path="C:\Apps\inteamadmin.sql\\$*///+?()|^(.+?)/*$|";
	$path = preg_replace("|^(.+?)/*$|", "\\1", $path);
	echo "wow- ".$path;
    //true redirect("crudmembers/filteredgrid");

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
    $filter = new DataFilter("Carian Ahli");
    $filter->db->select("*, , concat(jenisAhliCode,ahliKhairatID) as 'jenisAhliCodeahliKhairatID'");
    $filter->db->from("kwkpp_ahli_khairat");
    $filter->db->join("kwkpp_jenis_ahli","kwkpp_jenis_ahli.jenisAhliID=kwkpp_ahli_khairat.jenisAhliID","LEFT");

    $filter->noPekerja = new inputField("No Pekerja", "noPekerja");
	//$filter->noPekerja->clause ="=";
	$filter->noAhli = new inputField("No Ahli", "noAhli");
    $filter->noIC = new inputField("No IC", "noIC");
    //$filter->active->option("","");
    //$filter->active->options(array("y"=>"Yes","n"=>"No"));
    
    $filter->buttons("reset","search");
    $filter->build();
    

    $uri = "crudmembers/dataedit/show/<#ahliKhairatID#>";
    $popup_uri = "membercert/dataedit/show/<#ahliKhairatID#>";
	$popup_uri02 = "membercert02/dataedit/show/<#ahliKhairatID#>";
    //grid
    $grid = new DataGrid("Senarai Ahli");
    //$grid->use_function("callback_test");
    $grid->order_by("jenisAhliCodeahliKhairatID","asc");
    $grid->per_page = 5;
    $grid->use_function("substr");

    $grid->column_detail("No Pekerja", "noPekerja", $uri);

	$grid->column_detail("No Ahli", "jenisAhliCodeahliKhairatID", $uri);  
	
	$grid->column_detail_popwin("No IC","<#noIC#>", $popup_uri,"1000","500");
    $grid->column_detail_popwin("Nama","<substr><#nama#>|0|25</substr>", $popup_uri02,"1000","500" );
    //$grid->column("email","<#email#> <#lastlogin#>");
	//$grid->column("active","<strtoupper><#active#></strtoupper>", "align=middle");
    //$grid->column("callback test","<callback_test><#user_name#>|3</callback_test>");$grid->column("No Ahli","<#noAhli#>");
    
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
	if(is_logged() && get_role()<='3')
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
	if(is_logged() && get_role()<='3')
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
	if(is_logged() && get_role()<='3')
	{
 
    if (($this->uri->segment(5)==="1") && ($this->uri->segment(4)==="do_delete")){
      show_error("Please do not delete the first record, it's required by DataObject sample");
    }
////////////
/*  	
    
  $do = new DataObject("articles");
    $do->rel_one_to_one("author", "authors","author_id");
    
    $do->load(1);
    $article_one = $do->get_all();
    
    $data["title"]  = htmlspecialchars($article_one["title"]);
    $data["author"] = $article_one["author"]["firstname"] . " " .
                      $article_one["author"]["lastname"]; 
*/	// . $data["title"] . $data["author"];
//
    
 //   $grid = new DataGrid("Comments","comments");
 //   $grid->db->where("article_id", $art_id);
/////////////
/**/
if($this->uri->segment(5)==="show")
	{
	$this->rapyd->load("dataobject");
	$do = new DataObject("kwkpp_ahli_khairat");
    $do->rel_one_to_one("kodJenisAhli", "kwkpp_jenis_ahli","jenisAhliID");
    
	$ahliID = intval($this->uri->segment(6));
	$do->load($ahliID);
    $kod = $do->get_all();

	$do2 = new DataObject("kwkpp_ahli_khairat");
	$do2->load($ahliID);
    $noahli = $do2->get_all();
	
	$data["jenisAhliCode"] = $kod["kodJenisAhli"]["jenisAhliCode"].$noahli["ahliKhairatID"];

	}	

    //dataedit//
    $this->rapyd->load("dataedit");

    $edit = new DataEdit("Ahli", "kwkpp_ahli_khairat");
	
    $edit->back_uri = "crudmembers/filteredgrid";
	
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
    $edit->kod_noAhli->options("select ahliKhairatID, concat(jenisAhliCode,noAhli) from kwkpp_ahli_khairat ak left join kwkpp_jenis_ahli ja on (ja.jenisAhliID=ak.jenisAhliID)");
    $edit->kod_noAhli->mode = "readonly";
	
	*/
	$edit->noPekerja = new inputField("No Pekerja", "noPekerja");
    $edit->noPekerja->rule = "trim|required|max_length[10]";
	if($this->uri->segment(5)==="show")
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
    $edit->statusAhli->options(array("A"=>"Aktif","T"=>"Perkhidmatan Ditamatkan"));
	$edit->statusAhli->rule = "required";

	$edit->tarikhLahir = new dateField("Tarikh Lahir", "tarikhLahir","d/m/Y");

	$edit->tarikhMasuk = new dateField("Tarikh Masuk", "tarikhMasuk","d/m/Y");
	$edit->tarikhMasuk->insertValue = date("Y-m-d");

	$edit->tarikhKeluar = new dateField("Tarikh Keluar", "tarikhKeluar","d/m/Y");
//$edit->tarikhKeluar->option("onclick","auth");
//	$edit->tarikhKeluar->mode = "readonly";

	$edit->tarikhBersara = new dateField("Tarikh Bersara", "tarikhBersara","d/m/Y");

	$edit->kodStesenAkhir = new inputField("Kod Stesen Akhir", "kodStesenAkhir");
    $edit->kodStesenAkhir->rule = "trim|max_length[6]";

	$edit->noSambPej = new inputField("No Sambungan Pejabat", "noSambPej");
    $edit->noSambPej->rule = "max_length[6]";

	$edit->noTelRumah = new inputField("No Telefon Rumah", "noTelRumah");
    $edit->noTelRumah->rule = "max_length[11]";

	$edit->noTelBimbit = new inputField("No Telefon Bimbit", "noTelBimbit");
    $edit->noTelBimbit->rule = "max_length[11]";

	$edit->email = new inputField("e-mail", "email");
    $edit->email->rule = "valid_email|max_length[25]";

	$edit->komen = new inputField("Komen", "komen");
    $edit->komen->rule = "max_length[50]";

/* $edit->datefield = new dateField("Date", "datefield","eu");
$edit->datefield->insertValue = date("Y-m-d");

$edit->datetime = new dateField("Creation date", "created","d/m/Y H:i:s");  
$edit->datetime->mode = "readonly";
$edit->datetime->insertValue = date("Y-m-d H:i:s");  
	
	 
 $edit->body = new editorField("Body", "body");
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
      $edit->buttons("modify", "save", "undo", "back", "delete");
    } else {
      $edit->buttons("modify", "save", "undo", "delete", "back");
    }


    
    
    $edit->build();
    $data["edit"] = $edit->output; //$data["jenisAhliCode"] . $data["title"] . $data["author"]
     
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