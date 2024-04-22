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

$sentencia = $conexion->prepare("SELECT * FROM testimonios WHERE id = :id");
$sentencia->bindParam(':id', $id);
$sentencia->execute();
$resultado = $sentencia->fetch(PDO::FETCH_LAZY);

// mostrar datos
$opinion = $resultado['opinion'];
$nombre = $resultado['nombre'];

// Actualizar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST["id"];
    $opinion = $_POST["opinion"];
    $nombre = $_POST["nombre"];

    // Utiliza marcadores de posición y bindParam para evitar problemas de seguridad y de sintaxis SQL
    $sentencia = $conexion->prepare(" UPDATE testimonios 
                                      SET opinion = :opinion, 
                                          nombre = :nombre 
                                      WHERE id = :id");
    $sentencia->bindParam(':id', $id);
    $sentencia->bindParam(':opinion', $opinion);
    $sentencia->bindParam(':nombre', $nombre);
    $sentencia->execute();

    header('Location: index.php?resultado=2');
    exit;
}
?>


<br>
<div class="card">
    <div class="card-header text-center">Testimonios</div>
    <div class="card-body">
        <form class="formulario" action="editar.php" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" required />
            </div>

            <div class="mb-3">
                <label for="opinion" class="form-label">Opinión:</label>
                <input type="text" class="form-control" name="opinion" placeholder="Ingrese Opinión" value="<?php echo $opinion; ?>" required />
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" placeholder="Ingrese Nombre" value="<?php echo $nombre; ?>" required />
            </div>

            <button class="boton-guardar"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>

        </form>
    </div>
</div>



<?php include __DIR__ . '/../../templates/footer.php'; ?>