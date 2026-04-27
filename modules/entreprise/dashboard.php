<?php
session_start();
require_once "../../config/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'entreprise') {
    header("Location: ../../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT nom FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$entreprise = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Entreprise</title>
    <style>
        :root {
            --primary: #2563eb;    /* Bleu principal */
            --accent: #ec4899;     /* Rose accent */
            --dark: #1e2937;
            --light: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e0f2fe 100%);
            color: var(--dark);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.15);
            overflow: hidden;
        }

        header {
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }

        .logout {
            color: white;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .logout:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .welcome {
            background: #f8fafc;
            padding: 20px 30px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 20px;
            color: var(--dark);
        }

        .section {
            padding: 30px;
        }

        h2 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 22px;
            position: relative;
            padding-bottom: 10px;
        }

        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--accent);
            border-radius: 3px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        th {
            background: var(--primary);
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 500;
        }

        td {
            padding: 14px 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        tr:hover {
            background: #f8fafc;
        }

        input, textarea, select, button {
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.15);
        }

        button {
            background: linear-gradient(135deg, var(--primary), #3b82f6);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-pink {
            background: linear-gradient(135deg, var(--accent), #f472b6);
        }

        .btn-pink:hover {
            box-shadow: 0 6px 15px rgba(236, 72, 153, 0.3);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #334155;
        }

        hr {
            border: none;
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
            margin: 35px 0;
        }

        .success {
            color: #10b981;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Header -->
    <header>
        <h1>Dashboard Entreprise</h1>
        <a href="../../auth/logout.php" class="logout">Déconnexion</a>
    </header>

    <!-- Welcome -->
    <div class="welcome">
        <h1>Bienvenue, <strong><?= htmlspecialchars($entreprise['nom']) ?></strong> 👋</h1>
    </div>

    <div class="section">

        <?php
      // ======================
// RECUP ENTREPRISE_ID
// ======================
$stmt_ent = $conn->prepare("SELECT id FROM entreprises WHERE user_id = ?");
$stmt_ent->execute([$user_id]);
$ent = $stmt_ent->fetch(PDO::FETCH_ASSOC);

if (!$ent) {
    $stmt_create = $conn->prepare("INSERT INTO entreprises (user_id) VALUES (?)");
    $stmt_create->execute([$user_id]);
    $entreprise_id = $conn->lastInsertId();
} else {
    $entreprise_id = $ent['id'];
}
           

        // ======================
        // 1. ACCEPTER / REFUSER DEMANDE
        // ======================
        if (isset($_POST['valider']) || isset($_POST['refuser'])) {
            $id = $_POST['demande_id'];
            $statut = isset($_POST['valider']) ? 'validé' : 'refusé';
            $stmt = $conn->prepare("UPDATE demandes SET statut=? WHERE id=?");
            $stmt->execute([$statut, $id]);
            echo "<p class='success'>✅ Décision enregistrée</p>";
        }

        // ======================
        // 2. PUBLIER OFFRE
        // ======================
        if (isset($_POST['offre'])) {
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $stmt = $conn->prepare("INSERT INTO offres (entreprise_id, titre, description) VALUES (?, ?, ?)");
            $stmt->execute([$entreprise_id, $titre, $description]);
            echo "<p class='success'>✅ Offre publiée avec succès !</p>";
        }

        // ======================
        // 3. EVALUATION
        // ======================
        if (isset($_POST['evaluer'])) {
            $etudiant_id = $_POST['etudiant_id'];
            $note = $_POST['note'];
            $commentaire = $_POST['commentaire'];
            $stmt = $conn->prepare("INSERT INTO evaluations (etudiant_id, note, commentaire) VALUES (?, ?, ?)");
            $stmt->execute([$etudiant_id, $note, $commentaire]);
            echo "<p class='success'>✅ Évaluation enregistrée !</p>";
        }

        // ======================
        // 4. UPLOAD CV ETUDIANT
        // ======================
        if (isset($_POST['upload_cv'])) {
            $etudiant_id = $_POST['etudiant_id_cv'];
            $file = $_FILES['cv']['name'];
            $tmp = $_FILES['cv']['tmp_name'];

            if (!is_dir("uploads_cv")) {
                mkdir("uploads_cv", 0777, true);
            }

            $new_name = time() . "_" . basename($file);
            move_uploaded_file($tmp, "uploads_cv/" . $new_name);

            $check = $conn->prepare("SELECT id FROM cv WHERE etudiant_id = ?");
            $check->execute([$etudiant_id]);
            $cvExiste = $check->fetch(PDO::FETCH_ASSOC);

            if ($cvExiste) {
                $stmt = $conn->prepare("UPDATE cv SET fichier=? WHERE etudiant_id=?");
                $stmt->execute([$new_name, $etudiant_id]);
            } else {
                $stmt = $conn->prepare("INSERT INTO cv (etudiant_id, fichier) VALUES (?, ?)");
                $stmt->execute([$etudiant_id, $new_name]);
            }
            echo "<p class='success'>✅ CV uploadé avec succès !</p>";
        }
        ?>

        <!-- Réponses aux offres -->
<div class="card">
    <h2>Réponses des étudiants aux offres</h2>
    <?php
    $stmt = $conn->prepare("
        SELECT u.prenom, o.titre, r.statut
        FROM reponses_offres r
        JOIN users u ON u.id = r.etudiant_id
        JOIN offres o ON o.id = r.offre_id
        WHERE o.entreprise_id = ?
        ORDER BY r.id DESC
    ");
    $stmt->execute([$entreprise_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0): ?>
        <table>
            <tr>
                <th>Étudiant</th>
                <th>Offre</th>
                <th>Statut</th>
            </tr>
            <?php foreach($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['prenom']) ?></td>
                <td><?= htmlspecialchars($row['titre']) ?></td>
                <td><strong><?= htmlspecialchars($row['statut']) ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>❌ Aucune réponse pour le moment</p>
    <?php endif; ?>
</div>

        <!-- Publier une offre -->
        <div class="card">
            <h2>Publier une offre</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Titre de l'offre</label>
                    <input type="text" name="titre" required style="width:100%;">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="5" required style="width:100%; resize:vertical;"></textarea>
                </div>
                <button type="submit" name="offre" style="padding:12px 30px; font-size:16px;">Publier l'offre</button>
            </form>
        </div>

        <!-- Demandes de stage -->
<div class="card">
    <h2>Demandes de stage</h2>
    <?php
    $stmt = $conn->prepare("
        SELECT demandes.id, users.nom, users.prenom, demandes.statut
        FROM demandes
        JOIN etudiants ON etudiants.id = demandes.etudiant_id
        JOIN users ON users.id = etudiants.user_id
        WHERE demandes.entreprise_id = ?
    ");
    $stmt->execute([$entreprise_id]);
    $demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($demandes) > 0): ?>
        <table>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
            <?php foreach($demandes as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['nom']) ?></td>
                <td><?= htmlspecialchars($row['prenom']) ?></td>
                <td><?= htmlspecialchars($row['statut']) ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="demande_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="valider" class="btn-pink" style="padding:8px 16px; margin-right:8px;">✅ Valider</button>
                        <button type="submit" name="refuser" style="background:#ef4444; padding:8px 16px;">❌ Refuser</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>❌ Aucune demande pour le moment</p>
    <?php endif; ?>
</div>

       <!-- Liste des étudiants affectés -->
<div class="card">
    <h2>Liste des étudiants affectés à mon entreprise</h2>
    <?php
    $stmt = $conn->prepare("
        SELECT users.id, users.nom, users.prenom, users.email, demandes.statut
        FROM demandes
        JOIN etudiants ON etudiants.id = demandes.etudiant_id
        JOIN users ON users.id = etudiants.user_id
        WHERE demandes.entreprise_id = ?
    ");
    $stmt->execute([$entreprise_id]);
    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (count($etudiants) > 0): ?>
        <table>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Statut</th>
                <th>CV</th>
                <th>Upload CV</th>
            </tr>
            <?php foreach($etudiants as $row):
                $checkCv = $conn->prepare("SELECT fichier FROM cv WHERE etudiant_id = ?");
                $checkCv->execute([$row['id']]);
                $cv = $checkCv->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
                <td><?= htmlspecialchars($row['nom']) ?></td>
                <td><?= htmlspecialchars($row['prenom']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['statut']) ?></td>
                <td>
                    <?php if ($cv): ?>
                        <a href="../../modules/etudiant/uploads_cv/<?= htmlspecialchars($cv['fichier']) ?>" download style="color:var(--accent); text-decoration:underline;">📄 Télécharger</a>
                    <?php else: ?>
                        ❌ Pas de CV
                    <?php endif; ?>
                </td>
                <td>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="etudiant_id_cv" value="<?= $row['id'] ?>">
                        <input type="file" name="cv" accept=".pdf,.doc,.docx" required style="width:180px;">
                        <button type="submit" name="upload_cv" style="padding:8px 16px;">📤 Uploader</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>❌ Aucun étudiant trouvé pour votre entreprise</p>
    <?php endif; ?>
</div>

        <!-- Évaluation -->
        <div class="card">
            <h2>Évaluer un stagiaire</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Étudiant</label>
                    <select name="etudiant_id" required style="width:100%;">
                        <option value="">-- Choisir un étudiant --</option>
                        <?php
                        $stmt = $conn->prepare("
                            SELECT users.id, users.nom, users.prenom
                            FROM demandes
                            JOIN users ON users.id = demandes.etudiant_id
                            WHERE demandes.entreprise_id = ?
                        ");
                        $stmt->execute([$user_id]);
                        $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach($liste as $etudiant):
                        ?>
                            <option value="<?= $etudiant['id'] ?>"><?= htmlspecialchars($etudiant['prenom']." ".$etudiant['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Note (sur 20)</label>
                    <input type="number" name="note" min="0" max="20" required style="width:100%;">
                </div>

                <div class="form-group">
                    <label>Commentaire</label>
                    <textarea name="commentaire" rows="4" style="width:100%; resize:vertical;"></textarea>
                </div>

                <button type="submit" name="evaluer" style="padding:12px 30px; font-size:16px;">Enregistrer l'évaluation</button>
            </form>
        </div>

    </div>
</div>

</body>
</html>