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
$sentencia = $conexion->prepare("SELECT * FROM colaboradores WHERE id = '$id' ");
$sentencia->execute();
$resultado = $sentencia->fetch(PDO::FETCH_LAZY);


// encontrar datos.
$id = $resultado["id"];
$foto = $resultado["foto"];
$nombre =  $resultado["nombre"];
$descripcion = $resultado["descripcion"];
$link_facebook = $resultado["link_facebook"];
$link_twitter = $resultado["link_twitter"];
$link_instagram = $resultado["link_instagram"];


// Actualizar datos.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["desc"];
    $link_facebook = $_POST["link_facebook"];
    $link_twitter = $_POST["link_twitter"];
    $link_instagram = $_POST["link_instagram"];

    // Variables para los parámetros de la consulta
    $params = [
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':link_facebook' => $link_facebook,
        ':link_twitter' => $link_twitter,
        ':link_instagram' => $link_instagram,
        ':id' => $id
    ];

    // Verificar si se subió un archivo de foto.
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] == UPLOAD_ERR_OK) {
        // Obtener la información de la foto antigua para poder eliminarla después.
        $consultaFoto = $conexion->prepare("SELECT foto FROM colaboradores WHERE id = :id");
        $consultaFoto->execute([':id' => $id]);
        $fotoAntigua = $consultaFoto->fetchColumn();

        $fecha_foto = new DateTime();
        $nombre_foto = $fecha_foto->getTimestamp() . "_" . $_FILES["foto"]["name"];
        $tmp_foto = $_FILES['foto']['tmp_name'];
        move_uploaded_file($tmp_foto, "../../../img/colaboradores/" . $nombre_foto);

        // Eliminar la foto antigua si existe y no es la misma que la nueva.
        if ($fotoAntigua && file_exists("../../../img/colaboradores/" . $fotoAntigua) && $fotoAntigua !== $nombre_foto) {
            unlink("../../../img/colaboradores/" . $fotoAntigua);
        }

        // Agregar la foto al array de parámetros.
        $params[':foto'] = $nombre_foto;

        // Preparar consulta SQL para actualizar incluyendo la foto
        $sql = "UPDATE colaboradores SET 
                    foto = :foto, 
                    nombre = :nombre, 
                    descripcion = :descripcion, 
                    link_facebook = :link_facebook, 
                    link_twitter = :link_twitter, 
                    link_instagram = :link_instagram 
                WHERE id = :id";
    } else {
        // Preparar consulta SQL para actualizar sin cambiar la foto
        $sql = "UPDATE colaboradores SET 
                    nombre = :nombre, 
                    descripcion = :descripcion, 
                    link_facebook = :link_facebook, 
                    link_twitter = :link_twitter, 
                    link_instagram = :link_instagram 
                WHERE id = :id";
    }

    // Preparar y ejecutar la consulta SQL.
    $sentencia = $conexion->prepare($sql);
    $sentencia->execute($params);

    header('Location: index.php?resultado=2');
    exit;
}

?>


<br>
<div class="card">
    <div class="card-header text-center">Colaboradores</div>
    <div class="card-body">
        <form class="formulario" action="editar.php" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" required />
            </div>


            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label> <br />
                <img src="../../../img/colaboradores/<?php echo $foto; ?>" width="80">
                <input type="file" class="form-control" name="foto" placeholder="Ingrese Foto"/>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" placeholder="Ingrese Nombre" value="<?php echo $nombre; ?>" required />
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" class="form-control" name="desc" placeholder="Ingrese Descripción" value="<?php echo $descripcion; ?>" required />
            </div>

            <div class="mb-3">
                <label for="link_facebook" class="form-label">Link de Facebook:</label>
                <input type="text" class="form-control" name="link_facebook" placeholder="Ingrese link de Facebook" value="<?php echo $link_facebook; ?>" required />
            </div>

            <div class="mb-3">
                <label for="link_twitter" class="form-label">Link de Twitter:</label>
                <input type="text" class="form-control" name="link_twitter" placeholder="Ingrese link de Twitter" value="<?php echo $link_twitter; ?>" required />
            </div>

            <div class="mb-3">
                <label for="link_instagram" class="form-label">Link de Instagram:</label>
                <input type="text" class="form-control" name="link_instagram" placeholder="Ingrese link de Instagram" value="<?php echo $link_twitter; ?>" required />
            </div>

            <button class="boton-guardar"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>

        </form>
    </div>
</div>


<?php include __DIR__ . '/../../templates/footer.php'; ?>