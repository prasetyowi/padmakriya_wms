<?php

class M_PersetujuanPembongkaranBarang extends CI_Model
{
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

	public function GetPerusahaan()
	{
		$this->db->distinct()
			->select("b.client_wms_nama, b.client_wms_id")
			->from("sku_stock a")
			->join("client_wms b", "a.client_wms_id = b.client_wms_id")
			->where("a.depo_id", $this->session->userdata("depo_id"))
			->order_by("client_wms_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetTipeKonversi()
	{
		$this->db->select("*")
			->from("tipe_konversi")
			->where("tipe_konversi_is_aktif", 1)
			->order_by("tipe_konversi_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getStatus()
	{
		$query = $this->db->query("SELECT DISTINCT tr_konversi_sku_status FROM tr_konversi_sku");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetGudang()
	{

		$this->db->select("*")
			->from("depo_detail")
			->where("depo_id", $this->session->userdata('depo_id'))
			->order_by("depo_detail_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetPrincipleAktif()
	{
		$query = $this->db->query("SELECT * FROM principle WHERE principle_is_aktif = '1' ORDER BY principle_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetPrinciple()
	{
		$query = $this->db->query("SELECT
									principle.*,
									isnull(area.area_nama,'') AS area_nama
									FROM client_wms_principle
									LEFT JOIN principle
									ON principle.principle_id = client_wms_principle.principle_id
									LEFT JOIN area
									ON principle.area_id = area.area_id
									ORDER BY principle.principle_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetPrincipleByPerusahaan($perusahaan)
	{
		$query = $this->db->query("SELECT
									principle.*
									FROM client_wms_principle
									LEFT JOIN principle
									ON principle.principle_id = client_wms_principle.principle_id
                                    WHERE client_wms_principle.client_wms_id = '$perusahaan'
									ORDER BY principle.principle_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiSKUByFilter($tgl1, $tgl2, $perusahaan, $status, $tipe, $principle)
	{

		if ($perusahaan == "") {
			$perusahaan = "";
		} else {
			$perusahaan = "AND tr_konversi_sku.client_wms_id = '" . $perusahaan . "' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND tr_konversi_sku.tr_konversi_sku_status = '" . $status . "' ";
		}

		if ($tipe == "") {
			$tipe = "";
		} else {
			$tipe = "AND tr_konversi_sku.tipe_konversi_id = '" . $tipe . "' ";
		}

		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND principle.principle_id = '" . $principle . "' ";
		}

		$query = $this->db->query("SELECT
                                    tr_konversi_sku.tr_konversi_sku_id,
                                    tr_konversi_sku.tr_konversi_sku_kode,
                                    tr_konversi_sku.tipe_konversi_id,
                                    tipe_konversi.tipe_konversi_nama,
                                    tr_konversi_sku.client_wms_id,
                                    client_wms.client_wms_nama,
                                    FORMAT(tr_konversi_sku.tr_konversi_sku_tanggal, 'dd-MM-yyyy') AS tr_konversi_sku_tanggal,
                                    tr_konversi_sku.tr_konversi_sku_status,
									principle.principle_kode
                                    FROM tr_konversi_sku
                                    LEFT JOIN client_wms
                                    ON client_wms.client_wms_id = tr_konversi_sku.client_wms_id
                                    LEFT JOIN tipe_konversi
                                    ON tipe_konversi.tipe_konversi_id = tr_konversi_sku.tipe_konversi_id
									LEFT JOIN tr_konversi_sku_detail
									ON tr_konversi_sku_detail.tr_konversi_sku_id = tr_konversi_sku.tr_konversi_sku_id
									LEFT JOIN sku 
									ON tr_konversi_sku_detail.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
                                    WHERE FORMAT(tr_konversi_sku.tr_konversi_sku_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
                                    " . $perusahaan . "
                                    " . $status . "
                                    " . $tipe . "
                                    " . $principle . "
									GROUP BY  tr_konversi_sku.tr_konversi_sku_id,tr_konversi_sku.tr_konversi_sku_kode,
                                    tr_konversi_sku.tipe_konversi_id,
                                    tipe_konversi.tipe_konversi_nama,
                                    tr_konversi_sku.client_wms_id,
                                    client_wms.client_wms_nama,  FORMAT(tr_konversi_sku.tr_konversi_sku_tanggal, 'dd-MM-yyyy'),
									 tr_konversi_sku.tr_konversi_sku_status,
									principle.principle_kode,
									tr_konversi_sku.tr_konversi_sku_tgl_create
                                    ORDER BY tr_konversi_sku.tr_konversi_sku_tgl_create DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiHeaderById($id)
	{
		$query = $this->db->query("SELECT
                                    tr_konversi_sku_id,
                                    client_wms_id,
                                    depo_detail_id,
                                    tipe_konversi_id,
                                    tr_konversi_sku_kode,
                                    FORMAT(tr_konversi_sku_tanggal, 'dd-MM-yyyy') AS tr_konversi_sku_tanggal,
                                    tr_konversi_sku_status,
                                    tr_konversi_sku_keterangan,
                                    tr_konversi_sku_tgl_create,
                                    tr_konversi_sku_who_create,
									tr_konversi_sku_tgl_update,
                                    tr_konversi_sku_who_update
                                    FROM tr_konversi_sku
									WHERE tr_konversi_sku_id = '$id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiDetailById($id)
	{
		$query = $this->db->query("SELECT
                                    detail.tr_konversi_sku_detail_id,
                                    detail.tr_konversi_sku_id,
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.principle_id,
                                    principle.principle_kode AS principle,
                                    sku.principle_brand_id,
                                    principle_brand.principle_brand_nama AS brand,
                                    sku.sku_kemasan,
                                    sku.sku_satuan,
                                    detail.sku_stock_id,
									detail.sku_konversi_hasil,
                                    FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
                                    ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0) AS tr_konversi_sku_detail_qty_plan
                                    FROM tr_konversi_sku_detail detail
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail.sku_id
                                    LEFT JOIN principle
                                    ON principle.principle_id = sku.principle_id
                                    LEFT JOIN principle_brand
                                    ON principle_brand.principle_brand_id = sku.principle_brand_id
                                    WHERE detail.tr_konversi_sku_id = '$id'
                                    ORDER BY sku.sku_kode,FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiDetail2ById($id)
	{
		$query = $this->db->query("SELECT
                                        detail2.*,
                                        detail.sku_stock_expired_date,
                                        detail.sku_id AS sku_id_detail
                                    FROM tr_konversi_sku_detail2 detail2
                                    LEFT JOIN tr_konversi_sku_detail detail
                                    ON detail.tr_konversi_sku_detail_id = detail2.tr_konversi_sku_detail_id
                                    WHERE detail.tr_konversi_sku_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function Get_Kode($vrbl_param)
	{
		$this->db->select("*")
			->from("tipe_konversi")
			->where("tipe_konversi_id", $vrbl_param);
		$query = $this->db->get();

		return $query->row();
	}

	public function GetSelectedSKU($sku_id, $sku_stock_expired_date, $sku_stock_id)
	{
		if ($sku_id != "") {
			$sku_id = implode(",", $sku_id);
		} else {
			$sku_id = "NULL";
		}

		if ($sku_stock_expired_date != "") {
			$sku_stock_expired_date = implode(",", $sku_stock_expired_date);
		} else {
			$sku_stock_expired_date = "NULL";
		}

		if ($sku_stock_id != "") {
			$sku_stock_id = implode(",", $sku_stock_id);
		} else {
			$sku_stock_id = "NULL";
		}

		$query = $this->db->query("SELECT
                                    sku_stock.sku_stock_id,
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
                                    sku_induk.sku_induk_nama AS sku_induk,
                                    sku.sku_konversi_group,
                                    sku.sku_konversi_level,
                                    ISNULL(sku.sku_konversi_faktor, 0) AS sku_konversi_faktor,
                                    sku_stock.sku_stock_expired_date,
                                    FORMAT(sku_stock.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date2,
                                    ISNULL(temp.tr_konversi_sku_detail2_qty,0) AS tr_konversi_sku_detail2_qty
                                    FROM sku_stock
                                    INNER JOIN sku
                                    ON sku.sku_id = sku_stock.sku_id
                                    LEFT JOIN (SELECT tr_konversi_sku_detail_id AS sku_id,sku_stock_expired_date,SUM(tr_konversi_sku_detail2_qty) AS tr_konversi_sku_detail2_qty FROM tr_konversi_sku_detail2_temp GROUP BY tr_konversi_sku_detail_id,sku_stock_expired_date) temp
                                    ON temp.sku_id = sku_stock.sku_id
                                    AND temp.sku_stock_expired_date = sku_stock.sku_stock_expired_date
                                    LEFT JOIN sku_induk
                                    ON sku.sku_induk_id = sku_induk.sku_induk_id
                                    LEFT JOIN principle
                                    ON principle.principle_id = sku.principle_id
                                    LEFT JOIN principle_brand
                                    ON principle_brand.principle_brand_id = sku.principle_brand_id
                                    WHERE sku_stock.sku_id IN (" . $sku_id . ")
                                    AND sku_stock.sku_stock_expired_date IN (" . $sku_stock_expired_date . ")
                                    AND sku_stock.sku_stock_id IN (" . $sku_stock_id . ")
                                    ORDER BY principle.principle_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetSKUKonversiBySKU($sku_stock_id, $sku_id, $sku_stock_expired_date, $satuan, $tipe_konversi)
	{
		if ($tipe_konversi == "Unpack") {
			$tipe_konversi = "AND sku_konversi.sku_konversi_level < sku.sku_konversi_level ";
		} else if ($tipe_konversi == "Repack") {
			$tipe_konversi = "AND sku_konversi.sku_konversi_level > sku.sku_konversi_level ";
		}

		$query = $this->db->query("SELECT
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.sku_kemasan AS sku_kemasan_param,
                                    sku.sku_satuan AS sku_satuan_param,
                                    sku.sku_konversi_level AS sku_konversi_level_param,
                                    ISNULL(sku.sku_konversi_faktor, 0) AS sku_konversi_faktor_param,
                                    sku.sku_induk_id,
                                    sku_stock.sku_stock_id,
                                    sku_stock.sku_stock_expired_date,
                                    sku_konversi.sku_id AS sku_id_konversi,
                                    sku_konversi.sku_kemasan,
                                    sku_konversi.sku_satuan,
                                    sku.sku_konversi_group,
                                    sku_konversi.sku_konversi_level,
                                    ISNULL(sku_konversi.sku_konversi_faktor, 0) AS sku_konversi_faktor,
                                    CASE
                                        WHEN sku.sku_konversi_level > sku_konversi.sku_konversi_level THEN '*'
                                        WHEN sku.sku_konversi_level = sku_konversi.sku_konversi_level THEN '='
                                        ELSE '/'
                                    END konversi_operator,
                                    ISNULL(temp.tr_konversi_sku_detail2_qty,0) AS tr_konversi_sku_detail2_qty,
                                    ISNULL(temp.tr_konversi_sku_detail2_qty_result,0) AS tr_konversi_sku_detail2_qty_result
                                    FROM sku_stock
                                    LEFT JOIN sku
                                    ON sku.sku_id = sku_stock.sku_id
                                    LEFT JOIN sku sku_konversi
                                    ON sku.sku_konversi_group = sku_konversi.sku_konversi_group
                                    AND sku_konversi.sku_satuan <> '$satuan'
                                    LEFT JOIN tr_konversi_sku_detail2_temp temp
                                    ON temp.sku_id = sku_konversi.sku_id
                                    AND temp.sku_stock_expired_date = sku_stock.sku_stock_expired_date
                                    WHERE sku_stock.sku_id = '$sku_id'
                                    AND sku_stock.sku_stock_expired_date = '$sku_stock_expired_date'
                                    AND sku_stock.sku_stock_id = '$sku_stock_id'
                                    " . $tipe_konversi . "
                                    GROUP BY sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.sku_kemasan,
                                    sku.sku_satuan,
                                    sku.sku_konversi_level,
                                    ISNULL(sku.sku_konversi_faktor, 0),
                                    sku.sku_induk_id,
                                    sku_stock.sku_stock_id,
                                    sku_stock.sku_stock_expired_date,
                                    sku_konversi.sku_id,
                                    sku_konversi.sku_kemasan,
                                    sku_konversi.sku_satuan,
                                    sku.sku_konversi_group,
                                    sku_konversi.sku_konversi_level,
                                    ISNULL(sku_konversi.sku_konversi_faktor, 0),
                                    CASE
                                        WHEN sku.sku_konversi_level > sku_konversi.sku_konversi_level THEN '*'
                                        WHEN sku.sku_konversi_level = sku_konversi.sku_konversi_level THEN '='
                                        ELSE '/'
                                    END,
                                    ISNULL(temp.tr_konversi_sku_detail2_qty,0),
                                    ISNULL(temp.tr_konversi_sku_detail2_qty_result,0)
                                    ORDER BY sku_konversi.sku_konversi_level DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetSKUKonversiBySKU2($tr_konversi_sku_detail_id)
	{

		$query = $this->db->query("SELECT
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.sku_kemasan,
                                    sku.sku_satuan,
                                    sku.sku_konversi_level,
                                    ISNULL(sku.sku_konversi_faktor, 0) AS sku_konversi_faktor,
                                    sku.sku_induk_id,
                                    ISNULL(detail2.tr_konversi_sku_detail2_qty, 0) AS tr_konversi_sku_detail2_qty,
                                    ISNULL(detail2.tr_konversi_sku_detail2_qty_result, 0) AS tr_konversi_sku_detail2_qty_result
                                    FROM tr_konversi_sku_detail2 detail2
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail2.sku_id
                                    WHERE detail2.tr_konversi_sku_detail_id = '$tr_konversi_sku_detail_id'
                                    ORDER BY sku.sku_kode,sku.sku_konversi_level ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function search_filter_chosen_sku($perusahaan, $depo_detail_id, $principle, $sku_kode, $sku_nama_produk, $sku_satuan, $tipe_konversi)
	{
		// AND (sku_stock.sku_stock_awal + sku_stock.sku_stock_masuk - sku_stock.sku_stock_saldo_alokasi - sku_stock.sku_stock_keluar) > 0
		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND sku.principle_id = '$principle' ";
		}

		if ($sku_kode == "") {
			$sku_kode = "";
		} else {
			$sku_kode = "AND sku.sku_kode LIKE '%" . $sku_kode . "%' ";
		}

		if ($sku_nama_produk == "") {
			$sku_nama_produk = "";
		} else {
			$sku_nama_produk = "AND sku.sku_nama_produk LIKE '%" . $sku_nama_produk . "%' ";
		}

		if ($sku_satuan == "") {
			$sku_satuan = "";
		} else {
			$sku_satuan = "AND sku.sku_satuan LIKE '%" . $sku_satuan . "%' ";
		}

		if ($tipe_konversi == "D5CF6747-6C95-40E1-A64B-6A9057DD320B") {
			$tipe_konversi = "AND sku.sku_konversi_level <> (select max(s.sku_konversi_level) as max_level from sku s where s.sku_konversi_group = sku.sku_konversi_group)";
		} else if ($tipe_konversi == "B5B99B77-86D2-48B8-964F-D4B91CDD9B0C") {
			$tipe_konversi = "AND sku.sku_konversi_level <> '0'";
		} else {
			$tipe_konversi = "";
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
									sku_induk.sku_induk_nama AS sku_induk,
									sku.sku_konversi_group,
									sku.sku_konversi_level,
									sku_stock.sku_stock_id,
									sku_stock.sku_stock_expired_date,
									FORMAT(sku_stock.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date2,
									ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0) AS sku_stock,
									isnull(sku_stock.sku_stock_batch_no, '') as sku_stock_batch_no
									FROM sku_stock
									INNER JOIN sku
									ON sku.sku_id = sku_stock.sku_id
									LEFT JOIN sku_induk
									ON sku.sku_induk_id = sku_induk.sku_induk_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									WHERE sku_stock.client_wms_id = '$perusahaan'
                                    AND sku_stock.depo_detail_id = '$depo_detail_id'
                                    " . $principle . "
                                    " . $sku_kode . "
                                    " . $sku_nama_produk . "
                                    " . $sku_satuan . " 
                                    " . $tipe_konversi . " 
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
											sku_induk.sku_induk_nama,
											sku.sku_konversi_group,
											sku.sku_konversi_level,
											sku_stock.sku_stock_id,
											sku_stock.sku_stock_expired_date,
											FORMAT(sku_stock.sku_stock_expired_date, 'yyyy-MM-dd'),
											ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0),
											isnull(sku_stock.sku_stock_batch_no, '')
									HAVING ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0) > 0
									ORDER BY principle.principle_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function insert_tr_konversi_sku_detail2_temp($data)
	{
		$sku_stock_id = $data["sku_stock_id"] == "" ? null : $data["sku_stock_id"];
		$tr_konversi_sku_detail_id = $data["tr_konversi_sku_detail_id"] == "" ? null : $data["tr_konversi_sku_detail_id"];
		$sku_id = $data["sku_id"] == "" ? null : $data["sku_id"];
		$sku_stock_expired_date = $data["sku_stock_expired_date"] == "" ? null : $data["sku_stock_expired_date"];
		$tr_konversi_sku_detail2_qty_result = $data["tr_konversi_sku_detail2_qty_result"] == "" ? null : $data["tr_konversi_sku_detail2_qty_result"];
		$tr_konversi_sku_detail2_qty = $data["tr_konversi_sku_detail2_qty"] == "" ? null : $data["tr_konversi_sku_detail2_qty"];

		$this->db->set("tr_konversi_sku_detail2_id", "NEWID()", FALSE);
		$this->db->set("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
		$this->db->set("tr_konversi_sku_detail2_qty", $tr_konversi_sku_detail2_qty);
		$this->db->set("tr_konversi_sku_detail2_qty_result", $tr_konversi_sku_detail2_qty_result);

		$queryinsert = $this->db->insert("tr_konversi_sku_detail2_temp");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create, $is_from_do)
	{
		$tr_konversi_sku_id = $tr_konversi_sku_id == '' ? null : $tr_konversi_sku_id;
		$client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
		$depo_detail_id = $depo_detail_id == '' ? null : $depo_detail_id;
		$tipe_konversi_id = $tipe_konversi_id == '' ? null : $tipe_konversi_id;
		$tr_konversi_sku_kode = $tr_konversi_sku_kode == '' ? null : $tr_konversi_sku_kode;
		$tr_konversi_sku_tanggal = $tr_konversi_sku_tanggal == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $tr_konversi_sku_tanggal)));;
		$tr_konversi_sku_status = $tr_konversi_sku_status == '' ? null : $tr_konversi_sku_status;
		$tr_konversi_sku_tgl_create = $tr_konversi_sku_tgl_create == '' ? null : $tr_konversi_sku_tgl_create;
		$tr_konversi_sku_who_create = $tr_konversi_sku_who_create == '' ? null : $tr_konversi_sku_who_create;
		$is_from_do = $is_from_do == '' ? null : $is_from_do;

		$this->db->set("tr_konversi_sku_id", $tr_konversi_sku_id);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("tipe_konversi_id", $tipe_konversi_id);
		$this->db->set("tr_konversi_sku_kode", $tr_konversi_sku_kode);
		$this->db->set("tr_konversi_sku_tanggal", $tr_konversi_sku_tanggal);
		$this->db->set("tr_konversi_sku_status", $tr_konversi_sku_status);
		$this->db->set("tr_konversi_sku_keterangan", $tr_konversi_sku_keterangan);
		$this->db->set("tr_konversi_sku_tgl_create", "GETDATE()", FALSE);
		$this->db->set("tr_konversi_sku_who_create", $tr_konversi_sku_who_create);
		$this->db->set("is_from_do", $is_from_do);

		$queryinsert = $this->db->insert("tr_konversi_sku");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function update_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create)
	{
		$tr_konversi_sku_id = $tr_konversi_sku_id == '' ? null : $tr_konversi_sku_id;
		$client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
		$depo_detail_id = $depo_detail_id == '' ? null : $depo_detail_id;
		$tipe_konversi_id = $tipe_konversi_id == '' ? null : $tipe_konversi_id;
		$tr_konversi_sku_kode = $tr_konversi_sku_kode == '' ? null : $tr_konversi_sku_kode;
		$tr_konversi_sku_tanggal = $tr_konversi_sku_tanggal == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $tr_konversi_sku_tanggal)));;
		$tr_konversi_sku_status = $tr_konversi_sku_status == '' ? null : $tr_konversi_sku_status;
		$tr_konversi_sku_keterangan = $tr_konversi_sku_keterangan == '' ? null : $tr_konversi_sku_keterangan;
		$tr_konversi_sku_tgl_create = $tr_konversi_sku_tgl_create == '' ? null : $tr_konversi_sku_tgl_create;
		$tr_konversi_sku_who_create = $tr_konversi_sku_who_create == '' ? null : $tr_konversi_sku_who_create;

		// $this->db->set("tr_konversi_sku_id", $tr_konversi_sku_id);
		// $this->db->set("client_wms_id", $client_wms_id);
		// $this->db->set("tipe_konversi_id", $tipe_konversi_id);
		// $this->db->set("tr_konversi_sku_kode", $tr_konversi_sku_kode);
		// $this->db->set("tr_konversi_sku_tanggal", $tr_konversi_sku_tanggal);
		$this->db->set("tr_konversi_sku_status", $tr_konversi_sku_status);
		$this->db->set("tr_konversi_sku_keterangan", $tr_konversi_sku_keterangan);
		// $this->db->set("tr_konversi_sku_tgl_create", "GETDATE()", FALSE);
		// $this->db->set("tr_konversi_sku_who_create", $tr_konversi_sku_who_create);

		$this->db->where("tr_konversi_sku_id", $tr_konversi_sku_id);
		$queryinsert = $this->db->update("tr_konversi_sku");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_tr_konversi_sku_detail($tr_konversi_sku_detail_id, $tr_konversi_sku_id, $data)
	{
		$sku_stock_id = $data["sku_stock_id"] == "" ? null : $data["sku_stock_id"];
		$sku_id = $data["sku_id"] == "" ? null : $data["sku_id"];
		$sku_stock_expired_date = $data["sku_stock_expired_date"] == "" ? null : $data["sku_stock_expired_date"];
		$tr_konversi_sku_detail_qty_plan = $data["tr_konversi_sku_detail_qty_plan"] == "" ? null : $data["tr_konversi_sku_detail_qty_plan"];
		$sku_konversi_hasil = $data["sku_konversi_hasil"] == "" ? null : $data["sku_konversi_hasil"];

		$this->db->set("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		$this->db->set("tr_konversi_sku_id", $tr_konversi_sku_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
		$this->db->set("tr_konversi_sku_detail_qty_plan", $tr_konversi_sku_detail_qty_plan);
		$this->db->set("sku_konversi_hasil", $sku_konversi_hasil);

		$queryinsert = $this->db->insert("tr_konversi_sku_detail");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_tr_konversi_sku_detail2($tr_konversi_sku_detail_id, $data)
	{
		$sku_stock_id = $data["sku_stock_id"] == "" ? null : $data["sku_stock_id"];
		$sku_id = $data["sku_id"] == "" ? null : $data["sku_id"];
		// $sku_stock_expired_date = $data["sku_stock_expired_date"] == "" ? null : $data["sku_stock_expired_date"];
		$tr_konversi_sku_detail2_qty_result = $data["tr_konversi_sku_detail2_qty_result"] == "" ? null : $data["tr_konversi_sku_detail2_qty_result"];
		$tr_konversi_sku_detail2_qty = $data["tr_konversi_sku_detail2_qty"] == "" ? null : $data["tr_konversi_sku_detail2_qty"];

		$this->db->set("tr_konversi_sku_detail2_id", "NEWID()", FALSE);
		$this->db->set("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_id", $sku_id);
		// $this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
		$this->db->set("tr_konversi_sku_detail2_qty", $tr_konversi_sku_detail2_qty);
		$this->db->set("tr_konversi_sku_detail2_qty_result", $tr_konversi_sku_detail2_qty_result);

		$queryinsert = $this->db->insert("tr_konversi_sku_detail2");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function get_tr_konversi_sku_detail2_temp($sku_id, $sku_stock_expired_date)
	{
		$this->db->select("*")
			->from("tr_konversi_sku_detail2_temp")
			->where("tr_konversi_sku_detail_id", $sku_id)
			->where("sku_stock_expired_date", $sku_stock_expired_date);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function delete_tr_konversi_sku_detail2_temp()
	{
		return $this->db->query("DELETE tr_konversi_sku_detail2_temp");
	}

	public function delete_tr_konversi_sku_detail2_temp_by_sku_stock_id($sku_id, $sku_stock_expired_date)
	{
		$this->db->where('sku_id', $sku_id);
		$this->db->where('sku_stock_expired_date', $sku_stock_expired_date);
		return $this->db->delete('tr_konversi_sku_detail2_temp');
	}

	public function delete_tr_konversi_sku_detail($id)
	{
		$this->db->where('tr_konversi_sku_id', $id);
		return $this->db->delete('tr_konversi_sku_detail');
	}

	public function delete_tr_konversi_sku_detail2($id)
	{
		return $this->db->query("DELETE tr_konversi_sku_detail2 WHERE tr_konversi_sku_detail_id IN (SELECT tr_konversi_sku_detail_id FROM tr_konversi_sku_detail WHERE tr_konversi_sku_id = '$id') ");
	}

	public function Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_konversi_sku_id, $tr_konversi_sku_kode, $is_approvaldana, $total_biaya)
	{
		$query = $this->db->query("exec approval_pengajuan '$depo_id', '$karyawan_id','$approvalParam', '$tr_konversi_sku_id','$tr_konversi_sku_kode', '$is_approvaldana','$total_biaya'");

		// $res = $query->result_array();

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Success
		}

		return $res;
	}

	public function proses_konversi_sku_pack_unpack($sku_id, $qty_plan)
	{
		$query = $this->db->query("Exec proses_konversi_sku_pack_unpack '$sku_id', '$qty_plan'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function CheckKonersiSKU($sku_id)
	{
		$this->db->select("*")
			->from("tr_konversi_sku_detail2_temp")
			->where("tr_konversi_sku_detail_id", $sku_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
	}

	public function CheckQtyKonersiSKU($sku_id, $qty_plan)
	{
		$this->db->select("*")
			->from("tr_konversi_sku_detail2_temp")
			->where("tr_konversi_sku_detail_id", $sku_id)
			->where_not_in("tr_konversi_sku_detail2_qty", $qty_plan);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
	}

	public function check_sku_stock_id($client_wms_id, $depo_detail_id, $data)
	{
		$sku_stock_id = $data['sku_stock_id'] == '' ? null : $data['sku_stock_id'];
		$depo_id = $this->session->userdata('depo_id');
		$sku_induk_id = $this->db->select("sku_induk_id")->from("sku_stock")->where("sku_id", $data['sku_id'])->get()->row(0)->sku_induk_id;
		$sku_id = $data['sku_id'] == '' ? null : $data['sku_id'];
		$sku_stock_expired_date = $data['sku_stock_expired_date'] == '' ? null : $data['sku_stock_expired_date'];
		$sku_stock_keluar = $data['tr_konversi_sku_detail2_qty_result'] == '' ? null : $data['tr_konversi_sku_detail2_qty_result'];
		// $sku_stock_akhir = $data['sku_stock_akhir'] == '' ? null : $data['sku_stock_akhir'];

		$this->db->select("*")
			->from("sku_stock")
			->where("sku_stock_id", $sku_stock_id);
		$query = $this->db->get();

		$sku_stock_keluar = $query->row(0)->sku_stock_keluar + $sku_stock_keluar;

		if ($query->num_rows() == 0) {

			$this->db->set("sku_stock_id", $sku_stock_id);
			// $this->db->set("unit_mandiri_id", $unit_mandiri_id);
			$this->db->set("client_wms_id", $client_wms_id);
			$this->db->set("depo_id", $depo_id);
			$this->db->set("depo_detail_id", $depo_detail_id);
			$this->db->set("sku_induk_id", $sku_induk_id);
			$this->db->set("sku_id", $sku_id);
			$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
			// $this->db->set("sku_stock_batch_no", $sku_stock_batch_no);
			$this->db->set("sku_stock_awal", 0);
			$this->db->set("sku_stock_masuk", 0);
			$this->db->set("sku_stock_alokasi", 0);
			$this->db->set("sku_stock_saldo_alokasi", 0);
			$this->db->set("sku_stock_keluar", $sku_stock_keluar);
			$this->db->set("sku_stock_akhir", 0);
			$this->db->set("sku_stock_is_jual", 1);
			$this->db->set("sku_stock_is_aktif", 1);
			$this->db->set("sku_stock_is_deleted", 0);

			$queryinsert = $this->db->insert("sku_stock");
		} else {
			$this->db->set("sku_stock_keluar", $sku_stock_keluar);
			$this->db->where("sku_stock", $sku_stock_id);

			$queryinsert = $this->db->update("sku_stock");
		}

		return $queryinsert;
	}
}
