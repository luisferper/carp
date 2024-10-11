<?php

if ( $_SERVER['REQUEST_METHOD']=='GET' && realpath(__FILE__) == realpath( $_SERVER['SCRIPT_FILENAME'])){
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );
}

// Para el XSS
$xss = new XSS();

// Base de Datos
$db = new DataBase;

// Por cualquier cosa
if (isset($_POST['p'])) {
   $p = $_POST['p'];
   if (empty($p)){
     exit();
   }
}

// Saber si el proceso es numerico
if (!is_numeric($p)){
  exit();
}

if ($p == 1){
    $usuario = $xss->XSString($_POST['upu']);
    $password = $xss->XSString($_POST['upp']);
    if (empty($usuario) || ctype_space($usuario) || is_null($usuario) || empty($password) || ctype_space($password) || is_null($password)){
      echo 1;
      return;
    }
    $logon = LoginFunc($usuario, $password);
    if ($logon == FALSE){
      echo 2;
      return;
    }else{
      echo 3;
    }
}

/* Mesas */
/* Abriendo una mesa */
if ($p == 2) {
  
   if (isLogin() == FALSE){
     exit();
   }

   $rnd = randomString(10);
   $hash = sha1($rnd.date('d-m-Y').$_SESSION['idSession']);
   
   $insData = array(
    'numesa' => $_POST['mesa'],
    'fechahora' => date('Y-m-d h:i:s'),
    'encargado' => $_SESSION['idSession'],
    'hash' => $hash,
    'status' => 1,
   );
   $selectSQL = $db->add('mesas', $insData);

   echo $hash;
   
}

// Abriendo una cuenta a la mesa
if ($p == 3){

   if (isLogin() == FALSE){
     exit();
   }

   $nombre = $xss->XSString($_POST['accountnm']);
   $mesa = $xss->XSString($_POST['mesa']);
   if(
    empty($nombre) || ctype_space($nombre) || is_null($nombre) ||
    empty($mesa) || ctype_space($mesa) || is_null($mesa)
   ){
      echo 1;
      return;
   }

   $rnd = randomString(10);
   $hash = sha1($rnd.date('d-m-Y').$_SESSION['idSession']);
   $insData = array(
    'nombre' => $nombre,
    'idmesa' => $mesa,
    'fechahora' => date('Y-m-d h:i:s'),
    'hash' => $hash,
   );
   $updaSQL = $db->add('cuentas', $insData);
   $where = array('id' => $mesa);
   
   $sqlwhere = array('id' => $mesa);
   $rst = $db->select('mesas', '*', $sqlwhere);
   foreach ($rst as $key){
      if (is_null($key['cuentas'])){

        $upda2 = array('cuentas' => $updaSQL.',');
        $db->update('mesas', $where, $upda2);

      }else{

        $dataS = $key['cuentas'].$updaSQL.',';
        $upda2 = array('cuentas' => $dataS);
        $db->update('mesas', $where, $upda2);

      }
        
   }
   
   echo $hash;

}

// Abriendo una cuenta a la mesa
if ($p == 4){

   if (isLogin() == FALSE){exit();}

   $string = $_POST['string'];
   $mesa = $xss->XSString($_POST['savetable']);
   $cuenta = $xss->XSString($_POST['cuenta']);

   if(empty($mesa) || ctype_space($mesa) || is_null($mesa) || empty($cuenta) || ctype_space($cuenta) || is_null($cuenta)){
      echo 1;
      return;
   }

   if (empty($string)){
      $dt = null;
   }else{
      $dt = $string;
   }

   $where = array(
    'id' => $mesa,
    'idmesa' => $cuenta
   );

   $data = array(
    'insumos' => $dt
   );

   $updatedSQL = $db->update('cuentas', $where, $data);

}

