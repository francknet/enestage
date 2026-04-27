<?php
session_start();
session_regenerate_id(true);

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin_sys') {
    header("Location: /enestage/auth/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "enestage");
$et = $conn->query("SELECT etudiants.id, users.nom, users.prenom FROM etudiants JOIN users ON users.id = etudiants.user_id");

// AJOUTER CES LIGNES POUR DEBOGUER
if (!$et) {
    echo "Erreur SQL : " . $conn->error;
} else {
    echo "Nombre d'étudiants trouvés : " . $et->num_rows;
}
if ($conn->connect_error) {
    die("Erreur connexion : " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Admin Système - EneStage</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Segoe UI', sans-serif;
    background: #e8f4fd;
    color: #1e293b;
    min-height: 100vh;
}

/* ===== HEADER ===== */
.header {
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    color: white;
    padding: 20px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 15px rgba(37,99,235,0.3);
}
.header h1 {
    font-size: 24px;
    letter-spacing: 1px;
}
.header a {
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 8px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    transition: 0.3s;
}
.header a:hover {
    background: rgba(255,255,255,0.35);
}

/* ===== MAIN ===== */
.main {
    max-width: 1400px;
    margin: 30px auto;
    padding: 0 20px;
}

/* ===== SECTION TITLE ===== */
.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #1d4ed8;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #bfdbfe;
}

/* ===== CARDS ROW ===== */
.cards-row {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

/* ===== CARD ===== */
.card {
    background: white;
    border-radius: 14px;
    padding: 25px;
    flex: 1;
    min-width: 250px;
    box-shadow: 0 2px 15px rgba(37,99,235,0.08);
    border-top: 4px solid #2563eb;
    transition: 0.3s;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(37,99,235,0.15);
}
.card h2 {
    font-size: 16px;
    color: #1d4ed8;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ===== FORM ===== */
.form-group {
    margin-bottom: 12px;
}
.form-group label {
    display: block;
    font-size: 13px;
    color: #64748b;
    margin-bottom: 4px;
}
.form-group input,
.form-group select {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    font-size: 14px;
    color: #1e293b;
    background: #f8faff;
    transition: 0.2s;
}
.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

/* ===== BUTTON ===== */
.btn {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    margin-top: 5px;
}
.btn-blue { background: #2563eb; color: white; }
.btn-green { background: #16a34a; color: white; }
.btn-orange { background: #ea580c; color: white; }
.btn-purple { background: #7c3aed; color: white; }
.btn-teal { background: #0d9488; color: white; }
.btn:hover { opacity: 0.88; transform: translateY(-1px); }

/* ===== SUCCESS / ERROR ===== */
.msg-success {
    background: #dcfce7;
    color: #16a34a;
    padding: 10px 14px;
    border-radius: 8px;
    margin-top: 10px;
    font-size: 14px;
}
.msg-error {
    background: #fee2e2;
    color: #dc2626;
    padding: 10px 14px;
    border-radius: 8px;
    margin-top: 10px;
    font-size: 14px;
}

/* ===== TABLE ===== */
.table-wrapper {
    background: white;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 2px 15px rgba(37,99,235,0.08);
    flex: 1;
    min-width: 250px;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
table th {
    background: #eff6ff;
    color: #1d4ed8;
    padding: 10px 14px;
    text-align: left;
    font-weight: 600;
}
table td {
    padding: 10px 14px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
}
table tr:hover td {
    background: #f8faff;
}

/* ===== DIVIDER ===== */
.divider {
    border: none;
    border-top: 2px solid #bfdbfe;
    margin: 10px 0 25px 0;
}
</style>
</head>
<body>

<!-- ===== HEADER ===== -->
<div class="header">
    <h1>🛡️ ADMINISTRATEUR SYSTÈME</h1>
    <a href="/enestage/auth/logout.php">🚪 Déconnexion</a>
</div>

<div class="main">

<?php
// Messages
ob_start();

// CREER ETUDIANT
if (isset($_POST['create_etudiant'])) {
    try {
        $nom = $_POST['nom']; $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, 'etudiant')");
        $stmt->bind_param("ssss", $nom, $prenom, $email, $password);
        $stmt->execute();
        $new_user_id = $conn->insert_id;

        // ✅ Insérer aussi dans etudiants
        $stmt2 = $conn->prepare("INSERT INTO etudiants (user_id) VALUES (?)");
        $stmt2->bind_param("i", $new_user_id);
        $stmt2->execute();
        $new_etudiant_id = $conn->insert_id;

        echo '<div class="msg-success">✅ Étudiant créé avec succès</div>';
    } catch (Exception $e) {
        echo '<div class="msg-error">❌ Erreur : '.$e->getMessage().'</div>';
    }
}

// CREER ENTREPRISE
if (isset($_POST['create_entreprise'])) {
    try {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, 'entreprise')");
        $stmt->bind_param("sss", $nom, $email, $password);
        $stmt->execute();
        $new_user_id = $conn->insert_id;

        // ✅ Insérer aussi dans entreprises
        $stmt2 = $conn->prepare("INSERT INTO entreprises (user_id) VALUES (?)");
        $stmt2->bind_param("i", $new_user_id);
        $stmt2->execute();

        echo '<div class="msg-success">✅ Entreprise créée avec succès</div>';
    } catch (Exception $e) {
        echo '<div class="msg-error">❌ Erreur : '.$e->getMessage().'</div>';
    }
}

// CREER ENCADRANT
if (isset($_POST['create_encadrant'])) {
    try {
        $nom = $_POST['nom']; $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, 'encadrant')");
        $stmt->bind_param("sss", $nom, $email, $password);
        $stmt->execute();
        echo '<div class="msg-success">✅ Encadrant créé avec succès</div>';
    } catch (Exception $e) {
        echo '<div class="msg-error">❌ Erreur : '.$e->getMessage().'</div>';
    }
}

// AFFECTER ENCADRANT
if (isset($_POST['affecter_encadrant'])) {
    $etudiant = $_POST['etudiant_id']; $encadrant = $_POST['encadrant_id'];
    $stmt = $conn->prepare("INSERT INTO affectations_encadrants (etudiant_id, encadrant_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $etudiant, $encadrant);
    $stmt->execute();
    echo '<div class="msg-success">✅ Affectation encadrant réussie</div>';
}

// AFFECTER ENTREPRISE
if (isset($_POST['affecter_entreprise'])) {
    $etudiant_id = $_POST['etudiant_id']; $entreprise_id = $_POST['entreprise_id'];
    $check = $conn->prepare("SELECT id FROM etudiants WHERE id=?");
    $check->bind_param("i", $etudiant_id); $check->execute();
    if ($check->get_result()->num_rows == 0) {
        echo '<div class="msg-error">❌ Étudiant inexistant</div>';
    } else {
        $check2 = $conn->prepare("SELECT id FROM users WHERE id=? AND role='entreprise'");
        $check2->bind_param("i", $entreprise_id); $check2->execute();
        if ($check2->get_result()->num_rows == 0) {
            echo '<div class="msg-error">❌ Entreprise inexistante</div>';
        } else {
            $stmt = $conn->prepare("INSERT INTO affectations_entreprises (etudiant_id, entreprise_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $etudiant_id, $entreprise_id); $stmt->execute();
            echo '<div class="msg-success">✅ Affectation réussie</div>';
        }
    }
}

$messages = ob_get_clean();
echo $messages;
?>

<!-- ====================== -->
<!-- SECTION 1 : CRÉER COMPTES -->
<!-- ====================== -->
<p class="section-title">👤 Créer des comptes</p>
<div class="cards-row">

    <!-- ETUDIANT -->
    <div class="card">
        <h2>👨‍🎓 Créer un compte étudiant</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" required>
            </div>
            <div class="form-group">
                <label>Prénom</label>
                <input type="text" name="prenom" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>
            <button class="btn btn-blue" name="create_etudiant">➕ Créer le compte</button>
        </form>
    </div>

    <!-- ENTREPRISE -->
    <div class="card">
        <h2>🏢 Créer un compte entreprise</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nom entreprise</label>
                <input type="text" name="nom" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>
            <button class="btn btn-green" name="create_entreprise">➕ Créer l'entreprise</button>
        </form>
    </div>

    <!-- ENCADRANT -->
    <div class="card">
        <h2>👨‍🏫 Créer un compte encadreur</h2>
        <form method="POST">
            <div class="form-group">
                <label>Nom</label>
                <input type="text" name="nom" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" required>
            </div>
            <button class="btn btn-orange" name="create_encadrant">➕ Créer l'encadreur</button>
        </form>
    </div>

</div>

<hr class="divider">

<!-- ====================== -->
<!-- SECTION 2 : AFFECTER -->
<!-- ====================== -->
<p class="section-title">🔗 Affecter des étudiants</p>
<div class="cards-row">

    <!-- AFFECTER ENCADRANT -->
    <div class="card">
        <h2>👨‍🏫 Affecter étudiant à un encadrant</h2>
        <form method="POST">
            <div class="form-group">
                <label>Étudiant</label>
                <select name="etudiant_id">
                    <?php
                $et = $conn->query("SELECT etudiants.id, users.nom, users.prenom FROM etudiants JOIN users ON users.id = etudiants.user_id");
                while($row = $et->fetch_assoc()){
                    echo "<option value='".$row['id']."'>".$row['nom']." ".$row['prenom']."</option>";           
                }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Encadrant</label>
                <select name="encadrant_id">
                    <?php
            
                    $en = $conn->query("SELECT id, nom FROM users WHERE role='encadrant'");
                    while($row = $en->fetch_assoc()){
                        echo "<option value='".$row['id']."'>".$row['nom']."</option>";
                    }
                       
                    ?>
                </select>
            </div>
            <button class="btn btn-purple" name="affecter_encadrant">🔗 Affecter</button>
        </form>
    </div>

    <!-- AFFECTER ENTREPRISE -->
    <div class="card">
        <h2>🏢 Affecter étudiant à une entreprise</h2>
        <form method="POST">
            <div class="form-group">
                <label>Étudiant</label>
                <select name="etudiant_id" required>
                    <?php
   $et2 = $conn->query("SELECT etudiants.id, users.nom, users.prenom FROM etudiants JOIN users ON users.id = etudiants.user_id");
                while($row2 = $et2->fetch_assoc()){
                    echo "<option value='".$row2['id']."'>".$row2['nom']." ".$row2['prenom']."</option>";
                }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Entreprise</label>
                <select name="entreprise_id" required>
                    <?php
                    $res2 = $conn->query("SELECT id, nom FROM users WHERE role='entreprise'");
                    while($row2 = $res2->fetch_assoc()){
                        echo "<option value='".$row2['id']."'>".$row2['nom']."</option>";
                    }
                    ?>
                </select>
            </div>
            <button class="btn btn-teal" name="affecter_entreprise">🔗 Affecter</button>
        </form>
    </div>

</div>

<hr class="divider">

<!-- ====================== -->
<!-- SECTION 3 : LISTES -->
<!-- ====================== -->
/*<p class="section-title">📋 Listes des utilisateurs</p>
<div class="cards-row">

    <!-- LISTE ETUDIANTS -->
    <div class="table-wrapper">
        <h2 style="color:#1d4ed8; margin-bottom:15px;">👨‍🎓 Étudiants</h2>
        <table>
            <tr><th>Prénom</th><th>Nom</th></tr>
            <?php
            $res = $conn->query("SELECT * FROM users WHERE role='etudiant'");
            while($r = $res->fetch_assoc()){
                echo "<tr><td>".$r['prenom']."</td><td>".$r['nom']."</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- LISTE ENTREPRISES -->
    <div class="table-wrapper">
        <h2 style="color:#1d4ed8; margin-bottom:15px;">🏢 Entreprises</h2>
        <table>
            <tr><th>ID</th><th>Nom</th></tr>
            <?php
            $res = $conn->query("SELECT id, nom FROM users WHERE role='entreprise'");
            while($r = $res->fetch_assoc()){
                echo "<tr><td>".$r['id']."</td><td>".$r['nom']."</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- LISTE ENCADRANTS -->
    <div class="table-wrapper">
        <h2 style="color:#1d4ed8; margin-bottom:15px;">👨‍🏫 Encadrants</h2>
        <table>
            <tr><th>Nom</th></tr>
            <?php
            $res = $conn->query("SELECT * FROM users WHERE role='encadrant'");
            while($r = $res->fetch_assoc()){
                echo "<tr><td>".$r['nom']."</td></tr>";
            }
            ?>
        </table>
    </div>

</div>

</div><!-- end main -->
</body>
</html>