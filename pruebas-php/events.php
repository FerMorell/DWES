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
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$dbFile = __DIR__ . '/CalendarDataAccess.php.db';

$CalendarDataAccess = new CalendarDataAccess($dbFile);
$events = $CalendarDataAccess->getEventsByUserId($_SESSION['usuario']);

$user = $CalendarDataAccess->getUserById($_SESSION['usuario']);
$nombre = $user->getFirstName();
$apellido = $user->getLastName();
?>

<!--  Página con el listado de eventos del usuario conectado. -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <style>
        table {
            border: 1px solid black;
            border-collapse: collapse;

        }

        td,
        th {
            border: 1px solid black;

        }

        th {
            background-color: papayawhip;
        }

        td {
            background-color: whitesmoke;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href=" https://getbootstrap.esdocu.com/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="events.css">

</head>

<body>
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
                <p></p>
                <button type="button" class="btn btn-outline-primary me-2"><a href="change.password.php"> Cambiar contra</a></button>
                <p></p>
                <button type="button" class="btn btn-outline-primary me-2"> <a href="#"> Editar perfil </a></button>

            </div>
        </header>
    </div>

    <span class="visually-hidden">Nuevo Evento</span> <a href="new-event.php"><i class="fa-regular fa-calendar-plus"></i>
    </a>
    <table>
        <tr>
            <th> ID del evento</th>
            <th>Título</th>
            <th>Inicio</th>
            <th>Description</th>
            <th>EndDate</th>
            <th colspan="2">Modificacion</th>

        </tr>
        <?php foreach ($events as $event) { ?>
            <tr>
                <td> <?= $event->getId() ?></td>
                <td> <?= $event->getTitle() ?></td>
                <td> <?= $event->getStartDate() ?></td>
                <td> <?= $event->getDescription() ?></td>
                <td> <?= $event->getEndDate() ?></td>
                <td> <span class="visually-hidden">Editar evento</span> <a href="edit-event.php?id=<?= $event->getId() ?>"> <i class="fa-regular fa-pen-to-square"></i> </a> </td>
                <td> <span class="visually-hidden">Borrar evento</span> <a href="delete-event.php?id=<?= $event->getId() ?>"> <i class="fa-solid fa-trash-can"></i> </a> </td>

            </tr>

        <?php } ?>
    </table>
    <span class="visually-hidden">Nuevo Evento</span> <a href="new-event.php"><i class="fa-regular fa-calendar-plus"></i>
    </a>
</body>

</html>