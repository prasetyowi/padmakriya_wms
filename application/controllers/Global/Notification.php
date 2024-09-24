<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification extends CI_Controller
{
	private $MenuKode;

	public function __construct()
	{
		parent::__construct();
	}

	public function updateNotificationRead()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		//update data
		$this->db->set("notification_is_read", 1);
		$this->db->set("notification_tgl_read", "GETDATE()", FALSE);
		$this->db->where("notification_id", $dataPost->notifId);

		$query = $this->db->update("notification");

		echo json_encode($query);
	}

	public function getNotification()
	{
		header('Content-Type: application/json');

		$countNotifIsNotRead = $this->db->query("SELECT * FROM notification WHERE notification_is_read = 0 AND notification_to_who_id = '" . $this->session->userdata('karyawan_id') . "'")->num_rows();

		$dataNotif = $this->db->query("SELECT who.karyawan_nama as from_who, who.karyawan_foto, too.karyawan_nama, nf.*
															 FROM notification nf 
															 LEFT JOIN karyawan who ON nf.notification_from_who_id = who.karyawan_id
															 LEFT JOIN karyawan too ON nf.notification_to_who_id = too.karyawan_id
															 WHERE nf.notification_to_who_id = '" . $this->session->userdata('karyawan_id') . "'
                                    AND nf.notification_tgl >= dateadd(day, -60, getdate())
                               ORDER BY nf.notification_tgl DESC
																")->result();

		$menuDepo = $this->db->query("SELECT distinct g.depo_id, g.depo_nama, count(n.depo_id) as total
                                  from depo as g
                                  inner join pengguna_depo as pg on pg.depo_id = g.depo_id
                                  left join notification n on n.depo_id = g.depo_id AND n.notification_is_read = 0 AND n.notification_to_who_id = '" . $this->session->userdata('karyawan_id') . "'
                                  where pg.pengguna_id = '" . $this->session->userdata('pengguna_id') . "' 
                                  group by g.depo_id, g.depo_nama")->result();

		$pulse = $countNotifIsNotRead > 0 ? "pulse" : "";

		$semua = "'semua'";
		$belum_dibaca = "'belum_dibaca'";


		$html = '<a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1">
                  <i class="fa-solid fa-bell" style="color: black"></i>
                  <span class="badge bg-red ' . $pulse . ' setCountNotif">' . $countNotifIsNotRead . '</span>
                </a>
                <ul class="dropdown-menu list-unstyled msg_list setWidth" role="menu" aria-labelledby="navbarDropdown1">
                  <div class="parent-notification">
                    <div class="notification">
                    <h3 name="CAPTION-NOTIFIKASI">Notifikasi</h3>
                    <div role="presentation" class="dropdown notificationssss">
                      <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown2">
                        <i class="fa-solid fa-ellipsis"></i>
                      </a>
                      <ul class="dropdown-menu list-unstyled msg_list" role="menu" aria-labelledby="navbarDropdown2">
                        <div class="menu-lainnya">
                          <div class="tandai-semua" onclick="handlerReadAllNotification()">
                            <i class="fas fa-check"></i>
                            <p><span name="CAPTION-BACASEMUA">Tandai semua sebagai telah dibaca</span></p>
                          </div>
                        </div>

                      </ul>
                    </div>
                  </div>
                  <div class="sub-notification-tab">
											<div class="tabs-depo">';
		foreach ($menuDepo as $key => $menu) {
			if ($this->session->userdata('depo_id') == $menu->depo_id) {
				$html .= '<input type="radio" name="tabs" id="tab' . $key . '" value="' . $menu->depo_id . '" onchange="handlerGetDataByDepo(this.value)" checked><label for="tab' . $key . '">';
			} else {
				$html .= '<input type="radio" name="tabs" id="tab' . $key . '" value="' . $menu->depo_id . '" onchange="handlerGetDataByDepo(this.value)"><label for="tab' . $key . '">';
			}
			if ($menu->total != 0) {
				$html .= '<div class="count-tabs"><span>' . $menu->total . '</span></div>';
			}
			$html .= '<span>' . $menu->depo_nama . '</span>
                        </label>';
		}
		$html .= '    </div>
              </div>
              <div class="sub-notification-read">
                  <input type="radio" id="fat" name="fatfit" class="notification-input" value="semua" onchange="handlerGetDataByRead(' . $semua . ', event)">
                  <input type="radio" id="fit" name="fatfit" class="notification-input" value="belum_dibaca" onchange="handlerGetDataByRead(' . $belum_dibaca . ', event)">
                  <div>
                    <label for="fat" name="CAPTION-SEMUA">Semua</label>
                    <label for="fit" name="CAPTION-BELUMDIBACA">Belum Dibaca</label>
                  </div>
                  <div class="show-all">
                  <h6 name="CAPTION-LIHATSEMUA">Lihat Semua</h6>
                  </div>
                </div>
                <div id="initDataNotification">';

		if (!empty($dataNotif)) {
			foreach ($dataNotif as $data) {

				$notifIsread = $data->notification_is_read == 0 ? "#0ea5e9" : "#475569";



				$html .= '<div class="parent-list-notification-item">
              <div class="list-notification-item" onclick="handlerTestNotif(\'' . $data->notification_id . '\', \'' . $data->notification_data_id . '\', \'' . $data->notification_judul . '\', \'' . $data->notification_keterangan . '\', \'' . $data->karyawan_nama . '\', \'' . $data->notification_to_modul . '\')">
                <img src="' . $this->session->userdata('backend_url') . 'assets/images/karyawan/' . $data->karyawan_foto . '">
                <div class="text">
                  <h4>' . $data->from_who . '</h4>
                  <p>' . $data->notification_keterangan . ' ' . $data->karyawan_nama . '</p>
                  <p style="display: block;color: ' . $notifIsread . '">' . time_elapsed_string($data->notification_tgl) . '</p>
                </div>';
				if ($data->notification_is_read == 0) {
					$html .= '<div class="not-read" style="margin:auto">
                              <i class="fa-solid fa-circle"></i>
                            </div>';
				}

				$html .= '</div>
            </div>';
			}
		} else {
			$html .= '<div class="notification-not-found">
                  <i class="fa-solid fa-bell-slash"></i>
                  <h4 class="text-danger" name="CAPTION-TIDAKADANOTIFIKASI">Anda tidak memiliki notifikasi</h4>
              </div>';
		}

		$html .= '    </div></div>
                </ul>';

		echo $html;
	}

	public function getNotificationByType()
	{
		header('Content-Type: application/json');

		$dataPost = json_decode(file_get_contents("php://input"));

		$type = $dataPost->type == null ? "" : ($dataPost->type == "semua" ? "" : "AND nf.notification_is_read = 0");

		$depo_id =  $dataPost->depo_id == null ? "" : "AND nf.depo_id = '$dataPost->depo_id'";


		$dataNotif = $this->db->query("SELECT who.karyawan_nama as from_who, who.karyawan_foto, too.karyawan_nama, nf.*
															 FROM notification nf 
															 LEFT JOIN karyawan who ON nf.notification_from_who_id = who.karyawan_id
															 LEFT JOIN karyawan too ON nf.notification_to_who_id = too.karyawan_id
															 WHERE nf.notification_to_who_id = '" . $this->session->userdata('karyawan_id') . "'
                                    AND nf.notification_tgl >= dateadd(day, -60, getdate())
                                    $depo_id
                                    $type
                               ORDER BY nf.notification_tgl DESC
																")->result();

		echo json_encode($dataNotif);
	}

	public function updateAllNotification()
	{

		// update notification berdasarkan karyawan id dan read 0
		$this->db->set("notification_is_read", 1);
		$this->db->set("notification_tgl_read", "GETDATE()", FALSE);
		$this->db->where("notification_to_who_id", $this->session->userdata('karyawan_id'));
		$this->db->where("notification_is_read", 0);

		$this->db->update("notification");

		senderPusher([
			'message' => 'update all notification'
		]);
	}
}
