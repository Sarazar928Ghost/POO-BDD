<table class="table table-bordered table-dark">
    <thead>
        <th scope="col">ID</th>
        <th scope="col">Titre</th>
        <th scope="col">Contenu</th>
        <th scope="col">Actif</th>
        <th scope="col">Actions</th>
    </thead>
    <tbody>
        <?php foreach($annonces as $annonce) : ?>
            <tr>
                <th><strong><?= $annonce->id ?></strong></th>
                <td><?= $annonce->titre ?></td>
                <td><?= $annonce->description ?></td>
                <td>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="customSwitch<?= $annonce->id ?>" <?= $annonce->actif ? 'checked' : '' ?> data-id="<?= $annonce->id ?>">
                        <label class="custom-control-label" for="customSwitch<?= $annonce->id ?>"></label>
                    </div>
                </td>
                <td colspan="2">
                    <a href="/annonces/modifier/<?= $annonce->id ?>" class="btn btn-warning">Modifier</a>
                    <a href="/users/deleteAnnonce/<?= $annonce->id ?>" class="btn btn-danger">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>