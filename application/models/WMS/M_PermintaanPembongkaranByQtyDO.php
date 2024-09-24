<?php

class M_PermintaanPembongkaranByQtyDO extends CI_Model
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
		return $this->db->query("SELECT
									principle.principle_kode AS principle,
									brand.principle_brand_nama AS brand,
									s.client_wms_id,
									-- ss.depo_detail_id,
									-- ss.depo_detail_nama,
									s.sku_kode,
									s.sku_id,
									s.sku_nama_produk,
									SUM(d2d.sku_qty) AS sku_qty_draft,
									ISNULL(ss.stok, 0) AS sku_stock,
									s.sku_konversi_group,
									s.sku_kemasan,
									s.sku_satuan,
									SUM(d2d.sku_qty) * s.sku_konversi_faktor as qty_composite,
									s.sku_konversi_level,
									s.sku_konversi_faktor,
									CASE 
										WHEN SUM(d2d.sku_qty) > ISNULL(ss.stok, 0) then 1 else 0 end as type_request,
									d2d.type_item as flag
								FROM delivery_order_draft dd
								INNER JOIN (select sku_id, delivery_order_draft_id, sku_qty, case when sku_harga_nett = 0 then 'Bonus' else 'Reguler' end as type_item from delivery_order_detail_draft
										) d2d
								ON dd.delivery_order_draft_id = d2d.delivery_order_draft_id
								left join tipe_delivery_order tp on tp.tipe_delivery_order_id = dd.tipe_delivery_order_id
								INNER JOIN sku s
								ON s.sku_id = d2d.sku_id
								--INNER JOIN sku_stock ss on ss.sku_stock_id = d2d.sku_stock_id and ss.sku_id = d2d.sku_id
								LEFT JOIN ( select SUM(ISNULL(ss.sku_stock_awal, 0) + ISNULL(ss.sku_stock_masuk, 0) - ISNULL(ss.sku_stock_saldo_alokasi, 0) - ISNULL(ss.sku_stock_keluar, 0)) as stok, sku_id, CASE WHEN dd.depo_detail_is_bonus = 1 then 'Bonus' ELSE 'Reguler' END AS flag from sku_stock ss
													INNER JOIN depo_detail dd
														ON dd.depo_detail_id = ss.depo_detail_id 
														WHERE dd.depo_id = '$depo_id' and ss.depo_id ='$depo_id'
														$wtipe
														group by sku_id, CASE WHEN dd.depo_detail_is_bonus = 1 then 'Bonus' ELSE 'Reguler' END
											) ss on d2d.sku_id = ss.sku_id and s.sku_id = ss.sku_id and ss.flag = d2d.type_item
								LEFT JOIN principle
									ON principle.principle_id = s.principle_id
								LEFT JOIN principle_brand brand
									ON brand.principle_brand_id = s.principle_brand_id
								WHERE dd.depo_id = '$depo_id'
								and left(sku_satuan,3) not like 'krt'
								AND FORMAT(dd.delivery_order_draft_tgl_rencana_kirim, 'yyyy-MM-dd') <= '$tgl'
								AND dd.delivery_order_draft_status = 'Draft'
								AND tp.tipe_delivery_order_alias ='$tipe'
								GROUP BY 
									principle.principle_kode,
									brand.principle_brand_nama,
									s.client_wms_id,
									-- ss.depo_detail_id,
									-- ss.depo_detail_nama,
									ss.flag,
									s.sku_kode,
									s.sku_id,
									s.sku_nama_produk,
									ss.stok,
									s.sku_konversi_group,
									s.sku_kemasan,
									s.sku_satuan,
									s.sku_konversi_level,
									s.sku_konversi_faktor,
									d2d.type_item
									order by  principle.principle_kode ,
									brand.principle_brand_nama ,s.sku_kode")->result_array();
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
		$this->db->set("tipe_konversi_id", 'B5B99B77-86D2-48B8-964F-D4B91CDD9B0C');
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

	public function insert_tr_konversi_sku_detail($tr_konversi_sku_detail_id, $tr_konversi_sku_id, $data, $sku_id)
	{
		$sku_stock_id = $data["sku_stock_id"] == "" ? null : $data["sku_stock_id"];
		// $sku_id = $data["sku_id"] == "" ? null : $data["sku_id"];
		$sku_stock_expired_date = $data["sku_expdate"] == "" ? null : $data["sku_expdate"];
		$tr_konversi_sku_detail_qty_plan = $data["hasil"] == "" ? null : $data["hasil"];
		$sku_satuan_hasil = $data['sku_satuan_hasil'] == "" ? null : $data['sku_satuan_hasil'];

		$sku_stock_data = $this->db->select("*")->from("sku_stock")->where("sku_stock_id", $sku_stock_id)->get()->row();

		$this->db->set("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		$this->db->set("tr_konversi_sku_id", $tr_konversi_sku_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_id", $sku_stock_data->sku_id);
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

	public function GetListPembongkaran($principle, $flag, $arr_list_pembongkaran)
	{

		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND principle='$principle'";
		}

		if ($flag == "") {
			$flag = "";
		} else {
			$flag = "AND flag='$flag'";
		}


		if (count($arr_list_pembongkaran) > 0) {

			$union = " UNION ALL ";
			$table_sementara = "";


			foreach ($arr_list_pembongkaran as $key => $value) {

				$table_sementara .= "SELECT
											'" . $value['sku_id'] . "' AS sku_id,
											'" . $value['sku_nama_produk'] . "' AS sku_nama_produk,
											'" . $value['principle'] . "' AS principle,
											'" . $value['sku_kode'] . "' AS sku_kode,
											'" . $value['client_wms_id'] . "' AS client_wms_id,
											'" . $value['brand'] . "' AS brand,
											'" . $value['sku_kemasan'] . "' AS sku_kemasan,
											'" . $value['sku_satuan'] . "' AS sku_satuan,
											'" . $value['sku_qty_draft'] . "' AS sku_qty_draft,
											'" . $value['sku_stock'] . "' AS sku_stock,
											'" . $value['flag'] . "' AS flag,
											'" . $value['sku_konversi_group'] . "' AS sku_konversi_group,
											'" . $value['sku_konversi_level'] . "' AS sku_konversi_level,
											'" . $value['sku_konversi_faktor'] . "' AS sku_konversi_faktor";

				if ($key < count($arr_list_pembongkaran) - 1) {
					$table_sementara .= $union;
				}
			}

			$query = $this->db->query("SELECT * 
										FROM (" . $table_sementara . ") a
										WHERE sku_id IS NOT NULL
										" . $principle . "
										" . $flag . "
										ORDER BY flag, sku_kode ASC");

			if ($query->num_rows() == 0) {
				$query = array();
			} else {
				$query = $query->result_array();
			}
		} else {
			$query = array();
		}

		return $query;
	}
}