// Cancelar Cuenta Single
if ($p == 5){

   if (isLogin() == FALSE){exit();}

   // Sacamos primero lo que vamos a usar
   // id de la mesa
   $cuenta = $_POST['cuenta'];
   $sqlwhere = array('id' => $cuenta);
   $rst = $db->select('cuentas', '*', $sqlwhere);
   foreach ($rst as $key){
        $idmesa = $key['idmesa'];
   }

   // Sacamos datos de la mesa
   $mesarst = $db->select('mesas', '*', array('id' => $idmesa));
   foreach ($mesarst as $key){
      $encardado = $key['encargado'];
      $cuentas = $key['cuentas'];
      $hashmesa = $key['hash'];
   }

   // Comprobar que el encargado
   // es el que elimina la cuenta
   if ($encardado <> $_SESSION['idSession']){echo 1;return;}

   // Eliminamos la cuenta de la mesa.
   $chacc = str_replace($cuenta.',', '', $cuentas);

   if (is_null($chacc) || empty($chacc)){
     $chacc = NULL;
   }

   // Numero de la mesa
   $whomesa = getNumberOfMesa($idmesa);

   // Actualizamos las cuentas de la mesa.
   $db->update('mesas', array('id' => $idmesa), array('cuentas' => $chacc));

   // Finalmente eliminamos la cuenta por completo.
   $db->delete('cuentas', array('id' => $cuenta));

   // Imprimimos el hash de la mesa para redireccionar.
   if ($whomesa == 0){
     // Eliminamos la mesa ZERO
     $db->delete('mesas', array('id' => $idmesa));
     echo URLSITE.'inicio';
   }else{
     echo URLSITE.'mesa/'.$hashmesa;
   }
   

}

// Pagar la cuenta (no la mesa)
if ($p == 6){

   // Tiene que estar logeado
   if (isLogin() == FALSE){exit();}

   // Tomamos las variables
   $id = $xss->XSString($_POST['id']);
   $pagocon = $xss->XSString($_POST['pagocon']);
   $type = $xss->XSString($_POST['type']);

   if ($type <> 2){
       if (empty($pagocon)){
          echo 1;
          return;
       }
   }

   $pagocon = str_replace('.', '', $pagocon);

   // Sacamos los datos de la cuenta
   $rst = $db->select('cuentas', '*', array('id' => $id));
   foreach ($rst as $key){
      $idmesa = $key['idmesa'];
      $insmos = $key['insumos'];
      $nombre = $key['nombre'];
      $newhash = sha1($key['hash'].$id.$pagocon.$type);
   }

   $rstMesa = $db->select('mesas', '*', array('id' => $idmesa));
   foreach ($rstMesa as $keyT){
     $mesa = $keyT['numesa'];
     $cuentas = $keyT['cuentas'];
   }

   // Tomamos los datos del sitio
   $dataSite = getDataSite();
   $exp = explode('|', $dataSite);

   if ($exp[9] > 0){
     $pc = getDataAccActive2($insmos);
     $sm = $pc * $exp[9] / 100;
     $total = $pc + $sm;
   }else{
     $total = getDataAccActive($insmos);
   }

   if ($type == 2){
     $minus = 0;
     $pagocon = $total;
   }else{
     $minus = $pagocon - $total;
   }


   // Insertamos los datos de la cuenta en ventas
   $ventasArray = array(
      'idmesa'  => $idmesa,
      'mesa'  => $mesa,
      'cuenta'  => $nombre,
      'usuario'  => $_SESSION['idSession'],
      'articulos'  => $insmos,
      'ano'  => date('Y'),
      'mes'  => date('m'),
      'dia'  => date('d'),
      'hora'  => date('h:i:s'),
      'total'  => $total,
      'pagocon'  => $pagocon,
      'tipopago'  => $type,
      'cambio'  => $minus,
      'hash'  => $newhash,
      'propina' => $exp[9]
   );

   $selectSQL = $db->add('ventas', $ventasArray);

   // Agregamos los datos a las estadisticas
   setStats($insmos);
   
   // Eliminamos la cuenta de la mesa.
   $chacc = str_replace($id.',', '', $cuentas);
   if (is_null($chacc) || empty($chacc)){
     $chacc = NULL;
   }
   // Actualizamos las cuentas de la mesa.
   $db->update('mesas', array('id' => $idmesa), array('cuentas' => $chacc));

   // Finalmente eliminamos la cuenta por completo.
   $db->delete('cuentas', array('id' => $id));

   // Revisamos si la mesa ya no tiene cuentas activas
   $rstMesa = $db->select('mesas', '*', array('id' => $idmesa));
   foreach ($rstMesa as $keyT){
     $cuentas = $keyT['cuentas'];
   }
   
   // Si ya no hay cuentas cambiamos su status
   if (empty($cuentas) || is_null($cuentas)){
      $db->update('mesas', array('id' => $idmesa, 'encargado' => $_SESSION['idSession']), array('status' => 2));
   }

   echo $newhash;

}


