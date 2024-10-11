<?php

function mesaSingle($hash){

    // Tomamos los datos del sitio
    $dataSite = getDataSite();
    $exp = explode('|', $dataSite);
    $prcp = '';

    if ($exp[9] > 0){
      $prcp = '(+ Propina '.$exp[9].'%)';
    }

    // Conexion a base de datos
    $db = new DataBase;

    if (isAdmin() == FALSE){
      $where = array('hash' => $hash, 'encargado' => $_SESSION['idSession'], 'status' => 1);
    }else{
      $where = array('hash' => $hash, 'status' => 1);
    }

    $rst = $db->select('mesas', '*', $where);
    if (empty($rst)){
    	errorMessage();
    }else{
    	foreach ($rst as $key){

            echo '
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h1 class="page-title float-start">
                  Mesa #'.$key['numesa'].'
                </h1>
                <button id="btnAsAcc" data-bs-toggle="modal" data-bs-target="#addAccount" class="btn btn-sm btn-warning float-end"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path><path d="M9 17v1a3 3 0 0 0 6 0v-1"></path></svg> Asignar Nueva Cuenta</button>
              </div>
            </div>
          </div>
        </div>

        <div class="container-xl">
        <div class="row">
        <div class="col-md-9">
        <div id="acballacc" class="card">
          <div class="card-body">
            <div class="row">
            '.checkAccounts($key['id']).'
            <input type="hidden" id="actualMesaNum" value="'.$key['id'].'">
            </div>
          </div>
        </div>
        </div>
        ';

        if ($exp[17] == 1){
          $fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
          $rslttable = $fmt->formatCurrency(getTotalOfTable($key['cuentas']), "CLP");
          $ttltable = str_replace('$', '', $rslttable);
        }else{
          $ttltable = getTotalOfTable($key['cuentas']);
        }

        echo '
        <div class="col-md-3">
           <div class="card">
             <div class="card-body">
                <h1 class="totalAccountSingle text-center">Total:</h1>
                <h1 class="totalAccountSingle text-center">$'.$ttltable.' <small>'.$exp[10].'</small></h1>
                <h2 class="text-center">'.$prcp.'</h2>
             </div>
           </div>
           <a class="btn btn-lg btn-block btn-outline-success mt-2" data-bs-toggle="modal" data-bs-target="#payModalMesa">Pagar Mesa</a>
           <a class="btn btn.lg btn-block btn-outline-info mt-2" data-bs-toggle="modal" data-bs-target="#chgModalMesa">Cambiar Mesa</a>
           <!--<a class="btn btn.lg btn-block btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#preModalBind">Juntar Cuentas</a>-->
           <a data-bs-toggle="modal" data-bs-target="#modal-danger" class="closeaccount btn btn-block btn-outline-danger mt-2" data-cuenta="'.$key['id'].'">Cancelar Mesa</a>
        </div>
        </div>
        </div>




<!-- Modal -->
<div class="modal fade" id="addAccount" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Nombre de la Cuenta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formAccount"> 
           <label>Nombre de la Cuenta</label>
           <input type="hidden" name="p" value="3">
           <input type="hidden" name="mesa" value="'.$key['id'].'">
           <input id="accountnm" type="text" class="form-control" name="accountnm" >
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary openaccont">Guardar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="payModalMesa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">&nbsp;</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
         <form id="PayForm">
           <input type="hidden" name="id" value="'.$key['id'].'">
           <input id="valueaccpay" value="'.getTotalOfTable($key['cuentas']).'" type="hidden"> 
           <p></p>
           <label>Pago Con:</label>
           <input type="number" id="canpayval" class="form-control mb-3" name="pagocon" placeholder="$0">
           <label>Tipo de Pago</label>
           <select id="methodPaySelect" name="type" class="form-control">
              <option value="1">Efectivo</option>
              <option value="2">Tarjeta</option>
           </select>
         </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="payTable();">Pagar Mesa</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

    <div class="modal modal-blur fade" id="modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-danger"></div>
          <div class="modal-body text-center py-4">
            <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            <h3>¿Estas seguro de cancelar esta Mesa?</h3>
            <div class="text-secondary">Si cancelas esta mesa se eliminaran todos los datos de las cuentas activas en la misma.</div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <div class="row">
                <div class="col">
                 <a href="#" class="btn btn-info w-100" data-bs-dismiss="modal">
                    Continuar con la Mesa
                  </a></div>
                <div class="col">
                  <a href="#" id="cancelAccBtnSuccess" class="btn btn-danger w-100" data-bs-dismiss="modal">
                    SI, Cancelar Mesa.
                  </a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<!-- Cambio de Mesa -->
<div class="modal fade" id="chgModalMesa" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" >Cambiar Mesa</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body mesasopen">
         '.checkMesaChg().'
         <input type="hidden" id="heremesa" value="'.$key['id'].'">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary changemesanow">Cambiar Mesa</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>


    <div class="modal modal-blur fade" id="preModalBind" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-danger"></div>
          <div class="modal-body text-center py-4">
            <h3>¿Estas Seguro de Juntar estas Cuentas?</h3>
            <div class="text-secondary">El juntar las cuentas es una accion que no se puede deshacer.</div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <div class="row">
                <div class="col">
                 <a href="#" class="btn btn-info w-100" data-bs-dismiss="modal">
                    Cancelar
                  </a></div>
                <div class="col">
                  <a href="#" onclick="uniAccount();" class="btn btn-danger w-100" data-bs-dismiss="modal">
                    SI.
                  </a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    ';
    	}
    }
}


