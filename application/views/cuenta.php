<?php template_set('title', 'Cuenta de usuario') ?>

<h2>Cuenta de usuario</h2>
            <?php if (!logueado()): ?>
        <div class="formulario">
          <?php $variable = error_array();
           if ( ! empty($variable)): ?>
            <div class="alert alert-danger" role="alert">
              <?= validation_errors() ?>
            </div>
          <?php endif ?>

        <?= mensajes() ?>
          <?= form_open('usuarios/login') ?>
            <div class="form-group">
              <?= form_label('Nick:', 'nick') ?>
              <?= form_input('nick', set_value('nick', '', FALSE),
                             'id="nick" class="form-control"') ?>
            </div>
            <div class="form-group">
              <?= form_label('Contraseña:', 'password') ?>
              <?= form_password('password', '',
                                'id="password" class="form-control"') ?>
            </div>
            <?= form_submit('login', 'Login', 'class="btn btn-success"') ?>
            <?= anchor('/usuarios/registro', 'Registrame', 'class="btn btn-info" role="button"') ?>
          <?= form_close() ?>

        </div>

        <?php else: ?>
            <div id="info">

              <p>Usuario: <?= $info_usuario_nick['nick'] ?> </p>
              <p>Foto de perfil:</p><img src="/upload/<?= $info_usuario_foto['foto_perfil'] ?>"></img>


<?php echo form_open_multipart('usuarios/foto_perfil');?>

<input type="file" name="foto" size="20" />

<?php
 if ( ! empty($error)): ?>
  <div class="alert alert-danger" role="alert">
    <?= $error ?>
  </div>
<?php endif ?>
<?php
 if ( ! empty($exito)): ?>
  <div id="exito">
    Imagen subida con exito
  </div>
<?php endif ?>

<input type="submit" value="Subir" />

</form>
            </div>
            <div class="panel-body">
              <?php if (isset($filas)) {
                ?> <a href="<?= base_url() ?>pdfs/generar" id="texto_negro" title="Listado">Listado como pdf</a>
                  <table border="1"
                         class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                      <th>Nick</th>
                      <th>Acciones</th>
                    </thead>
                    <tbody>
                      <?php foreach ($filas as $fila): ?>
                        <tr>
                          <td><?= $fila['nick'] ?></td>
                          <td align="center">
                            <?= anchor('/usuarios/borrar/' . $fila['id'], 'Borrar',
                                       'class="btn btn-danger btn-xs" role="button"') ?>
                          </td>
                        </tr>
                      <?php endforeach ?>
                    </tbody>
                  </table>
                    <?php
              }?>

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
          <?php endif;
