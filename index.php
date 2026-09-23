<?php
session_start();
include_once('inc/config.php');
if (!isset($_SESSION['login'])) {
    header('location:login.php');
} else if ($_SESSION['login'] == false) {
    header('location:login.php');
}


$tema = $_COOKIE['tema'] ?? "claro";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['tema'])) {

        if ($tema == "claro") {

            $tema = "escuro";
        } else {
            $tema = "claro";

        }

        setcookie("tema", $tema, time() + 36000, "/");
    }


    header("Location:index.php");
    exit;
}
if ($tema == "claro") {
    $arquivo = "css/claro/style.css";
} else {
    $arquivo = "css/escuro/style.css";
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link href=<?php echo $arquivo ?> rel="stylesheet">

</head>

<body>
    <nav class="navbar">
        <div class="logo">Reserva</div>
        <form method="post" class="botao-tema">

            <button class="btn btn-tema" type="submit" name="tema" id="themeButton">
                <?php if ($tema == "claro"): ?>
                    <i class="bi bi-moon"></i>
                <?php else: ?>
                    <i class="bi bi-sun"></i>
                <?php endif; ?>
            </button>

        </form>
        <div class="usuario">
            <div class="usuario-info">
                <div class="usuario-nome">
                    <?php echo $_SESSION['nome'] ?? 'Usuário'; ?>
                </div>
                <div class="usuario-cargo">
                    <?php echo $_SESSION['cargo'] ?? 'Funcionário'; ?>
                </div>
            </div>
            <a href="logout.php" class="btn-sair">Sair</a>
        </div>


    </nav>

    <aside class="sidebar">
        <div class="menu">
            <div class="menu-titulo">Menu
            </div>
            <a href="index.php" class="ativo">

                <span>Dashboard</span>
            </a>
            <a href="professores.php">

                <span>Professores</span>
            </a>


            <a href="turmas.php">

                <span>Turmas</span>
            </a>

            <a href="laboratorios.php">

                <span>Laboratorios</span>
            </a>
            
            <a href="Reservar.php">

                <span>Reservar</span>
            </a>
        </div>
    </aside>

    <session>
        < </session>
</body>

</html>