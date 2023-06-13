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
    // Récupérons les informations du film à supprimer
    $film = $req->fetch();

    // Effectuer une nouvelle requête pour la suppression
    $delete_req = $db->prepare("DELETE FROM film WHERE id=:id");
    $delete_req ->bindValue(":id", $film["id"]);
    $delete_req->execute();
    $delete_req->closeCursor();

    //  Générer le message flash de suppresion
    $_SESSION['success'] = "<em>" . stripslashes($film['name']) . "</em> a été ajoute à la liste avec succès";
    //  Effectuer une redirection ver la page index
    // Arrêter l'éxecution du script
    return header(("Location: index.php"))

?>