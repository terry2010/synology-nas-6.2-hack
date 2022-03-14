<?php
//删除$del_dir中的重复文件
$safe_dir = "/volume1/book/kindle人电子书合集89G解压密码为sz520";
$del_dir = "/volume1/book/kindlerenALLCollection";
$dir = "/volume1/book/kindle人电子书合集89G解压密码为sz520";

//$safe_dir = "/var/services/homes/terry/test/gossh";
//$del_dir = "/var/services/homes/terry/test/gossh-del";
ini_set("memory_limit", "4G");
$hash_list = array();
$duplicate_list = array();
$full_list = array();
function file_list($path)
{
    $hash_list = $duplicate_list = $full_list = array();
    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($path . "/" . $file)) {
//                    echo $path . ": " . $file . "<br>";
                    $tmp = file_list($path . "/" . $file);
                    $hash_list = array_merge($hash_list,$tmp['hash_list']);
                    $duplicate_list = array_merge($duplicate_list,$tmp['duplicate_list']);
                    $full_list = array_merge($full_list,$tmp['full_list']);
                } else {
                    echo $fname = $path . "/" . $file;
                    echo "[:::::]";
                    echo $hash = md5_file($fname);
                    echo "\n\r";
                    $crc32 = dechex(hash_file("crc32", $fname));
                    $size = filesize($fname);
                    $_data = array('hash' => $hash, 'name' => $fname, 'size' => $size, 'crc32' => $crc32);
                    if (isset($hash_list[$hash])) {
                        if (!isset($duplicate_list[$hash])) {
                            $duplicate_list[$hash][] = $hash_list[$hash];
                        }
                        $duplicate_list[$hash][] = $_data;
                    } else {
                        $hash_list[$hash] = $_data;
                    }

                    $full_list[] = $_data;
                }
            }
        }
        return array('hash_list' => $hash_list, 'duplicate_list' => $duplicate_list, 'full_list' => $full_list);
    } else {
        return false;
    }
}

$safe_hash_list = array();
$safe_duplicate_list = array();
$safe_full_list = array();
$del_hash_list = array();
$del_duplicate_list = array();
$del_full_list = array();

$safe = file_list($safe_dir);
$del = file_list($del_dir);

foreach ($safe['hash_list'] as $hash => $v) {
    foreach ($del['full_list'] as $k1 => $v1) {
        if ($v1['hash'] == $hash) {
            var_dump($v1['name']);
            unlink($v1['name']);
        }
    }
}


die('ok');


file_list($dir);
file_put_contents("duplicate_list.json", json_encode($duplicate_list, JSON_UNESCAPED_UNICODE));
file_put_contents("full_list.json", json_encode($full_list, JSON_UNESCAPED_UNICODE));


$sql = "";
foreach ($duplicate_list as $k => $v) {
    foreach ($v as $k1 => $v1) {
        $path = addslashes(dirname($v1['name']));
        $name = addslashes($v1['name']);
        $hash = $v1['size'];
        $crc32 = $v1['crc32'];
        $size = $v1['size'];
        $sql .= "INSERT INTO `list` ( `hash`,`crc32`, `name`, `path`,`size`) VALUES ('{$k}', '$crc32','$name', '$path','$size');\r\n";

    }
}
file_put_contents("duplicate_list.sql", $sql);


foreach ($full_list as $k => $v) {

}

function get_zip_file($zip)
{
    $shell = "unzip -v $zip";
    exec($shell, $output, $ret);
//    var_dump($ret,$output);
    if (0 === $ret) {
        foreach ($output as $k => $v) {
            if ($k >= 3) {
                if (strpos("aa" . $v, "------") == 2) {
                    break;
                }
                $arr = explode(" ", $v);
                //array(16) {
                //  [0]=>
                //  string(0) ""
                //  [1]=>
                //  string(0) ""
                //  [2]=>
                //  string(6) "103483"
                //  [3]=>
                //  string(0) ""
                //  [4]=>
                //  string(6) "Defl:N"
                //  [5]=>
                //  string(0) ""
                //  [6]=>
                //  string(0) ""
                //  [7]=>
                //  string(0) ""
                //  [8]=>
                //  string(5) "74340"
                //  [9]=>
                //  string(0) ""
                //  [10]=>
                //  string(3) "28%"
                //  [11]=>
                //  string(10) "10-01-2020"
                //  [12]=>
                //  string(5) "20:49"
                //  [13]=>
                //  string(8) "e1998a89"
                //  [14]=>
                //  string(0) ""
                //  [15]=>
                //  string(25) "?????_14612839/leg001.pdg"
                //}
                if (count($arr) == 16 && strlen($arr[13] > 0) && strlen($arr[15]) > 0) {
                    //2: 文件长度
                    //8：压缩后的长度
                    //13： crc32
                    //15: 文件名
                }

            }
        }
    }
}

function fix_string($str)
{
    //检查格式
    $res = utf8_gb2312($str);
    if ($res == 'gb2312') {
        $str_utf8 = iconv('gb2312', "utf-8//IGNORE", $str);
    } else {
        $str_utf8 = $str;
    }
    return $str_utf8;
}

function utf8_gb2312($str, $default = 'gb2312')
{
    $str = preg_replace("/[\x01-\x7F]+/", "", $str);
    if (empty($str)) {
        return $default;
    }
    $preg = array(
        "gb2312" => "/^([\xA1-\xF7][\xA0-\xFE])+$/", //正则判断是否是gb2312
        "utf-8" => "/^[\x{4E00}-\x{9FA5}]+$/u",   //正则判断是否是汉字(utf8编码的条件了)，这个范围实际上已经包含了繁体中文字了

    );

    if ($default == 'gb2312') {
        $option = 'utf-8';
    } else {
        $option = 'gb2312';
    }

    if (!preg_match($preg[$default], $str)) {
        return $option;
    }

    $str = @iconv($default, $option, $str);

    //不能转成 $option, 说明原来的不是 $default
    if (empty($str)) {
        return $option;
    }

    return $default;

}

get_zip_file("/volume1/book/耶鲁写作课_14612839.zip");
