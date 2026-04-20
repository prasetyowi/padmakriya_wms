<?php

date_default_timezone_set('Asia/Jakarta');

class M_CheckInOutShipmentDriver extends CI_Model
{
  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  public function filteredData($dataPost)
  {
    $date = explode(" - ", $dataPost->tglMulai);

    $this->db->select("sl.security_logbook_id as id,
                       sl.security_logbook_kode as kode,
                       sl.delivery_order_batch_id as do_batch_id,
                       sl.is_confirm_departure,
                       ISNULL(FORMAT(sl.security_logbook_tgl_masuk, 'yyyy-MM-dd HH:mm'), '-') as tglMasuk,
                       ISNULL(FORMAT(sl.security_logbook_tgl_keluar, 'yyyy-MM-dd HH:mm'), '-') as tglKeluar,
                       p.principle_nama as principle,
											 k.kendaraan_nopol,
											 ky.karyawan_nama")
      ->from("security_logbook sl")
      ->join("kendaraan k", "sl.kendaraan_id = k.kendaraan_id", "left")
      ->join("karyawan ky", "sl.karyawan_id = ky.karyawan_id", "left")
      ->join("principle p", "sl.principle_id = p.principle_id", "left")
      ->where("FORMAT(sl.security_logbook_tgl_masuk, 'yyyy-MM-dd') >=", date('Y-m-d', strtotime(str_replace("/", "-", $date[0]))))
      ->where("FORMAT(sl.security_logbook_tgl_masuk, 'yyyy-MM-dd') <=", date('Y-m-d', strtotime(str_replace("/", "-", $date[1]))));
    if ($dataPost->kode != "") {
      $this->db->where("sl.security_logbook_kode", $dataPost->kode);
    }
    $this->db->where("sl.depo_id", $this->session->userdata('depo_id'));
    $this->db->where("sl.flag", 'Check In Out Shipment Driver');

    $this->db->order_by("sl.security_logbook_kode", "ASC");

    return $this->db->get()->result();
  }

  public function getDepoPrefix($depo_id)
  {
    $listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
    return $listDoBatch->row();
  }

  public function getDataPrinciple()
  {
    return $this->db->select('principle_id, principle_kode, principle_nama')->from('principle')
      ->order_by('principle_nama')
      ->get()->result();
  }

  public function getDataEkspedisi()
  {
    return $this->db->select('ekspedisi_id, ekspedisi_kode, ekspedisi_nama')->from('ekspedisi')
      ->order_by('ekspedisi_nama')
      ->get()->result();
  }

  public function getDataDriver()
  {
    return $this->db->select('karyawan_id, karyawan_nama')->from('karyawan')
      ->where("depo_id", $this->session->userdata('depo_id'))
      ->where('karyawan_level_id', '339D8AC2-C6CE-4B47-9BFC-E372592AF521')
      ->order_by('karyawan_nama')
      ->get()->result();
  }

  public function getDataNopols()
  {
    return $this->db->distinct()->select('kendaraan.kendaraan_id, kendaraan.kendaraan_nopol')->from('kendaraan')
      ->join("delivery_order_batch", "kendaraan.kendaraan_id = delivery_order_batch.kendaraan_id", "left")
      ->where("delivery_order_batch.depo_id", $this->session->userdata('depo_id'))
      ->where('delivery_order_batch.delivery_order_batch_status', 'in transit validation completed')
      // ->where_not_in('delivery_order_batch.delivery_order_batch_status', 'Completed', 'Closing Delivery Confirm')
      ->order_by('kendaraan.kendaraan_nopol')
      ->get()->result();
  }

  public function getDataKaryawanByNopol($dataPost)
  {


    return $this->db->query("SELECT DISTINCT
                              karyawan.karyawan_id AS id,
                              karyawan.karyawan_nama AS nama
                            FROM delivery_order_batch
                            LEFT JOIN karyawan
                              ON delivery_order_batch.karyawan_id = delivery_order_batch.karyawan_id
                            WHERE delivery_order_batch.depo_id = '" . $this->session->userdata('depo_id') . "'
                            AND delivery_order_batch.delivery_order_batch_status = 'in transit validation completed'
                            AND delivery_order_batch.kendaraan_id = '$dataPost->kendaraanId'
                            AND karyawan.karyawan_id in(delivery_order_batch.karyawan_id)
                            ORDER BY karyawan_nama")->result();
  }

  public function getDataDeliveryOrderBatch($dataPost)
  {

    $karyawan = $this->db->select("karyawan_telepon as phone")->from('karyawan')->where('karyawan_id', $dataPost->karyawanId)->get()->row();

    $deliveryOrderBatch =  $this->db->select('delivery_order_batch_id as id, delivery_order_batch_kode as kode')
      ->from('delivery_order_batch')
      ->where("depo_id", $this->session->userdata('depo_id'))
      ->where('delivery_order_batch.delivery_order_batch_status', 'in transit validation completed')
      // ->where_not_in('delivery_order_batch_status', 'Completed', 'Closing Delivery Confirm')
      ->where("karyawan_id", $dataPost->karyawanId)
      ->order_by('delivery_order_batch_id')
      ->get()->result();

    $response = [
      'karyawan' => $karyawan,
      'deliveryOrderBatch' => $deliveryOrderBatch
    ];

    return $response;
  }

  public function requestGetNopolByFDJR($dataPost)
  {
    return $this->db->select('k.kendaraan_nopol as nopol')
      ->from('delivery_order_batch fdjr')
      ->join('kendaraan k', 'fdjr.kendaraan_id = k.kendaraan_id', 'left')
      ->where("fdjr.delivery_order_batch_id", $dataPost->deliveryOrderBatchId)
      ->get()->row();
  }

  public function saveData($logSecId, $kendaraanId, $karyawanId, $kmKeberangkatan, $kmKembali, $generate_kode, $keterangan, $listDeliveryOrderBatch, $noHandphone, $tglTrukKeberangkatan, $jamTrukKeberangkatan, $name_file, $mode)
  {

    if ($mode == 'add') {
      //save to header
      $this->db->set("security_logbook_id", $logSecId);
      // $this->db->set("principle_id", $principle);
      // $this->db->set("ekspedisi_id", $ekspedisi);
      // $this->db->set("delivery_order_batch_id", $deliveryOrderBatch);
      $this->db->set("security_logbook_kode", $generate_kode);
      // $this->db->set("security_logbook_nama_driver", $namaDriver);
      $this->db->set("security_logbook_no_hp", $noHandphone);
      // $this->db->set("security_logbook_nopol", $nopol);
      // $this->db->set("security_logbook_total_ctn", $totalCatatanSJ);
      $this->db->set("flag", 'Check In Out Shipment Driver');
      $this->db->set("security_logbook_tgl_masuk", "GETDATE()", false);
      $this->db->set("security_logbook_tgl_create", "GETDATE()", false);
      $this->db->set("security_logbook_who_create", $this->session->userdata('pengguna_username'));
      $this->db->set("depo_id", $this->session->userdata('depo_id'));
      $this->db->set("kendaraan_id", $kendaraanId);
      $this->db->set("karyawan_id", $karyawanId);
      $this->db->set("security_logbook_km_berangkat", $kmKeberangkatan);
      $this->db->set("security_logbook_keterangan", $keterangan == "" ? NULL : $keterangan);
      $this->db->insert('security_logbook');

      //insert to detail
      if ($name_file !== NULL) {
        $insertBatch = [];
        foreach (json_decode($name_file) as $key => $value) {
          $logSecDetailId = $this->M_Vrbl->Get_NewID();
          $logSecDetailId = $logSecDetailId[0]['NEW_ID'];
          // $insertBatch[$key] = [
          //   'security_logbook_detail_id' => $logSecDetailId,
          //   'security_logbook_id' => $logSecId,
          //   'security_logbook_detail_attachment' => $value
          // ];
          $this->db->set("security_logbook_detail_id", $logSecDetailId);
          $this->db->set("security_logbook_id", $logSecId);
          $this->db->set("security_logbook_detail_attachment", $value);
          $this->db->insert('security_logbook_detail');
        }
        // $this->db->insert_batch('security_logbook_detail', $insertBatch);
      }

      foreach ($listDeliveryOrderBatch as $key => $value) {
        $logSecDetail2Id = $this->M_Vrbl->Get_NewID();
        $logSecDetail2Id = $logSecDetail2Id[0]['NEW_ID'];

        $this->db->set("security_logbook_detail_2_id", $logSecDetail2Id);
        $this->db->set("security_logbook_id", $logSecId);
        $this->db->set("delivery_order_batch_id", $value);
        $this->db->insert('security_logbook_detail_2');
      }
    }

    if ($mode == 'edit') {
      $this->db->set("kendaraan_id", $kendaraanId);
      $this->db->set("karyawan_id", $karyawanId);
      $this->db->set("security_logbook_km_berangkat", $kmKeberangkatan);
      $this->db->set("security_logbook_km_kembali", $kmKembali == "" ? NULL : $kmKembali);
      $this->db->set("security_logbook_keterangan", $keterangan == "" ? NULL : $keterangan);
      $this->db->set("security_logbook_no_hp", $noHandphone);
      $this->db->where("security_logbook_id", $this->input->post('shipmentDriverId'));
      $this->db->update('security_logbook');


      if ($name_file !== NULL) {
        $insertBatch = [];
        foreach (json_decode($name_file) as $key => $value) {
          $logSecDetailId = $this->M_Vrbl->Get_NewID();
          $logSecDetailId = $logSecDetailId[0]['NEW_ID'];
          // $insertBatch[$key] = [
          //   'security_logbook_detail_id' => $logSecDetailId,
          //   'security_logbook_id' => $logSecId,
          //   'security_logbook_detail_attachment' => $value
          // ];
          $this->db->set("security_logbook_detail_id", $logSecDetailId);
          $this->db->set("security_logbook_id", $this->input->post('shipmentDriverId'));
          $this->db->set("security_logbook_detail_attachment", $value);
          $this->db->insert('security_logbook_detail');
        }
        // $this->db->insert_batch('security_logbook_detail', $insertBatch);
      }

      $this->db->delete("security_logbook_detail_2", ['security_logbook_id' => $this->input->post('shipmentDriverId')]);
      foreach ($listDeliveryOrderBatch as $key => $value) {
        $logSecDetail2Id = $this->M_Vrbl->Get_NewID();
        $logSecDetail2Id = $logSecDetail2Id[0]['NEW_ID'];

        $this->db->set("security_logbook_detail_2_id", $logSecDetail2Id);
        $this->db->set("security_logbook_id", $this->input->post('shipmentDriverId'));
        $this->db->set("delivery_order_batch_id", $value);
        $this->db->insert('security_logbook_detail_2');
      }
    }
  }

  public function deleteData($dataPost)
  {

    $this->db->trans_begin();

    $this->db->where("security_logbook_id", $dataPost->shipmentDriverId);
    $this->db->delete('security_logbook');

    $getFileDetail = $this->db->select('security_logbook_detail_attachment as file')->from('security_logbook_detail')->where('security_logbook_id', $dataPost->shipmentDriverId)->get()->result();

    foreach ($getFileDetail as $key => $value) {
      if ($value->file != NULL) {
        unlink('assets/images/uploads/LogSecurity/' . $value->file);
      }
    }

    $this->db->where("security_logbook_id", $dataPost->shipmentDriverId);
    $this->db->delete('security_logbook_detail');

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return false;
    } else {
      $this->db->trans_commit();
      return true;
    }
  }

  public function getDataInboundSupplier($shipmentDriverId, $mode)
  {

    if ($mode == 'form') {
      $header = $this->db->select("sl.*")
        ->from('security_logbook sl')
        ->where('sl.security_logbook_id', $shipmentDriverId)->get()->row();
    }

    if ($mode == 'view') {
      $header = $this->db->select("sl.*, kendaraan_nopol, karyawan_nama")
        ->from('security_logbook sl')
        ->join('kendaraan', 'sl.kendaraan_id = kendaraan.kendaraan_id', 'left')
        ->join('karyawan', 'sl.karyawan_id = karyawan.karyawan_id', 'left')
        ->where('sl.security_logbook_id', $shipmentDriverId)->get()->row();
    }



    $detailImg = $this->db->select("security_logbook_detail_attachment as file")
      ->from('security_logbook_detail')
      ->where('security_logbook_id', $shipmentDriverId)->get()->result();

    $detailDelivery = $this->db->select("delivery_order_batch.delivery_order_batch_id as id, delivery_order_batch.delivery_order_batch_kode as kode")
      ->from('security_logbook_detail_2')
      ->join('delivery_order_batch', 'security_logbook_detail_2.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id')
      ->where('security_logbook_detail_2.security_logbook_id', $shipmentDriverId)->get()->result();

    $response = [
      'header' => $header,
      'detail' => $detailImg,
      'detail2' => $detailDelivery
    ];
    return $response;

    // return $this->db->select("sl.*, sld.security_logbook_detail_attachment as file")
    //   ->from('security_logbook sl')
    //   ->join('security_logbook_detail sld', 'sl.security_logbook_id = sld.security_logbook_id', 'left')
    //   ->where('sl.security_logbook_id', $shipmentDriverId)->get()->result();
  }

  public function konfirmasiData($dataPost)
  {

    $this->db->trans_begin();

    $kendaraanId = $this->db->select("kendaraan_id")->from("security_logbook")->where("security_logbook_id", $dataPost->shipmentDriverId)->get()->row()->kendaraan_id;

    $this->db->update('security_logbook', [
      'security_logbook_tgl_keluar' => date('Y-m-d H:i:s'),
      'security_logbook_km_kembali' => $dataPost->kmKembali,
    ], ['security_logbook_id' => $dataPost->shipmentDriverId]);


    $getBatch = $this->db->select("delivery_order_batch.delivery_order_batch_id, delivery_order_batch.kendaraan_km_awal")
      ->from("security_logbook_detail_2")
      ->join('delivery_order_batch', 'security_logbook_detail_2.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id', 'left')
      ->where("security_logbook_detail_2.security_logbook_id", $dataPost->shipmentDriverId)->get()->result();
    if ($getBatch) {
      foreach ($getBatch as $key => $value) {
        $this->db->update('delivery_order_batch', [
          'kendaraan_km_akhir' => $dataPost->kmKembali,
          'kendaraan_km_terpakai' => $dataPost->kmKembali - $value->kendaraan_km_awal,
        ], ['delivery_order_batch_id' => $value->delivery_order_batch_id]);

        $this->db->update('kendaraan', [
          'kendaraan_last_kilometer' => $dataPost->kmKembali,
        ], ['kendaraan_id' => $kendaraanId]);
      }
    }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $response = [
        'status' => false,
        'message' => "Data gagal dikonfirmasi"
      ];
    } else {
      $this->db->trans_commit();
      $response = [
        'status' => true,
        'message' => "Data berhasil dikonfirmasi"
      ];
    }

    return $response;
  }

