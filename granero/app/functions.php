<?php

function randomString($length=15){
    $char = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $clen = strlen($char);
    $rand = "";
    for ($i=0; $i<$length; $i++) { $rand .= $char[rand(0, $clen-1)]; }
    return $rand;
}

function getTitlePage($entry){

          if (empty($entry)){
            $entry = NULL;
          }

          // Traemos el titulo desde la base de datos.
          $title = normalTitleSite();
          
          // Ahora cambiamos todo lo que tenga un guion.
          $char = str_replace('-', ' ', $entry);
          // Contamos todo lo que este dentro del explode
          $expStr = explode(' ', $char);
          $cntStr = count($expStr);

          // Aqui pasamos a mayusculas lo que necesitamos
          // para el titulo de todas las palabras que entren
          $StrUpp = '';
          for ($i= 0; $i < $cntStr; $i++) { 
            
              // Aqui hacemos que la primer letra de cada palabra
              // sea mayuscula y agregamos un espacio para volver a
              // separar todas las palabras.
              $StrUpp .= ucfirst($expStr[$i]).' ';
          }
          
          // Aqui es donde definimos como va a estar el String
          // en nuestra etiqueta Title dentro del HTML
          if (is_null($entry) || empty($entry) || ctype_space($entry)) {
            $StrFinal = $title;
          }else{
            $titleExp = explode('- ', $title);
            $StrFinal = $StrUpp.'- '.$titleExp[0];
          }

          // Definimos como el titulo del sitio y listo solamente se pasa.
          define('TITLESITE', $StrFinal);

          $tsite = explode(' - ', $StrFinal);
          define('TITLENAME', $tsite[0]);

}

// Datos del Sitio
function getDataSite(){
    // Conexion a base de datos
    $db = new DataBase;
    $where = array('id' => 1);
    $rst = $db->select('configuration', '*', $where);
    foreach ($rst as $key){
    	$data = $key['titlesite'].'|'.$key['descsite'].'|'.$key['logo'].'|'.$key['direccion'].'|'.$key['telefono'].'|'.$key['cateco'].'|'.$key['mesas'].'|'.$key['estilo'].'|'.$key['color'].'|'.$key['porcentaje'].'|'.$key['divisa'].'|'.$key['smtp'].'|'.$key['usersmtp'].'|'.$key['passmtp'].'|'.$key['smtport'].'|'.$key['smtpactive'].'|'.$key['decimals'].'|'.$key['miles'];
    }
    return $data;
}

// Verificar si esta logeado.
function isLogin(){
    $router = new LogonAuth();
    $check = $router->login_check();
    return $check;
}

// Saber si es administrador
function isAdmin(){
    $db = new DataBase;
    $where = array('id' => $_SESSION['idSession']);
    $selectSQL = $db->select('usuarios', '*', $where);
    foreach ($selectSQL as $key){
        $rank = $key['usertype'];
    }
    if ($rank == 2){
        return TRUE;
    }else{
        return FALSE;
    }
}

// Titulo del sitio
function normalTitleSite(){
	$dataSite = getDataSite();
	$exp = explode('|', $dataSite);
	$title = $exp[0].' - '.$exp[1];
    return $title;
}

/* Todo lo referente a los no usuarios */
function LoginFunc($usuario, $password){
    $logon = new LogonAuth;
    $rst = $logon->LoginUser($usuario, $password);
    return $rst;
}


function checkMesa($numesa){
    $db = new DataBase;
    $where = array('numesa' => $numesa, 'status' => 1);
    $selectSQL = $db->select('mesas', '*', $where);
    if (empty($selectSQL)){
         $btn = '<h3 class="text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mug"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4.083 5h10.834a1.08 1.08 0 0 1 1.083 1.077v8.615c0 2.38 -1.94 4.308 -4.333 4.308h-4.334c-2.393 0 -4.333 -1.929 -4.333 -4.308v-8.615a1.08 1.08 0 0 1 1.083 -1.077" /><path d="M16 8h2.5c1.38 0 2.5 1.045 2.5 2.333v2.334c0 1.288 -1.12 2.333 -2.5 2.333h-2.5" /></svg>Mesa Libre</h3><button type="button" data-mesa="'.$numesa.'" class="opentable btn btn-lg btn-block btn-outline-primary">Abrir Mesa</button> ';
    }else{
       foreach ($selectSQL as $key){
         $encargado = nameUser($key['encargado']);
         $btn = '<h3 class="text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mood-smile"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 10l.01 0" /><path d="M15 10l.01 0" /><path d="M9.5 15a3.5 3.5 0 0 0 5 0" /></svg>'.$encargado.'</h3><a href="'.URLSITE.'mesa/'.$key['hash'].'" class="btn btn-lg btn-block btn-outline-warning">Ver Cuentas</a> ';
       }
    }
    return $btn;
}


/* Desplegar Mesas */
function DisplayMesas(){

  $dataSite = getDataSite();
  $exp = explode('|', $dataSite);
  echo '<div class="container"><div class="row">';
  $num = $exp[6] + 1;
  for ($i=1; $i < $num; $i++){ 
    echo'
      <div class="col-sm-3 mt-4">
        <div class="card">
          <div class="headermesa card-header p-2 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-credit-card" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg> Mesa #'.$i.'
          </div>
          <div class="card-body">
             '.checkMesa($i).'  
          </div>
        </div>
      </div>
    ';
  }
  echo '</div></div>';
}

