<?php
session_start();
require_once __DIR__ . "/data-access/CalendarDataAccess.php";
?>


<!-- Página para registrarse en la aplicación. -->
<?php
if (!isset($_SESSION['iniciada'])) {
} else {
    header("Location: events.php");
    die();
}
?>

<?php
$mostrarModal = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errores = [];
    if (!empty($_POST['contrasenia']) && !empty($_POST['contrasenia2'])) {
        $password1 = $_POST['contrasenia'];
        $password2 = $_POST['contrasenia2'];

        if ($password1 === $password2) {
            $password = $_POST['contrasenia'];
        } else {
            array_push($errores, "Las contraseñas no coinciden.");
            $mostrarModal = true;
        }
    } else {
        $errores = "Por favor, completa ambos campos de contraseña.";
        $mostrarModal = true;
    }


    if (empty($_POST['nombre'])) {
        array_push($errores, "No has puesto bien el nombre o lo has enviado vacio.");
        $mostrarModal = true;
    } else {
        $nombre = $_POST['nombre'];
    }


    if (!filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
        array_push($errores, "El correo que has puesto es inválido.");
        $mostrarModal = true;
    } else {
        $email = $_POST['email'];
    }


    if (empty($_POST['apellidos'])) {
        array_push($errores, "No has puesto bien el apellido o lo has enviado vacio.");
        $mostrarModal = true;
    } else {
        $apellido = $_POST['apellidos'];
    }


    //HACER  EXCEPCION DE FECHA NACIMIENTO
    if (empty($_POST['fechaNacimiento'])) {
        array_push($errores, "No has puesto bien la fecha de nacimiento/ viene vacía.");
        $mostrarModal = true;
    } else {
        $fechaNacimiento = $_POST['fechaNacimiento'];
    }

    $dbFile = __DIR__ . '/CalendarDataAccess.php.db';

    $CalendarDataAccess = new CalendarDataAccess($dbFile);

    $user = $CalendarDataAccess->getUserByEmail($email);

    if ($user == null) {
        //no existe un usuario con ese correo 
        $newUser = new User($email,  password_hash($password1, PASSWORD_DEFAULT), $nombre, $apellido, $fechaNacimiento, 'Un nuevo usuario.');
        $created = $CalendarDataAccess->createUser($newUser);

        $mostrarModal2 = true;
    } else {
        array_push($errores, "Ya hay un usuario con este correo $email");
        $mostrarModal = true;
    }
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="plantilla.css">
</head>

<body>
    <div class="formulario">
        <form method="post" action="">
            <div class="seccion">
                <div class="datos">
                    <input type="email" id="email" name="email" placeholder=" " value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" required>
                    <label for="email">Correo Electrónico:</label>

                </div>
                <div class="datos">
                    <input type="text" placeholder=" " required name="nombre" value="<?= isset($_POST['nombre']) ? $_POST['nombre'] : '' ?>">
                    <label for="nombre">Nombre:</label>
                </div>
                <div class="datos">
                    <input type="text" placeholder=" " required name="apellidos" value="<?= isset($_POST['apellidos']) ? $_POST['apellidos'] : '' ?>">
                    <label for="apellidos">Apellidos: </label>
                </div>
                <div class="datos">
                    <input type="date" placeholder=" " required name="fechaNacimiento" value="<?= isset($_POST['fechaNacimiento']) ? $_POST['fechaNacimiento'] : ' ' ?>">
                    <label for="fechaNacimiento">Fecha Nacimiento:</label>
                </div>
                <div class="datos">

                    <input type="password" name="contrasenia" placeholder=" " id="contrasenia" required min=8 value="<?= isset($_POST['contrasenia']) ? $_POST['contrasenia'] : '' ?>" pattern="(?=.*\d)(?=.*[A-Za-z]).{8,}">
                    <label for="contrasenia">Contraseña:</label>
                </div>
                <div class="datos">

                    <input type="password" name="contrasenia2" placeholder=" " required id="contraseniaRepetida" min=8 pattern="(?=.*\d)(?=.*[A-Za-z]).{8,}"> <label for="contraseniaRepetida">Repetir Contraseña:</label>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit">Registar</button>
            </div>
        </form>
    </div>

    </form>
    </div>

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




    <div class="modal fade" id="errorModal2" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Has Creado el usuario correctamente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                </div>
                <div class="modal-footer">
                    <a href="index.php"> <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Iniciar</button>
                    </a>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
<script>
    <?php if ($mostrarModal): ?>
        let errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    <?php endif; ?>

    <?php if ($mostrarModal2): ?>
        let errorModal2 = new bootstrap.Modal(document.getElementById('errorModal2'));
        errorModal2.show();
    <?php endif ?>
</script>

</html>