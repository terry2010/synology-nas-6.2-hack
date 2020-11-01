<?php

function _parse_cli_argv() {
    if (count($GLOBALS['argv']) > 2) {
        $arr['query'] = $GLOBALS['argv'];
        unset($arr['query'][0]);
    } else {
        $arr['query'] = @$GLOBALS['argv'][1];
    }
    if (is_array($arr['query'])) {

        foreach ($arr['query'] as $k => $v) {
            if (strpos($v, '=')) {
                $tmp_arr = explode('=', $v);
                $arg[$tmp_arr[0]] = $tmp_arr[1];
            } else {
                $arg[$v] = '';
            }
        }
    } elseif ($arr['query']) {
        if (strpos($arr['query'], '&')) {
            $query = explode('&', html_entity_decode($arr['query']));
        } else {
            $query[0] = $arr['query'];
        }
        foreach ($query as $k => $v) {
            if (strpos($v, '=')) {
                $tmp_arr = explode('=', $v);
                $arg[$tmp_arr[0]] = $tmp_arr[1];
            } else {
                $arg[$v] = '';
            }
        }
    }
    $_GET = @$arg;
}

function getDir($path) {
    //判断目录是否为空
    if (!file_exists($path)) {
        return [];
    }

    $files = scandir($path);
    $fileItem = [];
    foreach ($files as $v) {
        $newPath = $path . DIRECTORY_SEPARATOR . $v;
        if (is_dir($newPath) && $v != '.' && $v != '..') {
            $fileItem = array_merge($fileItem, getDir($newPath));
        } else if (is_file($newPath)) {
            $fileItem[] = $newPath;
        }
    }

    return $fileItem;
}

_parse_cli_argv();

$path = $_GET['path'];
if (strlen($path) < 3) {
    echo "使用方式:
          php index.php path=\"/volumeUSB1/usbshare/你的目录\"
          php index.php order=namelengthdesc path=\"/volumeUSB1/usbshare/你的目录\"
           usb路径一般为 
                       /volumeUSB1/usbshare/你的目录
                       /volumeUSB2/usbshare/目录名
            volumeUSB后面的数字是第几个usb设备，这个需要自己尝试一下才知道
";
    die();
}



$file_list = getDir($path);

$order = @$_GET['order'];
if (strlen($order) > 1) {
    if ($order == 'namelengthdesc') {
        $new_file_list = array();
        foreach ($file_list as $k => $v) {
            $new_file_list[strlen($v)][] = $v;
        }
        ksort($new_file_list,SORT_NUMERIC);
         print_r($new_file_list);
    }
} else {
    var_dump($file_list);
}



