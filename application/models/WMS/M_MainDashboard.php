<?php
class M_MainDashboard extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function getPerusahan()
	{
		if ($this->session->userdata('client_wms_id') == "") {
			$query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0
						order by b.client_wms_nama asc");
		} else {
			$query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.client_wms_id = '" . $this->session->userdata('client_wms_id') . "'
						AND a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0
						order by b.client_wms_nama asc");
		}

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getStatusDO($DepoID, $tgl1, $tgl2)
	{
		$query = $this->db->query("
		SELECT 'In Progress' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = '$DepoID'
		AND delivery_order_status = 'in progress'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Progress Item Request' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in progress item request'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Progress Pick Up Item' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in progress pick up item'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Pick Up Item Confirmed' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'pick up item confirmed'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Progress Packing Item' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in progress packing item'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Packing Item Confirmed' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'packing item confirmed'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Transit Validation' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in transit validation'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Transit Validation Completed' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in transit validation completed'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Transit' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in transit'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Delivered' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'delivered'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Partially Delivered' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'partially delivered'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Not Delivered' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'not delivered'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Canceled' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'canceled'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}



	public function GetListDOByStatus($status, $tgl1, $tgl2)
	{
		$query = $this->db->select('delivery_order_id as do_id, delivery_order_tgl_aktual_kirim as tgl, delivery_order_kode as kode, delivery_order_kirim_nama as nama, delivery_order_kirim_alamat as alamat');
		$this->db->from('delivery_order');
		$this->db->where('delivery_order_status', $status);
		$this->db->where("format(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') >=", $tgl1);
		$this->db->where("format(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') <=", $tgl2);
		$this->db->order_by('tgl', 'ASC');

		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return $query->result_array();
		}
	}

	public function generateCardDashboard($tgl1 = null, $tgl2 = null, $mode = null, $type = null)
	{

		$datePickingOrder = "";
		$dateMutasiPallet = "";
		$dateKoreksiBarang = "";
		$datePemusnahan = "";
		$datePermintaanBarang = "";
		$datePengemasanBarang = "";
		$dateKoreksiPallet = "";
		$dateStockOpname = "";
		$datePenerimaanPembelian = "";
		$orderBy = "";

		if ($mode != null) {
			if ($mode == 0) {
				$datePickingOrder .= "AND dbo.floordate(po.picking_order_tanggal) = '$tgl1'";
			} else {
				$datePickingOrder .= "AND dbo.floordate(po.picking_order_tanggal) BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$dateMutasiPallet .= "AND dbo.floordate(tmpd.tr_mutasi_pallet_draft_tgl_create) = '$tgl1'";
			} else {
				$dateMutasiPallet .= "AND dbo.floordate(tmpd.tr_mutasi_pallet_draft_tgl_create) BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$dateKoreksiBarang .= "AND dbo.floordate(a.tr_koreksi_stok_draft_tgl_create) = '$tgl1'";
			} else {
				$dateKoreksiBarang .= "AND dbo.floordate(a.tr_koreksi_stok_draft_tgl_create) BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$datePemusnahan .= "AND dbo.floordate(a.tr_koreksi_stok_draft_tgl_create) = '$tgl1'";
			} else {
				$datePemusnahan .= "AND dbo.floordate(a.tr_koreksi_stok_draft_tgl_create) BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$datePermintaanBarang .= "AND dbo.floordate(picking_list_create_tgl) = '$tgl1'";
			} else {
				$datePermintaanBarang .= "AND dbo.floordate(picking_list_create_tgl) BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$datePengemasanBarang .= "AND dbo.floordate(pl.picking_list_create_tgl) = '$tgl1'";
			} else {
				$datePengemasanBarang .= "AND dbo.floordate(pl.picking_list_create_tgl) BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$dateKoreksiPallet .= "AND dbo.floordate(tksp.tr_koreksi_stok_pallet_tgl_create) = '$tgl1'";
			} else {
				$dateKoreksiPallet .= "AND dbo.floordate(tksp.tr_koreksi_stok_pallet_tgl_create) BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$dateStockOpname .= "AND dbo.floordate(tpo.tr_opname_plan_tgl_create) = '$tgl1'";
			} else {
				$dateStockOpname .= "AND dbo.floordate(tpo.tr_opname_plan_tgl_create) BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$datePenerimaanPembelian .= "AND dbo.floordate(penerimaan_pembelian_tgl) = '$tgl1'";
			} else {
				$datePenerimaanPembelian .= "AND dbo.floordate(penerimaan_pembelian_tgl) BETWEEN '$tgl1' AND '$tgl2'";
			}
		} else if ($type == "opname") {
			if ($this->session->userdata('karyawan_id') == "AD030BE3-7D9E-4A66-B985-D6085F623DA2") {
				$datePickingOrder .= "";
				$dateMutasiPallet .= "";
				$dateKoreksiBarang .= "";
				$datePemusnahan .= "";
				$datePermintaanBarang .= "";
				$datePengemasanBarang .= "";
				$dateKoreksiPallet .= "";
				$dateStockOpname .= "";
			} else {
				$datePickingOrder .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "'";
				$dateMutasiPallet .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "'";
				$dateKoreksiBarang .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "'";
				$datePemusnahan .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "'";
				$datePermintaanBarang .= "";
				$datePengemasanBarang .= "";
				$dateKoreksiPallet .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "'";
				$dateStockOpname .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "' AND tpo.depo_id = '" . $this->session->userdata('depo_id') . "'";
			}


			$orderBy .= "order by a.urut DESC";
		} else {
			$datePickingOrder .= "AND dbo.floordate(po.picking_order_tanggal) = '$tgl1'";
			$dateMutasiPallet .= "AND dbo.floordate(tmpd.tr_mutasi_pallet_draft_tgl_create) = '$tgl1'";
			$dateKoreksiBarang .= "AND dbo.floordate(a.tr_koreksi_stok_draft_tgl_create) = '$tgl1'";
			$datePemusnahan .= "AND dbo.floordate(a.tr_koreksi_stok_draft_tgl_create) = '$tgl1'";
			$datePermintaanBarang .= "AND dbo.floordate(picking_list_create_tgl) = '$tgl1'";
			$datePengemasanBarang .= "AND dbo.floordate(pl.picking_list_create_tgl) = '$tgl1'";
			$dateKoreksiPallet .= "AND dbo.floordate(tksp.tr_koreksi_stok_pallet_tgl_create) = '$tgl1'";
			$dateStockOpname .= "AND dbo.floordate(tpo.tr_opname_plan_tgl_create) = '$tgl1'";
			$datePenerimaanPembelian .= "AND dbo.floordate(penerimaan_pembelian_tgl) = '$tgl1'";

			$orderBy .= "order by a.urut ASC";
		}

		return $this->db->query("SELECT * 
										FROM (
											SELECT
												1 AS urut,
												'CAPTION-130005007' AS bahasa,
												'PengeluaranBarang' AS modul,
												'fas fa-box-open' AS icon,
												COUNT(pop.picking_order_id) AS jum,
												'WMS/Distribusi/PengeluaranBarang/PengeluaranBarangMenu' AS url
											FROM picking_order_plan pop
											INNER JOIN picking_order po 
												ON pop.picking_order_id = po.picking_order_id
											WHERE pop.picking_order_plan_status IN('Open','In Progress') $datePickingOrder
											AND po.depo_id = '" . $this->session->userdata('depo_id') . "'

											UNION ALL

											SELECT
												2 AS urut, 'CAPTION-MUTASIPALLET', 'MutasiPallet',
												'fas fa-boxes',
												COUNT(tmpd.tr_mutasi_pallet_draft_id),
												'WMS/MutasiPallet/MutasiPalletMenu'
											FROM tr_mutasi_pallet_draft tmpd
											WHERE tmpd.tr_mutasi_pallet_draft_status IN('Approved','In Progress') $dateMutasiPallet 
											AND tmpd.depo_id_asal = '" . $this->session->userdata('depo_id') . "'

											UNION ALL

											SELECT
												3 AS urut, 'CAPTION-120009000', 'KoreksiBarang',
												'fas fa-people-carry',
												COUNT(a.tr_koreksi_stok_draft_id),
												'WMS/KoreksiStokBarang/KoreksiStokBarangMenu'
											FROM tr_koreksi_stok_draft a
											INNER JOIN tipe_mutasi tm ON a.tipe_mutasi_id = tm.tipe_mutasi_id
											WHERE tm.tipe_mutasi_nama IN ('Koreksi Masuk', 'Koreksi Keluar')
											$dateKoreksiBarang 
											AND a.depo_id_asal = '" . $this->session->userdata('depo_id') . "'

											UNION ALL

											SELECT
												4 AS urut, 'CAPTION-120015000', 'Pemusnahan',
												'fas fa-dolly',
												COUNT(a.tr_koreksi_stok_draft_id),
												'WMS/KoreksiStokBarang/KoreksiStokBarangMenu'
											FROM tr_koreksi_stok_draft a
											INNER JOIN tipe_mutasi tm ON a.tipe_mutasi_id = tm.tipe_mutasi_id 
											WHERE tm.tipe_mutasi_id IN (
													'9246A374-B798-442B-8EE0-631EE6ADA7C9',
													'86A1887B-28F2-4F81-86C1-84650B2F2FEC'
												)
											AND a.tr_koreksi_stok_draft_status IN('Approved','In Progress')
											$datePemusnahan 
											AND a.depo_id_asal = '" . $this->session->userdata('depo_id') . "'

											UNION ALL

											SELECT
												5 AS urut, 'CAPTION-130005006', 'PermintaanBarang',
												'fa-solid fa-hands-holding-circle',
												COUNT(picking_list_id),
												'WMS/Distribusi/PermintaanBarang/PermintaanBarangMenu'
											FROM picking_list
											WHERE picking_list_status IN('Open','In Progress') $datePermintaanBarang 
											AND depo_id = '" . $this->session->userdata('depo_id') . "'

											UNION ALL

											SELECT
												6 AS urut, 'CAPTION-130005009', 'PengemasanBarang',
												'fa-solid fa-truck-ramp-box',
												COUNT(pl.picking_list_id),
												'WMS/Distribusi/PengemasanBarang/PengemasanBarangMenu'
											FROM picking_list pl
											INNER JOIN delivery_order_batch dob ON pl.delivery_order_batch_id = dob.delivery_order_batch_id
											INNER JOIN picking_order po ON dob.picking_order_id = po.picking_order_id
											WHERE pl.picking_list_status in('Open', 'In Process') $datePengemasanBarang
											AND pl.depo_id = '" . $this->session->userdata('depo_id') . "'
											AND po.depo_id = '" . $this->session->userdata('depo_id') . "'

											UNION ALL

											SELECT
												7 AS urut, 'CAPTION-120011000', 'KoreksiPallet',
												'fa-solid fa-pallet',
												COUNT(tksp.tr_koreksi_stok_pallet_id),
												'WMS/KoreksiPallet/KoreksiPalletMenu'
											FROM tr_koreksi_stok_pallet tksp
											WHERE tksp.tr_koreksi_stok_pallet_status IN('Open','In Progress')  $dateKoreksiPallet
											AND tksp.depo_id_asal = '" . $this->session->userdata('depo_id') . "'

											UNION ALL

											SELECT
												8 AS urut, 'CAPTION-123004000', 'StockOpname',
												'fa-solid fa-magnifying-glass',
												COUNT(tpo.tr_opname_plan_id),
												'WMS/ProsesOpname/ProsesOpnameMenu'
											FROM tr_opname_plan tpo
											WHERE tpo.tr_opname_plan_status IN('Approved', 'In Progress') $dateStockOpname
											AND tpo.depo_id = '" . $this->session->userdata('depo_id') . "'

											UNION ALL

											SELECT
												9 AS urut, 'CAPTION-126006000', 'PenerimaanBarang',
												'fas fa-box-open',
												COUNT(penerimaan_pembelian_id),
												'WMS/PenerimaanBarang/PenerimaanBarangMenu'
											FROM penerimaan_pembelian
											WHERE penerimaan_pembelian_status NOT IN('Close') $datePenerimaanPembelian
											AND depo_id = '" . $this->session->userdata('depo_id') . "'
										) AS a
										$orderBy
										")->result();
		// return $this->db->last_query();
	}

	public function getSKUNotInWMS()
	{
		return $this->db->query("SELECT DISTINCT principle_kode, sku_konversi_group, sku_nama_produk, datasource FROM
														(
																SELECT principle_kode as principle_kode, sku_konversi_group as sku_konversi_group, sku_nama_produk as sku_nama_produk, 'Bosnet' as datasource
																	FROM sku_import_temp
																	where sku_konversi_group not in (SELECT sku_konversi_group FROM sku)
																union
																SELECT b.principle_kode as principle_kode, a.sku_kode as sku_konversi_group, a.sku_nama_produk as sku_nama_produk, 'ASN' as datasource
																	FROM penerimaan_surat_jalan_temp a
														inner join principle b on a.principle_id = b.principle_id
																	where a.sku_kode not in (SELECT sku_konversi_group FROM sku)
																
														) as skubelumterdaftar
														order by principle_kode, sku_konversi_group")->result();
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
                                    SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty_result, 0)) AS tr_konversi_sku_detail2_qty_result
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

	public function getReportDashboardDOI($client_wms_id, $principle_id, $doi_internal, $month)
	{
		if ($client_wms_id == '') {
			$filter_client_wms_id = 'null';
		} else {
			$filter_client_wms_id = "'" . $client_wms_id . "'";
		}

		if ($principle_id == '') {
			$filter_principle_id = 'null';
		} else {
			$filter_principle_id = "'" . $principle_id . "'";
		}

		return $this->db->query("EXEC report_dashboard_doi " . $filter_client_wms_id . ", " . $filter_principle_id . ", '" . $this->session->userdata('depo_id') . "', " . $doi_internal . ", " . $month . "")->result_array();
	}

	public function getDashboardSJSupplier($client_wms_id, $principle_id, $mode, $tgl1, $tgl2)
	{
		if ($client_wms_id == '') {
			$filter_client_wms_id = 'null';
		} else {
			$filter_client_wms_id = "'" . $client_wms_id . "'";
		}

		if ($principle_id == '') {
			$filter_principle_id = 'null';
		} else {
			$filter_principle_id = "'" . $principle_id . "'";
		}

		return $this->db->query("EXEC report_dashboard_penerimaan_supplier '" . $this->session->userdata('depo_id') . "', " . $filter_client_wms_id . ", " . $filter_principle_id . ", '" . $mode . "', '" . $tgl1 . "', '" . $tgl2 . "'")->result_array();
	}

	public function getDashboardTruckSupplier($client_wms_id, $principle_id, $mode, $tgl1, $tgl2)
	{
		if ($client_wms_id == '') {
			$filter_client_wms_id = 'null';
		} else {
			$filter_client_wms_id = "'" . $client_wms_id . "'";
		}

		if ($principle_id == '') {
			$filter_principle_id = 'null';
		} else {
			$filter_principle_id = "'" . $principle_id . "'";
		}

		return $this->db->query("EXEC report_dashboard_truck_supplier '" . $this->session->userdata('depo_id') . "', " . $filter_client_wms_id . ", " . $filter_principle_id . ", '" . $mode . "', '" . $tgl1 . "', '" . $tgl2 . "'")->result_array();
	}

	public function getDashboardStock($client_wms_id, $principle_id, $depo_detail)
	{
		if ($client_wms_id == '') {
			$filter_client_wms_id = 'null';
		} else {
			$filter_client_wms_id = "'" . $client_wms_id . "'";
		}

		if ($principle_id == '') {
			$filter_principle_id = 'null';
		} else {
			$filter_principle_id = "'" . $principle_id . "'";
		}

		if ($depo_detail == '') {
			$filter_depo_detail = 'null';
		} else {
			$filter_depo_detail = "'" . $depo_detail . "'";
		}

		return $this->db->query("EXEC report_dashboard_stock " . $filter_client_wms_id . ", '" . $this->session->userdata('depo_id') . "', " . $filter_depo_detail . ", " . $filter_principle_id . "")->result_array();
	}

	public function getDashboardServiceLevel($client_wms_id, $principle_id, $tipe, $mode, $tgl1, $tgl2)
	{
		if ($client_wms_id == '') {
			$filter_client_wms_id = 'null';
		} else {
			$filter_client_wms_id = "'" . $client_wms_id . "'";
		}

		if ($principle_id == '') {
			$filter_principle_id = 'null';
		} else {
			$filter_principle_id = "'" . $principle_id . "'";
		}

		if ($tipe == '') {
			$filter_tipe = 'null';
		} else {
			$filter_tipe = "'" . $tipe . "'";
		}

		return $this->db->query("EXEC report_dashboard_service_level '" . $this->session->userdata('depo_id') . "', " . $filter_client_wms_id . ", " . $filter_principle_id . "," . $filter_tipe . ", '" . $mode . "', '" . $tgl1 . "', '" . $tgl2 . "'")->result_array();
	}

	public function getDashboardStatusDO($client_wms_id, $principle_id, $status, $mode, $tgl1, $tgl2)
	{
		if ($client_wms_id == '') {
			$filter_client_wms_id = 'null';
		} else {
			$filter_client_wms_id = "'" . $client_wms_id . "'";
		}

		if ($principle_id == '') {
			$filter_principle_id = 'null';
		} else {
			$filter_principle_id = "'" . $principle_id . "'";
		}

		if ($status == '') {
			$filter_status = 'null';
		} else {
			$filter_status = "'" . $status . "'";
		}

		return $this->db->query("EXEC report_dashboard_status_do '" . $this->session->userdata('depo_id') . "', " . $filter_client_wms_id . ", " . $filter_principle_id . "," . $filter_status . ", '" . $mode . "', '" . $tgl1 . "', '" . $tgl2 . "'")->result_array();
	}

	public function getDashboardFleetProductivity($mode, $flag, $status_assign, $value, $tgl1, $tgl2, $start, $length, $search, $draw, $area)
	{
		if ($status_assign == '') {
			$filter_status_assign = 'null';
		} else {
			$filter_status_assign = "'" . $status_assign . "'";
		}

		if ($value == '') {
			$filter_value = 'null';
		} else {
			$filter_value = "'" . $value . "'";
		}

		if ($start == null) {
			$start = 'null';
		} else {
			$start = "'" . $start . "'";
		}

		if ($length == null) {
			$length = 'null';
		} else {
			$length = "'" . $length . "'";
		}

		if ($search == null) {
			$search = 'null';
		} else {
			$search = "'" . $search . "'";
		}

		if ($area == '') {
			$filter_area = 'null';
		} else {
			$filter_area = "'" . $area . "'";
		}

		if ($flag != 'list_detail_kendaraan_model') {
			return $this->db->query("EXEC report_dashboard_fleet_productivity '" . $this->session->userdata('depo_id') . "', '" . $mode . "', '" . $flag . "', $filter_status_assign, '" . $tgl1 . "', '" . $tgl2 . "', $filter_value, $start, $length, $search, null, $filter_area")->result_array();
		} else {
			if ($draw == null) {
				return $this->db->query("EXEC report_dashboard_fleet_productivity '" . $this->session->userdata('depo_id') . "', '" . $mode . "', '" . $flag . "', $filter_status_assign, '" . $tgl1 . "', '" . $tgl2 . "', $filter_value, $start, $length, $search, null, $filter_area")->result_array();
			} else {
				$data = $this->db->query("EXEC report_dashboard_fleet_productivity '" . $this->session->userdata('depo_id') . "', '" . $mode . "', '" . $flag . "', $filter_status_assign, '" . $tgl1 . "', '" . $tgl2 . "', $filter_value, $start, $length, $search, 'data', $filter_area")->result_array();
				$jumlah = $this->db->query("EXEC report_dashboard_fleet_productivity '" . $this->session->userdata('depo_id') . "', '" . $mode . "', '" . $flag . "', $filter_status_assign, '" . $tgl1 . "', '" . $tgl2 . "', $filter_value, $start, $length, $search, 'jumlah', $filter_area")->row_array();
				$jumlah_filter = $this->db->query("EXEC report_dashboard_fleet_productivity '" . $this->session->userdata('depo_id') . "', '" . $mode . "', '" . $flag . "', $filter_status_assign, '" . $tgl1 . "', '" . $tgl2 . "', $filter_value, $start, $length, $search, 'jumlah_filter', $filter_area")->row_array();

				$response = [
					'data' => $data,
					'draw' => $draw,
					'recordsFiltered' => intval($jumlah_filter['recordsFiltered']),
					'recordsTotal' => intval($jumlah['recordsTotal'])
				];

				return $response;
			}
		}
	}

	public function getKeterangan($mode)
	{
		return $this->db->query("EXEC report_dashboard_keterangan '" . $mode . "'")->row_array();
	}

	public function getListDataByCard($modul, $tgl)
	{
		if ($modul == 'PengeluaranBarang') {
			return $this->db->query("SELECT 
												pop.karyawan_nama,
												po.picking_order_kode as kode,     
												FORMAT(po.picking_order_tanggal, 'dd-MM-yyyy') AS tgl,
												po.picking_order_status as status
											FROM picking_order_plan pop
											INNER JOIN picking_order po 
												ON pop.picking_order_id = po.picking_order_id
											WHERE pop.picking_order_plan_status IN('Open','In Progress') and dbo.floordate(po.picking_order_tanggal) = '$tgl'
											AND po.depo_id = '" . $this->session->userdata('depo_id') . "'")->result_array();
		} else if ($modul == 'MutasiPallet') {
			return $this->db->query("SELECT
												tmpd.tr_mutasi_pallet_draft_kode as kode,
												FORMAT(tmpd.tr_mutasi_pallet_draft_tanggal, 'dd-MM-yyyy') AS tgl,
												tmpd.tr_mutasi_pallet_draft_status as status,
												tmpd.tr_mutasi_pallet_draft_nama_checker
											FROM tr_mutasi_pallet_draft tmpd
											WHERE tmpd.tr_mutasi_pallet_draft_status IN('Approved','In Progress') and dbo.floordate(tmpd.tr_mutasi_pallet_draft_tgl_create) = '$tgl'
											AND tmpd.depo_id_asal = '" . $this->session->userdata('depo_id') . "'")->result_array();
		} else if ($modul == 'KoreksiBarang') {
			return $this->db->query("SELECT
												a.tr_koreksi_stok_draft_kode as kode,
												FORMAT(a.tr_koreksi_stok_draft_tanggal, 'dd-MM-yyyy') AS tgl,
												a.tr_koreksi_stok_draft_status as status,
												a.tr_koreksi_stok_draft_nama_checker, 
												a.tr_koreksi_stok_draft_pengemudi, 
												a.tr_koreksi_stok_draft_kendaraan, 
												a.tr_koreksi_stok_draft_nopol, 
												a.tipe_dokumen,
												tm.tipe_mutasi_nama
											FROM tr_koreksi_stok_draft a
											INNER JOIN tipe_mutasi tm ON a.tipe_mutasi_id = tm.tipe_mutasi_id
											WHERE tm.tipe_mutasi_nama IN ('Koreksi Masuk', 'Koreksi Keluar')
											AND dbo.floordate(a.tr_koreksi_stok_draft_tgl_create) = '$tgl' 
											AND a.depo_id_asal = '" . $this->session->userdata('depo_id') . "'")->result_array();
		} else if ($modul == 'Pemusnahan') {
			return $this->db->query("SELECT
												a.tr_koreksi_stok_draft_kode as kode,
												FORMAT(a.tr_koreksi_stok_draft_tanggal, 'dd-MM-yyyy') AS tgl,
												a.tr_koreksi_stok_draft_status as status,
												a.tr_koreksi_stok_draft_nama_checker, 
												a.tr_koreksi_stok_draft_pengemudi, 
												a.tr_koreksi_stok_draft_kendaraan, 
												a.tr_koreksi_stok_draft_nopol, 
												a.tipe_dokumen,
												tm.tipe_mutasi_nama
											FROM tr_koreksi_stok_draft a
											INNER JOIN tipe_mutasi tm ON a.tipe_mutasi_id = tm.tipe_mutasi_id 
											WHERE tm.tipe_mutasi_id IN (
													'9246A374-B798-442B-8EE0-631EE6ADA7C9',
													'86A1887B-28F2-4F81-86C1-84650B2F2FEC'
												)
											AND a.tr_koreksi_stok_draft_status IN('Approved','In Progress')
											AND dbo.floordate(a.tr_koreksi_stok_draft_tgl_create) = '$tgl'
											AND a.depo_id_asal = '" . $this->session->userdata('depo_id') . "'")->result_array();
		} else if ($modul == 'PermintaanBarang') {
			return $this->db->query("SELECT
												picking_list_kode as kode,
												FORMAT(picking_list_tgl_kirim, 'dd-MM-yyyy') AS tgl,
												picking_list_status as status
											FROM picking_list
											WHERE picking_list_status IN('Open','In Progress') AND dbo.floordate(picking_list_create_tgl) = '$tgl'
											AND depo_id = '" . $this->session->userdata('depo_id') . "'")->result_array();
		} else if ($modul == 'PengemasanBarang') {
			return $this->db->query("SELECT
												pl.picking_list_kode as kode,
												FORMAT(pl.picking_list_tgl_kirim, 'dd-MM-yyyy') AS tgl,
												pl.picking_list_status as status,
												dob.delivery_order_batch_kode,
												po.picking_order_kode
											FROM picking_list pl
											INNER JOIN delivery_order_batch dob ON pl.delivery_order_batch_id = dob.delivery_order_batch_id
											INNER JOIN picking_order po ON dob.picking_order_id = po.picking_order_id
											WHERE pl.picking_list_status in('Open', 'In Process') AND dbo.floordate(pl.picking_list_create_tgl) = '$tgl'
											AND pl.depo_id = '" . $this->session->userdata('depo_id') . "'
											AND po.depo_id = '" . $this->session->userdata('depo_id') . "'")->result_array();
		} else if ($modul == 'KoreksiPallet') {
			return $this->db->query("SELECT
												tksp.tr_koreksi_stok_pallet_kode as kode,
												FORMAT(tksp.tr_koreksi_stok_pallet_tanggal, 'dd-MM-yyyy') AS tgl,
												tksp.tr_koreksi_stok_pallet_status as status
											FROM tr_koreksi_stok_pallet tksp
											WHERE tksp.tr_koreksi_stok_pallet_status IN('Open','In Progress') AND dbo.floordate(tksp.tr_koreksi_stok_pallet_tgl_create) = '$tgl'
											AND tksp.depo_id_asal = '" . $this->session->userdata('depo_id') . "'")->result_array();
		} else if ($modul == 'StockOpname') {
			return $this->db->query("SELECT
												tpo.tr_opname_plan_kode as kode,
												FORMAT(tpo.tr_opname_plan_tanggal, 'dd-MM-yyyy') AS tgl,
												tpo.tr_opname_plan_status as status,
												tpo.tipe_stok
											FROM tr_opname_plan tpo
											WHERE tpo.tr_opname_plan_status IN('Approved', 'In Progress') AND dbo.floordate(tpo.tr_opname_plan_tgl_create) = '$tgl'
											AND tpo.depo_id = '" . $this->session->userdata('depo_id') . "'")->result_array();
		} else if ($modul == 'PenerimaanBarang') {
			return $this->db->query("SELECT
												penerimaan_pembelian_kode as kode,
												FORMAT(penerimaan_pembelian_tgl, 'dd-MM-yyyy') AS tgl,
												penerimaan_pembelian_status as status,
												penerimaan_pembelian_pengemudi, 
												penerimaan_pembelian_nopol
											FROM penerimaan_pembelian
											WHERE penerimaan_pembelian_status NOT IN('Close') AND dbo.floordate(penerimaan_pembelian_tgl) = '$tgl'
											AND depo_id = '" . $this->session->userdata('depo_id') . "'
											")->result_array();
		}
	}
}
