<?php

class M_TipePengiriman extends CI_Model
{
	private $table_name = "tipe_pengiriman";

    private $primary_key = "tipe_pengiriman_id";
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function Get_TipePengiriman()
	{
		$this->db	->select("TipePengirimanID, NamaTipe, KeteranganTipe, isactive")
					->from("TipePengiriman")
					->order_by("NamaTipe");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
	
	public function Get_TipePengiriman_Active()
	{
		$this->db	->select("TipePengirimanID, NamaTipe, KeteranganTipe, isactive")
					->from("TipePengiriman")
					->where("isactive", 1)
					->order_by("NamaTipe");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
	
	public function Check_TipePengiriman_Duplicate_NamaTipe( $NamaTipe )
	{
		$this->db	->select("TipePengirimanID, NamaTipe, KeteranganTipe, isactive")
					->from("TipePengiriman")
					->where("NamaTipe", $NamaTipe )
					->order_by("NamaTipe");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
			
		return $query;
	}
	
	public function Check_TipePengiriman_Duplicate_NamaTipe_Others( $TipePengirimanID, $NamaTipe )
	{
		$this->db	->select("TipePengirimanID, NamaTipe, KeteranganTipe, isactive")
					->from("TipePengiriman")
					->where("NamaTipe", $NamaTipe )
					->where("TipePengirimanID <>", $TipePengirimanID );
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
			
		return $query;
	}
	
	
	public function Insert_TipePengiriman( $NamaTipe, $KeteranganTipe, $isactive )
	{
		$this->db->set("TipePengirimanID", "NewID()", FALSE);
		$this->db->set("NamaTipe", $NamaTipe);
		$this->db->set("KeteranganTipe", $KeteranganTipe);
		$this->db->set("isactive", $isactive);
		
		$this->db->insert("TipePengiriman");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryinsert = 1;	}
		else					{	$queryinsert = 0;	}
	
		return $queryinsert;
	}
		
	public function Update_TipePengiriman( $TipePengirimanID, $NamaTipe, $KeteranganTipe, $isactive )
	{
		$this->db->set("NamaTipe", $NamaTipe);
		$this->db->set("KeteranganTipe", $KeteranganTipe);
		$this->db->set("isactive", $isactive);
		
		$this->db->where("TipePengirimanID", $TipePengirimanID );
		
		$this->db->update("TipePengiriman");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryinsert = 1;	}
		else					{	$queryinsert = 0;	}
	
		return $queryinsert;
	}
		
	public function Delete_TipePengiriman($Target_TipePengirimanID)
	{
		$this->db->trans_begin();
		
		$this->db->where("TipePengirimanID", $Target_TipePengirimanID);
		
		$this->db->delete("TipePengiriman");
		
		$error = $this->db->error();

		if ($error['message'] != '' || $error['message'] != null)
		//if( $error['message'] != '' && $error['message'] != null )
		{
			$res = $error['message']; // Error
			
			$this->db->trans_rollback();
		}
		else
		{
			$this->db->trans_commit();
			
			$affectedrows = $this->db->affected_rows();
			if( abs($affectedrows) > 0 )
			{
				$res = 1; // Success
			}
			else
			{
				$res = 0; // Success
			}
		}
		
		return $res;
	}

    public function countAll()
    {
		$this->db->from($this->table_name);
		return $this->db->count_all_results();
    }

    public function findAll()
    {
        $objects = $this->db->get($this->table_name);
        return $objects->result();
    }

	public function findManyByCriteria($criterias)
	{
		foreach($criterias as $criteria) {
			if ($criteria['type'] == 'specific') {
				$this->db->where($criteria['colName'], $criteria['colValue']);
			} else if ($criteria['type'] == 'similar') {
				$this->db->like($criteria['colName'], $criteria['colValue']);
			} else if ($criteria['type'] == 'in') {
				$this->db->where_in($criteria['colName'], $criteria['colValue']);
			}
		}

		$objects = $this->db->get($this->table_name);
		return $objects->result();
	}

	public function findOneByPK($pkValue)
	{
		$this->db->where($this->primary_key, $pkValue);

		$objects = $this->db->get($this->table_name);
		$results = $objects->result();
		return $results[0];
	}
}
