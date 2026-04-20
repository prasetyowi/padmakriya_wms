<?php

class M_PengeluaranBarang extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function Get_PengeluaranBarang($Tgl_PPB, $No_PPB, $No_FDJR, $No_PB, $Tipe_PB)
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

		$query = $this->db->query("SELECT
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
										tipe_delivery_order.tipe_delivery_order_nama AS picking_list_tipe,
										picking_order.picking_order_keterangan,
										picking_order_plan.picking_order_plan_status,
										picking_order_plan.tipe_delivery_order_id,
										tipe_delivery_order_plan.tipe_delivery_order_nama AS picking_order_plan_tipe
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
									WHERE picking_order.depo_id = '" . $this->session->userdata('depo_id') . "' AND FORMAT(picking_order.picking_order_tanggal,'yyyy-MM-dd') = '$Tgl_PPB'
																		" . $No_PPB . " 
																		" . $No_FDJR . " 
																		" . $No_PB . " 
																		" . $Tipe_PB . "
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
											tipe_delivery_order.tipe_delivery_order_nama,
											picking_order.picking_order_keterangan,
											picking_order_plan.picking_order_plan_status,
											picking_order_plan.tipe_delivery_order_id,
											tipe_delivery_order_plan.tipe_delivery_order_nama
									ORDER BY picking_order.picking_order_kode ASC");

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
										tipe_delivery_order.tipe_delivery_order_nama AS picking_order_plan_tipe,
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
										SUM(picking_order_aktual.sku_stock_qty_ambil) AS sku_stock_qty_ambil_aktual,
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
									LEFT JOIN delivery_order
										ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
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
											tipe_delivery_order.tipe_delivery_order_nama,
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
									ORDER BY picking_order_plan.picking_order_plan_nourut ASC");

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
									delivery_order.tipe_delivery_order_id,
									ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') AS picking_order_plan_tipe,
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
									SUM(picking_order_aktual.sku_stock_qty_ambil) AS sku_stock_qty_ambil_aktual,
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
								LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_plan.delivery_order_id
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
										picking_order_plan.delivery_order_id,
										delivery_order.delivery_order_kode,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order_batch.delivery_order_batch_tanggal_kirim,
										picking_list.picking_list_id,
										picking_list.picking_list_kode,
										picking_list.picking_list_tgl_kirim,
										delivery_order.tipe_delivery_order_id,
										ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk'),
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
								ORDER BY picking_order_plan.picking_order_plan_nourut ASC");

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
									tipe_delivery_order.tipe_delivery_order_nama AS picking_order_plan_tipe,
									picking_order_plan.picking_order_plan_id,
									principal.principle_kode AS principal,
									brand.principle_brand_nama AS brand,
									picking_order_plan.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									picking_order_plan.sku_stock_qty_ambil,
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
										tipe_delivery_order.tipe_delivery_order_nama,
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

	public function Get_PickingBKBBulk($picking_order_id, $karyawan_id)
	{
		if ($karyawan_id == "") {
			$karyawan_id = "";
		} else {
			$karyawan_id = "AND picking_order_plan.karyawan_id = '$karyawan_id' ";
		}

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
										delivery_order.tipe_delivery_order_id,
										ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') AS tipe_delivery_order_nama,
										picking_order_plan.picking_order_plan_id,
										principal.principle_kode AS principal,
										brand.principle_brand_nama AS brand,
										picking_order_plan.sku_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_satuan,
										sku.sku_kemasan,
										picking_order_plan.sku_stock_qty_ambil,
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
										ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') = 'Bulk' AND picking_order.picking_order_id = '$picking_order_id' " . $karyawan_id . "
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
											delivery_order.tipe_delivery_order_id,
											tipe_delivery_order.tipe_delivery_order_nama,
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

	public function Get_PickingBKBStandar($picking_order_id, $delivery_order_kode)
	{
		if ($delivery_order_kode == "") {
			$delivery_order_kode = "";
		} else {
			$delivery_order_kode = "AND delivery_order.delivery_order_kode = '$delivery_order_kode' ";
		}

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
									ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') AS tipe_delivery_order_nama,
									picking_order_plan.picking_order_plan_id,
									principal.principle_kode AS principal,
									brand.principle_brand_nama AS brand,
									picking_order_plan.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									picking_order_plan.sku_stock_qty_ambil,
									picking_order_plan.karyawan_id,
									picking_order_plan.karyawan_nama,
									picking_order_plan.sku_stock_id,
									FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date
								FROM picking_order
								LEFT JOIN picking_order_plan
									ON picking_order.picking_order_id = picking_order_plan.picking_order_id
								LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = picking_order_plan.sku_stock_id
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
								WHERE ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') = 'Standar' AND picking_order.picking_order_id = '$picking_order_id' " . $delivery_order_kode . "
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
										ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk'),
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

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function Get_Checker_BKB($picking_order_id)
	{
		$query = $this->db->query("SELECT
										karyawan_id,
										karyawan_nama
									FROM picking_order_plan
									WHERE picking_order_id = '$picking_order_id'
									GROUP BY karyawan_id,
											 karyawan_nama
									ORDER BY karyawan_nama ASC");
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

	public function Get_Checker_BKB_By_Kode($picking_order_kode)
	{
		$query = $this->db->query("SELECT
										karyawan_id,
										karyawan_nama
									FROM picking_order
									LEFT JOIN picking_order_plan
									   ON picking_order_plan.picking_order_id = picking_order.picking_order_id
									WHERE picking_order_kode = '$picking_order_kode'
									GROUP BY karyawan_id,
											 karyawan_nama
									ORDER BY karyawan_nama ASC");
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

	public function Get_Checker_BKB_Principal($principal, $depo_id)
	{
		$query = $this->db->query("SELECT
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
									WHERE principle.principle_kode = '$principal' AND karyawan.depo_id = '$depo_id'
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
									WHERE principle.principle_kode = '$principal' AND karyawan.depo_id = '$depo_id'
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
		$query = $this->db->query("SELECT sku_stock_saldo_alokasi AS sisa_stock FROM sku_stock WHERE sku_stock_id = '$sku_stock_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->sisa_stock;
		}

		// return $this->db->last_query();
		return $query;
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
		$this->db->select("picking_list_id,picking_list_kode")
			->from("picking_list")
			->where("picking_list_status", "Open")
			->where("depo_id", $this->session->userdata('depo_id'))
			->order_by("picking_list_kode");
		$query = $this->db->get();

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
		$query = $this->db->query("SELECT
										picking_order_aktual_h.picking_order_aktual_h_id,
										picking_order_aktual_h.picking_order_id,
										picking_order_aktual_h.picking_order_aktual_kode,
										FORMAT(picking_order_aktual_h.picking_order_aktual_tgl, 'dd-MM-yyyy') AS picking_order_aktual_tgl,
										picking_order_aktual_h.karyawan_id,
										picking_order_aktual_h.karyawan_nama,
										delivery_order.tipe_delivery_order_id,
										ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') AS tipe_bkb
									FROM picking_order_aktual_h
									LEFT JOIN picking_order_aktual_d
										ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
									LEFT JOIN delivery_order
										ON delivery_order.delivery_order_id = picking_order_aktual_d.delivery_order_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE picking_order_aktual_h.picking_order_id = '$picking_order_id'
									AND ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk') = '$tipe_bkb'
									GROUP BY picking_order_aktual_h.picking_order_aktual_h_id,
											picking_order_aktual_h.picking_order_id,
											picking_order_aktual_h.picking_order_aktual_kode,
											FORMAT(picking_order_aktual_h.picking_order_aktual_tgl, 'dd-MM-yyyy'),
											picking_order_aktual_h.karyawan_id,
											picking_order_aktual_h.karyawan_nama,
											delivery_order.tipe_delivery_order_id,
											ISNULL(tipe_delivery_order.tipe_delivery_order_nama, 'Bulk')
									ORDER BY picking_order_aktual_h.picking_order_aktual_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
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
								ORDER BY sku.sku_kode ASC");

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
									tipe_delivery_order.tipe_delivery_order_nama AS picking_list_tipe,
									picking.picking_list_tgl_kirim,
									picking.picking_list_create_who,
									picking.picking_list_create_tgl,
									picking.picking_list_keterangan,
									picking.picking_list_status
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
										tipe_delivery_order.tipe_delivery_order_nama,
										picking.picking_list_tgl_kirim,
										picking.picking_list_create_who,
										picking.picking_list_create_tgl,
										picking.picking_list_keterangan,
										picking.picking_list_status");

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
		$query = $this->db->query("SELECT
										picking.picking_list_id,
										picking.unit_mandiri_id,
										picking.depo_id,
										picking.depo_detail_id,
										depo_detail.depo_detail_nama,
										ISNULL(rak.rak_nama, '') AS rak_nama,
										picking.area_id,
										picking.picking_list_kode,
										picking.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_nama AS picking_list_tipe,
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
									WHERE picking.picking_list_id = '$picking_list_id'
									GROUP BY picking.picking_list_id,
										picking.unit_mandiri_id,
										picking.depo_id,
										picking.depo_detail_id,
										depo_detail.depo_detail_nama,
										ISNULL(rak.rak_nama, ''),
										picking.area_id,
										picking.picking_list_kode,
										picking.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_nama,
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
									ORDER BY sku.sku_kode ASC");

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
									ISNULL(rak.rak_nama, '') AS rak_nama,
									picking.area_id,
									picking.delivery_order_batch_id,
									picking.picking_list_kode,
									do.delivery_order_kode,
									do_batch.delivery_order_batch_kode,
									picking.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_nama AS picking_list_tipe,
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
										tipe_delivery_order.tipe_delivery_order_nama,
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
		$query = $this->db->query("SELECT
									picking_list.picking_list_id,
									picking_list.unit_mandiri_id,
									picking_list.depo_id,
									picking_list.depo_detail_id,
									depo_detail.depo_detail_nama,
									ISNULL(rak.rak_nama, '') AS rak_nama,
									picking_list.area_id,
									picking_list.picking_list_kode,
									delivery_order.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_nama,
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
								AND tipe_delivery_order.tipe_delivery_order_nama = 'Bulk'
								GROUP BY picking_list.picking_list_id,
										picking_list.unit_mandiri_id,
										picking_list.depo_id,
										picking_list.depo_detail_id,
										depo_detail.depo_detail_nama,
										ISNULL(rak.rak_nama, ''),
										picking_list.area_id,
										picking_list.picking_list_kode,
										delivery_order.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_nama,
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
	  							ORDER BY tipe_delivery_order.tipe_delivery_order_nama ASC");

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
									tipe_delivery_order.tipe_delivery_order_nama,
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
										tipe_delivery_order.tipe_delivery_order_nama,
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
								ORDER BY tipe_delivery_order.tipe_delivery_order_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Insert_PickingOrder($picking_order_id, $picking_list_id, $depo_id, $depo_detail_id, $picking_order_tanggal, $picking_order_kode, $picking_order_keterangan, $picking_order_status)
	{
		$this->db->set("picking_order_id", $picking_order_id);
		$this->db->set("picking_list_id", $picking_list_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
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
										tipe_delivery_order.tipe_delivery_order_nama AS tipe_do,
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
											tipe_delivery_order.tipe_delivery_order_nama,
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

	public function Insert_PickingOrderPlanBulk($picking_order_id, $picking_list_id, $picking_order_type, $S_UserID, $S_pengguna_nama, $picking_order_status)
	{
		$no = 1;
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
										tipe_delivery_order.tipe_delivery_order_nama AS tipe_do,
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
										picking.picking_list_kode,
										picking.picking_list_tgl_kirim,
										picking.picking_list_create_who,
										picking.picking_list_create_tgl,
										picking.picking_list_keterangan,
										picking.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_nama,
										picking.picking_list_status,
										picking_detail2.sku_id,
										picking_detail2.sku_stock_id,
										sku_stock.sku_stock_expired_date");

		foreach ($query->result() as $row) {
			$this->db->set("picking_order_plan_id", "NewID()", FALSE);
			$this->db->set("picking_order_id", $picking_order_id);
			$this->db->set("karyawan_id", $S_UserID);
			$this->db->set("karyawan_nama", $S_pengguna_nama);
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

	public function Insert_PickingOrderPlanMix($picking_order_id, $picking_list_id, $picking_order_type, $S_UserID, $S_pengguna_nama, $picking_order_status)
	{
		$no = 1;
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
										do.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_nama AS tipe_do,
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
									LEFT JOIN delivery_order do
										ON do.delivery_order_id = picking_detail.delivery_order_id
									LEFT JOIN sku_stock
										ON sku_stock.sku_stock_id = picking_detail2.sku_stock_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = do.tipe_delivery_order_id
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
										do.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_nama,
										picking.picking_list_status,
										picking_detail2.sku_id,
										picking_detail2.sku_stock_id,
										sku_stock.sku_stock_expired_date");

		foreach ($query->result() as $row) {
			$this->db->set("picking_order_plan_id", "NewID()", FALSE);
			$this->db->set("picking_order_id", $picking_order_id);
			$this->db->set("karyawan_id", $S_UserID);
			$this->db->set("karyawan_nama", $S_pengguna_nama);
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
										tipe_delivery_order.tipe_delivery_order_nama AS tipe_do,
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
											tipe_delivery_order.tipe_delivery_order_nama,
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

	public function Insert_PickingOrderAktual_D($picking_order_aktual_h_id, $picking_order_plan_id, $delivery_order_id, $sku_id, $sku_stock_id, $sku_stock_expired_date, $sku_stock_qty_ambil, $tipe_do, $no_urut)
	{
		$query = $this->db->query("SELECT sku_stock_saldo_alokasi AS sisa_stock, sku_stock_keluar FROM sku_stock WHERE sku_stock_id = '$sku_stock_id' ");

		$sisa_stock = $query->row(0)->sisa_stock;
		$sku_stock_keluar = $query->row(0)->sku_stock_keluar;

		$stock_akhir = $sisa_stock - $sku_stock_qty_ambil;
		$stock_keluar = $sku_stock_keluar + $sku_stock_qty_ambil;

		if ($tipe_do == "Standar") {
			$this->db->set("picking_order_aktual_d_id", "NewID()", FALSE);
			$this->db->set("picking_order_aktual_h_id", $picking_order_aktual_h_id);
			$this->db->set("picking_order_plan_id", $picking_order_plan_id);
			$this->db->set("delivery_order_id", $delivery_order_id);
			$this->db->set("sku_id", $sku_id);
			$this->db->set("sku_stock_id", $sku_stock_id);
			$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
			$this->db->set("sku_stock_qty_ambil", $sku_stock_qty_ambil);
			$this->db->set("picking_order_plan_nourut", $no_urut);

			$this->db->insert("picking_order_aktual_d");

			$affectedrows = $this->db->affected_rows();
			if ($affectedrows > 0) {
				$this->db->set("sku_stock_saldo_alokasi", $stock_akhir);
				$this->db->set("sku_stock_keluar", $stock_keluar);

				$this->db->where("sku_stock_id", $sku_stock_id);

				$this->db->update("sku_stock");

				$queryinsert = 1;
			} else {
				$queryinsert = 0;
			}

			return $queryinsert;
			// return $this->db->last_query();

		} else if ($tipe_do == "Bulk") {
			$this->db->set("picking_order_aktual_d_id", "NewID()", FALSE);
			$this->db->set("picking_order_aktual_h_id", $picking_order_aktual_h_id);
			$this->db->set("picking_order_plan_id", $picking_order_plan_id);
			// $this->db->set("delivery_order_id", $delivery_order_id);
			$this->db->set("sku_id", $sku_id);
			$this->db->set("sku_stock_id", $sku_stock_id);
			$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
			$this->db->set("sku_stock_qty_ambil", $sku_stock_qty_ambil);
			$this->db->set("picking_order_plan_nourut", $no_urut);

			$this->db->insert("picking_order_aktual_d");

			$affectedrows = $this->db->affected_rows();
			if ($affectedrows > 0) {
				$this->db->set("sku_stock_saldo_alokasi", $stock_akhir);
				$this->db->set("sku_stock_keluar", $stock_keluar);

				$this->db->where("sku_stock_id", $sku_stock_id);

				$this->db->update("sku_stock");

				$queryinsert = 1;
			} else {
				$queryinsert = 0;
			}

			return $queryinsert;
			// return $this->db->last_query();

		}
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

	public function Get_BKBBulkById($picking_order_id)
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
									FORMAT(picking_order.picking_order_tanggal, 'dd-MM-yyyy') AS picking_order_tanggal,
									picking_order_plan.delivery_order_id,
									delivery_order.delivery_order_kode,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order_batch.delivery_order_batch_tanggal_kirim,
									picking_list.picking_list_kode,
									FORMAT(picking_list.picking_list_tgl_kirim, 'dd-MM-yyyy') AS picking_list_tgl_kirim,
									delivery_order.tipe_delivery_order_id,
									ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') AS picking_order_plan_tipe,
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
									FORMAT(picking_order_plan.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
									FORMAT(picking_order_aktual.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date_aktual,
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
								LEFT JOIN delivery_order
									ON delivery_order.delivery_order_id = picking_order_aktual.delivery_order_id
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
								WHERE picking_order.picking_order_id = '$picking_order_id' AND ISNULL(tipe_delivery_order.tipe_delivery_order_nama,'Bulk') = 'Bulk'
								ORDER BY picking_order_plan.picking_order_plan_nourut ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_BKBStandarById($picking_order_id)
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
									tipe_delivery_order.tipe_delivery_order_nama AS picking_order_plan_tipe,
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
										tipe_delivery_order.tipe_delivery_order_nama AS picking_order_plan_tipe,
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

	public function Update_PickingOrderPlan($picking_order_id, $picking_order_plan_status, $karyawan_id, $karyawan_nama)
	{
		$this->db->set("picking_order_plan_status", $picking_order_plan_status);
		$this->db->set("karyawan_id", $karyawan_id);
		$this->db->set("karyawan_nama", $karyawan_nama);

		$this->db->where("picking_order_id", $picking_order_id);

		$this->db->update("picking_order_plan");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
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
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
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
			->order_by("tipe_delivery_order_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
}
