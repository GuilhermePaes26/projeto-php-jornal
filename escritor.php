<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['tipo'] !== 'escritor') {
    header('Location: login.php');
    exit();
}

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
    <link rel="stylesheet" href="./css/escritor.css">
    <title>Criar Notícia</title>
</head>
<body>
<div class="container">
    <h1>Criar Notícia</h1>

    <?php if (isset($mensagem)) : ?>
        <p><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <label for="title">Título:</label>
        <input type="text" id="title" name="title" required>
        
        <label for="content">Conteúdo:</label>
        <textarea id="content" name="content" rows="5" required></textarea>
        
        <label for="image">Imagem (opcional):</label>
        <input type="file" id="image" name="image">
        
        <button type="submit">Criar Notícia</button>
    </form>

    <div class="footer-links">
        <a href="index.php">Home</a>
        <br><br>
        <a href="logout.php">Sair</a>
    </div>
</div>
</body>
</html>
