<?php
    session_start();

    /* Si l'identifinat du film à modifier n'existe pas dans*/

        if( !isset($_GET['film_id']) || empty($_GET['film_id'])) {
            //On redirige l'utilisateur vers la page d'accueil
            //  On arrête l'exécution du script 
            return header("Location: index.php");
        }



    /* Dans le cas contraire, */

    /* Récuperer l'identifiant du film, */
    // Tout en protegant le sytéme contre les failles de type XSS
    $film_id = (int) htmlentities((trim($_GET['film_id'])));

    /* Etablir un connexion avec la base des données */
    require __DIR__ . "/db/connexion.php";

    /* Effectuer une requête pour vérifier que l'identifiant appartient à celui d'un film de la table "film"*/
    $req = $db->prepare("SELECT * FROM film WHERE id=:id LIMIT 1");
    $req->bindValue(":id", $film_id);
    $req->execute();
        
    // Compter les nombres des enregistrement récupéré de la table film
    $row = $req->rowCount();

    // Si ce n'est pas le cas égal à 1
    if ($row != 1) {
        // On redirige l'utilisateur vers la page d'accueil
        // On arrête l'exécution du script//
        return header("Location: index.php");
    }
        
    // Dans le cas contaire
    // Récupérons les informations du film à modifer
    $film = $req->fetch();
        



    // Si les données arrive au serveur via la méthode "POST", 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //  Un peut de cyber sécurité
        // Protection contre les failles de type XSS pour éviter les injection de scripts malveillants
        $post_clean = [];
        $errors     = [];

        foreach ($_POST as $key => $value) {
            $post_clean[$key] = htmlspecialchars(trim(addslashes($value))); 
        }




        // Protection contre les failles de type CSRF

        /*
        * SI la clé csrtf_token n'existe pas dans les sessions ou dans le formulaire envoyé
        * OU que la valeur qui leur est associée est vide
        * OU que leur valeur n'est pas identique,
        */
        if ( ! isset($_SESSION['csrf_token']) 
            || ! isset($post_clean['csrf_token']) 
            ||empty($_SESSION['csrf_token']) 
            || empty($post_clean['csrf_token']) 
            ||$_SESSION['csrf_token'] 
            !== $post_clean['csrf_token']) {

            /* Effectuons une redirection ver la page de laquelle preoviennet les informations
            * Puis, arrêtons l'exécution du script.
            */
            unset($_SESSION['csrf_token']);
            return header("Location: $_SERVER[HTTP_REFERER]");
        }
        unset($_SESSION['csrf_token']);

        // Protection contre les spams
        if ( ! isset ($post_clean['honey_pot']) || ($post_clean['honey_pot'] !== "")) {
            /* Effectuons une redirection ver la page de laquelle preoviennet les informations
            * Puis, arrêtons l'exécution du script. */

            return header("Location: $_SERVER[HTTP_REFERER]");
        }

        // Validation des données,
        if ( isset($post_clean['name']) ) {
            if ( empty($post_clean['name']) ) {
                $errors['name'] = "Le nom du film est obligatoire";
            }
            elseif (mb_strlen($post_clean['name']) > 255) {
                $errors['name'] = "Le nom du film ne doit pas dépasser 255 caractères";
            }
        }
        
        if ( isset($post_clean['actors']) ) {
            if ( empty($post_clean['actors']) ) {
                $errors['actors'] = "Le nom du ou des acteurs est obligatoire";
            }
            elseif (mb_strlen($post_clean['actors']) > 255) {
                $errors['actors'] = "Le nom du ou des acteurs ne doit pas dépasser 255 caractères";
            }
        }


        if (isset($post_clean['review'])) {
            if ( ($post_clean['review']) !== "") {
                if ( ! is_numeric($post_clean['review'])) {
                    $errors['review'] = "Veuillez entrer un nombre";
                }
                elseif (($post_clean['review'] < '0') || ($post_clean['review'] > '5')) {
                    $errors["review"] = "La note doit être comprise entre 0 et 5.";
                }
            }
        }



        if (isset($post_clean['comment'])){
            if ( $post_clean['comment'] !== "" ) {
                if (mb_strlen($post_clean['comment']) > 1000) {
                    $errors['comment'] = "Le commentaire ne doit pas dépasser 1000 caractères.";
                }
            }
        }
        // S'il y a des erreurs,
        if (count($errors) > 0) {
            // Sauvegarder les anciennes données envoyées en sesion,
            $_SESSION['old'] = $post_clean;

            // Sauvegarder les messages d'erreurs en sesion,
            $_SESSION['form_errors'] = $errors;

            /* Redirection vers la page laquelle proviennent les information,
            * Arrêt de l'execution du script. */
            return header(("Location: $_SERVER[HTTP_REFERER]"));
        }
        
        if (isset($post_clean['review']) && !empty($post_clean['review'])) {
            
            // L'arrondoir à une chiffre apreès la virgules
            $review_rounded = round($post_clean['review'], 1);
        }
        // Etablir une connexion avec la basse des données
        require __DIR__ . "/db/connexion.php";

        //Effectuer une requête d'insertion des données dans la table "film",
        $req = $db->prepare("UPDATE film SET name=:name, actors=:actors, review=:review, comment=:comment, updated_at=now() WHERE id=:id");

        $req -> bindValue(":name",      $post_clean['name']);
        $req -> bindValue(":actors",    $post_clean['actors']);
        $req -> bindValue(":review",    isset($review_rounded) ? $review_rounded : "" );
        $req -> bindValue(":comment",   $post_clean['comment']);
        $req -> bindValue(":id",        $film['id']);

        $req->execute();

        /* Non obligatoire */
        $req->closeCursor();

        /* Générons un message flesh de succés */
        $_SESSION['success'] = "<em>" . stripslashes($post_clean['name']) ."</em> a été modifié avec succès";

        //Efferctuer une redirection ver la page d'accueil
        // Arrêter l'exécution du script
        return header("Location: index.php");
    };

    $_SESSION['csrf_token'] = bin2hex(random_bytes(30));
