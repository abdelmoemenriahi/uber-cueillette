<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'farmer') {
    header('Location: ../login.php'); exit;
}
require_once '../config.php';

$id = $_SESSION['user_id'];
$success = ''; $erreur = '';

// Charger les listes
$fruits = $pdo->query("SELECT * FROM type_fruit ORDER BY libelle")->fetchAll();
$gouvernorats = $pdo->query("SELECT * FROM gouvernorat ORDER BY libelle")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_fruit   = $_POST['type-fruit'] ?? '';
    $id_gouv    = $_POST['gouvernorat'] ?? '';
    $adresse    = trim($_POST['adresse'] ?? '');
    $date_debut = $_POST['date-debut'] ?? '';
    $date_fin   = $_POST['date-fin'] ?? '';
    $date_limite = $_POST['date-limite'] ?? '';
    $nb_ouvriers = intval($_POST['nombre-ouvriers'] ?? 0);
    $prix       = floatval($_POST['prix-journee'] ?? 0);

    $stmt = $pdo->prepare("INSERT INTO offre (id_type_fruit, id_gouvernorat, adresse, date_debut, date_fin, nombre_ouvriers, prix_journee, date_limite, id_agriculteur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$id_fruit, $id_gouv, $adresse, $date_debut, $date_fin, $nb_ouvriers, $prix, $date_limite, $id]);
    $success = 'Offre publiée avec succès !';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une offre - Agriculteur</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/ajouteroffre.css">
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
            <li><a href="farmerdashboard.php">Accueil</a></li>
            <li><a href="ajouteroffre.php" class="active">Ajouter une offre</a></li>
            <li><a href="mesoffres.php">Mes offres</a></li>
            <li><a href="candidatures.php">Candidatures</a></li>
            <li><a href="profilmodif.php">Modifier profil</a></li>
        </ul>
    </div>
    <div class="main-content">
        <h1>Ajouter une nouvelle offre</h1>
        <?php if ($success): ?><div class="success-msg"></div><?php endif; ?>
        <?php if ($erreur): ?><div class="error-msg"></div><?php endif; ?>
        <div class="offre-formcont">
            <form id="addOffreForm" method="POST" action="ajouteroffre.php" onsubmit="return validateAddOffre()">
                <div class="offre-form-row">
                    <div class="offre-form-grp">
                        <label for="type-fruit">Type de fruit</label>
                        <select id="type-fruit" name="type-fruit" required>
                            <option value="">Sélectionnez un fruit</option>
                            <?php foreach ($fruits as $f): ?>
                            <option value="<?= $f['id_type_fruit'] ?>"><?= htmlspecialchars($f['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="errfruit" class="offre-Errmsg"></div>
                    </div>
                    <div class="offre-form-grp">
                        <label for="gouvernorat">Gouvernorat</label>
                        <select id="gouvernorat" name="gouvernorat" required>
                            <option value="">Sélectionnez un gouvernorat</option>
                            <?php foreach ($gouvernorats as $g): ?>
                            <option value="<?= $g['id_gouvernorat'] ?>"><?= htmlspecialchars($g['libelle']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="errgouv" class="offre-Errmsg"></div>
                    </div>
                </div>
                <div class="offre-form-grp">
                    <label for="adresse">Adresse du site de récolte</label>
                    <input type="text" id="adresse" name="adresse" placeholder="Ex: Route de Siliana, Km 5" required>
                    <div id="erradresse" class="offre-Errmsg"></div>
                </div>
                <div class="offre-form-row">
                    <div class="offre-form-grp">
                        <label for="date-debut">Date de début</label>
                        <input type="date" id="date-debut" name="date-debut" required>
                        <div id="errdatedebut" class="offre-Errmsg"></div>
                    </div>
                    <div class="offre-form-grp">
                        <label for="date-fin">Date de fin</label>
                        <input type="date" id="date-fin" name="date-fin" required>
                        <div id="errdatefin" class="offre-Errmsg"></div>
                    </div>
                </div>
                <div class="offre-form-row">
                    <div class="offre-form-grp">
                        <label for="date-limite">Date limite de candidature</label>
                        <input type="date" id="date-limite" name="date-limite" required>
                        <div id="errdatelimite" class="offre-Errmsg"></div>
                        <small>Les ouvriers doivent postuler avant cette date</small>
                    </div>
                    <div class="offre-form-grp">
                        <label for="nombre-ouvriers">Nombre d'ouvriers</label>
                        <input type="number" id="nombre-ouvriers" name="nombre-ouvriers" min="1" max="50" placeholder="1 à 50" required>
                        <div id="errnbouvriers" class="offre-Errmsg"></div>
                    </div>
                </div>
                <div class="offre-form-grp">
                    <label for="prix-journee">Prix à la journée (DT)</label>
                    <input type="number" id="prix-journee" name="prix-journee" step="0.5" min="20" placeholder="Ex: 50" required>
                    <div id="errprix" class="offre-Errmsg"></div>
                    <small>Prix minimum : 20 DT par jour</small>
                </div>
                <div class="offre-form-buttons">
                    <button type="submit" class="offre-btn-submit">Publier l'offre</button>
                    <button type="reset" class="offre-btn-reset">Réinitialiser</button>
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