function checkAccounts($mesa){

    // Tomamos los datos del sitio
    $dataSite = getDataSite();
    $exp = explode('|', $dataSite);

    // Conexion a base de datos
    $db = new DataBase;
    $where = array('idmesa' => $mesa);
    $rst = $db->select('cuentas', '*', $where);
    $html = '';
    if (empty($rst)){
    	$html .= '';
    }else{
    	foreach ($rst as $key){
        
        if ($exp[17] == 1){
          $fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
          $chkacc = str_replace('$', '', $fmt->formatCurrency(getDataAccActive2($key['insumos']), "CLP"));
        }else{
          $chkacc = getDataAccActive2($key['insumos']);
        }

    		$html .= '
    		    <div class="col-md-4">
               <div class="card">
                 <div class="card-header">
                   <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mood-smile-beam" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 21a9 9 0 1 1 0 -18a9 9 0 0 1 0 18z" /><path d="M10 10c-.5 -1 -2.5 -1 -3 0" /><path d="M17 10c-.5 -1 -2.5 -1 -3 0" /><path d="M14.5 15a3.5 3.5 0 0 1 -5 0" /></svg> '.$key['nombre'].'
                 </div>
                 <div class="accbodycard card-body text-center">
                   <h3 class="mb-1">Total:</h3>
                   <h1 class="totalAccountSingle">$'.$chkacc.' <small>'.$exp[10].'</small></h1>
                   <a href="'.URLSITE.'cuenta/'.$key['hash'].'" class="btn btn-block btn-outline-info">Ver Listado</a>
                   <a class="printaccount btn btn-block btn-outline-info mt-1" data-cuenta="'.$key['hash'].'">Imprimir Cuenta</a>
                   <!--<a class="unifacc btn btn-sm btn-outline-success float-end mt-3" data-id="'.$key['id'].'">Seleccionar Cuenta</a>-->
                 </div>
               </div>
            </div>';
    	}
    }
    return $html;
}



function cuentaSingle($hash){

    // Tomamos los datos del sitio
    $dataSite = getDataSite();
    $exp = explode('|', $dataSite);
    $btpc = '';
    $note = '';

    if ($exp[9] > 0){
      $btpc = '(+ Propina '.$exp[9].'%)';
    }

    // Conexion a base de datos
    $db = new DataBase;
    $where = array('hash' => $hash);
    $rst = $db->select('cuentas', '*', $where);
    if (empty($rst)){
      errorMessage();
    }else{

      echo '<div class="page-header d-print-none"><div class="container-xl"><div class="row">';

      foreach ($rst as $key){

        $whomesa = getNumberOfMesa($key['idmesa']);
        if ($whomesa == 0){
          $rtnbuttonback = '';
          if (is_null($key['nota']) || empty($key['nota'])) {
            $txtnota = '';
          }else{
            $txtnota = base64_decode($key['nota']);
          }
          $note = '
             <div class="input-group mb-2 mt-2">
               <input type="hidden" id="idaccountnote" value="'.$key['id'].'">
               <input type="text" class="form-control" placeholder="Nota" id="noteintpdata" value="'.$txtnota.'">
               <button class="btn btn-outline-secondary" type="button" onclick="saveNota();">Guardar</button>
             </div>
          ';
        }else{
          $rtnbuttonback = '<a class="btn btn-info float-start mt-3" href="'.URLSITE.'mesa/'.ReturnHashLink($key['idmesa']).'">Regresar</a>';
        }
        
        echo'
           <div class="col-md-12 mb-3">
             <h1 class="page-title float-start"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-hexagon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z" /><path d="M6.201 18.744a4 4 0 0 1 3.799 -2.744h4a4 4 0 0 1 3.798 2.741" /><path d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" /></svg> '.$key['nombre'].'</h1>
           </div>
           <div class="col-md-9">
             <div class="row" id="tableInsuAdd">
             '.displayInsAccount($key['hash']).'
             </div>

             <div class="btn-group" role="group">
             '.$rtnbuttonback.'
             <button class="btn btn-warning float-start mt-3" data-bs-toggle="modal" data-bs-target="#sticInsMdl">A&ntilde;adir Insumo</button>
             </div>
             ';
              
              if ($exp[17] == 1){
                $fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
                $insrntdata = getDataAccActive($key['insumos']);
                $moneda = str_replace('$', '', $fmt->formatCurrency($insrntdata, "CLP"));
              }else{
                $moneda = getDataAccActive($key['insumos']);
              }

            echo'
           </div>
           <div class="col-md-3">
             <div class="card text-center">
               <div class="card-body">
                 <h1 class="totalAccountSingle">Total</h1>
                 <h1 id="totalAccountSingle" class="totalAccountSingle">$'.$moneda.' <small>'.$exp[10].'</small></h1>
                 <h2>'.$btpc.'</h2>
               </div>
             </div>
             '.$note.'
             <a class="payaccont btn btn-block btn-lg btn-success mt-1" data-bs-toggle="modal" data-bs-target="#payModal" data-cuenta="'.$key['id'].'">Pagar Cuenta</a>
             <a class="printaccount btn btn-block btn-info mt-2" data-cuenta="'.$key['hash'].'">Imprimir Cuenta</a>
             <a data-bs-toggle="modal" data-bs-target="#modal-danger" class="closeaccounta btn btn-block btn-danger mt-1" data-cuenta="'.$key['id'].'">Cancelar Cuenta</a>
           </div>

           <!-- Modal -->
           <div class="modal fade" id="sticInsMdl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
             <div class="modal-dialog">
               <div class="modal-content">
                 <div class="modal-header">
                   <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                   <table id="insumosAddTable" class="table">
                     <thead>
                       <tr>
                         <th>Nombre</th>
                         <th>Categoria</th>
                         <th>Codigo</th>
                         <th>Precio</th>
                         <th>&nbsp;</th>
                       </tr>
                     </thead>
                     <tbody>
                     </tbody>
                   </table>
                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                 </div>
               </div>
             </div>
           </div>
           <input type="hidden" id="stringMesa" value="'.$key['id'].'">
           <input type="hidden" id="stringAccount" value="'.$key['idmesa'].'">
        ';
      }

      echo '</div></div></div>


    <div class="modal modal-blur fade" id="modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-danger"></div>
          <div class="modal-body text-center py-4">
            <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            <h3>¿Estas seguro de cancelar esta cuenta?</h3>
            <div class="text-secondary">Si cancelas esta cuenta se eliminaran todos los datos referentes a esta cuenta.</div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <div class="row">
                <div class="col">
                 <a href="#" class="btn btn-info w-100" data-bs-dismiss="modal">
                    Continuar con la Cuenta
                  </a></div>
                <div class="col">
                  <a href="#" id="cancelAccBtnSuccess" class="btn btn-danger w-100" data-bs-dismiss="modal">
                    SI, Cancelar la cuenta.
                  </a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


<!-- Modal -->
<div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">&nbsp;</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
         <form id="PayForm">
           <input type="hidden" name="id" value="'.$key['id'].'">
           <input id="valueaccpay" value="'.getDataAccActive($key['insumos']).'" type="hidden"> 
           <label>Pago Con:</label>
           <input type="number" id="canpayval" class="form-control mb-3" name="pagocon" placeholder="$0">
           <label>Tipo de Pago:</label>
           <select id="methodPaySelect" name="type" class="form-control">
              <option value="1">Efectivo</option>
              <option value="2">Tarjeta</option>
           </select>
         </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="payAccount();">Pagar Cuenta</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>';

    }

}




