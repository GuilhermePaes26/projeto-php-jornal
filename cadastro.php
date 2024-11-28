<?php
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

function cadastrar($email, $senha, $type) {
    global $pdo;

    $sql = "INSERT INTO users (email_user, password_user, type_user) VALUES (:email, :senha, :type)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':email' => $email,
        ':senha' => password_hash($senha, PASSWORD_DEFAULT),
        ':type' => $type
    ]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $type = $_POST['type'];

    try {
        cadastrar($email, $senha, $type);
        $mensagem = "Cadastro realizado com sucesso!";
    } catch (Exception $e) {
        $mensagem = "Erro ao cadastrar: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/cadastro.css">
    <title>Cadastro</title>
</head>
<body>
    <?php if (isset($mensagem)) : ?>
        <p><?php echo $mensagem; ?></p>
    <?php endif; ?>

    <div class="container">
    <div class="header">
        <img src="./img/bola.png" alt="Logo">
        <h1>Cadastro</h1>
    </div>
    <?php if (isset($mensagem)) : ?>
        <p><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>
    <form action="" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        
        <label for="type">Tipo:</label>
        <input type="text" id="type" name="type" required>
        
        <button type="submit">Cadastrar</button>
    </form>
    <div class="footer">
        <p>Já possui cadastro? <a href="./login.php">Entrar</a></p>
        <p><a href="index.php">Home</a></p>
    </div>
</div>
</body>
</html>
