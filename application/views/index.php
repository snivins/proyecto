<?php template_set('title', 'Inicio') ?>
<div id="inicio">
  <h2>Inicio</h2>
  <div id="info">
    <p itemprop="description"><i itemprop="name">Tactical rentoy action</i> es un <span itemscope="genre">juego online multijugador de cartas</span> que te permite jugar rapidamente sin complicaciones, solo registrate y juega.
    <br />Si nunca has jugado al rentoy visita el <a href="<?= base_url() ?>juegos/tutorial" title="Aprende a jugar">tutorial</a> donde te explicamos las reglas basicas
  <br />Si no las dominas no te preocupes el juego te ayudara con información visual sobre que movimientos puedes hacer.
  <br />¿Quieres jugar? Registrate o logueate y juega hasta que te sangren los ojos</p>
<br />
<div id="enlaces">
  <a href="<?= base_url() ?>usuarios/registro" title="Registrate">Nuevo usuario</a><a href="<?= base_url() ?>usuarios/login" title="Inicia sesión">Login  </a>
</div>
<br />
<br />
    <p id="descripcion" itemprop="about">Tradicionalmente los pescadores del litoral gaditano disfrutan en sus ratos libres de un juego de cartas que es conocido con el nombre de rentoy. En la provincia de Cádiz se practican dos modalidades, la de Sanlúcar y la de Conil, donde también es llamado ‘la guinea’. Las partidas normalmente se juegan por parejas y si se realizan al estilo de Sanlúcar a cada jugador se le dan tres cartas repartiéndose cinco en la forma de Conil. En ambos casos siempre se saca una carta boca arriba sobre la mesa que es la de triunfo.

    Para poner un ejemplo el modelo de Sanlúcar se juega en El Puerto de Santa María y la carta de mayor valor es el dos de triunfo, que se le llama ‘la malilla’, a continuación las figuras y el as, siendo el tres de triunfo (el llera) la de menor valía. En el de Conil, que se juega también en Barbate, las cartas más importantes son el cuatro de bastos (el mujero), el caballo de oro (el tuerto), el tres de triunfo (la andorra), el dos de triunfo (la malilla) y después las figuras de triunfos… De cualquier forma tienen un denominador común, el farol, que es la jugada falsa hecha para engañar al contrario.<p>
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
