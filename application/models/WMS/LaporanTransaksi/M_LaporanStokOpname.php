<?php

class M_LaporanStokOpname extends CI_Model
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
			$client_wms = "AND trp.client_wms_id = '$client_wms_id' ";
		}
		if ($depo_detail_id == '') {
			$depo_detail = "";
		} else {
			$depo_detail = "and trp.depo_detail_id ='$depo_detail_id' ";
		}
		if ($principle_id == '') {
			$principle = "";
		} else {
			$principle = "and trp.principle_id = '$principle_id' ";
		}
		if ($sku_kode == '') {
			$skukode = "";
		} else {
			$skukode = "f.sku_kode like '%$sku_kode%' ";
		}
		if ($sku_nama == '') {
			$skunama = "";
		} else {
			$skunama = "f.sku_nama_produk like '%$sku_nama%' ";
		}
		$depo_id = $this->session->userdata("depo_id");
		$query = $this->db->query("SELECT
										trp.tr_opname_plan_id,
										depo.depo_nama,
										FORMAT(trp.tr_opname_plan_tgl_create, 'yyyy') AS tahun,
										FORMAT(trp.tr_opname_plan_tgl_create, 'MM') AS bulan,
										FORMAT(trp.tr_opname_plan_tgl_create, 'dd-MM-yyyy') AS tanggal,
										isnull(cl.client_wms_nama,'ALL') as client_wms_nama,
										isnull(p.principle_nama,'ALL') as principle_nama,
										trp.tr_opname_plan_status,
										isnull(tp.tipe_opname_nama,'') as tipe_opname_nama,
										isnull(trp.tipe_stok,'') as tipe_stok,
										k.karyawan_nama,
										trp.tr_opname_plan_kode,
										isnull(dp.depo_detail_nama,'') as depo_detail_nama ,
										d.rak_lajur_detail_nama AS nama_detail_rak,
										isnull(c.pallet_kode,'') as pallet_kode,
										isnull(f.sku_kode,'') as sku_kode,
										isnull(f.sku_nama_produk,'') as sku_nama_produk,
										isnull(f.sku_kemasan,'') as sku_kemasan,
										isnull(f.sku_satuan,'') as sku_satuan,
										ISNULL(b.sku_batch_no, '') AS sku_batch_no,
										format(b.sku_expired_date, 'dd-MM-yyyy') AS sku_expired_date,
										--b.sku_actual_qty_opname
										a.tr_opname_plan_detail2_status,
										isnull(f.sku_kode_sku_principle,'') as sku_kode_sku_principle,
										b.sku_actual_qty_opname

	 								 FROM tr_opname_plan trp
										LEFT JOIN depo
											ON depo.depo_id = trp.depo_id
										LEFT JOIN client_wms cl
											ON cl.client_wms_id = trp.client_wms_id
										LEFT JOIN principle p
											ON p.principle_id = trp.principle_id
										LEFT JOIN tipe_opname tp
											ON tp.tipe_opname_id = trp.tipe_opname_id
										LEFT JOIN karyawan k
											ON k.karyawan_id = trp.karyawan_id_penanggungjawab
										LEFT JOIN depo_detail dp
											ON dp.depo_detail_id = trp.depo_detail_id
										LEFT JOIN tr_opname_plan_detail2 a
											ON trp.tr_opname_plan_id = a.tr_opname_plan_id
										LEFT JOIN tr_opname_plan_detail3 b
											ON a.tr_opname_plan_detail2_id = b.tr_opname_plan_detail2_id
										LEFT JOIN pallet c
											ON a.pallet_id = c.pallet_id
										LEFT JOIN rak_lajur_detail d
											ON c.rak_lajur_detail_id = d.rak_lajur_detail_id
										LEFT JOIN rak_lajur e
											ON e.rak_lajur_id = d.rak_lajur_id
										LEFT JOIN sku f
											ON b.sku_id = f.sku_id
	  
										where trp.depo_id = '$depo_id'
											AND format(trp.tr_opname_plan_tgl_create, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
											
											$client_wms
											$depo_detail
											$principle
											$skukode
											$skunama
										order by trp.tr_opname_plan_kode
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
			$client_wms = "AND trp.client_wms_id = '$client_wms_id' ";
		}
		if ($depo_detail_id == '') {
			$depo_detail = "";
		} else {
			$depo_detail = "and trp.depo_detail_id ='$depo_detail_id' ";
		}
		if ($principle_id == '') {
			$principle = "";
		} else {
			$principle = "and trp.principle_id = '$principle_id' ";
		}
		if ($sku_kode == '') {
			$skukode = "";
		} else {
			$skukode = "f.sku_kode like '%$sku_kode%' ";
		}
		if ($sku_nama == '') {
			$skunama = "";
		} else {
			$skunama = "f.sku_nama_produk like '%$sku_nama%' ";
		}
		$depo_id = $this->session->userdata("depo_id");
		$query = $this->db->query("SELECT
										COALESCE(COUNT(trp.tr_opname_plan_id), 0) as hasil
	 								 FROM tr_opname_plan trp
										LEFT JOIN depo
											ON depo.depo_id = trp.depo_id
										LEFT JOIN client_wms cl
											ON cl.client_wms_id = trp.client_wms_id
										LEFT JOIN principle p
											ON p.principle_id = trp.principle_id
										LEFT JOIN tipe_opname tp
											ON tp.tipe_opname_id = trp.tipe_opname_id
										LEFT JOIN karyawan k
											ON k.karyawan_id = trp.karyawan_id_penanggungjawab
										LEFT JOIN depo_detail dp
											ON dp.depo_detail_id = trp.depo_detail_id
										LEFT JOIN tr_opname_plan_detail2 a
											ON trp.tr_opname_plan_id = a.tr_opname_plan_id
										LEFT JOIN tr_opname_plan_detail3 b
											ON a.tr_opname_plan_detail2_id = b.tr_opname_plan_detail2_id
										LEFT JOIN pallet c
											ON a.pallet_id = c.pallet_id
										LEFT JOIN rak_lajur_detail d
											ON c.rak_lajur_detail_id = d.rak_lajur_detail_id
										LEFT JOIN rak_lajur e
											ON e.rak_lajur_id = d.rak_lajur_id
										LEFT JOIN sku f
											ON b.sku_id = f.sku_id
	  
										where trp.depo_id = '$depo_id'
											AND format(trp.tr_opname_plan_tgl_create, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
											
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
		$query = $this->db->query("exec report_transaksi_stock_opname $start,$length,$order_column,'$order_dir','$tgl_1','$tgl_2','$client_wms_id','$depo_id','$depo_detail_id','$principle_id','$sku_kode','$sku_nama','$tgl1','$tgl2'");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}
}
