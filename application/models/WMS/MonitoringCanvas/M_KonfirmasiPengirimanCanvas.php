<?php

class M_KonfirmasiPengirimanCanvas extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->load->model(['M_AutoGen', 'M_Vrbl', 'M_Function']);
	}

	function getSales(): array
	{

		return $this->db->select('client_pt_id, client_pt_nama')->from('client_pt')
			->where('client_pt_segmen_id3', '96E77207-836B-40B1-ADA4-76E80F625B52')->get()->result();
	}

	public function getDataByFilter($dataPost)
	{
		$exlodeTanggal = explode(' - ', $dataPost->tanggal);

		$this->db->select("dob.delivery_order_batch_id as id_fdjr,
											 do.delivery_order_id,
											FORMAT(dob.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') as tanggal_fdjr,
											isnull(do.delivery_order_ambil_nama, '') as sales,
											tdo.tipe_delivery_order_alias as tipe_fdjr,
											dob.delivery_order_batch_status as status_fdjr,
											dob.delivery_order_batch_kode as kode_fdjr,
											pl.picking_list_kode as kode_pb,
											po.picking_order_kode as kode_ppb,
											stk.serah_terima_kirim_kode as kode_serah_terima,
											CASE WHEN dos.delivery_order_id IS NULL AND dob.delivery_order_batch_status = 'completed' THEN 'Settlement has not Started' WHEN dos.delivery_order_id IS NOT NULL AND dob.delivery_order_batch_status = 'completed' THEN 'Settlement Success' ELSE '' END AS status,
											pl.picking_list_id,
											stk.serah_terima_kirim_id,
											COUNT(DISTINCT pj.penerimaan_penjualan_id) AS jml_btb")
			->from('delivery_order_batch dob')
			->join('delivery_order do', 'dob.delivery_order_batch_id = do.delivery_order_batch_id', 'left')
			->join('delivery_order_settlement dos', 'dos.delivery_order_id = do.delivery_order_id', 'left')
			->join('penerimaan_penjualan pj', 'pj.delivery_order_batch_id = dob.delivery_order_batch_id', 'left')
			->join('canvas', 'do.canvas_id = canvas.canvas_id', 'left')
			->join('tipe_delivery_order tdo', 'dob.tipe_delivery_order_id = tdo.tipe_delivery_order_id', 'left')
			->join('picking_list pl', 'dob.picking_list_id = pl.picking_list_id', 'left')
			->join('picking_order po', 'dob.picking_order_id = po.picking_order_id', 'left')
			->join('serah_terima_kirim stk', 'dob.serah_terima_kirim_id = stk.serah_terima_kirim_id', 'left')
			->where('dob.depo_id', $this->session->userdata('depo_id'))
			->where("FORMAT(canvas.canvas_enddate, 'yyyy-MM-dd') >=", date('Y-m-d', strtotime(str_replace("/", "-", $exlodeTanggal[0]))))
			->where("FORMAT(canvas.canvas_enddate, 'yyyy-MM-dd') <=", date('Y-m-d', strtotime(str_replace("/", "-", $exlodeTanggal[1]))));
		if ($dataPost->fdjrId !== 'all') $this->db->where('dob.delivery_order_batch_id', $dataPost->fdjrId);
		if ($dataPost->sales !== 'all') $this->db->where('do.client_pt_id', $dataPost->sales);
		if ($dataPost->status !== 'all') $this->db->where('canvas.canvas_status', $dataPost->status);
		$this->db->group_by("dob.delivery_order_batch_id,
																do.delivery_order_id,
																FORMAT(dob.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy'),
																isnull(do.delivery_order_ambil_nama, ''),
																tdo.tipe_delivery_order_alias,
																dob.delivery_order_batch_status,
																dob.delivery_order_batch_kode,
																pl.picking_list_kode,
																po.picking_order_kode,
																stk.serah_terima_kirim_kode,
																CASE WHEN dos.delivery_order_id IS NULL AND dob.delivery_order_batch_status = 'completed' THEN 'Settlement has not Started' WHEN dos.delivery_order_id IS NOT NULL AND dob.delivery_order_batch_status = 'completed' THEN 'Settlement Success' ELSE '' END,
																pl.picking_list_id,
																stk.serah_terima_kirim_id");
		$this->db->order_by("FORMAT(dob.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') ASC");

		return $this->db->get()->result();
	}

	public function getDataFromClosing($fdjrId)
	{

		$response = [];
		$areaFdjr = [];
		$areaCanvas = [];

		$response['header'] = $this->db->select("dob.delivery_order_batch_id,
																						 do.delivery_order_id,
																						 FORMAT(dob.delivery_order_batch_tanggal, 'dd-MM-yyyy') as tanggal_fdjr,
																						 do.delivery_order_ambil_nama as sales,
																						 tdo.tipe_delivery_order_alias as tipe_fdjr,
																						 dob.delivery_order_batch_status as status_fdjr,
																						 dob.delivery_order_batch_kode as kode_fdjr,
																						 kendaraan.kendaraan_nopol as nopol,
																						 canvas.canvas_id,
																						 dob.is_confirm_canvas")
			->from('delivery_order_batch dob')
			->join('delivery_order do', 'dob.delivery_order_batch_id = do.delivery_order_batch_id', 'left')
			->join('canvas', 'do.canvas_id = canvas.canvas_id', 'left')
			->join('tipe_delivery_order tdo', 'dob.tipe_delivery_order_id = tdo.tipe_delivery_order_id', 'left')
			->join('kendaraan', 'dob.kendaraan_id = kendaraan.kendaraan_id', 'left')
			->join('picking_list pl', 'dob.picking_list_id = pl.picking_list_id', 'left')
			->join('picking_order po', 'dob.picking_order_id = po.picking_order_id', 'left')
			->join('serah_terima_kirim stk', 'dob.serah_terima_kirim_id = stk.serah_terima_kirim_id', 'left')
			->where('dob.delivery_order_batch_id', $fdjrId)->get()->row();


		$getAreaFdjr = $this->db->select('area.area_kode')
			->from('delivery_order_area a')
			->join('area', 'a.area_id = area.area_id', 'left')
			->where('a.delivery_order_batch_id', $fdjrId)->get()->result();

		if ($getAreaFdjr) {
			foreach ($getAreaFdjr as $key => $val) {
				$areaFdjr[] = $val->area_kode;
			}
		}

		$response['header']->area = $areaFdjr;


		$response['listPermintaan'] = $this->db->select("delivery_order.delivery_order_batch_id,
																										canvas.canvas_id,
																										FORMAT(canvas.canvas_requestdate, 'dd-MM-yyyy') as tanggal,
																										canvas.canvas_kode,
																										delivery_order.delivery_order_id,
																										delivery_order.delivery_order_kode,
																										delivery_order.delivery_order_kirim_nama as customer,
																										delivery_order.delivery_order_kirim_alamat as alamat,
																										delivery_order.delivery_order_status")
			->from('canvas')
			->join('delivery_order', 'canvas.canvas_id = delivery_order.canvas_id', 'left')
			->where('canvas.canvas_id', $response['header']->canvas_id)->get()->row();

		$getAreaCanvas = $this->db->select('area.area_kode')
			->from('canvas_detail_3 a')
			->join('area', 'a.area_id = area.area_id', 'left')
			->where('a.canvas_id', $response['header']->canvas_id)->get()->result();

		if ($getAreaCanvas) {
			foreach ($getAreaCanvas as $key => $val) {
				$areaCanvas[] = $val->area_kode;
			}
		}
		$response['listPermintaan']->area = $areaCanvas;

		return (object) $response;
	}

	public function getDepoPrefix($depo_id)
	{
		return $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get()->row();
	}

	public function downloadCanvasSO($dataPost)
	{
		$this->db->trans_begin();

		$getDataSOFas = $this->db->query("SELECT
												sales_order.sales_order_id,
												sales_order.depo_id,
												sales_order.sales_order_kode,
												sales_order.client_wms_id,
												sales_order.sales_order_is_handheld,
												sales_order.sales_order_status,
												sales_order.sales_id,
												sales_order.client_pt_id,
												sales_order.sales_order_tgl,
												sales_order.sales_order_tgl_exp,
												sales_order.sales_order_tgl_harga,
												sales_order.sales_order_tgl_sj,
												sales_order.sales_order_tgl_kirim,
												sales_order.sales_order_tipe_pembayaran,
												sales_order.tipe_sales_order_id,
												sales_order.sales_order_no_po,
												sales_order.sales_order_who_create,
												sales_order.sales_order_tgl_create,
												sales_order.sales_order_is_downloaded,
												sales_order.tipe_delivery_order_id,
												sales_order.sales_order_is_uploaded,
												sales_order.sales_order_keterangan,
												sales_order.principle_id,
												sales_order.canvas_id,
												client_pt.client_pt_nama,
												client_pt.client_pt_alamat,
												client_pt.client_pt_telepon,
												client_pt.client_pt_propinsi,
												client_pt.client_pt_kota,
												client_pt.client_pt_kecamatan,
												client_pt.client_pt_kelurahan,
												client_pt.client_pt_latitude,
												client_pt.client_pt_longitude,
												client_pt.client_pt_kodepos,
												area.area_nama,
												SUM(sales_order_detail.sku_harga_nett) as nominal
										FROM FAS.dbo.sales_order sales_order
										LEFT JOIN FAS.dbo.sales_order_detail sales_order_detail ON sales_order.sales_order_id = sales_order_detail.sales_order_id
										INNER JOIN FAS.dbo.client_pt client_pt ON client_pt.client_pt_id = sales_order.client_pt_id
										INNER JOIN FAS.dbo.area area ON area.area_id = client_pt.area_id
										WHERE sales_order.sales_order_id NOT IN(SELECT sales_order_id from WMS.dbo.delivery_order_canvas)
											AND sales_order.canvas_id = '$dataPost->canvasId'
										GROUP BY
												sales_order.sales_order_id,
												sales_order.depo_id,
												sales_order.sales_order_kode,
												sales_order.client_wms_id,
												sales_order.sales_order_is_handheld,
												sales_order.sales_order_status,
												sales_order.sales_id,
												sales_order.client_pt_id,
												sales_order.sales_order_tgl,
												sales_order.sales_order_tgl_exp,
												sales_order.sales_order_tgl_harga,
												sales_order.sales_order_tgl_sj,
												sales_order.sales_order_tgl_kirim,
												sales_order.sales_order_tipe_pembayaran,
												sales_order.tipe_sales_order_id,
												sales_order.sales_order_no_po,
												sales_order.sales_order_who_create,
												sales_order.sales_order_tgl_create,
												sales_order.sales_order_is_downloaded,
												sales_order.tipe_delivery_order_id,
												sales_order.sales_order_is_uploaded,
												sales_order.sales_order_keterangan,
												sales_order.principle_id,
												sales_order.canvas_id,
												client_pt.client_pt_nama,
												client_pt.client_pt_alamat,
												client_pt.client_pt_telepon,
												client_pt.client_pt_propinsi,
												client_pt.client_pt_kota,
												client_pt.client_pt_kecamatan,
												client_pt.client_pt_kelurahan,
												client_pt.client_pt_latitude,
												client_pt.client_pt_longitude,
												client_pt.client_pt_kodepos,
												area.area_nama")->result();

		if ($getDataSOFas) {
			foreach ($getDataSOFas as $key => $value) {
				$deliveryOrderCanvasId = $this->M_Function->Get_NewID()[0]['kode'];
				$generate_kode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal(date('Y-m-d h:i:s'), $this->M_Vrbl->Get_Kode('KODE_DO')->vrbl_kode, $this->getDepoPrefix($this->session->userdata('depo_id'))->depo_kode_preffix);

				$this->db->insert('sales_order', [
					'sales_order_id' => $value->sales_order_id,
					'depo_id' => $value->depo_id,
					'sales_order_kode' => $value->sales_order_kode,
					'client_wms_id' => $value->client_wms_id,
					'sales_order_is_handheld' => $value->sales_order_is_handheld,
					'sales_order_status' => $value->sales_order_status,
					'sales_id' => $value->sales_id,
					'client_pt_id' => $value->client_pt_id,
					'sales_order_tgl' => $value->sales_order_tgl,
					'sales_order_tgl_exp' => $value->sales_order_tgl_exp,
					'sales_order_tgl_harga' => $value->sales_order_tgl_harga,
					'sales_order_tgl_sj' => $value->sales_order_tgl_sj,
					'sales_order_tgl_kirim' => $value->sales_order_tgl_kirim,
					'sales_order_tipe_pembayaran' => $value->sales_order_tipe_pembayaran,
					'tipe_sales_order_id' => $value->tipe_sales_order_id,
					'sales_order_no_po' => $value->sales_order_no_po,
					'sales_order_who_create' => $value->sales_order_who_create,
					'sales_order_tgl_create' => $value->sales_order_tgl_create,
					'sales_order_is_downloaded' => $value->sales_order_is_downloaded,
					'tipe_delivery_order_id' => $value->tipe_delivery_order_id,
					'sales_order_is_uploaded' => $value->sales_order_is_uploaded,
					'sales_order_keterangan' => $value->sales_order_keterangan,
					'principle_id' => $value->principle_id,
					'canvas_id' => $value->canvas_id
				]);

				$this->db->insert('delivery_order_canvas', [
					'delivery_order_id' => $deliveryOrderCanvasId,
					'delivery_order_batch_id' => $dataPost->deliveryOrderBacthId,
					'sales_order_id' => $value->sales_order_id,
					'delivery_order_kode' => $generate_kode,
					'client_wms_id' => $value->client_wms_id,
					'delivery_order_tgl_buat_do' => $value->sales_order_tgl,
					'delivery_order_tgl_expired_do' => $value->sales_order_tgl_exp,
					'delivery_order_tgl_surat_jalan' => $value->sales_order_tgl_sj,
					'delivery_order_tgl_rencana_kirim' => $value->sales_order_tgl_kirim,
					'delivery_order_tgl_aktual_kirim' => $value->sales_order_tgl_kirim,
					'delivery_order_status' => 'delivered',
					'delivery_order_is_prioritas' => 0,
					'delivery_order_is_need_packing' => 0,
					'delivery_order_tipe_layanan' => 'Delivery Only',
					'delivery_order_tipe_pembayaran' => 0,
					'depo_id' => $this->session->userdata('depo_id'),
					'client_pt_id' => $value->client_pt_id,
					'delivery_order_kirim_nama' => $value->client_pt_nama,
					'delivery_order_kirim_alamat' => $value->client_pt_alamat,
					'delivery_order_kirim_telp' => $value->client_pt_telepon,
					'delivery_order_kirim_provinsi' => $value->client_pt_propinsi,
					'delivery_order_kirim_kota' => $value->client_pt_kota,
					'delivery_order_kirim_kecamatan' => $value->client_pt_kecamatan,
					'delivery_order_kirim_kelurahan' => $value->client_pt_kelurahan,
					'delivery_order_kirim_latitude' => $value->client_pt_latitude,
					'delivery_order_kirim_longitude' => $value->client_pt_longitude,
					'delivery_order_kirim_kodepos' => $value->client_pt_kodepos,
					'delivery_order_kirim_area' => $value->area_nama,
					'delivery_order_update_who' => $this->session->userdata('pengguna_username'),
					'delivery_order_update_tgl' => date('Y-m-d H:i:s'),
					'tipe_delivery_order_id' => 'B608AD49-2E8E-4289-8463-EC90DAAFB971',
					'delivery_order_nominal_tunai' => $value->nominal,
					'delivery_order_jumlah_bayar' => $value->nominal,
					'delivery_order_is_paid' => 'Tunai',
					'canvas_id' => $dataPost->canvasId,
				]);

				$getDataDetailSOFas = $this->db->query("SELECT * FROM FAS.dbo.sales_order_detail sales_order_detail WHERE sales_order_id = '$value->sales_order_id'")->result();
				if ($getDataDetailSOFas) {
					foreach ($getDataDetailSOFas as $keyDetail => $val) {
						if ($value->sales_order_id === $val->sales_order_id) {

							$this->db->insert('sales_order_detail', [
								'sales_order_detail_id' => $val->sales_order_detail_id,
								'sales_order_id' => $val->sales_order_id,
								'client_wms_id' => $val->client_wms_id,
								'sku_id' => $val->sku_id,
								'sku_kode' => $val->sku_kode,
								'sku_nama_produk' => $val->sku_nama_produk,
								'sku_harga_satuan' => $val->sku_harga_satuan,
								'sku_disc_percent' => $val->sku_disc_percent,
								'sku_disc_rp' => $val->sku_disc_rp,
								'sku_harga_nett' => $val->sku_harga_nett,
								'sku_request_expdate' => $val->sku_request_expdate,
								'sku_filter_expdate' => $val->sku_filter_expdate,
								'sku_filter_expdatebulan' => $val->sku_filter_expdatebulan,
								'sku_filter_expdatetahun' => $val->sku_filter_expdatetahun,
								'sku_weight' => $val->sku_weight,
								'sku_weight_unit' => $val->sku_weight_unit,
								'sku_length' => $val->sku_length,
								'sku_length_unit' => $val->sku_length_unit,
								'sku_width' => $val->sku_width,
								'sku_width_unit' => $val->sku_width_unit,
								'sku_height' => $val->sku_height,
								'sku_height_unit' => $val->sku_height_unit,
								'sku_volume' => $val->sku_volume,
								'sku_volume_unit' => $val->sku_volume_unit,
								'sku_qty' => $val->sku_qty,
								'sku_keterangan' => $val->sku_keterangan,
								'tipe_stock_nama' => $val->tipe_stock_nama,
								'is_promo' => $val->is_promo
							]);


							$deliveryOrderDetailCanvasId = $this->M_Function->Get_NewID()[0]['kode'];
							$this->db->insert('delivery_order_detail_canvas', [
								'delivery_order_detail_id' => $deliveryOrderDetailCanvasId,
								'delivery_order_id' => $deliveryOrderCanvasId,
								'delivery_order_batch_id' => $dataPost->deliveryOrderBacthId,
								'sku_id' => $val->sku_id,
								'sku_kode' => $val->sku_kode,
								'sku_nama_produk' => $val->sku_nama_produk,
								'sku_harga_satuan' => $val->sku_harga_satuan,
								'sku_disc_percent' => $val->sku_disc_percent,
								'sku_disc_rp' => $val->sku_disc_rp,
								'sku_harga_nett' => $val->sku_harga_nett,
								'sku_request_expdate' => 0,
								'sku_weight' => $val->sku_weight,
								'sku_length' => $val->sku_length,
								'sku_width' => $val->sku_width,
								'sku_height' => $val->sku_height,
								'sku_volume' => $val->sku_volume,
								'sku_volume_unit' => $val->sku_volume_unit,
								'sku_qty' => $val->sku_qty,
								'sku_qty_kirim' => $val->sku_qty,
								'tipe_stock_nama' => $val->tipe_stock_nama
							]);

							$getDataDetail2SOFas = $this->db->query("SELECT sales_order_detail_2.*, CAST((isnull(sales_order_detail_2.sku_qty, 0) * sku.sku_konversi_faktor) as INT) as sku_composite
																											 FROM FAS.dbo.sales_order_detail_2 sales_order_detail_2
																											 LEFT JOIN FAS.dbo.sku as sku ON sales_order_detail_2.sku_id = sku.sku_id
																											 WHERE sales_order_detail_2.sales_order_detail_id = '$val->sales_order_detail_id'")->result();

							if ($getDataDetail2SOFas) {
								foreach ($getDataDetail2SOFas as $keyDetail2 => $item) {
									if ($val->sales_order_detail_id === $item->sales_order_detail_id) {
										$deliveryOrderDetail2CanvasId = $this->M_Function->Get_NewID()[0]['kode'];
										$this->db->insert('delivery_order_detail2_canvas', [
											'delivery_order_detail2_id' => $deliveryOrderDetail2CanvasId,
											'delivery_order_detail_id' => $deliveryOrderDetailCanvasId,
											'delivery_order_id' => $deliveryOrderCanvasId,
											'sku_id' => $item->sku_id,
											'sku_stock_id' => $item->sku_stock_id,
											'sku_expdate' => $item->sku_expdate,
											'sku_qty' => $item->sku_qty,
											'sku_qty_composite' => $item->sku_composite
										]);
									}
								}
							}
						}
					}
				}
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return [
				'status' => 401,
				'message' => 'Download data canvas SO gagal'
			];
		} else {
			$this->db->trans_commit();
			return [
				'status' => 200,
				'message' => 'Download data canvas SO berhasil',
			];
		}
	}

	public function requestGetDataListDO($deliveryOrderBatchId)
	{
		$response = [];

		$resultQuery = $this->db->select("dos.delivery_order_id, 
											FORMAT(dos.delivery_order_tgl_buat_do, 'dd-MM-yyyy') as tanggal_do,
											isnull(so.sales_order_kode, '-') as kode_so,
											isnull(so.sales_order_no_po, '-') as kode_so_ext,
											dos.delivery_order_kode as kode_do,
											dos.delivery_order_kirim_nama as customer,
											dos.delivery_order_kirim_alamat as alamat,
											tp.tipe_pembayaran_nama as tipe_pembayaran,
											dos.delivery_order_status as status,
											dos.canvas_id,
											tdo.tipe_delivery_order_alias")
			->from("delivery_order_canvas dos")
			->join('sales_order so', 'dos.sales_order_id = so.sales_order_id', 'left')
			->join('tipe_pembayaran tp', 'dos.delivery_order_tipe_pembayaran = tp.tipe_pembayaran_id', 'left')
			->join('tipe_delivery_order tdo', 'dos.tipe_delivery_order_id = tdo.tipe_delivery_order_id', 'left')
			->where("dos.delivery_order_batch_id", $deliveryOrderBatchId)->get()->result();

		if ($resultQuery) {
			foreach ($resultQuery as $key => $value) {
				$areaArray = [];
				$getArea = $this->db->select('area.area_kode')
					->from('canvas_detail_3 a')
					->join('area', 'a.area_id = area.area_id', 'left')
					->where('a.canvas_id', $value->canvas_id)->get()->result();

				if ($getArea) {
					foreach ($getArea as $key => $val) {
						$areaArray[] = $val->area_kode;
					}
				}

				$response[] = [
					'delivery_order_id' => $value->delivery_order_id,
					'tanggal_do' => $value->tanggal_do,
					'kode_so' => $value->kode_so,
					'kode_so_ext' => $value->kode_so_ext,
					'kode_do' => $value->kode_do,
					'customer' => $value->customer,
					'alamat' => $value->alamat,
					'tipe_pembayaran' => $value->tipe_pembayaran,
					'status' => $value->status,
					'canvas_id' => $value->canvas_id,
					'tipe_delivery_order_alias' => $value->tipe_delivery_order_alias,
					'area' => $areaArray
				];
			}
		}

		return $response;
	}

	// public function requestGetSummaryListDO($dataPost)
	// {
	// 	// if (!$dataPost->deliveryOrderCanvasIdArray) return [];

	// 	$deliveryOrderId = [];

	// 	for ($i = 0; $i < count($dataPost->deliveryOrderCanvasIdArray); $i++) {
	// 		$deliveryOrderId[$i] = "'" . $dataPost->deliveryOrderCanvasIdArray[$i] . "'";
	// 	}

	// 	return $this->db->query("SELECT b.sku_konversi_group, b.sku_nama_produk, --b.sku_expdate,
	// 															left(b.compositeCanvas,Len(b.compositeCanvas)-1) As composite_satuan,
	// 															isnull(left(b.QtyCanvas,Len(b.QtyCanvas)-1), '0') As qty_canvas,
	// 															isnull(left(mergecomposite.Qty,Len(mergecomposite.Qty)-1), b.valuecomposite) As qty_terjual

	// 													from (
	// 																select distinct b2.sku_konversi_group, b2.sku_nama_produk, 
	// 																				(
	// 																						select cast(a1.sku_qty as varchar(255)) + '/' as [text()]
	// 																							from (
	// 																								select sku_qty, sku_konversi_group, sku_konversi_level
	// 																								from (
	// 																										select sum(a1.sku_qty) sku_qty, b1.sku_konversi_group, b1.sku_konversi_level
	// 																											from delivery_order_detail_canvas a1
	// 																										inner join sku b1 on a1.sku_id = b1.sku_id
	// 																												where b1.sku_konversi_group = b2.sku_konversi_group
	// 																													and delivery_order_id in (" . implode(',', $deliveryOrderId) . ")
	// 																											group by b1.sku_konversi_group, b1.sku_konversi_level
	// 																												union
	// 																											select 0 as sku_qty, sku_konversi_group, sku_konversi_level
	// 																												from sku
	// 																												where sku_id not in (select sku_id
	// 																																from delivery_order_detail_canvas
	// 																																	where delivery_order_id in (" . implode(',', $deliveryOrderId) . ")
	// 																																)
	// 																												and sku_konversi_group in (select b.sku_konversi_group 
	// 																																		from delivery_order_detail_canvas a
	// 																																	inner join sku b on a.sku_id = b.sku_id 
	// 																																			where delivery_order_id in (" . implode(',', $deliveryOrderId) . "))
	// 																												and sku.sku_konversi_group =  b2.sku_konversi_group ) as xx
	// 																											) as a1
	// 																						order by sku_konversi_group, sku_konversi_level desc
	// 																					for xml path (''), type
	// 																				).value('text()[1]','nvarchar(max)') [Qty],
	// 																				(
	// 																						select b1.sku_satuan + '/' as [text()]
	// 																							from delivery_order_detail_canvas a1
	// 																					inner join sku b1 on a1.sku_id = b1.sku_id
	// 																							where b1.sku_konversi_group = b2.sku_konversi_group
	// 																								and delivery_order_id in (" . implode(',', $deliveryOrderId) . ")
	// 																						order by a1.sku_kode
	// 																					for xml path (''), type
	// 																				).value('text()[1]','nvarchar(max)') [composite]
	// 																	from delivery_order_detail_canvas a2
	// 															inner join SKU b2 on a2.sku_id = b2.sku_id
	// 																	where delivery_order_id in (" . implode(',', $deliveryOrderId) . ")
	// 																	) mergecomposite
	// 														right join (
	// 														select distinct b2.sku_konversi_group, b2.sku_nama_produk, 
	// 																		(
	// 																				select cast(a.sku_qty as varchar(255)) + '/' as [text()] 
	// 																					from (
	// 																						select cast(a1.sku_qty as varchar(255)) as sku_qty, b1.sku_konversi_group, b1.sku_konversi_level
	// 																						from canvas_detail a1
	// 																					inner join sku b1 on a1.sku_id = b1.sku_id
	// 																							where canvas_id = '$dataPost->canvasId'
	// 																							and b1.sku_konversi_group =  b2.sku_konversi_group
	// 																							union
	// 																						select 0 as sku_qty, sku_konversi_group, sku_konversi_level
	// 																						from sku
	// 																							where sku_id not in (select sku_id
	// 																												from canvas_detail
	// 																												where canvas_id = '$dataPost->canvasId')
	// 																							and sku_konversi_group in (select b.sku_konversi_group 
	// 																														from canvas_detail a
	// 																												inner join sku b on a.sku_id = b.sku_id 
	// 																														where canvas_id = '$dataPost->canvasId')
	// 																							and sku.sku_konversi_group =  b2.sku_konversi_group
	// 																							) as a
	// 																				order by sku_konversi_level desc
	// 																			for xml path (''), type
	// 																		).value('text()[1]','nvarchar(max)') [QtyCanvas],
	// 																		(
	// 																				select b1.sku_satuan + '/' as [text()]
	// 																					from sku b1
	// 																					where b1.sku_konversi_group in (select b.sku_konversi_group 
	// 																														from canvas_detail a
	// 																												inner join sku b on a.sku_id = b.sku_id 
	// 																													where canvas_id = '$dataPost->canvasId')
	// 																						and b1.sku_konversi_group = b2.sku_konversi_group
	// 																				order by b1.sku_konversi_level desc
	// 																			for xml path (''), type
	// 																		).value('text()[1]','nvarchar(max)') [compositeCanvas],
	// 																		maxcomposite.valuecomposite
	// 																		--c.sku_expdate
	// 															from canvas_detail a2
	// 													inner join SKU b2 on a2.sku_id = b2.sku_id
	// 													left join (
	// 																	select sku_konversi_group, maxcomposite,
	// 																			case when maxcomposite = 1 then '0'
	// 																			when maxcomposite = 2 then '0/0'
	// 																			when maxcomposite = 3 then '0/0/0'
	// 																			end as valuecomposite
	// 																	from(
	// 																		select count(sku_konversi_group) maxcomposite, sku_konversi_group from sku group by sku_konversi_group
	// 																		) as a
	// 															) as maxcomposite on maxcomposite.sku_konversi_group = b2.sku_konversi_group
	// 													left join (
	// 																	select distinct m.sku_konversi_group, n.sku_expdate from canvas_detail_2 n
	// 																	inner join sku m on n.sku_id = m.sku_id
	// 																	where canvas_id = '$dataPost->canvasId'
	// 																) as c on b2.sku_konversi_group = c.sku_konversi_group
	// 															where canvas_id = '$dataPost->canvasId'
	// 													) as b on mergecomposite.sku_konversi_group = b.sku_konversi_group")->result();
	// }

	public function requestGetSummaryListDO($dataPost)
	{
		return $this->db->query("exec proc_summary_data_canvas '$dataPost->deliveryOrderBatch'")->result();
	}

	public function requestGetDataDOById($dataPost)
	{
		$header = $this->db->select("dos.delivery_order_kode as kode_do,
																dos.delivery_order_kirim_nama as customer,
																dos.delivery_order_kirim_alamat as alamat,
																dos.delivery_order_status as status,
																tdo.tipe_delivery_order_alias")
			->from("delivery_order_canvas dos")
			->join('tipe_delivery_order tdo', 'dos.tipe_delivery_order_id = tdo.tipe_delivery_order_id', 'left')
			->where("dos.delivery_order_id", $dataPost->deliveryOrderId)->get()->row();


		$getAreaCanvas = $this->db->select('area.area_kode')
			->from('canvas_detail_3 a')
			->join('area', 'a.area_id = area.area_id', 'left')
			->where('a.canvas_id', $dataPost->canvasId)->get()->result();

		$areaArray = [];

		if ($getAreaCanvas) {
			foreach ($getAreaCanvas as $key => $val) {
				$areaArray[] = $val->area_kode;
			}
		}

		$header->area = $areaArray;

		$detail = $this->db->select("sku.sku_kode, sku.sku_nama_produk, sku.sku_kemasan, sku.sku_satuan, dosd.sku_qty, isnull(dosd.sku_keterangan, '-') as sku_keterangan")
			->from("delivery_order_detail_canvas dosd")
			->join('sku', 'dosd.sku_id = sku.sku_id', 'left')
			->where("dosd.delivery_order_id", $dataPost->deliveryOrderId)->get()->result();


		return [
			'header' => $header,
			'detail' => $detail,
		];
	}

	public function GetPrincipleByPerusahaan($delivery_order_id)
	{
		$query = $this->db->query("SELECT DISTINCT
										principle.*
									FROM delivery_order do
                                    LEFT JOIN client_wms_principle
                                    ON client_wms_principle.client_wms_id = do.client_wms_id
									LEFT JOIN principle
									ON client_wms_principle.principle_id = principle.principle_id
									WHERE do.delivery_order_id = '$delivery_order_id'
									ORDER BY principle.principle_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetCheckerByPrinciple()
	{
		$query = $this->db->query("SELECT DISTINCT
										karyawan.*
									FROM karyawan
									LEFT JOIN karyawan_principle
									ON karyawan.karyawan_id = karyawan_principle.karyawan_id
									WHERE karyawan.depo_id = '" . $this->session->userdata('depo_id') . "' AND karyawan.karyawan_level_id = '0C2CC2B3-B26C-4249-88BE-77BD0BA61C41'
									ORDER BY karyawan.karyawan_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function GetHeaderDeliveryOrderById($id)
	{
		$query = $this->db->query("SELECT
                                    do.delivery_order_batch_id,
                                    fdjr.delivery_order_batch_kode,
																		do.delivery_order_id,
																		do.delivery_order_kode,
																		do.sales_order_id,
																		so.sales_order_kode,
																		so.sales_order_no_po,
																		FORMAT(do.delivery_order_tgl_buat_do, 'dd/MM/yyyy') AS delivery_order_tgl_buat_do,
																		FORMAT(do.delivery_order_tgl_expired_do, 'dd/MM/yyyy') AS delivery_order_tgl_expired_do,
																		FORMAT(do.delivery_order_tgl_surat_jalan, 'dd/MM/yyyy') AS delivery_order_tgl_surat_jalan,
																		FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd/MM/yyyy') AS delivery_order_tgl_rencana_kirim,
																		do.delivery_order_update_tgl,
																		do.client_wms_id,
																		client_wms.client_wms_nama,
																		client_wms.client_wms_alamat,
																		do.client_pt_id,
																		ISNULL(do.delivery_order_kirim_nama,'') AS delivery_order_kirim_nama,
																		ISNULL(do.delivery_order_kirim_alamat,'') AS delivery_order_kirim_alamat,
																		ISNULL(do.delivery_order_kirim_telp,'') AS delivery_order_kirim_telp,
																		ISNULL(do.delivery_order_kirim_provinsi,'') AS delivery_order_kirim_provinsi,
																		ISNULL(do.delivery_order_kirim_kota,'') AS delivery_order_kirim_kota,
																		ISNULL(do.delivery_order_kirim_kecamatan,'') AS delivery_order_kirim_kecamatan,
																		ISNULL(do.delivery_order_kirim_kelurahan,'') AS delivery_order_kirim_kelurahan,
																		ISNULL(do.delivery_order_kirim_kodepos,'') AS delivery_order_kirim_kodepos,
																		ISNULL(do.delivery_order_kirim_area,'') AS delivery_order_kirim_area,
																		do.principle_id,
																		ISNULL(do.delivery_order_ambil_nama,'') AS delivery_order_ambil_nama,
																		ISNULL(do.delivery_order_ambil_alamat,'') AS delivery_order_ambil_alamat,
																		ISNULL(do.delivery_order_ambil_telp,'') AS delivery_order_ambil_telp,
																		ISNULL(do.delivery_order_ambil_provinsi,'') AS delivery_order_ambil_provinsi,
																		ISNULL(do.delivery_order_ambil_kota,'') AS delivery_order_ambil_kota,
																		ISNULL(do.delivery_order_ambil_kecamatan,'') AS delivery_order_ambil_kecamatan,
																		ISNULL(do.delivery_order_ambil_kelurahan,'') AS delivery_order_ambil_kelurahan,
																		ISNULL(do.delivery_order_ambil_kodepos,'') AS delivery_order_ambil_kodepos,
																		ISNULL(do.delivery_order_ambil_area,'') AS delivery_order_ambil_area,
																		do.delivery_order_tipe_pembayaran,
																		do.delivery_order_tipe_layanan,
																		do.tipe_delivery_order_id,
																		ISNULL(do.delivery_order_keterangan,'') AS delivery_order_keterangan,
																		do.delivery_order_status,
                                    CASE
																			WHEN do.delivery_order_status = 'delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim'
																			WHEN do.delivery_order_status = 'partially delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim sebagian'
																			WHEN do.delivery_order_status = 'not delivered' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'tidak terkirim'
																			WHEN do.delivery_order_kode LIKE '%/DOR/%' THEN 'retur'
																			WHEN do.delivery_order_status = 'rescheduled' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'rescheduled'
																			ELSE '' END AS do_status,
									penerimaan_penjualan.depo_detail_id,
									penerimaan_penjualan.karyawan_id,
									FORMAT(ISNULL(penerimaan_penjualan.penerimaan_penjualan_tgl,GETDATE()),'dd-MM-yyyy') AS penerimaan_penjualan_tgl
									FROM delivery_order do
									LEFT JOIN FAS.dbo.sales_order so ON so.sales_order_id = do.sales_order_id 
									LEFT JOIN delivery_order_batch fdjr ON do.delivery_order_batch_id = fdjr.delivery_order_batch_id
									LEFT JOIN penerimaan_penjualan ON penerimaan_penjualan.delivery_order_batch_id = fdjr.delivery_order_batch_id
									LEFT JOIN client_wms ON do.client_wms_id = client_wms.client_wms_id
									WHERE do.delivery_order_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}

	public function GetDetailDeliveryOrderById($deliveryOrderId)
	{
		// return $this->db->select("sku.sku_id,
		// 													principle.principle_id,
		// 													principle.principle_kode as principle,
		// 													sku.sku_konversi_group,
		// 													sku.sku_kode,
		// 													sku.sku_nama_produk,
		// 													sku.sku_kemasan,
		// 													sku.sku_satuan,
		// 													sku.sku_konversi_level,
		// 													dod.sku_expdate")
		// 	->from("sku")
		// 	->join('principle', 'sku.principle_id = principle.principle_id', 'left')
		// 	->join('delivery_order_detail2 dod', 'sku.sku_id = dod.sku_id', 'inner')
		// 	->where_in('sku.sku_konversi_group', $skuKonversiGroupArray)
		// 	->group_by("sku.sku_id,
		// 							principle.principle_id,
		// 							principle.principle_kode,
		// 							sku.sku_konversi_group,
		// 							sku.sku_kode,
		// 							sku.sku_nama_produk,
		// 							sku.sku_kemasan,
		// 							sku.sku_satuan,
		// 							sku.sku_konversi_level,
		// 							dod.sku_expdate")
		// 	->order_by('sku.sku_konversi_group', 'ASC')
		// 	->order_by('sku.sku_konversi_level', 'DESC')->get()->result();

		return $this->db->query("SELECT 
																a.sku_id,
																a.sku_induk_id,
																a.sku_kode,
																a.sku_nama_produk,
																d.principle_id,
																d.principle_kode as principle,
																a.sku_kemasan,
																a.sku_satuan,
																a.sku_konversi_group,
																a.sku_konversi_level,
																c.sku_expdate
														from sku a
														inner join (
																select a.delivery_order_id, b.sku_konversi_group, a.sku_expdate
																	from delivery_order_detail2 a
															inner join sku b on a.sku_id = b.sku_id
																	where a.delivery_order_id = '$deliveryOrderId'
																) as c on a.sku_konversi_group = c.sku_konversi_group
														inner join principle d on a.principle_id = d.principle_id
														where a.sku_konversi_group in (select distinct a.sku_konversi_group from sku a inner join delivery_order_detail2 b on a.sku_id = b.sku_id where b.delivery_order_id = '$deliveryOrderId')
														order by sku_konversi_group, sku_konversi_level")->result();
	}

	public function GetGudang()
	{
		$query = $this->db->query("SELECT
										depo_detail_id,
										depo_detail_nama
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "' AND depo_detail_is_gudang_penerima = '1'
									ORDER BY depo_detail_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_pallet_jenis()
	{
		$this->db->select("*")
			->from("pallet_jenis")
			->where("pallet_jenis_id", "86C84F5F-D967-4DA6-8B6E-3F81679232F3");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = "0";
		} else {
			$query = $query->row(0)->pallet_jenis_kode;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_NEWID()
	{
		$query = $this->db->query("SELECT NEWID() AS generate_kode");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->generate_kode;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Insert_PalletTemp($delivery_order_batch_id, $pallet_id, $pallet_kode, $rak_lajur_detail_id, $depo_detail_id)
	{
		//insert delivery_order_progress
		$this->db->set("pallet_id", $pallet_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("depo_id", $this->session->userdata('depo_id'));
		$this->db->set("pallet_kode", $pallet_kode);
		$this->db->set("rak_lajur_detail_id", $rak_lajur_detail_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("pallet_jenis_id", "86C84F5F-D967-4DA6-8B6E-3F81679232F3");
		$this->db->set("pallet_tanggal_create", "GETDATE()", FALSE);
		$this->db->set("pallet_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("pallet_is_aktif", '1');

		$this->db->insert("pallet_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_PalletTemp2($delivery_order_batch_id, $pallet_id, $depo_detail_id)
	{
		$query = $this->db->query("INSERT INTO pallet_temp
									(pallet_id,
									pallet_jenis_id,
									delivery_order_batch_id,
									depo_id,
									rak_lajur_detail_id,
									pallet_kode,
									pallet_tanggal_create,
									pallet_who_create,
									pallet_is_aktif,
									depo_detail_id,
									id_temp,
									flag,
									tipe_stock,
									karyawan_id,
									depo_detail_nama)
									SELECT
									'" . $pallet_id . "',
									pallet_jenis_id,
									'" . $delivery_order_batch_id . "',
									depo_id,
									rak_lajur_detail_id,
									pallet_kode,
									pallet_tanggal_create,
									pallet_who_create,
									1,
									'$depo_detail_id',
									NULL,
									NULL,
									NULL,
									NULL,
									NULL
									FROM pallet
									WHERE pallet_id = '$pallet_id' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function get_pallet_by_arr_id($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
										pallet_temp.pallet_id,
										pallet_temp.pallet_kode,
										pallet_temp.pallet_jenis_id,
										pallet_jenis.pallet_jenis_nama,
										pallet_temp.rak_lajur_detail_id,
										rak_lajur_detail.rak_lajur_detail_nama,
										ISNULL((select pallet_kode FROM pallet WHERE pallet_id = pallet_temp.pallet_id),'0') AS status_pallet
									FROM pallet_temp
									LEFT JOIN pallet_jenis
									ON pallet_temp.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = pallet_temp.rak_lajur_detail_id
									WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY pallet_temp.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Delete_PalletTemp($pallet_id)
	{
		$this->db->trans_begin();

		$this->db->where("pallet_id", $pallet_id);

		$this->db->delete("pallet_detail_temp");

		$this->db->where("pallet_id", $pallet_id);

		$this->db->delete("pallet_temp");

		$error = $this->db->error();

		if ($error['message'] != '' || $error['message'] != null)
		//if( $error['message'] != '' && $error['message'] != null )
		{
			$res = $error['message']; // Error

			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();

			$affectedrows = $this->db->affected_rows();
			if (abs($affectedrows) > 0) {
				$res = 1; // Success
			} else {
				$res = 0; // Success
			}
		}

		return $res;
	}

	public function Delete_PalletDetailTemp($pallet_detail_id)
	{
		$this->db->trans_begin();

		$this->db->where("pallet_detail_id", $pallet_detail_id);

		$this->db->delete("pallet_detail_temp");

		$error = $this->db->error();

		if ($error['message'] != '' || $error['message'] != null)
		//if( $error['message'] != '' && $error['message'] != null )
		{
			$res = $error['message']; // Error

			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();

			$affectedrows = $this->db->affected_rows();
			if (abs($affectedrows) > 0) {
				$res = 1; // Success
			} else {
				$res = 0; // Success
			}
		}

		return $res;
	}

	public function Delete_PalletTemp2($pallet_id)
	{
		$this->db->trans_begin();

		$this->db->where("pallet_id", $pallet_id);

		$this->db->delete("pallet_temp");

		$error = $this->db->error();

		if ($error['message'] != '' || $error['message'] != null)
		//if( $error['message'] != '' && $error['message'] != null )
		{
			$res = $error['message']; // Error

			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();

			$affectedrows = $this->db->affected_rows();
			if (abs($affectedrows) > 0) {
				$res = 1; // Success
			} else {
				$res = 0; // Success
			}
		}

		return $res;
	}

	public function check_kode_pallet($pallet_kode, $depo_detail_id)
	{
		$query = $this->db->query("SELECT
									pallet.*
									FROM pallet
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_detail_id = pallet.rak_lajur_detail_id
									LEFT JOIN rak_lajur
									ON rak_lajur.rak_lajur_id = rak_lajur_detail.rak_lajur_id
									LEFT JOIN rak
									ON rak.rak_id = rak_lajur.rak_id
									WHERE pallet.pallet_kode = '$pallet_kode'
									AND rak.depo_detail_id = '$depo_detail_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->pallet_id;
		}

		return $query;
	}

	public function check_rak_lajur_detail($depo_detail_id, $kode)
	{
		return $this->db->query("SELECT
										rak_lajur.rak_lajur_id,
										rak_lajur.rak_lajur_nama,
										rak_lajur_detail.rak_lajur_detail_id AS rak_detail_id,
										rak_lajur_detail.rak_lajur_detail_nama AS rak_detail_nama
									FROM rak
									LEFT JOIN rak_lajur
									ON rak_lajur.rak_id = rak.rak_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									WHERE rak.depo_id = '" . $this->session->userdata('depo_id') . "' AND rak.depo_detail_id = '$depo_detail_id' 
									AND rak_lajur_detail.rak_lajur_detail_nama = '$kode'")->row();
	}

	public function update_pallet_rak_lajur_detail($pallet_id, $rak_lajur_detail_id)
	{
		$this->db->set("rak_lajur_detail_id", $rak_lajur_detail_id);
		$this->db->where("pallet_id", $pallet_id);
		return $this->db->update("pallet_temp");

		// return $this->db->last_query();
	}

	public function Get_Pallet($delivery_order_batch_id)
	{
		$this->db->select("*")
			->from("pallet_temp")
			->where("delivery_order_batch_id", $delivery_order_batch_id)
			->order_by("pallet_kode");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_PalletDetail($pallet_id)
	{
		$query = $this->db->query("SELECT
									pallet_detail_temp.pallet_id,
									pallet_detail_temp.pallet_detail_id,
									pallet_temp.delivery_order_batch_id,
									principle.principle_kode AS principle,
									principle_brand.principle_brand_nama AS brand,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_induk_id,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									pallet_detail_temp.sku_stock_id,
									ISNULL(sku_stock.sku_stock_batch_no,'') AS sku_stock_batch_no,
									FORMAT(pallet_detail_temp.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
									FORMAT(pallet_detail_temp.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date_retur,
									ISNULL(pallet_detail_temp.sku_stock_qty, 0) AS sku_stock_qty,
									penerimaan_tipe.penerimaan_tipe_nama
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_detail_temp.pallet_id = pallet_temp.pallet_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = pallet_detail_temp.sku_stock_id
									LEFT JOIN sku
									ON pallet_detail_temp.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN penerimaan_tipe
									ON penerimaan_tipe.penerimaan_tipe_id = pallet_detail_temp.penerimaan_tipe_id
									WHERE pallet_detail_temp.pallet_id = '$pallet_id'
									ORDER BY penerimaan_tipe.penerimaan_tipe_nama, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function Get_PalletDetail2($penerimaan_penjualan_id, $delivery_order_id, $pallet_id)
	{
		$query = $this->db->query("SELECT
									pallet_detail.pallet_id,
									principle.principle_kode AS principle,
									principle_brand.principle_brand_nama AS brand,
									sku.sku_id,
									sku.sku_kode,
									sku.sku_induk_id,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									pallet_detail.sku_stock_id,
									FORMAT(pallet_detail.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
									penerimaan_tipe.penerimaan_tipe_nama,
									ISNULL(sku_stock.sku_stock_batch_no,'') AS sku_stock_batch_no,
									SUM(ISNULL(pallet_detail.sku_stock_qty, 0)) - SUM(ISNULL(pallet_detail.sku_stock_ambil, 0)) + SUM(ISNULL(pallet_detail.sku_stock_in, 0)) - SUM(ISNULL(pallet_detail.sku_stock_out, 0)) + SUM(ISNULL(pallet_detail.sku_stock_terima, 0)) AS sku_stock_terima
									FROM pallet
									LEFT JOIN pallet_detail
									ON pallet_detail.pallet_id = pallet.pallet_id
									LEFT JOIN sku_stock
									ON sku_stock.sku_stock_id = pallet_detail.sku_stock_id
									LEFT JOIN (SELECT
									detail.delivery_order_id,
									detail2.pallet_id,
									detail.sku_id,
									detail.sku_stock_id,
									detail.sku_jumlah_barang,
									detail.sku_jumlah_terima
									FROM penerimaan_penjualan_detail detail
									LEFT JOIN penerimaan_penjualan_detail2 detail2
									ON detail2.penerimaan_penjualan_id = detail.penerimaan_penjualan_id
									WHERE detail.penerimaan_penjualan_id = '$penerimaan_penjualan_id'
									GROUP BY detail.delivery_order_id,
											detail2.pallet_id,
											detail.sku_id,
											detail.sku_stock_id,
											detail.sku_jumlah_barang,
											detail.sku_jumlah_terima) penerimaan
									ON penerimaan.pallet_id = pallet.pallet_id
									AND penerimaan.sku_stock_id = sku_stock.sku_stock_id
									LEFT JOIN sku
									ON pallet_detail.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN penerimaan_tipe
									ON penerimaan_tipe.penerimaan_tipe_id = pallet_detail.penerimaan_tipe_id
									WHERE pallet_detail.pallet_id = '$pallet_id'
									AND pallet_detail.sku_id in (select sku_id from delivery_order_detail where delivery_order_id = '$delivery_order_id')
									GROUP BY  pallet_detail.pallet_id,
											principle.principle_kode,
											principle_brand.principle_brand_nama,
											sku.sku_id,
											sku.sku_kode,
											sku.sku_induk_id,
											sku.sku_nama_produk,
											sku.sku_kemasan,
											sku.sku_satuan,
											pallet_detail.sku_stock_id,
											FORMAT(pallet_detail.sku_stock_expired_date, 'yyyy-MM-dd'),
											penerimaan_tipe.penerimaan_tipe_nama,
											ISNULL(sku_stock.sku_stock_batch_no,'')
									ORDER BY penerimaan_tipe.penerimaan_tipe_nama, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function Get_SKU_Expired_Date($sku_id)
	{
		$this->db->distinct()->select("sku_stock_id,sku_id,FORMAT(sku_stock_expired_date,'dd-MM-yyyy') AS sku_stock_expired_date")
			->from("sku_stock")
			->where("sku_id", $sku_id)
			->where("depo_id", $this->session->userdata('depo_id'))
			->order_by("FORMAT(sku_stock_expired_date,'dd-MM-yyyy')");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Update_SkuExpDatePalletTemp($pallet_detail_id, $sku_id, $sku_stock_expired_date, $depo_detail_id)
	{
		$cek_sku = $this->db->query("SELECT sku_stock_id FROM sku_stock WHERE FORMAT(sku_stock_expired_date,'yyyy-MM-dd') = '$sku_stock_expired_date' AND sku_id = '$sku_id' AND depo_detail_id = '$depo_detail_id' ");
		// $cek_sku = $this->db->query("SELECT sku_stock_id FROM sku_stock WHERE FORMAT(sku_stock_expired_date,'yyyy-MM-dd') = '$sku_stock_expired_date' AND sku_id = '$sku_id' ");

		if ($cek_sku->num_rows() > 0) {
			$this->db->set("sku_stock_id", $cek_sku->row(0)->sku_stock_id);
		}

		$this->db->set("sku_stock_expired_date", $sku_stock_expired_date);

		$this->db->where("pallet_detail_id", $pallet_detail_id);

		$this->db->update("pallet_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
		// return $this->db->last_query();
	}

	public function Update_ClosingPengirimanFDJR($delivery_order_batch_id, $delivery_order_batch_status)
	{
		$this->db->set("delivery_order_batch_status", $delivery_order_batch_status);
		$this->db->where("delivery_order_batch_id", $delivery_order_batch_id);

		$this->db->update("delivery_order_batch");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Update_QtySKUPalletTemp($pallet_detail_id, $sku_stock_qty)
	{
		$this->db->set("sku_stock_qty", $sku_stock_qty);

		$this->db->where("pallet_detail_id", $pallet_detail_id);

		$this->db->update("pallet_detail_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
	}

	public function Check_QtySKUPalletTemp($delivery_order_batch_id, $sku_id, $sku_stock_qty, $penerimaan_tipe_nama)
	{
		if ($penerimaan_tipe_nama == "retur") {

			$query = $this->db->query("SELECT
									delivery_order_detail.sku_id,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) AS sku_qty,
									SUM(ISNULL(pallet.sku_stock_qty, 0)) AS sku_stock_qty,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) - (SUM(ISNULL(pallet.sku_stock_qty, 0)) + " . $sku_stock_qty . ") AS sku_stock_qty_max
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
									LEFT JOIN (SELECT
									pallet_temp.delivery_order_batch_id,
									pallet_detail_temp.sku_id,
									SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
									GROUP BY pallet_temp.delivery_order_batch_id,
											sku_id) pallet
									ON pallet.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									AND pallet.sku_id = delivery_order_detail.sku_id
									WHERE delivery_order.delivery_order_batch_id = '$delivery_order_batch_id'
									AND delivery_order_detail.sku_id = '$sku_id'
									AND delivery_order.delivery_order_kode LIKE '%/DOR/%'
									AND delivery_order.delivery_order_status = 'delivered'
									GROUP BY delivery_order_detail.sku_id");
		} else if ($penerimaan_tipe_nama == "terkirim sebagian") {

			$query = $this->db->query("SELECT
									delivery_order_detail.sku_id,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) AS sku_qty,
									SUM(ISNULL(pallet.sku_stock_qty, 0)) AS sku_stock_qty,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) - (SUM(ISNULL(pallet.sku_stock_qty, 0)) + " . $sku_stock_qty . ") AS sku_stock_qty_max
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
									LEFT JOIN (SELECT
									pallet_temp.delivery_order_batch_id,
									pallet_detail_temp.sku_id,
									SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
									GROUP BY pallet_temp.delivery_order_batch_id,
											sku_id) pallet
									ON pallet.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									AND pallet.sku_id = delivery_order_detail.sku_id
									WHERE delivery_order.delivery_order_batch_id = '$delivery_order_batch_id'
									AND delivery_order_detail.sku_id = '$sku_id'
									AND delivery_order.delivery_order_status = 'partially delivered'
									GROUP BY delivery_order_detail.sku_id");
		} else if ($penerimaan_tipe_nama == "tidak terkirim") {

			$query = $this->db->query("SELECT
									delivery_order_detail.sku_id,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) AS sku_qty,
									SUM(ISNULL(pallet.sku_stock_qty, 0)) AS sku_stock_qty,
									SUM(delivery_order_detail.sku_qty - delivery_order_detail.sku_qty_kirim) - (SUM(ISNULL(pallet.sku_stock_qty, 0)) + " . $sku_stock_qty . ") AS sku_stock_qty_max
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
									LEFT JOIN (SELECT
									pallet_temp.delivery_order_batch_id,
									pallet_detail_temp.sku_id,
									SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
									GROUP BY pallet_temp.delivery_order_batch_id,
											sku_id) pallet
									ON pallet.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									AND pallet.sku_id = delivery_order_detail.sku_id
									WHERE delivery_order.delivery_order_batch_id = '$delivery_order_batch_id'
									AND delivery_order_detail.sku_id = '$sku_id'
									AND delivery_order.delivery_order_status = 'not delivered'
									GROUP BY delivery_order_detail.sku_id");
		}

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->sku_stock_qty_max;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetRakLajurDetail($depo_detail_id)
	{
		$query = $this->db->query("SELECT
									rak_lajur_detail.rak_lajur_detail_id,
									rak_lajur_detail.rak_lajur_detail_nama
									FROM rak
									LEFT JOIN rak_lajur
									ON rak_lajur.rak_id = rak.rak_id
									LEFT JOIN rak_lajur_detail
									ON rak_lajur_detail.rak_lajur_id = rak_lajur.rak_lajur_id
									WHERE rak.depo_id = '" . $this->session->userdata('depo_id') . "' AND rak.depo_detail_id = '$depo_detail_id'
									ORDER BY rak_lajur_detail.rak_lajur_detail_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function getKodeAutoComplete($value, $type)
	{
		if ($type == 'notone') {
			return $this->db->select("CONCAT(REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 2)), '/', REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 3))) as kode")
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

	public function UpdatePalletKodeTemByIdTemp($pallet_id, $pallet_kode)
	{
		$this->db->set("pallet_kode", $pallet_kode);
		$this->db->where("pallet_id", $pallet_id);

		$this->db->update("pallet_temp");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
		// return $this->db->last_query();
	}

	public function Check_PenerimaanPenjualan($delivery_order_batch_id)
	{
		// $this->db->select("penerimaan_penjualan_kode")
		// 	->from("penerimaan_penjualan")
		// 	->where("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
		// $query = $this->db->get();

		$this->db->select("penerimaan_penjualan_id")
			->from("penerimaan_penjualan")
			->where("delivery_order_batch_id", $delivery_order_batch_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			// $query = $query->row(0)->penerimaan_penjualan_kode;
			$query = $query->row(0)->penerimaan_penjualan_id;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Update_StockSKU($delivery_order_batch_id, $depo_detail_id, $client_wms_id)
	{
		$sql_check = $this->db->query("SELECT
																			pallet_detail_temp.pallet_detail_id,
																			pallet_detail_temp.sku_id,
																			sku.sku_induk_id,
																			pallet_detail_temp.sku_stock_id,
																			pallet_detail_temp.sku_stock_expired_date,
																			pallet_detail_temp.sku_stock_qty
																	FROM pallet_temp
																	LEFT JOIN pallet_detail_temp
																	ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
																	LEFT JOIN sku
																	ON sku.sku_id = pallet_detail_temp.sku_id
																	WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
																		AND pallet_temp.depo_detail_id = '$depo_detail_id' ");

		if ($sql_check->num_rows() == 0) {
			$queryupdate = 0;
		} else {

			foreach ($sql_check->result() as $row) {
				$query_sku_stock_id = $this->db->query("SELECT NEWID() AS generate_kode");

				$sku_stock_id = $query_sku_stock_id->row(0)->generate_kode;

				$this->db->set("sku_stock_id", $sku_stock_id);
				$this->db->set("unit_mandiri_id", $this->session->userdata('unit_mandiri_id'));
				$this->db->set("client_wms_id", $client_wms_id);
				$this->db->set("depo_id", $this->session->userdata('depo_id'));
				$this->db->set("depo_detail_id", $depo_detail_id);
				$this->db->set("sku_induk_id", $row->sku_induk_id);
				$this->db->set("sku_id", $row->sku_id);
				$this->db->set("sku_stock_expired_date", $row->sku_stock_expired_date);
				// $this->db->set("sku_stock_batch_no", $sku_stock_batch_no);
				$this->db->set("sku_stock_awal", "0");
				$this->db->set("sku_stock_masuk", $row->sku_stock_qty);
				$this->db->set("sku_stock_alokasi", "0");
				$this->db->set("sku_stock_saldo_alokasi", "0");
				$this->db->set("sku_stock_keluar", "0");
				$this->db->set("sku_stock_akhir", "0");
				$this->db->set("sku_stock_is_jual", "1");
				$this->db->set("sku_stock_is_aktif", "1");
				$this->db->set("sku_stock_is_deleted", "0");

				$this->db->insert("sku_stock");

				$this->db->set("sku_stock_id", $sku_stock_id);
				$this->db->where("pallet_detail_id", $row->pallet_detail_id);

				$this->db->update("pallet_detail_temp");
			}

			$queryupdate = 1;
		}

		// return $this->db->last_query();
		return $queryupdate;
	}

	public function Insert_PenerimaanPenjualan($penerimaan_penjualan_id, $penerimaan_penjualan_kode, $delivery_order_batch_id, $depo_detail_id, $karyawan_id, $penerimaan_penjualan_keterangan, $client_wms_id)
	{
		//insert delivery_order_progress
		$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		$this->db->set("depo_id", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("karyawan_id", $karyawan_id);
		// $this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		$this->db->set("penerimaan_penjualan_kode", $penerimaan_penjualan_kode);
		$this->db->set("penerimaan_penjualan_tgl", "GETDATE()", FALSE);
		$this->db->set("penerimaan_penjualan_who_create", $this->session->userdata('pengguna_username'));
		$this->db->set("penerimaan_penjualan_keterangan", $penerimaan_penjualan_keterangan);
		$this->db->set("penerimaan_penjualan_tgl_create", "GETDATE()", FALSE);
		$this->db->set("client_wms_id", $client_wms_id);
		// $this->db->set("principle_id", $principle_id);

		$this->db->insert("penerimaan_penjualan");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function get_data_do_by_id($delivery_order_batch_id, $delivery_order_id, $arrDataSkuBygroup)
	{
		$query = $this->db->query("SELECT
									delivery_order_detail.delivery_order_id,
									delivery_order_detail.sku_id,
									sku.sku_nama_produk,
									sku.principle_id,
									sku.sku_kemasan,
									sku.sku_satuan,
									--delivery_order_detail.sku_qty AS sku_jumlah_barang,
									--ISNULL(penerimaan.sku_stock_qty, 0) AS sku_jumlah_terima,
									penerimaan.jumlah_terima AS sku_jumlah_barang,
									penerimaan.jumlah_terima AS sku_jumlah_terima,
									penerimaan.sku_stock_id,
									penerimaan.sku_stock_expired_date,
									penerimaan.depo_detail_id
									FROM delivery_order_batch
									LEFT JOIN delivery_order ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN delivery_order_detail ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN (SELECT
																pallet_temp.delivery_order_batch_id,
																pallet_temp.depo_detail_id,
																pallet_detail_temp.penerimaan_tipe_id,
																pallet_detail_temp.sku_id,
																pallet_detail_temp.sku_stock_id,
																pallet_detail_temp.sku_stock_expired_date,
																pallet_detail_temp.jumlah_terima,
																SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
														FROM pallet_temp
														LEFT JOIN pallet_detail_temp
														ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
														WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
														GROUP BY pallet_temp.delivery_order_batch_id,
																pallet_temp.depo_detail_id,
																pallet_detail_temp.penerimaan_tipe_id,
																pallet_detail_temp.sku_id,
																pallet_detail_temp.sku_stock_expired_date,
																pallet_detail_temp.jumlah_terima,
																pallet_detail_temp.sku_stock_id) penerimaan
									ON penerimaan.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
										AND penerimaan.sku_id = delivery_order_detail.sku_id
									LEFT JOIN tipe_delivery_order ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									LEFT JOIN sku ON sku.sku_id = delivery_order_detail.sku_id
									WHERE delivery_order_batch.delivery_order_batch_id = '$delivery_order_batch_id'
										AND delivery_order.delivery_order_id = '$delivery_order_id'
										AND ISNULL(penerimaan.sku_stock_qty, 0) > 0
										AND ISNULL(delivery_order_detail.sku_qty, 0) - ISNULL(delivery_order_detail.sku_qty_kirim, 0) > 0");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function get_sku_stock_by_pallet($delivery_order_batch_id)
	{

		$query = $this->db->query("SELECT
									pallet_detail_temp.sku_id,
									pallet_detail_temp.sku_stock_id,
									SUM(pallet_detail_temp.sku_stock_qty) AS sku_stock_qty
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_temp.pallet_id = pallet_detail_temp.pallet_id
									WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'
									GROUP BY pallet_detail_temp.sku_id,
											pallet_detail_temp.sku_stock_id");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function get_data_pallet_by_fdjr($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT * FROM pallet_temp WHERE delivery_order_batch_id = '$delivery_order_batch_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Insert_PenerimaanPenjualanDetail($penerimaan_penjualan_detail_id, $penerimaan_penjualan_id, $delivery_order_id, $data, $skuStockId, $client_wms_id)
	{
		$this->db->set("penerimaan_penjualan_detail_id", $penerimaan_penjualan_detail_id);
		$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		$this->db->set("delivery_order_id", $delivery_order_id);
		$this->db->set("sku_id", $data['sku_id']);
		$this->db->set("sku_jumlah_barang", $data['qty_terima']);
		$this->db->set("sku_jumlah_terima", $data['qty_terima']);
		$this->db->set("principle_id", $data['principle_id']);
		$this->db->set("client_wms_id", $client_wms_id);
		// $this->db->set("penerimaan_tipe_id", $penerimaan_tipe_id);
		// $this->db->set("sku_stock_id", $skuStockId);

		$this->db->insert("penerimaan_penjualan_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_PenerimaanPenjualanDetail2($penerimaan_penjualan_id, $data)
	{
		$this->db->set("penerimaan_penjualan_detail2_id", "NEWID()", FALSE);
		$this->db->set("penerimaan_penjualan_id", $penerimaan_penjualan_id);
		$this->db->set("pallet_id", $data['pallet_id']);

		$this->db->insert("penerimaan_penjualan_detail2");

		$pallet_kode = $this->db->query("select * from pallet_temp where pallet_id = '" . $data['pallet_id'] . "' ")->row(0)->pallet_kode;

		$this->db->set("pallet_generate_detail2_is_aktif", "1");
		$this->db->where("pallet_generate_detail2_kode", $pallet_kode);

		$this->db->update("pallet_generate_detail2");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function cek_pallet_penerimaan($penerimaan_penjualan_id, $pallet_id)
	{
		$this->db->select("*")
			->from("penerimaan_penjualan_detail2")
			->where("penerimaan_penjualan_id", $penerimaan_penjualan_id)
			->where("pallet_id", $pallet_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->num_rows();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Insert_sku_stock_card($sku_stock_card_dokumen_id, $sku_stock_card_dokumen_no, $sku_stock_card_keterangan, $sku_stock_card_jenis, $depo_id, $depo_detail_id, $sku_id, $sku_stock_id, $sku_stock_card_qty, $sku_stock_card_is_posting)
	{
		$sku_stock_card_dokumen_id = $sku_stock_card_dokumen_id == '' ? null : $sku_stock_card_dokumen_id;
		$sku_stock_card_dokumen_no = $sku_stock_card_dokumen_no == '' ? null : $sku_stock_card_dokumen_no;
		$sku_stock_card_keterangan = $sku_stock_card_keterangan == '' ? null : $sku_stock_card_keterangan;
		$sku_stock_card_jenis = $sku_stock_card_jenis == '' ? null : $sku_stock_card_jenis;
		$depo_id = $depo_id == '' ? null : $depo_id;
		$depo_detail_id = $depo_detail_id == '' ? null : $depo_detail_id;
		$sku_id = $sku_id == '' ? null : $sku_id;
		$sku_stock_id = $sku_stock_id == '' ? null : $sku_stock_id;
		$sku_stock_card_qty = $sku_stock_card_qty == '' ? null : $sku_stock_card_qty;
		$sku_stock_card_is_posting = $sku_stock_card_is_posting == '' ? null : $sku_stock_card_is_posting;

		$this->db->set("sku_stock_card_id", "NEWID()", FALSE);
		$this->db->set("sku_stock_card_tanggal", "GETDATE()", FALSE);
		$this->db->set("sku_stock_card_bulan", "MONTH(GETDATE())", FALSE);
		$this->db->set("sku_stock_card_tahun", "YEAR(GETDATE())", FALSE);
		$this->db->set("sku_stock_card_dokumen_id", $sku_stock_card_dokumen_id);
		$this->db->set("sku_stock_card_dokumen_no", $sku_stock_card_dokumen_no);
		$this->db->set("sku_stock_card_keterangan", $sku_stock_card_keterangan);
		// $this->db->set("tipe_mutasi_id", $tipe_mutasi_id);
		$this->db->set("sku_stock_card_jenis", $sku_stock_card_jenis);
		$this->db->set("depo_id", $depo_id);
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("sku_id", $sku_id);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->set("sku_stock_card_qty", $sku_stock_card_qty);
		$this->db->set("sku_stock_card_is_posting", $sku_stock_card_is_posting);
		$this->db->set("sku_stock_card_tgl_create", "GETDATE()", FALSE);
		$this->db->set("sku_stock_card_who_create", $this->session->userdata('pengguna_username'));
		// $this->db->set("sku_stock_card_tgl_posting", $sku_stock_card_tgl_posting);
		// $this->db->set("sku_stock_card_who_posting", $sku_stock_card_who_posting);

		$this->db->insert("sku_stock_card");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function get_pallet_temp($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT 
										pallet_id,
										pallet_jenis_id,
										depo_id,
										rak_lajur_detail_id,
										pallet_kode,
										pallet_tanggal_create,
										pallet_who_create,
										pallet_is_aktif
									FROM pallet_temp 
									WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function get_pallet_detail_temp($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT 
										pallet_detail_temp.pallet_detail_id,
										pallet_detail_temp.pallet_id,
										pallet_detail_temp.sku_id,
										pallet_detail_temp.sku_stock_id,
										pallet_detail_temp.sku_stock_expired_date,
										pallet_detail_temp.sku_stock_qty,
										pallet_detail_temp.penerimaan_tipe_id
									FROM pallet_temp
									LEFT JOIN pallet_detail_temp
									ON pallet_detail_temp.pallet_id = pallet_temp.pallet_id
									WHERE pallet_temp.delivery_order_batch_id = '$delivery_order_batch_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function CheckPalletById($pallet_id)
	{
		$this->db->select("pallet_id")
			->from("pallet")
			->where("pallet_id", $pallet_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = "0";
		} else {
			$query = $query->row(0)->pallet_id;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Insert_Pallet($data)
	{
		$this->db->set("pallet_id", $data['pallet_id']);
		$this->db->set("pallet_jenis_id", $data['pallet_jenis_id']);
		$this->db->set("depo_id", $data['depo_id']);
		$this->db->set("rak_lajur_detail_id", $data['rak_lajur_detail_id']);
		$this->db->set("pallet_kode", $data['pallet_kode']);
		$this->db->set("pallet_tanggal_create", $data['pallet_tanggal_create']);
		$this->db->set("pallet_who_create", $data['pallet_who_create']);
		$this->db->set("pallet_is_aktif", $data['pallet_is_aktif']);

		$this->db->insert("pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $queryinsert;
		return $this->db->last_query();
	}

	public function Insert_PalletDetail($data)
	{
		$this->db->set("pallet_detail_id", $data['pallet_detail_id']);
		$this->db->set("pallet_id", $data['pallet_id']);
		$this->db->set("sku_id", $data['sku_id']);
		$this->db->set("sku_stock_id", $data['sku_stock_id']);
		$this->db->set("sku_stock_expired_date", $data['sku_stock_expired_date']);
		$this->db->set("sku_stock_qty", 0);
		// $this->db->set("penerimaan_tipe_id", $data['penerimaan_tipe_id']);
		$this->db->set("sku_stock_terima", $data['sku_stock_qty']);

		$this->db->insert("pallet_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_rak_lajur_detail_pallet($data)
	{
		//insert delivery_order_progress
		$this->db->set("rak_lajur_detail_pallet_id", "NEWID()", FALSE);
		$this->db->set("rak_lajur_detail_id", $data['rak_lajur_detail_id']);
		$this->db->set("pallet_id", $data['pallet_id']);

		$this->db->insert("rak_lajur_detail_pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Insert_rak_lajur_detail_pallet_his($depo_detail_id, $data)
	{
		//insert delivery_order_progress
		$this->db->set("rak_lajur_detail_pallet_id", "NEWID()", FALSE);
		$this->db->set("depo_id", $this->session->userdata('depo_id'));
		$this->db->set("depo_detail_id", $depo_detail_id);
		$this->db->set("rak_lajur_detail_id", $data['rak_lajur_detail_id']);
		$this->db->set("pallet_id", $data['pallet_id']);
		$this->db->set("tanggal_create", "GETDATE()", FALSE);
		$this->db->set("who_create", $this->session->userdata('pengguna_username'));

		$this->db->insert("rak_lajur_detail_pallet_his");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function Update_Pallet($data)
	{
		$this->db->set("rak_lajur_detail_id", $data['rak_lajur_detail_id']);
		$this->db->where("pallet_id", $data['pallet_id']);

		$this->db->update("pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		// return $queryinsert;
		return $this->db->last_query();
	}

	public function Update_rak_lajur_detail_pallet($data)
	{
		//insert delivery_order_progress
		$this->db->set("rak_lajur_detail_id", $data['rak_lajur_detail_id']);
		$this->db->where("pallet_id", $data['pallet_id']);

		$this->db->update("rak_lajur_detail_pallet");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}

	public function CheckPalletDetailById($pallet_id, $sku_stock_id)
	{
		$this->db->select("pallet_detail_id")
			->from("pallet_detail")
			->where("pallet_id", $pallet_id)
			->where("sku_stock_id", $sku_stock_id);

		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->pallet_detail_id;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Update_PalletDetail($pallet_id, $sku_id, $sku_stock_id, $data)
	{
		// $qty_terima = $this->db->query("SELECT ISNULL(sku_stock_terima,0) AS sku_stock_terima FROM pallet_detail WHERE pallet_id = '$pallet_id' AND sku_id = '$sku_id' AND sku_stock_expired_date = '" . $data['sku_stock_expired_date'] . "' ")->row(0)->sku_stock_terima;
		$qty_terima = $this->db->query("SELECT ISNULL(sku_stock_terima,0) AS sku_stock_terima FROM pallet_detail WHERE pallet_id = '$pallet_id' AND sku_stock_id = '$sku_stock_id' ")->row(0)->sku_stock_terima;
		$qty_terima = $qty_terima + $data['sku_stock_qty'];

		$this->db->set("sku_stock_terima", $qty_terima);

		$this->db->where("pallet_id", $pallet_id);
		$this->db->where("sku_id", $sku_id);
		// $this->db->where("penerimaan_tipe_id", $data['penerimaan_tipe_id']);
		$this->db->where("sku_stock_expired_date", $data['sku_stock_expired_date']);
		// $this->db->where("sku_stock_id", $sku_stock_id);

		$this->db->update("pallet_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryupdate = 1;
		} else {
			$queryupdate = 0;
		}

		return $queryupdate;
		// return $this->db->last_query();
	}

	public function Delete_PalletDetail($delivery_order_batch_id)
	{
		$query = $this->db->query("DELETE FROM pallet_detail_temp WHERE pallet_id IN (SELECT pallet_id FROM pallet_temp WHERE delivery_order_batch_id = '$delivery_order_batch_id') ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}

	public function Delete_Pallet($delivery_order_batch_id)
	{
		$query = $this->db->query("DELETE FROM pallet_temp WHERE delivery_order_batch_id = '$delivery_order_batch_id' ");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}

	public function get_penerimaan_penjualan_id($delivery_order_batch_id)
	{
		$this->db->select("penerimaan_penjualan_id")
			->from("penerimaan_penjualan")
			->where("delivery_order_batch_id", $delivery_order_batch_id);

		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->penerimaan_penjualan_id;
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetPrincipleByPenerimaanPenjualan($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT DISTINCT
										principle.*
									FROM penerimaan_penjualan
                                    LEFT JOIN client_wms_principle
                                    ON client_wms_principle.client_wms_id = penerimaan_penjualan.client_wms_id
									LEFT JOIN principle
									ON client_wms_principle.principle_id = principle.principle_id
									WHERE penerimaan_penjualan.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY principle.principle_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetHeaderPenerimaanPenjualanByDOId($id)
	{
		$query = $this->db->query("SELECT
                                    do.delivery_order_batch_id,
									fdjr.delivery_order_batch_kode,
									do.delivery_order_id,
									do.delivery_order_kode,
									do.sales_order_id,
									so.sales_order_kode,
									so.sales_order_no_po,
									FORMAT(do.delivery_order_tgl_buat_do, 'dd/MM/yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(do.delivery_order_tgl_expired_do, 'dd/MM/yyyy') AS delivery_order_tgl_expired_do,
									FORMAT(do.delivery_order_tgl_surat_jalan, 'dd/MM/yyyy') AS delivery_order_tgl_surat_jalan,
									FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd/MM/yyyy') AS delivery_order_tgl_rencana_kirim,
									do.client_wms_id,
									client_wms.client_wms_nama,
									client_wms.client_wms_alamat,
									do.client_pt_id,
									ISNULL(do.delivery_order_kirim_nama, '') AS delivery_order_kirim_nama,
									ISNULL(do.delivery_order_kirim_alamat, '') AS delivery_order_kirim_alamat,
									ISNULL(do.delivery_order_kirim_telp, '') AS delivery_order_kirim_telp,
									ISNULL(do.delivery_order_kirim_provinsi, '') AS delivery_order_kirim_provinsi,
									ISNULL(do.delivery_order_kirim_kota, '') AS delivery_order_kirim_kota,
									ISNULL(do.delivery_order_kirim_kecamatan, '') AS delivery_order_kirim_kecamatan,
									ISNULL(do.delivery_order_kirim_kelurahan, '') AS delivery_order_kirim_kelurahan,
									ISNULL(do.delivery_order_kirim_kodepos, '') AS delivery_order_kirim_kodepos,
									ISNULL(do.delivery_order_kirim_area, '') AS delivery_order_kirim_area,
									do.principle_id,
									ISNULL(do.delivery_order_ambil_nama, '') AS delivery_order_ambil_nama,
									ISNULL(do.delivery_order_ambil_alamat, '') AS delivery_order_ambil_alamat,
									ISNULL(do.delivery_order_ambil_telp, '') AS delivery_order_ambil_telp,
									ISNULL(do.delivery_order_ambil_provinsi, '') AS delivery_order_ambil_provinsi,
									ISNULL(do.delivery_order_ambil_kota, '') AS delivery_order_ambil_kota,
									ISNULL(do.delivery_order_ambil_kecamatan, '') AS delivery_order_ambil_kecamatan,
									ISNULL(do.delivery_order_ambil_kelurahan, '') AS delivery_order_ambil_kelurahan,
									ISNULL(do.delivery_order_ambil_kodepos, '') AS delivery_order_ambil_kodepos,
									ISNULL(do.delivery_order_ambil_area, '') AS delivery_order_ambil_area,
									do.delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.tipe_delivery_order_id,
									ISNULL(do.delivery_order_keterangan, '') AS delivery_order_keterangan,
									do.delivery_order_status,
									CASE
										WHEN do.delivery_order_status = 'delivered' AND
										do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim'
										WHEN do.delivery_order_status = 'partially delivered' AND
										do.delivery_order_kode LIKE '%/DO/%' THEN 'terkirim sebagian'
										WHEN do.delivery_order_status = 'not delivered' AND
										do.delivery_order_kode LIKE '%/DO/%' THEN 'tidak terkirim'
										WHEN do.delivery_order_kode LIKE '%/DOR/%' THEN 'retur'
										WHEN do.delivery_order_status = 'rescheduled' AND do.delivery_order_kode LIKE '%/DO/%' THEN 'rescheduled'
										ELSE ''
									END AS do_status,
									penerimaan_penjualan.penerimaan_penjualan_id,
									penerimaan_penjualan.penerimaan_penjualan_kode,
									FORMAT(penerimaan_penjualan.penerimaan_penjualan_tgl,'dd-MM-yyyy') AS penerimaan_penjualan_tgl,
									penerimaan_penjualan.depo_detail_id,
									depo_detail.depo_detail_nama,
									penerimaan_penjualan.karyawan_id,
									karyawan.karyawan_nama
									FROM delivery_order do
									LEFT JOIN FAS.dbo.sales_order so
									ON so.sales_order_id = do.sales_order_id 
									LEFT JOIN delivery_order_batch fdjr
									ON do.delivery_order_batch_id = fdjr.delivery_order_batch_id
									LEFT JOIN penerimaan_penjualan
									ON penerimaan_penjualan.delivery_order_batch_id = fdjr.delivery_order_batch_id
									LEFT JOIN karyawan
									ON penerimaan_penjualan.karyawan_id = karyawan.karyawan_id
									LEFT JOIN client_wms
									ON do.client_wms_id = client_wms.client_wms_id
									LEFT JOIN depo_detail
									ON penerimaan_penjualan.depo_detail_id = depo_detail.depo_detail_id
									WHERE do.delivery_order_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDetailPenerimaanPenjualanByDOId($delivery_order_id)
	{
		$query = $this->db->query("SELECT
									delivery_order_batch.delivery_order_batch_id,
									delivery_order_batch.delivery_order_batch_kode,
									delivery_order.delivery_order_id,
									delivery_order.delivery_order_kode,
									FORMAT(delivery_order.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') AS delivery_order_tgl_aktual_kirim,
									delivery_order.delivery_order_kirim_nama,
									principle.principle_kode AS principle,
									principle_brand.principle_brand_nama AS brand,
									delivery_order_detail.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.sku_kemasan,
									sku.sku_satuan,
									delivery_order_detail.sku_qty,
									ISNULL(delivery_order_detail.sku_qty_kirim, 0) AS sku_qty_kirim,
									ISNULL(penerimaan.sku_jumlah_terima, 0) AS sisa_jumlah_terima,
									delivery_order_batch.karyawan_id,
									karyawan.karyawan_nama,
									tipe_delivery_order.tipe_delivery_order_alias,
									penerimaan.kondisi_barang
									FROM delivery_order
									LEFT JOIN delivery_order_detail
									ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN delivery_order_batch
									ON delivery_order_batch.delivery_order_batch_id = delivery_order.delivery_order_batch_id
									LEFT JOIN (SELECT
									delivery_order_id,
									sku_id,
									SUM(sku_jumlah_barang) AS sku_jumlah_barang,
									SUM(sku_jumlah_terima) AS sku_jumlah_terima,
									kondisi_barang
									FROM penerimaan_penjualan_detail
									GROUP BY delivery_order_id,
											sku_id,kondisi_barang) penerimaan
									ON penerimaan.delivery_order_id = delivery_order_detail.delivery_order_id
									AND penerimaan.sku_id = delivery_order_detail.sku_id
									LEFT JOIN sku
									ON delivery_order_detail.sku_id = sku.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									LEFT JOIN karyawan
									ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
									LEFT JOIN tipe_delivery_order
									ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
									WHERE delivery_order.delivery_order_id = '$delivery_order_id'
									ORDER BY delivery_order.delivery_order_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetPalletPenerimaanPenjualanByDOId($delivery_order_id, $penerimaan_penjualan_id)
	{
		$query = $this->db->query("SELECT
										penerimaan_penjualan_detail.delivery_order_id,
										penerimaan_penjualan_detail2.pallet_id,
										pallet.pallet_kode,
										pallet.pallet_jenis_id,
										pallet_jenis.pallet_jenis_nama
									FROM penerimaan_penjualan_detail
									LEFT JOIN penerimaan_penjualan_detail2
									ON penerimaan_penjualan_detail.penerimaan_penjualan_id = penerimaan_penjualan_detail2.penerimaan_penjualan_id
									LEFT JOIN pallet
									ON pallet.pallet_id = penerimaan_penjualan_detail2.pallet_id
									LEFT JOIN pallet_jenis
									ON pallet.pallet_jenis_id = pallet_jenis.pallet_jenis_id
									WHERE penerimaan_penjualan_detail.delivery_order_id = '$delivery_order_id'
									AND penerimaan_penjualan_detail.penerimaan_penjualan_id = '$penerimaan_penjualan_id'
									GROUP BY penerimaan_penjualan_detail.delivery_order_id,
											penerimaan_penjualan_detail2.pallet_id,
											pallet.pallet_kode,
											pallet.pallet_jenis_id,
											pallet_jenis.pallet_jenis_nama
									ORDER BY pallet.pallet_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function search_filter_chosen_sku($brand, $principle, $sku_nama_produk, $sku_kemasan, $sku_satuan, $filter_sku_id)
	{
		$filter_sku_id_str = "";

		if (count($filter_sku_id) > 0) {
			$filter_sku_id_str = "AND sku.sku_id NOT IN (" . implode(",", $filter_sku_id) . ")";
		} else {
			$filter_sku_id_str = "";
		}

		if ($brand == "") {
			$brand = "";
		} else {
			$brand = "AND principle_brand.principle_brand_nama LIKE '%" . $brand . "%' ";
		}

		if ($principle == "") {
			$principle = "";
		} else {
			$principle = "AND principle.principle_kode LIKE '%" . $principle . "%' ";
		}

		if ($sku_nama_produk == "") {
			$sku_nama_produk = "";
		} else {
			$sku_nama_produk = "AND sku.sku_nama_produk LIKE '%" . $sku_nama_produk . "%' ";
		}

		if ($sku_kemasan == "") {
			$sku_kemasan = "";
		} else {
			$sku_kemasan = "AND sku.sku_kemasan LIKE '%" . $sku_kemasan . "%' ";
		}

		if ($sku_satuan == "") {
			$sku_satuan = "";
		} else {
			$sku_satuan = "AND sku.sku_satuan LIKE '%" . $sku_satuan . "%' ";
		}

		$query = $this->db->query("SELECT
									sku.sku_id,
									sku.sku_kode,
									sku.sku_nama_produk,
									sku.principle_id,
									principle.principle_kode AS principle,
									sku.principle_brand_id,
									principle_brand.principle_brand_nama AS brand,
									sku.sku_kemasan,
									sku.sku_satuan,
									sku.sku_induk_id,
									sku_induk.sku_induk_nama AS sku_induk
									FROM sku
									LEFT JOIN sku_induk
									ON sku.sku_induk_id = sku_induk.sku_induk_id
									LEFT JOIN sku_stock
									ON sku.sku_id = sku_stock.sku_id
									LEFT JOIN principle
									ON principle.principle_id = sku.principle_id
									LEFT JOIN principle_brand
									ON principle_brand.principle_brand_id = sku.principle_brand_id
									WHERE sku_stock.sku_id IS NOT NULL
									" . $brand . "
									" . $principle . "
									" . $sku_nama_produk . "
									" . $sku_kemasan . "
									" . $sku_satuan . "
									" . $filter_sku_id_str . "
									GROUP BY sku.sku_id,
											sku.sku_kode,
											sku.sku_nama_produk,
											sku.principle_id,
											principle.principle_kode,
											sku.principle_brand_id,
											principle_brand.principle_brand_nama,
											sku.sku_kemasan,
											sku.sku_satuan,
											sku.sku_induk_id,
											sku_induk.sku_induk_nama
									ORDER BY principle.principle_kode, sku.sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Exec_proc_sku_konversi_group_canvas($sku_konversi_group, $sku_qty_composite)
	{

		$query = $this->db->query("Exec proc_sku_konversi_group_canvas '$sku_konversi_group',$sku_qty_composite");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_data_sku_by_group($sku_konversi_group)
	{

		$query = $this->db->query("SELECT * FROM sku where sku_konversi_group = '$sku_konversi_group' ORDER BY sku_konversi_level DESC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_delivery_order_detail_by_id($list_do)
	{

		if (count($list_do) > 0) {

			$query = $this->db->query("SELECT 
											do.delivery_order_id,
											sku.sku_konversi_group,
											SUM(ISNULL(do.sku_qty * sku.sku_konversi_faktor, 0)) as sku_qty_composite,
											SUM(ISNULL(do.sku_qty_kirim * sku.sku_konversi_faktor, 0)) as sku_qty_kirim_composite
										FROM delivery_order_detail do
										LEFT JOIN sku
										ON sku.sku_id = do.sku_id
										WHERE CONVERT(NVARCHAR(36), delivery_order_id) IN (" . implode(",", $list_do) . ")
										GROUP BY do.delivery_order_id,
												 sku.sku_konversi_group");

			if ($query->num_rows() == 0) {
				$query = array();
			} else {
				$query = $query->result_array();
			}
		} else {
			$query = array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function getDataSKUCanvas($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
				dod2.delivery_order_detail2_id
				,a.delivery_order_detail_id
				,a.delivery_order_id
				,c.sku_id
				,c.sku_kode
				,c.sku_nama_produk
				,dod2.sku_expdate
				,dod2.sku_qty_composite
				,ISNULL(dod2.sku_qty_composite, 0) - ISNULL(d.qtykonversi, 0) AS sku_qty
				FROM delivery_order_detail a
				INNER JOIN sku b
				ON a.sku_id = b.sku_id
				INNER JOIN delivery_order_detail2 dod2
				ON a.delivery_order_detail_id = dod2.delivery_order_detail_id
				INNER JOIN (SELECT
				*
				FROM sku
				WHERE sku_konversi_faktor = 1) c
				ON b.sku_konversi_group = c.sku_konversi_group
				LEFT JOIN (SELECT
				sku2.sku_id
				,SUM(ISNULL(a.sku_qty_kirim, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS qtykonversi
				FROM delivery_order_detail_canvas a
				INNER JOIN sku
				ON a.sku_id = sku.sku_id
				INNER JOIN sku sku2
				ON sku.sku_konversi_group = sku2.sku_konversi_group
				WHERE delivery_order_batch_id = '$delivery_order_batch_id'
				AND sku2.sku_konversi_faktor = 1
				GROUP BY sku2.sku_id) d
				ON c.sku_id = d.sku_id
				WHERE a.delivery_order_batch_id = '$delivery_order_batch_id'
				AND ISNULL(dod2.sku_qty_composite, 0) - ISNULL(d.qtykonversi, 0) > 0");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function Update_penerimaan_penjualan_sku_stock($penerimaan_penjualan_id, $sku_id, $sku_stock_id, $sku_expired_date)
	{
		//insert delivery_order_progress
		$this->db->set("sku_expired_date", $sku_expired_date);
		$this->db->set("sku_stock_id", $sku_stock_id);
		$this->db->where("sku_id", $sku_id);
		$this->db->where("penerimaan_penjualan_id", $penerimaan_penjualan_id);

		$this->db->update("penerimaan_penjualan_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
		// return $this->db->last_query();
	}
}
