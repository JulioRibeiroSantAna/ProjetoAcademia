<?php
// includes-Gerais/bate-papo-dinamico.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connection.php';

$is_admin = ($_SESSION['tipo_usuario'] === 'admin');
$msg = '';

// Criar tabela se não existir
$pdo->exec("CREATE TABLE IF NOT EXISTS profissionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    especialidade VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    foto VARCHAR(255) DEFAULT NULL
)");

// Se enviou formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin) {
    $acao = $_POST['acao'];
    
    if ($acao === 'adicionar') {
        $nome = $_POST['nome'];
        $especialidade = $_POST['especialidade'];
        $descricao = $_POST['descricao'];
        $foto = '';
        
        // Upload de foto
        if ($_FILES['foto']['error'] === 0) {
            $pasta = __DIR__ . '/../uploads/';
            if (!is_dir($pasta)) mkdir($pasta, 0777, true);
            
            $nome_arquivo = time() . '_' . $_FILES['foto']['name'];
            move_uploaded_file($_FILES['foto']['tmp_name'], $pasta . $nome_arquivo);
            $foto = 'uploads/' . $nome_arquivo;
        }
        
        $stmt = $pdo->prepare("INSERT INTO profissionais (nome, especialidade, descricao, foto) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $especialidade, $descricao, $foto]);
        $msg = 'Profissional adicionado!';
    }
    
    if ($acao === 'excluir') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM profissionais WHERE id = ?");
        $stmt->execute([$id]);
        $msg = 'Profissional excluído!';
    }
}

// Buscar profissionais
$stmt = $pdo->query("SELECT * FROM profissionais ORDER BY nome");
$profissionais = $stmt->fetchAll();
?>

<div class="mef-card">
    <?php if ($msg): ?>
        <div class="alert alert-success"><?php echo $msg; ?></div>
    <?php endif; ?>
    
    <?php if ($is_admin): ?>
        <div class="d-flex justify-content-between mb-4">
            <h1>GERENCIAR PROFISSIONAIS</h1>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
                Adicionar Profissional
            </button>
        </div>
    <?php else: ?>
        <h1>BATE-PAPO</h1>
        <p>Escolha um profissional para conversar</p>
    <?php endif; ?>

    <!-- Lista de profissionais -->
    <div class="row">
        <?php foreach ($profissionais as $prof): ?>
        <div class="col-md-4 mb-4">
            <div class="professional-card">
                <img src="<?php echo $prof['foto'] ? '../' . $prof['foto'] : '../imagens-teste/goku.jpg'; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <?php if ($is_admin): ?>
                        <button class="btn btn-sm btn-danger float-end" onclick="excluir(<?php echo $prof['id']; ?>)">Excluir</button>
                    <?php endif; ?>
                    
                    <h5><?php echo $prof['nome']; ?></h5>
                    <span class="badge bg-primary"><?php echo $prof['especialidade']; ?></span>
                    <p><?php echo $prof['descricao']; ?></p>
                    
                    <?php if (!$is_admin): ?>
                        <a href="<?php echo $_SESSION['tipo_usuario'] === 'admin' ? '../AdmLogado/conversa-Adm.php' : '../UsuarioLogado/conversa.php'; ?>?id=<?php echo $prof['id']; ?>" class="btn mef-btn-primary">Conversar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($is_admin): ?>
<!-- Modal para adicionar -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5>Adicionar Profissional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="acao" value="adicionar">
                    <div class="mb-3">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Especialidade</label>
                        <select class="form-control" name="especialidade" required>
                            <option value="Nutricionista">Nutricionista</option>
                            <option value="Psicólogo">Psicólogo</option>
                            <option value="Personal Trainer">Personal Trainer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" class="form-control" name="foto" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function excluir(id) {
    if (confirm('Tem certeza?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="acao" value="excluir">
            <input type="hidden" name="id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php endif; ?>