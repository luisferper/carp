<?php

/* Test Conection*/
if (isset($_POST['testconection'])){
        $hosting = $_POST['db_host'];
        $port = $_POST['db_port'];
        $dbname = $_POST['dbname'];
        $db_username = $_POST['db_username'];
        $db_password = $_POST['db_password'];
        try{
            $conn = new PDO('mysql:host='.$hosting.'; port='.$port.'; dbname='.$dbname,$db_username,$db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo 1;
            $conn = null;
            return;
        }
        catch(PDOException $e){
            echo 2;
            return;
        }
}

/*******************************************/

// Para saber la url que se usara y la ip local.
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
        $url = "https://";
}else{
    $url = "http://";
}
$url.= $_SERVER['HTTP_HOST'];
$url.= $_SERVER['REQUEST_URI'];
define('URLHOST', str_replace('install.php', '', $url));

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $local = "https://";
}else{
    $local = "http://";
}
$local .= getHostByName(getHostName());
$local .= str_replace('install.php', '', $_SERVER['REQUEST_URI']);

/*******************************************/

// Hacer la instalacion
if (isset($_POST['installFinish'])){

// Base de datos
$SQL = 'U0VUIFNRTF9NT0RFID0gIk5PX0FVVE9fVkFMVUVfT05fWkVSTyI7ClNUQVJUIFRSQU5TQUNUSU9OOwpTRVQgdGltZV96b25lID0gIiswMDowMCI7CkNSRUFURSBUQUJMRSBgY2F0ZWdvcmlhYCAoCiAgYGlkYCBpbnQoMTEpIE5PVCBOVUxMLAogIGBub21icmVgIHZhcmNoYXIoMTAwKSBOT1QgTlVMTCwKICBgY29tYW5kYWAgaW50KDExKSBOT1QgTlVMTAopIEVOR0lORT1Jbm5vREIgREVGQVVMVCBDSEFSU0VUPXV0ZjggQ09MTEFURT11dGY4X2dlbmVyYWxfY2k7CklOU0VSVCBJTlRPIGBjYXRlZ29yaWFgIChgaWRgLCBgbm9tYnJlYCwgYGNvbWFuZGFgKSBWQUxVRVMKKDEsICdEZWZhdWx0JywgMSk7CkNSRUFURSBUQUJMRSBgY29uZmlndXJhdGlvbmAgKAogIGBpZGAgaW50KDExKSBOT1QgTlVMTCwKICBgdGl0bGVzaXRlYCB2YXJjaGFyKDI1MCkgTk9UIE5VTEwsCiAgYGRlc2NzaXRlYCB2YXJjaGFyKDI1MCkgTk9UIE5VTEwsCiAgYGxvZ29gIHZhcmNoYXIoMjUwKSBOT1QgTlVMTCwKICBgZGlyZWNjaW9uYCB2YXJjaGFyKDI1MCkgTk9UIE5VTEwsCiAgYHRlbGVmb25vYCB2YXJjaGFyKDI1MCkgTk9UIE5VTEwsCiAgYGNhdGVjb2AgaW50KDExKSBOT1QgTlVMTCwKICBgbWVzYXNgIGludCgxMSkgTk9UIE5VTEwsCiAgYGVzdGlsb2AgaW50KDExKSBOT1QgTlVMTCwKICBgY29sb3JgIGludCgxMSkgTk9UIE5VTEwsCiAgYHBvcmNlbnRhamVgIGludCgxMSkgTk9UIE5VTEwsCiAgYGRpdmlzYWAgdmFyY2hhcigyMCkgTk9UIE5VTEwsCiAgYHNtdHBgIHZhcmNoYXIoMjUwKSBOT1QgTlVMTCwKICBgdXNlcnNtdHBgIHZhcmNoYXIoMjUwKSBOT1QgTlVMTCwKICBgcGFzc210cGAgdmFyY2hhcigyNTApIE5PVCBOVUxMLAogIGBzbXRwb3J0YCBpbnQoMTEpIE5PVCBOVUxMLAogIGBzbXRwYWN0aXZlYCBpbnQoMTEpIE5PVCBOVUxMLAogIGBkZWNpbWFsc2AgaW50KDExKSBOT1QgTlVMTCwKICBgbWlsZXNgIGludCgxMSkgTk9UIE5VTEwKKSBFTkdJTkU9SW5ub0RCIERFRkFVTFQgQ0hBUlNFVD11dGY4IENPTExBVEU9dXRmOF9nZW5lcmFsX2NpOwpJTlNFUlQgSU5UTyBgY29uZmlndXJhdGlvbmAgKGBpZGAsIGB0aXRsZXNpdGVgLCBgZGVzY3NpdGVgLCBgbG9nb2AsIGBkaXJlY2Npb25gLCBgdGVsZWZvbm9gLCBgY2F0ZWNvYCwgYG1lc2FzYCwgYGVzdGlsb2AsIGBjb2xvcmAsIGBwb3JjZW50YWplYCwgYGRpdmlzYWAsIGBzbXRwYCwgYHVzZXJzbXRwYCwgYHBhc3NtdHBgLCBgc210cG9ydGAsIGBzbXRwYWN0aXZlYCwgYGRlY2ltYWxzYCwgYG1pbGVzYCkgVkFMVUVTCigxLCAnQ2FmZXN5cyB2MycsICdTaXN0ZW1hIGRlIENhZmV0ZXJpYXMnLCAnZGVmYXVsdC5wbmcnLCAnQ2FsbGUgc2llbXByZSB2aXZhICMxMjM0JywgJzg0NDEyMzQ1NjknLCAxLCA4LCAxLCAxLCAwLCAnTVhOJywgJ3NtdHAuZG9hbWluLmNvbScsICdub3JlcGx5QGRvbWFpbi5jb20nLCAncGFzc2RlZmF1bHQnLCA0NjUsIDEsIDEsIDIpOwpDUkVBVEUgVEFCTEUgYGN1ZW50YXNgICgKICBgaWRgIGJpZ2ludCgyMCkgTk9UIE5VTEwsCiAgYG5vbWJyZWAgdmFyY2hhcig1MCkgTk9UIE5VTEwsCiAgYGluc3Vtb3NgIGxvbmd0ZXh0IERFRkFVTFQgTlVMTCwKICBgaWRtZXNhYCBiaWdpbnQoMjApIE5PVCBOVUxMLAogIGBmZWNoYWhvcmFgIGRhdGV0aW1lIE5PVCBOVUxMLAogIGBub3RhYCB0ZXh0IERFRkFVTFQgTlVMTCwKICBgaGFzaGAgdmFyY2hhcigyNTApIE5PVCBOVUxMCikgRU5HSU5FPUlubm9EQiBERUZBVUxUIENIQVJTRVQ9dXRmOCBDT0xMQVRFPXV0ZjhfZ2VuZXJhbF9jaTsKQ1JFQVRFIFRBQkxFIGBpbnN1bW9zYCAoCiAgYGlkYCBpbnQoMTEpIE5PVCBOVUxMLAogIGBub21icmVgIHZhcmNoYXIoMTAwKSBOT1QgTlVMTCwKICBgZGVzY3JpcGNpb25gIHZhcmNoYXIoMjUwKSBOT1QgTlVMTCwKICBgY2F0ZWdvcmlhYCBpbnQoMTEpIE5PVCBOVUxMLAogIGBjb2RpZ29gIGludCgxMSkgTk9UIE5VTEwsCiAgYHByZWNpb2AgZGVjaW1hbCgxNSwyKSBOT1QgTlVMTCwKICBgc3RhdHVzYCBpbnQoMTEpIE5PVCBOVUxMCikgRU5HSU5FPUlubm9EQiBERUZBVUxUIENIQVJTRVQ9dXRmOCBDT0xMQVRFPXV0ZjhfZ2VuZXJhbF9jaTsKSU5TRVJUIElOVE8gYGluc3Vtb3NgIChgaWRgLCBgbm9tYnJlYCwgYGRlc2NyaXBjaW9uYCwgYGNhdGVnb3JpYWAsIGBjb2RpZ29gLCBgcHJlY2lvYCwgYHN0YXR1c2ApIFZBTFVFUwooMSwgJ0FydGljdWxvIGRlIEVqZW1wbG8nLCAnRXN0byBlcyB1biBlamVtcGxvJywgMSwgMTIzLCAxMC4wMCwgMSk7CkNSRUFURSBUQUJMRSBgbWVzYXNgICgKICBgaWRgIGJpZ2ludCgxMSkgTk9UIE5VTEwsCiAgYG51bWVzYWAgaW50KDExKSBOT1QgTlVMTCwKICBgZmVjaGFob3JhYCBkYXRldGltZSBOT1QgTlVMTCwKICBgY3VlbnRhc2AgdmFyY2hhcig1MCkgREVGQVVMVCBOVUxMLAogIGBlbmNhcmdhZG9gIGludCgxMSkgTk9UIE5VTEwsCiAgYGhhc2hgIHZhcmNoYXIoMjUwKSBOT1QgTlVMTCwKICBgc3RhdHVzYCBpbnQoMTEpIE5PVCBOVUxMCikgRU5HSU5FPUlubm9EQiBERUZBVUxUIENIQVJTRVQ9dXRmOCBDT0xMQVRFPXV0ZjhfZ2VuZXJhbF9jaTsKQ1JFQVRFIFRBQkxFIGBzdGF0c2AgKAogIGBpZGAgaW50KDExKSBOT1QgTlVMTCwKICBgaW5zdW1vYCBpbnQoMTEpIE5PVCBOVUxMLAogIGBtZXNgIHZhcmNoYXIoMikgTk9UIE5VTEwsCiAgYGNhbnRpZGFkYCBpbnQoMTEpIE5PVCBOVUxMCikgRU5HSU5FPUlubm9EQiBERUZBVUxUIENIQVJTRVQ9dXRmOCBDT0xMQVRFPXV0ZjhfZ2VuZXJhbF9jaTsKQ1JFQVRFIFRBQkxFIGB1c3Vhcmlvc2AgKAogIGBpZGAgaW50KDExKSBOT1QgTlVMTCwKICBgbm9tYnJlYCB2YXJjaGFyKDEwMCkgTk9UIE5VTEwsCiAgYHVzdWFyaW9gIHZhcmNoYXIoNTApIE5PVCBOVUxMLAogIGB1c2VycGFzc2AgdmFyY2hhcigyNTApIE5PVCBOVUxMLAogIGB1c2VybWFpbGAgdmFyY2hhcigyNTApIE5PVCBOVUxMLAogIGBzdGF0dXNgIGludCgxMSkgTk9UIE5VTEwsCiAgYHVzZXJ0eXBlYCBpbnQoMTEpIE5PVCBOVUxMCikgRU5HSU5FPUlubm9EQiBERUZBVUxUIENIQVJTRVQ9dXRmOCBDT0xMQVRFPXV0ZjhfZ2VuZXJhbF9jaTsKQ1JFQVRFIFRBQkxFIGB2ZW50YXNgICgKICBgaWRgIGJpZ2ludCgxMSkgTk9UIE5VTEwsCiAgYGlkbWVzYWAgaW50KDExKSBOT1QgTlVMTCwKICBgbWVzYWAgaW50KDExKSBOT1QgTlVMTCwKICBgY3VlbnRhYCB2YXJjaGFyKDEwMCkgTk9UIE5VTEwsCiAgYHVzdWFyaW9gIGludCgxMSkgTk9UIE5VTEwsCiAgYGFydGljdWxvc2AgdGV4dCBOT1QgTlVMTCwKICBgYW5vYCB2YXJjaGFyKDQpIE5PVCBOVUxMLAogIGBtZXNgIHZhcmNoYXIoMikgTk9UIE5VTEwsCiAgYGRpYWAgdmFyY2hhcigyKSBOT1QgTlVMTCwKICBgaG9yYWAgdmFyY2hhcigxMCkgTk9UIE5VTEwsCiAgYHRvdGFsYCBkZWNpbWFsKDE1LDIpIE5PVCBOVUxMLAogIGBwYWdvY29uYCBkZWNpbWFsKDE1LDIpIE5PVCBOVUxMLAogIGB0aXBvcGFnb2AgaW50KDExKSBOT1QgTlVMTCwKICBgY2FtYmlvYCBkZWNpbWFsKDE1LDIpIE5PVCBOVUxMLAogIGBoYXNoYCB2YXJjaGFyKDI1MCkgTk9UIE5VTEwsCiAgYHByb3BpbmFgIGludCgxMSkgTk9UIE5VTEwKKSBFTkdJTkU9SW5ub0RCIERFRkFVTFQgQ0hBUlNFVD11dGY4IENPTExBVEU9dXRmOF9nZW5lcmFsX2NpOwpBTFRFUiBUQUJMRSBgY2F0ZWdvcmlhYCBBREQgUFJJTUFSWSBLRVkgKGBpZGApOwpBTFRFUiBUQUJMRSBgY29uZmlndXJhdGlvbmAgQUREIFBSSU1BUlkgS0VZIChgaWRgKTsKQUxURVIgVEFCTEUgYGN1ZW50YXNgIEFERCBQUklNQVJZIEtFWSAoYGlkYCk7CkFMVEVSIFRBQkxFIGBpbnN1bW9zYCBBREQgUFJJTUFSWSBLRVkgKGBpZGApOwpBTFRFUiBUQUJMRSBgbWVzYXNgIEFERCBQUklNQVJZIEtFWSAoYGlkYCk7CkFMVEVSIFRBQkxFIGBzdGF0c2AgQUREIFBSSU1BUlkgS0VZIChgaWRgKTsKQUxURVIgVEFCTEUgYHVzdWFyaW9zYCBBREQgUFJJTUFSWSBLRVkgKGBpZGApOwpBTFRFUiBUQUJMRSBgdmVudGFzYCBBREQgUFJJTUFSWSBLRVkgKGBpZGApOwpBTFRFUiBUQUJMRSBgY2F0ZWdvcmlhYCBNT0RJRlkgYGlkYCBpbnQoMTEpIE5PVCBOVUxMIEFVVE9fSU5DUkVNRU5ULCBBVVRPX0lOQ1JFTUVOVD0yOwpBTFRFUiBUQUJMRSBgY3VlbnRhc2AgTU9ESUZZIGBpZGAgYmlnaW50KDIwKSBOT1QgTlVMTCBBVVRPX0lOQ1JFTUVOVDsKQUxURVIgVEFCTEUgYGluc3Vtb3NgIE1PRElGWSBgaWRgIGludCgxMSkgTk9UIE5VTEwgQVVUT19JTkNSRU1FTlQsIEFVVE9fSU5DUkVNRU5UPTI7CkFMVEVSIFRBQkxFIGBtZXNhc2AgTU9ESUZZIGBpZGAgYmlnaW50KDExKSBOT1QgTlVMTCBBVVRPX0lOQ1JFTUVOVDsKQUxURVIgVEFCTEUgYHN0YXRzYCBNT0RJRlkgYGlkYCBpbnQoMTEpIE5PVCBOVUxMIEFVVE9fSU5DUkVNRU5UOwpBTFRFUiBUQUJMRSBgdXN1YXJpb3NgIE1PRElGWSBgaWRgIGludCgxMSkgTk9UIE5VTEwgQVVUT19JTkNSRU1FTlQsIEFVVE9fSU5DUkVNRU5UPTIwOwpBTFRFUiBUQUJMRSBgdmVudGFzYCBNT0RJRlkgYGlkYCBiaWdpbnQoMTEpIE5PVCBOVUxMIEFVVE9fSU5DUkVNRU5UOwpDT01NSVQ7';

// Conexion a base de datos
$hosting = $_POST['db_host'];
$port = $_POST['db_port'];
$dbname = $_POST['dbname'];
$db_username = $_POST['db_username'];
$db_password = $_POST['db_password'];
try{$pdo = new pdo("mysql:host=".$hosting."; port=".$port."; dbname=".$dbname."",$db_username,$db_password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));}catch(PDOException $ex){}

