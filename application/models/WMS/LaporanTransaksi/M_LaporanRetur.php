<?php

class M_LaporanRetur extends CI_Model
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
			$depo_detail = "and dob.depo_detail_id ='$depo_detail_id' ";
		}
		if ($principle_id == '') {
			$principle = "";
		} else {
			$principle = "and do.principle_id = '$principle_id' ";
		}
		if ($sku_kode == '') {
			$skukode = "";
		} else {
			$skukode = "dod1.sku_kode like '%$sku_kode%' ";
		}
		if ($sku_nama == '') {
			$skunama = "";
		} else {
			$skunama = "dod1.sku_nama_produk like '%$sku_nama%' ";
		}
		$depo_id = $this->session->userdata("depo_id");
		$query = $this->db->query("select pb.penerimaan_pembelian_id ,pbd4.karyawan_nama,pb.penerimaan_pembelian_kode, pbd2.sku_nama from penerimaan_pembelian pb
		LEFT JOIN (SELECT  k.karyawan_nama, pb4.penerimaan_pembelian_id from penerimaan_pembelian_detail4 pb4
		left join karyawan k on k.karyawan_id = pb4.karyawan_id
				) pbd4 on pbd4.penerimaan_pembelian_id =pb.penerimaan_pembelian_id
		left join (select pbd.penerimaan_pembelian_detail_id, pbd.penerimaan_pembelian_id, pbd.batch_no,
				pbd.sku_id, pbd.sku_jumlah_barang as jml_barang, sku.sku_kode,sku.sku_nama_produk AS sku_nama, sku.sku_kemasan,sku_satuan,pbd.sku_exp_date 
				from penerimaan_pembelian_detail pbd
		left join sku on sku.sku_id = pbd.sku_id) pbd2 on pbd2.penerimaan_pembelian_id = pb.penerimaan_pembelian_id and pbd4.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id
		
		");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}
	public function count_all_data()
	{
		$query = $this->db->query("select count(pb.penerimaan_pembelian_id) as hasil from penerimaan_pembelian pb
		LEFT JOIN (SELECT  k.karyawan_nama, pb4.penerimaan_pembelian_id from penerimaan_pembelian_detail4 pb4
		left join karyawan k on k.karyawan_id = pb4.karyawan_id
				) pbd4 on pbd4.penerimaan_pembelian_id =pb.penerimaan_pembelian_id
		left join (select pbd.penerimaan_pembelian_detail_id, pbd.penerimaan_pembelian_id, pbd.batch_no,
				pbd.sku_id, pbd.sku_jumlah_barang as jml_barang, sku.sku_kode,sku.sku_nama_produk AS sku_nama, sku.sku_kemasan,sku_satuan,pbd.sku_exp_date 
				from penerimaan_pembelian_detail pbd
		left join sku on sku.sku_id = pbd.sku_id) pbd2 on pbd2.penerimaan_pembelian_id = pb.penerimaan_pembelian_id and pbd4.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id
		")->row();
		return $query->hasil;
	}

	// public function get_filtered_data($start, $length, $search_value, $order_column, $order_dir)
	// {
	// 	return $this->db->query("select pb.penerimaan_pembelian_id as penerimaan_pembelian_id, 
	// 	pbd4.karyawan_nama as karyawan_nama,
	// 	pb.penerimaan_pembelian_kode as penerimaan_pembelian_kode, 
	// 	pbd2.sku_nama as sku_nama from penerimaan_pembelian pb
	// 	LEFT JOIN (SELECT k.karyawan_nama, pb4.penerimaan_pembelian_id from penerimaan_pembelian_detail4 pb4
	// 	left join karyawan k on k.karyawan_id = pb4.karyawan_id
	// 			) pbd4 on pbd4.penerimaan_pembelian_id =pb.penerimaan_pembelian_id
	// 	left join (select pbd.penerimaan_pembelian_detail_id, pbd.penerimaan_pembelian_id, pbd.batch_no,
	// 			pbd.sku_id, pbd.sku_jumlah_barang as jml_barang, sku.sku_kode,sku.sku_nama_produk AS sku_nama, sku.sku_kemasan,sku_satuan,pbd.sku_exp_date 
	// 			from penerimaan_pembelian_detail pbd
	// 	left join sku on sku.sku_id = pbd.sku_id) pbd2 on pbd2.penerimaan_pembelian_id = pb.penerimaan_pembelian_id and pbd4.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id
	// 	")->result_array();
	// 	// Kolom yang dapat diurutkan
	// 	$sortable_columns = array(
	// 		'penerimaan_pembelian_id',
	// 		'karyawan_nama',
	// 		'penerimaan_pembelian_kode',
	// 		'sku_nama'
	// 	);

	// 	// Kolom yang dapat dicari
	// 	$searchable_columns = array(
	// 		'penerimaan_pembelian_id',
	// 		'karyawan_nama',
	// 		'penerimaan_pembelian_kode',
	// 		'sku_nama'
	// 	);

	// 	// Cari
	// 	if (!empty($search_value)) {
	// 		$search_value = $this->db->escape_str($search_value);
	// 		$this->db->group_start();
	// 		foreach ($searchable_columns as $column) {
	// 			$this->db->or_like($column, $search_value);
	// 		}
	// 		$this->db->group_end();
	// 	}

	// 	// Urutkan
	// 	if ($order_column <= count($sortable_columns)) {
	// 		$this->db->order_by($sortable_columns[$order_column], $order_dir);
	// 	}
	// }

	public function get_filtered_data($start, $length, $search_value, $order_column, $order_dir)
	{
		$this->db->select('pb.penerimaan_pembelian_id as penerimaan_pembelian_id, 
        pbd4.karyawan_nama as karyawan_nama,
        pb.penerimaan_pembelian_kode as penerimaan_pembelian_kode, 
        pbd2.sku_nama as sku_nama');
		$this->db->from('penerimaan_pembelian pb');
		$this->db->join('(SELECT k.karyawan_nama, pb4.penerimaan_pembelian_id from penerimaan_pembelian_detail4 pb4
        left join karyawan k on k.karyawan_id = pb4.karyawan_id) pbd4', 'pbd4.penerimaan_pembelian_id = pb.penerimaan_pembelian_id', 'left');
		$this->db->join('(select pbd.penerimaan_pembelian_detail_id, pbd.penerimaan_pembelian_id, pbd.batch_no,
                pbd.sku_id, pbd.sku_jumlah_barang as jml_barang, sku.sku_kode,sku.sku_nama_produk AS sku_nama, sku.sku_kemasan,sku_satuan,pbd.sku_exp_date 
                from penerimaan_pembelian_detail pbd
        left join sku on sku.sku_id = pbd.sku_id) pbd2', 'pbd2.penerimaan_pembelian_id = pb.penerimaan_pembelian_id and pbd4.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id', 'left');


		// Kolom yang dapat diurutkan
		$sortable_columns = array(
			// 'penerimaan_pembelian_id',
			'karyawan_nama',
			'penerimaan_pembelian_kode',
			'sku_nama'
		);

		// Kolom yang dapat dicari
		$searchable_columns = array(
			// 'penerimaan_pembelian_id',
			'karyawan_nama',
			'penerimaan_pembelian_kode',
			'sku_nama'
		);

		// Cari
		if (!empty($search_value)) {
			$this->db->group_start();
			foreach ($searchable_columns as $column) {
				$this->db->or_like($column, $search_value);
			}
			$this->db->group_end();
		}

		// Urutkan
		if ($order_column <= count($sortable_columns)) {
			$this->db->order_by($sortable_columns[$order_column], $order_dir);
		}

		$this->db->limit($length, $start);
		return $this->db->get()->result_array();
	}
}
