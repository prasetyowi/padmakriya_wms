<?php

date_default_timezone_set('Asia/Jakarta');

class M_KoreksiPallet extends CI_Model
{

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getDataKoreksiPalletByFilter($data)
	{
		$tgl = explode(" - ", $data->filterKoreksiPalletTanggal);

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$kode = $data->filterNoKoreksiPallet == "" ? "" : "AND a.tr_koreksi_stok_pallet_id = '$data->filterNoKoreksiPallet'";

		return  $this->db->query("SELECT DISTINCT
                                a.tr_koreksi_stok_pallet_id as id,
                                a.tr_koreksi_stok_pallet_kode as kode,
                                FORMAT(a.tr_koreksi_stok_pallet_tgl_create, 'yyyy-MM-dd HH:ss') as tgl,
                                ISNULL(a.tr_koreksi_stok_pallet_keterangan, '') as keterangan,
                                c.client_wms_nama as client,
                                d.principle_nama as principle,
                                e.tipe_mutasi_nama as tipe,
                                f.depo_detail_nama
                              FROM tr_koreksi_stok_pallet a
                              LEFT JOIN tr_koreksi_stok_pallet_detail b ON a.tr_koreksi_stok_pallet_id = b.tr_koreksi_stok_pallet_id
                              LEFT JOIN client_wms c ON a.client_wms_id = c.client_wms_id
                              LEFT JOIN principle d ON a.principle_id = d.principle_id
                              LEFT JOIN tipe_mutasi e ON a.tipe_mutasi_id = e.tipe_mutasi_id
                              LEFT JOIN depo_detail f ON a.depo_detail_id_tujuan = f.depo_detail_id
                              WHERE a.depo_id_asal = '" . $this->session->userdata('depo_id') . "'
                                AND is_assigment_pengeluaran_barang is null
                                AND FORMAT(a.tr_koreksi_stok_pallet_tgl_create, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
                                " . $kode . "
                              ORDER BY FORMAT(a.tr_koreksi_stok_pallet_tgl_create, 'yyyy-MM-dd HH:ss') DESC")->result();
		// return $this->db->last_query();
	}

	public function getDataKoreksiPallet()
	{
		return $this->db->select("tr_koreksi_stok_pallet_id as id, tr_koreksi_stok_pallet_kode as kode")->from("tr_koreksi_stok_pallet")->where("is_assigment_pengeluaran_barang is null")->order_by("tr_koreksi_stok_pallet_tanggal", "ASC")->get()->result();
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function get_DepoNameBySession()
	{
		return $this->db->select("depo_id, depo_nama")
			->from("depo")
			->where("depo_id", $this->session->userdata('depo_id'))->get()->row();
	}

	public function getTypeTransactions()
	{
		return $this->db->select("tipe_mutasi_id as id, tipe_mutasi_nama as nama")
			->from("tipe_mutasi")->order_by("tipe_mutasi_nama", "ASC")->where("tipe_mutasi_flag", "K")
			->where_not_in("tipe_mutasi_nama", array("Pemusnahan", "Retur Supplier", "Koreksi Masuk", "Koreksi Keluar"))->get()->result();
	}

	public function checkKodePallet($kode)
	{
		return $this->db->query("SELECT 
                                a.pallet_id,
                                a.pallet_kode,
                                FORMAT(a.pallet_tanggal_create, 'dd/MM/yyyy HH:mm') as tgl_create,
                                a.pallet_who_create,
                                b.rak_lajur_detail_nama,
                                d.client_wms_id,
                                b.principle_id,
                                c.pallet_detail_id,
                                c.sku_stock_id,
                                c.sku_id, 
                                c.sku_stock_expired_date,
                                c.sku_stock_expired_date_sj,
                                ISNULL(c.sku_stock_qty, 0) - ISNULL(c.sku_stock_ambil, 0) + ISNULL(c.sku_stock_in, 0) - ISNULL(c.sku_stock_out, 0) + ISNULL(c.sku_stock_terima, 0) as qty,
                                d.sku_kode,
                                d.sku_nama_produk,
                                d.sku_satuan,
                                d.sku_kemasan,
                                e.depo_detail_id
                            FROM pallet a 
                            LEFT JOIN rak_lajur_detail b 
                              ON a.rak_lajur_detail_id = b.rak_lajur_detail_id
                            LEFT JOIN pallet_detail c
                              ON a.pallet_id = c.pallet_id
                            LEFT JOIN sku d
                              ON c.sku_id = d.sku_id
                            LEFT JOIN rak e
	                            ON b.rak_id = e.rak_id
                            WHERE a.pallet_kode = '$kode'
                            ORDER BY c.sku_id")->result();
	}

	public function getAllSKU($skuIdInTable, $principle, $principleBrand)
	{

		$this->db->distinct()
			->select("sku.sku_id,
                sku_induk.sku_induk_nama,
                sku.sku_kode,
                sku.sku_nama_produk,
                sku.sku_kemasan,
                sku.sku_satuan,
                CONCAT(FORMAT(DATEADD(month, isnull(sku.sku_minimum_expired_date, 0), GETDATE()), 'yyyy-MM'), '-01') as tgl_expired,
                principle.principle_nama,
                principle_brand.principle_brand_nama")
			->from("sku")
			->join("sku_induk", "sku.sku_induk_id = sku_induk.sku_induk_id", "left")
			->join("principle", "sku.principle_id = principle.principle_id", "left")
			->join("principle_brand", "sku.principle_brand_id = principle_brand.principle_brand_id", "left")
			// ->join("sku_stock", "sku.sku_id = sku_stock.sku_id", "left")
			// ->where("sku_stock.sku_id is NOT NULL", NULL, FALSE)
			->where("principle.principle_id", $principle);
		// if (count($skuIdInTable) > 0) {
		//   $this->db->where_not_in("sku.sku_id", $skuIdInTable);
		// }
		if ($principleBrand != "") {
			$this->db->where("principle_brand.principle_brand_id", $principleBrand);
		}
		// $this->db->limit(100);

		return $this->db->get()->result();



		// return $this->db->query("SELECT DISTINCT TOP 10
		//                             sku.sku_id,
		//                             sku_induk.sku_induk_nama,
		//                             sku.sku_nama_produk,
		//                             sku.sku_kemasan,
		//                             sku.sku_satuan,
		//                             CONCAT(FORMAT(DATEADD(month, sku.sku_minimum_expired_date, GETDATE()), 'yyyy-MM'), '-01') as tgl_expired,
		//                             principle.principle_nama,
		//                             principle_brand.principle_brand_nama
		//                         from sku
		//                         LEFT JOIN sku_induk on sku.sku_induk_id = sku_induk.sku_induk_id
		//                         LEFT JOIN principle on sku.principle_id = principle.principle_id
		//                         LEFT JOIN principle_brand on sku.principle_brand_id = principle_brand.principle_brand_id
		//                         LEFT JOIN sku_stock on sku.sku_id = sku_stock.sku_id
		//                         where sku_stock.sku_id is not null")->result();
	}

	public function getAllSKUById($id)
	{
		return  $this->db->select("sku_id, sku_kode, sku_nama_produk, sku_kemasan, sku_satuan, sku_induk_id,
                              CONCAT(FORMAT(DATEADD(month, isnull(sku_minimum_expired_date, 0), GETDATE()), 'yyyy-MM'), '-01') as tgl_expired")
			->from("sku")
			->where_in("sku_id", $id)->get()->result();
	}

	public function checkDataInSkuStockByparams($data, $depo_detail_id)
	{
		return $this->db->select("*")
			->from("sku_stock")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("depo_detail_id", $depo_detail_id)
			->where("sku_id", $data->skuId)
			->where("sku_stock_expired_date", $data->expiredDate)
			->get()->row();
	}

	public function insert_data_to_sku_stock($sku_stock_id, $data, $depo_detail_id, $client_wms_id, $qtyReduced)
	{
		$client_wms_id = $this->db->query("select client_wms_id from sku where sku_id='$data->skuId'")->row(0)->client_wms_id;

		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
		$this->db->set("client_wms_id", $client_wms_id);
		$this->db->set("depo_id", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("sku_induk_id", $data->skuIndukId);
		$this->db->set("sku_id", $data->skuId);
		$this->db->set("sku_stock_expired_date", $data->expiredDate);
		$this->db->set("sku_stock_awal", 0);
		$this->db->set("sku_stock_masuk", $qtyReduced);
		$this->db->set("sku_stock_alokasi", 0);
		$this->db->set("sku_stock_saldo_alokasi", 0);
		$this->db->set("sku_stock_keluar", 0);
		$this->db->set("sku_stock_akhir", 0);
		$this->db->set("sku_stock_is_jual", 1);
		$this->db->set("sku_stock_is_aktif", 1);
		$this->db->set("sku_stock_is_deleted", 0);

		$queryinsert = $this->db->insert("sku_stock");

		return $queryinsert;
	}

	public function update_data_to_sku_stock($qty_stock, $client_wms_id, $qtyReduced)
	{
		// $stock_masuk = $qty_stock->sku_stock_masuk + $qtyReduced;
		return $this->db->query("exec insertupdate_sku_stock 'masuk', '$qty_stock->sku_stock_id', NULL, '$qtyReduced'");
		// return $this->db->query("UPDATE sku_stock SET sku_stock_masuk = '$stock_masuk' WHERE sku_stock_id = '$qty_stock->sku_stock_id'");
	}

	public function update_sku_stock_id_in_pallet_detail($id, $sku_stock_id)
	{
		return $this->db->query("UPDATE pallet_detail SET sku_stock_id = '$sku_stock_id' WHERE pallet_detail_id = '$id'");
	}

	public function insertToPalletDetail($pallet_detail_id, $pallet_id, $data, $qtyReduced)
	{
		$this->db->set("pallet_detail_id", $pallet_detail_id);
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("sku_id", $data->skuId);
		$this->db->set("sku_stock_expired_date", $data->expiredDate);
		$this->db->set("sku_stock_qty", 0);
		$this->db->set("sku_stock_in", $qtyReduced);
		$this->db->set("sku_stock_expired_date_sj", $data->expiredDate);

		$queryinsert = $this->db->insert("pallet_detail");

		return $queryinsert;
		// return true;
	}

	public function insertToTrKoreksiPalletDetail($tr_pallet_detail_id, $tr_pallet_id, $data)
	{
		$dataDetail = [
			'tr_koreksi_stok_pallet_detail_id' => $tr_pallet_detail_id,
			'tr_koreksi_stok_pallet_id' => $tr_pallet_id,
			'sku_id' => $data->skuId !== "null" ? $data->skuId : NULL,
			'sku_stock_id' => $data->skuStockId !== "null" ? $data->skuStockId : NULL,
			'sku_qty_koreksi' => $data->qtyPlan !== "null" ? $data->qtyPlan : NULL,
			'sku_expired_date' => $data->expiredDate,
			'sku_qty_availabe' => $data->qtyAvailable
		];
		return $this->db->insert('tr_koreksi_stok_pallet_detail', $dataDetail);
	}

	public function update_tr_koreksi_stok_pallet_detail($tr_koreksi_stok_pallet_detail_id, $sku_stock_id)
	{
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->where("tr_koreksi_stok_pallet_detail_id", $tr_koreksi_stok_pallet_detail_id);

		$queryinsert = $this->db->update("tr_koreksi_stok_pallet_detail");

		return $queryinsert;
	}

	public function updatePalletAndSkuStock($data, $qtyReduced, $type)
	{
		if ($type == 'out') {
			//update data pallet detail kolom stock out, ambil dari terbaru terlebih dahulu
			// $stockOut = $this->db->select("isnull(sku_stock_out, 0) as sku_stock_out")->from("pallet_detail")->where("pallet_detail_id", $data->palletDetailId)->get()->row()->sku_stock_out;
			$getPalletDetail = $this->db->select("pallet_id, sku_stock_id, isnull(sku_stock_out, 0) as sku_stock_out")->from("pallet_detail")->where("pallet_detail_id", $data->palletDetailId)->get()->row();

			// $this->db->set("sku_stock_out", $stockOut + str_replace("-", "", $qtyReduced));
			// $this->db->where("pallet_detail_id", $data->palletDetailId);
			// $this->db->update("pallet_detail");
			$this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_out', '$getPalletDetail->pallet_id', '$getPalletDetail->sku_stock_id', '" . str_replace("-", "", $qtyReduced) . "'");


			// update data sku stock kolom stock keluar, ambil dari terbaru terlebih dahulu
			// $stockKeluar = $this->db->select("isnull(sku_stock_keluar, 0) as sku_stock_keluar")->from("sku_stock")->where("sku_stock_id", $data->skuStockId)->get()->row()->sku_stock_keluar;

			// $stock_keluar = $stockKeluar + str_replace("-", "", $qtyReduced);

			return $this->db->query("exec insertupdate_sku_stock 'keluar', '$getPalletDetail->sku_stock_id', NULL, '" . str_replace("-", "", $qtyReduced) . "'");
			// $this->db->set("sku_stock_keluar", $stockKeluar + str_replace("-", "", $qtyReduced));
			// $this->db->where("sku_stock_id", $data->skuStockId);
			// return $this->db->update("sku_stock");
		}

		if ($type == 'in') {
			//update data pallet detail kolom stock in, ambil dari terbaru terlebih dahulu
			// $stockIn = $this->db->select("isnull(sku_stock_in, 0) as sku_stock_in")->from("pallet_detail")->where("pallet_detail_id", $data->palletDetailId)->get()->row()->sku_stock_in;
			$getPalletDetail = $this->db->select("pallet_id, sku_stock_id, isnull(sku_stock_in, 0) as sku_stock_in")->from("pallet_detail")->where("pallet_detail_id", $data->palletDetailId)->get()->row();

			// $this->db->set("sku_stock_in", $stockIn + $qtyReduced);
			// $this->db->where("pallet_detail_id", $data->palletDetailId);
			// $this->db->update("pallet_detail");
			$this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_in', '$getPalletDetail->pallet_id', '$getPalletDetail->sku_stock_id', '$qtyReduced'");

			return $this->db->query("exec insertupdate_sku_stock 'masuk', '$getPalletDetail->sku_stock_id', NULL, '$qtyReduced'");
			//update data sku stock kolom stock masuk, ambil dari terbaru terlebih dahulu
			// $stockMasuk = $this->db->select("isnull(sku_stock_masuk, 0) as sku_stock_masuk")->from("sku_stock")->where("sku_stock_id", $data->skuStockId)->get()->row()->sku_stock_masuk;

			// $this->db->set("sku_stock_masuk", $stockMasuk + $qtyReduced);
			// $this->db->where("sku_stock_id", $data->skuStockId);
			// return $this->db->update("sku_stock");
		}
	}

	public function getDataHeaderKoreksiPallet($id)
	{
		return $this->db->select("a.tr_koreksi_stok_pallet_kode as kode,
                              FORMAT(a.tr_koreksi_stok_pallet_tgl_create, 'yyyy-MM-dd') as tgl,
                              ISNULL(a.tr_koreksi_stok_pallet_keterangan, '') as keterangan,
                              b.tipe_mutasi_nama as tipe,
                              c.pallet_kode,
                              FORMAT(c.pallet_tanggal_create, 'yyyy-MM-dd HH:ss') as tgl_pallet,
                              c.pallet_who_create,
                              d.depo_detail_nama
                ")
			->from("tr_koreksi_stok_pallet a")
			->join("tipe_mutasi b", "a.tipe_mutasi_id = b.tipe_mutasi_id", "left")
			->join("pallet c", "a.pallet_id = c.pallet_id", "left")
			->join("depo_detail d", "a.depo_detail_id_tujuan = d.depo_detail_id", "left")
			->where("a.tr_koreksi_stok_pallet_id", $id)->get()->row();
	}

	public function getDataDetailKoreksiPallet($id)
	{
		return $this->db->select("FORMAT(a.sku_expired_date, 'yyyy-MM-dd') as tgl,
                              a.sku_qty_availabe,
                              a.sku_qty_koreksi,
                              ISNULL(a.sku_qty_koreksi, 0) - ISNULL(a.sku_qty_availabe, 0) as qtyTot,
                              b.sku_kode,
                              b.sku_nama_produk,
                              b.sku_satuan,
                              b.sku_kemasan")
			->from("tr_koreksi_stok_pallet_detail a")
			->join("sku b", "a.sku_id = b.sku_id", "left")
			->where("a.tr_koreksi_stok_pallet_id", $id)->get()->result();
	}

	public function getPrinciples()
	{
		return $this->db->select("principle_id, principle_nama")->from("principle")->order_by("principle_nama", "ASC")->get()->result();
	}

	public function requestGetPrincipleBrand($principleId)
	{
		return $this->db->select("principle_brand_id as, principle_brand_nama as nama")->from("principle_brand")->where('principle_id', $principleId)->order_by("principle_brand_nama", "ASC")->get()->result();
	}

	public function getKodeAutoComplete($value)
	{
		return $this->db->select("SUBSTRING(pallet_kode, CHARINDEX('PAL/', pallet_kode), LEN(pallet_kode)) as kode")
			->from("pallet")
			->where("depo_id", $this->session->userdata('depo_id'))
			->like("pallet_kode", $value)
			->get()
			->result();
	}
}