function getCateJSON(){

   if (isLogin() == FALSE){exit();}
   $db = new DataBase;

   $dataSite = getDataSite();
   $exp = explode('|', $dataSite);

   $selectSQL = $db->select('insumos', '*');
   if (empty($selectSQL)){
     return;
   }else{
     $getIns = '';
     foreach ($selectSQL as $key){

      if ($exp[17] == 1){
        $fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
        $precchk = str_replace('$', '', $fmt->formatCurrency($key['precio'], "CLP"));
      }else{
        $precchk = $key['precio'];
      }


      $getIns .='[
         "<p id=nmins'.$key['id'].'>'.$key['nombre'].'</p><span  class=badge>'.$key['descripcion'].'</span>",
         "'.getCate($key['categoria']).'",
         "'.$key['codigo'].'",
         "$'.$precchk.'",
         "<a onclick=adProTable('.$key['id'].') id=addbutton'.$key['id'].' class=addbutton data-id='.$key['id'].' data-price='.$key['precio'].'>Agregar</a>"],';

     }
   }

   $res = substr($getIns, 0, -1);
   $finalData = '{"data":['.$res.']}';
   echo $finalData;

}


function getInsuJSON(){

   if (isLogin() == FALSE){exit();}
   $db = new DataBase;
   $selectSQL = $db->select('insumos', '*');
   if (empty($selectSQL)){
     return;
   }else{
     $getIns = '';
     foreach ($selectSQL as $key){

      $getIns .='[
         "'.$key['nombre'].'",
         "'.$key['descripcion'].'",
         "'.$key['codigo'].'",
         "$'.$key['precio'].'",
         "'.getCate($key['categoria']).'",
         "<div class=btn-group><button onclick=editarInsumo('.$key['id'].') class=btn>Editar</button><button onclick=deleteInsumo('.$key['id'].') class=btn>Eliminar</button></div>"],';

     }
   }

   $res = substr($getIns, 0, -1);
   $finalData = '{"data":['.$res.']}';
   echo $finalData;

}

function getCatesJSON(){
   if (isLogin() == FALSE){exit();}
   $db = new DataBase;
   $selectSQL = $db->select('categoria', '*');
   if (empty($selectSQL)){
     return;
   }else{
     $getIns = '';
     foreach ($selectSQL as $key){

      if ($key['comanda'] == 1){
        $comanda = 'No';
      }else{
        $comanda = 'Si';
      }

      $getIns .='[
         "'.$key['nombre'].'",
         "'.$comanda.'",
         "<div class=btn-group><button onclick=editarCategoria('.$key['id'].') class=btn>Editar</button><button onclick=deleteCategoria('.$key['id'].') class=btn>Eliminar</button></div>"],';
     }
   }
   $res = substr($getIns, 0, -1);
   $finalData = '{"data":['.$res.']}';
   echo $finalData;
}


function getUsersJSON(){
   if (isLogin() == FALSE){exit();}
   $db = new DataBase;
   $selectSQL = $db->select('usuarios', '*');
   if (empty($selectSQL)){
     return;
   }else{
     $getIns = '';
     foreach ($selectSQL as $key){

      if ($key['status'] == 1){
        $sts = 'Inactivo';
      }else{
        $sts = 'Activo';
      }
      if ($key['usertype'] == 1){
        $rnk = 'Usuario';
      }else{
        $rnk = 'Administrador';
      } 

      $getIns .='[
         "'.$key['nombre'].'",
         "'.$key['usuario'].'",
         "'.$key['usermail'].'",
         "'.$rnk.'",
         "'.$sts.'",
         "<div class=btn-group><button onclick=editarUser('.$key['id'].') class=btn>Editar</button><button onclick=deleteUser('.$key['id'].') class=btn>Eliminar</button></div>"],';
     }
   }
   $res = substr($getIns, 0, -1);
   $finalData = '{"data":['.$res.']}';
   echo $finalData;
}

function getCate($id){
    $db = new DataBase;
    $where = array('id' => $id);
    $selectSQL = $db->select('categoria', '*', $where);
    foreach ($selectSQL as $key){
        return $key['nombre'];
    }
}

function getTotalOfTable($cuentas){

    // Tomamos los datos del sitio
    $dataSite = getDataSite();
    $expDS = explode('|', $dataSite);

    if (is_null($cuentas)){
      return 0;
    }
    $db = new DataBase;
    $exp = explode(',', $cuentas);
    $cnt = count($exp);
    $price = null;
    for ($i=0; $i < $cnt; $i++){ 
       $arr = array('id' => $exp[$i]);
       $rst = $db->select('cuentas', '*', $arr);
       foreach ($rst as $key){
          $price += getDataAccActive2($key['insumos']);
       }
    }

    // Decimales
    if ($expDS[16] == 1){

      if ($expDS[9] > 0){
        $op = $price * $expDS[9] / 100;
        $sm = $price + $op;
        return number_format((float)$sm, 2, '.', '');
      }else{
        return number_format((float)$price, 2, '.', '');
      }

    }else{

      if ($expDS[9] > 0){
        $op = $price * $expDS[9] / 100;
        $sm = $price + $op;
        return $sm;
      }else{
        return $price;
      }

    }
    
}

function getTotalOfTable2($cuentas){

    if (is_null($cuentas)){
      return 0;
    }
    $db = new DataBase;
    $exp = explode(',', substr($cuentas, 0, -1));
    $cnt = count($exp);
    $price = null;
    for ($i=0; $i < $cnt; $i++){ 
       $arr = array('id' => $exp[$i]);
       $rst = $db->select('cuentas', '*', $arr);
       foreach ($rst as $key){
          $price += getDataAccActive2($key['insumos']);
       }
    }
    
    return $price;

}


