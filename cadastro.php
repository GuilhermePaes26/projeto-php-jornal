<?php
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
    <title>Cadastro</title>
</head>
<body>
    <h1>Formulário de Cadastro</h1>

    <?php if (isset($mensagem)) : ?>
        <p><?php echo $mensagem; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="nome">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="email">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>

        <label for="type">Tipo:</label><br>
        <input type="text" id="type" name="type" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>
    <br><br>
    <a href="index.php">Home</a>
    <h3>Já possui o cadastro?<h3>
    <a href="./login.php">Entrar</a>
</body>
</html>
