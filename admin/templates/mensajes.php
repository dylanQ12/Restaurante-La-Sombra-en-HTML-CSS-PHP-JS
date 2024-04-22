<?php if (isset($estado_mensaje)) : ?>
    <?php if ($estado_mensaje == 1) : ?>
        <div class="alert alert-success alert-dismissible fade show alertas" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            ¡Guardado satisfactoriamente!
        </div>

    <?php elseif ($estado_mensaje == 2) : ?>
        <div class="alert alert-success alert-dismissible fade show alertas" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            ¡Actualizado satisfactoriamente!
        </div>

    <?php elseif ($estado_mensaje == 3) : ?>
        <div class="alert alert-success alert-dismissible fade show alertas" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            ¡Eliminado satisfactoriamente!
        </div>
    <?php endif; ?>
<?php endif; ?>


<?php if (isset($enviar)): ?>
   <?php if ($enviar == 1) : ?>
     <div class="alert alert-success alert-dismissible fade show alertas" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        ¡Comentario enviado satisfactoriamente!
     </div>
    <?php endif; ?>
<?php endif; ?>


<?php if (isset($errores)) : ?>
    <?php if ($errores == 1) : ?>
        <div class="alert alert-danger alert-dismissible fade show alertas" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            ¡Usuario o Password incorrecta!
        </div>

    <?php elseif ($errores == 2) : ?>
        <div class="alert alert-danger alert-dismissible fade show alertas" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            ¡El Usuario no existe!
        </div>
    <?php endif; ?>
<?php endif; ?>

