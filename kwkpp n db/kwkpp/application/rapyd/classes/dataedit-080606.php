<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Rapyd Components
 *
 * An open source library for CodeIgniter application development framework for PHP 4.3.2 or newer
 *
 * @package		rapyd.components
 * @author		Felice Ostuni
 * @license		http://www.fsf.org/licensing/licenses/lgpl.txt LGPL
 * @version		0.9.6
 * @filesource
 */
 
/**
 * ancestor 
 */
require_once("dataform.php");

/**
 * DataEdit base class.
 *
 * @package    rapyd.components
 * @author     Felice Ostuni
 * @author     Thierry Rey
 * @access     public
 */
class DataEdit extends DataForm{

  //deprecated could be removed in the 1.0
  var $back_url = "";
  
  //new property
  var $back_uri = "";
  
  var $check_pk = true;

  var $_postprocess_uri = "";

  var $_undo_uri = "";
  var $_buttons = array();
  var $_pkey =0;

  var $back_save = false;
  var $back_delete = true;
  var $back_cancel = false;

  var $back_cancel_save = false;
  var $back_cancel_delete = false;

  //var $pk_value = "";
  
 /**
  * PHP4 constructor.
  *
  * @access   public
  * @param    string   $title  widget title
  * @param    mixed   $table  db-tablename to be edited / or a dataobject instance
  * @return   void
  */
  function DataEdit($title, $table){
      
    if (is_object($table) && is_a($table, "DataObject"))
    {
      $dataobject =& $table;
    } else {
    
      $dataobject = new DataObject($table);

    }
    parent::DataForm(null, $dataobject);

    $this->session =& $this->rapyd->session; 

    $this->_pkey = count($this->_dataobject->pk);
    
    
    if ($this->rapyd->uri->get("osp",1)==""){
      $this->rapyd->uri->un_set("osp");
    }
    $this->_sniff_status();
    $this->title($title);
  }



 /**
  * transforn a PK array in the same format of the one used in DO->load() function ie: array(pk1=>value1, pk2=>value2) 
  * in a string formated as we attent at the end (pk part)of the URI (as explain in conventions)=>/pk1_name/pk1_value/pk2_name/pk2_value/...
  * @access   private
  * @param    array   
  * @return   string
  */
	function pk_to_URI($pk)
	{
		  $result="";
		  foreach ($pk as $keyfield => $keyvalue){
		  	$result.= "/".$keyvalue;	
			}
			return $result;
	}
	/**
  * rebuild the PK array in the same format of the one used in DO->load() function ie: array(pk1=>value1, pk2=>value2) 
  * from the string formated as we attent at the end (pk part)of the URI (as explain in conventions)=>/pk1_name/pk1_value/pk2_name/pk2_value/...
  * @access   private
  * @param    string   
  * @return   array
  */
	function URI_to_pk($id_str , $do)
	{
		  $result=array();
	
		  //check and remove for '/' in first and last position for that explode work fine.
      $tmp_ar = explode("/",$id_str);
			$keys = array_keys($do->pk);
		 	for($i=0;$i <= count($tmp_ar)-1;$i++){
		 		$result[$keys[$i]]=$tmp_ar[$i];
		 	}

		 	return $result;
	}
  
	/**
	* rebuild the string formated as we attent at the end (pk part)of the URI (as explain in conventions)=>/pk1_name/pk1_value/pk2_name/pk2_value/...
	* without the first slash 
  * from the segment_array 
  * @access   private
  * @param    array    
  * @return   string
  */	
	function segment_id_str($segment_ar)
	{
		$id_segment = array_slice($segment_ar,-($this->_pkey));
		return join('/',$id_segment);
	}


