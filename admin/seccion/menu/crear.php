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
    $ingredientes = $_POST["ingredientes"];
    $precio = $_POST["precio"];

    $fecha_foto = new DateTime();
    $nombre_foto = $fecha_foto->getTimestamp(). "_" . $foto;
    $tmp_foto = $_FILES['foto']['tmp_name'];

    if($tmp_foto != "") {
        move_uploaded_file($tmp_foto,"../../../img/menu/". $nombre_foto);
    }

    $sentencia = $conexion->prepare("INSERT INTO menu(foto, nombre, ingredientes, precio) 
                                    VALUES('$nombre_foto', '$nombre', '$ingredientes', '$precio')");
    $sentencia->execute();
    header('Location: index.php?resultado=1');
    exit;
} 
?>
 

<br>
<div class="card">
    <div class="card-header text-center">Menú de Comidas</div>
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
            <label for="ingredientes" class="form-label">Ingredientes:</label>
                <input
                    type="text"
                    class="form-control"
                    name="ingredientes"
                    placeholder="Ingrese Ingredientes"
                    required
                />
            </div>

            <div class="mb-3">
            <label for="precio" class="form-label">Precio:</label>
                <input
                    type="text"
                    class="form-control"
                    name="precio"
                    placeholder="Ingrese Precio"
                    required
                />
            </div>

            <button class="boton-guardar"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
            
        </form>
    </div>
</div>


<?php include __DIR__ . '/../../templates/footer.php'; ?>