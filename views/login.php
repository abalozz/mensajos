@extends 'layouts/public'

<header>
    <h1>Mensajos</h1>
</header>

<section id="login">
    <form action="?page=home&amp;action=login" method="POST">
        <div class="form_input">
            <label for="identity">Nombre de usuario o email:</label>
            <input type="text" id="identity" name="identity" value="<?php echo (isset($identity)) ? $identity : null; ?>" required>
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
    </form>
</section>

<footer>
    
</footer>