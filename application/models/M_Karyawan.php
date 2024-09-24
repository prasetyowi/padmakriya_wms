<?php

class M_Karyawan extends CI_Model
{
	const ID_LEVEL_DRIVER = '339D8AC2-C6CE-4B47-9BFC-E372592AF521';

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function Getkaryawan()
	{
		$query = $this->db->query("	select 
										k.karyawan_id, c.client_wms_id, null as unit_mandiri_id, k.depo_id, karyawan_nama, 
										isnull( karyawan_telepon, 'N/A') as karyawan_telepon, isnull( karyawan_email, 'N/A') as karyawan_email,	
										karyawan_tanggal_lahir, kd.karyawan_divisi_id, kl.karyawan_level_id, karyawan_supervisor_id, 
										karyawan_foto, karyawan_digital_signature, karyawan_is_deleted, karyawan_is_aktif,
								
										karyawan_is_client_wms, case when karyawan_is_client_wms = 0 then 'Internal' else c.client_wms_nama end as karyawan_tipe,
										kd.karyawan_divisi_nama, kl.karyawan_level_nama
									from karyawan as k
										inner join karyawan_divisi as kd on kd.karyawan_divisi_id = k.karyawan_divisi_id
										inner join karyawan_level as kl on kl.karyawan_level_id = k.karyawan_level_id
										inner join depo as d on d.depo_id = k.depo_id
										left join client_wms as c on c.client_wms_id = k.client_wms_id
									order by karyawan_is_client_wms, karyawan_tipe, karyawan_level_nama, karyawan_nama");

		if($query->num_rows() == 0)	{	return 0;	}
		else						{	return $query->result_array(); }
	}
	
	public function Getkaryawan_profil_by_karyawan_id( $karyawan_id )
	{
		$query = $this->db->query("	select k.karyawan_nama, 
										case when k.karyawan_is_client_wms = 1 then 'Eksternal' else 'Internal' end as karyawan_tipe,
										k.karyawan_is_client_wms, isnull(c.client_wms_nama, '') as karyawan_perusahaan,
										d.depo_nama, k.karyawan_telepon, kd.karyawan_divisi_nama, kl.karyawan_level_nama,
										isnull(ks.karyawan_nama,'') as karyawan_direct_supervisor,
										case when k.karyawan_is_aktif = 0 then 'Tidak Aktif' else 'Aktif' end as karyawan_status,
										k.karyawan_foto, k.karyawan_digital_signature
									from karyawan as k
										inner join karyawan_divisi as kd on kd.karyawan_divisi_id = k.karyawan_divisi_id
										inner join karyawan_level as kl on kl.karyawan_level_id = k.karyawan_level_id
										inner join depo as d on d.depo_id = k.depo_id
										left join client_wms as c on c.client_wms_id = k.client_wms_id
										left join karyawan as ks on ks.karyawan_id = k.karyawan_supervisor_id
									where k.karyawan_id = '$karyawan_id'");

		if($query->num_rows() == 0)	{	return 0;	}
		else						{	return $query->result_array(); }
	}
	
	public function Getkaryawan_client_wms()
	{
		$this->db	->select("	karyawan_id, client_wms_id, k.unit_mandiri_id, k.depo_id, karyawan_nama, 
								isnull( karyawan_telepon, 'N/A') as karyawan_telepon, isnull( karyawan_email, 'N/A') as karyawan_email,	
								karyawan_tanggal_lahir, kd.karyawan_divisi_id, kl.karyawan_level_id, karyawan_supervisor_id, karyawan_is_client_wms,
								karyawan_foto, karyawan_digital_signature, karyawan_is_deleted, karyawan_is_aktif,
								
								kd.karyawan_divisi_nama, kl.karyawan_level_nama")
					->from("karyawan as k")
					->join("karyawan_divisi as kd","kd.karyawan_divisi_id = k.karyawan_divisi_id","inner")
					->join("karyawan_level as kl","kl.karyawan_level_id = k.karyawan_level_id","inner")
					->join("depo as d","d.depo_id = k.depo_id","inner")
					->where("karyawan_is_client_wms", 1 )
					->order_by("karyawan_level_nama","ASC")
					->order_by("karyawan_nama", "ASC");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	return 0;	}
		else						{	return $query->result_array(); }
	}
	
	public function Getkaryawan_non_client_wms( $karyawan_nama, $karyawan_divisi_id, $karyawan_level_id )
	{
		$query = $this->db	->query("select 
										karyawan_id, client_wms_id, k.unit_mandiri_id, k.depo_id, karyawan_nama, 
										isnull( karyawan_telepon, 'N/A') as karyawan_telepon, isnull( karyawan_email, 'N/A') as karyawan_email,	
										karyawan_tanggal_lahir, kd.karyawan_divisi_id, kl.karyawan_level_id, karyawan_supervisor_id, karyawan_is_client_wms,
										karyawan_foto, karyawan_digital_signature, karyawan_is_deleted, karyawan_is_aktif,
										kd.karyawan_divisi_nama, kl.karyawan_level_nama
									from karyawan as k
										inner join karyawan_divisi as kd on kd.karyawan_divisi_id = k.karyawan_divisi_id
										inner join karyawan_level as kl on kl.karyawan_level_id = k.karyawan_level_id
									where karyawan_is_client_wms = 0
										and dbo.searchstr( karyawan_nama, '$karyawan_nama' ) = 1
										and dbo.searchstr( kd.karyawan_divisi_id, '$karyawan_divisi_id' ) = 1
										and dbo.searchstr( kl.karyawan_level_id, '$karyawan_level_id' ) = 1
									order by karyawan_level_nama, karyawan_nama");

		if($query->num_rows() == 0)	{	return 0;	}
		else						{	return $query->result_array(); }
	}
	
	public function Getkaryawan_by_karyawan_level_id( $karyawan_level_id )
	{
		$this->db->select("	karyawan_id, client_wms_id, unit_mandiri_id, depo_id, karyawan_nama, karyawan_telepon, karyawan_email, 
							karyawan_tanggal_lahir, karyawan_divisi_id, karyawan_level_id, karyawan_supervisor_id, karyawan_is_client_wms,
							karyawan_foto, karyawan_digital_signature, karyawan_is_deleted, karyawan_is_aktif")
				 ->from('karyawan')
				 ->where('karyawan_level_id', $karyawan_level_id)
				 ->order_by("karyawan_nama");
		$query = $this->db->get();

		if($query->num_rows() == 0)	{	return 0;	}
		else						{	return $query->result_array(); }
	}
		
	public function Getkaryawan_by_karyawan_id( $karyawan_id )
	{
		$this->db	->select("	karyawan_id, client_wms_id, unit_mandiri_id, depo_id, karyawan_nama, karyawan_telepon, karyawan_email, 
								karyawan_tanggal_lahir, karyawan_divisi_id, karyawan_level_id, karyawan_supervisor_id, karyawan_is_client_wms,
								karyawan_foto, karyawan_digital_signature, karyawan_is_deleted, karyawan_is_aktif")
					->from("karyawan")
					->where("karyawan_id", $karyawan_id );
		$query = $this->db->get();	
		
		if($query->num_rows() == 0)	{	return 0;	}
		else						{	return $query->result_array(); }

	}	
	
	public function Checkkaryawan_duplicate( $karyawan_nama )
	{
		$this->db	->select("karyawan_id")
					->from("karyawan")
					->where("karyawan_nama", $karyawan_nama );
		$query = $this->db->get();
		if($query->num_rows() == 0)	{	$query = 0;	}
		else						{	$query = 1; }
		
		return $query;
	}
	
	public function Insertkaryawan( $karyawan_id, $client_wms_id, $depo_id, $karyawan_nama, $karyawan_telepon, 
									$karyawan_email, $karyawan_tanggal_lahir, $karyawan_divisi_id, $karyawan_level_id, 
									$karyawan_supervisor_id, $karyawan_is_client_wms, $karyawan_is_aktif )
	{
		$this->db->set("karyawan_id", $karyawan_id);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("karyawan_nama", $karyawan_nama);
		$this->db->set("karyawan_telepon", $karyawan_telepon);
		$this->db->set("karyawan_email", $karyawan_email);
		$this->db->set("karyawan_tanggal_lahir", $karyawan_tanggal_lahir);
		$this->db->set("karyawan_divisi_id", $karyawan_divisi_id);
		$this->db->set("karyawan_level_id", $karyawan_level_id);
		$this->db->set("karyawan_supervisor_id", $karyawan_supervisor_id);
		$this->db->set("karyawan_is_client_wms", $karyawan_is_client_wms);
		$this->db->set("karyawan_foto", null );
		$this->db->set("karyawan_digital_signature", null );
		$this->db->set("karyawan_is_deleted", 0 );
		$this->db->set("karyawan_is_aktif", $karyawan_is_aktif);

		$this->db->insert("karyawan");
		
		$affectedrows = $this->db->affected_rows();
		
		if($affectedrows > 0)	{	$queryinsert = 1;	}
		else					{	$queryinsert = 0;	}
		
		return $queryinsert; 
	}
			
	public function Updatekaryawan( $karyawan_id, $client_wms_id, $depo_id, $karyawan_nama, $karyawan_telepon, 
									$karyawan_email, $karyawan_tanggal_lahir, $karyawan_divisi_id, $karyawan_level_id, 
									$karyawan_supervisor_id, $karyawan_is_client_wms, $karyawan_foto, 
									$karyawan_digital_signature, $karyawan_is_deleted, $karyawan_is_aktif )
	{
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("karyawan_nama", $karyawan_nama);
		$this->db->set("karyawan_telepon", $karyawan_telepon);
		$this->db->set("karyawan_email", $karyawan_email);
		$this->db->set("karyawan_tanggal_lahir", $karyawan_tanggal_lahir);
		$this->db->set("karyawan_divisi_id", $karyawan_divisi_id);
		$this->db->set("karyawan_level_id", $karyawan_level_id);
		$this->db->set("karyawan_supervisor_id", $karyawan_supervisor_id);
		$this->db->set("karyawan_is_client_wms", $karyawan_is_client_wms);
		$this->db->set("karyawan_foto", $karyawan_foto);
		$this->db->set("karyawan_digital_signature", $karyawan_digital_signature);
		$this->db->set("karyawan_is_deleted", 0 );
		$this->db->set("karyawan_is_aktif", $karyawan_is_aktif);

		$this->db->where("karyawan_id", $karyawan_id);
		
		$this->db->update("karyawan");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryupdate = 1;	}
		else					{	$queryupdate = 0;	}
			
		return $queryupdate;
	}		
	
	public function Updatekaryawan_karyawan_foto( $karyawan_id, $karyawan_foto )
	{
		$this->db->set("karyawan_foto", $karyawan_foto);

		$this->db->where("karyawan_id", $karyawan_id);
		
		$this->db->update("karyawan");
		
		$affectedrows = $this->db->affected_rows();
		if($affectedrows > 0)	{	$queryupdate = 1;	}
		else					{	$queryupdate = 0;	}
			
		return $queryupdate;
	}		
		
	public function Updatekaryawan_karyawan_is_aktif($karyawan_id, $karyawan_is_aktif)
	{
		$this->db->set("karyawan_is_aktif", $karyawan_is_aktif);
		
		$this->db->where("karyawan_id", $karyawan_id);
		
		$this->db->update("karyawan");
		
		return 1;
	}
	
	public function Deletekaryawan($karyawan_id)
	{
		$this->db->where("karyawan_id", $karyawan_id);
		
		$this->db->delete("karyawan");
		
		return 1;
	}
	
}
