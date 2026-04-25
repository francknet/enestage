<?php
require_once("../config/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $filiere = isset($_POST['filiere']) ? $_POST['filiere'] : null;
    $role = $_POST['role'];

    // 🔍 Vérifier si email existe déjà
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        echo "Cet email est déjà utilisé ! <a href='register.php'>Réessayer</a>";
        exit();
    }

    // ✅ Insertion dans users
    $sql = "INSERT INTO users (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nom, $prenom, $email, $password, $role]);
    $user_id = $conn->lastInsertId();

    // 📌 Si étudiant
    if ($role == "etudiant") {
        $stmt = $conn->prepare("INSERT INTO etudiants (user_id, prenom, filiere) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $prenom, $filiere]);
    }

    // 📌 Si entreprise
    if ($role == "entreprise") {
        $stmt = $conn->prepare("INSERT INTO entreprises (user_id) VALUES (?)");
        $stmt->execute([$user_id]);
    }

    echo "Inscription réussie ! <a href='login.php'>Se connecter</a>";
}
?>

<h2>Administrateur-Sys</h2>

<form method="POST">
    Nom: <input type="text" name="nom" required><br>
    Prenom: <input type="text" name="prenom" required><br>
    Email: <input type="email" name="email" required><br>
    Mot de passe: <input type="password" name="password" required><br>
    
    Rôle:
    <select name="role" id="role" onchange="toggleFiliere()">
        <option value="etudiant">Etudiant</option>
        <option value="entreprise">Entreprise</option>
        <option value="encadrant">Encadrant</option>
        <option value="admin">Admin</option>
    </select><br><br>
    <div id="filiereField" style="display:none;">
    <label>Filière :</label>
    <input type="text" name="filiere">
    </div>
    
<p>Déjà un compte ?</p>
<a href="login.php">
    <button type="button">Se connecter</button>
</a>
</form>
<script>
function toggleFiliere() {
    var role = document.getElementById("role").value;
    var filiereField = document.getElementById("filiereField");

    if (role === "etudiant") {
        filiereField.style.display = "block";
    } else {
        filiereField.style.display = "none";
    }
}
</script>