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
    <title>Notícias</title>
</head>
<body>
    <h1>Notícias Publicadas</h1>

    <?php if (empty($posts)) : ?>
        <a href="./cadastro.php">Cadastre-se</a>
        <br><br>
        <a href="./login.php">Entrar</a>
        <p>Não há notícias publicadas ainda.</p>
    <?php else : ?>
        <?php foreach ($posts as $post) : ?>
            <div>
                <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                
                <?php if (!empty($post['image'])) : ?>
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Imagem" width="300">
                <?php endif; ?>
                
                <hr>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