function getthePriceStock($at, $ct){
    // Conexion a base de datos
    $db = new DataBase;
    $where = array('id' => $at);
    $rst = $db->select('insumos', '*', $where);
    if (empty($rst)){
       return 0; 
    }else{
       foreach ($rst as $key){
         $price = $key['precio'] * $ct;
       }
       return $price;      
    }

}




function getDataAccActive($insumos){

    // Tomamos los datos del sitio
    $dataSite = getDataSite();
    $expDS = explode('|', $dataSite);

    if (empty($insumos)){
      return 0;
    }
    
    // Sacar listado
    $dt = explode('#', substr($insumos, 0, -1));

    // Sacamos la cantidad de productos
    $cnt = count($dt);

    // Sacamos los precios
    $price = null;
    for ($i=0; $i < $cnt; $i++) { 
      $at = explode('|', $dt[$i]);
      $price += getthePriceStock($at[0],$at[1]);
    }

    // Sacamos lo de las decimales en el total
    if ($expDS[16] == 1){
      $mysm = number_format((float)$sm, 2, '.', '');
      $myprc = number_format((float)$price, 2, '.', '');
    }else{
      
      if ($expDS[17] == 1) {

        //$fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
        //$mysm = str_replace('$', '', $fmt->formatCurrency($sm, "CLP"));
        //$myprc = str_replace('$', '', $fmt->formatCurrency($price, "CLP"));
        $mysm = $sm;
        $myprc = $price;

      }else{
        $mysm = $sm;
        $myprc = $price;        
      }

    }

    if ($expDS[9] > 0){
      $op = $price * $expDS[9] / 100;
      $sm = $price + $op;
      return $sm;
    }else{
      return $myprc;
    }

    

}

function getDataAccActive2($insumos){

    // Tomamos los datos del sitio
    $dataSite = getDataSite();
    $expDS = explode('|', $dataSite);

    if (empty($insumos)){
      return 0;
    }
    
    // Sacar listado
    $dt = explode('#', $insumos);

    // Sacamos la cantidad de productos
    $cnt = count($dt);

    // Sacamos los precios
    $price = null;
    for ($i=0; $i < $cnt; $i++) { 
      $at = explode('|', $dt[$i]);
      $price += getthePriceStock($at[0],$at[1]);
    }

    if ($expDS[16] == 1){
      return number_format((float)$price, 2, '.', '');
    }else{
      return $price;
    }

    

}


function ReturnHashLink($mesa){

    // Conexion a base de datos
    $db = new DataBase;
    $where = array('id' => $mesa);
    $rst = $db->select('mesas', '*', $where);
    foreach ($rst as $key){
        return $key['hash'];
    }

}

function parseDataIns($insm, $mode){
    
    $purgate = substr($insm, 0, -1);
    $exp = explode('#', $purgate); 
    $cnt = count($exp);
    $data = null;
    for ($i=0; $i < $cnt; $i++){ 
       $data .= parseSecondData($exp[$i], $mode);
    }

    return $data;
}

function parseDataInsInfo($insm, $mode){
    
    $purgate = substr($insm, 0, -1);
    $exp = explode('#', $purgate); 
    $cnt = count($exp);
    $data = null;
    for ($i=0; $i < $cnt; $i++){ 
       $data .= getRowsInfoTicketVentas($exp[$i]);
    }

    return $data;
}


/*
1|3||1#
2|2||1#
3|1||1#
4|1||1#
5|1||1#
6|1||1
*/

function getTheProNameRtn($id){

    // Conexion a base de datos
    $db = new DataBase;
    $where = array('id' => $id);
    $rst = $db->select('insumos', '*', $where);
    foreach ($rst as $key){
        $nombre = $key['nombre'];
        $precio = $key['precio'];
        $status = $key['status'];
    }

    return $nombre.'|'.$precio.'|'.$status;

}


function displayInsAccount($hash){

    // Conexion a base de datos
    $db = new DataBase;
    $where = array('hash' => $hash);
    $rst = $db->select('cuentas', '*', $where);
    if (empty($rst)){
         return;
    }else{
      foreach ($rst as $key){
         $data = parseDataIns($key['insumos'], 1);
      }
    }

    return $data;

}

function nameUser($id){
    $db = new DataBase;
    $where = array('id' => $id);
    $selectSQL = $db->select('usuarios', '*', $where);
    foreach ($selectSQL as $key){
        $name = $key['nombre'];
    }
    return $name;
}

function rtnInsumos($id){

    $db = new DataBase;
    $where = array('id' => $id);
    $selectSQL = $db->select('cuentas', '*', $where);
    foreach ($selectSQL as $key){
        $insumos = $key['insumos'];
    }
    return $insumos;

}

function getRowsInfoTicket($insumos){
    $dataSite = getDataSite();
    $exp = explode('|', $dataSite);

    $insData = explode('|', $insumos);
    $db = new DataBase;
    $where = array('id' => $insData[0]);
    $selectSQL = $db->select('insumos', '*', $where);
    $dt = '';
    foreach ($selectSQL as $key){

      if ($exp[16] == 1){
        $myprs = $key['precio']; 
        $mttpr = $key['precio'] * $insData[1]; 
        $ttlper = $mttpr;
      }else{
      if ($exp[17] == 1) {
        $fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
        $myprs = str_replace('$', '', $fmt->formatCurrency($key['precio'], "CLP")); 
        $mttpr = $key['precio'] * $insData[1]; 
        $ttlper = str_replace('$', '', $fmt->formatCurrency($mttpr, "CLP"));
      }else{
        $myprs = $key['precio'];  
        $ttlper = $key['precio'] * $insData[1];        
      }
      }

      $dt = '
        '.$key['nombre'].' [$'.$myprs.'<small>'.$exp[10].'</small>'.']  ['.$insData[1].'] ..... $'.$ttlper.'<small>'.$exp[10].'</small><p></p>
        ';
    }
    return $dt; 
}

