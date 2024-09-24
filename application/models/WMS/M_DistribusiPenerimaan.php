<?php

date_default_timezone_set('Asia/Jakarta');

class M_DistribusiPenerimaan extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function get_data_penerimaan_by_params($tahun, $bulan, $kode, $status)
	{
		$this->db->select("dp.distribusi_penerimaan_id as id,
                        dp.distribusi_penerimaan_kode as kode,
                        FORMAT(dp.distribusi_penerimaan_tgl_create, 'dd-MM-yyyy') as tgl,
                        dp.distribusi_penerimaan_status as status,
                        pp.penerimaan_pembelian_kode as pp_kode,
                        depo_detail.depo_detail_nama as depo");
		$this->db->from("distribusi_penerimaan dp");
		$this->db->join("penerimaan_pembelian pp", "dp.penerimaan_pembelian_id = pp.penerimaan_pembelian_id", "left");
		$this->db->join("depo", "dp.depo_id_asal = depo.depo_id", "left");
		$this->db->join("depo_detail", "dp.depo_detail_id_asal = depo_detail.depo_detail_id", "left");
		$this->db->where("YEAR(dp.distribusi_penerimaan_tgl_create)", $tahun);
		$this->db->where("MONTH(dp.distribusi_penerimaan_tgl_create)", $bulan);
		if ($kode != "") {
			$this->db->where("dp.distribusi_penerimaan_id", $kode);
		}
		if ($status != "") {
			$this->db->where("dp.distribusi_penerimaan_status", $status);
		}
		$this->db->order_by("dp.distribusi_penerimaan_kode", "ASC");
		$data = $this->db->get()->result();
		return $data;
	}

	public function get_kode_distribusi_penerimaan()
	{
		return $this->db->select("distribusi_penerimaan_id as id, distribusi_penerimaan_kode as kode")
			->from("distribusi_penerimaan")->order_by('distribusi_penerimaan_kode', 'ASC')->get()->result();
	}

	public function get_data_dpdt($id)
	{
		return $this->db->select("dpdt.distribusi_penerimaan_detail_temp_id as id,
                              dpdt.depo_detail_id_tujuan as gudang_id,
                              dpdt.is_valid as status,
                              p.pallet_kode as kode,
                              pj.pallet_jenis_nama as nama")
			->from("distribusi_penerimaan_detail_temp dpdt")
			->join("pallet p", "dpdt.pallet_id = p.pallet_id", "left")
			->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left")
			->where("dpdt.distribusi_penerimaan_id", $id)
			->order_by("p.pallet_kode", "ASC")->get()->result();
	}

	public function get_depo_detail_by_depo()
	{
		return $this->db->select("depo_detail_id, depo_detail_nama")
			->from("depo_detail")
			->where("depo_id", $this->session->userdata('depo_id'))->get()->result();
	}

	public function get_no_penerimaan()
	{
		return $this->db->select("pp.penerimaan_pembelian_id as id, pp.penerimaan_pembelian_kode as kode")
			->from("penerimaan_pembelian pp")
			->join("distribusi_penerimaan dp", "pp.penerimaan_pembelian_id =  dp.penerimaan_pembelian_id", "left")
			->where("dp.penerimaan_pembelian_id is NULL")
			->order_by("pp.penerimaan_pembelian_tgl_create", "ASC")->get()->result();
	}

	public function get_data_by_no_penerimaan($id)
	{
		return $this->db->select("sj.penerimaan_surat_jalan_kode as sj_kode,
                              sj.penerimaan_surat_jalan_no_sj as sj_no,
                              sj.principle_id as principle_id,
                              pt.penerimaan_tipe_nama as tipe,
                              sj.penerimaan_surat_jalan_status as status,
                              pb.penerimaan_pembelian_nopol as nopol,
                              pb.penerimaan_pembelian_tgl as tgl_btb,
                              ekspedisi.ekspedisi_kode as eks_kode,
                              ekspedisi.ekspedisi_nama as eks_nama,
                              pb.penerimaan_pembelian_pengemudi as pengemudi,
                              dd.depo_detail_id as gudang_id,
                              dd.depo_detail_nama as gudang,
                              k.karyawan_nama as checker,
                              k.karyawan_id as checker_id")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_surat_jalan sj", "pb.penerimaan_surat_jalan_id = sj.penerimaan_surat_jalan_id", "left")
			->join("penerimaan_tipe pt", "sj.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->join("ekspedisi", "pb.ekspedisi_id = ekspedisi.ekspedisi_id", "left")
			->join("depo_detail dd", "pb.depo_detail_id = dd.depo_detail_id", "left")
			->join("karyawan k", "pb.penerimaan_pembelian_nama_checker = k.karyawan_id", "left")
			->where("pb.penerimaan_pembelian_id", $id)->get()->row();
	}

	public function get_data_pallet_by_no_penerimaan($id)
	{
		return $this->db->select("pb.penerimaan_pembelian_id as po_id,
                              p.pallet_id")
			->from("penerimaan_pembelian pb")
			->join("penerimaan_pembelian_detail2 pbd", "pb.penerimaan_pembelian_id = pbd.penerimaan_pembelian_id", "left")
			->join("pallet p", "pbd.pallet_id = p.pallet_id", "left")
			->where("pb.penerimaan_pembelian_id", $id)->get()->result();
	}

	public function check_kode_pallet_by_no_penerimaan($po_id, $kode_pallet)
	{

		return $this->db->select("dpdt.distribusi_penerimaan_detail_temp_id as id, dpdt.is_valid as status, dpdt.attachement as file")
			->from("distribusi_penerimaan_detail_temp dpdt")
			->join("pallet p", "dpdt.pallet_id = p.pallet_id", "left")
			->where("dpdt.distribusi_penerimaan_id", $po_id)
			->where("p.pallet_kode", $kode_pallet)->get()->row();
	}

	public function chek_data_dpdt($po_id, $pallet_id)
	{
		return $this->db->select("*")->from("distribusi_penerimaan_detail_temp")
			->where("depo_id_tujuan", $this->session->userdata("depo_id"))
			->where("distribusi_penerimaan_id", $po_id)
			->where("pallet_id", $pallet_id)->get();
	}

	public function insert_ke_dpdt($data)
	{
		$this->db->set("distribusi_penerimaan_detail_temp_id", "NewID()", FALSE);
		$this->db->set("distribusi_penerimaan_id", $data->po_id);
		$this->db->set("depo_id_tujuan", $this->session->userdata('depo_id'));
		$this->db->set("pallet_id", $data->pallet_id);
		$this->db->set("is_valid", 0);

		$queryinsert = $this->db->insert("distribusi_penerimaan_detail_temp");

		return $queryinsert;
	}

	public function update_status_dpdt($data, $file)
	{
		$file_ = $file == null ? null : $file;

		$this->db->set("is_valid", 1);
		$this->db->set("attachement", $file_);
		$this->db->where("distribusi_penerimaan_detail_temp_id", $data->id);
		$this->db->update("distribusi_penerimaan_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function update_gudang_tujuan_by_id($id, $gudang_tujuan_id)
	{
		$this->db->set("depo_detail_id_tujuan", $gudang_tujuan_id);
		$this->db->where("distribusi_penerimaan_detail_temp_id", $id);
		$this->db->update("distribusi_penerimaan_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function save_data_to_distribusi_penerimaan($dp_id, $generate_kode, $tgl, $no_penerimaan, $status, $keterangan, $gudang_asal)
	{
		$tgl_ = $tgl . " " . date('H:i:s');

		$ket = $keterangan == "" ? null : $keterangan;
		$this->db->set("distribusi_penerimaan_id", $dp_id);
		$this->db->set("penerimaan_pembelian_id", $no_penerimaan);
		$this->db->set("distribusi_penerimaan_kode", $generate_kode);
		$this->db->set("distribusi_penerimaan_tanggal", $tgl_);
		$this->db->set("distribusi_penerimaan_keterangan", $ket);
		$this->db->set("distribusi_penerimaan_status", $status);
		$this->db->set("depo_id_asal", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id_asal", $gudang_asal);
		$this->db->set("distribusi_penerimaan_tgl_create", "GETDATE()", false);
		$this->db->set("distribusi_penerimaan_who_create", $this->session->userdata('pengguna_username'));

		$queryinsert = $this->db->insert("distribusi_penerimaan");

		return $queryinsert;
		// return true;
	}

	public function get_data_dpdt_by_no_penerimaan($no_penerimaan)
	{
		return $this->db->select("*")
			->from("distribusi_penerimaan_detail_temp")
			->where("distribusi_penerimaan_id", $no_penerimaan)
			->get()->result();
	}

	public function get_data_dpdt_by_no_penerimaan2($no_penerimaan, $detail_id_tujuan)
	{
		return $this->db->select("*")
			->from("distribusi_penerimaan_detail_temp")
			->where("distribusi_penerimaan_id", $no_penerimaan)
			->where("depo_detail_id_tujuan", $detail_id_tujuan)
			->get()->result();
	}

	public function save_data_to_distribusi_penerimaan_detail($dp_id, $data)
	{
		$this->db->set("distribusi_penerimaan_detail_id", "NewID()", FALSE);
		$this->db->set("distribusi_penerimaan_id", $dp_id);
		$this->db->set("depo_id_tujuan", $data->depo_id_tujuan);
		$this->db->set("depo_detail_id_tujuan", $data->depo_detail_id_tujuan);
		$this->db->set("pallet_id", $data->pallet_id);
		$this->db->set("is_valid", $data->is_valid);
		$this->db->set("attachement", $data->attachement);

		$queryinsert = $this->db->insert("distribusi_penerimaan_detail");
		return $queryinsert;
	}

	public function delete_data_distribusi_penerimaan_detail_temp_by_params($id)
	{
		$this->db->where('distribusi_penerimaan_id', $id);
		return $this->db->delete('distribusi_penerimaan_detail_temp');
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
			->from("distribusi_penerimaan_detail_temp dpdt")
			->join("pallet p", "dpdt.pallet_id = p.pallet_id", "left")
			->join("pallet_detail pd", "p.pallet_id = pd.pallet_id", "left")
			->join("sku", "pd.sku_id = sku.sku_id", "left")
			->join("penerimaan_tipe pt", "pd.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->join("principle", "sku.principle_id = principle.principle_id", "left")
			->where("dpdt.distribusi_penerimaan_detail_temp_id", $id)
			->order_by("sku.sku_kode", "ASC")->get()->result();
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function save_data_to_mutasi_pallet_draft($mp_id, $dp_id, $generate_kode_, $tgl, $principle, $tipe_penerimaan, $status, $keterangan, $gudang_asal, $depo_detail_id_tujuan, $checker)
	{
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
		$this->db->set("tr_mutasi_pallet_draft_nama_checker", $checker);

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

	public function getPrefixPallet($po_id)
	{
		return $this->db->select("pj.pallet_jenis_kode")
			->from("distribusi_penerimaan_detail_temp dpdt")
			->join("pallet p", "dpdt.pallet_id = p.pallet_id", "left")
			->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left")
			->where("dpdt.distribusi_penerimaan_id", $po_id)->get()->row();
	}

	public function get_header_view($id)
	{
		return $this->db->select("sj.penerimaan_surat_jalan_kode as sj_kode,
                            sj.penerimaan_surat_jalan_no_sj as sj_no,
                            pt.penerimaan_tipe_nama as tipe,
                            dp.distribusi_penerimaan_kode as kode,
                            dp.distribusi_penerimaan_status as status,
                            dp.distribusi_penerimaan_keterangan as keterangan,
                            FORMAT(dp.distribusi_penerimaan_tanggal, 'dd-MM-yyyy') as tgl,
                            pb.penerimaan_pembelian_nopol as nopol,
                            FORMAT(pb.penerimaan_pembelian_tgl, 'dd-MM-yyyy') as tgl_btb,
                            pb.penerimaan_pembelian_kode as kode_btb,
                            ekspedisi.ekspedisi_kode as eks_kode,
                            ekspedisi.ekspedisi_nama as eks_nama,
                            pb.penerimaan_pembelian_pengemudi as pengemudi,
                            dd.depo_detail_nama as gudang,
                            k.karyawan_nama as checker")
			->from("distribusi_penerimaan dp")
			->join("penerimaan_pembelian pb", "dp.penerimaan_pembelian_id = pb.penerimaan_pembelian_id", "left")
			->join("penerimaan_surat_jalan sj", "pb.penerimaan_surat_jalan_id = sj.penerimaan_surat_jalan_id", "left")
			->join("penerimaan_tipe pt", "sj.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->join("ekspedisi", "pb.ekspedisi_id = ekspedisi.ekspedisi_id", "left")
			->join("depo_detail dd", "dp.depo_detail_id_asal = dd.depo_detail_id", "left")
			->join("karyawan k", "pb.penerimaan_pembelian_nama_checker = k.karyawan_id", "left")
			->where("dp.distribusi_penerimaan_id", $id)->get()->row();
	}

	public function get_detail_view($id)
	{
		return $this->db->select("dpd.distribusi_penerimaan_detail_id as id,
                              dpd.is_valid as valid,
                              dd.depo_detail_nama as gudang_tujuan,
                              p.pallet_kode as kode,
                              pj.pallet_jenis_nama as pallet_jenis")
			->from("distribusi_penerimaan_detail dpd")
			->join("depo_detail dd", "dpd.depo_detail_id_tujuan = dd.depo_detail_id", "left")
			->join("pallet p", "dpd.pallet_id = p.pallet_id", "left")
			->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left")
			->where("dpd.distribusi_penerimaan_id", $id)->get()->result();
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
			->from("distribusi_penerimaan_detail dpd")
			->join("pallet p", "dpd.pallet_id = p.pallet_id", "left")
			->join("pallet_detail pd", "p.pallet_id = pd.pallet_id", "left")
			->join("sku", "pd.sku_id = sku.sku_id", "left")
			->join("penerimaan_tipe pt", "pd.penerimaan_tipe_id = pt.penerimaan_tipe_id", "left")
			->join("principle", "sku.principle_id = principle.principle_id", "left")
			->where("dpd.distribusi_penerimaan_detail_id", $id)
			->order_by("sku.sku_kode", "ASC")->get()->result();
	}

	public function getKodeAutoComplete($value)
	{
		return $this->db->select("CONCAT(REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 2)), '/', REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 3))) as kode")
			->from("pallet")
			->where("depo_id", $this->session->userdata('depo_id'))
			->like("pallet_kode", $value)->get()->result();
	}
}
