@extends 'layouts/private'

<ul>
    <?php foreach ($users as $user): ?>
        <li><a href="?page=users&amp;action=show&amp;id=<?php echo $user->get_id() ?>"><?php echo $user->get_username() ?></a></li>
    <?php endforeach; ?>
</ul>