function getRowsInfoTicketVentas($insumos){
    $dataSite = getDataSite();
    $exp = explode('|', $dataSite);

    $insData = explode('|', $insumos);
    $db = new DataBase;
    $where = array('id' => $insData[0]);
    $selectSQL = $db->select('insumos', '*', $where);
    $dt = '';
    foreach ($selectSQL as $key){
        
      $ttlper = $key['precio'] * $insData[1];
      $dt = ''.$key['nombre'].' [$'.$key['precio'].''.$exp[10].''.'] ['.$insData[1].'] $'.$ttlper.''.$exp[10].'';
    }
    return $dt; 
}

// Removido de la v3 de lado fronted
// para eliminarse luego
function rtnCate(){
    $db = new DataBase;
    $selectSQL = $db->select('categoria', '*');
    $cate = '';
    foreach ($selectSQL as $key){
        $cate .= '<option value="'.$key['id'].'">'.$key['nombre'].'</option>';
    }
    return $cate;
}


// Removido de la v3 de lado fronted
// para eliminarse luego
function rntCatePer($id){
    $db = new DataBase;
    if (empty($id)){
      $selectSQL = $db->select('categoria', '*');
    }else{
      $selectSQL = $db->select('categoria', '*', array('id' => $id));
    }
    $rtn = '';
    foreach ($selectSQL as $key){
      $rtn .= '<option value="'.$key['id'].'">'.$key['nombre'].'</option>';
    }
    return '<optgroup>'.$rtn.'</optgroup>';
}


function checkUser($user){
    $db = new DataBase;
    $where = array('usuario' => $user);
    $selectSQL = $db->select('usuarios', '*', $where);
    if (empty($selectSQL)){
      $dt = 1;
    }else{
      $dt = 2;
    }
    return $dt;
}

function checkMail($mail){
    $db = new DataBase;
    $where = array('usermail' => $mail);
    $selectSQL = $db->select('usuarios', '*', $where);
    if (empty($selectSQL)){
      $dt = 1;
    }else{
      $dt = 2;
    }
    return $dt;
}


function compareMyMail($id){
    $db = new DataBase;
    $where = array('id' => $id);
    $selectSQL = $db->select('usuarios', '*', $where);
    foreach($selectSQL as $key){
        $mymail = $key['usermail'];
    }
    return $mymail;
}




// Para informes

/* Mesas ocupadas */
function CountTableActive(){
    $db = new DataBase;
    $selectSQL = $db->QueryLong('SELECT COUNT(*) AS cuantas FROM mesas WHERE status = 1');
    foreach ($selectSQL as $key){
      return $key['cuantas'];
    }
}

/* Cuentas Abiertas */
function CountAccActive(){
    $db = new DataBase;
    $selectSQL = $db->QueryLong('SELECT COUNT(*) AS cuantas FROM cuentas');
    foreach ($selectSQL as $key){
      return $key['cuantas'];
    }
}

/* Ganancias del dia */
function Money4Day(){
    
    $dataSite = getDataSite();
    $exp = explode('|', $dataSite);

    $db = new DataBase;
    $selectSQL = $db->QueryLong('SELECT total FROM ventas WHERE ano = "'.date('Y').'" AND mes = "'.date('m').'" AND dia = "'.date('d').'"');
    $total = null;
    foreach ($selectSQL as $key){
      $total += $key['total'];
    } 

    return '$'.$total.' '.$exp[10];
}



function getVentasDayJSON(){
   if (isLogin() == FALSE){exit();}
   $db = new DataBase;
   $selectSQL = $db->QueryLong('SELECT * FROM ventas LIMIT 50');
   if (empty($selectSQL)){
     return;
   }else{
     $getIns = '';
     foreach ($selectSQL as $key){

      if ($key['tipopago'] == 1){
        $tpp = 'Efectivo';
      }else{
        $tpp = 'Tarjeta';
      }

      $getIns .='[
         "'.$key['id'].'",
         "'.$key['mesa'].'",
         "'.$key['cuenta'].'",
         "'.nameUser($key['usuario']).'",
         "'.parseDataInsInfo($key['articulos'], 2).'",
         "'.$key['dia'].'/'.$key['mes'].'/'.$key['ano'].'",
         "'.$key['hora'].'",
         "$'.$key['total'].'",
         "$'.$key['pagocon'].'",
         "$'.$key['cambio'].'",
         "'.$tpp.'",
         "'.$key['propina'].'%",
         "<a target=_blank href='.URLSITE.'ticket/'.$key['hash'].'>Imprimir Ticket</a>"],';
     }
   }
   $res = substr($getIns, 0, -1);
   $finalData = '{"data":['.$res.']}';
   echo $finalData;
}

function usersOptionsInfo(){
    $db = new DataBase;
    $selectSQL = $db->QueryLong('SELECT * FROM usuarios WHERE usertype = 1 AND status = 2');
    $opt = '';
    foreach ($selectSQL as $key){
      $opt .= '<option value="'.$key['id'].'">'.$key['nombre'].'</option>';
    }
    return $opt;
}


// CREACION DE INFORMES.
function getHeadExcel(){

   $html = '<table><thead><tr>
            <th>Folio</th>
            <th>Mesa</th>
            <th>Cuenta</th>
            <th>Mesero</th>
            <th>Consumo</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Total</th>
            <th>Pago Con</th>
            <th>Cambio</th>
            <th>Tipo de Pago</th>
            <th>Propina</th>
          </tr><tbody>';
   return $html;

}

function getTheFooterExcel(){
   $html = '</tbody></table>';
   return $html;
}


