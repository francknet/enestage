<div style="width: 50%; margin: auto;">
<style>
body {
    background-color: #c8d456; /* bleu */
}
</style>

<?php
session_start();
require "../../config/database.php";

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT nom FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$encadrant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'encadrant') {
    header("Location: ../../auth/login.php");
    exit();
}

$encadrant_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Espace Encadrant</title>
</head>
<body>

<h1>Bienvenue Encadreur <?= htmlspecialchars($encadrant['nom']) ?></h1>
</h1>
<a href="../../auth/logout.php">Se déconnecter</a>
<hr>
<?php
$encadrant_id = $_SESSION['user_id'];

$stmtUser = $conn->prepare("SELECT nom, prenom FROM users WHERE id = ?");
$stmtUser->execute([$encadrant_id]);
$encadrant = $stmtUser->fetch(PDO::FETCH_ASSOC);
?>
<!-- ====================== -->
<!-- 1. MES ETUDIANTS -->
<!-- ====================== -->

<h2>Mes étudiants affectés</h2>

<table border="1">
<tr>
    <th>Nom</th>
    <th>Prénom</th>
    <th>Entreprise</th>
</tr>

<?php
$stmt = $conn->prepare("
SELECT u.nom, u.prenom, e.nom AS entreprise
FROM affectations_encadrants ae
JOIN etudiants et ON ae.etudiant_id = et.id
JOIN users u ON et.user_id = u.id
LEFT JOIN affectations_entreprises ae2 ON et.id = ae2.etudiant_id
LEFT JOIN entreprises e ON ae2.entreprise_id = e.id
WHERE ae.encadrant_id = ?
");

$stmt->execute([$encadrant_id]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>".$row['nom']."</td>";
    echo "<td>".$row['prenom']."</td>";
    echo "<td>".($row['entreprise'] ?? 'Non affecté')."</td>";
    echo "</tr>";
}
?>
</table>

<hr>

<!-- ====================== -->
<!-- 2. EVALUER ETUDIANT -->
<!-- ====================== -->

<?php
$et = $conn->prepare("
SELECT et.id, u.prenom
FROM affectations_encadrants ae
JOIN etudiants et ON ae.etudiant_id = et.id
JOIN users u ON et.user_id = u.id
WHERE ae.encadrant_id = ?
");

$et->execute([$encadrant_id]);
$etudiants = $et->fetchAll(PDO::FETCH_ASSOC);
?>
    <h2>Évaluer un étudiant</h2>

<form method="POST">

    Étudiant :
    <select name="etudiant_id">

        <?php foreach ($etudiants as $r) { ?>
            <option value="<?= $r['id'] ?>">
                <?= htmlspecialchars($r['prenom']) ?>
            </option>
        <?php } ?>

    </select>

    <br><br>

    Note :
    <input type="number" name="note" min="0" max="20" required>

    <br><br>

    Commentaire :
    <textarea name="commentaire" required></textarea>

    <br><br>

    <button type="submit" name="evaluer">Envoyer</button>
</form>

<?php
if (isset($_POST['evaluer'])) {

    $etudiant_id = $_POST['etudiant_id'];
    $note = $_POST['note'];
    $commentaire = $_POST['commentaire'];

    $stmt = $conn->prepare("
        INSERT INTO evaluations (encadrant_id, etudiant_id, note, commentaire)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([$encadrant_id, $etudiant_id, $note, $commentaire]);

    echo "✅ Évaluation envoyée";
}
?>
<hr>

</form>
</body>
</html>
</div>