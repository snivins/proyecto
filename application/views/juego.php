<?php template_set('title', 'Rentoy time') ?>

<div id="partida">
  <h3>Situación actual jugador 1</h3>
  <p id="estado1"><p>
  <p id="estado_partida"><p>
  <h3>Situación actual jugador 2</h3>
  <p id="estado2"><p>
  <h3>Situación actual jugador 3</h3>
  <p id="estado3"><p>
  <h3>Situación actual jugador 4</h3>

  <button id="unirse" >Unirse a partida</button>
  <p id="estado4"><p>
</div>

<script>
var en_partida = false;
$(document).ready(function() {
  getEstado();
    getEstado2();
      getEstado3();
        getEstado4();
  setInterval(function(){
    getEstado();
      getEstado2();
        getEstado3();
          getEstado4();
  }, 3000);

  $("#unirse").click(function() {
    $.post("<?= base_url() ?>" + 'juegos/unirse_partida', {
      id : "4",
      id_partida: "1"
    },darOK);
  });

});
function darOK(r){
  alert("yay");
  alert($.parseJSON(r));
}




function getEstado(){
  if (!en_partida){
    $.post("<?= base_url() ?>" + 'juegos/get_estado', {
      id : "1"
    } ,pintar);
  } else {
    $.post("<?= base_url() ?>" + 'juegos/get_estado_partida', {
      id_partida : "1",
      id : "1"
    } ,pintar_partida);
  }
}

function pintar_partida(r) {
  var estado = $.parseJSON(r);
  $('#estado_partida').text(estado.juega);
}


function getEstado2(){
  $.post("<?= base_url() ?>" + 'juegos/get_estado', {
    id : "2"
  } ,pintar2);
}
function getEstado3(){
  $.post("<?= base_url() ?>" + 'juegos/get_estado', {
    id : "3"
  } ,pintar3);
}
function getEstado4(){
  $.post("<?= base_url() ?>" + 'juegos/get_estado', {
    id : "4"
  } ,pintar4);
}
function pintar(r){
    $('#estado1').empty();
    var estado = $.parseJSON(r);
    if (estado != null) {
      if (estado['estado'] == "creada") {
        $('#estado1').text('Esperando jugadores  ');
        en_partida = false;
      } else if (estado['estado'] == "jugando"){
        $('#estado1').text('En partida  ');
        en_partida = true;
      }
    } else {
      en_partida = false;
      $('#estado1').text('En menu');
    }
}
function pintar2(r){
    $('#estado2').empty();
    var estado = $.parseJSON(r);
    if (estado != null) {
      if (estado['estado'] == "creada") {
        $('#estado2').text('Esperando jugadores  ');
      } else if (estado['estado'] == "jugando"){
        $('#estado2').text('En partida  ');
      }
    } else {
      $('#estado2').text('En menu');
    }
}
function pintar3(r){
    $('#estado3').empty();
    var estado = $.parseJSON(r);
    if (estado != null) {
      if (estado['estado'] == "creada") {
        $('#estado3').text('Esperando jugadores  ');
      } else if (estado['estado'] == "jugando"){
        $('#estado3').text('En partida  ');
      }
    } else {
      $('#estado3').text('En menu');
    }
}
function pintar4(r){
    $('#estado4').empty();
    var estado = $.parseJSON(r);
    if (estado != null) {
      if (estado['estado'] == "creada") {
        $('#estado4').text('Esperando jugadores  ');
      } else if (estado['estado'] == "jugando"){
        $('#estado4').text('En partida  ');
      }
    } else {
      $('#estado4').text('En menu');
    }
}

</script>
