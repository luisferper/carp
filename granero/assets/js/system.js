// Notificaciones
function noti(text,style){
   var html, cls;
   if (style == 1){csl = 'alert-success';}else if (style == 2){csl = 'alert-warning';}else{csl = 'alert-danger';}
   $('#notification').remove();
   html = '<div id="notification" class="alert '+csl+' animate_animated animate__bounceIn" role="alert">'+text+'</div>';
   $('body').append(html);
   setTimeout(function(){$('#notification').removeClass('animate__bounceIn').addClass('animate__fadeOutUp').fadeOut();},4000);
}

$('.loginbtn').on('click', function(){
     var dataFrm = $('#loginForm').serialize();
     $.ajax({
         type: 'POST',
         url: baseurl+'ajax',
         data: dataFrm,
         beforeSend: function(){
         	$('#loginForm').hide();
         	$('#loader').show();
         },
         success: function(res){
            if (res == 1){
              noti('Todos los campos son obligatorios.', 3);
         	  $('#loader').hide();
         	  $('#loginForm').show();
            }else if(res == 2){
              noti('Inicio de sesión incorrecto, revisa tus datos de acceso.', 2);
         	  $('#loader').hide();
         	  $('#loginForm').show();
            }else if(res == 3){
              location.reload();
            }
         },
         error: function(){
           noti('Revisa tu conexion a internet.', 3);
         }
     });
});

$('.recoverybtn').on('click', function(){
     var dataFrm = $('#recoveryForm').serialize();
     $.ajax({
         type: 'POST',
         url: baseurl+'ajax',
         data: dataFrm,
         beforeSend: function(){
          $('#recoveryForm').hide();
          $('#loader-recove').show();
         },
         success: function(res){
            if (res == 1){
              noti('Todos los campos son obligatorios.', 3);
            $('#loader-recove').hide();
            $('#recoveryForm').show();
            }else if(res == 2){
              noti('La recuperacion de cuentas por correo no esta habilitado.', 2);
              $('#loader-recove').hide();
              $('#recoveryForm').show();
            }else if(res == 3){
              noti('Se te ha enviado un correo con tu nueva contraseña.', 1);
              $('#loader-recove').hide();
              $('#recoveryForm').show();
              $('#recoveryForm')[0].reset();

              var myModalEl = document.getElementById('recoveryModal');
              var modal = bootstrap.Modal.getInstance(myModalEl)
              modal.hide();

            }
         },
         error: function(){
           noti('Revisa tu conexion a internet.', 3);
         }
     });
});




$('.opentable').on('click', function(){
   var mesa = $(this).attr('data-mesa');
   var dataFrm = 'p=2&mesa='+mesa;
     $.ajax({
         type: 'POST',
         url: baseurl+'ajax',
         data: dataFrm,
         beforeSend: function(){
            $('#bigloader').show();
            $('.opentable').prop('disabled', true);
         },
         success: function(res){
            setTimeout(function(){
               window.location.href = baseurl+'mesa/'+res;
            }, 1000);
         },
         error: function(){
            noti('Revisa tu conexion a internet.', 3);
            $('.opentable').prop('disabled', false);
         }
     });
});


$('.openaccont').on('click', function(){
   var dataFrm = $('#formAccount').serialize();
     $.ajax({
         type: 'POST',
         url: baseurl+'ajax',
         data: dataFrm,
         beforeSend: function(){
            $('#bigloader').show();
         },
         success: function(res){
            if (res == 1){
              noti('Todos los campos son obligatorios.', 3);
              $('#bigloader').hide();
            }else{
              setTimeout(function(){
                 window.location.href = baseurl+'cuenta/'+res;
              }, 1000);
            }
         },
         error: function(){
            noti('Revisa tu conexion a internet.', 3);
            $('#bigloader').hide();
         }
     });
});


