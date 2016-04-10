<html>
<head>
  <meta charset="utf-8">
  <title>prueba chat</title>
  <style>
    #chat {
      border: 1px solid green;
      width: 400px;
      padding: 10px;
    }
    #mensajes {
      border: 1px solid blue;
    }
    #mov {
      position: relative;
      width: 100px;
      border: 0px;
    }
    #parr{
      background-color: red;
    }
  </style>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
  </script>
  <script>
    $(document).ready(function() {
      var valor = 0;
      var calculo;
      $("#ay").mousedown(function () {
        $("#parr").css("background-color" , "red")
        $("#mov").animate({left : "+=100"}, 500);
        calculo = setInterval(function(){
          valor+= 0.1;
          $("#valor").val(valor*10);
          if (valor > 5 && valor < 7) {
            $("#parr").css("background-color" , "green");
          } else $("#parr").css("background-color" , "red");
        }, 1);
      });
      $("#ay").mouseup(function () {
        if (valor > 10) {
          valor = 10;
        }
        $("#mov").animate({left : "-=100"});
        $("#parr").css("background-color" , "red");
        var valor_final = Math.floor(valor * 10);
        valor = 0;
        clearInterval(calculo);
        if (valor_final > 50 && valor_final < 70) alert("yay");
      });



      $.getJSON("<?= base_url() ?>" + 'chats/ver_mensajes', pintar);
      setInterval(function(){
        $(this).attr("title", "prueba chat");
        $.getJSON("<?= base_url() ?>" + 'chats/ver_mensajes', pintar);
      }, 3000);

          $("#enviar").click(function () {
            if ($("#contenido").val() != "") {
              $.getJSON("<?= base_url() ?>" + "chats/escribir_mensajes/" + $("#usuario").val() + "/"+ $("#contenido").val());
              $("#contenido").val("");
              $.getJSON("<?= base_url() ?>" + 'chats/ver_mensajes', pintar);
            } else {
              alert('Debes introducir un comentario');
            }

          });
        });

    function pintar(r) {
      $('#mensajes').empty();
      for (var cosa in r){
        $('#mensajes').append('<p>'+r[cosa].usuario+': '+r[cosa].contenido + '</p>');
      }
    }
  </script>
</head>
<body>
    <div id="juego">
      <div id="chat">
      <h2>Chat</h2>
      <div id="mensajes">
      </div>
      <form>
        <input type="text" id="usuario" placeholder="Usuario"><br />
        <input type="text" id="contenido" placeholder="Escribe tu mensaje">
        <input type="button" id="enviar" value="Enviar">
      </form>
    </div>
    <div id="partida">
    </div>
  </div>
  <div id="mov">
    <p>-<span id="parr">o</span>-</p>
  </div>
  <button value="ay" id="ay">pulsa</button>
    <input type="text" id="valor" placeholder="0"><br />
</body>
</html>
