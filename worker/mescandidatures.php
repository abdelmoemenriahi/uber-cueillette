<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'worker') {
    header('Location: ../login.php'); exit;
}
require_once '../config.php';
$id = $_SESSION['user_id'];
$filtDecision = $_GET['decision'] ?? 'all';

// Stats
$stmt = $pdo->prepare("SELECT decision, COUNT(*) as n FROM candidature 
WHERE id_ouvrier=? GROUP BY decision");
$stmt->execute([$id]);
$stats = ['encours'=>0,'accepte'=>0,'refuse'=>0];
foreach ($stmt->fetchAll() as $row) $stats[$row['decision']] = $row['n'];

$sql = "SELECT c.*, tf.libelle AS fruit, g.libelle AS gouvernorat, o.prix_journee, o.date_debut, o.date_fin
    FROM candidature c JOIN offre o ON c.id_offre=o.id_offre
    JOIN type_fruit tf ON o.id_type_fruit=tf.id_type_fruit
    JOIN gouvernorat g ON o.id_gouvernorat=g.id_gouvernorat
    WHERE c.id_ouvrier=?";
$params = [$id];
if ($filtDecision !== 'all') {
$sql .= " AND c.decision=?"; $params[] = $filtDecision;
 }
$sql .= " ORDER BY c.date_candidature DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$candidatures = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes candidatures - Ouvrier</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/workercandida.css">
</head>
<body>
<nav class="navbar">
    <div class="navcont">
        <div class="logotext"><a href="../index.php">Uber<span>Cueillette</span></a></div>
        <div class="navlien">
            <span class="welcome-text">Bienvenue</span>
            <a href="../logout.php" class="btn-logout">Déconnexion</a>
        </div>
    </div>
</nav>
<div class="dashboard">
    <div class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="workerdashboard.php">Accueil</a></li>
            <li><a href="offres.php">Offres disponibles</a></li>
            <li><a href="mescandidatures.php" class="active">Mes candidatures</a></li>
            <li><a href="historique.php">Historique</a></li>
            <li><a href="profil.php">Mon profil</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Mes candidatures</h1>
        <div class="stats-banner">
            <div class="stat-item">
                <span class="stat-label">En attente</span>
                <span class="stat-value"><?= $stats['encours'] ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Acceptées</span>
                <span class="stat-value"><?= $stats['accepte'] ?></span>
            </div>
            <div class="stat-item"><span class="stat-label">Refusées</span>
            <span class="stat-value"><?= $stats['refuse'] ?></span>
        </div>
        </div>
        <div class="filter-bar">
            <form method="GET" action="mescandidatures.php" >
                <div class="filter-group">
                    <label>Filtrer par décision :</label>
                    <select name="decision" onchange="this.form.submit()">
                        <option value="all" <?= $filtDecision==='all'?'selected':'' ?>>Toutes</option>
                        <option value="encours" <?= $filtDecision==='encours'?'selected':'' ?>>En attente</option>
                        <option value="accepte" <?= $filtDecision==='accepte'?'selected':'' ?>>Acceptées</option>
                        <option value="refuse" <?= $filtDecision==='refuse'?'selected':'' ?>>Refusées</option>
                    </select>
                </div>
                <a href="mescandidatures.php" class="filter-reset">Réinitialiser</a>
            </form>
        </div>
        <div class="candidatures-list">
        <?php if (empty($candidatures)): ?>
            <p>Aucune candidature trouvée.</p>
        <?php endif; ?>
        <?php foreach ($candidatures as $c): ?>
            <?php
                $cls = $c['decision']==='accepte'?'accepted':($c['decision']==='refuse'?'refused':'');
                $b = $c['decision']==='accepte'?['Acceptée','badge-success']:($c['decision']==='refuse'?['Refusée','badge-danger']:['En attente','badge-warning']);
            ?>
            <div class="candidature-card <?= $cls ?>">
                <div class="candidature-header">
                    <div class="offre-info-header">
                        <h3>Récolte de </h3>
                        <span class="offre-lieu"></span>
                    </div>
                    <span class="badge <?= $b[1] ?>"><?= $b[0] ?></span>
                </div>
                <div class="candidature-details">
                    <div class="detail-row"><span class="detail-label">Date de candidature :</span><span class="detail-value"><?= date('d/m/Y', strtotime($c['date_candidature'])) ?></span></div>
                    <div class="detail-row"><span class="detail-label">Période :</span><span class="detail-value"><?= date('d/m/Y', strtotime($c['date_debut'])) ?> - <?= date('d/m/Y', strtotime($c['date_fin'])) ?></span></div>
                    <div class="detail-row"><span class="detail-label">Prix à la journée :</span><span class="detail-value"><?= $c['prix_journee'] ?> DT</span></div>
                    <div class="detail-row"><span class="detail-label">Décision :</span>
                        <span class="detail-value <?= $c['decision']==='accepte'?'decision-success':($c['decision']==='refuse'?'decision-danger':'decision-warning') ?>">
                            <?= $c['decision']==='encours'?'En attente de réponse':$b[0] ?>
                        </span>
                    </div>
                    <?php if ($c['commentaire']): ?>
                    <div class="detail-row"><span class="detail-label">Commentaire :</span><span class="detail-value"><?= htmlspecialchars($c['commentaire']) ?></span></div>
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
    <div class="footcontact"><p>Email: contact@example.com | Tél: +216 XX XXX XXX</p></div>
</footer>
</body>
</html>
