{{ extends 'layouts/private' }}

<div>
    Nombre de usuario: <?php echo $user->get_username() ?>
</div>

<div>
    Email: <?php echo $user->get_email() ?>
</div>

<div>
    <?php if ($user->is_followed_by(Auth::user())): ?>
        <a href="?page=users&amp;action=follow&amp;id=<?php echo $user->get_id() ?>">Dejar de seguir</a>
    <?php else: ?>
        <a href="?page=users&amp;action=follow&amp;id=<?php echo $user->get_id() ?>">Seguir</a>
    <?php endif; ?>
</div>