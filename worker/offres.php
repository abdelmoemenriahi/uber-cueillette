<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'worker') {
    header('Location: ../login.php'); exit;
}
require_once '../config.php';
$id = $_SESSION['user_id'];
$success = '';

if (isset($_GET['postuler'])) {
    $id_offre = intval($_GET['postuler']);
    // Vérifier pas déjà postulé
    $stmt = $pdo->prepare("SELECT id_candidature FROM candidature 
    WHERE id_offre=? AND id_ouvrier=?");
    $stmt->execute([$id_offre, $id]);
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO candidature (id_offre, id_ouvrier, decision) 
        VALUES (?, ?, 'encours')");
        $stmt->execute([$id_offre, $id]);
        $success = 'Candidature envoyée avec succès !';
    }
}

$filtFruit = intval($_GET['fruit'] ?? 0);
$filtGouv  = intval($_GET['gouv'] ?? 0);
$filtPrix  = $_GET['prix'] ?? '';

$sql = "SELECT o.*, tf.libelle AS fruit, g.libelle AS gouvernorat,
    (SELECT COUNT(*) FROM candidature 
    WHERE id_offre=o.id_offre AND decision='accepte') AS nb_acceptes,
    (SELECT COUNT(*) FROM candidature WHERE id_offre=o.id_offre AND id_ouvrier=?) AS deja_postule
    FROM offre o
    JOIN type_fruit tf ON o.id_type_fruit=tf.id_type_fruit
    JOIN gouvernorat g ON o.id_gouvernorat=g.id_gouvernorat
    WHERE o.date_limite >= CURDATE()";
    
$params = [$id];
if ($filtFruit > 0) { $sql .= " AND o.id_type_fruit=?"; $params[] = $filtFruit; }
if ($filtGouv  > 0) { $sql .= " AND o.id_gouvernorat=?"; $params[] = $filtGouv; }
if ($filtPrix === 'asc') $sql .= " ORDER BY o.prix_journee ASC";
elseif ($filtPrix === 'desc') $sql .= " ORDER BY o.prix_journee DESC";
else $sql .= " ORDER BY o.id_offre DESC";

$stmt = $pdo->prepare($sql); $stmt->execute($params);
$offres = $stmt->fetchAll();

$fruits = $pdo->query("SELECT * FROM type_fruit ORDER BY libelle")->fetchAll();
$gouvernorats = $pdo->query("SELECT * FROM gouvernorat ORDER BY libelle")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres disponibles - Ouvrier</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/offres.css">
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
            <li><a href="workerdashboard.php">Accueil</a></li>
            <li><a href="offres.php" class="active">Offres disponibles</a></li>
            <li><a href="mescandidatures.php">Mes candidatures</a></li>
            <li><a href="historique.php">Historique</a></li>
            <li><a href="profil.php">Mon profil</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Offres disponibles</h1>
        <?php if ($success): ?><div ><?= $success ?></div><?php endif; ?>
        <form method="GET" action="offres.php" class="offres-filters">
            <div class="filter-grp">
                <label>Type de fruit</label>
                <select name="fruit">
                    <option value="0">Tous les fruits</option>
                    <?php foreach ($fruits as $f): ?>
                    <option value="<?= $f['id_type_fruit'] ?>" <?= $filtFruit==$f['id_type_fruit']?'selected':'' ?>><?= htmlspecialchars($f['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-grp">
                <label>Gouvernorat</label>
                <select name="gouv">
                    <option value="0">Tous les gouvernorats</option>
                    <?php foreach ($gouvernorats as $g): ?>
                    <option value="<?= $g['id_gouvernorat'] ?>" <?= $filtGouv==$g['id_gouvernorat']?'selected':'' ?>><?= htmlspecialchars($g['libelle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-grp">
                <label>Trier par prix</label>
                <select name="prix">
                    <option value="">Par défaut</option>
                    <option value="asc" <?= $filtPrix==='asc'?'selected':'' ?>>Prix croissant</option>
                    <option value="desc" <?= $filtPrix==='desc'?'selected':'' ?>>Prix décroissant</option>
                </select>
            </div>
            <button type="submit" class="btn-filter">Filtrer</button>
            <a href="offres.php" class="btn-reset-filter">Réinitialiser</a>
        </form>
        <div class="offres-list">
        <?php if (empty($offres)): ?>
            <div class="empty-msg">Aucune offre disponible pour le moment.</div>
        <?php endif; ?>
        <?php foreach ($offres as $o): ?>
            <?php $pleine = $o['nb_acceptes'] >= $o['nombre_ouvriers']; ?>
            <div class="offre-card">
                <div class="offre-main">
                    <h3></h3>
                    <div class="offre-meta">
                        <span>📍></span>
                        <span>📅 <?= date('d/m/Y', strtotime($o['date_debut'])) ?> → <?= date('d/m/Y', strtotime($o['date_fin'])) ?></span>
                        <span>👥 <?= $o['nb_acceptes'] ?>/<?= $o['nombre_ouvriers'] ?> ouvriers</span>
                        <span>⏳ Limite: <?= date('d/m/Y', strtotime($o['date_limite'])) ?></span>
                    </div>
                </div>
                <div class="offre-actions">
                    <div class="offre-prix"><?= $o['prix_journee'] ?> DT/jour</div>
                    <?php if ($o['deja_postule']): ?>
                        <span class="badge badge-warning">Déjà postulé</span>
                    <?php elseif ($pleine): ?>
                        <span class="badge badge-danger">Offre complète</span>
                    <?php else: ?>
                        <a href="offres.php?postuler=<?= $o['id_offre'] ?>" class="btn-postuler" onclick="return confirm('Postuler à cette offre ?')">Postuler</a>
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
