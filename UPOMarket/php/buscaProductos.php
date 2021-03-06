<?php
session_start();
include './utils/utilsProductos.php';
$categorias = listarCategorias();
/*Realización de la busqueda de un producto y visualización de los datos del mismo*/
if (!isset($_GET["busqueda"])) {
    header("location:principal.php");
}
$busca = trim(filter_var($_GET["busqueda"], FILTER_SANITIZE_STRING));
$categoria = trim(filter_var($_GET["categoria"], FILTER_SANITIZE_STRING));
if (empty($categoria)) {
    $productos = buscarProductos($busca);
} else {
    $productos = buscarProductosCategoria($busca, $categoria);
}
if (empty($productos)) {
    $errores[] = "No se ha encontrado ningún producto";
} else {
    $data = json_encode($productos);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Buscar - UPOMarket</title>
        <link href="../frameworks/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="../css/shop-homepage.css" rel="stylesheet">
        <link href="../css/header.css" rel="stylesheet">
        <link href="../css/footer.css" rel="stylesheet">
        <link href="../css/principal.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link href="../css/buscaProductos.css" rel="stylesheet">
        <script src="../frameworks/jquery/jquery.min.js"></script>
        <script src="../frameworks/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script><!-- Para que se vean los logos -->
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function () {
                var data = <?php
if (isset($data))
    echo $data;
else
    echo "null"
    ?>;
                if (data == null) {
                    $("#contenedorTablaProductos").hide();
                }
                $('#productos').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                    },
                    "data": data,
                    "paging": true,
                    "ordering": true,
                    "searching": false,
                    "order": [[1, "asc"]],
                    columnDefs: [{
                            targets: 2,
                            render: function (data, type, row) {
                                return data.length > 50 ?
                                        data.substr(0, 50) + '…' :
                                        data;
                            }
                        }],
                    "columns": [
                        {"data": "id"},
                        {"data": "nombre"},
                        {"data": "descripcion"},
                        {"data": "imagen"},
                        {"data": "precio"}
                    ],
                    "drawCallback": function () {
                        var table = $('#productos').DataTable();
                        $('#productos tbody').on('click', 'tr', function () {
                            var id = table.row(this).data().id;
                            var input = $("<input type='text' name='idProducto'/>");
                            $(input).val(id);
                            $("#formProducto").append(input);
                            $("#formProducto").submit();
                        });
                        var imgs = table.column(3).data();
                        var rows = $("tbody tr");
                        //Mostramos la imagen miniatura del producto
                        for (var i = 0; i < imgs.length; i++) {
                            var aux = $(rows[i]).children()[3];
                            path = data[i]['imagen'];
                            var imagen = document.createElement("img");
                            $(imagen).attr("src", path);
                            $(imagen).attr("alt", "No disponible");
                            $(imagen).attr("onerror", "reemplazarImg(this)");
                            $(imagen).addClass("mostrarImagen");
                            aux.replaceChild(imagen, aux.firstChild);
                        }

                        var cells = $("tbody tr :nth-child(2)");
                        for (var i = 0; i < cells.length; i++) {
                            var text = $(cells[i]).text();
                            var busca = "<?php echo $busca ?>";
                            var re = new RegExp(busca, 'gi');
                            cells[i].innerHTML = cells[i].innerHTML.replace(re, "<span class='highlight'>" + busca + "</span>");
                        }
                        cells = $("tbody tr :nth-child(3)");
                        for (var i = 0; i < cells.length; i++) {
                            var text = $(cells[i]).text();
                            var busca = "<?php echo $busca ?>";
                            var re = new RegExp(busca, 'gi');
                            cells[i].innerHTML = cells[i].innerHTML.replace(re, "<span class='highlight'>" + busca + "</span>");
                        }
                    }
                });
            }
            );
            function reemplazarImg(img) {
                $(img).attr("src", "../img/productDefaultImage.jpg");
            }
        </script>

    </head>

    <body>
        <form id="formProducto" action="producto.php" method="GET" hidden>
        </form>
        <?php
        include './header.php';
        ?>
        <!-- Page Content -->
        <main class="container">
            <div class="row">
                <div class="col-lg-3">
                    <img id="logo_main" class="img-fluid" src="../img/upomarket.png" alt="upomarket">
                    <nav id='categorias' class="list-group">
                        <ul class="list-unstyled">
                            <h4 class="text-center">Categorías</h4>
                            <?php
                            foreach ($categorias as $c) {
                                echo '<li><a href="./categoria.php?categoria=' . $c[0] . '" class="list-group-item">' . $c[0] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>
                <!-- /.col-lg-3 -->

                <div class="col-lg-9">
                    <!-- /.col-lg-9 -->
                    <?php
                    include './barraBusqueda.php';
                    if (empty($errores)) {
                        echo '<h5>Se han econtrado ' . count($productos) . " coincidencias para la búsqueda '" . $busca . "':</h5>";
                    } else {
                        foreach ($errores as $e) {
                            echo $e;
                        }
                    }
                    ?>
                    <div id='contenedorTablaProductos' class='table-responsive'>
                        <table id="productos" class="table table-striped table-bordered dataTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Imagen</th>
                                    <th>Precio(&euro;)</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /.row -->
            </div>

        </main>
        <!-- /.container -->
        <?php
        include '../html/footer.html';
        ?>
    </body>

</html>