<?php template_set('title', 'Rentoy time') ?>

<div id="partida">
<p id="cosas"><p>
  <h3>Situación actual jugador 1</h3>
  <button id="unirse1" >Unirse a partida</button>
  <p id="estado1"><p>
  <p id="estado_partida1"><p>
  <p>Carta1: <span id="1carta1"></span>, Carta2: <span id="1carta2"></span>, Carta3: <span id="1carta3"></span><p>
  <h3>Situación actual jugador 2</h3>
  <button id="unirse2" >Unirse a partida</button>
  <p id="estado2"><p>
  <p id="estado_partida2"><p>
  <p>Carta1: <span id="2carta1"></span>, Carta2: <span id="2carta2"></span>, Carta3: <span id="2carta3"></span><p>
  <h3>Situación actual jugador 3</h3>
  <button id="unirse3" >Unirse a partida</button>
  <p id="estado3"><p>
  <p id="estado_partida3"><p>
  <p>Carta1: <span id="3carta1"></span>, Carta2: <span id="3carta2"></span>, Carta3: <span id="3carta3"></span><p>
  <h3>Situación actual jugador 4</h3>
  <button id="unirse4" >Unirse a partida</button>
  <p id="estado4"><p>
  <p id="estado_partida4"><p>
  <p>Carta1: <span id="4carta1"></span>, Carta2: <span id="4carta2"></span>, Carta3: <span id="4carta3"></span><p>
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

  $("#unirse1").click(function() {
    $.post("<?= base_url() ?>" + 'juegos/unirse_partida', {
      id : "1",
      id_partida: "1"
    },darOK);
  });
  $("#unirse2").click(function() {
    $.post("<?= base_url() ?>" + 'juegos/unirse_partida', {
      id : "2",
      id_partida: "1"
    },darOK);
  });
  $("#unirse3").click(function() {
    $.post("<?= base_url() ?>" + 'juegos/unirse_partida', {
      id : "3",
      id_partida: "1"
    },darOK);
  });
  $("#unirse4").click(function() {
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
  if (estado.hasOwnProperty("jug_1")){
    var jugadorino = "jug_1";
    var estado_jug = $.parseJSON(estado.jug_1);
  }else if (estado.hasOwnProperty("jug_2")){
    var jugadorino = "jug_2";
    var estado_jug = $.parseJSON(estado.jug_2);
  }else if(estado.hasOwnProperty("jug_3")){
    var jugadorino = "jug_3";
    var estado_jug = $.parseJSON(estado.jug_3);
  }else if(estado.hasOwnProperty("jug_4")){
    var jugadorino = "jug_4";
    var estado_jug = $.parseJSON(estado.jug_4);
  }


  $('#estado_partida1').text(estado_jug.estado);
  $('#1carta1').text(estado_jug.carta_uno);
  if(estado_jug.hasOwnProperty("carta_dos")){
    $('#1carta2').text(estado_jug.carta_dos);
  } else {
    $('#1carta2').text("no tiene");
  }
  if(estado_jug.hasOwnProperty("carta_tres")){
    $('#1carta3').text(estado_jug.carta_tres);
  } else {
    $('#1carta3').text("no tiene");
  }
}

function pintar_partida2(r) {
  var estado = $.parseJSON(r);
  if (estado.hasOwnProperty("jug_1")){
    var jugadorino = "jug_1";
    var estado_jug = $.parseJSON(estado.jug_1);
  }else if (estado.hasOwnProperty("jug_2")){
    var jugadorino = "jug_2";
    var estado_jug = $.parseJSON(estado.jug_2);
  }else if(estado.hasOwnProperty("jug_3")){
    var jugadorino = "jug_3";
    var estado_jug = $.parseJSON(estado.jug_3);
  }else if(estado.hasOwnProperty("jug_4")){
    var jugadorino = "jug_4";
    var estado_jug = $.parseJSON(estado.jug_4);
  }


  $('#estado_partida2').text(estado_jug.estado);
  $('#2carta1').text(estado_jug.carta_uno);
  if(estado_jug.hasOwnProperty("carta_dos")){
    $('#2carta2').text(estado_jug.carta_dos);
  } else {
    $('#2carta2').text("no tiene");
  }
  if(estado_jug.hasOwnProperty("carta_tres")){
    $('#2carta3').text(estado_jug.carta_tres);
  } else {
    $('#2carta3').text("no tiene");
  }
}
function pintar_partida3(r) {
  var estado = $.parseJSON(r);
  if (estado.hasOwnProperty("jug_1")){
    var jugadorino = "jug_1";
    var estado_jug = $.parseJSON(estado.jug_1);
  }else if (estado.hasOwnProperty("jug_2")){
    var jugadorino = "jug_2";
    var estado_jug = $.parseJSON(estado.jug_2);
  }else if(estado.hasOwnProperty("jug_3")){
    var jugadorino = "jug_3";
    var estado_jug = $.parseJSON(estado.jug_3);
  }else if(estado.hasOwnProperty("jug_4")){
    var jugadorino = "jug_4";
    var estado_jug = $.parseJSON(estado.jug_4);
  }


  $('#estado_partida3').text(estado_jug.estado);
  $('#3carta1').text(estado_jug.carta_uno);
  if(estado_jug.hasOwnProperty("carta_dos")){
    $('#3carta2').text(estado_jug.carta_dos);
  } else {
    $('#3carta2').text("no tiene");
  }
  if(estado_jug.hasOwnProperty("carta_tres")){
    $('#3carta3').text(estado_jug.carta_tres);
  } else {
    $('#3carta3').text("no tiene");
  }
}
function pintar_partida4(r) {
  var estado = $.parseJSON(r);
  if (estado.hasOwnProperty("jug_1")){
    var jugadorino = "jug_1";
    var estado_jug = $.parseJSON(estado.jug_1);
  }else if (estado.hasOwnProperty("jug_2")){
    var jugadorino = "jug_2";
    var estado_jug = $.parseJSON(estado.jug_2);
  }else if(estado.hasOwnProperty("jug_3")){
    var jugadorino = "jug_3";
    var estado_jug = $.parseJSON(estado.jug_3);
  }else if(estado.hasOwnProperty("jug_4")){
    var jugadorino = "jug_4";
    var estado_jug = $.parseJSON(estado.jug_4);
  }


  $('#cosas').text(estado.jug_1);
  $('#estado_partida4').text(estado_jug.estado);
  $('#4carta1').text(estado_jug.carta_uno);
  if(estado_jug.hasOwnProperty("carta_dos")){
    $('#4carta2').text(estado_jug.carta_dos);
  } else {
    $('#4carta2').text("no tiene");
  }
  if(estado_jug.hasOwnProperty("carta_tres")){
    $('#4carta3').text(estado_jug.carta_tres);
  } else {
    $('#4carta3').text("no tiene");
  }
}
function getEstado2(){
  if (!en_partida){
    $.post("<?= base_url() ?>" + 'juegos/get_estado', {
      id : "2"
    } ,pintar2);
  } else {
    $.post("<?= base_url() ?>" + 'juegos/get_estado_partida', {
      id_partida : "1",
      id : "2"
    } ,pintar_partida2);
  }
}
function getEstado3(){
  if (!en_partida){
    $.post("<?= base_url() ?>" + 'juegos/get_estado', {
      id : "3"
    } ,pintar3);
  } else {
    $.post("<?= base_url() ?>" + 'juegos/get_estado_partida', {
      id_partida : "1",
      id : "3"
    } ,pintar_partida3);
  }
}
function getEstado4(){
  if (!en_partida){
    $.post("<?= base_url() ?>" + 'juegos/get_estado', {
      id : "4"
    } ,pintar4);
  } else {
    $.post("<?= base_url() ?>" + 'juegos/get_estado_partida', {
      id_partida : "1",
      id : "4"
    } ,pintar_partida4);
  }
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
        $( "#unirse1" ).hide();
        $( "#unirse2" ).hide();
        $( "#unirse3" ).hide();
        $( "#unirse4" ).hide();
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
