<?php

class M_KomparasiLokasiStockPalletGlobal extends CI_Model
{
	public function GetDepoDetail()
	{
		$this->db->select("*")
			->from("depo_detail")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("depo_detail_flag_jual", 1)
			->order_by("depo_detail_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPrinnciple()
	{
		$this->db->select("*")
			->from("principle")
			->where("principle_is_aktif", 1)
			->order_by("principle_kode");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetStatusProgress()
	{
		$this->db->select("*")
			->from("status_progress")
			->where("status_progress_modul", "Delivery Order")
			->order_by("status_progress_no");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetTipeDeliveryOrder()
	{
		$this->db->select("*")
			->from("tipe_delivery_order")
			->where("tipe_delivery_order_alias <>", "Mix")
			->order_by("tipe_delivery_order_alias");
		$query = $this->db->get();

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

	public function GetPerusahaan()
	{
		$this->db->select("*")
			->from("client_wms")
			->order_by("client_wms_nama");
		$query = $this->db->get();

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

	public function Get_list_komparasi_lokasi_stock_pallet($depo_detail_id, $pallet_kode)
	{
		if ($depo_detail_id == "") {
			$depo_detail_id = "";
		} else {
			$depo_detail_id = "AND CONVERT(NVARCHAR(36),d.depo_detail_id) = '" . $depo_detail_id . "' ";
		}

		if ($pallet_kode == "") {
			$pallet_kode = "";
		} else {
			$pallet_kode = "AND b.pallet_kode LIKE '%" . $pallet_kode . "%' ";
		}

		$query = $this->db->query("SELECT
									a.pallet_id,
									b.pallet_kode,
									c.rak_lajur_detail_id,
									c.rak_lajur_detail_nama,
									d.depo_detail_id AS iddepodetailpallet,
									e.depo_detail_id AS iddepodetailsummary,
									g.depo_detail_nama AS depodetailpallet,
									h.depo_detail_nama AS depodetailsummary,
									CASE
										WHEN d.depo_detail_id <> e.depo_detail_id THEN 'Beda'
										ELSE 'Sama'
									END AS flag
									FROM pallet_detail a
									INNER JOIN pallet b
									ON a.pallet_id = b.pallet_id
									INNER JOIN rak_lajur_detail c
									ON b.rak_lajur_detail_id = b.rak_lajur_detail_id
									AND c.rak_lajur_detail_id = b.rak_lajur_detail_id
									INNER JOIN rak d
									ON c.rak_id = d.rak_id
									LEFT JOIN (SELECT
									sku_stock_id,
									depo_detail_id
									FROM sku_stock
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "') e
									ON a.sku_stock_id = e.sku_stock_id
									INNER JOIN sku f
									ON a.sku_id = f.sku_id
									INNER JOIN depo_detail g
									ON d.depo_detail_id = g.depo_detail_id
									INNER JOIN depo_detail h
									ON e.depo_detail_id = h.depo_detail_id
									INNER JOIN principle i
									ON f.principle_id = i.principle_id
									WHERE d.depo_detail_id <> e.depo_detail_id
									AND b.depo_id = '" . $this->session->userdata('depo_id') . "'
									" . $pallet_kode . "
									GROUP BY a.pallet_id,
											b.pallet_kode,
											c.rak_lajur_detail_id,
											c.rak_lajur_detail_nama,
											d.depo_detail_id,
											e.depo_detail_id,
											g.depo_detail_nama,
											h.depo_detail_nama");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_komparasi_lokasi_stock_pallet_by_id($pallet_id)
	{

		$query = $this->db->query("SELECT
									a.pallet_id,
									b.pallet_kode,
									c.rak_lajur_detail_id,
									c.rak_lajur_detail_nama,
									d.depo_detail_id AS iddepodetailpallet,
									e.depo_detail_id AS iddepodetailsummary,
									g.depo_detail_nama AS depodetailpallet,
									h.depo_detail_nama AS depodetailsummary,
									CASE
										WHEN d.depo_detail_id <> e.depo_detail_id THEN 'Beda'
										ELSE 'Sama'
									END AS flag
									FROM pallet_detail a
									INNER JOIN pallet b
									ON a.pallet_id = b.pallet_id
									INNER JOIN rak_lajur_detail c
									ON b.rak_lajur_detail_id = b.rak_lajur_detail_id
									AND c.rak_lajur_detail_id = b.rak_lajur_detail_id
									INNER JOIN rak d
									ON c.rak_id = d.rak_id
									LEFT JOIN (SELECT
									sku_stock_id,
									depo_detail_id
									FROM sku_stock
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "') e
									ON a.sku_stock_id = e.sku_stock_id
									INNER JOIN sku f
									ON a.sku_id = f.sku_id
									INNER JOIN depo_detail g
									ON d.depo_detail_id = g.depo_detail_id
									INNER JOIN depo_detail h
									ON e.depo_detail_id = h.depo_detail_id
									INNER JOIN principle i
									ON f.principle_id = i.principle_id
									WHERE CONVERT(NVARCHAR(36),a.pallet_id) = '$pallet_id'
									GROUP BY a.pallet_id,
											b.pallet_kode,
											c.rak_lajur_detail_id,
											c.rak_lajur_detail_nama,
											d.depo_detail_id,
											e.depo_detail_id,
											g.depo_detail_nama,
											h.depo_detail_nama");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_rak_lajur_detail_by_depo_detail_id($depo_detail_id)
	{

		$query = $this->db->query("SELECT
									a.rak_lajur_detail_id,
									a.rak_lajur_detail_nama
									FROM rak_lajur_detail a
									LEFT JOIN rak b
									ON b.rak_id = a.rak_id
									WHERE CONVERT(nvarchar(36), b.depo_detail_id) = '$depo_detail_id'
									ORDER BY a.rak_lajur_detail_nama ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function update_pallet($pallet_id, $rak_lajur_detail_id)
	{
		$rak_lajur_detail_id = $rak_lajur_detail_id == "" ? null : $rak_lajur_detail_id;

		$this->db->set("rak_lajur_detail_id", $rak_lajur_detail_id);

		$this->db->where("pallet_id", $pallet_id);

		$queryinsert = $this->db->update("pallet");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function update_rak_lajur_detail_pallet($pallet_id, $rak_lajur_detail_id)
	{
		$rak_lajur_detail_id = $rak_lajur_detail_id == "" ? null : $rak_lajur_detail_id;

		$this->db->set("rak_lajur_detail_id", $rak_lajur_detail_id);

		$this->db->where("pallet_id", $pallet_id);

		$queryinsert = $this->db->update("rak_lajur_detail_pallet");

		return $queryinsert;
		// return $this->db->last_query();
	}
}
