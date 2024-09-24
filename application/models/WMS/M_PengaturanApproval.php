<?php

class M_PengaturanApproval extends CI_Model
{
	public function getDepoPrefix($depo_id)
	{
		$prefix = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $prefix->row();
	}
	public function getListMenuWMS()
	{
		$query = $this->db->select("menu_name,menu_id,menu_kode")->from("menu_web")->where("menu_application", 'WMS')->where("menu_parent IS NOT NULL")->get();
		// }
		// return $arrAreaId;
		return $query->result_array();
	}
	public function getListJenisApproval()
	{
		$query = $this->db->select("*")->from("getjenisapproval()")->get();
		// }
		// return $arrAreaId;
		return $query->result_array();
	}
	public function getListKaryawanDivisi()
	{
		$query = $this->db->select("*")->from("karyawan_divisi")->where("karyawan_divisi_is_aktif", 1)->get();
		// }
		// return $arrAreaId;
		return $query->result_array();
	}
	public function getListKaryawanLevel()
	{
		$query = $this->db->select("*")->from("karyawan_level")->where("karyawan_level_is_aktif", 1)->get();
		// }
		// return $arrAreaId;
		return $query->result_array();
	}
	public function getListKaryawanLevelByDivisi($divisi_id)
	{
		$query = $this->db->select("*")->from("karyawan_level")->where("karyawan_level_is_aktif", 1)->where('karyawan_divisi_id', $divisi_id)->get();
		// }
		// return $arrAreaId;
		return $query->result_array();
	}
	public function CheckParameterTersimpan($parameter)
	{
		$query = $this->db->select("COUNT(approval_setting_parameter) as is_duplikat")->from("approval_setting")->where("approval_setting_parameter", $parameter)->where('depo_id',  $this->session->userdata('depo_id'))->get();
		// }
		// return $arrAreaId;
		return $query->row();
	}

