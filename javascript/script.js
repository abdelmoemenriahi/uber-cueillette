function validateLogin() {
    var pseudo = document.getElementById('pseudo').value;
    var password = document.getElementById('password').value;
    var userType = document.getElementById('user-type').value;
    var isValid = true;
    
    var pseudoRegex = /^[A-Za-z]+$/;
    if (!pseudoRegex.test(pseudo)) {
        document.getElementById('errpseudo').innerHTML = 'Le pseudo ne doit contenir que des lettres';
        isValid = false;
    }
    else
        document.getElementById('errpseudo').innerHTML = '';
    
    var passwordRegex = /^[A-Za-z0-9]{7,}[$#]$/;
    if (!passwordRegex.test(password)) {
        document.getElementById('errpass').innerHTML = 'Le mot de passe doit contenir au moins 8 caractères (lettres ou chiffres) et se terminer par $ ou #';
        isValid = false;
    }
    else
        document.getElementById('errpass').innerHTML = '';

    if (userType === "") {
        document.getElementById('errtype').innerHTML = 'Veuillez sélectionner votre profil';
        isValid = false;
    }
    else
        document.getElementById('errtype').innerHTML = '';
    
    return isValid;
}

function validateFarmerForm() {
    var nom = document.getElementById('nom').value;
    var prenom = document.getElementById('prenom').value;
    var cin = document.getElementById('cin').value;
    var email = document.getElementById('email').value;
    var adresse = document.getElementById('adresse').value;
    var pseudo = document.getElementById('pseudo').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmpass').value;
    var isValid = true;
    
    if (nom.trim() === "") {
        document.getElementById('errnom').innerHTML = 'Le nom est requis';
        isValid = false;
    }
    else if (!/^[A-Za-zÀ-ÿ]+$/.test(nom)) {
        document.getElementById('errnom').innerHTML = 'Le nom ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errnom').innerHTML = '';
    }

    if (prenom.trim() === "") {
        document.getElementById('errprenom').innerHTML = 'Le prénom est requis';
        isValid = false;
    }
    else if (!/^[A-Za-zÀ-ÿ]+$/.test(prenom)) {
        document.getElementById('errprenom').innerHTML = 'Le prénom ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errprenom').innerHTML = '';
    }
    
    if (cin === "") {
        document.getElementById('errcin').innerHTML = 'Le CIN est requis';
        isValid = false;
    }
    else if (!/^\d{8}$/.test(cin)) {
        document.getElementById('errcin').innerHTML = 'Le CIN doit contenir exactement 8 chiffres';
        isValid = false;
    }
    else {
        document.getElementById('errcin').innerHTML = '';
    }

    if (email === "") {
        document.getElementById('erremail').innerHTML = 'L\'email est requis';
        isValid = false;
    }
    else if (!/^[^\s@]+@([^\s@]+\.)+[^\s@]+$/.test(email)) {
        document.getElementById('erremail').innerHTML = 'Veuillez entrer un email valide';
        isValid = false;
    }
    else {
        document.getElementById('erremail').innerHTML = '';
    }

    if (adresse.trim() === "") {
        document.getElementById('erradresse').innerHTML = 'L\'adresse est requise';
        isValid = false;
    }
    else {
        document.getElementById('erradresse').innerHTML = '';
    }
    
    if (pseudo === "") {
        document.getElementById('errpseudo').innerHTML = 'Le pseudo est requis';
        isValid = false;
    }
    else if (!/^[A-Za-z]+$/.test(pseudo)) {
        document.getElementById('errpseudo').innerHTML = 'Le pseudo ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errpseudo').innerHTML = '';
    }
    
    if (password === "") {
        document.getElementById('errpass').innerHTML = 'Le mot de passe est requis';
        isValid = false;
    }
    else if (!/^[A-Za-z0-9]{7,}[$#]$/.test(password)) {
        document.getElementById('errpass').innerHTML = 'Le mot de passe doit contenir au moins 8 caractères (lettres ou chiffres) et se terminer par $ ou #';
        isValid = false;
    }
    else {
        document.getElementById('errpass').innerHTML = '';
    }
    
    if (confirmPassword === "") {
        document.getElementById('errconfirm').innerHTML = 'Veuillez confirmer le mot de passe';
        isValid = false;
    }
    else if (password !== confirmPassword) {
        document.getElementById('errconfirm').innerHTML = 'Les mots de passe ne correspondent pas';
        isValid = false;
    }
    else {
        document.getElementById('errconfirm').innerHTML = '';
    }
    
    return isValid;
}