function parseSecondData($ins, $mode){
      
      // Tomamos los datos del sitio
      $dataSite = getDataSite();
      $expDS = explode('|', $dataSite);

      if (empty($ins)){
        return;
      }

      $exp = explode('|', $ins);
      $cnt = count($exp);
      $rtn = getTheProNameRtn($exp[0]);
      $expI = explode('|', $rtn);

      $id = $exp[0];
      $cantidad = $exp[1];
      $nombre = $expI[0];
      $precio = $expI[1] * $exp[1];

      if ($expDS[16] == 1){
         $mymn = number_format((float)$precio, 2, '.', '');
      }else{
         if ($expDS[17] == 1){
           $fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
           $mymn = str_replace('$', '', $fmt->formatCurrency($precio, "CLP"));
         }else{
          $mymn = (int) $precio;
         }
      }

      if ($exp[3] == 1){
        $ent = '<a class="deliverySt noIE" data-status="1"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M13 17h-9a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6a2 2 0 1 1 4 0a7 7 0 0 1 4 6v2" /><path d="M9 17v1a3 3 0 0 0 4.194 2.753" /><path d="M22 22l-5 -5" /><path d="M17 22l5 -5" /></svg></a>';
      }else{
        $ent = '<a class="deliverySt yesIE" data-status="2"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M11.5 17h-7.5a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3c.016 .129 .037 .256 .065 .382"></path><path d="M9 17v1a3 3 0 0 0 2.502 2.959"></path><path d="M15 19l2 2l4 -4"></path></svg></a>';
      }

      $dt = '<div id="coInDa'.$id.'" class="insaddobjt col-md-3 col-sm-12 mb-3"><div class="card"><div class="card-body">'.$ent.'<h4 class="text-center ttladd">'.$nombre.'</h4><div class="input-group mb-2"><input type="text" id="noteInsAcc'.$id.'" class="form-control" aria-describedby="button-addon2" value="'.base64_decode($exp[2]).'"><button onclick="saveNote('.$id.');" class="btn btn-outline-secondary text-center" type="button" id="button-addon2">G</button></div><div class="input-group"><button onclick="minus('.$id.');" class="btn btn-outline-danger" type="button">-</button><input id="inptCant'.$id.'" type="text" class="form-control text-center" value="'.$exp[1].'" disabled=""><button onclick="maximus('.$id.');" class="btn btn-outline-success" type="button">+</button></div><h2 class="totalAccIns'.$id.' subtotalpro mt-2 text-center" data-subtl="'.$precio.'">Total: $'.$mymn.' <small>'.$expDS[10].'</small></h2></div></div></div>'; 
      

      $dt2 = '
        <div class="card mt-2">
          <div class="card-body text-center">
             <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-article" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M7 8h10" /><path d="M7 12h10" /><path d="M7 16h10" /></svg>
             <span class="badge text-bg-primary">Insmo: '.$nombre.'</span>
             <span class="badge text-bg-success">Cantidad: '.$exp[1].'</span>
             <span class="badge text-bg-warning">Precio Unitario: $'.$expI[1].'</span>
             <span class="badge text-bg-danger">SubTotal: $'.$precio.'</span>
             <span class="badge text-bg-secondary">Detalles: '.base64_decode($exp[2]).'</span>
          </div>
        </div>
      ';

      if ($mode == 1){
        return $dt;
      }else{
        return $dt2;
      }

}