  function _sniff_status(){
   
    $this->_status = "idle";

    $segment_array = $this->uri->segment_array();

	$id_str = $this->segment_id_str($segment_array);
	    
    //The following var is unsuded?? it seams to be an old test remaining code??
    //$uri_array = $this->rapyd->uri->explode_uri($this->uri->uri_string());
  
    ///// show /////
    if ($this->rapyd->uri->is_set("show") && (count($this->rapyd->uri->get("show")) == $this->_pkey+1) ){
    
        $this->_status = "show";

        $this->_process_uri = "";
        $result = $this->_dataobject->load($this->URI_to_pk($id_str,$this->_dataobject));
        
        if (!$result){
          $this->_status = "unknow_record";
        } 
     
    ///// modify /////
    } elseif ($this->rapyd->uri->is_set("modify")  && (count($this->rapyd->uri->get("modify")) == $this->_pkey+1)){
    
        $this->_status = "modify";
    
        $this->_process_uri = $this->rapyd->uri->change_clause($this->rapyd->uri->uri_array, "modify", "update");
    
        $result = $this->_dataobject->load($this->URI_to_pk($id_str,$this->_dataobject));
        if (!$result){
          $this->_status = "unknow_record";
        }
        
    
    ///// create /////
    } elseif ($this->rapyd->uri->is_set("create")){
    
        $this->_status = "create";
    
        $this->_process_uri = $this->rapyd->uri->change_clause($this->rapyd->uri->uri_array, "create", "insert");
        
    
    ///// delete /////
    } elseif ($this->rapyd->uri->is_set("delete") && (count($this->rapyd->uri->get("delete")) == $this->_pkey+1)){
    
        $this->_status = "delete";
        
        $this->_process_uri = $this->rapyd->uri->change_clause($this->rapyd->uri->uri_array, "delete", "do_delete");
        $this->_undo_uri    = $this->rapyd->uri->change_clause($this->rapyd->uri->uri_array, "delete", "show");

        $result = $this->_dataobject->load($this->URI_to_pk($id_str,$this->_dataobject));
        if (!$result){
          $this->_status = "unknow_record";
        }
    }
  }


  function _sniff_action(){
          
    $segment_array = $this->uri->segment_array();
    $id_str = $this->segment_id_str($segment_array);
    
    ///// insert /////
    if ($this->rapyd->uri->is_set("insert")){

      $this->_action = "insert";
      $this->_postprocess_uri =  $this->rapyd->uri->change_clause($this->rapyd->uri->uri_array, "insert", "show");

    ///// update /////
    } elseif ($this->rapyd->uri->is_set("update")){
    
      $this->_action = "update";

      //this uri is completed in the "process" method
      $this->_postprocess_uri = $this->rapyd->uri->unset_clause($this->rapyd->uri->uri_array, "update");

      $this->_dataobject->load($this->URI_to_pk($id_str,$this->_dataobject));
      
    ///// delete /////
    } elseif ($this->rapyd->uri->is_set("do_delete")){
    
      $this->_action = "delete";
      $result = $this->_dataobject->load($this->URI_to_pk($id_str,$this->_dataobject));
      if (!$result){
        $this->_status = "unknow_record";
      }

    }
  }



  function is_valid(){
    $result = parent::is_valid();

    if (!$this->check_pk) return $result;

    if ($this->_action=="update" || $this->_action=="insert"){
      $pk_check=array();
      $pk_error = "";
      $hiddens = array();
      
      //pk fields mode can setted to "autohide" or "readonly" (so pk integrity violation check isn't needed)
      foreach ($this->_fields as $field_name => $field_copy){
        //reference
        $field =& $this->$field_name;
        $field->_getValue();
        if (!$field->apply_rules){
          $hiddens[$field->db_name] = $field->value;
        }
      }
          
      //We build a pk array from the form value that is submit if its a writing action (update & insert)
      foreach ($this->_dataobject->pk as $keyfield => $keyvalue){
        if (isset($this->validation->$keyfield)){
          $pk_check[$keyfield] = $this->validation->$keyfield;
        // detect that a pk is hidden, so no integrity check needed
        } elseif (array_key_exists($keyfield,$hiddens)){
          $pk_check[$keyfield] = $hiddens[$keyfield];
        }
      }
      
      if (sizeof($pk_check) != $this->_pkey){
      //If PK is Autoincrement we don't need to check PK integrity, But its supose that for a none AutoIcrement PK the form always contain the right PK fields
        if (sizeof($this->_dataobject->pk)==1 && sizeof($pk_check)==0)return $result;
      }
      // this check the unicity of PK with the new DO function
      if ($result && !$this->_dataobject->are_unique($pk_check)){
        $result = false;
        $pk_error .= RAPYD_MSG_0210."<br />";
      }

    }
    $this->error_string = $pk_error.$this->error_string;
    return $result;
  }



