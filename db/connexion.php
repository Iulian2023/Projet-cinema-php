<?php
/* Connexion à une base MySQL avec l'invocation de pilote */
    $dsn_db = 'mysql:dbname=projet_cinema;host=127.0.0.1;port=3306';
    $user_db = 'root';
    $password_db = '';

    try {
        $db = new PDO($dsn_db, $user_db, $password_db);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
        die("error de conexion à la base de donnes : " . $e->getMessage());
    }
?>