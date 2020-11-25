<?php if(!empty($_SESSION['erreur'])) : ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $_SESSION['erreur']; unset($_SESSION['erreur']); ?>
    </div>
<?php endif; ?>
<h1>Connexion</h1>
<?= $loginForm ?>
<a href="/users/register">Pas inscrit - M'inscrire</a>