<?php

class M_Approval extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->load->model('M_AutoGen');
		$this->load->model('M_Vrbl');
	}

	public function ApprovalProses($dataAcc, $dataReject)
	{
		$this->db->trans_start();
		// data yg di approv
		if ($dataAcc) {
			# code...
			foreach ($dataAcc as $key => $value) {
				$queryProsedure = $this->db->query("
							exec approval_proses '" . $value["approval_id"] . "', '" . $this->session->userdata('karyawan_id') . "', 1, '" . $value["approval_keterangan"] . "'
				");
			}
		}
		// data yg di reject
		if ($dataReject) {
			# code...
			foreach ($dataReject as $key => $value) {
				$queryProsedure = $this->db->query("
							exec approval_proses '" . $value["approval_id"] . "', '" . $this->session->userdata('karyawan_id') . "', 0, '" . $value["approval_keterangan"] . "'
				");
			}
		}
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			return 0;
		}

		return 1;
	}

	public function Get_DataApproval()
	{
		$Bahasa = $this->session->userdata('Bahasa');

		/**
		 * backup lama
		 * 
		 */
		// $data = $this->db->select("	apv.approval_setting_id, apv.approval_setting_keterangan, 
		// 							gja.jenis_" . $Bahasa . " as approval_setting_jenis,
		// 							(COUNT(apv1.approval_id)+COUNT(apv2.approval_id)) as jumlah")
		// 	->from('approval_setting apv')
		// 	->join('approval as apv1', "apv1.approval_setting_id = apv.approval_setting_id AND apv1.approval_status = 'In progress' AND apv1.depo_id = '" . $this->session->userdata('depo_id') . "' AND apv1.approval_is_direct_spv = 0 AND apv1.approval_level_id = '" . $this->session->userdata('karyawan_level_id') . "' AND apv1.approval_divisi_id = '" . $this->session->userdata('karyawan_divisi_id') . "'", 'left')
		// 	->join('approval as apv2', "apv2.approval_setting_id = apv.approval_setting_id AND apv2.approval_status = 'In progress' AND apv2.depo_id = '" . $this->session->userdata('depo_id') . "' AND apv2.approval_is_direct_spv = 1 AND apv2.approval_karyawan_id = '" . $this->session->userdata('karyawan_id') . "'", 'left')
		// 	->join('dbo.getjenisapproval() as gja', "gja.jenis = apv.approval_setting_jenis", "left")
		// 	// ->where('anggaran_detail.anggaran_detail_kode', $kode)
		// 	->where('apv.depo_id',  $this->session->userdata('depo_id'))
		// 	->where('apv.approval_setting_is_aktif',  '1')
		// 	->group_by('apv.approval_setting_id')
		// 	->group_by('apv.approval_setting_keterangan')
		// 	->group_by('gja.jenis_' . $Bahasa)
		// 	//->group_by('apv.approval_setting_jenis')
		// 	->order_by('COUNT(apv1.approval_id)+COUNT(apv2.approval_id)', 'asc')
		// 	->order_by('approval_setting_jenis', 'asc')
		// 	->get();

		// $data = $this->db->select("apv.approval_setting_id,apv.approval_setting_keterangan,apv.approval_setting_jenis,(COUNT(apv1.approval_id)+COUNT(apv2.approval_id)) as jumlah")
		//     ->from('approval_setting apv')
		//     ->join('approval as apv1', "apv1.approval_setting_id = apv.approval_setting_id AND apv1.approval_status = 'In progress' AND apv1.depo_id = '" . $this->session->userdata('depo_id') . "' AND apv1.approval_is_direct_spv = 0 AND apv1.approval_level_id = 'E89ADD55-2ECE-4308-93CF-2406F8AF8F50' AND apv1.approval_divisi_id = 'E4F4AD27-6823-4026-BDDC-A977700B8ADA'", 'left')
		//     ->join('approval as apv2', "apv2.approval_setting_id = apv.approval_setting_id AND apv2.approval_status = 'In progress' AND apv2.depo_id = '" . $this->session->userdata('depo_id') . "' AND apv2.approval_is_direct_spv = 1 AND apv2.approval_karyawan_id = '4D607153-10D0-4D21-8B66-9F744FFF754B'", 'left')
		//     // ->where('anggaran_detail.anggaran_detail_kode', $kode)
		//     ->group_by('apv.approval_setting_id')
		//     ->group_by('apv.approval_setting_keterangan')
		//     ->group_by('apv.approval_setting_jenis')
		//     ->get();


		/**
		 * tes baru
		 */
		$arrGroup = [];
		$pengguna_grup_id = $this->session->userdata('pengguna_grup_id');
		$is_dewa = $this->db->query("select * from pengguna_grup where pengguna_grup_id ='$pengguna_grup_id' and pengguna_grup_is_dewa =1")->num_rows();
		$karyawan_id = $this->session->userdata('karyawan_id');
		$dataGroup = $this->db->query("SELECT b.approval_group_id from approval_group a LEFT JOIN approval_group_detail b on b.approval_group_id = a.approval_group_id where b.karyawan_id = '$karyawan_id'")->result_array();
		foreach ($dataGroup as $key => $value) {
			// array_push($arrGroup, $value['approval_group_id']);
			$arrGroup[$key] = "'" . $value['approval_group_id'] . "'";
		}
		// var_dump($dataGroup);
		$list = implode(", ", $arrGroup);
		// die;

		if ($is_dewa > 0) {

			$data = $this->db->select("	apv.approval_setting_id, apv.approval_setting_keterangan, 
									gja.jenis_" . $Bahasa . " as approval_setting_jenis,
									COUNT(apv1.approval_id) as jumlah")
				->from('approval_setting apv')
				->join('approval as apv1', "apv1.approval_setting_id = apv.approval_setting_id AND apv1.approval_status = 'In progress' AND apv1.depo_id = '" . $this->session->userdata('depo_id') . "' ", 'left')
				// ->join('approval as apv2', "apv2.approval_setting_id = apv.approval_setting_id AND apv2.approval_status = 'In progress' AND apv2.depo_id = '" . $this->session->userdata('depo_id') . "' AND apv2.approval_is_direct_spv = 1 AND apv2.approval_karyawan_id = '" . $this->session->userdata('karyawan_id') . "'", 'left')
				->join('dbo.getjenisapproval() as gja', "gja.jenis = apv.approval_setting_jenis", "left")
				// ->where('anggaran_detail.anggaran_detail_kode', $kode)
				->where('apv.depo_id',  $this->session->userdata('depo_id'))
				->where('apv.approval_setting_is_aktif',  '1')
				->group_by('apv.approval_setting_id')
				->group_by('apv.approval_setting_keterangan')
				->group_by('gja.jenis_' . $Bahasa)
				//->group_by('apv.approval_setting_jenis')
				->order_by('COUNT(apv1.approval_id)', 'desc')
				->order_by('approval_setting_jenis', 'asc')
				->get();
		} else {

			// $data = $this->db->select("apv.approval_setting_id,apv.approval_setting_keterangan,apv.approval_setting_jenis,(COUNT(apv1.approval_id)+COUNT(apv2.approval_id)) as jumlah")
			$data = $this->db->select("	apv.approval_setting_id, apv.approval_setting_keterangan, 
									gja.jenis_" . $Bahasa . " as approval_setting_jenis,
									COUNT(apv1.approval_id) as jumlah")
				->from('approval_setting apv')
				->join('approval as apv1', "apv1.approval_setting_id = apv.approval_setting_id AND apv1.approval_status = 'In progress' AND apv1.depo_id = '" . $this->session->userdata('depo_id') . "' AND apv1.approval_is_direct_spv = 0 AND apv1.approval_level_id = '" . $this->session->userdata('karyawan_level_id') . "' AND apv1.approval_divisi_id = '" . $this->session->userdata('karyawan_divisi_id') . "' AND apv1.approval_group_id in ($list)", 'left')
				->join('approval as apv2', "apv2.approval_setting_id = apv.approval_setting_id AND apv2.approval_status = 'In progress' AND apv2.depo_id = '" . $this->session->userdata('depo_id') . "' AND apv2.approval_is_direct_spv = 1 AND apv2.approval_karyawan_id = '" . $this->session->userdata('karyawan_id') . "'", 'left')
				->join('dbo.getjenisapproval() as gja', "gja.jenis = apv.approval_setting_jenis", "left")
				// ->where('anggaran_detail.anggaran_detail_kode', $kode)
				->where('apv.depo_id',  $this->session->userdata('depo_id'))
				->where('apv.approval_setting_is_aktif',  '1')
				->group_by('apv.approval_setting_id')
				->group_by('apv.approval_setting_keterangan')
				->group_by('gja.jenis_' . $Bahasa)
				//->group_by('apv.approval_setting_jenis')
				->order_by('COUNT(apv1.approval_id)', 'desc')
				->order_by('approval_setting_jenis', 'asc')
				->get();
		}
		if ($data->num_rows() == 0) {
			$data = 0;
		} else {
			$data = $data->result_array();
		}

		// return $this->db->last_query();
		return $data;
	}

	public function getHistoryApproval($approval_reff_dokumen_kode)
	{
		$approval_status = array('In Progress', 'Pending');

		$data = $this->db->select("approval_id,FORMAT(approval_tanggal, 'dd-MM-yyyy') as tgl,approval_reff_dokumen_jenis,approval_reff_dokumen_kode,approval_status,kry.karyawan_nama,approval_keterangan, approval_group.approval_group_nama ")
			->from('approval')
			->join('karyawan as kry', "kry.karyawan_id = approval.approval_karyawan_id", 'left')
			->join('approval_group_detail', "approval_group_detail.karyawan_id = approval.approval_karyawan_id AND approval.approval_group_id = approval_group_detail.approval_group_id", 'left')
			->join('approval_group', "approval_group.approval_group_id = approval_group_detail.approval_group_id", 'left')
			->where('approval.approval_reff_dokumen_kode', $approval_reff_dokumen_kode)
			// ->where("approval.approval_status != 'In Progress'")
			// ->where("approval.approval_status != 'Pending'")
			->where_not_in("approval.approval_status", $approval_status)
			->order_by('approval_tanggal', 'DESC')
			->get();
		return $data->result_array();
	}

	public function getApprovalByDocumentId($dokumen_id)
	{
		$data = $this->db->select("approval_id,ISNULL(FORMAT(approval_tanggal, 'dd-MM-yyyy'),'') as tgl,approval_reff_dokumen_jenis,approval_reff_dokumen_kode,approval_status,ISNULL(kry.karyawan_nama,'') AS karyawan_nama,ISNULL(approval_keterangan,'') AS approval_keterangan")
			->from('approval')
			->join('karyawan as kry', "kry.karyawan_id = approval.approval_karyawan_id", 'left')
			->where('approval.approval_reff_dokumen_id', $dokumen_id)
			->order_by('approval_tanggal', 'DESC')
			->get();
		return $data->result_array();
		// return $this->db->last_query();
	}

	public function getApprovalByApprovalSettingId($approval_setting_id)
	{
		$depo_id = $this->session->userdata('depo_id');
		$apprSetId = $approval_setting_id;

		$pengguna_grup_id = $this->session->userdata('pengguna_grup_id');
		$is_dewa = $this->db->query("select * from pengguna_grup where pengguna_grup_id ='$pengguna_grup_id' and pengguna_grup_is_dewa =1")->num_rows();

		if ($is_dewa > 0) {
			$query = "SELECT 
					approval_id,
					approval_pengajuan_id,
					approval_reff_dokumen_id,
					approval_reff_dokumen_kode,
					approval_reff_dokumen_jenis,
					dd.depo_detail_id as depo_detail_id_asal,
					dd.depo_detail_nama as gudang_asal,
					a.karyawan_nama as diajukan_oleh,
					aps.approval_setting_reff_url as url_doc,
					FORMAT(approval_tanggal_create, 'dd-MM-yyyy') as tgl 
				FROM approval
						left join (
							select pengguna_id as karyawan_id, karyawan_nama, 'tabelpengguna' as keterangan from pengguna a inner join karyawan b on a.karyawan_id = b.karyawan_id
							union
							select karyawan_id, karyawan_nama, 'tabelkaryawan' as keterangan from karyawan
					      ) a on a.karyawan_id = approval.approval_karyawan_create_id
				LEFT JOIN approval_setting aps ON approval.approval_setting_id = aps.approval_setting_id
				LEFT JOIN tr_konversi_sku as tks ON approval.approval_reff_dokumen_id = tks.tr_konversi_sku_id
				LEFT JOIN depo_detail as dd ON tks.depo_detail_id = dd.depo_detail_id
				WHERE approval_status = 'In progress' AND approval.approval_setting_id ='" . $apprSetId . "' 
					AND approval.depo_id = '" . $this->session->userdata('depo_id') . "' 
					AND approval_is_direct_spv = 0 
					--AND approval_level_id = '" . $this->session->userdata('karyawan_level_id') . "' AND approval_divisi_id = '" . $this->session->userdata('karyawan_divisi_id') . "'
				
				UNION
				SELECT
					approval_id,
					approval_pengajuan_id,
					approval_reff_dokumen_id,
					approval_reff_dokumen_kode,
					approval_reff_dokumen_jenis,
					dd.depo_detail_id as depo_detail_id_asal,
					dd.depo_detail_nama as gudang_asal,
					a.karyawan_nama as diajukan_oleh,
					aps.approval_setting_reff_url as url_doc,
					FORMAT(approval_tanggal_create, 'dd-MM-yyyy') as tgl 
				FROM approval
						left join (
							select pengguna_id as karyawan_id, karyawan_nama, 'tabelpengguna' as keterangan from pengguna a inner join karyawan b on a.karyawan_id = b.karyawan_id
							union
							select karyawan_id, karyawan_nama, 'tabelkaryawan' as keterangan from karyawan
					      ) a on a.karyawan_id = approval.approval_karyawan_create_id
				LEFT JOIN approval_setting aps ON approval.approval_setting_id = aps.approval_setting_id
				LEFT JOIN tr_konversi_sku as tks ON approval.approval_reff_dokumen_id = tks.tr_konversi_sku_id
				LEFT JOIN depo_detail as dd ON tks.depo_detail_id = dd.depo_detail_id
				WHERE approval_status = 'In progress' AND approval.approval_setting_id ='" . $apprSetId . "' 
					AND approval.depo_id = '" . $this->session->userdata('depo_id') . "' 
					AND approval_is_direct_spv = 1 AND approval_karyawan_id = '" . $this->session->userdata('karyawan_id') . "'
				ORDER BY FORMAT(approval_tanggal_create, 'dd-MM-yyyy') DESC
		";
		} else {
			$query = "SELECT 
					approval_id,
					approval_pengajuan_id,
					approval_reff_dokumen_id,
					approval_reff_dokumen_kode,
					approval_reff_dokumen_jenis,
					a.karyawan_nama as diajukan_oleh,
					aps.approval_setting_reff_url as url_doc,
					FORMAT(approval_tanggal_create, 'dd-MM-yyyy') as tgl 
				FROM approval
						left join (
							select pengguna_id as karyawan_id, karyawan_nama, 'tabelpengguna' as keterangan from pengguna a inner join karyawan b on a.karyawan_id = b.karyawan_id
							union
							select karyawan_id, karyawan_nama, 'tabelkaryawan' as keterangan from karyawan
					      ) a on a.karyawan_id = approval.approval_karyawan_create_id
				LEFT JOIN approval_setting aps ON approval.approval_setting_id = aps.approval_setting_id
				WHERE approval_status = 'In progress' AND approval.approval_setting_id ='" . $apprSetId . "' 
					AND approval.depo_id = '" . $this->session->userdata('depo_id') . "' 
					AND approval_is_direct_spv = 0 
					AND approval_level_id = '" . $this->session->userdata('karyawan_level_id') . "' AND approval_divisi_id = '" . $this->session->userdata('karyawan_divisi_id') . "'
					
				UNION
				SELECT
					approval_id,
					approval_pengajuan_id,
					approval_reff_dokumen_id,
					approval_reff_dokumen_kode,
					approval_reff_dokumen_jenis,
					a.karyawan_nama as diajukan_oleh,
					aps.approval_setting_reff_url as url_doc,
					FORMAT(approval_tanggal_create, 'dd-MM-yyyy') as tgl 
				FROM approval
						left join (
							select pengguna_id as karyawan_id, karyawan_nama, 'tabelpengguna' as keterangan from pengguna a inner join karyawan b on a.karyawan_id = b.karyawan_id
							union
							select karyawan_id, karyawan_nama, 'tabelkaryawan' as keterangan from karyawan
					      ) a on a.karyawan_id = approval.approval_karyawan_create_id
				LEFT JOIN approval_setting aps ON approval.approval_setting_id = aps.approval_setting_id
				WHERE approval_status = 'In progress' AND approval.approval_setting_id ='" . $apprSetId . "' 
					AND approval.depo_id = '" . $this->session->userdata('depo_id') . "' 
					AND approval_is_direct_spv = 1 AND approval_karyawan_id = '" . $this->session->userdata('karyawan_id') . "'
							ORDER BY FORMAT(approval_tanggal_create, 'dd-MM-yyyy') DESC
		";
		}

		// $query = "SELECT 
		// 			approval_id,
		// 			approval_pengajuan_id,
		// 			approval_reff_dokumen_id,
		// 			approval_reff_dokumen_kode,
		// 			approval_reff_dokumen_jenis,
		// 			kry.karyawan_nama as diajukan_oleh,
		// 			aps.approval_setting_reff_url as url_doc,
		// 			FORMAT(approval_tanggal_create, 'dd-MM-yyyy') as tgl 
		// 		FROM approval
		// 		LEFT JOIN karyawan kry ON approval.approval_karyawan_create_id = kry.karyawan_id
		// 		LEFT JOIN approval_setting aps ON approval.approval_setting_id = aps.approval_setting_id
		// 		WHERE approval_status = 'In progress' AND approval.approval_setting_id ='" . $approval_setting_id . "' 
		// 			AND approval.depo_id = '" . $this->session->userdata('depo_id') . "' 
		// 			AND approval_is_direct_spv = 0 AND approval_level_id = 'E89ADD55-2ECE-4308-93CF-2406F8AF8F50' AND approval_divisi_id = 'E4F4AD27-6823-4026-BDDC-A977700B8ADA'

		// 		UNION
		// 		SELECT
		// 			approval_id,
		// 			approval_pengajuan_id,
		// 			approval_reff_dokumen_id,
		// 			approval_reff_dokumen_kode,
		// 			approval_reff_dokumen_jenis,
		// 			kry.karyawan_nama as diajukan_oleh,
		// 			aps.approval_setting_reff_url as url_doc,
		// 			FORMAT(approval_tanggal_create, 'dd-MM-yyyy') as tgl 
		// 		FROM approval
		// 		LEFT JOIN karyawan kry ON approval.approval_karyawan_create_id = kry.karyawan_id
		// 		LEFT JOIN approval_setting aps ON approval.approval_setting_id = aps.approval_setting_id
		// 		WHERE approval_status = 'In progress' AND approval.approval_setting_id ='" . $approval_setting_id . "' 
		// 			AND approval.depo_id = '" . $this->session->userdata('depo_id') . "' 
		// 			AND approval_is_direct_spv = 1 AND approval_karyawan_id = '4D607153-10D0-4D21-8B66-9F744FFF754B'
		// ";
		// return $query;
		$data = $this->db->query($query);

		return $data->result_array();
	}

	public function getApprovalSettingByApprovalSettingId($approval_setting_id)
	{
		$this->db->select("approval_setting_parameter")
			->from("approval_setting")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("approval_setting_id", $approval_setting_id);

		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetGudang()
	{

		$this->db->select("*")
			->from("depo_detail")
			->where("depo_id", $this->session->userdata('depo_id'))
			->order_by("depo_detail_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetChecker()
	{
		$this->db->select("karyawan_id, karyawan_nama")
			->from("karyawan")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("karyawan_divisi_id", "E4F4AD27-6823-4026-BDDC-A977700B8ADA")
			->where("karyawan_is_deleted", 0)
			->where("karyawan_is_aktif", 1)
			->order_by("karyawan_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function updateTrKonversiSKU($tr_konversi_sku_id, $gudang, $checker)
	{
		$this->db->set('depo_detail_id_tujuan', $gudang);
		$this->db->set('karyawan_id', $checker);

		$this->db->where('tr_konversi_sku_id', $tr_konversi_sku_id);

		$this->db->update('tr_konversi_sku');

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}
}
