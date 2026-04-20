<?php

class M_LaporanPenjualan extends CI_Model
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
		// $query = $this->db->query("SELECT
		// 								*
		// 							FROM client_wms
		// 							-- WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
		// 							ORDER BY client_wms_nama ASC");

		$query = $this->db->query("SELECT b.*
		FROM depo_client_wms a
		LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
		WHERE a.depo_id = '" . $this->session->userdata('depo_id') . "'
		AND b.client_wms_is_aktif = 1
		AND b.client_wms_is_deleted = 0");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
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

	public function count_all_data($tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $driver)
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
			$skukode = "and dod1.sku_kode like '%$sku_kode%' ";
		}
		if ($sku_nama == '') {
			$skunama = "";
		} else {
			$skunama = "and dod1.sku_nama_produk like '%$sku_nama%' ";
		}

		if ($driver != "") {
			$driver = " AND k.karyawan_id = '" . $driver . "' ";
			// $driver = " AND k.karyawan_nama LIKE '%" . $driver . "%' ";
		} else {
			$driver = "";
		}

		$depo_id = $this->session->userdata("depo_id");

		$query =  $this->db->query("SELECT COUNT(do.delivery_order_id) as hasil
									from delivery_order do
										LEFT JOIN delivery_order_batch dob on do.delivery_order_batch_id = dob.delivery_order_batch_id
										LEFT JOIN delivery_order_detail dod1 on do.delivery_order_id = dod1.delivery_order_id
										--LEFT JOIN delivery_order_detail2 dod2 on dod1.delivery_order_detail_id = dod2.delivery_order_detail_id
										LEFT JOIN sales_order so on so.sales_order_id = do.sales_order_id
										LEFT JOIN karyawan k on k.karyawan_id = dob.karyawan_id
										LEFT JOIN reason r on r.reason_id = do.delivery_order_reject_reason
										LEFT JOIN kendaraan kn on kn.kendaraan_id = dob.kendaraan_id
										left join depo on depo.depo_id = do.depo_id
										where do.depo_id = '$depo_id'
										and do.delivery_order_status in ('not delivered', 'delivered')
										AND format(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
										$client_wms
										--	$depo_detail
										$principle
										$skukode
										$driver
										$skunama")->row();
		return $query->hasil;
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

		$query = $this->db->query("SELECT 
									dob.delivery_order_batch_id,do.delivery_order_id,dod1.delivery_order_detail_id,depo.depo_nama,
									do.client_wms_id,
									FORMAT(do.delivery_order_tgl_buat_do,'yyyy-MM-dd') as tgl_do,
									FORMAT(do.delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') as tgl_terkirim,
									do.delivery_order_kode,
									isnull(so.sales_order_no_po,'') as sales_order_no,
									do.delivery_order_kirim_nama as nama_outlet,
									do.delivery_order_kirim_alamat as alamat_outlet,
									do.delivery_order_kirim_area as area,
									dod1.sku_nama_produk,
									dod1.sku_kode,
									dod1.sku_qty,
									dod1.sku_qty_kirim,
									dod1.sku_harga_nett as harga,
									--dod2.sku_expdate as ed,
									dob.delivery_order_batch_kode,
									k.karyawan_nama,
									kn.kendaraan_nopol,
									FORMAT(dob.delivery_order_batch_tanggal_kirim,'yyyy-MM-dd') as delivery_order_batch_tanggal_kirim,
									do.delivery_order_status,
									isnull(r.reason_keterangan,'') as reason,
									do.principle_id
								from delivery_order do
									LEFT JOIN delivery_order_batch dob on do.delivery_order_batch_id = dob.delivery_order_batch_id
									LEFT JOIN delivery_order_detail dod1 on do.delivery_order_id = dod1.delivery_order_id
									--LEFT JOIN delivery_order_detail2 dod2 on dod1.delivery_order_detail_id = dod2.delivery_order_detail_id

									LEFT JOIN sales_order so on so.sales_order_id = do.sales_order_id
									LEFT JOIN karyawan k on k.karyawan_id = dob.karyawan_id
									LEFT JOIN reason r on r.reason_id = do.delivery_order_reject_reason
									LEFT JOIN kendaraan kn on kn.kendaraan_id = dob.kendaraan_id
									left join depo on depo.depo_id = do.depo_id
										where do.depo_id = '$depo_id'
										and do.delivery_order_status in ('not delivered', 'delivered')
										AND format(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
										$client_wms
									--=	$depo_detail
										$principle
										$skukode
										$skunama

								group by dob.delivery_order_batch_id,do.delivery_order_id,dod1.delivery_order_detail_id,
								do.client_wms_id,depo.depo_nama,
									FORMAT(do.delivery_order_tgl_buat_do,'yyyy-MM-dd'),
									do.delivery_order_tgl_aktual_kirim	,
									do.delivery_order_kode,
									so.sales_order_no_po,
									do.delivery_order_kirim_nama ,
									do.delivery_order_kirim_alamat ,
									do.delivery_order_kirim_area ,
									dod1.sku_nama_produk,
									dod1.sku_kode,
									dod1.sku_qty,
									dod1.sku_qty_kirim,
									dod1.sku_harga_nett,
									--dod2.sku_expdate ,
									dob.delivery_order_batch_kode,
									k.karyawan_nama,
									kn.kendaraan_nopol,
									dob.delivery_order_batch_tanggal_kirim,
									do.delivery_order_status,
									r.reason_keterangan,
									do.principle_id
		");

		/* Not fix
		$query = $this->db->query("SELECT
									depo.depo_nama,
									FORMAT(do.delivery_order_tgl_buat_do,'yyyy-MM-dd') as tgl_do,
									do.delivery_order_tgl_aktual_kirim	,
									do.delivery_order_kode,
									isnull(so.sales_order_no_po,'') as sales_order_no,
									do.delivery_order_kirim_nama as nama_outlet,
									do.delivery_order_kirim_alamat as alamat_outlet,
									do.delivery_order_kirim_area as area,
									dod1.sku_nama_produk,
									dod1.sku_kode,
									dod1.sku_qty,
									dod1.sku_qty_kirim,
									dod1.sku_harga_nett as harga,
									--dod2.sku_expdate as ed,
									dob.delivery_order_batch_kode,
									k.karyawan_nama,
									kn.kendaraan_nopol,
									dob.delivery_order_batch_tanggal_kirim,
									do.delivery_order_status,
									isnull(r.reason_keterangan,'')	
								from delivery_order do
									LEFT JOIN delivery_order_detail dod1 on do.delivery_order_id = dod1.delivery_order_id
									LEFT JOIN delivery_order_detail2 dod2 on dod1.delivery_order_detail_id = dod2.delivery_order_detail_id
									LEFT JOIN delivery_order_batch dob on do.delivery_order_batch_id = dob.delivery_order_batch_id
									LEFT JOIN depo on depo.depo_id = do.depo_id
									LEFT JOIN sales_order so on so.sales_id = do.sales_order_id
									LEFT JOIN karyawan k on k.karyawan_id = dob.karyawan_id
									LEFT JOIN reason r on r.reason_id = do.delivery_order_reject_reason
									LEFT JOIN kendaraan kn on kn.kendaraan_id = dob.kendaraan_id
								where do.depo_id ='" . $this->session->userdata('depo_id') . "'
									AND format(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'

								GROUP BY depo.depo_nama,
									FORMAT(do.delivery_order_tgl_buat_do,'yyyy-MM-dd') ,
									do.delivery_order_tgl_aktual_kirim	,
									do.delivery_order_kode,
									so.sales_order_no_po,
									do.delivery_order_kirim_nama ,
									do.delivery_order_kirim_alamat ,
									do.delivery_order_kirim_area ,
									dod1.sku_nama_produk,
									dod1.sku_kode,
									dod1.sku_qty,
									dod1.sku_qty_kirim,
									dod1.sku_harga_nett,
									--dod2.sku_expdate ,
									dob.delivery_order_batch_kode,
									k.karyawan_nama,
									kn.kendaraan_nopol,
									dob.delivery_order_batch_tanggal_kirim,
									do.delivery_order_status,
									r.reason_keterangan

		");
		*/

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}

	public function GetDetailNew($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{

		$depo_id = $this->session->userdata("depo_id");

		$this->db->select("depo.depo_nama as depo_nama, FORMAT(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') as tgl_do, FORMAT(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') as tgl_terkirim, do.delivery_order_kode as delivery_order_kode, ISNULL(so.sales_order_no_po, '') as sales_order_no, do.delivery_order_kirim_nama as nama_outlet, do.delivery_order_kirim_alamat as alamat_outlet, do.delivery_order_kirim_area as area, dod1.sku_nama_produk as sku_nama_produk, dod1.sku_kode as sku_kode, dod1.sku_qty as sku_qty, dod1.sku_qty_kirim as sku_qty_kirim, dod1.sku_harga_nett as harga, dob.delivery_order_batch_kode as delivery_order_batch_kode, k.karyawan_nama as karyawan_nama, kn.kendaraan_nopol as kendaraan_nopol, FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') as delivery_order_batch_tanggal_kirim, do.delivery_order_status as delivery_order_status, ISNULL(r.reason_keterangan, '') as reason");
		$this->db->from("delivery_order as do");
		$this->db->join("delivery_order_batch as dob", "do.delivery_order_batch_id = dob.delivery_order_batch_id", "left");
		$this->db->join("delivery_order_detail as dod1", "do.delivery_order_id = dod1.delivery_order_id", "left");
		$this->db->join("sales_order as so", "so.sales_order_id = do.sales_order_id", "left");
		$this->db->join("karyawan as k", "k.karyawan_id = dob.karyawan_id", "left");
		$this->db->join("reason as r", "r.reason_id = do.delivery_order_reject_reason", "left");
		$this->db->join("kendaraan as kn", "kn.kendaraan_id = dob.kendaraan_id", "left");
		$this->db->join("depo", "depo.depo_id = do.depo_id", "left");
		$this->db->where("do.depo_id", $depo_id);
		$this->db->where_in("do.delivery_order_status", array("not delivered", "delivered"));
		$this->db->where("FORMAT(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'");
		if ($client_wms_id == '') {
			// $this->db->where('');
		} else {
			$this->db->where('do.client_wms_id', $client_wms_id);
		}

		if ($depo_detail_id == '') {
			// $this->db->where('');
		} else {
			$this->db->where('dob.depo_detail_id', $depo_detail_id);
		}

		if ($principle_id == '') {
			// $this->db->where('');
		} else {
			$this->db->where('do.principle_id', $principle_id);
		}

		if ($sku_kode == '') {
			// $this->db->where('');
		} else {
			$this->db->like('dod1.sku_kode', $sku_kode);
		}

		if ($sku_nama == '') {
			// $this->db->where('');
		} else {
			$this->db->like('dod1.sku_nama_produk', $sku_nama);
		}
		$this->db->group_by(" depo.depo_nama, FORMAT(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd'), do.delivery_order_tgl_aktual_kirim, do.delivery_order_kode, so.sales_order_no_po, do.delivery_order_kirim_nama, do.delivery_order_kirim_alamat, do.delivery_order_kirim_area, dod1.sku_nama_produk, dod1.sku_kode, dod1.sku_qty, dod1.sku_qty_kirim, dod1.sku_harga_nett, dob.delivery_order_batch_kode, k.karyawan_nama, kn.kendaraan_nopol, dob.delivery_order_batch_tanggal_kirim, do.delivery_order_status, r.reason_keterangan");

		$sortable_columns = array(
			// 'penerimaan_pembelian_id',
			'depo_nama',
			// 'tgl_do',
			// 'tgl_terkirim',
			'delivery_order_kode',
			// 'sales_order_no',
			// 'nama_outlet',
			'sku_kode',
			'sku_nama_produk',
			'sku_qty',
			// 'sku_qty_kirim',
			// 'harga',
			'delivery_order_batch_kode',
			'karyawan_nama',
			'kendaraan_nopol',
			// 'delivery_order_batch_tanggal_kirim',
			'delivery_order_status',
			// 'reason'
		);

		// Kolom yang dapat dicari
		$searchable_columns = array(
			'depo_nama',
			// 'tgl_do',
			// 'tgl_terkirim',
			'delivery_order_kode',
			// 'sales_order_no',
			// 'nama_outlet',
			'sku_kode',
			'sku_nama_produk',
			'sku_qty',
			// 'sku_qty_kirim',
			// 'harga',
			'delivery_order_batch_kode',
			'karyawan_nama',
			'kendaraan_nopol',
			// 'delivery_order_batch_tanggal_kirim',
			'delivery_order_status',
			// 'reason'
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

	public function GetDetailNewProcedure($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2, $driver)
	{
		// $start = $start == '' ? 0 : $start;
		// $length = $length == '' ? 0 : $length;
		// // $search_value = $search_value == '' ? NULL : $search_value;
		// $order_column = $order_column == '' ? 0 : $order_column;
		// $order_dir = $order_dir == '' ? NULL : $order_dir;
		// $tgl1 = $tgl1 == '' ? NULL : $tgl1;
		// $tgl2 = $tgl2 == '' ? NULL : $tgl2;
		// $client_wms_id = $client_wms_id == '' ? NULL : $client_wms_id;
		// $depo_id = $depo_id == '' ? NULL : $depo_id;
		// $depo_detail_id = $depo_detail_id == '' ? NULL : $depo_detail_id;
		// $principle_id = $principle_id == '' ? NULL : $principle_id;
		// $sku_kode = $sku_kode == '' ? NULL : $sku_kode;
		// $sku_nama = $sku_nama == '' ? NULL : $sku_nama;
		// $tgl_1 = $tgl_1 == '' ? NULL : $tgl_1;
		// $tgl_2 = $tgl_2 == '' ? NULL : $tgl_2;

		$order_str = "";
		$offset_str = "";

		if ($depo_detail_id != "") {
			$depo_detail_id = " AND dob.depo_detail_id = '" . $depo_detail_id . "' ";
		} else {
			$depo_detail_id = "";
		}

		if ($principle_id != "") {
			$principle_id = " AND sku.principle_id = '" . $principle_id . "' ";
		} else {
			$principle_id = "";
		}

		if ($sku_kode != "") {
			$sku_kode = " AND dod1.sku_kode LIKE '%" . $sku_kode . "%' ";
		} else {
			$sku_kode = "";
		}

		if ($sku_nama != "") {
			$sku_nama = " AND dod1.sku_nama_produk LIKE '%" . $sku_nama . "%' ";
		} else {
			$sku_nama = "";
		}

		if ($client_wms_id != "") {
			$client_wms_id = " AND do.client_wms_id = '" . $client_wms_id . "' ";
		} else {
			$client_wms_id = "";
		}

		if ($driver != "") {
			$driver = " AND k.karyawan_id = '" . $driver . "' ";
			// $driver = " AND k.karyawan_nama LIKE '%" . $driver . "%' ";
		} else {
			$driver = "";
		}

		if ($order_column == 0) {
			$order_str = " depo_nama $order_dir";
		} else if ($order_column == 1) {
			$order_str = " tgl_do $order_dir";
		} else if ($order_column == 2) {
			$order_str = " tgl_terkirim $order_dir";
		} else if ($order_column == 3) {
			$order_str = " delivery_order_kode $order_dir";
		} else if ($order_column == 4) {
			$order_str = " sales_order_no $order_dir";
		} else if ($order_column == 5) {
			$order_str = " nama_outlet $order_dir";
		} else if ($order_column == 6) {
			$order_str = " sku_kode $order_dir";
		} else if ($order_column == 7) {
			$order_str = " sku_nama_produk $order_dir";
		} else if ($order_column == 8) {
			$order_str = " sku_qty $order_dir";
		} else if ($order_column == 9) {
			$order_str = " sku_qty_kirim $order_dir";
		} else if ($order_column == 10) {
			$order_str = " harga $order_dir";
		} else if ($order_column == 11) {
			$order_str = " delivery_order_batch_kode $order_dir";
		} else if ($order_column == 12) {
			$order_str = " karyawan_nama $order_dir";
		} else if ($order_column == 13) {
			$order_str = " kendaraan_nopol $order_dir";
		} else if ($order_column == 14) {
			$order_str = " delivery_order_batch_tanggal_kirim $order_dir";
		} else if ($order_column == 15) {
			$order_str = " delivery_order_status $order_dir";
		} else if ($order_column == 16) {
			$order_str = " reason $order_dir";
		} else {
			$order_str = " RowNum ASC";
		}

		if ($length == -1) {
			$offset_str = "";
		} else {
			$offset_str = " OFFSET $start ROWS FETCH NEXT $length ROWS ONLY";
		}

		// $query = $this->db->query("exec report_transaksi_penjualan $start,$length,$order_column,'$order_dir','$tgl_1','$tgl_2','$client_wms_id','$depo_id','$depo_detail_id','$principle_id','$sku_kode','$sku_nama','$tgl1','$tgl2'");

		$query = $this->db->query("SELECT *
									FROM (SELECT
									depo.depo_nama AS depo_nama,
									FORMAT(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') AS tgl_do,
									FORMAT(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') AS tgl_terkirim,
									do.delivery_order_kode AS delivery_order_kode,
									ISNULL(so.sales_order_no_po, '') AS sales_order_no,
									do.delivery_order_kirim_nama AS nama_outlet,
									do.delivery_order_kirim_alamat AS alamat_outlet,
									do.delivery_order_kirim_area AS area,
									dod1.sku_nama_produk AS sku_nama_produk,
									dod1.sku_kode AS sku_kode,
									dod1.sku_qty AS sku_qty,
									dod1.sku_qty_kirim AS sku_qty_kirim,
									dod1.sku_harga_nett AS harga,
									dob.delivery_order_batch_kode AS delivery_order_batch_kode,
									k.karyawan_nama AS karyawan_nama,
									kn.kendaraan_nopol AS kendaraan_nopol,
									FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') AS delivery_order_batch_tanggal_kirim,
									do.delivery_order_status AS delivery_order_status,
									ISNULL(r.reason_keterangan, '') AS reason,
									ROW_NUMBER() OVER (ORDER BY depo_nama ASC) AS RowNum
								FROM delivery_order AS do
								LEFT JOIN delivery_order_batch AS dob ON do.delivery_order_batch_id = dob.delivery_order_batch_id
								LEFT JOIN delivery_order_detail AS dod1 ON do.delivery_order_id = dod1.delivery_order_id
								LEFT JOIN sku ON sku.sku_id = dod1.sku_id
								LEFT JOIN sales_order AS so ON so.sales_order_id = do.sales_order_id
								LEFT JOIN karyawan AS k ON k.karyawan_id = dob.karyawan_id
								LEFT JOIN reason AS r ON r.reason_id = do.delivery_order_reject_reason
								LEFT JOIN kendaraan AS kn ON kn.kendaraan_id = dob.kendaraan_id
								LEFT JOIN depo ON depo.depo_id = do.depo_id
								WHERE do.depo_id = '$depo_id'
								AND do.delivery_order_status IN ('not delivered', 'delivered', 'partially delivered')
								AND FORMAT(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
								" . $depo_detail_id . " 
								" . $principle_id . " 
								" . $sku_kode . " 
								" . $sku_nama . " 
								" . $driver . " 
								" . $client_wms_id . ") CTE
								ORDER BY " . $order_str . "
								" . $offset_str . " ");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}

	public function GetDetailNewProcedure2($tgl1, $tgl2, $client_wms_id, $depo_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2, $driver)
	{

		if ($principle_id != "") {
			$principle_id = " AND sku.principle_id = '" . $principle_id . "' ";
		} else {
			$principle_id = "";
		}

		if ($sku_kode != "") {
			$sku_kode = " AND dod1.sku_kode LIKE '%" . $sku_kode . "%' ";
		} else {
			$sku_kode = "";
		}

		if ($sku_nama != "") {
			$sku_nama = " AND dod1.sku_nama_produk LIKE '%" . $sku_nama . "%' ";
		} else {
			$sku_nama = "";
		}

		if ($client_wms_id != "") {
			$client_wms_id = " AND do.client_wms_id = '" . $client_wms_id . "' ";
		} else {
			$client_wms_id = "";
		}

		if ($driver != "") {
			$driver = " AND k.karyawan_id = '" . $driver . "' ";
			// $driver = " AND k.karyawan_nama LIKE '%" . $driver . "%' ";
		} else {
			$driver = "";
		}

		$query = $this->db->query("SELECT
									depo.depo_nama AS depo_nama,
									FORMAT(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') AS tgl_do,
									FORMAT(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') AS tgl_terkirim,
									do.delivery_order_kode AS delivery_order_kode,
									ISNULL(so.sales_order_no_po, '') AS sales_order_no,
									do.delivery_order_kirim_nama AS nama_outlet,
									do.delivery_order_kirim_alamat AS alamat_outlet,
									do.delivery_order_kirim_area AS area,
									dod1.sku_nama_produk AS sku_nama_produk,
									dod1.sku_kode AS sku_kode,
									dod1.sku_qty AS sku_qty,
									dod1.sku_qty_kirim AS sku_qty_kirim,
									dod1.sku_harga_nett AS harga,
									dob.delivery_order_batch_kode AS delivery_order_batch_kode,
									k.karyawan_nama AS karyawan_nama,
									kn.kendaraan_nopol AS kendaraan_nopol,
									FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') AS delivery_order_batch_tanggal_kirim,
									do.delivery_order_status AS delivery_order_status,
									ISNULL(r.reason_keterangan, '') AS reason,
									ROW_NUMBER() OVER (ORDER BY depo_nama ASC) AS RowNum
								FROM delivery_order AS do
								LEFT JOIN delivery_order_batch AS dob ON do.delivery_order_batch_id = dob.delivery_order_batch_id
								LEFT JOIN delivery_order_detail AS dod1 ON do.delivery_order_id = dod1.delivery_order_id
								LEFT JOIN sku ON sku.sku_id = dod1.sku_id
								LEFT JOIN sales_order AS so ON so.sales_order_id = do.sales_order_id
								LEFT JOIN karyawan AS k ON k.karyawan_id = dob.karyawan_id
								LEFT JOIN reason AS r ON r.reason_id = do.delivery_order_reject_reason
								LEFT JOIN kendaraan AS kn ON kn.kendaraan_id = dob.kendaraan_id
								LEFT JOIN depo ON depo.depo_id = do.depo_id
								WHERE do.depo_id = '$depo_id'
								AND do.delivery_order_status IN ('not delivered', 'delivered', 'partially delivered')
								AND FORMAT(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
								" . $principle_id . " 
								" . $sku_kode . " 
								" . $sku_nama . " 
								" . $driver . " 
								" . $client_wms_id . "");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}
}
