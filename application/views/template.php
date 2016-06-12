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
        <link rel=stylesheet href="/estilos/general.css" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Amatic+SC' rel='stylesheet' type='text/css'>
        <script src="js/jquery.min.js"></script>
        <script src="js/jcanvas.min.js"></script>
    </head>
    <body><header>
            <a href="#cuerpo"><img src="/images/logo.png" title="Inicio" alt="inicio" /></a>
            <h1>Rentoy time!!</h1>
            <article>
              <?php if (!logueado()): ?>
                <?= form_open('usuarios/login') ?>
                  <div class="form-group">
                    <?= form_label('Nick:', 'nick') ?>
                    <?= form_input('nick', set_value('nick', '', FALSE),
                                   'id="nick" ') ?>
                  </div>
                  <div class="form-group">
                    <?= form_label('Contraseña:', 'password') ?>
                    <?= form_password('password', '',
                                      'id="password" ') ?>
                  </div>
                  <?= form_submit('login', 'Login') ?>
                  <?= anchor('/usuarios/registrar', 'Registrate', ' role="button"') ?>
                <?= form_close() ?>

              <?php else: ?>
                    <p>Bienvenido <?= nick(usuario_id())?></p>
                    <a  href="<?= base_url() ?>usuarios/logout" title="Logout">Salir</a>
              <?php endif;?>
                <img src="/images/fotoPerfil.png" title="Foto de perfil" alt="Profile picture"/>

            </article>
        </header>
        <nav>
            <a href="<?= base_url() ?>" title="Inicio">Home</a>
            <a href="<?= base_url() ?>juegos" title="Jugar ahora">Jugar</a>
            <a href="index.html" title="Aprende a jugar">Tutorial</a>
            <a href="<?= base_url() ?>usuarios/cuenta" title="Cuenta de usuario">Perfil</a>
            <a href="index.html" title="Videos and shit">Multimedia</a>
        </nav>
        <section id="cuerpo" class="container-fluid" >
          <div class="row">
            <div class="col-sm-1"></div>
            <?= $contents ?>
            <div class="col-sm-1"></div>
          </div>
        </section>
        <hr />
        <footer>

            <h4>Webmaster: Sniv</h4>
            <h4>e-mail: sniv@gmail.com</h4>
            <a href="http://www.twitter.com"><img alt="twitter" src="/images/Twitter_logo_blue.png" ></a>
            <a href="http://www.facebook.com"><img alt="facebook" src="/images/facebook%20logo%20vector.png" ></a>
        </footer>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
                integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
                crossorigin="anonymous"></script>
    </body>
</html>
