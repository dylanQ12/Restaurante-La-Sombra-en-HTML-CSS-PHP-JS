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

// encontrar datos del usuario por id.
$id = $_GET['id'] ?? null;

$sql = "SELECT * FROM usuarios WHERE id = :id";

$sentencia = $conexion->prepare($sql);
$sentencia->bindParam(':id', $id, PDO::PARAM_INT);
$sentencia->execute();
$resultado = $sentencia->fetch(PDO::FETCH_LAZY);

$nombre = $resultado['nombre'];
$apellido = $resultado['apellido'];
$usuario = $resultado['usuario'];
$correo = $resultado['correo'];
$id_rol = $resultado['id_rol'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];
    $correo = $_POST["correo"];
    $id_rol = $_POST["id_rol"];

    $password_hasheada = password_hash($password, PASSWORD_BCRYPT);

    $sql = " UPDATE usuarios 
             SET nombre = :nombre,
                 apellido = :apellido,
                 usuario = :usuario,
                 password = :password,
                 correo = :correo,
                 id_rol = :id_rol
             WHERE id = :id ";
    $sentencia = $conexion->prepare($sql);
    $sentencia->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $sentencia->bindParam(':apellido', $apellido, PDO::PARAM_STR);
    $sentencia->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $sentencia->bindParam(':password', $password_hasheada);
    $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
    $sentencia->bindParam(':id_rol', $id_rol, PDO::PARAM_INT);
    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
    $sentencia->execute();

    header('Location: index.php?resultado=2');
    exit;
}
?>

<br>
<div class="card">
    <div class="card-header text-center">Usuarios</div>
    <div class="card-body">
        <form class="formulario" action="editar.php" method="post">

            <div class="mb-3">
                <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>" required />
            </div>

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" placeholder="Ingrese nombre" value="<?php echo $nombre; ?>" />
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" class="form-control" name="apellido" placeholder="Ingrese apellido" value="<?php echo $apellido; ?>" required />
            </div>

            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre de usuario:</label>
                <input type="text" class="form-control" name="usuario" placeholder="Ingrese nombre de usuario" value="<?php echo $usuario; ?>" required />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" placeholder="Ingrese password" value="<?php echo "********"; ?>" required />
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" class="form-control" name="correo" placeholder="Ingrese correo" value="<?php echo $correo; ?>" required />
            </div>

            <?php
            // Consultar el y el rol de la tabla roles.
            $sql = "SELECT id, rol FROM roles ORDER BY id ASC";
            $resultado = $conexion->prepare($sql);
            $resultado->execute();
            $roles = $resultado->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="mb-3">
            <label for="id_rol" class="form-label">Rol:</label>
                <select name="id_rol" id="rol" class="form-select" required>
                    <option value="" disabled>--- Seleccione ---</option>
                    <?php foreach ($roles as $rol) : ?>
                        <option value="<?php echo $rol['id']; ?>" <?php echo ($id_rol == $rol['id']) ? 'selected' : ''; ?>>
                            <?php echo $rol['rol']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="boton-guardar"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
        </form>
    </div>
</div>



<?php include __DIR__ . '/../../templates/footer.php'; ?>