// Pagar la mesa
if ($p == 7){

  // Tiene que estar logeado
   if (isLogin() == FALSE){exit();}

   // Tomamos las variables
   $id = $xss->XSString($_POST['id']);
   $pagocon = $xss->XSString($_POST['pagocon']);
   $type = $xss->XSString($_POST['type']);

   if ($type <> 2){
       if (empty($pagocon)){
          echo 1;
          return;
       }
   }

   // Sacamos los datos de la mesa
   $rstMesa = $db->select('mesas', '*', array('id' => $id));
   foreach ($rstMesa as $keyT){
     $mesa = $keyT['numesa'];
     $cuentas = $keyT['cuentas'];
   }

   // Tomamos los datos del sitio
   $dataSite = getDataSite();
   $expDS = explode('|', $dataSite);

   if ($expDS[9] > 0){
     
     $sm = getTotalOfTable2($cuentas);

     $smop = $sm * $expDS[9] / 100;
     $total = $sm + $smop;
     
     if ($type == 2){
       $minus = 0;
       $pagocon = $total;
     }else{
       $minus = $pagocon - $total;
     }


   }else{

     $total = getTotalOfTable($cuentas);
     if ($type == 2){
       $minus = 0;
       $pagocon = $total;
     }else{

       $minus = $pagocon - $total;
     }

   }


   $dtninsrtn = '';
   $exp = explode(',', substr($cuentas, 0, -1));
   $cnt = count($exp);
   for ($i=0; $i < $cnt; $i++){ 
     $dtninsrtn .= rtnInsumos($exp[$i]);
   }

   // Agregamos los datos a las estadisticas
   setStats($dtninsrtn);

   $newhash = sha1($id.$mesa.$_SESSION['idSession'].$total);

   // Insertamos los datos de la cuenta en ventas
   $ventasArray = array(
      'idmesa'  => $id,
      'mesa'  => $mesa,
      'cuenta'  => 'Mesa Completa - Cuentas:'.$cnt,
      'usuario'  => $_SESSION['idSession'],
      'articulos'  => $dtninsrtn,
      'ano'  => date('Y'),
      'mes'  => date('m'),
      'dia'  => date('d'),
      'hora'  => date('h:i:s'),
      'total'  => $total,
      'pagocon'  => $pagocon,
      'tipopago'  => $type,
      'cambio'  => $minus,
      'hash'  => $newhash,
      'propina' => $expDS[9]
   );

   $selectSQL = $db->add('ventas', $ventasArray);

   // Finalmente eliminamos las cuentas por completo.
   for ($i=0; $i < $cnt; $i++){ 
     $db->delete('cuentas', array('id' => $exp[$i]));
   }

   // Actualizamos la mesa y todo listo.
   $db->update('mesas', array('id' => $id, 'encargado' => $_SESSION['idSession']), array('status' => 2));

   echo $newhash;

}

if ($p == 8){

  // Tiene que estar logeado
  if (isLogin() == FALSE){exit();}

   // Tomamos las variables
   $id = $xss->XSString($_POST['id']);

  // Sacamos las cuentas de la mesa
  $rstMesa = $db->select('mesas', '*', array('id' => $id));
  foreach ($rstMesa as $keyT){
    $cuentas = $keyT['cuentas'];
  }

  // Si no hay cuentas activas pues solo se elimina la cuenta
  if (empty($cuentas) || is_null($cuentas)){
    $db->delete('mesas', array('id' => $id));
  }

  // Si hay cuentas activas las separamos
  // sacamos sus id y luego eliminamos la cuenta
  $exp = explode(',', substr($cuentas, 0, -1));
  $cnt = count($exp);
  for ($i=0; $i < $cnt; $i++){ 
    $db->delete('cuentas', array('id' => $exp[$i]));
  }

  // Eliminamos la mesa y finalizamos
  $db->delete('mesas', array('id' => $id));


}

if ($p == 9){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  // Tomamos las variables
  $nombre = $xss->XSString($_POST['nombre']);
  $descripcion = $xss->XSString($_POST['descripcion']);
  $codigo = $xss->XSString($_POST['codigo']);
  $precio = $xss->XSString($_POST['precio']);
  $categoria = $xss->XSString($_POST['categoria']);

  if(
    empty($nombre) || ctype_space($nombre) || is_null($nombre) ||
    empty($descripcion) || ctype_space($descripcion) || is_null($descripcion) || 
    empty($codigo) || ctype_space($codigo) || is_null($codigo) || 
    empty($precio) || ctype_space($precio) || is_null($precio) || 
    empty($categoria) || ctype_space($categoria) || is_null($categoria)
  ){
      echo 1;
      return;
  }

  $arrayName = array(
    'nombre' => $nombre, 
    'descripcion' => $descripcion, 
    'codigo' => $codigo, 
    'precio' => $precio, 
    'categoria' => $categoria,
    'status' => 1
  );

  $selectSQL = $db->add('insumos', $arrayName);

}

