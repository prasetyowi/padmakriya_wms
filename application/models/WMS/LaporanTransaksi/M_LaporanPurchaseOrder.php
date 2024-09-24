<?php

class M_LaporanPurchaseOrder extends CI_Model
{
	function __construct()
	{
		parent::__construct();
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

	public function Get_Gudang()
	{
		$query = $this->db->query("SELECT
										*
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY depo_detail_nama ASC");


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
	public function count_all_data($tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{
		if ($client_wms_id == '') {
			$client_wms = "";
		} else {
			$client_wms = "AND do.client_wms_id = '$client_wms_id' ";
		}
		if ($depo_detail_id == '') {
			$depo_detail = "";
		} else {
			$depo_detail = "and dob.depo_detail_id ='$depo_detail_id' ";
		}
		if ($principle_id == '') {
			$principle = "";
		} else {
			$principle = "and do.principle_id = '$principle_id' ";
		}
		if ($sku_kode == '') {
			$skukode = "";
		} else {
			$skukode = "and dod1.sku_kode like '%$sku_kode%' ";
		}
		if ($sku_nama == '') {
			$skunama = "";
		} else {
			$skunama = "and dod1.sku_nama_produk like '%$sku_nama%' ";
		}
		$depo_id = $this->session->userdata("depo_id");
		$query = $this->db->query("SELECT COUNT(do.delivery_order_id)as hasil
								from delivery_order do
									LEFT JOIN delivery_order_detail dod1 on do.delivery_order_id = dod1.delivery_order_id
									LEFT JOIN delivery_order_detail2 dod2 on dod1.delivery_order_id = dod2.delivery_order_id and dod1.sku_id = dod2.sku_id and dod1.delivery_order_detail_id = dod2.delivery_order_detail_id
									LEFT JOIN 
										(select so.sales_order_kode,so.sales_order_id, sod.sku_qty,so.sales_order_no_po,so.sales_order_tgl,sod.sku_harga_nett,sod.sku_id
										from sales_order so
										left join sales_order_detail sod on sod.sales_order_id = so.sales_order_id 
										where so.depo_id ='$depo_id')
										so on so.sales_order_id = do.sales_order_id and so.sku_id = dod1.sku_id
									LEFT JOIN reason r on r.reason_id = do.delivery_order_reject_reason
									left join depo on depo.depo_id = do.depo_id
										where do.depo_id = '$depo_id' AND
										FORMAT(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'
										$client_wms
										$depo_detail
										$principle
										$skukode
										$skunama")->row();
		return $query->hasil;
	}

	public function GetDetail($tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{
		if ($client_wms_id == '') {
			$client_wms = "";
		} else {
			$client_wms = "AND do.client_wms_id = '$client_wms_id' ";
		}
		if ($depo_detail_id == '') {
			$depo_detail = "";
		} else {
			$depo_detail = "and dob.depo_detail_id ='$depo_detail_id' ";
		}
		if ($principle_id == '') {
			$principle = "";
		} else {
			$principle = "and do.principle_id = '$principle_id' ";
		}
		if ($sku_kode == '') {
			$skukode = "";
		} else {
			$skukode = "and dod1.sku_kode like '%$sku_kode%' ";
		}
		if ($sku_nama == '') {
			$skunama = "";
		} else {
			$skunama = "and dod1.sku_nama_produk like '%$sku_nama%' ";
		}
		$depo_id = $this->session->userdata("depo_id");
		$query = $this->db->query("SELECT
									FORMAT(so.sales_order_tgl,'yyyy-MM-dd') as tgl_so,
									so.sales_order_kode as no_so,
									FORMAT(do.delivery_order_tgl_buat_do,'yyyy-MM-dd') as tgl_do,
									FORMAT(do.delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') as tgl_terkirim,
									do.delivery_order_kode as no_do,
									isnull(so.sales_order_no_po,'') as no_so_eksternal,
									do.delivery_order_kirim_nama as nama_outlet,
									do.delivery_order_kirim_alamat as alamat_outlet,
									do.delivery_order_kirim_area as area,
									depo.depo_nama,
									do.client_wms_id,
									dod1.sku_nama_produk,
									dod1.sku_kode,
									dod1.sku_qty,
									dod1.sku_qty_kirim,
									dod1.sku_harga_nett as harga,
									do.delivery_order_status,
									isnull(r.reason_keterangan,'') as reason,
									do.principle_id,
									so.sku_qty as qty_so,
									so.sku_harga_nett,
									dod2.sku_expdate,
									do.delivery_order_status
									
									
								from delivery_order do
									LEFT JOIN delivery_order_detail dod1 on do.delivery_order_id = dod1.delivery_order_id
									LEFT JOIN delivery_order_detail2 dod2 on dod1.delivery_order_id = dod2.delivery_order_id and dod1.sku_id = dod2.sku_id and dod1.delivery_order_detail_id = dod2.delivery_order_detail_id
									LEFT JOIN 
										(select so.sales_order_kode,so.sales_order_id, sod.sku_qty,so.sales_order_no_po,so.sales_order_tgl,sod.sku_harga_nett,sod.sku_id
										from sales_order so
										left join sales_order_detail sod on sod.sales_order_id = so.sales_order_id 
										where so.depo_id ='$depo_id')
										so on so.sales_order_id = do.sales_order_id and so.sku_id = dod1.sku_id
									LEFT JOIN reason r on r.reason_id = do.delivery_order_reject_reason
									left join depo on depo.depo_id = do.depo_id
										where do.depo_id = '$depo_id'	
										$client_wms
										$depo_detail
										$principle
										$skukode
										$skunama
										
								group by 
									do.delivery_order_tgl_buat_do,
									do.client_wms_id,depo.depo_nama,
									FORMAT(do.delivery_order_tgl_buat_do,'yyyy-MM-dd'),
									do.delivery_order_tgl_aktual_kirim	,
									do.delivery_order_kode,
									so.sales_order_no_po,
									so.sales_order_tgl,
									so.sales_order_kode,
									do.delivery_order_kirim_nama ,
									do.delivery_order_kirim_alamat ,
									do.delivery_order_kirim_area ,
									dod1.sku_nama_produk,
									dod1.sku_kode,
									dod1.sku_qty,
									dod1.sku_qty_kirim,
									dod1.sku_harga_nett,
									do.delivery_order_status,
									r.reason_keterangan,
									do.principle_id,
									so.sku_qty ,
									so.sku_harga_nett,
									dod2.sku_expdate,
									do.delivery_order_status
								
									order by do.delivery_order_kode DESC, do.delivery_order_tgl_buat_do

		");
		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}
	public function GetDetailNew($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama)
	{

		$depo_id = $this->session->userdata("depo_id");

		$this->db->select("FORMAT(so.sales_order_tgl, 'yyyy-MM-dd') as tgl_so,
						so.sales_order_kode as no_so,
						FORMAT(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') as tgl_do,
						FORMAT(do.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') as tgl_terkirim,
						do.delivery_order_kode as no_do,
						ISNULL(so.sales_order_no_po, '') as no_so_eksternal,
						do.delivery_order_kirim_nama as nama_outlet,
						do.delivery_order_kirim_alamat as alamat_outlet,
						do.delivery_order_kirim_area as area,
						depo.depo_nama as depo_nama,
						do.client_wms_id,
						dod1.sku_nama_produk as sku_nama_produk,
						dod1.sku_kode as sku_kode,
						dod1.sku_qty as sku_qty,
						dod1.sku_qty_kirim as sku_qty_kirim, 
						dod1.sku_harga_nett as harga,
						do.delivery_order_status as delivery_order_status,
						ISNULL(r.reason_keterangan, '') as reason,
						do.principle_id,
						so.sku_qty as qty_so,
						so.sku_harga_nett as sku_harga_nett,
						dod2.sku_expdate as sku_expdate
					");
		$this->db->from('delivery_order do');
		$this->db->join('delivery_order_detail dod1', 'do.delivery_order_id = dod1.delivery_order_id', 'LEFT');
		$this->db->join('delivery_order_detail2 dod2', 'dod1.delivery_order_id = dod2.delivery_order_id AND dod1.sku_id = dod2.sku_id AND dod1.delivery_order_detail_id = dod2.delivery_order_detail_id', 'LEFT');
		$this->db->join('(SELECT so.sales_order_kode, so.sales_order_id, sod.sku_qty, so.sales_order_no_po, so.sales_order_tgl, sod.sku_harga_nett, sod.sku_id FROM sales_order so LEFT JOIN sales_order_detail sod ON sod.sales_order_id = so.sales_order_id) so', 'so.sales_order_id = do.sales_order_id AND so.sku_id = dod1.sku_id', 'LEFT');
		$this->db->join('reason r', 'r.reason_id = do.delivery_order_reject_reason', 'LEFT');
		$this->db->join('depo', 'depo.depo_id = do.depo_id', 'LEFT');
		$this->db->where('do.depo_id', $depo_id);
		$this->db->where("FORMAT(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'");

		if ($client_wms_id == '') {
			// $this->db->where('');
		} else {
			$this->db->where('do.client_wms_id', $client_wms_id);
		}

		if ($depo_detail_id == '') {
			// $this->db->where('');
		} else {
			$this->db->where('dob.depo_detail_id', $depo_detail_id);
		}

		if ($principle_id == '') {
			// $this->db->where('');
		} else {
			$this->db->where('do.principle_id', $principle_id);
		}

		if ($sku_kode == '') {
			// $this->db->where('');
		} else {
			$this->db->like('dod1.sku_kode', $sku_kode);
		}

		if ($sku_nama == '') {
			// $this->db->where('');
		} else {
			$this->db->like('dod1.sku_nama_produk', $sku_nama);
		}
		$this->db->group_by("
						do.delivery_order_tgl_buat_do,
						do.client_wms_id,
						depo.depo_nama,
						FORMAT(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd'),
						do.delivery_order_tgl_aktual_kirim,
						do.delivery_order_kode,
						so.sales_order_no_po,
						so.sales_order_tgl,
						so.sales_order_kode,
						do.delivery_order_kirim_nama,
						do.delivery_order_kirim_alamat,
						do.delivery_order_kirim_area,
						dod1.sku_nama_produk,
						dod1.sku_kode,
						dod1.sku_qty,
						dod1.sku_qty_kirim,
						dod1.sku_harga_nett,
						do.delivery_order_status,
						r.reason_keterangan,
						do.principle_id,
						so.sku_qty,
						so.sku_harga_nett,
						dod2.sku_expdate
					");
		$this->db->order_by('do.delivery_order_kode, do.delivery_order_tgl_buat_do', 'ASC');
		$sortable_columns = array(
			// 'penerimaan_pembelian_id',
			'tgl_so',
			'no_so',
			'tgl_do',
			'tgl_terkirim',
			'no_do',
			'no_so_eksternal',
			'nama_outlet',
			'alamat_outlet',
			'area',
			'sku_nama_produk',
			'sku_kode',
			'sku_qty',
			'sku_qty_kirim',
			'harga',
			'delivery_order_status',
			'reason',
			'qty_so',
			'sku_harga_nett',
			'sku_expdate',
		);

		// Kolom yang dapat dicari
		$searchable_columns = array(
			// 'tgl_so',
			'no_so',
			// 'tgl_do',/
			// 'tgl_terkirim',
			'no_do',
			'no_so_eksternal',
			'nama_outlet',
			'alamat_outlet',
			'area',
			'sku_nama_produk',
			'sku_kode',
			// 'sku_qty',
			// 'sku_qty_kirim',
			// 'harga',
			'delivery_order_status',
			// 'reason',
			'qty_so',
			'sku_harga_nett',
			'sku_expdate',
		);

		// Cari
		if (!empty($search_value)) {
			$this->db->group_start();
			foreach ($searchable_columns as $column) {
				$this->db->or_like($column, $search_value);
			}
			$this->db->group_end();
		}

		// Urutkan
		if ($order_column <= count($sortable_columns)) {
			$this->db->order_by($sortable_columns[$order_column], $order_dir);
		}

		$this->db->limit($length, $start);
		return $this->db->get()->result_array();
	}


	public function GetDetailNewProcedure($start, $length, $search_value, $order_column, $order_dir, $tgl1, $tgl2, $client_wms_id, $depo_id, $depo_detail_id, $principle_id, $sku_kode, $sku_nama, $tgl_1, $tgl_2)
	{
		$start = $start == '' ? 0 : $start;
		$length = $length == '' ? 0 : $length;
		// $search_value = $search_value == '' ? NULL : $search_value;
		$order_column = $order_column == '' ? 0 : $order_column;
		$order_dir = $order_dir == '' ? NULL : $order_dir;
		$tgl1 = $tgl1 == '' ? NULL : $tgl1;
		$tgl2 = $tgl2 == '' ? NULL : $tgl2;
		$client_wms_id = $client_wms_id == '' ? NULL : $client_wms_id;
		$depo_id = $depo_id == '' ? NULL : $depo_id;
		$depo_detail_id = $depo_detail_id == '' ? NULL : $depo_detail_id;
		$principle_id = $principle_id == '' ? NULL : $principle_id;
		$sku_kode = $sku_kode == '' ? NULL : $sku_kode;
		$sku_nama = $sku_nama == '' ? NULL : $sku_nama;
		$tgl_1 = $tgl_1 == '' ? NULL : $tgl_1;
		$tgl_2 = $tgl_2 == '' ? NULL : $tgl_2;
		$query = $this->db->query("exec report_transaksi_purchase_order $start,$length,$order_column,'$order_dir','$tgl_1','$tgl_2','$client_wms_id','$depo_id','$depo_detail_id','$principle_id','$sku_kode','$sku_nama','$tgl1','$tgl2'");

		if ($query->num_rows() == 0) {
			return [];
		} else {
			return $query->result_array();
		}
	}
}