function validateWorkerForm() {
    var nom = document.getElementById('nom').value;
    var prenom = document.getElementById('prenom').value;
    var cin = document.getElementById('cin').value;
    var email = document.getElementById('email').value;
    var photo = document.getElementById('photo').value;
    var description = document.getElementById('description').value;
    var pseudo = document.getElementById('pseudo').value;
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmpass').value;
    var isValid = true;
    
    if (nom.trim() === "") {
        document.getElementById('errnom').innerHTML = 'Le nom est requis';
        isValid = false;
    }
    else if (!/^[A-Za-zÀ-ÿ]+$/.test(nom)) {
        document.getElementById('errnom').innerHTML = 'Le nom ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errnom').innerHTML = '';
    }
    
    if (prenom.trim() === "") {
        document.getElementById('errprenom').innerHTML = 'Le prénom est requis';
        isValid = false;
    }
    else if (!/^[A-Za-zÀ-ÿ]+$/.test(prenom)) {
        document.getElementById('errprenom').innerHTML = 'Le prénom ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errprenom').innerHTML = '';
    }
    
    if (cin === "") {
        document.getElementById('errcin').innerHTML = 'Le CIN est requis';
        isValid = false;
    }
    else if (!/^\d{8}$/.test(cin)) {
        document.getElementById('errcin').innerHTML = 'Le CIN doit contenir exactement 8 chiffres';
        isValid = false;
    }
    else {
        document.getElementById('errcin').innerHTML = '';
    }
    
    if (email === "") {
        document.getElementById('erremail').innerHTML = 'L\'email est requis';
        isValid = false;
    }
    else if (!/^[^\s@]+@([^\s@]+\.)+[^\s@]+$/.test(email)) {
        document.getElementById('erremail').innerHTML = 'Veuillez entrer un email valide';
        isValid = false;
    }
    else {
        document.getElementById('erremail').innerHTML = '';
    }
    
    if (photo === "") {
        document.getElementById('errphoto').innerHTML = 'Veuillez sélectionner une photo';
        isValid = false;
    }
    else {
        document.getElementById('errphoto').innerHTML = '';
    }
    
    if (description.trim() === "") {
        document.getElementById('errdescription').innerHTML = 'La description est requise';
        isValid = false;
    }
    else {
        document.getElementById('errdescription').innerHTML = '';
    }

    if (pseudo === "") {
        document.getElementById('errpseudo').innerHTML = 'Le pseudo est requis';
        isValid = false;
    }
    else if (!/^[A-Za-z]+$/.test(pseudo)) {
        document.getElementById('errpseudo').innerHTML = 'Le pseudo ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errpseudo').innerHTML = '';
    }

    if (password === "") {
        document.getElementById('errpass').innerHTML = 'Le mot de passe est requis';
        isValid = false;
    }
    else if (!/^[A-Za-z0-9]{7,}[$#]$/.test(password)) {
        document.getElementById('errpass').innerHTML = 'Le mot de passe doit contenir au moins 8 caractères (lettres ou chiffres) et se terminer par $ ou #';
        isValid = false;
    }
    else {
        document.getElementById('errpass').innerHTML = '';
    }
    
    if (confirmPassword === "") {
        document.getElementById('errconfirm').innerHTML = 'Veuillez confirmer le mot de passe';
        isValid = false;
    }
    else if (password !== confirmPassword) {
        document.getElementById('errconfirm').innerHTML = 'Les mots de passe ne correspondent pas';
        isValid = false;
    }
    else {
        document.getElementById('errconfirm').innerHTML = '';
    }
    
    return isValid;
}




