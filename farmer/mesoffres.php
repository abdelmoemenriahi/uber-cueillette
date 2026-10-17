<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'farmer') {
    header('Location: ../login.php'); exit;
}
require_once '../config.php';
$id = $_SESSION['user_id'];

// Suppression
if (isset($_GET['supprimer'])) {
    $id_offre = intval($_GET['supprimer']);
    // Vérifier qu'aucun postulant
    $stmt = $pdo->prepare("SELECT COUNT(*) as total 
    FROM candidature WHERE id_offre = ?");
    $stmt->execute([$id_offre]);
    if ($stmt->fetch()['total'] == 0) {
        $stmt = $pdo->prepare("DELETE FROM offre 
        WHERE id_offre = ? AND id_agriculteur = ?");
        $stmt->execute([$id_offre, $id]);
        $success = 'Offre supprimée avec succès.';
    } else {
        $erreur = 'Impossible de supprimer : des candidats ont postulé.';
    }
}

$stmt = $pdo->prepare("SELECT o.*, tf.libelle AS fruit, g.libelle AS gouvernorat,
    (SELECT COUNT(*) FROM candidature c 
    WHERE c.id_offre = o.id_offre AND c.decision='accepte') AS nb_acceptes,
    (SELECT COUNT(*) FROM candidature c 
    WHERE c.id_offre = o.id_offre) AS nb_total_candidats
    FROM offre o
    JOIN type_fruit tf ON o.id_type_fruit = tf.id_type_fruit
    JOIN gouvernorat g ON o.id_gouvernorat = g.id_gouvernorat
    WHERE o.id_agriculteur = ?
    ORDER BY o.id_offre DESC");
$stmt->execute([$id]);
$offres = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes offres - Agriculteur</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/farmerdashboard.css">
    <link rel="stylesheet" href="../css/offres.css">
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
        <h1>Mes offres</h1>
        <?php if (!empty($success)): ?>
            <div class="success-msg">
                <?= htmlspecialchars($success) ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($erreur)): ?>
            <div class="error-msg">
                <?= htmlspecialchars($erreur) ?>
            </div>
            <?php endif; ?>
        <div class="offres-list">
        <?php if (empty($offres)): ?>
            <div class="empty-msg">Vous n'avez pas encore publié d'offres. <a href="ajouteroffre.php">Ajouter une offre</a></div>
        <?php endif; ?>
        <?php foreach ($offres as $o): ?>
            <?php
                $today = date('Y-m-d');
                if ($o['date_limite'] < $today) $statut = ['Clôturée','badge-danger'];
                elseif ($o['nb_acceptes'] >= $o['nombre_ouvriers']) $statut = ['Complète','badge-danger'];
                elseif ($o['nb_acceptes'] > 0) $statut = ['Active','badge-success'];
                else $statut = ['Nouvelle','badge-warning'];
            ?>
            <div class="offre-card">
                <div class="offre-main">
                    <h3><?= htmlspecialchars($o['fruit']) ?> - <?= htmlspecialchars($o['gouvernorat']) ?></h3>
                    <div class="offre-meta">
                        <span>📍 <?= htmlspecialchars($o['adresse']) ?></span>
                        <span>📅 <?= date('d/m/Y', strtotime($o['date_debut'])) ?> → <?= date('d/m/Y', strtotime($o['date_fin'])) ?></span>
                        <span>👥 <?= $o['nb_acceptes'] ?>/<?= $o['nombre_ouvriers'] ?> ouvriers</span>
                        <span>⏳ Limite: <?= date('d/m/Y', strtotime($o['date_limite'])) ?></span>
                        <span>👤 <?= $o['nb_total_candidats'] ?> candidat(s)</span>
                    </div>
                </div>
                <div class="offre-actions">
                    <div class="offre-prix"><?= $o['prix_journee'] ?> DT/jour</div>
                    <span class="badge <?= $statut[1] ?>"><?= $statut[0] ?></span>
                    <a href="candidatures.php?offre=<?= $o['id_offre'] ?>" class="btn-voir">Voir candidatures</a>
                    <?php if ($o['nb_total_candidats'] == 0): ?>
                    <a href="mesoffres.php?supprimer=<?= $o['id_offre'] ?>" class="btn-supprimer" onclick="return confirm('Supprimer cette offre ?')">Supprimer</a>
                    <?php endif; ?>
                    <?php if ($o['date_fin'] < $today && $o['nb_acceptes'] > 0): ?>
                    <a href="evaluer.php?offre=<?= $o['id_offre'] ?>" class="btn-voir" style="background:#fff3e0;color:#e67e22;">Évaluer ouvriers</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
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
