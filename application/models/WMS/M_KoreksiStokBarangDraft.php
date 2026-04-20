<?php

date_default_timezone_set('Asia/Jakarta');

class M_KoreksiStokBarangDraft extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function get_data_koreksi_stok_draft_by_filter($filter_no_koreksi_draft, $tgl1, $tgl2, $filter_gudang_asal_koreksi_draft, $filter_koreksi_draft_tipe_transaksi, $filter_koreksi_draft_principle, $filter_koreksi_draft_checker, $filter_koreksi_draft_status)
	{
		$this->db->select("tksd.tr_koreksi_stok_draft_id as id,
                        tksd.tr_koreksi_stok_draft_kode as kode,
                        FORMAT(tksd.tr_koreksi_stok_draft_tanggal, 'dd-MM-yyyy') as tgl,
                        tm.tipe_mutasi_nama as tipe,
                        p.principle_kode as principle,
                        tksd.tr_koreksi_stok_draft_nama_checker as checker,
                        dd.depo_detail_nama as gudang,
                        tksd.tr_koreksi_stok_draft_status as status");
		$this->db->from("tr_koreksi_stok_draft tksd");
		$this->db->join("tipe_mutasi tm", "tksd.tipe_mutasi_id = tm.tipe_mutasi_id", "left");
		$this->db->join("principle p", "tksd.principle_id = p.principle_id", "left");
		$this->db->join("depo_detail dd", "tksd.depo_detail_id_asal = dd.depo_detail_id", "left");
		$this->db->where("FORMAT(tksd.tr_koreksi_stok_draft_tanggal, 'yyyy-MM-dd') >=", $tgl1);
		$this->db->where("FORMAT(tksd.tr_koreksi_stok_draft_tanggal, 'yyyy-MM-dd') <=", $tgl2);
		$this->db->where("tm.tipe_mutasi_nama <>", "Pemusnahan");
		if ($filter_no_koreksi_draft != "") {
			$this->db->where("tksd.tr_koreksi_stok_draft_id", $filter_no_koreksi_draft);
		}
		// if ($filter_koreksi_draft_tgl_draft != "") {
		//   $this->db->where("tksd.tr_koreksi_stok_draft_tanggal", $filter_koreksi_draft_tgl_draft);
		// }
		if ($filter_gudang_asal_koreksi_draft != "") {
			$this->db->where("tksd.depo_detail_id_asal", $filter_gudang_asal_koreksi_draft);
		}
		if ($filter_koreksi_draft_tipe_transaksi != "") {
			$this->db->where("tksd.tipe_mutasi_id", $filter_koreksi_draft_tipe_transaksi);
		}
		if ($filter_koreksi_draft_principle != "") {
			$this->db->where("tksd.principle_id", $filter_koreksi_draft_principle);
		}
		if ($filter_koreksi_draft_checker != "") {
			$this->db->where("tksd.tr_koreksi_stok_draft_nama_checker", $filter_koreksi_draft_checker);
		}
		if ($filter_koreksi_draft_status != "") {
			$this->db->where("tksd.tr_koreksi_stok_draft_status", $filter_koreksi_draft_status);
		}
		return $this->db->get()->result();
		// return $this->db->last_query();
	}

	public function data_corrections_draft()
	{
		return $this->db->select("tr_koreksi_stok_draft_id as id, tr_koreksi_stok_draft_kode as kode")
			->from("tr_koreksi_stok_draft")->where("depo_id_asal", $this->session->userdata("depo_id"))->order_by("tr_koreksi_stok_draft_kode", "ASC")->get()->result();
	}

	public function get_warehouses()
	{
		return $this->db->select("depo_detail_id as id, depo_detail_nama as nama")
			->from("depo_detail")->where("depo_id", $this->session->userdata("depo_id"))->order_by("depo_detail_nama", "ASC")->get()->result();
	}

	public function get_principles()
	{
		return $this->db->select("principle_id as id, principle_kode as nama")
			->from("principle")->order_by("principle_kode", "ASC")->get()->result();
	}

	public function get_type_transactions()
	{
		return $this->db->select("tipe_mutasi_id as id, tipe_mutasi_nama as nama")
			->from("tipe_mutasi")->order_by("tipe_mutasi_nama", "ASC")->where("tipe_mutasi_flag", "K")
			->where_not_in("tipe_mutasi_nama", array("Pemusnahan", "Retur Supplier", "Koreksi Pallet"))->get()->result();
	}

	public function get_type_document()
	{
		return $this->db->select("*")->from("dbo.gettipedokumen()")->where("flag", "1")->get()->result();
	}

	public function get_type_document_by_id($id)
	{
		return $this->db->select("*")->from("dbo.gettipedokumen()")->where("flag", "1")->where('tipe_dokumen_nama', $id)->get()->row();
	}

	public function get_checker_by_principleId($id)
	{
		return $this->db->select("k.karyawan_id as id, k.karyawan_nama as nama")
			->from("karyawan k")
			->join("karyawan_principle kp", "k.karyawan_id = kp.karyawan_id")
			->where("kp.principle_id", $id)->where("k.karyawan_level_id", "0C2CC2B3-B26C-4249-88BE-77BD0BA61C41")
			->where("k.depo_id", $this->session->userdata('depo_id'))->order_by("k.karyawan_nama", "ASC")->get()->result();
	}

	public function get_DepoNameBySession()
	{
		return $this->db->select("depo_id, depo_nama")
			->from("depo")
			->where("depo_id", $this->session->userdata('depo_id'))->get()->row();
	}

	public function get_BrandByPrincipleId($id)
	{
		return $this->db->select("principle_brand_id as id, principle_brand_nama as nama")
			->from("principle_brand")
			->where("principle_id", $id)->order_by("principle_brand_nama", "ASC")->get()->result_array();
	}

	public function get_SKUIndukByPrincipleId($id)
	{
		return $this->db->select("sku_induk_id as id, sku_induk_nama as nama")
			->from("sku_induk")
			->where("principle_id", $id)->order_by("sku_induk_nama", "ASC")->get()->result_array();
	}

	public function search_filter_chosen_sku($depo, $gudang, $principle, $brand, $sku_induk, $nama_sku, $sku_kode_wms, $sku_kode_pabrik)
	{
		$this->db->select(" sk.sku_stock_id as id,
                        si.sku_induk_nama as sku_induk,
                        s.sku_nama_produk as sku,
                        s.sku_kemasan as kemasan,
                        s.sku_satuan as satuan,
                        p.principle_kode as principle,
                        pb.principle_brand_nama as brand,
                        format(sk.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
                        (sk.sku_stock_awal + sku_stock_masuk) - sk.sku_stock_saldo_alokasi - sk.sku_stock_keluar as qty_available")
			->from("sku_stock sk")
			->join("sku s", "sk.sku_id = s.sku_id", "left")
			->join("sku_induk si", "sk.sku_induk_id = si.sku_induk_id", "left")
			->join("principle p", "s.principle_id = p.principle_id", "left")
			->join("principle_brand pb", "s.principle_brand_id = pb.principle_brand_id", "left")
			->where("sk.depo_id", $depo)
			->where("sk.depo_detail_id", $gudang)
			->where("s.principle_id", $principle);
		if ($brand != "") {
			$this->db->where("s.principle_brand_id", $brand);
		}
		if ($sku_induk != "") {
			$this->db->where("sk.sku_induk_id", $sku_induk);
		}
		if ($nama_sku != "") {
			$this->db->where("s.sku_nama_produk", $nama_sku);
		}
		if ($sku_kode_wms != "") {
			$this->db->where("s.sku_kode", $sku_kode_wms);
		}
		if ($sku_kode_pabrik != "") {
			$this->db->where("s.sku_kode_sku_principle", $sku_kode_pabrik);
		}
		$data = $this->db->get();
		return $data->result();
	}

	public function get_data_sku_by_id($id)
	{
		return $this->db->select(" sk.sku_stock_id as id,
                        si.sku_induk_nama as sku_induk,
                        s.sku_id as sku_id,
                        s.sku_nama_produk as sku,
                        s.sku_kemasan as kemasan,
                        s.sku_satuan as satuan,
                        s.sku_kode as sku_kode,
                        p.principle_kode as principle,
                        pb.principle_brand_nama as brand,
                        format(sk.sku_stock_expired_date, 'yyyy-MM-dd') as ed,
                        (sk.sku_stock_awal + sku_stock_masuk) - sku_stock_saldo_alokasi - sku_stock_keluar as qty_available")
			->from("sku_stock sk")
			->join("sku s", "sk.sku_id = s.sku_id", "left")
			->join("sku_induk si", "sk.sku_induk_id = si.sku_induk_id", "left")
			->join("principle p", "s.principle_id = p.principle_id", "left")
			->join("principle_brand pb", "s.principle_brand_id = pb.principle_brand_id", "left")
			->where_in("sk.sku_stock_id", $id)->get()->result();
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function insert_to_tr_koreksi_draft($kpd_id, $generate_kode, $tgl, $gudang, $principle, $checker, $tipe, $status, $keterangan, $tipeDokumen, $noReferensiDokumen, $name_file)
	{
		$tgl_ = $tgl . " " . date('H:i:s');
		$ket = $keterangan == "" ? null : $keterangan;
		$tipeDokumen = $tipeDokumen == "undefined" ? null : $tipeDokumen;
		$noReferensiDokumen = $noReferensiDokumen == "" ? null : $noReferensiDokumen;
		$name_file = $name_file == null ? null : $name_file;
		// $tgl_ = date_create(str_replace("/", "-", $tgl));
		$this->db->set("tr_koreksi_stok_draft_id", $kpd_id);
		$this->db->set("principle_id", $principle);
		$this->db->set("tr_koreksi_stok_draft_kode", $generate_kode);
		$this->db->set("tr_koreksi_stok_draft_tanggal", $tgl_);
		$this->db->set("tipe_mutasi_id", $tipe);
		$this->db->set("tr_koreksi_stok_draft_keterangan", $ket);
		$this->db->set("tr_koreksi_stok_draft_status", $status);
		$this->db->set("depo_id_asal", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id_asal", $gudang);
		$this->db->set("tr_koreksi_stok_draft_tgl_create", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_draft_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("tr_koreksi_stok_draft_nama_checker", $checker);
		$this->db->set("tipe_dokumen", $tipeDokumen);
		$this->db->set("no_referensi_dokumen", $noReferensiDokumen);
		$this->db->set("attachment", $name_file);

		$this->db->set("tr_koreksi_stok_draft_tgl_update", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_draft_who_update", $this->session->userdata('pengguna_username'));

		$queryinsert = $this->db->insert("tr_koreksi_stok_draft");

		return $queryinsert;
	}

	public function insert_to_tr_koreksi_detail_draft($kpd_id, $data)
	{
		$this->db->set("tr_koreksi_stok_detail_draft_id", "NewID()", FALSE);
		$this->db->set("tr_koreksi_stok_draft_id", $kpd_id);
		$this->db->set("sku_id", $data->sku_id);
		$this->db->set("sku_stock_id", $data->sku_stock_id);
		$this->db->set("sku_qty_plan_koreksi", $data->qty_plan);

		$queryinsert = $this->db->insert("tr_koreksi_stok_detail_draft");
		return $queryinsert;
	}

	public function getDataKoreksiDraftHeader($id)
	{
		return $this->db->select("tksd.tr_koreksi_stok_draft_id as id,
                              tksd.principle_id as principle_id,
                              tksd.tr_koreksi_stok_draft_kode as kode,
                              FORMAT(tksd.tr_koreksi_stok_draft_tanggal, 'yyyy-MM-dd') as tgl,
															tksd.tr_koreksi_stok_draft_tgl_update,
                              tksd.tipe_mutasi_id as tipe_id,
                              ISNULL(tksd.tr_koreksi_stok_draft_keterangan, '') as keterangan,
                              tksd.tr_koreksi_stok_draft_status as status,
                              tksd.depo_detail_id_asal as gudang_id, 
                              k.karyawan_id as checker_id,
							  tksd.tipe_dokumen,
							  tksd.no_referensi_dokumen,
							  tksd.attachment")
			->from("tr_koreksi_stok_draft tksd")
			->join("karyawan k", "tksd.tr_koreksi_stok_draft_nama_checker = k.karyawan_nama", "left")
			->where("tksd.tr_koreksi_stok_draft_id", $id)->get()->row();
	}

	public function getDataKoreksiDraftDetail($id)
	{
		return $this->db->select("tksdd.sku_stock_id as id,
                              tksdd.sku_id as sku_id,
                              s.sku_kode as sku_kode,
                              s.sku_nama_produk as sku_nama,
                              pb.principle_brand_nama as brand,
                              s.sku_satuan as sku_satuan,
                              s.sku_kemasan as sku_kemasan,
                              format(ss.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
                              (ss.sku_stock_awal + ss.sku_stock_masuk) - ss.sku_stock_saldo_alokasi - ss.sku_stock_keluar as qty_available,
                              tksdd.sku_qty_plan_koreksi as qty_plan")
			->from("tr_koreksi_stok_detail_draft tksdd")
			->join("sku s", "tksdd.sku_id = s.sku_id", "left")
			->join("sku_stock ss", "tksdd.sku_stock_id = ss.sku_stock_id", "left")
			->join("principle_brand pb", "s.principle_brand_id = pb.principle_brand_id", "left")
			->where("tksdd.tr_koreksi_stok_draft_id", $id)->get()->result();
	}

	public function update_to_tr_koreksi_draft($id, $gudang, $principle, $checker, $tipe, $status, $keterangan, $tipeDokumen, $noReferensiDokumen, $name_file)
	{
		$ket = $keterangan == "" ? null : $keterangan;
		$tipeDokumen = $tipeDokumen == "undefined" ? null : $tipeDokumen;
		$noReferensiDokumen = $noReferensiDokumen == "" ? null : $noReferensiDokumen;
		$name_file = $name_file == null ? null : $name_file;

		$this->db->set("principle_id", $principle);
		$this->db->set("tipe_mutasi_id", $tipe);
		$this->db->set("tr_koreksi_stok_draft_keterangan", $ket);
		$this->db->set("tr_koreksi_stok_draft_status", $status);
		$this->db->set("depo_detail_id_asal", $gudang);
		$this->db->set("tr_koreksi_stok_draft_nama_checker", $checker);

		$this->db->set("tipe_dokumen", $tipeDokumen);
		$this->db->set("no_referensi_dokumen", $noReferensiDokumen);

		if ($name_file != null) {
			$this->db->set("attachment", $name_file);
		}

		$this->db->set("tr_koreksi_stok_draft_tgl_update", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_draft_who_update", $this->session->userdata('pengguna_username'));

		$this->db->where("tr_koreksi_stok_draft_id", $id);
		return $this->db->update("tr_koreksi_stok_draft");
	}

	public function delete_to_tr_koreksi_detail_draft($id)
	{
		$this->db->where('tr_koreksi_stok_draft_id', $id);
		return $this->db->delete('tr_koreksi_stok_detail_draft');
	}

	public function update_to_tr_koreksi_detail_draft($id, $data)
	{
		$this->db->set("tr_koreksi_stok_detail_draft_id", "NewID()", FALSE);
		$this->db->set("tr_koreksi_stok_draft_id", $id);
		$this->db->set("sku_id", $data->sku_id);
		$this->db->set("sku_stock_id", $data->sku_stock_id);
		$this->db->set("sku_qty_plan_koreksi", $data->qty_plan);

		$queryinsert = $this->db->insert("tr_koreksi_stok_detail_draft");
		return $queryinsert;
	}

	public function autoCompleteReferensiDokumen($reffDokumen, $tableName, $tableNameKode, $tipeDokumen)
	{
		if ($tipeDokumen == 'Mutasi Pallet Antar Gudang') {
			return $this->db->select("$tableNameKode AS kode")
				->from($tableName)
				->where("depo_id_asal", $this->session->userdata('depo_id'))
				->where("tipe_mutasi_id", 'E81C03FB-7B0F-4D04-A971-D8D7232E4A69')
				->like($tableNameKode, $reffDokumen)
				->order_by($tableNameKode, "ASC")->get()->result();
		} else if ($tipeDokumen == 'Mutasi Pallet Antar Rak') {
			return $this->db->select("$tableNameKode AS kode")
				->from($tableName)
				->where("depo_id_asal", $this->session->userdata('depo_id'))
				->where("tipe_mutasi_id", 'C02CC613-2FAB-49A9-9372-B27D9444FE93')
				->like($tableNameKode, $reffDokumen)
				->order_by($tableNameKode, "ASC")->get()->result();
		} else if ($tipeDokumen == 'Koreksi Stok Barang') {
			return $this->db->select("$tableNameKode AS kode")
				->from($tableName)
				->where("depo_id_asal", $this->session->userdata('depo_id'))
				->like($tableNameKode, $reffDokumen)
				->order_by($tableNameKode, "ASC")->get()->result();
		} else {
			return $this->db->select("$tableNameKode AS kode")
				->from($tableName)
				->where("depo_id", $this->session->userdata('depo_id'))
				->like($tableNameKode, $reffDokumen)
				->order_by($tableNameKode, "ASC")->get()->result();
		}
	}
}
