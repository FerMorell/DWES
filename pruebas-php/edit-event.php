<?php
session_start();
require_once __DIR__ . "/data-access/CalendarDataAccess.php";

if (!isset($_SESSION['iniciada']) || !$_SESSION['iniciada']) {
    header("Location: index.php");
    exit();
}

$dbFile = __DIR__ . '/CalendarDataAccess.php.db';
$CalendarDataAccess = new CalendarDataAccess($dbFile);
$idGet = null;
$errores = [];
$event = null;
$mostrarModal = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $idGet = $_GET['id'];
    } else {
        array_push($errores, "No se ha proporcionado un ID válido.");
        $mostrarModal = true;
    }

    if ($idGet) {
        $event = $CalendarDataAccess->getEventById($idGet);
        if ($event == null || $event->getUserId() != $_SESSION['usuario']) {
            array_push($errores, "No se puede acceder al evento, porque no existe o no tienes permisos para verlo.");
            $mostrarModal = true;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $fechaInicio = $_POST['fechaHora1'];
    $fechaFinal = $_POST['fechaHora2'];


    $timestampInicio = strtotime($fechaInicio);
    $timestampFinal = strtotime($fechaFinal);


    if ($timestampInicio >= $timestampFinal) {
        array_push($errores, "La fecha y hora de finalización debe ser posterior a la fecha y hora de inicio.");
        $mostrarModal = true;
    }

    $idGet = $_GET['id'];
    $eventToUpdate = $CalendarDataAccess->getEventById($idGet);
    $eventToUpdate->setTitle($_POST['titulo']);
    $eventToUpdate->setDescription($_POST['desc']);

    $eventToUpdate->setStartDate($_POST['fechaHora1']);
    $eventToUpdate->setEndDate($_POST['fechaHora2']);
    $eventUpdated = $CalendarDataAccess->updateEvent($eventToUpdate);
    if ($eventUpdated) {
        header("Location: events.php");
        exit();
    } else {
        array_push($errores, "Ha ocurrido un error");
        $mostrarModal = true;
    }
}

$user = $CalendarDataAccess->getUserById($_SESSION['usuario']);
$nombre = $user->getFirstName();
$apellido = $user->getLastName();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="editEvent.css">
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
                <p></p>
                <button type="button" class="btn btn-outline-primary me-2"><a href="change.password.php"> Cambiar contra</a></button>
                <p></p>
                <button type="button" class="btn btn-outline-primary me-2"> <a href="#"> Editar perfil </a></button>

            </div>
        </header>
    </div>

    <form method="post" action="">
        <div class="seccion">
            <label>Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?= $event ? $event->getTitle() : '' ?>" required>
            <p></p>
            <label for="desc">Descripción: </label>
            <textarea name="desc" id="desc"><?= $event ? $event->getDescription() : '' ?></textarea>
            <p></p>
            <label>Fecha y hora inicio: <input type="datetime-local" min="<?= date("Y-m-d\TH:i"); ?>" required name="fechaHora1" value="<?= $event ? $event->getStartDate() : '' ?>"></label>
            <p></p>
            <label>Fecha y hora fin: <input type="datetime-local" min="<?= date("Y-m-d\TH:i"); ?>" required name="fechaHora2" value="<?= $event ? $event->getEndDate() : '' ?>"></label>
        </div>
        <div class="d-grid gap-2">
            <button class="btn btn-primary" type="submit">Registrar</button>
        </div>
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
                        <?php foreach ($errores as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><a href="events.php">Volver a listado de eventos</a></button>
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