<?php template_set('title', 'Tutorial') ?>
  <h2>Tutorial</h2>
  <div id="info">
    COMING SOON !! !!
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
