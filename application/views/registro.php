<?php template_set('title', 'Rentoy time') ?>
<h2>Registro de usuario</h2>
        <div class="formulario">
        <?= mensajes() ?>
        <?php if ( ! empty(error_array())): ?>
            <div class="alert alert-danger" role="alert">
              <?= validation_errors() ?>
            </div>
          <?php endif ?>
<?= form_open('usuarios/registrar') ?>
           <div class="form-group">
             <?= form_label('Nick:', 'nick') ?>
             <?= form_input('nick', set_value('nick', '', FALSE),
                            'id="nick" class="form-control"') ?>
           </div>
           <div class="form-group">
             <?= form_label('Email:', 'email') ?>
             <?= form_input(array(
                           'type' => 'email',
                           'name' => 'email',
                           'id' => 'email',
                           'value' => set_value('email', '', FALSE),
                           'class' => 'form-control'
             )) ?>
           </div>
           <div class="form-group">
             <?= form_label('Contraseña:', 'password') ?>
             <?= form_password('password', '',
                               'id="password" class="form-control"') ?>
           </div>
           <div class="form-group">
             <?= form_label('Confirmar Contraseña:', 'password_confirm') ?>
             <?= form_password('password_confirm', '',
                               'id="password_confirm" class="form-control"') ?>
           </div>
           <?= form_submit('registrar', 'Registrar', 'class="btn btn-success"') ?>
           <?= anchor('/juegos', 'Volver', 'class="btn btn-info" role="button"') ?>
         <?= form_close() ?>
       </div>
