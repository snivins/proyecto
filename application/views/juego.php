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
        <p>Jugador 1: <span id="nick_1"></span><br />Jugador 2: <span id="nick_2"></span><br /> Jugador 3: <span id="nick_3"></span><br /> Jugador 4: <span id="nick_4"></span></p>
      </div>
      <div id="carga">
        <div id="proceso">Cargando ...</div>
      </div>
      <div id="partida">

        <div style="position: relative; width: 0; height: 0">
      <p id="b_salir_jc">Abandonar partida</p>
    </div>
        <canvas id="pantalla" width="800" height="600"></canvas>

                <div style="position: relative; width: 0; height: 0">
                  <p id="i_estado"></p>
              <p id="ataque" class="boton">Envio</p>
              <p id="defensa_paso" class="boton">Paso</p>
              <p id="defensa_quiero" class="boton">Quiero</p>
              <p id="defensa_mas" class="boton">Mas</p>
              <div id="mensajes_estado">
              </div>

              <p id="puntos_texto" class="punto_info">Equipo 1 | Equipo 2</p>
              <p id="puntos_equipo1" class="punto_info"></p>
              <p id="puntos_equipo2" class="punto_info"></p>
              <p id="puntos_pendientes" class="punto_info">Jugando por <span id="cantidad">1</span></p>
              <div id="chat">
                  <div id="mensajes">
                  </div>
                  <form>
                    <input type="text" id="contenido" placeholder="Escribe tu mensaje">
                    <div  id="chaterino">
                      <input type="button" id="enviar" value="Publico">
                      <input type="button" id="enviar_privado" value="Privado">
                    </div>
                  </form>
                </div>
            </div>
      </div>
  </div>
<script>

//variables de estado
var tablero_pintado = false;
var objetos_pintados = false;
var cartas_jugadas = "";

var ultimo_jug = "";
var estado_jugador = "";
var nick1 = "";
var nick2 = "";
var nick3 = "";
var nick4 = "";
var nicks_fijos = ["","","",""];
var foto1 = "";
var foto2 = "";
var foto3 = "";
var foto4 = "";
var puntos = 0;

var en_partida = false;
$(document).ready(function() {
  $('#menu').hide();
  $('#partida').hide();
  $('#lobby').hide();
  $('#carga').hide();
  getEstado();
  setInterval(function(){
    getEstado();
  }, 3000);




});
function darOK(r){/*
  alert("yay");
  alert($.parseJSON(r));*/
}
function mostrar() {
  $.getJSON("<?= base_url() ?>" + 'chats/ver_mensajes', pintar_mensajes);
}
function mostrar_jugadas() {
  $.getJSON("<?= base_url() ?>" + 'juegos/ver_jugadas', pintar_jugadas);
}
function enviar () {
  if ($("#contenido").val() != "") {
    $.post("<?= base_url() ?>" + "chats/escribir_mensajes/", {
      contenido : $("#contenido").val()
    }, mostrar());
    //$.getJSON("<?= base_url() ?>" + "chats/escribir_mensajes/" + $("#contenido").val() + "/"+ $("#usuario").val());
    $("#contenido").val("");
  }
}

function enviar_privado() {
  if ($("#contenido").val() != "") {
    $.post("<?= base_url() ?>" + "chats/escribir_mensaje_privado/", {
      contenido : $("#contenido").val()
    }, mostrar());
    //$.getJSON("<?= base_url() ?>" + "chats/escribir_mensajes/" + $("#contenido").val() + "/"+ $("#usuario").val());
    $("#contenido").val("");
  }
}
function pintar_mensajes(r) {
  $('#mensajes').empty();
  for (var cosa in r){
    $('#mensajes').append('<p><i><b>'+r[cosa].usuario+':</i></b> '+r[cosa].contenido + '</p>');
  }
}
function pintar_jugadas(r) {
  $('#mensajes_estado').empty();
  for (var cosa in r){
    $('#mensajes_estado').append('<p>'+r[cosa].ultima_jugada+'</p>');
  }
}


