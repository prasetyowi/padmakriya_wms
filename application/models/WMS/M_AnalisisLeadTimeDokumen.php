<?php

class M_AnalisisLeadTimeDokumen extends CI_Model
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

	public function count_all_data($tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2)
	{
		if ($client_wms_id == '') {
			$client_wms = "";
		} else {
			$client_wms = "AND ssc.client_wms_id = '$client_wms_id' ";
		}
		if ($depo_detail_id == '') {
			$depo_detail = "";
		} else {
			$depo_detail = "and ssc.depo_detail_id ='$depo_detail_id' ";
		}
		if ($principle_id == '') {
			$principle = "";
		} else {
			$principle = "and ssc.principle_id = '$principle_id' ";
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

		$query =  $this->db->query("SELECT count(ssc.sku_stock_card_id) as hasil FROM sku_stock_card ssc
				LEFT JOIN depo ON depo.depo_id = ssc.depo_id
				LEFT JOIN depo_detail dd ON dd.depo_detail_id = ssc.depo_detail_id
				LEFT JOIN sku ON sku.sku_id = ssc.sku_id
				LEFT JOIN principle ON principle.principle_id = sku.principle_id
				LEFT JOIN sku_stock ss ON ss.sku_stock_id = ssc.sku_stock_id AND ss.sku_id = ssc.sku_id
				LEFT JOIN (
					SELECT
						sku_id,
						SUM(CASE WHEN sku_stock_card_keterangan = 'Stock Opname' THEN sku_stock_card_qty ELSE 0 END) AS opname,
						SUM(CASE WHEN sku_stock_card_keterangan = 'Permintaan Pengeluaran Barang' THEN sku_stock_card_qty ELSE 0 END) AS penjualan,
						SUM(CASE WHEN sku_stock_card_keterangan = 'Bukti Terima Barang dari Outlet' and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS penerimaan_retur_outlet,
						SUM(CASE WHEN sku_stock_card_keterangan IN ('Mutasi Pallet Antar Gudang','Mutasi Stock') and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS mutasi_in_antar_gudang,
						SUM(CASE WHEN sku_stock_card_keterangan IN ('Mutasi Pallet Antar Gudang','Mutasi Stock') and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS mutasi_out_antar_gudang,
						SUM(CASE WHEN sku_stock_card_keterangan = 'Koreksi Barang Masuk' and sku_stock_card_keterangan ='Koreksi Stok Pallet' and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS koreksi_adjustmen_in,
						SUM(CASE WHEN sku_stock_card_keterangan = 'Koreksi Barang Keluar' and sku_stock_card_keterangan ='Koreksi Stok Pallet' and sku_stock_card_jenis ='K'  THEN sku_stock_card_qty ELSE 0 END) AS koreksi_adjustmen_out,
						SUM(CASE WHEN sku_stock_card_keterangan = 'Konfirmasi Penerimaan Barang Supplier' THEN sku_stock_card_qty ELSE 0 END) AS penerimaan_supplier,
						SUM(CASE WHEN sku_stock_card_keterangan = 'Konversi in' and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS pembongkaran_D,
						SUM(CASE WHEN sku_stock_card_keterangan = 'Konversi out' and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS pembongkaran_K
					FROM sku_stock_card
					GROUP BY sku_id
				) AS skuu ON skuu.sku_id = ssc.sku_id
				LEFT JOIN (
					SELECT
						sku_stock_card_id,
						SUM(sku_stock_card_qty) AS counta2
					FROM sku_stock_card
					GROUP BY sku_stock_card_id
				) AS skuu2 ON skuu2.sku_stock_card_id = ssc.sku_stock_card_id
				WHERE FORMAT(ssc.sku_stock_card_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
				AND ssc.depo_id ='$depo_id' 
				--AND
				-- FORMAT(ssc.sku_stock_card_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl_2'
												--$client_wms
												$depo_detail
												--$principle
												$skukode
												$skunama
				")->row();
		return $query->hasil;
	}


	public function GetDetail($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2)
	{
		$this->db->select("ssc.sku_id,
					ssc.sku_stock_card_id,
					depo.depo_nama,
					dd.depo_detail_nama,
					principle.principle_nama,
					sku.sku_nama_produk,
					sku.sku_kemasan,
					sku.sku_kode,
					ss.sku_stock_batch_no,
					FORMAT(ss.sku_stock_expired_date, 'dd-MM-yyyy') as sku_stock_expired_date,
					FORMAT(ssc.sku_stock_card_tanggal, 'dd-MM-yyyy') as sku_stock_card_tanggal,
					CASE 
						WHEN ISNULL(ssc.sku_stock_card_tanggal, '$tgl1') < '$tgl1' THEN sum(ssc.sku_stock_card_qty)
					END as stock_awal,
					CASE 
						WHEN ISNULL(ssc.sku_stock_card_tanggal, '$tgl2') < '$tgl2' THEN sum(ssc.sku_stock_card_qty)
					END as stock_akhir,
					COALESCE(skuu.opname, 0) AS sum_opname,
					COALESCE(skuu.penerimaan_supplier, 0) AS penerimaan_supplier,
					COALESCE(skuu.penerimaan_retur_outlet, 0) AS penerimaan_retur_outlet,
					COALESCE(skuu.penjualan, 0) AS penjualan,
					COALESCE(skuu.koreksi_adjustmen_in, 0) AS koreksi_adjustmen_in,
					COALESCE(skuu.koreksi_adjustmen_out, 0) AS koreksi_adjustmen_out,
					COALESCE(skuu.mutasi_in_antar_gudang, 0) AS mutasi_in_antar_gudang,
					COALESCE(skuu.mutasi_out_antar_gudang, 0) AS mutasi_out_antar_gudang,
					COALESCE(skuu.pembongkaran_D, 0) AS pembongkaran_D,
					COALESCE(skuu.pembongkaran_K, 0) AS pembongkaran_K,
					0 AS mutasi_in_extern_gudang,
					0 AS pemusnahan,
					0 AS retur_supplier,
					0 AS mutasi_out_antar_cabang,
					0 AS saldo_perhitungan_akhir,
					FORMAT(ssc.sku_stock_card_tanggal, 'dd-MM-yyyy'),
					SUM(skuu2.counta2) AS sum_counta2
			");

		$this->db->from('sku_stock_card ssc');
		$this->db->join('depo', 'depo.depo_id = ssc.depo_id', 'left');
		$this->db->join('depo_detail dd', 'dd.depo_detail_id = ssc.depo_detail_id', 'left');
		$this->db->join('sku', 'sku.sku_id = ssc.sku_id', 'left');
		$this->db->join('principle', 'principle.principle_id = sku.principle_id', 'left');
		$this->db->join('sku_stock ss', 'ss.sku_stock_id = ssc.sku_stock_id AND ss.sku_id = ssc.sku_id', 'left');

		$this->db->join(
			"(SELECT
				sku_id,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Stock Opname' THEN sku_stock_card_qty ELSE 0 END) AS opname,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Permintaan Pengeluaran Barang' THEN sku_stock_card_qty ELSE 0 END) AS penjualan,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Bukti Terima Barang dari Outlet' AND sku_stock_card_jenis = 'K' THEN sku_stock_card_qty ELSE 0 END) AS penerimaan_retur_outlet,
				SUM(CASE WHEN sku_stock_card_keterangan IN ('Mutasi Pallet Antar Gudang','Mutasi Stock') AND sku_stock_card_jenis = 'K' THEN sku_stock_card_qty ELSE 0 END) AS mutasi_in_antar_gudang,
				SUM(CASE WHEN sku_stock_card_keterangan IN ('Mutasi Pallet Antar Gudang','Mutasi Stock') AND sku_stock_card_jenis = 'D' THEN sku_stock_card_qty ELSE 0 END) AS mutasi_out_antar_gudang,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Koreksi Barang Masuk' AND sku_stock_card_keterangan = 'Koreksi Stok Pallet' AND sku_stock_card_jenis = 'D' THEN sku_stock_card_qty ELSE 0 END) AS koreksi_adjustmen_in,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Koreksi Barang Keluar' AND sku_stock_card_keterangan = 'Koreksi Stok Pallet' AND sku_stock_card_jenis = 'K' THEN sku_stock_card_qty ELSE 0 END) AS koreksi_adjustmen_out,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Konfirmasi Penerimaan Barang Supplier' THEN sku_stock_card_qty ELSE 0 END) AS penerimaan_supplier,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Konversi in' AND sku_stock_card_jenis = 'D' THEN sku_stock_card_qty ELSE 0 END) AS pembongkaran_D,
				SUM(CASE WHEN sku_stock_card_keterangan = 'Konversi out' AND sku_stock_card_jenis = 'K' THEN sku_stock_card_qty ELSE 0 END) AS pembongkaran_K
			FROM sku_stock_card
			GROUP BY sku_id) skuu",
			"skuu.sku_id = ssc.sku_id",
			"left"
		);

		$this->db->join(
			'(SELECT
					sku_stock_card_id,
					SUM(sku_stock_card_qty) AS counta2
				FROM sku_stock_card
				GROUP BY sku_stock_card_id) skuu2',
			'skuu2.sku_stock_card_id = ssc.sku_stock_card_id',
			'left'
		);

		$this->db->where("FORMAT(ssc.sku_stock_card_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl_1' AND '$tgl_2'");
		$this->db->where("ssc.depo_id", $depo_id);

		if ($depo_detail_id == '') {
			// $this->db->where('');
		} else {
			$this->db->where('ssc.depo_detail_id', $depo_detail_id);
		}

		if ($principle_id == '') {
			// $this->db->where('');
		} else {
			$this->db->where('ssc.principle_id', $principle_id);
		}

		if ($sku_kode == '') {
			// $this->db->where('');
		} else {
			$this->db->like('sku.sku_kode', $sku_kode);
		}

		if ($sku_nama == '') {
			// $this->db->where('');
		} else {
			$this->db->like('sku.sku_nama_produk', $sku_nama);
		}
		$this->db->group_by("
				ssc.sku_id,
				ssc.sku_stock_card_id,
				depo.depo_nama,
				dd.depo_detail_nama,
				principle.principle_nama,
				ss.sku_stock_batch_no,
				sku.sku_nama_produk,
				sku.sku_kemasan,
				ss.sku_stock_expired_date,
				ssc.sku_stock_card_tanggal,
				sku.sku_kode,
				skuu.opname,
				skuu.penerimaan_supplier,
				skuu.penerimaan_retur_outlet,
				skuu.penjualan,
				skuu.koreksi_adjustmen_in,
				skuu.koreksi_adjustmen_out,
				skuu.mutasi_in_antar_gudang,
				skuu.mutasi_out_antar_gudang,
				skuu.pembongkaran_D,
				skuu.pembongkaran_K,
				FORMAT(ssc.sku_stock_card_tanggal, 'dd-MM-yyyy')
			");

		$this->db->order_by('principle.principle_nama');

		$sortable_columns = array(
			'sku_id',
			'sku_stock_card_id',
			'depo_nama',
			'depo_detail_nama',
			'principle_nama',
			'sku_stock_batch_no',
			'sku_nama_produk',
			'sku_kemasan',
			'sku_stock_expired_date',
			'sku_stock_card_tanggal',
			'opname',
			'penerimaan_supplier',
			'penerimaan_retur_outlet',
			'penjualan',
			'koreksi_adjustmen_in',
			'koreksi_adjustmen_out',
			'mutasi_in_antar_gudang',
			'mutasi_out_antar_gudang',
			'pembongkaran_D',
			'pembongkaran_K',
			'sku_stock_card_tanggal'
		);

		// Kolom yang dapat dicari
		$searchable_columns = array(
			'sku_id',
			'sku_stock_card_id',
			'depo_nama',
			'depo_detail_nama',
			'principle_nama',
			'sku_stock_batch_no',
			'sku_nama_produk',
			'sku_kemasan',
			'sku_stock_expired_date',
			'sku_stock_card_tanggal',
			'opname',
			'penerimaan_supplier',
			'penerimaan_retur_outlet',
			'penjualan',
			'koreksi_adjustmen_in',
			'koreksi_adjustmen_out',
			'mutasi_in_antar_gudang',
			'mutasi_out_antar_gudang',
			'pembongkaran_D',
			'pembongkaran_K',
			'sku_stock_card_tanggal'
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
		// if ($this->db->get()->num_rows() == 0) {
		// 	return [];
		// } else {
		return $this->db->get()->result_array();
		// }
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

		$query = $this->db->query("exec report_transaksi_stock_movement $start,$length,$order_column,'$order_dir','$tgl_1','$tgl_2','$client_wms_id','$depo_id','$depo_detail_id','$principle_id','$sku_kode','$sku_nama','$tgl1','$tgl2'");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}

	public function Get_laporan_transaksi_stock_movement_detail($tipe_report, $tgl1, $tgl2, $tipe_transaksi, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_id, $sku_kode, $sku_nama, $sku_stock_expired_date, $data_stock_awal)
	{
		$tipe_report2 = "";
		$tgl2 = date('m/d/Y', strtotime($tgl2 . "+1 days"));

		if ($depo_detail_id == "" || $depo_detail_id == "null") {
			$depo_detail_id = "";
		} else {
			$depo_detail_id = " AND sku_stock_card.depo_detail_id = '$depo_detail_id'";
		}

		if ($principle_id == "" || $principle_id == "null") {
			$principle_id = "";
		} else {
			$principle_id = " AND sku.principle_id = '$principle_id'";
		}

		if ($sku_kode == "") {
			$sku_kode = "";
		} else {
			$sku_kode = " AND sku.sku_kode LIKE '%" . $sku_kode . "%'";
		}

		if ($sku_nama == "") {
			$sku_nama = "";
		} else {
			$sku_nama = " AND sku.sku_nama_produk LIKE '%" . $sku_nama . "%'";
		}

		if ($sku_stock_expired_date == "") {
			$sku_stock_expired_date = "";
		} else {
			$sku_stock_expired_date = " AND FORMAT(sku_stock.sku_stock_expired_date, 'dd-MM-yyyy') = '$sku_stock_expired_date'";
		}

		if ($tipe_report == "pembongkaran_in") {
			$tipe_report = " AND sku_stock_card_keterangan IN ('Pembongkaran', 'Pengemasan') AND sku_stock_card_jenis = 'D'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "pembongkaran_out") {
			$tipe_report = " AND sku_stock_card_keterangan IN ('Pembongkaran', 'Pengemasan') AND sku_stock_card_jenis = 'K'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "opname_in") {
			$tipe_report = " AND sku_stock_card_keterangan = 'Stock Opname' AND sku_stock_card_jenis = 'D'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "opname_out") {
			$tipe_report = " AND sku_stock_card_keterangan = 'Stock Opname' AND sku_stock_card_jenis = 'K'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "penjualan") {
			$tipe_report = " AND sku_stock_card_keterangan = 'Permintaan Pengeluaran Barang'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "penerimaan_retur_outlet") {
			$tipe_report = " AND sku_stock_card_keterangan IN ('Bukti Terima Barang dari Outlet', 'Bukti Terima Barang dari Canvas') AND sku_stock_card_jenis = 'D'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "mutasi_in_antar_gudang") {
			$tipe_report = " AND sku_stock_card_keterangan IN ('Mutasi Pallet Antar Gudang', 'Mutasi Stock') AND sku_stock_card_jenis = 'D'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "mutasi_out_antar_gudang") {
			$tipe_report = " AND sku_stock_card_keterangan IN ('Mutasi Pallet Antar Gudang', 'Mutasi Stock') AND sku_stock_card_jenis = 'K'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "koreksi_adjustmen_in") {
			$tipe_report = " AND (sku_stock_card_keterangan IN ('Koreksi Barang Masuk','System Correction') OR sku_stock_card_keterangan like '%Koreksi Stok Pallet%') AND sku_stock_card_jenis = 'D'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "koreksi_adjustmen_out") {
			$tipe_report = " AND (sku_stock_card_keterangan IN ('Koreksi Barang Masuk','System Correction') OR sku_stock_card_keterangan like '%Koreksi Stok Pallet%') AND sku_stock_card_jenis = 'K'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "penerimaan_supplier") {
			$tipe_report = " AND sku_stock_card_keterangan = 'Konfirmasi Penerimaan Barang Supplier' AND sku_stock_card_jenis = 'D'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "pembongkaran_D") {
			$tipe_report = " AND sku_stock_card_keterangan = 'Konversi In' AND sku_stock_card_jenis = 'D'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else if ($tipe_report == "pembongkaran_K") {
			$tipe_report = " AND sku_stock_card_keterangan = 'Konversi out' AND sku_stock_card_jenis = 'K'";
			$tipe_report2 = " AND sku_stock_card_keterangan NOT IN ('Stock Awal', 'Stock Akhir')";
		} else {
			$tipe_report = "";
			$tipe_report2 = "";
		}

		$query = $this->db->query("SELECT
									sku_stock_card_dokumen_id,
									sku_stock_card_dokumen_no,
									format(sku_stock_card_tanggal, 'dd-MM-yyyy HH:mm:ss') AS sku_stock_card_tanggal,
									depo_id,
									depo_nama,
									depo_detail_id,
									depo_detail_nama,
									principle_id,
									principle_kode,
									tipe_mutasi_id,
									tipe_mutasi_nama,
									sku_id,
									sku_kode,
									sku_nama_produk,
									sku_stock_card_keterangan,
									ekspedisi_nama,
									driver_nama,
									stock_in,
									stock_out,
									SUM(stock_total) OVER (PARTITION BY
									depo_id,
									depo_detail_id,
									sku_id
									ORDER BY format(sku_stock_card_tanggal, 'dd-MM-yyyy HH:mm:ss'), depo_nama, depo_detail_nama, sku_kode
									ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW) AS stock_total
									FROM (SELECT  NULL AS sku_stock_card_dokumen_id,
											NULL AS sku_stock_card_dokumen_no,
											NULL AS sku_stock_card_tanggal,
											'" . $data_stock_awal['depo_id'] . "' AS depo_id,
											NULL AS depo_nama,
											'" . $data_stock_awal['depo_detail_id'] . "' AS depo_detail_id,
											NULL AS depo_detail_nama,
											'" . $data_stock_awal['principle_id'] . "' AS principle_id,
											NULL AS principle_kode,
											NULL AS tipe_mutasi_id,
											NULL AS tipe_mutasi_nama,
											'" . $data_stock_awal['sku_id'] . "'AS sku_id,
											'" . $data_stock_awal['sku_kode'] . "' AS sku_kode,
											'" . $data_stock_awal['sku_nama_produk'] . "' AS sku_nama_produk,
											'" . $data_stock_awal['sku_stock_card_keterangan'] . "' AS sku_stock_card_keterangan,
											NULL AS ekspedisi_nama,
											NULL AS driver_nama,
											" . $data_stock_awal['stock_in'] . " AS stock_in,
											0 AS stock_out,
											" . $data_stock_awal['stock_total'] . " AS stock_total
									UNION ALL
									SELECT
									sku_stock_card.sku_stock_card_dokumen_id,
									sku_stock_card.sku_stock_card_dokumen_no,
									sku_stock_card.sku_stock_card_tanggal,
									CONVERT(NVARCHAR(36),sku_stock_card.depo_id) AS depo_id,
									depo.depo_nama,
									CONVERT(NVARCHAR(36),sku_stock_card.depo_detail_id) AS depo_detail_id,
									depo_detail.depo_detail_nama,
									CONVERT(NVARCHAR(36),sku.principle_id) AS principle_id,
									principle.principle_kode,
									sku_stock_card.tipe_mutasi_id,
									tipe_mutasi.tipe_mutasi_nama,
									CONVERT(NVARCHAR(36),sku_stock_card.sku_id) AS sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku_stock_card.sku_stock_card_keterangan,
									CASE
										WHEN sku_stock_card.sku_stock_card_keterangan = 'Konfirmasi Distribusi Penerimaan' THEN penerimaan_pembelian.ekspedisi_nama
										ELSE ''
									END AS ekspedisi_nama,
									CASE
										WHEN sku_stock_card.sku_stock_card_keterangan = 'Konfirmasi Distribusi Penerimaan' THEN penerimaan_pembelian.penerimaan_pembelian_pengemudi
										ELSE ''
									END AS driver_nama,
									SUM(CASE
										WHEN sku_stock_card.sku_stock_card_jenis = 'D' THEN sku_stock_card.sku_stock_card_qty
										ELSE 0
									END) AS stock_in,
									SUM(CASE
										WHEN sku_stock_card.sku_stock_card_jenis = 'K' THEN sku_stock_card.sku_stock_card_qty
										ELSE 0
									END) AS stock_out,
									SUM(CASE
										WHEN sku_stock_card.sku_stock_card_jenis = 'D' THEN sku_stock_card.sku_stock_card_qty
										ELSE 0
									END) - SUM(CASE
										WHEN sku_stock_card.sku_stock_card_jenis = 'K' THEN sku_stock_card.sku_stock_card_qty
										ELSE 0
									END) AS stock_total
									FROM sku_stock_card
									LEFT JOIN (SELECT
									penerimaan_pembelian.*,
									ekspedisi.ekspedisi_nama
									FROM penerimaan_pembelian
									LEFT JOIN ekspedisi
									ON penerimaan_pembelian.ekspedisi_id = ekspedisi.ekspedisi_id) penerimaan_pembelian
									ON penerimaan_pembelian.penerimaan_pembelian_id = sku_stock_card.sku_stock_card_dokumen_id
									AND sku_stock_card.sku_stock_card_keterangan = 'Konfirmasi Distribusi Penerimaan'
									LEFT JOIN sku
									ON sku.sku_id = sku_stock_card.sku_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = sku_stock_card.sku_stock_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN depo
									ON depo.depo_id = sku_stock_card.depo_id
									LEFT JOIN depo_detail
									ON depo_detail.depo_detail_id = sku_stock_card.depo_detail_id
									LEFT JOIN tipe_mutasi
									ON sku_stock_card.tipe_mutasi_id = tipe_mutasi.tipe_mutasi_id
									WHERE isnull(sku_stock_card.sku_stock_card_tanggal, '$tgl1') >= '$tgl1'
									AND ISNULL(sku_stock_card.sku_stock_card_tanggal, '$tgl1') < '$tgl2'
									AND CONVERT(NVARCHAR(36),sku_stock_card.depo_id) = '$depo_id'
									" . $depo_detail_id . "
									" . $principle_id . "
									" . $sku_kode . "
									" . $sku_nama . "
									" . $tipe_report . "
									" . $sku_stock_expired_date . "
									GROUP BY sku_stock_card.sku_stock_card_dokumen_id,
											sku_stock_card.sku_stock_card_dokumen_no,
											sku_stock_card.sku_stock_card_tanggal,
											sku_stock_card.depo_id,
											depo.depo_nama,
											sku_stock_card.depo_detail_id,
											depo_detail.depo_detail_nama,
											sku.principle_id,
											principle.principle_kode,
											sku_stock_card.tipe_mutasi_id,
											tipe_mutasi.tipe_mutasi_nama,
											sku_stock_card.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku_stock_card.sku_stock_card_keterangan,
											CASE
											WHEN sku_stock_card.sku_stock_card_keterangan = 'Konfirmasi Distribusi Penerimaan' THEN penerimaan_pembelian.ekspedisi_nama
											ELSE ''
											END,
											CASE
											WHEN sku_stock_card.sku_stock_card_keterangan = 'Konfirmasi Distribusi Penerimaan' THEN penerimaan_pembelian.penerimaan_pembelian_pengemudi
											ELSE ''
											END) stock_movement
									WHERE sku_id IS NOT NULL
									" . $tipe_report2 . "
									ORDER BY format(sku_stock_card_tanggal, 'dd-MM-yyyy HH:mm:ss') asc, depo_nama, depo_detail_nama, sku_kode ASC");

		return $query->result_array();
		// return $this->db->last_query();
	}

	public function Get_laporan_lead_time_do($tgl1, $tgl2, $depo_id, $principle_id)
	{

		$query = $this->db->query("exec report_do_lead '$tgl1','$tgl2','','$depo_id','$principle_id'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query =  $query->result_array();
		}

		return $query;
	}

	public function GetSalesOrderByFilter($tgl1, $tgl2, $perusahaan, $kode, $status, $tipe, $sales, $principle, $tgl_kirim, $start, $end, $search, $order_column, $order_dir, $is_priority)
	{
		if ($perusahaan == "") {
			$perusahaan = "";
		} else {
			$perusahaan = "AND so.client_wms_id = '" . $perusahaan . "' ";
		}

		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND so.principle_id = '" . $principle . "' ";
		}

		if ($kode == "") {
			$kode = "";
		} else {
			$kode = "AND so.sales_order_kode LIKE '%" . $kode . "%' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND so.sales_order_status= '$status' ";
		}

		if ($tipe == "") {
			$tipe = "";
		} else {
			$tipe = "AND so.tipe_sales_order_id = '" . $tipe . "' ";
		}

		if ($sales == "") {
			$sales = "";
		} else {
			$sales = "AND so.sales_id = '" . $sales . "' ";
		}

		if ($tgl_kirim == "") {
			$tgl_kirim = "";
		} else {
			$tgl_kirim = "AND FORMAT(so.sales_order_tgl_kirim, 'yyyy-MM-dd') = '" . $tgl_kirim . "' ";
		}

		if ($is_priority == "") {
			$is_priority = "";
		} else {
			$is_priority = "AND is_priority = '" . $is_priority . "' ";
		}

		$order_by = '';
		if ($order_column == 0) {
			$order_by = "ORDER BY is_priority DESC";
		} else if ($order_column == 1) {
			$order_by = "ORDER BY sales_order_tgl " . $order_dir . "";
		} else if ($order_column == 2) {
			$order_by = "ORDER BY sales_order_tgl_kirim " . $order_dir . "";
		} else if ($order_column == 3) {
			$order_by = "ORDER BY sales_order_kode " . $order_dir . "";
		} else if ($order_column == 4) {
			$order_by = "ORDER BY sales_order_no_po " . $order_dir . "";
		} else if ($order_column == 5) {
			$order_by = "ORDER BY kode_sales " . $order_dir . "";
		} else if ($order_column == 6) {
			$order_by = "ORDER BY karyawan_nama " . $order_dir . "";
		} else if ($order_column == 7) {
			$order_by = "ORDER BY kode_customer_eksternal " . $order_dir . "";
		} else if ($order_column == 8) {
			$order_by = "ORDER BY principle_kode " . $order_dir . "";
		} else if ($order_column == 9) {
			$order_by = "ORDER BY client_wms_nama " . $order_dir . "";
		} else if ($order_column == 10) {
			$order_by = "ORDER BY client_pt_nama " . $order_dir . "";
		} else if ($order_column == 11) {
			$order_by = "ORDER BY client_pt_alamat " . $order_dir . "";
		} else if ($order_column == 12) {
			$order_by = "ORDER BY tipe_sales_order_nama " . $order_dir . "";
		} else if ($order_column == 13) {
			$order_by = "ORDER BY sales_order_status " . $order_dir . "";
		} else if ($order_column == 14) {
			$order_by = "ORDER BY sku_harga_nett " . $order_dir . "";
		} else if ($order_column == 15) {
			$order_by = "ORDER BY sales_order_keterangan " . $order_dir . "";
		} else if ($order_column == 16) {
			$order_by = "ORDER BY is_priority " . $order_dir . "";
		}

		$query = $this->db->query("SELECT
										ROW_NUMBER() OVER (ORDER BY FORMAT(so.sales_order_tgl,'dd-MM-yyyy') DESC) - 1 AS idx,
										so.sales_order_id,
										so.sales_order_kode,
										ISNULL(so.sales_order_no_po,'') sales_order_no_po,
										FORMAT(so.sales_order_tgl,'dd-MM-yyyy') AS sales_order_tgl,
										--FORMAT(so.sales_order_tgl_create,'dd-MM-yyyy') AS sales_order_tgl,
										--FORMAT(so.sales_order_tgl_exp,'dd-MM-yyyy') AS sales_order_tgl,
										--FORMAT(so.sales_order_tgl_sj,'dd-MM-yyyy') AS sales_order_tgl,
										--FORMAT(so.sales_order_tgl_harga,'dd-MM-yyyy') AS sales_order_tgl,
										FORMAT(so.sales_order_tgl_kirim,'dd-MM-yyyy') AS sales_order_tgl_kirim,
										FORMAT(so.sales_order_tgl_kirim,'yyyy-MM-dd') AS sales_order_tgl_kirim2,
										so.client_pt_id,
										cust.client_pt_nama,
										cust.client_pt_alamat,
										so.tipe_sales_order_id,
										tipe.tipe_sales_order_nama,
										so.sales_order_status,
										k.karyawan_nama,
										sum(sod.sku_harga_nett) as sku_harga_nett,
										pt.client_wms_nama,
										ISNULL(so.sales_order_keterangan,'') sales_order_keterangan,
										ISNULL(bs.szCustId,'') as  kode_customer_eksternal,
										ISNULL(bs.szSalesId,'') as  kode_sales,
										sales_order_tgl_update as tglUpdate,
										ISNULL(principle.principle_kode, '') as principle_kode,
										CASE WHEN ISNULL(so.is_priority, 0) = 1 THEN 'Yes' ELSE '' END AS is_priority
									FROM sales_order so
									LEFT JOIN client_pt cust
									ON cust.client_pt_id = so.client_pt_id
									LEFT JOIN tipe_sales_order tipe
									ON tipe.tipe_sales_order_id = so.tipe_sales_order_id
									LEFT JOIN principle
									ON principle.principle_id = so.principle_id
									LEFT JOIN bosnet_so bs
									ON so.sales_order_no_po = bs.szFSoId
									LEFT JOIN karyawan k
									ON so.sales_id = k.karyawan_id
									LEFT JOIN sales_order_detail sod
									ON so.sales_order_id = sod.sales_order_id
									LEFT JOIN client_wms pt
									ON so.client_wms_id = pt.client_wms_id
									WHERE FORMAT(so.sales_order_tgl,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									" . $perusahaan . "
									" . $kode . "
									" . $status . "
									" . $tipe . "
									" . $sales . "
									" . $principle . "
									" . $tgl_kirim . "
									" . $is_priority . "
									GROUP BY
										so.sales_order_id,
										so.sales_order_kode,
										ISNULL(so.sales_order_no_po,''),
										FORMAT(so.sales_order_tgl,'dd-MM-yyyy'),
										FORMAT(so.sales_order_tgl_kirim,'dd-MM-yyyy'),
										FORMAT(so.sales_order_tgl_kirim,'yyyy-MM-dd'),
										so.client_pt_id,
										cust.client_pt_nama,
										cust.client_pt_alamat,
										so.tipe_sales_order_id,
										tipe.tipe_sales_order_nama,
										so.sales_order_status,
										k.karyawan_nama,
										pt.client_wms_nama,
										ISNULL(so.sales_order_keterangan,''),
										ISNULL(bs.szCustId,''),
										ISNULL(bs.szSalesId,''),
										sales_order_tgl_update,
										ISNULL(principle.principle_kode, ''),
										so.sales_order_keterangan,
										CASE WHEN ISNULL(so.is_priority, 0) = 1 THEN 'Yes' ELSE '' END
									--ORDER BY FORMAT(so.sales_order_tgl,'dd-MM-yyyy') DESC, so.sales_order_kode ASC
									" . $order_by . "
									OFFSET " . $start . " ROWS
									FETCH NEXT " . $end . " ROWS ONLY");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_total_laporan_lead_time_do_detail_so_approved($tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range)
	{
		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "AND so.principle_id = '" . $principle_id . "' ";
		}

		if ($range == "jml_0_sd_3") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_create, so.sales_order_tgl_update) <= 3";
		} else if ($range == "jml_4_sd_7") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_create, so.sales_order_tgl_update) BETWEEN 4 AND 7";
		} else if ($range == "jml_8_sd_14") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_create, so.sales_order_tgl_update) BETWEEN 8 AND 14";
		} else if ($range == "jml_15_sd_28") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_create, so.sales_order_tgl_update) BETWEEN 15 AND 28";
		} else if ($range == "jml_29") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_create, so.sales_order_tgl_update) >= 29";
		}

		$query = $this->db->query("SELECT count(DISTINCT so.sales_order_id) as jml
						FROM sales_order so
						LEFT JOIN client_pt cust ON cust.client_pt_id = so.client_pt_id
						LEFT JOIN tipe_sales_order tipe ON tipe.tipe_sales_order_id = so.tipe_sales_order_id
						LEFT JOIN principle ON principle.principle_id = so.principle_id
						LEFT JOIN bosnet_so bs ON so.sales_order_no_po = bs.szFSoId
						LEFT JOIN karyawan k ON so.sales_id = k.karyawan_id
						LEFT JOIN sales_order_detail sod ON so.sales_order_id = sod.sales_order_id
						LEFT JOIN client_wms pt ON so.client_wms_id = pt.client_wms_id
						WHERE format(so.sales_order_tgl, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
						AND so.sales_order_status = 'Approved'
						AND so.tipe_sales_order_id IN ('D5A2AB04-0236-424D-859C-1888B46D6F50','AD89E05B-46A6-453B-8F19-886514234A21')
						AND CONVERT(NVARCHAR(36), so.depo_id) = '$depo_id'
						" . $range . "");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->jml;
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_laporan_lead_time_do_detail_so_approved($start, $end, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range)
	{
		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "AND so.principle_id = '" . $principle_id . "' ";
		}

		if ($range == "jml_0_sd_3") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_create, so.sales_order_tgl_update) <= 3";
		} else if ($range == "jml_4_sd_7") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_create, so.sales_order_tgl_update) BETWEEN 4 AND 7";
		} else if ($range == "jml_8_sd_14") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_create, so.sales_order_tgl_update) BETWEEN 8 AND 14";
		} else if ($range == "jml_15_sd_28") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_create, so.sales_order_tgl_update) BETWEEN 15 AND 28";
		} else if ($range == "jml_29") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_create, so.sales_order_tgl_update) >= 29";
		}

		$order_by = '';
		if ($order_column == 0) {
			$order_by = "ORDER BY is_priority DESC";
		} else if ($order_column == 1) {
			$order_by = "ORDER BY sales_order_tgl " . $order_dir . "";
		} else if ($order_column == 2) {
			$order_by = "ORDER BY sales_order_tgl_kirim " . $order_dir . "";
		} else if ($order_column == 3) {
			$order_by = "ORDER BY sales_order_kode " . $order_dir . "";
		} else if ($order_column == 4) {
			$order_by = "ORDER BY sales_order_no_po " . $order_dir . "";
		} else if ($order_column == 5) {
			$order_by = "ORDER BY kode_sales " . $order_dir . "";
		} else if ($order_column == 6) {
			$order_by = "ORDER BY karyawan_nama " . $order_dir . "";
		} else if ($order_column == 7) {
			$order_by = "ORDER BY kode_customer_eksternal " . $order_dir . "";
		} else if ($order_column == 8) {
			$order_by = "ORDER BY principle_kode " . $order_dir . "";
		} else if ($order_column == 9) {
			$order_by = "ORDER BY client_wms_nama " . $order_dir . "";
		} else if ($order_column == 10) {
			$order_by = "ORDER BY client_pt_nama " . $order_dir . "";
		} else if ($order_column == 11) {
			$order_by = "ORDER BY client_pt_alamat " . $order_dir . "";
		} else if ($order_column == 12) {
			$order_by = "ORDER BY tipe_sales_order_nama " . $order_dir . "";
		} else if ($order_column == 13) {
			$order_by = "ORDER BY sales_order_status " . $order_dir . "";
		} else if ($order_column == 14) {
			$order_by = "ORDER BY sku_harga_nett " . $order_dir . "";
		} else if ($order_column == 15) {
			$order_by = "ORDER BY sales_order_keterangan " . $order_dir . "";
		} else if ($order_column == 16) {
			$order_by = "ORDER BY is_priority " . $order_dir . "";
		}

		$query = $this->db->query("SELECT 'SO Approved' AS tipe,
												so.sales_order_id,
												so.sales_order_kode,
												ISNULL(so.sales_order_no_po, '') sales_order_no_po,
												FORMAT(so.sales_order_tgl, 'dd-MM-yyyy') AS sales_order_tgl,
												FORMAT(so.sales_order_tgl_create, 'dd-MM-yyyy') AS sales_order_tgl,
												FORMAT(so.sales_order_tgl_exp, 'dd-MM-yyyy') AS sales_order_tgl,
												FORMAT(so.sales_order_tgl_sj, 'dd-MM-yyyy') AS sales_order_tgl,
												FORMAT(so.sales_order_tgl_harga, 'dd-MM-yyyy') AS sales_order_tgl,
												FORMAT(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
												FORMAT(so.sales_order_tgl_kirim, 'yyyy-MM-dd') AS sales_order_tgl_kirim2,
												so.client_pt_id,
												cust.client_pt_nama,
												cust.client_pt_alamat,
												so.tipe_sales_order_id,
												tipe.tipe_sales_order_nama,
												so.sales_order_status,
												k.karyawan_nama,
												sum(sod.sku_harga_nett) AS sku_harga_nett,
												pt.client_wms_nama,
												ISNULL(so.sales_order_keterangan, '') sales_order_keterangan,
												ISNULL(bs.szCustId, '') AS kode_customer_eksternal,
												ISNULL(bs.szSalesId, '') AS kode_sales,
												sales_order_tgl_update AS tglUpdate,
												ISNULL(principle.principle_kode, '') AS principle_kode,
												CASE
													WHEN ISNULL(so.is_priority, 0) = 1 THEN 'Yes'
													ELSE ''
												END AS is_priority
						FROM sales_order so
						LEFT JOIN client_pt cust ON cust.client_pt_id = so.client_pt_id
						LEFT JOIN tipe_sales_order tipe ON tipe.tipe_sales_order_id = so.tipe_sales_order_id
						LEFT JOIN principle ON principle.principle_id = so.principle_id
						LEFT JOIN bosnet_so bs ON so.sales_order_no_po = bs.szFSoId
						LEFT JOIN karyawan k ON so.sales_id = k.karyawan_id
						LEFT JOIN sales_order_detail sod ON so.sales_order_id = sod.sales_order_id
						LEFT JOIN client_wms pt ON so.client_wms_id = pt.client_wms_id
						WHERE format(so.sales_order_tgl, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
						AND so.sales_order_status = 'Approved'
						AND so.tipe_sales_order_id IN ('D5A2AB04-0236-424D-859C-1888B46D6F50','AD89E05B-46A6-453B-8F19-886514234A21')
						AND CONVERT(NVARCHAR(36), so.depo_id) = '$depo_id'
						" . $range . "
						GROUP BY so.sales_order_id,
								so.sales_order_kode,
								ISNULL(so.sales_order_no_po, ''),
								FORMAT(so.sales_order_tgl, 'dd-MM-yyyy'),
								FORMAT(so.sales_order_tgl_create, 'dd-MM-yyyy'),
								FORMAT(so.sales_order_tgl_exp, 'dd-MM-yyyy'),
								FORMAT(so.sales_order_tgl_sj, 'dd-MM-yyyy'),
								FORMAT(so.sales_order_tgl_harga, 'dd-MM-yyyy'),
								FORMAT(so.sales_order_tgl_kirim, 'dd-MM-yyyy'),
								FORMAT(so.sales_order_tgl_kirim, 'yyyy-MM-dd'),
								so.client_pt_id,
								cust.client_pt_nama,
								cust.client_pt_alamat,
								so.tipe_sales_order_id,
								tipe.tipe_sales_order_nama,
								so.sales_order_status,
								k.karyawan_nama,
								pt.client_wms_nama,
								ISNULL(so.sales_order_keterangan, ''),
								ISNULL(bs.szCustId, ''),
								ISNULL(bs.szSalesId, ''),
								sales_order_tgl_update,
								ISNULL(principle.principle_kode, ''),
								so.sales_order_keterangan,
								CASE
									WHEN ISNULL(so.is_priority, 0) = 1 THEN 'Yes'
									ELSE ''
								END
						" . $order_by . "
						OFFSET " . $start . " ROWS
						FETCH NEXT " . $end . " ROWS ONLY");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_total_laporan_lead_time_do_detail_so_approved_belum_punya_do_draft($tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range)
	{
		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "AND so.principle_id = '" . $principle_id . "' ";
		}

		if ($range == "jml_0_sd_3") {
			$range = "AND DATEDIFF(DAY, sales_order_tgl_update, GETDATE()) <= 3";
		} else if ($range == "jml_4_sd_7") {
			$range = "AND DATEDIFF(DAY, sales_order_tgl_update, GETDATE()) BETWEEN 4 AND 7";
		} else if ($range == "jml_8_sd_14") {
			$range = "AND DATEDIFF(DAY, sales_order_tgl_update, GETDATE()) BETWEEN 8 AND 14";
		} else if ($range == "jml_15_sd_28") {
			$range = "AND DATEDIFF(DAY, sales_order_tgl_update, GETDATE()) BETWEEN 15 AND 28";
		} else if ($range == "jml_29") {
			$range = "AND DATEDIFF(DAY, sales_order_tgl_update, GETDATE()) >= 29";
		}

		$query = $this->db->query("SELECT count(DISTINCT so.sales_order_id) as jml
						FROM sales_order so
						LEFT JOIN delivery_order_draft do_draft ON so.sales_order_id = do_draft.sales_order_id
						LEFT JOIN client_pt cust ON cust.client_pt_id = so.client_pt_id
						LEFT JOIN tipe_sales_order tipe ON tipe.tipe_sales_order_id = so.tipe_sales_order_id
						LEFT JOIN principle ON principle.principle_id = so.principle_id
						LEFT JOIN bosnet_so bs ON so.sales_order_no_po = bs.szFSoId
						LEFT JOIN karyawan k ON so.sales_id = k.karyawan_id
						LEFT JOIN sales_order_detail sod ON so.sales_order_id = sod.sales_order_id
						LEFT JOIN client_wms pt ON so.client_wms_id = pt.client_wms_id
						WHERE format(so.sales_order_tgl, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
						AND so.sales_order_status = 'Approved'
						AND do_draft.delivery_order_draft_id IS NULL
						AND so.tipe_sales_order_id IN ('D5A2AB04-0236-424D-859C-1888B46D6F50','AD89E05B-46A6-453B-8F19-886514234A21')
						AND CONVERT(NVARCHAR(36), so.depo_id) = '$depo_id'
						" . $principle_id . "
						" . $range . "");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->jml;
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_laporan_lead_time_do_detail_so_approved_belum_punya_do_draft($start, $end, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range)
	{
		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "AND so.principle_id = '" . $principle_id . "' ";
		}

		if ($range == "jml_0_sd_3") {
			$range = "AND DATEDIFF(DAY, sales_order_tgl_update, GETDATE()) <= 3";
		} else if ($range == "jml_4_sd_7") {
			$range = "AND DATEDIFF(DAY, sales_order_tgl_update, GETDATE()) BETWEEN 4 AND 7";
		} else if ($range == "jml_8_sd_14") {
			$range = "AND DATEDIFF(DAY, sales_order_tgl_update, GETDATE()) BETWEEN 8 AND 14";
		} else if ($range == "jml_15_sd_28") {
			$range = "AND DATEDIFF(DAY, sales_order_tgl_update, GETDATE()) BETWEEN 15 AND 28";
		} else if ($range == "jml_29") {
			$range = "AND DATEDIFF(DAY, sales_order_tgl_update, GETDATE()) >= 29";
		}

		$order_by = '';
		if ($order_column == 0) {
			$order_by = "ORDER BY is_priority DESC";
		} else if ($order_column == 1) {
			$order_by = "ORDER BY sales_order_tgl " . $order_dir . "";
		} else if ($order_column == 2) {
			$order_by = "ORDER BY sales_order_tgl_kirim " . $order_dir . "";
		} else if ($order_column == 3) {
			$order_by = "ORDER BY sales_order_kode " . $order_dir . "";
		} else if ($order_column == 4) {
			$order_by = "ORDER BY sales_order_no_po " . $order_dir . "";
		} else if ($order_column == 5) {
			$order_by = "ORDER BY kode_sales " . $order_dir . "";
		} else if ($order_column == 6) {
			$order_by = "ORDER BY karyawan_nama " . $order_dir . "";
		} else if ($order_column == 7) {
			$order_by = "ORDER BY kode_customer_eksternal " . $order_dir . "";
		} else if ($order_column == 8) {
			$order_by = "ORDER BY principle_kode " . $order_dir . "";
		} else if ($order_column == 9) {
			$order_by = "ORDER BY client_wms_nama " . $order_dir . "";
		} else if ($order_column == 10) {
			$order_by = "ORDER BY client_pt_nama " . $order_dir . "";
		} else if ($order_column == 11) {
			$order_by = "ORDER BY client_pt_alamat " . $order_dir . "";
		} else if ($order_column == 12) {
			$order_by = "ORDER BY tipe_sales_order_nama " . $order_dir . "";
		} else if ($order_column == 13) {
			$order_by = "ORDER BY sales_order_status " . $order_dir . "";
		} else if ($order_column == 14) {
			$order_by = "ORDER BY sku_harga_nett " . $order_dir . "";
		} else if ($order_column == 15) {
			$order_by = "ORDER BY sales_order_keterangan " . $order_dir . "";
		} else if ($order_column == 16) {
			$order_by = "ORDER BY is_priority " . $order_dir . "";
		}

		$query = $this->db->query("SELECT 'SO Approved Belum Punya DO Draft' AS tipe,
											so.sales_order_id,
											so.sales_order_kode,
											ISNULL(so.sales_order_no_po, '') sales_order_no_po,
											FORMAT(so.sales_order_tgl, 'dd-MM-yyyy') AS sales_order_tgl,
											FORMAT(so.sales_order_tgl_create, 'dd-MM-yyyy') AS sales_order_tgl,
											FORMAT(so.sales_order_tgl_exp, 'dd-MM-yyyy') AS sales_order_tgl,
											FORMAT(so.sales_order_tgl_sj, 'dd-MM-yyyy') AS sales_order_tgl,
											FORMAT(so.sales_order_tgl_harga, 'dd-MM-yyyy') AS sales_order_tgl,
											FORMAT(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
											FORMAT(so.sales_order_tgl_kirim, 'yyyy-MM-dd') AS sales_order_tgl_kirim2,
											so.client_pt_id,
											cust.client_pt_nama,
											cust.client_pt_alamat,
											so.tipe_sales_order_id,
											tipe.tipe_sales_order_nama,
											so.sales_order_status,
											k.karyawan_nama,
											sum(sod.sku_harga_nett) AS sku_harga_nett,
											pt.client_wms_nama,
											ISNULL(so.sales_order_keterangan, '') sales_order_keterangan,
											ISNULL(bs.szCustId, '') AS kode_customer_eksternal,
											ISNULL(bs.szSalesId, '') AS kode_sales,
											sales_order_tgl_update AS tglUpdate,
											ISNULL(principle.principle_kode, '') AS principle_kode,
											CASE
												WHEN ISNULL(so.is_priority, 0) = 1 THEN 'Yes'
												ELSE ''
											END AS is_priority
					FROM sales_order so
					LEFT JOIN delivery_order_draft do_draft ON so.sales_order_id = do_draft.sales_order_id
					LEFT JOIN client_pt cust ON cust.client_pt_id = so.client_pt_id
					LEFT JOIN tipe_sales_order tipe ON tipe.tipe_sales_order_id = so.tipe_sales_order_id
					LEFT JOIN principle ON principle.principle_id = so.principle_id
					LEFT JOIN bosnet_so bs ON so.sales_order_no_po = bs.szFSoId
					LEFT JOIN karyawan k ON so.sales_id = k.karyawan_id
					LEFT JOIN sales_order_detail sod ON so.sales_order_id = sod.sales_order_id
					LEFT JOIN client_wms pt ON so.client_wms_id = pt.client_wms_id
					WHERE format(so.sales_order_tgl, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
					AND so.sales_order_status = 'Approved'
					AND do_draft.delivery_order_draft_id IS NULL
					AND so.tipe_sales_order_id IN ('D5A2AB04-0236-424D-859C-1888B46D6F50','AD89E05B-46A6-453B-8F19-886514234A21')
					AND CONVERT(NVARCHAR(36), so.depo_id) = '$depo_id'
					" . $range . "
					" . $principle_id . "
					GROUP BY so.sales_order_id,
							so.sales_order_kode,
							ISNULL(so.sales_order_no_po, ''),
							FORMAT(so.sales_order_tgl, 'dd-MM-yyyy'),
							FORMAT(so.sales_order_tgl_create, 'dd-MM-yyyy'),
							FORMAT(so.sales_order_tgl_exp, 'dd-MM-yyyy'),
							FORMAT(so.sales_order_tgl_sj, 'dd-MM-yyyy'),
							FORMAT(so.sales_order_tgl_harga, 'dd-MM-yyyy'),
							FORMAT(so.sales_order_tgl_kirim, 'dd-MM-yyyy'),
							FORMAT(so.sales_order_tgl_kirim, 'yyyy-MM-dd'),
							so.client_pt_id,
							cust.client_pt_nama,
							cust.client_pt_alamat,
							so.tipe_sales_order_id,
							tipe.tipe_sales_order_nama,
							so.sales_order_status,
							k.karyawan_nama,
							pt.client_wms_nama,
							ISNULL(so.sales_order_keterangan, ''),
							ISNULL(bs.szCustId, ''),
							ISNULL(bs.szSalesId, ''),
							sales_order_tgl_update,
							ISNULL(principle.principle_kode, ''),
							so.sales_order_keterangan,
							CASE
								WHEN ISNULL(so.is_priority, 0) = 1 THEN 'Yes'
								ELSE ''
							END
						" . $order_by . "
						OFFSET " . $start . " ROWS
						FETCH NEXT " . $end . " ROWS ONLY");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_total_laporan_lead_time_do_detail_so_approved_jadi_do_draft($tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range)
	{
		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "AND so.principle_id = '" . $principle_id . "' ";
		}

		if ($range == "jml_0_sd_3") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_update, do.delivery_order_draft_tgl_buat_do) <= 3";
		} else if ($range == "jml_4_sd_7") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_update, do.delivery_order_draft_tgl_buat_do) BETWEEN 4 AND 7";
		} else if ($range == "jml_8_sd_14") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_update, do.delivery_order_draft_tgl_buat_do) BETWEEN 8 AND 14";
		} else if ($range == "jml_15_sd_28") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_update, do.delivery_order_draft_tgl_buat_do) BETWEEN 15 AND 28";
		} else if ($range == "jml_29") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_update, do.delivery_order_draft_tgl_buat_do) >= 29";
		}

		$query = $this->db->query("SELECT count(DISTINCT do.delivery_order_draft_id) as jml
						FROM sales_order so
						LEFT JOIN delivery_order_draft DO ON so.sales_order_id = do.sales_order_id
						LEFT JOIN tipe_delivery_order tipe ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
						LEFT JOIN client_pt cp ON cp.client_pt_id = do.client_pt_id
						LEFT JOIN bosnet_so bso ON so.sales_order_no_po = bso.szFSoId
						LEFT JOIN client_pt_segmen cps1 ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
						LEFT JOIN client_pt_segmen cps2 ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
						LEFT JOIN client_pt_segmen cps3 ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
						LEFT JOIN principle ON principle.principle_id = so.principle_id
						LEFT JOIN karyawan ky ON so.sales_id = ky.karyawan_id
						WHERE format(so.sales_order_tgl, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
						AND so.sales_order_status = 'Approved'
						AND do.delivery_order_draft_status = 'Draft'
						AND do.sales_order_id IS NOT NULL
						AND tipe_sales_order_id IN ('D5A2AB04-0236-424D-859C-1888B46D6F50'',''AD89E05B-46A6-453B-8F19-886514234A21')
						AND CONVERT(NVARCHAR(36), so.depo_id) = '$depo_id'
						" . $principle_id . "
						" . $range . "");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->jml;
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_laporan_lead_time_do_detail_so_approved_jadi_do_draft($start, $end, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range)
	{
		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "AND so.principle_id = '" . $principle_id . "' ";
		}

		if ($range == "jml_0_sd_3") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_update, do.delivery_order_draft_tgl_buat_do) <= 3";
		} else if ($range == "jml_4_sd_7") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_update, do.delivery_order_draft_tgl_buat_do) BETWEEN 4 AND 7";
		} else if ($range == "jml_8_sd_14") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_update, do.delivery_order_draft_tgl_buat_do) BETWEEN 8 AND 14";
		} else if ($range == "jml_15_sd_28") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_update, do.delivery_order_draft_tgl_buat_do) BETWEEN 15 AND 28";
		} else if ($range == "jml_29") {
			$range = "AND DATEDIFF(DAY, so.sales_order_tgl_update, do.delivery_order_draft_tgl_buat_do) >= 29";
		}

		$order_by = '';
		if ($order_column == 0) {
			$order_by = "ORDER BY format(do.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') DESC";
		} else if ($order_column == 1) {
			$order_by = "ORDER BY delivery_order_draft_tgl_rencana_kirim " . $order_dir . "";
		} else if ($order_column == 2) {
			$order_by = "ORDER BY delivery_order_draft_kode " . $order_dir . "";
		} else if ($order_column == 3) {
			$order_by = "ORDER BY sales_order_kode " . $order_dir . "";
		} else if ($order_column == 4) {
			$order_by = "ORDER BY sales_order_no_po " . $order_dir . "";
		} else if ($order_column == 5) {
			$order_by = "ORDER BY principle_kode " . $order_dir . "";
		} else if ($order_column == 6) {
			$order_by = "ORDER BY karyawan_nama " . $order_dir . "";
		} else if ($order_column == 7) {
			$order_by = "ORDER BY delivery_order_draft_kirim_nama " . $order_dir . "";
		} else if ($order_column == 8) {
			$order_by = "ORDER BY delivery_order_draft_kirim_alamat " . $order_dir . "";
		} else if ($order_column == 9) {
			$order_by = "ORDER BY delivery_order_draft_kirim_area " . $order_dir . "";
		} else if ($order_column == 10) {
			$order_by = "ORDER BY tipe_delivery_order_alias " . $order_dir . "";
		} else if ($order_column == 11) {
			$order_by = "ORDER BY umur_so " . $order_dir . "";
		} else if ($order_column == 12) {
			$order_by = "ORDER BY delivery_order_draft_nominal_tunai " . $order_dir . "";
		} else if ($order_column == 13) {
			$order_by = "ORDER BY delivery_order_draft_status " . $order_dir . "";
		} else if ($order_column == 14) {
			$order_by = "ORDER BY client_pt_segmen_nama1 " . $order_dir . "";
		} else if ($order_column == 15) {
			$order_by = "ORDER BY client_pt_segmen_nama2 " . $order_dir . "";
		} else if ($order_column == 16) {
			$order_by = "ORDER BY client_pt_segmen_nama3 " . $order_dir . "";
		} else if ($order_column == 17) {
			$order_by = "ORDER BY delivery_order_draft_is_prioritas " . $order_dir . "";
		}

		$query = $this->db->query("SELECT DISTINCT 'SO Approved jadi DO Draft' AS tipe,
												do.delivery_order_draft_id,
												do.delivery_order_draft_kode,
												so.sales_order_id,
												so.sales_order_no_po,
												so.principle_id,
												principle.principle_kode,
												ky.karyawan_nama,
												cp.client_pt_segmen_id1,
												cp.client_pt_segmen_id2,
												cp.client_pt_segmen_id3,
												cps1.client_pt_segmen_nama AS client_pt_segmen_nama1,
												cps2.client_pt_segmen_nama AS client_pt_segmen_nama2,
												cps3.client_pt_segmen_nama AS client_pt_segmen_nama3,
												ISNULL(so.sales_order_kode, '') AS sales_order_kode,
												format(do.delivery_order_draft_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
												format(do.delivery_order_draft_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_expired_do,
												format(do.delivery_order_draft_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_draft_tgl_surat_jalan,
												format(do.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_rencana_kirim,
												format(do.delivery_order_draft_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_aktual_kirim,
												format(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
												DATEDIFF(DAY, so.sales_order_tgl, GETDATE()) AS umur_so,
												CASE
													WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.pabrik_id
													ELSE do.client_pt_id
												END client_pt_id,
												CASE
													WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_nama
													ELSE do.delivery_order_draft_kirim_nama
												END delivery_order_draft_kirim_nama,
												CASE
													WHEN do.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do.delivery_order_draft_ambil_alamat
													ELSE do.delivery_order_draft_kirim_alamat
												END delivery_order_draft_kirim_alamat,
												CASE
													WHEN do.delivery_order_draft_tipe_pembayaran = '0' THEN 'TUNAI'
													ELSE 'NON TUNAI'
												END AS delivery_order_draft_tipe_pembayaran,
												do.delivery_order_draft_tipe_layanan,
												do.tipe_delivery_order_id,
												tipe.tipe_delivery_order_nama,
												tipe.tipe_delivery_order_alias,
												do.delivery_order_draft_status,
												do.delivery_order_draft_kirim_area,
												CASE
													WHEN ISNULL(do.delivery_order_draft_is_prioritas, 0) = 1 THEN 'Yes'
													ELSE ''
												END AS delivery_order_draft_is_prioritas,
												ISNULL(do.delivery_order_draft_nominal_tunai, 0) AS delivery_order_draft_nominal_tunai,
												ISNULL(bso.szSalesId, '-') AS szSalesId,
												'0' AS is_ada_fdjr
						FROM sales_order so
						LEFT JOIN delivery_order_draft DO ON so.sales_order_id = do.sales_order_id
						LEFT JOIN tipe_delivery_order tipe ON do.tipe_delivery_order_id = tipe.tipe_delivery_order_id
						LEFT JOIN client_pt cp ON cp.client_pt_id = do.client_pt_id
						LEFT JOIN bosnet_so bso ON so.sales_order_no_po = bso.szFSoId
						LEFT JOIN client_pt_segmen cps1 ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
						LEFT JOIN client_pt_segmen cps2 ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
						LEFT JOIN client_pt_segmen cps3 ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
						LEFT JOIN principle ON principle.principle_id = so.principle_id
						LEFT JOIN karyawan ky ON so.sales_id = ky.karyawan_id
						WHERE format(so.sales_order_tgl, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
						AND so.sales_order_status = 'Approved'
						AND do.delivery_order_draft_status = 'Draft'
						AND do.sales_order_id IS NOT NULL
						AND tipe_sales_order_id IN ('D5A2AB04-0236-424D-859C-1888B46D6F50'',''AD89E05B-46A6-453B-8F19-886514234A21')
						AND CONVERT(NVARCHAR(36), so.depo_id) = '$depo_id'
						" . $range . "
						" . $principle_id . "
						" . $order_by . "
						OFFSET " . $start . " ROWS
						FETCH NEXT " . $end . " ROWS ONLY");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_total_laporan_lead_time_do_detail_do_draft_approved($tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range)
	{
		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "AND so.principle_id = '" . $principle_id . "' ";
		}

		if ($range == "jml_0_sd_3") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_tgl_buat_do, do_draft.delivery_order_draft_approve_tgl) <= 3";
		} else if ($range == "jml_4_sd_7") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_tgl_buat_do, do_draft.delivery_order_draft_approve_tgl) BETWEEN 4 AND 7";
		} else if ($range == "jml_8_sd_14") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_tgl_buat_do, do_draft.delivery_order_draft_approve_tgl) BETWEEN 8 AND 14";
		} else if ($range == "jml_15_sd_28") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_tgl_buat_do, do_draft.delivery_order_draft_approve_tgl) BETWEEN 15 AND 28";
		} else if ($range == "jml_29") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_tgl_buat_do, do_draft.delivery_order_draft_approve_tgl) >= 29";
		}

		$query = $this->db->query("SELECT count(DISTINCT do_draft.delivery_order_draft_id) as jml
						FROM sales_order so
						LEFT JOIN delivery_order_draft do_draft ON so.sales_order_id = do_draft.sales_order_id
						LEFT JOIN delivery_order do ON do.delivery_order_draft_id = do_draft.delivery_order_draft_id
						LEFT JOIN tipe_delivery_order tipe ON do_draft.tipe_delivery_order_id = tipe.tipe_delivery_order_id
						LEFT JOIN client_pt cp ON cp.client_pt_id = do_draft.client_pt_id
						LEFT JOIN bosnet_so bso ON so.sales_order_no_po = bso.szFSoId
						LEFT JOIN client_pt_segmen cps1 ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
						LEFT JOIN client_pt_segmen cps2 ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
						LEFT JOIN client_pt_segmen cps3 ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
						LEFT JOIN principle ON principle.principle_id = so.principle_id
						LEFT JOIN karyawan ky ON so.sales_id = ky.karyawan_id
						WHERE format(so.sales_order_tgl, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
						AND so.sales_order_status = 'Approved'
						AND do_draft.delivery_order_draft_status = 'Approved'
						AND do_draft.sales_order_id IS NOT NULL
						AND do.delivery_order_id IS NULL
						AND tipe_sales_order_id IN ('D5A2AB04-0236-424D-859C-1888B46D6F50'',''AD89E05B-46A6-453B-8F19-886514234A21')
						AND CONVERT(NVARCHAR(36), so.depo_id) = '$depo_id'
						" . $principle_id . "
						" . $range . "");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->jml;
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_laporan_lead_time_do_detail_do_draft_approved($start, $end, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range)
	{
		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "AND so.principle_id = '" . $principle_id . "' ";
		}

		if ($range == "jml_0_sd_3") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_tgl_buat_do, do_draft.delivery_order_draft_approve_tgl) <= 3";
		} else if ($range == "jml_4_sd_7") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_tgl_buat_do, do_draft.delivery_order_draft_approve_tgl) BETWEEN 4 AND 7";
		} else if ($range == "jml_8_sd_14") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_tgl_buat_do, do_draft.delivery_order_draft_approve_tgl) BETWEEN 8 AND 14";
		} else if ($range == "jml_15_sd_28") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_tgl_buat_do, do_draft.delivery_order_draft_approve_tgl) BETWEEN 15 AND 28";
		} else if ($range == "jml_29") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_tgl_buat_do, do_draft.delivery_order_draft_approve_tgl) >= 29";
		}

		$order_by = '';
		if ($order_column == 0) {
			$order_by = "ORDER BY format(do_draft.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') DESC";
		} else if ($order_column == 1) {
			$order_by = "ORDER BY delivery_order_draft_tgl_rencana_kirim " . $order_dir . "";
		} else if ($order_column == 2) {
			$order_by = "ORDER BY delivery_order_draft_kode " . $order_dir . "";
		} else if ($order_column == 3) {
			$order_by = "ORDER BY sales_order_kode " . $order_dir . "";
		} else if ($order_column == 4) {
			$order_by = "ORDER BY sales_order_no_po " . $order_dir . "";
		} else if ($order_column == 5) {
			$order_by = "ORDER BY principle_kode " . $order_dir . "";
		} else if ($order_column == 6) {
			$order_by = "ORDER BY karyawan_nama " . $order_dir . "";
		} else if ($order_column == 7) {
			$order_by = "ORDER BY delivery_order_draft_kirim_nama " . $order_dir . "";
		} else if ($order_column == 8) {
			$order_by = "ORDER BY delivery_order_draft_kirim_alamat " . $order_dir . "";
		} else if ($order_column == 9) {
			$order_by = "ORDER BY delivery_order_draft_kirim_area " . $order_dir . "";
		} else if ($order_column == 10) {
			$order_by = "ORDER BY tipe_delivery_order_alias " . $order_dir . "";
		} else if ($order_column == 11) {
			$order_by = "ORDER BY umur_so " . $order_dir . "";
		} else if ($order_column == 12) {
			$order_by = "ORDER BY delivery_order_draft_nominal_tunai " . $order_dir . "";
		} else if ($order_column == 13) {
			$order_by = "ORDER BY delivery_order_draft_status " . $order_dir . "";
		} else if ($order_column == 14) {
			$order_by = "ORDER BY client_pt_segmen_nama1 " . $order_dir . "";
		} else if ($order_column == 15) {
			$order_by = "ORDER BY client_pt_segmen_nama2 " . $order_dir . "";
		} else if ($order_column == 16) {
			$order_by = "ORDER BY client_pt_segmen_nama3 " . $order_dir . "";
		} else if ($order_column == 17) {
			$order_by = "ORDER BY delivery_order_draft_is_prioritas " . $order_dir . "";
		}

		$query = $this->db->query("SELECT DISTINCT 'DO Draft diapproved' AS tipe,
												do_draft.delivery_order_draft_id,
												do_draft.delivery_order_draft_kode,
												do_draft.sales_order_id,
												so.sales_order_no_po,
												so.principle_id,
												principle.principle_kode,
												ky.karyawan_nama,
												cp.client_pt_segmen_id1,
												cp.client_pt_segmen_id2,
												cp.client_pt_segmen_id3,
												cps1.client_pt_segmen_nama AS client_pt_segmen_nama1,
												cps2.client_pt_segmen_nama AS client_pt_segmen_nama2,
												cps3.client_pt_segmen_nama AS client_pt_segmen_nama3,
												ISNULL(so.sales_order_kode, '') AS sales_order_kode,
												format(do_draft.delivery_order_draft_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
												format(do_draft.delivery_order_draft_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_expired_do,
												format(do_draft.delivery_order_draft_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_draft_tgl_surat_jalan,
												format(do_draft.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_rencana_kirim,
												format(do_draft.delivery_order_draft_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_aktual_kirim,
												format(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
												DATEDIFF(DAY, so.sales_order_tgl, GETDATE()) AS umur_so,
												CASE
													WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.pabrik_id
													ELSE do_draft.client_pt_id
												END client_pt_id,
												CASE
													WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.delivery_order_draft_ambil_nama
													ELSE do_draft.delivery_order_draft_kirim_nama
												END delivery_order_draft_kirim_nama,
												CASE
													WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.delivery_order_draft_ambil_alamat
													ELSE do_draft.delivery_order_draft_kirim_alamat
												END delivery_order_draft_kirim_alamat,
												CASE
													WHEN do_draft.delivery_order_draft_tipe_pembayaran = '0' THEN 'TUNAI'
													ELSE 'NON TUNAI'
												END AS delivery_order_draft_tipe_pembayaran,
												do_draft.delivery_order_draft_tipe_layanan,
												do.tipe_delivery_order_id,
												tipe.tipe_delivery_order_nama,
												tipe.tipe_delivery_order_alias,
												do_draft.delivery_order_draft_status,
												do_draft.delivery_order_draft_kirim_area,
												CASE
													WHEN ISNULL(do_draft.delivery_order_draft_is_prioritas, 0) = 1 THEN 'Yes'
													ELSE ''
												END AS delivery_order_draft_is_prioritas,
												ISNULL(do_draft.delivery_order_draft_nominal_tunai, 0) AS delivery_order_draft_nominal_tunai,
												ISNULL(bso.szSalesId, '-') AS szSalesId,
												CASE
													WHEN do.delivery_order_batch_id IS NULL THEN '0'
													ELSE '1'
												END AS is_ada_fdjr
						FROM sales_order so
						LEFT JOIN delivery_order_draft do_draft ON so.sales_order_id = do_draft.sales_order_id
						LEFT JOIN delivery_order do ON do.delivery_order_draft_id = do_draft.delivery_order_draft_id
						LEFT JOIN tipe_delivery_order tipe ON do_draft.tipe_delivery_order_id = tipe.tipe_delivery_order_id
						LEFT JOIN client_pt cp ON cp.client_pt_id = do_draft.client_pt_id
						LEFT JOIN bosnet_so bso ON so.sales_order_no_po = bso.szFSoId
						LEFT JOIN client_pt_segmen cps1 ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
						LEFT JOIN client_pt_segmen cps2 ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
						LEFT JOIN client_pt_segmen cps3 ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
						LEFT JOIN principle ON principle.principle_id = so.principle_id
						LEFT JOIN karyawan ky ON so.sales_id = ky.karyawan_id
						WHERE format(so.sales_order_tgl, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
						AND so.sales_order_status = 'Approved'
						AND do_draft.delivery_order_draft_status = 'Approved'
						AND do_draft.sales_order_id IS NOT NULL
						AND do.delivery_order_id IS NULL
						AND tipe_sales_order_id IN ('D5A2AB04-0236-424D-859C-1888B46D6F50'',''AD89E05B-46A6-453B-8F19-886514234A21')
						AND CONVERT(NVARCHAR(36), so.depo_id) = '$depo_id'
						" . $range . "
						" . $principle_id . "
						" . $order_by . "
						OFFSET " . $start . " ROWS
						FETCH NEXT " . $end . " ROWS ONLY");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_total_laporan_lead_time_do_detail_do_dilaksanakan($tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range)
	{
		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "AND so.principle_id = '" . $principle_id . "' ";
		}

		if ($range == "jml_0_sd_3") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_approve_tgl, fdjr.delivery_order_batch_create_tgl) <= 3";
		} else if ($range == "jml_4_sd_7") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_approve_tgl, fdjr.delivery_order_batch_create_tgl) BETWEEN 4 AND 7";
		} else if ($range == "jml_8_sd_14") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_approve_tgl, fdjr.delivery_order_batch_create_tgl) BETWEEN 8 AND 14";
		} else if ($range == "jml_15_sd_28") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_approve_tgl, fdjr.delivery_order_batch_create_tgl) BETWEEN 15 AND 28";
		} else if ($range == "jml_29") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_approve_tgl, fdjr.delivery_order_batch_create_tgl) >= 29";
		}

		$query = $this->db->query("SELECT count(DISTINCT do_draft.delivery_order_draft_id) as jml
						FROM sales_order so
						LEFT JOIN delivery_order_draft do_draft ON so.sales_order_id = do_draft.sales_order_id
						LEFT JOIN delivery_order DO ON do.delivery_order_draft_id = do_draft.delivery_order_draft_id
						LEFT JOIN delivery_order_batch fdjr ON fdjr.delivery_order_batch_id = do.delivery_order_batch_id
						LEFT JOIN tipe_delivery_order tipe ON do_draft.tipe_delivery_order_id = tipe.tipe_delivery_order_id
						LEFT JOIN client_pt cp ON cp.client_pt_id = do_draft.client_pt_id
						LEFT JOIN bosnet_so bso ON so.sales_order_no_po = bso.szFSoId
						LEFT JOIN client_pt_segmen cps1 ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
						LEFT JOIN client_pt_segmen cps2 ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
						LEFT JOIN client_pt_segmen cps3 ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
						LEFT JOIN principle ON principle.principle_id = so.principle_id
						LEFT JOIN karyawan ky ON so.sales_id = ky.karyawan_id
						WHERE format(so.sales_order_tgl, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
						AND so.sales_order_status = 'Approved'
						AND do_draft.delivery_order_draft_status = 'Approved'
						AND fdjr.delivery_order_batch_status <> 'Draft'
						AND do.sales_order_id IS NOT NULL
						AND tipe_sales_order_id IN ('D5A2AB04-0236-424D-859C-1888B46D6F50', 'AD89E05B-46A6-453B-8F19-886514234A21')
						AND CONVERT(NVARCHAR(36), so.depo_id) = '$depo_id'
						" . $principle_id . "
						" . $range . "");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->jml;
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_laporan_lead_time_do_detail_do_dilaksanakan($start, $end, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $depo_id, $principle_id, $tipe, $range)
	{
		if ($principle_id == "") {
			$principle_id = "";
		} else {
			$principle_id = "AND so.principle_id = '" . $principle_id . "' ";
		}

		if ($range == "jml_0_sd_3") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_approve_tgl, fdjr.delivery_order_batch_create_tgl) <= 3";
		} else if ($range == "jml_4_sd_7") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_approve_tgl, fdjr.delivery_order_batch_create_tgl) BETWEEN 4 AND 7";
		} else if ($range == "jml_8_sd_14") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_approve_tgl, fdjr.delivery_order_batch_create_tgl) BETWEEN 8 AND 14";
		} else if ($range == "jml_15_sd_28") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_approve_tgl, fdjr.delivery_order_batch_create_tgl) BETWEEN 15 AND 28";
		} else if ($range == "jml_29") {
			$range = "AND DATEDIFF(DAY, do_draft.delivery_order_draft_approve_tgl, fdjr.delivery_order_batch_create_tgl) >= 29";
		}

		$order_by = '';
		if ($order_column == 0) {
			$order_by = "ORDER BY format(do_draft.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') DESC";
		} else if ($order_column == 1) {
			$order_by = "ORDER BY delivery_order_draft_tgl_rencana_kirim " . $order_dir . "";
		} else if ($order_column == 2) {
			$order_by = "ORDER BY delivery_order_draft_kode " . $order_dir . "";
		} else if ($order_column == 3) {
			$order_by = "ORDER BY sales_order_kode " . $order_dir . "";
		} else if ($order_column == 4) {
			$order_by = "ORDER BY sales_order_no_po " . $order_dir . "";
		} else if ($order_column == 5) {
			$order_by = "ORDER BY principle_kode " . $order_dir . "";
		} else if ($order_column == 6) {
			$order_by = "ORDER BY karyawan_nama " . $order_dir . "";
		} else if ($order_column == 7) {
			$order_by = "ORDER BY delivery_order_draft_kirim_nama " . $order_dir . "";
		} else if ($order_column == 8) {
			$order_by = "ORDER BY delivery_order_draft_kirim_alamat " . $order_dir . "";
		} else if ($order_column == 9) {
			$order_by = "ORDER BY delivery_order_draft_kirim_area " . $order_dir . "";
		} else if ($order_column == 10) {
			$order_by = "ORDER BY tipe_delivery_order_alias " . $order_dir . "";
		} else if ($order_column == 11) {
			$order_by = "ORDER BY umur_so " . $order_dir . "";
		} else if ($order_column == 12) {
			$order_by = "ORDER BY delivery_order_draft_nominal_tunai " . $order_dir . "";
		} else if ($order_column == 13) {
			$order_by = "ORDER BY delivery_order_draft_status " . $order_dir . "";
		} else if ($order_column == 14) {
			$order_by = "ORDER BY client_pt_segmen_nama1 " . $order_dir . "";
		} else if ($order_column == 15) {
			$order_by = "ORDER BY client_pt_segmen_nama2 " . $order_dir . "";
		} else if ($order_column == 16) {
			$order_by = "ORDER BY client_pt_segmen_nama3 " . $order_dir . "";
		} else if ($order_column == 17) {
			$order_by = "ORDER BY delivery_order_draft_is_prioritas " . $order_dir . "";
		}

		$query = $this->db->query("SELECT DISTINCT 'DO Dilaksanakan' AS tipe,
										do_draft.delivery_order_draft_id,
										do_draft.delivery_order_draft_kode,
										do_draft.sales_order_id,
										so.sales_order_no_po,
										so.principle_id,
										principle.principle_kode,
										ky.karyawan_nama,
										cp.client_pt_segmen_id1,
										cp.client_pt_segmen_id2,
										cp.client_pt_segmen_id3,
										cps1.client_pt_segmen_nama AS client_pt_segmen_nama1,
										cps2.client_pt_segmen_nama AS client_pt_segmen_nama2,
										cps3.client_pt_segmen_nama AS client_pt_segmen_nama3,
										ISNULL(so.sales_order_kode, '') AS sales_order_kode,
										format(do_draft.delivery_order_draft_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_buat_do,
										format(do_draft.delivery_order_draft_tgl_expired_do, 'dd-MM-yyyy') AS delivery_order_draft_tgl_expired_do,
										format(do_draft.delivery_order_draft_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_draft_tgl_surat_jalan,
										format(do_draft.delivery_order_draft_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_rencana_kirim,
										format(do_draft.delivery_order_draft_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_draft_tgl_aktual_kirim,
										format(so.sales_order_tgl_kirim, 'dd-MM-yyyy') AS sales_order_tgl_kirim,
										DATEDIFF(DAY, so.sales_order_tgl, GETDATE()) AS umur_so,
										CASE
											WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.pabrik_id
											ELSE do_draft.client_pt_id
										END client_pt_id,
										CASE
											WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.delivery_order_draft_ambil_nama
											ELSE do_draft.delivery_order_draft_kirim_nama
										END delivery_order_draft_kirim_nama,
										CASE
											WHEN do_draft.delivery_order_draft_tipe_layanan = 'Pickup Only' THEN do_draft.delivery_order_draft_ambil_alamat
											ELSE do_draft.delivery_order_draft_kirim_alamat
										END delivery_order_draft_kirim_alamat,
										CASE
											WHEN do_draft.delivery_order_draft_tipe_pembayaran = '0' THEN 'TUNAI'
											ELSE 'NON TUNAI'
										END AS delivery_order_draft_tipe_pembayaran,
										do_draft.delivery_order_draft_tipe_layanan,
										do.tipe_delivery_order_id,
										tipe.tipe_delivery_order_nama,
										tipe.tipe_delivery_order_alias,
										do_draft.delivery_order_draft_status,
										do_draft.delivery_order_draft_kirim_area,
										CASE
											WHEN ISNULL(do_draft.delivery_order_draft_is_prioritas, 0) = 1 THEN 'Yes'
											ELSE ''
										END AS delivery_order_draft_is_prioritas,
										ISNULL(do_draft.delivery_order_draft_nominal_tunai, 0) AS delivery_order_draft_nominal_tunai,
										ISNULL(bso.szSalesId, '-') AS szSalesId,
										CASE
											WHEN do.delivery_order_batch_id IS NULL THEN '0'
											ELSE '1'
										END AS is_ada_fdjr
						FROM sales_order so
						LEFT JOIN delivery_order_draft do_draft ON so.sales_order_id = do_draft.sales_order_id
						LEFT JOIN delivery_order DO ON do.delivery_order_draft_id = do_draft.delivery_order_draft_id
						LEFT JOIN delivery_order_batch fdjr ON fdjr.delivery_order_batch_id = do.delivery_order_batch_id
						LEFT JOIN tipe_delivery_order tipe ON do_draft.tipe_delivery_order_id = tipe.tipe_delivery_order_id
						LEFT JOIN client_pt cp ON cp.client_pt_id = do_draft.client_pt_id
						LEFT JOIN bosnet_so bso ON so.sales_order_no_po = bso.szFSoId
						LEFT JOIN client_pt_segmen cps1 ON cps1.client_pt_segmen_id = cp.client_pt_segmen_id1
						LEFT JOIN client_pt_segmen cps2 ON cps2.client_pt_segmen_id = cp.client_pt_segmen_id2
						LEFT JOIN client_pt_segmen cps3 ON cps3.client_pt_segmen_id = cp.client_pt_segmen_id3
						LEFT JOIN principle ON principle.principle_id = so.principle_id
						LEFT JOIN karyawan ky ON so.sales_id = ky.karyawan_id
						WHERE format(so.sales_order_tgl, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
						AND so.sales_order_status = 'Approved'
						AND do_draft.delivery_order_draft_status = 'Approved'
						AND fdjr.delivery_order_batch_status <> 'Draft'
						AND do.sales_order_id IS NOT NULL
						AND tipe_sales_order_id IN ('D5A2AB04-0236-424D-859C-1888B46D6F50', 'AD89E05B-46A6-453B-8F19-886514234A21')
						AND CONVERT(NVARCHAR(36), so.depo_id) = '$depo_id'
						" . $range . "
						" . $principle_id . "
						" . $order_by . "
						OFFSET " . $start . " ROWS
						FETCH NEXT " . $end . " ROWS ONLY");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}
}
