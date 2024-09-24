<?php

class M_PengirimanBarang extends CI_Model
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

		$result = $this->db->query($sql);

		return $sql;
	}

	public function SavePengirimanBarang($dataSTP, $dataBulk, $dataFlushOut, $dataStandar, $dataReschedule, $dataCanvas, $picking_order_id, $last_update, $newid)
	{

		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "picking_order",
			'whereField' => "picking_order_id",
			'whereValue' => $picking_order_id,
			'fieldDateUpdate' => "picking_order_tgl_update",
			'fieldWhoUpdate' => "picking_order_who_update",
			'lastUpdated' => $last_update
		]);

		$newID1 = $this->M_Function->Get_NewID();
		$newID = $newID1[0]['kode'];

		$this->db->set('serah_terima_kirim_id', $dataSTP['serah_terima_kirim_id']);
		$this->db->set('delivery_order_batch_id', $dataSTP['delivery_order_batch_id']);
		$this->db->set('picking_order_id', $dataSTP['picking_order_id']);
		$this->db->set('serah_terima_kirim_kode', $dataSTP['serah_terima_kirim_kode']);
		$this->db->set('serah_terima_kirim_tgl', $dataSTP['serah_terima_kirim_tgl']);
		$this->db->set('serah_terima_kirim_who', $dataSTP['serah_terima_kirim_who']);
		$this->db->set('serah_terima_kirim_status', 'Open');
		$this->db->set('serah_terima_kirim_keterangan', $dataSTP['serah_terima_kirim_keterangan']);

		$this->db->insert('serah_terima_kirim');

		// update batch do, delivery_order_batch_status, masukkan no picklist

		// $this->db->set('serah_terima_kirim_id', $dataSTP['serah_terima_kirim_id'] );
		// $this->db->set('delivery_order_batch_status', 'in transit' );
		// $this->db->where('delivery_order_batch_id', $dataSTP['delivery_order_batch_id']);
		// $this->db->update('delivery_order_batch');

		// jika sku_id ada do maka tipe == bulk
		//insert ke serah_terima_kirim_d1 detail1
		if (isset($dataBulk) && count($dataBulk) > 0) {
			foreach ($dataBulk as $key => $value) {
				$ID1 = $this->M_Function->Get_NewID()[0]['kode'];

				$this->db->set('serah_terima_kirim_d1_id', $ID1);
				$this->db->set('serah_terima_kirim_id', $dataSTP['serah_terima_kirim_id']);
				$this->db->set('sku_id', $value['sku_id']);
				$this->db->set('jumlah_ambil_plan', $value['qty_ambil_plan']);
				$this->db->set('jumlah_ambil_aktual', $value['qty_ambil_aktual'] == 'null' ? NULL :  $value['qty_ambil_aktual']);
				// $this->db->set('jumlah_ambil_aktual', $jumlah_ambil_aktual[$key]);
				$this->db->set('jumlah_serah_terima', $value['qty_serah_terima']);
				$this->db->set('jumlah_serah_terima_rusak', $value['qty_serah_terima_rusak']);

				$this->db->insert('serah_terima_kirim_d1');
			}
		}
		//insert ke serah_terima_kirim_d1 detail1
		if (isset($dataFlushOut) && count($dataFlushOut) > 0) {
			foreach ($dataFlushOut as $key => $value) {
				$ID1 = $this->M_Function->Get_NewID()[0]['kode'];

				$this->db->set(
					'serah_terima_kirim_d1_id',
					$ID1
				);
				$this->db->set('serah_terima_kirim_id', $dataSTP['serah_terima_kirim_id']);
				$this->db->set('sku_id', $value['sku_id']);
				$this->db->set('jumlah_ambil_plan', $value['qty_ambil_plan']);
				$this->db->set('jumlah_ambil_aktual', $value['qty_ambil_aktual'] == 'null' ? NULL : $value['qty_ambil_aktual']);
				// $this->db->set('jumlah_ambil_aktual', $jumlah_ambil_aktual[$key]);
				$this->db->set('jumlah_serah_terima', $value['qty_serah_terima']);
				$this->db->set('jumlah_serah_terima_rusak', $value['qty_serah_terima_rusak']);

				$this->db->insert('serah_terima_kirim_d1');
			}
		}
		// jika ada do maka tipe == standar
		//insert ke serah_terima_kirim_d2 detail2
		if (isset($dataStandar) && count($dataStandar) > 0) {
			foreach ($dataStandar as $key => $value) {
				$ID2 = $this->M_Function->Get_NewID()[0]['kode'];

				$this->db->set('serah_terima_kirim_d2_id', $ID2);
				$this->db->set('serah_terima_kirim_id', $dataSTP['serah_terima_kirim_id']);
				$this->db->set('delivery_order_id', $value['do_id']);
				$this->db->set('jumlah_paket', $value['qty_packed']);
				$this->db->set('jumlah_serah_terima', $value['qty_packed_terima']);

				$this->db->insert('serah_terima_kirim_d2');

				// // update delivery_order_status di tbl delivery order
				// $this->db->set('delivery_order_status', 'in transit' );
				// $this->db->where('delivery_order_id', $value);
				// $this->db->update('delivery_order');
				// // insert ke table delivery_order_progress id status in progress item request
				// $this->db->set('delivery_order_progress_id', "NewID()",FALSE);
				// $this->db->set('delivery_order_id', $value );
				// $this->db->set('status_progress_id', '5D07AD85-E9C1-4B88-BECC-84CF0F766919' );
				// $this->db->set('status_progress_nama', 'in transit' );
				// $this->db->set('delivery_order_progress_create_who', $this->session->userdata('pengguna_username') );
				// $this->db->set('delivery_order_progress_create_tgl', "GETDATE()",FALSE );
				// $this->db->insert('delivery_order_progress');

			}
		}

		//insert ke serah_terima_kirim_d3 detail3
		if (isset($dataReschedule) && count($dataReschedule) > 0) {
			foreach ($dataReschedule as $key => $value) {
				$ID3 = $this->M_Function->Get_NewID()[0]['kode'];

				$this->db->set('serah_terima_kirim_d3_id', $ID3);
				$this->db->set('serah_terima_kirim_id', $dataSTP['serah_terima_kirim_id']);
				$this->db->set('sku_id', $value['sku_id']);
				$this->db->set('jumlah_ambil_plan', $value['qty_ambil_plan']);
				$this->db->set('jumlah_ambil_aktual', $value['qty_ambil_aktual'] == 'null' ? NULL :  $value['qty_ambil_aktual']);
				// $this->db->set('jumlah_ambil_aktual', $jumlah_ambil_aktual[$key]);
				$this->db->set('jumlah_serah_terima', $value['qty_serah_terima']);
				$this->db->set('jumlah_serah_terima_rusak', $value['qty_serah_terima_rusak']);

				$this->db->insert('serah_terima_kirim_d3');
			}
		}

		//insert ke serah_terima_kirim_d4 detail4
		if (isset($dataCanvas) && count($dataCanvas) > 0) {
			foreach ($dataCanvas as $key => $value) {
				$ID4 = $this->M_Function->Get_NewID()[0]['kode'];

				$this->db->set('serah_terima_kirim_d4_id', $ID4);
				$this->db->set('serah_terima_kirim_id', $dataSTP['serah_terima_kirim_id']);
				$this->db->set('sku_id', $value['sku_id']);
				$this->db->set('jumlah_ambil_plan', $value['qty_ambil_plan']);
				$this->db->set('jumlah_ambil_aktual', $value['qty_ambil_aktual'] == 'null' ? NULL :  $value['qty_ambil_aktual']);
				// $this->db->set('jumlah_ambil_aktual', $jumlah_ambil_aktual[$key]);
				$this->db->set('jumlah_serah_terima', $value['qty_serah_terima']);
				$this->db->set('jumlah_serah_terima_rusak', $value['qty_serah_terima_rusak']);

				$this->db->insert('serah_terima_kirim_d4');
			}
		}

		if ($lastUpdatedChecked['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'message' => 'Data Gagal disimpan.',
				'serah_terima_kirim_id' => null
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => 'Data Gagal disimpan.',
				'serah_terima_kirim_id' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Data Berhasil disimpan.',
				'serah_terima_kirim_id' => $newid
			];
		}

		return $response;
	}
	public function UpdatePengirimanBarang($serah_terima_kirim_id, $last_update,  $serah_terima_kirim_d1_id, $jumlah_serah_terima_1, $jumlah_serah_terima_rusak_1, $serah_terima_kirim_d2_id, $jumlah_serah_terima_2, $serah_terima_kirim_d3_id, $jumlah_serah_terima_3, $jumlah_serah_terima_rusak_3, $serah_terima_kirim_d4_id, $jumlah_serah_terima_4, $jumlah_serah_terima_rusak_4)
	{

		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' 			=> "serah_terima_kirim",
			'whereField' 		=> "serah_terima_kirim_id",
			'whereValue' 		=> $serah_terima_kirim_id,
			'fieldDateUpdate' 	=> "serah_terima_kirim_update_tgl",
			'fieldWhoUpdate' 	=> "serah_terima_kirim_update_who",
			'lastUpdated' 		=> $last_update
		]);



		//update ke serah_terima_kirim_d1 detail1
		if ($serah_terima_kirim_d1_id != null) {
			foreach ($serah_terima_kirim_d1_id as $key => $value) {

				$this->db->set('jumlah_serah_terima', $jumlah_serah_terima_1[$key]);
				$this->db->set('jumlah_serah_terima_rusak', $jumlah_serah_terima_rusak_1[$key]);
				$this->db->where('serah_terima_kirim_d1_id', $value);
				$this->db->update('serah_terima_kirim_d1');
			}
		}
		// jika ada do maka tipe == standar
		//update ke serah_terima_kirim_d2 detail2
		if ($serah_terima_kirim_d2_id != null) {
			foreach ($serah_terima_kirim_d2_id as $key => $value) {

				$this->db->set('jumlah_serah_terima', $jumlah_serah_terima_2[$key]);
				$this->db->where('serah_terima_kirim_d2_id', $value);
				$this->db->update('serah_terima_kirim_d2');
			}
		}

		//update ke serah_terima_kirim_d3 detail3
		if ($serah_terima_kirim_d3_id != null) {
			foreach ($serah_terima_kirim_d3_id as $key => $value) {

				$this->db->set('jumlah_serah_terima', $jumlah_serah_terima_3[$key]);
				$this->db->set('jumlah_serah_terima_rusak', $jumlah_serah_terima_rusak_3[$key]);
				$this->db->where('serah_terima_kirim_d3_id', $value);
				$this->db->update('serah_terima_kirim_d3');
			}
		}

		//update ke serah_terima_kirim_d4 detail4
		if ($serah_terima_kirim_d4_id != null) {
			foreach ($serah_terima_kirim_d4_id as $key => $value) {

				$this->db->set('jumlah_serah_terima', $jumlah_serah_terima_4[$key]);
				$this->db->set('jumlah_serah_terima_rusak', $jumlah_serah_terima_rusak_4[$key]);
				$this->db->where('serah_terima_kirim_d4_id', $value);
				$this->db->update('serah_terima_kirim_d4');
			}
		}

		if ($lastUpdatedChecked['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'message' => 'Data Gagal disimpan.',
				'serah_terima_kirim_id' => null
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => 'Data Gagal disimpan.',
				'serah_terima_kirim_id' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Data Berhasil disimpan.',
				'serah_terima_kirim_id' => $serah_terima_kirim_id
			];
		}

		return $response;
	}

	public function ConfirmPengirimanBarang($serah_terima_kirim_id, $delivery_order_batch_id, $delivery_order_id, $last_update)
	{

		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' 			=> "serah_terima_kirim",
			'whereField' 		=> "serah_terima_kirim_id",
			'whereValue' 		=> $serah_terima_kirim_id,
			'fieldDateUpdate' 	=> "serah_terima_kirim_update_tgl",
			'fieldWhoUpdate' 	=> "serah_terima_kirim_update_who",
			'lastUpdated' 		=> $last_update
		]);

		$this->db->set('serah_terima_kirim_status', 'Closed');
		$this->db->where('serah_terima_kirim_id', $serah_terima_kirim_id);
		$this->db->update('serah_terima_kirim');

		// get data last km kendaraan berdasarkan do batch
		// $listDoBatch = $this->db->select("kd.kendaraan_last_kilometer")
		//     ->from('delivery_order_batch dob')
		//     ->join('kendaraan kd', 'kd.kendaraan_id = dob.kendaraan_id', 'left')
		//     ->where('dob.delivery_order_batch_id', $delivery_order_batch_id)
		//     ->get();
		// $data_kendaraan =  $listDoBatch->row();

		// update batch do, delivery_order_batch_status, masukkan no STP, last km kendaraan

		$this->db->set('serah_terima_kirim_id', $serah_terima_kirim_id);
		$this->db->set('delivery_order_batch_status', 'in transit validation');
		// $this->db->set('kendaraan_km_awal', $data_kendaraan->kendaraan_last_kilometer);
		$this->db->where('delivery_order_batch_id', $delivery_order_batch_id);
		$this->db->update('delivery_order_batch');
		//update status do jika ada do
		if ($delivery_order_id != null) {
			foreach ($delivery_order_id as $key => $value) {

				// update delivery_order_status di tbl delivery order
				$this->db->set('delivery_order_status', 'in transit validation');
				$this->db->where('delivery_order_id', $value);
				$this->db->update('delivery_order');
				// insert ke table delivery_order_progress id status in progress item request
				$this->db->set('delivery_order_progress_id', "NewID()", FALSE);
				$this->db->set('delivery_order_id', $value);
				$this->db->set('status_progress_id', 'D1B7AE25-05C7-4270-AC50-9D7F1B8F832B');
				$this->db->set('status_progress_nama', 'in transit validation');
				$this->db->set('delivery_order_progress_create_who', $this->session->userdata('pengguna_username'));
				$this->db->set('delivery_order_progress_create_tgl', "GETDATE()", FALSE);
				$this->db->insert('delivery_order_progress');
			}
		} else {

			// $getDOId = $this->db->select("delivery_order_id")
			//     ->from("delivery_order")
			//     ->join("tipe_delivery_order", "delivery_order.tipe_delivery_order_id = tipe_delivery_order.tipe_delivery_order_id", "left")
			//     ->where('delivery_order_batch_id', $delivery_order_batch_id)
			//     ->where('tipe_delivery_order_id <>', 'Standar')
			//     ->get()->row();

			// $getDOId = $this->db->select("delivery_order_id")->from("delivery_order")->where('delivery_order_batch_id', $delivery_order_batch_id)->get()->row();

			// update delivery_order_status di tbl delivery order
			$this->db->set('delivery_order_status', 'in transit validation');
			$this->db->where('delivery_order_batch_id', $delivery_order_batch_id);
			$this->db->update('delivery_order');

			$getDOId = $this->db->select("delivery_order_id")->from("delivery_order")->where('delivery_order_batch_id', $delivery_order_batch_id)->get()->result();

			foreach ($getDOId as $key => $value) {
				// insert ke table delivery_order_progress id status in progress item request
				$this->db->set('delivery_order_progress_id', "NEWID()", FALSE);
				$this->db->set('delivery_order_id', $value->delivery_order_id);
				$this->db->set('status_progress_id', 'D1B7AE25-05C7-4270-AC50-9D7F1B8F832B');
				$this->db->set('status_progress_nama', 'in transit validation');
				$this->db->set('delivery_order_progress_create_who', $this->session->userdata('pengguna_username'));
				$this->db->set('delivery_order_progress_create_tgl', "GETDATE()", FALSE);
				$this->db->insert('delivery_order_progress');
			}

			// update delivery_order_status di tbl delivery order
			// $this->db->set('delivery_order_status', 'in transit validation');
			// $this->db->where('delivery_order_batch_id', $delivery_order_batch_id);
			// $this->db->update('delivery_order');
			// // insert ke table delivery_order_progress id status in progress item request
			// $this->db->set('delivery_order_progress_id', "NewID()", FALSE);
			// $this->db->set('delivery_order_id', $getDOId->delivery_order_id);
			// $this->db->set('status_progress_id', 'D1B7AE25-05C7-4270-AC50-9D7F1B8F832B');
			// $this->db->set('status_progress_nama', 'in transit validation');
			// $this->db->set('delivery_order_progress_create_who', $this->session->userdata('pengguna_username'));
			// $this->db->set('delivery_order_progress_create_tgl', "GETDATE()", FALSE);
			// $this->db->insert('delivery_order_progress');
		}

		if ($lastUpdatedChecked['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'message' => 'Data Gagal dikonfirmasi.',
				'serah_terima_kirim_id' => null
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => 'Data Gagal dikonfirmasi.',
				'serah_terima_kirim_id' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Data Berhasil dikonfirmasi.',
				'serah_terima_kirim_id' => $serah_terima_kirim_id
			];
		}

		return $response;
	}

	public function getDataPengirimanDetail($pengiriman_barang_id)
	{
		$sql = "SELECT st.serah_terima_kirim_kode,
						st.delivery_order_batch_id,
						st.serah_terima_kirim_id,
						st.serah_terima_kirim_keterangan,
						st.serah_terima_kirim_status,
						st.serah_terima_kirim_update_tgl,
						FORMAT(st.serah_terima_kirim_tgl, 'yyyy-MM-dd') AS tgl_create,
						dob.delivery_order_batch_kode,
						po.picking_order_kode,
						kry.karyawan_nama
					FROM serah_terima_kirim st
					LEFT JOIN delivery_order_batch dob
						ON dob.delivery_order_batch_id = st.delivery_order_batch_id
					LEFT JOIN picking_order po
						ON po.picking_order_id = st.picking_order_id
					LEFT JOIN karyawan AS kry
						ON kry.karyawan_id = dob.karyawan_id
					WHERE st.serah_terima_kirim_id = '" . $pengiriman_barang_id . "'
        		";

		$query = $this->db->query($sql);

		return $query->row();
	}

	public function getDataReport($pengiriman_barang_id)
	{
		$sql = "SELECT 
                    delivery_order_batch.delivery_order_batch_kode AS kode,
                    karyawan.karyawan_nama AS driver,
                    kendaraan.kendaraan_kode AS armada,
                    serah_terima_kirim.serah_terima_kirim_kode AS kode_kirim,
                    picking_order.picking_order_kode AS kode_order,
                    delivery_order.delivery_order_no_urut_rute AS rute,
                    serah_terima_kirim.serah_terima_kirim_tgl AS tgl_terima, 
                    serah_terima_kirim.serah_terima_kirim_keterangan AS keterangan, 
                    delivery_order.delivery_order_kode AS no_do, 
                    delivery_order.delivery_order_kirim_nama AS nama,
                    delivery_order.delivery_order_kirim_alamat AS alamat, 
                    delivery_order.delivery_order_tipe_layanan AS tipe_layanan,
                    delivery_order.delivery_order_tipe_pembayaran AS tipe_pembayaran, 
                    sku.sku_nama_produk AS produk,
                    sku.sku_satuan AS satuan,
                    delivery_order_detail2.sku_qty AS qty,
                    delivery_order_detail2.sku_expdate AS exp_date
                FROM delivery_order 
                INNER JOIN delivery_order_detail2 
                    ON delivery_order.delivery_order_id = delivery_order_detail2.delivery_order_id
                INNER JOIN delivery_order_batch 
                    ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
                INNER JOIN serah_terima_kirim
                    ON delivery_order_batch.serah_terima_kirim_id = serah_terima_kirim.serah_terima_kirim_id
                INNER JOIN sku 
                    ON delivery_order_detail2.sku_id = sku.sku_id
                INNER JOIN karyawan 
                    ON delivery_order_batch.karyawan_id = karyawan.karyawan_id
                INNER JOIN kendaraan 
                    ON delivery_order_batch.kendaraan_id = kendaraan.kendaraan_id
                INNER JOIN picking_order
                    ON delivery_order_batch.picking_order_id = picking_order.picking_order_id
                WHERE delivery_order_batch.serah_terima_kirim_id = '" . $pengiriman_barang_id . "' 
                ORDER BY rute DESC";

		$query = $this->db->query($sql);
		return $query->result();
	}

	public function getDataPengirimanDetailD1($pengiriman_barang_id)
	{
		$sql = "SELECT
                    std1.*,
                    principle.principle_kode,
                    sku.sku_id,
                    sku.sku_kode,
                    sku.sku_nama_produk,
                    sku.sku_kemasan,
                    sku.sku_satuan
                FROM serah_terima_kirim_d1 std1
                LEFT JOIN sku
                    ON sku.sku_id = std1.sku_id
                LEFT JOIN principle
                    ON principle.principle_id = sku.principle_id
                WHERE std1.serah_terima_kirim_id = '" . $pengiriman_barang_id . "'
				ORDER BY principle.principle_kode, sku.sku_kode ASC
        ";

		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function getDataPengirimanDetailD2($pengiriman_barang_id)
	{
		// $sql = "SELECT
		//             std2.*,
		//             principle.principle_kode,
		//             do.delivery_order_kode,
		//             do.delivery_order_status,
		//             -- typ.tipe_delivery_order_nama,
		//             do.delivery_order_kirim_nama,
		//             do.delivery_order_kirim_alamat,
		//             do.delivery_order_kirim_telp,
		//             sku.sku_id,
		//             sku.sku_kode,
		//             sku.sku_nama_produk,
		//             sku.sku_kemasan,
		//             sku.sku_satuan
		//         FROM serah_terima_kirim_d2 std2
		//         LEFT JOIN delivery_order do
		//             ON std2.delivery_order_id = do.delivery_order_id
		//         LEFT JOIN delivery_order_detail dod
		//             ON do.delivery_order_id = dod.delivery_order_id
		//         LEFT JOIN sku
		//             ON dod.sku_id = sku.sku_id
		//         LEFT JOIN principle
		//             ON principle.principle_id = sku.principle_id
		//         -- LEFT JOIN tipe_delivery_order AS typ
		//         --     ON typ.tipe_delivery_order_id = do.tipe_delivery_order_id
		//         WHERE std2.serah_terima_kirim_id = '" . $pengiriman_barang_id . "'
		// ";

		$sql = "SELECT
         std2.*,
         do.delivery_order_kode,
        do.delivery_order_kirim_nama,
        do.delivery_order_kirim_alamat
    FROM serah_terima_kirim_d2 std2
    LEFT JOIN delivery_order do 
    ON std2.delivery_order_id = do.delivery_order_id
    WHERE std2.serah_terima_kirim_id = '" . $pengiriman_barang_id . "'
";

		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function getDataPengirimanDetailD3($pengiriman_barang_id)
	{
		$sql = "SELECT
                    std3.*,
                    principle.principle_kode,
                    sku.sku_id,
                    sku.sku_kode,
                    sku.sku_nama_produk,
                    sku.sku_kemasan,
                    sku.sku_satuan
                FROM serah_terima_kirim_d3 std3
                LEFT JOIN sku
                    ON sku.sku_id = std3.sku_id
                LEFT JOIN principle
                    ON principle.principle_id = sku.principle_id
                WHERE std3.serah_terima_kirim_id = '" . $pengiriman_barang_id . "'
				ORDER BY principle.principle_kode, sku.sku_kode ASC
        ";

		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function getDataPengirimanDetailD4($pengiriman_barang_id)
	{
		$sql = "SELECT
                    std4.*,
                    principle.principle_kode,
                    sku.sku_id,
                    sku.sku_kode,
                    sku.sku_nama_produk,
                    sku.sku_kemasan,
                    sku.sku_satuan
                FROM serah_terima_kirim_d4 std4
                LEFT JOIN sku
                    ON sku.sku_id = std4.sku_id
                LEFT JOIN principle
                    ON principle.principle_id = sku.principle_id
                WHERE std4.serah_terima_kirim_id = '" . $pengiriman_barang_id . "'
				ORDER BY principle.principle_kode, sku.sku_kode ASC
        ";

		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function getDataPengirimanSearch($ppb_id, $tgl, $tgl2)
	{
		$depo_id = $this->session->userdata('depo_id');
		$sql = "";
		$sql .= "SELECT
                    stp.*,
                    CONVERT(varchar, stp.serah_terima_kirim_tgl, 105) + ' (' + CONVERT(varchar, stp.serah_terima_kirim_tgl, 108) + ')' as tgl_create,
					FORMAT(dob.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') as tgl_kirim,
                    dob.delivery_order_batch_kode,
                    po.picking_order_kode,
					k.karyawan_nama
                FROM serah_terima_kirim stp
                LEFT JOIN delivery_order_batch dob
                    ON dob.delivery_order_batch_id = stp.delivery_order_batch_id
                LEFT JOIN picking_order po
                    ON po.picking_order_id = stp.picking_order_id
				LEFT JOIN karyawan k
					ON dob.karyawan_id = k.karyawan_id
                WHERE FORMAT(dob.delivery_order_batch_tanggal_kirim, 'yyyy-MM-dd') BETWEEN '$tgl' and '$tgl2'
                AND po.depo_id = '$depo_id'
                ";

		if ($ppb_id != null) {
			$sql .= "AND stp.picking_order_id LIKE '%$ppb_id%' ";
		}
		$query = $this->db->query($sql);

		return $query->result_array();
	}
	public function getListDoBatchPick()
	{
		$listDoBatch = $this->db->select("*")->from('delivery_order_batch')->where('depo_id', $this->session->userdata('depo_id'))->where('picking_order_id is NOT NULL')->get();
		return $listDoBatch->result_array();
	}
	public function getListPickOrder()
	{
		// $listDoBatch = $this->db->select("po.*")
		//     ->from('picking_order po')
		//     // ->join('')
		//     ->join('picking_list pk', 'pk.picking_list_id = po.picking_list_id', 'left')
		//     ->join('delivery_order_batch dob', 'dob.delivery_order_batch_id = pk.delivery_order_batch_id', 'left')
		//     ->where('po.depo_id', $this->session->userdata('depo_id'))
		//     ->where('dob.serah_terima_kirim_id IS NULL')
		//     ->where('po.picking_order_status', 'Completed')->get();
		// return $listDoBatch->result_array();

		return $this->db->query("SELECT c.picking_order_id, c.picking_order_kode from delivery_order_batch a
                                    LEFT JOIN picking_list b on a.delivery_order_batch_id = b.delivery_order_batch_id
                                    LEFT JOIN picking_order c on b.picking_list_id = c.picking_list_id
                                    where c.picking_order_id not in (select picking_order_id from serah_terima_kirim)
                                    and c.picking_order_status = 'Completed'
									and a.delivery_order_batch_status = 'pick up item confirmed'
                                    and a.depo_id = '" . $this->session->userdata('depo_id') . "'")->result_array();
	}

	public function getListDriver()
	{

		/**$this->db->select("*")
			->from("karyawan")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("karyawan_level_id", "339D8AC2-C6CE-4B47-9BFC-E372592AF521")
			->order_by("karyawan_nama");
		$query = $this->db->get();

		 */

		$query = $this->db->query("SELECT DISTINCT kry.karyawan_id, kry.karyawan_nama 
                            FROM delivery_order_batch a
                            LEFT JOIN picking_list b on a.delivery_order_batch_id = b.delivery_order_batch_id
                            LEFT JOIN picking_order c on b.picking_list_id = c.picking_list_id
                            left join picking_order_aktual_h d on c.picking_order_id = d.picking_order_id
                            left join picking_order_plan e on c.picking_order_id = e.picking_order_id
                            left join karyawan as kry on kry.karyawan_id = a.karyawan_id
                            where c.picking_order_id not in (select picking_order_id from serah_terima_kirim)
                                and c.picking_order_status = 'Completed'
								and a.delivery_order_batch_status IN ('pick up item confirmed', 'in transit validation')
                                and a.depo_id = '" . $this->session->userdata('depo_id') . "'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetPpbByDriver($karyawanId)
	{

		return $this->db->query(" SELECT DISTINCT c.picking_order_id, c.picking_order_kode 
														 FROM delivery_order_batch a
														 LEFT JOIN picking_list b on a.delivery_order_batch_id = b.delivery_order_batch_id
														 LEFT JOIN picking_order c on b.picking_list_id = c.picking_list_id
														 left join picking_order_aktual_h d on c.picking_order_id = d.picking_order_id
														 left join picking_order_plan e on c.picking_order_id = e.picking_order_id
														 left join karyawan as kry on kry.karyawan_id = a.karyawan_id
														 where kry.karyawan_id = '$karyawanId'
														 	and c.picking_order_id not in (select picking_order_id from serah_terima_kirim)
														 	and c.picking_order_status = 'Completed'
															and a.delivery_order_batch_status IN ('pick up item confirmed', 'in transit validation')
														 	and a.depo_id = '" . $this->session->userdata('depo_id') . "'")->result();
	}

	public function GetDoBatchByPickOrderId($ppb_id)
	{
		// $listDoBatch = $this->db->distinct()->select("dob.delivery_order_batch_id,dob.delivery_order_batch_kode,dob.tipe_delivery_order_id,typ.tipe_delivery_order_alias,typ.tipe_delivery_order_nama,kry.karyawan_id,kry.karyawan_nama, po.picking_order_tgl_update")
		// 	->from('picking_order po')
		// 	->join('picking_order_aktual_h poah', 'po.picking_order_id = poah.picking_order_id', 'left')
		// 	->join('picking_order_plan pop', 'po.picking_order_id = pop.picking_order_id', 'left')
		// 	->join('picking_list pk', 'pk.picking_list_id = po.picking_list_id', 'left')
		// 	->join('delivery_order_batch dob', 'dob.delivery_order_batch_id = pk.delivery_order_batch_id', 'left')
		// 	->join('karyawan as kry', 'kry.karyawan_id = dob.karyawan_id', 'left')
		// 	->join('tipe_delivery_order as typ', 'typ.tipe_delivery_order_id = dob.tipe_delivery_order_id', 'left')
		// 	->where('po.picking_order_id', $ppb_id)
		// 	->get();
		// return $listDoBatch->row();

		return $this->db->query("SELECT distinct dob.delivery_order_batch_id,
																dob.delivery_order_batch_kode,
																dob.tipe_delivery_order_id,
																typ.tipe_delivery_order_alias,
																typ.tipe_delivery_order_nama,
																kry.karyawan_id,
																kry.karyawan_nama, 
																po.picking_order_tgl_update,
																pop.karyawan_id,
																poah.karyawan_id
														from picking_order po
														left join picking_order_aktual_h poah on po.picking_order_id = poah.picking_order_id
														left join picking_order_plan pop on po.picking_order_id = pop.picking_order_id
														left join picking_list pk on pk.picking_list_id = po.picking_list_id
														left join delivery_order_batch dob on dob.delivery_order_batch_id = pk.delivery_order_batch_id
														left join karyawan as kry on kry.karyawan_id = dob.karyawan_id
														left join tipe_delivery_order as typ on typ.tipe_delivery_order_id = dob.tipe_delivery_order_id
														where po.picking_order_id = '$ppb_id'")->row();
	}

	public function GetDataDetailBulk($ppb_id)
	{
		$sql = "SELECT
					principle.principle_kode,
					pld.sku_id,
					sku.sku_kode,
					sku.sku_nama_produk,
					sku.sku_kemasan,
					sku.sku_satuan,
					pld.sku_stock_qty_ambil AS qty,
					picking_order_aktual.sku_stock_qty_ambil AS qty_ambil
				FROM picking_order pk
				LEFT JOIN picking_list_detail pldd ON pk.picking_list_id = pldd.picking_list_id
				LEFT JOIN picking_list_detail_2 pldd2 ON pldd.picking_list_detail_id = pldd2.picking_list_detail_id
				LEFT JOIN delivery_order do ON pldd2.delivery_order_id = do.delivery_order_id
				LEFT JOIN picking_order_plan pld 
					ON pk.picking_order_id = pld.picking_order_id
					AND pldd2.sku_id = pld.sku_id
				LEFT JOIN (SELECT
											picking_order_aktual_h.picking_order_id,
											picking_order_aktual_d.picking_order_plan_id,
											picking_order_aktual_d.sku_id,
											SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
										FROM picking_order_aktual_h
										LEFT JOIN picking_order_aktual_d
											ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
										WHERE picking_order_aktual_d.delivery_order_id IS NULL
										GROUP BY picking_order_aktual_h.picking_order_id,
												picking_order_aktual_d.picking_order_plan_id,
												picking_order_aktual_d.sku_id) picking_order_aktual
					ON picking_order_aktual.picking_order_id = pk.picking_order_id
					AND picking_order_aktual.picking_order_plan_id = pld.picking_order_plan_id
				LEFT JOIN sku
					ON sku.sku_id = pld.sku_id
				LEFT JOIN principle
					ON principle.principle_id = sku.principle_id
				WHERE pk.picking_order_id = '" . $ppb_id . "'
				AND do.tipe_delivery_order_id = 'ADF55030-9802-4C27-9658-F9D37AA01F95'
				AND pld.delivery_order_id IS NULL
				GROUP BY principle_kode,
						pld.sku_id,
						sku.sku_kode,
						sku.sku_nama_produk,
						sku.sku_kemasan,
						sku.sku_satuan,
						pld.sku_stock_qty_ambil,
						picking_order_aktual.sku_stock_qty_ambil
			ORDER BY sku.sku_kode ASC";
		$query = $this->db->query($sql);

		return $query->result_array();
		// $listDoBatch = $this->db->select("principle.principle_kode,
		//                                 sku.sku_id,
		//                                 sku.sku_kode,
		//                                 sku.sku_nama_produk,
		//                                 sku.sku_kemasan,
		//                                 sku.sku_satuan,
		//                                 pld.sku_stock_qty_ambil AS qty,
		//                                 SUM(pkd.sku_stock_qty_ambil) AS qty_ambil")
		//                         ->from('picking_order pk')
		//                         ->join('picking_order_aktual_h pkh','pkh.picking_order_id = pk.picking_order_id','left')
		//                         ->join('picking_order_aktual_d pkd','pkd.picking_order_aktual_h_id = pkh.picking_order_aktual_h_id','left')
		//                         ->join('picking_order_plan pld','pld.picking_order_plan_id = pkd.picking_order_plan_id', 'left')
		//                         ->join('sku','sku.sku_id = pkd.sku_id', 'left')
		//                         ->join('principle','principle.principle_id = sku.principle_id', 'left')
		//                         ->where('pk.picking_order_id',$ppb_id)
		//                         ->where('pkd.delivery_order_id IS NULL')
		//                         ->group_by('principle.principle_kode')
		//                         ->group_by('sku.sku_id')
		//                         ->group_by('sku.sku_nama_produk')
		//                         ->group_by('sku.sku_kode')
		//                         ->group_by('sku.sku_kemasan')
		//                         ->group_by('sku.sku_satuan')
		//                         ->group_by('pld.sku_stock_qty_ambil')
		//                         ->get();
		// return $listDoBatch->result_array();
	}

	public function GetDataDetailFlushOut($ppb_id)
	{
		$sql = "SELECT
					principle.principle_kode,
					pld.sku_id,
					sku.sku_kode,
					sku.sku_nama_produk,
					sku.sku_kemasan,
					sku.sku_satuan,
					pld.sku_stock_qty_ambil AS qty,
					picking_order_aktual.sku_stock_qty_ambil AS qty_ambil
				FROM picking_order pk
				LEFT JOIN picking_list_detail pldd ON pk.picking_list_id = pldd.picking_list_id
				LEFT JOIN picking_list_detail_2 pldd2 ON pldd.picking_list_detail_id = pldd2.picking_list_detail_id
				LEFT JOIN delivery_order do ON pldd2.delivery_order_id = do.delivery_order_id
				LEFT JOIN picking_order_plan pld 
					ON pk.picking_order_id = pld.picking_order_id
					AND pldd2.sku_id = pld.sku_id
				LEFT JOIN (SELECT
					picking_order_aktual_h.picking_order_id,
					picking_order_aktual_d.picking_order_plan_id,
					picking_order_aktual_d.sku_id,
					SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
				FROM picking_order_aktual_h
				LEFT JOIN picking_order_aktual_d
					ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
				WHERE picking_order_aktual_d.delivery_order_id IS NULL
				GROUP BY picking_order_aktual_h.picking_order_id,
						picking_order_aktual_d.picking_order_plan_id,
						picking_order_aktual_d.sku_id) picking_order_aktual
					ON picking_order_aktual.picking_order_id = pk.picking_order_id
					AND picking_order_aktual.picking_order_plan_id = pld.picking_order_plan_id
				LEFT JOIN sku
					ON sku.sku_id = pld.sku_id
				LEFT JOIN principle
					ON principle.principle_id = sku.principle_id
				WHERE pk.picking_order_id = '" . $ppb_id . "'
				AND do.tipe_delivery_order_id = 'E5E7A933-3137-4377-B6B9-AE37567CBB86'
				AND pld.delivery_order_id IS NULL
				GROUP BY principle_kode,
						pld.sku_id,
						sku.sku_kode,
						sku.sku_nama_produk,
						sku.sku_kemasan,
						sku.sku_satuan,
						pld.sku_stock_qty_ambil,
						picking_order_aktual.sku_stock_qty_ambil
				ORDER BY sku.sku_kode ASC
                ";
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function GetDataDetailStandar($ppb_id)
	{
		$listDoBatch = $this->db->select("do.delivery_order_id,
                                        do.delivery_order_kode,
                                        do.delivery_order_status,
                                        do.delivery_order_kirim_nama,
                                        do.delivery_order_kirim_alamat,
                                        COUNT(pack.delivery_order_id) as jumlah_packed")
			->from('picking_order po')
			->join('picking_list pk', 'pk.picking_list_id = po.picking_list_id', 'left')
			->join('picking_list_detail pkd', 'pkd.picking_list_id = pk.picking_list_id', 'left')
			->join('delivery_order do', 'pkd.delivery_order_id = do.delivery_order_id', 'left')
			// ->join('delivery_order_detail dod', 'do.delivery_order_id = dod.delivery_order_id', 'left')
			// ->join('sku', 'dod.sku_id = sku.sku_id', 'left')
			// ->join('principle', 'sku.principle_id = principle.principle_id', 'left')
			->join('tipe_delivery_order as typ', 'typ.tipe_delivery_order_id = do.tipe_delivery_order_id', 'left')
			->join('packing_h pack', 'pkd.delivery_order_id = pack.delivery_order_id', 'left')
			->where('po.picking_order_id', $ppb_id)
			->where('do.tipe_delivery_order_id', '0E626A53-82FC-4EA6-A4A2-1265279D6E1C') //where tipe do = standar
			->group_by('do.delivery_order_id')
			->group_by('do.delivery_order_kode')
			->group_by('do.delivery_order_status')
			// ->group_by('typ.tipe_delivery_order_nama')
			->group_by('do.delivery_order_kirim_nama')
			->group_by('do.delivery_order_kirim_alamat')
			// ->group_by('do.delivery_order_kirim_telp')
			// ->group_by('sku.sku_id,
			// sku.sku_kode,
			// sku.sku_nama_produk,
			// sku.sku_kemasan,
			// sku.sku_satuan,principle.principle_kode')
			->get();
		return $listDoBatch->result_array();
	}

	public function GetDataDetailReschedule($ppb_id)
	{
		$sql = "SELECT
					principle.principle_kode,
					pld.sku_id,
					sku.sku_kode,
					sku.sku_nama_produk,
					sku.sku_kemasan,
					sku.sku_satuan,
					pld.sku_stock_qty_ambil AS qty,
					picking_order_aktual.sku_stock_qty_ambil AS qty_ambil
				FROM picking_order pk
				LEFT JOIN picking_list_detail pldd ON pk.picking_list_id = pldd.picking_list_id
				LEFT JOIN picking_list_detail_2 pldd2 ON pldd.picking_list_detail_id = pldd2.picking_list_detail_id
				LEFT JOIN delivery_order do ON pldd2.delivery_order_id = do.delivery_order_id
				LEFT JOIN picking_order_plan pld 
					ON pk.picking_order_id = pld.picking_order_id
					AND pldd2.sku_id = pld.sku_id
				LEFT JOIN (SELECT
					picking_order_aktual_h.picking_order_id,
					picking_order_aktual_d.picking_order_plan_id,
					picking_order_aktual_d.sku_id,
					SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
				FROM picking_order_aktual_h
				LEFT JOIN picking_order_aktual_d
					ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
				WHERE picking_order_aktual_d.delivery_order_id IS NULL
				GROUP BY picking_order_aktual_h.picking_order_id,
						picking_order_aktual_d.picking_order_plan_id,
						picking_order_aktual_d.sku_id) picking_order_aktual
					ON picking_order_aktual.picking_order_id = pk.picking_order_id
					AND picking_order_aktual.picking_order_plan_id = pld.picking_order_plan_id
				LEFT JOIN sku
					ON sku.sku_id = pld.sku_id
				LEFT JOIN principle
					ON principle.principle_id = sku.principle_id
				WHERE pk.picking_order_id = '" . $ppb_id . "'
				AND do.tipe_delivery_order_id = 'EE5CD3F7-F7E3-475E-A7B4-67FD5AB65975'
				AND pld.delivery_order_id IS NULL
				GROUP BY principle_kode,
						pld.sku_id,
						sku.sku_kode,
						sku.sku_nama_produk,
						sku.sku_kemasan,
						sku.sku_satuan,
						pld.sku_stock_qty_ambil,
					  picking_order_aktual.sku_stock_qty_ambil
				ORDER BY sku.sku_kode ASC
                ";
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function GetDataDetailCanvas($ppb_id)
	{
		$sql = "SELECT
					principle.principle_kode,
					pld.sku_id,
					sku.sku_kode,
					sku.sku_nama_produk,
					sku.sku_kemasan,
					sku.sku_satuan,
					pld.sku_stock_qty_ambil AS qty,
					picking_order_aktual.sku_stock_qty_ambil AS qty_ambil
				FROM picking_order pk
				LEFT JOIN picking_list_detail pldd ON pk.picking_list_id = pldd.picking_list_id
				LEFT JOIN picking_list_detail_2 pldd2 ON pldd.picking_list_detail_id = pldd2.picking_list_detail_id
				LEFT JOIN delivery_order do ON pldd2.delivery_order_id = do.delivery_order_id
				LEFT JOIN picking_order_plan pld 
					ON pk.picking_order_id = pld.picking_order_id
					AND pldd2.sku_id = pld.sku_id
				LEFT JOIN (SELECT
					picking_order_aktual_h.picking_order_id,
					picking_order_aktual_d.picking_order_plan_id,
					picking_order_aktual_d.sku_id,
					SUM(picking_order_aktual_d.sku_stock_qty_ambil) AS sku_stock_qty_ambil
				FROM picking_order_aktual_h
				LEFT JOIN picking_order_aktual_d
					ON picking_order_aktual_h.picking_order_aktual_h_id = picking_order_aktual_d.picking_order_aktual_h_id
				WHERE picking_order_aktual_d.delivery_order_id IS NULL
				GROUP BY picking_order_aktual_h.picking_order_id,
						picking_order_aktual_d.picking_order_plan_id,
						picking_order_aktual_d.sku_id) picking_order_aktual
					ON picking_order_aktual.picking_order_id = pk.picking_order_id
					AND picking_order_aktual.picking_order_plan_id = pld.picking_order_plan_id
				LEFT JOIN sku
					ON sku.sku_id = pld.sku_id
				LEFT JOIN principle
					ON principle.principle_id = sku.principle_id
				WHERE pk.picking_order_id = '" . $ppb_id . "'
				AND do.tipe_delivery_order_id = 'B608AD49-2E8E-4289-8463-EC90DAAFB971'
				AND pld.delivery_order_id IS NULL
				GROUP BY principle_kode,
						pld.sku_id,
						sku.sku_kode,
						sku.sku_nama_produk,
						sku.sku_kemasan,
						sku.sku_satuan,
						pld.sku_stock_qty_ambil,
					  picking_order_aktual.sku_stock_qty_ambil
				ORDER BY sku.sku_kode ASC
                ";
		$query = $this->db->query($sql);

		return $query->result_array();
	}

	public function GetDataDetail($ppb_id, $tipe)
	{
		if ($tipe == 'Bulk') {
			return $this->GetDataDetailBulk($ppb_id);
		}

		if ($tipe == 'Flush Out') {
			return $this->GetDataDetailFlushOut($ppb_id);
		}

		if ($tipe == 'Reschedule') {
			return $this->GetDataDetailReschedule($ppb_id);
		}

		if ($tipe == 'Canvas') {
			return $this->GetDataDetailCanvas($ppb_id);
		}
	}

	public function getKodeAutoComplete($value)
	{
		return $this->db->select("sku_kode as kode")
			->from("sku")
			->like("sku_kode", $value)->get()->result();
	}
}
