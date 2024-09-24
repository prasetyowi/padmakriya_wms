<?php

class M_DeliveryOrderDraft extends CI_Model
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

	public function GetSalesBosnet()
	{
		$query = $this->db->query("SELECT
									karyawan_eks.karyawan_id,
									karyawan_eks.sales_eksternal_id,
									karyawan.karyawan_nama
									FROM BACKEND.dbo.karyawan_sales_eksternal karyawan_eks
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = karyawan_eks.karyawan_id
									WHERE karyawan.depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY karyawan_eks.sales_eksternal_id ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function updateIsPending($delivery_order_draft_id, $is_pending)
	{
		$this->db->set("delivery_order_draft_is_pending ", $is_pending);

		$this->db->where("delivery_order_draft_id", $delivery_order_draft_id);

		$this->db->update("delivery_order_draft");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
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

	public function GetTipeDeliveryOrderPrioritas()
	{
		$query = $this->db->query("SELECT * from tipe_delivery_order where tipe_delivery_order_tipe = 'Out' and tipe_delivery_order_nama not in ('Standar','Claim Driver','Claim Sales','Mix')");
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
		// $this->db->select("*")
		// 	->from("client_wms")
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
									delivery_order_draft_reff_id,
									delivery_order_draft_reff_no
									FROM delivery_order_draft
									WHERE delivery_order_draft_reff_id = '$delivery_order_id'
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

	public function GetDeliveryOrderDraftHeaderById($id)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									do.sales_order_id,
									FORMAT(do.delivery_order_draft_tgl_buat_do, 'dd/MM/yyyy') AS delivery_order_draft_tgl_buat_do,
									FORMAT(do.delivery_order_draft_tgl_expired_do, 'dd/MM/yyyy') AS delivery_order_draft_tgl_expired_do,
									FORMAT(do.delivery_order_draft_tgl_surat_jalan, 'dd/MM/yyyy') AS delivery_order_draft_tgl_surat_jalan,
									FORMAT(do.delivery_order_draft_tgl_rencana_kirim, 'dd/MM/yyyy') AS delivery_order_draft_tgl_rencana_kirim,
									do.delivery_order_draft_update_tgl,
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
									do.delivery_order_draft_reff_id,
									do.delivery_order_draft_reff_no,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_nama,
									ISNULL(do.delivery_order_draft_keterangan,'') AS delivery_order_draft_keterangan,
									do.delivery_order_draft_status,
									delivery_order_draft_nominal_tunai,
									ISNULL(do.delivery_order_draft_attachment, '') AS delivery_order_draft_attachment
									FROM delivery_order_draft do
									LEFT JOIN client_wms ON do.client_wms_id = client_wms.client_wms_id
									LEFT JOIN tipe_delivery_order tipe ON tipe.tipe_delivery_order_id = do.tipe_delivery_order_id
									WHERE do.delivery_order_draft_id = '$id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDeliveryOrderDraftDetailById($id)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_detail_draft_id,
									do.delivery_order_draft_id,
									do.sku_id,
									do.gudang_id,
									do.gudang_detail_id,
									do.sku_kode,
									do.sku_nama_produk,
									do.sku_harga_satuan,
									sku.sku_satuan,
									sku.sku_kemasan,
									sku.sku_konversi_faktor,
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
									FROM delivery_order_detail_draft do
									LEFT JOIN sku
									  ON sku.sku_id = do.sku_id
									WHERE delivery_order_draft_id = '$id'
									ORDER BY sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDeliveryOrderDraftHeaderByListId($id)
	{
		$id = implode(",", $id);

		$query = $this->db->query("SELECT
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									do.sales_order_id,
									t1.principle_id,
									FORMAT(do.delivery_order_draft_tgl_buat_do, 'dd/MM/yyyy') AS delivery_order_draft_tgl_buat_do,
									FORMAT(do.delivery_order_draft_tgl_expired_do, 'dd/MM/yyyy') AS delivery_order_draft_tgl_expired_do,
									FORMAT(do.delivery_order_draft_tgl_surat_jalan, 'dd/MM/yyyy') AS delivery_order_draft_tgl_surat_jalan,
									FORMAT(do.delivery_order_draft_tgl_rencana_kirim, 'dd/MM/yyyy') AS delivery_order_draft_tgl_rencana_kirim,
									do.delivery_order_draft_update_tgl,
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
									LEFT JOIN (
										SELECT sku.principle_id, dod.delivery_order_draft_id 
										FROM delivery_order_detail_draft dod
										LEFT JOIN sku
											ON sku.sku_id = dod.sku_id
										WHERE dod.delivery_order_draft_id IN (" . $id . ")
										GROUP BY sku.principle_id, dod.delivery_order_draft_id
									) t1 ON do.delivery_order_draft_id = t1.delivery_order_draft_id
									WHERE do.delivery_order_draft_id IN (" . $id . ")
									ORDER BY ISNULL(do.delivery_order_draft_is_prioritas, 0) DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDeliveryOrderDraftDetailByListId($id)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_detail_draft_id,
									do.delivery_order_draft_id,
									do.sku_id,
									principle.principle_id,
									do.gudang_id,
									do.gudang_detail_id,
									do.sku_kode,
									do.sku_nama_produk,
									do.sku_harga_satuan,
									sku.sku_satuan,
									sku.sku_kemasan,
									ISNULL(client_wms.use_gudang_bonus, '0') AS use_gudang_bonus,
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
									FROM delivery_order_detail_draft do
									LEFT JOIN sku
									  	ON sku.sku_id = do.sku_id
									LEFT JOIN client_wms 
										ON client_wms.client_wms_id = sku.client_wms_id
									INNER JOIN principle
										ON principle.principle_id = sku.principle_id
									WHERE delivery_order_draft_id = '$id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDeliveryOrderDraftDetail2ByListId($id)
	{
		$query = $this->db->query("SELECT
									delivery_order_detail2_draft_id,
									delivery_order_detail_draft_id,
									delivery_order_draft_id,
									sku_id,
									sku_stock_id,
									sku_expdate,
									ISNULL(sku_qty,0) AS sku_qty,
									ISNULL(sku_qty_composite,0) AS sku_qty_composite
									FROM delivery_order_detail2_draft
									WHERE delivery_order_draft_id = '$id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetTotalDeliveryOrderDraftByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $sales, $search, $principle, $soEksternal, $priority, $so, $status_pending)
	{

		if ($do_no == "") {
			$do_no = "";
		} else {
			$do_no = "AND do.delivery_order_draft_kode LIKE '%" . $do_no . "%' ";
		}

		if ($soEksternal == "") {
			$soEksternal = "";
		} else {
			$soEksternal = "AND so.sales_order_no_po LIKE '%" . $soEksternal . "%' ";
		}

		if ($so == "") {
			$so = "";
		} else {
			$so = "AND so.sales_order_kode LIKE '%" . $so . "%' ";
		}

		if ($customer == "") {
			$customer = "";
		} else {
			$customer = "AND CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_nama ELSE do.delivery_order_draft_kirim_nama END LIKE '%" . $customer . "%' ";
		}

		if ($alamat == "") {
			$alamat = "";
		} else {
			$alamat = "AND CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_alamat ELSE do.delivery_order_draft_kirim_alamat END LIKE '%" . $alamat . "%' ";
		}

		if ($tipe_pembayaran == "") {
			$tipe_pembayaran = "";
		} else {
			$tipe_pembayaran = "AND do.delivery_order_draft_tipe_pembayaran = '" . $tipe_pembayaran . "' ";
		}

		if ($tipe_layanan == "") {
			$tipe_layanan = "";
		} else {
			$tipe_layanan = "AND do.delivery_order_draft_tipe_layanan = '" . $tipe_layanan . "' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND do.delivery_order_draft_status = '" . $status . "' ";
		}

		if ($status_pending == "") {
			$status_pending = "";
		} else {
			if ($status_pending != '0') {
				$status_pending = "AND do.delivery_order_draft_is_pending = '" . $status_pending . "' ";
			} else {
				$status_pending = "AND (do.delivery_order_draft_is_pending = '" . $status_pending . "' OR do.delivery_order_draft_is_pending is null) ";
			}
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
		if ($sales == "") {
			$sales = "";
		} else {
			$sales = "AND bso.szSalesId = '$sales'";
		}
		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND so.principle_id = '$principle'";
		}

		if ($search == "") {
			$search = "";
		} else {
			$search = "AND (do.delivery_order_draft_kode LIKE '%" . $search . "%' OR CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_nama ELSE do.delivery_order_draft_kirim_nama END LIKE '%" . $search . "%' OR CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_alamat ELSE do.delivery_order_draft_kirim_alamat END LIKE '%" . $search . "%') ";
		}

		if ($priority == "") {
			$priority = "";
		} else {
			$priority = "AND do.delivery_order_draft_is_prioritas = '$priority'";
		}

		$query = $this->db->query("SELECT
									COUNT(DISTINCT do.delivery_order_draft_id) AS jumlah
									FROM delivery_order_draft do
									LEFT JOIN sales_order so ON so.sales_order_id = do.sales_order_id
									LEFT JOIN tipe_delivery_order tipe ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									LEFT JOIN bosnet_so bso ON so.sales_order_no_po = bso.szFSoId
									LEFT JOIN client_pt cp on cp.client_pt_id = do.client_pt_id
									left join client_pt_segmen cps1 on cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
									left join client_pt_segmen cps2 on cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
									left join client_pt_segmen cps3 on cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
									left join delivery_order do1 on do1.delivery_order_draft_id = do.delivery_order_draft_id
									WHERE format(do.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
									" . $do_no . "
									" . $soEksternal . "
									" . $so . "
									" . $customer . "
									" . $alamat . "
									" . $tipe_pembayaran . "
									" . $tipe_layanan . "
									" . $status . "
									" . $status_pending . "
									" . $tipe . "
									" . $segmen1 . "
									" . $segmen2 . "
									" . $segmen3 . "
									" . $sales . "
									" . $principle . "
									" . $search . "
									" . $priority . " ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->jumlah;
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDeliveryOrderDraftByFilter($tgl1, $tgl2, $do_no, $customer, $alamat, $tipe_pembayaran, $tipe_layanan, $status, $tipe, $segmen1, $segmen2, $segmen3, $sales, $start, $end, $search, $principle, $soEksternal, $priority, $so, $status_pending)
	{

		if ($do_no == "") {
			$do_no = "";
		} else {
			$do_no = "AND do.delivery_order_draft_kode LIKE '%" . $do_no . "%' ";
		}

		if ($soEksternal == "") {
			$soEksternal = "";
		} else {
			$soEksternal = "AND so.sales_order_no_po LIKE '%" . $soEksternal . "%' ";
		}

		if ($so == "") {
			$so = "";
		} else {
			$so = "AND so.sales_order_kode LIKE '%" . $so . "%' ";
		}

		if ($customer == "") {
			$customer = "";
		} else {
			$customer = "AND CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_nama ELSE do.delivery_order_draft_kirim_nama END LIKE '%" . $customer . "%' ";
		}

		if ($alamat == "") {
			$alamat = "";
		} else {
			$alamat = "AND CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_alamat ELSE do.delivery_order_draft_kirim_alamat END LIKE '%" . $alamat . "%' ";
		}

		if ($tipe_pembayaran == "") {
			$tipe_pembayaran = "";
		} else {
			$tipe_pembayaran = "AND do.delivery_order_draft_tipe_pembayaran = '" . $tipe_pembayaran . "' ";
		}

		if ($tipe_layanan == "") {
			$tipe_layanan = "";
		} else {
			$tipe_layanan = "AND do.delivery_order_draft_tipe_layanan = '" . $tipe_layanan . "' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND do.delivery_order_draft_status = '" . $status . "' ";
		}

		if ($status_pending == "") {
			$status_pending = "";
		} else {
			if ($status_pending != '0') {
				$status_pending = "AND do.delivery_order_draft_is_pending = '" . $status_pending . "' ";
			} else {
				$status_pending = "AND (do.delivery_order_draft_is_pending = '" . $status_pending . "' OR do.delivery_order_draft_is_pending is null) ";
			}
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
		if ($sales == "") {
			$sales = "";
		} else {
			$sales = "AND bso.szSalesId = '$sales'";
		}
		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND so.principle_id = '$principle'";
		}

		if ($search == "") {
			$search = "";
		} else {
			$search = "AND (do.delivery_order_draft_kode LIKE '%" . $search . "%' OR CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_nama ELSE do.delivery_order_draft_kirim_nama END LIKE '%" . $search . "%' OR CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_alamat ELSE do.delivery_order_draft_kirim_alamat END LIKE '%" . $search . "%') ";
		}

		if ($priority == "") {
			$priority = "";
		} else {
			$priority = "AND do.delivery_order_draft_is_prioritas = '$priority'";
		}

		$query = $this->db->query("SELECT
									ROW_NUMBER() OVER (ORDER BY do.delivery_order_draft_tgl_rencana_kirim DESC) - 1 AS idx,
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									do.delivery_order_draft_is_pending,
									so.sales_order_id,
									so.sales_order_no_po,
									so.principle_id,
									ISNULL(principle.principle_kode, '') AS principle_kode,
									ISNULL(ky.karyawan_nama, '') AS karyawan_nama,
									cp.client_pt_segmen_id1,
									cp.client_pt_segmen_id2,
									cp.client_pt_segmen_id3,
									cps1.client_pt_segmen_nama as client_pt_segmen_nama1 ,
									cps2.client_pt_segmen_nama as client_pt_segmen_nama2,
									cps3.client_pt_segmen_nama as client_pt_segmen_nama3,
									ISNULL(so.sales_order_kode,'') AS sales_order_kode,
									format(do.delivery_order_draft_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
									format(do.delivery_order_draft_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_expired_do,
									format(do.delivery_order_draft_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_draft_tgl_surat_jalan,
									format(do.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_rencana_kirim,
									format(do.delivery_order_draft_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_aktual_kirim,
									format(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
									DATEDIFF(DAY, so.sales_order_tgl,GETDATE()) AS umur_so,
									CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.pabrik_id ELSE do.client_pt_id END client_pt_id,
									CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_nama ELSE do.delivery_order_draft_kirim_nama END delivery_order_draft_kirim_nama,
									CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_alamat ELSE do.delivery_order_draft_kirim_alamat END delivery_order_draft_kirim_alamat,
									CASE WHEN do.delivery_order_draft_tipe_pembayaran = '0' THEN 'TUNAI' ELSE 'NON TUNAI' END AS delivery_order_draft_tipe_pembayaran,
									do.delivery_order_draft_tipe_layanan,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_nama,
									tipe.tipe_delivery_order_alias,
									do.delivery_order_draft_status,
									do.delivery_order_draft_kirim_area,
									CASE WHEN ISNULL(do.delivery_order_draft_is_prioritas, 0) = 1 THEN 'Yes' ELSE '' END AS delivery_order_draft_is_prioritas,
									ISNULL(do.delivery_order_draft_nominal_tunai,0) AS delivery_order_draft_nominal_tunai,
									ISNULL(bso.szSalesId, '-') as szSalesId,
									CASE WHEN do1.delivery_order_batch_id is null THEN '0' ELSE '1' END AS is_ada_fdjr
									FROM delivery_order_draft do
									LEFT JOIN sales_order so ON so.sales_order_id = do.sales_order_id
									LEFT JOIN tipe_delivery_order tipe ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									LEFT JOIN client_pt cp on cp.client_pt_id = do.client_pt_id
									LEFT JOIN bosnet_so bso ON so.sales_order_no_po = bso.szFSoId
									left join client_pt_segmen cps1 on cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
									left join client_pt_segmen cps2 on cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
									left join client_pt_segmen cps3 on cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
									left join principle on principle.principle_id = so.principle_id
									left join karyawan ky on so.sales_id = ky.karyawan_id
									left join delivery_order do1 on do1.delivery_order_draft_id = do.delivery_order_draft_id
									WHERE format(do.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
									" . $do_no . "
									" . $soEksternal . "
									" . $so . "
									" . $customer . "
									" . $alamat . "
									" . $tipe_pembayaran . "
									" . $tipe_layanan . "
									" . $status . "
									" . $status_pending . "
									" . $tipe . "
									" . $segmen1 . "
									" . $segmen2 . "
									" . $segmen3 . "
									" . $segmen3 . "
									" . $sales . "
									" . $principle . "
									" . $search . "
									" . $priority . "
									ORDER BY do.delivery_order_draft_tgl_rencana_kirim DESC, do.delivery_order_draft_kirim_area ASC, do.delivery_order_draft_kirim_nama ASC
									OFFSET " . $start . " ROWS
									FETCH NEXT " . $end . " ROWS ONLY");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
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
									client_pt.client_pt_id,
									client_pt.client_pt_nama,
									client_pt.client_pt_alamat,
									client_pt.client_pt_telepon,
									client_pt.client_pt_propinsi,
									client_pt.client_pt_kota,
									client_pt.client_pt_kecamatan,
									client_pt.client_pt_kelurahan,
									client_pt.client_pt_kodepos,
									client_pt.client_pt_latitude,
									client_pt.client_pt_longitude,
									client_pt.client_pt_nama_contact_person,
									client_pt.client_pt_telepon_contact_person,
									client_pt.client_pt_email_contact_person,
									client_pt.client_pt_keterangan,
									client_pt.area_id,
									client_pt.unit_mandiri_id,
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
									WHERE client_wms_client_pt.client_wms_id = '$perusahaan'
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
									LEFT JOIN depo_client_wms
									ON sku.client_wms_id = depo_client_wms.client_wms_id
									WHERE sku_stock.sku_id IS NOT NULL
									AND depo_client_wms.depo_id = '" . $this->session->userdata('depo_id') . "'
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
									-- LEFT JOIN depo_client_wms
									-- ON sku.client_wms_id = depo_client_wms.client_wms_id
									WHERE client_wms_principle.principle_id = '$client_pt'
									-- AND depo_client_wms.depo_id = '" . $this->session->userdata('depo_id') . "'
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

	public function insert_delivery_order_draft($delivery_order_draft_id, $sales_order_id, $delivery_order_draft_kode, $delivery_order_draft_yourref, $client_wms_id, $delivery_order_draft_tgl_buat_do, $delivery_order_draft_tgl_expired_do, $delivery_order_draft_tgl_surat_jalan, $delivery_order_draft_tgl_rencana_kirim, $delivery_order_draft_tgl_aktual_kirim, $delivery_order_draft_keterangan, $delivery_order_draft_status, $delivery_order_draft_is_prioritas, $delivery_order_draft_is_need_packing, $delivery_order_draft_tipe_layanan, $delivery_order_draft_tipe_pembayaran, $delivery_order_draft_sesi_pengiriman, $delivery_order_draft_request_tgl_kirim, $delivery_order_draft_request_jam_kirim, $tipe_pengiriman_id, $nama_tipe, $confirm_rate, $delivery_order_draft_reff_id, $delivery_order_draft_reff_no, $delivery_order_draft_total, $unit_mandiri_id, $depo_id, $client_pt_id, $delivery_order_draft_kirim_nama, $delivery_order_draft_kirim_alamat, $delivery_order_draft_kirim_telp, $delivery_order_draft_kirim_provinsi, $delivery_order_draft_kirim_kota, $delivery_order_draft_kirim_kecamatan, $delivery_order_draft_kirim_kelurahan, $delivery_order_draft_kirim_kodepos, $delivery_order_draft_kirim_area, $delivery_order_draft_kirim_invoice_pdf, $delivery_order_draft_kirim_invoice_dir, $pabrik_id, $delivery_order_draft_ambil_nama, $delivery_order_draft_ambil_alamat, $delivery_order_draft_ambil_telp, $delivery_order_draft_ambil_provinsi, $delivery_order_draft_ambil_kota, $delivery_order_draft_ambil_kecamatan, $delivery_order_draft_ambil_kelurahan, $delivery_order_draft_ambil_kodepos, $delivery_order_draft_ambil_area, $delivery_order_draft_update_who, $delivery_order_draft_update_tgl, $delivery_order_draft_approve_who, $delivery_order_draft_approve_tgl, $delivery_order_draft_reject_who, $delivery_order_draft_reject_tgl, $delivery_order_draft_reject_reason, $tipe_delivery_order_id, $delivery_order_draft_input_pembayaran_tunai, $name_file)
	{
		// $tgl = $tgl . " " . date('H:i:s');
		$sales_order_id = $sales_order_id == "" ? null : $sales_order_id;
		$delivery_order_draft_kode = $delivery_order_draft_kode == "" ? null : $delivery_order_draft_kode;
		$delivery_order_draft_yourref = $delivery_order_draft_yourref == "" ? null : $delivery_order_draft_yourref;
		$client_wms_id = $client_wms_id == "" ? null : $client_wms_id;
		$delivery_order_draft_tgl_buat_do = $delivery_order_draft_tgl_buat_do == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_tgl_buat_do)));
		$delivery_order_draft_tgl_expired_do = $delivery_order_draft_tgl_expired_do == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_tgl_expired_do)));
		$delivery_order_draft_tgl_surat_jalan = $delivery_order_draft_tgl_surat_jalan == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_tgl_surat_jalan)));
		$delivery_order_draft_tgl_rencana_kirim = $delivery_order_draft_tgl_rencana_kirim == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_tgl_rencana_kirim)));
		$delivery_order_draft_tgl_aktual_kirim = $delivery_order_draft_tgl_aktual_kirim == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_tgl_aktual_kirim)));
		$delivery_order_draft_keterangan = $delivery_order_draft_keterangan == "" ? null : $delivery_order_draft_keterangan;
		$delivery_order_draft_status = $delivery_order_draft_status == "" ? null : $delivery_order_draft_status;
		$delivery_order_draft_is_prioritas = $delivery_order_draft_is_prioritas == "" ? null : $delivery_order_draft_is_prioritas;
		$delivery_order_draft_is_need_packing = $delivery_order_draft_is_need_packing == "" ? null : $delivery_order_draft_is_need_packing;
		$delivery_order_draft_tipe_layanan = $delivery_order_draft_tipe_layanan == "" ? null : $delivery_order_draft_tipe_layanan;
		$delivery_order_draft_tipe_pembayaran = $delivery_order_draft_tipe_pembayaran == "" ? null : $delivery_order_draft_tipe_pembayaran;
		$delivery_order_draft_sesi_pengiriman = $delivery_order_draft_sesi_pengiriman == "" ? null : $delivery_order_draft_sesi_pengiriman;
		$delivery_order_draft_request_tgl_kirim = $delivery_order_draft_request_tgl_kirim == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_request_tgl_kirim)));
		$delivery_order_draft_request_jam_kirim = $delivery_order_draft_request_jam_kirim == "" ? null : $delivery_order_draft_request_jam_kirim;
		$tipe_pengiriman_id = $tipe_pengiriman_id == "" ? null : $tipe_pengiriman_id;
		$nama_tipe = $nama_tipe == "" ? null : $nama_tipe;
		$confirm_rate = $confirm_rate == "" ? null : $confirm_rate;
		$delivery_order_draft_reff_id = $delivery_order_draft_reff_id == "" ? null : $delivery_order_draft_reff_id;
		$delivery_order_draft_reff_no = $delivery_order_draft_reff_no == "" ? null : $delivery_order_draft_reff_no;
		$delivery_order_draft_total = $delivery_order_draft_total == "" ? null : $delivery_order_draft_total;
		$unit_mandiri_id = $unit_mandiri_id == "" ? null : $unit_mandiri_id;
		$depo_id = $depo_id == "" ? null : $depo_id;
		$client_pt_id = $client_pt_id == "" ? null : $client_pt_id;
		$delivery_order_draft_kirim_nama = $delivery_order_draft_kirim_nama == "" ? null : $delivery_order_draft_kirim_nama;
		$delivery_order_draft_kirim_alamat = $delivery_order_draft_kirim_alamat == "" ? null : $delivery_order_draft_kirim_alamat;
		$delivery_order_draft_kirim_telp = $delivery_order_draft_kirim_telp == "" ? null : $delivery_order_draft_kirim_telp;
		$delivery_order_draft_kirim_provinsi = $delivery_order_draft_kirim_provinsi == "" ? null : $delivery_order_draft_kirim_provinsi;
		$delivery_order_draft_kirim_kota = $delivery_order_draft_kirim_kota == "" ? null : $delivery_order_draft_kirim_kota;
		$delivery_order_draft_kirim_kecamatan = $delivery_order_draft_kirim_kecamatan == "" ? null : $delivery_order_draft_kirim_kecamatan;
		$delivery_order_draft_kirim_kelurahan = $delivery_order_draft_kirim_kelurahan == "" ? null : $delivery_order_draft_kirim_kelurahan;
		$delivery_order_draft_kirim_kodepos = $delivery_order_draft_kirim_kodepos == "" ? null : $delivery_order_draft_kirim_kodepos;
		$delivery_order_draft_kirim_area = $delivery_order_draft_kirim_area == "" ? null : $delivery_order_draft_kirim_area;
		$delivery_order_draft_kirim_invoice_pdf = $delivery_order_draft_kirim_invoice_pdf == "" ? null : $delivery_order_draft_kirim_invoice_pdf;
		$delivery_order_draft_kirim_invoice_dir = $delivery_order_draft_kirim_invoice_dir == "" ? null : $delivery_order_draft_kirim_invoice_dir;
		$pabrik_id = $pabrik_id == "" ? null : $pabrik_id;
		$delivery_order_draft_ambil_nama = $delivery_order_draft_ambil_nama == "" ? null : $delivery_order_draft_ambil_nama;
		$delivery_order_draft_ambil_alamat = $delivery_order_draft_ambil_alamat == "" ? null : $delivery_order_draft_ambil_alamat;
		$delivery_order_draft_ambil_telp = $delivery_order_draft_ambil_telp == "" ? null : $delivery_order_draft_ambil_telp;
		$delivery_order_draft_ambil_provinsi = $delivery_order_draft_ambil_provinsi == "" ? null : $delivery_order_draft_ambil_provinsi;
		$delivery_order_draft_ambil_kota = $delivery_order_draft_ambil_kota == "" ? null : $delivery_order_draft_ambil_kota;
		$delivery_order_draft_ambil_kecamatan = $delivery_order_draft_ambil_kecamatan == "" ? null : $delivery_order_draft_ambil_kecamatan;
		$delivery_order_draft_ambil_kelurahan = $delivery_order_draft_ambil_kelurahan == "" ? null : $delivery_order_draft_ambil_kelurahan;
		$delivery_order_draft_ambil_kodepos = $delivery_order_draft_ambil_kodepos == "" ? null : $delivery_order_draft_ambil_kodepos;
		$delivery_order_draft_ambil_area = $delivery_order_draft_ambil_area == "" ? null : $delivery_order_draft_ambil_area;
		$delivery_order_draft_update_who = $delivery_order_draft_update_who == "" ? null : $delivery_order_draft_update_who;
		$delivery_order_draft_update_tgl = $delivery_order_draft_update_tgl == "" ? null : $delivery_order_draft_update_tgl;
		$delivery_order_draft_approve_who = $delivery_order_draft_approve_who == "" ? null : $delivery_order_draft_approve_who;
		$delivery_order_draft_approve_tgl = $delivery_order_draft_approve_tgl == "" ? null : $delivery_order_draft_approve_tgl;
		$delivery_order_draft_reject_who = $delivery_order_draft_reject_who == "" ? null : $delivery_order_draft_reject_who;
		$delivery_order_draft_reject_tgl = $delivery_order_draft_reject_tgl == "" ? null : $delivery_order_draft_reject_tgl;
		$delivery_order_draft_reject_reason = $delivery_order_draft_reject_reason == "" ? null : $delivery_order_draft_reject_reason;
		$tipe_delivery_order_id = $tipe_delivery_order_id == "" ? null : $tipe_delivery_order_id;
		$delivery_order_draft_input_pembayaran_tunai = $delivery_order_draft_input_pembayaran_tunai == "" ? null : $delivery_order_draft_input_pembayaran_tunai;
		$name_file = $name_file == "" ? null : $name_file;

		// $tgl_ = date_create(str_replace("/", "-", $tgl));
		$this->db->set("delivery_order_draft_id", $delivery_order_draft_id);
		$this->db->set("sales_order_id", $sales_order_id);
		$this->db->set("delivery_order_draft_kode", $delivery_order_draft_kode);
		$this->db->set("delivery_order_draft_yourref", $delivery_order_draft_yourref);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("delivery_order_draft_tgl_buat_do", $delivery_order_draft_tgl_buat_do);
		$this->db->set("delivery_order_draft_tgl_expired_do", $delivery_order_draft_tgl_expired_do);
		$this->db->set("delivery_order_draft_tgl_surat_jalan", $delivery_order_draft_tgl_surat_jalan);
		$this->db->set("delivery_order_draft_tgl_rencana_kirim", $delivery_order_draft_tgl_rencana_kirim);
		$this->db->set("delivery_order_draft_tgl_aktual_kirim", $delivery_order_draft_tgl_aktual_kirim);
		$this->db->set("delivery_order_draft_keterangan", $delivery_order_draft_keterangan);
		$this->db->set("delivery_order_draft_status", $delivery_order_draft_status);
		$this->db->set("delivery_order_draft_is_prioritas", $delivery_order_draft_is_prioritas);
		$this->db->set("delivery_order_draft_is_need_packing", $delivery_order_draft_is_need_packing);
		$this->db->set("delivery_order_draft_tipe_layanan", $delivery_order_draft_tipe_layanan);
		$this->db->set("delivery_order_draft_tipe_pembayaran", $delivery_order_draft_tipe_pembayaran);
		$this->db->set("delivery_order_draft_sesi_pengiriman", $delivery_order_draft_sesi_pengiriman);
		$this->db->set("delivery_order_draft_request_tgl_kirim", $delivery_order_draft_request_tgl_kirim);
		$this->db->set("delivery_order_draft_request_jam_kirim", $delivery_order_draft_request_jam_kirim);
		$this->db->set("tipe_pengiriman_id", $tipe_pengiriman_id);
		$this->db->set("nama_tipe", $nama_tipe);
		$this->db->set("confirm_rate", $confirm_rate);
		$this->db->set("delivery_order_draft_reff_id", $delivery_order_draft_reff_id);
		$this->db->set("delivery_order_draft_reff_no", $delivery_order_draft_reff_no);
		$this->db->set("delivery_order_draft_total", $delivery_order_draft_total);
		$this->db->set("unit_mandiri_id", $unit_mandiri_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("client_pt_id", $client_pt_id);
		$this->db->set("delivery_order_draft_kirim_nama", $delivery_order_draft_kirim_nama);
		$this->db->set("delivery_order_draft_kirim_alamat", $delivery_order_draft_kirim_alamat);
		$this->db->set("delivery_order_draft_kirim_telp", $delivery_order_draft_kirim_telp);
		$this->db->set("delivery_order_draft_kirim_provinsi", $delivery_order_draft_kirim_provinsi);
		$this->db->set("delivery_order_draft_kirim_kota", $delivery_order_draft_kirim_kota);
		$this->db->set("delivery_order_draft_kirim_kecamatan", $delivery_order_draft_kirim_kecamatan);
		$this->db->set("delivery_order_draft_kirim_kelurahan", $delivery_order_draft_kirim_kelurahan);
		$this->db->set("delivery_order_draft_kirim_kodepos", $delivery_order_draft_kirim_kodepos);
		$this->db->set("delivery_order_draft_kirim_area", $delivery_order_draft_kirim_area);
		$this->db->set("delivery_order_draft_kirim_invoice_pdf", $delivery_order_draft_kirim_invoice_pdf);
		$this->db->set("delivery_order_draft_kirim_invoice_dir", $delivery_order_draft_kirim_invoice_dir);
		$this->db->set("pabrik_id", $pabrik_id);
		$this->db->set("delivery_order_draft_ambil_nama", $delivery_order_draft_ambil_nama);
		$this->db->set("delivery_order_draft_ambil_alamat", $delivery_order_draft_ambil_alamat);
		$this->db->set("delivery_order_draft_ambil_telp", $delivery_order_draft_ambil_telp);
		$this->db->set("delivery_order_draft_ambil_provinsi", $delivery_order_draft_ambil_provinsi);
		$this->db->set("delivery_order_draft_ambil_kota", $delivery_order_draft_ambil_kota);
		$this->db->set("delivery_order_draft_ambil_kecamatan", $delivery_order_draft_ambil_kecamatan);
		$this->db->set("delivery_order_draft_ambil_kelurahan", $delivery_order_draft_ambil_kelurahan);
		$this->db->set("delivery_order_draft_ambil_kodepos", $delivery_order_draft_ambil_kodepos);
		$this->db->set("delivery_order_draft_ambil_area", $delivery_order_draft_ambil_area);
		$this->db->set("delivery_order_draft_update_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_draft_update_tgl", "GETDATE()", FALSE);
		$this->db->set("delivery_order_draft_approve_who", $delivery_order_draft_approve_who);
		$this->db->set("delivery_order_draft_approve_tgl", $delivery_order_draft_approve_tgl);
		$this->db->set("delivery_order_draft_reject_who", $delivery_order_draft_reject_who);
		$this->db->set("delivery_order_draft_reject_tgl", $delivery_order_draft_reject_tgl);
		$this->db->set("delivery_order_draft_reject_reason", $delivery_order_draft_reject_reason);
		$this->db->set("tipe_delivery_order_id", $tipe_delivery_order_id);
		$this->db->set("is_from_so", 0);
		$this->db->set("delivery_order_draft_nominal_tunai", $delivery_order_draft_input_pembayaran_tunai);
		$this->db->set("delivery_order_draft_attachment", $name_file);

		$queryinsert = $this->db->insert("delivery_order_draft");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_delivery_order_detail_draft($dod_id, $delivery_order_draft_id, $data)
	{
		// $getData = $this->db->select("sku_weight_product, sku_weight_product_unit")->from("sku")->where("sku_id", $data['sku_id'])->get()->result();

		$delivery_order_draft_id 	= $delivery_order_draft_id == "" ? null : $delivery_order_draft_id;
		$sku_id 					= $data->sku_id == "" ? null : $data->sku_id;
		// $gudang_id = $data->gudang_id == "" ? null : $data->gudang_id;
		// $gudang_detail_id = $data->gudang_detail_id == "" ? null : $data->gudang_detail_id;
		$sku_kode 					= $data->sku_kode == "" || $data->sku_kode == "null" ? null : $data->sku_kode;
		$sku_nama_produk 			= $data->sku_nama_produk == "" || $data->sku_nama_produk == "null" ? null : $data->sku_nama_produk;
		$sku_harga_satuan 			= $data->sku_harga_satuan == "" || $data->sku_harga_satuan == "null" ? null : $data->sku_harga_satuan;
		$sku_disc_percent 			= $data->sku_disc_percent == "" || $data->sku_disc_percent == "null" ? null : $data->sku_disc_percent;
		$sku_disc_rp 				= $data->sku_disc_rp == "" || $data->sku_disc_rp == "" ? null : $data->sku_disc_rp;
		$sku_harga_nett 			= $data->sku_harga_nett == "" || $data->sku_harga_nett == "null" ? null : $data->sku_harga_nett;
		$sku_request_expdate 		= $data->sku_request_expdate == "" || $data->sku_request_expdate == "null" ? null : $data->sku_request_expdate;
		$sku_filter_expdate 		= $data->sku_request_expdate == "0" ? null : $data->sku_filter_expdate;
		$sku_filter_expdatebulan 	= $data->sku_request_expdate == "0" ? null : $data->sku_filter_expdatebulan;
		// $sku_filter_expdatetahun = $data->sku_filter_expdatetahun == "" ? null : $data->sku_filter_expdatetahun;

		$sku_weight 				= $data->sku_weight == "" || $data->sku_weight == "null" ? null : $data->sku_weight;
		// $sku_weight = $getData->sku_weight_product == null ? null : $getData->sku_weight_product;

		$sku_weight_unit 			= $data->sku_weight_unit == "" || $data->sku_weight_unit == "null" ? null : $data->sku_weight_unit;
		// $sku_weight_unit =  $getData->sku_weight_product_unit == null ? null : $getData->sku_weight_product_unit;

		$sku_length 				= $data->sku_length == "" || $data->sku_length == "null" ? null : $data->sku_length;
		$sku_length_unit 			= $data->sku_length_unit == "" || $data->sku_length_unit == "null" ? null : $data->sku_length_unit;
		$sku_width 					= $data->sku_width == "" || $data->sku_width == "null" ? null : $data->sku_width;
		$sku_width_unit 			= $data->sku_width_unit == "" || $data->sku_width_unit == "null" ? null : $data->sku_width_unit;
		$sku_height 				= $data->sku_height == "" || $data->sku_height == "null" ? null : $data->sku_height;
		$sku_height_unit 			= $data->sku_height_unit == "" || $data->sku_height_unit == "null" ? null : $data->sku_height_unit;
		$sku_volume 				= $data->sku_volume == "" || $data->sku_volume == "null" ? null : $data->sku_volume;
		$sku_volume_unit 			= $data->sku_volume_unit == "" || $data->sku_volume_unit == "null" ? null : $data->sku_volume_unit;
		$sku_qty 					= $data->sku_qty == "" || $data->sku_qty == "null" ? null : $data->sku_qty;
		$sku_keterangan 			= $data->sku_keterangan == "" || $data->sku_keterangan == "null" ? null : $data->sku_keterangan;
		$sku_tipe_stock 			= $data->sku_tipe_stock == "" || $data->sku_tipe_stock == "null" ? null : $data->sku_tipe_stock;

		$this->db->set("delivery_order_detail_draft_id", $dod_id);
		$this->db->set("delivery_order_draft_id", $delivery_order_draft_id);
		$this->db->set("sku_id", $sku_id);
		// $this->db->set("gudang_id", $gudang_id);
		// $this->db->set("gudang_detail_id", $gudang_detail_id);
		$this->db->set("sku_kode", $sku_kode);
		$this->db->set("sku_nama_produk", $sku_nama_produk);
		$this->db->set("sku_harga_satuan", $sku_harga_satuan);
		// $this->db->set("sku_harga_satuan", 1);
		$this->db->set("sku_disc_percent", $sku_disc_percent);
		$this->db->set("sku_disc_rp", $sku_disc_rp);
		$this->db->set("sku_harga_nett", $sku_harga_nett);
		// $this->db->set("sku_harga_nett", 1);
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
		$this->db->set("tipe_stock_nama", $sku_tipe_stock);


		$queryinsert = $this->db->insert("delivery_order_detail_draft");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_confirm_delivery_order_detail_draft($dod_id, $delivery_order_draft_id, $data)
	{
		$delivery_order_draft_id = $delivery_order_draft_id == "" ? null : $delivery_order_draft_id;
		$sku_id = $data["sku_id"] == "" ? null : $data["sku_id"];
		// $gudang_id = $data["gudang_id"] == "" ? null : $data["gudang_id"];
		// $gudang_detail_id = $data["gudang_detail_id"] == "" ? null : $data["gudang_detail_id"];
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
		$sku_qty = $data["sku_qty"] == "" || $data["sku_qty"] == "null" ? null : $data["sku_qty"];
		$sku_keterangan = $data["sku_keterangan"] == "" || $data["sku_keterangan"] == "null" ? null : $data["sku_keterangan"];
		$sku_tipe_stock = $data["sku_tipe_stock"] == "" || $data["sku_tipe_stock"] == "null" ? null : $data["sku_tipe_stock"];

		$this->db->set("delivery_order_detail_draft_id", $dod_id);
		$this->db->set("delivery_order_draft_id", $delivery_order_draft_id);
		$this->db->set("sku_id", $sku_id);
		// $this->db->set("gudang_id", $gudang_id);
		// $this->db->set("gudang_detail_id", $gudang_detail_id);
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
		$this->db->set("tipe_stock_nama", $sku_tipe_stock);


		$queryinsert = $this->db->insert("delivery_order_detail_draft");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_confirm_delivery_order_detail_draft_retur($dod_id, $delivery_order_draft_id, $data)
	{
		$delivery_order_draft_id = $delivery_order_draft_id == "" ? null : $delivery_order_draft_id;
		$sku_id = $data["sku_id"] == "" ? null : $data["sku_id"];
		// $gudang_id = $data["gudang_id"] == "" ? null : $data["gudang_id"];
		// $gudang_detail_id = $data["gudang_detail_id"] == "" ? null : $data["gudang_detail_id"];
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
		$sku_qty = $data["sku_qty"] == "" || $data["sku_qty"] == "null" ? null : $data["sku_qty"];
		$sku_keterangan = $data["sku_keterangan"] == "" || $data["sku_keterangan"] == "null" ? null : $data["sku_keterangan"];
		$sku_tipe_stock = $data["sku_tipe_stock"] == "" || $data["sku_tipe_stock"] == "null" ? null : $data["sku_tipe_stock"];

		$this->db->set("delivery_order_detail_draft_id", $dod_id);
		$this->db->set("delivery_order_draft_id", $delivery_order_draft_id);
		$this->db->set("sku_id", $sku_id);
		// $this->db->set("gudang_id", $gudang_id);
		// $this->db->set("gudang_detail_id", $gudang_detail_id);
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
		$this->db->set("tipe_stock_nama", $sku_tipe_stock);


		$queryinsert = $this->db->insert("delivery_order_detail_draft");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function update_delivery_order_draft(
		$delivery_order_draft_id,
		$sales_order_id,
		$delivery_order_draft_kode,
		$delivery_order_draft_yourref,
		$client_wms_id,
		$delivery_order_draft_tgl_buat_do,
		$delivery_order_draft_tgl_expired_do,
		$delivery_order_draft_tgl_surat_jalan,
		$delivery_order_draft_tgl_rencana_kirim,
		$delivery_order_draft_tgl_aktual_kirim,
		$delivery_order_draft_keterangan,
		$delivery_order_draft_status,
		$delivery_order_draft_is_prioritas,
		$delivery_order_draft_is_need_packing,
		$delivery_order_draft_tipe_layanan,
		$delivery_order_draft_tipe_pembayaran,
		$delivery_order_draft_sesi_pengiriman,
		$delivery_order_draft_request_tgl_kirim,
		$delivery_order_draft_request_jam_kirim,
		$tipe_pengiriman_id,
		$nama_tipe,
		$confirm_rate,
		$delivery_order_draft_reff_id,
		$delivery_order_draft_reff_no,
		$delivery_order_draft_total,
		$unit_mandiri_id,
		$depo_id,
		$client_pt_id,
		$delivery_order_draft_kirim_nama,
		$delivery_order_draft_kirim_alamat,
		$delivery_order_draft_kirim_telp,
		$delivery_order_draft_kirim_provinsi,
		$delivery_order_draft_kirim_kota,
		$delivery_order_draft_kirim_kecamatan,
		$delivery_order_draft_kirim_kelurahan,
		$delivery_order_draft_kirim_kodepos,
		$delivery_order_draft_kirim_area,
		$delivery_order_draft_kirim_invoice_pdf,
		$delivery_order_draft_kirim_invoice_dir,
		$pabrik_id,
		$delivery_order_draft_ambil_nama,
		$delivery_order_draft_ambil_alamat,
		$delivery_order_draft_ambil_telp,
		$delivery_order_draft_ambil_provinsi,
		$delivery_order_draft_ambil_kota,
		$delivery_order_draft_ambil_kecamatan,
		$delivery_order_draft_ambil_kelurahan,
		$delivery_order_draft_ambil_kodepos,
		$delivery_order_draft_ambil_area,
		$delivery_order_draft_update_who,
		$delivery_order_draft_update_tgl,
		$delivery_order_draft_approve_who,
		$delivery_order_draft_approve_tgl,
		$delivery_order_draft_reject_who,
		$delivery_order_draft_reject_tgl,
		$delivery_order_draft_reject_reason,
		$tipe_delivery_order_id,
		$delivery_order_draft_input_pembayaran_tunai,
		$name_file
	) {
		// $tgl = $tgl . " " . date('H:i:s');
		// $sales_order_id = $sales_order_id == "" ? null : $sales_order_id;
		$delivery_order_draft_kode = $delivery_order_draft_kode == "" ? null : $delivery_order_draft_kode;
		$delivery_order_draft_yourref = $delivery_order_draft_yourref == "" ? null : $delivery_order_draft_yourref;
		$client_wms_id = $client_wms_id == "" ? null : $client_wms_id;
		$delivery_order_draft_tgl_buat_do = $delivery_order_draft_tgl_buat_do == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_tgl_buat_do)));
		$delivery_order_draft_tgl_expired_do = $delivery_order_draft_tgl_expired_do == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_tgl_expired_do)));
		$delivery_order_draft_tgl_surat_jalan = $delivery_order_draft_tgl_surat_jalan == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_tgl_surat_jalan)));
		$delivery_order_draft_tgl_rencana_kirim = $delivery_order_draft_tgl_rencana_kirim == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_tgl_rencana_kirim)));
		$delivery_order_draft_tgl_aktual_kirim = $delivery_order_draft_tgl_aktual_kirim == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_tgl_aktual_kirim)));
		$delivery_order_draft_keterangan = $delivery_order_draft_keterangan == "" ? null : $delivery_order_draft_keterangan;
		$delivery_order_draft_status = $delivery_order_draft_status == "" ? null : $delivery_order_draft_status;
		$delivery_order_draft_is_prioritas = $delivery_order_draft_is_prioritas == "" ? null : $delivery_order_draft_is_prioritas;
		$delivery_order_draft_is_need_packing = $delivery_order_draft_is_need_packing == "" ? null : $delivery_order_draft_is_need_packing;
		$delivery_order_draft_tipe_layanan = $delivery_order_draft_tipe_layanan == "" ? null : $delivery_order_draft_tipe_layanan;
		$delivery_order_draft_tipe_pembayaran = $delivery_order_draft_tipe_pembayaran == "" ? null : $delivery_order_draft_tipe_pembayaran;
		$delivery_order_draft_sesi_pengiriman = $delivery_order_draft_sesi_pengiriman == "" ? null : $delivery_order_draft_sesi_pengiriman;
		$delivery_order_draft_request_tgl_kirim = $delivery_order_draft_request_tgl_kirim == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_draft_request_tgl_kirim)));
		$delivery_order_draft_request_jam_kirim = $delivery_order_draft_request_jam_kirim == "" ? null : $delivery_order_draft_request_jam_kirim;
		$tipe_pengiriman_id = $tipe_pengiriman_id == "" ? null : $tipe_pengiriman_id;
		$nama_tipe = $nama_tipe == "" ? null : $nama_tipe;
		$confirm_rate = $confirm_rate == "" ? null : $confirm_rate;
		$delivery_order_draft_reff_id = $delivery_order_draft_reff_id == "" ? null : $delivery_order_draft_reff_id;
		$delivery_order_draft_reff_no = $delivery_order_draft_reff_no == "" ? null : $delivery_order_draft_reff_no;
		$delivery_order_draft_total = $delivery_order_draft_total == "" ? null : $delivery_order_draft_total;
		$unit_mandiri_id = $unit_mandiri_id == "" ? null : $unit_mandiri_id;
		$depo_id = $depo_id == "" ? null : $depo_id;
		$client_pt_id = $client_pt_id == "" ? null : $client_pt_id;
		$delivery_order_draft_kirim_nama = $delivery_order_draft_kirim_nama == "" ? null : $delivery_order_draft_kirim_nama;
		$delivery_order_draft_kirim_alamat = $delivery_order_draft_kirim_alamat == "" ? null : $delivery_order_draft_kirim_alamat;
		$delivery_order_draft_kirim_telp = $delivery_order_draft_kirim_telp == "" ? null : $delivery_order_draft_kirim_telp;
		$delivery_order_draft_kirim_provinsi = $delivery_order_draft_kirim_provinsi == "" ? null : $delivery_order_draft_kirim_provinsi;
		$delivery_order_draft_kirim_kota = $delivery_order_draft_kirim_kota == "" ? null : $delivery_order_draft_kirim_kota;
		$delivery_order_draft_kirim_kecamatan = $delivery_order_draft_kirim_kecamatan == "" ? null : $delivery_order_draft_kirim_kecamatan;
		$delivery_order_draft_kirim_kelurahan = $delivery_order_draft_kirim_kelurahan == "" ? null : $delivery_order_draft_kirim_kelurahan;
		$delivery_order_draft_kirim_kodepos = $delivery_order_draft_kirim_kodepos == "" ? null : $delivery_order_draft_kirim_kodepos;
		$delivery_order_draft_kirim_area = $delivery_order_draft_kirim_area == "" ? null : $delivery_order_draft_kirim_area;
		$delivery_order_draft_kirim_invoice_pdf = $delivery_order_draft_kirim_invoice_pdf == "" ? null : $delivery_order_draft_kirim_invoice_pdf;
		$delivery_order_draft_kirim_invoice_dir = $delivery_order_draft_kirim_invoice_dir == "" ? null : $delivery_order_draft_kirim_invoice_dir;
		$pabrik_id = $pabrik_id == "" ? null : $pabrik_id;
		$delivery_order_draft_ambil_nama = $delivery_order_draft_ambil_nama == "" ? null : $delivery_order_draft_ambil_nama;
		$delivery_order_draft_ambil_alamat = $delivery_order_draft_ambil_alamat == "" ? null : $delivery_order_draft_ambil_alamat;
		$delivery_order_draft_ambil_telp = $delivery_order_draft_ambil_telp == "" ? null : $delivery_order_draft_ambil_telp;
		$delivery_order_draft_ambil_provinsi = $delivery_order_draft_ambil_provinsi == "" ? null : $delivery_order_draft_ambil_provinsi;
		$delivery_order_draft_ambil_kota = $delivery_order_draft_ambil_kota == "" ? null : $delivery_order_draft_ambil_kota;
		$delivery_order_draft_ambil_kecamatan = $delivery_order_draft_ambil_kecamatan == "" ? null : $delivery_order_draft_ambil_kecamatan;
		$delivery_order_draft_ambil_kelurahan = $delivery_order_draft_ambil_kelurahan == "" ? null : $delivery_order_draft_ambil_kelurahan;
		$delivery_order_draft_ambil_kodepos = $delivery_order_draft_ambil_kodepos == "" ? null : $delivery_order_draft_ambil_kodepos;
		$delivery_order_draft_ambil_area = $delivery_order_draft_ambil_area == "" ? null : $delivery_order_draft_ambil_area;
		$delivery_order_draft_update_who = $delivery_order_draft_update_who == "" ? null : $delivery_order_draft_update_who;
		$delivery_order_draft_update_tgl = $delivery_order_draft_update_tgl == "" ? null : $delivery_order_draft_update_tgl;
		$delivery_order_draft_approve_who = $delivery_order_draft_approve_who == "" ? null : $delivery_order_draft_approve_who;
		$delivery_order_draft_approve_tgl = $delivery_order_draft_approve_tgl == "" ? null : $delivery_order_draft_approve_tgl;
		$delivery_order_draft_reject_who = $delivery_order_draft_reject_who == "" ? null : $delivery_order_draft_reject_who;
		$delivery_order_draft_reject_tgl = $delivery_order_draft_reject_tgl == "" ? null : $delivery_order_draft_reject_tgl;
		$delivery_order_draft_reject_reason = $delivery_order_draft_reject_reason == "" ? null : $delivery_order_draft_reject_reason;
		$tipe_delivery_order_id = $tipe_delivery_order_id == "" ? null : $tipe_delivery_order_id;
		$delivery_order_draft_input_pembayaran_tunai = $delivery_order_draft_input_pembayaran_tunai == "" ? null : $delivery_order_draft_input_pembayaran_tunai;
		$name_file = $name_file == "" ? null : $name_file;

		// $tgl_ = date_create(str_replace("/", "-", $tgl));
		// $this->db->set("sales_order_id", $sales_order_id);
		$this->db->set("delivery_order_draft_yourref", $delivery_order_draft_yourref);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("delivery_order_draft_tgl_buat_do", $delivery_order_draft_tgl_buat_do);
		$this->db->set("delivery_order_draft_tgl_expired_do", $delivery_order_draft_tgl_expired_do);
		$this->db->set("delivery_order_draft_tgl_surat_jalan", $delivery_order_draft_tgl_surat_jalan);
		$this->db->set("delivery_order_draft_tgl_rencana_kirim", $delivery_order_draft_tgl_rencana_kirim);
		$this->db->set("delivery_order_draft_tgl_aktual_kirim", $delivery_order_draft_tgl_aktual_kirim);
		$this->db->set("delivery_order_draft_keterangan", $delivery_order_draft_keterangan);
		$this->db->set("delivery_order_draft_status", $delivery_order_draft_status);
		$this->db->set("delivery_order_draft_is_prioritas", $delivery_order_draft_is_prioritas);
		$this->db->set("delivery_order_draft_is_need_packing", $delivery_order_draft_is_need_packing);
		$this->db->set("delivery_order_draft_tipe_layanan", $delivery_order_draft_tipe_layanan);
		$this->db->set("delivery_order_draft_tipe_pembayaran", $delivery_order_draft_tipe_pembayaran);
		$this->db->set("delivery_order_draft_sesi_pengiriman", $delivery_order_draft_sesi_pengiriman);
		$this->db->set("delivery_order_draft_request_tgl_kirim", $delivery_order_draft_request_tgl_kirim);
		$this->db->set("delivery_order_draft_request_jam_kirim", $delivery_order_draft_request_jam_kirim);
		$this->db->set("tipe_pengiriman_id", $tipe_pengiriman_id);
		$this->db->set("nama_tipe", $nama_tipe);
		$this->db->set("confirm_rate", $confirm_rate);
		$this->db->set("delivery_order_draft_reff_id", $delivery_order_draft_reff_id);
		$this->db->set("delivery_order_draft_reff_no", $delivery_order_draft_reff_no);
		$this->db->set("delivery_order_draft_total", $delivery_order_draft_total);
		$this->db->set("unit_mandiri_id", $unit_mandiri_id);
		// $this->db->set("depo_id", $depo_id);
		// $this->db->set("client_pt_id", $client_pt_id);
		$this->db->set("delivery_order_draft_kirim_nama", $delivery_order_draft_kirim_nama);
		$this->db->set("delivery_order_draft_kirim_alamat", $delivery_order_draft_kirim_alamat);
		$this->db->set("delivery_order_draft_kirim_telp", $delivery_order_draft_kirim_telp);
		$this->db->set("delivery_order_draft_kirim_provinsi", $delivery_order_draft_kirim_provinsi);
		$this->db->set("delivery_order_draft_kirim_kota", $delivery_order_draft_kirim_kota);
		$this->db->set("delivery_order_draft_kirim_kecamatan", $delivery_order_draft_kirim_kecamatan);
		$this->db->set("delivery_order_draft_kirim_kelurahan", $delivery_order_draft_kirim_kelurahan);
		$this->db->set("delivery_order_draft_kirim_kodepos", $delivery_order_draft_kirim_kodepos);
		$this->db->set("delivery_order_draft_kirim_area", $delivery_order_draft_kirim_area);
		$this->db->set("delivery_order_draft_kirim_invoice_pdf", $delivery_order_draft_kirim_invoice_pdf);
		$this->db->set("delivery_order_draft_kirim_invoice_dir", $delivery_order_draft_kirim_invoice_dir);
		$this->db->set("pabrik_id", $pabrik_id);
		$this->db->set("delivery_order_draft_ambil_nama", $delivery_order_draft_ambil_nama);
		$this->db->set("delivery_order_draft_ambil_alamat", $delivery_order_draft_ambil_alamat);
		$this->db->set("delivery_order_draft_ambil_telp", $delivery_order_draft_ambil_telp);
		$this->db->set("delivery_order_draft_ambil_provinsi", $delivery_order_draft_ambil_provinsi);
		$this->db->set("delivery_order_draft_ambil_kota", $delivery_order_draft_ambil_kota);
		$this->db->set("delivery_order_draft_ambil_kecamatan", $delivery_order_draft_ambil_kecamatan);
		$this->db->set("delivery_order_draft_ambil_kelurahan", $delivery_order_draft_ambil_kelurahan);
		$this->db->set("delivery_order_draft_ambil_kodepos", $delivery_order_draft_ambil_kodepos);
		$this->db->set("delivery_order_draft_ambil_area", $delivery_order_draft_ambil_area);
		$this->db->set("delivery_order_draft_update_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_draft_update_tgl", "GETDATE()", FALSE);
		$this->db->set("delivery_order_draft_approve_who", $delivery_order_draft_approve_who);
		$this->db->set("delivery_order_draft_approve_tgl", $delivery_order_draft_approve_tgl);
		$this->db->set("delivery_order_draft_reject_who", $delivery_order_draft_reject_who);
		$this->db->set("delivery_order_draft_reject_tgl", $delivery_order_draft_reject_tgl);
		$this->db->set("delivery_order_draft_reject_reason", $delivery_order_draft_reject_reason);
		$this->db->set("tipe_delivery_order_id", $tipe_delivery_order_id);
		$this->db->set("delivery_order_draft_nominal_tunai", $delivery_order_draft_input_pembayaran_tunai);
		if ($name_file != null) {
			$this->db->set("delivery_order_draft_attachment", $name_file);
		}

		$this->db->where("delivery_order_draft_id", $delivery_order_draft_id);

		$queryinsert = $this->db->update("delivery_order_draft");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function confirm_delivery_order_draft($delivery_order_draft_id)
	{
		$this->db->set("delivery_order_draft_status", "Approved");
		$this->db->set("delivery_order_draft_update_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_draft_update_tgl", "GETDATE()", FALSE);
		$this->db->set("delivery_order_draft_approve_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_draft_approve_tgl", "GETDATE()", FALSE);

		$this->db->where("delivery_order_draft_id", $delivery_order_draft_id);

		$queryinsert = $this->db->update("delivery_order_draft");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function reject_delivery_order_draft($delivery_order_draft_id)
	{
		$this->db->set("delivery_order_draft_status", "Rejected");
		$this->db->set("delivery_order_draft_update_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_draft_update_tgl", "GETDATE()", FALSE);
		$this->db->set("delivery_order_draft_reject_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_draft_reject_tgl", "GETDATE()", FALSE);

		$this->db->where("delivery_order_draft_id", $delivery_order_draft_id);

		$queryinsert = $this->db->update("delivery_order_draft");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_delivery_order_detail_draft_log($delivery_order_draft_id)
	{

		$sql = "INSERT delivery_order_detail_draft_log
			SELECT NEWID()
				  ,[delivery_order_detail_draft_id]
				  ,[delivery_order_draft_id]
				  ,[sku_id]
				  ,[gudang_id]
				  ,[gudang_detail_id]
				  ,[sku_kode]
				  ,[sku_nama_produk]
				  ,[sku_harga_satuan]
				  ,[sku_disc_percent]
				  ,[sku_disc_rp]
				  ,[sku_harga_nett]
				  ,[sku_request_expdate]
				  ,[sku_filter_expdate]
				  ,[sku_filter_expdatebulan]
				  ,[sku_filter_expdatetahun]
				  ,[sku_weight]
				  ,[sku_weight_unit]
				  ,[sku_length]
				  ,[sku_length_unit]
				  ,[sku_width]
				  ,[sku_width_unit]
				  ,[sku_height]
				  ,[sku_height_unit]
				  ,[sku_volume]
				  ,[sku_volume_unit]
				  ,[sku_qty]
				  ,[sku_keterangan]
				  ,[tipe_stock_nama]
				  ,'" . date("Y-m-d H:i:s") . "' as log_tanggal
				  ,'edit do draft' as log_tipe
				  ,'" . $this->session->userdata('pengguna_username') . "' as log_create_who
			FROM delivery_order_detail_draft
			WHERE delivery_order_draft_id = ? ";
		return $this->db->query($sql, array($delivery_order_draft_id));
	}

	public function delete_delivery_order_detail_draft($delivery_order_draft_id)
	{
		$this->db->where('delivery_order_draft_id', $delivery_order_draft_id);
		return $this->db->delete('delivery_order_detail_draft');
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
			WHERE ss.client_wms_id = '$client_wms_id'
			AND ss.depo_id = '" . $this->session->userdata('depo_id') . "'
			AND ss.sku_id = '$sku_id'
			AND ISNULL(dd.depo_detail_flag_jual,'0') = '1'
			AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
			AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
			AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
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
								LEFT JOIN depo_detail dd
								ON ss.depo_detail_id = dd.depo_detail_id
								WHERE ss.client_wms_id = '$client_wms_id' 
								AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
								AND ss.sku_id = '$sku_id'
								AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
								AND ISNULL(dd.depo_detail_flag_jual, 0) '1' 
								AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
								AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
								AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
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
									ss.sku_stock_is_deleted,
									sku.sku_konversi_faktor
									FROM sku_stock ss
									LEFT JOIN sku ON ss.sku_id = sku.sku_id
									LEFT JOIN depo_detail dd
									ON ss.depo_detail_id = dd.depo_detail_id
									WHERE ss.client_wms_id = '$client_wms_id' 
									AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
									AND ss.sku_id = '$sku_id'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= '$tgl_do'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') <= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
									AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
									AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
									AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
									AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
									AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
									ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUFOByReqNull($client_wms_id, $sku_id)
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
			WHERE ss.client_wms_id = '$client_wms_id' 
			AND ss.depo_id = '" . $this->session->userdata('depo_id') . "'
			AND ss.sku_id = '$sku_id'
			AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
			AND ISNULL(dd.depo_detail_is_flashout,'0') = '1' 
			AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
			AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
			AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
			ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUFOByReqBesarDari($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
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
								LEFT JOIN depo_detail dd
								ON ss.depo_detail_id = dd.depo_detail_id
								WHERE ss.client_wms_id = '$client_wms_id' 
								AND ss.depo_id = '" . $this->session->userdata('depo_id') . "'
								AND ss.sku_id = '$sku_id'
								AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
								AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
								AND ISNULL(dd.depo_detail_is_flashout,'0') = '1' 
								AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
								AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
								AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
								ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUFOByReqKurangDari($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
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
									LEFT JOIN depo_detail dd
									ON ss.depo_detail_id = dd.depo_detail_id
									WHERE ss.client_wms_id = '$client_wms_id' 
									AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
									AND ss.sku_id = '$sku_id'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= '$tgl_do'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') <= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
									AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
									AND ISNULL(dd.depo_detail_is_flashout,'0') = '1' 
									AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
									AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
									AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
									ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUByReqNullPembongkaran($client_wms_id, $sku_id)
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

		$query = $this->db->query("SELECT TOP 1
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
			WHERE ss.client_wms_id = '$client_wms_id' 
			AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
			AND ss.sku_id = '$sku_id'
			AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
			AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
			AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
			AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
			AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
			ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUByReqBesarDariPembongkaran($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
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

		$query = $this->db->query("SELECT TOP 1
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
								LEFT JOIN depo_detail dd
								ON ss.depo_detail_id = dd.depo_detail_id
								WHERE ss.client_wms_id = '$client_wms_id' 
								AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
								AND ss.sku_id = '$sku_id'
								AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
								AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
								AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
								AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
								AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
								AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
								ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUByReqKurangDariPembongkaran($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
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

		$query = $this->db->query("SELECT TOP 1
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
									LEFT JOIN depo_detail dd
									ON ss.depo_detail_id = dd.depo_detail_id
									WHERE ss.client_wms_id = '$client_wms_id' 
									AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
									AND ss.sku_id = '$sku_id'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= '$tgl_do'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') <= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
									AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
									AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
									AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
									AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
									AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
									ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUFOByReqNullPembongkaran($client_wms_id, $sku_id)
	{

		$query = $this->db->query("SELECT TOP 1
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
			WHERE ss.client_wms_id = '$client_wms_id' 
			AND ss.depo_id = '" . $this->session->userdata('depo_id') . "'
			AND ss.sku_id = '$sku_id'
			AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
			AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
			AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
			AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
			AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
			ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUFOByReqBesarDariPembongkaran($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
	{

		$query = $this->db->query("SELECT TOP 1
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
								WHERE ss.client_wms_id = '$client_wms_id' 
								AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
								AND ss.sku_id = '$sku_id'
								AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
								AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
								AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
								AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
								AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
								AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
								ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUFOByReqKurangDariPembongkaran($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
	{

		$query = $this->db->query("SELECT TOP 1
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
									WHERE ss.client_wms_id = '$client_wms_id' 
									AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
									AND ss.sku_id = '$sku_id'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= '$tgl_do'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') <= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
									AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
									AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
									AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
									AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
									AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
									ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUReturByReqNull($client_wms_id, $sku_id)
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

		$query = $this->db->query("SELECT TOP 1
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
			WHERE ss.client_wms_id = '$client_wms_id'
			AND ss.depo_id = '" . $this->session->userdata('depo_id') . "'
			AND ss.sku_id = '$sku_id'
			AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
			AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
			AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
			AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
			AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
			ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUReturByReqBesarDari($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
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

		$query = $this->db->query("SELECT TOP 1
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
								LEFT JOIN depo_detail dd
								ON ss.depo_detail_id = dd.depo_detail_id
								WHERE ss.client_wms_id = '$client_wms_id' 
								AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
								AND ss.sku_id = '$sku_id'
								AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
								AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
								AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
								AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
								AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
								AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
								ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUReturByReqKurangDari($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
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

		$query = $this->db->query("SELECT TOP 1
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
									LEFT JOIN depo_detail dd
									ON ss.depo_detail_id = dd.depo_detail_id
									WHERE ss.client_wms_id = '$client_wms_id' 
									AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
									AND ss.sku_id = '$sku_id'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= '$tgl_do'
									AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') <= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
									AND ISNULL(dd.depo_detail_flag_jual, 0) = '1' 
									AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
									AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
									AND ISNULL(dd.depo_detail_is_bonus,'0') = '0'
									AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
									ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function InsertToDODetail2Draft($id, $dod_id, $do_id, $sku_id, $sku_stock_id, $ed, $qty, $sku_konversi_faktor)
	{
		$this->db->set('delivery_order_detail2_draft_id', $id);
		$this->db->set('delivery_order_detail_draft_id', $dod_id);
		$this->db->set('delivery_order_draft_id', $do_id);
		$this->db->set('sku_id', $sku_id);
		$this->db->set('sku_stock_id', $sku_stock_id);
		$this->db->set('sku_expdate', $ed);
		$this->db->set('sku_qty', $qty);
		$this->db->set('sku_qty_composite', $qty * $sku_konversi_faktor);
		return $this->db->insert('delivery_order_detail2_draft');

		// $this->db->insert('delivery_order_detail2_draft');
		// return $this->db->last_query();
	}

	public function InsertToDODetail2Draft_retur($id, $dod_id, $do_id, $sku_id, $sku_stock_id, $ed, $qty, $sku_konversi_faktor)
	{
		$this->db->set('delivery_order_detail2_draft_id', $id);
		$this->db->set('delivery_order_detail_draft_id', $dod_id);
		$this->db->set('delivery_order_draft_id', $do_id);
		$this->db->set('sku_id', $sku_id);
		$this->db->set('sku_stock_id', $sku_stock_id);
		$this->db->set('sku_expdate', $ed);
		$this->db->set('sku_qty', $qty);
		$this->db->set('sku_qty_composite', $sku_konversi_faktor);
		return $this->db->insert('delivery_order_detail2_draft');

		// $this->db->insert('delivery_order_detail2_draft');
		// return $this->db->last_query();
	}

	public function Insert_delivery_order_draft_detail_msg($do_id, $sku_kode, $msg, $principle)
	{
		$this->db->set('delivery_order_draft_detail_msg_id', "NEWID()", FALSE);
		$this->db->set('depo_id', $this->session->userdata('depo_id'));
		$this->db->set('delivery_order_draft_id', $do_id);
		$this->db->set('sku_kode', $sku_kode);
		$this->db->set('delivery_order_draft_detail_msg', $msg);
		$this->db->set('principle_id', $principle == "" ? null : $principle);
		return $this->db->insert('delivery_order_draft_detail_msg');
	}

	public function GetDataStockAlokasi($sku_stock_id)
	{
		return $this->db->select("*")
			->from('sku_stock')
			->where('sku_stock_id', $sku_stock_id)
			->get()->row();
	}

	public function UpdateDataSKUStockAlokasi($sisaAlokasi, $sisaSaldoAlokasi, $sku_stock_id)
	{
		$this->db->set('sku_stock_alokasi', $sisaAlokasi);
		$this->db->set('sku_stock_saldo_alokasi', $sisaSaldoAlokasi);
		$this->db->where('sku_stock_id', $sku_stock_id);
		return $this->db->update('sku_stock');
	}

	public function getNameSKUById($client_wms_id, $id)
	{
		return $this->db->select("sku_kode, sku_nama_produk as sku")->from("sku")->where("client_wms_id", $client_wms_id)->where("sku_id", $id)->get()->row();
	}

	public function update_area_client_pt($client_pt_id, $area_id)
	{

		$this->db->set("area_id", $area_id);
		$this->db->where("client_pt_id", $client_pt_id);

		$this->db->update("client_pt");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
		// return $this->db->last_query();
	}


	public function getDataSearchEditDO($tgl1, $tgl2, $area, $status, $principle)
	{
		if ($area == "") {
			$areaSearch = "";
		} else {
			$areaSearch = "AND do.delivery_order_draft_kirim_area = '$area'";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND do.delivery_order_draft_status = '$status'";
		}

		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND so.principle_id = '$principle'";
		}

		$query = $this->db->query("SELECT
									ISNULL(principle.principle_kode, '') AS principle_kode,
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									ISNULL(so.sales_order_kode,'') AS sales_order_kode,
									ISNULL(so.sales_order_no_po,'') AS sales_order_no_po,
									format(do.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') AS delivery_order_draft_tgl_rencana_kirim,
									CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_nama ELSE do.delivery_order_draft_kirim_nama END delivery_order_draft_kirim_nama,
									CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_alamat ELSE do.delivery_order_draft_kirim_alamat END delivery_order_draft_kirim_alamat,
									ISNULL(do.delivery_order_draft_kirim_area,'') AS delivery_order_draft_kirim_area,
									do.delivery_order_draft_status,
									tp.tipe_delivery_order_nama
									FROM delivery_order_draft do
									LEFT JOIN sales_order so ON so.sales_order_id = do.sales_order_id
									LEFT JOIN tipe_delivery_order tp on tp.tipe_delivery_order_id = do.tipe_delivery_order_id
									LEFT JOIN principle ON so.principle_id = principle.principle_id
									
									WHERE format(do.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
										AND do.delivery_order_draft_id not in(select delivery_order_draft_id from delivery_order
										where delivery_order_draft_id is not null and depo_id = '" . $this->session->userdata('depo_id') . "')
										AND do.delivery_order_draft_status <> 'Rejected'
										AND do.delivery_order_draft_status <> 'canceled'
										AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
									" . $areaSearch . " " . $status . " " . $principle . "
									ORDER BY do.delivery_order_draft_tgl_rencana_kirim DESC, do.delivery_order_draft_kirim_area ASC, do.delivery_order_draft_kirim_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function getDataSearchUbahKeDraft($tgl1, $tgl2, $area)
	{
		if ($area == "") {
			$areaSearch = "";
		} else {
			$areaSearch = "AND do.delivery_order_draft_kirim_area = '$area'";
		}

		$query = $this->db->query("SELECT
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									ISNULL(so.sales_order_kode,'') AS sales_order_kode,
									ISNULL(so.sales_order_no_po,'') AS sales_order_no_po,
									format(do.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') AS delivery_order_draft_tgl_rencana_kirim,
									CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_nama ELSE do.delivery_order_draft_kirim_nama END delivery_order_draft_kirim_nama,
									CASE WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_alamat ELSE do.delivery_order_draft_kirim_alamat END delivery_order_draft_kirim_alamat,
									ISNULL(do.delivery_order_draft_kirim_area,'') AS delivery_order_draft_kirim_area,
									do.delivery_order_draft_status,
									tp.tipe_delivery_order_nama
									FROM delivery_order_draft do
									LEFT JOIN sales_order so ON so.sales_order_id = do.sales_order_id
									LEFT JOIN tipe_delivery_order tp on tp.tipe_delivery_order_id = do.tipe_delivery_order_id
									WHERE format(do.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
										AND do.delivery_order_draft_status = 'Approved'
										AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
										AND tp.tipe_delivery_order_nama = 'Bulk'
										AND do.delivery_order_draft_id not in (select delivery_order_draft_id from delivery_order where delivery_order_draft_id is not null)
									" . $areaSearch . "
									ORDER BY do.delivery_order_draft_tgl_rencana_kirim DESC, do.delivery_order_draft_kirim_area ASC, do.delivery_order_draft_kirim_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
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
			->where("depo_id", $this->session->userdata('depo_id'));
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
		$query = $this->db->query("select * from sku where sku_konversi_group = '$sku_konversi_group' and sku_konversi_level <> 0 order by sku_konversi_level asc");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
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

	public function get_sku_stock_by_sku($sku_id)
	{
		$query = $this->db->query("SELECT 
									sku_stock_id,
									unit_mandiri_id,
									client_wms_id,
									depo_id,
									depo_detail_id,
									sku_induk_id,
									sku_id,
									FORMAT(sku_stock_expired_date,'yyyy-MM-dd') AS sku_stock_expired_date,
									sku_stock_batch_no,
									sku_stock_awal,
									sku_stock_masuk,
									sku_stock_alokasi,
									sku_stock_saldo_alokasi,
									sku_stock_keluar,
									sku_stock_akhir,
									sku_stock_is_jual,
									sku_stock_is_aktif,
									sku_stock_is_deleted
									FROM sku_stock 
									WHERE sku_id = '$sku_id' 
									AND depo_id = '" . $this->session->userdata('depo_id') . "' 
									AND depo_detail_id IN (SELECT depo_detail_id FROM depo_detail WHERE depo_id = '" . $this->session->userdata('depo_id') . "') 
									ORDER BY sku_stock_expired_date ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
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

	public function get_sku_stock_by_gudang($client_wms_id, $depo_detail_id, $sku_id)
	{
		// $query = $this->db->query("SELECT 
		// 							sku_stock_id,
		// 							unit_mandiri_id,
		// 							client_wms_id,
		// 							depo_id,
		// 							depo_detail_id,
		// 							sku_induk_id,
		// 							sku_id,
		// 							FORMAT(sku_stock_expired_date,'yyyy-MM-dd') AS sku_stock_expired_date,
		// 							sku_stock_batch_no,
		// 							sku_stock_awal,
		// 							sku_stock_masuk,
		// 							sku_stock_alokasi,
		// 							sku_stock_saldo_alokasi,
		// 							sku_stock_keluar,
		// 							sku_stock_akhir,
		// 							sku_stock_is_jual,
		// 							sku_stock_is_aktif,
		// 							sku_stock_is_deleted
		// 							FROM sku_stock 
		// 							WHERE sku_id = '$sku_id' 
		// 							AND depo_id = '" . $this->session->userdata('depo_id') . "' 
		// 							AND depo_detail_id IN (SELECT depo_detail_id FROM depo_detail WHERE depo_id = '" . $this->session->userdata('depo_id') . "') 
		// 							ORDER BY sku_stock_expired_date ASC");

		$query = $this->db->query("SELECT 
									sku_stock_id,
									unit_mandiri_id,
									client_wms_id,
									depo_id,
									depo_detail_id,
									sku_induk_id,
									sku_id,
									FORMAT(sku_stock_expired_date,'yyyy-MM-dd') AS sku_stock_expired_date,
									sku_stock_batch_no,
									sku_stock_awal,
									sku_stock_masuk,
									sku_stock_alokasi,
									sku_stock_saldo_alokasi,
									sku_stock_keluar,
									sku_stock_akhir,
									sku_stock_is_jual,
									sku_stock_is_aktif,
									sku_stock_is_deleted
									FROM sku_stock 
									WHERE sku_id = '$sku_id' 
									AND depo_id = '" . $this->session->userdata('depo_id') . "' 
									AND client_wms_id = '$client_wms_id'
									AND depo_detail_id = '$depo_detail_id' 
									ORDER BY sku_stock_expired_date ASC");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
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

	public function updateStatusDODraft($value)
	{
		$this->db->set("delivery_order_draft_status", 'canceled');
		$this->db->where("delivery_order_draft_id", $value);
		$queryupdate = $this->db->update("delivery_order_draft");

		return $queryupdate;
	}

	public function Insert_tr_konversi_sku_from_do_detail($tr_konversi_sku_from_do_id, $delivery_order_id)
	{
		$query = $this->db->query("select * from tr_konversi_sku_from_do_detail where tr_konversi_sku_from_do_id = '$tr_konversi_sku_from_do_id' and delivery_order_id = '$delivery_order_id'");

		// if ($query->num_rows() == 0) {
		// 	$tr_konversi_sku_from_do_id = $tr_konversi_sku_from_do_id == "" ? null : $tr_konversi_sku_from_do_id;
		// 	$delivery_order_id = $delivery_order_id == "" ? null : $delivery_order_id;

		// 	$this->db->set("tr_konversi_sku_from_do_detail_id", "NEWID()", FALSE);
		// 	$this->db->set("delivery_order_id", $delivery_order_id);
		// 	$this->db->set("tr_konversi_sku_from_do_id", $tr_konversi_sku_from_do_id);

		// 	$queryinsert = $this->db->insert("tr_konversi_sku_from_do_detail");
		// }

		// return $queryinsert;
		return $this->db->last_query();
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

	public function Delete_qty_tr_konversi_sku_detail($tr_konversi_sku_id, $sku_id)
	{
		$this->db->where("tr_konversi_sku_id", $tr_konversi_sku_id);
		$this->db->where("sku_id", $sku_id);

		$queryinsert = $this->db->delete("tr_konversi_sku_detail");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function delete_tr_konversi_sku_from_do()
	{

		$this->db->query("delete from tr_konversi_sku_from_do_detail where tr_konversi_sku_from_do_id in (select tr_konversi_sku_from_do_id from tr_konversi_sku_from_do where depo_id = '" . $this->session->userdata('depo_id') . "') ");
		$this->db->query("delete from tr_konversi_sku_from_do where depo_id = '" . $this->session->userdata('depo_id') . "' ");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Delete_delivery_order_draft_detail_msg($principle)
	{
		$this->db->where("depo_id", $this->session->userdata('depo_id'));
		if ($principle != "") {
			$this->db->where("principle_id", $principle);
		}

		$querydelete = $this->db->delete("delivery_order_draft_detail_msg");

		return $querydelete;
		// return $this->db->last_query();
	}

	public function get_delivery_order_draft_detail_msg($principle)
	{
		if ($principle == "" || $principle == NULL) {
			$principle = "";
		} else {
			$principle = "AND msg.principle_id = '$principle'";
		}

		$query = $this->db->query("SELECT
								msg.delivery_order_draft_detail_msg_id,
								msg.depo_id,
								msg.delivery_order_draft_id,
								do.delivery_order_draft_kode,
								msg.sku_kode,
								sku.sku_nama_produk,
								msg.delivery_order_draft_detail_msg AS pesan_error,
								p.principle_kode AS principle,
								pt.client_pt_nama AS outlet,
								s.client_pt_segmen_nama AS segmen,
								case when dod.sku_harga_nett <> 0 then 'Jual' ELSE 'Bonus' END AS flag
							FROM delivery_order_draft_detail_msg msg
							LEFT JOIN delivery_order_draft do
							ON msg.delivery_order_draft_id = do.delivery_order_draft_id 
							LEFT JOIN sku
							ON sku.sku_kode = msg.sku_kode
							LEFT JOIN principle p
							ON msg.principle_id = p.principle_id
							LEFT JOIN client_pt pt
							ON do.client_pt_id = pt.client_pt_id
							LEFT JOIN client_pt_segmen s
							ON pt.client_pt_segmen_id1 = s.client_pt_segmen_id
							LEFT JOIN delivery_order_detail_draft dod
							ON do.delivery_order_draft_id = dod.delivery_order_draft_id AND dod.sku_kode = msg.sku_kode
							WHERE msg.depo_id = '" . $this->session->userdata('depo_id') . "'
							$principle
							ORDER BY do.delivery_order_draft_kode, msg.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function proses_insertupdate_sku_stock($tipe, $sku_stock_id, $sku_qty)
	{
		$this->db->query("exec insertupdate_sku_stock '$tipe', '$sku_stock_id', NULL, '$sku_qty'");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function deleteDODetail2DraftByDOID($dod_id)
	{
		$this->db->where('delivery_order_draft_id', $dod_id);
		$this->db->delete('delivery_order_detail2_draft');

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}

	public function get_sku_stock_by_sku_id($sku_id)
	{

		$query = $this->db->query("SELECT
									sku_stock_id,
									sku_id,
									sku_stock.depo_detail_id,
									depo_detail.depo_detail_nama,
									FORMAT(sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
									ISNULL(sku_stock_batch_no, '') sku_stock_batch_no
									FROM sku_stock
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock.depo_detail_id
									WHERE sku_stock.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND sku_id = '$sku_id'
									ORDER BY FORMAT(sku_stock_expired_date, 'yyyy-MM-dd') ASC, ISNULL(sku_stock_batch_no, '') ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function get_do_detail2_sementara($sku_id, $arr_do_detail2)
	{

		$union = " UNION ALL ";
		$table_sementara = "";
		$list_sku_id = array();
		foreach ($arr_do_detail2 as $key => $value) {
			array_push($list_sku_id, "'" . $value['sku_id'] . "'");

			$table_sementara .= "SELECT '" . $value['idx'] . "' AS idx, '" . $value['sku_id'] . "' AS sku_id, '" . $value['sku_kode'] . "' AS sku_kode, '" . $value['sku_nama_produk'] . "' AS sku_nama_produk, '" . $value['sku_stock_id'] . "' AS sku_stock_id,'" . $value['sku_stock_expired_date'] . "' AS sku_stock_expired_date," . $value['sku_qty'] . " AS sku_qty," . $value['sku_konversi_faktor'] . " AS sku_konversi_faktor";

			if ($key < count($arr_do_detail2) - 1) {
				$table_sementara .= $union;
			}
		}

		$query = $this->db->query("SELECT
									idx,
									sku_id,
									sku_kode,
									sku_nama_produk,
									sku_stock_id,
									sku_stock_expired_date,
									sku_qty,
									sku_konversi_faktor
									FROM (" . $table_sementara . ") a
									WHERE sku_id = '$sku_id'
									ORDER BY idx ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function get_do_detail2($delivery_order_draft_id, $sku_id)
	{

		$query = $this->db->query("SELECT * FROM delivery_order_detail2_draft WHERE delivery_order_draft_id = '$delivery_order_draft_id' AND sku_id = '$sku_id' ");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function cek_total_sku_qty_detail2($sku_id, $arr_do_detail2)
	{

		$union = " UNION ALL ";
		$table_sementara = "";
		$list_sku_id = array();
		foreach ($arr_do_detail2 as $key => $value) {
			array_push($list_sku_id, "'" . $value['sku_id'] . "'");

			$table_sementara .= "SELECT '" . $value['idx'] . "' AS idx, '" . $value['sku_id'] . "' AS sku_id, '" . $value['sku_kode'] . "' AS sku_kode, '" . $value['sku_nama_produk'] . "' AS sku_nama_produk, '" . $value['sku_stock_id'] . "' AS sku_stock_id,'" . $value['sku_stock_expired_date'] . "' AS sku_stock_expired_date," . $value['sku_qty'] . " AS sku_qty," . $value['sku_konversi_faktor'] . " AS sku_konversi_faktor";

			if ($key < count($arr_do_detail2) - 1) {
				$table_sementara .= $union;
			}
		}

		$query = $this->db->query("SELECT
									sku_id,
									sku_kode,
									sku_nama_produk,
									SUM(sku_qty) AS sku_qty
									FROM (" . $table_sementara . ") a
									WHERE sku_id = '$sku_id'
									GROUP BY sku_id,
											sku_kode,
											sku_nama_produk
									ORDER BY sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDataSKUByReqNullBonus($client_wms_id, $sku_id)
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
			WHERE ss.client_wms_id = '$client_wms_id'
			AND ss.depo_id = '" . $this->session->userdata('depo_id') . "'
			AND ss.sku_id = '$sku_id'
			AND ISNULL(dd.depo_detail_flag_jual,'0') = '1'
			AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
			AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
			AND ISNULL(dd.depo_detail_is_bonus,'0') = '1'
			AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
			ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUByReqNullPembongkaranBonus($client_wms_id, $sku_id)
	{
		$query = $this->db->query("SELECT TOP 1
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
			WHERE ss.client_wms_id = '$client_wms_id' 
			AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
			AND ss.sku_id = '$sku_id'
			AND ISNULL(dd.depo_detail_flag_jual,'0') = '1'
			AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
			AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
			AND ISNULL(dd.depo_detail_is_bonus,'0') = '1'
			AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
			ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUByReqBesarDariBonus($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
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
								LEFT JOIN depo_detail dd
								ON ss.depo_detail_id = dd.depo_detail_id
								WHERE ss.client_wms_id = '$client_wms_id' 
								AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
								AND ss.sku_id = '$sku_id'
								AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
								AND ISNULL(dd.depo_detail_flag_jual,'0') = '1'
								AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
								AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
								AND ISNULL(dd.depo_detail_is_bonus,'0') = '1'
								AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
								ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetDataSKUByReqBesarDariPembongkaranBonus($client_wms_id, $sku_id, $sku_filter_expdate, $tgl_do)
	{
		$query = $this->db->query("SELECT TOP 1
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
								LEFT JOIN depo_detail dd
								ON ss.depo_detail_id = dd.depo_detail_id
								WHERE ss.client_wms_id = '$client_wms_id' 
								AND ss.depo_id = '" . $this->session->userdata('depo_id') . "' 
								AND ss.sku_id = '$sku_id'
								AND FORMAT(ss.sku_stock_expired_date, 'yyyy-MM-dd') >= DATEADD(MONTH, $sku_filter_expdate, '$tgl_do')
								AND ISNULL(dd.depo_detail_flag_jual,'0') = '1'
								AND ISNULL(dd.depo_detail_is_flashout,'0') = '0' 
								AND ISNULL(dd.depo_detail_is_kirimulang,'0') = '0'
								AND ISNULL(dd.depo_detail_is_bonus,'0') = '1'
								AND ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock_keluar, 0) > 0
								ORDER BY ss.sku_stock_expired_date, ss.sku_id, ss.sku_stock_batch_no ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function delete_delivery_order_detail2_draft($delivery_order_draft_id)
	{
		$this->db->where('delivery_order_draft_id', $delivery_order_draft_id);
		return $this->db->delete('delivery_order_detail2_draft');
	}

	public function GetDeliveryOrderDraftPrioritasStokByFilter($tgl, $tipe)
	{
		$wtipe = "";
		if ($tipe == 'DO Reschedule') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0)
						AND isnull(dd.depo_detail_is_flashout, 0) = 0
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 1
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		if ($tipe == 'Picking per SKU' || $tipe == 'Canvas') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0,1)
						AND isnull(dd.depo_detail_is_flashout, 0) = 0
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 0
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		if ($tipe == 'Flush Out') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0)
						AND isnull(dd.depo_detail_is_flashout, 0) = 1
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 0
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		$depo_id = $this->session->userdata("depo_id");

		$query = $this->db->query("SELECT
									principle.principle_kode AS principle,
									brand.principle_brand_nama AS brand,
									s.client_wms_id,
									s.sku_kode,
									s.sku_id,
									s.sku_nama_produk,
									SUM(dtl.sku_qty) AS sku_qty_draft,
									ISNULL(ss.stok, 0) AS sku_stock,
									s.sku_konversi_group,
									s.sku_kemasan,
									s.sku_satuan,
									SUM(dtl.sku_qty) * s.sku_konversi_faktor AS qty_composite,
									s.sku_konversi_level,
									s.sku_konversi_faktor,
									CASE
										WHEN SUM(dtl.sku_qty) > ISNULL(ss.stok, 0) THEN 1
										ELSE 0
									END AS type_request,
									CASE
										WHEN ISNULL(dtl.sku_harga_nett,0) = 0 THEN 'Bonus'
										ELSE 'Reguler'
									END AS flag
									FROM delivery_order_draft hdr
									LEFT JOIN delivery_order_detail_draft dtl
									ON dtl.delivery_order_draft_id = hdr.delivery_order_draft_id
									LEFT JOIN (SELECT
									sku_id,
									SUM(ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(ss.sku_stock_saldo_alokasi, 0) - ISNULL(ss.sku_stock_keluar, 0)) AS stok
									FROM sku_stock ss
									INNER JOIN depo_detail dd
									ON dd.depo_detail_id = ss.depo_detail_id
									WHERE ss.depo_id = '$depo_id'
									" . $wtipe . "
									GROUP BY sku_id) ss
									ON dtl.sku_id = ss.sku_id
									LEFT JOIN tipe_delivery_order tp
									ON tp.tipe_delivery_order_id = hdr.tipe_delivery_order_id
									INNER JOIN sku s
									ON s.sku_id = dtl.sku_id
									LEFT JOIN principle
									ON principle.principle_id = s.principle_id
									LEFT JOIN principle_brand brand
									ON brand.principle_brand_id = s.principle_brand_id
									WHERE hdr.depo_id = '$depo_id'
									AND FORMAT(hdr.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') <= '$tgl'
									AND hdr.delivery_order_draft_status = 'Draft'
									AND tp.tipe_delivery_order_alias = '$tipe'
									GROUP BY principle.principle_kode,
											brand.principle_brand_nama,
											s.client_wms_id,
											s.sku_kode,
											s.sku_id,
											s.sku_nama_produk,
											ss.stok,
											s.sku_konversi_group,
											s.sku_kemasan,
											s.sku_satuan,
											s.sku_konversi_level,
											s.sku_konversi_faktor,
											CASE
												WHEN ISNULL(dtl.sku_harga_nett,0) = 0 THEN 'Bonus'
												ELSE 'Reguler'
											END
									ORDER BY principle.principle_kode,brand.principle_brand_nama, s.sku_kode");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_delivery_order_draft_detail_by_sku_id($tgl, $tipe, $sku_id, $flag)
	{
		$wtipe = "";
		if ($tipe == 'DO Reschedule') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0)
						AND isnull(dd.depo_detail_is_flashout, 0) = 0
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 1
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		if ($tipe == 'Picking per SKU' || $tipe == 'Canvas') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0,1)
						AND isnull(dd.depo_detail_is_flashout, 0) = 0
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 0
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		if ($tipe == 'Flush Out') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0)
						AND isnull(dd.depo_detail_is_flashout, 0) = 1
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 0
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		$depo_id = $this->session->userdata("depo_id");

		$query = $this->db->query("SELECT
									hdr.delivery_order_draft_id,
									dtl.delivery_order_detail_draft_id,
									hdr.delivery_order_draft_kode AS do_draft_kode,
									FORMAT(hdr.delivery_order_draft_tgl_buat_do, 'dd/MM/yyyy') AS delivery_order_draft_tgl_buat_do,
									FORMAT(hdr.delivery_order_draft_tgl_rencana_kirim, 'dd/MM/yyyy') AS delivery_order_draft_tgl_rencana_kirim,
									hdr.delivery_order_draft_kirim_nama AS do_draft_nama,
									hdr.delivery_order_draft_kirim_alamat AS do_draft_alamat,
									CASE WHEN hdr.delivery_order_draft_is_prioritas = '1' THEN 'YA' ELSE 'TIDAK' END AS delivery_order_draft_is_prioritas,
									hdr.client_pt_id,
									client_pt.client_pt_segmen_id1,
									ISNULL(segment1.client_pt_segmen_nama,'') as segment1,
									client_pt.client_pt_segmen_id2,
									ISNULL(segment2.client_pt_segmen_nama,'') as segment2,
									client_pt.client_pt_segmen_id3,
									ISNULL(segment3.client_pt_segmen_nama,'') as segment3,
									dtl.sku_id,
									SUM(dtl.sku_qty) AS do_draft_qty,
									SUM(ISNULL(ss.stok, 0)) AS sku_stock_qty,
									so.sales_order_no_po
								FROM delivery_order_draft hdr
									LEFT JOIN delivery_order_detail_draft dtl
									ON dtl.delivery_order_draft_id = hdr.delivery_order_draft_id
									LEFT JOIN (SELECT
									sku_id,
									SUM(ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(ss.sku_stock_saldo_alokasi, 0) - ISNULL(ss.sku_stock_keluar, 0)) AS stok
									FROM sku_stock ss
									INNER JOIN depo_detail dd
									ON dd.depo_detail_id = ss.depo_detail_id
									WHERE ss.depo_id = '$depo_id'
									" . $wtipe . "
									GROUP BY sku_id) ss
									ON dtl.sku_id = ss.sku_id
									LEFT JOIN tipe_delivery_order tp
									ON tp.tipe_delivery_order_id = hdr.tipe_delivery_order_id
									INNER JOIN sku s
									ON s.sku_id = dtl.sku_id
									LEFT JOIN principle
									ON principle.principle_id = s.principle_id
									LEFT JOIN principle_brand brand
									ON brand.principle_brand_id = s.principle_brand_id
									LEFT JOIN client_pt
									ON client_pt.client_pt_id = hdr.client_pt_id
									LEFT JOIN client_pt_segmen segment1
									ON segment1.client_pt_segmen_id = client_pt.client_pt_segmen_id1
									LEFT JOIN client_pt_segmen segment2
									ON segment2.client_pt_segmen_id = client_pt.client_pt_segmen_id2
									LEFT JOIN client_pt_segmen segment3
									ON segment3.client_pt_segmen_id = client_pt.client_pt_segmen_id3
									left join sales_order so
									on so.sales_order_id = hdr.sales_order_id
									
									WHERE hdr.depo_id = '$depo_id'
									AND FORMAT(hdr.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') <= '$tgl'
									AND hdr.delivery_order_draft_status = 'Draft'
									AND tp.tipe_delivery_order_alias = '$tipe'
									AND dtl.sku_id = '$sku_id'
									AND CASE
										WHEN ISNULL(dtl.sku_harga_nett,0) = 0 THEN 'Bonus'
										ELSE 'Reguler'
									END = '$flag'
									GROUP BY hdr.delivery_order_draft_id,
											dtl.delivery_order_detail_draft_id,
											hdr.delivery_order_draft_kode,
											FORMAT(hdr.delivery_order_draft_tgl_buat_do, 'dd/MM/yyyy'),
											FORMAT(hdr.delivery_order_draft_tgl_rencana_kirim, 'dd/MM/yyyy'),
											hdr.delivery_order_draft_kirim_nama,
											hdr.delivery_order_draft_kirim_alamat,
											hdr.delivery_order_draft_is_prioritas,
											hdr.client_pt_id,
											client_pt.client_pt_segmen_id1,
											ISNULL(segment1.client_pt_segmen_nama,''),
											client_pt.client_pt_segmen_id2,
											ISNULL(segment2.client_pt_segmen_nama,''),
											client_pt.client_pt_segmen_id3,
											ISNULL(segment3.client_pt_segmen_nama,''),
											dtl.sku_id,
											so.sales_order_no_po
									ORDER BY hdr.delivery_order_draft_is_prioritas DESC, FORMAT(hdr.delivery_order_draft_tgl_rencana_kirim, 'dd/MM/yyyy') DESC, hdr.delivery_order_draft_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Insert_delivery_order_detail_draft_log_prioritas_stok($delivery_order_detail_draft_id, $delivery_order_draft_id, $sku_id, $sku_qty, $log_tipe)
	{

		$query = $this->db->query("INSERT delivery_order_detail_draft_log ( 
				delivery_order_detail_draft_log_id,
				delivery_order_detail_draft_id,
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
				tipe_stock_nama,
				log_tanggal,
				log_tipe,
				log_create_who)
				SELECT
				NEWID(),
				delivery_order_detail_draft_id,
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
				tipe_stock_nama,
				GETDATE() as log_tanggal,
				'" . $log_tipe . "' as log_tipe,
				'" . $this->session->userdata('pengguna_username') . "' as log_create_who
				FROM delivery_order_detail_draft
				WHERE delivery_order_detail_draft_id = '$delivery_order_detail_draft_id'
				AND delivery_order_draft_id = '$delivery_order_draft_id'");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Update_delivery_order_draft_prioritas_stok($delivery_order_detail_draft_id, $delivery_order_draft_id, $sku_id, $sku_qty)
	{
		// $tgl = $tgl . " " . date('H:i:s');
		$delivery_order_detail_draft_id = $delivery_order_detail_draft_id == "" ? null : $delivery_order_detail_draft_id;
		$delivery_order_draft_id = $delivery_order_draft_id == "" ? null : $delivery_order_draft_id;
		$sku_id = $sku_id == "" ? null : $sku_id;
		$sku_qty = $sku_qty == "" ? null : $sku_qty;

		// $tgl_ = date_create(str_replace("/", "-", $tgl));

		$this->db->set("delivery_order_draft_update_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_draft_update_tgl", "GETDATE()", FALSE);

		$this->db->where("delivery_order_draft_id", $delivery_order_draft_id);

		$this->db->update("delivery_order_draft");


		$this->db->set("sku_qty", $sku_qty);

		$this->db->where("delivery_order_detail_draft_id", $delivery_order_detail_draft_id);

		$queryinsert = $this->db->update("delivery_order_detail_draft");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insertMutasiStok($delivery_order_draft_id)
	{

		$delivery_order_draft_id = "'" . implode("', '", $delivery_order_draft_id) . "'";
		$depo_detail_reschedule = $this->db->query("select * from depo_detail where depo_id ='" . $this->session->userdata('depo_id') . "' and depo_detail_is_kirimulang = 1")->row();
		$data = $this->db->query("SELECT ss.client_wms_id,
			ss.sku_stock_id,
			ss.sku_id,
			dod2.delivery_order_draft_id,
			s.principle_id,
			isnull(dod2.sku_qty, 0) as selisih,
			ss.sku_stock_expired_date as sku_expdate,
			ss.depo_id
		FROM sku_stock ss
		inner JOIN (SELECT
		*
		FROM delivery_order_detail2_draft
		WHERE delivery_order_draft_id  IN ($delivery_order_draft_id)) dod2
		ON ss.sku_id = dod2.sku_id
		AND format(ss.sku_stock_expired_date, 'yyyy-MM-dd') = dod2.sku_expdate
		Inner join sku s
		ON ss.sku_id = s.sku_id and dod2.sku_id = s.sku_id
		WHERE depo_detail_id = '$depo_detail_reschedule->depo_detail_id'
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
				$this->db->set('depo_detail_asal', $depo_detail_reschedule->depo_detail_id);
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
}
