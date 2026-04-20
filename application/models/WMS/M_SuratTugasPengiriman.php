<?php

class M_SuratTugasPengiriman extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function GetTipeDeliveryOrder()
	{
		$this->db->select("*")
			->from("tipe_delivery_order")
			->where_in("tipe_delivery_order_id", array("F1E7E5C6-FB70-4E07-993C-3551E5B7DDC3", "23080729-40FB-42B2-B58E-588E0C3CCC3F", "C5BE83E2-01E8-4E24-B766-26BB4158F2CD"))
			->order_by("tipe_delivery_order_alias");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_SuratTugasPengiriman($Tgl_FDJR, $Tgl_FDJR2, $No_FDJR, $karyawan_id, $Status_FDJR)
	{

		if ($No_FDJR == "") {
			$No_FDJR = "";
		} else {
			$No_FDJR = " AND delivery_order_batch.delivery_order_batch_kode = '$No_FDJR' ";
		}
		if ($karyawan_id == "") {
			$karyawan_id = "";
		} else {
			$karyawan_id = " AND delivery_order_batch.karyawan_id = '$karyawan_id' ";
		}
		if ($Status_FDJR == "") {
			$Status_FDJR = "";
		} else {
			$Status_FDJR = " AND delivery_order_batch.delivery_order_batch_status = '$Status_FDJR' ";
		}

		$query = $this->db->query("SELECT
										delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										picking_list.picking_list_id,
										ISNULL(picking_list.picking_list_kode,'') AS picking_list_kode,
										picking_order.picking_order_id,
										ISNULL(picking_order.picking_order_kode,'') AS picking_order_kode,
										FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim,'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim,
										delivery_order_batch.delivery_order_batch_status,
										delivery_order_batch.karyawan_id,
										driver.karyawan_nama,
										serah_terima_kirim.serah_terima_kirim_id,
										ISNULL(serah_terima_kirim.serah_terima_kirim_kode,'') AS serah_terima_kirim_kode,
										delivery_order_batch.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										CASE WHEN delivery_order_settlement.delivery_order_id IS NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement has not Started' WHEN delivery_order_settlement.delivery_order_id IS NOT NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement Success' ELSE '' END AS settlement_status,
										COUNT(DISTINCT penerimaan_penjualan.penerimaan_penjualan_id) AS jml_btb
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN picking_list
									ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN picking_order
									ON picking_list.picking_list_id = picking_order.picking_list_id
									LEFT JOIN serah_terima_kirim
									ON serah_terima_kirim.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									AND serah_terima_kirim.picking_order_id = picking_order.picking_order_id
									LEFT JOIN delivery_order_settlement
									ON delivery_order_settlement.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN penerimaan_penjualan
  									ON penerimaan_penjualan.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN tipe_delivery_order
									ON delivery_order_batch.tipe_delivery_order_id = tipe_delivery_order.tipe_delivery_order_id
									LEFT JOIN karyawan driver
									ON delivery_order_batch.karyawan_id = driver.karyawan_id
									WHERE FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim,'yyyy-MM-dd') BETWEEN '$Tgl_FDJR' AND '$Tgl_FDJR2' 
									AND tipe_delivery_order.tipe_delivery_order_nama <> 'Canvas'
									AND delivery_order_batch.depo_id = '" . $this->session->userdata('depo_id') . "' 
									" . $No_FDJR . " " . $karyawan_id . " " . $Status_FDJR . "
									GROUP BY delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										picking_list.picking_list_id,
										ISNULL(picking_list.picking_list_kode,''),
										picking_order.picking_order_id,
										ISNULL(picking_order.picking_order_kode,''),
										FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim,'dd-MM-yyyy'),
										delivery_order_batch.delivery_order_batch_status,
										delivery_order_batch.karyawan_id,
										driver.karyawan_nama,
										serah_terima_kirim.serah_terima_kirim_id,
										ISNULL(serah_terima_kirim.serah_terima_kirim_kode,''),
										delivery_order_batch.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										CASE WHEN delivery_order_settlement.delivery_order_id IS NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement has not Started' WHEN delivery_order_settlement.delivery_order_id IS NOT NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement Success' ELSE '' END
										ORDER BY delivery_order_batch.delivery_order_batch_kode ASC");

		// AND delivery_order_batch.delivery_order_batch_status NOT IN ('Closing Delivery Confirm','completed')

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_Driver()
	{
		$query = $this->db->query("SELECT * FROM karyawan WHERE karyawan_level_id = '339d8ac2-c6ce-4b47-9bfc-e372592af521' AND depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY karyawan_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_SettlementHeader($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
										delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order_batch.karyawan_id,
										karyawan.karyawan_nama,
										COUNT(DISTINCT delivery_order_settlement.delivery_order_id) AS jumlah
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_settlement
									ON delivery_order_settlement.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									GROUP BY delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order_batch.karyawan_id,
										karyawan.karyawan_nama ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_ClosingPengirimanHeader($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
										delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										FORMAT(delivery_order_batch.delivery_order_batch_tanggal, 'dd-MM-yyyy') AS delivery_order_batch_tanggal,
										FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim,
										delivery_order_batch.delivery_order_batch_update_tgl,
										delivery_order_batch.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										delivery_order_batch.area_id,
										area.area_nama,
										delivery_order_batch.delivery_order_batch_status,
										delivery_order_batch.tipe_ekspedisi_id,
										tipe_ekspedisi.tipe_ekspedisi_nama,
										delivery_order_batch.kendaraan_id,
										kendaraan.kendaraan_model,
										delivery_order_batch.karyawan_id,
										karyawan.karyawan_nama,
										ISNULL(delivery_order_batch.kendaraan_km_akhir,0) AS kendaraan_km_akhir
									FROM delivery_order_batch
									LEFT JOIN karyawan
										ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									LEFT JOIN kendaraan
										ON kendaraan.kendaraan_id = delivery_order_batch.kendaraan_id
									LEFT JOIN tipe_ekspedisi
										ON tipe_ekspedisi.tipe_ekspedisi_id = delivery_order_batch.tipe_ekspedisi_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = delivery_order_batch.tipe_delivery_order_id
									LEFT JOIN area
										ON area.area_id = delivery_order_batch.area_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									GROUP BY delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										FORMAT(delivery_order_batch.delivery_order_batch_tanggal, 'dd-MM-yyyy'),
										FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy'),
										delivery_order_batch.delivery_order_batch_update_tgl,
										delivery_order_batch.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										delivery_order_batch.area_id,
										area.area_nama,
										delivery_order_batch.delivery_order_batch_status,
										delivery_order_batch.tipe_ekspedisi_id,
										tipe_ekspedisi.tipe_ekspedisi_nama,
										delivery_order_batch.kendaraan_id,
										kendaraan.kendaraan_model,
										delivery_order_batch.karyawan_id,
										karyawan.karyawan_nama,
										ISNULL(delivery_order_batch.kendaraan_km_akhir,0) ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_ClosingPengirimanDetail($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
									delivery_order_batch.delivery_order_batch_id,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									ISNULL(delivery_order.delivery_order_nominal_tunai, 0) AS delivery_order_nominal_tunai,
									CASE
										WHEN ISNULL(delivery_order.delivery_order_jumlah_bayar, '0') = 0 AND
										ISNULL(delivery_order.delivery_order_tipe_pembayaran, 0) = '0' THEN ISNULL(delivery_order.delivery_order_nominal_tunai, 0)
										ELSE ISNULL(delivery_order.delivery_order_jumlah_bayar, 0)
									END AS delivery_order_jumlah_bayar,
									FORMAT(delivery_order.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_aktual_kirim,
									ISNULL(delivery_order.delivery_order_kirim_nama, '') AS delivery_order_kirim_nama,
									ISNULL(delivery_order.delivery_order_kirim_alamat, '') AS delivery_order_kirim_alamat,
									ISNULL(delivery_order.delivery_order_kirim_telp, '') AS delivery_order_kirim_telp,
									delivery_order.delivery_order_tipe_pembayaran AS delivery_order_tipe_pembayaran,
									ISNULL(delivery_order.delivery_order_no_urut_rute, '') AS delivery_order_no_urut_rute,
									delivery_order.delivery_order_status,
									delivery_order.delivery_order_reject_reason AS reason_id,
									dor.delivery_order_id AS dor_id,
									CASE
										WHEN delivery_order.delivery_order_kode LIKE '%/DO/%' AND
										delivery_order.is_ada_titipan = '1' THEN '1'
										ELSE '0'
									END ada_titipan,
									CASE
										WHEN delivery_order.delivery_order_kode LIKE '%/DO/%' AND
										dor.delivery_order_id IS NOT NULL THEN '1'
										ELSE '0'
									END cek_titipan,
									CASE
										WHEN delivery_order.delivery_order_kode LIKE '%/DO/%' AND
										dor.delivery_order_id IS NULL THEN '1'
										ELSE '0'
									END boleh_titip,
									delivery_order.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_alias,
									so.sales_order_kode,
									so.sales_order_no_po,
									btb.penerimaan_penjualan_id,
									CASE
										WHEN delivery_order.delivery_order_status NOT IN ('delivered') OR
										(delivery_order.delivery_order_kode LIKE '%/DO/%' AND
										delivery_order.is_ada_titipan = '1') THEN CASE
											WHEN btb.penerimaan_penjualan_id IS NOT NULL THEN 'SUDAH BTB'
											ELSE 'BELUM BTB'
										END
										ELSE 'TIDAK ADA BTB'
									END sudah_btb
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN delivery_order dor
									ON delivery_order.delivery_order_id = dor.delivery_order_reff_id
									LEFT JOIN sales_order so
									ON so.sales_order_id = delivery_order.sales_order_id
									LEFT JOIN (SELECT
									penerimaan_penjualan_id,
									delivery_order_id
									FROM penerimaan_penjualan_detail
									GROUP BY penerimaan_penjualan_id,
											delivery_order_id) btb
									ON btb.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									LEFT JOIN kendaraan
									ON kendaraan.kendaraan_id = delivery_order_batch.kendaraan_id
									LEFT JOIN tipe_ekspedisi
									ON tipe_ekspedisi.tipe_ekspedisi_id = delivery_order_batch.tipe_ekspedisi_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN area
									ON area.area_id = delivery_order_batch.area_id
									LEFT JOIN tipe_pembayaran
									ON tipe_pembayaran.tipe_pembayaran_id = delivery_order.delivery_order_tipe_pembayaran
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY CASE
									WHEN delivery_order.delivery_order_no_urut_rute IS NULL THEN 1
									ELSE 0
									END, delivery_order.delivery_order_no_urut_rute, delivery_order.delivery_order_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_ClosingPengirimanArea($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
									area.*
									FROM delivery_order_area
									LEFT JOIN area
									ON area.area_id = delivery_order_area.area_id
									WHERE delivery_order_area.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY area.area_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetClosingPengirimanDODetail($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
									delivery_order_batch.delivery_order_batch_id,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									sku.principle_id,
									principle.principle_kode,
									delivery_order_detail.sku_id,
									delivery_order_detail.sku_kode,
									delivery_order_detail.sku_nama_produk,
									delivery_order_detail.sku_harga_nett,
									delivery_order_detail.sku_qty,
									delivery_order_detail.sku_qty_kirim,
									delivery_order_detail.reason_id,
									ISNULL(reason.reason_keterangan,'') AS reason_keterangan
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN sku
									ON sku.sku_id = delivery_order_detail.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN reason
									ON reason.reason_id = delivery_order_detail.reason_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY delivery_order.delivery_order_kode, principle.principle_kode, delivery_order_detail.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_ClosingPengirimanByDO($delivery_order_id)
	{
		// $query = $this->db->query("SELECT
		// 							delivery_order.delivery_order_id,
		// 							delivery_order_detail.delivery_order_detail_id,
		// 							delivery_order.delivery_order_kode,
		// 							sku.sku_id,
		// 							sku.sku_kode,
		// 							principle.principle_kode AS principle,
		// 							principle_brand.principle_brand_nama AS brand,
		// 							sku.sku_nama_produk,
		// 							sku.sku_kemasan,
		// 							sku.sku_satuan,
		// 							ISNULL(delivery_order_detail.sku_qty,0) AS sku_qty,
		// 							CASE WHEN ISNULL(delivery_order_detail.sku_qty_kirim,0) = 0 THEN ISNULL(delivery_order_detail_temp.sku_qty_kirim,0) ELSE ISNULL(delivery_order_detail.sku_qty_kirim,0) END AS sku_qty_kirim,
		// 							CASE WHEN delivery_order_detail.reason_id IS NULL THEN delivery_order_detail_temp.reason_id ELSE delivery_order_detail.reason_id END AS reason_id
		// 						FROM delivery_order
		// 						LEFT JOIN delivery_order_detail
		// 							ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
		// 						LEFT JOIN delivery_order_detail_temp
		// 							ON delivery_order_detail.delivery_order_id = delivery_order_detail_temp.delivery_order_id
		// 							AND delivery_order_detail.sku_id = delivery_order_detail_temp.sku_id
		// 						LEFT JOIN sku
		// 							ON delivery_order_detail.sku_id = sku.sku_id
		// 						LEFT JOIN principle
		// 							ON sku.principle_id = principle.principle_id
		// 						LEFT JOIN principle_brand
		// 							ON sku.principle_brand_id = principle_brand.principle_brand_id
		// 						WHERE delivery_order.delivery_order_id = '$delivery_order_id'
		// 						ORDER BY sku.sku_kode ASC");

		$query = $this->db->query("SELECT
									delivery_order.delivery_order_id,
									delivery_order_detail2.delivery_order_detail_id,
									delivery_order_detail2.delivery_order_detail2_id,
									delivery_order.delivery_order_kode,
									sku.sku_id,
									sku.sku_kode,
									principle.principle_kode AS principle,
									principle_brand.principle_brand_nama AS brand,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									delivery_order_detail2.sku_expdate,
									ISNULL(delivery_order_detail2.sku_qty, 0) AS sku_qty,
									CASE
									WHEN ISNULL(delivery_order_detail2.sku_qty_kirim, 0) = 0 THEN ISNULL(delivery_order_detail_temp.sku_qty_kirim, 0)
									ELSE ISNULL(delivery_order_detail2.sku_qty_kirim, 0)
									END AS sku_qty_kirim,
								    CASE WHEN delivery_order_detail2.reason_id IS NULL THEN delivery_order_detail_temp.reason_id ELSE delivery_order_detail2.reason_id END AS reason_id
								FROM delivery_order
								LEFT JOIN delivery_order_detail2
									ON delivery_order.delivery_order_id = delivery_order_detail2.delivery_order_id
								LEFT JOIN delivery_order_detail_temp
									ON delivery_order_detail2.delivery_order_id = delivery_order_detail_temp.delivery_order_id
									AND delivery_order_detail2.sku_id = delivery_order_detail_temp.sku_id AND delivery_order_detail2.delivery_order_detail2_id = delivery_order_detail_temp.delivery_order_detail2_id
								LEFT JOIN sku
									ON delivery_order_detail2.sku_id = sku.sku_id
								LEFT JOIN principle
									ON sku.principle_id = principle.principle_id
								LEFT JOIN principle_brand
									ON sku.principle_brand_id = principle_brand.principle_brand_id
								WHERE delivery_order.delivery_order_id = '$delivery_order_id'
								ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Exec_Settlement($delivery_order_batch_id)
	{
		$query = $this->db->query("exec proses_settlement '$delivery_order_batch_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_Reason()
	{
		$query = $this->db->query("SELECT * FROM reason WHERE reason_modul_kode = 'SRE' ORDER BY reason_keterangan ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_tipe_pembayaran()
	{
		$query = $this->db->query("SELECT * FROM tipe_pembayaran ORDER BY tipe_pembayaran_id ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Update_ClosingPengirimanByDO($delivery_order_id, $delivery_order_detail_id, $sku_qty_kirim, $reason_id, $delivery_order_detail2_id)
	{
		if ($reason_id != "") {
			$reason_id = "'" . $reason_id . "'";
		} else {
			$reason_id = "NULL";
		}

		// $query = $this->db->query("INSERT INTO delivery_order_detail_temp 
		// 							SELECT 
		// 								delivery_order_detail_id,
		// 								delivery_order_id,
		// 								delivery_order_batch_id,
		// 								sku_id,
		// 								depo_id,
		// 								depo_detail_id,
		// 								sku_kode,
		// 								sku_nama_produk,
		// 								sku_harga_satuan,
		// 								sku_disc_percent,
		// 								sku_disc_rp,
		// 								sku_harga_nett,
		// 								sku_request_expdate,
		// 								sku_filter_expdate,
		// 								sku_filter_expdatebulan,
		// 								sku_filter_expdatetahun,
		// 								sku_weight,
		// 								sku_weight_unit,
		// 								sku_length,
		// 								sku_length_unit,
		// 								sku_width,
		// 								sku_width_unit,
		// 								sku_height,
		// 								sku_height_unit,
		// 								sku_volume,
		// 								sku_volume_unit,
		// 								sku_qty,
		// 								sku_keterangan,
		// 								'$sku_qty_kirim' AS sku_qty_kirim,
		// 								$reason_id AS reason_id
		// 							FROM delivery_order_detail
		// 							WHERE delivery_order_id = '$delivery_order_id' AND delivery_order_detail_id = '$delivery_order_detail_id' ");

		$query = $this->db->query("INSERT INTO delivery_order_detail_temp 
									SELECT
									dod2.delivery_order_detail2_id,
									dod2.delivery_order_detail_id,
									dod2.delivery_order_id,
									delivery_order_batch_id,
									dod2.sku_id,
									depo_id,
									depo_detail_id,
									sku_kode,
									sku_nama_produk,
									sku_harga_satuan,
									sku_disc_percent,
									sku_disc_rp,
									sku_harga_nett,
									sku_request_expdate,
									sku_filter_expdate,
									sku_filter_expdatebulan,
									sku_filter_expdatetahun,
									sku_weight,
									sku_weight_unit,
									sku_length,
									sku_length_unit,
									sku_width,
									sku_width_unit,
									sku_height,
									sku_height_unit,
									sku_volume,
									sku_volume_unit,
									dod2.sku_qty,
									sku_keterangan,
									'$sku_qty_kirim' AS sku_qty_kirim,
									$reason_id AS reason_id
									FROM delivery_order_detail2 dod2
									LEFT JOIN delivery_order_detail dod
									ON dod.delivery_order_detail_id = dod2.delivery_order_detail_id
									WHERE dod2.delivery_order_id = '$delivery_order_id'
									AND dod2.delivery_order_detail_id = '$delivery_order_detail_id'
									AND dod2.delivery_order_detail2_id = '$delivery_order_detail2_id' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;

		// return $this->db->last_query();
	}

	public function Delete_ClosingPengirimanByDO()
	{
		$query = $this->db->query("DELETE FROM delivery_order_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;

		// return $this->db->last_query();
	}

	public function Delete_DeliveryOrderDetailTemp($delivery_order_id, $delivery_order_detail_id, $delivery_order_detail2_id)
	{
		$query = $this->db->query("DELETE FROM delivery_order_detail_temp WHERE delivery_order_id = '$delivery_order_id' AND delivery_order_detail_id = '$delivery_order_detail_id' AND delivery_order_detail2_id = '$delivery_order_detail2_id' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;

		// return $this->db->last_query();
	}

	public function Update_ClosingPengirimanDO($delivery_order_id, $delivery_order_status, $reason_id, $yourref, $delivery_order_jumlah_bayar, $delivery_order_is_paid, $delivery_order_tipe_pembayaran, $tipe_delivery_order_nama)
	{
		if ($delivery_order_status == "partially delivered") {
			$check_delivery_order_detail_id = '';
			$sumSKUQtyKirim = 0;
			$query = $this->db->query("SELECT * FROM delivery_order_detail_temp WHERE delivery_order_id = '$delivery_order_id' ORDER BY sku_nama_produk ASC ");
			foreach ($query->result() as $row) {
				if ($check_delivery_order_detail_id == $row->delivery_order_detail_id) {
					$sumSKUQtyKirim = $sumSKUQtyKirim + intval($row->sku_qty_kirim);

					//update delivery_order_detail
					$this->db->set("sku_qty_kirim", $sumSKUQtyKirim);
					// $this->db->set("reason_id", $row->reason_id);

					$this->db->where("delivery_order_detail_id", $row->delivery_order_detail_id);

					$this->db->update("delivery_order_detail");
				} else {
					//update delivery_order_detail
					$this->db->set("sku_qty_kirim", $row->sku_qty_kirim);
					// $this->db->set("reason_id", $row->reason_id);

					$this->db->where("delivery_order_detail_id", $row->delivery_order_detail_id);

					$this->db->update("delivery_order_detail");


					$check_delivery_order_detail_id = $row->delivery_order_detail_id;
					$sumSKUQtyKirim = $row->sku_qty_kirim;
				}

				//update delivery_order_detail2
				$this->db->set("sku_qty_kirim", $row->sku_qty_kirim);
				$this->db->set("reason_id", $row->reason_id);

				$this->db->where("delivery_order_detail2_id", $row->delivery_order_detail2_id);

				$this->db->update("delivery_order_detail2");
			}
		} else if ($delivery_order_status == "not delivered" || $delivery_order_status == "rescheduled") {
			// $query = $this->db->query("SELECT * FROM delivery_order_detail WHERE delivery_order_id = '$delivery_order_id' ");
			// foreach ($query->result() as $row) {
			// 	//update delivery_order_detail
			// 	$this->db->set("sku_qty_kirim", 0);
			// 	$this->db->set("reason_id", $row->reason_id);

			// 	$this->db->where("delivery_order_detail_id", $row->delivery_order_detail_id);

			// 	$this->db->update("delivery_order_detail");
			// }

			// update delivery_order_detail
			if ($tipe_delivery_order_nama == "Retur") {
				$query = $this->db->query("SELECT * FROM delivery_order_detail WHERE delivery_order_id = '$delivery_order_id' ");
				foreach ($query->result() as $row) {
					//update delivery_order_detail
					$this->db->set("sku_qty_kirim", $row->sku_qty);
					if ($reason_id != "") {
						$this->db->set("reason_id", $reason_id);
					}
					$this->db->where("delivery_order_detail_id", $row->delivery_order_detail_id);

					$this->db->update("delivery_order_detail");
				}
			} else {
				$this->db->set("sku_qty_kirim", 0);
				// $this->db->set("reason_id", $reason_id);

				$this->db->where("delivery_order_id", $delivery_order_id);

				$this->db->update("delivery_order_detail");

				// update delivery_order_detail2
				$this->db->set("sku_qty_kirim", 0);
				$this->db->set("reason_id", $reason_id);

				$this->db->where("delivery_order_id", $delivery_order_id);

				$this->db->update("delivery_order_detail2");
			}
		} else {
			if ($tipe_delivery_order_nama == "Retur") {
				$this->db->set("sku_qty_kirim", 0);

				if ($reason_id != "") {
					$this->db->set("reason_id", $reason_id);
				}

				$this->db->where("delivery_order_id", $delivery_order_id);

				$this->db->update("delivery_order_detail");
			} else {
				$query = $this->db->query("SELECT * FROM delivery_order_detail WHERE delivery_order_id = '$delivery_order_id' ");
				foreach ($query->result() as $row) {
					//update delivery_order_detail
					$this->db->set("sku_qty_kirim", $row->sku_qty);
					if ($reason_id != "") {
						$this->db->set("reason_id", $reason_id);
					}
					$this->db->where("delivery_order_detail_id", $row->delivery_order_detail_id);

					$this->db->update("delivery_order_detail");
				}
			}
		}

		//update delivery_order
		$this->db->set("delivery_order_status", $delivery_order_status);
		if ($reason_id != "") {
			$this->db->set("delivery_order_reject_reason", $reason_id);
		}
		if ($yourref == "titipan") {
			$this->db->set("is_ada_titipan", "1");
		} else {
			$this->db->set("is_ada_titipan", null);
		}

		if ($delivery_order_jumlah_bayar != '' || $delivery_order_jumlah_bayar != null) {
			$this->db->set("delivery_order_jumlah_bayar", $delivery_order_jumlah_bayar);
		}
		$this->db->set("delivery_order_tipe_pembayaran", $delivery_order_tipe_pembayaran);
		$this->db->set("delivery_order_is_paid", $delivery_order_is_paid);
		$this->db->where("delivery_order_id", $delivery_order_id);

		$this->db->update("delivery_order");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
			var_dump($this->db->last_query());
		}

		return $queryupdate;
		// return $this->db->last_query();
	}

	public function Insert_DeliveryOrderProgressStatus($delivery_order_id, $status_progress_id, $status_progress_nama)
	{
		//insert delivery_order_progress
		$this->db->set("delivery_order_progress_id", "NewID()", FALSE);
		$this->db->set("delivery_order_id", $delivery_order_id);
		$this->db->set("status_progress_id", $status_progress_id);
		$this->db->set("status_progress_nama", $status_progress_nama);
		$this->db->set("delivery_order_progress_create_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_progress_create_tgl", "GETDATE()", FALSE);

		$this->db->insert("delivery_order_progress");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Update_ClosingPengirimanFDJR($delivery_order_batch_id, $delivery_order_batch_status, $kendaraan_km_akhir, $kendaraan_km_terpakai)
	{
		$this->db->set("delivery_order_batch_status", $delivery_order_batch_status);
		// $this->db->set("kendaraan_km_akhir", $kendaraan_km_akhir);
		// $this->db->set("kendaraan_km_terpakai", $kendaraan_km_terpakai);
		$this->db->where("delivery_order_batch_id", $delivery_order_batch_id);

		$this->db->update("delivery_order_batch");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Exec_ProsesSettlementDetail($delivery_order_batch_id, $sku_id)
	{
		$query = $this->db->query("exec proses_settlement_detail '$delivery_order_batch_id', '$sku_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_DetailBKB($picking_order_aktual_kode, $sku_id)
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
								WHERE picking_order_aktual_h.picking_order_aktual_kode = '$picking_order_aktual_kode' AND picking_order_aktual_d.sku_id = '$sku_id'
								ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_HeaderDO($delivery_order_kode)
	{
		$query = $this->db->query("SELECT
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									delivery_order.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_alias,
									delivery_order.delivery_order_status,
									FORMAT(delivery_order.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(delivery_order.delivery_order_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_tgl_expired_do,
									FORMAT(delivery_order.delivery_order_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_tgl_surat_jalan,
									FORMAT(delivery_order.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_rencana_kirim,
									delivery_order.client_wms_id,
									client_wms.client_wms_nama,
									client_wms.client_wms_alamat,
									delivery_order.delivery_order_keterangan,
									CASE WHEN delivery_order.delivery_order_tipe_pembayaran = '1' THEN 'KREDIT' ELSE 'TUNAI' END AS delivery_order_tipe_pembayaran,
									delivery_order.delivery_order_tipe_layanan,
									ISNULL(delivery_order.delivery_order_kirim_nama,'') AS delivery_order_kirim_nama,
									delivery_order.delivery_order_kirim_alamat,
									delivery_order.delivery_order_kirim_area,
									ISNULL(delivery_order.delivery_order_ambil_nama,'') AS delivery_order_ambil_nama,
									delivery_order.delivery_order_ambil_alamat,
									delivery_order.delivery_order_ambil_area
									FROM delivery_order
									LEFT JOIN client_wms
									ON client_wms.client_wms_id = delivery_order.client_wms_id
									LEFT JOIN tipe_delivery_order
									ON delivery_order.tipe_delivery_order_id = tipe_delivery_order.tipe_delivery_order_id
									WHERE delivery_order.delivery_order_kode = '$delivery_order_kode' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_DetailDO($delivery_order_kode, $sku_id)
	{
		$query = $this->db->query("SELECT
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									delivery_order_detail.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									delivery_order_detail.sku_request_expdate,
									ISNULL(delivery_order_detail.sku_keterangan,'') sku_keterangan,
									delivery_order_detail.sku_qty,
									delivery_order_detail.sku_qty_kirim
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
									LEFT JOIN sku
									ON sku.sku_id = delivery_order_detail.sku_id
									WHERE delivery_order.delivery_order_kode = '$delivery_order_kode'
									AND delivery_order_detail.sku_id = '$sku_id'
									ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Insert_Settlement($delivery_order_batch_id, $statussettlement)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $this->session->userdata('depo_id'))->get();

		$Tanggal = date('Y-m-d h:i:s');
		$Prefix = "STL";
		$Unit = $listDoBatch->row()->depo_kode_preffix;

		$query = $this->db->query("SELECT * FROM delivery_order WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");
		foreach ($query->result() as $row) {

			$query_kode = $this->db->query("declare @res varchar(50)  
					exec GenCodeGeneral ?, ?, ?, @res OUTPUT
					select @res as resultMessage", array($Tanggal, $Prefix, $Unit));

			$res = $query_kode->result_array();

			$delivery_order_settlement_kode = $res[0]['resultMessage'];

			$this->db->set("delivery_order_settlement_id", "NewID()", FALSE);
			$this->db->set("delivery_order_id", $row->delivery_order_id);
			$this->db->set("delivery_order_settlement_kode", $delivery_order_settlement_kode);
			$this->db->set("delivery_order_settlement_tgl", "GETDATE()", FALSE);
			$this->db->set("delivery_order_settlement_who", $this->session->userdata('pengguna_username'));
			$this->db->set("delivery_order_settlement_status", $statussettlement);

			$this->db->insert("delivery_order_settlement");
		}

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Get_StatusFDJR()
	{
		$query = $this->db->query("SELECT delivery_order_batch_status FROM delivery_order_batch WHERE depo_id = '" . $this->session->userdata('depo_id') . "' GROUP BY delivery_order_batch_status ORDER BY delivery_order_batch_status ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_KmAwalFDJR($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT kendaraan_km_awal FROM delivery_order_batch WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");
		return $query->result()[0]->kendaraan_km_awal;
	}

	public function Get_BTBHeader($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
									delivery_order_batch.delivery_order_batch_id,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order_batch.karyawan_id,
									karyawan.karyawan_nama,
									penerimaan_penjualan_detail2.pallet_id,
									pallet.pallet_kode,
									pallet_jenis.pallet_jenis_nama
									FROM delivery_order_batch
									LEFT JOIN penerimaan_penjualan
									ON penerimaan_penjualan.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN penerimaan_penjualan_detail2
									ON penerimaan_penjualan_detail2.penerimaan_penjualan_id = penerimaan_penjualan.penerimaan_penjualan_id
									LEFT JOIN pallet
									ON penerimaan_penjualan_detail2.pallet_id = pallet.pallet_id
									LEFT JOIN pallet_jenis
									ON pallet_jenis.pallet_jenis_id = pallet.pallet_jenis_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									GROUP BY delivery_order_batch.delivery_order_batch_id,
											delivery_order_batch.delivery_order_batch_kode,
											delivery_order_batch.karyawan_id,
											karyawan.karyawan_nama,
											penerimaan_penjualan_detail2.pallet_id,
											pallet.pallet_kode,
											pallet_jenis.pallet_jenis_nama");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_BTBDoRetur($delivery_order_batch_id, $perusahaan, $principle)
	{
		$query = $this->db->query("SELECT
										delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order.delivery_order_id,
										delivery_order.delivery_order_kode,
										FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') AS delivery_order_tgl_aktual_kirim,
										delivery_order.delivery_order_kirim_nama,
										principle.principle_kode AS principle,
										principle_brand.principle_brand_nama AS brand, 
										delivery_order_detail.sku_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_kemasan,
										sku.sku_satuan,
										delivery_order_detail.sku_qty,
										ISNULL(delivery_order_detail.sku_qty,0) - ISNULL(delivery_order_detail.sku_qty_kirim,0) AS sku_qty_kirim,
										ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) AS sisa_jumlah_terima,
										delivery_order_batch.karyawan_id,
										karyawan.karyawan_nama,
										tipe_delivery_order.tipe_delivery_order_alias,
										penerimaan.kondisi_barang
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima,
									kondisi_barang
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id,kondisi_barang) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									LEFT JOIN sku
									ON delivery_order_detail.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id' AND tipe_delivery_order.tipe_delivery_order_nama = 'Retur' AND sku.principle_id = '$principle' AND delivery_order.client_wms_id = '$perusahaan'
									AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) > 0
									ORDER BY delivery_order.delivery_order_kode,sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_BTBTerkirimSebagian($delivery_order_batch_id, $perusahaan, $principle)
	{
		$query = $this->db->query("SELECT
										delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order.delivery_order_id,
										delivery_order.delivery_order_kode,
										FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') AS delivery_order_tgl_aktual_kirim,
										delivery_order.delivery_order_kirim_nama,
										principle.principle_kode AS principle,
										principle_brand.principle_brand_nama AS brand,
										delivery_order_detail.sku_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_kemasan,
										sku.sku_satuan,
										delivery_order_detail.sku_qty,
										ISNULL(delivery_order_detail.sku_qty,0) - ISNULL(delivery_order_detail.sku_qty_kirim,0) AS sku_qty_kirim,
										ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) AS sisa_jumlah_terima,
										delivery_order_batch.karyawan_id,
										karyawan.karyawan_nama,
										tipe_delivery_order.tipe_delivery_order_alias,
										penerimaan.kondisi_barang
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima,
									kondisi_barang
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id,kondisi_barang) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									LEFT JOIN sku
									ON delivery_order_detail.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id' AND delivery_order.delivery_order_status = 'partially delivered' AND sku.principle_id = '$principle' AND delivery_order.client_wms_id = '$perusahaan'
									AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) > 0
									ORDER BY delivery_order.delivery_order_kode,sku.sku_kode ASC ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_BTBGagal($delivery_order_batch_id, $perusahaan, $principle)
	{
		$query = $this->db->query("SELECT
										delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order.delivery_order_id,
										delivery_order.delivery_order_kode,
										FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') AS delivery_order_tgl_aktual_kirim,
										delivery_order.delivery_order_kirim_nama,
										principle.principle_kode AS principle,
										principle_brand.principle_brand_nama AS brand, 
										delivery_order_detail.sku_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_kemasan,
										sku.sku_satuan,
										delivery_order_detail.sku_qty,
										ISNULL(delivery_order_detail.sku_qty,0) - ISNULL(delivery_order_detail.sku_qty_kirim,0) AS sku_qty_kirim,
										ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) AS sisa_jumlah_terima,
										delivery_order_batch.karyawan_id,
										karyawan.karyawan_nama,
										tipe_delivery_order.tipe_delivery_order_alias,
										penerimaan.kondisi_barang
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima,
									kondisi_barang
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id,kondisi_barang) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									LEFT JOIN sku
									ON delivery_order_detail.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id' AND delivery_order.delivery_order_status = 'not delivered' AND sku.principle_id = '$principle' AND delivery_order.client_wms_id = '$perusahaan'
									AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) > 0
									ORDER BY delivery_order.delivery_order_kode,sku.sku_kode ASC ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetBTBPallet($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
										delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order.delivery_order_id,
										delivery_order.delivery_order_kode,
										FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') AS delivery_order_tgl_aktual_kirim,
										delivery_order.delivery_order_kirim_nama,
										principle.principle_kode AS principle,
										principle_brand.principle_brand_nama AS brand, 
										delivery_order_detail.sku_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_kemasan,
										sku.sku_satuan,
										delivery_order_detail.sku_qty,
										ISNULL(delivery_order_detail.sku_qty,0) - ISNULL(delivery_order_detail.sku_qty_kirim,0) AS sku_qty_kirim,
										delivery_order_batch.karyawan_id,
										karyawan.karyawan_nama,
										tipe_delivery_order.tipe_delivery_order_alias
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN sku
									ON delivery_order_detail.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id' AND delivery_order.delivery_order_status = 'not delivered'
									ORDER BY delivery_order.delivery_order_kode,sku.sku_kode ASC ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}
	}

	public function Get_GudangPenerima()
	{
		$query = $this->db->query("SELECT
										depo_detail_id,
										depo_detail_nama
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND depo_detail_is_gudang_penerima = '1'
									ORDER BY depo_detail_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_SKU($sku_induk, $sku, $principle, $brand)
	{
		if ($sku_induk == "") {
			$sku_induk = "";
		} else {
			$sku_induk = " AND sku_induk.sku_induk_nama LIKE '%$sku_induk%' ";
		}
		if ($sku == "") {
			$sku = "";
		} else {
			$sku = " AND sku.sku_nama_produk LIKE '%$sku%' ";
		}
		if ($brand == "") {
			$brand = "";
		} else {
			$brand = " AND principle_brand.principle_brand_nama LIKE '%$brand%' ";
		}

		$query = $this->db->query("SELECT
										principle.principle_kode AS principle,
										principle_brand.principle_brand_nama AS brand,
										sku.sku_id,
										sku.sku_kode,
										sku.sku_induk_id,
										sku_induk.sku_induk_nama,
										sku.sku_nama_produk,
										sku.sku_kemasan,
										sku.sku_satuan,
										sku.sku_harga_jual,
										sku.sku_weight_unit,
										sku.sku_weight,
										sku.sku_length_unit,
										sku.sku_length,
										sku.sku_width_unit,
										sku.sku_width,
										sku.sku_height_unit,
										sku.sku_height,
										sku.sku_volume_unit,
										sku.sku_volume
									FROM sku
									LEFT JOIN sku_induk
									ON sku.sku_induk_id = sku_induk.sku_induk_id
									LEFT JOIN principle
										ON sku.principle_id = principle.principle_id
									LEFT JOIN principle_brand
										ON sku.principle_brand_id = principle_brand.principle_brand_id
									WHERE sku.sku_id IS NOT NULL AND sku.principle_id = '$principle' " . $sku_induk . " " . $sku . " " . $brand . "
									ORDER BY principle.principle_kode,principle_brand.principle_brand_nama,sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_SKUDelivery($delivery_order_batch_id)
	{

		$query = $this->db->query("SELECT
										delivery_order_detail_temp.delivery_order_detail_id,
										delivery_order_detail_temp.sku_id,
										sku.sku_kode,
										sku.sku_induk_id,
										sku.sku_nama_produk,
										sku.sku_kemasan,
										sku.sku_satuan,
										sku.sku_harga_jual,
										sku.sku_weight_unit,
										sku.sku_weight,
										sku.sku_length_unit,
										sku.sku_length,
										sku.sku_width_unit,
										sku.sku_width,
										sku.sku_height_unit,
										sku.sku_height,
										sku.sku_volume_unit,
										sku.sku_volume
									FROM delivery_order_detail_temp
									LEFT JOIN sku
									ON sku.sku_id = delivery_order_detail_temp.sku_id
									WHERE delivery_order_detail_temp.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_Customer($client_wms_id, $client_pt_nama, $client_pt_alamat, $client_pt_telepon, $area_id)
	{
		if ($client_pt_nama == "") {
			$client_pt_nama = "";
		} else {
			$client_pt_nama = " AND client_pt_nama LIKE '%$client_pt_nama%' ";
		}
		if ($client_pt_alamat == "") {
			$client_pt_alamat = "";
		} else {
			$client_pt_alamat = " AND client_pt_alamat LIKE '%$client_pt_alamat%' ";
		}
		if ($client_pt_telepon == "") {
			$client_pt_telepon = "";
		} else {
			$kemaclient_pt_teleponsan = " AND client_pt_telepon LIKE '%$client_pt_telepon%' ";
		}
		if ($area_id == "") {
			$area_id = "";
		} else {
			$area_id = " AND area_id = '$area_id' ";
		}

		$query = $this->db->query("SELECT
									client_pt.*,
									area.area_nama
									FROM client_pt
									LEFT JOIN client_wms_client_pt
									ON client_wms_client_pt.client_pt_id = client_pt.client_pt_id
									LEFT JOIN area
									ON area.area_id = client_pt.area_id
									WHERE client_wms_client_pt.client_wms_id = '$client_wms_id'
									" . $client_pt_nama . " " . $client_pt_alamat . " " . $client_pt_telepon . " " . $area_id . "
									ORDER BY client_pt.client_pt_nama ASC ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_SKU_Expired_Date($sku_id)
	{
		$this->db->distinct()->select("sku_id,FORMAT(sku_stock_expired_date,'dd-MM-yyyy') AS sku_stock_expired_date")
			->from("sku_stock")
			->where("sku_id", $sku_id)
			->where("depo_id", $this->session->userdata('depo_id'))
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

	public function Get_JenisPallet()
	{
		$this->db->select("*")
			->from("pallet_jenis")
			->order_by("pallet_jenis_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Insert_PalletTemp($delivery_order_batch_id, $pallet_id, $pallet_kode)
	{
		//insert delivery_order_progress
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("depo_id", $this->session->userdata('depo_id'));
		$this->db->set("pallet_kode", $pallet_kode);
		$this->db->set("pallet_jenis_id", "86C84F5F-D967-4DA6-8B6E-3F81679232F3");
		$this->db->set("pallet_tanggal_create", "GETDATE()", FALSE);
		$this->db->set("pallet_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("pallet_is_aktif", '1');

		$this->db->insert("pallet_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_PalletTemp2($delivery_order_batch_id, $pallet_id)
	{
		$query = $this->db->query("INSERT INTO pallet_temp
									SELECT
									'" . $pallet_id . "',
									pallet_jenis_id,
									'" . $delivery_order_batch_id . "',
									depo_id,
									rak_lajur_detail_id,
									pallet_kode,
									pallet_tanggal_create,
									pallet_who_create,
									1,
									NULL,
									NULL,
									NULL,
									NULL
									FROM pallet
									WHERE pallet_id = '$pallet_id' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Update_PalletTemp($pallet_id, $pallet_jenis_id, $pallet_kode)
	{
		// $this->db->set("pallet_kode", $pallet_kode);
		$this->db->set("pallet_jenis_id", $pallet_jenis_id);

		$this->db->where("pallet_id", $pallet_id);

		$this->db->update("pallet_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Insert_PalletDetailTemp($pallet_id, $sku_id, $sku_stock_qty, $penerimaan_tipe_id)
	{
		//insert delivery_order_progress
		$this->db->set("pallet_detail_id", "NewID()", FALSE);
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_stock_qty", $sku_stock_qty);
		$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);

		$this->db->insert("pallet_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Update_PalletDetailTemp($pallet_id, $pallet_detail_id, $sku_stock_id, $sku_stock_expired_date, $sku_stock_qty)
	{
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
		$this->db->set("sku_stock_qty", $sku_stock_qty);

		$this->db->where("pallet_id", $pallet_id);
		$this->db->where("pallet_detail_id", $pallet_detail_id);

		$this->db->update("pallet_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Delete_PalletTemp($pallet_id)
	{
		$this->db->trans_begin();

		$this->db->where("pallet_id", $pallet_id);

		$this->db->delete("pallet_detail_temp");

		$this->db->where("pallet_id", $pallet_id);

		$this->db->delete("pallet_temp");

		$error = $this->db->error();

		if ($error['message'] != '' || $error['message'] != null)
		//if( $error['message'] != '' && $error['message'] != null )
		{
			$res = $error['message']; // Error

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

	public function Delete_PalletDetailTemp($pallet_detail_id)
	{
		$this->db->trans_begin();

		$this->db->where("pallet_detail_id", $pallet_detail_id);

		$this->db->delete("pallet_detail_temp");

		$error = $this->db->error();

		if ($error['message'] != '' || $error['message'] != null)
		//if( $error['message'] != '' && $error['message'] != null )
		{
			$res = $error['message']; // Error

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

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function Get_Pallet($delivery_order_batch_id)
	{
		$this->db->select("*")
			->from("pallet_temp")
			->where("delivery_order_batch_id", $delivery_order_batch_id)
			->order_by("pallet_kode");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Check_SKUPallet($pallet_id, $sku_id)
	{
		$this->db->select("sku_id")
			->from("pallet_detail_temp")
			->where("pallet_id", $pallet_id)
			->where("sku_id", $sku_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->sku_id;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_PalletDetail($pallet_id)
	{
		$query = $this->db->query("SELECT
									pallet_detail_temp.pallet_id,
									pallet_detail_temp.pallet_detail_id,
									pallet_temp.delivery_order_batch_id,
									principle.principle_kode AS principle,
									principle_brand.principle_brand_nama AS brand,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_induk_id,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									pallet_detail_temp.sku_stock_id,
									FORMAT(pallet_detail_temp.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
									ISNULL(pallet_detail_temp.sku_stock_qty, 0) AS sku_stock_qty,
									penerimaan_tipe.penerimaan_tipe_nama
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_detail_temp.pallet_id = pallet_temp.pallet_id
									LEFT JOIN sku
									ON pallet_detail_temp.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN penerimaan_tipe
									ON penerimaan_tipe.penerimaan_tipe_id = pallet_detail_temp.penerimaan_tipe_id
									WHERE pallet_detail_temp.pallet_id = '$pallet_id'
									ORDER BY penerimaan_tipe.penerimaan_tipe_nama, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}



	public function Get_PalletDetailTable($pallet_id)
	{
		$query = $this->db->query("SELECT
									pallet_detail.pallet_id,
									pallet_detail.pallet_detail_id,
									principle.principle_kode AS principle,
									principle_brand.principle_brand_nama AS brand,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_induk_id,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									pallet_detail.sku_stock_id,
									ISNULL(FORMAT(pallet_detail.sku_stock_expired_date, 'dd-MM-yyyy'),'') AS sku_stock_expired_date,
									ISNULL(pallet_detail.sku_stock_qty, 0) AS sku_stock_qty,
									penerimaan_tipe.penerimaan_tipe_nama
									FROM pallet_detail
									LEFT JOIN sku
									ON pallet_detail.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN penerimaan_tipe
									ON penerimaan_tipe.penerimaan_tipe_id = pallet_detail.penerimaan_tipe_id
									WHERE pallet_detail.pallet_id = '$pallet_id'
									ORDER BY penerimaan_tipe.penerimaan_tipe_nama,sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Check_QtySKUPalletTemp($delivery_order_batch_id, $sku_id, $sku_stock_qty, $penerimaan_tipe_nama)
	{
		if ($penerimaan_tipe_nama == "retur") {

			$query = $this->db->query("SELECT
									delivery_order_detail.sku_id,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) AS sku_qty,
									SUM(ISNULL(pallet.sku_stock_qty, 0)) AS sku_stock_qty,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) - (SUM(ISNULL(pallet.sku_stock_qty, 0)) + " . $sku_stock_qty . ") AS sku_stock_qty_max
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
									LEFT JOIN (SELECT
									pallet_temp.delivery_order_batch_id,
									pallet_detail_temp.sku_id,
									SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
									GROUP BY pallet_temp.delivery_order_batch_id,
											sku_id) pallet
									ON pallet.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									AND pallet.sku_id = delivery_order_detail.sku_id
									WHERE delivery_order.delivery_order_batch_id = '$delivery_order_batch_id'
									AND delivery_order_detail.sku_id = '$sku_id'
									AND delivery_order.tipe_delivery_order_id = 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD'
									GROUP BY delivery_order_detail.sku_id");
		} else if ($penerimaan_tipe_nama == "terkirim sebagian") {

			$query = $this->db->query("SELECT
									delivery_order_detail.sku_id,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) AS sku_qty,
									SUM(ISNULL(pallet.sku_stock_qty, 0)) AS sku_stock_qty,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) - (SUM(ISNULL(pallet.sku_stock_qty, 0)) + " . $sku_stock_qty . ") AS sku_stock_qty_max
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
									LEFT JOIN (SELECT
									pallet_temp.delivery_order_batch_id,
									pallet_detail_temp.sku_id,
									SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
									GROUP BY pallet_temp.delivery_order_batch_id,
											sku_id) pallet
									ON pallet.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									AND pallet.sku_id = delivery_order_detail.sku_id
									WHERE delivery_order.delivery_order_batch_id = '$delivery_order_batch_id'
									AND delivery_order_detail.sku_id = '$sku_id'
									AND delivery_order.delivery_order_status = 'partially delivered'
									GROUP BY delivery_order_detail.sku_id");
		} else if ($penerimaan_tipe_nama == "tidak terkirim") {

			$query = $this->db->query("SELECT
									delivery_order_detail.sku_id,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) AS sku_qty,
									SUM(ISNULL(pallet.sku_stock_qty, 0)) AS sku_stock_qty,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) - (SUM(ISNULL(pallet.sku_stock_qty, 0)) + " . $sku_stock_qty . ") AS sku_stock_qty_max
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
									LEFT JOIN (SELECT
									pallet_temp.delivery_order_batch_id,
									pallet_detail_temp.sku_id,
									SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
									GROUP BY pallet_temp.delivery_order_batch_id,
											sku_id) pallet
									ON pallet.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									AND pallet.sku_id = delivery_order_detail.sku_id
									WHERE delivery_order.delivery_order_batch_id = '$delivery_order_batch_id'
									AND delivery_order_detail.sku_id = '$sku_id'
									AND delivery_order.delivery_order_status = 'not delivered'
									GROUP BY delivery_order_detail.sku_id");
		}

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->sku_stock_qty_max;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Update_QtySKUPalletTemp($pallet_detail_id, $sku_stock_qty)
	{
		$this->db->set("sku_stock_qty", $sku_stock_qty);

		$this->db->where("pallet_detail_id", $pallet_detail_id);

		$this->db->update("pallet_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Update_SkuExpDatePalletTemp($pallet_detail_id, $sku_id, $sku_stock_expired_date, $depo_detail_id)
	{
		$cek_sku = $this->db->query("SELECT sku_stock_id FROM sku_stock WHERE FORMAT(sku_stock_expired_date,'yyyy-MM-dd') = '$sku_stock_expired_date' AND sku_id = '$sku_id' AND depo_detail_id = '$depo_detail_id' ");
		// $cek_sku = $this->db->query("SELECT sku_stock_id FROM sku_stock WHERE FORMAT(sku_stock_expired_date,'yyyy-MM-dd') = '$sku_stock_expired_date' AND sku_id = '$sku_id' ");

		if ($cek_sku->num_rows() > 0) {
			$this->db->set("sku_stock_id", $cek_sku->row(0)->sku_stock_id);
		}

		$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);

		$this->db->where("pallet_detail_id", $pallet_detail_id);

		$this->db->update("pallet_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
		// return $this->db->last_query();
	}

	// public function Update_SkuExpDatePalletTemp($pallet_detail_id, $sku_stock_expired_date)
	// {
	// 	$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);

	// 	$this->db->where("pallet_detail_id", $pallet_detail_id);

	// 	$this->db->update("pallet_detail_temp");

	// 	$affectedrows = $this->db->affected_rows();
	// 	if ($affectedrows > 0) {
	// 		$queryupdate = 1;
	// 	} else {
	// 		$queryupdate = 0;
	// 	}

	// 	return $queryupdate;
	// 	// return $this->db->last_query();
	// }

	public function Insert_PalletDetailTempDoRetur($delivery_order_batch_id, $pallet_id)
	{
		$query = $this->db->query("INSERT INTO pallet_detail_temp
									SELECT
									NEWID() AS pallet_detail_id,
									'" . $pallet_id . "' AS pallet_id,
									delivery_order_detail.sku_id,
									NULL,
									NULL,
									ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) AS sku_stock_qty,
									'BDCFCBE1-52CF-404F-84B5-A19F0918CA8D' AS penerimaan_tipe_id,
									NULL,
									GETDATE(),
									NULL,
									NULL,
									NULL
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) > 0
									AND delivery_order.delivery_order_status = 'Retur' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Insert_PalletDetailTempTerkirimSebagian($delivery_order_batch_id, $pallet_id)
	{
		$query = $this->db->query("INSERT INTO pallet_detail_temp
									SELECT
									NEWID() AS pallet_detail_id,
									'" . $pallet_id . "' AS pallet_id,
									delivery_order_detail.sku_id,
									NULL,
									NULL,
									ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) AS sku_stock_qty,
									'29F5A94F-C55E-4BA0-B3EE-57A93327735F' AS penerimaan_tipe_id,
									NULL,
									GETDATE(),
									NULL,
									NULL,
									NULL
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) > 0
									AND delivery_order.delivery_order_status = 'partially delivered' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}


	public function Insert_PalletDetailTempGagal($delivery_order_batch_id, $pallet_id)
	{
		$query = $this->db->query("INSERT INTO pallet_detail_temp
									SELECT
									NEWID() AS pallet_detail_id,
									'" . $pallet_id . "' AS pallet_id,
									delivery_order_detail.sku_id,
									NULL,
									NULL,
									ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) AS sku_stock_qty,
									'79F2522A-CEA5-4FF8-BC79-C0B28808877D' AS penerimaan_tipe_id,
									NULL,
									GETDATE(),
									NULL,
									NULL,
									NULL
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima,0) > 0
									AND delivery_order.delivery_order_status = 'not delivered' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Check_PenerimaanPenjualanFDJR($delivery_order_batch_id, $penerima_tipe)
	{
		// $this->db->select("delivery_order_batch_id")
		// 	->from("penerimaan_penjualan")
		// 	->where("delivery_order_batch_id", $delivery_order_batch_id);
		// $query = $this->db->get();

		$query = $this->db->query("SELECT
									penerimaan_penjualan.delivery_order_batch_id
									FROM penerimaan_penjualan
									LEFT JOIN penerimaan_tipe
									ON penerimaan_penjualan.penerimaan_tipe_id = penerimaan_tipe.penerimaan_tipe_id
									WHERE penerimaan_penjualan.delivery_order_batch_id = '$delivery_order_batch_id' AND penerimaan_tipe.penerimaan_tipe_nama = '$penerima_tipe'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->delivery_order_batch_id;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Insert_PenerimaanPenjualan($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $delivery_order_batch_id, $depo_detail_id, $karyawan_id, $penerimaan_tipe_id, $penerimaan_tipe_nama, $penerimaan_penjualan_keterangan, $client_wms_id, $principle_id)
	{
		// $this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		// $this->db->set("depo_id", $this->session->userdata('depo_id'));
		// $this->db->set("depo_detail_id", $depo_detail_id);
		// $this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		// $this->db->set("karyawan_id", $karyawan_id);
		// $this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		// $this->db->set("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
		// $this->db->set("penerimaan_penjualan_tgl", "GETDATE()", FALSE);
		// $this->db->set("penerimaan_penjualan_who_create", $this->session->userdata('pengguna_username'));
		// $this->db->set("penerimaan_penjualan_keterangan", $penerimaan_penjualan_keterangan);
		// $this->db->set("penerimaan_penjualan_tgl_create", "GETDATE()", FALSE);

		// $this->db->insert("penerimaan_penjualan");

		if ($penerimaan_tipe_nama == "retur") {
			$penerimaan_tipe = " AND tipe_delivery_order.tipe_delivery_order_nama='Retur' ";
			$penerimaan_tipe_id = "BDCFCBE1-52CF-404F-84B5-A19F0918CA8D";

			//insert delivery_order_progress
			$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
			$this->db->set("depo_id", $this->session->userdata('depo_id'));
			$this->db->set("depo_detail_id", $depo_detail_id);
			$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
			$this->db->set("karyawan_id", $karyawan_id);
			$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
			$this->db->set("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
			$this->db->set("penerimaan_penjualan_tgl", "GETDATE()", FALSE);
			$this->db->set("penerimaan_penjualan_who_create", $this->session->userdata('pengguna_username'));
			$this->db->set("penerimaan_penjualan_keterangan", $penerimaan_penjualan_keterangan);
			$this->db->set("penerimaan_penjualan_tgl_create", "GETDATE()", FALSE);
			$this->db->set("client_wms_id", $client_wms_id);
			$this->db->set("principle_id", $principle_id);

			$this->db->insert("penerimaan_penjualan");

			$affectedrows = $this->db->affected_rows();
			if ($affectedrows > 0) {
				$query_detail = $this->db->query("INSERT INTO penerimaan_penjualan_detail
													SELECT
  NEWID() AS penerimaan_penjualan_detail_id,
  '" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
  delivery_order_detail.delivery_order_id,
  delivery_order_detail.sku_id,
  delivery_order_detail.sku_qty,
  ISNULL(penerimaan.sku_stock_qty,0) AS sku_jumlah_terima
FROM delivery_order_batch
LEFT JOIN delivery_order
  ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
LEFT JOIN delivery_order_detail
  ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
LEFT JOIN (SELECT
  pallet_temp.delivery_order_batch_id,
  pallet_detail_temp.penerimaan_tipe_id,
  pallet_detail_temp.sku_id,
  SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
FROM pallet_temp
LEFT JOIN pallet_detail_temp
  ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
GROUP BY pallet_temp.delivery_order_batch_id,
         pallet_detail_temp.penerimaan_tipe_id,
         pallet_detail_temp.sku_id) penerimaan
  ON penerimaan.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
  AND penerimaan.sku_id = delivery_order_detail.sku_id
LEFT JOIN tipe_delivery_order
  ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) > 0
AND ISNULL(penerimaan.sku_stock_qty,0) > 0
" . $penerimaan_tipe . " ");
				$queryinsert = 1;
			} else {
				$queryinsert = 0;
			}
		} else if ($penerimaan_tipe_nama == "terkirim sebagian") {
			$penerimaan_tipe = " AND delivery_order.delivery_order_status='partially delivered' ";
			$penerimaan_tipe_id = "29F5A94F-C55E-4BA0-B3EE-57A93327735F";

			//insert delivery_order_progress
			$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
			$this->db->set("depo_id", $this->session->userdata('depo_id'));
			$this->db->set("depo_detail_id", $depo_detail_id);
			$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
			$this->db->set("karyawan_id", $karyawan_id);
			$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
			$this->db->set("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
			$this->db->set("penerimaan_penjualan_tgl", "GETDATE()", FALSE);
			$this->db->set("penerimaan_penjualan_who_create", $this->session->userdata('pengguna_username'));
			$this->db->set("penerimaan_penjualan_keterangan", $penerimaan_penjualan_keterangan);
			$this->db->set("penerimaan_penjualan_tgl_create", "GETDATE()", FALSE);
			$this->db->set("client_wms_id", $client_wms_id);
			$this->db->set("principle_id", $principle_id);

			$this->db->insert("penerimaan_penjualan");

			$affectedrows = $this->db->affected_rows();
			if ($affectedrows > 0) {
				$query_detail = $this->db->query("INSERT INTO penerimaan_penjualan_detail
													SELECT
  NEWID() AS penerimaan_penjualan_detail_id,
  '" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
  delivery_order_detail.delivery_order_id,
  delivery_order_detail.sku_id,
  delivery_order_detail.sku_qty,
  ISNULL(penerimaan.sku_stock_qty,0) AS sku_jumlah_terima
FROM delivery_order_batch
LEFT JOIN delivery_order
  ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
LEFT JOIN delivery_order_detail
  ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
LEFT JOIN (SELECT
  pallet_temp.delivery_order_batch_id,
  pallet_detail_temp.penerimaan_tipe_id,
  pallet_detail_temp.sku_id,
  SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
FROM pallet_temp
LEFT JOIN pallet_detail_temp
  ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
GROUP BY pallet_temp.delivery_order_batch_id,
         pallet_detail_temp.penerimaan_tipe_id,
         pallet_detail_temp.sku_id) penerimaan
  ON penerimaan.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
  AND penerimaan.sku_id = delivery_order_detail.sku_id
LEFT JOIN tipe_delivery_order
  ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) > 0
AND ISNULL(penerimaan.sku_stock_qty,0) > 0
" . $penerimaan_tipe . " ");
				$queryinsert = 1;
			} else {
				$queryinsert = 0;
			}
		} else if ($penerimaan_tipe_nama == "tidak terkirim") {
			$penerimaan_tipe = " AND delivery_order.delivery_order_status='not delivered' ";
			$penerimaan_tipe_id = "79F2522A-CEA5-4FF8-BC79-C0B28808877D";

			//insert delivery_order_progress
			$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
			$this->db->set("depo_id", $this->session->userdata('depo_id'));
			$this->db->set("depo_detail_id", $depo_detail_id);
			$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
			$this->db->set("karyawan_id", $karyawan_id);
			$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
			$this->db->set("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
			$this->db->set("penerimaan_penjualan_tgl", "GETDATE()", FALSE);
			$this->db->set("penerimaan_penjualan_who_create", $this->session->userdata('pengguna_username'));
			$this->db->set("penerimaan_penjualan_keterangan", $penerimaan_penjualan_keterangan);
			$this->db->set("penerimaan_penjualan_tgl_create", "GETDATE()", FALSE);
			$this->db->set("client_wms_id", $client_wms_id);
			$this->db->set("principle_id", $principle_id);

			$this->db->insert("penerimaan_penjualan");

			$affectedrows = $this->db->affected_rows();
			if ($affectedrows > 0) {
				$query_detail = $this->db->query("INSERT INTO penerimaan_penjualan_detail
													SELECT
  NEWID() AS penerimaan_penjualan_detail_id,
  '" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
  delivery_order_detail.delivery_order_id,
  delivery_order_detail.sku_id,
  delivery_order_detail.sku_qty,
  ISNULL(penerimaan.sku_stock_qty,0) AS sku_jumlah_terima
FROM delivery_order_batch
LEFT JOIN delivery_order
  ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
LEFT JOIN delivery_order_detail
  ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
LEFT JOIN (SELECT
  pallet_temp.delivery_order_batch_id,
  pallet_detail_temp.penerimaan_tipe_id,
  pallet_detail_temp.sku_id,
  SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
FROM pallet_temp
LEFT JOIN pallet_detail_temp
  ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
GROUP BY pallet_temp.delivery_order_batch_id,
         pallet_detail_temp.penerimaan_tipe_id,
         pallet_detail_temp.sku_id) penerimaan
  ON penerimaan.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
  AND penerimaan.sku_id = delivery_order_detail.sku_id
LEFT JOIN tipe_delivery_order
  ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) > 0
AND ISNULL(penerimaan.sku_stock_qty,0) > 0
" . $penerimaan_tipe . " ");

				$query_do_gagal = $this->db->query("SELECT * FROM delivery_order WHERE delivery_order_batch_id='$delivery_order_batch_id' AND delivery_order_status='not delivered'");
				foreach ($query_do_gagal->result() as $row) {
					$this->db->set("sku_qty", 0);
					$this->db->set("sku_qty_kirim", 0);

					$this->db->where("delivery_order_id", $row->delivery_order_id);

					$this->db->update("delivery_order_detail");
				}

				$queryinsert = 1;
			} else {
				$queryinsert = 0;
			}
		} else if ($penerimaan_tipe_nama == "titipan") {
			$penerimaan_tipe = "";
			$penerimaan_tipe_id = "A9F3967E-94ED-4761-B385-4770FEEC229A";

			//insert delivery_order_progress
			$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
			$this->db->set("depo_id", $this->session->userdata('depo_id'));
			$this->db->set("depo_detail_id", $depo_detail_id);
			$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
			$this->db->set("karyawan_id", $karyawan_id);
			$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
			$this->db->set("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
			$this->db->set("penerimaan_penjualan_tgl", "GETDATE()", FALSE);
			$this->db->set("penerimaan_penjualan_who_create", $this->session->userdata('pengguna_username'));
			$this->db->set("penerimaan_penjualan_keterangan", $penerimaan_penjualan_keterangan);
			$this->db->set("penerimaan_penjualan_tgl_create", "GETDATE()", FALSE);
			$this->db->set("client_wms_id", $client_wms_id);
			$this->db->set("principle_id", $principle_id);

			$this->db->insert("penerimaan_penjualan");

			$affectedrows = $this->db->affected_rows();
			if ($affectedrows > 0) {
				$query_detail = $this->db->query("INSERT INTO penerimaan_penjualan_detail
									SELECT
										NEWID() AS penerimaan_penjualan_detail_id,
										'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
										NULL,
										pallet_temp.sku_id,
										pallet_temp.sku_stock_qty,
										pallet_temp.sku_stock_qty AS sku_jumlah_terima
										FROM delivery_order_batch
										LEFT JOIN (SELECT
											pallet_temp.pallet_id,
											pallet_temp.delivery_order_batch_id,
											pallet_detail_temp.sku_id,
											pallet_detail_temp.penerimaan_tipe_id,
											SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
										FROM pallet_temp
										LEFT JOIN pallet_detail_temp
										ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
										WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id' AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
										GROUP BY pallet_temp.pallet_id,
											pallet_temp.delivery_order_batch_id,
											pallet_detail_temp.sku_id,
											pallet_detail_temp.penerimaan_tipe_id) pallet_temp
										ON pallet_temp.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
										WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'");

				$queryinsert = 1;
			} else {
				$queryinsert = 0;
			}
		}

		$query_detail2 = $this->db->query("INSERT INTO penerimaan_penjualan_detail2
									SELECT
										NEWID() AS penerimaan_penjualan_detail2_id,
										'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
										pallet_id
										FROM pallet_temp
										WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_PenerimaanPenjualanDetail($delivery_order_batch_id, $penerimaan_penjualan_id, $penerimaan_tipe)
	{
		$penerimaan_tipe_id = "NULL";

		if ($penerimaan_tipe == "retur") {
			$penerimaan_tipe = " AND tipe_delivery_order.tipe_delivery_order_nama='Retur' ";
			$penerimaan_tipe_id = "BDCFCBE1-52CF-404F-84B5-A19F0918CA8D";

			$query = $this->db->query("INSERT INTO penerimaan_penjualan_detail
										SELECT
											NEWID() AS penerimaan_penjualan_detail_id,
											'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
											delivery_order_detail.delivery_order_id,
											delivery_order_detail.sku_id,
											delivery_order_detail.sku_qty,
											delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim AS sku_jumlah_terima
											FROM delivery_order_batch
											LEFT JOIN delivery_order
											ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
											LEFT JOIN delivery_order_detail
											ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
											LEFT JOIN tipe_delivery_order
											ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
											WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id' " . $penerimaan_tipe . " ");
		} else if ($penerimaan_tipe == "terkirim sebagian") {
			$penerimaan_tipe = " AND delivery_order.delivery_order_status='partially delivered' ";
			$penerimaan_tipe_id = "29F5A94F-C55E-4BA0-B3EE-57A93327735F";

			$query = $this->db->query("INSERT INTO penerimaan_penjualan_detail
										SELECT
											NEWID() AS penerimaan_penjualan_detail_id,
											'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
											delivery_order_detail.delivery_order_id,
											delivery_order_detail.sku_id,
											delivery_order_detail.sku_qty,
											delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim AS sku_jumlah_terima
											FROM delivery_order_batch
											LEFT JOIN delivery_order
											ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
											LEFT JOIN delivery_order_detail
											ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
											LEFT JOIN tipe_delivery_order
											ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
											WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id' " . $penerimaan_tipe . " ");
		} else if ($penerimaan_tipe == "tidak terkirim") {
			$penerimaan_tipe = " AND delivery_order.delivery_order_status='not delivered' ";
			$penerimaan_tipe_id = "79F2522A-CEA5-4FF8-BC79-C0B28808877D";

			$query = $this->db->query("INSERT INTO penerimaan_penjualan_detail
										SELECT
											NEWID() AS penerimaan_penjualan_detail_id,
											'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
											delivery_order_detail.delivery_order_id,
											delivery_order_detail.sku_id,
											delivery_order_detail.sku_qty,
											delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim AS sku_jumlah_terima
											FROM delivery_order_batch
											LEFT JOIN delivery_order
											ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
											LEFT JOIN delivery_order_detail
											ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
											LEFT JOIN tipe_delivery_order
											ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
											WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id' " . $penerimaan_tipe . " ");

			$query_do_gagal = $this->db->query("SELECT * FROM delivery_order WHERE delivery_order_batch_id='$delivery_order_batch_id' AND delivery_order_status='not delivered'");
			foreach ($query_do_gagal->result() as $row) {
				$this->db->set("sku_qty", 0);
				$this->db->set("sku_qty_kirim", 0);

				$this->db->where("delivery_order_id", $row->delivery_order_id);

				$this->db->update("delivery_order_detail");
			}
		} else if ($penerimaan_tipe == "titipan") {
			$penerimaan_tipe = "";
			$penerimaan_tipe_id = "A9F3967E-94ED-4761-B385-4770FEEC229A";

			$query = $this->db->query("INSERT INTO penerimaan_penjualan_detail
									SELECT
										NEWID() AS penerimaan_penjualan_detail_id,
										'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
										NULL,
										pallet_temp.sku_id,
										pallet_temp.sku_stock_qty,
										pallet_temp.sku_stock_qty AS sku_jumlah_terima
										FROM delivery_order_batch
										LEFT JOIN (SELECT
											pallet_temp.pallet_id,
											pallet_temp.delivery_order_batch_id,
											pallet_detail_temp.sku_id,
											pallet_detail_temp.penerimaan_tipe_id,
											SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
										FROM pallet_temp
										LEFT JOIN pallet_detail_temp
										ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
										WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id' AND pallet_detail_temp.penerimaan_tipe_id = 'A9F3967E-94ED-4761-B385-4770FEEC229A'
										GROUP BY pallet_temp.pallet_id,
											pallet_temp.delivery_order_batch_id,
											pallet_detail_temp.sku_id,
											pallet_detail_temp.penerimaan_tipe_id) pallet_temp
										ON pallet_temp.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
										WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'");
		}

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $this->db->last_query();
		// return $queryinsert;
	}

	public function Insert_PenerimaanPenjualanDetailTitipan($delivery_order_batch_id, $penerimaan_penjualan_id)
	{
		$query = $this->db->query("INSERT INTO penerimaan_penjualan_detail
									SELECT
										NEWID() AS penerimaan_penjualan_detail_id,
										'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
										NULL,
										pallet_temp.sku_id,
										pallet_temp.sku_stock_qty,
										pallet_temp.sku_stock_qty AS sku_jumlah_terima
										FROM delivery_order_batch
										LEFT JOIN (SELECT
											pallet_temp.pallet_id,
											pallet_temp.delivery_order_batch_id,
											pallet_detail_temp.sku_id,
											SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
										FROM pallet_temp
										LEFT JOIN pallet_detail_temp
										ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
										WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
										GROUP BY pallet_temp.pallet_id,
											pallet_temp.delivery_order_batch_id,
											pallet_detail_temp.sku_id) pallet_temp
										ON pallet_temp.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
										WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Insert_PenerimaanPenjualanDetail2($delivery_order_batch_id, $penerimaan_penjualan_id)
	{
		$query = $this->db->query("INSERT INTO penerimaan_penjualan_detail2
									SELECT
										NEWID() AS penerimaan_penjualan_detail2_id,
										'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
										pallet_id
										FROM pallet_temp
										WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Check_PenerimaanPenjualan($penerimaan_penjualan_kode)
	{
		$this->db->select("penerimaan_penjualan_kode")
			->from("penerimaan_penjualan")
			->where("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->penerimaan_penjualan_kode;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_NEWID()
	{
		$query = $this->db->query("SELECT NEWID() AS generate_kode");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->generate_kode;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Check_QtySKUPallet($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
							SUM(ISNULL(pallet_detail_temp.sku_stock_qty,0)) AS sku_stock_qty
						FROM pallet_temp
						LEFT JOIN pallet_detail_temp
						ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
						WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->sku_stock_qty;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Insert_Pallet($data)
	{
		$this->db->set("pallet_id", $data['pallet_id']);
		$this->db->set("pallet_jenis_id", $data['pallet_jenis_id']);
		$this->db->set("depo_id", $data['depo_id']);
		$this->db->set("pallet_kode", $data['pallet_kode']);
		$this->db->set("pallet_tanggal_create", $data['pallet_tanggal_create']);
		$this->db->set("pallet_who_create", $data['pallet_who_create']);
		$this->db->set("pallet_is_aktif", $data['pallet_is_aktif']);

		$this->db->insert("pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Insert_PalletDetail($data)
	{
		$this->db->set("pallet_detail_id", $data['pallet_detail_id']);
		$this->db->set("pallet_id", $data['pallet_id']);
		$this->db->set("sku_id", $data['sku_id']);
		$this->db->set("sku_stock_id", $data['sku_stock_id']);
		$this->db->set("sku_stock_expired_date", $data['sku_stock_expired_date']);
		$this->db->set("sku_stock_qty", 0);
		$this->db->set("penerimaan_tipe_id", $data['penerimaan_tipe_id']);
		$this->db->set("sku_stock_terima", $data['sku_stock_qty']);

		$this->db->insert("pallet_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Update_PalletDetail($pallet_id, $sku_id, $sku_stock_id, $data)
	{
		$qty_terima = $this->db->query("SELECT ISNULL(sku_stock_terima,0) AS sku_stock_terima FROM pallet_detail WHERE pallet_id = '$pallet_id' AND sku_id = '$sku_id' AND sku_stock_id = '$sku_stock_id'")->row(0)->sku_stock_terima;
		$qty_terima = $qty_terima + $data['sku_stock_qty'];

		$this->db->set("sku_stock_terima", $qty_terima);

		$this->db->where("pallet_id", $pallet_id);
		$this->db->where("sku_id", $sku_id);
		$this->db->where("sku_stock_id", $sku_stock_id);

		$this->db->update("pallet_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
		// return $this->db->last_query();
	}

	public function Update_KodePallet($delivery_order_batch_id)
	{
		$query_pallet = $this->db->query("SELECT pallet_id,pallet_jenis_id FROM pallet_temp WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");

		foreach ($query_pallet->result() as $row) {
			$date_now = date('Y-m-d h:i:s');
			$param =  $row->pallet_jenis_id;

			$vrbl = $this->M_SuratTugasPengiriman->Get_KodePallet($param);
			$prefix = $vrbl;

			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->M_SuratTugasPengiriman->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;
			$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			$this->db->set("pallet_kode", $generate_kode);

			$this->db->where("pallet_id", $row->pallet_id);

			$this->db->update("pallet_temp");
		}

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Delete_Pallet($delivery_order_batch_id)
	{
		$query = $this->db->query("DELETE FROM pallet_temp WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}

	public function Delete_PalletDetail($delivery_order_batch_id)
	{
		$query = $this->db->query("DELETE FROM pallet_detail_temp WHERE pallet_id IN (SELECT pallet_id FROM pallet_temp WHERE delivery_order_batch_id = '$delivery_order_batch_id') ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}

	public function Update_StockSKU($delivery_order_batch_id, $depo_detail_id, $penerimaan_tipe_id)
	{
		$sql_check = $this->db->query("SELECT
										pallet_detail_temp.pallet_detail_id,
										pallet_detail_temp.sku_id,
										sku.sku_induk_id,
										pallet_detail_temp.sku_stock_expired_date,
										pallet_detail_temp.sku_stock_qty
										FROM pallet_temp
										LEFT JOIN pallet_detail_temp
										ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
										LEFT JOIN sku
										ON sku.sku_id = pallet_detail_temp.sku_id
										WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id' AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id' ");

		if ($sql_check->num_rows() == 0) {
			$queryupdate = 0;
		} else {

			foreach ($sql_check->result() as $row) {
				$query_stock_masuk = $this->db->query("SELECT * FROM sku_stock 
													WHERE sku_id = '$row->sku_id' 
													AND sku_stock_expired_date = '$row->sku_stock_expired_date'
													AND depo_detail_id = '$depo_detail_id' ");

				if ($query_stock_masuk->num_rows() == 0) {
					$query_sku_stock_id = $this->db->query("SELECT NEWID() AS generate_kode");

					$sku_stock_id = $query_sku_stock_id->row(0)->generate_kode;

					$this->db->set("sku_stock_id", $sku_stock_id);
					$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
					// $this->db->set("client_wms_id", $client_wms_id);
					$this->db->set("depo_id", $this->session->userdata('depo_id'));
					$this->db->set("depo_detail_id", $depo_detail_id);
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

					$this->db->set("sku_stock_id", $sku_stock_id);
					$this->db->where("pallet_detail_id", $row->pallet_detail_id);

					$this->db->update("pallet_detail_temp");
				} else {
					$sku_stock_masuk = $query_stock_masuk->row(0)->sku_stock_masuk + $row->sku_stock_qty;

					$this->db->set("sku_stock_masuk", $sku_stock_masuk);
					$this->db->where("sku_stock_id", $query_stock_masuk->row(0)->sku_stock_id);

					$this->db->update("sku_stock");
				}
			}

			$queryupdate = 1;
		}

		// return $this->db->last_query();
		return $queryupdate;
	}

	public function Check_PenerimaanPenjualanByDo($delivery_order_batch_id, $delivery_order_id)
	{
		$arr_sku = array();

		$query = $this->db->query("SELECT
									CASE
										WHEN  penerimaan_penjualan_detail.delivery_order_id IS NULL THEN '0'
										ELSE '1'
									END check_sku
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_detail
									ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
									LEFT JOIN penerimaan_penjualan_detail
									ON penerimaan_penjualan_detail.delivery_order_id = delivery_order_detail.delivery_order_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id' AND delivery_order.delivery_order_id = '$delivery_order_id'
									GROUP BY CASE
									WHEN  penerimaan_penjualan_detail.delivery_order_id IS NULL THEN '0'
									ELSE '1'
									END");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->check_sku;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Check_StatusPenerimaanPenjualan($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
										delivery_order.delivery_order_status
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id' AND delivery_order.delivery_order_status IN ('partially delivered','not delivered')
									GROUP BY delivery_order.delivery_order_status");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return count($query);
	}

	public function Get_KodePallet($pallet_jenis_id)
	{
		$query = $this->db->query("SELECT pallet_jenis_kode FROM pallet_jenis WHERE pallet_jenis_id = '$pallet_jenis_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->pallet_jenis_kode;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Check_SKUBTB($delivery_order_batch_id)
	{

		$query = $this->db->query("SELECT
										pallet_detail_temp.sku_id,
										sku.sku_id,
										CASE WHEN sku.sku_id IS NULL THEN 0 ELSE 1 END check_sku
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
									LEFT JOIN sku
									ON sku.sku_id = pallet_detail_temp.sku_id
									WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_PenerimaanTipe($delivery_order_batch_id, $penerimaan_tipe_id)
	{
		$query = $this->db->query("SELECT
									pallet_temp.delivery_order_batch_id,
									pallet_detail_temp.penerimaan_tipe_id,
									penerimaan_tipe.penerimaan_tipe_nama
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
									LEFT JOIN penerimaan_tipe
									ON penerimaan_tipe.penerimaan_tipe_id = pallet_detail_temp.penerimaan_tipe_id
									WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id' AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
									GROUP BY pallet_temp.delivery_order_batch_id,
											pallet_detail_temp.penerimaan_tipe_id,
											penerimaan_tipe.penerimaan_tipe_nama");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Update_DOSkuQty($delivery_order_id, $sku_id, $sku_qty)
	{
		//update delivery_order
		$this->db->set("sku_qty", $sku_qty);
		$this->db->where("delivery_order_id", $delivery_order_id);
		$this->db->where("sku_id", $sku_id);

		$this->db->update("delivery_order_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Get_Perusahaan()
	{
		$this->db->select("*")
			->from("client_wms")
			->where("client_wms_is_aktif", 1)
			->where("client_wms_is_deleted", 0);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_PerusahaanById($client_wms_id)
	{
		$this->db->select("*")
			->from("client_wms")
			->where("client_wms_id", $client_wms_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetFDJRKode($delivery_order_batch_id)
	{
		$this->db->select("delivery_order_batch_kode")
			->from("delivery_order_batch")
			->where("delivery_order_batch_id", $delivery_order_batch_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->delivery_order_batch_kode;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Check_SKUDelivery($delivery_order_batch_id, $sku_id)
	{
		$this->db->select("sku_id")
			->from("delivery_order_detail_temp")
			->where("delivery_order_batch_id", $delivery_order_batch_id)
			->where("sku_id", $sku_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->sku_id;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Update_ReqExpDate($delivery_order_detail_id, $sku_id, $sku_request_expdate)
	{
		// $this->db->set("pallet_kode", $pallet_kode);
		$this->db->set("sku_request_expdate", $sku_request_expdate);

		$this->db->where("delivery_order_detail_id", $delivery_order_detail_id);
		$this->db->where("sku_id", $sku_id);

		$this->db->update("delivery_order_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Update_SKUKeterangan($delivery_order_detail_id, $sku_id, $sku_keterangan)
	{
		// $this->db->set("pallet_kode", $pallet_kode);
		$this->db->set("sku_keterangan", $sku_keterangan);

		$this->db->where("delivery_order_detail_id", $delivery_order_detail_id);
		$this->db->where("sku_id", $sku_id);

		$this->db->update("delivery_order_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Update_SKUQty($delivery_order_detail_id, $sku_id, $sku_qty)
	{
		// $this->db->set("pallet_kode", $pallet_kode);
		$this->db->set("sku_qty", $sku_qty);

		$this->db->where("delivery_order_detail_id", $delivery_order_detail_id);
		$this->db->where("sku_id", $sku_id);

		$this->db->update("delivery_order_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Delete_SKUDelivery($delivery_order_detail_id)
	{
		$query = $this->db->query("DELETE FROM delivery_order_detail_temp WHERE delivery_order_detail_id = '$delivery_order_detail_id' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;

		// return $this->db->last_query();
	}

	public function Insert_DeliveryOrderDetailTemp($delivery_order_batch_id, $sku_id, $depo_id, $depo_detail_id, $sku_kode, $sku_nama_produk, $sku_harga_satuan, $sku_disc_percent, $sku_disc_rp, $sku_harga_nett, $sku_request_expdate, $sku_filter_expdate, $sku_filter_expdatebulan, $sku_filter_expdatetahun, $sku_weight, $sku_weight_unit, $sku_length, $sku_length_unit, $sku_width, $sku_width_unit, $sku_height, $sku_height_unit, $sku_volume, $sku_volume_unit, $sku_qty, $sku_keterangan, $sku_qty_kirim, $reason_id)
	{
		$this->db->set("delivery_order_detail_id", "NewID()", FALSE);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("depo_id", $depo_id);
		// $this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("sku_kode", $sku_kode);
		$this->db->set("sku_nama_produk", $sku_nama_produk);
		$this->db->set("sku_harga_satuan", $sku_harga_satuan);
		$this->db->set("sku_disc_percent", 0);
		$this->db->set("sku_disc_rp", 0);
		$this->db->set("sku_harga_nett", $sku_harga_nett);
		$this->db->set("sku_request_expdate", 0);
		$this->db->set("sku_filter_expdate", 0);
		$this->db->set("sku_filter_expdatebulan", 0);
		$this->db->set("sku_filter_expdatetahun", 0);
		if ($sku_weight != "null") {
			$this->db->set("sku_weight", $sku_weight);
		}
		if ($sku_weight_unit != "null") {
			$this->db->set("sku_weight_unit", $sku_weight_unit);
		}
		if ($sku_length != "null") {
			$this->db->set("sku_length", $sku_length);
		}
		if ($sku_length_unit != "null") {
			$this->db->set("sku_length_unit", $sku_length_unit);
		}
		if ($sku_width != "null") {
			$this->db->set("sku_width", $sku_width);
		}
		if ($sku_width_unit != "null") {
			$this->db->set("sku_width_unit", $sku_width_unit);
		}
		if ($sku_height != "null") {
			$this->db->set("sku_height", $sku_height);
		}
		if ($sku_height_unit != "null") {
			$this->db->set("sku_height_unit", $sku_height_unit);
		}
		if ($sku_volume != "null") {
			$this->db->set("sku_volume", $sku_volume);
		}
		if ($sku_volume_unit != "null") {
			$this->db->set("sku_volume_unit", $sku_volume_unit);
		}
		$this->db->set("sku_qty", $sku_qty);
		$this->db->set("sku_keterangan", $sku_keterangan);
		// $this->db->set("sku_qty_kirim", $sku_qty_kirim);
		// $this->db->set("reason_id", $reason_id);


		$this->db->insert("delivery_order_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_DeliveryOrder($delivery_order_id, $delivery_order_batch_id, $sales_order_id, $delivery_order_kode, $delivery_order_yourref, $client_wms_id, $delivery_order_tgl_buat_do, $delivery_order_tgl_expired_do, $delivery_order_tgl_surat_jalan, $delivery_order_tgl_rencana_kirim, $delivery_order_tgl_aktual_kirim, $delivery_order_keterangan, $delivery_order_status, $delivery_order_is_prioritas, $delivery_order_is_need_packing, $delivery_order_tipe_layanan, $delivery_order_tipe_pembayaran, $delivery_order_sesi_pengiriman, $delivery_order_request_tgl_kirim, $delivery_order_request_jam_kirim, $tipe_pengiriman_id, $nama_tipe, $confirm_rate, $delivery_order_reff_id, $delivery_order_reff_no, $delivery_order_total, $unit_mandiri_id, $depo_id, $client_pt_id, $delivery_order_kirim_nama, $delivery_order_kirim_alamat, $delivery_order_kirim_telp, $delivery_order_kirim_provinsi, $delivery_order_kirim_kota, $delivery_order_kirim_kecamatan, $delivery_order_kirim_kelurahan, $delivery_order_kirim_latitude, $delivery_order_kirim_longitude, $delivery_order_kirim_kodepos, $delivery_order_kirim_area, $delivery_order_kirim_invoice_pdf, $delivery_order_kirim_invoice_dir, $principle_id, $delivery_order_ambil_nama, $delivery_order_ambil_alamat, $delivery_order_ambil_telp, $delivery_order_ambil_provinsi, $delivery_order_ambil_kota, $delivery_order_ambil_kecamatan, $delivery_order_ambil_kelurahan, $delivery_order_ambil_latitude, $delivery_order_ambil_longitude, $delivery_order_ambil_kodepos, $delivery_order_ambil_area, $delivery_order_update_who, $delivery_order_update_tgl, $delivery_order_approve_who, $delivery_order_approve_tgl, $delivery_order_reject_who, $delivery_order_reject_tgl, $delivery_order_reject_reason, $delivery_order_no_urut_rute, $delivery_order_prioritas_stock, $tipe_delivery_order_id, $delivery_order_draft_id, $delivery_order_draft_kode)
	{
		$this->db->set("delivery_order_id", $delivery_order_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		// $this->db->set("sales_order_id", $sales_order_id);
		$this->db->set("delivery_order_kode", $delivery_order_kode);
		// $this->db->set("delivery_order_yourref", $delivery_order_yourref);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("delivery_order_tgl_buat_do", $delivery_order_tgl_buat_do);
		$this->db->set("delivery_order_tgl_expired_do", $delivery_order_tgl_expired_do);
		$this->db->set("delivery_order_tgl_surat_jalan", $delivery_order_tgl_surat_jalan);
		$this->db->set("delivery_order_tgl_rencana_kirim", $delivery_order_tgl_rencana_kirim);
		$this->db->set("delivery_order_tgl_aktual_kirim", $delivery_order_tgl_aktual_kirim);
		$this->db->set("delivery_order_keterangan", $delivery_order_keterangan);
		$this->db->set("delivery_order_status", $delivery_order_status);
		$this->db->set("delivery_order_is_prioritas", $delivery_order_is_prioritas);
		$this->db->set("delivery_order_is_need_packing", $delivery_order_is_need_packing);
		$this->db->set("delivery_order_tipe_layanan", $delivery_order_tipe_layanan);
		$this->db->set("delivery_order_tipe_pembayaran", $delivery_order_tipe_pembayaran);
		// $this->db->set("delivery_order_sesi_pengiriman", $delivery_order_sesi_pengiriman);
		// $this->db->set("delivery_order_request_tgl_kirim", $delivery_order_request_tgl_kirim);
		// $this->db->set("delivery_order_request_jam_kirim", $delivery_order_request_jam_kirim);
		// $this->db->set("tipe_pengiriman_id", $tipe_pengiriman_id);
		// $this->db->set("nama_tipe", $nama_tipe);
		// $this->db->set("confirm_rate", $confirm_rate);
		// $this->db->set("delivery_order_reff_id", $delivery_order_reff_id);
		// $this->db->set("delivery_order_reff_no", $delivery_order_reff_no);
		// $this->db->set("delivery_order_total", $delivery_order_total);
		$this->db->set("unit_mandiri_id", $unit_mandiri_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("client_pt_id", $client_pt_id);
		$this->db->set("delivery_order_kirim_nama", $delivery_order_kirim_nama);
		$this->db->set("delivery_order_kirim_alamat", $delivery_order_kirim_alamat);
		$this->db->set("delivery_order_kirim_telp", $delivery_order_kirim_telp);
		$this->db->set("delivery_order_kirim_provinsi", $delivery_order_kirim_provinsi);
		$this->db->set("delivery_order_kirim_kota", $delivery_order_kirim_kota);
		$this->db->set("delivery_order_kirim_kecamatan", $delivery_order_kirim_kecamatan);
		$this->db->set("delivery_order_kirim_kelurahan", $delivery_order_kirim_kelurahan);
		$this->db->set("delivery_order_kirim_latitude", $delivery_order_kirim_latitude);
		$this->db->set("delivery_order_kirim_longitude", $delivery_order_kirim_longitude);
		$this->db->set("delivery_order_kirim_kodepos", $delivery_order_kirim_kodepos);
		$this->db->set("delivery_order_kirim_area", $delivery_order_kirim_area);
		// $this->db->set("delivery_order_kirim_invoice_pdf", $delivery_order_kirim_invoice_pdf);
		// $this->db->set("delivery_order_kirim_invoice_dir", $delivery_order_kirim_invoice_dir);
		// $this->db->set("principle_id", $principle_id);
		// $this->db->set("delivery_order_ambil_nama", $delivery_order_ambil_nama);
		// $this->db->set("delivery_order_ambil_alamat", $delivery_order_ambil_alamat);
		// $this->db->set("delivery_order_ambil_telp", $delivery_order_ambil_telp);
		// $this->db->set("delivery_order_ambil_provinsi", $delivery_order_ambil_provinsi);
		// $this->db->set("delivery_order_ambil_kota", $delivery_order_ambil_kota);
		// $this->db->set("delivery_order_ambil_kecamatan", $delivery_order_ambil_kecamatan);
		// $this->db->set("delivery_order_ambil_kelurahan", $delivery_order_ambil_kelurahan);
		// $this->db->set("delivery_order_ambil_latitude", $delivery_order_ambil_latitude);
		// $this->db->set("delivery_order_ambil_longitude", $delivery_order_ambil_longitude);
		// $this->db->set("delivery_order_ambil_kodepos", $delivery_order_ambil_kodepos);
		$this->db->set("delivery_order_ambil_area", $delivery_order_ambil_area);
		$this->db->set("delivery_order_update_who", $delivery_order_update_who);
		$this->db->set("delivery_order_update_tgl", $delivery_order_update_tgl);
		$this->db->set("delivery_order_approve_who", $delivery_order_approve_who);
		$this->db->set("delivery_order_approve_tgl", $delivery_order_approve_tgl);
		// $this->db->set("delivery_order_reject_who", $delivery_order_reject_who);
		// $this->db->set("delivery_order_reject_tgl", $delivery_order_reject_tgl);
		// $this->db->set("delivery_order_reject_reason", $delivery_order_reject_reason);
		// $this->db->set("delivery_order_no_urut_rute", $delivery_order_no_urut_rute);
		// $this->db->set("delivery_order_prioritas_stock", $delivery_order_prioritas_stock);
		$this->db->set("tipe_delivery_order_id", $tipe_delivery_order_id);
		// $this->db->set("delivery_order_draft_id", $delivery_order_draft_id);
		// $this->db->set("delivery_order_draft_kode", $delivery_order_draft_kode);

		$this->db->insert("delivery_order");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_DeliveryOrderRetur($delivery_order_id, $delivery_order_batch_id, $sales_order_id, $delivery_order_kode, $delivery_order_yourref, $client_wms_id, $delivery_order_tgl_buat_do, $delivery_order_tgl_expired_do, $delivery_order_tgl_surat_jalan, $delivery_order_tgl_rencana_kirim, $delivery_order_tgl_aktual_kirim, $delivery_order_keterangan, $delivery_order_status, $delivery_order_is_prioritas, $delivery_order_is_need_packing, $delivery_order_tipe_layanan, $delivery_order_tipe_pembayaran, $delivery_order_sesi_pengiriman, $delivery_order_request_tgl_kirim, $delivery_order_request_jam_kirim, $tipe_pengiriman_id, $nama_tipe, $confirm_rate, $delivery_order_reff_id, $delivery_order_reff_no, $delivery_order_total, $unit_mandiri_id, $depo_id, $client_pt_id, $delivery_order_kirim_nama, $delivery_order_kirim_alamat, $delivery_order_kirim_telp, $delivery_order_kirim_provinsi, $delivery_order_kirim_kota, $delivery_order_kirim_kecamatan, $delivery_order_kirim_kelurahan, $delivery_order_kirim_latitude, $delivery_order_kirim_longitude, $delivery_order_kirim_kodepos, $delivery_order_kirim_area, $delivery_order_kirim_invoice_pdf, $delivery_order_kirim_invoice_dir, $principle_id, $delivery_order_ambil_nama, $delivery_order_ambil_alamat, $delivery_order_ambil_telp, $delivery_order_ambil_provinsi, $delivery_order_ambil_kota, $delivery_order_ambil_kecamatan, $delivery_order_ambil_kelurahan, $delivery_order_ambil_latitude, $delivery_order_ambil_longitude, $delivery_order_ambil_kodepos, $delivery_order_ambil_area, $delivery_order_update_who, $delivery_order_update_tgl, $delivery_order_approve_who, $delivery_order_approve_tgl, $delivery_order_reject_who, $delivery_order_reject_tgl, $delivery_order_reject_reason, $delivery_order_no_urut_rute, $delivery_order_prioritas_stock, $tipe_delivery_order_id, $delivery_order_draft_id, $delivery_order_draft_kode)
	{
		$delivery_order_id = $delivery_order_id == '' ? null : $delivery_order_id;
		$delivery_order_batch_id = $delivery_order_batch_id == '' ? null : $delivery_order_batch_id;
		$sales_order_id = $sales_order_id == '' ? null : $sales_order_id;
		$delivery_order_kode = $delivery_order_kode == '' ? null : $delivery_order_kode;
		$delivery_order_yourref = $delivery_order_yourref == '' ? null : $delivery_order_yourref;
		$client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
		$delivery_order_tgl_buat_do = $delivery_order_tgl_buat_do == '' ? null : $delivery_order_tgl_buat_do;
		$delivery_order_tgl_expired_do = $delivery_order_tgl_expired_do == '' ? null : $delivery_order_tgl_expired_do;
		$delivery_order_tgl_surat_jalan = $delivery_order_tgl_surat_jalan == '' ? null : $delivery_order_tgl_surat_jalan;
		$delivery_order_tgl_rencana_kirim = $delivery_order_tgl_rencana_kirim == '' ? null : $delivery_order_tgl_rencana_kirim;
		$delivery_order_tgl_aktual_kirim = $delivery_order_tgl_aktual_kirim == '' ? null : $delivery_order_tgl_aktual_kirim;
		$delivery_order_keterangan = $delivery_order_keterangan == '' ? null : $delivery_order_keterangan;
		$delivery_order_status = $delivery_order_status == '' ? null : $delivery_order_status;
		$delivery_order_is_prioritas = $delivery_order_is_prioritas == '' ? null : $delivery_order_is_prioritas;
		$delivery_order_is_need_packing = $delivery_order_is_need_packing == '' ? null : $delivery_order_is_need_packing;
		$delivery_order_tipe_layanan = $delivery_order_tipe_layanan == '' ? null : $delivery_order_tipe_layanan;
		$delivery_order_tipe_pembayaran = $delivery_order_tipe_pembayaran == '' ? null : $delivery_order_tipe_pembayaran;
		$delivery_order_sesi_pengiriman = $delivery_order_sesi_pengiriman == '' ? null : $delivery_order_sesi_pengiriman;
		$delivery_order_request_tgl_kirim = $delivery_order_request_tgl_kirim == '' ? null : $delivery_order_request_tgl_kirim;
		$delivery_order_request_jam_kirim = $delivery_order_request_jam_kirim == '' ? null : $delivery_order_request_jam_kirim;
		$tipe_pengiriman_id = $tipe_pengiriman_id == '' ? null : $tipe_pengiriman_id;
		$nama_tipe = $nama_tipe == '' ? null : $nama_tipe;
		$confirm_rate = $confirm_rate == '' ? null : $confirm_rate;
		$delivery_order_reff_id = $delivery_order_reff_id == '' ? null : $delivery_order_reff_id;
		$delivery_order_reff_no = $delivery_order_reff_no == '' ? null : $delivery_order_reff_no;
		$delivery_order_total = $delivery_order_total == '' ? null : $delivery_order_total;
		$unit_mandiri_id = $unit_mandiri_id == '' ? null : $unit_mandiri_id;
		$depo_id = $depo_id == '' ? null : $depo_id;
		$client_pt_id = $client_pt_id == '' ? null : $client_pt_id;
		$delivery_order_kirim_nama = $delivery_order_kirim_nama == '' ? null : $delivery_order_kirim_nama;
		$delivery_order_kirim_alamat = $delivery_order_kirim_alamat == '' ? null : $delivery_order_kirim_alamat;
		$delivery_order_kirim_telp = $delivery_order_kirim_telp == '' ? null : $delivery_order_kirim_telp;
		$delivery_order_kirim_provinsi = $delivery_order_kirim_provinsi == '' ? null : $delivery_order_kirim_provinsi;
		$delivery_order_kirim_kota = $delivery_order_kirim_kota == '' ? null : $delivery_order_kirim_kota;
		$delivery_order_kirim_kecamatan = $delivery_order_kirim_kecamatan == '' ? null : $delivery_order_kirim_kecamatan;
		$delivery_order_kirim_kelurahan = $delivery_order_kirim_kelurahan == '' ? null : $delivery_order_kirim_kelurahan;
		$delivery_order_kirim_latitude = $delivery_order_kirim_latitude == '' ? null : $delivery_order_kirim_latitude;
		$delivery_order_kirim_longitude = $delivery_order_kirim_longitude == '' ? null : $delivery_order_kirim_longitude;
		$delivery_order_kirim_kodepos = $delivery_order_kirim_kodepos == '' ? null : $delivery_order_kirim_kodepos;
		$delivery_order_kirim_area = $delivery_order_kirim_area == '' ? null : $delivery_order_kirim_area;
		$delivery_order_kirim_invoice_pdf = $delivery_order_kirim_invoice_pdf == '' ? null : $delivery_order_kirim_invoice_pdf;
		$delivery_order_kirim_invoice_dir = $delivery_order_kirim_invoice_dir == '' ? null : $delivery_order_kirim_invoice_dir;
		$principle_id = $principle_id == '' ? null : $principle_id;
		$delivery_order_ambil_nama = $delivery_order_ambil_nama == '' ? null : $delivery_order_ambil_nama;
		$delivery_order_ambil_alamat = $delivery_order_ambil_alamat == '' ? null : $delivery_order_ambil_alamat;
		$delivery_order_ambil_telp = $delivery_order_ambil_telp == '' ? null : $delivery_order_ambil_telp;
		$delivery_order_ambil_provinsi = $delivery_order_ambil_provinsi == '' ? null : $delivery_order_ambil_provinsi;
		$delivery_order_ambil_kota = $delivery_order_ambil_kota == '' ? null : $delivery_order_ambil_kota;
		$delivery_order_ambil_kecamatan = $delivery_order_ambil_kecamatan == '' ? null : $delivery_order_ambil_kecamatan;
		$delivery_order_ambil_kelurahan = $delivery_order_ambil_kelurahan == '' ? null : $delivery_order_ambil_kelurahan;
		$delivery_order_ambil_latitude = $delivery_order_ambil_latitude == '' ? null : $delivery_order_ambil_latitude;
		$delivery_order_ambil_longitude = $delivery_order_ambil_longitude == '' ? null : $delivery_order_ambil_longitude;
		$delivery_order_ambil_kodepos = $delivery_order_ambil_kodepos == '' ? null : $delivery_order_ambil_kodepos;
		$delivery_order_ambil_area = $delivery_order_ambil_area == '' ? null : $delivery_order_ambil_area;
		$delivery_order_update_who = $delivery_order_update_who == '' ? null : $delivery_order_update_who;
		$delivery_order_update_tgl = $delivery_order_update_tgl == '' ? null : $delivery_order_update_tgl;
		$delivery_order_approve_who = $delivery_order_approve_who == '' ? null : $delivery_order_approve_who;
		$delivery_order_approve_tgl = $delivery_order_approve_tgl == '' ? null : $delivery_order_approve_tgl;
		$delivery_order_reject_who = $delivery_order_reject_who == '' ? null : $delivery_order_reject_who;
		$delivery_order_reject_tgl = $delivery_order_reject_tgl == '' ? null : $delivery_order_reject_tgl;
		$delivery_order_reject_reason = $delivery_order_reject_reason == '' ? null : $delivery_order_reject_reason;
		$delivery_order_no_urut_rute = $delivery_order_no_urut_rute == '' ? null : $delivery_order_no_urut_rute;
		$delivery_order_prioritas_stock = $delivery_order_prioritas_stock == '' ? null : $delivery_order_prioritas_stock;
		$tipe_delivery_order_id = $tipe_delivery_order_id == '' ? null : $tipe_delivery_order_id;
		$delivery_order_draft_id = $delivery_order_draft_id == '' ? null : $delivery_order_draft_id;
		$delivery_order_draft_kode = $delivery_order_draft_kode == '' ? null : $delivery_order_draft_kode;
		// $is_ada_titipan = $is_ada_titipan == '' ? null : $is_ada_titipan;

		$this->db->set("delivery_order_id", $delivery_order_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("sales_order_id", $sales_order_id);
		$this->db->set("delivery_order_kode", $delivery_order_kode);
		$this->db->set("delivery_order_yourref", $delivery_order_yourref);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("delivery_order_tgl_buat_do", $delivery_order_tgl_buat_do);
		$this->db->set("delivery_order_tgl_expired_do", $delivery_order_tgl_expired_do);
		$this->db->set("delivery_order_tgl_surat_jalan", $delivery_order_tgl_surat_jalan);
		$this->db->set("delivery_order_tgl_rencana_kirim", $delivery_order_tgl_rencana_kirim);
		$this->db->set("delivery_order_tgl_aktual_kirim", $delivery_order_tgl_aktual_kirim);
		$this->db->set("delivery_order_keterangan", $delivery_order_keterangan);
		$this->db->set("delivery_order_status", $delivery_order_status);
		$this->db->set("delivery_order_is_prioritas", $delivery_order_is_prioritas);
		$this->db->set("delivery_order_is_need_packing", $delivery_order_is_need_packing);
		$this->db->set("delivery_order_tipe_layanan", $delivery_order_tipe_layanan);
		$this->db->set("delivery_order_tipe_pembayaran", $delivery_order_tipe_pembayaran);
		$this->db->set("delivery_order_sesi_pengiriman", $delivery_order_sesi_pengiriman);
		$this->db->set("delivery_order_request_tgl_kirim", $delivery_order_request_tgl_kirim);
		$this->db->set("delivery_order_request_jam_kirim", $delivery_order_request_jam_kirim);
		$this->db->set("tipe_pengiriman_id", $tipe_pengiriman_id);
		$this->db->set("nama_tipe", $nama_tipe);
		$this->db->set("confirm_rate", $confirm_rate);
		$this->db->set("delivery_order_reff_id", $delivery_order_reff_id);
		$this->db->set("delivery_order_reff_no", $delivery_order_reff_no);
		$this->db->set("delivery_order_total", $delivery_order_total);
		$this->db->set("unit_mandiri_id", $unit_mandiri_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("client_pt_id", $client_pt_id);
		$this->db->set("delivery_order_kirim_nama", $delivery_order_kirim_nama);
		$this->db->set("delivery_order_kirim_alamat", $delivery_order_kirim_alamat);
		$this->db->set("delivery_order_kirim_telp", $delivery_order_kirim_telp);
		$this->db->set("delivery_order_kirim_provinsi", $delivery_order_kirim_provinsi);
		$this->db->set("delivery_order_kirim_kota", $delivery_order_kirim_kota);
		$this->db->set("delivery_order_kirim_kecamatan", $delivery_order_kirim_kecamatan);
		$this->db->set("delivery_order_kirim_kelurahan", $delivery_order_kirim_kelurahan);
		$this->db->set("delivery_order_kirim_latitude", $delivery_order_kirim_latitude);
		$this->db->set("delivery_order_kirim_longitude", $delivery_order_kirim_longitude);
		$this->db->set("delivery_order_kirim_kodepos", $delivery_order_kirim_kodepos);
		$this->db->set("delivery_order_kirim_area", $delivery_order_kirim_area);
		$this->db->set("delivery_order_kirim_invoice_pdf", $delivery_order_kirim_invoice_pdf);
		$this->db->set("delivery_order_kirim_invoice_dir", $delivery_order_kirim_invoice_dir);
		$this->db->set("principle_id", $principle_id);
		$this->db->set("delivery_order_ambil_nama", $delivery_order_ambil_nama);
		$this->db->set("delivery_order_ambil_alamat", $delivery_order_ambil_alamat);
		$this->db->set("delivery_order_ambil_telp", $delivery_order_ambil_telp);
		$this->db->set("delivery_order_ambil_provinsi", $delivery_order_ambil_provinsi);
		$this->db->set("delivery_order_ambil_kota", $delivery_order_ambil_kota);
		$this->db->set("delivery_order_ambil_kecamatan", $delivery_order_ambil_kecamatan);
		$this->db->set("delivery_order_ambil_kelurahan", $delivery_order_ambil_kelurahan);
		$this->db->set("delivery_order_ambil_latitude", $delivery_order_ambil_latitude);
		$this->db->set("delivery_order_ambil_longitude", $delivery_order_ambil_longitude);
		$this->db->set("delivery_order_ambil_kodepos", $delivery_order_ambil_kodepos);
		$this->db->set("delivery_order_ambil_area", $delivery_order_ambil_area);
		$this->db->set("delivery_order_update_who", $delivery_order_update_who);
		$this->db->set("delivery_order_update_tgl", $delivery_order_update_tgl);
		$this->db->set("delivery_order_approve_who", $delivery_order_approve_who);
		$this->db->set("delivery_order_approve_tgl", $delivery_order_approve_tgl);
		$this->db->set("delivery_order_reject_who", $delivery_order_reject_who);
		$this->db->set("delivery_order_reject_tgl", $delivery_order_reject_tgl);
		$this->db->set("delivery_order_reject_reason", $delivery_order_reject_reason);
		$this->db->set("delivery_order_no_urut_rute", $delivery_order_no_urut_rute);
		$this->db->set("delivery_order_prioritas_stock", $delivery_order_prioritas_stock);
		$this->db->set("tipe_delivery_order_id", $tipe_delivery_order_id);
		$this->db->set("delivery_order_draft_id", $delivery_order_draft_id);
		$this->db->set("delivery_order_draft_kode", $delivery_order_draft_kode);
		// $this->db->set("is_ada_titipan", $is_ada_titipan);

		$this->db->insert("delivery_order");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_DeliveryOrderDetail($dod_id, $delivery_order_id, $data)
	{
		$delivery_order_detail_id = $data['delivery_order_detail_id'] == '' ? null : $data['delivery_order_detail_id'];
		$delivery_order_id = $data['delivery_order_id'] == '' ? null : $data['delivery_order_id'];
		$delivery_order_batch_id = $data['delivery_order_batch_id'] == '' ? null : $data['delivery_order_batch_id'];
		$sku_id = $data['sku_id'] == '' ? null : $data['sku_id'];
		$depo_id = $data['depo_id'] == '' ? null : $data['depo_id'];
		$depo_detail_id = $data['depo_detail_id'] == '' ? null : $data['depo_detail_id'];
		$sku_kode = $data['sku_kode'] == '' ? null : $data['sku_kode'];
		$sku_nama_produk = $data['sku_nama_produk'] == '' ? null : $data['sku_nama_produk'];
		$sku_harga_satuan = $data['sku_harga_satuan'] == '' ? null : $data['sku_harga_satuan'];
		$sku_disc_percent = $data['sku_disc_percent'] == '' ? null : $data['sku_disc_percent'];
		$sku_disc_rp = $data['sku_disc_rp'] == '' ? null : $data['sku_disc_rp'];
		$sku_harga_nett = $data['sku_harga_nett'] == '' ? null : $data['sku_harga_nett'];
		$sku_request_expdate = $data['sku_request_expdate'] == '' ? null : $data['sku_request_expdate'];
		$sku_filter_expdate = $data['sku_filter_expdate'] == '' ? null : $data['sku_filter_expdate'];
		$sku_filter_expdatebulan = $data['sku_filter_expdatebulan'] == '' ? null : $data['sku_filter_expdatebulan'];
		$sku_filter_expdatetahun = $data['sku_filter_expdatetahun'] == '' ? null : $data['sku_filter_expdatetahun'];
		$sku_weight = $data['sku_weight'] == '' ? null : $data['sku_weight'];
		$sku_weight_unit = $data['sku_weight_unit'] == '' ? null : $data['sku_weight_unit'];
		$sku_length = $data['sku_length'] == '' ? null : $data['sku_length'];
		$sku_length_unit = $data['sku_length_unit'] == '' ? null : $data['sku_length_unit'];
		$sku_width = $data['sku_width'] == '' ? null : $data['sku_width'];
		$sku_width_unit = $data['sku_width_unit'] == '' ? null : $data['sku_width_unit'];
		$sku_height = $data['sku_height'] == '' ? null : $data['sku_height'];
		$sku_height_unit = $data['sku_height_unit'] == '' ? null : $data['sku_height_unit'];
		$sku_volume = $data['sku_volume'] == '' ? null : $data['sku_volume'];
		$sku_volume_unit = $data['sku_volume_unit'] == '' ? null : $data['sku_volume_unit'];
		$sku_qty = $data['sku_qty'] == '' ? null : $data['sku_qty'];
		$sku_keterangan = $data['sku_keterangan'] == '' ? null : $data['sku_keterangan'];
		$sku_qty_kirim = $data['sku_qty_kirim'] == '' ? null : $data['sku_qty_kirim'];
		$reason_id = $data['reason_id'] == '' ? null : $data['reason_id'];
		$tipe_stock_nama = $data['tipe_stock_nama'] == '' ? null : $data['tipe_stock_nama'];

		$this->db->set("delivery_order_detail_id", $dod_id);
		$this->db->set("delivery_order_id", $delivery_order_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("sku_kode", $sku_kode);
		$this->db->set("sku_nama_produk", $sku_nama_produk);
		$this->db->set("sku_harga_satuan", $sku_harga_satuan);
		$this->db->set("sku_disc_percent", $sku_disc_percent);
		$this->db->set("sku_disc_rp", $sku_disc_rp);
		$this->db->set("sku_harga_nett", $sku_harga_nett);
		$this->db->set("sku_request_expdate", $sku_request_expdate);
		$this->db->set("sku_filter_expdate", $sku_filter_expdate);
		$this->db->set("sku_filter_expdatebulan", $sku_filter_expdatebulan);
		$this->db->set("sku_filter_expdatetahun", $sku_filter_expdatetahun);
		$this->db->set("sku_weight", $sku_weight);
		$this->db->set("sku_weight_unit", $sku_weight_unit);
		$this->db->set("sku_length", $sku_length);
		$this->db->set("sku_length_unit", $sku_length_unit);
		$this->db->set("sku_width", $sku_width);
		$this->db->set("sku_width_unit", $sku_width_unit);
		$this->db->set("sku_height", $sku_height);
		$this->db->set("sku_height_unit", $sku_height_unit);
		$this->db->set("sku_volume", $sku_volume);
		$this->db->set("sku_volume_unit", $sku_volume_unit);
		$this->db->set("sku_qty", $sku_qty);
		$this->db->set("sku_keterangan", $sku_keterangan);
		$this->db->set("sku_qty_kirim", $sku_qty_kirim);
		$this->db->set("reason_id", $reason_id);
		$this->db->set("tipe_stock_nama", $tipe_stock_nama);

		$queryinsert = $this->db->insert("delivery_order_detail");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_DeliveryOrderDetailRetur($delivery_order_id, $delivery_order_batch_id)
	{
		$query = $this->db->query("INSERT INTO delivery_order_detail 
									SELECT 
										NEWID() AS delivery_order_detail_id,
										'" . $delivery_order_id . "' AS delivery_order_id,
										'" . $delivery_order_batch_id . "' AS delivery_order_batch_id,
										sku_id,
										depo_id,
										depo_detail_id,
										sku_kode,
										sku_nama_produk,
										sku_harga_satuan,
										sku_disc_percent,
										sku_disc_rp,
										sku_harga_nett,
										sku_request_expdate,
										sku_filter_expdate,
										sku_filter_expdatebulan,
										sku_filter_expdatetahun,
										sku_weight,
										sku_weight_unit,
										sku_length,
										sku_length_unit,
										sku_width,
										sku_width_unit,
										sku_height,
										sku_height_unit,
										sku_volume,
										sku_volume_unit,
										sku_qty,
										sku_keterangan,
										sku_qty_kirim,
										reason_id
									FROM delivery_order_detail_temp
									WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;

		// return $this->db->last_query();
	}

	public function Delete_DeliveryOrderReturDetailTemp($delivery_order_batch_id)
	{
		$query = $this->db->query("DELETE FROM delivery_order_detail_temp WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;

		// return $this->db->last_query();
	}

	public function check_kode_pallet($kode_pallet)
	{
		$this->db->select("pallet_id")
			->from("pallet")
			->where("pallet_kode", $kode_pallet);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->pallet_id;
		}

		return $query;
	}

	public function get_pallet_by_arr_id($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
										pallet_temp.pallet_id,
										pallet_temp.pallet_kode,
										pallet_temp.pallet_jenis_id,
										pallet_jenis.pallet_jenis_nama
									FROM pallet_temp
									LEFT JOIN pallet_jenis
									ON pallet_temp.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY pallet_temp.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetPerusahaan()
	{
		$query = $this->db->query("SELECT * FROM client_wms 
									WHERE client_wms_id IN (SELECT client_wms_id FROM karyawan_principle WHERE karyawan_id = '" . $this->session->userdata('karyawan_id') . "' GROUP BY client_wms_id)
									ORDER BY client_wms_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPrincipleByPerusahaan($perusahaan)
	{
		$query = $this->db->query("SELECT
										principle.*
									FROM client_wms_principle
									LEFT JOIN principle
									ON client_wms_principle.principle_id = principle.principle_id
									WHERE client_wms_principle.client_wms_id = '$perusahaan'
									ORDER BY principle.principle_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetCheckerByPrinciple($perusahaan, $principle)
	{
		// WHERE karyawan.depo_id = '" . $this->session->userdata('depo_id') . "' AND karyawan.client_wms_id = '$perusahaan' AND karyawan_principle.principle_id = '$principle' AND karyawan.karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'

		$query = $this->db->query("SELECT
										karyawan.*
									FROM karyawan
									LEFT JOIN karyawan_principle
									ON karyawan.karyawan_id = karyawan_principle.karyawan_id
									WHERE karyawan.depo_id = '" . $this->session->userdata('depo_id') . "' AND karyawan_principle.principle_id = '$principle' AND karyawan.karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
									ORDER BY karyawan.karyawan_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Delete_PalletTemp2($pallet_id)
	{
		$this->db->trans_begin();

		$this->db->where("pallet_id", $pallet_id);

		$this->db->delete("pallet_temp");

		$error = $this->db->error();

		if ($error['message'] != '' || $error['message'] != null)
		//if( $error['message'] != '' && $error['message'] != null )
		{
			$res = $error['message']; // Error

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

	public function get_pallet_temp($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT 
										pallet_id,
										pallet_jenis_id,
										depo_id,
										rak_lajur_detail_id,
										pallet_kode,
										pallet_tanggal_create,
										pallet_who_create,
										pallet_is_aktif
									FROM pallet_temp 
									WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function get_pallet_detail_temp($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT 
										pallet_detail_temp.pallet_detail_id,
										pallet_detail_temp.pallet_id,
										pallet_detail_temp.sku_id,
										pallet_detail_temp.sku_stock_id,
										pallet_detail_temp.sku_stock_expired_date,
										pallet_detail_temp.sku_stock_qty,
										pallet_detail_temp.penerimaan_tipe_id
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_detail_temp.pallet_id = pallet_temp.pallet_id
									WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function CheckPalletById($pallet_id)
	{
		$this->db->select("pallet_id")
			->from("pallet")
			->where("pallet_id", $pallet_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->pallet_id;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function CheckPalletDetailById($pallet_id, $sku_id, $sku_stock_id)
	{
		$this->db->select("pallet_detail_id")
			->from("pallet_detail")
			->where("pallet_id", $pallet_id)
			->where("sku_id", $sku_id)
			->where("sku_stock_id", $sku_stock_id);

		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->pallet_detail_id;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function check_sisa_btb_by_fdjr($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
									delivery_order_batch.delivery_order_batch_id,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') AS delivery_order_tgl_aktual_kirim,
									delivery_order.delivery_order_kirim_nama,
									principle.principle_kode AS principle,
									principle_brand.principle_brand_nama AS brand,
									delivery_order_detail.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									delivery_order_detail.sku_qty,
									ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) AS sku_qty_kirim,
									ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) AS sisa_jumlah_terima,
									delivery_order_batch.karyawan_id,
									karyawan.karyawan_nama,
									tipe_delivery_order.tipe_delivery_order_alias
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									LEFT JOIN sku
									ON delivery_order_detail.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
									ORDER BY delivery_order.delivery_order_kode, sku.sku_kode ASC");

		// if ($query->num_rows() == 0) {
		// 	$query = 0;
		// } else {
		// 	$query = $query->num_rows();
		// }

		// return $this->db->last_query();
		return $query->num_rows();
	}

	public function GetArea()
	{
		$query = $this->db->query("SELECT
										area.*
									FROM depo_area_header
									LEFT JOIN area_header
									ON depo_area_header.area_header_id = area_header.area_header_id
									LEFT JOIN area
									ON area_header.area_header_id = area.area_header_id
									WHERE depo_area_header.depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY area.area_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetTipeLayanan()
	{
		$this->db->select("*")
			->from("tipe_layanan")
			->where("tipe_layanan_is_aktif", "1")
			->order_by("tipe_layanan_kode");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPerusahaanByFDJR($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT DISTINCT
										client_wms.*
									FROM delivery_order
									LEFT JOIN client_wms
									ON client_wms.client_wms_id = delivery_order.client_wms_id
									WHERE delivery_order.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY client_wms.client_wms_nama");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPerusahaanByDO($delivery_order_id)
	{
		$query = $this->db->query("SELECT DISTINCT
										client_wms.*
									FROM delivery_order
									LEFT JOIN client_wms
									ON client_wms.client_wms_id = delivery_order.client_wms_id
									WHERE delivery_order.delivery_order_id = '$delivery_order_id'
									ORDER BY client_wms.client_wms_nama");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetCustomerByDO($delivery_order_id)
	{
		$query = $this->db->query("SELECT DISTINCT
										client_pt.*,
										isnull(area.area_nama,'') AS area_nama
									FROM delivery_order
									LEFT JOIN client_pt
									ON client_pt.client_pt_id = delivery_order.client_pt_id
									LEFT JOIN area
									ON client_pt.area_id = area.area_id
									WHERE delivery_order.delivery_order_id = '$delivery_order_id'
									ORDER BY client_pt.client_pt_nama");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}


	public function GetPerusahaanById($id)
	{
		$this->db->select("*")
			->from("client_wms")
			->where("client_wms_id", $id)
			->order_by("client_wms_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetSelectedCustomer($customer, $perusahaan)
	{
		$query = $this->db->query("SELECT
									client_pt.*,
									isnull(area.area_nama,'') AS area_nama
									FROM client_wms_client_pt
									LEFT JOIN client_pt
									ON client_wms_client_pt.client_pt_id = client_pt.client_pt_id
									LEFT JOIN area
									ON client_pt.area_id = area.area_id
									WHERE client_pt.client_pt_id = '$customer'
									AND client_wms_client_pt.client_wms_id = '$perusahaan'
									ORDER BY client_pt.client_pt_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetSelectedPrinciple($customer, $perusahaan)
	{
		$query = $this->db->query("SELECT
									principle.*,
									isnull(area.area_nama,'') AS area_nama
									FROM client_wms_principle
									LEFT JOIN principle
									ON principle.principle_id = client_wms_principle.principle_id
									LEFT JOIN area
									ON principle.area_id = area.area_id
									WHERE principle.principle_id = '$customer'
									AND client_wms_principle.client_wms_id = '$perusahaan'
									ORDER BY principle.principle_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetSelectedSKU($sku_id)
	{
		$sku_id = implode(",", $sku_id);

		$query = $this->db->query("SELECT
									sku_id,
									sku_kode,
									sku_nama_produk,
									ISNULL(sku_harga_jual, '0') AS sku_harga_jual,
									sku_satuan,
									sku_kemasan,
									ISNULL(sku_konversi_faktor,0) AS sku_konversi_faktor,
									ISNULL(sku_weight, '0') AS sku_weight,
									ISNULL(sku_weight_unit, '') AS sku_weight_unit,
									ISNULL(sku_length, '0') AS sku_length,
									ISNULL(sku_length_unit, '') AS sku_length_unit,
									ISNULL(sku_width, '0') AS sku_width,
									ISNULL(sku_width_unit, '') AS sku_width_unit,
									ISNULL(sku_height, '0') AS sku_height,
									ISNULL(sku_height_unit, '') AS sku_height_unit,
									ISNULL(sku_volume, '0') AS sku_volume,
									ISNULL(sku_volume_unit, '') AS sku_volume_unit
									FROM sku
									where sku_id in (" . $sku_id . ")
									ORDER BY sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetFactoryByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan, $nama, $alamat, $telp, $area)
	{

		if ($nama == "") {
			$nama = "";
		} else {
			$nama = "AND principle.principle_nama LIKE '%" . $nama . "%' ";
		}

		if ($alamat == "") {
			$alamat = "";
		} else {
			$alamat = "AND principle.principle_alamat LIKE '%" . $alamat . "%' ";
		}

		if ($telp == "") {
			$telp = "";
		} else {
			$telp = "AND principle.principle_telepon LIKE '%" . $telp . "%' ";
		}

		if ($area == "") {
			$area = "";
		} else {
			$area = "AND principle.area_id = '" . $area . "' ";
		}

		$query = $this->db->query("SELECT
									principle.*,
									isnull(area.area_nama,'') AS area_nama
									FROM client_wms_principle
									LEFT JOIN principle
									ON principle.principle_id = client_wms_principle.principle_id
									LEFT JOIN area
									ON principle.area_id = area.area_id
									WHERE client_wms_principle.client_wms_id = '$perusahaan'
									" . $nama . "
									" . $alamat . "
									" . $telp . "
									" . $area . "
									ORDER BY principle.principle_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetCustomerByTypePelayanan($perusahaan, $tipe_pembayaran, $tipe_layanan, $nama, $alamat, $telp, $area)
	{

		if ($nama == "") {
			$nama = "";
		} else {
			$nama = "AND client_pt.client_pt_nama LIKE '%" . $nama . "%' ";
		}

		if ($alamat == "") {
			$alamat = "";
		} else {
			$alamat = "AND client_pt.client_pt_alamat LIKE '%" . $alamat . "%' ";
		}

		if ($telp == "") {
			$telp = "";
		} else {
			$telp = "AND client_pt.client_pt_telepon LIKE '%" . $telp . "%' ";
		}

		if ($area == "") {
			$area = "";
		} else {
			$area = "AND client_pt.area_id = '" . $area . "' ";
		}

		$query = $this->db->query("SELECT distinct
									client_pt.*,
									ISNULL(area.area_nama, '') AS area_nama
									FROM client_wms_client_pt
									LEFT JOIN client_pt
									ON client_wms_client_pt.client_pt_id = client_pt.client_pt_id
									LEFT JOIN area
									ON client_pt.area_id = area.area_id
									LEFT JOIN area_header
									ON area_header.area_header_id = area.area_header_id
									LEFT JOIN depo_area_header
									ON depo_area_header.area_header_id = area_header.area_header_id
									WHERE client_pt.client_pt_id IS NOT NULL
									--AND client_wms_client_pt.client_wms_id = '$perusahaan'
									" . $nama . "
									" . $alamat . "
									" . $telp . "
									" . $area . "
									ORDER BY client_pt.client_pt_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function search_filter_chosen_sku($client_pt, $perusahaan, $tipe_pembayaran, $brand, $principle, $sku_induk, $sku_nama_produk, $sku_kemasan, $sku_satuan)
	{
		if ($tipe_pembayaran == "0") {
			$tipe_pembayaran = "";
		} else {
			$tipe_pembayaran = "AND client_principle.client_pt_principle_is_kredit = '1' ";
		}

		if ($brand == "") {
			$brand = "";
		} else {
			$brand = "AND principle_brand.principle_brand_nama LIKE '%" . $brand . "%' ";
		}

		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND principle.principle_kode LIKE '%" . $principle . "%' ";
		}

		if ($sku_induk == "") {
			$sku_induk = "";
		} else {
			$sku_induk = "AND sku_induk.sku_induk_nama LIKE '%" . $sku_induk . "%' ";
		}

		if ($sku_nama_produk == "") {
			$sku_nama_produk = "";
		} else {
			$sku_nama_produk = "AND sku.sku_nama_produk LIKE '%" . $sku_nama_produk . "%' ";
		}

		if ($sku_kemasan == "") {
			$sku_kemasan = "";
		} else {
			$sku_kemasan = "AND sku.sku_kemasan LIKE '%" . $sku_kemasan . "%' ";
		}

		if ($sku_satuan == "") {
			$sku_satuan = "";
		} else {
			$sku_satuan = "AND sku.sku_satuan LIKE '%" . $sku_satuan . "%' ";
		}

		// $query = $this->db->query("SELECT
		// 							sku.sku_id,
		// 							sku.sku_kode,
		// 							sku.sku_nama_produk,
		// 							sku.principle_id,
		// 							principle.principle_kode AS principle,
		// 							sku.principle_brand_id,
		// 							principle_brand.principle_brand_nama AS brand,
		// 							sku.sku_kemasan,
		// 							sku.sku_satuan,
		// 							sku.sku_induk_id,
		// 							sku_induk.sku_induk_nama AS sku_induk
		// 							FROM (SELECT
		// 							client_wms_client_pt.client_wms_client_pt_id,
		// 							client_wms_client_pt.client_wms_id,
		// 							client_wms_client_pt.client_pt_id,
		// 							client_wms_principle.principle_id,
		// 							client_pt_principle.client_pt_principle_is_kredit
		// 							FROM client_wms_client_pt
		// 							LEFT JOIN client_wms_principle
		// 							ON client_wms_client_pt.client_wms_id = client_wms_principle.client_wms_id
		// 							LEFT JOIN client_pt_principle
		// 							ON client_wms_client_pt.client_pt_id = client_pt_principle.client_pt_id
		// 							AND client_wms_principle.principle_id = client_pt_principle.principle_id
		// 							WHERE client_wms_client_pt.client_wms_id = '$perusahaan'
		// 							AND client_wms_client_pt.client_pt_id = '$client_pt') client_principle
		// 							INNER JOIN sku
		// 							ON client_principle.principle_id = sku.principle_id
		// 							LEFT JOIN sku_induk
		// 							ON sku.sku_induk_id = sku_induk.sku_induk_id
		// 							LEFT JOIN sku_stock
		// 							ON sku.sku_id = sku_stock.sku_id
		// 							LEFT JOIN principle
		// 							ON principle.principle_id = sku.principle_id
		// 							LEFT JOIN principle_brand
		// 							ON principle_brand.principle_brand_id = sku.principle_brand_id
		// 							WHERE client_principle.principle_id IS NOT NULL
		// 							AND sku_stock.sku_id IS NOT NULL
		// 							" . $tipe_pembayaran . "
		// 							" . $brand . "
		// 							" . $principle . "
		// 							" . $sku_induk . "
		// 							" . $sku_nama_produk . "
		// 							" . $sku_kemasan . "
		// 							" . $sku_satuan . "
		// 							GROUP BY sku.sku_id,
		// 									sku.sku_kode,
		// 									sku.sku_nama_produk,
		// 									sku.principle_id,
		// 									principle.principle_kode,
		// 									sku.principle_brand_id,
		// 									principle_brand.principle_brand_nama,
		// 									sku.sku_kemasan,
		// 									sku.sku_satuan,
		// 									sku.sku_induk_id,
		// 									sku_induk.sku_induk_nama
		// 							ORDER BY principle.principle_kode, sku.sku_kode ASC");

		$query = $this->db->query("SELECT
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.principle_id,
									principle.principle_kode AS principle,
									sku.principle_brand_id,
									principle_brand.principle_brand_nama AS brand,
									sku.sku_kemasan,
									sku.sku_satuan,
									sku.sku_induk_id,
									sku_induk.sku_induk_nama AS sku_induk
									FROM sku
									LEFT JOIN sku_induk
									ON sku.sku_induk_id = sku_induk.sku_induk_id
									LEFT JOIN sku_stock
									ON sku.sku_id = sku_stock.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									WHERE sku_stock.sku_id IS NOT NULL
									" . $tipe_pembayaran . "
									" . $brand . "
									" . $principle . "
									" . $sku_induk . "
									" . $sku_nama_produk . "
									" . $sku_kemasan . "
									" . $sku_satuan . "
									GROUP BY sku.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.principle_id,
											principle.principle_kode,
											sku.principle_brand_id,
											principle_brand.principle_brand_nama,
											sku.sku_kemasan,
											sku.sku_satuan,
											sku.sku_induk_id,
											sku_induk.sku_induk_nama
									ORDER BY principle.principle_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function search_filter_chosen_sku_by_pabrik($client_pt, $perusahaan, $tipe_pembayaran, $brand, $principle, $sku_induk, $sku_nama_produk, $sku_kemasan, $sku_satuan)
	{
		if ($brand == "") {
			$brand = "";
		} else {
			$brand = "AND principle_brand.principle_brand_nama LIKE '%" . $brand . "%' ";
		}

		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND principle.principle_kode LIKE '%" . $principle . "%' ";
		}

		if ($sku_induk == "") {
			$sku_induk = "";
		} else {
			$sku_induk = "AND sku_induk.sku_induk_nama LIKE '%" . $sku_induk . "%' ";
		}

		if ($sku_nama_produk == "") {
			$sku_nama_produk = "";
		} else {
			$sku_nama_produk = "AND sku.sku_nama_produk LIKE '%" . $sku_nama_produk . "%' ";
		}

		if ($sku_kemasan == "") {
			$sku_kemasan = "";
		} else {
			$sku_kemasan = "AND sku.sku_kemasan LIKE '%" . $sku_kemasan . "%' ";
		}

		if ($sku_satuan == "") {
			$sku_satuan = "";
		} else {
			$sku_satuan = "AND sku.sku_satuan LIKE '%" . $sku_satuan . "%' ";
		}

		$query = $this->db->query("SELECT
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.principle_id,
									principle.principle_kode AS principle,
									sku.principle_brand_id,
									principle_brand.principle_brand_nama AS brand,
									sku.sku_kemasan,
									sku.sku_satuan,
									sku.sku_induk_id,
									sku_induk.sku_induk_nama AS sku_induk
									FROM sku
									LEFT JOIN client_wms_principle
									ON client_wms_principle.principle_id = sku.principle_id
									LEFT JOIN sku_induk
									ON sku.sku_induk_id = sku_induk.sku_induk_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									WHERE client_wms_principle.principle_id = '$client_pt'
									" . $brand . "
									" . $principle . "
									" . $sku_induk . "
									" . $sku_nama_produk . "
									" . $sku_kemasan . "
									" . $sku_satuan . "
									GROUP BY sku.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.principle_id,
											principle.principle_kode,
											sku.principle_brand_id,
											principle_brand.principle_brand_nama,
											sku.sku_kemasan,
											sku.sku_satuan,
											sku.sku_induk_id,
											sku_induk.sku_induk_nama
									ORDER BY principle.principle_kode,sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function get_proses_do_detail_retur($delivery_order_id)
	{

		$query = $this->db->query("SELECT
									do.delivery_order_detail_id,
									do.delivery_order_id,
									do.sku_id,
									do.depo_id,
									do.depo_detail_id,
									do.sku_kode,
									do.sku_nama_produk,
									do.sku_harga_satuan,
									sku.sku_satuan,
									sku.sku_kemasan,
									ISNULL(do.sku_disc_percent, '0') AS sku_disc_percent,
									ISNULL(do.sku_disc_rp, '0') AS sku_disc_rp,
									ISNULL(do.sku_harga_nett, '0') AS sku_harga_nett,
									ISNULL(do.sku_request_expdate, '0') AS sku_request_expdate,
									ISNULL(do.sku_filter_expdate, '0') AS sku_filter_expdate,
									ISNULL(do.sku_filter_expdatebulan, '0') AS sku_filter_expdatebulan,
									ISNULL(do.sku_filter_expdatetahun, '0') AS sku_filter_expdatetahun,
									ISNULL(do.sku_weight, '0') AS sku_weight,
									ISNULL(do.sku_weight_unit, '') AS sku_weight_unit,
									ISNULL(do.sku_length, '0') AS sku_length,
									ISNULL(do.sku_length_unit, '') AS sku_length_unit,
									ISNULL(do.sku_width, '0') AS sku_width,
									ISNULL(do.sku_width_unit, '') AS sku_width_unit,
									ISNULL(do.sku_height, '0') AS sku_height,
									ISNULL(do.sku_height_unit, '') AS sku_height_unit,
									ISNULL(do.sku_volume, '0') AS sku_volume,
									ISNULL(do.sku_volume_unit, '') AS sku_volume_unit,
									ISNULL(do.sku_qty, '0') AS sku_qty,
									ISNULL(do.sku_qty_kirim, 0) AS sku_qty_kirim,
									ISNULL(do.sku_qty, 0) - ISNULL(do.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) AS sisa_jumlah_terima,
									ISNULL(do.sku_keterangan, '') AS sku_keterangan,
									ISNULL(do.tipe_stock_nama, '') AS tipe_stock
									FROM delivery_order_detail do
									LEFT JOIN sku
									ON sku.sku_id = do.sku_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id) penerimaan
									ON penerimaan.delivery_order_id = do.delivery_order_id
									AND penerimaan.sku_id = do.sku_id
									WHERE do.delivery_order_id = '$delivery_order_id'
									ORDER BY sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetLokasi()
	{
		$query = $this->db->query("select tipe_stock_nama as nama from dbo.gettipestock() where tipe_stock_is_aktif = '1' order by tipe_stock_urut ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function get_delivery_order_kode($delivery_order_id)
	{
		$query = $this->db->query("select delivery_order_kode from delivery_order where delivery_order_id = '$delivery_order_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->delivery_order_kode;
		}

		return $query;
	}

	public function get_delivery_order_retur_id($delivery_order_id)
	{
		$query = $this->db->query("select delivery_order_id from delivery_order where delivery_order_reff_id = '$delivery_order_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->delivery_order_id;
		}

		return $query;
	}

	public function insert_delivery_order($delivery_order_id, $delivery_order_batch_id, $sales_order_id, $delivery_order_kode, $delivery_order_yourref, $client_wms_id, $delivery_order_tgl_buat_do, $delivery_order_tgl_expired_do, $delivery_order_tgl_surat_jalan, $delivery_order_tgl_rencana_kirim, $delivery_order_tgl_aktual_kirim, $delivery_order_keterangan, $delivery_order_status, $delivery_order_is_prioritas, $delivery_order_is_need_packing, $delivery_order_tipe_layanan, $delivery_order_tipe_pembayaran, $delivery_order_sesi_pengiriman, $delivery_order_request_tgl_kirim, $delivery_order_request_jam_kirim, $tipe_pengiriman_id, $nama_tipe, $confirm_rate, $delivery_order_reff_id, $delivery_order_reff_no, $delivery_order_total, $unit_mandiri_id, $depo_id, $client_pt_id, $delivery_order_kirim_nama, $delivery_order_kirim_alamat, $delivery_order_kirim_telp, $delivery_order_kirim_provinsi, $delivery_order_kirim_kota, $delivery_order_kirim_kecamatan, $delivery_order_kirim_kelurahan, $delivery_order_kirim_latitude, $delivery_order_kirim_longitude, $delivery_order_kirim_kodepos, $delivery_order_kirim_area, $delivery_order_kirim_invoice_pdf, $delivery_order_kirim_invoice_dir, $principle_id, $delivery_order_ambil_nama, $delivery_order_ambil_alamat, $delivery_order_ambil_telp, $delivery_order_ambil_provinsi, $delivery_order_ambil_kota, $delivery_order_ambil_kecamatan, $delivery_order_ambil_kelurahan, $delivery_order_ambil_latitude, $delivery_order_ambil_longitude, $delivery_order_ambil_kodepos, $delivery_order_ambil_area, $delivery_order_update_who, $delivery_order_update_tgl, $delivery_order_approve_who, $delivery_order_approve_tgl, $delivery_order_reject_who, $delivery_order_reject_tgl, $delivery_order_reject_reason, $delivery_order_no_urut_rute, $delivery_order_prioritas_stock, $tipe_delivery_order_id, $delivery_order_draft_id, $delivery_order_draft_kode)
	{
		// $delivery_order_id = $delivery_order_draft_id == '' ? null : $delivery_order_draft_id;
		$delivery_order_batch_id = $delivery_order_batch_id == '' ? null : $delivery_order_batch_id;
		$sales_order_id = $sales_order_id == '' ? null : $sales_order_id;
		// $delivery_order_kode = $delivery_order_draft_kode == '' ? null : $delivery_order_draft_kode;
		// $delivery_order_yourref = $delivery_order_draft_yourref == '' ? null : $delivery_order_draft_yourref;
		$client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
		$delivery_order_tgl_buat_do = $delivery_order_tgl_buat_do == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_tgl_buat_do)));
		$delivery_order_tgl_expired_do = $delivery_order_tgl_expired_do == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_tgl_expired_do)));
		$delivery_order_tgl_surat_jalan = $delivery_order_tgl_surat_jalan == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_tgl_surat_jalan)));
		$delivery_order_tgl_rencana_kirim = $delivery_order_tgl_rencana_kirim == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_tgl_rencana_kirim)));
		$delivery_order_tgl_aktual_kirim = $delivery_order_tgl_aktual_kirim == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_tgl_aktual_kirim)));
		$delivery_order_keterangan = $delivery_order_keterangan == '' ? null : $delivery_order_keterangan;
		// $delivery_order_status = $delivery_order_status == '' ? null : $delivery_order_status;
		$delivery_order_is_prioritas = $delivery_order_is_prioritas == '' ? null : $delivery_order_is_prioritas;
		$delivery_order_is_need_packing = $delivery_order_is_need_packing == '' ? null : $delivery_order_is_need_packing;
		$delivery_order_tipe_layanan = $delivery_order_tipe_layanan == '' ? null : $delivery_order_tipe_layanan;
		$delivery_order_tipe_pembayaran = $delivery_order_tipe_pembayaran == '' ? null : $delivery_order_tipe_pembayaran;
		$delivery_order_sesi_pengiriman = $delivery_order_sesi_pengiriman == '' ? null : $delivery_order_sesi_pengiriman;
		$delivery_order_request_tgl_kirim = $delivery_order_request_tgl_kirim == '' ? null : $delivery_order_request_tgl_kirim;
		$delivery_order_request_jam_kirim = $delivery_order_request_jam_kirim == '' ? null : $delivery_order_request_jam_kirim;
		$tipe_pengiriman_id = $tipe_pengiriman_id == '' ? null : $tipe_pengiriman_id;
		$nama_tipe = $nama_tipe == '' ? null : $nama_tipe;
		$confirm_rate = $confirm_rate == '' ? null : $confirm_rate;
		$delivery_order_reff_id = $delivery_order_reff_id == '' ? null : $delivery_order_reff_id;
		$delivery_order_reff_no = $delivery_order_reff_no == '' ? null : $delivery_order_reff_no;
		$delivery_order_total = $delivery_order_total == '' ? null : $delivery_order_total;
		$unit_mandiri_id = $unit_mandiri_id == '' ? null : $unit_mandiri_id;
		$depo_id = $depo_id == '' ? null : $depo_id;
		$client_pt_id = $client_pt_id == '' ? null : $client_pt_id;
		$delivery_order_kirim_nama = $delivery_order_kirim_nama == '' ? null : $delivery_order_kirim_nama;
		$delivery_order_kirim_alamat = $delivery_order_kirim_alamat == '' ? null : $delivery_order_kirim_alamat;
		$delivery_order_kirim_telp = $delivery_order_kirim_telp == '' ? null : $delivery_order_kirim_telp;
		$delivery_order_kirim_provinsi = $delivery_order_kirim_provinsi == '' ? null : $delivery_order_kirim_provinsi;
		$delivery_order_kirim_kota = $delivery_order_kirim_kota == '' ? null : $delivery_order_kirim_kota;
		$delivery_order_kirim_kecamatan = $delivery_order_kirim_kecamatan == '' ? null : $delivery_order_kirim_kecamatan;
		$delivery_order_kirim_kelurahan = $delivery_order_kirim_kelurahan == '' ? null : $delivery_order_kirim_kelurahan;
		// $delivery_order_kirim_latitude = $delivery_order_kirim_latitude == '' ? null : $delivery_order_kirim_latitude;
		// $delivery_order_kirim_longitude = $delivery_order_kirim_longitude == '' ? null : $delivery_order_kirim_longitude;
		$delivery_order_kirim_kodepos = $delivery_order_kirim_kodepos == '' ? null : $delivery_order_kirim_kodepos;
		$delivery_order_kirim_area = $delivery_order_kirim_area == '' ? null : $delivery_order_kirim_area;
		$delivery_order_kirim_invoice_pdf = $delivery_order_kirim_invoice_pdf == '' ? null : $delivery_order_kirim_invoice_pdf;
		$delivery_order_kirim_invoice_dir = $delivery_order_kirim_invoice_dir == '' ? null : $delivery_order_kirim_invoice_dir;
		$principle_id = $principle_id == '' ? null : $principle_id;
		$delivery_order_ambil_nama = $delivery_order_ambil_nama == '' ? null : $delivery_order_ambil_nama;
		$delivery_order_ambil_alamat = $delivery_order_ambil_alamat == '' ? null : $delivery_order_ambil_alamat;
		$delivery_order_ambil_telp = $delivery_order_ambil_telp == '' ? null : $delivery_order_ambil_telp;
		$delivery_order_ambil_provinsi = $delivery_order_ambil_provinsi == '' ? null : $delivery_order_ambil_provinsi;
		$delivery_order_ambil_kota = $delivery_order_ambil_kota == '' ? null : $delivery_order_ambil_kota;
		$delivery_order_ambil_kecamatan = $delivery_order_ambil_kecamatan == '' ? null : $delivery_order_ambil_kecamatan;
		$delivery_order_ambil_kelurahan = $delivery_order_ambil_kelurahan == '' ? null : $delivery_order_ambil_kelurahan;
		// $delivery_order_ambil_latitude = $delivery_order_ambil_latitude == '' ? null : $delivery_order_ambil_latitude;
		// $delivery_order_ambil_longitude = $delivery_order_ambil_longitude == '' ? null : $delivery_order_ambil_longitude;
		$delivery_order_ambil_kodepos = $delivery_order_ambil_kodepos == '' ? null : $delivery_order_ambil_kodepos;
		$delivery_order_ambil_area = $delivery_order_ambil_area == '' ? null : $delivery_order_ambil_area;
		// $delivery_order_update_who = $delivery_order_update_who == '' ? null : $delivery_order_update_who;
		// $delivery_order_update_tgl = $delivery_order_update_tgl == '' ? null : $delivery_order_update_tgl;
		// $delivery_order_approve_who = $delivery_order_approve_who == '' ? null : $delivery_order_approve_who;
		// $delivery_order_approve_tgl = $delivery_order_approve_tgl == '' ? null : $delivery_order_approve_tgl;
		// $delivery_order_reject_who = $delivery_order_reject_who == '' ? null : $delivery_order_reject_who;
		// $delivery_order_reject_tgl = $delivery_order_reject_tgl == '' ? null : $delivery_order_reject_tgl;
		// $delivery_order_reject_reason = $delivery_order_reject_reason == '' ? null : $delivery_order_reject_reason;
		// $delivery_order_no_urut_rute = $delivery_order_no_urut_rute == '' ? null : $delivery_order_no_urut_rute;
		// $delivery_order_prioritas_stock = $delivery_order_prioritas_stock == '' ? null : $delivery_order_prioritas_stock;
		$tipe_delivery_order_id = $tipe_delivery_order_id == '' ? null : $tipe_delivery_order_id;
		$delivery_order_draft_id = $delivery_order_draft_id == '' ? null : $delivery_order_draft_id;
		$delivery_order_draft_kode = $delivery_order_draft_kode == '' ? null : $delivery_order_draft_kode;

		$this->db->set("delivery_order_id", $delivery_order_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("sales_order_id", $sales_order_id);
		$this->db->set("delivery_order_kode", $delivery_order_kode);
		$this->db->set("delivery_order_yourref", NULL);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("delivery_order_tgl_buat_do", $delivery_order_tgl_buat_do);
		$this->db->set("delivery_order_tgl_expired_do", $delivery_order_tgl_expired_do);
		$this->db->set("delivery_order_tgl_surat_jalan", $delivery_order_tgl_surat_jalan);
		$this->db->set("delivery_order_tgl_rencana_kirim", $delivery_order_tgl_rencana_kirim);
		$this->db->set("delivery_order_tgl_aktual_kirim", $delivery_order_tgl_aktual_kirim);
		$this->db->set("delivery_order_keterangan", $delivery_order_keterangan);
		$this->db->set("delivery_order_status", $delivery_order_status);
		$this->db->set("delivery_order_is_prioritas", $delivery_order_is_prioritas);
		$this->db->set("delivery_order_is_need_packing", $delivery_order_is_need_packing);
		$this->db->set("delivery_order_tipe_layanan", $delivery_order_tipe_layanan);
		$this->db->set("delivery_order_tipe_pembayaran", $delivery_order_tipe_pembayaran);
		$this->db->set("delivery_order_sesi_pengiriman", $delivery_order_sesi_pengiriman);
		$this->db->set("delivery_order_request_tgl_kirim", $delivery_order_request_tgl_kirim);
		$this->db->set("delivery_order_request_jam_kirim", $delivery_order_request_jam_kirim);
		$this->db->set("tipe_pengiriman_id", $tipe_pengiriman_id);
		$this->db->set("nama_tipe", $nama_tipe);
		$this->db->set("confirm_rate", $confirm_rate);
		$this->db->set("delivery_order_reff_id", $delivery_order_reff_id);
		$this->db->set("delivery_order_reff_no", $delivery_order_reff_no);
		$this->db->set("delivery_order_total", $delivery_order_total);
		// $this->db->set("unit_mandiri_id", $unit_mandiri_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("client_pt_id", $client_pt_id);
		$this->db->set("delivery_order_kirim_nama", $delivery_order_kirim_nama);
		$this->db->set("delivery_order_kirim_alamat", $delivery_order_kirim_alamat);
		$this->db->set("delivery_order_kirim_telp", $delivery_order_kirim_telp);
		$this->db->set("delivery_order_kirim_provinsi", $delivery_order_kirim_provinsi);
		$this->db->set("delivery_order_kirim_kota", $delivery_order_kirim_kota);
		$this->db->set("delivery_order_kirim_kecamatan", $delivery_order_kirim_kecamatan);
		$this->db->set("delivery_order_kirim_kelurahan", $delivery_order_kirim_kelurahan);
		// $this->db->set("delivery_order_kirim_latitude", $delivery_order_kirim_latitude);
		// $this->db->set("delivery_order_kirim_longitude", $delivery_order_kirim_longitude);
		$this->db->set("delivery_order_kirim_latitude", NULL);
		$this->db->set("delivery_order_kirim_longitude", NULL);
		$this->db->set("delivery_order_kirim_kodepos", $delivery_order_kirim_kodepos);
		$this->db->set("delivery_order_kirim_area", $delivery_order_kirim_area);
		$this->db->set("delivery_order_kirim_invoice_pdf", $delivery_order_kirim_invoice_pdf);
		$this->db->set("delivery_order_kirim_invoice_dir", $delivery_order_kirim_invoice_dir);
		$this->db->set("principle_id", $principle_id);
		$this->db->set("delivery_order_ambil_nama", $delivery_order_ambil_nama);
		$this->db->set("delivery_order_ambil_alamat", $delivery_order_ambil_alamat);
		$this->db->set("delivery_order_ambil_telp", $delivery_order_ambil_telp);
		$this->db->set("delivery_order_ambil_provinsi", $delivery_order_ambil_provinsi);
		$this->db->set("delivery_order_ambil_kota", $delivery_order_ambil_kota);
		$this->db->set("delivery_order_ambil_kecamatan", $delivery_order_ambil_kecamatan);
		$this->db->set("delivery_order_ambil_kelurahan", $delivery_order_ambil_kelurahan);
		// $this->db->set("delivery_order_ambil_latitude", $delivery_order_ambil_latitude);
		// $this->db->set("delivery_order_ambil_longitude", $delivery_order_ambil_longitude);
		$this->db->set("delivery_order_ambil_latitude", NULL);
		$this->db->set("delivery_order_ambil_longitude", NULL);
		$this->db->set("delivery_order_ambil_kodepos", $delivery_order_ambil_kodepos);
		$this->db->set("delivery_order_ambil_area", $delivery_order_ambil_area);
		$this->db->set("delivery_order_update_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_update_tgl", "GETDATE()", FALSE);
		$this->db->set("delivery_order_approve_who", NULL);
		$this->db->set("delivery_order_approve_tgl", NULL);
		$this->db->set("delivery_order_reject_who", NULL);
		$this->db->set("delivery_order_reject_tgl", NULL);
		$this->db->set("delivery_order_reject_reason", NULL);
		$this->db->set("delivery_order_no_urut_rute", $delivery_order_no_urut_rute);
		$this->db->set("delivery_order_prioritas_stock", NULL);
		$this->db->set("tipe_delivery_order_id", $tipe_delivery_order_id);
		$this->db->set("delivery_order_draft_id", $delivery_order_draft_id);
		$this->db->set("delivery_order_draft_kode", $delivery_order_draft_kode);

		$queryinsert = $this->db->insert("delivery_order");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_delivery_order_detail($delivery_order_detail_id, $delivery_order_id, $delivery_order_batch_id, $data)
	{
		// $delivery_order_detail_id = $data['delivery_order_detail_id'] == '' ? null : $data['delivery_order_detail_id'];
		// $delivery_order_id = $data['delivery_order_id'] == '' ? null : $data['delivery_order_id'];
		// $delivery_order_batch_id = $data['delivery_order_batch_id'] == '' ? null : $data['delivery_order_batch_id'];
		$sku_id = $data['sku_id'] == '' ? null : $data['sku_id'];
		// $depo_id = $data['depo_id'] == '' ? null : $data['depo_id'];
		// $depo_detail_id = $data['depo_detail_id'] == '' ? null : $data['depo_detail_id'];
		$sku_kode = $data["sku_kode"] == "" || $data["sku_kode"] == "null" ? null : $data["sku_kode"];
		$sku_nama_produk = $data["sku_nama_produk"] == "" || $data["sku_nama_produk"] == "null" ? null : $data["sku_nama_produk"];
		$sku_harga_satuan = $data["sku_harga_satuan"] == "" || $data["sku_harga_satuan"] == "null" ? null : $data["sku_harga_satuan"];
		$sku_disc_percent = $data["sku_disc_percent"] == "" || $data["sku_disc_percent"] == "null" ? null : $data["sku_disc_percent"];
		$sku_disc_rp = $data["sku_disc_rp"] == "" || $data["sku_disc_rp"] == "" ? null : $data["sku_disc_rp"];
		$sku_harga_nett = $data["sku_harga_nett"] == "" || $data["sku_harga_nett"] == "null" ? null : $data["sku_harga_nett"];
		$sku_request_expdate = $data["sku_request_expdate"] == "" || $data["sku_request_expdate"] == "null" ? null : $data["sku_request_expdate"];
		$sku_filter_expdate = $data["sku_request_expdate"] == "0" ? null : $data["sku_filter_expdate"];
		$sku_filter_expdatebulan = $data["sku_request_expdate"] == "0" ? null : $data["sku_filter_expdatebulan"];
		// $sku_filter_expdatetahun = $data["sku_filter_expdatetahun"] == "" ? null : $data["sku_filter_expdatetahun"];
		$sku_weight = $data["sku_weight"] == "" || $data["sku_weight"] == "null" ? null : $data["sku_weight"];
		$sku_weight_unit = $data["sku_weight_unit"] == "" || $data["sku_weight_unit"] == "null" ? null : $data["sku_weight_unit"];
		$sku_length = $data["sku_length"] == "" || $data["sku_length"] == "null" ? null : $data["sku_length"];
		$sku_length_unit = $data["sku_length_unit"] == "" || $data["sku_length_unit"] == "null" ? null : $data["sku_length_unit"];
		$sku_width = $data["sku_width"] == "" || $data["sku_width"] == "null" ? null : $data["sku_width"];
		$sku_width_unit = $data["sku_width_unit"] == "" || $data["sku_width_unit"] == "null" ? null : $data["sku_width_unit"];
		$sku_height = $data["sku_height"] == "" || $data["sku_height"] == "null" ? null : $data["sku_height"];
		$sku_height_unit = $data["sku_height_unit"] == "" || $data["sku_height_unit"] == "null" ? null : $data["sku_height_unit"];
		$sku_volume = $data["sku_volume"] == "" || $data["sku_volume"] == "null" ? null : $data["sku_volume"];
		$sku_volume_unit = $data["sku_volume_unit"] == "" || $data["sku_volume_unit"] == "null" ? null : $data["sku_volume_unit"];
		$sku_qty = $data["sku_qty"] == "" || $data["sku_qty"] == "null" ? null : -1 * $data["sku_qty"];
		$sku_keterangan = $data["sku_keterangan"] == "" || $data["sku_keterangan"] == "null" ? null : $data["sku_keterangan"];
		$tipe_stock_nama = $data['tipe_stock_nama'] == '' ? null : $data['tipe_stock_nama'];
		$sku_qty_kirim = $data['sku_qty_kirim'] == '' ? null : $data['sku_qty_kirim'];
		// $reason_id = $data['reason_id'] == '' ? null : $data['reason_id'];

		$this->db->set("delivery_order_detail_id", $delivery_order_detail_id);
		$this->db->set("delivery_order_id", $delivery_order_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("sku_id", $sku_id);
		// $this->db->set("depo_id", $depo_id);
		// $this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("sku_kode", $sku_kode);
		$this->db->set("sku_nama_produk", $sku_nama_produk);
		$this->db->set("sku_harga_satuan", $sku_harga_satuan);
		$this->db->set("sku_disc_percent", $sku_disc_percent);
		$this->db->set("sku_disc_rp", $sku_disc_rp);
		$this->db->set("sku_harga_nett", $sku_harga_nett);
		$this->db->set("sku_request_expdate", $sku_request_expdate);
		$this->db->set("sku_filter_expdate", $sku_filter_expdate);
		$this->db->set("sku_filter_expdatebulan", $sku_filter_expdatebulan);
		// $this->db->set("sku_filter_expdatetahun", $sku_filter_expdatetahun);
		$this->db->set("sku_weight", $sku_weight);
		$this->db->set("sku_weight_unit", $sku_weight_unit);
		$this->db->set("sku_length", $sku_length);
		$this->db->set("sku_length_unit", $sku_length_unit);
		$this->db->set("sku_width", $sku_width);
		$this->db->set("sku_width_unit", $sku_width_unit);
		$this->db->set("sku_height", $sku_height);
		$this->db->set("sku_height_unit", $sku_height_unit);
		$this->db->set("sku_volume", $sku_volume);
		$this->db->set("sku_volume_unit", $sku_volume_unit);
		$this->db->set("sku_qty", $sku_qty);
		$this->db->set("sku_keterangan", $sku_keterangan);
		$this->db->set("sku_qty_kirim", $sku_qty_kirim);
		$this->db->set("reason_id", null);
		$this->db->set("tipe_stock_nama", $tipe_stock_nama);

		$queryinsert = $this->db->insert("delivery_order_detail");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_delivery_order_detail2($delivery_order_detail2_id, $delivery_order_detail_id, $delivery_order_id, $sku_id, $sku_stock_id, $ed, $qty, $sku_konversi_faktor)
	{
		// $delivery_order_detail_id = $data['delivery_order_detail_id'] == '' ? null : $data['delivery_order_detail_id'];
		// $delivery_order_id = $data['delivery_order_id'] == '' ? null : $data['delivery_order_id'];
		// $delivery_order_batch_id = $data['delivery_order_batch_id'] == '' ? null : $data['delivery_order_batch_id'];
		$sku_id = $sku_id == '' ? null : $sku_id;
		$sku_stock_id = $sku_stock_id == '' ? null : $sku_stock_id;
		$sku_expdate = $ed == '' ? null : date('Y-m-d', strtotime($ed));
		$sku_qty = $qty == '' ? null : -1 * $qty;
		$sku_konversi_faktor = $sku_konversi_faktor == '' ? null : -1 * $sku_konversi_faktor;

		// $this->db->set("delivery_order_detail2_id", "NEWID()", FALSE);
		$this->db->set("delivery_order_detail2_id", $delivery_order_detail2_id);
		$this->db->set("delivery_order_detail_id", $delivery_order_detail_id);
		$this->db->set("delivery_order_id", $delivery_order_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_expdate", $sku_expdate);
		$this->db->set("sku_qty", $sku_qty);
		$this->db->set("sku_qty_composite", $sku_konversi_faktor);


		$queryinsert = $this->db->insert("delivery_order_detail2");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_delivery_order_progress($do_id)
	{
		$this->db->set("delivery_order_progress_id", "NEWID()", FALSE);
		$this->db->set("delivery_order_id", $do_id);
		$this->db->set("status_progress_id", "B06EC359-E6D4-4EAB-9303-8251E64FC17A");
		$this->db->set("status_progress_nama", "in progress");
		$this->db->set("delivery_order_progress_create_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_progress_create_tgl", "GETDATE()", FALSE);

		$queryinsert = $this->db->insert("delivery_order_progress");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function GetDeliveryOrderReturHeaderById($id)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_id,
									do.delivery_order_kode,
									FORMAT(do.delivery_order_tgl_buat_do, 'dd/MM/yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(do.delivery_order_tgl_expired_do, 'dd/MM/yyyy') AS delivery_order_tgl_expired_do,
									FORMAT(do.delivery_order_tgl_surat_jalan, 'dd/MM/yyyy') AS delivery_order_tgl_surat_jalan,
									FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd/MM/yyyy') AS delivery_order_tgl_rencana_kirim,
									do.client_wms_id,
									client_wms.client_wms_nama,
									client_wms.client_wms_alamat,
									do.client_pt_id,
									ISNULL(do.delivery_order_kirim_nama,'') AS delivery_order_kirim_nama,
									ISNULL(do.delivery_order_kirim_alamat,'') AS delivery_order_kirim_alamat,
									ISNULL(do.delivery_order_kirim_telp,'') AS delivery_order_kirim_telp,
									ISNULL(do.delivery_order_kirim_provinsi,'') AS delivery_order_kirim_provinsi,
									ISNULL(do.delivery_order_kirim_kota,'') AS delivery_order_kirim_kota,
									ISNULL(do.delivery_order_kirim_kecamatan,'') AS delivery_order_kirim_kecamatan,
									ISNULL(do.delivery_order_kirim_kelurahan,'') AS delivery_order_kirim_kelurahan,
									ISNULL(do.delivery_order_kirim_kodepos,'') AS delivery_order_kirim_kodepos,
									ISNULL(do.delivery_order_kirim_area,'') AS delivery_order_kirim_area,
									do.principle_id,
									ISNULL(do.delivery_order_ambil_nama,'') AS delivery_order_ambil_nama,
									ISNULL(do.delivery_order_ambil_alamat,'') AS delivery_order_ambil_alamat,
									ISNULL(do.delivery_order_ambil_telp,'') AS delivery_order_ambil_telp,
									ISNULL(do.delivery_order_ambil_provinsi,'') AS delivery_order_ambil_provinsi,
									ISNULL(do.delivery_order_ambil_kota,'') AS delivery_order_ambil_kota,
									ISNULL(do.delivery_order_ambil_kecamatan,'') AS delivery_order_ambil_kecamatan,
									ISNULL(do.delivery_order_ambil_kelurahan,'') AS delivery_order_ambil_kelurahan,
									ISNULL(do.delivery_order_ambil_kodepos,'') AS delivery_order_ambil_kodepos,
									ISNULL(do.delivery_order_ambil_area,'') AS delivery_order_ambil_area,
									do.delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.tipe_delivery_order_id,
									ISNULL(do.delivery_order_keterangan,'') AS delivery_order_keterangan,
									do.delivery_order_status
									FROM delivery_order do
									LEFT JOIN client_wms
									ON do.client_wms_id = client_wms.client_wms_id
									WHERE do.delivery_order_id = '$id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDeliveryOrderReturDetailById($id)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_detail_id,
									do.delivery_order_id,
									do.sku_id,
									do.depo_id,
									do.depo_detail_id,
									do.sku_kode,
									do.sku_nama_produk,
									do.sku_harga_satuan,
									sku.sku_satuan,
									sku.sku_kemasan,
									ISNULL(do.sku_disc_percent, '0') AS sku_disc_percent,
									ISNULL(do.sku_disc_rp, '0') AS sku_disc_rp,
									ISNULL(do.sku_harga_nett, '0') AS sku_harga_nett,
									ISNULL(do.sku_request_expdate, '0') AS sku_request_expdate,
									ISNULL(do.sku_filter_expdate, '0') AS sku_filter_expdate,
									ISNULL(do.sku_filter_expdatebulan, '0') AS sku_filter_expdatebulan,
									ISNULL(do.sku_filter_expdatetahun, '0') AS sku_filter_expdatetahun,
									ISNULL(do.sku_weight, '0') AS sku_weight,
									ISNULL(do.sku_weight_unit, '') AS sku_weight_unit,
									ISNULL(do.sku_length, '0') AS sku_length,
									ISNULL(do.sku_length_unit, '') AS sku_length_unit,
									ISNULL(do.sku_width, '0') AS sku_width,
									ISNULL(do.sku_width_unit, '') AS sku_width_unit,
									ISNULL(do.sku_height, '0') AS sku_height,
									ISNULL(do.sku_height_unit, '') AS sku_height_unit,
									ISNULL(do.sku_volume, '0') AS sku_volume,
									ISNULL(do.sku_volume_unit, '') AS sku_volume_unit,
									ISNULL(do.sku_qty, '0') AS sku_qty,
									ISNULL(do.sku_keterangan, '') AS sku_keterangan,
									ISNULL(do.tipe_stock_nama, '') AS tipe_stock
									FROM delivery_order_detail do
									LEFT JOIN sku
									  ON sku.sku_id = do.sku_id
									WHERE delivery_order_id = '$id'
									ORDER BY sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetGudang()
	{
		$query = $this->db->query("SELECT
										depo_detail_id,
										depo_detail_nama
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND depo_detail_is_gudang_penerima = '1'
									ORDER BY depo_detail_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetSKUDODetailBySettlement($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT DISTINCT
									principle.principle_kode AS principle,
									principle_brand.principle_brand_nama AS brand,
									delivery_order_detail.sku_id,
									sku.sku_induk_id,
									sku_induk.sku_induk_nama AS sku_induk,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									ISNULL(sku.sku_harga_jual, '0') AS sku_harga_jual,
									sku.sku_satuan,
									sku.sku_kemasan,
									ISNULL(sku.sku_weight, '0') AS sku_weight,
									ISNULL(sku.sku_weight_unit, '') AS sku_weight_unit,
									ISNULL(sku.sku_length, '0') AS sku_length,
									ISNULL(sku.sku_length_unit, '') AS sku_length_unit,
									ISNULL(sku.sku_width, '0') AS sku_width,
									ISNULL(sku.sku_width_unit, '') AS sku_width_unit,
									ISNULL(sku.sku_height, '0') AS sku_height,
									ISNULL(sku.sku_height_unit, '') AS sku_height_unit,
									ISNULL(sku.sku_volume, '0') AS sku_volume,
									ISNULL(sku.sku_volume_unit, '') AS sku_volume_unit
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									LEFT JOIN (SELECT
										delivery_order.delivery_order_batch_id,
										delivery_order_detail.sku_id,
										SUM(delivery_order_detail.sku_qty) AS sku_qty,
										SUM(delivery_order_detail.sku_qty_kirim) AS sku_qty_kirim
									FROM delivery_order
									LEFT JOIN delivery_order_detail
										ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									WHERE delivery_order.delivery_order_batch_id = '$delivery_order_batch_id'
									AND delivery_order.tipe_delivery_order_id IN ('F1E7E5C6-FB70-4E07-993C-3551E5B7DDC3','23080729-40FB-42B2-B58E-588E0C3CCC3F')
									GROUP BY delivery_order.delivery_order_batch_id,
										delivery_order_detail.sku_id) do_sisipan
										ON do_sisipan.delivery_order_batch_id = delivery_order_detail.delivery_order_batch_id
										AND do_sisipan.sku_id = delivery_order_detail.sku_id
									LEFT JOIN sku
									ON delivery_order_detail.sku_id = sku.sku_id
									LEFT JOIN sku_induk
									ON sku_induk.sku_induk_id = sku.sku_induk_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									AND ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
									AND ISNULL(penerimaan.sku_jumlah_terima, 0) - ISNULL(do_sisipan.sku_qty, 0) > 0
									ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDODetail3($id)
	{
		$query = $this->db->query("SELECT
		dod3.delivery_order_detail3_id, 
		dod3.delivery_order_id, 
		tb.tipe_biaya_id, 
		tb.tipe_biaya_nama,
		dod3.delivery_order_detail3_nilai, 
  		ISNULL(dod3.delivery_order_detail3_keterangan, '') AS delivery_order_detail3_keterangan
		FROM delivery_order_detail3 AS dod3 
		JOIN tipe_biaya AS tb ON tb.tipe_biaya_id = dod3.tipe_biaya_id 
		WHERE dod3.delivery_order_id = '" . $id . "'
		");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetTipeBiaya()
	{
		$query = $this->db->query("SELECT 
		tipe_biaya_id, 
		tipe_biaya_nama 
		FROM tipe_biaya 
		WHERE tipe_biaya_is_aktif = 1");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function deleteDODetail3($id)
	{
		$this->db->where('delivery_order_id', $id);
		return $this->db->delete('delivery_order_detail3');
	}

	public function insertDODetail3($delivery_order_detail3_id, $delivery_order_id, $tipe_biaya_id, $delivery_order_detail3_nilai, $delivery_order_detail3_keterangan)
	{
		$delivery_order_detail3_keterangan_ = $delivery_order_detail3_keterangan == null ? null : $delivery_order_detail3_keterangan;

		$this->db->set("delivery_order_detail3_id", $delivery_order_detail3_id);
		$this->db->set("delivery_order_id", $delivery_order_id);
		$this->db->set("tipe_biaya_id", $tipe_biaya_id);
		$this->db->set("delivery_order_detail3_nilai", $delivery_order_detail3_nilai);
		$this->db->set("delivery_order_detail3_keterangan", $delivery_order_detail3_keterangan_);
		$queryinsert = $this->db->insert("delivery_order_detail3");

		// $affectedrows = $this->db->affected_rows();
		// if ($affectedrows > 0) {
		// 	$queryinsert = 1;
		// } else {
		// 	$queryinsert = 0;
		// }

		return $queryinsert;
	}

	public function getKodeAutoComplete($value)
	{
		return $this->db->select("CONCAT(REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 2)), '/', REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 3))) as kode")
			->from("pallet")
			->where("depo_id", $this->session->userdata('depo_id'))
			->like("pallet_kode", $value)->get()->result();
	}

	public function CekDOKirimUlang($delivery_order_batch_id)
	{
		$arr_do = array();
		$cek_kirim_ulang = array();

		$query = $this->db->query("SELECT
									do.delivery_order_id,
									do.delivery_order_status,
									do.delivery_order_kode,
									do_kirim_ulang.delivery_order_draft_id
									FROM delivery_order do
									LEFT JOIN delivery_order_draft do_kirim_ulang
									ON do_kirim_ulang.delivery_order_draft_reff_id = do.delivery_order_id
									WHERE delivery_order_batch_id = '$delivery_order_batch_id'
									AND do.delivery_order_status = 'rescheduled' ");

		if ($query->num_rows() == 0) {
			$arr_do = array();
		} else {
			$query = $query->result_array();

			foreach ($query as $value) {
				if ($value['delivery_order_draft_id'] == "") {
					$cek_kirim_ulang = array("status" => "0", "kode_do" => $value['delivery_order_kode']);
					array_push($arr_do, $cek_kirim_ulang);
				}
			}
		}

		return $arr_do;
	}
}
