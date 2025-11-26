<?php
session_start();
if(isset($_SESSION["id"])) {
    header("location:index.php");
    exit();
}

$errorMessage = $_GET['error'] ?? '';
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lexend+Deca:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Kirjaudu</title>
</head>
<body>
    <header class="py-3 bg-light">
        <div class="d-flex justify-content-end gap-2 pe-3">
            <a href="/index.php" class="btn btn-outline-secondary">Muistilaput</a>

            <?php if(isset($_SESSION["id"])): ?>
                <a href="/logout.php" class="btn btn-outline-secondary">Kirjaudu ulos</a>
            <?php else: ?>
                <a href="/login.php" class="btn btn-outline-secondary">Kirjaudu sisään</a>
            <?php endif ?>
        </div>
    </header>

    <main>
        <h1 class="text-center mt-5">Kirjaudu Sisään</h1>
        <form method="post" action="login-action.php" class="d-flex justify-content-center mt-5">
            <div class="loginForm card p-4" style="width: 400px;">
                <?php if ($errorMessage): ?>
                    <div class="alert alert-danger text-center">
                        <?php echo htmlspecialchars($errorMessage); ?>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="f_username" class="form-label"><b>Käyttäjätunnus</b></label>
                    <input type="text" class="form-control" placeholder="Käyttäjätunnus..." name="f_username" required>
                </div>

                <div class="mb-3">
                    <label for="f_password" class="form-label"><b>Salasana</b></label>
                    <input type="password" class="form-control" placeholder="Salasana..." name="f_password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Kirjaudu</button>                
                <div class="text-center">
                    <span class="forgot_password">Ei käyttäjätunnuksia? <a href="/register.php">Rekisteröidy</a></span>
                </div>            
            </div>
        </form>
    </main>
</body>
</html>