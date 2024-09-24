<?php

class M_LaporanPemusnahan extends CI_Model
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

	public function GetDetail($tgl1, $tgl2, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{
		if ($client_wms == '') {
			$client_wms = "";
		} else {
			$client_wms = "AND ";
		}
		$query = $this->db->query("SELECT
									depo.depo_nama,
									FORMAT(do.delivery_order_tgl_buat_do,'yyyy-MM-dd') as tgl_do,
									do.delivery_order_tgl_aktual_kirim	,
									do.delivery_order_kode,
									isnull(so.sales_order_no_po,'') as sales_order_no,
									do.delivery_order_kirim_nama as nama_outlet,
									do.delivery_order_kirim_alamat as alamat_outlet,
									do.delivery_order_kirim_area as area,
									dod1.sku_nama_produk,
									dod1.sku_kode,
									dod1.sku_qty,
									dod1.sku_qty_kirim,
									dod1.sku_harga_nett as harga,
									--dod2.sku_expdate as ed,
									dob.delivery_order_batch_kode,
									k.karyawan_nama,
									kn.kendaraan_nopol,
									dob.delivery_order_batch_tanggal_kirim,
									do.delivery_order_status,
									isnull(r.reason_keterangan,'')	
								from delivery_order do
									LEFT JOIN delivery_order_detail dod1 on do.delivery_order_id = dod1.delivery_order_id
									LEFT JOIN delivery_order_detail2 dod2 on dod1.delivery_order_detail_id = dod2.delivery_order_detail_id
									LEFT JOIN delivery_order_batch dob on do.delivery_order_batch_id = dob.delivery_order_batch_id
									LEFT JOIN depo on depo.depo_id = do.depo_id
									LEFT JOIN sales_order so on so.sales_id = do.sales_order_id
									LEFT JOIN karyawan k on k.karyawan_id = dob.karyawan_id
									LEFT JOIN reason r on r.reason_id = do.delivery_order_reject_reason
									LEFT JOIN kendaraan kn on kn.kendaraan_id = dob.kendaraan_id
								where do.depo_id ='" . $this->session->userdata('depo_id') . "'
									AND format(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'

								GROUP BY depo.depo_nama,
									FORMAT(do.delivery_order_tgl_buat_do,'yyyy-MM-dd') ,
									do.delivery_order_tgl_aktual_kirim	,
									do.delivery_order_kode,
									so.sales_order_no_po,
									do.delivery_order_kirim_nama ,
									do.delivery_order_kirim_alamat ,
									do.delivery_order_kirim_area ,
									dod1.sku_nama_produk,
									dod1.sku_kode,
									dod1.sku_qty,
									dod1.sku_qty_kirim,
									dod1.sku_harga_nett,
									--dod2.sku_expdate ,
									dob.delivery_order_batch_kode,
									k.karyawan_nama,
									kn.kendaraan_nopol,
									dob.delivery_order_batch_tanggal_kirim,
									do.delivery_order_status,
									r.reason_keterangan

		");
	}
}
