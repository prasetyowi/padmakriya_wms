<?php

class M_KonversiPengemasanBarang extends CI_Model
{
	public function GetKonversiSKU()
	{
		$this->db->select("*")
			->from("tr_konversi_sku")
			// ->where('tr_konversi_sku_status !=', "Completed")
			->where_not_in('tr_konversi_sku_status', array("Completed", "Reject"))
			->order_by("tr_konversi_sku_kode");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getStatus()
	{
		$query = $this->db->query("SELECT DISTINCT tr_konversi_sku_status FROM tr_konversi_sku");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getKodeAutoComplete($value, $depo_detail_id_tujuan)
	{
		$this->db->select("SUBSTRING(p.pallet_kode, CHARINDEX('PAL/', p.pallet_kode), LEN(p.pallet_kode)) as kode, p.pallet_id, dp.depo_kode_preffix");
		$this->db->from("pallet as p");
		$this->db->join("rak_lajur_detail_pallet as ldp", "p.pallet_id = ldp.pallet_id", "left");
		$this->db->join("rak_lajur_detail as ld", "ldp.rak_lajur_detail_id = ld.rak_lajur_detail_id", "left");
		$this->db->join("rak", "ld.rak_id = rak.rak_id", "left");
		$this->db->join("depo as dp", "dp.depo_id = p.depo_id", "left");
		$this->db->where("p.depo_id", $this->session->userdata('depo_id'));
		if ($depo_detail_id_tujuan != null) {
			$this->db->where("rak.depo_detail_id", $depo_detail_id_tujuan);
		}
		$this->db->like("p.pallet_kode", $value);
		$query =  $this->db->get();


		return $query->result();
	}

	public function GetPerusahaan()
	{
		// $this->db->select("*")
		// 	->from("client_wms")
		// 	->order_by("client_wms_nama");
		// $query = $this->db->get();

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

	public function GetTipeKonversi()
	{
		$this->db->select("*")
			->from("tipe_konversi")
			->where("tipe_konversi_is_aktif", 1)
			->order_by("tipe_konversi_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetKonversiSKUByFilter($tgl1, $tgl2, $perusahaan, $status, $tipe)
	{

		if ($perusahaan == "") {
			$perusahaan = "";
		} else {
			$perusahaan = "AND tr_konversi_sku.client_wms_id = '" . $perusahaan . "' ";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = "AND tr_konversi_sku.tr_konversi_sku_status = '" . $status . "' ";
		}

		if ($tipe == "") {
			$tipe = "";
		} else {
			$tipe = "AND tr_konversi_sku.tipe_konversi_id = '" . $tipe . "' ";
		}
		$depo = $this->session->userdata("depo_id");

		$query = $this->db->query("SELECT
                                    tr_konversi_sku.tr_konversi_sku_id,
                                    tr_konversi_sku.tr_konversi_sku_kode,
                                    tr_konversi_sku.tipe_konversi_id,
                                    tipe_konversi.tipe_konversi_nama,
                                    tr_konversi_sku.client_wms_id,
                                    client_wms.client_wms_nama,
                                    FORMAT(tr_konversi_sku.tr_konversi_sku_tanggal, 'dd-MM-yyyy') AS tr_konversi_sku_tanggal,
                                    tr_konversi_sku.tr_konversi_sku_status
                                    FROM tr_konversi_sku
                                    LEFT JOIN client_wms
                                    ON client_wms.client_wms_id = tr_konversi_sku.client_wms_id
                                    LEFT JOIN tipe_konversi
                                    ON tipe_konversi.tipe_konversi_id = tr_konversi_sku.tipe_konversi_id
                                    WHERE FORMAT(tr_konversi_sku.tr_konversi_sku_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
                                    AND tr_konversi_sku.tr_konversi_sku_status NOT IN ('Rejected','In Progress Approval','Draft')
									AND tr_konversi_sku.tipe_konversi_id = 'D5CF6747-6C95-40E1-A64B-6A9057DD320B'
									--AND tr_konversi_sku.depo_id = '$depo'
									AND tr_konversi_sku.depo_detail_id in (select depo_detail_id from depo_detail where depo_id = '$depo')
                                    " . $perusahaan . "
                                    " . $status . "
                                    " . $tipe . "
                                    ORDER BY tr_konversi_sku.tr_konversi_sku_tgl_create DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiHeaderById($id)
	{
		$query = $this->db->query("SELECT
                                    konversi.tr_konversi_sku_id,
                                    konversi.client_wms_id,
                                    perusahaan.client_wms_nama,
                                    konversi.tipe_konversi_id,
                                    konversi.depo_detail_id,
                                    gudang.depo_detail_nama,
                                    tipe.tipe_konversi_nama,
                                    konversi.tr_konversi_sku_kode,
                                    FORMAT(konversi.tr_konversi_sku_tanggal, 'dd/MM/yyyy') AS tr_konversi_sku_tanggal,
                                    konversi.tr_konversi_sku_status,
                                    konversi.tr_konversi_sku_keterangan,
                                    konversi.tr_konversi_sku_tgl_create,
                                    konversi.tr_konversi_sku_who_create
                                    FROM tr_konversi_sku konversi
                                    left join client_wms perusahaan
                                    on konversi.client_wms_id = perusahaan.client_wms_id
                                    left join depo_detail gudang
                                    on konversi.depo_detail_id = gudang.depo_detail_id
                                    left join tipe_konversi tipe
                                    on konversi.tipe_konversi_id = tipe.tipe_konversi_id
                                    WHERE tr_konversi_sku_id = '$id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiDetailById($id)
	{
		$query = $this->db->query("SELECT
                                    detail.tr_konversi_sku_detail_id,
                                    detail.tr_konversi_sku_id,
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.principle_id,
                                    principle.principle_kode AS principle,
                                    sku.principle_brand_id,
                                    principle_brand.principle_brand_nama AS brand,
                                    sku.sku_kemasan,
                                    sku.sku_satuan,
                                    detail.sku_stock_id,
                                    FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
                                    ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0) AS tr_konversi_sku_detail_qty_plan,
                                    SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty_result, 0)) AS tr_konversi_sku_detail2_qty_result
                                    FROM tr_konversi_sku_detail detail
                                    LEFT JOIN tr_konversi_sku_detail2 detail2
                                    ON detail.tr_konversi_sku_detail_id = detail2.tr_konversi_sku_detail_id
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail.sku_id
                                    LEFT JOIN principle
                                    ON principle.principle_id = sku.principle_id
                                    LEFT JOIN principle_brand
                                    ON principle_brand.principle_brand_id = sku.principle_brand_id
                                    WHERE detail.tr_konversi_sku_id = '$id'
                                    GROUP BY detail.tr_konversi_sku_detail_id,
                                            detail.tr_konversi_sku_id,
                                            sku.sku_id,
                                            sku.sku_kode,
                                            sku.sku_nama_produk,
                                            sku.principle_id,
                                            principle.principle_kode,
                                            sku.principle_brand_id,
                                            principle_brand.principle_brand_nama,
                                            sku.sku_kemasan,
                                            sku.sku_satuan,
                                            detail.sku_stock_id,
                                            FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd'),
                                            ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0)
                                    ORDER BY sku.sku_kode, FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') ASC");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiDetailWithPalletById($id, $arr_konversi_sku_pallet_asal)
	{

		if (count($arr_konversi_sku_pallet_asal) > 0) {

			$union = " UNION ALL ";
			$table_sementara = "";


			foreach ($arr_konversi_sku_pallet_asal as $key => $value) {
				$sku_stock_qty_pallet = $value['sku_stock_qty_pallet'] != "" || $value['sku_stock_qty_pallet'] !== NULL ? $value['sku_stock_qty_pallet'] : 0;
				$sku_stock_qty = $value['sku_stock_qty'] != "" || $value['sku_stock_qty'] !== NULL ? $value['sku_stock_qty'] : 0;

				$table_sementara .= "SELECT '" . $value['idx'] . "' AS idx, '" . $value['tr_konversi_sku_detail_id'] . "' AS tr_konversi_sku_detail_id,'" . $value['tr_konversi_sku_id'] . "' AS tr_konversi_sku_id, '" . $value['pallet_id_asal'] . "' AS pallet_id_asal, '" . $value['sku_stock_id'] . "' AS sku_stock_id, " . $sku_stock_qty_pallet . " AS sku_stock_qty_pallet, " . $sku_stock_qty . " AS sku_stock_qty";

				if ($key < count($arr_konversi_sku_pallet_asal) - 1) {
					$table_sementara .= $union;
				}
			}

			$query = $this->db->query("SELECT
									detail.tr_konversi_sku_detail_id,
									detail.tr_konversi_sku_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.principle_id,
									principle.principle_kode AS principle,
									sku.principle_brand_id,
									principle_brand.principle_brand_nama AS brand,
									sku.sku_kemasan,
									sku.sku_satuan,
									detail.sku_stock_id,
									detail_tmp.tr_konversi_sku_detail2_qty AS qty_aktual,
									FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
									ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0) AS tr_konversi_sku_detail_qty_plan,
									SUM(ISNULL(pallet_asal.sku_stock_qty, 0)) AS sku_stock_qty_pallet_ambil,
									SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty_result, 0)) AS tr_konversi_sku_detail2_qty_result,
									SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty, 0)) AS tr_konversi_sku_detail2_qty
									FROM tr_konversi_sku_detail detail
									LEFT JOIN tr_konversi_sku_detail2_temp detail_tmp
									ON detail.tr_konversi_sku_detail_id = detail_tmp.tr_konversi_sku_detail_id
									LEFT JOIN tr_konversi_sku_detail2 detail2
									ON detail.tr_konversi_sku_detail_id = detail2.tr_konversi_sku_detail_id
									LEFT JOIN (" . $table_sementara . ") pallet_asal
									ON CONVERT(nvarchar(36),detail.tr_konversi_sku_detail_id) = CONVERT(nvarchar(36),pallet_asal.tr_konversi_sku_detail_id)
									LEFT JOIN sku
									ON sku.sku_id = detail.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									WHERE detail.tr_konversi_sku_id = '$id'
									GROUP BY detail.tr_konversi_sku_detail_id,
											detail.tr_konversi_sku_id,
											sku.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.principle_id,
											principle.principle_kode,
											sku.principle_brand_id,
											principle_brand.principle_brand_nama,
											sku.sku_kemasan,
											sku.sku_satuan,
											detail.sku_stock_id,
											detail_tmp.tr_konversi_sku_detail2_qty,
											FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd'),
											ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0)
									ORDER BY sku.sku_kode, FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') ASC");

			if ($query->num_rows() == 0) {
				$query = [];
			} else {
				$query = $query->result();
			}
		} else {
			$query = $this->db->query("SELECT
									detail.tr_konversi_sku_detail_id,
									detail.tr_konversi_sku_id,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.principle_id,
									principle.principle_kode AS principle,
									sku.principle_brand_id,
									principle_brand.principle_brand_nama AS brand,
									sku.sku_kemasan,
									sku.sku_satuan,
									detail.sku_stock_id,
									detail_tmp.tr_konversi_sku_detail2_qty AS qty_aktual,
									FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
									ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0) AS tr_konversi_sku_detail_qty_plan,
									0 AS sku_stock_qty_pallet_ambil,
									SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty_result, 0)) AS tr_konversi_sku_detail2_qty_result,
									SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty, 0)) AS tr_konversi_sku_detail2_qty
									FROM tr_konversi_sku_detail detail
									LEFT JOIN tr_konversi_sku_detail2_temp detail_tmp
									ON detail.tr_konversi_sku_detail_id = detail_tmp.tr_konversi_sku_detail_id
									LEFT JOIN tr_konversi_sku_detail2 detail2
									ON detail.tr_konversi_sku_detail_id = detail2.tr_konversi_sku_detail_id
									LEFT JOIN sku
									ON sku.sku_id = detail.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									WHERE detail.tr_konversi_sku_id = '$id'
									GROUP BY detail.tr_konversi_sku_detail_id,
											detail.tr_konversi_sku_id,
											sku.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.principle_id,
											principle.principle_kode,
											sku.principle_brand_id,
											principle_brand.principle_brand_nama,
											sku.sku_kemasan,
											sku.sku_satuan,
											detail.sku_stock_id,
											detail_tmp.tr_konversi_sku_detail2_qty,
											FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd'),
											ISNULL(pallet_asal.sku_stock_qty, 0),
											ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0)
									ORDER BY sku.sku_kode, FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') ASC");