// Venta de cuenta single
function ventaAccSingle($hash){

    // Tomamos los datos del sitio
    $dataSite = getDataSite();
    $expDS = explode('|', $dataSite);

    // Conexion a base de datos
    $db = new DataBase;
    $where = array('hash' => $hash);
    $rst = $db->select('ventas', '*', $where);
    if (empty($rst)){
        errorMessage();
    }else{
      foreach ($rst as $key){
          $centaname = $key['cuenta'];
          $articulos = $key['articulos'];
          $mesa = $key['mesa'];
          $encargado = $key['usuario'];
          $pagocon = $key['pagocon'];
          $cambio = $key['cambio'];
          $tipopa = $key['tipopago'];
          $hashpay = $key['hash'];
      }

      if ($tipopa == 1){
        $pt = 'Efectivo';
      }else{
        $pt = 'Tarjeta';
      }

    }

    if ($expDS[17] == 1){
      $thnmnew = '<p>Orden Rapida</p>';
    }else{
      if ($mesa == 0){
        $thnmnew = '<p>Orden Rapida</p>';
      }else{
        $thnmnew = '<p>Mesa: #'.$mesa.' </p>';
      }
    }

    echo'
    <div class="container">
     <div class="row mt-3">
       <div class="col-md-12">
         <div id="closePaysuccess"></div>
       </div>
       <div class="col-md-9">
            <div class="card">
              <div class="card-body">
                <h1>Cuenta: '.$centaname.'</h1>
                '.parseDataIns($articulos, 2).'
           <div class="row">
            <div class="col-md-4 mt-3 text-center">
               <div class="card">
                 <div class="card-body">
                   <h2>
                     '.$thnmnew.'
                     <p>Encargado: '.nameUser($encargado).' </p>
                   </h2>
                 </div>
               </div>
            </div>
            <div class="col-md-4 mt-3 text-center">
               <div class="card">
                 <div class="card-body">
                   <h2>
            ';

               if ($expDS[17] == 1){
                 $fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
                 $npw = str_replace('$', '', $fmt->formatCurrency($pagocon, "CLP"));
                 $npwc = str_replace('$', '', $fmt->formatCurrency($cambio, "CLP"));
                 $ntotl = str_replace('$', '', $fmt->formatCurrency(getDataAccActive($articulos), "CLP"));
               }else{
                 $npw = $pagocon;
                 $npwc = $cambio;
                 $ntotl = getDataAccActive($articulos);
               }

            echo'
                     <p>Pago con: $'.$npw.'</p>
                     <p>Cambio: $'.$npwc.'</p>
                   </h2>
                 </div>
               </div>
            </div>
            <div class="col-md-4 mt-3 text-center">
               <div class="card">
                 <div class="card-body">
                   <h2>
                     <p>Tipo de pago: '.$pt.'</p>
                     <p>Total: $'.$ntotl.'</p>
                   </h2>
                 </div>
               </div>
            </div>      
           </div>
          </div>
        </div>
       </div>
       <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                 <button class="btn btn-block btn-lg btn-info" onclick="PrintTicketPay();">Imprimir Ticket</button>
                 <input type="hidden" id="hashventa" value="'.$hashpay.'">
              </div>
            </div>
       </div>
      </div>
     </div>
    ';

}


function showTicket($action){
$dataSite = getDataSite();
$exp = explode('|', $dataSite);
$title = $exp[0].' - '.$exp[1];
$db = new DataBase;
$where = array('hash' => $action);
$selectSQL = $db->select('cuentas', '*', $where);
foreach ($selectSQL as $key){
   $insumos = $key['insumos'];

   if (is_null($key['nota']) || empty($key['nota'])){
      $nota = '';
   }else{
      $nota = '<hr><p>Nota: '.base64_decode($key['nota']).'</p>';
   }

}

$expp = explode('#', substr($insumos, 0, -1));
$cnt = count($expp);
$insInfo = '';
for ($i=0; $i < $cnt; $i++){ 
   $insInfo .= getRowsInfoTicket($expp[$i]);
}

// Agregamos lo de la propina
if ($exp[9] > 0){

  $tt = getDataAccActive2($insumos);
  $pc = $tt * $exp[9] / 100;
  $sm = $tt + $pc;

  if ($exp[17] == 1){
  $fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
  $ht = '
    <p>SubTotal: $'.str_replace('$', '', $fmt->formatCurrency($tt, "CLP")).'<small>'.$exp[10].'</small></p>
    <p>Propina: $'.str_replace('$', '', $fmt->formatCurrency($pc, "CLP")).'<small>'.$exp[10].'</small> ('.$exp[9].'%)</p>
    <hr>
    <p>Total: $'.str_replace('$', '', $fmt->formatCurrency($sm, "CLP")).'<small>'.$exp[10].'</small></p>
  ';  
  }else{
  $ht = '
    <p>SubTotal: $'.$tt.'<small>'.$exp[10].'</small></p>
    <p>Propina: $'.$pc.'<small>'.$exp[10].'</small> ('.$exp[9].'%)</p>
    <hr>
    <p>Total: $'.$sm.'<small>'.$exp[10].'</small></p>
  ';    
  }

}else{
  if ($exp[17] == 1){
    $fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
    $dttotal = str_replace('$', '', $fmt->formatCurrency(getDataAccActive($insumos), "CLP"));
  }else{
    $dttotal = getDataAccActive($insumos);
  }

  $ht = '
    <p>Total: $'.$dttotal.'<small>'.$exp[10].'</small></p>
  ';
}

echo'
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <style>
    *{margin: 5px;}
    body{text-align: center;font-family: monospace;font-weight: 100;}
  </style>
</head>
<body>
  <img width="100" src="'.URLSITE.'images/'.$exp[2].'">
  <h2><strong>'.$exp[0].'</strong></h2>
  <hr>
  <p>'.$exp[3].'</p>
  <p>'.$exp[4].'</p>
  <p>Fecha: '.date('d').'/'.date('m').'/'.date('Y').' - '.date('h:i A').'</p>
  '.$nota.'
  <hr>
   '.$insInfo.'
  <hr>
   Le atiende: '.nameUser($_SESSION['idSession']).'
  <hr>
  '.$ht.'
</body>
  <script>
    document.addEventListener("DOMContentLoaded", function(event){window.print();});
  </script>
</html>
';
}


