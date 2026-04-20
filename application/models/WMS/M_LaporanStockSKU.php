<?php

class M_LaporanStockSKU extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function GetSKU()
	{
		$query = $this->db->query("SELECT
										sku_id,sku_nama_produk
									FROM sku
									ORDER BY sku_kode ASC");
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Principle()
	{
		$query = $this->db->query("SELECT
										*
									FROM principle
									ORDER BY principle_kode ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
	public function Get_ClientWms()
	{
		$query = $this->db->query("SELECT
										*
									FROM client_wms
									-- WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY client_wms_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
	public function Get_RakLajur()
	{
		$query = $this->db->query("select rak_lajur_detail_id, rak_lajur_detail_nama from rak_lajur_detail
									ORDER BY rak_lajur_detail_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
	public function Get_Pallet()
	{
		$query = $this->db->query("select pallet_id, pallet_kode from pallet
									ORDER BY pallet_kode ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getDataBySku($text)
	{
		return $this->db->query("select sku_id,sku_kode,sku_nama_produk from sku where sku_nama_produk like '%$text%' or sku_kode like  '%$text%'")->result_array();
	}
	public function getDataDetailBySku($id)
	{
		$depo_id = $this->session->userdata('depo_id');
		return $this->db->query("select rlj.rak_lajur_detail_nama,p.pallet_kode, isnull(pd.sku_stock_qty,0)-isnull(pd.sku_stock_ambil,0)+isnull(pd.sku_stock_in,0)-isnull(pd.sku_stock_out,0)+isnull(pd.sku_stock_terima,0) as qtyHasil,
		isnull(pd.batch_no,'') as batch_no,
		pd.sku_stock_expired_date
			from rak_lajur_detail rlj
		inner join rak_lajur_detail_pallet rljp on rljp.rak_lajur_detail_id = rlj.rak_lajur_detail_id
		inner join pallet p on p.pallet_id = rljp.pallet_id
		inner join pallet_detail pd on pd.pallet_id = rljp.pallet_id
		where pd.sku_id = '$id' and p.depo_id = '$depo_id' ")->result_array();
	}
	public function GetDetailInformasiAll($principle, $id_pallet, $rak)
	{
		// $principle = $principle == '' ? '' : $principle;
		// $id_pallet = $id_pallet == '' ? '' : $id_pallet;
		// $rak = $rak == '' ? '' : $rak;
		if ($rak == '') {
			$w_rak = '';
		} else {
			$w_rak = "AND rlj.rak_lajur_detail_id ='$rak'";
		}
		if ($principle == '') {
			$w_principle = '';
		} else {
			$w_principle = "AND principle.principle_id  ='$principle'";
		}
		if ($id_pallet == '') {
			$w_pallet = '';
		} else {
			$w_pallet = "AND p.pallet_id  ='$id_pallet'";
		}
		$depo_id = $this->session->userdata('depo_id');
		return $this->db->query("select  
			principle.principle_nama,
			sku.sku_id,
			sku.sku_kode,
			sku.sku_nama_produk,
			pd.sku_stock_expired_date,
			rlj.rak_lajur_detail_nama,
			p.pallet_kode, isnull(pd.sku_stock_qty,0)-isnull(pd.sku_stock_ambil,0)+isnull(pd.sku_stock_in,0)-isnull(pd.sku_stock_out,0)+isnull(pd.sku_stock_terima,0) as qtyHasil,
			isnull(pd.batch_no,'') as batch_no,
			pd.sku_stock_expired_date
		from rak_lajur_detail rlj
			inner join rak_lajur_detail_pallet rljp on rljp.rak_lajur_detail_id = rlj.rak_lajur_detail_id
			inner join pallet p on p.pallet_id = rljp.pallet_id 
			inner join pallet_detail pd on pd.pallet_id = rljp.pallet_id
			inner join sku on sku.sku_id = pd.sku_id
			inner join principle on sku.principle_id = principle.principle_id
		where p.depo_id = '$depo_id' 
		$w_rak
		$w_principle
		$w_pallet
			order by principle.principle_nama, rak_lajur_detail_nama asc")->result_array();
	}
}
