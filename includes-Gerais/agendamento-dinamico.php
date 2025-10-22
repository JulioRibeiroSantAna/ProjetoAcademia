<?php
/**
 * ARQUIVO: agendamento-dinamico.php
 * Sistema de agendamento de consultas
 * Usuário escolhe: profissional, data e hora
 * Verifica se horário está livre antes de agendar
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connection.php';

$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
$id_usuario = $_SESSION['id_usuario'] ?? null;
$msg = '';

// Só usuários logados podem agendar
if (!$id_usuario) {
    header('Location: ../Autenticacao/login.php');
    exit();
}

// Processa formulário de agendamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profissional = $_POST['profissional'] ?? '';
    $data = $_POST['data'] ?? '';
    $hora = $_POST['hora'] ?? '';
    
    if ($profissional && $data && $hora) {
        // Junta data e hora: 2024-10-22 14:00:00
        $data_completa = $data . ' ' . $hora . ':00';
        
        try {
            // Verifica se o horário já está ocupado
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM agendamentos WHERE id_nutricionista = ? AND data_hora = ?");
            $stmt->execute([$profissional, $data_completa]);
            
            if ($stmt->fetchColumn() == 0) {
                // Horário livre, salva agendamento
                $stmt = $pdo->prepare("INSERT INTO agendamentos (id_nutricionista, id_usuario, data_hora) VALUES (?, ?, ?)");
                if ($stmt->execute([$profissional, $id_usuario, $data_completa])) {
                    $msg = 'Consulta agendada com sucesso!';
                } else {
                    $msg = 'Erro ao agendar consulta!';
                }
            } else {
                $msg = 'Horário já ocupado! Escolha outro horário.';
            }
        } catch (PDOException $e) {
            $msg = 'Erro no sistema. Tente novamente.';
            error_log("Erro agendamento: " . $e->getMessage());
        }
    } else {
        $msg = 'Preencha todos os campos!';
    }
}

// Buscar profissionais disponíveis
try {
    $stmt = $pdo->query("SELECT * FROM profissionais ORDER BY nome");
    $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $profissionais = [];
}

// Buscar dados do usuário
$usuario = [];
try {
    $stmt = $pdo->prepare("SELECT nome, email, telefone FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    $usuario = [];
}
?>

<div class="mef-card">
    <h1 class="text-center mb-4">AGENDAMENTO</h1>
    <p class="text-center lead mb-4">Preencha os campos para Agendar sua Consulta</p>
    
    <?php if ($msg): ?>
        <div class="alert alert-<?php echo strpos($msg, 'sucesso') !== false ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <!-- Dados do usuário -->
        <h3 class="mb-3">Seus Dados</h3>
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['nome'] ?? ''); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email'] ?? ''); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Telefone</label>
            <input type="tel" class="form-control" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" readonly>
        </div>
        
        <!-- Agendamento -->
        <h3 class="mb-3">Agendamento</h3>
        <div class="mb-3">
            <label class="form-label">Profissional</label>
            <select class="form-select" name="profissional" required>
                <option value="">Escolha um profissional</option>
                <?php foreach ($profissionais as $prof): ?>
                    <option value="<?php echo $prof['id']; ?>">
                        <?php echo htmlspecialchars($prof['nome']); ?> - <?php echo htmlspecialchars($prof['especialidade']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Data</label>
            <input type="date" class="form-control" name="data" min="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Horário</label>
            <select class="form-select" name="hora" required>
                <option value="">Escolha um horário</option>
                <option value="08:00">08:00</option>
                <option value="09:00">09:00</option>
                <option value="10:00">10:00</option>
                <option value="14:00">14:00</option>
                <option value="15:00">15:00</option>
                <option value="16:00">16:00</option>
            </select>
        </div>
        
        <button type="submit" class="btn mef-btn-primary w-100">AGENDAR CONSULTA</button>
    </form>
</div>