function showTicketVenta($action){

$dataSite = getDataSite();
$exp = explode('|', $dataSite);
$title = $exp[0].' - '.$exp[1];

$db = new DataBase;
$where = array('hash' => $action);
$selectSQL = $db->select('ventas', '*', $where);
foreach ($selectSQL as $key){

   $folio = $key['id'];
   $articulos = $key['articulos'];
   $mesa = $key['mesa'];
   $usuario = $key['usuario'];
   $tipopa = $key['tipopago'];
   $pagocon = $key['pagocon'];
   $cambio = $key['cambio'];
   $propina = $key['propina'];

}

$expp = explode('#', substr($articulos, 0, -1));
$cnt = count($expp);
$insInfo = '';
for ($i=0; $i < $cnt; $i++){ 
   $insInfo .= getRowsInfoTicket($expp[$i]);
}

if ($tipopa == 1){
  $pt = 'Efectivo';
}else{
  $pt = 'Tarjeta';
}

// Decimales
if ($exp[16] == 1) {
//--------------------------------------------------
// Agregamos lo de la propina
if ($propina > 0){
  $tt = getDataAccActive2($articulos);
  $pc = $tt * $propina / 100;
  $sm = $tt + $pc;
  $pp = $pagocon + $pc;
  $ht = '
    <h4>Tipo de pago: '.$pt.'</h4>
    <p>SubTotal: $'.$tt.'<small>'.$exp[10].'</small></p>
    <p>Propina: $'.$pc.'<small>'.$exp[10].'</small> ('.$propina.'%)</p>
    <hr>
    <p>Total: $'.$sm.'<small>'.$exp[10].'</small></p>
    <p>Pago con: $'.$pagocon.'<small>'.$exp[10].'</small></p>
    <p>Cambio: $'.$cambio.'<small>'.$exp[10].'</small></p>
  ';
}else{
  $ht = '
    <h4>Tipo de pago: '.$pt.'</h4>
    <p>Total: $'.getDataAccActive($articulos).'<small>'.$exp[10].'</small></p>
    <p>Pago con: $'.$pagocon.'<small>'.$exp[10].'</small></p>
    <p>Cambio: $'.$cambio.'<small>'.$exp[10].'</small></p>
  ';
}
//--------------------------------------------------  
}else{
//--------------------------------------------------

     if ($exp[17] == 1) {
     #..............................................

$fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
// Agregamos lo de la propina
if ($propina > 0){
  $tt = getDataAccActive2($articulos);
  $pc = $tt * $propina / 100;
  $sm = $tt + $pc;
  $pp = $pagocon + $pc;

   $sbtl = str_replace('$', '', $fmt->formatCurrency($tt, "CLP"));
   $proptl = str_replace('$', '', $fmt->formatCurrency($pc, "CLP"));
   $ttlpl = str_replace('$', '', $fmt->formatCurrency($sm, "CLP"));
   $pgcntl = str_replace('$', '', $fmt->formatCurrency($pagocon, "CLP"));
   $cmbitl = str_replace('$', '', $fmt->formatCurrency($cambio, "CLP"));

  $ht = '
    <h4>Tipo de pago: '.$pt.'</h4>
    <p>SubTotal: $'.$sbtl.'<small>'.$exp[10].'</small></p>
    <p>Propina: $'.$proptl.'<small>'.$exp[10].'</small> ('.$propina.'%)</p>
    <hr>
    <p>Total: $'.$ttlpl.'<small>'.$exp[10].'</small></p>
    <p>Pago con: $'.$pgcntl.'<small>'.$exp[10].'</small></p>
    <p>Cambio: $'.$cmbitl.'<small>'.$exp[10].'</small></p>
  ';
}else{
     
     $newtotal = str_replace('$', '', $fmt->formatCurrency(getDataAccActive($articulos), "CLP"));
     $newpgcon = str_replace('$', '', $fmt->formatCurrency($pagocon, "CLP"));
     $newcambio = str_replace('$', '', $fmt->formatCurrency($cambio, "CLP"));

  $ht = '
    <h4>Tipo de pago: '.$pt.'</h4>
    <p>Total: $'.$newtotal.'<small>'.$exp[10].'</small></p>
    <p>Pago con: $'.$newpgcon.'<small>'.$exp[10].'</small></p>
    <p>Cambio: $'.$newcambio.'<small>'.$exp[10].'</small></p>
  ';
}

     #..............................................
     }else{
     #..............................................

// Agregamos lo de la propina
if ($propina > 0){
  $tt = getDataAccActive2($articulos);
  $pc = $tt * $propina / 100;
  $sm = $tt + $pc;
  $pp = $pagocon + $pc;
  $ht = '
    <h4>Tipo de pago: '.$pt.'</h4>
    <p>SubTotal: $'.$tt.'<small>'.$exp[10].'</small></p>
    <p>Propina: $'.$pc.'<small>'.$exp[10].'</small> ('.$propina.'%)</p>
    <hr>
    <p>Total: $'.$sm.'<small>'.$exp[10].'</small></p>
    <p>Pago con: $'.$pagocon.'<small>'.$exp[10].'</small></p>
    <p>Cambio: $'.$cambio.'<small>'.$exp[10].'</small></p>
  ';
}else{
  $ht = '
    <h4>Tipo de pago: '.$pt.'</h4>
    <p>Total: $'.getDataAccActive($articulos).'<small>'.$exp[10].'</small></p>
    <p>Pago con: $'.(int) $pagocon.'<small>'.$exp[10].'</small></p>
    <p>Cambio: $'.(int) $cambio.'<small>'.$exp[10].'</small></p>
  ';
}

     #..............................................
     }

//-------------------------------------------------- 
}

if ($exp[17] == 1){
  $tituloTicket = '<h3>Orden Rapida</h3>';
}else{
  if ($mesa == 0){
    $tituloTicket = '<h3>Orden Rapida</h3>';
  }else{
    $tituloTicket = '<h3>Mesa #'.$mesa.'</h3>';
  }
  
}

echo'
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <style>
    *{margin: 5px;}
    body{text-align: center;font-family: monospace;font-weight: 100;}
  </style>
</head>
<body>
  <img width="100" src="'.URLSITE.'images/'.$exp[2].'">
  <h2><strong>'.$exp[0].'</strong></h2>
  <hr>
  '.$tituloTicket.'
  <hr>
  Folio: '.$folio.'
  <hr>
  <p>'.$exp[3].'</p>
  <p>Tel&eacute;fono: '.$exp[4].'</p>
  <p>Fecha: '.date('d').'/'.date('m').'/'.date('Y').' - '.date('h:i A').'</p>
  <hr>
   '.$insInfo.'
  <hr>
   Le atiendi&oacute;: '.nameUser($usuario).'
  <hr>
  '.$ht.'
</body>
  <script>
    document.addEventListener("DOMContentLoaded", function(event){window.print();});
  </script>
</html>
';
}