// Instala la base de datos
$pdo->prepare(base64_decode($SQL))->execute();

// Crear Password
$hashing = password_hash($_POST['passDataU'].$_POST['sal'], PASSWORD_DEFAULT);

// Instala el usuario
$pdo->prepare("INSERT INTO usuarios (id, nombre, usuario, userpass, usermail, status, usertype) VALUES (NULL, '".$_POST["nameDataU"]."', '".$_POST["userDataU"]."', '".$hashing."','".$_POST["userDataM"]."', 2, 2);")->execute();


if ($_POST['insType'] == 1){
   $lclinst = 'true';
}else{
   $lclinst = 'false';
}

// Escribir el archivo PHP
$f = '<';
$fs = '?';
$fsh = 'p';
$fileIndex = fopen("index.php", "w");
fwrite($fileIndex, $f.$fs.$fsh."h".$fsh.PHP_EOL);
fwrite($fileIndex, "error_reporting(0);".PHP_EOL);
fwrite($fileIndex, "date_default_timezone_set('".$_POST["zonahoraria"]."');".PHP_EOL);
fwrite($fileIndex, "\$hosting = '".$_POST['db_host']."';".PHP_EOL);
fwrite($fileIndex, "\$basededatos = '".$_POST['dbname']."';".PHP_EOL);
fwrite($fileIndex, "\$usuariobd = '".$_POST['db_username']."';".PHP_EOL);
fwrite($fileIndex, "\$passbd = '".$_POST['db_password']."';".PHP_EOL);
fwrite($fileIndex, "\$app_folder = 'app';".PHP_EOL);
fwrite($fileIndex, "\$assets_folder = 'assets';".PHP_EOL);
fwrite($fileIndex, "\$url_site = '".$_POST['urlsite']."';".PHP_EOL);
fwrite($fileIndex, "\$sal = '".$_POST['sal']."';".PHP_EOL);
fwrite($fileIndex, "\$port = '".$_POST['db_port']."';".PHP_EOL);
fwrite($fileIndex, "define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));".PHP_EOL);
fwrite($fileIndex, "define('BASEPATH', \$app_folder);".PHP_EOL);
fwrite($fileIndex, "define('ASSETSPATH', \$assets_folder);".PHP_EOL);
fwrite($fileIndex, "define('URLSITE', \$url_site);".PHP_EOL);
fwrite($fileIndex, "define('HOSTINGDB', \$hosting);".PHP_EOL);
fwrite($fileIndex, "define('DATABASENAME', \$basededatos);".PHP_EOL);
fwrite($fileIndex, "define('USERDATABASE', \$usuariobd);".PHP_EOL);
fwrite($fileIndex, "define('PASSDATABASE', \$passbd);".PHP_EOL);
fwrite($fileIndex, "define('PORTDATABASE', \$port);".PHP_EOL);
fwrite($fileIndex, "define('SALT', \$sal);".PHP_EOL);
fwrite($fileIndex, "define('LOCALINS', ".$lclinst.");".PHP_EOL);
fwrite($fileIndex, "require_once BASEPATH.'/autoload.php';".PHP_EOL);
fclose($fileIndex);
$pdo = null;
return;
}

