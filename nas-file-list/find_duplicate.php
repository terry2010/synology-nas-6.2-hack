<?php
$dir = "/volume1/book";
$dir = "/volume1/homes/terry";

ini_set("memory_limit", "4G");
$hash_list = array();
$duplicate_list = array();
$full_list = array();
function file_list($path)
{
    global $hash_list, $duplicate_list, $full_list;
    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($path . "/" . $file)) {
//                    echo $path . ": " . $file . "<br>";
                    file_list($path . "/" . $file);
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
    }
}

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
