<?php

class M_LaporanPengiriman extends CI_Model
{
    function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function GetDataDOByFilter($tgl) {
		$query = $this->db->query("SELECT DISTINCT
		FORMAT(delivery_order_tgl_rencana_kirim, 'yyyy-MM-dd') AS tgl,
		delivery_order_status AS status
	  FROM delivery_order
	  WHERE FORMAT(delivery_order_tgl_rencana_kirim, 'yyyy-MM-dd') = '$tgl'
	  AND delivery_order_status IN ('delivered', 'not delivered', 'rescheduled', 'partially delivered')
	  AND depo_id = '".$this->session->userdata('depo_id')."'");

	  if ($query->num_rows() == 0) {
		$query = 0;
	  } else {
		$query = $query->result_array();
	  }

	  return $query;
	}

	public function GetDataDOByStatusTgl($tgl, $status) {
		$query = $this->db->query("SELECT
		FORMAT(delivery_order_tgl_rencana_kirim, 'yyyy-MM-dd') AS tgl,
		c.karyawan_nama as pengemudi,
		ISNULL(a.delivery_order_kode, '') as do,
		ISNULL(d.sales_order_kode, '') as so_fas,
		ISNULL(d.sales_order_no_po, '') as so,
		a.delivery_order_status as status_do,
		CASE WHEN a.delivery_order_tipe_pembayaran = 1 THEN 'NON TUNAI' ELSE 'TUNAI' END AS pembayaran,
		a.delivery_order_jumlah_bayar as jumlah_bayar
	  FROM delivery_order a
	  LEFT JOIN delivery_order_batch b
		ON a.delivery_order_batch_id = b.delivery_order_batch_id
	  LEFT JOIN karyawan c
		ON b.karyawan_id = c.karyawan_id
	  LEFT JOIN sales_order d
		ON a.sales_order_id = d.sales_order_id
	  WHERE FORMAT(a.delivery_order_tgl_rencana_kirim, 'yyyy-MM-dd') = '$tgl'
	  AND a.delivery_order_status = '$status'
	  AND a.depo_id = '".$this->session->userdata('depo_id')."'
	  ORDER BY a.delivery_order_kode");
	  
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDataDOByStatusTgl2($tgl, $status) {
		$query = $this->db->query("SELECT
		FORMAT(delivery_order_tgl_rencana_kirim, 'yyyy-MM-dd') AS tgl,
		c.karyawan_nama as pengemudi,
		ISNULL(a.delivery_order_kode, '') AS do,
		ISNULL(d.sales_order_kode, '') as so_fas,
		ISNULL(d.sales_order_no_po, '') as so,
		e.sku_nama_produk as sku,
		ISNULL(sku_qty, 0) AS sku_qty,
		ISNULL(sku_qty_kirim, 0) AS sku_qty_kirim,
		(ISNULL(sku_qty, 0) - ISNULL(sku_qty_kirim, 0)) AS selisih
	  FROM delivery_order a
	  LEFT JOIN delivery_order_batch b
		ON a.delivery_order_batch_id = b.delivery_order_batch_id
	  LEFT JOIN karyawan c
		ON b.karyawan_id = c.karyawan_id
	  LEFT JOIN sales_order d
		ON a.sales_order_id = d.sales_order_id
	  LEFT JOIN delivery_order_detail e
		ON a.delivery_order_id = e.delivery_order_id
	  WHERE FORMAT(a.delivery_order_tgl_rencana_kirim, 'yyyy-MM-dd') = '$tgl'
	  AND a.delivery_order_status = '$status'
	  AND a.depo_id = '".$this->session->userdata('depo_id')."'
	  ORDER BY a.delivery_order_kode");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
}
?>