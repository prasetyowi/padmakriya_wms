<?php

class M_LaporanPenyimpananBarangSupplier extends CI_Model
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
	public function count_all_data($tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{
		if ($client_wms_id == '') {
			$client_wms = "";
		} else {
			$client_wms = "AND tmpd.client_wms_id = '$client_wms_id' ";
		}
		if ($depo_detail_id == '') {
			$depo_detail = "";
		} else {
			$depo_detail = "and tmpd.depo_detail_id_asal ='$depo_detail_id' ";
		}
		if ($principle_id == '') {
			$principle = "";
		} else {
			$principle = "and tmpd.principle_id = '$principle_id' ";
		}
		if ($sku_kode == '') {
			$skukode = "";
		} else {
			$skukode = "and sku.sku_kode like '%$sku_kode%' ";
		}
		if ($sku_nama == '') {
			$skunama = "";
		} else {
			$skunama = "and sku.sku_nama_produk like '%$sku_nama%' ";
		}

		$depo_id = $this->session->userdata("depo_id");
		$query = $this->db->query("SELECT
									COUNT(tmpd.tr_mutasi_pallet_draft_id) AS hasil
								
								FROM tr_mutasi_pallet_draft tmpd
								LEFT JOIN depo_detail dd
									ON tmpd.depo_detail_id_asal = dd.depo_detail_id
								LEFT JOIN depo_detail dd2
									ON tmpd.depo_detail_id_tujuan = dd2.depo_detail_id
								LEFT JOIN tipe_mutasi tm
									ON tmpd.tipe_mutasi_id = tm.tipe_mutasi_id
								LEFT JOIN karyawan k
									ON tmpd.tr_mutasi_pallet_draft_nama_checker = k.karyawan_nama
								LEFT JOIN tr_mutasi_pallet_detail_draft tmpdd ON tmpdd.tr_mutasi_pallet_draft_id = tmpd.tr_mutasi_pallet_draft_id
								LEFT JOIN pallet p ON tmpdd.pallet_id = p.pallet_id
								LEFT JOIN rak_lajur_detail rld ON tmpdd.rak_lajur_detail_id_tujuan = rld.rak_lajur_detail_id
								LEFT JOIN pallet_detail pd ON p.pallet_id = pd.pallet_id
								LEFT JOIN sku ON pd.sku_id = sku.sku_id
	  
										$client_wms
										$depo_detail
										$principle
										$skukode
										$skunama
										")->row();
		return $query->hasil;
	}

	public function GetDetailNew($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{
		$search_column = "";
		$order_by_column = "";

		if ($client_wms_id == "") {
			$client_wms_id = "";
		} else {
			$client_wms_id = "AND cw.client_wms_id = '$client_wms_id'";
		}

		if ($depo_detail_id == "") {
			$depo_detail_id = "";
		} else {
			$depo_detail_id = "AND pb.depo_detail_id = '$depo_detail_id'";
		}

		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "AND pb.principle_id = '$principle_id'";
		}

		if ($sku_kode == "") {
			$sku_kode = "";
		} else {
			$sku_kode = "AND sku.sku_kode LIKE '%$sku_kode%'";
		}

		if ($sku_nama == "") {
			$sku_nama = "";
		} else {
			$sku_nama = "AND sku.sku_nama_produk LIKE '%$sku_nama%'";
		}


		$sortable_columns = array(
			'client_wms',
			'principle',
			'tgl',
			'pb_kode',
			'depo_detail_nama',
			'depo_detail_nama',
			'pallet_kode',
			'kode_produk',
			'nama_produk',
			'batch_no',
			'exp_date',
			'qty',
			'kemasan'
		);

		// Kolom yang dapat dicari
		$searchable_columns = array(
			"cw.client_wms_nama",
			"principle.principle_kode",
			"FORMAT(pb.penerimaan_pembelian_tgl, 'dd-MM-yyyy')",
			"pb.penerimaan_pembelian_kode",
			"ISNULL(dd.depo_detail_nama,'')",
			"ISNULL(p.pallet_kode,'')",
			"ISNULL(sku.sku_kode,'')",
			"ISNULL(sku.sku_nama_produk,'')",
			"ISNULL(pbdtl2d.batch_no,'')",
			"pbdtl2d.sku_stock_expired_date",
			"ISNULL(pbdtl2d.sku_stock_qty,0)",
			"ISNULL(sku.sku_kemasan,'')"
		);
		// Cari
		if (!empty($search_value)) {

			$search_column = " AND (pb.penerimaan_pembelian_id IS NOT NULL ";
			foreach ($searchable_columns as $column) {
				$search_column .= " OR " . $column . " LIKE '%$search_value%'";
			}
			$search_column .= ")";
		}

		// Urutkan
		if ($order_column <= count($sortable_columns)) {
			$order_by_column = "ORDER BY " . $sortable_columns[$order_column] . " $order_dir";
		} else {
			$order_by_column = "ORDER BY pb.penerimaan_pembelian_tgl,pb.penerimaan_pembelian_kode ASC";
		}

		$depo_id = $this->session->userdata("depo_id");

		$query = $this->db->query("SELECT pb.penerimaan_pembelian_id AS pb_id,
									pb.penerimaan_pembelian_kode AS pb_kode,
									FORMAT(pb.penerimaan_pembelian_tgl, 'dd-MM-yyyy') AS tgl,
									pb.penerimaan_pembelian_status AS status_mutasi,
									pb.principle_id,
									principle.principle_kode AS principle,
									cw.client_wms_nama AS client_wms,
									pb.depo_id,
									pb.depo_detail_id,
									ISNULL(d.depo_nama,'') AS depo_nama,
									ISNULL(dd.depo_detail_nama,'') AS depo_detail_nama,
									ISNULL(p.pallet_kode,'') AS pallet_kode,
									pbdtl2d.sku_id,
									ISNULL(sku.sku_nama_produk,'') AS nama_produk,
									ISNULL(sku.sku_kode,'') AS kode_produk,
									pbdtl2d.sku_stock_expired_date AS exp_date,
									ISNULL(pbdtl2d.batch_no,'') AS batch_no,
									ISNULL(pbdtl2d.sku_stock_qty,0) AS qty,
									ISNULL(sku.sku_kemasan,'') AS kemasan
								FROM penerimaan_pembelian pb
								LEFT JOIN penerimaan_pembelian_detail2d pbdtl2d ON pbdtl2d.penerimaan_pembelian_id = pb.penerimaan_pembelian_id
								LEFT JOIN pallet p ON pbdtl2d.pallet_id = p.pallet_id
								LEFT JOIN client_wms cw ON pb.client_wms_id = cw.client_wms_id
								LEFT JOIN principle ON pb.principle_id = principle.principle_id
								LEFT JOIN rak_lajur_detail_pallet rldp ON rldp.pallet_id = p.pallet_id
								LEFT JOIN rak_lajur_detail rld ON rld.rak_lajur_detail_id = rldp.rak_lajur_detail_id
								LEFT JOIN sku ON pbdtl2d.sku_id = sku.sku_id
								LEFT JOIN depo d ON d.depo_id = pb.depo_id
								LEFT JOIN depo_detail dd ON dd.depo_detail_id = pb.depo_detail_id
								WHERE pb.depo_id = '$depo_id'
								AND FORMAT(pb.penerimaan_pembelian_tgl, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
								" . $client_wms_id . "
								" . $depo_detail_id . "
								" . $principle_id . "
								" . $sku_kode . "
								" . $sku_nama . "
								" . $order_by_column . " 
								OFFSET $start ROWS FETCH NEXT $length ROWS ONLY")->result_array();
		return $query;
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
		$query = $this->db->query("exec report_transaksi_penyimpanan_barang_supplier $start,$length,$order_column,'$order_dir','$tgl_1','$tgl_2','$client_wms_id','$depo_id','$depo_detail_id','$principle_id','$sku_kode','$sku_nama','$tgl1','$tgl2'");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}
}
