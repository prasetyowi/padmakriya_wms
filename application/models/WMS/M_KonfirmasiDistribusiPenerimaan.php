<?php

date_default_timezone_set('Asia/Jakarta');

class M_KonfirmasiDistribusiPenerimaan extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function get_gudangs()
	{
		return $this->db->select("depo_detail_id as id, depo_detail_nama as nama")
			->from("depo_detail")
			->where("depo_id", $this->session->userdata('depo_id'))
			->order_by("depo_detail_nama", "ASC")->get()->result();
	}

	public function get_no_draft_mutasi()
	{
		return $this->db->select("tr_mutasi_pallet_draft_id as id, tr_mutasi_pallet_draft_kode as kode")
			->from("tr_mutasi_pallet_draft")
			// ->join("distribusi_penerimaan dp", "pp.penerimaan_pembelian_id =  dp.penerimaan_pembelian_id", "left")
			->where("distribusi_penerimaan_id !=", null)
			->order_by("tr_mutasi_pallet_draft_tanggal", "ASC")->get()->result();
	}

	public function get_data_mutasi_pallet_draft_by_kode($no_mutasi, $gudang_tujuan, $status)
	{
		$this->db->select("tmpd.tr_mutasi_pallet_draft_id as id,
                            tmpd.tr_mutasi_pallet_draft_kode as kode,
                            pb.penerimaan_pembelian_tgl,
                            FORMAT(tmpd.tr_mutasi_pallet_draft_tanggal, 'yyyy-MM-dd HH:mm:ss') as tgl,
                            tmpd.tr_mutasi_pallet_draft_status as status,
                            dd.depo_detail_nama as depo_asal,
                            dd2.depo_detail_nama as depo_tujuan,
                            pb.penerimaan_pembelian_kode as pb_kode,
														tmpd.tr_mutasi_pallet_draft_tgl_update");
		$this->db->from("tr_mutasi_pallet_draft tmpd");
		$this->db->join("distribusi_penerimaan dp", "tmpd.distribusi_penerimaan_id = dp.distribusi_penerimaan_id", "left");
		$this->db->join("penerimaan_pembelian pb", "dp.penerimaan_pembelian_id = pb.penerimaan_pembelian_id", "left");
		$this->db->join("depo_detail dd", "tmpd.depo_detail_id_asal = dd.depo_detail_id", "left");
		$this->db->join("depo_detail dd2", "tmpd.depo_detail_id_tujuan = dd2.depo_detail_id", "left");
		$this->db->where("tmpd.depo_id_asal", $this->session->userdata('depo_id'));
		if ($no_mutasi != "") {
			$this->db->where("tmpd.tr_mutasi_pallet_draft_id", $no_mutasi);
		}
		if ($gudang_tujuan != "") {
			$this->db->where("tmpd.depo_detail_id_tujuan", $gudang_tujuan);
		}
		if ($status != "") {
			$this->db->where("tmpd.tr_mutasi_pallet_draft_status", $status);
		}
		$this->db->where_in('tmpd.tr_mutasi_pallet_draft_status', array('In Progress', 'Completed'));
		$this->db->order_by("pb.penerimaan_pembelian_tgl", "DESC");
		return $this->db->get()->result();
	}

	public function get_data_header_konfirmasi($id)
	{
		$this->db->select("tmpd.tr_mutasi_pallet_draft_id as id,
                            tmpd.tr_mutasi_pallet_draft_kode as kode,
                            FORMAT(tmpd.tr_mutasi_pallet_draft_tanggal, 'dd-MM-yyyy') as tgl,
                            tmpd.tr_mutasi_pallet_draft_status as status,
                            k.karyawan_nama as checker,
                            tm.tipe_mutasi_id as tipe_id,
                            tm.tipe_mutasi_nama as tipe,
                            tmpd.principle_id as principle,
                            tmpd.client_wms_id as client_wms,
                            tmpd.distribusi_penerimaan_id as distribusi_id,
														tmpd.tr_mutasi_pallet_draft_tgl_update,
                            dd.depo_detail_id as depo_asal_id,
                            dd.depo_detail_nama as depo_asal,
                            dd2.depo_detail_id as depo_tujuan_id,
                            dd2.depo_detail_nama as depo_tujuan,
                            pb.penerimaan_pembelian_kode as pb_kode,
                            psj.penerimaan_surat_jalan_kode as no_sj");
		$this->db->from("tr_mutasi_pallet_draft tmpd");
		$this->db->join("distribusi_penerimaan dp", "tmpd.distribusi_penerimaan_id = dp.distribusi_penerimaan_id", "left");
		$this->db->join("penerimaan_pembelian pb", "dp.penerimaan_pembelian_id = pb.penerimaan_pembelian_id", "left");
		$this->db->join("penerimaan_surat_jalan psj", "pb.penerimaan_surat_jalan_id = psj.penerimaan_surat_jalan_id", "left");
		$this->db->join("depo_detail dd", "tmpd.depo_detail_id_asal = dd.depo_detail_id", "left");
		$this->db->join("depo_detail dd2", "tmpd.depo_detail_id_tujuan = dd2.depo_detail_id", "left");
		$this->db->join("tipe_mutasi tm", "tmpd.tipe_mutasi_id = tm.tipe_mutasi_id", "left");
		$this->db->join("karyawan k", "tmpd.tr_mutasi_pallet_draft_nama_checker = k.karyawan_nama", "left");
		$this->db->where("tmpd.tr_mutasi_pallet_draft_id", $id);
		return $this->db->get()->row();
	}

	public function get_data_detail_konfirmasi($id)
	{

		$this->db->select("tmpdd.tr_mutasi_pallet_detail_draft_id as id,
                            p.pallet_id as pallet_id,
                            p.pallet_kode as kode,
                            tmpdd.rak_lajur_detail_id_tujuan as rak_jalur,
                            rld.rak_lajur_detail_nama as rak_nama,
                            pj.pallet_jenis_nama as jenis,
                            tmpdd.is_valid as status,
                            tmpdd.attachement as file,
							tmpdd.pallet_id_tujuan,
							p2.pallet_kode as pallet_kode_tujuan");
		$this->db->from("tr_mutasi_pallet_detail_draft tmpdd");
		$this->db->join("tr_mutasi_pallet_draft tmpd", "tmpdd.tr_mutasi_pallet_draft_id = tmpd.tr_mutasi_pallet_draft_id", "left");
		$this->db->join("pallet p", "tmpdd.pallet_id = p.pallet_id", "left");
		$this->db->join("pallet p2", "tmpdd.pallet_id_tujuan = p2.pallet_id", "left");
		$this->db->join("rak_lajur_detail rld", "tmpdd.rak_lajur_detail_id_tujuan = rld.rak_lajur_detail_id", "left");
		$this->db->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left");
		$this->db->where("tmpdd.tr_mutasi_pallet_draft_id", $id);
		return $this->db->get()->result();
	}

	public function get_data_detail_pallet($id)
	{
		return $this->db->select("principle.principle_kode as principle,
                              sku.sku_kode as sku_kode,
                              sku.sku_nama_produk as sku_nama,
                              sku.sku_kemasan as sku_kemasan,
                              sku.sku_satuan as sku_satuan,
                              pd.sku_stock_expired_date as ed,
                              pt.penerimaan_tipe_nama as tipe,
                              pd.sku_stock_qty as qty")
			->from("tr_mutasi_pallet_detail_draft mpdd")
			->join("pallet p", "mpdd.pallet_id = p.pallet_id", "left")
			->join("pallet_detail pd", "p.pallet_id = pd.pallet_id", "left")
			->join("sku", "pd.sku_id = sku.sku_id", "left")
			->join("penerimaan_tipe pt", "pd.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->join("principle", "sku.principle_id = principle.principle_id", "left")
			->where("mpdd.tr_mutasi_pallet_detail_draft_id", $id)
			->order_by("sku.sku_kode", "ASC")->get()->result();
	}

	public function check_kode_pallet_by_no_mutasi($id, $kode_pallet)
	{
		return $this->db->select("tmpdd.tr_mutasi_pallet_detail_draft_id as id, tmpdd.is_valid as status, tmpdd.attachement as file")
			->from("tr_mutasi_pallet_draft tmpd")
			->join("tr_mutasi_pallet_detail_draft tmpdd", "tmpd.tr_mutasi_pallet_draft_id = tmpdd.tr_mutasi_pallet_draft_id", "left")
			->join("pallet p", "tmpdd.pallet_id = p.pallet_id", "left")
			->where("tmpd.tr_mutasi_pallet_draft_id", $id)
			->where("p.pallet_kode", $kode_pallet)->get()->row();
	}
	public function check_kode_pallet_tujuan_by_no_mutasi($id, $kode_pallet, $rak_lajur)
	{
		// return $this->db->select("*")
		// 	->from("pallet p")
		// 	->where("p.pallet_kode", $kode_pallet)->get()->row();

		return $this->db->select('*')
			->from('pallet')
			->join('rak_lajur_detail rld', 'rld.rak_lajur_detail_id = pallet.rak_lajur_detail_id')
			->join('rak', 'rak.rak_id = rld.rak_id')
			->where('rak.depo_detail_id', $rak_lajur)
			->where("pallet.pallet_kode", $kode_pallet)->get()->row();


		// return $this->db->get()->result();
	}

	public function get_data_tr_mutasi_pallet_draft_id($id, $pallet_id)
	{
		return $this->db->select("tmpdd.tr_mutasi_pallet_detail_draft_id as id")
			->from("tr_mutasi_pallet_detail_draft tmpdd")
			->where("tmpdd.tr_mutasi_pallet_draft_id", $id)
			->where("tmpdd.pallet_id", $pallet_id)->get()->row();
	}

	public function update_status_tmpdd($data, $file)
	{
		$file_ = $file == null ? null : $file;

		$this->db->set("is_valid", 1);
		$this->db->set("attachement", $file_);
		$this->db->where("tr_mutasi_pallet_detail_draft_id", $data->id);
		$this->db->update("tr_mutasi_pallet_detail_draft");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function getPrefixPallet($id)
	{
		return $this->db->select("pj.pallet_jenis_kode")
			->from("tr_mutasi_pallet_draft tmpd")
			->join("tr_mutasi_pallet_detail_draft tmpdd", "tmpd.tr_mutasi_pallet_draft_id = tmpdd.tr_mutasi_pallet_draft_id", "left")
			->join("pallet p", "tmpdd.pallet_id = p.pallet_id", "left")
			->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left")
			->where("tmpd.tr_mutasi_pallet_draft_id", $id)->get()->row();
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function check_rak_lajur_detail($gudang_tujuan, $kode)
	{
		return $this->db->query("SELECT
                                DISTINCT
                                    rld.rak_lajur_detail_id AS rak_detail_id,
                                    rld.rak_lajur_detail_nama AS rak_detail_nama
                                FROM rak_lajur_detail rld
                                LEFT JOIN rak r
                                    ON rld.rak_id = r.rak_id
                                WHERE r.depo_id = '" . $this->session->userdata('depo_id') . "' AND r.depo_detail_id = '$gudang_tujuan'
                                    AND rld.rak_lajur_detail_nama = '$kode'")->row();
	}

	public function check_and_get_data_rak_lajur_detail_pallet($pallet_id)
	{
		return $this->db->select("rak_lajur_detail.rak_lajur_detail_nama, rak_lajur_detail_pallet.pallet_id, rak_lajur_detail_pallet.rak_lajur_detail_id as rak_detail_id")->from("rak_lajur_detail_pallet")
			->join("rak_lajur_detail", "rak_lajur_detail_pallet.rak_lajur_detail_id = rak_lajur_detail.rak_lajur_detail_id")
			->where("rak_lajur_detail_pallet.pallet_id", $pallet_id)->get()->row();
	}

	public function insert_data_to_rak_lajur_detail_pallet($data)
	{

		$this->db->set("rak_lajur_detail_pallet_id", "NewID()", FALSE);
		$this->db->set("rak_lajur_detail_id", $data->rak_lajur_detail_id_tujuan);
		$this->db->set("pallet_id", $data->pallet_id);

		$queryinsert = $this->db->insert("rak_lajur_detail_pallet");

		return $queryinsert;
	}

	public function insert_data_to_rak_lajur_detail_pallet_his($tipe_transaksi, $gudang_detail_asal, $gudang_detail_tujuan, $data)
	{

		$this->db->set("rak_lajur_detail_pallet_id", "NewID()", FALSE);
		// $this->db->set("depo_id", $gudang_detail_asal);
		$this->db->set("depo_id", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id", $gudang_detail_asal);
		$this->db->set("rak_lajur_detail_id", $data->rak_lajur_detail_id_tujuan);
		$this->db->set("pallet_id", $data->pallet_id);
		$this->db->set("tipe_mutasi_id", $tipe_transaksi);
		$this->db->set("tanggal_create", "GETDATE()", FALSE);
		$this->db->set("who_create", $this->session->userdata('pengguna_username'));

		$queryinsert = $this->db->insert("rak_lajur_detail_pallet_his");

		return $queryinsert;
	}

	public function update_data_pallet_by_params($data, $tr_id)
	{
		// //update rak_detail_id di tabel pallet by pallet_id
		// $this->db->set("rak_lajur_detail_id", $data->rak_detail_id);
		// $this->db->where("pallet_id", $pallet_id);
		// $this->db->update("pallet_temp");

		$this->db->set("rak_lajur_detail_id_tujuan", $data->rak_detail_id);
		$this->db->set("pallet_id_tujuan", null);

		$this->db->where("tr_mutasi_pallet_detail_draft_id", $tr_id->id);
		$this->db->update("tr_mutasi_pallet_detail_draft");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}
	public function update_data_pallet_tujuan_by_params($pallet, $id, $tr_mutasi_pallet_detail_draft_id)
	{
		// var_dump($pallet);
		$this->db->set("pallet_id_tujuan", $pallet->pallet_id);
		$this->db->set("rak_lajur_detail_id_tujuan", null);

		$this->db->where("tr_mutasi_pallet_detail_draft_id", $tr_mutasi_pallet_detail_draft_id);
		$this->db->update("tr_mutasi_pallet_detail_draft");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function update_data_pallet_by_params2($data)
	{
		//update rak_detail_id di tabel pallet by pallet_id
		$this->db->set("rak_lajur_detail_id", $data->rak_lajur_detail_id_tujuan);
		$this->db->where("pallet_id", $data->pallet_id);
		$this->db->update("pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function delete_data_to_rak_lajur_detail_pallet($id)
	{
		$this->db->where('pallet_id', $id);
		return $this->db->delete('rak_lajur_detail_pallet');
	}

	public function save_data_to_tr_mutasi_pallet($mp_id, $generate_kode, $tgl, $mutasi_draft_id, $principle, $client_wms, $tipe_transaksi, $status, $keterangan, $gudang_detail_asal, $gudang_detail_tujuan, $checker)
	{
		$tgl_ = $tgl . " " . date('H:i:s');
		$ket = $keterangan == "" ? null : $keterangan;
		$this->db->set("tr_mutasi_pallet_id", $mp_id);
		$this->db->set("principle_id", $principle);
		$this->db->set("tr_mutasi_pallet_kode", $generate_kode);
		$this->db->set("tr_mutasi_pallet_tanggal", $tgl_);
		$this->db->set("tipe_mutasi_id", $tipe_transaksi);
		$this->db->set("tr_mutasi_pallet_keterangan", $ket);
		$this->db->set("tr_mutasi_pallet_status", $status);
		$this->db->set("depo_id_asal", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id_asal", $gudang_detail_asal);
		$this->db->set("depo_id_tujuan", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id_tujuan", $gudang_detail_tujuan);
		$this->db->set("tr_mutasi_pallet_tgl_create", "GETDATE()", FALSE);
		$this->db->set("tr_mutasi_pallet_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("tr_mutasi_pallet_draft_id", $mutasi_draft_id);
		$this->db->set("tr_mutasi_pallet_nama_checker", $checker);
		$this->db->set("client_wms_id", $client_wms);

		$queryinsert = $this->db->insert("tr_mutasi_pallet");

		return $queryinsert;
	}

	public function get_data_tmpd_by_mutasi_draft_id($id)
	{
		return $this->db->select("tmpdd.pallet_id, tmpdd.is_valid, tmpdd.attachement, tmpdd.rak_lajur_detail_id_tujuan, tmpdd.pallet_id_tujuan")
			->from("tr_mutasi_pallet_detail_draft tmpdd")
			->where("tmpdd.tr_mutasi_pallet_draft_id", $id)
			->get()->result();
	}

	public function save_data_to_tr_mutasi_pallet_detail($mp_id, $data)
	{
		$this->db->set("tr_mutasi_pallet_detail_id", "NewID()", FALSE);
		$this->db->set("tr_mutasi_pallet_id", $mp_id);
		$this->db->set("pallet_id", $data->pallet_id);
		$this->db->set("is_valid", $data->is_valid);
		$this->db->set("attachement", $data->attachement);
		$this->db->set("rak_lajur_detail_id_tujuan", $data->rak_lajur_detail_id_tujuan);
		$this->db->set("pallet_id_tujuan", $data->pallet_id_tujuan);

		$queryinsert = $this->db->insert("tr_mutasi_pallet_detail");
		return $queryinsert;
	}

	public function check_exist_in_tr_mutasi_pallet($id)
	{
		return $this->db->select("tr_mutasi_pallet_draft_id")
			->from("tr_mutasi_pallet")
			->where("tr_mutasi_pallet_draft_id", $id)
			->get()->row();
	}

	public function get_data_tr_mutasi($id)
	{
		return $this->db->select("tr_mutasi_pallet_id")
			->from("tr_mutasi_pallet")
			->where("tr_mutasi_pallet_draft_id", $id)
			->get()->row();
	}

	public function delete_data_tr_mutasi_dan_detail($mutasi_draft_id, $mp_id)
	{
		$this->db->where('tr_mutasi_pallet_draft_id', $mutasi_draft_id);
		$del1 = $this->db->delete('tr_mutasi_pallet');
		if ($del1) {
			$this->db->where('tr_mutasi_pallet_id', $mp_id);
			return $this->db->delete('tr_mutasi_pallet_detail');
		}
	}

	public function update_data_tr_mutasi($id)
	{
		//update tr_mutasi_pallet
		$this->db->set("tr_mutasi_pallet_status", "Completed");
		$this->db->where("tr_mutasi_pallet_draft_id", $id);
		$respone = $this->db->update("tr_mutasi_pallet");
		if ($respone) {
			$this->db->set("tr_mutasi_pallet_draft_status", "Completed");
			$this->db->where("tr_mutasi_pallet_draft_id", $id);
			$this->db->update("tr_mutasi_pallet_draft");

			$result = true;
		} else {
			$result = false;
		}

		return $result;
	}

	public function get_status_tmpd($id)
	{
		return $this->db->select("tr_mutasi_pallet_draft_status as status")->from("tr_mutasi_pallet_draft")->where("distribusi_penerimaan_id", $id)->get()->result();
	}

	public function update_status_table_distribusi($id)
	{
		$this->db->set("distribusi_penerimaan_status", "Close");
		$this->db->where("distribusi_penerimaan_id", $id);
		return $this->db->update("distribusi_penerimaan");
	}

	public function get_data_pallet_by_id_mutasi($id)
	{
		return $this->db->select("p.pallet_id,
                                    p.pallet_jenis_id,
                                    tmpdd.tr_mutasi_pallet_draft_id,
                                    p.depo_id,
                                    p.rak_lajur_detail_id,
                                    p.pallet_kode,
                                    p.pallet_tanggal_create,
                                    p.pallet_who_create,
                                    p.pallet_is_aktif")
			->from("tr_mutasi_pallet_draft tmpd")
			->join("tr_mutasi_pallet_detail_draft tmpdd", "tmpd.tr_mutasi_pallet_draft_id = tmpdd.tr_mutasi_pallet_draft_id", "left")
			->join("pallet p", "tmpdd.pallet_id = p.pallet_id", "left")
			->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left")
			->where("tmpdd.tr_mutasi_pallet_draft_id", $id)->get()->result();
	}

	public function existing_data_in_pallet_temp($id)
	{
		return $this->db->select("*")->from("pallet_temp")->where("pallet_id", $id)->get()->row();
	}

	public function insert_to_pallet_temp($data)
	{
		$this->db->set("pallet_id", $data->pallet_id);
		$this->db->set("pallet_jenis_id", $data->pallet_jenis_id);
		$this->db->set("delivery_order_batch_id", $data->tr_mutasi_pallet_draft_id);
		$this->db->set("depo_id", $data->depo_id);
		$this->db->set("rak_lajur_detail_id", $data->rak_lajur_detail_id);
		$this->db->set("pallet_kode", $data->pallet_kode);
		$this->db->set("pallet_tanggal_create", $data->pallet_tanggal_create);
		$this->db->set("pallet_who_create", $data->pallet_who_create);
		$this->db->set("pallet_is_aktif", $data->pallet_is_aktif);

		$queryinsert = $this->db->insert("pallet_temp");
		return $queryinsert;
	}

	public function get_data_rak_lajur_detail($id)
	{
		return $this->db->select("*")->from("tr_mutasi_pallet_detail_draft")->where('tr_mutasi_pallet_draft_id', $id)->get()->result();
	}

	public function check_data_in_sku_stock($mutasi_draft_id)
	{
		return $this->db->query("SELECT
                                    tmpd.depo_id_asal,
                                    tmpd.depo_detail_id_asal,
                                    tmpd.depo_id_tujuan,
                                    tmpd.depo_detail_id_tujuan,
                                    pb.client_wms_id,
                                    pd.sku_id,
                                    FORMAT(pd.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
                                    sku.sku_induk_id,
                                    SUM(pd.sku_stock_qty) AS sku_stock_qty,
									tmpdd.pallet_id ,
									tmpdd.pallet_id_tujuan,
									pd.pallet_detail_id
                                FROM tr_mutasi_pallet_draft tmpd
                                LEFT JOIN tr_mutasi_pallet_detail_draft tmpdd
                                    ON tmpd.tr_mutasi_pallet_draft_id = tmpdd.tr_mutasi_pallet_draft_id
                                LEFT JOIN distribusi_penerimaan dp
                                    ON tmpd.distribusi_penerimaan_id = dp.distribusi_penerimaan_id
                                LEFT JOIN penerimaan_pembelian pb
                                    ON dp.penerimaan_pembelian_id = pb.penerimaan_pembelian_id
                                LEFT JOIN pallet p
                                    ON tmpdd.pallet_id = p.pallet_id
                                LEFT JOIN pallet_detail pd
                                    ON p.pallet_id = pd.pallet_id
									
                                LEFT JOIN sku
                                    ON pd.sku_id = sku.sku_id
                                WHERE tmpdd.tr_mutasi_pallet_draft_id = '$mutasi_draft_id'
                                GROUP BY tmpd.depo_id_asal,
                                        tmpd.depo_detail_id_asal,
                                        tmpd.depo_id_tujuan,
                                        tmpd.depo_detail_id_tujuan,
                                        pb.client_wms_id,
                                        pd.sku_id,
                                        FORMAT(pd.sku_stock_expired_date, 'yyyy-MM-dd'),
                                        sku.sku_induk_id,
										pd.pallet_detail_id,
										tmpdd.pallet_id ,
										tmpdd.pallet_id_tujuan")->result();
	}

	public function check_data_sku_stock_asal($data)
	{
		return $this->db->select("*")
			->from("sku_stock")
			->where("depo_id", $data->depo_id_asal)
			->where("depo_detail_id", $data->depo_detail_id_asal)
			->where("sku_id", $data->sku_id)
			->where("FORMAT(sku_stock_expired_date,'yyyy-MM-dd')", $data->sku_stock_expired_date)
			->get();
	}

	public function check_data_sku_stock_tujuan($data)
	{
		return $this->db->select("*")
			->from("sku_stock")
			->where("depo_id", $data->depo_id_tujuan)
			->where("depo_detail_id", $data->depo_detail_id_tujuan)
			->where("sku_id", $data->sku_id)
			->where("FORMAT(sku_stock_expired_date,'yyyy-MM-dd')", $data->sku_stock_expired_date)
			->get();
	}

	public function insert_data_to_sku_stock($data, $sku_awal)
	{
		$sku_awal = $sku_awal != null ? $sku_awal : '';
		$id = $this->db->query("select newid() as id")->row();

		// $this->db->set("sku_stock_id", "NEWID()", FALSE);
		$this->db->set("sku_stock_id", $id->id);
		$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
		$this->db->set("client_wms_id", $data->client_wms_id);
		$this->db->set("depo_id", $data->depo_id_tujuan);
		$this->db->set("depo_detail_id", $data->depo_detail_id_tujuan);
		$this->db->set("sku_induk_id", $data->sku_induk_id);
		$this->db->set("sku_id", $data->sku_id);
		$this->db->set("sku_stock_expired_date", $data->sku_stock_expired_date);
		$this->db->set("sku_stock_awal", 0);
		$this->db->set("sku_stock_masuk", $data->sku_stock_qty);
		$this->db->set("sku_stock_alokasi", 0);
		$this->db->set("sku_stock_saldo_alokasi", 0);
		$this->db->set("sku_stock_keluar", 0);
		$this->db->set("sku_stock_akhir", 0);
		$this->db->set("sku_stock_is_jual", 1);
		$this->db->set("sku_stock_is_aktif", 1);
		$this->db->set("sku_stock_is_deleted", 0);

		$queryinsert = $this->db->insert("sku_stock");


		if ($data->pallet_id_tujuan != null || $data->pallet_id_tujuan != '') {


			$this->db->query("exec cek_exist_sku_stock_in_pallet '$data->pallet_id_tujuan','$data->sku_id','$id->id','$data->sku_stock_expired_date','$data->sku_stock_qty'");
			// $this->db->query("update pallet_detail set pallet_id = '$data->pallet_id_tujuan', sku_stock_id ='" .  $id->id . "' where pallet_detail_id ='$data->pallet_detail_id'");
			// $this->db->query("delete pallet_detail where pallet_detail_id ='$data->pallet_detail_id'");
		}
		if ($queryinsert) {
			// PROCEDURE MODE KELUAR
			$sku_stock_keluar = $data->sku_stock_qty;
			$query = $this->db->query("exec insertupdate_sku_stock 'keluar', '$sku_awal->sku_stock_id', NULL, $sku_stock_keluar");

			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}

	public function update_data_to_sku_stock($data, $sku_awal, $sku_akhir)
	{
		$sku_awal = $sku_awal != null ? $sku_awal : '';
		$sku_akhir = $sku_akhir != null ? $sku_akhir : '';

		$sku_stock_masuk = $data->sku_stock_qty;
		$sku_stock_keluar = $data->sku_stock_qty;

		// $cekPalletTujuan = $this->db->query("SELECT * from pallet_detail where sku_id='$data->sku_id' and format(sku_stock_expired_date,'yyyy-MM-dd')='$data->ed' and pallet_id='$data->pallet_id_tujuan'");

		// $qty = $cekPalletAsal->sku_stock_qty + $data->qty;
		// if ($cekPalletTujuan->num_rows() > 0) {

		//JIKA PALLET ADA MAKA JALANKAN INI, 
		if ($data->pallet_id_tujuan != null || $data->pallet_id_tujuan != '') {

			$this->db->query("exec cek_exist_sku_stock_in_pallet '$data->pallet_id_tujuan','$data->sku_id','$sku_akhir->sku_stock_id','$data->sku_stock_expired_date','$data->sku_stock_qty'");

			// $queryinsert = $this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_in','" . $data->pallet_id_tujuan . "' '" . $data->sku_stock_id . "	', '$data->qty'");

			// $this->db->query("delete pallet_detail where pallet_detail_id ='$data->pallet_detail_id'");
		}
		// } else {
		// 	$this->db->query("update pallet_detail set pallet_id = '$data->pallet_id_tujuan', sku_stock_id ='$sku_akhir->sku_stock_id' where pallet_detail_id ='$data->pallet_detail_id'");
		// }
		// PROCEDURE MODE KELUAR
		$chk = $this->db->query("exec insertupdate_sku_stock 'keluar', '$sku_awal->sku_stock_id', '$data->client_wms_id', $sku_stock_keluar");

		// $this->db->set("client_wms_id", $data->client_wms_id);
		// $this->db->set("sku_stock_masuk", $sku_stock_masuk);
		// $this->db->set("sku_stock_keluar", $sku_stock_keluar);
		// $this->db->where("sku_stock_id", $sku_awal->sku_stock_id);
		// $chk = $this->db->update("sku_stock");

		if ($chk) {
			// PROCEDURE MODE MASUK
			$query = $this->db->query("exec insertupdate_sku_stock 'masuk', '$sku_akhir->sku_stock_id', '$data->client_wms_id', $sku_stock_masuk");

			// $this->db->set("client_wms_id", $data->client_wms_id);
			// $this->db->set("sku_stock_masuk", $sku_stock_masuk);
			// $this->db->set("sku_stock_keluar", $sku_stock_keluar);
			// $this->db->where("sku_stock_id", $sku_akhir->sku_stock_id);
			// $query = $this->db->update("sku_stock");

			return $query;
		}

		// $data_asal = [
		//     'sku_stock_id' => $sku_awal->sku_stock_id,
		//     'qty' => $sku_stock_keluar,
		// ];

		// $data_tujuan = [
		//     'sku_stock_id' => $sku_akhir->sku_stock_id,
		//     'qty' => $sku_stock_masuk,
		// ];

		// $data = [
		//     'asal' => $data_asal,
		//     'tujuan' => $data_tujuan
		// ];
		// return $data;

		// return $this->db->update("sku_stock");
	}

	public function delete_data_in_pallet_temp($id)
	{
		$this->db->where('delivery_order_batch_id', $id);
		return $this->db->delete('pallet_temp');
	}

	public function get_data_header_konfirmasi_view($id)
	{
		$this->db->select("tmp.tr_mutasi_pallet_kode as kode,
                            tmp.tr_mutasi_pallet_tanggal as tgl,
                            tmp.tr_mutasi_pallet_status as status,
                            k.karyawan_nama as checker,
                            tmp.tr_mutasi_pallet_keterangan as keterangan,
                            tmpd.tr_mutasi_pallet_draft_kode as kode_draft,
                            tm.tipe_mutasi_nama as tipe,
                            dd.depo_detail_nama as depo_asal,
                            dd2.depo_detail_nama as depo_tujuan,
                            pb.penerimaan_pembelian_kode as pb_kode,
                            psj.penerimaan_surat_jalan_kode as no_sj");
		$this->db->from("tr_mutasi_pallet tmp");
		$this->db->join("tr_mutasi_pallet_draft tmpd", "tmp.tr_mutasi_pallet_draft_id = tmpd.tr_mutasi_pallet_draft_id", "left");
		$this->db->join("distribusi_penerimaan dp", "tmpd.distribusi_penerimaan_id = dp.distribusi_penerimaan_id", "left");
		$this->db->join("penerimaan_pembelian pb", "dp.penerimaan_pembelian_id = pb.penerimaan_pembelian_id", "left");
		$this->db->join("penerimaan_surat_jalan psj", "pb.penerimaan_surat_jalan_id = psj.penerimaan_surat_jalan_id", "left");
		$this->db->join("depo_detail dd", "tmp.depo_detail_id_asal = dd.depo_detail_id", "left");
		$this->db->join("depo_detail dd2", "tmp.depo_detail_id_tujuan = dd2.depo_detail_id", "left");
		$this->db->join("tipe_mutasi tm", "tmp.tipe_mutasi_id = tm.tipe_mutasi_id", "left");
		$this->db->join("karyawan k", "tmpd.tr_mutasi_pallet_draft_nama_checker = k.karyawan_nama", "left");
		$this->db->where("tmp.tr_mutasi_pallet_draft_id", $id);
		return $this->db->get()->row();
	}

	public function get_data_detail_konfirmasi_view($id)
	{
		$this->db->select("tmpd.tr_mutasi_pallet_detail_id as id,
                            p.pallet_id as pallet_id,
                            p.pallet_kode as kode,
                            p.rak_lajur_detail_id as rak_jalur,
                            rld.rak_lajur_detail_nama as rak_nama,
                            pj.pallet_jenis_nama as jenis,
                            tmpd.is_valid as status");
		$this->db->from("tr_mutasi_pallet_detail tmpd");
		$this->db->join("tr_mutasi_pallet tmp", "tmpd.tr_mutasi_pallet_id = tmp.tr_mutasi_pallet_id", "left");
		$this->db->join("pallet p", "tmpd.pallet_id = p.pallet_id", "left");
		$this->db->join("rak_lajur_detail rld", "p.rak_lajur_detail_id = rld.rak_lajur_detail_id", "left");
		$this->db->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left");
		$this->db->where("tmp.tr_mutasi_pallet_draft_id", $id);
		return $this->db->get()->result();
	}
	public function get_data_detail_konfirmasi_view_cetak($id)
	{
		// $check = $this->db->query("select * from tr_mutasi_pallet where tr_mutasi_pallet_draft_id='$id'")->num_rows();
		// if ($check != 0) {
		// 	$this->db->select("
		// 					tmp.tr_mutasi_pallet_draft_kode as kode_pallet1,
		// 					tmp.tr_mutasi_pallet_tanggal as tgl,
		// 					tmp.tr_mutasi_pallet_status as status,
		// 					tmpd.tr_mutasi_pallet_detail_id as id,
		// 					p.pallet_id as pallet_id,
		// 					p.pallet_kode as kode,
		// 					p.rak_lajur_detail_id as rak_jalur,
		// 					rld.rak_lajur_detail_nama as rak_nama,
		// 					pj.pallet_jenis_nama as jenis,
		// 					tmpd.is_valid as status");
		// 	$this->db->from("tr_mutasi_pallet_detail tmpd");
		// 	$this->db->join("tr_mutasi_pallet tmp", "tmpd.tr_mutasi_pallet_id = tmp.tr_mutasi_pallet_id", "left");
		// 	$this->db->join("pallet p", "tmpd.pallet_id = p.pallet_id", "left");
		// 	$this->db->join("rak_lajur_detail rld", "p.rak_lajur_detail_id = rld.rak_lajur_detail_id", "left");
		// 	$this->db->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left");
		// 	$this->db->where("tmp.tr_mutasi_pallet_draft_id", $id);
		// 	return $this->db->get()->result();
		// } else {	
		// draft
		$this->db->select("
							tmp.tr_mutasi_pallet_kode as no_mutasi,
							tmpd.tr_mutasi_pallet_draft_kode as no_mutasi_draft,
							pb.penerimaan_pembelian_kode as no_penerimaan,
							FORMAT(tmpd.tr_mutasi_pallet_draft_tanggal,'dd-MM-yyyy') as tgl,
							dd.depo_detail_nama as depo_asal,
                            dd2.depo_detail_nama as depo_tujuan,
							tmpd.tr_mutasi_pallet_draft_status as status,
							tmpdd.tr_mutasi_pallet_detail_draft_id as id,
                            p.pallet_id as pallet_id,
                            p.pallet_kode as kode,
                            tmpdd.rak_lajur_detail_id_tujuan as rak_jalur,
                            rld.rak_lajur_detail_nama as rak_nama,
                            pj.pallet_jenis_nama as jenis,
                            tmpdd.is_valid as status,
                            tmpdd.attachement as file");
		$this->db->from("tr_mutasi_pallet_detail_draft tmpdd");
		$this->db->join("tr_mutasi_pallet_draft tmpd", "tmpdd.tr_mutasi_pallet_draft_id = tmpd.tr_mutasi_pallet_draft_id", "left");
		$this->db->join("tr_mutasi_pallet tmp", "tmp.tr_mutasi_pallet_draft_id = tmpd.tr_mutasi_pallet_draft_id", "left");
		$this->db->join("depo_detail dd", "tmp.depo_detail_id_asal = dd.depo_detail_id", "left");
		$this->db->join("depo_detail dd2", "tmp.depo_detail_id_tujuan = dd2.depo_detail_id", "left");
		$this->db->join("distribusi_penerimaan dp", "tmpd.distribusi_penerimaan_id = dp.distribusi_penerimaan_id", "left");
		$this->db->join("penerimaan_pembelian pb", "dp.penerimaan_pembelian_id = pb.penerimaan_pembelian_id", "left");
		$this->db->join("pallet p", "tmpdd.pallet_id = p.pallet_id", "left");
		$this->db->join("rak_lajur_detail rld", "tmpdd.rak_lajur_detail_id_tujuan = rld.rak_lajur_detail_id", "left");
		$this->db->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left");
		$this->db->where("tmpdd.tr_mutasi_pallet_draft_id", $id);
		return $this->db->get()->result();
		// }
	}

	public function get_data_detail_pallet_view($id)
	{
		return $this->db->select("principle.principle_kode as principle,
                              sku.sku_kode as sku_kode,
                              sku.sku_nama_produk as sku_nama,
                              sku.sku_kemasan as sku_kemasan,
                              sku.sku_satuan as sku_satuan,
                              pd.sku_stock_expired_date as ed,
                              pt.penerimaan_tipe_nama as tipe,
                              pd.sku_stock_qty as qty")
			->from("tr_mutasi_pallet_detail mpdd")
			->join("pallet p", "mpdd.pallet_id = p.pallet_id", "left")
			->join("pallet_detail pd", "p.pallet_id = pd.pallet_id", "left")
			->join("sku", "pd.sku_id = sku.sku_id", "left")
			->join("penerimaan_tipe pt", "pd.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->join("principle", "sku.principle_id = principle.principle_id", "left")
			->where("mpdd.tr_mutasi_pallet_detail_id", $id)
			->order_by("sku.sku_kode", "ASC")->get()->result();
	}

	public function get_data_tr_mutasi_id($id)
	{
		return $this->db->select("tr_mutasi_pallet_id as id")
			->from("tr_mutasi_pallet")
			->where("tr_mutasi_pallet_draft_id", $id)
			->get()->row();
	}
	public function get_data_tr_mutasi_and_detail_id($id)
	{
		return $this->db->select("tr_mutasi_pallet_id as id,trp.rak_lajur_detail_id_tujuan, trp.pallet_id_tujuan")
			->from("tr_mutasi_pallet tr")
			->join("tr_mutasi_pallet_detail trp", "tr.tr_mutasi_pallet_id = trp.tr_mutasi_pallet_id", "left")
			->where("tr.tr_mutasi_pallet_draft_id", $id)
			->get()->row();
	}

	public function checkKodePallet($kode)
	{
		return $this->db->query("SELECT a.tr_mutasi_pallet_draft_id as id, 
                                        a.tr_mutasi_pallet_draft_kode as kode, 
                                        c.penerimaan_pembelian_tgl,
                                        FORMAT(a.tr_mutasi_pallet_draft_tanggal, 'yyyy-MM-dd HH:mm:ss') as tgl,
                                        a.tr_mutasi_pallet_draft_status as status,
                                        dd.depo_detail_nama as depo_asal,
                                        dd2.depo_detail_nama as depo_tujuan,
                                        c.penerimaan_pembelian_kode as pb_kode
                                FROM tr_mutasi_pallet_draft a
                                LEFT JOIN distribusi_penerimaan b ON a.distribusi_penerimaan_id = b.distribusi_penerimaan_id
                                LEFT JOIN penerimaan_pembelian c ON b.penerimaan_pembelian_id = c.penerimaan_pembelian_id
                                LEFT JOIN tr_mutasi_pallet_detail_draft d ON a.tr_mutasi_pallet_draft_id = d.tr_mutasi_pallet_draft_id
                                LEFT JOIN pallet e ON d.pallet_id = e.pallet_id
                                LEFT JOIN depo_detail dd ON a.depo_detail_id_asal = dd.depo_detail_id
                                LEFT JOIN depo_detail dd2 ON a.depo_detail_id_tujuan = dd2.depo_detail_id
                                WHERE a.depo_id_asal = '" . $this->session->userdata('depo_id') . "'
                                    AND a.tr_mutasi_pallet_draft_status in('In Progress', 'Completed')
                                    AND e.pallet_kode = '$kode'
                                order by c.penerimaan_pembelian_tgl DESC")->result();
	}

	public function getKodeAutoComplete($value, $type)
	{
		if ($type == 'notone') {
			return $this->db->select("SUBSTRING(pallet_kode, CHARINDEX('PAL/', pallet_kode), LEN(pallet_kode)) as kode")
				->from("pallet")
				->where("depo_id", $this->session->userdata('depo_id'))
				->like("pallet_kode", $value)->get()->result();
		}

		if ($type == 'one') {
			return $this->db->select("rak_lajur_detail_nama as kode")
				->from("rak_lajur_detail")
				->like("rak_lajur_detail_nama", $value)->get()->result();
		}
	}

	// public function eksekusi_pallet($id, $rak_lajur_detail_id, $sku_id, $depo_detail_id, $sku_stock_expired_date, $pallet_id_tujuan, $pallet_id_asal)
	public function eksekusi_pallet($id, $data, $gudang_detail_asal, $gudang_detail_tujuan)
	{

		$getDataSkuPallet = $this->db->select("
		
			mpdd.pallet_id as pallet_id_asal,
			mpdd.pallet_id_tujuan as pallet_id_tujuan,
			sku.client_wms_id,
			principle.principle_kode as principle, sku.sku_id,sku.sku_induk_id, sku.sku_kode as sku_kode, sku.sku_nama_produk as sku_nama, sku.sku_kemasan as sku_kemasan, sku.sku_satuan as sku_satuan, pd.sku_stock_expired_date as ed, pd.pallet_detail_id, pd.sku_stock_qty as qty")
			->from("tr_mutasi_pallet_detail_draft mpdd")
			->join("pallet p", "mpdd.pallet_id = p.pallet_id", "left")
			->join("pallet_detail pd", "p.pallet_id = pd.pallet_id", "left")
			->join("sku", "pd.sku_id = sku.sku_id", "left")
			->join("principle", "sku.principle_id = principle.principle_id", "left")
			->where("mpdd.tr_mutasi_pallet_detail_draft_id", $data->tr_mutasi_pallet_detail_draft_id)
			->order_by("sku.sku_kode", "ASC")->get()->result();


		// var_dump($getDataSkuPallet);
		// foreach ($getDataSkuPallet as $key => $value) {
		// 	var_dump($value->sku_id);
		// }
		// die;

		$this->db->trans_begin();
		foreach ($getDataSkuPallet as $key => $value) {

			$cek1 = $this->db->query("select * from sku_stock where sku_id ='$value->sku_id' and format(sku_stock_expired_date,'yyyy-MM-dd')='$value->ed' and depo_detail_id='$gudang_detail_tujuan'");

			if ($cek1->num_rows() > 0) {
				// if ($cek1->row(0)->sku_stock_id != null) {

				$cekPalletAsal =  $this->db->query("SELECT * from pallet_detail where sku_id='$value->sku_id' and format(sku_stock_expired_date,'yyyy-MM-dd') and pallet_id='$value->pallet_id'");

				$cekPalletTujuan = $this->db->query("SELECT * from pallet_detail where sku_id='$value->sku_id' and format(sku_stock_expired_date,'yyyy-MM-dd')='$value->ed' and pallet_id='$value->pallet_id_tujuan'");

				// $qty = $cekPalletAsal->sku_stock_qty + $data->qty;
				if ($cekPalletTujuan->num_rows() > 0) {

					$queryinsert = $this->db->query("exec insertupdate_sku_stock_pallet 'sku_stock_in','" . $value->pallet_id_tujuan . "' '" . $value->sku_stock_id . "', '$value->qty'");

					$this->db->query("delete pallet_detail where pallet_detail_id ='$value->pallet_detail_id'");
				} else {
					$this->db->query("update pallet_detail set pallet_id = '$value->pallet_id_tujuan', sku_stock_id ='" . $cek1->row(0)->sku_stock_id . "' where pallet_detail_id ='$value->pallet_detail_id'");
				}
				// }s
			} else {
				// foreach ($getDataSkuPallet as $key => $value) {
				$id = $this->db->query("select newid() as id")->row();
				$this->db->set("sku_stock_id", $id->id);
				// $this->db->set("unit_mandiri_id", $unit_mandiri_id);
				$this->db->set("client_wms_id", $value->client_wms_id);
				$this->db->set("depo_id", $this->session->userdata('depo_id'));
				$this->db->set("depo_detail_id", $gudang_detail_tujuan);
				$this->db->set("sku_induk_id", $value->sku_induk_id);
				$this->db->set("sku_id", $value->sku_id);
				$this->db->set("sku_stock_expired_date", $value->ed);
				// $this->db->set("sku_stock_batch_no", $sku_stock_batch_no);
				$this->db->set("sku_stock_awal", 0);
				$this->db->set("sku_stock_masuk", $value->qty);
				$this->db->set("sku_stock_alokasi", 0);
				$this->db->set("sku_stock_saldo_alokasi", 0);
				$this->db->set("sku_stock_keluar", 0);
				$this->db->set("sku_stock_akhir", 0);
				$this->db->set("sku_stock_is_jual", 1);
				$this->db->set("sku_stock_is_aktif", 1);
				$this->db->set("sku_stock_is_deleted", 0);

				$queryinsert = $this->db->insert("sku_stock");

				$this->db->query("update pallet_detail set pallet_id = '$data->pallet_id_tujuan', sku_stock_id ='" . $id->id . "' where pallet_detail_id ='$data->pallet_detail_id'");

				// }
			}
		}
	}
}