function ConfigModulo(){

 $dataSite = getDataSite();
 $exp = explode('|', $dataSite);
 $btnrmv = '';
  
 if ($exp[2] <> 'default.png'){
   $btnrmv = '<button class="btn btn-danger mt-3" onclick="rtnImgDefaultLogo();">Imagen por Defecto</button>';
 }

 // Estilo
 if ($exp[7] == 1){
   $estilo = '
     <option value="1">Boxed</option>
     <option value="2">Open</option>
   ';
 }else{
   $estilo = '
     <option value="2">Open</option>
     <option value="1">Boxed</option>
   ';
 }

 // Color
 if ($exp[8] == 1){
   $color = '
     <option value="1">Dark</option>
     <option value="2">White</option>
   ';
 }else{
   $color = '
     <option value="2">White</option>
     <option value="1">Dark</option>
   ';
 }


 // SMTP
 if ($exp[15] == 2){
    $slct = '
       <select name="smtpactive" class="form-control smtpcontrol">
          <option value="2">Si</option>
          <option value="1">No</option>
       </select>
    ';
 }else{
    $slct = '
       <select name="smtpactive" class="form-control smtpcontrol">
          <option value="1">No</option>
          <option value="2">Si</option>
       </select>
    ';
 }

 // Decimales y Miles
 if ($exp[16] == 1){
   $dcml = '
           <label>Usar Decimales:</label>
           <select id="decimals" class="form-control" name="decimals">
             <option value="1">Si</option>
             <option value="2">No</option>
           </select>
   ';
 }else{
   $dcml = '
           <label>Usar Decimales:</label>
           <select id="decimals" class="form-control" name="decimals">
             <option value="2">No</option>
             <option value="1">Si</option>
           </select>
   ';
 }

 if ($exp[17] == 1){
   $dcmlo = '
           <label class="mt-2">Formato en Miles (usando punto en lugar de coma):</label>
           <select id="miles" class="form-control" name="miles">
             <option value="1">Si</option>
             <option value="2">No</option>
           </select>
   ';
 }else{
   $dcmlo = '
           <label class="mt-2">Formato en Miles (usando punto en lugar de coma):</label>
           <select id="miles" class="form-control" name="miles">
             <option value="2">No</option>
             <option value="1">Si</option>
           </select>
   ';
 }


echo'
<div class="container">
   <div class="row">
   <div class="col-md-12 mt-5">
     <h1>Configuraci&oacute;n</h1>
   </div>
   <div class="col-md-7 mb-3">
   <div class="card">
     <div class="card-body">
     <ul class="nav nav-tabs" id="myTab" role="tablist">
       <li class="nav-item" role="presentation">
         <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general-tab-pane" type="button" role="tab" aria-selected="true">General / Sistema</button>
       </li>
       <li class="nav-item" role="presentation">
         <button class="nav-link" data-bs-toggle="tab" data-bs-target="#logo-tab-pane" type="button" role="tab" aria-selected="false">Logo / Estilos</button>
       </li>
       <li class="nav-item" role="presentation">
         <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cash-tab-pane" type="button" role="tab" aria-selected="false">Avanzado</button>
       </li>
       <li class="nav-item" role="presentation">
         <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tools-tab-pane" type="button" role="tab" aria-selected="false">Herramientas</button>
       </li>
       <li class="nav-item" role="presentation">
         <button class="nav-link" data-bs-toggle="tab" data-bs-target="#mail-tab-pane" type="button" role="tab" aria-selected="false">Correos Config</button>
       </li>

     </ul>
     <div class="tab-content" id="myTabContent">
       <div class="tab-pane fade show active" id="general-tab-pane" role="tabpanel">
          <form id="frmConfigData">
             <input type="hidden" name="p" value="21">
             <label class="mt-3">Nombre:</label>
             <input type="text" class="form-control" name="titlesite" value="'.$exp[0].'">
             <label class="mt-3">Descripcion:</label>
             <input type="text" class="form-control" name="descsite" value="'.$exp[1].'">
             <label class="mt-3">Direccion:</label>
             <input type="text" class="form-control" name="direccion" value="'.$exp[3].'">
             <label class="mt-3">Telefono:</label>
             <input type="text" class="form-control" name="telefono" value="'.$exp[4].'">
             <label class="mt-3">Cantidad de Mesas:</label>
             <input type="number" class="form-control" name="mesas" value="'.$exp[6].'">
          </form>
          <button class="btn btn-success mt-3 float-end" onclick="saveConfig();">Guardar</button>
       </div>

       <div class="tab-pane fade" id="logo-tab-pane" role="tabpanel">

          <h1 class="mt-3">Logo:</h1>
          <img src="'.URLSITE.'images/'.$exp[2].'" width="200" style="display: block;margin: 0 auto;">
           <form id="imgfrmsys">
             <input type="hidden" name="p" value="23">
             <input id="ticc" type="file" name="imglogo">
           </form>
           <button class="btn btn-warning mt-3" onclick="changeimgConf();">Cambiar Imagen</button>
           '.$btnrmv.'
          <hr>
          <h1>Estilos:</h1>
          <form id="formcfgstyles">
            <input type="hidden" name="p" value="22">
            <label>Color:</label>
            <select class="form-control" name="color">
              '.$color.'
            </select>
            <label class="mt-3">Estilo:</label>
            <select class="form-control" name="estilo">
             '.$estilo.'
            </select>
          </form>
          <button class="btn btn-success mt-3 float-end" onclick="saveConfigStyles();">Guardar</button>

  
       </div>

       <div class="tab-pane fade" id="cash-tab-pane" role="tabpanel">
         <h1 class="mt-3">Moneda:</h1>
         <div class="alert alert-warning" role="alert">
           <strong>IMPORTANTE: Para usar el tipo de moneda que usa punto (.) en lugar de coma (,) para separar las cantidades en miles (Ejemplo $1.000) debes de tener habilitado la extension "Intl" en el php.ini, de lo contrario no podras usar esto y el software no funcionara de manera correcta.</strong>. 
         </div>
         <form id="formdcmls">
         <input type="hidden" name="p" value="38">
         '.$dcml.$dcmlo.'
         </form>
         <button class="btn btn-primary mt-3" onclick="saveDcMls();">Guardar Cambios</button>
         <hr>
         <h1 class="mt-3">Propinas:</h1>
         <div class="alert alert-warning" role="alert">
           Esto es para agregar un porcentaje de las propinas, esto se cumple en las mesas y en las cuentas cuando se cobran de manera individual, puedes personalizar la cantidad sin problema, pero dejare como un tope la cantidad de 25%. 
         </div>
         <form id="frmpercent">
           <label>Porcentaje:</label>
           <input type="hidden" name="p" value="25">
           <input class="form-control" type="number" name="porcentaje" value="'.$exp[9].'">
           <button type="button" class="btn btn-warning mt-3" onclick="savePercent();">Guardar Cambios</button>
         </form>
         <hr>
         <h1 class="mt-3">Divisas:</h1>
         <div class="alert alert-danger" role="alert">
           Bueno como hay muchos tipos de divisas, se deja este campo para personalizar la misma, algunos tienen simbolos o abreviaciones, unos van primero que el valor y otros al final, no es una solucion definitiva pero de momento servira, por defecto es el peso mexicano.
         </div>
         <form id="frmdivisa">
           <label>Moneda:</label>
           <input type="hidden" name="p" value="26">
           <input class="form-control" type="text" name="divisa" value="'.$exp[10].'">
           <button type="button" class="btn btn-warning mt-3"  onclick="saveDivisa();">Guardar Cambios</button>
         </form>
       </div>

       <div class="tab-pane fade" id="tools-tab-pane" role="tabpanel">
        <h1 class="mt-3">Descargar / Importar Insumos:</h1>

        <div class="btn-group mt-2" role="group" aria-label="Basic example">
          <a href="'.URLSITE.'download/insumos" target="_blank" type="button" class="btn btn-outline-success">Descargar Insumos</a>
          <button type="button" class="btn btn-outline-primary" onclick="imptinsm();">Importar Insumnos</button>
          <a href="'.URLSITE.'assets/format.txt" target="_blank" type="button" class="btn btn-outline-warning">Formato de Ejemplo</a>
        </div>

        <form id="imptinsm">
           <input type="hidden" name="p" value="33">
           <input type="file" name="insuimport" id="inptflinsu">
        </form>

       <hr>

       <h1 class="mt-3">Base de Datos</h1>
       <div class="alert alert-danger" role="alert">
         Esta funcion borra todos los registros y solo deja el usuario principal y los datos por defecto de la instalacion, esta accion no se puede deshacer.
       </div>
       <button class="btn btn-outline-danger" onclick="restoreSystem();">Restaurar Sistema</button>

       <hr>
       <h1 class="mt-3">Respaldo</h1>
       <div class="alert alert-success" role="alert">
         Se puede guardar la base de datos del sistema, junto con todos sus registros por si se necesita migrar el programa y no tener que hacer la instalacion de nuevo.
       </div>
       <a href="'.URLSITE.'download/database" class="btn btn-outline-success">Guardar Base de Datos</a>

       </div>

       <div class="tab-pane fade" id="mail-tab-pane" role="tabpanel">
          <h1 class="mt-3">Configuracion de Correos</h1>
             <label>Recovery Activado / Correos Activados</label>
             '.$slct.'
          <form id="smtpfrm" class="mt-3">
             <input type="hidden" name="p" value="35">
             <input type="hidden" name="smtpactive" id="smtpstatus">
             <label class="mt-2">Servidor SMTP:</label>
             <input type="text" name="smtp" class="form-control" value="'.$exp[11].'">
             <label class="mt-2">Usuario SMTP:</label>
             <input type="text" name="usersmtp" class="form-control" value="'.$exp[12].'">
             <label class="mt-2">Contrase&ntilde;a SMTP:</label>
             <input type="text" name="passmtp" class="form-control" value="'.$exp[13].'">
             <label class="mt-2">Puerto SMTP:</label>
             <input type="text" name="smtport" class="form-control" value="'.$exp[14].'">
             <button onclick="saveSMTP();" type="button" class="btn btn-primary float-end mt-3">Guardar Configuraci&oacute;n</button>
          </form>
       </div>
     </div>
        </div>
      </div>
     </div>

      <div class="col-md-5">
         <div class="card">
           <div class="card-body">

               <div class="alert alert-primary" role="alert">
                 El logotipo debe de tener medidas minima de 150px y maxima de 200px. Si se sobre pasan estas medidas puede deformar el estilo del sistema y del ticket. la imagen debe de estar en formato PNG con fondo transparente.
               </div>
               <div class="alert alert-success" role="alert">
                 La cantidad de mesas es ilimitada, sin embargo recuerda que mientras mas mesas, el sistema es mas lento, el sistema puede procesar bien una cantidad amplia de mesas pero se recomienda usar como maximo 50 mesas.
               </div>

           </div>
         </div>
      </div>
   </div>
</div>

';

}



