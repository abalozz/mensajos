@extends 'layouts/private'

<section id="users-list">
    <?php foreach ($users as $user): ?>
        <article class='user'>
            <a href="?page=users&amp;action=show&amp;id=<?php echo $user->get_id() ?>">
                <img src="<?php echo $user->get_profile_image() ?>" alt="<?php $user->get_name() ?>" class="profile-image">
                <h2 class="name">
                    <?php echo $user->get_name() ?>
                </h2>
                <span class="username"><?php echo $user->get_username() ?></span>
            </a>
            <a href="?page=users&amp;action=follow&amp;id=<?php echo $user->get_id() ?>" class="btn follow">
                <?php if (Auth::user()->is_following_to($user)): ?>
                    Dejar de seguir
                <?php else: ?>
                    Seguir
                <?php endif; ?>
            </a>
        </article>
    <?php endforeach; ?>
</section>