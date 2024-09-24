<?php

class M_Vrbl extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function Get_Vrbl_By_Param($param)
	{
		$this->db->select("*")
			->from("vrbl")
			->where("vrbl_param", $param);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Vrbl_Assets_Url()
	{
		$this->db->select("vrbl_ket_patch")
			->from("vrbl")
			->where("vrbl_param", 'ASSETS_URL' );
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Vrbl_Assets_Upload_Url()
	{
		$this->db->select("vrbl_ket_patch")
			->from("vrbl")
			->where("vrbl_param", 'ASSETS_UPLOAD_URL' );
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Kons($Param)
	{
		$this->db->select("Datastring")
			->from("Kons")
			->where("Param", $Param);
		$queryvrbl = $this->db->get();

		if ($queryvrbl->num_rows() == 0) :
			$queryvrbl = 0;
		else :
			$queryvrbl = $queryvrbl->result_array();
		endif;

		return $queryvrbl;
	}

	public function Get_NewID()
	{
		$querybatuid = $this->db->query("select newid() as NEW_ID");

		return $querybatuid->result_array();
	}

	public function Get_Segment()
	{
		$querybatuid = $this->db->query("	select Segment_Kode, Segment_Nama
											from dbo.GetSegment()");

		return $querybatuid->result_array();
	}

	public function Get_Image_Size()
	{
		$querybatuid = $this->db->query("select isnull( dbo.GetGlobalValueFloat('IMAGE_SIZE_WIDTH'), 0 ) as Width, 
												isnull( dbo.GetGlobalValueFloat('IMAGE_SIZE_HEIGHT'), 0 ) as Height", FALSE);

		return $querybatuid->result_array();
	}

	public function Get_Image_Employee_Size()
	{
		$querybatuid = $this->db->query("select isnull( dbo.GetGlobalValueFloat('IMAGE_SIZE_EMPLOYEE_WIDTH'), 0 ) as Width, 
												isnull( dbo.GetGlobalValueFloat('IMAGE_SIZE_EMPLOYEE_HEIGHT'), 0 ) as Height", FALSE);

		return $querybatuid->result_array();
	}

	public function Get_Image_Depo_Size()
	{
		$querybatuid = $this->db->query("select isnull( dbo.GetGlobalValueFloat('IMAGE_SIZE_DEPO_LENGTH'), 0 ) as Length, 
												isnull( dbo.GetGlobalValueFloat('IMAGE_SIZE_DEPO_WIDTH'), 0 ) as Width", FALSE);

		return $querybatuid->result_array();
	}

	public function Get_Jumlah_Maksimal_Varian_Ragam()
	{
		$querybatuid = $this->db->query("select isnull( dbo.GetGlobalValueFloat('JUMLAH_MAKSIMAL_VARIAN_RAGAM'), 0 ) as MaksimalVarianRagam", FALSE);

		return $querybatuid->result_array();
	}

	public function Get_Varian_Optional_Max_Limit()
	{
		$querybatuid = $this->db->query("select isnull( dbo.GetGlobalValueFloat('VARIAN_OPTIONAL_MAX_LIMIT'), 0 ) as VarianOptionalMaxLimit", FALSE);

		return $querybatuid->result_array();
	}

	public function Get_Amount_Header_Import_SKU()
	{
		$querybatuid = $this->db->query("select isnull( dbo.GetGlobalValueFloat('AMOUNT_HEADER_IMPORT_SKU'), 0 ) as AmountHeader", FALSE);

		return $querybatuid->result_array();
	}

	public function Get_TipeKurir()
	{
		$query = $this->db->query("select * from dbo.TipeKurir()", FALSE);

		return $query->result_array();
	}
	public function Get_Kode($vrbl_param)
	{
		$query = $this->db->select('*')
			->from('vrbl')
			->where('vrbl_param', $vrbl_param)
			->get();

		return $query->row();
	}
}
