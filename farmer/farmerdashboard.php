<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'farmer') {
    header('Location: ../login.php'); exit;
}
require_once '../config.php';

$id = $_SESSION['user_id'];

// Stats
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM offre
 WHERE id_agriculteur = ? AND date_limite >= CURDATE()");
$stmt->execute([$id]); $offresActives = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM candidature c 
JOIN offre o ON c.id_offre = o.id_offre 
WHERE o.id_agriculteur = ? AND c.decision = 'encours'");
$stmt->execute([$id]); $candidaturesAttente = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total 
FROM candidature c 
JOIN offre o ON c.id_offre = o.id_offre 
WHERE o.id_agriculteur = ? AND c.decision = 'accepte'");
$stmt->execute([$id]); $ouvriersRecrutes = $stmt->fetch()['total'];

$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM offre 
WHERE id_agriculteur = ? AND date_fin < CURDATE()");
$stmt->execute([$id]); $chantiersTermines = $stmt->fetch()['total'];

// Offres récentes
$stmt = $pdo->prepare("SELECT o.*, tf.libelle AS fruit, g.libelle AS gouvernorat,
    (SELECT COUNT(*) FROM candidature c WHERE c.id_offre = o.id_offre AND c.decision='accepte') AS nb_acceptes
    FROM offre o
    JOIN type_fruit tf ON o.id_type_fruit = tf.id_type_fruit
    JOIN gouvernorat g ON o.id_gouvernorat = g.id_gouvernorat
    WHERE o.id_agriculteur = ?
    ORDER BY o.id_offre DESC LIMIT 5");
$stmt->execute([$id]); $offres = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Agriculteur</title>
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
            <li><a href="farmerdashboard.php" class="active">Accueil</a></li>
            <li><a href="ajouteroffre.php">Ajouter une offre</a></li>
            <li><a href="mesoffres.php">Mes offres</a></li>
            <li><a href="candidatures.php">Candidatures</a></li>
            <li><a href="profilmodif.php">Modifier profil</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Tableau de bord Agriculteur</h1>
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Offres actives</h3>
                <div class="stat-number">
                    <?= $offresActives ?></div>
                </div>
            <div class="stat-card">
                <h3>Candidatures en attente</h3>
                <div class="stat-number"><?= $candidaturesAttente ?>
            </div>
        </div>
            <div class="stat-card">
                <h3>Ouvriers recrutés</h3>
                <div class="stat-number">
                    <?= $ouvriersRecrutes ?>
                </div>
                </div>
            <div class="stat-card"><h3>Chantiers terminés</h3>
            <div class="stat-number"><?= $chantiersTermines ?>
        </div>
    </div>
        </div>
        <div class="table-container">
            <h2>Offres récentes</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fruit</th>
                        <th>Lieu</th>
                        <th>Période</th>
                        <th>Prix/jour</th>
                        <th>Ouvriers</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($offres as $o): ?>
                    <?php
                        $today = date('Y-m-d');
                        if ($o['date_limite'] < $today) $statut = ['Clôturée','badge-danger'];
                        elseif ($o['nb_acceptes'] >= $o['nombre_ouvriers']) $statut = ['Complète','badge-danger'];
                        elseif ($o['nb_acceptes'] > 0) $statut = ['Active','badge-success'];
                        else $statut = ['Nouvelle','badge-warning'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($o['fruit']) ?></td>
                        <td><?= htmlspecialchars($o['gouvernorat']) ?></td>
                        <td><?= date('d/m', strtotime($o['date_debut'])) ?> - <?= date('d/m', strtotime($o['date_fin'])) ?></td>
                        <td><?= $o['prix_journee'] ?> DT</td>
                        <td><?= $o['nb_acceptes'] ?>/<?= $o['nombre_ouvriers'] ?></td>
                        <td><span class="badge <?= $statut[1] ?>"><?= $statut[0] ?></span></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($offres)): ?>
                    <tr><td colspan="6" style="text-align:center;color:#999;">Aucune offre publiée</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
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
