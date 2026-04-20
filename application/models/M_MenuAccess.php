<?php

class M_MenuAccess extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function Get_MenuAccess_From_Menu_Global()
	{
		$this->db	->select("menu_kode, menu_link, menu_name, menu_class, menu_parent, menu_order, menu_c, menu_r, menu_u, menu_d")
					->from("menu_web")
					->order_by("menu_kode");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}

	public function Get_MenuAccess_From_Menu()
	{
		$this->db	->select("menu_kode, menu_link, menu_name, menu_class, menu_parent, menu_order, menu_c, menu_r, menu_u, menu_d")
					->from("menu_web")
					->where("menu_application", $this->session->userdata('Mode') )
					->order_by("menu_kode");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}

	public function Get_MenuAccess_By_UserGroupID_MenuLink( $pengguna_id, $menu_link )
	{
		$this->db	->select("	m.menu_c as MenuC, m.menu_r as MenuR, m.menu_u as MenuU, m.menu_d as MenuD, 
								ma.status_c as StatusC, ma.status_r as StatusR, ma.status_u as StatusU, ma.status_d as StatusD")
					->from("menu_access_web as ma")
					->join("menu_web as m","m.menu_kode = ma.menu_kode","inner")
					->where("ma.pengguna_grup_id", $pengguna_id )
					->where("m.menu_link", $menu_link )
					->order_by("m.menu_kode");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;	
	}
	
	public function Get_MenuAccess_By_UserGroupID( $pengguna_grup_id )
	{
		$this->db	->select("m.menu_kode, m.menu_link, m.menu_name, m.menu_class, m.menu_parent, m.menu_order, status_c, status_r, status_u, status_d, menu_c, menu_r, menu_u, menu_d")
					->from("menu_web as m")
					->join("menu_access_web as ma","m.menu_kode = ma.menu_kode","left")
					->where("ma.pengguna_grup_id", $pengguna_grup_id)
					->order_by("m.menu_kode");
		$query = $this->db->get();	
		
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = $query->result_array(); }
		
		return $query;
	}
	
	public function Insert_MenuAccess( $pengguna_grup_id, $menu_kode, $menu_c, $menu_r, $menu_u, $menu_d )
	{
		$this->db	->set("menu_access_id", "NEWID()", FALSE );
		$this->db	->set("pengguna_grup_id", $pengguna_grup_id);
		$this->db	->set("menu_kode",$menu_kode);
		$this->db	->set("status_c", $menu_c);
		$this->db	->set("status_r", $menu_r);
		$this->db	->set("status_u", $menu_u);
		$this->db	->set("status_d", $menu_d);
		$this->db->insert("menu_access_web");
		
		$affectedrows = $this->db->affected_rows();
		if( $affectedrows == 0 ){ $query = 0; }
		else					{ $query = 1; }

		return $query;
	}
	
	public function Update_MenuAccess( $pengguna_grup_id, $menu_kode, $menu_c, $menu_r, $menu_u, $menu_d )
	{
		$this->db	->set("status_c", $menu_c);
		$this->db	->set("status_r", $menu_r);
		$this->db	->set("status_u", $menu_u);
		$this->db	->set("status_d", $menu_d);
		$this->db->where("menu_kode", $menu_kode);
		$this->db->where("pengguna_grup_id", $pengguna_grup_id);
		$this->db->update("menu_access_web");
		
		$affectedrows = $this->db->affected_rows();
		if( $affectedrows == 0 ){ $query = 0; }
		else					{ $query = 1; }

		return $query;
	}
		
	public function Delete_MenuAccess($pengguna_grup_id)
	{
		$this->db->where("pengguna_grup_id", $pengguna_grup_id);
		
		$this->db->delete("menu_access_web");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$querydelete = 1;	}
		else					{	$querydelete = 0;	}
	
		return $querydelete;
	}
}