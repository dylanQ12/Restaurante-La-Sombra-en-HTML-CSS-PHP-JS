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
$sentencia = $conexion->prepare("SELECT COUNT(*) FROM banners");
$sentencia->execute();
$totalRegistros = $sentencia->fetchColumn();
$totalPaginas = ceil($totalRegistros / $porPagina); // Calcular el total de páginas

// Listar en la base de datos con paginación
$sentencia = $conexion->prepare("SELECT * FROM banners ORDER BY id ASC LIMIT :inicio, :porPagina");
$sentencia->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$sentencia->bindParam(':porPagina', $porPagina, PDO::PARAM_INT);
$sentencia->execute();
$resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Eliminar registros por id.
if (isset($_GET['id'] )) {
    $id = $_GET['id'];

    // Preparar y ejecutar la consulta
    $sentencia = $conexion->prepare("SELECT imagen FROM banners WHERE id = :id");
    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
    $sentencia->execute();

    // Obtener el resultado
    $resultado = $sentencia->fetch(PDO::FETCH_LAZY);

    // Verificar si el archivo existe y eliminarlo
    if ($resultado && file_exists("../../../img/" . $resultado['imagen'])) {
        if (!unlink("../../../img/" . $resultado['imagen'])) {
            // Manejo de error al eliminar el archivo
            echo "Error al eliminar la imagen.";
            exit;
        }
    }

    // Eliminar el registro de la base de datos
    $sentencia = $conexion->prepare("DELETE FROM banners WHERE id = :id");
    $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
    if (!$sentencia->execute()) {
        // Manejo de error al eliminar el registro
        echo "Error al eliminar el registro de la base de datos.";
        exit;
    }

   
    // Redirigir al usuario a index.php
    header('Location: index.php?resultado=3');
    exit;
}
?>


<br>
<a class="btn btn-success boton" href="crear.php"><i class="fa-regular fa-plus"></i> Nuevo</a>
<br>

<h2 class="text-center">Banners</h2><br/>

<?php include '../../templates/mensajes.php'; ?>

<div class="table-responsive">

    <table id="tbl_banners" class="table table-responsive tabla">
        <thead class="thead-ligth">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Imagen</th>
                <th scope="col">Titulo</th>
                <th scope="col">Descripción</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                $contador = ($pagina - 1) * $porPagina;
                foreach ($resultado as $key => $value) { ?>
                    <td> <?php echo ++$contador; ?> </td>
                    <td> <img src="../../../img/<?php echo $value['imagen']; ?>" width="80"> </td>
                    <td> <?php echo $value['titulo']; ?> </td>
                    <td> <?php echo $value['descripcion']; ?> </td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="editar.php?id= <?php echo $value['id']; ?>"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                        <a class="btn btn-danger btn-sm" href="index.php?id= <?php echo $value['id']; ?>"><i class="fa-solid fa-trash"></i> Eliminar</a>
                    </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<nav class="paginador">
    <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
            <li class="page-item <?php if ($i == $pagina) echo 'active'; ?>">
                <a class="page-link" href="index.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>



<?php include __DIR__ . '/../../templates/footer.php'; ?>