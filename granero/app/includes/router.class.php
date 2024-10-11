<?php

Class Router
{


    public function URL($url)
    {
        $getUrl = $this-> removeQueryStringVariables($url);
        $this->Views($getUrl);
    }

    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }


    public function Views($param){

        // Contar diagonales "/"
        $exp = explode('/', $param);

        // Contar las Diagonales
        $count = count($exp);

        // Paginas normales
        if ($count == 1){

              // Para las funciones AJAX
              if ($param == 'ajax') {
                require_once BASEPATH.'/ajax.php';
                return;
              }

              if ($param == 'getAllInsumos') {
                getCateJSON();
                return;
              }

              if ($param == 'getInsumos') {
                getInsuJSON();
                return;
              }          

              if ($param == 'getCates') {
                getCatesJSON();
                return;
              } 

              if ($param == 'getUsers') {
                getUsersJSON();
                return;
              }  

              if ($param == 'getVentasDay') {
                getVentasDayJSON();
                return;
              }             
                
              // Cerrar Sesion
              if ($param == 'salir'){
                  $_SESSION = array();
                  $params = session_get_cookie_params();
                  setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
                  session_destroy();
                  header('Location: '.URLSITE);
                  return;
              }


              // Hacemos la comprobacion de las paginas que estan restringidas
              if (is_null($param) || empty($param) || ctype_space($param)){

                  if (isLogin() == TRUE){
                    header('Location: '.URLSITE.'inicio');
                  }
                  getTheHeader('');
                  loginForm();
                  getTheFooter();

              }else{

                  if (isLogin() == FALSE){
                    header('Location: '.URLSITE);
                  }

                  getTheHeader($param);
                  getTheMenu();
                  if ($param == 'inicio'){
                     SingleOrdersIndex();
                  }

                  if ($param == 'mesas'){
                     DisplayMesas();
                  }

                  if ($param == 'ordenes') {
                     OrdersPagev3();
                  }

                  if ($param == 'insumos') {
                     insumosModulo();
                  }

                  if ($param == 'categorias') {
                     categoiasModulo();
                  }

                  if ($param == 'usuarios') {
                     UsersModulo();
                  }

                  if ($param == 'informes') {
                     InformesModule();
                  }

                  if ($param == 'configuracion') {
                     ConfigModulo();
                  }

                  getTheFooter();

              }

        // Paginas con mas diagonales
        }else{
             $spwy = explode('/', $param);
             $ctl = new Controller;
             $ctl -> ControlString($spwy[0], $spwy[1]);
        }
    }
}

