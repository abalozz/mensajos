<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?></title>
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1, maximum-scale=2">

    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/normalize.css">

    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/nav.css">
    <link rel="stylesheet" href="css/header-user.css">
    <link rel="stylesheet" href="css/send-box.css">
    <link rel="stylesheet" href="css/messages.css">
    <link rel="stylesheet" href="css/users-list.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>

    <nav>
        <div class="max-nav-width">
            <a href="./">Inicio</a>
            <a href="?page=users&amp;action=show&amp;id=<?php echo Auth::user()->get_id() ?>">Perfil</a>
            <div class="nav-right">
                <a href="?page=settings">Ajustes</a>
                <a href="?page=home&amp;action=logout">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div id="wrap">
        <?php echo $child ?>
    </div>

    <script src="js/utils.js"></script>
    <script src="js/app.js"></script>
</body>
</html>