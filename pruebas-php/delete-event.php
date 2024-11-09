<!--  Página para eliminar un evento del usuario conectado -->
<?php
session_start();
require_once __DIR__ . "/data-access/CalendarDataAccess.php";

if ($_SESSION['iniciada']) {
} else {
    header("Location: index.php");
    die();
}
$dbFile = __DIR__ . '/CalendarDataAccess.php.db';
$CalendarDataAccess = new CalendarDataAccess($dbFile);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $idGet = $_GET['id'];
    $dbFile = __DIR__ . '/CalendarDataAccess.php.db';
    $CalendarDataAccess = new CalendarDataAccess($dbFile);
    $errores = [];
    $id = $_SESSION['usuario'];
    $event;

    echo "<h2>Evento $idGet de usuario</h2><ul>";
    $event = $CalendarDataAccess->getEventById($idGet);
    if ($event == null) {
        array_push($errores, "No se puede acceder al evento, porque no existe o porque no tienes permisos para verlo.");
        $mostrarModal = true;
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['eliminar'])) {
        $borrar = $_POST['eliminar'];
        $idGet = $_GET['id'];
        // echo "<h2>Eliminar evento</h2>";
        $eventDeleted = $CalendarDataAccess->deleteEvent($idGet);
        // echo $eventDeleted ? "Evento eliminado con éxito." : "Error al eliminar evento.";
        if ($eventDeleted) {
            header("Location: events.php");
            die();
        }
    }
    if (isset($_POST['noEliminar'])) {
        $noBorrar = $_POST['noEliminar'];
        if ($noBorrar != null) {
            header("Location: events.php");
            die();
        }
    }
}
$user = $CalendarDataAccess->getUserById($_SESSION['usuario']);
$nombre = $user->getFirstName();
$apellido = $user->getLastName();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href=" https://getbootstrap.esdocu.com/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="delete.css">

</head>

<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <a href="/" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
                <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                    <use xlink:href="#bootstrap"></use>
                </svg>
            </a>

            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#" class="nav-link px-2 link-secondary"><?php echo $nombre ?></a></li>
                <li><a href="#" class="nav-link px-2 link-dark"><?php echo $apellido ?></a></li>

            </ul>

            <div class="col-md-3 text-end">
                <button type="button" class="btn btn-outline-primary me-2"><a href="logout.php"> Desconectar</a></button>
                <button type="button" class="btn btn-outline-primary me-2"><a href="change.password.php"> Cambiar contra</a></button>
                <button type="button" class="btn btn-outline-primary me-2">Editar perfil</button>

            </div>
        </header>
    </div>

    <form method="post">

        <h1>¿Seguro que deseas eliminar el evento?</h1><!-- poner el titulo php!-->
        <button type="submit" class="btn btn-danger" name="eliminar" value="eliminar">Si, eliminar evento</button>
        <button type="submit" class="btn btn-success" name="noEliminar" value="noEliminar">No, volver a listado de eventos</button>


    </form>

    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <?php foreach ($errores as $errors): ?>
                            <li><?= $errors ?></li>
                        <?php endforeach; ?>
                    </ul>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><a href="events.php"> Volver a listado de eventos</a></button>

                </div>

            </div>
        </div>
    </div>
</body>
<script>
    <?php if ($mostrarModal): ?>
        let errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    <?php endif; ?>
</script>

</html>