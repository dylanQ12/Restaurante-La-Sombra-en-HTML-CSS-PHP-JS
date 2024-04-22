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

$id = $_GET['id'] ?? null;

// consulta en la base de datos
$sentencia = $conexion->prepare("SELECT * FROM banners WHERE id = '$id' ");
$sentencia->execute();
$resultado = $sentencia->fetch(PDO::FETCH_LAZY);

// encontrar datos.
$id = $resultado['id'];
$imagen = $resultado['imagen'];
$titulo = $resultado['titulo'];
$descripcion = $resultado['descripcion'];
$enlace = $resultado['link'];


//Actualizar datos.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST["id"];
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["desc"];
    $link = $_POST["link"];

    // Si se ha subido una nueva imagen y no hay errores en la subida.
    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["error"] == UPLOAD_ERR_OK) {
        $consultaImagen = $conexion->prepare("SELECT imagen FROM banners WHERE id = :id");
        $consultaImagen->bindParam(':id', $id, PDO::PARAM_INT);
        $consultaImagen->execute();
        $imagenAntigua = $consultaImagen->fetchColumn();

        // Generar un nuevo nombre de archivo único para la nueva imagen.
        $fecha = new DateTime();
        $nombreImagen = $fecha->getTimestamp() . "_" . $_FILES["imagen"]["name"];
        $rutaImagen = "../../../img/" . $nombreImagen;

        // Mover la nueva imagen al directorio de imágenes.
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            // Si se ha movido la nueva imagen con éxito, eliminar la anterior.
            if ($imagenAntigua && file_exists("../../../img/" . $imagenAntigua)) {
                unlink("../../../img/" . $imagenAntigua);
            }

            // Actualizar la ruta de la imagen en la base de datos.
            $sql = "UPDATE banners SET imagen = :imagen, titulo = :titulo, descripcion = :descripcion, link = :link WHERE id = :id";
            $sentencia = $conexion->prepare($sql);
            $sentencia->bindParam(':imagen', $nombreImagen, PDO::PARAM_STR);
        } else {
            // Manejar el error de mover el archivo, posiblemente estableciendo una variable de error y mostrándola al usuario.
            echo '<div class="alert alert-danger">Error al subir el archivo.</div>';
        }
    } else {
        // Si no se ha subido una nueva imagen, solo actualizar los otros datos.
        $sql = "UPDATE banners SET titulo = :titulo, descripcion = :descripcion, link = :link WHERE id = :id";
        $sentencia = $conexion->prepare($sql);
    }

    // Enlazar parámetros comunes y ejecutar la actualización.
    $sentencia->bindParam(':titulo', $titulo, PDO::PARAM_STR);
    $sentencia->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $sentencia->bindParam(':link', $link, PDO::PARAM_STR);
    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
    $sentencia->execute();

    header('Location: index.php?resultado=2');
    exit;
}
?>


<br>
<div class="card">
    <div class="card-header text-center">Banners</div>
    <div class="card-body">
        <form class="formulario" action="editar.php" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?> required"/>
            </div>

            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label> <br />
                <img src="../../../img/<?php echo $imagen; ?>" width="80">
                <input type="file" class="form-control" name="imagen" placeholder="Ingrese Imagen" />
            </div>



            <div class="mb-3">
                <label for="titulo" class="form-label">Titulo:</label>
                <input type="text" class="form-control" name="titulo" placeholder="Ingrese Título" value="<?php echo $titulo; ?>" required />
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" class="form-control" name="desc" placeholder="Ingrese Descripción" value="<?php echo $descripcion; ?>" required />
            </div>

            <div class="mb-3">
                <label for="link" class="form-label">Enlace:</label>
                <input type="text" class="form-control" name="link" placeholder="Ingrese Enlace" value="<?php echo $enlace; ?>" required />
            </div>

            <button class="boton-guardar"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>

        </form>
    </div>
</div>


<?php include __DIR__ . '/../../templates/footer.php'; ?>