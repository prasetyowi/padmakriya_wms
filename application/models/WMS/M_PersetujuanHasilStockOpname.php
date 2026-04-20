<?php

date_default_timezone_set('Asia/Jakarta');

class M_PersetujuanHasilStockOpname extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->load->model(['M_AutoGen', 'M_Vrbl', 'M_Function']);
	}

	public function getKodeDokumenOpname()
	{
		return $this->db->select("tr_opname_plan_id as id, tr_opname_plan_kode as kode")
			->from("tr_opname_plan")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where_in("tr_opname_plan_status", ["Waiting For Final Approve", "In Progress Revision"])
			->where("tr_opname_plan_is_hasil_compare", NULL)
			->order_by("tr_opname_plan_kode", "ASC")
			->get()->result();
	}

	public function getDataDokumenOpnameKode($dataPost)
	{

		$whereData = $dataPost->type == 'adjustment' ? ["Waiting For Final Approve", "In Progress Revision"] : ["Waiting For Final Approve", "In Progress Revision", "Completed"];

		return $this->db->select("tr_opname_plan_id as id, tr_opname_plan_kode as kode, tr_opname_plan_status as status")
			->from("tr_opname_plan")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where_in("tr_opname_plan_status", $whereData)
			->where("tr_opname_plan_is_hasil_compare", NULL)
			->order_by("tr_opname_plan_kode", "ASC")
			->get()->result();
	}

	public function getKodeDokumenOpnameCompare($dataPost)
	{
		return $this->db->select("tr_opname_plan_id as id, tr_opname_plan_kode as kode")
			->from("tr_opname_plan")
			->where("depo_detail_id", $dataPost->value)
			->where("tr_opname_plan_status", "Waiting For Final Approve")
			->where("tr_opname_plan_is_hasil_compare", NULL)
			->order_by("tr_opname_plan_kode", "ASC")
			->get()->result();
	}

	public function getDepoDetail()
	{
		return $this->db->select("depo_detail_id as id, depo_detail_nama as nama")
			->from("depo_detail")
			->where("depo_id", $this->session->userdata('depo_id'))
			->order_by("depo_detail_nama", "ASC")
			->get()->result();
	}

	public function Get_LastUpdateOpname($id)
	{
		return $this->db->query("select tr_opname_plan_tgl_update as tglUpd from tr_opname_plan where tr_opname_plan_id = '$id'")->row_array();
	}

	public function getDataOpnameByKode($dataPost)
	{
		//get data opname for header
		$header = $this->db->select("topp.tr_opname_plan_id as id,
																 topp.tr_opname_plan_kode as kode,
																 topp.tr_opname_plan_keterangan as keterangan,
                                 k.karyawan_nama as penanggung_jawab,
                                 FORMAT(topp.tr_opname_plan_tgl_create, 'yyyy-MM-dd') as tanggal,
                                 cw.client_wms_nama as perusahaan,
                                 p.principle_nama as principle,
                                 topp.tipe_stok as jenis_stock,
                                 tp.tipe_opname_nama as tipe_stock_opname,
                                 depo.depo_nama as unit_cabang,
                                 dd.depo_detail_nama as area_opname,
                                 topp.tr_opname_plan_status as status
                                 ")
			->from("tr_opname_plan topp")
			->join("client_wms cw", "topp.client_wms_id = cw.client_wms_id", "left")
			->join("principle p", "topp.principle_id = p.principle_id", "left")
			->join("tipe_opname tp", "topp.tipe_opname_id = tp.tipe_opname_id", "left")
			->join("karyawan k", "topp.karyawan_id_penanggungjawab = k.karyawan_id", "left")
			->join("depo", "topp.depo_id = depo.depo_id", "left")
			->join("depo_detail dd", "topp.depo_detail_id = dd.depo_detail_id", "left")
			->where("topp.tr_opname_plan_id", $dataPost->value)
			->get()->row();

		if ($dataPost->type == "selisih") $selisih = "and td3.sku_qty_sistem - td3.sku_actual_qty_opname != 0";

		if ($dataPost->type == null) $selisih = "";

		$dataDetail = $this->db->query("SELECT
                                    td2.tr_opname_plan_detail2_id as opname_detail2_id,
                                    rp.pallet_id,
	                                  p.pallet_kode,
                                    td3.tr_opname_plan_detail3_id as opname_detail3_id,
                                    rd.rak_lajur_detail_nama as kode_rak,
                                    (select dd.depo_detail_nama from tr_opname_plan topp
                                      left join depo_detail dd on topp.depo_detail_id = dd.depo_detail_id
                                      where topp.tr_opname_plan_id = '$dataPost->value') as area,
                                    sku.sku_kode as kode_sku,
                                    sku.sku_nama_produk as nama_sku,
                                    td3.sku_expired_date as exp_date,
                                    sku.sku_kemasan as kemasan,
                                    sku.sku_satuan as satuan,
                                    td3.sku_qty_sistem as qty_sistem,
                                    td3.sku_actual_qty_opname as qty_aktual,
                                    td3.sku_qty_sistem - td3.sku_actual_qty_opname as deviasi,
																		isnull(td3.sku_batch_no, '-') as sku_batch_no,
                                    td2.tr_opname_plan_detail2_status as status
                                  from tr_opname_plan_detail2  as td2
                                  left join rak_lajur_detail_pallet as rp on rp.pallet_id = td2.pallet_id 
                                  left join tr_opname_plan_detail as td on td.tr_opname_plan_detail_id =td2.tr_opname_plan_detail_id
                                  left join tr_opname_plan_detail3 as td3 on td3.tr_opname_plan_detail2_id = td2.tr_opname_plan_detail2_id
                                  left join rak_lajur_detail as rd on rd.rak_lajur_detail_id = td.rak_lajur_detail_id
                                  left join tr_opname_plan as t on t.tr_opname_plan_id = td.tr_opname_plan_id
                                  left join pallet p on p.pallet_id = rp.pallet_id
                                  left join sku on sku.sku_id = td3.sku_id
                                  where t.depo_id = '" . $this->session->userdata('depo_id')  . "' and td2.tr_opname_plan_id = '$dataPost->value'
                                    $selisih
                                  order by rp.pallet_id")->result_array();

		$tmpgroup = [];
		$group = [];

		foreach ($dataDetail as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['pallet_kode']);
			unset($tmpdata['pallet_id']);
			unset($tmpdata['opname_detail2_id']);
			unset($tmpdata['kode_rak']);
			unset($tmpdata['area']);
			unset($tmpdata['status']);

			$tmpgroup[$data['pallet_kode']]['pallet_kode'] = $data['pallet_kode'];
			$tmpgroup[$data['pallet_kode']]['pallet_id'] = $data['pallet_id'];
			$tmpgroup[$data['pallet_kode']]['opname_detail2_id'] = $data['opname_detail2_id'];
			$tmpgroup[$data['pallet_kode']]['kode_rak'] = $data['kode_rak'];
			$tmpgroup[$data['pallet_kode']]['area'] = $data['area'];
			$tmpgroup[$data['pallet_kode']]['status'] = $data['status'];

			$tmpgroup[$data['pallet_kode']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}

		foreach ($tmpgroup as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['pallet_kode']);
			$group[$data['pallet_kode']] = $tmpdata;
		}

		$lastUpdate = $this->db->select("tr.tr_opname_plan_tgl_update as tgl")
			->from("tr_opname_plan tr")
			->where("tr.tr_opname_plan_id", $dataPost->value)
			->get()->row();

		$response  = [
			'header' => $header,
			'detail' => $group,
			'lastUpdate' => $lastUpdate,
		];

		return $response;
	}

	public function saveDataApprovalOpname($dataPost)
	{

		$this->db->trans_begin();

		if ($dataPost->dataSupervisor) {
			foreach ($dataPost->dataSupervisor as $key => $value) {
				$this->db->set("sku_actual_qty_opname", $value->aktual_qty);
				$this->db->where("tr_opname_plan_detail3_id", $value->id_temp3);

				$this->db->update("tr_opname_plan_detail3");
			}
		}

		if ($dataPost->dataChecker) {

			$opnameDetailId = [];

			//get opname detail
			foreach ($dataPost->dataChecker as $key => $value) {
				$opnameDetail = $this->db->select("tr_opname_plan_detail_id as id")->from("tr_opname_plan_detail2")->where("tr_opname_plan_detail2_id", $value->opnameDetail2Id)->get()->row();
				$opnameDetailId[] = $opnameDetail->id;
			}

			$opnameDetailId = array_map("unserialize", array_unique(array_map("serialize", $opnameDetailId)));

			//update opname
			$this->db->set("tr_opname_plan_status", "In Progress Revision");
			$this->db->where("tr_opname_plan_id", $dataPost->opnameId);

			$this->db->update("tr_opname_plan");

			//update opname detail
			$this->db->set("tr_opname_plan_detail_status", "In Progress");
			$this->db->where_in("tr_opname_plan_detail_id", $opnameDetailId);

			$this->db->update("tr_opname_plan_detail");

			//update opname detail2
			foreach ($dataPost->dataChecker as $key => $value) {
				$this->db->set("tr_opname_plan_detail2_status", "In Progress");
				$this->db->where("tr_opname_plan_detail2_id", $value->opnameDetail2Id);
				$this->db->where("pallet_id", $value->palletId);

				$this->db->update("tr_opname_plan_detail2");
			}

			//get karyawan id by opname id
			$karyawanId = $this->db->select("karyawan_id_penanggungjawab as id")->from('tr_opname_plan')->where("tr_opname_plan_id", $dataPost->opnameId)->get()->row();

			$this->db->set("notification_id", "NewID()", FALSE);
			$this->db->set("notification_from_modul", "WMS/PersetujuanHasilStockOpname");
			$this->db->set("notification_to_modul", "WMS/ProsesOpname");
			$this->db->set("notification_judul", "Revisi Opname");
			$this->db->set("notification_keterangan", "Revisi Opname ditugaskan untuk");
			$this->db->set("notification_from_who_id", $this->session->userdata('karyawan_id'));
			$this->db->set("notification_to_who_id", $karyawanId->id);
			$this->db->set("notification_tgl", "GETDATE()", FALSE);
			$this->db->set("notification_is_read", 0);
			$this->db->set("notification_data_id", $dataPost->opnameId);
			$this->db->set("depo_id", $this->session->userdata('depo_id'));

			$this->db->insert("notification");

			senderPusher([
				'message' => 'insert notification'
			]);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	public function getDataDetailOpnameBySKU($dataPost)
	{
		if ($dataPost->type == "selisih") {
			$selisih = "and td3.sku_qty_sistem - td3.sku_actual_qty_opname != 0";
		}


		if ($dataPost->type == null) {
			$selisih = "";
		}

		//get data opname for header
		$header = $this->db->select("topp.tr_opname_plan_id as id,
																 topp.tr_opname_plan_kode as kode,
																 topp.tr_opname_plan_keterangan as keterangan,
                                 k.karyawan_nama as penanggung_jawab,
                                 FORMAT(topp.tr_opname_plan_tgl_create, 'yyyy-MM-dd') as tanggal,
                                 cw.client_wms_nama as perusahaan,
                                 p.principle_nama as principle,
                                 topp.tipe_stok as jenis_stock,
                                 tp.tipe_opname_nama as tipe_stock_opname,
                                 depo.depo_nama as unit_cabang,
                                 dd.depo_detail_nama as area_opname,
                                 topp.tr_opname_plan_status as status
                                 ")
			->from("tr_opname_plan topp")
			->join("client_wms cw", "topp.client_wms_id = cw.client_wms_id", "left")
			->join("principle p", "topp.principle_id = p.principle_id", "left")
			->join("tipe_opname tp", "topp.tipe_opname_id = tp.tipe_opname_id", "left")
			->join("karyawan k", "topp.karyawan_id_penanggungjawab = k.karyawan_id", "left")
			->join("depo", "topp.depo_id = depo.depo_id", "left")
			->join("depo_detail dd", "topp.depo_detail_id = dd.depo_detail_id", "left")
			->where("topp.tr_opname_plan_id", $dataPost->opnameId)
			->get()->row();

		$dataDetail = $this->db->query("SELECT 
                                      sku.sku_kode,
                                      sku.sku_nama_produk,
                                      sku.sku_kemasan,
                                      sku.sku_satuan,
                                      td3.sku_expired_date,
                                      rd.rak_lajur_detail_nama,
                                      (SELECT dd.depo_detail_nama from tr_opname_plan topp
                                            left join depo_detail dd on topp.depo_detail_id = dd.depo_detail_id
                                            where topp.tr_opname_plan_id = '$dataPost->opnameId') as area,
                                      p.pallet_kode,
                                      td3.sku_qty_sistem,
                                      td3.sku_actual_qty_opname,
                                      td3.sku_qty_sistem - td3.sku_actual_qty_opname as deviasi,
                                      x.tot_qty_sistem,
                                      x.tot_qty_aktual,
                                      x.tot_deviasi
                                    from tr_opname_plan_detail3 as td3
                                    inner join (select 
                                                  sku_id, 
                                                  SUM(sku_qty_sistem) as tot_qty_sistem, 
                                                  SUM(sku_actual_qty_opname) as tot_qty_aktual,
                                                  SUM(sku_qty_sistem) - SUM(sku_actual_qty_opname) as tot_deviasi
                                                from tr_opname_plan_detail3 as t3 
                                                group by sku_id ) as x on x.sku_id = td3.sku_id 
                                    left join sku on sku.sku_id = td3.sku_id
                                    left join tr_opname_plan_detail as td on td.tr_opname_plan_detail_id = td3.tr_opname_plan_detail_id
                                    left join tr_opname_plan_detail2 as td2 on td2.tr_opname_plan_detail2_id = td3.tr_opname_plan_detail2_id
                                    left join rak_lajur_detail as rd on rd.rak_lajur_detail_id = td.rak_lajur_detail_id
                                    left join tr_opname_plan as t on t.tr_opname_plan_id = td.tr_opname_plan_id
                                    left join pallet p on p.pallet_id = td2.pallet_id
                                    where t.depo_id = '" . $this->session->userdata('depo_id') . "' 
                                      and td3.tr_opname_plan_id = '$dataPost->opnameId'
                                      $selisih
                                    order by sku.sku_kode")->result_array();

		$tmpgroup = [];
		$group = [];

		foreach ($dataDetail as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['sku_kode']);
			unset($tmpdata['sku_nama_produk']);
			unset($tmpdata['sku_kemasan']);
			unset($tmpdata['sku_satuan']);
			unset($tmpdata['sku_expired_date']);
			unset($tmpdata['rak_lajur_detail_nama']);
			unset($tmpdata['area']);
			unset($tmpdata['tot_qty_sistem']);
			unset($tmpdata['tot_qty_aktual']);
			unset($tmpdata['tot_deviasi']);

			$tmpgroup[$data['sku_kode']]['sku_kode'] = $data['sku_kode'];
			$tmpgroup[$data['sku_kode']]['sku_nama_produk'] = $data['sku_nama_produk'];
			$tmpgroup[$data['sku_kode']]['sku_kemasan'] = $data['sku_kemasan'];
			$tmpgroup[$data['sku_kode']]['sku_satuan'] = $data['sku_satuan'];
			$tmpgroup[$data['sku_kode']]['sku_expired_date'] = $data['sku_expired_date'];
			$tmpgroup[$data['sku_kode']]['rak_lajur_detail_nama'] = $data['rak_lajur_detail_nama'];
			$tmpgroup[$data['sku_kode']]['area'] = $data['area'];
			$tmpgroup[$data['sku_kode']]['tot_qty_sistem'] = $data['tot_qty_sistem'];
			$tmpgroup[$data['sku_kode']]['tot_qty_aktual'] = $data['tot_qty_aktual'];
			$tmpgroup[$data['sku_kode']]['tot_deviasi'] = $data['tot_deviasi'];

			$tmpgroup[$data['sku_kode']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}
		foreach ($tmpgroup as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['sku_kode']);
			$group[$data['sku_kode']] = $tmpdata;
		}

		$response = [
			'header' => $header,
			'detail' => $group
		];

		return $response;
	}

	public function konfirmasiDataApprovalOpname($dataPost, $palletDetailId)
	{
		$this->db->trans_begin();

		//update opname
		$this->db->set("tr_opname_plan_status", "Completed");
		$this->db->set("tr_opname_plan_tgl_approve", "GETDATE()", FALSE);
		$this->db->set("tr_opname_plan_who_approve", $this->session->userdata('pengguna_username'));
		$this->db->set("tr_opname_plan_who_update", $this->session->userdata('pengguna_username'));
		$this->db->set("tr_opname_plan_tgl_update", "GETDATE()", FALSE);
		$this->db->where("tr_opname_plan_id", $dataPost->opnameId);
		$this->db->update("tr_opname_plan");

		$getPalletId = $this->db->select("topd2.pallet_id, topd.rak_lajur_detail_id")
			->from("tr_opname_plan_detail2 topd2")
			->join("tr_opname_plan_detail topd", 'topd2.tr_opname_plan_detail_id = topd.tr_opname_plan_detail_id', 'left')
			->where("topd2.tr_opname_plan_id", $dataPost->opnameId)->get()->result();
		foreach ($getPalletId as $key => $value) {
			// $this->db->set("rak_lajur_detail_id", $value->rak_lajur_detail_id);
			$this->db->set("pallet_is_lock", 0);
			$this->db->set("pallet_is_lock_reason", NULL);
			$this->db->where("pallet_id", $value->pallet_id);
			$this->db->update("pallet");
		}

		$getDataOpnameSKUStock = $this->getDataOpnameSKUStock($dataPost->opnameId);
		$dataOpnamePallet = $this->getDataOpnamePallet($dataPost->opnameId);
		$dataPallet = $this->db->query("SELECT distinct pallet_id
		FROM tr_opname_plan_detail2 t2
		where t2.tr_opname_plan_id = '$dataPost->opnameId'")->result();


		// foreach ($dataOpnamePallet as $key => $value) {
		// 	$getPalletDetail = $this->db->select("pallet_detail_id")->from('pallet_detail')->where('pallet_id', $value->pallet_id)->get()->row();
		// 	array_push($palletDetailId, $getPalletDetail->pallet_detail_id);
		// }

		// $palletId = [];
		// // $palletDetailId = [];

		// foreach ($dataOpnamePallet as $key => $value) {
		// 	$palletId[] = $value->pallet_id;
		// }

		// $getPalletDetail = $this->db->select("pallet_detail_id")->from('pallet_detail')->where_in('pallet_id', $palletId)->get()->result();
		// if (!empty($getPalletDetail)) {
		// 	foreach ($getPalletDetail as $key => $value) {
		// 		array_push($palletDetailId, $value->pallet_detail_id);
		// 	}
		// }

		// $paramsDetail = array();
		// $idx = explode(",", $sj_id);
		// if (!empty($paramsDetail)) {
		// 	for ($i = 0; $i < count($palletDetailId); $i++) {
		// 		$paramsDetail[$i] = "'" . $palletDetailId[$i] . "'";
		// 	}
		// }


		//  update lock jgn disenggol
		foreach ($getDataOpnameSKUStock as $key => $value) {
			$checkDataInSkuStocck = $this->checkDataInSkuStocck($value);
			if ($checkDataInSkuStocck->num_rows() == 0) {
				//insert ke table sku_stock
				$this->insertDataToSkuStock($value);
			} else {
				//update ke table sku_stock
				$this->updateDataToSkuStock($value, $checkDataInSkuStocck->row(0));
			}
		}

		$this->db->query("
				update a
				set a.sku_stock_id = b.sku_stock_id 
				from pallet_detail a
				inner join (
							select sku_stock_id, sku_id, sku_stock_expired_date 
								from sku_stock
								where depo_detail_id in (
														select depo_detail_id from tr_opname_plan where tr_opname_plan_id = '$dataPost->opnameId'
															)
							) as b on a.sku_id = b.sku_id and a.sku_stock_expired_date = b.sku_stock_expired_date
				where pallet_detail_id in (" . $palletDetailId . ")
		");

		$this->db->query("
			UPDATE a
			SET a.sku_stock_id = b.sku_stock_id
			FROM tr_opname_plan_detail3 a
			INNER JOIN sku_stock b
				ON a.sku_id = b.sku_id
				AND a.sku_expired_date = b.sku_stock_expired_date
				AND ISNULL(a.sku_batch_no, '') = ISNULL(b.sku_stock_batch_no, '')
			WHERE a.tr_opname_plan_id = '$dataPost->opnameId'");

		foreach ($dataOpnamePallet as $key => $value) {
			$checkDataInPalletDetail = $this->checkDataInPalletDetail($value);

			if ($checkDataInPalletDetail->num_rows() == 0) {
				//insert ke table pallet_detail
				$this->insertDataToPalletDetail($value);
			} else {
				//update ke table pallet_detail
				$this->updateDataToPalletDetail($value, $checkDataInPalletDetail->row(0));
			}
		}

		if ($dataPost->mode != "2") {
			//prosedure sku_stock_card
			$this->db->query("exec proses_posting_stock_card 'OPNAME', '$dataPost->opnameId', '" . $this->session->userdata('pengguna_username') . "'");
		}

		foreach ($dataPallet as $key => $value) {
			$this->db->query("delete pallet_detail where pallet_id = '$value->pallet_id' and (isnull(sku_stock_qty,0) - isnull(sku_stock_ambil,0) + isnull(sku_stock_in,0) - isnull(sku_stock_out,0)+ isnull(sku_stock_terima,0)) = 0");
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			// return 'oke';
			$this->db->trans_commit();
			return true;
		}
	}

	public function getDataOpnameSKUStock($opnameId)
	{
		return $this->db->query("SELECT
                              t.depo_id,
                              t.depo_detail_id,
                              sku.client_wms_id,
                              t3.sku_id,
                              FORMAT(t3.sku_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
                              sku.sku_induk_id,
                              SUM(ISNULL(t3.sku_actual_qty_opname,0)) - SUM(ISNULL(t3.sku_qty_sistem,0)) AS sku_stock_qty,
															t3.sku_batch_no
                          FROM tr_opname_plan t
                          LEFT JOIN tr_opname_plan_detail3 t3
                              ON t.tr_opname_plan_id = t3.tr_opname_plan_id
                          LEFT JOIN sku
                              ON t3.sku_id = sku.sku_id
                          WHERE t.tr_opname_plan_id = '$opnameId'
                          GROUP BY t.depo_id,
                              t.depo_detail_id,
                              sku.client_wms_id,
                              t3.sku_id,
                              FORMAT(t3.sku_expired_date, 'yyyy-MM-dd'),
                              sku.sku_induk_id,t3.sku_batch_no")->result();
	}

	public function getDataOpnamePallet($opnameId)
	{
		return $this->db->query("SELECT
                              t.depo_id,
                              t.depo_detail_id,
                              t2.pallet_id,
                              t3.sku_id,
                              FORMAT(t3.sku_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
                              SUM(ISNULL(t3.sku_actual_qty_opname,0)) - SUM(ISNULL(t3.sku_qty_sistem,0)) AS sku_stock_qty
                            FROM tr_opname_plan_detail2 t2
                            LEFT JOIN tr_opname_plan t
	                              ON t2.tr_opname_plan_id = t.tr_opname_plan_id
                            LEFT JOIN tr_opname_plan_detail3 t3
                                ON t2.tr_opname_plan_detail2_id = t3.tr_opname_plan_detail2_id
                            WHERE t2.tr_opname_plan_id = '$opnameId'
                            GROUP BY
                                t.depo_id,
	                              t.depo_detail_id,
                                t2.pallet_id,
                                t3.sku_id,
                                FORMAT(t3.sku_expired_date, 'yyyy-MM-dd')")->result();
	}

	public function checkDataInSkuStocck($data)
	{
		return $this->db->select("*")
			->from("sku_stock")
			->where("depo_id", $data->depo_id)
			->where("depo_detail_id", $data->depo_detail_id)
			->where("sku_id", $data->sku_id)
			->where("sku_stock_batch_no", isset($data->sku_batch_no) ? $data->sku_batch_no : NULL)
			->where("FORMAT(sku_stock_expired_date,'yyyy-MM-dd')", $data->sku_stock_expired_date)
			->get();
	}

	public function checkDataInPalletDetail($data)
	{
		return $this->db->select("pallet_detail_id, ISNULL(sku_stock_in, 0) as sku_stock_in, ISNULL(sku_stock_out, 0) as sku_stock_out, pallet_id, sku_stock_id")
			->from("pallet_detail")
			->where("pallet_id", $data->pallet_id)
			->where("sku_id", $data->sku_id)
			->where("FORMAT(sku_stock_expired_date,'yyyy-MM-dd')", $data->sku_stock_expired_date)
			->get();
	}

	public function insertDataToSkuStock($data)
	{

		$this->db->set("sku_stock_id", "NEWID()", FALSE);
		$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
		$this->db->set("client_wms_id", $data->client_wms_id);
		$this->db->set("depo_id", $data->depo_id);
		$this->db->set("depo_detail_id", $data->depo_detail_id);
		$this->db->set("sku_induk_id", $data->sku_induk_id);
		$this->db->set("sku_id", $data->sku_id);
		$this->db->set("sku_stock_expired_date", $data->sku_stock_expired_date);
		$this->db->set("sku_stock_batch_no", $data->sku_batch_no == null ? NULL : $data->sku_batch_no);
		$this->db->set("sku_stock_awal", 0);
		if ($data->sku_stock_qty < 0) {
			$this->db->set("sku_stock_keluar", $data->sku_stock_qty);
			$this->db->set("sku_stock_masuk", 0);
		} else {
			$this->db->set("sku_stock_masuk", $data->sku_stock_qty);
			$this->db->set("sku_stock_keluar", 0);
		}
		$this->db->set("sku_stock_alokasi", 0);
		$this->db->set("sku_stock_saldo_alokasi", 0);
		$this->db->set("sku_stock_akhir", 0);
		$this->db->set("sku_stock_is_jual", 1);
		$this->db->set("sku_stock_is_aktif", 1);
		$this->db->set("sku_stock_is_deleted", 0);

		return $this->db->insert("sku_stock");
	}

	public function updateDataToSkuStock($data, $skuAwal)
	{
		if ($data->sku_stock_qty < 0) {
			// PROCEDURE MODE KELUAR
			$sku_stock_keluar = abs($data->sku_stock_qty);
			return $this->db->query("exec insertupdate_sku_stock 'keluar', '$skuAwal->sku_stock_id', NULL, $sku_stock_keluar");
		} else {
			// PROCEDURE MODE MASUK
			$sku_stock_masuk = $data->sku_stock_qty;
			return $this->db->query("exec insertupdate_sku_stock 'masuk', '$skuAwal->sku_stock_id', NULL, $sku_stock_masuk");
		}
	}

	public function insertDataToPalletDetail($data)
	{

		$getSKuStock = $this->checkDataInSkuStocck($data)->row();

		$this->db->set("pallet_detail_id", "NEWID()", FALSE);
		$this->db->set("pallet_id", $data->pallet_id);
		$this->db->set("sku_id", $data->sku_id);
		$this->db->set("sku_stock_id", $getSKuStock->sku_stock_id);
		$this->db->set("sku_stock_expired_date", $data->sku_stock_expired_date);
		$this->db->set("sku_stock_qty", 0);
		if ($data->sku_stock_qty < 0) {
			$this->db->set("sku_stock_out", $data->sku_stock_qty);
		} else {
			$this->db->set("sku_stock_in", $data->sku_stock_qty);
		}

		return $this->db->insert("pallet_detail");
	}

	public function updateDataToPalletDetail($data, $dataAwal)
	{
		if ($data->sku_stock_qty < 0) {
			// $sku_stock_out = $dataAwal->sku_stock_out + abs($data->sku_stock_qty);
			// $this->db->set("sku_stock_out", $sku_stock_out);

			return $this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_out', '$dataAwal->pallet_id', '$dataAwal->sku_stock_id', '" . abs($data->sku_stock_qty) . "'");
		} else {
			// $sku_stock_in = $dataAwal->sku_stock_in + $data->sku_stock_qty;
			// $this->db->set("sku_stock_in", $sku_stock_in);

			return $this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_in', '$dataAwal->pallet_id', '$dataAwal->sku_stock_id', '$data->sku_stock_qty'");
		}

		// $this->db->where("pallet_detail_id", $dataAwal->pallet_detail_id);
		// return $this->db->update("pallet_detail");
	}

	public function getCompareOpname($dataPost)
	{
		$opnameTempId = $this->M_Vrbl->Get_NewID();
		$opnameTempId = $opnameTempId[0]['NEW_ID'];

		foreach ($dataPost->opnameId as $key => $value) {
			$this->db->set("tr_opname_plan_temp_id", $opnameTempId);
			$this->db->set("tr_opname_plan_temp_urut", $key + 1);
			$this->db->set("tr_opname_plan_id", $value);
			$this->db->insert("tr_opname_plan_temp");
		}

		$getIdTemp = $this->db->select("tr_opname_plan_temp_id")->from("tr_opname_plan_temp")->where_in("tr_opname_plan_id", $dataPost->opnameId)->get()->row();

		//exec prosedure
		$result = $this->db->query("exec proses_compare_opname '$getIdTemp->tr_opname_plan_temp_id'")->result_array();

		$tmpgroup = [];
		$group = [];

		foreach ($result as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['pallet_kode']);
			unset($tmpdata['pallet_id']);
			unset($tmpdata['rak_lajur_detail_nama']);
			unset($tmpdata['rak_lajur_detail_id']);
			unset($tmpdata['depo_detail_nama']);
			unset($tmpdata['namadokumen1']);
			unset($tmpdata['namadokumen2']);
			unset($tmpdata['namadokumen3']);

			$tmpgroup[$data['pallet_kode']]['pallet_kode'] = $data['pallet_kode'];
			$tmpgroup[$data['pallet_kode']]['pallet_id'] = $data['pallet_id'];
			$tmpgroup[$data['pallet_kode']]['rak_lajur_detail_nama'] = $data['rak_lajur_detail_nama'];
			$tmpgroup[$data['pallet_kode']]['rak_lajur_detail_id'] = $data['rak_lajur_detail_id'];
			$tmpgroup[$data['pallet_kode']]['depo_detail_nama'] = $data['depo_detail_nama'];
			$tmpgroup[$data['pallet_kode']]['namadokumen1'] = $data['namadokumen1'];
			$tmpgroup[$data['pallet_kode']]['namadokumen2'] = $data['namadokumen2'];
			$tmpgroup[$data['pallet_kode']]['namadokumen3'] = $data['namadokumen3'];

			$tmpgroup[$data['pallet_kode']]['data'][] = $tmpdata; /* harus tetap ada key-nya, dalam hal ini 'data' */
		}
		foreach ($tmpgroup as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['pallet_kode']);
			$group[$data['pallet_kode']] = $tmpdata;
		}

		return $group;
	}

	public function getDepoPrefix($depo_id)
	{
		$data = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $data->row();
	}

	public function saveDataApprovalOpnameByCompare($opnameId, $mappingData)
	{
		$tmpgroup = [];
		$fixData = [];

		$this->db->trans_begin();

		$newOpnameId = $this->M_Function->Get_NewID();
		$newOpnameId = $newOpnameId[0]['kode'];

		//update status opname sblmya jadi completed, dan is_compare jadi 1
		$this->db->set('tr_opname_plan_status', 'Completed');
		// $this->db->set('tr_opname_plan_is_hasil_compare', 1);
		$this->db->set('tr_opname_plan_idhasilcompare', $newOpnameId);
		$this->db->where_in('tr_opname_plan_id', $opnameId);
		$this->db->update('tr_opname_plan');

		//get salah satu header opname
		$oldOpname = $this->db->get_where('tr_opname_plan', ['tr_opname_plan_id' => $opnameId[0]])->row();

		$date_now = date('Y-m-d h:i:s');
		$param =  'KODE_OPCOMP';
		$vrbl = $this->M_Vrbl->Get_Kode($param);
		$prefix = $vrbl->vrbl_kode;
		// get prefik depo
		$depoPrefix = $this->getDepoPrefix($this->session->userdata('depo_id'));
		$unit = $depoPrefix->depo_kode_preffix;
		$generateKode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

		//insert opname
		$this->db->set("tr_opname_plan_id", $newOpnameId);
		$this->db->set("depo_id", $oldOpname->depo_id);
		$this->db->set("depo_detail_id", $oldOpname->depo_detail_id);
		$this->db->set("tr_opname_plan_kode", $generateKode);
		$this->db->set("tr_opname_plan_tanggal", "GETDATE()", false);
		$this->db->set("client_wms_id", $oldOpname->client_wms_id);
		$this->db->set("karyawan_id_penanggungjawab", $this->session->userdata('karyawan_id'));
		$this->db->set("principle_id", $oldOpname->principle_id);
		$this->db->set("tipe_stok", $oldOpname->tipe_stok);
		$this->db->set("tipe_opname_id", 'CAC2CFC6-4371-4021-A8FE-AEF91F7C970F');
		$this->db->set("tr_opname_plan_keterangan", 'Hasil compare');
		$this->db->set("tr_opname_plan_status", 'Completed');
		$this->db->set("tr_opname_plan_tgl_create", "GETDATE()", false);
		$this->db->set("tr_opname_plan_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("tr_opname_plan_tgl_start", "GETDATE()", false);
		$this->db->set("tr_opname_plan_tgl_end", "GETDATE()", false);
		$this->db->set("tr_opname_plan_is_hasil_compare", 1);
		$this->db->insert("tr_opname_plan");

		foreach ($mappingData as $key => $data) {
			$tmpdata = $data;
			unset($tmpdata['palletId']);
			unset($tmpdata['rakLajurDetailId']);

			$tmpgroup[$data['palletId']]['palletId'] = $data['palletId'];
			$tmpgroup[$data['palletId']]['rakLajurDetailId'] = $data['rakLajurDetailId'];

			$tmpgroup[$data['palletId']]['data'][] = $tmpdata;
		}
		foreach ($tmpgroup as $key => $data) {
			$fixData[] = $data;
		}

		foreach ($fixData as $key => $value) {
			/** insert opname detail */
			$newOpnameDetailId = $this->M_Function->Get_NewID();
			$newOpnameDetailId = $newOpnameDetailId[0]['kode'];
			$this->db->set("tr_opname_plan_detail_id", $newOpnameDetailId);
			$this->db->set("tr_opname_plan_id", $newOpnameId);
			$this->db->set("rak_lajur_detail_id", $value['rakLajurDetailId']);
			$this->db->set("tr_opname_plan_detail_status", 'Completed');
			$this->db->insert("tr_opname_plan_detail");
			/** end insert opname detail */

			/** insert opname detail 2 */
			$newOpnameDetail2Id = $this->M_Function->Get_NewID();
			$newOpnameDetail2Id = $newOpnameDetail2Id[0]['kode'];
			$this->db->set("tr_opname_plan_detail2_id", $newOpnameDetail2Id);
			$this->db->set("tr_opname_plan_detail_id", $newOpnameDetailId);
			$this->db->set("tr_opname_plan_id", $newOpnameId);
			$this->db->set("pallet_id", $value['palletId']);
			$this->db->set("tr_opname_plan_detail2_status", "Completed");
			$this->db->set("is_valid", 1);
			$this->db->insert("tr_opname_plan_detail2");

			$this->db->set("rak_lajur_detail_id", $value['rakLajurDetailId']);
			$this->db->set("pallet_is_lock", 0);
			$this->db->set("pallet_is_lock_reason", NULL);
			$this->db->where("pallet_id", $value['palletId']);
			$this->db->update("pallet");
			/** end insert opname detail 2 */


			/** insert opname detail 3 */
			foreach ($value['data'] as $key => $val) {

				$getQtySistem = $this->db->select("pallet_id, sku_stock_id, sku_stock_qty")->from("pallet_detail")->where('pallet_id', $value['palletId'])->where("sku_stock_id", $val['skuStockId'])->get()->row();

				$this->db->set("tr_opname_plan_detail3_id", "NewID()", FALSE);
				$this->db->set("tr_opname_plan_detail2_id", $newOpnameDetail2Id);
				$this->db->set("tr_opname_plan_detail_id", $newOpnameDetailId);
				$this->db->set("tr_opname_plan_id", $newOpnameId);
				$this->db->set("sku_id", $val['skuId']);
				$this->db->set("sku_stock_id", $val['skuStockId']);
				$this->db->set("sku_expired_date", $val['expDate']);
				$this->db->set("sku_actual_qty_opname", $val['qty']);
				if (empty($getQtySistem)) {
					$this->db->set("sku_qty_sistem", 0);
				} else {
					if (($value['palletId'] == $getQtySistem->pallet_id) && ($val['skuStockId'] == $getQtySistem->sku_stock_id)) {
						$this->db->set("sku_qty_sistem", $getQtySistem->sku_stock_qty);
					}
				}
				$this->db->insert("tr_opname_plan_detail3");
			}
			/** end insert opname detail 3 */
		}

		//update sku stok dan pallet detail
		$getDataOpnameSKUStock = $this->getDataOpnameSKUStock($newOpnameId);
		$dataOpnamePallet = $this->getDataOpnamePallet($newOpnameId);

		foreach ($getDataOpnameSKUStock as $key => $value) {
			$checkDataInSkuStocck = $this->checkDataInSkuStocck($value);
			if ($checkDataInSkuStocck->num_rows() == 0) {
				//insert ke table sku_stock
				$this->insertDataToSkuStock($value);
			} else {
				//update ke table sku_stock
				$this->updateDataToSkuStock($value, $checkDataInSkuStocck->row(0));
			}
		}

		foreach ($dataOpnamePallet as $key => $value) {
			$checkDataInPalletDetail = $this->checkDataInPalletDetail($value);

			if ($checkDataInPalletDetail->num_rows() == 0) {
				//insert ke table sku_stock
				$this->insertDataToPalletDetail($value);
			} else {
				//update ke table sku_stock
				$this->updateDataToPalletDetail($value, $checkDataInPalletDetail->row(0));
			}
		}

		$palletId = [];
		$palletDetailId = [];

		foreach ($dataOpnamePallet as $key => $value) {
			$palletId[] = $value->pallet_id;
		}

		$getPalletDetail = $this->db->select("pallet_detail_id")->from('pallet_detail')->where_in('pallet_id', $palletId)->get()->result();
		if (!empty($getPalletDetail)) {
			foreach ($getPalletDetail as $key => $value) {
				array_push($palletDetailId, $value->pallet_detail_id);
				# code...
			}
		}
		// echo json_encode($palletDetailId);

		$paramsDetail = array();
		// $idx = explode(",", $sj_id);
		if (!empty($palletDetailId)) {
			for ($i = 0; $i < count($palletDetailId); $i++) {
				$paramsDetail[$i] = "'" . $palletDetailId[$i] . "'";
			}
		}

		$this->db->query("
				update a
				set a.sku_stock_id = b.sku_stock_id 
				from pallet_detail a
				inner join (
							select sku_stock_id, sku_id, sku_stock_expired_date 
								from sku_stock
								where depo_detail_id in (
														select depo_detail_id from tr_opname_plan where tr_opname_plan_id = '$newOpnameId'
															)
							) as b on a.sku_id = b.sku_id and a.sku_stock_expired_date = b.sku_stock_expired_date
				where pallet_detail_id in (" . implode(',', $paramsDetail) . ")
		");

		//prosedure sku_stock_card
		$this->db->query("exec proses_posting_stock_card 'OPNAME', '$newOpnameId', '" . $this->session->userdata('pengguna_username') . "'");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}
}
