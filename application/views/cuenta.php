<?php template_set('title', 'Cuenta de usuario') ?>

            <?php if (!logueado()): ?>
<h2>Cuenta de usuario</h2>
        <div class="formulario">
          <?php if ( ! empty(error_array())): ?>
            <div class="alert alert-danger" role="alert">
              <?= validation_errors() ?>
            </div>
          <?php endif ?>
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
            <?= anchor('/usuarios/registrar', 'Registrame', 'class="btn btn-info" role="button"') ?>
          <?= form_close() ?>

        </div>

                    <?php else: ?>
                        <div>
                          <p>laoalaolaa</p>
                        </div>
                    <?php endif;