// Informe completo
function getToAllInfo(){

   if (isLogin() == FALSE){exit();}
   $db = new DataBase;
   $selectSQL = $db->QueryLong('SELECT * FROM ventas');
   $data = '';
   foreach ($selectSQL as $key){

      if ($key['tipopago'] == 1){
        $tpp = 'Efectivo';
      }else{
        $tpp = 'Tarjeta';
      }

     $data .='
        <tr>
          <td>'.$key['idmesa'].'</td>
          <td>'.$key['mesa'].'</td>
          <td>'.$key['cuenta'].'</td>
          <td>'.nameUser($key['usuario']).'</td>
          <td>'.parseDataInsInfo($key['articulos'], 2).'</td>
          <td>'.$key['dia'].'/'.$key['mes'].'/'.$key['ano'].'</td>
          <td>'.$key['hora'].'</td>
          <td>$'.$key['total'].'</td>
          <td>$'.$key['pagocon'].'</td>
          <td>$'.$key['cambio'].'</td>
          <td>'.$tpp.'</td>
          <td>'.$key['propina'].'%</td>
        </tr>
     ';
   }
  header("Content-Type: application/xls");    
  header("Content-Disposition: attachment; filename=Informe_Completo_Ventas_" . date('Y:m:d:m:s').".xls");
  header("Pragma: no-cache"); 
  header("Expires: 0");
  $header = getHeadExcel();
  $footer = getTheFooterExcel();
  echo $header.$data.$footer;

}

function getToAllInfoPerMesero(){

   if (isLogin() == FALSE){exit();}
   $db = new DataBase;
   $selectSQL = $db->QueryLong('SELECT * FROM ventas WHERE usuario = '.$_SESSION['idmesero'].'');
   $data = '';
   foreach ($selectSQL as $key){

      if ($key['tipopago'] == 1){
        $tpp = 'Efectivo';
      }else{
        $tpp = 'Tarjeta';
      }

     $data .='
        <tr>
          <td>'.$key['idmesa'].'</td>
          <td>'.$key['mesa'].'</td>
          <td>'.$key['cuenta'].'</td>
          <td>'.nameUser($key['usuario']).'</td>
          <td>'.parseDataInsInfo($key['articulos'], 2).'</td>
          <td>'.$key['dia'].'/'.$key['mes'].'/'.$key['ano'].'</td>
          <td>'.$key['hora'].'</td>
          <td>$'.$key['total'].'</td>
          <td>$'.$key['pagocon'].'</td>
          <td>$'.$key['cambio'].'</td>
          <td>'.$tpp.'</td>
          <td>'.$key['propina'].'%</td>
        </tr>
     ';
   }
  header("Content-Type: application/xls");    
  header("Content-Disposition: attachment; filename=Informe_Completo_Ventas_Mesero_".nameUser($_SESSION['idmesero'])."_" . date('Y:m:d:m:s').".xls");
  header("Pragma: no-cache"); 
  header("Expires: 0");
  $header = getHeadExcel();
  $footer = getTheFooterExcel();
  echo $header.$data.$footer;

}

function getToAllInfoPerMesa(){

   if (isLogin() == FALSE){exit();}
   $db = new DataBase;
   $selectSQL = $db->QueryLong('SELECT * FROM ventas WHERE mesa = '.$_SESSION['numesa'].'');
   $data = '';
   foreach ($selectSQL as $key){

      if ($key['tipopago'] == 1){
        $tpp = 'Efectivo';
      }else{
        $tpp = 'Tarjeta';
      }

     $data .='
        <tr>
          <td>'.$key['idmesa'].'</td>
          <td>'.$key['mesa'].'</td>
          <td>'.$key['cuenta'].'</td>
          <td>'.nameUser($key['usuario']).'</td>
          <td>'.parseDataInsInfo($key['articulos'], 2).'</td>
          <td>'.$key['dia'].'/'.$key['mes'].'/'.$key['ano'].'</td>
          <td>'.$key['hora'].'</td>
          <td>$'.$key['total'].'</td>
          <td>$'.$key['pagocon'].'</td>
          <td>$'.$key['cambio'].'</td>
          <td>'.$tpp.'</td>
          <td>'.$key['propina'].'%</td>
        </tr>
     ';
   }
  header("Content-Type: application/xls");    
  header("Content-Disposition: attachment; filename=Informe_Completo_Ventas_Mesa_".$_SESSION['numesa']."_" . date('Y:m:d:m:s').".xls");
  header("Pragma: no-cache"); 
  header("Expires: 0");
  $header = getHeadExcel();
  $footer = getTheFooterExcel();
  echo $header.$data.$footer;

}


function getToAllInfoPerDay(){

   if (isLogin() == FALSE){exit();}
   $db = new DataBase;
   $selectSQL = $db->QueryLong('SELECT * FROM ventas WHERE dia ="'.date('d').'" AND mes ="'.date('m').'" AND ano ="'.date('Y').'"');
   $data = '';
   foreach ($selectSQL as $key){

      if ($key['tipopago'] == 1){
        $tpp = 'Efectivo';
      }else{
        $tpp = 'Tarjeta';
      }

     $data .='
        <tr>
          <td>'.$key['idmesa'].'</td>
          <td>'.$key['mesa'].'</td>
          <td>'.$key['cuenta'].'</td>
          <td>'.nameUser($key['usuario']).'</td>
          <td>'.parseDataInsInfo($key['articulos'], 2).'</td>
          <td>'.$key['dia'].'/'.$key['mes'].'/'.$key['ano'].'</td>
          <td>'.$key['hora'].'</td>
          <td>$'.$key['total'].'</td>
          <td>$'.$key['pagocon'].'</td>
          <td>$'.$key['cambio'].'</td>
          <td>'.$tpp.'</td>
          <td>'.$key['propina'].'%</td>
        </tr>
     ';
   }
  header("Content-Type: application/xls");    
  header("Content-Disposition: attachment; filename=Informe_Completo_Del_Dia_" . date('Y:m:d').".xls");
  header("Pragma: no-cache"); 
  header("Expires: 0");
  $header = getHeadExcel();
  $footer = getTheFooterExcel();
  echo $header.$data.$footer;

}



