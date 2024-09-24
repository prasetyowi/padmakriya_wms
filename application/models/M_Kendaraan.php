<?php

class M_Kendaraan extends CI_Model
{
	private $table_name = "kendaraan";

    private $primary_key = "kendaraan_id";

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function Get_Kendaraan()
	{
		$this->db	->select("	Kendaraan_ID, Kendaraan_Kode, Kendaraan_Nopol, Kendaraan_Model, Kendaraan_NoRangka, Kendaraan_Warna, 
								Kendaraan_Merk, Kendaraan_Tahun, Kendaraan_Tipe, Kendaraan_TglMasaPajak, Kendaraan_TglMasaSTNK,
								Kendaraan_NoBPKB, KJ_Kode, Kendaraan_KapVol, Kendaraan_KapWeight, um.UnitMandiri_ID, um.UnitMandiri_Name, Kendaraan_IsActive" )
					->from("Kendaraan as k")
					->join("UnitMandiri as um","um.UnitMandiri_ID = k.UnitMandiri_ID","inner")
					->order_by("Kendaraan_Nopol", "ASC")
					->order_by("Kendaraan_NoRangka", "ASC");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
				
	public function Get_Kendaraan_By_Kendaraan_ID( $Kendaraan_ID )
	{
		$this->db	->select("	Kendaraan_ID, Kendaraan_Kode, Kendaraan_Nopol, Kendaraan_Model, Kendaraan_NoRangka, Kendaraan_Warna, 
								Kendaraan_Merk, Kendaraan_Tahun, Kendaraan_Tipe, Kendaraan_TglMasaPajak, Kendaraan_TglMasaSTNK,
								Kendaraan_NoBPKB, KJ_Kode, Kendaraan_KapVol, Kendaraan_KapWeight, um.UnitMandiri_ID, um.UnitMandiri_Name, Kendaraan_IsActive" )
					->from("Kendaraan as k")
					->join("UnitMandiri as um","um.UnitMandiri_ID = k.UnitMandiri_ID","inner")
					->where("Kendaraan_ID", $Kendaraan_ID)
					->order_by("Kendaraan_Nopol", "ASC")
					->order_by("Kendaraan_NoRangka", "ASC");
		$query = $this->db->get();	
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
		
		return $query;
	}
	
	public function Check_Kendaraan_Duplicate_By_Kendaraan_Kode( $Kendaraan_Kode )
	{
		$this->db	->select("Kendaraan_ID")
					->from("Kendaraan")
					->where("Kendaraan_Kode", $Kendaraan_Kode);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Check_Kendaraan_Duplicate_By_Kendaraan_NoPol( $Kendaraan_Nopol )
	{
		$this->db	->select("Kendaraan_ID")
					->from("Kendaraan")
					->where("Kendaraan_Nopol", $Kendaraan_Nopol);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Check_Kendaraan_Duplicate_By_Kendaraan_NoRangka( $Kendaraan_NoRangka )
	{
		$this->db	->select("Kendaraan_ID")
					->from("Kendaraan")
					->where("Kendaraan_NoRangka", $Kendaraan_NoRangka);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Check_Kendaraan_Duplicate_By_Kendaraan_Kode_Others( $Kendaraan_ID, $Kendaraan_Kode )
	{
		$this->db	->select("Kendaraan_ID")
					->from("Kendaraan")
					->where("Kendaraan_Kode", $Kendaraan_Kode)
					->where("Kendaraan_ID <>", $Kendaraan_ID);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Check_Kendaraan_Duplicate_By_Kendaraan_NoPol_Others( $Kendaraan_ID, $Kendaraan_Nopol )
	{
		$this->db	->select("Kendaraan_ID")
					->from("Kendaraan")
					->where("Kendaraan_Nopol", $Kendaraan_Nopol)
					->where("Kendaraan_ID <>", $Kendaraan_ID);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Check_Kendaraan_Duplicate_By_Kendaraan_NoRangka_Others( $Kendaraan_ID, $Kendaraan_NoRangka )
	{
		$this->db	->select("Kendaraan_ID")
					->from("Kendaraan")
					->where("Kendaraan_NoRangka", $Kendaraan_NoRangka)
					->where("Kendaraan_ID <>", $Kendaraan_ID);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	/*
	public function Check_Kendaraan_IsUsed( $Kendaraan_ID )
	{
		$this->db	->select("Kendaraan_ID")
					->from("Kendaraan")
					->where("Kendaraan_ID", $Kendaraan_ID);
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 1;	}
		else						{	$query = 0; }
		
		return $query;
	}
	*/
	public function Insert_Kendaraan( 	$Kendaraan_Kode, $Kendaraan_Nopol, $Kendaraan_Model, $Kendaraan_NoRangka, $Kendaraan_Warna, 
										$Kendaraan_Merk, $Kendaraan_Tahun, $Kendaraan_Tipe, $Kendaraan_TglMasaPajak, $Kendaraan_TglMasaSTNK,
										$Kendaraan_NoBPKB, $KJ_Kode, $Kendaraan_KapVol, $Kendaraan_KapWeight, $UnitMandiri_ID,  
										$Kendaraan_IsActive )
	{
		$this->db->set("Kendaraan_ID", "NEWID()", FALSE);
		$this->db->set("Kendaraan_Kode", $Kendaraan_Kode);
		$this->db->set("Kendaraan_Nopol", $Kendaraan_Nopol);
		$this->db->set("Kendaraan_Model", $Kendaraan_Model);
		$this->db->set("Kendaraan_NoRangka", $Kendaraan_NoRangka);
		$this->db->set("Kendaraan_Warna", $Kendaraan_Warna);
		$this->db->set("Kendaraan_Merk", $Kendaraan_Merk);
		$this->db->set("Kendaraan_Tahun", $Kendaraan_Tahun);
		$this->db->set("Kendaraan_Tipe", $Kendaraan_Tipe);
		$this->db->set("Kendaraan_TglMasaPajak", $Kendaraan_TglMasaPajak);
		$this->db->set("Kendaraan_TglMasaSTNK", $Kendaraan_TglMasaSTNK);
		$this->db->set("Kendaraan_NoBPKB", $Kendaraan_NoBPKB);
		$this->db->set("KJ_Kode", $KJ_Kode);
		$this->db->set("Kendaraan_KapVol", $Kendaraan_KapVol);
		$this->db->set("Kendaraan_KapWeight", $Kendaraan_KapWeight);
		$this->db->set("UnitMandiri_ID", $UnitMandiri_ID);
		$this->db->set("Kendaraan_IsActive", $Kendaraan_IsActive);
		
		$this->db->insert("Kendaraan");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryinsert = 1;	}
		else					{	$queryinsert = 0;	}
	
		return $queryinsert;
	}
			
	public function Update_Kendaraan( 	$Kendaraan_ID, $Kendaraan_Kode, $Kendaraan_Nopol, $Kendaraan_Model, $Kendaraan_NoRangka, $Kendaraan_Warna, 
										$Kendaraan_Merk, $Kendaraan_Tahun, $Kendaraan_Tipe, $Kendaraan_TglMasaPajak, $Kendaraan_TglMasaSTNK,
										$Kendaraan_NoBPKB, $KJ_Kode, $Kendaraan_KapVol, $Kendaraan_KapWeight, $UnitMandiri_ID, 
										$Kendaraan_IsActive )
	{
		$this->db->set("Kendaraan_Kode", $Kendaraan_Kode);
		$this->db->set("Kendaraan_Nopol", $Kendaraan_Nopol);
		$this->db->set("Kendaraan_Model", $Kendaraan_Model);
		$this->db->set("Kendaraan_NoRangka", $Kendaraan_NoRangka);
		$this->db->set("Kendaraan_Warna", $Kendaraan_Warna);
		$this->db->set("Kendaraan_Merk", $Kendaraan_Merk);
		$this->db->set("Kendaraan_Tahun", $Kendaraan_Tahun);
		$this->db->set("Kendaraan_Tipe", $Kendaraan_Tipe);
		$this->db->set("Kendaraan_TglMasaPajak", $Kendaraan_TglMasaPajak);
		$this->db->set("Kendaraan_TglMasaSTNK", $Kendaraan_TglMasaSTNK);
		$this->db->set("Kendaraan_NoBPKB", $Kendaraan_NoBPKB);
		$this->db->set("KJ_Kode", $KJ_Kode);
		$this->db->set("Kendaraan_KapVol", $Kendaraan_KapVol);
		$this->db->set("Kendaraan_KapWeight", $Kendaraan_KapWeight);
		$this->db->set("UnitMandiri_ID", $UnitMandiri_ID);
		$this->db->set("Kendaraan_IsActive", $Kendaraan_IsActive);
		
		$this->db->where("Kendaraan_ID", $Kendaraan_ID );
		
		$this->db->update("Kendaraan");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryupdate = 1;	}
		else					{	$queryupdate = 0;	}
	
		return $queryupdate;
	}	
		
	public function Delete_Kendaraan($Target_Kendaraan_ID)
	{
		$this->db->trans_begin();
		
		$this->db->where("Kendaraan_ID", $Target_Kendaraan_ID);
		$this->db->delete("Kendaraan");
		
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
		$this->db->where('kendaraan_is_deleted', '0');
		$this->db->from($this->table_name);
		return $this->db->count_all_results();
    }

	public function countByCriteria($criterias)
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

		$this->db->from($this->table_name);
		return $this->db->count_all_results();
	}

    public function findAll()
    {
		$this->db->where('kendaraan_is_deleted', '0');

        $objects = $this->db->get($this->table_name);
        return $objects->result();
    }

	public function findManyByCriteria($criterias, $asObject = true)
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
		return $asObject ? $objects->result() : $objects->result_array();
	}

	public function findOneByPK($pkValue)
	{
		$this->db->where($this->primary_key, $pkValue);

		$objects = $this->db->get($this->table_name);
		$results = $objects->result();
		return $results[0];
	}
}
