{{ extends 'layouts/private' }}

<header>
    <div id="user-media">
        <img src="https://pbs.twimg.com/profile_banners/437581554/1393030827/web"
            alt="Cabecera" class="user-header-img">
        <img src="https://pbs.twimg.com/profile_images/3614570769/cd11aed39c0c64e296d00c7bd827bb3a.jpeg"
            alt="<?php echo $user->get_username() ?>" class="user-profile-img">
        <h1 class="name"><?php echo $user->get_username() ?> <span class="username">(<?php echo $user->get_username() ?>)</span></h1>
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
    <form action="?page=messages&amp;action=store" method="post">
        <textarea name="mensajo" cols="30" rows="10" placeholder="Envía un nuevo mensajo"></textarea>
        <button>Enviar</button>
    </form>
</div>

<section id="messages">
    <?php if (count($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <article class="message">
                <img src="https://pbs.twimg.com/profile_images/3614570769/cd11aed39c0c64e296d00c7bd827bb3a.jpeg"
                    alt="<?php echo $message->get_username() ?>" class="profile-img">
                <a href="?page=users&amp;action=show&amp;id=<?php echo $message->get_user_id() ?>"><?php echo $message->get_username() ?></a>
                <p>
                    <?php echo $message->get_content() ?>
                </p>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        No tienes mensajes que ver :(
    <?php endif; ?>
</section>
