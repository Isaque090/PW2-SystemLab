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


$sql = "SELECT * FROM professores  WHERE status = 'ativo'";
$result = $conexao->query($sql);


if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nm_professor'];
    $ds_email = $_POST['ds_email'];
    $email = $_POST['ds_email'];
    $matricula = $_POST['matricula'];

    $enviar = $conexao->prepare('insert into professores (nm_professor, ds_email, matricula ) 
VALUES (?, ?, ?)');
    $enviar->bind_param('sss', $nome, $ds_email, $matricula);
    if ($enviar->execute()) {

        $_SESSION['mensagemJs'] = "alert('Professores Cadastrado com Sucesso')";
    } else {

        $_SESSION['mensagemJs'] = "alert('ERRO ao cadstrar Professores')";
    }
    header('location:professores.php');
    exit;
}

if (isset($_POST['editar'])) {
    $nome = $_POST['nm_professor'];
    $email = $_POST['ds_email'];
    $matricula = $_POST['ds_matricula'];
    $status = $_POST['status'];
    $id = $_POST['id'];

    $editar = $conexao->prepare('UPDATE Professores SET nm_professor = ?, ds_email = ?, matricula = ?, status = ? WHERE id = ?');
    $editar->bind_param('ssssi', $nome, $email, $matricula, $status, $id);

    if ($editar->execute()) {


        $_SESSION['mensagemJs'] = "alert('Professores Editado com Sucesso')";
    } else {


        $_SESSION['mensagemJs'] = "alert('ERRO ao Editar Professores')";
    }
    header('location:professores.php');
    exit;
}


if (isset($_POST['excluir'])) {
    $id = $_POST['id'];
    $status = "inativo";
    $excluir = $conexao->prepare('UPDATE Professores SET status = ? where id=?');
    $excluir->bind_param('si', $status, $id);
    if ($excluir->execute()) {
        $_SESSION['mensagemJs'] = "alert('Professores Excluido com Sucesso')";
    } else {
        $_SESSION['mensagemJs'] = "alert('ERRO ao Excluir Professores')";
    }
    header('location:professores.php');
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


    header("Location: professores.php");
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
    <title>Professores</title>

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
            <a href="professores.php" class="ativo">
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
        <div class="cabecalho-pagina">
            <div>
                <h1>Professores</h1>
                <p>Gerencie os Professores do sistema.</p>
            </div>
            <button type="button" class="btn btn-adicionar btn-sm" data-toggle="modal"
                data-target="#modalNovoProfessores">
                + Novo Professor
            </button>
        </div>

        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Matricula</th>
                    <th>Status</th>
                    <th width="180">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($linha = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($linha['id']) ?></td>
                            <td><?= htmlspecialchars($linha['nm_professor']) ?></td>
                            <td><?= htmlspecialchars($linha['ds_email']) ?></td>
                            <td><?= htmlspecialchars($linha['matricula']) ?></td>
                            <td><?= htmlspecialchars($linha['status']) ?></td>
                            <td>
                                <div class="d-flex">
                                    <form action="professores.php" method="post" class="mr-2">
                                        <input type="hidden" name="id" value="<?= $linha['id'] ?>">
                                        <button type="submit" name="excluir" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                    </form>
                                    <button type="button" class="btn btn-warning btn-editar btn-sm"
                                        data-id="<?= $linha['id'] ?>"
                                        data-nome="<?= htmlspecialchars($linha['nm_professor']) ?>"
                                        data-email="<?= $linha['ds_email'] ?>"
                                        data-status="<?= htmlspecialchars($linha['status']) ?>" data
                                        data-matricula="<?= htmlspecialchars($linha['matricula']) ?>" data-toggle="modal"
                                        data-target="#modalEditarProfessores">
                                        Editar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Nenhum Professor cadastrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <div class="modal fade" id="modalNovoProfessores" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="professores.php">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Professor</h5>

                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nm_professor" required>
                        </div>

                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" class="form-control" name="ds_email" required>
                        </div>

                        <div class="form-group">
                            <label>Matricula</label>
                            <input type="number" class="form-control" name="matricula" required>
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


    <div class="modal fade" id="modalEditarProfessores" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" action="professores.php">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar Professor</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editar_id">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="nm_professor" id="editar_nome" required>
                        </div>
                        <div class="form-group">
                            <label>Matricula</label>
                            <input type="number" class="form-control" name="ds_matricula" id="editar_matricula"
                                required>
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" class="form-control" name="ds_email" id="editar_email" required>
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
                    document.getElementById("editar_nome").value = this.dataset.nome;
                    document.getElementById("editar_matricula").value = this.dataset.matricula;
                    document.getElementById("editar_email").value = this.dataset.email;
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