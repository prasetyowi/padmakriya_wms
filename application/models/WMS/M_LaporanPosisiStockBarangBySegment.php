<?php

class M_LaporanPosisiStockBarangBySegment extends CI_Model
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
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Gudang()
	{
		$query = $this->db->query("SELECT
										depo_detail_id, depo_detail_nama, depo_detail_flag_jual AS jual, depo_detail_is_flashout AS flashout,
										depo_detail_is_kirimulang AS kirimulang, depo_detail_is_qa AS qa, depo_detail_is_bonus AS bonus, depo_detail_is_bs AS bs
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY depo_detail_nama ASC");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_SKU_Stock_Segment_By_Segment()
	{
		$query = $this->db->query("SELECT distinct b.client_pt_segmen_id, b.client_pt_segmen_nama FROM sku_stock_segment a
									LEFT JOIN client_pt_segmen b ON a.client_pt_segmen_id = b.client_pt_segmen_id 
									WHERE a.client_pt_segmen_id IS NOT NULL AND depo_id = '" . $this->session->userdata('depo_id') . "'");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function count_all_data($start = null, $length = null, $search_value = null, $order_column = null, $order_dir = null, $depo_id, $principle_id, $client_pt_segmen_id)
	{
		if ($start == null) {
			$start = "null";
		}
		if ($length == null) {
			$length = "null";
		}
		if ($order_column == null) {
			$order_column = "null";
		}
		if ($order_dir == null) {
			$order_dir = "'ASC'";
		}
		// $start = $start == '' ? 0 : $start;
		// $length = $length == '' ? 0 : $length;
		// $order_column = $order_column == '' ? 0 : $order_column;
		// $order_dir = $order_dir == '' ? NULL : $order_dir;

		if ($principle_id == "") {
			$principle_id = "NULL";
		} else {
			$principle_id = "'" . $principle_id . "'";
		}

		if ($client_pt_segmen_id == "") {
			$client_pt_segmen_id = "NULL";
		} else {
			$client_pt_segmen_id = "'" . $client_pt_segmen_id . "'";
		}

		$query = $this->db->query("exec report_stock_composite_global_segment $start,$length,$order_column,$order_dir, '$depo_id', $principle_id, $client_pt_segmen_id");
		// Ambil hasil query
		$results = $query->result();

		// Hitung total data
		$totalRows = count($results);

		return $totalRows;
	}

	public function exec_report_posisi_stock_barang($start = null, $length = null, $search_value = null, $order_column = null, $order_dir = null, $depo_id, $principle_id, $client_pt_segmen_id)
	{
		// var_dump([$start, $length]);
		if ($start == null) {
			$start = "null";
		}
		if ($length == null) {
			$length = "null";
		}
		if ($order_column == null) {
			$order_column = "null";
		}
		if ($order_dir == null) {
			$order_dir = "'ASC'";
		}
		// $start = $start == '' ? 0 : $start;
		// $length = $length == '' ? 0 : $length;
		// $order_column = $order_column == '' ? 0 : $order_column;
		// $order_dir = $order_dir == '' ? NULL : $order_dir;

		if ($principle_id == "") {
			$principle_id = "NULL";
		} else {
			$principle_id = "'" . $principle_id . "'";
		}

		if ($client_pt_segmen_id == "") {
			$client_pt_segmen_id = "NULL";
		} else {
			$client_pt_segmen_id = "'" . $client_pt_segmen_id . "'";
		}

		$query = $this->db->query("exec report_stock_composite_global_segment $start,$length,$order_column,$order_dir, '$depo_id', $principle_id, $client_pt_segmen_id");

		return $query->result_array();
	}
}
