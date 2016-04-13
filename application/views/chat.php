<?php template_set('title', 'Chat time') ?>
<body>
    <div id="juego">
      <div id="partida">

        <canvas id="canvas" width="700" height="600" >
        Your browser does not support the HTML5 canvas tag.</canvas>

      </div>
      <div id="chat">
        <h2>Chat</h2>
        <div id="mensajes">
        </div>
        <form>
          <input type="text" id="usuario" placeholder="Usuario"><br />
          <input type="text" id="contenido" placeholder="Escribe tu mensaje">
          <input type="button" id="enviar" value="Enviar mensaje publico">
          <input type="button" id="enviar_privado" value="Enviar mensaje privado"><br/>
          <input type="button" id="rondo" value="Cuenta atras">
        </form>
      </div>
  </div>
  <div id="minijogo">
    <div id="mov">
      <p>-<span id="parr">o</span>-</p>
    </div>
    <button value="ay" id="ay">pulsa</button>
    <input type="text" id="valor" placeholder="0"><br />
  </div>
  <script>
  var tiempo;

    var valor = 0;
    var calculo;
    var cuenta;
    var valido = false;
    var segs = 0;
    $(document).ready(function() {
      $("#minijogo").hide();
      //calculo de mensaje privado
      $("#ay").mousedown(function () {
          mini_start();
      });

      $("#ay").mouseup(function () {
          mini_stop();
      });
      $(this).keydown(function (e) {
        if(e.keyCode == 32){      mini_start();    }//espacio
        if(e.keyCode == 13){      enviar();        }//intro
      });
      $(this).keyup(function (e) {
        if(e.keyCode == 32){      mini_stop();     }
      });

      $("#rondo").click(function () {
          resetTimers();
          cuentaAtras();
      });




      setInterval(function(){
        mostrar();
      }, 3000);

      $("#enviar_privado").click(function () {
        $("#minijogo").show();
        if (!valido) valido = true;
        setTimeout(function() {
          $("#minijogo").hide();
          valido = false;
        },10000);
      });

      $("#enviar").click(enviar);
    });

    //funciones
    function reloj() {

      segs = 30;//tiempo por turno

      tiempo = setInterval(function () {
        segs--;
      }, 1000);
    }


    function resetTimers(){
        clearInterval(cuenta);
        clearInterval(tiempo);
    }
    function mostrar() {
      $.getJSON("<?= base_url() ?>" + 'chats/ver_mensajes', pintar);
    }
    function cuentaAtras() {
        var canvas = document.getElementById("canvas");
        var ctx = canvas.getContext("2d");
        var total = 0;
        //pinta el circulo
        ctx.beginPath();
        ctx.strokeStyle= 'black';
        ctx.arc(650, 50, 25, 0, 2 * Math.PI);
        ctx.lineWidth  = 2;
        ctx.stroke();
        reloj();
        //fecha.setSeconds(0);
        cuenta = setInterval(function(){
          $("#canvas").stop(true,true);
            total = 2-((segs/30) * 2);
            // if (sec != secAntiguo) {
              ctx.beginPath();
              ctx.fillStyle= '#79CAF9';
              ctx.arc(650, 50, 20, 0, 2 * Math.PI);
              ctx.fill();
              ctx.font = "30px Arial";
              ctx.fillStyle= 'black';
              if (segs >= 0){
                if (segs < 10) {
                  ctx.fillText(segs,643,61);
                } else {
                  ctx.fillText(segs,633,61);
                }
              } else {
                resetTimers();
              }


            ctx.beginPath();
            ctx.strokeStyle= '#79CAF9';
            ctx.lineWidth   = 2;
            ctx.arc(650, 50, 25, 1.5 * Math.PI, (1.5 + total) * Math.PI);
            ctx.stroke();
        },1);

    }


    function enviar () {
      if ($("#contenido").val() != "") {
        $.getJSON("<?= base_url() ?>" + "chats/escribir_mensajes/" + $("#contenido").val() + "/"+ $("#usuario").val());
        $("#contenido").val("");
        mostrar();
      } else {
        alert('Debes introducir un comentario');
      }
    }
    function pintar(r) {
      $('#mensajes').empty();
      for (var cosa in r){
        $('#mensajes').append('<p><i><b>'+r[cosa].usuario+':</i></b> '+r[cosa].contenido + '</p>');
      }
    }

    function mini_start() {
        //en caso de que se bugee
        if (valido && valor == 0) {
          valor = 0;
          $("#parr").css("background-color" , "red")
          $("#mov").animate({left : "+=200"}, 500);
          calculo = setInterval(function(){
            valor+= 0.1;
            $("#valor").val(valor*10);
            if (valor > 5 && valor < 7) {
              $("#parr").css("background-color" , "green");
            } else if (valor > 3 && valor < 5) {
              $("#parr").css("background-color" , "yellow");
            } else if (valor > 7 && valor < 9) {
              $("#parr").css("background-color" , "yellow");
            } else $("#parr").css("background-color" , "red");
          }, 1);
        }
    }

    function mini_stop() {
      $("#mov").animate({left : "-=200"});
      $("#parr").css("background-color" , "red");
      var valor_final = Math.floor(valor * 10);
      valor = 0;
      clearInterval(calculo);
      if (valor_final > 50 && valor_final < 70) alert("yay");
    }


  </script>
