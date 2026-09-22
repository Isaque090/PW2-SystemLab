<?php 
include_once('inc/config.php');

if(isset($_POST['enviar'])){
$login=$_POST['login'];
$senha=$_POST['senha'];
$sql='Select * from  login where email =? and senha=?';
$smt=$conexao->prepare($sql);
$smt->bind_param('ss',$login,$senha);
if($smt->num_rows>0){
    echo "conta existe";
}
else{
    echo "Conta inezistente";
}
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" action="index.php">
        <label for="">Login:</label>
        <input name="login" type="text" required >
        <label for="">Senha</label>
        <input name="senha" type="Password" required  >
        <input type="submit" name="enviar">
    </form>
</body>
</html>