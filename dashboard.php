<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "usuario", "senha", "link_manager");

// Verifica conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Função para adicionar um novo link
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_link'])) {
    $nome = $_POST['nome'];
    $validade = $_POST['validade'];
    $link = "https://seudominio.com/link_" . uniqid() . ".php";

    // Insere no banco de dados
    $sql = "INSERT INTO links (nome, link, validade) VALUES ('$nome', '$link', '$validade')";
    if ($conn->query($sql) === TRUE) {
        echo "Link criado com sucesso: <a href='$link'>$link</a>";
    } else {
        echo "Erro ao criar link: " . $conn->error;
    }
}

// Função para verificar e atualizar status
$sqlUpdate = "UPDATE links SET status='expirado' WHERE validade < CURDATE()";
$conn->query($sqlUpdate);

// Consulta para listar os links
$sql = "SELECT * FROM links";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Links</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
    <h1>Gerenciador de Links</h1>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required>
        </div>
        <div class="form-group">
            <label for="validade">Data de Validade:</label>
            <input type="date" name="validade" id="validade" required>
        </div>
        <button type="submit" name="add_link">Gerar Link</button>
    </form>

    <h2>Lista de Links</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Link</th>
                <th>Validade</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['nome']; ?></td>
                        <td><a href="<?php echo $row['link']; ?>"><?php echo $row['link']; ?></a></td>
                        <td><?php echo date("d/m/Y", strtotime($row['validade'])); ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhum link cadastrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
