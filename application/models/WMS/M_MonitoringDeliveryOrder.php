<?php

class M_MonitoringDeliveryOrder extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function getPerusahaan()
    {
        $query = $this->db->query("SELECT client_wms_id, client_wms_nama FROM client_wms WHERE client_wms_is_deleted = 0 AND client_wms_is_aktif = 1");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getPrinciple()
    {
        $query = $this->db->query("SELECT principle_id, principle_kode FROM principle WHERE principle_is_deleted = 0 AND principle_is_aktif = 1");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getDataFilter($perusahaan, $principle, $tgl1, $tgl2, $status)
    {
        if ($perusahaan == '') {
            $perusahaan = '';
        } else {
            $perusahaan = "AND a.client_wms_id = '$perusahaan'";
        }

        if ($principle == '') {
            $principle = '';
        } else {
            $principle = "AND c.principle_id = '$principle'";
        }

        if ($status == '') {
            $status = '';
        } else {
            $status = "AND a.delivery_order_status = '$status'";
        }

        $depo_id = $this->session->userdata('depo_id');

        // $query = $this->db->query("SELECT a.client_wms_id, d.client_wms_nama, c.principle_id, ISNULL(c.principle_kode, '') AS principle_kode,
        // (SELECT COUNT(*) 
        // FROM delivery_order_draft dod
        // LEFT JOIN sales_order so ON dod.sales_order_id = so.sales_order_id
        // WHERE FORMAT(dod.delivery_order_draft_tgl_aktual_kirim, 'yyyy-MM-dd') >= '$tgl1' 
        // 	AND FORMAT(dod.delivery_order_draft_tgl_aktual_kirim, 'yyyy-MM-dd') <= '$tgl2'   
        // 	AND dod.depo_id = '$depo_id' 
        // 	AND dod.delivery_order_draft_status = 'Approved'
        // 	AND NOT EXISTS (
        // 		SELECT delivery_order_draft_id
        // 		FROM delivery_order o
        // 		WHERE o.delivery_order_draft_id = dod.delivery_order_draft_id
        // 		AND o.delivery_order_draft_id is not null
        // 	)
        // 	 AND dod.client_wms_id = a.client_wms_id 
        // 	 AND so.principle_id = c.principle_id   
        // 	) AS in_progress_count,
        // --COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'in progress' THEN a.delivery_order_id END) AS in_progress_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'canceled' THEN a.delivery_order_id END) AS canceled_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status NOT IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered', 'approved') THEN a.delivery_order_id END) AS exception_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'delivered' THEN a.delivery_order_id END) AS delivered_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'not delivered' THEN a.delivery_order_id END) AS not_delivered_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'partially delivered' THEN a.delivery_order_id END) AS partially_delivered_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered') OR a.delivery_order_status NOT IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered', 'approved') THEN a.delivery_order_id END) AS total_delivery_orders,
        // CASE 
        //     WHEN COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered') OR a.delivery_order_status NOT IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered', 'approved') THEN a.delivery_order_id END) = 0 THEN 0
        //     ELSE (COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('delivered', 'not delivered', 'partially delivered') THEN a.delivery_order_id END) / CAST(COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered') OR a.delivery_order_status NOT IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered', 'approved') THEN a.delivery_order_id END) AS FLOAT)) * 100
        // END AS presentase
        // FROM delivery_order a
        // LEFT JOIN sales_order b on a.sales_order_id = b.sales_order_id
        // LEFT JOIN principle c on b.principle_id = c.principle_id
        // LEFT JOIN client_wms d on a.client_wms_id = d.client_wms_id 
        // WHERE FORMAT(a.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') >= '$tgl1' AND FORMAT(a.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') <= '$tgl2'   
        // AND a.depo_id = '$depo_id'
        // $perusahaan
        // $principle
        // $status                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
        // GROUP BY a.client_wms_id, d.client_wms_nama, c.principle_id, c.principle_kode
        // ORDER BY d.client_wms_nama, c.principle_kode ASC");

        $query = $this->db->query("SELECT a.client_wms_id, d.client_wms_nama, c.principle_id, ISNULL(c.principle_kode, '') AS principle_kode,
		(SELECT COUNT(*) 
		FROM delivery_order_draft dod
		LEFT JOIN sales_order so ON dod.sales_order_id = so.sales_order_id
		WHERE FORMAT(dod.delivery_order_draft_tgl_aktual_kirim, 'yyyy-MM-dd') >= '$tgl1' 
			AND FORMAT(dod.delivery_order_draft_tgl_aktual_kirim, 'yyyy-MM-dd') <= '$tgl2'   
			AND dod.depo_id = '$depo_id' 
			AND dod.delivery_order_draft_status = 'Approved'
			AND NOT EXISTS (
				SELECT delivery_order_draft_id
				FROM delivery_order o
				WHERE o.delivery_order_draft_id = dod.delivery_order_draft_id
				AND o.delivery_order_draft_id is not null
			)
			 AND dod.client_wms_id = a.client_wms_id 
			 AND so.principle_id = c.principle_id   
			) AS in_progress_count,
        --COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'in progress' THEN a.delivery_order_id END) AS in_progress_count,
        COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'canceled' THEN a.delivery_order_id END) AS canceled_count,
		COUNT(DISTINCT CASE WHEN a.delivery_order_status NOT IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered', 'approved') THEN a.delivery_order_id END) AS exception_count,
        COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'delivered' THEN a.delivery_order_id END) AS delivered_count,
        COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'not delivered' THEN a.delivery_order_id END) AS not_delivered_count,
        COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'partially delivered' THEN a.delivery_order_id END) AS partially_delivered_count
        FROM (
					SELECT delivery_order_id, depo_id, delivery_order_tgl_aktual_kirim, delivery_order_status,
				   CASE WHEN sales_order_id IS NULL THEN canvas_id ELSE sales_order_id END AS reffid, 
			       client_wms_id from delivery_order
			 ) a
        LEFT JOIN ( SELECT sales_order_id AS id, principle_id, 'SO' AS flag FROM sales_order WHERE tipe_sales_order_id NOT IN('7EB8BB9F-7162-4155-8084-FE2EE45AE32D')
					UNION
					SELECT canvas_id AS id, principle_id,'CANVAS' AS flag FROM canvas 
		) b ON a.reffid = b.id
        LEFT JOIN principle c on b.principle_id = c.principle_id
        LEFT JOIN client_wms d on a.client_wms_id = d.client_wms_id 
        WHERE FORMAT(a.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') >= '$tgl1' AND FORMAT(a.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') <= '$tgl2'   
        AND a.depo_id = '$depo_id'
        $perusahaan
        $principle
        $status                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         
        GROUP BY a.client_wms_id, d.client_wms_nama, c.principle_id, c.principle_kode
        ORDER BY d.client_wms_nama, c.principle_kode ASC");


        // $query = $this->db->query("SELECT a.client_wms_id, e.client_wms_nama, c.principle_id, ISNULL(d.principle_kode, '') AS principle_kode,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'in progress' THEN a.delivery_order_id END) AS in_progress_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'canceled' THEN a.delivery_order_id END) AS canceled_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status NOT IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered', 'approved') THEN a.delivery_order_id END) AS exception_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'delivered' THEN a.delivery_order_id END) AS delivered_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'not delivered' THEN a.delivery_order_id END) AS not_delivered_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'partially delivered' THEN a.delivery_order_id END) AS partially_delivered_count,
        // COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered') OR a.delivery_order_status NOT IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered', 'approved') THEN a.delivery_order_id END) AS total_delivery_orders,
        // CASE 
        //     WHEN COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered') OR a.delivery_order_status NOT IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered', 'approved') THEN a.delivery_order_id END) = 0 THEN 0
        //     ELSE (COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('delivered', 'not delivered', 'partially delivered') THEN a.delivery_order_id END) / CAST(COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered') OR a.delivery_order_status NOT IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered', 'approved') THEN a.delivery_order_id END) AS FLOAT)) * 100
        // END AS presentase
        // FROM delivery_order a
        // LEFT JOIN delivery_order_detail b on a.delivery_order_id = b.delivery_order_id
        // LEFT JOIN sku c on b.sku_id = c.sku_id
        // LEFT JOIN principle d on d.principle_id = c.principle_id
        // LEFT JOIN client_wms e on a.client_wms_id = e.client_wms_id 
        // WHERE FORMAT(a.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') >= '$tgl1' AND FORMAT(a.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') <= '$tgl2'   
        // AND a.depo_id = '$depo_id'
        // $perusahaan
        // $principle
        // $status                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
        // GROUP BY a.client_wms_id, e.client_wms_nama, c.principle_id, d.principle_kode
        // ORDER BY e.client_wms_nama, d.principle_kode ASC");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }

    public function getDetailDO($perusahaan, $principle, $tgl1, $tgl2, $status)
    {
        if ($perusahaan == '') {
            $perusahaan = '';
        } else {
            $perusahaan = "AND a.client_wms_id = '$perusahaan'";
        }

        if ($principle == '') {
            $principle = '';
        } else {
            $principle = "AND c.principle_id = '$principle'";
        }

        if ($status == '') {
            $status = '';
        } else {
            $status = "AND a.delivery_order_status = '$status'";
        }

        $query = $this->db->query("SELECT a.client_wms_id, e.client_wms_nama, c.principle_id, ISNULL(d.principle_kode, '') AS principle_kode,
        COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'in progress' THEN a.delivery_order_id END) AS in_progress_count,
        COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'canceled' THEN a.delivery_order_id END) AS canceled_count,
        COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'delivered' THEN a.delivery_order_id END) AS delivered_count,
        COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'not delivered' THEN a.delivery_order_id END) AS not_delivered_count,
        COUNT(DISTINCT CASE WHEN a.delivery_order_status = 'partially delivered' THEN a.delivery_order_id END) AS partially_delivered_count,
        COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered') THEN a.delivery_order_id END) AS total_delivery_orders,
        CASE 
            WHEN COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered') THEN a.delivery_order_id END) = 0 THEN 0
            ELSE (COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('delivered', 'not delivered', 'partially delivered') THEN a.delivery_order_id END) / CAST(COUNT(DISTINCT CASE WHEN a.delivery_order_status IN ('in progress', 'canceled', 'delivered', 'not delivered', 'partially delivered') THEN a.delivery_order_id END) AS FLOAT)) * 100
        END AS presentase
        FROM delivery_order a
        LEFT JOIN delivery_order_detail b on a.delivery_order_id = b.delivery_order_id
        LEFT JOIN sku c on b.sku_id = c.sku_id
        LEFT JOIN principle d on d.principle_id = c.principle_id
        LEFT JOIN client_wms e on a.client_wms_id = e.client_wms_id 
        WHERE FORMAT(a.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') >= '$tgl1' AND FORMAT(a.delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') <= '$tgl2'   
        $perusahaan
        $principle
        $status                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
        GROUP BY a.client_wms_id, e.client_wms_nama, c.principle_id,     d.principle_kode
        ORDER BY e.client_wms_nama, d.principle_kode ASC");

        if ($query->num_rows() == 0) {
            $query = 0;
        } else {
            $query = $query->result_array();
        }

        return $query;
    }
}
