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
        <link href='https://fonts.googleapis.com/css?family=Slackey' rel='stylesheet' type='text/css'>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/jcanvas.min.js"></script>
        <script src="/js/jquery.cookie.js"></script>
    </head>
    <body itemscope itemtype="http://schema.org/VideoGame"><header>
            <a href="#cuerpo"><img src="/images/logo.png" itemprop="image" title="Inicio" alt="inicio" /></a>
            <h1>Tactical Rentoy Action!</h1>
            <article>
              <div id="info_usuario">
              <?php if (!logueado()): ?>
                <?= form_open('usuarios/login') ?>
                  <div class="inputerino">
                    <?= form_input('nick', set_value('nick', '', FALSE),
                                   'id="nick" placeholder="Nick" class="form-control"') ?>
                  </div>
                  <div class="inputerino">
                    <?= form_password('password', '',
                                      'id="password" placeholder="Password" class="form-control"') ?>
                  </div>
                  <?= form_submit('login', 'Login') ?>
                  <?= anchor('/usuarios/registro', 'Registrate', ' role="button"') ?>
                <?= form_close() ?>

              <?php else: ?>
                    <p class="whiterino">Bienvenido <i><?= nick(usuario_id())?></i></p>
                    <a  href="<?= base_url() ?>usuarios/logout" class="btn btn-warning" title="Logout">Salir</a>
              <?php endif;?>

            </div>

            <?php if (!logueado()): ?>
              <img src="/upload/fotoPerfil.png" itemprop="image" title="Foto de perfil" alt="Profile picture"/>
            <?php else: ?>
              <img src="/upload/<?= foto(usuario_id())?>" itemprop="image" title="Foto de perfil" alt="Profile picture"/>
            <?php endif;?>

            </article>
        </header>
        <nav>
            <a href="<?= base_url() ?>juegos/juego" title="Inicio">Inicio</a>
            <a href="<?= base_url() ?>juegos" title="Jugar ahora">Jugar</a>
            <a href="<?= base_url() ?>juegos/tutorial" title="Aprende a jugar">Tutorial</a>
            <a href="<?= base_url() ?>usuarios/cuenta" title="Cuenta de usuario">Perfil</a>
            <a href="<?= base_url() ?>juegos/multimedia" title="Videos">Multimedia</a>
        </nav>
        <section id="cuerpo" class="container-fluid" >
          <div class="row">
            <div class="col-sm-1"></div>
            <?= $contents ?>
            <div class="col-sm-1"></div>
          </div>
        </section>
        <footer>
        <div>
        </div>
          <div>
            <h4>Webmaster: Sniv</h4>
            <div>
            </div>
            <h4>e-mail: sniv@gmail.com</h4>
          </div>
          <div>
            <a href="http://www.twitter.com"><img itemprop="image" alt="twitter" src="/images/Twitter_logo_blue.png" ></a>
            <div>
            </div>
            <a href="http://www.facebook.com"><img itemprop="image" alt="facebook" src="/images/facebook%20logo%20vector.png" ></a>
          </div>
          <div>
          </div>
        </footer>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
                integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
                crossorigin="anonymous"></script>
    </body>
</html>
