<?php

class M_Pengguna_Grup extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function Getpengguna_grup()
	{
		$this->db	->select("pengguna_grup_id, pengguna_grup_kode, pengguna_grup_nama")
					->from("pengguna_grup")
					->order_by("pengguna_grup_kode");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
		
	public function Getpengguna_grup_by_pengguna_grup_is_dewa( $pengguna_grup_is_dewa )
	{
		$this->db	->select("pengguna_grup_id, pengguna_grup_kode, pengguna_grup_nama")
					->from("pengguna_grup")
					->where("pengguna_grup_is_dewa <= ", $pengguna_grup_is_dewa )
					->order_by("pengguna_grup_kode");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
				
	public function Getpengguna_grup_checker()
	{
		$this->db	->select("pengguna_grup_id, pengguna_grup_kode, pengguna_grup_nama")
					->from("pengguna_grup")
					->where("pengguna_grup_nama", "CHECKER");
		$query = $this->db->get();	
		
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = $query->result_array(); }
		
		return $query;
	}
				
	public function Getpengguna_grup_by_pengguna_grup_id( $pengguna_grup_id )
	{
		$this->db	->select("USerGroupID, pengguna_grup_kode, pengguna_grup_nama")
					->from("pengguna_grup")
					->where("pengguna_grup_id", $pengguna_grup_id);
		$query = $this->db->get();	
		
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Checkpengguna_grup_duplicate( $pengguna_grup_id )
	{
		$this->db	->select("pengguna_grup_kode")
					->from("pengguna_grup")
					->where("pengguna_grup_id", $pengguna_grup_id);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Insertpengguna_grup( $pengguna_grup_id, $pengguna_grup_kode, $pengguna_grup_nama)
	{
		$this->db->set("pengguna_grup_id", $pengguna_grup_id);
		$this->db->set("pengguna_grup_kode", $pengguna_grup_kode);
		$this->db->set("pengguna_grup_nama", $pengguna_grup_nama);
		
		$this->db->insert("pengguna_grup");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryinsert = 1;	}
		else					{	$queryinsert = 0;	}
	
		return $queryinsert;
	}
			
	public function Updatepengguna_grup( $pengguna_grup_id, $pengguna_grup_nama)
	{
		$this->db->set("pengguna_grup_nama", $pengguna_grup_nama);
	
		$this->db->where("pengguna_grup_id", $pengguna_grup_id);
		
		$this->db->update("pengguna_grup");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryupdate = 1;	}
		else					{	$queryupdate = 0;	}
			
		return $queryupdate;
	}		
		
	public function Deletepengguna_grup($Target_pengguna_grup_id)
	{
		$this->db->where("pengguna_grup_id", $Target_pengguna_grup_id);
		
		$this->db->delete("pengguna_grup");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$querydelete = 1;	}
		else					{	$querydelete = 0;	}
	
		return $querydelete;
	}
}