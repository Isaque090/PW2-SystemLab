<?php

include_once('inc/config.php');
session_start();
if (!isset($_SESSION['login'])) {
    header('location:login.php');
    exit;
} else if ($_SESSION['login'] == false) {
    header('location:login.php');
    exit;
}


$sql = "SELECT * FROM  turma WHERE status = 'ativo'";
$result = $conexao->query($sql);


if (isset($_POST['cadastrar'])) {

    $ds_curso = $_POST['ds_curso'];


    $enviar = $conexao->prepare('insert into turma (ds_curso) 
VALUES (?)');
    $enviar->bind_param('s', $ds_curso);
    if ($enviar->execute()) {

        $_SESSION['mensagemJs'] = "alert(' turma Cadastrado com Sucesso')";
    } else {

        $_SESSION['mensagemJs'] = "alert('ERRO ao cadastrar  turma')";
    }
    header('location: turmas.php');
    exit;
}

if (isset($_POST['editar'])) {
    $curso = $_POST['ds_curso'];
    $id = $_POST['id'];
    $status = $_POST['status'];
    $editar = $conexao->prepare('UPDATE turma SET ds_curso = ?,status=? WHERE id = ?');
    $editar->bind_param('ssi', $curso, $status, $id);

    if ($editar->execute()) {


        $_SESSION['mensagemJs'] = "alert('turma Editado com Sucesso')";
    } else {


        $_SESSION['mensagemJs'] = "alert('ERRO ao Editar turma')";
    }
    header('location:turmas.php');
    exit;
}


if (isset($_POST['excluir'])) {
    $id = $_POST['id'];
    $status = "inativo";
    $excluir = $conexao->prepare('UPDATE turma SET status = ? where id=?');
    $excluir->bind_param('si', $status, $id);
    if ($excluir->execute()) {
        $_SESSION['mensagemJs'] = "alert('turma Excluido com Sucesso')";
    } else {
        $_SESSION['mensagemJs'] = "alert('ERRO ao Excluir turma')";
    }
    header('location:turmas.php');
    exit;
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


    header("Location: turmas.php");
    exit;
}
if ($tema == "claro") {
    $arquivo = "css/claro/tema_claro.css";
} else {
    $arquivo = "css/escuro/tema_escuro.css";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turmas</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

            </div>
            <a href="logout.php" class="btn-sair">Sair</a>
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


            <a href="turmas.php" class="ativo">

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
        <div class="cabecalho-pagina">
            <div>
                <h1>Turmas</h1>
                <p>Gerencie os Turmas do sistema.</p>
            </div>
            <button type="button" class="btn btn-adicionar btn-sm" data-toggle="modal" data-target="#modalNovoTurmas">
                + Nova Turma
            </button>
        </div>

        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Código</th>
                    <th>Curso</th>
                    <th>Status</th>
                    <th width="180">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($linha = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($linha['id']) ?></td>
                            <td><?= htmlspecialchars($linha['ds_curso']) ?></td>
                            <td><?= htmlspecialchars($linha['status']) ?></td>
                            <td>
                                <div class="d-flex">
                                    <form action="turmas.php" method="post" class="mr-2">
                                        <input type="hidden" name="id" value="<?= $linha['id'] ?>">
                                        <button type="submit" name="excluir" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                    </form>
                                    <button type="button" class="btn btn-warning btn-editar btn-sm"
                                        data-id="<?= $linha['id'] ?>" data-toggle="modal"
                                        data-curso="<?= htmlspecialchars($linha['ds_curso']) ?>"
                                        data-status="<?= htmlspecialchars($linha['status']) ?>" data-target="#modalEditarTurma">
                                        Editar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Nenhuma Turma cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <div class="modal fade" id="modalNovoTurmas" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="turmas.php">
                    <div class="modal-header">
                        <h5 class="modal-title">Nova turma</h5>

                    </div>
                    <div class="modal-body">


                        <div class="form-group">
                            <label>Curso</label>
                            <input type="text" class="form-control" name="ds_curso" required>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="cadastrar" class="btn btn-primary">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalEditarTurma" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="turmas.php">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Turma</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editar_id">
                        <div class="form-group">
                            <label>Curso</label>
                            <input type="text" class="form-control" name="ds_curso" id="editar_curso" required>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" id="editar_status" required>
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="editar" class="btn btn-primary">Salvar alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const botoesEditar = document.querySelectorAll(".btn-editar");
            botoesEditar.forEach(function (botao) {
                botao.addEventListener("click", function () {
                    document.getElementById("editar_id").value = this.dataset.id;
                    document.getElementById("editar_curso").value = this.dataset.curso;
                    document.getElementById("editar_status").value = this.dataset.status;
                });
            });
        });
        <?php if (isset($_SESSION['mensagemJs'])) {
            echo $_SESSION['mensagemJs'];
            unset($_SESSION['mensagemJs']);
        } ?>
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
</body>

</html>