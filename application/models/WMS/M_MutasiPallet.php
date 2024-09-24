<?php

class M_MutasiPallet extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
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

	public function Get_Gudang()
	{
		$query = $this->db->query("SELECT
										*
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
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
	}

	public function Get_MutasiDraftPallet()
	{
		$query = $this->db->query("SELECT
										tr_mutasi_pallet_draft.*
									FROM tr_mutasi_pallet_draft
									LEFT JOIN tr_mutasi_pallet
									   ON tr_mutasi_pallet.tr_mutasi_pallet_draft_id = tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id
									WHERE tr_mutasi_pallet.tr_mutasi_pallet_draft_id IS NULL AND tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_status = 'Approved' AND tr_mutasi_pallet_draft.distribusi_penerimaan_id IS NULL
									ORDER BY tr_mutasi_pallet_draft_kode ASC");


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

	public function Get_PencarianMutasiPalletTable($tgl1, $tgl2, $id, $gudang_asal, $gudang_tujuan, $tipe, $client_wms, $principle, $checker, $status)
	{
		// $id = "";
		// if ($id == "") {
		// 	$id = "";
		// } else {
		// 	$id = "AND tr_mutasi_pallet.tr_mutasi_pallet_id = '$id'";
		// }

		// $gudang_asal = "";
		// if ($gudang_asal == "") {
		// 	$gudang_asal = "";
		// } else {
		// 	$gudang_asal = "AND tr_mutasi_pallet.depo_detail_id_asal = '$gudang_asal'";
		// }

		// $gudang_tujuan = "";
		// if ($gudang_tujuan == "") {
		// 	$gudang_tujuan = "";
		// } else {
		// 	$gudang_tujuan = "AND tr_mutasi_pallet.depo_detail_id_tujuan = '$gudang_tujuan'";
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
		// 	$client_wms = "AND tr_mutasi_pallet.client_wms_id = '$client_wms'";
		// }

		// $principle = "";
		// if ($principle == "") {
		// 	$principle = "";
		// } else {
		// 	$principle = "AND tr_mutasi_pallet.principle_id = '$principle'";
		// }

		// $checker = "";
		// if ($checker == "") {
		// 	$checker = "";
		// } else {
		// 	$checker = "AND tr_mutasi_pallet.tr_mutasi_pallet_nama_checker = '$checker'";
		// }

		// $status = "";
		// if ($status == "") {
		// 	$status = "";
		// } else {
		// 	$status = "AND tr_mutasi_pallet.tr_mutasi_pallet_status = '$status'";
		// }

		// $query = $this->db->query("SELECT
		// 							tr_mutasi_pallet.tr_mutasi_pallet_id,
		// 							tr_mutasi_pallet.tr_mutasi_pallet_kode,
		// 							tr_mutasi_pallet.tr_mutasi_pallet_draft_id,
		// 							tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_kode,
		// 							FORMAT(tr_mutasi_pallet.tr_mutasi_pallet_tanggal, 'dd-MM-yyyy') AS tr_mutasi_pallet_tanggal,
		// 							tr_mutasi_pallet_draft.tipe_mutasi_id,
		// 							tipe_mutasi.tipe_mutasi_nama AS tr_mutasi_pallet_tipe,
		// 							tr_mutasi_pallet.principle_id,
		// 							principle.principle_kode,
		// 							tr_mutasi_pallet.tr_mutasi_pallet_nama_checker,
		// 							tr_mutasi_pallet.depo_detail_id_asal,
		// 							gudang_asal.depo_detail_nama AS gudang_asal,
		// 							tr_mutasi_pallet.depo_detail_id_tujuan,
		// 							gudang_tujuan.depo_detail_nama AS gudang_tujuan,
		// 							tr_mutasi_pallet.tr_mutasi_pallet_status
		// 							FROM tr_mutasi_pallet
		// 							LEFT JOIN tr_mutasi_pallet_draft
		// 								ON tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id = tr_mutasi_pallet.tr_mutasi_pallet_draft_id
		// 							LEFT JOIN principle
		// 								ON principle.principle_id = tr_mutasi_pallet.principle_id
		// 							LEFT JOIN depo_detail gudang_asal
		// 								ON gudang_asal.depo_detail_id = tr_mutasi_pallet.depo_detail_id_asal
		// 							LEFT JOIN depo_detail gudang_tujuan
		// 								ON gudang_tujuan.depo_detail_id = tr_mutasi_pallet.depo_detail_id_tujuan
		// 							LEFT JOIN tipe_mutasi
		// 								ON tipe_mutasi.tipe_mutasi_id = tr_mutasi_pallet_draft.tipe_mutasi_id
		// 							WHERE FORMAT(tr_mutasi_pallet.tr_mutasi_pallet_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2' 
		// 							" . $gudang_asal . "
		// 							" . $gudang_tujuan . "
		// 							" . $tipe . "
		// 							" . $client_wms . "
		// 							" . $principle . "
		// 							" . $checker . "
		// 							" . $status . "
		// 							ORDER BY tr_mutasi_pallet.tr_mutasi_pallet_kode ASC");

		// if ($query->num_rows() == 0) {
		// 	$query = 0;
		// } else {
		// 	$query = $query->result_array();
		// }


		$this->db->select("tr_mutasi_pallet.tr_mutasi_pallet_id,
												tr_mutasi_pallet.tr_mutasi_pallet_kode,
												tr_mutasi_pallet.tr_mutasi_pallet_draft_id,
												tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_kode,
												FORMAT(tr_mutasi_pallet.tr_mutasi_pallet_tanggal, 'dd-MM-yyyy') AS tr_mutasi_pallet_tanggal,
												tr_mutasi_pallet_draft.tipe_mutasi_id,
												tipe_mutasi.tipe_mutasi_nama AS tr_mutasi_pallet_tipe,
												tr_mutasi_pallet.principle_id,
												principle.principle_kode,
												tr_mutasi_pallet.tr_mutasi_pallet_nama_checker,
												tr_mutasi_pallet.depo_detail_id_asal,
												gudang_asal.depo_detail_nama AS gudang_asal,
												tr_mutasi_pallet.depo_detail_id_tujuan,
												gudang_tujuan.depo_detail_nama AS gudang_tujuan,
												tr_mutasi_pallet.tr_mutasi_pallet_status")
			->from("tr_mutasi_pallet")
			->join("tr_mutasi_pallet_draft", "tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id = tr_mutasi_pallet.tr_mutasi_pallet_draft_id", "left")
			->join("principle", "principle.principle_id = tr_mutasi_pallet.principle_id", "left")
			->join("depo_detail gudang_asal", "gudang_asal.depo_detail_id = tr_mutasi_pallet.depo_detail_id_asal", "left")
			->join("depo_detail gudang_tujuan", "gudang_tujuan.depo_detail_id = tr_mutasi_pallet.depo_detail_id_tujuan", "left")
			->join("tipe_mutasi", "tipe_mutasi.tipe_mutasi_id = tr_mutasi_pallet_draft.tipe_mutasi_id", "left")
			->where("FORMAT(tr_mutasi_pallet.tr_mutasi_pallet_tanggal, 'yyyy-MM-dd') >=", $tgl1)
			->where("FORMAT(tr_mutasi_pallet.tr_mutasi_pallet_tanggal, 'yyyy-MM-dd') <=", $tgl2)
			->where("tr_mutasi_pallet.depo_id_asal", $this->session->userdata('depo_id'))
			->where("tr_mutasi_pallet.depo_id_tujuan", $this->session->userdata('depo_id'));
		if ($id != "") {
			$this->db->where("tr_mutasi_pallet.tr_mutasi_pallet_id", $id);
		}

		if ($gudang_asal != "") {
			$this->db->where("tr_mutasi_pallet.depo_detail_id_asal", $gudang_asal);
		}

		if ($gudang_tujuan != "") {
			$this->db->where("tr_mutasi_pallet.depo_detail_id_tujuan", $gudang_tujuan);
		}

		if ($tipe != "") {
			$this->db->where("tr_mutasi_pallet_draft.tipe_mutasi_id", $tipe);
		}

		if ($client_wms != "") {
			$this->db->where("tr_mutasi_pallet.client_wms_id", $client_wms);
		}

		if ($principle != "") {
			$this->db->where("tr_mutasi_pallet.principle_id", $principle);
		}

		if ($checker != "") {
			$this->db->where("tr_mutasi_pallet.tr_mutasi_pallet_nama_checker", $checker);
		}

		if ($status != "") {
			$this->db->where("tr_mutasi_pallet.tr_mutasi_pallet_status", $status);
		}

		$this->db->order_by("tr_mutasi_pallet.tr_mutasi_pallet_kode", "ASC");

		return $this->db->get()->result_array();

		// return $query;
		// return $this->db->last_query();
	}

	public function Get_MutasiPalletDraft($tr_mutasi_pallet_draft_id)
	{
		$query = $this->db->query("SELECT
										tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id,
										tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_kode,
										FORMAT(tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_tanggal, 'dd-MM-yyyy') AS tr_mutasi_pallet_draft_tanggal,
										tr_mutasi_pallet_draft.depo_detail_id_asal,
										gudang_asal.depo_detail_nama AS gudang_asal,
										tr_mutasi_pallet_draft.depo_detail_id_tujuan,
										gudang_tujuan.depo_detail_nama AS gudang_tujuan,
										principle.principle_nama,
										ISNULL(client_wms.client_wms_nama, '-') as client_wms_nama,
										tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_nama_checker,
										tr_mutasi_pallet_draft.tipe_mutasi_id,
										tipe_mutasi.tipe_mutasi_nama AS tr_mutasi_pallet_draft_tipe,
										tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_status,
										tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_tgl_update
									FROM tr_mutasi_pallet_draft
									LEFT JOIN depo_detail gudang_asal
									ON tr_mutasi_pallet_draft.depo_detail_id_asal = gudang_asal.depo_detail_id
									LEFT JOIN depo_detail gudang_tujuan
									ON tr_mutasi_pallet_draft.depo_detail_id_tujuan = gudang_tujuan.depo_detail_id
									LEFT JOIN client_wms
									ON tr_mutasi_pallet_draft.client_wms_id = client_wms.client_wms_id
									LEFT JOIN principle
									ON tr_mutasi_pallet_draft.principle_id = principle.principle_id
									LEFT JOIN tipe_mutasi
										ON tipe_mutasi.tipe_mutasi_id = tr_mutasi_pallet_draft.tipe_mutasi_id
									WHERE tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_Pallet($tr_mutasi_pallet_draft_id)
	{
		$query = $this->db->query("SELECT
									tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_detail_draft_id,
									tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id,
									tr_mutasi_pallet_detail_draft.is_valid,
									tr_mutasi_pallet_detail_draft.pallet_id,
									pallet.pallet_kode,
									pallet.pallet_jenis_id,
									pallet_jenis.pallet_jenis_nama,
									pallet.pallet_is_aktif,
									rak_lajur.rak_id,
									rak_lajur.rak_lajur_nama,
									tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_asal AS rak_lajur_detail_id,
									rak_lajur_detail.rak_lajur_detail_nama,
									tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_tujuan,
									ISNULL(rak_lajur_detail_tujuan.rak_lajur_detail_nama,'') AS rak_lajur_detail_tujuan_nama
									FROM tr_mutasi_pallet_detail_draft
									LEFT JOIN pallet
									ON tr_mutasi_pallet_detail_draft.pallet_id = pallet.pallet_id
									LEFT JOIN pallet_jenis
									ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_asal
									LEFT JOIN rak_lajur_detail AS rak_lajur_detail_tujuan
									ON rak_lajur_detail_tujuan.rak_lajur_detail_id = tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_tujuan
									LEFT JOIN rak_lajur
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									LEFT JOIN rak
									ON rak_lajur.rak_id = rak.rak_id
									WHERE tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'
									ORDER BY pallet.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_Pallet2($tr_mutasi_pallet_draft_id)
	{
		$query = $this->db->query("SELECT
									tr_mutasi_pallet_detail.tr_mutasi_pallet_detail_id,
									tr_mutasi_pallet_detail.tr_mutasi_pallet_id,
									tr_mutasi_pallet_detail.is_valid,
									tr_mutasi_pallet_detail.pallet_id,
									pallet.pallet_kode,
									pallet.pallet_jenis_id,
									pallet_jenis.pallet_jenis_nama,
									pallet.pallet_is_aktif,
									rak_lajur.rak_id,
									rak_lajur.rak_lajur_nama,
									tr_mutasi_pallet_detail.rak_lajur_detail_id_asal AS rak_lajur_detail_id,
									rak_lajur_detail.rak_lajur_detail_nama,
									tr_mutasi_pallet_detail.rak_lajur_detail_id_tujuan,
									ISNULL(rak_lajur_detail_tujuan.rak_lajur_detail_nama, '') AS rak_lajur_detail_tujuan_nama
									FROM tr_mutasi_pallet
									LEFT JOIN tr_mutasi_pallet_detail
									ON tr_mutasi_pallet_detail.tr_mutasi_pallet_id = tr_mutasi_pallet.tr_mutasi_pallet_id
									LEFT JOIN pallet
									ON tr_mutasi_pallet_detail.pallet_id = pallet.pallet_id
									LEFT JOIN pallet_jenis
									ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = tr_mutasi_pallet_detail.rak_lajur_detail_id_asal
									LEFT JOIN rak_lajur_detail AS rak_lajur_detail_tujuan
									ON rak_lajur_detail_tujuan.rak_lajur_detail_id = tr_mutasi_pallet_detail.rak_lajur_detail_id_tujuan
									LEFT JOIN rak_lajur
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									LEFT JOIN rak
									ON rak_lajur.rak_id = rak.rak_id
									WHERE tr_mutasi_pallet.tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'
									ORDER BY pallet.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
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


	public function check_kode_pallet_by_no_mutasi($id, $kode_pallet)
	{
		return $this->db->select("tmpdd.tr_mutasi_pallet_detail_draft_id as id, tmpdd.is_valid as status, tmpdd.attachement as file")
			->from("tr_mutasi_pallet_draft tmpd")
			->join("tr_mutasi_pallet_detail_draft tmpdd", "tmpd.tr_mutasi_pallet_draft_id = tmpdd.tr_mutasi_pallet_draft_id", "left")
			->join("pallet p", "tmpdd.pallet_id = p.pallet_id", "left")
			->where("tmpd.tr_mutasi_pallet_draft_id", $id)
			->where("p.pallet_kode", $kode_pallet)->get()->row();
	}

	public function update_status_tmpdd($data, $file)
	{
		$file_ = $file == null ? null : $file;

		$this->db->set("is_valid", 1);
		$this->db->set("attachement", $file_);
		$this->db->where("tr_mutasi_pallet_detail_draft_id", $data->id);
		$this->db->update("tr_mutasi_pallet_detail_draft");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function getPrefixPallet($id)
	{
		return $this->db->select("pj.pallet_jenis_kode")
			->from("tr_mutasi_pallet_draft tmpd")
			->join("tr_mutasi_pallet_detail_draft tmpdd", "tmpd.tr_mutasi_pallet_draft_id = tmpdd.tr_mutasi_pallet_draft_id", "left")
			->join("pallet p", "tmpdd.pallet_id = p.pallet_id", "left")
			->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left")
			->where("tmpd.tr_mutasi_pallet_draft_id", $id)->get()->row();

		// return $this->db->last_query();
	}

	public function check_rak_lajur_detail($gudang_tujuan, $kode)
	{
		return $this->db->query("SELECT
										rak_lajur.rak_lajur_id,
										rak_lajur.rak_lajur_nama,
										rak_lajur_detail.rak_lajur_detail_id AS rak_detail_id,
										rak_lajur_detail.rak_lajur_detail_nama AS rak_detail_nama
									FROM rak
									LEFT JOIN rak_lajur
									ON rak_lajur.rak_id = rak.rak_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									WHERE rak.depo_id = '" . $this->session->userdata('depo_id') . "' AND rak.depo_detail_id = '$gudang_tujuan' 
									AND rak_lajur_detail.rak_lajur_detail_nama = '$kode'")->row();

		// return $this->db->query("SELECT DISTINCT
		//                             p.pallet_id AS pallet_id,
		//                             p.rak_lajur_detail_id AS pallet_rak_detail,
		//                             rld.rak_lajur_detail_id AS rak_detail_id,
		//                             rld.rak_lajur_detail_nama AS rak_detail_nama
		//                         FROM tr_mutasi_pallet_detail_draft tmpdd
		//                         LEFT JOIN pallet p
		//                             ON tmpdd.pallet_id = p.pallet_id
		//                         LEFT JOIN rak_lajur_detail_pallet rldp
		//                             ON p.pallet_id = rldp.pallet_id
		//                         LEFT JOIN rak_lajur_detail rld
		//                             ON rldp.rak_lajur_detail_id = rld.rak_lajur_detail_id
		//                         LEFT JOIN rak_lajur rl
		//                             ON rld.rak_lajur_id = rl.rak_lajur_id
		//                         LEFT JOIN rak r
		//                             ON rl.rak_id = r.rak_id
		//                         WHERE tmpdd.pallet_id = '$pallet_id'
		//                             AND r.depo_detail_id = '$gudang_tujuan'
		//                             AND rld.rak_lajur_detail_nama = '$kode'")->row();
	}

	public function check_and_get_data_rak_lajur_detail_pallet($pallet_id)
	{
		return $this->db->select("rak_lajur_detail.rak_lajur_detail_nama, rak_lajur_detail_pallet.pallet_id, rak_lajur_detail_pallet.rak_lajur_detail_id as rak_detail_id")->from("rak_lajur_detail_pallet")
			->join("rak_lajur_detail", "rak_lajur_detail_pallet.rak_lajur_detail_id = rak_lajur_detail.rak_lajur_detail_id")
			->where("rak_lajur_detail_pallet.pallet_id", $pallet_id)->get()->row();
	}

	public function insert_data_to_rak_lajur_detail_pallet($pallet_id, $data)
	{

		$this->db->set("rak_lajur_detail_pallet_id", "NewID()", FALSE);
		$this->db->set("rak_lajur_detail_id", $data->rak_detail_id);
		$this->db->set("pallet_id", $pallet_id);

		$queryinsert = $this->db->insert("rak_lajur_detail_pallet");

		return $queryinsert;
	}

	public function update_data_pallet_by_params($pallet_id, $data)
	{
		//update rak_detail_id di tabel pallet by pallet_id
		$this->db->set("rak_lajur_detail_id", $data->rak_detail_id);
		$this->db->where("pallet_id", $pallet_id);
		$this->db->update("pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function update_data_mutasi_pallet_by_params($tr_mutasi_pallet_draft_id, $pallet_id, $data)
	{
		//update rak_detail_id di tabel pallet by pallet_id
		$this->db->set("rak_lajur_detail_id_tujuan", $data->rak_lajur_detail_id);
		$this->db->where("pallet_id", $pallet_id);
		$this->db->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		$this->db->update("tr_mutasi_pallet_detail_draft");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}


	public function update_data_mutasi_pallet_by_params2($tr_mutasi_pallet_id, $pallet_id, $data)
	{
		//update rak_detail_id di tabel pallet by pallet_id
		$this->db->set("rak_lajur_detail_id_tujuan", $data->rak_lajur_detail_id);
		$this->db->where("pallet_id", $pallet_id);
		$this->db->where("tr_mutasi_pallet_id", $tr_mutasi_pallet_id);
		$this->db->update("tr_mutasi_pallet_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Insert_MutasiPallet($tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_id, $tr_mutasi_pallet_kode, $tr_mutasi_pallet_keterangan, $tr_mutasi_pallet_status)
	{
		$this->db->trans_begin();

		$query = $this->db->query("INSERT INTO tr_mutasi_pallet
							SELECT
								'$tr_mutasi_pallet_id',
								principle_id,
								'$tr_mutasi_pallet_kode',
								GETDATE(),
								tipe_mutasi_id,
								'$tr_mutasi_pallet_keterangan',
								'$tr_mutasi_pallet_status',
								depo_id_asal,
								depo_detail_id_asal,
								depo_id_tujuan,
								depo_detail_id_tujuan,
								GETDATE(),
								'" . $this->session->userdata('pengguna_username') . "',
								'$tr_mutasi_pallet_draft_id',
								tr_mutasi_pallet_draft_nama_checker,
								client_wms_id,
								GETDATE(),
								null
							FROM tr_mutasi_pallet_draft
							WHERE tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$query_detail = $this->db->query("INSERT INTO tr_mutasi_pallet_detail
							SELECT
								NEWID(),
								'$tr_mutasi_pallet_id',
								pallet_id,
								is_valid,
								attachement,
								rak_lajur_detail_id_asal,
      							rak_lajur_detail_id_tujuan,
								NULL
							FROM tr_mutasi_pallet_detail_draft
							WHERE tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'");

			$cek_pallet = $this->db->query("SELECT * FROM tr_mutasi_pallet_detail_draft WHERE tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id' ");

			foreach ($cek_pallet as $row) {

				// $insert_pallet_temp = $this->db->query("INSERT INTO pallet_temp
				// 									SELECT
				// 										pallet_id,
				// 										pallet_jenis_id,
				// 										'$tr_mutasi_pallet_draft_id' AS delivery_order_batch_id,
				// 										depo_id,
				// 										rak_lajur_detail_id,
				// 										pallet_kode,
				// 										pallet_tanggal_create,
				// 										pallet_who_create,
				// 										pallet_is_aktif
				// 									FROM pallet
				// 									WHERE pallet_id = '$row->pallet_id'");

				// $insert_pallet_detail_temp = $this->db->query("INSERT INTO pallet_detail_temp
				// 									SELECT
				// 										pallet_detail_id,
				// 										pallet_id,
				// 										sku_id,
				// 										sku_stock_id,
				// 										sku_stock_expired_date,
				// 										sku_stock_qty,
				// 										penerimaan_tipe_id
				// 									FROM pallet_detail
				// 									WHERE pallet_id = '$row->pallet_id'");
			}

			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return 0;
		} else {
			$this->db->trans_commit();
			return 1;
		}
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

	public function Konfirmasi_MutasiPalet($tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_status)
	{
		$this->db->trans_begin();

		$this->db->set("tr_mutasi_pallet_draft_status", "Completed");
		$this->db->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		$this->db->where("tr_mutasi_pallet_draft_tgl_update", 'GETDATE()', false);
		$this->db->where("tr_mutasi_pallet_draft_who_update", $this->session->userdata('pengguna_username'));
		$this->db->update("tr_mutasi_pallet_draft");

		// $affectedrows = $this->db->affected_rows();
		// if ($affectedrows > 0) {

		$this->db->set("tr_mutasi_pallet_status", "Completed");
		$this->db->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		$this->db->set("tr_mutasi_pallet_tgl_update", 'GETDATE()', FALSE);
		$this->db->set("tr_mutasi_pallet_who_update", $this->session->userdata('pengguna_username'));
		$this->db->update("tr_mutasi_pallet");

		// 	$affectedrows2 = $this->db->affected_rows();
		// 	if ($affectedrows2 > 0) {
		// 		$queryinsert = 1;
		// 	} else {
		// 		$this->db->set("tr_mutasi_pallet_draft_status", "In Progress Approval");
		// 		$this->db->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		// 		$this->db->where("tr_mutasi_pallet_draft_tgl_update", 'GETDATE()', false);
		// 		$this->db->update("tr_mutasi_pallet_draft");

		// 		$queryinsert = 0;
		// 	}
		// } else {
		// 	$queryinsert = 0;
		// }

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return 0;
		} else {
			$this->db->trans_commit();
			return 1;
		}
	}

	public function Update_sku_stock($tr_mutasi_pallet_draft_id, $tipe_mutasi)
	{
		$query_tr_mutasi = $this->db->query("SELECT
												tr_mutasi_pallet.depo_id_asal,
												tr_mutasi_pallet.depo_detail_id_asal,
												tr_mutasi_pallet.depo_id_tujuan,
												tr_mutasi_pallet.depo_detail_id_tujuan,
												tr_mutasi_pallet.client_wms_id,
												pallet_detail.sku_id,
												FORMAT(pallet_detail.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
												sku.sku_induk_id,
												SUM(ISNULL(pallet_detail.sku_stock_qty, 0) - ISNULL(pallet_detail.sku_stock_ambil, 0) + ISNULL(pallet_detail.sku_stock_in, 0) - ISNULL(pallet_detail.sku_stock_out, 0) + ISNULL(pallet_detail.sku_stock_terima, 0)) AS sku_stock_qty
												FROM tr_mutasi_pallet
												LEFT JOIN tr_mutasi_pallet_detail
												ON tr_mutasi_pallet.tr_mutasi_pallet_id = tr_mutasi_pallet_detail.tr_mutasi_pallet_id
												LEFT JOIN pallet
												ON pallet.pallet_id = tr_mutasi_pallet_detail.pallet_id
												LEFT JOIN pallet_detail
												ON pallet.pallet_id = pallet_detail.pallet_id
												LEFT JOIN sku
												ON sku.sku_id = pallet_detail.sku_id
												WHERE tr_mutasi_pallet.tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'
												GROUP BY tr_mutasi_pallet.depo_id_asal,
														tr_mutasi_pallet.depo_detail_id_asal,
														tr_mutasi_pallet.depo_id_tujuan,
														tr_mutasi_pallet.depo_detail_id_tujuan,
														tr_mutasi_pallet.client_wms_id,
														pallet_detail.sku_id,
														FORMAT(pallet_detail.sku_stock_expired_date, 'yyyy-MM-dd'),
														sku.sku_induk_id");

		foreach ($query_tr_mutasi->result() as $row) {

			$query_cek_sku_stock_asal = $this->db->query("SELECT
														*
														FROM sku_stock
														WHERE depo_id = '$row->depo_id_asal'
														AND depo_detail_id = '$row->depo_detail_id_asal'
														AND sku_id = '$row->sku_id'
														AND FORMAT(sku_stock_expired_date, 'yyyy-MM-dd') = '$row->sku_stock_expired_date'");

			$query_cek_sku_stock_tujuan = $this->db->query("SELECT
														*
														FROM sku_stock
														WHERE depo_id = '$row->depo_id_tujuan'
														AND depo_detail_id = '$row->depo_detail_id_tujuan'
														AND sku_id = '$row->sku_id'
														AND FORMAT(sku_stock_expired_date, 'yyyy-MM-dd') = '$row->sku_stock_expired_date'");

			if ($query_cek_sku_stock_asal->num_rows() == 0) {
				// return $this->db->last_query();
				// return "query_cek_sku_stock_asal tidak ada";

				$this->db->set("tr_mutasi_pallet_draft_status", "In Progress Approval");
				$this->db->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
				$this->db->update("tr_mutasi_pallet_draft");

				$affectedrows = $this->db->affected_rows();
				if ($affectedrows > 0) {

					$this->db->set("tr_mutasi_pallet_status", "In Progress");
					$this->db->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
					$this->db->update("tr_mutasi_pallet");
				}

				$queryinsert = 2;
			} else {
				if ($tipe_mutasi == "Mutasi Pallet Antar Gudang") {
					// PROCEDURE MODE SALDO ALOKASI KURANG
					// $tmpSkuStockID = $query_cek_sku_stock_asal->row(0)->sku_stock_id;
					// $sku_stock_saldo_alokasi = $row->sku_stock_qty;
					// $query = $this->db->query("exec insertupdate_sku_stock 'saldoalokasi_kurang', '$tmpSkuStockID', NULL, $sku_stock_saldo_alokasi");

					if ($query_cek_sku_stock_tujuan->num_rows() == 0) {
						$this->db->set("sku_stock_id", "NEWID()", FALSE);
						$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
						$this->db->set("client_wms_id", $row->client_wms_id);
						$this->db->set("depo_id", $row->depo_id_tujuan);
						$this->db->set("depo_detail_id", $row->depo_detail_id_tujuan);
						$this->db->set("sku_induk_id", $row->sku_induk_id);
						$this->db->set("sku_id", $row->sku_id);
						$this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
						// $this->db->set("sku_stock_batch_no", $sku_stock_batch_no);
						$this->db->set("sku_stock_awal", "0");
						$this->db->set("sku_stock_masuk", $row->sku_stock_qty);
						$this->db->set("sku_stock_alokasi", "0");
						$this->db->set("sku_stock_saldo_alokasi", "0");
						$this->db->set("sku_stock_keluar", "0");
						$this->db->set("sku_stock_akhir", "0");
						$this->db->set("sku_stock_is_jual", "1");
						$this->db->set("sku_stock_is_aktif", "1");
						$this->db->set("sku_stock_is_deleted", "0");
						$this->db->insert("sku_stock");

						// PROCEDURE MODE KELUAR
						$tmpSkuStockID = $query_cek_sku_stock_asal->row(0)->sku_stock_id;
						$sku_stock_keluar = $row->sku_stock_qty;
						$query = $this->db->query("exec insertupdate_sku_stock 'keluar', '$tmpSkuStockID', NULL, $sku_stock_keluar");

						$queryinsert =  1;
						// return "query_cek_sku_stock_tujuan tidak ada";
					} else {
						// PROCEDURE MODE MASUK
						$tmpSkuStockID = $query_cek_sku_stock_tujuan->row(0)->sku_stock_id;
						$sku_stock_masuk = $row->sku_stock_qty;
						$query = $this->db->query("exec insertupdate_sku_stock 'masuk', '$tmpSkuStockID', NULL, $sku_stock_masuk");

						// PROCEDURE MODE KELUAR
						$tmpSkuStockID = $query_cek_sku_stock_asal->row(0)->sku_stock_id;
						$sku_stock_keluar = $row->sku_stock_qty;
						$query = $this->db->query("exec insertupdate_sku_stock 'keluar', '$tmpSkuStockID', '$row->client_wms_id', $sku_stock_keluar");

						// $this->db->set("client_wms_id", $row->client_wms_id);
						// $this->db->where("sku_stock_id", $query_cek_sku_stock_tujuan->row(0)->sku_stock_id);
						// $this->db->update("sku_stock");

						// return "query_cek_sku_stock_asal ada";

						$queryinsert =  1;
					}
				}
			}
		}

		return $queryinsert;
	}

	public function Insert_rak_lajur_detail_pallet_his($tr_mutasi_pallet_draft_id)
	{
		$query = $this->db->query("SELECT
									tr_mutasi_pallet.tr_mutasi_pallet_id,
									tr_mutasi_pallet.tr_mutasi_pallet_draft_id,
									tr_mutasi_pallet.depo_id_asal,
									tr_mutasi_pallet.depo_detail_id_asal,
									tr_mutasi_pallet.depo_id_tujuan,
									tr_mutasi_pallet.depo_detail_id_tujuan,
									tr_mutasi_pallet.tipe_mutasi_id,
									tr_mutasi_pallet_detail.pallet_id,
									tr_mutasi_pallet_detail.rak_lajur_detail_id_tujuan,
									rak_lajur_detail_pallet.rak_lajur_detail_id
									FROM tr_mutasi_pallet
									LEFT JOIN tr_mutasi_pallet_detail
									ON tr_mutasi_pallet.tr_mutasi_pallet_id = tr_mutasi_pallet_detail.tr_mutasi_pallet_id
									LEFT JOIN rak_lajur_detail_pallet
									ON rak_lajur_detail_pallet.pallet_id = tr_mutasi_pallet_detail.pallet_id
									WHERE tr_mutasi_pallet.tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'");

		foreach ($query->result() as $row) {

			//update rak_lajur_detail_id di pallet
			$this->db->set("rak_lajur_detail_id", $row->rak_lajur_detail_id_tujuan);
			$this->db->where("pallet_id", $row->pallet_id);
			$this->db->update("pallet");

			$this->db->set("rak_lajur_detail_pallet_id", "NewID()", FALSE);
			$this->db->set("depo_id", $row->depo_id_tujuan);
			$this->db->set("depo_detail_id", $row->depo_detail_id_tujuan);
			$this->db->set("rak_lajur_detail_id", $row->rak_lajur_detail_id_tujuan);
			$this->db->set("pallet_id", $row->pallet_id);
			$this->db->set("tipe_mutasi_id", $row->tipe_mutasi_id);
			$this->db->set("tanggal_create", "GETDATE()", FALSE);
			$this->db->set("who_create", $this->session->userdata('pengguna_username'));

			$this->db->insert("rak_lajur_detail_pallet_his");

			//delete rak_lajur_detail_pallet_his by pallet
			$this->db->where('rak_lajur_detail_id', $row->rak_lajur_detail_id);
			$this->db->where('pallet_id', $row->pallet_id);
			$this->db->delete('rak_lajur_detail_pallet');

			//insert le rak_lajur_detail_pallet
			$this->db->set("rak_lajur_detail_pallet_id", "NewID()", FALSE);
			$this->db->set("rak_lajur_detail_id", $row->rak_lajur_detail_id_tujuan);
			$this->db->set("pallet_id", $row->pallet_id);

			$this->db->insert("rak_lajur_detail_pallet");
		}
	}

	public function Check_MutasiPaletKode($tr_mutasi_pallet_draft_id)
	{
		$this->db->select("tr_mutasi_pallet_kode")
			->from("tr_mutasi_pallet")
			->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->tr_mutasi_pallet_kode;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Check_MutasiPaletKode2($tr_mutasi_pallet_draft_id)
	{
		$this->db->select("tr_mutasi_pallet_kode")
			->from("tr_mutasi_pallet")
			->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		$query = $this->db->get()->row()->tr_mutasi_pallet_kode;

		// if ($query->num_rows() == 0) {
		// 	$query = 0;
		// } else {
		// 	$query = $query->row(0)->tr_mutasi_pallet_kode;
		// }

		// return $this->db->last_query();
		return $query;
	}

	public function Check_RakTujuanMutasiPalet($tr_mutasi_pallet_draft_id)
	{
		$query = $this->db->query("SELECT
									tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_tujuan
									FROM tr_mutasi_pallet_draft
									LEFT JOIN tr_mutasi_pallet_detail_draft
									ON tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id = tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id
									WHERE tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'
									AND tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_tujuan is null");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_MutasiPalletById($tr_mutasi_pallet_id)
	{
		$query = $this->db->query("SELECT
									header.tr_mutasi_pallet_id,
									header.tr_mutasi_pallet_kode,
									header.tr_mutasi_pallet_draft_id,
									draft.tr_mutasi_pallet_draft_kode,
									FORMAT(header.tr_mutasi_pallet_tanggal, 'dd/MM/yyyy') AS tr_mutasi_pallet_tanggal,
									header.tr_mutasi_pallet_tgl_update,
									header.depo_id_asal,
									header.depo_detail_id_asal,
									gudang_asal.depo_detail_nama AS gudang_asal,
									header.depo_id_tujuan,
									header.depo_detail_id_tujuan,
									gudang_tujuan.depo_detail_nama AS gudang_tujuan,
									header.client_wms_id,
									client_wms.client_wms_nama,
									header.principle_id,
									principle.principle_nama,
									header.tr_mutasi_pallet_nama_checker,
									header.tipe_mutasi_id,
									tipe_mutasi.tipe_mutasi_nama AS tr_mutasi_pallet_tipe,
									header.tr_mutasi_pallet_status,
									header.tr_mutasi_pallet_keterangan
									FROM tr_mutasi_pallet header
									LEFT JOIN tr_mutasi_pallet_draft draft
									ON draft.tr_mutasi_pallet_draft_id = header.tr_mutasi_pallet_draft_id
									LEFT JOIN depo_detail gudang_asal
									ON header.depo_detail_id_asal = gudang_asal.depo_detail_id
									LEFT JOIN depo_detail gudang_tujuan
									ON header.depo_detail_id_tujuan = gudang_tujuan.depo_detail_id
									LEFT JOIN client_wms
									ON header.client_wms_id = client_wms.client_wms_id
									LEFT JOIN principle
									ON header.principle_id = principle.principle_id
									LEFT JOIN tipe_mutasi
									ON tipe_mutasi.tipe_mutasi_id = header.tipe_mutasi_id
									WHERE header.tr_mutasi_pallet_id = '$tr_mutasi_pallet_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_MutasiPalletDetailById($tr_mutasi_pallet_id)
	{
		$query = $this->db->query("SELECT
									tr_mutasi_pallet_detail.tr_mutasi_pallet_detail_id,
									tr_mutasi_pallet_detail.tr_mutasi_pallet_id,
									tr_mutasi_pallet_detail.is_valid,
									tr_mutasi_pallet_detail.pallet_id,
									pallet.pallet_kode,
									pallet.pallet_jenis_id,
									pallet_jenis.pallet_jenis_nama,
									pallet.pallet_is_aktif,
									rak_lajur.rak_id,
									rak_lajur.rak_lajur_nama,
									tr_mutasi_pallet_detail.rak_lajur_detail_id_asal AS rak_lajur_detail_id,
									rak_lajur_detail.rak_lajur_detail_nama,
									tr_mutasi_pallet_detail.rak_lajur_detail_id_tujuan,
									rak_lajur_detail_tujuan.rak_lajur_detail_nama AS rak_lajur_detail_tujuan_nama
									FROM tr_mutasi_pallet
									LEFT JOIN tr_mutasi_pallet_detail
									ON tr_mutasi_pallet_detail.tr_mutasi_pallet_id = tr_mutasi_pallet.tr_mutasi_pallet_id
									LEFT JOIN pallet
									ON tr_mutasi_pallet_detail.pallet_id = pallet.pallet_id
									LEFT JOIN pallet_jenis
									ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = tr_mutasi_pallet_detail.rak_lajur_detail_id_asal
									LEFT JOIN rak_lajur_detail AS rak_lajur_detail_tujuan
									ON rak_lajur_detail_tujuan.rak_lajur_detail_id = tr_mutasi_pallet_detail.rak_lajur_detail_id_tujuan
									LEFT JOIN rak_lajur
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									LEFT JOIN rak
									ON rak_lajur.rak_id = rak.rak_id
									WHERE tr_mutasi_pallet.tr_mutasi_pallet_id = '$tr_mutasi_pallet_id'
									ORDER BY pallet.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function get_tr_mutasi_pallet_id($tr_mutasi_pallet_draft_id)
	{
		return $this->db->select("*")->from("tr_mutasi_pallet")->where('tr_mutasi_pallet_draft_id', $tr_mutasi_pallet_draft_id)->get()->row(0)->tr_mutasi_pallet_id;
	}

	public function get_data_rak_lajur_detail($id)
	{
		// return $this->db->select("*")->from("pallet_temp")->where('delivery_order_batch_id', $id)->get()->result();
		$query = $this->db->query("SELECT
										pallet.pallet_id,
										pallet.pallet_jenis_id,
										pallet.depo_id,
										tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_tujuan AS rak_lajur_detail_id,
										pallet.pallet_kode,
										pallet.pallet_tanggal_create,
										pallet.pallet_who_create,
										pallet.pallet_is_aktif,
										pallet.pallet_is_lock
									FROM tr_mutasi_pallet_detail_draft
									LEFT JOIN pallet
									ON tr_mutasi_pallet_detail_draft.pallet_id = pallet.pallet_id
									WHERE tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id = '$id' ");
		return $query->result();
	}

	public function get_data_rak_lajur_detail2($id)
	{
		// return $this->db->select("*")->from("pallet_temp")->where('delivery_order_batch_id', $id)->get()->result();
		$query = $this->db->query("SELECT
										pallet.pallet_id,
										pallet.pallet_jenis_id,
										pallet.depo_id,
										tr_mutasi_pallet_detail.rak_lajur_detail_id_tujuan AS rak_lajur_detail_id,
										pallet.pallet_kode,
										pallet.pallet_tanggal_create,
										pallet.pallet_who_create,
										pallet.pallet_is_aktif,
										pallet.pallet_is_lock
									FROM tr_mutasi_pallet
									LEFT JOIN tr_mutasi_pallet_detail
									ON tr_mutasi_pallet_detail.tr_mutasi_pallet_id = tr_mutasi_pallet.tr_mutasi_pallet_id
									LEFT JOIN pallet
									ON tr_mutasi_pallet_detail.pallet_id = pallet.pallet_id
									WHERE tr_mutasi_pallet.tr_mutasi_pallet_draft_id = '$id' ");
		return $query->result();
	}

	public function update_data_pallet_by_params2($data)
	{
		//update rak_detail_id di tabel pallet by pallet_id
		$this->db->set("rak_lajur_detail_id", $data->rak_lajur_detail_id);
		$this->db->where("pallet_id", $data->pallet_id);
		$this->db->update("pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function delete_data_to_rak_lajur_detail_pallet($id)
	{
		$this->db->where('pallet_id', $id);
		return $this->db->delete('rak_lajur_detail_pallet');
	}

	public function insert_data_to_rak_lajur_detail_pallet_2($data)
	{

		$this->db->set("rak_lajur_detail_pallet_id", "NewID()", FALSE);
		$this->db->set("rak_lajur_detail_id", $data->rak_lajur_detail_id);
		$this->db->set("pallet_id", $data->pallet_id);

		$queryinsert = $this->db->insert("rak_lajur_detail_pallet");

		return $queryinsert;
	}

	public function check_data_rak_lajur_detail_pallet_his($pallet_id)
	{
		return $this->db->select("*")->from("rak_lajur_detail_pallet_his")
			->where("pallet_id", $pallet_id)->get()->row();
	}

	public function delete_data_to_rak_lajur_detail_pallet_his($id)
	{
		$this->db->where('pallet_id', $id);
		return $this->db->delete('rak_lajur_detail_pallet_his');
	}

	public function Exec_proses_posting_stock_card($identity, $tr_mutasi_pallet_id, $who)
	{
		$query = $this->db->query("exec proses_posting_stock_card '$identity', '$tr_mutasi_pallet_id','$who'");

		// $res = $query->result_array();

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Success
		}

		return $res;
	}

	public function Update_MutasiPalletDraft($tr_mutasi_pallet_draft_id)
	{
		//update rak_detail_id di tabel pallet by pallet_id
		$this->db->set("tr_mutasi_pallet_draft_status", "In Progress");
		$this->db->where("tr_mutasi_pallet_draft_id", $tr_mutasi_pallet_draft_id);
		$this->db->where("tr_mutasi_pallet_draft_tgl_update", 'GETDATE()', false);
		$this->db->update("tr_mutasi_pallet_draft");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}


	public function Update_MutasiPallet($tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_id, $tr_mutasi_pallet_keterangan, $tr_mutasi_pallet_status)
	{
		//update rak_detail_id di tabel pallet by pallet_id
		$this->db->set("tr_mutasi_pallet_status", $tr_mutasi_pallet_status);
		$this->db->set("tr_mutasi_pallet_keterangan", $tr_mutasi_pallet_keterangan);
		$this->db->set("tr_mutasi_pallet_tgl_update", 'GETDATE()', false);
		$this->db->where("tr_mutasi_pallet_id", $tr_mutasi_pallet_id);
		$this->db->update("tr_mutasi_pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function updateRakLajurDetailTujuanInTrMutasi($tr_mutasi_pallet_detail_draft_id, $rak_detail_id)
	{
		$this->db->set("rak_lajur_detail_id_tujuan", $rak_detail_id);
		$this->db->where("tr_mutasi_pallet_detail_draft_id", $tr_mutasi_pallet_detail_draft_id);
		return $this->db->update("tr_mutasi_pallet_detail_draft");
	}

	public function updateRakLajurDetailTujuanInTrMutasi2($tr_mutasi_pallet_detail_id, $rak_detail_id)
	{
		$this->db->set("rak_lajur_detail_id_tujuan", $rak_detail_id);
		$this->db->where("tr_mutasi_pallet_detail_id", $tr_mutasi_pallet_detail_id);
		return $this->db->update("tr_mutasi_pallet_detail");
	}

	public function updatePalletIsLock($pallet_id)
	{
		$this->db->set("pallet_is_lock_reason", NULL);
		$this->db->set("pallet_is_lock", 0);
		$this->db->where("pallet_id", $pallet_id);
		return $this->db->update("pallet");
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

	public function getKodeAutoComplete($value, $type)
	{
		if ($type == 'notone') {
			return $this->db->select("CONCAT(REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 2)), '/', REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 3))) as kode")
				->from("pallet")
				->where("depo_id", $this->session->userdata('depo_id'))
				->like("pallet_kode", $value)->get()->result();
		}

		if ($type == 'one') {
			return $this->db->select("rak_lajur_detail_nama as kode")
				->from("rak_lajur_detail")
				->like("rak_lajur_detail_nama", $value)->get()->result();
		}
	}
	public function GetTglUpdate($tr_mutasi_pallet_id)
	{
		return $this->db->query("select ISNULL(tr_mutasi_pallet_tgl_update,'') as tgl_update from tr_mutasi_pallet where tr_mutasi_pallet_id = '$tr_mutasi_pallet_id'")->row();
	}
}
