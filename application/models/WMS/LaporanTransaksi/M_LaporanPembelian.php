<?php

class M_LaporanPembelian extends CI_Model
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
	public function GetDetail($tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{
		if ($client_wms_id == '') {
			$client_wms = "";
		} else {
			$client_wms = "AND do.client_wms_id = '$client_wms_id' ";
		}
		if ($depo_detail_id == '') {
			$depo_detail = "";
		} else {
			$depo_detail = "and pb.depo_detail_id ='$depo_detail_id' ";
		}
		if ($principle_id == '') {
			$principle = "";
		} else {
			$principle = "and pb.principle_id = '$principle_id' ";
		}
		if ($sku_kode == '') {
			$skukode = "";
		} else {
			$skukode = "and pbd2.sku_kode like '%$sku_kode%' ";
		}
		if ($sku_nama == '') {
			$skunama = "";
		} else {
			$skunama = "and pbd2.sku_nama_produk like '%$sku_nama%' ";
		}
		$depo_id = $this->session->userdata("depo_id");
		$query = $this->db->query("	
								SELECT
								pb.penerimaan_pembelian_id,
								client_wms.client_wms_nama AS pt,
								principle.principle_nama,
								pb.penerimaan_pembelian_kode AS kodepenerimaanbarang,
								pb.penerimaan_pembelian_tgl_create AS tgl_pb,
								ISNULL(pbd4.karyawan_nama,'') as karyawan_nama,
								ISNULL(pb.penerimaan_pembelian_kode,'') as penerimaan_pembelian_kode,
								ISNULL(pbd2.sku_nama,'') as sku_nama,
								ISNULL(pbd2.sku_kode,'')as sku_kode, 
								ISNULL(pbd2.batch_no,'') as batch_no,
								ISNULL(pbd2.sku_exp_date,'') as sku_exp_date,
								ISNULL(pbd2.jml_barang,'')as jml_barang,
								pb.penerimaan_pembelian_tgl AS jam_mulai_bongkar,
								'' AS kondisi_barang,
								pbd3.sj_jumlah_barang,
								ISNULL(pbd3.sku_jumlah_barang_terima,'')as sku_jumlah_barang_terima,
								pbd2.jml_barang AS sjd_jumlah_barang,
								pbd3.sku_kemasan,
								pbd3.tgl_sj,
								principle.principle_kode
							
							FROM penerimaan_pembelian pb
							LEFT JOIN (SELECT
								k.karyawan_nama,
								pb4.penerimaan_pembelian_id
							FROM penerimaan_pembelian_detail4 pb4
							LEFT JOIN karyawan k
								ON k.karyawan_id = pb4.karyawan_id) pbd4
								ON pbd4.penerimaan_pembelian_id = pb.penerimaan_pembelian_id
							LEFT JOIN (SELECT
								pbd.penerimaan_pembelian_detail_id,
								pbd.penerimaan_pembelian_id,
								pbd.batch_no,
								pbd.sku_id,
								pbd.sku_jumlah_barang AS jml_barang,
								sku.sku_kode,
								sku.sku_nama_produk AS sku_nama,
								sku.sku_kemasan,
								sku_satuan,
								pbd.sku_exp_date
							
							FROM penerimaan_pembelian_detail pbd
							LEFT JOIN sku
								ON sku.sku_id = pbd.sku_id) pbd2
								ON pbd2.penerimaan_pembelian_id = pb.penerimaan_pembelian_id
							-- AND pbd4.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id
							LEFT JOIN client_wms
								ON client_wms.client_wms_id = pb.client_wms_id
							LEFT JOIN principle
								ON principle.principle_id = pb.principle_id
							LEFT JOIN (SELECT
								sjd.sku_jumlah_barang AS sj_jumlah_barang,
								sj.penerimaan_surat_jalan_tgl_create AS tgl_sj,
								pbd3.penerimaan_pembelian_id,
								sjd.sku_id,
								sjd.sku_jumlah_barang_terima,
								sku.sku_kemasan
							FROM penerimaan_pembelian_detail3 pbd3
							LEFT JOIN penerimaan_surat_jalan sj
								ON sj.penerimaan_surat_jalan_id = pbd3.penerimaan_surat_jalan_id
							LEFT JOIN penerimaan_surat_jalan_detail sjd
								ON sjd.penerimaan_surat_jalan_id = sj.penerimaan_surat_jalan_id
								AND sjd.penerimaan_surat_jalan_id = pbd3.penerimaan_surat_jalan_id
							LEFT JOIN sku
								ON sku.sku_id = sjd.sku_id) pbd3
								ON pb.penerimaan_pembelian_id = pbd3.penerimaan_pembelian_id
								AND pbd2.sku_id = pbd3.sku_id
							WHERE pb.depo_id = '$depo_id'
							AND format(pb.penerimaan_pembelian_tgl_create, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
								$client_wms
										$depo_detail
										$principle
										$skukode
										$skunama
		");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}
	public function GetDetailCount($tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{
		if ($client_wms_id == '') {
			$client_wms = "";
		} else {
			$client_wms = "AND do.client_wms_id = '$client_wms_id' ";
		}
		if ($depo_detail_id == '') {
			$depo_detail = "";
		} else {
			$depo_detail = "and pb.depo_detail_id ='$depo_detail_id' ";
		}
		if ($principle_id == '') {
			$principle = "";
		} else {
			$principle = "and pb.principle_id = '$principle_id' ";
		}
		if ($sku_kode == '') {
			$skukode = "";
		} else {
			$skukode = "and pbd2.sku_kode like '%$sku_kode%' ";
		}
		if ($sku_nama == '') {
			$skunama = "";
		} else {
			$skunama = "and pbd2.sku_nama_produk like '%$sku_nama%' ";
		}
		$depo_id = $this->session->userdata("depo_id");
		$query = $this->db->query("SELECT
								COALESCE(COUNT(pb.penerimaan_pembelian_id),0) as hasil
								
							
							FROM penerimaan_pembelian pb
							LEFT JOIN (SELECT
								k.karyawan_nama,
								pb4.penerimaan_pembelian_id
							FROM penerimaan_pembelian_detail4 pb4
							LEFT JOIN karyawan k
								ON k.karyawan_id = pb4.karyawan_id) pbd4
								ON pbd4.penerimaan_pembelian_id = pb.penerimaan_pembelian_id
							LEFT JOIN (SELECT
								pbd.penerimaan_pembelian_detail_id,
								pbd.penerimaan_pembelian_id,
								pbd.batch_no,
								pbd.sku_id,
								pbd.sku_jumlah_barang AS jml_barang,
								sku.sku_kode,
								sku.sku_nama_produk AS sku_nama,
								sku.sku_kemasan,
								sku_satuan,
								pbd.sku_exp_date
							
							FROM penerimaan_pembelian_detail pbd
							LEFT JOIN sku
								ON sku.sku_id = pbd.sku_id) pbd2
								ON pbd2.penerimaan_pembelian_id = pb.penerimaan_pembelian_id
							-- AND pbd4.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id
							LEFT JOIN client_wms
								ON client_wms.client_wms_id = pb.client_wms_id
							LEFT JOIN principle
								ON principle.principle_id = pb.principle_id
							LEFT JOIN (SELECT
								sjd.sku_jumlah_barang AS sj_jumlah_barang,
								sj.penerimaan_surat_jalan_tgl_create AS tgl_sj,
								pbd3.penerimaan_pembelian_id,
								sjd.sku_id,
								sjd.sku_jumlah_barang_terima,
								sku.sku_kemasan
							FROM penerimaan_pembelian_detail3 pbd3
							LEFT JOIN penerimaan_surat_jalan sj
								ON sj.penerimaan_surat_jalan_id = pbd3.penerimaan_surat_jalan_id
							LEFT JOIN penerimaan_surat_jalan_detail sjd
								ON sjd.penerimaan_surat_jalan_id = sj.penerimaan_surat_jalan_id
								AND sjd.penerimaan_surat_jalan_id = pbd3.penerimaan_surat_jalan_id
							LEFT JOIN sku
								ON sku.sku_id = sjd.sku_id) pbd3
								ON pb.penerimaan_pembelian_id = pbd3.penerimaan_pembelian_id
								AND pbd2.sku_id = pbd3.sku_id
							WHERE pb.depo_id = '$depo_id'
							AND format(pb.penerimaan_pembelian_tgl_create, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
								$client_wms
										$depo_detail
										$principle
										$skukode
										$skunama
		");

		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return $query->row()->hasil;
		}
	}
	public function GetDetailNewProcedure($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2)
	{
		$start = $start == '' ? 0 : $start;
		$length = $length == '' ? 0 : $length;
		// $search_value = $search_value == '' ? NULL : $search_value;
		$order_column = $order_column == '' ? 0 : $order_column;
		$order_dir = $order_dir == '' ? NULL : $order_dir;
		$tgl1 = $tgl1 == '' ? NULL : $tgl1;
		$tgl2 = $tgl2 == '' ? NULL : $tgl2;
		$client_wms_id = $client_wms_id == '' ? NULL : $client_wms_id;
		$depo_id = $depo_id == '' ? NULL : $depo_id;
		$depo_detail_id = $depo_detail_id == '' ? NULL : $depo_detail_id;
		$principle_id = $principle_id == '' ? NULL : $principle_id;
		$sku_kode = $sku_kode == '' ? NULL : $sku_kode;
		$sku_nama = $sku_nama == '' ? NULL : $sku_nama;
		$tgl_1 = $tgl_1 == '' ? NULL : $tgl_1;
		$tgl_2 = $tgl_2 == '' ? NULL : $tgl_2;
		$query = $this->db->query("exec report_transaksi_pembelian $start,$length,$order_column,'$order_dir','$tgl_1','$tgl_2','$client_wms_id','$depo_id','$depo_detail_id','$principle_id','$sku_kode','$sku_nama','$tgl1','$tgl2'");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}
}
