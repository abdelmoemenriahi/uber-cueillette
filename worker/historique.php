<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'worker') {
    header('Location: ../login.php');
    exit;
}
require_once '../config.php';

$id = $_SESSION['user_id'];
$filtAnnee = $_GET['annee'] ?? 'all';

// calcul des totaux (gains, nombre de chantiers)
$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(remuneration),0) as gains,
    COUNT(*) as nb
    FROM candidature
    WHERE id_ouvrier=? 
    AND decision='accepte' 
    AND remuneration IS NOT NULL
");

$stmt->execute([$id]);
$totaux = $stmt->fetch();

// Calcul de la note moyenne
$stmt = $pdo->prepare("
    SELECT ROUND(AVG(note),1) as moy
    FROM candidature
    WHERE id_ouvrier=? 
    AND note IS NOT NULL
");

$stmt->execute([$id]);
$noteMoy = $stmt->fetch()['moy'] ?? 'N/A';

// liste des chantiers terminés
$sql = "
    SELECT 
        c.*, 
        tf.libelle AS fruit, 
        g.libelle AS gouvernorat,
        o.prix_journee, 
        o.date_debut, 
        o.date_fin,
        DATEDIFF(o.date_fin, o.date_debut)+1 AS nb_jours
    FROM candidature c
    JOIN offre o ON c.id_offre=o.id_offre
    JOIN type_fruit tf ON o.id_type_fruit=tf.id_type_fruit
    JOIN gouvernorat g ON o.id_gouvernorat=g.id_gouvernorat
    WHERE c.id_ouvrier=? 
    AND c.decision='accepte' 
    AND o.date_fin < CURDATE()
";

$params = [$id];

if ($filtAnnee !== 'all') {
    $sql .= " AND YEAR(o.date_fin)=?";
    $params[] = $filtAnnee;
}

$sql .= " ORDER BY o.date_fin DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$chantiers = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT DISTINCT YEAR(o.date_fin) as annee
    FROM candidature c
    JOIN offre o ON c.id_offre=o.id_offre
    WHERE c.id_ouvrier=? 
    AND c.decision='accepte' 
    AND o.date_fin < CURDATE()
    ORDER BY annee DESC
");

$stmt->execute([$id]);
$annees = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - Ouvrier</title>

    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/workerhistorique.css">
</head>

<body>

    <nav class="navbar">
        <div class="navcont">
            <div class="logotext">
                <a href="../index.php">
                    Uber<span>Cueillette</span>
                </a>
            </div>
            <div class="navlien">
                <span class="welcome-text">
                    Bienvenue, <?= $_SESSION['nom'] ?>
                </span>

                <a href="../logout.php" class="btn-logout">
                    Déconnexion
                </a>
            </div>
        </div>
    </nav>

    <div class="dashboard">

        <div class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="workerdashboard.php">Accueil</a></li>
                <li><a href="offres.php">Offres disponibles</a></li>
                <li><a href="mescandidatures.php">Mes candidatures</a></li>
                <li><a href="historique.php" class="active">Historique</a></li>
                <li><a href="profil.php">Mon profil</a></li>
            </ul>
        </div>

        <div class="main-content">
            <h1>Historique des chantiers</h1>

            <div class="gains-total">

                <div class="gains-card">
                    <span class="gains-label">Gains totaux</span>
                    <span class="gains-amount"><?= $totaux['gains'] ?> DT</span>
                </div>

                <div class="gains-card">
                    <span class="gains-label">Chantiers réalisés</span>
                    <span class="gains-amount"><?= $totaux['nb'] ?></span>
                </div>

                <div class="gains-card">
                    <span class="gains-label">Note moyenne</span>
                    <span class="gains-amount"><?= $noteMoy ?> ★</span>
                </div>

            </div>
            <div class="filter-bar">
                <form method="GET" action="historique.php">
                    <div class="filter-group">
                        <label>Filtrer par année :</label>
                        <select name="annee" onchange="this.form.submit()">
                            <option value="all">Toutes les années</option>
                            <?php foreach ($annees as $a): ?>
                                <option value="<?= $a['annee'] ?>" <?= $filtAnnee == $a['annee'] ? 'selected' : '' ?>>
                                    <?= $a['annee'] ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                    <a href="historique.php" class="filter-reset">
                        Réinitialiser
                    </a>
                </form>
            </div>
            <div class="historique-list">

                <?php if (empty($chantiers)): ?>
                    <p>Aucun chantier terminé.</p>
                <?php endif; ?>
                <?php foreach ($chantiers as $c): ?>

                    <div class="chantier-card">
                        <div class="chantier-header">
                            <div class="chantier-title">
                                <h3>Récolte de <?= $c['fruit'] ?></h3>
                                <span class="chantier-lieu"><?= $c['gouvernorat'] ?></span>
                            </div>
                            <div class="chantier-date">
                                <?= date('M Y', strtotime($c['date_debut'])) ?>
                            </div>

                        </div>

                        <div class="chantier-details">
                            <div class="details-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Période :</span>
                                    <span class="detail-value">
                                        <?= date('d/m/Y', strtotime($c['date_debut'])) ?> -
                                        <?= date('d/m/Y', strtotime($c['date_fin'])) ?>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Jours travaillés :</span>

                                    <span class="detail-value">
                                        <?= $c['nb_jours'] ?> jours
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Prix à la journée :</span>

                                    <span class="detail-value">
                                        <?= $c['prix_journee'] ?> DT
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Rémunération :</span>

                                    <span class="detail-value highlight">
                                        <?= $c['remuneration'] ?? 'Non saisie' ?>
                                        <?= $c['remuneration'] ? 'DT' : '' ?>
                                    </span>
                                </div>

                            </div>

                        </div>
                        <?php if ($c['note'] !== null): ?>
                            <div class="chantier-avis">
                                <div class="avis-header">
                                    <span class="avis-label">
                                        Note reçue :
                                    </span>
                                    <div class="etoiles">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php $seuil = round($c['note'] / 2); ?>
                                            <span class="etoile">
                                                <?= $i <= $seuil ? '★' : '☆' ?>
                                            </span>
                                        <?php endfor; ?>

                                    </div>

                                    <span class="note-text">
                                        <?= $c['note'] ?>/10
                                    </span>

                                </div>
                                <?php if ($c['commentaire']): ?>
                                    <div class="commentaire">
                                        <span class="commentaire-label">
                                            Commentaire :
                                        </span>
                                        <p>
                                            "<?= $c['commentaire'] ?>"
                                        </p>
                                    </div>

                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer id="contact">
        <p>&copy; 2026 Uber-Cueillette - Tous droits réservés</p>
        <p>
            Institut Supérieur de Gestion de Tunis - Projet Web2</p>

        <div class="footcontact">
            <p>
                Email: contact@example.com | Tél: +216 XX XXX XXX
            </p>
        </div>
    </footer>
</body>
</html>