<?php

class M_LaporanMutasiBarang extends CI_Model
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

	// public function GetDetail($tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	// {
	// 	if ($client_wms_id == '') {
	// 		$client_wms = "";
	// 	} else {
	// 		$client_wms = "AND tr_mutasi_pallet.client_wms_id = '$client_wms_id' ";
	// 	}
	// 	if ($depo_detail_id == '') {
	// 		$depo_detail = "";
	// 	} else {
	// 		$depo_detail = "and tr_mutasi_pallet.depo_detail_asal ='$depo_detail_id' ";
	// 	}
	// 	if ($principle_id == '') {
	// 		$principle = "";
	// 	} else {
	// 		$principle = "and tr_mutasi_pallet.principle_id = '$principle_id' ";
	// 	}
	// 	if ($sku_kode == '') {
	// 		$skukode = "";
	// 	} else {
	// 		$skukode = "sku.sku_kode like '%$sku_kode%' ";
	// 	}
	// 	if ($sku_nama == '') {
	// 		$skunama = "";
	// 	} else {
	// 		$skunama = "sku.sku_nama_produk like '%$sku_nama%' ";
	// 	}
	// 	$depo_id = $this->session->userdata("depo_id");

	// 	$query = $this->db->query("SELECT
	// 									tr_mutasi_pallet.depo_id_asal as depo_id,
	// 									depo.depo_nama,
	// 									FORMAT(tr_mutasi_pallet.tr_mutasi_pallet_tgl_create,'yyyy-MM-dd') as tgl_mutasi_pallet,
	// 									tr_mutasi_pallet.tr_mutasi_pallet_kode as kode,
	// 									tipe_mutasi.tipe_mutasi_nama AS tipe_mutasi,
	// 									tr_mutasi_pallet.tr_mutasi_pallet_status as status_mutasi,
	// 									gudang_asal.depo_detail_nama as gudang_asal,
	// 									gudang_tujuan.depo_detail_nama as gudang_tujuan,
	// 									client_wms.client_wms_nama as nama_perusahaan,
	// 									principle.principle_nama,
	// 									tr_mutasi_pallet.tr_mutasi_pallet_nama_checker as checker,
	// 									tr_mutasi_pallet_detail.tr_mutasi_pallet_detail_id,
	// 									tr_mutasi_pallet_detail.tr_mutasi_pallet_id,
	// 									tr_mutasi_pallet_detail.is_valid,
	// 									tr_mutasi_pallet_detail.pallet_id,
	// 									pallet.pallet_kode,
	// 									rak_lajur.rak_lajur_nama as lokasi_rak_asal,
	// 									rak_lajur_detail.rak_lajur_detail_nama as lokasi_bin_asal,
	// 									rak_lajur_detail_tujuan.rak_lajur_detail_nama AS lokasi_bin_tujuan,
	// 									sku.sku_kode,
	// 									sku.sku_nama_produk,
	// 									sku.sku_satuan,
	// 									pallet_detail.sku_stock_expired_date,
	// 									--pallet_detail.sku_stock_qty
	// 									ISNULL(pallet_detail.sku_stock_qty,0) - ISNULL(pallet_detail.sku_stock_ambil,0) + ISNULL(pallet_detail.sku_stock_in,0) - ISNULL(pallet_detail.sku_stock_out,0) + ISNULL(pallet_detail.sku_stock_terima,0) AS sku_stock_qty


	// 								FROM tr_mutasi_pallet
	// 									LEFT JOIN depo on depo.depo_id = tr_mutasi_pallet.depo_id_asal
	// 									LEFT JOIN depo_detail gudang_asal
	// 									ON tr_mutasi_pallet.depo_detail_id_asal = gudang_asal.depo_detail_id
	// 									LEFT JOIN depo_detail gudang_tujuan
	// 									ON tr_mutasi_pallet.depo_detail_id_tujuan = gudang_tujuan.depo_detail_id
	// 									LEFT JOIN client_wms
	// 									ON tr_mutasi_pallet.client_wms_id = client_wms.client_wms_id
	// 									LEFT JOIN principle
	// 									ON tr_mutasi_pallet.principle_id = principle.principle_id
	// 									LEFT JOIN tipe_mutasi
	// 									ON tipe_mutasi.tipe_mutasi_id = tr_mutasi_pallet.tipe_mutasi_id
	// 									LEFT JOIN tr_mutasi_pallet_detail
	// 									ON tr_mutasi_pallet_detail.tr_mutasi_pallet_id = tr_mutasi_pallet.tr_mutasi_pallet_id
	// 									LEFT JOIN pallet
	// 									ON tr_mutasi_pallet_detail.pallet_id = pallet.pallet_id
	// 									LEFT JOIN pallet_jenis
	// 									ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
	// 									LEFT JOIN rak_lajur_detail
	// 									ON rak_lajur_detail.rak_lajur_detail_id = tr_mutasi_pallet_detail.rak_lajur_detail_id_asal
	// 									LEFT JOIN rak_lajur_detail AS rak_lajur_detail_tujuan
	// 									ON rak_lajur_detail_tujuan.rak_lajur_detail_id = tr_mutasi_pallet_detail.rak_lajur_detail_id_tujuan
	// 									LEFT JOIN rak_lajur
	// 									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
	// 									LEFT JOIN pallet_detail
	// 									ON pallet_detail.pallet_id =tr_mutasi_pallet_detail.pallet_id
	// 									left join sku	
	// 									ON sku.sku_id = pallet_detail.sku_id
	// 									LEFT JOIN rak
	// 									ON rak_lajur.rak_id = rak.rak_id

	// 								WHERE tr_mutasi_pallet.depo_id_asal ='$depo_id'
	// 								AND FORMAT(tr_mutasi_pallet.tr_mutasi_pallet_tgl_create,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
	// 									$client_wms
	// 									$depo_detail
	// 									$principle
	// 									$skukode
	// 									$skunama
	// 									ORDER BY tr_mutasi_pallet.tr_mutasi_pallet_kode ASC
	// 	");

	// 	if ($query->num_rows() == 0) {
	// 		return [];
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }
	public function GetDetailCount($tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{
		if ($client_wms_id == '') {
			$client_wms = "";
		} else {
			$client_wms = "AND tr_mutasi_pallet.client_wms_id = '$client_wms_id' ";
		}
		if ($depo_detail_id == '') {
			$depo_detail = "";
		} else {
			$depo_detail = "and tr_mutasi_pallet.depo_detail_asal ='$depo_detail_id' ";
		}
		if ($principle_id == '') {
			$principle = "";
		} else {
			$principle = "and tr_mutasi_pallet.principle_id = '$principle_id' ";
		}
		if ($sku_kode == '') {
			$skukode = "";
		} else {
			$skukode = "sku.sku_kode like '%$sku_kode%' ";
		}
		if ($sku_nama == '') {
			$skunama = "";
		} else {
			$skunama = "sku.sku_nama_produk like '%$sku_nama%' ";
		}
		$depo_id = $this->session->userdata("depo_id");

		$query = $this->db->query("SELECT
										COALESCE(COUNT(tr_mutasi_pallet.tr_mutasi_pallet_id),0) as hasil
										
									FROM tr_mutasi_pallet
										LEFT JOIN depo on depo.depo_id = tr_mutasi_pallet.depo_id_asal
										LEFT JOIN depo_detail gudang_asal
										ON tr_mutasi_pallet.depo_detail_id_asal = gudang_asal.depo_detail_id
										LEFT JOIN depo_detail gudang_tujuan
										ON tr_mutasi_pallet.depo_detail_id_tujuan = gudang_tujuan.depo_detail_id
										LEFT JOIN client_wms
										ON tr_mutasi_pallet.client_wms_id = client_wms.client_wms_id
										LEFT JOIN principle
										ON tr_mutasi_pallet.principle_id = principle.principle_id
										LEFT JOIN tipe_mutasi
										ON tipe_mutasi.tipe_mutasi_id = tr_mutasi_pallet.tipe_mutasi_id
										LEFT JOIN tr_mutasi_pallet_detail
										ON tr_mutasi_pallet_detail.tr_mutasi_pallet_id = tr_mutasi_pallet.tr_mutasi_pallet_id
										LEFT JOIN pallet
										ON tr_mutasi_pallet_detail.pallet_id = pallet.pallet_id
										LEFT JOIN pallet_jenis
										ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
										LEFT JOIN rak_lajur_detail
										ON rak_lajur_detail.rak_lajur_detail_id = tr_mutasi_pallet_detail.rak_lajur_detail_id_asal
										LEFT JOIN rak_lajur_detail AS rak_lajur_detail_tujuan
										ON rak_lajur_detail_tujuan.rak_lajur_detail_id = tr_mutasi_pallet_detail.rak_lajur_detail_id_tujuan
										LEFT JOIN rak_lajur
										ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
										LEFT JOIN pallet_detail
										ON pallet_detail.pallet_id =tr_mutasi_pallet_detail.pallet_id
										left join sku	
										ON sku.sku_id = pallet_detail.sku_id
										LEFT JOIN rak
										ON rak_lajur.rak_id = rak.rak_id

									WHERE tr_mutasi_pallet.depo_id_asal ='$depo_id'
									AND FORMAT(tr_mutasi_pallet.tr_mutasi_pallet_tgl_create,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
										$client_wms
										$depo_detail
										$principle
										$skukode
										$skunama
										--ORDER BY tr_mutasi_pallet.tr_mutasi_pallet_kode ASC
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
		$query = $this->db->query("exec report_transaksi_mutasi_barang $start,$length,$order_column,'$order_dir','$tgl_1','$tgl_2','$client_wms_id','$depo_id','$depo_detail_id','$principle_id','$sku_kode','$sku_nama','$tgl1','$tgl2'");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}
}
