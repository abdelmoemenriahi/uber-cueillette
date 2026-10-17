<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'farmer') {
    header('Location: ../login.php'); exit;
}
require_once '../config.php';
$id = $_SESSION['user_id'];
$success = ''; $erreur = '';

$stmt = $pdo->prepare("SELECT * FROM agriculteur WHERE id_agriculteur=?");
$stmt->execute([$id]);
$farmer = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = trim($_POST['nom'] ?? '');
    $prenom  = trim($_POST['prenom'] ?? '');
    $cin     = trim($_POST['cin'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $pseudo  = trim($_POST['pseudo'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE agriculteur 
        SET nom=?, prenom=?, CIN=?, email=?, adresse=?, pseudo=?, password=? 
        WHERE id_agriculteur=?");
        $stmt->execute([$nom, $prenom, $cin, $email, $adresse, $pseudo, $hash, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE agriculteur 
        SET nom=?, prenom=?, CIN=?, email=?, adresse=?, pseudo=? 
        WHERE id_agriculteur=?");
        $stmt->execute([$nom, $prenom, $cin, $email, $adresse, $pseudo, $id]);
    }
    $_SESSION['nom'] = $prenom;
    $success = 'Profil mis à jour avec succès !';
    $stmt = $pdo->prepare("SELECT * FROM agriculteur WHERE id_agriculteur=?");
    $stmt->execute([$id]);
    $farmer = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier profil - Agriculteur</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/farmerdashboard.css">
    <link rel="stylesheet" href="../css/profilmodif.css">
</head>
<body>
<nav class="navbar">
    <div class="navcont">
        <div class="logotext"><a href="../index.php">Uber<span>Cueillette</span></a></div>
        <div class="navlien">
            <span class="welcome-text">Bienvenue, <?= htmlspecialchars($_SESSION['nom']) ?></span>
            <a href="../logout.php" class="btn-logout">Déconnexion</a>
        </div>
    </div>
</nav>
<div class="dashboard">
    <div class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="farmerdashboard.php">Accueil</a></li>
            <li><a href="ajouteroffre.php">Ajouter une offre</a></li>
            <li><a href="mesoffres.php">Mes offres</a></li>
            <li><a href="candidatures.php">Candidatures</a></li>
            <li><a href="profilmodif.php" class="active">Modifier profil</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Modifier mon profil</h1>
        <?php if ($success): ?><div class="success-msg"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <div class="profile-formcont">
            <form method="POST" action="profilmodif.php" onsubmit="return validateProfileForm()">
                <div class="form-row">
                    <div class="form-grp">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($farmer['nom']) ?>" required>
                        <div id="errnom" class="Errmsg"></div>
                    </div>
                    <div class="form-grp">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($farmer['prenom']) ?>" required>
                        <div id="errprenom" class="Errmsg"></div>
                    </div>
                </div>
                <div class="form-grp">
                    <label for="cin">CIN</label>
                    <input type="text" id="cin" name="cin" value="<?= htmlspecialchars($farmer['cin']) ?>" maxlength="8" required>
                    <div id="errcin" class="Errmsg"></div>
                </div>
                <div class="form-grp">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($farmer['email']) ?>" required>
                    <div id="erremail" class="Errmsg"></div>
                </div>
                <div class="form-grp">
                    <label for="adresse">Adresse</label>
                    <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($farmer['adresse']) ?>" required>
                    <div id="erradresse" class="Errmsg"></div>
                </div>
                <div class="form-grp">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($farmer['pseudo']) ?>" required>
                    <div id="errpseudo" class="Errmsg"></div>
                </div>
                <div class="form-row">
                    <div class="form-grp">
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="Laisser vide si inchangé">
                        <small>Au moins 8 caractères et se terminant par $ ou #</small>
                        <div id="errpass" class="Errmsg"></div>
                    </div>
                    <div class="form-grp">
                        <label for="confirmpass">Confirmer le mot de passe</label>
                        <input type="password" id="confirmpass" name="confirmpass" placeholder="Confirmer">
                        <div id="errconfirm" class="Errmsg"></div>
                    </div>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="btn-save">Enregistrer</button>
                    <button type="button" class="btn-cancel" onclick="annulerModifications()">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
<footer id="contact">
    <p>&copy; 2026 Uber-Cueillette - Tous droits réservés</p>
    <p>Institut Supérieur de Gestion de Tunis - Projet Web2</p>
    <div class="footcontact"><p>Email: contact@example.com | Tél: +216 XX XXX XXX</p></div>
</footer>
<script src="../javascript/script.js"></script>
</body>
</html>
