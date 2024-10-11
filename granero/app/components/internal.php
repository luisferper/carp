<?php

function getTheMenu(){

$dataSite = getDataSite();
$exp = explode('|', $dataSite);

$add = '';
if (isAdmin() == TRUE){

  $add = '
                    <li class="nav-item"><a class="nav-link" href="'.URLSITE.'insumos"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-ipad-horizontal-pause" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M13 20h-8a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7" /><path d="M9 17h4" /><path d="M17 17v5" /><path d="M21 17v5" /></svg> 
                        <span class="nav-link-title">Insumos</span></a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="'.URLSITE.'categorias"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-float-right" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M14 5m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M4 7l6 0" /><path d="M4 11l6 0" /><path d="M4 15l16 0" /><path d="M4 19l16 0" /></svg>
                        <span class="nav-link-title">Categorias</span></a>
                    </li>


                    <li class="nav-item"><a class="nav-link" href="'.URLSITE.'usuarios"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-bolt" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4c.267 0 .529 .026 .781 .076" /><path d="M19 16l-2 3h4l-2 3" /></svg>
                        <span class="nav-link-title">Usuarios</span></a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="'.URLSITE.'informes"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-table-options" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 21h-7a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7" /><path d="M3 10h18" /><path d="M10 3v18" /><path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19.001 15.5v1.5" /><path d="M19.001 21v1.5" /><path d="M22.032 17.25l-1.299 .75" /><path d="M17.27 20l-1.3 .75" /><path d="M15.97 17.25l1.3 .75" /><path d="M20.733 20l1.3 .75" /></svg>
                        <span class="nav-link-title">Informes</span></a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="'.URLSITE.'configuracion"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings-cog" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12.003 21c-.732 .001 -1.465 -.438 -1.678 -1.317a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c.886 .215 1.325 .957 1.318 1.694" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19.001 15.5v1.5" /><path d="M19.001 21v1.5" /><path d="M22.032 17.25l-1.299 .75" /><path d="M17.27 20l-1.3 .75" /><path d="M15.97 17.25l1.3 .75" /><path d="M20.733 20l1.3 .75" /></svg>
                        <span class="nav-link-title">Configuraci&oacute;n</span></a>
                    </li>
  ';

}

echo'
<header class="navbar navbar-expand-md d-print-none">
  <div class="container-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
    </button>
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
      <a href="'.URLSITE.'"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box-padding" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M8 16v.01" /><path d="M8 12v.01" /><path d="M8 8v.01" /><path d="M16 16v.01" /><path d="M16 12v.01" /><path d="M16 8v.01" /><path d="M12 8v.01" /><path d="M12 16v.01" /></svg> '.$exp[0].'</a>
    </h1>
  </div>
</header>
<header class="navbar-expand-md">
        <div class="collapse navbar-collapse" id="navbar-menu">
          <div class="navbar">
            <div class="container-xl">
              <div class="row flex-fill align-items-center">
                <div class="col">
                  <ul id="sbmnu" class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="'.URLSITE.'inicio"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M10 12h4v4h-4z" /></svg>
                        <span class="nav-link-title">Inicio</span></a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="'.URLSITE.'mesas"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-salad"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 11h16a1 1 0 0 1 1 1v.5c0 1.5 -2.517 5.573 -4 6.5v1a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-1c-1.687 -1.054 -4 -5 -4 -6.5v-.5a1 1 0 0 1 1 -1z" /><path d="M18.5 11c.351 -1.017 .426 -2.236 .5 -3.714v-1.286h-2.256c-2.83 0 -4.616 .804 -5.64 2.076" /><path d="M5.255 11.008a12.204 12.204 0 0 1 -.255 -2.008v-1h1.755c.98 0 1.801 .124 2.479 .35" /><path d="M8 8l1 -4l4 2.5" /><path d="M13 11v-.5a2.5 2.5 0 1 0 -5 0v.5" /></svg>
                        <span class="nav-link-title">Mesas</span></a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="'.URLSITE.'ordenes"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-description" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                        <span class="nav-link-title">Ordenes</span></a>
                    </li>
                    '.$add.'
                    <li class="nav-item"><a class="nav-link" href="'.URLSITE.'salir"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-lock-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M13 21h-6a2 2 0 0 1 -2 -2v-6a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v.5" /><path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M8 11v-4a4 4 0 1 1 8 0v4" /><path d="M22 22l-5 -5" /><path d="M17 22l5 -5" /></svg>
                        <span class="nav-link-title">Cerrar Sesi&oacute;n</span></a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>';
}



