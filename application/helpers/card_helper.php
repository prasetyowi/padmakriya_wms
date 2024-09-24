<?php

function random_color_part()
{
	return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function random_color()
{
	return "#" . random_color_part() . random_color_part() . random_color_part();
}

function CardDashboard()
{

	$CI = get_instance();

	$CI->load->model('WMS/M_MainDashboard', 'M_Dashboard');

	$data = $CI->M_Dashboard->generateCardDashboard();
	$html = "";
	foreach ($data as $key => $value) {

		$html .= '<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-6"><a href="' . base_url() . $value->url . '" target="_BLANK"><div class="tile-stats"><div class="icon"><i class="' . $value->icon . '" style="color:' . random_color() . '"></i></div><div class="count" id="' . $value->modul . '">' . $value->jum . '</div><h5 name="' . $value->bahasa . '"></h5></div></a></div>';
	}
	return $html;
}

function cardProsesOpname()
{
	$CI = get_instance();

	$CI->load->model('WMS/M_MainDashboard', 'M_Dashboard');

	$data = $CI->M_Dashboard->generateCardDashboard(null, null, null, "opname");
	$html = "";
	foreach ($data as $key => $value) {
		if ($value->modul == "StockOpname") {
			$html .= '<div class="card-opname"><div class="card-opname-parent" onclick="handlerDaftarSuratKerjaDetail(\'' . $value->modul . '\')"><h4 name="' . $value->bahasa . '"></h4><h4 style="margin-right: 20px;">' . $value->jum . '</h4></div></div>';
		}
	}
	return $html;
}

function time_elapsed_string($datetime)
{

	date_default_timezone_set('Asia/Jakarta');

	$etime = time() - strtotime($datetime);
	if ($etime < 1) {
		return $_SESSION['Bahasa'] == "ENG" ? 'just now' : ($_SESSION['Bahasa'] == "IND" ? 'baru saja' : '');
	}

	$a = array(
		12 * 30 * 24 * 60 * 60  =>  $_SESSION['Bahasa'] == "ENG" ? 'years' : ($_SESSION['Bahasa'] == "IND" ? 'tahun' : ''),
		30 * 24 * 60 * 60       =>  $_SESSION['Bahasa'] == "ENG" ? 'months' : ($_SESSION['Bahasa'] == "IND" ? 'bulan' : ''),
		24 * 60 * 60            =>  $_SESSION['Bahasa'] == "ENG" ? 'days' : ($_SESSION['Bahasa'] == "IND" ? 'hari' : ''),
		60 * 60             =>  $_SESSION['Bahasa'] == "ENG" ? 'hours' : ($_SESSION['Bahasa'] == "IND" ? 'jam' : ''),
		60                  =>  $_SESSION['Bahasa'] == "ENG" ? 'minutes' : ($_SESSION['Bahasa'] == "IND" ? 'menit' : ''),
		1                   =>  $_SESSION['Bahasa'] == "ENG" ? 'seconds' : ($_SESSION['Bahasa'] == "IND" ? 'detik' : '')
	);

	foreach ($a as $secs => $str) {
		$d = $etime / $secs;

		if ($d >= 1) {
			$r = round($d);
			return  $r . ' ' . $str . ($r > 1 ? '' : '') . ' yang lalu';
		}
	}
}

function checkLastUpdatedData($params)
{

	date_default_timezone_set('Asia/Jakarta');

	$app = &get_instance();

	$app->db->trans_begin();

	$errorNotSameLastUpdated = false;

	$lastUpdated = ($params->lastUpdated === 'null' || $params->lastUpdated === "" || $params->lastUpdated === null || $params->lastUpdated === NULL || $params->lastUpdated === 'NULL') ? NULL : $params->lastUpdated;
	$fieldDateUpdate = $params->fieldDateUpdate;

	$andMoreWhere = "";

	if (isset($params->addMoreWhere)) {
		foreach ($params->addMoreWhere as $key => $value) {
			$andMoreWhere .= " and " . $value['addMoreWhereField'] . " = '" . $value['addMoreWhereValue'] . "'";
		}
	}

	$getLastUpdatedDb = $app->db->query("SELECT $fieldDateUpdate 
																				FROM $params->table 
																				WHERE $params->whereField = '$params->whereValue' 
																				" . $andMoreWhere . "")->row()->$fieldDateUpdate;

	if ($lastUpdated !== $getLastUpdatedDb) $errorNotSameLastUpdated = true;

	$app->db->set($fieldDateUpdate, date('Y-m-d H:i:s'));
	$app->db->set($params->fieldWhoUpdate, $app->session->userdata('pengguna_username'));
	$app->db->where($params->whereField, $params->whereValue);

	$app->db->update($params->table);

	$getLastUpdatedNew = $app->db->query("SELECT $fieldDateUpdate 
																				FROM $params->table 
																				WHERE $params->whereField = '$params->whereValue' 
																				" . $andMoreWhere . "")->row()->$fieldDateUpdate;


	if ($app->db->trans_status() === FALSE) {
		$app->db->trans_rollback();
		$response = [
			'status' => 401,
			'lastUpdatedNew' => null
		];
	} else if ($errorNotSameLastUpdated) {
		$app->db->trans_rollback();
		$response = [
			'status' => 400,
			'lastUpdatedNew' => null
		];
	} else {
		$app->db->trans_commit();
		$response = [
			'status' => 200,
			'lastUpdatedNew' => $getLastUpdatedNew
		];
	}

	return $response;
}

function responseJson($params)
{
	$app = &get_instance();

	if ($params->lastUpdatedChecked['status'] === 400) {
		$app->db->trans_rollback();
		$response = [
			'status' => 400,
			'message' => 'Data Gagal ' . $params->status,
			'lastUpdatedNew' => null
		];
	} else if ($app->db->trans_status() === FALSE) {
		$app->db->trans_rollback();
		$response = [
			'status' => 401,
			'message' => 'Data Gagal ' . $params->status,
			'lastUpdatedNew' => null
		];
	} else {
		$app->db->trans_commit();
		$response = [
			'status' => 200,
			'message' => 'Data Berhasil ' . $params->status,
			'lastUpdatedNew' => $params->lastUpdatedChecked['lastUpdatedNew']
		];
	}

	return $response;
}

function getLastTbgDepo()
{
	$app = &get_instance();

	if ($app->session->userdata('depo_id')) {
		return $app->db->select("CASE
														WHEN depo_last_tbg is null THEN null
														WHEN depo_last_tbg is not null then DATEADD(day, 1, depo_last_tbg)
														ELSE null
													END depo_last_tbg")->from("depo")->where("depo_id", $app->session->userdata('depo_id'))->get()->row()->depo_last_tbg;
	} else {
		return null;
	}
}


if (!function_exists('getVrblPusher')) {

	function getVrblPusher()
	{
		$app = &get_instance();

		$data = $app->db->select("vrbl_param as params, vrbl_kode as kode")->from("vrbl")->like('vrbl_param', 'pusher')->get()->result();

		$vrblPusher = [];

		if ($data) {
			foreach ($data as $key => $value) {
				$vrblPusher[$value->params] = $value->kode;
			}
		}

		return (object)$vrblPusher;
	}
}

if (!function_exists('envPusher')) {

	function envPusher()
	{

		$options = array(
			'cluster' => getVrblPusher()->PUSHER_cluster,
			'useTLS' => true
		);
		$pusher = new Pusher\Pusher(
			checkProductionApp()->PUSHER_KEY,
			checkProductionApp()->PUSHER_SECRET,
			checkProductionApp()->PUSHER_APP_ID,
			$options
		);

		return $pusher;
	}
}

if (!function_exists('senderPusher')) {

	function senderPusher($data = [])
	{
		return envPusher()->trigger('my-channel', 'my-event', $data);
	}
}

if (!function_exists('checkProductionApp')) {

	function checkProductionApp()
	{
		$app = &get_instance();

		$checkUrl = $app->db->query("SELECT aplikasi_url from getapplicationname() WHERE aplikasi_kode = '" . $app->session->userdata('Mode') . "'")->row();

		if ($checkUrl) {
			if (strpos($checkUrl->aplikasi_url, 'localhost') !== false) {
				return (object)[
					'PUSHER_KEY' => getVrblPusher()->PUSHER_KEY_DEV,
					'PUSHER_SECRET' => getVrblPusher()->PUSHER_SECRET_DEV,
					'PUSHER_APP_ID' => getVrblPusher()->PUSHER_APP_ID_DEV,
				];
			} else {
				return (object)[
					'PUSHER_KEY' => getVrblPusher()->PUSHER_KEY_PROD,
					'PUSHER_SECRET' => getVrblPusher()->PUSHER_SECRET_PROD,
					'PUSHER_APP_ID' => getVrblPusher()->PUSHER_APP_ID_PROD,
				];
			}
		} else {
			return (object)[
				'PUSHER_KEY' => getVrblPusher()->PUSHER_KEY_DEV,
				'PUSHER_SECRET' => getVrblPusher()->PUSHER_SECRET_DEV,
				'PUSHER_APP_ID' => getVrblPusher()->PUSHER_APP_ID_DEV,
			];
		}
	}
}

function cekDokumenTutupGudang($params)
{
	date_default_timezone_set('Asia/Jakarta');

	$app = &get_instance();

	$depo_id = $app->session->userdata('depo_id');
	$tgl_dokumen = $params->tgl_dokumen;

	$getStatusTutupGudang = $app->db->query("Exec cek_dokumen_tutup_gudang '$depo_id', '$tgl_dokumen'")->row(0);

	$response = [
		'status' => $getStatusTutupGudang->kode,
		'message' => $getStatusTutupGudang->ErrMsg
	];

	return $response;
}