  function process(){
  
    $result = parent::process();
    
    switch($this->_action){
      
      case "update": 
        if ($this->on_error()){
          $this->_status = "modify";
          $this->_process_uri = $this->rapyd->uri->uri_string();
          $this->_sniff_fields();
          $this->_build_buttons();
          $this->build_form();
        }
        if ($this->on_success()){

          $this->_postprocess_uri .= "/". $this->rapyd->uri->build_clause("show".$this->pk_to_URI($this->_dataobject->pk));

/*$filename = explode('/',$this->_postprocess_uri);
if($filename[0] == "cruddecease"){
	if(isset($filename[5]) && is_numeric($filename[5]))
	{$this->cruddecease_onsuccess($filename[5]);}
	elseif(isset($filename[3]) && is_numeric($filename[3]))
	{$this->cruddecease_onsuccess($filename[3]);}
}*/
//elseif($filename[0] == "crudmembers"){
//$this->crudmembers_onsuccess($filename[5]);
//}
          if ($this->back_save){
            header("Refresh:0;url=".$this->back_url);
          } else {
            redirect("/".$this->_postprocess_uri,'refresh');
          }
          
        }
      break;
      
      case "insert":  
        if ($this->on_error()){
          $this->_status = "create";
          $this->_process_uri = $this->rapyd->uri->uri_string();
          $this->_sniff_fields();
          $this->_build_buttons();
          $this->build_form();
        }
        if ($this->on_success()){

          $this->_postprocess_uri .= $this->pk_to_URI($this->_dataobject->pk);

$filename = explode('/',$this->_postprocess_uri);
if($filename[0] == "cruddecease"){
	if(isset($filename[5]) && is_numeric($filename[5]))
	{$this->cruddecease_onsuccess($filename[5]);}
	elseif(isset($filename[3]) && is_numeric($filename[3]))
	{$this->cruddecease_onsuccess($filename[3]);}
	elseif(isset($filename[6]) && is_numeric($filename[6]))
	{$this->cruddecease_onsuccess($filename[6]);}
}
elseif($filename[0] == "crudmembers"){
	if(isset($filename[5]) && is_numeric($filename[5]))
	{$this->crudmembers_onsuccess($filename[5]);}
	elseif(isset($filename[3]) && is_numeric($filename[3]))
	{$this->crudmembers_onsuccess($filename[3]);}
}
  
          if ($this->back_save){
            header("Refresh:0;url=".$this->back_url);
          } else {
            redirect($this->_postprocess_uri,'refresh');
          }
          
        }
      break;
      
      case "delete": 
        if ($this->on_error()){
          $this->_build_buttons();
          $this->build_message_form(RAPYD_MSG_0206);
        }



		if ($this->on_success()){

          $this->_build_buttons();

		  if ($this->back_delete){
            header("Refresh:0;url=".$this->back_url);

          } else {
/*$this->_postprocess_uri = $this->uri->uri_string();
echo $this->_postprocess_uri;
$cruddecease = explode('/',$this->_postprocess_uri);
echo $cruddecease[6];
if($cruddecease[0] == "cruddecease"){
$this->cruddecease_onsuccess($cruddecease[6]);}*/
            $this->build_message_form(RAPYD_MSG_0202);
          }
        }
      break;
      
    }
    
    switch($this->_status){
    
      case "show":      
      case "modify":
      case "create":
        $this->_build_buttons();
        $this->build_form();
      break;
      case "delete":
        $this->_build_buttons();
        $this->build_message_form(RAPYD_MSG_0209);


      break;
      case "unknow_record":
        $this->_build_buttons();
        $this->build_message_form(RAPYD_MSG_0208);
      break;
    }

    
  }
/* This function will execute when user insert/update decease member, it will update status as MD - Decease*/
function cruddecease_onsuccess($kematianID)
{ 	
switch($this->_action){
case "insert":
$query = $this->db->query("SELECT ahliKhairatID FROM kwkpp_kematian WHERE kematianID='$kematianID'");
if ($query->num_rows() > 0)
{
   foreach ($query->result() as $row)
   {
      $ahliKhairatID = $row->ahliKhairatID;
   }
	$query = $this->db->query("UPDATE kwkpp_ahli_khairat SET statusAhli='MD' WHERE ahliKhairatID='$ahliKhairatID'");
	/*calculate current active members, then use this formula: (80% * jumlah_ahli_aktif) - pendahuluan - tunggakan= tuntutan*/

//count normal active member
$query_norm_active =  $this->db->query("select count(*) as 'norm_active' from kwkpp_ahli_khairat ak where ak.jenisAhliID='2' and ak.statusAhli='AK'");
	if ($query_norm_active->num_rows() > 0)
	{
	   foreach ($query_norm_active->result() as $row)
	   {
		  $norm_active = $row->norm_active;
	   }
	}
	else
	{
		$norm_active = 0;
	}

/*can add here to store no of active members and date*/
//get amount of Remuneration & Claim according to member type
	$query_remuneration =  $this->db->query("select ja.amaunTuntutanPendahuluan as 'remuneration', ja.amaunTuntutan as 'claim', ak.jenisAhliID as 'jenisAhli' from kwkpp_ahli_khairat ak join kwkpp_jenis_ahli ja on (ja.jenisAhliID=ak.jenisAhliID) where ak.ahliKhairatID='$ahliKhairatID'");
	if ($query_remuneration->num_rows() > 0)
	{
	   foreach ($query_remuneration->result() as $row)
	   {
		  $remuneration = $row->remuneration;
		  $claim = $row->claim;
		  $jenisAhli = $row->jenisAhli;
	   }
	}
	else
	{
		$remuneration = 0;
		$claim = 0;
		$jenisAhli = 0;
	}

//A-B sum(amaun_bil) - sum(amaun_bayaran)
	$query_tunggakan =  $this->db->query("select (select if(sum(amaun_bil),sum(amaun_bil),0) from kwkpp_billing b where b.ahliKhairatID='$ahliKhairatID') - (select if(sum(amaun),sum(amaun),0) from kwkpp_sumbangan s where s.ahliKhairatID='$ahliKhairatID') as 'tunggakan' ");

	if ($query_tunggakan->num_rows() > 0)
	{
	   foreach ($query_tunggakan->result() as $row)
	   {
		  $tunggakan = $row->tunggakan;
	   }
	}
	else
	{
		$tunggakan = 0;
	}

//tuntutan total
/*TUNTUTAN FOR NORMAL MEMBER (AB)*/
if($jenisAhli==='2')
	{$tuntutan = (80/100 * $norm_active) -  $remuneration - $tunggakan + $claim;}
/*TUNTUTAN FOR LIFE MEMBER (SH)*/
elseif($jenisAhli==='1')
	{$tuntutan = $remuneration - $tunggakan + $claim;}

	$query_update_kematian = $this->db->query("UPDATE kwkpp_kematian SET jumlah_ahli_aktif='$norm_active', pendahuluan='$remuneration', tunggakan='$tunggakan', tuntutan='$tuntutan' WHERE ahliKhairatID='$ahliKhairatID'");

//tuntutan for each penama
	$query_tuntutan_penama =  $this->db->query("select penamaID, round(($tuntutan*peratus/100),2) as 'tuntutan_penama' from kwkpp_penama where ahliKhairatID='$ahliKhairatID'");
	if ($query_tuntutan_penama->num_rows() > 0)
	{
	   foreach ($query_tuntutan_penama->result() as $row)
	   {
		  $penamaID = $row->penamaID;
		  $tuntutan_penama = $row->tuntutan_penama;

		  //insert into kwkpp_bayaran, with default status bayaran is BB-'Belum Bayar'. Status lain - [(SB-Sudah Bayar) dan (DP-Dalam Proses)]
		  $query_insert_tuntutan = $this->db->query("INSERT INTO kwkpp_bayaran set ahliKhairatID='$ahliKhairatID', penamaID='$penamaID', amaun='$tuntutan_penama', statusBayaran='BB'");
			
	   }
	}




	/*calculate current active members, then use this formula: (80% * jumlah_ahli_aktif) - pendahuluan - tunggakan= tuntutan*/
/*
A sum(amaun_bil)
	$query_amaun_bil =  $this->db->query("select sum(amaun_bil) as 'amaun_bil' from kwkpp_billing b where b.ahliKhairatID='$ahliKhairatID'");
	if ($query_amaun_bil->num_rows() > 0)
	{
	   foreach ($query_amaun_bil->result() as $row)
	   {
		  $amaun_bil = $row->amaun_bil;
	   }
	}
	else
	{
		$amaun_bil = 0;
	}

B sum(amaun_bayaran)
	$query_amaun_bayaran =  $this->db->query("select sum(amaun) as 'amaun_bayaran' from kwkpp_sumbangan s where s.ahliKhairatID='$ahliKhairatID'");
	if ($query_amaun_bayaran->num_rows() > 0)
	{
	   foreach ($query_amaun_bayaran->result() as $row)
	   {
		  $amaun_bayaran = $row->amaun_bayaran;
	   }
	}
	else
	{
		$amaun_bayaran = 0;
	}
*/
/*	$query = $this->db->query("select round(count(*)*80/100,2)-(ja.amaunTuntutanPendahuluan) as 'tuntutan' from kwkpp_ahli_khairat ak left join kwkpp_jenis_ahli ja on (ja.jenisAhliID=ak.jenisAhliID) where ak.statusAhli='AK'");
	if ($query->num_rows() > 0)
	{
	   foreach ($query->result() as $row)
	   {
		  $tuntutan = $row->tuntutan;
	   }
	}
	else
	{
		$tuntutan = 0;
	}

	$query = $this->db->query("UPDATE kwkpp_kematian SET tuntutan='$tuntutan' WHERE ahliKhairatID='$ahliKhairatID'");
*/
	

}

break;
case "update":
$query = $this->db->query("SELECT ahliKhairatID FROM kwkpp_kematian WHERE kematianID='$kematianID'");
if ($query->num_rows() > 0)
{
   foreach ($query->result() as $row)
   {
      $ahliKhairatID = $row->ahliKhairatID;
   }
	$query = $this->db->query("UPDATE kwkpp_ahli_khairat SET statusAhli='MD' WHERE ahliKhairatID='$ahliKhairatID'");
}

break;
/*case "delete":
	$query = $this->db->query("SELECT ahliKhairatID FROM kwkpp_kematian WHERE kematianID='$_POST[kematianID]'");
if ($query->num_rows() > 0)
{
   foreach ($query->result() as $row)
   {
      $ahliKhairatID = $row->ahliKhairatID;
   }
}

$query = $this->db->query("UPDATE kwkpp_ahli_khairat SET statusAhli='A' WHERE ahliKhairatID='$ahliKhairatID'");
break;*/
}

}
/*when */
function crudmembers_onsuccess($ahliKhairatID)
{    
switch($this->_action){
case "insert":
$query = $this->db->query("select SUBSTRING(ak.tarikhMasuk,1,4) as 'tahun', SUBSTRING(ak.tarikhMasuk,6,2) as 'bulan', ja.amaunYuran as 'amaunYuran' from kwkpp_ahli_khairat ak left join kwkpp_jenis_ahli ja on (ak.jenisAhliID=ja.jenisAhliID) where ahliKhairatID='".$ahliKhairatID."'");
if ($query->num_rows() > 0)
{
   foreach ($query->result() as $row)
   {
      $tahun = $row->tahun;
      $bulan = $row->bulan;
	  $amaunYuran = $row->amaunYuran;
   }
   
   $query = $this->db->query("INSERT INTO kwkpp_billing set ahliKhairatID='".$ahliKhairatID."', jenisBilCode='P1', tahun='".$tahun."', bulan='".$bulan."', amaun_bil='".$amaunYuran."'");
}

break;

}

}

