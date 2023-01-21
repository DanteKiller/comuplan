<?php

session_start();
ob_start();
//require_once 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComuPlan</title>

    <link rel="stylesheet" href="./css/login.css">
    <script type="text/javascript">
        localStorage.clear()
    </script>

</head>
<body class="b-login">
    <div class="d-login-c">
        <div class="d-login">
            <img src="./img/logo/logo.png" alt="Logo">

            <?php
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (!empty($dados['btnLogin'])) {
                //var_dump($dados);
                $query_user = "SELECT * FROM usuarios WHERE user =:user  LIMIT 1 ";
                //Prepara a query
                $result_user = $con->prepare($query_user);
                //Transforma em string
                $result_user->bindParam(':user', $dados['user'], PDO::PARAM_STR);
                //Executa
                $result_user->execute();

                if (($result_user) and ($result_user->rowCount() != 0)) {
                    //Ler valor
                    $row_user = $result_user->fetch(PDO::FETCH_ASSOC);
                    if ($row_user['pass'] === "") {
                        echo "Usuário logado";
                        $_SESSION['id'] = $row_user['id'];
                        $_SESSION['cod'] = $row_user['cod'];
                        $_SESSION['nome'] = $row_user['name'];
                        $_SESSION['depto'] = $row_user['depto'];
                        $_SESSION['equipe'] = $row_user['equipe'];
                        $_SESSION['pass'] = "cadastrar";
                        header("Location: master.php");
                    } elseif (password_verify($dados['pass'], $row_user['pass'])) {
                        echo "Usuário logado";
                        $_SESSION['id'] = $row_user['id'];
                        $_SESSION['nome'] = $row_user['name'];
                        $_SESSION['cod'] = $row_user['cod'];
                        $_SESSION['depto'] = $row_user['depto'];
                        $_SESSION['equipe'] = $row_user['equipe'];
                        $_SESSION['pass'] = "cadastrado";
                        header("Location: master.php");
                    } else {
                        $_SESSION['msg'] = "<p class='e-login'>Erro: Usuário ou senha inválido!</p>";
                    }
                } else {
                    $_SESSION['msg'] = "<p class='e-login'>Erro: Usuário ou senha inválido!</p>";
                }
            }
            ?>

            <form method="POST" action="">
                <input
                    class="i-login"
                    type="text"
                    name="user"
                    placeholder="Usuário"
                    value="<?php if (isset($dados['user'])) {
                        echo $dados['user'];
                           } ?>">
                <br><br>
                <input
                    class="i-login"
                    type="password"
                    name="pass"
                    placeholder="Senha"
                    value="<?php if (isset($dados['pass'])) {
                        echo $dados['pass'];
                           } ?>">
                <br><br>
                <input class="bt-login" type="submit"value="Login" name="btnLogin">
            </form>
        </div>
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>
    </div>
</body>
</html>
