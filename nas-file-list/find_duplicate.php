<?php
$dir = "/volume1/book";

ini_set("memory_limit","2G");
$hash_list = array();
$duplicate_list = array();
$full_list = array();
function file_list($path)
{
    global $hash_list ,$duplicate_list,$full_list;
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
                    if(isset($hash_list[$hash])) {
                        if(!isset($duplicate_list[$hash])) {
                            $duplicate_list[$hash][] = $hash_list[$hash];
                        }
                        $duplicate_list[$hash][] = $fname;
                    }else {
                        $hash_list[$hash] = $fname;
                    }
                    $full_list[] = array('hash'=>$hash,'fname'=>$fname);
                }
            }
        }
    }
}

file_list($dir);
file_put_contents("duplicate_list.json",json_encode($duplicate_list,JSON_UNESCAPED_UNICODE));
file_put_contents("full_list.json",json_encode($full_list,JSON_UNESCAPED_UNICODE));