function getToAllInfoPerDate(){

   $fecha = $_SESSION['day'].'_'.$_SESSION['month'].'_'.$_SESSION['year'];

   if (isLogin() == FALSE){exit();}
   $db = new DataBase;
   $selectSQL = $db->QueryLong('SELECT * FROM ventas WHERE dia = "'.$_SESSION['day'].'" AND mes = "'.$_SESSION['month'].'" AND ano = "'.$_SESSION['year'].'"');
   $data = '';
   foreach ($selectSQL as $key){

      if ($key['tipopago'] == 1){
        $tpp = 'Efectivo';
      }else{
        $tpp = 'Tarjeta';
      }

     $data .='
        <tr>
          <td>'.$key['idmesa'].'</td>
          <td>'.$key['mesa'].'</td>
          <td>'.$key['cuenta'].'</td>
          <td>'.nameUser($key['usuario']).'</td>
          <td>'.parseDataInsInfo($key['articulos'], 2).'</td>
          <td>'.$key['dia'].'/'.$key['mes'].'/'.$key['ano'].'</td>
          <td>'.$key['hora'].'</td>
          <td>$'.$key['total'].'</td>
          <td>$'.$key['pagocon'].'</td>
          <td>$'.$key['cambio'].'</td>
          <td>'.$tpp.'</td>
          <td>'.$key['propina'].'%</td>
        </tr>
     ';
   }
  header("Content-Type: application/xls");    
  header("Content-Disposition: attachment; filename=Informe_Fecha_".$fecha.".xls");
  header("Pragma: no-cache"); 
  header("Expires: 0");
  $header = getHeadExcel();
  $footer = getTheFooterExcel();
  echo $header.$data.$footer;

}


function getToAllVendidoMes(){

   if (isLogin() == FALSE){exit();}
   $db = new DataBase;
   $selectSQL = $db->QueryLong('SELECT * FROM stats WHERE mes = '.date('m').'');
   $data = '';
   foreach ($selectSQL as $key){
       
     $insdata = getTheProNameRtn($key['insumo']);
     $expinsname = explode('|', $insdata);

     $data .='
        <tr>
          <td>'.$expinsname[0].'</td>
          <td>'.$key['cantidad'].' Venta(s)</td>
        </tr>
     ';
   }
  header("Content-Type: application/xls");    
  header("Content-Disposition: attachment; filename=Lo_Mas_Vendido_del_Mes_".date('d-m-Y').".xls");
  header("Pragma: no-cache"); 
  header("Expires: 0");
  $header = '<table><thead><tr>
            <th>Insumo</th>
            <th>Ventas</th>
          </tr><tbody>
  ';
  $footer = getTheFooterExcel();
  echo $header.$data.$footer;

}


function ParseArticlesCocina($data, $mesa, $encargado, $cate, $nameacc){
   
   $db = new DataBase;

   // Separamos los articulos
   $exp = explode('|', $data);

   $insumo = $exp[0];
   $cantidad = $exp[1];
   $detalles = $exp[2];
   $entregado = $exp[3];
   $rtn = '';

   // Sacamos la categoria del insumo
   $selectSQL = $db->QueryLong('SELECT * FROM insumos WHERE id ='.$insumo.' AND categoria = '.$cate);
   foreach ($selectSQL as $key){

     if ($mesa == 0){
       $mymesa = 'Cuenta: '.$nameacc;
     }else{
       $mymesa = 'Mesa #'.$mesa;
     }

     if ($entregado == 1){
       $rtn .= '
       <tr>
         <td>'.$key['nombre'].'</td>
         <td>'.$cantidad.'</td>
         <td>'.base64_decode($detalles).'</td>
         <td>'.$encargado.'</td>
         <td>'.$mymesa.'</td>
       </tr>';
     }
   }

   return $rtn;
   
}


function ParseOrdenesCocina($id, $mesa, $encargado, $cate){

   $db = new DataBase;
   $selectSQL = $db-> select('cuentas', '*', array('id' => $id));
   foreach ($selectSQL as $key){
       $insumos = $key['insumos'];
       $nameacc = $key['nombre'];
   }
   
   $exp = explode('#', substr($insumos, 0, -1));
   $cntexp = count($exp);
   $tnb = '';

   for ($i=0; $i < $cntexp; $i++){ 
      $tnb .= ParseArticlesCocina($exp[$i], $mesa, $encargado, $cate, $nameacc);
   }

   return $tnb;

}

function checkMesaChg(){

    $db = new DataBase;
    $dataSite = getDataSite();
    $exp = explode('|', $dataSite);

    $num = $exp[6] + 1;
    $btn = '';
    for ($i=1; $i < $num; $i++){

    $where = array('numesa' => $i, 'status' => 1);
    $selectSQL = $db->select('mesas', '*', $where);
    if (empty($selectSQL)){
         $btn .= '<button type="button" class="btn btn-primary mt-2 slctchg" data-numesa="'.$i.'">Mesa #'.$i.'</button> ';
    }else{
       foreach ($selectSQL as $key){
         $btn .= '<button type="button" class="btn btn-secondary mt-2" style="margin-right:5px;">Mesa #'.$i.'</button>';
       }
    }
   }

   return $btn; 
}


