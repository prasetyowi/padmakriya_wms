<?php

class M_DeliveryOrderBatch extends CI_Model
{
	public function GetHeaderFDJRById($id)
	{
		$this->db->select("a.*, b.kendaraan_volume_cm3_max as volumeMax, b.kendaraan_berat_gr_max as beratMax, b.kendaraan_id as kendaraanID, b.karyawan_id as karyawanID, b.kendaraan_volume_cm3_terpakai as volumeTerpakai, b.kendaraan_berat_gr_terpakai as beratTerpakai")
			->from("delivery_order_batch a")
			->join("delivery_order_batch_h b", "a.delivery_order_batch_h_id = b.delivery_order_batch_h_id", "left")
			->where("a.delivery_order_batch_id", $id)
			->order_by("a.delivery_order_batch_kode");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetCetakHeaderFDJRById($id)
	{

		$query = $this->db->query("SELECT
										do.delivery_order_id,
										do.delivery_order_kode,
										FORMAT(do.delivery_order_tgl_buat_do, 'dd/MM/yyyy') as delivery_order_tgl_aktual_kirim,
										FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd/MM/yyyy') as delivery_order_tgl_rencana_kirim,
										FORMAT(do.delivery_order_tgl_aktual_kirim, 'dd/MM/yyyy') as delivery_order_tgl_aktual_kirim,
										FORMAT(do.delivery_order_tgl_expired_do, 'dd/MM/yyyy') as delivery_order_tgl_expired_do,
										do.sales_order_id,
										so.sales_order_kode,
										FORMAT(so.sales_order_tgl_create, 'dd/MM/yyyy') as sales_order_tgl_create,
										FORMAT(so.sales_order_tgl, 'dd/MM/yyyy') as sales_order_tgl,
										FORMAT(so.sales_order_tgl_kirim, 'dd/MM/yyyy') as sales_order_tgl_kirim,
										FORMAT(so.sales_order_tgl_harga, 'dd/MM/yyyy') as sales_order_tgl_harga,
										FORMAT(so.sales_order_tgl_exp, 'dd/MM/yyyy') as sales_order_tgl_exp,
										so.sales_order_tipe_pembayaran,
										so.sales_order_no_po,
										FORMAT(bso.dtmOrder, 'dd/MM/yyyy') as dtmOrder,
										FORMAT(bso.dtmExpiration, 'dd/MM/yyyy') as dtmExpiration,
										CASE
											WHEN do.delivery_order_tipe_pembayaran = '1' THEN 
											'TUNAI'
											ELSE
											'KREDIT'
										END delivery_order_tipe_pembayaran,
										CASE
											WHEN bso.bCash = '1' THEN 'TUNAI'
											ELSE 'TRANSFER / BG'
										END bCash,
										do.client_wms_id,
										cw.client_wms_nama,
										cw.client_wms_alamat,
										cw.client_wms_telepon,
										do.client_pt_id,
										do.delivery_order_kirim_nama,
										do.delivery_order_kirim_alamat,
										do.delivery_order_kirim_telp,
										so.client_pt_id,
										cp.client_pt_nama,
										cp.client_pt_alamat,
										cp.client_pt_telepon,
										cp.client_pt_npwp,
										fdjr.karyawan_id,
										driver.karyawan_nama as driver_nama,
										so.sales_id,
										sales.karyawan_nama as sales_nama,
										fdjr.kendaraan_id,
										kd.kendaraan_nopol,
										FORMAT(do.delivery_order_approve_tgl, 'dd/MM/yyyy') as delivery_order_approve_tgl,
										do.delivery_order_approve_who
									from delivery_order do
									left join delivery_order_batch fdjr
									on fdjr.delivery_order_batch_id = do.delivery_order_batch_id
									left join sales_order so
									on so.sales_order_id = do.sales_order_id
									left join MIDDLEWARE.dbo.bosnet_so bso
									on bso.szFSoId = so.sales_order_no_po
									left join client_pt cp
									on cp.client_pt_id = so.client_pt_id
									left join client_wms cw
									on cw.client_wms_id = do.client_wms_id
									left join karyawan driver
									on driver.karyawan_id = fdjr.karyawan_id
									left join karyawan sales
									on sales.karyawan_id = so.sales_id
									left join kendaraan kd
									on kd.kendaraan_id = fdjr.kendaraan_id
									where do.delivery_order_batch_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetCetakHeaderFDJRDetailById($id)
	{

		$data = array();
		$query_laporan = $this->db->query("SELECT
										so.sales_order_id,
										do.delivery_order_detail_id,
										do.delivery_order_id,
										do.sku_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.sku_satuan,
										sku.sku_konversi_group,
										sku.sku_konversi_faktor,
										do.sku_qty,
										so.sku_harga_satuan,
										so.sku_harga_nett,
										so.diskon1,
										so.diskon2,
										so.diskon3,
										ISNULL(so.sku_harga_nett, 0) - ISNULL(so.diskon1, 0) - ISNULL(so.diskon2, 0) - ISNULL(so.diskon3, 0) + ISNULL(so.sku_ppn_rp, 0) as sku_harga_nett_ppn
									FROM (select
										do.sales_order_id,
										dod.delivery_order_detail_id,
										dod.delivery_order_id,
										dod.delivery_order_batch_id,
										CASE WHEN ISNULL(dod.sku_harga_satuan, 0) > 0 THEN 'JUAL' ELSE 'BONUS' END as tipe,
										dod.sku_id,
										dod.depo_id,
										dod.depo_detail_id,
										dod.sku_kode,
										dod.sku_nama_produk,
										dod.sku_harga_satuan,
										dod.sku_disc_percent,
										dod.sku_disc_rp,
										dod.sku_harga_nett,
										dod.sku_request_expdate,
										dod.sku_filter_expdate,
										dod.sku_filter_expdatebulan,
										dod.sku_filter_expdatetahun,
										dod.sku_weight,
										dod.sku_weight_unit,
										dod.sku_length,
										dod.sku_length_unit,
										dod.sku_width,
										dod.sku_width_unit,
										dod.sku_height,
										dod.sku_height_unit,
										dod.sku_volume,
										dod.sku_volume_unit,
										dod.sku_qty,
										dod.sku_keterangan,
										dod.sku_qty_kirim,
										dod.reason_id,
										dod.tipe_stock_nama
									from delivery_order_detail dod
									inner join delivery_order do
									on do.delivery_order_id = dod.delivery_order_id
									where do.delivery_order_batch_id = '$id') do
									LEFT JOIN (select
										sod.sales_order_detail_id,
										sod.sales_order_id,
										sod.client_wms_id,
										CASE WHEN ISNULL(sod.sku_harga_satuan, 0) > 0 THEN 'JUAL' ELSE 'BONUS' END as tipe,
										sod.sku_id,
										sod.sku_kode,
										sod.sku_nama_produk,
										sod.sku_harga_satuan,
										sod.sku_disc_percent,
										sod.sku_disc_rp,
										sod.sku_harga_nett,
										sod.sku_request_expdate,
										sod.sku_filter_expdate,
										sod.sku_filter_expdatebulan,
										sod.sku_filter_expdatetahun,
										sod.sku_weight,
										sod.sku_weight_unit,
										sod.sku_length,
										sod.sku_length_unit,
										sod.sku_width,
										sod.sku_width_unit,
										sod.sku_height,
										sod.sku_height_unit,
										sod.sku_volume,
										sod.sku_volume_unit,
										sod.sku_qty,
										sod.sku_keterangan,
										sod.tipe_stock_nama,
										sod.is_promo,
										sod.sku_ppn_percent,
										sod.sku_ppn_rp,
										sod.sku_diskon_global_percent,
										sod.sku_diskon_global_rp,
										sod.sales_order_detail_tipe,
										sod.sku_disc_promo_rp,
										ISNULL((SELECT SUM(sku_promo_diskon_amount) as diskon 
										FROM sales_order_detail_promo sodp 
										WHERE sodp.sales_order_id = sod.sales_order_id
										AND sodp.sku_id = sod.sku_id
										AND sodp.sales_order_detail_tipe = 'DISKON'
										AND LEFT(sodp.no_reff_external, 3) = 'D1-'
										AND sodp.external_system = 'BOSNET'), 0) as diskon1,
										ISNULL((SELECT SUM(sku_promo_diskon_amount) as diskon 
										FROM sales_order_detail_promo sodp 
										WHERE sodp.sales_order_id = sod.sales_order_id
										AND sodp.sku_id = sod.sku_id
										AND sodp.sales_order_detail_tipe = 'DISKON'
										AND LEFT(sodp.no_reff_external, 3) = 'D2-'
										AND sodp.external_system = 'BOSNET'), 0) as diskon2,
										ISNULL((SELECT SUM(sku_promo_diskon_amount) as diskon 
										FROM sales_order_detail_promo sodp 
										WHERE sodp.sales_order_id = sod.sales_order_id
										AND sodp.sku_id = sod.sku_id
										AND sodp.sales_order_detail_tipe = 'DISKON'
										AND LEFT(sodp.no_reff_external, 3) = 'D3-'
										AND sodp.external_system = 'BOSNET'), 0) as diskon3
									from sales_order_detail sod) so
									ON so.sales_order_id = do.sales_order_id
									AND so.sku_id = do.sku_id
									AND so.sales_order_detail_tipe = do.tipe
									LEFT JOIN sku
									ON sku.sku_id = do.sku_id
									WHERE sku.sku_id is not null
									ORDER BY do.delivery_order_id, do.sku_id ASC");

		if ($query_laporan->num_rows() == 0) {
			$data = array();
		} else {
			$query_laporan = $query_laporan->result_array();

			foreach ($query_laporan as $key => $value) {

				$sku_composite =  $this->db->query("exec proses_konversi_sku_composite_simple '" . $value['sku_konversi_group'] . "'")->row(0)->sku_composite;

				array_push($data, $value);

				$data[$key]['sku_satuan'] = $sku_composite;

				$sku_composite_qty =  $this->db->query("exec proses_konversi_sku_qty_composite_simple '" . $value['sku_konversi_group'] . "', " . $value['sku_qty'] . ", " . $value['sku_konversi_faktor'])->row(0)->sku_composite_qty;

				$data[$key]['sku_qty'] = $sku_composite_qty;
			}
		}

		return $data;
	}

	public function GetCetakHeaderFDJRTotalById($id)
	{

		$query = $this->db->query("SELECT
										so.sales_order_id,
										do.delivery_order_id,
										SUM(ISNULL(so.sku_harga_nett, 0)) AS sku_harga_nett,
										SUM(ISNULL(so.diskon1, 0)) AS diskon1,
										SUM(ISNULL(so.diskon2, 0)) AS diskon2,
										SUM(ISNULL(so.diskon3, 0)) AS diskon3,
										SUM(ISNULL(so.sku_diskon_global_rp, 0)) AS sku_diskon_global_rp,
										SUM(CASE WHEN ISNULL(so.sku_ppn_rp, 0) > 0 THEN (ISNULL(so.sku_harga_nett, 0) - ISNULL(so.diskon1, 0) - ISNULL(so.diskon2, 0) - ISNULL(so.diskon3, 0) - ISNULL(so.sku_diskon_global_rp, 0)) * 0.11 ELSE 0 END) sku_ppn_rp,
										SUM(so.sku_harga_nett) - SUM(so.diskon1) - SUM(so.diskon2) - SUM(so.diskon3) - SUM(ISNULL(so.sku_diskon_global_rp, 0)) + SUM(CASE WHEN ISNULL(so.sku_ppn_rp, 0) > 0 THEN (ISNULL(so.sku_harga_nett, 0) - ISNULL(so.diskon1, 0) - ISNULL(so.diskon2, 0) - ISNULL(so.diskon3, 0) - ISNULL(so.sku_diskon_global_rp, 0)) * 0.11 ELSE 0 END) AS sku_harga_nett_ppn
									FROM (select
										do.sales_order_id,
										dod.delivery_order_detail_id,
										dod.delivery_order_id,
										dod.delivery_order_batch_id,
										CASE WHEN ISNULL(dod.sku_harga_satuan, 0) > 0 THEN 'JUAL' ELSE 'BONUS' END as tipe,
										dod.sku_id,
										dod.depo_id,
										dod.depo_detail_id,
										dod.sku_kode,
										dod.sku_nama_produk,
										dod.sku_harga_satuan,
										dod.sku_disc_percent,
										dod.sku_disc_rp,
										dod.sku_harga_nett,
										dod.sku_request_expdate,
										dod.sku_filter_expdate,
										dod.sku_filter_expdatebulan,
										dod.sku_filter_expdatetahun,
										dod.sku_weight,
										dod.sku_weight_unit,
										dod.sku_length,
										dod.sku_length_unit,
										dod.sku_width,
										dod.sku_width_unit,
										dod.sku_height,
										dod.sku_height_unit,
										dod.sku_volume,
										dod.sku_volume_unit,
										dod.sku_qty,
										dod.sku_keterangan,
										dod.sku_qty_kirim,
										dod.reason_id,
										dod.tipe_stock_nama
									from delivery_order_detail dod
									inner join delivery_order do
									on do.delivery_order_id = dod.delivery_order_id
									where do.delivery_order_batch_id = '$id') do
									LEFT JOIN (select
										sod.sales_order_detail_id,
										sod.sales_order_id,
										sod.client_wms_id,
										CASE WHEN ISNULL(sod.sku_harga_satuan, 0) > 0 THEN 'JUAL' ELSE 'BONUS' END as tipe,
										sod.sku_id,
										sod.sku_kode,
										sod.sku_nama_produk,
										sod.sku_harga_satuan,
										sod.sku_disc_percent,
										sod.sku_disc_rp,
										sod.sku_harga_nett,
										sod.sku_request_expdate,
										sod.sku_filter_expdate,
										sod.sku_filter_expdatebulan,
										sod.sku_filter_expdatetahun,
										sod.sku_weight,
										sod.sku_weight_unit,
										sod.sku_length,
										sod.sku_length_unit,
										sod.sku_width,
										sod.sku_width_unit,
										sod.sku_height,
										sod.sku_height_unit,
										sod.sku_volume,
										sod.sku_volume_unit,
										sod.sku_qty,
										sod.sku_keterangan,
										sod.tipe_stock_nama,
										sod.is_promo,
										sod.sku_ppn_percent,
										sod.sku_ppn_rp,
										sod.sku_diskon_global_percent,
										sod.sku_diskon_global_rp,
										sod.sales_order_detail_tipe,
										sod.sku_disc_promo_rp,
										ISNULL((SELECT SUM(sku_promo_diskon_amount) as diskon 
										FROM sales_order_detail_promo sodp 
										WHERE sodp.sales_order_id = sod.sales_order_id
										AND sodp.sku_id = sod.sku_id
										AND sodp.sales_order_detail_tipe = 'DISKON'
										AND LEFT(sodp.sku_konversi_group_reff, 3) = 'D1-'
										AND sodp.no_reff_external = 'BOSNET'), 0) as diskon1,
										ISNULL((SELECT SUM(sku_promo_diskon_amount) as diskon 
										FROM sales_order_detail_promo sodp 
										WHERE sodp.sales_order_id = sod.sales_order_id
										AND sodp.sku_id = sod.sku_id
										AND sodp.sales_order_detail_tipe = 'DISKON'
										AND LEFT(sodp.sku_konversi_group_reff, 3) = 'D2-'
										AND sodp.no_reff_external = 'BOSNET'), 0) as diskon2,
										ISNULL((SELECT SUM(sku_promo_diskon_amount) as diskon 
										FROM sales_order_detail_promo sodp 
										WHERE sodp.sales_order_id = sod.sales_order_id
										AND sodp.sku_id = sod.sku_id
										AND sodp.sales_order_detail_tipe = 'DISKON'
										AND LEFT(sodp.sku_konversi_group_reff, 3) = 'D3-'
										AND sodp.no_reff_external = 'BOSNET'), 0) as diskon3
									from sales_order_detail sod) so
									ON so.sales_order_id = do.sales_order_id
									AND so.sku_id = do.sku_id
									AND so.sales_order_detail_tipe = do.tipe
									LEFT JOIN sku
									ON sku.sku_id = do.sku_id
									GROUP BY so.sales_order_id,
										do.delivery_order_id");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}


	public function GetKendaraanById($id)
	{
		$this->db->select("*")
			->from("kendaraan")
			->where("kendaraan_id", $id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}
	public function GetDriverByID($id)
	{
		$this->db->select("*")
			->from("karyawan")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("karyawan_level_id", "339D8AC2-C6CE-4B47-9BFC-E372592AF521")
			->where("karyawan_id", "$id")
			->order_by("karyawan_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}
	public function GetPengemudiById($id)
	{
		$this->db->select("*")
			->from("karyawan")
			->where("karyawan_id", $id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}
	public function GetAssigmentDriver($id)
	{

		$query = $this->db->query("SELECT dob_log.* from delivery_order_batch_log dob_log left join delivery_order_batch dob on dob.delivery_order_batch_id = dob_log.delivery_order_batch_id where dob_log.delivery_order_batch_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}

	public function GetHeaderFdjrAreaById($id)
	{
		$data = $this->db->select("area_id")->from("delivery_order_area")->where('delivery_order_batch_id', $id)->get()->result();
		$response = [];
		foreach ($data as $value) {
			$response[] = $value->area_id;
		}

		return implode(',', $response);
	}

	public function GetRowHeaderFDJRById($id)
	{
		$this->db->select("*")
			->from("delivery_order_batch")
			->where("delivery_order_batch_id", $id)
			->order_by("delivery_order_batch_kode");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function GetDetailFDJRById($id)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_no_urut_rute,
									do.delivery_order_batch_id,
									do.delivery_order_id,
									do.delivery_order_kode,
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									FORMAT(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(do.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_aktual_kirim,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
										ELSE do.delivery_order_kirim_nama
									END AS delivery_order_kirim_nama,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
										ELSE do.delivery_order_kirim_alamat
									END AS delivery_order_kirim_alamat,
									CASE
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_telp
										ELSE do.delivery_order_kirim_telp
									END AS delivery_order_kirim_telp,
									do.delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias
									FROM delivery_order do
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									WHERE do.delivery_order_batch_id = '$id'
									ORDER BY do.delivery_order_no_urut_rute ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDetailFDJRByAlamat($id)
	{
		$query = $this->db->query("SELECT DISTINCT
		do.delivery_order_batch_id,
		do.delivery_order_no_urut_rute,
		CASE
		WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
		ELSE do.delivery_order_kirim_nama
		END AS delivery_order_kirim_nama,
		CASE
		WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
		ELSE do.delivery_order_kirim_alamat
		END AS delivery_order_kirim_alamat,
		CASE
		WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_telp
		ELSE do.delivery_order_kirim_telp
		END AS delivery_order_kirim_telp,
		CASE 
			WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_kecamatan
			ELSE delivery_order_kirim_kecamatan
		END AS delivery_order_kirim_kecamatan,
		CASE 
			WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_area
			ELSE delivery_order_kirim_area
		END AS delivery_order_kirim_area,
		CASE
		WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_latitude
		ELSE do.delivery_order_kirim_latitude
		END AS delivery_order_kirim_latitude,
		CASE
		WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_longitude
		ELSE do.delivery_order_kirim_longitude
		END AS delivery_order_kirim_longitude,
		--so.sales_order_no_po,
		--pc.principle_kode,
		NULL as sales_order_no_po,
		NULL as principle_kode,
		SUM(CAST(ISNULL(dodd2.sku_weight, 0) AS INT) * CAST(ISNULL(dodd2.sku_qty, 0) AS INT)) as sku_weight,
		SUM(CAST(ISNULL(dodd2.sku_volume, 0) AS INT) * CAST(ISNULL(dodd2.sku_qty, 0) AS INT)) as sku_volume,
		--do.delivery_order_reff_id as kirim_ulang
		NULL as kirim_ulang,
		(SELECT COUNT(distinct do1.delivery_order_id)
		FROM delivery_order do1
		WHERE do1.delivery_order_batch_id = do.delivery_order_batch_id 
		-- AND REPLACE(do1.delivery_order_kirim_alamat, '\"', '') = REPLACE(do.delivery_order_kirim_alamat, '\"', '')) AS jumlah_do
		AND do1.delivery_order_kirim_alamat = do.delivery_order_kirim_alamat
		AND do1.delivery_order_kirim_nama = do.delivery_order_kirim_nama) AS jumlah_do,
		ISNULL(xx.composite, 0) as composite
		FROM delivery_order do
		--LEFT JOIN sales_order so ON do.sales_order_id = so.sales_order_id
		--LEFT JOIN principle pc ON so.principle_id = pc.principle_id
		LEFT JOIN delivery_order_detail_draft dodd2 ON dodd2.delivery_order_draft_id = do.delivery_order_draft_id
		LEFT JOIN (
				  select distinct (
				select cast(sum(a.sku_qty) as varchar(100)) + ' ' + case 
							when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
							else sku_satuan
					   end +  '; ' AS [text()]
				  from delivery_order_detail a
			inner join sku b on a.sku_id = b.sku_id
			inner join delivery_order c on a.delivery_order_id = c.delivery_order_id and c.client_pt_id = xx.client_pt_id
			inner join tipe_delivery_order d on c.tipe_delivery_order_id = d.tipe_delivery_order_id
				 where c.delivery_order_id IN (select delivery_order_id from delivery_order dot2 where dot2.delivery_order_batch_id = '$id')
				   and d.tipe_delivery_order_tipe in ('OUT')
				group by case 
							when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
							else sku_satuan
					   end
				for xml path (''), type
	   ).value('text()[1]','NVARCHAR(MAX)') AS composite
	   ,
	   delivery_order_kirim_nama,
	   delivery_order_kirim_alamat
	from delivery_order xx
	where xx.delivery_order_id in (select delivery_order_id from delivery_order dot2 where dot2.delivery_order_batch_id = '$id')
	) as xx on do.delivery_order_kirim_nama = xx.delivery_order_kirim_nama and do.delivery_order_kirim_alamat = xx.delivery_order_kirim_alamat
		WHERE do.delivery_order_batch_id = '$id'
		GROUP BY do.delivery_order_batch_id, do.delivery_order_no_urut_rute, do.delivery_order_tipe_layanan, do.delivery_order_ambil_nama, do.delivery_order_kirim_nama, do.delivery_order_ambil_alamat,
		do.delivery_order_kirim_alamat, do.delivery_order_ambil_telp, do.delivery_order_kirim_telp, do.delivery_order_ambil_latitude, do.delivery_order_kirim_latitude, do.delivery_order_ambil_longitude,
		do.delivery_order_kirim_longitude, do.delivery_order_reff_id, do.delivery_order_ambil_kecamatan, do.delivery_order_kirim_kecamatan, do.delivery_order_ambil_area, do.delivery_order_kirim_area, xx.composite
		--, so.sales_order_no_po,
		--pc.principle_kode
		ORDER BY do.delivery_order_no_urut_rute ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDOIDByDOBatch($id)
	{
		$query = $this->db->query("SELECT 
		do.delivery_order_id
		FROM delivery_order do
		WHERE do.delivery_order_batch_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDetailFDJRByAlamatPrint($id)
	{
		$query = $this->db->query("SELECT
		do.delivery_order_batch_id,
		do.delivery_order_no_urut_rute,
		do.delivery_order_tipe_pembayaran,
		do.delivery_order_nominal_tunai,
		CASE
		WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
		ELSE do.delivery_order_kirim_nama
		END AS delivery_order_kirim_nama,
		CASE
		WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
		ELSE do.delivery_order_kirim_alamat
		END AS delivery_order_kirim_alamat,
		CASE
		WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_telp
		ELSE do.delivery_order_kirim_telp
		END AS delivery_order_kirim_telp,
		CASE
		WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_latitude
		ELSE do.delivery_order_kirim_latitude
		END AS delivery_order_kirim_latitude,
		CASE
		WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_longitude
		ELSE do.delivery_order_kirim_longitude
		END AS delivery_order_kirim_longitude,
		SUM(CAST(ISNULL(dodd2.sku_weight, 0) AS INT) * CAST(ISNULL(dodd2.sku_qty, 0) AS INT)) as sku_weight,
		SUM(CAST(ISNULL(dodd2.sku_volume, 0) AS INT) * CAST(ISNULL(dodd2.sku_qty, 0) AS INT)) as sku_volume
		FROM delivery_order do
		LEFT JOIN delivery_order_detail_draft dodd2 ON dodd2.delivery_order_draft_id = do.delivery_order_draft_id
		WHERE do.delivery_order_batch_id = '$id'
		GROUP BY do.delivery_order_batch_id, do.delivery_order_no_urut_rute, do.delivery_order_tipe_layanan, do.delivery_order_ambil_nama, do.delivery_order_tipe_pembayaran,
		do.delivery_order_nominal_tunai,do.delivery_order_kirim_nama, do.delivery_order_ambil_alamat,
		do.delivery_order_kirim_alamat, do.delivery_order_ambil_telp, do.delivery_order_kirim_telp, do.delivery_order_ambil_latitude, do.delivery_order_kirim_latitude, do.delivery_order_ambil_longitude,
		do.delivery_order_kirim_longitude
		ORDER BY do.delivery_order_no_urut_rute ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetSKUSummaryFDJRById($id)
	{
		$query = $this->db->query("SELECT
									do_detail.sku_id,
									sku.sku_kode,
									sku.sku_konversi_group,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									CASE
										WHEN do_detail.sku_request_expdate = '1' THEN 'YA'
										ELSE 'TIDAK'
									END AS sku_request_expdate,
									ISNULL(do_detail.sku_filter_expdate, '') AS sku_filter_expdate,
									ISNULL(do_detail.sku_filter_expdatebulan, '') AS sku_filter_expdatebulan,
									ISNULL(do_detail.tipe_stock_nama,'') AS tipe_stock_nama,
									SUM(do_detail.sku_qty) AS sku_qty,
									'0' AS qty_available,
									principle.principle_kode,
									SUM(do_detail.sku_qty*sku.sku_konversi_faktor) as total_konversi_pcs,
									(select top 1 b.sku_satuan from sku b where b.sku_konversi_group = sku.sku_konversi_group order by b.sku_konversi_level desc) as group_carton,
									(select top 1 b.sku_konversi_faktor from sku b where b.sku_konversi_group = sku.sku_konversi_group order by b.sku_konversi_level desc) as sku_konversi_faktor_carton
									FROM delivery_order do
									LEFT JOIN delivery_order_detail do_detail
									ON do.delivery_order_id = do_detail.delivery_order_id
									LEFT JOIN sku
									ON do_detail.sku_id = sku.sku_id
									LEFT JOIN principle 
									ON principle.principle_id = sku.principle_id
									WHERE do.delivery_order_batch_id = '$id'
									GROUP BY do_detail.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.sku_kemasan,
											sku.sku_satuan,
											do_detail.sku_request_expdate,
											do_detail.sku_filter_expdate,
											do_detail.sku_filter_expdatebulan,
											do_detail.tipe_stock_nama,
											sku.sku_konversi_faktor,
											principle.principle_kode,
											sku.sku_konversi_group
									ORDER BY sku.sku_kode ASC");

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
			->order_by("tipe_delivery_order_alias");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDOTempByIDTempWithNoUrut($delivery_order_temp_id, $alamat, $case, $nama)
	{
		if ($alamat == null) {
			$alamat = "";
			$alamat2 = "";
			$alamat3 = "";
			$nama = "";
			$nama2 = "";
			$nama3 = "";
		} else {
			$alamats = implode(",", $alamat);
			$namas = implode(",", $nama);
			// $alamat = "AND REPLACE(delivery_order_draft_kirim_alamat, '\"', '') IN (" . $alamat . ")";
			$alamat = "AND do.delivery_order_draft_kirim_alamat IN (" . $alamats . ")";
			$alamat2 = "AND c.delivery_order_draft_kirim_alamat IN (" . $alamats . ")";
			$alamat3 = "AND xx.delivery_order_draft_kirim_alamat IN (" . $alamats . ")";
			$nama = "AND do.delivery_order_draft_kirim_nama IN (" . $namas . ")";
			$nama2 = "AND c.delivery_order_draft_kirim_nama IN (" . $namas . ")";
			$nama3 = "AND xx.delivery_order_draft_kirim_nama IN (" . $namas . ")";
		}

		$query = $this->db->query("SELECT DISTINCT
        dot.delivery_order_batch_id,
        dot.delivery_order_temp_id,
        do.client_pt_id,
		-- so.sales_order_no_po,
		-- pc.principle_kode,
		NULL as sales_order_no_po,
		NULL as principle_kode,
        CASE 
          WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
          ELSE do.delivery_order_draft_kirim_nama
        END AS delivery_order_draft_kirim_nama,
        CASE 
          WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_telp
          ELSE delivery_order_draft_kirim_telp
        END AS delivery_order_draft_kirim_telp,
        -- REPLACE(CASE 
        --   WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
        --   ELSE delivery_order_draft_kirim_alamat
        -- END, '\"', '') AS delivery_order_draft_kirim_alamat,
        CASE 
          WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
          ELSE do.delivery_order_draft_kirim_alamat
        END AS delivery_order_draft_kirim_alamat,
		CASE 
			WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_kecamatan
			ELSE delivery_order_draft_kirim_kecamatan
		END AS delivery_order_draft_kirim_kecamatan,
		CASE 
			WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
			ELSE delivery_order_draft_kirim_area
		END AS delivery_order_draft_kirim_area,
		CASE
		" . $case . "
		END AS no_urut,
        client_pt.client_pt_latitude,
        client_pt.client_pt_longitude,
        SUM(CAST(ISNULL(dodd.sku_weight, 0) AS INT) * CAST(ISNULL(dodd.sku_qty, 0) AS INT)) as sku_weight,
		SUM(CAST(ISNULL(dodd.sku_volume, 0) AS INT) * CAST(ISNULL(dodd.sku_qty, 0) AS INT)) as sku_volume,
		--delivery_order.delivery_order_reff_id as kirim_ulang,
		NULL as kirim_ulang,
		(SELECT COUNT(distinct do1.delivery_order_draft_id)
		FROM delivery_order_temp dot1
		INNER JOIN delivery_order_draft do1 ON do1.delivery_order_draft_id = dot1.delivery_order_draft_id
		WHERE dot1.delivery_order_temp_id = dot.delivery_order_temp_id
		AND do1.delivery_order_draft_kirim_alamat = do.delivery_order_draft_kirim_alamat
		AND do1.delivery_order_draft_kirim_nama = do.delivery_order_draft_kirim_nama) AS jumlah_do,
		ISNULL(xx.composite, 0) as composite
        FROM delivery_order_temp dot 
        LEFT JOIN delivery_order_draft do ON do.delivery_order_draft_id = dot.delivery_order_draft_id
		--LEFT JOIN sales_order so ON do.sales_order_id = so.sales_order_id
		--LEFT JOIN principle pc ON so.principle_id = pc.principle_id
        LEFT JOIN client_pt ON client_pt.client_pt_id = do.client_pt_id
        LEFT JOIN delivery_order_detail_draft dodd ON dodd.delivery_order_draft_id = do.delivery_order_draft_id
		LEFT JOIN delivery_order ON do.delivery_order_draft_id = delivery_order.delivery_order_draft_id
		LEFT JOIN (
				select distinct (
				select cast(sum(a.sku_qty) as varchar(100)) + ' ' + case 
							when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
							else sku_satuan
						end +  '; ' AS [text()]
				from delivery_order_detail_draft a
			inner join sku b on a.sku_id = b.sku_id
			inner join delivery_order_draft c on a.delivery_order_draft_id = c.delivery_order_draft_id and c.client_pt_id = xx.client_pt_id
			inner join tipe_delivery_order d on c.tipe_delivery_order_id = d.tipe_delivery_order_id
				where c.delivery_order_draft_id IN (select delivery_order_draft_id from delivery_order_temp dot2 where dot2.delivery_order_temp_id = $delivery_order_temp_id $alamat2 $nama2)
					and d.tipe_delivery_order_tipe in ('OUT')
				group by case 
							when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
							else sku_satuan
						end
				for xml path (''), type
		).value('text()[1]','NVARCHAR(MAX)') AS composite
		,
		delivery_order_draft_kirim_nama,
		delivery_order_draft_kirim_alamat
		from delivery_order_draft xx
		where xx.delivery_order_draft_id in (select delivery_order_draft_id from delivery_order_temp dot2 where dot2.delivery_order_temp_id = $delivery_order_temp_id $alamat3 $nama3)
		) as xx on do.delivery_order_draft_kirim_nama = xx.delivery_order_draft_kirim_nama and do.delivery_order_draft_kirim_alamat = xx.delivery_order_draft_kirim_alamat
        WHERE dot.delivery_order_temp_id = $delivery_order_temp_id $alamat $nama
        GROUP BY do.delivery_order_draft_kirim_alamat, dot.delivery_order_temp_id, dot.delivery_order_batch_id, client_pt.client_pt_latitude, client_pt.client_pt_longitude, 
        do.client_pt_id, do.delivery_order_draft_tipe_layanan, do.delivery_order_draft_ambil_nama, do.delivery_order_draft_kirim_nama, do.delivery_order_draft_ambil_telp, 
        do.delivery_order_draft_kirim_telp, do.delivery_order_draft_ambil_alamat, delivery_order.delivery_order_reff_id, do.delivery_order_draft_ambil_kecamatan,
		do.delivery_order_draft_kirim_kecamatan, do.delivery_order_draft_ambil_area,
		do.delivery_order_draft_kirim_area ,xx.composite
		ORDER BY no_urut ASC");
		// AND REPLACE(do1.delivery_order_draft_kirim_alamat, '\"', '') = REPLACE(do.delivery_order_draft_kirim_alamat, '\"', '')) AS jumlah_do

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDOTempByBatch($delivery_order_temp_id, $alamat, $nama)
	{
		if ($alamat == null) {
			$alamat = "";
			$alamat2 = "";
			$alamat3 = "";
			$nama = "";
			$nama2 = "";
			$nama3 = "";
		} else {
			$alamats = implode(",", $alamat);
			$namas = implode(",", $nama);
			// $alamat = "AND REPLACE(delivery_order_draft_kirim_alamat, '\"', '') IN (" . $alamat . ")";
			$alamat = "AND do.delivery_order_draft_kirim_alamat IN (" . $alamats . ")";
			$alamat2 = "AND c.delivery_order_draft_kirim_alamat IN (" . $alamats . ")";
			$alamat3 = "AND xx.delivery_order_draft_kirim_alamat IN (" . $alamats . ")";
			$nama = "AND do.delivery_order_draft_kirim_nama IN (" . $namas . ")";
			$nama2 = "AND c.delivery_order_draft_kirim_nama IN (" . $namas . ")";
			$nama3 = "AND xx.delivery_order_draft_kirim_nama IN (" . $namas . ")";
		}

		$query = $this->db->query("SELECT DISTINCT
        dot.delivery_order_batch_id,
        dot.delivery_order_temp_id,
        do.client_pt_id,
        CASE 
          WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
          ELSE do.delivery_order_draft_kirim_nama
        END AS delivery_order_draft_kirim_nama,
        CASE 
          WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_telp
          ELSE delivery_order_draft_kirim_telp
        END AS delivery_order_draft_kirim_telp,
        -- REPLACE(CASE 
        --   WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
        --   ELSE delivery_order_draft_kirim_alamat
        -- END, '\"', '') AS delivery_order_draft_kirim_alamat,
       CASE 
          WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
          ELSE do.delivery_order_draft_kirim_alamat
        END AS delivery_order_draft_kirim_alamat,
		CASE 
			WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_kecamatan
			ELSE delivery_order_draft_kirim_kecamatan
		END AS delivery_order_draft_kirim_kecamatan,
		CASE 
			WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
			ELSE delivery_order_draft_kirim_area
		END AS delivery_order_draft_kirim_area,
        client_pt.client_pt_latitude,
        client_pt.client_pt_longitude,
		-- so.sales_order_no_po,
		-- pc.principle_kode,
		NULL as sales_order_no_po,
		NULL as principle_kode,
        SUM(CAST(ISNULL(dodd.sku_weight, 0) AS INT) * CAST(ISNULL(dodd.sku_qty, 0) AS INT)) as sku_weight,
		SUM(CAST(ISNULL(dodd.sku_volume, 0) AS INT) * CAST(ISNULL(dodd.sku_qty, 0) AS INT)) as sku_volume,
		-- delivery_order.delivery_order_reff_id as kirim_ulang,
		NULL as kirim_ulang,
		(SELECT COUNT(distinct do1.delivery_order_draft_id)
		FROM delivery_order_temp dot1
		INNER JOIN delivery_order_draft do1 ON do1.delivery_order_draft_id = dot1.delivery_order_draft_id
		WHERE dot1.delivery_order_temp_id = dot.delivery_order_temp_id
		-- AND REPLACE(do1.delivery_order_draft_kirim_alamat, '\"', '') = REPLACE(do.delivery_order_draft_kirim_alamat, '\"', '')) AS jumlah_do
		AND do1.delivery_order_draft_kirim_alamat = do.delivery_order_draft_kirim_alamat 
		AND do1.delivery_order_draft_kirim_nama = do.delivery_order_draft_kirim_nama) AS jumlah_do,
		ISNULL(xx.composite, 0) as composite
		--do.delivery_order_draft_id
        FROM delivery_order_temp dot 
        LEFT JOIN delivery_order_draft do ON do.delivery_order_draft_id = dot.delivery_order_draft_id
		--LEFT JOIN sales_order so ON do.sales_order_id = so.sales_order_id
		--LEFT JOIN principle pc ON so.principle_id = pc.principle_id
        LEFT JOIN client_pt ON client_pt.client_pt_id = do.client_pt_id
        LEFT JOIN delivery_order_detail_draft dodd ON dodd.delivery_order_draft_id = do.delivery_order_draft_id
		LEFT JOIN delivery_order ON do.delivery_order_draft_id = delivery_order.delivery_order_draft_id
		LEFT JOIN (
  					select distinct (
					select cast(sum(a.sku_qty) as varchar(100)) + ' ' + case 
								when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
								else sku_satuan
						   end +  '; ' AS [text()]
					  from delivery_order_detail_draft a
				inner join sku b on a.sku_id = b.sku_id
				inner join delivery_order_draft c on a.delivery_order_draft_id = c.delivery_order_draft_id and c.client_pt_id = xx.client_pt_id
				inner join tipe_delivery_order d on c.tipe_delivery_order_id = d.tipe_delivery_order_id
					 where c.delivery_order_draft_id IN (select delivery_order_draft_id from delivery_order_temp dot2 where dot2.delivery_order_temp_id = $delivery_order_temp_id $alamat2 $nama2)
					   and d.tipe_delivery_order_tipe in ('OUT')
					group by case 
								when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
								else sku_satuan
						   end
					for xml path (''), type
		   ).value('text()[1]','NVARCHAR(MAX)') AS composite
		   ,
		   delivery_order_draft_kirim_nama,
		   delivery_order_draft_kirim_alamat
		from delivery_order_draft xx
		where xx.delivery_order_draft_id in (select delivery_order_draft_id from delivery_order_temp dot2 where dot2.delivery_order_temp_id = $delivery_order_temp_id $alamat3 $nama3)
		) as xx on do.delivery_order_draft_kirim_nama = xx.delivery_order_draft_kirim_nama and do.delivery_order_draft_kirim_alamat = xx.delivery_order_draft_kirim_alamat
        WHERE dot.delivery_order_temp_id = $delivery_order_temp_id $alamat $nama
        GROUP BY do.delivery_order_draft_kirim_alamat, dot.delivery_order_temp_id, dot.delivery_order_batch_id, client_pt.client_pt_latitude, client_pt.client_pt_longitude, 
        do.client_pt_id, do.delivery_order_draft_tipe_layanan, do.delivery_order_draft_ambil_nama, do.delivery_order_draft_kirim_nama, do.delivery_order_draft_ambil_telp, 
        do.delivery_order_draft_kirim_telp, do.delivery_order_draft_ambil_alamat, delivery_order.delivery_order_reff_id, delivery_order_draft_ambil_kecamatan, delivery_order_draft_kirim_kecamatan, delivery_order_draft_ambil_area, delivery_order_draft_kirim_area,xx.composite");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDOTempByBatchNoUrut($delivery_order_temp_id, $alamat, $delivery_order_batch_id)
	{
		if ($alamat == null) {
			$alamat = "";
			$alamat2 = "";
			$alamat3 = "";
		} else {
			$alamats = implode(",", $alamat);
			// $alamat = "AND REPLACE(delivery_order_draft_kirim_alamat, '\"', '') IN (" . $alamat . ")";
			$alamat = "AND delivery_order_draft_kirim_alamat IN (" . $alamat . ")";
			$alamat2 = "AND c.delivery_order_draft_kirim_alamat IN (" . $alamats . ")";
			$alamat3 = "AND xx.delivery_order_draft_kirim_alamat IN (" . $alamats . ")";
		}

		$query = $this->db->query("SELECT DISTINCT
		do.delivery_order_no_urut_rute,
				dot.delivery_order_batch_id,
				dot.delivery_order_temp_id,
				dod.client_pt_id,
				CASE 
				  WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
				  ELSE dod.delivery_order_draft_kirim_nama
				END AS delivery_order_draft_kirim_nama,
				CASE 
				  WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_telp
				  ELSE delivery_order_draft_kirim_telp
				END AS delivery_order_draft_kirim_telp,
				-- REPLACE(CASE 
				--   WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
				--   ELSE delivery_order_draft_kirim_alamat
				-- END, '\"', '') AS delivery_order_draft_kirim_alamat,
				CASE 
				  WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
				  ELSE dod.delivery_order_draft_kirim_alamat
				END AS delivery_order_draft_kirim_alamat,
				CASE 
					WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_kecamatan
					ELSE delivery_order_draft_kirim_kecamatan
				END AS delivery_order_draft_kirim_kecamatan,
				CASE 
					WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
					ELSE delivery_order_draft_kirim_area
				END AS delivery_order_draft_kirim_area,
				client_pt.client_pt_latitude,
				client_pt.client_pt_longitude,
				-- so.sales_order_no_po,
				-- pc.principle_kode,
				NULL as sales_order_no_po,
				NULL as principle_kode,
				SUM(CAST(ISNULL(dodd2.sku_weight, 0) AS INT) * CAST(ISNULL(dodd2.sku_qty, 0) AS INT)) as sku_weight,
				SUM(CAST(ISNULL(dodd2.sku_volume, 0) AS INT) * CAST(ISNULL(dodd2.sku_qty, 0) AS INT)) as sku_volume,
				-- do.delivery_order_reff_id as kirim_ulang
				--dot.delivery_order_draft_id
				NULL as kirim_ulang,
				(SELECT COUNT(distinct do1.delivery_order_draft_id)
				FROM delivery_order_temp dot1
				INNER JOIN delivery_order_draft do1 ON do1.delivery_order_draft_id = dot1.delivery_order_draft_id
				WHERE dot1.delivery_order_temp_id = dot.delivery_order_temp_id
				-- AND REPLACE(do1.delivery_order_draft_kirim_alamat, '\"', '') = REPLACE(dod.delivery_order_draft_kirim_alamat, '\"', '')) AS jumlah_do
				AND do1.delivery_order_draft_kirim_alamat = dod.delivery_order_draft_kirim_alamat
				AND do1.delivery_order_draft_kirim_nama = dod.delivery_order_draft_kirim_nama) AS jumlah_do,
				ISNULL(xx.composite, 0) as composite
				FROM delivery_order_temp dot 
				LEFT JOIN delivery_order_draft dod ON dod.delivery_order_draft_id = dot.delivery_order_draft_id
				-- LEFT JOIN sales_order so ON dod.sales_order_id = so.sales_order_id
				-- LEFT JOIN principle pc ON so.principle_id = pc.principle_id
				LEFT JOIN delivery_order do ON do.delivery_order_draft_id = dot.delivery_order_draft_id
				LEFT JOIN delivery_order_detail_draft dodd2 ON dodd2.delivery_order_draft_id = dot.delivery_order_draft_id
				LEFT JOIN client_pt ON client_pt.client_pt_id = do.client_pt_id 
				LEFT JOIN (
				  select distinct (
				select cast(sum(a.sku_qty) as varchar(100)) + ' ' + case 
							when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
							else sku_satuan
					   end +  '; ' AS [text()]
				  from delivery_order_detail_draft a
			inner join sku b on a.sku_id = b.sku_id
			inner join delivery_order_draft c on a.delivery_order_draft_id = c.delivery_order_draft_id and c.client_pt_id = xx.client_pt_id
			inner join tipe_delivery_order d on c.tipe_delivery_order_id = d.tipe_delivery_order_id
				 where c.delivery_order_draft_id IN (select delivery_order_draft_id from delivery_order_temp dot2 where dot2.delivery_order_temp_id = $delivery_order_temp_id $alamat2)
				   and d.tipe_delivery_order_tipe in ('OUT')
				group by case 
							when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
							else sku_satuan
					   end
				for xml path (''), type
	   ).value('text()[1]','NVARCHAR(MAX)') AS composite
	   ,
	   delivery_order_draft_kirim_nama,
	   delivery_order_draft_kirim_alamat
	from delivery_order_draft xx
	where xx.delivery_order_draft_id in (select delivery_order_draft_id from delivery_order_temp dot2 where dot2.delivery_order_temp_id = $delivery_order_temp_id $alamat3)
	) as xx on dod.delivery_order_draft_kirim_nama = xx.delivery_order_draft_kirim_nama and dod.delivery_order_draft_kirim_alamat = xx.delivery_order_draft_kirim_alamat
		WHERE do.delivery_order_batch_id = " . $delivery_order_batch_id . " AND dot.delivery_order_temp_id = " . $delivery_order_temp_id . " " . $alamat . "
		GROUP BY do.delivery_order_no_urut_rute, dot.delivery_order_batch_id, dot.delivery_order_temp_id, dod.client_pt_id, dod.delivery_order_draft_tipe_layanan, dod.delivery_order_draft_ambil_nama,
		dod.delivery_order_draft_kirim_nama, dod.delivery_order_draft_ambil_telp, dod.delivery_order_draft_kirim_telp, dod.delivery_order_draft_ambil_alamat, dod.delivery_order_draft_kirim_alamat,
		client_pt.client_pt_latitude, client_pt.client_pt_longitude, do.delivery_order_reff_id, delivery_order_draft_kirim_kecamatan, delivery_order_draft_ambil_kecamatan, delivery_order_draft_kirim_area, delivery_order_draft_ambil_area, xx.composite
		ORDER BY do.delivery_order_no_urut_rute ASC");

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

	public function GetAreaByTgl($tgl_kirim, $do_batch_id, $mode)
	{
		if ($mode != 'create') {
			$union = "UNION
			SELECT b.area_id, b.area_nama
				FROM delivery_order_area a
				INNER JOIN area b on a.area_id = b.area_id
			WHERE a.delivery_order_batch_id = '$do_batch_id'";
		} else {
			$union = '';
		}

		$query = $this->db->query("SELECT DISTINCT c.area_id, c.area_nama
	  FROM depo_area_header a
	  LEFT JOIN area_header b
		ON b.area_header_id = a.area_header_id
	  LEFT JOIN area c
		ON c.area_header_id = b.area_header_id
	  WHERE EXISTS (SELECT *
	  FROM delivery_order_draft d
	  WHERE d.delivery_order_draft_kirim_area = c.area_nama
	  AND d.depo_id = '" . $this->session->userdata('depo_id') . "'
	  AND dbo.floordate(delivery_order_draft_tgl_rencana_kirim) <= '$tgl_kirim'
	  AND delivery_order_draft_status = 'Approved'
	  AND delivery_order_draft_id NOT IN (select delivery_order_draft_id from delivery_order where delivery_order_draft_id IS NOT NULL
	  AND d.depo_id = '" . $this->session->userdata('depo_id') . "'))
	  $union
	  ORDER BY c.area_nama ASC");

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

	public function GetTipePengiriman()
	{
		$this->db->select("*")
			->from("tipe_pengiriman")
			->order_by("tipe_pengiriman_nama_tipe");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetGudang()
	{
		$this->db->select("*")
			->from("depo_detail")
			// ->where("depo_id", $this->session->userdata('depo_id'))
			->where("depo_detail_id", "15F0B95F-A469-4086-94E6-B73306A3CBAC")
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

	public function GetDODraftById($delivery_order_draft_id, $no_urut)
	{
		$this->db->select("*,'$no_urut' AS no_urut")
			->from("delivery_order_draft")
			->join("client_pt", "client_pt.client_pt_id = delivery_order_draft.client_pt_id", "left")
			->where("delivery_order_draft_id", $delivery_order_draft_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDODraftDetailById($delivery_order_draft_id)
	{
		$this->db->select("*")
			->from("delivery_order_detail_draft")
			->where("delivery_order_draft_id", $delivery_order_draft_id);
		// ->order_by("sku_kode");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDeliveryOrderBatchByFilter($tgl1, $tgl2, $do_batch_number, $tgl3, $tgl4, $tipe_pelayanan, $pengemasan, $area, $status, $tipe_delivery_order)
	{

		if ($do_batch_number == "") {
			$do_batch_number = "";
		} else {
			$do_batch_number = "AND do.delivery_order_batch_kode LIKE '%" . $do_batch_number . "%' ";
		}

		// if ($tipe_pengiriman == "") {
		// 	$tipe_pengiriman = "";
		// } else {
		// 	$tipe_pengiriman = "AND do.tipe_pengiriman = '$tipe_pengiriman' ";
		// }
		if ($tipe_delivery_order == "") {
			$tipe_delivery_order = "";
		} else {
			$tipe_delivery_order = "AND do.tipe_delivery_order_id = '$tipe_delivery_order' ";
		}

		if ($tipe_pelayanan == "") {
			$tipe_pelayanan = "";
		} else {
			$tipe_pelayanan = "AND do.delivery_order_batch_tipe_layanan_id = '$tipe_pelayanan' ";
		}

		if ($area == "") {
			$area = "";
		} else {
			$area = "AND delivery_order_area.area_id = '$area' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND do.delivery_order_batch_status = '$status' ";
		}

		$query = $this->db->query("SELECT DISTINCT
										do.delivery_order_batch_id
									,do.delivery_order_batch_kode
									,do.delivery_order_batch_tipe_layanan_id
									,do.delivery_order_batch_tipe_layanan_nama
									,ISNULL(dobh.kendaraan_berat_gr_max, 0) AS berat_max
									,ISNULL(dobh.kendaraan_volume_cm3_max, 0) AS volume_max
									,ISNULL(do.kendaraan_berat_gr_terpakai, 0) AS berat_terpakai
									,ISNULL(do.kendaraan_volume_cm3_terpakai, 0) AS volume_terpakai
									,COUNT(DISTINCT (do2.delivery_order_id)) AS total_outlet
									,FORMAT(do.delivery_order_batch_tanggal, 'dd-MM-yyyy') AS delivery_order_batch_tanggal
									,FORMAT(do.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim
									,do.tipe_delivery_order_id
									,
										--ISNULL(tipe_pengiriman.tipe_pengiriman_nama_tipe,'') AS tipe_pengiriman_nama_tipe,
										do.delivery_order_batch_status
									,tipe_delivery_order.tipe_delivery_order_alias
									,karyawan.karyawan_nama
									,dobh.delivery_order_batch_h_ritasi
									,kendaraan.kendaraan_nopol
									,do.delivery_order_batch_update_tgl
									FROM delivery_order_batch do
									LEFT JOIN delivery_order_batch_h dobh
										ON do.delivery_order_batch_h_id = dobh.delivery_order_batch_h_id
									LEFT JOIN delivery_order do2
										ON do.delivery_order_batch_id = do2.delivery_order_batch_id
									LEFT JOIN delivery_order_area
										ON do.delivery_order_batch_id = delivery_order_area.delivery_order_batch_id
									--LEFT JOIN tipe_pengiriman ON tipe_pengiriman.tipe_pengiriman_id = do.tipe_pengiriman_id
									LEFT JOIN tipe_delivery_order
										ON tipe_delivery_order.tipe_delivery_order_id = do.tipe_delivery_order_id
									LEFT JOIN karyawan
										ON dobh.karyawan_id = karyawan.karyawan_id
									LEFT JOIN kendaraan
										ON dobh.kendaraan_id = kendaraan.kendaraan_id
									WHERE format(do.delivery_order_batch_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND format(do.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') BETWEEN '$tgl3' AND '$tgl4'
									AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
									" . $do_batch_number . "
									" . $tipe_pelayanan . "
									" . $area . "
									" . $status . "
									" . $tipe_delivery_order . "
									GROUP BY do.delivery_order_batch_id,
									do.delivery_order_batch_kode,
									do.delivery_order_batch_tipe_layanan_id,
									do.delivery_order_batch_tipe_layanan_nama,
									ISNULL(dobh.kendaraan_berat_gr_max, 0),
									ISNULL(dobh.kendaraan_volume_cm3_max, 0),
									ISNULL(do.kendaraan_berat_gr_terpakai, 0),
									ISNULL(do.kendaraan_volume_cm3_terpakai, 0),
									format(do.delivery_order_batch_tanggal, 'dd-MM-yyyy'),
									format(do.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy'),
									do.tipe_delivery_order_id,
									do.delivery_order_batch_status,
									tipe_delivery_order.tipe_delivery_order_alias,
									karyawan.karyawan_nama,
									kendaraan.kendaraan_nopol,
									dobh.delivery_order_batch_h_ritasi,
									do.delivery_order_batch_update_tgl
									ORDER BY format(do.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDODraftByFilter($tgl, $area, $tipe, $tipe_layanan, $kelas_jalan, $kendaraan)
	{
		$areaArray = [];

		for ($i = 0; $i < count($area); $i++) {
			$areaArray[$i] = "'" . $area[$i] . "'";
		}

		if (empty($area)) {
			$areaWhereIn = "";
		} else {
			$areaWhereIn = "AND CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
										ELSE delivery_order_draft_kirim_area
									END IN (" . implode(',', $areaArray) . ")";
		}

		if ($tipe == "") {
			$tipe = "";
		} else if ($tipe == "BB403FA9-007F-42CD-87F0-92CEF7B40AAD") {
			// $tipe = "AND do.tipe_delivery_order_id IN ('0E626A53-82FC-4EA6-A4A2-1265279D6E1C','ADF55030-9802-4C27-9658-F9D37AA01F95', 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD', 'EE5CD3F7-F7E3-475E-A7B4-67FD5AB65975') ";
			$tipe = "AND do.tipe_delivery_order_id IN ('0E626A53-82FC-4EA6-A4A2-1265279D6E1C','ADF55030-9802-4C27-9658-F9D37AA01F95', 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD', 'EE5CD3F7-F7E3-475E-A7B4-67FD5AB65975') ";
		} else {
			$tipe = "AND do.tipe_delivery_order_id = '$tipe' ";
		}

		if ($tipe_layanan == "") {
			$tipe_layanan = "";
		} else {
			$tipe_layanan = "AND do.delivery_order_draft_tipe_layanan = '$tipe_layanan' ";
		}

		if ($kelas_jalan == 'true') {
			$kelas_jalan_berat_muatan = $this->db->query("SELECT kelas_jalan_berat_muatan FROM kendaraan_kelas_jalan a
			LEFT JOIN kelas_jalan b ON a.kelas_jalan_id = b.kelas_jalan_id
			WHERE a.kendaraan_id = '$kendaraan'")->row()->kelas_jalan_berat_muatan;

			$kelas_jalan_berat_muatan = "AND kj.kelas_jalan_berat_muatan >= $kelas_jalan_berat_muatan";
		} else {
			$kelas_jalan_berat_muatan = '';
		}

		// $query = $this->db->query("SELECT DISTINCT
		// 							--do.delivery_order_draft_id,
		// 							--do.delivery_order_draft_kode,
		// 							--FORMAT(do.delivery_order_draft_tgl_buat_do,'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
		// 							--FORMAT(do.delivery_order_draft_tgl_aktual_kirim,'dd-MM-yyyy') AS delivery_order_draft_tgl_aktual_kirim,
		// 							CASE 
		// 								WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
		// 								ELSE do.delivery_order_draft_kirim_nama
		// 							END AS delivery_order_draft_kirim_nama,
		// 							-- REPLACE(CASE 
		// 							-- 	WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
		// 							-- 	ELSE delivery_order_draft_kirim_alamat
		// 							-- END, '\"', '') AS delivery_order_draft_kirim_alamat,
		// 							CASE 
		// 								WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
		// 								ELSE do.delivery_order_draft_kirim_alamat
		// 							END AS delivery_order_draft_kirim_alamat,
		// 							CASE 
		// 								WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_kecamatan
		// 								ELSE delivery_order_draft_kirim_kecamatan
		// 							END AS delivery_order_draft_kirim_kecamatan,
		// 							CASE 
		// 								WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
		// 								ELSE delivery_order_draft_kirim_area
		// 							END AS delivery_order_draft_kirim_area,
		// 							so.sales_order_no_po,
		// 							pc.principle_kode,
		// 							--CASE WHEN do.delivery_order_draft_tipe_pembayaran = '1' THEN 'NON TUNAI' ELSE 'TUNAI' END delivery_order_draft_tipe_pembayaran,
		// 							--do.delivery_order_draft_tipe_layanan,
		// 							--do.tipe_delivery_order_id,
		// 							tipe.tipe_delivery_order_nama,
		// 							--do.delivery_order_draft_status
		// 							SUM(CAST(ISNULL(dodd.sku_weight, 0) AS INT)) as sku_weight,
		// 							SUM(CAST(ISNULL(dodd.sku_volume, 0) AS INT)) as sku_volume,
		// 							xx.composite
		// 							FROM delivery_order_draft do
		// 							LEFT JOIN sales_order so ON do.sales_order_id = so.sales_order_id
		// 							LEFT JOIN tipe_delivery_order tipe
		// 							ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
		// 							LEFT JOIN principle pc ON so.principle_id = pc.principle_id
		// 							LEFT JOIN delivery_order_detail_draft dodd ON do.delivery_order_draft_id = dodd.delivery_order_draft_id
		// 							WHERE FORMAT(do.delivery_order_draft_tgl_rencana_kirim,'yyyy-MM-dd') <= '$tgl'
		// 								AND NOT EXISTS
		// 										(
		// 										SELECT  NULL
		// 										FROM    delivery_order 
		// 										WHERE   do.delivery_order_draft_id = delivery_order.delivery_order_draft_id
		// 										)
		// 								-- AND do.delivery_order_draft_id not in (select delivery_order_draft_id from delivery_order)
		// 								AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
		// 								AND do.delivery_order_draft_status = 'Approved'
		// 								" . $areaWhereIn . "
		// 								" . $tipe . "
		// 								" . $tipe_layanan . "
		// 								GROUP BY 
		// 								do.delivery_order_draft_tipe_layanan,
		// 								do.delivery_order_draft_ambil_nama,
		// 								do.delivery_order_draft_kirim_nama, 
		// 								do.delivery_order_draft_ambil_alamat,
		// 								do.delivery_order_draft_kirim_alamat,
		// 								do.delivery_order_draft_ambil_kecamatan,
		// 								do.delivery_order_draft_kirim_kecamatan,
		// 								do.delivery_order_draft_ambil_area,
		// 								do.delivery_order_draft_kirim_area,
		// 								tipe.tipe_delivery_order_nama,
		// 								so.sales_order_no_po, pc.principle_kode
		// 								ORDER BY delivery_order_draft_kirim_area, delivery_order_draft_kirim_alamat, delivery_order_draft_kirim_nama ASC");

		$query = $this->db->query("SELECT DISTINCT
									--do.delivery_order_draft_id,
									--do.delivery_order_draft_kode,
									--FORMAT(do.delivery_order_draft_tgl_buat_do,'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
									--FORMAT(do.delivery_order_draft_tgl_aktual_kirim,'dd-MM-yyyy') AS delivery_order_draft_tgl_aktual_kirim,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
										ELSE do.delivery_order_draft_kirim_nama
									END AS delivery_order_draft_kirim_nama,
									-- REPLACE(CASE 
									-- 	WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
									-- 	ELSE delivery_order_draft_kirim_alamat
									-- END, '\"', '') AS delivery_order_draft_kirim_alamat,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
										ELSE do.delivery_order_draft_kirim_alamat
									END AS delivery_order_draft_kirim_alamat,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_kecamatan
										ELSE delivery_order_draft_kirim_kecamatan
									END AS delivery_order_draft_kirim_kecamatan,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
										ELSE delivery_order_draft_kirim_area
									END AS delivery_order_draft_kirim_area,
									so.sales_order_no_po,
									pc.principle_kode,
									--CASE WHEN do.delivery_order_draft_tipe_pembayaran = '1' THEN 'NON TUNAI' ELSE 'TUNAI' END delivery_order_draft_tipe_pembayaran,
									--do.delivery_order_draft_tipe_layanan,
									--do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_nama,
									--do.delivery_order_draft_status
									SUM(CAST(ISNULL(dodd.sku_weight, 0) AS INT) * CAST(ISNULL(dodd.sku_qty, 0) AS INT)) as sku_weight,
									SUM(CAST(ISNULL(dodd.sku_volume, 0) AS INT) * CAST(ISNULL(dodd.sku_qty, 0) AS INT)) as sku_volume,
									ISNULL(xx.composite, 0) as composite,
									ISNULL(kj.kelas_jalan_nama, '') AS kelas_jalan_nama
									FROM delivery_order_draft do
									LEFT JOIN sales_order so ON do.sales_order_id = so.sales_order_id
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									LEFT JOIN principle pc ON so.principle_id = pc.principle_id
									LEFT JOIN delivery_order_detail_draft dodd ON do.delivery_order_draft_id = dodd.delivery_order_draft_id
									LEFT JOIN client_pt cpt ON do.client_pt_id = cpt.client_pt_id
									LEFT JOIN kelas_jalan kj ON cpt.kelas_jalan_id = kj.kelas_jalan_id
									LEFT JOIN (
        select distinct (
        select cast(sum(a.sku_qty) as varchar(100)) + ' ' + case 
        when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
        else sku_satuan
        end +  '; ' AS [text()]
        from delivery_order_detail_draft a
        inner join sku b on a.sku_id = b.sku_id
        inner join delivery_order_draft c on a.delivery_order_draft_id = c.delivery_order_draft_id and c.client_pt_id = xx.client_pt_id
        inner join tipe_delivery_order d on c.tipe_delivery_order_id = d.tipe_delivery_order_id
        where c.delivery_order_draft_id IN (select delivery_order_draft_id from delivery_order_draft do where FORMAT(do.delivery_order_draft_tgl_rencana_kirim,'yyyy-MM-dd') <= '$tgl'
            AND NOT EXISTS
                    (
                    SELECT  NULL
                    FROM    delivery_order 
                    WHERE   do.delivery_order_draft_id = delivery_order.delivery_order_draft_id
                    )
            -- AND do.delivery_order_draft_id not in (select delivery_order_draft_id from delivery_order)
            AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
            AND do.delivery_order_draft_status = 'Approved'
           " . $areaWhereIn . "
										" . $tipe . "
										" . $tipe_layanan . ")
        and d.tipe_delivery_order_tipe in ('OUT')
        group by case 
        when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
        else sku_satuan
        end
        for xml path (''), type
        ).value('text()[1]','NVARCHAR(MAX)') AS composite
        ,
        delivery_order_draft_kirim_nama,
        delivery_order_draft_kirim_alamat
        from delivery_order_draft xx
        where xx.delivery_order_draft_id in (select delivery_order_draft_id from delivery_order_draft do where FORMAT(do.delivery_order_draft_tgl_rencana_kirim,'yyyy-MM-dd') <= '$tgl'
            AND NOT EXISTS
                    (
                    SELECT  NULL
                    FROM    delivery_order 
                    WHERE   do.delivery_order_draft_id = delivery_order.delivery_order_draft_id
                    )
            -- AND do.delivery_order_draft_id not in (select delivery_order_draft_id from delivery_order)
            AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
            AND do.delivery_order_draft_status = 'Approved'
           " . $areaWhereIn . "
										" . $tipe . "
										" . $tipe_layanan . ")
        ) as xx on do.delivery_order_draft_kirim_nama = xx.delivery_order_draft_kirim_nama and do.delivery_order_draft_kirim_alamat = xx.delivery_order_draft_kirim_alamat
									WHERE FORMAT(do.delivery_order_draft_tgl_rencana_kirim,'yyyy-MM-dd') <= '$tgl'
										AND NOT EXISTS
												(
												SELECT  NULL
												FROM    delivery_order 
												WHERE   do.delivery_order_draft_id = delivery_order.delivery_order_draft_id
												)
										-- AND do.delivery_order_draft_id not in (select delivery_order_draft_id from delivery_order)
										AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
										AND do.delivery_order_draft_status = 'Approved'
										" . $areaWhereIn . "
										" . $tipe . "
										" . $tipe_layanan . "
										" . $kelas_jalan_berat_muatan . "
										GROUP BY 
										do.delivery_order_draft_tipe_layanan,
										do.delivery_order_draft_ambil_nama,
										do.delivery_order_draft_kirim_nama, 
										do.delivery_order_draft_ambil_alamat,
										do.delivery_order_draft_kirim_alamat,
										do.delivery_order_draft_ambil_kecamatan,
										do.delivery_order_draft_kirim_kecamatan,
										do.delivery_order_draft_ambil_area,
										do.delivery_order_draft_kirim_area,
										tipe.tipe_delivery_order_nama,
										so.sales_order_no_po, pc.principle_kode,
										xx.composite,
										kj.kelas_jalan_nama
										ORDER BY delivery_order_draft_kirim_area, delivery_order_draft_kirim_alamat, delivery_order_draft_kirim_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDODraftByAlamat($tgl, $area, $tipe, $tipe_layanan, $alamat, $nama)
	{
		$alamat_do = implode(",", $alamat);
		$nama_do = implode(",", $nama);

		$areaArray = [];

		for ($i = 0; $i < count($area); $i++) {
			$areaArray[$i] = "'" . $area[$i] . "'";
		}

		if ($area == "") {
			$areaWhereIn = "";
		} else {
			$areaWhereIn = "AND CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
										ELSE delivery_order_draft_kirim_area
									END IN (" . implode(',', $areaArray) . ")";
		}

		if ($tipe == "") {
			$tipe = "";
		} else if ($tipe == "BB403FA9-007F-42CD-87F0-92CEF7B40AAD") {
			$tipe = "AND do.tipe_delivery_order_id IN ('0E626A53-82FC-4EA6-A4A2-1265279D6E1C','ADF55030-9802-4C27-9658-F9D37AA01F95', 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD', 'EE5CD3F7-F7E3-475E-A7B4-67FD5AB65975') ";
		} else {
			$tipe = "AND do.tipe_delivery_order_id = '$tipe' ";
		}

		if ($tipe_layanan == "") {
			$tipe_layanan = "";
		} else {
			$tipe_layanan = "AND do.delivery_order_draft_tipe_layanan = '$tipe_layanan' ";
		}

		$query = $this->db->query("SELECT 
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									do.client_pt_id,
									FORMAT(do.delivery_order_draft_tgl_buat_do,'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
									FORMAT(do.delivery_order_draft_tgl_aktual_kirim,'dd-MM-yyyy') AS delivery_order_draft_tgl_aktual_kirim,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
										ELSE delivery_order_draft_kirim_nama
									END AS delivery_order_draft_kirim_nama,
									-- REPLACE(CASE 
									-- 	WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
									-- 	ELSE delivery_order_draft_kirim_alamat
									-- END, '\"', '') AS delivery_order_draft_kirim_alamat,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
										ELSE delivery_order_draft_kirim_alamat
									END AS delivery_order_draft_kirim_alamat,
									CASE WHEN do.delivery_order_draft_tipe_pembayaran = '1' THEN 'NON TUNAI' ELSE 'TUNAI' END delivery_order_draft_tipe_pembayaran,
									do.delivery_order_draft_tipe_layanan,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias,
									do.delivery_order_draft_status,
									(
										SELECT  delivery_order_reff_id
										FROM    delivery_order 
										WHERE   do.delivery_order_draft_id = delivery_order.delivery_order_draft_id
									) as kirim_ulang
									FROM delivery_order_draft do
									LEFT JOIN client_pt ON client_pt.client_pt_id = do.client_pt_id 
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									WHERE FORMAT(do.delivery_order_draft_tgl_rencana_kirim,'yyyy-MM-dd') <= '$tgl'
										AND NOT EXISTS
												(
												SELECT  NULL
												FROM    delivery_order 
												WHERE   do.delivery_order_draft_id = delivery_order.delivery_order_draft_id
												)
										-- AND do.delivery_order_draft_id not in (select delivery_order_draft_id from delivery_order)
										AND do.depo_id = '" . $this->session->userdata('depo_id') . "'
										AND do.delivery_order_draft_status = 'Approved'
										AND delivery_order_draft_kirim_alamat IN (" . $alamat_do . ")
										AND delivery_order_draft_kirim_nama IN (" . $nama_do . ")
										" . $areaWhereIn . "
										" . $tipe . "
										" . $tipe_layanan . "
										ORDER BY delivery_order_draft_kirim_nama ASC");
		// AND REPLACE(delivery_order_draft_kirim_alamat, '\"', '') IN (" . $alamat_do . ")

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetSelectedDODraft($do_id)
	{
		$do_id = implode(",", $do_id);

		$query = $this->db->query("SELECT 
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									FORMAT(do.delivery_order_draft_tgl_buat_do,'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
									FORMAT(do.delivery_order_draft_tgl_aktual_kirim,'dd-MM-yyyy') AS delivery_order_draft_tgl_aktual_kirim,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
										ELSE delivery_order_draft_kirim_nama
									END AS delivery_order_draft_kirim_nama,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_telp
										ELSE delivery_order_draft_kirim_telp
									END AS delivery_order_draft_kirim_telp,
									-- REPLACE(CASE 
									-- 	WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
									-- 	ELSE delivery_order_draft_kirim_alamat
									-- END, '\"', '') AS delivery_order_draft_kirim_alamat,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
										ELSE delivery_order_draft_kirim_alamat
									END AS delivery_order_draft_kirim_alamat,
									CASE WHEN do.delivery_order_draft_tipe_pembayaran = '1' THEN 'NON TUNAI' ELSE 'TUNAI' END delivery_order_draft_tipe_pembayaran,
									do.delivery_order_draft_tipe_layanan,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias,
									do.delivery_order_draft_status
									FROM delivery_order_draft do
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									WHERE do.delivery_order_draft_id IN (" . $do_id . ")
									ORDER BY do.delivery_order_draft_kirim_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetIDDOByBatch($temp_do, $alamat_do, $nama_do)
	{
		$alamat_do = implode(",", $alamat_do);
		$nama_do = implode(",", $nama_do);

		$alamat = "AND do.delivery_order_draft_kirim_alamat IN (" . $alamat_do . ")";
		$alamat2 = "AND c.delivery_order_draft_kirim_alamat IN (" . $alamat_do . ")";
		$alamat3 = "AND xx.delivery_order_draft_kirim_alamat IN (" . $alamat_do . ")";
		$nama = "AND do.delivery_order_draft_kirim_nama IN (" . $nama_do . ")";
		$nama2 = "AND c.delivery_order_draft_kirim_nama IN (" . $nama_do . ")";
		$nama3 = "AND xx.delivery_order_draft_kirim_nama IN (" . $nama_do . ")";

		$query = $this->db->query("SELECT dot.delivery_order_draft_id, 	
		dot.delivery_order_temp_id,
		dot.delivery_order_batch_id,
		dod.delivery_order_draft_kode,
		FORMAT(dod.delivery_order_draft_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
		FORMAT(dod.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') AS tgl_kirim,
		CASE 
			WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
			ELSE dod.delivery_order_draft_kirim_nama
			END AS delivery_order_draft_kirim_nama,
		CASE 
			WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_telp
			 ELSE delivery_order_draft_kirim_telp
			END AS delivery_order_draft_kirim_telp,
		-- 	REPLACE(CASE 
		-- WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
		-- ELSE delivery_order_draft_kirim_alamat
		-- END, '\"', '') AS delivery_order_draft_kirim_alamat,
			CASE 
		WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
		ELSE dod.delivery_order_draft_kirim_alamat
		END AS delivery_order_draft_kirim_alamat,
		client_pt.client_pt_latitude,
		client_pt.client_pt_longitude,
		tod.tipe_delivery_order_alias,
		so.sales_order_no_po,
		pc.principle_kode,
		do.delivery_order_reff_id as kirim_ulang,
		ISNULL(xx.composite, 0) as composite
		from delivery_order_temp dot
		left join delivery_order_draft dod ON dod.delivery_order_draft_id = dot.delivery_order_draft_id
		left join sales_order so ON dod.sales_order_id = so.sales_order_id
		left join principle pc ON so.principle_id = pc.principle_id
		left join delivery_order do ON dod.delivery_order_draft_id = do.delivery_order_draft_id
		left join tipe_delivery_order tod ON dod.tipe_delivery_order_id = tod.tipe_delivery_order_id
		left join client_pt ON client_pt.client_pt_id = dod.client_pt_id 
		LEFT JOIN (
  					select distinct (
					select cast(sum(a.sku_qty) as varchar(100)) + ' ' + case 
								when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
								else sku_satuan
						   end +  '; ' AS [text()]
					  from delivery_order_detail_draft a
				inner join sku b on a.sku_id = b.sku_id
				inner join delivery_order_draft c on a.delivery_order_draft_id = c.delivery_order_draft_id and c.client_pt_id = xx.client_pt_id
				inner join tipe_delivery_order d on c.tipe_delivery_order_id = d.tipe_delivery_order_id
					 where c.delivery_order_draft_id IN (select delivery_order_draft_id from delivery_order_temp dot2 where dot2.delivery_order_temp_id = $temp_do $alamat2 $nama2)
					 and c.delivery_order_draft_id = xx.delivery_order_draft_id and d.tipe_delivery_order_tipe in ('OUT')
					group by case 
								when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
								else sku_satuan
						   end
					for xml path (''), type
		   ).value('text()[1]','NVARCHAR(MAX)') AS composite
		   ,
		   delivery_order_draft_kirim_nama,
		   delivery_order_draft_kirim_alamat,
		   delivery_order_draft_id
		from delivery_order_draft xx
		where xx.delivery_order_draft_id in (select delivery_order_draft_id from delivery_order_temp dot2 where dot2.delivery_order_temp_id = $temp_do $alamat3 $nama3)
		) as xx on dod.delivery_order_draft_kirim_nama = xx.delivery_order_draft_kirim_nama and dod.delivery_order_draft_kirim_alamat = xx.delivery_order_draft_kirim_alamat and dod.delivery_order_draft_id = xx.delivery_order_draft_id
		-- where REPLACE(dod.delivery_order_draft_kirim_alamat, '\"', '') IN (" . $alamat_do . ")
		where dod.delivery_order_draft_kirim_alamat IN (" . $alamat_do . ")
		AND dod.delivery_order_draft_kirim_nama IN (" . $nama_do . ")
		AND dot.delivery_order_temp_id = " . $temp_do . "");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDetailIDDOByBatch($batch_do, $alamat_do)
	{

		$query = $this->db->query("SELECT 
    do.delivery_order_batch_id,
    do.delivery_order_kode,
    do.delivery_order_draft_kode,
    so.sales_order_no_po,
	pc.principle_kode,
    tod.tipe_delivery_order_alias,
    do.delivery_order_reff_id as kirim_ulang,
    FORMAT(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
    FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') AS tgl_kirim,
    CASE
    WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
    ELSE do.delivery_order_kirim_nama
END AS delivery_order_kirim_nama,
CASE
    WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
    ELSE do.delivery_order_kirim_alamat
END AS delivery_order_kirim_alamat,
CASE
    WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_telp
    ELSE do.delivery_order_kirim_telp
END AS delivery_order_kirim_telp,
ISNULL(xx.composite, 0) as composite
    FROM delivery_order do
    left join sales_order so ON do.sales_order_id = so.sales_order_id
	left join principle pc ON so.principle_id = pc.principle_id
    left join tipe_delivery_order tod ON do.tipe_delivery_order_id = tod.tipe_delivery_order_id
	LEFT JOIN (
  					select distinct (
					select cast(sum(a.sku_qty) as varchar(100)) + ' ' + case 
								when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
								else sku_satuan
						   end +  '; ' AS [text()]
					  from delivery_order_detail a
				inner join sku b on a.sku_id = b.sku_id
				inner join delivery_order c on a.delivery_order_id = c.delivery_order_id and c.client_pt_id = xx.client_pt_id
				inner join tipe_delivery_order d on c.tipe_delivery_order_id = d.tipe_delivery_order_id
					 where c.delivery_order_id IN (select delivery_order_id from delivery_order do2 where do2.delivery_order_batch_id = $batch_do AND do2.delivery_order_kirim_alamat = $alamat_do)
					 and c.delivery_order_id = xx.delivery_order_id and d.tipe_delivery_order_tipe in ('OUT')
					group by case 
								when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
								else sku_satuan
						   end
					for xml path (''), type
		   ).value('text()[1]','NVARCHAR(MAX)') AS composite
		   ,
		   delivery_order_kirim_nama,
		   delivery_order_kirim_alamat,
		   delivery_order_id
		from delivery_order xx
		where xx.delivery_order_id in (select delivery_order_id from delivery_order do2 where do2.delivery_order_batch_id = $batch_do AND do2.delivery_order_kirim_alamat = $alamat_do)
		) as xx on do.delivery_order_kirim_nama = xx.delivery_order_kirim_nama and do.delivery_order_kirim_alamat = xx.delivery_order_kirim_alamat and do.delivery_order_id = xx.delivery_order_id
WHERE do.delivery_order_batch_id = " . $batch_do . " AND do.delivery_order_kirim_alamat = " . $alamat_do . "
ORDER BY do.delivery_order_no_urut_rute ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDONotExists($temp_do, $delivery_order_batch_id)
	{

		$query = $this->db->query("SELECT delivery_order_id, delivery_order_batch_id, delivery_order_draft_id from delivery_order do where delivery_order_batch_id = '" . $delivery_order_batch_id . "' 
		AND NOT EXISTS (SELECT * FROM delivery_order_temp WHERE do.delivery_order_draft_id = delivery_order_draft_id 
		AND delivery_order_temp_id = '" . $temp_do . "')");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetIDDOByBatchByNoUrut($temp_do, $alamat_do, $case, $nama_do)
	{
		$alamat_do = implode(",", $alamat_do);
		$nama_do = implode(",", $nama_do);

		$query = $this->db->query("SELECT dot.delivery_order_draft_id, 	
		dot.delivery_order_temp_id,
		dot.delivery_order_batch_id,
		dod.delivery_order_draft_kode,
		dod.delivery_order_draft_tipe_pembayaran,
		dod.delivery_order_draft_nominal_tunai,
		FORMAT(dod.delivery_order_draft_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
		CASE 
			WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
			ELSE delivery_order_draft_kirim_nama
			END AS delivery_order_draft_kirim_nama,
		CASE 
			WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_telp
			 ELSE delivery_order_draft_kirim_telp
			END AS delivery_order_draft_kirim_telp,
		-- 	REPLACE(CASE 
		-- WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
		-- ELSE delivery_order_draft_kirim_alamat
		-- END, '\"', '') AS delivery_order_draft_kirim_alamat,
			CASE 
		WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
		ELSE delivery_order_draft_kirim_alamat
		END AS delivery_order_draft_kirim_alamat,
		CASE
		" . $case . "
		END AS no_urut,
		client_pt.client_pt_latitude,
		client_pt.client_pt_longitude
		from delivery_order_temp dot
		left join delivery_order_draft dod ON dod.delivery_order_draft_id = dot.delivery_order_draft_id
		left join client_pt ON client_pt.client_pt_id = dod.client_pt_id 
		where dod.delivery_order_draft_kirim_alamat IN (" . $alamat_do . ")
		AND dod.delivery_order_draft_kirim_nama IN (" . $nama_do . ")
		AND dot.delivery_order_temp_id = " . $temp_do . "");
		// where REPLACE(dod.delivery_order_draft_kirim_alamat, '\"', '') IN (" . $alamat_do . ")

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetSelectedDODraftSKU($do_id)
	{
		$do_id = implode(",", $do_id);

		$query = $this->db->query("SELECT
		do.sku_id,
		sku.sku_kode,
		sku.sku_konversi_group,
		sku.sku_nama_produk,
		sku.sku_kemasan,
		sku.sku_satuan,
		CASE WHEN do.sku_request_expdate = '1' THEN 'YA' ELSE 'TIDAK' END AS sku_request_expdate,
		ISNULL(do.sku_filter_expdate,'') AS sku_filter_expdate,
		ISNULL(do.sku_filter_expdatebulan,'') AS sku_filter_expdatebulan,
		ISNULL(do.tipe_stock_nama,'') AS tipe_stock_nama,
		SUM(do.sku_qty) AS sku_qty,
		sku.sku_konversi_faktor,
		'0' AS qty_available,
		principle.principle_kode,
		SUM(do.sku_qty*sku.sku_konversi_faktor) as total_konversi_pcs,
		(select top 1 b.sku_satuan from sku b where b.sku_konversi_group = sku.sku_konversi_group order by b.sku_konversi_level desc) as group_carton,
		(select top 1 b.sku_konversi_faktor from sku b where b.sku_konversi_group = sku.sku_konversi_group order by b.sku_konversi_level desc) as sku_konversi_faktor_carton
	FROM delivery_order_detail_draft do
	LEFT JOIN sku
	ON do.sku_id = sku.sku_id
	LEFT JOIN principle ON principle.principle_id = sku.principle_id
	WHERE do.delivery_order_draft_id IN (" . $do_id . ")
	GROUP BY do.sku_id,
		sku.sku_kode,
		sku.sku_nama_produk,
		sku.sku_kemasan,
		sku.sku_satuan,
		do.sku_request_expdate,
		do.sku_filter_expdate,
		do.sku_filter_expdatebulan,
		do.tipe_stock_nama,
		sku.sku_konversi_faktor,
		principle.principle_kode,
		sku.sku_konversi_group
	ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetSelectedDODraftSKU2($do_id)
	{
		$do_id = implode(",", $do_id);

		// $query = $this->db->query("SELECT
		// LEFT((SELECT
		//   CAST(SUM(a.sku_qty) AS varchar(100)) + ' ' + CASE
		// 	WHEN PATINDEX('%[0-9]%', sku_satuan) > 0 THEN LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
		// 	ELSE sku_satuan
		//   END + '; ' AS [text()]
		// FROM delivery_order_detail_draft a
		// INNER JOIN sku b
		//   ON a.sku_id = b.sku_id
		// INNER JOIN delivery_order_draft c
		//   ON a.delivery_order_draft_id = c.delivery_order_draft_id
		// INNER JOIN tipe_delivery_order d
		//   ON c.tipe_delivery_order_id = d.tipe_delivery_order_id
		// WHERE c.delivery_order_draft_id IN (" . $do_id . ")
		// AND d.tipe_delivery_order_tipe IN ('OUT')
		// GROUP BY CASE
		// 		   WHEN PATINDEX('%[0-9]%', sku_satuan) > 0 THEN LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
		// 		   ELSE sku_satuan
		// 		 END,
		// 		 sku_konversi_level
		// ORDER BY b.sku_konversi_level DESC
		// FOR xml PATH (''), TYPE)
		// .value('text()[1]', 'NVARCHAR(MAX)'),
		// LEN((SELECT
		//   CAST(SUM(a.sku_qty) AS varchar(100)) + ' ' + CASE
		// 	WHEN PATINDEX('%[0-9]%', sku_satuan) > 0 THEN LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
		// 	ELSE sku_satuan
		//   END + '; ' AS [text()]
		// FROM delivery_order_detail_draft a
		// INNER JOIN sku b
		//   ON a.sku_id = b.sku_id
		// INNER JOIN delivery_order_draft c
		//   ON a.delivery_order_draft_id = c.delivery_order_draft_id
		// INNER JOIN tipe_delivery_order d
		//   ON c.tipe_delivery_order_id = d.tipe_delivery_order_id
		// WHERE c.delivery_order_draft_id IN (" . $do_id . ")
		// AND d.tipe_delivery_order_tipe IN ('OUT')
		// GROUP BY CASE
		// 		   WHEN PATINDEX('%[0-9]%', sku_satuan) > 0 THEN LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
		// 		   ELSE sku_satuan
		// 		 END,
		// 		 sku_konversi_level
		// ORDER BY b.sku_konversi_level DESC
		// FOR xml PATH (''), TYPE)
		// .value('text()[1]', 'NVARCHAR(MAX)')) - 1) [composite]");

		$query = $this->db->query("select (
            select cast(sum(a.sku_qty) as varchar(100)) + ' ' + case 
                        when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
                        else sku_satuan
                   end +  '; ' AS [text()]
              from delivery_order_detail_draft a
        inner join sku b on a.sku_id = b.sku_id
        inner join delivery_order_draft c on a.delivery_order_draft_id = c.delivery_order_draft_id
        inner join tipe_delivery_order d on c.tipe_delivery_order_id = d.tipe_delivery_order_id
             where c.delivery_order_draft_id IN (" . $do_id . ")
               and d.tipe_delivery_order_tipe in ('OUT')
            group by case 
                        when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
                        else sku_satuan
                   end
            for xml path (''), type
   ).value('text()[1]','NVARCHAR(MAX)') AS composite");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function GetTotalComposite($do_id)
	{
		$do_id = implode(",", $do_id);

		// $query = $this->db->query("SELECT
		// LEFT((SELECT
		//   CAST(SUM(a.sku_qty) AS varchar(100)) + ' ' + CASE
		// 	WHEN PATINDEX('%[0-9]%', sku_satuan) > 0 THEN LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
		// 	ELSE sku_satuan
		//   END + '; ' AS [text()]
		// FROM delivery_order_detail_draft a
		// INNER JOIN sku b
		//   ON a.sku_id = b.sku_id
		// INNER JOIN delivery_order_draft c
		//   ON a.delivery_order_draft_id = c.delivery_order_draft_id
		// INNER JOIN tipe_delivery_order d
		//   ON c.tipe_delivery_order_id = d.tipe_delivery_order_id
		// WHERE c.delivery_order_draft_id IN (" . $do_id . ")
		// AND d.tipe_delivery_order_tipe IN ('OUT')
		// GROUP BY CASE
		// 		   WHEN PATINDEX('%[0-9]%', sku_satuan) > 0 THEN LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
		// 		   ELSE sku_satuan
		// 		 END,
		// 		 sku_konversi_level
		// ORDER BY b.sku_konversi_level DESC
		// FOR xml PATH (''), TYPE)
		// .value('text()[1]', 'NVARCHAR(MAX)'),
		// LEN((SELECT
		//   CAST(SUM(a.sku_qty) AS varchar(100)) + ' ' + CASE
		// 	WHEN PATINDEX('%[0-9]%', sku_satuan) > 0 THEN LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
		// 	ELSE sku_satuan
		//   END + '; ' AS [text()]
		// FROM delivery_order_detail_draft a
		// INNER JOIN sku b
		//   ON a.sku_id = b.sku_id
		// INNER JOIN delivery_order_draft c
		//   ON a.delivery_order_draft_id = c.delivery_order_draft_id
		// INNER JOIN tipe_delivery_order d
		//   ON c.tipe_delivery_order_id = d.tipe_delivery_order_id
		// WHERE c.delivery_order_draft_id IN (" . $do_id . ")
		// AND d.tipe_delivery_order_tipe IN ('OUT')
		// GROUP BY CASE
		// 		   WHEN PATINDEX('%[0-9]%', sku_satuan) > 0 THEN LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
		// 		   ELSE sku_satuan
		// 		 END,
		// 		 sku_konversi_level
		// ORDER BY b.sku_konversi_level DESC
		// FOR xml PATH (''), TYPE)
		// .value('text()[1]', 'NVARCHAR(MAX)')) - 1) [composite]");

		$query = $this->db->query("select (
            select cast(sum(a.sku_qty) as varchar(100)) + ' ' + case 
                        when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
                        else sku_satuan
                   end +  '; ' AS [text()]
              from delivery_order_detail a
        inner join sku b on a.sku_id = b.sku_id
        inner join delivery_order c on a.delivery_order_id = c.delivery_order_id
        inner join tipe_delivery_order d on c.tipe_delivery_order_id = d.tipe_delivery_order_id
             where c.delivery_order_id IN (" . $do_id . ")
               and d.tipe_delivery_order_tipe in ('OUT')
            group by case 
                        when PATINDEX('%[0-9]%', sku_satuan) > 0 then LEFT(sku_satuan, PATINDEX('%[0-9]%', sku_satuan) - 1)
                        else sku_satuan
                   end
            for xml path (''), type
   ).value('text()[1]','NVARCHAR(MAX)') AS composite");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function insertSKUKonversiTemp($sku_konversi_temp_id, $sku_id, $sku_qty, $sku_qty_composite)
	{
		$this->db->set("sku_konversi_temp_id", $sku_konversi_temp_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_qty", $sku_qty);
		$this->db->set("sku_qty_composite", $sku_qty_composite);

		$this->db->insert("sku_konversi_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryInsert = 1;
		} else {
			$queryInsert = 0;
		}

		return $queryInsert;
	}

	public function insert_delivery_order_payment_driver($do_id, $tipe_pembayaran_id, $delivery_order_jumlah_bayar)
	{
		$this->db->set("delivery_order_payment_driver_id", "NEWID()", FALSE);
		$this->db->set("delivery_order_id", $do_id);
		$this->db->set("tipe_pembayaran_id", $tipe_pembayaran_id);
		$this->db->set("delivery_order_jumlah_bayar", $tipe_pembayaran_id == 1 ? 0 : $delivery_order_jumlah_bayar);
		$this->db->set("delivery_order_payment_driver_tgl", "GETDATE()", FALSE);
		$this->db->set("delivery_order_payment_driver_who", $this->session->userdata('pengguna_username'));

		$this->db->insert("delivery_order_payment_driver");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryInsert = 1;
		} else {
			$queryInsert = 0;
		}

		return $queryInsert;
	}

	public function delete_delivery_order_payment_driver($do_id)
	{
		$this->db->where("delivery_order_id", $do_id);
		$this->db->delete("delivery_order_payment_driver");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryInsert = 1;
		} else {
			$queryInsert = 0;
		}

		return $queryInsert;
	}

	public function execProsesKonversiComposite($sku_konversi_temp_id)
	{
		$query = $this->db->query("exec proses_konversi_composite '$sku_konversi_temp_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetSelectedDODraftSKUDetail($do_id, $sku_id)
	{
		$do_id = implode(",", $do_id);

		$query = $this->db->query("SELECT 
									do.delivery_order_draft_id,
									do.delivery_order_draft_kode,
									FORMAT(do.delivery_order_draft_tgl_buat_do,'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
									FORMAT(do.delivery_order_draft_tgl_aktual_kirim,'dd-MM-yyyy') AS delivery_order_draft_tgl_aktual_kirim,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
										ELSE delivery_order_draft_kirim_nama
									END AS delivery_order_draft_kirim_nama,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_telp
										ELSE delivery_order_draft_kirim_telp
									END AS delivery_order_draft_kirim_telp,
									-- REPLACE(CASE 
									-- 	WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
									-- 	ELSE delivery_order_draft_kirim_alamat
									-- END, '\"', '') AS delivery_order_draft_kirim_alamat,
									CASE 
										WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
										ELSE delivery_order_draft_kirim_alamat
									END AS delivery_order_draft_kirim_alamat,
									CASE WHEN do.delivery_order_draft_tipe_pembayaran = '1' THEN 'NON TUNAI' ELSE 'TUNAI' END delivery_order_draft_tipe_pembayaran,
									do.delivery_order_draft_tipe_layanan,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias,
									do.delivery_order_draft_status,
									do_detail.sku_qty
									FROM delivery_order_draft do
									LEFT JOIN delivery_order_detail_draft do_detail
									ON do.delivery_order_draft_id = do_detail.delivery_order_draft_id
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									WHERE do.delivery_order_draft_id IN (" . $do_id . ") AND do_detail.sku_id = '$sku_id'
									GROUP BY do.delivery_order_draft_id,
											do.delivery_order_draft_kode,
											FORMAT(do.delivery_order_draft_tgl_buat_do,'dd-MM-yyyy'),
											FORMAT(do.delivery_order_draft_tgl_aktual_kirim,'dd-MM-yyyy'),
											CASE 
												WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_nama
												ELSE delivery_order_draft_kirim_nama
											END,
											CASE 
												WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_telp
												ELSE delivery_order_draft_kirim_telp
											END,
											CASE 
												WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_alamat
												ELSE delivery_order_draft_kirim_alamat
											END,
											CASE WHEN do.delivery_order_draft_tipe_pembayaran = '1' THEN 'NON TUNAI' ELSE 'TUNAI' END,
											do.delivery_order_draft_tipe_layanan,
											do.tipe_delivery_order_id,
											tipe.tipe_delivery_order_alias,
											do.delivery_order_draft_status,
											do_detail.sku_qty
									ORDER BY do.delivery_order_draft_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}


	public function GetSelectedDOSKUDetail($fdjr_id, $sku_id)
	{
		$query = $this->db->query("SELECT 
									do.delivery_order_id,
									do.delivery_order_kode,
									do.delivery_order_no_urut_rute,
									FORMAT(do.delivery_order_tgl_buat_do,'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(do.delivery_order_tgl_aktual_kirim,'dd-MM-yyyy') AS delivery_order_tgl_aktual_kirim,
									CASE 
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_nama
										ELSE delivery_order_kirim_nama
									END AS delivery_order_kirim_nama,
									CASE 
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_telp
										ELSE delivery_order_kirim_telp
									END AS delivery_order_kirim_telp,
									CASE 
										WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_alamat
										ELSE delivery_order_kirim_alamat
									END AS delivery_order_kirim_alamat,
									CASE WHEN do.delivery_order_tipe_pembayaran = '1' THEN 'NON TUNAI' ELSE 'TUNAI' END delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_alias,
									do.delivery_order_status,
									do_detail.sku_qty
									FROM delivery_order do
									LEFT JOIN delivery_order_detail do_detail
									ON do.delivery_order_id = do_detail.delivery_order_id
									LEFT JOIN tipe_delivery_order tipe
									ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
									WHERE do.delivery_order_batch_id = '$fdjr_id' AND do_detail.sku_id = '$sku_id'
									GROUP BY do.delivery_order_id,
											do.delivery_order_kode,
											do.delivery_order_no_urut_rute,
											FORMAT(do.delivery_order_tgl_buat_do,'dd-MM-yyyy'),
											FORMAT(do.delivery_order_tgl_aktual_kirim,'dd-MM-yyyy'),
											CASE 
												WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_nama
												ELSE delivery_order_kirim_nama
											END,
											CASE 
												WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_telp
												ELSE delivery_order_kirim_telp
											END,
											CASE 
												WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_alamat
												ELSE delivery_order_kirim_alamat
											END,
											CASE WHEN do.delivery_order_tipe_pembayaran = '1' THEN 'NON TUNAI' ELSE 'TUNAI' END,
											do.delivery_order_tipe_layanan,
											do.tipe_delivery_order_id,
											tipe.tipe_delivery_order_alias,
											do.delivery_order_status,
											do_detail.sku_qty
									ORDER BY do.delivery_order_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetKapasitasTerpakai($do_id)
	{
		$do_id = implode(",", $do_id);

		$query = $this->db->query("SELECT
									SUM(CAST(ISNULL(do.sku_weight, 0) AS INT) * CAST(ISNULL(do.sku_qty, 0) AS INT)) as sku_weight,
									SUM(CAST(ISNULL(do.sku_volume, 0) AS INT) * CAST(ISNULL(do.sku_qty, 0) AS INT)) as sku_volume
									FROM delivery_order_detail_draft do
									WHERE do.delivery_order_draft_id IN (" . $do_id . ")");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function GetKapasitasTerpakaiByDriver($kendaraan_id)
	{

		$query = $this->db->query("SELECT
										SUM(CAST(ISNULL(kendaraan_berat_gr_terpakai, 0) AS INT)) AS beratTerpakai,
										SUM(CAST(ISNULL(kendaraan_volume_cm3_terpakai, 0) AS INT)) AS volumeTerpakai
									FROM delivery_order_batch
									WHERE delivery_order_batch_status = 'Draft'
									AND kendaraan_id = '$kendaraan_id'
									AND depo_id = '" . $this->session->userdata('depo_id') . "'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
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
			$query = $query->result_array();
		}

		return $query;
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	//Insert data into dbo.delivery_order_batch_log as history when there are change assigment
	public function insertNewData($delivery_order_batch_log_id, $delivery_order_batch_id, $depo_id, $delivery_order_batch_create_who, $kendaraan_id_awal, $karyawan_id_awal, $kendaraan_id_pengganti, $karyawan_id_pengganti, $reason)
	{
		$this->db->set("delivery_order_batch_log_id", $delivery_order_batch_log_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("driver_awal", $karyawan_id_awal);
		$this->db->set("driver_pengganti", $karyawan_id_pengganti);
		$this->db->set("armada_awal", $kendaraan_id_awal);
		$this->db->set("armada_pengganti", $kendaraan_id_pengganti);
		$this->db->set("reason", $reason);
		$this->db->set("tanggal_jam", "GETDATE()", false);
		$this->db->set("karyawan_id_update", $delivery_order_batch_create_who);

		$this->db->insert("delivery_order_batch_log");
		// $queryInsert = $this->db->insert("delivery_order_batch_log");

		// var_dump($queryInsert);
		// die;

		//$queryInsert = $this->db->last_query();
		//var_dump($queryInsert);
		//die;

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryInsert = 1;
		} else {
			$queryInsert = 0;
		}

		return $queryInsert;
	}

	//When data have been inserted into dbo.delivery_order_batch_log, then on dbo.delivery_order_batch will updated with new data
	public function updateNewData($kendaraan_id_pengganti, $karyawan_id_pengganti, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max)
	{
		$this->db->set("kendaraan_id", $kendaraan_id_pengganti);
		$this->db->set("karyawan_id", $karyawan_id_pengganti);
		$this->db->set("kendaraan_volume_cm3_max", $kendaraan_volume_cm3_max);
		$this->db->set("kendaraan_berat_gr_max", $kendaraan_berat_gr_max);

		$queryUpdate = $this->db->update("delivery_order_batch");

		// $affectedrows = $this->db->affected_rows();

		// if ($affectedrows > 0) {
		// 	$queryUpdate = 1;
		// } else {
		// 	$queryUpdate = 0;
		// }

		return $queryUpdate;
	}

	public function insert_delivery_order_batch($delivery_order_batch_id, $delivery_order_batch_kode, $unit_mandiri_id, $client_wms_id, $depo_id, $depo_detail_id, $delivery_order_batch_ritasi, $delivery_order_batch_tanggal, $delivery_order_batch_tanggal_kirim, $tipe_pengiriman_id, $delivery_order_batch_tipe_layanan_id, $delivery_order_batch_tipe_layanan_no, $delivery_order_batch_tipe_layanan_nama, $tipe_delivery_order_id, $delivery_order_batch_is_need_packing, $delivery_order_batch_create_who, $delivery_order_batch_create_tgl, $delivery_order_batch_status, $kendaraan_id, $karyawan_id, $tipe_ekspedisi_id, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max, $kendaraan_volume_cm3_terpakai, $kendaraan_berat_gr_terpakai, $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa, $kendaraan_km_awal, $kendaraan_km_akhir, $kendaraan_km_terpakai, $picking_list_id, $picking_order_id, $serah_terima_kirim_id, $dobhID)
	{
		// $tgl = $tgl . " " . date('H:i:s');
		$unit_mandiri_id = $unit_mandiri_id == "" ? null : $unit_mandiri_id;
		$client_wms_id = $client_wms_id == "" ? null : $client_wms_id;
		$depo_id = $depo_id == "" ? null : $depo_id;
		$depo_detail_id = $depo_detail_id == "" ? null : $depo_detail_id;
		$delivery_order_batch_ritasi = $delivery_order_batch_ritasi == "" ? null : $delivery_order_batch_ritasi;
		$delivery_order_batch_tanggal = $delivery_order_batch_tanggal == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_batch_tanggal)));
		$delivery_order_batch_tanggal_kirim = $delivery_order_batch_tanggal_kirim == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_batch_tanggal_kirim)));
		$tipe_pengiriman_id = $tipe_pengiriman_id == "" ? null : $tipe_pengiriman_id;
		$delivery_order_batch_tipe_layanan_id = $delivery_order_batch_tipe_layanan_id == "" ? null : $delivery_order_batch_tipe_layanan_id;
		$delivery_order_batch_tipe_layanan_no = $delivery_order_batch_tipe_layanan_no == "" ? null : $delivery_order_batch_tipe_layanan_no;
		$delivery_order_batch_tipe_layanan_nama = $delivery_order_batch_tipe_layanan_nama == "" ? null : $delivery_order_batch_tipe_layanan_nama;
		$tipe_delivery_order_id = $tipe_delivery_order_id == "" ? null : $tipe_delivery_order_id;
		$delivery_order_batch_is_need_packing = $delivery_order_batch_is_need_packing == "" ? null : $delivery_order_batch_is_need_packing;
		$delivery_order_batch_create_who = $delivery_order_batch_create_who == "" ? null : $delivery_order_batch_create_who;
		$delivery_order_batch_create_tgl = $delivery_order_batch_create_tgl == "" ? null : $delivery_order_batch_create_tgl;
		// $area_id = $area_id == "" ? null : $area_id;
		$delivery_order_batch_status = $delivery_order_batch_status == "" ? null : $delivery_order_batch_status;
		$kendaraan_id = $kendaraan_id == "" ? null : $kendaraan_id;
		$karyawan_id = $karyawan_id == "" ? null : $karyawan_id;
		$tipe_ekspedisi_id = $tipe_ekspedisi_id == "" ? null : $tipe_ekspedisi_id;
		$kendaraan_volume_cm3_max = $kendaraan_volume_cm3_max == "" ? null : $kendaraan_volume_cm3_max;
		$kendaraan_berat_gr_max = $kendaraan_berat_gr_max == "" ? null : $kendaraan_berat_gr_max;
		$kendaraan_volume_cm3_terpakai = $kendaraan_volume_cm3_terpakai == "" ? null : $kendaraan_volume_cm3_terpakai;
		$kendaraan_berat_gr_terpakai = $kendaraan_berat_gr_terpakai == "" ? null : $kendaraan_berat_gr_terpakai;
		// $kendaraan_volume_cm3_sisa = $kendaraan_volume_cm3_sisa == "" ? null : $kendaraan_volume_cm3_sisa;
		// $kendaraan_berat_gr_sisa = $kendaraan_berat_gr_sisa == "" ? null : $kendaraan_berat_gr_sisa;
		$kendaraan_volume_cm3_sisa = $kendaraan_volume_cm3_max - $kendaraan_volume_cm3_terpakai;
		$kendaraan_berat_gr_sisa = $kendaraan_berat_gr_max - $kendaraan_berat_gr_terpakai;
		$kendaraan_km_awal = $kendaraan_km_awal == "" ? null : $kendaraan_km_awal;
		$kendaraan_km_akhir = $kendaraan_km_akhir == "" ? null : $kendaraan_km_akhir;
		$kendaraan_km_terpakai = $kendaraan_km_terpakai == "" ? null : $kendaraan_km_terpakai;
		$picking_list_id = $picking_list_id == "" ? null : $picking_list_id;
		$picking_order_id = $picking_order_id == "" ? null : $picking_order_id;
		$serah_terima_kirim_id = $serah_terima_kirim_id == "" ? null : $serah_terima_kirim_id;

		// $tgl_ = date_create(str_replace("/", "-", $tgl));
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("delivery_order_batch_kode", $delivery_order_batch_kode);
		$this->db->set("unit_mandiri_id", $unit_mandiri_id);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
		// $this->db->set("delivery_order_batch_ritasi", $delivery_order_batch_ritasi);
		$this->db->set("delivery_order_batch_tanggal", $delivery_order_batch_tanggal);
		$this->db->set("delivery_order_batch_tanggal_kirim", $delivery_order_batch_tanggal_kirim);
		$this->db->set("tipe_pengiriman_id", $tipe_pengiriman_id);
		$this->db->set("delivery_order_batch_tipe_layanan_id", $delivery_order_batch_tipe_layanan_id);
		$this->db->set("delivery_order_batch_tipe_layanan_no", $delivery_order_batch_tipe_layanan_no);
		$this->db->set("delivery_order_batch_tipe_layanan_nama", $delivery_order_batch_tipe_layanan_nama);
		$this->db->set("tipe_delivery_order_id", $tipe_delivery_order_id);
		$this->db->set("delivery_order_batch_is_need_packing", $delivery_order_batch_is_need_packing);
		$this->db->set("delivery_order_batch_create_who", $delivery_order_batch_create_who);
		$this->db->set("delivery_order_batch_create_tgl", "GETDATE()", FALSE);
		// $this->db->set("area_id", $area_id);
		$this->db->set("delivery_order_batch_status", $delivery_order_batch_status);
		$this->db->set("kendaraan_id", $kendaraan_id);
		$this->db->set("karyawan_id", $karyawan_id);
		$this->db->set("tipe_ekspedisi_id", $tipe_ekspedisi_id);
		$this->db->set("kendaraan_volume_cm3_max", $kendaraan_volume_cm3_max);
		$this->db->set("kendaraan_berat_gr_max", $kendaraan_berat_gr_max);
		$this->db->set("kendaraan_volume_cm3_terpakai", $kendaraan_volume_cm3_terpakai);
		$this->db->set("kendaraan_berat_gr_terpakai", $kendaraan_berat_gr_terpakai);
		$this->db->set("kendaraan_volume_cm3_sisa", $kendaraan_volume_cm3_sisa);
		$this->db->set("kendaraan_berat_gr_sisa", $kendaraan_berat_gr_sisa);
		$this->db->set("kendaraan_km_awal", $kendaraan_km_awal);
		$this->db->set("kendaraan_km_akhir", $kendaraan_km_akhir);
		$this->db->set("kendaraan_km_terpakai", $kendaraan_km_terpakai);
		$this->db->set("picking_list_id", $picking_list_id);
		$this->db->set("picking_order_id", $picking_order_id);
		$this->db->set("serah_terima_kirim_id", $serah_terima_kirim_id);
		$this->db->set("delivery_order_batch_h_id", $dobhID);

		$queryinsert = $this->db->insert("delivery_order_batch");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function update_delivery_order_batch($delivery_order_batch_id, $delivery_order_batch_kode, $unit_mandiri_id, $client_wms_id, $depo_id, $depo_detail_id, $delivery_order_batch_ritasi, $delivery_order_batch_tanggal, $delivery_order_batch_tanggal_kirim, $tipe_pengiriman_id, $delivery_order_batch_tipe_layanan_id, $delivery_order_batch_tipe_layanan_no, $delivery_order_batch_tipe_layanan_nama, $tipe_delivery_order_id, $delivery_order_batch_is_need_packing, $delivery_order_batch_create_who, $delivery_order_batch_create_tgl, $delivery_order_batch_status, $kendaraan_id, $karyawan_id, $tipe_ekspedisi_id, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max, $kendaraan_volume_cm3_terpakai, $kendaraan_berat_gr_terpakai, $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa, $kendaraan_km_awal, $kendaraan_km_akhir, $kendaraan_km_terpakai, $picking_list_id, $picking_order_id, $serah_terima_kirim_id)
	{
		// $tgl = $tgl . " " . date('H:i:s');
		$unit_mandiri_id = $unit_mandiri_id == "" ? null : $unit_mandiri_id;
		$client_wms_id = $client_wms_id == "" ? null : $client_wms_id;
		$depo_id = $depo_id == "" ? null : $depo_id;
		$depo_detail_id = $depo_detail_id == "" ? null : $depo_detail_id;
		$delivery_order_batch_ritasi = $delivery_order_batch_ritasi == "" ? null : $delivery_order_batch_ritasi;
		$delivery_order_batch_tanggal = $delivery_order_batch_tanggal == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_batch_tanggal)));
		$delivery_order_batch_tanggal_kirim = $delivery_order_batch_tanggal_kirim == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_batch_tanggal_kirim)));
		$tipe_pengiriman_id = $tipe_pengiriman_id == "" ? null : $tipe_pengiriman_id;
		$delivery_order_batch_tipe_layanan_id = $delivery_order_batch_tipe_layanan_id == "" ? null : $delivery_order_batch_tipe_layanan_id;
		$delivery_order_batch_tipe_layanan_no = $delivery_order_batch_tipe_layanan_no == "" ? null : $delivery_order_batch_tipe_layanan_no;
		$delivery_order_batch_tipe_layanan_nama = $delivery_order_batch_tipe_layanan_nama == "" ? null : $delivery_order_batch_tipe_layanan_nama;
		$tipe_delivery_order_id = $tipe_delivery_order_id == "" ? null : $tipe_delivery_order_id;
		$delivery_order_batch_is_need_packing = $delivery_order_batch_is_need_packing == "" ? null : $delivery_order_batch_is_need_packing;
		$delivery_order_batch_create_who = $delivery_order_batch_create_who == "" ? null : $delivery_order_batch_create_who;
		$delivery_order_batch_create_tgl = $delivery_order_batch_create_tgl == "" ? null : $delivery_order_batch_create_tgl;
		$delivery_order_batch_status = $delivery_order_batch_status == "" ? null : $delivery_order_batch_status;
		$kendaraan_id = $kendaraan_id == "" ? null : $kendaraan_id;
		$karyawan_id = $karyawan_id == "" ? null : $karyawan_id;
		$tipe_ekspedisi_id = $tipe_ekspedisi_id == "" ? null : $tipe_ekspedisi_id;
		$kendaraan_volume_cm3_max = $kendaraan_volume_cm3_max == "" ? null : $kendaraan_volume_cm3_max;
		$kendaraan_berat_gr_max = $kendaraan_berat_gr_max == "" ? null : $kendaraan_berat_gr_max;
		$kendaraan_volume_cm3_terpakai = $kendaraan_volume_cm3_terpakai == "" ? null : $kendaraan_volume_cm3_terpakai;
		$kendaraan_berat_gr_terpakai = $kendaraan_berat_gr_terpakai == "" ? null : $kendaraan_berat_gr_terpakai;
		// $kendaraan_volume_cm3_sisa = $kendaraan_volume_cm3_sisa == "" ? null : $kendaraan_volume_cm3_sisa;
		// $kendaraan_berat_gr_sisa = $kendaraan_berat_gr_sisa == "" ? null : $kendaraan_berat_gr_sisa;
		$kendaraan_volume_cm3_sisa = $kendaraan_volume_cm3_max - $kendaraan_volume_cm3_terpakai;
		$kendaraan_berat_gr_sisa = $kendaraan_berat_gr_max - $kendaraan_berat_gr_terpakai;
		$kendaraan_km_awal = $kendaraan_km_awal == "" ? null : $kendaraan_km_awal;
		$kendaraan_km_akhir = $kendaraan_km_akhir == "" ? null : $kendaraan_km_akhir;
		$kendaraan_km_terpakai = $kendaraan_km_terpakai == "" ? null : $kendaraan_km_terpakai;
		$picking_list_id = $picking_list_id == "" ? null : $picking_list_id;
		$picking_order_id = $picking_order_id == "" ? null : $picking_order_id;
		$serah_terima_kirim_id = $serah_terima_kirim_id == "" ? null : $serah_terima_kirim_id;

		// $tgl_ = date_create(str_replace("/", "-", $tgl));
		// $this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		// $this->db->set("delivery_order_batch_kode", $delivery_order_batch_kode);
		$this->db->set("unit_mandiri_id", $unit_mandiri_id);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("delivery_order_batch_ritasi", $delivery_order_batch_ritasi);
		$this->db->set("delivery_order_batch_tanggal", $delivery_order_batch_tanggal);
		$this->db->set("delivery_order_batch_tanggal_kirim", $delivery_order_batch_tanggal_kirim);
		$this->db->set("tipe_pengiriman_id", $tipe_pengiriman_id);
		$this->db->set("delivery_order_batch_tipe_layanan_id", $delivery_order_batch_tipe_layanan_id);
		$this->db->set("delivery_order_batch_tipe_layanan_no", $delivery_order_batch_tipe_layanan_no);
		$this->db->set("delivery_order_batch_tipe_layanan_nama", $delivery_order_batch_tipe_layanan_nama);
		$this->db->set("tipe_delivery_order_id", $tipe_delivery_order_id);
		$this->db->set("delivery_order_batch_is_need_packing", $delivery_order_batch_is_need_packing);
		$this->db->set("delivery_order_batch_create_who", $delivery_order_batch_create_who);
		// $this->db->set("delivery_order_batch_update_who", $this->session->userdata('pengguna_username'));
		// $this->db->set("delivery_order_batch_update_tgl", "GETDATE()", FALSE);
		// $this->db->set("area_id", $area_id);
		$this->db->set("delivery_order_batch_status", $delivery_order_batch_status);
		$this->db->set("kendaraan_id", $kendaraan_id);
		$this->db->set("karyawan_id", $karyawan_id);
		$this->db->set("tipe_ekspedisi_id", $tipe_ekspedisi_id);
		$this->db->set("kendaraan_volume_cm3_max", $kendaraan_volume_cm3_max);
		$this->db->set("kendaraan_berat_gr_max", $kendaraan_berat_gr_max);
		$this->db->set("kendaraan_volume_cm3_terpakai", $kendaraan_volume_cm3_terpakai);
		$this->db->set("kendaraan_berat_gr_terpakai", $kendaraan_berat_gr_terpakai);
		$this->db->set("kendaraan_volume_cm3_sisa", $kendaraan_volume_cm3_sisa);
		$this->db->set("kendaraan_berat_gr_sisa", $kendaraan_berat_gr_sisa);
		$this->db->set("kendaraan_km_awal", $kendaraan_km_awal);
		$this->db->set("kendaraan_km_akhir", $kendaraan_km_akhir);
		$this->db->set("kendaraan_km_terpakai", $kendaraan_km_terpakai);
		$this->db->set("picking_list_id", $picking_list_id);
		$this->db->set("picking_order_id", $picking_order_id);
		$this->db->set("serah_terima_kirim_id", $serah_terima_kirim_id);

		$this->db->where("delivery_order_batch_id", $delivery_order_batch_id);

		$queryupdate = $this->db->update("delivery_order_batch");

		return $queryupdate;
		// return $this->db->last_query();
	}

	public function confirm_delivery_order_batch($delivery_order_batch_id, $delivery_order_batch_kode, $unit_mandiri_id, $client_wms_id, $depo_id, $depo_detail_id, $delivery_order_batch_ritasi, $delivery_order_batch_tanggal, $delivery_order_batch_tanggal_kirim, $tipe_pengiriman_id, $delivery_order_batch_tipe_layanan_id, $delivery_order_batch_tipe_layanan_no, $delivery_order_batch_tipe_layanan_nama, $tipe_delivery_order_id, $delivery_order_batch_is_need_packing, $delivery_order_batch_create_who, $delivery_order_batch_create_tgl, $delivery_order_batch_status, $kendaraan_id, $karyawan_id, $tipe_ekspedisi_id, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max, $kendaraan_volume_cm3_terpakai, $kendaraan_berat_gr_terpakai, $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa, $kendaraan_km_awal, $kendaraan_km_akhir, $kendaraan_km_terpakai, $picking_list_id, $picking_order_id, $serah_terima_kirim_id)
	{
		// $tgl = $tgl . " " . date('H:i:s');
		$unit_mandiri_id = $unit_mandiri_id == "" ? null : $unit_mandiri_id;
		$client_wms_id = $client_wms_id == "" ? null : $client_wms_id;
		$depo_id = $depo_id == "" ? null : $depo_id;
		$depo_detail_id = $depo_detail_id == "" ? null : $depo_detail_id;
		$delivery_order_batch_ritasi = $delivery_order_batch_ritasi == "" ? null : $delivery_order_batch_ritasi;
		$delivery_order_batch_tanggal = $delivery_order_batch_tanggal == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_batch_tanggal)));
		$delivery_order_batch_tanggal_kirim = $delivery_order_batch_tanggal_kirim == "" ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_batch_tanggal_kirim)));
		$tipe_pengiriman_id = $tipe_pengiriman_id == "" ? null : $tipe_pengiriman_id;
		$delivery_order_batch_tipe_layanan_id = $delivery_order_batch_tipe_layanan_id == "" ? null : $delivery_order_batch_tipe_layanan_id;
		$delivery_order_batch_tipe_layanan_no = $delivery_order_batch_tipe_layanan_no == "" ? null : $delivery_order_batch_tipe_layanan_no;
		$delivery_order_batch_tipe_layanan_nama = $delivery_order_batch_tipe_layanan_nama == "" ? null : $delivery_order_batch_tipe_layanan_nama;
		$tipe_delivery_order_id = $tipe_delivery_order_id == "" ? null : $tipe_delivery_order_id;
		$delivery_order_batch_is_need_packing = $delivery_order_batch_is_need_packing == "" ? null : $delivery_order_batch_is_need_packing;
		$delivery_order_batch_create_who = $delivery_order_batch_create_who == "" ? null : $delivery_order_batch_create_who;
		$delivery_order_batch_create_tgl = $delivery_order_batch_create_tgl == "" ? null : $delivery_order_batch_create_tgl;
		// $area_id = $area_id == "" ? null : $area_id;
		$delivery_order_batch_status = $delivery_order_batch_status == "" ? null : $delivery_order_batch_status;
		$kendaraan_id = $kendaraan_id == "" ? null : $kendaraan_id;
		$karyawan_id = $karyawan_id == "" ? null : $karyawan_id;
		$tipe_ekspedisi_id = $tipe_ekspedisi_id == "" ? null : $tipe_ekspedisi_id;
		$kendaraan_volume_cm3_max = $kendaraan_volume_cm3_max == "" ? null : $kendaraan_volume_cm3_max;
		$kendaraan_berat_gr_max = $kendaraan_berat_gr_max == "" ? null : $kendaraan_berat_gr_max;
		$kendaraan_volume_cm3_terpakai = $kendaraan_volume_cm3_terpakai == "" ? null : $kendaraan_volume_cm3_terpakai;
		$kendaraan_berat_gr_terpakai = $kendaraan_berat_gr_terpakai == "" ? null : $kendaraan_berat_gr_terpakai;
		// $kendaraan_volume_cm3_sisa = $kendaraan_volume_cm3_sisa == "" ? null : $kendaraan_volume_cm3_sisa;
		// $kendaraan_berat_gr_sisa = $kendaraan_berat_gr_sisa == "" ? null : $kendaraan_berat_gr_sisa;
		$kendaraan_volume_cm3_sisa = $kendaraan_volume_cm3_max - $kendaraan_volume_cm3_terpakai;
		$kendaraan_berat_gr_sisa = $kendaraan_berat_gr_max - $kendaraan_berat_gr_terpakai;
		$kendaraan_km_awal = $kendaraan_km_awal == "" ? null : $kendaraan_km_awal;
		$kendaraan_km_akhir = $kendaraan_km_akhir == "" ? null : $kendaraan_km_akhir;
		$kendaraan_km_terpakai = $kendaraan_km_terpakai == "" ? null : $kendaraan_km_terpakai;
		$picking_list_id = $picking_list_id == "" ? null : $picking_list_id;
		$picking_order_id = $picking_order_id == "" ? null : $picking_order_id;
		$serah_terima_kirim_id = $serah_terima_kirim_id == "" ? null : $serah_terima_kirim_id;

		// $tgl_ = date_create(str_replace("/", "-", $tgl));
		// $this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		// $this->db->set("delivery_order_batch_kode", $delivery_order_batch_kode);
		$this->db->set("unit_mandiri_id", $unit_mandiri_id);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("delivery_order_batch_ritasi", $delivery_order_batch_ritasi);
		$this->db->set("delivery_order_batch_tanggal", $delivery_order_batch_tanggal);
		$this->db->set("delivery_order_batch_tanggal_kirim", $delivery_order_batch_tanggal_kirim);
		$this->db->set("tipe_pengiriman_id", $tipe_pengiriman_id);
		$this->db->set("delivery_order_batch_tipe_layanan_id", $delivery_order_batch_tipe_layanan_id);
		$this->db->set("delivery_order_batch_tipe_layanan_no", $delivery_order_batch_tipe_layanan_no);
		$this->db->set("delivery_order_batch_tipe_layanan_nama", $delivery_order_batch_tipe_layanan_nama);
		$this->db->set("tipe_delivery_order_id", $tipe_delivery_order_id);
		$this->db->set("delivery_order_batch_is_need_packing", $delivery_order_batch_is_need_packing);
		$this->db->set("delivery_order_batch_create_who", $delivery_order_batch_create_who);
		// $this->db->set("delivery_order_batch_create_tgl", "GETDATE()", FALSE);
		// $this->db->set("area_id", $area_id);
		$this->db->set("delivery_order_batch_status", "in progress");
		$this->db->set("kendaraan_id", $kendaraan_id);
		$this->db->set("karyawan_id", $karyawan_id);
		$this->db->set("tipe_ekspedisi_id", $tipe_ekspedisi_id);
		$this->db->set("kendaraan_volume_cm3_max", $kendaraan_volume_cm3_max);
		$this->db->set("kendaraan_berat_gr_max", $kendaraan_berat_gr_max);
		$this->db->set("kendaraan_volume_cm3_terpakai", $kendaraan_volume_cm3_terpakai);
		$this->db->set("kendaraan_berat_gr_terpakai", $kendaraan_berat_gr_terpakai);
		$this->db->set("kendaraan_volume_cm3_sisa", $kendaraan_volume_cm3_sisa);
		$this->db->set("kendaraan_berat_gr_sisa", $kendaraan_berat_gr_sisa);
		$this->db->set("kendaraan_km_awal", $kendaraan_km_awal);
		$this->db->set("kendaraan_km_akhir", $kendaraan_km_akhir);
		$this->db->set("kendaraan_km_terpakai", $kendaraan_km_terpakai);
		$this->db->set("picking_list_id", $picking_list_id);
		$this->db->set("picking_order_id", $picking_order_id);
		$this->db->set("serah_terima_kirim_id", $serah_terima_kirim_id);

		$this->db->where("delivery_order_batch_id", $delivery_order_batch_id);

		$queryupdate = $this->db->update("delivery_order_batch");

		return $queryupdate;
		// return $this->db->last_query();
	}

	public function insert_delivery_order($delivery_order_batch_id, $do_id, $do_kode, $data, $mode, $delivery_order_batch_tanggal_kirim)
	{
		// $delivery_order_id = $data['delivery_order_draft_id'] == '' ? null : $data['delivery_order_draft_id'];
		$delivery_order_batch_id = $delivery_order_batch_id == '' ? null : $delivery_order_batch_id;
		$sales_order_id = $data['sales_order_id'] == '' ? null : $data['sales_order_id'];
		// $delivery_order_kode = $data['delivery_order_draft_kode'] == '' ? null : $data['delivery_order_draft_kode'];
		// $delivery_order_yourref = $data['delivery_order_draft_yourref'] == '' ? null : $data['delivery_order_draft_yourref'];
		$client_wms_id = $data['client_wms_id'] == '' ? null : $data['client_wms_id'];
		$delivery_order_tgl_buat_do = $data['delivery_order_draft_tgl_buat_do'] == '' ? null : $data['delivery_order_draft_tgl_buat_do'];
		$delivery_order_tgl_expired_do = $data['delivery_order_draft_tgl_expired_do'] == '' ? null : $data['delivery_order_draft_tgl_expired_do'];
		$delivery_order_tgl_surat_jalan = $data['delivery_order_draft_tgl_surat_jalan'] == '' ? null : $data['delivery_order_draft_tgl_surat_jalan'];
		$delivery_order_tgl_rencana_kirim = $data['delivery_order_draft_tgl_rencana_kirim'] == '' ? null : $data['delivery_order_draft_tgl_rencana_kirim'];
		$delivery_order_tgl_aktual_kirim = $delivery_order_batch_tanggal_kirim == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_batch_tanggal_kirim)));
		// $delivery_order_tgl_aktual_kirim = $data['delivery_order_draft_tgl_aktual_kirim'] == '' ? null : $data['delivery_order_draft_tgl_aktual_kirim'];
		$delivery_order_keterangan = $data['delivery_order_draft_keterangan'] == '' ? null : $data['delivery_order_draft_keterangan'];
		// $delivery_order_status = $data['delivery_order_draft_status'] == '' ? null : $data['delivery_order_draft_status'];
		$delivery_order_is_prioritas = $data['delivery_order_draft_is_prioritas'] == '' ? null : $data['delivery_order_draft_is_prioritas'];
		$delivery_order_is_need_packing = $data['delivery_order_draft_is_need_packing'] == '' ? null : $data['delivery_order_draft_is_need_packing'];
		$delivery_order_tipe_layanan = $data['delivery_order_draft_tipe_layanan'] == '' ? null : $data['delivery_order_draft_tipe_layanan'];
		$delivery_order_tipe_pembayaran = $data['delivery_order_draft_tipe_pembayaran'] == '' ? null : $data['delivery_order_draft_tipe_pembayaran'];
		$delivery_order_sesi_pengiriman = $data['delivery_order_draft_sesi_pengiriman'] == '' ? null : $data['delivery_order_draft_sesi_pengiriman'];
		$delivery_order_request_tgl_kirim = $data['delivery_order_draft_request_tgl_kirim'] == '' ? null : $data['delivery_order_draft_request_tgl_kirim'];
		$delivery_order_request_jam_kirim = $data['delivery_order_draft_request_jam_kirim'] == '' ? null : $data['delivery_order_draft_request_jam_kirim'];
		$tipe_pengiriman_id = $data['tipe_pengiriman_id'] == '' ? null : $data['tipe_pengiriman_id'];
		$nama_tipe = $data['nama_tipe'] == '' ? null : $data['nama_tipe'];
		$confirm_rate = $data['confirm_rate'] == '' ? null : $data['confirm_rate'];
		$delivery_order_reff_id = $data['delivery_order_draft_reff_id'] == '' ? null : $data['delivery_order_draft_reff_id'];
		$delivery_order_reff_no = $data['delivery_order_draft_reff_no'] == '' ? null : $data['delivery_order_draft_reff_no'];
		$delivery_order_total = $data['delivery_order_draft_total'] == '' ? null : $data['delivery_order_draft_total'];
		$unit_mandiri_id = $data['unit_mandiri_id'] == '' ? null : $data['unit_mandiri_id'];
		$depo_id = $data['depo_id'] == '' ? null : $data['depo_id'];
		$client_pt_id = $data['client_pt_id'] == '' ? null : $data['client_pt_id'];
		$delivery_order_kirim_nama = $data['delivery_order_draft_kirim_nama'] == '' ? null : $data['delivery_order_draft_kirim_nama'];
		$delivery_order_kirim_alamat = $data['delivery_order_draft_kirim_alamat'] == '' ? null : $data['delivery_order_draft_kirim_alamat'];
		$delivery_order_kirim_telp = $data['delivery_order_draft_kirim_telp'] == '' ? null : $data['delivery_order_draft_kirim_telp'];
		$delivery_order_kirim_provinsi = $data['delivery_order_draft_kirim_provinsi'] == '' ? null : $data['delivery_order_draft_kirim_provinsi'];
		$delivery_order_kirim_kota = $data['delivery_order_draft_kirim_kota'] == '' ? null : $data['delivery_order_draft_kirim_kota'];
		$delivery_order_kirim_kecamatan = $data['delivery_order_draft_kirim_kecamatan'] == '' ? null : $data['delivery_order_draft_kirim_kecamatan'];
		$delivery_order_kirim_kelurahan = $data['delivery_order_draft_kirim_kelurahan'] == '' ? null : $data['delivery_order_draft_kirim_kelurahan'];
		$delivery_order_kirim_latitude = $data['client_pt_latitude'] == '' ? null : $data['client_pt_latitude'];
		$delivery_order_kirim_longitude = $data['client_pt_longitude'] == '' ? null : $data['client_pt_longitude'];
		$delivery_order_kirim_kodepos = $data['delivery_order_draft_kirim_kodepos'] == '' ? null : $data['delivery_order_draft_kirim_kodepos'];
		$delivery_order_kirim_area = $data['delivery_order_draft_kirim_area'] == '' ? null : $data['delivery_order_draft_kirim_area'];
		$delivery_order_kirim_invoice_pdf = $data['delivery_order_draft_kirim_invoice_pdf'] == '' ? null : $data['delivery_order_draft_kirim_invoice_pdf'];
		$delivery_order_kirim_invoice_dir = $data['delivery_order_draft_kirim_invoice_dir'] == '' ? null : $data['delivery_order_draft_kirim_invoice_dir'];
		$principle_id = $data['pabrik_id'] == '' ? null : $data['pabrik_id'];
		$delivery_order_ambil_nama = $data['delivery_order_draft_ambil_nama'] == '' ? null : $data['delivery_order_draft_ambil_nama'];
		$delivery_order_ambil_alamat = $data['delivery_order_draft_ambil_alamat'] == '' ? null : $data['delivery_order_draft_ambil_alamat'];
		$delivery_order_ambil_telp = $data['delivery_order_draft_ambil_telp'] == '' ? null : $data['delivery_order_draft_ambil_telp'];
		$delivery_order_ambil_provinsi = $data['delivery_order_draft_ambil_provinsi'] == '' ? null : $data['delivery_order_draft_ambil_provinsi'];
		$delivery_order_ambil_kota = $data['delivery_order_draft_ambil_kota'] == '' ? null : $data['delivery_order_draft_ambil_kota'];
		$delivery_order_ambil_kecamatan = $data['delivery_order_draft_ambil_kecamatan'] == '' ? null : $data['delivery_order_draft_ambil_kecamatan'];
		$delivery_order_ambil_kelurahan = $data['delivery_order_draft_ambil_kelurahan'] == '' ? null : $data['delivery_order_draft_ambil_kelurahan'];
		// $delivery_order_ambil_latitude = $data['client_pt_latitude'] == '' ? null : $data['client_pt_latitude'];
		// $delivery_order_ambil_longitude = $data['client_pt_longitude'] == '' ? null : $data['client_pt_longitude'];
		$delivery_order_ambil_kodepos = $data['delivery_order_draft_ambil_kodepos'] == '' ? null : $data['delivery_order_draft_ambil_kodepos'];
		$delivery_order_ambil_area = $data['delivery_order_draft_ambil_area'] == '' ? null : $data['delivery_order_draft_ambil_area'];
		// $delivery_order_update_who = $data['delivery_order_draft_update_who'] == '' ? null : $data['delivery_order_draft_update_who'];
		// $delivery_order_update_tgl = $data['delivery_order_draft_update_tgl'] == '' ? null : $data['delivery_order_draft_update_tgl'];
		// $delivery_order_approve_who = $data['delivery_order_draft_approve_who'] == '' ? null : $data['delivery_order_draft_approve_who'];
		// $delivery_order_approve_tgl = $data['delivery_order_draft_approve_tgl'] == '' ? null : $data['delivery_order_draft_approve_tgl'];
		// $delivery_order_reject_who = $data['delivery_order_draft_reject_who'] == '' ? null : $data['delivery_order_draft_reject_who'];
		// $delivery_order_reject_tgl = $data['delivery_order_draft_reject_tgl'] == '' ? null : $data['delivery_order_draft_reject_tgl'];
		// $delivery_order_reject_reason = $data['delivery_order_draft_reject_reason'] == '' ? null : $data['delivery_order_draft_reject_reason'];
		$delivery_order_no_urut_rute = $data['no_urut'] == '' ? null : $data['no_urut'];
		// $delivery_order_prioritas_stock = $data['delivery_order_draft_prioritas_stock'] == '' ? null : $data['delivery_order_draft_prioritas_stock'];
		$tipe_delivery_order_id = $data['tipe_delivery_order_id'] == '' ? null : $data['tipe_delivery_order_id'];
		$delivery_order_draft_id = $data['delivery_order_draft_id'] == '' ? null : $data['delivery_order_draft_id'];
		$delivery_order_draft_kode = $data['delivery_order_draft_kode'] == '' ? null : $data['delivery_order_draft_kode'];
		$delivery_order_nominal_tunai = $data['delivery_order_draft_nominal_tunai'] == '' ? null : $data['delivery_order_draft_nominal_tunai'];
		$delivery_order_attachment = $data['delivery_order_draft_attachment'] == '' ? null : $data['delivery_order_draft_attachment'];
		// $is_promo = $data['is_promo'] == '' ? null : $data['is_promo'];
		$is_promo = $data['is_promo'];
		$isCanvas = $data['is_canvas'] == '' ? null : $data['is_canvas'];
		$canvasId = $data['canvas_id'] == '' ? null : $data['canvas_id'];

		$this->db->set("delivery_order_id", $do_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("sales_order_id", $sales_order_id);
		if ($mode == "laksanakan") {
			$this->db->set("delivery_order_kode", $do_kode);
		}
		$this->db->set("delivery_order_yourref", NULL);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("delivery_order_tgl_buat_do", $delivery_order_tgl_buat_do);
		$this->db->set("delivery_order_tgl_expired_do", $delivery_order_tgl_expired_do);
		$this->db->set("delivery_order_tgl_surat_jalan", $delivery_order_tgl_surat_jalan);
		$this->db->set("delivery_order_tgl_rencana_kirim", $delivery_order_tgl_rencana_kirim);
		// $this->db->set("delivery_order_tgl_aktual_kirim", "GETDATE()", FALSE);
		$this->db->set("delivery_order_tgl_aktual_kirim", $delivery_order_tgl_aktual_kirim);
		$this->db->set("delivery_order_keterangan", $delivery_order_keterangan);
		$this->db->set("delivery_order_status", "Draft");
		$this->db->set("delivery_order_is_prioritas", $delivery_order_is_prioritas);
		$this->db->set("delivery_order_is_need_packing", $delivery_order_is_need_packing);
		$this->db->set("delivery_order_tipe_layanan", $delivery_order_tipe_layanan);
		$this->db->set("delivery_order_tipe_pembayaran", $delivery_order_tipe_pembayaran);
		$this->db->set("delivery_order_sesi_pengiriman", $delivery_order_sesi_pengiriman);
		$this->db->set("delivery_order_request_tgl_kirim", $delivery_order_request_tgl_kirim);
		$this->db->set("delivery_order_request_jam_kirim", $delivery_order_request_jam_kirim);
		$this->db->set("tipe_pengiriman_id", $tipe_pengiriman_id);
		$this->db->set("nama_tipe", $nama_tipe);
		$this->db->set("confirm_rate", $confirm_rate);
		$this->db->set("delivery_order_reff_id", $delivery_order_reff_id);
		$this->db->set("delivery_order_reff_no", $delivery_order_reff_no);
		$this->db->set("delivery_order_total", $delivery_order_total);
		$this->db->set("unit_mandiri_id", $unit_mandiri_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("client_pt_id", $client_pt_id);
		$this->db->set("delivery_order_kirim_nama", $delivery_order_kirim_nama);
		$this->db->set("delivery_order_kirim_alamat", $delivery_order_kirim_alamat);
		$this->db->set("delivery_order_kirim_telp", $delivery_order_kirim_telp);
		$this->db->set("delivery_order_kirim_provinsi", $delivery_order_kirim_provinsi);
		$this->db->set("delivery_order_kirim_kota", $delivery_order_kirim_kota);
		$this->db->set("delivery_order_kirim_kecamatan", $delivery_order_kirim_kecamatan);
		$this->db->set("delivery_order_kirim_kelurahan", $delivery_order_kirim_kelurahan);
		$this->db->set("delivery_order_kirim_latitude", $delivery_order_kirim_latitude);
		$this->db->set("delivery_order_kirim_longitude", $delivery_order_kirim_longitude);
		// $this->db->set("delivery_order_kirim_latitude", NULL);
		// $this->db->set("delivery_order_kirim_longitude", NULL);
		$this->db->set("delivery_order_kirim_kodepos", $delivery_order_kirim_kodepos);
		$this->db->set("delivery_order_kirim_area", $delivery_order_kirim_area);
		$this->db->set("delivery_order_kirim_invoice_pdf", $delivery_order_kirim_invoice_pdf);
		$this->db->set("delivery_order_kirim_invoice_dir", $delivery_order_kirim_invoice_dir);
		$this->db->set("principle_id", $principle_id);
		$this->db->set("delivery_order_ambil_nama", $delivery_order_ambil_nama);
		$this->db->set("delivery_order_ambil_alamat", $delivery_order_ambil_alamat);
		$this->db->set("delivery_order_ambil_telp", $delivery_order_ambil_telp);
		$this->db->set("delivery_order_ambil_provinsi", $delivery_order_ambil_provinsi);
		$this->db->set("delivery_order_ambil_kota", $delivery_order_ambil_kota);
		$this->db->set("delivery_order_ambil_kecamatan", $delivery_order_ambil_kecamatan);
		$this->db->set("delivery_order_ambil_kelurahan", $delivery_order_ambil_kelurahan);
		// $this->db->set("delivery_order_ambil_latitude", $delivery_order_ambil_latitude);
		// $this->db->set("delivery_order_ambil_longitude", $delivery_order_ambil_longitude);
		$this->db->set("delivery_order_ambil_latitude", NULL);
		$this->db->set("delivery_order_ambil_longitude", NULL);
		$this->db->set("delivery_order_ambil_kodepos", $delivery_order_ambil_kodepos);
		$this->db->set("delivery_order_ambil_area", $delivery_order_ambil_area);
		$this->db->set("delivery_order_update_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_update_tgl", "GETDATE()", FALSE);
		$this->db->set("delivery_order_approve_who", NULL);
		$this->db->set("delivery_order_approve_tgl", NULL);
		$this->db->set("delivery_order_reject_who", NULL);
		$this->db->set("delivery_order_reject_tgl", NULL);
		$this->db->set("delivery_order_reject_reason", NULL);
		$this->db->set("delivery_order_no_urut_rute", $delivery_order_no_urut_rute);
		$this->db->set("delivery_order_prioritas_stock", NULL);
		$this->db->set("tipe_delivery_order_id", $tipe_delivery_order_id);
		$this->db->set("delivery_order_draft_id", $delivery_order_draft_id);
		$this->db->set("delivery_order_draft_kode", $delivery_order_draft_kode);
		$this->db->set("delivery_order_nominal_tunai", $delivery_order_nominal_tunai);
		$this->db->set("delivery_order_attachment", $delivery_order_attachment);
		$this->db->set("is_promo", $is_promo);
		$this->db->set("is_canvas", $isCanvas);
		$this->db->set("canvas_id", $canvasId);

		$queryinsert = $this->db->insert("delivery_order");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_delivery_order_detail($dod_id, $delivery_order_id, $delivery_order_batch_id, $data)
	{
		// $delivery_order_detail_id = $data['delivery_order_detail_id'] == '' ? null : $data['delivery_order_detail_id'];
		// $delivery_order_id = $data['delivery_order_id'] == '' ? null : $data['delivery_order_id'];
		// $delivery_order_batch_id = $data['delivery_order_batch_id'] == '' ? null : $data['delivery_order_batch_id'];
		$sku_id = $data['sku_id'] == '' ? null : $data['sku_id'];
		$depo_id = $data['gudang_id'] == '' ? null : $data['gudang_id'];
		$depo_detail_id = $data['gudang_detail_id'] == '' ? null : $data['gudang_detail_id'];
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
		// $sku_qty_kirim = $data['sku_qty_kirim'] == '' ? null : $data['sku_qty_kirim'];
		// $reason_id = $data['reason_id'] == '' ? null : $data['reason_id'];

		$this->db->set("delivery_order_detail_id", $dod_id);
		$this->db->set("delivery_order_id", $delivery_order_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("sku_kode", $sku_kode);
		$this->db->set("sku_nama_produk", $sku_nama_produk);
		$this->db->set("sku_harga_satuan", $sku_harga_satuan);
		$this->db->set("sku_disc_percent", $sku_disc_percent);
		$this->db->set("sku_disc_rp", $sku_disc_rp);
		$this->db->set("sku_harga_nett", $sku_harga_nett);
		$this->db->set("sku_request_expdate", $sku_request_expdate);
		$this->db->set("sku_filter_expdate", $sku_filter_expdate);
		$this->db->set("sku_filter_expdatebulan", $sku_filter_expdatebulan);
		$this->db->set("sku_filter_expdatetahun", $sku_filter_expdatetahun);
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
		$this->db->set("sku_qty_kirim", null);
		$this->db->set("reason_id", null);
		$this->db->set("tipe_stock_nama", $tipe_stock_nama);


		$queryinsert = $this->db->insert("delivery_order_detail");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_delivery_order_progress($do_id)
	{
		$this->db->set("delivery_order_progress_id", "NEWID()", FALSE);
		$this->db->set("delivery_order_id", $do_id);
		$this->db->set("status_progress_id", "B06EC359-E6D4-4EAB-9303-8251E64FC17A");
		$this->db->set("status_progress_nama", "in progress");
		$this->db->set("delivery_order_progress_create_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_progress_create_tgl", "GETDATE()", FALSE);

		$queryinsert = $this->db->insert("delivery_order_progress");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function InsertDOTemp($delivery_order_temp_id, $delivery_order_batch_id, $delivery_order_draft_id)
	{
		$this->db->set("delivery_order_temp_id", $delivery_order_temp_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("delivery_order_draft_id", $delivery_order_draft_id);

		$queryinsert = $this->db->insert("delivery_order_temp");
		return $queryinsert;
	}

	public function update_delivery_order($delivery_order_batch_id, $delivery_order_draft_id, $no_urut, $mode, $do_kode, $delivery_order_batch_tanggal_kirim)
	{

		$delivery_order_batch_tanggal_kirim = $delivery_order_batch_tanggal_kirim == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_batch_tanggal_kirim)));

		$this->db->set("delivery_order_update_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_update_tgl", "GETDATE()", FALSE);
		// $this->db->set("delivery_order_tgl_aktual_kirim", "GETDATE()", FALSE);
		$this->db->set("delivery_order_tgl_aktual_kirim", $delivery_order_batch_tanggal_kirim);
		$this->db->set("delivery_order_no_urut_rute", $no_urut);
		if ($mode == "insert") {
			$this->db->set("delivery_order_status", "Draft");
		}

		if ($mode == "laksanakan") {
			$this->db->set("delivery_order_status", "in progress item request");
			$this->db->set("delivery_order_kode", $do_kode);
		}
		$this->db->where("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->where("delivery_order_draft_id", $delivery_order_draft_id);

		$queryupdate = $this->db->update("delivery_order");

		return $queryupdate;
	}

	public function Check_DO_Batch_Duplicate($delivery_order_batch_kode)
	{
		$this->db->select("delivery_order_batch_kode")
			->from("delivery_order_batch")
			->where("delivery_order_batch_kode", $delivery_order_batch_kode);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = 1;
		}

		return $query;
	}

	public function Check_DO($delivery_order_draft_id, $delivery_order_batch_id)
	{
		$this->db->select("delivery_order_draft_id, delivery_order_id")
			->from("delivery_order")
			->where("delivery_order_draft_id", $delivery_order_draft_id)
			->where("delivery_order_batch_id", $delivery_order_batch_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function DeleteDO($fdjr_id, $do_id)
	{

		$query = $this->db->query("DELETE FROM delivery_order_detail WHERE delivery_order_id = (SELECT delivery_order_id FROM delivery_order WHERE delivery_order_draft_id = '$do_id' AND delivery_order_batch_id = '$fdjr_id')");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Gagal
		}

		return $res;
	}

	public function DeleteDONotExists($delivery_order_id)
	{

		$query = $this->db->query("DELETE FROM delivery_order WHERE delivery_order_id = '" . $delivery_order_id . "'");
		$query = $this->db->query("DELETE FROM delivery_order_detail WHERE delivery_order_id = '" . $delivery_order_id . "'");
		// $query = $this->db->query("DELETE FROM delivery_order_detail2 WHERE delivery_order_id = '" . $delivery_order_id . "'");

		// $affectedrows = $this->db->affected_rows();
		// if ($affectedrows > 0) {
		// 	$res = 1; // Success
		// } else {
		// 	$res = 0; // Gagal
		// }

		return 1;
	}

	public function DeleteDOTempByAlamat($do_id, $temp_do)
	{
		$do_id = implode(",", $do_id);

		$query = $this->db->query("DELETE FROM delivery_order_temp WHERE delivery_order_draft_id IN (" . $do_id . ") AND delivery_order_temp_id = " . $temp_do . "");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Gagal
		}

		return $res;
	}

	public function DeleteDOByAlamat($do_id, $batch_id)
	{

		$do_id = implode(",", $do_id);

		$query = $this->db->query("DELETE FROM delivery_order WHERE delivery_order_draft_id IN (" . $do_id . ") AND delivery_order_batch_id = " . $batch_id . "");

		// $affectedrows = $this->db->affected_rows();
		// 	if ($affectedrows > 0) {
		// 		$res = 1; // Success
		// 	} else {
		// 		$res = 0; // Gagal
		// 	}

		return $query;
	}

	public function DeleteDOTemp($temp_do)
	{

		$query = $this->db->query("DELETE FROM delivery_order_temp WHERE delivery_order_temp_id = " . $temp_do . "");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Gagal
		}

		return $res;
	}

	// public function Check_DODraft2($do_draft_id)
	// {
	// 	return $this->db->select("*")
	// 		->from("delivery_order_detail2_draft")
	// 		->where("delivery_order_draft_id", $do_draft_id)->get()->result();
	// }

	public function Check_DODraft2($do_draft_id, $delivery_order_detail_draft_id)
	{
		return $this->db->select("*")
			->from("delivery_order_detail2_draft")
			->where("delivery_order_draft_id", $do_draft_id)->where("delivery_order_detail_draft_id", $delivery_order_detail_draft_id)->get()->result();
	}

	public function insert_delivery_order_detail2($do_id, $dod_id, $data)
	{
		$this->db->set("delivery_order_detail2_id", "NEWID()", FALSE);
		$this->db->set("delivery_order_detail_id", $dod_id);
		$this->db->set("delivery_order_id", $do_id);
		$this->db->set("sku_id", $data->sku_id);
		$this->db->set("sku_stock_id", $data->sku_stock_id);
		$this->db->set("sku_expdate", $data->sku_expdate);
		$this->db->set("sku_qty", $data->sku_qty);
		$this->db->set("sku_qty_composite", (int)$data->sku_qty_composite);


		$queryinsert = $this->db->insert("delivery_order_detail2");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function UpdateLatLongDODraft($client_pt_id, $latlong_text, $mode)
	{
		$this->db->set($mode, $latlong_text);
		$this->db->where("client_pt_id", $client_pt_id);
		$this->db->update("client_pt");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function UpdateLatLongDO($alamat_do, $latlong_text, $mode_do)
	{
		$this->db->set($mode_do, $latlong_text);
		$this->db->where("delivery_order_kirim_alamat", $alamat_do);
		$this->db->update("delivery_order");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function OptimasiRute($do_temp_id, $depo_id)
	{
		$query = $this->db->query("exec proses_optimasi_rute '$do_temp_id','$depo_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetVrbl()
	{
		$query = $this->db->query("SELECT vrbl_flg_value FROM vrbl WHERE vrbl_param = 'USE_OPTIMASI_RUTE'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function GetVrblLocation()
	{
		$query = $this->db->query("SELECT vrbl_kode FROM vrbl WHERE vrbl_param = 'MODE_SEARCH'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function GetDeliveryOrderByFDJRDraft($tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_id,
									ISNULL(do.delivery_order_kode,'') AS delivery_order_kode,
									do.delivery_order_batch_id,
									fdjr.delivery_order_batch_kode,
									FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_rencana_kirim,
									FORMAT(do.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_aktual_kirim,
									do.sales_order_id,
									ISNULL(so.sales_order_kode,'') AS sales_order_kode,
									ISNULL(so.sales_order_no_po,'') AS sales_order_no_po,
									do.delivery_order_kirim_nama,
									do.delivery_order_kirim_alamat,
									do.tipe_delivery_order_id,
									tipe.tipe_delivery_order_nama,
									tipe.tipe_delivery_order_alias,
									do.delivery_order_update_tgl
									FROM delivery_order do
									LEFT JOIN FAS.dbo.sales_order so
									ON so.sales_order_id = do.sales_order_id
									LEFT JOIN delivery_order_batch fdjr
									ON fdjr.delivery_order_batch_id = do.delivery_order_batch_id
									LEFT JOIN tipe_delivery_order tipe
									ON tipe.tipe_delivery_order_id = do.tipe_delivery_order_id
									WHERE FORMAT(fdjr.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND fdjr.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND fdjr.delivery_order_batch_status = 'Draft'
									ORDER BY FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') DESC, fdjr.delivery_order_batch_kode, do.delivery_order_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDeliveryOrderBatchDraft($tgl1, $tgl2)
	{
		$query = $this->db->query("SELECT
									delivery_order_batch_id,
									delivery_order_batch_kode,
									FORMAT(delivery_order_batch_create_tgl, 'dd-MM-yyyy') AS delivery_order_batch_create_tgl,
									FORMAT(delivery_order_batch_tanggal, 'dd-MM-yyyy') AS delivery_order_batch_tanggal,
									FORMAT(delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim,
									delivery_order_batch_tanggal_kirim AS dtm_delivery_order_batch_tanggal_kirim,
									fdjr.tipe_delivery_order_id,
									tipe.tipe_delivery_order_nama,
									tipe.tipe_delivery_order_alias,
									fdjr.karyawan_id,
									karyawan.karyawan_nama,
									delivery_order_batch_tipe_layanan_id,
									delivery_order_batch_tipe_layanan_nama,
									delivery_order_batch_status,
									delivery_order_batch_update_tgl,
									delivery_order_batch_update_who
									FROM delivery_order_batch fdjr
									LEFT JOIN tipe_delivery_order tipe
									ON tipe.tipe_delivery_order_id = fdjr.tipe_delivery_order_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = fdjr.karyawan_id
									WHERE FORMAT(fdjr.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND fdjr.depo_id = '" . $this->session->userdata('depo_id') . "'
									AND fdjr.delivery_order_batch_status = 'Draft'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetAreaByFDJRID($id)
	{
		$query = $this->db->select("area.area_nama")->from("delivery_order_area")
			->join("area", "delivery_order_area.area_id = area.area_id", "left")
			->where("delivery_order_area.delivery_order_batch_id", $id)->get();

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function move_delivery_order($delivery_order_batch_id, $delivery_order_tgl_rencana_kirim, $delivery_order_id)
	{
		$this->db->set("delivery_order_tgl_rencana_kirim", $delivery_order_tgl_rencana_kirim);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);

		$this->db->where("delivery_order_id", $delivery_order_id);

		$this->db->update("delivery_order");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryUpdate = 1;
		} else {
			$queryUpdate = 0;
		}

		return $queryUpdate;
		// return $this->db->last_query();
	}

	public function getDetailBatchPerDO($do_batch)
	{
		$query =  $this->db->query("SELECT
		do.delivery_order_kode,
		client_pt.client_pt_nama,
		client_pt.client_pt_alamat,
		do.delivery_order_kirim_nama,
		do.delivery_order_kirim_alamat,
		do.delivery_order_tgl_buat_do,
		dodd2.sku_nama_produk,dodd2.sku_disc_percent,
		dodd2.sku_kode,
		sku.sku_satuan,
		dodd2.sku_harga_satuan,
		client_pt.client_pt_telepon,
		dodd2.sku_qty,
		dodd2.sku_qty*dodd2.sku_harga_satuan as jumlah,
		dodd2.sku_harga_nett
		FROM delivery_order_detail dodd2
		LEFT JOIN delivery_order do
		  ON dodd2.delivery_order_id = do.delivery_order_id
		  left join sku on sku.sku_id = dodd2.sku_id
		  left join client_pt on client_pt.client_pt_id = do.client_pt_id
		WHERE do.delivery_order_batch_id = '$do_batch'
		GROUP BY do.delivery_order_kode,do.delivery_order_batch_id,
		dodd2.sku_nama_produk,
		dodd2.sku_harga_satuan,
		dodd2.sku_kode,
		dodd2.sku_qty,
		dodd2.sku_qty*dodd2.sku_harga_satuan,
		dodd2.sku_harga_nett,
		sku.sku_satuan,
		client_pt.client_pt_nama,dodd2.sku_disc_percent,
		client_pt.client_pt_telepon,
		client_pt.client_pt_alamat,
		do.delivery_order_tgl_buat_do,
		do.delivery_order_kirim_nama,
		do.delivery_order_kirim_alamat
		ORDER BY do.delivery_order_kode ASC");
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	// public function GetAreaWilayahSimulasi($tgl_kirim, $tipe_layanan, $tipe)
	// {
	// 	if ($tipe == "BB403FA9-007F-42CD-87F0-92CEF7B40AAD") {
	// 		$tipe = "AND dod.tipe_delivery_order_id IN ('0E626A53-82FC-4EA6-A4A2-1265279D6E1C','ADF55030-9802-4C27-9658-F9D37AA01F95', 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD') ";
	// 	} else {
	// 		$tipe = "AND dod.tipe_delivery_order_id = '$tipe'";
	// 	}

	// 	$query = $this->db->query("SELECT DISTINCT
	// 	area.area_wilayah
	//   FROM delivery_order_draft dod
	//   LEFT JOIN delivery_order_detail_draft dodd
	// 	ON dod.delivery_order_draft_id = dodd.delivery_order_draft_id
	//   LEFT JOIN area
	// 	ON area.area_kode =
	// 					   CASE
	// 						 WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
	// 						 ELSE delivery_order_draft_kirim_area
	// 					   END
	//   WHERE format(dod.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') <= '$tgl_kirim'
	//   AND NOT EXISTS (SELECT
	// 	NULL
	//   FROM delivery_order do
	//   WHERE do.delivery_order_draft_id = dod.delivery_order_draft_id)
	//   AND dod.depo_id = '" . $this->session->userdata('depo_id') . "'
	//   AND dod.delivery_order_draft_status = 'Approved'
	//   $tipe
	//   AND dod.delivery_order_draft_tipe_layanan = '" . $tipe_layanan . "'");

	// 	if ($query->num_rows() == 0) {
	// 		$query = 0;
	// 	} else {
	// 		$query = $query->result_array();
	// 	}

	// 	return $query;
	// }

	public function GetAreaSimulasi($tgl_kirim, $tipe_layanan, $tipe, $segmentasi1, $segmentasi2, $segmentasi3, $mode, $delivery_order_batch_id)
	{
		if ($tipe == "BB403FA9-007F-42CD-87F0-92CEF7B40AAD") {
			$tipe = "AND dod.tipe_delivery_order_id IN ('0E626A53-82FC-4EA6-A4A2-1265279D6E1C','ADF55030-9802-4C27-9658-F9D37AA01F95', 'C5BE83E2-01E8-4E24-B766-26BB4158F2CD', 'EE5CD3F7-F7E3-475E-A7B4-67FD5AB65975') ";
		} else {
			$tipe = "AND dod.tipe_delivery_order_id = '$tipe'";
		}

		if ($segmentasi1 != '') {
			$str = '';
			foreach ($segmentasi1 as $key => $value) {
				$str .= "'$value',";
			}

			$str = rtrim($str, ',');

			$segmentasi1 = "AND client_pt_segmen_id1 IN ($str)";
		} else {
			$segmentasi1 = '';
		}

		if ($segmentasi2 != '') {
			$str = '';
			foreach ($segmentasi2 as $key => $value) {
				$str .= "'$value',";
			}

			$str = rtrim($str, ',');

			$segmentasi2 = "AND client_pt_segmen_id2 IN ($str)";
		} else {
			$segmentasi2 = '';
		}

		if ($segmentasi3 != '') {
			$str = '';
			foreach ($segmentasi3 as $key => $value) {
				$str .= "'$value',";
			}

			$str = rtrim($str, ',');

			$segmentasi3 = "AND client_pt_segmen_id3 IN ($str)";
		} else {
			$segmentasi3 = '';
		}

		if ($mode == 'edit') {
			$union = "UNION
			SELECT
			  c.area_id,
			  c.area_wilayah,
			  tdo.tipe_delivery_order_nama,
			  CASE
				WHEN a.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_area
				ELSE delivery_order_kirim_area
			  END area_nama,
			  COUNT(DISTINCT a.client_pt_id) AS jml_outlet,
			  COUNT(DISTINCT a.delivery_order_draft_id) AS jml_do,
			  SUM(ABS(CAST(ISNULL(b.sku_weight, 0) AS int) * CAST(ISNULL(b.sku_qty, 0) AS int))) AS sku_weight,
			  SUM(ABS(CAST(ISNULL(b.sku_volume, 0) AS int) * CAST(ISNULL(b.sku_qty, 0) AS int))) AS sku_volume
			FROM delivery_order a
			LEFT JOIN tipe_delivery_order tdo
			  ON a.tipe_delivery_order_id = tdo.tipe_delivery_order_id
			LEFT JOIN delivery_order_detail b
			  ON a.delivery_order_id = b.delivery_order_id
			LEFT JOIN area c
			  ON c.area_kode =
							  CASE
								WHEN a.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_area
								ELSE delivery_order_kirim_area
							  END
			WHERE a.delivery_order_batch_id = '$delivery_order_batch_id'
			GROUP BY c.area_id,
					 c.area_wilayah,
					 tdo.tipe_delivery_order_nama,
					 CASE
					   WHEN a.delivery_order_tipe_layanan = 'Pickup Only' THEN delivery_order_ambil_area
					   ELSE delivery_order_kirim_area
					 END";
		} else {
			$union = "";
		}

		$query = $this->db->query("SELECT
		area.area_id,
		area.area_wilayah,
		tdo.tipe_delivery_order_nama,
		CASE
		  WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
		  ELSE delivery_order_draft_kirim_area
		END area_nama,
		COUNT(DISTINCT dod.client_pt_id) AS jml_outlet,
		COUNT(DISTINCT dod.delivery_order_draft_id) AS jml_do,
		SUM(ABS(CAST(ISNULL(dodd.sku_weight, 0) AS int) * CAST(ISNULL(dodd.sku_qty, 0) AS int))) AS sku_weight,
		SUM(ABS(CAST(ISNULL(dodd.sku_volume, 0) AS int) * CAST(ISNULL(dodd.sku_qty, 0) AS int))) AS sku_volume
	  FROM delivery_order_draft dod
	  LEFT JOIN tipe_delivery_order tdo
		ON dod.tipe_delivery_order_id = tdo.tipe_delivery_order_id
	  LEFT JOIN delivery_order_detail_draft dodd
		ON dod.delivery_order_draft_id = dodd.delivery_order_draft_id
	  LEFT JOIN area
		ON area.area_kode =
						   CASE
							 WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
							 ELSE delivery_order_draft_kirim_area
						   END
	  LEFT JOIN client_pt 
	    ON client_pt.client_pt_id = dod.client_pt_id
	  WHERE format(dod.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') <= '$tgl_kirim'
	  AND NOT EXISTS (SELECT
		NULL
	  FROM delivery_order do
	  WHERE do.delivery_order_draft_id = dod.delivery_order_draft_id)
	  AND dod.depo_id = '" . $this->session->userdata('depo_id') . "'
	  AND dod.delivery_order_draft_status = 'Approved'
	  $tipe
	  AND dod.delivery_order_draft_tipe_layanan = '" . $tipe_layanan . "'
	  $segmentasi1 $segmentasi2 $segmentasi3
	  GROUP BY area.area_id, area.area_wilayah, tdo.tipe_delivery_order_nama,
			   CASE
				 WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
				 ELSE delivery_order_draft_kirim_area
			   END
	   $union
	  ORDER BY CASE
		WHEN dod.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN delivery_order_draft_ambil_area
		ELSE delivery_order_draft_kirim_area
	  END ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getSegmentasi($id)
	{
		$this->db->select('client_pt_segmen_id, client_pt_segmen_nama');
		$this->db->from('client_pt_segmen');

		if ($id == null) {
			$this->db->where('client_pt_segmen_level', '1');
		} else {
			$this->db->where_in('client_pt_segmen_reff_id', $id);
		}

		$this->db->order_by('client_pt_segmen_nama', 'ASC');

		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getRitasiByDOBatch($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
		a.delivery_order_batch_h_id,
		a.kendaraan_volume_cm3_terpakai AS volumeTerpakaiBatch,
		a.kendaraan_berat_gr_terpakai AS BeratTerpakaiBatch,
		b.kendaraan_volume_cm3_terpakai AS volumeTerpakaiRitasi,
		b.kendaraan_berat_gr_terpakai AS beratTerpakaiRitasi,
		b.kendaraan_berat_gr_sisa AS beratSisaRitasi,
		b.kendaraan_volume_cm3_sisa AS volumeSisaRitasi
	  FROM delivery_order_batch a
	  LEFT JOIN delivery_order_batch_h b ON a.delivery_order_batch_h_id = b.delivery_order_batch_h_id
	  WHERE delivery_order_batch_id = '" . $delivery_order_batch_id . "'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function getCountDOBatchByRitasi($IDRitasi)
	{
		$query = $this->db->query("SELECT
		COUNT(delivery_order_batch_h_id) as jml
	  FROM delivery_order_batch 
	  WHERE delivery_order_batch_h_id = '" . $IDRitasi . "'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function getRitasi($tgl_kirim, $kendaraan, $driver, $ritasi)
	{
		$query = $this->db->query("SELECT
		FORMAT(delivery_order_batch_h_tanggal_kirim, 'yyyy-MM-dd') AS tgl_kirim,
		a.karyawan_id,
		c.karyawan_nama,
		a.kendaraan_id,
		b.kendaraan_nopol,
		b.kendaraan_model,
		a.delivery_order_batch_h_ritasi,
		a.kendaraan_berat_gr_max,
		a.kendaraan_volume_cm3_max,
		a.kendaraan_berat_gr_sisa,
		a.kendaraan_volume_cm3_sisa,
		a.kendaraan_berat_gr_terpakai,
		a.kendaraan_volume_cm3_terpakai
	  FROM delivery_order_batch_h a
	  LEFT JOIN kendaraan b
		ON a.kendaraan_id = b.kendaraan_id
	  LEFT JOIN karyawan c
		ON a.karyawan_id = c.karyawan_id
	  WHERE format(a.delivery_order_batch_h_tanggal_kirim, 'dd-MM-yyyy') = '$tgl_kirim'
	  AND a.kendaraan_id = '$kendaraan'
	  AND a.karyawan_id = '$driver'
	  AND a.delivery_order_batch_h_ritasi = '$ritasi'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function checkRitasi($delivery_order_batch_tanggal_kirim, $karyawan_id, $kendaraan_id, $ritasi, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max, $kendaraan_volume_cm3_terpakai, $kendaraan_berat_gr_terpakai, $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa)
	{
		$query = $this->db->query("SELECT delivery_order_batch_h_id, kendaraan_volume_cm3_terpakai, kendaraan_berat_gr_terpakai
		FROM delivery_order_batch_h
		WHERE format(delivery_order_batch_h_tanggal_kirim, 'yyyy-MM-dd') = '" . date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_batch_tanggal_kirim))) . "'
		AND karyawan_id = '$karyawan_id'
		AND kendaraan_id = '$kendaraan_id'
		AND delivery_order_batch_h_ritasi = '$ritasi'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function insertRitasi($dobhID, $delivery_order_batch_tanggal_kirim, $karyawan_id, $kendaraan_id, $ritasi, $kendaraan_volume_cm3_max, $kendaraan_berat_gr_max, $kendaraan_volume_cm3_terpakai, $kendaraan_berat_gr_terpakai, $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa)
	{
		$this->db->set("delivery_order_batch_h_id", $dobhID);
		$this->db->set("delivery_order_batch_h_tanggal_kirim", date('Y-m-d', strtotime(str_replace("/", "-", $delivery_order_batch_tanggal_kirim))));
		$this->db->set("karyawan_id", $karyawan_id);
		$this->db->set("kendaraan_id", $kendaraan_id);
		$this->db->set("delivery_order_batch_h_ritasi", $ritasi);
		$this->db->set("kendaraan_volume_cm3_max", $kendaraan_volume_cm3_max);
		$this->db->set("kendaraan_berat_gr_max", $kendaraan_berat_gr_max);
		$this->db->set("kendaraan_volume_cm3_terpakai", $kendaraan_volume_cm3_terpakai);
		$this->db->set("kendaraan_berat_gr_terpakai", $kendaraan_berat_gr_terpakai);
		$this->db->set("kendaraan_volume_cm3_sisa", $kendaraan_volume_cm3_sisa);
		$this->db->set("kendaraan_berat_gr_sisa", $kendaraan_berat_gr_sisa);

		$queryInsert = $this->db->insert("delivery_order_batch_h");

		return $queryInsert;
	}

	public function updateRitasi($delivery_order_batch_h_id, $kendaraan_volume_cm3_terpakai, $kendaraan_berat_gr_terpakai, $kendaraan_volume_cm3_sisa, $kendaraan_berat_gr_sisa)
	{
		$this->db->set("kendaraan_volume_cm3_terpakai", $kendaraan_volume_cm3_terpakai);
		$this->db->set("kendaraan_berat_gr_terpakai", $kendaraan_berat_gr_terpakai);
		$this->db->set("kendaraan_volume_cm3_sisa", $kendaraan_volume_cm3_sisa);
		$this->db->set("kendaraan_berat_gr_sisa", $kendaraan_berat_gr_sisa);

		$this->db->where("delivery_order_batch_h_id", $delivery_order_batch_h_id);

		$queryUpdate = $this->db->update("delivery_order_batch_h");

		return $queryUpdate;
	}
}
