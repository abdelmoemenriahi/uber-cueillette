<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'worker') {
    header('Location: ../login.php'); exit;
}
require_once '../config.php';
$id = $_SESSION['user_id'];
$success = '';

$stmt = $pdo->prepare("SELECT * FROM ouvrier WHERE id_ouvrier=?");
$stmt->execute([$id]); $worker = $stmt->fetch();

$stmt = $pdo->prepare("SELECT ROUND(AVG(note),1) as moy, COUNT(*) as nb 
FROM candidature WHERE id_ouvrier=? AND decision='accepte'");
$stmt->execute([$id]); $stats = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom         = trim($_POST['nom'] ?? '');
    $prenom      = trim($_POST['prenom'] ?? '');
    $cin         = trim($_POST['cin'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $pseudo      = trim($_POST['pseudo'] ?? '');
    $password    = $_POST['password'] ?? '';

    // Photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoData = file_get_contents($_FILES['photo']['tmp_name']);
        $photoNom  = basename($_FILES['photo']['name']);
        $stmt = $pdo->prepare("UPDATE ouvrier SET photo=?, photo_nom=? WHERE id_ouvrier=?");
        $stmt->execute([$photoData, $photoNom, $id]);
    }

    if ($password !== '') {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE ouvrier SET nom=?, prenom=?, CIN=?, email=?, description=?, pseudo=?, password=? WHERE id_ouvrier=?");
        $stmt->execute([$nom, $prenom, $cin, $email, $description, $pseudo, $hash, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE ouvrier SET nom=?, prenom=?, CIN=?, email=?, description=?, pseudo=? WHERE id_ouvrier=?");
        $stmt->execute([$nom, $prenom, $cin, $email, $description, $pseudo, $id]);
    }
    $_SESSION['nom'] = $prenom;
    $success = 'Profil mis à jour avec succès !';
    $stmt = $pdo->prepare("SELECT * FROM ouvrier WHERE id_ouvrier=?");
    $stmt->execute([$id]); $worker = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil - Ouvrier</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/workerprofil.css">
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
            <li><a href="workerdashboard.php">Accueil</a></li>
            <li><a href="offres.php">Offres disponibles</a></li>
            <li><a href="mescandidatures.php">Mes candidatures</a></li>
            <li><a href="historique.php">Historique</a></li>
            <li><a href="profil.php" class="active">Mon profil</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Mon profil</h1>
        <?php if ($success): ?>
            <div class="success-msg"></div>
            <?php endif; ?>
        <div class="profile-container">
            <div class="profile-info">
                <div class="profile-avatar">
                    <?php if ($worker['photo']): ?>
                        <img src="../photo.php?id=<?= $id ?>" alt="Photo" class="avatar-img">
                    <?php else: ?>
                        <div class="avatar-placeholder"><?= strtoupper(substr($worker['prenom'],0,1).substr($worker['nom'],0,1)) ?></div>
                    <?php endif; ?>
                    <div class="profile-stats">
                        <div class="stat-badge">
                            <span class="stat-label">Note moyenne</span>
                            <span class="stat-value"><?= $stats['moy'] ?? 'N/A' ?> ★</span>
                        </div>
                        <div class="stat-badge">
                            <span class="stat-label">Chantiers</span>
                            <span class="stat-value"><?= $stats['nb'] ?>
                        </span>
                    </div>
                    </div>
                </div>
                <form id="profileForm" method="POST" action="profil.php" enctype="multipart/form-data" onsubmit="return validateWorkerProfileForm()">
                    <div class="form-row">
                        <div class="form-grp">
                            <label for="nom">Nom</label>
                            <input type="text" id="nom" name="nom" required>
                            <div id="errnom" class="Errmsg"></div>
                        </div>
                        <div class="form-grp">
                            <label for="prenom">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required>
                            <div id="errprenom" class="Errmsg"></div>
                        </div>
                    </div>
                    <div class="form-grp">
                        <label for="cin">CIN</label>
                        <input type="text" id="cin" name="cin"  maxlength="8" required>
                        <div id="errcin" class="Errmsg"></div>
                    </div>
                    <div class="form-grp">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                        <div id="erremail" class="Errmsg"></div>
                    </div>
                    <div class="form-grp">
                        <label for="photo">Changer la photo d'identité</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                        <div id="errphoto" class="Errmsg"></div>
                        <small>Laisser vide pour garder la photo actuelle</small>
                    </div>
                    <div class="form-grp">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" required></textarea>
                        <div id="errdescription" class="Errmsg"></div>
                    </div>
                    <div class="form-grp">
                        <label for="pseudo">Pseudo</label>
                        <input type="text" id="pseudo" name="pseudo" required>
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
                        <button type="submit" class="btn-save">Enregistrer les modifications</button>
                        <button type="button" class="btn-cancel" onclick="annulerModifications()">Annuler</button>
                    </div>
                </form>
            </div>
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
