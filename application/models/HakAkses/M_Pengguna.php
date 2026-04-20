<?php

class M_Pengguna extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function Getpengguna()
	{
		$this->db	->select("	pengguna_id, pengguna_kode, pengguna_username,	pengguna_password,	pg.pengguna_grup_id, pg.pengguna_grup_nama,
								pengguna_is_aktif,	pengguna_who_create,	pengguna_who_create_id,	pengguna_date_create,	
								pengguna_who_update	pengguna_who_update_id,	pengguna_date_update,	
								r.region_id, r.region_nama")
					->from("pengguna as p")
					->join("pengguna_grup as pg","pg.pengguna_grup_id = p.pengguna_grup_id","inner")
					->join("region as r","r.Region_id = p.region_id","inner")
					->where("pg.pengguna_grup_is_dewa", 0 )
					->order_by("pengguna_username");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
			
	public function Getpengguna_checker()
	{
		$this->db	->select("	pengguna_id, pengguna_kode, pengguna_username,	pengguna_password,	pg.pengguna_grup_id, pg.pengguna_grup_nama,
								pengguna_is_aktif,	pengguna_who_create,	pengguna_who_create_id,	pengguna_date_create,	
								pengguna_who_update	pengguna_who_update_id,	pengguna_date_update,	
								r.region_id, r.region_nama")
					->from("pengguna as p")
					->join("pengguna_grup as pg","pg.pengguna_grup_id = p.pengguna_grup_id","inner")
					->join("region as r","r.Region_id = u.region_id","inner")
					->where("ug.pengguna_grup_nama", "CHECKER")
					->where("pg.pengguna_grup_is_dewa", 0 )
					->order_by("pengguna_username");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
				
	public function Getpengguna_by_pengguna_id( $pengguna_id )
	{
		$this->db	->select("	pengguna_id, pengguna_kode, pengguna_username,	pengguna_password,	p.pengguna_grup_id,
								pengguna_is_aktif,	pengguna_who_create,	pengguna_who_create_id,	pengguna_date_create,	
								pengguna_who_update,	pengguna_who_update_id,	pengguna_date_update,	
								r.region_id, r.region_nama, p.karyawan_id")
					->from("pengguna as p")
					->join("pengguna_grup as pg","pg.pengguna_grup_id = p.pengguna_grup_id","inner")
					->join("region as r","r.region_id = p.region_id","inner")
					->where("pg.pengguna_grup_is_dewa", 0 )
					->where("pengguna_id", $pengguna_id);
		$query = $this->db->get();	
		
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = $query->result_array(); }
		
		return $query;
	}		
	
	public function Getpengguna_by_pengguna_username( $pengguna_username )
	{
		$this->db	->select("	pengguna_id, pengguna_kode, pengguna_username,	pengguna_password,	p.pengguna_grup_id,
								pengguna_is_aktif,	pengguna_who_create,	pengguna_who_create_id,	pengguna_date_create,	
								pengguna_who_update	pengguna_who_update_id,	pengguna_date_update, 
								
								pg.pengguna_grup_is_dewa,
								k.karyawan_id, k.karyawan_level_id, k.karyawan_divisi_id, k.karyawan_nama, k.karyawan_foto,
								r.region_id, r.region_nama")
					->from("pengguna as p")
					->join("pengguna_grup as pg","pg.pengguna_grup_id = p.pengguna_grup_id","inner")
					->join("karyawan as k","k.karyawan_id = p.karyawan_id", "left")
					->join("region as r","r.Region_id = p.region_id","inner")
					->where("pengguna_username", $pengguna_username);
		$query = $this->db->get();	
		
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = $query->result_array(); }
		
		return $query;
	}
	
	public function Getpengguna_check( $pengguna_username )
	{	
		$this->db	->select("pengguna_id, pengguna_password, pengguna_username")
					->from("pengguna as p") 
					->join("pengguna_grup as pg","pg.pengguna_grup_id = p.pengguna_grup_id","inner")
					->where("pengguna_username",$pengguna_username);
		
		$queryvalidation = $this->db->get();
		
		if( $queryvalidation->num_rows() == 0 ):
			$queryvalidation = 0;
		else:
			$queryvalidation = $queryvalidation->result_array();
		endif;
		
		return $queryvalidation;
	}
	
	public function Getpengguna_administrator_check( $administrator_kode )
	{	
		$this->db	->select("vrbl_backend_id, vrbl_backend_kode, vrbl_backend_desc")
					->from("vrbl_backend") 
					->where("vrbl_backend_param", 'LOGIN_ADMINISTRATOR')
					->where("vrbl_backend_kode", $administrator_kode );
		
		$queryvalidation = $this->db->get();
		
		if( $queryvalidation->num_rows() == 0 ):
			$queryvalidation = 0;
		else:
			$queryvalidation = $queryvalidation->result_array();
		endif;
		
		return $queryvalidation;
	}
	
	public function Getpengguna_by_karyawan_id( $karyawan_id )
	{
		$this->db	->select("	pengguna_id, pengguna_kode, pengguna_username, pengguna_password, pengguna_grup_id,
								pengguna_is_aktif, pengguna_who_create, pengguna_who_create_id, pengguna_date_create, 
								pengguna_who_update	pengguna_who_update_id,	pengguna_date_update,	
								region_id")
					->from("pengguna")
					->where("karyawan_id", $karyawan_id );
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = $query->result_array(); }
		
		return $query;
	}
	
	public function Checkpengguna_pengguna_group_is_used( $pengguna_id )
	{
		$this->db	->select("pengguna_kode")
					->from("pengguna")
					->where("pengguna_id", $pengguna_id );
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Checkpengguna_duplicate_kode( $pengguna_kode )
	{
		$this->db	->select("pengguna_kode")
					->from("pengguna")
					->where("pengguna_kode", $pengguna_kode);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Checkpengguna_duplicate_username( $pengguna_username )
	{
		$this->db	->select("pengguna_username")
					->from("pengguna")
					->where("pengguna_username", $pengguna_username);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Checkpengguna_duplicate_kode_others( $pengguna_id, $pengguna_kode )
	{
		$this->db	->select("pengguna_kode")
					->from("pengguna")
					->where("pengguna_kode", $pengguna_kode)
					->where("pengguna_id <>", $pengguna_id);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Checkpengguna_duplicate_username_others( $pengguna_id, $pengguna_username )
	{
		$this->db	->select("pengguna_username")
					->from("pengguna")
					->where("pengguna_username", $pengguna_username)
					->where("pengguna_id <>", $pengguna_id);
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Checkpengguna_password( $pengguna_id, $Password )
	{
		$this->db	->select("pengguna_kode")
					->from("pengguna")
					->where("pengguna_id", $pengguna_id )
					->where("Password", md5($Password) );
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Insertpengguna( $karyawan_id, $pengguna_kode, $pengguna_username, $pengguna_password, $pengguna_grup_id, $pengguna_is_aktif, $region_id )
	{
		$options = 
		[
			'cost' => 11
		];
				

		$this->db->set("pengguna_id", "NEWID()", FALSE);
		$this->db->set("pengguna_kode", $pengguna_kode);
		$this->db->set("pengguna_username", $pengguna_username);
		$this->db->set("pengguna_password", password_hash( $pengguna_password, PASSWORD_BCRYPT, $options ) );
		$this->db->set("pengguna_grup_id", $pengguna_grup_id);
		$this->db->set("pengguna_is_aktif", $pengguna_is_aktif, FALSE);
		$this->db->set("region_id", $region_id);
		$this->db->set("karyawan_id", $karyawan_id );
		
		$this->db->set("pengguna_who_create", $this->session->userdata('pengguna_username') );
		$this->db->set("pengguna_who_create_id", $this->session->userdata('pengguna_id') );
		$this->db->set("pengguna_date_create", "GETDATE()", FALSE );
	
		$this->db->insert("pengguna");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryinsert = 1;	}
		else					{	$queryinsert = 0;	}
			
		return $queryinsert;
	}
		
	public function Updatepengguna( $karyawan_id, $pengguna_id, $pengguna_kode, $pengguna_username, $pengguna_password, $pengguna_grup_id, $pengguna_is_aktif, $region_id )
	{
		$options = 
		[
			'cost' => 11
		];

		$this->db->set("pengguna_kode", $pengguna_kode);
		$this->db->set("pengguna_username", $pengguna_username);
		$this->db->set("pengguna_password", password_hash( $pengguna_password, PASSWORD_BCRYPT, $options ) );
		$this->db->set("pengguna_grup_id", $pengguna_grup_id);
		$this->db->set("pengguna_is_aktif", $pengguna_is_aktif, FALSE);
		$this->db->set("region_id", $region_id);
		
		$this->db->set("pengguna_who_update", $this->session->userdata('pengguna_username') );
		$this->db->set("pengguna_who_update_id", $this->session->userdata('pengguna_id') );
		$this->db->set("pengguna_date_update", "GETDATE()", FALSE );
		
		$this->db->where("pengguna_id", $pengguna_id);
		
		$this->db->update("pengguna");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryinsert = 1;	}
		else					{	$queryinsert = 0;	}
			
		return $queryinsert;
	}
				
	public function Updatepengguna_pengguna_default_bahasa( $pengguna_id, $pengguna_default_bahasa )
	{
		$this->db->set("pengguna_default_bahasa", $pengguna_default_bahasa);
		
		$this->db->where("pengguna_id", $pengguna_id);
		
		$this->db->update("pengguna");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryinsert = 1;	}
		else					{	$queryinsert = 0;	}
			
		return $queryinsert;
	}
		
	public function Updatepengguna_checker( $PenggunaID, $PenggunaKode, $PenggunaName, $Password, $IsActive, $Region_ID, $UnitMandiri_ID, $S_PenggunaName, $S_PenggunaID)
	{
		$this->db->set("pengguna_kode", $PenggunaKode);
		$this->db->set("pengguna_username", $PenggunaName);
		$this->db->set("pengguna_password", md5($Password) );
		$this->db->set("pengguna_is_aktif", $IsActive, FALSE);
		$this->db->set("region_id", $Region_ID);
		$this->db->set("UnitMandiri_ID", $UnitMandiri_ID );
		
		$this->db->set("WhoUpdate", $S_PenggunaName);
		$this->db->set("WhoUpdateID", $S_PenggunaID);
		$this->db->set("DateUpdate", "GETDATE()", FALSE);
		
		$this->db->where("pengguna_id", $PenggunaID);
		
		$this->db->update("Pengguna_Web");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryupdate = 1;	}
		else					{	$queryupdate = 0;	}
			
		return $queryupdate;
	}		
		
	public function Update_Pengguna_Password( $PenggunaKode, $NewPassword, $S_PenggunaName, $S_PenggunaID )
	{
		$this->db->set("Password", md5( $NewPassword ) );
		
		$this->db->set("WhoUpdate", $S_PenggunaName);
		$this->db->set("WhoUpdateID", $S_PenggunaID);
		$this->db->set("DateUpdate", "GETDATE()", FALSE);
		
		$this->db->where("PenggunaID", $S_PenggunaID);
		
		$this->db->update("Pengguna_Web");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryupdate = 1;	}
		else					{	$queryupdate = 0;	}
			
		return $queryupdate;
	}
	
	public function Update_Pengguna_Without_Password( $PenggunaID, $PenggunaKode, $PenggunaName, $PenggunaGroupID, $IsActive, $Region_ID, $UnitMandiri_ID, $S_PenggunaName, $S_PenggunaID)
	{
		//$this->db->set("PenggunaKode", $PenggunaKode);
		$this->db->set("PenggunaName", $PenggunaName);
		$this->db->set("USerGroupID", $PenggunaGroupID);
		$this->db->set("IsActive", $IsActive, FALSE);
		$this->db->set("Region_ID", $Region_ID);
		$this->db->set("UnitMandiri_ID", $UnitMandiri_ID );
		
		$this->db->set("WhoUpdate", $S_PenggunaName);
		$this->db->set("WhoUpdateID", $S_PenggunaID);
		$this->db->set("DateUpdate", "GETDATE()", FALSE);
		
		$this->db->where("PenggunaID", $PenggunaID);
		
		$this->db->update("Pengguna_Web");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryupdate = 1;	}
		else					{	$queryupdate = 0;	}
			
		return $queryupdate;
	}		
	
	public function Update_PenggunaChecker_Without_Password( $PenggunaID, $PenggunaKode, $PenggunaName, $IsActive, $Region_ID, $UnitMandiri_ID, $S_PenggunaName, $S_PenggunaID)
	{
		//$this->db->set("PenggunaKode", $PenggunaKode);
		$this->db->set("PenggunaName", $PenggunaName);
		$this->db->set("IsActive", $IsActive, FALSE);
		$this->db->set("Region_ID", $Region_ID);
		$this->db->set("UnitMandiri_ID", $UnitMandiri_ID );
		
		$this->db->set("WhoUpdate", $S_PenggunaName);
		$this->db->set("WhoUpdateID", $S_PenggunaID);
		$this->db->set("DateUpdate", "GETDATE()", FALSE);
		
		$this->db->where("PenggunaID", $PenggunaID);
		
		$this->db->update("Pengguna_Web");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryupdate = 1;	}
		else					{	$queryupdate = 0;	}
			
		return $queryupdate;
	}		
	
	public function Delete_Pengguna($Target_PenggunaID)
	{
		$this->db->where("PenggunaID", $Target_PenggunaID);
		
		$this->db->delete("Pengguna_Web");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$querydelete = 1;	}
		else					{	$querydelete = 0;	}
	
		return $querydelete;
	}

	private $table_name = 'pengguna';
	private $primary_key = 'pengguna_id';

	public function findOneByPK($pkValue)
	{
		$this->db->where($this->primary_key, $pkValue);

		$objects = $this->db->get($this->table_name);
		$results = $objects->result();
		return $results[0];
	}
}
