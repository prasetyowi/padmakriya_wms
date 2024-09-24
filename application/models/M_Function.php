<?php

class M_Function extends CI_Model
{
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

	public function Get_Function_TipeKurir()
	{
		$this->db	->select("Nama, IsActive") 
					->from("dbo.TipeKurir()")
					->where("IsActive", 1 );
		$queryvrbl = $this->db->get();
	
		if(	$queryvrbl->num_rows() == 0):
			$queryvrbl = 0;
		else:
			$queryvrbl = $queryvrbl->result_array();
		endif;
		
		return $queryvrbl;
	}
	
	public function Get_Function_GroupKategori()
	{
		$this->db	->select("Nama, IsActive") 
					->from("dbo.GroupKategori()")
					->where("IsActive", 1 );
		$queryvrbl = $this->db->get();
	
		if(	$queryvrbl->num_rows() == 0):
			$queryvrbl = 0;
		else:
			$queryvrbl = $queryvrbl->result_array();
		endif;
		
		return $queryvrbl;
	}
	
	public function Get_Function_GetApplicationName()
	{
		$queryvrbl = $this->db->query("	select aplikasi_urut, aplikasi_kode, aplikasi_sign, aplikasi_keterangan, 
											aplikasi_warna_bg, aplikasi_warna_font, aplikasi_url
										from dbo.getapplicationname()
										where aplikasi_is_aktif = 1
										order by aplikasi_urut");

		if(	$queryvrbl->num_rows() == 0):
			$queryvrbl = 0;
		else:
			$queryvrbl = $queryvrbl->result_array();
		endif;
		
		return $queryvrbl;
	}
	
	public function Get_Function_GetApplicationName_By_Aplikasi_Kode( $aplikasi_kode )
	{
		$queryvrbl = $this->db->query("	select aplikasi_urut, aplikasi_kode, aplikasi_sign, aplikasi_keterangan, 
											aplikasi_warna_bg, aplikasi_warna_font, aplikasi_url
										from dbo.getapplicationname()
										where aplikasi_is_aktif = 1 and aplikasi_kode = '$aplikasi_kode'
										order by aplikasi_urut");

		if(	$queryvrbl->num_rows() == 0):
			$queryvrbl = 0;
		else:
			$queryvrbl = $queryvrbl->result_array();
		endif;
		
		return $queryvrbl;
	}
	
	public function Get_Function_JenisChannel()
	{
		$this->db	->select("jenis, grup") 
					->from("dbo.getjenischannel()")
					->order_by("grup", "ASC")
					->order_by("jenis", "ASC");
		$queryvrbl = $this->db->get();
	
		if(	$queryvrbl->num_rows() == 0):
			$queryvrbl = 0;
		else:
			$queryvrbl = $queryvrbl->result_array();
		endif;
		
		return $queryvrbl;
	}
	
	public function Get_Function_SubKategori()
	{
		$this->db	->select("sub_kategori_nama, sub_kategori_nilai, sub_kategori_nilai_parent") 
					->from("dbo.getsubkategori()")
					->order_by("sub_kategori_nilai");
		$queryvrbl = $this->db->get();
	
		if(	$queryvrbl->num_rows() == 0):
			$queryvrbl = 0;
		else:
			$queryvrbl = $queryvrbl->result_array();
		endif;
		
		return $queryvrbl;
	}
	
	public function Get_Function_getsatuanunitbarang()
	{
		$this->db	->select("satuan_kode, satuan_nama, satuan_grup") 
					->from("dbo.getsatuanunitbarang()")
					->order_by("satuan_kode");
		$queryvrbl = $this->db->get();
	
		if(	$queryvrbl->num_rows() == 0):
			$queryvrbl = 0;
		else:
			$queryvrbl = $queryvrbl->result_array();
		endif;
		
		return $queryvrbl;
	}

	public function Get_GudangVA(){
		$queryvrbl = $this->db->query("SELECT
							    GudangRak.GudangRak_ID,
							    GudangRak.GudangRak_Nama,
								gudang.nama_gudang,
								GUDANG1.GudangVA_nama
							FROM GudangRak
							LEFT JOIN gudang
							  ON GudangRak.Gudang_ID = gudang.Gudang_ID
							LEFT JOIN GUDANG1
							  ON GudangRak.GudangVA_ID  = GUDANG1.GUDANGVA_ID
							ORDER BY gudang.nama_gudang ASC");
	
		if(	$queryvrbl->num_rows() == 0):
			$queryvrbl = 0;
		else:
			$queryvrbl = $queryvrbl->result_array();
		endif;
		
		return $queryvrbl;
	}

	public function Get_NewID(){
		$queryvrbl = $this->db->query("SELECT NEWID() AS kode");
	
		if(	$queryvrbl->num_rows() == 0):
			$queryvrbl = 0;
		else:
			$queryvrbl = $queryvrbl->result_array();
		endif;
		
		return $queryvrbl;
	}
}