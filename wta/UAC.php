<?php


function getNeedUserLevel () {
    $url = $_SERVER['REQUEST_URI'];
    $arr = array (
        'index' => 111;
        'user' => 111;
        'tech' => 555;
        'admin' => 666;
        'wta' => 777;
        'root' => 777;
    );
    if (strpos($url, '/') === 0) {
        $url = substr($url, 1);
    }
    $dir = strstr($url, '/', true);
    if ($dir === false) {
        $dir = strstr($url, '.', true);
    }
    if ($dir === false) {
        $dir = 'index';
    }
    
    if (isset($arr[$dir])) {
        return $arr[$dir];
    } else {
        return 999;
    }
    return false;
}



