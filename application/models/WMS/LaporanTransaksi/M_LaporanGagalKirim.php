<?php

class M_LaporanGagalKirim extends CI_Model
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

	public function getDetail($start, $length, $search_value, $tgl, $driver)
	{
		$start = $start == '' ? 0 : $start;
		$length = $length == '' ? 0 : $length;
		// $search_value = $search_value == '' ? NULL : $search_value;
		$tgl = $tgl == '' ? NULL : $tgl;
		$driver = $driver == '' ? NULL : $driver;

		$query = $this->db->query("exec report_gagal_kirim $start,$length,'$driver','$tgl'");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}

	public function count_all_data($tgl, $driver)
	{
		$query = $this->db->query("SELECT distinct b.sku_id,
				a.sku_nama_produk,
				left(a.sku_kode, charindex('-', a.sku_kode)-1) as sku_kode,
				NULL as sku_kemasan,
				b.sku_kemasan,
				p.principle_kode,
				pb.principle_brand_nama,
				a.delivery_order_batch_id,
				k.karyawan_nama,
				dob.delivery_order_batch_tanggal,
				dob.delivery_order_batch_kode,
				so.sales_order_no_po,
				so.sales_order_kode,
				do.delivery_order_kode, 
				do.delivery_order_kirim_nama, 
				do.delivery_order_kirim_alamat,
				r.reason_keterangan
		    from delivery_order_detail a
	  left join delivery_order_batch dob on a.delivery_order_batch_id = dob.delivery_order_batch_id
	  inner join sku b on left(a.sku_kode, charindex('-', a.sku_kode)-1) = b.sku_konversi_group
	  left join principle p on b.principle_id = p.principle_id
	  left join principle_brand pb on b.principle_brand_id = pb.principle_brand_id
	  inner join delivery_order do on a.delivery_order_id = do.delivery_order_id
	  inner join tipe_delivery_order td on do.tipe_delivery_order_id = td.tipe_delivery_order_id
	  LEFT JOIN karyawan k ON dob.karyawan_id = k.karyawan_id
      LEFT JOIN delivery_order_detail2_gagal dod2 ON do.delivery_order_id = dod2.delivery_order_id AND a.delivery_order_detail_id = dod2.delivery_order_detail_id
	  LEFT JOIN sales_order so ON do.sales_order_id = so.sales_order_id
	  LEFT JOIN reason r on r.reason_id = dod2.reason_id
		   where dob.karyawan_id = '$driver'
			     and dob.delivery_order_batch_tanggal = '$tgl'
			     and do.delivery_order_status in ('not delivered', 'partially delivered')
		     and b.sku_konversi_faktor = 1
		order by p.principle_kode, pb.principle_brand_nama, left(a.sku_kode, charindex('-', a.sku_kode)-1)");

		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return $query->num_rows();
		}
	}
}
