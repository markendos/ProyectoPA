<?php

//funciones
function mostrarPerfil($nombre, $email, $tipo, $errores = null) {
    ?>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Perfil</title>
        <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="../css/shop-homepage.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/footer.css" rel="stylesheet">
        <link href="../css/principal.css" rel="stylesheet" type="text/css"/>
        <link href="../css/perfil.css" rel="stylesheet" type="text/css"/>
        <script src="../frameworks/jquery/jquery.min.js"></script>
        <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->

    </head>

    <body>
        <?php
        include './header.php';
        ?>

        <!-- Page Content -->
        <main class="container">

            <div class="row">
                <!-- LISTA DE CATEGORÍAS -->
                <div class="col-lg-3">
                    <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                    <div class="list-group">
                        <p class="list-group-item active">Opciones</p>
                        <a href="perfil.php" class="list-group-item">Ver Perfil</a>
                        <a href="cambiarImagenDePerfil.php" class="list-group-item">Cambiar Imagen</a>
                        <a href="cambiarNombreDeUsuario.php" class="list-group-item">Cambiar Nombre</a>
                        <a href="editarDireccion.php" class="list-group-item">Editar Dirección de Envío</a>
                        <?php if ($tipo == "cliente") {
                            ?>
                            <a href="convertirseEnVendedor.php" class="list-group-item">Convertirse en vendedor</a>
                        <?php }
                        ?>
                    </div>
                </div>
                <!-- /.col-lg-3 -->

                <div class="col-lg-9">
                    <div id="contenedorPerfil">
                        <div class="card mt-4">
                            <div class="card-body">
                                <h3 class="card-title">Perfil
                                </h3>
                                <?php
                                if ($errores != null) {
                                    echo "<p id='mensajeErrores'>";
                                    foreach ($errores as $error) {
                                        echo $error . "<br />";
                                    }
                                    echo "</p>";
                                }
                                ?>
                                <form class="form-signin" action="#" method="post" enctype="multipart/form-data">
                                    <?php
                                    $query = "SELECT foto FROM usuarios WHERE email='" . $_SESSION['email'] . "' AND (foto is not null)";
                                    $result = ejecutarConsulta($query);
                                    if (mysqli_num_rows($result) > 0) {
                                        $row = mysqli_fetch_array($result);
                                        $rutaImg = $row['foto'];
                                        echo '<img src="../img/usrFotos/' . $_SESSION['email'] . "/" . $rutaImg . '" alt="Imagen de perfil" id="imgPerfil"/>';
                                    } else {
                                        echo '<img src="../img/defaultProfile.png" alt="Imagen de perfil" id="imgPerfil"/>';
                                    }
                                    ?>


                                    <h6 class="labelPerfil">Nombre:</h6>
                                    <p>
                                        <?php
                                        echo $nombre;
                                        ?>
                                    </p>
                                    <h6 class="labelPerfil">Email:</h6>
                                    <p>
                                        <?php
                                        echo $email;
                                        ?>
                                    </p>
                                    <h6 class="labelPerfil">Tipo de Usuario:</h6>
                                    <p>
                                        <?php
                                        echo $tipo;
                                        ?>
                                    </p>
                                    <h6 class="labelPerfil">Contraseña actual:</h6>
                                    <input name="actualpsswd" type="password" id="inputPassword" class="form-control" placeholder="Contraseña actual" required>
                                    <h6 class="labelPerfil">Nueva contraseña:</h6>
                                    <input name="newpsswd" type="password" id="inputPassword" class="form-control" placeholder="Contraseña" required>
                                    <h6 class="labelPerfil">Confirmar Contraseña:</h6>
                                    <input name="confirmpsswd" type="password" id="inputConfirmPassword" class="form-control" placeholder="Contraseña" required>
                                    <br />
                                    <input class="btn btn-lg btn-primary btn-block text-uppercase" type="submit" type="submit" value="Confirmar" name="cambiarContrasenia"></input>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-9 -->
            </div>

        </main>
        <!-- /.container -->
        <?php
        include '../html/footer.html';
        ?>
    </body>

    </html>
    <?php
}
?>

<?php
include "./utils/sesionUtils.php";
include "./utils/manejadorBD.php";
session_start();
if (isset($_SESSION['email'])) {

    $sql = "SELECT nombre, email, tipo FROM usuarios WHERE email='" . $_SESSION['email'] . "'";
    $result = ejecutarConsulta($sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $nombre = $row['nombre'];
        $email = $row['email'];
        $tipo = $row['tipo'];
    }

    if (isset($_POST['cambiarContrasenia'])) {

        if (isset($_POST['actualpsswd'])) {
            $actualpsswd = filter_var($_POST['actualpsswd'], FILTER_SANITIZE_MAGIC_QUOTES);
        } else {
            $errores[] = "Contraseña no válida";
        }
        if (isset($_POST['newpsswd'])) {
            $newpsswd = filter_var($_POST['newpsswd'], FILTER_SANITIZE_MAGIC_QUOTES);
        } else {
            $errores[] = "Nueva contraseña no válida";
        }
        if (isset($_POST['confirmpsswd'])) {
            $confirmpsswd = filter_var($_POST['confirmpsswd'], FILTER_SANITIZE_MAGIC_QUOTES);
        } else {
            $errores[] = "Nueva contraseña no válida";
        }

        if (!isset($errores) && $newpsswd === $confirmpsswd && $newpsswd != "") {
            $sentencia = "SELECT password FROM usuarios WHERE email='" . $_SESSION['email'] . "'";
            $result = ejecutarConsulta($sentencia);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                $psswdHash = $row['password'];
                if (password_verify($actualpsswd, $psswdHash)) {
                    $newpsswdhash = password_hash($newpsswd, PASSWORD_DEFAULT);
                    $sentencia = "UPDATE usuarios SET password='" . $newpsswdhash . "' WHERE email='" . $_SESSION['email'] . "'";
                    $result = ejecutarConsulta($sentencia);
                }else{
                    $errores[] = "Contraseña no válida";
                }
            }
        }
        if (isset($errores)) {
            mostrarPerfil($nombre, $email, $tipo, $errores);
        } else {
            header('Location: ./perfil.php');
        }
    } else {
        mostrarPerfil($nombre, $email, $tipo);
    }
} else {
    header('Location: ./principal.php');
}
?>
