<?php

function getTheHeader($entry){
   $dataSite = getDataSite();
   $exp = explode('|', $dataSite);
   $boxed = '';

   if (!empty($entry)){
     // Estilo
     if ($exp[7] == 1){
       $boxed = 'class="layout-boxed"';
     }else{
       $boxed = '';
     }
   }

   getTitlePage($entry);
   // Color
   if ($exp[8] == 1){
     $color = 'data-bs-theme="dark"';
   }else{
     $color = '';
   }

   echo'<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>'.TITLESITE.'</title><link href="'.URLSITE.''.ASSETSPATH.'/css/tabler.min.css" rel="stylesheet"><link href="'.URLSITE.''.ASSETSPATH.'/css/style.css" rel="stylesheet"></head><body '.$boxed.' '.$color.'><div class="page">';
}

function getTheFooter(){

   // Tomamos los datos del sitio
   $dataSite = getDataSite();
   $expDS = explode('|', $dataSite);

   echo '</div><div id="bigloader"><div class="progress"><div class="progress-bar progress-bar-indeterminate bg-green"></div></div></div><div id="appdata"></div><script src="'.URLSITE.''.ASSETSPATH.'/js/jquery.min.js"></script><script src="'.URLSITE.''.ASSETSPATH.'/js/jquery-ui.js"></script><script src="'.URLSITE.''.ASSETSPATH.'/js/jquery.dataTables.min.js"></script><script src="'.URLSITE.''.ASSETSPATH.'/js/dataTables.bootstrap5.min.js"></script><script src="'.URLSITE.''.ASSETSPATH.'/js/dataTables.responsive.min.js"></script><script src="'.URLSITE.''.ASSETSPATH.'/js/responsive.bootstrap5.min.js"></script><script src="'.URLSITE.''.ASSETSPATH.'/js/bootstrap.min.js"></script><script src="'.URLSITE.''.ASSETSPATH.'/js/system.js"></script><script>var baseurl = "'.URLSITE.'"; var divisa = "'.$expDS[10].'"; var prcpresent = '.$expDS[9].'; var dcmls = '.$expDS[16].'; var miles = '.$expDS[17].';</script></body></html>';
}

function errorMessage(){

echo '<div class="page page-center mt-5"><div class="container-tight py-4"><div class="empty"><div class="empty-img"></div><p class="empty-title">Hubo un error! lo que buscas ya no esta aqui.</p><p class="empty-subtitle text-secondary">Tal vez entraste en algun registro viejo o algo que ya no esta disponible, regresa a la pagina de inicio.</p><div class="empty-action"><a href="'.URLSITE.'inicio" class="btn btn-primary"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M5 12l14 0"></path><path d="M5 12l6 6"></path><path d="M5 12l6 -6"></path></svg> Volver al inicio</a></div></div></div></div>';
}


// removido en la version 3
// para borrarse despues.
function OrdenesModulo(){

    // Conexion a base de datos
    $db = new DataBase;
    $where = array('status' => 1);
    $rst = $db->select('mesas', '*', $where);
    if (empty($rst)) {
    }else{
      foreach ($rst as $key){
         $cuentas = substr($key['cuentas'], 0, -1);
         $numesa = $key['numesa'];
         $encargado = nameUser($key['encargado']);
      }
    }

    // Contar Cuentas
    $exp = explode(',', $cuentas);
    $expc = count($exp);
    
    // Sacamos el info de las cuentas
    // para ponerlo en la tabla
    $tn = '';
    for ($i=0; $i < $expc; $i++) { 
      $tn .= ParseOrdenesCocina($exp[$i], $numesa, $encargado);
    }

    
   echo'
     <div class="container mt-4">
     <table class="table">
        <thead>
           <tr>
              <th>Insumo</th>
              <th>Cantidad</th>
              <th>Detalles</th>
              <th>Mesero</th>
              <th>Mesa</th>
           </tr>
        </thead>
        <tbody>
        '.$tn.'
        </tbody>
     </table>
     <a onclick="refreshPage();" class="btn btn-lg btn-warning mt-3">Actualizar</a>
     </div>
   ';

}


function SingleOrdersIndex(){
echo'
<div class="container mt-3">
<div class="row">
<div class="col-12">
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
  Crear Orden
</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">&nbsp;</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <form id="newSingleOrderForm">
           <label>Nombre:</label>
           <input type="hidden" name="p" value="36">
           <input type="text" class="form-control" name="nombre">
         </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="createNewOrdenSingle();">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

'.DisplayMesasZero().'

   </div>
  </div>
</div>
';
}

function OrdersPagev3(){

     echo'<div class="container mt-3"><div class="row"><div class="col-md-12"><div class="accordion" id="accordionBS">';
    // Conexion a base de datos
    $db = new DataBase;
    $where = array('comanda' => 2);
    $rst = $db->select('categoria', '*', $where);
    foreach ($rst as $key){
       echo'

  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse'.$key['id'].'" aria-expanded="false">
        <span class="lgw"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-article"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M7 8h10" /><path d="M7 12h10" /><path d="M7 16h10" /></svg>'.$key['nombre'].'</span>
      </button>
    </h2>
    <div id="flush-collapse'.$key['id'].'" class="accordion-collapse collapse" data-bs-parent="#accordionBS">
      <div class="accordion-body">

       <table class="table">
         <thead>
            <tr>
              <th>Insumo</th>
              <th>Cantidad</th>
              <th>Detalles</th>
              <th>Mesero</th>
              <th>Mesa</th>
            </tr>
         </thead>
         <tbody>
             '.OrdenesModulo2($key['id']).'
         </tbody>
        </table>
      </div>
    </div>
  </div>

    ';
    }

echo'<a onclick="refreshPage();" class="btn btn-lg btn-warning mt-3 float-end">Actualizar</a></div></div></div></div>';

}

function ContentPanelOrders(){

    // Conexion a base de datos
    $db = new DataBase;
    $where = array('comanda' => 2);
    $rst = $db->select('categoria', '*', $where);
    echo'
<div class="accordion accordion-flush" id="accordionFlushExample">
  <div class="accordion-item">

    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
        Accordion Item #1
      </button>
    </h2>
    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first items accordion body.</div>
    </div>
  </div>

  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
        Accordion Item #2
      </button>
    </h2>
    <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body"></div>
    </div>
  </div>

  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
        Accordion Item #3
      </button>
    </h2>
    <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
      <div class="accordion-body"></div>
    </div>

  </div>
</div>
    ';


}

function OrdenesModulo2($cate){

    // Conexion a base de datos
    $db = new DataBase;
    $where = array('status' => 1);
    $rst = $db->select('mesas', '*', $where);
    if (empty($rst)) {
    }else{
      $theacc = '';
      foreach ($rst as $key){
         $theacc .= $key['cuentas'];
         $numesa = $key['numesa'];
         $encargado = nameUser($key['encargado']);


      }
    }

    // Contar Cuentas
    $exp = explode(',', $theacc);
    $expc = count($exp);
    
    // Sacamos el info de las cuentas
    // para ponerlo en la tabla
    $tn = '';
    for ($i=0; $i < $expc; $i++) { 
      $tn .= ParseOrdenesCocina($exp[$i], $numesa, $encargado, $cate);
    }
    
    return $tn;

}