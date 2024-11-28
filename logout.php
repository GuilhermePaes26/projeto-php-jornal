<?php
// Inicia a sessão
session_start();

// Destrói a sessão
session_destroy();

// Redireciona o usuário para a página de login
header("Location: login.php");
exit();
?>