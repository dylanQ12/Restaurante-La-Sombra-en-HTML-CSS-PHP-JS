<?php
include 'bd.php';

$estado_mensaje = $_GET['resultado'] ?? null;
$errores = $_GET['error'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? null;
    $password_ingresada = $_POST['password'] ?? null;

    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $resultado = $conexion->prepare($sql);
    $resultado->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $resultado->execute();
    $fila = $resultado->fetch(PDO::FETCH_LAZY);
    $usuario = $fila['usuario'];
    $password = $fila['password'];

    if ($fila) {
        if (password_verify($password_ingresada, $password)) {
            session_start();
            $_SESSION['usuario'] = $fila['nombre'];
            header('Location: seccion/banners/index.php?resultado');
            exit;
        } else {
            // Si la contraseña no coincide
            header('Location: index.php?error=1');
            exit;
        }
    } else {
        // Si el usuario no existe
        header('Location: index.php?error=2');
        exit;
    }
}
?>


<!doctype html>
<html lang="es">

<head>
    <title>Iniciar Sesión</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href=".././css/admin/login.css">
</head>


<body>
    <?php include 'templates/mensajes.php'; ?>


    <section class="container mt-5">
        <div class="login-container">
            <h2>Iniciar Sesión</h2>
            <form action="index.php" method="post" class="login-form" autocomplete="off">
                <div class="form-group">
                    <input type="text" class="form-control" name="usuario" placeholder="Usuario" Required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" Required>
                </div>
                <button type="submit" class="btn-login"><i class="fa fa-sign-out-alt"></i> Entrar</button>
            </form>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

</html>