<?php
session_start();
include_once('inc/config.php');
$nExiste = "";
if (isset($_POST['enviar'])) {
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $sql = 'Select * from  login where email =? and senha=?';
    $smt = $conexao->prepare($sql);
    $smt->bind_param('ss', $login, $senha);
    $smt->execute();
    $result = $smt->get_result();


    if ($result->num_rows > 0) {
        $_SESSION['login'] = true;
        header('location:index.php');
    } else {
        $nExiste = "Email ou senha Incorreta";
    }
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


    header("Location: login.php");
    exit;
}
if ($tema == "claro") {
    $arquivo = "css/claro/login.css";
} else {
    $arquivo = "css/escuro/login.css";
}
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href=<?php echo $arquivo ?> rel="stylesheet">

</head>

<body class="d-flex align-items-center justify-content-center">
<form method="post" class="botao-tema">

        <button class="btn btn-tema" type="submit" name="tema" id="themeButton">
            <?php if ($tema == "claro"): ?>
                <i class="bi bi-moon"></i>
            <?php else: ?>
                <i class="bi bi-sun"></i>
            <?php endif; ?>
        </button>

    </form>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-6 col-lg-4">

                <div class="card login-card shadow-lg p-4">
                    <div class="card-body">
                         <h2 class="text-center text-white mb-1">Sistema De Reserva </h2>
                        <p class="text-center text-secondary mb-4">Sistema para a reserva de labs</p>

                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Login</label>
                                <input type="email" class="form-control form-control-lg" name="login"
                                    placeholder="seu@email.com" required>
                            </div>

                            <div class="mb-4">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control form-control-lg" name="senha"
                                    placeholder="Senha" required>
                            </div>


                            <button type="submit" name="enviar" class="btn btn-primary btn-lg w-100">
                                Entrar
                            </button>

                            <?php if (!empty($nExiste)): ?>
                                <div class="alert alert-danger alert-dismissible fade show mt-3 mb-0" role="alert">
                                    <?php echo $nExiste; ?>
                                    <button type="button" class="btn-close ButtonAlert" data-bs-dismiss="alert"
                                        aria-label="Fechar"></button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        

    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>