<?php
session_start();

// Verifica se o usuário está logado e se é escritor
if (!isset($_SESSION['email']) || $_SESSION['tipo'] !== 'escritor') {
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $id_user = $_SESSION['id_user']; 

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = 'uploads/' . basename($_FILES['image']['name']);
    
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
        } else {
            $image = null; 
        }
    } else {
        $image = null; 
    }
    $sql = "INSERT INTO posts (id_user, title, content, image) VALUES (:id_user, :title, :content, :image)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_user' => $id_user,
        ':title' => $title,
        ':content' => $content,
        ':image' => $image
    ]);

    $mensagem = "Notícia criada com sucesso e aguardando aprovação!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Notícia</title>
</head>
<body>
    <h1>Criar Notícia</h1>

    <?php if (isset($mensagem)) : ?>
        <p><?php echo $mensagem; ?></p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="title">Título:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="content">Conteúdo:</label><br>
        <textarea id="content" name="content" rows="5" required></textarea><br><br>

        <label for="image">Imagem (opcional):</label><br>
        <input type="file" id="image" name="image"><br><br>

        <button type="submit">Criar Notícia</button>
    </form>
    <br><br>
    <a href="logout.php">Sair</a>
</body>
</html>
