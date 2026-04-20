<?php

class M_PengeluaranBarang extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function Get_PengeluaranBarang($tgl1, $tgl2, $No_PPB, $No_FDJR, $No_PB, $Tipe_PB, $Status_PB)
	{
		if ($No_PPB == "") {
			$No_PPB = "";
		} else {
			$No_PPB = " AND picking_order.picking_order_kode = '$No_PPB' ";
		}
		if ($No_FDJR == "") {
			$No_FDJR = "";
		} else {
			$No_FDJR = " AND delivery_order_batch.delivery_order_batch_kode = '$No_FDJR' ";
		}
		if ($No_PB == "") {
			$No_PB = "";
		} else {
			$No_PB = " AND picking_list.picking_list_kode = '$No_PB' ";
		}
		if ($Tipe_PB == "all") {
			$Tipe_PB = "";
		} else {
			$Tipe_PB = " AND picking_list.tipe_delivery_order_id IN ($Tipe_PB) ";
		}

		if ($Status_PB == "all") {
			$Status_PB = "";
		} else {
			$Status_PB = " AND picking_order.picking_order_status = '$Status_PB'";
		}

		$query = $this->db->query("SELECT DISTINCT
										picking_order.picking_order_id,
										picking_order.depo_id,
										depo.depo_nama,
										picking_order.picking_order_kode,
										FORMAT(picking_order.picking_order_tanggal, 'dd-MM-yyyy') AS picking_order_tanggal,
										delivery_order_batch.delivery_order_batch_kode,
										FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim,
										picking_list.picking_list_kode,
										FORMAT(picking_list.picking_list_tgl_kirim, 'dd-MM-yyyy') AS picking_list_tgl_kirim,
										picking_list.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias AS picking_list_tipe,
										picking_order.picking_order_keterangan,
										picking_order.picking_order_status,
										-- picking_order_plan.picking_order_plan_status,
										picking_order_plan.tipe_delivery_order_id,
										tipe_delivery_order_plan.tipe_delivery_order_alias AS picking_order_plan_tipe,
										karyawan.karyawan_nama,
										delivery_order_batch.delivery_order_batch_id,
										kendaraan.kendaraan_nopol
									FROM picking_order
									LEFT JOIN picking_order_plan
										ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN picking_list
										ON picking_list.picking_list_id = picking_order.picking_list_id
									LEFT JOIN delivery_order
										ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN delivery_order_batch
										ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN sku_stock
										ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN depo
										ON depo.depo_id = picking_order.depo_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = picking_list.tipe_delivery_order_id
									LEFT JOIN tipe_delivery_order tipe_delivery_order_plan
										ON tipe_delivery_order_plan.tipe_delivery_order_id = picking_list.tipe_delivery_order_id
									LEFT JOIN karyawan ON delivery_order_batch.karyawan_id = karyawan.karyawan_id
									LEFT JOIN kendaraan ON delivery_order_batch.kendaraan_id = kendaraan.kendaraan_id
									WHERE picking_order.depo_id = '" . $this->session->userdata('depo_id') . "' 
											AND FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
																		" . $No_PPB . " 
																		" . $No_FDJR . " 
																		" . $No_PB . " 
																		" . $Tipe_PB . "
																		" . $Status_PB . "
									GROUP BY picking_order.picking_order_id,
											picking_order.depo_id,
											depo.depo_nama,
											picking_order.picking_order_kode,
											picking_order.picking_order_tanggal,
											delivery_order_batch.delivery_order_batch_kode,
											delivery_order_batch.delivery_order_batch_tanggal_kirim,
											picking_list.picking_list_kode,
											picking_list.picking_list_tgl_kirim,
											picking_list.tipe_delivery_order_id,
											tipe_delivery_order.tipe_delivery_order_alias,
											picking_order.picking_order_keterangan,
											picking_order.picking_order_status,
											picking_order_plan.tipe_delivery_order_id,
											tipe_delivery_order_plan.tipe_delivery_order_alias,
											karyawan.karyawan_nama,
											delivery_order_batch.delivery_order_batch_id,
											kendaraan.kendaraan_nopol
									ORDER BY FORMAT(picking_order.picking_order_tanggal, 'dd-MM-yyyy'), picking_order.picking_order_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_DetailPengeluaranBarang($picking_order_kode)
	{
		$query = $this->db->query("SELECT
										picking_order.picking_order_id,
										picking_order_plan.picking_order_plan_id,
										picking_order.depo_id,
										picking_order.depo_detail_id,
										depo_detail.depo_detail_nama,
										ISNULL(rak.rak_nama, '') AS rak_nama,
										depo.depo_nama,
										picking_order.picking_order_kode,
										picking_order_plan.picking_order_plan_nourut,
										FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd') AS picking_order_tanggal,
										picking_order_plan.delivery_order_id,
										delivery_order.delivery_order_kode,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order_batch.delivery_order_batch_tanggal_kirim,
										picking_list.picking_list_id,
										picking_list.picking_list_kode,
										picking_list.picking_list_tgl_kirim,
										picking_order_plan.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias AS picking_order_plan_tipe,
										tipe_delivery_order.tipe_delivery_order_nama,
										picking_order.picking_order_keterangan,
										picking_order.picking_order_status,
										picking_order_plan.picking_order_plan_status,
										picking_order.picking_order_tgl_update,
										principal.principle_kode AS principal,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_satuan,
										sku.sku_kemasan,
										picking_order_plan.sku_id,
										picking_order_plan.sku_stock_id,
										FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
										picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil,
										SUM(picking_order_aktual.sku_stock_qty_ambil) AS sku_stock_qty_ambil_aktual,
										CASE
										WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
										ELSE picking_order_plan.karyawan_id
										END AS karyawan_id,
										CASE
										WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
										ELSE picking_order_plan.karyawan_nama
										END AS karyawan_nama,
										karyawan.karyawan_nama as driver
									FROM picking_order
									LEFT JOIN picking_order_plan
										ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN (SELECT
										picking_order_aktual_h.picking_order_id,
										picking_order_aktual_h.karyawan_id,
										picking_order_aktual_h.karyawan_nama,
										picking_order_aktual_d.picking_order_plan_id,
										picking_order_aktual_d.delivery_order_id,
										picking_order_aktual_d.sku_id,
										picking_order_aktual_d.sku_stock_id,
										picking_order_aktual_d.sku_stock_expired_date,
										SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
									FROM picking_order_aktual_h
									LEFT JOIN picking_order_aktual_d
										ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
									GROUP BY picking_order_aktual_h.picking_order_id,
											picking_order_aktual_h.karyawan_id,
											picking_order_aktual_h.karyawan_nama,
											picking_order_aktual_d.picking_order_plan_id,
											picking_order_aktual_d.delivery_order_id,
											picking_order_aktual_d.sku_id,
											picking_order_aktual_d.sku_stock_id,
											picking_order_aktual_d.sku_stock_expired_date) picking_order_aktual
										ON picking_order.picking_order_id = picking_order_aktual.picking_order_id
										AND picking_order_aktual.picking_order_plan_id = picking_order_plan.picking_order_plan_id
									LEFT JOIN picking_list
										ON picking_list.picking_list_id = picking_order.picking_list_id
									LEFT JOIN delivery_order
										ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN delivery_order_batch
										ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN karyawan 
										ON delivery_order_batch.karyawan_id = karyawan.karyawan_id
									LEFT JOIN sku_stock
										ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN rak_sku
										ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
									LEFT JOIN rak
										ON rak.rak_id = rak_sku.rak_id
									LEFT JOIN SKU
										ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN sku_induk
										ON SKU.sku_induk_id = sku_induk.sku_induk_id
									LEFT JOIN principle principal
										ON sku.principle_id = principal.principle_id
									LEFT JOIN depo_detail
										ON depo_detail.depo_detail_id = picking_order.depo_detail_id
									LEFT JOIN depo
										ON depo.depo_id = picking_order.depo_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = picking_order_plan.tipe_delivery_order_id
									WHERE picking_order.picking_order_kode = '$picking_order_kode'
									GROUP BY picking_order.picking_order_id,
											picking_order_plan.picking_order_plan_id,
											picking_order.depo_id,
											picking_order.depo_detail_id,
											depo_detail.depo_detail_nama,
											ISNULL(rak.rak_nama, ''),
											depo.depo_nama,
											picking_order.picking_order_kode,
											picking_order_plan.picking_order_plan_nourut,
											FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd'),
											picking_order_plan.delivery_order_id,
											delivery_order.delivery_order_kode,
											delivery_order_batch.delivery_order_batch_kode,
											delivery_order_batch.delivery_order_batch_tanggal_kirim,
											picking_list.picking_list_id,
											picking_list.picking_list_kode,
											picking_list.picking_list_tgl_kirim,
											picking_order_plan.tipe_delivery_order_id,
											tipe_delivery_order.tipe_delivery_order_alias,
											tipe_delivery_order.tipe_delivery_order_nama,
											picking_order.picking_order_keterangan,
											picking_order.picking_order_status,
											picking_order_plan.picking_order_plan_status,
											picking_order.picking_order_tgl_update,
											principal.principle_kode,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.sku_satuan,
											sku.sku_kemasan,
											picking_order_plan.sku_id,
											picking_order_plan.sku_stock_id,
											FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
											picking_order_plan.sku_stock_qty_ambil,
											CASE
												WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
												ELSE picking_order_plan.karyawan_id
											END,
											CASE
												WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
												ELSE picking_order_plan.karyawan_nama
											END,
											karyawan.karyawan_nama
									ORDER BY principal.principle_kode, sku.sku_kode, FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_DetailPengeluaranBarangMix($picking_order_kode)
	{

		$pickingListId = $this->db->select("picking_list_id")->from("picking_order")->where("picking_order_kode", $picking_order_kode)->get()->row()->picking_list_id;

		$query = $this->db->query("SELECT
																	picking_order.picking_order_id,
																	picking_order_plan.picking_order_plan_id,
																	picking_order.depo_id,
																	picking_order.depo_detail_id,
																	depo_detail.depo_detail_nama,
																	ISNULL(rak.rak_nama, '') AS rak_nama,
																	depo.depo_nama,
																	picking_order.picking_order_kode,
																	picking_order_plan.picking_order_plan_nourut,
																	FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd') AS picking_order_tanggal,
																	--picking_order_plan.delivery_order_id,
																	--delivery_order.delivery_order_kode,
																	delivery_order_batch.delivery_order_batch_kode,
																	delivery_order_batch.delivery_order_batch_tanggal_kirim,
																	picking_list.picking_list_id,
																	picking_list.picking_list_kode,
																	picking_list.picking_list_tgl_kirim,
																	delivery_order.tipe_delivery_order_id,
																	CASE 
																		WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
																		WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk')
																		ELSE NULL
																	END as tipe_delivery_order_nama,
																	CASE 
																		WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
																		WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU')
																		ELSE NULL
																	END as picking_order_plan_tipe,
																	--ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') AS tipe_delivery_order_nama,
																	--ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU') AS picking_order_plan_tipe,
																	picking_order.picking_order_keterangan,
																	picking_order.picking_order_status,
																	picking_order_plan.picking_order_plan_status,
																	principal.principle_kode AS principal,
																	sku.sku_kode,
																	sku.sku_nama_produk,
																	sku.sku_satuan,
																	sku.sku_kemasan,
																	picking_order_plan.sku_id,
																	picking_order_plan.sku_stock_id,
																	FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
																	picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil,
																	picking_order_aktual.sku_stock_qty_ambil AS sku_stock_qty_ambil_aktual,
																	CASE
																		WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
																		ELSE picking_order_plan.karyawan_id
																	END AS karyawan_id,
																	CASE
																		WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
																		ELSE picking_order_plan.karyawan_nama
																	END AS karyawan_nama
																FROM picking_order
																LEFT JOIN picking_order_plan
																	ON picking_order.picking_order_id = picking_order_plan.picking_order_id
																LEFT JOIN (SELECT
																	picking_order_aktual_h.picking_order_id,
																	picking_order_aktual_h.karyawan_id,
																	picking_order_aktual_h.karyawan_nama,
																	picking_order_aktual_d.picking_order_plan_id,
																	picking_order_aktual_d.delivery_order_id,
																	picking_order_aktual_d.sku_id,
																	picking_order_aktual_d.sku_stock_id,
																	picking_order_aktual_d.sku_stock_expired_date,
																	SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
																FROM picking_order_aktual_h
																LEFT JOIN picking_order_aktual_d
																	ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
																GROUP BY picking_order_aktual_h.picking_order_id,
																				picking_order_aktual_h.karyawan_id,
																				picking_order_aktual_h.karyawan_nama,
																				picking_order_aktual_d.picking_order_plan_id,
																				picking_order_aktual_d.delivery_order_id,
																				picking_order_aktual_d.sku_id,
																				picking_order_aktual_d.sku_stock_id,
																				picking_order_aktual_d.sku_stock_expired_date) picking_order_aktual
																	ON picking_order.picking_order_id = picking_order_aktual.picking_order_id
																	AND picking_order_aktual.picking_order_plan_id = picking_order_plan.picking_order_plan_id
																LEFT JOIN picking_list
																	ON picking_list.picking_list_id = picking_order.picking_list_id
																LEFT JOIN (select
																							do.delivery_order_id,
																							do.delivery_order_kode,
																							do.delivery_order_reff_id,
																							do.tipe_delivery_order_id,
																							dod2.sku_id,
																							dod2.sku_stock_id
																						from delivery_order do
																						left join delivery_order_detail2 dod2
																							on dod2.delivery_order_id = do.delivery_order_id
																						where do.delivery_order_id in (select delivery_order_id from picking_list_detail where picking_list_id = '$pickingListId')) delivery_order
																	ON delivery_order.sku_stock_id = picking_order_plan.sku_stock_id
																LEFT JOIN delivery_order_batch
																	ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
																LEFT JOIN rak_sku
																	ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
																LEFT JOIN rak
																	ON rak.rak_id = rak_sku.rak_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_order_plan.sku_id
																LEFT JOIN sku_induk
																	ON SKU.sku_induk_id = sku_induk.sku_induk_id
																LEFT JOIN principle principal
																	ON sku.principle_id = principal.principle_id
																LEFT JOIN depo_detail
																	ON depo_detail.depo_detail_id = picking_order.depo_detail_id
																LEFT JOIN depo
																	ON depo.depo_id = picking_order.depo_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
																WHERE picking_order.picking_order_kode = '$picking_order_kode'
																GROUP BY picking_order.picking_order_id,
																				picking_order_plan.picking_order_plan_id,
																				picking_order.depo_id,
																				picking_order.depo_detail_id,
																				depo_detail.depo_detail_nama,
																				ISNULL(rak.rak_nama, ''),
																				depo.depo_nama,
																				picking_order.picking_order_kode,
																				picking_order_plan.picking_order_plan_nourut,
																				FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd'),
																				--picking_order_plan.delivery_order_id,
																				--delivery_order.delivery_order_kode,
																				delivery_order_batch.delivery_order_batch_kode,
																				delivery_order_batch.delivery_order_batch_tanggal_kirim,
																				picking_list.picking_list_id,
																				picking_list.picking_list_kode,
																				picking_list.picking_list_tgl_kirim,
																				delivery_order.tipe_delivery_order_id,
																				picking_order_aktual.sku_stock_qty_ambil,
																				CASE 
																					WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
																					WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk')
																					ELSE NULL
																				END,
																				CASE 
																					WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
																					WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU')
																					ELSE NULL
																				END,
																				--ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk'),
																				--ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU'),
																				picking_order.picking_order_keterangan,
																				picking_order.picking_order_status,
																				picking_order_plan.picking_order_plan_status,
																				principal.principle_kode,
																				sku.sku_kode,
																				sku.sku_nama_produk,
																				sku.sku_satuan,
																				sku.sku_kemasan,
																				picking_order_plan.sku_id,
																				picking_order_plan.sku_stock_id,
																				FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
																				picking_order_plan.sku_stock_qty_ambil,
																				CASE
																					WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
																					ELSE picking_order_plan.karyawan_id
																				END,
																				CASE
																					WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
																					ELSE picking_order_plan.karyawan_nama
																				END
																ORDER BY principal.principle_kode, sku.sku_kode, FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') ASC");

		// $query = $this->db->query("SELECT
		// 							picking_order.picking_order_id,
		// 							picking_order_plan.picking_order_plan_id,
		// 							picking_order.depo_id,
		// 							picking_order.depo_detail_id,
		// 							depo_detail.depo_detail_nama,
		// 							ISNULL(rak.rak_nama, '') AS rak_nama,
		// 							depo.depo_nama,
		// 							picking_order.picking_order_kode,
		// 							picking_order_plan.picking_order_plan_nourut,
		// 							FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd') AS picking_order_tanggal,
		// 							picking_order_plan.delivery_order_id,
		// 							delivery_order.delivery_order_kode,
		// 							delivery_order_batch.delivery_order_batch_kode,
		// 							delivery_order_batch.delivery_order_batch_tanggal_kirim,
		// 							picking_list.picking_list_id,
		// 							picking_list.picking_list_kode,
		// 							picking_list.picking_list_tgl_kirim,
		// 							delivery_order.tipe_delivery_order_id,
		// 							ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') as tipe_delivery_order_nama,
		// 							ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU') AS picking_order_plan_tipe,
		// 							picking_order.picking_order_keterangan,
		// 							picking_order.picking_order_status,
		// 							picking_order_plan.picking_order_plan_status,
		// 							principal.principle_kode AS principal,
		// 							sku.sku_kode,
		// 							sku.sku_nama_produk,
		// 							sku.sku_satuan,
		// 							sku.sku_kemasan,
		// 							picking_order_plan.sku_id,
		// 							picking_order_plan.sku_stock_id,
		// 							FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
		// 							picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil,
		// 							SUM(picking_order_aktual.sku_stock_qty_ambil) AS sku_stock_qty_ambil_aktual,
		// 							CASE
		// 								WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
		// 								ELSE picking_order_plan.karyawan_id
		// 							END AS karyawan_id,
		// 							CASE
		// 								WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
		// 								ELSE picking_order_plan.karyawan_nama
		// 							END AS karyawan_nama
		// 						FROM picking_order
		// 						LEFT JOIN picking_order_plan
		// 							ON picking_order.picking_order_id = picking_order_plan.picking_order_id
		// 						LEFT JOIN (SELECT
		// 							picking_order_aktual_h.picking_order_id,
		// 							picking_order_aktual_h.karyawan_id,
		// 							picking_order_aktual_h.karyawan_nama,
		// 							picking_order_aktual_d.picking_order_plan_id,
		// 							picking_order_aktual_d.delivery_order_id,
		// 							picking_order_aktual_d.sku_id,
		// 							picking_order_aktual_d.sku_stock_id,
		// 							picking_order_aktual_d.sku_stock_expired_date,
		// 							SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
		// 						FROM picking_order_aktual_h
		// 						LEFT JOIN picking_order_aktual_d
		// 							ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
		// 						GROUP BY picking_order_aktual_h.picking_order_id,
		// 								picking_order_aktual_h.karyawan_id,
		// 								picking_order_aktual_h.karyawan_nama,
		// 								picking_order_aktual_d.picking_order_plan_id,
		// 								picking_order_aktual_d.delivery_order_id,
		// 								picking_order_aktual_d.sku_id,
		// 								picking_order_aktual_d.sku_stock_id,
		// 								picking_order_aktual_d.sku_stock_expired_date) picking_order_aktual
		// 							ON picking_order.picking_order_id = picking_order_aktual.picking_order_id
		// 							AND picking_order_aktual.picking_order_plan_id = picking_order_plan.picking_order_plan_id
		// 						LEFT JOIN picking_list
		// 							ON picking_list.picking_list_id = picking_order.picking_list_id
		// 						LEFT JOIN delivery_order
		// 							ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
		// 						LEFT JOIN delivery_order_batch
		// 							ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
		// 						LEFT JOIN sku_stock
		// 							ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
		// 						LEFT JOIN rak_sku
		// 							ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
		// 						LEFT JOIN rak
		// 							ON rak.rak_id = rak_sku.rak_id
		// 						LEFT JOIN SKU
		// 							ON SKU.sku_id = picking_order_plan.sku_id
		// 						LEFT JOIN sku_induk
		// 							ON SKU.sku_induk_id = sku_induk.sku_induk_id
		// 						LEFT JOIN principle principal
		// 							ON sku.principle_id = principal.principle_id
		// 						LEFT JOIN depo_detail
		// 							ON depo_detail.depo_detail_id = picking_order.depo_detail_id
		// 						LEFT JOIN depo
		// 							ON depo.depo_id = picking_order.depo_id
		// 						LEFT JOIN tipe_delivery_order
		// 							ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
		// 						WHERE picking_order.picking_order_kode = '$picking_order_kode'
		// 						GROUP BY picking_order.picking_order_id,
		// 								picking_order_plan.picking_order_plan_id,
		// 								picking_order.depo_id,
		// 								picking_order.depo_detail_id,
		// 								depo_detail.depo_detail_nama,
		// 								ISNULL(rak.rak_nama, ''),
		// 								depo.depo_nama,
		// 								picking_order.picking_order_kode,
		// 								picking_order_plan.picking_order_plan_nourut,
		// 								FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd'),
		// 								picking_order_plan.delivery_order_id,
		// 								delivery_order.delivery_order_kode,
		// 								delivery_order_batch.delivery_order_batch_kode,
		// 								delivery_order_batch.delivery_order_batch_tanggal_kirim,
		// 								picking_list.picking_list_id,
		// 								picking_list.picking_list_kode,
		// 								picking_list.picking_list_tgl_kirim,
		// 								delivery_order.tipe_delivery_order_id,
		// 								ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk'),
		// 								ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU'),
		// 								picking_order.picking_order_keterangan,
		// 								picking_order.picking_order_status,
		// 								picking_order_plan.picking_order_plan_status,
		// 								principal.principle_kode,
		// 								sku.sku_kode,
		// 								sku.sku_nama_produk,
		// 								sku.sku_satuan,
		// 								sku.sku_kemasan,
		// 								picking_order_plan.sku_id,
		// 								picking_order_plan.sku_stock_id,
		// 								FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
		// 								picking_order_plan.sku_stock_qty_ambil,
		// 								CASE
		// 									WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
		// 									ELSE picking_order_plan.karyawan_id
		// 								END,
		// 								CASE
		// 									WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
		// 									ELSE picking_order_plan.karyawan_nama
		// 								END
		// 						ORDER BY principal.principle_kode, sku.sku_kode, FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_Detail_BKB_Aktual_Plan($picking_order_id, $picking_order_plan_id)
	{
		$query = $this->db->query("SELECT
									picking_order.picking_order_id,
									picking_order.picking_order_kode,
									picking_order_aktual_h.picking_order_aktual_h_id,
									picking_order_aktual_h.picking_order_aktual_kode,
									picking_order_aktual_h.picking_order_aktual_tgl,
									picking_order_aktual_d.delivery_order_id,
									picking_order_aktual_h.karyawan_id,
									picking_order_aktual_h.karyawan_nama,
									principal.principle_kode AS principal,
									brand.principle_brand_nama AS brand,
									picking_order_aktual_d.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									picking_order_plan.picking_order_plan_id,
									picking_order_plan.picking_order_plan_nourut,
									picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil_plan,
									picking_order_plan.sku_stock_id AS sku_stock_id_plan,
									FORMAT(picking_order_plan.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date_plan,
									picking_order_aktual_d.picking_order_plan_nourut AS picking_order_aktual_nourut,
									picking_order_aktual_d.sku_stock_qty_ambil AS sku_stock_qty_ambil_aktual,
									picking_order_aktual_d.sku_stock_id,
									FORMAT(picking_order_aktual_d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date_aktual
								FROM picking_order
								LEFT JOIN picking_order_plan
									ON picking_order_plan.picking_order_id = picking_order.picking_order_id
								LEFT JOIN picking_order_aktual_h
									ON picking_order_aktual_h.picking_order_id = picking_order.picking_order_id
								LEFT JOIN picking_order_aktual_d
									ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
									AND picking_order_plan.picking_order_plan_id = picking_order_aktual_d.picking_order_plan_id
								LEFT JOIN SKU
									ON SKU.sku_id = picking_order_aktual_d.sku_id
								LEFT JOIN sku_induk
									ON SKU.sku_induk_id = sku_induk.sku_induk_id
								LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
								LEFT JOIN principle_brand brand
									ON sku.principle_brand_id = brand.principle_brand_id
								WHERE picking_order.picking_order_id = '$picking_order_id' AND picking_order_aktual_d.picking_order_plan_id = '$picking_order_plan_id'
								ORDER BY picking_order_plan.picking_order_plan_nourut, picking_order_aktual_d.picking_order_plan_nourut ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_PickingBKB($picking_order_id)
	{
		// $query = $this->db->query("SELECT
		// 							  picking_order.picking_order_id,
		// 							  picking_order.picking_order_kode,
		// 							  picking_order.picking_order_tanggal,
		// 							  delivery_order_batch.delivery_order_batch_kode,
		// 							  delivery_order_batch.delivery_order_batch_tanggal_kirim,
		// 							  picking_list.picking_list_kode,
		// 							  picking_list.picking_list_tgl_kirim,
		// 							  picking_list.picking_list_tipe,
		// 							  picking_order.picking_order_keterangan,
		// 							  picking_order_plan.picking_order_plan_status,
		// 							  picking_order_plan.picking_order_plan_tipe,
		// 							  principal.principle_kode AS principal,
		// 							  brand.principle_brand_nama AS brand,
		// 							  picking_order_plan.sku_id,
		// 							  sku.sku_kode,
		// 							  sku.sku_nama_produk,
		// 							  sku.sku_satuan,
		// 							  sku.sku_kemasan,
		// 							  picking_order_plan.sku_id,
		// 							  picking_order_plan.karyawan_id,
		// 							  picking_order_plan.karyawan_nama
		// 							FROM picking_order
		// 							LEFT JOIN picking_order_plan
		// 							  ON picking_order.picking_order_id = picking_order_plan.picking_order_id
		// 							LEFT JOIN picking_list
		// 							  ON picking_list.picking_list_id = picking_order.picking_list_id
		// 							LEFT JOIN delivery_order
		// 							  ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
		// 							LEFT JOIN delivery_order_batch
		// 							  ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
		// 							LEFT JOIN SKU
		// 							  ON SKU.sku_id = picking_order_plan.sku_id
		// 							LEFT JOIN sku_induk
		// 							  ON SKU.sku_induk_id = sku_induk.sku_induk_id
		// 							LEFT JOIN principle principal
		// 							  ON sku.principle_id = principal.principle_id
		// 							LEFT JOIN principle_brand brand
		// 							  ON sku.principle_brand_id = brand.principle_brand_id
		// 							WHERE picking_order.picking_order_kode = '$picking_order_kode'
		// 							GROUP BY picking_order.picking_order_id,
		// 							         picking_order.picking_order_kode,
		// 							         picking_order.picking_order_tanggal,
		// 							         delivery_order_batch.delivery_order_batch_kode,
		// 							         delivery_order_batch.delivery_order_batch_tanggal_kirim,
		// 							         picking_list.picking_list_kode,
		// 							         picking_list.picking_list_tgl_kirim,
		// 							         picking_list.picking_list_tipe,
		// 							         picking_order.picking_order_keterangan,
		// 							         picking_order_plan.picking_order_plan_status,
		// 							         picking_order_plan.picking_order_plan_tipe,
		// 							         principal.principle_kode,
		// 							         brand.principle_brand_nama,
		// 							         picking_order_plan.sku_id,
		// 							         sku.sku_kode,
		// 							         sku.sku_nama_produk,
		// 							         sku.sku_satuan,
		// 							         sku.sku_kemasan,
		// 							         picking_order_plan.sku_id,
		// 							         picking_order_plan.karyawan_id,
		// 							         picking_order_plan.karyawan_nama
		// 							ORDER BY sku.sku_kode ASC");

		$query = $this->db->query("SELECT
									picking_order.picking_order_id,
									picking_order.picking_order_kode,
									picking_order_plan.picking_order_plan_nourut,
									picking_order.picking_order_tanggal,
									picking_order_plan.delivery_order_id,
									delivery_order.delivery_order_kode,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order_batch.delivery_order_batch_tanggal_kirim,
									picking_order.picking_order_keterangan,
									picking_order_plan.picking_order_plan_status,
									picking_order_plan.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_alias AS picking_order_plan_tipe,
									picking_order_plan.picking_order_plan_id,
									principal.principle_kode AS principal,
									brand.principle_brand_nama AS brand,
									picking_order_plan.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									picking_order_plan.sku_stock_qty_ambil,
									(SELECT SUM(pk_aktual_d.sku_stock_qty_ambil) FROM picking_order_aktual_d pk_aktual_d WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id) AS total_ambil,
									picking_order_plan.karyawan_id,
									picking_order_plan.karyawan_nama,
									picking_order_plan.sku_stock_id,
									FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date
								FROM picking_order
								LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
								LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
								LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
								LEFT JOIN delivery_order_batch
									ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
								LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
								LEFT JOIN sku_induk
									ON SKU.sku_induk_id = sku_induk.sku_induk_id
								LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
								LEFT JOIN principle_brand brand
									ON sku.principle_brand_id = brand.principle_brand_id
								LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = picking_order_plan.tipe_delivery_order_id
								WHERE picking_order.picking_order_id = '$picking_order_id'
								GROUP BY picking_order.picking_order_id,
										picking_order.picking_order_kode,
										picking_order_plan.picking_order_plan_nourut,
										picking_order.picking_order_tanggal,
										picking_order_plan.delivery_order_id,
										delivery_order.delivery_order_kode,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order_batch.delivery_order_batch_tanggal_kirim,
										picking_order.picking_order_keterangan,
										picking_order_plan.picking_order_plan_status,
										picking_order_plan.picking_order_plan_id,
										picking_order_plan.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										principal.principle_kode,
										brand.principle_brand_nama,
										picking_order_plan.sku_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_satuan,
										sku.sku_kemasan,
										picking_order_plan.sku_stock_qty_ambil,
										picking_order_plan.karyawan_id,
										picking_order_plan.karyawan_nama,
										picking_order_plan.sku_stock_id,
										FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy')
								ORDER BY picking_order_plan.picking_order_plan_nourut ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_PickingBKBBulk($picking_order_id, $karyawan_id, $principle)
	{
		if ($karyawan_id == "") {
			$karyawan_id = "";
		} else if ($karyawan_id == "AD030BE3-7D9E-4A66-B985-D6085F623DA2") {
			$karyawan_id = "";
		} else {
			$karyawan_id = "AND picking_order_plan.karyawan_id = '$karyawan_id' ";
		}
		$depo_id = $this->session->userdata('depo_id');
		$wherePrinciple = $principle === 'all' ? '' : " AND principal.principle_kode = '$principle'";

		$pickingListId = $this->db->select("picking_list_id")->from("picking_order")->where("picking_order_id", $picking_order_id)->get()->row()->picking_list_id;

		$query =  $this->db->query("SELECT
										picking_order.picking_order_id,
										picking_order.picking_order_kode,
										picking_order_plan.picking_order_plan_nourut,
										picking_order.picking_order_tanggal,
										null as delivery_order_id,
										null as delivery_order_kode,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order_batch.delivery_order_batch_tanggal_kirim,
										picking_order.picking_order_keterangan,
										picking_order_plan.picking_order_plan_status,
										delivery_order.tipe_delivery_order_id,
									CASE 
										WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
										WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk')
										ELSE NULL
									END as tipe_delivery_order_nama,
									CASE 
										WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
										WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU')
										ELSE NULL
									END as tipe_delivery_order_alias,
									--ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') as tipe_delivery_order_nama,
									--ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Picking Per SKU') AS tipe_delivery_order_alias,
									picking_order_plan.picking_order_plan_id,
									principal.principle_kode AS principal,
									brand.principle_brand_nama AS brand,
									picking_order_plan.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									picking_order_plan.sku_stock_qty_ambil,
									(SELECT SUM(pk_aktual_d.sku_stock_qty_ambil) 
										FROM picking_order_aktual_d pk_aktual_d 
										WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id) AS total_ambil,
									picking_order_plan.karyawan_id,
									picking_order_plan.karyawan_nama,
									picking_order_plan.sku_stock_id,
									FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama
								FROM picking_order
								LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
								LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
								LEFT JOIN depo_detail
									ON sku_stock.depo_detail_id = depo_detail.depo_detail_id
								LEFT JOIN (select
												do.delivery_order_id,
												do.delivery_order_kode,
												do.delivery_order_batch_id,
												do.delivery_order_reff_id,
												do.tipe_delivery_order_id,
												dod2.sku_id,
												dod2.sku_stock_id
											from delivery_order do
											left join delivery_order_detail2 dod2
												on dod2.delivery_order_id = do.delivery_order_id
											where do.delivery_order_id in (select delivery_order_id from picking_list_detail where picking_list_id = '$pickingListId')) delivery_order
									ON delivery_order.sku_stock_id = picking_order_plan.sku_stock_id
								LEFT JOIN delivery_order_batch
									ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
								LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
								LEFT JOIN sku_induk
									ON SKU.sku_induk_id = sku_induk.sku_induk_id
								LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
								LEFT JOIN principle_brand brand
									ON sku.principle_brand_id = brand.principle_brand_id
								LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
								WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') = 'Bulk'
								AND picking_order.depo_id = '$depo_id' AND picking_order.picking_order_id = '$picking_order_id' " . $karyawan_id . " " . $wherePrinciple . "
								GROUP BY picking_order.picking_order_id,
										picking_order.picking_order_kode,
										picking_order_plan.picking_order_plan_nourut,
										picking_order.picking_order_tanggal,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order_batch.delivery_order_batch_tanggal_kirim,
										picking_order.picking_order_keterangan,
										picking_order_plan.picking_order_plan_status,
										picking_order_plan.picking_order_plan_id,
										delivery_order.tipe_delivery_order_id,
										CASE 
											WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
											WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk')
											ELSE NULL
										END,
										CASE 
											WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
											WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU')
											ELSE NULL
										END,
										--ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk'),
										--ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Picking Per SKU'),
										principal.principle_kode,
										brand.principle_brand_nama,
										picking_order_plan.sku_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_satuan,
										sku.sku_kemasan,
										picking_order_plan.sku_stock_qty_ambil,
										picking_order_plan.karyawan_id,
										picking_order_plan.karyawan_nama,
										picking_order_plan.sku_stock_id,
										FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
										sku_stock.depo_detail_id,
										depo_detail.depo_detail_nama
								ORDER BY principal.principle_kode, brand.principle_brand_nama, depo_detail.depo_detail_nama ASC");

		// $query = $this->db->query("SELECT
		// 								picking_order.picking_order_id,
		// 								picking_order.picking_order_kode,
		// 								picking_order_plan.picking_order_plan_nourut,
		// 								picking_order.picking_order_tanggal,
		// 								picking_order_plan.delivery_order_id,
		// 								delivery_order.delivery_order_kode,
		// 								delivery_order_batch.delivery_order_batch_kode,
		// 								delivery_order_batch.delivery_order_batch_tanggal_kirim,
		// 								picking_order.picking_order_keterangan,
		// 								picking_order_plan.picking_order_plan_status,
		// 								delivery_order.tipe_delivery_order_id,
		// 								ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') as tipe_delivery_order_nama,
		// 								ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Picking Per SKU') AS tipe_delivery_order_alias,
		// 								picking_order_plan.picking_order_plan_id,
		// 								principal.principle_kode AS principal,
		// 								brand.principle_brand_nama AS brand,
		// 								picking_order_plan.sku_id,
		// 								sku.sku_kode,
		// 								sku.sku_nama_produk,
		// 								sku.sku_satuan,
		// 								sku.sku_kemasan,
		// 								picking_order_plan.sku_stock_qty_ambil,
		// 								(SELECT SUM(pk_aktual_d.sku_stock_qty_ambil) FROM picking_order_aktual_d pk_aktual_d WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id) AS total_ambil,
		// 								picking_order_plan.karyawan_id,
		// 								picking_order_plan.karyawan_nama,
		// 								picking_order_plan.sku_stock_id,
		// 								FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
		// 								sku_stock.depo_detail_id,
		// 								depo_detail.depo_detail_nama
		// 							FROM picking_order
		// 							LEFT JOIN picking_order_plan
		// 								ON picking_order.picking_order_id = picking_order_plan.picking_order_id
		// 							LEFT JOIN sku_stock
		// 								ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
		// 							LEFT JOIN depo_detail
		// 								ON sku_stock.depo_detail_id = depo_detail.depo_detail_id
		// 							LEFT JOIN delivery_order
		// 								ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
		// 							LEFT JOIN delivery_order_batch
		// 								ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
		// 							LEFT JOIN SKU
		// 								ON SKU.sku_id = picking_order_plan.sku_id
		// 							LEFT JOIN sku_induk
		// 								ON SKU.sku_induk_id = sku_induk.sku_induk_id
		// 							LEFT JOIN principle principal
		// 								ON sku.principle_id = principal.principle_id
		// 							LEFT JOIN principle_brand brand
		// 								ON sku.principle_brand_id = brand.principle_brand_id
		// 							LEFT JOIN tipe_delivery_order
		// 								ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
		// 							WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') = 'Bulk' AND picking_order.picking_order_id = '$picking_order_id' " . $karyawan_id . "
		// 							GROUP BY picking_order.picking_order_id,
		// 									picking_order.picking_order_kode,
		// 									picking_order_plan.picking_order_plan_nourut,
		// 									picking_order.picking_order_tanggal,
		// 									picking_order_plan.delivery_order_id,
		// 									delivery_order.delivery_order_kode,
		// 									delivery_order_batch.delivery_order_batch_kode,
		// 									delivery_order_batch.delivery_order_batch_tanggal_kirim,
		// 									picking_order.picking_order_keterangan,
		// 									picking_order_plan.picking_order_plan_status,
		// 									picking_order_plan.picking_order_plan_id,
		// 									delivery_order.tipe_delivery_order_id,
		// 									ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk'),
		// 									ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Picking Per SKU'),
		// 									principal.principle_kode,
		// 									brand.principle_brand_nama,
		// 									picking_order_plan.sku_id,
		// 									sku.sku_kode,
		// 									sku.sku_nama_produk,
		// 									sku.sku_satuan,
		// 									sku.sku_kemasan,
		// 									picking_order_plan.sku_stock_qty_ambil,
		// 									picking_order_plan.karyawan_id,
		// 									picking_order_plan.karyawan_nama,
		// 									picking_order_plan.sku_stock_id,
		// 									FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
		// 									sku_stock.depo_detail_id,
		// 									depo_detail.depo_detail_nama
		// 							ORDER BY picking_order_plan.picking_order_plan_nourut ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_PickingBKBFlushOut($picking_order_id, $karyawan_id, $principle)
	{
		if ($karyawan_id == "") {
			$karyawan_id = "";
		} else if ($karyawan_id == "AD030BE3-7D9E-4A66-B985-D6085F623DA2") {
			$karyawan_id = "";
		} else {
			$karyawan_id = "AND picking_order_plan.karyawan_id = '$karyawan_id' ";
		}

		$wherePrinciple = $principle === 'all' ? '' : " AND principal.principle_kode = '$principle'";

		$pickingListId = $this->db->select("picking_list_id")->from("picking_order")->where("picking_order_id", $picking_order_id)->get()->row()->picking_list_id;

		$query = $this->db->query("SELECT
									picking_order.picking_order_id,
									picking_order.picking_order_kode,
									picking_order_plan.picking_order_plan_nourut,
									picking_order.picking_order_tanggal,
									null as delivery_order_id,
									null as delivery_order_kode,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order_batch.delivery_order_batch_tanggal_kirim,
									picking_order.picking_order_keterangan,
									picking_order_plan.picking_order_plan_status,
									delivery_order.tipe_delivery_order_id,
									CASE
									WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
									WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Flush Out')
									ELSE NULL
									END as tipe_delivery_order_nama,
									CASE
									WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
									WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU')
									ELSE NULL
									END as tipe_delivery_order_alias,
									--ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Flush Out') as tipe_delivery_order_nama,
									--ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Picking Per SKU') AS tipe_delivery_order_alias,
									picking_order_plan.picking_order_plan_id,
									principal.principle_kode AS principal,
									brand.principle_brand_nama AS brand,
									picking_order_plan.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									picking_order_plan.sku_stock_qty_ambil,
									(SELECT SUM(pk_aktual_d.sku_stock_qty_ambil)
									FROM picking_order_aktual_d pk_aktual_d
									WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id) AS total_ambil,
									picking_order_plan.karyawan_id,
									picking_order_plan.karyawan_nama,
									picking_order_plan.sku_stock_id,
									FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN depo_detail
									ON sku_stock.depo_detail_id = depo_detail.depo_detail_id
									LEFT JOIN (select
									do.delivery_order_id,
									do.delivery_order_kode,
									do.delivery_order_batch_id,
									do.delivery_order_reff_id,
									do.tipe_delivery_order_id,
									dod2.sku_id,
									dod2.sku_stock_id
									from delivery_order do
									left join delivery_order_detail2 dod2
									on dod2.delivery_order_id = do.delivery_order_id
									where do.delivery_order_id in (select delivery_order_id from picking_list_detail where picking_list_id = '$pickingListId')) delivery_order
									ON delivery_order.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN delivery_order_batch
									ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN sku_induk
									ON SKU.sku_induk_id = sku_induk.sku_induk_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN principle_brand brand
									ON sku.principle_brand_id = brand.principle_brand_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Flush Out') = 'Flush Out' AND picking_order.picking_order_id = '$picking_order_id' " . $karyawan_id . " " . $wherePrinciple . "
									GROUP BY picking_order.picking_order_id,
									picking_order.picking_order_kode,
									picking_order_plan.picking_order_plan_nourut,
									picking_order.picking_order_tanggal,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order_batch.delivery_order_batch_tanggal_kirim,
									picking_order.picking_order_keterangan,
									picking_order_plan.picking_order_plan_status,
									picking_order_plan.picking_order_plan_id,
									delivery_order.tipe_delivery_order_id,
									CASE
									WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
									WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Flush Out')
									ELSE NULL
									END,
									CASE
									WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
									WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU')
									ELSE NULL
									END,
									--ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Flush Out'),
									--ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Picking Per SKU'),
									principal.principle_kode,
									brand.principle_brand_nama,
									picking_order_plan.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									picking_order_plan.sku_stock_qty_ambil,
									picking_order_plan.karyawan_id,
									picking_order_plan.karyawan_nama,
									picking_order_plan.sku_stock_id,
									FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama
									ORDER BY picking_order_plan.picking_order_plan_nourut ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_PickingBKBStandar($picking_order_id, $delivery_order_kode, $principle)
	{
		if ($delivery_order_kode == "") {
			$delivery_order_kode = "";
		} else {
			$delivery_order_kode = "AND delivery_order.delivery_order_kode = '$delivery_order_kode' ";
		}

		$wherePrinciple = $principle === 'all' ? '' : " AND principal.principle_kode = '$principle'";

		$query = $this->db->query("SELECT
									picking_order.picking_order_id,
									picking_order.picking_order_kode,
									picking_order_plan.picking_order_plan_nourut,
									picking_order.picking_order_tanggal,
									picking_order_plan.delivery_order_id,
									delivery_order.delivery_order_kode,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order_batch.delivery_order_batch_tanggal_kirim,
									picking_list.picking_list_kode,
									picking_list.picking_list_tgl_kirim,
									picking_order.picking_order_keterangan,
									picking_order_plan.picking_order_plan_status,
									delivery_order.tipe_delivery_order_id,
									ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Bulk') AS tipe_delivery_order_alias,
									picking_order_plan.picking_order_plan_id,
									principal.principle_kode AS principal,
									brand.principle_brand_nama AS brand,
									picking_order_plan.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									picking_order_plan.sku_stock_qty_ambil,
									(SELECT SUM(pk_aktual_d.sku_stock_qty_ambil) FROM picking_order_aktual_d pk_aktual_d WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id) AS total_ambil,
									picking_order_plan.karyawan_id,
									picking_order_plan.karyawan_nama,
									picking_order_plan.sku_stock_id,
									FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama
								FROM picking_order
								LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
								LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
								LEFT JOIN depo_detail
									ON sku_stock.depo_detail_id = depo_detail.depo_detail_id
								LEFT JOIN picking_list
									ON picking_list.picking_list_id = picking_order.picking_list_id
								LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
								LEFT JOIN delivery_order_batch
									ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
								LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
								LEFT JOIN sku_induk
									ON SKU.sku_induk_id = sku_induk.sku_induk_id
								LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
								LEFT JOIN principle_brand brand
									ON sku.principle_brand_id = brand.principle_brand_id
								LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
								WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') = 'Standar' AND picking_order.picking_order_id = '$picking_order_id' " . $delivery_order_kode . " " . $wherePrinciple . "
								GROUP BY picking_order.picking_order_id,
										picking_order.picking_order_kode,
										picking_order_plan.picking_order_plan_nourut,
										picking_order.picking_order_tanggal,
										picking_order_plan.delivery_order_id,
										delivery_order.delivery_order_kode,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order_batch.delivery_order_batch_tanggal_kirim,
										picking_list.picking_list_kode,
										picking_list.picking_list_tgl_kirim,
										picking_order.picking_order_keterangan,
										picking_order_plan.picking_order_plan_status,
										picking_order_plan.picking_order_plan_id,
										delivery_order.tipe_delivery_order_id,
										ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Picking Per SKU'),
										principal.principle_kode,
										brand.principle_brand_nama,
										picking_order_plan.sku_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_satuan,
										sku.sku_kemasan,
										picking_order_plan.sku_stock_qty_ambil,
										picking_order_plan.karyawan_id,
										picking_order_plan.karyawan_nama,
										picking_order_plan.sku_stock_id,
										FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
										sku_stock.depo_detail_id,
										depo_detail.depo_detail_nama
								ORDER BY principal.principle_kode, depo_detail.depo_detail_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_PickingBKBKirimUlang($picking_order_id, $karyawan_id, $principle)
	{
		if ($karyawan_id == "") {
			$karyawan_id = "";
		} else if ($karyawan_id == "AD030BE3-7D9E-4A66-B985-D6085F623DA2") {
			$karyawan_id = "";
		} else {
			$karyawan_id = "AND picking_order_plan.karyawan_id = '$karyawan_id' ";
		}

		$wherePrinciple = $principle === 'all' ? '' : " AND principal.principle_kode = '$principle'";

		$pickingListId = $this->db->select("picking_list_id")->from("picking_order")->where("picking_order_id", $picking_order_id)->get()->row()->picking_list_id;

		$query = $this->db->query("SELECT
																	picking_order.picking_order_id,
																	picking_order.picking_order_kode,
																	picking_order_plan.picking_order_plan_nourut,
																	picking_order.picking_order_tanggal,
																	null as delivery_order_id,
																	null as delivery_order_kode,
																	--picking_order_plan.delivery_order_id,
																	--delivery_order.delivery_order_kode,
																	delivery_order_batch.delivery_order_batch_kode,
																	delivery_order_batch.delivery_order_batch_tanggal_kirim,
																	picking_order.picking_order_keterangan,
																	picking_order_plan.picking_order_plan_status,
																	delivery_order.tipe_delivery_order_id,
																	CASE 
																		WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
																		WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk')
																		ELSE NULL
																	END as tipe_delivery_order_nama,
																	CASE 
																		WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
																		WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU')
																		ELSE NULL
																	END as tipe_delivery_order_alias,
																	--ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') as tipe_delivery_order_nama,
																	--ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Picking Per SKU') AS tipe_delivery_order_alias,
																	picking_order_plan.picking_order_plan_id,
																	principal.principle_kode AS principal,
																	brand.principle_brand_nama AS brand,
																	picking_order_plan.sku_id,
																	sku.sku_kode,
																	sku.sku_nama_produk,
																	sku.sku_satuan,
																	sku.sku_kemasan,
																	picking_order_plan.sku_stock_qty_ambil,
																	(SELECT SUM(pk_aktual_d.sku_stock_qty_ambil)
																		FROM picking_order_aktual_d pk_aktual_d 
																		WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id) AS total_ambil,
																	picking_order_plan.karyawan_id,
																	picking_order_plan.karyawan_nama,
																	picking_order_plan.sku_stock_id,
																	FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
																	depo_detail.depo_detail_id as depo_detail_id,
																	depo_detail.depo_detail_nama as depo_detail_nama
																FROM picking_order
																LEFT JOIN picking_order_plan
																	ON picking_order.picking_order_id = picking_order_plan.picking_order_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
																LEFT JOIN depo_detail ON sku_stock.depo_detail_id = depo_detail.depo_detail_id
																LEFT JOIN (select
																				do.delivery_order_id,
																				do.delivery_order_kode,
																				do.delivery_order_batch_id,
																				do.delivery_order_reff_id,
																				do.tipe_delivery_order_id,
																				dod2.sku_id,
																				dod2.sku_stock_id
																			from delivery_order do
																			left join delivery_order_detail2 dod2
																				on dod2.delivery_order_id = do.delivery_order_id
																			where do.delivery_order_id in (select delivery_order_id from picking_list_detail where picking_list_id = '$pickingListId')) delivery_order
																	ON delivery_order.sku_stock_id = picking_order_plan.sku_stock_id
																LEFT JOIN delivery_order_batch
																	ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_order_plan.sku_id
																LEFT JOIN sku_induk
																	ON SKU.sku_induk_id = sku_induk.sku_induk_id
																LEFT JOIN principle principal
																	ON sku.principle_id = principal.principle_id
																LEFT JOIN principle_brand brand
																	ON sku.principle_brand_id = brand.principle_brand_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
																WHERE tipe_delivery_order.tipe_delivery_order_nama = 'Reschedule' AND picking_order.picking_order_id = '$picking_order_id' " . $karyawan_id . " " . $wherePrinciple . "
																GROUP BY picking_order.picking_order_id,
																		picking_order.picking_order_kode,
																		picking_order_plan.picking_order_plan_nourut,
																		picking_order.picking_order_tanggal,
																		--picking_order_plan.delivery_order_id,
																		--delivery_order.delivery_order_kode,
																		delivery_order_batch.delivery_order_batch_kode,
																		delivery_order_batch.delivery_order_batch_tanggal_kirim,
																		picking_order.picking_order_keterangan,
																		picking_order_plan.picking_order_plan_status,
																		picking_order_plan.picking_order_plan_id,
																		delivery_order.tipe_delivery_order_id,
																		CASE 
																			WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
																			WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk')
																			ELSE NULL
																		END,
																		CASE 
																			WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
																			WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Picking Per SKU')
																			ELSE NULL
																		END,
																		--ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk'),
																		--ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Picking Per SKU'),
																		principal.principle_kode,
																		brand.principle_brand_nama,
																		picking_order_plan.sku_id,
																		sku.sku_kode,
																		sku.sku_nama_produk,
																		sku.sku_satuan,
																		sku.sku_kemasan,
																		picking_order_plan.sku_stock_qty_ambil,
																		picking_order_plan.karyawan_id,
																		picking_order_plan.karyawan_nama,
																		picking_order_plan.sku_stock_id,
																		FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
																		depo_detail.depo_detail_id,
																		depo_detail.depo_detail_nama
																ORDER BY principal.principle_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_PickingBKBCanvas($picking_order_id, $karyawan_id, $principle)
	{
		if ($karyawan_id == "") {
			$karyawan_id = "";
		} else if ($karyawan_id == "AD030BE3-7D9E-4A66-B985-D6085F623DA2") {
			$karyawan_id = "";
		} else {
			$karyawan_id = "AND picking_order_plan.karyawan_id = '$karyawan_id' ";
		}

		$wherePrinciple = $principle === 'all' ? '' : " AND principal.principle_kode = '$principle'";

		$pickingListId = $this->db->select("picking_list_id")->from("picking_order")->where("picking_order_id", $picking_order_id)->get()->row()->picking_list_id;

		$query =  $this->db->query("SELECT
																	picking_order.picking_order_id,
																	picking_order.picking_order_kode,
																	picking_order_plan.picking_order_plan_nourut,
																	picking_order.picking_order_tanggal,
																	null as delivery_order_id,
																	null as delivery_order_kode,
																	delivery_order_batch.delivery_order_batch_kode,
																	delivery_order_batch.delivery_order_batch_tanggal_kirim,
																	picking_order.picking_order_keterangan,
																	picking_order_plan.picking_order_plan_status,
																	delivery_order.tipe_delivery_order_id,
																	CASE 
																		WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
																		WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Canvas')
																		ELSE NULL
																	END as tipe_delivery_order_nama,
																	CASE 
																		WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
																		WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Canvas')
																		ELSE NULL
																	END as tipe_delivery_order_alias,
																	--ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Canvas') as tipe_delivery_order_nama,
																	--ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Canvas') AS tipe_delivery_order_alias,
																	picking_order_plan.picking_order_plan_id,
																	principal.principle_kode AS principal,
																	brand.principle_brand_nama AS brand,
																	picking_order_plan.sku_id,
																	sku.sku_kode,
																	sku.sku_nama_produk,
																	sku.sku_satuan,
																	sku.sku_kemasan,
																	picking_order_plan.sku_stock_qty_ambil,
																	(SELECT SUM(pk_aktual_d.sku_stock_qty_ambil) 
																		FROM picking_order_aktual_d pk_aktual_d 
																		WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id) AS total_ambil,
																	picking_order_plan.karyawan_id,
																	picking_order_plan.karyawan_nama,
																	picking_order_plan.sku_stock_id,
																	FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
																	sku_stock.depo_detail_id,
																	depo_detail.depo_detail_nama
																FROM picking_order
																LEFT JOIN picking_order_plan
																	ON picking_order.picking_order_id = picking_order_plan.picking_order_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
																LEFT JOIN depo_detail
																	ON sku_stock.depo_detail_id = depo_detail.depo_detail_id
																LEFT JOIN (select
																				do.delivery_order_id,
																				do.delivery_order_kode,
																				do.delivery_order_batch_id,
																				do.delivery_order_reff_id,
																				do.tipe_delivery_order_id,
																				dod2.sku_id,
																				dod2.sku_stock_id
																			from delivery_order do
																			left join delivery_order_detail2 dod2
																				on dod2.delivery_order_id = do.delivery_order_id
																			where do.delivery_order_id in (select delivery_order_id from picking_list_detail where picking_list_id = '$pickingListId')) delivery_order
																	ON delivery_order.sku_stock_id = picking_order_plan.sku_stock_id
																LEFT JOIN delivery_order_batch
																	ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_order_plan.sku_id
																LEFT JOIN sku_induk
																	ON SKU.sku_induk_id = sku_induk.sku_induk_id
																LEFT JOIN principle principal
																	ON sku.principle_id = principal.principle_id
																LEFT JOIN principle_brand brand
																	ON sku.principle_brand_id = brand.principle_brand_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
																WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Canvas') = 'Canvas' AND picking_order.picking_order_id = '$picking_order_id' " . $karyawan_id . " " . $wherePrinciple . "
																GROUP BY picking_order.picking_order_id,
																		picking_order.picking_order_kode,
																		picking_order_plan.picking_order_plan_nourut,
																		picking_order.picking_order_tanggal,
																		delivery_order_batch.delivery_order_batch_kode,
																		delivery_order_batch.delivery_order_batch_tanggal_kirim,
																		picking_order.picking_order_keterangan,
																		picking_order_plan.picking_order_plan_status,
																		picking_order_plan.picking_order_plan_id,
																		delivery_order.tipe_delivery_order_id,
																		CASE 
																			WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_nama
																			WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Canvas')
																			ELSE NULL
																		END,
																		CASE 
																			WHEN delivery_order.delivery_order_reff_id is not null THEN tipe_delivery_order.tipe_delivery_order_alias
																			WHEN delivery_order.delivery_order_reff_id is null THEN ISNULL(tipe_delivery_order.tipe_delivery_order_alias, 'Canvas')
																			ELSE NULL
																		END,
																		--ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Canvas'),
																		--ISNULL(tipe_delivery_order.tipe_delivery_order_alias,'Canvas'),
																		principal.principle_kode,
																		brand.principle_brand_nama,
																		picking_order_plan.sku_id,
																		sku.sku_kode,
																		sku.sku_nama_produk,
																		sku.sku_satuan,
																		sku.sku_kemasan,
																		picking_order_plan.sku_stock_qty_ambil,
																		picking_order_plan.karyawan_id,
																		picking_order_plan.karyawan_nama,
																		picking_order_plan.sku_stock_id,
																		FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
																		sku_stock.depo_detail_id,
																		depo_detail.depo_detail_nama
																ORDER BY principal.principle_kode, depo_detail.depo_detail_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function Get_Checker_BKB($picking_order_id)
	{
		// $query = $this->db->query("SELECT
		// 								k.karyawan_id,
		// 								k.karyawan_nama
		// 							FROM picking_order_plan pop
		// 							LEFT JOIN karyawan k 
		// 								ON pop.karyawan_id = k.karyawan_id
		// 							WHERE pop.picking_order_id = '$picking_order_id'
		// 								OR k.karyawan_id = 'AD030BE3-7D9E-4A66-B985-D6085F623DA2'
		// 							GROUP BY pop.karyawan_id,
		// 									 pop.karyawan_nama
		// 							ORDER BY pop.karyawan_nama ASC");


		if ($this->session->userdata('karyawan_id') == "AD030BE3-7D9E-4A66-B985-D6085F623DA2") {
			$result = $this->db->select("karyawan_id, karyawan_nama")->from("karyawan")->where("karyawan_id", "AD030BE3-7D9E-4A66-B985-D6085F623DA2")->get()->result_array();
		} else {
			$result = $this->db->select("karyawan_id, karyawan_nama")->from("karyawan")->where("karyawan_id", $this->session->userdata("karyawan_id"))->get()->result_array();
		}
		// $this->db	->select("*")
		// 			->from("karyawan")
		// 			->where("karyawan_level_id", '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41');
		// $query = $this->db->get();	

		// if ($query->num_rows() == 0) {
		// 	$query = 0;
		// } else {
		// 	$query = $query->result_array();
		// }

		return $result;
	}

	public function Get_Checker_BKB_By_Kode($picking_order_kode)
	{
		// $query = $this->db->query("SELECT
		// 								karyawan_id,
		// 								karyawan_nama
		// 							FROM picking_order
		// 							LEFT JOIN picking_order_plan
		// 							   ON picking_order_plan.picking_order_id = picking_order.picking_order_id
		// 							WHERE picking_order_kode = '$picking_order_kode'
		// 							GROUP BY karyawan_id,
		// 									 karyawan_nama
		// 							ORDER BY karyawan_nama ASC");
		$this->db->select("karyawan_id,
											karyawan_nama")
			->from("karyawan")
			->where("karyawan_level_id", '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41');
		$query = $this->db->get()->result_array();

		// if ($query->num_rows() == 0) {
		// 	$query = 0;
		// } else {
		// 	$query = $query->result_array();
		// }

		return $query;
	}

	public function Get_Checker_BKB_Principal($principal, $depo_id)
	{
		$query = $this->db->query("SELECT DISTINCT
										karyawan_principle.karyawan_id,
										karyawan.client_wms_id,
										karyawan.unit_mandiri_id,
										karyawan.depo_id,
										karyawan.karyawan_nama,
										karyawan.karyawan_telepon,
										karyawan.karyawan_email,
										karyawan.karyawan_tanggal_lahir,
										karyawan.karyawan_divisi_id,
										karyawan.karyawan_level_id,
										karyawan.karyawan_supervisor_id,
										karyawan.karyawan_is_client_wms,
										karyawan.karyawan_foto,
										karyawan.karyawan_digital_signature,
										karyawan.karyawan_is_deleted,
										karyawan.karyawan_is_aktif
									FROM karyawan_principle
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = karyawan_principle.karyawan_id
									LEFT JOIN principle
									ON principle.principle_id = karyawan_principle.principle_id
									WHERE karyawan.depo_id = '$depo_id' AND karyawan.karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
									ORDER BY karyawan.karyawan_nama ASC");
		// $this->db	->select("*")
		// 			->from("karyawan")
		// 			->where("karyawan_level_id", '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41');
		// $query = $this->db->get();	

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_Checker_Principal($depo_id, $principal)
	{
		$query = $this->db->query("SELECT TOP 1
										karyawan.*,
										karyawan_principle.principle_id,
										principle.principle_kode
									FROM karyawan
									LEFT JOIN karyawan_principle
										ON karyawan.karyawan_id = karyawan_principle.karyawan_id
									LEFT JOIN principle
										ON karyawan_principle.principle_id = principle.principle_id
									WHERE principle.principle_kode = '$principal' AND karyawan.depo_id = '$depo_id' AND karyawan.karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
									ORDER BY karyawan.karyawan_nama ASC ");
		// $this->db	->select("*")
		// 			->from("karyawan")
		// 			->where("karyawan_level_id", '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41');
		// $query = $this->db->get();	

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_TipeDO()
	{
		$this->db->select("*")
			->from("tipe_delivery_order");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_SKU_Expired_Date($sku_id)
	{
		$this->db->select("sku_stock_id,sku_id,FORMAT(sku_stock_expired_date,'dd-MM-yyyy') AS sku_stock_expired_date")
			->from("sku_stock")
			->where("sku_id", $sku_id)
			->order_by("FORMAT(sku_stock_expired_date,'dd-MM-yyyy')");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_Delivery_Order_Picking_Order($picking_order_id, $karyawan_id)
	{
		if ($picking_order_id == "") {
			$picking_order_id = "";
		} else {
			$picking_order_id = "AND picking_order_plan.picking_order_id = '$picking_order_id' ";
		}
		if ($karyawan_id == "") {
			$karyawan_id = "";
		} else if ($karyawan_id = "AD030BE3-7D9E-4A66-B985-D6085F623DA2") {
			$karyawan_id = "";
		} else {
			$karyawan_id = "AND picking_order_plan.karyawan_id = '$karyawan_id' ";
		}

		$query = $this->db->query("SELECT
										delivery_order.delivery_order_kode
									FROM picking_order_plan
									LEFT JOIN delivery_order
									ON picking_order_plan.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE tipe_delivery_order.tipe_delivery_order_nama = 'Standar' " . $picking_order_id . " " . $karyawan_id . "
									GROUP BY delivery_order.delivery_order_kode
									ORDER BY delivery_order.delivery_order_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Channel_Active()
	{
		$this->db->select("	channel_id, channel_nama, channel_jenis, channel_grup, channel_flag_ecomm, channel_is_sppbr, 
								channel_is_aktif, channel_is_deleted")
			->from("channel")
			->where("channel_is_aktif", 1);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Channel_By_ID($channel_id)
	{
		$this->db->select("	channel_id, channel_nama, channel_jenis, channel_grup, channel_flag_ecomm, channel_is_sppbr, 
								channel_is_aktif, channel_is_deleted")
			->from("channel")
			->where("channel_id", $channel_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Check_Qty_Ambil($sku_stock_id)
	{

		return $this->db->select("sku_stock_alokasi as sisa_stock")->from("sku_stock")->where("sku_stock_id", $sku_stock_id)->get()->row()->sisa_stock;

		// $query = $this->db->query("SELECT sku_stock_saldo_alokasi AS sisa_stock FROM sku_stock WHERE sku_stock_id = '$sku_stock_id' ");

		// if ($query->num_rows() == 0) {
		// 	$query = 0;
		// } else {
		// 	$query = $query->row(0)->sisa_stock;
		// }

		// // return $this->db->last_query();
		// return $query;
	}

	public function Check_Qty_Plan($picking_order_plan_id)
	{
		$query = $this->db->query("SELECT
										picking_order_plan.picking_order_plan_id,
										picking_order_plan.sku_id,
										picking_order_plan.sku_stock_id,
										ISNULL(picking_order_plan.sku_stock_qty_ambil, 0) - ISNULL(SUM(picking_order_aktual_d.sku_stock_qty_ambil), 0) AS sisa_stock
									FROM picking_order_plan
									LEFT JOIN picking_order_aktual_d
										ON picking_order_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id
									WHERE picking_order_plan.picking_order_plan_id = '$picking_order_plan_id'
									GROUP BY picking_order_plan.picking_order_plan_id,
											picking_order_plan.sku_id,
											picking_order_plan.sku_stock_id,
											ISNULL(picking_order_plan.sku_stock_qty_ambil, 0)");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->sisa_stock;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Check_PengeluaranBarang_Duplicate($picking_order_kode)
	{
		$this->db->select("picking_order_kode")
			->from("picking_order")
			->where("picking_order_kode", $picking_order_kode);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = 1;
		}

		return $query;
	}

	public function Check_No_PB_Duplicate($no_pb)
	{
		$this->db->select("picking_list_id")
			->from("picking_order")
			->where("picking_list_id", $no_pb);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = 1;
		}

		return $query;
	}

	public function Check_BKB_Duplicate($picking_order_aktual_kode)
	{
		$this->db->select("picking_order_aktual_kode")
			->from("picking_order_aktual_h")
			->where("picking_order_aktual_kode", $picking_order_aktual_kode);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = 1;
		}

		return $query;
	}

	public function Get_NoPB()
	{
		$this->db->select("picking_list.picking_list_id,picking_list.picking_list_kode, karyawan.karyawan_nama")
			->from("picking_list")
			->join("delivery_order_batch as do_batch", "do_batch.delivery_order_batch_id = picking_list.delivery_order_batch_id", "left")
			->join("karyawan", "karyawan.karyawan_id = do_batch.karyawan_id", "left")
			->where("picking_list_status", "Open")
			->where("picking_list.depo_id", $this->session->userdata('depo_id'))
			->order_by("picking_list_kode");
		$query = $this->db->get();
		// var_dump($this->db->last_query());
		// die;

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_NewNoPPB($prefix, $unit)
	{
		$No_PPB = $unit . '/' . $prefix . '/' . date('Y') . date('m') . date('d');

		$query = $this->db->query("SELECT * FROM picking_order WHERE picking_order_kode LIKE '$No_PPB%' ");
		$no = $query->num_rows() + 1;
		if ($no > 0 && $no < 10) {
			$No_PPB = $No_PPB . "00" . $no;
		} else if ($no >= 10 && $no < 100) {
			$No_PPB = $No_PPB . "0" . $no;
		} else if ($no >= 100) {
			$No_PPB = $No_PPB . $no;
		}

		return $No_PPB;
	}

	public function Get_NewNoBKB($prefix, $unit)
	{
		$No_BKB = $unit . '/' . $prefix . '/' . date('Y') . date('m') . date('d');

		$query = $this->db->query("SELECT * FROM picking_order_aktual_h WHERE picking_order_aktual_kode LIKE '$No_BKB%' ");
		$no = $query->num_rows() + 1;
		if ($no > 0 && $no < 10) {
			$No_BKB = $No_BKB . "00" . $no;
		} else if ($no >= 10 && $no < 100) {
			$No_BKB = $No_BKB . "0" . $no;
		} else if ($no >= 100) {
			$No_BKB = $No_BKB . $no;
		}

		return $No_BKB;
	}

	public function Get_DaftarBKB($picking_order_id, $tipe_bkb)
	{
		// $tipe = $tipe_bkb != 'Reschedule' ? 'Bulk' : 'Reschedule';
		$tipeBkbAlias = $tipe_bkb == 'Bulk' ? 'Picking Per SKU' : ($tipe_bkb == 'Reschedule' ? 'DO Reschedule' : '');
		$query = $this->db->query("SELECT
										picking_order_aktual_h.picking_order_aktual_h_id,
										picking_order_aktual_h.picking_order_id,
										picking_order_aktual_h.picking_order_aktual_kode,
										FORMAT(picking_order_aktual_h.picking_order_aktual_tgl, 'dd-MM-yyyy') AS picking_order_aktual_tgl,
										picking_order_aktual_h.karyawan_id,
										picking_order_aktual_h.karyawan_nama,
										delivery_order.tipe_delivery_order_id,
										ISNULL(tipe_delivery_order.tipe_delivery_order_nama, '$tipe_bkb') as tipe_delivery_order_nama,
										ISNULL(tipe_delivery_order.tipe_delivery_order_alias, '$tipeBkbAlias') AS tipe_bkb
									FROM picking_order_aktual_h
									LEFT JOIN picking_order_aktual_d
										ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
									LEFT JOIN delivery_order
										ON delivery_order.delivery_order_id = picking_order_aktual_d.delivery_order_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE picking_order_aktual_h.picking_order_id = '$picking_order_id'
										AND ISNULL(tipe_delivery_order.tipe_delivery_order_nama, '$tipe_bkb') = '$tipe_bkb'
									GROUP BY picking_order_aktual_h.picking_order_aktual_h_id,
											picking_order_aktual_h.picking_order_id,
											picking_order_aktual_h.picking_order_aktual_kode,
											FORMAT(picking_order_aktual_h.picking_order_aktual_tgl, 'dd-MM-yyyy'),
											picking_order_aktual_h.karyawan_id,
											picking_order_aktual_h.karyawan_nama,
											delivery_order.tipe_delivery_order_id,
											ISNULL(tipe_delivery_order.tipe_delivery_order_nama, '$tipe_bkb'),
											ISNULL(tipe_delivery_order.tipe_delivery_order_alias, '$tipeBkbAlias')
									ORDER BY picking_order_aktual_h.picking_order_aktual_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_HeaderBKBAktual($picking_order_aktual_h_id)
	{
		$query = $this->db->query("SELECT
									po_aktual.picking_order_aktual_h_id,
									po_aktual.picking_order_aktual_kode,
									FORMAT(po_aktual.picking_order_aktual_tgl, 'dd-MM-yyyy') AS picking_order_aktual_tgl,
									po.picking_order_id,
									po.picking_order_kode,
									FORMAT(po.picking_order_tanggal, 'dd-MM-yyyy') AS picking_order_tanggal,
									fdjr.delivery_order_batch_id,
									fdjr.delivery_order_batch_kode,
									FORMAT(fdjr.delivery_order_batch_tanggal, 'dd-MM-yyyy') AS delivery_order_batch_tanggal,
									FORMAT(fdjr.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim,
									po_aktual.karyawan_id,
									po_aktual.karyawan_nama AS checker_nama
									FROM picking_order_aktual_h po_aktual
									LEFT JOIN picking_order po
									ON po.picking_order_id = po_aktual.picking_order_id
									LEFT JOIN picking_list pl
									ON po.picking_list_id = pl.picking_list_id
									LEFT JOIN delivery_order_batch fdjr
									ON pl.delivery_order_batch_id = fdjr.delivery_order_batch_id
									LEFT JOIN karyawan checker
									ON checker.karyawan_id = po_aktual.karyawan_id
									WHERE po_aktual.picking_order_aktual_h_id = '$picking_order_aktual_h_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_DetailBKBAktual($picking_order_aktual_h_id)
	{
		$query = $this->db->query("SELECT
									picking_order_aktual_h.picking_order_aktual_h_id,
									picking_order_aktual_h.picking_order_aktual_kode,
									picking_order_aktual_h.picking_order_aktual_tgl,
									picking_order_aktual_h.picking_order_id,
									picking_order.picking_order_kode,
									picking_order_aktual_d.delivery_order_id,
									delivery_order.delivery_order_kode,
									picking_order_aktual_h.karyawan_id,
									picking_order_aktual_h.karyawan_nama,
									principal.principle_kode AS principal,
									brand.principle_brand_nama AS brand,
									picking_order_aktual_d.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									picking_order_aktual_d.picking_order_plan_id,
									picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil_plan,
									(SELECT SUM(pk_aktual_d.sku_stock_qty_ambil) FROM picking_order_aktual_d pk_aktual_d WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id) AS total_ambil,
									picking_order_aktual_d.sku_stock_qty_ambil,
									picking_order_aktual_d.sku_stock_id,
									FORMAT(picking_order_plan.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date_plan,
									FORMAT(picking_order_aktual_d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date
								FROM picking_order_aktual_h
								LEFT JOIN picking_order_aktual_d
									ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
								LEFT JOIN picking_order
									ON picking_order_aktual_h.picking_order_id = picking_order.picking_order_id
								LEFT JOIN picking_order_plan
									ON picking_order_plan.picking_order_plan_id = picking_order_aktual_d.picking_order_plan_id
									AND picking_order_plan.picking_order_id = picking_order.picking_order_id
								LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_aktual_d.delivery_order_id
								LEFT JOIN SKU
									ON SKU.sku_id = picking_order_aktual_d.sku_id
								LEFT JOIN sku_induk
									ON SKU.sku_induk_id = sku_induk.sku_induk_id
								LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
								LEFT JOIN principle_brand brand
									ON sku.principle_brand_id = brand.principle_brand_id
								WHERE picking_order_aktual_h.picking_order_aktual_h_id = '$picking_order_aktual_h_id'
								ORDER BY principal.principle_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_DetailBKB($picking_order_aktual_h_id)
	{
		$query = $this->db->query("SELECT
									picking_order_aktual_h.picking_order_aktual_h_id,
									picking_order_aktual_h.picking_order_aktual_kode,
									picking_order_aktual_h.picking_order_aktual_tgl,
									picking_order_aktual_h.picking_order_id,
									picking_order.picking_order_kode,
									picking_order_aktual_d.delivery_order_id,
									delivery_order.delivery_order_kode,
									picking_order_aktual_h.karyawan_id,
									picking_order_aktual_h.karyawan_nama,
									principal.principle_kode AS principal,
									brand.principle_brand_nama AS brand,
									picking_order_aktual_d.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									picking_order_aktual_d.picking_order_plan_id,
									picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil_plan,
									(SELECT SUM(pk_aktual_d.sku_stock_qty_ambil) FROM picking_order_aktual_d pk_aktual_d WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id) AS total_ambil,
									picking_order_aktual_d.sku_stock_qty_ambil,
									picking_order_aktual_d.sku_stock_id,
									FORMAT(picking_order_plan.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date_plan,
									FORMAT(picking_order_aktual_d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date
								FROM picking_order_aktual_h
								LEFT JOIN picking_order_aktual_d
									ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
								LEFT JOIN picking_order
									ON picking_order_aktual_h.picking_order_id = picking_order.picking_order_id
								LEFT JOIN picking_order_plan
									ON picking_order_plan.picking_order_plan_id = picking_order_aktual_d.picking_order_plan_id
									AND picking_order_plan.picking_order_id = picking_order.picking_order_id
								LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_aktual_d.delivery_order_id
								LEFT JOIN SKU
									ON SKU.sku_id = picking_order_aktual_d.sku_id
								LEFT JOIN sku_induk
									ON SKU.sku_induk_id = sku_induk.sku_induk_id
								LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
								LEFT JOIN principle_brand brand
									ON sku.principle_brand_id = brand.principle_brand_id
								WHERE picking_order_aktual_h.picking_order_aktual_h_id = '$picking_order_aktual_h_id'
								ORDER BY principal.principle_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_NewPengeluaranBarang($picking_list_id)
	{
		if ($picking_list_id == "") {
			$picking_list_id = "picking.picking_list_id IS NULL";
		} else {
			$picking_list_id = " picking.picking_list_id = '$picking_list_id' ";
		}

		$query = $this->db->query("SELECT
									picking.picking_list_id,
									picking.unit_mandiri_id,
									picking.depo_id,
									picking.depo_detail_id,
									depo_detail.depo_detail_nama,
									picking.area_id,
									picking.delivery_order_batch_id,
									picking.picking_list_kode,
									do_batch.delivery_order_batch_kode,
									picking.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_alias AS picking_list_tipe,
									tipe_delivery_order.tipe_delivery_order_nama,
									picking.picking_list_tgl_kirim,
									picking.picking_list_create_who,
									picking.picking_list_create_tgl,
									picking.picking_list_keterangan,
									picking.picking_list_status,
									picking.picking_list_tgl_update,
									karyawan.karyawan_id,
									karyawan.karyawan_nama
								FROM picking_list picking
								LEFT JOIN picking_list_detail picking_detail
									ON picking.picking_list_id = picking_detail.picking_list_id
								LEFT JOIN picking_list_detail_2 picking_detail2
									ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
								LEFT JOIN delivery_order_batch do_batch
									ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
								LEFT JOIN delivery_order do
									ON do.delivery_order_id = picking_detail.delivery_order_id
								LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
								LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = picking.depo_detail_id
								LEFT JOIN karyawan 
									ON do_batch.karyawan_id = karyawan.karyawan_id
								WHERE " . $picking_list_id . "
								GROUP BY picking.picking_list_id,
										picking.unit_mandiri_id,
										picking.depo_id,
										picking.depo_detail_id,
										depo_detail.depo_detail_nama,
										picking.area_id,
										picking.delivery_order_batch_id,
										picking.picking_list_kode,
										do_batch.delivery_order_batch_kode,
										picking.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										tipe_delivery_order.tipe_delivery_order_nama,
										picking.picking_list_tgl_kirim,
										picking.picking_list_create_who,
										picking.picking_list_create_tgl,
										picking.picking_list_keterangan,
										picking.picking_list_status,
										picking.picking_list_tgl_update,
										karyawan.karyawan_id,
										karyawan.karyawan_nama");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_NewPengeluaranBarangBulk($picking_list_id)
	{

		$getDoBatchId = $this->db->select("delivery_order_batch_id as doBatchId")->from("picking_list")->where("picking_list_id", $picking_list_id)->get()->row();

		$query = $this->db->query("SELECT
																	picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	depo_detail.depo_detail_nama,
																	ISNULL(rak.rak_nama, '') AS rak_nama,
																	picking.area_id,
																	picking.delivery_order_batch_id,
																	picking.picking_list_kode,
																	do.delivery_order_kode,
																	do_batch.delivery_order_batch_kode,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias AS picking_list_tipe,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.picking_list_status,
																	principal.principle_kode AS principal,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
																	sku.sku_kode,
																	sku.sku_nama_produk,
																	sku.sku_satuan,
																	sku.sku_kemasan,
																	FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama
																FROM picking_list picking
																LEFT JOIN picking_list_detail picking_detail
																	ON picking.picking_list_id = picking_detail.picking_list_id
																LEFT JOIN picking_list_detail_2 picking_detail2
																	ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
																LEFT JOIN delivery_order_batch do_batch
																	ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
																LEFT JOIN delivery_order do
																	ON do.delivery_order_id = picking_detail.delivery_order_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
																LEFT JOIN rak_sku
																	ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
																LEFT JOIN rak
																	ON rak.rak_id = rak_sku.rak_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_detail2.sku_id
																LEFT JOIN sku_induk
																	ON SKU.sku_induk_id = sku_induk.sku_induk_id
																LEFT JOIN principle principal
																	ON sku.principle_id = principal.principle_id
																LEFT JOIN depo_detail
																	ON depo_detail.depo_detail_id = picking.depo_detail_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
																LEFT JOIN (SELECT TOP 1 WITH TIES
																	a.principle_id,
																	b.karyawan_id,
																	b.karyawan_nama
																FROM karyawan_principle a
																INNER JOIN karyawan b
																	ON a.karyawan_id = b.karyawan_id
																WHERE karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
																AND b.depo_id = '" . $this->session->userdata('depo_id') . "'
																AND a.principle_id IN (SELECT DISTINCT
																	principle_id
																FROM sku
																WHERE sku_id IN (SELECT
																	sku_id
																FROM delivery_order_detail
																WHERE delivery_order_batch_id = '$getDoBatchId->doBatchId'))
																ORDER BY ROW_NUMBER() OVER (PARTITION BY a.principle_id ORDER BY b.karyawan_nama ASC)) AS a
																	ON a.principle_id = sku.principle_id
																WHERE picking.picking_list_id = '$picking_list_id'
																GROUP BY picking.picking_list_id,
																				picking.unit_mandiri_id,
																				picking.depo_id,
																				picking.depo_detail_id,
																				depo_detail.depo_detail_nama,
																				ISNULL(rak.rak_nama, ''),
																				picking.area_id,
																				picking.delivery_order_batch_id,
																				picking.picking_list_kode,
																				do.delivery_order_kode,
																				do_batch.delivery_order_batch_kode,
																				picking.tipe_delivery_order_id,
																				tipe_delivery_order.tipe_delivery_order_alias,
																				picking.picking_list_tgl_kirim,
																				picking.picking_list_create_who,
																				picking.picking_list_create_tgl,
																				picking.picking_list_keterangan,
																				picking.picking_list_status,
																				principal.principle_kode,
																				picking_detail2.sku_id,
																				picking_detail2.sku_stock_id,
																				sku.sku_kode,
																				sku.sku_nama_produk,
																				sku.sku_satuan,
																				sku.sku_kemasan,
																				FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
																				a.karyawan_id,
																				a.karyawan_nama
																ORDER BY do.delivery_order_kode, sku.sku_kode ASC");

		// $query = $this->db->query("SELECT
		// 								picking.picking_list_id,
		// 								picking.unit_mandiri_id,
		// 								picking.depo_id,
		// 								picking.depo_detail_id,
		// 								depo_detail.depo_detail_nama,
		// 								ISNULL(rak.rak_nama, '') AS rak_nama,
		// 								picking.area_id,
		// 								picking.picking_list_kode,
		// 								picking.tipe_delivery_order_id,
		// 								tipe_delivery_order.tipe_delivery_order_alias AS picking_list_tipe,
		// 								picking.picking_list_tgl_kirim,
		// 								picking.picking_list_create_who,
		// 								picking.picking_list_create_tgl,
		// 								picking.picking_list_keterangan,
		// 								picking.picking_list_status,
		// 								principal.principle_kode AS principal,
		// 								picking_detail2.sku_id,
		// 								picking_detail2.sku_stock_id,
		// 								SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
		// 								sku.sku_kode,
		// 								sku.sku_nama_produk,
		// 								sku.sku_satuan,
		// 								sku.sku_kemasan,
		// 								FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date
		// 							FROM picking_list picking
		// 							LEFT JOIN picking_list_detail picking_detail
		// 								ON picking.picking_list_id = picking_detail.picking_list_id
		// 							LEFT JOIN picking_list_detail_2 picking_detail2
		// 								ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
		// 							LEFT JOIN delivery_order_batch do_batch
		// 								ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
		// 							LEFT JOIN delivery_order do
		// 								ON do.delivery_order_id = picking_detail.delivery_order_id
		// 							LEFT JOIN sku_stock
		// 								ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
		// 							LEFT JOIN rak_sku
		// 								ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
		// 							LEFT JOIN rak
		// 								ON rak.rak_id = rak_sku.rak_id
		// 							LEFT JOIN SKU
		// 								ON SKU.sku_id = picking_detail2.sku_id
		// 							LEFT JOIN sku_induk
		// 								ON SKU.sku_induk_id = sku_induk.sku_induk_id
		// 							LEFT JOIN principle principal
		// 								ON sku.principle_id = principal.principle_id
		// 							LEFT JOIN depo_detail
		// 								ON depo_detail.depo_detail_id = picking.depo_detail_id
		// 							LEFT JOIN tipe_delivery_order
		// 								ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
		// 							WHERE picking.picking_list_id = '$picking_list_id'
		// 							GROUP BY picking.picking_list_id,
		// 								picking.unit_mandiri_id,
		// 								picking.depo_id,
		// 								picking.depo_detail_id,
		// 								depo_detail.depo_detail_nama,
		// 								ISNULL(rak.rak_nama, ''),
		// 								picking.area_id,
		// 								picking.picking_list_kode,
		// 								picking.tipe_delivery_order_id,
		// 								tipe_delivery_order.tipe_delivery_order_alias,
		// 								picking.picking_list_tgl_kirim,
		// 								picking.picking_list_create_who,
		// 								picking.picking_list_create_tgl,
		// 								picking.picking_list_keterangan,
		// 								picking.picking_list_status,
		// 								principal.principle_kode,
		// 								picking_detail2.sku_id,
		// 								picking_detail2.sku_stock_id,
		// 								sku.sku_kode,
		// 								sku.sku_nama_produk,
		// 								sku.sku_satuan,
		// 								sku.sku_kemasan,
		// 								FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy')
		// 							ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_NewPengeluaranBarangStandar($picking_list_id)
	{
		$query = $this->db->query("SELECT
									picking.picking_list_id,
									picking.unit_mandiri_id,
									picking.depo_id,
									picking.depo_detail_id,
									depo_detail.depo_detail_nama,
									--ISNULL(rak.rak_nama, '') AS rak_nama,
									picking.area_id,
									picking.delivery_order_batch_id,
									picking.picking_list_kode,
									do.delivery_order_kode,
									do_batch.delivery_order_batch_kode,
									picking.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_alias AS picking_list_tipe,
									picking.picking_list_tgl_kirim,
									picking.picking_list_create_who,
									picking.picking_list_create_tgl,
									picking.picking_list_keterangan,
									picking.picking_list_status,
									principal.principle_kode AS principal,
									picking_detail2.sku_id,
									picking_detail2.sku_stock_id,
									SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date
								FROM picking_list picking
								LEFT JOIN picking_list_detail picking_detail
									ON picking.picking_list_id = picking_detail.picking_list_id
								LEFT JOIN picking_list_detail_2 picking_detail2
									ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
								LEFT JOIN delivery_order_batch do_batch
									ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
								LEFT JOIN delivery_order do
									ON do.delivery_order_id = picking_detail.delivery_order_id
								LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
								-- LEFT JOIN rak_sku
								-- 	ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
								-- LEFT JOIN rak
								-- 	ON rak.rak_id = rak_sku.rak_id
								LEFT JOIN SKU
									ON SKU.sku_id = picking_detail2.sku_id
								LEFT JOIN sku_induk
									ON SKU.sku_induk_id = sku_induk.sku_induk_id
								LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
								LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = picking.depo_detail_id
								LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
								WHERE picking.picking_list_id = '$picking_list_id'
								GROUP BY picking.picking_list_id,
										picking.unit_mandiri_id,
										picking.depo_id,
										picking.depo_detail_id,
										depo_detail.depo_detail_nama,
										--ISNULL(rak.rak_nama, ''),
										picking.area_id,
										picking.delivery_order_batch_id,
										picking.picking_list_kode,
										do.delivery_order_kode,
										do_batch.delivery_order_batch_kode,
										picking.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										picking.picking_list_tgl_kirim,
										picking.picking_list_create_who,
										picking.picking_list_create_tgl,
										picking.picking_list_keterangan,
										picking.picking_list_status,
										principal.principle_kode,
										picking_detail2.sku_id,
										picking_detail2.sku_stock_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_satuan,
										sku.sku_kemasan,
										FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy')
								ORDER BY do.delivery_order_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_NewPengeluaranBarangKirimUlang($picking_list_id)
	{

		$getDoBatchId = $this->db->select("delivery_order_batch_id as doBatchId")->from("picking_list")->where("picking_list_id", $picking_list_id)->get()->row();

		$query = $this->db->query("SELECT
																	picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	depo_detail.depo_detail_nama,
																	ISNULL(rak.rak_nama, '') AS rak_nama,
																	picking.area_id,
																	picking.delivery_order_batch_id,
																	picking.picking_list_kode,
																	do.delivery_order_kode,
																	do_batch.delivery_order_batch_kode,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias AS picking_list_tipe,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.picking_list_status,
																	principal.principle_kode AS principal,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
																	sku.sku_kode,
																	sku.sku_nama_produk,
																	sku.sku_satuan,
																	sku.sku_kemasan,
																	FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama
																FROM picking_list picking
																LEFT JOIN picking_list_detail picking_detail
																	ON picking.picking_list_id = picking_detail.picking_list_id
																LEFT JOIN picking_list_detail_2 picking_detail2
																	ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
																LEFT JOIN delivery_order_batch do_batch
																	ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
																LEFT JOIN delivery_order do
																	ON do.delivery_order_id = picking_detail.delivery_order_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
																LEFT JOIN rak_sku
																	ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
																LEFT JOIN rak
																	ON rak.rak_id = rak_sku.rak_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_detail2.sku_id
																LEFT JOIN sku_induk
																	ON SKU.sku_induk_id = sku_induk.sku_induk_id
																LEFT JOIN principle principal
																	ON sku.principle_id = principal.principle_id
																LEFT JOIN depo_detail
																	ON depo_detail.depo_detail_id = picking.depo_detail_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
																LEFT JOIN (SELECT TOP 1 WITH TIES
																	a.principle_id,
																	b.karyawan_id,
																	b.karyawan_nama
																FROM karyawan_principle a
																INNER JOIN karyawan b
																	ON a.karyawan_id = b.karyawan_id
																WHERE karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
																AND b.depo_id = '" . $this->session->userdata('depo_id') . "'
																AND a.principle_id IN (SELECT DISTINCT
																	principle_id
																FROM sku
																WHERE sku_id IN (SELECT
																	sku_id
																FROM delivery_order_detail
																WHERE delivery_order_batch_id = '$getDoBatchId->doBatchId'))
																ORDER BY ROW_NUMBER() OVER (PARTITION BY a.principle_id ORDER BY b.karyawan_nama ASC)) AS a
																	ON a.principle_id = sku.principle_id
																WHERE picking.picking_list_id = '$picking_list_id'
																GROUP BY picking.picking_list_id,
																				picking.unit_mandiri_id,
																				picking.depo_id,
																				picking.depo_detail_id,
																				depo_detail.depo_detail_nama,
																				ISNULL(rak.rak_nama, ''),
																				picking.area_id,
																				picking.delivery_order_batch_id,
																				picking.picking_list_kode,
																				do.delivery_order_kode,
																				do_batch.delivery_order_batch_kode,
																				picking.tipe_delivery_order_id,
																				tipe_delivery_order.tipe_delivery_order_alias,
																				picking.picking_list_tgl_kirim,
																				picking.picking_list_create_who,
																				picking.picking_list_create_tgl,
																				picking.picking_list_keterangan,
																				picking.picking_list_status,
																				principal.principle_kode,
																				picking_detail2.sku_id,
																				picking_detail2.sku_stock_id,
																				sku.sku_kode,
																				sku.sku_nama_produk,
																				sku.sku_satuan,
																				sku.sku_kemasan,
																				FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
																				a.karyawan_id,
																				a.karyawan_nama
																ORDER BY do.delivery_order_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_NewPengeluaranBarangCanvas($picking_list_id)
	{

		$getDoBatchId = $this->db->select("delivery_order_batch_id as doBatchId")->from("picking_list")->where("picking_list_id", $picking_list_id)->get()->row();

		$query = $this->db->query("SELECT
																	picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	depo_detail.depo_detail_nama,
																	ISNULL(rak.rak_nama, '') AS rak_nama,
																	picking.area_id,
																	picking.delivery_order_batch_id,
																	picking.picking_list_kode,
																	do.delivery_order_kode,
																	do_batch.delivery_order_batch_kode,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias AS picking_list_tipe,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.picking_list_status,
																	principal.principle_kode AS principal,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
																	sku.sku_kode,
																	sku.sku_nama_produk,
																	sku.sku_satuan,
																	sku.sku_kemasan,
																	FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama
																FROM picking_list picking
																LEFT JOIN picking_list_detail picking_detail
																	ON picking.picking_list_id = picking_detail.picking_list_id
																LEFT JOIN picking_list_detail_2 picking_detail2
																	ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
																LEFT JOIN delivery_order_batch do_batch
																	ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
																LEFT JOIN delivery_order do
																	ON do.delivery_order_id = picking_detail.delivery_order_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
																LEFT JOIN rak_sku
																	ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
																LEFT JOIN rak
																	ON rak.rak_id = rak_sku.rak_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_detail2.sku_id
																LEFT JOIN sku_induk
																	ON SKU.sku_induk_id = sku_induk.sku_induk_id
																LEFT JOIN principle principal
																	ON sku.principle_id = principal.principle_id
																LEFT JOIN depo_detail
																	ON depo_detail.depo_detail_id = picking.depo_detail_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
																LEFT JOIN (SELECT TOP 1 WITH TIES
																	a.principle_id,
																	b.karyawan_id,
																	b.karyawan_nama
																FROM karyawan_principle a
																INNER JOIN karyawan b
																	ON a.karyawan_id = b.karyawan_id
																WHERE karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
																AND b.depo_id = '" . $this->session->userdata('depo_id') . "'
																AND a.principle_id IN (SELECT DISTINCT
																	principle_id
																FROM sku
																WHERE sku_id IN (SELECT
																	sku_id
																FROM delivery_order_detail
																WHERE delivery_order_batch_id = '$getDoBatchId->doBatchId'))
																ORDER BY ROW_NUMBER() OVER (PARTITION BY a.principle_id ORDER BY b.karyawan_nama ASC)) AS a
																	ON a.principle_id = sku.principle_id
																WHERE picking.picking_list_id = '$picking_list_id'
																GROUP BY picking.picking_list_id,
																				picking.unit_mandiri_id,
																				picking.depo_id,
																				picking.depo_detail_id,
																				depo_detail.depo_detail_nama,
																				ISNULL(rak.rak_nama, ''),
																				picking.area_id,
																				picking.delivery_order_batch_id,
																				picking.picking_list_kode,
																				do.delivery_order_kode,
																				do_batch.delivery_order_batch_kode,
																				picking.tipe_delivery_order_id,
																				tipe_delivery_order.tipe_delivery_order_alias,
																				picking.picking_list_tgl_kirim,
																				picking.picking_list_create_who,
																				picking.picking_list_create_tgl,
																				picking.picking_list_keterangan,
																				picking.picking_list_status,
																				principal.principle_kode,
																				picking_detail2.sku_id,
																				picking_detail2.sku_stock_id,
																				sku.sku_kode,
																				sku.sku_nama_produk,
																				sku.sku_satuan,
																				sku.sku_kemasan,
																				FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
																				a.karyawan_id,
																				a.karyawan_nama
																ORDER BY do.delivery_order_kode, sku.sku_kode ASC");

		// $query = $this->db->query("SELECT
		// 								picking.picking_list_id,
		// 								picking.unit_mandiri_id,
		// 								picking.depo_id,
		// 								picking.depo_detail_id,
		// 								depo_detail.depo_detail_nama,
		// 								ISNULL(rak.rak_nama, '') AS rak_nama,
		// 								picking.area_id,
		// 								picking.picking_list_kode,
		// 								picking.tipe_delivery_order_id,
		// 								tipe_delivery_order.tipe_delivery_order_alias AS picking_list_tipe,
		// 								picking.picking_list_tgl_kirim,
		// 								picking.picking_list_create_who,
		// 								picking.picking_list_create_tgl,
		// 								picking.picking_list_keterangan,
		// 								picking.picking_list_status,
		// 								principal.principle_kode AS principal,
		// 								picking_detail2.sku_id,
		// 								picking_detail2.sku_stock_id,
		// 								SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
		// 								sku.sku_kode,
		// 								sku.sku_nama_produk,
		// 								sku.sku_satuan,
		// 								sku.sku_kemasan,
		// 								FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date
		// 							FROM picking_list picking
		// 							LEFT JOIN picking_list_detail picking_detail
		// 								ON picking.picking_list_id = picking_detail.picking_list_id
		// 							LEFT JOIN picking_list_detail_2 picking_detail2
		// 								ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
		// 							LEFT JOIN delivery_order_batch do_batch
		// 								ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
		// 							LEFT JOIN delivery_order do
		// 								ON do.delivery_order_id = picking_detail.delivery_order_id
		// 							LEFT JOIN sku_stock
		// 								ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
		// 							LEFT JOIN rak_sku
		// 								ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
		// 							LEFT JOIN rak
		// 								ON rak.rak_id = rak_sku.rak_id
		// 							LEFT JOIN SKU
		// 								ON SKU.sku_id = picking_detail2.sku_id
		// 							LEFT JOIN sku_induk
		// 								ON SKU.sku_induk_id = sku_induk.sku_induk_id
		// 							LEFT JOIN principle principal
		// 								ON sku.principle_id = principal.principle_id
		// 							LEFT JOIN depo_detail
		// 								ON depo_detail.depo_detail_id = picking.depo_detail_id
		// 							LEFT JOIN tipe_delivery_order
		// 								ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
		// 							WHERE picking.picking_list_id = '$picking_list_id'
		// 							GROUP BY picking.picking_list_id,
		// 								picking.unit_mandiri_id,
		// 								picking.depo_id,
		// 								picking.depo_detail_id,
		// 								depo_detail.depo_detail_nama,
		// 								ISNULL(rak.rak_nama, ''),
		// 								picking.area_id,
		// 								picking.picking_list_kode,
		// 								picking.tipe_delivery_order_id,
		// 								tipe_delivery_order.tipe_delivery_order_alias,
		// 								picking.picking_list_tgl_kirim,
		// 								picking.picking_list_create_who,
		// 								picking.picking_list_create_tgl,
		// 								picking.picking_list_keterangan,
		// 								picking.picking_list_status,
		// 								principal.principle_kode,
		// 								picking_detail2.sku_id,
		// 								picking_detail2.sku_stock_id,
		// 								sku.sku_kode,
		// 								sku.sku_nama_produk,
		// 								sku.sku_satuan,
		// 								sku.sku_kemasan,
		// 								FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy')
		// 							ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_NewPengeluaranBarangMix($picking_list_id)
	{
		$query = $this->db->query("SELECT
										picking_list.picking_list_id,
										delivery_order.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_nama
									FROM picking_list
									LEFT JOIN picking_list_detail
										ON picking_list.picking_list_id = picking_list_detail.picking_list_id
									LEFT JOIN delivery_order
										ON delivery_order.delivery_order_id = picking_list_detail.delivery_order_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE picking_list.picking_list_id = '$picking_list_id'
									GROUP BY picking_list.picking_list_id,
										delivery_order.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_nama
									ORDER BY tipe_delivery_order.tipe_delivery_order_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_NewPengeluaranBarangMixBulk($picking_list_id)
	{

		$getDoBatchId = $this->db->select("delivery_order_batch_id as doBatchId")->from("picking_list")->where("picking_list_id", $picking_list_id)->get()->row();

		$query = $this->db->query("SELECT
																	picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	depo_detail.depo_detail_nama,
																	ISNULL(rak.rak_nama, '') AS rak_nama,
																	picking.area_id,
																	picking.delivery_order_batch_id,
																	picking.picking_list_kode,
																	do_batch.delivery_order_batch_kode,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias AS picking_list_tipe,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.picking_list_status,
																	principal.principle_kode AS principal,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
																	sku.sku_kode,
																	sku.sku_nama_produk,
																	sku.sku_satuan,
																	sku.sku_kemasan,
																	FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama
																FROM picking_list picking
																LEFT JOIN picking_list_detail picking_detail
																	ON picking.picking_list_id = picking_detail.picking_list_id
																LEFT JOIN picking_list_detail_2 picking_detail2
																	ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
																LEFT JOIN delivery_order_batch do_batch
																	ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
																LEFT JOIN delivery_order do
																	ON do.delivery_order_id = picking_detail.delivery_order_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
																LEFT JOIN rak_sku
																	ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
																LEFT JOIN rak
																	ON rak.rak_id = rak_sku.rak_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_detail2.sku_id
																LEFT JOIN sku_induk
																	ON SKU.sku_induk_id = sku_induk.sku_induk_id
																LEFT JOIN principle principal
																	ON sku.principle_id = principal.principle_id
																LEFT JOIN depo_detail
																	ON depo_detail.depo_detail_id = picking.depo_detail_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = do.tipe_delivery_order_id
																LEFT JOIN (SELECT TOP 1 WITH TIES
																	a.principle_id,
																	b.karyawan_id,
																	b.karyawan_nama
																FROM karyawan_principle a
																INNER JOIN karyawan b
																	ON a.karyawan_id = b.karyawan_id
																WHERE karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
																AND b.depo_id = '" . $this->session->userdata('depo_id') . "'
																AND a.principle_id IN (SELECT DISTINCT
																	principle_id
																FROM sku
																WHERE sku_id IN (SELECT
																	sku_id
																FROM delivery_order_detail
																WHERE delivery_order_batch_id = '$getDoBatchId->doBatchId'))
																ORDER BY ROW_NUMBER() OVER (PARTITION BY a.principle_id ORDER BY b.karyawan_nama ASC)) AS a
																	ON a.principle_id = sku.principle_id
																WHERE picking.picking_list_id = '$picking_list_id'
																	AND tipe_delivery_order.tipe_delivery_order_nama = 'Bulk' 
																GROUP BY picking.picking_list_id,
																				picking.unit_mandiri_id,
																				picking.depo_id,
																				picking.depo_detail_id,
																				depo_detail.depo_detail_nama,
																				ISNULL(rak.rak_nama, ''),
																				picking.area_id,
																				picking.delivery_order_batch_id,
																				picking.picking_list_kode,
																				do_batch.delivery_order_batch_kode,
																				picking.tipe_delivery_order_id,
																				tipe_delivery_order.tipe_delivery_order_alias,
																				picking.picking_list_tgl_kirim,
																				picking.picking_list_create_who,
																				picking.picking_list_create_tgl,
																				picking.picking_list_keterangan,
																				picking.picking_list_status,
																				principal.principle_kode,
																				picking_detail2.sku_id,
																				picking_detail2.sku_stock_id,
																				sku.sku_kode,
																				sku.sku_nama_produk,
																				sku.sku_satuan,
																				sku.sku_kemasan,
																				FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
																				a.karyawan_id,
																				a.karyawan_nama
																ORDER BY sku.sku_kode ASC");

		// $query = $this->db->query("SELECT
		// 							picking_list.picking_list_id,
		// 							picking_list.unit_mandiri_id,
		// 							picking_list.depo_id,
		// 							picking_list.depo_detail_id,
		// 							depo_detail.depo_detail_nama,
		// 							ISNULL(rak.rak_nama, '') AS rak_nama,
		// 							picking_list.area_id,
		// 							picking_list.picking_list_kode,
		// 							delivery_order.tipe_delivery_order_id,
		// 							tipe_delivery_order.tipe_delivery_order_alias,
		// 							picking_list.picking_list_tgl_kirim,
		// 							picking_list.picking_list_create_who,
		// 							picking_list.picking_list_create_tgl,
		// 							picking_list.picking_list_keterangan,
		// 							picking_list.picking_list_status,
		// 							principal.principle_kode AS principal,
		// 							picking_list_detail_2.sku_id,
		// 							picking_list_detail_2.sku_stock_id,
		// 							SUM(picking_list_detail_2.sku_qty_order) AS sku_qty_order,
		// 							sku.sku_kode,
		// 							sku.sku_nama_produk,
		// 							sku.sku_satuan,
		// 							sku.sku_kemasan,
		// 							FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date
		// 						FROM picking_list
		// 						LEFT JOIN picking_list_detail
		// 							ON picking_list.picking_list_id = picking_list_detail.picking_list_id
		// 						LEFT JOIN picking_list_detail_2
		// 							ON picking_list_detail.picking_list_detail_id = picking_list_detail_2.picking_list_detail_id
		// 						LEFT JOIN delivery_order
		// 							ON delivery_order.delivery_order_id = picking_list_detail.delivery_order_id
		// 						LEFT JOIN sku_stock
		// 							ON sku_stock.sku_stock_id = picking_list_detail_2.sku_stock_id
		// 						LEFT JOIN rak_sku
		// 							ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
		// 						LEFT JOIN rak
		// 							ON rak.rak_id = rak_sku.rak_id
		// 						LEFT JOIN SKU
		// 							ON SKU.sku_id = picking_list_detail_2.sku_id
		// 						LEFT JOIN sku_induk
		// 							ON SKU.sku_induk_id = sku_induk.sku_induk_id
		// 						LEFT JOIN principle principal
		// 							ON sku.principle_id = principal.principle_id
		// 						LEFT JOIN depo_detail
		// 							ON depo_detail.depo_detail_id = picking_list.depo_detail_id
		// 						LEFT JOIN tipe_delivery_order
		// 							ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
		// 						WHERE picking_list.picking_list_id = '$picking_list_id'
		// 						AND tipe_delivery_order.tipe_delivery_order_nama = 'Bulk'
		// 						GROUP BY picking_list.picking_list_id,
		// 								picking_list.unit_mandiri_id,
		// 								picking_list.depo_id,
		// 								picking_list.depo_detail_id,
		// 								depo_detail.depo_detail_nama,
		// 								ISNULL(rak.rak_nama, ''),
		// 								picking_list.area_id,
		// 								picking_list.picking_list_kode,
		// 								delivery_order.tipe_delivery_order_id,
		// 								tipe_delivery_order.tipe_delivery_order_alias,
		// 								picking_list.picking_list_tgl_kirim,
		// 								picking_list.picking_list_create_who,
		// 								picking_list.picking_list_create_tgl,
		// 								picking_list.picking_list_keterangan,
		// 								picking_list.picking_list_status,
		// 								principal.principle_kode,
		// 								picking_list_detail_2.sku_id,
		// 								picking_list_detail_2.sku_stock_id,
		// 								sku.sku_kode,
		// 								sku.sku_nama_produk,
		// 								sku.sku_satuan,
		// 								sku.sku_kemasan,
		// 								FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy')
		// 							ORDER BY tipe_delivery_order.tipe_delivery_order_alias ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_NewPengeluaranBarangMixStandar($picking_list_id)
	{
		$query = $this->db->query("SELECT
									picking_list.picking_list_id,
									picking_list.unit_mandiri_id,
									picking_list.depo_id,
									picking_list.depo_detail_id,
									depo_detail.depo_detail_nama,
									ISNULL(rak.rak_nama, '') AS rak_nama,
									picking_list.area_id,
									picking_list.picking_list_kode,
									picking_list_detail.delivery_order_id,
									delivery_order.delivery_order_kode,
									delivery_order.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_alias,
									picking_list.picking_list_tgl_kirim,
									picking_list.picking_list_create_who,
									picking_list.picking_list_create_tgl,
									picking_list.picking_list_keterangan,
									picking_list.picking_list_status,
									principal.principle_kode AS principal,
									picking_list_detail_2.sku_id,
									picking_list_detail_2.sku_stock_id,
									SUM(picking_list_detail_2.sku_qty_order) AS sku_qty_order,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date
								FROM picking_list
								LEFT JOIN picking_list_detail
									ON picking_list.picking_list_id = picking_list_detail.picking_list_id
								LEFT JOIN picking_list_detail_2
									ON picking_list_detail.picking_list_detail_id = picking_list_detail_2.picking_list_detail_id
								LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_list_detail.delivery_order_id
								LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_list_detail_2.sku_stock_id
								LEFT JOIN rak_sku
									ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
								LEFT JOIN rak
									ON rak.rak_id = rak_sku.rak_id
								LEFT JOIN SKU
									ON SKU.sku_id = picking_list_detail_2.sku_id
								LEFT JOIN sku_induk
									ON SKU.sku_induk_id = sku_induk.sku_induk_id
								LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
								LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = picking_list.depo_detail_id
								LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
								WHERE picking_list.picking_list_id = '$picking_list_id'
								AND tipe_delivery_order.tipe_delivery_order_nama = 'Standar'
								GROUP BY picking_list.picking_list_id,
										picking_list.unit_mandiri_id,
										picking_list.depo_id,
										picking_list.depo_detail_id,
										depo_detail.depo_detail_nama,
										ISNULL(rak.rak_nama, ''),
										picking_list.area_id,
										picking_list.picking_list_kode,
										picking_list_detail.delivery_order_id,
										delivery_order.delivery_order_kode,
										delivery_order.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										picking_list.picking_list_tgl_kirim,
										picking_list.picking_list_create_who,
										picking_list.picking_list_create_tgl,
										picking_list.picking_list_keterangan,
										picking_list.picking_list_status,
										principal.principle_kode,
										picking_list_detail_2.sku_id,
										picking_list_detail_2.sku_stock_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_satuan,
										sku.sku_kemasan,
										FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy')
								ORDER BY tipe_delivery_order.tipe_delivery_order_alias ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_NewPengeluaranBarangMixKirimUlang($picking_list_id)
	{

		$getDoBatchId = $this->db->select("delivery_order_batch_id as doBatchId")->from("picking_list")->where("picking_list_id", $picking_list_id)->get()->row();

		$query = $this->db->query("SELECT
																	picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	depo_detail.depo_detail_nama,
																	ISNULL(rak.rak_nama, '') AS rak_nama,
																	picking.area_id,
																	picking.delivery_order_batch_id,
																	picking.picking_list_kode,
																	do_batch.delivery_order_batch_kode,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias AS picking_list_tipe,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.picking_list_status,
																	principal.principle_kode AS principal,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
																	sku.sku_kode,
																	sku.sku_nama_produk,
																	sku.sku_satuan,
																	sku.sku_kemasan,
																	FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama
																FROM picking_list picking
																LEFT JOIN picking_list_detail picking_detail
																	ON picking.picking_list_id = picking_detail.picking_list_id
																LEFT JOIN picking_list_detail_2 picking_detail2
																	ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
																LEFT JOIN delivery_order_batch do_batch
																	ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
																LEFT JOIN delivery_order do
																	ON do.delivery_order_id = picking_detail.delivery_order_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
																LEFT JOIN rak_sku
																	ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
																LEFT JOIN rak
																	ON rak.rak_id = rak_sku.rak_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_detail2.sku_id
																LEFT JOIN sku_induk
																	ON SKU.sku_induk_id = sku_induk.sku_induk_id
																LEFT JOIN principle principal
																	ON sku.principle_id = principal.principle_id
																LEFT JOIN depo_detail
																	ON depo_detail.depo_detail_id = picking.depo_detail_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = do.tipe_delivery_order_id
																LEFT JOIN (SELECT TOP 1 WITH TIES
																	a.principle_id,
																	b.karyawan_id,
																	b.karyawan_nama
																FROM karyawan_principle a
																INNER JOIN karyawan b
																	ON a.karyawan_id = b.karyawan_id
																WHERE karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
																AND b.depo_id = '" . $this->session->userdata('depo_id') . "'
																AND a.principle_id IN (SELECT DISTINCT
																	principle_id
																FROM sku
																WHERE sku_id IN (SELECT
																	sku_id
																FROM delivery_order_detail
																WHERE delivery_order_batch_id = '$getDoBatchId->doBatchId'))
																ORDER BY ROW_NUMBER() OVER (PARTITION BY a.principle_id ORDER BY b.karyawan_nama ASC)) AS a
																	ON a.principle_id = sku.principle_id
																WHERE picking.picking_list_id = '$picking_list_id'
																	AND tipe_delivery_order.tipe_delivery_order_nama = 'Reschedule' 
																GROUP BY picking.picking_list_id,
																				picking.unit_mandiri_id,
																				picking.depo_id,
																				picking.depo_detail_id,
																				depo_detail.depo_detail_nama,
																				ISNULL(rak.rak_nama, ''),
																				picking.area_id,
																				picking.delivery_order_batch_id,
																				picking.picking_list_kode,
																				do_batch.delivery_order_batch_kode,
																				picking.tipe_delivery_order_id,
																				tipe_delivery_order.tipe_delivery_order_alias,
																				picking.picking_list_tgl_kirim,
																				picking.picking_list_create_who,
																				picking.picking_list_create_tgl,
																				picking.picking_list_keterangan,
																				picking.picking_list_status,
																				principal.principle_kode,
																				picking_detail2.sku_id,
																				picking_detail2.sku_stock_id,
																				sku.sku_kode,
																				sku.sku_nama_produk,
																				sku.sku_satuan,
																				sku.sku_kemasan,
																				FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy'),
																				a.karyawan_id,
																				a.karyawan_nama
																ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Insert_PickingOrder($picking_order_id, $picking_list_id, $depo_id, $picking_order_tanggal, $picking_order_kode, $picking_order_keterangan, $picking_order_status)
	{
		$this->db->set("picking_order_id", $picking_order_id);
		$this->db->set("picking_list_id", $picking_list_id);
		$this->db->set("depo_id", $depo_id);
		// $this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("depo_detail_id", null);
		$this->db->set("picking_order_tanggal", $picking_order_tanggal);
		$this->db->set("picking_order_kode", $picking_order_kode);
		$this->db->set("picking_order_keterangan", $picking_order_keterangan);
		$this->db->set("picking_order_status", $picking_order_status);

		$this->db->insert("picking_order");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$this->db->set("picking_order_id", $picking_order_id);
			$this->db->where("picking_list_id", $picking_list_id);

			$this->db->update("delivery_order_batch");

			$this->db->set("picking_list_status", "In Process");
			$this->db->where("picking_list_id", $picking_list_id);

			$this->db->update("picking_list");

			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $this->db->last_query();
		return $queryinsert;
	}

	public function Insert_PickingOrderPlanStandar($picking_order_id, $picking_list_id, $picking_order_type, $S_UserID, $S_pengguna_nama, $picking_order_status)
	{
		$no = 1;
		$query = $this->db->query("SELECT
										picking.picking_list_id,
										picking.unit_mandiri_id,
										picking.depo_id,
										picking.depo_detail_id,
										picking.area_id,
										picking_detail.delivery_order_id,
										picking.delivery_order_batch_id,
										picking.picking_list_kode,
										do.delivery_order_kode,
										do_batch.delivery_order_batch_kode,
										picking.picking_list_tgl_kirim,
										picking.picking_list_create_who,
										picking.picking_list_create_tgl,
										picking.picking_list_keterangan,
										picking.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias AS tipe_do,
										picking.picking_list_status,
										picking_detail2.sku_id,
										picking_detail2.sku_stock_id,
										SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
										sku_stock.sku_stock_expired_date
									FROM picking_list picking
									LEFT JOIN picking_list_detail picking_detail
										ON picking.picking_list_id = picking_detail.picking_list_id
									LEFT JOIN picking_list_detail_2 picking_detail2
										ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
									LEFT JOIN delivery_order_batch do_batch
										ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
									LEFT JOIN delivery_order do
										ON do.delivery_order_id = picking_detail.delivery_order_id
									LEFT JOIN sku_stock
										ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
									WHERE picking.picking_list_id = '$picking_list_id'
									GROUP BY picking.picking_list_id,
											picking.unit_mandiri_id,
											picking.depo_id,
											picking.depo_detail_id,
											picking.area_id,
											picking_detail.delivery_order_id,
											picking.delivery_order_batch_id,
											picking.picking_list_kode,
											do.delivery_order_kode,
											do_batch.delivery_order_batch_kode,
											picking.picking_list_tgl_kirim,
											picking.picking_list_create_who,
											picking.picking_list_create_tgl,
											picking.picking_list_keterangan,
											picking.tipe_delivery_order_id,
											tipe_delivery_order.tipe_delivery_order_alias,
											picking.picking_list_status,
											picking_detail2.sku_id,
											picking_detail2.sku_stock_id,
											sku_stock.sku_stock_expired_date
									ORDER BY do.delivery_order_kode, picking_detail2.sku_id ASC");

		foreach ($query->result() as $row) {
			$this->db->set("picking_order_plan_id", "NewID()", FALSE);
			$this->db->set("picking_order_id", $picking_order_id);
			$this->db->set("karyawan_id", $S_UserID);
			$this->db->set("karyawan_nama", $S_pengguna_nama);
			$this->db->set("delivery_order_id", $row->delivery_order_id);
			$this->db->set("sku_id", $row->sku_id);
			$this->db->set("sku_stock_id", $row->sku_stock_id);
			$this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
			$this->db->set("sku_stock_qty_ambil", $row->sku_qty_order);
			// $this->db->set("picking_order_plan_print_tgl", "GETDATE()", FALSE);
			// $this->db->set("picking_order_plan_print_who", $S_pengguna_nama);
			// $this->db->set("picking_order_plan_print_counter", 0);
			// $this->db->set("picking_order_plan_print_rekap_tgl", "");
			// $this->db->set("picking_order_plan_print_rekap_who", $picking_order_plan_print_rekap_who);
			// $this->db->set("picking_order_plan_print_rekap_counter", $picking_order_plan_print_rekap_counter);
			// $this->db->set("picking_order_plan_tipe", $row->picking_list_tipe);
			// $this->db->set("picking_order_plan_tipe", $picking_order_type);
			$this->db->set("tipe_delivery_order_id", $picking_order_type);
			$this->db->set("picking_order_plan_status", $picking_order_status);
			$this->db->set("picking_order_plan_nourut", $no);

			$this->db->insert("picking_order_plan");

			$no++;
		}

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $this->db->last_query();
		return $queryinsert;
	}

	public function Insert_PickingOrderPlanBulk($picking_order_id, $picking_list_id, $picking_order_type, $picking_order_status)
	{
		$no = 1;
		$getDoBatchId = $this->db->select("delivery_order_batch_id as doBatchId")->from("picking_list")->where("picking_list_id", $picking_list_id)->get()->row();
		$query =  $this->db->query("SELECT
																	picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	picking.area_id,
																	picking.picking_list_kode,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias AS tipe_do,
																	picking.picking_list_status,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
																	sku_stock.sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama
																FROM picking_list picking
																LEFT JOIN picking_list_detail picking_detail
																	ON picking.picking_list_id = picking_detail.picking_list_id
																LEFT JOIN picking_list_detail_2 picking_detail2
																	ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
																LEFT JOIN delivery_order_batch do_batch
																	ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
																LEFT JOIN delivery_order do
																	ON do.delivery_order_id = picking_detail.delivery_order_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_detail2.sku_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
																LEFT JOIN
																(
																	select top 1 with ties a.principle_id, b.karyawan_id, b.karyawan_nama from karyawan_principle a 
																	inner join karyawan b on a.karyawan_id = b.karyawan_id
																	where karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
																	and b.depo_id = '" . $this->session->userdata('depo_id') . "'
																	and a.principle_id in (
																							select distinct principle_id from sku where sku_id in (
																								select sku_id from delivery_order_detail
																								where delivery_order_batch_id = '$getDoBatchId->doBatchId'
																							)
																						)
																	order by row_number() over (partition by a.principle_id order by b.karyawan_nama asc)
																) as a on a.principle_id = sku.principle_id
																WHERE picking.picking_list_id = '$picking_list_id'
																GROUP BY picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	picking.area_id,
																	picking.picking_list_kode,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias,
																	picking.picking_list_status,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	sku_stock.sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama");

		// $query = $this->db->query("SELECT
		// 								picking.picking_list_id,
		// 								picking.unit_mandiri_id,
		// 								picking.depo_id,
		// 								picking.depo_detail_id,
		// 								picking.area_id,
		// 								picking.picking_list_kode,
		// 								picking.picking_list_tgl_kirim,
		// 								picking.picking_list_create_who,
		// 								picking.picking_list_create_tgl,
		// 								picking.picking_list_keterangan,
		// 								picking.tipe_delivery_order_id,
		// 								tipe_delivery_order.tipe_delivery_order_nama AS tipe_do,
		// 								picking.picking_list_status,
		// 								picking_detail2.sku_id,
		// 								picking_detail2.sku_stock_id,
		// 								SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
		// 								sku_stock.sku_stock_expired_date
		// 							FROM picking_list picking
		// 							LEFT JOIN picking_list_detail picking_detail
		// 								ON picking.picking_list_id = picking_detail.picking_list_id
		// 							LEFT JOIN picking_list_detail_2 picking_detail2
		// 								ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
		// 							LEFT JOIN delivery_order_batch do_batch
		// 								ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
		// 							LEFT JOIN delivery_order do
		// 								ON do.delivery_order_id = picking_detail.delivery_order_id
		// 							LEFT JOIN sku_stock
		// 								ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
		// 							LEFT JOIN tipe_delivery_order
		// 								ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
		// 							WHERE picking.picking_list_id = '$picking_list_id'
		// 							GROUP BY picking.picking_list_id,
		// 								picking.unit_mandiri_id,
		// 								picking.depo_id,
		// 								picking.depo_detail_id,
		// 								picking.area_id,
		// 								picking.picking_list_kode,
		// 								picking.picking_list_tgl_kirim,
		// 								picking.picking_list_create_who,
		// 								picking.picking_list_create_tgl,
		// 								picking.picking_list_keterangan,
		// 								picking.tipe_delivery_order_id,
		// 								tipe_delivery_order.tipe_delivery_order_nama,
		// 								picking.picking_list_status,
		// 								picking_detail2.sku_id,
		// 								picking_detail2.sku_stock_id,
		// 								sku_stock.sku_stock_expired_date");

		foreach ($query->result() as $row) {
			$this->db->set("picking_order_plan_id", "NewID()", FALSE);
			$this->db->set("picking_order_id", $picking_order_id);
			$this->db->set("karyawan_id", $row->karyawan_id);
			$this->db->set("karyawan_nama", $row->karyawan_nama);
			// $this->db->set("delivery_order_id", $row->delivery_order_id);
			$this->db->set("sku_id", $row->sku_id);
			$this->db->set("sku_stock_id", $row->sku_stock_id);
			$this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
			$this->db->set("sku_stock_qty_ambil", $row->sku_qty_order);
			// $this->db->set("picking_order_plan_print_tgl", "GETDATE()", FALSE);
			// $this->db->set("picking_order_plan_print_who", $S_pengguna_nama);
			// $this->db->set("picking_order_plan_print_counter", 0);
			// $this->db->set("picking_order_plan_print_rekap_tgl", "");
			// $this->db->set("picking_order_plan_print_rekap_who", $picking_order_plan_print_rekap_who);
			// $this->db->set("picking_order_plan_print_rekap_counter", $picking_order_plan_print_rekap_counter);
			// $this->db->set("picking_order_plan_tipe", $row->picking_list_tipe);
			// $this->db->set("picking_order_plan_tipe", $picking_order_type);
			$this->db->set("tipe_delivery_order_id", $picking_order_type);
			$this->db->set("picking_order_plan_status", $picking_order_status);
			$this->db->set("picking_order_plan_nourut", $no);

			$this->db->insert("picking_order_plan");

			$no++;
		}

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $this->db->last_query();
		return $queryinsert;
	}

	public function Insert_PickingOrderPlanFlushOut($picking_order_id, $picking_list_id, $picking_order_type, $picking_order_status)
	{
		$no = 1;
		$getDoBatchId = $this->db->select("delivery_order_batch_id as doBatchId")->from("picking_list")->where("picking_list_id", $picking_list_id)->get()->row();
		$query =  $this->db->query("SELECT
																	picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	picking.area_id,
																	picking.picking_list_kode,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias AS tipe_do,
																	picking.picking_list_status,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
																	sku_stock.sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama
																FROM picking_list picking
																LEFT JOIN picking_list_detail picking_detail
																	ON picking.picking_list_id = picking_detail.picking_list_id
																LEFT JOIN picking_list_detail_2 picking_detail2
																	ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
																LEFT JOIN delivery_order_batch do_batch
																	ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
																LEFT JOIN delivery_order do
																	ON do.delivery_order_id = picking_detail.delivery_order_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_detail2.sku_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
																LEFT JOIN
																(
																	select top 1 with ties a.principle_id, b.karyawan_id, b.karyawan_nama from karyawan_principle a 
																	inner join karyawan b on a.karyawan_id = b.karyawan_id
																	where karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
																	and b.depo_id = '" . $this->session->userdata('depo_id') . "'
																	and a.principle_id in (
																							select distinct principle_id from sku where sku_id in (
																								select sku_id from delivery_order_detail
																								where delivery_order_batch_id = '$getDoBatchId->doBatchId'
																							)
																						)
																	order by row_number() over (partition by a.principle_id order by b.karyawan_nama asc)
																) as a on a.principle_id = sku.principle_id
																WHERE picking.picking_list_id = '$picking_list_id'
																GROUP BY picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	picking.area_id,
																	picking.picking_list_kode,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias,
																	picking.picking_list_status,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	sku_stock.sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama");

		foreach ($query->result() as $row) {
			$this->db->set("picking_order_plan_id", "NewID()", FALSE);
			$this->db->set("picking_order_id", $picking_order_id);
			$this->db->set("karyawan_id", $row->karyawan_id);
			$this->db->set("karyawan_nama", $row->karyawan_nama);
			// $this->db->set("delivery_order_id", $row->delivery_order_id);
			$this->db->set("sku_id", $row->sku_id);
			$this->db->set("sku_stock_id", $row->sku_stock_id);
			$this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
			$this->db->set("sku_stock_qty_ambil", $row->sku_qty_order);
			// $this->db->set("picking_order_plan_print_tgl", "GETDATE()", FALSE);
			// $this->db->set("picking_order_plan_print_who", $S_pengguna_nama);
			// $this->db->set("picking_order_plan_print_counter", 0);
			// $this->db->set("picking_order_plan_print_rekap_tgl", "");
			// $this->db->set("picking_order_plan_print_rekap_who", $picking_order_plan_print_rekap_who);
			// $this->db->set("picking_order_plan_print_rekap_counter", $picking_order_plan_print_rekap_counter);
			// $this->db->set("picking_order_plan_tipe", $row->picking_list_tipe);
			// $this->db->set("picking_order_plan_tipe", $picking_order_type);
			$this->db->set("tipe_delivery_order_id", $picking_order_type);
			$this->db->set("picking_order_plan_status", $picking_order_status);
			$this->db->set("picking_order_plan_nourut", $no);

			$this->db->insert("picking_order_plan");

			$no++;
		}

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $this->db->last_query();
		return $queryinsert;
	}

	public function Insert_PickingOrderPlanKirimUlang($picking_order_id, $picking_list_id, $picking_order_type, $picking_order_status)
	{
		$no = 1;
		$getDoBatchId = $this->db->select("delivery_order_batch_id as doBatchId")->from("picking_list")->where("picking_list_id", $picking_list_id)->get()->row();
		$query =  $this->db->query("SELECT
																	picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	picking.area_id,
																	picking.picking_list_kode,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias AS tipe_do,
																	picking.picking_list_status,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
																	sku_stock.sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama
																FROM picking_list picking
																LEFT JOIN picking_list_detail picking_detail
																	ON picking.picking_list_id = picking_detail.picking_list_id
																LEFT JOIN picking_list_detail_2 picking_detail2
																	ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
																LEFT JOIN delivery_order_batch do_batch
																	ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
																LEFT JOIN delivery_order do
																	ON do.delivery_order_id = picking_detail.delivery_order_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_detail2.sku_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
																LEFT JOIN
																(
																	select top 1 with ties a.principle_id, b.karyawan_id, b.karyawan_nama from karyawan_principle a 
																	inner join karyawan b on a.karyawan_id = b.karyawan_id
																	where karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
																	and b.depo_id = '" . $this->session->userdata('depo_id') . "'
																	and a.principle_id in (
																							select distinct principle_id from sku where sku_id in (
																								select sku_id from delivery_order_detail
																								where delivery_order_batch_id = '$getDoBatchId->doBatchId'
																							)
																						)
																	order by row_number() over (partition by a.principle_id order by b.karyawan_nama asc)
																) as a on a.principle_id = sku.principle_id
																WHERE picking.picking_list_id = '$picking_list_id'
																GROUP BY picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	picking.area_id,
																	picking.picking_list_kode,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias,
																	picking.picking_list_status,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	sku_stock.sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama");

		foreach ($query->result() as $row) {
			$this->db->set("picking_order_plan_id", "NewID()", FALSE);
			$this->db->set("picking_order_id", $picking_order_id);
			$this->db->set("karyawan_id", $row->karyawan_id);
			$this->db->set("karyawan_nama", $row->karyawan_nama);
			$this->db->set("sku_id", $row->sku_id);
			$this->db->set("sku_stock_id", $row->sku_stock_id);
			$this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
			$this->db->set("sku_stock_qty_ambil", $row->sku_qty_order);
			$this->db->set("tipe_delivery_order_id", $picking_order_type);
			$this->db->set("picking_order_plan_status", $picking_order_status);
			$this->db->set("picking_order_plan_nourut", $no);

			$this->db->insert("picking_order_plan");

			$no++;
		}

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $this->db->last_query();
		return $queryinsert;
	}

	public function Insert_PickingOrderPlanCanvas($picking_order_id, $picking_list_id, $picking_order_type, $picking_order_status)
	{
		$no = 1;
		$getDoBatchId = $this->db->select("delivery_order_batch_id as doBatchId")->from("picking_list")->where("picking_list_id", $picking_list_id)->get()->row();
		$query =  $this->db->query("SELECT
																	picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	picking.area_id,
																	picking.picking_list_kode,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias AS tipe_do,
																	picking.picking_list_status,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
																	sku_stock.sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama
																FROM picking_list picking
																LEFT JOIN picking_list_detail picking_detail
																	ON picking.picking_list_id = picking_detail.picking_list_id
																LEFT JOIN picking_list_detail_2 picking_detail2
																	ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
																LEFT JOIN delivery_order_batch do_batch
																	ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
																LEFT JOIN delivery_order do
																	ON do.delivery_order_id = picking_detail.delivery_order_id
																LEFT JOIN SKU
																	ON SKU.sku_id = picking_detail2.sku_id
																LEFT JOIN sku_stock
																	ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
																LEFT JOIN tipe_delivery_order
																	ON tipe_delivery_order.tipe_delivery_order_id = picking.tipe_delivery_order_id
																LEFT JOIN
																(
																	select top 1 with ties a.principle_id, b.karyawan_id, b.karyawan_nama from karyawan_principle a 
																	inner join karyawan b on a.karyawan_id = b.karyawan_id
																	where karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
																	and b.depo_id = '" . $this->session->userdata('depo_id') . "'
																	and a.principle_id in (
																							select distinct principle_id from sku where sku_id in (
																								select sku_id from delivery_order_detail
																								where delivery_order_batch_id = '$getDoBatchId->doBatchId'
																							)
																						)
																	order by row_number() over (partition by a.principle_id order by b.karyawan_nama asc)
																) as a on a.principle_id = sku.principle_id
																WHERE picking.picking_list_id = '$picking_list_id'
																GROUP BY picking.picking_list_id,
																	picking.unit_mandiri_id,
																	picking.depo_id,
																	picking.depo_detail_id,
																	picking.area_id,
																	picking.picking_list_kode,
																	picking.picking_list_tgl_kirim,
																	picking.picking_list_create_who,
																	picking.picking_list_create_tgl,
																	picking.picking_list_keterangan,
																	picking.tipe_delivery_order_id,
																	tipe_delivery_order.tipe_delivery_order_alias,
																	picking.picking_list_status,
																	picking_detail2.sku_id,
																	picking_detail2.sku_stock_id,
																	sku_stock.sku_stock_expired_date,
																	a.karyawan_id,
																	a.karyawan_nama");

		foreach ($query->result() as $row) {
			$this->db->set("picking_order_plan_id", "NewID()", FALSE);
			$this->db->set("picking_order_id", $picking_order_id);
			$this->db->set("karyawan_id", $row->karyawan_id);
			$this->db->set("karyawan_nama", $row->karyawan_nama);
			$this->db->set("sku_id", $row->sku_id);
			$this->db->set("sku_stock_id", $row->sku_stock_id);
			$this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
			$this->db->set("sku_stock_qty_ambil", $row->sku_qty_order);
			$this->db->set("tipe_delivery_order_id", $picking_order_type);
			$this->db->set("picking_order_plan_status", $picking_order_status);
			$this->db->set("picking_order_plan_nourut", $no);

			$this->db->insert("picking_order_plan");

			$no++;
		}

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $this->db->last_query();
		return $queryinsert;
	}

	public function Insert_PickingOrderPlanMix($picking_order_id, $picking_list_id, $picking_order_type, $S_UserID, $S_pengguna_nama, $picking_order_status, $bulk, $standar, $kirimUlang)
	{
		$no = 1;
		if ($bulk != 0) {
			$getDoBatchId = $this->db->select("delivery_order_batch_id as doBatchId")->from("picking_list")->where("picking_list_id", $picking_list_id)->get()->row();
			$query = $this->db->query("SELECT
													picking.picking_list_id,
													picking.unit_mandiri_id,
													picking.depo_id,
													picking.depo_detail_id,
													picking.area_id,
													picking.picking_list_kode,
													picking.picking_list_tgl_kirim,
													picking.picking_list_create_who,
													picking.picking_list_create_tgl,
													picking.picking_list_keterangan,
													picking.tipe_delivery_order_id,
													tipe_delivery_order.tipe_delivery_order_alias AS tipe_do,
													picking.picking_list_status,
													picking_detail2.sku_id,
													picking_detail2.sku_stock_id,
													SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
													sku_stock.sku_stock_expired_date,
													a.karyawan_id,
													a.karyawan_nama
												FROM picking_list picking
												LEFT JOIN picking_list_detail picking_detail
													ON picking.picking_list_id = picking_detail.picking_list_id
												LEFT JOIN picking_list_detail_2 picking_detail2
													ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
												LEFT JOIN delivery_order_batch do_batch
													ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
												LEFT JOIN delivery_order do
													ON do.delivery_order_id = picking_detail.delivery_order_id
												LEFT JOIN SKU
													ON SKU.sku_id = picking_detail2.sku_id
												LEFT JOIN sku_stock
													ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
												LEFT JOIN tipe_delivery_order
													ON tipe_delivery_order.tipe_delivery_order_id = do.tipe_delivery_order_id
												LEFT JOIN
												(
													select top 1 with ties a.principle_id, b.karyawan_id, b.karyawan_nama from karyawan_principle a 
													inner join karyawan b on a.karyawan_id = b.karyawan_id
													where karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
													and b.depo_id = '" . $this->session->userdata('depo_id') . "'
													and a.principle_id in (
																			select distinct principle_id from sku where sku_id in (
																				select sku_id from delivery_order_detail
																				where delivery_order_batch_id = '$getDoBatchId->doBatchId'
																			)
																		)
													order by row_number() over (partition by a.principle_id order by b.karyawan_nama asc)
												) as a on a.principle_id = sku.principle_id
												WHERE picking.picking_list_id = '$picking_list_id' AND tipe_delivery_order.tipe_delivery_order_nama = 'Bulk'
												GROUP BY picking.picking_list_id,
													picking.unit_mandiri_id,
													picking.depo_id,
													picking.depo_detail_id,
													picking.area_id,
													picking.picking_list_kode,
													picking.picking_list_tgl_kirim,
													picking.picking_list_create_who,
													picking.picking_list_create_tgl,
													picking.picking_list_keterangan,
													picking.tipe_delivery_order_id,
													tipe_delivery_order.tipe_delivery_order_alias,
													picking.picking_list_status,
													picking_detail2.sku_id,
													picking_detail2.sku_stock_id,
													sku_stock.sku_stock_expired_date,
													a.karyawan_id,
													a.karyawan_nama");

			// $query = $this->db->query("SELECT
			// 								picking.picking_list_id,
			// 								picking.unit_mandiri_id,
			// 								picking.depo_id,
			// 								picking.depo_detail_id,
			// 								picking.area_id,
			// 								picking.picking_list_kode,
			// 								picking.picking_list_tgl_kirim,
			// 								picking.picking_list_create_who,
			// 								picking.picking_list_create_tgl,
			// 								picking.picking_list_keterangan,
			// 								do.tipe_delivery_order_id,
			// 								tipe_delivery_order.tipe_delivery_order_nama AS tipe_do,
			// 								picking.picking_list_status,
			// 								picking_detail2.sku_id,
			// 								picking_detail2.sku_stock_id,
			// 								SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
			// 								sku_stock.sku_stock_expired_date
			// 							FROM picking_list picking
			// 							LEFT JOIN picking_list_detail picking_detail
			// 								ON picking.picking_list_id = picking_detail.picking_list_id
			// 							LEFT JOIN picking_list_detail_2 picking_detail2
			// 								ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
			// 							LEFT JOIN delivery_order do
			// 								ON do.delivery_order_id = picking_detail.delivery_order_id
			// 							LEFT JOIN sku_stock
			// 								ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
			// 							LEFT JOIN tipe_delivery_order
			// 								ON tipe_delivery_order.tipe_delivery_order_id = do.tipe_delivery_order_id
			// 							WHERE picking.picking_list_id = '$picking_list_id' AND tipe_delivery_order.tipe_delivery_order_nama = 'Bulk'
			// 							GROUP BY picking.picking_list_id,
			// 								picking.unit_mandiri_id,
			// 								picking.depo_id,
			// 								picking.depo_detail_id,
			// 								picking.area_id,
			// 								picking.picking_list_kode,
			// 								picking.picking_list_tgl_kirim,
			// 								picking.picking_list_create_who,
			// 								picking.picking_list_create_tgl,
			// 								picking.picking_list_keterangan,
			// 								do.tipe_delivery_order_id,
			// 								tipe_delivery_order.tipe_delivery_order_nama,
			// 								picking.picking_list_status,
			// 								picking_detail2.sku_id,
			// 								picking_detail2.sku_stock_id,
			// 								sku_stock.sku_stock_expired_date");

			foreach ($query->result() as $row) {
				$this->db->set("picking_order_plan_id", "NewID()", FALSE);
				$this->db->set("picking_order_id", $picking_order_id);
				$this->db->set("karyawan_id", $row->karyawan_id);
				$this->db->set("karyawan_nama", $row->karyawan_nama);
				// $this->db->set("delivery_order_id", $row->delivery_order_id);
				$this->db->set("sku_id", $row->sku_id);
				$this->db->set("sku_stock_id", $row->sku_stock_id);
				$this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
				$this->db->set("sku_stock_qty_ambil", $row->sku_qty_order);
				// $this->db->set("picking_order_plan_print_tgl", "GETDATE()", FALSE);
				// $this->db->set("picking_order_plan_print_who", $S_pengguna_nama);
				// $this->db->set("picking_order_plan_print_counter", 0);
				// $this->db->set("picking_order_plan_print_rekap_tgl", "");
				// $this->db->set("picking_order_plan_print_rekap_who", $picking_order_plan_print_rekap_who);
				// $this->db->set("picking_order_plan_print_rekap_counter", $picking_order_plan_print_rekap_counter);
				// $this->db->set("picking_order_plan_tipe", $row->picking_list_tipe);
				// $this->db->set("picking_order_plan_tipe", $picking_order_type);
				$this->db->set("tipe_delivery_order_id", $picking_order_type);
				$this->db->set("picking_order_plan_status", $picking_order_status);
				$this->db->set("picking_order_plan_nourut", $no);

				$this->db->insert("picking_order_plan");
				$no++;
			}
		}

		if ($standar != 0) {
			$query = $this->db->query("SELECT
			picking.picking_list_id,
			picking.unit_mandiri_id,
			picking.depo_id,
			picking.depo_detail_id,
			picking.area_id,
			picking_detail.delivery_order_id,
			picking.delivery_order_batch_id,
			picking.picking_list_kode,
			do.delivery_order_kode,
			do_batch.delivery_order_batch_kode,
			picking.picking_list_tgl_kirim,
			picking.picking_list_create_who,
			picking.picking_list_create_tgl,
			picking.picking_list_keterangan,
			do.tipe_delivery_order_id,
			tipe_delivery_order.tipe_delivery_order_alias AS tipe_do,
			picking.picking_list_status,
			picking_detail2.sku_id,
			picking_detail2.sku_stock_id,
			SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
			sku_stock.sku_stock_expired_date
		FROM picking_list picking
		LEFT JOIN picking_list_detail picking_detail
			ON picking.picking_list_id = picking_detail.picking_list_id
		LEFT JOIN picking_list_detail_2 picking_detail2
			ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
		LEFT JOIN delivery_order_batch do_batch
			ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
		LEFT JOIN delivery_order do
			ON do.delivery_order_id = picking_detail.delivery_order_id
		LEFT JOIN sku_stock
			ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
		LEFT JOIN tipe_delivery_order
			ON tipe_delivery_order.tipe_delivery_order_id = do.tipe_delivery_order_id
		WHERE picking.picking_list_id = '$picking_list_id' AND tipe_delivery_order.tipe_delivery_order_nama = 'Standar'
		GROUP BY picking.picking_list_id,
				picking.unit_mandiri_id,
				picking.depo_id,
				picking.depo_detail_id,
				picking.area_id,
				picking_detail.delivery_order_id,
				picking.delivery_order_batch_id,
				picking.picking_list_kode,
				do.delivery_order_kode,
				do_batch.delivery_order_batch_kode,
				picking.picking_list_tgl_kirim,
				picking.picking_list_create_who,
				picking.picking_list_create_tgl,
				picking.picking_list_keterangan,
				do.tipe_delivery_order_id,
				tipe_delivery_order.tipe_delivery_order_alias,
				picking.picking_list_status,
				picking_detail2.sku_id,
				picking_detail2.sku_stock_id,
				sku_stock.sku_stock_expired_date
		ORDER BY do.delivery_order_kode, picking_detail2.sku_id ASC");

			foreach ($query->result() as $row) {
				$this->db->set("picking_order_plan_id", "NewID()", FALSE);
				$this->db->set("picking_order_id", $picking_order_id);
				$this->db->set("karyawan_id", $S_UserID);
				$this->db->set("karyawan_nama", $S_pengguna_nama);
				$this->db->set("delivery_order_id", $row->delivery_order_id);
				$this->db->set("sku_id", $row->sku_id);
				$this->db->set("sku_stock_id", $row->sku_stock_id);
				$this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
				$this->db->set("sku_stock_qty_ambil", $row->sku_qty_order);
				// $this->db->set("picking_order_plan_print_tgl", "GETDATE()", FALSE);
				// $this->db->set("picking_order_plan_print_who", $S_pengguna_nama);
				// $this->db->set("picking_order_plan_print_counter", 0);
				// $this->db->set("picking_order_plan_print_rekap_tgl", "");
				// $this->db->set("picking_order_plan_print_rekap_who", $picking_order_plan_print_rekap_who);
				// $this->db->set("picking_order_plan_print_rekap_counter", $picking_order_plan_print_rekap_counter);
				// $this->db->set("picking_order_plan_tipe", $row->picking_list_tipe);
				// $this->db->set("picking_order_plan_tipe", $picking_order_type);
				$this->db->set("tipe_delivery_order_id", $picking_order_type);
				$this->db->set("picking_order_plan_status", $picking_order_status);
				$this->db->set("picking_order_plan_nourut", $no);

				$this->db->insert("picking_order_plan");

				$no++;
			}
		}

		if ($kirimUlang != 0) {
			$getDoBatchId = $this->db->select("delivery_order_batch_id as doBatchId")->from("picking_list")->where("picking_list_id", $picking_list_id)->get()->row();
			$query = $this->db->query("SELECT
													picking.picking_list_id,
													picking.unit_mandiri_id,
													picking.depo_id,
													picking.depo_detail_id,
													picking.area_id,
													picking.picking_list_kode,
													picking.picking_list_tgl_kirim,
													picking.picking_list_create_who,
													picking.picking_list_create_tgl,
													picking.picking_list_keterangan,
													picking.tipe_delivery_order_id,
													tipe_delivery_order.tipe_delivery_order_alias AS tipe_do,
													picking.picking_list_status,
													picking_detail2.sku_id,
													picking_detail2.sku_stock_id,
													SUM(picking_detail2.sku_qty_order) AS sku_qty_order,
													sku_stock.sku_stock_expired_date,
													a.karyawan_id,
													a.karyawan_nama
												FROM picking_list picking
												LEFT JOIN picking_list_detail picking_detail
													ON picking.picking_list_id = picking_detail.picking_list_id
												LEFT JOIN picking_list_detail_2 picking_detail2
													ON picking_detail.picking_list_detail_id = picking_detail2.picking_list_detail_id
												LEFT JOIN delivery_order_batch do_batch
													ON do_batch.delivery_order_batch_id = picking.delivery_order_batch_id
												LEFT JOIN delivery_order do
													ON do.delivery_order_id = picking_detail.delivery_order_id
												LEFT JOIN SKU
													ON SKU.sku_id = picking_detail2.sku_id
												LEFT JOIN sku_stock
													ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
												LEFT JOIN tipe_delivery_order
													ON tipe_delivery_order.tipe_delivery_order_id = do.tipe_delivery_order_id
												LEFT JOIN
												(
													select top 1 with ties a.principle_id, b.karyawan_id, b.karyawan_nama from karyawan_principle a 
													inner join karyawan b on a.karyawan_id = b.karyawan_id
													where karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
													and b.depo_id = '" . $this->session->userdata('depo_id') . "'
													and a.principle_id in (
																			select distinct principle_id from sku where sku_id in (
																				select sku_id from delivery_order_detail
																				where delivery_order_batch_id = '$getDoBatchId->doBatchId'
																			)
																		)
													order by row_number() over (partition by a.principle_id order by b.karyawan_nama asc)
												) as a on a.principle_id = sku.principle_id
												WHERE picking.picking_list_id = '$picking_list_id' AND tipe_delivery_order.tipe_delivery_order_nama = 'Reschedule'
												GROUP BY picking.picking_list_id,
													picking.unit_mandiri_id,
													picking.depo_id,
													picking.depo_detail_id,
													picking.area_id,
													picking.picking_list_kode,
													picking.picking_list_tgl_kirim,
													picking.picking_list_create_who,
													picking.picking_list_create_tgl,
													picking.picking_list_keterangan,
													picking.tipe_delivery_order_id,
													tipe_delivery_order.tipe_delivery_order_alias,
													picking.picking_list_status,
													picking_detail2.sku_id,
													picking_detail2.sku_stock_id,
													sku_stock.sku_stock_expired_date,
													a.karyawan_id,
													a.karyawan_nama");

			foreach ($query->result() as $row) {
				$this->db->set("picking_order_plan_id", "NewID()", FALSE);
				$this->db->set("picking_order_id", $picking_order_id);
				$this->db->set("karyawan_id", $row->karyawan_id);
				$this->db->set("karyawan_nama", $row->karyawan_nama);
				$this->db->set("sku_id", $row->sku_id);
				$this->db->set("sku_stock_id", $row->sku_stock_id);
				$this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
				$this->db->set("sku_stock_qty_ambil", $row->sku_qty_order);
				$this->db->set("tipe_delivery_order_id", $picking_order_type);
				$this->db->set("picking_order_plan_status", $picking_order_status);
				$this->db->set("picking_order_plan_nourut", $no);

				$this->db->insert("picking_order_plan");
				$no++;
			}
		}

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $this->db->last_query();
		return $queryinsert;
	}

	public function Insert_PickingOrderAktual_H($picking_order_aktual_h_id, $picking_order_id, $picking_order_aktual_kode, $karyawan_id, $karyawan_nama)
	{
		$this->db->set("picking_order_aktual_h_id", $picking_order_aktual_h_id);
		$this->db->set("picking_order_id", $picking_order_id);
		$this->db->set("picking_order_aktual_kode", $picking_order_aktual_kode);
		$this->db->set("picking_order_aktual_tgl", "GETDATE()", FALSE);
		$this->db->set("karyawan_id", $karyawan_id);
		$this->db->set("karyawan_nama", $karyawan_nama);

		$this->db->insert("picking_order_aktual_h");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_PickingOrderAktual_D($data, $picking_order_aktual_h_id)
	{

		$sku_stock = explode(" || ", $data['sku_stock_expired_date']);
		$sku_stock_id = $sku_stock[0];
		$sku_stock_expired_date = date('Y-m-d', strtotime($sku_stock[1]));

		$query = $this->db->query("SELECT ISNULL(sku_stock_saldo_alokasi, 0) AS sisa_stock, ISNULL(sku_stock_keluar, 0) as sku_stock_keluar FROM sku_stock WHERE sku_stock_id = '$sku_stock_id' ");

		$sisa_stock = $query->row(0)->sisa_stock;
		$sku_stock_keluar = $query->row(0)->sku_stock_keluar;

		$stock_akhir = $sisa_stock - $data['sku_stock_qty_ambil'];
		$stock_keluar = $sku_stock_keluar + $data['sku_stock_qty_ambil'];

		if ($data['tipe_do'] == "Standar") {
			$this->db->set("picking_order_aktual_d_id", $data['picking_order_aktual_d_id']);
			$this->db->set("picking_order_aktual_h_id", $picking_order_aktual_h_id);
			$this->db->set("picking_order_plan_id", $data['picking_order_plan_id']);
			$this->db->set("delivery_order_id", $data['delivery_order_id']);
			$this->db->set("sku_id", $data['sku_id']);
			$this->db->set("sku_stock_id", $sku_stock_id);
			$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
			$this->db->set("sku_stock_qty_ambil", $data['sku_stock_qty_ambil']);
			$this->db->set("picking_order_plan_nourut", $data['no_urut']);
			$this->db->set("depo_detail_id", $data['depo_detail_id']);
			$this->db->set("flag", $data['checked'] == "true" ? 1 : NULL);

			$this->db->insert("picking_order_aktual_d");

			$affectedrows = $this->db->affected_rows();
			if ($affectedrows > 0) {
				$this->db->query("exec insertupdate_sku_stock 'saldoalokasi_kurang', '$sku_stock_id', NULL, '" . $data['sku_stock_qty_ambil'] . "'");

				// $this->db->set("sku_stock_saldo_alokasi", $stock_akhir);
				// $this->db->set("sku_stock_keluar", $stock_keluar);

				// $this->db->where("sku_stock_id", $sku_stock_id);

				// $this->db->update("sku_stock");

				$queryinsert = 1;
			} else {
				$queryinsert = 0;
			}

			return $queryinsert;
			// return $this->db->last_query();

		} else if ($data['tipe_do'] == "Bulk") {
			$this->db->set("picking_order_aktual_d_id", $data['picking_order_aktual_d_id']);
			$this->db->set("picking_order_aktual_h_id", $picking_order_aktual_h_id);
			$this->db->set("picking_order_plan_id", $data['picking_order_plan_id']);
			// $this->db->set("delivery_order_id", $data['delivery_order_id']);
			$this->db->set("sku_id", $data['sku_id']);
			$this->db->set("sku_stock_id", $sku_stock_id);
			$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
			$this->db->set("sku_stock_qty_ambil", $data['sku_stock_qty_ambil']);
			$this->db->set("picking_order_plan_nourut", $data['no_urut']);
			$this->db->set("depo_detail_id", $data['depo_detail_id']);
			$this->db->set("flag", $data['checked'] == "true" ? 1 : NULL);

			$this->db->insert("picking_order_aktual_d");

			$affectedrows = $this->db->affected_rows();
			if ($affectedrows > 0) {

				$this->db->query("exec insertupdate_sku_stock 'saldoalokasi_kurang', '$sku_stock_id', NULL,'" . $data['sku_stock_qty_ambil'] . "'");

				// $this->db->set("sku_stock_saldo_alokasi", $stock_akhir);
				// $this->db->set("sku_stock_keluar", $stock_keluar);

				// $this->db->where("sku_stock_id", $sku_stock_id);

				// $this->db->update("sku_stock");

				$queryinsert = 1;
			} else {
				$queryinsert = 0;
			}

			return $queryinsert;
			// return $this->db->last_query();

		} else if ($data['tipe_do'] == "Reschedule") {
			$this->db->set("picking_order_aktual_d_id", $data['picking_order_aktual_d_id']);
			$this->db->set("picking_order_aktual_h_id", $picking_order_aktual_h_id);
			$this->db->set("picking_order_plan_id", $data['picking_order_plan_id']);
			// $this->db->set("delivery_order_id", $data['delivery_order_id']);
			$this->db->set("sku_id", $data['sku_id']);
			$this->db->set("sku_stock_id", $sku_stock_id);
			$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
			$this->db->set("sku_stock_qty_ambil", $data['sku_stock_qty_ambil']);
			$this->db->set("picking_order_plan_nourut", $data['no_urut']);
			$this->db->set("depo_detail_id", $data['depo_detail_id']);
			$this->db->set("flag", $data['checked'] == "true" ? 1 : NULL);

			$this->db->insert("picking_order_aktual_d");

			$affectedrows = $this->db->affected_rows();
			if ($affectedrows > 0) {

				$this->db->query("exec insertupdate_sku_stock 'saldoalokasi_kurang', '$sku_stock_id', NULL,'" . $data['sku_stock_qty_ambil'] . "'");

				// $this->db->set("sku_stock_saldo_alokasi", $stock_akhir);
				// $this->db->set("sku_stock_keluar", $stock_keluar);

				// $this->db->where("sku_stock_id", $sku_stock_id);

				// $this->db->update("sku_stock");

				$queryinsert = 1;
			} else {
				$queryinsert = 0;
			}

			return $queryinsert;
			// return $this->db->last_query();

		}
	}

	public function insertToAktualD2($picking_order_aktual_d_id, $pallet_id)
	{
		$this->db->set("picking_order_aktual_d_2_id", "NewID()", FALSE);
		$this->db->set("picking_order_aktual_d_id", $picking_order_aktual_d_id);
		$this->db->set("pallet_id", $pallet_id);

		return $this->db->insert("picking_order_aktual_d_2");
	}

	public function UpdateQtypaletDetail($pallet_detail_id, $qty)
	{

		$getQty = $this->db->select("pallet_id, sku_stock_id, sku_stock_ambil")->from("pallet_detail")->where("pallet_detail_id", $pallet_detail_id)->get()->row();
		// $oldQty = $getQty->sku_stock_ambil == null ? 0 : (int)$getQty->sku_stock_ambil;

		// $this->db->set("sku_stock_ambil", (int)$qty + $oldQty);
		// $this->db->where("pallet_detail_id", $pallet_detail_id);
		// return $this->db->update("pallet_detail");

		return $this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_ambil', '$getQty->pallet_id', '$getQty->sku_stock_id', '$qty'");
	}

	public function Get_CountNumberBKB($picking_order_id)
	{
		$query = $this->db->query("SELECT
										picking_order_aktual_h.picking_order_id,
										picking_order_aktual_h.picking_order_aktual_h_id,
										picking_order_aktual_d.picking_order_aktual_d_id,
										picking_order_aktual_d.picking_order_plan_nourut
									FROM picking_order_aktual_h 
									LEFT JOIN picking_order_aktual_d
									ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
									WHERE picking_order_aktual_h.picking_order_id = '$picking_order_id'
									ORDER BY picking_order_aktual_d.picking_order_plan_nourut ASC");
		$no = $query->num_rows();

		return $no;
	}

	public function Get_BKBBulkByIdHeader($picking_order_id)
	{
		$query = $this->db->query("SELECT
									po.picking_order_id,
									po.picking_order_kode,
									po.picking_order_tanggal,
									fdjr.delivery_order_batch_id,
									fdjr.delivery_order_batch_kode,
									FORMAT(fdjr.delivery_order_batch_tanggal,'dd-MM-yyyy') AS delivery_order_batch_tanggal,
									FORMAT(fdjr.delivery_order_batch_tanggal_kirim,'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim,
									fdjr.karyawan_id,
									driver.karyawan_nama AS driver_nama,
									fdjr.kendaraan_id,
									kendaraan.kendaraan_nopol,
									kendaraan.kendaraan_model
									FROM picking_order po
									LEFT JOIN picking_list pl
									ON po.picking_list_id = pl.picking_list_id
									LEFT JOIN delivery_order_batch fdjr
									ON pl.delivery_order_batch_id = fdjr.delivery_order_batch_id
									LEFT JOIN karyawan driver
									ON driver.karyawan_id = fdjr.karyawan_id
									LEFT JOIN kendaraan
									ON kendaraan.kendaraan_id = fdjr.kendaraan_id
									WHERE po.picking_order_id = '$picking_order_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_BKBBulkById($picking_order_id, $cetak)
	{
		// $query = $this->db->query("SELECT
		// 							picking_order.picking_order_id,
		// 							picking_order_plan.picking_order_plan_id,
		// 							picking_order.depo_id,
		// 							picking_order.depo_detail_id,
		// 							depo_detail.depo_detail_nama,
		// 							ISNULL(rak.rak_nama, '') AS rak_nama,
		// 							depo.depo_nama,
		// 							picking_order.picking_order_kode,
		// 							FORMAT(picking_order.picking_order_tanggal, 'dd-MM-yyyy') AS picking_order_tanggal,
		// 							picking_order_plan.delivery_order_id,
		// 							delivery_order.delivery_order_kode,
		// 							delivery_order_batch.delivery_order_batch_kode,
		// 							delivery_order_batch.delivery_order_batch_tanggal_kirim,
		// 							picking_list.picking_list_kode,
		// 							FORMAT(picking_list.picking_list_tgl_kirim, 'dd-MM-yyyy') AS picking_list_tgl_kirim,
		// 							delivery_order.tipe_delivery_order_id,
		// 							ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') AS picking_order_plan_tipe,
		// 							picking_order.picking_order_keterangan,
		// 							picking_order.picking_order_status,
		// 							picking_order_plan.picking_order_plan_status,
		// 							principal.principle_kode AS principal,
		// 							sku.sku_kode,
		// 							sku.sku_nama_produk,
		// 							sku.sku_satuan,
		// 							sku.sku_kemasan,
		// 							picking_order_plan.sku_id,
		// 							picking_order_plan.sku_stock_id,
		// 							FORMAT(picking_order_plan.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
		// 							FORMAT(picking_order_aktual.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date_aktual,
		// 							picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil,
		// 							picking_order_aktual.sku_stock_qty_ambil AS sku_stock_qty_ambil_aktual,
		// 							CASE
		// 							WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
		// 							ELSE picking_order_plan.karyawan_id
		// 							END AS karyawan_id,
		// 							CASE
		// 							WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
		// 							ELSE picking_order_plan.karyawan_nama
		// 							END AS karyawan_nama
		// 						FROM picking_order
		// 						LEFT JOIN picking_order_plan
		// 							ON picking_order.picking_order_id = picking_order_plan.picking_order_id
		// 						LEFT JOIN (SELECT
		// 							picking_order_aktual_h.picking_order_id,
		// 							picking_order_aktual_h.karyawan_id,
		// 							picking_order_aktual_h.karyawan_nama,
		// 							picking_order_aktual_d.picking_order_plan_id,
		// 							picking_order_aktual_d.delivery_order_id,
		// 							picking_order_aktual_d.sku_id,
		// 							picking_order_aktual_d.sku_stock_id,
		// 							picking_order_aktual_d.sku_stock_expired_date,
		// 							SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
		// 						FROM picking_order_aktual_h
		// 						LEFT JOIN picking_order_aktual_d
		// 							ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
		// 						GROUP BY picking_order_aktual_h.picking_order_id,
		// 								picking_order_aktual_h.karyawan_id,
		// 								picking_order_aktual_h.karyawan_nama,
		// 								picking_order_aktual_d.picking_order_plan_id,
		// 								picking_order_aktual_d.delivery_order_id,
		// 								picking_order_aktual_d.sku_id,
		// 								picking_order_aktual_d.sku_stock_id,
		// 								picking_order_aktual_d.sku_stock_expired_date) picking_order_aktual
		// 							ON picking_order.picking_order_id = picking_order_aktual.picking_order_id
		// 							AND picking_order_aktual.picking_order_plan_id = picking_order_plan.picking_order_plan_id
		// 						LEFT JOIN picking_list
		// 							ON picking_list.picking_list_id = picking_order.picking_list_id
		// 						LEFT JOIN delivery_order
		// 							ON delivery_order.delivery_order_id = picking_order_aktual.delivery_order_id
		// 						LEFT JOIN delivery_order_batch
		// 							ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
		// 						LEFT JOIN sku_stock
		// 							ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
		// 						LEFT JOIN rak_sku
		// 							ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
		// 						LEFT JOIN rak
		// 							ON rak.rak_id = rak_sku.rak_id
		// 						LEFT JOIN SKU
		// 							ON SKU.sku_id = picking_order_plan.sku_id
		// 						LEFT JOIN sku_induk
		// 							ON SKU.sku_induk_id = sku_induk.sku_induk_id
		// 						LEFT JOIN principle principal
		// 							ON sku.principle_id = principal.principle_id
		// 						LEFT JOIN depo_detail
		// 							ON depo_detail.depo_detail_id = picking_order.depo_detail_id
		// 						LEFT JOIN depo
		// 							ON depo.depo_id = picking_order.depo_id
		// 						LEFT JOIN tipe_delivery_order
		// 							ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
		// 						WHERE picking_order.picking_order_id = '$picking_order_id' AND ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') = 'Bulk'
		// 						ORDER BY picking_order_plan.picking_order_plan_nourut ASC");

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		}

		// "ISNULL((SELECT
		// 								SUM(pk_aktual_d.sku_stock_qty_ambil)
		// 							FROM picking_order_aktual_d pk_aktual_d
		// 							WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id
		// 							AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id), 0) AS total_ambil,";

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									picking_order_plan.sku_stock_qty_ambil,
									0 AS total_ambil,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									pallet.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									pallet.batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN (SELECT DISTINCT
									rjd.rak_lajur_detail_nama,
									pd.sku_id,
									pd.sku_stock_id,
									pd.sku_stock_expired_date,
									rjd.rak_lajur_detail_id,
									pallet.pallet_kode,
									pd.batch_no,
									ISNULL(pd.sku_stock_qty, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) - ISNULL(pd.sku_stock_ambil, 0) AS stock
									FROM pallet
									LEFT JOIN pallet_detail pd
									ON pallet.pallet_id = pd.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									WHERE rjdp.rak_lajur_detail_id IS NOT NULL
									AND pallet.depo_id = '" . $this->session->userdata('depo_id') . "') AS pallet
									ON pallet.sku_stock_id = sku_stock.sku_stock_id
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									AND depo_detail_is_kirimulang = '0'
									$order_by");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_BKBBulkByIdComposite($picking_order_id, $data, $cetak)
	{
		$table_sementara = "";
		$table_temp = "";
		$kolom_temp = "";
		if ($data != null) {
			$union = " UNION ALL ";
			foreach ($data as $key => $value) {
				$table_sementara .= "SELECT '" . $value['sku_konversi_group'] . "' AS sku_konversi_group, '" . $value['composite'] . "' AS composite, '" . $value['Ratio'] . "' AS ratio ";

				if ($key < count($data) - 1) {
					$table_sementara .= $union;
				}
			}

			$table_temp = "LEFT JOIN (" . $table_sementara . ") composite 
			ON composite.sku_konversi_group = SKU.sku_konversi_group";

			$kolom_temp = "composite.ratio, composite.composite,";
		}

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		}

		// "ISNULL((SELECT
		// 								SUM(pk_aktual_d.sku_stock_qty_ambil)
		// 							FROM picking_order_aktual_d pk_aktual_d
		// 							WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id
		// 							AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id), 0) AS total_ambil,";

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									sku.sku_satuan,
  									sku.sku_konversi_faktor,
									picking_order_plan.sku_stock_qty_ambil * sku.sku_konversi_faktor As sku_qty_composite,
									picking_order_plan.sku_stock_qty_ambil,
									0 AS total_ambil,
									0 * sku.sku_konversi_faktor AS total_ambil_composite,
									$kolom_temp
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									pallet.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									pallet.batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN (SELECT DISTINCT
									rjd.rak_lajur_detail_nama,
									pd.sku_id,
									pd.sku_stock_id,
									pd.sku_stock_expired_date,
									rjd.rak_lajur_detail_id,
									pallet.pallet_kode,
									pd.batch_no,
									ISNULL(pd.sku_stock_qty, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) - ISNULL(pd.sku_stock_ambil, 0) AS stock
									FROM pallet
									LEFT JOIN pallet_detail pd
									ON pallet.pallet_id = pd.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									WHERE rjdp.rak_lajur_detail_id IS NOT NULL
									AND pallet.depo_id = '" . $this->session->userdata('depo_id') . "') AS pallet
									ON pallet.sku_stock_id = sku_stock.sku_stock_id
									$table_temp
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									AND depo_detail_is_kirimulang = '0'
									$order_by");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}
		return $query;

		// return $this->db->last_query();
	}

	public function Get_BKBBulkAktualById($picking_order_id, $cetak)
	{

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		}

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									picking_order_plan.sku_stock_qty_ambil,
									ISNULL(picking_order_aktual_d.sku_stock_qty_ambil, 0) AS total_ambil,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									rjd.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(picking_order_aktual_d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									sku_stock.sku_stock_batch_no AS batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN (SELECT
									picking_order_aktual_d_id,
									picking_order_plan_id,
									depo_detail_id,
									sku_id,
									sku_stock_id,
									sku_stock_expired_date,
									SUM(sku_stock_qty_ambil) AS sku_stock_qty_ambil
									FROM picking_order_aktual_d
									WHERE picking_order_aktual_h_id IN (SELECT
									picking_order_aktual_h_id
									FROM picking_order_aktual_h
									WHERE picking_order_id = '$picking_order_id')
									GROUP BY picking_order_aktual_d_id,
											picking_order_plan_id,
											depo_detail_id,
											sku_id,
											sku_stock_id,
											sku_stock_expired_date) picking_order_aktual_d
									ON picking_order_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id
									LEFT JOIN picking_order_aktual_d_2
									ON picking_order_aktual_d_2.picking_order_aktual_d_id = picking_order_aktual_d.picking_order_aktual_d_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_aktual_d.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN pallet
									ON pallet.pallet_id = picking_order_aktual_d_2.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									AND depo_detail_is_kirimulang = '0'
									$order_by");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}
		return $query;

		// return $this->db->last_query();
	}

	public function Get_BKBBulkAktualByIdComposite($picking_order_id, $data, $cetak)
	{
		$table_sementara = "";
		$table_temp = "";
		$kolom_temp = "";
		if ($data != null) {
			$union = " UNION ALL ";
			foreach ($data as $key => $value) {
				$table_sementara .= "SELECT '" . $value['sku_konversi_group'] . "' AS sku_konversi_group, '" . $value['composite'] . "' AS composite, '" . $value['Ratio'] . "' AS ratio ";

				if ($key < count($data) - 1) {
					$table_sementara .= $union;
				}
			}

			$table_temp = "LEFT JOIN (" . $table_sementara . ") composite 
			ON composite.sku_konversi_group = SKU.sku_konversi_group";

			$kolom_temp = "composite.ratio, composite.composite,";
		}

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		}

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									sku.sku_satuan,
  									sku.sku_konversi_faktor,
									picking_order_plan.sku_stock_qty_ambil * sku.sku_konversi_faktor As sku_qty_composite,
									picking_order_plan.sku_stock_qty_ambil,
									ISNULL(picking_order_aktual_d.sku_stock_qty_ambil, 0) AS total_ambil,
									ISNULL(picking_order_aktual_d.sku_stock_qty_ambil, 0) * sku.sku_konversi_faktor AS total_ambil_composite,
									$kolom_temp
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									rjd.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(picking_order_aktual_d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									sku_stock.sku_stock_batch_no AS batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN (SELECT
									picking_order_aktual_d_id,
									picking_order_plan_id,
									depo_detail_id,
									sku_id,
									sku_stock_id,
									sku_stock_expired_date,
									SUM(sku_stock_qty_ambil) AS sku_stock_qty_ambil
									FROM picking_order_aktual_d
									WHERE picking_order_aktual_h_id IN (SELECT
									picking_order_aktual_h_id
									FROM picking_order_aktual_h
									WHERE picking_order_id = '$picking_order_id')
									GROUP BY picking_order_aktual_d_id,
											picking_order_plan_id,
											depo_detail_id,
											sku_id,
											sku_stock_id,
											sku_stock_expired_date) picking_order_aktual_d
									ON picking_order_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id
									LEFT JOIN picking_order_aktual_d_2
									ON picking_order_aktual_d_2.picking_order_aktual_d_id = picking_order_aktual_d.picking_order_aktual_d_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_aktual_d.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN pallet
									ON pallet.pallet_id = picking_order_aktual_d_2.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									$table_temp
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									AND depo_detail_is_kirimulang = '0'
									$order_by");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}
		return $query;

		// return $this->db->last_query();
	}

	public function Get_BKBKirimUlangById($picking_order_id, $cetak)
	{

		// "ISNULL((SELECT
		// 								SUM(pk_aktual_d.sku_stock_qty_ambil)
		// 							FROM picking_order_aktual_d pk_aktual_d
		// 							WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id
		// 							AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id), 0) AS total_ambil,";

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		}

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									picking_order_plan.sku_stock_qty_ambil,
									0 AS total_ambil,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									pallet.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									pallet.batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN (SELECT DISTINCT
									rjd.rak_lajur_detail_nama,
									pd.sku_id,
									pd.sku_stock_id,
									pd.sku_stock_expired_date,
									rjd.rak_lajur_detail_id,
									pallet.pallet_kode,
									pd.batch_no,
									ISNULL(pd.sku_stock_qty, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) - ISNULL(pd.sku_stock_ambil, 0) AS stock
									FROM pallet
									LEFT JOIN pallet_detail pd
									ON pallet.pallet_id = pd.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									WHERE rjdp.rak_lajur_detail_id IS NOT NULL
									AND pallet.depo_id = '" . $this->session->userdata('depo_id') . "') AS pallet
									ON pallet.sku_stock_id = sku_stock.sku_stock_id
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									AND depo_detail_is_kirimulang = '1'
									$order_by");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_BKBKirimUlangByIdComposite($picking_order_id, $data, $cetak)
	{
		// "ISNULL((SELECT
		// 								SUM(pk_aktual_d.sku_stock_qty_ambil)
		// 							FROM picking_order_aktual_d pk_aktual_d
		// 							WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id
		// 							AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id), 0) AS total_ambil,";

		$table_sementara = "";
		$table_temp = "";
		$kolom_temp = "";
		if ($data != null) {
			$union = " UNION ALL ";
			foreach ($data as $key => $value) {
				$table_sementara .= "SELECT '" . $value['sku_konversi_group'] . "' AS sku_konversi_group, '" . $value['composite'] . "' AS composite, '" . $value['Ratio'] . "' AS ratio ";

				if ($key < count($data) - 1) {
					$table_sementara .= $union;
				}
			}

			$table_temp = "LEFT JOIN (" . $table_sementara . ") composite 
			ON composite.sku_konversi_group = SKU.sku_konversi_group";

			$kolom_temp = "composite.ratio, composite.composite,";
		}

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		}

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									sku.sku_satuan,
  									sku.sku_konversi_faktor,
									picking_order_plan.sku_stock_qty_ambil * sku.sku_konversi_faktor As sku_qty_composite,
									picking_order_plan.sku_stock_qty_ambil,
									0 AS total_ambil,
									0 * sku.sku_konversi_faktor AS total_ambil_composite,
									$kolom_temp
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									pallet.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									pallet.batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN (SELECT DISTINCT
									rjd.rak_lajur_detail_nama,
									pd.sku_id,
									pd.sku_stock_id,
									pd.sku_stock_expired_date,
									rjd.rak_lajur_detail_id,
									pallet.pallet_kode,
									pd.batch_no,
									ISNULL(pd.sku_stock_qty, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) - ISNULL(pd.sku_stock_ambil, 0) AS stock
									FROM pallet
									LEFT JOIN pallet_detail pd
									ON pallet.pallet_id = pd.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									WHERE rjdp.rak_lajur_detail_id IS NOT NULL
									AND pallet.depo_id = '" . $this->session->userdata('depo_id') . "') AS pallet
									ON pallet.sku_stock_id = sku_stock.sku_stock_id
									$table_temp
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									AND depo_detail_is_kirimulang = '1'
									$order_by");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;

		// return $this->db->last_query();
	}

	public function Get_BKBKirimUlangAktualById($picking_order_id, $cetak)
	{

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		}

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									picking_order_plan.sku_stock_qty_ambil,
									ISNULL(picking_order_aktual_d.sku_stock_qty_ambil, 0) AS total_ambil,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									rjd.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(picking_order_aktual_d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									sku_stock.sku_stock_batch_no AS batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN (SELECT
									picking_order_aktual_d_id,
									picking_order_plan_id,
									depo_detail_id,
									sku_id,
									sku_stock_id,
									sku_stock_expired_date,
									SUM(sku_stock_qty_ambil) AS sku_stock_qty_ambil
									FROM picking_order_aktual_d
									WHERE picking_order_aktual_h_id IN (SELECT
									picking_order_aktual_h_id
									FROM picking_order_aktual_h
									WHERE picking_order_id = '$picking_order_id')
									GROUP BY picking_order_aktual_d_id,
											picking_order_plan_id,
											depo_detail_id,
											sku_id,
											sku_stock_id,
											sku_stock_expired_date) picking_order_aktual_d
									ON picking_order_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id
									LEFT JOIN picking_order_aktual_d_2
									ON picking_order_aktual_d_2.picking_order_aktual_d_id = picking_order_aktual_d.picking_order_aktual_d_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN pallet
									ON pallet.pallet_id = picking_order_aktual_d_2.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									AND depo_detail_is_kirimulang = '1'
									$order_by");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}
		return $query;

		// return $this->db->last_query();
	}

	public function Get_BKBKirimUlangAktualByIdComposite($picking_order_id, $data, $cetak)
	{
		$table_sementara = "";
		$table_temp = "";
		$kolom_temp = "";
		if ($data != null) {
			$union = " UNION ALL ";
			foreach ($data as $key => $value) {
				$table_sementara .= "SELECT '" . $value['sku_konversi_group'] . "' AS sku_konversi_group, '" . $value['composite'] . "' AS composite, '" . $value['Ratio'] . "' AS ratio ";

				if ($key < count($data) - 1) {
					$table_sementara .= $union;
				}
			}

			$table_temp = "LEFT JOIN (" . $table_sementara . ") composite 
			ON composite.sku_konversi_group = SKU.sku_konversi_group";

			$kolom_temp = "composite.ratio, composite.composite,";
		}

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		}

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									sku.sku_satuan,
  									sku.sku_konversi_faktor,
									picking_order_plan.sku_stock_qty_ambil * sku.sku_konversi_faktor As sku_qty_composite,
									picking_order_plan.sku_stock_qty_ambil,
									ISNULL(picking_order_aktual_d.sku_stock_qty_ambil, 0) AS total_ambil,
									ISNULL(picking_order_aktual_d.sku_stock_qty_ambil, 0) * sku.sku_konversi_faktor AS total_ambil_composite,
									$kolom_temp
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									rjd.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(picking_order_aktual_d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									sku_stock.sku_stock_batch_no AS batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN (SELECT
									picking_order_aktual_d_id,
									picking_order_plan_id,
									depo_detail_id,
									sku_id,
									sku_stock_id,
									sku_stock_expired_date,
									SUM(sku_stock_qty_ambil) AS sku_stock_qty_ambil
									FROM picking_order_aktual_d
									WHERE picking_order_aktual_h_id IN (SELECT
									picking_order_aktual_h_id
									FROM picking_order_aktual_h
									WHERE picking_order_id = '$picking_order_id')
									GROUP BY picking_order_aktual_d_id,
											picking_order_plan_id,
											depo_detail_id,
											sku_id,
											sku_stock_id,
											sku_stock_expired_date) picking_order_aktual_d
									ON picking_order_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id
									LEFT JOIN picking_order_aktual_d_2
									ON picking_order_aktual_d_2.picking_order_aktual_d_id = picking_order_aktual_d.picking_order_aktual_d_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN pallet
									ON pallet.pallet_id = picking_order_aktual_d_2.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									$table_temp
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									AND depo_detail_is_kirimulang = '1'
									$order_by");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}
		return $query;

		// return $this->db->last_query();
	}

	public function insertSKUKonversiTemp($sku_konversi_temp_id, $sku_id, $sku_expired_date, $sku_qty, $sku_qty_composite, $total_ambil, $total_ambil_composite, $batch_no, $tipe)
	{
		if ($tipe == 'aktual') {
			$this->db->set("sku_qty", $sku_qty);
			$this->db->set("sku_qty_composite", intval($sku_qty));
		} else {
			$this->db->set("sku_qty", $sku_qty);
			$this->db->set("sku_qty_composite", intval($sku_qty_composite));
		}

		$this->db->set("sku_konversi_temp_id", $sku_konversi_temp_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_expired_date", $sku_expired_date);
		$this->db->set("batch_no", $batch_no);

		$this->db->insert("sku_konversi_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function execProsesKonversiComposite($sku_konversi_temp_id)
	{
		$query = $this->db->query("Exec proses_konversi_composite '$sku_konversi_temp_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_BKBStandarById($picking_order_id)
	{
		// $query = $this->db->query("SELECT
		// 							picking_order.picking_order_id,
		// 							picking_order.picking_order_kode,
		// 							FORMAT(picking_order.picking_order_tanggal, 'dd-MM-yyyy') AS picking_order_tanggal,
		// 							picking_list.picking_list_id,
		// 							picking_list.picking_list_kode,
		// 							FORMAT(picking_list.picking_list_tgl_kirim, 'dd-MM-yyyy') AS picking_list_tgl_kirim,
		// 							picking_order_aktual.delivery_order_id,
		// 							delivery_order.delivery_order_kode,
		// 							delivery_order.delivery_order_batch_id,
		// 							delivery_order_batch.delivery_order_batch_kode,
		// 							picking_order_aktual.picking_order_aktual_h_id,
		// 							picking_order_aktual.picking_order_aktual_kode,
		// 							FORMAT(picking_order_aktual.picking_order_aktual_tgl, 'dd-MM-yyyy') AS picking_order_aktual_tgl,
		// 							delivery_order.tipe_delivery_order_id,
		// 							tipe_delivery_order.tipe_delivery_order_nama AS picking_order_plan_tipe,
		// 							picking_order.picking_order_status,
		// 							picking_order_plan.picking_order_plan_status,
		// 							picking_order_plan.picking_order_plan_nourut,
		// 							principal.principle_kode AS principal,
		// 							sku.sku_kode,
		// 							sku.sku_nama_produk,
		// 							sku.sku_satuan,
		// 							sku.sku_kemasan,
		// 							picking_order_plan.sku_id,
		// 							picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil,
		// 							picking_order_aktual.sku_stock_qty_ambil AS sku_stock_qty_ambil_aktual,
		// 							picking_order_plan.sku_stock_id,
		// 							FORMAT(picking_order_plan.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
		// 							picking_order_aktual.sku_stock_id,
		// 							FORMAT(picking_order_aktual.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date_aktual,
		// 							CASE
		// 							WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
		// 							ELSE picking_order_plan.karyawan_id
		// 							END AS karyawan_id,
		// 							CASE
		// 							WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
		// 							ELSE picking_order_plan.karyawan_nama
		// 							END AS karyawan_nama,
		// 							delivery_order_batch.karyawan_id AS driver_id,
		// 							driver.karyawan_nama AS driver_nama
		// 						FROM picking_order
		// 						LEFT JOIN picking_order_plan
		// 							ON picking_order.picking_order_id = picking_order_plan.picking_order_id
		// 						LEFT JOIN (SELECT
		// 							picking_order_aktual_h.picking_order_aktual_h_id,
		// 							picking_order_aktual_h.picking_order_id,
		// 							picking_order_aktual_h.picking_order_aktual_tgl,
		// 							picking_order_aktual_h.karyawan_id,
		// 							picking_order_aktual_h.karyawan_nama,
		// 							picking_order_aktual_h.picking_order_aktual_kode,
		// 							picking_order_aktual_d.picking_order_plan_id,
		// 							picking_order_aktual_d.delivery_order_id,
		// 							picking_order_aktual_d.sku_id,
		// 							picking_order_aktual_d.sku_stock_id,
		// 							picking_order_aktual_d.sku_stock_expired_date,
		// 							SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
		// 						FROM picking_order_aktual_h
		// 						LEFT JOIN picking_order_aktual_d
		// 							ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
		// 						GROUP BY picking_order_aktual_h.picking_order_aktual_h_id,
		// 								picking_order_aktual_h.picking_order_id,
		// 								picking_order_aktual_h.picking_order_aktual_tgl,
		// 								picking_order_aktual_h.karyawan_id,
		// 								picking_order_aktual_h.karyawan_nama,
		// 								picking_order_aktual_h.picking_order_aktual_kode,
		// 								picking_order_aktual_d.picking_order_plan_id,
		// 								picking_order_aktual_d.delivery_order_id,
		// 								picking_order_aktual_d.sku_id,
		// 								picking_order_aktual_d.sku_stock_id,
		// 								picking_order_aktual_d.sku_stock_expired_date) picking_order_aktual
		// 							ON picking_order.picking_order_id = picking_order_aktual.picking_order_id
		// 							AND picking_order_aktual.picking_order_plan_id = picking_order_plan.picking_order_plan_id
		// 						LEFT JOIN picking_list
		// 							ON picking_list.picking_list_id = picking_order.picking_list_id
		// 						LEFT JOIN delivery_order
		// 							ON delivery_order.delivery_order_id = picking_order_aktual.delivery_order_id
		// 						LEFT JOIN delivery_order_batch
		// 							ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
		// 						LEFT JOIN sku_stock
		// 							ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
		// 						LEFT JOIN rak_sku
		// 							ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
		// 						LEFT JOIN rak
		// 							ON rak.rak_id = rak_sku.rak_id
		// 						LEFT JOIN SKU
		// 							ON SKU.sku_id = picking_order_plan.sku_id
		// 						LEFT JOIN sku_induk
		// 							ON SKU.sku_induk_id = sku_induk.sku_induk_id
		// 						LEFT JOIN principle principal
		// 							ON sku.principle_id = principal.principle_id
		// 						LEFT JOIN depo_detail
		// 							ON depo_detail.depo_detail_id = picking_order.depo_detail_id
		// 						LEFT JOIN karyawan driver
		// 							ON delivery_order_batch.karyawan_id = driver.karyawan_id
		// 						LEFT JOIN depo
		// 							ON depo.depo_id = picking_order.depo_id
		// 						LEFT JOIN tipe_delivery_order
		// 							ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
		// 						WHERE picking_order.picking_order_id = '$picking_order_id' AND tipe_delivery_order.tipe_delivery_order_nama = 'Standar'
		// 						ORDER BY picking_order_plan.picking_order_plan_nourut ASC");

		$query = $this->db->query("SELECT
																delivery_order.delivery_order_kode,
																sku.sku_kode,
																sku.sku_nama_produk,
																picking_order_plan.sku_stock_qty_ambil,
																ISNULL((SELECT SUM(pk_aktual_d.sku_stock_qty_ambil) FROM picking_order_aktual_d pk_aktual_d WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id),0) AS total_ambil,
																depo_detail.depo_detail_nama,
																depo_detail.rak_lajur_detail_nama,
																depo_detail.pallet_kode,
																sku.sku_nama_produk as detail_sku,
																depo_detail.stock as sku_stock_qty
															FROM picking_order
															LEFT JOIN picking_order_plan
																ON picking_order.picking_order_id = picking_order_plan.picking_order_id
															LEFT JOIN sku_stock
																ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
															LEFT JOIN delivery_order
																ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
															LEFT JOIN SKU
																ON SKU.sku_id = picking_order_plan.sku_id
															LEFT JOIN principle principal
																ON sku.principle_id = principal.principle_id
															LEFT JOIN tipe_delivery_order
																ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
															LEFT JOIN ( SELECT  
																			depo_detail.depo_detail_id,
																			depo_detail.depo_detail_nama,
																			rjd.rak_lajur_detail_nama,
																			pd.sku_id,
																			pd.sku_stock_expired_date,
																			rjd.rak_lajur_detail_id,
																			pallet.pallet_kode,
																			ISNULL(pd.sku_stock_qty, 0)  + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) - ISNULL(pd.sku_stock_ambil, 0) as stock
																		FROM depo_detail
																		LEFT JOIN rak ON rak.depo_detail_id = depo_detail.depo_detail_id
																		left join rak_lajur_detail rjd on rak.rak_id = rjd.rak_id
																		left join rak_lajur_detail_pallet rjdp on rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
																		left join pallet on rjdp.pallet_id = pallet.pallet_id
																		left join pallet_detail pd on pallet.pallet_id = pd.pallet_id
																		WHERE rjdp.rak_lajur_detail_id is not null
																	) as depo_detail
																ON sku_stock.depo_detail_id = depo_detail.depo_detail_id
																AND sku.sku_id = depo_detail.sku_id
																AND FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') = FORMAT(depo_detail.sku_stock_expired_date, 'dd-MM-yyyy')
															WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') = 'Standar' 
																AND picking_order.picking_order_id = '$picking_order_id'
																AND depo_detail.stock != 0");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_CetakLabel($picking_order_id)
	{
		$query = $this->db->query("SELECT
										picking_order.picking_order_id,
										picking_order.picking_order_kode,
										FORMAT(picking_order.picking_order_tanggal, 'dd-MM-yyyy') AS picking_order_tanggal,
										picking_list.picking_list_id,
										picking_list.picking_list_kode,
										FORMAT(picking_list.picking_list_tgl_kirim, 'dd-MM-yyyy') AS picking_list_tgl_kirim,
										picking_order_aktual.delivery_order_id,
										delivery_order.delivery_order_kode,
										delivery_order.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										picking_order_aktual.picking_order_aktual_h_id,
										picking_order_aktual.picking_order_aktual_kode,
										FORMAT(picking_order_aktual.picking_order_aktual_tgl, 'dd-MM-yyyy') AS picking_order_aktual_tgl,
										delivery_order.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias AS picking_order_plan_tipe,
										picking_order.picking_order_status,
										picking_order_plan.picking_order_plan_status,
										picking_order_plan.picking_order_plan_nourut,
										principal.principle_kode AS principal,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_satuan,
										sku.sku_kemasan,
										picking_order_plan.sku_id,
										picking_order_plan.sku_stock_qty_ambil AS sku_stock_qty_ambil,
										picking_order_aktual.sku_stock_qty_ambil AS sku_stock_qty_ambil_aktual,
										picking_order_plan.sku_stock_id,
										FORMAT(picking_order_plan.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
										picking_order_aktual.sku_stock_id,
										FORMAT(picking_order_aktual.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date_aktual,
										CASE
										WHEN picking_order_aktual.karyawan_id IS NOT NULL THEN picking_order_aktual.karyawan_id
										ELSE picking_order_plan.karyawan_id
										END AS karyawan_id,
										CASE
										WHEN picking_order_aktual.karyawan_nama IS NOT NULL THEN picking_order_aktual.karyawan_nama
										ELSE picking_order_plan.karyawan_nama
										END AS karyawan_nama,
										delivery_order_batch.karyawan_id AS driver_id,
										driver.karyawan_nama AS driver_nama
									FROM picking_order
									LEFT JOIN picking_order_plan
										ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN (SELECT
										picking_order_aktual_h.picking_order_aktual_h_id,
										picking_order_aktual_h.picking_order_id,
										picking_order_aktual_h.picking_order_aktual_tgl,
										picking_order_aktual_h.karyawan_id,
										picking_order_aktual_h.karyawan_nama,
										picking_order_aktual_h.picking_order_aktual_kode,
										picking_order_aktual_d.picking_order_plan_id,
										picking_order_aktual_d.delivery_order_id,
										picking_order_aktual_d.sku_id,
										picking_order_aktual_d.sku_stock_id,
										picking_order_aktual_d.sku_stock_expired_date,
										SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
									FROM picking_order_aktual_h
									LEFT JOIN picking_order_aktual_d
										ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
									GROUP BY picking_order_aktual_h.picking_order_aktual_h_id,
											picking_order_aktual_h.picking_order_id,
											picking_order_aktual_h.picking_order_aktual_tgl,
											picking_order_aktual_h.karyawan_id,
											picking_order_aktual_h.karyawan_nama,
											picking_order_aktual_h.picking_order_aktual_kode,
											picking_order_aktual_d.picking_order_plan_id,
											picking_order_aktual_d.delivery_order_id,
											picking_order_aktual_d.sku_id,
											picking_order_aktual_d.sku_stock_id,
											picking_order_aktual_d.sku_stock_expired_date) picking_order_aktual
										ON picking_order.picking_order_id = picking_order_aktual.picking_order_id
										AND picking_order_aktual.picking_order_plan_id = picking_order_plan.picking_order_plan_id
									LEFT JOIN picking_list
										ON picking_list.picking_list_id = picking_order.picking_list_id
									LEFT JOIN delivery_order
										ON delivery_order.delivery_order_id = picking_order_aktual.delivery_order_id
									LEFT JOIN delivery_order_batch
										ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN sku_stock
										ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN rak_sku
										ON sku_stock.sku_stock_id = rak_sku.sku_stock_id
									LEFT JOIN rak
										ON rak.rak_id = rak_sku.rak_id
									LEFT JOIN SKU
										ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN sku_induk
										ON SKU.sku_induk_id = sku_induk.sku_induk_id
									LEFT JOIN principle principal
										ON sku.principle_id = principal.principle_id
									LEFT JOIN depo_detail
										ON depo_detail.depo_detail_id = picking_order.depo_detail_id
									LEFT JOIN karyawan driver
										ON delivery_order_batch.karyawan_id = driver.karyawan_id
									LEFT JOIN depo
										ON depo.depo_id = picking_order.depo_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE picking_order.picking_order_id = '$picking_order_id' AND tipe_delivery_order.tipe_delivery_order_nama = 'Standar'
									ORDER BY picking_order_plan.picking_order_plan_nourut ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Insert_Channel(
		$channel_nama,
		$channel_jenis,
		$channel_grup,
		$channel_is_sppbr,
		$channel_flag_ecomm,
		$channel_is_aktif,
		$S_UserName,
		$S_UserID
	) {
		$this->db->set("channel_id", "NewID()", FALSE);
		$this->db->set("channel_nama", $channel_nama);
		$this->db->set("channel_jenis", $channel_jenis);
		$this->db->set("channel_grup", $channel_grup);
		$this->db->set("channel_is_sppbr", $channel_is_sppbr);
		$this->db->set("channel_flag_ecomm", $channel_flag_ecomm);

		$this->db->set("channel_is_aktif", $channel_is_aktif);

		$this->db->insert("channel");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Update_PickingOrder($picking_order_id, $picking_order_status, $picking_order_keterangan)
	{
		$this->db->set("picking_order_status", $picking_order_status);
		$this->db->set("picking_order_keterangan", $picking_order_keterangan);

		$this->db->where("picking_order_id", $picking_order_id);

		$this->db->update("picking_order");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Update_PickingOrderPlan($picking_order_id, $picking_order_plan_status, $karyawan_id, $karyawan_nama, $standar, $picking_order_plan_id)
	{

		if ($karyawan_id != "AD030BE3-7D9E-4A66-B985-D6085F623DA2") {
			if ($standar != 0) {
				$this->db->set("karyawan_id", $karyawan_id);
				$this->db->set("karyawan_nama", $karyawan_nama);
				$this->db->where_in("picking_order_plan_id", $picking_order_plan_id);
				$this->db->update("picking_order_plan");
			}
		}

		$this->db->set("picking_order_plan_status", $picking_order_plan_status);
		$this->db->where("picking_order_id", $picking_order_id);

		return $this->db->update("picking_order_plan");

		// $affectedrows = $this->db->affected_rows();
		// if ($affectedrows > 0) {
		// 	$queryupdate = true;
		// } else {
		// 	$queryupdate = false;
		// }

		// return $queryupdate;
	}

	public function Update_DilaksanakanPickingOrder($picking_order_id, $picking_list_id, $picking_order_status, $picking_order_keterangan)
	{
		$query = $this->db->query("SELECT * FROM picking_list_detail WHERE picking_list_id = '$picking_list_id' ");
		foreach ($query->result() as $row) {
			//insert delivery_order_progress
			$this->db->set("delivery_order_progress_id", "NewID()", FALSE);
			$this->db->set("delivery_order_id", $row->delivery_order_id);
			$this->db->set("status_progress_id", "48B36DFE-37B0-424B-B456-A413F4EC9779");
			$this->db->set("status_progress_nama", "in progress pick up item");
			$this->db->set("delivery_order_progress_create_who", $this->session->userdata('pengguna_username'));
			$this->db->set("delivery_order_progress_create_tgl", "GETDATE()", FALSE);

			$this->db->insert("delivery_order_progress");

			//update delivery_order
			$this->db->set("delivery_order_status", "in progress pick up item");
			$this->db->where("delivery_order_id", $row->delivery_order_id);

			$this->db->update("delivery_order");
		}

		//update delivery_order_batch
		$this->db->set("delivery_order_batch_status", "in progress pick up item");
		$this->db->where("picking_order_id", $picking_order_id);

		$this->db->update("delivery_order_batch");

		//update picking_order
		$this->db->set("picking_order_status", $picking_order_status);
		$this->db->set("picking_order_keterangan", $picking_order_keterangan);

		$this->db->where("picking_order_id", $picking_order_id);

		$this->db->update("picking_order");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Update_SelesaiPickingOrder($picking_order_id, $picking_list_id, $picking_order_status, $picking_order_keterangan)
	{
		//update picking_order
		$this->db->set("picking_order_status", $picking_order_status);
		$this->db->set("picking_order_keterangan", $picking_order_keterangan);

		$this->db->where("picking_order_id", $picking_order_id);

		$this->db->update("picking_order");

		//update delivery_order_batch
		$this->db->set("delivery_order_batch_status", 'pick up item confirmed');
		$this->db->where("picking_order_id", $picking_order_id);

		$this->db->update("delivery_order_batch");

		//update delivery_order
		$query = $this->db->query("SELECT * FROM picking_list_detail WHERE picking_list_id = '$picking_list_id' ");

		foreach ($query->result() as $row) {

			//insert delivery_order_progress
			$this->db->set("delivery_order_progress_id", "NewID()", FALSE);
			$this->db->set("delivery_order_id", $row->delivery_order_id);
			$this->db->set("status_progress_id", "746C18C9-820B-4B3F-8399-0F9CEDF434B5");
			$this->db->set("status_progress_nama", "pick up item confirmed");
			$this->db->set("delivery_order_progress_create_who", $this->session->userdata('pengguna_username'));
			$this->db->set("delivery_order_progress_create_tgl", "GETDATE()", FALSE);

			$this->db->insert("delivery_order_progress");

			//update delivery_order
			$this->db->set("delivery_order_status", "pick up item confirmed");
			$this->db->where("delivery_order_id", $row->delivery_order_id);

			$this->db->update("delivery_order");
		}

		//update picking_list
		$this->db->set("picking_list_status", "Close");
		$this->db->where("picking_list_id", $picking_list_id);

		$this->db->update("picking_list");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = true;
		} else {
			$queryupdate = false;
		}

		return $queryupdate;
	}

	public function Update_Channel(
		$channel_id,
		$channel_nama,
		$channel_jenis,
		$channel_grup,
		$channel_is_sppbr,
		$channel_flag_ecomm,
		$channel_is_aktif,
		$S_UserName,
		$S_UserID
	) {
		$this->db->set("channel_nama", $channel_nama);
		$this->db->set("channel_jenis", $channel_jenis);
		$this->db->set("channel_grup", $channel_grup);
		$this->db->set("channel_is_sppbr", $channel_is_sppbr);
		$this->db->set("channel_flag_ecomm", $channel_flag_ecomm);
		$this->db->set("channel_is_aktif", $channel_is_aktif);

		$this->db->where("channel_id", $channel_id);

		$this->db->update("Channel");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Delete_Channel($channel_id)
	{
		$this->db->trans_begin();

		$this->db->where("channel_id", $channel_id);

		$this->db->delete("channel");

		$error = $this->db->error();

		if ($error["message"] != "" || $error["message"] != null)
		//if( $error["message"] != "" && $error["message"] != null )
		{
			$res = $error["message"]; // Error

			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();

			$affectedrows = $this->db->affected_rows();
			if (abs($affectedrows) > 0) {
				$res = 1; // Success
			} else {
				$res = 0; // Success
			}
		}

		return $res;
	}

	public function Get_TipePB()
	{
		$this->db->select("*")
			->from("tipe_delivery_order")
			->order_by("tipe_delivery_order_alias");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetLocationRak($depo_detail_id, $sku_id, $expired_date)
	{

		return $this->db->query("SELECT distinct 
															rjd.rak_lajur_detail_id as id,
															rak_lajur.rak_lajur_nama as nama, 
															rjd.rak_lajur_detail_nama as sub_nama, 
															rjdp.rak_lajur_detail_id as chk
														from rak
														left join rak_lajur on rak.rak_id = rak_lajur.rak_id
														left join rak_lajur_detail rjd on rak_lajur.rak_lajur_id = rjd.rak_lajur_id
														left join rak_lajur_detail_pallet rjdp on rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
														left join pallet on rjdp.pallet_id = pallet.pallet_id
														left join pallet_detail pd on pallet.pallet_id = pd.pallet_id
														where rak.depo_id = '" . $this->session->userdata('depo_id') . "'
															and rak.depo_detail_id = '$depo_detail_id'
															and pd.sku_id = '$sku_id'
															and format(pd.sku_stock_expired_date, 'dd-MM-yyyy') = '$expired_date'
															and rjdp.rak_lajur_detail_id is not null 
														order by rak_lajur.rak_lajur_nama, rjd.rak_lajur_detail_nama ASC")->result_array();

		// return $this->db->query("SELECT distinct 
		// 													rjd.rak_lajur_detail_id as id,
		// 													rak_lajur.rak_lajur_nama as nama, 
		// 													rjd.rak_lajur_detail_nama as sub_nama, 
		// 													rjdp.rak_lajur_detail_id as chk,
		// 													rjdp.pallet_id
		// 												from rak
		// 												left join rak_lajur on rak.rak_id = rak_lajur.rak_id
		// 												left join rak_lajur_detail rjd on rak_lajur.rak_lajur_id = rjd.rak_lajur_id
		// 												left join rak_lajur_detail_pallet rjdp on rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
		// 												left join pallet on rjdp.pallet_id = pallet.pallet_id
		// 												left join pallet_detail pd on pallet.pallet_id = pd.pallet_id
		// 												where rak.depo_detail_id = '$depo_detail_id'
		// 													and pd.sku_id = '$sku_id'
		// 													and format(pd.sku_stock_expired_date, 'dd-MM-yyyy') = '$expired_date'
		// 													and rjdp.rak_lajur_detail_id is not null 
		// 												order by rak_lajur.rak_lajur_nama ASC")->result_array();
	}

	public function GetDetailRakPalletById($sku_kode, $expiredDate, $id, $sameSKU)
	{

		$whereSameSKU = ($sameSKU == 1) ? "AND sku.sku_kode = '$sku_kode' AND format(pd.sku_stock_expired_date, 'dd-MM-yyyy') = '$expiredDate'" : "";

		return $this->db->query("SELECT 
													pallet.pallet_id,
													pj.pallet_jenis_nama,
													pallet.pallet_kode,
													pallet.pallet_id as p_id,
													pd.pallet_detail_id,
													format(pd.sku_stock_expired_date, 'dd-MM-yyyy') as sku_stock_expired_date,
													ISNULL(pd.sku_stock_qty, 0)  + ISNULL(pd.sku_stock_in, 0) + ISNULL(pd.sku_stock_terima, 0) - ISNULL(pd.sku_stock_out, 0) - ISNULL(pd.sku_stock_ambil, 0) as sku_stock_qty,
													sku.sku_kode,
													sku.sku_nama_produk
												FROM rak_lajur_detail rjd
												LEFT JOIN rak_lajur_detail_pallet rjdp ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
												LEFT JOIN pallet ON rjdp.pallet_id = pallet.pallet_id
												LEFT JOIN pallet_jenis pj ON pallet.pallet_jenis_id = pj.pallet_jenis_id
												LEFT JOIN pallet_detail pd ON pallet.pallet_id = pd.pallet_id
												LEFT JOIN sku ON pd.sku_id = sku.sku_id
												LEFT JOIN penerimaan_tipe pt ON pd.penerimaan_tipe_id = pt.penerimaan_tipe_id
												WHERE rjd.rak_lajur_detail_id = '$id' $whereSameSKU")->result_array();
	}

	public function CheckScanPalletInRak($depo_detail_id, $nama_rak, $kode, $typeInput, $palletId, $skuKode)
	{

		if ($typeInput == "non-pilih") {
			return $this->db->select("*")
				->from("rak")
				->join("rak_lajur_detail rjd", "rak.rak_id = rjd.rak_id", "left")
				->join("rak_lajur_detail_pallet rjdp", "rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id", "left")
				->join("pallet p", "rjdp.pallet_id = p.pallet_id", "left")
				->where("p.depo_id", $this->session->userdata('depo_id'))
				->where("p.pallet_kode", $kode)
				->get()->row();
		}

		// if ($typeInput == "pilih") {
		// 	return $this->db->select("pallet.pallet_id, pallet.pallet_kode, pallet_jenis.pallet_jenis_kode, depo_detail.depo_detail_nama")
		// 		->from("pallet")
		// 		->join("rak_lajur_detail", "pallet.rak_lajur_detail_id = rak_lajur_detail.rak_lajur_detail_id", "left")
		// 		->join("rak", "rak_lajur_detail.rak_id = rak.rak_id", "left")
		// 		->join("depo_detail", "rak.depo_detail_id = depo_detail.depo_detail_id", "left")
		// 		->join("pallet_jenis", "pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id", "left")
		// 		->where("pallet.depo_id", $this->session->userdata('depo_id'))
		// 		->where("pallet.pallet_kode", $kode)
		// 		->get()->row();
		// }

		if ($typeInput == "scan-sku") {

			$getSkuIdByKodeSku = $this->db->select("sku_id")->from('sku')->where('sku_kode', $skuKode)->get()->row()->sku_id;

			$palletId = $this->db->select("pallet_id")->from('pallet')->where('pallet_kode', $kode)->get()->row()->pallet_id;

			$queryCheck = $this->db->select("pallet_detail.pallet_id,rld.rak_lajur_detail_nama,
												pallet_detail.pallet_id,
												pallet_detail.pallet_detail_id,
												pallet_detail.sku_id,
												pallet_detail.sku_stock_id,
												FORMAT(pallet_detail.sku_stock_expired_date, 'dd-MM-yyyy') as sku_stock_expired_date,
												ISNULL(pallet_detail.sku_stock_qty, 0) - ISNULL(pallet_detail.sku_stock_ambil, 0) + ISNULL(pallet_detail.sku_stock_in, 0) - ISNULL(pallet_detail.sku_stock_out, 0) + ISNULL(pallet_detail.sku_stock_terima, 0) as qty_available,
												sku.sku_kode,
												sku.sku_nama_produk,
												sku.sku_satuan,
												sku.sku_kemasan")
				->from("pallet_detail")
				->join("pallet", "pallet_detail.pallet_id = pallet.pallet_id", "left")
				->join("rak_lajur_detail_pallet rjdp", "rjdp.pallet_id = pallet.pallet_id", "left")
				->join("rak_lajur_detail rld", "rld.rak_lajur_detail_id = rjdp.rak_lajur_detail_id", "inner")
				->join("sku", "pallet_detail.sku_id = sku.sku_id", "left")
				->where("pallet.pallet_kode", $kode)
				->where("pallet_detail.sku_id", $getSkuIdByKodeSku)
				->get()->result();

			!empty($queryCheck) ? $response = ['pallet_id' => $palletId, 'data' => $queryCheck] : $response = [];

			return $response;

			// return $this->db->select("pallet_detail.pallet_id,
			// 													pallet_detail.pallet_detail_id,
			// 													pallet_detail.sku_id,
			// 													pallet_detail.sku_stock_id,
			// 													FORMAT(pallet_detail.sku_stock_expired_date, 'dd-MM-yyyy') as sku_stock_expired_date,
			// 													ISNULL(pallet_detail.sku_stock_qty, 0) - ISNULL(pallet_detail.sku_stock_ambil, 0) + ISNULL(pallet_detail.sku_stock_in, 0) - ISNULL(pallet_detail.sku_stock_out, 0) + ISNULL(pallet_detail.sku_stock_terima, 0) as qty_available,
			// 													sku.sku_kode,
			// 													sku.sku_nama_produk,
			// 													sku.sku_satuan,
			// 													sku.sku_kemasan")
			// 	->from("pallet_detail")
			// 	->join("sku", "pallet_detail.sku_id = sku.sku_id", "left")
			// 	->where("pallet_detail.pallet_id", $palletId)
			// 	->where("sku.sku_kode", $kode)
			// 	->get()->row();
		}
	}

	public function GetDataPickingOrderPlanDByPoId($picking_order_id)
	{
		return $this->db->select("poad.*")
			->from("picking_order po")
			->join("picking_order_plan pop", "po.picking_order_id = pop.picking_order_id", "left")
			->join("picking_order_aktual_d poad", "pop.picking_order_plan_id = poad.picking_order_plan_id", "left")
			->where("po.picking_order_id", $picking_order_id)
			->where("picking_order_aktual_d_id !=", NULL)
			->get()->result();
	}
	public function DeleteRakLajurDetailPalletByPalletId($pallet_id)
	{
		$this->db->where('pallet_id', $pallet_id);
		return $this->db->delete('rak_lajur_detail_pallet');
	}

	public function updateDataIsTakeInPickingOrderPlan($picking_order_plan_id)
	{
		$this->db->set("is_take", 1);

		$this->db->where("picking_order_plan_id", $picking_order_plan_id);

		return $this->db->update("picking_order_plan");
	}

	public function CheckIsTakePickingOrderPlan($picking_order_plan_id)
	{
		$query = $this->db->query("SELECT
										isnull(is_take, 0) as is_take FROM picking_order_plan WHERE picking_order_plan_id = '$picking_order_plan_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->is_take;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function getPickingOrderPlanId($picking_order_id)
	{
		$data = $this->db->select("picking_order_plan_id")->from("picking_order_plan")->where("picking_order_id", $picking_order_id)->get()->result();

		$dataFix = [];

		foreach ($data as $key => $value) {
			$dataFix[] = $value->picking_order_plan_id;
		}

		return $dataFix;
	}

	public function getKodeAutoComplete($value, $type)
	{

		return $this->db->select("CONCAT(REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 2)), '/', REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 3))) as kode")
			->from("pallet")
			->where("depo_id", $this->session->userdata('depo_id'))
			->like("pallet_kode", $value)->get()->result();

		// if ($type == 'pilih' || $type == 'non-pilih') {
		// 	return $this->db->select("CONCAT(REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 2)), '/', REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 3))) as kode")
		// 		->from("pallet")
		// 		->where("depo_id", $this->session->userdata('depo_id'))
		// 		->like("pallet_kode", $value)->get()->result();
		// }

		// if ($type == 'scan-sku') {
		// 	return $this->db->select("sku_kode as kode")
		// 		->from("sku")
		// 		->like("sku_kode", $value)->get()->result();
		// }
	}

	public function getPalletBySkuId($skuId)
	{
		return $this->db->distinct()->select("pallet.pallet_id, pallet.pallet_kode, pallet_jenis.pallet_jenis_kode, depo_detail.depo_detail_nama")
			->from("pallet")
			->join("rak_lajur_detail", "pallet.rak_lajur_detail_id = rak_lajur_detail.rak_lajur_detail_id", "left")
			->join("rak", "rak_lajur_detail.rak_id = rak.rak_id", "left")
			->join("depo_detail", "rak.depo_detail_id = depo_detail.depo_detail_id", "left")
			->join("pallet_detail", "pallet.pallet_id = pallet_detail.pallet_id", "left")
			->join("pallet_jenis", "pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id", "left")
			->where("pallet.depo_id", $this->session->userdata('depo_id'))
			->where("pallet_detail.sku_id", $skuId)
			->get()->result();
	}


	public function Get_BKBCanvasById($picking_order_id, $cetak)
	{

		// "ISNULL((SELECT
		// 								SUM(pk_aktual_d.sku_stock_qty_ambil)
		// 							FROM picking_order_aktual_d pk_aktual_d
		// 							WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id
		// 							AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id), 0) AS total_ambil,";

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		}

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									picking_order_plan.sku_stock_qty_ambil,
									0 AS total_ambil,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									pallet.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									pallet.batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN (SELECT DISTINCT
									rjd.rak_lajur_detail_nama,
									pd.sku_id,
									pd.sku_stock_id,
									pd.sku_stock_expired_date,
									rjd.rak_lajur_detail_id,
									pallet.pallet_kode,
									pd.batch_no,
									ISNULL(pd.sku_stock_qty, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) - ISNULL(pd.sku_stock_ambil, 0) AS stock
									FROM pallet
									LEFT JOIN pallet_detail pd
									ON pallet.pallet_id = pd.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									WHERE rjdp.rak_lajur_detail_id IS NOT NULL
									AND pallet.depo_id = '" . $this->session->userdata('depo_id') . "') AS pallet
									ON pallet.sku_stock_id = sku_stock.sku_stock_id
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									AND ISNULL(depo_detail_flag_jual, 0) = '1'
									AND ISNULL(depo_detail_is_kirimulang, 0) = '0'
									AND ISNULL(depo_detail_is_flashout, 0) = '0'
									AND ISNULL(depo_detail_is_qa, 0) = '0'
									AND ISNULL(depo_detail_is_bonus, 0) = '0'
									
									$order_by");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		// return $this->db->last_query();
		return $query;
	}
	public function Get_BKBCanvasAktualById($picking_order_id, $cetak)
	{

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		}

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									picking_order_plan.sku_stock_qty_ambil,
									ISNULL(picking_order_aktual_d.sku_stock_qty_ambil, 0) AS total_ambil,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									rjd.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(picking_order_aktual_d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									sku_stock.sku_stock_batch_no AS batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN (SELECT
									picking_order_aktual_d_id,
									picking_order_plan_id,
									depo_detail_id,
									sku_id,
									sku_stock_id,
									sku_stock_expired_date,
									SUM(sku_stock_qty_ambil) AS sku_stock_qty_ambil
									FROM picking_order_aktual_d
									WHERE picking_order_aktual_h_id IN (SELECT
									picking_order_aktual_h_id
									FROM picking_order_aktual_h
									WHERE picking_order_id = '$picking_order_id')
									GROUP BY picking_order_aktual_d_id,
											picking_order_plan_id,
											depo_detail_id,
											sku_id,
											sku_stock_id,
											sku_stock_expired_date) picking_order_aktual_d
									ON picking_order_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id
									LEFT JOIN picking_order_aktual_d_2
									ON picking_order_aktual_d_2.picking_order_aktual_d_id = picking_order_aktual_d.picking_order_aktual_d_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN pallet
									ON pallet.pallet_id = picking_order_aktual_d_2.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									-- AND depo_detail_is_flag_jual = '1'
									AND ISNULL(depo_detail_flag_jual, 0) = '1'
									AND ISNULL(depo_detail_is_kirimulang, 0) = '0'
									AND ISNULL(depo_detail_is_flashout, 0) = '0'
									AND ISNULL(depo_detail_is_qa, 0) = '0'
									AND ISNULL(depo_detail_is_bonus, 0) = '0'
									
									$order_by");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}
		return $query;

		// return $this->db->last_query();
	}

	public function Get_BKBCanvasByIdComposite($picking_order_id, $data, $cetak)
	{
		$table_sementara = "";
		$table_temp = "";
		$kolom_temp = "";
		if ($data != null) {
			$union = " UNION ALL ";
			foreach ($data as $key => $value) {
				$table_sementara .= "SELECT '" . $value['sku_konversi_group'] . "' AS sku_konversi_group, '" . $value['composite'] . "' AS composite, '" . $value['Ratio'] . "' AS ratio ";

				if ($key < count($data) - 1) {
					$table_sementara .= $union;
				}
			}

			$table_temp = "LEFT JOIN (" . $table_sementara . ") composite 
			ON composite.sku_konversi_group = SKU.sku_konversi_group";

			$kolom_temp = "composite.ratio, composite.composite,";
		}

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, pallet.rak_lajur_detail_nama ASC";
		}

		// "ISNULL((SELECT
		// 								SUM(pk_aktual_d.sku_stock_qty_ambil)
		// 							FROM picking_order_aktual_d pk_aktual_d
		// 							WHERE pk_aktual_d.sku_id = picking_order_plan.sku_id
		// 							AND pk_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id), 0) AS total_ambil,";

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									sku.sku_satuan,
  									sku.sku_konversi_faktor,
									picking_order_plan.sku_stock_qty_ambil * sku.sku_konversi_faktor As sku_qty_composite,
									picking_order_plan.sku_stock_qty_ambil,
									0 AS total_ambil,
									0 * sku.sku_konversi_faktor AS total_ambil_composite,
									$kolom_temp
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									pallet.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(pallet.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									pallet.batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN (SELECT DISTINCT
									rjd.rak_lajur_detail_nama,
									pd.sku_id,
									pd.sku_stock_id,
									pd.sku_stock_expired_date,
									rjd.rak_lajur_detail_id,
									pallet.pallet_kode,
									pd.batch_no,
									ISNULL(pd.sku_stock_qty, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) - ISNULL(pd.sku_stock_ambil, 0) AS stock
									FROM pallet
									LEFT JOIN pallet_detail pd
									ON pallet.pallet_id = pd.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									WHERE rjdp.rak_lajur_detail_id IS NOT NULL
									AND pallet.depo_id = '" . $this->session->userdata('depo_id') . "') AS pallet
									ON pallet.sku_stock_id = sku_stock.sku_stock_id
									$table_temp
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									AND depo_detail_is_kirimulang = '0'
									$order_by");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}
		return $query;

		// return $this->db->last_query();
	}

	public function Get_BKBCanvasAktualByIdComposite($picking_order_id, $data, $cetak)
	{
		$table_sementara = "";
		$table_temp = "";
		$kolom_temp = "";
		if ($data != null) {
			$union = " UNION ALL ";
			foreach ($data as $key => $value) {
				$table_sementara .= "SELECT '" . $value['sku_konversi_group'] . "' AS sku_konversi_group, '" . $value['composite'] . "' AS composite, '" . $value['Ratio'] . "' AS ratio ";

				if ($key < count($data) - 1) {
					$table_sementara .= $union;
				}
			}

			$table_temp = "LEFT JOIN (" . $table_sementara . ") composite 
			ON composite.sku_konversi_group = SKU.sku_konversi_group";

			$kolom_temp = "composite.ratio, composite.composite,";
		}

		if ($cetak == 'perprinciple') {
			$order_by = "ORDER BY principal.principle_kode, SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		} else {
			$order_by = "ORDER BY SKU.sku_konversi_group, rjd.rak_lajur_detail_nama ASC";
		}

		$query = $this->db->query("SELECT DISTINCT
									principal.principle_kode AS principal,
									picking_order_plan.picking_order_plan_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									sku.sku_satuan,
  									sku.sku_konversi_faktor,
									picking_order_plan.sku_stock_qty_ambil * sku.sku_konversi_faktor As sku_qty_composite,
									picking_order_plan.sku_stock_qty_ambil,
									ISNULL(picking_order_aktual_d.sku_stock_qty_ambil, 0) AS total_ambil,
									ISNULL(picking_order_aktual_d.sku_stock_qty_ambil, 0) * sku.sku_konversi_faktor AS total_ambil_composite,
									$kolom_temp
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									rjd.rak_lajur_detail_nama,
									pallet.pallet_kode,
									sku.sku_nama_produk AS detail_sku,
									FORMAT(picking_order_aktual_d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									sku_stock.sku_stock_batch_no AS batch_no
									FROM picking_order
									LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
									LEFT JOIN (SELECT
									picking_order_aktual_d_id,
									picking_order_plan_id,
									depo_detail_id,
									sku_id,
									sku_stock_id,
									sku_stock_expired_date,
									SUM(sku_stock_qty_ambil) AS sku_stock_qty_ambil
									FROM picking_order_aktual_d
									WHERE picking_order_aktual_h_id IN (SELECT
									picking_order_aktual_h_id
									FROM picking_order_aktual_h
									WHERE picking_order_id = '$picking_order_id')
									GROUP BY picking_order_aktual_d_id,
											picking_order_plan_id,
											depo_detail_id,
											sku_id,
											sku_stock_id,
											sku_stock_expired_date) picking_order_aktual_d
									ON picking_order_aktual_d.picking_order_plan_id = picking_order_plan.picking_order_plan_id
									LEFT JOIN picking_order_aktual_d_2
									ON picking_order_aktual_d_2.picking_order_aktual_d_id = picking_order_aktual_d.picking_order_aktual_d_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_aktual_d.sku_stock_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
									LEFT JOIN SKU
									ON SKU.sku_id = picking_order_plan.sku_id
									LEFT JOIN principle principal
									ON sku.principle_id = principal.principle_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN pallet
									ON pallet.pallet_id = picking_order_aktual_d_2.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rjdp
									ON rjdp.pallet_id = pallet.pallet_id
									LEFT JOIN rak_lajur_detail rjd
									ON rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id
									LEFT JOIN rak
									ON rak.rak_id = rjd.rak_id
									$table_temp
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = 'Bulk'
									AND picking_order.picking_order_id = '$picking_order_id'
									AND depo_detail_is_kirimulang = '0'
									$order_by");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}
		return $query;

		// return $this->db->last_query();
	}
}
