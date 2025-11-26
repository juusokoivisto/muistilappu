<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lexend+Deca:wght@100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="index.js" defer></script>
    <title>Muistilappu</title>
</head>
<body>
    <header class="py-3 bg-light border-bottom">
        <div class="d-flex justify-content-between align-items-center pe-3">
            <div class="d-flex gap-2 ms-3">
                <a id="createStickyNote" class="btn btn-outline-secondary d-flex align-items-center">
                    <i class="bi bi-stickies me-2"></i>
                    <span>Uusi muistilappu</span>
                </a>

                <?php if(isset($_SESSION["id"])): ?>
                    <a id="saveStickyNotes" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="bi bi-database-add me-2"></i>
                        <span>Tallenna muistilaput</span>
                    </a>

                    <a id="getStickyNotes" class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="bi bi-cloud-arrow-down me-2"></i>
                        <span>Hae muistilaput</span>
                    </a>
                <?php else: ?>                    
                    <div class="d-flex align-items-center">
                        <span>Kirjaudu sisään, että voit tallentaa sinun muistilaput.</span>
                    </div>                
                <?php endif ?>
            </div>

            <div class="d-flex gap-2 align-items-center">
                <?php if(isset($_SESSION["username"])): ?>
                    <span>Hei, <?php echo $_SESSION["username"]; ?></span>
                <?php endif ?>
                
                <a href="/index.php" class="btn btn-outline-secondary">Muistilaput</a>
                <?php if(isset($_SESSION["id"])): ?>
                    <a href="/logout.php" class="btn btn-outline-secondary">Kirjaudu ulos</a>
                <?php else: ?>
                    <a href="/login.php" class="btn btn-outline-secondary">Kirjaudu sisään</a>
                <?php endif ?>
            </div>
        </div>
    </header>

    <main>
    <div class="sticky-note-holder" id="stickyNoteHolder"></div>
    </main>
</body>
</html>