<?php

class M_pengguna_gudang extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function Get_Pengguna_Gudang()
	{
		$this->db	->select("pengguna_gudang_id, pengguna_id, gudang_id")
					->distinct()
					->from("pengguna_gudang")
					->order_by("pengguna_gudang");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
	
	public function Get_Pengguna_Gudang_By_Pengguna_ID( $pengguna_id )
	{
		$this->db	->select("pengguna_gudang_id, pengguna_id, g.gudang_id, g.gudang_nama")
					->distinct()
					->from("pengguna_gudang as pg")
					->join("gudang as g","g.gudang_id = pg.gudang_id","inner")
					->where("pengguna_id", $pengguna_id )
					->order_by("g.gudang_nama");
					
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}

	public function Check_pengguna_gudang_Is_Used( $gudang_id )
	{
		$this->db	->select("gudang_id")
					->from("KurirOrder")
					->where("gudang_id", $gudang_id );
		$query1 = $this->db->get_compiled_select();
		
		$this->db->reset_query();
		
		$this->db	->select("gudang_id")
					->from("SKU_gudang")
					->where("gudang_id", $gudang_id );
		$query2 = $this->db->get_compiled_select();
		
		$this->db->reset_query();
		
		$this->db	->select("gudang_id")
					->from("SKU_gudangD")
					->where("gudang_id", $gudang_id );
		$query3 = $this->db->get_compiled_select();

		$this->db->reset_query();
		
		$q = $this->db->query("$query1 UNION ALL $query2 UNION ALL $query3");
		
		if($q->num_rows() == 0)	{	return 0;	}
		else					{	return 1; 	}
	}
	
	public function Insert_pengguna_gudang( $pengguna_id, $gudang_id )
	{
		$this->db->set("pengguna_gudang_ID", "NEWID()", FALSE );
		$this->db->set("pengguna_id", $pengguna_id);
		$this->db->set("gudang_id", $gudang_id);
		
		$this->db->insert("pengguna_gudang");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryinsert = 1;	}
		else					{	$queryinsert = 0;	}
	
		return $queryinsert;
	}
			
	public function Delete_pengguna_gudang($Target_pengguna_id)
	{
		$this->db->where("pengguna_id", $Target_pengguna_id);
		
		$this->db->delete("pengguna_gudang");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$querydelete = 1;	}
		else					{	$querydelete = 0;	}
	
		return $querydelete;
	}
}