function adProTable(id){

    var prec = $('#addbutton'+id).attr('data-price');
    var name = $('#nmins'+id).text();
    var dom = $('#tableInsuAdd');
    var npshdt = null;
    if (miles == 1){
       npshdt = formatearNumero(prec);
    }else{
       npshdt = prec;
    }

    var html = '<div id="coInDa'+id+'" class="insaddobjt col-md-3 col-sm-12 mb-3"><div class="card"><div class="card-body"><a class="deliverySt noIE" data-status="1"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M13 17h-9a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6a2 2 0 1 1 4 0a7 7 0 0 1 4 6v2" /><path d="M9 17v1a3 3 0 0 0 4.194 2.753" /><path d="M22 22l-5 -5" /><path d="M17 22l5 -5" /></svg></a><h4 class="text-center ttladd">'+name+'</h4><div class="input-group mb-2"><input type="text" id="noteInsAcc'+id+'" class="form-control" aria-describedby="button-addon2"><button onclick="saveNote('+id+')" class="btn btn-outline-secondary text-center" type="button" id="button-addon2">G</button></div><div class="input-group"><button onclick="minus('+id+');" class="btn btn-outline-danger" type="button">-</button><input id="inptCant'+id+'" type="text" class="form-control text-center" value="1" disabled><button onclick="maximus('+id+');" class="btn btn-outline-success" type="button">+</button></div><h2 class="totalAccIns'+id+' subtotalpro mt-2 text-center" data-subtl="'+prec+'">Total: $'+npshdt+' <small>'+divisa+'</small></h2></div></div></div>';
    
    if ($('#coInDa'+id).length){
      maximus(id);
    }else{
      dom.append(html);
    }

    lectString();

    $('.deliverySt').on('click', function(){
        var status = $(this).attr('data-status');
        if (status == 1){
          $(this).attr('data-status', 2).removeClass('noIE').addClass('yesIE').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M11.5 17h-7.5a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3c.016 .129 .037 .256 .065 .382" /><path d="M9 17v1a3 3 0 0 0 2.502 2.959" /><path d="M15 19l2 2l4 -4" /></svg>');
        }
        getAllToDB();
    });

};

$('.deliverySt').on('click', function(){
    var status = $(this).attr('data-status');
    if (status == 1){
      $(this).attr('data-status', 2).removeClass('noIE').addClass('yesIE').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M11.5 17h-7.5a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3c.016 .129 .037 .256 .065 .382" /><path d="M9 17v1a3 3 0 0 0 2.502 2.959" /><path d="M15 19l2 2l4 -4" /></svg>');
    }
    getAllToDB();
});

function saveNote(id){
   lectString();
}

function lectString(){
    var cant = $('.insaddobjt').length;
    if (cant == 0){
      $('#totalAccountSingle').html('$0'+' <small>'+divisa+'</small>');
      getAllToDB();
      return;
    }
    var lmt = cant + 1;
    var data, sum = null;
    for (var i = 1; i < lmt; i++){
       data = $('.insaddobjt:nth-child('+i+') .subtotalpro').attr('data-subtl');
       sum += parseFloat(data);
    }

    // Decimales
    if (dcmls == 1){
    //-------------------------------------------------------

       if (prcpresent > 0){
          var ptt = sum.toFixed(2);
          var optt = parseFloat(ptt) * parseInt(prcpresent) / 100;
          var smtt = parseFloat(ptt) + parseFloat(optt);
          var thesm = smtt.toFixed(2); 
       }else{
          var thesm = sum.toFixed(2); 
       }

    //-------------------------------------------------------
    }else{
    //-------------------------------------------------------

       if (prcpresent > 0){
          var ptt = sum;
          var optt = parseInt(ptt) * parseInt(prcpresent) / 100;
          var smtt = parseInt(ptt) + parseInt(optt);
          var thesm = smtt; 
       }else{
          var thesm = sum; 
       }

    //-------------------------------------------------------
    }
    
    if (sum == null){
       $('#totalAccountSingle').html('$0'+' <small>'+divisa+'</small>');
    }else{
       if (miles == 1){
          $('#totalAccountSingle').html('$'+formatearNumero(thesm)+' <small>'+divisa+'</small>');
       }else{
          $('#totalAccountSingle').html('$'+thesm+' <small>'+divisa+'</small>');
       }
       
    }
    getAllToDB();
}

