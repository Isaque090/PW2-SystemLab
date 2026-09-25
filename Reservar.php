<?php

include_once('inc/config.php');
include_once('inc/trocar_tema.php');
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == false) {
    header('location:login.php');
    exit;
}


if (isset($_POST['cadastrar'])) {
    $cd_professor = $_POST['cd_professor'];
    $cd_turma = $_POST['cd_turma'];
    $cd_lab = $_POST['cd_lab'];
    $horario_inicio = $_POST['horario_inicio'];
    $horario_termino = $_POST['horario_termino'];



    if ($horario_inicio >= $horario_termino) {
        $_SESSION['mensagemJs'] =
            "alert('O horário de término deve ser maior que o horário de início.');";
        header('location: Reservar.php');
        exit;
    }


    $sql_verifica = "SELECT id FROM reservas WHERE cd_lab = ? AND horario_inicio < ? AND horario_termino > ?";
    $verifica = $conexao->prepare($sql_verifica);
    $verifica->bind_param('iss', $cd_lab, $horario_termino, $horario_inicio);
    $verifica->execute();
    $resultado_verifica = $verifica->get_result();

    if ($resultado_verifica->num_rows > 0) {

        $_SESSION['mensagemJs'] = "alert('Este laboratório já está reservado nesse horário.');";
        header('location: Reservar.php');
        exit;
    }

    $sql = "INSERT INTO reservas (cd_professor, cd_turma, cd_lab, horario_inicio, horario_termino) VALUES (?, ?, ?, ?, ?)";
    $enviar = $conexao->prepare($sql);
    $enviar->bind_param('iiiss', $cd_professor, $cd_turma, $cd_lab, $horario_inicio, $horario_termino);

    if ($enviar->execute()) {
        $sql_status = "UPDATE lab SET status = 'Reservado' WHERE id = ?";
        $status = $conexao->prepare($sql_status);
        $status->bind_param('i', $cd_lab);
        $status->execute();
        $_SESSION['mensagemJs'] = "alert('Reserva cadastrada com sucesso!');";
    } else {
        $_SESSION['mensagemJs'] = "alert('Erro ao cadastrar reserva.');";
    }
    header('location: Reservar.php');
    exit;
}

$sql = "SELECT reservas.id,nm_professor AS professor,ds_curso AS turma,numero_lab
        AS laboratorio,horario_inicio,horario_termino FROM reservas 
        INNER JOIN professores ON reservas.cd_professor = professores.id INNER JOIN turma ON reservas.cd_turma = turma.id
        INNER JOIN lab ON reservas.cd_lab = lab.id ORDER BY reservas.horario_inicio DESC";
$result = $conexao->query($sql);


$professores = $conexao->query(
    "SELECT * FROM professores ORDER BY nm_professor"
);

$turmas = $conexao->query(
    "SELECT * FROM turma ORDER BY ds_curso"
);

$laboratorios = $conexao->query(
    "SELECT * FROM lab ORDER BY numero_lab"
);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $arquivo; ?>" rel="stylesheet">
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            Reserva
        </div>
        <form method="post" class="botao-tema">
            <button class="btn btn-tema" type="submit"value="Reservar" name="tema" id="themeButton">
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
                    <?php
                    echo $_SESSION['nome'] ?? 'Usuário';
                    ?>
                </div>
            </div>
            <a href="logout.php" class="btn-sair">
                Sair
            </a>
        </div>
    </nav>

    <aside class="sidebar">
        <div class="menu">
            <div class="menu-titulo">Menu</div>
            <a href="index.php">
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

            <a href="Reservar.php"  class="ativo">

                <span>Reservar</span>
            </a>
        </div>
    </aside>

    <main class="conteudo">
        <div class="cabecalho-pagina">
            <div>
                <h1>Reservas</h1>
                <p>Gerencie as reservas dos laboratórios.</p>
            </div>

            <button type="button" class="btn btn-adicionar btn-sm" data-toggle="modal" data-target="#modalNovaReserva">
                + Nova Reserva
            </button>
        </div>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Professor</th>
                    <th>Turma</th>
                    <th>Laboratório</th>
                    <th>Início</th>
                    <th>Término</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($linha = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($linha['id']) ?></td>
                            <td><?= htmlspecialchars($linha['professor']) ?></td>
                            <td><?= htmlspecialchars($linha['turma']) ?></td>
                            <td>Laboratório<?= htmlspecialchars($linha['laboratorio']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($linha['horario_inicio'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($linha['horario_termino'])) ?> </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">
                            Nenhuma reserva cadastrada.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <div class="modal fade" id="modalNovaReserva" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="Reservar.php">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Nova Reserva
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label>Professor</label>
                            <select name="cd_professor" class="form-control" required>
                                <option value="">Selecione o professor</option>

                                <?php while ($professor = $professores->fetch_assoc()): ?>
                                    <option value="<?= $professor['id'] ?>">
                                        <?= htmlspecialchars($professor['nm_professor']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>
                                Turma
                            </label>

                            <select name="cd_turma" class="form-control" required>
                                <option value="">Selecione a turma</option>

                                <?php while ($turma = $turmas->fetch_assoc()): ?>
                                    <option value="<?= $turma['id'] ?>"><?= htmlspecialchars($turma['ds_curso']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Laboratório</label>


                            <select name="cd_lab" class="form-control" required>
                                <option value="">Selecione o laboratório</option>

                                <?php while ($lab = $laboratorios->fetch_assoc()): ?>
                                    <option value="<?= $lab['id'] ?>">Laboratório<?= htmlspecialchars($lab['numero_lab']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>Horário de início
                            </label>
                            <input type="datetime-local" name="horario_inicio" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Horário de término</label>
                            <input type="datetime-local" name="horario_termino" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" name="cadastrar" class="btn btn-primary">
                            Reservar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        <?php
        if (isset($_SESSION['mensagemJs'])) {
            echo $_SESSION['mensagemJs'];
            unset($_SESSION['mensagemJs']);
        }
        ?>
    </script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
</body>

</html>