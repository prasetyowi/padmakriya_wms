<?php

class M_GroupApproval extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getClientWMS($session_id)
	{
		$this->db->select('client_wms_id, client_wms_nama');
		$this->db->from('client_wms');
		if ($session_id != '') {
			$this->db->where('client_wms_id', $session_id);
		}

		$this->db->order_by('client_wms_nama', 'asc');

		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getDivisiAndLevel($id)
	{
		$query = $this->db->query("SELECT d.karyawan_divisi_nama, l.karyawan_level_nama
									FROM karyawan as k
									LEFT JOIN karyawan_divisi AS d ON k.karyawan_divisi_id = d.karyawan_divisi_id
									LEFT JOIN karyawan_level AS l ON k.karyawan_level_id = l.karyawan_level_id
									WHERE k.karyawan_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function getApprovalGrup()
	{
		$data = $this->db->select("*")
			->from('approval_group')
			->where('depo_id',  $this->session->userdata('depo_id'))
			->order_by('approval_group_nama', 'ASC')
			->get();
		return $data->result();
	}

	public function getKaryawan()
	{
		$data = $this->db->select("karyawan_id, karyawan_nama, karyawan_divisi_id, karyawan_level_id")
			->from('karyawan')
			->where('depo_id',  $this->session->userdata('depo_id'))
			// ->where('client_wms_id', $client)
			->order_by('karyawan_nama', 'ASC')
			->get();

		return $data->result_array();
	}

	public function saveGroup($grup_id, $depo_id, $nama, $keterangan, $is_aktif, $tgl_create, $who_create)
	{
		$this->db->set('approval_group_id', $grup_id);
		$this->db->set('depo_id', $depo_id);
		$this->db->set('approval_group_nama', $nama);
		$this->db->set('approval_group_keterangan', $keterangan);
		$this->db->set('approval_group_is_aktif', $is_aktif);
		$this->db->set('approval_group_tgl_create', $tgl_create);
		$this->db->set('approval_group_who_create', $who_create);

		$this->db->insert('approval_group');

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 0;
		} else {
			$queryinsert = 1;
		}

		return $queryinsert;
	}

	public function getDetailByGrupID($approval_group_id)
	{
		$this->db->select("*")
			->from("approval_group_detail")
			->where("approval_group_id", $approval_group_id)
			->order_by("approval_group_detail_no_urut", "ASC");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}

	public function isRelation($approval_group_detail_id)
	{
		$query = $this->db->query("SELECT approval_group_detail_id FROM approval_group_detail WHERE approval_group_detail_id = '$approval_group_detail_id'");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->row_array();
		}

		return $query;
	}

	public function deleteDetail($approval_group_id)
	{
		$this->db->where('approval_group_id', $approval_group_id);
		$this->db->delete('approval_group_detail');

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}

	public function saveDetail($detail_id, $grup_id, $karyawan_id, $urutan)
	{
		$this->db->set('approval_group_detail_id', $detail_id);
		$this->db->set('approval_group_id', $grup_id);
		$this->db->set('approval_group_detail_no_urut', $urutan);
		$this->db->set('karyawan_id', $karyawan_id);

		$this->db->insert('approval_group_detail');

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}
}