function UsersModulo(){

echo'
<div class="container">
   <div class="row">
   <div class="col-md-12 mt-5">
     <h1 class="float-start">Usuarios</h1>
     <button type="button" class="btn btn-warning float-end" data-bs-toggle="modal" data-bs-target="#staticNewUsersModal"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-big-up-lines" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 12h-3.586a1 1 0 0 1 -.707 -1.707l6.586 -6.586a1 1 0 0 1 1.414 0l6.586 6.586a1 1 0 0 1 -.707 1.707h-3.586v3h-6v-3z"></path><path d="M9 21h6"></path><path d="M9 18h6"></path></svg> Nuevo Usuario</button>
   </div>
   <div class="col-md-12 mt-2">
   <div class="card">
     <div class="card-body">
           <table id="usersTable" class="table">
             <thead>
               <tr>
                 <th scope="col">Nombre</th>
                 <th scope="col">Usuario</th>
                 <th scope="col">Email</th>
                 <th scope="col">Rango</th>
                 <th scope="col">Status</th>
                 <th scope="col">Acciones</th>
               </tr>
             </thead>
           </table>
        </div>
      </div>
     </div>
   </div>
</div>


<!-- Modal -->
<div class="modal fade" id="staticNewUsersModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Nuevo Usuario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <form id="newUserForm">
           <input type="hidden" name="p" value="18">
           <label>Nombre:</label>
           <input type="text" class="form-control" name="nusr">
           <label class="mt-2">Usuario:</label>
           <input type="text" class="form-control" name="uusr">
           <label class="mt-2">Contrase&ntilde;a</label>
           <input type="text" class="form-control" name="pusr">
           <label class="mt-2">Email:</label>
           <input type="text" class="form-control" name="emlusr">
           <label class="mt-2">Status:</label>
           <select class="form-control" name="status">
             <option value="2">Activo</option>
             <option value="1">Inactivo</option>
           </select>
           <label class="mt-2">Rango:</label>
           <select class="form-control" name="rango">
             <option value="1">Usuario</option>
             <option value="2">Administrador</option>
           </select>
         </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="saveNewUsuario();">Guardar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

    <div class="modal modal-blur fade" id="modal-danger" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-danger"></div>
          <div class="modal-body text-center py-4">
            <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            <h3>¿Estas seguro de eliminar este usuario?</h3>
            <div class="text-secondary">Si eliminas este usuario no los registros, ventas y otros datos no seran visibles, esta accion no se puede deshacer.</div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <div class="row">
                <div class="col">
                 <a href="#" class="btn btn-info w-100" data-bs-dismiss="modal">
                    Cancelar
                  </a></div>
                <div class="col">
                  <a href="#" id="cancelUsrBtnSuccess" class="btn btn-danger w-100" data-bs-dismiss="modal">
                    SI, Eliminar Usuario.
                  </a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


';

}