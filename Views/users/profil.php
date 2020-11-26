<h1 class="mt-4">Panel utilisateur</h1>
<a href="/annonces/ajouter" class="btn btn-primary mb-4">Créer une annonce</a>
<h1>Mes annonces</h1>
    <table class="table">
    <thead>
        <th>ID</th>
        <th>Titre</th>
        <th>Contenu</th>
        <th>Actif</th>
        <th>Actions</th>
    </thead>
    <tbody>
        <?php foreach($annonces as $annonce) : ?>
            <tr>
                <td><?= $annonce->id ?></td>
                <td><?= $annonce->titre ?></td>
                <td><?= $annonce->description ?></td>
                <td><?= $annonce->actif ?></td>
                <td>
                    <a href="/annonces/modifier/<?= $annonce->id ?>" class="btn btn-warning">Modifier</a>
                    <a href="/users/deleteAnnonce/<?= $annonce->id ?>" class="btn btn-danger">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>