<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MenuController extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    protected $MenuKode;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('M_Menu');
        $this->load->model('M_Vrbl');

        if ($this->session->has_userdata('pengguna_id') == 0) {
            $res = $this->M_Vrbl->Get_Vrbl_By_Param('BACKEND_URL');

            $Url = $res[0]['vrbl_ket_patch'];

            redirect($Url . 'MainPage');
        }

        $pengguna_grup_id     = $this->session->userdata('pengguna_grup_id');
        $menu_application    = $this->session->userdata('Mode');

        //$link    = $this->input->post('link');
        $link     = $this->uri->uri_string();
        $link    = explode('/', $link);

        $lastindex = count($link) - 1;
        $lastvalue = $link[$lastindex];

        $menu_link_main = '';

        $currentdirectory     = $this->router->fetch_directory();
        $currentcontroller    = $this->router->fetch_class();
        $currentfunction     = $this->router->fetch_method();

        $menu_link_main = $currentdirectory . $currentcontroller;

        $res = $this->M_Menu->Getmenu_by_menu_link_auto($menu_link_main);
        if ($res != 0) {
            $this->MenuKode = $res[0]['menu_kode'];

            $this->session->set_userdata('last_menu_kode', $this->MenuKode);
        } else {
            $this->MenuKode = $this->session->userdata('last_menu_kode');
        }
    }
    function containsExcludedSubstring($str, $excludedSubstrings)
    {
        foreach ($excludedSubstrings as $substring) {
            if (strpos($str, $substring) !== false) {
                return true;
            }
        }
        return false;
    }
}
