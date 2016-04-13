<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= isset($title) ? $title : '' ?></title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet"
              href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
              integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
              crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet"
              href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
              integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
              crossorigin="anonymous">

        <!-- Estilo propio -->
        <link rel=stylesheet href="estilos/general.css" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Amatic+SC' rel='stylesheet' type='text/css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    </head>
    <body><header>
            <a href="#cuerpo"><img src="images/logo.png" title="Inicio" alt="inicio" /></a>
            <h1>Dungeons &amp; lizards</h1>
            <article>
                <form>
                    <input type="text" title="Usuario" name="user" placeholder="User" required><br />
                    <input type="password" title="Password" name="pass" placeholder="Password" required><br />
                    <input type="submit" value="Login">
                    <a  href="registro.html" title="Registrarse">Registrate</a>
                </form>
                <img src="images/fotoPerfil.png" title="Foto de perfil" alt="Profile picture"/>
            </article>
        </header>
        <nav>
            <a href="index.html" title="Inicio">Home</a>
            <a href="juego.html" title="Jugar ahora">Jugar</a>
            <a href="index.html" title="Aprende a jugar">Tutorial</a>
            <a href="index.html" title="Sobre nosotros">Acerca de</a>
            <a href="index.html" title="Videos and shit">Multimedia</a>
        </nav>
        <section id="cuerpo" class="container-fluid" >
          <div class="row">
            <div >
            <div class="col-md-1"></div>
            <?= $contents ?>
            </div>
          </div>
        </section>
        <hr />
        <footer>

            <h4>Webmaster: Sniv</h4>
            <h4>e-mail: sniv@gmail.com</h4>
            <a href="http://www.twitter.com"><img alt="twitter" src="images/Twitter_logo_blue.png" ></a>
            <a href="http://www.facebook.com"><img alt="facebook" src="images/facebook%20logo%20vector.png" ></a>
        </footer>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
                integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
                crossorigin="anonymous"></script>
    </body>
</html>
