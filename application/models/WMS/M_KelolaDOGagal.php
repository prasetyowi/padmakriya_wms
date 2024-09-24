<?php

class M_KelolaDOGagal extends CI_Model
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

	public function GetTipeEkspedisi()
	{
		$this->db->select("*")
			->from("tipe_ekspedisi")
			->order_by("tipe_ekspedisi_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
	public function GetKendaraan()
	{
		$this->db->select("*")
			->from("kendaraan")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("kendaraan_is_aktif", "1")
			->order_by("kendaraan_nopol");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDriver()
	{
		$this->db->select("*")
			->from("karyawan")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("karyawan_level_id", "339D8AC2-C6CE-4B47-9BFC-E372592AF521")
			->order_by("karyawan_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
	public function getSegment()
	{
		return $this->db->query('select * from client_pt_segmen where client_pt_segmen_level =1')->result();
	}
	public function getSegment2($id)
	{

		return $this->db->query("select * from client_pt_segmen where client_pt_segmen_reff_id ='$id' and client_pt_segmen_level =2")->result();
	}
	public function getSegment3($id)
	{
		return $this->db->query("select * from client_pt_segmen where client_pt_segmen_reff_id='$id' and client_pt_segmen_level =3")->result();
	}

	public function CheckDOKirimUlang($delivery_order_batch_id, $delivery_order_id)
	{
		$query = $this->db->query("SELECT
									delivery_order_reff_id,
									delivery_order_reff_no
									FROM delivery_order
									WHERE delivery_order_reff_id = '$delivery_order_id'
									UNION ALL
									SELECT
									delivery_order_reff_id,
									delivery_order_reff_no
									FROM delivery_order
									WHERE delivery_order_reff_id = '$delivery_order_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
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

	public function Get_area_by_nama($area_nama)
	{
		$this->db->select("*")
			->from("area")
			->where("area_nama", $area_nama);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0);
		}

		return $query;
	}

	public function GetSelectedSKU($sku_id)
	{
		$sku_id = implode(",", $sku_id);

		$query = $this->db->query("select * from sku where sku_id in (" . $sku_id . ") order by sku_kode asc");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetKelolaDOGagalHeaderById($id)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_id,
									do.delivery_order_kode,
									do.sales_order_id,
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
									do.principle_id AS pabrik_id,
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
									do.delivery_order_reff_id,
									do.delivery_order_reff_no,
									do.tipe_delivery_order_id,
									ISNULL(do.delivery_order_keterangan,'') AS delivery_order_keterangan,
									do.delivery_order_status,
									CAST(delivery_order_nominal_tunai as numeric(10,2)) as delivery_order_nominal_tunai,
									ISNULL(do.delivery_order_attachment, '') AS delivery_order_attachment
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

	public function GetKelolaDOGagalDetailById($id)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_detail_id,
									do.delivery_order_id,
									do.sku_id,
									do.depo_id AS gudang_id,
									do.depo_detail_id AS gudang_detail_id,
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
									ISNULL(do.sku_qty_kirim, '0') AS sku_qty_kirim,
									ISNULL(do.sku_keterangan, '') AS sku_keterangan,
									ISNULL(do.tipe_stock_nama, '') AS tipe_stock
									FROM delivery_order_detail do
									LEFT JOIN sku
									  ON sku.sku_id = do.sku_id
									WHERE do.delivery_order_id = '$id'
									ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function getSkuQtySelisih($id)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_detail_id,
									do.delivery_order_id,
									do.sku_id,
									do.depo_id AS gudang_id,
									do.depo_detail_id AS gudang_detail_id,
									do.sku_kode,
									do.sku_nama_produk,
									do.sku_harga_satuan,
									sku.sku_satuan,
									sku.sku_kemasan,
									ISNULL(do.sku_qty, '0') AS sku_qty,
									ISNULL(do.sku_qty_kirim, '0') AS sku_qty_kirim,
									(ISNULL(do.sku_qty, '0') - ISNULL(do.sku_qty_kirim, '0')) AS sku_qty_selisih,
									ISNULL(do.sku_keterangan, '') AS sku_keterangan,
									ISNULL(do.tipe_stock_nama, '') AS tipe_stock
									FROM delivery_order_detail do
									LEFT JOIN sku
									  ON sku.sku_id = do.sku_id
									WHERE do.delivery_order_id IN ($id)
									AND (do.sku_qty - do.sku_qty_kirim) <> 0
									ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKelolaDOGagalHeaderByListId($id)
	{
		$id = implode(",", $id);

		$query = $this->db->query("SELECT
									do.delivery_order_id AS delivery_order_draft_id,
									do.sales_order_id AS sales_order_id,
									do.delivery_order_kode AS delivery_order_draft_kode,
									'' AS delivery_order_draft_yourref,
									do.client_wms_id AS client_wms_id,
									FORMAT(GETDATE(), 'yyyy-MM-dd') AS delivery_order_draft_tgl_buat_do,
									'' AS delivery_order_draft_tgl_expired_do,
									'' AS delivery_order_draft_tgl_surat_jalan,
									'' AS delivery_order_draft_tgl_rencana_kirim,
									'' AS delivery_order_draft_tgl_aktual_kirim,
									do.delivery_order_keterangan AS delivery_order_draft_keterangan,
									do.delivery_order_status AS delivery_order_draft_status,
									do.delivery_order_is_prioritas AS delivery_order_draft_is_prioritas,
									do.delivery_order_is_need_packing AS delivery_order_draft_is_need_packing,
									do.delivery_order_tipe_layanan AS delivery_order_draft_tipe_layanan,
									do.delivery_order_tipe_pembayaran AS delivery_order_draft_tipe_pembayaran,
									NULL AS delivery_order_draft_sesi_pengiriman,
									NULL AS delivery_order_draft_request_tgl_kirim,
									NULL AS delivery_order_draft_request_jam_kirim,
									do.tipe_pengiriman_id AS tipe_pengiriman_id,
									do.nama_tipe AS nama_tipe,
									do.confirm_rate AS confirm_rate,
									do.delivery_order_id AS delivery_order_draft_reff_id,
									do.delivery_order_kode AS delivery_order_draft_reff_no,
									do.delivery_order_total AS delivery_order_draft_total,
									do.unit_mandiri_id AS unit_mandiri_id,
									do.depo_id AS depo_id,
									do.client_pt_id AS client_pt_id,
									do.delivery_order_kirim_nama AS delivery_order_draft_kirim_nama,
									do.delivery_order_kirim_alamat AS delivery_order_draft_kirim_alamat,
									do.delivery_order_kirim_telp AS delivery_order_draft_kirim_telp,
									do.delivery_order_kirim_provinsi AS delivery_order_draft_kirim_provinsi,
									do.delivery_order_kirim_kota AS delivery_order_draft_kirim_kota,
									do.delivery_order_kirim_kecamatan AS delivery_order_draft_kirim_kecamatan,
									do.delivery_order_kirim_kelurahan AS delivery_order_draft_kirim_kelurahan,
									do.delivery_order_kirim_kodepos AS delivery_order_draft_kirim_kodepos,
									do.delivery_order_kirim_area AS delivery_order_draft_kirim_area,
									do.delivery_order_kirim_invoice_pdf AS delivery_order_draft_kirim_invoice_pdf,
									do.delivery_order_kirim_invoice_dir AS delivery_order_draft_kirim_invoice_dir,
									do.principle_id AS pabrik_id,
									do.delivery_order_ambil_nama AS delivery_order_draft_ambil_nama,
									do.delivery_order_ambil_alamat AS delivery_order_draft_ambil_alamat,
									do.delivery_order_ambil_telp AS delivery_order_draft_ambil_telp,
									do.delivery_order_ambil_provinsi AS delivery_order_draft_ambil_provinsi,
									do.delivery_order_ambil_kota AS delivery_order_draft_ambil_kota,
									do.delivery_order_ambil_kecamatan AS delivery_order_draft_ambil_kecamatan,
									do.delivery_order_ambil_kelurahan AS delivery_order_draft_ambil_kelurahan,
									do.delivery_order_ambil_kodepos AS delivery_order_draft_ambil_kodepos,
									do.delivery_order_ambil_area AS delivery_order_draft_ambil_area,
									NULL AS delivery_order_draft_update_who,
									GETDATE() AS delivery_order_draft_update_tgl,
									'' AS delivery_order_draft_approve_who,
									NULL AS delivery_order_draft_approve_tgl,
									NULL AS delivery_order_draft_reject_who,
									NULL AS delivery_order_draft_reject_tgl,
									NULL AS delivery_order_draft_reject_reason,
									'EE5CD3F7-F7E3-475E-A7B4-67FD5AB65975' AS tipe_delivery_order_id,
									NULL AS is_from_so,
									CASE WHEN do.delivery_order_status = 'partially delivered' THEN 0 ELSE do.delivery_order_nominal_tunai end AS delivery_order_draft_nominal_tunai,
									do.delivery_order_attachment AS delivery_order_draft_attachment,
									do.is_promo AS is_promo,
									NULL AS is_canvas,
									tdo.tipe_delivery_order_nama
									FROM delivery_order do
									LEFT JOIN tipe_delivery_order tdo ON tdo.tipe_delivery_order_id = do.tipe_delivery_order_id
									WHERE do.delivery_order_id IN (" . $id . ")
									UNION ALL
									SELECT
									do.delivery_order_draft_id,
									do.sales_order_id AS sales_order_id,
									do.delivery_order_draft_kode,
									do.delivery_order_draft_yourref,
									do.client_wms_id AS client_wms_id,
									FORMAT(do.delivery_order_draft_tgl_buat_do, 'yyyy-MM-dd') AS delivery_order_draft_tgl_buat_do,
									do.delivery_order_draft_tgl_expired_do,
									do.delivery_order_draft_tgl_surat_jalan,
									do.delivery_order_draft_tgl_rencana_kirim,
									do.delivery_order_draft_tgl_aktual_kirim,
									do.delivery_order_draft_keterangan AS delivery_order_draft_keterangan,
									do.delivery_order_draft_status,
									do.delivery_order_draft_is_prioritas AS delivery_order_draft_is_prioritas,
									do.delivery_order_draft_is_need_packing AS delivery_order_draft_is_need_packing,
									do.delivery_order_draft_tipe_layanan AS delivery_order_draft_tipe_layanan,
									do.delivery_order_draft_tipe_pembayaran AS delivery_order_draft_tipe_pembayaran,
									do.delivery_order_draft_sesi_pengiriman,
									do.delivery_order_draft_request_tgl_kirim,
									do.delivery_order_draft_request_jam_kirim,
									do.tipe_pengiriman_id AS tipe_pengiriman_id,
									do.nama_tipe AS nama_tipe,
									do.confirm_rate AS confirm_rate,
									do.delivery_order_draft_id AS delivery_order_draft_reff_id,
									do.delivery_order_draft_kode AS delivery_order_draft_reff_no,
									do.delivery_order_draft_total AS delivery_order_draft_total,
									do.unit_mandiri_id AS unit_mandiri_id,
									do.depo_id AS depo_id,
									do.client_pt_id AS client_pt_id,
									do.delivery_order_draft_kirim_nama AS delivery_order_draft_kirim_nama,
									do.delivery_order_draft_kirim_alamat AS delivery_order_draft_kirim_alamat,
									do.delivery_order_draft_kirim_telp AS delivery_order_draft_kirim_telp,
									do.delivery_order_draft_kirim_provinsi AS delivery_order_draft_kirim_provinsi,
									do.delivery_order_draft_kirim_kota AS delivery_order_draft_kirim_kota,
									do.delivery_order_draft_kirim_kecamatan AS delivery_order_draft_kirim_kecamatan,
									do.delivery_order_draft_kirim_kelurahan AS delivery_order_draft_kirim_kelurahan,
									do.delivery_order_draft_kirim_kodepos AS delivery_order_draft_kirim_kodepos,
									do.delivery_order_draft_kirim_area AS delivery_order_draft_kirim_area,
									do.delivery_order_draft_kirim_invoice_pdf AS delivery_order_draft_kirim_invoice_pdf,
									do.delivery_order_draft_kirim_invoice_dir AS delivery_order_draft_kirim_invoice_dir,
									do.pabrik_id,
									do.delivery_order_draft_ambil_nama AS delivery_order_draft_ambil_nama,
									do.delivery_order_draft_ambil_alamat AS delivery_order_draft_ambil_alamat,
									do.delivery_order_draft_ambil_telp AS delivery_order_draft_ambil_telp,
									do.delivery_order_draft_ambil_provinsi AS delivery_order_draft_ambil_provinsi,
									do.delivery_order_draft_ambil_kota AS delivery_order_draft_ambil_kota,
									do.delivery_order_draft_ambil_kecamatan AS delivery_order_draft_ambil_kecamatan,
									do.delivery_order_draft_ambil_kelurahan AS delivery_order_draft_ambil_kelurahan,
									do.delivery_order_draft_ambil_kodepos AS delivery_order_draft_ambil_kodepos,
									do.delivery_order_draft_ambil_area AS delivery_order_draft_ambil_area,
									do.delivery_order_draft_update_who,
									do.delivery_order_draft_update_tgl,
									do.delivery_order_draft_approve_who,
									do.delivery_order_draft_approve_tgl,
									do.delivery_order_draft_reject_who,
									do.delivery_order_draft_reject_tgl,
									do.delivery_order_draft_reject_reason,
									do.tipe_delivery_order_id AS tipe_delivery_order_id,
									do.is_from_so,
									do.delivery_order_draft_nominal_tunai AS delivery_order_draft_nominal_tunai,
									do.delivery_order_draft_attachment AS delivery_order_draft_attachment,
									do.is_promo,
									do.is_canvas,
									tdo.tipe_delivery_order_nama
									FROM delivery_order_draft do
									LEFT JOIN tipe_delivery_order tdo ON tdo.tipe_delivery_order_id = do.tipe_delivery_order_id
									WHERE do.delivery_order_draft_id IN (" . $id . ")
									ORDER BY do.delivery_order_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKelolaDOGagalDetailByListId($id)
	{
		$cekTipe = $this->db->query("SELECT tipe_delivery_order_id FROM delivery_order WHERE delivery_order_id = '$id'")->row('tipe_delivery_order_id');

		if ($cekTipe == 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD') {
			$tipeA = "";
			$tipeB = "";
		} else {
			$tipeA = "AND tipe_delivery_order_id NOT IN ('$id')";
			$tipeB = "AND tipe_delivery_order_id IN ('$id')";
		}
		// $query = $this->db->query("SELECT
		// 							'' AS delivery_order_detail_draft_id,
		// 							'' AS delivery_order_draft_id,
		// 							do.sku_id AS sku_id,
		// 							do.depo_id AS gudang_id,
		// 							do.depo_detail_id AS gudang_detail_id,
		// 							do.sku_kode AS sku_kode,
		// 							do.sku_nama_produk AS sku_nama_produk,
		// 							ISNULL(do.sku_harga_satuan, '0') AS sku_harga_satuan,
		// 							ISNULL(do.sku_disc_percent, '0') AS sku_disc_percent,
		// 							ISNULL(do.sku_disc_rp, '0') AS sku_disc_rp,
		// 							ISNULL(do.sku_harga_nett, '0') AS sku_harga_nett,
		// 							ISNULL(do.sku_request_expdate, '0') AS sku_request_expdate,
		// 							ISNULL(do.sku_filter_expdate, '0') AS sku_filter_expdate,
		// 							ISNULL(do.sku_filter_expdatebulan, '0') AS sku_filter_expdatebulan,
		// 							ISNULL(do.sku_filter_expdatetahun, '0') AS sku_filter_expdatetahun,
		// 							ISNULL(do.sku_weight, '0') AS sku_weight,
		// 							ISNULL(do.sku_weight_unit, '') AS sku_weight_unit,
		// 							ISNULL(do.sku_length, '0') AS sku_length,
		// 							ISNULL(do.sku_length_unit, '') AS sku_length_unit,
		// 							ISNULL(do.sku_width, '0') AS sku_width,
		// 							ISNULL(do.sku_width_unit, '') AS sku_width_unit,
		// 							ISNULL(do.sku_height, '0') AS sku_height,
		// 							ISNULL(do.sku_height_unit, '') AS sku_height_unit,
		// 							ISNULL(do.sku_volume, '0') AS sku_volume,
		// 							ISNULL(do.sku_volume_unit, '') AS sku_volume_unit,
		// 							case when d.delivery_order_status = 'not delivered' then ISNULL(do.sku_qty, '0') 
		// 							when d.delivery_order_status = 'partially delivered' then do.sku_qty - sku_qty_kirim end as sku_qty,
		// 							ISNULL(do.sku_keterangan, '') AS sku_keterangan,
		// 							ISNULL(do.tipe_stock_nama, '') AS tipe_stock_nama
		// 							FROM delivery_order_detail do
		// 							left join delivery_order as d on do.delivery_order_id = d.delivery_order_id
		// 							WHERE do.delivery_order_id = '$id' AND (d.delivery_order_status = 'not delivered' OR (d.delivery_order_status = 'partially delivered' and do.sku_qty - sku_qty_kirim <> 0))
		// 							UNION ALL
		// 							SELECT
		// 							'' AS delivery_order_detail_draft_id,
		// 							'' AS delivery_order_draft_id,
		// 							do.sku_id AS sku_id,
		// 							do.gudang_id,
		// 							do.gudang_detail_id,
		// 							do.sku_kode AS sku_kode,
		// 							do.sku_nama_produk AS sku_nama_produk,
		// 							ISNULL(do.sku_harga_satuan, '0') AS sku_harga_satuan,
		// 							ISNULL(do.sku_disc_percent, '0') AS sku_disc_percent,
		// 							ISNULL(do.sku_disc_rp, '0') AS sku_disc_rp,
		// 							ISNULL(do.sku_harga_nett, '0') AS sku_harga_nett,
		// 							ISNULL(do.sku_request_expdate, '0') AS sku_request_expdate,
		// 							ISNULL(do.sku_filter_expdate, '0') AS sku_filter_expdate,
		// 							ISNULL(do.sku_filter_expdatebulan, '0') AS sku_filter_expdatebulan,
		// 							ISNULL(do.sku_filter_expdatetahun, '0') AS sku_filter_expdatetahun,
		// 							ISNULL(do.sku_weight, '0') AS sku_weight,
		// 							ISNULL(do.sku_weight_unit, '') AS sku_weight_unit,
		// 							ISNULL(do.sku_length, '0') AS sku_length,
		// 							ISNULL(do.sku_length_unit, '') AS sku_length_unit,
		// 							ISNULL(do.sku_width, '0') AS sku_width,
		// 							ISNULL(do.sku_width_unit, '') AS sku_width_unit,
		// 							ISNULL(do.sku_height, '0') AS sku_height,
		// 							ISNULL(do.sku_height_unit, '') AS sku_height_unit,
		// 							ISNULL(do.sku_volume, '0') AS sku_volume,
		// 							ISNULL(do.sku_volume_unit, '') AS sku_volume_unit,
		// 							ISNULL(do.sku_qty, '0') AS sku_qty,
		// 							ISNULL(do.sku_keterangan, '') AS sku_keterangan,
		// 							ISNULL(do.tipe_stock_nama, '') AS tipe_stock_nama
		// 							FROM delivery_order_detail_draft do
		// 							WHERE do.delivery_order_draft_id = '$id' ");


		// $query = $this->db->query("SELECT
		// 							'' AS delivery_order_detail_draft_id,
		// 							'' AS delivery_order_draft_id,
		// 							dod2.sku_id AS sku_id,
		// 							do.depo_id AS gudang_id,
		// 							do.depo_detail_id AS gudang_detail_id,
		// 							sku.sku_kode AS sku_kode,
		// 							sku.sku_nama_produk AS sku_nama_produk,
		// 							0 AS sku_harga_satuan,
		// 							--ISNULL(do.sku_harga_satuan, '0') AS sku_harga_satuan,
		// 							ISNULL(do.sku_disc_percent, '0') AS sku_disc_percent,
		// 							ISNULL(do.sku_disc_rp, '0') AS sku_disc_rp,
		// 							0 AS sku_harga_nett,
		// 							--ISNULL(do.sku_harga_nett, '0') AS sku_harga_nett,
		// 							ISNULL(do.sku_request_expdate, '0') AS sku_request_expdate,
		// 							ISNULL(do.sku_filter_expdate, '0') AS sku_filter_expdate,
		// 							ISNULL(do.sku_filter_expdatebulan, '0') AS sku_filter_expdatebulan,
		// 							ISNULL(do.sku_filter_expdatetahun, '0') AS sku_filter_expdatetahun,
		// 							ISNULL(sku.sku_weight, '0') AS sku_weight,
		// 							ISNULL(sku.sku_weight_unit, '') AS sku_weight_unit,
		// 							ISNULL(sku.sku_length, '0') AS sku_length,
		// 							ISNULL(sku.sku_length_unit, '') AS sku_length_unit,
		// 							ISNULL(sku.sku_width, '0') AS sku_width,
		// 							ISNULL(sku.sku_width_unit, '') AS sku_width_unit,
		// 							ISNULL(sku.sku_height, '0') AS sku_height,
		// 							ISNULL(sku.sku_height_unit, '') AS sku_height_unit,
		// 							ISNULL(sku.sku_volume, '0') AS sku_volume,
		// 							ISNULL(sku.sku_volume_unit, '') AS sku_volume_unit,
		// 							case when d.delivery_order_status = 'not delivered' then ISNULL(do.sku_qty, '0') 
		// 							when d.delivery_order_status = 'partially delivered' then dod2.sku_qty end as sku_qty,
		// 							ISNULL(do.sku_keterangan, '') AS sku_keterangan,
		// 							ISNULL(do.tipe_stock_nama, '') AS tipe_stock_nama
		// 							FROM delivery_order_detail do
		// 							left join delivery_order as d on do.delivery_order_id = d.delivery_order_id
		// 							right JOIN (
		// 											SELECT DISTINCT
		// 										delivery_order_detail_id,
		// 										a.delivery_order_id,
		// 										sku_id,
		// 										sku_stock_id,
		// 										sku_expdate,
		// 										sku_qty
		// 									FROM delivery_order_detail2_gagal a 
		// 								INNER JOIN delivery_order b on a.delivery_order_id = b.delivery_order_id
		// 								WHERE a.delivery_order_id IN ('$id') $tipeA
		// 									 UNION
		// 									SELECT DISTINCT
		// 										delivery_order_detail_id,
		// 										a.delivery_order_id,
		// 										sku_id,
		// 										sku_stock_id,
		// 										sku_expdate,
		// 										sku_qty
		// 									FROM delivery_order_detail2_terkirim a 
		// 								INNER JOIN delivery_order b on a.delivery_order_id = b.delivery_order_id
		// 								WHERE a.delivery_order_id IN ('$id') $tipeB
		// 							) dod2 on dod2.delivery_order_id = do.delivery_order_id and dod2.delivery_order_detail_id = do.delivery_order_detail_id
		// 							INNER JOIN sku
		// 							ON dod2.sku_id = sku.sku_id
		// 							WHERE do.delivery_order_id = '$id' AND (d.delivery_order_status = 'not delivered' OR (d.delivery_order_status = 'partially delivered'))
		// 							UNION ALL
		// 							SELECT
		// 							'' AS delivery_order_detail_draft_id,
		// 							'' AS delivery_order_draft_id,
		// 							do.sku_id AS sku_id,
		// 							do.gudang_id,
		// 							do.gudang_detail_id,
		// 							do.sku_kode AS sku_kode,
		// 							do.sku_nama_produk AS sku_nama_produk,
		// 							ISNULL(do.sku_harga_satuan, '0') AS sku_harga_satuan,
		// 							ISNULL(do.sku_disc_percent, '0') AS sku_disc_percent,
		// 							ISNULL(do.sku_disc_rp, '0') AS sku_disc_rp,
		// 							ISNULL(do.sku_harga_nett, '0') AS sku_harga_nett,
		// 							ISNULL(do.sku_request_expdate, '0') AS sku_request_expdate,
		// 							ISNULL(do.sku_filter_expdate, '0') AS sku_filter_expdate,
		// 							ISNULL(do.sku_filter_expdatebulan, '0') AS sku_filter_expdatebulan,
		// 							ISNULL(do.sku_filter_expdatetahun, '0') AS sku_filter_expdatetahun,
		// 							ISNULL(do.sku_weight, '0') AS sku_weight,
		// 							ISNULL(do.sku_weight_unit, '') AS sku_weight_unit,
		// 							ISNULL(do.sku_length, '0') AS sku_length,
		// 							ISNULL(do.sku_length_unit, '') AS sku_length_unit,
		// 							ISNULL(do.sku_width, '0') AS sku_width,
		// 							ISNULL(do.sku_width_unit, '') AS sku_width_unit,
		// 							ISNULL(do.sku_height, '0') AS sku_height,
		// 							ISNULL(do.sku_height_unit, '') AS sku_height_unit,
		// 							ISNULL(do.sku_volume, '0') AS sku_volume,
		// 							ISNULL(do.sku_volume_unit, '') AS sku_volume_unit,
		// 							ISNULL(do.sku_qty, '0') AS sku_qty,
		// 							ISNULL(do.sku_keterangan, '') AS sku_keterangan,
		// 							ISNULL(do.tipe_stock_nama, '') AS tipe_stock_nama
		// 							FROM delivery_order_detail_draft do
		// 							WHERE do.delivery_order_draft_id = '$id' ");

		$query = $this->db->query("SELECT 
										'' AS delivery_order_detail_draft_id, 
										'' AS delivery_order_draft_id, 
										dod2.sku_id AS sku_id, 
										do.depo_id AS gudang_id, 
										dod.depo_detail_id AS gudang_detail_id, 
										sku.sku_kode AS sku_kode, 
										sku.sku_nama_produk AS sku_nama_produk, 
										0 AS sku_harga_satuan, 
										--ISNULL(do.sku_harga_satuan, '0') AS sku_harga_satuan,
										ISNULL(dod.sku_disc_percent, '0') AS sku_disc_percent, 
										ISNULL(dod.sku_disc_rp, '0') AS sku_disc_rp, 
										0 AS sku_harga_nett, 
										--ISNULL(do.sku_harga_nett, '0') AS sku_harga_nett,
										ISNULL(dod.sku_request_expdate, '0') AS sku_request_expdate, 
										ISNULL(dod.sku_filter_expdate, '0') AS sku_filter_expdate, 
										ISNULL(dod.sku_filter_expdatebulan, '0') AS sku_filter_expdatebulan, 
										ISNULL(dod.sku_filter_expdatetahun, '0') AS sku_filter_expdatetahun, 
										ISNULL(sku.sku_weight, '0') AS sku_weight, 
										ISNULL(sku.sku_weight_unit, '') AS sku_weight_unit, 
										ISNULL(sku.sku_length, '0') AS sku_length, 
										ISNULL(sku.sku_length_unit, '') AS sku_length_unit, 
										ISNULL(sku.sku_width, '0') AS sku_width, 
										ISNULL(sku.sku_width_unit, '') AS sku_width_unit, 
										ISNULL(sku.sku_height, '0') AS sku_height, 
										ISNULL(sku.sku_height_unit, '') AS sku_height_unit, 
										ISNULL(sku.sku_volume, '0') AS sku_volume, 
										ISNULL(sku.sku_volume_unit, '') AS sku_volume_unit, 
										case when do.delivery_order_status = 'not delivered' then ISNULL(dod.sku_qty, '0') when do.delivery_order_status = 'partially delivered' then dod2.sku_qty end as sku_qty, 
										ISNULL(dod.sku_keterangan, '') AS sku_keterangan, 
										ISNULL(dod.tipe_stock_nama, '') AS tipe_stock_nama
										FROM (SELECT
											delivery_order_detail_id,
											delivery_order_id,
											sku_id,
											SUM(sku_qty) AS sku_qty
										FROM (SELECT 
											DISTINCT delivery_order_detail_id, 
											a.delivery_order_id, 
											sku_id, 
											sku_stock_id, 
											sku_expdate, 
											sku_qty,
											'GAGAL' AS do_resc_status
										FROM  delivery_order_detail2_gagal a 
										INNER JOIN delivery_order b on a.delivery_order_id = b.delivery_order_id 
										WHERE a.delivery_order_id IN ('$id') 
										" . $tipeA . " 
										UNION 
										SELECT 
											DISTINCT delivery_order_detail_id, 
											a.delivery_order_id, 
											sku_id, 
											sku_stock_id, 
											sku_expdate, 
											sku_qty,
											'TERKIRIM' AS do_resc_status
										FROM  delivery_order_detail2_terkirim a 
										INNER JOIN delivery_order b on a.delivery_order_id = b.delivery_order_id 
										WHERE  a.delivery_order_id IN ('$id') 
										" . $tipeB . ") do_olahan
										GROUP BY delivery_order_detail_id,
												delivery_order_id,
												sku_id) dod2
										LEFT JOIN delivery_order_detail dod
										ON dod.delivery_order_detail_id = dod2.delivery_order_detail_id
										LEFT JOIN delivery_order do
										ON do.delivery_order_id = dod.delivery_order_id
										INNER JOIN sku 
										ON dod2.sku_id = sku.sku_id
										WHERE do.delivery_order_id = '$id' 
										AND do.delivery_order_status IN ('not delivered','partially delivered')");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKelolaDOGagalDetail2ByListId($id, $sku_id)
	{
		$query = $this->db->query("SELECT
									'' AS delivery_order_detail2_draft_id,
									'' AS delivery_order_detail_draft_id,
									'' AS delivery_order_draft_id,
									sku_id,
									sku_stock_id,
									sku_expdate,
									ISNULL(sku_qty,'0') sku_qty
									FROM delivery_order_detail2
									WHERE delivery_order_id = '$id' AND sku_id = '$sku_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetTotalKelolaDOGagalByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $search, $so_eksternal, $is_reschedule)
	{

		if ($do_no == "") {
			$do_no = "";
		} else {
			$do_no = "AND do.delivery_order_kode LIKE '%" . $do_no . "%' ";
		}

		if ($so_eksternal == "") {
			$so_eksternal = "";
		} else {
			$so_eksternal = "AND so.sales_order_no_po LIKE '%" . $so_eksternal . "%' ";
		}

		if ($customer == "") {
			$customer = "";
		} else {
			$customer = "AND do.delivery_order_kirim_nama LIKE '%" . $customer . "%' ";
		}

		if ($alamat == "") {
			$alamat = "";
		} else {
			$alamat = "AND delivery_order_kirim_alamat LIKE '%" . $alamat . "%' ";
		}

		if ($tipe_pembayaran == "") {
			$tipe_pembayaran = "";
		} else {
			$tipe_pembayaran = "AND do.delivery_order_tipe_pembayaran = '" . $tipe_pembayaran . "' ";
		}

		if ($tipe_layanan == "") {
			$tipe_layanan = "";
		} else {
			$tipe_layanan = "AND do.delivery_order_tipe_layanan = '" . $tipe_layanan . "' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND do.delivery_order_status = '" . $status . "' ";
		}

		if ($tipe == "") {
			$tipe = "";
		} else {
			$tipe = "AND do.tipe_delivery_order_id = '" . $tipe . "' ";
		}
		if ($segmen1 == "") {
			$segmen1 = "";
		} else {
			$segmen1 = "AND cp.client_pt_segmen_id1 = '" . $segmen1 . "' ";
		}
		if ($segmen2 == "") {
			$segmen2 = "";
		} else {
			$segmen2 = "AND cp.client_pt_segmen_id2 = '" . $segmen2 . "' ";
		}
		if ($segmen3 == "") {
			$segmen3 = "";
		} else {
			$segmen3 = "AND cp.client_pt_segmen_id3 = '" . $segmen3 . "' ";
		}

		if ($is_reschedule == "") {
			$is_reschedule = "AND do_draft.delivery_order_draft_id IS NULL AND do.is_reschedule IS NULL";
		} else {
			$is_reschedule = "AND do_draft.delivery_order_draft_id IS NOT NULL AND do.is_reschedule = '$is_reschedule'";
		}

		if ($search == "") {
			$search = "";
		} else {
			$search = "AND (do.delivery_order_kode LIKE '%" . $search . "%' OR CASE WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama ELSE do.delivery_order_kirim_nama END LIKE '%" . $search . "%' OR CASE WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat ELSE do.delivery_order_kirim_alamat END LIKE '%" . $search . "%') ";
			// $search = "AND (do.delivery_order_kode LIKE '%" . $search . "%' OR do.delivery_order_kirim_nama LIKE '%" . $search . "%' OR do.delivery_order_kirim_alamat LIKE '%" . $search . "%') ";
		}

		$query = $this->db->query("SELECT 
									COUNT(DISTINCT do.delivery_order_id) AS jumlah
									FROM delivery_order do
									LEFT JOIN sales_order so
									ON so.sales_order_id = do.sales_order_id
									LEFT JOIN delivery_order_draft do_draft
									ON do_draft.delivery_order_draft_reff_id = do.delivery_order_id
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									LEFT JOIN client_pt cp
									ON cp.client_pt_id = do.client_pt_id
									LEFT JOIN client_pt_segmen cps1
									ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
									LEFT JOIN client_pt_segmen cps2
									ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
									LEFT JOIN client_pt_segmen cps3
									ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
									WHERE format(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND (
										do.delivery_order_status = 'not delivered'
										OR do.delivery_order_status = 'partially delivered'
									)
									AND do.delivery_order_id IS NOT NULL
									" . $is_reschedule . "
									" . $do_no . "
									" . $so_eksternal . "
									" . $customer . "
									" . $alamat . "
									" . $tipe_pembayaran . "
									" . $tipe_layanan . "
									" . $tipe . "
									" . $segmen1 . "
									" . $segmen2 . "
									" . $segmen3 . "
									" . $search . "
									" . $status . " ");


		/* -- QUERY LAMA 2023-12-13
		$query = $this->db->query("select 
									COUNT(DISTINCT do.delivery_order_id) AS jumlah
									from (SELECT
									do.delivery_order_id,
									do.delivery_order_kode,
									so.sales_order_id,
									so.sales_order_no_po,
									cp.client_pt_segmen_id1,
									cp.client_pt_segmen_id2,
									cp.client_pt_segmen_id3,
									cps1.client_pt_segmen_nama AS client_pt_segmen_nama1,
									cps2.client_pt_segmen_nama AS client_pt_segmen_nama2,
									cps3.client_pt_segmen_nama AS client_pt_segmen_nama3,
									ISNULL(so.sales_order_kode, '') AS sales_order_kode,
									format(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
									format(do.delivery_order_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_tgl_expired_do,
									format(do.delivery_order_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_tgl_surat_jalan,
									format(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_rencana_kirim,
									format(do.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_aktual_kirim,
									format(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
									do.delivery_order_update_tgl,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.principle_id
										ELSE do.client_pt_id
									END client_pt_id,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
										ELSE do.delivery_order_kirim_nama
									END delivery_order_kirim_nama,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
										ELSE do.delivery_order_kirim_alamat
									END delivery_order_kirim_alamat,
									CASE
										WHEN do.delivery_order_tipe_pembayaran = '0' THEN 'TUNAI'
										ELSE 'NON TUNAI'
									END AS delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias,
									do.delivery_order_status,
									do.delivery_order_kirim_area,
									ISNULL(do.delivery_order_nominal_tunai, 0) AS delivery_order_nominal_tunai
									FROM delivery_order do
									LEFT JOIN sales_order so
									ON so.sales_order_id = do.sales_order_id
									LEFT JOIN delivery_order_draft do_draft
									ON do_draft.delivery_order_draft_reff_id = do.delivery_order_id
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									LEFT JOIN client_pt cp
									ON cp.client_pt_id = do.client_pt_id
									LEFT JOIN client_pt_segmen cps1
									ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
									LEFT JOIN client_pt_segmen cps2
									ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
									LEFT JOIN client_pt_segmen cps3
									ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
									WHERE format(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND (
										do.delivery_order_status = 'not delivered'
										OR do.delivery_order_status = 'partially delivered'
									)
									AND do_draft.delivery_order_draft_id IS NULL
									) do
									WHERE do.delivery_order_id IS NOT NULL
									" . $do_no . "
									" . $so_eksternal . "
									" . $customer . "
									" . $alamat . "
									" . $tipe_pembayaran . "
									" . $tipe_layanan . "
									" . $tipe . "
									" . $segmen1 . "
									" . $segmen2 . "
									" . $segmen3 . "
									" . $search . "
									" . $status . " ");

		*/
		//BACKUP query lama
		/**UNION ALL
									SELECT
									do_draft.delivery_order_draft_id,
									do_draft.delivery_order_draft_kode,
									so.sales_order_id,
									so.sales_order_no_po,
									cp.client_pt_segmen_id1,
									cp.client_pt_segmen_id2,
									cp.client_pt_segmen_id3,
									cps1.client_pt_segmen_nama AS client_pt_segmen_nama1,
									cps2.client_pt_segmen_nama AS client_pt_segmen_nama2,
									cps3.client_pt_segmen_nama AS client_pt_segmen_nama3,
									ISNULL(so.sales_order_kode, '') AS sales_order_kode,
									format(do_draft.delivery_order_draft_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
									format(do_draft.delivery_order_draft_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_expired_do,
									format(do_draft.delivery_order_draft_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_draft_tgl_surat_jalan,
									format(do_draft.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_rencana_kirim,
									format(do_draft.delivery_order_draft_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_aktual_kirim,
									format(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
									do.delivery_order_update_tgl,
									CASE
										WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.pabrik_id
										ELSE do_draft.client_pt_id
									END client_pt_id,
									CASE
										WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.delivery_order_draft_ambil_nama
										ELSE do_draft.delivery_order_draft_kirim_nama
									END delivery_order_draft_kirim_nama,
									CASE
										WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.delivery_order_draft_ambil_alamat
										ELSE do_draft.delivery_order_draft_kirim_alamat
									END delivery_order_draft_kirim_alamat,
									CASE
										WHEN do_draft.delivery_order_draft_tipe_pembayaran = '0' THEN 'TUNAI'
										ELSE 'NON TUNAI'
									END AS delivery_order_draft_tipe_pembayaran,
									do_draft.delivery_order_draft_tipe_layanan,
									do_draft.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias,
									do_draft.delivery_order_draft_status,
									do_draft.delivery_order_draft_kirim_area,
									ISNULL(do_draft.delivery_order_draft_nominal_tunai, 0) AS delivery_order_draft_nominal_tunai
									FROM delivery_order_draft do_draft
									LEFT JOIN FAS.dbo.sales_order so
									ON so.sales_order_id = do_draft.sales_order_id
									LEFT JOIN delivery_order do
									ON do.delivery_order_draft_id = do_draft.delivery_order_draft_id
									LEFT JOIN tipe_delivery_order tipe
									ON do_draft.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									LEFT JOIN client_pt cp
									ON cp.client_pt_id = do_draft.client_pt_id
									LEFT JOIN client_pt_segmen cps1
									ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
									LEFT JOIN client_pt_segmen cps2
									ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
									LEFT JOIN client_pt_segmen cps3
									ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
									WHERE format(do_draft.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND do_draft.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND do.delivery_order_draft_id IS NULL
									AND do_draft.delivery_order_draft_status = 'Approved' */
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->jumlah;
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKelolaDOGagalByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $start, $end, $search, $so_eksternal, $is_reschedule)
	{

		if ($do_no == "") {
			$do_no = "";
		} else {
			$do_no = "AND do.delivery_order_kode LIKE '%" . $do_no . "%' ";
		}

		if ($so_eksternal == "") {
			$so_eksternal = "";
		} else {
			$so_eksternal = "AND do.sales_order_no_po LIKE '%" . $so_eksternal . "%' ";
		}

		if ($customer == "") {
			$customer = "";
		} else {
			$customer = "AND do.delivery_order_kirim_nama LIKE '%" . $customer . "%' ";
		}

		if ($alamat == "") {
			$alamat = "";
		} else {
			$alamat = "AND delivery_order_kirim_alamat LIKE '%" . $alamat . "%' ";
		}

		if ($tipe_pembayaran == "") {
			$tipe_pembayaran = "";
		} else {
			$tipe_pembayaran = "AND do.delivery_order_tipe_pembayaran = '" . $tipe_pembayaran . "' ";
		}

		if ($tipe_layanan == "") {
			$tipe_layanan = "";
		} else {
			$tipe_layanan = "AND do.delivery_order_tipe_layanan = '" . $tipe_layanan . "' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND do.delivery_order_status = '" . $status . "' ";
		}

		if ($tipe == "") {
			$tipe = "";
		} else {
			$tipe = "AND do.tipe_delivery_order_id = '" . $tipe . "' ";
		}
		if ($segmen1 == "") {
			$segmen1 = "";
		} else {
			$segmen1 = "AND cp.client_pt_segmen_id1 = '" . $segmen1 . "' ";
		}
		if ($segmen2 == "") {
			$segmen2 = "";
		} else {
			$segmen2 = "AND cp.client_pt_segmen_id2 = '" . $segmen2 . "' ";
		}
		if ($segmen3 == "") {
			$segmen3 = "";
		} else {
			$segmen3 = "AND cp.client_pt_segmen_id3 = '" . $segmen3 . "' ";
		}

		if ($is_reschedule == "") {
			$is_reschedule = "AND do_draft.delivery_order_draft_id IS NULL AND do.is_reschedule IS NULL";
		} else {
			$is_reschedule = "AND do_draft.delivery_order_draft_id IS NOT NULL AND do.is_reschedule = '$is_reschedule'";
		}

		if ($search == "") {
			$search = "";
		} else {
			$search = "AND (do.delivery_order_kode LIKE '%" . $search . "%' OR CASE WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama ELSE do.delivery_order_kirim_nama END LIKE '%" . $search . "%' OR CASE WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat ELSE do.delivery_order_kirim_alamat END LIKE '%" . $search . "%') ";
			// $search = "AND (do.delivery_order_kode LIKE '%" . $search . "%' OR do.delivery_order_kirim_nama LIKE '%" . $search . "%' OR do.delivery_order_kirim_alamat LIKE '%" . $search . "%') ";
		}

		$query = $this->db->query("select 
									ROW_NUMBER() OVER (ORDER BY do.delivery_order_tgl_rencana_kirim DESC) - 1 AS idx,
									do.*
									from (SELECT
									do.delivery_order_id,
									do.delivery_order_kode,
									so.sales_order_id,
									so.sales_order_no_po,
									cp.client_pt_segmen_id1,
									cp.client_pt_segmen_id2,
									cp.client_pt_segmen_id3,
									cps1.client_pt_segmen_nama AS client_pt_segmen_nama1,
									cps2.client_pt_segmen_nama AS client_pt_segmen_nama2,
									cps3.client_pt_segmen_nama AS client_pt_segmen_nama3,
									ISNULL(so.sales_order_kode, '') AS sales_order_kode,
									format(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
									format(do.delivery_order_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_tgl_expired_do,
									format(do.delivery_order_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_tgl_surat_jalan,
									format(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_rencana_kirim,
									format(do.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_aktual_kirim,
									format(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
									do.delivery_order_update_tgl,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.principle_id
										ELSE do.client_pt_id
									END client_pt_id,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
										ELSE do.delivery_order_kirim_nama
									END delivery_order_kirim_nama,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
										ELSE do.delivery_order_kirim_alamat
									END delivery_order_kirim_alamat,
									CASE
										WHEN do.delivery_order_tipe_pembayaran = '0' THEN 'TUNAI'
										ELSE 'NON TUNAI'
									END AS delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias,
									do.delivery_order_status,
									do.delivery_order_kirim_area,
									ISNULL(do.delivery_order_nominal_tunai, 0) AS delivery_order_nominal_tunai
									FROM delivery_order do
									LEFT JOIN sales_order so
									ON so.sales_order_id = do.sales_order_id
									LEFT JOIN delivery_order_draft do_draft
									ON do_draft.delivery_order_draft_reff_id = do.delivery_order_id
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									LEFT JOIN client_pt cp
									ON cp.client_pt_id = do.client_pt_id
									LEFT JOIN client_pt_segmen cps1
									ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
									LEFT JOIN client_pt_segmen cps2
									ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
									LEFT JOIN client_pt_segmen cps3
									ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
									WHERE format(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND (
										do.delivery_order_status = 'not delivered'
										OR do.delivery_order_status = 'partially delivered'
									)
									" . $is_reschedule . "
									) do
									WHERE do.delivery_order_id IS NOT NULL
									" . $do_no . "
									" . $so_eksternal . "
									" . $customer . "
									" . $alamat . "
									" . $tipe_pembayaran . "
									" . $tipe_layanan . "
									" . $tipe . "
									" . $segmen1 . "
									" . $segmen2 . "
									" . $segmen3 . "
									" . $search . "
									" . $status . "
									ORDER BY do.delivery_order_tgl_aktual_kirim DESC, do.delivery_order_kirim_area ASC, do.delivery_order_kirim_nama ASC
									OFFSET " . $start . " ROWS
									FETCH NEXT " . $end . " ROWS ONLY");
		//BACKUP QUERY lama
		/**UNION ALL
									SELECT
									do_draft.delivery_order_draft_id,
									do_draft.delivery_order_draft_kode,
									so.sales_order_id,
									so.sales_order_no_po,
									cp.client_pt_segmen_id1,
									cp.client_pt_segmen_id2,
									cp.client_pt_segmen_id3,
									cps1.client_pt_segmen_nama AS client_pt_segmen_nama1,
									cps2.client_pt_segmen_nama AS client_pt_segmen_nama2,
									cps3.client_pt_segmen_nama AS client_pt_segmen_nama3,
									ISNULL(so.sales_order_kode, '') AS sales_order_kode,
									format(do_draft.delivery_order_draft_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
									format(do_draft.delivery_order_draft_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_expired_do,
									format(do_draft.delivery_order_draft_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_draft_tgl_surat_jalan,
									format(do_draft.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_rencana_kirim,
									format(do_draft.delivery_order_draft_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_aktual_kirim,
									format(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
									do.delivery_order_update_tgl,
									CASE
										WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.pabrik_id
										ELSE do_draft.client_pt_id
									END client_pt_id,
									CASE
										WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.delivery_order_draft_ambil_nama
										ELSE do_draft.delivery_order_draft_kirim_nama
									END delivery_order_draft_kirim_nama,
									CASE
										WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.delivery_order_draft_ambil_alamat
										ELSE do_draft.delivery_order_draft_kirim_alamat
									END delivery_order_draft_kirim_alamat,
									CASE
										WHEN do_draft.delivery_order_draft_tipe_pembayaran = '0' THEN 'TUNAI'
										ELSE 'NON TUNAI'
									END AS delivery_order_draft_tipe_pembayaran,
									do_draft.delivery_order_draft_tipe_layanan,
									do_draft.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias,
									do_draft.delivery_order_draft_status,
									do_draft.delivery_order_draft_kirim_area,
									ISNULL(do_draft.delivery_order_draft_nominal_tunai, 0) AS delivery_order_draft_nominal_tunai
									FROM delivery_order_draft do_draft
									LEFT JOIN FAS.dbo.sales_order so
									ON so.sales_order_id = do_draft.sales_order_id
									LEFT JOIN delivery_order do
									ON do.delivery_order_draft_id = do_draft.delivery_order_draft_id
									LEFT JOIN tipe_delivery_order tipe
									ON do_draft.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									LEFT JOIN client_pt cp
									ON cp.client_pt_id = do_draft.client_pt_id
									LEFT JOIN client_pt_segmen cps1
									ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
									LEFT JOIN client_pt_segmen cps2
									ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
									LEFT JOIN client_pt_segmen cps3
									ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
									WHERE format(do_draft.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND do_draft.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND do_draft.delivery_order_draft_status = 'Approved'
									AND do.delivery_order_draft_id IS NULL */
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKelolaDOGagalRescheduleByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $so_eksternal)
	{

		if ($do_no == "") {
			$do_no = "";
		} else {
			$do_no = "AND do.delivery_order_kode LIKE '%" . $do_no . "%' ";
		}

		if ($so_eksternal == "") {
			$so_eksternal = "";
		} else {
			$so_eksternal = "AND do.sales_order_no_po LIKE '%" . $so_eksternal . "%' ";
		}

		if ($customer == "") {
			$customer = "";
		} else {
			$customer = "AND do.delivery_order_kirim_nama LIKE '%" . $customer . "%' ";
		}

		if ($alamat == "") {
			$alamat = "";
		} else {
			$alamat = "AND delivery_order_kirim_alamat LIKE '%" . $alamat . "%' ";
		}

		if ($tipe_pembayaran == "") {
			$tipe_pembayaran = "";
		} else {
			$tipe_pembayaran = "AND do.delivery_order_tipe_pembayaran = '" . $tipe_pembayaran . "' ";
		}

		if ($tipe_layanan == "") {
			$tipe_layanan = "";
		} else {
			$tipe_layanan = "AND do.delivery_order_tipe_layanan = '" . $tipe_layanan . "' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND do.delivery_order_status = '" . $status . "' ";
		}

		if ($tipe == "") {
			$tipe = "";
		} else {
			$tipe = "AND do.tipe_delivery_order_id = '" . $tipe . "' ";
		}
		if ($segmen1 == "") {
			$segmen1 = "";
		} else {
			$segmen1 = "AND cp.client_pt_segmen_id1 = '" . $segmen1 . "' ";
		}
		if ($segmen2 == "") {
			$segmen2 = "";
		} else {
			$segmen2 = "AND cp.client_pt_segmen_id2 = '" . $segmen2 . "' ";
		}
		if ($segmen3 == "") {
			$segmen3 = "";
		} else {
			$segmen3 = "AND cp.client_pt_segmen_id3 = '" . $segmen3 . "' ";
		}

		$query = $this->db->query("select
									ROW_NUMBER() OVER (ORDER BY do.delivery_order_tgl_rencana_kirim DESC) - 1 AS idx,
									do.*
									from (SELECT
									do.delivery_order_id,
									do.delivery_order_kode,
									so.sales_order_id,
									so.sales_order_no_po,
									cp.client_pt_segmen_id1,
									cp.client_pt_segmen_id2,
									cp.client_pt_segmen_id3,
									cps1.client_pt_segmen_nama AS client_pt_segmen_nama1,
									cps2.client_pt_segmen_nama AS client_pt_segmen_nama2,
									cps3.client_pt_segmen_nama AS client_pt_segmen_nama3,
									ISNULL(so.sales_order_kode, '') AS sales_order_kode,
									format(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
									format(do.delivery_order_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_tgl_expired_do,
									format(do.delivery_order_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_tgl_surat_jalan,
									format(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_rencana_kirim,
									format(do.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_aktual_kirim,
									format(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
									do.delivery_order_update_tgl,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.principle_id
										ELSE do.client_pt_id
									END client_pt_id,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
										ELSE do.delivery_order_kirim_nama
									END delivery_order_kirim_nama,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
										ELSE do.delivery_order_kirim_alamat
									END delivery_order_kirim_alamat,
									CASE
										WHEN do.delivery_order_tipe_pembayaran = '0' THEN 'TUNAI'
										ELSE 'NON TUNAI'
									END AS delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias,
									do.delivery_order_status,
									do.delivery_order_kirim_area,
									ISNULL(do.delivery_order_nominal_tunai, 0) AS delivery_order_nominal_tunai
									FROM delivery_order do
									LEFT JOIN sales_order so
									ON so.sales_order_id = do.sales_order_id
									LEFT JOIN delivery_order_draft do_draft
									ON do_draft.delivery_order_draft_reff_id = do.delivery_order_id
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									LEFT JOIN client_pt cp
									ON cp.client_pt_id = do.client_pt_id
									LEFT JOIN client_pt_segmen cps1
									ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
									LEFT JOIN client_pt_segmen cps2
									ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
									LEFT JOIN client_pt_segmen cps3
									ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
									WHERE format(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND (do.delivery_order_status = 'not delivered' OR do.delivery_order_status = 'partially delivered')
									AND do.is_reschedule IN ('1')
									) do
									WHERE do.delivery_order_id IS NOT NULL
									" . $do_no . "
									" . $so_eksternal . "
									" . $customer . "
									" . $alamat . "
									" . $tipe_pembayaran . "
									" . $tipe_layanan . "
									" . $tipe . "
									" . $segmen1 . "
									" . $segmen2 . "
									" . $segmen3 . "
									" . $status . "
									ORDER BY do.delivery_order_tgl_aktual_kirim DESC, do.delivery_order_kirim_area ASC, do.delivery_order_kirim_nama ASC");
		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKelolaDOGagalNotRescheduleByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $so_eksternal)
	{

		if ($do_no == "") {
			$do_no = "";
		} else {
			$do_no = "AND do.delivery_order_kode LIKE '%" . $do_no . "%' ";
		}

		if ($so_eksternal == "") {
			$so_eksternal = "";
		} else {
			$so_eksternal = "AND do.sales_order_no_po LIKE '%" . $so_eksternal . "%' ";
		}

		if ($customer == "") {
			$customer = "";
		} else {
			$customer = "AND do.delivery_order_kirim_nama LIKE '%" . $customer . "%' ";
		}

		if ($alamat == "") {
			$alamat = "";
		} else {
			$alamat = "AND delivery_order_kirim_alamat LIKE '%" . $alamat . "%' ";
		}

		if ($tipe_pembayaran == "") {
			$tipe_pembayaran = "";
		} else {
			$tipe_pembayaran = "AND do.delivery_order_tipe_pembayaran = '" . $tipe_pembayaran . "' ";
		}

		if ($tipe_layanan == "") {
			$tipe_layanan = "";
		} else {
			$tipe_layanan = "AND do.delivery_order_tipe_layanan = '" . $tipe_layanan . "' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND do.delivery_order_status = '" . $status . "' ";
		}

		if ($tipe == "") {
			$tipe = "";
		} else {
			$tipe = "AND do.tipe_delivery_order_id = '" . $tipe . "' ";
		}
		if ($segmen1 == "") {
			$segmen1 = "";
		} else {
			$segmen1 = "AND cp.client_pt_segmen_id1 = '" . $segmen1 . "' ";
		}
		if ($segmen2 == "") {
			$segmen2 = "";
		} else {
			$segmen2 = "AND cp.client_pt_segmen_id2 = '" . $segmen2 . "' ";
		}
		if ($segmen3 == "") {
			$segmen3 = "";
		} else {
			$segmen3 = "AND cp.client_pt_segmen_id3 = '" . $segmen3 . "' ";
		}

		$query = $this->db->query("select 
									ROW_NUMBER() OVER (ORDER BY do.delivery_order_tgl_rencana_kirim DESC) - 1 AS idx,
									do.*
									from (SELECT
									do.delivery_order_id,
									do.delivery_order_kode,
									so.sales_order_id,
									so.sales_order_no_po,
									cp.client_pt_segmen_id1,
									cp.client_pt_segmen_id2,
									cp.client_pt_segmen_id3,
									cps1.client_pt_segmen_nama AS client_pt_segmen_nama1,
									cps2.client_pt_segmen_nama AS client_pt_segmen_nama2,
									cps3.client_pt_segmen_nama AS client_pt_segmen_nama3,
									ISNULL(so.sales_order_kode, '') AS sales_order_kode,
									format(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
									format(do.delivery_order_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_tgl_expired_do,
									format(do.delivery_order_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_tgl_surat_jalan,
									format(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_rencana_kirim,
									format(do.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_aktual_kirim,
									format(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
									do.delivery_order_update_tgl,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.principle_id
										ELSE do.client_pt_id
									END client_pt_id,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
										ELSE do.delivery_order_kirim_nama
									END delivery_order_kirim_nama,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
										ELSE do.delivery_order_kirim_alamat
									END delivery_order_kirim_alamat,
									CASE
										WHEN do.delivery_order_tipe_pembayaran = '0' THEN 'TUNAI'
										ELSE 'NON TUNAI'
									END AS delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias,
									do.delivery_order_status,
									do.delivery_order_kirim_area,
									ISNULL(do.delivery_order_nominal_tunai, 0) AS delivery_order_nominal_tunai
									FROM delivery_order do
									LEFT JOIN sales_order so
									ON so.sales_order_id = do.sales_order_id
									LEFT JOIN delivery_order_draft do_draft
									ON do_draft.delivery_order_draft_reff_id = do.delivery_order_id
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									LEFT JOIN client_pt cp
									ON cp.client_pt_id = do.client_pt_id
									LEFT JOIN client_pt_segmen cps1
									ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
									LEFT JOIN client_pt_segmen cps2
									ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
									LEFT JOIN client_pt_segmen cps3
									ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
									WHERE format(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND (do.delivery_order_status = 'not delivered' OR do.delivery_order_status = 'partially delivered')
									AND do.is_reschedule IN ('0')
									) do
									WHERE do.delivery_order_id IS NOT NULL
									" . $do_no . "
									" . $so_eksternal . "
									" . $customer . "
									" . $alamat . "
									" . $tipe_pembayaran . "
									" . $tipe_layanan . "
									" . $tipe . "
									" . $segmen1 . "
									" . $segmen2 . "
									" . $segmen3 . "
									" . $status . "
									ORDER BY do.delivery_order_tgl_aktual_kirim DESC, do.delivery_order_kirim_area ASC, do.delivery_order_kirim_nama ASC");
		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKelolaDOGagalNotRescheduleBuatDraftByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $so_eksternal)
	{

		if ($do_no == "") {
			$do_no = "";
		} else {
			$do_no = "AND do.delivery_order_kode LIKE '%" . $do_no . "%' ";
		}

		if ($so_eksternal == "") {
			$so_eksternal = "";
		} else {
			$so_eksternal = "AND do.sales_order_no_po LIKE '%" . $so_eksternal . "%' ";
		}

		if ($customer == "") {
			$customer = "";
		} else {
			$customer = "AND do.delivery_order_kirim_nama LIKE '%" . $customer . "%' ";
		}

		if ($alamat == "") {
			$alamat = "";
		} else {
			$alamat = "AND delivery_order_kirim_alamat LIKE '%" . $alamat . "%' ";
		}

		if ($tipe_pembayaran == "") {
			$tipe_pembayaran = "";
		} else {
			$tipe_pembayaran = "AND do.delivery_order_tipe_pembayaran = '" . $tipe_pembayaran . "' ";
		}

		if ($tipe_layanan == "") {
			$tipe_layanan = "";
		} else {
			$tipe_layanan = "AND do.delivery_order_tipe_layanan = '" . $tipe_layanan . "' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND do.delivery_order_status = '" . $status . "' ";
		}

		if ($tipe == "") {
			$tipe = "";
		} else {
			$tipe = "AND do.tipe_delivery_order_id = '" . $tipe . "' ";
		}
		if ($segmen1 == "") {
			$segmen1 = "";
		} else {
			$segmen1 = "AND cp.client_pt_segmen_id1 = '" . $segmen1 . "' ";
		}
		if ($segmen2 == "") {
			$segmen2 = "";
		} else {
			$segmen2 = "AND cp.client_pt_segmen_id2 = '" . $segmen2 . "' ";
		}
		if ($segmen3 == "") {
			$segmen3 = "";
		} else {
			$segmen3 = "AND cp.client_pt_segmen_id3 = '" . $segmen3 . "' ";
		}

		$query = $this->db->query("select 
									ROW_NUMBER() OVER (ORDER BY do.delivery_order_tgl_rencana_kirim DESC) - 1 AS idx,
									do.*
									from (SELECT
									do.delivery_order_id,
									do.delivery_order_kode,
									so.sales_order_id,
									so.sales_order_no_po,
									cp.client_pt_segmen_id1,
									cp.client_pt_segmen_id2,
									cp.client_pt_segmen_id3,
									cps1.client_pt_segmen_nama AS client_pt_segmen_nama1,
									cps2.client_pt_segmen_nama AS client_pt_segmen_nama2,
									cps3.client_pt_segmen_nama AS client_pt_segmen_nama3,
									ISNULL(so.sales_order_kode, '') AS sales_order_kode,
									format(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
									format(do.delivery_order_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_tgl_expired_do,
									format(do.delivery_order_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_tgl_surat_jalan,
									format(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_rencana_kirim,
									format(do.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_aktual_kirim,
									format(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
									do.delivery_order_update_tgl,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.principle_id
										ELSE do.client_pt_id
									END client_pt_id,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
										ELSE do.delivery_order_kirim_nama
									END delivery_order_kirim_nama,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
										ELSE do.delivery_order_kirim_alamat
									END delivery_order_kirim_alamat,
									CASE
										WHEN do.delivery_order_tipe_pembayaran = '0' THEN 'TUNAI'
										ELSE 'NON TUNAI'
									END AS delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias,
									do.delivery_order_status,
									do.delivery_order_kirim_area,
									ISNULL(do.delivery_order_nominal_tunai, 0) AS delivery_order_nominal_tunai
									FROM delivery_order do
									LEFT JOIN sales_order so
									ON so.sales_order_id = do.sales_order_id
									LEFT JOIN delivery_order_draft do_draft
									ON do_draft.delivery_order_draft_reff_id = do.delivery_order_id
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									LEFT JOIN client_pt cp
									ON cp.client_pt_id = do.client_pt_id
									LEFT JOIN client_pt_segmen cps1
									ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
									LEFT JOIN client_pt_segmen cps2
									ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
									LEFT JOIN client_pt_segmen cps3
									ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
									WHERE format(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND (do.delivery_order_status = 'not delivered' OR do.delivery_order_status = 'partially delivered')
									AND do.is_reschedule IN ('2')
									) do
									WHERE do.delivery_order_id IS NOT NULL
									" . $do_no . "
									" . $so_eksternal . "
									" . $customer . "
									" . $alamat . "
									" . $tipe_pembayaran . "
									" . $tipe_layanan . "
									" . $tipe . "
									" . $segmen1 . "
									" . $segmen2 . "
									" . $segmen3 . "
									" . $status . "
									ORDER BY do.delivery_order_tgl_aktual_kirim DESC, do.delivery_order_kirim_area ASC, do.delivery_order_kirim_nama ASC");
		if ($query->num_rows() == 0) {
			$query = array();
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

	public function GetLokasi()
	{
		return $this->db->query("select tipe_stock_nama as nama from dbo.gettipestock() where tipe_stock_is_aktif = '1' order by tipe_stock_urut ASC")->result();
	}

	public function GetFDJRByFilter($tgl1, $tgl2, $area)
	{
		if ($area == "") {
			$area = "";
		} else {
			$area = "AND area.area_nama = '$area' ";
		}

		$query = $this->db->query("SELECT DISTINCT
									do.delivery_order_batch_id,
									do.delivery_order_batch_kode,
									do.delivery_order_batch_tipe_layanan_id,
									do.delivery_order_batch_tipe_layanan_nama,
									format(do.delivery_order_batch_tanggal, 'dd-MM-yyyy') AS delivery_order_batch_tanggal,
									format(do.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim,
									do.tipe_delivery_order_id,
									do.delivery_order_batch_status,
									tipe_delivery_order.tipe_delivery_order_alias,
									karyawan.karyawan_nama,
									do.delivery_order_batch_update_tgl,
									do.delivery_order_batch_update_who
									FROM delivery_order_batch do
									LEFT JOIN delivery_order_area ON do.delivery_order_batch_id = delivery_order_area.delivery_order_batch_id
									LEFT JOIN area ON area.area_id = delivery_order_area.area_id
									LEFT JOIN tipe_delivery_order on tipe_delivery_order.tipe_delivery_order_id = do.tipe_delivery_order_id
									LEFT JOIN karyawan ON do.karyawan_id = karyawan.karyawan_id
									WHERE format(do.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND do.delivery_order_batch_status = 'Draft'
									" . $area . "
									ORDER BY format(do.delivery_order_batch_tanggal, 'dd-MM-yyyy'), do.delivery_order_batch_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetLastNoUrut($delivery_order_batch_id)
	{
		$this->db->select("*")
			->from("delivery_order")
			->where("delivery_order_batch_id", $delivery_order_batch_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
	}

	public function cek_do_by_do_draft($delivery_order_draft_id)
	{
		$this->db->select("*")
			->from("delivery_order")
			->where("delivery_order_draft_id", $delivery_order_draft_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
	}

	public function GetDataSKUByReqNull($client_wms_id, $sku_id)
	{
		// return $this->db->select("*")
		// 	->from("sku_stock")->where("client_wms_id", $client_wms_id)
		// 	->where("sku_id", $sku_id)->order_by("sku_stock_expired_date", "ASC")->get()->result();
		// return $this->db->select("ss.*")
		// 	->from("sku_stock ss")
		// 	->join("depo_detail dd", "ss.depo_detail_id = dd.depo_detail_id", "left")
		// 	->where("ss.client_wms_id", $client_wms_id)
		// 	->where("ss.sku_id", $sku_id)
		// 	->where("dd.depo_detail_flag_jual", 1)
		// 	->order_by("ss.sku_stock_expired_date", "ASC")->get()->result();

		$query = $this->db->query("SELECT 
										sku.sku_konversi_faktor,
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
										ss.sku_stock_is_deleted
									FROM sku_stock ss
									LEFT JOIN depo_detail dd
									ON ss.depo_detail_id = dd.depo_detail_id
									inner join sku on sku.sku_id = ss.sku_id
									WHERE ss.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND ss.sku_id = '$sku_id'
									AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '1'
									AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
									ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		//AND ss.client_wms_id = '$client_wms_id'

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUByStatusFlag($client_wms_id, $sku_id)
	{
		$query = $this->db->query("SELECT 
										sku.sku_konversi_faktor,
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
										ss.sku_stock_is_deleted
									FROM sku_stock ss
									LEFT JOIN depo_detail dd
									ON ss.depo_detail_id = dd.depo_detail_id
									inner join sku on sku.sku_id = ss.sku_id
									WHERE ss.client_wms_id = '$client_wms_id'
									AND ss.sku_id = '$sku_id'
									AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '1'
									AND ISNULL(dd.depo_detail_flag_jual,'0') = '1'
									AND ISNULL(dd.depo_detail_is_flashout,'0') = '0'
									AND ISNULL(dd.depo_detail_is_qa,'0') = '0'
									AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
									ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUByReqBesarDari($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
	{

		// return $this->db->query("SELECT *
		// 		FROM sku_stock
		// 		WHERE client_wms_id = '$client_wms_id' AND sku_id = '$sku_id'
		// 			AND (FORMAT(sku_stock_expired_date, 'MM') >= MONTH(GETDATE()) 
		// 						AND format(sku_stock_expired_date, 'MM') < MONTH(GETDATE()) + $sku_filter_expdate)
		// 		ORDER BY sku_stock_expired_date ASC")->result();

		// return $this->db->query("SELECT ss.*
		// 			FROM sku_stock ss
		// 			LEFT JOIN depo_detail dd ON ss.depo_detail_id = dd.depo_detail_id
		// 			WHERE ss.client_wms_id = '$client_wms_id' AND ss.sku_id = '$sku_id'
		// 					AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
		// 					AND dd.depo_detail_flag_jual = '1'
		// 			ORDER BY ss.sku_stock_expired_date ASC")->result();

		$query = $this->db->query("SELECT 
								sku.sku_konversi_faktor,
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
								ss.sku_stock_is_deleted
								FROM sku_stock ss
								LEFT JOIN depo_detail dd
								ON ss.depo_detail_id = dd.depo_detail_id
								inner join sku on sku.sku_id = ss.sku_id
								WHERE ss.client_wms_id = '$client_wms_id' 
								AND ss.sku_id = '$sku_id'
								AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
								AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '1'
								AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
								ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUByReqKurangDari($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
	{

		// return $this->db->query("SELECT *
		// 		FROM sku_stock
		// 		WHERE client_wms_id = '$client_wms_id' AND sku_id = '$sku_id'
		// 			AND (FORMAT(sku_stock_expired_date, 'MM') < MONTH(GETDATE()) 
		// 						AND format(sku_stock_expired_date, 'MM') >= MONTH(GETDATE()) - $sku_filter_expdate)
		// 		ORDER BY sku_stock_expired_date ASC")->result();

		// return $this->db->query("SELECT ss.*
		// 	FROM sku_stock ss
		// 	LEFT JOIN depo_detail dd ON ss.depo_detail_id = dd.depo_detail_id
		// 	WHERE ss.client_wms_id = '$client_wms_id' AND ss.sku_id = '$sku_id'
		// 			AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= '$tgl_do'
		// 			AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') <= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
		// 			AND dd.depo_detail_flag_jual = '1'
		// 	ORDER BY ss.sku_stock_expired_date ASC")->result();

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
									ss.sku_stock_is_deleted
									FROM sku_stock ss
									LEFT JOIN depo_detail dd
									ON ss.depo_detail_id = dd.depo_detail_id
									WHERE ss.client_wms_id = '$client_wms_id' AND ss.sku_id = '$sku_id'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= '$tgl_do'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') <= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
									AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '1'
									AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
									ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function getNameSKUById($client_wms_id, $id)
	{
		return $this->db->select("sku_kode, sku_nama_produk as sku")->from("sku")->where("client_wms_id", $client_wms_id)->where("sku_id", $id)->get()->row();
	}

	public function get_delivery_order_draft_detail_msg()
	{

		$query = $this->db->query("SELECT
								msg.delivery_order_draft_detail_msg_id,
								msg.depo_id,
								msg.delivery_order_draft_id,
								do.delivery_order_kode AS delivery_order_draft_kode,
								msg.sku_kode,
								sku.sku_nama_produk,
								msg.delivery_order_draft_detail_msg AS pesan_error
							FROM delivery_order_draft_detail_msg msg
							LEFT JOIN delivery_order do
							ON msg.delivery_order_draft_id = do.delivery_order_id 
							LEFT JOIN sku
							ON sku.sku_kode = msg.sku_kode 
							where msg.depo_id = '" . $this->session->userdata('depo_id') . "' 
							ORDER BY do.delivery_order_kode, msg.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function insert_delivery_order_batch_from_kelola_do_gagal($dob_id, $delivery_order_batch_kode, $tipe_layanan, $tipe, $tanggal_kirim, $tipe_ekspedisi, $delivery_order_batch_tipe_layanan_id, $delivery_order_batch_tipe_layanan_no, $delivery_order_batch_tipe_layanan_nama, $kendaraan_id, $karyawan_id, $getVolWeigth, $getSumWeightAndVolume)
	{
		$kendaraan_id = $kendaraan_id == "" ? null : $kendaraan_id;
		$karyawan_id = $karyawan_id == "" ? null : $karyawan_id;
		$tanggal_kirim = date('Y-m-d', strtotime(str_replace("/", "-", $tanggal_kirim)));
		$kendaraan_volume_cm3_sisa = (int)$getVolWeigth->kendaraan_kap_vol - (int)$getSumWeightAndVolume->sum_volume;
		$kendaraan_berat_gr_sisa = (int)$getVolWeigth->kendaraan_kap_weight - (int)$getSumWeightAndVolume->sum_weight;

		$this->db->set("delivery_order_batch_id", $dob_id);
		$this->db->set("delivery_order_batch_kode", $delivery_order_batch_kode);
		$this->db->set("delivery_order_batch_create_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_batch_create_tgl", "GETDATE()", FALSE);
		$this->db->set("delivery_order_batch_tanggal",  "GETDATE()", FALSE);
		$this->db->set("delivery_order_batch_tanggal_kirim",  $tanggal_kirim);
		$this->db->set("delivery_order_batch_status",  "Draft");
		$this->db->set("depo_id",  $this->session->userdata('depo_id'));
		$this->db->set("delivery_order_batch_tipe_layanan_id", $delivery_order_batch_tipe_layanan_id);
		$this->db->set("delivery_order_batch_tipe_layanan_no", $delivery_order_batch_tipe_layanan_no);
		$this->db->set("delivery_order_batch_tipe_layanan_nama", $delivery_order_batch_tipe_layanan_nama);
		$this->db->set("kendaraan_volume_cm3_max", (int)$getVolWeigth->kendaraan_kap_vol);
		$this->db->set("kendaraan_berat_gr_max", (int)$getVolWeigth->kendaraan_kap_weight);
		$this->db->set("kendaraan_volume_cm3_terpakai", (int)$getSumWeightAndVolume->sum_volume);
		$this->db->set("kendaraan_berat_gr_terpakai", (int)$getSumWeightAndVolume->sum_weight);
		$this->db->set("kendaraan_volume_cm3_sisa", $kendaraan_volume_cm3_sisa);
		$this->db->set("kendaraan_berat_gr_sisa", $kendaraan_berat_gr_sisa);

		$this->db->set("tipe_delivery_order_id", $tipe);
		$this->db->set("tipe_ekspedisi_id", $tipe_ekspedisi);
		$this->db->set("kendaraan_id", $kendaraan_id);
		$this->db->set("karyawan_id", $karyawan_id);

		$queryinsert = $this->db->insert("delivery_order_batch");
		if ($queryinsert) {
			return 1;
		} else {
			return 0;
		}
	}
	public function InsertDoAreaIfNotExistByDO($delivery_order_batch_id, $delivery_order_id)
	{
		$getAreaIdByDo = $this->db->query("select area.area_id as area_id from delivery_order do left join area on area.area_nama = do.delivery_order_kirim_area where do.delivery_order_id = '$delivery_order_id'")->row()->area_id;

		$cekAreaByAreaDo = $this->db->query("select * from delivery_order_area where area_id ='$getAreaIdByDo'")->num_rows();

		if ($cekAreaByAreaDo == 0) {
			$this->db->set("delivery_order_area_id", "NEWID()", FALSE);
			$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
			$this->db->set("area_id", $getAreaIdByDo);

			return $this->db->insert("delivery_order_area");
		} else {
			return false;
		}
	}
	public function UpdateFdjr($delivery_order_batch_id)
	{
		$this->db->set("delivery_order_batch_update_tgl", "GETDATE()", false);
		$this->db->set("delivery_order_batch_update_who", $this->session->userdata('pengguna_username'));

		$this->db->where("delivery_order_batch_id", $delivery_order_batch_id);

		return $this->db->update("delivery_order_batch");
	}
	public function GetKapasitasKendaraan($kendaraan)
	{
		$this->db->select("*")
			->from("kendaraan")
			->where("kendaraan_id", $kendaraan)
			->order_by("kendaraan_nopol");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}

	public function UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id)
	{
		// $this->db->query("update sku_stock set sku_stock_alokasi = '$sisaAlokasi' where sku_stock_id ='$sku_stock_id'");
		// $this->db->set('sku_stock_alokasi', $sisaAlokasi);
		// $this->db->set('sku_stock_saldo_alokasi', $sisaSaldoAlokasi);
		// $this->db->where('sku_stock_id', $sku_stock_id);
		// return $this->db->update('sku_stock');
		return $this->db->query("exec insertupdate_sku_stock 'saldoalokasi_tambah', '$sku_stock_id', NULL, '$sisaSaldoAlokasi'");
	}

	public function getDeliveryOrderDetail2ByDOID($dod)
	{
		$query = $this->db->query("SELECT * FROM delivery_order_detail2 WHERE delivery_order_id = '$dod'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function updateIsRescheduleDeliveryOrder($status, $delivery_order_draft_id)
	{
		if ($status == 'reschedule') {
			$this->db->set("is_reschedule",  1);
		} else {
			$this->db->set("is_reschedule",  0);
		}

		$this->db->where_in("delivery_order_id", $delivery_order_draft_id);
		$this->db->update("delivery_order");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function cekTipeDO($do_id)
	{
		$query = $this->db->query("SELECT tipe_delivery_order_id FROM delivery_order WHERE delivery_order_id = '$do_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}

	public function insertMutasiStok($status, $delivery_order_draft_id)
	{
		$delivery_order_draft_id = "'" . implode("', '", $delivery_order_draft_id) . "'";

		// $data = $this->db->query("SELECT ss.client_wms_id,
		// 	ss.sku_stock_id,
		// 	ss.sku_id,
		// 	dod2.delivery_order_id,
		// 	s.principle_id,
		// 	isnull(dod2.sku_qty, 0) - isnull(dod2.sku_qty_kirim, 0) as selisih,
		// 	ss.sku_stock_expired_date as sku_expdate,
		// 	ss.depo_id
		// FROM sku_stock ss
		// inner JOIN (SELECT
		// *
		// FROM delivery_order_detail2
		// WHERE delivery_order_id IN ($delivery_order_draft_id)) dod2
		// ON ss.sku_id = dod2.sku_id
		// AND format(ss.sku_stock_expired_date, 'yyyy-MM-dd') = dod2.sku_expdate
		// Inner join sku s
		// ON ss.sku_id = s.sku_id and dod2.sku_id = s.sku_id
		// WHERE depo_detail_id = '66A9E421-19BE-4C56-9C86-6233DBDBA231'
		// ORDER BY ss.client_wms_id, s.principle_id, ss.sku_id, ss.sku_stock_id")->result();

		$data = $this->db->query("SELECT ss.client_wms_id,
			ss.sku_stock_id,
			ss.sku_id,
			dod2.delivery_order_id,
			s.principle_id,
			isnull(dod2.sku_qty, 0) as selisih,
			ss.sku_stock_expired_date as sku_expdate,
			ss.depo_id
		FROM sku_stock ss
		inner JOIN (SELECT
		*
		FROM delivery_order_detail2_gagal
		WHERE delivery_order_id IN ($delivery_order_draft_id)) dod2
		ON ss.sku_id = dod2.sku_id
		AND format(ss.sku_stock_expired_date, 'yyyy-MM-dd') = dod2.sku_expdate
		Inner join sku s
		ON ss.sku_id = s.sku_id and dod2.sku_id = s.sku_id
		WHERE depo_detail_id = '66A9E421-19BE-4C56-9C86-6233DBDBA231'
		ORDER BY ss.client_wms_id, s.principle_id, ss.sku_id, ss.sku_stock_id")->result();

		// $data = $this->db->get()->result();
		$groupedData = [];

		foreach ($data as $item) {

			$groupKey = $item->client_wms_id . '_' . $item->principle_id;
			if (!isset($groupedData[$groupKey])) {
				$groupedData[$groupKey] = [
					'client_wms_id' => $item->client_wms_id,
					'principle_id' => $item->principle_id,
					'depo_id' => $item->depo_id,
					'items' => [],
				];
			}
			$existingItemKey = array_search($item->sku_stock_id, array_column($groupedData[$groupKey]['items'], 'sku_stock_id'));

			if ($existingItemKey !== false) {
				$groupedData[$groupKey]['items'][$existingItemKey]->selisih += $item->selisih;
			} else {
				$groupedData[$groupKey]['items'][] = $item;
			}
		}


		$groupedData = array_values($groupedData);
		foreach ($groupedData as $key => $value) {
			$id = $this->db->query("select newid() as id")->row();

			$date_now = date('Y-m-d h:i:s');
			$param =  'KODE_MTSS';
			$vrbl = $this->M_Vrbl->Get_Kode($param);
			$prefix = $vrbl->vrbl_kode;
			// get prefik depo
			$depo_id = $this->session->userdata('depo_id');
			$depoPrefix = $this->getDepoPrefix($depo_id);
			$unit = $depoPrefix->depo_kode_preffix;
			$tr_mutasi_stok_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal($date_now, $prefix, $unit);

			$this->db->set('tr_mutasi_stok_id', $id->id);
			$this->db->set('client_wms_id', $value['client_wms_id']);
			$this->db->set('principle_id', $value['principle_id']);
			$this->db->set('tr_mutasi_stok_kode', $tr_mutasi_stok_kode);
			$this->db->set('tr_mutasi_stok_tanggal', 'getdate()', false);
			$this->db->set('tr_mutasi_stok_keterangan', 'DO Gagal Kirim Tidak Di Reschedule');
			$this->db->set('tr_mutasi_stok_status', 'Draft');
			$this->db->set('depo_id_asal', $depo_id);
			$this->db->set('tr_mutasi_stok_tgl_create', 'getdate()', false);
			$this->db->set('tr_mutasi_stok_who_create', $this->session->userdata('pengguna_username'));
			$this->db->set('tr_mutasi_stok_nama_checker', '');
			$this->db->set('tr_mutasi_stok_tgl_update', null);
			$this->db->set('tr_mutasi_stokt_who_update', null);

			$query = $this->db->insert("tr_mutasi_stok");

			foreach ($value['items'] as $key => $item) {

				$this->db->set('tr_mutasi_stok_detail_id', 'newid()', false);
				$this->db->set('tr_mutasi_stok_id', $id->id);
				$this->db->set('depo_detail_asal', '66A9E421-19BE-4C56-9C86-6233DBDBA231');
				$this->db->set('pallet_id_asal', null);
				$this->db->set('sku_stock_id', $item->sku_stock_id);
				$this->db->set('sku_id', $item->sku_id);
				$this->db->set('sku_stock_expired_date', $item->sku_expdate);
				$this->db->set('qty', $item->selisih);
				$query = $this->db->insert('tr_mutasi_stok_detail');
				# code...
			}
		}
		return $query;
	}

	public function Generate_do_not_reschedule_header($delivery_order_draft_id, $delivery_order_draft_kode, $delivery_order_id, $tanggal_rencana_kirim, $tipe_delivery_order_id)
	{

		$query = $this->db->query("insert into delivery_order_draft (delivery_order_draft_id,
									sales_order_id,
									delivery_order_draft_kode,
									delivery_order_draft_yourref,
									client_wms_id,
									delivery_order_draft_tgl_buat_do,
									delivery_order_draft_tgl_expired_do,
									delivery_order_draft_tgl_surat_jalan,
									delivery_order_draft_tgl_rencana_kirim,
									delivery_order_draft_tgl_aktual_kirim,
									delivery_order_draft_keterangan,
									delivery_order_draft_status,
									delivery_order_draft_is_prioritas,
									delivery_order_draft_is_need_packing,
									delivery_order_draft_tipe_layanan,
									delivery_order_draft_tipe_pembayaran,
									delivery_order_draft_sesi_pengiriman,
									delivery_order_draft_request_tgl_kirim,
									delivery_order_draft_request_jam_kirim,
									tipe_pengiriman_id,
									nama_tipe,
									confirm_rate,
									delivery_order_draft_reff_id,
									delivery_order_draft_reff_no,
									delivery_order_draft_total,
									unit_mandiri_id,
									depo_id,
									client_pt_id,
									delivery_order_draft_kirim_nama,
									delivery_order_draft_kirim_alamat,
									delivery_order_draft_kirim_telp,
									delivery_order_draft_kirim_provinsi,
									delivery_order_draft_kirim_kota,
									delivery_order_draft_kirim_kecamatan,
									delivery_order_draft_kirim_kelurahan,
									delivery_order_draft_kirim_kodepos,
									delivery_order_draft_kirim_area,
									delivery_order_draft_kirim_invoice_pdf,
									delivery_order_draft_kirim_invoice_dir,
									pabrik_id,
									delivery_order_draft_ambil_nama,
									delivery_order_draft_ambil_alamat,
									delivery_order_draft_ambil_telp,
									delivery_order_draft_ambil_provinsi,
									delivery_order_draft_ambil_kota,
									delivery_order_draft_ambil_kecamatan,
									delivery_order_draft_ambil_kelurahan,
									delivery_order_draft_ambil_kodepos,
									delivery_order_draft_ambil_area,
									delivery_order_draft_update_who,
									delivery_order_draft_update_tgl,
									delivery_order_draft_approve_who,
									delivery_order_draft_approve_tgl,
									delivery_order_draft_reject_who,
									delivery_order_draft_reject_tgl,
									delivery_order_draft_reject_reason,
									tipe_delivery_order_id,
									is_from_so,
									delivery_order_draft_nominal_tunai,
									delivery_order_draft_attachment,
									is_promo,
									is_canvas,
									canvas_id)
									select
									'$delivery_order_draft_id' AS delivery_order_draft_id,
									sales_order_id,
									'$delivery_order_draft_kode' AS delivery_order_draft_kode,
									delivery_order_kode as delivery_order_draft_yourref,
									client_wms_id,
									GETDATE() AS delivery_order_draft_tgl_buat_do,
									DATEADD(DAY, 7, GETDATE()) AS delivery_order_draft_tgl_expired_do,
									DATEADD(DAY, 1, GETDATE()) AS delivery_order_draft_tgl_surat_jalan,
									'$tanggal_rencana_kirim' AS delivery_order_draft_tgl_rencana_kirim,
									DATEADD(DAY, 1, GETDATE()) delivery_order_draft_tgl_aktual_kirim,
									delivery_order_keterangan AS delivery_order_draft_keterangan,
									'Draft' AS delivery_order_draft_status,
									delivery_order_is_prioritas AS delivery_order_draft_is_prioritas,
									delivery_order_is_need_packing AS delivery_order_draft_is_need_packing,
									delivery_order_tipe_layanan AS delivery_order_draft_tipe_layanan,
									delivery_order_tipe_pembayaran AS delivery_order_draft_tipe_pembayaran,
									delivery_order_sesi_pengiriman AS delivery_order_draft_sesi_pengiriman,
									NULL AS delivery_order_draft_request_tgl_kirim,
									NULL AS delivery_order_draft_request_jam_kirim,
									tipe_pengiriman_id,
									nama_tipe,
									confirm_rate,
									delivery_order_id AS delivery_order_draft_reff_id,
									delivery_order_kode AS delivery_order_draft_reff_no,
									delivery_order_total AS delivery_order_draft_total,
									unit_mandiri_id,
									depo_id,
									client_pt_id,
									delivery_order_kirim_nama,
									delivery_order_kirim_alamat,
									delivery_order_kirim_telp,
									delivery_order_kirim_provinsi,
									delivery_order_kirim_kota,
									delivery_order_kirim_kecamatan,
									delivery_order_kirim_kelurahan,
									delivery_order_kirim_kodepos,
									delivery_order_kirim_area,
									delivery_order_kirim_invoice_pdf,
									delivery_order_kirim_invoice_dir,
									principle_id,
									delivery_order_ambil_nama,
									delivery_order_ambil_alamat,
									delivery_order_ambil_telp,
									delivery_order_ambil_provinsi,
									delivery_order_ambil_kota,
									delivery_order_ambil_kecamatan,
									delivery_order_ambil_kelurahan,
									delivery_order_ambil_kodepos,
									delivery_order_ambil_area,
									'" . $this->session->userdata('pengguna_username') . "' AS delivery_order_update_who,
									GETDATE() AS delivery_order_update_tgl,
									NULL AS delivery_order_approve_who,
									NULL AS delivery_order_approve_tgl,
									NULL AS delivery_order_reject_who,
									NULL AS delivery_order_reject_tgl,
									NULL AS delivery_order_reject_reason,
									CASE WHEN '$tipe_delivery_order_id' = '' THEN NULL ELSE '$tipe_delivery_order_id' END AS tipe_delivery_order_id,
									0 AS is_from_so,
									CASE WHEN delivery_order_status = 'partially delivered' THEN 0 ELSE delivery_order_nominal_tunai end AS delivery_order_nominal_tunai,
									delivery_order_attachment,
									is_promo,
									is_canvas,
									canvas_id
									from delivery_order
									where delivery_order_id = '$delivery_order_id'");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Generate_do_not_reschedule_detail($delivery_order_draft_id, $delivery_order_id)
	{
		// $query = $this->db->query("insert delivery_order_detail_draft (delivery_order_detail_draft_id,
		// 							delivery_order_draft_id,
		// 							sku_id,
		// 							gudang_id,
		// 							gudang_detail_id,
		// 							sku_kode,
		// 							sku_nama_produk,
		// 							sku_harga_satuan,
		// 							sku_disc_percent,
		// 							sku_disc_rp,
		// 							sku_harga_nett,
		// 							sku_request_expdate,
		// 							sku_filter_expdate,
		// 							sku_filter_expdatebulan,
		// 							sku_filter_expdatetahun,
		// 							sku_weight,
		// 							sku_weight_unit,
		// 							sku_length,
		// 							sku_length_unit,
		// 							sku_width,
		// 							sku_width_unit,
		// 							sku_height,
		// 							sku_height_unit,
		// 							sku_volume,
		// 							sku_volume_unit,
		// 							sku_qty,
		// 							sku_keterangan,
		// 							tipe_stock_nama)
		// 							select 
		// 							NEWID() AS delivery_order_detail_draft_id,
		// 							'$delivery_order_draft_id' AS delivery_order_draft_id,
		// 							sku_id,
		// 							depo_id AS gudang_id,
		// 							depo_detail_id AS gudang_detail_id,
		// 							sku_kode,
		// 							sku_nama_produk,
		// 							sku_harga_satuan,
		// 							sku_disc_percent,
		// 							sku_disc_rp,
		// 							sku_harga_nett,
		// 							sku_request_expdate,
		// 							sku_filter_expdate,
		// 							sku_filter_expdatebulan,
		// 							sku_filter_expdatetahun,
		// 							sku_weight,
		// 							sku_weight_unit,
		// 							sku_length,
		// 							sku_length_unit,
		// 							sku_width,
		// 							sku_width_unit,
		// 							sku_height,
		// 							sku_height_unit,
		// 							sku_volume,
		// 							sku_volume_unit,
		// 							sku_qty,
		// 							sku_keterangan,
		// 							tipe_stock_nama
		// 							from delivery_order_detail
		// 							where delivery_order_id = '$delivery_order_id'");

		$query = $this->db->query("insert delivery_order_detail_draft (delivery_order_detail_draft_id,
		delivery_order_draft_id,
		sku_id,
		gudang_id,
		gudang_detail_id,
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
		tipe_stock_nama)
		select 
		NEWID() AS delivery_order_detail_draft_id,
		'$delivery_order_draft_id' AS delivery_order_draft_id,
		dod2.sku_id,
		dod.depo_id AS gudang_id,
		dod.depo_detail_id AS gudang_detail_id,
		sku.sku_kode,
		sku.sku_nama_produk,
		1 AS sku_harga_satuan,
		dod.sku_disc_percent,
		dod.sku_disc_rp,
		1 AS sku_harga_nett,
		dod.sku_request_expdate,
		dod.sku_filter_expdate,
		dod.sku_filter_expdatebulan,
		dod.sku_filter_expdatetahun,
		sku.sku_weight,
		sku.sku_weight_unit,
		sku.sku_length,
		sku.sku_length_unit,
		sku.sku_width,
		sku.sku_width_unit,
		sku.sku_height,
		sku.sku_height_unit,
		sku.sku_volume,
		sku.sku_volume_unit,
		dod2.sku_qty,
		dod.sku_keterangan,
		dod.tipe_stock_nama
		from delivery_order_detail dod
		right JOIN (
				SELECT DISTINCT
					delivery_order_detail_id,
					a.delivery_order_id,
					sku_id,
					sku_stock_id,
					sku_expdate,
					sku_qty
				FROM delivery_order_detail2_gagal a 
			INNER JOIN delivery_order b on a.delivery_order_id = b.delivery_order_id
			WHERE a.delivery_order_id IN ('$delivery_order_id') AND tipe_delivery_order_id NOT IN ('C5BE83E2-01E8-4E24-B766-26BB4158F2CD')
					UNION
				SELECT DISTINCT
					delivery_order_detail_id,
					a.delivery_order_id,
					sku_id,
					sku_stock_id,
					sku_expdate,
					sku_qty
				FROM delivery_order_detail2_terkirim a 
			INNER JOIN delivery_order b on a.delivery_order_id = b.delivery_order_id
			WHERE a.delivery_order_id IN ('$delivery_order_id') AND tipe_delivery_order_id IN ('C5BE83E2-01E8-4E24-B766-26BB4158F2CD')
		) dod2 on dod2.delivery_order_detail_id = dod.delivery_order_detail_id
		INNER JOIN sku
			ON dod2.sku_id = sku.sku_id
		where dod.delivery_order_id = '$delivery_order_id'");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}
}