function validateAddOffre() {
    var fruit = document.getElementById('type-fruit').value;
    var gouvernorat = document.getElementById('gouvernorat').value;
    var adresse = document.getElementById('adresse').value;
    var dateDebut = document.getElementById('date-debut').value;
    var dateFin = document.getElementById('date-fin').value;
    var dateLimite = document.getElementById('date-limite').value;
    var nbOuvriers = document.getElementById('nombre-ouvriers').value;
    var prix = document.getElementById('prix-journee').value;
    var isValid = true;
    
    // Validation fruit
    if (fruit === "") {
        document.getElementById('errfruit').innerHTML = 'Veuillez sélectionner un type de fruit';
        isValid = false;
    }
    else {
        document.getElementById('errfruit').innerHTML = '';
    }
    
    // Validation gouvernorat
    if (gouvernorat === "") {
        document.getElementById('errgouv').innerHTML = 'Veuillez sélectionner un gouvernorat';
        isValid = false;
    }
    else {
        document.getElementById('errgouv').innerHTML = '';
    }
    
    // Validation adresse
    if (adresse.trim() === "") {
        document.getElementById('erradresse').innerHTML = 'L\'adresse est requise';
        isValid = false;
    }
    else if (adresse.trim().length < 5) {
        document.getElementById('erradresse').innerHTML = 'Adresse trop courte (minimum 5 caractères)';
        isValid = false;
    }
    else {
        document.getElementById('erradresse').innerHTML = '';
    }
    
    // Validation date début
    if (dateDebut === "") {
        document.getElementById('errdatedebut').innerHTML = 'La date de début est requise';
        isValid = false;
    }
    else {
        document.getElementById('errdatedebut').innerHTML = '';
    }
    
    // validation date fin
    if (dateFin === "") {
        document.getElementById('errdatefin').innerHTML = 'La date de fin est requise';
        isValid = false;
    }
    else if (dateDebut !== "" && dateFin < dateDebut) {
        document.getElementById('errdatefin').innerHTML = 'La date de fin doit être après la date de début';
        isValid = false;
    }
    else {
        document.getElementById('errdatefin').innerHTML = '';
    }
    
    // validation date limite
    if (dateLimite === "") {
        document.getElementById('errdatelimite').innerHTML = 'La date limite est requise';
        isValid = false;
    }
    else if (dateDebut !== "" && dateLimite > dateDebut) {
        document.getElementById('errdatelimite').innerHTML = 'La date limite doit être avant la date de début';
        isValid = false;
    }
    else {
        document.getElementById('errdatelimite').innerHTML = '';
    }
    
    if (nbOuvriers === "") {
        document.getElementById('errnbouvriers').innerHTML = 'Le nombre d\'ouvriers est requis';
        isValid = false;
    }
    else if (nbOuvriers < 1) {
        document.getElementById('errnbouvriers').innerHTML = 'Minimum 1 ouvrier';
        isValid = false;
    }
    else if (nbOuvriers > 50) {
        document.getElementById('errnbouvriers').innerHTML = 'Maximum 50 ouvriers';
        isValid = false;
    }
    else {
        document.getElementById('errnbouvriers').innerHTML = '';
    }
    
    if (prix === "") {
        document.getElementById('errprix').innerHTML = 'Le prix est requis';
        isValid = false;
    }
    else if (prix < 20) {
        document.getElementById('errprix').innerHTML = 'Le prix minimum est 20 DT par jour';
        isValid = false;
    }
    else if (prix > 200) {
        document.getElementById('errprix').innerHTML = 'Le prix maximum est 200 DT par jour';
        isValid = false;
    }
    else {
        document.getElementById('errprix').innerHTML = '';
    }
    
    return isValid;
}

