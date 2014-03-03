{{ extends 'layouts/private' }}

<div>
    Nombre de usuario: <?php echo $user->get_username() ?>
</div>

<div>
    Email: <?php echo $user->get_email() ?>
</div>

<div>
    Seguidores: <?php echo $user->get_number_of_followers() ?>
</div>

<div>
    Siguiendo: <?php echo $user->get_number_of_followings() ?>
</div>

<div>
    <?php if (Auth::user()->get_id() != $user->get_id()): ?>
        <?php if (Auth::user()->is_following_to($user)): ?>
            <a href="?page=users&amp;action=follow&amp;id=<?php echo $user->get_id() ?>">Dejar de seguir</a>
        <?php else: ?>
            <a href="?page=users&amp;action=follow&amp;id=<?php echo $user->get_id() ?>">Seguir</a>
        <?php endif; ?>
    <?php endif; ?>
</div>