if ($p == 10){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  $id = $xss->XSString($_POST['id']);
  $sqlwhere = array('id' => $id);
  $rst = $db->select('insumos', '*', $sqlwhere);
  foreach ($rst as $key){
    echo '
     <div class="modal fade" id="editInsumoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
       <div class="modal-dialog">
         <div class="modal-content">
           <div class="modal-header">
             <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Insumo</h1>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
               <form id="editInsuForm">
                 <input type="hidden" name="p" value="11">
                 <input type="hidden" name="id" value="'.$key['id'].'">
                 <label>Nombre:</label>
                 <input type="text" class="form-control" name="nombre" value="'.$key['nombre'].'">
                 <label class="mt-2">Descripcion:</label>
                 <input type="text" class="form-control" name="descripcion" value="'.$key['descripcion'].'">
                 <label class="mt-2">Codigo:</label>
                 <input type="text" class="form-control" name="codigo" value="'.$key['codigo'].'">
                 <label class="mt-2">Precio:</label>
                 <input type="text" class="form-control" name="precio" value="'.$key['precio'].'">
                 <label class="mt-2">Categoria:</label>
                 <select class="form-control" name="categoria">
                   '.rntCatePer($key['categoria']).rntCatePer('').'
                 </select>
               </form>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-primary" onclick="InsumoUpdate();">Guardar Cambios</button>
             <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
           </div>
         </div>
       </div>
     </div>
    ';
  }
}

if ($p == 11){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  // Tomamos las variables
  $id = $xss->XSString($_POST['id']);
  $nombre = $xss->XSString($_POST['nombre']);
  $descripcion = $xss->XSString($_POST['descripcion']);
  $codigo = $xss->XSString($_POST['codigo']);
  $precio = $xss->XSString($_POST['precio']);
  $categoria = $xss->XSString($_POST['categoria']);

  if(
    empty($id) || ctype_space($id) || is_null($id) ||
    empty($nombre) || ctype_space($nombre) || is_null($nombre) ||
    empty($descripcion) || ctype_space($descripcion) || is_null($descripcion) || 
    empty($codigo) || ctype_space($codigo) || is_null($codigo) || 
    empty($precio) || ctype_space($precio) || is_null($precio) || 
    empty($categoria) || ctype_space($categoria) || is_null($categoria)
  ){
      echo 1;
      return;
  }

  $arrayName = array(
    'nombre' => $nombre, 
    'descripcion' => $descripcion, 
    'codigo' => $codigo, 
    'precio' => $precio, 
    'categoria' => $categoria,
    'status' => 1
  );


  // actualizamos el insumo 
  $db->update('insumos', array('id' => $id), $arrayName);


}

if ($p == 12){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}
  
  $id = $xss->XSString($_POST['id']);
  // Eliminamos y finalizamos
  $db->delete('insumos', array('id' => $id));

}

if ($p == 13){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  // Tomamos las variables
  $nombre = $xss->XSString($_POST['nombre']);
  $comanda = $xss->XSString($_POST['comanda']);


  if(
    empty($nombre) || ctype_space($nombre) || is_null($nombre) ||
    empty($comanda) || ctype_space($comanda) || is_null($comanda)
  ){
      echo 1;
      return;
  }

  $arrayName = array(
    'nombre' => $nombre,
    'comanda' => $comanda
  );

  $selectSQL = $db->add('categoria', $arrayName);

}

if ($p == 14){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  $id = $xss->XSString($_POST['id']);
  $sqlwhere = array('id' => $id);
  $rst = $db->select('categoria', '*', $sqlwhere);
  foreach ($rst as $key){

    if ($key['comanda'] == 1){
      $comanda = '
        <label class="mt-1">Comanda</label>
        <select class="form-control" name="comanda">
          <option value="1">No</option>
          <option value="2">Si</option>
        </select>
      ';
    }else{
      $comanda = '
        <label class="mt-1">Comanda</label>
        <select class="form-control" name="comanda">
          <option value="2">Si</option>
          <option value="1">No</option>
        </select>
      ';
    }

    echo '
     <div class="modal fade" id="editCateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
       <div class="modal-dialog">
         <div class="modal-content">
           <div class="modal-header">
             <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Insumo</h1>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
               <form id="editCateForm">
                 <input type="hidden" name="p" value="15">
                 <input type="hidden" name="id" value="'.$key['id'].'">
                 <label>Nombre:</label>
                 <input type="text" class="form-control" name="nombre" value="'.$key['nombre'].'">
                 <p></p>
                 '.$comanda.'
               </form>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-primary" onclick="CateUpdate();">Guardar Cambios</button>
             <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
           </div>
         </div>
       </div>
     </div>
    ';
  }

}