 /**
  * append a default button
  *
  * @access   public
  * @param    string  $name     a default button name ('modify','save','undo','backedit','back')
  * @param    string  $caption  the label of the button (if not set, the default labels will used)
  * @return   void
  */ 
  function crud_button($name="",$caption=null){
    $this->_buttons[$name]=$caption;
  }
  
 /**
  * append a set of default buttons
  *
  * @access   public
  * @param    mixed  $names   a list of button names.  For example 'modify','save','undo','backedit','back'
  * @return   void
  */ 
  function buttons($names){
    $buttons = func_get_args();
    foreach($buttons as $button){
      $this->crud_button($button);
    }
  }

 /**
  * build the appended buttons
  *
  * @access   private
  * @return   void
  */ 
  function _build_buttons(){
    foreach($this->_buttons as $button=>$caption){
      $build_button = "_build_".$button."_button";
      if ($caption == null){
        $this->$build_button();
      } else {
        $this->$build_button($caption);      
      }
    }
    $this->_buttons = array();
  
  }

 /**
  * append the default "modify" button, modify is the button that appears in the top-right corner when the status is "show"
  *
  * @access   public
  * @param    string $caption  the label of the button (if not set, the default labels will used)
  * @return   void
  */
  function _build_modify_button($caption=RAPYD_BUTTON_MODIFY)
  {
    if ($this->_status == "show"  && $this->rapyd->uri->is_set("show"))
    {
      $modify_uri = $this->rapyd->uri->change_clause($this->rapyd->uri->uri_array, "show", "modify");
      
      $action = "javascript:window.location='" . site_url($modify_uri) . "'";
      $this->button("btn_modify", $caption, $action, "TR"); 
    }
  }

