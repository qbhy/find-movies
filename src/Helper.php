<?php

if (!function_exists("clear")) {
    function clear($str)
    {
        $str = trim(str_replace("\n", "", $str));
        $str = str_replace(" ", "", $str);
        $str = str_replace("\t", "", $str);
        $str = str_replace("80s手机电影网编辑整理", "", $str);
        $str = str_replace("80s手机mp4下载网编辑整理", "", $str);
        return $str;
    }
}
