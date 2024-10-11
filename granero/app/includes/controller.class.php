<?php

 Class Controller {

       public function ControlString($param, $action){

          // Descargar Insumos
         if ($param == 'download'){
             if ($action == 'insumos'){
                DownloadAllInsumos();
             }

             if ($action == 'database'){
                BackUpTables();
             }
            return;
         }

         // Para el ticket no necesitamos ni el header / footer
         if ($param == 'impcuenta'){
            showTicket($action);
            return;
         }
         
         // Ticket de la cuenta ya pagada
         if ($param == 'ticket'){
            showTicketVenta($action);
            return;
         }

         // Informe completo
         if ($param == 'info'){
            if ($action == 'complete') {
               getToAllInfo();
            }
            // Info por mesero
            if ($action == 'mesero') {
               getToAllInfoPerMesero();
            }
            // Info por mesa
            if ($action == 'mesa') {
               getToAllInfoPerMesa();
            }
            // Info del dia
            if ($action == 'day') {
               getToAllInfoPerDay();
            }
            // Info por Fecha
            if ($action == 'date') {
               getToAllInfoPerDate();
            }

            // Lo mas vendido del mes
            if ($action == 'topsales') {
               getToAllVendidoMes();
            }


            return;
         }

         if ($param == 'orders'){
            // sacamos el hash de la mesa
            if ($action == 'list') {
               getTheHeader($param);
               echo '<div class="page-wrapper">';
               OrdersPagev3();
               echo '</div>';
               getTheFooter();
               return;
            }
         }

         // Traemos la cabecera y el menu
         getTheHeader($param);
         getTheMenu();
         echo '<div class="page-wrapper">';

         if ($param == 'mesa'){
            // sacamos el hash de la mesa
            $hash = $action;
            mesaSingle($hash);
         }

         if ($param == 'cuenta'){
            // sacamos el hash de la mesa
            $hash = $action;
            cuentaSingle($hash);
         }

         if ($param == 'ventac'){
            // sacamos el hash de la mesa
            ventaAccSingle($action);
         }


         echo '</div>';
         // Traemos el pie de pagina
         getTheFooter();

       }

 }