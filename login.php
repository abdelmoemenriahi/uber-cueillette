<?php
session_start();
require_once 'config.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo'] ?? '');
    $password = $_POST['password'] ?? '';
    $userType = $_POST['user-type'] ?? '';

    if ($userType === 'farmer') {
        $stmt = $pdo->prepare("SELECT * FROM agriculteur WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_agriculteur'];
            $_SESSION['user_type'] = 'farmer';
            $_SESSION['pseudo'] = $user['pseudo'];
            $_SESSION['nom'] = $user['prenom'];
            header('Location: farmer/farmerdashboard.php');
            exit;
        } else {
            $erreur = 'Pseudo ou mot de passe incorrect.';
        }
    } elseif ($userType === 'worker') {
        $stmt = $pdo->prepare("SELECT * FROM ouvrier WHERE pseudo = ?");
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_ouvrier'];
            $_SESSION['user_type'] = 'worker';
            $_SESSION['pseudo'] = $user['pseudo'];
            $_SESSION['nom'] = $user['prenom'];
            header('Location: worker/workerdashboard.php');
            exit;
        } else {
            $erreur = 'Pseudo ou mot de passe incorrect.';
        }
    } else {
        $erreur = 'Veuillez sélectionner votre profil.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Uber-Cueillette</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<nav class="navbar">
    <div class="navcont">
        <div class="logotext"><a href="index.php">Uber<span>Cueillette</span></a></div>
        <div class="navlien">
            <a href="index.php">Accueil</a>
            <a href="index.php#features">Fonctionnalités</a>
            <a href="index.php#a-propos">À propos</a>
            <a href="index.php#contact">Contact</a>
        </div>
    </div>
</nav>

<div class="formcont">
    <h2>Connexion</h2>

    <?php if ($erreur): ?>
        <div class="Errmsg" style="margin-bottom:10px; text-align:center;"><?php echo $erreur; ?></div>
    <?php endif; ?>

    <form id="loginForm" method="POST" action="login.php" onsubmit="return validateLogin()">
        <div class="form-grp">
            <label for="pseudo">Pseudo</label>
            <input type="text" id="pseudo" name="pseudo" placeholder="Votre pseudo" required>
            <div id="errpseudo" class="Errmsg"></div>
        </div>
        <div class="form-grp">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
            <small>Le mot de passe doit contenir au moins 8 caractères et se terminer par $ ou #</small>
            <div id="errpass" class="Errmsg"></div>
        </div>
        <div class="form-grp">
            <label for="user-type">Vous êtes</label>
            <select id="user-type" name="user-type" required>
                <option value="">Sélectionnez votre profil</option>
                <option value="farmer">Agriculteur</option>
                <option value="worker">Ouvrier</option>
            </select>
            <div id="errtype" class="Errmsg"></div>
        </div>
        <button type="submit" class="btn1">Se connecter</button>
    </form>
    <p class="signin">
        Pas encore de compte ?<br>
        <a href="registerfarmer.php" class="btn2">Je suis agriculteur</a>
        <a href="registerworker.php" class="btn2">Je suis ouvrier</a>
    </p>
</div>

<footer id="contact">
    <p>&copy; 2026 Uber-Cueillette - Tous droits réservés</p>
    <p>Institut Supérieur de Gestion de Tunis - Projet Web2</p>
    <div class="footcontact">
        <p>Email: contact@example.com | Tél: +216 XX XXX XXX</p></div>
</footer>
<script src="javascript/script.js"></script>
</body>
</html>
