<?php

if ($libs = opendir(__DIR__.'/includes')) {
   while (false !== ($chk = readdir($libs))) {
        if ($chk != "." && $chk != "..") {
            include __DIR__.'/includes/'.$chk;
        }
    }
    closedir($libs);
}

if ($libs = opendir(__DIR__.'/components')) {
   while (false !== ($chk = readdir($libs))) {
        if ($chk != "." && $chk != "..") {
            include __DIR__.'/components/'.$chk;
        }
    }
    closedir($libs);
}

$xss = new XSS();
$router = new Router();
$logon = new LogonAuth();
require_once 'functions.php';
$url = $xss->XSString($_SERVER['QUERY_STRING']);
$logon -> session_process();
$router->URL($url);

