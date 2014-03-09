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

    <div id="send-mensajo-box">
        <textarea id="content-message" name="message" cols="30" rows="10" placeholder="Envía un nuevo mensajo"></textarea>
        <button id="send-message">Enviar</button>
    </div>
</div>

<section id="messages">
    <?php if (count($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <article class="message">
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
                    </menu>
                </div>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        No tienes mensajes que ver :(
    <?php endif; ?>
</section>