			if ($query->num_rows() == 0) {
				$query = [];
			} else {
				$query = $query->result();
			}
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiEditHeaderById($id)
	{
		$query = $this->db->query("SELECT
                                    konversi.tr_konversi_sku_id,
                                    konversi.client_wms_id,
                                    perusahaan.client_wms_nama,
                                    konversi.tipe_konversi_id,
                                    konversi.depo_detail_id,
                                    konversi.depo_detail_id_tujuan,
                                    gudang.depo_detail_nama,
                                    gudang_tujuan.depo_detail_nama as depo_detail_nama_tujuan,
                                    tipe.tipe_konversi_nama,
                                    konversi.tr_konversi_sku_kode,
                                    FORMAT(konversi.tr_konversi_sku_tanggal, 'dd/MM/yyyy') AS tr_konversi_sku_tanggal,
                                    konversi.tr_konversi_sku_status,
                                    konversi.tr_konversi_sku_keterangan,
                                    konversi.tr_konversi_sku_tgl_create,
                                    konversi.tr_konversi_sku_tgl_update,
                                    konversi.tr_konversi_sku_who_create,
                                    konversi.tr_konversi_sku_who_update
                                    FROM tr_konversi_sku konversi
                                    left join client_wms perusahaan
                                    on konversi.client_wms_id = perusahaan.client_wms_id
                                    left join depo_detail gudang
                                    on konversi.depo_detail_id = gudang.depo_detail_id
                                    left join depo_detail gudang_tujuan
                                    on konversi.depo_detail_id_tujuan = gudang_tujuan.depo_detail_id
                                    left join tipe_konversi tipe
                                    on konversi.tipe_konversi_id = tipe.tipe_konversi_id
                                    WHERE tr_konversi_sku_id = '$id' ");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiEditDetailById($id)
	{
		$query = $this->db->query("SELECT
                                    detail.tr_konversi_sku_detail_id,
                                    detail.tr_konversi_sku_id,
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.principle_id,
                                    principle.principle_kode AS principle,
                                    sku.principle_brand_id,
                                    principle_brand.principle_brand_nama AS brand,
                                    sku.sku_kemasan,
                                    sku.sku_satuan,
                                    detail.sku_stock_id,
                                    detail_tmp.tr_konversi_sku_detail2_qty AS qty_aktual,
                                    FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
                                    ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0) AS tr_konversi_sku_detail_qty_plan,
                                    SUM(ISNULL(pallet_asal.sku_stock_qty, 0)) AS sku_stock_qty_pallet_ambil,
                                    SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty_result, 0)) AS tr_konversi_sku_detail2_qty_result,
                                    SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty, 0)) AS tr_konversi_sku_detail2_qty
                                    FROM tr_konversi_sku_detail detail
                                    LEFT JOIN tr_konversi_sku_detail2_temp detail_tmp
                                    ON detail.tr_konversi_sku_detail_id = detail_tmp.tr_konversi_sku_detail_id
                                    LEFT JOIN tr_konversi_sku_detail2 detail2
                                    ON detail.tr_konversi_sku_detail_id = detail2.tr_konversi_sku_detail_id
									LEFT JOIN tr_konversi_sku_pallet_asal pallet_asal
                                    ON detail.tr_konversi_sku_detail_id = pallet_asal.tr_konversi_sku_detail_id
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail.sku_id
                                    LEFT JOIN principle
                                    ON principle.principle_id = sku.principle_id
                                    LEFT JOIN principle_brand
                                    ON principle_brand.principle_brand_id = sku.principle_brand_id
                                    WHERE detail.tr_konversi_sku_id = '$id'
                                    GROUP BY detail.tr_konversi_sku_detail_id,
                                            detail.tr_konversi_sku_id,
                                            sku.sku_id,
                                            sku.sku_kode,
                                            sku.sku_nama_produk,
                                            sku.principle_id,
                                            principle.principle_kode,
                                            sku.principle_brand_id,
                                            principle_brand.principle_brand_nama,
                                            sku.sku_kemasan,
                                            sku.sku_satuan,
                                            detail.sku_stock_id,
                                            detail_tmp.tr_konversi_sku_detail2_qty,
                                            FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd'),
                                            ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0)
                                    ORDER BY sku.sku_kode, FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') ASC");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiEditDetailById_detail($id)
	{
		$query = $this->db->query("SELECT
                                    detail.tr_konversi_sku_detail_id,
                                    detail.tr_konversi_sku_id,
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.principle_id,
                                    principle.principle_kode AS principle,
                                    sku.principle_brand_id,
                                    principle_brand.principle_brand_nama AS brand,
                                    sku.sku_kemasan,
                                    sku.sku_satuan,
                                    detail.sku_stock_id,
                                    detail_tmp.tr_konversi_sku_detail2_qty AS qty_aktual,
                                    FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
                                    ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0) AS tr_konversi_sku_detail_qty_plan,
                                    ISNULL(pallet_asal.sku_stock_qty, 0) AS sku_stock_qty_pallet_ambil,
                                    SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty_result, 0)) AS tr_konversi_sku_detail2_qty_result,
                                    SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty, 0)) AS tr_konversi_sku_detail2_qty
                                    FROM tr_konversi_sku_detail detail
                                    LEFT JOIN tr_konversi_sku_detail2_temp detail_tmp
                                    ON detail.tr_konversi_sku_detail_id = detail_tmp.tr_konversi_sku_detail_id
                                    LEFT JOIN tr_konversi_sku_detail2 detail2
                                    ON detail.tr_konversi_sku_detail_id = detail2.tr_konversi_sku_detail_id
									LEFT JOIN tr_konversi_sku_pallet_asal pallet_asal
                                    ON detail.tr_konversi_sku_detail_id = pallet_asal.tr_konversi_sku_detail_id
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail.sku_id
                                    LEFT JOIN principle
                                    ON principle.principle_id = sku.principle_id
                                    LEFT JOIN principle_brand
                                    ON principle_brand.principle_brand_id = sku.principle_brand_id
                                    WHERE detail.tr_konversi_sku_id = '$id'
                                    GROUP BY detail.tr_konversi_sku_detail_id,
                                            detail.tr_konversi_sku_id,
                                            sku.sku_id,
                                            sku.sku_kode,
                                            sku.sku_nama_produk,
                                            sku.principle_id,
                                            principle.principle_kode,
                                            sku.principle_brand_id,
                                            principle_brand.principle_brand_nama,
                                            sku.sku_kemasan,
                                            sku.sku_satuan,
                                            detail.sku_stock_id,
                                            detail_tmp.tr_konversi_sku_detail2_qty,
                                            FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd'),
                                            ISNULL(pallet_asal.sku_stock_qty, 0),
                                            ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0)
                                    ORDER BY sku.sku_kode, FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') ASC");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiEditDetail2ById($id)
	{
		$query = $this->db->query("SELECT
                                    detail2.*,
                                    detail.sku_stock_expired_date
                                    FROM tr_konversi_sku_detail detail
                                    LEFT JOIN tr_konversi_sku_detail2 detail2
                                    ON detail.tr_konversi_sku_detail_id = detail2.tr_konversi_sku_detail_id
                                    WHERE detail.tr_konversi_sku_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function sumDetailById($id)
	{
		$query = $this->db->query("SELECT SUM(tr_konversi_sku_detail_qty_plan) as total1, tr_konversi_sku_detail_id
                                    FROM tr_konversi_sku_detail
                                    WHERE tr_konversi_sku_detail_id = '$id'
                                    GROUP BY tr_konversi_sku_detail_id");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function sumDetail2ById($id)
	{
		$query = $this->db->query("SELECT SUM(ISNULL(tr_konversi_sku_detail2_qty, 0)) as total2, tr_konversi_sku_detail_id
                                    FROM tr_konversi_sku_detail2
                                    WHERE tr_konversi_sku_detail_id = '$id'
                                    GROUP BY tr_konversi_sku_detail_id");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetKonversiDetailPallet($id, $sku_id, $sku_stock_expired_date, $sku_stock_id)
	{
		$query = $this->db->query("SELECT DISTINCT
                                    rak.depo_detail_id,
                                    depo_detail.depo_detail_nama,
                                    rak.rak_id,
                                    rak.rak_nama,
                                    rak_lajur.rak_lajur_id,
                                    rak_lajur.rak_lajur_nama,
                                    rak_lajur_detail.rak_lajur_detail_id,
                                    rak_lajur_detail.rak_lajur_detail_nama,
                                    pallet.pallet_id,
                                    pallet.pallet_kode,
                                    CASE 
                                        WHEN pallet.pallet_is_lock = '1' THEN 'LOCKED'
                                        ELSE '' 
                                    END pallet_is_lock,
                                    pallet_detail.sku_stock_qty,
                                    pallet_detail.sku_stock_ambil,
                                    ISNULL(temp.tr_konversi_sku_detail2_qty, 0) AS tr_konversi_sku_detail2_qty
                                    FROM tr_konversi_sku_detail detail
                                    LEFT JOIN sku_stock
                                    ON detail.sku_id = sku_stock.sku_id
                                    AND detail.sku_stock_expired_date = sku_stock.sku_stock_expired_date
                                    LEFT JOIN (SELECT
                                    pallet_id,
                                    sku_id,
                                    sku_stock_expired_date,
                                    SUM(ISNULL(sku_stock_qty,0) - ISNULL(sku_stock_ambil,0) + ISNULL(sku_stock_in,0) - ISNULL(sku_stock_out,0) + ISNULL(sku_stock_terima,0)) AS sku_stock_qty,
                                    SUM(sku_stock_ambil) AS sku_stock_ambil
                                    FROM pallet_detail
                                    WHERE sku_stock_id = '$sku_stock_id'
                                    GROUP BY pallet_id,
                                            sku_id,
                                            sku_stock_expired_date) pallet_detail
                                    ON detail.sku_id = pallet_detail.sku_id
                                    AND detail.sku_stock_expired_date = pallet_detail.sku_stock_expired_date
                                    LEFT JOIN pallet
                                    ON pallet.pallet_id = pallet_detail.pallet_id
                                    LEFT JOIN rak_lajur_detail_pallet
                                    ON rak_lajur_detail_pallet.pallet_id = pallet.pallet_id
                                    LEFT JOIN rak_lajur_detail
                                    ON rak_lajur_detail.rak_lajur_detail_id = rak_lajur_detail_pallet.rak_lajur_detail_id
                                    LEFT JOIN rak_lajur
                                    ON rak_lajur.rak_lajur_id = rak_lajur_detail.rak_lajur_id
                                    LEFT JOIN rak
                                    ON rak.rak_id = rak_lajur.rak_id
                                    LEFT JOIN depo_detail
                                    ON depo_detail.depo_detail_id = rak.depo_detail_id
                                    LEFT JOIN tr_konversi_sku_detail2_temp temp
                                    ON temp.tr_konversi_sku_detail_id = detail.tr_konversi_sku_detail_id
                                    AND temp.pallet_id_asal = pallet.pallet_id
                                    WHERE sku_stock.sku_id = '$sku_id'
                                    AND FORMAT(sku_stock.sku_stock_expired_date, 'yyyy-MM-dd') = '$sku_stock_expired_date'
                                    AND sku_stock.sku_stock_id = '$sku_stock_id'
                                    AND detail.tr_konversi_sku_detail_id = '$id'
                                    AND pallet.pallet_id IS NOT NULL
                                    ORDER BY depo_detail.depo_detail_nama, rak_lajur.rak_lajur_nama, rak_lajur_detail.rak_lajur_detail_nama, pallet.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetKonversiDetailPallet2($tr_konversi_sku_detail_id)
	{
		$query = $this->db->query("SELECT
                                    detail2.tr_konversi_sku_detail_id,
                                    detail2.tr_konversi_sku_detail2_id,
                                    detail2.sku_stock_id,
                                    detail2.sku_id,
                                    rak.depo_detail_id,
                                    depo_detail.depo_detail_nama,
                                    rak.rak_id,
                                    rak.rak_nama,
                                    rak_lajur.rak_lajur_id,
                                    rak_lajur.rak_lajur_nama,
                                    rak_lajur_detail.rak_lajur_detail_id,
                                    rak_lajur_detail.rak_lajur_detail_nama,
                                    pallet.pallet_id,
                                    pallet.pallet_kode,
                                    (ISNULL(pallet_detail.sku_stock_qty,0) - ISNULL(pallet_detail.sku_stock_ambil,0) + ISNULL(pallet_detail.sku_stock_in,0) - ISNULL(pallet_detail.sku_stock_out,0) + ISNULL(pallet_detail.sku_stock_terima,0)) AS sku_stock_qty,
                                    pallet_detail.sku_stock_ambil,
                                    ISNULL(detail2.tr_konversi_sku_detail2_qty, 0) AS tr_konversi_sku_detail2_qty
                                    FROM tr_konversi_sku_detail2 detail2
                                    LEFT JOIN pallet_detail
                                    ON detail2.pallet_id_asal = pallet_detail.pallet_id
                                    AND detail2.sku_stock_id = pallet_detail.sku_stock_id
                                    LEFT JOIN pallet
                                    ON pallet.pallet_id = pallet_detail.pallet_id
                                    LEFT JOIN rak_lajur_detail_pallet
                                    ON rak_lajur_detail_pallet.pallet_id = pallet.pallet_id
                                    LEFT JOIN rak_lajur_detail
                                    ON rak_lajur_detail.rak_lajur_detail_id = rak_lajur_detail_pallet.rak_lajur_detail_id
                                    LEFT JOIN rak_lajur
                                    ON rak_lajur.rak_lajur_id = rak_lajur_detail.rak_lajur_id
                                    LEFT JOIN rak
                                    ON rak.rak_id = rak_lajur.rak_id
                                    LEFT JOIN depo_detail
                                    ON depo_detail.depo_detail_id = rak.depo_detail_id
                                    WHERE detail2.tr_konversi_sku_detail_id = '$tr_konversi_sku_detail_id'
                                    ORDER BY sku_stock_qty, depo_detail.depo_detail_nama, rak_lajur.rak_lajur_nama, rak_lajur_detail.rak_lajur_detail_nama, pallet.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function Get_Kode($vrbl_param)
	{
		$this->db->select("*")
			->from("tipe_konversi")
			->where("tipe_konversi_id", $vrbl_param);
		$query = $this->db->get();

		return $query->row();
	}

	public function GetSelectedSKU($sku_id, $sku_stock_expired_date)
	{
		if ($sku_id != "") {
			$sku_id = implode(",", $sku_id);
		} else {
			$sku_id = "NULL";
		}

		if ($sku_stock_expired_date != "") {
			$sku_stock_expired_date = implode(",", $sku_stock_expired_date);
		} else {
			$sku_stock_expired_date = "NULL";
		}

		$query = $this->db->query("SELECT
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.principle_id,
                                    principle.principle_kode AS principle,
                                    sku.principle_brand_id,
                                    principle_brand.principle_brand_nama AS brand,
                                    sku.sku_kemasan,
                                    sku.sku_satuan,
                                    sku.sku_induk_id,
                                    sku_induk.sku_induk_nama AS sku_induk,
                                    sku.sku_konversi_group,
                                    sku.sku_konversi_level,
                                    ISNULL(sku.sku_konversi_faktor, 0) AS sku_konversi_faktor,
                                    sku_stock.sku_stock_expired_date,
                                    FORMAT(sku_stock.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date2,
                                    ISNULL(temp.tr_konversi_sku_detail2_qty,0) AS tr_konversi_sku_detail2_qty
                                    FROM sku_stock
                                    INNER JOIN sku
                                    ON sku.sku_id = sku_stock.sku_id
                                    LEFT JOIN (SELECT tr_konversi_sku_detail_id AS sku_id,sku_stock_expired_date,SUM(tr_konversi_sku_detail2_qty) AS tr_konversi_sku_detail2_qty FROM tr_konversi_sku_detail2_temp GROUP BY tr_konversi_sku_detail_id,sku_stock_expired_date) temp
                                    ON temp.sku_id = sku_stock.sku_id
                                    AND temp.sku_stock_expired_date = sku_stock.sku_stock_expired_date
                                    LEFT JOIN sku_induk
                                    ON sku.sku_induk_id = sku_induk.sku_induk_id
                                    LEFT JOIN principle
                                    ON principle.principle_id = sku.principle_id
                                    LEFT JOIN principle_brand
                                    ON principle_brand.principle_brand_id = sku.principle_brand_id
                                    WHERE sku_stock.sku_id IN (" . $sku_id . ")
                                    AND sku_stock.sku_stock_expired_date IN (" . $sku_stock_expired_date . ")
                                    GROUP BY sku.sku_id,
                                            sku.sku_kode,
                                            sku.sku_nama_produk,
                                            sku.principle_id,
                                            principle.principle_kode,
                                            sku.principle_brand_id,
                                            principle_brand.principle_brand_nama,
                                            sku.sku_kemasan,
                                            sku.sku_satuan,
                                            sku.sku_induk_id,
                                            sku_induk.sku_induk_nama,
                                            sku.sku_konversi_group,
                                            sku.sku_konversi_level,
                                            ISNULL(sku.sku_konversi_faktor, 0),
                                            sku_stock.sku_stock_expired_date,
                                            FORMAT(sku_stock.sku_stock_expired_date, 'yyyy-MM-dd'),
                                            ISNULL(temp.tr_konversi_sku_detail2_qty,0)
                                    ORDER BY principle.principle_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetSKUKonversiBySKU($sku_id, $sku_stock_expired_date, $satuan, $tipe_konversi)
	{
		if ($tipe_konversi == "Unpack") {
			$tipe_konversi = "AND sku_konversi.sku_konversi_level < sku.sku_konversi_level ";
		} else if ($tipe_konversi == "Repack") {
			$tipe_konversi = "AND sku_konversi.sku_konversi_level > sku.sku_konversi_level ";
		}

		$query = $this->db->query("SELECT
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.sku_kemasan AS sku_kemasan_param,
                                    sku.sku_satuan AS sku_satuan_param,
                                    sku.sku_konversi_level AS sku_konversi_level_param,
                                    ISNULL(sku.sku_konversi_faktor, 0) AS sku_konversi_faktor_param,
                                    sku.sku_induk_id,
                                    sku_stock.sku_stock_expired_date,
                                    sku_konversi.sku_id AS sku_id_konversi,
                                    sku_konversi.sku_kemasan,
                                    sku_konversi.sku_satuan,
                                    sku.sku_konversi_group,
                                    sku_konversi.sku_konversi_level,
                                    ISNULL(sku_konversi.sku_konversi_faktor, 0) AS sku_konversi_faktor,
                                    CASE
                                        WHEN sku.sku_konversi_level > sku_konversi.sku_konversi_level THEN '*'
                                        WHEN sku.sku_konversi_level = sku_konversi.sku_konversi_level THEN '='
                                        ELSE '/'
                                    END konversi_operator,
                                    ISNULL(temp.tr_konversi_sku_detail2_qty,0) AS tr_konversi_sku_detail2_qty,
                                    ISNULL(temp.tr_konversi_sku_detail2_qty_result,0) AS tr_konversi_sku_detail2_qty_result
                                    FROM sku_stock
                                    LEFT JOIN sku
                                    ON sku.sku_id = sku_stock.sku_id
                                    LEFT JOIN sku sku_konversi
                                    ON sku.sku_konversi_group = sku_konversi.sku_konversi_group
                                    AND sku_konversi.sku_satuan <> '$satuan'
                                    LEFT JOIN tr_konversi_sku_detail2_temp temp
                                    ON temp.sku_id = sku_konversi.sku_id
                                    AND temp.sku_stock_expired_date = sku_stock.sku_stock_expired_date
                                    WHERE sku_stock.sku_id = '$sku_id'
                                    AND sku_stock.sku_stock_expired_date = '$sku_stock_expired_date'
                                    " . $tipe_konversi . "
                                    GROUP BY sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.sku_kemasan,
                                    sku.sku_satuan,
                                    sku.sku_konversi_level,
                                    ISNULL(sku.sku_konversi_faktor, 0),
                                    sku.sku_induk_id,
                                    sku_stock.sku_stock_expired_date,
                                    sku_konversi.sku_id,
                                    sku_konversi.sku_kemasan,
                                    sku_konversi.sku_satuan,
                                    sku.sku_konversi_group,
                                    sku_konversi.sku_konversi_level,
                                    ISNULL(sku_konversi.sku_konversi_faktor, 0),
                                    CASE
                                        WHEN sku.sku_konversi_level > sku_konversi.sku_konversi_level THEN '*'
                                        WHEN sku.sku_konversi_level = sku_konversi.sku_konversi_level THEN '='
                                        ELSE '/'
                                    END,
                                    ISNULL(temp.tr_konversi_sku_detail2_qty,0),
                                    ISNULL(temp.tr_konversi_sku_detail2_qty_result,0)
                                    ORDER BY sku_konversi.sku_konversi_level DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetSKUKonversiBySKU2($tr_konversi_sku_detail_id)
	{

		$query = $this->db->query("SELECT
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.sku_kemasan,
                                    sku.sku_satuan,
                                    sku.sku_konversi_level,
                                    ISNULL(sku.sku_konversi_faktor, 0) AS sku_konversi_faktor,
                                    sku.sku_induk_id,
                                    ISNULL(detail2.tr_konversi_sku_detail2_qty, 0) AS tr_konversi_sku_detail2_qty,
                                    ISNULL(detail2.tr_konversi_sku_detail2_qty_result, 0) AS tr_konversi_sku_detail2_qty_result
                                    FROM tr_konversi_sku_detail2 detail2
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail2.sku_id
                                    WHERE detail2.tr_konversi_sku_detail_id = '$tr_konversi_sku_detail_id'
                                    ORDER BY sku.sku_kode,sku.sku_konversi_level ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function search_filter_chosen_sku($perusahaan, $principle, $sku_kode, $sku_nama_produk, $sku_satuan)
	{
		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND sku.principle_id = '$principle' ";
		}

		if ($sku_kode == "") {
			$sku_kode = "";
		} else {
			$sku_kode = "AND sku.sku_kode LIKE '%" . $sku_kode . "%' ";
		}

		if ($sku_nama_produk == "") {
			$sku_nama_produk = "";
		} else {
			$sku_nama_produk = "AND sku.sku_nama_produk LIKE '%" . $sku_nama_produk . "%' ";
		}

		if ($sku_satuan == "") {
			$sku_satuan = "";
		} else {
			$sku_satuan = "AND sku.sku_satuan LIKE '%" . $sku_satuan . "%' ";
		}

		$query = $this->db->query("SELECT
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.principle_id,
                                    principle.principle_kode AS principle,
                                    sku.principle_brand_id,
                                    principle_brand.principle_brand_nama AS brand,
                                    sku.sku_kemasan,
                                    sku.sku_satuan,
                                    sku.sku_induk_id,
                                    sku_induk.sku_induk_nama AS sku_induk,
                                    sku_stock.sku_stock_id,
                                    sku_stock.sku_stock_expired_date,
                                    FORMAT(sku_stock.sku_stock_expired_date,'yyyy-MM-dd') AS sku_stock_expired_date2
                                    FROM sku_stock
                                    INNER JOIN sku
                                    ON sku.sku_id = sku_stock.sku_id
                                    LEFT JOIN sku_induk
                                    ON sku.sku_induk_id = sku_induk.sku_induk_id
                                    LEFT JOIN principle
                                    ON principle.principle_id = sku.principle_id
                                    LEFT JOIN principle_brand
                                    ON principle_brand.principle_brand_id = sku.principle_brand_id
                                    WHERE sku_stock.client_wms_id = '$perusahaan'
                                    " . $principle . "
                                    " . $sku_kode . "
                                    " . $sku_nama_produk . "
                                    " . $sku_satuan . "
                                    GROUP BY sku.sku_id,
                                            sku.sku_kode,
                                            sku.sku_nama_produk,
                                            sku.principle_id,
                                            principle.principle_kode,
                                            sku.principle_brand_id,
                                            principle_brand.principle_brand_nama,
                                            sku.sku_kemasan,
                                            sku.sku_satuan,
                                            sku.sku_induk_id,
                                            sku_induk.sku_induk_nama,
                                            sku_stock.sku_stock_id,
                                            sku_stock.sku_stock_expired_date,
                                            FORMAT(sku_stock.sku_stock_expired_date,'yyyy-MM-dd')
                                    ORDER BY principle.principle_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function insert_tr_konversi_sku_detail2_temp($data)
	{
		// $sku_stock_id = $data["sku_stock_id"] == "" ? null : $data["sku_stock_id"];
		$tr_konversi_sku_detail_id = $data["tr_konversi_sku_detail_id"] == "" ? null : $data["tr_konversi_sku_detail_id"];
		$sku_id = $data["sku_id"] == "" ? null : $data["sku_id"];
		$sku_stock_expired_date = $data["sku_stock_expired_date"] == "" ? null : $data["sku_stock_expired_date"];
		$tr_konversi_sku_detail2_qty_result = $data["tr_konversi_sku_detail2_qty_result"] == "" ? null : $data["tr_konversi_sku_detail2_qty_result"];
		$tr_konversi_sku_detail2_qty = $data["tr_konversi_sku_detail2_qty"] == "" ? null : $data["tr_konversi_sku_detail2_qty"];
		$pallet_id_asal = $data["pallet_id_asal"] == "" ? null : $data["pallet_id_asal"];
		$pallet_id_tujuan = $data["pallet_id_tujuan"] == "" ? null : $data["pallet_id_tujuan"];

		$this->db->set("tr_konversi_sku_detail2_id", "NEWID()", FALSE);
		$this->db->set("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		// $this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
		$this->db->set("tr_konversi_sku_detail2_qty", $tr_konversi_sku_detail2_qty);
		$this->db->set("tr_konversi_sku_detail2_qty_result", $tr_konversi_sku_detail2_qty_result);
		$this->db->set("pallet_id_asal", $pallet_id_asal);
		$this->db->set("pallet_id_tujuan", $pallet_id_tujuan);

		$queryinsert = $this->db->insert("tr_konversi_sku_detail2_temp");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create)
	{
		$tr_konversi_sku_id = $tr_konversi_sku_id == '' ? null : $tr_konversi_sku_id;
		$client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
		$depo_detail_id = $depo_detail_id == '' ? null : $depo_detail_id;
		$tipe_konversi_id = $tipe_konversi_id == '' ? null : $tipe_konversi_id;
		$tr_konversi_sku_kode = $tr_konversi_sku_kode == '' ? null : $tr_konversi_sku_kode;
		$tr_konversi_sku_tanggal = $tr_konversi_sku_tanggal == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $tr_konversi_sku_tanggal)));
		$tr_konversi_sku_status = $tr_konversi_sku_status == '' ? null : $tr_konversi_sku_status;
		$tr_konversi_sku_keterangan = $tr_konversi_sku_keterangan == '' ? null : $tr_konversi_sku_keterangan;
		$tr_konversi_sku_tgl_create = $tr_konversi_sku_tgl_create == '' ? null : $tr_konversi_sku_tgl_create;
		$tr_konversi_sku_who_create = $tr_konversi_sku_who_create == '' ? null : $tr_konversi_sku_who_create;

		$this->db->set("tr_konversi_sku_id", $tr_konversi_sku_id);
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("tipe_konversi_id", $tipe_konversi_id);
		$this->db->set("tr_konversi_sku_kode", $tr_konversi_sku_kode);
		$this->db->set("tr_konversi_sku_tanggal", $tr_konversi_sku_tanggal);
		$this->db->set("tr_konversi_sku_status", $tr_konversi_sku_status);
		$this->db->set("tr_konversi_sku_keterangan", $tr_konversi_sku_keterangan);
		$this->db->set("tr_konversi_sku_tgl_create", "GETDATE()", FALSE);
		$this->db->set("tr_konversi_sku_who_create", $tr_konversi_sku_who_create);

		$queryinsert = $this->db->insert("tr_konversi_sku");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function update_tr_konversi_sku($tr_konversi_sku_id, $client_wms_id, $depo_detail_id, $tipe_konversi_id, $tr_konversi_sku_kode, $tr_konversi_sku_tanggal, $tr_konversi_sku_status, $tr_konversi_sku_keterangan, $tr_konversi_sku_tgl_create, $tr_konversi_sku_who_create)
	{
		$tr_konversi_sku_id = $tr_konversi_sku_id == '' ? null : $tr_konversi_sku_id;
		$client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
		$depo_detail_id = $depo_detail_id == '' ? null : $depo_detail_id;
		$tipe_konversi_id = $tipe_konversi_id == '' ? null : $tipe_konversi_id;
		$tr_konversi_sku_kode = $tr_konversi_sku_kode == '' ? null : $tr_konversi_sku_kode;
		$tr_konversi_sku_tanggal = $tr_konversi_sku_tanggal == '' ? null : date('Y-m-d', strtotime(str_replace("/", "-", $tr_konversi_sku_tanggal)));
		$tr_konversi_sku_status = $tr_konversi_sku_status == '' ? null : $tr_konversi_sku_status;
		$tr_konversi_sku_keterangan = $tr_konversi_sku_keterangan == '' ? null : $tr_konversi_sku_keterangan;
		$tr_konversi_sku_tgl_create = $tr_konversi_sku_tgl_create == '' ? null : $tr_konversi_sku_tgl_create;
		$tr_konversi_sku_who_create = $tr_konversi_sku_who_create == '' ? null : $tr_konversi_sku_who_create;

		// $this->db->set("tr_konversi_sku_id", $tr_konversi_sku_id);
		// $this->db->set("client_wms_id", $client_wms_id);
		// $this->db->set("tipe_konversi_id", $tipe_konversi_id);
		// $this->db->set("tr_konversi_sku_kode", $tr_konversi_sku_kode);
		// $this->db->set("tr_konversi_sku_tanggal", $tr_konversi_sku_tanggal);
		$this->db->set("tr_konversi_sku_status", $tr_konversi_sku_status);
		$this->db->set("tr_konversi_sku_keterangan", $tr_konversi_sku_keterangan);
		// $this->db->set("tr_konversi_sku_tgl_create", "GETDATE()", FALSE);
		// $this->db->set("tr_konversi_sku_who_create", $tr_konversi_sku_who_create);

		$this->db->where("tr_konversi_sku_id", $tr_konversi_sku_id);
		$queryinsert = $this->db->update("tr_konversi_sku");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_tr_konversi_sku_detail($tr_konversi_sku_detail_id, $tr_konversi_sku_id, $data)
	{
		// $sku_stock_id = $data["sku_stock_id"] == "" ? null : $data["sku_stock_id"];
		$sku_id = $data["sku_id"] == "" ? null : $data["sku_id"];
		$sku_stock_expired_date = $data["sku_stock_expired_date"] == "" ? null : $data["sku_stock_expired_date"];
		$tr_konversi_sku_detail_qty_plan = $data["tr_konversi_sku_detail_qty_plan"] == "" ? null : $data["tr_konversi_sku_detail_qty_plan"];

		$this->db->set("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		$this->db->set("tr_konversi_sku_id", $tr_konversi_sku_id);
		// $this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
		$this->db->set("tr_konversi_sku_detail_qty_plan", $tr_konversi_sku_detail_qty_plan);

		$queryinsert = $this->db->insert("tr_konversi_sku_detail");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_tr_konversi_sku_detail2($sku_stock_id_tujuan, $data)
	{
		$tr_konversi_sku_detail2_id = $data["tr_konversi_sku_detail2_id"] == "" ? null : $data["tr_konversi_sku_detail2_id"];
		$tr_konversi_sku_detail_id = $data["tr_konversi_sku_detail_id"] == "" ? null : $data["tr_konversi_sku_detail_id"];
		$sku_stock_id = $sku_stock_id_tujuan == "" ? null : $sku_stock_id_tujuan;
		$sku_id = $data["sku_id"] == "" ? null : $data["sku_id"];
		// $sku_stock_expired_date = $data["sku_stock_expired_date"] == "" ? null : $data["sku_stock_expired_date"];
		$tr_konversi_sku_detail2_qty_result = $data["tr_konversi_sku_detail2_qty_result"] == "" ? null : $data["tr_konversi_sku_detail2_qty_result"];
		$tr_konversi_sku_detail2_qty = $data["tr_konversi_sku_detail2_qty"] == "" ? null : $data["tr_konversi_sku_detail2_qty"];
		$pallet_id_asal = $data["pallet_id_asal"] == "" ? null : $data["pallet_id_asal"];
		$pallet_id_tujuan = $data["pallet_id_tujuan"] == "" ? null : $data["pallet_id_tujuan"];

		// $this->db->set("tr_konversi_sku_detail2_id", "NEWID()", FALSE);
		$this->db->set("tr_konversi_sku_detail2_id", $tr_konversi_sku_detail2_id);
		$this->db->set("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_id", $sku_id);
		// $this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
		$this->db->set("tr_konversi_sku_detail2_qty", $tr_konversi_sku_detail2_qty);
		$this->db->set("tr_konversi_sku_detail2_qty_result", $tr_konversi_sku_detail2_qty_result);
		$this->db->set("pallet_id_asal", $pallet_id_asal);
		$this->db->set("pallet_id_tujuan", $pallet_id_tujuan);

		$queryinsert = $this->db->insert("tr_konversi_sku_detail2");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function insert_tr_konversi_sku_detail3($sku_stock_id_tujuan, $data)
	{
		$tr_konversi_sku_detail_id = $data["tr_konversi_sku_detail_id"] == "" ? null : $data["tr_konversi_sku_detail_id"];
		$sku_stock_id = $sku_stock_id_tujuan == "" ? null : $sku_stock_id_tujuan;
		$tr_konversi_sku_detail3_qty_result = $data["tr_konversi_sku_detail2_qty_result"] == "" ? null : $data["tr_konversi_sku_detail2_qty_result"];
		$pallet_id = $data["tr_konversi_sku_detail2_qty"] == "" ? null : $data["pallet_id"];

		$this->db->set("tr_konversi_sku_detail3_id", "NEWID()", FALSE);
		$this->db->set("tr_konversi_sku_detail2_id", $tr_konversi_sku_detail_id);
		$this->db->set("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("tr_konversi_sku_detail3_qty_result", $tr_konversi_sku_detail3_qty_result);
		$this->db->set("pallet_id", $pallet_id);

		$queryinsert = $this->db->insert("tr_konversi_sku_detail3");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function get_tr_konversi_sku_detail2_temp($id, $pallet_id_asal)
	{
		$query = $this->db->query("SELECT
                                        temp.*,
                                        detail.tr_konversi_sku_detail_qty_plan,
                                        detail.sku_stock_id AS sku_stock_id_asal
                                    FROM tr_konversi_sku_detail2_temp temp
                                    LEFT JOIN tr_konversi_sku_detail detail
                                    ON detail.tr_konversi_sku_detail_id = temp.tr_konversi_sku_detail_id
                                    WHERE temp.tr_konversi_sku_detail_id = '$id'
                                    AND temp.pallet_id_asal = '$pallet_id_asal' ");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function update_tr_konversi_sku_detail2_temp_by_pallet($tr_konversi_sku_detail_id, $pallet_id, $tr_konversi_sku_detail2_qty, $tr_konversi_sku_detail2_qty_result)
	{
		$this->db->set("tr_konversi_sku_detail2_qty", $tr_konversi_sku_detail2_qty);
		$this->db->set("tr_konversi_sku_detail2_qty_result", $tr_konversi_sku_detail2_qty_result);

		$this->db->where("pallet_id", $pallet_id);
		$this->db->where("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		$queryinsert = $this->db->update("tr_konversi_sku_detail2_temp");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function delete_tr_konversi_sku_detail2_temp()
	{
		return $this->db->query("DELETE tr_konversi_sku_detail2_temp");
	}

	public function delete_tr_konversi_sku_detail2_temp_by_pallet($tr_konversi_sku_detail_id, $pallet_id)
	{
		return $this->db->query("DELETE tr_konversi_sku_detail2_temp WHERE tr_konversi_sku_detail_id = '$tr_konversi_sku_detail_id' AND pallet_id = '$pallet_id' ");
	}

	public function delete_tr_konversi_sku_detail2_temp_by_sku_stock_id($sku_id, $sku_stock_expired_date, $pallet_id_asal)
	{
		$this->db->where('pallet_id_asal', $pallet_id_asal);
		$this->db->where('sku_id', $sku_id);
		$this->db->where('sku_stock_expired_date', $sku_stock_expired_date);
		$this->db->delete('tr_konversi_sku_detail2_temp');

		return $this->db->last_query();
	}

	public function delete_tr_konversi_sku_detail($id)
	{
		$this->db->where('tr_konversi_sku_id', $id);
		return $this->db->delete('tr_konversi_sku_detail');
	}

	public function delete_tr_konversi_sku_detail2($id, $pallet_id_asal)
	{
		return $this->db->query("DELETE tr_konversi_sku_detail2 WHERE tr_konversi_sku_detail_id = '$id' AND pallet_id_asal = '$pallet_id_asal' ");
	}

	public function delete_tr_konversi_sku_detail3($id, $pallet_id)
	{
		return $this->db->query("DELETE tr_konversi_sku_detail3 WHERE tr_konversi_sku_detail_id = '$id' AND pallet_id = '$pallet_id' ");
	}

	public function Exec_approval_pengajuan($depo_id, $karyawan_id, $approvalParam, $tr_konversi_sku_id, $tr_konversi_sku_kode, $is_approvaldana, $total_biaya)
	{
		$query = $this->db->query("exec approval_pengajuan '$depo_id', '$karyawan_id','$approvalParam', '$tr_konversi_sku_id','$tr_konversi_sku_kode', '$is_approvaldana','$total_biaya'");

		// $res = $query->result_array();

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Success
		}

		return $res;
	}

	public function proses_konversi_sku_pack_unpack($sku_id, $qty_plan)
	{
		$query = $this->db->query("Exec proses_konversi_sku_pack_unpack '$sku_id', '$qty_plan'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function check_kode_pallet($depo_detail_id, $kode_pallet)
	{
		// $this->db->select("pallet_id")
		// 	->from("pallet")
		// 	->where("pallet_kode", $kode_pallet);
		// $query = $this->db->get();

		$query = $this->db->query("select
										pallet.pallet_id,
										pallet.pallet_kode
									from pallet
									left join rak_lajur_detail_pallet
									on rak_lajur_detail_pallet.pallet_id = pallet.pallet_id
									left join rak_lajur_detail
									on rak_lajur_detail.rak_lajur_detail_id = rak_lajur_detail_pallet.rak_lajur_detail_id
									left join rak_lajur
									on rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									left join rak
									on rak.rak_id = rak_lajur.rak_id
									where rak.depo_detail_id = '$depo_detail_id' and pallet.pallet_kode = '$kode_pallet'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->pallet_id;
		}

		return $query;
	}
	public function check_kode_pallet_and_qty($kode_pallet, $sku_stock_id)
	{
		$this->db->select("pallet_id")
			->from("pallet")
			->where("pallet_kode", $kode_pallet);


		$query = $this->db->query("select pallet.pallet_id,pallet.pallet_kode, ISNULL(pd.sku_stock_qty,0) - ISNULL(pd.sku_stock_ambil,0) + ISNULL(pd.sku_stock_in,0) - ISNULL(pd.sku_stock_out,0) + ISNULL(pd.sku_stock_terima,0) AS sku_stock_qty  from pallet left join pallet_detail pd on pd.pallet_id = pallet.pallet_id
		where pallet.pallet_kode =  '$kode_pallet' and pd.sku_stock_id ='$sku_stock_id' order by sku_stock_qty desc");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0);
		}

		return $query;
	}

	public function Getkonversiskuheader2($id)
	{
		$query = $this->db->query("SELECT
                                    detail.tr_konversi_sku_detail_id,
                                    detail.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.sku_satuan,
                                    sku.sku_kemasan,
                                    sku.sku_konversi_level,
                                    detail.sku_stock_expired_date,
                                    detail.tr_konversi_sku_detail_qty_plan,
                                    SUM(ISNULL(temp.tr_konversi_sku_detail2_qty,0)) AS tr_konversi_sku_detail2_qty,
                                    SUM(ISNULL(temp.tr_konversi_sku_detail2_qty_result,0)) AS tr_konversi_sku_detail2_qty_result
                                    FROM tr_konversi_sku_detail detail
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail.sku_id
                                    LEFT JOIN tr_konversi_sku_detail2_temp temp
                                    ON detail.tr_konversi_sku_detail_id = temp.tr_konversi_sku_detail_id
                                    WHERE detail.tr_konversi_sku_detail_id = '$id'
                                    GROUP BY detail.tr_konversi_sku_detail_id,
                                            detail.sku_id,
                                            sku.sku_kode,
                                            sku.sku_nama_produk,
                                            sku.sku_satuan,
                                            sku.sku_kemasan,
                                            sku.sku_konversi_level,
                                            detail.sku_stock_expired_date,
                                            detail.tr_konversi_sku_detail_qty_plan");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetViewkonversiskudetail2($id)
	{

		$query = $this->db->query("SELECT
                                    detail2.tr_konversi_sku_detail2_id,
                                    detail2.sku_id,
                                    sku.sku_nama_produk,
                                    sku.sku_satuan,
                                    detail2.tr_konversi_sku_detail2_qty,
                                    detail2.tr_konversi_sku_detail2_qty_result,
                                    detail2.pallet_id_asal,
                                    pallet_asal.pallet_kode AS pallet_kode_asal,
                                    detail2.pallet_id_tujuan,
                                    pallet_tujuan.pallet_kode AS pallet_kode_tujuan
                                    FROM tr_konversi_sku_detail2 detail2
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail2.sku_id
                                    LEFT JOIN pallet pallet_asal
                                    ON pallet_asal.pallet_id = detail2.pallet_id_asal
                                    LEFT JOIN pallet pallet_tujuan
                                    ON pallet_tujuan.pallet_id = detail2.pallet_id_tujuan
                                    WHERE detail2.tr_konversi_sku_detail2_id = '$id'
                                    ORDER BY sku.sku_konversi_level DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetViewkonversiskuheader2($id)
	{
		$query = $this->db->query("SELECT
                                    detail.tr_konversi_sku_detail_id,
                                    detail.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.sku_satuan,
                                    sku.sku_kemasan,
                                    sku.sku_konversi_level,
                                    detail.sku_stock_expired_date,
                                    detail.tr_konversi_sku_detail_qty_plan,
                                    SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty,0)) AS tr_konversi_sku_detail2_qty,
                                    SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty_result,0)) AS tr_konversi_sku_detail2_qty_result
                                    FROM tr_konversi_sku_detail detail
                                    LEFT JOIN tr_konversi_sku_detail2 detail2
                                    ON detail.tr_konversi_sku_detail_id = detail2.tr_konversi_sku_detail_id
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail.sku_id
                                    WHERE detail2.tr_konversi_sku_detail2_id = '$id'
                                    GROUP BY detail.tr_konversi_sku_detail_id,
                                            detail.sku_id,
                                            sku.sku_kode,
                                            sku.sku_nama_produk,
                                            sku.sku_satuan,
                                            sku.sku_kemasan,
                                            sku.sku_konversi_level,
                                            detail.sku_stock_expired_date,
                                            detail.tr_konversi_sku_detail_qty_plan");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Getkonversiskudetail2($id, $pallet_id, $satuan, $tipe_konversi)
	{
		$tipe_konversi = preg_replace("/[^a-zA-Z]/", "", $tipe_konversi);

		if ($tipe_konversi == "Unpack") {
			$tipe_konversi = "AND sku_konversi.sku_konversi_level < sku.sku_konversi_level ";
		} else if ($tipe_konversi == "Repack") {
			$tipe_konversi = "AND sku_konversi.sku_konversi_level > sku.sku_konversi_level ";
		}

		$query = $this->db->query("SELECT DISTINCT
                                    detail.tr_konversi_sku_detail_id,
                                    detail.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.sku_kemasan AS sku_kemasan_param,
                                    sku.sku_satuan AS sku_satuan_param,
                                    sku.sku_konversi_level AS sku_konversi_level_param,
                                    ISNULL(sku.sku_konversi_faktor, 0) AS sku_konversi_faktor_param,
                                    sku.sku_induk_id,
                                    sku_konversi.sku_id AS sku_id_konversi,
                                    sku_konversi.sku_kemasan,
                                    sku_konversi.sku_satuan,
                                    sku.sku_konversi_group,
                                    sku_konversi.sku_konversi_level,
                                    ISNULL(sku_konversi.sku_konversi_faktor, 0) AS sku_konversi_faktor,
                                    CASE
                                        WHEN sku.sku_konversi_level > sku_konversi.sku_konversi_level THEN '*'
                                        WHEN sku.sku_konversi_level = sku_konversi.sku_konversi_level THEN '='
                                        ELSE '/'
                                    END konversi_operator,
                                    detail.sku_stock_expired_date,
                                    pallet_detail.pallet_id,
                                    pallet.pallet_kode,
                                    temp.pallet_id_tujuan,
                                    temp.pallet_kode AS pallet_kode_tujuan,
                                    detail.tr_konversi_sku_detail_qty_plan,
                                    ISNULL(temp.tr_konversi_sku_detail2_qty, 0) AS tr_konversi_sku_detail2_qty,
                                    ISNULL(temp.tr_konversi_sku_detail2_qty_result, 0) AS tr_konversi_sku_detail2_qty_result,
									tks.is_from_do,
									detail.sku_konversi_hasil
                                    FROM tr_konversi_sku_detail detail
									LEFT JOIN tr_konversi_sku AS tks ON detail.tr_konversi_sku_id = tks.tr_konversi_sku_id
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail.sku_id
                                    LEFT JOIN sku sku_konversi
                                    ON sku_konversi.sku_konversi_group = sku.sku_konversi_group
                                    AND sku_konversi.sku_satuan <> '$satuan'
                                    LEFT JOIN pallet_detail
                                    ON pallet_detail.sku_id = detail.sku_id
                                    AND pallet_detail.sku_stock_expired_date = detail.sku_stock_expired_date
                                    LEFT JOIN pallet
                                    ON pallet.pallet_id = pallet_detail.pallet_id
                                    LEFT JOIN (SELECT
                                    a.tr_konversi_sku_detail_id,
                                    a.pallet_id_asal,
                                    a.pallet_id_tujuan,
                                    b.pallet_kode,
                                    a.sku_id,
                                    SUM(a.tr_konversi_sku_detail2_qty) AS tr_konversi_sku_detail2_qty,
                                    SUM(a.tr_konversi_sku_detail2_qty_result) AS tr_konversi_sku_detail2_qty_result
                                    FROM tr_konversi_sku_detail2_temp a
                                    LEFT JOIN pallet b
                                    ON a.pallet_id_tujuan = b.pallet_id
                                    GROUP BY a.tr_konversi_sku_detail_id,
                                            a.pallet_id_asal,
                                            a.pallet_id_tujuan,
                                            b.pallet_kode,
                                            a.sku_id) temp
                                    ON detail.tr_konversi_sku_detail_id = temp.tr_konversi_sku_detail_id
                                    AND temp.pallet_id_asal = pallet_detail.pallet_id
                                    AND temp.sku_id = sku_konversi.sku_id
                                    WHERE detail.tr_konversi_sku_detail_id = '$id'
                                    AND pallet_detail.pallet_id = '$pallet_id'
                                    " . $tipe_konversi . "
                                    ORDER BY sku_konversi.sku_konversi_level DESC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function CheckKonversiSKU($tr_konversi_sku_detail_id)
	{
		$this->db->select("*")
			->from("tr_konversi_sku_detail2_temp")
			->where("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function CheckQtyKonersiSKU($id)
	{
		$query = $this->db->query("SELECT
                                        detail.tr_konversi_sku_detail_id,
                                        detail.tr_konversi_sku_id,
                                        detail.sku_stock_expired_date,
                                        detail.tr_konversi_sku_detail_qty_plan,
                                        SUM(temp.tr_konversi_sku_detail2_qty) AS tr_konversi_sku_detail2_qty
                                    FROM tr_konversi_sku_detail2_temp temp
                                    LEFT JOIN tr_konversi_sku_detail detail
                                    ON temp.tr_konversi_sku_detail_id = detail.tr_konversi_sku_detail_id
                                    WHERE temp.tr_konversi_sku_detail_id = '$id'
                                    GROUP BY detail.tr_konversi_sku_detail_id,
                                        detail.tr_konversi_sku_id,
                                        detail.sku_stock_expired_date,
                                        detail.tr_konversi_sku_detail_qty_plan
                                    HAVING SUM(temp.tr_konversi_sku_detail2_qty) - detail.tr_konversi_sku_detail_qty_plan > 0");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function check_konversi_sku_detail2($id)
	{
		$query = $this->db->query("SELECT
                                    detail.tr_konversi_sku_detail_id,
                                    detail.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.sku_satuan,
                                    sku.sku_kemasan,
                                    sku.sku_konversi_level,
                                    detail.sku_stock_expired_date,
                                    detail.tr_konversi_sku_detail_qty_plan,
                                    SUM(ISNULL(temp.tr_konversi_sku_detail2_qty,0)) AS tr_konversi_sku_detail2_qty,
                                    SUM(ISNULL(temp.tr_konversi_sku_detail2_qty_result,0)) AS tr_konversi_sku_detail2_qty_result
                                    FROM tr_konversi_sku_detail detail
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail.sku_id
                                    LEFT JOIN tr_konversi_sku_detail2_temp temp
                                    ON detail.tr_konversi_sku_detail_id = temp.tr_konversi_sku_detail_id
                                    WHERE detail.tr_konversi_sku_detail_id = '$id'
                                    GROUP BY detail.tr_konversi_sku_detail_id,
                                            detail.sku_id,
                                            sku.sku_kode,
                                            sku.sku_nama_produk,
                                            sku.sku_satuan,
                                            sku.sku_kemasan,
                                            sku.sku_konversi_level,
                                            detail.sku_stock_expired_date,
                                            detail.tr_konversi_sku_detail_qty_plan");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function update_pallet_detail($sku_stock_id_tujuan, $data)
	{
		$this->db->select("*")
			->from("pallet_detail")
			->where("pallet_id", $data['pallet_id_tujuan'])
			->where("sku_stock_id", $sku_stock_id_tujuan);
		$query_pallet_tujuan = $this->db->get();

		if ($query_pallet_tujuan->num_rows() == 0) {
			$palet_asal = $data['pallet_id_asal'];
			$sku_stock_id_asal = $data['sku_stock_id_asal'];
			$qty_plan = $data['tr_konversi_sku_detail_qty_plan'];

			$sku_stock = $this->db->select("*")
				->from("sku_stock")
				->where("sku_stock_id", $sku_stock_id_tujuan)->get();
			$sku_stock = $sku_stock->row();

			$sku_stock_out_asal = $this->db->select("*")
				->from("pallet_detail")
				->where("pallet_id", $data['pallet_id_asal'])
				->where("sku_stock_id", $data['sku_stock_id_asal'])->get()->row(0)->sku_stock_out;

			// $this->db->set('sku_stock_out', $sku_stock_out_asal + $data['tr_konversi_sku_detail_qty_plan']);
			// $this->db->where('sku_stock_id', $data['sku_stock_id_asal']);
			// $this->db->where('pallet_id', $data['pallet_id_asal']);
			// $this->db->update('pallet_detail');

			$this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_out', '$palet_asal', '$sku_stock_id_asal', $qty_plan");

			$this->db->set('pallet_detail_id', "NEWID()", FALSE);
			$this->db->set('pallet_id', $data['pallet_id_tujuan']);
			$this->db->set('sku_id', $data['sku_id']);
			$this->db->set('sku_stock_id', $sku_stock_id_tujuan);
			$this->db->set('sku_stock_expired_date', $sku_stock->sku_stock_expired_date);
			$this->db->set('sku_stock_qty', 0);
			$this->db->set('sku_stock_ambil', 0);
			$this->db->set('sku_stock_in', $data['tr_konversi_sku_detail2_qty_result']);
			$this->db->set('sku_stock_out', 0);
			$this->db->set('sku_stock_terima', 0);
			$this->db->set('sku_stock_expired_date_sj', NULL);

			$queryinsert = $this->db->insert('pallet_detail');
		} else {

			// $sku_stock_out_asal = $this->db->select("*")
			// 	->from("pallet_detail")
			// 	->where("pallet_id", $data['pallet_id_asal'])
			// 	->where("sku_stock_id", $data['sku_stock_id_asal'])->get()->row(0)->sku_stock_out;

			// $this->db->set('sku_stock_out', $sku_stock_out_asal + $data['tr_konversi_sku_detail_qty_plan']);
			// $this->db->where('sku_stock_id', $data['sku_stock_id_asal']);
			// $this->db->where('pallet_id', $data['pallet_id_asal']);
			// $this->db->update('pallet_detail');
			$palet_asal = $data['pallet_id_asal'];
			$sku_stock_id_asal = $data['sku_stock_id_asal'];
			$qty_plan = $data['tr_konversi_sku_detail_qty_plan'];
			$this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_out', '$palet_asal', '$sku_stock_id_asal', $qty_plan");


			foreach ($query_pallet_tujuan->result_array() as $key => $value) {
				// $this->db->set('sku_stock_in', $value['sku_stock_in'] + $data['tr_konversi_sku_detail2_qty_result']);
				// $this->db->where('sku_stock_id', $sku_stock_id_tujuan);
				// $this->db->where('pallet_id', $data['pallet_id_tujuan']);

				// $queryinsert = $this->db->update('pallet_detail');

				$palet_tujuan = $data['pallet_id_tujuan'];
				$qty_result = $data['tr_konversi_sku_detail2_qty_result'];
				$queryinsert = $this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_in', '$palet_tujuan', '$sku_stock_id_tujuan', $qty_result");
			}
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function check_sku_stock_id($client_wms_id, $depo_detail_id, $tr_konversi_sku_id)
	{
		$query_tr_konversi_sku_detail = $this->db->select("*")
			->from("tr_konversi_sku_detail")
			->where("tr_konversi_sku_id", $tr_konversi_sku_id)->get()->result_array();

		foreach ($query_tr_konversi_sku_detail as $key => $value) {
			$tr_konversi_sku_detail_qty_plan = $value['tr_konversi_sku_detail_qty_plan'] == '' ? null : $value['tr_konversi_sku_detail_qty_plan'];
			$sku_stock_id_asal = $value['sku_stock_id'];

			$this->db->select("*")
				->from("sku_stock")
				->where("sku_stock_id", $sku_stock_id_asal);
			$query_sku_stock_asal = $this->db->get()->result_array();

			foreach ($query_sku_stock_asal as $key => $value2) {
				$sku_stock_keluar = $value2['sku_stock_keluar'] + $tr_konversi_sku_detail_qty_plan;

				// $this->db->set("sku_stock_keluar", $sku_stock_keluar);
				// $this->db->where("sku_stock_id", $sku_stock_id_asal);

				// $queryinsert = $this->db->update("sku_stock");

				$tipe = "keluar";
				$waktu_delay = "00:00:01";

				$queryinsert = $this->db->query("exec insertupdate_sku_stock '$tipe', '$sku_stock_id_asal', NULL, '$tr_konversi_sku_detail_qty_plan'");
			}
		}

		$query_tr_konversi_sku_detail2 = $this->db->query("SELECT
                                                            temp.tr_konversi_sku_detail_id,
                                                            temp.sku_id,
                                                            temp.sku_stock_id,
                                                            temp.sku_stock_expired_date,
                                                            SUM(temp.tr_konversi_sku_detail2_qty) AS tr_konversi_sku_detail2_qty,
                                                            SUM(temp.tr_konversi_sku_detail2_qty_result) AS tr_konversi_sku_detail2_qty_result
                                                            FROM tr_konversi_sku_detail2_temp temp
                                                            LEFT JOIN tr_konversi_sku_detail detail
                                                            ON detail.tr_konversi_sku_detail_id = temp.tr_konversi_sku_detail_id
                                                            WHERE detail.tr_konversi_sku_id = '$tr_konversi_sku_id'
                                                            GROUP BY temp.tr_konversi_sku_detail_id,
                                                            temp.sku_id,
                                                            temp.sku_stock_id,
                                                            temp.sku_stock_expired_date")->result_array();

		foreach ($query_tr_konversi_sku_detail2 as $key => $value) {
			$sku_stock_id = $value['sku_stock_id'] == '' ? null : $value['sku_stock_id'];
			$depo_id = $this->session->userdata('depo_id');
			$sku_id = $value['sku_id'] == '' ? null : $value['sku_id'];
			$sku_stock_expired_date = $value['sku_stock_expired_date'] == '' ? null : $value['sku_stock_expired_date'];
			$tr_konversi_sku_detail2_qty_result = $value['tr_konversi_sku_detail2_qty_result'] == '' ? null : $value['tr_konversi_sku_detail2_qty_result'];
			$sku_induk_id = $this->db->select("sku_induk_id")->from("sku")->where("sku_id", $sku_id)->get()->row(0)->sku_induk_id;
			// $sku_stock_akhir = $data['sku_stock_akhir'] == '' ? null : $data['sku_stock_akhir'];

			//query -> sku stock tujuan
			$this->db->select("*")
				->from("sku_stock")
				->where("depo_id", $depo_id)
				->where("client_wms_id", $client_wms_id)
				->where("depo_detail_id", $depo_detail_id)
				->where("sku_id", $sku_id)
				->where("sku_stock_expired_date", $sku_stock_expired_date);
			$query_sku_stock_tujuan = $this->db->get();

			if ($query_sku_stock_tujuan->num_rows() == 0) {

				$this->db->set("sku_stock_id", "NEWID()", FALSE);
				// $this->db->set("unit_mandiri_id", $unit_mandiri_id);
				$this->db->set("client_wms_id", $client_wms_id);
				$this->db->set("depo_id", $depo_id);
				$this->db->set("depo_detail_id", $depo_detail_id);
				$this->db->set("sku_induk_id", $sku_induk_id);
				$this->db->set("sku_id", $sku_id);
				$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);
				// $this->db->set("sku_stock_batch_no", $sku_stock_batch_no);
				$this->db->set("sku_stock_awal", 0);
				$this->db->set("sku_stock_masuk", $tr_konversi_sku_detail2_qty_result);
				$this->db->set("sku_stock_alokasi", 0);
				$this->db->set("sku_stock_saldo_alokasi", 0);
				$this->db->set("sku_stock_keluar", 0);
				$this->db->set("sku_stock_akhir", 0);
				$this->db->set("sku_stock_is_jual", 1);
				$this->db->set("sku_stock_is_aktif", 1);
				$this->db->set("sku_stock_is_deleted", 0);

				$queryinsert = $this->db->insert("sku_stock");
			} else {
				foreach ($query_sku_stock_tujuan->result_array() as $key => $value2) {
					// $sku_stock_masuk = $value2['sku_stock_masuk'] + $tr_konversi_sku_detail2_qty_result;

					// $this->db->set("sku_stock_masuk", $sku_stock_masuk);
					// $this->db->where("sku_stock_id", $value2['sku_stock_id']);

					// $queryinsert = $this->db->update("sku_stock");

					$tipe = "masuk";
					$waktu_delay = "00:00:01";

					$queryinsert = $this->db->query("exec insertupdate_sku_stock '$tipe', '" . $value2['sku_stock_id'] . "', NULL, '$tr_konversi_sku_detail2_qty_result'");
				}
			}
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function get_sku_stock_id_tujuan($client_wms_id, $depo_detail_id, $sku_id, $sku_stock_expired_date)
	{
		$query = $this->db->select("*")
			->from("sku_stock")
			->where("client_wms_id", $client_wms_id)
			->where("depo_detail_id", $depo_detail_id)
			->where("sku_id", $sku_id)
			->where("sku_stock_expired_date", $sku_stock_expired_date)->get();
		return $query->row();
	}

	public function Exec_proses_posting_sku_stock_card($identity, $tr_konversi_sku_id, $tr_konversi_sku_who_create)
	{
		$query = $this->db->query("exec proses_posting_stock_card '$identity', '$tr_konversi_sku_id', '$tr_konversi_sku_who_create'");

		// $res = $query->result_array();

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$res = 1; // Success
		} else {
			$res = 0; // Success
		}

		return $res;
	}

	public function getDataKonversiByID($id)
	{
		$arr_detail = [];
		$header = $this->db->query("SELECT
                                    k.tr_konversi_sku_kode,
                                    k.tr_konversi_sku_keterangan,
                                    tk.tipe_konversi_nama,
                                    pt.client_wms_nama,
                                    g.depo_detail_nama,
                                    FORMAT(k.tr_konversi_sku_tanggal, 'dd-MM-yyyy') AS tr_konversi_sku_tanggal,
                                    k.tr_konversi_sku_status
                                    FROM tr_konversi_sku AS k
                                    LEFT JOIN client_wms AS pt
                                    ON pt.client_wms_id = k.client_wms_id
                                    LEFT JOIN tipe_konversi AS tk
                                    ON tk.tipe_konversi_id = k.tipe_konversi_id
                                    left join depo_detail g
                                    on k.depo_detail_id = g.depo_detail_id
                                    WHERE k.tr_konversi_sku_id = '$id'")->row_array();

		// $detail = $this->db->query("SELECT s.sku_kode,
		//                                     s.sku_nama_produk,
		//                                     b.principle_brand_nama,
		//                                     s.sku_kemasan,
		//                                     s.sku_satuan,
		//                                     FORMAT(d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
		//                                     d.tr_konversi_sku_detail_qty_plan
		//                             FROM tr_konversi_sku_detail AS d
		//                             LEFT JOIN sku AS s ON d.sku_id = s.sku_id 
		//                             LEFT JOIN principle_brand AS b ON s.principle_brand_id = b.principle_brand_id
		//                             where d.tr_konversi_sku_id = '$id'")->result_array();
		$detail = $this->db->query("SELECT s.sku_kode,
                                            s.sku_nama_produk,
                                            b.principle_brand_nama,
                                            s.sku_kemasan,
                                            s.sku_satuan,
                                            FORMAT(d.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
                                            d.tr_konversi_sku_detail_qty_plan,
                                            g.depo_detail_nama,
                                            lj1.rak_lajur_detail_nama AS lokasi_asal,
                                            p1.pallet_kode AS kode_asal,
                                            d2.tr_konversi_sku_detail2_qty AS qty_aktual,
                                            s1.sku_satuan AS satuan_asal,
                                            d2.tr_konversi_sku_detail2_qty_result AS qty_konversi,
                                            s2.sku_satuan AS satuan_tujuan,
                                            p2.pallet_kode AS kode_tujuan,
                                            lj2.rak_lajur_detail_nama AS lokasi_tujuan
                                    FROM tr_konversi_sku AS k
                                    LEFT JOIN depo_detail AS g ON k.depo_detail_id = g.depo_detail_id
                                    LEFT JOIN tr_konversi_sku_detail AS d ON k.tr_konversi_sku_id = d.tr_konversi_sku_id
                                    LEFT JOIN tr_konversi_sku_detail2 AS d2 ON d.tr_konversi_sku_detail_id = d2.tr_konversi_sku_detail_id
                                    LEFT JOIN pallet AS p1 ON d2.pallet_id_asal = p1.pallet_id
                                    LEFT JOIN pallet AS p2 ON d2.pallet_id_tujuan = p2.pallet_id
                                    LEFT JOIN rak_lajur_detail_pallet AS pd1 ON d2.pallet_id_asal = pd1.pallet_id
                                    LEFT JOIN rak_lajur_detail_pallet AS pd2 ON d2.pallet_id_tujuan = pd2.pallet_id
                                    LEFT JOIN rak_lajur_detail as lj1 ON pd1.rak_lajur_detail_id = lj1.rak_lajur_detail_id
                                    LEFT JOIN rak_lajur_detail as lj2 ON pd2.rak_lajur_detail_id = lj2.rak_lajur_detail_id
                                    LEFT JOIN sku as s1 ON d.sku_id = s1.sku_id
                                    LEFT JOIN sku as s2 ON d2.sku_id = s2.sku_id
                                    LEFT JOIN sku AS s ON d.sku_id = s.sku_id
                                    LEFT JOIN principle_brand AS b ON s.principle_brand_id = b.principle_brand_id
                                    where d.tr_konversi_sku_id = '$id'")->result_array();

		foreach ($detail as $data) {
			$arr_detail[] = array(
				'sku_kode'          => $data['sku_kode'],
				'sku_nama_produk'   => $data['sku_nama_produk'],
				'brand'             => $data['principle_brand_nama'],
				'sku_kemasan'       => $data['sku_kemasan'],
				'sku_satuan'        => $data['sku_satuan'],
				'expired_date'      => $data['sku_stock_expired_date'],
				'qty_plan'          => $data['tr_konversi_sku_detail_qty_plan'],
				'area'              => $data['depo_detail_nama'],
				'lokasi_asal'       => $data['lokasi_asal'],
				'kode_asal'         => $data['kode_asal'],
				'qty_aktual'        => $data['qty_aktual'],
				'satuan_asal'       => $data['satuan_asal'],
				'qty_konversi'      => $data['qty_konversi'],
				'satuan_tujuan'     => $data['satuan_tujuan'],
				'kode_tujuan'       => $data['kode_tujuan'],
				'lokasi_tujuan'     => $data['lokasi_tujuan'],
			);
		}

		$response = [
			'header' => $header,
			'detail' => $arr_detail
		];

		return $response;
	}

	public function Get_sku_konversi_group_by_tr_konversi_sku_id($tr_konversi_sku_id)
	{
		$query = $this->db->query("SELECT
									a.sku_id,
									b.sku_konversi_group
									FROM tr_konversi_sku_detail a
									LEFT JOIN sku b
									ON b.sku_id = a.sku_id
									WHERE CONVERT(nvarchar(36), a.tr_konversi_sku_id) = '$tr_konversi_sku_id'
									GROUP BY a.sku_id,b.sku_konversi_group
									ORDER BY b.sku_konversi_group ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_sku_satuan_by_tr_konversi_sku_id($tr_konversi_sku_id, $sku_konversi_group, $sku_id)
	{
		$sku_satuan = $this->db->query("select * from sku where sku_id='$sku_id'")->row(0)->sku_konversi_level;

		$query = $this->db->query("SELECT
									sku.sku_konversi_group,
									sku.sku_satuan,
									sku.sku_konversi_level
									FROM sku
									LEFT JOIN tr_konversi_sku_detail konversi
									ON konversi.sku_id = sku.sku_id
									AND CONVERT(nvarchar(36), konversi.tr_konversi_sku_id) = '$tr_konversi_sku_id'
									WHERE sku.sku_konversi_group = '$sku_konversi_group'
									AND sku.sku_konversi_level > 0
									GROUP BY sku.sku_konversi_group,
											sku.sku_satuan,
											sku.sku_konversi_level
									ORDER BY sku.sku_konversi_level DESC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_sku_by_tr_konversi_sku_id($tr_konversi_sku_id, $sku_konversi_group, $sku_satuan)
	{
		$query = $this->db->query("SELECT
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									sku.sku_konversi_group
									FROM sku
									WHERE sku.sku_konversi_group = '$sku_konversi_group'
									AND sku.sku_satuan = '$sku_satuan'
									ORDER BY sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_list_sku_konversi_group_by_tr_konversi_sku_id($tr_konversi_sku_id, $arr_list_hasil_sku_konversi_pengemasan, $arr_konversi_sku_pallet_asal)
	{

		$join_table_sementara = "";
		$join_table_sementara2 = "";

		$column_tr_konversi_sku_detail_qty_plan = "0";

		$union = " UNION ALL ";
		$table_sementara = "";

		foreach ($arr_list_hasil_sku_konversi_pengemasan as $key => $value) {
			$sku_composite = $value['sku_satuan'] == "" || $value['sku_satuan'] === NULL ? 0 : preg_replace("/[^0-9]/", "", $value['sku_satuan']);

			$sku_qty = $value['sku_qty'] == "" || $value['sku_qty'] === NULL ? 0 : $sku_composite * $value['sku_qty_hasil_konversi'];
			$sku_qty_konversi_plan = $value['sku_qty_konversi_plan'] == "" || $value['sku_qty_konversi_plan'] === NULL ? 0 : $value['sku_qty_konversi_plan'];
			$sku_qty_hasil_konversi = $value['sku_qty_hasil_konversi'] == "" || $value['sku_qty_hasil_konversi'] === NULL ? 0 : $value['sku_qty_hasil_konversi'];
			$sku_qty_sisa_konversi = $value['sku_qty_sisa_konversi'] == "" || $value['sku_qty_sisa_konversi'] === NULL ? 0 : $value['sku_qty_sisa_konversi'];

			$table_sementara .= "SELECT '" . $value['idx'] . "' AS idx, '" . $value['sku_id_awal'] . "' AS sku_id_awal,'" . $value['sku_id'] . "' AS sku_id, '" . $value['sku_kode'] . "' AS sku_kode, '" . $value['sku_nama_produk'] . "' AS sku_nama_produk, '" . $value['sku_konversi_group'] . "' AS sku_konversi_group, '" . $value['sku_satuan'] . "' AS sku_satuan, '" . $value['sku_kemasan'] . "' AS sku_kemasan, " . $sku_qty . " AS sku_qty, " . $sku_qty_konversi_plan . " AS sku_qty_konversi_plan, " . $sku_qty_hasil_konversi . " AS sku_qty_hasil_konversi, " . $sku_qty_sisa_konversi . " AS sku_qty_sisa_konversi";

			if ($key < count($arr_list_hasil_sku_konversi_pengemasan) - 1) {
				$table_sementara .= $union;
			}
		}

		if (count($arr_konversi_sku_pallet_asal) > 0) {

			$union2 = " UNION ALL ";
			$table_sementara2 = "";

			foreach ($arr_konversi_sku_pallet_asal as $key => $value) {

				$table_sementara2 .= "SELECT '" . $value['idx'] . "' AS idx, '" . $value['tr_konversi_sku_detail_id'] . "' AS tr_konversi_sku_detail_id,'" . $value['tr_konversi_sku_id'] . "' AS tr_konversi_sku_id, '" . $value['pallet_id_asal'] . "' AS pallet_id_asal, '" . $value['sku_stock_id'] . "' AS sku_stock_id, " . $value['sku_stock_qty_pallet'] . " AS sku_stock_qty_pallet, " . $value['sku_stock_qty'] . " AS sku_stock_qty";

				if ($key < count($arr_konversi_sku_pallet_asal) - 1) {
					$table_sementara2 .= $union2;
				}
			}

			$column_tr_konversi_sku_detail_qty_plan = "SUM(pallet_asal.sku_stock_qty)";

			$join_table_sementara2 = "LEFT JOIN (SELECT
											table_sementara.tr_konversi_sku_detail_id,
											table_sementara.tr_konversi_sku_id,
											table_sementara.sku_stock_id,
											sku.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.sku_satuan,
											sku.sku_kemasan,
											sku.sku_konversi_group,
											sku.sku_konversi_level,
											sku.sku_konversi_faktor,
											SUM(table_sementara.sku_stock_qty_pallet) AS sku_stock_qty_pallet,
											SUM(table_sementara.sku_stock_qty) AS sku_stock_qty
										FROM (" . $table_sementara2 . ") table_sementara
										LEFT JOIN sku_stock
										ON sku_stock.sku_stock_id = table_sementara.sku_stock_id
										LEFT JOIN sku
										ON sku.sku_id = sku_stock.sku_id
										group by table_sementara.tr_konversi_sku_detail_id,
											table_sementara.tr_konversi_sku_id,
											table_sementara.sku_stock_id,
											sku.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.sku_satuan,
											sku.sku_kemasan,
											sku.sku_konversi_group,
											sku.sku_konversi_level,
											sku.sku_konversi_faktor) AS pallet_asal 
											ON pallet_asal.tr_konversi_sku_detail_id = konversi.tr_konversi_sku_detail_id";
		}

		// $query = "SELECT
		// sku.sku_konversi_group,
		// SUM(ISNULL(konversi.tr_konversi_sku_detail_qty_plan, 0)) AS tr_konversi_sku_detail_qty_plan,
		// SUM(konversi_hasil.sku_qty_konversi_plan) AS sku_qty_konversi_plan,
		// SUM(konversi_hasil.sku_qty) AS sku_qty,
		// SUM(konversi_hasil.sku_qty_sisa_konversi) AS sku_qty_sisa_konversi_a,
		// SUM(ISNULL(konversi.tr_konversi_sku_detail_qty_plan, 0)) - SUM(ISNULL(konversi_hasil.sku_qty, 0)) AS sku_qty_sisa_konversi,
		// CASE WHEN SUM(ISNULL(konversi.tr_konversi_sku_detail_qty_plan, 0)) - SUM(ISNULL(konversi_hasil.sku_qty, 0)) < 0 THEN '1' ELSE '0' END is_selisih
		// FROM tr_konversi_sku_detail konversi
		// LEFT JOIN sku
		// ON konversi.sku_id = sku.sku_id
		// LEFT JOIN (
		// SELECT
		// 	sku_konversi_group,
		// 	SUM(ISNULL(sku_qty_konversi_plan, 0)) AS sku_qty_konversi_plan,
		// 	SUM(ISNULL(sku_qty, 0)) AS sku_qty,
		// 	SUM(ISNULL(sku_qty_sisa_konversi, 0)) AS sku_qty_sisa_konversi
		// FROM (" . $table_sementara . ") a
		// GROUP BY sku_konversi_group
		// ) konversi_hasil
		// ON konversi_hasil.sku_konversi_group = sku.sku_konversi_group
		// WHERE CONVERT(nvarchar(36), konversi.tr_konversi_sku_id) = '$tr_konversi_sku_id'
		// GROUP BY sku.sku_konversi_group
		// ORDER BY sku.sku_konversi_group ASC";

		if (count($arr_list_hasil_sku_konversi_pengemasan) > 0) {

			$query = $this->db->query("SELECT
									sku.sku_konversi_group,
									" . $column_tr_konversi_sku_detail_qty_plan . " AS tr_konversi_sku_detail_qty_plan,
									SUM(konversi_hasil.sku_qty_konversi_plan) AS sku_qty_konversi_plan,
									SUM(konversi_hasil.sku_qty) AS sku_qty,
									SUM(konversi_hasil.sku_qty_sisa_konversi) AS sku_qty_sisa_konversi_a,
									" . $column_tr_konversi_sku_detail_qty_plan . " - SUM(ISNULL(konversi_hasil.sku_qty, 0)) AS sku_qty_sisa_konversi,
									CASE WHEN " . $column_tr_konversi_sku_detail_qty_plan . " - SUM(ISNULL(konversi_hasil.sku_qty, 0)) < 0 THEN '1' ELSE '0' END is_selisih
									FROM tr_konversi_sku_detail konversi
									LEFT JOIN sku
									ON konversi.sku_id = sku.sku_id
									LEFT JOIN (
									SELECT
										a.sku_konversi_group,
										SUM(ISNULL(sku_qty_konversi_plan, 0)) AS sku_qty_konversi_plan,
										SUM(ISNULL(sku_qty, 0) / CONVERT(INT,ISNULL(sku_awal.sku_konversi_faktor,0))) AS sku_qty,
										SUM(ISNULL(sku_qty_sisa_konversi, 0) / CONVERT(INT,ISNULL(sku_awal.sku_konversi_faktor,0))) AS sku_qty_sisa_konversi
									FROM (" . $table_sementara . ") a
									LEFT JOIN sku sku_awal
									ON sku_awal.sku_id = CONVERT(NVARCHAR(36),a.sku_id_awal)
									GROUP BY a.sku_konversi_group, ISNULL(sku_awal.sku_konversi_faktor,0)
									) konversi_hasil
									ON konversi_hasil.sku_konversi_group = sku.sku_konversi_group
									" . $join_table_sementara2 . "
									WHERE CONVERT(nvarchar(36), konversi.tr_konversi_sku_id) = '$tr_konversi_sku_id'
									GROUP BY sku.sku_konversi_group
									ORDER BY sku.sku_konversi_group ASC");

			if ($query->num_rows() == 0) {
				$query = array();
			} else {
				$query = $query->result_array();
			}
		} else {

			$query = $this->db->query("SELECT
									sku.sku_konversi_group,
									" . $column_tr_konversi_sku_detail_qty_plan . " AS tr_konversi_sku_detail_qty_plan,
									0 AS sku_qty_konversi_plan,
									SUM(ISNULL(konversi.tr_konversi_sku_detail_qty_plan, 0)) AS sku_qty_sisa_konversi,
									'0' AS is_selisih
									FROM tr_konversi_sku_detail konversi
									LEFT JOIN sku
									ON konversi.sku_id = sku.sku_id
									" . $join_table_sementara2 . "
									WHERE CONVERT(nvarchar(36), konversi.tr_konversi_sku_id) = '$tr_konversi_sku_id'
									GROUP BY sku.sku_konversi_group
									ORDER BY sku.sku_konversi_group ASC");

			if ($query->num_rows() == 0) {
				$query = array();
			} else {
				$query = $query->result_array();
			}
		}

		return $query;
	}

	public function Exec_proses_konversi_sku_packing($sku_id, $sku_qty, $sku_satuan)
	{
		$query = $this->db->query("Exec proses_konversi_sku_packing '$sku_id', " . $sku_qty . ",'$sku_satuan'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_tr_konversi_sku_detail_by_sku_id($tr_konversi_sku_id, $sku_id_awal, $sku_id, $sku_stock_id, $tr_konversi_sku_detail2_qty, $tr_konversi_sku_detail2_qty_result, $pallet_id_tujuan)
	{

		$query = $this->db->query("SELECT
									NEWID() AS tr_konversi_sku_detail2_id,
									tr_konversi_sku_detail_id,
									'$sku_id' AS sku_id,
									'$sku_stock_id' AS sku_stock_id,
									'$tr_konversi_sku_detail2_qty' AS tr_konversi_sku_detail2_qty,
									'$tr_konversi_sku_detail2_qty_result' AS tr_konversi_sku_detail2_qty_result,
									NULL AS pallet_id_asal,
									'$pallet_id_tujuan' AS pallet_id_tujuan
									FROM tr_konversi_sku_detail
									WHERE tr_konversi_sku_id = '$tr_konversi_sku_id'
									AND sku_id = '$sku_id_awal'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function delete_tr_konversi_sku_detail2_by_tr_konversi_sku_detail_id($id)
	{
		$this->db->where('tr_konversi_sku_detail_id', $id);
		return $this->db->delete('tr_konversi_sku_detail2');
	}

	public function GetHasilKonversiPengemasan($tr_konversi_sku_id)
	{

		$query = $this->db->query("SELECT
									dtl.tr_konversi_sku_detail_id,
									dtl.sku_id AS sku_id_awal,
									dtl2.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									sku.sku_satuan,
									sku.sku_kemasan,
									FORMAT(dtl.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
									ISNULL(dtl2.tr_konversi_sku_detail2_qty, 0) AS sku_qty,
									ISNULL(dtl2.tr_konversi_sku_detail2_qty, 0) AS sku_qty_konversi_plan,
									ISNULL(dtl2.tr_konversi_sku_detail2_qty_result, 0) AS sku_qty_hasil_konversi,
									ISNULL(dtl.tr_konversi_sku_detail_qty_plan, 0) - ISNULL(dtl2.tr_konversi_sku_detail2_qty, 0) AS sku_qty_sisa_konversi
									FROM tr_konversi_sku_detail dtl
									LEFT JOIN tr_konversi_sku_detail2 dtl2
									ON dtl2.tr_konversi_sku_detail_id = dtl.tr_konversi_sku_detail_id
									LEFT JOIN sku
									ON sku.sku_id = dtl2.sku_id
									WHERE dtl.tr_konversi_sku_id = '$tr_konversi_sku_id'
									ORDER BY sku.sku_konversi_group ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetHasilKonversiPengemasan_detail($tr_konversi_sku_id)
	{

		$query = $this->db->query("SELECT
									dtl.tr_konversi_sku_detail_id,
									dtl.sku_id AS sku_id_awal,
									dtl2.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_konversi_group,
									sku.sku_satuan,
									sku.sku_kemasan,
									FORMAT(sku_stock.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
									ISNULL(dtl2.tr_konversi_sku_detail2_qty, 0) AS sku_qty,
									ISNULL(dtl2.tr_konversi_sku_detail2_qty, 0) AS sku_qty_konversi_plan,
									ISNULL(dtl2.tr_konversi_sku_detail2_qty_result, 0) AS sku_qty_hasil_konversi
									FROM tr_konversi_sku_detail dtl
									LEFT JOIN tr_konversi_sku_detail2 dtl2
									ON dtl2.tr_konversi_sku_detail_id = dtl.tr_konversi_sku_detail_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = dtl2.sku_stock_id
									LEFT JOIN sku
									ON sku.sku_id = dtl2.sku_id
									WHERE dtl.tr_konversi_sku_id = '$tr_konversi_sku_id'
									ORDER BY sku.sku_konversi_group ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetKonversiPalletAsal($tr_konversi_sku_detail_id)
	{

		$query = $this->db->query("SELECT
									a.tr_konversi_sku_pallet_asal_id,
									a.tr_konversi_sku_detail_id,
									a.tr_konversi_sku_id,
									a.pallet_id_asal,
									pallet.pallet_kode,
									a.sku_stock_id,
									SUM(b.tr_konversi_sku_detail2_qty) AS sku_stock_qty_ambil
									FROM tr_konversi_sku_pallet_asal a
									LEFT JOIN tr_konversi_sku_detail2 b
									ON b.tr_konversi_sku_detail_id = a.tr_konversi_sku_detail_id
									LEFT JOIN pallet
									ON pallet.pallet_id = a.pallet_id_asal
									WHERE a.tr_konversi_sku_detail_id = '$tr_konversi_sku_detail_id'
									GROUP BY a.tr_konversi_sku_pallet_asal_id,
											a.tr_konversi_sku_detail_id,
											a.tr_konversi_sku_id,
											a.pallet_id_asal,
											pallet.pallet_kode,
											a.sku_stock_id
									ORDER BY pallet.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_pallet_tujuan_by_tr_konversi_sku_id($tr_konversi_sku_id)
	{

		$query = $this->db->query("SELECT
									dtl2.pallet_id_tujuan AS pallet_id,
									pallet.pallet_kode
									FROM tr_konversi_sku_detail dtl
									LEFT JOIN tr_konversi_sku_detail2 dtl2
									ON dtl2.tr_konversi_sku_detail_id = dtl.tr_konversi_sku_detail_id
									LEFT JOIN pallet
									ON pallet.pallet_id = dtl2.pallet_id_tujuan
									WHERE dtl.tr_konversi_sku_id = '$tr_konversi_sku_id'
									GROUP BY dtl2.pallet_id_tujuan,
											pallet.pallet_kode
									ORDER BY pallet.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_pallet_asal_by_tr_konversi_sku_id($tr_konversi_sku_id)
	{

		$query = $this->db->query("SELECT
									pa.tr_konversi_sku_pallet_asal_id,
									CONVERT(nvarchar(36), pa.tr_konversi_sku_detail_id) AS tr_konversi_sku_detail_id,
									CONVERT(nvarchar(36), pa.tr_konversi_sku_id) AS tr_konversi_sku_id,
									ISNULL(CONVERT(nvarchar(36), pa.pallet_id_asal), '') AS pallet_id_asal,
									ISNULL(CONVERT(nvarchar(36), pa.sku_stock_id), '') AS sku_stock_id,
									ISNULL(pd.sku_stock_qty, 0) + ISNULL(pd.sku_stock_terima, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_ambil, 0) - ISNULL(pd.sku_stock_out, 0) AS sku_stock_qty_pallet,
									ISNULL(pa.sku_stock_qty, 0) AS sku_stock_qty
									FROM tr_konversi_sku_pallet_asal pa
									LEFT JOIN pallet_detail pd
									ON pd.pallet_id = pa.pallet_id_asal
									AND pd.sku_stock_id = pa.sku_stock_id
									WHERE CONVERT(nvarchar(36), pa.tr_konversi_sku_id) = '$tr_konversi_sku_id'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
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

	public function update_tr_konversi_sku_detail2_sku_stock_tujuan($tr_konversi_sku_detail2_id, $sku_stock_id)
	{

		$this->db->set('sku_stock_id', $sku_stock_id);
		$this->db->where('tr_konversi_sku_detail2_id', $tr_konversi_sku_detail2_id);

		$this->db->update("tr_konversi_sku_detail2");

		// $affectedrows = $this->db->affected_rows();
		// if ($affectedrows > 0) {
		// 	$queryinsert = 1;
		// } else {
		// 	$queryinsert = 0;
		// }

		// return $queryinsert;
		return $this->db->last_query();
	}

	public function Get_sku_stock_by_tr_konversi_sku_id($tr_konversi_sku_detail_id)
	{
		$query = $this->db->query("SELECT
									dtl.tr_konversi_sku_detail_id,
									dtl.tr_konversi_sku_id,
									dtl.sku_id,
									dtl.sku_stock_id,
									dtl.sku_stock_expired_date,
									dtl.tr_konversi_sku_detail_qty_plan,
									SUM(dtl2.tr_konversi_sku_detail2_qty) AS tr_konversi_sku_detail_qty_aktual,
									dtl.sku_konversi_hasil
									FROM tr_konversi_sku_detail dtl
									LEFT JOIN tr_konversi_sku_detail2 dtl2
									ON dtl2.tr_konversi_sku_detail_id = dtl.tr_konversi_sku_detail_id
									WHERE dtl.tr_konversi_sku_detail_id = '$tr_konversi_sku_detail_id'
									GROUP BY dtl.tr_konversi_sku_detail_id,
											dtl.tr_konversi_sku_id,
											dtl.sku_id,
											dtl.sku_stock_id,
											dtl.sku_stock_expired_date,
											dtl.tr_konversi_sku_detail_qty_plan,
											dtl.sku_konversi_hasil
									ORDER BY dtl.sku_stock_id ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_pallet_by_sku_stock_id($sku_stock_id)
	{
		// $filter_pallet_asal_str = "";

		// if (count($filter_pallet_asal) > 0) {
		// 	$filter_pallet_asal_str = "AND a.pallet_id NOT IN (" . implode(",", $filter_pallet_asal) . ")";
		// } else {
		// 	$filter_pallet_asal_str = "";
		// }

		$query = $this->db->query("SELECT
									a.pallet_id,
									a.pallet_kode,
									b.sku_stock_id,
									b.sku_stock_expired_date,
									SUM(ISNULL(b.sku_stock_qty, 0) + ISNULL(b.sku_stock_terima, 0) + ISNULL(b.sku_stock_in, 0) - ISNULL(b.sku_stock_ambil, 0) - ISNULL(b.sku_stock_out, 0)) AS sku_stock_qty
									FROM pallet a
									LEFT JOIN pallet_detail b
									ON b.pallet_id = a.pallet_id
									WHERE b.sku_stock_id = '$sku_stock_id'
									GROUP BY a.pallet_id,
											 a.pallet_kode,
											 b.sku_stock_id,
											 b.sku_stock_expired_date
									ORDER BY a.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetListPalletAsal($sku_stock_id, $arr_konversi_sku_pallet_asal)
	{
		$union = " UNION ALL ";
		$table_sementara = "";


		foreach ($arr_konversi_sku_pallet_asal as $key => $value) {

			$table_sementara .= "SELECT '" . $value['idx'] . "' AS idx, '" . $value['tr_konversi_sku_detail_id'] . "' AS tr_konversi_sku_detail_id,'" . $value['tr_konversi_sku_id'] . "' AS tr_konversi_sku_id, '" . $value['pallet_id_asal'] . "' AS pallet_id_asal, '" . $value['sku_stock_id'] . "' AS sku_stock_id, '" . $value['sku_stock_qty_pallet'] . "' AS sku_stock_qty_pallet, '" . $value['sku_stock_qty'] . "' AS sku_stock_qty";

			if ($key < count($arr_konversi_sku_pallet_asal) - 1) {
				$table_sementara .= $union;
			}
		}

		$query = $this->db->query("SELECT
									idx,
									tr_konversi_sku_detail_id,
									tr_konversi_sku_id,
									pallet_id_asal,
									sku_stock_id,
									sku_stock_qty_pallet,
									sku_stock_qty
									FROM (" . $table_sementara . ") a
									WHERE sku_stock_id = '$sku_stock_id'
									ORDER BY idx ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function insert_tr_konversi_sku_pallet_asal($tr_konversi_sku_pallet_asal_id, $tr_konversi_sku_detail_id, $tr_konversi_sku_id, $pallet_id_asal, $sku_stock_id, $sku_stock_qty)
	{
		$tr_konversi_sku_detail_id = $tr_konversi_sku_detail_id == "" ? null : $tr_konversi_sku_detail_id;
		$tr_konversi_sku_id = $tr_konversi_sku_id == "" ? null : $tr_konversi_sku_id;
		$pallet_id_asal = $pallet_id_asal == "" ? null : $pallet_id_asal;
		$sku_stock_id = $sku_stock_id == "" ? null : $sku_stock_id;
		$sku_stock_qty = $sku_stock_qty == "" ? null : $sku_stock_qty;

		$this->db->set("tr_konversi_sku_pallet_asal_id", $tr_konversi_sku_pallet_asal_id);
		$this->db->set("tr_konversi_sku_detail_id", $tr_konversi_sku_detail_id);
		$this->db->set("tr_konversi_sku_id", $tr_konversi_sku_id);
		$this->db->set("pallet_id_asal", $pallet_id_asal);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_stock_qty", $sku_stock_qty);

		$queryinsert = $this->db->insert("tr_konversi_sku_pallet_asal");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function delete_tr_konversi_sku_pallet_asal($tr_konversi_sku_id)
	{
		$this->db->where("tr_konversi_sku_id", $tr_konversi_sku_id);
		$queryinsert = $this->db->delete("tr_konversi_sku_pallet_asal");

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Get_tr_konversi_sku_pallet_asal_by_tr_konversi_sku_id($tr_konversi_sku_id)
	{

		$query = $this->db->query("SELECT * FROM tr_konversi_sku_pallet_asal WHERE tr_konversi_sku_id = '$tr_konversi_sku_id'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_tr_konversi_sku_pallet_asal_by_tr_konversi_sku_detail_id($tr_konversi_sku_detail_id)
	{

		$query = $this->db->query("SELECT * FROM tr_konversi_sku_pallet_asal WHERE tr_konversi_sku_detail_id = '$tr_konversi_sku_detail_id'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
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
}
