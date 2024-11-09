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
    $errores = [];

    if (!empty($_POST['contraActual'])) {
        $contrasenia = $_POST['contraActual'];
    } else {
        array_push($errores, "Contraseña inválida");
        $mostrarModal = true;
    }
    if (!empty($_POST['contraNueva'])) {
        $contraseniaNueva = $_POST['contraNueva'];
    } else {
        array_push($errores, "Contraseña inválida");
        $mostrarModal = true;
    }
    if (!empty($_POST['contraNueva2'])) {
        $contraseniaNueva2 = $_POST['contraNueva2'];
    } else {
        array_push($errores, "Contraseña inválida");
        $mostrarModal = true;
    }
    $user = $CalendarDataAccess->getUserById($_SESSION['usuario']);

    if ($user != null) {
        $contraOriginal = $user->getPassword();
        $contrasenia = $_POST['contraActual'];
        if (password_verify($contrasenia, $contraOriginal)) {
            $contraseniaNueva = $_POST['contraNueva'];
            $contraseniaNueva2 = $_POST['contraNueva2'];
            if ($contraseniaNueva === $contraseniaNueva2) {
                $newHashedPassword = password_hash($contraseniaNueva, PASSWORD_DEFAULT);
                $user->setPassword($newHashedPassword);
                $CalendarDataAccess->updateUser($user);
                if ($user != null) {
                    session_regenerate_id();
                    header("Location: events.php");
                    die();
                } else {
                    array_push($errores, "Ha ocurrido un error");
                    $mostrarModal = true;
                }
            } else {
                array_push($errores, "Contraseñas no coinciden");
                $mostrarModal = true;
            }
        } else {
            array_push($errores, "Contraseña original incorrecta");
            $mostrarModal = true;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="plantilla.css">
</head>

<body>
    <div class="formulario">

        <form action="" method="post">

            <div class="datos">
                <input type="password" name="contraActual" placeholder=" " id="contraActual" pattern="(?=.*\d)(?=.*[A-Za-z]).{8,}" required>
                <label for=" contraActual"> Contraseña Actual</label>
            </div>
            <div class="datos">
                <input type="password" name="contraNueva" placeholder=" " id="contraNueva" pattern="(?=.*\d)(?=.*[A-Za-z]).{8,}" required>
                <label for=" contraNueva">Contraseña Nueva</label>
            </div>
            <div class="datos">
                <input type="password" name="contraNueva2" placeholder="" id="contraNueva2" pattern="(?=.*\d)(?=.*[A-Za-z]).{8,}" required>
                <label for=" contraNueva2">Repite la contraseña</label>
            </div>
            <button type="submit" class="btn btn-success" name="enviar" value="">Cambiar</button>

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
</body>
<script>
    <?php if ($mostrarModal): ?>
        let errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    <?php endif; ?>
</script>

</html>