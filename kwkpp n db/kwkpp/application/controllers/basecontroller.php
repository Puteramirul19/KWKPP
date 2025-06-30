<?php

class BaseController extends Controller {

  var $data_type = null;
  var $data = null;
  var $get_theme = true;
/*SYSTEM SETTING*/
  //var $system_name = "KUMPULAN WANG KHAIRAT PEKERJA & PESARA TNB";
  //var $max_date_decease = 10;
  //var $active_status = "AK";
  //var $decease_status = "MD";  
  var $sumbangan_bil_code = "S1";
  var $pendaftaran_bil_code = "P1";
  var $tukar_penama_bil_code = "P2";
  //var $mm_array = array("01"=>"Jan","02"=>"02","03"=>"03","04"=>"04","05"=>"05","06"=>"06","07"=>"07","08"=>"08","09"=>"09","10"=>"10","11"=>"11","12"=>"12");

 // var $yy_array = year_listing();
/*SYSTEM SETTING*/

	function BaseController()
	{

		parent::Controller(); 
 
    //required helpers for samples
    $this->load->helper('url');
    $this->load->helper('text');

	//$this->rapyd->language('malay');
	//$this->curr_language = $this->rapyd->language->language;  
 
		//rapyd library
		$this->load->library("rapyd");

		//rapyd theme persistence
    if ($this->get_theme AND $this->rapyd->session->get("current_theme")) $this->rapyd->config->set_item("theme", $this->rapyd->session->get("current_theme"));

    //I use THISFILE, instead __FILE__ to prevent some documented php-bugs with higlight_syntax()&__FILE__
    define ("THISFILE",   APPPATH."controllers/". $this->uri->rsegment(2).EXT);
    define ("VIEWPATH",   APPPATH."views/");
	}
 
  
  ##### iframes & actions #####
  /**/function loadiframe($data=null, $head="", $resize="")
  {

    $template['head'] = $head;
    $template['content'] = $data;
    $template["theme"] = $this->rapyd->config->item("theme");
    $template["style"] = $this->load->view("style_".$template["theme"], null, true);
    
    $template['onload'] = "";

	
    if ($resize!=""){
      $template['onload'] = "autofit_iframe('$resize');";
    }
    $this->load->view('iframe', $template);
  }
  
  
	function _render($view, $data=null, $highlight=array())
	{
  
    $content["content"] = $this->load->view($view, $data, true);
    $content["rapyd_head"] = $this->rapyd->get_head();
    
    $content["theme"] = $this->rapyd->config->item("theme");
    $content["style"] = $this->load->view("style_".$content["theme"], null, true);

    $language_ON = $this->rapyd->config->item("rapyd_lang_ON");
    $content["language_links"] = (isset($language_ON) && $language_ON === True) ? "&nbsp;".language_links() : "|&nbsp; rapyd_lang is off";

    
    $content["code"] = "";

/*status statistic*/
	$content["statusAhli"] = null;
	$query = $this->db->query("select distinct concat(ja.jenisAhliDesc,': ',count(*)) as 'jenisAhliDesc', ja.jenisAhliID from kwkpp_ahli_khairat ak join kwkpp_jenis_ahli ja on (ja.jenisAhliID=ak.jenisAhliID) group by ja.jenisAhliDesc order by ja.jenisAhliDesc");

	if ($query->num_rows() > 0)
	{
	   foreach ($query->result() as $row)
	   {
		  $content["statusAhli"] .= "['".$row->jenisAhliDesc."', null, null,";
		  $i=$row->jenisAhliID;
		  $query_membertype = $this->db->query("select concat(sa.statusAhliDesc,': ',count(*)) as 'statusAhli' from kwkpp_ahli_khairat ak join kwkpp_status_ahli sa on (sa.statusAhliCode=ak.statusAhli) join kwkpp_jenis_ahli ja on (ja.jenisAhliID=ak.jenisAhliID) where ak.jenisAhliID='".$i."' group by ak.statusAhli, ak.jenisAhliID");

		  foreach ($query_membertype->result() as $row_membertype)
		  {

		  $content["statusAhli"] .= "['".$row_membertype->statusAhli."', null, null],";
		  
		  }
		  $content["statusAhli"] .= "],";
	   }
	}
/*status statistic*/

    $this->load->view('template', $content);

  }
  
 
  ##### utility, show you $_SESSION status #####
  function _session_dump()
  {
    echo '<div style="height:200px; background-color:#fdfdfd; overflow:auto;">';
    echo '<pre style="font: 11px Courier New,Verdana">';
    var_export($_SESSION);
    echo '</pre>';
    echo '</div>';
  }

 /* function year_listing()
  {
	$curr_year = date("Y");
	$curr_year_minus5 = $curr_year-5;
	$curr_year_plus5 = $curr_year+5;
	for($y=$curr_year_minus5;$y<=$curr_year_plus5;$y++)
	{$yyyy_array[$y] = $y;}

	return $yyyy_array;
  }
*/

  
}
?>