 /**
  * append the default "delete" button, delete is the button that appears in the top-right corner when the status is "show"
  *
  * @access   public
  * @param    string  $caption  the label of the button (if not set, the default labels will used)
  * @return   void
  */
  function _build_delete_button($caption=RAPYD_BUTTON_DELETE){

    if ($this->_status == "show"  && $this->rapyd->uri->is_set("show"))
    {
      $delete_uri = $this->rapyd->uri->change_clause($this->rapyd->uri->uri_array, "show", "delete");

      $action = "javascript:window.location='" . site_url($delete_uri) . "'";
      $this->button("btn_delete", $caption, $action, "TR"); 

    } elseif($this->_status == "delete") {

      $action = "javascript:window.location='" . site_url($this->_process_uri) . "'";
      $this->button("btn_delete", $caption, $action, "BL"); 
    }
  }


 /**
  * append the default "save" button,  save is the button that appears in the bottom-left corner when the status is "create" or "modify"
  *
  * @access   public
  * @param    string  $caption  the label of the button (if not set, the default labels will used)
  * @return   void
  */
  function _build_save_button($caption=RAPYD_BUTTON_SAVE){
    if (($this->_status == "create") || ($this->_status == "modify")){  
      $this->submit("btn_submit", $caption, "BL"); 
    }
  }
  

