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

    public function generateKode($date,$prefix,$unit)
    {
        // $this->db->trans_start();
        $sql = "DECLARE	
		            @ResultKode varchar(50)
                
                EXEC GenCodeGeneral 
                    @TglTrans = '".$date."',
                    @Prefik = '".$prefix."',
                    @unit = '".$unit."',
                    @ResultKode = @ResultKode OUTPUT
                    

                SELECT	@ResultKode as 'ResultKode'
               

                ";

        $result = $this->db->query($sql);

        return $sql;
    
    }
    
    public function SavePengirimanBarang($dataSTP,$sku_id,$jumlah_ambil_plan,$jumlah_ambil_aktual,$jumlah_serah_terima,$delivery_order_id,$jumlah_paket,$jumlah_serah_terima_paket){

        $this->db->trans_start();

        $newID1 = $this->M_Function->Get_NewID();
		$newID = $newID1[0]['kode'];

        $this->db->set('serah_terima_kirim_id', $dataSTP['serah_terima_kirim_id'] );
        $this->db->set('delivery_order_batch_id', $dataSTP['delivery_order_batch_id'] );
        $this->db->set('picking_order_id', $dataSTP['picking_order_id'] );
		$this->db->set('serah_terima_kirim_kode' , $dataSTP['serah_terima_kirim_kode']);
		$this->db->set('serah_terima_kirim_tgl' , $dataSTP['serah_terima_kirim_tgl']);
		$this->db->set('serah_terima_kirim_who' , $dataSTP['serah_terima_kirim_who']);
		$this->db->set('serah_terima_kirim_status' , 'Open');
		$this->db->set('serah_terima_kirim_keterangan' , $dataSTP['serah_terima_kirim_keterangan']);

        $this->db->insert('serah_terima_kirim');

        // update batch do, delivery_order_batch_status, masukkan no picklist

        // $this->db->set('serah_terima_kirim_id', $dataSTP['serah_terima_kirim_id'] );
        // $this->db->set('delivery_order_batch_status', 'in transit' );
        // $this->db->where('delivery_order_batch_id', $dataSTP['delivery_order_batch_id']);
        // $this->db->update('delivery_order_batch');

        // jika sku_id ada do maka tipe == bulk
        //insert ke serah_terima_kirim_d1 detail1
        if ($sku_id != null) {
            foreach ($sku_id as $key => $value) {
                $ID1 = $this->M_Function->Get_NewID();
                $ID = $ID1[0]['kode'];
                
                $this->db->set('serah_terima_kirim_d1_id', $ID );
                $this->db->set('serah_terima_kirim_id', $dataSTP['serah_terima_kirim_id'] );
                $this->db->set('sku_id', $value);
                $this->db->set('jumlah_ambil_plan', $jumlah_ambil_plan[$key] );
                $this->db->set('jumlah_ambil_aktual', $jumlah_ambil_aktual[$key] );
                $this->db->set('jumlah_ambil_aktual', $jumlah_ambil_aktual[$key] );
                $this->db->set('jumlah_serah_terima', $jumlah_serah_terima[$key] );

                $this->db->insert('serah_terima_kirim_d1');
            }
        }
        // jika ada do maka tipe == standar
        //insert ke serah_terima_kirim_d2 detail2
        if ($delivery_order_id != null) {
            foreach ($delivery_order_id as $key => $value) {
                $ID1 = $this->M_Function->Get_NewID();
                $ID = $ID1[0]['kode'];
                
                $this->db->set('serah_terima_kirim_d2_id', $ID );
                $this->db->set('serah_terima_kirim_id', $dataSTP['serah_terima_kirim_id'] );
                $this->db->set('delivery_order_id', $value);
                $this->db->set('jumlah_paket', $jumlah_paket[$key] );
                $this->db->set('jumlah_serah_terima', $jumlah_serah_terima_paket[$key] );

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
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            return 0;
        } 
      
        return 1;
		
        
    }
    public function UpdatePengirimanBarang($serah_terima_kirim_d1_id,$serah_terima_kirim_d2_id,$jumlah_serah_terima_1,$jumlah_serah_terima_2){

        $this->db->trans_start();

        //update ke serah_terima_kirim_d1 detail1
        if ($serah_terima_kirim_d1_id != null) {
            foreach ($serah_terima_kirim_d1_id as $key => $value) {

                $this->db->set('jumlah_serah_terima', $jumlah_serah_terima_1[$key] );
                $this->db->where('serah_terima_kirim_d1_id', $value );
                $this->db->update('serah_terima_kirim_d1');
            }
        }
        // jika ada do maka tipe == standar
        //update ke serah_terima_kirim_d2 detail2
        if ($serah_terima_kirim_d2_id != null) {
            foreach ($serah_terima_kirim_d2_id as $key => $value) {
                
                $this->db->set('jumlah_serah_terima', $jumlah_serah_terima_2[$key] );
                $this->db->where('serah_terima_kirim_d2_id', $value );
                $this->db->update('serah_terima_kirim_d2');  
            }
        }
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            return 0;
        } 
      
        return 1;
		
        
    }

    public function ConfirmPengirimanBarang($serah_terima_kirim_id,$delivery_order_batch_id,$delivery_order_id){

        $this->db->trans_start();

        $this->db->set('serah_terima_kirim_status' , 'Closed');
        $this->db->where('serah_terima_kirim_id', $serah_terima_kirim_id);
        $this->db->update('serah_terima_kirim');

        // get data last km kendaraan berdasarkan do batch
        $listDoBatch = $this->db->select("kd.kendaraan_last_kilometer")
                                ->from('delivery_order_batch dob')
                                ->join('kendaraan kd','kd.kendaraan_id = dob.kendaraan_id','left')
                                ->where('dob.delivery_order_batch_id', $delivery_order_batch_id)
                                ->get();
        $data_kendaraan =  $listDoBatch->row();

        // update batch do, delivery_order_batch_status, masukkan no STP, last km kendaraan

        $this->db->set('serah_terima_kirim_id', $serah_terima_kirim_id );
        $this->db->set('delivery_order_batch_status', 'in transit' );
        $this->db->set('kendaraan_km_awal', $data_kendaraan->kendaraan_last_kilometer);
        $this->db->where('delivery_order_batch_id', $delivery_order_batch_id);
        $this->db->update('delivery_order_batch');
        //update status do jika ada do
        if ($delivery_order_id != null) {
            foreach ($delivery_order_id as $key => $value) {

                // update delivery_order_status di tbl delivery order
                $this->db->set('delivery_order_status', 'in transit' );
                $this->db->where('delivery_order_id', $value);
                $this->db->update('delivery_order');
                // insert ke table delivery_order_progress id status in progress item request
                $this->db->set('delivery_order_progress_id', "NewID()",FALSE);
                $this->db->set('delivery_order_id', $value );
                $this->db->set('status_progress_id', '5D07AD85-E9C1-4B88-BECC-84CF0F766919' );
                $this->db->set('status_progress_nama', 'in transit' );
                $this->db->set('delivery_order_progress_create_who', $this->session->userdata('pengguna_username') );
                $this->db->set('delivery_order_progress_create_tgl', "GETDATE()",FALSE );
                $this->db->insert('delivery_order_progress');
            }
        }
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            return 0;
        } 
      
        return 1;
		
        
    }

    public function getDataPengirimanDetail($pengiriman_barang_id){
        $sql = "SELECT
                    st.serah_terima_kirim_kode,
                    st.delivery_order_batch_id,
                    st.serah_terima_kirim_id,
                    st.serah_terima_kirim_keterangan,
                    st.serah_terima_kirim_status,
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
                WHERE st.serah_terima_kirim_id = '".$pengiriman_barang_id."'
        ";

        $query = $this->db->query($sql);

        return $query->row();

    }

    public function getDataPengirimanDetailD1($pengiriman_barang_id){
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
                WHERE std1.serah_terima_kirim_id = '".$pengiriman_barang_id."'
        ";

        $query = $this->db->query($sql);

        return $query->result_array();

    }

    public function getDataPengirimanDetailD2($pengiriman_barang_id){
        $sql = "SELECT
                    std2.*,
                    do.delivery_order_kode,
                    do.delivery_order_status,
                    typ.tipe_delivery_order_nama,
                    do.delivery_order_kirim_nama,
                    do.delivery_order_kirim_alamat,
                    do.delivery_order_kirim_telp
                FROM serah_terima_kirim_d2 std2
                LEFT JOIN delivery_order do
                    ON std2.delivery_order_id = do.delivery_order_id
                LEFT JOIN tipe_delivery_order AS typ
                    ON typ.tipe_delivery_order_id = do.tipe_delivery_order_id
                WHERE std2.serah_terima_kirim_id = '".$pengiriman_barang_id."'
        ";

        $query = $this->db->query($sql);

        return $query->result_array();

    }

    public function getDataPengirimanSearch($ppb_id,$tgl,$tgl2)
    {
        $depo_id = $this->session->userdata('depo_id');
        $sql = "";
        $sql .= "SELECT
                    stp.*,
                    FORMAT(stp.serah_terima_kirim_tgl, 'dd-MM-yyyy') as tgl_create,
                    dob.delivery_order_batch_kode,
                    po.picking_order_kode
                FROM serah_terima_kirim stp
                LEFT JOIN delivery_order_batch dob
                    ON dob.delivery_order_batch_id = stp.delivery_order_batch_id
                LEFT JOIN picking_order po
                    ON po.picking_order_id = stp.picking_order_id
                WHERE FORMAT(stp.serah_terima_kirim_tgl, 'yyyy-MM-dd') BETWEEN '$tgl' and '$tgl2'
                AND po.depo_id = '$depo_id'
                ";    
        
        if ($ppb_id != null) {
            $sql.="AND stp.picking_order_id LIKE '%$ppb_id%' ";
        }
        $query = $this->db->query($sql);

        return $query->result_array();
    }
    public function getListDoBatchPick()
    {
        $listDoBatch = $this->db->select("*")->from('delivery_order_batch')->where('depo_id',$this->session->userdata('depo_id'))->where('picking_order_id is NOT NULL')->get();
        return $listDoBatch->result_array();
    }
    public function getListPickOrder()
    {
        $listDoBatch = $this->db->select("po.*")
                                ->from('picking_order po')
                                // ->join('')
                                ->join('picking_list pk','pk.picking_list_id = po.picking_list_id','left')
                                ->join('delivery_order_batch dob','dob.delivery_order_batch_id = pk.delivery_order_batch_id','left')
                                ->where('po.depo_id',$this->session->userdata('depo_id'))
                                ->where('dob.serah_terima_kirim_id IS NULL')
                                ->where('po.picking_order_status','Selesai')->get();
        return $listDoBatch->result_array();
    }
    public function GetDoBatchByPickOrderId($ppb_id)
    {
        $listDoBatch = $this->db->select("dob.delivery_order_batch_id,dob.delivery_order_batch_kode,dob.tipe_delivery_order_id,typ.tipe_delivery_order_nama,kry.karyawan_id,kry.karyawan_nama")
                                ->from('picking_order po')
                                ->join('picking_list pk','pk.picking_list_id = po.picking_list_id','left')
                                ->join('delivery_order_batch dob','dob.delivery_order_batch_id = pk.delivery_order_batch_id','left')
                                ->join('karyawan as kry', 'kry.karyawan_id = dob.karyawan_id', 'left')
                                ->join('tipe_delivery_order as typ', 'typ.tipe_delivery_order_id = dob.tipe_delivery_order_id', 'left')
                                ->where('po.picking_order_id',$ppb_id)
                                ->get();
        return $listDoBatch->row();
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
					SUM(pld.sku_stock_qty_ambil) AS qty,
					SUM(picking_order_aktual.sku_stock_qty_ambil) AS qty_ambil
				FROM picking_order pk
				LEFT JOIN picking_order_plan pld
					ON pk.picking_order_id = pld.picking_order_id
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
				WHERE pk.picking_order_id = '".$ppb_id."'
				AND pld.delivery_order_id IS NULL
				GROUP BY principle_kode,
						pld.sku_id,
						sku.sku_kode,
						sku.sku_nama_produk,
						sku.sku_kemasan,
						sku.sku_satuan
                ";
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

    public function GetDataDetailStandar($ppb_id)
    {
        $listDoBatch = $this->db->select("do.delivery_order_id,
                                        do.delivery_order_kode,
                                        do.delivery_order_status,
                                        typ.tipe_delivery_order_nama,
                                        do.delivery_order_kirim_nama,
                                        do.delivery_order_kirim_alamat,
                                        do.delivery_order_kirim_telp,
                                        COUNT(pack.delivery_order_id) as jumlah_packed")
                                ->from('picking_order po')
                                ->join('picking_list pk','pk.picking_list_id = po.picking_list_id','left')
                                ->join('picking_list_detail pkd','pkd.picking_list_id = pk.picking_list_id','left')
                                ->join('delivery_order do','pkd.delivery_order_id = do.delivery_order_id', 'left')
                                ->join('tipe_delivery_order as typ', 'typ.tipe_delivery_order_id = do.tipe_delivery_order_id', 'left')
                                ->join('packing_h pack','pkd.delivery_order_id = pack.delivery_order_id', 'left')
                                ->where('po.picking_order_id',$ppb_id)
                                ->where('do.tipe_delivery_order_id','0E626A53-82FC-4EA6-A4A2-1265279D6E1C')//where tipe do = standar
                                ->group_by('do.delivery_order_id')
                                ->group_by('do.delivery_order_kode')
                                ->group_by('do.delivery_order_status')
                                ->group_by('typ.tipe_delivery_order_nama')
                                ->group_by('do.delivery_order_kirim_nama')
                                ->group_by('do.delivery_order_kirim_alamat')
                                ->group_by('do.delivery_order_kirim_telp')
                                ->get();
        return $listDoBatch->result_array();
    }
}
