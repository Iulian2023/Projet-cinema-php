<?php
    session_start();
    //Définitions du titre spécifique à la page
    $title = "Page d'accueil" ?>
<?php 
//Chargement de l'entête ainsi que la balise ouvrante du body 
 require __DIR__ . "/components/head.php"; ?>

<!-- Chargement de la barre de navigation -->
<?php require __DIR__ . "/components/nav.php"; ?>
    <!-- Chargemnt du contenu spécific à la page -->
    <main class="container-fluid">
        <h1 class="text-center my-3 display-5">Listes des films</h1>
        <?php if(isset($_SESSION['success']) && !empty($_SESSION['success']) ) : ?>  
            <div class="text-center alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif ?>

        <div class="d-flex justify-content-end align-items-center">
            <a class="btn btn-primary" href="create.php">Nouveau film</a>
        </div>
    </main>
    <!-- Chargement du pied de page -->
<?php require __DIR__ . "/components/footer.php"; ?>
    <!-- Chargement de la fermenture du document -->
<?php require __DIR__ . "/components/foot.php"; ?>
