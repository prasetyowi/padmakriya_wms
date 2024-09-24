<?php

date_default_timezone_set("Asia/Jakarta");

class M_PemusnahanDraft extends CI_Model
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
									AND depo_detail_is_bs = 1
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
									WHERE tipe_mutasi_id IN ('9246A374-B798-442B-8EE0-631EE6ADA7C9','86A1887B-28F2-4F81-86C1-84650B2F2FEC')
									ORDER BY tipe_mutasi_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function get_data_pemusnahan_draft_by_filter($filter_no_koreksi_draft, $filter_koreksi_draft_tgl_draft, $filter_gudang_asal_koreksi_draft, $filter_koreksi_draft_tipe_transaksi, $filter_koreksi_draft_principle, $filter_koreksi_draft_checker, $filter_koreksi_draft_status)
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
		$this->db->where_in("tksd.tipe_mutasi_id", array("9246A374-B798-442B-8EE0-631EE6ADA7C9", "86A1887B-28F2-4F81-86C1-84650B2F2FEC"));
		$this->db->where("format(tksd.tr_koreksi_stok_draft_tanggal,'yyyy-MM-dd')", $filter_koreksi_draft_tgl_draft);
		if ($filter_no_koreksi_draft != "") {
			$this->db->where("tksd.tr_koreksi_stok_draft_id", $filter_no_koreksi_draft);
		}
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

		// return $this->db->last_query();
		return $this->db->get()->result();
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
			->where_not_in("tipe_mutasi_nama", array("Pemusnahan", "Retur Supplier"))->get()->result();
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
			->where("sk.depo_id", $this->session->userdata('depo_id'))
			->where("sk.depo_detail_id", $gudang)
			->where("s.principle_id", $principle)
			->where("(sk.sku_stock_awal + sku_stock_masuk) - sk.sku_stock_saldo_alokasi - sk.sku_stock_keluar !=", null)
			->where("(sk.sku_stock_awal + sku_stock_masuk) - sk.sku_stock_saldo_alokasi - sk.sku_stock_keluar !=", 0);
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

		// return $this->db->last_query();
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
                        format(sk.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
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

	public function insert_to_tr_koreksi_draft($kpd_id, $generate_kode, $tgl, $gudang, $principle, $checker, $tipe, $status, $keterangan, $ekspedisi, $driver, $kendaraan, $nopol)
	{
		$tgl = $tgl . " " . date('H:i:s');
		$ket = $keterangan == "" ? null : $keterangan;
		$ekspedisi = $ekspedisi == "" ? null : $ekspedisi;
		$driver = $driver == "" ? null : $driver;
		$kendaraan = $kendaraan == "" ? null : $kendaraan;
		$nopol = $nopol == "" ? null : $nopol;
		// $tgl_ = date_create(str_replace("/", "-", $tgl));
		$this->db->set("tr_koreksi_stok_draft_id", $kpd_id);
		$this->db->set("principle_id", $principle);
		$this->db->set("tr_koreksi_stok_draft_kode", $generate_kode);
		$this->db->set("tr_koreksi_stok_draft_tanggal", $tgl);
		$this->db->set("tipe_mutasi_id", $tipe);
		$this->db->set("tr_koreksi_stok_draft_keterangan", $ket);
		$this->db->set("tr_koreksi_stok_draft_status", $status);
		$this->db->set("depo_id_asal", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id_asal", $gudang);
		$this->db->set("tr_koreksi_stok_draft_tgl_create", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_draft_tgl_update", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_draft_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("tr_koreksi_stok_draft_nama_checker", $checker);
		$this->db->set("ekspedisi_id", $ekspedisi);
		$this->db->set("tr_koreksi_stok_draft_pengemudi", $driver);
		$this->db->set("tr_koreksi_stok_draft_kendaraan", $kendaraan);
		$this->db->set("tr_koreksi_stok_draft_nopol", $nopol);

		$queryinsert = $this->db->insert("tr_koreksi_stok_draft");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_to_tr_koreksi_detail_draft($kpd_id, $data)
	{
		$this->db->set("tr_koreksi_stok_detail_draft_id", "NewID()", FALSE);
		$this->db->set("tr_koreksi_stok_draft_id", $kpd_id);
		$this->db->set("sku_id", $data['sku_id']);
		$this->db->set("sku_stock_id", $data['sku_stock_id']);
		$this->db->set("sku_qty_plan_koreksi", $data['qty_plan']);

		$queryinsert = $this->db->insert("tr_koreksi_stok_detail_draft");
		return $queryinsert;
	}

	public function getDataPemusnahanDraftHeader($id)
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
							  tksd.ekspedisi_id,
							  e.ekspedisi_nama,
							  tksd.tr_koreksi_stok_draft_pengemudi,
							  tksd.tr_koreksi_stok_draft_kendaraan,
							  tksd.tr_koreksi_stok_draft_nopol")
			->from("tr_koreksi_stok_draft tksd")
			->join("karyawan k", "tksd.tr_koreksi_stok_draft_nama_checker = k.karyawan_nama", "left")
			->join("ekspedisi e", "e.ekspedisi_id = tksd.ekspedisi_id", "left")
			->where("tksd.tr_koreksi_stok_draft_id", $id)->get()->row();
	}

	public function getDataPemusnahanDraftDetail($id)
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

	public function update_to_tr_koreksi_draft($id, $gudang, $principle, $checker, $tipe, $status, $keterangan, $ekspedisi, $driver, $kendaraan, $nopol)
	{
		$ket = $keterangan == "" ? null : $keterangan;
		$ekspedisi = $ekspedisi == "" ? null : $ekspedisi;
		$driver = $driver == "" ? null : $driver;
		$kendaraan = $kendaraan == "" ? null : $kendaraan;
		$nopol = $nopol == "" ? null : $nopol;
		$this->db->set("principle_id", $principle);
		$this->db->set("tipe_mutasi_id", $tipe);
		$this->db->set("tr_koreksi_stok_draft_keterangan", $ket);
		$this->db->set("tr_koreksi_stok_draft_status", $status);
		$this->db->set("depo_detail_id_asal", $gudang);
		$this->db->set("tr_koreksi_stok_draft_nama_checker", $checker);
		$this->db->set("ekspedisi_id", $ekspedisi);
		$this->db->set("tr_koreksi_stok_draft_pengemudi", $driver);
		$this->db->set("tr_koreksi_stok_draft_kendaraan", $kendaraan);
		$this->db->set("tr_koreksi_stok_draft_nopol", $nopol);
		$this->db->set("tr_koreksi_stok_draft_tgl_update", 'GETDATE()', false);
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
		$this->db->set("sku_id", $data['sku_id']);
		$this->db->set("sku_stock_id", $data['sku_stock_id']);
		$this->db->set("sku_qty_plan_koreksi", $data['qty_plan']);

		$queryinsert = $this->db->insert("tr_koreksi_stok_detail_draft");
		return $queryinsert;
	}

	public function get_ekspedisi()
	{
		$query = $this->db->query("SELECT
										*
									FROM ekspedisi
									ORDER BY ekspedisi_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function get_driver()
	{
		$query = $this->db->query("SELECT
										*
									FROM karyawan
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND karyawan_level_id = '339D8AC2-C6CE-4B47-9BFC-E372592AF521'
									ORDER BY karyawan_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function get_kendaraan()
	{
		$query = $this->db->query("SELECT
										kendaraan_model
									FROM kendaraan
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									GROUP BY kendaraan_model
									ORDER BY kendaraan_model ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function get_nopol($kendaraan_model)
	{
		$query = $this->db->query("SELECT
										*
									FROM kendaraan
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND kendaraan_model = '$kendaraan_model'
									ORDER BY kendaraan_nopol ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
}
