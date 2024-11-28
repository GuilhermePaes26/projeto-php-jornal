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

$sql = "SELECT * FROM posts WHERE status = 'aprovado' ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <title>Notícias</title>
</head>
<body>
<div class="header">
    <img src="./img/bola.png" alt="Logo">
    Football News Portal
    <img src="./img/bola.png" alt="Logo">
</div>

<div class="action-links">
    <a href="./cadastro.php">Cadastre-se</a>
    <a href="./login.php">Entrar</a>
    <a href="./logout.php">Sair</a>
</div>

<div class="grid-container">
    <?php if (empty($posts)) : ?>
        <p>Não há notícias publicadas ainda.</p>
    <?php else : ?>
        <?php foreach ($posts as $post) : ?>
            <div class="card">
                <?php if (!empty($post['image'])) : ?>
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Imagem">
                <?php else : ?>
                    <img src="placeholder.jpg" alt="Imagem genérica">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