?>

<?php require __DIR__ . "/components/head.php"; ?>
<?php require __DIR__ . "/components/nav.php"; ?>

<!-- Chargement du contenu spécific à la page -->
<main class="container">
        <h1 class="text-center my-3 display-5">Modifier ce film</h1>

        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto shadow bg-white p-4">
                    <?php if (isset($_SESSION['form_errors']) && !empty(isset($_SESSION['form_errors']))) :?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                        <?php foreach($_SESSION['form_errors'] as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach ?>
                    </ul>
                    </div>
                    <?php unset($_SESSION['form_errors']);?>
                    <?php endif ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="name">Le Nom du film <span class="text-danger">*</span></label>
                            <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="form-control" 
                            autofocus 
                            maxlength="255" 
                            value="<?= isset($_SESSION['old']['name']) && !empty($_SESSION['old']['name']) ? stripslashes($_SESSION['old']['name']) : htmlspecialchars(stripslashes($film['name'])) ; unset($_SESSION['old']['name']);?>">
                        </div>
                        <div class="mb-3">
                            <label for="actors">Le Nom du/des acteur(s) <span class="text-danger">*</span></label>
                            <input 
                            type="text" 
                            name="actors" 
                            id="actors" 
                            class="form-control" 
                            maxlength="255"
                            value="<?= isset($_SESSION['old']['actors']) && !empty($_SESSION['old']['actors']) ? stripslashes($_SESSION['old']['actors']) : htmlspecialchars(stripslashes($film['actors'])); unset($_SESSION['old']['actors']);?>">
                        </div>
                        <div class="mb-3">
                            <label for="review">La note / 5</label>
                            <input 
                            type="number" 
                            name="review" 
                            id="review" 
                            step=".1" 
                            max="5" 
                            min="0" 
                            class="form-control"
                            value="<?= isset($_SESSION['old']['review']) && !empty($_SESSION['old']['review']) ? stripslashes($_SESSION['old']['review']) : htmlspecialchars(stripslashes($film['review'])); unset($_SESSION['old']['review']);?>" >
                        </div>
                        <div class="mb-3">
                            <label for="comment">Un commentaire ?</label>
                            <textarea 
                            name="comment" 
                            id="comment" 
                            class="form-control" 
                            rows="4"><?= isset($_SESSION['old']['comment']) && !empty($_SESSION['old']['comment']) ? stripslashes($_SESSION['old']['comment']) : htmlspecialchars(stripslashes($film['comment'])); unset($_SESSION['old']['comment']);?></textarea>
                        </div>
                        <div class="mb-3">
                            <input 
                            type="hidden" 
                            name="csrf_token" 
                            value="<?= $_SESSION['csrf_token']; ?>">
                        </div>
                        <div class="mb-3">
                            <input 
                            type="hidden" 
                            name="honey_pot" 
                            value="">
                        </div>
                        <div class="mb-3">
                            <input 
                            formnovalidate 
                            type="submit" 
                            class="btn btn-secondary shadow" 
                            value="Modifier">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
        
<?php require __DIR__ . "/components/footer.php"; ?>
<?php require __DIR__ . "/components/foot.php"; ?>