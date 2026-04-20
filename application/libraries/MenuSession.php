<?php
defined('BASEPATH') or exit('No direct script access allowed');
class MenuSession
{
	private $data = [];

	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->database('default');
		$this->CI->load->model('M_Menu');
		$this->CI->load->model('M_Depo');
		$this->CI->load->model('M_Function');
		$this->CI->load->model('M_Vrbl');
	}

	public function checkMenuAccess($MenuKode)
	{
		// return $MenuKode;
		$access = $this->CI->M_Menu->Getmenu_access_web($this->CI->session->userdata('pengguna_grup_id'), $MenuKode);
		if ($access['R'] != 1) {
			redirect(base_url('MainPage/Index'));
			return false;
			# code...
		}
		return true;
	}
	public function getDataMenu()
	{
		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();
		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();
		$data['sidemenu'] = $this->CI->M_Menu->GetMenu_Depo('', $this->CI->session->userdata('pengguna_grup_id'));
		$data['Ses_UserName'] = $this->CI->session->userdata('pengguna_username');

		$data['css_files'] = array(
			base_url('/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css'),
			base_url('/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css'),

			base_url('/assets/css/custom/horizontal-scroll.css'),
			base_url('/vendors/pnotify/dist/pnotify.css'),
			base_url('/vendors/pnotify/dist/pnotify.buttons.css'),
			base_url('/vendors/pnotify/dist/pnotify.nonblock.css'),
			base_url('/vendors/jquery-ui/jquery-ui.min.css'),

			base_url('/vendors/fullcalendar-5.11/lib/main.css'),

			base_url('/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css'),
		);

		$data['js_files'] 	= array(
			base_url('/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js'),
			base_url('/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js'),

			base_url('/vendors/pnotify/dist/pnotify.js'),
			base_url('/vendors/pnotify/dist/pnotify.buttons.js'),
			base_url('/vendors/pnotify/dist/pnotify.nonblock.js'),
			base_url('/vendors/jquery-ui/jquery-ui.min.js'),

			base_url('/vendors/fullcalendar-5.11/lib/main.js'),

			base_url('/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')
		);
		return $data;
	}
}