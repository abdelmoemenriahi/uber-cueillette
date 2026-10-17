<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uber-Cueillette - Accueil</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
<nav class="navbar">
    <div class="navcont">
        <div class="logotext"><a href="index.php">Uber<span>Cueillette</span></a></div>
        <div class="navlien">
            <a href="index.php">Accueil</a>
            <a href="#features">Fonctionnalités</a>
            <a href="#a-propos">À propos</a>
            <a href="#contact">Contact</a>
            <a href="login.php" class="btn-login">Connexion</a>
            <a href="registerfarmer.php" class="btn-register">Je suis agriculteur</a>
            <a href="registerworker.php" class="btn-register">Je suis ouvrier</a>
        </div>
    </div>
</nav>

<section class="sect1">
    <div class="sect1cont">
        <h1>Connectez agriculteurs et ouvriers agricoles</h1>
        <p>La plateforme qui facilite les récoltes de fruits en Tunisie</p>
        <div class="sect1btns">
            <a href="registerfarmer.php" class="btn btnmain">Je propose une récolte</a>
            <a href="registerworker.php" class="btn btnsecond">Je cherche du travail</a>
        </div>
    </div>
</section>

<section id="features" class="features">
    <h2>Comment ça marche ?</h2>
    <div class="featuresgrid">
        <div class="featureC">
            <h3>Inscription gratuite</h3>
            <p>Créez votre compte en quelques minutes en tant qu'agriculteur ou ouvrier</p>
        </div>
        <div class="featureC"><h3>Publication d'offres</h3><p>Les agriculteurs publient leurs besoins pour la saison des récoltes</p></div>
        <div class="featureC">
            <h3>Recherche d'emploi</h3>
            <p>Les ouvriers trouvent les offres correspondant à leurs critères</p>
        </div>
        <div class="featureC">
            <h3>Mise en relation</h3>
            <p>Postulez et gérez vos candidatures en toute simplicité</p>
        </div>
    </div>
</section>

<section id="a-propos" class="sect2">
    <h2>Pourquoi choisir Uber-Cueillette ?</h2>
    <div class="featuresgrid">
        <div class="featureC">
            <h3>Système de notation</h3>
            <p>Évaluez la qualité du travail pour plus de transparence</p></div>
        <div class="featureC">
            <h3>Filtres avancés</h3>
            <p>Filtrez les offres par fruit, région ou prix</p>
    </div>
        <div class="featureC">
            <h3>Suivi des chantiers</h3>
            <p>Consultez votre historique et vos gains</p>
        </div>
        <div class="featureC">
            <h3>Sécurisé</h3>
            <p>Vos données sont protégées et vos paiements sécurisés</p>
        </div>
    </div>
</section>

<footer id="contact">
    <p>&copy; 2026 Uber-Cueillette - Tous droits réservés</p>
    <p>Institut Supérieur de Gestion de Tunis - Projet Web2</p>
    <div class="footcontact">
        <p>Email: contact@example.com | Tél: +216 XX XXX XXX</p>
    </div>
</footer>
</body>
</html>
