<?php

class M_KoreksiKartuStok extends CI_Model
{
	public function GetDepoDetail()
	{
		$this->db->select("*")
			->from("depo_detail")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("depo_detail_flag_jual", 1)
			->order_by("depo_detail_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPrinnciple()
	{
		$this->db->select("*")
			->from("principle")
			->where("principle_is_aktif", 1)
			->order_by("principle_kode");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetSalesBosnet()
	{
		$query = $this->db->query("SELECT
									karyawan_eks.karyawan_id,
									karyawan_eks.sales_eksternal_id,
									karyawan.karyawan_nama
									FROM BACKEND.dbo.karyawan_sales_eksternal karyawan_eks
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = karyawan_eks.karyawan_id
									WHERE karyawan.depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY karyawan_eks.sales_eksternal_id ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetStatusProgress()
	{
		$this->db->select("*")
			->from("status_progress")
			->where("status_progress_modul", "Delivery Order")
			->order_by("status_progress_no");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetTipeDeliveryOrder()
	{
		$this->db->select("*")
			->from("tipe_delivery_order")
			->where("tipe_delivery_order_alias <>", "Mix")
			->order_by("tipe_delivery_order_alias");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetTipeLayanan()
	{
		$this->db->select("*")
			->from("tipe_layanan")
			->where("tipe_layanan_is_aktif", "1")
			->order_by("tipe_layanan_kode");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPerusahaan()
	{
		$pengguna_grup_id = $this->session->userdata('pengguna_grup_id');
		$is_dewa = $this->db->query("select * from pengguna_grup where pengguna_grup_id ='$pengguna_grup_id' and pengguna_grup_is_dewa =1")->num_rows();

		if ($is_dewa > 0) {

			// $client = "where client_wms_id IN (SELECT client_wms_id FROM karyawan_principle WHERE karyawan_id = '" . $this->session->userdata('karyawan_id') . "' GROUP BY client_wms_id)";
			$query = $this->db->query("SELECT * FROM client_wms 
									ORDER BY client_wms_nama ASC");
			if ($query->num_rows() == 0) {
				$query = 0;
			} else {
				$query = $query->result_array();
			}
		} else {

			if ($this->session->userdata('client_wms_id') == '') {
				$query = 0;
			} else {
				$client = "where client_wms_id= '" . $this->session->userdata('client_wms_id') . "'";
				$query = $this->db->query("SELECT * FROM client_wms 
								" . $client . "
									ORDER BY client_wms_nama ASC");
				if ($query->num_rows() == 0) {
					$query = 0;
				} else {
					$query = $query->result_array();
				}
			}
		}

		return $query;
	}
	public function GetPerusahaanById($id)
	{
		$this->db->select("*")
			->from("client_wms")
			->where("client_wms_id", $id)
			->order_by("client_wms_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
	public function getData($requestData = '')
	{
		$depo = $this->session->userdata('depo_id');
		$sql = "exec proses_maintenance_stock_card 0,'$depo'";
		// $params = array($requestData['start'], $requestData['length']);

		$query = $this->db->query($sql);
		return $query->result();
	}
	public function proses()
	{
		$depo = $this->session->userdata('depo_id');
		$sql = "exec proses_maintenance_stock_card 1,'$depo'";
		// $params = array($requestData['start'], $requestData['length']);

		$query = $this->db->query($sql);
		return true;
	}
}
