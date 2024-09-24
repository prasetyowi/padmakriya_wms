<?php

class M_KomparasiStockPalletGlobal extends CI_Model
{
	public function GetDepoDetail()
	{
		$this->db->select("*")
			->from("depo_detail")
			->where("depo_id", $this->session->userdata('depo_id'))
			//->where("depo_detail_flag_jual", 1)
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
		$this->db->select("*")
			->from("client_wms")
			->order_by("client_wms_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
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
	public function getSegment()
	{
		return $this->db->query('select * from client_pt_segmen where client_pt_segmen_level =1')->result();
	}
	public function getSegment2($id)
	{

		return $this->db->query("select * from client_pt_segmen where client_pt_segmen_reff_id ='$id' and client_pt_segmen_level =2")->result();
	}
	public function getSegment3($id)
	{
		return $this->db->query("select * from client_pt_segmen where client_pt_segmen_reff_id='$id' and client_pt_segmen_level =3")->result();
	}

	public function Get_list_komparasi_lokasi_stock_pallet($depo_detail_id, $principle_id, $hasil, $alokasi)
	{
		if ($depo_detail_id == "") {
			$depo_detail_id = "NULL";
		} else {
			$depo_detail_id = "'" . $depo_detail_id . "'";
		}

		if ($principle_id == "") {
			$principle_id = "NULL";
		} else {
			$principle_id = "'" . $principle_id . "'";
		}

		$query = $this->db->query("exec proses_maintenance_stock_pallet_global '0', '" . $this->session->userdata('depo_id') . "', " . $depo_detail_id . ", " . $principle_id . ", '" . $hasil . "', '" . $alokasi . "'");

		// if ($sku_kode == "") {
		// 	$sku_kode = "";
		// } else {
		// 	$sku_kode = "AND c.sku_kode LIKE '%" . $sku_kode . "%' ";
		// }

		// if ($sku_nama_produk == "") {
		// 	$sku_nama_produk = "";
		// } else {
		// 	$sku_nama_produk = "AND c.sku_nama_produk LIKE '%" . $sku_nama_produk . "%' ";
		// }

		// $query = $this->db->query("SELECT
		// 							a.sku_stock_id,
		// 							a.sku_id,
		// 							d.principle_kode,
		// 							c.sku_kode,
		// 							c.sku_nama_produk,
		// 							a.sku_stock_expired_date,
		// 							ISNULL(a.sku_stock_awal, 0) + ISNULL(a.sku_stock_masuk, 0) - ISNULL(a.sku_stock_keluar, 0) AS qtysummary,
		// 							ISNULL(a.sku_stock_alokasi, 0) AS sku_stock_alokasi,
		// 							ISNULL(a.sku_stock_saldo_alokasi, 0) AS sku_stock_saldo_alokasi,
		// 							ISNULL(b.qty, 0) AS qtypallet,
		// 							CASE
		// 								WHEN ISNULL(a.sku_stock_awal, 0) + ISNULL(a.sku_stock_masuk, 0) - ISNULL(a.sku_stock_keluar, 0) <> ISNULL(b.qty, 0) THEN 'beda'
		// 								ELSE 'sama'
		// 							END AS hasil
		// 							FROM sku_stock a
		// 							LEFT JOIN (SELECT
		// 							SUM(ISNULL(sku_stock_qty, 0) - ISNULL(sku_stock_ambil, 0) + ISNULL(sku_stock_in, 0) - ISNULL(sku_stock_out, 0) + ISNULL(sku_stock_terima, 0)) AS qty,
		// 							sku_stock_id
		// 							FROM pallet_detail
		// 							GROUP BY sku_stock_id) b
		// 							ON a.sku_stock_id = b.sku_stock_id
		// 							INNER JOIN sku c
		// 							ON a.sku_id = c.sku_id
		// 							INNER JOIN principle d
		// 							ON c.principle_id = d.principle_id
		// 							WHERE ISNULL(a.sku_stock_awal, 0) + ISNULL(a.sku_stock_masuk, 0) - ISNULL(a.sku_stock_keluar, 0) <> ISNULL(b.qty, 0)
		// 							AND a.depo_id = '" . $this->session->userdata('depo_id') . "'
		// 							" . $depo_detail_id . "
		// 							" . $principle_id . "
		// 							" . $sku_kode . "
		// 							" . $sku_nama_produk . "
		// 							ORDER BY c.sku_kode");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_header_komparasi_lokasi_stock_pallet_by_sku_stock_id($sku_stock_id)
	{

		$query = $this->db->query("SELECT
									a.sku_stock_id,
									a.sku_id,
									d.principle_kode,
									c.sku_kode,
									c.sku_nama_produk,
									FORMAT(a.sku_stock_expired_date,'yyyy-MM-dd') as sku_stock_expired_date,
									ISNULL(a.sku_stock_awal, 0) + ISNULL(a.sku_stock_masuk, 0) - ISNULL(a.sku_stock_keluar, 0) AS qtysummary,
									ISNULL(a.sku_stock_alokasi, 0) AS sku_stock_alokasi,
									ISNULL(a.sku_stock_saldo_alokasi, 0) AS sku_stock_saldo_alokasi,
									ISNULL(b.qty, 0) AS qtypallet,
									CASE
										WHEN ISNULL(a.sku_stock_awal, 0) + ISNULL(a.sku_stock_masuk, 0) - ISNULL(a.sku_stock_keluar, 0) <> ISNULL(b.qty, 0) THEN 'beda'
										ELSE 'sama'
									END AS hasil
									FROM sku_stock a
									LEFT JOIN (SELECT
									SUM(ISNULL(sku_stock_qty, 0) - ISNULL(sku_stock_ambil, 0) + ISNULL(sku_stock_in, 0) - ISNULL(sku_stock_out, 0) + ISNULL(sku_stock_terima, 0)) AS qty,
									sku_stock_id
									FROM pallet_detail
									GROUP BY sku_stock_id) b
									ON a.sku_stock_id = b.sku_stock_id
									INNER JOIN sku c
									ON a.sku_id = c.sku_id
									INNER JOIN principle d
									ON c.principle_id = d.principle_id
									WHERE ISNULL(a.sku_stock_awal, 0) + ISNULL(a.sku_stock_masuk, 0) - ISNULL(a.sku_stock_keluar, 0) <> ISNULL(b.qty, 0)
									AND CONVERT(nvarchar(36), a.sku_stock_id) = '$sku_stock_id'");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_detail_komparasi_lokasi_stock_pallet_by_sku_stock_id($sku_stock_id)
	{

		$query = $this->db->query("SELECT
									CONVERT(nvarchar(36), pd.pallet_detail_id) AS pallet_detail_id,
									CONVERT(nvarchar(36), pd.pallet_id) AS pallet_id,
									p.pallet_kode,
									CONVERT(nvarchar(36), pd.sku_stock_id) AS sku_stock_id,
									ISNULL(pd.sku_stock_qty, 0) AS sku_stock_qty,
									ISNULL(pd.sku_stock_ambil, 0) AS sku_stock_ambil,
									ISNULL(pd.sku_stock_in, 0) AS sku_stock_in,
									ISNULL(pd.sku_stock_out, 0) AS sku_stock_out,
									ISNULL(pd.sku_stock_terima, 0) AS sku_stock_terima,
									ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_terima, 0) AS qty,
									p.pallet_is_lock AS is_lock,
									rld.rak_lajur_detail_nama AS lokasi
									FROM pallet p
									LEFT JOIN pallet_detail pd
									ON pd.pallet_id = p.pallet_id
									LEFT JOIN rak_lajur_detail_pallet rldp
									ON p.pallet_id = rldp.pallet_id
									LEFT JOIN rak_lajur_detail rld
									ON rldp.rak_lajur_detail_id = rld.rak_lajur_detail_id
									WHERE CONVERT(nvarchar(36), pd.sku_stock_id) = '$sku_stock_id'
									ORDER BY p.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function proses_maintenance_stock_pallet_global($depo_detail_id, $principle_id, $hasil, $alokasi)
	{
		if ($depo_detail_id == "") {
			$depo_detail_id = "NULL";
		} else {
			$depo_detail_id = "'" . $depo_detail_id . "'";
		}

		if ($principle_id == "") {
			$principle_id = "NULL";
		} else {
			$principle_id = "'" . $principle_id . "'";
		}

		$query = $this->db->query("exec proses_maintenance_stock_pallet_global '1','" . $this->session->userdata('depo_id') . "'," . $depo_detail_id . "," . $principle_id . ", '" . $hasil . "', '" . $alokasi . "'");

		return $query;
	}

	public function update_pallet_detail($pallet_detail_id, $pallet_id, $sku_stock_id, $sku_stock_qty, $sku_stock_ambil, $sku_stock_in, $sku_stock_out, $sku_stock_terima)
	{
		$pallet_id = $pallet_id == "" ? null : $pallet_id;
		$sku_stock_id = $sku_stock_id == "" ? null : $sku_stock_id;
		$sku_stock_qty = $sku_stock_qty == "" ? null : $sku_stock_qty;
		$sku_stock_ambil = $sku_stock_ambil == "" ? null : $sku_stock_ambil;
		$sku_stock_in = $sku_stock_in == "" ? null : $sku_stock_in;
		$sku_stock_out = $sku_stock_out == "" ? null : $sku_stock_out;
		$sku_stock_terima = $sku_stock_terima == "" ? null : $sku_stock_terima;

		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_stock_qty", $sku_stock_qty);
		$this->db->set("sku_stock_ambil", $sku_stock_ambil);
		$this->db->set("sku_stock_in", $sku_stock_in);
		$this->db->set("sku_stock_out", $sku_stock_out);
		$this->db->set("sku_stock_terima", $sku_stock_terima);

		$this->db->where("pallet_detail_id", $pallet_detail_id);

		$queryinsert = $this->db->update("pallet_detail");

		return $queryinsert;
		// return $this->db->last_query();
	}
}
