@extends 'layouts/private'

<div id="user-display">
    <header>
        <div id="user-media">
            <img src="<?php echo $user->get_header_image() ?>"
                alt="Cabecera de <?php echo $user->get_name() ?>" class="user-header-img">
            <img src="<?php echo $user->get_profile_image() ?>"
                alt="<?php echo $user->get_name() ?>" class="user-profile-img">
            <h1 class="name"><?php echo $user->get_name() ?> <span class="username">(<?php echo $user->get_username() ?>)</span></h1>
        </div>
        <div id="user-stats">
            <a href="?page=users&amp;action=show&amp;id=<?php echo $user->get_id() ?>" id="mensajos" class="stat">
                <span class="text">Mensajos</span>
                <span class="count"><?php echo $user->get_number_of_messages() ?></span>
            </a
            ><a href="?page=users&amp;action=followers&amp;id=<?php echo $user->get_id() ?>" id="followers" class="stat">
                <span class="text">Seguidores</span>
                <span class="count"><?php echo $user->get_number_of_followers() ?></span>
            </a
            ><a href="?page=users&amp;action=followings&amp;id=<?php echo $user->get_id() ?>" id="following" class="stat">
                <span class="text">Siguiendo</span>
                <span class="count"><?php echo $user->get_number_of_followings() ?></span>
            </a>
        </div>
    </header>

    <?php if (Auth::user()->get_id() != $user->get_id()): ?>
        <div class="follow">
            <a href="?page=users&amp;action=follow&amp;id=<?php echo $user->get_id() ?>" class="btn">
                <?php if (Auth::user()->is_following_to($user)): ?>
                    Dejar de seguir
                <?php else: ?>
                    Seguir
                <?php endif; ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<section id="messages">
    <?php if (count($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <article class="message" id="message-id-<?php echo $message->get_id() ?>">
                <figure class="profile-img">
                    <img src="<?php echo $message->get_user()->get_profile_image() ?>"
                        alt="<?php echo $message->get_user()->get_name() ?>">
                </figure>
                <div class="content">
                    <a href="?page=users&amp;action=show&amp;id=<?php echo $message->get_user_id() ?>">
                        <?php echo $message->get_user()->get_name() ?> (<?php echo $message->get_user()->get_username() ?>)
                    </a>
                    <p>
                        <?php echo $message->get_content() ?>
                    </p>
                    <menu class="actions">
                        <?php if (!$message->is_from(Auth::user())): ?>
                            <a href="?page=messages&amp;action=forward&amp;id=<?php echo $message->get_id() ?>">
                                <?php if ($message->is_forwarded(Auth::user())): ?>
                                    Reenviado
                                <?php else: ?>
                                    Reenviar
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($message->is_from(Auth::user())): ?>
                            <a href="#" data-message-id="<?php echo $message->get_id() ?>" class="delete-message">Eliminar</a>
                        <?php endif; ?>
                    </menu>
                </div>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        No tienes mensajes que ver :(
    <?php endif; ?>
</section>