function validateProfileForm() {
    var nom = document.getElementById('nom').value;
    var prenom = document.getElementById('prenom').value;
    var cin = document.getElementById('cin').value;
    var email = document.getElementById('email').value;
    var adresse = document.getElementById('adresse').value;
    var pseudo = document.getElementById('pseudo').value;
    var password = document.getElementById('password').value;
    var confirmpass = document.getElementById('confirmpass').value;
    var isValid = true;
    
    // Validation nom
    if (nom.trim() === "") {
        document.getElementById('errnom').innerHTML = 'Le nom est requis';
        isValid = false;
    }
    else if (!/^[A-Za-zÀ-ÿ]+$/.test(nom)) {
        document.getElementById('errnom').innerHTML = 'Le nom ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errnom').innerHTML = '';
    }
    
    // Validation prénom
    if (prenom.trim() === "") {
        document.getElementById('errprenom').innerHTML = 'Le prénom est requis';
        isValid = false;
    }
    else if (!/^[A-Za-zÀ-ÿ]+$/.test(prenom)) {
        document.getElementById('errprenom').innerHTML = 'Le prénom ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errprenom').innerHTML = '';
    }
    
    // Validation CIN
    if (cin === "") {
        document.getElementById('errcin').innerHTML = 'Le CIN est requis';
        isValid = false;
    }
    else if (!/^\d{8}$/.test(cin)) {
        document.getElementById('errcin').innerHTML = 'Le CIN doit contenir exactement 8 chiffres';
        isValid = false;
    }
    else {
        document.getElementById('errcin').innerHTML = '';
    }
    
    // Validation email
    if (email === "") {
        document.getElementById('erremail').innerHTML = 'L\'email est requis';
        isValid = false;
    }
    else if (!/^[^\s@]+@([^\s@]+\.)+[^\s@]+$/.test(email)) {
        document.getElementById('erremail').innerHTML = 'Veuillez entrer un email valide';
        isValid = false;
    }
    else {
        document.getElementById('erremail').innerHTML = '';
    }
    
    // Validation adresse
    if (adresse.trim() === "") {
        document.getElementById('erradresse').innerHTML = 'L\'adresse est requise';
        isValid = false;
    }
    else {
        document.getElementById('erradresse').innerHTML = '';
    }
    
    // Validation pseudo
    if (pseudo === "") {
        document.getElementById('errpseudo').innerHTML = 'Le pseudo est requis';
        isValid = false;
    }
    else if (!/^[A-Za-z]+$/.test(pseudo)) {
        document.getElementById('errpseudo').innerHTML = 'Le pseudo ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errpseudo').innerHTML = '';
    }
    
    if (password !== "") {
        if (!/^[A-Za-z0-9]{7,}[$#]$/.test(password)) {
            document.getElementById('errpass').innerHTML = 'Le mot de passe doit contenir au moins 8 caractères (lettres ou chiffres) et se terminer par $ ou #';
            isValid = false;
        }
        else if (password !== confirmpass) {
            document.getElementById('errconfirm').innerHTML = 'Les mots de passe ne correspondent pas';
            isValid = false;
        }
        else {
            document.getElementById('errpass').innerHTML = '';
            document.getElementById('errconfirm').innerHTML = '';
        }
    }
    else {
        document.getElementById('errpass').innerHTML = '';
        document.getElementById('errconfirm').innerHTML = '';
    }
    
    if (isValid) {
        alert('Profil mis à jour avec succès !');
    }
    
    return isValid;
}

function annulerModifications() {
    if (confirm('Annuler les modifications ?')) {
        location.reload();
    }
}

function changerPhoto() {
    alert('Fonctionnalité de changement de photo à venir');
}

