<?php
// includes-Gerais/videos-apoio-dinamico.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connection.php';

$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin'); // CORRIGIDO
$msg = '';

// Se enviou formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin) {
    $acao = $_POST['acao'] ?? '';
    
    if ($acao === 'adicionar') {
        $titulo = trim($_POST['titulo'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $categoria = $_POST['categoria'] ?? '';
        
        if ($titulo && $url && $descricao && $categoria) {
            // Converter URL do YouTube
            if (strpos($url, 'youtube.com/watch') !== false) {
                $url = str_replace('youtube.com/watch?v=', 'youtube.com/embed/', $url);
            } elseif (strpos($url, 'youtu.be/') !== false) {
                $url = str_replace('youtu.be/', 'youtube.com/embed/', $url);
            }
            
            try {
                $stmt = $pdo->prepare("INSERT INTO videos (titulo, descricao, url, id_nutricionista) VALUES (?, ?, ?, ?)");
                $stmt->execute([$titulo, $descricao, $url, 1]);
                
                // Inserir tópico se não existir
                $stmt = $pdo->prepare("SELECT id_topico FROM topicos WHERE nome = ?");
                $stmt->execute([$categoria]);
                $topico = $stmt->fetch();
                
                if (!$topico) {
                    $stmt = $pdo->prepare("INSERT INTO topicos (nome) VALUES (?)");
                    $stmt->execute([$categoria]);
                    $topico_id = $pdo->lastInsertId();
                } else {
                    $topico_id = $topico['id_topico'];
                }
                
                // Associar vídeo ao tópico
                $video_id = $pdo->lastInsertId();
                $stmt = $pdo->prepare("INSERT INTO videos_topicos (videos_id, topicos_id) VALUES (?, ?)");
                $stmt->execute([$video_id, $topico_id]);
                
                $msg = 'Vídeo adicionado com sucesso!';
            } catch (PDOException $e) {
                $msg = 'Erro ao adicionar vídeo: ' . $e->getMessage();
            }
        } else {
            $msg = 'Preencha todos os campos!';
        }
    }
    
    if ($acao === 'excluir') {
        $id = $_POST['video_id'] ?? 0;
        try {
            // Excluir associações primeiro
            $stmt = $pdo->prepare("DELETE FROM videos_topicos WHERE videos_id = ?");
            $stmt->execute([$id]);
            
            // Excluir vídeo
            $stmt = $pdo->prepare("DELETE FROM videos WHERE id_video = ?");
            $stmt->execute([$id]);
            $msg = 'Vídeo excluído com sucesso!';
        } catch (PDOException $e) {
            $msg = 'Erro ao excluir vídeo: ' . $e->getMessage();
        }
    }
}

// Buscar vídeos com categorias
try {
    $stmt = $pdo->query("
        SELECT v.*, t.nome as categoria 
        FROM videos v 
        LEFT JOIN videos_topicos vt ON v.id_video = vt.videos_id
        LEFT JOIN topicos t ON vt.topicos_id = t.id_topico
        ORDER BY v.data_upload DESC
    ");
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $videos = [];
}
?>

<div class="mef-card">
    <?php if ($msg): ?>
        <div class="alert alert-<?php echo strpos($msg, 'sucesso') !== false ? 'success' : 'danger'; ?>"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>
    
    <?php if ($is_admin): ?>
        <div class="d-flex justify-content-between mb-4">
            <h1>Vídeos de Apoio</h1>
            <button class="btn mef-btn-primary" data-bs-toggle="modal" data-bs-target="#videoModal">
                Adicionar Vídeo
            </button>
        </div>
    <?php else: ?>
        <h1>Vídeos de Apoio</h1>
        <p>Aprenda sobre alimentação saudável!</p>
    <?php endif; ?>

    <?php if (empty($videos)): ?>
        <div class="text-center py-5">
            <i class="bi bi-play-circle display-1 text-muted"></i>
            <h3 class="mt-3">Nenhum vídeo disponível</h3>
            <p class="text-muted">Os vídeos aparecerão aqui em breve!</p>
        </div>
    <?php else: ?>
        <!-- Lista de vídeos -->
        <div class="row">
            <?php foreach ($videos as $video): ?>
            <div class="col-md-6 mb-4">
                <div class="content-card h-100">
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe src="<?php echo htmlspecialchars($video['url']); ?>" 
                                title="<?php echo htmlspecialchars($video['titulo']); ?>" 
                                allowfullscreen></iframe>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="flex-grow-1"><?php echo htmlspecialchars($video['titulo']); ?></h4>
                        <?php if ($is_admin): ?>
                            <button class="btn btn-sm btn-danger ms-2" onclick="excluirVideo(<?php echo $video['id_video']; ?>)">
                                <i class="bi bi-trash"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <p class="text-muted"><?php echo htmlspecialchars($video['descricao']); ?></p>
                    
                    <?php if ($video['categoria']): ?>
                        <span class="badge bg-primary"><?php echo htmlspecialchars($video['categoria']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($is_admin): ?>
<!-- Modal para adicionar vídeo -->
<div class="modal fade" id="videoModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Adicionar Vídeo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="acao" value="adicionar">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" class="form-control" name="titulo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL do YouTube</label>
                        <input type="url" class="form-control" name="url" placeholder="https://youtube.com/watch?v=..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categoria</label>
                        <select class="form-control" name="categoria" required>
                            <option value="">Selecione uma categoria</option>
                            <option value="Receitas">Receitas</option>
                            <option value="Dicas">Dicas</option>
                            <option value="Exercícios">Exercícios</option>
                            <option value="Nutrição">Nutrição</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn mef-btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function excluirVideo(id) {
    if (confirm('Tem certeza que deseja excluir este vídeo?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="acao" value="excluir">
            <input type="hidden" name="video_id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php endif; ?>