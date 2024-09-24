<?php

class M_LaporanStock extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
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

	public function exec_report_stock($tipe_report, $tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{
		$tgl2 = date('m/d/Y', strtotime($tgl2 . "+1 days"));

		if ($depo_detail_id == "") {
			$depo_detail_id = "NULL";
		} else {
			$depo_detail_id = "'" . $depo_detail_id . "'";
		}

		if ($principle_id == "") {
			$principle_id = "NULL";
		} else {
			$principle_id = "'" . $principle_id . "'";
		}

		if ($sku_kode == "") {
			$sku_kode = "NULL";
		} else {
			$sku_kode = "'" . $sku_kode . "'";
		}

		if ($sku_nama == "") {
			$sku_nama = "NULL";
		} else {
			$sku_nama = "'" . $sku_nama . "'";
		}

		// $query = $this->db->query("exec report_stock 'rekap','07/21/2022','07/22/2022',NULL,'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26',NULL,NULL,NULL,NULL");
		$query = $this->db->query("exec report_stock '$tipe_report','$tgl1','$tgl2',NULL,'$depo_id',$depo_detail_id,$principle_id,$sku_kode,$sku_nama");
		// $query = $this->db->query("exec report_stock '$tipe_report','$tgl1','$tgl2','$client_wms','$depo_id',$depo_detail_id,$principle_id,$sku_kode,$sku_nama");

		return $query->result_array();
		// return $this->db->last_query();
	}

	public function exec_report_stock_movement($tipe_report, $tgl1, $tgl2, $tipe_transaksi, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{
		$tgl2 = date('m/d/Y', strtotime($tgl2 . "+1 days"));

		if ($depo_detail_id == "" || $depo_detail_id == "null") {
			$depo_detail_id = "NULL";
		} else {
			$depo_detail_id = "'" . $depo_detail_id . "'";
		}

		if ($principle_id == "" || $principle_id == "null") {
			$principle_id = "NULL";
		} else {
			$principle_id = "'" . $principle_id . "'";
		}

		if ($sku_kode == "") {
			$sku_kode = "NULL";
		} else {
			$sku_kode = "'" . $sku_kode . "'";
		}

		if ($sku_nama == "") {
			$sku_nama = "NULL";
		} else {
			$sku_nama = "'" . $sku_nama . "'";
		}

		$query = $this->db->query("exec report_stock_movement '$tipe_report','$tgl1','$tgl2',NULL,NULL,'$depo_id',$depo_detail_id,$principle_id,$sku_kode,$sku_nama");

		return $query->result_array();
		// return $this->db->last_query();
	}
}
