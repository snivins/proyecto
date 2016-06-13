<?php template_set('title', 'Rentoy time') ?>
<h2>Rentoy time!</h2>
  <?php if (logueado()): ?>
    <div id="juego">
      <div id="menu">
        <h3>Situación actual jugador =>Menu</h3>
        <p class="estado"><p>
        <div id="listado"></div>
        <button id="nueva_partida">Nueva partida</button>
      </div>
      <div id="lobby">
        <h3>Situación actual jugador =>lobby</h3>
        <p class="estado"><p>
        <p id="estado_partida"><p>
        <button class="boton_salir" >Abandonar partida</button>
        <p>Jugadores unidos</p>
        <p>Jugador 1: <span id="nick_1"></span> ,Jugador 2: <span id="nick_2"></span>, Jugador 3: <span id="nick_3"></span>, Jugador 4: <span id="nick_4"></span></p>
      </div>
      <div id="partida">
        <canvas id="pantalla" width="800" height="600"></canvas>
      </div>
  </div>
<script>
/*Trozo de partida pre canvas

<h3>Situación actual jugador =>partida</h3>
<p id="cartas_jugadas"><p>
<p>Jugando por <span id="puntosPen"></span><p>
<p>Equipo 1: <span id="puntos1"></span><p>
<p>Equipo 2: <span id="puntos2"></span><p>
<p class="estado"><p>
<p id="estado_partida"><p>
<p>Carta1: <button class="acciones" id="carta1"></button>, Carta2: <button iclass="acciones" id="carta2"></button>, Carta3: <button class="acciones" id="carta3"></button><p>
  <p>Acciones</p>
  <button class="acciones" id="boton_envio">Envio</button>
  <button class="defensa" id="boton_quiero">Quiero</button>
  <button class="defensa" id="boton_mas">Mas</button>
  <button class="defensa" id="boton_paso">Paso</button>
  <button class="boton_salir">Abandonar partida</button>

*/
var en_partida = false;
$(document).ready(function() {
  $('#menu').hide();
  $('#partida').hide();
  $('#lobby').hide();
  getEstado();
  setInterval(function(){
    getEstado();
  }, 3000);



});
function darOK(r){
  alert("yay");
  alert($.parseJSON(r));
}




function getEstado(){
  $.post("<?= base_url() ?>" + 'juegos/get_estado',pintar);
  if (en_partida) {
    $.post("<?= base_url() ?>" + 'juegos/get_estado_partida', {
      id_partida : "1"
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

    if (estado_jug.estado == "ataque"){
      $('.acciones').click(function () {
        $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
          id_partida: "1",
          movimiento: $('#'+this.id).text()
        } );
      });
    } else if (estado_jug.estado == "defensa") {
      $('.defensa').click(function () {
        $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
          id_partida: "1",
          movimiento: $('#'+this.id).text()
        } );
      });
    }
  $('#puntos1').text(estado.puntos_equipo_1);
$('#puntos2').text(estado.puntos_equipo_2);
if (parseInt(estado.puntos_pendientes) > 0){
  $('#puntosPen').text(estado.puntos_pendientes + " puntos");
} else if (parseInt(estado.puntos_ronda) > 1){
  $('#puntosPen').text(estado.puntos_ronda + " puntos");
} else {
  $('#puntosPen').text(estado.puntos_ronda + " punto");
}

  $('#cartas_jugadas').text(estado.cartas_jugadas);
  $('#estado_partida1').text(estado_jug.estado);
  $('#carta1').text(estado_jug.carta_uno);

  $('canvas').drawImage({
    draggable: true,
    name: 'carta1',
    source: 'images/baraja/'+estado_jug.carta_uno+'.png',
    x: 150, y: 150,
    height: 120,
    bringToFront: true,
    width: 80,
    mouseover: function(layer) {
      $(this).animateLayer(layer, {
          shadowColor: '#000',
          shadowBlur: 10,
      }, 0);
    },
    mouseout: function(layer) {
      $(this).animateLayer(layer, {
          shadowColor: '#F00',
          shadowBlur: 0,
      }, 0);
    }
  });
  if(estado_jug.hasOwnProperty("carta_dos")){
    $('#carta2').show();
      $('#carta2').text(estado_jug.carta_dos);
  } else {
    $('#carta2').hide();
  }
  if(estado_jug.hasOwnProperty("carta_tres")){
    $('#carta3').show();
    $('#carta3').text(estado_jug.carta_tres);
  } else {
    $('#carta3').hide();
  }
}

function pintar(r){
    $('#estado').empty();
    var estado = $.parseJSON(r);
    if (estado != null) {
      if (estado['estado'] == "creada") {
        $('.estado').text('Esperando jugadores  ');
        $('#lobby').show();
        $('#menu').hide();
        $('#partida').hide();
        if (estado.hasOwnProperty('jug_1_nick')){
          $('#nick_1').text(estado.jug_1_nick);
        }
        if (estado.hasOwnProperty('jug_2_nick')){
          $('#nick_2').text(estado.jug_2_nick);
        }
        if (estado.hasOwnProperty('jug_3_nick')){
          $('#nick_3').text(estado.jug_3_nick);
        }
        if (estado.hasOwnProperty('jug_4_nick')){
          $('#nick_4').text(estado.jug_4_nick);
        }
        $(".boton_salir").click(function() {
          $.post("<?= base_url() ?>" + 'juegos/abandonar_sala');
        });
        en_partida = false;
      } else if (estado['estado'] == "jugando"){
        $('.estado').text('En partida  ');
        $(".boton_salir").click(function() {
          $.post("<?= base_url() ?>" + 'juegos/abandonar_partida');
        });
        en_partida = true;
        $('#lobby').hide();
        $('#menu').hide();
        $('#partida').show();
      }
    } else {
      en_partida = false;
      $('.estado').text('En menu');
      $.post("<?= base_url() ?>" + 'juegos/listar_partidas',pintar_listado);

      $("#nueva_partida").click(function() {
        $.post("<?= base_url() ?>" + 'juegos/nueva_partida');
      });
      $('#lobby').hide();
      $('#menu').show();
      $('#partida').hide();
    }
}
function pintar_listado(r){
    $('#listado').empty();
    var estado = $.parseJSON(r);

    for (mimi in estado){
        var jugadores = 1;
        if (estado[mimi].jug_2 != null) {
          jugadores++;
        }
        if (estado[mimi].jug_3 != null) {
          jugadores++;
        }
        if (estado[mimi].jug_4 != null) {
          jugadores++;
        }
        $('#listado').append('<p> Partida: '+ estado[mimi].id_partida +' | Jugadores: '+ jugadores +' de 4</p>');
        $('#listado').append('<button class="unirse" id="'+estado[mimi].id_partida+'">Unirse</button>');
    }
    $(".unirse").click(function() {
      $.post("<?= base_url() ?>" + 'juegos/unirse_partida', {
        id_partida: this.id
      },darOK);
    });
}

</script>

              <?php else: ?>
                    <p>Debes registrarte para poder jugar</p>
                    <a  href="<?= base_url() ?>usuarios/registro" title="Registro">Registro</a>
                    <p>o logueate con tu cuenta si ya tienes una</p>
                    <a  href="<?= base_url() ?>usuarios/cuenta" title="Login">Login</a>
              <?php endif;?>
