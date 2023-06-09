<!DOCTYPE html>
<html lang="fr">
    <head>
        <!-- Encodage des caractères -->
        <meta charset="UTF-8">
        <!-- Minumum de compatibilité avec Internet Explorer -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--  Minimum de Responsive design à toujour mettre en place -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- le titre de la page -->
        <title>Cinema <?= isset($title) ? " - $title" : ""; ?></title>

        <!-- Ma favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="assets/image/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/image/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/image/favicon/favicon-16x16.png">
        <link rel="manifest" href="asset/images/favicon/site.webmanifest">
        <link rel="mask-icon" href="assets/image/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <!-- Le cadre de travail Bootrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        
        <link rel="stylesheet" href="styles/app.css">
        
        <!-- Les familles de polices depuis Google -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Poppins&display=swap" rel="stylesheet">

        <!-- font awesome -->
        <?= isset($font_awesome) ? $font_awesome : ""; ?>

    </head>
    <body class="bg-light">