<?php

class M_MutasiPalletDraft extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function Get_PencarianMutasiPalletDraftTable($tgl1, $tgl2, $id, $gudang_asal, $gudang_tujuan, $tipe, $client_wms, $principle, $checker, $status)
	{
		// $id = "";
		// if ($id == "") {
		// 	$id = "";
		// } else {
		// 	$id = "AND tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id = '$id'";
		// }

		// $gudang_asal = "";
		// if ($gudang_asal == "") {
		// 	$gudang_asal = "";
		// } else {
		// 	$gudang_asal = "AND tr_mutasi_pallet_draft.depo_detail_id_asal = '$gudang_asal'";
		// }

		// $gudang_tujuan = "";
		// if ($gudang_tujuan == "") {
		// 	$gudang_tujuan = "";
		// } else {
		// 	$gudang_tujuan = "AND tr_mutasi_pallet_draft.depo_detail_id_tujuan = '$gudang_tujuan'";
		// }

		// $tipe = "";
		// if ($tipe == "") {
		// 	$tipe = "";
		// } else {
		// 	$tipe = "AND tr_mutasi_pallet_draft.tipe_mutasi_id = '$tipe'";
		// }

		// $client_wms = "";
		// if ($client_wms == "") {
		// 	$client_wms = "";
		// } else {
		// 	$client_wms = "AND tr_mutasi_pallet_draft.client_wms_id = '$client_wms'";
		// }

		// $principle = "";
		// if ($principle == "") {
		// 	$principle = "";
		// } else {
		// 	$principle = "AND tr_mutasi_pallet_draft.principle_id = '$principle'";
		// }

		// $checker = "";
		// if ($checker == "") {
		// 	$checker = "";
		// } else {
		// 	$checker = "AND tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_nama_checker = '$checker'";
		// }

		// $status = "";
		// if ($status == "") {
		// 	$status = "";
		// } else {
		// 	$status = "AND tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_status = '$status'";
		// }

		$this->db->select("tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id,
											tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_kode,
											FORMAT(tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_tanggal, 'dd-MM-yyyy') AS tr_mutasi_pallet_draft_tanggal,
											tr_mutasi_pallet_draft.tipe_mutasi_id,
											tipe_mutasi.tipe_mutasi_nama AS tr_mutasi_pallet_draft_tipe,
											tr_mutasi_pallet_draft.principle_id,
											principle.principle_kode,
											tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_nama_checker,
											tr_mutasi_pallet_draft.depo_detail_id_asal,
											gudang_asal.depo_detail_nama AS gudang_asal,
											tr_mutasi_pallet_draft.depo_detail_id_tujuan,
											gudang_tujuan.depo_detail_nama AS gudang_tujuan,
											tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_status")
			->from("tr_mutasi_pallet_draft")
			->join("principle", "principle.principle_id = tr_mutasi_pallet_draft.principle_id", "left")
			->join("depo_detail gudang_asal", "gudang_asal.depo_detail_id = tr_mutasi_pallet_draft.depo_detail_id_asal", "left")
			->join("depo_detail gudang_tujuan", "gudang_tujuan.depo_detail_id = tr_mutasi_pallet_draft.depo_detail_id_tujuan", "left")
			->join("tipe_mutasi", "tipe_mutasi.tipe_mutasi_id = tr_mutasi_pallet_draft.tipe_mutasi_id", "left")
			->where("FORMAT(tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_tanggal, 'yyyy-MM-dd') >=", $tgl1)
			->where("FORMAT(tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_tanggal, 'yyyy-MM-dd') <=", $tgl2)
			->where("tr_mutasi_pallet_draft.distribusi_penerimaan_id", NULL)
			->where("tr_mutasi_pallet_draft.depo_id_asal", $this->session->userdata('depo_id'))
			->where("tr_mutasi_pallet_draft.depo_id_tujuan", $this->session->userdata('depo_id'));
		if ($id != "") {
			$this->db->where("tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id", $id);
		}

		if ($gudang_asal != "") {
			$this->db->where("tr_mutasi_pallet_draft.depo_detail_id_asal", $gudang_asal);
		}

		if ($gudang_tujuan != "") {
			$this->db->where("tr_mutasi_pallet_draft.depo_detail_id_tujuan", $gudang_tujuan);
		}

		if ($tipe != "") {
			$this->db->where("tr_mutasi_pallet_draft.tipe_mutasi_id", $tipe);
		}

		if ($client_wms != "") {
			$this->db->where("tr_mutasi_pallet_draft.client_wms_id", $client_wms);
		}

		if ($principle != "") {
			$this->db->where("tr_mutasi_pallet_draft.principle_id", $principle);
		}

		if ($checker != "") {
			$this->db->where("tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_nama_checker", $checker);
		}

		if ($status != "") {
			$this->db->where("tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_status", $status);
		}

		$this->db->order_by("tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_kode", "ASC");

		return $this->db->get()->result_array();
		// $this->db->get()->result_array();
		// return $this->db->last_query();

		// $query = $this->db->query("SELECT
		// 							tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id,
		// 							tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_kode,
		// 							FORMAT(tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_tanggal, 'dd-MM-yyyy') AS tr_mutasi_pallet_draft_tanggal,
		// 							tr_mutasi_pallet_draft.tipe_mutasi_id,
		// 							tipe_mutasi.tipe_mutasi_nama AS tr_mutasi_pallet_draft_tipe,
		// 							tr_mutasi_pallet_draft.principle_id,
		// 							principle.principle_kode,
		// 							tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_nama_checker,
		// 							tr_mutasi_pallet_draft.depo_detail_id_asal,
		// 							gudang_asal.depo_detail_nama AS gudang_asal,
		// 							tr_mutasi_pallet_draft.depo_detail_id_tujuan,
		// 							gudang_tujuan.depo_detail_nama AS gudang_tujuan,
		// 							tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_status
		// 							FROM tr_mutasi_pallet_draft
		// 							LEFT JOIN principle
		// 								ON principle.principle_id = tr_mutasi_pallet_draft.principle_id
		// 							LEFT JOIN depo_detail gudang_asal
		// 								ON gudang_asal.depo_detail_id = tr_mutasi_pallet_draft.depo_detail_id_asal
		// 							LEFT JOIN depo_detail gudang_tujuan
		// 								ON gudang_tujuan.depo_detail_id = tr_mutasi_pallet_draft.depo_detail_id_tujuan
		// 							LEFT JOIN tipe_mutasi
		// 								ON tipe_mutasi.tipe_mutasi_id = tr_mutasi_pallet_draft.tipe_mutasi_id
		// 							WHERE FORMAT(tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2' 
		// 							AND tr_mutasi_pallet_draft.distribusi_penerimaan_id IS NULL
		// 							" . $id . "
		// 							" . $gudang_asal . "
		// 							" . $gudang_tujuan . "
		// 							" . $tipe . "
		// 							" . $client_wms . "
		// 							" . $principle . "
		// 							" . $checker . "
		// 							" . $status . "
		// 							ORDER BY tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_kode ASC");

		// // if ($query->num_rows() == 0) {
		// // 	$query = 0;
		// // } else {
		// // 	$query = $query->result_array();
		// // }

		// return $principle;
		// // return $this->db->last_query();
	}

	public function Get_Checker()
	{
		$query = $this->db->query("SELECT
										*
									FROM karyawan
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
									ORDER BY karyawan_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Principle()
	{
		$query = $this->db->query("SELECT
										*
									FROM principle
									ORDER BY principle_kode ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Gudang($gudangAsal = "", $tipeMutasiID = "")
	{
		if ($tipeMutasiID == 'C02CC613-2FAB-49A9-9372-B27D9444FE93') {
			$query = $this->db->query("SELECT
											*
										FROM depo_detail
										WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND depo_detail_id = '$gudangAsal'
										ORDER BY depo_detail_nama ASC");
		} else if ($gudangAsal == "") {
			$query = $this->db->query("SELECT
										*
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY depo_detail_nama ASC");
		} else {
			$query = $this->db->query("SELECT
										*
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND depo_detail_id != '$gudangAsal'
									ORDER BY depo_detail_nama ASC");
		}

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_GudangTujuanByTipe($gudang_asal)
	{
		$query = $this->db->query("SELECT
										*
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									AND depo_detail_id = '$gudang_asal'
									ORDER BY depo_detail_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Status()
	{
		$query = $this->db->query("SELECT
										tr_mutasi_pallet_draft_status AS status_mutasi
									FROM tr_mutasi_pallet_draft
									GROUP BY tr_mutasi_pallet_draft_status
									ORDER BY tr_mutasi_pallet_draft_status ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_TipeTransaksi()
	{
		$query = $this->db->query("SELECT
										tipe_mutasi_id,
										tipe_mutasi_nama AS tipe_transaksi
									FROM tipe_mutasi
									WHERE tipe_mutasi_flag_2 = 'Pallet'
									ORDER BY tipe_mutasi_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Pallet($depo_detail_id, $principle, $perusahaan)
	{
		$query = $this->db->query("SELECT
									pallet.pallet_id,
									ISNULL(pallet.pallet_kode,'') AS pallet_kode,
									rak_lajur_detail.rak_id,
									ISNULL(rak_lajur.rak_lajur_nama,'') AS rak_nama,
									pallet.rak_lajur_detail_id,
									ISNULL(rak_lajur_detail.rak_lajur_detail_nama,'') AS rak_lajur_detail_nama,
									pallet.pallet_jenis_id,
									ISNULL(pallet_jenis.pallet_jenis_nama,'') AS pallet_jenis_nama
									FROM pallet
									LEFT JOIN pallet_jenis
									ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									LEFT JOIN rak_lajur_detail_pallet
									ON rak_lajur_detail_pallet.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = rak_lajur_detail_pallet.rak_lajur_detail_id
									LEFT JOIN rak_lajur
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									LEFT JOIN rak
									ON rak_lajur.rak_id = rak.rak_id
									WHERE rak.depo_id = '" . $this->session->userdata('depo_id') . "' AND rak.depo_detail_id = '$depo_detail_id' 
										--AND rak_lajur_detail.client_wms_id = '$perusahaan' AND rak_lajur_detail.principle_id = '$principle'
									
									ORDER BY ISNULL(pallet.pallet_kode,'') ASC");

		//AND rak_lajur.principle_id = '$principle'

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_GudangById($gudang_id)
	{
		$this->db->select("depo_detail_nama")
			->from("depo_detail")
			->where("depo_detail_id", $gudang_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_PrincipleById($principle)
	{
		$this->db->select("principle_kode")
			->from("principle")
			->where("principle_id", $principle);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_PerusahaanById($perusahaan)
	{
		$this->db->select("client_wms_nama")
			->from("client_wms")
			->where("client_wms_id", $perusahaan);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function Get_NewID()
	{
		$query = $this->db->query("SELECT NEWID() AS kode");
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->kode;
		}

		return $query;
	}

	public function Get_MutasiPalletById($tr_mutasi_pallet_draft_id)
	{
		$this->db->select("*")
			->from("tr_mutasi_pallet_draft")
			->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}

	public function Get_MutasiPalletDetailById($tr_mutasi_pallet_draft_id)
	{
		$query = $this->db->query("SELECT
									tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_detail_draft_id,
									tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id,
									tr_mutasi_pallet_detail_draft.pallet_id,
									ISNULL(pallet.pallet_kode, '') AS pallet_kode,
									rak_lajur_detail.rak_id,
									ISNULL(rak_lajur.rak_lajur_nama, '') AS rak_nama,
									tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_asal AS rak_lajur_detail_id,
									ISNULL(rak_lajur_detail.rak_lajur_detail_nama, '') AS rak_lajur_detail_nama,
									pallet.pallet_jenis_id,
									ISNULL(pallet_jenis.pallet_jenis_nama, '') AS pallet_jenis_nama
									FROM tr_mutasi_pallet_detail_draft
									LEFT JOIN pallet
									ON tr_mutasi_pallet_detail_draft.pallet_id = pallet.pallet_id
									LEFT JOIN pallet_jenis
									ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_asal
									LEFT JOIN rak_lajur
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									LEFT JOIN rak
									ON rak_lajur.rak_id = rak.rak_id
									WHERE tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'
									ORDER BY ISNULL(pallet.pallet_kode, '') ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_MutasiPalletByKode($kode)
	{
		$this->db->select("*")
			->from("tr_mutasi_pallet_draft")
			->where("tr_mutasi_pallet_draft_kode", $kode);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}

	public function Get_MutasiPalletDetailByKode($kode)
	{
		$query = $this->db->query("SELECT
									tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_detail_draft_id,
									tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id,
									tr_mutasi_pallet_detail_draft.pallet_id,
									ISNULL(pallet.pallet_kode, '') AS pallet_kode,
									rak_lajur_detail.rak_id,
									ISNULL(rak_lajur.rak_lajur_nama, '') AS rak_nama,
									tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_asal AS rak_lajur_detail_id,
									ISNULL(rak_lajur_detail.rak_lajur_detail_nama, '') AS rak_lajur_detail_nama,
									pallet.pallet_jenis_id,
									ISNULL(pallet_jenis.pallet_jenis_nama, '') AS pallet_jenis_nama
									FROM tr_mutasi_pallet_draft
									LEFT JOIN tr_mutasi_pallet_detail_draft
									ON tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id = tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id
									LEFT JOIN pallet
									ON tr_mutasi_pallet_detail_draft.pallet_id = pallet.pallet_id
									LEFT JOIN pallet_jenis
									ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_asal
									LEFT JOIN rak_lajur
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									LEFT JOIN rak
									ON rak_lajur.rak_id = rak.rak_id
									WHERE tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_kode = '$kode'
									ORDER BY ISNULL(pallet.pallet_kode, '') ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Check_Channel_Duplicate($channel_nama)
	{
		$this->db->select("channel_id")
			->from("channel")
			->where("channel_nama", $channel_nama);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = 1;
		}

		return $query;
	}

	public function Insert_MutasiPalletDraft($tr_mutasi_pallet_draft_id, $distribusi_penerimaan_id, $principle_id, $client_wms_id, $tr_mutasi_pallet_draft_kode, $tr_mutasi_pallet_draft_tanggal, $tr_mutasi_pallet_draft_tipe, $tr_mutasi_pallet_draft_keterangan, $tr_mutasi_pallet_draft_status, $depo_id_asal, $depo_detail_id_asal, $depo_id_tujuan, $depo_detail_id_tujuan, $tr_mutasi_pallet_draft_tgl_create, $tr_mutasi_pallet_draft_who_create, $tr_mutasi_pallet_draft_nama_checker)
	{
		$this->db->set("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		// $this->db->set("distribusi_penerimaan_id", $distribusi_penerimaan_id);
		$this->db->set("principle_id", $principle_id);
		$this->db->set("tr_mutasi_pallet_draft_kode", $tr_mutasi_pallet_draft_kode);
		$this->db->set("tr_mutasi_pallet_draft_tanggal", $tr_mutasi_pallet_draft_tanggal);
		$this->db->set("tipe_mutasi_id", $tr_mutasi_pallet_draft_tipe);
		$this->db->set("tr_mutasi_pallet_draft_keterangan", $tr_mutasi_pallet_draft_keterangan);
		$this->db->set("tr_mutasi_pallet_draft_status", $tr_mutasi_pallet_draft_status);
		$this->db->set("depo_id_asal", $depo_id_asal);
		$this->db->set("depo_detail_id_asal", $depo_detail_id_asal);
		$this->db->set("depo_id_tujuan", $depo_id_tujuan);
		$this->db->set("depo_detail_id_tujuan", $depo_detail_id_tujuan);
		$this->db->set("tr_mutasi_pallet_draft_tgl_create", $tr_mutasi_pallet_draft_tgl_create);
		$this->db->set("tr_mutasi_pallet_draft_who_create", $tr_mutasi_pallet_draft_who_create);
		$this->db->set("tr_mutasi_pallet_draft_nama_checker", $tr_mutasi_pallet_draft_nama_checker);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("tr_mutasi_pallet_draft_tgl_update", 'GETDATE()', FALSE);
		$this->db->set("tr_mutasi_pallet_draft_who_update", $tr_mutasi_pallet_draft_who_create);


		$this->db->insert("tr_mutasi_pallet_draft");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Insert_MutasiPalletDetailDraft($tr_mutasi_pallet_draft_id, $pallet_id, $rak_lajur_detail_id_asal)
	{
		$this->db->set("tr_mutasi_pallet_detail_draft_id", "NEWID()", FALSE);
		$this->db->set("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("rak_lajur_detail_id_asal", $rak_lajur_detail_id_asal);

		$this->db->insert("tr_mutasi_pallet_detail_draft");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Update_MutasiPalletDraft($tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_draft_kode, $principle_id, $client_wms_id, $tr_mutasi_pallet_draft_tanggal, $tr_mutasi_pallet_draft_tipe, $tr_mutasi_pallet_draft_keterangan, $tr_mutasi_pallet_draft_status, $depo_id_asal, $depo_detail_id_asal, $depo_id_tujuan, $depo_detail_id_tujuan, $tr_mutasi_pallet_draft_tgl_create, $tr_mutasi_pallet_draft_who_create, $tr_mutasi_pallet_draft_nama_checker)
	{
		$this->db->set("tr_mutasi_pallet_draft_kode", $tr_mutasi_pallet_draft_kode);
		$this->db->set("principle_id", $principle_id);
		$this->db->set("tr_mutasi_pallet_draft_tanggal", $tr_mutasi_pallet_draft_tanggal);
		$this->db->set("tipe_mutasi_id", $tr_mutasi_pallet_draft_tipe);
		$this->db->set("tr_mutasi_pallet_draft_keterangan", $tr_mutasi_pallet_draft_keterangan);
		$this->db->set("tr_mutasi_pallet_draft_status", $tr_mutasi_pallet_draft_status);
		$this->db->set("depo_id_asal", $depo_id_asal);
		$this->db->set("depo_detail_id_asal", $depo_detail_id_asal);
		$this->db->set("depo_id_tujuan", $depo_id_tujuan);
		$this->db->set("depo_detail_id_tujuan", $depo_detail_id_tujuan);
		$this->db->set("tr_mutasi_pallet_draft_tgl_create", $tr_mutasi_pallet_draft_tgl_create);
		$this->db->set("tr_mutasi_pallet_draft_who_create", $tr_mutasi_pallet_draft_who_create);
		$this->db->set("tr_mutasi_pallet_draft_nama_checker", $tr_mutasi_pallet_draft_nama_checker);
		$this->db->set("client_wms_id", $client_wms_id);

		$this->db->set("tr_mutasi_pallet_draft_tgl_update", 'GETDATE()', FALSE);
		$this->db->set("tr_mutasi_pallet_draft_who_update", $tr_mutasi_pallet_draft_who_create);


		$this->db->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		$this->db->update("tr_mutasi_pallet_draft");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Update_MutasiPalletDetailDraft($tr_mutasi_pallet_draft_id, $pallet_id, $rak_lajur_detail_id_asal)
	{
		$this->db->set("tr_mutasi_pallet_detail_draft_id", "NEWID()", FALSE);
		$this->db->set("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("rak_lajur_detail_id_asal", $rak_lajur_detail_id_asal);

		$this->db->insert("tr_mutasi_pallet_detail_draft");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Delete_MutasiPalletDetailDraft($tr_mutasi_pallet_draft_id)
	{
		$this->db->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);

		$this->db->delete("tr_mutasi_pallet_detail_draft");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Success
		}

		return $res;
	}

	public function Delete_pallet_temp($tr_mutasi_pallet_draft_id)
	{
		$this->db->query("DELETE FROM pallet_temp WHERE delivery_order_batch_id = '$tr_mutasi_pallet_draft_id' ");
	}

	public function Delete_pallet_detail_temp($tr_mutasi_pallet_draft_id)
	{
		$this->db->query("DELETE FROM pallet_detail_temp WHERE pallet_id IN (SELECT pallet_id FROM pallet_temp WHERE delivery_order_batch_id = '$tr_mutasi_pallet_draft_id') ");
	}

	public function Get_ParameterApprovalMutasiPallet($tipe_mutasi)
	{
		$query = $this->db->query("select vrbl_param, vrbl_kode from vrbl where vrbl_param in
								(select approval_setting_parameter from approval_setting where menu_web_kode = '120002000' and approval_setting_jenis = '$tipe_mutasi')");
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->vrbl_param;
		}

		return $query;
	}

	public function Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_draft_kode, $is_approvaldana, $total_biaya)
	{
		$query = $this->db->query("exec approval_pengajuan '$depo_id', '$karyawan_id','$approvalParam', '$tr_mutasi_pallet_draft_id','$tr_mutasi_pallet_draft_kode', '$is_approvaldana','$total_biaya'");

		// $res = $query->result_array();

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Success
		}

		return $res;
	}

	public function Get_PalletDetail($pallet_id)
	{
		$query = $this->db->query("SELECT
									pallet.pallet_id,
									pallet.pallet_kode,
									pallet.pallet_jenis_id,
									pallet_jenis.pallet_jenis_nama,
									pallet_detail.sku_id,
									sku.sku_kode,
									principle.principle_kode,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									FORMAT(pallet_detail.sku_stock_expired_date,'dd-MM-yyyy') AS sku_stock_expired_date,
									penerimaan_tipe.penerimaan_tipe_nama,
									ISNULL(pallet_detail.sku_stock_qty,0) - ISNULL(pallet_detail.sku_stock_ambil,0) + ISNULL(pallet_detail.sku_stock_in,0) - ISNULL(pallet_detail.sku_stock_out,0) + ISNULL(pallet_detail.sku_stock_terima,0) AS sku_stock_qty
									FROM pallet
									LEFT JOIN pallet_detail
									ON pallet.pallet_id = pallet_detail.pallet_id
									LEFT JOIN pallet_jenis
									ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									LEFT JOIN sku
									ON sku.sku_id = pallet_detail.sku_id
									LEFT JOIN principle
									ON sku.principle_id = principle.principle_id
									LEFT JOIN penerimaan_tipe
									ON penerimaan_tipe.penerimaan_tipe_id = pallet_detail.penerimaan_tipe_id
									WHERE pallet.pallet_id = '$pallet_id'
									ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function insert_pallet_temp($tr_mutasi_pallet_draft_id, $pallet_id)
	{
		$this->db->query("INSERT INTO pallet_temp
							SELECT
								pallet_id,
								pallet_jenis_id,
								'$tr_mutasi_pallet_draft_id' AS delivery_order_batch_id,
								depo_id,
								rak_lajur_detail_id,
								pallet_kode,
								pallet_tanggal_create,
								pallet_who_create,
								pallet_is_aktif
							FROM pallet
							WHERE pallet_id = '$pallet_id' ");
	}

	public function insert_pallet_detail_temp($pallet_id)
	{
		$this->db->query("INSERT INTO pallet_detail_temp
							SELECT
								*
							FROM pallet_detail
							WHERE pallet_id = '$pallet_id' ");
	}

	public function Get_tipe_mutasi($tipe_mutasi_id)
	{
		$this->db->select("tipe_mutasi_nama")
			->from("tipe_mutasi")
			->where("tipe_mutasi_id", $tipe_mutasi_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->tipe_mutasi_nama;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_ParamTipe($tipe_mutasi)
	{
		$query = $this->db->query("SELECT vrbl_param FROM vrbl WHERE vrbl_keterangan = '$tipe_mutasi'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->vrbl_param;
		}

		return $query;
	}

	public function Get_tipe_mutasi_nama($tipe_mutasi_id)
	{
		$query = $this->db->query("SELECT tipe_mutasi_nama FROM tipe_mutasi WHERE tipe_mutasi_id = '$tipe_mutasi_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->tipe_mutasi_nama;
		}

		return $query;
	}

	public function Get_CheckerPrinciple($principle, $client_wms_id)
	{
		$query = $this->db->query("select
									karyawan.*
									from karyawan
									left join karyawan_principle
									on karyawan.karyawan_id = karyawan_principle.karyawan_id
									where karyawan_principle.principle_id = '$principle'
									AND karyawan_principle.client_wms_id = '$client_wms_id'
									AND karyawan.karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
									AND karyawan.depo_id = '" . $this->session->userdata('depo_id') . "'
									order by karyawan.karyawan_nama ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getClientWms()
	{
		// return $this->db->select("client_wms_id, client_wms_nama")
		// 	->from("client_wms")
		// 	->where("client_wms_is_aktif", 1)
		// 	->get()->result();
		return $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0")->result();
	}

	public function getDataPrincipleByClientWmsId($id)
	{
		$this->db->select("b.principle_id as id, b.principle_kode as kode, b.principle_nama as nama")
			->from("client_wms_principle a")
			->join("principle b", "a.principle_id = b.principle_id");
		if ($id != "") {
			# code...
			$this->db->where("a.client_wms_id", $id);
		}
		return $this->db->get()->result();
	}
	public function GetTglUpdate($tr_mutasi_pallet_draft_id)
	{
		return $this->db->query("select tr_mutasi_pallet_draft_tgl_update as tgl_update from tr_mutasi_pallet_draft where tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'")->row();
	}
}