/* Recuperar Cuenta */
function RecoveryAccount($usrmail){

   $stcng = getDataSite();
   $ex = explode('|', $stcng);
   if ($ex[15] == 1){
     echo 2;
     return;
   }

   $db = new DataBase;
   $rst = $db->QueryLong('SELECT * FROM usuarios WHERE usermail = "'.$usrmail.'" LIMIT 1');
   if (empty($rst)){
      echo 2;
      return;
   }else{
     foreach ($rst as $key){
         
         $id = $key['id'];
         $usuario = $key['usuario'];
         $email = $key['usermail'];
         $newpass = randomString(8);
         $newcrypt = password_hash($newpass.SALT, PASSWORD_DEFAULT);

         $subject = $ex[0].' - Recuperacion de Cuenta.';
         $message = '<p>Has solicitado el cambio de contrase&ntilde;a</p><p>Usuario:<strong> '.$usuario.'</strong></p><p>Nueva Contrase&ntilde;a:<strong> '.$newpass.'</strong></p>';   

         $upda = array('userpass' => $newcrypt);
         $where = array('id' => $key['id']);
         $db->update('usuarios', $where, $upda);     

         $result = SendingEmailPM($email, $subject, $message);
         if ($result == 1){
           echo 3;
         }else{
           echo 2;
         }
     }
   }
}


function SendingEmailPM($email, $subject, $message){


$mail = new PHPMailer();
$stcng = getDataSite();
$ex = explode('|', $stcng);

try {
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = $ex[11];                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = $ex[12];                  //SMTP username
    $mail->Password   = $ex[13];                       //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = $ex[14];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($ex[12], 'Webmaster '.$ex[0]);
    $mail->addAddress($email);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->send();
    return 1;
} catch (Exception $e) {
    return 2;
}

}

function DownloadAllInsumos(){
    if (isAdmin() == FALSE){exit();}
    $db = new DataBase;
    $selectSQL = $db->select('insumos', '*');
    $dt = '';
    foreach ($selectSQL as $key){
      $dt .= $key['nombre'].'|'.$key['descripcion'].'|'.$key['codigo'].'|'.$key['precio']."\n";
    }
    header("Content-Type: Content-Type: text/plain");    
    header("Content-Disposition: attachment; filename=Insumos_Completos_Cafesys_" . date('Y:m:d').".txt");
    header("Pragma: no-cache"); 
    header("Expires: 0");
    echo $dt;
}


function restoreCafeSys(){
    $db = new DataBase;
    $db->QueryLong('TRUNCATE TABLE ventas');
    $db->QueryLong('TRUNCATE TABLE mesas');
    $db->QueryLong('TRUNCATE TABLE insumos');
    $db->QueryLong('TRUNCATE TABLE categoria');
    $db->QueryLong('TRUNCATE TABLE cuentas');
    $db->QueryLong('TRUNCATE TABLE stats');
    $db->QueryLong('DELETE FROM usuarios WHERE usertype <> 2');
    $db->add('categoria', array('nombre' => 'Default', 'comanda' => 1, 'id' => 1));
    $insinsumos = array('id' => 1,'nombre' => 'Articulo de Ejemplo', 'descripcion' => 'Esto es un ejemplo','categoria' => 1,'codigo' => 123,'precio' => 10.00, 'status' => 1);
    $db->add('insumos', $insinsumos);
}


function BackUpTables(){

    // Tiene que estar logeado
    if (isAdmin() == FALSE){exit();}

    // Tiempo limite
    set_time_limit(300000);
    
    // Limite de memoria 
    ini_set('memory_limit', '2048M');

    // Variable en blanco
    $tablasARespaldar = [];

    // Conexion a base de datos 
    $conexion = new PDO("mysql:host=".HOSTINGDB.";port=".PORTDATABASE.";dbname=".DATABASENAME, USERDATABASE, PASSDATABASE);
    $conexion -> exec("SET CHARACTER SET utf8");

    // Decodificar Tablas
    $tablasdecode = 'categoria,configuration,cuentas,insumos,mesas,usuarios,ventas';

    // Separar y Contar Tablas
    $expl = explode(',', $tablasdecode);

    // Contar la cantidad de tablas
    $count = count($expl);

    // Contenido del respaldo
    $contenido = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSTART TRANSACTION;\r\nSET time_zone = \"+00:00\";\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `" . $nombreDeBaseDeDatos . "`\r\n--\r\n";

    // Pasamos los datos usando un for
    for ($i = 0; $i < $count; $i++) { 
        $tablasARespaldar[] = $expl[$i];
    }

    //------------------------------------------
    foreach ($tablasARespaldar as $nombreDeLaTabla){

        // Datos e cada tabla a respaldar
        $datosQueContieneLaTabla = $conexion->prepare('SELECT * FROM `'.$nombreDeLaTabla.'`');
        $datosQueContieneLaTabla->execute();
        $cantidadDeCampos = $datosQueContieneLaTabla->columnCount();
        $cantidadDeFilas = $datosQueContieneLaTabla->rowCount();

        // Creamos el esquema de la Tabla
        $esquemaDeTabla = $conexion->prepare('SHOW CREATE TABLE `'.$nombreDeLaTabla.'`');
        $esquemaDeTabla->execute();
        $filaDeTabla = $esquemaDeTabla->fetch();

        // Agregamos el contenido
        $contenido .= "\n\n" . $filaDeTabla[1] . ";\n\n";

        for ($i = 0, $contador = 0; $i < $cantidadDeCampos; $i++, $contador = 0) {
            while ($fila = $datosQueContieneLaTabla->fetch()) {
                //La primera y cada 100 veces
                if ($contador % 100 == 0 || $contador == 0) {
                    $contenido .= "\nINSERT INTO " . $nombreDeLaTabla . " VALUES";
                }
                $contenido .= "\n(";
                for ($j = 0; $j < $cantidadDeCampos; $j++) {
                    $fila[$j] = str_replace("\n", "\\n", addslashes($fila[$j]));
                    if (isset($fila[$j])) {
                        $contenido .= '"' . $fila[$j] . '"';
                    } else {
                        $contenido .= '""';
                    }
                    if ($j < ($cantidadDeCampos - 1)) {
                        $contenido .= ',';
                    }
                }
                $contenido .= ")";
                # Cada 100...
                if ((($contador + 1) % 100 == 0 && $contador != 0) || $contador + 1 == $cantidadDeFilas) {
                    $contenido .= ";";
                } else {
                    $contenido .= ",";
                }
                $contador = $contador + 1;
            }
        }
        $contenido .= "\n\n\n";

    }
    //------------------------------------------
    $contenido .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";


    header("Content-Type: Content-Type: text/plain");    
    header("Content-Disposition: attachment; filename=Database_Cafesys3_" . date('Y:m:d').".sql");
    header("Pragma: no-cache"); 
    header("Expires: 0");
    echo $contenido;

}


