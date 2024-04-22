<?php
$ruta_login = "http://localhost/Restaurante/admin/";
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location:' . $ruta_login);
    exit;
} 
?>


<?php include '../../templates/header.php' ?>


<?php
include '../../bd.php';

// Obtener mensajes.
$estado_mensaje = $_GET['resultado'] ?? null;

// Paginación
$porPagina = 5; // Cantidad de registros por página
$pagina = $_GET['pagina'] ?? 1; // Página actual, por defecto es 1
$inicio = ($pagina - 1) * $porPagina; // Cálculo para el inicio del LIMIT

// Contar el total de registros
$sentencia = $conexion->prepare("SELECT COUNT(*) FROM comentarios");
$sentencia->execute();
$totalRegistros = $sentencia->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina); // Calcular el total de páginas

// Listar en la base de datos con paginación
$sentencia = $conexion->prepare("SELECT * FROM comentarios ORDER BY id ASC LIMIT :inicio, :porPagina");
$sentencia->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$sentencia->bindParam(':porPagina', $porPagina, PDO::PARAM_INT);
$sentencia->execute();
$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Eliminar registros por id.
if ($_GET['id'] ?? null) {
    $id = $_GET['id'];

    $sentencia = $conexion->prepare("DELETE FROM comentarios WHERE id = :id");
    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
    $sentencia->execute();

    // Redirigir al usuario a index.php
    header('Location: index.php?resultado=3');
    exit;
}
?>


<br />
<h2 class="text-center">Bandeja de comentarios</h2><br/>

<?php include '../../templates/mensajes.php'; ?>


<div class="table-responsive">
    <table id="tbl_comentarios" class="table table-responsive tabla">
        <thead class="thead-ligth">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Correo</th>
                <th scope="col">Mensaje</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                $contador = ($pagina - 1) * $porPagina;
                foreach ($resultado as $key => $value) { ?>
                    <td> <?php echo ++$contador; ?> </td>
                    <td> <?php echo $value['nombre']; ?> </td>
                    <td> <?php echo $value['correo']; ?> </td>
                    <td> <?php echo $value['mensaje']; ?> </td>
                    <td>
                        <a class="btn btn-danger btn-sm" href="index.php?id= <?php echo $value['id']; ?>"><i class="fa-solid fa-trash"></i> Eliminar</a>
                    </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>


<nav class="paginador">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
            <li class="page-item <?php if ($i == $pagina) echo 'active'; ?>">
                <a class="page-link" href="index.php?pagina=<?php echo $i; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php } ?>
    </ul>
</nav>


<?php include __DIR__ . '/../../templates/footer.php'; ?>