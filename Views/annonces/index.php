<h1 class="d-flex justify-content-center mt-4 p-3 mb-2 bg-dark text-white">Liste des annonces</h1>
<?php // Permet d'injecter une div qui englobe 3 card maximum ?>
<?php $i = 0; $addCardDeck = true; ?>
<?php foreach($annonces as $annonce) : ?>
    <?= $addCardDeck ? '<div class="card-deck">' : '' ?>
        <div class="card">
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
            // On ferme la div du card-deck
            echo '</div>';
            // On permet une nouvelle injection d'un card-deck
            $addCardDeck = true;
            $i = 0;
        }else{
            // On bloque une nouvelle injection d'un card-deck
            $addCardDeck = false;
        }

    ?>
<?php endforeach; ?>
<?php //Si c'est le cas c'est qu'il faut fermer un card-deck ?>
<?= $i != 0 ? '</div>' : '' ?>