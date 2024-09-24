<?php

class M_AdvanceShipmentNotice extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function GetDataPrinciple()
	{
		$query = $this->db->query("SELECT principle_id, principle_kode FROM principle ORDER BY principle_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPenerimaanSuratJalanTemp($principle_id)
	{
		$query = $this->db->query("SELECT DISTINCT
		a.principle_id,
		b.principle_kode,
		a.shipment_number,
		c.depo_nama,
		ISNULL(a.penerimaan_surat_jalan_keterangan, '') AS keterangan,
		COUNT(DISTINCT penerimaan_surat_jalan_no_sj) AS do,
		SUM(sku_jumlah_barang) as qty,
		MAX(format(a.penerimaan_surat_jalan_tgl_create, 'yyyy-MM-dd HH:mm:ss')) as penerimaan_surat_jalan_tgl_create,
		a.penerimaan_surat_jalan_status
	  FROM penerimaan_surat_jalan_temp a
	  LEFT JOIN principle b
		ON b.principle_id = a.principle_id
	  LEFT JOIN depo c
		ON c.depo_id = a.depo_id
	  WHERE a.principle_id = '$principle_id'
	  AND a.depo_id = '" . $this->session->userdata('depo_id') . "'
	  AND (penerimaan_surat_jalan_status <> 'Generate Success'
	  OR penerimaan_surat_jalan_status IS NULL)
	  GROUP BY a.principle_id,
	  		   b.principle_kode,
			   a.shipment_number,
			   a.penerimaan_surat_jalan_keterangan,
			   c.depo_nama,
			   a.penerimaan_surat_jalan_status
	  ORDER BY b.principle_kode");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetListDOByShipment($shipment_number, $principle_id)
	{
		$query = $this->db->query("SELECT 
		penerimaan_surat_jalan_no_sj,
		shipment_number,
		sku_kode,
		sku_nama_produk,
		sku_jumlah_barang
		FROM penerimaan_surat_jalan_temp
		WHERE principle_id = '$principle_id' 
		AND shipment_number = '$shipment_number'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDataFilter($principle_id)
	{
		$tgl1 = '';
		$tgl2 = '';

		if ($principle_id == '') {
			$principle_id = '';
		} else {
			$principle_id = "AND a.principle_id = '$principle_id'";
		}

		$query = $this->db->query("SELECT DISTINCT
		a.principle_id,
		b.principle_kode,
		a.shipment_number,
		c.depo_nama,
		ISNULL(a.penerimaan_surat_jalan_keterangan, '') AS keterangan,
		COUNT(DISTINCT a.penerimaan_surat_jalan_no_sj) AS do,
		SUM(a.sku_jumlah_barang) as qty,
		MAX(format(a.penerimaan_surat_jalan_tgl_create, 'yyyy-MM-dd HH:mm:ss')) as penerimaan_surat_jalan_tgl_create,
		a.penerimaan_surat_jalan_status,
		a.penerimaan_surat_jalan_tgl_update as tglUpdate
	  FROM penerimaan_surat_jalan_temp a
	  LEFT JOIN principle b
		ON b.principle_id = a.principle_id
		LEFT JOIN depo c
		ON c.depo_id = a.depo_id
	--   WHERE format(a.penerimaan_surat_jalan_tgl_create, 'yyyy-MM-dd') >= '$tgl1'
	--   AND format(a.penerimaan_surat_jalan_tgl_create, 'yyyy-MM-dd') <= '$tgl2'
	  WHERE
	  a.depo_id = '" . $this->session->userdata('depo_id') . "'
	  AND (penerimaan_surat_jalan_status <> 'Generate Success'
	  OR penerimaan_surat_jalan_status IS NULL)
	  $principle_id
	  GROUP BY a.principle_id,
	  		   b.principle_kode,
			   a.shipment_number,
			   a.penerimaan_surat_jalan_keterangan,
			   c.depo_nama,
			   a.penerimaan_surat_jalan_status,
			   a.penerimaan_surat_jalan_tgl_update
	  ORDER BY b.principle_kode");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPSJByTempID($shipment_number, $principle_id)
	{
		$query = $this->db->query("SELECT depo_id, principle_id, client_wms_id, penerimaan_tipe_id, penerimaan_surat_jalan_kode, penerimaan_surat_jalan_no_sj, penerimaan_surat_jalan_tgl, sku_kode, sku_nama_produk, sku_jumlah_barang, shipment_number, tgl_file, jam_file, penerimaan_surat_jalan_tgl_update
		FROM penerimaan_surat_jalan_temp
		WHERE shipment_number = '$shipment_number' AND principle_id = '$principle_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPSJTempRow($shipment_number, $principle_id)
	{
		$query = $this->db->query("SELECT TOP 1
		shipment_number,
		penerimaan_surat_jalan_tgl,
		depo_id,
		principle_id,
		client_wms_id,
		penerimaan_tipe_id
	  FROM penerimaan_surat_jalan_temp
	  WHERE shipment_number = '$shipment_number'
	  AND principle_id = '$principle_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function GetPSJ($penerimaan_surat_jalan_no_sj, $principle_id)
	{
		$query = $this->db->query("SELECT TOP 1 penerimaan_surat_jalan_id,  FORMAT(penerimaan_surat_jalan_tgl_create, 'yyyy-MM-dd HH:mm:ss') as penerimaan_surat_jalan_tgl_create, penerimaan_surat_jalan_no_sj, principle_id, tgl_file, jam_file
		FROM penerimaan_surat_jalan
		WHERE penerimaan_surat_jalan_no_sj = '$penerimaan_surat_jalan_no_sj' AND principle_id = '$principle_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function GetClientPrinciple()
	{
		$query = $this->db->query("SELECT
			client_wms_id,
			principle_id
			FROM client_wms_principle
			WHERE client_wms_principle_id = '95AB0D18-298C-41F1-A64F-8FC254474564'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function GetSKUIDSuntory($principle_id, $sku_kode, $sku_jumlah_barang)
	{
		$query = $this->db->query("SELECT TOP 1
			sku_id, (sku_konversi_faktor*$sku_jumlah_barang) as sku_konversi_faktor
			FROM sku
			WHERE principle_id = '$principle_id' 
			AND sku_konversi_group = '$sku_kode'
			AND sku_is_aktif = '1'
			ORDER BY sku_konversi_level DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
			var_dump($this->db->last_query());
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function GetSKUEXPDate($penerimaan_surat_jalan_no_sj, $sku_kode)
	{
		$query = $this->db->query("SELECT sku_exp_date, batch 
		FROM penerimaan_surat_jalan_temp2 
		WHERE penerimaan_surat_jalan_no_sj = '$penerimaan_surat_jalan_no_sj' 
		AND sku_kode = '$sku_kode'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}
		return $query;
	}

	public function GetPenerimaanTipe()
	{
		$query = $this->db->query("SELECT
			penerimaan_tipe_id
			FROM penerimaan_tipe
			WHERE penerimaan_tipe_nama = 'beli'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function GetDepo($depo_kode)
	{
		$query = $this->db->query("SELECT
			depo_id
			FROM depo_eksternal
			WHERE depo_eksternal_kode = '$depo_kode'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function CheckTransNumber()
	{
		$query = $this->db->query("SELECT
			TOP 1 transaction_number as trans_num
			FROM penerimaan_surat_jalan_temp
			ORDER BY transaction_index DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function GetPSJTemp2($shipment_number)
	{
		$query = $this->db->query("SELECT
		sku_kode,
		sku_nama_produk,
		sku_jumlah_barang,
		sku_exp_date,
		batch,
		penerimaan_surat_jalan_no_sj
	  FROM penerimaan_surat_jalan_temp2
	  WHERE shipment_number = '$shipment_number'
	  ORDER BY penerimaan_surat_jalan_no_sj");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPSJTemp2SUM($shipment_number)
	{
		$query = $this->db->query("SELECT
		sku_kode,
		SUM(sku_jumlah_barang) AS sku_jumlah_barang,
		sku_exp_date,
		batch
	  FROM penerimaan_surat_jalan_temp2
	  WHERE shipment_number = '$shipment_number'
	  GROUP BY sku_exp_date,
			   batch,
			   sku_kode
	  ORDER BY sku_kode");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function insert_penerimaan_surat_jalan_temp(
		$penerimaan_surat_jalan_temp_id,
		$depo_id,
		$principle_id,
		$client_wms_id,
		$penerimaan_tipe_id,
		$trans_num,
		$date_now,
		$generate_kode,
		$delivery_order_number,
		$delivery_order_line_number,
		$delivery_date,
		$armada_number,
		$expedition_name,
		$po_number,
		$po_line,
		$load_id,
		$sales_order_number,
		$sales_order_line_number,
		$vendor_id,
		$warehouse_id_from,
		$organization_code_from,
		$warehouse_id,
		$customer_name,
		$customer_ship_to,
		$item_id,
		$item_description,
		$transaction_quantity,
		$uom_code,
		$unit_price,
		$net_price,
		$extended_amount,
		$pricing_date,
		$creation_date,
		$creation_time,
		$created_by_name,
		$shipment_number,
		$get_tgl,
		$get_jam
	) {
		$this->db->set("penerimaan_surat_jalan_temp_id", $penerimaan_surat_jalan_temp_id);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("principle_id", $principle_id);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		$this->db->set("transaction_number", $trans_num);
		$this->db->set("penerimaan_surat_jalan_tgl_create", $date_now);
		// $this->db->set("penerimaan_surat_jalan_kode", $generate_kode);
		$this->db->set("penerimaan_surat_jalan_no_sj", $delivery_order_number);
		$this->db->set("delivery_order_line_number", $delivery_order_line_number);
		$this->db->set("penerimaan_surat_jalan_tgl", $delivery_date);
		$this->db->set("armada_number", $armada_number);
		$this->db->set("expedition_name", $expedition_name);
		$this->db->set("po_number", $po_number);
		$this->db->set("po_line", $po_line);
		$this->db->set("load_id", $load_id);
		$this->db->set("sales_order_number", $sales_order_number);
		$this->db->set("sales_order_line_number", $sales_order_line_number);
		$this->db->set("vendor_id", $vendor_id);
		$this->db->set("warehouse_id_from", $warehouse_id_from);
		$this->db->set("organization_code_from", $organization_code_from);
		$this->db->set("warehouse_id", $warehouse_id);
		$this->db->set("customer_name", $customer_name);
		$this->db->set("customer_ship_to", $customer_ship_to);
		$this->db->set("sku_kode", $item_id);
		$this->db->set("sku_nama_produk", $item_description);
		$this->db->set("sku_jumlah_barang", $transaction_quantity);
		$this->db->set("sku_satuan", $uom_code);
		$this->db->set("unit_price", $unit_price);
		$this->db->set("net_price", $net_price);
		$this->db->set("extended_amount", $extended_amount);
		$this->db->set("pricing_date", $pricing_date);
		$this->db->set("creation_date", $creation_date);
		$this->db->set("creation_time", $creation_time);
		$this->db->set("created_by_name", $created_by_name);
		$this->db->set("shipment_number", $shipment_number);
		$this->db->set("is_download", 0);
		$this->db->set("status", 0);
		$this->db->set("tgl_file", $get_tgl);
		$this->db->set("jam_file", $get_jam);
		$this->db->set("penerimaan_surat_jalan_status", 'Open');
		$this->db->set("penerimaan_surat_jalan_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("penerimaan_surat_jalan_flag_create", 'Manual Download');

		$queryinsert = $this->db->insert("penerimaan_surat_jalan_temp");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function insert_penerimaan_surat_jalan_temp2(
		$penerimaan_surat_jalan_temp2_id,
		$date_now,
		$shipment_number,
		$delivery_number,
		$nomor_armada,
		$nama_expedisi,
		$material,
		$desciption,
		$quantity,
		$uom,
		$batch,
		$get_tgl_batch,
		$get_jam_batch,
		$sku_exp_date
	) {
		$this->db->set("penerimaan_surat_jalan_temp2_id", $penerimaan_surat_jalan_temp2_id);
		$this->db->set("penerimaan_surat_jalan_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("penerimaan_surat_jalan_tgl_create", $date_now);
		$this->db->set("shipment_number", $shipment_number);
		$this->db->set("penerimaan_surat_jalan_no_sj", $delivery_number);
		$this->db->set("nomor_armada", $nomor_armada);
		$this->db->set("nama_expedisi", $nama_expedisi);
		$this->db->set("sku_kode", $material);
		$this->db->set("sku_nama_produk", $desciption);
		$this->db->set("sku_jumlah_barang", $quantity);
		$this->db->set("sku_satuan", $uom);
		$this->db->set("batch", $batch);
		$this->db->set("sku_exp_date", $sku_exp_date);
		$this->db->set("tgl_file", $get_tgl_batch);
		$this->db->set("jam_file", $get_jam_batch);
		$this->db->set("penerimaan_surat_jalan_flag_create", "Manual Download");

		$queryinsert = $this->db->insert("penerimaan_surat_jalan_temp2");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function InsertPenerimaanSuratJalan(
		$PSJTempRow,
		$penerimaan_surat_jalan_id,
		$generate_kode,
		$date_now
	) {
		$this->db->set("penerimaan_surat_jalan_id", $penerimaan_surat_jalan_id);
		$this->db->set("depo_id", $PSJTempRow['depo_id']);
		$this->db->set("principle_id", $PSJTempRow['principle_id']);
		$this->db->set("client_wms_id", $PSJTempRow['client_wms_id']);
		$this->db->set("penerimaan_tipe_id", $PSJTempRow['penerimaan_tipe_id']);
		$this->db->set("penerimaan_surat_jalan_kode", $generate_kode);
		$this->db->set("penerimaan_surat_jalan_no_sj", $PSJTempRow['shipment_number']);
		$this->db->set("penerimaan_surat_jalan_tgl", $PSJTempRow['penerimaan_surat_jalan_tgl']);
		$this->db->set("penerimaan_surat_jalan_status", 'Open');
		$this->db->set("is_status", 0);
		$this->db->set("penerimaan_surat_jalan_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("penerimaan_surat_jalan_tgl_create", "GETDATE()", FALSE);

		$queryinsert = $this->db->insert("penerimaan_surat_jalan");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function insertPSJDetailOri(
		$penerimaan_surat_jalan_id,
		$sku_id,
		$penerimaan_tipe_id,
		$sku_jumlah_barang,
		$sku_exp_date,
		$batch
	) {
		$this->db->set("penerimaan_surat_jalan_detail_ori_id", "NEWID()", FALSE);
		$this->db->set("penerimaan_surat_jalan_id", $penerimaan_surat_jalan_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		$this->db->set("sku_jumlah_barang", $sku_jumlah_barang);
		$this->db->set("sku_exp_date", $sku_exp_date);
		$this->db->set("batch_no", $batch);

		$queryinsert = $this->db->insert("penerimaan_surat_jalan_detail_ori");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function insertPSJDetailTemp2($penerimaan_surat_jalan_id, $shipment_number, $penerimaan_surat_jalan_no_sj, $sku_kode, $batch, $sku_nama_produk, $sku_jumlah_barang)
	{
		$this->db->set("penerimaan_surat_jalan_detail_temp2_id", "NEWID()", FALSE);
		$this->db->set("penerimaan_surat_jalan_id", $penerimaan_surat_jalan_id);
		$this->db->set("shipment_number", $shipment_number);
		$this->db->set("penerimaan_surat_jalan_no_sj", $penerimaan_surat_jalan_no_sj);
		$this->db->set("sku_kode", $sku_kode);
		$this->db->set("sku_nama_produk", $sku_nama_produk);
		$this->db->set("sku_jumlah_barang", $sku_jumlah_barang);
		$this->db->set("batch", $batch);
		$this->db->set("is_download", 0);

		$queryinsert = $this->db->insert("penerimaan_surat_jalan_detail_temp2");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function insertPSJSKUKonversiTemp($sku_konversi_temp_id, $sku_id, $sku_konversi_faktor, $sku_exp_date, $sku_jumlah_barang, $batch)
	{
		$this->db->set("sku_konversi_temp_id", $sku_konversi_temp_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_expired_date", $sku_exp_date);
		$this->db->set("sku_qty", $sku_jumlah_barang);
		$this->db->set("sku_qty_composite", $sku_konversi_faktor);
		$this->db->set("batch_no", $batch);

		$queryinsert = $this->db->insert("sku_konversi_temp");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
			var_dump($this->db->last_query());
		}

		return $queryinsert;
	}

	public function ExecProsesKonversiSKU($sku_konversi_temp_id)
	{
		$query = $this->db->query("Exec proses_konversi_sku '$sku_konversi_temp_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function insertPSJDetail($penerimaan_surat_jalan_id, $sku_id, $hasil, $sku_expired_date, $batch_no)
	{
		$this->db->set("penerimaan_surat_jalan_detail_id", "NEWID()", FALSE);
		$this->db->set("penerimaan_surat_jalan_id", $penerimaan_surat_jalan_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_jumlah_barang", $hasil);
		$this->db->set("sku_exp_date", $sku_expired_date);
		$this->db->set("batch_no", $batch_no);

		$queryinsert = $this->db->insert("penerimaan_surat_jalan_detail");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function UpdatePSJTemp($shipment_number, $principle_id, $mode, $date_now)
	{
		if ($mode == 'depo_id') {
			$keterangan = 'depo_id kosong';
		} else if ($mode == 'principle_id') {
			$keterangan = 'principle_id kosong';
		} else if ($mode == 'client_wms_id') {
			$keterangan = 'client_wms_id kosong';
		} else if ($mode == 'sku_kode') {
			$keterangan = 'sku_kode kosong';
		} else if ($mode == 'SUKSES') {
			$keterangan = 'SUKSES';
			$this->db->set("penerimaan_surat_jalan_status", "Generate Success");
			$this->db->set("status", "1");
		} else {
			$keterangan = 'SKU ' . $mode . ' belum terdaftar';
		}

		$this->db->set("penerimaan_surat_jalan_keterangan", $keterangan);
		// $this->db->set("penerimaan_surat_jalan_tgl_create", $date_now);
		$this->db->set("penerimaan_surat_jalan_who_update", $this->session->userdata('pengguna_username'));
		$this->db->set("penerimaan_surat_jalan_tgl_update", $date_now);

		$this->db->where("shipment_number", $shipment_number);
		$this->db->where("principle_id", $principle_id);

		$queryupdate = $this->db->update("penerimaan_surat_jalan_temp");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function CheckDO($delivery_order_number, $shipment_number)
	{
		$query =  $this->db->query("SELECT TOP 1 penerimaan_surat_jalan_no_sj, tgl_file, jam_file, format(penerimaan_surat_jalan_tgl_create, 'yyyy-MM-dd HH:mm:ss') as tgl_upload 
		FROM penerimaan_surat_jalan_temp WHERE penerimaan_surat_jalan_no_sj = '" . $delivery_order_number . "' 
		AND shipment_number = '" . $shipment_number . "' ORDER BY tgl_upload DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function CheckDOBatch($delivery_order_number, $shipment_number)
	{
		$query =  $this->db->query("SELECT TOP 1 penerimaan_surat_jalan_no_sj, tgl_file, jam_file, 
		format(penerimaan_surat_jalan_tgl_create, 'yyyy-MM-dd HH:mm:ss') as tgl_upload 
		FROM penerimaan_surat_jalan_temp2 WHERE penerimaan_surat_jalan_no_sj = '" . $delivery_order_number . "' 
		AND shipment_number = '" . $shipment_number . "' ORDER BY tgl_upload DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function DeleteDOShipment($delivery_order_number, $shipment_number)
	{
		$querydelete = $this->db->query("DELETE FROM penerimaan_surat_jalan_temp WHERE penerimaan_surat_jalan_no_sj = '" . $delivery_order_number . "' AND shipment_number = '" . $shipment_number . "'");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}

	public function DeleteDOShipmentBatch($delivery_order_number, $shipment_number)
	{
		$querydelete = $this->db->query("DELETE FROM penerimaan_surat_jalan_temp2 WHERE penerimaan_surat_jalan_no_sj = '" . $delivery_order_number . "' AND shipment_number = '" . $shipment_number . "'");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}

	public function DeleteDOPSJ($penerimaan_surat_jalan_no_sj, $principle_id)
	{
		$querydelete = $this->db->query("DELETE FROM penerimaan_surat_jalan WHERE penerimaan_surat_jalan_no_sj = '" . $penerimaan_surat_jalan_no_sj . "' AND principle_id = '" . $principle_id . "'");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}

	public function DeleteDOPSJDetail()
	{
		$querydelete = $this->db->query("DELETE FROM penerimaan_surat_jalan_detail WHERE penerimaan_surat_jalan_id NOT IN (SELECT penerimaan_surat_jalan_id FROM penerimaan_surat_jalan)");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}

	public function DeleteDOPSJDetailOri()
	{
		$querydelete = $this->db->query("DELETE FROM penerimaan_surat_jalan_detail_ori WHERE penerimaan_surat_jalan_id NOT IN (SELECT penerimaan_surat_jalan_id FROM penerimaan_surat_jalan)");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}
}