function smtpConfiguration($smtpserver,$smtpuser,$smtppass,$stmport,$smtpstatus){

    $db = new DataBase;
    
    $cfg = array('id' => 1);

    $dataSMTP = array(
      'smtp' => $smtpserver, 
      'usersmtp' => $smtpuser, 
      'passmtp' => $smtppass, 
      'smtport' => $stmport, 
      'smtpactive' => $smtpstatus
    );

    $db->update('configuration', $cfg, $dataSMTP);

}

function DisplayAccountsZero($id){

    $account = substr($id, 0, -1);

    // Tomamos los datos del sitio
    $dataSite = getDataSite();
    $exp = explode('|', $dataSite);
    $btpc = '';

    if ($exp[9] > 0){
      $btpc = '(+ Propina '.$exp[9].'%)';
    }

    $db = new DataBase;
    $where = array('id' => $account);
    $selectSQL = $db->select('cuentas', '*', $where);
    $html = '';
    foreach ($selectSQL as $key){
       if (is_null($key['nota']) || empty($key['nota'])){
         $nota = '';
       }else{
         $nota = '- <small><i>'.base64_decode($key['nota']).'</i></small>';
       }

       if ($exp[17] == 1){
        $fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
         $prcnw = str_replace('$', '', $fmt->formatCurrency(getDataAccActive($key['insumos']), "CLP"));
       }else{
         $prcnw = getDataAccActive($key['insumos']);
       }

       $html = '
       <div class="accountZero" role="alert">
        <span class="float-start mt-2"><h3><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mood-smile"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 10l.01 0" /><path d="M15 10l.01 0" /><path d="M9.5 15a3.5 3.5 0 0 0 5 0" /></svg> '.$key['nombre'].' - $'.$prcnw.' '.$exp[10].'  <small>'.$btpc.'</small>'.$nota.'</h3></span>
        <div class="btn-group float-end" role="group" aria-label="Basic example">
          <a href="'.URLSITE.'cuenta/'.$key['hash'].'" type="button" class="btn btn-outline-success">Ver Cuenta</a>
          <a type="button" class="btn btn-outline-warning printaccount" data-cuenta="'.$key['hash'].'">Imrpimir</a>
        </div>
       </div>';
    }

    return $html;


}

function DisplayMesasZero(){

     $db = new DataBase;
     $where = array('numesa' => 0);
     $selectSQL = $db->select('mesas', '*', $where);
     $html = '';
     foreach ($selectSQL as $key){
       $html .= DisplayAccountsZero($key['cuentas']);
     }

     return $html;

}

function getNumberOfMesa($id){
     $db = new DataBase;
     $where = array('id' => $id);
     $selectSQL = $db->select('mesas', '*', $where);
     foreach ($selectSQL as $key){
       $numesa = $key['numesa'];
     }
     return $numesa;
}


function setStats($string){

  // Sacamos el String
  // 2|1||1#3|1||1#1|1||1#
  $frstexp = explode('#', substr($string, 0, -1));
  // Contamos el string de la cuenta.
  $ctnexp = count($frstexp);

  // Conexion a base de datos
  $db = new DataBase;

  // el For
  for ($i=0; $i < $ctnexp; $i++){ 

      // Sacamos los insumos uno a uno
      $expIns = explode('|', $frstexp[$i]);

      // Necesitamos el ID
      $idinsu = $expIns[0];
      // Necesitamos la cantidad
      $insucnt = $expIns[1];

      // Primero hacemos un select
      // Sacamos el Where
      $where = array('insumo' => $idinsu,'mes' => date('m'));
      $rst = $db->select('stats', '*', $where);
      if (empty($rst)){
        // Si no hay un registro lo agregamos con el ID, el Mes y su Cantidad
        $addArrayData = array('insumo' => $idinsu, 'mes' => date('m'), 'cantidad' => $insucnt);
        $db->add('stats', $addArrayData);
      }else{
        foreach ($rst as $key){
            $idreg = $key['id'];
        }

        // Si existe un registro entonces solamente actualizamos el registro
        // agregamos la cantidad que se vendio a la que ya esta registrada
        $SQL = 'UPDATE stats SET cantidad = cantidad + '.$insucnt.' WHERE id = '.$idreg.'';
        $db->QueryLong($SQL);

      }

  }

}