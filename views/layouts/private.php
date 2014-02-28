<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title ?></title>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/app.css">
</head>
<body>

    <ul>
        <li><a href="./">Inicio</a></li>
        <li><a href="?page=users">Lista de usuarios</a></li>
        <li><a href="?page=home&amp;action=logout">Cerrar sesión</a></li>
    </ul>

    <?php echo $child ?>
</body>
</html>