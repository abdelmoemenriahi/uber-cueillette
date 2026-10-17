<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'farmer') {
    header('Location: ../login.php'); exit;
}
require_once '../config.php';
$id = $_SESSION['user_id'];

if (isset($_GET['action']) && isset($_GET['cid'])) {
    $cid    = intval($_GET['cid']);
    $action = $_GET['action'];
    if ($action === 'accepter') {
        // Vérifier quota
        $stmt = $pdo->prepare("SELECT c.id_offre, o.nombre_ouvriers,
            (SELECT COUNT(*) FROM candidature 
            WHERE id_offre=c.id_offre AND decision='accepte') as nb_acc
            FROM candidature c JOIN offre o ON c.id_offre=o.id_offre 
            WHERE c.id_candidature=?");
        $stmt->execute([$cid]);
        $row = $stmt->fetch();
        if ($row && $row['nb_acc'] < $row['nombre_ouvriers']) {
            $stmt = $pdo->prepare("UPDATE candidature SET decision='accepte' 
            WHERE id_candidature=?");
            $stmt->execute([$cid]);
        }
    } elseif ($action === 'refuser') {
        $stmt = $pdo->prepare("UPDATE candidature SET decision='refuse'
         WHERE id_candidature=?");
        $stmt->execute([$cid]);
    }
    header('Location: candidatures.php'); exit;
}

// Filtre par offre
$filtreOffre = isset($_GET['offre']) ? intval($_GET['offre']) : 0;
$filtreDecision = $_GET['decision'] ?? 'all';

$sql = "SELECT c.*, o.prenom AS onom, o.nom AS oprenom, o.CIN, o.email, o.description,
    (SELECT ROUND(AVG(note),1) FROM candidature WHERE id_ouvrier=c.id_ouvrier AND note IS NOT NULL) AS note_moy,
    tf.libelle AS fruit, g.libelle AS gouvernorat, off.date_debut, off.date_fin, off.id_offre
    FROM candidature c
    JOIN ouvrier o ON c.id_ouvrier = o.id_ouvrier
    JOIN offre off ON c.id_offre = off.id_offre
    JOIN type_fruit tf ON off.id_type_fruit = tf.id_type_fruit
    JOIN gouvernorat g ON off.id_gouvernorat = g.id_gouvernorat
    WHERE off.id_agriculteur = ?";


$params = [$id];
if ($filtreOffre > 0) {
     $sql .= " AND off.id_offre = ?";
      $params[] = $filtreOffre; 
     }


if ($filtreDecision !== 'all') {
     $sql .= " AND c.decision = ?"; 
     $params[] = $filtreDecision; 
     }

$sql .= " ORDER BY c.date_candidature DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$candidatures = $stmt->fetchAll();

// Stats globales
$stmt = $pdo->prepare("SELECT c.decision, COUNT(*) as n 
FROM candidature c 
JOIN offre o ON c.id_offre=o.id_offre 
WHERE o.id_agriculteur=? 
GROUP BY c.decision");

$stmt->execute([$id]);
$stats = ['encours'=>0,'accepte'=>0,'refuse'=>0];
foreach ($stmt->fetchAll() as $row) $stats[$row['decision']] = $row['n'];

// Mes offres pour filtre
$stmt = $pdo->prepare("SELECT o.id_offre, tf.libelle AS fruit, g.libelle AS gouvernorat 
FROM offre o 
JOIN type_fruit tf ON o.id_type_fruit=tf.id_type_fruit
 JOIN gouvernorat g ON o.id_gouvernorat=g.id_gouvernorat 
 WHERE o.id_agriculteur=? 
 ORDER BY o.id_offre DESC");

 
$stmt->execute([$id]);
$mesOffres = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatures - Agriculteur</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/candida.css">
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
            <li><a href="candidatures.php" class="active">Candidatures</a></li>
            <li><a href="profilmodif.php">Modifier profil</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Candidatures reçues</h1>
        <div class="stats-banner">
            <div class="stat-item">
                <span class="stat-label">En attente</span>
                <span class="stat-value"><?= $stats['encours'] ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Acceptées</span>
                <span class="stat-value"><?= $stats['accepte'] ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Refusées</span>
                <span class="stat-value"><?= $stats['refuse'] ?></span>
            </div>
        </div>
        <div class="filter-bar">
            <form method="GET" action="candidatures.php" style="display:flex;gap:16px;flex-wrap:wrap;align-items:center;">
                <div class="filter-group">
                    <label>Offre :</label>
                    <select name="offre" onchange="this.form.submit()">
                        <option value="0">Toutes les offres</option>
                        <?php foreach ($mesOffres as $mo): ?>
                        <option value="<?= $mo['id_offre'] ?>" <?= $filtreOffre==$mo['id_offre']?'selected':'' ?>><?= htmlspecialchars($mo['fruit'].' - '.$mo['gouvernorat']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Décision :</label>
                    <select name="decision" onchange="this.form.submit()">
                        <option value="all" <?= $filtreDecision==='all'?'selected':'' ?>>Toutes</option>
                        <option value="encours" <?= $filtreDecision==='encours'?'selected':'' ?>>En attente</option>
                        <option value="accepte" <?= $filtreDecision==='accepte'?'selected':'' ?>>Acceptées</option>
                        <option value="refuse" <?= $filtreDecision==='refuse'?'selected':'' ?>>Refusées</option>
                    </select>
                </div>
                <a href="candidatures.php" class="filter-reset">Réinitialiser</a>
            </form>
        </div>
        <div class="candidatures-list">
        <?php if (empty($candidatures)): ?>
            <p >Aucune candidature trouvée.</p>
        <?php endif; ?>
        <?php foreach ($candidatures as $c): ?>
            <?php
                $cls = $c['decision']==='accepte'?'accepted':($c['decision']==='refuse'?'refused':'');
                $badge = $c['decision']==='accepte'?['Acceptée','badge-success']:($c['decision']==='refuse'?['Refusée','badge-danger']:['En attente','badge-warning']);
            ?>
            <div class="candidature-card <?= $cls ?>">
                <div class="candidature-header">
                    <div class="candidat-info">
                        <div class="candidat-avatar"><div class="avatar-initials"><?= strtoupper(substr($c['onom'],0,1).substr($c['oprenom'],0,1)) ?></div></div>
                        <div class="candidat-details">
                            <h3><?= htmlspecialchars($c['onom'].' '.$c['oprenom']) ?></h3>
                            <span class="candidat-note">Note moyenne: ★ <?= $c['note_moy'] ?? 'N/A' ?>/5</span>
                        </div>
                    </div>
                    <div class="candidature-offre">
                        <span class="offre-badge"><?= htmlspecialchars($c['fruit'].' - '.$c['gouvernorat']) ?></span>
                        <span class="badge <?= $badge[1] ?>"><?= $badge[0] ?></span>
                    </div>
                </div>
                <div class="candidature-body">
                    <div class="candidat-infos">
                        <div class="info-line">
                            <span class="info-label">CIN :</span>
                            <span><?= htmlspecialchars($c['CIN']) ?></span>
                        </div>
                        <div class="info-line">
                            <span class="info-label">Email :</span><span>
                                <?= htmlspecialchars($c['email']) ?></span>
                            </div>
                        <div class="info-line">
                            <span class="info-label">Description :</span>
                            <span><?= htmlspecialchars(substr($c['description'],0,80)).'...' ?>
                        </span>
                    </div>
                        <div class="info-line">
                            <span class="info-label">Candidature :</span>
                            <span><?= date('d/m/Y', strtotime($c['date_candidature'])) ?></span>
                        </div>
                    </div>
                    <div class="candidature-actions">
                        <?php if ($c['decision'] === 'encours'): ?>
                            <a href="candidatures.php?action=accepter&cid=<?= $c['id_candidature'] ?>" class="btn-accept" onclick="return confirm('Accepter cette candidature ?')">Accepter</a>
                            <a href="candidatures.php?action=refuser&cid=<?= $c['id_candidature'] ?>" class="btn-refuse" onclick="return confirm('Refuser cette candidature ?')">Refuser</a>
                        <?php else: ?>
                            <span class="decision-text">Candidature <?= $badge[0] ?> le <?= date('d/m/Y', strtotime($c['date_candidature'])) ?></span>
                        <?php endif; ?>
                    </div>
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