if ($p == 15){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  // Tomamos las variables
  $id = $xss->XSString($_POST['id']);
  $nombre = $xss->XSString($_POST['nombre']);
  $comanda = $xss->XSString($_POST['comanda']);

  if(
    empty($id) || ctype_space($id) || is_null($id) ||
    empty($nombre) || ctype_space($nombre) || is_null($nombre) ||
    empty($comanda) || ctype_space($comanda) || is_null($comanda)
  ){
      echo 1;
      return;
  }

  $arrayName = array(
    'nombre' => $nombre, 
    'comanda' => $comanda
  );


  // actualizamos el insumo 
  $db->update('categoria', array('id' => $id), $arrayName);


}


if ($p == 16){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}
  
  $id = $xss->XSString($_POST['id']);
  // Eliminamos y finalizamos
  $db->delete('categoria', array('id' => $id));

}


if ($p == 17){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  $id = $xss->XSString($_POST['id']);
  $sqlwhere = array('id' => $id);
  $rst = $db->select('usuarios', '*', $sqlwhere);
  foreach ($rst as $key){
    
      if ($key['status'] == 1){
        $sts = '<option value="1">Inactivo</option><option value="2">Activo</option>';
      }else{
        $sts = '<option value="2">Activo</option><option value="1">Inactivo</option>';
      }
      if ($key['usertype'] == 1){
        $rnk = '<option value="1">Usuario</option><option value="2">Administrador</option>';
      }else{
        $rnk = '<option value="2">Administrador</option><option value="1">Usuario</option>';
      } 

    echo '
     <div class="modal fade" id="editUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
       <div class="modal-dialog">
         <div class="modal-content">
           <div class="modal-header">
             <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Insumo</h1>
             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
              <form id="editUserForm">
                 <input type="hidden" name="p" value="20">
                 <input type="hidden" name="id" value="'.$key['id'].'">
                 <label>Nombre:</label>
                 <input type="text" class="form-control" name="nusr" value="'.$key['nombre'].'">
                 <label class="mt-2">Usuario:</label>
                 <input type="text" class="form-control" name="uusr" value="'.$key['usuario'].'">
                 <label class="mt-2">Email:</label>
                 <input type="text" class="form-control" name="emlusr" value="'.$key['usermail'].'">
                 <label class="mt-2">Contrase&ntilde;a</label>
                 <input type="text" class="form-control" name="pusr">
                 <label class="mt-2">Status:</label>
                 <select class="form-control" name="status">
                   '.$sts.'
                 </select>
                 <label class="mt-2">Rango:</label>
                 <select class="form-control" name="rango">
                   '.$rnk.'
                 </select>
               </form>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-primary" onclick="userUpdate();">Guardar Cambios</button>
             <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
           </div>
         </div>
       </div>
     </div>
    ';
  }
}

if ($p == 18){


  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  // Tomamos las variables
  $nusr = $xss->XSString($_POST['nusr']);
  $uusr = $xss->XSString($_POST['uusr']);
  $pusr = $xss->XSString($_POST['pusr']);
  $emlusr = $xss->XSString($_POST['emlusr']);
  $status = $xss->XSString($_POST['status']);
  $rango = $xss->XSString($_POST['rango']);

  if(
    empty($nusr) || ctype_space($nusr) || is_null($nusr) ||
    empty($uusr) || ctype_space($uusr) || is_null($uusr) || 
    empty($pusr) || ctype_space($pusr) || is_null($pusr) || 
    empty($emlusr) || ctype_space($emlusr) || is_null($emlusr) || 
    empty($status) || ctype_space($status) || is_null($status) || 
    empty($rango) || ctype_space($rango) || is_null($rango)
  ){
      echo 1;
      return;
  }

  // Hash password
  $hash = password_hash($pusr.SALT, PASSWORD_DEFAULT);

  if (checkUser($uusr) == 2){
      echo 2;
      return;
  }

  if (checkMail($emlusr) == 2){
      echo 3;
      return;
  }  

  $arrayName = array(
    'nombre' => $nusr, 
    'usuario' => $uusr, 
    'userpass' => $hash, 
    'usermail' => $emlusr, 
    'status' => $status, 
    'usertype' => $rango
  );

  $selectSQL = $db->add('usuarios', $arrayName);


}

if ($p == 19){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}
  
  $id = $xss->XSString($_POST['id']);
  // Eliminamos y finalizamos
  $db->delete('usuarios', array('id' => $id));

}

