<?php

date_default_timezone_set('Asia/Jakarta');

class M_PenerimaanBarang extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->load->model('M_Vrbl');
	}

	public function getClientWms()
	{
		// $dataclientwms = $this->db->select("client_wms_id, client_wms_nama")
		// 	->from("client_wms")
		// 	->where("client_wms_is_aktif", 1)
		// 	->get()->result();

		$query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0");

		return $query->result();
	}

	public function getTipePenerimaan()
	{
		$dataTipePenerimaan = $this->db->select("penerimaan_tipe_id as id, penerimaan_tipe_nama as nama")
			->from("penerimaan_tipe")
			->where("penerimaan_tipe_is_aktif", 1)
			->where("penerimaan_tipe_jenis", "Pembelian")
			->get()->result();
		return $dataTipePenerimaan;
	}

	public function get_data_principle_by_client_wms_id($id)
	{
		$query = $this->db->select("b.principle_id as id, b.principle_kode as kode, b.principle_nama as nama")
			->from("client_wms_principle a")
			->join("principle b", "a.principle_id = b.principle_id")
			->where("a.client_wms_id", $id)->get()->result();
		return $query;
	}

	public function get_data_surat_jalan($tahun, $bulan, $perusahaan, $principle, $status)
	{

		$this->db->select("pb.penerimaan_pembelian_id as id,
                            pb.penerimaan_pembelian_tgl,
                            FORMAT(pb.penerimaan_pembelian_tgl, 'yyyy-MM-dd HH:mm:ss') as tgl,
                            pb.penerimaan_pembelian_kode as kode,
                            pb.penerimaan_pembelian_status as status,
							pb.penerimaan_pembelian_tgl_update,
                            cw.client_wms_nama as pt,
                            p.principle_kode as p_kode,
                            p.principle_nama as p_nama")
			//pbd3.penerimaan_surat_jalan_id as psjID
			->from("penerimaan_pembelian pb")
			->join("penerimaan_pembelian_detail3 pbd3", "pb.penerimaan_pembelian_id = pbd3.penerimaan_pembelian_id", "left")
			->join("penerimaan_surat_jalan psj", "pbd3.penerimaan_surat_jalan_id = psj.penerimaan_surat_jalan_id", "left")
			->join("principle p", "psj.principle_id = p.principle_id", "left")
			->join("client_wms cw", "psj.client_wms_id = cw.client_wms_id", "left")
			->where("pb.depo_id", $this->session->userdata('depo_id'))
			->where("YEAR(pb.penerimaan_pembelian_tgl)", $tahun)
			->where("MONTH(pb.penerimaan_pembelian_tgl)", $bulan);
		if ($perusahaan != "") {
			$this->db->where("psj.client_wms_id", $perusahaan);
		}

		if ($principle != "") {
			$this->db->where("psj.principle_id", $principle);
		}
		if ($status != "") {
			$this->db->where("psj.penerimaan_surat_jalan_status", $status);
		}
		$this->db->distinct();
		$this->db->order_by("pb.penerimaan_pembelian_tgl", "DESC");
		return $this->db->get()->result();
	}

	public function getKodePenerimanSuratJalan()
	{
		$data = $this->db->select("sj.penerimaan_surat_jalan_id as id, sj.penerimaan_surat_jalan_kode as kode")
			->from("penerimaan_surat_jalan sj")
			->join("penerimaan_pembelian pb", "sj.penerimaan_surat_jalan_id = pb.penerimaan_surat_jalan_id", "left")
			->order_by("sj.penerimaan_surat_jalan_kode", "ASC")
			->where("sj.depo_id", $this->session->userdata("depo_id"))
			->where("pb.penerimaan_surat_jalan_id is NULL")
			->get()->result();
		return $data;
	}

	public function getGudangByDepoId()
	{
		$datagudang = $this->db->select("d.depo_id as depo_id, dd.depo_detail_id as depo_detail_id, dd.depo_detail_nama as depo_detail_nama")
			->from("depo d")
			->join("depo_detail dd", "d.depo_id = dd.depo_id", "left")
			->where("dd.depo_id", $this->session->userdata('depo_id'))
			->where("dd.depo_detail_is_gudang_penerima", 2)
			->get()->row();
		return $datagudang;
	}

	public function getExpedisi()
	{
		$dataekspedisi = $this->db->select("ekspedisi_id, ekspedisi_kode, ekspedisi_nama")->from('ekspedisi')->where("ekspedisi_is_aktif", 1)->order_by('ekspedisi_nama')->get();
		return $dataekspedisi->result();
	}

	public function get_surat_jalan($id)
	{
		return $this->db->select("sj.penerimaan_surat_jalan_id as id,
                                  sj.penerimaan_surat_jalan_kode as kode")
			->from("penerimaan_surat_jalan sj")
			->where_in("sj.penerimaan_surat_jalan_id", $id)
			->where("sj.depo_id", $this->session->userdata("depo_id"))
			->order_by("sj.penerimaan_surat_jalan_kode", "ASC")
			->get()->result();
	}

	public function get_surat_jalan_detail($penerimaanPembelianId, $id, $pallet_id)
	{
		if ($pallet_id[0] == null) {
			$whr = "";
		} else {
			$whr = "WHERE pallet_id IN (" . implode(",", $pallet_id) . ")";
		}

		$dataKaryawan = $this->db->select("*")->from('karyawan')->where('karyawan_id', $this->session->userdata('karyawan_id'))->get()->row();

		if ($dataKaryawan->karyawan_level_id == '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41') {
			$karyawanId =  "AND pbd4.karyawan_id = '$dataKaryawan->karyawan_id'";
		} else {
			$karyawanId = "";
		}

		// $pbd4 = $this->db->select("karyawan_id")->from("penerimaan_pembelian_detail4")->where("penerimaan_pembelian_id", $penerimaanPembelianId)->get()->result();

		// if ($dataKaryawan->karyawan_id == '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41') {
		// 	$karyawanId =  "AND pdb.karyawan_id = '$dataKaryawan->karyawan_id'";
		// } else {
		// 	$karyawanId = "";
		// }

		// $query =  $this->db->query("SELECT
		//                                 sjd.sku_id,
		//                                 sjd.sku_jumlah_barang AS sjd_jumlah_barang,
		//                                 ISNULL(sjd.sku_jumlah_barang_terima,0) + ISNULL(plt.sku_stock_qty,0) AS jumlah_terima,
		//                                 ISNULL(sjd.sku_jumlah_barang, 0) - (ISNULL(sjd.sku_jumlah_barang_terima,0) + ISNULL(plt.sku_stock_qty,0)) AS sisa,
		//                                 sjd.sku_exp_date AS sj_ed,
		//                                 sku.sku_kode AS sku_kode,
		//                                 sku.sku_nama_produk AS sku_nama_produk,
		//                                 sku.sku_kemasan AS sku_kemasan,
		//                                 sku.sku_satuan AS sku_satuan
		//                             FROM (SELECT
		//                                 sku_id,
		//                                 sku_exp_date,
		//                                 SUM(sku_jumlah_barang) AS sku_jumlah_barang,
		//                                 SUM(ISNULL(sku_jumlah_barang_terima, 0)) AS sku_jumlah_barang_terima
		//                             FROM penerimaan_surat_jalan_detail
		//                             WHERE penerimaan_surat_jalan_id IN (" . $id . ")
		//                             GROUP BY sku_id,
		//                                     sku_exp_date) sjd
		//                             LEFT JOIN (SELECT
		//                                 sku_id,
		//                                 sku_exp_date,
		//                                 SUM(sku_stock_qty) AS sku_stock_qty
		//                             FROM pallet_detail_temp $whr
		//                             GROUP BY sku_id,
		//                                     sku_exp_date) plt
		//                                 ON plt.sku_id = sjd.sku_id
		//                                 AND plt.sku_exp_date = sjd.sku_exp_date
		//                             LEFT JOIN sku
		//                                 ON sjd.sku_id = sku.sku_id");

		$query = $this->db->query("SELECT  
                                        pdb.penerimaan_pembelian_detail_id,
                                        pdb.sku_id,
                                        pdb.sku_jumlah_barang as sjd_jumlah_barang,
                                        SUM(ISNULL(pdb.sku_jumlah_terima,0)) + ISNULL(plt.sku_stock_qty,0) AS jumlah_terima,
                                        ISNULL(pdb.sku_jumlah_barang, 0) - (SUM(ISNULL(pdb.sku_jumlah_terima,0)) + ISNULL(plt.sku_stock_qty,0)) AS sisa,
                                        pdb.sku_exp_date AS sj_ed,
                                        pdb.batch_no,
                                        pdb.karyawan_id,
                                        sku.sku_kode,
                                        sku.sku_nama_produk,
                                        sku.sku_kemasan,
                                        sku.sku_satuan
                                    FROM penerimaan_pembelian_detail as pdb
                                        LEFT JOIN sku ON pdb.sku_id = sku.sku_id
                                        LEFT JOIN (	SELECT 
                                                        karyawan_id, penerimaan_pembelian_id
                                                    FROM penerimaan_pembelian_detail4
													WHERE penerimaan_pembelian_id = '$penerimaanPembelianId'
													) pbd4
											ON pbd4.penerimaan_pembelian_id = pdb.penerimaan_pembelian_id
										LEFT JOIN (	SELECT 
													sku_id, sku_exp_date,SUM(sku_stock_qty) AS sku_stock_qty
												FROM pallet_detail_temp $whr
												GROUP BY sku_id, sku_exp_date) plt 
										ON plt.sku_id = pdb.sku_id 
										AND plt.sku_exp_date = pdb.sku_exp_date
                                    WHERE pdb.sku_id IN (
                                                            SELECT p.sku_id FROM penerimaan_pembelian_detail3 p3
                                                                INNER JOIN penerimaan_pembelian_detail p ON p.penerimaan_pembelian_id = p3.penerimaan_pembelian_id
                                                            WHERE penerimaan_surat_jalan_id IN (" . $id . ")
                                                            GROUP BY p.sku_id, sku_exp_date, p.penerimaan_pembelian_id )
                                    AND pdb.penerimaan_pembelian_id IN (	SELECT penerimaan_pembelian_id 
                                                                        FROM penerimaan_pembelian_detail3 
                                                                        WHERE penerimaan_surat_jalan_id IN (" . $id . "))
                                    AND pdb.penerimaan_pembelian_id = '$penerimaanPembelianId'
                                    $karyawanId
                                    GROUP BY pdb.penerimaan_pembelian_detail_id,pdb.sku_id, pdb.sku_exp_date, pdb.batch_no, pdb.karyawan_id, pdb.sku_jumlah_barang,
                                        sku.sku_kode, sku.sku_nama_produk, sku.sku_kemasan, sku.sku_satuan, ISNULL(plt.sku_stock_qty,0)");
		return $query->result_array();
	}


	public function get_surat_jalan_detail_view($id)
	{

		//get sku_id di pallet_detail dan comparekan dengan data yg di penerimaan_pembelian_detail
		$getSkuId = $this->getSkuIdPalletDetail($id);

		$arr1 = [];
		$arrSkuId = [];
		$arrSkuIdToUseComparePalletDetail = [];
		foreach ($getSkuId as $key => $value) {
			array_push($arrSkuId, $value->sku_id);
		}

		$dataPenerimaanbarangDetail = $this->dataPenerimaanbarangDetail($id);

		foreach ($dataPenerimaanbarangDetail as $key => $data) {
			if (in_array($data->sku_id, $arrSkuId)) {
				array_push($arr1, $data->sku_id);
			}
		}

		$dataSku = array_unique($arr1);
		$idx = 0;

		foreach ($dataSku as $key => $value) {
			$arrSkuIdToUseComparePalletDetail[$idx++] = "'" . $value . "'";
		}


		return $this->db->query("SELECT 
                                    pbd.penerimaan_pembelian_detail_id as id,
                                    pbd.sku_jumlah_barang as jml_barang,
                                    pbd2.qty as jml_terima,
                                    pbd.sku_exp_date,
                                    pbd.batch_no,
                                    sku.sku_kode as sku_kode,
                                    sku.sku_nama_produk as sku_nama,
                                    sku.sku_kemasan as sku_kemasan,
                                    sku.sku_satuan as sku_satuan
                                FROM penerimaan_pembelian_detail pbd
                                LEFT JOIN (SELECT pbd2.penerimaan_pembelian_id, sku_id, SUM(pd.sku_stock_qty) as qty
                                            FROM penerimaan_pembelian_detail2 pbd2
                                            LEFT JOIN pallet_detail pd on pbd2.pallet_id = pd.pallet_id
                                            where pbd2.penerimaan_pembelian_id = '$id'
                                                and pd.sku_id in(" . implode(',', $arrSkuIdToUseComparePalletDetail) . ")
                                            GROUP BY pbd2.penerimaan_pembelian_id, sku_id
                                    ) as pbd2
                                    ON pbd.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id
                                    AND pbd.sku_id = pbd2.sku_id
                                LEFT JOIN sku on pbd.sku_id = sku.sku_id
                                WHERE pbd.penerimaan_pembelian_id = '$id'")->result();
	}

	public function get_data_jenis_palet()
	{
		$data = $this->db->select("pallet_jenis_id as id,pallet_jenis_kode as kode, pallet_jenis_nama as nama")
			->from("pallet_jenis")
			->order_by("pallet_jenis_nama", "ASC")
			->get()->result_array();
		return $data;
	}

	public function get_data_palet($id)
	{

		$get_data = $this->db->select("pallet_id as id, delivery_order_batch_id as do_id, pallet_kode, pallet_jenis_id as jenis_id, depo_detail_id, id_temp, flag, tipe_stock, karyawan_id, pallet_who_create, depo_detail_nama")->from('pallet_temp')->order_by("pallet_tanggal_create", "ASC")->get()->result();
		$arr = array();

		foreach ($id as $keys => $value) {
			if ($get_data) {
				foreach ($get_data as $key => $val) {
					if ($val->id_temp != null) {
						if (in_array($value, json_decode($val->id_temp))) {
							array_push($arr, $val);
						}
					}
				}
			}
		}

		return array_map("unserialize", array_unique(array_map("serialize", $arr)));
	}

	public function save_data_pallet($value, $data)
	{
		$this->db->set("pallet_id", $data->pallet_generate_detail2_id);
		$this->db->set("id_temp", json_encode($value));
		$this->db->set("pallet_jenis_id", $data->pallet_jenis_id);
		$this->db->set("pallet_kode", $data->pallet_generate_detail2_kode);
		$this->db->set("depo_id", $this->session->userdata("depo_id"));
		$this->db->set("pallet_tanggal_create", "GETDATE()", false);
		$this->db->set("pallet_who_create", $this->session->userdata("pengguna_username"));
		$this->db->set("pallet_is_aktif", 1);
		$this->db->set("karyawan_id", $this->session->userdata("karyawan_id"));

		return $this->db->insert("pallet_temp");
	}

	public function update_jenis_pallet_by_id($id, $jenis_palet_id)
	{
		$this->db->set("pallet_jenis_id", $jenis_palet_id);
		$this->db->where("pallet_id", $id);
		$this->db->update("pallet_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function update_gudang_tujuan_by_id($id, $gudang_tujuan_id, $tipe_stock)
	{

		$depoDetailNama = $this->db->select("depo_detail_nama")->from("depo_detail")->where("depo_detail_id", $gudang_tujuan_id)->get()->row()->depo_detail_nama;

		$this->db->set("depo_detail_id", $gudang_tujuan_id);
		$this->db->set("depo_detail_nama", $depoDetailNama);
		$this->db->set("flag", 1);
		$this->db->set("tipe_stock", $tipe_stock);
		$this->db->where("pallet_id", $id);
		return $this->db->update("pallet_temp");
	}

	public function delete_pallet($id)
	{
		$this->db->where('pallet_id', $id);
		$query = $this->db->delete('pallet_temp');
		if ($query) {
			$this->db->where('pallet_id', $id);
			$this->db->delete('pallet_detail_temp');
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}

	public function delete_pallet_detail($id)
	{
		$this->db->where('pallet_detail_id', $id);
		return $this->db->delete('pallet_detail_temp');
		// return true;
	}
	public function get_surat_jalan_detail_by_arrId($id)
	{
		$query = $this->db->select("pl.pallet_detail_id as pallet_detail_id,
                                    pl.penerimaan_tipe_id as tipe_id,
                                    pl.sku_exp_date,
                                    pl.sku_stock_expired_date as expired_date,
                                    pl.sku_stock_qty as qty,
                                    pl.jumlah_barang,
                                    pl.batch_no,
                                    sku.sku_id as sku_id,
                                    sku.sku_kode as sku_kode,
                                    sku.sku_nama_produk as sku_nama_produk,
                                    sku.sku_kemasan as sku_kemasan,
                                    sku.sku_satuan as sku_satuan,
                                    p.principle_kode as principle")
			->from("pallet_detail_temp pl")
			->join("sku", "pl.sku_id = sku.sku_id", "left")
			->join("principle p", "sku.principle_id = p.principle_id", "left")
			->where("pl.pallet_id", $id)
			->order_by("pl.tanggal_create", "ASC")
			->get()->result();
		return $query;
	}

	public function get_surat_jalan_detail_by_arrId_view($id)
	{
		return $this->db->select("pl.sku_stock_expired_date as expired_date,
                                    pl.sku_stock_qty as qty,
                                    pl.sku_stock_qty as jumlah_barang,
                                    pl.batch_no,
                                    sku.sku_kode as sku_kode,
                                    sku.sku_nama_produk as sku_nama_produk,
                                    sku.sku_kemasan as sku_kemasan,
                                    sku.sku_satuan as sku_satuan,
                                    p.principle_kode as principle")
			->from("penerimaan_pembelian_detail2d pl")
			->join("sku", "pl.sku_id = sku.sku_id", "left")
			->join("principle p", "sku.principle_id = p.principle_id", "left")
			->where("pl.pallet_id", $id)
			->get()->result();
	}

	public function save_data_to_pallet_detail_temp($arr, $sj_id, $pallet_id, $penerimaanBarangId)
	{
		$sql = "select sku_id, sku_exp_date, sku_jumlah_barang, batch_no
        from penerimaan_pembelian_detail
        where penerimaan_pembelian_id = '$penerimaanBarangId'
        and (";

		foreach ($arr as $i => $value) {

			$batchNo = $value['batch_no'] == "" ? "and batch_no is null" : "and batch_no = '" . $value['batch_no'] . "'";

			if ($i == 0) {
				$sql .= "(sku_id = '" . $value['sku_id'] . "' and sku_exp_date = '" . $value['ed'] . "' and sku_jumlah_barang = '" . $value['jumlah_barang'] . "' $batchNo)";
			} else {
				$sql .= "or (sku_id = '" . $value['sku_id'] . "' and sku_exp_date = '" . $value['ed'] . "' and sku_jumlah_barang = '" . $value['jumlah_barang'] . "' $batchNo)";
			}
		}

		$sql .= ")";

		$query = $this->db->query($sql)->result();


		if (empty($query)) {
			foreach ($arr as $key => $val) {
				$this->db->set("pallet_detail_id", "NewID()", FALSE);
				$this->db->set("pallet_id", $pallet_id);
				$this->db->set("sku_id", $val['sku_id']);
				$this->db->set("sku_stock_qty", 0);
				$this->db->set("sku_exp_date", $val['ed']);
				$this->db->set("tanggal_create", "GETDATE()", false);
				$this->db->set("jumlah_barang", $val['jumlah_barang']);
				$this->db->set("batch_no", $val['batch_no']);
				$queryinsert = $this->db->insert("pallet_detail_temp");
			}

			return $queryinsert;
		} else {
			foreach ($query as $key => $val) {
				$this->db->set("pallet_detail_id", "NewID()", FALSE);
				$this->db->set("pallet_id", $pallet_id);
				$this->db->set("sku_id", $val->sku_id);
				$this->db->set("sku_stock_qty", 0);
				$this->db->set("sku_exp_date", $val->sku_exp_date);
				$this->db->set("tanggal_create", "GETDATE()", false);
				$this->db->set("jumlah_barang", $val->sku_jumlah_barang);
				$this->db->set("batch_no", $val->batch_no);
				$queryinsert = $this->db->insert("pallet_detail_temp");
			}

			return $queryinsert;
		}

		// $result =  array_map("unserialize", array_unique(array_map("serialize", $query)));

	}

	public function update_ed_pallet_detail_by_id($id, $ed)
	{
		$this->db->set("sku_stock_expired_date", $ed);
		$this->db->where("pallet_detail_id", $id);
		$this->db->update("pallet_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function update_tipe_pallet_detail_by_id($id, $tipe_id)
	{
		$this->db->set("penerimaan_tipe_id", $tipe_id);
		$this->db->where("pallet_detail_id", $id);
		$this->db->update("pallet_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function update_qty_pallet_detail_by_id($id, $qty)
	{
		$qty_ = ($qty == 0) || ($qty == "") ? 0 : $qty;

		$this->db->set("sku_stock_qty", $qty_);
		$this->db->where("pallet_detail_id", $id);
		$this->db->update("pallet_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function Get_KodePallet($pallet_jenis_id)
	{
		$query = $this->db->query("SELECT pallet_jenis_kode FROM pallet_jenis WHERE pallet_jenis_id = '$pallet_jenis_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->pallet_jenis_kode;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function save_data_penerimaan_pembelian($pb_id, $generate_kode, $client_id, $principle_id)
	{

		$this->db->set("penerimaan_pembelian_id", $pb_id);
		$this->db->set("depo_id", $this->session->userdata("depo_id"));
		$this->db->set("client_wms_id", $client_id);
		$this->db->set("penerimaan_pembelian_tgl", "GETDATE()", false);
		// $this->db->set("penerimaan_surat_jalan_id", $doc_fob);
		// $this->db->set("penerimaan_tipe_id", $value->penerimaan_tipe_id);
		$this->db->set("penerimaan_pembelian_kode", $generate_kode);
		$this->db->set("penerimaan_pembelian_who_create", $this->session->userdata("pengguna_username"));
		$this->db->set("penerimaan_pembelian_tgl_create", "GETDATE()", false);

		$this->db->set("penerimaan_pembelian_tgl_update", "GETDATE()", false);
		$this->db->set("penerimaan_pembelian_who_update", $this->session->userdata("pengguna_username"));

		$this->db->set("penerimaan_pembelian_status", "Open");
		$this->db->set("principle_id", $principle_id);

		$queryinsert = $this->db->insert("penerimaan_pembelian");

		return $queryinsert;
	}

	public function update_data_penerimaan_pembelian($pb_id, $keterangan)
	{
		$ket = $keterangan == "" ? NULL : $keterangan;

		$this->db->set("penerimaan_pembelian_keterangan", $ket);
		$this->db->where("penerimaan_pembelian_id", $pb_id);

		return $this->db->update("penerimaan_pembelian");
	}

	public function update_status_sj($sj_id)
	{
		$this->db->set("penerimaan_surat_jalan_status", "Close");
		$this->db->where("penerimaan_surat_jalan_id", $sj_id);
		return $this->db->update("penerimaan_surat_jalan");
		// return $this->db->query("UPDATE penerimaan_surat_jalan SET penerimaan_surat_jalan_status = 'Close' WHERE penerimaan_surat_jalan_id = '$sj_id'");
	}

	public function update_flag_pb($pb_id)
	{
		$this->db->set("penerimaan_pembelian_status", "Close");
		$this->db->where_in("penerimaan_pembelian_id", $pb_id);
		return $this->db->update("penerimaan_pembelian");
	}

	public function update_surat_jalan_in_progress($doc_fob, $type)
	{
		if ($type ==  "simpan") {
			$this->db->set("penerimaan_surat_jalan_status", "In Progress");
			$this->db->where_in("penerimaan_surat_jalan_id", $doc_fob);
			return $this->db->update("penerimaan_surat_jalan");
		}

		if ($type == "delete") {
			$this->db->set("penerimaan_surat_jalan_status", "Open");
			$this->db->where_in("penerimaan_surat_jalan_id", $doc_fob);
			return $this->db->update("penerimaan_surat_jalan");
		}
	}

	public function save_data_penerimaan_pembelian_detail($pb_id, $data)
	{

		$this->db->set("penerimaan_pembelian_detail_id", "NewID()", FALSE);
		$this->db->set("penerimaan_pembelian_id", $pb_id);
		$this->db->set("sku_id", $data['sku_id']);
		// $this->db->set("penerimaan_tipe_id", $data['tipe_id']);
		$this->db->set("sku_jumlah_barang", $data['hasil']);
		$this->db->set("sku_exp_date", $data['sku_expired_date']);
		$this->db->set("batch_no", $data['batch_no']);

		return $this->db->insert("penerimaan_pembelian_detail");
	}

	public function save_data_penerimaan_pembelian_detail2($pb_id, $pallet_id, $depo_detail_id, $pbd_2_id)
	{

		$this->db->set("penerimaan_pembelian_detail2_id", $pbd_2_id);
		$this->db->set("penerimaan_pembelian_id", $pb_id);
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("depo_detail_id", $depo_detail_id);

		$queryinsert = $this->db->insert("penerimaan_pembelian_detail2");

		return $queryinsert;
	}

	public function save_data_penerimaan_pembelian_detail3($pb_id, $data)
	{
		$data_ = $this->db->select("penerimaan_tipe_id as tipe_id")->from("penerimaan_surat_jalan")->where("penerimaan_surat_jalan_id", $data)->get()->row();

		$this->db->set("penerimaan_pembelian_detail3_id", "NewID()", FALSE);
		$this->db->set("penerimaan_pembelian_id", $pb_id);
		$this->db->set("penerimaan_surat_jalan_id", $data);
		$this->db->set("penerimaan_tipe_id", $data_->tipe_id);

		$queryinsert = $this->db->insert("penerimaan_pembelian_detail3");

		return $queryinsert;
	}

	public function get_pallet_temp($id)
	{

		$get_data = $this->db->select("*")->from('pallet_temp')->get()->result();
		$arr = array();

		foreach ($id as $keys => $value) {
			if ($get_data) {
				foreach ($get_data as $key => $val) {
					if ($val->id_temp != null) {
						if (in_array($value, json_decode($val->id_temp))) {
							array_push($arr, $val);
						}
					}
				}
			}
		}

		return array_map("unserialize", array_unique(array_map("serialize", $arr)));
	}

	public function get_pallet_detail_temp($id)
	{
		$query = $this->db->select("*")
			->from("pallet_detail_temp")
			->where("pallet_id", $id)
			->get()->result();
		return $query;
	}

	public function save_data_pallet_from_temp($pallet_id, $value)
	{
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("pallet_jenis_id", $value->pallet_jenis_id);
		$this->db->set("depo_id", $value->depo_id);
		$this->db->set("pallet_kode", $value->pallet_kode);
		$this->db->set("pallet_tanggal_create", "GETDATE()", false);
		$this->db->set("pallet_who_create", $value->pallet_who_create);
		$this->db->set("pallet_is_aktif", $value->pallet_is_aktif);

		$queryinsert = $this->db->insert("pallet");

		return $queryinsert;
	}

	public function save_data_pallet_detail_from_temp($pallet_detail_id, $pallet_id, $val, $pbd_2_id, $pb_id)
	{
		$this->db->set("pallet_detail_id", $pallet_detail_id);
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("sku_id", $val->sku_id);
		$this->db->set("sku_stock_expired_date", $val->sku_stock_expired_date);
		$this->db->set("sku_stock_qty", $val->sku_stock_qty);
		$this->db->set("penerimaan_tipe_id", $val->penerimaan_tipe_id);
		$this->db->set("sku_stock_expired_date_sj", $val->sku_exp_date);
		// $this->db->set("batch_no", $val->batch_no);

		$queryinsert = $this->db->insert("pallet_detail");

		$this->db->set("pallet_detail_id", $pallet_detail_id);
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("sku_id", $val->sku_id);
		$this->db->set("sku_stock_expired_date", $val->sku_stock_expired_date);
		$this->db->set("sku_stock_qty", $val->sku_stock_qty);
		$this->db->set("penerimaan_tipe_id", $val->penerimaan_tipe_id);
		$this->db->set("sku_stock_expired_date_sj", $val->sku_exp_date);
		$this->db->set("penerimaan_pembelian_detail2_id", $pbd_2_id);
		$this->db->set("penerimaan_pembelian_id", $pb_id);
		// $this->db->set("batch_no", $val->batch_no);

		$queryinsert = $this->db->insert("penerimaan_pembelian_detail2d");

		return $queryinsert;
		// return true;
	}

	public function delete_data_pallet_temp_by_doc_fob($id)
	{

		$this->db->where('pallet_id', $id);
		return $this->db->delete('pallet_temp');
	}

	public function delete_data_pallet_detail_temp_by_pallet_id($id)
	{
		$this->db->where('pallet_id', $id);
		return $this->db->delete('pallet_detail_temp');
	}

	public function get_data_header_view($id)
	{
		return $this->db->select("pb.penerimaan_pembelian_id as id,
                                    pb.penerimaan_pembelian_kode as kode,
                                    pb.penerimaan_pembelian_tgl as tgl,
                                    pb.penerimaan_pembelian_nopol as nopol,
                                    pb.penerimaan_pembelian_pengemudi as pengemudi,
                                    pb.penerimaan_pembelian_keterangan as keterangan,
                                    sj.penerimaan_surat_jalan_kode as kode_sj,
                                    sj.penerimaan_surat_jalan_no_sj as no_sj,
                                    cwp.client_wms_principle_top as tempo,
                                    pt.penerimaan_tipe_nama as tipe,
                                    ekspedisi.ekspedisi_kode as ekspedisi_kode,
                                    ekspedisi.ekspedisi_nama as ekspedisi_nama,
                                    dd.depo_detail_nama as depo_detail_nama")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_surat_jalan sj", "pb.penerimaan_surat_jalan_id = sj.penerimaan_surat_jalan_id", "left")
			->join("principle p", "sj.principle_id = p.principle_id", "left")
			->join("client_wms_principle cwp", "p.principle_id = cwp.principle_id", "left")
			->join("penerimaan_tipe pt", "pb.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->join("ekspedisi", "pb.ekspedisi_id = ekspedisi.ekspedisi_id", "left")
			->join("depo_detail dd", "pb.depo_detail_id = dd.depo_detail_id", "left")
			->where("pb.penerimaan_surat_jalan_id", $id)->get()->row();
	}

	public function get_print_header($id)
	{
		// return $this->db->select("pb.penerimaan_pembelian_id as pb_id,
		//                             pb.penerimaan_pembelian_kode as pb_kode,
		//                             FORMAT(pb.penerimaan_pembelian_tgl, 'dd-MM-yyyy') as pb_tgl,
		//                             ISNULL(pb.penerimaan_pembelian_keterangan, '-') as pb_keterangan,
		//                             pb.penerimaan_pembelian_nopol as pb_nopol,
		//                             pb.penerimaan_pembelian_pengemudi as pb_pengemudi,
		//                             pb.penerimaan_pembelian_who_create as pembuat,
		//                             p.pallet_id as pallet_id,
		//                             p.pallet_kode as pallet_kode,
		//                             dd.depo_detail_nama as gudang,
		//                             k.karyawan_nama as checker")
		//     ->from("penerimaan_pembelian pb")
		//     ->join("penerimaan_pembelian_detail2 pbd2", "pb.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id", "left")
		//     ->join("pallet p", "pbd2.pallet_id = p.pallet_id", "left")
		//     ->join("depo_detail dd", "pb.depo_detail_id = dd.depo_detail_id", "left")
		//     ->join("karyawan k", "pb.penerimaan_pembelian_nama_checker = k.karyawan_id", "left")
		//     ->where("pb.penerimaan_pembelian_id", $id)
		//     ->where("pb.depo_id", $this->session->userdata('depo_id'))
		//     ->get()->result();

		return $this->db->select("p.pallet_kode as pallet_kode")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_pembelian_detail2 pbd2", "pb.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id", "left")
			->join("pallet p", "pbd2.pallet_id = p.pallet_id", "left")
			->where("pb.penerimaan_pembelian_id", $id)
			->where("pb.depo_id", $this->session->userdata('depo_id'))
			->get()->result();
	}

	public function get_print_detail($id)
	{
		return $this->db->select("pd.pallet_id as pallet_id,
                                    pd.sku_stock_expired_date as ed,
                                    pd.sku_stock_qty as qty,
                                    sku.sku_kode as sku_kode,
                                    sku.sku_nama_produk as sku_nama,
                                    sku.sku_kemasan as sku_kemasan,
                                    sku.sku_satuan as sku_satuan")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_pembelian_detail2 pbd2", "pb.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id", "left")
			->join("pallet", "pbd2.pallet_id =  pallet.pallet_id", "left")
			->join("pallet_detail pd", "pallet.pallet_id = pd.pallet_id", "left")
			->join("sku", "pd.sku_id = sku.sku_id", "left")
			->where("pb.penerimaan_pembelian_id", $id)
			->order_by("sku.sku_kode", "ASC")
			->get()->result();
	}
	public function get_print_single_header($id)
	{
		return $this->db->select("p.pallet_kode as pallet_kode")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_surat_jalan sj", "pb.penerimaan_surat_jalan_id = sj.penerimaan_surat_jalan_id", "left")
			->join("penerimaan_pembelian_detail2 pbd2", "pb.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id", "left")
			->join("pallet p", "pbd2.pallet_id = p.pallet_id", "left")
			->where("p.pallet_id", $id)
			->where("pb.depo_id", $this->session->userdata('depo_id'))
			->get()->result();

		// return $this->db->select("pb.penerimaan_pembelian_id as pb_id,
		//                             pb.penerimaan_pembelian_kode as pb_kode,
		//                             FORMAT(pb.penerimaan_pembelian_tgl, 'dd-MM-yyyy') as pb_tgl,
		//                             ISNULL(pb.penerimaan_pembelian_keterangan, '-') as pb_keterangan,
		//                             pb.penerimaan_pembelian_nopol as pb_nopol,
		//                             pb.penerimaan_pembelian_pengemudi as pb_pengemudi,
		//                             pb.penerimaan_pembelian_who_create as pembuat,
		//                             p.pallet_id as pallet_id,
		//                             p.pallet_kode as pallet_kode,
		//                             dd.depo_detail_nama as gudang,
		//                             k.karyawan_nama as checker")
		//     ->from("penerimaan_pembelian pb")
		//     ->join("penerimaan_surat_jalan sj", "pb.penerimaan_surat_jalan_id = sj.penerimaan_surat_jalan_id", "left")
		//     ->join("penerimaan_pembelian_detail2 pbd2", "pb.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id", "left")
		//     ->join("pallet p", "pbd2.pallet_id = p.pallet_id", "left")
		//     ->join("depo_detail dd", "pb.depo_detail_id = dd.depo_detail_id", "left")
		//     ->join("karyawan k", "pb.penerimaan_pembelian_nama_checker = k.karyawan_id", "left")
		//     ->where("p.pallet_id", $id)
		//     ->where("pb.depo_id", $this->session->userdata('depo_id'))
		//     ->get()->result();
	}

	public function get_print_single_detail($id)
	{
		return $this->db->select("pd.pallet_id as pallet_id,
                                    pd.sku_stock_expired_date as ed,
                                    pd.sku_stock_qty as qty,
                                    sku.sku_kode as sku_kode,
                                    sku.sku_nama_produk as sku_nama,
                                    sku.sku_kemasan as sku_kemasan,
                                    sku.sku_satuan as sku_satuan")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_pembelian_detail2 pbd2", "pb.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id", "left")
			->join("pallet", "pbd2.pallet_id =  pallet.pallet_id", "left")
			->join("pallet_detail pd", "pallet.pallet_id = pd.pallet_id", "left")
			->join("sku", "pd.sku_id = sku.sku_id", "left")
			->where("pallet.pallet_id", $id)
			->order_by("sku.sku_kode", "ASC")
			->get()->result();
	}

	public function get_checker_by_depo($id)
	{
		$depo_id = $this->session->userdata('depo_id');
		return $this->db->select("k.karyawan_id AS id, k.karyawan_nama AS nama, kd.karyawan_divisi_nama as divisi, kl.karyawan_level_nama as level")
			->from("karyawan k")
			->join("karyawan_divisi kd", "k.karyawan_divisi_id = kd.karyawan_divisi_id", "left")
			->join("karyawan_level kl", "k.karyawan_level_id = kl.karyawan_level_id", "left")
			->where("k.depo_id", $depo_id)
			->where("kl.karyawan_level_id", "0C2CC2B3-B26C-4249-88BE-77BD0BA61C41")->get()->result();
		// return $this->db->select("k.karyawan_id   AS id, k.karyawan_nama AS nama")
		//     ->from("penerimaan_surat_jalan sj")
		//     ->join("principle p", "sj.principle_id = p.principle_id", "left")
		//     ->join("karyawan_principle kp", "p.principle_id = kp.principle_id", "left")
		//     ->join("karyawan k", "kp.karyawan_id = k.karyawan_id", "left")
		//     ->distinct()
		//     ->where("k.depo_id", $depo_id)
		//     ->where("k.karyawan_divisi_id", "E4F4AD27-6823-4026-BDDC-A977700B8ADA")
		//     ->where_in("sj.penerimaan_surat_jalan_id", $id)->get()->result();
	}

	public function check_data_in_sku_stock($id, $pb_id, $pallet_id)
	{
		// return $this->db->select("pb.client_wms_id,
		//                         pb.depo_id,
		//                         pb.depo_detail_id,
		//                         pd.sku_id,
		//                         pd.sku_stock_expired_date,
		//                         pd.pallet_detail_id,
		//                         sku.sku_induk_id,
		//                         SUM(pd.sku_stock_qty) as qty")
		//     ->from("penerimaan_pembelian pb")
		//     ->join("penerimaan_pembelian_detail2 pbd2", "pb.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id", "left")
		//     ->join("penerimaan_pembelian_detail3 pbd3", "pb.penerimaan_pembelian_id = pbd3.penerimaan_pembelian_id", "left")
		//     ->join("pallet p", "pbd2.pallet_id = p.pallet_id", "left")
		//     ->join("pallet_detail pd", "p.pallet_id = pd.pallet_id", "left")
		//     ->join("sku", "pd.sku_id = sku.sku_id", "left")
		//     ->where_in("pbd3.penerimaan_surat_jalan_id", $id)
		//     ->group_by("pb.client_wms_id,pb.depo_id,pb.depo_detail_id,pd.sku_id,pd.sku_stock_expired_date,pd.pallet_detail_id,sku.sku_induk_id")->get()->result();
		return $this->db->query("SELECT
                                    pb.client_wms_id,
                                    pb.depo_id,
                                    pb.depo_detail_id,
                                    pbd.pallet_detail_id,
                                    pbd.sku_id,
                                    pbd.sku_stock_expired_date,
                                    sku.sku_induk_id,
                                    pbd.sku_stock_qty AS qty
                                FROM (SELECT
                                        pbd2.penerimaan_pembelian_id,
                                        pd.pallet_detail_id,
                                        pd.sku_id,
                                        pd.sku_stock_expired_date,
                                        SUM(pd.sku_stock_qty) AS sku_stock_qty
                                    FROM penerimaan_pembelian_detail2 pbd2
                                    LEFT JOIN pallet p
                                        ON p.pallet_id = pbd2.pallet_id
                                    LEFT JOIN pallet_detail pd
                                        ON p.pallet_id = pd.pallet_id
                                    WHERE pbd2.penerimaan_pembelian_id IN (SELECT
                                                                                penerimaan_pembelian_id
                                                                            FROM penerimaan_pembelian_detail3
                                                                            WHERE penerimaan_surat_jalan_id IN (" . implode($id) . ")
                                                                                AND penerimaan_pembelian_id = '$pb_id'
                                                                            GROUP BY penerimaan_pembelian_id)
                                        AND pbd2.pallet_id = '$pallet_id'
                                    GROUP BY pbd2.penerimaan_pembelian_id,
                                            pd.pallet_detail_id,
                                            pd.sku_id,
                                            pd.sku_stock_expired_date) pbd
                                LEFT JOIN penerimaan_pembelian pb
                                    ON pb.penerimaan_pembelian_id = pbd.penerimaan_pembelian_id
                                LEFT JOIN sku
                                    ON pbd.sku_id = sku.sku_id")->result();
	}

	public function insert_data_to_sku_stock($sku_stock_id, $data, $gudang_asal)
	{
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
		$this->db->set("client_wms_id", $data->client_wms_id);
		$this->db->set("depo_id", $data->depo_id);
		// $this->db->set("depo_detail_id", $data->depo_detail_id);
		$this->db->set("depo_detail_id", $gudang_asal);
		$this->db->set("sku_induk_id", $data->sku_induk_id);
		$this->db->set("sku_id", $data->sku_id);
		$this->db->set("sku_stock_expired_date", $data->sku_stock_expired_date);
		$this->db->set("sku_stock_awal", 0);
		$this->db->set("sku_stock_masuk", $data->qty);
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

	public function update_sku_stock_id_in_pallet_detail($id, $sku_stock_id)
	{
		$this->db->query("UPDATE penerimaan_pembelian_detail2d SET sku_stock_id = '$sku_stock_id' WHERE pallet_detail_id = '$id'");
		return $this->db->query("UPDATE pallet_detail SET sku_stock_id = '$sku_stock_id' WHERE pallet_detail_id = '$id'");
	}

	public function update_data_to_sku_stock($data, $qty_stock)
	{
		$stock_masuk = $qty_stock->sku_stock_masuk + $data->qty;
		return $this->db->query("UPDATE sku_stock SET client_wms_id = '$data->client_wms_id',sku_stock_masuk = '$stock_masuk' WHERE sku_stock_id = '$qty_stock->sku_stock_id'");
	}

	public function check_data_sku_stock_by_params($data)
	{
		return $this->db->select("*")
			->from("sku_stock")
			->where("depo_id", $data->depo_id)
			->where("depo_detail_id", $data->depo_detail_id)
			->where("sku_id", $data->sku_id)
			->where("sku_stock_expired_date", $data->sku_stock_expired_date)
			->get()->row();
	}

	public function get_data_surat_jalan2($perusahaan_filter_sj, $principle_filter_sj)
	{

		return
			// $this->db->distinct();
			$this->db->select("sj.penerimaan_surat_jalan_id as sj_id,
                                    FORMAT(sj.penerimaan_surat_jalan_tgl, 'dd-MM-yyyy') as tgl,
                                    sj.penerimaan_surat_jalan_kode as sj_kode,
                                    sj.penerimaan_surat_jalan_no_sj as no_sj,
                                    sj.penerimaan_surat_jalan_status as status,
                                    ISNULL(sj.penerimaan_surat_jalan_keterangan,'') as keterangan,
                                    cw.client_wms_nama as pt,
                                    p.principle_kode as p_kode,
                                    p.principle_nama as p_nama,
                                    pt.penerimaan_tipe_nama as tipe")
			->from("penerimaan_surat_jalan sj")
			->join("principle p", "sj.principle_id = p.principle_id", "left")
			->join("client_wms cw", "sj.client_wms_id = cw.client_wms_id", "left")
			->join("penerimaan_tipe pt", "sj.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->join("penerimaan_surat_jalan_temp sjt", "sjt.penerimaan_surat_jalan_no_sj = sj.penerimaan_surat_jalan_no_sj", "left")
			->where("sj.depo_id", $this->session->userdata('depo_id'))
			->where("sj.client_wms_id", $perusahaan_filter_sj)
			->where("sj.principle_id", $principle_filter_sj)
			->where_in("sj.penerimaan_surat_jalan_status", array("Open", "In Progress", "Partially Received"))
			->order_by("sjt.shipment_number")->get()->result();


		// return $this->db->select("sj.penerimaan_surat_jalan_id as sj_id,
		//                         FORMAT(sj.penerimaan_surat_jalan_tgl, 'dd-MM-yyyy') as tgl,
		//                         sj.penerimaan_surat_jalan_kode as sj_kode,
		//                         sj.penerimaan_surat_jalan_no_sj as no_sj,
		//                         sj.penerimaan_surat_jalan_status as status,
		//                         ISNULL(sj.penerimaan_surat_jalan_keterangan,'') as keterangan,
		//                         cw.client_wms_nama as pt,
		//                         p.principle_kode as p_kode,
		//                         p.principle_nama as p_nama,
		//                         pt.penerimaan_tipe_nama as tipe,
		//                         pbd.sku_jumlah_barang as jml_barang,
		//                         pbd.sku_jumlah_terima as jml_terima")
		// ->from("penerimaan_surat_jalan sj")
		// ->join("principle p", "sj.principle_id = p.principle_id", "left")
		// ->join("client_wms cw", "sj.client_wms_id = cw.client_wms_id", "left")
		// ->join("penerimaan_tipe pt", "sj.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
		// ->join("penerimaan_pembelian_detail3 pbd3", "sj.penerimaan_surat_jalan_id = pbd3.penerimaan_surat_jalan_id", "left")
		// ->join("penerimaan_pembelian_detail pbd", "pbd3.penerimaan_pembelian_id = pbd.penerimaan_pembelian_id", "left")
		// ->where("sj.depo_id", $this->session->userdata('depo_id'))
		// ->where("sj.client_wms_id", $perusahaan_filter_sj)
		// ->where("sj.principle_id", $principle_filter_sj)
		// ->where_in("sj.penerimaan_surat_jalan_status", array("Open", "In Progress", "Partially Received"))->get()->result();
	}

	public function save_to_temp_before($pb_id, $value)
	{
		$this->db->set("penerimaan_pembelian_detail3_id", "NewID()", FALSE);
		$this->db->set("penerimaan_pembelian_id", $pb_id);
		$this->db->set("penerimaan_surat_jalan_id", $value);

		return $this->db->insert("penerimaan_pembelian_detail3");
	}

	public function get_data_gudang_tujuan()
	{
		return $this->db->select("depo_detail_id, depo_detail_nama")
			->from("depo_detail")
			->where("depo_id", $this->session->userdata('depo_id'))->get()->result();
	}

	public function get_pb_id($id)
	{
		return $this->db->select("penerimaan_pembelian_id as id")->from("penerimaan_pembelian_detail3")->where_in("penerimaan_surat_jalan_id", $id)->get()->row();
	}

	public function chk_data($id)
	{
		return $this->db->select("*")->from("penerimaan_pembelian")->where("penerimaan_pembelian_id", $id)->get()->row();
	}

	public function save_data_to_distribusi_penerimaan($dp_id, $generate_kode, $tgl, $no_penerimaan, $status, $keterangan, $gudang_asal)
	{
		$tgl_ = $tgl . " " . date('H:i:s');
		$ket = $keterangan == "" ? NULL : $keterangan;

		$this->db->set("distribusi_penerimaan_id", $dp_id);
		$this->db->set("penerimaan_pembelian_id", $no_penerimaan);
		$this->db->set("distribusi_penerimaan_kode", $generate_kode);
		$this->db->set("distribusi_penerimaan_tanggal", $tgl_);
		$this->db->set("distribusi_penerimaan_status", $status);
		$this->db->set("distribusi_penerimaan_keterangan", $ket);
		$this->db->set("depo_id_asal", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id_asal", $gudang_asal);
		$this->db->set("distribusi_penerimaan_tgl_create", "GETDATE()", false);
		$this->db->set("distribusi_penerimaan_who_create", $this->session->userdata('pengguna_username'));

		$queryinsert = $this->db->insert("distribusi_penerimaan");

		return $queryinsert;
		// return true;
	}

	public function get_data_dpdt_by_no_penerimaan($id, $pallet_id)
	{
		return $this->db->select("*")
			->from("penerimaan_pembelian_detail2")
			->where("penerimaan_pembelian_id", $id)
			->where("pallet_id", $pallet_id)
			->get()->result();
	}

	public function get_data_dpdt_by_no_penerimaan2($id, $detail_id_tujuan, $pallet_id)
	{
		return $this->db->select("*")
			->from("penerimaan_pembelian_detail2")
			->where("penerimaan_pembelian_id", $id)
			->where("depo_detail_id", $detail_id_tujuan)
			->where("pallet_id", $pallet_id)
			->get()->result();
	}

	public function save_data_to_distribusi_penerimaan_detail($dp_id, $data)
	{
		$this->db->set("distribusi_penerimaan_detail_id", "NewID()", FALSE);
		$this->db->set("distribusi_penerimaan_id", $dp_id);
		$this->db->set("depo_id_tujuan", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id_tujuan", $data->depo_detail_id);
		$this->db->set("pallet_id", $data->pallet_id);
		$this->db->set("is_valid", 1);

		$queryinsert = $this->db->insert("distribusi_penerimaan_detail");
		return $queryinsert;
	}

	public function save_data_to_mutasi_pallet_draft($mp_id, $dp_id, $generate_kode_, $tgl, $principle, $status, $keterangan, $gudang_asal, $depo_detail_id_tujuan, $checker, $client_id)
	{
		$karyawanNama = $this->db->select("karyawan_nama")->from('karyawan')->where('karyawan_id', $checker)->get()->row();
		$tgl_ = $tgl . " " . date('H:i:s');

		$ket = $keterangan == "" ? null : $keterangan;
		$this->db->set("tr_mutasi_pallet_draft_id", $mp_id);
		$this->db->set("distribusi_penerimaan_id", $dp_id);
		$this->db->set("principle_id", $principle);
		$this->db->set("tr_mutasi_pallet_draft_kode", $generate_kode_);
		$this->db->set("tr_mutasi_pallet_draft_tanggal", $tgl_);
		$this->db->set("tipe_mutasi_id", "E81C03FB-7B0F-4D04-A971-D8D7232E4A69");
		$this->db->set("tr_mutasi_pallet_draft_keterangan", $ket);
		$this->db->set("tr_mutasi_pallet_draft_status", "In Progress");
		$this->db->set("depo_id_asal", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id_asal", $gudang_asal);
		$this->db->set("depo_id_tujuan", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id_tujuan", $depo_detail_id_tujuan);
		$this->db->set("tr_mutasi_pallet_draft_tgl_create", "GETDATE()", false);
		$this->db->set("tr_mutasi_pallet_draft_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("tr_mutasi_pallet_draft_nama_checker", $karyawanNama->karyawan_nama);
		$this->db->set("client_wms_id", $client_id);

		$this->db->set("tr_mutasi_pallet_draft_tgl_update", "GETDATE()", false);
		$this->db->set("tr_mutasi_pallet_draft_who_update", $this->session->userdata('pengguna_username'));

		$queryinsert = $this->db->insert("tr_mutasi_pallet_draft");

		return $queryinsert;
		// return true;
	}

	public function save_data_to_mutasi_pallet_detail_draft($mp_id, $pallet_id)
	{
		$this->db->set("tr_mutasi_pallet_detail_draft_id", "NewID()", FALSE);
		$this->db->set("tr_mutasi_pallet_draft_id", $mp_id);
		$this->db->set("pallet_id", $pallet_id);

		$queryinsert = $this->db->insert("tr_mutasi_pallet_detail_draft");

		return $queryinsert;
	}

	public function get_pallet_detail_id($id, $pallet_id)
	{
		return $this->db->select("pd.pallet_detail_id, pd.sku_id")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_pembelian_detail2 pbd2", "pb.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id", "left")
			->join("pallet p", "pbd2.pallet_id = p.pallet_id", "left")
			->join("pallet_detail pd", "p.pallet_id = pd.pallet_id", "left")
			->where("pb.penerimaan_pembelian_id", $id)
			->where("pbd2.pallet_id", $pallet_id)
			->order_by("pd.sku_id", "ASC")->get()->result();
	}

	public function get_width_and_lenght_by_depo($id)
	{
		return $this->db->select("depo_width, depo_length, depo_nama")->from("depo")->where("depo_id", $id)->get()->row();
	}

	public function GetDepoDetailMenu($id)
	{
		return $this->db->select("*")->from("depo_detail")->where("depo_id", $id)->get()->result();
	}

	public function delete_penerimaan_pembelian($id)
	{
		return $this->db->where('penerimaan_pembelian_id', $id)->delete('penerimaan_pembelian');
	}

	public function delete_penerimaan_pembelian_detail($id)
	{
		return $this->db->where('penerimaan_pembelian_id', $id)->delete('penerimaan_pembelian_detail');
	}

	public function delete_penerimaan_pembelian_detail2($id)
	{
		return $this->db->where('penerimaan_pembelian_id', $id)->delete('penerimaan_pembelian_detail2');
	}

	public function delete_penerimaan_pembelian_detail3($id)
	{
		return $this->db->where('penerimaan_pembelian_id', $id)->delete('penerimaan_pembelian_detail3');
	}

	public function delete_penerimaan_pembelian_detail4($id)
	{
		return $this->db->where('penerimaan_pembelian_id', $id)->delete('penerimaan_pembelian_detail4');
	}

	public function get_pallet_real($id)
	{
		return $this->db->select("p.pallet_id")->from("penerimaan_pembelian_detail2 pbd2")
			->join("pallet p", "pbd2.pallet_id = p.pallet_id", "left")->where("pbd2.penerimaan_pembelian_id", $id)
			->get()->result();
	}

	public function delete_pallet_real($id)
	{
		return $this->db->where('pallet_id', $id)->delete('pallet');
	}

	public function delete_pallet_detail_real($id)
	{
		return $this->db->where('pallet_id', $id)->delete('pallet_detail');
	}

	public function getDataheaderPb($id)
	{
		return $this->db->select("pb.penerimaan_pembelian_id as id,
                                    pb.penerimaan_pembelian_kode as kode,
                                    FORMAT(pb.penerimaan_pembelian_tgl, 'yyyy-MM-dd') as tgl,
                                    e.ekspedisi_id as e_id,
                                    e.ekspedisi_kode as e_kode,
                                    e.ekspedisi_nama as e_nama,
                                    pb.penerimaan_pembelian_nopol as nopol,
                                    pb.penerimaan_pembelian_pengemudi as pengemudi,
                                    dd.depo_detail_id as gudang_id,
                                    dd.depo_detail_nama as gudang,
                                    k.karyawan_id as checker_id,
                                    k.karyawan_nama as checker,
                                    ISNULL(pb.penerimaan_pembelian_keterangan, '') as keterangan,
                                    psj.client_wms_id,
	                                psj.principle_id,
                                    psj.penerimaan_surat_jalan_reason,
																		pb.penerimaan_pembelian_tgl_update")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_pembelian_detail3 pbd3", "pb.penerimaan_pembelian_id = pbd3.penerimaan_pembelian_id", "left")
			->join("penerimaan_surat_jalan psj", "pbd3.penerimaan_surat_jalan_id = psj.penerimaan_surat_jalan_id", "left")
			->join("depo_detail dd", "pb.depo_detail_id = dd.depo_detail_id", "left")
			->join("ekspedisi e", "pb.ekspedisi_id = e.ekspedisi_id", "left")
			->join("karyawan k", "pb.penerimaan_pembelian_nama_checker = k.karyawan_id", "left")
			->where("pb.penerimaan_pembelian_id", $id)->get()->row();
	}

	public function getDataBySuratJalanID($suratJalanID)
	{
		return $this->db->select("ekspedisi_id,
									security_logbook_nama_driver,
									security_logbook_nopol")
			->from("security_logbook")
			->where("penerimaan_surat_jalan_id", $suratJalanID)->get()->row();
	}

	public function getDataPalletPb($id)
	{
		return $this->db->select("p.pallet_id as id,
                                    p.pallet_kode as kode,
                                    pj.pallet_jenis_nama as jenis,
                                    dd.depo_detail_nama as gudang")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_pembelian_detail2 pbd2", "pb.penerimaan_pembelian_id = pbd2.penerimaan_pembelian_id", "left")
			->join("pallet p", "pbd2.pallet_id = p.pallet_id", "left")
			->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left")
			->join("depo_detail dd", "pbd2.depo_detail_id = dd.depo_detail_id", "left")
			->distinct()
			->where("pb.penerimaan_pembelian_id", $id)
			->order_by("p.pallet_kode", "ASC")->get()->result();
	}

	public function get_sj_id($id)
	{
		return $this->db->select("pbd3.penerimaan_surat_jalan_id as id")->from("penerimaan_pembelian_detail3 pbd3")
			->where("pbd3.penerimaan_pembelian_id", $id)->get()->result();
	}

	public function get_sku_id_by_id($id)
	{

		$arr =  array();

		$data = $this->db->select("psjd.sku_id as id")->from("penerimaan_surat_jalan psj")
			->join("penerimaan_surat_jalan_detail psjd", "psj.penerimaan_surat_jalan_id = psjd.penerimaan_surat_jalan_id", "left")->where_in("psj.penerimaan_surat_jalan_id", $id)->get()->result();

		foreach ($data as $key => $value) {
			array_push($arr, $value->id);
		}

		return $arr;
	}

	public function get_jumlah_terima_by_sku_id_and_id($id, $sku_id)
	{
		return $this->db->select("pbd.sku_jumlah_terima, pbd.sku_id, pbd.penerimaan_pembelian_id")->from("penerimaan_pembelian_detail pbd")
			->join("penerimaan_pembelian_detail3 pbd3", "pbd.penerimaan_pembelian_id = pbd3.penerimaan_pembelian_id", "left")
			->distinct()
			->where_in("pbd3.penerimaan_surat_jalan_id", $id)
			->where_in("pbd.sku_id", $sku_id)
			->order_by("pbd.sku_id", "ASC")
			->get()->result();
	}

	public function get_perusahaan($id)
	{
		return $this->db->select("client_wms_id as id, client_wms_nama as nama")->from("client_wms")->where("client_wms_id", $id)->get()->row();
	}

	public function get_principle($id)
	{
		return $this->db->select("principle_id as id, principle_nama as nama")->from("principle")->where("principle_id", $id)->get()->row();
	}

	public function check_data_ed_in_pallet_detail_temp($id)
	{
		return $this->db->select("pallet_detail_id as id, sku_stock_expired_date as ed")->from("pallet_detail_temp")->where("pallet_detail_id", $id)->get()->row();
	}

	public function get_sku_in_pallet_temp($id)
	{
		$get_data = $this->db->select("pallet_id, id_temp")->from('pallet_temp')->get()->result();
		$arr = array();

		foreach ($id as $keys => $value) {
			if ($get_data) {
				foreach ($get_data as $key => $val) {
					if ($val->id_temp != null) {
						if (in_array($value, json_decode($val->id_temp))) {
							array_push($arr, $val->pallet_id);
						}
					}
				}
			}
		}

		$t = array_map("unserialize", array_unique(array_map("serialize", $arr)));

		if ($t != null) {
			$detail = $this->db->select("sku_id, sku_stock_expired_date as ed")->from("pallet_detail_temp")->where_in("pallet_id", $t)->get()->result_array();
			return array_map("unserialize", array_unique(array_map("serialize", $detail)));
		}
	}

	public function get_qty_in_pallet_detail_temp($id)
	{
		$get_data = $this->db->select("pallet_id, id_temp")->from('pallet_temp')->get()->result();
		$arr = array();

		foreach ($id as $keys => $value) {
			if ($get_data) {
				foreach ($get_data as $key => $val) {
					if ($val->id_temp != null) {
						if (in_array($value, json_decode($val->id_temp))) {
							array_push($arr, $val->pallet_id);
						}
					}
				}
			}
		}

		$t = array_map("unserialize", array_unique(array_map("serialize", $arr)));

		if ($t != null) {
			return $this->db->select("pallet_detail_temp.sku_id, 
            sku.sku_kode,
                                        sum(pallet_detail_temp.sku_stock_qty) as sku_stock_qty, 
                                        pallet_detail_temp.sku_stock_expired_date")
				->from("pallet_detail_temp")
				->join("sku", "pallet_detail_temp.sku_id = sku.sku_id", "left")
				->where_in("pallet_detail_temp.pallet_id", $t)
				->group_by("pallet_detail_temp.sku_id, sku.sku_kode,pallet_detail_temp.sku_stock_expired_date")
				->order_by("pallet_detail_temp.sku_id", "ASC")->get()->result();
			// $result = array();
			// foreach ($detail as $k => $v) {
			//     // echo json_encode($v);
			//     // $sku_id = $v['sku_id'];
			//     // $result[$sku_id][] = $v['sku_stock_qty'];
			// }

			// echo json_encode($detail);

			// // foreach ($result as $key => $value) {
			// //     $new[] = array('sku_id' => $key, 'sku_stock_qty' => array_sum($value), 'sku_stock_expired_date');
			// // }
		}
	}

	public function get_area_rak_gudang($tipe_stock, $client_wms, $principle, $depo)
	{

		$data = $this->db->select('depo_detail_id as id')->from('depo_detail')->where('depo_id', $depo)->get()->result();
		$depo_detail_id = [];
		foreach ($data as $key => $value) {
			array_push($depo_detail_id, $value->id);
		}


		return $this->db->select("rak.depo_detail_id, rak.rak_id")
			->from("rak")
			->join("rak_lajur_detail rjd", "rak.rak_id = rjd.rak_id", "left")
			->join("depo_detail dd", "rak.depo_detail_id = dd.depo_detail_id", "left")
			->distinct()
			->where("rak.depo_id", $depo)
			->where_in("rak.depo_detail_id", $depo_detail_id)
			// ->where("rjd.client_wms_id", $client_wms)
			// ->where("rjd.principle_id", $principle)
			->where("rjd.rak_lajur_detail_tipe_stock", $tipe_stock)->get()->result();
	}

	public function get_data_rak_by_id($rak_id, $tipe_stock)
	{
		return $this->db->select("dd.depo_detail_nama,
                                cw.client_wms_nama,
                                p.principle_nama,
                                rjd.rak_lajur_detail_tipe_stock,
                                count(rjd.rak_lajur_detail_nama) as jumlah_rak,
                                count(rjdp.rak_lajur_detail_pallet_id) as jumlah_terisi,
                                count(rjd.rak_lajur_detail_nama) - count(rjdp.rak_lajur_detail_pallet_id) as jumlah_kosong")
			->from("rak")
			// ->join("rak_lajur rj", "rak.rak_id = rj.rak_id", "left")
			->join("rak_lajur_detail rjd", "rak.rak_id = rjd.rak_id", "left")
			->join("rak_lajur_detail_pallet rjdp", "rjd.rak_lajur_detail_id = rjdp.rak_lajur_detail_id", "left")
			->join("client_wms cw", "rjd.client_wms_id = cw.client_wms_id", "left")
			->join("principle p", "rjd.principle_id = p.principle_id", "left")
			->join("depo_detail dd", "rak.depo_detail_id = dd.depo_detail_id", "left")
			->where("rak.rak_id", $rak_id)
			->where("rjd.rak_lajur_detail_tipe_stock", $tipe_stock)
			->group_by("dd.depo_detail_nama, cw.client_wms_nama, p.principle_nama, rjd.rak_lajur_detail_tipe_stock")
			->get()->row();
	}

	public function check_data_when_status_close_show_alert($id)
	{
		return $this->db->select("cw.client_wms_nama, 
                                    p.principle_nama,
                                    COUNT(CASE 
                                            WHEN pb.penerimaan_pembelian_status = 'Open' then pb.penerimaan_pembelian_status
                                        END) status_open,
                                    COUNT(CASE 
                                            WHEN pb.penerimaan_pembelian_status = 'Close' then pb.penerimaan_pembelian_status
                                        END) status_close")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_pembelian_detail3 pbd3", "pb.penerimaan_pembelian_id = pbd3.penerimaan_pembelian_id", "left")
			->join("penerimaan_surat_jalan psj", "pbd3.penerimaan_surat_jalan_id = psj.penerimaan_surat_jalan_id", "left")
			->join("client_wms cw", "psj.client_wms_id =  cw.client_wms_id", "left")
			->join("principle p", "psj.principle_id = p.principle_id", "left")
			->where_in("pbd3.penerimaan_surat_jalan_id", $id)
			->group_by("cw.client_wms_nama, p.principle_nama")->get()->row();
	}


	public function checkKodePallet($surat_jalan_id, $client_id,  $principle_id,  $kode)
	{
		// return $this->db->select("*")
		//     ->from("pallet_generate pg")
		//     ->join("pallet_generate_detail pgd", "pg.pallet_generate_id = pgd.pallet_generate_id", "left")
		//     ->join("pallet_generate_detail2 pgd2", "pg.pallet_generate_id = pgd2.pallet_generate_id", "left")
		//     ->where("pg.client_wms_id", $client_id)
		//     ->where("pg.principle_id", $principle_id)
		//     ->where_in("pgd.penerimaan_surat_jalan_id", $surat_jalan_id)
		//     ->where("pgd2.pallet_generate_detail2_kode", $kode)->get()->row();

		return $this->db->select("*")
			->from("pallet_generate_detail2 pgd2")
			->where("pgd2.pallet_generate_detail2_kode", $kode)->get()->row();
	}

	public function existKodePallet($kode)
	{
		return $this->db->select("*")
			->from("pallet")
			->where("pallet_kode", $kode)->get()->row();
	}

	public function existKodePalletTemp($kode)
	{
		return $this->db->select("pallet_temp.*, karyawan.karyawan_nama")
			->from("pallet_temp")
			->join("karyawan", "pallet_temp.karyawan_id = karyawan.karyawan_id", "left")
			->where("pallet_temp.pallet_kode", $kode)->get()->row();
	}

	public function checkKodeSKU($surat_jalan_id, $kodeSKU, $mode)
	{
		$query =  $this->db->select("psjd.sku_id, psjd.sku_exp_date")
			->from("sku")
			->join("penerimaan_surat_jalan_detail psjd", "sku.sku_id = psjd.sku_id", "left")
			->where_in("psjd.penerimaan_surat_jalan_id", $surat_jalan_id);
		if ($mode == 'scan') {
			$query->where("sku.sku_kode_sku_principle", $kodeSKU);
		} else {
			$query->where("sku.sku_kode", $kodeSKU);
		}
		$query->order_by("sku.sku_konversi_level", "DESC");

		$data = $query->get()->result();

		if (empty($data)) {
			$query2 = $this->db->select("sku_id,
                                        FORMAT(DATEADD(MONTH, sku_minimum_expired_date, DATEADD(month, DATEDIFF(month, 0, GETDATE()), 0)), 'yyyy-MM-dd') as sku_exp_date,
                                        sku_kemasan,
                                        sku_satuan,
                                        sku_kode,
                                        sku_nama_produk")
				->from("sku");
			if ($mode == 'scan') {
				$query2->where("sku_kode_sku_principle", $kodeSKU);
			} else {
				$query2->where("sku_kode", $kodeSKU);
			}
			$query2->order_by("sku.sku_konversi_level", "DESC");

			$result = $query->get()->result();
			return $result;
		} else {
			return $data;
		}
	}

	public function getDataSuratJalanDetail($sjId)
	{
		// return $this->db->select("sku_id, SUM(isnull(sku_jumlah_barang, 0)) as sku_jumlah_barang, sku_exp_date")
		//     ->from("penerimaan_surat_jalan_detail")->where_in("penerimaan_surat_jalan_id", $sjId)->group_by("sku_id, sku_exp_date")->get()->result();

		return $this->db->select("a.sku_id, a.sku_exp_date, sum(a.sku_jumlah_barang - isnull(a.sku_jumlah_barang_terima, 0)) as sku_qty, 
                                    sum((a.sku_jumlah_barang - isnull(a.sku_jumlah_barang_terima, 0)) * b.sku_konversi_faktor) as sku_qty_composite, a.batch_no")
			->from("penerimaan_surat_jalan_detail a")
			->join("sku b", "a.sku_id = b.sku_id", "left")
			->where_in("a.penerimaan_surat_jalan_id", $sjId)->group_by("a.sku_id, a.sku_exp_date,a.batch_no")->get()->result();
	}



	public function getPalletTempByPalletId($pallet_id)
	{
		return $this->db->select("*")->from("pallet_temp")->where("pallet_id", $pallet_id)->get()->row();
	}

	public function getDataAndSumPalletDetail($sku_id, $penerimaanBarangId)
	{
		return $this->db->query("SELECT
                                    sku_id,
                                    sku_stock_expired_date,
                                    sku_stock_expired_date_sj,
                                    SUM(sku_stock_qty) as sku_stock_qty
                                FROM pallet_detail
                                WHERE pallet_id in (SELECT pallet_id FROM penerimaan_pembelian_detail2 WHERE penerimaan_pembelian_id = '$penerimaanBarangId')
                                        AND sku_id IN(" . $sku_id . ")
                                group by sku_id,
                                sku_stock_expired_date,
                                sku_stock_expired_date_sj")->result();
	}

	public function getSkuIdPalletDetail($penerimaanBarangId)
	{
		return $this->db->query("SELECT
                                    sku_id
                                FROM penerimaan_pembelian_detail2d
                                WHERE penerimaan_pembelian_id = '$penerimaanBarangId'")->result();
	}

	public function updateDetailPenerimaanBarangById($penerimaanBarangId, $value)
	{

		$this->db->set("sku_jumlah_terima", $value['jumlah_terima']);
		$this->db->where("penerimaan_pembelian_id", $penerimaanBarangId);
		$this->db->where("sku_id", $value['sku_id']);
		$this->db->where("sku_exp_date", $value['sku_exp_date']);
		return $this->db->update("penerimaan_pembelian_detail");
	}

	public function updatePenerimaanPembelian($id, $data, $mode)
	{
		$this->db->set($mode, $data);
		$this->db->where("penerimaan_pembelian_id", $id);
		return $this->db->update("penerimaan_pembelian");
	}

	public function checkPalletIsreadyInPbd2($penerimaanBarangId)
	{
		return $this->db->select("pallet.pallet_id,pallet.pallet_kode")
			->from("penerimaan_pembelian_detail2 pbd2")
			->join("pallet", "pbd2.pallet_id = pallet.pallet_id", "left")
			->distinct()
			->where("pbd2.penerimaan_pembelian_id", $penerimaanBarangId)->get()->result();
	}

	public function getDataPenerimaanById($penerimaanBarangId)
	{
		return $this->db->select("pb.client_wms_id, pb.principle_id, pbd3.penerimaan_surat_jalan_id as sj_id")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_pembelian_detail3 pbd3", "pb.penerimaan_pembelian_id = pbd3.penerimaan_pembelian_id", "left")
			->where("pb.penerimaan_pembelian_id", $penerimaanBarangId)->get()->result();
	}

	public function requestUpdateHeaderService($penerimaanBarangId, $gudang_penerima, $expedisi)
	{
		$this->db->set("depo_detail_id", $gudang_penerima);
		$this->db->set("ekspedisi_id", $expedisi != "" ? $expedisi : NULL);
		$this->db->where("penerimaan_pembelian_id", $penerimaanBarangId);
		return $this->db->update("penerimaan_pembelian");
	}

	public function requestUpdateHeaderVehicle($penerimaanBarangId, $gudang_penerima, $no_kendaraan)
	{
		$this->db->set("depo_detail_id", $gudang_penerima);
		$this->db->set("penerimaan_pembelian_nopol", $no_kendaraan != "" ? $no_kendaraan : NULL);
		$this->db->where("penerimaan_pembelian_id", $penerimaanBarangId);
		return $this->db->update("penerimaan_pembelian");
	}

	public function requestUpdateHeaderDriver($penerimaanBarangId, $gudang_penerima, $nama_pengemudi)
	{
		$this->db->set("depo_detail_id", $gudang_penerima);
		$this->db->set("penerimaan_pembelian_pengemudi", $nama_pengemudi != "" ? $nama_pengemudi : NULL);
		$this->db->where("penerimaan_pembelian_id", $penerimaanBarangId);
		return $this->db->update("penerimaan_pembelian");
	}

	public function requestUpdateHeaderChecker($penerimaanBarangId, $gudang_penerima, $checker)
	{
		$dataChecker = $this->db->select("*")->from("penerimaan_pembelian_detail4")->where("penerimaan_pembelian_id", $penerimaanBarangId)->get()->result();

		$dataInsert = [];

		if (!empty($checker)) {
			foreach ($checker as $key => $value) {
				$newId = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];
				$chkIfExistData = $this->db->get_where("penerimaan_pembelian_detail4", [
					'penerimaan_pembelian_id' => $penerimaanBarangId,
					'karyawan_id' => $value
				])->row();

				if ($chkIfExistData == null) {
					$dataInsert[] = [
						'penerimaan_pembelian_detail4_id' => $newId,
						'penerimaan_pembelian_id' => $penerimaanBarangId,
						'karyawan_id' => $value
					];
					// $this->db->set("penerimaan_pembelian_detail4_id", "NewID()", FALSE);
					// $this->db->set("penerimaan_pembelian_id", $penerimaanBarangId);
					// $this->db->set("karyawan_id", $value);
					// $this->db->insert("penerimaan_pembelian_detail4");
				}
			}
		}

		$this->db->insert_batch('penerimaan_pembelian_detail4', $dataInsert);

		if (!empty($dataChecker)) {
			foreach ($dataChecker as $key => $value) {
				if (!in_array($value->karyawan_id, $checker)) {
					$this->db->delete("penerimaan_pembelian_detail4", ['penerimaan_pembelian_detail4_id' => $value->penerimaan_pembelian_detail4_id]);
				}
			}
		}

		// $this->db->set("depo_detail_id", $gudang_penerima);
		// $this->db->set("penerimaan_pembelian_nama_checker", $checker != "" ? $checker : NULL);
		// $this->db->where("penerimaan_pembelian_id", $penerimaanBarangId);
		// return $this->db->update("penerimaan_pembelian");
	}

	public function requestUpdateHeaderketerangan($penerimaanBarangId, $gudang_penerima, $keterangan)
	{
		$this->db->set("depo_detail_id", $gudang_penerima);
		$this->db->set("penerimaan_pembelian_keterangan", $keterangan != "" ? $keterangan : NULL);
		$this->db->where("penerimaan_pembelian_id", $penerimaanBarangId);
		return $this->db->update("penerimaan_pembelian");
	}

	public function checkPalletConfirm($id)
	{
		return $this->db->select("pallet_id")->from("penerimaan_pembelian_detail2")->where("penerimaan_pembelian_id", $id)->get()->result();
	}

	public function dataPenerimaanbarangDetail($penerimaanBarangId)
	{
		return $this->db->select("penerimaan_pembelian_id, penerimaan_pembelian_detail_id, sku_id, sku_exp_date, isnull(sku_jumlah_terima, 0) as sku_jumlah_terima")->from("penerimaan_pembelian_detail")->where("penerimaan_pembelian_id", $penerimaanBarangId)->get()->result();
	}

	public function saveDataToKonversiTemp($newId, $value)
	{
		$this->db->set("sku_konversi_temp_id", $newId);
		$this->db->set("sku_id", $value->sku_id);
		$this->db->set("sku_expired_date", $value->sku_exp_date);
		$this->db->set("sku_qty", $value->sku_qty);
		$this->db->set("sku_qty_composite", (int)$value->sku_qty_composite);
		// $this->db->set("batch_no", $value->batch_no);

		return $this->db->insert("sku_konversi_temp");
	}

	public function getKodeAutoComplete($value, $type)
	{

		if ($type == 'pallet') {
			return $this->db->select("CONCAT(REVERSE(PARSENAME(REPLACE(REVERSE(pallet_generate_detail2_kode), '/', '.'), 2)), '/', REVERSE(PARSENAME(REPLACE(REVERSE(pallet_generate_detail2_kode), '/', '.'), 3))) as kode")
				->from("pallet_generate_detail2")
				->where("pallet_generate_detail2_is_aktif", 0)
				->like("pallet_generate_detail2_kode", $value)->get()->result();
		}

		if ($type == 'sku') {
			return $this->db->select("sku_kode as kode")
				->from("sku")
				->like("sku_kode", $value)->get()->result();
		}
		if ($type == 'newPallet') {
			$depo_id = $this->session->userdata('depo_id');

			// return $this->dbwms->select("a.pallet_generate_detail2_kode as kode")
			// 	->from("pallet_generate_detail2 a")
			// 	->join("pallet_generate b", "a.pallet_generate_id = b.pallet_generate_id", 'left')
			// 	->where("b.depo_id", $depo_id)
			// 	->where("a.pallet_generate_detail2_is_aktif", 0)
			// 	->like("a.pallet_generate_detail2_kode", $value)->get()->result();

			return $this->db->query("SELECT
				kode, is_aktif
			  FROM (SELECT
				a.pallet_generate_detail2_kode AS kode,
				a.pallet_generate_detail2_is_aktif as is_aktif
			  FROM pallet_generate_detail2 a
			  LEFT JOIN pallet_generate b
				ON a.pallet_generate_id = b.pallet_generate_id
			  WHERE b.depo_id = '$depo_id'
			  AND a.pallet_generate_detail2_is_aktif = 0
			  AND a.pallet_generate_detail2_kode LIKE '%$value%' ESCAPE '!'
			--   UNION
			--   SELECT
			-- 	pallet_kode AS kode,
			-- 	pallet_is_aktif as is_aktif
			--   FROM pallet
			--   WHERE depo_id = '$depo_id'
			--   AND pallet_kode LIKE '%$value%' ESCAPE '!'
			  ) AS combined_result
			  ")->result();

			// return $this->dbwms->query("
			//   SELECT
			// 	pallet_kode AS kode,
			// 	pallet_is_aktif as is_aktif
			//   FROM pallet
			//   WHERE depo_id = '$depo_id'
			//   AND pallet_is_aktif = 1
			//   AND pallet_kode LIKE '%$value%' ESCAPE '!'")->result();
		}
	}

	public function getDataDetailCetakBuktiPenerimaan($id)
	{
		return $this->db->query("select pbd.penerimaan_pembelian_detail_id, pbd.penerimaan_pembelian_id, pbd.batch_no,
		pbd.sku_id, pbd.sku_jumlah_barang as jml_barang, sku.sku_kode,sku.sku_nama_produk AS sku_nama, sku.sku_kemasan,sku_satuan,pbd.sku_exp_date from penerimaan_pembelian_detail pbd
		left join sku on sku.sku_id = pbd.sku_id
		where penerimaan_pembelian_id ='$id'")->result();
	}
	public function getDataDetailCheckerCetakBuktiPenerimaan($id)
	{

		return $this->db->query("select k.karyawan_nama from penerimaan_pembelian_detail4 pb4
		left join karyawan k on k.karyawan_id = pb4.karyawan_id
		where penerimaan_pembelian_id ='$id'
		")->result();
	}
}
