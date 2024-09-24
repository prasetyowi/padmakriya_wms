<?php

class M_SalesOrder extends CI_Model
{
	//tes user baru 5
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function GetPerusahaan()
	{
		// $this->db->select("*")
		// 	->from("client_wms")
		// 	->where("client_wms_is_aktif", 1)
		// 	->order_by("client_wms_nama");
		// $query = $this->db->get();

		$query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_SalesOrderNotInDO($tgl, $status, $perusahaan, $principle)
	{
		if ($status == "1") {
			$status = " AND (CASE WHEN client_pt.client_pt_id IS NOT NULL THEN '1' ELSE '0' END = '1')";
		} else if ($status == "0") {
			$status = " AND (CASE WHEN client_pt.client_pt_id IS NOT NULL THEN '1' ELSE '0' END = '0')";
		} else {
			$status = "";
		}

		$perusahaan = $perusahaan != "" ? " AND perusahaan.client_wms_kode = '" . $perusahaan . "'" : "";
		$principle = $principle != "" ? " AND principle.principle_kode = '" . $principle . "'" : "";

		$query = $this->db->query("SELECT DISTINCT
									so.sales_order_id,
									so.sales_order_kode,
									principle.principle_kode,
									so.client_wms_id,
									so.sales_order_tipe_pembayaran,
									FORMAT(so.sales_order_tgl, 'dd-MM-yyyy') AS sales_order_tgl,
									FORMAT(so.sales_order_tgl_sj, 'dd-MM-yyyy') AS sales_order_tgl_sj,
									FORMAT(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
									so.depo_id,
									so.client_pt_id,
									client_pt.client_pt_nama,
									client_pt.client_pt_alamat,
									client_pt.client_pt_telepon,
									client_pt.client_pt_propinsi,
									client_pt.client_pt_kota,
									client_pt.client_pt_kecamatan,
									client_pt.client_pt_kelurahan,
									client_pt.client_pt_kodepos,
									area.area_nama,
									so.tipe_delivery_order_id,
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									so.sales_order_status,
									so.tipe_sales_order_id,
									ts.tipe_sales_order_nama,
									CASE WHEN client_pt.client_pt_id IS NOT NULL THEN '1' ELSE '0' END status_customer
									FROM sales_order so
									LEFT JOIN sales_order_detail so_detail
									ON so_detail.sales_order_id = so.sales_order_id
									LEFT JOIN delivery_order_draft do
									ON do.sales_order_id = so.sales_order_id
									LEFT JOIN sku
									ON sku.sku_id = so_detail.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN client_pt_eksternal client_pt
									ON client_pt.client_pt_id = so.client_pt_id
									LEFT JOIN area
									ON area.area_id = client_pt.area_id
									LEFT JOIN tipe_sales_order ts
									ON so.tipe_sales_order_id = ts.tipe_sales_order_id
									WHERE so.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND FORMAT(so.sales_order_tgl_kirim, 'yyyy-MM-dd') = '$tgl'
									AND so.sales_order_status = 'Approved'
									AND do.delivery_order_draft_id IS NULL
									AND so.sales_order_id IN (SELECT
									so.sales_order_id
									FROM sales_order_detail so
									INNER JOIN client_wms perusahaan
									ON perusahaan.client_wms_id = so.client_wms_id
									WHERE so.sales_order_id IS NOT NULL
									" . $perusahaan . ")
									" . $principle . "
									" . $status . "
									ORDER BY FORMAT(so.sales_order_tgl_kirim, 'dd-MM-yyyy'), so.sales_order_kode ASC");

		// $query = $this->db->query("SELECT
		// 								do.delivery_order_draft_id,
		// 								do.sales_order_id,
		// 								so.sales_order_kode,
		// 								do.delivery_order_draft_kode,
		// 								do.client_wms_id,
		// 								FORMAT(do.delivery_order_draft_tgl_buat_do,'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
		// 								FORMAT(do.delivery_order_draft_tgl_surat_jalan,'dd-MM-yyyy') AS delivery_order_draft_tgl_surat_jalan,
		// 								do.client_pt_id,
		// 								do.delivery_order_draft_kirim_nama,
		// 								do.delivery_order_draft_kirim_alamat,
		// 								do.delivery_order_draft_kirim_telp,
		// 								do.delivery_order_draft_kirim_provinsi,
		// 								do.delivery_order_draft_kirim_kota,
		// 								do.delivery_order_draft_kirim_kecamatan,
		// 								do.delivery_order_draft_kirim_kelurahan,
		// 								do.delivery_order_draft_kirim_kodepos,
		// 								do.delivery_order_draft_kirim_area,
		// 								do.tipe_delivery_order_id,
		// 								tipe.tipe_delivery_order_nama,
		// 								do.delivery_order_draft_status
		// 							FROM delivery_order_draft do
		// 							LEFT JOIN tipe_delivery_order tipe
		// 							ON tipe.tipe_delivery_order_id = do.tipe_delivery_order_id
		// 							INNER JOIN sales_order so
		// 							ON so.sales_order_id = do.sales_order_id
		// 							WHERE FORMAT(so.sales_order_tgl,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
		// 							AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
		// 							ORDER BY FORMAT(do.delivery_order_draft_tgl_surat_jalan,'dd-MM-yyyy'), do.delivery_order_draft_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_SalesOrderInDO($tgl, $perusahaan, $principle)
	{
		$perusahaan = $perusahaan != "" ? " AND client_wms.client_wms_kode = '" . $perusahaan . "'" : "";
		$principle = $principle != "" ? "AND principle.principle_kode = '" . $principle . "'" : "";

		$query = $this->db->query("SELECT DISTINCT
									so.sales_order_id,
									so.sales_order_kode,
									principle.principle_kode,
									so.client_wms_id,
									so.sales_order_tipe_pembayaran,
									FORMAT(so.sales_order_tgl, 'dd-MM-yyyy') AS sales_order_tgl,
									FORMAT(so.sales_order_tgl_sj, 'dd-MM-yyyy') AS sales_order_tgl_sj,
									FORMAT(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
									FORMAT(do.delivery_order_draft_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_draft_tgl_surat_jalan,
									so.depo_id,
									so.client_pt_id,
									client_pt.client_pt_nama,
									client_pt.client_pt_alamat,
									client_pt.client_pt_telepon,
									client_pt.client_pt_propinsi,
									client_pt.client_pt_kota,
									client_pt.client_pt_kecamatan,
									client_pt.client_pt_kelurahan,
									client_pt.client_pt_kodepos,
									area.area_nama,
									so.tipe_delivery_order_id,
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									do.delivery_order_draft_status,
									so.sales_order_status,
									so.tipe_sales_order_id,
									ts.tipe_sales_order_nama
									FROM sales_order so
									LEFT JOIN sales_order_detail so_detail
									ON so_detail.sales_order_id = so.sales_order_id
									LEFT JOIN delivery_order_draft do
									ON do.sales_order_id = so.sales_order_id
									LEFT JOIN sku
									ON sku.sku_id = so_detail.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN client_pt
									ON client_pt.client_pt_id = do.client_pt_id
									LEFT JOIN client_wms
									ON client_wms.client_wms_id = do.client_wms_id
									LEFT JOIN area
									ON area.area_id = client_pt.area_id
									LEFT JOIN tipe_sales_order ts
									ON so.tipe_sales_order_id = ts.tipe_sales_order_id
									WHERE so.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND FORMAT(so.sales_order_tgl_kirim, 'yyyy-MM-dd') = '$tgl'
									AND so.sales_order_status = 'Approved'
									AND do.delivery_order_draft_id IS NOT NULL
									" . $perusahaan . "
									" . $principle . "
									ORDER BY FORMAT(so.sales_order_tgl_kirim, 'dd-MM-yyyy'), so.sales_order_kode ASC");

		// $query = $this->db->query("SELECT
		// 								do.delivery_order_draft_id,
		// 								do.sales_order_id,
		// 								so.sales_order_kode,
		// 								do.delivery_order_draft_kode,
		// 								do.client_wms_id,
		// 								FORMAT(do.delivery_order_draft_tgl_buat_do,'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
		// 								FORMAT(do.delivery_order_draft_tgl_surat_jalan,'dd-MM-yyyy') AS delivery_order_draft_tgl_surat_jalan,
		// 								do.client_pt_id,
		// 								do.delivery_order_draft_kirim_nama,
		// 								do.delivery_order_draft_kirim_alamat,
		// 								do.delivery_order_draft_kirim_telp,
		// 								do.delivery_order_draft_kirim_provinsi,
		// 								do.delivery_order_draft_kirim_kota,
		// 								do.delivery_order_draft_kirim_kecamatan,
		// 								do.delivery_order_draft_kirim_kelurahan,
		// 								do.delivery_order_draft_kirim_kodepos,
		// 								do.delivery_order_draft_kirim_area,
		// 								do.tipe_delivery_order_id,
		// 								tipe.tipe_delivery_order_nama,
		// 								do.delivery_order_draft_status
		// 							FROM delivery_order_draft do
		// 							LEFT JOIN tipe_delivery_order tipe
		// 							ON tipe.tipe_delivery_order_id = do.tipe_delivery_order_id
		// 							INNER JOIN sales_order so
		// 							ON so.sales_order_id = do.sales_order_id
		// 							WHERE FORMAT(so.sales_order_tgl,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
		// 							AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
		// 							ORDER BY FORMAT(do.delivery_order_draft_tgl_surat_jalan,'dd-MM-yyyy'), do.delivery_order_draft_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_CanvasNotInDO($tgl, $perusahaan)
	{

		$query = $this->db->query("SELECT
									canvas.canvas_id,
									canvas.canvas_kode,
									canvas.client_wms_id,
									'0' AS sales_order_tipe_pembayaran,
									FORMAT(canvas.canvas_requestdate, 'dd-MM-yyyy') AS canvas_requestdate,
									ISNULL(FORMAT(do.delivery_order_draft_tgl_surat_jalan, 'dd-MM-yyyy'),'') AS delivery_order_draft_tgl_surat_jalan,
									canvas.depo_id,
									'B608AD49-2E8E-4289-8463-EC90DAAFB971' AS tipe_delivery_order_id,
									do.delivery_order_draft_id,
									ISNULL(do.delivery_order_draft_kode,'') AS delivery_order_draft_kode,
									canvas.canvas_status,
									'81887BB5-AD0F-45AF-BDE9-C2AE49061510' AS tipe_sales_order_id,
									'CANVAS' tipe_sales_order_nama
									FROM canvas
									LEFT JOIN delivery_order_draft do ON do.canvas_id = canvas.canvas_id
									WHERE canvas.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND FORMAT(canvas.canvas_requestdate, 'yyyy-MM-dd') = '$tgl'
									AND canvas.canvas_status IN ('Approved', 'In Progress')
									AND do.delivery_order_draft_id IS NULL
									AND canvas.canvas_id IN (SELECT
									canvas.canvas_id
									FROM FAS.dbo.canvas
									INNER JOIN FAS.dbo.client_wms perusahaan
									ON perusahaan.client_wms_id = canvas.client_wms_id
									WHERE perusahaan.client_wms_kode = '$perusahaan')
									ORDER BY FORMAT(canvas.canvas_requestdate, 'dd-MM-yyyy'), canvas.canvas_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_CanvasInDO($tgl, $perusahaan)
	{
		$query = $this->db->query("SELECT
									canvas.canvas_id,
									canvas.canvas_kode,
									canvas.client_wms_id,
									'0' AS sales_order_tipe_pembayaran,
									FORMAT(canvas.canvas_requestdate, 'dd-MM-yyyy') AS canvas_requestdate,
									FORMAT(do.delivery_order_draft_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_draft_tgl_surat_jalan,
									canvas.depo_id,
									do.tipe_delivery_order_id,
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									canvas.canvas_status,
									'81887BB5-AD0F-45AF-BDE9-C2AE49061510' AS tipe_sales_order_id,
									'CANVAS' tipe_sales_order_nama
									FROM canvas
									LEFT JOIN delivery_order_draft do ON do.canvas_id = canvas.canvas_id
									WHERE canvas.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND FORMAT(canvas.canvas_requestdate, 'yyyy-MM-dd') = '$tgl'
									AND canvas.canvas_status IN ('Approved', 'In Progress')
									AND do.delivery_order_draft_id IS NOT NULL
									AND canvas.canvas_id IN (SELECT
									canvas.canvas_id
									FROM FAS.dbo.canvas
									INNER JOIN FAS.dbo.client_wms perusahaan
									ON perusahaan.client_wms_id = canvas.client_wms_id
									WHERE perusahaan.client_wms_kode = '$perusahaan')
									ORDER BY FORMAT(canvas.canvas_requestdate, 'dd-MM-yyyy'), canvas.canvas_kode ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_sales_order($tgl)
	{
		$fas = $this->load->database('fas', TRUE);

		$query = $fas->query("SELECT
									hdr.sales_order_id,
									hdr.depo_id,
									hdr.sales_order_kode,
									ISNULL(CONVERT(nvarchar(36), dtl.client_wms_id), '') client_wms_id,
									ISNULL(CONVERT(nvarchar(36), hdr.channel_id), '') channel_id,
									hdr.sales_order_is_handheld,
									hdr.sales_order_status,
									ISNULL(hdr.sales_order_approved_by, '') AS sales_order_approved_by,
									ISNULL(CONVERT(nvarchar(36), hdr.sales_id), '') sales_id,
									ISNULL(CONVERT(nvarchar(36), hdr.client_pt_id), '') client_pt_id,
									ISNULL(hdr.sales_order_tgl, '') sales_order_tgl,
									ISNULL(hdr.sales_order_tgl_exp, '') sales_order_tgl_exp,
									ISNULL(hdr.sales_order_tgl_harga, '') sales_order_tgl_harga,
									ISNULL(hdr.sales_order_tgl_sj, '') sales_order_tgl_sj,
									ISNULL(hdr.sales_order_tgl_kirim, '') sales_order_tgl_kirim,
									ISNULL(hdr.sales_order_tipe_pembayaran, '') sales_order_tipe_pembayaran,
									ISNULL(CONVERT(nvarchar(36), hdr.tipe_sales_order_id), '') tipe_sales_order_id,
									ISNULL(hdr.sales_order_no_po, '') sales_order_no_po,
									ISNULL(hdr.sales_order_who_create, '') sales_order_who_create,
									ISNULL(hdr.sales_order_tgl_create, '') sales_order_tgl_create,
									hdr.sales_order_is_downloaded,
									ISNULL(CONVERT(nvarchar(36), hdr.tipe_delivery_order_id), '') tipe_delivery_order_id,
									hdr.sales_order_is_uploaded,
									ISNULL(SUM(dtl.sku_harga_nett), 0) AS sku_harga_nett,
									CASE WHEN SUM(dtl.is_promo) > 0 THEN 1 ELSE 0 END is_promo,
									NULL AS is_canvas
									FROM sales_order hdr
									LEFT JOIN sales_order_detail dtl
									ON dtl.sales_order_id = hdr.sales_order_id
									WHERE FORMAT(hdr.sales_order_tgl_kirim, 'yyyy-MM-dd') = '$tgl'
									GROUP BY hdr.sales_order_id,
											hdr.depo_id,
											hdr.sales_order_kode,
											ISNULL(CONVERT(nvarchar(36), dtl.client_wms_id), ''),
											ISNULL(CONVERT(nvarchar(36), hdr.channel_id), ''),
											hdr.sales_order_is_handheld,
											hdr.sales_order_status,
											ISNULL(hdr.sales_order_approved_by, ''),
											ISNULL(CONVERT(nvarchar(36), hdr.sales_id), ''),
											ISNULL(CONVERT(nvarchar(36), hdr.client_pt_id), ''),
											ISNULL(hdr.sales_order_tgl, ''),
											ISNULL(hdr.sales_order_tgl_exp, ''),
											ISNULL(hdr.sales_order_tgl_harga, ''),
											ISNULL(hdr.sales_order_tgl_sj, ''),
											ISNULL(hdr.sales_order_tgl_kirim, ''),
											ISNULL(hdr.sales_order_tipe_pembayaran, ''),
											ISNULL(CONVERT(nvarchar(36), hdr.tipe_sales_order_id), ''),
											ISNULL(hdr.sales_order_no_po, ''),
											ISNULL(hdr.sales_order_who_create, ''),
											ISNULL(hdr.sales_order_tgl_create, ''),
											hdr.sales_order_is_downloaded,
											ISNULL(CONVERT(nvarchar(36), hdr.tipe_delivery_order_id), ''),
											hdr.sales_order_is_uploaded
									ORDER BY ISNULL(hdr.sales_order_tgl, '') ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $fas->last_query();
	}

	public function Get_sales_order_wms($tgl, $perusahaan_eksternal, $principle)
	{

		$perusahaan_eksternal = $perusahaan_eksternal != "" ? " AND cw.client_wms_kode = '" . $perusahaan_eksternal . "' " : "";
		$principle = $principle != "" ? " AND principle.principle_kode = '" . $principle . "' " : "";

		$query = $this->db->query("SELECT
									hdr.sales_order_id,
									hdr.depo_id,
									hdr.sales_order_kode,
									ISNULL(CONVERT(nvarchar(36), dtl.client_wms_id), '') client_wms_id,
									ISNULL(CONVERT(nvarchar(36), hdr.channel_id), '') channel_id,
									hdr.sales_order_is_handheld,
									hdr.sales_order_status,
									ISNULL(hdr.sales_order_approved_by, '') AS sales_order_approved_by,
									ISNULL(CONVERT(nvarchar(36), hdr.sales_id), '') sales_id,
									ISNULL(CONVERT(nvarchar(36), hdr.client_pt_id), '') client_pt_id,
									ISNULL(hdr.sales_order_tgl, '') sales_order_tgl,
									ISNULL(hdr.sales_order_tgl_exp, '') sales_order_tgl_exp,
									ISNULL(hdr.sales_order_tgl_harga, '') sales_order_tgl_harga,
									ISNULL(hdr.sales_order_tgl_sj, '') sales_order_tgl_sj,
									ISNULL(hdr.sales_order_tgl_kirim, '') sales_order_tgl_kirim,
									ISNULL(hdr.sales_order_tipe_pembayaran, '') sales_order_tipe_pembayaran,
									ISNULL(CONVERT(nvarchar(36), hdr.tipe_sales_order_id), '') tipe_sales_order_id,
									ISNULL(hdr.sales_order_no_po, '') sales_order_no_po,
									ISNULL(hdr.sales_order_who_create, '') sales_order_who_create,
									ISNULL(hdr.sales_order_tgl_create, '') sales_order_tgl_create,
									hdr.sales_order_is_downloaded,
									ISNULL(CONVERT(nvarchar(36), hdr.tipe_delivery_order_id), '') tipe_delivery_order_id,
									hdr.sales_order_is_uploaded,
									ISNULL(hdr.total_faktur, 0) AS sku_harga_nett,
									CASE WHEN SUM(dtl.is_promo) > 0 THEN 1 ELSE 0 END is_promo,
									NULL AS is_canvas,
									ISNULL(hdr.is_priority, 0) AS is_priority
									FROM sales_order hdr
									LEFT JOIN sales_order_detail dtl
									ON dtl.sales_order_id = hdr.sales_order_id
									LEFT JOIN sku
									ON sku.sku_id = dtl.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN client_wms cw
									ON cw.client_wms_id = hdr.client_wms_id
									WHERE FORMAT(hdr.sales_order_tgl_kirim, 'yyyy-MM-dd') = '$tgl'
									AND hdr.sales_order_status = 'Approved' " . $perusahaan_eksternal . " " . $principle . "
									GROUP BY hdr.sales_order_id,
											hdr.depo_id,
											hdr.sales_order_kode,
											ISNULL(CONVERT(nvarchar(36), dtl.client_wms_id), ''),
											ISNULL(CONVERT(nvarchar(36), hdr.channel_id), ''),
											hdr.sales_order_is_handheld,
											hdr.sales_order_status,
											ISNULL(hdr.sales_order_approved_by, ''),
											ISNULL(CONVERT(nvarchar(36), hdr.sales_id), ''),
											ISNULL(CONVERT(nvarchar(36), hdr.client_pt_id), ''),
											ISNULL(hdr.sales_order_tgl, ''),
											ISNULL(hdr.sales_order_tgl_exp, ''),
											ISNULL(hdr.sales_order_tgl_harga, ''),
											ISNULL(hdr.sales_order_tgl_sj, ''),
											ISNULL(hdr.sales_order_tgl_kirim, ''),
											ISNULL(hdr.sales_order_tipe_pembayaran, ''),
											ISNULL(CONVERT(nvarchar(36), hdr.tipe_sales_order_id), ''),
											ISNULL(hdr.sales_order_no_po, ''),
											ISNULL(hdr.sales_order_who_create, ''),
											ISNULL(hdr.sales_order_tgl_create, ''),
											hdr.sales_order_is_downloaded,
											ISNULL(CONVERT(nvarchar(36), hdr.tipe_delivery_order_id), ''),
											hdr.sales_order_is_uploaded,
											ISNULL(hdr.is_priority, 0),
											ISNULL(hdr.total_faktur, 0)
									ORDER BY ISNULL(hdr.sales_order_tgl, '') ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_sales_order_detail($so_id, $perusahaan)
	{
		$fas = $this->load->database('fas', TRUE);

		$query = $fas->query("SELECT
									dtl.sales_order_detail_id,
									dtl.sales_order_id,
									ISNULL(CONVERT(nvarchar(36), dtl.client_wms_id), '') client_wms_id,
									ISNULL(CONVERT(nvarchar(36), dtl.sku_id), '') sku_id,
									ISNULL(dtl.sku_kode, '') sku_kode,
									ISNULL(dtl.sku_nama_produk, '') sku_nama_produk,
									ISNULL(dtl.sku_harga_satuan, '0') sku_harga_satuan,
									ISNULL(dtl.sku_disc_percent, '0') sku_disc_percent,
									ISNULL(dtl.sku_disc_rp, '0') sku_disc_rp,
									ISNULL(dtl.sku_harga_nett, '0') sku_harga_nett,
									ISNULL(dtl.sku_request_expdate, '0') sku_request_expdate,
									ISNULL(dtl.sku_filter_expdate, '0') sku_filter_expdate,
									ISNULL(dtl.sku_filter_expdatebulan, '0') sku_filter_expdatebulan,
									ISNULL(dtl.sku_filter_expdatetahun, '0') sku_filter_expdatetahun,
									ISNULL(dtl.sku_weight, '0') sku_weight,
									ISNULL(dtl.sku_weight_unit, '') sku_weight_unit,
									ISNULL(dtl.sku_length, '0') sku_length,
									ISNULL(dtl.sku_length_unit, '') sku_length_unit,
									ISNULL(dtl.sku_width, '0') sku_width,
									ISNULL(dtl.sku_width_unit, '') sku_width_unit,
									ISNULL(dtl.sku_height, '0') sku_height,
									ISNULL(dtl.sku_height_unit, '') sku_height_unit,
									ISNULL(dtl.sku_volume, '0') sku_volume,
									ISNULL(dtl.sku_volume_unit, '') sku_volume_unit,
									ISNULL(dtl.sku_qty, '0') sku_qty,
									ISNULL(dtl.sku_keterangan, '') sku_keterangan,
									ISNULL(dtl.tipe_stock_nama, '') tipe_stock_nama,
									ISNULL(dtl.is_promo, '') is_promo
								   FROM sales_order hdr
								   LEFT JOIN sales_order_detail dtl
								   ON dtl.sales_order_id = hdr.sales_order_id 
								   INNER JOIN client_wms perusahaan
								   ON perusahaan.client_wms_id = dtl.client_wms_id
								   WHERE dtl.sales_order_id = '$so_id'
								   AND perusahaan.client_wms_kode = '$perusahaan'
								   ORDER BY hdr.sales_order_tgl ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_sales_order_detail_wms($so_id, $perusahaan)
	{
		$query = $this->db->query("SELECT
									dtl.sales_order_detail_id,
									dtl.sales_order_id,
									ISNULL(CONVERT(nvarchar(36), dtl.client_wms_id), '') client_wms_id,
									ISNULL(CONVERT(nvarchar(36), dtl.sku_id), '') sku_id,
									ISNULL(dtl.sku_kode, '') sku_kode,
									ISNULL(dtl.sku_nama_produk, '') sku_nama_produk,
									ISNULL(dtl.sku_harga_satuan, '0') sku_harga_nett,
									ISNULL(dtl.sku_disc_percent, '0') sku_disc_percent,
									ISNULL(dtl.sku_disc_rp, '0') sku_disc_rp,
									ISNULL(dtl.sku_harga_nett, '0') sku_harga_nett,
									ISNULL(dtl.sku_request_expdate, '0') sku_request_expdate,
									ISNULL(dtl.sku_filter_expdate, '0') sku_filter_expdate,
									ISNULL(dtl.sku_filter_expdatebulan, '0') sku_filter_expdatebulan,
									ISNULL(dtl.sku_filter_expdatetahun, '0') sku_filter_expdatetahun,
									ISNULL(dtl.sku_weight, '0') sku_weight,
									ISNULL(dtl.sku_weight_unit, '') sku_weight_unit,
									ISNULL(dtl.sku_length, '0') sku_length,
									ISNULL(dtl.sku_length_unit, '') sku_length_unit,
									ISNULL(dtl.sku_width, '0') sku_width,
									ISNULL(dtl.sku_width_unit, '') sku_width_unit,
									ISNULL(dtl.sku_height, '0') sku_height,
									ISNULL(dtl.sku_height_unit, '') sku_height_unit,
									ISNULL(dtl.sku_volume, '0') sku_volume,
									ISNULL(dtl.sku_volume_unit, '') sku_volume_unit,
									ISNULL(dtl.sku_qty, 0) sku_qty,
									ISNULL(dtl.sku_keterangan, '') sku_keterangan,
									ISNULL(dtl.tipe_stock_nama, '') tipe_stock_nama,
									ISNULL(dtl.is_promo, '') is_promo
								   FROM sales_order hdr
								   LEFT JOIN sales_order_detail dtl
								   ON dtl.sales_order_id = hdr.sales_order_id 
								   INNER JOIN client_wms perusahaan
								   ON perusahaan.client_wms_id = dtl.client_wms_id
								   WHERE dtl.sales_order_id = '$so_id'
								   AND perusahaan.client_wms_id = '$perusahaan'
								   AND hdr.sales_order_status = 'Approved'
								   ORDER BY hdr.sales_order_tgl ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_sales_order_detail2_wms($sales_order_detail_id)
	{
		$query = $this->db->query("SELECT
									sales_order_detail2_id,
									sales_order_detail_id,
									sku_id,
									sku_stock_id,
									sku_expdate,
									sku_qty
								   FROM sales_order_detail_2
								   WHERE sales_order_detail_id = '$sales_order_detail_id'
								   ORDER BY sku_id ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_client_wms_so($so_id)
	{
		$query = $this->db->query("SELECT
									sales_order_id,
									client_wms_id,
									SUM(sku_harga_nett) AS sku_harga_nett
									FROM sales_order_detail
									WHERE sales_order_id = '$so_id'
									GROUP BY sales_order_id,client_wms_id");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Insert_sales_order($data)
	{
		$sales_order_id = $data['sales_order_id'] == '' ? null : $data['sales_order_id'];
		$depo_id = $data['depo_id'] == '' ? null : $data['depo_id'];
		$sales_order_kode = $data['sales_order_kode'] == '' ? null : $data['sales_order_kode'];
		$client_wms_id = $data['client_wms_id'] == '' ? null : $data['client_wms_id'];
		$channel_id = $data['channel_id'] == '' ? null : $data['channel_id'];
		$sales_order_is_handheld = $data['sales_order_is_handheld'] == '' ? null : $data['sales_order_is_handheld'];
		$sales_order_status = $data['sales_order_status'] == '' ? null : $data['sales_order_status'];
		$sales_order_approved_by = $data['sales_order_approved_by'] == '' ? null : $data['sales_order_approved_by'];
		$sales_id = $data['sales_id'] == '' ? null : $data['sales_id'];
		$client_pt_id = $data['client_pt_id'] == '' ? null : $data['client_pt_id'];
		$sales_order_tgl = $data['sales_order_tgl'] == '' ? null : $data['sales_order_tgl'];
		$sales_order_tgl_exp = $data['sales_order_tgl_exp'] == '' ? null : $data['sales_order_tgl_exp'];
		$sales_order_tgl_harga = $data['sales_order_tgl_harga'] == '' ? null : $data['sales_order_tgl_harga'];
		$sales_order_tgl_sj = $data['sales_order_tgl_sj'] == '' ? null : $data['sales_order_tgl_sj'];
		$sales_order_tgl_kirim = $data['sales_order_tgl_kirim'] == '' ? null : $data['sales_order_tgl_kirim'];
		$sales_order_tipe_pembayaran = $data['sales_order_tipe_pembayaran'] == '' ? null : $data['sales_order_tipe_pembayaran'];
		$tipe_sales_order_id = $data['tipe_sales_order_id'] == '' ? null : $data['tipe_sales_order_id'];
		$sales_order_no_po = $data['sales_order_no_po'] == '' ? null : $data['sales_order_no_po'];
		$sales_order_who_create = $data['sales_order_who_create'] == '' ? null : $data['sales_order_who_create'];
		$sales_order_tgl_create = $data['sales_order_tgl_create'] == '' ? null : $data['sales_order_tgl_create'];
		$sales_order_is_downloaded = $data['sales_order_is_downloaded'] == '' ? null : $data['sales_order_is_downloaded'];
		$tipe_delivery_order_id = $data['tipe_delivery_order_id'] == '' ? null : $data['tipe_delivery_order_id'];
		$sales_order_is_uploaded = $data['sales_order_is_uploaded'] == '' ? null : $data['sales_order_is_uploaded'];
		$sales_order_no_reff = $data['sales_order_no_reff'] == '' ? null : $data['sales_order_no_reff'];

		$this->db->set('sales_order_id', $sales_order_id);
		$this->db->set('depo_id', $depo_id);
		$this->db->set('sales_order_kode', $sales_order_kode);
		$this->db->set('client_wms_id', $client_wms_id);
		$this->db->set('channel_id', $channel_id);
		$this->db->set('sales_order_is_handheld', $sales_order_is_handheld);
		$this->db->set('sales_order_status', $sales_order_status);
		$this->db->set('sales_order_approved_by', $sales_order_approved_by);
		$this->db->set('sales_id', $sales_id);
		$this->db->set('client_pt_id', $client_pt_id);
		$this->db->set('sales_order_tgl', $sales_order_tgl);
		$this->db->set('sales_order_tgl_exp', $sales_order_tgl_exp);
		$this->db->set('sales_order_tgl_harga', $sales_order_tgl_harga);
		$this->db->set('sales_order_tgl_sj', $sales_order_tgl_sj);
		$this->db->set('sales_order_tgl_kirim', $sales_order_tgl_kirim);
		$this->db->set('sales_order_tipe_pembayaran', $sales_order_tipe_pembayaran);
		$this->db->set('tipe_sales_order_id', $tipe_sales_order_id);
		$this->db->set('sales_order_no_po', $sales_order_no_po);
		$this->db->set('sales_order_who_create', $sales_order_who_create);
		$this->db->set('sales_order_tgl_create', $sales_order_tgl_create);
		$this->db->set('sales_order_is_downloaded', $sales_order_is_downloaded);
		$this->db->set('tipe_delivery_order_id', $tipe_delivery_order_id);
		$this->db->set('sales_order_is_uploaded', $sales_order_is_uploaded);
		$this->db->set('sales_order_no_reff', $sales_order_no_reff);

		$this->db->insert("sales_order");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;

		// return $this->db->last_query();
	}

	public function Insert_sales_order_detail($data)
	{
		$sales_order_detail_id = $data['sales_order_detail_id'] == '' ? null : $data['sales_order_detail_id'];
		$sales_order_id = $data['sales_order_id'] == '' ? null : $data['sales_order_id'];
		$client_wms_id = $data['client_wms_id'] == '' ? null : $data['client_wms_id'];
		$sku_id = $data['sku_id'] == '' ? null : $data['sku_id'];
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
		$tipe_stock_nama = $data['tipe_stock_nama'] == '' ? null : $data['tipe_stock_nama'];
		$is_promo = $data['is_promo'] == '' ? null : $data['is_promo'];

		$this->db->set('sales_order_detail_id', $sales_order_detail_id);
		$this->db->set('sales_order_id', $sales_order_id);
		$this->db->set('client_wms_id', $client_wms_id);
		$this->db->set('sku_id', $sku_id);
		$this->db->set('sku_kode', $sku_kode);
		$this->db->set('sku_nama_produk', $sku_nama_produk);
		$this->db->set('sku_harga_satuan', $sku_harga_satuan);
		$this->db->set('sku_disc_percent', $sku_disc_percent);
		$this->db->set('sku_disc_rp', $sku_disc_rp);
		$this->db->set('sku_harga_nett', $sku_harga_nett);
		$this->db->set('sku_request_expdate', $sku_request_expdate);
		$this->db->set('sku_filter_expdate', $sku_filter_expdate);
		$this->db->set('sku_filter_expdatebulan', $sku_filter_expdatebulan);
		$this->db->set('sku_filter_expdatetahun', $sku_filter_expdatetahun);
		$this->db->set('sku_weight', $sku_weight);
		$this->db->set('sku_weight_unit', $sku_weight_unit);
		$this->db->set('sku_length', $sku_length);
		$this->db->set('sku_length_unit', $sku_length_unit);
		$this->db->set('sku_width', $sku_width);
		$this->db->set('sku_width_unit', $sku_width_unit);
		$this->db->set('sku_height', $sku_height);
		$this->db->set('sku_height_unit', $sku_height_unit);
		$this->db->set('sku_volume', $sku_volume);
		$this->db->set('sku_volume_unit', $sku_volume_unit);
		$this->db->set('sku_qty', $sku_qty);
		$this->db->set('sku_keterangan', $sku_keterangan);
		$this->db->set('tipe_stock_nama', $tipe_stock_nama);
		$this->db->set('is_promo', $is_promo);

		$this->db->insert("sales_order_detail");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_sales_order_internal($tgl, $perusahaan, $principle)
	{
		// $perusahaan = $perusahaan != "" ? $perusahaan = " AND perusahaan.client_wms_kode = '$perusahaan' " : "";
		// $principle = $principle != "" ? $principle = " AND principle.principle_kode = '$principle' " : "";

		// $this->db->query("INSERT INTO sales_order
		// 					SELECT
		// 					*
		// 					FROM FAS.dbo.sales_order
		// 					WHERE FORMAT(sales_order_tgl_kirim, 'yyyy-MM-dd') = '$tgl'
		// 					AND sales_order_id IN (SELECT DISTINCT
		// 					so.sales_order_id
		// 					FROM sales_order_detail so
		// 					INNER JOIN FAS.dbo.client_wms perusahaan
		// 					ON perusahaan.client_wms_id = so.client_wms_id
		// 					LEFT JOIN sku
		// 					ON sku.sku_id = so.sku_id
		// 					LEFT JOIN principle
		// 					ON principle.principle_id = sku.principle_id
		// 					WHERE so.sales_order_detail_id IS NOT NULL
		// 					" . $perusahaan . "
		// 					" . $principle . ")
		// 					AND sales_order_status = 'Approved'
		// 					AND sales_order_id NOT IN (select sales_order_id from sales_order)");

		$perusahaan = $perusahaan != "" ? $perusahaan = " AND b.client_wms_kode = '$perusahaan' " : "";
		$principle = $principle != "" ? $principle = " AND c.principle_kode = '$principle' " : "";

		$this->db->query("INSERT INTO sales_order
							SELECT a.*
							FROM FAS.dbo.sales_order a
							INNER JOIN client_wms b on a.client_wms_id = b.client_wms_id
							INNER JOIN principle c on a.principle_id = c.principle_id
							INNER JOIN tipe_sales_order d on a.tipe_sales_order_id = d.tipe_sales_order_id
							WHERE FORMAT(sales_order_tgl_kirim, 'yyyy-MM-dd') = '$tgl'
							" . $perusahaan . "
							" . $principle . "
							AND a.sales_order_status = 'Approved'
							AND d.tipe_sales_order_nama IN ('RETUR', 'BELI')
							AND a.sales_order_id NOT IN (select sales_order_id from sales_order)");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		$response = [
			'result' => $queryinsert,
			'affectedrows' => $affectedrows
		];

		return $response;
	}

	public function insert_sales_order_detail_internal($tgl, $perusahaan, $principle)
	{
		$perusahaan = $perusahaan != "" ? $perusahaan = " AND perusahaan.client_wms_kode = '$perusahaan' " : "";
		$principle = $principle != "" ? $principle = " AND principle.principle_kode = '$principle' " : "";

		$query = $this->db->query("INSERT INTO sales_order_detail
									SELECT
										so.*
										FROM FAS.dbo.sales_order_detail so
										INNER JOIN FAS.dbo.client_wms perusahaan
										ON perusahaan.client_wms_id = so.client_wms_id
										LEFT JOIN sku
										ON sku.sku_id = so.sku_id
										LEFT JOIN principle
										ON principle.principle_id = sku.principle_id
										WHERE sales_order_id IN (SELECT
										sales_order_id
										FROM FAS.dbo.sales_order a
										INNER JOIN tipe_sales_order b on a.tipe_sales_order_id = b.tipe_sales_order_id
										WHERE FORMAT(sales_order_tgl_kirim,'yyyy-MM-dd') = '$tgl' AND sales_order_status = 'Approved'
										AND b.tipe_sales_order_nama IN ('RETUR', 'BELI'))
										" . $perusahaan . "
										" . $principle . "
										AND so.sales_order_detail_id NOT IN (select sales_order_detail_id from sales_order_detail)");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();

	}

	public function insert_sales_order_detail2_internal($tgl, $perusahaan, $principle)
	{
		$perusahaan = $perusahaan != "" ? $perusahaan = " AND perusahaan.client_wms_kode = '$perusahaan' " : "";
		$principle = $principle != "" ? $principle = " AND principle.principle_kode = '$principle' " : "";

		$query = $this->db->query("INSERT INTO sales_order_detail_2 (sales_order_detail2_id,
										sales_order_detail_id,
										sku_id,
										sku_stock_id,
										sku_expdate,
										sku_qty,
										delivery_order_reff_id)
										SELECT
											sales_order_detail2_id,
											sales_order_detail_id,
											sku_id,
											sku_stock_id,
											sku_expdate,
											sku_qty,
											delivery_order_reff_id
										FROM FAS.dbo.sales_order_detail_2
										WHERE sales_order_detail_id IN (SELECT
											sales_order_detail_id
										FROM FAS.dbo.sales_order_detail
										INNER JOIN FAS.dbo.client_wms perusahaan
										ON perusahaan.client_wms_id = sales_order_detail.client_wms_id
										LEFT JOIN sku
										ON sku.sku_id = sales_order_detail.sku_id
										LEFT JOIN principle
										ON principle.principle_id = sku.principle_id
										WHERE sales_order_id IN (SELECT
											sales_order_id
										FROM FAS.dbo.sales_order a
										INNER JOIN tipe_sales_order b on a.tipe_sales_order_id = b.tipe_sales_order_id
										WHERE FORMAT(sales_order_tgl_kirim, 'yyyy-MM-dd') = '$tgl'
										AND b.tipe_sales_order_nama = 'RETUR'
										AND sales_order_status = 'Approved'
										) " . $perusahaan . " " . $principle . ")
										AND sales_order_detail2_id NOT IN (select sales_order_detail2_id from sales_order_detail_2) ");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();

	}

	public function Insert_sales_order_detail2($dodd_id, $dod_id, $so_id)
	{
		$query = $this->db->query("INSERT INTO delivery_order_detail2_draft
									SELECT
									NEWID(),
									'$dodd_id',
									'$dod_id',
									so_detail2.sku_id,
									so_detail2.sku_stock_id,
									so_detail2.sku_expdate,
									so_detail2.sku_qty
									FROM sales_order so
									LEFT JOIN sales_order_detail so_detail
									ON so_detail.sales_order_id = so.sales_order_id
									LEFT JOIN sales_order_detail_2 so_detail2
									ON so_detail2.sales_order_detail_id = so_detail.sales_order_detail_id
									WHERE so.sales_order_id = '$so_id'");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $this->db->last_query();
		return $queryinsert;
	}

	public function Insert_delivery_order($dod_id, $do_kode, $client_wms_id, $delivery_order_draft_nominal_tunai, $client_pt_id, $client_pt_nama, $client_pt_telepon, $client_pt_alamat, $client_pt_kelurahan, $client_pt_kecamatan, $client_pt_kota, $client_pt_propinsi, $client_pt_kodepos, $area_nama, $data, $type)
	{

		$sales_order_id = $data['sales_order_id'] == '' ? null : $data['sales_order_id'];
		$client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
		// $sales_order_tipe_pembayaran = $data['sales_order_tipe_pembayaran'] == '' ? null : $data['sales_order_tipe_pembayaran'];
		$sales_order_tipe_pembayaran = $data['sales_order_tipe_pembayaran'];
		$depo_id = $data['depo_id'] == '' ? null : $data['depo_id'];
		// $client_pt_id = $data['client_pt_id'] == '' ? null : $data['client_pt_id'];
		$sales_order_tgl = $data['sales_order_tgl'] == '' ? null : $data['sales_order_tgl'];
		$sales_order_tgl_exp = $data['sales_order_tgl_exp'] == '' ? null : $data['sales_order_tgl_exp'];
		$sales_order_tgl_harga = $data['sales_order_tgl_harga'] == '' ? null : $data['sales_order_tgl_harga'];
		$sales_order_tgl_sj = $data['sales_order_tgl_sj'] == '' ? null : $data['sales_order_tgl_sj'];
		$sales_order_tgl_kirim = $data['sales_order_tgl_kirim'] == '' ? null : $data['sales_order_tgl_kirim'];
		$sales_order_is_priority = $data['is_priority'] == '' ? 0 : $data['is_priority'];
		// $client_pt_id = $data['client_pt_id'] == '' ? null : $data['client_pt_id'];
		// $client_pt_nama = $data['client_pt_nama'] == '' ? null : $data['client_pt_nama'];
		// $client_pt_alamat = $data['client_pt_alamat'] == '' ? null : $data['client_pt_alamat'];
		// $client_pt_telepon = $data['client_pt_telepon'] == '' ? null : $data['client_pt_telepon'];
		// $client_pt_propinsi = $data['client_pt_propinsi'] == '' ? null : $data['client_pt_propinsi'];
		// $client_pt_kota = $data['client_pt_kota'] == '' ? null : $data['client_pt_kota'];
		// $client_pt_kecamatan = $data['client_pt_kecamatan'] == '' ? null : $data['client_pt_kecamatan'];
		// $client_pt_kelurahan = $data['client_pt_kelurahan'] == '' ? null : $data['client_pt_kelurahan'];
		// $client_pt_kodepos = $data['client_pt_kodepos'] == '' ? null : $data['client_pt_kodepos'];
		$client_pt_id = $client_pt_id == '' ? null : $client_pt_id;
		$client_pt_nama = $client_pt_nama == '' ? null : $client_pt_nama;
		$client_pt_alamat = $client_pt_alamat == '' ? null : $client_pt_alamat;
		$client_pt_telepon = $client_pt_telepon == '' ? null : $client_pt_telepon;
		$client_pt_propinsi = $client_pt_propinsi == '' ? null : $client_pt_propinsi;
		$client_pt_kota = $client_pt_kota == '' ? null : $client_pt_kota;
		$client_pt_kecamatan = $client_pt_kecamatan == '' ? null : $client_pt_kecamatan;
		$client_pt_kelurahan = $client_pt_kelurahan == '' ? null : $client_pt_kelurahan;
		$client_pt_kodepos = $client_pt_kodepos == '' ? null : $client_pt_kodepos;
		$area_nama = $area_nama == '' ? null : $area_nama;
		// $area_nama = $data['area_nama'] == '' ? null : $data['area_nama'];
		$tipe_delivery_order_id = $data['tipe_delivery_order_id'] == '' ? null : $data['tipe_delivery_order_id'];
		$delivery_order_draft_nominal_tunai = $delivery_order_draft_nominal_tunai == '' ? null : $delivery_order_draft_nominal_tunai;
		// $delivery_order_draft_nominal_tunai = $data['nominal_tunai'] == '' ? null : $data['nominal_tunai'];
		$is_promo = $data['is_promo'];
		$is_canvas = $data['is_canvas'] == '0' ? null : $data['is_canvas'];

		$this->db->set('delivery_order_draft_id', $dod_id);
		if ($type == 'sales') {
			$this->db->set('sales_order_id', $sales_order_id);
			$this->db->set('delivery_order_draft_status', "Draft");
		} else {
			// $this->db->set('delivery_order_draft_status', "Approved");
			$this->db->set('delivery_order_draft_status', "Draft");
		}
		$this->db->set('delivery_order_draft_kode', $do_kode);
		// $this->db->set('delivery_order_draft_yourref', $delivery_order_draft_yourref);
		$this->db->set('client_wms_id', $client_wms_id);
		$this->db->set('delivery_order_draft_tgl_buat_do', "GETDATE()", FALSE);
		$this->db->set('delivery_order_draft_tgl_expired_do', "DATEADD(day, 7, GETDATE())", FALSE);
		$this->db->set('delivery_order_draft_tgl_surat_jalan', $sales_order_tgl_sj);
		$this->db->set('delivery_order_draft_tgl_rencana_kirim', $sales_order_tgl_kirim);
		$this->db->set('delivery_order_draft_tgl_aktual_kirim', $sales_order_tgl_kirim);
		// $this->db->set('delivery_order_draft_keterangan', $delivery_order_draft_keterangan);
		$this->db->set('delivery_order_draft_is_prioritas', $sales_order_is_priority);
		$this->db->set('delivery_order_draft_is_need_packing', 0);
		$this->db->set('delivery_order_draft_tipe_layanan', "Delivery Only");
		$this->db->set('delivery_order_draft_tipe_pembayaran', $sales_order_tipe_pembayaran);
		$this->db->set('delivery_order_draft_sesi_pengiriman', 0);
		// $this->db->set('delivery_order_draft_request_tgl_kirim', $delivery_order_draft_request_tgl_kirim);
		// $this->db->set('delivery_order_draft_request_jam_kirim', $delivery_order_draft_request_jam_kirim);
		// $this->db->set('tipe_pengiriman_id', $tipe_pengiriman_id);
		// $this->db->set('nama_tipe', $nama_tipe);
		// $this->db->set('confirm_rate', $confirm_rate);
		// $this->db->set('delivery_order_draft_reff_id', $delivery_order_draft_reff_id);
		// $this->db->set('delivery_order_draft_reff_no', $delivery_order_draft_reff_no);
		// $this->db->set('delivery_order_draft_total', $delivery_order_draft_total);
		$this->db->set('unit_mandiri_id', $this->session->userdata('unit_mandiri_id'));
		$this->db->set('depo_id', $this->session->userdata('depo_id'));
		// $this->db->set('client_pt_id', $client_pt_id);
		// $this->db->set('delivery_order_draft_kirim_nama', $client_pt_nama);
		// $this->db->set('delivery_order_draft_kirim_alamat', $client_pt_alamat);
		// $this->db->set('delivery_order_draft_kirim_telp', $client_pt_telepon);
		// $this->db->set('delivery_order_draft_kirim_provinsi', $client_pt_propinsi);
		// $this->db->set('delivery_order_draft_kirim_kota', $client_pt_kota);
		// $this->db->set('delivery_order_draft_kirim_kecamatan', $client_pt_kecamatan);
		// $this->db->set('delivery_order_draft_kirim_kelurahan', $client_pt_kelurahan);
		// $this->db->set('delivery_order_draft_kirim_kodepos', $client_pt_kodepos);
		// $this->db->set('delivery_order_draft_kirim_area', $area_nama);
		$this->db->set('client_pt_id', $data['client_pt_id'] == '' ? null : $data['client_pt_id']);
		// $this->db->set('delivery_order_draft_kirim_nama', $data['client_pt_nama'] == '' ? null : $data['client_pt_nama']);
		// $this->db->set('delivery_order_draft_kirim_alamat', $data['client_pt_alamat'] == '' ? null : $data['client_pt_alamat']);
		// $this->db->set('delivery_order_draft_kirim_telp', $data['client_pt_telepon'] == '' ? null : $data['client_pt_telepon']);
		// $this->db->set('delivery_order_draft_kirim_provinsi', $data['client_pt_propinsi'] == '' ? null : $data['client_pt_propinsi']);
		// $this->db->set('delivery_order_draft_kirim_kota', $data['client_pt_kota'] == '' ? null : $data['client_pt_kota']);
		// $this->db->set('delivery_order_draft_kirim_kecamatan', $data['client_pt_kecamatan'] == '' ? null : $data['client_pt_kecamatan']);
		// $this->db->set('delivery_order_draft_kirim_kelurahan', $data['client_pt_kelurahan'] == '' ? null : $data['client_pt_kelurahan']);
		// $this->db->set('delivery_order_draft_kirim_kodepos', $data['client_pt_kodepos'] == '' ? null : $data['client_pt_kodepos']);
		// $this->db->set('delivery_order_draft_kirim_area', $data['area_kode'] == '' ? null : $data['area_kode']);
		$this->db->set('delivery_order_draft_kirim_nama', $client_pt_nama);
		$this->db->set('delivery_order_draft_kirim_alamat', $client_pt_alamat);
		$this->db->set('delivery_order_draft_kirim_telp', $client_pt_telepon);
		$this->db->set('delivery_order_draft_kirim_provinsi', $client_pt_propinsi);
		$this->db->set('delivery_order_draft_kirim_kota', $client_pt_kota);
		$this->db->set('delivery_order_draft_kirim_kecamatan', $client_pt_kecamatan);
		$this->db->set('delivery_order_draft_kirim_kelurahan', $client_pt_kelurahan);
		$this->db->set('delivery_order_draft_kirim_kodepos', $client_pt_kodepos);
		$this->db->set('delivery_order_draft_kirim_area', $area_nama);
		// $this->db->set('delivery_order_draft_kirim_invoice_pdf', $delivery_order_draft_kirim_invoice_pdf);
		// $this->db->set('delivery_order_draft_kirim_invoice_dir', $delivery_order_draft_kirim_invoice_dir);
		// $this->db->set('pabrik_id', $pabrik_id);
		// $this->db->set('delivery_order_draft_ambil_nama', $delivery_order_draft_ambil_nama);
		// $this->db->set('delivery_order_draft_ambil_alamat', $delivery_order_draft_ambil_alamat);
		// $this->db->set('delivery_order_draft_ambil_telp', $delivery_order_draft_ambil_telp);
		// $this->db->set('delivery_order_draft_ambil_provinsi', $delivery_order_draft_ambil_provinsi);
		// $this->db->set('delivery_order_draft_ambil_kota', $delivery_order_draft_ambil_kota);
		// $this->db->set('delivery_order_draft_ambil_kecamatan', $delivery_order_draft_ambil_kecamatan);
		// $this->db->set('delivery_order_draft_ambil_kelurahan', $delivery_order_draft_ambil_kelurahan);
		// $this->db->set('delivery_order_draft_ambil_kodepos', $delivery_order_draft_ambil_kodepos);
		// $this->db->set('delivery_order_draft_ambil_area', $delivery_order_draft_ambil_area);
		$this->db->set('delivery_order_draft_update_who', $this->session->userdata('pengguna_username'));
		$this->db->set('delivery_order_draft_update_tgl', "GETDATE()", FALSE);
		// $this->db->set('delivery_order_draft_approve_who', $delivery_order_draft_approve_who);
		// $this->db->set('delivery_order_draft_approve_tgl', $delivery_order_draft_approve_tgl);
		// $this->db->set('delivery_order_draft_reject_who', $delivery_order_draft_reject_who);
		// $this->db->set('delivery_order_draft_reject_tgl', $delivery_order_draft_reject_tgl);
		// $this->db->set('delivery_order_draft_reject_reason', $delivery_order_draft_reject_reason);
		$this->db->set('tipe_delivery_order_id', $tipe_delivery_order_id);
		$this->db->set('is_from_so', 1);
		$this->db->set('delivery_order_draft_nominal_tunai', $delivery_order_draft_nominal_tunai);
		// $this->db->set('delivery_order_draft_attachment', $delivery_order_draft_attachment);
		$this->db->set('is_promo', $is_promo);
		$this->db->set('is_canvas', $is_canvas);
		if ($type == 'sales') {
			$this->db->set('canvas_id', null);
		} else {
			$this->db->set('canvas_id', $sales_order_id);
		}
		$this->db->insert("delivery_order_draft");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}
		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_delivery_order_detail($dodd_id, $dod_id, $data)
	{
		$sales_order_id = $data['sales_order_id'] == '' ? null : $data['sales_order_id'];
		$sku_id = $data['sku_id'] == '' ? null : $data['sku_id'];
		$sku_kode = $data['sku_kode'] == '' ? null : $data['sku_kode'];
		$sku_nama_produk = $data['sku_nama_produk'] == '' ? null : $data['sku_nama_produk'];
		// $sku_harga_satuan = $data['sku_harga_satuan'] == '' ? null : $data['sku_harga_satuan'];
		$sku_harga_satuan = $data['sku_harga_nett'] == '' ? null : $data['sku_harga_nett'];
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
		$tipe_stock_nama = $data['tipe_stock_nama'] == '' ? null : $data['tipe_stock_nama'];


		$this->db->set('delivery_order_detail_draft_id', $dodd_id);
		$this->db->set('delivery_order_draft_id', $dod_id);
		$this->db->set('sku_id', $sku_id);
		// $this->db->set('gudang_id', $gudang_id);
		// $this->db->set('gudang_detail_id', $gudang_detail_id);
		$this->db->set('sku_kode', $sku_kode);
		$this->db->set('sku_nama_produk', $sku_nama_produk);
		$this->db->set('sku_harga_satuan', $sku_harga_satuan);
		$this->db->set('sku_disc_percent', $sku_disc_percent);
		$this->db->set('sku_disc_rp', $sku_disc_rp);
		$this->db->set('sku_harga_nett', $sku_harga_nett);
		$this->db->set('sku_request_expdate', $sku_request_expdate);
		$this->db->set('sku_filter_expdate', $sku_filter_expdate);
		$this->db->set('sku_filter_expdatebulan', $sku_filter_expdatebulan);
		$this->db->set('sku_filter_expdatetahun', $sku_filter_expdatetahun);
		$this->db->set('sku_weight', $sku_weight);
		$this->db->set('sku_weight_unit', $sku_weight_unit);
		$this->db->set('sku_length', $sku_length);
		$this->db->set('sku_length_unit', $sku_length_unit);
		$this->db->set('sku_width', $sku_width);
		$this->db->set('sku_width_unit', $sku_width_unit);
		$this->db->set('sku_height', $sku_height);
		$this->db->set('sku_height_unit', $sku_height_unit);
		$this->db->set('sku_volume', $sku_volume);
		$this->db->set('sku_volume_unit', $sku_volume_unit);
		$this->db->set('sku_qty', $sku_qty);
		$this->db->set('sku_keterangan', $sku_keterangan);
		$this->db->set('tipe_stock_nama', $tipe_stock_nama);

		$this->db->insert("delivery_order_detail_draft");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $this->db->last_query();
		return $queryinsert;
	}

	public function Insert_delivery_order_detail2($delivery_order_detail2_draft_id, $delivery_order_detail_draft_id, $delivery_order_draft_id, $data)
	{
		$sku_konversi_faktor = 0;
		$sku_id = $data['sku_id'] == '' ? null : $data['sku_id'];

		if ($sku_id != "") {
			$sku_obj = $this->db->query("SELECT * FROM sku WHERE sku_id = '$sku_id' ");

			if ($sku_obj->num_rows() == 0) {
				$sku_konversi_faktor = 0;
			} else {
				foreach ($sku_obj->result_array() as $val_sku) {
					$sku_konversi_faktor = $val_sku['sku_konversi_faktor'];
				}
			}
		}

		$sku_stock_id = $data['sku_stock_id'] == '' ? null : $data['sku_stock_id'];
		$sku_expdate = $data['sku_expdate'] == '' ? null : $data['sku_expdate'];
		$sku_qty = $data['sku_qty'] == '' ? null : $data['sku_qty'];
		$sku_qty_composite = $data['sku_qty'] == '' ? null : $sku_konversi_faktor * $data['sku_qty'];

		$this->db->set('delivery_order_detail2_draft_id', $delivery_order_detail2_draft_id);
		$this->db->set('delivery_order_detail_draft_id', $delivery_order_detail_draft_id);
		$this->db->set('delivery_order_draft_id', $delivery_order_draft_id);
		$this->db->set('sku_id', $sku_id);
		$this->db->set('sku_stock_id', $sku_stock_id);
		$this->db->set('sku_expdate', $sku_expdate);
		$this->db->set('sku_qty', $sku_qty);
		$this->db->set('sku_qty_composite', $sku_qty_composite);

		$this->db->insert("delivery_order_detail2_draft");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $this->db->last_query();
		return $queryinsert;
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function Check_DO_By_SO($so_id, $client_wms_id)
	{
		$query = $this->db->query("SELECT * FROM delivery_order_draft WHERE sales_order_id = '$so_id' AND client_wms_id = '$client_wms_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
	}

	public function check_so_duplicate($kode)
	{
		$this->db->select("*")
			->from("sales_order")
			->where("sales_order_kode", $kode);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
	}

	public function cek_customer_eksternal($customer_id, $perusahaan_eksternal)
	{
		$this->db->select("*")
			->from("client_pt_eksternal")
			->where("client_pt_eksternal_id", $customer_id)
			->where("perusahaan_eksternal", $perusahaan_eksternal);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
	}

	public function Insert_client_pt($client_pt_id, $client_pt_eksternal_id)
	{
		$query = $this->db->query("INSERT INTO client_pt (
									client_pt_id,
									client_pt_nama,
									client_pt_alamat,
									client_pt_telepon,
									client_pt_propinsi,
									client_pt_kota,
									client_pt_kecamatan,
									client_pt_kelurahan,
									client_pt_kodepos,
									client_pt_latitude,
									client_pt_longitude,
									client_pt_nama_contact_person,
									client_pt_telepon_contact_person,
									client_pt_email_contact_person,
									client_pt_keterangan,
									kelas_jalan_id,
									area_id,
									client_pt_corporate_id,
									client_pt_top,
									client_pt_kredit_limit,
									client_pt_acc,
									client_pt_titik_antar_id,
									client_pt_segmen_id1,
									client_pt_segmen_id2,
									client_pt_segmen_id3,
									unit_mandiri_id,
									client_pt_is_deleted,
									client_pt_is_aktif,
									client_pt_is_multi_lokasi,
									lokasi_outlet_id,
									kelas_jalan2_id)
									SELECT
									'$client_pt_id' AS client_pt_id,
									client_pt_nama,
									client_pt_alamat,
									client_pt_telepon,
									client_pt_propinsi,
									client_pt_kota,
									client_pt_kecamatan,
									client_pt_kelurahan,
									client_pt_kodepos,
									client_pt_latitude,
									client_pt_longitude,
									client_pt_nama AS client_pt_nama_contact_person,
									client_pt_telepon AS client_pt_telepon_contact_person,
									'' AS client_pt_email_contact_person,
									'' AS client_pt_keterangan,
									NULL AS kelas_jalan_id,
									area_detail.area_id AS area_id,
									NULL AS client_pt_corporate_id,
									client_pt_top,
									client_pt_kredit_limit,
									NULL AS client_pt_acc,
									NULL AS client_pt_titik_antar_id,
									NULL AS client_pt_segmen_id1,
									NULL AS client_pt_segmen_id2,
									NULL AS client_pt_segmen_id3,
									NULL AS unit_mandiri_id,
									0 AS client_pt_is_deleted,
									1 AS client_pt_is_aktif,
									NULL AS client_pt_is_multi_lokasi,
									NULL AS lokasi_outlet_id,
									NULL AS kelas_jalan2_id
									FROM client_pt_eksternal
									LEFT JOIN area_detail
									ON area_detail.area_kelurahan = client_pt_eksternal.client_pt_kelurahan
									AND area_detail.area_kecamatan = client_pt_eksternal.client_pt_kecamatan
									AND area_detail.area_kota = client_pt_eksternal.client_pt_kota
									WHERE client_pt_eksternal_id = '$client_pt_eksternal_id'
									GROUP BY client_pt_nama,
											client_pt_alamat,
											client_pt_telepon,
											client_pt_propinsi,
											client_pt_kota,
											client_pt_kecamatan,
											client_pt_kelurahan,
											client_pt_kodepos,
											client_pt_latitude,
											client_pt_longitude,
											client_pt_nama,
											client_pt_telepon,
											area_detail.area_id,
											client_pt_top,
											client_pt_kredit_limit");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_client_pt_eksternal($data, $perusahaan_eksternal)
	{
		$client_pt_id = $data['client_pt_id'] == '' ? null : $data['client_pt_id'];
		$client_pt_nama = $data['client_pt_nama'] == '' ? null : $data['client_pt_nama'];
		$client_pt_alamat = $data['client_pt_alamat'] == '' ? null : $data['client_pt_alamat'];
		$client_pt_telepon = $data['client_pt_telepon'] == '' ? null : $data['client_pt_telepon'];
		$client_pt_propinsi = $data['client_pt_propinsi'] == '' ? null : $data['client_pt_propinsi'];
		$client_pt_kota = $data['client_pt_kota'] == '' ? null : $data['client_pt_kota'];
		$client_pt_kecamatan = $data['client_pt_kecamatan'] == '' ? null : $data['client_pt_kecamatan'];
		$client_pt_kelurahan = $data['client_pt_kelurahan'] == '' ? null : $data['client_pt_kelurahan'];
		$client_pt_kodepos = $data['client_pt_kodepos'] == '' ? null : $data['client_pt_kodepos'];
		$client_pt_latitude = $data['client_pt_latitude'] == '' ? null : $data['client_pt_latitude'];
		$client_pt_longitude = $data['client_pt_longitude'] == '' ? null : $data['client_pt_longitude'];
		$client_pt_nama_contact_person = $data['client_pt_nama_contact_person'] == '' ? null : $data['client_pt_nama_contact_person'];
		$client_pt_telepon_contact_person = $data['client_pt_telepon_contact_person'] == '' ? null : $data['client_pt_telepon_contact_person'];
		$client_pt_email_contact_person = $data['client_pt_email_contact_person'] == '' ? null : $data['client_pt_email_contact_person'];
		$client_pt_keterangan = $data['client_pt_keterangan'] == '' ? null : $data['client_pt_keterangan'];
		$kelas_jalan_id = $data['kelas_jalan_id'] == '' ? null : $data['kelas_jalan_id'];
		$area_id = $data['area_id'] == '' ? null : $data['area_id'];
		// $area_id = $data['area_id'] == '' ? null : $data['area_id'];
		$client_pt_corporate_id = $data['client_pt_corporate_id'] == '' ? null : $data['client_pt_corporate_id'];
		$client_pt_top = $data['client_pt_top'] == '' ? null : $data['client_pt_top'];
		$client_pt_kredit_limit = $data['client_pt_kredit_limit'] == '' ? null : $data['client_pt_kredit_limit'];
		$client_pt_acc = $data['client_pt_acc'] == '' ? null : $data['client_pt_acc'];
		$client_pt_titik_antar_id = $data['client_pt_titik_antar_id'] == '' ? null : $data['client_pt_titik_antar_id'];
		// $client_pt_segmen_id1 = $data['client_pt_segmen_id1'] == '' ? null : $data['client_pt_segmen_id1'];
		// $client_pt_segmen_id2 = $data['client_pt_segmen_id2'] == '' ? null : $data['client_pt_segmen_id2'];
		// $client_pt_segmen_id3 = $data['client_pt_segmen_id3'] == '' ? null : $data['client_pt_segmen_id3'];
		$unit_mandiri_id = $data['unit_mandiri_id'] == '' ? null : $data['unit_mandiri_id'];
		$client_pt_is_deleted = $data['client_pt_is_deleted'] == '' ? null : $data['client_pt_is_deleted'];
		$client_pt_is_aktif = $data['client_pt_is_aktif'] == '' ? null : $data['client_pt_is_aktif'];
		$client_pt_is_multi_lokasi = $data['client_pt_is_multi_lokasi'] == '' ? null : $data['client_pt_is_multi_lokasi'];
		$lokasi_outlet_id = $data['lokasi_outlet_id'] == '' ? null : $data['lokasi_outlet_id'];
		$kelas_jalan2_id = $data['kelas_jalan2_id'] == '' ? null : $data['kelas_jalan2_id'];
		$perusahaan_eksternal = $perusahaan_eksternal == '' ? null : $perusahaan_eksternal;

		$this->db->set("client_pt_eksternal_id", $client_pt_id);
		$this->db->set("client_pt_id", $client_pt_id);
		$this->db->set('client_pt_nama', $client_pt_nama);
		$this->db->set('client_pt_alamat', $client_pt_alamat);
		$this->db->set('client_pt_telepon', $client_pt_telepon);
		$this->db->set('client_pt_propinsi', $client_pt_propinsi);
		$this->db->set('client_pt_kota', $client_pt_kota);
		$this->db->set('client_pt_kecamatan', $client_pt_kecamatan);
		$this->db->set('client_pt_kelurahan', $client_pt_kelurahan);
		$this->db->set('client_pt_kodepos', $client_pt_kodepos);
		$this->db->set('client_pt_latitude', $client_pt_latitude);
		$this->db->set('client_pt_longitude', $client_pt_longitude);
		$this->db->set('client_pt_nama_contact_person', $client_pt_nama_contact_person);
		$this->db->set('client_pt_telepon_contact_person', $client_pt_telepon_contact_person);
		$this->db->set('client_pt_email_contact_person', $client_pt_email_contact_person);
		$this->db->set('client_pt_keterangan', $client_pt_keterangan);
		$this->db->set('kelas_jalan_id', $kelas_jalan_id);
		$this->db->set('area_id', $area_id);
		$this->db->set('client_pt_corporate_id', $client_pt_corporate_id);
		$this->db->set('client_pt_top', $client_pt_top);
		$this->db->set('client_pt_kredit_limit', $client_pt_kredit_limit);
		$this->db->set('client_pt_acc', $client_pt_acc);
		$this->db->set('client_pt_titik_antar_id', $client_pt_titik_antar_id);
		// $this->db->set('client_pt_segmen_id1', $client_pt_segmen_id1);
		// $this->db->set('client_pt_segmen_id2', $client_pt_segmen_id2);
		// $this->db->set('client_pt_segmen_id3', $client_pt_segmen_id3);
		$this->db->set('unit_mandiri_id', $unit_mandiri_id);
		$this->db->set('client_pt_is_deleted', $client_pt_is_deleted);
		$this->db->set('client_pt_is_aktif', $client_pt_is_aktif);
		$this->db->set('client_pt_is_multi_lokasi', $client_pt_is_multi_lokasi);
		$this->db->set('lokasi_outlet_id', $lokasi_outlet_id);
		$this->db->set('kelas_jalan2_id', $kelas_jalan2_id);
		$this->db->set('perusahaan_eksternal', $perusahaan_eksternal);

		$this->db->insert("client_pt_eksternal");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_client_pt_eksternal_internal($customer_id, $perusahaan_eksternal)
	{
		$query = $this->db->query("INSERT INTO client_pt_eksternal (
									client_pt_eksternal_id,
									client_pt_id,
									client_pt_nama,
									client_pt_alamat,
									client_pt_telepon,
									client_pt_propinsi,
									client_pt_kota,
									client_pt_kecamatan,
									client_pt_kelurahan,
									client_pt_kodepos,
									client_pt_latitude,
									client_pt_longitude,
									client_pt_nama_contact_person,
									client_pt_telepon_contact_person,
									client_pt_email_contact_person,
									client_pt_keterangan,
									kelas_jalan_id,
									area_id,
									client_pt_corporate_id,
									client_pt_top,
									client_pt_kredit_limit,
									client_pt_acc,
									client_pt_titik_antar_id,
									client_pt_segmen_id1,
									client_pt_segmen_id2,
									client_pt_segmen_id3,
									unit_mandiri_id,
									client_pt_is_deleted,
									client_pt_is_aktif,
									client_pt_is_multi_lokasi,
									lokasi_outlet_id,
									kelas_jalan2_id,
									perusahaan_eksternal)
									SELECT
									client_pt_id AS client_pt_eksternal_id,
									client_pt_id,
									client_pt_nama,
									client_pt_alamat,
									client_pt_telepon,
									client_pt_propinsi,
									client_pt_kota,
									client_pt_kecamatan,
									client_pt_kelurahan,
									client_pt_kodepos,
									client_pt_latitude,
									client_pt_longitude,
									client_pt_nama_contact_person,
									client_pt_telepon_contact_person,
									client_pt_email_contact_person,
									client_pt_keterangan,
									kelas_jalan_id,
									area_id,
									client_pt_corporate_id,
									client_pt_top,
									client_pt_kredit_limit,
									client_pt_acc,
									client_pt_titik_antar_id,
									client_pt_segmen_id1,
									client_pt_segmen_id2,
									client_pt_segmen_id3,
									unit_mandiri_id,
									client_pt_is_deleted,
									client_pt_is_aktif,
									client_pt_is_multi_lokasi,
									lokasi_outlet_id,
									kelas_jalan2_id,
									'$perusahaan_eksternal' AS perusahaan_eksternal
									FROM client_pt
									WHERE client_pt_id = '$customer_id' ");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();

	}

	public function Get_client_pt_by_eksternal($client_pt_eksternal_id)
	{
		$this->db->select("*")
			->from("client_pt_eksternal")
			->where("client_pt_eksternal_id", $client_pt_eksternal_id);
		$query = $this->db->get();
		// $query = $this->db->query("SELECT
		// 							client_pt.client_pt_id,
		// 							client_pt.client_pt_nama,
		// 							client_pt.client_pt_alamat,
		// 							client_pt.client_pt_telepon,
		// 							client_pt.client_pt_propinsi,
		// 							client_pt.client_pt_kota,
		// 							client_pt.client_pt_kecamatan,
		// 							client_pt.client_pt_kelurahan,
		// 							client_pt.client_pt_kodepos,
		// 							client_pt.client_pt_latitude,
		// 							client_pt.client_pt_longitude,
		// 							client_pt.client_pt_nama_contact_person,
		// 							client_pt.client_pt_telepon_contact_person,
		// 							client_pt.client_pt_email_contact_person,
		// 							client_pt.client_pt_keterangan,
		// 							client_pt.kelas_jalan_id,
		// 							client_pt.area_id,
		// 							client_pt.client_pt_corporate_id,
		// 							client_pt.client_pt_top,
		// 							client_pt.client_pt_kredit_limit,
		// 							client_pt.client_pt_acc,
		// 							client_pt.client_pt_titik_antar_id,
		// 							client_pt.client_pt_segmen_id1,
		// 							client_pt.client_pt_segmen_id2,
		// 							client_pt.client_pt_segmen_id3,
		// 							client_pt.unit_mandiri_id,
		// 							client_pt.client_pt_is_deleted,
		// 							client_pt.client_pt_is_aktif,
		// 							client_pt.client_pt_is_multi_lokasi,
		// 							client_pt.lokasi_outlet_id,
		// 							client_pt.kelas_jalan2_id,
		// 							area.area_nama
		// 							FROM client_pt
		// 							LEFT JOIN area
		// 							ON area.area_id = client_pt.area_id
		// 							WHERE client_pt.client_pt_id IN (SELECT client_pt_id FROM client_pt_eksternal WHERE client_pt_eksternal_id = '$client_pt_eksternal_id') ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0);
		}

		return $query;
	}

	public function Cek_client_pt($client_pt_eksternal_id)
	{
		$query = $this->db->query("SELECT
										a.*
									FROM client_pt a
									INNER JOIN client_pt_eksternal b
									ON b.client_pt_id = a.client_pt_id
									WHERE b.client_pt_eksternal_id = '$client_pt_eksternal_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
	}

	public function Get_client_pt($client_pt_eksternal_id)
	{
		$query = $this->db->query("SELECT
										a.*,area.area_nama
									FROM client_pt a
									INNER JOIN client_pt_eksternal b
									ON b.client_pt_id = a.client_pt_id
									LEFT JOIN area
									ON area.area_id = a.area_id
									WHERE b.client_pt_eksternal_id = '$client_pt_eksternal_id' ");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->row(0);
		}

		return $query;
	}

	public function Update_client_pt_eksternal($client_pt_id, $client_pt_nama, $client_pt_alamat, $client_pt_kelurahan, $client_pt_kecamatan, $client_pt_kota, $client_pt_propinsi, $client_pt_kodepos)
	{
		$this->db->set('client_pt_id', $client_pt_id);

		$this->db->where('client_pt_nama', $client_pt_nama);
		$this->db->where('client_pt_alamat', $client_pt_alamat);
		$this->db->where('client_pt_kelurahan', $client_pt_kelurahan);
		$this->db->where('client_pt_kecamatan', $client_pt_kecamatan);
		$this->db->where('client_pt_kota', $client_pt_kota);
		$this->db->where('client_pt_propinsi', $client_pt_propinsi);
		$this->db->where('client_pt_kodepos', $client_pt_kodepos);

		$this->db->update("client_pt_eksternal");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;

		// return $this->db->last_query();
	}

	public function insert_canvas_internal($tgl, $perusahaan)
	{
		$query = $this->db->query("INSERT INTO canvas (canvas_id,
									depo_id,
									canvas_kode,
									canvas_requestdate,
									client_wms_id,
									kendaraan_id,
									canvas_keterangan,
									canvas_startdate,
									canvas_enddate,
									canvas_status,
									canvas_tanggal_create,
									canvas_who_create,
									canvas_reff_kode,
									client_pt_id,
									principle_id)
									SELECT
									canvas.canvas_id,
									canvas.depo_id,
									canvas.canvas_kode,
									canvas.canvas_requestdate,
									canvas.client_wms_id,
									canvas.kendaraan_id,
									canvas.canvas_keterangan,
									canvas.canvas_startdate,
									canvas.canvas_enddate,
									canvas.canvas_status,
									canvas.canvas_tanggal_create,
									canvas.canvas_who_create,
									canvas.canvas_reff_kode,
									canvas.client_pt_id,
									principle_id
									FROM FAS.dbo.canvas canvas
									INNER JOIN FAS.dbo.client_wms perusahaan
									ON perusahaan.client_wms_id = canvas.client_wms_id
									WHERE FORMAT(canvas.canvas_requestdate, 'yyyy-MM-dd') = '$tgl'
									AND perusahaan.client_wms_kode = '$perusahaan'
									AND canvas.canvas_id NOT IN (select canvas_id from canvas)
									AND canvas.canvas_status = 'Approved'");

		$affectedrows = $this->db->affected_rows();

		if (
			$affectedrows > 0
		) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_canvas_detail_internal($tgl, $perusahaan)
	{
		$query = $this->db->query("INSERT INTO canvas_detail (canvas_detail_id,
									canvas_id,
									sku_id,
									sku_kode,
									sku_nama,
									sku_kemasan,
									sku_satuan,
									sku_qty,
									sku_keterangan,
									tipe_stock_nama)
									SELECT
									canvas_detail_id,
									canvas_id,
									sku_id,
									sku_kode,
									sku_nama,
									sku_kemasan,
									sku_satuan,
									sku_qty,
									sku_keterangan,
									tipe_stock_nama
									FROM FAS.dbo.canvas_detail
									WHERE canvas_id IN (SELECT
									canvas.canvas_id
									FROM FAS.dbo.canvas canvas
									INNER JOIN FAS.dbo.client_wms perusahaan
									ON perusahaan.client_wms_id = canvas.client_wms_id
									WHERE FORMAT(canvas.canvas_requestdate, 'yyyy-MM-dd') = '$tgl'
									AND perusahaan.client_wms_kode = '$perusahaan'
									AND canvas.canvas_status = 'Approved')
									AND canvas_detail_id NOT IN (select canvas_detail_id from canvas_detail)");

		$affectedrows = $this->db->affected_rows();

		if (
			$affectedrows > 0
		) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();

	}

	public function insert_canvas_detail2_internal($tgl, $perusahaan)
	{
		$query = $this->db->query("INSERT INTO canvas_detail_2 (canvas_detail_2_id,
									canvas_detail_id,
									canvas_id,
									sku_id,
									sku_stock_id,
									sku_expdate,
									sku_qty)
									SELECT
									canvas_detail_2_id,
									canvas_detail_id,
									canvas_id,
									sku_id,
									sku_stock_id,
									sku_expdate,
									sku_qty
									FROM FAS.dbo.canvas_detail_2
									WHERE canvas_id IN (SELECT
																				canvas.canvas_id
																			FROM FAS.dbo.canvas canvas
																			INNER JOIN FAS.dbo.client_wms perusahaan
																			ON perusahaan.client_wms_id = canvas.client_wms_id
																			WHERE FORMAT(canvas.canvas_requestdate, 'yyyy-MM-dd') = '$tgl'
																			AND perusahaan.client_wms_kode = '$perusahaan'
																			AND canvas.canvas_status = 'Approved')
																			AND canvas_detail_2_id NOT IN (select canvas_detail_2_id from canvas_detail_2)");

		$affectedrows = $this->db->affected_rows();

		if (
			$affectedrows > 0
		) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();

	}

	public function insert_canvas_detail3_internal($tgl, $perusahaan)
	{
		$query = $this->db->query("INSERT INTO canvas_detail_3 (canvas_detail_3_id,
									canvas_id,
									area_id)
									SELECT
									canvas_detail_3_id,
									canvas_id,
									area_id
									FROM FAS.dbo.canvas_detail_3
									WHERE canvas_id IN (SELECT
																				canvas.canvas_id
																			FROM FAS.dbo.canvas canvas
																			INNER JOIN FAS.dbo.client_wms perusahaan
																			ON perusahaan.client_wms_id = canvas.client_wms_id
																			WHERE FORMAT(canvas.canvas_requestdate, 'yyyy-MM-dd') = '$tgl'
																			AND perusahaan.client_wms_kode = '$perusahaan'
																			AND canvas.canvas_status = 'Approved')
																			AND canvas_detail_3_id NOT IN (select canvas_detail_3_id from canvas_detail_3)");

		$affectedrows = $this->db->affected_rows();

		if (
			$affectedrows > 0
		) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();

	}

	public function Get_canvas_wms($tgl, $perusahaan)
	{
		$query = $this->db->query("SELECT
									canvas.canvas_id AS sales_order_id,
									canvas.depo_id,
									canvas_kode AS sales_order_kode,
									ISNULL(CONVERT(nvarchar(36), canvas.client_wms_id), '') client_wms_id,
									NULL AS channel_id,
									0 AS sales_order_is_handheld,
									canvas.canvas_status AS sales_order_status,
									'' AS sales_order_approved_by,
									canvas.karyawan_id AS sales_id,
									NULL AS client_pt_id,
									ISNULL(canvas.canvas_requestdate, '') sales_order_tgl,
									ISNULL(canvas.canvas_requestdate, '') sales_order_tgl_exp,
									ISNULL(canvas.canvas_requestdate, '') AS sales_order_tgl_harga,
									ISNULL(canvas.canvas_requestdate, '') sales_order_tgl_sj,
									ISNULL(canvas.canvas_requestdate, '') sales_order_tgl_kirim,
									1 AS sales_order_tipe_pembayaran,
									'81887BB5-AD0F-45AF-BDE9-C2AE49061510' tipe_sales_order_id,
									canvas.canvas_reff_kode AS sales_order_no_po,
									ISNULL(canvas.canvas_who_create, '') sales_order_who_create,
									ISNULL(canvas.canvas_tanggal_create, '') sales_order_tgl_create,
									0 AS sales_order_is_downloaded,
									'B608AD49-2E8E-4289-8463-EC90DAAFB971' tipe_delivery_order_id,
									'1' AS sales_order_is_uploaded,
									ISNULL(SUM(ISNULL(sku.sku_harga_jual, 0) * canvas_detail.sku_qty), 0) AS sku_harga_nett,
									0 AS is_promo,
									1 AS is_canvas,
									canvas.client_pt_id,
									client_pt.client_pt_nama,
									client_pt.client_pt_alamat,
									client_pt.client_pt_telepon,
									client_pt.client_pt_propinsi,
									client_pt.client_pt_kota,
									client_pt.client_pt_kecamatan,
									client_pt.client_pt_kelurahan,
									client_pt.client_pt_kodepos,
									--area.area_kode
									(SELECT TOP 1 b.area_kode FROM canvas_detail_3 a LEFT JOIN area b ON a.area_id = b.area_id WHERE a.canvas_id = canvas.canvas_id) AS area_kode
									FROM canvas
									LEFT JOIN canvas_detail ON canvas_detail.canvas_id = canvas.canvas_id
									LEFT JOIN sku ON sku.sku_id = canvas_detail.sku_id
									LEFT JOIN client_wms ON client_wms.client_wms_id = canvas.client_wms_id
									LEFT JOIN client_pt ON canvas.client_pt_id = client_pt.client_pt_id
									LEFT JOIN area ON client_pt.area_id = area.area_id
									WHERE FORMAT(canvas_requestdate, 'yyyy-MM-dd') = '$tgl'
									AND client_wms.client_wms_kode = '$perusahaan'
									AND canvas.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND canvas.canvas_id not in (select canvas_id from delivery_order_draft where canvas_id is not null)
									GROUP BY canvas.canvas_id,
											canvas.depo_id,
											canvas.canvas_kode,
											ISNULL(CONVERT(nvarchar(36), canvas.client_wms_id), ''),
											canvas.canvas_status,
											canvas.karyawan_id,
											ISNULL(canvas.canvas_requestdate, ''),
											canvas.canvas_reff_kode,
											ISNULL(canvas.canvas_who_create, ''),
											ISNULL(canvas.canvas_tanggal_create, ''),
											canvas.client_pt_id,
											client_pt.client_pt_nama,
											client_pt.client_pt_alamat,
											client_pt.client_pt_telepon,
											client_pt.client_pt_propinsi,
											client_pt.client_pt_kota,
											client_pt.client_pt_kecamatan,
											client_pt.client_pt_kelurahan,
											client_pt.client_pt_kodepos
											--area.area_kode
									ORDER BY ISNULL(canvas.canvas_requestdate, '') ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_canvas_detail_wms($canvas_id, $client_wms_id)
	{
		$query = $this->db->query("SELECT
									canvas_detail.canvas_detail_id AS sales_order_detail_id,
									canvas_detail.canvas_id AS sales_order_id,
									'$client_wms_id' AS client_wms_id,
									canvas_detail.sku_id,
									canvas_detail.sku_kode,
									canvas_detail.sku_nama AS sku_nama_produk,
									0 AS sku_disc_percent,
									0 AS sku_disc_rp,
									ISNULL(sku.sku_harga_jual, '0') sku_harga_nett,
									ISNULL(sku.sku_harga_jual, '0') sku_harga_satuan,
									NULL AS sku_request_expdate,
									NULL AS sku_filter_expdate,
									NULL AS sku_filter_expdatebulan,
									NULL AS sku_filter_expdatetahun,
									ISNULL(sku.sku_weight, '0') sku_weight,
									ISNULL(sku.sku_weight_unit, '') sku_weight_unit,
									ISNULL(sku.sku_length, '0') sku_length,
									ISNULL(sku.sku_length_unit, '') sku_length_unit,
									ISNULL(sku.sku_width, '0') sku_width,
									ISNULL(sku.sku_width_unit, '') sku_width_unit,
									ISNULL(sku.sku_height, '0') sku_height,
									ISNULL(sku.sku_height_unit, '') sku_height_unit,
									ISNULL(sku.sku_volume, '0') sku_volume,
									ISNULL(sku.sku_volume_unit, '') sku_volume_unit,
									canvas_detail.sku_qty,
									canvas_detail.sku_keterangan,
									canvas_detail.tipe_stock_nama,
									'0' AS is_promo
									FROM canvas_detail
									LEFT JOIN sku
									ON sku.sku_id = canvas_detail.sku_id
									WHERE canvas_detail.canvas_id = '$canvas_id'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_canvas_detail2_wms($canvas_id)
	{
		$query = $this->db->query("SELECT a.*, CAST((isnull(a.sku_qty, 0) * sku.sku_konversi_faktor) as INT) as sku_composite
									FROM canvas_detail_2 a
									LEFT JOIN sku ON a.sku_id = sku.sku_id
									WHERE a.canvas_id = '$canvas_id'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function cek_sku_stock_canvas($sku_id)
	{
		$query = $this->db->query("SELECT
									sku_stock_id,
									sku_id,
									sku_stock_expired_date,
									ISNULL(sku_stock_awal, 0) + ISNULL(sku_stock_masuk, 0) - ISNULL(sku_stock_alokasi, 0) - ISNULL(sku_stock_keluar, 0) AS stock_akhir
									FROM sku_stock
									WHERE sku_id = '$sku_id'
									AND sku_stock_is_jual = '1'
									AND depo_id = '" . $this->session->userdata('depo_id') . "'
									AND depo_detail_id IN (SELECT depo_detail_id FROM depo_detail WHERE depo_detail_flag_jual = '1')
									ORDER BY sku_stock_expired_date DESC");
		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDeliveryOrderDraftCanvasByFilter($tgl, $perusahaan)
	{

		$query = $this->db->query("SELECT
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									do.sales_order_id,
									FORMAT(do.delivery_order_draft_tgl_buat_do, 'dd/MM/yyyy') AS delivery_order_draft_tgl_buat_do,
									FORMAT(do.delivery_order_draft_tgl_expired_do, 'dd/MM/yyyy') AS delivery_order_draft_tgl_expired_do,
									FORMAT(do.delivery_order_draft_tgl_surat_jalan, 'dd/MM/yyyy') AS delivery_order_draft_tgl_surat_jalan,
									FORMAT(do.delivery_order_draft_tgl_rencana_kirim, 'dd/MM/yyyy') AS delivery_order_draft_tgl_rencana_kirim,
									do.client_wms_id,
									client_wms.client_wms_nama,
									client_wms.client_wms_alamat,
									do.client_pt_id,
									ISNULL(do.delivery_order_draft_kirim_nama,'') AS delivery_order_draft_kirim_nama,
									ISNULL(do.delivery_order_draft_kirim_alamat,'') AS delivery_order_draft_kirim_alamat,
									ISNULL(do.delivery_order_draft_kirim_telp,'') AS delivery_order_draft_kirim_telp,
									ISNULL(do.delivery_order_draft_kirim_provinsi,'') AS delivery_order_draft_kirim_provinsi,
									ISNULL(do.delivery_order_draft_kirim_kota,'') AS delivery_order_draft_kirim_kota,
									ISNULL(do.delivery_order_draft_kirim_kecamatan,'') AS delivery_order_draft_kirim_kecamatan,
									ISNULL(do.delivery_order_draft_kirim_kelurahan,'') AS delivery_order_draft_kirim_kelurahan,
									ISNULL(do.delivery_order_draft_kirim_kodepos,'') AS delivery_order_draft_kirim_kodepos,
									ISNULL(do.delivery_order_draft_kirim_area,'') AS delivery_order_draft_kirim_area,
									do.pabrik_id,
									ISNULL(do.delivery_order_draft_ambil_nama,'') AS delivery_order_draft_ambil_nama,
									ISNULL(do.delivery_order_draft_ambil_alamat,'') AS delivery_order_draft_ambil_alamat,
									ISNULL(do.delivery_order_draft_ambil_telp,'') AS delivery_order_draft_ambil_telp,
									ISNULL(do.delivery_order_draft_ambil_provinsi,'') AS delivery_order_draft_ambil_provinsi,
									ISNULL(do.delivery_order_draft_ambil_kota,'') AS delivery_order_draft_ambil_kota,
									ISNULL(do.delivery_order_draft_ambil_kecamatan,'') AS delivery_order_draft_ambil_kecamatan,
									ISNULL(do.delivery_order_draft_ambil_kelurahan,'') AS delivery_order_draft_ambil_kelurahan,
									ISNULL(do.delivery_order_draft_ambil_kodepos,'') AS delivery_order_draft_ambil_kodepos,
									ISNULL(do.delivery_order_draft_ambil_area,'') AS delivery_order_draft_ambil_area,
									do.delivery_order_draft_tipe_pembayaran,
									do.delivery_order_draft_tipe_layanan,
									do.tipe_delivery_order_id,
									ISNULL(do.delivery_order_draft_keterangan,'') AS delivery_order_draft_keterangan,
									do.delivery_order_draft_status,
									ISNULL(delivery_order_draft_nominal_tunai,0) as delivery_order_draft_nominal_tunai,
									ISNULL(do.delivery_order_draft_attachment, '') AS delivery_order_draft_attachment,
									do.is_promo,
									do.is_canvas
									FROM delivery_order_draft do
									LEFT JOIN client_wms
									ON do.client_wms_id = client_wms.client_wms_id
									WHERE FORMAT(do.delivery_order_draft_tgl_surat_jalan, 'yyyy-MM-dd') = '$tgl'
									AND client_wms.client_wms_kode = '$perusahaan'
									AND do.is_canvas = '1'
									ORDER BY do.delivery_order_draft_kode ASC");

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
									a.client_wms_principle_id,
									a.client_wms_id,
									a.principle_id,
									b.principle_kode
									FROM client_wms_principle a
									INNER JOIN client_wms
									ON client_wms.client_wms_id = a.client_wms_id
									INNER JOIN principle b
									ON b.principle_id = a.principle_id
									WHERE client_wms.client_wms_kode = '$perusahaan'
									ORDER BY b.principle_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_tipe_transaksi_bosnet()
	{
		$query = $this->db->query("SELECT * FROM MIDDLEWARE.dbo.tipe_transaksi_bosnet() where is_aktif = '1'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_stock_movement_bosnet($tgl, $perusahaan, $tipe_transaksi, $depo_eksternal)
	{
		$filter_perusahaan = "";

		if (count($perusahaan) > 0) {
			$filter_perusahaan = "AND p.szCategory_1 IN (" . implode(",", $perusahaan) . ")";
		} else {
			$filter_perusahaan = "";
		}

		$bosnet = $this->load->database('bosnet', TRUE);

		$query = $bosnet->query("SELECT a.szProductId,
									p.szName AS szProductName,
									p.szCategory_1 AS szPrincipleId,
									a.szLocationType,
									a.szLocationId,
									a.szStockTypeId,
									a.dtmTransaction,
									a.gdHistoryId,
									a.shOrder,
									a.decQty,
									a.decDocQty,
									a.szDocUomId,
									a.szTrnId,
									a.szDocId,
									a.szStockTransferReason,
									a.szOrderTypeId,
									a.szOrderItemTypeId,
									a.szUrl,
									a.szReportedAsType,
									a.szReportedAsId,
									a.szPartyType,
									a.szPartyId,
									a.szEmployeeId,
									a.szRefDocId,
									a.szPartyLocType,
									a.szPartyLocId,
									a.szFakturPajakId,
									a.decCOGS,
									a.dtmLastUpdated,
									a.bFreeze,
									a.szDistProductId,
									a.decDistQty,
									a.szOwnerId
								FROM BOS_INV_StockHistory a
								LEFT JOIN BOS_INV_Product p ON p.szProductId = a.szProductId
								WHERE FORMAT(a.dtmTransaction, 'yyyy-MM-dd') = '$tgl'
								AND a.szReportedAsId = '$depo_eksternal'
								AND a.szTrnId = '$tipe_transaksi'
								" . $filter_perusahaan . " ");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_stock_movement_bosnet_wms($tgl, $perusahaan, $tipe_transaksi, $depo_eksternal)
	{
		$filter_perusahaan = "";

		if (count($perusahaan) > 0) {
			$filter_perusahaan = "AND a.szPrincipleId IN (" . implode(",", $perusahaan) . ")";
		} else {
			$filter_perusahaan = "";
		}

		$query = $this->db->query("SELECT a.szProductId,
									a.szProductName,
									a.szPrincipleId,
									a.szLocationType,
									a.szLocationId,
									a.szStockTypeId,
									FORMAT(a.dtmTransaction,'dd-MM-yyyy') AS dtmTransaction,
									a.gdHistoryId,
									a.shOrder,
									a.decQty,
									a.decDocQty,
									a.szDocUomId,
									a.szTrnId,
									a.szDocId,
									a.szStockTransferReason,
									a.szOrderTypeId,
									a.szOrderItemTypeId,
									a.szUrl,
									a.szReportedAsType,
									a.szReportedAsId,
									a.szPartyType,
									a.szPartyId,
									a.szEmployeeId,
									a.szRefDocId,
									a.szPartyLocType,
									a.szPartyLocId,
									a.szFakturPajakId,
									a.decCOGS,
									a.dtmLastUpdated,
									a.bFreeze,
									a.szDistProductId,
									a.decDistQty,
									a.szOwnerId
								FROM MIDDLEWARE.dbo.BOS_INV_StockHistory a
								WHERE FORMAT(a.dtmTransaction, 'yyyy-MM-dd') = '$tgl'
								AND a.szReportedAsId = '$depo_eksternal'
								AND a.szTrnId = '$tipe_transaksi'
								" . $filter_perusahaan . " ");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Insert_stock_movement_bosnet($szProductId, $szPrincipleId, $szLocationType, $szLocationId, $szStockTypeId, $dtmTransaction, $gdHistoryId, $shOrder, $decQty, $decDocQty, $szDocUomId, $szTrnId, $szDocId, $szStockTransferReason, $szOrderTypeId, $szOrderItemTypeId, $szUrl, $szReportedAsType, $szReportedAsId, $szPartyType, $szPartyId, $szEmployeeId, $szRefDocId, $szPartyLocType, $szPartyLocId, $szFakturPajakId, $decCOGS, $dtmLastUpdated, $bFreeze, $szDistProductId, $decDistQty, $szOwnerId, $szProductName)
	{

		$middleware = $this->load->database('middleware', TRUE);

		$szProductId = $szProductId == '' ? '' : $szProductId;
		$szPrincipleId = $szPrincipleId == '' ? '' : $szPrincipleId;
		$szLocationType = $szLocationType == '' ? '' : $szLocationType;
		$szLocationId = $szLocationId == '' ? '' : $szLocationId;
		$szStockTypeId = $szStockTypeId == '' ? '' : $szStockTypeId;
		$dtmTransaction = $dtmTransaction == '' ? '' : $dtmTransaction;
		$gdHistoryId = $gdHistoryId == '' ? '' : $gdHistoryId;
		$shOrder = $shOrder == '' ? '' : $shOrder;
		$decQty = $decQty == '' ? 0 : $decQty;
		$decDocQty = $decDocQty == 0 ? '' : $decDocQty;
		$szDocUomId = $szDocUomId == '' ? '' : $szDocUomId;
		$szTrnId = $szTrnId == '' ? '' : $szTrnId;
		$szDocId = $szDocId == '' ? '' : $szDocId;
		$szStockTransferReason = $szStockTransferReason == '' ? '' : $szStockTransferReason;
		$szOrderTypeId = $szOrderTypeId == '' ? '' : $szOrderTypeId;
		$szOrderItemTypeId = $szOrderItemTypeId == '' ? '' : $szOrderItemTypeId;
		$szUrl = $szUrl == '' ? '' : $szUrl;
		$szReportedAsType = $szReportedAsType == '' ? '' : $szReportedAsType;
		$szReportedAsId = $szReportedAsId == '' ? '' : $szReportedAsId;
		$szPartyType = $szPartyType == '' ? '' : $szPartyType;
		$szPartyId = $szPartyId == '' ? '' : $szPartyId;
		$szEmployeeId = $szEmployeeId == '' ? '' : $szEmployeeId;
		$szRefDocId = $szRefDocId == '' ? '' : $szRefDocId;
		$szPartyLocType = $szPartyLocType == '' ? '' : $szPartyLocType;
		$szPartyLocId = $szPartyLocId == '' ? '' : $szPartyLocId;
		$szFakturPajakId = $szFakturPajakId == '' ? '' : $szFakturPajakId;
		$decCOGS = $decCOGS == '' ? 0 : $decCOGS;
		$dtmLastUpdated = $dtmLastUpdated == '' ? '' : $dtmLastUpdated;
		$bFreeze = $bFreeze == '' ? '' : $bFreeze;
		$szDistProductId = $szDistProductId == '' ? '' : $szDistProductId;
		$decDistQty = $decDistQty == '' ? 0 : $decDistQty;
		$szOwnerId = $szOwnerId == '' ? '' : $szOwnerId;
		$szProductName = $szProductName == '' ? '' : $szProductName;

		$middleware->set('szProductId', $szProductId);
		$middleware->set('szPrincipleId', $szPrincipleId);
		$middleware->set('szLocationType', $szLocationType);
		$middleware->set('szLocationId', $szLocationId);
		$middleware->set('szStockTypeId', $szStockTypeId);
		$middleware->set('dtmTransaction', $dtmTransaction);
		// $middleware->set('gdHistoryId', $gdHistoryId);
		$middleware->set('shOrder', $shOrder);
		$middleware->set('decQty', $decQty);
		$middleware->set('decDocQty', $decDocQty);
		$middleware->set('szDocUomId', $szDocUomId);
		$middleware->set('szTrnId', $szTrnId);
		$middleware->set('szDocId', $szDocId);
		$middleware->set('szStockTransferReason', $szStockTransferReason);
		$middleware->set('szOrderTypeId', $szOrderTypeId);
		$middleware->set('szOrderItemTypeId', $szOrderItemTypeId);
		$middleware->set('szUrl', $szUrl);
		$middleware->set('szReportedAsType', $szReportedAsType);
		$middleware->set('szReportedAsId', $szReportedAsId);
		$middleware->set('szPartyType', $szPartyType);
		$middleware->set('szPartyId', $szPartyId);
		$middleware->set('szEmployeeId', $szEmployeeId);
		$middleware->set('szRefDocId', $szRefDocId);
		$middleware->set('szPartyLocType', $szPartyLocType);
		$middleware->set('szPartyLocId', $szPartyLocId);
		$middleware->set('szFakturPajakId', $szFakturPajakId);
		$middleware->set('decCOGS', $decCOGS);
		$middleware->set('dtmLastUpdated', $dtmLastUpdated);
		$middleware->set('bFreeze', $bFreeze);
		$middleware->set('szDistProductId', $szDistProductId);
		$middleware->set('decDistQty', $decDistQty);
		$middleware->set('szOwnerId', $szOwnerId);
		$middleware->set('szProductName', $szProductName);

		$middleware->insert("BOS_INV_StockHistory");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;

		// return $middleware->last_query();
	}

	public function Delete_stock_movement_bosnet($szProductId, $dtmTransaction, $szTrnId)
	{

		$query = $this->db->query("DELETE MIDDLEWARE.dbo.BOS_INV_StockHistory WHERE szProductId = '$szProductId' AND dtmTransaction = '$dtmTransaction' AND szTrnId = '$szTrnId'");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;

		// return $this->db->last_query();
	}

	public function Get_depo_eksternal($sistem_eksternal, $depo_id)
	{

		$query = $this->db->query("SELECT * FROM depo_eksternal WHERE sistem_eksternal = '$sistem_eksternal' AND CONVERT(NVARCHAR(36),depo_id) = '$depo_id' ");

		if ($query->num_rows() == 0) {
			$query = "";
		} else {
			$query = $query->row(0)->depo_eksternal_kode;
		}

		return $query;

		// return $this->db->last_query();
	}

	public function Exec_proses_sku_konversi_group($sku_konversi_group, $qty)
	{

		$query = $this->db->query("Exec proses_sku_konversi_group '$sku_konversi_group',$qty");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;

		// return $this->db->last_query();
	}

	public function Get_stock_movement_bosnet_wms_group_by_principle($tgl, $perusahaan, $tipe_transaksi, $depo_eksternal)
	{
		$filter_perusahaan = "";

		if (count($perusahaan) > 0) {
			$filter_perusahaan = "AND a.szPrincipleId IN (" . implode(",", $perusahaan) . ")";
		} else {
			$filter_perusahaan = "";
		}

		$query = $this->db->query("SELECT 	a.szDocId,
											a.szTrnId,
											a.szStockTypeId,
										  	p.principle_id,
										  	p.principle_kode
									FROM MIDDLEWARE.dbo.BOS_INV_StockHistory a
									LEFT JOIN principle p ON p.principle_kode = a.szPrincipleId
									WHERE FORMAT(a.dtmTransaction, 'yyyy-MM-dd') = '$tgl'
									AND a.szReportedAsId = '$depo_eksternal'
									AND a.szTrnId = '$tipe_transaksi' " . $filter_perusahaan . "
									GROUP BY a.szDocId,
											a.szTrnId,
											a.szStockTypeId,
										  	p.principle_id,
										  	p.principle_kode
									ORDER BY p.principle_kode ASC");

		// $query = $this->db->query("SELECT p.principle_id, p.principle_kode
		// 							FROM MIDDLEWARE.dbo.BOS_INV_StockHistory a
		// 							LEFT JOIN principle p
		// 							ON p.principle_kode = a.szPrincipleId
		// 							WHERE FORMAT(a.dtmTransaction, 'yyyy-MM-dd') = '$tgl'
		// 							AND a.szReportedAsId = '$depo_eksternal'
		// 							AND a.szTrnId = '$tipe_transaksi'
		// 							" . $filter_perusahaan . " 
		// 							GROUP BY p.principle_id, p.principle_kode
		// 							ORDER BY p.principle_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_stock_movement_bosnet_wms_by_principle($tgl, $principle, $tipe_transaksi, $depo_eksternal, $tipe_stock)
	{
		$filter_perusahaan = "";

		$query = $this->db->query("SELECT a.szProductId,
									a.szProductName,
									a.szPrincipleId,
									a.szLocationType,
									a.szLocationId,
									a.szStockTypeId,
									FORMAT(a.dtmTransaction,'dd-MM-yyyy') AS dtmTransaction,
									a.gdHistoryId,
									a.shOrder,
									a.decQty,
									a.decDocQty,
									a.szDocUomId,
									a.szTrnId,
									a.szDocId,
									a.szStockTransferReason,
									a.szOrderTypeId,
									a.szOrderItemTypeId,
									a.szUrl,
									a.szReportedAsType,
									a.szReportedAsId,
									a.szPartyType,
									a.szPartyId,
									a.szEmployeeId,
									a.szRefDocId,
									a.szPartyLocType,
									a.szPartyLocId,
									a.szFakturPajakId,
									a.decCOGS,
									a.dtmLastUpdated,
									a.bFreeze,
									a.szDistProductId,
									a.decDistQty,
									a.szOwnerId
								FROM MIDDLEWARE.dbo.BOS_INV_StockHistory a
								WHERE FORMAT(a.dtmTransaction, 'yyyy-MM-dd') = '$tgl'
								AND a.szPrincipleId = '$principle'
								AND a.szReportedAsId = '$depo_eksternal'
								AND a.szTrnId = '$tipe_transaksi'
								AND a.szStockTypeId = '$tipe_stock' ");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function cek_tr_koreksi_draft($no_referensi_dokumen)
	{
		$filter_perusahaan = "";

		$query = $this->db->query("SELECT * FROM tr_koreksi_stok_draft WHERE no_referensi_dokumen = '$no_referensi_dokumen' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function insert_to_tr_koreksi_draft($kpd_id, $generate_kode, $tgl, $gudang, $principle, $checker, $tipe, $status, $keterangan, $ekspedisi, $driver, $kendaraan, $nopol, $no_referensi_dokumen)
	{
		$kpd_id = $kpd_id == '' ? null : $kpd_id;
		$generate_kode = $generate_kode == '' ? null : $generate_kode;
		$tgl = $tgl == '' ? null : $tgl;
		$gudang = $gudang == '' ? null : $gudang;
		$principle = $principle == '' ? null : $principle;
		$checker = $checker == '' ? null : $checker;
		$tipe = $tipe == '' ? null : $tipe;
		$status = $status == '' ? null : $status;
		$keterangan = $keterangan == '' ? null : $keterangan;
		$ekspedisi = $ekspedisi == '' ? null : $ekspedisi;
		$driver = $driver == '' ? null : $driver;
		$kendaraan = $kendaraan == '' ? null : $kendaraan;
		$nopol = $nopol == '' ? null : $nopol;
		$no_referensi_dokumen = $no_referensi_dokumen == '' ? null : $no_referensi_dokumen;

		// $tgl_ = date_create(str_replace("/", "-", $tgl));
		$this->db->set("tr_koreksi_stok_draft_id", $kpd_id);
		$this->db->set("principle_id", $principle);
		$this->db->set("tr_koreksi_stok_draft_kode", $generate_kode);
		$this->db->set("tr_koreksi_stok_draft_tanggal", $tgl);
		$this->db->set("tipe_mutasi_id", $tipe);
		$this->db->set("tr_koreksi_stok_draft_keterangan", $keterangan);
		$this->db->set("tr_koreksi_stok_draft_status", $status);
		$this->db->set("depo_id_asal", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id_asal", $gudang);
		$this->db->set("tr_koreksi_stok_draft_tgl_create", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_draft_tgl_update", "GETDATE()", FALSE);
		$this->db->set("tr_koreksi_stok_draft_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("tr_koreksi_stok_draft_nama_checker", $checker);
		$this->db->set("ekspedisi_id", $ekspedisi);
		$this->db->set("tr_koreksi_stok_draft_pengemudi", $driver);
		$this->db->set("tr_koreksi_stok_draft_kendaraan", $kendaraan);
		$this->db->set("tr_koreksi_stok_draft_nopol", $nopol);
		$this->db->set("no_referensi_dokumen", $no_referensi_dokumen);

		$queryinsert = $this->db->insert("tr_koreksi_stok_draft");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_to_tr_koreksi_detail_draft($kpdd_id, $kpd_id, $sku_id, $sku_stock_id, $qty)
	{
		$kpd_id = $kpd_id == '' ? null : $kpd_id;
		$sku_id = $sku_id == '' ? null : $sku_id;
		$sku_stock_id = $sku_stock_id == '' ? null : $sku_stock_id;
		$qty = $qty == '' ? null : abs($qty);

		$this->db->set("tr_koreksi_stok_detail_draft_id", $kpdd_id);
		$this->db->set("tr_koreksi_stok_draft_id", $kpd_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_qty_plan_koreksi", $qty);

		$queryinsert = $this->db->insert("tr_koreksi_stok_detail_draft");
		return $queryinsert;
	}
}
