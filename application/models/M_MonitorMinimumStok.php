<?php

class M_MonitorMinimumStok extends CI_Model
{
	//tes user baru 5
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getSKUGroup($sku_id)
	{
		$this->db->select("*")
			->from("sku")
			->where("sku_id", $sku_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->sku_konversi_group;
		}

		return $query;
	}

	public function getNameSKUById($sku_id)
	{
		$this->db->select("*")
			->from("sku")
			->where("sku_id", $sku_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->sku_nama_produk;
		}

		return $query;
	}

	public function get_client_wms_konversi_sku()
	{

		$query = $this->db->query("select NEWID() AS tr_konversi_sku_id, sku.client_wms_id from tr_konversi_sku_from_do konversi left join sku on sku.sku_id = konversi.sku_id group by sku.client_wms_id");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function get_tr_konversi_sku_from_do_by_depo()
	{
		$this->db->select("*")
			->from("tr_konversi_sku_from_do")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("tr_konversi_sku_from_do_keterangan", "MINIMUM STOCK");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function cek_tr_konversi_sku_from_do($sku_id, $sku_stock_id)
	{
		$this->db->select("*")
			->from("tr_konversi_sku_from_do")
			->where("sku_id", $sku_id)
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("sku_stock_id", $sku_stock_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getSKULevel($sku_konversi_group)
	{

		$query = $this->db->query("select top 1 * from sku where sku_konversi_group = '$sku_konversi_group' and sku_konversi_level <> 0 order by sku_konversi_level desc");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDataSKUPembongkaran($sku_id, $depo_detail_id)
	{
		$query = $this->db->query("SELECT
									ss.sku_stock_id,
									ss.unit_mandiri_id,
									ss.client_wms_id,
									ss.depo_id,
									ss.depo_detail_id,
									ss.sku_induk_id,
									ss.sku_id,
									ss.sku_stock_expired_date,
									ss.sku_stock_batch_no,
									ISNULL(ss.sku_stock_awal,'0') AS sku_stock_awal,
									ISNULL(ss.sku_stock_masuk,'0') AS sku_stock_masuk,
									ISNULL(ss.sku_stock_alokasi,'0') AS sku_stock_alokasi,
									ISNULL(ss.sku_stock_saldo_alokasi,'0') AS sku_stock_saldo_alokasi,
									ISNULL(ss.sku_stock_keluar,'0') AS sku_stock_keluar,
									ISNULL(ss.sku_stock_akhir,'0') AS sku_stock_akhir,
									ss.sku_stock_is_jual,
									ss.sku_stock_is_aktif,
									ss.sku_stock_is_deleted,
									sku.sku_konversi_faktor
									FROM sku_stock ss
									LEFT JOIN sku ON ss.sku_id = sku.sku_id
									LEFT JOIN depo_detail dd ON ss.depo_detail_id = dd.depo_detail_id
									WHERE ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
									AND ss.sku_id = '$sku_id'
									AND ss.depo_detail_id = '$depo_detail_id'
									ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function Get_MonitorMinimumStok($principle, $depo_detail_id, $min_stock)
	{

		$min_stock = $min_stock == '' ? 0 : $min_stock;

		$query = $this->db->query("SELECT
									sku_stock.sku_stock_id,
									sku_stock.client_wms_id,
									sku_stock.depo_id,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									sku_stock.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									principle.principle_kode AS principle,
									brand.principle_brand_nama AS brand,
									sku.sku_satuan,
									sku.sku_kemasan,
									FORMAT(sku_stock.sku_stock_expired_date,'yyyy-MM-dd') AS sku_stock_expired_date,
									sku_stock.sku_stock_batch_no,
									ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0) AS sku_stock,
									sku.sku_konversi_group,
									sku.sku_konversi_level,
									(SELECT MAX(a.sku_konversi_level) FROM sku a WHERE a.sku_konversi_group = sku.sku_konversi_group) AS max_level,
									'$min_stock' AS min_stock,
									CASE
										WHEN ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0) >= '$min_stock' THEN '1'
										ELSE '0' 
									END AS status_stock
									FROM sku_stock
									LEFT JOIN sku
									ON sku_stock.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand brand
									ON brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									WHERE sku_stock.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND sku_stock.depo_detail_id = '$depo_detail_id'
									AND sku.principle_id = '$principle'
									ORDER BY sku.sku_konversi_group ASC, sku_konversi_level DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDataSKUHasilKonversi($sku_id, $depo_detail_id)
	{
		implode(",", $sku_id);

		$query = $this->db->query("SELECT
									sku_stock.sku_stock_id,
									sku_stock.client_wms_id,
									sku_stock.depo_id,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									sku_stock.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									principle.principle_kode AS principle,
									brand.principle_brand_nama AS brand,
									sku.sku_satuan,
									sku.sku_kemasan,
									FORMAT(sku_stock.sku_stock_expired_date,'yyyy-MM-dd') AS sku_stock_expired_date,
									sku_stock.sku_stock_batch_no,
									ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0) AS sku_stock,
									sku.sku_konversi_group,
									sku.sku_konversi_level,
									(SELECT MAX(a.sku_konversi_level) FROM sku a WHERE a.sku_konversi_group = sku.sku_konversi_group) AS max_level
									FROM sku_stock
									LEFT JOIN sku
									ON sku_stock.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand brand
									ON brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									WHERE sku_stock.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND sku_stock.depo_detail_id = '$depo_detail_id'
									AND sku_id IN (" . $sku_id . ")
									ORDER BY sku.sku_konversi_group ASC, sku_konversi_level DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function insert_tr_konversi_sku_from_do($tr_konversi_sku_from_do_id, $depo_id, $tr_konversi_sku_from_do_tanggal, $sku_id, $sku_qty, $tr_konversi_sku_from_do_status, $tr_konversi_sku_from_do_tgl_create, $tr_konversi_sku_from_do_who_create, $tr_konversi_sku_from_do_keterangan, $sku_stock_exp_date, $sku_stock_id)
	{
		// $tr_konversi_sku_from_do_id = $tr_konversi_sku_from_do_id == "" ? null : $tr_konversi_sku_from_do_id;
		$depo_id = $depo_id == "" ? null : $depo_id;
		$tr_konversi_sku_from_do_tanggal = $tr_konversi_sku_from_do_tanggal == "" ? null : date('Y-m-d', strtotime($tr_konversi_sku_from_do_tanggal));
		$sku_id = $sku_id == "" ? null : $sku_id;
		$sku_qty = $sku_qty == "" ? null : $sku_qty;
		$tr_konversi_sku_from_do_status = $tr_konversi_sku_from_do_status == "" ? null : $tr_konversi_sku_from_do_status;
		$tr_konversi_sku_from_do_tgl_create = $tr_konversi_sku_from_do_tgl_create == "" ? null : $tr_konversi_sku_from_do_tgl_create;
		$tr_konversi_sku_from_do_who_create = $tr_konversi_sku_from_do_who_create == "" ? null : $tr_konversi_sku_from_do_who_create;
		$tr_konversi_sku_from_do_keterangan = $tr_konversi_sku_from_do_keterangan == "" ? null : $tr_konversi_sku_from_do_keterangan;
		$sku_stock_exp_date = $sku_stock_exp_date == "" ? null : $sku_stock_exp_date;
		$sku_stock_id = $sku_stock_id == "" ? null : $sku_stock_id;

		$this->db->set("tr_konversi_sku_from_do_id", $tr_konversi_sku_from_do_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("tr_konversi_sku_from_do_tanggal", $tr_konversi_sku_from_do_tanggal);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_qty", $sku_qty);
		$this->db->set("tr_konversi_sku_from_do_status", $tr_konversi_sku_from_do_status);
		$this->db->set("tr_konversi_sku_from_do_tgl_create", $tr_konversi_sku_from_do_tgl_create);
		$this->db->set("tr_konversi_sku_from_do_who_create", $tr_konversi_sku_from_do_who_create);
		$this->db->set("tr_konversi_sku_from_do_keterangan", $tr_konversi_sku_from_do_keterangan);
		$this->db->set("sku_stock_exp_date", $sku_stock_exp_date);
		$this->db->set("sku_stock_id", $sku_stock_id);

		$queryinsert = $this->db->insert("tr_konversi_sku_from_do");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Update_tr_konversi_sku_from_do($tr_konversi_sku_from_do_id, $depo_id, $tr_konversi_sku_from_do_tanggal, $sku_id, $sku_qty, $tr_konversi_sku_from_do_status, $tr_konversi_sku_from_do_tgl_create, $tr_konversi_sku_from_do_who_create, $tr_konversi_sku_from_do_keterangan, $sku_stock_exp_date, $sku_stock_id)
	{
		// $tr_konversi_sku_from_do_id = $tr_konversi_sku_from_do_id == "" ? null : $tr_konversi_sku_from_do_id;
		$depo_id = $depo_id == "" ? null : $depo_id;
		$tr_konversi_sku_from_do_tanggal = $tr_konversi_sku_from_do_tanggal == "" ? null : date('Y-m-d', strtotime($tr_konversi_sku_from_do_tanggal));
		$sku_id = $sku_id == "" ? null : $sku_id;
		$sku_qty = $sku_qty == "" ? null : $sku_qty;
		$tr_konversi_sku_from_do_status = $tr_konversi_sku_from_do_status == "" ? null : $tr_konversi_sku_from_do_status;
		$tr_konversi_sku_from_do_tgl_create = $tr_konversi_sku_from_do_tgl_create == "" ? null : $tr_konversi_sku_from_do_tgl_create;
		$tr_konversi_sku_from_do_who_create = $tr_konversi_sku_from_do_who_create == "" ? null : $tr_konversi_sku_from_do_who_create;
		$tr_konversi_sku_from_do_keterangan = $tr_konversi_sku_from_do_keterangan == "" ? null : $tr_konversi_sku_from_do_keterangan;

		$this->db->set("depo_id", $depo_id);
		$this->db->set("tr_konversi_sku_from_do_tanggal", $tr_konversi_sku_from_do_tanggal);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_qty", $sku_qty);
		$this->db->set("tr_konversi_sku_from_do_status", $tr_konversi_sku_from_do_status);
		$this->db->set("tr_konversi_sku_from_do_tgl_create", $tr_konversi_sku_from_do_tgl_create);
		$this->db->set("tr_konversi_sku_from_do_who_create", $tr_konversi_sku_from_do_who_create);
		$this->db->set("tr_konversi_sku_from_do_keterangan", $tr_konversi_sku_from_do_keterangan);
		$this->db->set("sku_stock_exp_date", $sku_stock_exp_date);
		$this->db->set("sku_stock_id", $sku_stock_id);

		$this->db->where("tr_konversi_sku_from_do_id", $tr_konversi_sku_from_do_id);

		$queryinsert = $this->db->update("tr_konversi_sku_from_do");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Update_qty_tr_konversi_sku_detail($tr_konversi_sku_id, $sku_id, $sku_qty)
	{
		$this->db->set("tr_konversi_sku_detail_qty_plan", $sku_qty);

		$this->db->where("tr_konversi_sku_id", $tr_konversi_sku_id);
		$this->db->where("sku_id", $sku_id);

		$queryinsert = $this->db->update("tr_konversi_sku_detail");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function delete_tr_konversi_sku_from_do()
	{

		$this->db->query("delete from tr_konversi_sku_from_do_detail where tr_konversi_sku_from_do_id in (select tr_konversi_sku_from_do_id from tr_konversi_sku_from_do where depo_id = '" . $this->session->userdata('depo_id') . "' AND tr_konversi_sku_from_do_keterangan = 'MINIMUM STOCK') ");
		$this->db->query("delete from tr_konversi_sku_from_do where depo_id = '" . $this->session->userdata('depo_id') . "' AND tr_konversi_sku_from_do_keterangan = 'MINIMUM STOCK' ");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function get_sku_stock_by_konversi_sku($data)
	{
		$counter = 0;
		$union = " UNION ALL ";
		$table_sementara = "";
		$list_sku_id = array();
		foreach ($data as $key => $value) {
			array_push($list_sku_id, "'" . $value['sku_id'] . "'");

			$table_sementara .= "SELECT '" . $value['sku_id'] . "' AS sku_id, " . $value['sku_qty'] . " AS sku_qty, '" . $value['sku_stock_id'] . "' AS sku_stock_id, '" . $value['sku_stock_exp_date'] . "' AS sku_stock_exp_date ";

			$counter++;

			if ($key < count($data) - 1) {
				$table_sementara .= $union;
			}
		}

		$sku_id = implode(",", $list_sku_id);


		$query = $this->db->query("SELECT
									sku_stock.client_wms_id,
									sku_stock.sku_id,
									sku_stock.sku_kode,
									sku_stock.sku_nama_produk,
									sku_stock.principle_kode,
									sku_stock.principle_brand_nama,
									sku_stock.sku_satuan,
									sku_stock.sku_kemasan,
									sku_stock.sku_stock_id,
									sku_stock.sku_stock_expired_date,
									sku_stock.sku_stock_batch_no,
									sku_stock.depo_detail_id,
									gudang.depo_detail_nama,
									SUM(ISNULL(konversi.sku_qty,0)) AS sku_qty
									FROM (SELECT
									sku.client_wms_id,
									sku_stock.depo_id,
									sku_stock.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									principle.principle_kode,
									principle_brand.principle_brand_nama,
									sku.sku_satuan,
									sku.sku_kemasan,
									sku_stock.sku_stock_id,
									sku_stock.sku_stock_expired_date,
									sku_stock.sku_stock_batch_no,
									sku_stock.depo_detail_id
									FROM sku_stock
									LEFT JOIN sku
									ON sku.sku_id = sku_stock.sku_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									WHERE sku_stock.sku_id in (" . $sku_id . ")
									AND sku_stock.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND sku_stock.depo_detail_id IN (SELECT depo_detail_id FROM depo_detail WHERE depo_id = '" . $this->session->userdata('depo_id') . "')
									GROUP BY sku.client_wms_id, 
											sku_stock.depo_id,
											sku_stock.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											principle.principle_kode,
											principle_brand.principle_brand_nama,
											sku.sku_satuan,
											sku.sku_kemasan,
											sku_stock.sku_stock_id,
											sku_stock.sku_stock_expired_date,
											sku_stock.sku_stock_batch_no,
											sku_stock.depo_detail_id) sku_stock
									INNER JOIN (" . $table_sementara . ") konversi
									ON konversi.sku_id = sku_stock.sku_id
									AND konversi.sku_stock_id = sku_stock.sku_stock_id
									LEFT JOIN depo_detail gudang
									ON gudang.depo_detail_id = sku_stock.depo_detail_id
									GROUP BY sku_stock.client_wms_id,
											sku_stock.sku_id,
											sku_stock.sku_kode,
											sku_stock.sku_nama_produk,
											sku_stock.principle_kode,
											sku_stock.principle_brand_nama,
											sku_stock.sku_satuan,
											sku_stock.sku_kemasan,
											sku_stock.sku_stock_id,
											sku_stock.sku_stock_expired_date,
											sku_stock.sku_stock_batch_no,
											sku_stock.depo_detail_id,
											gudang.depo_detail_nama
									ORDER BY sku_stock.client_wms_id, sku_stock.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function getED($sku_id, $client_wms_id, $depo_detail_id)
	{
		$query = $this->db->query("SELECT sku_stock_id, sku_id, client_wms_id, depo_id, 
											sku_stock_batch_no, depo_detail_id, 
											FORMAT(sku_stock_expired_date,'yyyy-MM-dd') AS sku_stock_expired_date
									FROM sku_stock 
									WHERE sku_id = '$sku_id' 
									AND depo_id = '" . $this->session->userdata('depo_id') . "' 
									AND client_wms_id = '$client_wms_id'
									AND depo_detail_id = '$depo_detail_id' 
									ORDER BY sku_stock_expired_date ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
}
