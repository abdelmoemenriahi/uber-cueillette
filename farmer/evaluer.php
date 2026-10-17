<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'farmer') {
    header('Location: ../login.php'); exit;
}
require_once '../config.php';
$id = $_SESSION['user_id'];
$id_offre = intval($_GET['offre'] ?? 0);
$success = '';

// Vérifier que l'offre appartient à cet agriculteur
$stmt = $pdo->prepare("SELECT o.*, tf.libelle AS fruit, g.libelle AS gouvernorat 
FROM offre o JOIN type_fruit tf ON o.id_type_fruit=tf.id_type_fruit
 JOIN gouvernorat g ON o.id_gouvernorat=g.id_gouvernorat 
 WHERE o.id_offre=? AND o.id_agriculteur=?");


$stmt->execute([$id_offre, $id]);
$offre = $stmt->fetch();
if (!$offre) { header('Location: mesoffres.php'); exit; }

// Enregistrer évaluations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['note'] as $cid => $note) {
        $commentaire  = trim($_POST['commentaire'][$cid] ?? '');
        $remuneration = floatval($_POST['remuneration'][$cid] ?? 0);
        $stmt = $pdo->prepare("UPDATE candidature SET note=?, commentaire=?, remuneration=? 
        WHERE id_candidature=? AND id_offre=?");
        $stmt->execute([$note, $commentaire, $remuneration, intval($cid), $id_offre]);
    }
    $success = 'Évaluations enregistrées avec succès !';
}

// Ouvriers acceptés
$stmt = $pdo->prepare("SELECT c.*, ov.prenom, ov.nom 
FROM candidature c 
JOIN ouvrier ov ON c.id_ouvrier=ov.id_ouvrier 
WHERE c.id_offre=? AND c.decision='accepte'");

$stmt->execute([$id_offre]);
$ouvriers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Évaluer les ouvriers</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/farmerdashboard.css">
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
            <li><a href="mesoffres.php" class="active">Mes offres</a></li>
            <li><a href="candidatures.php">Candidatures</a></li>
            <li><a href="profilmodif.php">Modifier profil</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Évaluer les ouvriers - <?= htmlspecialchars($offre['fruit'].' - '.$offre['gouvernorat']) ?></h1>
        <?php if ($success): ?>
            <div ><?= $success ?>
        </div>
        <?php endif; ?>
        <form method="POST" action="evaluer.php?offre=<?= $id_offre ?>">
        <?php foreach ($ouvriers as $ov): ?>
            <div class="eval-card">
                <h3><?= htmlspecialchars($ov['prenom'].' '.$ov['nom']) ?></h3>
                <div class="eval-row">
                    <div class="eval-grp">
                        <label>Note (0-10)</label>
                        <input type="number" name="note[<?= $ov['id_candidature'] ?>]" min="0" max="10" value="<?= $ov['note'] ?? '' ?>" required>
                    </div>
                    <div class="eval-grp">
                        <label>Rémunération (DT)</label>
                        <input type="number" name="remuneration[<?= $ov['id_candidature'] ?>]" min="0" step="0.5" value="<?= $ov['remuneration'] ?? '' ?>">
                    </div>
                    <div class="eval-grp">
                        <label>Commentaire</label>
                        <textarea name="commentaire[<?= $ov['id_candidature'] ?>]" rows="2"><?= htmlspecialchars($ov['commentaire'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($ouvriers)): ?>
            <p style="color:#999;text-align:center;padding:32px;">Aucun ouvrier accepté pour cette offre.</p>
        <?php else: ?>
            <button type="submit" class="btn-eval">Enregistrer les évaluations</button>
        <?php endif; ?>
        </form>
    </div>
</div>
<footer id="contact">
    <p>&copy; 2026 Uber-Cueillette - Tous droits réservés</p>
    <p>Institut Supérieur de Gestion de Tunis - Projet Web2</p>
    <div class="footcontact">
        <p>Email: contact@example.com | Tél: +216 XX XXX XXX</p>
    </div>
</footer>
</body>
</html>
