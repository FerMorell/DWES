<?php session_start();
require_once __DIR__ . "/data-access/CalendarDataAccess.php"; ?>


<?php
if (isset($_SESSION['iniciada'])) {
    header("Location: events.php");
    die();
}
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errores = [];
    if (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
        $email = $_POST['email'];

        if (!empty($_POST['contrasenia'])) {
            $contrasenia = $_POST['contrasenia'];
        } else {
            array_push($errores, "Contraseña inválida");
            $mostrarModal = true;
        }
    } else {
        array_push($errores, "Email inválido");
        $mostrarModal = true;
    }
    $dbFile = __DIR__ . '/CalendarDataAccess.php.db';
    $CalendarDataAccess = new CalendarDataAccess($dbFile);
    $user = $CalendarDataAccess->getUserByEmail($email);

    if ($user != null) {
        $contraOriginal = $user->getPassword();
        if (password_verify($contrasenia, $contraOriginal)) {
            $_SESSION['usuario'] = $user->getId();
            $_SESSION['iniciada'] = true;
            session_regenerate_id();
            header("Location: events.php");
            die();
        } else {
            array_push($errores, "Contraseña inválida");
            $mostrarModal = true;
        }
    } else {
        array_push($errores, "No existe ningún usuario con este correo $email");
        $mostrarModal = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="plantilla.css">
</head>

<body>
    <div class="formulario">

        <form method="POST">
            <div class="datos">
                <input type="email" id="email" name="email" placeholder="nombre@gmail.com" value="<?= isset($_POST['email']) ? $_POST['email'] : ' ' ?>" required>
                <label for="email">Introduce el email: </label>
            </div>
            <div class="datos">
                <input type="password" name="contrasenia" placeholder="Contraseña" value="<?= isset($_POST['contrasenia']) ? $_POST['contrasenia'] : '' ?>" required>
                <!-- pattern="(?=.*\d)(?=.*[A-Za-z]).{8,}"  Lo he comentado para ir probando el resto de usuarios-->
                <label for="contrasenia">Introduce la contraseña: </label>

            </div>
            <button type="submit" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Enviar</button>
            <a href="register.php" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true">Registrate</a>

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