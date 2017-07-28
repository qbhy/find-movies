<?php

if (!function_exists("clear")) {
    function clear($str)
    {
        $searchs = ['\n', " ", '\t', "80s手机电影网编辑整理", "80s手机mp4下载网编辑整理"];
        return str_replace($searchs, '', $str);
    }
}