function getAllToDB(){
   var dt = $('.insaddobjt').length;
   var limit = dt + 1;
   var fnlstring = '';
   var stsu = null;
   for (var i = 1; i < limit; i++){

     var strdata = $('.insaddobjt:nth-child('+i+')').attr('id');
     var rpls = strdata.replace('coInDa', '');
     var ctn = $('#inptCant'+rpls).val();
     var stus = $('.insaddobjt:nth-child('+i+') .deliverySt').attr('data-status');
     var txtdtls = $('#noteInsAcc'+rpls).val();

     fnlstring += rpls+'|'+ctn+'|'+btoa(txtdtls)+'|'+stus+'#';

   }
   var cuenta = $('#stringAccount').val();
   var mesa = $('#stringMesa').val();
   saveAccount(mesa, fnlstring, cuenta);

}

function formatearNumero(numero){
    return new Intl.NumberFormat("es-CL").format(numero);
}

function minus(id){
    var prc = $('#addbutton'+id).attr('data-price');
    var inpC = $('#inptCant'+id).val();
    var sum = parseInt(inpC) - 1;
    if (sum == 0){
      $('#coInDa'+id).remove();
      lectString();
      return;
    }

    $('#inptCant'+id).val(sum);
    
    // Decimales
    if (dcmls == 1){
      var updPr = sum * parseFloat(prc).toFixed(2);
      $('.totalAccIns'+id).attr('data-subtl', updPr.toFixed(2));
      $('.totalAccIns'+id).html('Total: $'+updPr.toFixed(2)+' <small>'+divisa+'</small>');
    }else{
      var updPr = sum * parseInt(prc);
      $('.totalAccIns'+id).attr('data-subtl', updPr);
      if (miles == 1){
         $('.totalAccIns'+id).html('Total: $'+formatearNumero(updPr)+' <small>'+divisa+'</small>');
      }else{
         $('.totalAccIns'+id).html('Total: $'+updPr+' <small>'+divisa+'</small>');
      }

    }

    lectString();
}

function maximus(id){
    var prc = $('#addbutton'+id).attr('data-price');
    var inpC = $('#inptCant'+id).val();
    var sum = parseInt(inpC) + 1;
    $('#inptCant'+id).val(sum);
    
    // Decimales
    if (dcmls == 1){
      var updPr = sum * parseFloat(prc).toFixed(2);
      $('.totalAccIns'+id).attr('data-subtl', updPr.toFixed(2));
      $('.totalAccIns'+id).html('Total: $'+updPr.toFixed(2)+' <small>'+divisa+'</small>');
    }else{
      var updPr = sum * parseInt(prc);
      $('.totalAccIns'+id).attr('data-subtl', updPr);
      if (miles == 1){
        $('.totalAccIns'+id).html('Total: $'+formatearNumero(updPr)+' <small>'+divisa+'</small>');
      }else{
        $('.totalAccIns'+id).html('Total: $'+updPr+' <small>'+divisa+'</small>');
      }

    }

    lectString();
}


