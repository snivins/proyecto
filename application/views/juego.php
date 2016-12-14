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
        <h3>Situación actual jugador =>lobby<span id="nombre"></span></h3>
        <p><span class="estado"></span><i class="fa fa-spinner fa-pulse"></i><p>

        <input type="text" id="nombre_partida" maxlength="28" placeholder="Nombre de Partida">

        <button id="boton_nombre">Cambiar</button><br/>
        <button class="boton_salir" >Abandonar partida</button>
        <p>Jugadores unidos</p>
        <p>Jugador 1: <span id="nick_1"></span><br />Jugador 2: <span id="nick_2"></span><br /> Jugador 3: <span id="nick_3"></span><br /> Jugador 4: <span id="nick_4"></span></p>
      </div>
      <div id="carga">
        <div id="proceso">Cargando <i class="fa fa-spinner fa-pulse"></i></div>
      </div>
      <div id="final">
        <div id="fin_partida">
          <p id="info_fin_partida"></p>
          <p id="info_equipos_fin_partida"></p>
          <button id="confirmar_salir" >Aceptar</button>
        </div>
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

              <div id="minijuego">
                  <div id="barra_f">
                  </div>
                  <div id="objetivo"></div>
                      <div id="barra_s"></div>
                  <p id="ayuda"></p>
              </div>
                <p id="i_ultima">Ultima mano</p>
                  <p id="i_vida">Vida</p>
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
var cartas_jugadas_visibles = [false,false,false,false];
var cartas_jugadas_visibles2 = [false,false,false,false];

  var nicks= [];

var coordX_cartas= [400,450,500,550,600];
var coordY_cartas= [200,225,250,275,300];
var coordX2_cartas= [160,120,80,40];


var en_partida = false;

var activo = false;
$(document).ready(function() {
  $('#menu').hide();
  $('#partida').hide();
  $('#lobby').hide();
  $('#carga').hide();
      $('#minijuego').hide();
  getEstado();
  setInterval(function(){
    getEstado();
  }, 3000);




});
function darOK(r){/*
  alert("yay");
  alert($.parseJSON(r));*/
}