if ($p == 20){
   
   return;

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  // Tomamos las variables
  $id = $xss->XSString($_POST['id']);
  $nusr = $xss->XSString($_POST['nusr']);
  $uusr = $xss->XSString($_POST['uusr']);
  $emlusr = $xss->XSString($_POST['emlusr']);
  $pusr = $xss->XSString($_POST['pusr']);
  $status = $xss->XSString($_POST['status']);
  $rango = $xss->XSString($_POST['rango']);

  if(
    empty($id) || ctype_space($id) || is_null($id) ||
    empty($nusr) || ctype_space($nusr) || is_null($nusr) ||
    empty($uusr) || ctype_space($uusr) || is_null($uusr) || 
    empty($emlusr) || ctype_space($emlusr) || is_null($emlusr) || 
    empty($status) || ctype_space($status) || is_null($status) || 
    empty($rango) || ctype_space($rango) || is_null($rango)
  ){
      echo 1;
      return;
  }

  if (empty($pusr)){
    $arrayName = array('nombre' => $nusr, 'usuario' => $uusr,'usermail' =>  $emlusr,'status' => $status, 'usertype' => $rango);
  }else{
    // Hash password
    $hash = password_hash($pusr.SALT, PASSWORD_DEFAULT);
    $arrayName = array('nombre' => $nusr, 'usuario' => $uusr, 'userpass' => $hash, 'usermail' =>  $emlusr, 'status' => $status, 'usertype' => $rango);
  }

  // actualizamos el insumo 
  $db->update('usuarios', array('id' => $id), $arrayName);

}

if ($p == 21){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  // Tomamos las variables
  $titlesite = $xss->XSString($_POST['titlesite']);
  $descsite = $xss->XSString($_POST['descsite']);
  $direccion = $xss->XSString($_POST['direccion']);
  $telefono = $xss->XSString($_POST['telefono']);
  $cateco = 1;
  $mesas = $xss->XSString($_POST['mesas']);

  if(
    empty($titlesite) || ctype_space($titlesite) || is_null($titlesite) ||
    empty($descsite) || ctype_space($descsite) || is_null($descsite) ||
    empty($direccion) || ctype_space($direccion) || is_null($direccion) || 
    empty($telefono) || ctype_space($telefono) || is_null($telefono) || 
    empty($cateco) || ctype_space($cateco) || is_null($cateco) ||
    empty($mesas) || ctype_space($mesas) || is_null($mesas)
  ){
      echo 1;
      return;
  }

  if ($mesas < 1){
    $mesas = 10;
  }else if ($mesas > 50){
    $mesas = 50;
  }else if (!is_numeric($mesas)) {
    $mesas = 10;
  }

  $arrayName = array(
    'titlesite' => $titlesite,
    'descsite' => $descsite,
    'direccion' => $direccion,
    'telefono' => $telefono,
    'cateco' => $cateco,
    'mesas' =>  $mesas
  );

  // actualizamos 
  $db->update('configuration', array('id' => 1), $arrayName);


}

if ($p == 22){


  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  // Tomamos las variables
  $color = $xss->XSString($_POST['color']);
  $estilo = $xss->XSString($_POST['estilo']);

  if(
    empty($color) || ctype_space($color) || is_null($color) ||
    empty($estilo) || ctype_space($estilo) || is_null($estilo) 
  ){
      echo 1;
      return;
  }

  $arrayName = array(
    'color' => $color,
    'estilo' => $estilo
  );

  // actualizamos 
  $db->update('configuration', array('id' => 1), $arrayName);

}



if ($p == 23){

// Tiene que estar logeado
if (isAdmin() == FALSE){exit();}

// Tomamos el archivo 
$image = $_FILES['imglogo'];

// Datos importantes del archivo
$nombre = $image['name'];
$ext = pathinfo($image['name'], PATHINFO_EXTENSION);

// Si la imagen no es png para atras
if ($ext != 'png'){
  //echo 1;
  //return;
}

// Salimos si la imagen tiene 0 bytes
if (filesize($image["tmp_name"]) <= 0) {
  //echo 2;
  //return;
}

// Numero unico para la imagen
$image_name = bin2hex(random_bytes(16)).'.'.$ext;
$imgfolder = str_replace('app', 'images/', __DIR__);

move_uploaded_file(
    // Temp image location
    $image["tmp_name"],
    // New image location
    $imgfolder.$image_name
);


$arrayName = array('logo' => $image_name);
// actualizamos 
$db->update('configuration', array('id' => 1), $arrayName);


}