function getEstado(){
  $.post("<?= base_url() ?>" + 'juegos/get_estado',pintar);
  if (en_partida) {
    $.post("<?= base_url() ?>" + 'juegos/get_estado_partida', {
      id_partida : "1"
    } ,pintar_partida);
  }
}
function salir(){
  $.post("<?= base_url() ?>" + 'juegos/abandonar_partida');
}
function pintar_tablero(estado, jugadorino) {

  var nicks= [];
  var fotos= [];
  var posiciones = [];
  var activo = estado.activo;
  switch (jugadorino){
    case 1:
      nicks = [nick1, nick2, nick3, nick4];
      fotos = [foto2,foto3,foto4];
      posiciones = [1,2,3,4];
      break;
    case 2:
      nicks = [nick2, nick3, nick4, nick1];

      fotos = [foto3,foto4,foto1];
      posiciones = [2,3,4,1];
      break;
    case 3:
      nicks = [nick3, nick4, nick1, nick2];
      fotos = [foto4,foto1,foto2];
      posiciones = [3,4,1,2];
      break;
    case 4:
      nicks = [nick4, nick1, nick2, nick3];
      fotos = [foto1,foto2,foto3];
      posiciones = [4,1,2,3];
      break;
  }
/*
          $('#jug_bot').text(nicks[0]);
          $('#jug_izq').text(nicks[1]);
          $('#jug_up').text(nicks[2]);
          $('#jug_der').text(nicks[3]);
          $('#jug_izq_img').attr("src", "images/"+ fotos[0]);
          $('#jug_up_img').attr("src", "images/"+ fotos[1]);
          $('#jug_der_img').attr("src", "images/"+ fotos[2]);*/

          var coordX = [400,50,300,700];
          var coordY = [570,400,100,400];
          var coordX2 = [50,300,700];
          var coordY2 = [350,50,350];
          for (var i = 0; i < 3 ; i++){
            var ruta = 'images/'+fotos[i];
            $('canvas').drawImage({
                layer: true,
                source: ruta,
                x: coordX2[i], y: coordY2[i],
                height: 80 ,
                width: 80,
            });
          }

          $('canvas').drawImage({
              layer: true,
              name: 'vida',
              source: 'images/baraja/'+estado.vida+'.png',
              x: 300, y: 200,
              height: 120,
              bringToFront: true,
              width: 80,
            });
          $('#b_salir_jc').mouseup(function() {
            if (confirm("¿Esta seguro que desea salir? La partida terminara para todos los jugadores.")){
              $.post("<?= base_url() ?>" + 'juegos/abandonar_partida');
            }
          });
          //pintmos los nicks
          for (var i = 0; i < 4 ; i++){
            $('canvas').drawText({
              layer:true,
              fillStyle: '#000',
              x: coordX[i], y: coordY[i],
              fontSize: 20,
              fontFamily: 'Verdana, sans-serif',
              text: nicks[i]
            });
          }


          //pintamos las 3 cartas
          $('canvas').drawImage({
            layer:true,
            draggable: false,//arrastrable
            name: 'carta1',
            group: 'cartas',
            visible: true,//cartas_visibles[0],
            source: 'images/baraja/00_reverso.png',
            x: 300, y: 500,
            height: 120,
            bringToFront: true,
            width: 80,
          });

          $('canvas').drawImage({
            layer:true,
            draggable: false,//arrastrable
            name: 'carta2',
            group: 'cartas',
            visible: true,//cartas_visibles[0],
            source: 'images/baraja/00_reverso.png',
            x: 400, y: 500,
            height: 120,
            bringToFront: true,
            width: 80,
          });

          $('canvas').drawImage({
            layer:true,
            draggable: false,//arrastrable
            name: 'carta3',
            group: 'cartas',
            visible: true,//cartas_visibles[0],
            source: 'images/baraja/00_reverso.png',
            x: 500, y: 500,
            height: 120,
            bringToFront: true,
            width: 80,
          });


        tablero_pintado = true;
        $('#carga').hide();
        $('#partida').show();
}
function pintar_mano(estado_jug, estado){
    var cartas_visibles = [true,true,true];
    var cartas_jugadas =$.parseJSON(estado.cartas_jugadas);
    for (var j in cartas_jugadas) {
      if (estado_jug.carta_uno == cartas_jugadas[j]) cartas_visibles[0] = false;
      if (estado_jug.carta_dos == cartas_jugadas[j]) cartas_visibles[1] = false;
      if (estado_jug.carta_tres == cartas_jugadas[j]) cartas_visibles[2] = false;
    }
    $('canvas').setLayer('carta1', {
    visible: cartas_visibles[0],
    draggable: true,
    source: 'images/baraja/'+estado_jug.carta_uno+'.png',
    bringToFront: true,
    dragstop: function(layer) {
      var distX, distY;
      if (layer.eventX > 150 && layer.eventX < 650 && layer.eventY > 100 && layer.eventY < 400) {
        $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
          movimiento: estado_jug.carta_uno
        } );
      }
    }
  }).drawLayers();
  if (estado_jug.hasOwnProperty("carta_dos")) {
    $('canvas').setLayer('carta2', {
    visible: cartas_visibles[1],
    draggable: true,
    source: 'images/baraja/'+estado_jug.carta_dos+'.png',
    bringToFront: true,
    dragstop: function(layer) {
      var distX, distY;
      if (layer.eventX > 150 && layer.eventX < 650 && layer.eventY > 100 && layer.eventY < 400) {
        $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
          movimiento: estado_jug.carta_dos
        });
      }
    }
    }).drawLayers();
  } else {
    $('canvas').setLayer('carta2', {
      visible: true,
      draggable: false,
      source: 'images/baraja/00_reverso.png',
      bringToFront: true
    }).drawLayers();
  }
  if (estado_jug.hasOwnProperty("carta_dos")) {
    $('canvas').setLayer('carta3', {
    visible: cartas_visibles[2],
    draggable: true,
    source: 'images/baraja/'+estado_jug.carta_tres+'.png',
    bringToFront: true,
    dragstop: function(layer) {
      var distX, distY;
      if (layer.eventX > 150 && layer.eventX < 650 && layer.eventY > 100 && layer.eventY < 400) {
        $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
          movimiento: estado_jug.carta_tres
        });
      }
    }
    }).drawLayers();
  } else {
    $('canvas').setLayer('carta3', {
      visible: true,
      draggable: false,
      source: 'images/baraja/00_reverso.png',
      bringToFront: true
    }).drawLayers();
  }
}
function pintar_cartas_jugadas(estado){

  if ($.parseJSON(estado.cartas_jugadas) != estado.vida){
    //alert("time to pintar cartas");

       cartas_jugadas =$.parseJSON(estado.cartas_jugadas);

    coordX_cartas= [400,450,500,550,600];
    coordY_cartas= [175,200,225,250,275];
    for (var j = 1; j<cartas_jugadas.length;j++){

      $('canvas').drawImage({
        layer: true,
        group: 'cartas_jugadas',
        source: 'images/baraja/'+cartas_jugadas[j]+'.png',
        x: coordX_cartas[j-1], y: coordY_cartas[j-1],
        height: 120,
        bringToFront: true,
        width: 80,
      });
    }
  }
  cartas_jugadas = $.parseJSON(estado.cartas_jugadas);

}

