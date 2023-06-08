<?php

    // var_dump($_SERVER);
    // die()

    // Si les données arrive au serveur via la méthode "POST", 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        //  Un peut de cyber sécurité
        // Protection contre les failles de type XSS pour éviter les injection de scripts malveillants
        $post_clean = [];

        foreach ($_POST as $key => $value) {
            $post_clean[$key] = htmlspecialchars(trim(addslashes($value))); 
        }


        // Protection contre les failles de type CSRF
        
        // Protection contre les spams
        
        // Validation des données
        
        // S'il y a des erreurs,
        
        // Sauvegarder les anciennes données envoyées en sesion,
        // Sauvegarder les messages d'erreurs en sesion,
        // Redirection vers la page laquelle proviennent les information,
        // Arrêt de l'execution du script.
        
        // Dans le cas contraitre,
        // Etablir une connexion avec la basse des données
        
        //Effectuer une requête d'insertion des données dans la table "films",
        // Efferctuer une redirection ver la page d'accueil
        
        // Arrêter l'exécution du script
    }

?>

<?php require __DIR__ . "/components/head.php"; ?>
<?php require __DIR__ . "/components/nav.php"; ?>

<!-- Chargement du contenu spécific à la page -->
<main class="container">
        <h1 class="text-center my-3 display-5">Nouveau film</h1>

        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto shadow bg-white p-4">
                    <form method="post">
                        <div class="mb-3">
                            <label for="name">Le Nom du film <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" autofocus >
                        </div>
                        <div class="mb-3">
                            <label for="actors">Le Nom du/des acteur(s) <span class="text-danger">*</span></label>
                            <input type="text" name="actors" id="actors" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="review">La note / 5</label>
                            <input type="text" name="review" id="review" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="comment">Un commentaire ?</label>
                            <textarea name="comment" id="comment" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <input type="submit" class="btn btn-primary shadow" value="Envoyer">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
        
<?php require __DIR__ . "/components/footer.php"; ?>
<?php require __DIR__ . "/components/foot.php"; ?>