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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['eliminar'])) {
        $borrar = $_POST['eliminar'];
        // $idGet = $_GET['id'];
        session_destroy();
        // $eventDeleted = $CalendarDataAccess->deleteEvent($idGet);
        session_regenerate_id();
        header("Location: index.php");
        die();
    }
    if (isset($_POST['noEliminar'])) {
        $noBorrar = $_POST['noEliminar'];
        if ($noBorrar != null) {
            header("Location: events.php");
            die();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="plantilla.css">
</head>

<body>
    <div class="formulario">
        <form method="post">

            <h1>¿Seguro que deseas desconectar?</h1><!-- poner el titulo php!-->
            <button type="submit" class="btn btn-danger" name="eliminar" value="eliminar">Si, desconectar</button>
            <p></p>
            <button type="submit" class="btn btn-success" name="noEliminar" value="noEliminar">No, volver a listado de eventos</button>


        </form>
    </div>
</body>

</html>