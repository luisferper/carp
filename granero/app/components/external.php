<?php

function loginForm(){
  $dataSite = getDataSite();
  $exp = explode('|', $dataSite);
  echo '
      <div class="container container-tight py-4">
        <div class="card card-md mt-8">
          <div class="card-body p-4">
            <form id="loginForm">
              <img class="indx-logo" src="'.URLSITE.'images/logo.png">
              <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="hidden" name="p" value="1">
                <input type="text" name="upu" class="form-control" placeholder="Usuario" autocomplete="off">
              </div>
              <div class="mb-1">
                <label class="form-label">
                  Contrase&ntilde;a
                </label>
                <div class="input-group input-group-flat">
                  <input type="password" name="upp" class="form-control" placeholder="Contrase&ntilde;a?" autocomplete="off">
                </div>
              </div>
              <div class="form-footer">
                <button type="button" class="loginbtn btn btn-primary w-100">Iniciar Sesi&oacute;n</button>
                <a href="'.URLSITE.'orders/list" class="btn btn-warning w-100 mt-3">Ordenes</a>
              </div>
              <a class="w-100 mt-3 text-center btn-block" data-bs-toggle="modal" data-bs-target="#recoveryModal">Olvidaste tu Contrase&ntilde;a?</a>
            </form>
            <div id="loader" class="py-4">
              <div class="text-secondary mb-3 text-center">Iniciando Sesi&oacute;n...</div>
              <div class="progress progress-sm"><div class="progress-bar progress-bar-indeterminate"></div></div>
            </div>
          </div>
        </div>
        <small class="text-center btn-block mt-4"><a target="_blank" href="https://xparrowsys.com/comunidad/">Xparrow Systems de M&eacute;xico</a></small>
      </div>


<!-- Modal -->
<div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">&nbsp;</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <form id="recoveryForm">
           <div class="input-group mb-3">
             <span class="input-group-text">
             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
             </span>
             <input type="hidden" name="p" value="32">
             <input type="text" class="form-control text-center" name="usrmail" placeholder="Correo Electronico">
           </div>
           <a class="btn btn-warning float-end recoverybtn">Recuperar Contrase&ntilde;a</a>
         </form>
         <div id="loader-recove" class="py-4">
           <div class="text-secondary mb-3 text-center">Espere un momento...</div>
           <div class="progress progress-sm"><div class="progress-bar progress-bar-indeterminate"></div></div>
         </div>

      </div>
    </div>
  </div>
</div>



      ';
}