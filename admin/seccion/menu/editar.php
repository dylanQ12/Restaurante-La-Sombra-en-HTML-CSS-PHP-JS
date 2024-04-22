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
$sentencia = $conexion->prepare("SELECT * FROM menu WHERE id = '$id' ");
$sentencia->execute();
$resultado = $sentencia->fetch(PDO::FETCH_LAZY);

// encontrar datos.
$foto = $resultado["foto"];
$nombre =  $resultado["nombre"];
$ingredientes = $resultado["ingredientes"];
$precio = $resultado["precio"];


// Actualizar datos.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $ingredientes = $_POST["ingredientes"];
    $precio = $_POST["precio"];

    // Verificar si se subió un archivo de foto.
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == UPLOAD_ERR_OK) {
        // Obtener la información de la foto antigua para poder eliminarla después.
        $consultaFoto = $conexion->prepare("SELECT foto FROM menu WHERE id = :id");
        $consultaFoto->bindParam(':id', $id, PDO::PARAM_INT);
        $consultaFoto->execute();
        $fotoAntigua = $consultaFoto->fetchColumn();

        // Si existe una foto antigua, intentar eliminarla.
        $rutaFotoAntigua = "../../../img/menu/" . $fotoAntigua;
        if ($fotoAntigua && file_exists($rutaFotoAntigua)) {
            if (!unlink($rutaFotoAntigua)) {
                // Error al eliminar el archivo
                error_log("Error al eliminar la imagen antigua: " . $rutaFotoAntigua);
                // Considera si quieres detener el proceso aquí o no.
            }
        }

        // Procesar la nueva foto.
        $fecha_foto = new DateTime();
        $nombre_foto = $fecha_foto->getTimestamp() . "_" . $_FILES["foto"]["name"];
        $tmp_foto = $_FILES['foto']['tmp_name'];
        move_uploaded_file($tmp_foto, "../../../img/menu/" . $nombre_foto);
    } else {
        $nombre_foto = null;
    }

    // Preparar la consulta SQL para la actualización.
    $sql = "UPDATE menu 
            SET nombre = :nombre, 
                ingredientes = :ingredientes, 
                precio = :precio" .
        ($nombre_foto ? ", foto = :foto" : "") .
        " WHERE id = :id";

    // Preparar y ejecutar la consulta SQL.
    $sentencia = $conexion->prepare($sql);
    $sentencia->bindParam(':nombre', $nombre);
    $sentencia->bindParam(':ingredientes', $ingredientes);
    $sentencia->bindParam(':precio', $precio);
    $sentencia->bindParam(':id', $id);

    if ($nombre_foto) {
        $sentencia->bindParam(':foto', $nombre_foto);
    }

    $sentencia->execute();

    header('Location: index.php?resultado=2');
    exit;
}

?>


<br>
<div class="card">
    <div class="card-header text-center">Menú de Comidas</div>
    <div class="card-body">
        <form class="formulario" action="editar.php" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" required />
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label> <br />
                <img src="../../../img/menu/<?php echo $foto; ?>" width="80">
                <input type="file" class="form-control" name="foto" placeholder="Ingrese Foto"  />
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" placeholder="Ingrese Nombre" value="<?php echo $nombre; ?>" required />
            </div>

            <div class="mb-3">
                <label for="ingredientes" class="form-label">Ingredientes:</label>
                <input type="text" class="form-control" name="ingredientes" placeholder="Ingrese Ingredientes" value="<?php echo $ingredientes; ?>" required />
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label>
                <input type="text" class="form-control" name="precio" placeholder="Ingrese Precio" value="<?php echo $precio; ?>" required />
            </div>

            <button class="boton-guardar"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>

        </form>
    </div>
</div>


<?php include __DIR__ . '/../../templates/footer.php'; ?>