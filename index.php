<?php
session_start();
include_once('inc/config.php');
if (!isset($_SESSION['login'])) {
    header('location:login.php');
} else if ($_SESSION['login'] == false) {
    header('location:login.php');
}



$sql_total = "SELECT COUNT(*) AS total FROM lab";
$resultado = $conexao->query($sql_total);
$total_labs = $resultado->fetch_assoc()['total'];



$sql_reservados = "SELECT COUNT(DISTINCT cd_lab) AS total FROM reservas WHERE horario_inicio <= NOW() AND horario_termino >= NOW()";
$result_reservados = $conexao->query($sql_reservados);
$total_reservados = $result_reservados->fetch_assoc()['total'];

$total_liberados = $total_labs - $total_reservados;



$sql_labs = "SELECT lab.id,lab.numero_lab,lab.status,
            reservas.horario_inicio,reservas.horario_termino,
            professores.nm_professor AS professor,turma.ds_curso AS turma
            FROM lab LEFT JOIN reservas 
    ON lab.id = reservas.cd_lab
    AND reservas.horario_inicio <= NOW()
    AND reservas.horario_termino >= NOW()
LEFT JOIN professores 
    ON reservas.cd_professor = professores.id
LEFT JOIN turma 
    ON reservas.cd_turma = turma.id
ORDER BY lab.numero_lab;";

$result_labs = $conexao->query($sql_labs);

$tema = $_COOKIE['tema'] ?? "claro";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['tema'])) {
        $redirecina = $_POST['tema'];
        if ($tema == "claro") {

            $tema = "escuro";
        } else {
            $tema = "claro";

        }

        setcookie("tema", $tema, time() + 36000, "/");

        header("Location:index.php");
        exit;
    }


}
if ($tema == "claro") {
    $arquivo = "css/claro/index.css";
} else {
    $arquivo = "css/escuro/index.css";
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

    <link href="<?php echo $arquivo ?>" rel="stylesheet">

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

    <main class="conteudo">
        <div class="container-fluid">
            <div class="mb-4">
                <h1>Dashboard</h1>
                <p> Situação dos laboratórios.</p>
            </div>
            <div class="row g-4 mb-4">

                <div class="col-md-4">
                    <div class="card card-informacao shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted">Total de Laboratórios</h6>
                                    <h2 class="fw-bold"><?= $total_labs ?></h2>
                                </div>
                                <i class="bi bi-pc-display fs-1 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-informacao shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted">Laboratórios Liberados</h6>
                                    <h2 class="fw-bold text-success"><?= $total_liberados ?></h2>
                                </div>
                                <i class="bi bi-check-circle fs-1 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-informacao shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted">Laboratórios Reservados</h6>
                                    <h2 class="fw-bold text-danger"><?= $total_reservados ?></h2>
                                </div>
                                <i class="bi bi-calendar-check fs-1 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <h3>Laboratórios</h3>
            </div>

            <div class="row g-4">

                <?php if ($result_labs->num_rows > 0): ?>
                    <?php while ($lab = $result_labs->fetch_assoc()): ?>
                        <?php

                        $reservado = !empty($lab['horario_inicio']);
                        if (!$reservado) {
                            $id = $lab['id'];
                            $sql_status = "UPDATE lab SET status = 'Liberado' WHERE id = ?";
                            $status = $conexao->prepare($sql_status);

                            $status->bind_param('i', $id);
                            $status->execute();
                        }
                        ?>

                        <div class="col-md-6 col-xl-4">
                            <div class="card card-informacao shadow-sm h-100">
                                <div class="card-header bg-transparent">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <strong class="lab">Laboratório <?= htmlspecialchars($lab['numero_lab']) ?>
                                        </strong>

                                        <?php if ($reservado): ?>
                                            <span class="badge text-bg-danger"> <i class="bi bi-circle-fill"></i> Reservado
                                            </span>
                                        <?php else: ?>

                                            <span class="badge text-bg-success"> <i class="bi bi-circle-fill"></i> Liberado
                                            </span>

                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <?php if ($reservado): ?>
                                        <div class="mb-3">
                                            <small class="text-muted">Professor
                                            </small>

                                            <div class="fw-bold"> <i class="bi bi-person"></i>
                                                <?= htmlspecialchars($lab['professor']) ?>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted">Turma
                                            </small>
                                            <div class="fw-bold"> <i class="bi bi-people"></i>
                                                <?= htmlspecialchars($lab['turma']) ?>

                                            </div>

                                        </div>

                                        <div>
                                            <small class="text-muted">Horário da reserva
                                            </small>

                                            <div class="fw-bold">
                                                <i class="bi bi-clock"></i>
                                                <?= date('H:i', strtotime($lab['horario_inicio'])) ?>
                                                -
                                                <?= date('H:i', strtotime($lab['horario_termino'])) ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-4">
                                            <i class="bi bi-check-circle-fill text-success" style="font-size: 45px;"></i>
                                            <h5 class="mt-3 lab">
                                                Laboratório disponível
                                            </h5>
                                            <p class="text-muted lab  mb-0">
                                                Nenhuma reserva acontecendo neste momento.
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-transparent text-end">
                                    <?php if ($reservado): ?>
                                        <span class="text-danger">
                                            <i class="bi bi-lock"></i>
                                            Em uso
                                        </span>
                                    <?php else: ?>
                                        <span class="text-success">
                                            <i class="bi bi-unlock"></i>
                                            Disponível
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            Nenhum laboratório cadastrado.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>