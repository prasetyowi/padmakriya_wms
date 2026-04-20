<?php

date_default_timezone_set('Asia/Jakarta');

class M_KoreksiStokBarang extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}




	public function get_data_koreksi_stok_by_filter($filter_no_koreksi, $tgl1, $tgl2, $filter_gudang_asal_koreksi, $filter_koreksi_tipe_transaksi, $filter_koreksi_principle, $filter_koreksi_checker, $filter_koreksi_status)
	{
		$this->db->select("tks.tr_koreksi_stok_id as id,
                        tks.tr_koreksi_stok_kode as kode,
                        FORMAT(tks.tr_koreksi_stok_tanggal, 'dd-MM-yyyy') as tgl,
                        tm.tipe_mutasi_nama as tipe,
                        p.principle_kode as principle,
                        tks.tr_koreksi_stok_nama_checker as checker,
                        dd.depo_detail_nama as gudang,
                        tks.tr_koreksi_stok_status as status");
		$this->db->from("tr_koreksi_stok tks");
		$this->db->join("tipe_mutasi tm", "tks.tipe_mutasi_id = tm.tipe_mutasi_id", "left");
		$this->db->join("principle p", "tks.principle_id = p.principle_id", "left");
		$this->db->join("depo_detail dd", "tks.depo_detail_id_asal = dd.depo_detail_id", "left");
		$this->db->where("FORMAT(tks.tr_koreksi_stok_tanggal, 'yyyy-MM-dd') >=", $tgl1);
		$this->db->where("FORMAT(tks.tr_koreksi_stok_tanggal, 'yyyy-MM-dd') <=", $tgl2);
		$this->db->where_in("tm.tipe_mutasi_nama", array('Koreksi Masuk', 'Koreksi Keluar'));
		$this->db->where("tm.tipe_mutasi_flag_2", "Other");
		if ($filter_no_koreksi != "") {
			$this->db->where("tks.tr_koreksi_stok_id", $filter_no_koreksi);
		}
		// if ($filter_koreksi_tgl != "") {
		//   $this->db->where("tks.tr_koreksi_stok_tanggal", $filter_koreksi_tgl);
		// }
		if ($filter_gudang_asal_koreksi != "") {
			$this->db->where("tks.depo_detail_id_asal", $filter_gudang_asal_koreksi);
		}
		if ($filter_koreksi_tipe_transaksi != "") {
			$this->db->where("tks.tipe_mutasi_id", $filter_koreksi_tipe_transaksi);
		}
		if ($filter_koreksi_principle != "") {
			$this->db->where("tks.principle_id", $filter_koreksi_principle);
		}
		if ($filter_koreksi_checker != "") {
			$this->db->where("tks.tr_koreksi_stok_nama_checker", $filter_koreksi_checker);
		}
		if ($filter_koreksi_status != "") {
			$this->db->where("tks.tr_koreksi_stok_status", $filter_koreksi_status);
		}
		return $this->db->get()->result();
	}

	public function get_stock_corrections()
	{
		return $this->db->select("tr_koreksi_stok_id as id, tr_koreksi_stok_kode as kode")
			->from("tr_koreksi_stok")->order_by("tr_koreksi_stok_kode", "ASC")->get()->result();
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
			->from("tipe_mutasi")->order_by("tipe_mutasi_nama", "ASC")
			->where("tipe_mutasi_flag_2", "Other")->where("tipe_mutasi_nama !=", "Pemusnahan")->get()->result();
	}

	public function getNoKoreksiDraft()
	{
		return $this->db->select("tr_koreksi_stok_draft_id as id, tr_koreksi_stok_draft_kode as kode")
			->from("tr_koreksi_stok_draft")
			->where("tr_koreksi_stok_draft_status", "Approved")
			->where("tr_koreksi_stok_draft_status <>", "Completed")
			->order_by("tr_koreksi_stok_draft_kode", "ASC")->get()->result();
	}

	public function get_checker_by_principleId($id)
	{
		return $this->db->select("k.karyawan_id as id, k.karyawan_nama as nama")
			->from("karyawan k")
			->join("karyawan_principle kp", "k.karyawan_id = kp.karyawan_id")
			->where("kp.principle_id", $id)->where("k.karyawan_level_id", "0C2CC2B3-B26C-4249-88BE-77BD0BA61C41")
			->where("k.depo_id", $this->session->userdata('depo_id'))->order_by("k.karyawan_nama", "ASC")->get()->result();
	}

	public function getDataKoreksiDraftHeader($id)
	{
		return $this->db->select("tksd.tr_koreksi_stok_draft_id as id,
                              tksd.principle_id as principle_id,
                              p.principle_kode as principle,
                              tksd.tipe_mutasi_id as tipe_id,
                              tm.tipe_mutasi_nama as tipe,
                              tksd.tr_koreksi_stok_draft_status as status,
                              tksd.depo_detail_id_asal as gudang_id,
                              dd.depo_detail_nama as gudang, 
                              k.karyawan_id as checker_id,
                              k.karyawan_nama as checker,
							  tksd.tipe_dokumen,
							  tksd.no_referensi_dokumen,
							  tksd.attachment,
							tksd.tr_koreksi_stok_draft_tgl_update")
			->from("tr_koreksi_stok_draft tksd")
			->join("principle p", "tksd.principle_id = p.principle_id", "left")
			->join("depo_detail dd", "tksd.depo_detail_id_asal = dd.depo_detail_id", "left")
			->join("tipe_mutasi tm", "tksd.tipe_mutasi_id = tm.tipe_mutasi_id", "left")
			->join("karyawan k", "tksd.tr_koreksi_stok_draft_nama_checker = k.karyawan_nama", "left")
			->where("tksd.tr_koreksi_stok_draft_id", $id)->get()->row();
	}

	public function getDataKoreksiDraftDetail($id)
	{
		return $this->db->select("tr_koreksi_stok_detail_draft_id as detail_id,
                              tr_koreksi_stok_draft_id as id,
                              sku_stock_id as sku_stock_id,
                              sku_id as sku_id,
                              sku_qty_plan_koreksi as qty_plan")
			->from("tr_koreksi_stok_detail_draft")
			->where("tr_koreksi_stok_draft_id", $id)->get()->result();
	}

	public function getDataKoreksiHeaderEdit($id)
	{
		return $this->db->select("tks.tr_koreksi_stok_id as id,
                              tks.tr_koreksi_stok_draft_id as draft_id,
                              tks.principle_id as principle_id,
                              p.principle_kode as principle,
                              tks.tipe_mutasi_id as tipe_id,
                              tks.tr_koreksi_stok_keterangan as keterangan,
                              tm.tipe_mutasi_nama as tipe,
                              tks.tr_koreksi_stok_status as status,
                              FORMAT(tks.tr_koreksi_stok_tanggal, 'yyyy-MM-dd') as tgl,
                              tks.tr_koreksi_stok_kode as kode,
                              tksd.tr_koreksi_stok_draft_kode as kode_draft,
                              tks.depo_detail_id_asal as gudang_id,
                              tks.tipe_dokumen,
                              tks.no_referensi_dokumen,
                              tks.attachment,
                              dd.depo_detail_nama as gudang, 
                              k.karyawan_id as checker_id,
                              k.karyawan_nama as checker,
							  tks.tr_koreksi_stok_pallet_tgl_update,
							  tksd.tr_koreksi_stok_draft_tgl_update")
			->from("tr_koreksi_stok tks")
			->join("tr_koreksi_stok_draft tksd", "tks.tr_koreksi_stok_draft_id = tksd.tr_koreksi_stok_draft_id", "left")
			->join("principle p", "tks.principle_id = p.principle_id", "left")
			->join("depo_detail dd", "tks.depo_detail_id_asal = dd.depo_detail_id", "left")
			->join("tipe_mutasi tm", "tks.tipe_mutasi_id = tm.tipe_mutasi_id", "left")
			->join("karyawan k", "tks.tr_koreksi_stok_nama_checker = k.karyawan_nama", "left")
			->where("tks.tr_koreksi_stok_id", $id)->get()->row();
	}

	public function check_exist_data_in_detail_temp($id)
	{
		return $this->db->select("*")->from("tr_koreksi_stok_detail_temp")->where("tr_koreksi_stok_id", $id)->get()->row();
	}

	public function getDataKoreksiDraftDetailTemp($id)
	{
		return $this->db->select("tksd.sku_stock_id as id,
                              tksd.sku_id as sku_id,
                              s.sku_kode as sku_kode,
                              s.sku_nama_produk as sku_nama,
                              pb.principle_brand_nama as brand,
                              s.sku_satuan as sku_satuan,
                              s.sku_kemasan as sku_kemasan,
                              format(ss.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
                              format(ss.sku_stock_expired_date, 'yyyy-MM-dd') as ed_,
                              ss.depo_id as depo,
                              ss.depo_detail_id as depo_detail,
                              ss.sku_induk_id as sku_induk_id,
                              tksd.sku_qty_plan_koreksi as qty_plan,
                              SUM(tksd2.sku_qty_aktual_koreksi) as qty_aktual")
			->from("tr_koreksi_stok_detail_temp tksd")
			->join("tr_koreksi_stok_detail2_temp tksd2", "tksd.tr_koreksi_stok_detail_draft_id = tksd2.tr_koreksi_stok_detail_draft_id", "left")
			->join("sku s", "tksd.sku_id = s.sku_id", "left")
			->join("sku_stock ss", "tksd.sku_stock_id = ss.sku_stock_id", "left")
			->join("principle_brand pb", "s.principle_brand_id = pb.principle_brand_id", "left")
			->group_by("tksd.sku_stock_id,tksd.sku_id,s.sku_kode,s.sku_nama_produk,pb.principle_brand_nama,s.sku_satuan,s.sku_kemasan,format(ss.sku_stock_expired_date, 'dd-MM-yyyy'), format(ss.sku_stock_expired_date, 'yyyy-MM-dd'), ss.depo_id, ss.depo_detail_id, ss.sku_induk_id, tksd.sku_qty_plan_koreksi")
			->where("tksd.tr_koreksi_stok_id", $id)->get()->result();
	}

	public function insertDataToTableDetailTemp($data)
	{
		$this->db->set("tr_koreksi_stok_detail_draft_id", $data->detail_id);
		$this->db->set("tr_koreksi_stok_id", $data->id);
		$this->db->set("sku_id", $data->sku_id);
		$this->db->set("sku_stock_id", $data->sku_stock_id);
		$this->db->set("sku_qty_plan_koreksi", $data->qty_plan);

		$queryinsert = $this->db->insert("tr_koreksi_stok_detail_temp");

		return $queryinsert;
	}

	public function getDataPalletBySkuStockId($id, $gudang_id, $ed)
	{
		return $this->db->select("tksd.tr_koreksi_stok_detail_draft_id as id,
                              tksd.tr_koreksi_stok_draft_id as koreksi_id,
                              p.pallet_id as pallet_id")
			->from("sku_stock ss")
			->join("pallet_detail pd", "ss.sku_stock_id = pd.sku_stock_id", "left")
			->join("pallet p", "pd.pallet_id = p.pallet_id", "left")
			->join("tr_koreksi_stok_detail_draft tksd", "ss.sku_stock_id = tksd.sku_stock_id", "left")
			->where("ss.depo_detail_id", $gudang_id)
			->where("ss.sku_stock_id", $id)
			->where("FORMAT(pd.sku_stock_expired_date, 'dd-MM-yyyy') = '$ed'")
			->get()->result();
	}

	public function check_exist_data_in_detail2_temp($data)
	{
		return $this->db->select("*")->from("tr_koreksi_stok_detail2_temp")->where("tr_koreksi_stok_detail_draft_id", $data->id)
			->where("pallet_id", $data->pallet_id)->get()->row();
	}

	public function insertDataToTableDetail2Temp($data)
	{
		$this->db->set("tr_koreksi_stok_detail2_id", "NewID()", FALSE);
		$this->db->set("tr_koreksi_stok_id", $data->koreksi_id);
		$this->db->set("tr_koreksi_stok_detail_draft_id", $data->id);
		$this->db->set("pallet_id", $data->pallet_id);

		return $this->db->insert("tr_koreksi_stok_detail2_temp");

		// return $queryinsert;
	}

	public function getDataPalletBySkuStockIdTemp($id, $gudang_id, $ed, $koreksi_draft_id)
	{
		$data =  $this->db->query("SELECT
                        tksdp.tr_koreksi_stok_detail_draft_id as id_temp,
                        tksdd.sku_id,
                        pallet.pallet_id,
                        pallet.pallet_kode as no_pallet,
                        pallet.sku_stock_id,
                        FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
                        rl.rak_lajur_nama as lokasi_rak,
                        rld.rak_lajur_detail_nama as lokasi_bin,
                        pallet.sku_stock_qty as qty,
                        SUM(tksdp.sku_qty_aktual_koreksi) AS qty_ambil,
                        tksdp.is_scan AS scan,
                        pallet.pallet_is_lock as lock
                      FROM tr_koreksi_stok_draft tksd
                      LEFT JOIN tr_koreksi_stok_detail_draft tksdd
                        ON tksd.tr_koreksi_stok_draft_id = tksdd.tr_koreksi_stok_draft_id
                      LEFT JOIN tr_koreksi_stok_detail2_temp tksdp
                        ON tksdp.tr_koreksi_stok_detail_draft_id = tksdd.tr_koreksi_stok_detail_draft_id
                        and tksdd.tr_koreksi_stok_detail_draft_id = tksdp.tr_koreksi_stok_detail_draft_id
                      LEFT JOIN (SELECT
                        pallet.pallet_id,
                        pallet.pallet_kode,
                        pallet.pallet_is_lock,
                        pallet_detail.sku_id,
                        pallet_detail.sku_stock_id,
                        pallet_detail.sku_stock_expired_date,
                        ISNULL(pallet_detail.sku_stock_qty, 0) - ISNULL(pallet_detail.sku_stock_ambil, 0) + ISNULL(pallet_detail.sku_stock_in, 0) - ISNULL(pallet_detail.sku_stock_out, 0) + ISNULL(pallet_detail.sku_stock_terima, 0) AS sku_stock_qty
                      FROM pallet
                      LEFT JOIN pallet_detail
                        ON pallet.pallet_id = pallet_detail.pallet_id) pallet
                        ON pallet.pallet_id = tksdp.pallet_id
                        AND pallet.sku_stock_id = tksdd.sku_stock_id
                      left join rak_lajur_detail_pallet rldp on pallet.pallet_id = rldp.pallet_id
                      left join rak_lajur_detail rld on rldp.rak_lajur_detail_id = rld.rak_lajur_detail_id
                      left join rak_lajur rl on rld.rak_lajur_id = rl.rak_lajur_id
                      WHERE tksdp.tr_koreksi_stok_id = '$koreksi_draft_id'
                        and tksd.depo_detail_id_asal = '$gudang_id'
                        and pallet.sku_stock_id = '$id'
                        and FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') = '$ed'
                      GROUP BY tksdp.tr_koreksi_stok_detail_draft_id,
                        tksdd.sku_stock_id,
                        tksdd.sku_id,
                        pallet.pallet_id,
                        pallet.pallet_kode,
                        pallet.sku_stock_id,
                        FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy'),
                        rl.rak_lajur_nama,
                        rld.rak_lajur_detail_nama,
                        tksdp.is_scan,
                        pallet.pallet_is_lock,
						pallet.sku_stock_qty");
		return $data->result();
	}

	public function UpdateQtyAmbilInDetail2Temp($id, $pallet_id, $qty)
	{
		$this->db->set("sku_qty_aktual_koreksi", $qty);
		$this->db->where("tr_koreksi_stok_detail_draft_id", $id);
		$this->db->where("pallet_id", $pallet_id);
		return $this->db->update("tr_koreksi_stok_detail2_temp");
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function check_kode_pallet_by_no_pallet($id, $koreksi_draft_id, $kode_pallet)
	{
		return $this->db->select("tksdt.tr_koreksi_stok_detail2_id as id, tksdt.is_scan as status, tksdt.attachment as file")
			->from("tr_koreksi_stok_detail_draft tksdd")
			->join("tr_koreksi_stok_detail2_temp tksdt", "tksdd.tr_koreksi_stok_detail_draft_id = tksdt.tr_koreksi_stok_detail_draft_id", "left")
			->join("pallet p", "tksdt.pallet_id = p.pallet_id", "left")
			->where("tksdt.tr_koreksi_stok_id", $koreksi_draft_id)
			->where("tksdd.sku_stock_id", $id)
			->where("p.pallet_kode", $kode_pallet)->get()->row();
	}

	public function update_status_tmpdd($data, $file)
	{
		$file_ = $file == null ? null : $file;

		$this->db->set("is_scan", 1);
		$this->db->set("attachment", $file_);
		$this->db->where("tr_koreksi_stok_detail2_id", $data->id);
		$this->db->update("tr_koreksi_stok_detail2_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function check_exist_in_tr_koreksi_draft($id)
	{
		return $this->db->select("tr_koreksi_stok_draft_id")
			->from("tr_koreksi_stok")
			->where("tr_koreksi_stok_draft_id", $id)
			->get()->row();
	}

	public function get_data_tr_koreksi_stok($id)
	{
		return $this->db->select("tr_koreksi_stok_id")
			->from("tr_koreksi_stok")
			->where("tr_koreksi_stok_draft_id", $id)
			->get()->row();
	}

	public function delete_data_tr_koreksi_dan_detail($koreksi_stok_draft, $kp_id)
	{
		$this->db->where('tr_koreksi_stok_draft_id', $koreksi_stok_draft);
		$this->db->delete('tr_koreksi_stok');

		$this->db->where('tr_koreksi_stok_id', $kp_id);
		$this->db->delete('tr_koreksi_stok_detail');

		$this->db->where('tr_koreksi_stok_id', $kp_id);
		return $this->db->delete('tr_koreksi_stok_detail2');
	}

	public function save_data_to_tr_koreksi_stok($kp_id, $generate_kode, $tgl, $koreksi_draft_id, $principle_id, $tipe_id, $keterangan, $gudang_id, $checker_id, $tipe_dokumen, $no_referensi_dokumen, $file)
	{
		$tgl_ = $tgl . " " . date('H:i:s');
		$ket = $keterangan == "" ? null : $keterangan;
		$tipe_dokumen = $tipe_dokumen == "" ? null : $tipe_dokumen;
		$no_referensi_dokumen = $no_referensi_dokumen == "" ? null : $no_referensi_dokumen;
		$file = $file == null ? null : $file;
		$this->db->set("tr_koreksi_stok_id", $kp_id);
		$this->db->set("principle_id", $principle_id);
		$this->db->set("tr_koreksi_stok_kode", $generate_kode);
		$this->db->set("tr_koreksi_stok_tanggal", $tgl);
		$this->db->set("tipe_mutasi_id", $tipe_id);
		$this->db->set("tr_koreksi_stok_keterangan", $ket);
		$this->db->set("tr_koreksi_stok_status", "In Progress");
		$this->db->set("depo_id_asal", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id_asal", $gudang_id);
		$this->db->set("tr_koreksi_stok_tgl_create", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("tr_koreksi_stok_draft_id", $koreksi_draft_id);
		$this->db->set("tr_koreksi_stok_nama_checker", $checker_id);
		$this->db->set("tr_koreksi_stok_pallet_tgl_update", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_pallet_who_update", $this->session->userdata('pengguna_username'));
		$this->db->set("tipe_dokumen", $tipe_dokumen);
		$this->db->set("no_referensi_dokumen", $no_referensi_dokumen);
		$this->db->set("attachment", $file);

		$queryinsert = $this->db->insert("tr_koreksi_stok");

		$this->db->set("tr_koreksi_stok_draft_tgl_update", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_draft_who_update", $this->session->userdata('pengguna_username'));
		$this->db->where("tr_koreksi_stok_draft_id", $koreksi_draft_id);
		$this->db->update("tr_koreksi_stok_draft");

		return $queryinsert;
	}

	public function get_data_tmpd_by_koreksi_draft_id($id)
	{
		return $this->db->select("*")
			->from("tr_koreksi_stok_detail_draft tksdd")
			->where("tksdd.tr_koreksi_stok_draft_id", $id)
			->get()->result();
	}

	public function save_data_to_tr_koreksi_stok_detail($kpd_id, $kp_id, $data)
	{
		$this->db->set("tr_koreksi_stok_detail_id", $kpd_id);
		$this->db->set("tr_koreksi_stok_id", $kp_id);
		$this->db->set("sku_id", $data->sku_id);
		$this->db->set("sku_stock_id", $data->sku_stock_id);
		$this->db->set("sku_qty_plan_koreksi", $data->sku_qty_plan_koreksi);

		$queryinsert = $this->db->insert("tr_koreksi_stok_detail");
		return $queryinsert;
	}

	public function get_data_detail2_tmpd($id, $kp_id)
	{
		return $this->db->select("*")
			->from("tr_koreksi_stok_detail2_temp tksdd2")
			->where("tksdd2.tr_koreksi_stok_detail_draft_id", $id)
			->where("tksdd2.tr_koreksi_stok_id", $kp_id)
			->where("tksdd2.is_scan", 1)
			->get()->result();
	}

	public function save_data_to_tr_koreksi_stok_detail2($kpd_id, $kp_id, $data)
	{
		$this->db->set("tr_koreksi_stok_detail2_id", "NewID()", false);
		$this->db->set("tr_koreksi_stok_detail_id", $kpd_id);
		$this->db->set("tr_koreksi_stok_id", $kp_id);
		$this->db->set("pallet_id", $data->pallet_id);
		$this->db->set("sku_qty_aktual_koreksi", $data->sku_qty_aktual_koreksi);
		$this->db->set("is_scan", $data->is_scan);
		$this->db->set("attachment", $data->attachment);

		$queryinsert = $this->db->insert("tr_koreksi_stok_detail2");
		return $queryinsert;
	}

	public function update_data_tr_koreksi($id)
	{
		//update tr_mutasi_pallet
		$this->db->set("tr_koreksi_stok_status", "Completed");
		$this->db->set("tr_koreksi_stok_pallet_tgl_update", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_pallet_who_update", $this->session->userdata('pengguna_username'));
		$this->db->where("tr_koreksi_stok_draft_id", $id);
		$respone = $this->db->update("tr_koreksi_stok");
		if ($respone) {
			$this->db->set("tr_koreksi_stok_draft_status", "Completed");
			$this->db->where("tr_koreksi_stok_draft_id", $id);
			$this->db->update("tr_koreksi_stok_draft");

			$result = true;
		} else {
			$result = false;
		}

		return $result;
	}

	public function update_qty_aktual_in_tr_koreksi_detail($id, $value)
	{
		$this->db->set("sku_qty_aktual_koreksi", $value['qty_aktual']);
		$this->db->where("tr_koreksi_stok_id", $id);
		$this->db->where("sku_stock_id", $value['sku_stock_id']);
		return $this->db->update("tr_koreksi_stok_detail");
	}

	public function check_exist_sku_stock_by_params($value)
	{

		return $this->db->select("*")
			->from("sku_stock")
			->where("depo_id", $value['depo_id'])
			->where("depo_detail_id", $value['depo_detail_id'])
			->where("sku_id", $value['sku_id'])
			->where("FORMAT(sku_stock_expired_date,'yyyy-MM-dd')", $value['ed'])
			->get();
	}

	public function delete_data_in_detail_temp($id)
	{
		$this->db->where('tr_koreksi_stok_id', $id);
		$this->db->delete('tr_koreksi_stok_detail_temp');

		$this->db->where('tr_koreksi_stok_id', $id);
		return $this->db->delete('tr_koreksi_stok_detail2_temp');
	}

	public function get_data_tr_koreksi_stok_id($id)
	{
		return $this->db->select("tr_koreksi_stok_id as id")
			->from("tr_koreksi_stok")
			->where("tr_koreksi_stok_draft_id", $id)
			->get()->row();
	}

	public function cancel_pallet($id, $pallet_id)
	{

		$get_img = $this->db->select("attachment")->from("tr_koreksi_stok_detail2_temp")->where("tr_koreksi_stok_detail_draft_id", $id)->where("pallet_id", $pallet_id)->get()->row();

		if ($get_img->attachment != null) {
			$temp_file = 'assets/images/uploads/Bukti-Cek-Pallet/' . $get_img->attachment;
			unlink($temp_file);
		}

		$this->db->set("sku_qty_aktual_koreksi", NULL);
		$this->db->set("is_scan", NULL);
		$this->db->set("attachment", NULL);
		$this->db->where("tr_koreksi_stok_detail_draft_id", $id);
		$this->db->where("pallet_id", $pallet_id);
		return $this->db->update("tr_koreksi_stok_detail2_temp");
	}

	public function update_data_in_koreksi_stok($koreksi_id, $keterangan)
	{
		$ket = $keterangan == null ? null : $keterangan;

		$this->db->set("tr_koreksi_stok_keterangan", $ket);
		$this->db->set("tr_koreksi_stok_tgl_update", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_who_update", $this->session->userdata('pengguna_username'));
		$this->db->where("tr_koreksi_stok_id", $koreksi_id);
		return $this->db->update("tr_koreksi_stok");
	}

	public function delete_data_tr_koreksi_dan_detail_edit($id)
	{

		$this->db->where('tr_koreksi_stok_detail_id', $id);
		$this->db->delete('tr_koreksi_stok_detail');

		$this->db->where('tr_koreksi_stok_detail_id', $id);
		return $this->db->delete('tr_koreksi_stok_detail2');
	}

	public function get_data_tmpd_by_koreksi_id_edit($id)
	{
		return $this->db->select("*")
			->from("tr_koreksi_stok_detail tksdd")
			->where("tksdd.tr_koreksi_stok_id", $id)
			->get()->result();
	}

	public function get_data_detail2_edit($id, $kp_id)
	{
		return $this->db->select("*")
			->from("tr_koreksi_stok_detail2 tksdd2")
			->where("tksdd2.tr_koreksi_stok_detail_id", $id)
			->where("tksdd2.tr_koreksi_stok_id", $kp_id)
			->where("tksdd2.is_scan", 1)
			->get()->result();
	}

	public function insert_data_to_sku_stock($data, $sku_awal)
	{
		$client_wms_id = $this->db->select("client_wms_id")->from("sku")->where("sku_id", $data->sku_id)->get()->row(0)->client_wms_id;

		$this->db->set("sku_stock_id", "NEWID()", FALSE);
		$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("depo_id", $data->depo_id);
		$this->db->set("depo_detail_id", $data->depo_detail_id);
		$this->db->set("sku_induk_id", $data->sku_induk_id);
		$this->db->set("sku_id", $data->sku_id);
		$this->db->set("sku_stock_expired_date", $data->ed);
		$this->db->set("sku_stock_awal", 0);
		$this->db->set("sku_stock_masuk", $data->qty_aktual);
		$this->db->set("sku_stock_alokasi", 0);
		$this->db->set("sku_stock_saldo_alokasi", 0);
		$this->db->set("sku_stock_keluar", 0);
		$this->db->set("sku_stock_akhir", 0);
		$this->db->set("sku_stock_is_jual", 1);
		$this->db->set("sku_stock_is_aktif", 1);
		$this->db->set("sku_stock_is_deleted", 0);

		$queryinsert = $this->db->insert("sku_stock");

		return $queryinsert;
	}

	public function update_data_to_sku_stock($data, $sku_awal)
	{
		// PROCEDURE MODE MASUK
		$tmpSkuStockID = $data['sku_stock_id'];
		$qty = $data['qty_aktual'];
		return $this->db->query("exec insertupdate_sku_stock 'masuk', '$tmpSkuStockID', NULL, $qty");;
	}

	public function update_data_to_detail_pallet($data, $get_pallet_detail)
	{

		// $qty_pallet = $get_pallet_detail->sku_stock_in + $data['qty_aktual'];

		// $this->db->set("sku_stock_in", $qty_pallet);
		// $this->db->where("sku_stock_id", $data['sku_stock_id']);
		// return $this->db->update("pallet_detail");

		return $this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_in', '$get_pallet_detail->pallet_id', '$get_pallet_detail->sku_stock_id', '" . $data['qty_aktual'] . "'");
	}

	public function update_data_to_skuStock_and_pallet_detail($data, $get_pallet_detail)
	{
		// PROCEDURE MODE KELUAR
		$tmpSkuStockID = $data['sku_stock_id'];
		$get_sku_stock = $this->db->select("sku_stock_masuk, sku_stock_keluar")->from("sku_stock")->where("sku_stock_id", $data['sku_stock_id'])->get()->row();
		$get_sku_pallet_detail = $this->db->select("sku_stock_in, ISNULL(sku_stock_out, 0) AS sku_stock_out")->from("pallet_detail")->where("pallet_detail_id", $get_pallet_detail->pallet_detail_id)->get()->row();
		$qty_ = $data['qty_aktual'];
		$this->db->query("exec insertupdate_sku_stock 'keluar', '$tmpSkuStockID', NULL, $qty_");

		// $qty_pallet_ = $get_pallet_detail->sku_stock_qty - $data['qty_aktual'];

		// $qty_out = $get_sku_pallet_detail->sku_stock_out + $data['qty_aktual'];
		// // $this->db->set("sku_stock_qty", $qty_pallet_);
		// $this->db->set("sku_stock_out", $qty_out);
		// $this->db->where("sku_stock_id", $data['sku_stock_id']);
		// $this->db->update("pallet_detail");

		$this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_out', '$get_pallet_detail->pallet_id', '$get_pallet_detail->sku_stock_id', '" . $data['qty_aktual'] . "'");

		return;
	}

	public function getDataKoreksiHeaderView($id)
	{
		return $this->db->select("tks.tr_koreksi_stok_id as id,
                              tks.tr_koreksi_stok_draft_id as draft_id,
                              tks.principle_id as principle_id,
                              p.principle_kode as principle,
                              tks.tipe_mutasi_id as tipe_id,
                              tks.tr_koreksi_stok_keterangan as keterangan,
                              tm.tipe_mutasi_nama as tipe,
                              tks.tr_koreksi_stok_status as status,
                              FORMAT(tks.tr_koreksi_stok_tanggal, 'yyyy-MM-dd') as tgl,
                              tks.tr_koreksi_stok_kode as kode,
                              tksd.tr_koreksi_stok_draft_kode as kode_draft,
                              tks.depo_detail_id_asal as gudang_id,
                              tks.tipe_dokumen,
                              tks.no_referensi_dokumen,
                              tks.attachment,
                              dd.depo_detail_nama as gudang, 
                              k.karyawan_id as checker_id,
                              k.karyawan_nama as checker")
			->from("tr_koreksi_stok tks")
			->join("tr_koreksi_stok_draft tksd", "tks.tr_koreksi_stok_draft_id = tksd.tr_koreksi_stok_draft_id", "left")
			->join("principle p", "tks.principle_id = p.principle_id", "left")
			->join("depo_detail dd", "tks.depo_detail_id_asal = dd.depo_detail_id", "left")
			->join("tipe_mutasi tm", "tks.tipe_mutasi_id = tm.tipe_mutasi_id", "left")
			->join("karyawan k", "tks.tr_koreksi_stok_nama_checker = k.karyawan_nama", "left")
			->where("tks.tr_koreksi_stok_id", $id)->get()->row();
	}

	public function getDataKoreksiDraftDetailView($id)
	{
		return $this->db->select("tksd.sku_stock_id as id,
                              tksd.sku_id as sku_id,
                              s.sku_kode as sku_kode,
                              s.sku_nama_produk as sku_nama,
                              pb.principle_brand_nama as brand,
                              s.sku_satuan as sku_satuan,
                              s.sku_kemasan as sku_kemasan,
                              format(ss.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
                              tksd.sku_qty_plan_koreksi as qty_plan,
                              SUM(tksd2.sku_qty_aktual_koreksi) as qty_aktual")
			->from("tr_koreksi_stok_detail tksd")
			->join("tr_koreksi_stok_detail2 tksd2", "tksd.tr_koreksi_stok_detail_id = tksd2.tr_koreksi_stok_detail_id", "left")
			->join("sku s", "tksd.sku_id = s.sku_id", "left")
			->join("sku_stock ss", "tksd.sku_stock_id = ss.sku_stock_id", "left")
			->join("principle_brand pb", "s.principle_brand_id = pb.principle_brand_id", "left")
			->where("tksd.tr_koreksi_stok_id", $id)
			->group_by("tksd.sku_stock_id,tksd.sku_id,s.sku_kode,s.sku_nama_produk,pb.principle_brand_nama,s.sku_satuan,s.sku_kemasan,format(ss.sku_stock_expired_date, 'dd-MM-yyyy'),tksd.sku_qty_plan_koreksi")
			->get()->result();
	}

	public function getDataPalletBySkuStockIdView($id, $gudang_id, $ed, $koreksi_stok_id)
	{
		$data =  $this->db->query("SELECT
                        tksdp.tr_koreksi_stok_detail_id as id_temp,
                        tksdd.sku_id,
                        pallet.pallet_id,
                        pallet.pallet_kode as no_pallet,
                        pallet.sku_stock_id,
                        FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
                        rl.rak_lajur_nama as lokasi_rak,
                        rld.rak_lajur_detail_nama as lokasi_bin,
                        pallet.sku_stock_qty as qty,
                        SUM(tksdp.sku_qty_aktual_koreksi) AS qty_ambil,
                        tksdp.is_scan AS scan
                      FROM tr_koreksi_stok tksd
                      LEFT JOIN tr_koreksi_stok_detail tksdd
                        ON tksd.tr_koreksi_stok_id = tksdd.tr_koreksi_stok_id
                      LEFT JOIN tr_koreksi_stok_detail2 tksdp
                        ON tksdp.tr_koreksi_stok_detail_id = tksdd.tr_koreksi_stok_detail_id
                        and tksdd.tr_koreksi_stok_detail_id = tksdp.tr_koreksi_stok_detail_id
                      LEFT JOIN (SELECT
                        pallet.pallet_id,
                        pallet.pallet_kode,
                        pallet_detail.sku_id,
                        pallet_detail.sku_stock_id,
                        pallet_detail.sku_stock_expired_date,
                        ISNULL(pallet_detail.sku_stock_qty, 0) - ISNULL(pallet_detail.sku_stock_ambil, 0) + ISNULL(pallet_detail.sku_stock_in, 0) - ISNULL(pallet_detail.sku_stock_out, 0) + ISNULL(pallet_detail.sku_stock_terima, 0) AS sku_stock_qty
                      FROM pallet
                      LEFT JOIN pallet_detail
                        ON pallet.pallet_id = pallet_detail.pallet_id) pallet
                        ON pallet.pallet_id = tksdp.pallet_id
                        AND pallet.sku_stock_id = tksdd.sku_stock_id
                      left join rak_lajur_detail_pallet rldp on pallet.pallet_id = rldp.pallet_id
                      left join rak_lajur_detail rld on rldp.rak_lajur_detail_id = rld.rak_lajur_detail_id
                      left join rak_lajur rl on rld.rak_lajur_id = rl.rak_lajur_id
                      WHERE tksdp.tr_koreksi_stok_id = '$koreksi_stok_id'
                        and tksd.depo_detail_id_asal = '$gudang_id'
                        and pallet.sku_stock_id = '$id'
                        and FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') = '$ed'
                      GROUP BY tksdp.tr_koreksi_stok_detail_id,
                        tksdd.sku_stock_id,
                        tksdd.sku_id,
                        pallet.pallet_id,
                        pallet.pallet_kode,
                        pallet.sku_stock_id,
                        FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy'),
                        rl.rak_lajur_nama,
                        rld.rak_lajur_detail_nama,
                        tksdp.is_scan,
						pallet.sku_stock_qty");
		return $data->result();
	}

	public function checkPalletIsLockOrNot($kode)
	{
		return $this->db->select("pallet_is_lock as lock")->from("pallet")->where("pallet_kode", $kode)->get()->row();
	}

	public function getKodeAutoComplete($value)
	{
		return $this->db->select("CONCAT(REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 2)), '/', REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 3))) as kode")
			->from("pallet")
			->where("depo_id", $this->session->userdata('depo_id'))
			->like("pallet_kode", $value)->get()->result();
	}
}
