@extends 'layouts/public'

<header>
    <h1>Registro</h1>
</header>

<section id="form">
    <form action="?page=home&amp;action=store" method="POST">
        <div class="form_input">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" name="username" value="<?php echo (isset($username)) ? $username : null; ?>" >
        </div>
        <div class="form_input">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo (isset($email)) ? $email : null; ?>" >
        </div>
        <div class="form_input">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" >
        </div>
        <div class="form_button">
            <button>Registrarse</button>
        </div>
        <?php if (isset($errors)): ?>
            <p id="errors">
                <?php echo $errors ?>
            </p>
        <?php endif; ?>
        <p id="reg">¿Ya tienes una cuenta? <a href="./">¡Inicia sesión!</a></p>
    </form>
</section>

<footer>
    
</footer>