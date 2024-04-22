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
    $imagen = $_FILES["imagen"]["name"];
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["desc"];
    $link = $_POST["link"];

    $fecha_imagen = new DateTime();
    $nombre_imagen = $fecha_imagen->getTimestamp() . "_" . $imagen;
    $tmp_imagen = $_FILES['imagen']['tmp_name'];

    if ($tmp_imagen != "") {
        move_uploaded_file($tmp_imagen, "../../../img/" . $nombre_imagen);
    }

    $sentencia = $conexion->prepare(" INSERT INTO banners(imagen, titulo, descripcion, link) 
                                      VALUES('$nombre_imagen', '$titulo', '$descripcion', '$link') ");
    $sentencia->execute();
    header('Location: index.php?resultado=1');
    exit;
}
?>


<br>
<div class="card">
    <div class="card-header text-center">Banners</div>
    <div class="card-body">
        <form class="formulario" action="crear.php" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" class="form-control" name="imagen" placeholder="Ingrese Imagen" required />
            </div>

            <div class="mb-3">
                <label for="titulo" class="form-label">Titulo:</label>
                <input type="text" class="form-control" name="titulo" placeholder="Ingrese Título" required />
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" class="form-control" name="desc" placeholder="Ingrese Descripción" required />
            </div>

            <div class="mb-3">
                <label for="link" class="form-label">Enlace:</label>
                <input type="text" class="form-control" name="link" placeholder="Ingrese Enlace" required />
            </div>

            <button class="boton-guardar"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>

        </form>
    </div>
</div>



<?php include __DIR__ . '/../../templates/footer.php'; ?>