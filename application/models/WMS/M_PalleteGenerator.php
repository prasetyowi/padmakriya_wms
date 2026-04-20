<?php

class M_PalleteGenerator extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function getDataPerusahaan()
	{
		// $query = $this->db->select("client_wms_id, client_wms_nama")
		// 	->from("client_wms")
		// 	->where("client_wms_is_aktif", 1)
		// 	->get()->result();

		$query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0");

		return $query->result();
	}

	public function getPerusahaanID($id)
	{
		$query = $this->db->select("client_wms_id, client_wms_nama")
			->from("client_wms")
			->where("client_wms_is_aktif", 1)
			->where("client_wms_id", $id)
			->get()->row();

		return $query;
	}

	public function getDataPrincipleByClientWmsID($id)
	{
		$query = $this->db->select("b.principle_id as id, b.principle_kode as kode, b.principle_nama as nama")
			->from("client_wms_principle a")
			->join("principle b", "a.principle_id = b.principle_id")
			->where("a.client_wms_id", $id)->get()->result();

		return $query;
	}

	public function getPrincipleByClientWmsID($id)
	{
		$query = $this->db->select("b.principle_id as id, b.principle_kode as kode, b.principle_nama as nama")
			->from("client_wms_principle a")
			->join("principle b", "a.principle_id = b.principle_id")
			->where("b.principle_id", $id)
			->get()->row();

		return $query;
	}


	public function getDataSuratJalan($perusahaan_sj, $principle_sj)
	{
		$query = $this->db->select("sj.penerimaan_surat_jalan_id as sj_id,
		FORMAT(sj.penerimaan_surat_jalan_tgl, 'dd-MM-yyyy') as tgl,
		sj.penerimaan_surat_jalan_kode as sj_kode,
		sj.penerimaan_surat_jalan_no_sj as no_sj,
		sj.penerimaan_surat_jalan_status as status,
		ISNULL(sj.penerimaan_surat_jalan_keterangan,'') as keterangan,
		cw.client_wms_nama as pt,
		p.principle_kode as p_kode,
		p.principle_nama as p_nama,
		pt.penerimaan_tipe_nama as tipe")
			->from("penerimaan_surat_jalan sj")
			->join("principle p", "sj.principle_id = p.principle_id", "left")
			->join("client_wms cw", "sj.client_wms_id = cw.client_wms_id", "left")
			->join("penerimaan_tipe pt", "sj.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->where("sj.depo_id", $this->session->userdata('depo_id'))
			->where("sj.client_wms_id", $perusahaan_sj)
			->where("sj.principle_id", $principle_sj)
			->where_in("sj.penerimaan_surat_jalan_status", array("Open", "In Progress", "Partially Received"))->get()->result();

		return $query;
	}

	public function getDataSuratJalanPallete($id)
	{

		$query = $this->db->select("sj.penerimaan_surat_jalan_id as sj_id,
		sj.penerimaan_surat_jalan_kode as sj_kode, sj.penerimaan_surat_jalan_no_sj as sj_eksternal")
			->from("penerimaan_surat_jalan sj")
			->where_in("sj.penerimaan_surat_jalan_id", $id)->get()->result();

		return $query;
	}

	public function get_print_header($id)
	{

		$query = $this->db->select("pallet_generate_detail2_kode as pg_kode")
			->from("pallet_generate_detail2")
			->where_in("pallet_generate_detail2_id", $id)
			->order_by('pallet_generate_detail2_kode', 'ASC')->get()->result();

		return $query;
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function savePalleteGenerate($pg_id, $id_perusahaan, $id_principle, $user)
	{
		$this->db->set("pallet_generate_id", $pg_id);
		if ($id_perusahaan != '' && $id_principle != '') {
			$this->db->set("client_wms_id", $id_perusahaan);
			$this->db->set("principle_id", $id_principle);
		}
		$this->db->set("pallet_generate_tgl_create", "GETDATE()", false);
		$this->db->set("pallet_generate_who_create", $user);

		$this->db->set("pallet_generate_tgl_update", "GETDATE()", false);
		$this->db->set("pallet_generate_who_update", $user);

		$this->db->set("depo_id", $this->session->userdata('depo_id'));

		$this->db->insert("pallet_generate");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function savePalleteGenerateDetail($pg_detail_id, $pg_id, $sj_id)
	{
		$this->db->set("pallet_generate_detail_id", $pg_detail_id);
		$this->db->set("pallet_generate_id", $pg_id);
		$this->db->set("penerimaan_surat_jalan_id", $sj_id);

		$this->db->insert("pallet_generate_detail");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function getJenisPallet()
	{
		$query = $this->db->select("pallet_jenis_id, pallet_jenis_kode")
			->from("pallet_jenis")
			->where("pallet_jenis_is_default", 1)
			->get()->row();

		return $query;
	}

	public function getJenisKeranjang()
	{
		$query = $this->db->select("pallet_jenis_id, pallet_jenis_kode")
			->from("pallet_jenis")
			->where("pallet_jenis_kode", "BIN")
			->get()->row();

		return $query;
	}

	public function savePalleteGenerateDetail2($pg_detail2_id, $pg_id, $pg_detail2_kode, $preffix_pallete)
	{
		// var_dump($pg_detail2_id, $pg_id, $pg_detail2_kode, $preffix_pallete);
		// die;
		$this->db->set("pallet_generate_detail2_id", $pg_detail2_id);
		$this->db->set("pallet_generate_id", $pg_id);
		$this->db->set("pallet_generate_detail2_kode", $pg_detail2_kode);
		$this->db->set("pallet_generate_detail2_is_aktif", 0);
		$this->db->set("pallet_generate_detail2_is_deleted", 0);
		$this->db->set("pallet_jenis_id", $preffix_pallete);

		$this->db->insert("pallet_generate_detail2");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}



	public function saveUpdatePalleteGenerateDetail2($pallete_id, $pg_id, $pallete_kode, $last_printed, $user, $print_amount, $isAktif, $pallete_preffix)
	{
		$this->db->set("pallet_generate_id", $pg_id);
		$this->db->set("pallet_generate_detail2_kode", $pallete_kode);
		$this->db->set("pallet_generate_detail2_date_last_print", $last_printed == 'null' ? NULL : $last_printed);
		$this->db->set("pallet_generate_detail2_who_last_print", $user);
		$this->db->set("pallet_generate_detail2_print_count", $print_amount == 'null' ? NULL : $print_amount);
		$this->db->set("pallet_generate_detail2_is_aktif", $isAktif);
		$this->db->set("pallet_jenis_id", $pallete_preffix);

		$this->db->where("pallet_generate_detail2_id", $pallete_id);

		return $this->db->update("pallet_generate_detail2");

		// $affectedrows = $this->db->affected_rows();
		// if ($affectedrows > 0) {
		// 	$queryupdate = 1;
		// } else {
		// 	$queryupdate = 0;
		// }

		// return $queryupdate;
	}

	public function saveUpdatePrintedPalleteGenerateDetail2($val, $datelastprint, $count, $user)
	{
		$this->db->set("pallet_generate_detail2_date_last_print", "GETDATE()", false);
		$this->db->set("pallet_generate_detail2_who_last_print", $user);
		$this->db->set("pallet_generate_detail2_print_count", $count);

		$this->db->where("pallet_generate_detail2_id", $val);

		return $this->db->update("pallet_generate_detail2");

		// $affectedrows = $this->db->affected_rows();
		// if ($affectedrows > 0) {
		// 	$queryupdate = 1;
		// } else {
		// 	$queryupdate = 0;
		// }

		// return $queryupdate;
	}

	public function getPalleteGenerate()
	{
		$query = $this->db->select("pg.pallet_generate_id, cw.client_wms_nama, p.principle_nama, format(pg.pallet_generate_tgl_create, 'yyyy-MM-dd') as pg_tgl")
			->from("pallet_generate as pg")
			->join("client_wms as cw", "cw.client_wms_id = pg.client_wms_id")
			->join("principle as p", "p.principle_id = pg.principle_id")
			->get()->result();

		return $query;
	}

	public function getDataFilterPalletGenerator($tgl1, $tgl2, $perusahaan, $principle)
	{
		$query = $this->db->select("pg.pallet_generate_id, cw.client_wms_nama, p.principle_nama, format(pg.pallet_generate_tgl_create, 'dd-MM-yyyy HH:mm') as pg_tgl")
			->from("pallet_generate as pg")
			->join("client_wms as cw", "cw.client_wms_id = pg.client_wms_id", "left")
			->join("principle as p", "p.principle_id = pg.principle_id", "left")
			->where("format(pg.pallet_generate_tgl_create, 'yyyy-MM-dd') >=", $tgl1)
			->where("format(pg.pallet_generate_tgl_create, 'yyyy-MM-dd') <=", $tgl2)
			->where("depo_id", $this->session->userdata('depo_id'))
			->order_by("pg.pallet_generate_tgl_create", "DESC");

		if ($perusahaan != "") {
			$this->db->where("pg.client_wms_id", $perusahaan);
		}

		if ($principle != "") {
			$this->db->where("pg.principle_id", $principle);
		}

		$query = $this->db->get()->result();

		return $query;
	}

	public function getDataPreffixPallete()
	{
		return $this->db->query("select pallet_jenis_id, pallet_jenis_kode from pallet_jenis where pallet_jenis_kode = 'PAL' and pallet_jenis_is_default = 1 union all select pallet_jenis_id, pallet_jenis_kode from pallet_jenis where pallet_jenis_kode != 'PAL'")->result();
	}

	public function getPalletJenisKodeByID($id)
	{
		$query = $this->db->select("pallet_jenis_kode")
			->from("pallet_jenis")
			->where("pallet_jenis_id", $id)
			->get()->row();

		return $query;
	}

	public function getPalleteGenerateByID($id)
	{
		$query = $this->db->select("pallet_generate_id as id, client_wms_id as perusahaanID, principle_id as principleID, format(pallet_generate_tgl_create, 'yyyy-MM-dd') as tgl, pallet_generate_tgl_update")
			->from("pallet_generate")
			->where("pallet_generate_id", $id)
			->get()->row();

		return $query;
	}

	public function getPalleteGenerateDetailByID($id)
	{
		$query = $this->db->select("pgd.pallet_generate_detail_id as detailID, sj.penerimaan_surat_jalan_kode as sj_kode, sj.penerimaan_surat_jalan_no_sj as sj_eksternal")
			->from("pallet_generate_detail pgd")
			->join("penerimaan_surat_jalan sj", "sj.penerimaan_surat_jalan_id = pgd.penerimaan_surat_jalan_id")
			->where("pgd.pallet_generate_id", $id)
			->get()->result();

		return $query;
	}

	public function getPalleteGenerateDetail2ByID($id)
	{
		$query = $this->db->select("pgd2.pallet_generate_detail2_id as pgd2ID, 
		pgd2.pallet_generate_detail2_kode as pgd2Kode, 
		pgd2.pallet_generate_detail2_date_last_print as pgd2LastPrint, 
		pgd2.pallet_generate_detail2_print_count as pgd2PrintCount,
		pgd2.pallet_generate_detail2_is_aktif as pgd2IsAktif, 
		pj.pallet_jenis_id as pjID, pj.pallet_jenis_kode as pjKode")
			->from("pallet_generate_detail2 pgd2")
			->join("pallet_jenis pj", "pj.pallet_jenis_id = pgd2.pallet_jenis_id")
			->where("pgd2.pallet_generate_id", $id)
			->where("pgd2.pallet_generate_detail2_is_deleted", 0)
			->order_by("pgd2.pallet_generate_detail2_kode", "ASC")
			->get()->result();

		return $query;
	}

	public function deletePalletGenerateDetail($id)
	{
		$query = $this->db->where("pallet_generate_detail_id", $id)
			->delete("pallet_generate_detail");

		return $query;
	}

	public function deletePalletGenerateDetail2($id)
	{
		$this->db->set("pallet_generate_detail2_is_deleted", 1);
		$this->db->where("pallet_generate_detail2_id", $id);

		return $this->db->update("pallet_generate_detail2");
	}

	public function getDepoKodePreffix($id)
	{
		return $this->db->select("depo_kode_preffix")->from("depo")->where("depo_id", $id)->get()->row();
	}

	public function getprintAmountbyID($id)
	{
		return $this->db->select("pallet_generate_detail2_print_count")->from("pallet_generate_detail2")->where("pallet_generate_detail2_id", $id)->get()->row();
	}
}