function move() {
    if (!activo && $("#contenido").val() != "") {
        $('#ayuda').text("Para la barra en el centro");
        $('#minijuego').fadeIn('slow');
        activo = true;
        $('#barra_f').css("background-color", "grey");
        var elem = document.getElementById("barra_s");
        var left_o = -630;
        var left = left_o;

        elem.style.left = left_o;
        var id = setInterval(frame, 10);
        var sentido= true;
        $('#minijuego').click(function() {
            clearInterval(id);
            if (left >= left_o + 160 && left <= left_o + 240){
                $('#barra_f').css("background-color", "green");
                $('#ayuda').text("Exito");
                enviar_privado();
            } else {
                $('#barra_f').css("background-color", "red");
                $('#ayuda').text("Fallo");
                enviar();
            }
            activo = false;
            $('#minijuego').off('click');
            $('#minijuego').fadeOut('slow');
        });
        function frame() {
            if (left >= left_o+395) {
                sentido= false;
            } else if (left <= left_o+5) {
                sentido= true;
            }

            if (sentido){
                left += 5;
                elem.style.left = left + 'px';
            } else {
                left -= 5;
                elem.style.left = left + 'px';
            }
        }
    }
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
    if(r[cosa].privado == ""){
      $('#mensajes').append('<p><i><b>'+r[cosa].usuario+':</i></b> '+r[cosa].contenido + '</p>');
    } else {
      $('#mensajes').append('<p><i><b>'+r[cosa].usuario+':</i></b><span id="msg_privado"> '+r[cosa].contenido + '</span></p>');
    }
  }
}
function pintar_jugadas(r) {
  $('#mensajes_estado').empty();
  for (var cosa in r){
    $('#mensajes_estado').append('<p>'+r[cosa].ultima_jugada.replace('_', ' de ').replace('.', '<br>')+'</p>');
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
  var fotos= [];
  var posiciones = [];
  var activo = estado.turno_jug.replace('jug_',"");
  //var activo = estado.activo;
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
            var ruta = 'upload/'+fotos[i];
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
              height: 123,
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

          //pinto las zonas remarcadas, cartas en juego, mano y ultima mano
          $('canvas').drawRect({
            layer: true,
            name: "zona_cartas",
            strokeStyle: '#ccc',
            strokeWidth: 4,
            x: 420, y: 260,
            width: 410,
            height: 270,
            cornerRadius: 10
          });//800 600 215-420-625 125-260-395

          $('canvas').drawRect({
            layer: true,
            name: "zona_mano",
            strokeStyle: '#000',
            strokeWidth: 4,
            x: 400, y: 510,
            width: 330,
            height: 170,
            cornerRadius: 10
          });

          $('canvas').drawRect({
            layer: true,
            name: "zona_ultima_mano",
            strokeStyle: '#009',
            strokeWidth: 4,
            x: 90, y: 240,
            width: 230,
            height: 130,
            cornerRadius: 10
          });

          //pintamos las 3 cartas
          $('canvas').drawImage({
            layer:true,
            draggable: false,//arrastrable
            name: 'carta1',
            group: 'cartas',
            visible: false,//cartas_visibles[0],
            source: 'images/baraja/00_reverso.png',
            x: 300, y: 500,
            height: 123,
            bringToFront: true,
            width: 80,
          });

          $('canvas').drawImage({
            layer:true,
            draggable: false,//arrastrable
            name: 'carta2',
            group: 'cartas',
            visible: false,//cartas_visibles[0],
            source: 'images/baraja/00_reverso.png',
            x: 400, y: 500,
            height: 123,
            bringToFront: true,
            width: 80,
          });

          $('canvas').drawImage({
            layer:true,
            draggable: false,//arrastrable
            name: 'carta3',
            group: 'cartas',
            visible: false,//cartas_visibles[0],
            source: 'images/baraja/00_reverso.png',
            x: 500, y: 500,
            height: 123,
            bringToFront: true,
            width: 80,
          });


          //pinto las cartas jugadas
          for (var j = 1; j<5;j++){

              var nombre_carta = "cartas_jugadas_"+j;
            $('canvas').drawImage({
              layer: true,
              group: 'cartas_jugadas',
              name: nombre_carta,
              visible:false,
              source: 'images/baraja/00_reverso.png',
              x: coordX_cartas[j-1], y: coordY_cartas[j-1],
              height: 123,
              bringToFront: true,
              width: 80,
            });

          }

          //pinto las cartas jugadas en la ultima mano
          for (var j = 0; j<4;j++){

              var nombre_carta = "cj_ultima_mano"+j;
            $('canvas').drawImage({
              layer: true,
              group: 'cj_ultima_mano',
              name: nombre_carta,
              visible:false,
              source: 'images/baraja/00_reverso.png',
              x: coordX2_cartas[j], y: 240,
              height: 123,
              bringToFront: true,
              width: 80,
            });

          }
        tablero_pintado = true;
        $('#carga').hide();
        $('#partida').show();
}
function pintar_mano(estado_jug, estado){
    var cartas_visibles = [true,true,true];
    var cartas_jugadas_totales =$.parseJSON(estado.cartas_jugadas_totales);
    for (var j in cartas_jugadas_totales) {
      if (estado_jug.carta_uno == cartas_jugadas_totales[j]) cartas_visibles[0] = false;
      if (estado_jug.carta_dos == cartas_jugadas_totales[j]) cartas_visibles[1] = false;
      if (estado_jug.carta_tres == cartas_jugadas_totales[j]) cartas_visibles[2] = false;
    }
    var cartas_jugadas =$.parseJSON(estado.cartas_jugadas);
    for (var j in cartas_jugadas) {
      if (estado_jug.carta_uno == cartas_jugadas[j]) cartas_visibles[0] = false;
      if (estado_jug.carta_dos == cartas_jugadas[j]) cartas_visibles[1] = false;
      if (estado_jug.carta_tres == cartas_jugadas[j]) cartas_visibles[2] = false;
    }
    $('canvas').setLayer('carta1', {
      visible: cartas_visibles[0],
      draggable: true,
      x: 300, y: 500,
      source: 'images/baraja/'+estado_jug.carta_uno+'.png',
      bringToFront: true,
      dragstop: function(layer) {
        var distX, distY;
        if (layer.eventX > 215 && layer.eventX < 625 && layer.eventY > 125 && layer.eventY < 395) {
          $.post("<?= base_url() ?>" + 'juegos/nueva_jugada', {
            movimiento: estado_jug.carta_uno
          } );
        }
      }//800 600 215-420-625 125-260-395
    }).drawLayers();
    if (estado_jug.hasOwnProperty("carta_dos")) {
      $('canvas').setLayer('carta2', {
      visible: cartas_visibles[1],
      draggable: true,
      source: 'images/baraja/'+estado_jug.carta_dos+'.png',
      bringToFront: true,
      x: 400, y: 500,
      dragstop: function(layer) {
        var distX, distY;
        if (layer.eventX > 215 && layer.eventX < 625 && layer.eventY > 125 && layer.eventY < 395) {
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
        x: 400, y: 500,
        bringToFront: true
      }).drawLayers();
    }
  if (estado_jug.hasOwnProperty("carta_tres")) {
    $('canvas').setLayer('carta3', {
    visible: cartas_visibles[2],
    draggable: true,
    x: 500, y: 500,
    source: 'images/baraja/'+estado_jug.carta_tres+'.png',
    bringToFront: true,
    dragstop: function(layer) {
      var distX, distY;
      if (layer.eventX > 215 && layer.eventX < 625 && layer.eventY > 125 && layer.eventY < 395) {
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
      x: 500, y: 500,
      bringToFront: true
    }).drawLayers();
  }
}
function pintar_cartas_jugadas(estado){

  //if ($.parseJSON(estado.cartas_jugadas) != estado.vida){
    //alert("time to pintar cartas");

    if (typeof $.parseJSON(estado.cartas_jugadas) == "string") {
      cartas_jugadas_visibles = [false,false,false,false];
    } else {
        cartas_jugadas =$.parseJSON(estado.cartas_jugadas);

        for (var j = 1; j<cartas_jugadas.length;j++){
          cartas_jugadas_visibles[j-1] = true;
            var nombre_carta = "cartas_jugadas_"+j;
            $('canvas').setLayer(nombre_carta ,{
              source: 'images/baraja/'+cartas_jugadas[j]+'.png',
            });
        }
    }
    for (var j = 1; j<5;j++){
      var nombre_carta = "cartas_jugadas_"+j;
        $('canvas').setLayer(nombre_carta ,{
          visible: cartas_jugadas_visibles[j-1],
        });
    }

    //actualizo cartas ultima mano

    var ultima_mano =$.parseJSON(estado.ultima_mano);
    if (ultima_mano != null) {
      cartas_jugadas_visibles2= [false,false,false,false];
    for (var j = 0; j<ultima_mano.length;j++){
      cartas_jugadas_visibles2[j] = true;
      var nombre_carta = "cj_ultima_mano"+j;
      $('canvas').setLayer(nombre_carta ,{
        source: 'images/baraja/'+ultima_mano[j]+'.png',
      });
    }
    for (var j = 0; j<4;j++){
      var nombre_carta = "cj_ultima_mano"+j;
        $('canvas').setLayer(nombre_carta ,{
          visible: cartas_jugadas_visibles2[j],
        });
    }

    }
  //}
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
  if(estado.turno_jug != ultimo_jug || estado_jug.estado != estado_jugador){
      pintar_cartas_jugadas(estado);
      pintar_mano(estado_jug, estado);

      mostrar_jugadas();
      ultimo_jug = estado.turno_jug;
  }
  if (!tablero_pintado){
    pintar_tablero(estado,jugadorino);
    pintar_mano(estado_jug, estado);
    pintar_cartas_jugadas(estado);
    mostrar_jugadas();
  }

  var active_player = estado.turno_jug;
  active_player = active_player.substr(active_player.length - 1);
  if (nicks_fijos[active_player - 1] == nicks[0]) {
    $('#i_estado').text("Tu turno");
  } else {
    $('#i_estado').text("Turno de "+nicks_fijos[active_player - 1]);
  }
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
      $('#ataque').fadeTo("slow", 0.35);
      $('#defensa_mas').fadeTo("slow", 0.35);
      $('#defensa_quiero').fadeTo("slow", 0.35);
      $('#defensa_paso').fadeTo("slow", 0.35);
    }
    estado_jugador = estado_jug.estado;
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
        $('#final').hide();
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
        $("#nombre").text(" de "+estado.nombre);
        $("#boton_nombre").click(function() {
          if ($("#nombre_partida").val() != ""){
            $.post("<?= base_url() ?>" + 'juegos/nuevo_nombre', {
              nombre: $("#nombre_partida").val()
            } );
          }
        });
        en_partida = false;
          $.cookie('jugando', 'true', { expires: 1, path: '/' });
      } else if (estado['estado'] == "abandonando" || estado['estado'] == "terminando1" || estado['estado'] == "terminando2"){
        $('#final').show();
        $('#lobby').hide();
        $('#menu').hide();
        $('#partida').hide();
        if (estado['estado'] == "abandonando") {
          $('#info_fin_partida').text("Un jugador ha abandonado la partida D:");
        } else {
          var texto = "El equipo "+estado['estado']+" ha ganado la partida.";
          texto = texto.replace("terminando1", "1").replace("terminando2", "2");
          if (estado['estado'] == "terminando1"){
            var texto2 = "Ganadores: "+estado['jug_1_nick']+ ", "+estado['jug_3_nick']+" Losers: "+estado['jug_2_nick']+ ", "+estado['jug_4_nick'];
          } else {
            var texto2 = "Ganadores: ";
          }
          $('#info_fin_partida').text(texto);
          $('#info_equipos_fin_partida').text(texto2);
        }
        $('#confirmar_salir').click(function() {
          $.post("<?= base_url() ?>" + 'juegos/confirmar_salir');
        });
      } else if (estado['estado'] == "jugando"){
        $('.estado').text('En partida  ');
        if (!en_partida)    {
          $('#carga').show();
        }
        if (carga >= 100){
          clearInterval(barracarga);
        }
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
        $('#final').hide();
        mostrar();
        $(this).keydown(function (e) {
            if(e.keyCode == 13){      enviar();        }//intro
          });
          $("#enviar").click(enviar);
          $("#enviar_privado").click(move);
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
      $('#final').hide();
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
        $('#listado').append('<p> Partida: '+ estado[mimi].nombre +' | Jugadores: '+ jugadores +' de 4</p>');
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
