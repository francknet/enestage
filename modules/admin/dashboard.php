<?php
session_start();
require "../../config/database.php";

/* Vérification ADMIN */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}

/* ---------- SUPPRIMER ENCADRANT ---------- */
if(isset($_GET['delete_encadrant'])){
    $id = $_GET['delete_encadrant'];
    $conn->query("DELETE FROM users WHERE id=$id AND role='encadrant'");
}

/* ---------- CREER ENCADRANT ---------- */
if(isset($_POST['create_encadrant'])){
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $conn->query("INSERT INTO users (nom, email, password, role, statut)
                  VALUES ('$nom', '$email', '$password', 'encadrant', 'actif')");
}

/* ---------- SUPPRIMER ETUDIANT ---------- */
if(isset($_GET['delete_etudiant'])){
    $id = $_GET['delete_etudiant'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='etudiant'");
    $stmt->execute([$id]);
}

/* ---------- SUPPRIMER ENTREPRISE ---------- */
if(isset($_GET['delete_entreprise'])){
    $id = $_GET['delete_entreprise'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=? AND role='entreprise'");
    $stmt->execute([$id]);
}

/* ---------- SUSPENDRE UTILISATEUR ---------- */
if(isset($_GET['suspendre'])){
    $id = $_GET['suspendre'];
    $stmt = $conn->prepare("UPDATE users SET statut='suspendu' WHERE id=?");
    $stmt->execute([$id]);
}

/* ---------- VALIDER DEMANDE ---------- */
if(isset($_GET['valider'])){
    $id = $_GET['valider'];
    $stmt = $conn->prepare("UPDATE demandes_stage SET statut='validé' WHERE id_demande=?");
    $stmt->execute([$id]);
}

/* ---------- AFFECTER ENCADRANT ---------- */
if(isset($_POST['affecter'])){
    $demande = $_POST['demande_id'];
    $encadrant = $_POST['encadrant_id'];
    $stmt = $conn->prepare("UPDATE demandes_stage SET encadrant_id=? WHERE id_demande=?");
    $stmt->execute([$encadrant, $demande]);
}

/* ---------- STATISTIQUES ---------- */
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch(PDO::FETCH_ASSOC)['total'];
$totalEtudiants = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='etudiant'")->fetch(PDO::FETCH_ASSOC)['total'];
$totalEntreprises = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='entreprise'")->fetch(PDO::FETCH_ASSOC)['total'];
$totalStages = $conn->query("SELECT COUNT(*) as total FROM demandes_stage")->fetch(PDO::FETCH_ASSOC)['total'];
$totalValides = $conn->query("SELECT COUNT(*) as total FROM demandes_stage WHERE statut='validé'")->fetch(PDO::FETCH_ASSOC)['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <center><title>Admin Dashboard</title></center>
    
    <!-- ===== PALETTE INDIGO PURPLE (Élégant & Premium) ===== -->
    <style>
        :root {
            --bg: #0f0a1f;
            --surface: #1a1633;
            --primary: #6366f1;
            --accent: #a5b4fc;
            --text: #e0e7ff;
            --text-light: #c4c9f8;
            --success: #34d399;
            --warning: #fcd34d;
            --danger: #fb7185;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 30px;
            line-height: 1.6;
        }

        h1, h2 {
            color: var(--primary);
            margin: 25px 0 15px 0;
        }

        h1 {
            font-size: 2.3rem;
            border-bottom: 3px solid var(--accent);
            padding-bottom: 12px;
        }

        a {
            color: var(--accent);
        }

        a:hover {
            color: var(--primary);
        }

        hr {
            border: none;
            border-top: 1px solid #433a6b;
            margin: 35px 0;
        }

        /* Statistiques */
        p {
            background: var(--surface);
            padding: 12px 18px;
            border-radius: 12px;
            margin: 8px 0;
            border-left: 4px solid var(--accent);
        }

        /* Tableaux */
        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--surface);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.2);
        }

        th {
            background-color: var(--primary);
            color: white;
            padding: 16px 12px;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 14px 12px;
            border-bottom: 1px solid #433a6b;
        }

        tr:hover {
            background-color: #27224f;
        }

        /* Boutons */
        button {
            background-color: var(--primary);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s;
        }

        button:hover {
            background-color: var(--accent);
            color: #0f0a1f;
            transform: translateY(-2px);
        }

        /* Formulaire */
        input[type="number"], input[type="text"] {
            background: #27224f;
            border: 2px solid #433a6b;
            color: var(--text);
            padding: 12px;
            border-radius: 12px;
            width: 100%;
            max-width: 420px;
        }

        input:focus {
            border-color: var(--accent);
            outline: none;
        }

        /* Bouton phpMyAdmin */
        a button {
            background-color: var(--accent);
            color: #0f0a1f;
            font-weight: bold;
        }
    </style>
</head>
<body>

<center><h1>Tableau de bord Administrateur</h1></center>
<a href="../../auth/logout.php">Se déconnecter</a>
<hr>

<!-- ================= STATISTIQUES ================= -->
<center><h2>Statistiques</h2>
<p>Total utilisateurs : <?php echo $totalUsers; ?></p>
<p>Total étudiants : <?php echo $totalEtudiants; ?></p>
<p>Total entreprises : <?php echo $totalEntreprises; ?></p>
<p>Total demandes : <?php echo $totalStages; ?></p>
<p>Demandes validées : <?php echo $totalValides; ?></p>
<hr></center>

<!-- ================= LISTE ETUDIANTS ================= -->
<center><h2>Liste des étudiants</h2></center>
<table border="1">
<tr>
    <th>ID</th><th>Nom</th><th>Email</th><th>Statut</th><th>Action</th>
</tr>
<?php
$stmt = $conn->query("SELECT * FROM users WHERE role='etudiant'");
$stmt = $conn->prepare("
    SELECT
        users.id AS user_id,
        users.nom,
        users.email,
        etudiants.statut,
        etudiants.filiere
    FROM etudiants
    JOIN users ON etudiants.user_id = users.id
");
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo "<tr>";
    echo "<td>".$row['user_id']."</td>";
    echo "<td>".$row['nom']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "<td>".$row['statut']."</td>";
    echo "<td>
        <a href='?suspendre=".$row['user_id']."'>Suspendre</a> |
        <a href='?delete_etudiant=".$row['user_id']."' onclick='return confirm(\"Supprimer ?\")'>Supprimer</a>
    </td>";
    echo "</tr>";
}
?>
</table>
<hr>

<!-- ================= LISTE ENCADRANTS ================= -->
<center><h2>Liste des encadrants</h2></center>
<table border="1">
<tr>
    <th>ID</th><th>Nom</th><th>Email</th><th>Statut</th><th>Action</th>
</tr>
<?php
$stmt = $conn->query("SELECT * FROM users WHERE role='encadrant'");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['nom']."</td>";
    echo "<td>".$row['email']."</td>";
   
    echo "<td>
        <a href='?suspendre=".$row['id']."'>Suspendre</a> |
        <a href='?delete_encadrant=".$row['id']."' onclick='return confirm(\"Supprimer ?\")'>Supprimer</a>
    </td>";
    echo "</tr>";
}
?>
</table>

