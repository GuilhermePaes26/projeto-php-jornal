    <?php
    session_start();
    $host = "localhost";
    $dbname = "jornal";
    $username = "root";
    $password = "";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erro de conexão: " . $e->getMessage());
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $sql = "SELECT * FROM users WHERE email_user = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['password_user'])) {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['email'] = $user['email_user'];
            $_SESSION['tipo'] = $user['type_user'];

            if ($user['type_user'] == 'adm') {
                header('Location: admin.php'); 
            } else {
                header('Location: escritor.php'); 
            }
            exit();
        } else {
            $erro = "Usuário ou senha inválidos.";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>

        <?php if (isset($erro)) : ?>
            <p style="color: red;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="senha">Senha:</label><br>
            <input type="password" id="senha" name="senha" required><br><br>

            <button type="submit">Entrar</button>
        </form>
        <br><br>
    <a href="index.php">Home</a>
        <h3>Não possui o cadastro?<h3>
        <a href="./cadastro.php">Cadastre-se</a>
    </body>
    </html>
