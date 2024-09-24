<?php

class M_Pengguna_Grup_Aplikasi extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function Getpengguna_grup_aplikasi()
	{
		$this->db	->select("	pga.pengguna_grup_aplikasi_id, pga.pengguna_grup_aplikasi_kode
								pg.pengguna_grup_id, pg.pengguna_grup_nama")
					->from("pengguna_grup_aplikasi as pga")
					->join("pengguna_grup as pg","pg.pengguna_grup_id = pga.pengguna_grup_id","inner")
					->order_by("pengguna_grup_id","ASC")
					->order_by("pengguna_grup_aplikasi_kode","ASC");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
	
	public function Getpengguna_grup_aplikasi_by_pengguna_grup_id( $pengguna_grup_id )
	{
		$query = $this->db->query("	select  pga.pengguna_grup_aplikasi_id, pga.pengguna_grup_aplikasi_kode,
											pg.pengguna_grup_id, pg.pengguna_grup_nama,
											t.aplikasi_kode, t.aplikasi_sign, t.aplikasi_keterangan, 
											t.aplikasi_warna_bg, t.aplikasi_warna_font, t.aplikasi_url
									from pengguna_grup_aplikasi as pga
										inner join pengguna_grup as pg on pg.pengguna_grup_id = pga.pengguna_grup_id
										inner join dbo.getapplicationname() as t on t.aplikasi_kode = pga.pengguna_grup_aplikasi_kode
									where pg.pengguna_grup_id = '$pengguna_grup_id'
										and t.aplikasi_is_aktif = 1
									order by pengguna_grup_id, aplikasi_urut");

		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}
	
	public function Insertpengguna_grup_aplikasi( $pengguna_grup_id, $aplikasi_kode )
	{
		$this->db->set("pengguna_grup_aplikasi_id", "NEWID()", FALSE );
		$this->db->set("pengguna_grup_id", $pengguna_grup_id);
		$this->db->set("pengguna_grup_aplikasi_kode", $aplikasi_kode );
		
		$this->db->insert("pengguna_grup_aplikasi");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryinsert = 1;	}
		else					{	$queryinsert = 0;	}
	
		return $queryinsert;
	}
			
	public function Deletepengguna_grup_aplikasi($pengguna_grup_id)
	{
		$this->db->where("pengguna_grup_id", $pengguna_grup_id);
		
		$this->db->delete("pengguna_grup_aplikasi");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$querydelete = 1;	}
		else					{	$querydelete = 0;	}
	
		return $querydelete;
	}
}