<!-- ================= LISTE ENTREPRISES ================= -->
<center><h2>Liste des entreprises</h2></center>
<table border="1">
<tr>
    <th>ID</th><th>Nom</th><th>Email</th><th>Action</th>
</tr>
<?php
$stmt = $conn->query("SELECT * FROM users WHERE role='entreprise'");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo "<tr>";
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['nom']."</td>";
    echo "<td>".$row['email']."</td>";
    echo "<td>
        <a href='?delete_entreprise=".$row['id']."' onclick='return confirm(\"Supprimer ?\")'>Supprimer</a>
    </td>";
    echo "</tr>";
}
?>
</table>
<hr>

<!-- ================= DEMANDES STAGE ================= -->
<center><h2>Demandes de stage</h2></center>
<table border="1">
<tr>
    <th>ID</th><th>Etudiant</th><th>Entreprise</th><th>Statut</th><th>Action</th>
</tr>
<?php
$stmt = $conn->query("SELECT * FROM demandes_stage");
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo "<tr>";
    echo "<td>".$row['id_demande']."</td>";
    echo "<td>".$row['etudiant_id']."</td>";
    echo "<td>".$row['entreprise_id']."</td>";
    echo "<td>".$row['statut']."</td>";
    echo "<td>
        <a href='?valider=".$row['id_demande']."'>Valider</a>
    </td>";
    echo "</tr>";
}
?>
</table>
<hr>

<!-- ================= AFFECTER ENCADRANT ================= -->
<center><h2>Affecter un encadrant</h2>
<form method="POST">
    ID Demande : <input type="number" name="demande_id" required><br><br>
    ID Encadrant : <input type="number" name="encadrant_id" required><br><br>
    <button type="submit" name="affecter">Affecter</button>
</form></center>

<!-- Bouton accès phpMyAdmin -->
<h2>Gestion Base de Données</h2>
<a href="http://localhost/phpmyadmin" target="_blank">
    <button>
        Accéder à phpMyAdmin
    </button>
</a>

</body>
</html>