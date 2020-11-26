<h1 class="d-flex justify-content-center mt-4 p-3 mb-2 bg-dark text-white">Liste des annonces</h1>
<?php $i = 0; $ok = true; ?>
<?php foreach($annonces as $annonce) : ?>
    <?= $ok ? '<div class="card-group">' : '' ?>
        <div class="card" style="min-width: 18rem; min-height : 18rem;">
            <div class="card-body">
                <h5 class="card-title"><?= $annonce->titre ?></h5>
                <h6 class="card-subtitle mb-2 text-muted">Créé par ?</h6>
                <p class="card-text"><?= $annonce->description ?></p>
                <a href="/annonces/read/<?= $annonce->id ?>" class="card-link">Voir l'annonce</a>
            </div>
        </div>
    <?php 
        $i++;
        if($i === 3){
            echo '</div>';
            $ok = true;
            $i = 0;
        }else{
            $ok = false;
        }

    ?>
<?php endforeach; ?>
<?= $i != 0 ? '</div>' : '' ?>