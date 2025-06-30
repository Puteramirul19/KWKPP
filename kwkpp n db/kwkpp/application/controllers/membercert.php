<?php 
require_once('basecontroller.php');

class Membercert extends Controller {

	function Membercert()
	{
		parent::Controller();	
	}
	
	function index()
	{
		$this->load->library('rapyd');
		$this->load->view('membercert');
		
	}

	##### dataedit #####
	function dataedit()
	{	
		$this->load->library('rapyd');
		
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

	$tarikhMasuk = explode('-',$kwkpp_ahli_khairat["tarikhMasuk"]);
	$data["tarikhMasuk"] = $tarikhMasuk[2].".".$tarikhMasuk[1].".".$tarikhMasuk[0];
	$data["jenisAhliCode"] = $kwkpp_ahli_khairat["jenisAhli"]["jenisAhliCode"];
	$data["ahliKhairatID"] = $kwkpp_ahli_khairat["ahliKhairatID"];

	//$this->load->view('membercert', $data);
	 $this->load->plugin('to_pdf');
	 $html = $this->load->view('membercert', $data, true);
     pdf_create($html, 'filename',  'a4', 'landscape');

	  }
	  else
	  {redirect("auth/login");}

	  }
}
?>