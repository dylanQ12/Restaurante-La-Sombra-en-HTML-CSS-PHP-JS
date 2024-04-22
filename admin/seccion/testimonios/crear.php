<?php
$ruta_login = "http://localhost/Restaurante/admin/";
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location:' . $ruta_login);
    exit;
} 
?>


<?php include __DIR__ . '/../../templates/header.php'; ?>


<?php
include '../../bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opinion = $_POST["opinion"];
    $nombre = $_POST["nombre"];

    $sentencia = $conexion->prepare(" INSERT INTO testimonios(opinion, nombre) 
                                      VALUES('$opinion', '$nombre') ");
    $sentencia->execute();
    header('Location: index.php?resultado=1');
    exit;
}
?>


<br>
<div class="card">
    <div class="card-header text-center">Testimonios</div>
    <div class="card-body">
        <form class="formulario" action="crear.php" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="opinion" class="form-label">Opinión:</label>
                <input type="text" class="form-control" name="opinion" placeholder="Ingrese Opinión" required />
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" placeholder="Ingrese Nombre" required />
            </div>

            <button class="boton-guardar"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>

        </form>
    </div>
</div>



<?php include __DIR__ . '/../../templates/footer.php'; ?>