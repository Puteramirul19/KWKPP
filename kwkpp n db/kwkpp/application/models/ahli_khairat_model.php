<?php
class Ahli_khairat_model extends Model {

    function Ahli_khairat_model()
    {
        parent::Model();
    }

	function all_ahli_khairat()
	{
		$query = "SELECT ahliKhairatID, concat(nama, ' - ',noPekerja) FROM kwkpp_ahli_khairat order by nama";
		return $query;
	}

	function alive_ahli_khairat()
	{
		$query = "SELECT ak.ahliKhairatID, concat(ak.nama, ' - ', ak.noPekerja) FROM kwkpp_ahli_khairat ak where ak.ahliKhairatID not in (select k.ahliKhairatID from kwkpp_kematian k) order by nama";
		return $query;
	}

	function ahli_khairat_by_status($status)
	{
		$result =  mysql_query("SELECT * FROM kwkpp_ahli_khairat where status_ahli in ('$status') order by nama asc");
		while ($tablerow = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$ddmenu[$tablerow['ahliKhairatID']] = $tablerow['nama']." - ".$tablerow['noPekerja'];
		}
		return $ddmenu;
	}

	function all_ahli_khairat_dropdown()
	{
		$ddmenu = array( '0'  => 'Semua Ahli',); /*all members option*/
		$result =  mysql_query("SELECT * FROM kwkpp_ahli_khairat order by nama asc");
		while ($tablerow = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$ddmenu[$tablerow['ahliKhairatID']] = $tablerow['nama']." - ".$tablerow['noPekerja'];
		}
		return $ddmenu;
	}

	function all_ahli_khairat_dropdown_without_all_members() /*exclude option for all members*/
	{

//$dbres= $this->db->query('SELECT * FROM kwkpp_ahli_khairat order by nama asc');

/*		$this->db->select('*')->from('kwkpp_ahli_khairat')->orderby("nama", "asc");
		$dbres = $this->db->get();
	
		foreach ($dbres->result_array() as $tablerow) 
		{
		  $ddmenu[$tablerow['ahliKhairatID']] = $tablerow['nama']." - ".$tablerow['noPekerja'];
		}
 */	

	$result =  mysql_query("SELECT * FROM kwkpp_ahli_khairat order by nama asc");
	while ($tablerow = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$ddmenu[$tablerow['ahliKhairatID']] = $tablerow['nama']." - ".$tablerow['noPekerja'];
	}
	return $ddmenu;
	}

}

/*
	function countClientInvoices($client_id)
	{
		$this->db->where('client_id', $client_id);
		return $this->db->get('invoices')->num_rows();
	}

	function getAllClients()
	{
		$this->db->orderby('name', 'asc');
		return $this->db->get('clients');
	}

	function getAllClientInfo($id)
    {
		$this->db->where('id', $id);
		return $this->db->get('clients')->row();
    }

	function getClientContacts($id)
	{
		$this->db->where('client_id', $id);
		return $this->db->get('clientcontacts');
	}

	function addClient($clientInfo)
	{
		$this->db->insert('clients', $clientInfo);
		return TRUE;
	}

	function updateClient($client_id, $clientInfo)
	{
		$this->db->where('id', $clientInfo['id']);
		$this->db->update('clients', $clientInfo);
		return TRUE;
	}
	
	function deleteClient($client_id)
	{
		// Don't allow the admin to be deleted this way
		if ($client_id === 0) {
			return FALSE;
		} else {

			/**
			 * There are 5 tables of data to delete from in order to completely
			 * clear out record of this client.
			 *
			 * Handle them 1 at a time for clarity and ease of maintenance.
			 */
	
	/*		$this->db->query("DELETE FROM invoice_histories 
								USING invoice_histories, invoices 
								WHERE invoice_histories.invoice_id=invoices.id 
								AND invoices.client_id = " . $client_id);
			$this->db->query("DELETE FROM invoice_payments 
								USING invoice_payments, invoices 
								WHERE invoice_payments.invoice_id=invoices.id 
								AND invoices.client_id = " . $client_id);		 
			$this->db->where('client_id', $client_id);
			$this->db->delete('clientcontacts'); 
			$this->db->where('id', $client_id);
			$this->db->delete('clients');
			$this->db->where('client_id', $client_id);
			$this->db->delete('invoices'); 
			return TRUE;
		}
	}
	
*/
?>