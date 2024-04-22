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
    $foto = $_FILES["foto"]["name"]; 
    $nombre =  $_POST["nombre"];
    $descripcion = $_POST["desc"];
    $link_facebook = $_POST["link_facebook"];
    $link_twitter = $_POST["link_twitter"];
    $link_instagram = $_POST["link_instagram"];

    $fecha_foto = new DateTime();
    $nombre_foto = $fecha_foto->getTimestamp(). "_" . $foto;
    $tmp_foto = $_FILES['foto']['tmp_name'];

    if($tmp_foto != "") {
        move_uploaded_file($tmp_foto,"../../../img/colaboradores/". $nombre_foto);
    }

    $sentencia = $conexion->prepare("INSERT INTO colaboradores(foto, nombre, descripcion, link_facebook, link_twitter, link_instagram) 
                                    VALUES('$nombre_foto', '$nombre', '$descripcion', '$link_facebook', '$link_twitter', '$link_instagram')");
    $sentencia->execute();
    header('Location: index.php?resultado=1');
    exit;
} 
?>
 

<br>
<div class="card">
    <div class="card-header text-center">Colaboradores</div>
    <div class="card-body">
        <form class="formulario" action="crear.php" method="post" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label for="foto" class="form-label">Foto:</label>
                <input
                    type="file"
                    class="form-control"
                    name="foto"
                    placeholder="Ingrese Foto"
                    required
                />
            </div>

            <div class="mb-3">
            <label for="descripcion" class="form-label">Nombre:</label>
                <input
                    type="text"
                    class="form-control"
                    name="nombre"
                    placeholder="Ingrese Nombre"
                    required
                />
            </div>

            <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
                <input
                    type="text"
                    class="form-control"
                    name="desc"
                    placeholder="Ingrese Descripción"
                    required
                />
            </div>

            <div class="mb-3">
            <label for="link_facebook" class="form-label">Link de Facebook:</label>
                <input
                    type="text"
                    class="form-control"
                    name="link_facebook"
                    placeholder="Ingrese link de Facebook"
                    required
                />
            </div>

            <div class="mb-3">
            <label for="link_twitter" class="form-label">Link de Twitter:</label>
                <input
                    type="text"
                    class="form-control"
                    name="link_twitter"
                    placeholder="Ingrese link de Twitter"
                    required
                />
            </div>

            <div class="mb-3">
            <label for="link_instagram" class="form-label">Link de Instagram:</label>
                <input
                    type="text"
                    class="form-control"
                    name="link_instagram"
                    placeholder="Ingrese link de Instagram"
                    required
                />
            </div>

            <button class="boton-guardar"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
            
        </form>
    </div>
</div>


<?php include __DIR__ . '/../../templates/footer.php'; ?>