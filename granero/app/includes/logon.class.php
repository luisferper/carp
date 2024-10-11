<?php

Class LogonAuth {

       private static $instancia;
       private $dbh;

       public function __construct(){
	       $this->dbh = new DataBase();
       }

       public function session_process(){

             $session_name = 'JHSystems';   // Asignar un nombre a la sesion 
             $secure = LOCALINS;

             // Evitar acceder a la session por mediom de JavaScript
             $httponly = true;

             // Forzar que la sesion solo usa Cookies.
             if (ini_set('session.use_only_cookies', 1) === FALSE) {
                 echo 'Could not initiate a safe session (ini_set)';
                 exit();
             }

             // Gets current cookies params.
             $cookieParams = session_get_cookie_params();
             session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);

             // Sets the session name to the one set above.
             session_name($session_name);

             session_start();            // Start the PHP session 
             session_regenerate_id();    // regenerated the session, delete the old one. 

       }

       // Inicio de session
       public function LoginUser($user, $password){

           $db = new DataBase;
           $where = array('usuario' => $user);
           $selectSQL = $db->select('usuarios', '*', $where);
           
           foreach ($selectSQL as $key){
             
               if (password_verify($password.SALT, $key['userpass'])){
                    
                    if (!$key['status'] == 2){
                      return FALSE;
                    }

                    $UserBrowser = $_SERVER['HTTP_USER_AGENT'];
                    $_SESSION['idSession'] = $key['id'];
                    $_SESSION['usrNameSession'] = $key['usuario'];
                    $_SESSION['rankSession'] = $key['usertype'];
                    $_SESSION['LoginString'] = sha1($key['userpass'].$UserBrowser);
                    return TRUE;

               } else {
                    return FALSE;
               }

           }

       }

       public function login_check(){
           if (isset($_SESSION['idSession'], $_SESSION['usrNameSession'], $_SESSION['LoginString'])){
              
               $user_id = $_SESSION['idSession'];
               $login_string = $_SESSION['LoginString'];
               $username = $_SESSION['usrNameSession'];
               $user_browser = $_SERVER['HTTP_USER_AGENT'];

               $db = new DataBase;
               $where = array('id' => $_SESSION['idSession']);
               $selectSQL = $db->select('usuarios', '*', $where);
               foreach ($selectSQL as $key){
                   $passwdata = $key['userpass'];
               }

               $login_check = sha1($passwdata.$user_browser);
               
               if ($login_check == $login_string){
                   return TRUE;
               } else {
                   return FALSE;
               }

           } else {
               return FALSE;
           }
       }

       public function __clone(){
	       trigger_error('La clonación de este objeto no está permitida', E_USER_ERROR);
       }

}