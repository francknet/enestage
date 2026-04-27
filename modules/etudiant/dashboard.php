<div style="width: 50%; margin: auto;">
<h1>Dashboard Etudiant</h1>
<style>
body {
    background-color: #a0e2c7; /* bleu */
}
</style>
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'etudiant') {
    header("Location: ../../auth/login.php");
    exit();
}

// ======================
// CONNEXION BD
// ======================
require "../../config/database.php";

// ======================
// RECUPERER PRENOM
// ======================
if (!isset($_SESSION['prenom'])) {
    $id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT prenom FROM users WHERE id=?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['prenom'] = $user['prenom'];
}
?>

<h1>Bienvenue <?php echo $_SESSION['prenom']; ?></h1>

<a href="?action=demande">Soumettre demande de stage</a><br>
<a href="?action=statut">Consulter statut</a><br>
<a href="?action=cv">Déposer CV</a><br>
<a href="?action=entreprises">Mes entreprises</a><br>
<a href="?action=encadreur">Mon encadreur</a><br>
<a href="?action=evaluation">Consulter évaluation</a><br>
<a href="?action=offres">Offres d'emploi</a><br>
<a href="?action=documents">Télécharger documents</a><br>
<a href="?action=rapport">Déposer rapport</a><br>
<a href="?action=logout">Déconnexion</a><br><br>

<?php

// ======================
// LOGOUT
// ======================
if (isset($_GET['action']) && $_GET['action'] == "logout") {
    session_destroy();
    header("Location: ../../auth/login.php");
    exit();
}

