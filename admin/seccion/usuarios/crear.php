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
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $usuario = $_POST["usuario"];
    $password = $_POST["password"];
    $correo = $_POST["correo"];
    $rol = $_POST["id_rol"];

    $password_hasheada = password_hash($password, PASSWORD_BCRYPT);

    $sql = " INSERT INTO usuarios(nombre, apellido, usuario, password, correo, id_rol) 
             VALUES(:nombre, :apellido, :usuario, :password, :correo, :id_rol) ";
    $sentencia = $conexion->prepare($sql);
    $sentencia->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $sentencia->bindParam(':apellido', $apellido, PDO::PARAM_STR);
    $sentencia->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $sentencia->bindParam(':password', $password_hasheada);
    $sentencia->bindParam(':correo', $correo, PDO::PARAM_STR);
    $sentencia->bindParam(':id_rol', $rol, PDO::PARAM_INT);
    $sentencia->execute();

    header('Location: index.php?resultado=1');
    exit;
}
?>


<br>
<div class="card">
    <div class="card-header text-center">Usuarios</div>
    <div class="card-body">
        <form class="formulario" action="crear.php" method="post">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" placeholder="Ingrese nombre" required />
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" class="form-control" name="apellido" placeholder="Ingrese apellido" required />
            </div>

            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre de usuario:</label>
                <input type="text" class="form-control" name="usuario" placeholder="Ingrese nombre de usuario" required />
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" placeholder="Ingrese password" required />
            </div>

            <div class="mb-3">
                <label for="correo" class="form-label">Correo:</label>
                <input type="email" class="form-control" name="correo" placeholder="Ingrese correo" required />
            </div>


            <?php
            // Listar todos los roles.
            $sentencia = $conexion->prepare("SELECT id, rol FROM roles ORDER BY id ASC");
            $sentencia->execute();
            $roles = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="mb-3">
                <label for="id_rol" class="form-label">Rol:</label>
                <select name="id_rol" class="form-select" placeholder="Escoje un Rol" required>
                    <option value="" selected disabled>--- Seleccione ---</option>
                    <?php foreach ($roles as $rol) : ?>
                        <option value="<?php
                                        echo $rol['id']; ?>">
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