<?php
// includes-Gerais/conversa-dinamica.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connection.php';

$is_admin = ($_SESSION['tipo_usuario'] === 'admin');
$id_usuario = $_SESSION['id_usuario'];
$profissional_id = $_GET['id'] ?? 1;

// Se enviou mensagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['mensagem']) {
    $mensagem = trim($_POST['mensagem']);
    
    if ($mensagem) {
        $stmt = $pdo->prepare("INSERT INTO mensagens (id_usuario, id_nutricionista, conteudo) VALUES (?, ?, ?)");
        $stmt->execute([$id_usuario, $profissional_id, $mensagem]);
    }
}

// Buscar profissional
$stmt = $pdo->prepare("SELECT * FROM profissionais WHERE id = ?");
$stmt->execute([$profissional_id]);
$profissional = $stmt->fetch();

// Buscar mensagens
$stmt = $pdo->prepare("
    SELECT m.*, u.nome as usuario_nome 
    FROM mensagens m 
    JOIN usuarios u ON m.id_usuario = u.id_usuario 
    WHERE m.id_nutricionista = ? 
    ORDER BY m.data_envio ASC
");
$stmt->execute([$profissional_id]);
$mensagens = $stmt->fetchAll();
?>

<div class="mef-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>BATE-PAPO</h1>
            <p>Conversando com: <?php echo htmlspecialchars($profissional['nome'] ?? 'Profissional'); ?></p>
        </div>
        <a href="<?php echo $is_admin ? '../AdmLogado/bate-papo-Adm.php' : '../UsuarioLogado/bate-papo.php'; ?>" class="btn btn-secondary">Voltar</a>
    </div>

    <!-- Área de mensagens -->
    <div id="chatMessages" style="height: 400px; overflow-y: auto; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 10px; margin-bottom: 1rem;">
        <?php if (empty($mensagens)): ?>
            <p class="text-center text-muted">Nenhuma mensagem ainda. Inicie a conversa!</p>
        <?php else: ?>
            <?php foreach ($mensagens as $msg): ?>
                <?php $e_minha = ($msg['id_usuario'] == $id_usuario); ?>
                <div class="mb-3 d-flex <?php echo $e_minha ? 'justify-content-end' : 'justify-content-start'; ?>">
                    <div class="<?php echo $e_minha ? 'bg-primary' : 'bg-secondary'; ?> text-white p-3 rounded" style="max-width: 70%;">
                        <?php if (!$e_minha): ?>
                            <small><?php echo htmlspecialchars($msg['usuario_nome']); ?></small><br>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($msg['conteudo']); ?>
                        <br><small><?php echo date('d/m H:i', strtotime($msg['data_envio'])); ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Enviar mensagem -->
    <form method="POST" class="d-flex">
        <input type="text" class="form-control me-2" name="mensagem" placeholder="Digite sua mensagem..." required>
        <button class="btn mef-btn-primary" type="submit">Enviar</button>
    </form>
</div>

<script>
// Auto scroll para a última mensagem
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});
</script>