// ======================
// 1. DEMANDE DE STAGE
// ======================
if (isset($_GET['action']) && $_GET['action'] == "demande") {
?>
<h3>Demande de stage</h3>
<form method="POST">
    <select name="entreprise_id">
        <?php
        $req = $conn->query("SELECT entreprises.id, users.nom FROM entreprises JOIN users ON users.id = entreprises.user_id");
        while($row = $req->fetch(PDO::FETCH_ASSOC)){
            echo "<option value='".$row['id']."'>".$row['nom']."</option>";
        }
        ?>
    </select><br><br>
    <button name="envoyer">Envoyer</button>
</form>
<?php
if (isset($_POST['envoyer'])) {
    $etudiant = $_SESSION['user_id'];
    $entreprise = $_POST['entreprise_id'];
    $stmt = $conn->prepare("INSERT INTO demandes (etudiant_id, entreprise_id, statut) 
                            VALUES (?, ?, 'en attente')");
    $stmt->execute([$etudiant, $entreprise]);
    echo "✅ Demande envoyée !";
}
}
// ======================
// 2. STATUT DEMANDES
// ======================
if (isset($_GET['action']) && $_GET['action'] == "statut") {
    $id = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        SELECT entreprises.nom, demandes.statut
        FROM demandes
        JOIN entreprises ON entreprises.id = demandes.entreprise_id
        WHERE demandes.etudiant_id = ?
    ");
    $stmt->execute([$id]);
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Mes demandes</h3>";

    if (count($demandes) > 0) {
        foreach ($demandes as $row) {
            echo "Entreprise : " . $row['nom'] . "<br>";
            echo "Statut : " . $row['statut'] . "<br><br>";
        }
    } else {
        echo "❌ Aucune demande pour le moment";
    }
}

// ======================
// 3. DEPOT CV
// ======================
if (isset($_GET['action']) && $_GET['action'] == "cv") {
    $id = $_SESSION['user_id'];

    $check = $conn->prepare("SELECT * FROM cv WHERE etudiant_id = ?");
    $check->execute([$id]);
    $cvExiste = $check->fetch(PDO::FETCH_ASSOC);

    if (!$cvExiste) {
?>
<h3>Dépose ton CV ici</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="cv" accept=".pdf,.doc,.docx" required><br><br>
    <button name="upload_cv">Envoyer</button>
</form>
<?php
        if (isset($_POST['upload_cv'])) {
            $file = $_FILES['cv']['name'];
            $tmp = $_FILES['cv']['tmp_name'];

            if (!is_dir("uploads_cv")) {
                mkdir("uploads_cv");
            }

            move_uploaded_file($tmp, "uploads_cv/" . $file);

            $stmt = $conn->prepare("INSERT INTO cv (etudiant_id, fichier) VALUES (?, ?)");
            $stmt->execute([$id, $file]);
            echo "✅ CV envoyé !";
        }
    } else {
        echo "✅ CV déjà soumis : " . $cvExiste['fichier'];
    }
}

// ======================
// 4. ENTREPRISES AFFECTEES
// ======================
if (isset($_GET['action']) && $_GET['action'] == "entreprises") {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("
        SELECT u.nom
        FROM affectations_entreprises ae
        JOIN etudiants et ON et.id = ae.etudiant_id
        JOIN entreprises e ON e.id = ae.entreprise_id
        JOIN users u ON u.id = e.user_id
        WHERE et.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Mes entreprises affectées</h3>";
    if (count($rows) > 0) {
        foreach ($rows as $row) {
            echo "- " . $row['nom'] . "<br>";
        }
    } else {
        echo "❌ Aucune entreprise trouvée";
    }
}

// ======================
// 5. ENCADRANT
// ======================
if (isset($_GET['action']) && $_GET['action'] == "encadreur") {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("
        SELECT u.nom, u.prenom
        FROM affectations_encadrants ae
        JOIN etudiants et ON et.id = ae.etudiant_id
        JOIN users u ON u.id = ae.encadrant_id
        WHERE et.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $encadrant = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<h3>Mon encadrant</h3>";
    if ($encadrant) {
        echo "Encadrant : " . $encadrant['prenom'] . " " . $encadrant['nom'];
    } else {
        echo "❌ Aucun encadrant assigné";
    }
}

// ======================
// 6. EVALUATION
// ======================
if (isset($_GET['action']) && $_GET['action'] == "evaluation") {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("
        SELECT note, commentaire 
        FROM evaluations
        WHERE etudiant_id = ?
    ");
    $stmt->execute([$user_id]);
    $evaluations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Mes évaluations</h3>";
    if (count($evaluations) > 0) {
        foreach($evaluations as $row){
            echo "Note: ".$row['note']." - ".$row['commentaire']."<br>";
        }
    } else {
        echo "❌ Aucune évaluation pour le moment";
    }
}

// ======================
// 7. OFFRES + REPONSE
// ======================
if (isset($_GET['action']) && $_GET['action'] == "offres") {
    $id = $_SESSION['user_id'];

    $req = $conn->query("SELECT id, titre FROM offres");
    $offres = $req->fetchAll(PDO::FETCH_ASSOC);

    echo "<h3>Offres d'emploi</h3>";

    foreach($offres as $row){
?>
        <form method="POST">
            <?php echo $row['titre']; ?>
            <input type="hidden" name="offre_id" value="<?php echo $row['id']; ?>">
            <button name="accepter">Accepter</button>
            <button name="refuser">Refuser</button>
        </form>
<?php
    }

    if(isset($_POST['accepter']) || isset($_POST['refuser'])){
        $offre = $_POST['offre_id'];
        $statut = isset($_POST['accepter']) ? 'accepté' : 'refusé';

        $stmt = $conn->prepare("INSERT INTO reponses_offres (etudiant_id, offre_id, statut) VALUES (?, ?, ?)");
        $stmt->execute([$id, $offre, $statut]);

        echo "✅ Réponse envoyée";
    }
}

// ======================
// 8. DOCUMENTS
// ======================
if (isset($_GET['action']) && $_GET['action'] == "documents") {
    echo "<h3>Télécharger documents</h3>";
    echo "<a href='docs/convention.pdf' download>Convention</a><br>";
    echo "<a href='docs/guide.pdf' download>Guide stage</a><br>";
}

// ======================
// 9. RAPPORT
// ======================
if (isset($_GET['action']) && $_GET['action'] == "rapport") {
?>
<h3>Déposer rapport</h3>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="rapport" required><br><br>
    <button name="upload">Envoyer</button>
</form>
<?php
    if (isset($_POST['upload'])) {
        $file = $_FILES['rapport']['name'];
        $tmp = $_FILES['rapport']['tmp_name'];

        if (!is_dir("uploads")) {
            mkdir("uploads");
        }

        move_uploaded_file($tmp, "uploads/" . $file);
        echo "✅ Rapport envoyé !";
    }
}
?>
</div>