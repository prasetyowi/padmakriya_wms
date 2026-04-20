<?php

class M_LaporanStockMovement extends CI_Model
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

	public function count_all_data($tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2, $mode)
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
			$principle = "and sku.principle_id = '$principle_id' ";
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

		// if ($mode == 'realtime') {
		// 	$query =  $this->db->query("SELECT count(ssc.sku_stock_card_id) as hasil FROM sku_stock_card ssc
		// 	LEFT JOIN depo ON depo.depo_id = ssc.depo_id
		// 	LEFT JOIN depo_detail dd ON dd.depo_detail_id = ssc.depo_detail_id
		// 	LEFT JOIN sku ON sku.sku_id = ssc.sku_id
		// 	LEFT JOIN principle ON principle.principle_id = sku.principle_id
		// 	LEFT JOIN sku_stock ss ON ss.sku_stock_id = ssc.sku_stock_id AND ss.sku_id = ssc.sku_id
		// 	LEFT JOIN (
		// 		SELECT
		// 			sku_id,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Stock Opname' THEN sku_stock_card_qty ELSE 0 END) AS opname,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Permintaan Pengeluaran Barang' THEN sku_stock_card_qty ELSE 0 END) AS penjualan,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Bukti Terima Barang dari Outlet' and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS penerimaan_retur_outlet,
		// 			SUM(CASE WHEN sku_stock_card_keterangan IN ('Mutasi Pallet Antar Gudang','Mutasi Stock') and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS mutasi_in_antar_gudang,
		// 			SUM(CASE WHEN sku_stock_card_keterangan IN ('Mutasi Pallet Antar Gudang','Mutasi Stock') and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS mutasi_out_antar_gudang,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Koreksi Barang Masuk' and sku_stock_card_keterangan ='Koreksi Stok Pallet' and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS koreksi_adjustmen_in,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Koreksi Barang Keluar' and sku_stock_card_keterangan ='Koreksi Stok Pallet' and sku_stock_card_jenis ='K'  THEN sku_stock_card_qty ELSE 0 END) AS koreksi_adjustmen_out,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Konfirmasi Penerimaan Barang Supplier' THEN sku_stock_card_qty ELSE 0 END) AS penerimaan_supplier,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Konversi in' and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS pembongkaran_D,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Konversi out' and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS pembongkaran_K
		// 		FROM sku_stock_card
		// 		GROUP BY sku_id
		// 	) AS skuu ON skuu.sku_id = ssc.sku_id
		// 	LEFT JOIN (
		// 		SELECT
		// 			sku_stock_card_id,
		// 			SUM(sku_stock_card_qty) AS counta2
		// 		FROM sku_stock_card
		// 		GROUP BY sku_stock_card_id
		// 	) AS skuu2 ON skuu2.sku_stock_card_id = ssc.sku_stock_card_id
		// 	WHERE FORMAT(ssc.sku_stock_card_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
		// 	AND ssc.depo_id ='$depo_id' 
		// 	--AND
		// 	-- FORMAT(ssc.sku_stock_card_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl_2'
		// 									--$client_wms
		// 									$depo_detail
		// 									--$principle
		// 									$skukode
		// 									$skunama
		// 	")->row();
		// } else {
		// 	$query =  $this->db->query("SELECT count(ssc.sku_stock_card_his_daily_id) as hasil FROM sku_stock_card_his_daily ssc
		// 	LEFT JOIN depo ON depo.depo_id = ssc.depo_id
		// 	LEFT JOIN depo_detail dd ON dd.depo_detail_id = ssc.depo_detail_id
		// 	LEFT JOIN sku ON sku.sku_id = ssc.sku_id
		// 	LEFT JOIN principle ON principle.principle_id = sku.principle_id
		// 	LEFT JOIN sku_stock ss ON ss.sku_stock_id = ssc.sku_stock_id AND ss.sku_id = ssc.sku_id
		// 	LEFT JOIN (
		// 		SELECT
		// 			sku_id,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Stock Opname' THEN sku_stock_card_qty ELSE 0 END) AS opname,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Permintaan Pengeluaran Barang' THEN sku_stock_card_qty ELSE 0 END) AS penjualan,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Bukti Terima Barang dari Outlet' and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS penerimaan_retur_outlet,
		// 			SUM(CASE WHEN sku_stock_card_keterangan IN ('Mutasi Pallet Antar Gudang','Mutasi Stock') and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS mutasi_in_antar_gudang,
		// 			SUM(CASE WHEN sku_stock_card_keterangan IN ('Mutasi Pallet Antar Gudang','Mutasi Stock') and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS mutasi_out_antar_gudang,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Koreksi Barang Masuk' and sku_stock_card_keterangan ='Koreksi Stok Pallet' and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS koreksi_adjustmen_in,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Koreksi Barang Keluar' and sku_stock_card_keterangan ='Koreksi Stok Pallet' and sku_stock_card_jenis ='K'  THEN sku_stock_card_qty ELSE 0 END) AS koreksi_adjustmen_out,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Konfirmasi Penerimaan Barang Supplier' THEN sku_stock_card_qty ELSE 0 END) AS penerimaan_supplier,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Konversi in' and sku_stock_card_jenis ='D' THEN sku_stock_card_qty ELSE 0 END) AS pembongkaran_D,
		// 			SUM(CASE WHEN sku_stock_card_keterangan = 'Konversi out' and sku_stock_card_jenis ='K' THEN sku_stock_card_qty ELSE 0 END) AS pembongkaran_K
		// 		FROM sku_stock_card_his_daily
		// 		GROUP BY sku_id
		// 	) AS skuu ON skuu.sku_id = ssc.sku_id
		// 	LEFT JOIN (
		// 		SELECT
		// 			sku_stock_card_his_daily_id,
		// 			SUM(sku_stock_card_qty) AS counta2
		// 		FROM sku_stock_card_his_daily
		// 		GROUP BY sku_stock_card_his_daily_id
		// 	) AS skuu2 ON skuu2.sku_stock_card_his_daily_id = ssc.sku_stock_card_his_daily_id
		// 	WHERE FORMAT(ssc.sku_stock_card_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
		// 	AND ssc.depo_id ='$depo_id' 
		// 	--AND
		// 	-- FORMAT(ssc.sku_stock_card_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl_2'
		// 									--$client_wms
		// 									$depo_detail
		// 									--$principle
		// 									$skukode
		// 									$skunama
		// 	")->row();
		// }

		if ($mode == 'realtime') {
			$query =  $this->db->query("SELECT count(sku_stock_id) AS hasil FROM sku_stock ssc
                                        INNER JOIN sku on ssc.sku_id = sku.sku_id
                                            WHERE depo_id = '$depo_id' 
                                            $depo_detail
                                            $skukode
                                            $skunama
											$principle
                                            AND isnull(sku_stock_awal, 0) + isnull(sku_stock_masuk, 0) - isnull(sku_stock_keluar, 0) > 0")->row();
		} else {
			$query =  $this->db->query("SELECT count(sku_stock_id) AS hasil FROM sku_stock_his_daily ssc
										INNER JOIN sku on ssc.sku_id = sku.sku_id
                                            WHERE depo_id = '$depo_id'
											AND sku_stock_tgl = '$tgl1'
                                            $depo_detail
                                            $skukode
                                            $skunama
											$principle
                                            AND isnull(sku_stock_awal, 0) + isnull(sku_stock_masuk, 0) - isnull(sku_stock_keluar, 0) > 0")->row();
		}


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

	public function GetDetailNewProcedure($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2, $mode)
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

		$query = $this->db->query("exec report_transaksi_stock_movement $start,$length,$order_column,'$order_dir','$tgl_1','$tgl_2','$client_wms_id','$depo_id','$depo_detail_id','$principle_id','$sku_kode','$sku_nama','$tgl1','$tgl2', '$mode'");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}

	public function Get_laporan_transaksi_stock_movement_detail($tipe_report, $tgl1, $tgl2, $tipe_transaksi, $client_wms, $depo_id, $depo_detail_id, $principle_id, $sku_id, $sku_kode, $sku_nama, $sku_stock_expired_date, $data_stock_awal, $mode)
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

		if ($mode == 'realtime') {
			$table = 'sku_stock_card';
		} else {
			$table = 'sku_stock_card_his_daily';
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
									FROM $table sku_stock_card
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
}
