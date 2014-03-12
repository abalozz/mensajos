@extends 'layouts/public'

<header>
    <h1>Mensajos</h1>
</header>

<section id="form">
    <form action="?page=home&amp;action=login" method="POST">
        <div class="form_input">
            <label for="identity">Nombre de usuario o email:</label>
            <input type="text" id="identity" name="identity" value="<?php echo (isset($identity)) ? $identity : null; ?>"
                required autofocus>
        </div>
        <div class="form_input">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form_button">
            <button>Iniciar sesión</button>
        </div>
        <?php if (isset($errors)): ?>
            <p id="errors">
                <?php echo $errors ?>
            </p>
        <?php endif; ?>
        <p id="reg">¿No tienes una cuenta? <a href="?action=reg">¡Regístrate!</a></p>
    </form>
</section>

<footer>
    
</footer>