if ($p == 24){

// Tiene que estar logeado
if (isAdmin() == FALSE){exit();}

// Tomamos los datos del sitio
$dataSite = getDataSite();
$exp = explode('|', $dataSite);

$logo = $exp[2];
$namedefault = 'default.png';

// Folder de las imagenes
$imgfolder = str_replace('app', 'images/', __DIR__);

// Eliminamos la anterior imagen
unlink($imgfolder.$logo);

$arrayName = array('logo' => $namedefault);
// actualizamos 
$db->update('configuration', array('id' => 1), $arrayName);


}



if ($p == 25){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  if ($porcentaje > 25){
      echo 2;
      return;
  }

  $arrayName = array('porcentaje' => $_POST['porcentaje']);
  // actualizamos 
  $db->update('configuration', array('id' => 1), $arrayName);

}

if ($p == 26){

  // Tiene que estar logeado
  if (isAdmin() == FALSE){exit();}

  // Tomamos las variables
  $divisa = $xss->XSString($_POST['divisa']);
  if(empty($divisa) || ctype_space($divisa) || is_null($divisa)){
      echo 1;
      return;
  }

  $arrayName = array('divisa' => $divisa);
  // actualizamos 
  $db->update('configuration', array('id' => 1), $arrayName);

}


/* INFORMES */
if ($p == 27){

  if (isLogin() == FALSE){
    exit();
  }

  session_start();
  $_SESSION['idmesero'] = $_POST['mesero'];

}

if ($p == 28){

  if (isLogin() == FALSE){
    exit();
  }

  session_start();
  $_SESSION['numesa'] = $_POST['mesa'];

}

if ($p == 29){

  if (isLogin() == FALSE){
    exit();
  }

  $fecha = $_POST['fecha'];
  $exp = explode('/', $fecha);

  session_start();
  $_SESSION['day'] = $exp[0];
  $_SESSION['month'] = $exp[1];
  $_SESSION['year'] = $exp[2];

}

if ($p == 30){
  if (isLogin() == FALSE){
    exit();
  }
  $ticket = $_POST['ticket'];
  $sqlwhere = array('id' => $ticket);
  $rst = $db->select('ventas', '*', $sqlwhere);
  if (empty($rst)){
    echo 1;
    return;
  }else{
    foreach ($rst as $key) {
      echo $key['hash'];
    }
  }
}

if ($p == 31){
  if (isLogin() == FALSE){
    exit();
  }
  $mesa = $_POST['mesa'];
  $idmesa = $_POST['idmesa'];

  // Sacamos la mesa
  $sqlwhere = array('id' => $idmesa);
  $rst = $db->select('mesas', '*', $sqlwhere);
  foreach ($rst as $key){
     
  // Cambiamos la mesa
  $db->update('mesas', array('id' => $idmesa), array('numesa' => $mesa));

  }
}

if ($p == 32){
    $usrmail = $xss->XSString($_POST['usrmail']);
    if (empty($usrmail) || ctype_space($usrmail) || is_null($usrmail)){
      echo 1;
      return;
    }
    RecoveryAccount($usrmail);
}

if ($p == 33){
  
// Tiene que estar logeado
if (isAdmin() == FALSE){exit();}

// Tomamos el archivo 
$image = $_FILES['insuimport'];

// Datos importantes del archivo
$nombre = $image['name'];
$ext = pathinfo($image['name'], PATHINFO_EXTENSION);

// Si la imagen no es png para atras
if ($ext != 'txt'){
  //echo 1;
  //return;
}

// Salimos si la imagen tiene 0 bytes
if (filesize($image["tmp_name"]) <= 0) {
  //echo 2;
  //return;
}

// Numero unico para la imagen
$image_name = bin2hex(random_bytes(16)).'.'.$ext;
$imgfolder = str_replace('app', 'images/', __DIR__);

move_uploaded_file(
    // Temp image location
    $image["tmp_name"],
    // New image location
    $imgfolder.$image_name
);

$namefile = URLSITE.'images/'.$image_name;
$file_handle = fopen($namefile, 'r');
function get_all_lines($file_handle) { 
    while (!feof($file_handle)) {
        yield fgets($file_handle);
    }
}

$db = new DataBase;

foreach (get_all_lines($file_handle) as $line){
    $exp = explode('|', $line);
    $arrayDT = array('nombre' => $exp[0],'descripcion' => $exp[1],'categoria' => 1,'codigo' => $exp[2],'precio' => $exp[3],'status' =>  1);
    $db->add('insumos', $arrayDT);
}

fclose($file_handle);

$filenamesystem = str_replace('app', 'images/'.$image_name, __DIR__);
unlink($filenamesystem);

}


