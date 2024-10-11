<?php

function InformesModule(){

  $dataSite = getDataSite();
  $exp = explode('|', $dataSite);
  $mesas = $exp[6] + 1;
  $mshtml = '';
  for ($i=1; $i < $mesas; $i++) { 
    $mshtml .= '<option value="'.$i.'">Mesa #'.$i.'</option>';
  }

  echo'
     <div class="container mt-4">
       <div class="row">
         <h1>Informes</h1>

            <div class="col-md-4">
               <div class="card">
                 <div class="card-body text-center">
                    <h1>Mesas Ocupadas</h1>
                    <h1>'.CountTableActive().'</h1>
                 </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="card">
                 <div class="card-body text-center">
                    <h1>Cuentas Abiertas</h1>
                    <h1>'.CountAccActive().'</h1>
                 </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="card">
                 <div class="card-body text-center">
                    <h1>Ganancias del Dia</h1>
                    <h1>'.Money4Day().'</h1>
                 </div>
               </div>
            </div>

            <div class="col-md-3 mt-4">
               <div class="card">
                 <div id="btninfo" class="card-body text-center">
                    <h1>Descarga de Informes</h1>
                    <a href="'.URLSITE.'info/complete" class="btn mt-2 btn-block btn-primary">Informe Completo de Ventas</a>
                    <button data-bs-toggle="modal" data-bs-target="#meseroInfoFile" class="btn mt-2 btn-block btn-primary">Ventas por Mesero</button>
                    <button data-bs-toggle="modal" data-bs-target="#mesaInfoFile" class="btn mt-2 btn-block btn-primary">Ventas por Mesa</button>
                    <a href="'.URLSITE.'info/day" class="btn mt-2 btn-block btn-primary">Ventas del Dia</a>
                    <button data-bs-toggle="modal" data-bs-target="#infoPeFechaModal" class="btn mt-2 btn-block btn-primary">Ventas por Fecha</button>
                    <button data-bs-toggle="modal" data-bs-target="#TicketModalPrint" class="btn mt-2 btn-block btn-primary">Imprimir Ticket</button>
                    <a href="'.URLSITE.'info/topsales" class="btn mt-2 btn-block btn-primary">Lo mas vendido del Mes</a>
                 </div>
               </div>
            </div>

            <div class="col-md-9 mt-4">
               <div class="card">
                 <div class="card-body text-center">
                    <h1>Listado de ultimas ventas (Limitado a 50)</h1>
                    <table id="vddTable" class="table">
                      <thead>
                         <tr>
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
                           <th>Ticket</th>
                         </tr>
                         <tbody>
                         </tbody>
                    </table>
                    
                 </div>
               </div>
            </div>

       </div>
     </div>


    <div class="modal fade" id="meseroInfoFile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">&nbsp;</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
             <form id="frmUsers">
               <label>Mesero:</label>
               <input type="hidden" name="p" value="27">
               <select class="form-control" name="mesero">
               '.usersOptionsInfo().'
               </select>
             </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success" onclick="DonwloadPerMesero();">Descargar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="mesaInfoFile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">&nbsp;</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
             <form id="frmMesasInfo">
               <label>Mesa:</label>
               <input type="hidden" name="p" value="28">
               <select class="form-control" name="mesa">
               '.$mshtml.'
               </select>
             </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success" onclick="DonwloadPerMesa();">Descargar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="infoPeFechaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog  modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">&nbsp;</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
             <form id="frmFechaInfo">
               <label>Fecha:</label>
               <input type="hidden" name="p" value="29">
               <input id="fdpicker" type="text" class="form-control" name="fecha">
             </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success" onclick="DonwloadPerFecha();">Descargar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>


<div class="modal fade" id="TicketModalPrint" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label></label>
        <input type="number" class="form-control" id="ticketFolio">
      </div>
      <div class="modal-footer">
        <button class="btn btn-success" onclick="ImprimirTicketPerFolio();">Imprimir Ticket</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>


';

}