function saveAccount(mesa,string,cuenta){
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=4&savetable='+mesa+'&string='+string+'&cuenta='+cuenta,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}


$(document).ready(function() {

        if ($('#insumosAddTable').length){
           new DataTable('#insumosAddTable', {
               responsive: {
                   details: {
                       display: DataTable.Responsive.display.childRowImmediate,
                       target: '',
                       type: 'none'
                   }
               },
               ajax: {
                   url: baseurl+'getAllInsumos',
                   cache: true
               }
           });
        }

        if ($('#insumosTable').length){
           new DataTable('#insumosTable', {
               responsive: {
                   details: {
                       display: DataTable.Responsive.display.childRowImmediate,
                       target: '',
                       type: 'none'
                   }
               },
               ajax: {
                   url: baseurl+'getInsumos',
                   cache: true
               }
           });
        }

        if ($('#catewTable').length){
           new DataTable('#catewTable', {
               responsive: {
                   details: {
                       display: DataTable.Responsive.display.childRowImmediate,
                       target: '',
                       type: 'none'
                   }
               },
               ajax: {
                   url: baseurl+'getCates',
                   cache: true
               }
           });
        }

        if ($('#usersTable').length){
           new DataTable('#usersTable', {
               responsive: {
                   details: {
                       display: DataTable.Responsive.display.childRowImmediate,
                       target: '',
                       type: 'none'
                   }
               },
               ajax: {
                   url: baseurl+'getUsers',
                   cache: true
               }
           });
        }


        if ($('#vddTable').length){
           new DataTable('#vddTable', {
               responsive: {
                   details: {
                       display: DataTable.Responsive.display.childRowImmediate,
                       target: '',
                       type: 'none'
                   }
               },
               ajax: {
                   url: baseurl+'getVentasDay',
                   cache: true
               }
           });
        }

        $(function(){
            $("#fdpicker").datepicker({
               dateFormat: "dd/mm/yy"
            });
        });

});


// Cerrar Cuentas Single
$('.closeaccounta').on('click', function(){
  var idaccont = $(this).attr('data-cuenta');
  $('#cancelAccBtnSuccess').attr('onclick', 'cancelAccountSingle('+idaccont+');');
});

// Cerrar Mesa
$('.closeaccount').on('click', function(){
  var idaccont = $(this).attr('data-cuenta');
  $('#cancelAccBtnSuccess').attr('onclick', 'cancelMesaSingle('+idaccont+');');
});

function cancelAccountSingle(cuenta){
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=5&cuenta='+cuenta,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if(res == 1){
            noti('Solo el encargado puede eliminar esta cuenta.', 3);  
            $('#bigloader').hide();
         }else{
            window.location.href = res;
         }
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function cancelMesaSingle(cuenta){
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=8&id='+cuenta,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if(res == 1){
            noti('Solo el encargado puede eliminar esta cuenta.', 3);  
            $('#bigloader').hide();
         }else{
            window.location.href = baseurl+'mesas';
         }
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}


function popitup(url, windowName){
       newwindow=window.open(url,windowName,'height=600,width=350');
       if (window.focus) {newwindow.focus()}
       return false;
}

$('.printaccount').on('click', function(){
   var hash = $(this).attr('data-cuenta');
   popitup(baseurl+'impcuenta/'+hash, 'Imprimir Cuenta');
});

function payAccount(){

  var prevap = $('#canpayval').val();
  var premnp = $('#valueaccpay').val();

  if (prevap.length == 0){
    prevap = 0;
  }

  if (dcmls == 1){
    var paga = parseFloat(prevap).toFixed(2);
    var total = parseFloat(premnp).toFixed(2);
  }else{
     if (miles == 1){
        if (prevap != 0){
         var paga = prevap.replace('.', '');
        }else{
          var paga = 0;
        }
        var total = premnp;
     }else{
    var paga = parseInt(prevap);
    var total = parseInt(premnp);
     }
  }
  

  var method = $('#methodPaySelect').val();
  $('#bigloader').show();
  
  if (method == 1){

     if (dcmls == 1){
       console.log('decimales activo');
       if (parseFloat(paga) < parseFloat(total)){noti('La cantidad es menor a la de la cuenta.', 3);$('#bigloader').hide();return;}
     }else{
        if (miles == 1){
          console.log('miles activo');
          if (parseInt(paga) < parseInt(total)){noti('La cantidad es menor a la de la cuenta.', 3);$('#bigloader').hide();return;}
        }else{
         console.log('sin decimales pero tampoco miles');
         if (parseInt(paga) < parseInt(total)){noti('La cantidad es menor a la de la cuenta.', 3);$('#bigloader').hide();return;}        
        }
     }
  }

  var frm = $('#PayForm').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=6&'+frm,
      success: function(res){
          if (res == 1){
            noti('Debes de ingregar una cantidad para pagar la cuenta.', 3);
            $('#bigloader').hide();
          }else{
            window.location.href = baseurl+'ventac/'+res;
          }
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });

}

