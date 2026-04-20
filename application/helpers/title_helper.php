<?php

function Get_Title_Name()
{
    return 'Warehouse Management System';
}

function Get_Title_Menu_Name($menu_kode)
{
    $side_name = "Warehouse Management System";
    $title = "";
    $CI = &get_instance();

    $result = $CI->db->query("select * from menu_web where menu_kode = '$menu_kode'");

    if ($result->num_rows() > 0) {
        $title = $side_name . " | " . $result->row(0)->menu_name;
    } else {
        $title = "Warehouse Management System";
    }

    return $title;
}

function Get_Copyright_Name()
{
    $CI = get_instance();

    $CI->load->model('M_Vrbl');

    $query = $CI->M_Vrbl->Get_Vrbl_Copyright_Name();

    return $query[0]['vrbl_ket_patch'];
}
