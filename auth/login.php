<?php
session_start();
require_once("../config/database.php");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // ======================
    // RESET PASSWORD
    // ======================
    if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {

        if ($_POST['new_password'] !== $_POST['confirm_password']) {
            $error = "Les mots de passe ne correspondent pas";
        } else {

            $hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$hash, $email]);

            $success = "Mot de passe modifié. Vous pouvez vous connecter.";
        }

    } else {
// ======================
// LOGIN NORMAL
// ======================
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['email'] = $user['email'];

    // étudiant
    if ($user['role'] == 'etudiant') {

        $stmt2 = $conn->prepare("SELECT id FROM etudiants WHERE user_id = ?");
        $stmt2->execute([$user['id']]);
        $etudiant = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($etudiant) {
            $_SESSION['etudiant_id'] = $etudiant['id'];
        }
    }

    // redirections
    if ($user['role'] == 'admin') {
        header("Location: ../modules/admin/dashboard.php");
    } elseif ($user['role'] == 'admin_sys') {
        header("Location: /enestage/berthol.php");
    } elseif ($user['role'] == 'entreprise') {
        header("Location: ../modules/entreprise/dashboard.php");
    } elseif ($user['role'] == 'encadrant') {
        header("Location: ../modules/encadrant/dashboard.php");
    } else {
        header("Location: ../modules/etudiant/dashboard.php");
    }

    exit();

} else {
    $error = "Email ou mot de passe incorrect";
}
    }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — ENE Stage</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #0f172a;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .card {
      background: #1e293b;
      border: 1px solid #334155;
      border-radius: 16px;
      padding: 40px 36px;
      width: 100%;
      max-width: 400px;
    }
    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 24px;
    }
    .logo-icon {
      width: 36px; height: 36px;
      background: #6366f1;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
    }
    .logo-icon svg { width: 20px; height: 20px; }
    .logo-text { font-size: 16px; font-weight: 600; color: #e2e8f0; }
    .badge {
      display: inline-block;
      background: #312e81;
      color: #a5b4fc;
      font-size: 11px;
      font-weight: 500;
      padding: 3px 10px;
      border-radius: 20px;
      margin-bottom: 20px;
    }
    h2 { font-size: 22px; font-weight: 600; color: #f1f5f9; margin-bottom: 4px; }
    .subtitle { font-size: 13px; color: #64748b; margin-bottom: 24px; }
    .error-box {
      background: rgba(239,68,68,0.1);
      border: 1px solid rgba(239,68,68,0.3);
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13px;
      color: #f87171;
      margin-bottom: 16px;
    }
    .field { margin-bottom: 16px; }
    label {
      display: block;
      font-size: 11px;
      font-weight: 600;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 6px;
    }
    input[type="email"],
    input[type="password"] {
      width: 100%;
      background: #0f172a;
      border: 1px solid #334155;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 14px;
      color: #e2e8f0;
      outline: none;
      transition: border-color .2s;
    }
    input:focus { border-color: #6366f1; }
    input::placeholder { color: #475569; }
    .forgot {
      text-align: right;
      margin-top: -8px;
      margin-bottom: 20px;
    }
    .forgot a { font-size: 12px; color: #6366f1; text-decoration: none; }
    .btn {
      width: 100%;
      background: #6366f1;
      border: none;
      border-radius: 8px;
      padding: 12px;
      font-size: 14px;
      font-weight: 600;
      color: white;
      cursor: pointer;
      transition: background .2s;
    }
    .btn:hover { background: #4f46e5; }
  </style>
</head>
<body>
  <div class="card">
    <div class="logo">
      <div class="logo-icon">
        <svg viewBox="0 0 20 20" fill="none" stroke="white" stroke-width="1.5">
          <path d="M10 2L3 7v11h5v-5h4v5h5V7L10 2z"/>
        </svg>
      </div>
      <span class="logo-text">ENEStage</span>
    </div>
    <div class="badge">Plateforme de gestion des stages</div>
    <h2>Connexion</h2>
    <p class="subtitle">Bienvenue — connectez-vous à votre espace</p>

    <?php if ($error): ?>
      <div class="error-box"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" placeholder="votre@email.com" required>
      </div>
      <div class="field">
        <label>Mot de passe</label>
        <div id="resetBox" style="display:none; margin-top:10px;">
  <div class="field">
    <label>Nouveau mot de passe</label>
    <input type="password" name="new_password" placeholder="Nouveau mot de passe">
  </div>

  <div class="field">
    <label>Confirmer mot de passe</label>
    <input type="password" name="confirm_password" placeholder="Confirmer mot de passe">
  </div>

</div>
        <input type="password" name="password" placeholder="••••••••">
      </div>
      <div class="forgot">
  <a href="#" onclick="showReset(event)">Mot de passe oublié ?</a></div>
      <button type="submit" class="btn">Se connecter</button>
    </form>
  </div>
<script>
function showReset(e) {
    e.preventDefault();
    document.getElementById("resetBox").style.display = "block";
}
</script>
</body>
</html>