	public function getDataSearch()
	{

		$data = $this->db->select("
					approval_setting.*,
					FORMAT(approval_setting.approval_setting_tgl_create, 'yyyy-MM-dd') AS tgl
				")
			->from('approval_setting')
			->where('depo_id',  $this->session->userdata('depo_id'))
			->order_by('approval_setting.approval_setting_jenis', 'ASC')
			->get();

		return $data->result_array();
	}

	public function getPengaturanApprovalById($id)
	{
		$query = $this->db->select("
				approval_setting.*,
				web.menu_name
			")
			->from('approval_setting')
			->join("menu_web web", "web.menu_id = approval_setting.menu_web_id", "left")
			->where("approval_setting_id", $id)
			->where('depo_id',  $this->session->userdata('depo_id'))
			->get();
		return $query->row();
	}
	public function getPengaturanApprovalDetailById($id)
	{
		$query = $this->db->select("
				dtl.*, c.approval_group_nama,c.approval_group_id
			")
			->from('approval_setting_detail dtl')
			->join('approval_group c', 'c.approval_group_id = dtl.approval_group_id', 'left')

			// ->join('karyawan_level', 'dtl.karyawan_level_id = karyawan_level.karyawan_level_id', 'left')
			// ->join('karyawan_divisi', 'dtl.karyawan_divisi_id = karyawan_divisi.karyawan_divisi_id', 'left')
			->where("dtl.approval_setting_id", $id)
			->order_by('dtl.approval_setting_detail_no_urut', 'ASC')

			->get();
		return $query->result_array();
	}

	public function SavePengaturanApproval(
		$is_paralel,
		$parameter,
		$menu_id,
		$menu_kode,
		$jenis,
		$keterangan,
		$reff_url,
		$status,
		$dataDetail
	) {
		$this->db->trans_start();
		$newID1 = $this->M_Function->Get_NewID();
		$newID = $newID1[0]['kode'];


		$this->db->set('approval_setting_id', $newID);
		$this->db->set('menu_web_id', $menu_id);
		$this->db->set('menu_web_kode', $menu_kode);
		$this->db->set('approval_setting_reff_url', $reff_url);
		$this->db->set('approval_setting_jenis', $jenis);
		$this->db->set('approval_setting_keterangan', $keterangan);
		$this->db->set('approval_setting_is_parallel', $is_paralel);
		$this->db->set('approval_setting_is_aktif', $status);
		$this->db->set('depo_id', $this->session->userdata('depo_id'));
		$this->db->set('approval_setting_parameter', $parameter);
		$this->db->set('approval_setting_tgl_create', "GETDATE()", FALSE);
		$this->db->set('approval_setting_who_create', $this->session->userdata('pengguna_username'));
		$this->db->insert('approval_setting');

		foreach ($dataDetail as $key => $value) {
			$this->db->set('approval_setting_id', $newID);
			$this->db->set('approval_setting_detail_id', 'NEWID()', FALSE);
			$this->db->set('approval_setting_detail_no_urut', $key + 1);
			$this->db->set('approval_setting_detail_is_direct_spv', $value['is_direct_supervisor']);
			// $this->db->set('karyawan_divisi_id', $value['divisi'] == '' ? null : $value['divisi']);
			// $this->db->set('karyawan_level_id', $value['level'] == '' ? null : $value['level']);
			$this->db->set('approval_group_id', $value['group'] == '' ? null : $value['group']);
			$this->db->set('approval_setting_detail_min_nilai', $value['min_nilai']);
			$this->db->set('approval_setting_detail_max_nilai', $value['max_nilai']);
			$this->db->insert('approval_setting_detail');
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			// return $this->db->error();
			$this->db->trans_rollback();
			return 0;
		}
		// $this->db->trans_commit();

		return 1;
	}

	public function UpdatePengaturanApproval(
		$approval_setting_id,
		$is_paralel,
		$parameter,
		$menu_id,
		$menu_kode,
		$jenis,
		$keterangan,
		$reff_url,
		$status,
		$dataDetail
	) {
		$this->db->trans_start();

		$this->db->set('menu_web_id', $menu_id);
		$this->db->set('menu_web_kode', $menu_kode);
		$this->db->set('approval_setting_reff_url', $reff_url);
		$this->db->set('approval_setting_jenis', $jenis);
		$this->db->set('approval_setting_keterangan', $keterangan);
		$this->db->set('approval_setting_is_parallel', $is_paralel);
		$this->db->set('approval_setting_is_aktif', $status);
		$this->db->set('depo_id', $this->session->userdata('depo_id'));
		$this->db->set('approval_setting_parameter', $parameter);
		$this->db->set('approval_setting_tgl_create', "GETDATE()", FALSE);
		$this->db->where('approval_setting_id', $approval_setting_id);
		$this->db->update('approval_setting');

		$this->db->where('approval_setting_id', $approval_setting_id);
		$this->db->delete('approval_setting_detail');

		foreach ($dataDetail as $key => $value) {
			$this->db->set('approval_setting_id', $approval_setting_id);
			$this->db->set('approval_setting_detail_id', 'NEWID()', FALSE);
			$this->db->set('approval_setting_detail_no_urut', $key + 1);
			$this->db->set('approval_setting_detail_is_direct_spv', $value['is_direct_supervisor']);
			// $this->db->set('karyawan_divisi_id', $value['divisi'] == '' ? null : $value['divisi']);
			// $this->db->set('karyawan_level_id', $value['level'] == '' ? null : $value['level']);
			$this->db->set('approval_group_id', $value['group'] == '' ? null : $value['group']);
			$this->db->set('approval_setting_detail_min_nilai', $value['min_nilai']);
			$this->db->set('approval_setting_detail_max_nilai', $value['max_nilai']);
			$this->db->insert('approval_setting_detail');
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			// return $this->db->error();
			$this->db->trans_rollback();
			return 0;
		}
		// $this->db->trans_commit();

		return 1;
	}

	public function checkData()
	{
		$res = 0;
		$getParam = $this->db->query("SELECT vrbl_param as param from vrbl where left(vrbl_param,5) = 'APPRV'");
		if ($getParam->num_rows() == 0) {
			return [];
		} else {
			return $getParam->result();
		}
	}

	public function getData($array)
	{
		$quotedParams = array_map(function ($param) {
			return "'" . addslashes($param) . "'";
		}, $array);

		// Join the elements into a comma-separated string
		$paramString = implode(",", $quotedParams);
		$depo_id = $this->session->userdata('depo_id');
		$getParam = $this->db->query("SELECT distinct approval_setting_parameter from approval_setting where approval_setting_parameter in ($paramString) and depo_id ='$depo_id'");

		if ($getParam->num_rows() == 0) {
			return 0;
		} else {
			return $getParam->result();
		}
	}

	public function insertPengaturanApproval($value)
	{

		$this->db->set('approval_setting_id', 'newid()', false);
		$this->db->set('depo_id', $this->session->userdata('depo_id'));
		$this->db->set('approval_setting_is_aktif', 0);
		$this->db->set('approval_setting_tgl_create', 'getdate()', false);
		$this->db->set('approval_setting_who_create', $this->session->userdata('pengguna_username'));
		$this->db->set('approval_setting_parameter', $value);
		return $this->db->insert('approval_setting');
		// $this->db->trans_rollback();
	}

	public function getListNamaGroupApproval()
	{
		$depo_id = $this->session->userdata('depo_id');
		$query = $this->db->query("SELECT * from approval_group where depo_id = '$depo_id'");
		return $query->result();
	}

	public function getDataByApprovalGroupId($id)
	{
		$query = $this->db->query("SELECT c.karyawan_nama,b.approval_group_detail_no_urut,d.karyawan_divisi_nama from approval_group a
		LEFT JOIN approval_group_detail b on a.approval_group_id = b.approval_group_id
		LEFT JOIN karyawan c on c.karyawan_id = b.karyawan_id
		LEFT JOIN karyawan_divisi d on d.karyawan_divisi_id = c.karyawan_divisi_id
		where a.approval_group_id = '$id'");
		return $query->result_array();
	}
}
