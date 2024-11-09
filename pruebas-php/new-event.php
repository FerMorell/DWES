<?php
session_start();
require_once __DIR__ . "/data-access/CalendarDataAccess.php"; ?>


<?php
if ($_SESSION['iniciada']) {
} else {
    header("Location: index.php");
    die();
}
?>

<?php
$id = $_SESSION['usuario'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errores = [];
    $dbFile = __DIR__ . '/CalendarDataAccess.php.db';

    $CalendarDataAccess = new CalendarDataAccess($dbFile);
    $newEvent = new Event($id, $_POST['titulo'], $_POST['desc'], $_POST['fechaHora1'], $_POST['fechaHora2']);
    $eventCreated = $CalendarDataAccess->createEvent($newEvent);
    if ($eventCreated) {
        header("Location: events.php");
        die();
    } else {
        array_push($errores, "Error de validación");
        $mostrarModal = true;
    }
}

$dbFile = __DIR__ . '/CalendarDataAccess.php.db';

$CalendarDataAccess = new CalendarDataAccess($dbFile);
$user = $CalendarDataAccess->getUserById($_SESSION['usuario']);
$nombre = $user->getFirstName();
$apellido = $user->getLastName();

?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href=" https://getbootstrap.esdocu.com/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="editEvent.css">
</head>

<!-- <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom"></header> -->
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

<body>

    <form method="post" action="">

        <div class="seccion">

            <label>Título:
                <input type="text" id="titulo" name="titulo" value="<?= isset($_POST['titulo']) ? $_POST['titulo'] : ' ' ?>" required></label>
            <p></p>
            <label for="desc">Descripción: </label>
            <textarea name="desc" id="desc"></textarea>
            <p></p>
            <label>Fecha y hora inicio: <input type="datetime-local" min="<?= date("Y-m-d\TH:i"); ?>" required name="fechaHora1" value="<?= isset($_POST['fechaHora1']) ? $_POST['fechaHora1'] : ' ' ?>"></label>
            <p></p>
            <label>Fecha y hora fin: <input type="datetime-local" min="<?= date("Y-m-d\TH:i"); ?>" required name="fechaHora2" value="<?= isset($_POST['fechaHora2']) ? $_POST['fechaHora2'] : ' ' ?>"></label>
            <p></p>

            <!-- <p class="text-danger"><?= $passwordError ?></p> -->

            <p></p>
        </div>

        <div class="d-grid gap-2">
            <button class="btn btn-primary" type="submit">Registar</button>
        </div>
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
                        <?php foreach ($errores as $errors): ?>
                            <li><?= $errors ?></li>
                        <?php endforeach; ?>
                    </ul>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>

            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<script>
    <?php if ($mostrarModal): ?>
        let errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    <?php endif; ?>
</script>

</html>