<?php
function isHttpHost($host)
{
    if (!isset($_SERVER['HTTP_HOST'])) {
    return false;
    }
    return $_SERVER['HTTP_HOST'] ===  $host;
}

if (isHttpHost("mcstaging.heartsoulscrubs.com")) {
    $_SERVER["MAGE_RUN_CODE"] = "hts";
    $_SERVER["MAGE_RUN_TYPE"] = "website";
}elseif (isHttpHost("mcstaging.infinityscrubs.com")){
    $_SERVER["MAGE_RUN_CODE"] = "base";
    $_SERVER["MAGE_RUN_TYPE"] = "website";
}
