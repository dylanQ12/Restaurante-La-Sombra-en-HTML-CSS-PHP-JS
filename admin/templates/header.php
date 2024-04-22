<?php
$url_base = 'http://localhost/Restaurante/admin/';

function isActive($current_link, $url_base)
{
    // Obtén la URL actual sin los parámetros GET.
    $current_page = strtok($_SERVER["REQUEST_URI"], '?');

    // Compara la base de la URL más el enlace con la URL actual.
    return ($url_base . $current_link == $current_page) ? 'active' : '';
}
?>


<!doctype html>
<html lang="en">

<head>
    <title>Administrador | Restaurante La sombra</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Estilos css -->
    <link rel="stylesheet" href="../../../css/normalize.css">
    <link rel="stylesheet" href="../../../css/admin/navbar.css">
    <link rel="stylesheet" href="../../../css/admin/dashboard.css">
    <link rel="stylesheet" href="../../../css/admin/paginador.css">
    <link rel="stylesheet" href="../../../css/admin/formulario.css">
    <link rel="stylesheet" href="../../../css/admin/tabla.css">
    <link rel="stylesheet" href="../../../css/admin/botones.css">
    <link rel="stylesheet" href="../../../css/admin/alertas.css">

<body>
    <header>
        <nav class="navbar navbar-expand navbar-primary bg-light">
            <div class="nav navbar-nav">
                <a class="nav-item nav-link <?php echo isActive('seccion/banners/', $url_base); ?>" href="<?php echo $url_base; ?>seccion/banners/"><i class="fa fa-images"></i> Banners</a>
                <a class="nav-item nav-link <?php echo isActive('seccion/colaboradores/', $url_base); ?>" href="<?php echo $url_base; ?>seccion/colaboradores/"><i class="fa fa-users"></i> Colaboradores</a>
                <a class="nav-item nav-link <?php echo isActive('seccion/testimonios/', $url_base); ?>" href="<?php echo $url_base; ?>seccion/testimonios/"><i class="fa fa-star"></i> Testimonios</a>
                <a class="nav-item nav-link <?php echo isActive('seccion/menu/', $url_base); ?>" href="<?php echo $url_base; ?>seccion/menu/"><i class="fa fa-utensils"></i> Menú</a>
                <a class="nav-item nav-link <?php echo isActive('seccion/comentarios/', $url_base); ?>" href="<?php echo $url_base; ?>seccion/comentarios/"><i class="fa fa-comments"></i> Comentarios</a>
                <a class="nav-item nav-link <?php echo isActive('seccion/usuarios/', $url_base); ?>" href="<?php echo $url_base; ?>seccion/usuarios/"><i class="fa fa-user-plus"></i> Usuarios</a>
                <a class="nav-item nav-link" href="<?php echo $url_base; ?>cerrar.php"><i class="fa fa-sign-out-alt"></i> Cerrar sesión</a>
            </div>
        </nav>
    </header>


    <main>
        <section class="container">