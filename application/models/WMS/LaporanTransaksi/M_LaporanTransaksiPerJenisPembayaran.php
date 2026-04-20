<?php

class M_LaporanTransaksiPerJenisPembayaran extends CI_Model
{
	function __construct()
	{
		parent::__construct();
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

	public function Get_ClientWms()
	{
		$query = $this->db->query("SELECT
										*
									FROM client_wms
									-- WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY client_wms_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Driver()
	{
		$query = $this->db->query("SELECT
										*
									FROM karyawan
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									AND karyawan_divisi_id = '351F2A0C-13F4-4780-A526-428BEE343200'
									ORDER BY karyawan_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDetailLaporanTransaksiPerJenisPembayaran($tgl1, $tgl2, $client_wms_id, $depo_id, $principle_id, $driver)
	{
		if ($client_wms_id == "") {
			$client_wms_id = "";
		} else {
			$client_wms_id = "AND do.client_wms_id = '$client_wms_id' ";
		}
		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "and p.principle_id = '$principle_id' ";
		}

		if ($driver == "") {
			$driver = "";
		} else {
			$driver = " AND k.karyawan_id = '" . $driver . "' ";
		}

		$depo_id = $this->session->userdata("depo_id");

		$query =  $this->db->query("SELECT ISNULL(k.karyawan_nama, 'UNKNOWN') karyawan_nama,
										ISNULL(p.principle_kode, 'UNKNOWN') principle_kode,
										SUM(ISNULL(do.delivery_order_nominal_tunai, 0)) AS delivery_order_nominal_tunai,
										SUM(ISNULL(payment.delivery_order_jumlah_bayar, 0)) AS delivery_order_jumlah_bayar
									FROM delivery_order_batch fdjr
									LEFT JOIN delivery_order do ON fdjr.delivery_order_batch_id = do.delivery_order_batch_id
									LEFT JOIN (select
										a.delivery_order_id,
										sku.principle_id
									from delivery_order_detail a
									left join sku
									on sku.sku_id = a.sku_id
									group by a.delivery_order_id,
										sku.principle_id) dod ON dod.delivery_order_id = do.delivery_order_id
									LEFT JOIN (select a.delivery_order_id, SUM(ISNULL(a.delivery_order_jumlah_bayar, 0)) delivery_order_jumlah_bayar from delivery_order_payment_driver a group by a.delivery_order_id) payment ON payment.delivery_order_id = do.delivery_order_id
									LEFT JOIN principle p ON p.principle_id = dod.principle_id
									LEFT JOIN karyawan k ON k.karyawan_id = fdjr.karyawan_id
									WHERE FORMAT(fdjr.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2' 
									AND fdjr.depo_id = '$depo_id' 
									AND p.principle_id IS NOT NULL 
									AND k.karyawan_id IS NOT NULL
									" . $principle_id . "
									" . $driver . "
									GROUP BY ISNULL(k.karyawan_nama, 'UNKNOWN'),
											ISNULL(p.principle_kode, 'UNKNOWN') WITH ROLLUP
									ORDER BY GROUPING(ISNULL(k.karyawan_nama, 'UNKNOWN')),
											ISNULL(k.karyawan_nama, 'UNKNOWN'),
											GROUPING(ISNULL(p.principle_kode, 'UNKNOWN')),
											ISNULL(p.principle_kode, 'UNKNOWN')");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDetailLaporanTransaksiPerJenisPembayaranDetail($tgl1, $tgl2, $client_wms_id, $depo_id, $principle, $driver)
	{
		if ($client_wms_id == "" || $client_wms_id == "null") {
			$client_wms_id = "";
		} else {
			$client_wms_id = "AND do.client_wms_id = '$client_wms_id' ";
		}
		if ($principle == "" || $principle == "null") {
			$principle = "";
		} else {
			$principle = "and p.principle_kode = '$principle' ";
		}

		if ($driver == "" || $driver == "null") {
			$driver = "";
		} else {
			$driver = " AND k.karyawan_nama = '" . $driver . "' ";
		}

		$depo_id = $this->session->userdata("depo_id");

		$query =  $this->db->query("SELECT
										do.delivery_order_id,
										do.delivery_order_kode,
										FORMAT(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy') as delivery_order_tgl_buat_do,
										FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') as delivery_order_tgl_rencana_kirim,
										FORMAT(do.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy') as delivery_order_tgl_aktual_kirim,
										FORMAT(do.delivery_order_tgl_expired_do, 'dd-MM-yyyy') as delivery_order_tgl_expired_do,
										fdjr.karyawan_id,
										k.karyawan_nama,
										do.client_pt_id,
										do.delivery_order_kirim_nama,
										do.delivery_order_kirim_alamat,
										do.delivery_order_kirim_kelurahan,
										do.delivery_order_kirim_kecamatan,
										do.delivery_order_kirim_kota,
										do.delivery_order_ambil_provinsi,
										p.principle_id,
										p.principle_kode,
										ISNULL(do.delivery_order_nominal_tunai, 0) as delivery_order_nominal_tunai,
										tp.tipe_pembayaran_id,
										tp.tipe_pembayaran_nama,
										SUM(ISNULL(payment.delivery_order_jumlah_bayar, 0)) AS delivery_order_jumlah_bayar
									FROM delivery_order do
									LEFT JOIN delivery_order_detail dod ON dod.delivery_order_id = do.delivery_order_id
									LEFT JOIN delivery_order_payment_driver payment ON payment.delivery_order_id = do.delivery_order_id
									LEFT JOIN delivery_order_batch fdjr ON fdjr.delivery_order_batch_id = do.delivery_order_batch_id
									LEFT JOIN sku ON sku.sku_id = dod.sku_id
									LEFT JOIN principle p ON p.principle_id = sku.principle_id
									LEFT JOIN karyawan k ON k.karyawan_id = fdjr.karyawan_id
									LEFT JOIN tipe_pembayaran tp ON tp.tipe_pembayaran_id = payment.tipe_pembayaran_id
									WHERE FORMAT(fdjr.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2' 
									AND fdjr.depo_id = '$depo_id'
									" . $principle . "
									" . $driver . "
									GROUP BY do.delivery_order_id,
										do.delivery_order_kode,
										FORMAT(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy'),
										FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy'),
										FORMAT(do.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy'),
										FORMAT(do.delivery_order_tgl_expired_do, 'dd-MM-yyyy'),
										fdjr.karyawan_id,
										k.karyawan_nama,
										do.client_pt_id,
										do.delivery_order_kirim_nama,
										do.delivery_order_kirim_alamat,
										do.delivery_order_kirim_kelurahan,
										do.delivery_order_kirim_kecamatan,
										do.delivery_order_kirim_kota,
										do.delivery_order_ambil_provinsi,
										p.principle_id,
										p.principle_kode,
										ISNULL(do.delivery_order_nominal_tunai, 0),
										tp.tipe_pembayaran_id,
										tp.tipe_pembayaran_nama
									ORDER BY k.karyawan_nama, do.delivery_order_kode, tp.tipe_pembayaran_id ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
}
