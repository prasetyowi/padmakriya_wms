<?php

class M_Picking extends CI_Model
{
	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->load->model('M_Function');
		$this->load->model('M_SKUStock');
	}

	public function generateKode($date, $prefix, $unit)
	{
		// $this->db->trans_start();
		$sql = "DECLARE	
		            @ResultKode varchar(50)
                
                EXEC GenCodeGeneral 
                    @TglTrans = '" . $date . "',
                    @Prefik = '" . $prefix . "',
                    @unit = '" . $unit . "',
                    @ResultKode = @ResultKode OUTPUT
                    

                SELECT	@ResultKode as 'ResultKode'
               

                ";
		// delete FROM TempKode
		// INSERT INTO TempKode VALUES(@ResultKode)
		$result = $this->db->query($sql);
		// $result1 = $this->db->select("*")->from('TempKode')->get();;

		// $result1 = $this->db->query("SELECT	@ResultKode as 'ResultKode'");
		// $result1 = $this->db->query("SELECT	@ResultKode as 'ResultKode'");
		// $this->db->trans_complete(); 
		return $sql;

		// $result1 = $this->db->result();
		// if ($result !== NULL) {
		//     return TRUE;
		// }

	}

	public function Get_PickingArea($id)
	{
		$query = $this->db->query("SELECT
									area.area_nama
									FROM delivery_order_area
									LEFT JOIN area
									ON area.area_id = delivery_order_area.area_id
									WHERE delivery_order_area.delivery_order_batch_id = '$id'
									ORDER BY area.area_nama ASC");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Depo_By_Depo_ID($depo_id)
	{
		$this->db->select("*")
			->from("depo")
			->where("depo_id", $depo_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_DepoD_By_Depo_ID($depo_id)
	{
		$this->db->select("*")
			->from("depo_detail")
			->where("depo_id", $depo_id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	function getDataPickingListDetail($id)
	{
		$query =  $this->db->select("pk.*,
                                    typ.tipe_delivery_order_alias,
                                    area.area_nama,FORMAT(pk.picking_list_create_tgl, 'yyyy-MM-dd') as tgl_picking,
                                    FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') as tgl_kirim,
                                    FORMAT(dob.delivery_order_batch_create_tgl, 'yyyy-MM-dd') as tgl_fdjr,
                                    depo_d.depo_detail_nama,
                                    CASE
                                        WHEN dob.delivery_order_batch_is_need_packing = 1 then 'Yes'
                                        ELSE 'No'
                                    END as packed,
                                    eks.tipe_ekspedisi_nama,
                                    knd.kendaraan_kode as armada,
                                    kry.karyawan_nama,
                                    dob.kendaraan_volume_cm3_terpakai,
                                    dob.kendaraan_berat_gr_terpakai,
                                    dob.kendaraan_volume_cm3_sisa,
                                    dob.kendaraan_berat_gr_sisa,
                                    dob.kendaraan_volume_cm3_max,
                                    dob.kendaraan_berat_gr_max,
                                    dob.delivery_order_batch_kode as do_batch_kode,
									dob.delivery_order_batch_id,
									png.tipe_pengiriman_nama_tipe as tipe_pengiriman, 
									dob.delivery_order_batch_tipe_layanan_nama as nama_layanan,
									dob.delivery_order_batch_is_need_packing as ispacking, 
									area.area_nama")
			->from('picking_list as pk')
			->join('delivery_order_batch dob', 'dob.delivery_order_batch_id = pk.delivery_order_batch_id', 'left')
			->join('tipe_delivery_order as typ', 'typ.tipe_delivery_order_id = pk.tipe_delivery_order_id', 'left')
			->join('tipe_pengiriman as png', 'png.tipe_pengiriman_id = dob.tipe_pengiriman_id', 'left')
			->join('tipe_ekspedisi as eks', 'eks.tipe_ekspedisi_id = dob.tipe_ekspedisi_id', 'left')
			->join('depo_detail as depo_d', 'depo_d.depo_detail_id = pk.depo_detail_id', 'left')
			->join('area', 'area.area_id = pk.area_id', 'left')
			->join('kendaraan as knd', 'knd.kendaraan_id = dob.kendaraan_id', 'left')
			->join('karyawan as kry', 'kry.karyawan_id = dob.karyawan_id', 'left')
			->where('pk.picking_list_id', $id)
			->get();
		// 1 row
		// return object
		return $query->row();
	}

	function getDataPickingList()
	{
		$query =  $this->db->select("pk.*,typ.tipe_delivery_order_alias,FORMAT(pk.picking_list_create_tgl, 'dd-MM-yyyy') as tgl_picking,dob.delivery_order_batch_kode as do_batch_kode,png.tipe_pengiriman_nama_tipe as tipe_pengiriman, dob.delivery_order_batch_tipe_layanan_nama as nama_layanan,dob.delivery_order_batch_is_need_packing as ispacking, karyawan.karyawan_nama, dob.delivery_order_batch_id")
			->from('picking_list as pk')
			->join('delivery_order_batch dob', 'dob.delivery_order_batch_id = pk.delivery_order_batch_id', 'left')
			->join('delivery_order_area', 'dob.delivery_order_batch_id = delivery_order_area.delivery_order_batch_id', 'left')
			->join('tipe_pengiriman as png', 'png.tipe_pengiriman_id = dob.tipe_pengiriman_id', 'left')
			->join('karyawan', 'dob.karyawan_id = karyawan.karyawan_id', 'left')
			->join('tipe_delivery_order as typ', 'typ.tipe_delivery_order_id = pk.tipe_delivery_order_id', 'left')
			->where('pk.depo_id', $this->session->userdata('depo_id'))
			->get();

		return $query->result_array();
	}


	// function getDataPickingListSearch($area_id, $pick_id, $status, $tgl, $tgl2, $tipe_delivery_order_id, $tipe_layanan, $tipe_pengiriman)
	function getDataPickingListSearch($tgl, $tgl2, $driver, $fdjr)
	{
		$depo_id = $this->session->userdata('depo_id');
		$sql = "";
		$sql .= "SELECT DISTINCT  
                pk.*,typ.tipe_delivery_order_alias,
                FORMAT(pk.picking_list_create_tgl, 'dd-MM-yyyy') as tgl_picking,
                dob.delivery_order_batch_kode as do_batch_kode,
                png.tipe_pengiriman_nama_tipe as tipe_pengiriman, 
                dob.delivery_order_batch_tipe_layanan_nama as nama_layanan,
                dob.delivery_order_batch_is_need_packing as ispacking, 
                karyawan.karyawan_nama, 
                dob.delivery_order_batch_id
                FROM picking_list as pk
                LEFT JOIN delivery_order_batch dob ON dob.delivery_order_batch_id = pk.delivery_order_batch_id
                LEFT JOIN delivery_order_area ON dob.delivery_order_batch_id = delivery_order_area.delivery_order_batch_id
                LEFT JOIN tipe_pengiriman as png ON png.tipe_pengiriman_id = dob.tipe_pengiriman_id
                LEFT JOIN karyawan ON dob.karyawan_id = karyawan.karyawan_id
                LEFT JOIN tipe_delivery_order as typ ON typ.tipe_delivery_order_id = pk.tipe_delivery_order_id
                WHERE FORMAT(pk.picking_list_create_tgl, 'yyyy-MM-dd') BETWEEN '$tgl' and '$tgl2'
                AND pk.depo_id = '$depo_id' 
				ORDER BY pk.picking_list_create_tgl DESC";

		if ($driver != "") {
			$sql .= "AND dob.karyawan_id = '$driver'";
		}
		if ($fdjr != "") {
			$sql .= "AND dob.delivery_order_batch_id = '$fdjr'";
		}
		// if ($pick_id != null) {
		// 	$sql .= "AND pk.picking_list_kode LIKE '%$pick_id%' ";
		// }
		// if ($area_id != null) {
		// 	$sql .= "AND delivery_order_area.area_id = '$area_id'";
		// }
		// if ($status != null) {
		// 	$sql .= "AND pk.picking_list_status = '$status' ";
		// }
		// if ($tipe_delivery_order_id != null) {
		// 	$sql .= "AND pk.tipe_delivery_order_id = '$tipe_delivery_order_id'";
		// }
		// if ($tipe_layanan != null) {
		// 	$sql .= "AND dob.delivery_order_batch_tipe_layanan_no = '$tipe_layanan' ";
		// }
		// if ($tipe_pengiriman != null) {
		// 	$sql .= "AND dob.tipe_pengiriman_id = '$tipe_pengiriman' ";
		// }

		$query = $this->db->query($sql);

		return $query->result();
	}



	public function setDataDOBatch()
	{
		$this->db->set("DOB_ID", "NEWID()", false);
		$this->db->set("DOB_No", 'DOB/' . Date('Y-m-d'));
		$this->db->set("DOB_Tgl", Date('Y-m-d'));
		$this->db->set("DOB_TipePengiriman", '888A5D1E-63AC-461D-B13E-47358CEFD86C');
		$this->db->set("DOB_Status", '');

		$this->db->insert('DeliveryOrderBatch');

		print_r($this->db->error());
		die;
	}

	public function SavePickingList($data, $do_id, $prioritas_stock, $dataDoD, $dataSkuED)
	{
		$this->db->trans_start();

		$newID1 = $this->M_Function->Get_NewID();
		$newID = $newID1[0]['kode'];
		// insert pikcling list
		$this->db->set('picking_list_id', $data['picking_list_id']);
		$this->db->set('picking_list_kode', $data['picking_list_kode']);
		$this->db->set('area_id', $data['area_id']);
		$this->db->set('tipe_delivery_order_id', $data['tipe_delivery_order_id']);
		$this->db->set('delivery_order_batch_id', $data['delivery_order_batch_id']);
		$this->db->set('picking_list_tgl_kirim', $data['picking_list_tgl_kirim']);
		$this->db->set('picking_list_keterangan', $data['picking_list_keterangan']);
		// $this->db->set('picking_list_status' , $data['picking_list_status']);
		$this->db->set('picking_list_status', 'Open');
		$this->db->set('depo_id', $data['depo_id']);
		$this->db->set('depo_detail_id', $data['depo_detail_id']);
		$this->db->set('unit_mandiri_id', $data['unit_mandiri_id']);
		$this->db->set('picking_list_create_tgl', $data['picking_list_create_tgl']);
		$this->db->set('picking_list_create_who', $data['picking_list_create_who']);

		$this->db->insert('picking_list');

		// update batch do, delivery_order_batch_status, masukkan no picklist
		$this->db->set('picking_list_id', $data['picking_list_id']);
		$this->db->set('delivery_order_batch_status', 'in progress item request');
		$this->db->where('delivery_order_batch_id', $data['delivery_order_batch_id']);
		$this->db->update('delivery_order_batch');

		// insert detail   
		foreach ($do_id as $key => $item) {
			$newIDDet1 = $this->M_Function->Get_NewID();
			$picking_list_detail_id = $newIDDet1[0]['kode'];

			$this->db->set('picking_list_detail_id', $picking_list_detail_id);
			$this->db->set('picking_list_id', $data['picking_list_id']);
			$this->db->set('delivery_order_id', $item);

			$this->db->insert('picking_list_detail');

			$do_id_h = $item;
			// jika ada prioritas stock maka update tbl do
			if ($prioritas_stock[$key] > 0) {
				$this->db->set('delivery_order_prioritas_stock', $prioritas_stock[$key]);
				$this->db->where('delivery_order_id', $item);
				$this->db->update('delivery_order');
				# code...
			}
			// update delivery_order_status di tbl delivery order
			$this->db->set('delivery_order_status', ' in progress item request');
			$this->db->where('delivery_order_id', $item);
			$this->db->update('delivery_order');
			// insert ke table delivery_order_progress id status in progress item request
			$this->db->set('delivery_order_progress_id', "NewID()", FALSE);
			$this->db->set('delivery_order_id', $item);
			$this->db->set('status_progress_id', '37E633E4-593C-4E05-8541-D121D974F167');
			$this->db->set('status_progress_nama', 'in progress item request');
			$this->db->set('delivery_order_progress_create_who', $this->session->userdata('pengguna_username'));
			$this->db->set('delivery_order_progress_create_tgl', "GETDATE()", FALSE);
			$this->db->insert('delivery_order_progress');
		}
		// caall function 
		$this->update_prioritas_stock($data['picking_list_id']);

		// get data pickinglist_detail, order by prioritas stock DO
		$listDo = $this->db->select("pk.*")->from('picking_list_detail as pk')
			->join('delivery_order as do', 'do.delivery_order_id = pk.delivery_order_id')
			->where('pk.picking_list_id', $data['picking_list_id'])
			->order_by('do.delivery_order_prioritas_stock')
			->get();

		$dataDO = $listDo->result_array();

		foreach ($dataDO as $key => $value) {
			$doID = $value['delivery_order_id'];
			$picking_list_detail_id = $value['picking_list_detail_id'];
			foreach ($dataDoD as $key => $item) {

				$sku_id = $item['sku_id'];
				$do_id = $item['do_id'];
				$dod_id = $item['dod_id'];
				// cek apakah do_id sama dengan di detail pertama
				if ($do_id != $doID) {
					continue;
				}
				$sisaQty = $item['sku_qty'];
				$qtyDet = $item['sku_qty'];

				if ($qtyDet < 0) {
					continue;
					# code...
				}
				// jika tidak req ed
				if ($item['chkED'] == 0) {
					# code...
					$sisaQty = $qtyDet;
					// get data sku stock 
					$dataSKUStock = $this->M_SKUStock->findBySKUIdDepo($sku_id, $data['depo_id'], $data['depo_detail_id']);
					// $tt = 1;
					foreach ($dataSKUStock as $key => $value) {
						# code...
						$qtyStock = $value['sku_stock_awal'] + $value['sku_stock_masuk'] - $value['sku_stock_saldo_alokasi'] - $value['sku_stock_keluar'];
						$sku_stock_id = $value['sku_stock_id'];

						if ($qtyStock >= $sisaQty) {

							$qtyDet2 = $sisaQty;

							$newIDDet2 = $this->M_Function->Get_NewID();
							$newIDDet1 = $newIDDet2[0]['kode'];

							$this->db->set('picking_list_detail_2_id', $newIDDet1);
							$this->db->set('picking_list_detail_id', $picking_list_detail_id);
							$this->db->set('delivery_order_id', $do_id);
							$this->db->set('sku_id', $sku_id);
							$this->db->set('sku_stock_id', $sku_stock_id);
							$this->db->set('sku_qty_order', $qtyDet2);
							$this->db->insert('picking_list_detail_2');

							// cek stok_alokasi yg sudah ada
							$query =  $this->db->select("sku_stock_alokasi,sku_stock_saldo_alokasi")
								->from('sku_stock')
								->where('sku_stock_id', $sku_stock_id)
								->get();

							$dataStockAlokasi1 = $query->row();

							//update alokasi stock sku
							$sisaAlokasi = $dataStockAlokasi1->sku_stock_alokasi + $qtyDet2;
							$sisaSaldoAlokasi = $dataStockAlokasi1->sku_stock_saldo_alokasi + $qtyDet2;
							$this->db->set('sku_stock_alokasi', $sisaAlokasi);
							$this->db->set('sku_stock_saldo_alokasi', $sisaSaldoAlokasi);
							$this->db->where('sku_stock_id', $sku_stock_id);
							$this->db->update('sku_stock');

							# break...
							break;
						} else {
							$sisaQty -= $qtyStock;
							$qtyDet2 = $qtyStock;

							$newIDDet2 = $this->M_Function->Get_NewID();
							$newIDDet1 = $newIDDet2[0]['kode'];

							$this->db->set('picking_list_detail_2_id', $newIDDet1);
							$this->db->set('picking_list_detail_id', $picking_list_detail_id);
							$this->db->set('delivery_order_id', $do_id);
							$this->db->set('sku_id', $sku_id);
							$this->db->set('sku_stock_id', $sku_stock_id);
							$this->db->set('sku_qty_order', $qtyDet2);
							$this->db->insert('picking_list_detail_2');

							// cek stok_alokasi yg sudah ada
							$query =  $this->db->select("sku_stock_alokasi,sku_stock_saldo_alokasi")
								->from('sku_stock')
								->where('sku_stock_id', $sku_stock_id)
								->get();

							$dataStockAlokasi = $query->row();

							//update alokasi stock sku
							$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qtyDet2;
							$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qtyDet2;
							$this->db->set('sku_stock_alokasi', $sisaAlokasi);
							$this->db->set('sku_stock_saldo_alokasi', $sisaSaldoAlokasi);
							$this->db->where('sku_stock_id', $sku_stock_id);
							$this->db->update('sku_stock');
							if ($sisaQty <= 0) {
								break;
							};
						}
					}
				} else {
					foreach ($dataSkuED as $key => $item) {
						if ($item['dod_id'] == $dod_id) {
							# code...
							$newIDDet2 = $this->M_Function->Get_NewID();
							$newIDDet1 = $newIDDet2[0]['kode'];

							// cek stok_alokasi yg sudah ada
							$query =  $this->db->select("*")
								->from('sku_stock')
								->where('sku_stock_id', $item['sku_stock_id'])
								->get();

							$dataStockAlokasi = $query->row();

							$qtySisaStock = $dataStockAlokasi->sku_stock_awal + $dataStockAlokasi->sku_stock_masuk - $dataStockAlokasi->sku_stock_saldo_alokasi - $dataStockAlokasi->sku_stock_keluar;
							$qty = $item['qty'];

							if ($qtySisaStock < $qty) {
								$qty = 0;
							}

							$this->db->set('picking_list_detail_2_id', $newIDDet1);
							$this->db->set('picking_list_detail_id', $picking_list_detail_id);
							$this->db->set('delivery_order_id', $do_id);
							$this->db->set('sku_id', $sku_id);
							$this->db->set('sku_stock_id', $item['sku_stock_id']);
							$this->db->set('sku_qty_order', $qty);
							$this->db->insert('picking_list_detail_2');


							//update alokasi stock sku
							$sisaAlokasi = $dataStockAlokasi->sku_stock_alokasi + $qty;
							$sisaSaldoAlokasi = $dataStockAlokasi->sku_stock_saldo_alokasi + $qty;
							$this->db->set('sku_stock_alokasi', $sisaAlokasi);
							$this->db->set('sku_stock_saldo_alokasi', $sisaSaldoAlokasi);
							$this->db->where('sku_stock_id', $item['sku_stock_id']);
							$this->db->update('sku_stock');
							// return $sisaAlokasi;
						} else {
							continue;
						}
					}
				}
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			return 0;
		}


		return 1;
		// return $newID;
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}
	// do batch yg belum jadi picking
	public function getListDoBatch()
	{
		$listDoBatch = $this->db->select("*")->from('delivery_order_batch')->where('depo_id', $this->session->userdata('depo_id'))->where('picking_list_id', null)->get();
		return $listDoBatch->result_array();
	}
	// do batch yg belum jadi pick order
	public function getListDoBatchPick()
	{
		$listDoBatch = $this->db->select("*")->from('delivery_order_batch')->where('depo_id', $this->session->userdata('depo_id'))->where('picking_order_id is NOT NULL')->get();
		return $listDoBatch->result_array();
	}

	public function getListDOBatchFilter($dateStart, $dateEnd, $tipePengiriman, $gudangId)
	{
		$queryGudang = $this->db->select("GUDANGVA_ID")->from('Gudang1')->where('Gudang_ID', $gudangId)->get();
		$resultGudangVa = $queryGudang->result_array();

		$gudangVAArray = [];
		foreach ($resultGudangVa as $gudangVa) {
			$gudangVAArray[] = $gudangVa['GUDANGVA_ID'];
		}


		$listDoBatch = $this->db->select("dob.*")->from('DeliveryOrderBatch as dob')
			->join('DeliveryOrder as do', 'do.dob_id = dob.dob_id')
			->join('DeliveryOrderDetail as dod', 'dod.do_id = do.do_id')
			->where('dob.DOB_TipePengiriman', $tipePengiriman)
			->where_in('dod.GudangVA_ID', $gudangVAArray)
			->where('dob.DOB_Tgl >=', $dateStart)
			->where('dob.DOB_Tgl <=', $dateEnd)
			->group_by(array("dob.DOB_ID", "dob.DOB_No", "dob.DOB_Tgl", "dob.DOB_TipePengiriman", "dob.DOB_Status"))
			->get();

		return $listDoBatch->result_array();
	}

	public function getListPengiriman()
	{
		$dataListArmada = $this->db->select("*")->from('tipe_pengiriman')->get();
		return $dataListArmada->result_array();
	}
	public function getListLayanan()
	{
		$dataListArmada = $this->db->select("*")->from('tipe_layanan')->get();
		return $dataListArmada->result_array();
	}

	public function getArea()
	{
		$dataListArmada = $this->db->select("*")->from('area')->order_by('area_nama')->get();
		return $dataListArmada->result_array();
	}

	public function getDriver()
	{
		$query = $this->db->query("SELECT DISTINCT dob.karyawan_id, k.karyawan_nama 
									FROM delivery_order_batch AS dob
									LEFT JOIN karyawan AS k ON dob.karyawan_id = k.karyawan_id");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getFDJRByDriver($driver)
	{
		$driverVal = "";
		if ($driver != "") {
			$driverVal = "WHERE karyawan_id = '$driver'";
		} else {
			$driverVal = "";
		}

		$query = $this->db->query("SELECT delivery_order_batch_id, delivery_order_batch_kode FROM delivery_order_batch $driverVal");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function getTipeDo()
	{
		$dataListArmada = $this->db->select("*")->from('tipe_delivery_order')->order_by('tipe_delivery_order_alias')->get();
		return $dataListArmada->result_array();
	}

	public function getListDO($batchId = null)
	{
		$query = $this->db->select("do.*, pldo.NoResi as NoResi, pl.NoSuratTugasKurir, pl.PickList_ID")
			->from('DeliveryOrder as do')
			->join('PickListDO as pldo', 'do.DO_ID = pldo.DO_ID', 'left')
			->join('PickList as pl', 'pldo.PickingList_ID = pl.PickList_ID', 'left');
		if (strlen($batchId) > 0) {
			$query = $query->where('do.DOB_ID', $batchId);
		}

		$data = $query->get();

		return $data->result_array();
	}

	public function getPickingDetailSKU($picking_list_id, $do_id)
	{
		$data = $this->db->select("pk.*
                                    ,sku_stock.sku_induk_id
                                    ,sku_induk.sku_induk_kode
                                    ,sku_induk.sku_induk_nama
                                    ,sku.sku_nama_produk
                                    ,sku.sku_varian
                                    ,sku.sku_kemasan
                                    ,sku.sku_satuan
                                    ,principle.principle_kode,
                                    ,principle.principle_nama,
                                    ,principle_brand.principle_brand_nama,
                                    ,principle_brand.principle_brand_id,
                                    ,FORMAT(sku_stock_expired_date, 'dd-MM-yyyy') as ed,
                                    dtl.sku_qty_order
                            ")
			->from('picking_list_detail as pk')
			->join('picking_list_detail_2 as dtl', 'dtl.picking_list_detail_id = pk.picking_list_detail_id', 'left')
			->join('sku_stock', 'dtl.sku_stock_id = sku_stock.sku_stock_id', 'left')
			->join('sku', 'sku.sku_id = sku_stock.sku_id', 'left')
			->join('sku_induk', 'sku.sku_induk_id = sku_induk.sku_induk_id', 'left')
			->join('principle', 'principle.principle_id = sku.principle_id', 'left')
			->join('principle_brand', 'principle_brand.principle_brand_id = sku.principle_brand_id', 'left')
			->where('pk.picking_list_id', $picking_list_id)
			->where('pk.delivery_order_id', $do_id)
			->get();
		return $data->result_array();
	}
	public function getDoById($doID)
	{
		$data = $this->db->select("*")->from('DeliveryOrder')->where('DO_ID', $doID)->get();
		return $data->row();
	}

	public function getDoDById($doId)
	{
		$data = $this->db->select("dod.*,sku.sku_kemasan,sku.sku_satuan,sku.sku_kode_sku_principle,sku_induk.sku_induk_kode")
			->from('delivery_order_detail as dod')
			->join('sku', 'dod.sku_id = sku.sku_id', 'left')
			->join('sku_induk', 'sku_induk.sku_induk_id = sku.sku_induk_id', 'left')
			// ->join('sku_stock', 'sku.sku_id = sku_stock.sku_id', 'left')
			->where('dod.delivery_order_id', $doId)
			->get();

		return $data->result_array();
	}

	public function getDoByBatchId($doBatchId, $PickingListID)
	{
		$query = $this->db->query("SELECT delivery_order_id from picking_list_detail where picking_list_id = '$PickingListID'")->result_array();

		$arr_picking_list_id = [];
		foreach ($query as $value) {
			$arr_picking_list_id[] = $value['delivery_order_id'];
		}

		// jika di detail ada ed return 1
		$data = $this->db->distinct()->select("delivery_order.*,
                            (CASE
                                WHEN dod.sku_request_expdate = 1 THEN 1
                                ELSE 0
                            END) as req_ed,
                            typ.tipe_delivery_order_alias,
                            cs.client_pt_nama as customer_nama,cs.client_pt_alamat as customer_alamat,cs.client_pt_telepon as customer_telepon")
			->from('delivery_order')
			->join('tipe_delivery_order as typ', 'typ.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id', 'left')
			->join('client_pt as cs', 'cs.client_pt_id = delivery_order.client_pt_id')
			->join('delivery_order_detail as dod', 'dod.delivery_order_id = delivery_order.delivery_order_id AND dod.sku_request_expdate = 1', 'left')


			->where('delivery_order.delivery_order_batch_id', $doBatchId)
			->where_in('delivery_order.delivery_order_id', $arr_picking_list_id)
			->where_not_in("typ.tipe_delivery_order_nama", array('Retur'))
			// ->group_by()
			->get();
		return $data->result_array();
	}


	public function getDoBatchById($doBatchId)
	{
		$data = $this->db->select("doB.*,lyn.*,typ.tipe_delivery_order_alias,depoD.depo_detail_nama,area.area_nama, eks.tipe_ekspedisi_nama,FORMAT(doB.delivery_order_batch_tanggal, 'dd-MM-yyyy') as tgl_doBatch,FORMAT(doB.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') as tgl_kirim,png.tipe_pengiriman_nama_tipe as nama_pengiriman,kry.karyawan_nama,knd.kendaraan_kode as armada,knd.kendaraan_kap_vol,knd.kendaraan_kap_weight")
			->from('delivery_order_batch as doB')
			->join('tipe_delivery_order as typ', 'typ.tipe_delivery_order_id = doB.tipe_delivery_order_id', 'left')
			->join('tipe_layanan as lyn', 'lyn.tipe_layanan_kode = doB.delivery_order_batch_tipe_layanan_no', 'left')
			->join('tipe_pengiriman as png', 'png.tipe_pengiriman_id = doB.tipe_pengiriman_id', 'left')
			->join('kendaraan as knd', 'knd.kendaraan_id = doB.kendaraan_id', 'left')
			->join('karyawan as kry', 'kry.karyawan_id = doB.karyawan_id', 'left')
			->join('tipe_ekspedisi as eks', 'eks.tipe_ekspedisi_id = dob.tipe_ekspedisi_id', 'left')
			->join('area', 'area.area_id = dob.area_id', 'left')
			->join('depo_detail depoD', 'depoD.depo_detail_id = dob.depo_detail_id', 'left')
			->where('doB.delivery_order_batch_id', $doBatchId)

			->get();
		// get 1 row
		return $data->row();
	}

	public function GetDoBatchByArea($area_id, $tipe)
	{
		$data = $this->db->select("*")
			->from('delivery_order_batch')
			->where('area_id', $area_id)
			->where('delivery_order_batch_tipe_batch', $tipe)
			->get();

		return $data->result_array();
	}

	public function getSKUStockBySKUID($skuID, $depoDID, $depoID)
	{
		$data = $this->db->select("sku_stock_id
                                ,sku_stock.sku_induk_id
                                ,depo.depo_nama 
                                ,depo_detail.depo_detail_nama
                                ,sku_induk.sku_induk_kode
                                ,sku_induk.sku_induk_nama
                                ,sku.sku_nama_produk
                                ,sku.sku_varian
                                ,sku.sku_kemasan
                                ,sku.sku_satuan
                                ,principle.principle_kode
                                ,principle.principle_nama
                                ,principle_brand.principle_brand_nama

                                ,FORMAT(sku_stock_expired_date, 'dd-MM-yyyy') as ed
                                ,sku_stock_awal
                                ,sku_stock_masuk
                                ,sku_stock_alokasi
                                ,sku_stock_saldo_alokasi
                                ,sku_stock_keluar
                                ,sku_stock_akhir
                                ,sku_stock_is_jual")
			->from('sku_stock')
			->join('depo', 'depo.depo_id = sku_stock.depo_id', 'left')
			->join('depo_detail', 'depo_detail.depo_detail_id = sku_stock.depo_detail_id', 'left')
			->join('sku', 'sku.sku_id = sku_stock.sku_id', 'left')
			->join('sku_induk', 'sku.sku_induk_id = sku_induk.sku_induk_id', 'left')
			->join('principle', 'principle.principle_id = sku.principle_id', 'left')
			->join('principle_brand', 'principle_brand.principle_brand_id = sku.principle_brand_id', 'left')
			->where('sku_stock.sku_id', $skuID)
			->where('sku_stock.depo_id', $depoID)
			->where('sku_stock.depo_detail_id', $depoDID)
			->order_by('sku_stock_expired_date')
			->get();
		return $data->result_array();
	}

	public function getLokasiSKU($detailId)
	{
		$data = $this->db->select("dod.*, sku.SKU_Kode as sku_kode, skuUnit.SKU_ExpireDate as expiredDate, gudang.kode_gudang as kodeGudang")
			->from('DeliveryOrderDetail as dod')
			->join('SKU as sku', 'dod.SKU_ID = sku.SKU_ID', 'inner')
			->join('SKU_UnitMandiri as skuUnit', 'sku.SKU_ID = skuUnit.SKU_ID', 'inner')
			->join('Gudang1 as gudang1', 'skuUnit.GUDANGVA_ID = gudang1.GUDANGVA_ID', 'inner')
			->join('Gudang as gudang', 'gudang1.Gudang_ID = gudang.GUDANG_ID', 'inner')
			->where('DODetail_ID', $detailId)->get();
		return $data->result_array();
	}

	public function getLastNumberPickList()
	{
		$data = $this->db->select('count(PickList_ID) as total')->from('PickList')
			->where('PickList_Date >= ', Date('Y-m-d') . ' 00:00:00')
			->where('PickList_Date <= ', Date('Y-m-d') . ' 23:59:59')
			->get();
		return $data->row();
	}

	public function getLastNumberPickListD()
	{
		$data = $this->db->select('count(PickListD_ID) as total')->from('PickListD')
			->where('PickListD_Kode LIKE ', '%' . Date('Ymd') . '%')
			->get();
		return $data->row();
	}

	public function getLastId()
	{
		$data = $this->db->select("*")
			->from('PickList')
			->order_by('PickList_Date', "DESC")
			->limit(1)
			->get();

		$result = $data->result_array();
		return $result[0]['PickList_ID'];
	}

	public function getLastIdD()
	{
		$data = $this->db->select("*")
			->from('PickListD')
			->order_by('PickListD_Kode', "DESC")
			->limit(1)
			->get();

		$result = $data->result_array();
		return $result[0]['PickLIstD_ID'];
	}

	public function insertPickListDO($data)
	{
		$this->db->set("PickingListDO_ID", "NEWID()", false);
		$this->db->set("PickingList_ID", $data['PickingList_ID']);
		$this->db->set("DO_ID", $data['DO_ID']);
		$this->db->set("DO_No", $data['DO_No']);
		$this->db->set("NoResi", $data['NoResi']);
		$this->db->set("Prioritas", $data['Prioritas']);

		$this->db->insert('PickListDO');
	}

	public function insertPickList($data)
	{
		$this->db->set("PickList_ID", "NEWID()", false);
		$this->db->set("Gudang_ID", $data['Gudang_ID']);
		$this->db->set("GudangVA_ID", $data['GudangVA_ID']);
		$this->db->set("Channel_ID", '1637F090-7213-422F-9853-1F809D95C1BC');
		$this->db->set("PickList_Kode", $data['PickList_Kode']);
		$this->db->set("PickList_Nama", $data['PickList_Nama']);
		$this->db->set("PickList_Date", $data['PickList_Date']);
		$this->db->set("UnitMandiri_ID", $data['UnitMandiri_ID']);
		$this->db->set("Status", $data['Status']);
		$this->db->set("TipePengiriman_ID", $data['TipePengiriman_ID']);
		// $this->db->set("Area_ID", $data['Area_ID']);
		// $this->db->set("JenisArmada", $data['JenisArmada']);
		// $this->db->set("Kurir_ID", $data['Kurir_ID']);
		// $this->db->set("KurirD_ID", $data['KurirD_ID']);
		$this->db->set("NoSuratTugasKurir", $data['NoSuratTugasKurir']);

		$this->db->insert('PickList');
	}

	public function insertPickListD($data)
	{
		$this->db->set("PickLIstD_ID", "NEWID()", false);
		$this->db->set("PickList_ID", $data['PickList_ID']);
		$this->db->set("SKU_ID", $data['SKU_ID']);
		$this->db->set("PickListD_Kode", $data['PickListD_Kode']);
		$this->db->set("PickListD_QtyTotal", $data['PickListD_QtyTotal']);

		$this->db->insert('PickListD');
	}

	public function insertPickListD2($data)
	{
		$this->db->set("PickListD2_ID", "NEWID()", false);
		$this->db->set("PickListD_ID", $data['PickListD_ID']);
		$this->db->set("PickList_ID", $data['PickList_ID']);
		$this->db->set("SKU_UnitMandiriID", $data['SKU_UnitMandiriID']);
		$this->db->set("PickListD2_ExpiredDate", $data['PickListD2_ExpiredDate']);
		$this->db->set("PickListD2_SatuanUnit", $data['PickListD2_SatuanUnit']);
		$this->db->set("PickListD2_QtyPlan", $data['PickListD2_QtyPlan']);
		$this->db->set("PickListD2_QtyAssigned", $data['PickListD2_QtyAssigned']);
		$this->db->set("PickListD2_QtySisa", $data['PickListD2_QtySisa']);
		$this->db->set("Gudang_ID", $data['Gudang_ID']);
		$this->db->set("GudangVA_ID", $data['GudangVA_ID']);

		$query = $this->db->insert('PickListD2');
	}

	public function updateDoStatus($doId)
	{
		$doStatus = 'On Picking';
		$this->db->set('DO_Status', $doStatus);
		$this->db->where('DO_ID', $doId);
		$this->db->update('DeliveryOrder');
	}

	public function getAllGudang()
	{
		$data = $this->db->select("*")->from('Gudang')->get();
		return $data->result_array();
	}

	public function getPickListByBatch($batchId)
	{
		$listDoBatch = $this->db->select("pl.*")
			->from('DeliveryOrderBatch as dob')
			->join('DeliveryOrder as do', 'dob.DOB_ID = do.DOB_ID')
			->join('PickListDO as pldo', 'do.DO_ID = pldo.DO_ID')
			->join('PickList as pl', 'pldo.PickingList_ID = pl.PickList_ID')
			->where('dob.DOB_ID', $batchId)
			->get();
		return $listDoBatch->result_array();
	}

	public function getPickListBatch()
	{
		$listDoBatch = $this->db->select("dob.*")
			->from('DeliveryOrderBatch as dob')
			->join('DeliveryOrder as do', 'dob.DOB_ID = do.DOB_ID')
			->join('PickListDO as pldo', 'do.DO_ID = pldo.DO_ID')
			->join('PickList as pl', 'pldo.PickingList_ID = pl.PickList_ID')
			->get();
		return $listDoBatch->result_array();
	}

	public function getPickListDetail($pickListId)
	{
		$queryGetPickList = $this->db->select('pld.SKU_ID,sku.SKU_NamaProduk,sku.SKU_Kode, pld2.PickListD2_QtyPlan,
        pld2.PickListD2_ExpiredDate,pld2.PickListD2_SatuanUnit,pld2.PickListD2_QtyPlan,
        pld2.PickListD2_QtyAssigned,pld2.PickListD2_QtySisa,pld2.SKU_UnitMandiriID, PickListD2_ID,
        skuUnit.SKU_ExpireDate')
			->from('PickListD as pld')
			->join("PickListD2 as pld2", "pld.PickListD_ID = pld2.PickListD_ID", "inner")
			->join("SKU as sku", "pld.SKU_ID = sku.SKU_ID", "inner")
			->join('SKU_UnitMandiri as skuUnit', 'sku.SKU_ID = skuUnit.SKU_ID', 'inner')
			->where('pld.PickList_ID', $pickListId)
			->get();

		return $queryGetPickList->result_array();
	}

	public function getPickListByGudang($gudangId, $status = '')
	{
		$data = $this->db->select("*")->from('PickList')
			->where('Gudang_ID', $gudangId);
		if (strlen($status) > 0) {
			$data = $data->where('Status', $status);
		}
		$data = $data->get();
		return $data->result_array();
	}

	public function getPickListDetailD2byId($pickListD2Id)
	{
		$data = $this->db->select("*")
			->from('PickListD2')
			->where('PickListD2_ID', $pickListD2Id)
			->get();
		return $data->result_array();
	}

	public function updatePickListDone($pickListId)
	{
		$this->db->set('Status', 'Picked');
		$this->db->where('PickList_ID', $pickListId);
		$this->db->update('PickList');
	}

	public function updateQtyAssign($pickListD2Id, $qtyAssign, $qtySisa)
	{
		$this->db->set('PickListD2_QtyAssigned', $qtyAssign, false);
		$this->db->set('PickListD2_QtySisa', $qtySisa, false);
		$this->db->where('PickListD2_ID', $pickListD2Id);
		$this->db->update('PickListD2');

		$getPickListDetail = self::getPickListDetailD2byId($pickListD2Id);
		$pickListId = $getPickListDetail[0]['PickList_ID'];
		$sisa = 0;
		foreach ($getPickListDetail as $item) {
			$qtySisa = $item['PickListD2_QtySisa'];
			if ($qtySisa > 0) {
				$sisa = $qtySisa;
				break;
			}
		}
		if ($sisa == 0) {
			self::updatePickListDone($pickListId);
		}
	}

	public function getGudangByVA($gudangVa)
	{
		$data = $this->db->select("*")->from('Gudang1')
			->where('GUDANGVA_ID', $gudangVa);
		$data = $data->get();
		return $data->result_array();
	}
	// prosedure update prioritas stock do
	public function update_prioritas_stock($picking_list_id)
	{
		// $this->db->trans_start();
		$sql = "
                EXEC update_prioritas_stock 
                    @picking_list_id = '" . $picking_list_id . "'
                ";
		$result = $this->db->query($sql);

		return $sql;
	}
}