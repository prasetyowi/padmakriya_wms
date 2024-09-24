<?php

class M_RegisterTandaTerima extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function GetPerusahaan()
	{
		// $this->db->select("*")
		// 	->from("client_wms")
		// 	->order_by("client_wms_nama");
		// $query = $this->db->get();

		if ($this->session->userdata('client_wms_id') == "") {

			$query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0");
		} else {


			$query = $this->db->query("SELECT b.*
						FROM depo_client_wms a
						LEFT JOIN client_WMS b ON a.client_wms_id = b.client_wms_id
						WHERE a.client_wms_id = '" . $this->session->userdata('client_wms_id') . "'
						AND a.depo_id = '" . $this->session->userdata('depo_id') . "'
						AND b.client_wms_is_aktif = 1
						AND b.client_wms_is_deleted = 0");
		}

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function Get_NewID()
	{
		$query = $this->db->query("SELECT NEWID() AS kode");
		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->row(0)->kode;
		}

		return $query;
	}

	public function GetJumlahBayarDO($tanggal, $perusahaan)
	{
		$query = $this->db->query("SELECT principle.principle_id,
										ISNULL(principle.principle_kode, 'UNKNOWN') AS principle_kode,
										ISNULL(client_wms_rek_detail.nama_bank, '') AS nama_bank,
										ISNULL(client_wms_rek_detail.no_rekening, '') AS no_rekening,
										SUM(do.delivery_order_jumlah_bayar) AS delivery_order_jumlah_bayar
									FROM delivery_order DO
									LEFT JOIN delivery_order_detail do_dtl ON do_dtl.delivery_order_id = do.delivery_order_id
									LEFT JOIN sku ON sku.sku_id = do_dtl.sku_id
									LEFT JOIN principle ON principle.principle_id = sku.principle_id
									LEFT JOIN client_wms_rek ON client_wms_rek.client_wms_id = do.client_wms_id
									LEFT JOIN client_wms_rek_detail ON client_wms_rek_detail.client_wms_rek_id = client_wms_rek.client_wms_rek_id
									AND client_wms_rek_detail.principle_id = principle.principle_id
									AND convert(nvarchar(36), client_wms_rek_detail.depo_id) = '" . $this->session->userdata('depo_id') . "'
									WHERE FORMAT(do.delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tanggal'
									AND convert(nvarchar(36), do.depo_id) = '" . $this->session->userdata('depo_id') . "'
									AND convert(nvarchar(36), do.client_wms_id) = '$perusahaan'
									GROUP BY principle.principle_id,
											ISNULL(principle.principle_kode, 'UNKNOWN'),
											ISNULL(client_wms_rek_detail.nama_bank, ''),
											ISNULL(client_wms_rek_detail.no_rekening, '')
									ORDER BY ISNULL(principle.principle_kode, 'UNKNOWN') ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_tr_abacus_by_filter($tgl1, $tgl2, $perusahaan, $status)
	{

		if ($perusahaan == "") {
			$perusahaan = "";
		} else {
			$perusahaan = " AND a.client_wms_id = '$perusahaan'";
		}

		if ($status == "") {
			$status = "";
		} else {
			$status = " AND a.tr_abacus_status = '$status'";
		}

		$query = $this->db->query("SELECT
									a.tr_abacus_id,
									a.client_wms_id,
									perusahaan.client_wms_nama,
									a.depo_id,
									a.tr_abacus_kode,
									ISNULL(a.tr_abacus_reff_kode, '') AS tr_abacus_reff_kode,
									ISNULL(a.tr_abacus_grand_total, 0) AS tr_abacus_grand_total,
									FORMAT(a.tr_abacus_tanggal, 'dd-MM-yyyy') AS tr_abacus_tanggal,
									ISNULL(a.tr_abacus_status,'') AS tr_abacus_status,
									a.tr_abacus_tgl_create,
									a.tr_abacus_who_create,
									a.tr_abacus_keterangan,
									a.tr_abacus_tgl_update,
									a.tr_abacus_who_update
									FROM tr_abacus a
									LEFT JOIN client_wms perusahaan
									ON perusahaan.client_wms_id = a.client_wms_id
									WHERE FORMAT(a.tr_abacus_tanggal, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2' 
									" . $perusahaan . "
									" . $status . "
									ORDER BY a.tr_abacus_tanggal DESC, a.tr_abacus_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_tr_abacus_header_by_id($id)
	{
		$query = $this->db->query("SELECT
									tr_abacus_id,
									client_wms_id,
									depo_id,
									tr_abacus_kode,
									tr_abacus_reff_kode,
									tr_abacus_grand_total,
									FORMAT(tr_abacus_tanggal, 'yyyy-MM-dd') AS tr_abacus_tanggal,
									tr_abacus_status,
									tr_abacus_tgl_create,
									tr_abacus_who_create,
									tr_abacus_keterangan,
									tr_abacus_tgl_update,
									tr_abacus_who_update
									FROM tr_abacus
									WHERE tr_abacus_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_tr_abacus_detail_by_id($id)
	{
		$query = $this->db->query("SELECT 
										a.tr_abacus_detail_id,
										a.tr_abacus_id,
										ISNULL(principle.principle_kode, 'UNKNOWN') AS principle_kode,
										a.principle_id,
										ISNULL(a.no_rekening,'') AS no_rekening,
										ISNULL(client_wms_rek_detail.nama_bank, '') AS nama_bank,
										a.tr_abacus_detail_total
									FROM tr_abacus_detail a
									LEFT JOIN principle
									ON principle.principle_id = a.principle_id
									LEFT JOIN client_wms_rek_detail ON client_wms_rek_detail.no_rekening = a.no_rekening
									AND client_wms_rek_detail.principle_id = a.principle_id
									AND convert(nvarchar(36), client_wms_rek_detail.depo_id) = '" . $this->session->userdata('depo_id') . "'
									WHERE a.tr_abacus_id = '$id'
									ORDER BY ISNULL(principle.principle_kode, 'UNKNOWN') ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function getPrefixPallet($id)
	{
		return $this->db->select("pj.pallet_jenis_kode")
			->from("tr_mutasi_pallet_draft tmpd")
			->join("tr_mutasi_pallet_detail_draft tmpdd", "tmpd.tr_mutasi_pallet_draft_id = tmpdd.tr_mutasi_pallet_draft_id", "left")
			->join("pallet p", "tmpdd.pallet_id = p.pallet_id", "left")
			->join("pallet_jenis pj", "p.pallet_jenis_id = pj.pallet_jenis_id", "left")
			->where("tmpd.tr_mutasi_pallet_draft_id", $id)->get()->row();

		// return $this->db->last_query();
	}

	public function insert_tr_abacus($tr_abacus_id, $client_wms_id, $depo_id, $tr_abacus_kode, $tr_abacus_reff_kode, $tr_abacus_grand_total, $tr_abacus_tanggal, $tr_abacus_status, $tr_abacus_tgl_create, $tr_abacus_who_create, $tr_abacus_keterangan, $tr_abacus_tgl_update, $tr_abacus_who_update)
	{
		$tr_abacus_id = $tr_abacus_id == '' ? null : $tr_abacus_id;
		$client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
		$depo_id = $depo_id == '' ? null : $depo_id;
		$tr_abacus_kode = $tr_abacus_kode == '' ? null : $tr_abacus_kode;
		$tr_abacus_reff_kode = $tr_abacus_reff_kode == '' ? null : $tr_abacus_reff_kode;
		$tr_abacus_grand_total = $tr_abacus_grand_total == '' ? null : $tr_abacus_grand_total;
		$tr_abacus_tanggal = $tr_abacus_tanggal == '' ? null : $tr_abacus_tanggal;
		$tr_abacus_status = $tr_abacus_status == '' ? null : $tr_abacus_status;
		$tr_abacus_tgl_create = $tr_abacus_tgl_create == '' ? null : $tr_abacus_tgl_create;
		$tr_abacus_who_create = $tr_abacus_who_create == '' ? null : $tr_abacus_who_create;
		$tr_abacus_keterangan = $tr_abacus_keterangan == '' ? null : $tr_abacus_keterangan;
		$tr_abacus_tgl_update = $tr_abacus_tgl_update == '' ? null : $tr_abacus_tgl_update;
		$tr_abacus_who_update = $tr_abacus_who_update == '' ? null : $tr_abacus_who_update;

		$this->db->set('tr_abacus_id', $tr_abacus_id);
		$this->db->set('client_wms_id', $client_wms_id);
		$this->db->set('depo_id', $this->session->userdata('depo_id'));
		$this->db->set('tr_abacus_kode', $tr_abacus_kode);
		$this->db->set('tr_abacus_reff_kode', $tr_abacus_reff_kode);
		$this->db->set('tr_abacus_grand_total', $tr_abacus_grand_total);
		$this->db->set('tr_abacus_tanggal', $tr_abacus_tanggal);
		$this->db->set('tr_abacus_status', $tr_abacus_status);
		$this->db->set('tr_abacus_tgl_create', "GETDATE()", FALSE);
		$this->db->set('tr_abacus_who_create', $this->session->userdata('pengguna_username'));
		$this->db->set('tr_abacus_keterangan', $tr_abacus_keterangan);
		$this->db->set('tr_abacus_tgl_update', "GETDATE()", FALSE);
		$this->db->set('tr_abacus_who_update', $this->session->userdata('pengguna_username'));

		$queryinsert = $this->db->insert("tr_abacus");

		return $queryinsert;
	}

	public function insert_tr_abacus_detail($tr_abacus_detail_id, $tr_abacus_id, $principle_id, $no_rekening, $tr_abacus_detail_total)
	{
		$tr_abacus_id = $tr_abacus_id == '' ? null : $tr_abacus_id;
		$principle_id = $principle_id == '' ? null : $principle_id;
		$no_rekening = $no_rekening == '' ? null : $no_rekening;
		$tr_abacus_detail_total = $tr_abacus_detail_total == '' ? null : $tr_abacus_detail_total;

		$this->db->set('tr_abacus_detail_id', $tr_abacus_detail_id);
		$this->db->set('tr_abacus_id', $tr_abacus_id);
		$this->db->set('principle_id', $principle_id);
		$this->db->set('no_rekening', $no_rekening);
		$this->db->set('tr_abacus_detail_total', $tr_abacus_detail_total);

		$queryinsert = $this->db->insert("tr_abacus_detail");

		return $queryinsert;
	}

	public function update_tr_abacus($tr_abacus_id, $client_wms_id, $depo_id, $tr_abacus_kode, $tr_abacus_reff_kode, $tr_abacus_grand_total, $tr_abacus_tanggal, $tr_abacus_status, $tr_abacus_tgl_create, $tr_abacus_who_create, $tr_abacus_keterangan, $tr_abacus_tgl_update, $tr_abacus_who_update)
	{
		$tr_abacus_id = $tr_abacus_id == '' ? null : $tr_abacus_id;
		$client_wms_id = $client_wms_id == '' ? null : $client_wms_id;
		$depo_id = $depo_id == '' ? null : $depo_id;
		$tr_abacus_kode = $tr_abacus_kode == '' ? null : $tr_abacus_kode;
		$tr_abacus_reff_kode = $tr_abacus_reff_kode == '' ? null : $tr_abacus_reff_kode;
		$tr_abacus_grand_total = $tr_abacus_grand_total == '' ? null : $tr_abacus_grand_total;
		$tr_abacus_tanggal = $tr_abacus_tanggal == '' ? null : $tr_abacus_tanggal;
		$tr_abacus_status = $tr_abacus_status == '' ? null : $tr_abacus_status;
		$tr_abacus_tgl_create = $tr_abacus_tgl_create == '' ? null : $tr_abacus_tgl_create;
		$tr_abacus_who_create = $tr_abacus_who_create == '' ? null : $tr_abacus_who_create;
		$tr_abacus_keterangan = $tr_abacus_keterangan == '' ? null : $tr_abacus_keterangan;
		$tr_abacus_tgl_update = $tr_abacus_tgl_update == '' ? null : $tr_abacus_tgl_update;
		$tr_abacus_who_update = $tr_abacus_who_update == '' ? null : $tr_abacus_who_update;

		$this->db->set('client_wms_id', $client_wms_id);
		$this->db->set('depo_id', $this->session->userdata('depo_id'));
		$this->db->set('tr_abacus_kode', $tr_abacus_kode);
		$this->db->set('tr_abacus_reff_kode', $tr_abacus_reff_kode);
		$this->db->set('tr_abacus_grand_total', $tr_abacus_grand_total);
		$this->db->set('tr_abacus_tanggal', $tr_abacus_tanggal);
		$this->db->set('tr_abacus_status', $tr_abacus_status);
		$this->db->set('tr_abacus_tgl_create', "GETDATE()", FALSE);
		$this->db->set('tr_abacus_who_create', $this->session->userdata('pengguna_username'));
		$this->db->set('tr_abacus_keterangan', $tr_abacus_keterangan);
		$this->db->set('tr_abacus_tgl_update', "GETDATE()", FALSE);
		$this->db->set('tr_abacus_who_update', $this->session->userdata('pengguna_username'));
		$this->db->where('tr_abacus_id', $tr_abacus_id);

		$queryinsert = $this->db->update("tr_abacus");

		return $queryinsert;
	}

	public function delete_tr_abacus($tr_abacus_id)
	{
		$this->db->where('tr_abacus_id', $tr_abacus_id);
		$queryinsert = $this->db->delete("tr_abacus_detail");

		return $queryinsert;
	}
}
