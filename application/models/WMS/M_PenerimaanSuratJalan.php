<?php

date_default_timezone_set('Asia/Jakarta');

class M_PenerimaanSuratJalan extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function get_data_surat_jalan($tahun, $bulan, $perusahaan, $principle, $tipe_penerimaan, $status)
	{

		$this->db->select("sj.penerimaan_surat_jalan_id as sj_id,
                                    FORMAT(sj.penerimaan_surat_jalan_tgl, 'yyyy-MM-dd HH:mm:ss') as tgl,
                                    sj.penerimaan_surat_jalan_kode as sj_kode,
                                    sj.penerimaan_surat_jalan_no_sj as no_sj,
                                    sj.penerimaan_surat_jalan_status as status,
                                    ISNULL(sj.penerimaan_surat_jalan_keterangan,'') as keterangan,
                                    cw.client_wms_nama as pt,
                                    p.principle_kode as p_kode,
                                    p.principle_nama as p_nama,
                                    pt.penerimaan_tipe_nama as tipe,
                                    pt.penerimaan_tipe_id as tipe_id");
		$this->db->from("penerimaan_surat_jalan sj");
		$this->db->join("principle p", "sj.principle_id = p.principle_id", "left");
		$this->db->join("client_wms cw", "sj.client_wms_id = cw.client_wms_id", "left");
		$this->db->join("penerimaan_tipe pt", "sj.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left");
		$this->db->where("sj.depo_id", $this->session->userdata('depo_id'));
		if ($tahun != "") {
			$this->db->where("YEAR(sj.penerimaan_surat_jalan_tgl)", $tahun);
		}
		if ($bulan != "") {
			$this->db->where("MONTH(sj.penerimaan_surat_jalan_tgl)", $bulan);
		}
		if ($perusahaan != "") {
			$this->db->where("sj.client_wms_id", $perusahaan);
		}
		if ($principle != "") {
			$this->db->where("sj.principle_id", $principle);
		}
		if ($tipe_penerimaan != "") {
			$this->db->where("sj.penerimaan_tipe_id", $tipe_penerimaan);
		}
		if ($status != "") {
			$this->db->where("sj.penerimaan_surat_jalan_status", $status);
		}
		// $this->db->order_by("sj.penerimaan_surat_jalan_kode", "ASC");
		$this->db->order_by("sj.penerimaan_surat_jalan_tgl", "DESC");
		$data = $this->db->get()->result();
		return $data;
	}

	public function get_data_detail_surat_jalan($id)
	{
		$data = $this->db->select("sjd.penerimaan_surat_jalan_detail_id as sjd_id,
                                    sjd.sku_jumlah_barang as sjd_jumlah_barang,
                                    sjd.sku_harga as sjd_harga,
                                    sjd.sku_diskon_percent as sjd_diskon,
                                    sjd.sku_diskon_rp as sjd_value_diskon,
                                    sjd.sku_harga_total as sjd_harga_total,
                                    sku.sku_kode as sku_kode,
                                    sku.sku_nama_produk as sku_nama_produk,
                                    sku.sku_kemasan as sku_kemasan,
                                    sku.sku_satuan as sku_satuan,
                                    pt.penerimaan_tipe_nama as tipe")
			->from("penerimaan_surat_jalan_detail sjd")
			->join("sku", "sjd.sku_id = sku.sku_id", "left")
			->join("penerimaan_tipe pt", "penerimaan_tipe pt on sjd.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->where("sjd.penerimaan_surat_jalan_id", $id)
			->get()->result_array();
		return $data;
	}

	public function get_data_edit_surat_jalan_header($id)
	{
		return  $this->db->select("*")->from("penerimaan_surat_jalan")->where('penerimaan_surat_jalan_id', $id)->get()->row();
	}

	public function get_data_edit_surat_jalan_detail($id)
	{
		$data = $this->db->select("sjd.penerimaan_surat_jalan_detail_ori_id as sjd_id,
                                    sjd.sku_jumlah_barang as sjd_jumlah_barang,
                                    sjd.sku_exp_date as ed,
                                    sjd.reason_surat_jalan_detail_sku,
                                    sjd.batch_no,
                                    sku.sku_id as sku_id,
                                    sku.sku_kode as sku_kode,
                                    sku.sku_nama_produk as sku_nama_produk,
                                    sku.sku_kemasan as sku_kemasan,
                                    sku.sku_satuan as sku_satuan,
                                    sku.sku_harga_jual as sku_harga_jual,
                                    pt.penerimaan_tipe_id as tipe_id,
                                    pt.penerimaan_tipe_nama as tipe")
			->from("penerimaan_surat_jalan_detail_ori sjd")
			->join("sku", "sjd.sku_id = sku.sku_id", "left")
			->join("penerimaan_tipe pt", "penerimaan_tipe pt on sjd.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->where("sjd.penerimaan_surat_jalan_id", $id)
			->order_by("sku.sku_kode", "ASC")
			->get()->result_array();
		return $data;
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

	public function getSecurityLogbook()
	{
		$query = $this->db->query("SELECT 
										sc.security_logbook_id, 
										sc.security_logbook_kode, 
										sc.security_logbook_nama_driver, 
										sc.security_logbook_nopol, 
										sc.security_logbook_tgl_masuk, 
										sc.security_logbook_tgl_keluar,
										p.principle_nama,
										COUNT(scd.security_logbook_detail_3_id) AS total_sj
									FROM 
										security_logbook AS sc
									LEFT JOIN principle AS p ON sc.principle_id = p.principle_id
									LEFT JOIN 
										security_logbook_detail_3 AS scd ON sc.security_logbook_id = scd.security_logbook_id
									WHERE sc.flag = 'Inbound Supplier'
									AND scd.no_surat_jalan_eksternal NOT IN (SELECT penerimaan_surat_jalan_no_sj FROM penerimaan_surat_jalan)
									GROUP BY 
										sc.security_logbook_id, 
										sc.security_logbook_kode,
										sc.security_logbook_nama_driver, 
										sc.security_logbook_nopol, 
										p.principle_nama,
										sc.security_logbook_tgl_masuk, 
										sc.security_logbook_tgl_keluar");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function generateSuratJalan($sl_id)
	{
		$query = $this->db->query("SELECT pt.client_wms_id,
											pt.client_wms_nama,
											p.principle_id,
											p.principle_nama,
											sc.security_logbook_nopol,
											scd.security_logbook_detail_attachment,
											scd3.no_surat_jalan_eksternal
									FROM security_logbook AS sc
									LEFT JOIN client_wms AS pt ON sc.client_wms_id = pt.client_wms_id
									LEFT JOIN principle AS p ON sc.principle_id = p.principle_id
									LEFT JOIN security_logbook_detail AS scd ON sc.security_logbook_id = scd.security_logbook_id
									LEFT JOIN security_logbook_detail_3 AS scd3 ON sc.security_logbook_id = scd3.security_logbook_id
									WHERE sc.security_logbook_id = '$sl_id'
									AND scd3.no_surat_jalan_eksternal NOT IN (SELECT penerimaan_surat_jalan_no_sj FROM penerimaan_surat_jalan)");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function get_data_principle_by_client_wms_id($id)
	{
		$query = $this->db->select("b.principle_id as id, b.principle_kode as kode, b.principle_nama as nama")
			->from("client_wms_principle a")
			->join("principle b", "a.principle_id = b.principle_id")
			->where("a.client_wms_id", $id)->get()->result();
		return $query;
	}

	public function get_data_principle_by_principle_id($id)
	{
		$query = $this->db->select("p.principle_id as id, p.principle_kode as kode, p.principle_nama as nama, cwp.client_wms_principle_top as tempo")
			->from("principle p")
			->join("client_wms_principle cwp", "p.principle_id = cwp.principle_id", "left")
			->where("p.principle_id", $id)->get()->row();
		return $query;
	}

	public function get_data_sku_by_principle_id($client_wms_id, $id)
	{
		$query = $this->db->select("s.sku_id as sku_id,
                                    s.sku_kode as sku_kode,
                                    s.sku_nama_produk as sku,
                                    s.sku_satuan as sku_satuan,
                                    s.sku_kemasan as sku_kemasan,
                                    si.sku_induk_nama as sku_induk,
                                    p.principle_kode as principle,
                                    pb.principle_brand_nama as brand")
			->from("sku s")
			->join("sku_induk si", "s.sku_induk_id = si.sku_induk_id", "left")
			->join("principle p", "s.principle_id = p.principle_id", "left")
			->join("principle_brand pb", "s.principle_brand_id = pb.principle_brand_id", "left")
			->where("s.client_wms_id", $client_wms_id)
			->where("s.principle_id", $id)
			->where("s.sku_is_aktif", 1)
			->order_by("s.sku_kode, s.sku_satuan", "ASC")
			->get()->result_array();
		return $query;
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

	public function getTipePenerimaan2()
	{
		$dataTipePenerimaan = $this->db->select("penerimaan_tipe_id as id, penerimaan_tipe_nama as nama")
			->from("penerimaan_tipe")
			->where("penerimaan_tipe_is_aktif", 1)
			->where("penerimaan_tipe_jenis", "Pembelian")
			->where("penerimaan_tipe_id <>", "7E683187-445E-4044-87BF-58804C9E09DA")
			->get()->result();
		return $dataTipePenerimaan;
	}

	public function get_sku_by_id($id)
	{
		if (isset($id)) {
			$query = $this->db->select("sku_id,
                                        sku_kode,
                                        sku_nama_produk,
                                        sku_kemasan,
                                        sku_satuan,
                                        sku_harga_jual")
				->from("sku")
				->where_in("sku_id", $id)
				->get()->result_array();
			return $query;
		}
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function check_duplicate_no_sj($perusahaan, $principle, $no_sj)
	{
		$this->db->select("penerimaan_surat_jalan_no_sj")
			->from("penerimaan_surat_jalan")
			->where("client_wms_id", $perusahaan)
			->where("principle_id", $principle)
			->where("penerimaan_surat_jalan_no_sj", $no_sj);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = 1;
		}

		return $query;
	}

	public function generate($sj_id, $principle, $perusahaan, $tipe_penerimaan, $generate_kode, $no_surat_jalan, $tgl, $status, $name_file, $no_kendaraan)
	{
		$this->db->set("penerimaan_surat_jalan_id", $sj_id);
		$this->db->set("depo_id", $this->session->userdata("depo_id"));
		$this->db->set("principle_id", $principle);
		$this->db->set("client_wms_id", $perusahaan);
		$this->db->set("penerimaan_tipe_id", $tipe_penerimaan);
		$this->db->set("penerimaan_surat_jalan_kode", $generate_kode);
		$this->db->set("penerimaan_surat_jalan_no_sj", $no_surat_jalan);
		$this->db->set("penerimaan_surat_jalan_attachment", $name_file);
		$this->db->set("penerimaan_surat_jalan_tgl", "GETDATE()", false);
		$this->db->set("penerimaan_surat_jalan_status", $status);
		$this->db->set("penerimaan_surat_jalan_nopol", $no_kendaraan);
		$this->db->set("penerimaan_surat_jalan_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("penerimaan_surat_jalan_tgl_create", "GETDATE()", false);

		$this->db->set("penerimaan_surat_jalan_tgl_update", "GETDATE()", false);
		$this->db->set("penerimaan_surat_jalan_who_update", $this->session->userdata('pengguna_username'));

		$queryinsert = $this->db->insert("penerimaan_surat_jalan");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function save($sj_id, $principle, $perusahaan, $tipe_penerimaan, $generate_kode, $no_surat_jalan, $tgl, $status, $keterangan, $nama_file, $no_surat_jalan_counter, $no_kendaraan)
	{
		$this->db->set("penerimaan_surat_jalan_id", $sj_id);
		$this->db->set("depo_id", $this->session->userdata("depo_id"));
		$this->db->set("principle_id", $principle);
		$this->db->set("client_wms_id", $perusahaan);
		$this->db->set("penerimaan_tipe_id", $tipe_penerimaan);
		$this->db->set("penerimaan_surat_jalan_kode", $generate_kode);
		if ($no_surat_jalan_counter == "") {
			$this->db->set("penerimaan_surat_jalan_no_sj", $no_surat_jalan);
		} else {
			$this->db->set("penerimaan_surat_jalan_no_sj", $no_surat_jalan . "-" . $no_surat_jalan_counter);
			$this->db->set("penerimaan_surat_jalan_group_split", $no_surat_jalan);
		}
		$this->db->set("penerimaan_surat_jalan_attachment", $nama_file);
		$this->db->set("penerimaan_surat_jalan_tgl", "GETDATE()", false);
		$this->db->set("penerimaan_surat_jalan_status", $status);
		$this->db->set("penerimaan_surat_jalan_keterangan", $keterangan);
		$this->db->set("penerimaan_surat_jalan_nopol", $no_kendaraan);
		$this->db->set("penerimaan_surat_jalan_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("penerimaan_surat_jalan_tgl_create", "GETDATE()", false);

		$this->db->set("penerimaan_surat_jalan_tgl_update", "GETDATE()", false);
		$this->db->set("penerimaan_surat_jalan_who_update", $this->session->userdata('pengguna_username'));

		$queryinsert = $this->db->insert("penerimaan_surat_jalan");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function save_detail($sj_id, $value)
	{
		$this->db->set("penerimaan_surat_jalan_detail_ori_id", "NewID()", FALSE);
		$this->db->set("penerimaan_surat_jalan_id", $sj_id);
		$this->db->set("sku_id", $value->sku_id);
		$this->db->set("penerimaan_tipe_id", $value->sku_tipe);
		$this->db->set("sku_jumlah_barang", $value->jml_brng);
		$this->db->set("sku_exp_date", $value->ed);
		$this->db->set("batch_no", $value->batch_no);

		$this->db->insert("penerimaan_surat_jalan_detail_ori");

		$getKonversiKomposite = $this->db->select("sku_konversi_faktor")->from('sku')->where('sku_id', $value->sku_id)->get()->row();

		//insert ke table konversi_temp
		$this->db->set("sku_konversi_temp_id", $sj_id);
		$this->db->set("sku_id", $value->sku_id);
		$this->db->set("sku_expired_date", $value->ed);
		$this->db->set("sku_qty", $value->jml_brng);
		$this->db->set("sku_qty_composite", $value->jml_brng * $getKonversiKomposite->sku_konversi_faktor);
		$this->db->set("batch_no", $value->batch_no);

		$this->db->insert("sku_konversi_temp");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function save_detail_sj($sj_id, $item)
	{

		$this->db->set("penerimaan_surat_jalan_detail_id", "NewID()", FALSE);
		$this->db->set("penerimaan_surat_jalan_id", $sj_id);
		$this->db->set("sku_id", $item['sku_id']);
		// $this->db->set("penerimaan_tipe_id", $item->sku_tipe);
		$this->db->set("sku_jumlah_barang", $item['hasil']);
		$this->db->set("sku_exp_date", $item['sku_expired_date']);
		$this->db->set("batch_no", $item['batch_no']);
		// $this->db->set("sku_harga", implode("", $bruto));
		// $this->db->set("sku_diskon_percent", implode("", $diskon));
		// $this->db->set("sku_diskon_rp", implode("", $value_diskon));
		// $this->db->set("sku_harga_total", implode("", $netto));

		$this->db->insert("penerimaan_surat_jalan_detail");


		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function delete_file_db($id)
	{
		$getfile = $this->db->select("penerimaan_surat_jalan_attachment")->from("penerimaan_surat_jalan")->where("penerimaan_surat_jalan_id", $id)->get()->row();

		$temp_file = '';

		if ($getfile->penerimaan_surat_jalan_attachment != NULL) {
			// $temp_file = Get_Assets_Url() . 'images/Surat-Jalan/' . $getfile->penerimaan_surat_jalan_attachment;
			$path_proyek_target = '../padmakriya_assets/images/Surat-Jalan/';
			$fileExists = file_exists($path_proyek_target . $getfile->penerimaan_surat_jalan_attachment);

			if ($fileExists) {
				$temp_file = $path_proyek_target . $getfile->penerimaan_surat_jalan_attachment;
			} else {
				$temp_file = '';
			}
		}

		$this->db->set("penerimaan_surat_jalan_attachment", NULL);
		$this->db->where("penerimaan_surat_jalan_id", $id);
		$query = $this->db->update("penerimaan_surat_jalan");

		if ($query) {
			$result = 1;

			if ($temp_file != '') {
				unlink($temp_file);
			}
		} else {
			$result = 0;
		}

		return $result;
	}

	public function check_duplicate_no_sj_by_id($id, $perusahaan, $principle, $no_sj)
	{
		$this->db->select("penerimaan_surat_jalan_no_sj")
			->from("penerimaan_surat_jalan")
			->where("client_wms_id", $perusahaan)
			->where("principle_id", $principle)
			->where("penerimaan_surat_jalan_no_sj", $no_sj)
			->where("penerimaan_surat_jalan_id <>", $id);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = 1;
		}

		return $query;
	}

	public function get_file_db_by_id($id)
	{
		$getfile = $this->db->select("penerimaan_surat_jalan_attachment")->from("penerimaan_surat_jalan")->where("penerimaan_surat_jalan_id", $id)->get()->row();

		if ($getfile->penerimaan_surat_jalan_attachment != NULL) {
			$result = 1;
		} else {
			$result = 0;
		}

		return $result;
	}

	public function update($id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, $nama_file, $no_surat_jalan_counter, $no_kendaraan)
	{
		$tgl_ = $tgl . " " . date('H:i:s');

		$this->db->set("principle_id", $principle);
		$this->db->set("client_wms_id", $perusahaan);
		$this->db->set("penerimaan_tipe_id", $tipe_penerimaan);
		if ($no_surat_jalan_counter == "") {
			$this->db->set("penerimaan_surat_jalan_no_sj", $no_surat_jalan);
		} else {
			$this->db->set("penerimaan_surat_jalan_no_sj", $no_surat_jalan . "-" . $no_surat_jalan_counter);
			$this->db->set("penerimaan_surat_jalan_group_split", $no_surat_jalan);
		}
		$this->db->set("penerimaan_surat_jalan_attachment", $nama_file);
		$this->db->set("penerimaan_surat_jalan_tgl", $tgl_);
		$this->db->set("penerimaan_surat_jalan_status", $status);
		$this->db->set("penerimaan_surat_jalan_keterangan", $keterangan);
		$this->db->set("penerimaan_surat_jalan_nopol", $no_kendaraan);
		$this->db->set("penerimaan_surat_jalan_who_create", $this->session->userdata('pengguna_username'));

		$this->db->set("penerimaan_surat_jalan_tgl_update", "GETDATE()", false);
		$this->db->set("penerimaan_surat_jalan_who_update", $this->session->userdata('pengguna_username'));

		$this->db->where('penerimaan_surat_jalan_id', $id);

		$queryinsert = $this->db->update("penerimaan_surat_jalan");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function update2($id, $principle, $perusahaan, $tipe_penerimaan, $no_surat_jalan, $tgl, $status, $keterangan, $no_surat_jalan_counter, $no_kendaraan)
	{

		$tgl_ = $tgl . " " . date('H:i:s');

		$this->db->set("principle_id", $principle);
		$this->db->set("client_wms_id", $perusahaan);
		$this->db->set("penerimaan_tipe_id", $tipe_penerimaan);
		if ($no_surat_jalan_counter == "") {
			$this->db->set("penerimaan_surat_jalan_no_sj", $no_surat_jalan);
		} else {
			$this->db->set("penerimaan_surat_jalan_no_sj", $no_surat_jalan . "-" . $no_surat_jalan_counter);
			$this->db->set("penerimaan_surat_jalan_group_split", $no_surat_jalan);
		}
		$this->db->set("penerimaan_surat_jalan_tgl", $tgl_);
		$this->db->set("penerimaan_surat_jalan_status", $status);
		$this->db->set("penerimaan_surat_jalan_keterangan", $keterangan);
		$this->db->set("penerimaan_surat_jalan_nopol", $no_kendaraan);
		$this->db->set("penerimaan_surat_jalan_who_create", $this->session->userdata('pengguna_username'));

		$this->db->set("penerimaan_surat_jalan_tgl_update", "GETDATE()", false);
		$this->db->set("penerimaan_surat_jalan_who_update", $this->session->userdata('pengguna_username'));

		$this->db->where('penerimaan_surat_jalan_id', $id);

		$queryinsert = $this->db->update("penerimaan_surat_jalan");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function update_detail($id, $value)
	{

		$this->db->set("penerimaan_surat_jalan_detail_ori_id", "NewID()", FALSE);
		$this->db->set("penerimaan_surat_jalan_id", $id);
		$this->db->set("sku_id", $value->sku_id);
		$this->db->set("penerimaan_tipe_id", $value->sku_tipe);
		$this->db->set("sku_jumlah_barang", $value->jml_brng);
		$this->db->set("sku_exp_date", $value->ed);
		$this->db->set("batch_no", $value->batch_no);
		// $this->db->set("sku_harga", $value->bruto);
		// $this->db->set("sku_diskon_percent", $value->diskon);
		// $this->db->set("sku_diskon_rp", $value->value_diskon);
		// $this->db->set("sku_harga_total", $value->netto);

		$this->db->insert("penerimaan_surat_jalan_detail_ori");

		$getKonversiKomposite = $this->db->select("sku_konversi_faktor")->from('sku')->where('sku_id', $value->sku_id)->get()->row();

		//insert ke table konversi_temp
		$this->db->set("sku_konversi_temp_id", $id);
		$this->db->set("sku_id", $value->sku_id);
		$this->db->set("sku_expired_date", $value->ed);
		$this->db->set("sku_qty", $value->jml_brng);
		$this->db->set("sku_qty_composite", $value->jml_brng * $getKonversiKomposite->sku_konversi_faktor);
		$this->db->set("sku_qty_composite", $value->jml_brng * $getKonversiKomposite->sku_konversi_faktor);
		$this->db->set("batch_no", $value->batch_no);

		$this->db->insert("sku_konversi_temp");


		// $this->db->set("penerimaan_surat_jalan_detail_id", "NewID()", FALSE);
		// $this->db->set("penerimaan_surat_jalan_id", $id);
		// $this->db->set("sku_id", $value->sku_id);
		// $this->db->set("penerimaan_tipe_id", $value->sku_tipe);
		// $this->db->set("sku_jumlah_barang", $value->jml_brng);
		// $this->db->set("sku_exp_date", $value->ed);
		// // $this->db->set("sku_harga", $value->bruto);
		// // $this->db->set("sku_diskon_percent", $value->diskon);
		// // $this->db->set("sku_diskon_rp", $value->value_diskon);
		// // $this->db->set("sku_harga_total", $value->netto);

		// $this->db->insert("penerimaan_surat_jalan_detail");



		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function cancel_surat_jalan($id, $reason)
	{
		$this->db->set("penerimaan_surat_jalan_status", "Force Close");
		$this->db->set("penerimaan_surat_jalan_reason", $reason);
		$this->db->set("penerimaan_surat_jalan_who_reason", $this->session->userdata('pengguna_username'));
		$this->db->set("penerimaan_surat_jalan_tgl_reason", "GETDATE()", false);
		$this->db->where('penerimaan_surat_jalan_id', $id);

		return $this->db->update("penerimaan_surat_jalan");
	}

	public function getDataDetailSuratjalanKonversi($id)
	{
		$data = $this->db->select("sjd.penerimaan_surat_jalan_detail_ori_id as sjd_id,
                                    sjd.sku_jumlah_barang as sjd_jumlah_barang,
                                    ISNULL(sjd.sku_jumlah_barang_terima, 0) as sku_jumlah_terima,
                                    ISNULL(sjd.sku_jumlah_barang, 0) - ISNULL(sjd.sku_jumlah_barang_terima, 0) as sisa,
                                    sjd.sku_exp_date as ed,
                                    isnull(sjd.reason_surat_jalan_detail_sku, '') as reason_surat_jalan_detail_sku, 
                                    sku.sku_id as sku_id,
                                    sku.sku_kode as sku_kode,
                                    sku.sku_nama_produk as sku_nama_produk,
                                    sku.sku_kemasan as sku_kemasan,
                                    sku.sku_satuan as sku_satuan,
                                    sku.sku_harga_jual as sku_harga_jual,
                                    pt.penerimaan_tipe_id as tipe_id,
                                    pt.penerimaan_tipe_nama as tipe")
			->from("penerimaan_surat_jalan_detail_ori sjd")
			->join("sku", "sjd.sku_id = sku.sku_id", "left")
			->join("penerimaan_tipe pt", "penerimaan_tipe pt on sjd.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->where("sjd.penerimaan_surat_jalan_id", $id)
			->order_by("sku.sku_kode", "ASC")
			->get()->result_array();
		return $data;
	}

	public function getDatareason()
	{
		return $this->db->get_where('reason_surat_jalan')->result();
	}

	public function getDataReasonDetail($id)
	{
		return $this->db->get_where('reason_surat_jalan_detail', ['reason_surat_jalan_id' => $id])->result();
	}

	public function updateReason($global_id, $reason, $reasonDetail, $arrDetailSjd)
	{
		//update data header
		$dataHeader = [
			'reason_surat_jalan_id' => $reason,
			'reason_surat_jalan_detail_id' => $reasonDetail,
			'penerimaan_surat_jalan_status' => 'Close',
		];
		$this->db->where('penerimaan_surat_jalan_id', $global_id);
		$this->db->update('penerimaan_surat_jalan', $dataHeader);


		//update data detail
		foreach ($arrDetailSjd as  $value) {
			if ($value['reasonDetail'] != "") {
				$this->db->set('reason_surat_jalan_detail_sku', $value['reasonDetail']);
				$this->db->where('penerimaan_surat_jalan_detail_ori_id', $value['sjd_id']);
				$this->db->update('penerimaan_surat_jalan_detail_ori');
			}
			$dr = true;
		}

		return $dr;
	}

	public function getNoSuratJalanEksternal($input)
	{
		return $this->db->select("penerimaan_surat_jalan_no_sj as kode")->from("penerimaan_surat_jalan")->like("penerimaan_surat_jalan_no_sj", $input)->get()->result();
	}

	public function getLastNoCounterSuratJalan($input)
	{
		$this->db->select('penerimaan_surat_jalan_no_sj as kode, principle_id, client_wms_id');
		$this->db->limit(1);
		$this->db->like('penerimaan_surat_jalan_no_sj', $input);
		return $this->db->get('penerimaan_surat_jalan')->row();
	}
}