  public function KonfirmasiBerangkat($dataPost)
  {
    $this->db->trans_begin();

    $this->db->update('security_logbook', [
      'is_confirm_departure' => 1,
    ], ['security_logbook_id' => $dataPost->id]);

    $kmAwal = $this->db->select("security_logbook_km_berangkat")->from("security_logbook")->where("security_logbook_id", $dataPost->id)->get()->row()->security_logbook_km_berangkat;

    $getBatch = $this->db->select("delivery_order_batch_id")->from("security_logbook_detail_2")->where("security_logbook_id", $dataPost->id)->get()->result();
    if ($getBatch) {
      foreach ($getBatch as $key => $value) {
        $this->db->update('delivery_order_batch', [
          'delivery_order_batch_status' => 'in transit',
          'kendaraan_km_awal' => $kmAwal,
        ], ['delivery_order_batch_id' => $value->delivery_order_batch_id]);

        $this->db->update('delivery_order', [
          'delivery_order_status' => 'in transit',
        ], ['delivery_order_batch_id' => $value->delivery_order_batch_id]);

        $query = $this->db->select('*')->from('delivery_order')->where('delivery_order_batch_id', $value->delivery_order_batch_id)->get()->result();

        foreach ($query as $val) {
          $this->db->set('delivery_order_progress_id', 'NEWID()', FALSE);
          $this->db->set('delivery_order_id', $val->delivery_order_id);
          $this->db->set('status_progress_id', '5D07AD85-E9C1-4B88-BECC-84CF0F766919');
          $this->db->set('status_progress_nama', 'in transit');
          $this->db->set('delivery_order_progress_create_who', $this->session->userdata('pengguna_username'));
          $this->db->set('delivery_order_progress_create_tgl', 'GETDATE()', FALSE);
          $this->db->insert('delivery_order_progress');
        }
      }
    }

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return 0;
    } else {
      $this->db->trans_commit();
      return 1;
    }
  }
}
