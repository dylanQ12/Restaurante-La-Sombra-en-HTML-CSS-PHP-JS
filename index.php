<?php
include 'admin/bd.php';

$enviar = $_GET['mensaje'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    $mensaje = filter_var($_POST['mensaje'], FILTER_SANITIZE_STRING);

    if ($nombre && $correo && $mensaje) {
        $sql = " INSERT INTO comentarios(nombre, correo, mensaje)
                 VALUES(:nombre, :correo, :mensaje) ";
        $sentencias = $conexion->prepare($sql);
        $sentencias->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $sentencias->bindParam(':correo', $correo, PDO::PARAM_STR);
        $sentencias->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
        $sentencias->execute();
    }

    header("Location: index.php?mensaje=1");
    exit;
}
?>


<!doctype html>
<html lang="en">

<head>
    <title>Restaurante | Pearl Gourmet</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/normalize.css">

</head>

<body>

    <?php include 'admin/templates/mensajes.php'; ?>

    <!-- Navbar (menú de navegación) -->
    <nav id="inicio" class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
              Restaurante Pearl Gourmet
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#menu">Menú del Día</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#chefs">Chefs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonios">Testimonios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#horarios">Horarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/index.php">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Banner con Carrusel -->
    <section class="container-fluid p-0">
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">

            <!-- Indicadores del Carrusel dinámicos -->
            <div class="carousel-indicators">
                <?php
                // Consulta a la base de datos.
                $conexion = new mysqli("localhost", "root", "", "restaurante");
                $resultado = $conexion->query("SELECT * FROM banners ORDER BY id ASC");
                $contador = 0;
                foreach ($resultado as $banner) {
                    echo '<button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="' . $contador . '"' . ($contador === 0 ? ' class="active" aria-current="true"' : '') . ' aria-label="Slide ' . ($contador + 1) . '"></button>';
                    $contador++;
                }
                ?>
            </div>

            <!-- Slides del Carrusel dinámicos -->
            <div class="carousel-inner">
                <?php
                $esActivo = true;
                // Vuelve a hacer la consulta ya que necesitamos reiniciar el puntero de los resultados
                $resultado = $conexion->query("SELECT * FROM banners ORDER BY id ASC");
                foreach ($resultado as $banner) {
                    echo '<div class="carousel-item' . ($esActivo ? ' active' : '') . '">';
                    echo '<img src="img/' . $banner['imagen'] . '" class="d-block w-100 ">';
                    echo '<div class="carousel-caption d-none d-md-block">';
                    echo '<h1>'. "¡" . $banner['titulo']. "!" . '</h1>';
                    echo '<p>' . $banner['descripcion'] . '</p>';
                    // Asegúrate de que 'enlace' es el nombre correcto de la columna en tu base de datos
                    echo '<a href="' . $banner['link'] . '" class="btn btn-primary btn-lg">Ver Menú</a>';
                    echo '</div>';
                    echo '</div>';
                    $esActivo = false; // Solo el primer slide debe ser 'active'
                }
                ?>
            </div>

            <!-- Controles del Carrusel -->
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    </section>


    <!-- Chefs -->
    <section id="chefs" class="container mt-4 text-center">
        <h2>Nuestros Chefs</h2><br/>
        <div class="row">
            <?php
            // Conexión a la base de datos
            $conexion = new mysqli("localhost", "root", "", "restaurante");

            // Comprueba la conexión
            if ($conexion->connect_error) {
                die("Conexión fallida: " . $conexion->connect_error);
            }

            // Consulta para obtener la información de los chefs
            $consulta = $conexion->query("SELECT * FROM colaboradores ORDER BY id ASC");

            // Comprueba si la consulta tiene resultados
            if ($consulta->num_rows > 0) {
                // Salida de datos de cada fila
                while ($chef = $consulta->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card chef-card">';
                    echo '<img src="img/colaboradores/' . $chef['foto'] . '" class="card-img-top">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $chef['nombre'] . '</h5>';
                    echo '<p class="card-text">' . $chef['descripcion'] . '</p>';
                    echo '<div class="social-icons mt-3">';
                    if ($chef['link_facebook']) echo '<a href="' . $chef['link_facebook'] . '" class="text-dark me-2"><i class="fab fa-facebook"></i></a>';
                    if ($chef['link_twitter']) echo '<a href="' . $chef['link_twitter'] . '" class="text-dark me-2"><i class="fab fa-twitter"></i></a>';
                    if ($chef['link_instagram']) echo '<a href="' . $chef['link_instagram'] . '" class="text-dark me-2"><i class="fab fa-instagram"></i></a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<br>';
                }
            } else {
                echo "0 results";
            }

            // Cerrar la conexión
            $conexion->close();
            ?>
        </div>
    </section>


    <!-- Testimonios -->
    <section id="testimonios" class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">Testimonios</h2>
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <!-- Indicadores del Carrusel -->
                <div class="carousel-indicators">
                    <?php
                    // Conexión a la base de datos
                    $conexion = new mysqli("localhost", "root", "", "restaurante");
                    $consulta = $conexion->query("SELECT id FROM testimonios ORDER BY id ASC");
                    $contador = 0;
                    while ($fila = $consulta->fetch_assoc()) {
                        echo '<button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="' . $contador . '"' . ($contador === 0 ? ' class="active" aria-current="true"' : '') . ' aria-label="Testimonio ' . ($contador + 1) . '"></button>';
                        $contador++;
                    }
                    ?>
                </div>

                <!-- Slides del Carrusel de Testimonios -->
                <div class="carousel-inner">
                    <?php
                    $consulta = $conexion->query("SELECT * FROM testimonios ORDER BY id ASC");
                    $esActivo = true;
                    while ($testimonio = $consulta->fetch_assoc()) {
                        echo '<div class="carousel-item' . ($esActivo ? ' active' : '') . '">';
                        echo '<div class="testimonial-card mx-auto" style="max-width: 600px;">';
                        echo '<p class="card-text">"' . "¡" . $testimonio['opinion'] . "!" . '"</p>';
                        echo '<footer class="blockquote-footer text-end">' . $testimonio['nombre'] . '</footer>';
                        echo '</div>';
                        echo '</div>';
                        $esActivo = false; // Solo el primer slide debe ser 'active'
                    }
                    // Cerrar la conexión
                    $conexion->close();
                    ?>
                </div>
            </div>
        </div>
    </section>


    <!-- Menú de comidas -->
    <section id="menu" class="container mt-4">
        <h2 class="text-center">Menú (recomendado)</h2><br/>
        <div class="row row-cols-1 row-cols-md-4 g-4">
            <?php
            // Conexión a la base de datos
            $conexion = new mysqli("localhost", "root", "", "restaurante");

            // Comprueba la conexión
            if ($conexion->connect_error) {
                die("Conexión fallida: " . $conexion->connect_error);
            }

            // Consulta para obtener la información del menú
            $consulta = $conexion->query("SELECT * FROM menu ORDER BY id ASC");

            // Comprueba si la consulta tiene resultados
            if ($consulta->num_rows > 0) {
                // Salida de datos de cada fila
                while ($plato = $consulta->fetch_assoc()) {
                    echo '<div class="col d-flex">';
                    echo '<div class="card menu-card">';
                    echo '<img src="img/menu/' . $plato['foto'] . '" class="card-img-top">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $plato['nombre'] . '</h5>';
                    echo '<p class="card-text small"><strong>Ingredientes:</strong> ' . $plato['ingredientes'] . '</p>';
                    echo '<p class="card-text"><strong>Precio:</strong> $' . $plato['precio'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "No hay platos en el menú.";
            }

            // Cerrar la conexión
            $conexion->close();
            ?>
        </div>
    </section>


    <!-- Sección de Contacto -->
    <br><section id="contacto" class="container mt-4">
        <h2 class="text-center">Contacto</h2>
        <p class="text-center">¡Estamos aquí para servirle!</p>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <form action="index.php" method="post" autocomplete="off">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre" Required>
                    </div>

                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingrese correo electrónico" Required>
                    </div>

                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje:</label>
                        <textarea id="mensaje" name="mensaje" class="form-control" cols="30" rows="10" placeholder="Ingrese el mensaje" Required></textarea>
                    </div>

                    <div class="text-center">
                        <input class="btn btn-primary" type="submit" value="Enviar">
                    </div>
                </form>
            </div>
        </div>
    </section>
    <br>



    <!-- Sección de Horarios -->
    <section id="horarios" class="bg-light">
        <div class="container text-center py-5">
            <h3 class="mb-4">Horarios de Atención</h3>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5><strong>Lunes a Viernes</strong></h5>
                    <p>De 11:00 a.m. a 10:00 p.m.</p>
                    <i class="fas fa-clock fa-spin"></i>
                </div>

                <div class="col-md-4 mb-3">
                    <h5><strong>Sábado</strong></h5>
                    <p>De 12:00 a.m. a 5:00 p.m.</p>
                    <i class="fas fa-clock fa-spin"></i>
                </div>

                <div class="col-md-4 mb-3">
                    <h5><strong>Domingo</strong></h5>
                    <p>De 7:00 a.m. a 2:00 p.m.</p>
                    <i class="fas fa-clock fa-spin"></i>
                </div>
            </div>
        </div>
    </section>



    <!-- Footer (pie de página) -->
    <footer id="site-footer" class="bg-dark text-light text-center py-4">
        <div class="container">
            <p>&copy; 2024 Restaurante Pearl Gourmet. Todos los derechos reservados.</p>
            <div class="social-icons">
                <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>




    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>