function payTable(){

  var prevap = $('#canpayval').val();
  var premnp = $('#valueaccpay').val();
  var paga = parseFloat(prevap);
  var total = parseFloat(premnp);
  $('#bigloader').show();

  if (paga < total){
     noti('La cantidad es menor a la de la cuenta.', 3);
     $('#bigloader').hide();
     return;
  }else{
  var frm = $('#PayForm').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=7&'+frm,
      success: function(res){
          if (res == 1){
            noti('Debes de ingregar una cantidad para pagar la cuenta.', 3);
            $('#bigloader').hide();
          }else{
            window.location.href = baseurl+'ventac/'+res;
          }
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });

  }

}


function PrintTicketPay(){
  var hash = $('#hashventa').val();
  popitup(baseurl+'ticket/'+hash, 'Imprimir Cuenta');
}


function saveNewInsu(){
  var data = $('#newInsuForm').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: data,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if (res == 1){
           noti('Todos los campos son obligatorios.', 3);
           $('#bigloader').hide();
         }else{
           location.reload();
         }
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function editarInsumo(id){
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=10&id='+id,
      success: function(res){
         $('#appdata').html(res);
         var myModal = new bootstrap.Modal(document.getElementById('editInsumoModal'))
         myModal.show();

         $('#usestocke').on('change', function(){
             var vl = $(this).val();
             var dom = $('#stockcnte');
             if (vl == 1){
               dom.prop('disabled', false);
             }else{
               dom.prop('disabled', true);
             }
         });


      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function InsumoUpdate(){
  var datains = $('#editInsuForm').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if (res == 1){
           noti('Todos los campos son obligatorios.', 3);
           $('#bigloader').hide();
         }else{
           location.reload();
         }
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function deleteInsumo(id){
   var myModal = new bootstrap.Modal(document.getElementById('modal-danger'))
   myModal.show();
   $('#finalDeleteInsu').attr('onclick', 'finalDeleteInsu('+id+');');
}

function finalDeleteInsu(id){
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=12&id='+id,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
           location.reload();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

// Categorias
function saveNewCate(){
  var data = $('#newCateForm').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: data,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if (res == 1){
           noti('Todos los campos son obligatorios.', 3);
           $('#bigloader').hide();
         }else{
           location.reload();
         }
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function editarCategoria(id){
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=14&id='+id,
      success: function(res){
         $('#appdata').html(res);
         var myModal = new bootstrap.Modal(document.getElementById('editCateModal'))
         myModal.show();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function CateUpdate(){
  var datains = $('#editCateForm').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if (res == 1){
           noti('Todos los campos son obligatorios.', 3);
           $('#bigloader').hide();
         }else{
           location.reload();
         }
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function deleteCategoria(id){
   var myModal = new bootstrap.Modal(document.getElementById('modal-danger'))
   myModal.show();
   $('#finalDeleteCate').attr('onclick', 'finalDeleteCate('+id+');');
}

function finalDeleteCate(id){
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=16&id='+id,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
           location.reload();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

//***********************************************************************************

function editarUser(id){
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=17&id='+id,
      success: function(res){
         $('#appdata').html(res);
         var myModal = new bootstrap.Modal(document.getElementById('editUserModal'))
         myModal.show();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}


function deleteUser(id){
   var myModal = new bootstrap.Modal(document.getElementById('modal-danger'))
   myModal.show();
   $('#cancelUsrBtnSuccess').attr('onclick', 'cancelUsrBtnSuccess('+id+');');
}


function saveNewUsuario(){
  var data = $('#newUserForm').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: data,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if (res == 1){
           noti('Todos los campos son obligatorios.', 3);
           $('#bigloader').hide();
         }
         else if (res == 2){
           noti('El usuario que quieres registrar ya existe, intenta con otro.', 2);
           $('#bigloader').hide();
         }else if (res == 3){
           noti('El correo electronico que quieres registrar ya existe, intenta con otro.', 2);
           $('#bigloader').hide();
         }else{
           noti('Ususrio Registrado!', 1);
           if ($.fn.DataTable.isDataTable('#usersTable')) {
               $('#usersTable').dataTable().fnClearTable();
               $('#usersTable').dataTable().fnDestroy();
           }
           new DataTable('#usersTable', {
               responsive: {
                   details: {
                       display: DataTable.Responsive.display.childRowImmediate,
                       target: '',
                       type: 'none'
                   }
               },
               ajax: {
                   url: baseurl+'getUsers',
                   cache: true
               }
           });
           var myModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('staticNewUsersModal'));
           myModal.hide();
           $('#newUserForm')[0].reset();
           $('#bigloader').hide();
         }
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function userUpdate(){
  var datains = $('#editUserForm').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if (res == 1){
           noti('Todos los campos son obligatorios.', 3);
         }else{
           location.reload();
         }
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}


function cancelUsrBtnSuccess(id){
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=19&id='+id,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
           location.reload();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function saveConfig(){
  var datains = $('#frmConfigData').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if (res == 1){
           noti('Todos los campos son obligatorios.', 3);
         }else{
           noti('Cambios Guardados Correctamente.', 1);
         }
         $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function saveConfigStyles(){
  var datains = $('#formcfgstyles').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if (res == 1){
           noti('Todos los campos son obligatorios.', 3);
         }else{
           noti('Cambios Guardados Correctamente.', 1);
         }
         $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function changeimgConf(){
   $('#ticc').click();
}

$('#ticc').on('change', function(){
  var datains = new FormData($("#imgfrmsys")[0]);
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      contentType: false,
      processData: false,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         
         $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
});

function rtnImgDefaultLogo(){
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=24',
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         noti('Se ha cambiado al logo original del sistema.', 3);
         $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

$('#methodPaySelect').on('change', function(){
  var chk = $(this).val();
  var dm = $('#canpayval');
  if (chk == 2){
    dm.hide();
  }else{
    dm.show();
  }
});


function savePercent(){
  var datains = $('#frmpercent').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if (res == 1){
           noti('Todos los campos son obligatorios.', 3);
         }else if(res == 2){
           noti('El porcentaje no puede ser mayor a 25.', 2);
         }else{
           noti('Cambios Guardados Correctamente.', 1);
         }
         $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function saveDivisa(){
  var datains = $('#frmdivisa').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if (res == 1){
           noti('Todos los campos son obligatorios.', 3);
         }else{
           noti('Cambios Guardados Correctamente.', 1);
         }
         $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function DonwloadPerMesero(){
  var datains = $('#frmUsers').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         window.location.href = baseurl+'info/mesero';
         $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function DonwloadPerMesa(){
  var datains = $('#frmMesasInfo').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         window.location.href = baseurl+'info/mesa';
         $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function DonwloadPerFecha(){
  var datains = $('#frmFechaInfo').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         window.location.href = baseurl+'info/date';
         $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}


function ImprimirTicketPerFolio(){
  var folio = $('#ticketFolio').val();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=30&ticket='+folio,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         if (res == 1){
           noti('No hay registros de este Folio.', 3);
         }else{
           popitup(baseurl+'ticket/'+res, 'Imprimir Cuenta');
         }
         $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function refreshPage(){
  location.reload();
}

$('#shwArtModal').on('click', function(){

    if ($.fn.DataTable.isDataTable('#insumosAddTable')) {
        $('#insumosAddTable').dataTable().fnClearTable();
        $('#insumosAddTable').dataTable().fnDestroy();
    }

    if ($('#insumosAddTable').length){
        new DataTable('#insumosAddTable', {
           responsive: {
               details: {
                   display: DataTable.Responsive.display.childRowImmediate,
                    target: '',
                   type: 'none'
                }
           },
            ajax: {
                url: baseurl+'getAllInsumos',
                cache: true
            }
        });
    }

});

$('#usestock').on('change', function(){
    var vl = $(this).val();
    var dom = $('#stockcnt');
    if (vl == 1){
      dom.prop('disabled', false);
    }else{
      dom.prop('disabled', true);
    }
});

$('.slctchg').on('click', function(){
  var mesaopen = $('.mesasopen>button');
  var met = $(this);
  if (met.hasClass('btn-warning')){
     mesaopen.prop('disabled', false);
     met.removeClass('btn-warning').addClass('btn-primary').prop('disabled', false); 
  }else{
     mesaopen.prop('disabled', true);
     met.removeClass('btn-primary').addClass('btn-warning').prop('disabled', false);    
  }
});

$('.changemesanow').on('click', function(){

  var mesaopen = $('.mesasopen>button.btn-warning').attr('data-numesa');
  var idmesa = $('#heremesa').val();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=31&mesa='+mesaopen+'&idmesa='+idmesa,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
          location.reload();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });

});


// Unificacion de cuentas
$('.unifacc').on('click', function(){

var mydm = $(this);
var myacc = mydm.attr('data-id');
var mytbl = mydm.attr('data-mesa');

if (mydm.hasClass('btn-outline-success')){
   mydm.removeClass('btn-outline-success').addClass('btn-success');
}else{
   mydm.removeClass('btn-success').addClass('btn-outline-success');
}

});

function uniAccount(){
   var cnt = $('.accbodycard>.btn-success').length;
   var str = '';
   if (cnt == 0){
      noti('No hay cuentas seleccionadas.', 3);
   }else{
      $('.unifacc.btn-success').each(function(){
         str += $(this).attr('data-id')+'|';
      });
      console.log(str);
   }
}

function imptinsm(){
    $('#inptflinsu').val('');
    $('#inptflinsu').click();
}

$('#inptflinsu').on('change', function(){
  var datains = new FormData($("#imptinsm")[0]);
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      contentType: false,
      processData: false,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         $('#bigloader').hide();
         noti('Los insumos se han importado correctamente.', 1);
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
});


function restoreSystem(){
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=34',
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         $('#bigloader').hide();
         window.location.href = baseurl+'salir';
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

$('.smtpcontrol').on('change', function(){
    var valsmtp = $(this).val();
    if (valsmtp == 1){
       $('#smtpfrm *').prop('readonly', true);
       $('#smtpstatus').val(valsmtp);
       saveSMTP();
    }else{
       $('#smtpfrm *').prop('readonly', false);
       $('#smtpstatus').val(valsmtp);
    }
});

function saveSMTP(){
  var datains = $('#smtpfrm').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
         $('#bigloader').hide();
         noti('Cambios Guardados Correctamente.', 1);
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function createNewOrdenSingle(){
  var datains = $('#newSingleOrderForm').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: datains,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
        if (res == 1){
          noti('Todos los campos son obligatorios.', 3);
          $('#bigloader').hide();
        }else{
          setTimeout(function(){
             window.location.href = baseurl+'cuenta/'+res;
          }, 1000);
        }
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });
}

function saveNota(){
  var id = $('#idaccountnote').val();
  var nota = $('#noteintpdata').val();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: 'p=37&id='+id+'&nota='+nota,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
          noti('Nota guardada correctamente.', 1);
          $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });  
}

$('#miles').on('change', function(){
   var vale = $(this).val();
   var cntr = $('#decimals');
   var htmls = '';
   if (vale == 1){
     htmls = '<option value="2">No</option><option value="1">Si</option>';
   }else{
     htmls = '<option value="1">Si</option><option value="2">No</option>';
   }
   cntr.html(htmls);
});

$('#decimals').on('change', function(){
   var cntr = $('#miles').val();
   var html = '';
   if (cntr == 1){
     htmls = '<option value="2">No</option><option value="1">Si</option>';
     $('#decimals').html(htmls);
   }
});


function saveDcMls(){
  var frmdata = $('#formdcmls').serialize();
  $.ajax({
      type: 'POST',
      url: baseurl+'ajax',
      data: frmdata,
      beforeSend: function(){
         $('#bigloader').show();
      },
      success: function(res){
          noti('Ajustes guardados correctamente.', 1);
          $('#bigloader').hide();
      },
      error: function(){
         noti('Revisa tu conexion a internet.', 3);
         $('#bigloader').hide();
      }
  });  
}