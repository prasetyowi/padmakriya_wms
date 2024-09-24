<?php

class M_LaporanBiayaLainPengiriman extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function Get_Driver()
	{
		$query = $this->db->query("SELECT
										*
									FROM karyawan
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									AND karyawan_divisi_id = '351F2A0C-13F4-4780-A526-428BEE343200'
									ORDER BY karyawan_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getDetailBiaya($do_batch_kode, $do_id, $tgl_kirim)
	{
		$query = $this->db->query("SELECT d.tipe_biaya_nama, a.delivery_order_detail3_nilai, ISNULL(a.delivery_order_detail3_keterangan, '') AS delivery_order_detail3_keterangan FROM delivery_order_detail3 a
					LEFT JOIN delivery_order b ON a.delivery_order_id = b.delivery_order_id
					LEFT JOIN delivery_order_batch c ON b.delivery_order_batch_id = c.delivery_order_batch_id
					LEFT JOIN tipe_biaya d ON a.tipe_biaya_id = d.tipe_biaya_id
					WHERE c.delivery_order_batch_kode = '$do_batch_kode' AND a.delivery_order_id = '$do_id' AND FORMAT(delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') = '$tgl_kirim'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
}