function validateWorkerProfileForm() {
    var nom = document.getElementById('nom').value;
    var prenom = document.getElementById('prenom').value;
    var cin = document.getElementById('cin').value;
    var email = document.getElementById('email').value;
    var description = document.getElementById('description').value;
    var pseudo = document.getElementById('pseudo').value;
    var password = document.getElementById('password').value;
    var confirmpass = document.getElementById('confirmpass').value;
    var isValid = true;
    
    // Validation nom
    if (nom.trim() === "") {
        document.getElementById('errnom').innerHTML = 'Le nom est requis';
        isValid = false;
    }
    else if (!/^[A-Za-zÀ-ÿ]+$/.test(nom)) {
        document.getElementById('errnom').innerHTML = 'Le nom ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errnom').innerHTML = '';
    }
    
    if (prenom.trim() === "") {
        document.getElementById('errprenom').innerHTML = 'Le prénom est requis';
        isValid = false;
    }
    else if (!/^[A-Za-zÀ-ÿ]+$/.test(prenom)) {
        document.getElementById('errprenom').innerHTML = 'Le prénom ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errprenom').innerHTML = '';
    }

    if (cin === "") {
        document.getElementById('errcin').innerHTML = 'Le CIN est requis';
        isValid = false;
    }
    else if (!/^\d{8}$/.test(cin)) {
        document.getElementById('errcin').innerHTML = 'Le CIN doit contenir exactement 8 chiffres';
        isValid = false;
    }
    else {
        document.getElementById('errcin').innerHTML = '';
    }

    if (email === "") {
        document.getElementById('erremail').innerHTML = 'L\'email est requis';
        isValid = false;
    }
    else if (!/^[^\s@]+@([^\s@]+\.)+[^\s@]+$/.test(email)) {
        document.getElementById('erremail').innerHTML = 'Veuillez entrer un email valide';
        isValid = false;
    }
    else {
        document.getElementById('erremail').innerHTML = '';
    }
    
    // Validation photo
    var photo = document.getElementById('photo').value;
    if (photo !== "") {
        var photoExt = photo.split('.').pop().toLowerCase();
        if (photoExt !== 'jpg' && photoExt !== 'jpeg' && photoExt !== 'png') {
            document.getElementById('errphoto').innerHTML = 'Format de photo non valide (JPG, JPEG, PNG)';
            isValid = false;
        }
        else {
            document.getElementById('errphoto').innerHTML = '';
        }
    }
    else {
        document.getElementById('errphoto').innerHTML = '';
    }
    
    if (description.trim() === "") {
        document.getElementById('errdescription').innerHTML = 'La description est requise';
        isValid = false;
    }
    else if (description.trim().length < 10) {
        document.getElementById('errdescription').innerHTML = 'Description trop courte (minimum 10 caractères)';
        isValid = false;
    }
    else {
        document.getElementById('errdescription').innerHTML = '';
    }
    

    if (pseudo === "") {
        document.getElementById('errpseudo').innerHTML = 'Le pseudo est requis';
        isValid = false;
    }
    else if (!/^[A-Za-z]+$/.test(pseudo)) {
        document.getElementById('errpseudo').innerHTML = 'Le pseudo ne doit contenir que des lettres';
        isValid = false;
    }
    else {
        document.getElementById('errpseudo').innerHTML = '';
    }
    
    // validation mot de passe (si changé)
    if (password !== "") {
        if (!/^[A-Za-z0-9]{7,}[$#]$/.test(password)) {
            document.getElementById('errpass').innerHTML = 'Le mot de passe doit contenir au moins 8 caractères (lettres ou chiffres) et se terminer par $ ou #';
            isValid = false;
        }
        else if (password !== confirmpass) {
            document.getElementById('errconfirm').innerHTML = 'Les mots de passe ne correspondent pas';
            isValid = false;
        }
        else {
            document.getElementById('errpass').innerHTML = '';
            document.getElementById('errconfirm').innerHTML = '';
        }
    }
    else {
        document.getElementById('errpass').innerHTML = '';
        document.getElementById('errconfirm').innerHTML = '';
    }
    
    if (isValid) {
        alert('Profil mis à jour avec succès !');
    }
    
    return isValid;
}

function annulerModifications() {
    if (confirm('Annuler les modifications ?')) {
        location.reload();
    }
}

function changerPhoto() {
    alert('Fonctionnalité de changement de photo à venir');
}