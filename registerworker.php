<?php
session_start();
require_once 'config.php';

$erreur = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom         = trim($_POST['nom'] ?? '');
    $prenom      = trim($_POST['prenom'] ?? '');
    $cin         = trim($_POST['cin'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $pseudo      = trim($_POST['pseudo'] ?? '');
    $password    = $_POST['password'] ?? '';

    $photoData = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoData = file_get_contents($_FILES['photo']['tmp_name']);
    }

    $stmt = $pdo->prepare("SELECT id_ouvrier FROM ouvrier WHERE pseudo = ?");
    $stmt->execute([$pseudo]);
    if ($stmt->fetch()) {
        $erreur = 'Ce pseudo est déjà utilisé.';
    } else {
        $stmt = $pdo->prepare("SELECT id_ouvrier FROM ouvrier WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erreur = 'Cet email est déjà utilisé.';
        } else {
            $stmt = $pdo->prepare("SELECT id_ouvrier FROM ouvrier WHERE CIN = ?");
            $stmt->execute([$cin]);
            if ($stmt->fetch()) {
                $erreur = 'Ce numéro CIN est déjà utilisé.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO ouvrier (nom, prenom, CIN, email, photo, description, pseudo, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$nom, $prenom, $cin, $email, $photoData, $description, $pseudo, $hash])) {
                    $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
                } else {
                    $erreur = 'Erreur lors de l inscription veuillez réessayer';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Ouvrier - Uber-Cueillette</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/registerworker.css">
</head>
<body>
<nav class="navbar">
    <div class="navcont">
        <div class="logotext"><a href="index.php">Uber<span>Cueillette</span></a></div>
        <div class="navlien">
            <a href="index.php">Accueil</a>
            <a href="login.php" class="btn-login">Connexion</a>
        </div>
    </div>
</nav>

<div class="formcont">
    <h2>Inscription Ouvrier</h2>

    <?php if ($erreur): ?>
        <div class="Errmsg" style="margin-bottom:10px;"><?php echo $erreur; ?></div>
    <?php endif; ?>

    <form id="registerWorkerForm" method="POST" action="registerworker.php" enctype="multipart/form-data" onsubmit="return validateWorkerForm()">
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
            <label for="cin">Numéro CIN (8 chiffres)</label>
            <input type="text" id="cin" name="cin" maxlength="8" pattern="\d{8}" required>
            <div id="errcin" class="Errmsg"></div>
        </div>
        <div class="form-grp">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <div id="erremail" class="Errmsg"></div>
        </div>
        <div class="form-grp">
            <label for="photo">Photo d'identité</label>
            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg" required>
            <div id="errphoto" class="Errmsg"></div>
        </div>
        <div class="form-grp">
            <label for="description">Description (niveau éducatif, expérience, etc.)</label>
            <textarea id="description" name="description" rows="4" required></textarea>
            <div id="errdescription" class="Errmsg"></div>
        </div>
        <div class="form-grp">
            <label for="pseudo">Pseudo (lettres uniquement)</label>
            <input type="text" id="pseudo" name="pseudo" pattern="[A-Za-z]+" required>
            <div id="errpseudo" class="Errmsg"></div>
        </div>
        <div class="form-grp">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
            <small>Au moins 8 caractères (lettres ou chiffres) et se terminant par $ ou #</small>
            <div id="errpass" class="Errmsg"></div>
        </div>
        <div class="form-grp">
            <label for="confirmpass">Confirmer le mot de passe</label>
            <input type="password" id="confirmpass" name="confirmpass" required>
            <div id="errconfirm" class="Errmsg"></div>
        </div>
        <button type="submit" class="btn1">S'inscrire</button>
    </form>
    <p class="signin">Déjà inscrit ? <a href="login.php">Connectez-vous</a></p>
</div>

<footer id="contact">
    <p>&copy; 2026 Uber-Cueillette - Tous droits réservés</p>
    <p>Institut Supérieur de Gestion de Tunis - Projet Web2</p>
    <div class="footcontact"><p>Email: contact@example.com | Tél: +216 XX XXX XXX</p></div>
</footer>
<script src="javascript/script.js"></script>
<?php if ($success): ?>
<script>
    alert('<?php echo $success; ?>');
    window.location.href = 'login.php';
</script>
<?php endif; ?>
</body>
</html>
