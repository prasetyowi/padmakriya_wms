<?php

date_default_timezone_set('Asia/Jakarta');

class M_PengambilanBarangMutasiAntarUnit extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		$this->load->model(['M_AutoGen', 'M_Vrbl', 'M_Function']);

		date_default_timezone_set('Asia/Jakarta');
	}




	public function get_data_koreksi_stok_by_filter($filter_no_koreksi, $tgl1, $tgl2, $filterEkspedisiHomePage, $filterPengemudiHomePage, $filterKendaraanHomePage, $filter_koreksi_status)
	{
		$this->db->select("tmd.tr_mutasi_depo_id,
											tmd.tr_mutasi_depo_kode,
											ekspedisi.ekspedisi_nama,
											karyawan.karyawan_nama,
											kendaraan.kendaraan_model,
											kendaraan.kendaraan_nopol,
											tmd.tr_mutasi_depo_status,
											COUNT(tmdd.tr_mutasi_depo_id) as countSimpan")
			->from('tr_mutasi_depo tmd')
			->join('tr_mutasi_depo_detail_3 tmdd', 'tmd.tr_mutasi_depo_id = tmdd.tr_mutasi_depo_id', 'left')
			->join('ekspedisi', 'tmd.ekspedisi_id = ekspedisi.ekspedisi_id', 'left')
			->join('karyawan', 'tmd.karyawan_id = karyawan.karyawan_id', 'left')
			->join('kendaraan', 'tmd.kendaraan_id = kendaraan.kendaraan_id', 'left')
			->where('tmd.depo_id', $this->session->userdata('depo_id'))
			->where("FORMAT(tmd.tr_mutasi_depo_tgl_create, 'yyyy-MM-dd') >=", $tgl1)
			->where("FORMAT(tmd.tr_mutasi_depo_tgl_create, 'yyyy-MM-dd') <=",  $tgl2);
		if ($filter_no_koreksi !== 'all') $this->db->where('tmd.tr_mutasi_depo_kode', $filter_no_koreksi);
		if ($filterEkspedisiHomePage !== 'all') $this->db->where('tmd.ekspedisi_id', $filterEkspedisiHomePage);
		if ($filterPengemudiHomePage !== 'all') $this->db->where('tmd.karyawan_id', $filterPengemudiHomePage);
		if ($filterKendaraanHomePage !== 'all') $this->db->where('tmd.kendaraan_id', $filterKendaraanHomePage);
		if ($filter_koreksi_status !== 'all') $this->db->where('tmd.tr_mutasi_depo_status', $filter_koreksi_status);
		return $this->db->group_by("tmd.tr_mutasi_depo_id,
																tmd.tr_mutasi_depo_kode,
																ekspedisi.ekspedisi_nama,
																karyawan.karyawan_nama,
																kendaraan.kendaraan_model,
																kendaraan.kendaraan_nopol,
																tmd.tr_mutasi_depo_status")
			->having("COUNT(tmdd.tr_mutasi_depo_id) >", 0)
			->order_by('tmd.tr_mutasi_depo_kode', 'ASC')->get()->result();
	}

	public function get_stock_corrections()
	{
		return $this->db->select("tr_mutasi_depo_id as id, tr_mutasi_depo_kode as kode")
			->from("tr_mutasi_depo")->where('depo_id', $this->session->userdata('depo_id'))->order_by("tr_mutasi_depo_kode", "ASC")->get()->result();
	}

	public function get_warehouses()
	{
		return $this->db->select("depo_detail_id as id, depo_detail_nama as nama")
			->from("depo_detail")->where("depo_id", $this->session->userdata("depo_id"))->order_by("depo_detail_nama", "ASC")->get()->result();
	}

	function getDataEkspedisis(): array
	{
		return $this->db->get_where('ekspedisi', [
			'ekspedisi_is_aktif' => 1,
			'ekspedisi_is_deleted' => 0
		])->result();
	}

	function getDataDrivers(): array
	{
		return $this->db->get_where('karyawan', [
			'karyawan_is_aktif' => 1,
			'karyawan_is_deleted' => 0,
			'karyawan_level_id' => '339D8AC2-C6CE-4B47-9BFC-E372592AF521',
		])->result();
	}

	function getDataVehicles(): array
	{
		return $this->db->get_where('kendaraan', [
			'kendaraan_is_aktif' => 1,
			'kendaraan_is_deleted' => 0,
		])->result();
	}

	public function getNoKoreksiDraft()
	{
		return $this->db->select("tmd.tr_mutasi_depo_id as id, tmd.tr_mutasi_depo_kode as kode, COUNT(tmdd.tr_mutasi_depo_id) as countSimpan")
			->from("tr_mutasi_depo tmd")
			->join('tr_mutasi_depo_detail_3 tmdd', 'tmd.tr_mutasi_depo_id = tmdd.tr_mutasi_depo_id', 'left')
			->where("tmd.depo_id", $this->session->userdata('depo_id'))
			->where("tmd.tr_mutasi_depo_status", "In Progress Picking")
			->group_by("tmd.tr_mutasi_depo_id,
									tmd.tr_mutasi_depo_kode")
			->having("COUNT(tmdd.tr_mutasi_depo_id)", 0)
			->order_by("tmd.tr_mutasi_depo_kode", "ASC")->get()->result();
	}


	public function getDataKoreksiDraftHeader($id)
	{
		return $this->db->query("SELECT DISTINCT
																	a.tr_mutasi_depo_detail_id,
																	a.tr_mutasi_depo_id,
																	b.tr_mutasi_depo_kode,
																	b.tr_mutasi_depo_status,
																	b.tr_mutasi_depo_tgl_update,
																	b.tr_mutasi_depo_keterangan,
																	FORMAT(b.tr_mutasi_depo_tgl_create, 'yyyy-MM-dd') as tgl,
																	c.depo_nama as depoAsal,
																	d.depo_nama as depoTujuan,
																	e.depo_detail_id,
																	e.depo_detail_nama as gudangAsal,
																	f.ekspedisi_kode,
																	f.ekspedisi_nama,
																	g.karyawan_nama,
																	h.kendaraan_nopol,
																	h.kendaraan_model,
																	i.tr_mutasi_depo_detail_3_keterangan
														from tr_mutasi_depo_detail a
														left join tr_mutasi_depo b on a.tr_mutasi_depo_id = b.tr_mutasi_depo_id
														left join depo c on a.depo_id_asal = c.depo_id
														left join depo d on a.depo_id_tujuan = d.depo_id
														left join depo_detail e on a.depo_detail_id_asal = e.depo_detail_id
														left join ekspedisi f on b.ekspedisi_id = f.ekspedisi_id
														left join karyawan g on b.karyawan_id = g.karyawan_id
														left join kendaraan h on b.kendaraan_id = h.kendaraan_id
														left join tr_mutasi_depo_detail_3 i on a.tr_mutasi_depo_id = i.tr_mutasi_depo_id
														where b.tr_mutasi_depo_id = '$id'")->row();
	}

	public function getDataKoreksiDraftDetail($id)
	{
		return $this->db->select("tr_mutasi_depo_detail_2_id as detail_id,
															tr_mutasi_depo_detail_id,
															tr_mutasi_depo_id,
                              sku_stock_id as sku_stock_id,
                              sku_id as sku_id,
                              qty_plan as qty_plan")
			->from("tr_mutasi_depo_detail_2")
			->where("tr_mutasi_depo_id", $id)->get()->result();
	}

	public function getDataKoreksiHeaderEdit($id)
	{
		return $this->db->query("SELECT DISTINCT
																	a.tr_mutasi_depo_detail_id,
																	a.tr_mutasi_depo_id,
																	b.tr_mutasi_depo_kode,
																	b.tr_mutasi_depo_status,
																	b.tr_mutasi_depo_tgl_update,
																	b.tr_mutasi_depo_keterangan,
																	FORMAT(b.tr_mutasi_depo_tgl_create, 'yyyy-MM-dd') as tgl,
																	c.depo_nama as depoAsal,
																	d.depo_nama as depoTujuan,
																	e.depo_detail_id,
																	e.depo_detail_nama as gudangAsal,
																	f.ekspedisi_kode,
																	f.ekspedisi_nama,
																	g.karyawan_nama,
																	h.kendaraan_nopol,
																	h.kendaraan_model,
																	i.tr_mutasi_depo_detail_3_keterangan
															from tr_mutasi_depo_detail a
															left join tr_mutasi_depo b on a.tr_mutasi_depo_id = b.tr_mutasi_depo_id
															left join depo c on a.depo_id_asal = c.depo_id
															left join depo d on a.depo_id_tujuan = d.depo_id
															left join depo_detail e on a.depo_detail_id_asal = e.depo_detail_id
															left join ekspedisi f on b.ekspedisi_id = f.ekspedisi_id
															left join karyawan g on b.karyawan_id = g.karyawan_id
															left join kendaraan h on b.kendaraan_id = h.kendaraan_id
															left join tr_mutasi_depo_detail_3 i on a.tr_mutasi_depo_id = i.tr_mutasi_depo_id
															where b.tr_mutasi_depo_id = '$id'")->row();
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
															tksd.tr_koreksi_stok_detail_draft_id as detail2_id,
                              SUM(tksd2.sku_qty_aktual_koreksi) as qty_aktual")
			->from("tr_koreksi_stok_detail_temp tksd")
			->join("tr_koreksi_stok_detail2_temp tksd2", "tksd.tr_koreksi_stok_detail_draft_id = tksd2.tr_koreksi_stok_detail_draft_id", "left")
			->join("sku s", "tksd.sku_id = s.sku_id", "left")
			->join("sku_stock ss", "tksd.sku_stock_id = ss.sku_stock_id", "left")
			->join("principle_brand pb", "s.principle_brand_id = pb.principle_brand_id", "left")
			->group_by("tksd.sku_stock_id,tksd.sku_id,s.sku_kode,s.sku_nama_produk,pb.principle_brand_nama,s.sku_satuan,s.sku_kemasan,format(ss.sku_stock_expired_date, 'dd-MM-yyyy'), 
									format(ss.sku_stock_expired_date, 'yyyy-MM-dd'), ss.depo_id, ss.depo_detail_id, ss.sku_induk_id, tksd.sku_qty_plan_koreksi, tksd.tr_koreksi_stok_detail_draft_id")
			->where("tksd.tr_koreksi_stok_id", $id)->get()->result();
	}

	public function insertDataToTableDetailTemp($data)
	{
		$this->db->set("tr_koreksi_stok_detail_draft_id", $data->detail_id);
		$this->db->set("tr_koreksi_stok_id", $data->tr_mutasi_depo_id);
		$this->db->set("sku_id", $data->sku_id);
		$this->db->set("sku_stock_id", $data->sku_stock_id);
		$this->db->set("sku_qty_plan_koreksi", $data->qty_plan);

		$queryinsert = $this->db->insert("tr_koreksi_stok_detail_temp");

		return $queryinsert;
	}

	public function getDataPalletBySkuStockId($id, $gudang_id, $ed, $koreksi_draft_id)
	{
		return $this->db->select("tksd.tr_mutasi_depo_detail_2_id as id,
                              tksd.tr_mutasi_depo_id as koreksi_id,
                              p.pallet_id as pallet_id")
			->from("sku_stock ss")
			->join("pallet_detail pd", "ss.sku_stock_id = pd.sku_stock_id", "left")
			->join("pallet p", "pd.pallet_id = p.pallet_id", "left")
			->join("tr_mutasi_depo_detail_2 tksd", "ss.sku_stock_id = tksd.sku_stock_id", "left")
			->where("tksd.tr_mutasi_depo_id", $koreksi_draft_id)
			->where("ss.depo_id", $this->session->userdata('depo_id'))
			->where("ss.depo_detail_id", $gudang_id)
			->where("ss.sku_stock_id", $id)
			->where("FORMAT(pd.sku_stock_expired_date, 'dd-MM-yyyy') = '$ed'")
			->get()->result();
	}

	public function check_exist_data_in_detail2_temp($data)
	{
		return $this->db->select("*")->from("tr_koreksi_stok_detail2_temp")
			->where("tr_koreksi_stok_detail_draft_id", $data->id)
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
																	tksdd2.sku_id,
																	pallet.pallet_id,
																	pallet.pallet_kode as no_pallet,
																	pallet.sku_stock_id,
																	FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
																	rl.rak_lajur_nama as lokasi_rak,
																	rld.rak_lajur_detail_nama as lokasi_bin,
																	SUM(pallet.sku_stock_qty) AS qty,
																	SUM(tksdp.sku_qty_aktual_koreksi) AS qty_ambil,
																	tksdp.is_scan AS scan,
																	pallet.pallet_is_lock as lock
																FROM tr_mutasi_depo tksd
																LEFT JOIN tr_mutasi_depo_detail tksdd
																	ON tksd.tr_mutasi_depo_id = tksdd.tr_mutasi_depo_id
																LEFT JOIN tr_mutasi_depo_detail_2 tksdd2
																	ON tksdd.tr_mutasi_depo_detail_id = tksdd2.tr_mutasi_depo_detail_id
																LEFT JOIN tr_koreksi_stok_detail2_temp tksdp
																	ON tksdp.tr_koreksi_stok_detail_draft_id = tksdd2.tr_mutasi_depo_detail_2_id
																	and tksdd2.tr_mutasi_depo_detail_2_id = tksdp.tr_koreksi_stok_detail_draft_id
																LEFT JOIN (SELECT
																				pallet.pallet_id,
																				pallet.pallet_kode,
																				pallet.pallet_is_lock,
																				pallet_detail.sku_id,
																				pallet_detail.sku_stock_id,
																				pallet_detail.sku_stock_expired_date,
																				ISNULL(pallet_detail.sku_stock_qty, 0) + ISNULL(pallet_detail.sku_stock_in, 0) - ISNULL(pallet_detail.sku_stock_out, 0) - ISNULL(pallet_detail.sku_stock_ambil, 0) as sku_stock_qty
																			FROM pallet
																			LEFT JOIN pallet_detail
																				ON pallet.pallet_id = pallet_detail.pallet_id) pallet
																	ON pallet.pallet_id = tksdp.pallet_id
																	AND pallet.sku_stock_id = tksdd2.sku_stock_id
																left join rak_lajur_detail_pallet rldp on pallet.pallet_id = rldp.pallet_id
																left join rak_lajur_detail rld on rldp.rak_lajur_detail_id = rld.rak_lajur_detail_id
																left join rak_lajur rl on rld.rak_lajur_id = rl.rak_lajur_id
																WHERE tksdp.tr_koreksi_stok_id = '$koreksi_draft_id'
																	and tksdd.depo_detail_id_asal = '$gudang_id'
																	and pallet.sku_stock_id = '$id'
																	and FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') = '$ed'
																GROUP BY tksdp.tr_koreksi_stok_detail_draft_id,
																		tksdd2.sku_stock_id,
																		tksdd2.sku_id,
																		pallet.pallet_id,
																		pallet.pallet_kode,
																		pallet.sku_stock_id,
																		FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy'),
																		rl.rak_lajur_nama,
																		rld.rak_lajur_detail_nama,
																		tksdp.is_scan,
																		pallet.pallet_is_lock");
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

	public function check_kode_pallet_by_no_pallet($id, $detail2Id, $koreksi_draft_id, $kode_pallet)
	{
		return $this->db->select("tksdt.tr_koreksi_stok_detail2_id as id, tksdt.is_scan as status, tksdt.attachment as file")
			->from("tr_mutasi_depo_detail_2 tksdd")
			->join("tr_koreksi_stok_detail2_temp tksdt", "tksdd.tr_mutasi_depo_id = tksdt.tr_koreksi_stok_id", "left")
			->join("pallet p", "tksdt.pallet_id = p.pallet_id", "left")
			->where("tksdt.tr_koreksi_stok_id", $koreksi_draft_id)
			->where("tksdt.tr_koreksi_stok_detail_draft_id", $detail2Id)
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
		return $this->db->select("tr_mutasi_depo_id")
			->from("tr_mutasi_depo_detail_3")
			->where("tr_mutasi_depo_id", $id)
			->get()->row();
	}

	public function get_data_tr_koreksi_stok($id)
	{
		return $this->db->select("tr_koreksi_stok_id")
			->from("tr_koreksi_stok")
			->where("tr_koreksi_stok_draft_id", $id)
			->get()->row();
	}

	public function delete_data_tr_koreksi_dan_detail($koreksi_stok_draft)
	{
		$this->db->where('tr_mutasi_depo_id', $koreksi_stok_draft);
		return $this->db->delete('tr_mutasi_depo_detail_3');
	}

	public function save_data_to_tr_koreksi_stok($koreksi_draft_id, $keterangan)
	{

		$getDataDetail2Temp = $this->db->get_where('tr_koreksi_stok_detail2_temp', ['tr_koreksi_stok_id' => $koreksi_draft_id])->result();
		$getDataDetailId = $this->db->get_where('tr_mutasi_depo_detail', ['tr_mutasi_depo_id' => $koreksi_draft_id])->row()->tr_mutasi_depo_detail_id;

		if ($getDataDetail2Temp) {
			foreach ($getDataDetail2Temp as $key => $value) {
				$newId = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];
				$this->db->insert("tr_mutasi_depo_detail_3", [
					'tr_mutasi_depo_detail_3_id' => $newId,
					'tr_mutasi_depo_detail2_id' => $value->tr_koreksi_stok_detail_draft_id,
					'tr_mutasi_depo_detail_id' => $getDataDetailId,
					'tr_mutasi_depo_id' => $value->tr_koreksi_stok_id,
					'pallet_id' => $value->pallet_id,
					'sku_qty_aktual_ambil' => $value->sku_qty_aktual_koreksi == null ? 0 : $value->sku_qty_aktual_koreksi,
					'is_scan' => $value->is_scan,
					'tr_mutasi_depo_detail_3_keterangan' => $keterangan === '' ? NULL : $keterangan,
				]);
			}
		}

		$this->db->set("tr_mutasi_depo_tgl_update", "GETDATE()", FALSE);
		$this->db->set("tr_mutasi_depo_who_update", $this->session->userdata('pengguna_username'));
		$this->db->where("tr_mutasi_depo_id", $koreksi_draft_id);
		$this->db->update("tr_mutasi_depo");
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
		$this->db->set("tr_mutasi_depo_status", "Completed");
		$this->db->set("tr_mutasi_depo_tgl_update", "GETDATE()", FALSE);
		$this->db->set("tr_mutasi_depo_draft_who_update", $this->session->userdata('pengguna_username'));
		$this->db->where("tr_mutasi_depo_id", $id);
		$respone = $this->db->update("tr_mutasi_depo");

		return $respone;
	}

	public function update_qty_aktual_in_tr_koreksi_detail($id, $value)
	{
		$this->db->update("tr_mutasi_depo_detail_2", [
			'qty_ambil' => $value['qty_aktual']
		], [
			'tr_mutasi_depo_id' => $id,
			'sku_stock_id' => $value['sku_stock_id'],
			'sku_id' => $value['sku_id'],
		]);

		$detail2 = $this->db->get_where('tr_mutasi_depo_detail_2', [
			'tr_mutasi_depo_id' => $id,
			'sku_stock_id' => $value['sku_stock_id'],
			'sku_id' => $value['sku_id'],
		])->row();

		$this->db->update("tr_mutasi_depo_detail_3", [
			'sku_qty_aktual_ambil' => $value['qty_aktual']
		], [
			'tr_mutasi_depo_id' => $id,
			'tr_mutasi_depo_detail2_id' => $detail2->tr_mutasi_depo_detail_2_id,
		]);
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

		$this->db->set("tr_mutasi_depo_tgl_update", "GETDATE()", FALSE);
		$this->db->set("tr_mutasi_depo_who_update", $this->session->userdata('pengguna_username'));
		$this->db->where("tr_mutasi_depo_id", $koreksi_id);
		$this->db->update("tr_mutasi_depo");
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
			->from("tr_koreksi_stok_detail2_temp")
			->where("tr_koreksi_stok_id", $id)
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
		$this->db->set("sku_stock_id", "NEWID()", FALSE);
		$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
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

		$qty_pallet = $get_pallet_detail->sku_stock_qty + $data['qty_aktual'];

		$this->db->set("sku_stock_qty", $qty_pallet);
		$this->db->where("sku_stock_id", $data['sku_stock_id']);
		return $this->db->update("pallet_detail");
	}

	public function update_data_to_skuStock_and_pallet_detail($data, $get_pallet_detail)
	{
		// PROCEDURE MODE KELUAR
		$tmpSkuStockID = $data['sku_stock_id'];
		$get_sku_stock = $this->db->select("sku_stock_masuk, sku_stock_keluar")->from("sku_stock")->where("sku_stock_id", $data['sku_stock_id'])->get()->row();
		$qty_ = $data['qty_aktual'];
		$this->db->query("exec insertupdate_sku_stock 'keluar', '$tmpSkuStockID', NULL, $qty_");

		$qty_pallet_ = $get_pallet_detail->sku_stock_qty - $data['qty_aktual'];

		$this->db->set("sku_stock_qty", $qty_pallet_);
		$this->db->where("sku_stock_id", $data['sku_stock_id']);
		$this->db->update("pallet_detail");

		return;
	}

	public function getDataKoreksiHeaderView($id)
	{
		return $this->db->query("SELECT DISTINCT
																	a.tr_mutasi_depo_detail_id,
																	a.tr_mutasi_depo_id,
																	b.tr_mutasi_depo_kode,
																	b.tr_mutasi_depo_status,
																	b.tr_mutasi_depo_tgl_update,
																	b.tr_mutasi_depo_keterangan,
																	FORMAT(b.tr_mutasi_depo_tgl_create, 'yyyy-MM-dd') as tgl,
																	c.depo_nama as depoAsal,
																	d.depo_nama as depoTujuan,
																	e.depo_detail_id,
																	e.depo_detail_nama as gudangAsal,
																	f.ekspedisi_kode,
																	f.ekspedisi_nama,
																	g.karyawan_nama,
																	h.kendaraan_nopol,
																	h.kendaraan_model,
																	i.tr_mutasi_depo_detail_3_keterangan
															from tr_mutasi_depo_detail a
															left join tr_mutasi_depo b on a.tr_mutasi_depo_id = b.tr_mutasi_depo_id
															left join depo c on a.depo_id_asal = c.depo_id
															left join depo d on a.depo_id_tujuan = d.depo_id
															left join depo_detail e on a.depo_detail_id_asal = e.depo_detail_id
															left join ekspedisi f on b.ekspedisi_id = f.ekspedisi_id
															left join karyawan g on b.karyawan_id = g.karyawan_id
															left join kendaraan h on b.kendaraan_id = h.kendaraan_id
															left join tr_mutasi_depo_detail_3 i on a.tr_mutasi_depo_id = i.tr_mutasi_depo_id
															where b.tr_mutasi_depo_id = '$id'")->row();
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
                              tksd.qty_plan as qty_plan,
                              SUM(tksd2.sku_qty_aktual_ambil) as qty_aktual")
			->from("tr_mutasi_depo_detail_2 tksd")
			->join("tr_mutasi_depo_detail_3 tksd2", "tksd.tr_mutasi_depo_detail_2_id = tksd2.tr_mutasi_depo_detail2_id", "left")
			->join("sku s", "tksd.sku_id = s.sku_id", "left")
			->join("sku_stock ss", "tksd.sku_stock_id = ss.sku_stock_id", "left")
			->join("principle_brand pb", "s.principle_brand_id = pb.principle_brand_id", "left")
			->where("tksd.tr_mutasi_depo_id", $id)
			->group_by("tksd.sku_stock_id,tksd.sku_id,s.sku_kode,s.sku_nama_produk,pb.principle_brand_nama,s.sku_satuan,s.sku_kemasan,format(ss.sku_stock_expired_date, 'dd-MM-yyyy'),tksd.qty_plan")
			->get()->result();
	}

	public function getDataPalletBySkuStockIdView($id, $gudang_id, $ed, $koreksi_stok_id)
	{
		$data =  $this->db->query("SELECT
																	tksdp.tr_mutasi_depo_detail_id as id_temp,
																	tksdd2.sku_id,
																	pallet.pallet_id,
																	pallet.pallet_kode as no_pallet,
																	pallet.sku_stock_id,
																	FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
																	rl.rak_lajur_nama as lokasi_rak,
																	rld.rak_lajur_detail_nama as lokasi_bin,
																	SUM(pallet.sku_stock_qty) AS qty,
																	SUM(tksdp.sku_qty_aktual_ambil) AS qty_ambil,
																	tksdp.is_scan AS scan,
																	pallet.pallet_is_lock as lock
																FROM tr_mutasi_depo tksd
																LEFT JOIN tr_mutasi_depo_detail tksdd
																	ON tksd.tr_mutasi_depo_id = tksdd.tr_mutasi_depo_id
																LEFT JOIN tr_mutasi_depo_detail_2 tksdd2
																	ON tksdd.tr_mutasi_depo_detail_id = tksdd2.tr_mutasi_depo_detail_id
																LEFT JOIN tr_mutasi_depo_detail_3 tksdp
																	ON tksdp.tr_mutasi_depo_detail2_id = tksdd2.tr_mutasi_depo_detail_2_id
																LEFT JOIN (SELECT
																				pallet.pallet_id,
																				pallet.pallet_kode,
																				pallet.pallet_is_lock,
																				pallet_detail.sku_id,
																				pallet_detail.sku_stock_id,
																				pallet_detail.sku_stock_expired_date,
																				ISNULL(pallet_detail.sku_stock_qty, 0) + ISNULL(pallet_detail.sku_stock_in, 0) - ISNULL(pallet_detail.sku_stock_out, 0) - ISNULL(pallet_detail.sku_stock_ambil, 0) as sku_stock_qty
																			FROM pallet
																			LEFT JOIN pallet_detail
																				ON pallet.pallet_id = pallet_detail.pallet_id) pallet
																	ON pallet.pallet_id = tksdp.pallet_id
																	AND pallet.sku_stock_id = tksdd2.sku_stock_id
																left join rak_lajur_detail_pallet rldp on pallet.pallet_id = rldp.pallet_id
																left join rak_lajur_detail rld on rldp.rak_lajur_detail_id = rld.rak_lajur_detail_id
																left join rak_lajur rl on rld.rak_lajur_id = rl.rak_lajur_id
																WHERE tksdp.tr_mutasi_depo_id = '$koreksi_stok_id'
																	and tksdd.depo_detail_id_asal = '$gudang_id'
																	and pallet.sku_stock_id = '$id'
																	and FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') = '$ed'
																GROUP BY tksdp.tr_mutasi_depo_detail_id,
																		tksdd2.sku_stock_id,
																		tksdd2.sku_id,
																		pallet.pallet_id,
																		pallet.pallet_kode,
																		pallet.sku_stock_id,
																		FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy'),
																		rl.rak_lajur_nama,
																		rld.rak_lajur_detail_nama,
																		tksdp.is_scan,
																		pallet.pallet_is_lock");

		// $data =  $this->db->query("SELECT
		//                     tksdp.tr_koreksi_stok_detail_id as id_temp,
		//                     tksdd.sku_id,
		//                     pallet.pallet_id,
		//                     pallet.pallet_kode as no_pallet,
		//                     pallet.sku_stock_id,
		//                     FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
		//                     rl.rak_lajur_nama as lokasi_rak,
		//                     rld.rak_lajur_detail_nama as lokasi_bin,
		//                     SUM(pallet.sku_stock_qty) AS qty,
		//                     SUM(tksdp.sku_qty_aktual_koreksi) AS qty_ambil,
		//                     tksdp.is_scan AS scan
		//                   FROM tr_koreksi_stok tksd
		//                   LEFT JOIN tr_koreksi_stok_detail tksdd
		//                     ON tksd.tr_koreksi_stok_id = tksdd.tr_koreksi_stok_id
		//                   LEFT JOIN tr_koreksi_stok_detail2 tksdp
		//                     ON tksdp.tr_koreksi_stok_detail_id = tksdd.tr_koreksi_stok_detail_id
		//                     and tksdd.tr_koreksi_stok_detail_id = tksdp.tr_koreksi_stok_detail_id
		//                   LEFT JOIN (SELECT
		//                     pallet.pallet_id,
		//                     pallet.pallet_kode,
		//                     pallet_detail.sku_id,
		//                     pallet_detail.sku_stock_id,
		//                     pallet_detail.sku_stock_expired_date,
		//                     ISNULL(pallet_detail.sku_stock_qty, 0) + ISNULL(pallet_detail.sku_stock_in, 0) - ISNULL(pallet_detail.sku_stock_out, 0) - ISNULL(pallet_detail.sku_stock_ambil, 0) as sku_stock_qty
		//                   FROM pallet
		//                   LEFT JOIN pallet_detail
		//                     ON pallet.pallet_id = pallet_detail.pallet_id) pallet
		//                     ON pallet.pallet_id = tksdp.pallet_id
		//                     AND pallet.sku_stock_id = tksdd.sku_stock_id
		//                   left join rak_lajur_detail_pallet rldp on pallet.pallet_id = rldp.pallet_id
		//                   left join rak_lajur_detail rld on rldp.rak_lajur_detail_id = rld.rak_lajur_detail_id
		//                   left join rak_lajur rl on rld.rak_lajur_id = rl.rak_lajur_id
		//                   WHERE tksdp.tr_koreksi_stok_id = '$koreksi_stok_id'
		//                     and tksd.depo_detail_id_asal = '$gudang_id'
		//                     and pallet.sku_stock_id = '$id'
		//                     and FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') = '$ed'
		//                   GROUP BY tksdp.tr_koreksi_stok_detail_id,
		//                     tksdd.sku_stock_id,
		//                     tksdd.sku_id,
		//                     pallet.pallet_id,
		//                     pallet.pallet_kode,
		//                     pallet.sku_stock_id,
		//                     FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy'),
		//                     rl.rak_lajur_nama,
		//                     rld.rak_lajur_detail_nama,
		//                     tksdp.is_scan");
		return $data->result();
	}

	public function checkPalletIsLockOrNot($kode)
	{
		return $this->db->select("pallet_is_lock as lock")->from("pallet")->where("pallet_kode", $kode)->get()->row();
	}

	public function getKodeAutoComplete($value)
	{
		return $this->db->select("SUBSTRING(pallet_kode, CHARINDEX('PAL/', pallet_kode), LEN(pallet_kode)) as kode")
			->from("pallet")
			->where("depo_id", $this->session->userdata('depo_id'))
			->like("pallet_kode", $value)->get()->result();
	}
}