echo'

<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Instalar Granero en llamas</title>
    <link href="assets/css/tabler.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
  </head>
  <body data-bs-theme="dark">
     <div class="container mt-5">
     	<div class="row justify-content-md-center">

     		<div class="col-md-5">
               <div class="card">
                 <div class="card-body">

                 	<div id="menu-install" class="text-center">
                 		<a> - Base de Datos</a>
                 		<a> - Configuraci&oacute;n</a>
                 		<a> - Registro</a>
                 		<a> - Instalaci&oacute;n</a>
                 	</div>
                 	<hr style="margin: 10px;">
                  <div class="col-12 text-center">
                    <img src="images/logo.png" width="150">
                  </div>
                 	<h1 class="text-center mb-1">Instalador Granero</h1>
                 	<hr style="margin: 10px;">
                    <div id="loader" class="py-4">
                      <div id="text-loader" class="text-secondary mb-3 text-center"></div>
                      <div class="progress progress-sm"><div class="progress-bar progress-bar-indeterminate"></div></div>
                    </div>

                    <div id="finishInstallationAlert" class="alert alert-success text-center" role="alert">
                      Se ha hecho la instalaci&oacute;n del sistema, recuerda borrar o cambiar de ruta el archivo "install.php",
                      en un momento seras redireccionado al sistema.
                    </div>

                 	<div id="first-step">
                 		<form id="testcnnfrm">
                             <input type="hidden" name="testconection" value="1">
                             <label class="mt-2">Hosting</label>
                 			 <input type="text" class="form-control" name="db_host" placeholder="localhost">
                             <label class="mt-2">Base de Datos</label>
                 			 <input type="text" class="form-control" name="dbname" placeholder="database">
                             <label class="mt-2">Usuario</label>
                 			 <input type="text" class="form-control" name="db_username" placeholder="root">
                             <label class="mt-2">Contrase&ntilde;a</label>
                 			 <input type="text" class="form-control" name="db_password" placeholder="toor">
                             <label class="mt-2">Puerto</label>
                             <input type="text" class="form-control" name="db_port" value="3306">
                 			 <button type="button" class="btn float-end btn-info mt-2" onclick="testConexion();">Probar Conexion</button>
                 		</form>
                 	</div>
                    <div id="second-step">
                        <form id="secondStepForm">
                            <label>Tipo de Instalacion</label>
                            <select class="form-control" name="insType" id="insType">
                                <option selected disabled></option>
                                <option value="1">Hosting</option>
                                <option value="2">Local</option>
                            </select>
                            <label class="mt-2">URL Sistema</label>
                            <input id="urlsite" type="text" name="urlsite" class="form-control" readonly>
                            <label class="mt-2">Zona Horaria</label>
                  <select class="form-control" name="zonahoraria">
                     <optgroup label="US (Common)">
                       <option value="America/Puerto_Rico">Puerto Rico (Atlantic)</option>
                       <option value="America/New_York">New York (Eastern)</option>
                       <option value="America/Chicago">Chicago (Central)</option>
                       <option value="America/Denver">Denver (Mountain)</option>
                       <option value="America/Phoenix">Phoenix (MST)</option>
                       <option value="America/Los_Angeles">Los Angeles (Pacific)</option>
                       <option value="America/Anchorage">Anchorage (Alaska)</option>
                       <option value="Pacific/Honolulu">Honolulu (Hawaii)</option>
                     </optgroup>

                     <optgroup label="America">
                       <option value="America/Adak">Adak</option>
                       <option value="America/Anchorage">Anchorage</option>
                       <option value="America/Anguilla">Anguilla</option>
                       <option value="America/Antigua">Antigua</option>
                       <option value="America/Araguaina">Araguaina</option>
                       <option value="America/Argentina/Buenos_Aires">Argentina - Buenos Aires</option>
                       <option value="America/Argentina/Catamarca">Argentina - Catamarca</option>
                       <option value="America/Argentina/ComodRivadavia">Argentina - ComodRivadavia</option>
                       <option value="America/Argentina/Cordoba">Argentina - Cordoba</option>
                       <option value="America/Argentina/Jujuy">Argentina - Jujuy</option>
                       <option value="America/Argentina/La_Rioja">Argentina - La Rioja</option>
                       <option value="America/Argentina/Mendoza">Argentina - Mendoza</option>
                       <option value="America/Argentina/Rio_Gallegos">Argentina - Rio Gallegos</option>
                       <option value="America/Argentina/Salta">Argentina - Salta</option>
                       <option value="America/Argentina/San_Juan">Argentina - San Juan</option>
                       <option value="America/Argentina/San_Luis">Argentina - San Luis</option>
                       <option value="America/Argentina/Tucuman">Argentina - Tucuman</option>
                       <option value="America/Argentina/Ushuaia">Argentina - Ushuaia</option>
                       <option value="America/Aruba">Aruba</option>
                       <option value="America/Asuncion">Asuncion</option>
                       <option value="America/Atikokan">Atikokan</option>
                       <option value="America/Atka">Atka</option>
                       <option value="America/Bahia">Bahia</option>
                       <option value="America/Barbados">Barbados</option>
                       <option value="America/Belem">Belem</option>
                       <option value="America/Belize">Belize</option>
                       <option value="America/Blanc-Sablon">Blanc-Sablon</option>
                       <option value="America/Boa_Vista">Boa Vista</option>
                       <option value="America/Bogota">Bogota</option>
                       <option value="America/Boise">Boise</option>
                       <option value="America/Buenos_Aires">Buenos Aires</option>
                       <option value="America/Cambridge_Bay">Cambridge Bay</option>
                       <option value="America/Campo_Grande">Campo Grande</option>
                       <option value="America/Cancun">Cancun</option>
                       <option value="America/Caracas">Caracas</option>
                       <option value="America/Catamarca">Catamarca</option>
                       <option value="America/Cayenne">Cayenne</option>
                       <option value="America/Cayman">Cayman</option>
                       <option value="America/Chicago">Chicago</option>
                       <option value="America/Chihuahua">Chihuahua</option>
                       <option value="America/Coral_Harbour">Coral Harbour</option>
                       <option value="America/Cordoba">Cordoba</option>
                       <option value="America/Costa_Rica">Costa Rica</option>
                       <option value="America/Cuiaba">Cuiaba</option>
                       <option value="America/Curacao">Curacao</option>
                       <option value="America/Danmarkshavn">Danmarkshavn</option>
                       <option value="America/Dawson">Dawson</option>
                       <option value="America/Dawson_Creek">Dawson Creek</option>
                       <option value="America/Denver">Denver</option>
                       <option value="America/Detroit">Detroit</option>
                       <option value="America/Dominica">Dominica</option>
                       <option value="America/Edmonton">Edmonton</option>
                       <option value="America/Eirunepe">Eirunepe</option>
                       <option value="America/El_Salvador">El Salvador</option>
                       <option value="America/Ensenada">Ensenada</option>
                       <option value="America/Fortaleza">Fortaleza</option>
                       <option value="America/Fort_Wayne">Fort Wayne</option>
                       <option value="America/Glace_Bay">Glace Bay</option>
                       <option value="America/Godthab">Godthab</option>
                       <option value="America/Goose_Bay">Goose Bay</option>
                       <option value="America/Grand_Turk">Grand Turk</option>
                       <option value="America/Grenada">Grenada</option>
                       <option value="America/Guadeloupe">Guadeloupe</option>
                       <option value="America/Guatemala">Guatemala</option>
                       <option value="America/Guayaquil">Guayaquil</option>
                       <option value="America/Guyana">Guyana</option>
                       <option value="America/Halifax">Halifax</option>
                       <option value="America/Havana">Havana</option>
                       <option value="America/Hermosillo">Hermosillo</option>
                       <option value="America/Indiana/Indianapolis">Indiana - Indianapolis</option>
                       <option value="America/Indiana/Knox">Indiana - Knox</option>
                       <option value="America/Indiana/Marengo">Indiana - Marengo</option>
                       <option value="America/Indiana/Petersburg">Indiana - Petersburg</option>
                       <option value="America/Indiana/Tell_City">Indiana - Tell City</option>
                       <option value="America/Indiana/Vevay">Indiana - Vevay</option>
                       <option value="America/Indiana/Vincennes">Indiana - Vincennes</option>
                       <option value="America/Indiana/Winamac">Indiana - Winamac</option>
                       <option value="America/Indianapolis">Indianapolis</option>
                       <option value="America/Inuvik">Inuvik</option>
                       <option value="America/Iqaluit">Iqaluit</option>
                       <option value="America/Jamaica">Jamaica</option>
                       <option value="America/Jujuy">Jujuy</option>
                       <option value="America/Juneau">Juneau</option>
                       <option value="America/Kentucky/Louisville">Kentucky - Louisville</option>
                       <option value="America/Kentucky/Monticello">Kentucky - Monticello</option>
                       <option value="America/Knox_IN">Knox IN</option>
                       <option value="America/La_Paz">La Paz</option>
                       <option value="America/Lima">Lima</option>
                       <option value="America/Los_Angeles">Los Angeles</option>
                       <option value="America/Louisville">Louisville</option>
                       <option value="America/Maceio">Maceio</option>
                       <option value="America/Managua">Managua</option>
                       <option value="America/Manaus">Manaus</option>
                       <option value="America/Marigot">Marigot</option>
                       <option value="America/Martinique">Martinique</option>
                       <option value="America/Matamoros">Matamoros</option>
                       <option value="America/Mazatlan">Mazatlan</option>
                       <option value="America/Mendoza">Mendoza</option>
                       <option value="America/Menominee">Menominee</option>
                       <option value="America/Merida">Merida</option>
                       <option value="America/Mexico_City">Mexico City</option>
                       <option value="America/Miquelon">Miquelon</option>
                       <option value="America/Moncton">Moncton</option>
                       <option value="America/Monterrey">Monterrey</option>
                       <option value="America/Montevideo">Montevideo</option>
                       <option value="America/Montreal">Montreal</option>
                       <option value="America/Montserrat">Montserrat</option>
                       <option value="America/Nassau">Nassau</option>
                       <option value="America/New_York">New York</option>
                       <option value="America/Nipigon">Nipigon</option>
                       <option value="America/Nome">Nome</option>
                       <option value="America/Noronha">Noronha</option>
                       <option value="America/North_Dakota/Center">North Dakota - Center</option>
                       <option value="America/North_Dakota/New_Salem">North Dakota - New Salem</option>
                       <option value="America/Ojinaga">Ojinaga</option>
                       <option value="America/Panama">Panama</option>
                       <option value="America/Pangnirtung">Pangnirtung</option>
                       <option value="America/Paramaribo">Paramaribo</option>
                       <option value="America/Phoenix">Phoenix</option>
                       <option value="America/Port-au-Prince">Port-au-Prince</option>
                       <option value="America/Porto_Acre">Porto Acre</option>
                       <option value="America/Port_of_Spain">Port of Spain</option>
                       <option value="America/Porto_Velho">Porto Velho</option>
                       <option value="America/Puerto_Rico">Puerto Rico</option>
                       <option value="America/Rainy_River">Rainy River</option>
                       <option value="America/Rankin_Inlet">Rankin Inlet</option>
                       <option value="America/Recife">Recife</option>
                       <option value="America/Regina">Regina</option>
                       <option value="America/Resolute">Resolute</option>
                       <option value="America/Rio_Branco">Rio Branco</option>
                       <option value="America/Rosario">Rosario</option>
                       <option value="America/Santa_Isabel">Santa Isabel</option>
                       <option value="America/Santarem">Santarem</option>
                       <option value="America/Santiago">Santiago</option>
                       <option value="America/Santo_Domingo">Santo Domingo</option>
                       <option value="America/Sao_Paulo">Sao Paulo</option>
                       <option value="America/Scoresbysund">Scoresbysund</option>
                       <option value="America/Shiprock">Shiprock</option>
                       <option value="America/St_Barthelemy">St Barthelemy</option>
                       <option value="America/St_Johns">St Johns</option>
                       <option value="America/St_Kitts">St Kitts</option>
                       <option value="America/St_Lucia">St Lucia</option>
                       <option value="America/St_Thomas">St Thomas</option>
                       <option value="America/St_Vincent">St Vincent</option>
                       <option value="America/Swift_Current">Swift Current</option>
                       <option value="America/Tegucigalpa">Tegucigalpa</option>
                       <option value="America/Thule">Thule</option>
                       <option value="America/Thunder_Bay">Thunder Bay</option>
                       <option value="America/Tijuana">Tijuana</option>
                       <option value="America/Toronto">Toronto</option>
                       <option value="America/Tortola">Tortola</option>
                       <option value="America/Vancouver">Vancouver</option>
                       <option value="America/Virgin">Virgin</option>
                       <option value="America/Whitehorse">Whitehorse</option>
                       <option value="America/Winnipeg">Winnipeg</option>
                       <option value="America/Yakutat">Yakutat</option>
                       <option value="America/Yellowknife">Yellowknife</option>
                     </optgroup>
                   </select>
                   <label class="mt-2">String de Seguridad (SAL) <small><a href="#" onclick="generateNewString();">Generar Nuevo String</a></small></label>
                   <input type="text" class="form-control" name="sal" id="salvalue">
                   <button type="button" class="btn float-end btn-info mt-2" onclick="nextStep();">Siguiente</button>
                        </form>
                    </div>
                    <div id="third-step">
                        <form id="finalInstallForm">
                           <label class="mt-2">Nombre:</label>
                           <input type="text" name="nameDataU" class="form-control"><p></p>
                           <label class="mt-2">Usuario:</label>
                           <input type="text" name="userDataU" class="form-control"><p></p>
                           <label class="mt-2">Contrase&ntilde;a:</label>
                           <input type="text" name="passDataU" class="form-control"><p></p>
                           <label class="mt-2">Correo Electronico:</label>
                           <input type="text" name="userDataM" class="form-control"><p></p>
                           <button type="button" class="btn btn-warning mt-2 float-end" onclick="finshInstall();">Terminar Instalaci&oacute;n</button>
                        </form>
                    </div>
                 </div>
               </div>
     		</div>

     	</div>
     </div>
  </body>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/install.js"></script>
  <script type="text/javascript">
      var urlhost = "'.URLHOST.'";
      var localhost = "'.$local.'";
  </script>
</html>
';

?>