function pintar_partida(r) {
  var estado = $.parseJSON(r);
  if (estado.hasOwnProperty("jug_1")){
    var jugadorino = 1;
    var estado_jug = $.parseJSON(estado.jug_1);
  }else if (estado.hasOwnProperty("jug_2")){
    var jugadorino = 2;
    var estado_jug = $.parseJSON(estado.jug_2);
  }else if(estado.hasOwnProperty("jug_3")){
    var jugadorino = 3;
    var estado_jug = $.parseJSON(estado.jug_3);
  }else if(estado.hasOwnProperty("jug_4")){
    var jugadorino = 4;
    var estado_jug = $.parseJSON(estado.jug_4);
  }
  $('#puntos_equipo1').text(estado.puntos_equipo_1);
  $('#puntos_equipo2').text(estado.puntos_equipo_2);
  if (parseInt(estado.puntos_ronda) < 2){
    $('#cantidad').text("1 punto");
  } else {
    $('#cantidad').text(estado.puntos_ronda + " puntos");
  }
  if(estado.turno_jug != ultimo_jug){
      pintar_cartas_jugadas(estado);
      pintar_mano(estado_jug, estado);

      mostrar_jugadas();
      ultimo_jug = estado.turno_jug;
  }
  if (!tablero_pintado){
    pintar_tablero(estado,jugadorino);
    pintar_mano(estado_jug, estado);
  }

  var active_player = estado.turno_jug;
  active_player = active_player.substr(active_player.length - 1);
  //alert(nicks_fijos[active_player - 1]);
  if (estado_jug.estado != estado_jugador){
    if (estado_jug.estado == "ataque"){
      $('#i_estado').text("Tu turno");
      $('#ataque').fadeIn();
      $('#defensa_mas').fadeTo("slow", 0.35);
      $('#defensa_quiero').fadeTo("slow", 0.35);
      $('#defensa_paso').fadeTo("slow", 0.35);
      $('#ataque').mouseup(function() {
        $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
          movimiento: 'Envio'
        } );
      });
    } else if (estado_jug.estado == "defensa") {
      $('#i_estado').text("Tu turno");
      $('#ataque').fadeTo("slow", 0.35);
      $('#defensa_mas').fadeIn();
      $('#defensa_quiero').fadeIn();
      $('#defensa_paso').fadeIn();
      $('#defensa_mas').mouseup(function() {
        $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
          movimiento: 'Mas'
        } );
      });
      $('#defensa_quiero').mouseup(function() {
        $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
          movimiento: 'Quiero'
        } );
      });
      $('#defensa_paso').mouseup(function() {
        $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
          movimiento: 'Paso'
        } );
      });
    } else  {
      $('#i_estado').text("Turno de "+nicks_fijos[active_player - 1]);
      $('#ataque').fadeTo("slow", 0.35);
      $('#defensa_mas').fadeTo("slow", 0.35);
      $('#defensa_quiero').fadeTo("slow", 0.35);
      $('#defensa_paso').fadeTo("slow", 0.35);
    }
    estado_jugador = estado_jug.estado;
  }

}/* ----------------------------------------------------------------
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
  $('canvas').drawText({
    layer:true,
    name: 'puntos1',
    fillStyle: '#000',
    x: 90, y: 520,
    fontSize: 20,
    fontFamily: 'Verdana, sans-serif',
    text: "Equipo 1: "+estado.puntos_equipo_1
  });
$('canvas').drawText({
  layer:true,
  name: 'puntos2',
  fillStyle: '#000',
  x: 90, y: 560,
  fontSize: 20,
  fontFamily: 'Verdana, sans-serif',
  text: "Equipo 2: "+estado.puntos_equipo_2
});
$('#puntos2').text(estado.puntos_equipo_2);
if (parseInt(estado.puntos_pendientes) > 0){
  $('#puntosPen').text(estado.puntos_pendientes + " puntos");
  puntos = estado.puntos_pendientes;
} else if (parseInt(estado.puntos_ronda) > 1){
  $('#puntosPen').text(estado.puntos_ronda + " puntos");
  puntos = estado.puntos_ronda;
} else {
  $('#puntosPen').text(estado.puntos_ronda + " punto");
  puntos = 0;
}
var nicks= [];
var fotos= [];
var posiciones = [];
var activo = estado.activo;
switch (jugadorino){
  case 1:
    nicks = [nick1, nick2, nick3, nick4];
    fotos = [foto2,foto3,foto4];
    posiciones = [1,2,3,4];
    break;
  case 2:
    nicks = [nick2, nick3, nick4, nick1];
    fotos = [foto3,foto4,foto1];
    posiciones = [2,3,4,1];
    break;
  case 3:
    nicks = [nick3, nick4, nick1, nick2];
    fotos = [foto4,foto1,foto2];
    posiciones = [3,4,1,2];
    break;
  case 4:
    nicks = [nick4, nick1, nick2, nick3];
    fotos = [foto1,foto2,foto3];
    posiciones = [4,1,2,3];
    break;
}
var coordX = [400,100,270,700];
var coordY = [570,400,30,400];
var coordX2 = [100,400,700];
var coordY2 = [300,50,300];



if (estado_jug.estado == "defensa" || estado_jug.estado == "ataque") {
  $('canvas').drawText({
    layer:true,
    name: 'turno',
    fillStyle: '#000',
    x: 750, y: 50,
    fontSize: 20,
    fontFamily: 'Verdana, sans-serif',
    text: "Tu turno"
  });
  if (estado_jug.estado == "ataque"){
    $('canvas').drawArc({
      layer: true,
      name: 'boton_envio',
      strokeStyle: '#0AA',
      strokeWidth: 5,
      fillStyle: '#0FF',
      x: 580, y: 450,
      radius: 30,
      click: function () {
        $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
          movimiento: 'Envio'
        } );
      },
      mouseover: function(layer) {
        $(this).animateLayer(layer, {
            shadowColor: '#000',
            shadowBlur: 50,
        }, 0);
      },
      mouseout: function(layer) {
        $(this).animateLayer(layer, {
            shadowBlur: 0,
        }, 0);
      }
    });
    $('canvas').drawText({
      layer: true,
      name:'texto_envio',
      fillStyle: '#000',
      x: 580, y: 450,
      fontSize: 15,
      fontFamily: 'Verdana, sans-serif',
      text: "Envio"
    });
  } else {
    $('canvas').drawArc({
      layer: true,
      name: 'boton_quiero',
      strokeStyle: '#0AA',
      strokeWidth: 5,
      fillStyle: '#0FF',
      x: 580, y: 450,
      radius: 30,
      click: function () {
        $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
          movimiento: 'Quiero'
        } );
      },
      mouseover: function(layer) {
        $(this).animateLayer(layer, {
            shadowColor: '#000',
            shadowBlur: 50,
        }, 0);
      },
      mouseout: function(layer) {
        $(this).animateLayer(layer, {
            shadowBlur: 0,
        }, 0);
      }
    });
    $('canvas').drawText({
      layer: true,
      name:'texto_quiero',
      fillStyle: '#000',
      x: 580, y: 450,
      fontSize: 15,
      fontFamily: 'Verdana, sans-serif',
      text: "Quiero"
    });

      $('canvas').drawArc({
        layer: true,
        name: 'boton_mas',
        strokeStyle: '#0AA',
        strokeWidth: 5,
        fillStyle: '#0FF',
        x: 650, y: 450,
        radius: 30,
        click: function () {
          $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
            movimiento: 'Mas'
          } );
        },
        mouseover: function(layer) {
          $(this).animateLayer(layer, {
              shadowColor: '#000',
              shadowBlur: 50,
          }, 0);
        },
        mouseout: function(layer) {
          $(this).animateLayer(layer, {
              shadowBlur: 0,
          }, 0);
        }
      });
      $('canvas').drawText({
        layer: true,
        name:'texto_mas',
        fillStyle: '#000',
        x: 650, y: 450,
        fontSize: 15,
        fontFamily: 'Verdana, sans-serif',
        text: 'Pedir '+(parseInt(puntos) + 3)
      });
      $('canvas').drawArc({
        layer: true,
        name: 'boton_paso',
        strokeStyle: '#0AA',
        strokeWidth: 5,
        fillStyle: '#0FF',
        x: 720, y: 450,
        radius: 30,
        click: function () {
          $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
            movimiento: 'Paso'
          } );
        },
        mouseover: function(layer) {
          $(this).animateLayer(layer, {
              shadowColor: '#000',
              shadowBlur: 50,
          }, 0);
        },
        mouseout: function(layer) {
          $(this).animateLayer(layer, {
              shadowBlur: 0,
          }, 0);
        }
      });
      $('canvas').drawText({
        layer: true,
        name:'texto_paso',
        fillStyle: '#000',
        x: 720, y: 450,
        fontSize: 15,
        fontFamily: 'Verdana, sans-serif',
        text: 'Pasar'
      });
  }
} else {
  $('canvas').setLayer('turno', {
    visible: false
  }).drawLayers();
}


for (var i = 0; i < 4 ; i++){
  $('canvas').drawText({
    layer:true,
    fillStyle: '#000',
    strokeStyle: activo==posiciones[i]?'#0AF':'#000',
    x: coordX[i], y: coordY[i],
    fontSize: 20,
    fontFamily: 'Verdana, sans-serif',
    text: nicks[i]
  });
  if (i != 0) {
  var ruta = 'images/'+fotos[i-1];
  $('canvas').drawImage({
      layer: true,
      source: ruta,
      x: coordX2[i-1], y: coordY2[i-1],
      height: 80 ,
      width: 80,
    });
  }
}
/*
$('canvas').drawText({
  layer:true,
  strokeStyle: '#AFA',
  strokeWidth: 1,
  x: 100, y: 200,
  fontSize: 20,
  fontFamily: 'Verdana, sans-serif',
  text: estado.jug_4_nick
});*//* ----------------------------------------------------------------
  $('#cartas_jugadas').text(estado.cartas_jugadas);
  var cartas_usadas = false;
  if (typeof estado.cartas_jugadas != 'string'){
    cartas_usadas = true;
  }
  $('#estado_partida').text(estado.cartas_jugadas);

  var arrastrable = false;
  var color = '#F00';
  if (estado_jug.estado == 'ataque') {
    arrastrable = true;
    color = '#0F0';//poner aqui grupo d cartas

  }
  $('canvas').drawRect({
    layer: true,
    strokeStyle: '#c33',
    strokeWidth: 4,
    x: 400, y: 250,
    width: 500,
    height: 300,
    cornerRadius: 10,
  });
  $('canvas').drawImage({
    layer: true,
    name: 'baraja',
    source: 'images/baraja/00_reverso.png',
    x: 270, y: 270,
    height: 120,
    width: 80,
  });
  $('canvas').drawImage({
    layer: true,
    name: 'vida',
    source: 'images/baraja/'+estado.vida+'.png',
    x: 300, y: 300,
    height: 120,
    bringToFront: true,
    width: 80,
  });


    var cartas_visibles = [true,true,true];
if (typeof estado.vida == typeof $.parseJSON(estado.cartas_jugadas)) {
  //alert("yay");
  $('canvas').setLayerGroup('cartas_jugadas', {
    visible: false
  }).drawLayers();
} else {
  //alert("nay");
  //alert(typeof estado.cartas_jugadas);
  coordX_cartas= [400,450,500,550,600];
  coordY_cartas= [175,200,225,250,275];

  var cartas_jugadas =$.parseJSON(estado.cartas_jugadas);
  for (var j in cartas_jugadas) {
    if (estado_jug.carta_uno == cartas_jugadas[j]) cartas_visibles[0] = false;
    if (estado_jug.carta_dos == cartas_jugadas[j]) cartas_visibles[1] = false;
    if (estado_jug.carta_tres == cartas_jugadas[j]) cartas_visibles[2] = false;
  }

  for (var j = 1; j<cartas_jugadas.length;j++){


    $('canvas').drawImage({
      layer: true,
      group: 'cartas_jugadas',
      source: 'images/baraja/'+cartas_jugadas[j]+'.png',
      x: coordX_cartas[j-1], y: coordY_cartas[j-1],
      height: 120,
      bringToFront: true,
      width: 80,
    });
  }
}
$('canvas').drawArc({
  layer: true,
  strokeStyle: '#A00',
  strokeWidth: 5,
  fillStyle: '#F00',
  x: 50, y: 50,
  radius: 30,
  click: function () {
    if (confirm("¿Esta seguro que desea salir? La partida terminara para todos los jugadores.")){
      $.post("<?= base_url() ?>" + 'juegos/abandonar_partida');
    }
  },
  mouseover: function(layer) {
    $(this).animateLayer(layer, {
        shadowColor: '#000',
        shadowBlur: 50,
    }, 0);
  },
  mouseout: function(layer) {
    $(this).animateLayer(layer, {
        shadowBlur: 0,
    }, 0);
  }
});
$('canvas').drawText({
  layer:true,
  fillStyle: '#000',
  x: 50, y: 50,
  fontSize: 15,
  fontFamily: 'Verdana, sans-serif',
  text: "Salir"
});
/*
$('canvas').setLayer('carta1', {
  visible: false
}).drawLayers();
*/
/* ----------------------------------------------------------------

  $('canvas').drawImage({
    layer:true,
    draggable: arrastrable,
    name: 'carta1',
    group: 'cartas',
    visible: cartas_visibles[0],
    source: 'images/baraja/'+estado_jug.carta_uno+'.png',
    x: 300, y: 500,
    height: 120,
    bringToFront: true,
    width: 80,
    /*
    click: function () {
      $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
        movimiento: estado_jug.carta_uno
      } );*//* ----------------------------------------------------------------
      dragstop: function(layer) {
        var distX, distY;
        if (layer.eventX > 150 && layer.eventX < 650 && layer.eventY > 100 && layer.eventY < 400) {
          $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
            movimiento: estado_jug.carta_uno
          } );
        }
      }
  });


  if(estado_jug.hasOwnProperty("carta_dos")){
    $('#carta2').show();
      $('canvas').drawImage({
        layer:true,
        draggable: arrastrable,
        name: 'carta2',
        group: 'cartas',
        visible: cartas_visibles[1],
        source: 'images/baraja/'+estado_jug.carta_dos+'.png',
        x: 400, y: 480,
        height: 120,
        bringToFront: true,
        width: 80,
        dragstop: function(layer) {
          var distX, distY;
          if (layer.eventX > 150 && layer.eventX < 650 && layer.eventY > 100 && layer.eventY < 400) {
            $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
              movimiento: estado_jug.carta_dos
            } );
          }
        },
        mouseover: function(layer) {
          $(this).animateLayer(layer, {
              shadowColor: color,
              shadowBlur: 100,
          }, 0);
        },
        mouseout: function(layer) {
          $(this).animateLayer(layer, {
              shadowBlur: 0,
          }, 0);
        }
      });
  } else {
    $('#carta2').hide();
  }
  if(estado_jug.hasOwnProperty("carta_tres")){
    $('#carta3').show();

    $('canvas').drawImage({
      layer:true,
      draggable: arrastrable,
      name: 'carta3',
      group: 'cartas',
      source: 'images/baraja/'+estado_jug.carta_tres+'.png',
      x: 500, y: 500,
      height: 120,
      visible: cartas_visibles[2],
      bringToFront: true,
      width: 80,
      dragstop: function(layer) {
        var distX, distY;
        if (layer.eventX > 150 && layer.eventX < 650 && layer.eventY > 100 && layer.eventY < 400) {
          $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
            movimiento: estado_jug.carta_tres
          } );
        }
      },
      mouseover: function(layer) {
        $(this).animateLayer(layer, {
            shadowColor: color,
            shadowBlur: 100,
        }, 0);
      },
      mouseout: function(layer) {
        $(this).animateLayer(layer, {
            shadowBlur: 0,
        }, 0);
      }
    });
  } else {
    $('#carta3').hide();
  }

    var cartas_jugadas =$.parseJSON(estado.cartas_jugadas);
    for (var j in cartas_jugadas) {
      if (estado_jug.carta_uno == cartas_jugadas[j]) cartas_visibles[0] = false;
      if (estado_jug.carta_dos == cartas_jugadas[j]) cartas_visibles[1] = false;
      if (estado_jug.carta_tres == cartas_jugadas[j]) cartas_visibles[2] = false;
    }

    if (estado_jug.estado == 'ataque')   {
      arrastrable = true;
    } else {
      arrastrable = false;
    }
    if (estado_jug.estado != 'defensa'){
      $('canvas').setLayer('texto_quiero', {
        visible: false,
        }).drawLayers();
      $('canvas').setLayer('texto_mas', {
        visible: false,
        }).drawLayers();
      $('canvas').setLayer('texto_paso', {
        visible: false,
        }).drawLayers();


        $('canvas').setLayer('boton_quiero', {
          visible: false,
          }).drawLayers();
        $('canvas').setLayer('boton_mas', {
          visible: false,
          }).drawLayers();
        $('canvas').setLayer('boton_paso', {
          visible: false,
          }).drawLayers();
        }

      if (estado_jug.estado != 'ataque'){
        $('canvas').setLayer('texto_envio', {
          visible: false,
          }).drawLayers();
            $('canvas').setLayer('boton_envio', {
              visible: false,
              }).drawLayers();
      }

      $('canvas').setLayer('carta1', {
        visible:cartas_visibles[0],
        draggable: arrastrable,
        mouseover: function(layer) {
          $(this).animateLayer(layer, {
              shadowColor: color,
              shadowBlur: 100,
          }, 0);
        },
        mouseout: function(layer) {
          $(this).animateLayer(layer, {
              shadowBlur: 0,
          }, 0);
        }
      }).drawLayers();
      $('canvas').setLayer('carta2', {

        draggable: arrastrable,
          visible:cartas_visibles[1],
        mouseover: function(layer) {
          $(this).animateLayer(layer, {
              shadowColor: color,
              shadowBlur: 100,
          }, 0);
        },
        mouseout: function(layer) {
          $(this).animateLayer(layer, {
              shadowBlur: 0,
          }, 0);
        }
      }).drawLayers();
      $('canvas').setLayer('carta3', {

        draggable: arrastrable,
          visible:cartas_visibles[2],
        mouseover: function(layer) {
          $(this).animateLayer(layer, {
              shadowColor: color,
              shadowBlur: 100,
          }, 0);
        },
        mouseout: function(layer) {
          $(this).animateLayer(layer, {
              shadowBlur: 0,
          }, 0);
        }
      }).drawLayers();

}*/

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
          nick1 = estado.jug_1_nick;
        } else {
          $('#nick_1').text("");
        }
        if (estado.hasOwnProperty('jug_2_nick')){
          $('#nick_2').text(estado.jug_2_nick);
          nick2 = estado.jug_2_nick;
        } else {
          $('#nick_2').text("");
        }
        if (estado.hasOwnProperty('jug_3_nick')){
          $('#nick_3').text(estado.jug_3_nick);
          nick3 = estado.jug_3_nick;
        } else {
          $('#nick_3').text("");
        }
        if (estado.hasOwnProperty('jug_4_nick')){
          $('#nick_4').text(estado.jug_4_nick);
          nick4 = estado.jug_4_nick;
        } else {
          $('#nick_4').text("");
        }
        $(".boton_salir").click(function() {
          $.post("<?= base_url() ?>" + 'juegos/abandonar_sala');
        });
        en_partida = false;
          $.cookie('jugando', 'true', { expires: 1, path: '/' });
      } else if (estado['estado'] == "jugando"){
        $('.estado').text('En partida  ');
        if (!en_partida)        $('#carga').show();
        en_partida = true;
        nick1 = estado.jug_1_nick;
        nick2 = estado.jug_2_nick;
        nick3 = estado.jug_3_nick;
        nick4 = estado.jug_4_nick;
        nicks_fijos = [nick1, nick2, nick3, nick4];
        foto1 = estado.jug_1_foto;
        foto2 = estado.jug_2_foto;
        foto3 = estado.jug_3_foto;
        foto4 = estado.jug_4_foto;
        $('#lobby').hide();
        $('#menu').hide();
        mostrar();
        $(this).keydown(function (e) {
            if(e.keyCode == 13){      enviar();        }//intro
          });
          $("#enviar").click(enviar);
          $("#enviar_privado").click(enviar_privado);
          $.cookie('jugando', 'true', { expires: 1, path: '/' });
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
        $.removeCookie('jugando', { path: '/' });
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
                  <div id="mensaje">
                    <p>Debes registrarte para poder jugar o logueate con tu cuenta si ya tienes una</p><br /><br />
                    <div id="enlaces">
                      <a href="<?= base_url() ?>usuarios/registro" title="Registrate">Nuevo usuario</a><a href="<?= base_url() ?>usuarios/login" title="Inicia sesión">Login  </a>
                    </div>
                  </div>
              <?php endif;?>
