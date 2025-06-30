<?php
//require_once('basecontroller.php');
class Membercert2 extends Controller {

	function Membercert2()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$this->load->library('rapyd');
		$this->load->view('membercert2');
		
	}

/*	function pdf()
	{
		 $this->load->plugin('to_pdf');
		 // page info here, db calls, etc.     
		 $html = $this->load->view('controller/viewfile', $data, true);
		 pdf_create($html, 'membercert1');
	} 
*/
	##### dataedit #####
	function dataedit()
	{	
		$this->load->library('rapyd');
		
		//$this->load->helper('text');
		if(is_logged() && get_role()<='3')
		{
	 
		if (($this->uri->segment(5)==="1") && ($this->uri->segment(4)==="do_delete")){
		  show_error("Please do not delete the first record, it's required by DataObject sample");
		}

	$this->rapyd->load("dataobject");
	$do = new DataObject("kwkpp_ahli_khairat");
    $do->rel_one_to_one("jenisAhli", "kwkpp_jenis_ahli","jenisAhliID");
    
	$ahliID = intval($this->uri->segment(6));
	$do->load($ahliID);
    $kwkpp_ahli_khairat = $do->get_all();

	$data["nama"] = $kwkpp_ahli_khairat["nama"];
	$data["noPekerja"] = $kwkpp_ahli_khairat["noPekerja"];
	$data["noIC"] = $kwkpp_ahli_khairat["noIC"];
	$data["jenisAhliDesc"] = $kwkpp_ahli_khairat["jenisAhli"]["jenisAhliDesc"];
	$data["jenisAhliID"] = $kwkpp_ahli_khairat["jenisAhliID"];

    if($data["jenisAhliID"]==='1')
	{
		$data["jenisAhliID"]="Sijil Ahli Seumur Hidup";
		$data["noAhli"]="SH".$kwkpp_ahli_khairat["noAhliSH"];
		}
	elseif($data["jenisAhliID"]==='2')
	{
		$data["jenisAhliID"]="Sijil Keahlian";
		$data["noAhli"]="AB".$kwkpp_ahli_khairat["noAhliAB"];

	}


	/**/




	if($kwkpp_ahli_khairat["tarikhMasuk"]!='')
	{	
	$tarikhMasuk = explode('-',$kwkpp_ahli_khairat["tarikhMasuk"]);
	
	$this->load->model ('DateTime_model');
	$tarikhMasuk[1]=$this->DateTime_model->malay_month_name($tarikhMasuk[1]);
	
	$tarikhMasuk[0]=substr($tarikhMasuk[0], 2, 3);  

	$data["tarikhMasuk"] = $tarikhMasuk[2]." ".$tarikhMasuk[1]." ".$tarikhMasuk[0];
	}
	else
	{
	$data["tarikhMasuk"] = '-';
	}

	
	$data["jenisAhliCode"] = $kwkpp_ahli_khairat["jenisAhli"]["jenisAhliCode"];
	$data["ahliKhairatID"] = $kwkpp_ahli_khairat["ahliKhairatID"];
	
	$this->load->plugin('to_pdf');
	//$this->load->view('membercert2', $data);$data["nama"]."[]"
	$html = $this->load->view('membercert2', $data, true);
	//pdf_create($html, $data['nama']."(".$data['jenisAhliCode'].$data['ahliKhairatID'].")", 'a4', 'landscape');	
	pdf_create($html, $data['nama']."(".$data['noAhli'].")", 'a4', 'landscape');	

	}
	  else
	  {redirect("auth/login");}

	  }
}
?>