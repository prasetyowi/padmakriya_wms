<?php

function checkerList()
{
    $data = array(
        'chk1' => 'Checker A',
        'chk2' => 'Checker B',
        'chk3' => 'Checker C',
        'chk4' => 'Checker D',
    );
    return $data;
}

function getCheckerName($checkerId)
{
    $getListChecker = checkerList();
    $checkerName = '';
    foreach ($getListChecker as $key => $item) {
        if ($key == $checkerId) {
            $checkerName = $item;
        }
    }
    return $checkerName;
}

function getDateYMD($tanggal)
{
    if (strlen($tanggal) == 0 || $tanggal == null) {
        return '';
    } else {
        return Date('Y-m-d', strtotime($tanggal));
    }
}

function formatYMD($tanggal)
{
    if (strlen($tanggal) == 0 || $tanggal == null) {
        return '';
    } else {
        return Date('Y-m-d', strtotime(str_replace("/", "-", $tanggal)));
    }
}
