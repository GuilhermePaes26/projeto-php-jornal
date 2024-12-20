    <?php
    session_start();
    $host = "localhost";
    $dbname = "jornal";
    $username = "root";
    $password = "Gui26*";

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
        <link rel="stylesheet" href="./css/cadastro.css">
        <title>Login</title>
    </head>
    <div class="container">
    <div class="header">
        <img src="./img/bola.png" alt="Logo">
        <h1>Login</h1>
    </div>

    <?php if (isset($erro)) : ?>
        <p style="color: red;"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        
        <button type="submit">Entrar</button>
    </form>
    
    <div class="footer">
        <a href="index.php">Home</a>
        <h3>Não possui cadastro?</h3>
        <a href="./cadastro.php">Cadastre-se</a>
    </div>
</div>
    </body>
    </html>
