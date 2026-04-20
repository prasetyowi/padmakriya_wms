<?php

class M_PenerimaanRetur extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function search_fdjr_by_filter($Tgl_FDJR, $Tgl_FDJR2, $No_FDJR, $karyawan_id, $Status_FDJR)
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
										CASE WHEN delivery_order_settlement.delivery_order_id IS NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement has not Started' WHEN delivery_order_settlement.delivery_order_id IS NOT NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement Success' ELSE '' END AS settlement_status
									FROM delivery_order_batch
									LEFT JOIN delivery_order
									ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN picking_list
									ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN picking_order
									ON picking_list.picking_list_id = picking_order.picking_list_id
									LEFT JOIN serah_terima_kirim
									ON serah_terima_kirim.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN delivery_order_settlement
									ON delivery_order_settlement.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN tipe_delivery_order
									ON delivery_order_batch.tipe_delivery_order_id = tipe_delivery_order.tipe_delivery_order_id
									LEFT JOIN karyawan driver
									ON delivery_order_batch.karyawan_id = driver.karyawan_id
									WHERE FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim,'yyyy-MM-dd') BETWEEN '$Tgl_FDJR' 
                                    AND '$Tgl_FDJR2' AND delivery_order_batch.depo_id = '" . $this->session->userdata('depo_id') . "' 
                                    AND delivery_order_batch.delivery_order_batch_status IN ('In Process Closing Delivery','In Process Receiving Outlet','completed')
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

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
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

	public function GetHeaderFDJRById($id)
	{
		$query = $this->db->query("SELECT
                                    delivery_order_batch.delivery_order_batch_id,
                                    delivery_order_batch.delivery_order_batch_kode,
                                    FORMAT(delivery_order_batch.delivery_order_batch_create_tgl, 'dd/MM/yyyy') AS delivery_order_batch_create_tgl,
                                    FORMAT(delivery_order_batch.delivery_order_batch_tanggal, 'dd/MM/yyyy') AS delivery_order_batch_tanggal,
                                    FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim, 'dd/MM/yyyy') AS delivery_order_batch_tanggal_kirim,
                                    delivery_order_batch.tipe_delivery_order_id,
                                    tipe_delivery_order.tipe_delivery_order_alias,
                                    delivery_order_batch.area_id,
                                    area.area_nama,
                                    delivery_order_batch.delivery_order_batch_status,
                                    delivery_order_batch.tipe_ekspedisi_id,
                                    tipe_ekspedisi.tipe_ekspedisi_nama,
                                    delivery_order_batch.kendaraan_id,
                                    kendaraan.kendaraan_nopol,
                                    delivery_order_batch.karyawan_id,
                                    karyawan.karyawan_nama,
                                    ISNULL(delivery_order_batch.kendaraan_km_akhir, 0) AS kendaraan_km_akhir
                                    FROM delivery_order_batch
                                    LEFT JOIN karyawan
                                    ON delivery_order_batch.karyawan_id = karyawan.karyawan_id
                                    LEFT JOIN tipe_delivery_order
                                    ON delivery_order_batch.tipe_delivery_order_id = tipe_delivery_order.tipe_delivery_order_id
                                    LEFT JOIN tipe_ekspedisi
                                    ON delivery_order_batch.tipe_ekspedisi_id = tipe_ekspedisi.tipe_ekspedisi_id
                                    LEFT JOIN kendaraan
                                    ON delivery_order_batch.kendaraan_id = kendaraan.kendaraan_id
                                    LEFT JOIN area
                                    ON delivery_order_batch.area_id = area.area_id
                                    WHERE delivery_order_batch.delivery_order_batch_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDetailFDJRById2($delivery_order_batch_id, $is_btb)
	{
		$queryCondition1 = "";
		$queryCondition2 = "";

		// Membuat kondisi WHERE berdasarkan $is_btb
		if ($is_btb === "1") {
			$queryCondition1 = "AND tipe_delivery_order.tipe_delivery_order_nama NOT IN ('Retur') AND delivery_order.delivery_order_status IN ('not delivered', 'partially delivered')";
			$queryCondition2 = "AND tipe_delivery_order.tipe_delivery_order_nama IN ('Retur') AND delivery_order.delivery_order_status IN ('Delivered')";
		} else {
			$queryCondition1 = "AND tipe_delivery_order.tipe_delivery_order_nama NOT IN ('Retur')";
			$queryCondition2 = "AND tipe_delivery_order.tipe_delivery_order_nama IN ('Retur')";
		}

		$query = $this->db->query("SELECT DISTINCT
									delivery_order_batch.delivery_order_batch_id,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									ISNULL(delivery_order.delivery_order_nominal_tunai, 0) AS delivery_order_nominal_tunai,
									CASE
										WHEN ISNULL(delivery_order.delivery_order_jumlah_bayar, 0) = 0 AND
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
									reason.reason_keterangan, -- Added this line
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
									END boleh_titipan,
									delivery_order.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_alias,
									so.sales_order_kode,
									so.sales_order_no_po,
									-- btb.penerimaan_penjualan_id,
									CASE
										WHEN delivery_order.delivery_order_status NOT IN ('delivered') OR
										(delivery_order.delivery_order_kode LIKE '%/DO/%' AND
										delivery_order.is_ada_titipan = '1') THEN CASE
											WHEN btb.penerimaan_penjualan_id IS NOT NULL THEN 'SUDAH BTB'
											ELSE 'BELUM BTB'
										END
										ELSE 'TIDAK ADA BTB'
									END AS sudah_btb,
									CASE
										WHEN tipe_delivery_order.tipe_delivery_order_nama NOT IN ('Retur') AND delivery_order.delivery_order_status IN ('not delivered', 'partially delivered')
										THEN 'ADA KIRIMAN'
										ELSE 'TIDAK ADA KIRIMAN'
									END AS is_cek
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
									LEFT JOIN reason
									ON reason.reason_id = delivery_order.delivery_order_reject_reason
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									$queryCondition1
									UNION
									SELECT DISTINCT
									delivery_order_batch.delivery_order_batch_id,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									ISNULL(delivery_order.delivery_order_nominal_tunai, 0) AS delivery_order_nominal_tunai,
									CASE
										WHEN ISNULL(delivery_order.delivery_order_jumlah_bayar, 0) = 0 AND
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
									reason.reason_keterangan, -- Added this line
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
									END boleh_titipan,
									delivery_order.tipe_delivery_order_id,
									tipe_delivery_order.tipe_delivery_order_alias,
									so.sales_order_kode,
									so.sales_order_no_po,
									-- btb.penerimaan_penjualan_id,
									CASE
										WHEN delivery_order.delivery_order_status IN ('delivered') OR
										(delivery_order.delivery_order_kode LIKE '%/DO/%' AND
										delivery_order.is_ada_titipan = '1') THEN CASE
											WHEN btb.penerimaan_penjualan_id IS NOT NULL THEN 'SUDAH BTB'
											ELSE 'BELUM BTB'
										END
										ELSE 'TIDAK ADA BTB'
									END AS sudah_btb,
									CASE 
										WHEN tipe_delivery_order.tipe_delivery_order_nama = 'Retur' AND delivery_order.delivery_order_status = 'Delivered'
										THEN 'ADA RETUR'
										ELSE 'TIDAK ADA RETUR'
									END AS is_cek
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
									LEFT JOIN reason
									ON reason.reason_id = delivery_order.delivery_order_reject_reason
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
									$queryCondition2
									ORDER BY delivery_order.delivery_order_kode ASC");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function getDeliveryOrderIDNotDelivered($dob_id)
	{
		$query = $this->db->query("SELECT do.delivery_order_id as do_id, 
											do.delivery_order_kode as do_kode,
											so.sales_order_kode as no_so,
											dob.delivery_order_batch_update_tgl,
											tdo.tipe_delivery_order_nama,
											CASE
											WHEN do.delivery_order_status = 'delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim'
											WHEN do.delivery_order_status = 'partially delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim sebagian'
											WHEN do.delivery_order_status = 'not delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'tidak terkirim'
											WHEN do.delivery_order_kode LIKE '%/DOR/%' THEN 'retur'
											WHEN do.delivery_order_status = 'rescheduled' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'rescheduled'
                                    		ELSE '' END AS do_status
									FROM delivery_order AS do
									LEFT JOIN delivery_order_batch AS dob 
										ON do.delivery_order_batch_id = dob.delivery_order_batch_id
									LEFT JOIN sales_order AS so
										ON so.sales_order_id = do.sales_order_id
									LEFT JOIN tipe_delivery_order AS tdo
										ON do.tipe_delivery_order_id = tdo.tipe_delivery_order_id
									WHERE dob.delivery_order_batch_id = '$dob_id'
									AND do.delivery_order_status IN ('not delivered', 'partially delivered')
									AND tdo.tipe_delivery_order_nama <> 'Retur'");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getDeliveryOrderIDRetur($dob_id)
	{
		$query = $this->db->query("SELECT do.delivery_order_id as do_id, 
											do.delivery_order_kode as do_kode,
											so.sales_order_kode as no_so,
											dob.delivery_order_batch_update_tgl,
											tdo.tipe_delivery_order_nama,
											CASE
											WHEN do.delivery_order_status = 'delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim'
											WHEN do.delivery_order_status = 'partially delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim sebagian'
											WHEN do.delivery_order_status = 'not delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'tidak terkirim'
											WHEN do.delivery_order_kode LIKE '%/DOR/%' THEN 'retur'
											WHEN do.delivery_order_status = 'rescheduled' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'rescheduled'
                                    		ELSE '' END AS do_status
									FROM delivery_order AS do
									LEFT JOIN delivery_order_batch AS dob 
										ON do.delivery_order_batch_id = dob.delivery_order_batch_id
									LEFT JOIN sales_order AS so
										ON so.sales_order_id = do.sales_order_id
									LEFT JOIN tipe_delivery_order AS tdo
										ON do.tipe_delivery_order_id = tdo.tipe_delivery_order_id
									WHERE dob.delivery_order_batch_id = '$dob_id'
									AND do.delivery_order_status = 'delivered'
									AND tdo.tipe_delivery_order_nama = 'Retur'");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDetailFDJRById($id)
	{
		$query = $this->db->query("SELECT DISTINCT
										delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order.delivery_order_id,
										delivery_order.delivery_order_kode,
										ISNULL(delivery_order.delivery_order_nominal_tunai, 0) AS delivery_order_nominal_tunai,
										CASE
											WHEN ISNULL(delivery_order.delivery_order_jumlah_bayar, 0) = 0 AND
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
										reason.reason_keterangan, -- Added this line
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
										END boleh_titipan,
										delivery_order.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										so.sales_order_kode,
										so.sales_order_no_po,
										-- btb.penerimaan_penjualan_id,
										CASE
											WHEN delivery_order.delivery_order_status NOT IN ('delivered') OR
												(delivery_order.delivery_order_kode LIKE '%/DO/%' AND
												delivery_order.is_ada_titipan = '1') THEN CASE
											WHEN btb.penerimaan_penjualan_id IS NOT NULL THEN 'SUDAH BTB'
											ELSE 'BELUM BTB'
											END
											ELSE 'TIDAK ADA BTB'
										END AS sudah_btb,
										CASE
											WHEN tipe_delivery_order.tipe_delivery_order_nama NOT IN ('Retur') AND delivery_order.delivery_order_status IN ('not delivered', 'partially delivered')
											THEN 'ADA KIRIMAN'
											ELSE 'TIDAK ADA KIRIMAN'
										END AS is_cek
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
										LEFT JOIN reason
										ON reason.reason_id = delivery_order.delivery_order_reject_reason
										WHERE delivery_order_batch.delivery_order_batch_id = '$id'
										AND tipe_delivery_order.tipe_delivery_order_nama NOT IN ('Retur') AND delivery_order.delivery_order_status IN ('not delivered', 'partially delivered')
										UNION
										SELECT DISTINCT
										delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										delivery_order.delivery_order_id,
										delivery_order.delivery_order_kode,
										ISNULL(delivery_order.delivery_order_nominal_tunai, 0) AS delivery_order_nominal_tunai,
										CASE
											WHEN ISNULL(delivery_order.delivery_order_jumlah_bayar, 0) = 0 AND
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
										reason.reason_keterangan, -- Added this line
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
										END boleh_titipan,
										delivery_order.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										so.sales_order_kode,
										so.sales_order_no_po,
										-- btb.penerimaan_penjualan_id,
										CASE
											WHEN delivery_order.delivery_order_status IN ('delivered') OR
											(delivery_order.delivery_order_kode LIKE '%/DO/%' AND
											delivery_order.is_ada_titipan = '1') THEN CASE
												WHEN btb.penerimaan_penjualan_id IS NOT NULL THEN 'SUDAH BTB'
												ELSE 'BELUM BTB'
											END
											ELSE 'TIDAK ADA BTB'
										END AS sudah_btb,
										CASE 
											WHEN tipe_delivery_order.tipe_delivery_order_nama = 'Retur' AND delivery_order.delivery_order_status = 'Delivered'
											THEN 'ADA RETUR'
											ELSE 'TIDAK ADA RETUR'
										END AS is_cek
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
										LEFT JOIN reason
										ON reason.reason_id = delivery_order.delivery_order_reject_reason
										WHERE delivery_order_batch.delivery_order_batch_id = '$id'
										AND tipe_delivery_order.tipe_delivery_order_nama = 'Retur' AND delivery_order.delivery_order_status = 'Delivered'
										ORDER BY delivery_order.delivery_order_kode ASC");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetFDJRAreaById($id)
	{
		$query = $this->db->query("SELECT
									do_area.area_id,
									area.area_kode,
									area.area_nama
									FROM delivery_order_area do_area
									LEFT JOIN area
									ON area.area_id = do_area.area_id
									WHERE do_area.delivery_order_batch_id = '$id'
									ORDER BY area.area_nama ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetHeaderDeliveryOrderById($id)
	{
		$query = $this->db->query("SELECT TOP 1
                                    do.delivery_order_batch_id,
                                    fdjr.delivery_order_batch_kode,
									do.delivery_order_id,
									do.delivery_order_kode,
									do.sales_order_id,
									so.sales_order_kode,
									so.sales_order_no_po,
									FORMAT(do.delivery_order_tgl_buat_do, 'dd/MM/yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(do.delivery_order_tgl_expired_do, 'dd/MM/yyyy') AS delivery_order_tgl_expired_do,
									FORMAT(do.delivery_order_tgl_surat_jalan, 'dd/MM/yyyy') AS delivery_order_tgl_surat_jalan,
									FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd/MM/yyyy') AS delivery_order_tgl_rencana_kirim,
									do.delivery_order_update_tgl,
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
									tipe_delivery_order.tipe_delivery_order_nama,
									ISNULL(do.delivery_order_keterangan,'') AS delivery_order_keterangan,
									do.delivery_order_status,
                                    CASE
                                    WHEN do.delivery_order_status = 'delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim'
                                    WHEN do.delivery_order_status = 'partially delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim sebagian'
                                    WHEN do.delivery_order_status = 'not delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'tidak terkirim'
                                    WHEN do.delivery_order_kode LIKE '%/DOR/%' THEN 'retur'
									WHEN do.delivery_order_status = 'rescheduled' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'rescheduled'
                                    ELSE '' END AS do_status,
									penerimaan_penjualan.depo_detail_id,
									penerimaan_penjualan.karyawan_id,
									FORMAT(ISNULL(penerimaan_penjualan.penerimaan_penjualan_tgl,GETDATE()),'dd-MM-yyyy') AS penerimaan_penjualan_tgl
									FROM delivery_order do
									LEFT JOIN FAS.dbo.sales_order so
									ON so.sales_order_id = do.sales_order_id 
                                    LEFT JOIN delivery_order_batch fdjr
									ON do.delivery_order_batch_id = fdjr.delivery_order_batch_id
									LEFT JOIN penerimaan_penjualan
									ON penerimaan_penjualan.delivery_order_batch_id = fdjr.delivery_order_batch_id
									LEFT JOIN client_wms
									ON do.client_wms_id = client_wms.client_wms_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = do.tipe_delivery_order_id
									WHERE do.delivery_order_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDetailDeliveryOrderById($delivery_order_id)
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
									SUM(ABS(delivery_order_detail.sku_qty)) AS sku_qty,
									SUM(ISNULL(ABS(delivery_order_detail.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) AS sku_qty_kirim,
									SUM(ISNULL(ABS(delivery_order_detail.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sisa_jumlah_terima,
									delivery_order_batch.karyawan_id,
									karyawan.karyawan_nama,
									tipe_delivery_order.tipe_delivery_order_alias,
									penerimaan.kondisi_barang
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN delivery_order_batch
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima,
									kondisi_barang
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id,
											kondisi_barang) penerimaan
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
									WHERE delivery_order.delivery_order_id = '$delivery_order_id'
									AND ISNULL(ABS(delivery_order_detail.sku_qty), 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
									GROUP BY delivery_order_batch.delivery_order_batch_id,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd'),
									delivery_order.delivery_order_kirim_nama,
									principle.principle_kode,
									principle_brand.principle_brand_nama,
									delivery_order_detail.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									delivery_order_batch.karyawan_id,
									karyawan.karyawan_nama,
									tipe_delivery_order.tipe_delivery_order_alias,
									penerimaan.kondisi_barang
									ORDER BY delivery_order.delivery_order_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetDetailDOBatchById($delivery_order_id, $act)
	{
		$isBatch = "";
		if ($act == 'ViewBTBKiriman' || $act == 'ViewBTBRetur' || $act == 'ProsesBTBKiriman' || $act == 'ProsesBTBRetur') {
			$isBatch = "WHERE delivery_order.delivery_order_id IN ($delivery_order_id)";
		} else {
			$isBatch = "WHERE delivery_order.delivery_order_id = '$delivery_order_id')";
		}

		// $query = $this->db->query("SELECT
		// 							delivery_order_batch.delivery_order_batch_id,
		// 							delivery_order_batch.delivery_order_batch_kode,
		// 							delivery_order.delivery_order_id,
		// 							delivery_order.delivery_order_kode,
		// 							FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') AS sku_expdate,
		// 							FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') AS delivery_order_tgl_aktual_kirim,
		// 							delivery_order.delivery_order_kirim_nama,
		// 							principle.principle_kode AS principle,
		// 							principle.principle_id AS principle_id,
		// 							principle_brand.principle_brand_nama AS brand,
		// 							delivery_order_detail.sku_id,
		// 							sku.sku_kode,
		// 							sku.sku_nama_produk,
		// 							sku.sku_kemasan,
		// 							sku.sku_satuan,
		// 							SUM(ABS(delivery_order_detail.sku_qty)) AS sku_qty,
		// 							SUM(ISNULL(ABS(delivery_order_detail.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) AS sku_qty_kirim,
		// 							SUM(ISNULL(ABS(delivery_order_detail.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sisa_jumlah_terima,
		// 							delivery_order_batch.karyawan_id,
		// 							karyawan.karyawan_nama,
		// 							tipe_delivery_order.tipe_delivery_order_alias,
		// 							penerimaan.kondisi_barang
		// 							FROM delivery_order
		// 							LEFT JOIN delivery_order_detail
		// 							ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
		// 							LEFT JOIN delivery_order_batch
		// 							ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
		// 							LEFT JOIN (SELECT
		// 							delivery_order_id,
		// 							sku_id,
		// 							SUM(sku_jumlah_barang) AS sku_jumlah_barang,
		// 							SUM(sku_jumlah_terima) AS sku_jumlah_terima,
		// 							kondisi_barang
		// 							FROM penerimaan_penjualan_detail
		// 							GROUP BY delivery_order_id,
		// 									sku_id,
		// 									kondisi_barang) penerimaan
		// 							ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
		// 							AND penerimaan.sku_id = delivery_order_detail.sku_id
		// 							LEFT JOIN sku
		// 							ON delivery_order_detail.sku_id = sku.sku_id
		// 							LEFT JOIN principle
		// 							ON principle.principle_id = sku.principle_id
		// 							LEFT JOIN principle_brand
		// 							ON principle_brand.principle_brand_id = sku.principle_brand_id
		// 							LEFT JOIN karyawan
		// 							ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
		// 							LEFT JOIN tipe_delivery_order
		// 							ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
		// 							LEFT JOIN (SELECT DISTINCT
		//                                             delivery_order_detail_id,
		//                                             delivery_order_id,
		//                                             sku_id,
		//                                             sku_stock_id,
		//                                             sku_expdate,
		//                                             sku_qty
		//                                         FROM delivery_order_detail2) delivery_order_detail2
		//                             ON delivery_order_detail2.delivery_order_id = delivery_order.delivery_order_id
		//                             AND delivery_order_detail2.delivery_order_detail_id = delivery_order_detail.delivery_order_detail_id
		//                             AND delivery_order_detail2.sku_id = delivery_order_detail.sku_id
		// 							" . $isBatch .  "
		// 							AND ISNULL(ABS(delivery_order_detail.sku_qty), 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
		// 							GROUP BY delivery_order_batch.delivery_order_batch_id,
		// 								delivery_order_batch.delivery_order_batch_kode,
		// 								delivery_order.delivery_order_id,
		// 								delivery_order.delivery_order_kode,
		// 								FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd'),
		// 								FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd'),
		// 								delivery_order.delivery_order_kirim_nama,
		// 								principle.principle_kode,
		// 								principle.principle_id,
		// 								principle_brand.principle_brand_nama,
		// 								delivery_order_detail.sku_id,
		// 								sku.sku_kode,
		// 								sku.sku_nama_produk,
		// 								sku.sku_kemasan,
		// 								sku.sku_satuan,
		// 								delivery_order_batch.karyawan_id,
		// 								karyawan.karyawan_nama,
		// 								tipe_delivery_order.tipe_delivery_order_alias,
		// 								penerimaan.kondisi_barang
		// 							ORDER BY delivery_order.delivery_order_kode, sku.sku_kode ASC");

		$query = $this->db->query("SELECT
								delivery_order_batch.delivery_order_batch_id,
								delivery_order_batch.delivery_order_batch_kode,
								delivery_order.delivery_order_id,
								delivery_order.delivery_order_kode,
								delivery_order.client_wms_id,
								FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') AS sku_expdate,
								FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') AS delivery_order_tgl_aktual_kirim,
								delivery_order.delivery_order_kirim_nama,
								principle.principle_kode AS principle,
								principle.principle_id AS principle_id,
								principle_brand.principle_brand_nama AS brand,
								delivery_order_detail2.sku_id,
								sku.sku_kode,
								sku.sku_nama_produk,
								sku.sku_kemasan,
								sku.sku_satuan,
								SUM(ABS(delivery_order_detail2.sku_qty)) AS sku_qty,
								SUM(ISNULL(ABS(delivery_order_detail2.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail2.sku_qty_kirim, 0)) AS sku_qty_kirim,
								SUM(ISNULL(ABS(delivery_order_detail2.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail2.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sisa_jumlah_terima,
								delivery_order_batch.karyawan_id,
								karyawan.karyawan_nama,
								tipe_delivery_order.tipe_delivery_order_alias,
								penerimaan.kondisi_barang
							FROM delivery_order
							LEFT JOIN delivery_order_detail
								ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
							LEFT JOIN delivery_order_batch
								ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
							LEFT JOIN (SELECT
								delivery_order_id,
								sku_id,
								SUM(sku_jumlah_barang) AS sku_jumlah_barang,
								SUM(sku_jumlah_terima) AS sku_jumlah_terima,
								kondisi_barang
							FROM penerimaan_penjualan_detail
							GROUP BY delivery_order_id,
									sku_id,
									kondisi_barang) penerimaan
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
							LEFT JOIN (SELECT DISTINCT
								delivery_order_detail_id,
								delivery_order_id,
								sku_id,
								sku_stock_id,
								sku_expdate,
								sku_qty_kirim,
								sku_qty
							FROM delivery_order_detail2) delivery_order_detail2
								ON delivery_order_detail2.delivery_order_id = delivery_order.delivery_order_id
								AND delivery_order_detail2.delivery_order_detail_id = delivery_order_detail.delivery_order_detail_id
								AND delivery_order_detail2.sku_id = delivery_order_detail.sku_id
							" . $isBatch .  "
							AND ISNULL(ABS(delivery_order_detail2.sku_qty), 0) - ISNULL(delivery_order_detail2.sku_qty_kirim, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
							GROUP BY delivery_order_batch.delivery_order_batch_id,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									delivery_order.client_wms_id,
									FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd'),
									FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd'),
									delivery_order.delivery_order_kirim_nama,
									principle.principle_kode,
									principle.principle_id,
									principle_brand.principle_brand_nama,
									delivery_order_detail2.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									delivery_order_batch.karyawan_id,
									karyawan.karyawan_nama,
									tipe_delivery_order.tipe_delivery_order_alias,
									penerimaan.kondisi_barang
							ORDER BY delivery_order.delivery_order_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetHeaderPenerimaanPenjualanByDOId($id)
	{
		$query = $this->db->query("SELECT TOP 1
                                    do.delivery_order_batch_id,
									fdjr.delivery_order_batch_kode,
									do.delivery_order_id,
									do.delivery_order_kode,
									do.sales_order_id,
									so.sales_order_kode,
									so.sales_order_no_po,
									FORMAT(do.delivery_order_tgl_buat_do, 'dd/MM/yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(do.delivery_order_tgl_expired_do, 'dd/MM/yyyy') AS delivery_order_tgl_expired_do,
									FORMAT(do.delivery_order_tgl_surat_jalan, 'dd/MM/yyyy') AS delivery_order_tgl_surat_jalan,
									FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd/MM/yyyy') AS delivery_order_tgl_rencana_kirim,
									do.client_wms_id,
									client_wms.client_wms_nama,
									client_wms.client_wms_alamat,
									do.client_pt_id,
									ISNULL(do.delivery_order_kirim_nama, '') AS delivery_order_kirim_nama,
									ISNULL(do.delivery_order_kirim_alamat, '') AS delivery_order_kirim_alamat,
									ISNULL(do.delivery_order_kirim_telp, '') AS delivery_order_kirim_telp,
									ISNULL(do.delivery_order_kirim_provinsi, '') AS delivery_order_kirim_provinsi,
									ISNULL(do.delivery_order_kirim_kota, '') AS delivery_order_kirim_kota,
									ISNULL(do.delivery_order_kirim_kecamatan, '') AS delivery_order_kirim_kecamatan,
									ISNULL(do.delivery_order_kirim_kelurahan, '') AS delivery_order_kirim_kelurahan,
									ISNULL(do.delivery_order_kirim_kodepos, '') AS delivery_order_kirim_kodepos,
									ISNULL(do.delivery_order_kirim_area, '') AS delivery_order_kirim_area,
									do.principle_id,
									ISNULL(do.delivery_order_ambil_nama, '') AS delivery_order_ambil_nama,
									ISNULL(do.delivery_order_ambil_alamat, '') AS delivery_order_ambil_alamat,
									ISNULL(do.delivery_order_ambil_telp, '') AS delivery_order_ambil_telp,
									ISNULL(do.delivery_order_ambil_provinsi, '') AS delivery_order_ambil_provinsi,
									ISNULL(do.delivery_order_ambil_kota, '') AS delivery_order_ambil_kota,
									ISNULL(do.delivery_order_ambil_kecamatan, '') AS delivery_order_ambil_kecamatan,
									ISNULL(do.delivery_order_ambil_kelurahan, '') AS delivery_order_ambil_kelurahan,
									ISNULL(do.delivery_order_ambil_kodepos, '') AS delivery_order_ambil_kodepos,
									ISNULL(do.delivery_order_ambil_area, '') AS delivery_order_ambil_area,
									do.delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.tipe_delivery_order_id,
									ISNULL(do.delivery_order_keterangan, '') AS delivery_order_keterangan,
									do.delivery_order_status,
									CASE
										WHEN do.delivery_order_status = 'delivered' AND
										do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim'
										WHEN do.delivery_order_status = 'partially delivered' AND
										do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim sebagian'
										WHEN do.delivery_order_status = 'not delivered' AND
										do.delivery_order_kode LIKE '%/DO/%' THEN 'tidak terkirim'
										WHEN do.delivery_order_kode LIKE '%/DOR/%' THEN 'retur'
										WHEN do.delivery_order_status = 'rescheduled' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'rescheduled'
										ELSE ''
									END AS do_status,
									penerimaan_penjualan.penerimaan_penjualan_id,
									penerimaan_penjualan.penerimaan_penjualan_kode,
									FORMAT(penerimaan_penjualan.penerimaan_penjualan_tgl,'dd-MM-yyyy') AS penerimaan_penjualan_tgl,
									penerimaan_penjualan.depo_detail_id,
									depo_detail.depo_detail_nama,
									penerimaan_penjualan.karyawan_id,
									karyawan.karyawan_nama
									FROM delivery_order do
									LEFT JOIN FAS.dbo.sales_order so
									ON so.sales_order_id = do.sales_order_id 
									LEFT JOIN delivery_order_batch fdjr
									ON do.delivery_order_batch_id = fdjr.delivery_order_batch_id
									LEFT JOIN penerimaan_penjualan
									ON penerimaan_penjualan.delivery_order_batch_id = fdjr.delivery_order_batch_id
									LEFT JOIN karyawan
									ON penerimaan_penjualan.karyawan_id = karyawan.karyawan_id
									LEFT JOIN client_wms
									ON do.client_wms_id = client_wms.client_wms_id
									LEFT JOIN depo_detail
									ON penerimaan_penjualan.depo_detail_id = depo_detail.depo_detail_id
									WHERE do.delivery_order_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDetailPenerimaanPenjualanByDOId($delivery_order_id)
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
									delivery_order_batch.karyawan_id,
									karyawan.karyawan_nama,
									tipe_delivery_order.tipe_delivery_order_alias,
									penerimaan.kondisi_barang,
									SUM(ABS(delivery_order_detail.sku_qty)) AS sku_qty,
									SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) AS sku_qty_kirim,
									SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sisa_jumlah_terima
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN delivery_order_batch
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima,
									kondisi_barang
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id,
											kondisi_barang) penerimaan
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
									WHERE delivery_order.delivery_order_id IN ('$delivery_order_id')
									GROUP BY delivery_order_batch.delivery_order_batch_id,
											delivery_order_batch.delivery_order_batch_kode,
											delivery_order.delivery_order_id,
											delivery_order.delivery_order_kode,
											FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd'),
											delivery_order.delivery_order_kirim_nama,
											principle.principle_kode,
											principle_brand.principle_brand_nama,
											delivery_order_detail.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.sku_kemasan,
											sku.sku_satuan,
											delivery_order_batch.karyawan_id,
											karyawan.karyawan_nama,
											tipe_delivery_order.tipe_delivery_order_alias,
											penerimaan.kondisi_barang
									ORDER BY delivery_order.delivery_order_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetPalletPenerimaanPenjualanByDOId($delivery_order_id, $penerimaan_penjualan_id, $act)
	{
		$isBatch = "";
		if ($act == 'ViewBTBKiriman' || $act == 'ViewBTBRetur') {
			$isBatch = "WHERE penerimaan_penjualan_detail.delivery_order_id IN ($delivery_order_id)";
		} else {
			$isBatch = "WHERE penerimaan_penjualan_detail.delivery_order_id = '$delivery_order_id'";
		}

		$query = $this->db->query("SELECT DISTINCT
										-- penerimaan_penjualan_detail.delivery_order_id,
										penerimaan_penjualan_detail2.pallet_id,
										pallet.pallet_kode,
										pallet.pallet_jenis_id,
										pallet_jenis.pallet_jenis_nama
									FROM penerimaan_penjualan_detail
									LEFT JOIN penerimaan_penjualan_detail2
										ON penerimaan_penjualan_detail.penerimaan_penjualan_id = penerimaan_penjualan_detail2.penerimaan_penjualan_id
									LEFT JOIN pallet
										ON pallet.pallet_id = penerimaan_penjualan_detail2.pallet_id
									LEFT JOIN pallet_jenis
										ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									" . $isBatch . "
									AND penerimaan_penjualan_detail.penerimaan_penjualan_id = '$penerimaan_penjualan_id'
									GROUP BY penerimaan_penjualan_detail.delivery_order_id,
											penerimaan_penjualan_detail2.pallet_id,
											pallet.pallet_kode,
											pallet.pallet_jenis_id,
											pallet_jenis.pallet_jenis_nama
									ORDER BY pallet.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function get_perusahaan_by_do($id)
	{
		$query = $this->db->query("SELECT client_wms_id FROM delivery_order WHERE delivery_order_id = '$id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->client_wms_id;
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
									AND depo_detail_is_kirimulang = '1'
									ORDER BY depo_detail_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetGudangQA()
	{
		$query = $this->db->query("SELECT
										depo_detail_id,
										depo_detail_nama
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND depo_detail_is_gudang_penerima = '1'
									AND depo_detail_is_qa = '1'
									ORDER BY depo_detail_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}


	public function GetPrincipleByPerusahaan($delivery_order_id)
	{
		$query = $this->db->query("SELECT DISTINCT
										principle.*
									FROM delivery_order do
                                    LEFT JOIN client_wms_principle
                                    ON client_wms_principle.client_wms_id = do.client_wms_id
									LEFT JOIN principle
									ON client_wms_principle.principle_id = principle.principle_id
									WHERE do.delivery_order_id = '$delivery_order_id'
									ORDER BY principle.principle_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPrincipleByPenerimaanPenjualan($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT DISTINCT
										principle.*
									FROM penerimaan_penjualan
                                    LEFT JOIN client_wms_principle
                                    ON client_wms_principle.client_wms_id = penerimaan_penjualan.client_wms_id
									LEFT JOIN principle
									ON client_wms_principle.principle_id = principle.principle_id
									WHERE penerimaan_penjualan.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY principle.principle_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetCheckerByPrinciple()
	{
		// WHERE karyawan.depo_id = '" . $this->session->userdata('depo_id') . "' AND karyawan.client_wms_id = '$perusahaan' AND karyawan_principle.principle_id = '$principle' AND karyawan.karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'

		// $query = $this->db->query("SELECT
		// 								karyawan.*
		// 							FROM karyawan
		// 							LEFT JOIN karyawan_principle
		// 							ON karyawan.karyawan_id = karyawan_principle.karyawan_id
		// 							WHERE karyawan.depo_id = '" . $this->session->userdata('depo_id') . "' AND karyawan_principle.principle_id = '$principle' AND karyawan.karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
		// 							ORDER BY karyawan.karyawan_nama ASC");

		// $query = $this->db->query("SELECT DISTINCT
		// 								karyawan.*
		// 							FROM karyawan
		// 							LEFT JOIN karyawan_principle
		// 							ON karyawan.karyawan_id = karyawan_principle.karyawan_id
		// 							WHERE karyawan.depo_id = '" . $this->session->userdata('depo_id') . "' AND karyawan.karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
		// 							ORDER BY karyawan.karyawan_nama ASC");

		$query = $this->db->query("SELECT DISTINCT
										karyawan.*
									FROM karyawan
									LEFT JOIN karyawan_principle
									ON karyawan.karyawan_id = karyawan_principle.karyawan_id
									WHERE karyawan.karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
									ORDER BY karyawan.karyawan_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function get_pallet_by_arr_id($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
										pallet_temp.pallet_id,
										pallet_temp.pallet_kode,
										pallet_temp.pallet_jenis_id,
										pallet_jenis.pallet_jenis_nama,
										pallet_temp.rak_lajur_detail_id,
										rak_lajur_detail.rak_lajur_detail_nama,
										ISNULL((select pallet_kode FROM pallet WHERE pallet_id = pallet_temp.pallet_id),'0') AS status_pallet
									FROM pallet_temp
									LEFT JOIN pallet_jenis
									ON pallet_temp.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = pallet_temp.rak_lajur_detail_id
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

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
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

	public function Insert_PalletTemp($delivery_order_batch_id, $pallet_id, $pallet_kode, $rak_lajur_detail_id, $depo_detail_id)
	{
		//insert delivery_order_progress
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("depo_id", $this->session->userdata('depo_id'));
		$this->db->set("pallet_kode", $pallet_kode);
		$this->db->set("rak_lajur_detail_id", $rak_lajur_detail_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
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

	public function Insert_PalletTemp2($delivery_order_batch_id, $pallet_id, $depo_detail_id)
	{
		$query = $this->db->query("INSERT INTO pallet_temp
									(pallet_id,
									pallet_jenis_id,
									delivery_order_batch_id,
									depo_id,
									rak_lajur_detail_id,
									pallet_kode,
									pallet_tanggal_create,
									pallet_who_create,
									pallet_is_aktif,
									depo_detail_id,
									id_temp,
									flag,
									tipe_stock,
									karyawan_id,
									depo_detail_nama)
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
									'$depo_detail_id',
									NULL,
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
									ISNULL(sku_stock.sku_stock_batch_no,'') AS sku_stock_batch_no,
									FORMAT(pallet_detail_temp.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
									FORMAT(pallet_detail_temp.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date_retur,
									ISNULL(pallet_detail_temp.sku_stock_qty, 0) AS sku_stock_qty,
									ISNULL(pallet_detail_temp.jumlah_sisa, 0) AS jumlah_sisa,
									penerimaan_tipe.penerimaan_tipe_nama
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_detail_temp.pallet_id = pallet_temp.pallet_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = pallet_detail_temp.sku_stock_id
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
			$query = $query->result();
		}

		return $query;
	}

	public function Get_PalletDetail2($penerimaan_penjualan_id, $delivery_order_id, $pallet_id)
	{
		$query = $this->db->query("SELECT
									pallet_detail.pallet_id,
									principle.principle_kode AS principle,
									principle_brand.principle_brand_nama AS brand,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_induk_id,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									pallet_detail.sku_stock_id,
									FORMAT(pallet_detail.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
									penerimaan_tipe.penerimaan_tipe_nama,
									ISNULL(sku_stock.sku_stock_batch_no,'') AS sku_stock_batch_no,
									SUM(ISNULL(pallet_detail.sku_stock_qty, 0)) - SUM(ISNULL(pallet_detail.sku_stock_ambil, 0)) + SUM(ISNULL(pallet_detail.sku_stock_in, 0)) - SUM(ISNULL(pallet_detail.sku_stock_out, 0)) + SUM(ISNULL(pallet_detail.sku_stock_terima, 0)) AS sku_stock_terima
									FROM pallet
									LEFT JOIN pallet_detail
									ON pallet_detail.pallet_id = pallet.pallet_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = pallet_detail.sku_stock_id
									LEFT JOIN (SELECT
									detail.delivery_order_id,
									detail2.pallet_id,
									detail.sku_id,
									detail.sku_stock_id,
									detail.sku_jumlah_barang,
									detail.sku_jumlah_terima
									FROM penerimaan_penjualan_detail detail
									LEFT JOIN penerimaan_penjualan_detail2 detail2
									ON detail2.penerimaan_penjualan_id = detail.penerimaan_penjualan_id
									WHERE detail.penerimaan_penjualan_id = '$penerimaan_penjualan_id'
									GROUP BY detail.delivery_order_id,
											detail2.pallet_id,
											detail.sku_id,
											detail.sku_stock_id,
											detail.sku_jumlah_barang,
											detail.sku_jumlah_terima) penerimaan
									ON penerimaan.pallet_id = pallet.pallet_id
									AND penerimaan.sku_stock_id = sku_stock.sku_stock_id
									LEFT JOIN sku
									ON pallet_detail.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN penerimaan_tipe
									ON penerimaan_tipe.penerimaan_tipe_id = pallet_detail.penerimaan_tipe_id
									WHERE pallet_detail.pallet_id = '$pallet_id'
									AND pallet_detail.sku_id in (select sku_id from delivery_order_detail where delivery_order_id IN ($delivery_order_id))
									GROUP BY  pallet_detail.pallet_id,
											principle.principle_kode,
											principle_brand.principle_brand_nama,
											sku.sku_id,
											sku.sku_kode,
											sku.sku_induk_id,
											sku.sku_nama_produk,
											sku.sku_kemasan,
											sku.sku_satuan,
											pallet_detail.sku_stock_id,
											FORMAT(pallet_detail.sku_stock_expired_date, 'yyyy-MM-dd'),
											penerimaan_tipe.penerimaan_tipe_nama,
											ISNULL(sku_stock.sku_stock_batch_no,'')
									ORDER BY penerimaan_tipe.penerimaan_tipe_nama, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function Get_SKU_Expired_Date($sku_id)
	{
		$this->db->distinct()->select("sku_stock_id,sku_id,FORMAT(sku_stock_expired_date,'dd-MM-yyyy') AS sku_stock_expired_date")
			->from("sku_stock")
			->where("sku_id", $sku_id)
			->where("depo_id", $this->session->userdata('depo_id'))
			->order_by("FORMAT(sku_stock_expired_date,'dd-MM-yyyy')");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		// return $this->db->last_query();
		return $query;
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

	public function Insert_PalletDetailTempDoRetur($delivery_order_ids, $pallet_id, $client_wms_id, $depo_detail_id, $act)
	{
		$isBatch = "";
		if ($act == 'ProsesBTBKiriman' || $act == 'ProsesBTBRetur') {
			$isBatch = "WHERE delivery_order.delivery_order_id IN (" . implode(',', $delivery_order_ids) . ")";
		} else {
			$isBatch = "WHERE delivery_order.delivery_order_id = '$delivery_order_ids'";
		}

		$query = $this->db->query("SELECT
		                            NEWID() AS pallet_detail_id,
		                            '" . $pallet_id . "' AS pallet_id,
		                            delivery_order_detail.sku_id,
		                            NULL AS sku_stock_id,
		                            FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') AS sku_expdate,
		                            SUM(ISNULL(delivery_order_detail2.sku_qty, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sku_stock_qty,
		                            SUM(ISNULL(ABS(delivery_order_detail.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sisa_jumlah_terima,
		                            'BDCFCBE1-52CF-404F-84B5-A19F0918CA8D' AS penerimaan_tipe_id,
		                            NULL,
		                            GETDATE() AS tanggal_create,
		                            NULL,
		                            NULL,
		                            NULL,
		                            NULL
		                        FROM delivery_order
		                            LEFT JOIN delivery_order_detail
		                            ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
		                            LEFT JOIN (SELECT DISTINCT
		                                            delivery_order_detail_id,
		                                            delivery_order_id,
		                                            sku_id,
		                                            sku_stock_id,
		                                            sku_expdate,
		                                            sku_qty
		                                        FROM delivery_order_detail2) delivery_order_detail2
		                            ON delivery_order_detail2.delivery_order_id = delivery_order.delivery_order_id
		                            AND delivery_order_detail2.delivery_order_detail_id = delivery_order_detail.delivery_order_detail_id
		                            AND delivery_order_detail2.sku_id = delivery_order_detail.sku_id
		                            LEFT JOIN (SELECT
		                                            delivery_order_id,
		                                            sku_id,
		                                            sku_stock_id,
		                                            SUM(sku_jumlah_barang) AS sku_jumlah_barang,
		                                            SUM(sku_jumlah_terima) AS sku_jumlah_terima
		                                        FROM penerimaan_penjualan_detail
		                                        GROUP BY delivery_order_id,
		                                                sku_id,
		                                                sku_stock_id) penerimaan
		                            ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
		                            AND penerimaan.sku_id = delivery_order_detail.sku_id
		                            AND penerimaan.sku_stock_id = delivery_order_detail2.sku_stock_id
		                            LEFT JOIN tipe_delivery_order
		                            ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
		                            " . $isBatch . "
		                            --AND ISNULL(delivery_order_detail2.sku_qty, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
		                            AND delivery_order.delivery_order_status = 'delivered'
									AND tipe_delivery_order.tipe_delivery_order_nama = 'Retur'
		                        GROUP BY delivery_order_detail.sku_id,
		                                FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd')
		                        ORDER BY FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') ASC");

		$query = $this->db->query("SELECT
									NEWID() AS pallet_detail_id,
									'" . $pallet_id . "' AS pallet_id,
									delivery_order_detail2.sku_id,
									NULL AS sku_stock_id,
									FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') AS sku_expdate,
									SUM(ISNULL(delivery_order_detail2.sku_qty, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sku_stock_qty,
									SUM(ISNULL(ABS(delivery_order_detail2.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail2.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sisa_jumlah_terima,
									'BDCFCBE1-52CF-404F-84B5-A19F0918CA8D' AS penerimaan_tipe_id,
									NULL,
									GETDATE() AS tanggal_create,
									NULL,
									NULL,
									NULL,
									NULL
								FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT DISTINCT
													delivery_order_detail_id,
													delivery_order_id,
													sku_id,
													sku_stock_id,
													sku_expdate,
													sku_qty,
													sku_qty_kirim
												FROM delivery_order_detail2) delivery_order_detail2
									ON delivery_order_detail2.delivery_order_id = delivery_order.delivery_order_id
									AND delivery_order_detail2.delivery_order_detail_id = delivery_order_detail.delivery_order_detail_id
									AND delivery_order_detail2.sku_id = delivery_order_detail.sku_id
									LEFT JOIN (SELECT
													delivery_order_id,
													sku_id,
													sku_stock_id,
													SUM(sku_jumlah_barang) AS sku_jumlah_barang,
													SUM(sku_jumlah_terima) AS sku_jumlah_terima
												FROM penerimaan_penjualan_detail
												GROUP BY delivery_order_id,
														sku_id,
														sku_stock_id) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									AND penerimaan.sku_stock_id = delivery_order_detail2.sku_stock_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									" . $isBatch . "
									--AND ISNULL(delivery_order_detail2.sku_qty, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
									AND delivery_order.delivery_order_status = 'delivered'
									AND tipe_delivery_order.tipe_delivery_order_nama = 'Retur'
								GROUP BY delivery_order_detail2.sku_id,
										FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd')
								ORDER BY FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') ASC");

		$mergedData = array();  // New array to store merged data

		foreach ($query->result_array() as $key => $value) {
			$skuId = $value['sku_id'];
			$skuExpDate = $value['sku_expdate'];

			// Check if the data with the same sku_id and sku_stock_expired_date already exists in the merged data
			// $found = false;
			// foreach ($mergedData as &$mergedValue) {
			// 	if ($mergedValue['sku_id'] === $skuId && $mergedValue['sku_expdate'] === $skuExpDate) {
			// 		// If data with the same sku_id and sku_stock_expired_date exists, merge the quantities
			// 		$mergedValue['sku_stock_qty'] += $value['sku_stock_qty'];
			// 		$mergedValue['jumlah_sisa'] += $value['sisa_jumlah_terima'];
			// 		$found = true;
			// 		break;
			// 	}
			// }

			// $cekSkuStock = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = " . $value['sku_id'] . " AND sku_stock_expired_date = " . $value['sku_expdate'] . " AND depo_detail_id = " . $depo_detail_id . " ");
			// $skuStockId = $this->getSkuStockId($value['sku_id'], $value['sku_expdate'], $depo_detail_id, $client_wms_id, $value);

			// if (!$found) {
			// 	// If data doesn't exist, add it to the merged data
			// 	$mergedData[] = array(
			// 		'pallet_detail_id' => $value['pallet_detail_id'],
			// 		'pallet_id' => $value['pallet_id'],
			// 		'sku_id' => $skuId,
			// 		'sku_stock_id' => $value['sku_stock_id'],
			// 		'sku_stock_expired_date' => $skuExpDate,
			// 		'sku_stock_qty' => $value['sku_stock_qty'],
			// 		'penerimaan_tipe_id' => $value['penerimaan_tipe_id'],
			// 		'tanggal_create' => $value['tanggal_create'],
			// 		'jumlah_sisa' => $value['sisa_jumlah_terima']
			// 	);
			// }
			$this->db->set('pallet_detail_id', $value['pallet_detail_id']);
			$this->db->set('pallet_id', $value['pallet_id']);
			$this->db->set('sku_id', $skuId);
			// $this->db->set('sku_stock_id', $skuStockId);
			$this->db->set('sku_stock_expired_date', $skuExpDate);
			$this->db->set('sku_stock_qty', $value['sku_stock_qty']);
			$this->db->set('penerimaan_tipe_id', $value['penerimaan_tipe_id']);
			$this->db->set('tanggal_create', $value['tanggal_create']);
			$this->db->set('jumlah_sisa', $value['sisa_jumlah_terima']);
			$this->db->insert('pallet_detail_temp');
			// if (!$found) {
			// 	$mergedData[] = array(
			// 		'pallet_detail_id' => $value['pallet_detail_id'],
			// 		'pallet_id' => $value['pallet_id'],
			// 		'sku_id' => $skuId,
			// 		'sku_stock_id' => $skuStockId,
			// 		'sku_stock_expired_date' => $skuExpDate,
			// 		'sku_stock_qty' => $value['sku_stock_qty'],
			// 		'penerimaan_tipe_id' => $value['penerimaan_tipe_id'],
			// 		'tanggal_create' => $value['tanggal_create'],
			// 		'jumlah_sisa' => $value['sisa_jumlah_terima']
			// 	);
			// }
		}

		// var_dump($skuStockId);
		// die;

		// Insert the merged data into the database
		// foreach ($mergedData as $mergedValue) {
		// 	$this->db->insert("pallet_detail_temp", $mergedValue);
		// }

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Insert_PalletDetailTempTerkirimSebagian($delivery_order_id, $pallet_id, $client_wms_id, $depo_detail_id, $act)
	{
		$isBatch = "";
		if ($act == 'ProsesBTBKiriman' || $act == 'ProsesBTBRetur') {
			$isBatch = "WHERE delivery_order.delivery_order_id IN ($delivery_order_id)";
		} else {
			$isBatch = "WHERE delivery_order.delivery_order_id = '$delivery_order_id'";
		}

		$query = $this->db->query("SELECT
									NEWID() AS pallet_detail_id,
									'" . $pallet_id . "' AS pallet_id,
									delivery_order_detail.sku_id,
									delivery_order_detail2.sku_stock_id,
									FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') AS sku_expdate,
									SUM(ISNULL(delivery_order_detail2.sku_qty, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sku_stock_qty,
									SUM(ISNULL(ABS(delivery_order_detail.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sisa_jumlah_terima,
									'29F5A94F-C55E-4BA0-B3EE-57A93327735F' AS penerimaan_tipe_id,
									NULL,
									GETDATE() AS tanggal_create,
									NULL,
									NULL,
									NULL,
									NULL
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT DISTINCT
									delivery_order_detail_id,
									delivery_order_id,
									sku_id,
									sku_stock_id,
									sku_expdate,
									sku_qty
									FROM delivery_order_detail2) delivery_order_detail2
									ON delivery_order_detail2.delivery_order_id = delivery_order.delivery_order_id
									AND delivery_order_detail2.delivery_order_detail_id = delivery_order_detail.delivery_order_detail_id
									AND delivery_order_detail2.sku_id = delivery_order_detail.sku_id
									LEFT JOIN (SELECT
										delivery_order_id,
										sku_id,
										sku_stock_id,
										SUM(sku_jumlah_barang) AS sku_jumlah_barang,
										SUM(sku_jumlah_terima) AS sku_jumlah_terima
										FROM penerimaan_penjualan_detail
										GROUP BY delivery_order_id,
												sku_id,
												sku_stock_id) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									AND penerimaan.sku_stock_id = delivery_order_detail2.sku_stock_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									" . $isBatch . "
									AND delivery_order.delivery_order_status = 'partially delivered'
									GROUP BY delivery_order_detail.sku_id,
											delivery_order_detail2.sku_stock_id,
											FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd')
									HAVING SUM(ISNULL(ABS(delivery_order_detail.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) > 0
									ORDER BY FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') ASC");

		// return $this->db->last_query();
		// die;

		foreach ($query->result_array() as $key => $value) {

			$sku_stock_penerimaan = $this->db->query("SELECT * FROM sku_stock WHERE sku_stock_id = '" . $value['sku_stock_id'] . "' ")->row(0);

			$arr_sku_stock = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = '" . $sku_stock_penerimaan->sku_id . "' AND sku_stock_expired_date = '" . $sku_stock_penerimaan->sku_stock_expired_date . "' AND depo_detail_id = '$depo_detail_id' ");

			// $sku_stock_id = "";

			// if ($arr_sku_stock->num_rows() == 0) {
			// 	$query_sku_stock_id = $this->db->query("SELECT NEWID() AS generate_kode");

			// 	$sku_stock_id = $query_sku_stock_id->row(0)->generate_kode;

			// 	$this->db->set("sku_stock_id", $sku_stock_id);
			// 	$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
			// 	$this->db->set("client_wms_id", $client_wms_id);
			// 	$this->db->set("depo_id", $this->session->userdata('depo_id'));
			// 	$this->db->set("depo_detail_id", $depo_detail_id);
			// 	$this->db->set("sku_induk_id", $sku_stock_penerimaan->sku_induk_id);
			// 	$this->db->set("sku_id", $sku_stock_penerimaan->sku_id);
			// 	$this->db->set("sku_stock_expired_date", $sku_stock_penerimaan->sku_stock_expired_date);
			// 	$this->db->set("sku_stock_batch_no", $sku_stock_penerimaan->sku_stock_batch_no);
			// 	$this->db->set("sku_stock_awal", "0");
			// 	$this->db->set("sku_stock_masuk", $value['sku_stock_qty']);
			// 	$this->db->set("sku_stock_alokasi", "0");
			// 	$this->db->set("sku_stock_saldo_alokasi", "0");
			// 	$this->db->set("sku_stock_keluar", "0");
			// 	$this->db->set("sku_stock_akhir", "0");
			// 	$this->db->set("sku_stock_is_jual", "1");
			// 	$this->db->set("sku_stock_is_aktif", "1");
			// 	$this->db->set("sku_stock_is_deleted", "0");

			// 	$this->db->insert("sku_stock");
			// } else {
			// 	$sku_stock_id = $arr_sku_stock->row(0)->sku_stock_id;
			// }

			$this->db->set("pallet_detail_id", $value['pallet_detail_id']);
			$this->db->set("pallet_id", $value['pallet_id']);
			$this->db->set("sku_id", $value['sku_id']);

			if ($arr_sku_stock->num_rows() > 0) {
				$sku_stock_id = $arr_sku_stock->row(0)->sku_stock_id;
				$this->db->set("sku_stock_id", $sku_stock_id);
			}

			$this->db->set("sku_stock_expired_date", $value['sku_expdate']);
			$this->db->set("sku_stock_qty", $value['sku_stock_qty']);
			$this->db->set("penerimaan_tipe_id", $value['penerimaan_tipe_id']);
			// $this->db->set("sku_exp_date", $value['sku_exp_date']);
			$this->db->set("tanggal_create", $value['tanggal_create']);
			// $this->db->set("jumlah_barang", $value['jumlah_barang']);
			// $this->db->set("jumlah_terima", $value['jumlah_terima']);
			$this->db->set("jumlah_sisa", $value['sisa_jumlah_terima']);
			// $this->db->set("batch_no", $value['batch_no']);

			$this->db->insert("pallet_detail_temp");
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

	// public function Insert_PalletDetailTempGagal($delivery_order_id, $pallet_id, $client_wms_id, $depo_detail_id, $act)
	// {
	// 	$isBatch = "";
	// 	if ($act == 'ProsesBTBKiriman' || $act == 'ProsesBTBRetur') {
	// 		$isBatch = "WHERE delivery_order.delivery_order_id IN ($delivery_order_id)";
	// 	} else {
	// 		$isBatch = "WHERE delivery_order.delivery_order_id = '$delivery_order_id'";
	// 	}

	// 	$query = $this->db->query("SELECT
	// 								NEWID() AS pallet_detail_id,
	// 								'" . $pallet_id . "' AS pallet_id,
	// 								delivery_order_detail.sku_id,
	// 								delivery_order_detail2.sku_stock_id,
	// 								FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') AS sku_expdate,
	// 								SUM(ISNULL(delivery_order_detail2.sku_qty, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sku_stock_qty,
	// 								SUM(ISNULL(ABS(delivery_order_detail.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sisa_jumlah_terima,
	// 								'79F2522A-CEA5-4FF8-BC79-C0B28808877D' AS penerimaan_tipe_id,
	// 								NULL,
	// 								GETDATE() AS tanggal_create,
	// 								NULL,
	// 								NULL,
	// 								NULL,
	// 								NULL
	// 								FROM delivery_order
	// 								LEFT JOIN delivery_order_detail
	// 								ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
	// 								LEFT JOIN (SELECT DISTINCT
	// 								delivery_order_detail_id,
	// 								delivery_order_id,
	// 								sku_id,
	// 								sku_stock_id,
	// 								sku_expdate,
	// 								sku_qty
	// 								FROM delivery_order_detail2) delivery_order_detail2
	// 								ON delivery_order_detail2.delivery_order_id = delivery_order.delivery_order_id
	// 								AND delivery_order_detail2.delivery_order_detail_id = delivery_order_detail.delivery_order_detail_id
	// 								AND delivery_order_detail2.sku_id = delivery_order_detail.sku_id
	// 								LEFT JOIN (SELECT
	// 								delivery_order_id,
	// 								sku_id,
	// 								sku_stock_id,
	// 								SUM(sku_jumlah_barang) AS sku_jumlah_barang,
	// 								SUM(sku_jumlah_terima) AS sku_jumlah_terima
	// 								FROM penerimaan_penjualan_detail
	// 								GROUP BY delivery_order_id,
	// 										sku_id,
	// 										sku_stock_id) penerimaan
	// 								ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
	// 								AND penerimaan.sku_id = delivery_order_detail.sku_id
	// 								AND penerimaan.sku_stock_id = delivery_order_detail2.sku_stock_id
	// 								LEFT JOIN tipe_delivery_order
	// 								ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
	// 								" . $isBatch . "
	// 								AND ISNULL(delivery_order_detail2.sku_qty, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
	// 								AND delivery_order.delivery_order_status IN ('not delivered', 'partially delivered')
	// 								GROUP BY delivery_order_detail.sku_id,
	// 										delivery_order_detail2.sku_stock_id,
	// 										FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd')
	// 								ORDER BY FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') ASC");

	// 	foreach ($query->result_array() as $key => $value) {

	// 		$sku_stock_penerimaan = $this->db->query("SELECT * FROM sku_stock WHERE sku_stock_id = '" . $value['sku_stock_id'] . "' ")->row(0);

	// 		$arr_sku_stock = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = '" . $sku_stock_penerimaan->sku_id . "' AND sku_stock_expired_date = '" . $sku_stock_penerimaan->sku_stock_expired_date . "' AND depo_detail_id = '$depo_detail_id' ");

	// 		// $sku_stock_id = "";

	// 		// if ($arr_sku_stock->num_rows() == 0) {
	// 		// 	$query_sku_stock_id = $this->db->query("SELECT NEWID() AS generate_kode");

	// 		// 	$sku_stock_id = $query_sku_stock_id->row(0)->generate_kode;

	// 		// 	$this->db->set("sku_stock_id", $sku_stock_id);
	// 		// 	$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
	// 		// 	$this->db->set("client_wms_id", $client_wms_id);
	// 		// 	$this->db->set("depo_id", $this->session->userdata('depo_id'));
	// 		// 	$this->db->set("depo_detail_id", $depo_detail_id);
	// 		// 	$this->db->set("sku_induk_id", $sku_stock_penerimaan->sku_induk_id);
	// 		// 	$this->db->set("sku_id", $sku_stock_penerimaan->sku_id);
	// 		// 	$this->db->set("sku_stock_expired_date", $sku_stock_penerimaan->sku_stock_expired_date);
	// 		// 	$this->db->set("sku_stock_batch_no", $sku_stock_penerimaan->sku_stock_batch_no);
	// 		// 	$this->db->set("sku_stock_awal", "0");
	// 		// 	$this->db->set("sku_stock_masuk", $value['sku_stock_qty']);
	// 		// 	$this->db->set("sku_stock_alokasi", "0");
	// 		// 	$this->db->set("sku_stock_saldo_alokasi", "0");
	// 		// 	$this->db->set("sku_stock_keluar", "0");
	// 		// 	$this->db->set("sku_stock_akhir", "0");
	// 		// 	$this->db->set("sku_stock_is_jual", "1");
	// 		// 	$this->db->set("sku_stock_is_aktif", "1");
	// 		// 	$this->db->set("sku_stock_is_deleted", "0");

	// 		// 	$this->db->insert("sku_stock");
	// 		// } else {
	// 		// 	$sku_stock_id = $arr_sku_stock->row(0)->sku_stock_id;
	// 		// }

	// 		$this->db->set("pallet_detail_id", $value['pallet_detail_id']);
	// 		$this->db->set("pallet_id", $value['pallet_id']);
	// 		$this->db->set("sku_id", $value['sku_id']);
	// 		// $this->db->set("sku_stock_id", $sku_stock_id);

	// 		if ($arr_sku_stock->num_rows() > 0) {
	// 			$sku_stock_id = $arr_sku_stock->row(0)->sku_stock_id;
	// 			$this->db->set("sku_stock_id", $sku_stock_id);
	// 		}

	// 		$this->db->set("sku_stock_expired_date", $value['sku_expdate']);
	// 		$this->db->set("sku_stock_qty", $value['sku_stock_qty']);
	// 		$this->db->set("penerimaan_tipe_id", $value['penerimaan_tipe_id']);
	// 		// $this->db->set("sku_exp_date", $value['sku_exp_date']);
	// 		$this->db->set("tanggal_create", $value['tanggal_create']);
	// 		// $this->db->set("jumlah_barang", $value['jumlah_barang']);
	// 		// $this->db->set("jumlah_terima", $value['jumlah_terima']);
	// 		$this->db->set("jumlah_sisa", $value['sisa_jumlah_terima']);
	// 		// $this->db->set("batch_no", $value['batch_no']);

	// 		$this->db->insert("pallet_detail_temp");
	// 	}

	// 	$affectedrows = $this->db->affected_rows();
	// 	if ($affectedrows > 0) {
	// 		$queryinsert = 1;
	// 	} else {
	// 		$queryinsert = 0;
	// 	}

	// 	return $queryinsert;
	// 	// return $this->db->last_query();
	// }

	public function Insert_PalletDetailTempGagal($delivery_order_ids, $pallet_id, $client_wms_id, $depo_detail_id, $act)
	{
		$isBatch = "";
		if ($act == 'ProsesBTBKiriman' || $act == 'ProsesBTBRetur') {
			$isBatch = "WHERE delivery_order.delivery_order_id IN (" . implode(',', $delivery_order_ids) . ")";
		} else {
			$isBatch = "WHERE delivery_order.delivery_order_id = '$delivery_order_ids'";
		}

		// $query = $this->db->query("SELECT
		//                             NEWID() AS pallet_detail_id,
		//                             '" . $pallet_id . "' AS pallet_id,
		//                             delivery_order_detail.sku_id,
		// 							sku.sku_nama_produk,
		//                             NULL AS sku_stock_id,
		//                             FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') AS sku_expdate,
		//                             SUM(ISNULL(delivery_order_detail2.sku_qty, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sku_stock_qty,
		//                             SUM(ISNULL(ABS(delivery_order_detail.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sisa_jumlah_terima,
		//                             '79F2522A-CEA5-4FF8-BC79-C0B28808877D' AS penerimaan_tipe_id,
		//                             NULL,
		//                             GETDATE() AS tanggal_create,
		//                             NULL,
		//                             NULL,
		//                             NULL,
		//                             NULL
		//                         FROM delivery_order
		//                             LEFT JOIN delivery_order_detail
		//                             ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
		//                             LEFT JOIN (SELECT DISTINCT
		//                                             delivery_order_detail_id,
		//                                             delivery_order_id,
		//                                             sku_id,
		//                                             sku_stock_id,
		//                                             sku_expdate,
		//                                             sku_qty
		//                                         FROM delivery_order_detail2) delivery_order_detail2
		//                             ON delivery_order_detail2.delivery_order_id = delivery_order.delivery_order_id
		//                             AND delivery_order_detail2.delivery_order_detail_id = delivery_order_detail.delivery_order_detail_id
		//                             AND delivery_order_detail2.sku_id = delivery_order_detail.sku_id
		//                             LEFT JOIN (SELECT
		//                                             delivery_order_id,
		//                                             sku_id,
		//                                             sku_stock_id,
		//                                             SUM(sku_jumlah_barang) AS sku_jumlah_barang,
		//                                             SUM(sku_jumlah_terima) AS sku_jumlah_terima
		//                                         FROM penerimaan_penjualan_detail
		//                                         GROUP BY delivery_order_id,
		//                                                 sku_id,
		//                                                 sku_stock_id) penerimaan
		//                             ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
		//                             AND penerimaan.sku_id = delivery_order_detail.sku_id
		//                             AND penerimaan.sku_stock_id = delivery_order_detail2.sku_stock_id
		//                             LEFT JOIN tipe_delivery_order
		//                             ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
		// 							left join sku on delivery_order_detail.sku_id = sku.sku_id
		//                             " . $isBatch . "
		//                             AND ISNULL(delivery_order_detail2.sku_qty, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
		//                             AND delivery_order.delivery_order_status IN ('not delivered', 'partially delivered')
		//                         GROUP BY delivery_order_detail.sku_id,
		// 								sku.sku_nama_produk,
		//                                 FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd')
		// 								HAVING SUM(ISNULL(ABS(delivery_order_detail.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) <> 0
		//                         ORDER BY FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') ASC");

		$query = $this->db->query("SELECT
										NEWID() AS pallet_detail_id,
										'" . $pallet_id . "' AS pallet_id,
										delivery_order_detail2.sku_id,
										sku.sku_nama_produk,
										NULL AS sku_stock_id,
										FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') AS sku_expdate,
										SUM(ISNULL(delivery_order_detail2.sku_qty, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sku_stock_qty,
										SUM(ISNULL(ABS(delivery_order_detail2.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail2.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sisa_jumlah_terima,
										'79F2522A-CEA5-4FF8-BC79-C0B28808877D' AS penerimaan_tipe_id,
										NULL,
										GETDATE() AS tanggal_create,
										NULL,
										NULL,
										NULL,
										NULL
									FROM delivery_order
									LEFT JOIN delivery_order_detail
										ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT DISTINCT
										delivery_order_detail_id,
										delivery_order_id,
										sku_id,
										sku_stock_id,
										sku_expdate,
										sku_qty,
										delivery_order_detail2.sku_qty_kirim
									FROM delivery_order_detail2) delivery_order_detail2
										ON delivery_order_detail2.delivery_order_id = delivery_order.delivery_order_id
										AND delivery_order_detail2.delivery_order_detail_id = delivery_order_detail.delivery_order_detail_id
										AND delivery_order_detail2.sku_id = delivery_order_detail.sku_id
									LEFT JOIN (SELECT
										delivery_order_id,
										sku_id,
										sku_stock_id,
										SUM(sku_jumlah_barang) AS sku_jumlah_barang,
										SUM(sku_jumlah_terima) AS sku_jumlah_terima
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id,
											sku_stock_id) penerimaan
										ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
										AND penerimaan.sku_id = delivery_order_detail.sku_id
										AND penerimaan.sku_stock_id = delivery_order_detail2.sku_stock_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN sku
										ON delivery_order_detail.sku_id = sku.sku_id
									" . $isBatch . "
									AND ISNULL(delivery_order_detail2.sku_qty, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
									AND delivery_order.delivery_order_status IN ('not delivered', 'partially delivered')
									AND delivery_order.delivery_order_id NOT IN (SELECT delivery_order_id FROM penerimaan_penjualan_detail)
									GROUP BY delivery_order_detail2.sku_id,
											sku.sku_nama_produk,
											FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd')
									HAVING SUM(ISNULL(ABS(delivery_order_detail2.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail2.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) <> 0
									ORDER BY FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') ASC");

		$mergedData = array();  // New array to store merged data

		foreach ($query->result_array() as $key => $value) {
			$skuId = $value['sku_id'];
			$skuExpDate = $value['sku_expdate'];

			// Check if the data with the same sku_id and sku_stock_expired_date already exists in the merged data
			// $found = false;
			// foreach ($mergedData as &$mergedValue) {
			// 	if ($mergedValue['sku_id'] === $skuId && $mergedValue['sku_expdate'] === $skuExpDate) {
			// 		// If data with the same sku_id and sku_stock_expired_date exists, merge the quantities
			// 		$mergedValue['sku_stock_qty'] += $value['sku_stock_qty'];
			// 		$mergedValue['jumlah_sisa'] += $value['sisa_jumlah_terima'];
			// 		$found = true;
			// 		break;
			// 	}
			// }

			// $cekSkuStock = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = " . $value['sku_id'] . " AND sku_stock_expired_date = " . $value['sku_expdate'] . " AND depo_detail_id = " . $depo_detail_id . " ");
			// $skuStockId = $this->getSkuStockId($value['sku_id'], $value['sku_expdate'], $depo_detail_id, $client_wms_id, $value);

			// if (!$found) {
			// 	// If data doesn't exist, add it to the merged data
			// 	$mergedData[] = array(
			// 		'pallet_detail_id' => $value['pallet_detail_id'],
			// 		'pallet_id' => $value['pallet_id'],
			// 		'sku_id' => $skuId,
			// 		'sku_stock_id' => $value['sku_stock_id'],
			// 		'sku_stock_expired_date' => $skuExpDate,
			// 		'sku_stock_qty' => $value['sku_stock_qty'],
			// 		'penerimaan_tipe_id' => $value['penerimaan_tipe_id'],
			// 		'tanggal_create' => $value['tanggal_create'],
			// 		'jumlah_sisa' => $value['sisa_jumlah_terima']
			// 	);
			// }

			$this->db->set('pallet_detail_id', $value['pallet_detail_id']);
			$this->db->set('pallet_id', $value['pallet_id']);
			$this->db->set('sku_id', $skuId);
			$this->db->set('sku_stock_id', null);
			$this->db->set('sku_stock_expired_date', $skuExpDate);
			$this->db->set('sku_stock_qty', $value['sku_stock_qty']);
			$this->db->set('penerimaan_tipe_id', $value['penerimaan_tipe_id']);
			$this->db->set('tanggal_create', $value['tanggal_create']);
			$this->db->set('jumlah_sisa', $value['sisa_jumlah_terima']);
			$this->db->insert('pallet_detail_temp');

			// if (!$found) {
			// 	$mergedData[] = array(
			// 		'pallet_detail_id' => $value['pallet_detail_id'],
			// 		'pallet_id' => $value['pallet_id'],
			// 		'sku_id' => $skuId,
			// 		'sku_stock_id' => $skuStockId,
			// 		'sku_stock_expired_date' => $skuExpDate,
			// 		'sku_stock_qty' => $value['sku_stock_qty'],
			// 		'penerimaan_tipe_id' => $value['penerimaan_tipe_id'],
			// 		'tanggal_create' => $value['tanggal_create'],
			// 		'jumlah_sisa' => $value['sisa_jumlah_terima']
			// 	);
			// }
		}

		// var_dump($skuStockId);
		// die;

		// Insert the merged data into the database
		// foreach ($mergedData as $mergedValue) {
		// 	$this->db->insert("pallet_detail_temp", $mergedValue);
		// }

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function getSkuStockId($skuId, $skuExpDate, $depo_detail_id, $client_wms_id, $value)
	{
		// $sku_stock_penerimaan = $this->db->query("SELECT * FROM sku_stock WHERE sku_stock_id = '" . $value['sku_stock_id'] . "' ")->row(0);
		// die;
		// $arr_sku_stock = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = '" . $skuId . "' AND sku_stock_expired_date = '" . $skuExpDate . "' AND depo_detail_id = '$depo_detail_id' ");
		// var_dump($arr_sku_stock);

		$sku_stock_id = "";

		// if ($arr_sku_stock->num_rows() == 0) {
		// 	$query_sku_stock_id = $this->db->query("SELECT NEWID() AS generate_kode");

		// 	$sku_stock_id = $query_sku_stock_id->row(0)->generate_kode;

		// 	$this->db->set("sku_stock_id", $sku_stock_id);
		// 	$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
		// 	$this->db->set("client_wms_id", $client_wms_id);
		// 	$this->db->set("depo_id", $this->session->userdata('depo_id'));
		// 	$this->db->set("depo_detail_id", $depo_detail_id);
		// 	$this->db->set("sku_induk_id", $sku_stock_penerimaan->sku_induk_id);
		// 	$this->db->set("sku_id", $sku_stock_penerimaan->sku_id);
		// 	$this->db->set("sku_stock_expired_date", $sku_stock_penerimaan->sku_stock_expired_date);
		// 	$this->db->set("sku_stock_batch_no", $sku_stock_penerimaan->sku_stock_batch_no);
		// 	$this->db->set("sku_stock_awal", "0");
		// 	$this->db->set("sku_stock_masuk", $value['sisa_jumlah_terima']);
		// 	$this->db->set("sku_stock_alokasi", "0");
		// 	$this->db->set("sku_stock_saldo_alokasi", "0");
		// 	$this->db->set("sku_stock_keluar", "0");
		// 	$this->db->set("sku_stock_akhir", "0");
		// 	$this->db->set("sku_stock_is_jual", "1");
		// 	$this->db->set("sku_stock_is_aktif", "1");
		// 	$this->db->set("sku_stock_is_deleted", "0");

		// 	$this->db->insert("sku_stock");
		// } else {
		// 	$sku_stock_id = $arr_sku_stock->row(0)->sku_stock_id;
		// }
		$sku_induk_id = $this->db->query("SELECT sku_induk_id FROM sku WHERE sku_id = '$skuId'")->row()->sku_induk_id;

		$query = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = '" . $skuId . "' AND sku_stock_expired_date = '" . $skuExpDate . "' AND depo_detail_id = '" . $depo_detail_id . "'");

		if ($query->num_rows() > 0) {
			return $query->row()->sku_stock_id; // SKU stock exists, return its ID
		} else {
			$query_sku_stock_id = $this->db->query("SELECT NEWID() AS generate_kode");

			$sku_stock_id = $query_sku_stock_id->row(0)->generate_kode;

			$this->db->set("sku_stock_id", $sku_stock_id);
			$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
			$this->db->set("client_wms_id", $client_wms_id);
			$this->db->set("depo_id", $this->session->userdata('depo_id'));
			$this->db->set("depo_detail_id", $depo_detail_id);
			$this->db->set("sku_induk_id", $sku_induk_id);
			$this->db->set("sku_id", $skuId);
			$this->db->set("sku_stock_expired_date", $skuExpDate);
			$this->db->set("sku_stock_batch_no", null);
			$this->db->set("sku_stock_awal", "0");
			$this->db->set("sku_stock_masuk", $value['sisa_jumlah_terima']);
			$this->db->set("sku_stock_alokasi", "0");
			$this->db->set("sku_stock_saldo_alokasi", "0");
			$this->db->set("sku_stock_keluar", "0");
			$this->db->set("sku_stock_akhir", "0");
			$this->db->set("sku_stock_is_jual", "1");
			$this->db->set("sku_stock_is_aktif", "1");
			$this->db->set("sku_stock_is_deleted", "0");

			$this->db->insert("sku_stock");
		}
	}

	public function Insert_PalletDetailTempRescheduled($delivery_order_id, $pallet_id, $client_wms_id, $depo_detail_id)
	{
		$query = $this->db->query("SELECT
									NEWID() AS pallet_detail_id,
									'" . $pallet_id . "' AS pallet_id,
									delivery_order_detail.sku_id,
									delivery_order_detail2.sku_stock_id,
									FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') AS sku_expdate,
									SUM(ISNULL(delivery_order_detail2.sku_qty, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sku_stock_qty,
									'BDCFCBE1-52CF-404F-84B5-A19F0918CA8D' AS penerimaan_tipe_id,
									NULL,
									GETDATE() AS tanggal_create,
									NULL,
									NULL,
									NULL,
									NULL
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT DISTINCT
									delivery_order_detail_id,
									delivery_order_id,
									sku_id,
									sku_stock_id,
									sku_expdate,
									sku_qty
									FROM delivery_order_detail2) delivery_order_detail2
									ON delivery_order_detail2.delivery_order_id = delivery_order.delivery_order_id
									AND delivery_order_detail2.delivery_order_detail_id = delivery_order_detail.delivery_order_detail_id
									AND delivery_order_detail2.sku_id = delivery_order_detail.sku_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									sku_stock_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id,
											sku_stock_id) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									AND penerimaan.sku_stock_id = delivery_order_detail2.sku_stock_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE delivery_order.delivery_order_id = '$delivery_order_id'
									AND ISNULL(delivery_order_detail2.sku_qty, 0) - ISNULL(penerimaan.sku_jumlah_terima, 0) > 0
									GROUP BY delivery_order_detail.sku_id,
											delivery_order_detail2.sku_stock_id,
											FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd')
									ORDER BY FORMAT(delivery_order_detail2.sku_expdate, 'yyyy-MM-dd') ASC");

		foreach ($query->result_array() as $key => $value) {

			$sku_stock_penerimaan = $this->db->query("SELECT * FROM sku_stock WHERE sku_stock_id = '" . $value['sku_stock_id'] . "' ")->row(0);

			$arr_sku_stock = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = '" . $sku_stock_penerimaan->sku_id . "' AND sku_stock_expired_date = '" . $sku_stock_penerimaan->sku_stock_expired_date . "' AND depo_detail_id = '$depo_detail_id' ");

			// $sku_stock_id = "";

			// if ($arr_sku_stock->num_rows() == 0) {
			// 	$query_sku_stock_id = $this->db->query("SELECT NEWID() AS generate_kode");

			// 	$sku_stock_id = $query_sku_stock_id->row(0)->generate_kode;

			// 	$this->db->set("sku_stock_id", $sku_stock_id);
			// 	$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
			// 	$this->db->set("client_wms_id", $client_wms_id);
			// 	$this->db->set("depo_id", $this->session->userdata('depo_id'));
			// 	$this->db->set("depo_detail_id", $depo_detail_id);
			// 	$this->db->set("sku_induk_id", $sku_stock_penerimaan->sku_induk_id);
			// 	$this->db->set("sku_id", $sku_stock_penerimaan->sku_id);
			// 	$this->db->set("sku_stock_expired_date", $sku_stock_penerimaan->sku_stock_expired_date);
			// 	$this->db->set("sku_stock_batch_no", $sku_stock_penerimaan->sku_stock_batch_no);
			// 	$this->db->set("sku_stock_awal", "0");
			// 	$this->db->set("sku_stock_masuk", $value['sku_stock_qty']);
			// 	$this->db->set("sku_stock_alokasi", "0");
			// 	$this->db->set("sku_stock_saldo_alokasi", "0");
			// 	$this->db->set("sku_stock_keluar", "0");
			// 	$this->db->set("sku_stock_akhir", "0");
			// 	$this->db->set("sku_stock_is_jual", "1");
			// 	$this->db->set("sku_stock_is_aktif", "1");
			// 	$this->db->set("sku_stock_is_deleted", "0");

			// 	$this->db->insert("sku_stock");
			// } else {
			// 	$sku_stock_id = $arr_sku_stock->row(0)->sku_stock_id;
			// }

			$this->db->set("pallet_detail_id", $value['pallet_detail_id']);
			$this->db->set("pallet_id", $value['pallet_id']);
			$this->db->set("sku_id", $value['sku_id']);
			// $this->db->set("sku_stock_id", $sku_stock_id);

			if ($arr_sku_stock->num_rows() > 0) {
				$sku_stock_id = $arr_sku_stock->row(0)->sku_stock_id;
				$this->db->set("sku_stock_id", $sku_stock_id);
			}

			$this->db->set("sku_stock_expired_date", $value['sku_expdate']);
			$this->db->set("sku_stock_qty", $value['sku_stock_qty']);
			$this->db->set("penerimaan_tipe_id", $value['penerimaan_tipe_id']);
			// $this->db->set("sku_exp_date", $value['sku_exp_date']);
			$this->db->set("tanggal_create", $value['tanggal_create']);
			// $this->db->set("jumlah_barang", $value['jumlah_barang']);
			// $this->db->set("jumlah_terima", $value['jumlah_terima']);
			// $this->db->set("jumlah_sisa", $value['jumlah_sisa']);
			// $this->db->set("batch_no", $value['batch_no']);

			$this->db->insert("pallet_detail_temp");
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

	public function check_kode_pallet($pallet_kode, $depo_detail_id)
	{
		$query = $this->db->query("SELECT
									pallet.*
									FROM pallet
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = pallet.rak_lajur_detail_id
									LEFT JOIN rak_lajur
									ON rak_lajur.rak_lajur_id = rak_lajur_detail.rak_lajur_id
									LEFT JOIN rak
									ON rak.rak_id = rak_lajur.rak_id
									WHERE pallet.pallet_kode = '$pallet_kode'
									AND rak.depo_detail_id = '$depo_detail_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->pallet_id;
		}

		return $query;
	}

	public function Check_PenerimaanPenjualan($delivery_order_batch_id)
	{
		// $this->db->select("penerimaan_penjualan_kode")
		// 	->from("penerimaan_penjualan")
		// 	->where("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
		// $query = $this->db->get();

		$this->db->select("penerimaan_penjualan_id")
			->from("penerimaan_penjualan")
			->where("delivery_order_batch_id", $delivery_order_batch_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			// $query = $query->row(0)->penerimaan_penjualan_kode;
			$query = $query->row(0)->penerimaan_penjualan_id;
		}

		// return $this->db->last_query();
		return $query;
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

	public function get_pallet_detail_temp($delivery_order_batch_id, $act)
	{
		$isBatch = "";
		if ($act == "ProsesBTBKiriman" || $act == "ProsesBTBRetur") {
			$isBatch = "INNER JOIN pallet_detail_temp";
		} else {
			$isBatch = "LEFT JOIN pallet_detail_temp";
		}
		$query = $this->db->query("SELECT 
										pallet_detail_temp.pallet_detail_id,
										pallet_detail_temp.pallet_id,
										pallet_detail_temp.sku_id,
										pallet_detail_temp.sku_stock_id,
										pallet_detail_temp.sku_stock_expired_date,
										pallet_detail_temp.sku_stock_qty,
										pallet_detail_temp.jumlah_sisa,
										pallet_detail_temp.penerimaan_tipe_id
									FROM pallet_temp
									" . $isBatch . "
									ON pallet_detail_temp.pallet_id = pallet_temp.pallet_id
									WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Check_QtySKUPallet($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
							SUM(ISNULL(pallet_detail_temp.jumlah_sisa, 0)) AS sku_stock_qty
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

	public function Insert_PenerimaanPenjualan($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $delivery_order_batch_id, $depo_detail_id, $karyawan_id, $penerimaan_tipe_id, $penerimaan_tipe_nama, $penerimaan_penjualan_keterangan, $client_wms_id, $principle_id)
	{
		//insert delivery_order_progress
		$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		$this->db->set("depo_id", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("karyawan_id", $karyawan_id);
		// $this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		$this->db->set("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
		$this->db->set("penerimaan_penjualan_tgl", "GETDATE()", FALSE);
		$this->db->set("penerimaan_penjualan_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("penerimaan_penjualan_keterangan", $penerimaan_penjualan_keterangan);
		$this->db->set("penerimaan_penjualan_tgl_create", "GETDATE()", FALSE);
		// $this->db->set("client_wms_id", $client_wms_id);
		// $this->db->set("principle_id", $principle_id);

		$this->db->insert("penerimaan_penjualan");

		// if ($penerimaan_tipe_nama == "retur") {
		// 	$penerimaan_tipe = " AND tipe_delivery_order.tipe_delivery_order_nama='Retur' ";
		// 	$penerimaan_tipe_id = "BDCFCBE1-52CF-404F-84B5-A19F0918CA8D";

		// 	//insert delivery_order_progress
		// 	$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		// 	$this->db->set("depo_id", $this->session->userdata('depo_id'));
		// 	$this->db->set("depo_detail_id", $depo_detail_id);
		// 	$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		// 	$this->db->set("karyawan_id", $karyawan_id);
		// 	$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		// 	$this->db->set("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
		// 	$this->db->set("penerimaan_penjualan_tgl", "GETDATE()", FALSE);
		// 	$this->db->set("penerimaan_penjualan_who_create", $this->session->userdata('pengguna_username'));
		// 	$this->db->set("penerimaan_penjualan_keterangan", $penerimaan_penjualan_keterangan);
		// 	$this->db->set("penerimaan_penjualan_tgl_create", "GETDATE()", FALSE);
		// 	$this->db->set("client_wms_id", $client_wms_id);
		// 	$this->db->set("principle_id", $principle_id);

		// 	$this->db->insert("penerimaan_penjualan");

		// 	$affectedrows = $this->db->affected_rows();
		// 	if ($affectedrows > 0) {
		// 		$query_detail = $this->db->query("INSERT INTO penerimaan_penjualan_detail
		// 											SELECT
		// 											NEWID() AS penerimaan_penjualan_detail_id,
		// 											'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
		// 											delivery_order_detail.delivery_order_id,
		// 											delivery_order_detail.sku_id,
		// 											delivery_order_detail.sku_qty,
		// 											ISNULL(penerimaan.sku_stock_qty,0) AS sku_jumlah_terima
		// 											FROM delivery_order_batch
		// 											LEFT JOIN delivery_order
		// 											ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
		// 											LEFT JOIN delivery_order_detail
		// 											ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
		// 											LEFT JOIN (SELECT
		// 											pallet_temp.delivery_order_batch_id,
		// 											pallet_detail_temp.penerimaan_tipe_id,
		// 											pallet_detail_temp.sku_id,
		// 											SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
		// 											FROM pallet_temp
		// 											LEFT JOIN pallet_detail_temp
		// 											ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
		// 											WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
		// 											AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
		// 											GROUP BY pallet_temp.delivery_order_batch_id,
		// 													pallet_detail_temp.penerimaan_tipe_id,
		// 													pallet_detail_temp.sku_id) penerimaan
		// 											ON penerimaan.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
		// 											AND penerimaan.sku_id = delivery_order_detail.sku_id
		// 											LEFT JOIN tipe_delivery_order
		// 											ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
		// 											WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
		// 											AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) > 0
		// 											AND ISNULL(penerimaan.sku_stock_qty,0) > 0
		// 											" . $penerimaan_tipe . " ");
		// 		$queryinsert = 1;
		// 	} else {
		// 		$queryinsert = 0;
		// 	}
		// } else if ($penerimaan_tipe_nama == "terkirim sebagian") {
		// 	$penerimaan_tipe = " AND delivery_order.delivery_order_status='partially delivered' ";
		// 	$penerimaan_tipe_id = "29F5A94F-C55E-4BA0-B3EE-57A93327735F";

		// 	//insert delivery_order_progress
		// 	$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		// 	$this->db->set("depo_id", $this->session->userdata('depo_id'));
		// 	$this->db->set("depo_detail_id", $depo_detail_id);
		// 	$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		// 	$this->db->set("karyawan_id", $karyawan_id);
		// 	$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		// 	$this->db->set("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
		// 	$this->db->set("penerimaan_penjualan_tgl", "GETDATE()", FALSE);
		// 	$this->db->set("penerimaan_penjualan_who_create", $this->session->userdata('pengguna_username'));
		// 	$this->db->set("penerimaan_penjualan_keterangan", $penerimaan_penjualan_keterangan);
		// 	$this->db->set("penerimaan_penjualan_tgl_create", "GETDATE()", FALSE);
		// 	$this->db->set("client_wms_id", $client_wms_id);
		// 	$this->db->set("principle_id", $principle_id);

		// 	$this->db->insert("penerimaan_penjualan");

		// 	$affectedrows = $this->db->affected_rows();
		// 	if ($affectedrows > 0) {
		// 		$query_detail = $this->db->query("INSERT INTO penerimaan_penjualan_detail
		// 											SELECT
		// 											NEWID() AS penerimaan_penjualan_detail_id,
		// 											'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
		// 											delivery_order_detail.delivery_order_id,
		// 											delivery_order_detail.sku_id,
		// 											delivery_order_detail.sku_qty,
		// 											ISNULL(penerimaan.sku_stock_qty,0) AS sku_jumlah_terima
		// 											FROM delivery_order_batch
		// 											LEFT JOIN delivery_order
		// 											ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
		// 											LEFT JOIN delivery_order_detail
		// 											ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
		// 											LEFT JOIN (SELECT
		// 											pallet_temp.delivery_order_batch_id,
		// 											pallet_detail_temp.penerimaan_tipe_id,
		// 											pallet_detail_temp.sku_id,
		// 											SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
		// 											FROM pallet_temp
		// 											LEFT JOIN pallet_detail_temp
		// 											ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
		// 											WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
		// 											AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
		// 											GROUP BY pallet_temp.delivery_order_batch_id,
		// 													pallet_detail_temp.penerimaan_tipe_id,
		// 													pallet_detail_temp.sku_id) penerimaan
		// 											ON penerimaan.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
		// 											AND penerimaan.sku_id = delivery_order_detail.sku_id
		// 											LEFT JOIN tipe_delivery_order
		// 											ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
		// 											WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
		// 											AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) > 0
		// 											AND ISNULL(penerimaan.sku_stock_qty,0) > 0
		// 											" . $penerimaan_tipe . " ");
		// 		$queryinsert = 1;
		// 	} else {
		// 		$queryinsert = 0;
		// 	}
		// } else if ($penerimaan_tipe_nama == "tidak terkirim") {
		// 	$penerimaan_tipe = " AND delivery_order.delivery_order_status='not delivered' ";
		// 	$penerimaan_tipe_id = "79F2522A-CEA5-4FF8-BC79-C0B28808877D";

		// 	//insert delivery_order_progress
		// 	$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		// 	$this->db->set("depo_id", $this->session->userdata('depo_id'));
		// 	$this->db->set("depo_detail_id", $depo_detail_id);
		// 	$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		// 	$this->db->set("karyawan_id", $karyawan_id);
		// 	$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		// 	$this->db->set("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
		// 	$this->db->set("penerimaan_penjualan_tgl", "GETDATE()", FALSE);
		// 	$this->db->set("penerimaan_penjualan_who_create", $this->session->userdata('pengguna_username'));
		// 	$this->db->set("penerimaan_penjualan_keterangan", $penerimaan_penjualan_keterangan);
		// 	$this->db->set("penerimaan_penjualan_tgl_create", "GETDATE()", FALSE);
		// 	$this->db->set("client_wms_id", $client_wms_id);
		// 	$this->db->set("principle_id", $principle_id);

		// 	$this->db->insert("penerimaan_penjualan");

		// 	$affectedrows = $this->db->affected_rows();
		// 	if ($affectedrows > 0) {
		// 		$query_detail = $this->db->query("INSERT INTO penerimaan_penjualan_detail
		// 											SELECT
		// 											NEWID() AS penerimaan_penjualan_detail_id,
		// 											'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
		// 											delivery_order_detail.delivery_order_id,
		// 											delivery_order_detail.sku_id,
		// 											delivery_order_detail.sku_qty,
		// 											ISNULL(penerimaan.sku_stock_qty,0) AS sku_jumlah_terima
		// 											FROM delivery_order_batch
		// 											LEFT JOIN delivery_order
		// 											ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
		// 											LEFT JOIN delivery_order_detail
		// 											ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
		// 											LEFT JOIN (SELECT
		// 											pallet_temp.delivery_order_batch_id,
		// 											pallet_detail_temp.penerimaan_tipe_id,
		// 											pallet_detail_temp.sku_id,
		// 											SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
		// 											FROM pallet_temp
		// 											LEFT JOIN pallet_detail_temp
		// 											ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
		// 											WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
		// 											AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
		// 											GROUP BY pallet_temp.delivery_order_batch_id,
		// 													pallet_detail_temp.penerimaan_tipe_id,
		// 													pallet_detail_temp.sku_id) penerimaan
		// 											ON penerimaan.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
		// 											AND penerimaan.sku_id = delivery_order_detail.sku_id
		// 											LEFT JOIN tipe_delivery_order
		// 											ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
		// 											WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
		// 											AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) > 0
		// 											AND ISNULL(penerimaan.sku_stock_qty,0) > 0
		// 											" . $penerimaan_tipe . " ");

		// 		$query_do_gagal = $this->db->query("SELECT * FROM delivery_order WHERE delivery_order_batch_id='$delivery_order_batch_id' AND delivery_order_status='not delivered'");
		// 		foreach ($query_do_gagal->result() as $row) {
		// 			$this->db->set("sku_qty", 0);
		// 			$this->db->set("sku_qty_kirim", 0);

		// 			$this->db->where("delivery_order_id", $row->delivery_order_id);

		// 			$this->db->update("delivery_order_detail");
		// 		}

		// 		$queryinsert = 1;
		// 	} else {
		// 		$queryinsert = 0;
		// 	}
		// } else if ($penerimaan_tipe_nama == "titipan") {
		// 	$penerimaan_tipe = "";
		// 	$penerimaan_tipe_id = "A9F3967E-94ED-4761-B385-4770FEEC229A";

		// 	//insert delivery_order_progress
		// 	$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		// 	$this->db->set("depo_id", $this->session->userdata('depo_id'));
		// 	$this->db->set("depo_detail_id", $depo_detail_id);
		// 	$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		// 	$this->db->set("karyawan_id", $karyawan_id);
		// 	$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		// 	$this->db->set("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
		// 	$this->db->set("penerimaan_penjualan_tgl", "GETDATE()", FALSE);
		// 	$this->db->set("penerimaan_penjualan_who_create", $this->session->userdata('pengguna_username'));
		// 	$this->db->set("penerimaan_penjualan_keterangan", $penerimaan_penjualan_keterangan);
		// 	$this->db->set("penerimaan_penjualan_tgl_create", "GETDATE()", FALSE);
		// 	$this->db->set("client_wms_id", $client_wms_id);
		// 	$this->db->set("principle_id", $principle_id);

		// 	$this->db->insert("penerimaan_penjualan");

		// 	$affectedrows = $this->db->affected_rows();
		// 	if ($affectedrows > 0) {
		// 		$query_detail = $this->db->query("INSERT INTO penerimaan_penjualan_detail
		// 							SELECT
		// 								NEWID() AS penerimaan_penjualan_detail_id,
		// 								'" . $penerimaan_penjualan_id . "' AS penerimaan_penjualan_id,
		// 								NULL,
		// 								pallet_temp.sku_id,
		// 								pallet_temp.sku_stock_qty,
		// 								pallet_temp.sku_stock_qty AS sku_jumlah_terima
		// 								FROM delivery_order_batch
		// 								LEFT JOIN (SELECT
		// 									pallet_temp.pallet_id,
		// 									pallet_temp.delivery_order_batch_id,
		// 									pallet_detail_temp.sku_id,
		// 									pallet_detail_temp.penerimaan_tipe_id,
		// 									SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
		// 								FROM pallet_temp
		// 								LEFT JOIN pallet_detail_temp
		// 								ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
		// 								WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id' AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
		// 								GROUP BY pallet_temp.pallet_id,
		// 									pallet_temp.delivery_order_batch_id,
		// 									pallet_detail_temp.sku_id,
		// 									pallet_detail_temp.penerimaan_tipe_id) pallet_temp
		// 								ON pallet_temp.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
		// 								WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'");

		// 		$queryinsert = 1;
		// 	} else {
		// 		$queryinsert = 0;
		// 	}
		// }

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_PenerimaanPenjualanDetail($penerimaan_penjualan_detail_id, $penerimaan_penjualan_id, $delivery_order_id, $penerimaan_tipe_id, $data, $client_wms_id, $depo_detail_id)
	{
		$this->db->set("penerimaan_penjualan_detail_id", $penerimaan_penjualan_detail_id);
		$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		$this->db->set("delivery_order_id", $data['delivery_order_id']);
		$this->db->set("sku_id", $data['sku_id']);
		$this->db->set("sku_jumlah_barang", $data['sku_jumlah_barang']);
		$this->db->set("sku_jumlah_terima", $data['sku_jumlah_terima']);
		$this->db->set("principle_id", $data['principle_id']);
		$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		$this->db->set("sku_stock_id", $data['sku_stock_id']);
		$this->db->set("kondisi_barang", $data['kondisi_barang']);
		$this->db->set("client_wms_id", $data['client_wms_id']);

		$this->db->insert("penerimaan_penjualan_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_PenerimaanPenjualanDetailBatch($penerimaan_penjualan_detail_id, $penerimaan_penjualan_id, $delivery_order_id, $penerimaan_tipe_id, $data, $client_wms_id, $depo_detail_id)
	{
		$this->db->set("penerimaan_penjualan_detail_id", $penerimaan_penjualan_detail_id);
		$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		$this->db->set("delivery_order_id", $data['do_id']);
		$this->db->set("sku_id", $data['sku_id']);
		$this->db->set("sku_jumlah_barang", $data['sku_qty']);
		$this->db->set("sku_jumlah_terima", $data['sku_sisa']);
		$this->db->set("principle_id", $data['principle_id']);
		$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		$this->db->set("sku_stock_id", null);
		$this->db->set("sku_expired_date", $data['exp_date']);
		$this->db->set("kondisi_barang", $data['kondisi']);
		$this->db->set("client_wms_id", $data['client_wms_id']);

		$this->db->insert("penerimaan_penjualan_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_PenerimaanPenjualanDetail2($penerimaan_penjualan_id, $data)
	{
		$this->db->set("penerimaan_penjualan_detail2_id", "NEWID()", FALSE);
		$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		$this->db->set("pallet_id", $data['pallet_id']);

		$this->db->insert("penerimaan_penjualan_detail2");

		$pallet_kode = $this->db->query("select * from pallet_temp where pallet_id = '" . $data['pallet_id'] . "' ")->row(0)->pallet_kode;

		$this->db->set("pallet_generate_detail2_is_aktif", "1");
		$this->db->where("pallet_generate_detail2_kode", $pallet_kode);

		$this->db->update("pallet_generate_detail2");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function update_skuStockID_penerimaan_penjualan_detail($depo_detail_id, $penerimaan_penjualan_id)
	{
		$query = $this->db->query("UPDATE a
											SET a.sku_stock_id = b.sku_stock_id
											FROM penerimaan_penjualan_detail a
										INNER JOIN sku_stock b ON a.sku_id = b.sku_id AND a.sku_expired_date = b.sku_stock_expired_date
											WHERE b.depo_detail_id = '$depo_detail_id'
											AND a.penerimaan_penjualan_id = '$penerimaan_penjualan_id'");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Update_StockSKU($delivery_order_batch_id, $depo_detail_id, $penerimaan_tipe_id, $client_wms_id)
	{
		$sql_check = $this->db->query("SELECT
										pallet_detail_temp.pallet_detail_id,
										pallet_detail_temp.sku_id,
										sku.sku_induk_id,
										pallet_detail_temp.sku_stock_id,
										pallet_detail_temp.sku_stock_expired_date,
										pallet_detail_temp.sku_stock_qty,
										pallet_detail_temp.jumlah_sisa,
										sku.client_wms_id
										FROM pallet_temp
										LEFT JOIN pallet_detail_temp
										ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
										LEFT JOIN sku
										ON sku.sku_id = pallet_detail_temp.sku_id
										WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id' 
										AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
										AND pallet_temp.depo_detail_id = '$depo_detail_id' ");

		if ($sql_check->num_rows() == 0) {
			$queryupdate = 0;
		} else {

			foreach ($sql_check->result() as $row) {
				$query_stock_masuk = $this->db->query("SELECT * FROM sku_stock WHERE sku_id = '$row->sku_id' AND sku_stock_expired_date = '$row->sku_stock_expired_date' AND depo_detail_id = '$depo_detail_id' ");

				if ($query_stock_masuk->num_rows() == 0) {
					$query_sku_stock_id = $this->db->query("SELECT NEWID() AS generate_kode");

					$sku_stock_id = $query_sku_stock_id->row(0)->generate_kode;

					$this->db->set("sku_stock_id", $sku_stock_id);
					$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
					$this->db->set("client_wms_id", $row->client_wms_id);
					$this->db->set("depo_id", $this->session->userdata('depo_id'));
					$this->db->set("depo_detail_id", $depo_detail_id);
					$this->db->set("sku_induk_id", $row->sku_induk_id);
					$this->db->set("sku_id", $row->sku_id);
					$this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
					// $this->db->set("sku_stock_batch_no", $sku_stock_batch_no);
					$this->db->set("sku_stock_awal", "0");
					$this->db->set("sku_stock_masuk", $row->jumlah_sisa);
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
					$sku_stock_masuk = $query_stock_masuk->row(0)->sku_stock_masuk + $row->jumlah_sisa;

					$sku_stock_id = $query_stock_masuk->row(0)->sku_stock_id;

					// $this->db->set("sku_stock_masuk", $sku_stock_masuk);
					// $this->db->where("sku_stock_id", $sku_stock_id);

					// $this->db->update("sku_stock");

					$tipe = "masuk";
					$waktu_delay = "00:00:01";

					$this->db->query("exec insertupdate_sku_stock '$tipe', '$sku_stock_id', NULL, '$row->jumlah_sisa'");

					$this->db->set("sku_stock_id", $sku_stock_id);
					$this->db->where("pallet_detail_id", $row->pallet_detail_id);

					$this->db->update("pallet_detail_temp");
				}
			}

			$queryupdate = 1;
		}

		// return $this->db->last_query();
		return $queryupdate;
	}

	public function CheckPalletById($pallet_id)
	{
		$this->db->select("pallet_id")
			->from("pallet")
			->where("pallet_id", $pallet_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = "0";
		} else {
			$query = $query->row(0)->pallet_id;
		}

		// return $this->db->last_query();
		return $query;
	}

	// public function CheckPalletDetailById($pallet_id, $sku_id, $penerimaan_tipe_id, $sku_stock_expired_date)
	// {
	// 	$this->db->select("pallet_detail_id")
	// 		->from("pallet_detail")
	// 		->where("pallet_id", $pallet_id)
	// 		->where("sku_id", $sku_id)
	// 		->where("penerimaan_tipe_id", $penerimaan_tipe_id)
	// 		->where("sku_stock_expired_date", $sku_stock_expired_date);

	// 	$query = $this->db->get();

	// 	if ($query->num_rows() == 0) {
	// 		$query = 0;
	// 	} else {
	// 		$query = $query->row(0)->pallet_detail_id;
	// 	}

	// 	// return $this->db->last_query();
	// 	return $query;
	// }

	public function CheckPalletDetailById($pallet_id, $sku_stock_id)
	{
		$this->db->select("pallet_detail_id")
			->from("pallet_detail")
			->where("pallet_id", $pallet_id)
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

	public function Insert_Pallet($data)
	{
		$this->db->set("pallet_id", $data['pallet_id']);
		$this->db->set("pallet_jenis_id", $data['pallet_jenis_id']);
		$this->db->set("depo_id", $data['depo_id']);
		$this->db->set("rak_lajur_detail_id", $data['rak_lajur_detail_id']);
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

		// return $queryinsert;
		return $this->db->last_query();
	}

	public function Insert_PalletDetail($data)
	{
		$this->db->set("pallet_detail_id", $data['pallet_detail_id']);
		$this->db->set("pallet_id", $data['pallet_id']);
		$this->db->set("sku_id", $data['sku_id']);
		$this->db->set("sku_stock_id", $data['sku_stock_id']);
		$this->db->set("sku_stock_expired_date", $data['sku_stock_expired_date']);
		$this->db->set("sku_stock_qty", 0);
		// $this->db->set("penerimaan_tipe_id", $data['penerimaan_tipe_id']);
		$this->db->set("sku_stock_terima", $data['jumlah_sisa']);

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

	public function Update_Pallet($data)
	{
		$this->db->set("rak_lajur_detail_id", $data['rak_lajur_detail_id']);
		$this->db->where("pallet_id", $data['pallet_id']);

		$this->db->update("pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $queryinsert;
		return $this->db->last_query();
	}

	public function Update_PalletDetail($pallet_id, $sku_id, $sku_stock_id, $data)
	{
		// $qty_terima = $this->db->query("SELECT ISNULL(sku_stock_terima,0) AS sku_stock_terima FROM pallet_detail WHERE pallet_id = '$pallet_id' AND sku_id = '$sku_id' AND sku_stock_expired_date = '" . $data['sku_stock_expired_date'] . "' ")->row(0)->sku_stock_terima;
		// $qty_terima = $this->db->query("SELECT ISNULL(sku_stock_terima,0) AS sku_stock_terima FROM pallet_detail WHERE pallet_id = '$pallet_id' AND sku_stock_id = '$sku_stock_id' ")->row(0)->sku_stock_terima;
		// $qty_terima = $qty_terima + $data['jumlah_sisa'];

		// $this->db->set("sku_stock_terima", $qty_terima);

		// $this->db->where("pallet_id", $pallet_id);
		// $this->db->where("sku_id", $sku_id);
		// // $this->db->where("penerimaan_tipe_id", $data['penerimaan_tipe_id']);
		// $this->db->where("sku_stock_expired_date", $data['sku_stock_expired_date']);
		// // $this->db->where("sku_stock_id", $sku_stock_id);

		// $this->db->update("pallet_detail");

		$this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_terima', '$pallet_id', '" . $data['sku_stock_id'] . "', '" . $data['jumlah_sisa'] . "'");


		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
		// return $this->db->last_query();
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

	public function delPalletDetailTemp()
	{
		$query = $this->db->query("DELETE FROM pallet_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
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

	public function check_sisa_btb_by_do($delivery_order_id)
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
									WHERE delivery_order.delivery_order_id = '$delivery_order_id'
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

	public function Update_ClosingPengirimanFDJR($delivery_order_batch_id, $delivery_order_batch_status)
	{
		$this->db->set("delivery_order_batch_status", $delivery_order_batch_status);
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

	public function get_penerimaan_penjualan_id($delivery_order_batch_id)
	{
		$this->db->select("penerimaan_penjualan_id")
			->from("penerimaan_penjualan")
			->where("delivery_order_batch_id", $delivery_order_batch_id);

		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->penerimaan_penjualan_id;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function CheckPenerimaanPenjualanDetail($penerimaan_penjualan_id, $delivery_order_id, $sku_id)
	{
		$this->db->select("penerimaan_penjualan_detail_id")
			->from("penerimaan_penjualan_detail")
			->where("penerimaan_penjualan_id", $penerimaan_penjualan_id)
			->where("delivery_order_id", $delivery_order_id)
			->where("sku_id", $sku_id);
		$query = $this->db->get();

		// return $this->db->last_query();
		return $query->num_rows();
	}

	public function get_data_do_by_id($delivery_order_batch_id, $delivery_order_id, $penerimaan_tipe_id, $penerimaan_tipe_nama, $data, $act)
	{
		if ($penerimaan_tipe_nama == "retur") {
			$penerimaan_tipe_nama = " AND ((delivery_order.delivery_order_status = 'retur' AND delivery_order.delivery_order_kode LIKE '%/DOR/%') OR (delivery_order.delivery_order_status = 'delivered' AND delivery_order.tipe_delivery_order_id = 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD'))";
			$penerimaan_tipe_id = "BDCFCBE1-52CF-404F-84B5-A19F0918CA8D";
		} else if ($penerimaan_tipe_nama == "terkirim sebagian") {
			$penerimaan_tipe_nama = " AND delivery_order.delivery_order_status = 'partially delivered'";
			$penerimaan_tipe_id = "29F5A94F-C55E-4BA0-B3EE-57A93327735F";
		} else if ($penerimaan_tipe_nama == "tidak terkirim") {
			$penerimaan_tipe_nama = " AND delivery_order.delivery_order_status = 'not delivered'";
			$penerimaan_tipe_id = "79F2522A-CEA5-4FF8-BC79-C0B28808877D";
		} else if ($penerimaan_tipe_nama == "rescheduled") {
			$penerimaan_tipe_nama = " AND delivery_order.delivery_order_status = 'rescheduled'";
			$penerimaan_tipe_id = "BDCFCBE1-52CF-404F-84B5-A19F0918CA8D";
		}

		$counter = 0;
		$union = " UNION ALL ";
		$table_sementara = "";
		foreach ($data as $key => $value) {
			$table_sementara .= "SELECT '" . $value['delivery_order_id'] . "' AS delivery_order_id, '" . $value['kondisiBarang'] . "' AS kondisi_barang, '" . $value['sku_id'] . "' AS sku_id";

			$counter++;

			if ($key < count($data) - 1) {
				$table_sementara .= $union;
			}
		}

		$isBatch = "";
		if ($act == 'ProsesBTBKiriman' || $act == 'ProsesBTBRetur') {
			$isBatch = "AND delivery_order.delivery_order_id IN ($delivery_order_id)";
		} else {
			$isBatch = "AND delivery_order.delivery_order_id = '$delivery_order_id'";
		}

		$query = $this->db->query("SELECT
									delivery_order.client_wms_id,
									delivery_order_detail.delivery_order_id,
									delivery_order_detail.sku_id,
									sku.sku_nama_produk,
									sku.principle_id,
									sku.sku_kemasan,
									sku.sku_satuan,
									kondisi.kondisi_barang,
									SUM(ABS(delivery_order_detail2.sku_qty)) AS sku_jumlah_barang,
									SUM(ISNULL(ABS(delivery_order_detail.sku_qty), 0)) - SUM(ISNULL(delivery_order_detail.sku_qty_kirim, 0)) - SUM(ISNULL(penerimaan.sku_jumlah_terima, 0)) AS sku_jumlah_terima,
									-- ISNULL(penerimaan.sku_stock_qty, 0) AS sku_jumlah_terima,
									penerimaan.sku_stock_id,
									penerimaan.sku_stock_expired_date,
									penerimaan.depo_detail_id
									FROM delivery_order
									LEFT JOIN delivery_order_detail
										ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN delivery_order_detail2
										ON delivery_order_detail2.delivery_order_detail_id = delivery_order_detail.delivery_order_detail_id
									LEFT JOIN (SELECT
													pallet_temp.delivery_order_batch_id,
													pallet_temp.depo_detail_id,
													pallet_detail_temp.penerimaan_tipe_id,
													SUM(pallet_detail_temp.jumlah_sisa) AS sku_jumlah_terima,
													pallet_detail_temp.sku_id,
													pallet_detail_temp.sku_stock_id,
													pallet_detail_temp.sku_stock_expired_date,
													SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
												FROM pallet_temp
												LEFT JOIN pallet_detail_temp
													ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
												WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
												AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
												GROUP BY pallet_temp.delivery_order_batch_id,
														pallet_temp.depo_detail_id,
														pallet_detail_temp.penerimaan_tipe_id,
														pallet_detail_temp.sku_id,
														pallet_detail_temp.sku_stock_expired_date,
														pallet_detail_temp.sku_stock_id) penerimaan
										ON penerimaan.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									AND penerimaan.sku_id = delivery_order_detail2.sku_id
									AND penerimaan.sku_stock_expired_date = delivery_order_detail2.sku_expdate
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN sku
										ON sku.sku_id = delivery_order_detail.sku_id
									LEFT JOIN (" . $table_sementara . ") kondisi
										ON kondisi.delivery_order_id = delivery_order.delivery_order_id
									AND kondisi.sku_id = delivery_order_detail2.sku_id
									WHERE delivery_order.delivery_order_batch_id= '$delivery_order_batch_id'
									" . $isBatch . "
									AND ISNULL(penerimaan.sku_stock_qty, 0) > 0
									" . $penerimaan_tipe_nama . "
									GROUP BY delivery_order_detail.delivery_order_id,
										delivery_order_detail.sku_id,
										delivery_order.client_wms_id,
										sku.sku_nama_produk,
										sku.principle_id,
										sku.sku_kemasan,
										sku.sku_satuan,
										kondisi.kondisi_barang,
										ISNULL(penerimaan.sku_stock_qty, 0),
										ISNULL(delivery_order_detail.sku_qty_kirim, 0),
										penerimaan.sku_stock_id,
										penerimaan.sku_stock_expired_date,
										penerimaan.depo_detail_id
										HAVING SUM(ABS(delivery_order_detail2.sku_qty)) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) > 0
									ORDER BY sku_id ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function get_data_pallet_by_fdjr($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT * FROM pallet_temp WHERE delivery_order_batch_id = '$delivery_order_batch_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function update_stock_delivery_order_detail($delivery_order_id, $sku_qty, $sku_qty_kirim, $data, $act, $status_do)
	{
		// $this->db->set("sku_qty", $sku_qty);
		$cek_query = $this->db->query("SELECT sku_qty FROM delivery_order_detail WHERE delivery_order_id = '$delivery_order_id'");

		foreach ($cek_query->result_array() as $row) {
			if ($status_do['delivery_order_status'] == 'partially delivered') {
				$sku_qty = $row['sku_qty'] - $data['sku_sisa'];
			} else {
				$sku_qty = 0;
			}
		}

		$this->db->set("sku_qty_kirim", $sku_qty);

		if ($act == 'ProsesBTBKiriman' || $act == 'ProsesBTBRetur') {
			$this->db->where("delivery_order_id", $data['do_id']);
		} else {
			$this->db->where("delivery_order_id", $delivery_order_id);
		}

		$this->db->update("delivery_order_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
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
									AND delivery_order.delivery_order_kode LIKE '%/DOR/%'
									AND delivery_order.delivery_order_status = 'delivered'
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

	public function get_sku_stock_by_pallet($delivery_order_batch_id, $penerimaan_tipe_id)
	{

		$query = $this->db->query("SELECT
									pallet_detail_temp.sku_id,
									pallet_detail_temp.sku_stock_id,
									SUM(pallet_detail_temp.jumlah_sisa) AS sku_stock_qty
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
									WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
									-- AND pallet_detail_temp.penerimaan_tipe_id = '$penerimaan_tipe_id'
									GROUP BY pallet_detail_temp.sku_id,
											pallet_detail_temp.sku_stock_id");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Insert_sku_stock_card($sku_stock_card_dokumen_id, $sku_stock_card_dokumen_no, $sku_stock_card_keterangan, $sku_stock_card_jenis, $depo_id, $depo_detail_id, $sku_id, $sku_stock_id, $sku_stock_card_qty, $sku_stock_card_is_posting)
	{
		$sku_stock_card_dokumen_id = $sku_stock_card_dokumen_id == '' ? null : $sku_stock_card_dokumen_id;
		$sku_stock_card_dokumen_no = $sku_stock_card_dokumen_no == '' ? null : $sku_stock_card_dokumen_no;
		$sku_stock_card_keterangan = $sku_stock_card_keterangan == '' ? null : $sku_stock_card_keterangan;
		$sku_stock_card_jenis = $sku_stock_card_jenis == '' ? null : $sku_stock_card_jenis;
		$depo_id = $depo_id == '' ? null : $depo_id;
		$depo_detail_id = $depo_detail_id == '' ? null : $depo_detail_id;
		$sku_id = $sku_id == '' ? null : $sku_id;
		$sku_stock_id = $sku_stock_id == '' ? null : $sku_stock_id;
		$sku_stock_card_qty = $sku_stock_card_qty == '' ? null : $sku_stock_card_qty;
		$sku_stock_card_is_posting = $sku_stock_card_is_posting == '' ? null : $sku_stock_card_is_posting;

		$this->db->set("sku_stock_card_id", "NEWID()", FALSE);
		$this->db->set("sku_stock_card_tanggal", "GETDATE()", FALSE);
		$this->db->set("sku_stock_card_bulan", "MONTH(GETDATE())", FALSE);
		$this->db->set("sku_stock_card_tahun", "YEAR(GETDATE())", FALSE);
		$this->db->set("sku_stock_card_dokumen_id", $sku_stock_card_dokumen_id);
		$this->db->set("sku_stock_card_dokumen_no", $sku_stock_card_dokumen_no);
		$this->db->set("sku_stock_card_keterangan", $sku_stock_card_keterangan);
		// $this->db->set("tipe_mutasi_id", $tipe_mutasi_id);
		$this->db->set("sku_stock_card_jenis", $sku_stock_card_jenis);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_stock_card_qty", $sku_stock_card_qty);
		$this->db->set("sku_stock_card_is_posting", $sku_stock_card_is_posting);
		$this->db->set("sku_stock_card_tgl_create", "GETDATE()", FALSE);
		$this->db->set("sku_stock_card_who_create", $this->session->userdata('pengguna_username'));
		// $this->db->set("sku_stock_card_tgl_posting", $sku_stock_card_tgl_posting);
		// $this->db->set("sku_stock_card_who_posting", $sku_stock_card_who_posting);

		$this->db->insert("sku_stock_card");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function exec_insert_sku_stock_card($penerimaan_penjualan_id, $who)
	{
		$query = $this->db->query("exec proses_posting_stock_card 'BTBOUTLETBULK', '$penerimaan_penjualan_id', '$who'");

		if ($query) {
			return 1;
		} else {
			return 0;
		}
	}

	public function GetRakLajurDetail($depo_detail_id)
	{
		$query = $this->db->query("SELECT
									rak_lajur_detail.rak_lajur_detail_id,
									rak_lajur_detail.rak_lajur_detail_nama
									FROM rak
									LEFT JOIN rak_lajur
									ON rak_lajur.rak_id = rak.rak_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									WHERE rak.depo_id = '" . $this->session->userdata('depo_id') . "' AND rak.depo_detail_id = '$depo_detail_id'
									ORDER BY rak_lajur_detail.rak_lajur_detail_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function check_rak_lajur_detail($depo_detail_id, $kode)
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
									WHERE rak.depo_id = '" . $this->session->userdata('depo_id') . "' AND rak.depo_detail_id = '$depo_detail_id' 
									AND rak_lajur_detail.rak_lajur_detail_nama = '$kode'")->row();
	}

	public function update_pallet_rak_lajur_detail($pallet_id, $rak_lajur_detail_id)
	{
		$this->db->set("rak_lajur_detail_id", $rak_lajur_detail_id);
		$this->db->where("pallet_id", $pallet_id);
		return $this->db->update("pallet_temp");

		// return $this->db->last_query();
	}

	public function Insert_rak_lajur_detail_pallet($data)
	{
		//insert delivery_order_progress
		$this->db->set("rak_lajur_detail_pallet_id", "NEWID()", FALSE);
		$this->db->set("rak_lajur_detail_id", $data['rak_lajur_detail_id']);
		$this->db->set("pallet_id", $data['pallet_id']);

		$this->db->insert("rak_lajur_detail_pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Update_rak_lajur_detail_pallet($data)
	{
		//insert delivery_order_progress
		$this->db->set("rak_lajur_detail_id", $data['rak_lajur_detail_id']);
		$this->db->where("pallet_id", $data['pallet_id']);

		$this->db->update("rak_lajur_detail_pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_rak_lajur_detail_pallet_his($depo_detail_id, $data)
	{
		//insert delivery_order_progress
		$this->db->set("rak_lajur_detail_pallet_id", "NEWID()", FALSE);
		$this->db->set("depo_id", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("rak_lajur_detail_id", $data['rak_lajur_detail_id']);
		$this->db->set("pallet_id", $data['pallet_id']);
		$this->db->set("tanggal_create", "GETDATE()", FALSE);
		$this->db->set("who_create", $this->session->userdata('pengguna_username'));

		$this->db->insert("rak_lajur_detail_pallet_his");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Get_pallet_jenis()
	{
		$this->db->select("*")
			->from("pallet_jenis")
			->where("pallet_jenis_id", "86C84F5F-D967-4DA6-8B6E-3F81679232F3");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = "0";
		} else {
			$query = $query->row(0)->pallet_jenis_kode;
		}

		// return $this->db->last_query();
		return $query;
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
			// return $this->db->select("rak_lajur_detail_nama as kode")
			// 	->from("rak_lajur_detail")
			// 	->where("depo_id", $this->session->userdata('depo_id'))
			// 	->like("rak_lajur_detail_nama", $value)->get()->result();

			return $this->db->query("select rak_lajur_detail_nama as kode from rak_lajur_detail where rak_lajur_id in (select rak_lajur_id from rak_lajur inner join rak on rak.rak_id = rak_lajur.rak_id where depo_id = '" . $this->session->userdata('depo_id') . "') AND rak_lajur_detail_nama LIKE '%$value%' ORDER BY rak_lajur_detail_nama ASC")->result();
		}
	}

	public function UpdatePalletKodeTemByIdTemp($pallet_id, $pallet_kode)
	{
		$this->db->set("pallet_kode", $pallet_kode);
		$this->db->where("pallet_id", $pallet_id);

		$this->db->update("pallet_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
		// return $this->db->last_query();
	}

	public function GetPalletGenerator($depoPrefix)
	{
		$query = $this->db->query("select pallet_generate_detail2_id as pallet_id, pallet_generate_detail2_kode as pallet_kode from pallet_generate_detail2 where REVERSE(PARSENAME(REPLACE(REVERSE(pallet_generate_detail2_kode), '/', '.'), 1)) = '$depoPrefix' AND pallet_generate_detail2_is_aktif = '0'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function cek_pallet_penerimaan($penerimaan_penjualan_id, $pallet_id)
	{
		$this->db->select("*")
			->from("penerimaan_penjualan_detail2")
			->where("penerimaan_penjualan_id", $penerimaan_penjualan_id)
			->where("pallet_id", $pallet_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function cekStatusDOByDOID($do_id)
	{
		$this->db->select("delivery_order_status")
			->from("delivery_order")
			->where("delivery_order_id", $do_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}
	}
}
