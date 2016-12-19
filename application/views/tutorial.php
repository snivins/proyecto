<?php template_set('title', 'Tutorial') ?>
  <h2>Tutorial</h2>
  <div id="info_tutorial">
    <div id="tutorial_contenido">
      <fieldset>
        <legend>Indice:</legend>
        <ol id="indice">
          <li><a href="#introduccion" class="tuto_a" target="_self">Introducción al rentoy</a></li>
          <li><a href="#condiciones" class="tuto_a" target="_self">Condiciones de victoria</a></li>
          <li><a href="#acciones" class="tuto_a" target="_self">Acciones del jugador</a></li>
          <li><a href="#valores" class="tuto_a" target="_self">Valor de las cartas</a></li>
          <li><a href="#rondas" class="tuto_a" target="_self">Rondas y turnos</a></li>
          <li><a href="#reglas" class="tuto_a" target="_self">Reglas especiales</a></li>
        </ol>
      </fieldset>
      <h2 id="introduccion">1.- Introducción al rentoy</h2>
      <p>El rentoy es un juego de cartas para 4 jugadores,
        estos juegan por parejas en dos equipos colocados uno frente a otro de
        forma que enfrente tengas a tu compañero de equipo y un jugador enemigo
      a cada lado. <br> Es un juego en el que la comunicación en equipo es
     importante asi como la habilidad de dar faroles o verlos al momento, y suerte.</p>
     <a href="#indice" class="tuto_a" target="_self">Volver al índice</a>
     <h2 id="condiciones">2.- Condiciones de victoria</h2>
     <p> El objetivo del juego es llegar a 30 puntos, el juego se juega por rondas,
        siempre hay un ganador y al menos se lleva un punto.<br>
        La forma mas común de ganar puntos es viendo que carta tiene un valor mas
         alto al jugar 4 cartas, pero tambien se puede aumentar la cantidad de
         puntos por ronda o de ganar puntos cuando el equipo enemigo se niega a
         jugar por mas puntos.
     </p>
     <a href="#indice" class="tuto_a" target="_self">Volver al índice</a>
     <h2 id="acciones">3.- Acciones del jugador</h2>
     <p> La acción mas habitual es jugar una carta, simplemente arrastrala de tu mano cuando sea tu turno.
       Tambien puedes pedir mas puntos(Envio), pasando de 1 a 3 o en caso de ser mayor que 3 se suma 3 a los puntos
       que se estan jugando actualmente. Al hacer esto el siguiente jugador a tu izquierda debe elegir entre:
       <dl>
        <dt>Aceptar los puntos(Quiero)</dt>
        <dd>En cuyo caso el turno vuelve al jugador que envio inicialmente y debe jugar una carta de su mano,
           la ronda sigue hasta que se jueguen las 4 cartas, mientras un jugador pueda jugar puede seguir enviando
            y repetir este proceso solo que con los puntos acumulados</dd>
        <dt>Pasar</dt>
        <dd>El jugador no quiere jugar a los puntos que pide el contrario, asi que los puntos van al equipo que pide mas
        aunque se llevan los puntos previos a los que pedian</dd>
        <dt>Pedir mas puntos(Mas)</dt>
        <dd>Consiste en añadir 3 puntos a los pedidos y pasar el turno al jugador contrario, este tiene que repetir este proceso,
        </dd>
      </dl>
      Recuerda que ambos jugadores, el que envia inicialmente y el contrario a su izq, pueden ambos hacer estas acciones pero si cualquiera
      dice quiero el jugador que debe jugar la carta es el que envia inicialmente.
     </p>
     <a href="#indice" class="tuto_a" target="_self">Volver al índice</a>

     <h2 id="valores">4.- Valores de las cartas</h2>
     <p>Para saber el valor que tienen las cartas debes de tener en cuenta la "vida" en juego, es la carta que se pone boca arriba cada vez que se baraja
     <br>Primero esta el palo de la vida, puede ser espada, oro, bastos o copas. Las cartas del mismo palo de la vida ganan a todas las demas, en cuanto a los otros
       3 palos sigue habiendo una diferencia, en caso de que la primera carta jugada en una ronda sea de un palo distinto a la vida, este palo vale mas que los otros 2
     En resumen tenemos El palo de la vida &gt; El palo inicial &gt; Los otros 2 restantes<br>
     Ahora hablemos del valor numérico de las cartas, en este caso solo hay 2 variaciones.
     <dl>
       <dt>Si las cartas comparadas son del palo de la vida</dt>
       <dd>De menor a mayor: 3 &lt; 4 &lt; 5 &lt; 6 &lt; 7 &lt; 1 &lt; 10 &lt; 11 &lt; 12 &lt; 2. Siendo el dos la carta de mayor valor
       </dd>
       <dt>Si las cartas comparadas son del mismo palo pero distinto de la vida</dt>
       <dd>De menor a mayor: 2 &lt; 3 &lt; 4 &lt; 5 &lt; 6 &lt; 7 &lt; 1 &lt; 10 &lt; 11 &lt; 12. Siendo el rey la carta de mayor valor
       </dd>
      </dl>
      La diferencia es el 2 que si es de la vida es lo que mas vale y si no es lo que menos.
    </p>
    <a href="#indice" class="tuto_a" target="_self">Volver al índice</a>

    <h2 id="rondas">5.- Rondas y puntos</h2>
    <p>Una partida se puede dividir en 2 partes, la parte inicial en la que se reparte cada 3 rondas, en las cuales se reparten 1,2,3 cartas sucesivamente
    y se vuelve a barajar.
  A partir de que un equipo sobrepase los 21 puntos cada ronda consiste en 3 cartas y barajar</p>
   <a href="#indice" class="tuto_a" target="_self">Volver al índice</a>
   <h2 id="reglas">6.- Reglas especiales</h2>
   <p>En el juego real la comunicación privada en el equipo es esencial pero complicada debido a las posiciones, para
   emular eso el chat donde transcurre la comunicación tiene un modo de envio privado pero deberas superar un sencillo minijuego para
 evitar que el mensaje sea publico</p>
  <a href="#indice" class="tuto_a" target="_self">Volver al índice</a>
    </div>
</div>
<script>
$(document).ready(function() {
  if ($.cookie('jugando') != undefined){
    var ancho = screen.availWidth/ 2;
    ancho -= 200;
    var alto = screen.availHeight/ 2;
    alto -= 60;
    var myWindow = window.open("<?= base_url() ?>usuarios/recordatorio", "myWindow", "width=400,height=120,left="+ancho+",top="+alto+"");

  }

});
</script>
