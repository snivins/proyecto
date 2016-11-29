<html>
<head>
  <style>
  body {
    background-color: #06f;

    display: -webkit-flex;
    display: flex;
    -webkit-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-align-content: center;
    align-content: center;

     -webkit-flex-direction: column;
     flex-direction: column;
  }
  h2 {
    text-align: center;
  }
  </style>
</head>
<body>
<h2>Actualmente en una partida</h2>

<button onclick="myFunction()">Ir a partida</button>

<script>
function myFunction() {
  window.opener.document.location="<?= base_url() ?>juegos"
window.close();
}
</script>
</body>
  </html>