 /**
  * append the default "undo" button, undo is the button that appears in the top-right corner when the status is "create" or "modify"
  *
  * @access   public
  * @param    string  $caption  the label of the button (if not set, the default labels will used)
  * @return   void
  */
  function _build_undo_button($caption=RAPYD_BUTTON_UNDO){
  
    if ($this->_status == "create"){
    
      $action = "javascript:window.location='{$this->back_url}'";
      $this->button("btn_undo", $caption, $action, "TR"); 
     
    } elseif($this->_status == "modify") {
    
    if (($this->back_cancel_save === FALSE) || ($this->back_cancel === FALSE)){
    
        //is modify
        if ($this->rapyd->uri->is_set("modify"))
        {
          $undo_uri = $this->rapyd->uri->change_clause($this->rapyd->uri->uri_array, "modify", "show");

        //is modify on error
        } elseif ($this->rapyd->uri->is_set("update")){
        
          $undo_uri = $this->rapyd->uri->change_clause($this->rapyd->uri->uri_array, "update", "show");
        }

        $action = "javascript:window.location='" . site_url($undo_uri) . "'"; 
      } else {
        $action = "javascript:window.location='{$this->back_url}'";
      }
      
      $this->button("btn_undo", $caption, $action, "TR"); 
      
    } elseif($this->_status == "delete") {

      if(($this->back_cancel_delete === FALSE) || ($this->back_cancel === FALSE)){
        $undo_uri = site_url($this->_undo_uri);
        $action = "javascript:window.location='$undo_uri'";
      } else{
        $action = "javascript:window.location='{$this->back_url}'";
      }      
      
      $this->button("btn_undo", $caption, $action, "TR"); 
    }
  }

