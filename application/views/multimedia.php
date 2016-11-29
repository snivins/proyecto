<?php template_set('title', 'Multimedia') ?>

  <h2>Videos sobre rentoy</h2>
<div id="videos">
    <iframe width="560" height="315" src="https://www.youtube.com/embed/XHgzT2flN-E" frameborder="0" allowfullscreen></iframe>

    <iframe width="560" height="315" src="https://www.youtube.com/embed/Yq1zrnCNiGo" frameborder="0" allowfullscreen></iframe>

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