if ($p == 34){
   // Tiene que estar logeado
   if (isAdmin() == FALSE){exit();}
   restoreCafeSys();
}


if ($p == 35){
   // Tiene que estar logeado
   if (isAdmin() == FALSE){exit();}


   // Tomamos las variables
   $smtpserver = $xss->XSString($_POST['smtp']);
   $smtpuser = $xss->XSString($_POST['usersmtp']);
   $smtppass = $xss->XSString($_POST['passmtp']);
   $stmport = $xss->XSString($_POST['smtport']);
   $smtpstatus = $xss->XSString($_POST['smtpactive']);

   if(
    empty($smtpserver) || ctype_space($smtpserver) || is_null($smtpserver) ||
    empty($smtpuser) || ctype_space($smtpuser) || is_null($smtpuser) ||
    empty($stmport) || ctype_space($stmport) || is_null($stmport) ||
    empty($smtppass) || ctype_space($smtppass) || is_null($smtppass) ||
    empty($smtpstatus) || ctype_space($smtpstatus) || is_null($smtpstatus)
    ){
      echo 1;
      return;
   }

   smtpConfiguration($smtpserver,$smtpuser,$smtppass,$stmport,$smtpstatus);

}


if ($p == 36){

   if (isLogin() == FALSE){exit();}

   $nombre = $xss->XSString($_POST['nombre']);
   if(empty($nombre) || ctype_space($nombre) || is_null($nombre)){
      echo 1;
      return;
   }

   $db = new DataBase;

   // Creamos la Mesa Zero
   $rnd = randomString(10);
   $hash = sha1($rnd.date('d-m-Y').$_SESSION['idSession']);
   
   $insData = array(
    'numesa' => '0',
    'fechahora' => date('Y-m-d h:i:s'),
    'encargado' => $_SESSION['idSession'],
    'hash' => $hash,
    'status' => 1,
   );
   $selectSQL = $db->add('mesas', $insData);

   // Agregando la cuenta a la mesa ZERO
   $rnd = randomString(15);
   $hashaccount = sha1($rnd.date('d-m-Y').$_SESSION['idSession']);
   $insData = array(
    'nombre' => $nombre,
    'idmesa' => $selectSQL,
    'fechahora' => date('Y-m-d h:i:s'),
    'hash' => $hashaccount,
   );
   $updaSQL = $db->add('cuentas', $insData);

  $upda2 = array('cuentas' => $updaSQL.',');
  $idaccmesa = array('id' => $selectSQL);
  $db->update('mesas', $idaccmesa, $upda2);

   echo $hashaccount;
   

}

if ($p == 37){

   if (isLogin() == FALSE){exit();}
   
   $id = $xss->XSString($_POST['id']);
   $nota = $xss->XSString($_POST['nota']);
   if (empty($nota) || is_null($nota) || ctype_space($nota)){
      $str = null;
   }else{
      $str = base64_encode($nota);
   }

   $db = new DataBase;
   $db->update('cuentas', array('id' => $id), array('nota' => $str));

}   


if ($p == 38){
   // Tiene que estar logeado
   if (isAdmin() == FALSE){exit();}

   $decimals = $xss->XSString($_POST['decimals']);
   $miles = $xss->XSString($_POST['miles']);

   $db = new DataBase;
   if ($decimals == 2){
     $db->QueryLong('ALTER TABLE `insumos` CHANGE `precio` `precio` INT NOT NULL;');
     $db->QueryLong('ALTER TABLE `ventas` CHANGE `total` `total` INT NOT NULL;');
     $db->QueryLong('ALTER TABLE `ventas` CHANGE `pagocon` `pagocon` INT NOT NULL;');
     $db->QueryLong('ALTER TABLE `ventas` CHANGE `cambio` `cambio` INT NOT NULL;');
   }else{
     $db->QueryLong('ALTER TABLE `insumos` CHANGE `precio` `precio` DECIMAL(15,2) NOT NULL;');
     $db->QueryLong('ALTER TABLE `ventas` CHANGE `total` `total` DECIMAL(15,2) NOT NULL;');
     $db->QueryLong('ALTER TABLE `ventas` CHANGE `pagocon` `pagocon` DECIMAL(15,2) NOT NULL;');
     $db->QueryLong('ALTER TABLE `ventas` CHANGE `cambio` `cambio` DECIMAL(15,2) NOT NULL;');
   }

   $db->update('configuration', array('id' => 1), array('decimals' => $decimals,'miles' => $miles));

}