function insumosModulo(){

  echo '
    <div class="container mt-4">
     <div class="row">
         
         <div class="col-md-12">
            <h1 class="float-start">Insumos</h1> <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" type="button" class="btn btn-warning float-end"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-big-up-lines" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 12h-3.586a1 1 0 0 1 -.707 -1.707l6.586 -6.586a1 1 0 0 1 1.414 0l6.586 6.586a1 1 0 0 1 -.707 1.707h-3.586v3h-6v-3z" /><path d="M9 21h6" /><path d="M9 18h6" /></svg> Agregar Nuevo Insumo</button>
         </div>
         <div class="col-md-12">
           <div class="card">
             <div class="card-body">

                 <table id="insumosTable" class="table">
                   <thead>
                     <tr>
                       <th scope="col">Nombre</th>
                       <th scope="col">Descripci&oacute;n</th>
                       <th scope="col">Codigo</th>
                       <th scope="col">Precio</th>
                       <th scope="col">Categoria</th>
                       <th scope="col">Acciones</th>
                     </tr>
                   </thead>
                   <tbody>
                   </tbody>
                 </table>

             </div>
           </div>
         </div>
     </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Agregar Nuevo Insumo</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <form id="newInsuForm">
           <input type="hidden" name="p" value="9">
           <label>Nombre:</label>
           <input type="text" class="form-control" name="nombre">
           <label class="mt-2">Descripcion:</label>
           <input type="text" class="form-control" name="descripcion">
           <label class="mt-2">Codigo:</label>
           <input type="text" class="form-control" name="codigo">
           <label class="mt-2">Precio:</label>
           <input type="text" class="form-control" name="precio">
           <label class="mt-2">Categoria:</label>
           <select class="form-control" name="categoria">
           '.rtnCate().'
           </select>
         </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="saveNewInsu();">Guardar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal modal-blur fade" id="modal-danger" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-danger"></div>
          <div class="modal-body text-center py-4">
            <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            <h3>¿Estas seguro de eliminar este registro?</h3>
            <div class="text-secondary">Este proceso no se puede deshacer.</div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <div class="row">
                <div class="col"><a id="finalDeleteInsu" href="#" class="btn btn-danger w-100" data-bs-dismiss="modal">
                    Si Eliminar
                  </a></div>
                <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                    Cancelar
                  </a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

';
}


function categoiasModulo(){

  echo '
    <div class="container mt-4">
     <div class="row">
         
         <div class="col-md-12">
            <h1 class="float-start">Categorias</h1> <button data-bs-toggle="modal" data-bs-target="#staticBackdrop" type="button" class="btn btn-warning float-end"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-big-up-lines" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 12h-3.586a1 1 0 0 1 -.707 -1.707l6.586 -6.586a1 1 0 0 1 1.414 0l6.586 6.586a1 1 0 0 1 -.707 1.707h-3.586v3h-6v-3z" /><path d="M9 21h6" /><path d="M9 18h6" /></svg> Agregar Nueva Categoria</button>
         </div>
         <div class="col-md-12">
           <div class="card">
             <div class="card-body">
                 <table id="catewTable" class="table">
                   <thead>
                     <tr>
                       <th scope="col">Nombre</th>
                       <th scope="col">Comanda</th>
                       <th scope="col">Acciones</th>
                     </tr>
                   </thead>
                   <tbody>
                   </tbody>
                 </table>
             </div>
           </div>
         </div>
     </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Agregar Nuevo Insumo</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <form id="newCateForm">
           <input type="hidden" name="p" value="13">
           <label>Nombre:</label>
           <input type="text" class="form-control" name="nombre">
           <label class="mt-1">Comanda:</label>
           <select class="form-control" name="comanda">
             <option value="1">No</option>
             <option value="2">Si</option>
           </select>
         </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="saveNewCate();">Guardar</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal modal-blur fade" id="modal-danger" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-danger"></div>
          <div class="modal-body text-center py-4">
            <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" /><path d="M12 16h.01" /></svg>
            <h3>¿Estas seguro de eliminar este registro?</h3>
            <div class="text-secondary">Este proceso no se puede deshacer.</div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <div class="row">
                <div class="col"><a id="finalDeleteCate" href="#" class="btn btn-danger w-100" data-bs-dismiss="modal">
                    Si Eliminar
                  </a></div>
                <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                    Cancelar
                  </a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

';
}