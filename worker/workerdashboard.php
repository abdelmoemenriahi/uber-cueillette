<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'worker') {
    header('Location: ../login.php'); exit;
}
require_once '../config.php';
$id = $_SESSION['user_id'];

// Stats
$stmt = $pdo->prepare("SELECT COUNT(*) as t FROM candidature WHERE id_ouvrier=?");
$stmt->execute([$id]); $totalCandidatures = $stmt->fetch()['t'];
$stmt = $pdo->prepare("SELECT COUNT(*) as t FROM candidature
 WHERE id_ouvrier=? AND decision='encours'");
$stmt->execute([$id]); $enAttente = $stmt->fetch()['t'];
$stmt = $pdo->prepare("SELECT COUNT(*) as t FROM candidature 
WHERE id_ouvrier=? AND decision='accepte'");
$stmt->execute([$id]); $acceptees = $stmt->fetch()['t'];
$stmt = $pdo->prepare("SELECT COALESCE(SUM(remuneration),0) as total FROM candidature 
WHERE id_ouvrier=? AND remuneration IS NOT NULL");
$stmt->execute([$id]); $gains = $stmt->fetch()['total'];

// Offres recommandées
$stmt = $pdo->prepare("SELECT o.*, tf.libelle AS fruit, g.libelle AS gouvernorat 
FROM offre o 
JOIN type_fruit tf ON o.id_type_fruit=tf.id_type_fruit 
JOIN gouvernorat g ON o.id_gouvernorat=g.id_gouvernorat
 WHERE o.date_limite >= CURDATE() AND o.id_offre NOT IN 
 (SELECT id_offre FROM candidature WHERE id_ouvrier=?)
  ORDER BY RAND() LIMIT 3");

$stmt->execute([$id]); $offresReco = $stmt->fetchAll();

// Dernières candidatures
$stmt = $pdo->prepare("SELECT c.*, tf.libelle AS fruit, g.libelle AS gouvernorat 
FROM candidature c 
JOIN offre o ON c.id_offre=o.id_offre 
JOIN type_fruit tf ON o.id_type_fruit=tf.id_type_fruit 
JOIN gouvernorat g ON o.id_gouvernorat=g.id_gouvernorat
 WHERE c.id_ouvrier=? 
 ORDER BY c.date_candidature DESC LIMIT 4");


$stmt->execute([$id]); $derniersCandidatures = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Ouvrier</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/workerdashboard.css">
</head>
<body>
<nav class="navbar">
    <div class="navcont">
        <div class="logotext"><a href="../index.php">Uber<span>Cueillette</span></a></div>
        <div class="navlien">
            <span class="welcome-text">Bienvenue,</span>
            <a href="../logout.php" class="btn-logout">Déconnexion</a>
        </div>
    </div>
</nav>
<div class="dashboard">
    <div class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="workerdashboard.php" class="active">Accueil</a></li>
            <li><a href="offres.php">Offres disponibles</a></li>
            <li><a href="mescandidatures.php">Mes candidatures</a></li>
            <li><a href="historique.php">Historique</a></li>
            <li><a href="profil.php">Mon profil</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Tableau de bord Ouvrier</h1>
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Mes candidatures</h3>
                <div class="stat-number">
                    <?= $totalCandidatures ?>
                </div>
            </div>
            <div class="stat-card">
                <h3>En attente</h3>
                <div class="stat-number">
                    <?= $enAttente ?></div>
                </div>
            <div class="stat-card">
                <h3>Acceptées</h3>
                <div class="stat-number">
                    <?= $acceptees ?></div>
                </div>
            <div class="stat-card">
                <h3>Gains totaux</h3>
                <div class="stat-number">
                    <?= $gains ?> DT</div>
                </div>
        </div>
        <div class="section">
            <h2>Offres recommandées pour vous</h2>
            <div class="offres-grid">
            <?php foreach ($offresReco as $o): ?>
                <div class="offre-card-small">
                    <div class="offre-icon">🌿</div>
                    <h3><?= htmlspecialchars($o['fruit']) ?></h3>
                    <p class="lieu"><?= htmlspecialchars($o['gouvernorat']) ?></p>
                    <p class="prix"><?= $o['prix_journee'] ?> DT/jour</p>
                    <a href="offres.php" class="btn-postuler">Voir offres</a>
                </div>
            <?php endforeach; ?>
            <?php if (empty($offresReco)): ?><p style="color:#999;">Aucune offre disponible.</p><?php endif; ?>
            </div>
        </div>
        <div class="section">
            <h2>Mes dernières candidatures</h2>
            <div class="table-container">
                <table class="data-table">
                    <thead><tr><th>Offre</th><th>Date candidature</th><th>Statut</th></tr></thead>
                    <tbody>
                    <?php foreach ($derniersCandidatures as $c): ?>
                        <?php $b = $c['decision']==='accepte'?['Acceptée','badge-success']:($c['decision']==='refuse'?['Refusée','badge-danger']:['En attente','badge-warning']); ?>
                        <tr>
                            <td><?= htmlspecialchars($c['fruit'].' - '.$c['gouvernorat']) ?></td>
                            <td><?= date('d/m/Y', strtotime($c['date_candidature'])) ?></td>
                            <td><span class="badge <?= $b[1] ?>"><?= $b[0] ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($derniersCandidatures)): ?>
                        <tr><td colspan="3" style="text-align:center;color:#999;">Aucune candidature</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <a href="mescandidatures.php" class="voir-plus">Voir toutes mes candidatures →</a>
        </div>
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
