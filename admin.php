<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['tipo'] !== 'adm') {
    header('Location: login.php');
    exit();
}

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

$sql = "SELECT * FROM posts WHERE status = 'pendente'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['action']) && isset($_GET['id_post'])) {
    $action = $_GET['action'];
    $id_post = $_GET['id_post'];
    $status = ($action == 'aprovar') ? 'aprovado' : 'rejeitado';

    $sql = "UPDATE posts SET status = :status WHERE id_post = :id_post";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':status' => $status, ':id_post' => $id_post]);

    header('Location: admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprovação de Notícias</title>
</head>
<body>
    <h1>Aprovar Notícias</h1>

    <?php if (empty($posts)) : ?>
        <p>Não há notícias pendentes para aprovação.</p>
        <br><br>
        <a href="logout.php">Sair</a>
    <?php else : ?>
        <ul>
        <?php foreach ($posts as $post) : ?>
            <li>
                <strong><?php echo htmlspecialchars($post['title']); ?></strong><br>
                <p><?php echo htmlspecialchars($post['content']); ?></p>

                <?php if (!empty($post['image'])) : ?>
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Imagem da notícia" width="200"><br>
                <?php endif; ?>

                <a href="?action=aprovar&id_post=<?php echo $post['id_post']; ?>">Aprovar</a> |
                <a href="?action=rejeitar&id_post=<?php echo $post['id_post']; ?>">Rejeitar</a>
            </li>
        <?php endforeach; ?>
        </ul>
        <br><br>
        <a href="logout.php">Sair</a>
    <?php endif; ?>
</body>
</html>