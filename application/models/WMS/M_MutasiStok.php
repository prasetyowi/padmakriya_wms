<?php

class M_MutasiStok extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function Get_PencarianMutasiStokTable($tgl1, $tgl2, $id, $gudang_asal, $client_wms, $principle, $checker, $status)
	{
		if ($id != "") {
			$id = " AND hdr.tr_mutasi_stok_kode LIKE '%$id%' ";
		} else {
			$id = "";
		}

		if ($gudang_asal != "") {
			$gudang_asal = " AND convert(nvarchar(36),dtl.depo_detail_asal) = '$gudang_asal' ";
		} else {
			$gudang_asal = "";
		}

		if ($client_wms != "") {
			$client_wms = " AND convert(nvarchar(36),hdr.client_wms_id) = '$client_wms' ";
		} else {
			$client_wms = "";
		}

		if ($principle != "") {
			$principle = " AND convert(nvarchar(36),hdr.principle_id) = '$principle' ";
		} else {
			$principle = "";
		}

		if ($checker != "") {
			$checker = " AND hdr.tr_mutasi_stok_nama_checker LIKE '%$checker%' ";
		} else {
			$checker = "";
		}

		if ($status != "") {
			$status = " AND hdr.tr_mutasi_stok_status = '$status' ";
		} else {
			$status = "";
		}

		$query = $this->db->query("SELECT
										hdr.tr_mutasi_stok_id,
										hdr.client_wms_id,
										hdr.principle_id,
										principle.principle_kode,
										hdr.tr_mutasi_stok_kode,
										FORMAT(hdr.tr_mutasi_stok_tanggal,'dd/MM/yyyy') AS tr_mutasi_stok_tanggal,
										hdr.tr_mutasi_stok_keterangan,
										hdr.tr_mutasi_stok_status,
										hdr.depo_id_asal,
										dtl.depo_detail_asal,
										gudang.depo_detail_nama,
										hdr.tr_mutasi_stok_tgl_create,
										hdr.tr_mutasi_stok_who_create,
										hdr.tr_mutasi_stok_nama_checker,
										hdr.tr_mutasi_stok_tgl_update,
										hdr.tr_mutasi_stokt_who_update
									FROM tr_mutasi_stok hdr
									LEFT JOIN tr_mutasi_stok_detail dtl
									ON dtl.tr_mutasi_stok_id = hdr.tr_mutasi_stok_id
									LEFT JOIN principle
									ON principle.principle_id = hdr.principle_id
									LEFT JOIN depo_detail gudang
									ON gudang.depo_detail_id = dtl.depo_detail_asal
									WHERE hdr.depo_id_asal = '" . $this->session->userdata('depo_id') . "'
									AND FORMAT(hdr.tr_mutasi_stok_tanggal,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
									AND hdr.tr_mutasi_stok_status IN ('Approved','In Progress', 'Completed')
									" . $gudang_asal . "
									" . $client_wms . "
									" . $principle . "
									" . $checker . "
									" . $status . "
									GROUP BY 
										hdr.tr_mutasi_stok_id,
										hdr.client_wms_id,
										hdr.principle_id,
										principle.principle_kode,
										hdr.tr_mutasi_stok_kode,
										FORMAT(hdr.tr_mutasi_stok_tanggal,'dd/MM/yyyy'),
										hdr.tr_mutasi_stok_keterangan,
										hdr.tr_mutasi_stok_status,
										hdr.depo_id_asal,
										dtl.depo_detail_asal,
										gudang.depo_detail_nama,
										hdr.tr_mutasi_stok_tgl_create,
										hdr.tr_mutasi_stok_who_create,
										hdr.tr_mutasi_stok_nama_checker,
										hdr.tr_mutasi_stok_tgl_update,
										hdr.tr_mutasi_stokt_who_update
									ORDER BY FORMAT(hdr.tr_mutasi_stok_tanggal,'dd/MM/yyyy') DESC");


		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Checker()
	{
		$query = $this->db->query("SELECT
										*
									FROM karyawan
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
									ORDER BY karyawan_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
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

	public function Get_Gudang($gudangAsal = "", $tipeMutasiID = "")
	{
		if ($tipeMutasiID == 'C02CC613-2FAB-49A9-9372-B27D9444FE93') {
			$query = $this->db->query("SELECT
											*
										FROM depo_detail
										WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND depo_detail_id = '$gudangAsal'
										ORDER BY depo_detail_nama ASC");
		} else if ($gudangAsal == "") {
			$query = $this->db->query("SELECT
										*
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY depo_detail_nama ASC");
		} else {
			$query = $this->db->query("SELECT
										*
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND depo_detail_id != '$gudangAsal'
									ORDER BY depo_detail_nama ASC");
		}

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_GudangTujuanByTipe($gudang_asal)
	{
		$query = $this->db->query("SELECT
										*
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									AND depo_detail_id = '$gudang_asal'
									ORDER BY depo_detail_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Status()
	{
		$query = $this->db->query("SELECT
										tr_mutasi_pallet_draft_status AS status_mutasi
									FROM tr_mutasi_pallet_draft
									GROUP BY tr_mutasi_pallet_draft_status
									ORDER BY tr_mutasi_pallet_draft_status ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_TipeTransaksi()
	{
		$query = $this->db->query("SELECT
										tipe_mutasi_id,
										tipe_mutasi_nama AS tipe_transaksi
									FROM tipe_mutasi
									WHERE tipe_mutasi_flag_2 = 'Pallet'
									ORDER BY tipe_mutasi_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_SKU($depo_detail_id, $principle, $perusahaan, $filter_sku_stock_id)
	{
		$filter_sku_stock_id_str = "";

		if (isset($filter_sku_stock_id)) {
			if (count($filter_sku_stock_id) > 0) {
				$filter_sku_stock_id_str = "AND CONVERT(nvarchar(36), ss.sku_stock_id) NOT IN (" . implode(",", $filter_sku_stock_id) . ")";
			}
		} else {
			$filter_sku_stock_id_str = "";
		}

		$query = $this->db->query("SELECT
									ss.sku_stock_id,
									ss.sku_id,
									s.sku_kode,
									s.sku_nama_produk,
									FORMAT(ss.sku_stock_expired_date, 'dd/MM/yyyy') AS sku_stock_expired_date,
									ISNULL(ss.sku_stock_awal, 0) AS sku_stock_awal,
									ISNULL(ss.sku_stock_masuk, 0) AS sku_stock_masuk,
									ISNULL(ss.sku_stock_alokasi, 0) AS sku_stock_alokasi,
									ISNULL(ss.sku_stock_saldo_alokasi, 0) AS sku_stock_saldo_alokasi,
									ISNULL(ss.sku_stock_keluar, 0) AS sku_stock_keluar,
									ISNULL(ss.sku_stock_akhir, 0) AS sku_stock_akhir,
									ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(ss.sku_stock_saldo_alokasi, 0) - ISNULL(ss.sku_stock_keluar, 0) AS sku_stock_qty,
									ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(ss.sku_stock_saldo_alokasi, 0) - ISNULL(ss.sku_stock_keluar, 0) AS sku_stock_qty_awal
									FROM sku_stock ss
									INNER JOIN sku s
									ON s.sku_id = ss.sku_id
									WHERE CONVERT(nvarchar(36), ss.depo_id) = '" . $this->session->userdata('depo_id') . "'
									AND CONVERT(nvarchar(36), ss.depo_detail_id) = '$depo_detail_id'
									AND CONVERT(nvarchar(36), ss.client_wms_id) = '$perusahaan'
									AND CONVERT(nvarchar(36), s.principle_id) = '$principle'
									" . $filter_sku_stock_id_str . "
									ORDER BY s.sku_kode ASC, ss.sku_stock_expired_date DESC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_pallet_by_sku_stock_id($sku_stock_id)
	{

		$query = $this->db->query("SELECT
									a.pallet_id,
									b.pallet_kode,
									a.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_satuan,
									sku.sku_kemasan,
									SUM(ISNULL(a.sku_stock_qty, 0) + ISNULL(a.sku_stock_terima, 0) + ISNULL(a.sku_stock_in, 0) - ISNULL(a.sku_stock_ambil, 0) - ISNULL(a.sku_stock_out, 0)) AS sku_stock_qty
									FROM pallet_detail a
									INNER JOIN pallet b
									ON b.pallet_id = a.pallet_id
									INNER JOIN sku
									ON sku.sku_id = a.sku_id
									WHERE CONVERT(nvarchar(36), a.sku_stock_id) = '$sku_stock_id'
									GROUP BY a.pallet_id,
											b.pallet_kode,
											a.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.sku_satuan,
											sku.sku_kemasan
									ORDER BY b.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_list_mutasi_stock_detail($tr_mutasi_stok_id, $filter_sku_stock_id, $arr_list_multi_sku, $arr_tr_mutasi_stok_detail2)
	{
		$filter_sku_stock_id_str = "";

		if (isset($filter_sku_stock_id)) {
			if (count($filter_sku_stock_id) > 0) {
				$filter_sku_stock_id_str = "AND CONVERT(nvarchar(36), ss.sku_stock_id) IN (" . implode(",", $filter_sku_stock_id) . ")";
			}
		} else {
			$filter_sku_stock_id_str = "";
		}

		$union = " UNION ALL ";
		$table_sementara = "";
		foreach ($arr_list_multi_sku as $key => $value) {
			$sku_stock_qty = $value['sku_stock_qty'] == "" || $value['sku_stock_qty'] === NULL ? 0 : $value['sku_stock_qty'];

			$table_sementara .= "SELECT '" . $value['sku_stock_id'] . "' AS sku_stock_id, '" . $value['sku_id'] . "' AS sku_id, " . $sku_stock_qty . " AS sku_stock_qty, '" . $value['sku_stock_expired_date'] . "' AS sku_stock_expired_date, '" . $value['pallet_id_asal'] . "' AS pallet_id_asal";

			if ($key < count($arr_list_multi_sku) - 1) {
				$table_sementara .= $union;
			}
		}

		if (count($arr_tr_mutasi_stok_detail2) > 0) {

			$union2 = " UNION ALL ";
			$table_sementara2 = "";
			foreach ($arr_tr_mutasi_stok_detail2 as $key => $value) {
				$sku_stock_qty = $value['sku_stock_qty'] == "" || $value['sku_stock_qty'] === NULL ? 0 : $value['sku_stock_qty'];

				$table_sementara2 .= "SELECT '" . $value['tr_mutasi_stok_detail_id'] . "' AS tr_mutasi_stok_detail_id, '" . $value['no_urut'] . "' AS no_urut, '" . $value['depo_detail_id_tujuan'] . "' AS depo_detail_id_tujuan, '" . $value['pallet_id_tujuan'] . "' AS pallet_id_tujuan, '" . $value['sku_stock_id_tujuan'] . "' AS sku_stock_id_tujuan,'" . $value['sku_id'] . "' AS sku_id,'" . $value['sku_stock_expired_date'] . "' AS sku_stock_expired_date," . $sku_stock_qty . " AS sku_stock_qty";

				if ($key < count($arr_tr_mutasi_stok_detail2) - 1) {
					$table_sementara2 .= $union2;
				}
			}

			$query = $this->db->query("SELECT
									tms.tr_mutasi_stok_id,
									tmsd.tr_mutasi_stok_detail_id,
									tmsd.sku_stock_id,
									tmsd.sku_id,
									s.sku_kode,
									s.sku_nama_produk,
									s.sku_satuan,
									s.sku_kemasan,
									FORMAT(tmsd.sku_stock_expired_date, 'dd/MM/yyyy') AS sku_stock_expired_date,
									ISNULL(tmsd.qty, 0) AS sku_stock_qty,
									ISNULL(pd.sku_stock_qty, 0) + ISNULL(pd.sku_stock_terima, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_ambil, 0) - ISNULL(pd.sku_stock_out, 0) AS sku_stock_qty_awal,
									tmsd.pallet_id_asal,
									CASE WHEN SUM(ISNULL(tmsd2.sku_stock_qty, 0)) = ISNULL(tmsd.qty, 0) THEN '1' ELSE '0' END cek_tmsd2
									FROM tr_mutasi_stok tms
									LEFT JOIN tr_mutasi_stok_detail tmsd
									ON tmsd.tr_mutasi_stok_id = tms.tr_mutasi_stok_id
									LEFT JOIN (" . $table_sementara2 . ") tmsd2
									ON tmsd2.tr_mutasi_stok_detail_id = tmsd.tr_mutasi_stok_detail_id
									INNER JOIN sku s
									ON s.sku_id = tmsd.sku_id
									LEFT JOIN pallet_detail pd
									ON CONVERT(nvarchar(36), pd.pallet_id) = CONVERT(nvarchar(36), tmsd.pallet_id_asal)
									AND CONVERT(nvarchar(36), pd.sku_stock_id) = CONVERT(nvarchar(36), tmsd.sku_stock_id)
									WHERE CONVERT(nvarchar(36), tms.tr_mutasi_stok_id) = '$tr_mutasi_stok_id'
									GROUP BY tms.tr_mutasi_stok_id,
											tmsd.tr_mutasi_stok_detail_id,
											tmsd.sku_stock_id,
											tmsd.sku_id,
											s.sku_kode,
											s.sku_nama_produk,
											s.sku_satuan,
											s.sku_kemasan,
											FORMAT(tmsd.sku_stock_expired_date, 'dd/MM/yyyy'),
											ISNULL(tmsd.qty, 0),
											ISNULL(pd.sku_stock_qty, 0) + ISNULL(pd.sku_stock_terima, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_ambil, 0) - ISNULL(pd.sku_stock_out, 0),
											tmsd.pallet_id_asal
									ORDER BY s.sku_kode ASC, FORMAT(tmsd.sku_stock_expired_date, 'dd/MM/yyyy') DESC");

			if ($query->num_rows() == 0) {
				$query = array();
			} else {
				$query = $query->result_array();
			}
		} else {

			$query = $this->db->query("SELECT
									tms.tr_mutasi_stok_id,
									tmsd.tr_mutasi_stok_detail_id,
									tmsd.sku_stock_id,
									tmsd.sku_id,
									s.sku_kode,
									s.sku_nama_produk,
									s.sku_satuan,
									s.sku_kemasan,
									FORMAT(tmsd.sku_stock_expired_date, 'dd/MM/yyyy') AS sku_stock_expired_date,
									ISNULL(tmsd.qty, 0) AS sku_stock_qty,
									ISNULL(pd.sku_stock_qty, 0) + ISNULL(pd.sku_stock_terima, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_ambil, 0) - ISNULL(pd.sku_stock_out, 0) AS sku_stock_qty_awal,
									tmsd.pallet_id_asal,
									'0' AS cek_tmsd2
									FROM tr_mutasi_stok tms
									LEFT JOIN tr_mutasi_stok_detail tmsd
									ON tmsd.tr_mutasi_stok_id = tms.tr_mutasi_stok_id
									INNER JOIN sku s
									ON s.sku_id = tmsd.sku_id
									LEFT JOIN pallet_detail pd
									ON CONVERT(nvarchar(36), pd.pallet_id) = CONVERT(nvarchar(36), tmsd.pallet_id_asal)
									AND CONVERT(nvarchar(36), pd.sku_stock_id) = CONVERT(nvarchar(36), tmsd.sku_stock_id)
									WHERE CONVERT(nvarchar(36), tms.tr_mutasi_stok_id) = '$tr_mutasi_stok_id'
									ORDER BY s.sku_kode ASC, tmsd.sku_stock_expired_date DESC");

			if ($query->num_rows() == 0) {
				$query = array();
			} else {
				$query = $query->result_array();
			}
		}

		return $query;
		// return $this->db->last_query();
	}

	public function cek_mutasi_stok_detail2($tr_mutasi_stok_id, $arr_tr_mutasi_stok_detail2)
	{

		$union = " UNION ALL ";
		$table_sementara = "";

		if (count($arr_tr_mutasi_stok_detail2) > 0) {
			foreach ($arr_tr_mutasi_stok_detail2 as $key => $value) {
				$sku_stock_qty = $value['sku_stock_qty'] == "" || $value['sku_stock_qty'] === NULL ? 0 : $value['sku_stock_qty'];

				$table_sementara .= "SELECT '" . $value['tr_mutasi_stok_detail_id'] . "' AS tr_mutasi_stok_detail_id, '" . $value['no_urut'] . "' AS no_urut, '" . $value['depo_detail_id_tujuan'] . "' AS depo_detail_id_tujuan, '" . $value['pallet_id_tujuan'] . "' AS pallet_id_tujuan, '" . $value['sku_id'] . "' AS sku_id, " . $sku_stock_qty . " AS sku_stock_qty, '" . $value['sku_stock_expired_date'] . "' AS sku_stock_expired_date";

				if ($key < count($arr_tr_mutasi_stok_detail2) - 1) {
					$table_sementara .= $union;
				}
			}

			$query = $this->db->query("SELECT
										d.tr_mutasi_stok_detail_id,
										d.tr_mutasi_stok_id,
										d.depo_detail_asal,
										d.pallet_id_asal,
										d.sku_stock_id,
										d.sku_id,
										s.sku_kode,
										s.sku_nama_produk,
										FORMAT(d.sku_stock_expired_date, 'dd/MM/yyyy') AS sku_stock_expired_date,
										d.qty
										FROM tr_mutasi_stok_detail d
										LEFT JOIN sku s
										ON s.sku_id = d.sku_id
										LEFT JOIN (" . $table_sementara . ") d2_tmp
										ON d2_tmp.tr_mutasi_stok_detail_id = d.tr_mutasi_stok_detail_id
										WHERE d.tr_mutasi_stok_id = '$tr_mutasi_stok_id'
										AND d2_tmp.tr_mutasi_stok_detail_id IS NULL
										ORDER BY s.sku_kode,d.sku_stock_expired_date ASC");

			if ($query->num_rows() == 0) {
				$query = array();
			} else {
				$query = $query->result_array();
			}
		} else {
			$query = array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_list_mutasi_stock_detail2($tr_mutasi_stok_detail_id, $arr_tr_mutasi_stok_detail2)
	{

		$union = " UNION ALL ";
		$table_sementara = "";

		if (count($arr_tr_mutasi_stok_detail2) > 0) {
			foreach ($arr_tr_mutasi_stok_detail2 as $key => $value) {
				$sku_stock_qty = $value['sku_stock_qty'] == "" || $value['sku_stock_qty'] === NULL ? 0 : $value['sku_stock_qty'];

				$table_sementara .= "SELECT '" . $value['tr_mutasi_stok_detail_id'] . "' AS tr_mutasi_stok_detail_id, '" . $value['no_urut'] . "' AS no_urut, '" . $value['depo_detail_id_tujuan'] . "' AS depo_detail_id_tujuan, '" . $value['pallet_id_tujuan'] . "' AS pallet_id_tujuan, '" . $value['sku_id'] . "' AS sku_id, " . $sku_stock_qty . " AS sku_stock_qty, '" . $value['sku_stock_expired_date'] . "' AS sku_stock_expired_date";

				if ($key < count($arr_tr_mutasi_stok_detail2) - 1) {
					$table_sementara .= $union;
				}
			}

			$query = $this->db->query("SELECT a.*,isnull(d.depo_detail_nama,'') as depo_detail_tujuan,isnull(p.pallet_kode,'') as pallet_tujuan FROM (" . $table_sementara . ") a 
			left join depo_detail d on convert(nvarchar(36),d.depo_detail_id)=a.depo_detail_id_tujuan
			left join pallet p on convert(nvarchar(36),p.pallet_id)=a.pallet_id_tujuan
			WHERE tr_mutasi_stok_detail_id='$tr_mutasi_stok_detail_id'");

			if ($query->num_rows() == 0) {
				$query = array();
			} else {
				$query = $query->result_array();
			}
		} else {
			$query = array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_total_qty_mutasi_stock_detail2($tr_mutasi_stok_detail_id, $arr_tr_mutasi_stok_detail2)
	{

		$union = " UNION ALL ";
		$table_sementara = "";

		if (count($arr_tr_mutasi_stok_detail2) > 0) {
			foreach ($arr_tr_mutasi_stok_detail2 as $key => $value) {
				$sku_stock_qty = $value['sku_stock_qty'] == "" || $value['sku_stock_qty'] === NULL ? 0 : $value['sku_stock_qty'];

				$table_sementara .= "SELECT '" . $value['tr_mutasi_stok_detail_id'] . "' AS tr_mutasi_stok_detail_id, '" . $value['no_urut'] . "' AS no_urut, '" . $value['depo_detail_id_tujuan'] . "' AS depo_detail_id_tujuan, '" . $value['pallet_id_tujuan'] . "' AS pallet_id_tujuan, '" . $value['sku_id'] . "' AS sku_id, " . $sku_stock_qty . " AS sku_stock_qty, '" . $value['sku_stock_expired_date'] . "' AS sku_stock_expired_date";

				if ($key < count($arr_tr_mutasi_stok_detail2) - 1) {
					$table_sementara .= $union;
				}
			}

			$query = $this->db->query("SELECT SUM(sku_stock_qty) as total FROM (" . $table_sementara . ") a
			WHERE tr_mutasi_stok_detail_id='$tr_mutasi_stok_detail_id'");

			if ($query->num_rows() == 0) {
				$query = 0;
			} else {
				$query = $query->row(0)->total;
			}
		} else {
			$query = 0;
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_GudangById($gudang_id)
	{
		$this->db->select("depo_detail_nama")
			->from("depo_detail")
			->where("depo_detail_id", $gudang_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_PrincipleById($principle)
	{
		$this->db->select("principle_kode")
			->from("principle")
			->where("principle_id", $principle);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_PerusahaanById($perusahaan)
	{
		$this->db->select("client_wms_nama")
			->from("client_wms")
			->where("client_wms_id", $perusahaan);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function Get_NewID()
	{
		$query = $this->db->query("SELECT NEWID() AS kode");
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->kode;
		}

		return $query;
	}

	public function Get_tr_mutasi_stok_header($tr_mutasi_stok_id)
	{
		$query = $this->db->query("SELECT
									hdr.tr_mutasi_stok_id,
									hdr.client_wms_id,
									hdr.principle_id,
									hdr.tr_mutasi_stok_kode,
									FORMAT(hdr.tr_mutasi_stok_tanggal,'dd/MM/yyyy') AS tr_mutasi_stok_tanggal,
									hdr.tr_mutasi_stok_keterangan,
									hdr.tr_mutasi_stok_status,
									hdr.depo_id_asal,
									dtl.depo_detail_asal,
									hdr.tr_mutasi_stok_tgl_create,
									hdr.tr_mutasi_stok_who_create,
									hdr.tr_mutasi_stok_nama_checker,
									hdr.tr_mutasi_stok_tgl_update,
									hdr.tr_mutasi_stokt_who_update
									FROM tr_mutasi_stok hdr
									LEFT JOIN tr_mutasi_stok_detail dtl
									ON hdr.tr_mutasi_stok_id = dtl.tr_mutasi_stok_id
									WHERE CONVERT(NVARCHAR(36),hdr.tr_mutasi_stok_id) = '$tr_mutasi_stok_id'
									GROUP BY hdr.tr_mutasi_stok_id,
									hdr.client_wms_id,
									hdr.principle_id,
									hdr.tr_mutasi_stok_kode,
									FORMAT(hdr.tr_mutasi_stok_tanggal,'dd/MM/yyyy'),
									hdr.tr_mutasi_stok_keterangan,
									hdr.tr_mutasi_stok_status,
									hdr.depo_id_asal,
									dtl.depo_detail_asal,
									hdr.tr_mutasi_stok_tgl_create,
									hdr.tr_mutasi_stok_who_create,
									hdr.tr_mutasi_stok_nama_checker,
									hdr.tr_mutasi_stok_tgl_update,
									hdr.tr_mutasi_stokt_who_update");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->row();
		}

		return $query;
	}

	public function Get_tr_mutasi_stok_detail($tr_mutasi_stok_id)
	{
		$query = $this->db->query("SELECT
									dtl.tr_mutasi_stok_detail_id,
									dtl.tr_mutasi_stok_id,
									dtl.depo_detail_asal,
									dtl.pallet_id_asal,
									dtl.sku_stock_id,
									dtl.sku_id,
									dtl.sku_stock_expired_date,
									dtl.qty,
									ISNULL(pd.sku_stock_qty,0) + ISNULL(pd.sku_stock_terima,0) + ISNULL(pd.sku_stock_in,0) - ISNULL(pd.sku_stock_ambil,0) - ISNULL(pd.sku_stock_out,0) AS sku_stock_qty_awal
									FROM tr_mutasi_stok_detail dtl
									LEFT JOIN sku_stock ss
									ON ss.sku_stock_id = dtl.sku_stock_id
									LEFT JOIN pallet_detail pd
									ON pd.pallet_id = dtl.pallet_id_asal
									AND pd.sku_stock_id = dtl.sku_stock_id
									WHERE CONVERT(NVARCHAR(36), dtl.tr_mutasi_stok_id) = '$tr_mutasi_stok_id'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_tr_mutasi_stok_detail2($tr_mutasi_stok_id)
	{
		$query = $this->db->query("SELECT
									tr_mutasi_stok_detail2_id,
									tr_mutasi_stok_detail_id,
									tr_mutasi_stok_id,
									depo_detail_id_tujuan,
									pallet_id_tujuan,
									sku_stock_id_tujuan,
									sku_id,
									sku_stock_expired_date,
									qty
									FROM tr_mutasi_stok_detail2
									WHERE CONVERT(NVARCHAR(36), tr_mutasi_stok_id) = '$tr_mutasi_stok_id'
									order by sku_id, sku_stock_expired_date asc");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_MutasiPalletById($tr_mutasi_stok_id)
	{
		$this->db->select("*")
			->from("tr_mutasi_stok")
			->where("tr_mutasi_stok_id", $tr_mutasi_stok_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}

	public function Get_MutasiPalletDetailById($tr_mutasi_pallet_draft_id)
	{
		$query = $this->db->query("SELECT
									tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_detail_draft_id,
									tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id,
									tr_mutasi_pallet_detail_draft.pallet_id,
									ISNULL(pallet.pallet_kode, '') AS pallet_kode,
									rak_lajur_detail.rak_id,
									ISNULL(rak_lajur.rak_lajur_nama, '') AS rak_nama,
									tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_asal AS rak_lajur_detail_id,
									ISNULL(rak_lajur_detail.rak_lajur_detail_nama, '') AS rak_lajur_detail_nama,
									pallet.pallet_jenis_id,
									ISNULL(pallet_jenis.pallet_jenis_nama, '') AS pallet_jenis_nama
									FROM tr_mutasi_pallet_detail_draft
									LEFT JOIN pallet
									ON tr_mutasi_pallet_detail_draft.pallet_id = pallet.pallet_id
									LEFT JOIN pallet_jenis
									ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_asal
									LEFT JOIN rak_lajur
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									LEFT JOIN rak
									ON rak_lajur.rak_id = rak.rak_id
									WHERE tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'
									ORDER BY ISNULL(pallet.pallet_kode, '') ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_MutasiPalletByKode($kode)
	{
		$this->db->select("*")
			->from("tr_mutasi_pallet_draft")
			->where("tr_mutasi_pallet_draft_kode", $kode);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}

	public function Get_MutasiPalletDetailByKode($kode)
	{
		$query = $this->db->query("SELECT
									tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_detail_draft_id,
									tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id,
									tr_mutasi_pallet_detail_draft.pallet_id,
									ISNULL(pallet.pallet_kode, '') AS pallet_kode,
									rak_lajur_detail.rak_id,
									ISNULL(rak_lajur.rak_lajur_nama, '') AS rak_nama,
									tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_asal AS rak_lajur_detail_id,
									ISNULL(rak_lajur_detail.rak_lajur_detail_nama, '') AS rak_lajur_detail_nama,
									pallet.pallet_jenis_id,
									ISNULL(pallet_jenis.pallet_jenis_nama, '') AS pallet_jenis_nama
									FROM tr_mutasi_pallet_draft
									LEFT JOIN tr_mutasi_pallet_detail_draft
									ON tr_mutasi_pallet_detail_draft.tr_mutasi_pallet_draft_id = tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_id
									LEFT JOIN pallet
									ON tr_mutasi_pallet_detail_draft.pallet_id = pallet.pallet_id
									LEFT JOIN pallet_jenis
									ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = tr_mutasi_pallet_detail_draft.rak_lajur_detail_id_asal
									LEFT JOIN rak_lajur
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									LEFT JOIN rak
									ON rak_lajur.rak_id = rak.rak_id
									WHERE tr_mutasi_pallet_draft.tr_mutasi_pallet_draft_kode = '$kode'
									ORDER BY ISNULL(pallet.pallet_kode, '') ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Check_Channel_Duplicate($channel_nama)
	{
		$this->db->select("channel_id")
			->from("channel")
			->where("channel_nama", $channel_nama);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = 1;
		}

		return $query;
	}

	public function insert_tr_mutasi_stok($tr_mutasi_stok_id, $client_wms_id, $principle_id, $tr_mutasi_stok_kode, $tr_mutasi_stok_tanggal, $tr_mutasi_stok_keterangan, $tr_mutasi_stok_status, $depo_id_asal, $tr_mutasi_stok_tgl_create, $tr_mutasi_stok_who_create, $tr_mutasi_stok_nama_checker, $tr_mutasi_stok_tgl_update, $tr_mutasi_stokt_who_update)
	{
		$this->db->set("tr_mutasi_stok_id", $tr_mutasi_stok_id);
		$this->db->set("principle_id", $principle_id);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("tr_mutasi_stok_kode", $tr_mutasi_stok_kode);
		$this->db->set("tr_mutasi_stok_tanggal", $tr_mutasi_stok_tanggal);
		$this->db->set("tr_mutasi_stok_keterangan", $tr_mutasi_stok_keterangan);
		$this->db->set("tr_mutasi_stok_status", $tr_mutasi_stok_status);
		$this->db->set("depo_id_asal", $depo_id_asal);
		$this->db->set("tr_mutasi_stok_tgl_create", 'GETDATE()', FALSE);
		$this->db->set("tr_mutasi_stok_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("tr_mutasi_stok_nama_checker", $tr_mutasi_stok_nama_checker);
		$this->db->set("tr_mutasi_stok_tgl_update", 'GETDATE()', FALSE);
		$this->db->set("tr_mutasi_stokt_who_update", $this->session->userdata('pengguna_username'));

		$this->db->insert("tr_mutasi_stok");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_tr_mutasi_stok_detail($tr_mutasi_stok_detail_id, $tr_mutasi_stok_id, $depo_detail_id_asal, $pallet_id_asal, $sku_stock_id, $sku_id, $sku_stock_expired_date, $sku_stock_qty)
	{
		$this->db->set("tr_mutasi_stok_detail_id", $tr_mutasi_stok_detail_id);
		$this->db->set("tr_mutasi_stok_id", $tr_mutasi_stok_id);
		$this->db->set("depo_detail_asal", $depo_detail_id_asal);
		$this->db->set("pallet_id_asal", $pallet_id_asal);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
		$this->db->set("qty", $sku_stock_qty);

		$this->db->insert("tr_mutasi_stok_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_tr_mutasi_stok_detail2($tr_mutasi_stok_detail2_id, $tr_mutasi_stok_detail_id, $tr_mutasi_stok_id, $depo_detail_id_tujuan, $pallet_id_tujuan, $sku_stock_id_tujuan, $sku_id, $sku_stock_expired_date, $qty)
	{
		$this->db->set('tr_mutasi_stok_detail2_id', $tr_mutasi_stok_detail2_id);
		$this->db->set('tr_mutasi_stok_detail_id', $tr_mutasi_stok_detail_id);
		$this->db->set('tr_mutasi_stok_id', $tr_mutasi_stok_id);
		$this->db->set('depo_detail_id_tujuan', $depo_detail_id_tujuan);
		$this->db->set('pallet_id_tujuan', $pallet_id_tujuan);
		// $this->db->set('sku_stock_id_tujuan', $sku_stock_id_tujuan);
		$this->db->set('sku_id', $sku_id);
		$this->db->set('sku_stock_expired_date', $sku_stock_expired_date);
		$this->db->set('qty', $qty);

		$this->db->insert("tr_mutasi_stok_detail2");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function update_tr_mutasi_stok_detail2_sku_stock_tujuan($tr_mutasi_stok_detail2_id, $sku_stock_id)
	{

		$this->db->set('sku_stock_id_tujuan', $sku_stock_id);
		$this->db->where('tr_mutasi_stok_detail2_id', $tr_mutasi_stok_detail2_id);

		$this->db->update("tr_mutasi_stok_detail2");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function update_tr_mutasi_stok($tr_mutasi_stok_id, $client_wms_id, $principle_id, $tr_mutasi_stok_kode, $tr_mutasi_stok_tanggal, $tr_mutasi_stok_keterangan, $tr_mutasi_stok_status, $depo_id_asal, $tr_mutasi_stok_tgl_create, $tr_mutasi_stok_who_create, $tr_mutasi_stok_nama_checker, $tr_mutasi_stok_tgl_update, $tr_mutasi_stokt_who_update)
	{
		$this->db->set("principle_id", $principle_id);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("tr_mutasi_stok_kode", $tr_mutasi_stok_kode);
		$this->db->set("tr_mutasi_stok_tanggal", $tr_mutasi_stok_tanggal);
		$this->db->set("tr_mutasi_stok_keterangan", $tr_mutasi_stok_keterangan);
		$this->db->set("tr_mutasi_stok_status", $tr_mutasi_stok_status);
		$this->db->set("depo_id_asal", $depo_id_asal);
		// $this->db->set("tr_mutasi_stok_tgl_create", 'GETDATE()', FALSE);
		// $this->db->set("tr_mutasi_stok_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("tr_mutasi_stok_nama_checker", $tr_mutasi_stok_nama_checker);
		$this->db->set("tr_mutasi_stok_tgl_update", 'GETDATE()', FALSE);
		$this->db->set("tr_mutasi_stokt_who_update", $this->session->userdata('pengguna_username'));

		$this->db->where("tr_mutasi_stok_id", $tr_mutasi_stok_id);
		$this->db->update("tr_mutasi_stok");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function update_last_update_tr_mutasi_stok($tr_mutasi_stok_id, $tr_mutasi_stok_status, $tr_mutasi_stok_tgl_update, $tr_mutasi_stokt_who_update)
	{
		$this->db->set("tr_mutasi_stok_status", $tr_mutasi_stok_status);
		$this->db->set("tr_mutasi_stok_tgl_update", 'GETDATE()', FALSE);
		$this->db->set("tr_mutasi_stokt_who_update", $this->session->userdata('pengguna_username'));

		$this->db->where("tr_mutasi_stok_id", $tr_mutasi_stok_id);
		$this->db->update("tr_mutasi_stok");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function delete_tr_mutasi_stok_detail($tr_mutasi_stok_id)
	{
		$this->db->where("tr_mutasi_stok_id", $tr_mutasi_stok_id);

		$this->db->delete("tr_mutasi_stok_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Success
		}

		return $res;
	}

	public function delete_tr_mutasi_stok_detail2($tr_mutasi_stok_id)
	{
		$this->db->where("tr_mutasi_stok_id", $tr_mutasi_stok_id);

		$this->db->delete("tr_mutasi_stok_detail2");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Success
		}

		return $res;
	}

	public function Get_ParameterApprovalMutasiPallet($tipe_mutasi)
	{
		$query = $this->db->query("select vrbl_param, vrbl_kode from vrbl where vrbl_param in
								(select approval_setting_parameter from approval_setting where menu_web_kode = '120005000' and approval_setting_jenis = '$tipe_mutasi')");
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->vrbl_param;
		}

		return $query;
	}

	public function Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_mutasi_pallet_draft_id, $tr_mutasi_pallet_draft_kode, $is_approvaldana, $total_biaya)
	{
		$query = $this->db->query("exec approval_pengajuan '$depo_id', '$karyawan_id','$approvalParam', '$tr_mutasi_pallet_draft_id','$tr_mutasi_pallet_draft_kode', '$is_approvaldana','$total_biaya'");

		// $res = $query->result_array();

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Success
		}

		return $res;
	}

	public function Get_tipe_mutasi($tipe_mutasi_id)
	{
		$this->db->select("tipe_mutasi_nama")
			->from("tipe_mutasi")
			->where("tipe_mutasi_id", $tipe_mutasi_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->tipe_mutasi_nama;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_ParamTipe($tipe_mutasi)
	{
		$query = $this->db->query("SELECT vrbl_param FROM vrbl WHERE vrbl_keterangan = '$tipe_mutasi'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->vrbl_param;
		}

		return $query;
	}

	public function Get_tipe_mutasi_nama($tipe_mutasi_id)
	{
		$query = $this->db->query("SELECT tipe_mutasi_nama FROM tipe_mutasi WHERE tipe_mutasi_id = '$tipe_mutasi_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->tipe_mutasi_nama;
		}

		return $query;
	}

	public function Get_CheckerPrinciple($principle, $client_wms_id)
	{
		$query = $this->db->query("select
									karyawan.*
									from karyawan
									left join karyawan_principle
									on karyawan.karyawan_id = karyawan_principle.karyawan_id
									where karyawan_principle.principle_id = '$principle'
									AND karyawan_principle.client_wms_id = '$client_wms_id'
									AND karyawan.karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
									AND karyawan.depo_id = '" . $this->session->userdata('depo_id') . "'
									order by karyawan.karyawan_nama ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getClientWms()
	{
		// return $this->db->select("client_wms_id, client_wms_nama")
		// 	->from("client_wms")
		// 	->where("client_wms_is_aktif", 1)
		// 	->get()->result();

		return $this->db->query("SELECT b.*
		FROM depo_client_wms a
		LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
		WHERE a.depo_id = '" . $this->session->userdata('depo_id') . "'
		AND b.client_wms_is_aktif = 1
		AND b.client_wms_is_deleted = 0")->result();
	}

	public function getDataPrincipleByClientWmsId($id)
	{
		$this->db->select("b.principle_id as id, b.principle_kode as kode, b.principle_nama as nama")
			->from("client_wms_principle a")
			->join("principle b", "a.principle_id = b.principle_id");
		if ($id != "") {
			# code...
			$this->db->where("a.client_wms_id", $id);
		}
		return $this->db->get()->result();
	}
	public function GetTglUpdate($tr_mutasi_pallet_draft_id)
	{
		return $this->db->query("select tr_mutasi_pallet_draft_tgl_update as tgl_update from tr_mutasi_pallet_draft where tr_mutasi_pallet_draft_id = '$tr_mutasi_pallet_draft_id'")->row();
	}

	public function Get_list_gudang_tujuan($search, $depo_detail_id_tujuan)
	{
		$query = $this->db->query("SELECT TOP 25
										depo_detail_id as id,
										depo_detail_nama as text
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									AND depo_detail_nama LIKE '%" . $search . "%'
									ORDER BY CASE
									WHEN CONVERT(nvarchar(36), depo_detail_id) = '$depo_detail_id_tujuan' THEN 1
									ELSE 0
									END DESC,
									depo_detail_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function Get_list_pallet_tujuan($search, $depo_detail_id_tujuan, $pallet_id_tujuan)
	{
		$query = $this->db->query("SELECT
									a.pallet_id AS id,
									a.pallet_kode AS text
									FROM pallet a
									LEFT JOIN rak_lajur_detail b
									ON b.rak_lajur_detail_id = a.rak_lajur_detail_id
									LEFT JOIN rak_lajur c
									ON b.rak_lajur_id = c.rak_lajur_id
									LEFT JOIN rak d
									ON c.rak_id = d.rak_id
									WHERE CONVERT(nvarchar(36), d.depo_detail_id) = '$depo_detail_id_tujuan'
									AND a.pallet_kode LIKE '%$search%'
									ORDER BY CASE
									WHEN CONVERT(nvarchar(36), a.pallet_id) = '$pallet_id_tujuan' THEN 1
									ELSE 0
									END DESC,a.pallet_kode ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function Exec_cek_exist_sku_stock($depo_detail_id_tujuan, $sku_id, $sku_stock_expired_date, $qty)
	{
		$query = $this->db->query("exec cek_exist_sku_stock '" . $this->session->userdata('depo_id') . "','$depo_detail_id_tujuan','$sku_id','$sku_stock_expired_date'," . $qty . "");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Exec_cek_exist_sku_stock_in_pallet($pallet_id_tujuan, $sku_id, $sku_stock_id, $sku_stock_expired_date, $qty)
	{
		$query = $this->db->query("exec cek_exist_sku_stock_in_pallet '$pallet_id_tujuan','$sku_id','$sku_stock_id','$sku_stock_expired_date'," . $qty . "");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Exec_insertupdate_sku_stock($tipe, $sku_stock_id, $client_wms_id, $qty)
	{
		$query = $this->db->query("exec insertupdate_sku_stock '$tipe','$sku_stock_id','$client_wms_id'," . $qty . "");


		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Exec_insertupdate_sku_stock_pallet($tipe, $pallet_id, $sku_stock_id, $qty)
	{
		$query = $this->db->query("exec insertupdate_sku_stock_pallet '$tipe','$pallet_id','$sku_stock_id'," . $qty . "");


		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_sku_stock_id($depo_detail_id_tujuan, $sku_id, $sku_stock_expired_date)
	{
		$query = $this->db->query("select * from sku_stock where sku_id = '$sku_id' and depo_detail_id = '$depo_detail_id_tujuan' and sku_stock_expired_date = '$sku_stock_expired_date'");


		if ($query->num_rows() == 0) {
			$query = "";
		} else {
			$query = $query->row(0)->sku_stock_id;
		}

		return $query;
	}

	public function Exec_proses_posting_stock_card($identity, $tr_mutasi_stok_id, $who)
	{
		$query = $this->db->query("exec proses_posting_stock_card '$identity', '$tr_mutasi_stok_id','$who'");

		// $res = $query->result_array();

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Success
		}

		return $res;
	}
}