 /**
  * append the default "back" button, back is the button that appears in the bottom-left corner when the status is "show"
  *
  * @access   public
  * @param    string  $caption  the label of the button (if not set, the default labels will used)
  * @return   void
  */
  function _build_back_button($caption=RAPYD_BUTTON_BACK){
    if (($this->_status == "show") || ($this->_status == "unknow_record") || ($this->_action == "delete")){
      $action = "javascript:window.location='{$this->back_url}'";
      $this->button("btn_back", $caption, $action, "TR");
    }
  }

 /**
  * append the default "backerror" button
  *
  * @access   public
  * @param    string  $caption  the label of the button (if not set, the default labels will used)
  * @return   void
  */
  function _build_backerror_button($caption=RAPYD_BUTTON_BACKERROR){
    if (($this->_action == "do_delete") && ($this->_on_error)){   
      $action = "javascript:window.history.back()";
      $this->button("btn_backerror", $caption, $action, "TR");       
    }
  }
 
 /**
  * process , main build method, it lunch process() method
  *
  * @access   public
  * @return   void
  */
  function build(){
  
  
    //temp. back compatibility
    if (site_url("")!="/"){
      $this->back_uri = ($this->back_uri != "")? $this->back_uri :  trim(str_replace(site_url(""),"",str_replace($this->config->item('url_suffix') ,"",$this->back_url)), "/");
    } else {
      $this->back_uri = ($this->back_uri != "")? $this->back_uri : trim($this->back_url, "/");
    }
    
    if (($this->back_uri == "") && isset($this->_buttons["back"])){
      show_error('you must give a correct "BACK URI": $edit->back_uri');
    }

  
    //sniff and build fields
    $this->_sniff_fields();
    
    //sniff and perform action
    $this->_sniff_action();

    //build back_url 
    $persistence = $this->rapyd->session->get_persistence($this->back_uri, $this->rapyd->uri->gfid);
    
    
    if ( isset($persistence["back_post"]) ){
      $this->back_url = site_url($persistence["back_uri"]);
    } else {
      $this->back_url = site_url($this->back_uri);
    }


    $this->_built = true;
    
    $this->process();
    

  }
}


?>