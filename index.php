<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>EneStage</title>

<style>

/* ===== RESET ===== */
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: radial-gradient(circle at top, #0f172a, #020617);
    color: white;
    overflow: hidden;
}

/* ===== CONTAINER ===== */
.container {
    height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 2;
    position: relative;
}

/* ===== TITLE ===== */
h1 {
    font-size: 48px;
    margin-bottom: 40px;
    text-align: center;
    animation: fadeIn 1s ease;
}

/* ===== BUTTONS ===== */
.buttons {
    display: flex;
    gap: 20px;
}

.btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 25px;
    border-radius: 12px;
    text-decoration: none;
    color: white;
    font-size: 16px;
    transition: 0.3s;
}

.btn-login {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
}

.btn:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 10px 30px rgba(59,130,246,0.5);
}

/* ===== SVG ICON ===== */
.btn svg {
    width: 20px;
    height: 20px;
    fill: white;
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ===== FLOATING PARTICLES ===== */
.particle {
    position: absolute;
    width: 6px;
    height: 6px;
    background: rgba(59,130,246,0.4);
    border-radius: 50%;
    animation: float 10s infinite linear;
}

@keyframes float {
    from { transform: translateY(100vh); }
    to { transform: translateY(-10vh); }
}

/* ===== RUNNING CHARACTERS ===== */
.character {
    position: absolute;
    bottom: 50px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    animation: run 6s linear infinite;
}

.character.left {
    background: #ef4444;
    left: -100px;
}

.character.right {
    background: #22c55e;
    right: -100px;
    animation-direction: reverse;
}

@keyframes run {
    from { transform: translateX(0); }
    to { transform: translateX(120vw); }
}

/* glow effect */
.character::after {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: inherit;
    border-radius: 50%;
    filter: blur(20px);
    opacity: 0.6;
}

</style>

</head>

<body>

<!-- PARTICLES -->
<?php for($i=0; $i<30; $i++): ?>
<div class="particle" style="
    left: <?= rand(0,100) ?>%;
    animation-duration: <?= rand(5,15) ?>s;
    animation-delay: <?= rand(0,10) ?>s;
"></div>
<?php endfor; ?>

<!-- CHARACTERS -->
<div class="character left"></div>
<div class="character right"></div>

<!-- MAIN CONTENT -->
<div class="container">

    <h1>Bienvenue sur le portail suivie de stage etudiant : EneStage</h1>

    <div class="buttons">

        <a href="auth/login.php" class="btn btn-login">
            <!-- LOGIN ICON -->
            <svg viewBox="0 0 24 24">
                <path d="M10 17l5-5-5-5v10zm-8 4h12v-2h-12v2zm0-18v2h12v-2h-12z"/>
            </svg>
            Se connecter
        </a>
</div>

</div>

</body>
</html>