{{ extends 'layouts/private' }}

<p>Usuario: <?php echo $user->get_username() ?></p>
<h3><?php echo $mensajos ?></h3>

<form action="?page=messages&amp;action=store" method="post">
    <textarea name="mensajo" cols="30" rows="10" placeholder="Envía un nuevo mensajo"></textarea>
    <button>Enviar</button>
</form>
