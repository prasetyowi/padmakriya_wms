<?php

class M_PermintaanPengemasanByQtyDO extends CI_Model
{
	//tes user baru 5
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function GetTipeDeliveryOrder()
	{
		$query = $this->db->query("SELECT * from tipe_delivery_order where tipe_delivery_order_tipe = 'Out' and tipe_delivery_order_nama not in ('Standar','Claim Driver','Claim Sales','Mix')");
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getDataSearch($tgl, $tipe)
	{
		$wtipe = "";
		if ($tipe == 'DO Reschedule') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0)
						AND isnull(dd.depo_detail_is_flashout, 0) = 0
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 1
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		if ($tipe == 'Picking per SKU' || $tipe == 'Canvas') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0,1)
						AND isnull(dd.depo_detail_is_flashout, 0) = 0
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 0
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		if ($tipe == 'Flush Out') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0)
						AND isnull(dd.depo_detail_is_flashout, 0) = 1
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 0
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		$depo_id = $this->session->userdata("depo_id");

		$query = $this->db->query("SELECT
									do_detail.sku_id
									,sku.sku_kode
									,sku.sku_nama_produk
									,sku.principle_id
									,principle.principle_kode AS principle
									,sku.principle_brand_id
									,brand.principle_brand_nama AS brand
									,sku.sku_satuan
									,sku.sku_kemasan
									,sku.sku_konversi_group
									,sku.sku_konversi_level
									,sku.sku_konversi_faktor
									,CASE
										WHEN do_detail.sku_harga_nett = 0 THEN 'Bonus'
										ELSE 'Reguler'
									END AS type_item
									,SUM(do_detail.sku_qty) AS sku_qty
									,SUM(ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0)) AS sku_stock_qty
									,SUM(do_detail.sku_qty * sku.sku_konversi_faktor) AS sku_qty_konversi
									,ISNULL((SELECT
										SUM((ISNULL(ss.sku_stock_awal, 0) * s.sku_konversi_faktor) + (ISNULL(ss.sku_stock_masuk, 0) * s.sku_konversi_faktor) - (ISNULL(ss.sku_stock_saldo_alokasi, 0) * s.sku_konversi_faktor) - (ISNULL(ss.sku_stock_keluar, 0) * s.sku_konversi_faktor)) AS sku_stock_qty
										FROM sku_stock ss
										LEFT JOIN sku s
										ON s.sku_id = ss.sku_id
										WHERE s.sku_konversi_level < sku.sku_konversi_level
										AND s.sku_konversi_group = sku.sku_konversi_group)
									, 0)
									AS sku_stock_qty_bisa_dikemas
									,CASE
										WHEN SUM(do_detail.sku_qty) > SUM(ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0)) THEN '0'
										ELSE '1'
									END is_cukup
									FROM delivery_order_draft do
									LEFT JOIN delivery_order_detail_draft do_detail
									ON do_detail.delivery_order_draft_id = do.delivery_order_draft_id
									LEFT JOIN (SELECT
										sku_id
									,CASE
										WHEN dd.depo_detail_is_bonus = 1 THEN 'Bonus'
										ELSE 'Reguler'
										END AS flag
									,SUM(sku_stock_awal) AS sku_stock_awal
									,SUM(ss.sku_stock_masuk) AS sku_stock_masuk
									,SUM(ss.sku_stock_alokasi) AS sku_stock_alokasi
									,SUM(ss.sku_stock_saldo_alokasi) AS sku_stock_saldo_alokasi
									,SUM(ss.sku_stock_keluar) AS sku_stock_keluar
									,SUM(ss.sku_stock_akhir) AS sku_stock_akhir
									FROM sku_stock ss
									LEFT JOIN depo_detail dd
										ON dd.depo_detail_id = ss.depo_detail_id
									WHERE ss.depo_id = '$depo_id'
									" . $wtipe . "
									GROUP BY sku_id
											,CASE
												WHEN dd.depo_detail_is_bonus = 1 THEN 'Bonus'
												ELSE 'Reguler'
											END) sku_stock
									ON sku_stock.sku_id = do_detail.sku_id
										AND sku_stock.flag = CASE
										WHEN do_detail.sku_harga_nett = 0 THEN 'Bonus'
										ELSE 'Reguler'
										END
									LEFT JOIN sku
									ON sku.sku_id = do_detail.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand brand
									ON brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN tipe_delivery_order tp
									ON tp.tipe_delivery_order_id = do.tipe_delivery_order_id
									WHERE FORMAT(do.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') <= '$tgl'
									AND do.depo_id = '$depo_id'
									AND do.delivery_order_draft_status = 'Draft'
									AND tp.tipe_delivery_order_alias = 'Picking per SKU'
									GROUP BY do_detail.sku_id
											,sku.sku_kode
											,sku.sku_nama_produk
											,sku.principle_id
											,principle.principle_kode
											,sku.principle_brand_id
											,brand.principle_brand_nama
											,sku.sku_satuan
											,sku.sku_kemasan
											,sku.sku_konversi_group
											,sku.sku_konversi_level
											,sku.sku_konversi_faktor
											,CASE
											WHEN do_detail.sku_harga_nett = 0 THEN 'Bonus'
											ELSE 'Reguler'
											END
									HAVING sku.sku_konversi_level > (select min(sku_konversi_level) as sku_konversi_level from sku s where s.sku_konversi_group = sku.sku_konversi_group)
									ORDER BY principle.principle_kode, brand.principle_brand_nama, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function showDetailDOBySKU($sku_id, $tgl, $tipe)
	{
		$depo_id = $this->session->userdata("depo_id");
		$query = $this->db->query("SELECT
										dod.delivery_order_draft_kode AS do_draft_kode,
										dod.delivery_order_draft_kirim_nama AS do_draft_nama,
										dod.delivery_order_draft_kirim_alamat AS do_draft_alamat,
										dodd.sku_qty AS do_draft_qty
									FROM delivery_order_draft AS dod
									LEFT JOIN delivery_order_detail_draft AS dodd ON dod.delivery_order_draft_id = dodd.delivery_order_draft_id
									LEFT JOIN tipe_delivery_order AS tp on tp.tipe_delivery_order_id = dod.tipe_delivery_order_id
									WHERE dodd.sku_id = '$sku_id'
									AND dod.depo_id = '$depo_id'
									AND FORMAT(dod.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') <= '$tgl'
									AND dod.delivery_order_draft_status = 'Draft'
									AND tp.tipe_delivery_order_alias = '$tipe'
								");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getDataSearch1($tgl1, $tgl2)
	{
		$depo_id = $this->session->userdata("depo_id");

		return $this->db->query("SELECT
		principle.principle_kode AS principle,
		brand.principle_brand_nama AS brand,
		ss.client_wms_id,
		ss.sku_stock_id,
		depo_detail.depo_detail_id,
		depo_detail.depo_detail_nama,
		s.sku_kode,
		s.sku_id,
		s.sku_nama_produk,
		SUM(d2d.sku_qty) AS sku_qty_draft,
		ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(ss.sku_stock_saldo_alokasi, 0) - ISNULL(ss.sku_stock_keluar, 0) AS sku_stock,
		s.sku_konversi_group,
		s.sku_kemasan,
	    s.sku_satuan,
		SUM(d2d.sku_qty) * s.sku_konversi_faktor as qty_composite,
		s.sku_konversi_level,
		s.sku_konversi_faktor,
		d2d.sku_expdate,
		CASE 
			WHEN SUM(d2d.sku_qty) > ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(ss.sku_stock_saldo_alokasi, 0) - ISNULL(ss.sku_stock_keluar, 0) then 1 else 0 end as type_request
	FROM delivery_order_draft dd
	INNER JOIN delivery_order_detail2_draft d2d
	  ON dd.delivery_order_draft_id = d2d.delivery_order_draft_id
	INNER JOIN sku s
	  ON s.sku_id = d2d.sku_id
	  INNER JOIN sku_stock ss on ss.sku_stock_id = d2d.sku_stock_id and ss.sku_id = d2d.sku_id
	INNER JOIN depo_detail
		ON depo_detail.depo_detail_id = ss.depo_detail_id 
		LEFT JOIN principle
		ON principle.principle_id = s.principle_id
		LEFT JOIN principle_brand brand
		ON brand.principle_brand_id = s.principle_brand_id
	WHERE dd.depo_id = '$depo_id'
	--AND depo_detail.depo_id = '$depo_id'
	AND depo_detail.depo_detail_flag_jual = '1'
	and left(sku_satuan,3) not like 'krt'
	
	AND FORMAT(dd.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
	
	GROUP BY 
	s.sku_konversi_level,
		depo_detail.depo_detail_nama,
		d2d.sku_id,
		ss.client_wms_id,
		ss.sku_stock_id,
		depo_detail.depo_detail_id,
		principle.principle_kode ,
		brand.principle_brand_nama ,
		s.sku_kode,
		s.sku_id,
		s.sku_kemasan,
		s.sku_satuan,
		s.sku_nama_produk,
		s.sku_konversi_group,
		s.sku_konversi_faktor,
		d2d.sku_expdate,
		ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(ss.sku_stock_saldo_alokasi, 0) - ISNULL(ss.sku_stock_keluar, 0)
		order by  principle.principle_kode ,
    	brand.principle_brand_nama ,s.sku_kode")->result_array();
	}

	public function cekKonversiLevel($sku_konversi_group, $depo_detail_id, $level)
	{
		$depo_id = $this->session->userdata('depo_id');

		$query = $this->db->query("SELECT
									b.sku_stock_id,
									a.sku_id,
									a.sku_satuan,
									a.sku_konversi_level,
									ISNULL(ABS(b.sku_stock_awal), 0) + ISNULL(ABS(b.sku_stock_masuk), 0) - ISNULL(ABS(b.sku_stock_saldo_alokasi), 0) - ISNULL(ABS(b.sku_stock_keluar), 0) AS stok,
									b.sku_stock_expired_date,
									c.depo_detail_id,
									c.depo_detail_nama
									FROM sku a
									LEFT JOIN sku_stock b
									ON b.sku_id = a.sku_id
									LEFT JOIN depo_detail c
									ON b.depo_detail_id = c.depo_detail_id
									WHERE a.sku_konversi_group = '$sku_konversi_group'
									AND a.sku_konversi_level >= $level + 1
									AND b.depo_id = '$depo_id'
									AND isnull(c.depo_detail_flag_jual, 0) = 1
									AND isnull(c.depo_detail_is_bonus, 0) in (0, 1)
									AND isnull(c.depo_detail_is_flashout, 0) in (0, 1)
									AND isnull(c.depo_detail_is_kirimulang, 0) = 0
									-- AND b.depo_detail_id = '$depo_detail_id'
									AND ISNULL(ABS(b.sku_stock_awal), 0) + ISNULL(ABS(b.sku_stock_masuk), 0) - ISNULL(ABS(b.sku_stock_saldo_alokasi), 0) - ISNULL(ABS(b.sku_stock_keluar), 0) > 0
									ORDER BY b.sku_stock_expired_date ASC,
									ISNULL(ABS(b.sku_stock_awal), 0) + ISNULL(ABS(b.sku_stock_masuk), 0) - ISNULL(ABS(b.sku_stock_saldo_alokasi), 0) - ISNULL(ABS(b.sku_stock_keluar), 0) DESC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function HitungQtyByKemasan($id, $qty)
	{
		// return [['sku_id' => 'B7370AEE-7ACC-473A-A7FB-011C75AA46C6', 'hasil' => '5555556', 'sku_konversi_level' => 0, 'sku_satuan' => '', 'idx' => '']];
		$query = $this->db->query("exec proses_konversi_sku_pack_unpack '$id','$qty','Repack_do'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function getDataSKU($sku_id)
	{
		$query = $this->db->query("SELECT sku_konversi_group FROM sku WHERE sku_id ='$sku_id'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function getSkuIDByKonversiGrup($sku_konversi_grup)
	{
		$query = $this->db->query("SELECT TOP 1 * FROM sku WHERE sku_konversi_group ='$sku_konversi_grup' order by sku_konversi_level desc");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function insertSKUKonversiTemp($sku_konversi_temp_id, $sku_id, $sku_expired_date, $sku_qty, $sku_qty_composite)
	{
		$this->db->set("sku_konversi_temp_id", $sku_konversi_temp_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_expired_date", $sku_expired_date);
		$this->db->set("batch_no", NULL);
		$this->db->set("sku_qty", $sku_qty);
		$this->db->set("sku_qty_composite", intval($sku_qty_composite));

		$this->db->insert("sku_konversi_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function insert_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create, $is_from_do)
	{
		$tr_konversi_sku_id = $tr_konversi_sku_id == '' ? null : $tr_konversi_sku_id;
		$client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
		$depo_detail_id = $depo_detail_id == '' ? null : $depo_detail_id;
		$tipe_konversi_id = $tipe_konversi_id == '' ? null : $tipe_konversi_id;
		$tr_konversi_sku_kode = $tr_konversi_sku_kode == '' ? null : $tr_konversi_sku_kode;
		$tr_konversi_sku_tanggal = $tr_konversi_sku_tanggal == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $tr_konversi_sku_tanggal)));;
		$tr_konversi_sku_status = $tr_konversi_sku_status == '' ? null : $tr_konversi_sku_status;
		$tr_konversi_sku_tgl_create = $tr_konversi_sku_tgl_create == '' ? null : $tr_konversi_sku_tgl_create;
		$tr_konversi_sku_who_create = $tr_konversi_sku_who_create == '' ? null : $tr_konversi_sku_who_create;
		$is_from_do = $is_from_do == '' ? null : $is_from_do;
		$tr_konversi_sku_who_create = $this->session->userdata('pengguna_username');


		$this->db->set("tr_konversi_sku_id", $tr_konversi_sku_id);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("tipe_konversi_id", $tipe_konversi_id);
		$this->db->set("tr_konversi_sku_kode", $tr_konversi_sku_kode);
		$this->db->set("tr_konversi_sku_tanggal", 'getdate()', false);
		$this->db->set("tr_konversi_sku_status", 'Draft');
		$this->db->set("tr_konversi_sku_keterangan", $tr_konversi_sku_keterangan);
		$this->db->set("tr_konversi_sku_tgl_create", "GETDATE()", FALSE);
		$this->db->set("tr_konversi_sku_who_create", $tr_konversi_sku_who_create);
		$this->db->set("is_from_do", 1);

		$queryinsert = $this->db->insert("tr_konversi_sku");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_tr_konversi_sku_detail($tr_konversi_sku_detail_id, $tr_konversi_sku_id, $data)
	{
		$sku_stock_id = $data["sku_stock_id"] == "" ? null : $data["sku_stock_id"];
		$sku_id = $data["sku_id"] == "" ? null : $data["sku_id"];
		$sku_stock_expired_date = $data["sku_expdate"] == "" ? null : $data["sku_expdate"];
		$tr_konversi_sku_detail_qty_plan = $data["hasil"] == "" ? null : $data["hasil"];
		$sku_satuan_hasil = $data['sku_satuan_hasil'] == "" ? null : $data['sku_satuan_hasil'];

		$sku_stock_data = $this->db->select("*")->from("sku_stock")->where("sku_stock_id", $sku_stock_id)->get()->row();

		$this->db->set("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		$this->db->set("tr_konversi_sku_id", $tr_konversi_sku_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
		$this->db->set("tr_konversi_sku_detail_qty_plan", $tr_konversi_sku_detail_qty_plan);
		$this->db->set("sku_konversi_hasil", $sku_satuan_hasil);

		$queryinsert = $this->db->insert("tr_konversi_sku_detail");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_tr_konversi_sku_temp($tr_konversi_sku_temp_id, $client_wms_id, $sku_id, $depo_detail_id, $sku_stock_id, $sku_stock_expired_date, $tr_konversi_sku_detail_qty_plan)
	{
		$client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
		$depo_detail_id = $depo_detail_id == '' ? null : $depo_detail_id;
		$sku_id = $sku_id == '' ? null : $sku_id;
		$sku_stock_id = $sku_stock_id == '' ? null : $sku_stock_id;
		$sku_stock_expired_date = $sku_stock_expired_date == '' ? null : $sku_stock_expired_date;
		$tr_konversi_sku_detail_qty_plan = $tr_konversi_sku_detail_qty_plan == '' ? null : $tr_konversi_sku_detail_qty_plan;

		$this->db->set('tr_konversi_sku_detail_temp_id', "NEWID()", FALSE);
		$this->db->set('tr_konversi_sku_temp_id', $tr_konversi_sku_temp_id);
		$this->db->set('client_wms_id', $client_wms_id);
		$this->db->set('depo_detail_id', $depo_detail_id);
		$this->db->set('sku_id', $sku_id);
		$this->db->set('sku_stock_id', $sku_stock_id);
		$this->db->set('sku_stock_expired_date', $sku_stock_expired_date);
		$this->db->set('tr_konversi_sku_detail_qty_plan', $tr_konversi_sku_detail_qty_plan);

		$this->db->insert("tr_konversi_sku_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Delete_tr_konversi_sku_temp_by_id($tr_konversi_sku_temp_id)
	{
		$this->db->where('tr_konversi_sku_temp_id', $tr_konversi_sku_temp_id);
		$this->db->insert("tr_konversi_sku_temp");

		$affectedrows = $this->db->affected_rows();
		if (
			$affectedrows > 0
		) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Get_tr_konversi_sku_temp_by_id($tr_konversi_sku_temp_id)
	{
		$query = $this->db->query("select * from tr_konversi_sku_temp where tr_konversi_sku_temp_id = '$tr_konversi_sku_temp_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetListPengemasan($tgl, $tipe, $principle, $flag)
	{

		$wtipe = "";
		if ($tipe == 'DO Reschedule') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0)
						AND isnull(dd.depo_detail_is_flashout, 0) = 0
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 1
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		if ($tipe == 'Picking per SKU' || $tipe == 'Canvas') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0,1)
						AND isnull(dd.depo_detail_is_flashout, 0) = 0
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 0
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		if ($tipe == 'Flush Out') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0)
						AND isnull(dd.depo_detail_is_flashout, 0) = 1
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 0
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		$depo_id = $this->session->userdata("depo_id");

		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND principle.principle_kode = '$principle'";
		}

		if ($flag == "") {
			$flag = "";
		} else {
			$flag = "AND CASE WHEN do_detail.sku_harga_nett = 0 THEN 'Bonus' ELSE 'Reguler' END = '$flag'";
		}

		$query = $this->db->query("SELECT
									do_detail.sku_id
									,sku.client_wms_id
									,sku.sku_kode
									,sku.sku_nama_produk
									,sku.principle_id
									,principle.principle_kode AS principle
									,sku.principle_brand_id
									,brand.principle_brand_nama AS brand
									,sku.sku_satuan
									,sku.sku_kemasan
									,sku.sku_konversi_group
									,sku.sku_konversi_level
									,sku.sku_konversi_faktor
									,CASE
										WHEN do_detail.sku_harga_nett = 0 THEN 'Bonus'
										ELSE 'Reguler'
									END AS type_item
									,SUM(do_detail.sku_qty) AS sku_qty
									,SUM(ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0)) AS sku_stock_qty
									,SUM(do_detail.sku_qty * sku.sku_konversi_faktor) AS sku_qty_konversi
									,ISNULL((SELECT
										SUM((ISNULL(ss.sku_stock_awal, 0) * s.sku_konversi_faktor) + (ISNULL(ss.sku_stock_masuk, 0) * s.sku_konversi_faktor) - (ISNULL(ss.sku_stock_saldo_alokasi, 0) * s.sku_konversi_faktor) - (ISNULL(ss.sku_stock_keluar, 0) * s.sku_konversi_faktor)) AS sku_stock_qty
										FROM sku_stock ss
										LEFT JOIN sku s
										ON s.sku_id = ss.sku_id
										WHERE s.sku_konversi_level < sku.sku_konversi_level
										AND s.sku_konversi_group = sku.sku_konversi_group)
									, 0)
									AS sku_stock_qty_bisa_dikemas
									,CASE
										WHEN SUM(do_detail.sku_qty) > SUM(ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0)) THEN '0'
										ELSE '1'
									END is_cukup
									FROM delivery_order_draft do
									LEFT JOIN delivery_order_detail_draft do_detail
									ON do_detail.delivery_order_draft_id = do.delivery_order_draft_id
									LEFT JOIN (SELECT
										sku_id
									,CASE
										WHEN dd.depo_detail_is_bonus = 1 THEN 'Bonus'
										ELSE 'Reguler'
										END AS flag
									,SUM(sku_stock_awal) AS sku_stock_awal
									,SUM(ss.sku_stock_masuk) AS sku_stock_masuk
									,SUM(ss.sku_stock_alokasi) AS sku_stock_alokasi
									,SUM(ss.sku_stock_saldo_alokasi) AS sku_stock_saldo_alokasi
									,SUM(ss.sku_stock_keluar) AS sku_stock_keluar
									,SUM(ss.sku_stock_akhir) AS sku_stock_akhir
									FROM sku_stock ss
									LEFT JOIN depo_detail dd
										ON dd.depo_detail_id = ss.depo_detail_id
									WHERE ss.depo_id = '$depo_id'
									" . $wtipe . "
									GROUP BY sku_id
											,CASE
												WHEN dd.depo_detail_is_bonus = 1 THEN 'Bonus'
												ELSE 'Reguler'
											END) sku_stock
									ON sku_stock.sku_id = do_detail.sku_id
										AND sku_stock.flag = CASE
										WHEN do_detail.sku_harga_nett = 0 THEN 'Bonus'
										ELSE 'Reguler'
										END
									LEFT JOIN sku
									ON sku.sku_id = do_detail.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand brand
									ON brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN tipe_delivery_order tp
									ON tp.tipe_delivery_order_id = do.tipe_delivery_order_id
									WHERE FORMAT(do.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') <= '$tgl'
									AND do.depo_id = '$depo_id'
									AND do.delivery_order_draft_status = 'Draft'
									AND tp.tipe_delivery_order_alias = 'Picking per SKU'
									" . $principle . "
									" . $flag . "
									GROUP BY do_detail.sku_id
											,sku.client_wms_id
											,sku.sku_kode
											,sku.sku_nama_produk
											,sku.principle_id
											,principle.principle_kode
											,sku.principle_brand_id
											,brand.principle_brand_nama
											,sku.sku_satuan
											,sku.sku_kemasan
											,sku.sku_konversi_group
											,sku.sku_konversi_level
											,sku.sku_konversi_faktor
											,CASE
											WHEN do_detail.sku_harga_nett = 0 THEN 'Bonus'
											ELSE 'Reguler'
											END
									HAVING sku.sku_konversi_level > (select min(sku_konversi_level) as sku_konversi_level from sku s where s.sku_konversi_group = sku.sku_konversi_group)
									AND SUM(do_detail.sku_qty) > SUM(ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0))
									ORDER BY principle.principle_kode, brand.principle_brand_nama, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetListHitungKonversiPengemasan($tgl, $tipe, $filter_sku_id)
	{
		$filter_sku_id_str = "";

		if (count($filter_sku_id) > 0) {
			$filter_sku_id_str = "AND do_detail.sku_id IN (" . implode(",", $filter_sku_id) . ")";
		} else {
			$filter_sku_id_str = "";
		}

		$wtipe = "";
		if ($tipe == 'DO Reschedule') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0)
						AND isnull(dd.depo_detail_is_flashout, 0) = 0
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 1
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		if ($tipe == 'Picking per SKU' || $tipe == 'Canvas') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0,1)
						AND isnull(dd.depo_detail_is_flashout, 0) = 0
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 0
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}
		if ($tipe == 'Flush Out') {
			$wtipe = "AND isnull(dd.depo_detail_flag_jual, 0) = 1
						AND isnull(dd.depo_detail_is_bonus, 0) in (0)
						AND isnull(dd.depo_detail_is_flashout, 0) = 1
						AND isnull(dd.depo_detail_is_kirimulang, 0) = 0
						AND isnull(dd.depo_detail_is_qa, 0) = 0";
		}

		$depo_id = $this->session->userdata("depo_id");

		$query = $this->db->query("SELECT do.sku_id,
									do.sku_kode,
									do.sku_nama_produk,
									do.principle_id,
									principle.principle_kode AS principle,
									do.principle_brand_id,
									brand.principle_brand_nama AS brand,
									ISNULL(CONVERT(NVARCHAR(36), do.client_wms_id), '') AS client_wms_id,
									do.sku_satuan,
									do.sku_kemasan,
									do.sku_konversi_group,
									do.sku_konversi_level,
									do.sku_konversi_faktor,
									do.sku_qty,
									do.sku_qty_konversi,
									(do.sku_qty_konversi / sku_stock.sku_konversi_faktor) as sku_qty_konversi_rekomendasi,
									ISNULL(CONVERT(NVARCHAR(36), sku_stock.sku_stock_id), '') AS sku_stock_id_konversi,
									ISNULL(CONVERT(NVARCHAR(36), sku_stock.sku_id), '') AS sku_id_konversi,
									ISNULL(sku_stock.sku_satuan, '') AS sku_satuan_konversi,
									ISNULL(sku_stock.sku_kemasan, '') AS sku_kemasan_konversi,
									ISNULL(sku_stock.sku_konversi_group, '') AS sku_konversi_group,
									ISNULL(sku_stock.sku_konversi_faktor, 0) AS sku_konversi_faktor,
									ISNULL(sku_stock.sku_konversi_level, '') AS sku_konversi_level,
									ISNULL(CONVERT(NVARCHAR(36), sku_stock.depo_detail_id), '') AS depo_detail_id,
									ISNULL(dd.depo_detail_nama, '') AS depo_detail_nama,
									ISNULL(CONVERT(NVARCHAR(50), FORMAT(sku_stock.sku_stock_expired_date, 'yyyy-MM-dd')), '') AS sku_stock_expired_date,
									(ISNULL(sku_stock.sku_stock_awal, 0) + ISNULL(sku_stock.sku_stock_masuk, 0) - ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) - ISNULL(sku_stock.sku_stock_keluar, 0)) AS sku_stock_qty,
									((ISNULL(sku_stock.sku_stock_awal, 0) * ISNULL(sku_stock.sku_konversi_faktor, 0)) + (ISNULL(sku_stock.sku_stock_masuk, 0) * ISNULL(sku_stock.sku_konversi_faktor, 0)) - (ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) * ISNULL(sku_stock.sku_konversi_faktor, 0)) - (ISNULL(sku_stock.sku_stock_keluar, 0) * ISNULL(sku_stock.sku_konversi_faktor, 0))) AS sku_stock_qty_konversi,
									CASE
										WHEN ((ISNULL(sku_stock.sku_stock_awal, 0) * ISNULL(sku_stock.sku_konversi_faktor, 0)) + (ISNULL(sku_stock.sku_stock_masuk, 0) * ISNULL(sku_stock.sku_konversi_faktor, 0)) - (ISNULL(sku_stock.sku_stock_saldo_alokasi, 0) * ISNULL(sku_stock.sku_konversi_faktor, 0)) - (ISNULL(sku_stock.sku_stock_keluar, 0) * ISNULL(sku_stock.sku_konversi_faktor, 0))) >= do.sku_qty_konversi THEN 1
										ELSE 0
									END is_pengemasan
								FROM
								(SELECT do_detail.sku_id,
										sku.sku_kode,
										sku.sku_nama_produk,
										sku.principle_id,
										sku.principle_brand_id,
										ISNULL(CONVERT(NVARCHAR(36), sku.client_wms_id), '') AS client_wms_id,
										sku.sku_satuan,
										sku.sku_kemasan,
										sku.sku_konversi_group,
										sku.sku_konversi_level,
										sku.sku_konversi_faktor,
										SUM(do_detail.sku_qty) AS sku_qty,
										SUM(do_detail.sku_qty * sku.sku_konversi_faktor) AS sku_qty_konversi
								FROM delivery_order_draft DO
								LEFT JOIN delivery_order_detail_draft do_detail ON do_detail.delivery_order_draft_id = do.delivery_order_draft_id
								LEFT JOIN sku ON sku.sku_id = do_detail.sku_id
								LEFT JOIN tipe_delivery_order tp ON tp.tipe_delivery_order_id = do.tipe_delivery_order_id
								WHERE FORMAT(do.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') <= '$tgl'
									AND do.depo_id = '$depo_id'
									AND do.delivery_order_draft_status = 'Draft'
									AND tp.tipe_delivery_order_alias = 'Picking per SKU'
									" . $filter_sku_id_str . "
								GROUP BY do_detail.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.principle_id,
											sku.principle_brand_id,
											sku.client_wms_id,
											sku.sku_satuan,
											sku.sku_kemasan,
											sku.sku_konversi_group,
											sku.sku_konversi_level,
											sku.sku_konversi_faktor) DO
								LEFT JOIN
								(SELECT ss.sku_stock_id,
										ss.unit_mandiri_id,
										ss.client_wms_id,
										ss.depo_id,
										ss.depo_detail_id,
										ss.sku_induk_id,
										ss.sku_id,
										ss.sku_stock_expired_date,
										ss.sku_stock_batch_no,
										ss.sku_stock_awal,
										ss.sku_stock_masuk,
										ss.sku_stock_alokasi,
										ss.sku_stock_saldo_alokasi,
										ss.sku_stock_keluar,
										ss.sku_stock_akhir,
										ss.sku_stock_is_jual,
										ss.sku_stock_is_aktif,
										ss.sku_stock_is_deleted,
										s.sku_satuan,
										s.sku_kemasan,
										s.sku_konversi_group,
										s.sku_konversi_faktor,
										s.sku_konversi_level
								FROM sku_stock ss
								LEFT JOIN sku s ON s.sku_id = ss.sku_id
								LEFT JOIN depo_detail dd ON dd.depo_detail_id = ss.depo_detail_id
								WHERE ss.depo_id = '$depo_id'
									  " . $wtipe . ") sku_stock ON sku_stock.sku_konversi_group = do.sku_konversi_group
								AND sku_stock.sku_konversi_level < do.sku_konversi_level
								LEFT JOIN principle ON principle.principle_id = do.principle_id
								LEFT JOIN principle_brand brand ON brand.principle_brand_id = do.principle_brand_id
								LEFT JOIN depo_detail dd ON dd.depo_detail_id = sku_stock.depo_detail_id
								ORDER BY principle.principle_kode,
										brand.principle_brand_nama,
										do.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetTableSementaraGroupByPrincipleDepoDetail($arr_persiapan_pengemasan)
	{
		$union = " UNION ALL ";
		$table_sementara = "";

		foreach ($arr_persiapan_pengemasan as $key => $value) {
			$hasil = $value['hasil'] == "" || $value['hasil'] === NULL ? 0 : $value['hasil'];

			$table_sementara .= "SELECT '" . $value['sku_stock_id'] . "' AS sku_stock_id, '" . $value['sku_id'] . "' AS sku_id, '" . $value['client_wms_id'] . "' AS client_wms_id, '" . $value['principle_id'] . "' AS principle_id, '" . $value['depo_detail_id'] . "' AS depo_detail_id, '" . $value['sku_satuan_hasil'] . "' AS sku_satuan_hasil, '" . $value['sku_expdate'] . "' AS sku_expdate, " . $hasil . " AS hasil";

			if ($key < count($arr_persiapan_pengemasan) - 1) {
				$table_sementara .= $union;
			}
		}

		$query = $this->db->query("SELECT
										client_wms_id,
										principle_id,
										depo_detail_id
									FROM (" . $table_sementara . ") a
									GROUP BY client_wms_id,
											 principle_id,
											 depo_detail_id
									ORDER BY client_wms_id, principle_id, depo_detail_id");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_tr_konversi_sku_detail_temp($client_wms_id, $principle_id, $depo_detail_id, $arr_persiapan_pengemasan)
	{
		$union = " UNION ALL ";
		$table_sementara = "";

		foreach ($arr_persiapan_pengemasan as $key => $value) {
			$hasil = $value['hasil'] == "" || $value['hasil'] === NULL ? 0 : $value['hasil'];

			$table_sementara .= "SELECT '" . $value['sku_stock_id'] . "' AS sku_stock_id, '" . $value['sku_id'] . "' AS sku_id, '" . $value['client_wms_id'] . "' AS client_wms_id, '" . $value['principle_id'] . "' AS principle_id, '" . $value['depo_detail_id'] . "' AS depo_detail_id, '" . $value['sku_satuan_hasil'] . "' AS sku_satuan_hasil, '" . $value['sku_expdate'] . "' AS sku_expdate, " . $hasil . " AS hasil";

			if ($key < count($arr_persiapan_pengemasan) - 1) {
				$table_sementara .= $union;
			}
		}

		$query = $this->db->query("SELECT
										*
									FROM (" . $table_sementara . ") a
									WHERE client_wms_id = '$client_wms_id' AND principle_id = '$principle_id' AND depo_detail_id='$depo_detail_id'
									ORDER BY sku_stock_id ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
}
