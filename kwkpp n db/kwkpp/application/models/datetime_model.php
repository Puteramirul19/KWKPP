<?php
class DateTime_model extends Model {

    function DateTime_model()
    {
        parent::Model();
    }

	function month_mm()
    {
		$mm = array("01"=>"01","02"=>"02","03"=>"03","04"=>"04","05"=>"05","06"=>"06","07"=>"07","08"=>"08","09"=>"09","10"=>"10","11"=>"11","12"=>"12");
		return $mm;
    }


/***
This will create year listing in array with +-5 year from the parameter year OR if year=null it will use current year, order ascending
***/

function year5_yyyy($year=null)
    {
		if(!isset($year))
		{$year=date("Y");}

		$curr_year = $year;
		$curr_year_minus5 = $curr_year-5;
		$curr_year_plus5 = $curr_year+5;
		//$yyyy_array[0] = "model5";
		for($y=$curr_year_minus5;$y<=$curr_year_plus5;$y++)
		{$yyyy_array[$y] = $y;}

		return $yyyy_array;
    }


/***
This will create year listing from minimum-3 to max+3 year in kwkpp_billing table descending
***/
	function yearBill_yyyy()
    {
   
		$query = $this->db->query("SELECT min(tahun) as 'min_tahun' FROM kwkpp_billing");
		$row = $query->row();
		$min_tahun = $row->min_tahun-3;

		$query = $this->db->query("SELECT max(tahun) as 'max_tahun' FROM kwkpp_billing");
		$row = $query->row();
		$max_tahun = $row->max_tahun+3;
		//$yyyy_array[0] = "model3";
		for($y=$max_tahun;$y>=$min_tahun;$y--)
		{$yyyy_array[$y] = $y;}

		return $yyyy_array;
    }

	function malay_month_name($mm)
	{
	
		if($mm=="01")
		{$malay_month_name="Januari";}
		elseif($mm=="02")
		{$malay_month_name="Februari";}
		elseif($mm=="03")
		{$malay_month_name="Mac";}
		elseif($mm=="04")
		{$malay_month_name="April";}
		elseif($mm=="05")
		{$malay_month_name="Mei";}
		elseif($mm=="06")
		{$malay_month_name="Jun";}
		elseif($mm=="07")
		{$malay_month_name="Julai";}
		elseif($mm=="08")
		{$malay_month_name="Ogos";}
		elseif($mm=="09")
		{$malay_month_name="September";}
		elseif($mm=="10")
		{$malay_month_name="Oktober";}
		elseif($mm=="11")
		{$malay_month_name="November";}
		elseif($mm=="12")
		{$malay_month_name="Disember";}
		else
		{$malay_month_name="00";}

		return $malay_month_name;
//if($mm=="00")
	}

}

/*
	function getContactInfo($id)
    {
		$this->db->where('id', $id);
		$query = $this->db->get('clientcontacts');
		
		if ($query->num_rows() == 0) {
			return FALSE;
		} else {
			return $query->row();
		}
    }

	function getContactId($email)
    {
		$this->db->select('id');
		$this->db->where('email', $email);
		return $this->db->get('clientcontacts')->row()->id;
    }
	
	function password_reset($email, $random_passkey)
	{
		$this->db->where('email', $email);
		$this->db->where('access_level != 0'); // they allowed to login?
		$this->db->set('password_reset', $random_passkey);
		$this->db->update('clientcontacts');
		if ($this->db->affected_rows() != 0) {
			return $this->getContactId($email);
		} else {
			return FALSE;
		}
	}

	function password_confirm($id, $passkey)
	{
		$this->db->where('id', $id);
		$this->db->set('password_reset', $passkey);
		$password = $this->db->get('clientcontacts');
		if ($password->num_rows() == 1) {
			return $password;
		} else {
			return FALSE;
		}
	}

	function password_change($id, $new_password)
	{
		$this->db->where('id', $id);
		$this->db->set('password', $new_password);
		$this->db->update('clientcontacts');

		$this->db->where('id', $id);
		$password = $this->db->get('clientcontacts');
		if ($password->num_rows() == 1) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

*/
?>