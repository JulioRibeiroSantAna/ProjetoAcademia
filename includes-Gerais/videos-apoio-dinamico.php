<?php
/**
 * Sistema de Gerenciamento de Vídeos Educativos
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connection.php';

$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
$msg = '';

// Criação das tabelas se não existirem
$pdo->exec("CREATE TABLE IF NOT EXISTS videos (
    id_video INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT NOT NULL,
    url VARCHAR(255) NOT NULL,
    arquivo_video VARCHAR(255) DEFAULT NULL,
    thumbnail_video VARCHAR(255) DEFAULT NULL,
    tipo_video ENUM('url', 'arquivo') DEFAULT 'url',
    data_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_nutricionista INT DEFAULT NULL
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS topicos (
    id_topico INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
)");

$pdo->exec("CREATE TABLE IF NOT EXISTS videos_topicos (
    videos_id INT NOT NULL,
    topicos_id INT NOT NULL,
    PRIMARY KEY (videos_id, topicos_id),
    FOREIGN KEY (videos_id) REFERENCES videos(id_video) ON DELETE CASCADE,
    FOREIGN KEY (topicos_id) REFERENCES topicos(id_topico) ON DELETE CASCADE
)");

function uploadVideo($file) {
    $diretorio = __DIR__ . '/../uploads/videos/';
    
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }
    
    $extensoes_permitidas = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'];
    $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extensao, $extensoes_permitidas)) {
        return ['erro' => 'Formato de vídeo não permitido! Use: ' . implode(', ', $extensoes_permitidas)];
    }
    
    $tamanho_maximo = 500 * 1024 * 1024; // 500MB
    
    if ($file['size'] > $tamanho_maximo) {
        return ['erro' => 'O vídeo deve ter no máximo 500MB!'];
    }
    
    $nome_unico = uniqid() . '_' . time() . '.' . $extensao;
    $caminho_completo = $diretorio . $nome_unico;
    
    if (move_uploaded_file($file['tmp_name'], $caminho_completo)) {
        return ['sucesso' => $nome_unico];
    } else {
        return ['erro' => 'Falha ao fazer upload do vídeo!'];
    }
}

// Processa formulário (só admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin) {
    $acao = $_POST['acao'] ?? '';
    
    if ($acao === 'adicionar') {
        $titulo = trim($_POST['titulo'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $categoria = $_POST['categoria'] ?? '';
        $tipo_video = $_POST['tipo_video'] ?? 'url';
        
        if (!$titulo || !$descricao || !$categoria) {
            $msg = '❌ Título, descrição e categoria são obrigatórios!';
        } else {
            $arquivo_video = null;
            $thumbnail_video = null;
            $url_final = $url;
            
            if (isset($_FILES['thumbnail_file']) && $_FILES['thumbnail_file']['error'] === UPLOAD_ERR_OK) {
                $thumb_file = $_FILES['thumbnail_file'];
                $extensoes_thumb = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $extensao_thumb = strtolower(pathinfo($thumb_file['name'], PATHINFO_EXTENSION));
                
                if (!in_array($extensao_thumb, $extensoes_thumb)) {
                    $msg = '❌ Formato de imagem inválido! Use: JPG, PNG, GIF ou WEBP';
                } elseif ($thumb_file['size'] > 5 * 1024 * 1024) {
                    $msg = '❌ A capa deve ter no máximo 5MB!';
                } else {
                    $nome_thumb = uniqid() . '_' . time() . '.' . $extensao_thumb;
                    $destino_thumb = __DIR__ . '/../uploads/videos/thumbnails/' . $nome_thumb;
                    
                    if (!file_exists(__DIR__ . '/../uploads/videos/thumbnails/')) {
                        mkdir(__DIR__ . '/../uploads/videos/thumbnails/', 0777, true);
                    }
                    
                    if (move_uploaded_file($thumb_file['tmp_name'], $destino_thumb)) {
                        $thumbnail_video = $nome_thumb;
                    }
                }
            }
            
            if (!$msg && $tipo_video === 'arquivo' && isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
                $resultado = uploadVideo($_FILES['video_file']);
                if (isset($resultado['erro'])) {
                    $msg = '❌ ' . $resultado['erro'];
                } else {
                    $arquivo_video = $resultado['sucesso'];
                    $url_final = '';
                }
            } 
            elseif (!$msg && $tipo_video === 'url') {
                if (!$url) {
                    $msg = '❌ URL do YouTube é obrigatória!';
                } elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
                    $msg = '❌ URL inválida!';
                } elseif (strpos($url, 'youtube.com') === false && strpos($url, 'youtu.be') === false) {
                    $msg = '❌ Por favor, use apenas links do YouTube!';
                } else {
                    if (strpos($url, 'youtube.com/watch') !== false) {
                        preg_match('/[?&]v=([^&]+)/', $url, $matches);
                        if (isset($matches[1])) {
                            $url_final = 'https://www.youtube.com/embed/' . $matches[1];
                        }
                    } elseif (strpos($url, 'youtu.be/') !== false) {
                        $video_id = substr($url, strpos($url, 'youtu.be/') + 9);
                        $video_id = strtok($video_id, '?');
                        $url_final = 'https://www.youtube.com/embed/' . $video_id;
                    }
                }
            }
            
            if (!isset($msg) || strpos($msg, '❌') === false) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO videos (titulo, descricao, url, arquivo_video, thumbnail_video, tipo_video, id_nutricionista) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$titulo, $descricao, $url_final, $arquivo_video, $thumbnail_video, $tipo_video, 1]);
                    $video_id = $pdo->lastInsertId();
                
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
                
                $stmt = $pdo->prepare("INSERT INTO videos_topicos (videos_id, topicos_id) VALUES (?, ?)");
                $stmt->execute([$video_id, $topico_id]);
                
                    $msg = '✅ Vídeo adicionado com sucesso!';
                } catch (PDOException $e) {
                    $msg = '❌ Erro ao adicionar vídeo: ' . $e->getMessage();
                }
            }
        }
    }
    
    if ($acao === 'editar') {
        $video_id = $_POST['video_id'] ?? 0;
        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $categoria = $_POST['categoria'] ?? '';
        
        if (!$video_id || !$titulo || !$descricao || !$categoria) {
            $msg = '❌ Todos os campos são obrigatórios!';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE videos SET titulo = ?, descricao = ? WHERE id_video = ?");
                $stmt->execute([$titulo, $descricao, $video_id]);
                
                $stmt = $pdo->prepare("SELECT topicos_id FROM videos_topicos WHERE videos_id = ?");
                $stmt->execute([$video_id]);
                $topico_atual = $stmt->fetch();
                
                $stmt = $pdo->prepare("SELECT id_topico FROM topicos WHERE nome = ?");
                $stmt->execute([$categoria]);
                $novo_topico = $stmt->fetch();
                
                if (!$novo_topico) {
                    $stmt = $pdo->prepare("INSERT INTO topicos (nome) VALUES (?)");
                    $stmt->execute([$categoria]);
                    $novo_topico_id = $pdo->lastInsertId();
                } else {
                    $novo_topico_id = $novo_topico['id_topico'];
                }
                
                if ($topico_atual) {
                    $stmt = $pdo->prepare("UPDATE videos_topicos SET topicos_id = ? WHERE videos_id = ?");
                    $stmt->execute([$novo_topico_id, $video_id]);
                } else {
                    $stmt = $pdo->prepare("INSERT INTO videos_topicos (videos_id, topicos_id) VALUES (?, ?)");
                    $stmt->execute([$video_id, $novo_topico_id]);
                }
                
                $msg = '✅ Vídeo atualizado com sucesso!';
            } catch (PDOException $e) {
                $msg = '❌ Erro ao editar vídeo: ' . $e->getMessage();
            }
        }
    }
    
    if ($acao === 'excluir') {
        $id = $_POST['video_id'] ?? 0;
        try {
            $stmt = $pdo->prepare("SELECT arquivo_video, thumbnail_video FROM videos WHERE id_video = ?");
            $stmt->execute([$id]);
            $video = $stmt->fetch();
            
            $stmt = $pdo->prepare("DELETE FROM videos_topicos WHERE videos_id = ?");
            $stmt->execute([$id]);
            
            $stmt = $pdo->prepare("DELETE FROM videos WHERE id_video = ?");
            $stmt->execute([$id]);
            
            if ($video && $video['arquivo_video']) {
                $caminho_arquivo = __DIR__ . '/../uploads/videos/' . $video['arquivo_video'];
                if (file_exists($caminho_arquivo)) {
                    unlink($caminho_arquivo);
                }
            }
            
            if ($video && $video['thumbnail_video']) {
                $caminho_thumb = __DIR__ . '/../uploads/videos/thumbnails/' . $video['thumbnail_video'];
                if (file_exists($caminho_thumb)) {
                    unlink($caminho_thumb);
                }
            }
            
            $msg = '✅ Vídeo excluído com sucesso!';
        } catch (PDOException $e) {
            $msg = '❌ Erro ao excluir vídeo: ' . $e->getMessage();
        }
    }
}

// Pega filtros da URL (pesquisa e categoria)
$filtro_categorias = isset($_GET['categorias']) ? explode(',', $_GET['categorias']) : [];
$filtro_busca = $_GET['busca'] ?? '';

try {
    $sql = "
        SELECT DISTINCT v.*, GROUP_CONCAT(t.nome) as categorias
        FROM videos v 
        LEFT JOIN videos_topicos vt ON v.id_video = vt.videos_id
        LEFT JOIN topicos t ON vt.topicos_id = t.id_topico
        WHERE 1=1
    ";
    
    $params = [];
    
    if (!empty($filtro_categorias)) {
        $placeholders = implode(',', array_fill(0, count($filtro_categorias), '?'));
        $sql .= " AND t.nome IN ($placeholders)";
        $params = array_merge($params, $filtro_categorias);
    }
    
    if ($filtro_busca) {
        $sql .= " AND (v.titulo LIKE ? OR v.descricao LIKE ?)";
        $params[] = "%$filtro_busca%";
        $params[] = "%$filtro_busca%";
    }
    
    $sql .= " GROUP BY v.id_video ORDER BY v.data_upload DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $videos = [];
}

try {
    $stmt = $pdo->query("SELECT DISTINCT nome FROM topicos ORDER BY nome");
    $categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $categorias = [];
}
?>

<!-- CSS para tema escuro -->
<style>
/* ========== BARRA DE PESQUISA ESTILO YOUTUBE ========== */
.search-filter-section {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    padding: 20px 0;
    border-radius: 0;
    border: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    width: 100vw !important;
    min-width: 100vw !important;
    max-width: 100vw !important;
    margin: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    position: fixed;
    top: 80px;
    left: 0 !important;
    right: 0 !important;
    z-index: 999;
    overflow: visible !important;
    box-sizing: border-box;
}

.mef-card {
    margin-top: 250px;
}

.search-filter-container {
    display: grid;
    grid-template-columns: 1fr 240px 120px;
    gap: 15px;
    align-items: center;
    width: 100%;
    max-width: 95%;
    margin: 0 auto;
    padding: 0 2.5%;
    overflow: visible !important;
}

/* Campo de Busca */
.search-box-wrapper {
    position: relative;
    flex: 1;
}

.search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.6);
    font-size: 1.3rem;
    z-index: 10;
    transition: all 0.3s ease;
}

.search-input-modern {
    width: 100%;
    background: rgba(255, 255, 255, 0.1) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: #ffffff !important;
    padding: 12px 20px 12px 50px !important;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 400;
    transition: all 0.2s ease;
    height: 48px;
}

.search-input-modern:focus {
    background: rgba(255, 255, 255, 0.15) !important;
    border-color: rgba(102, 126, 234, 0.8) !important;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2) !important;
    outline: none;
}

.search-input-modern:focus + .search-icon,
.search-box-wrapper:hover .search-icon {
    color: rgba(102, 126, 234, 1);
}

.search-input-modern::placeholder {
    color: rgba(255, 255, 255, 0.5);
    font-size: 0.95rem;
}

/* Select de Filtro */
.filter-box-wrapper {
    position: relative;
}

.filter-icon {
    position: absolute;
    left: 24px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.6);
    font-size: 1.2rem;
    z-index: 10;
    transition: all 0.3s ease;
}

.filter-select-modern {
    width: 100%;
    background: rgba(255, 255, 255, 0.1) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
    color: #ffffff !important;
    padding: 12px 45px 12px 55px !important;
    border-radius: 25px;
    font-size: 0.95rem;
    font-weight: 400;
    height: 48px;
    cursor: pointer;
    transition: all 0.2s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ffffff' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 20px center !important;
}

.filter-select-modern:focus {
    background-color: rgba(255, 255, 255, 0.15) !important;
    border-color: rgba(102, 126, 234, 0.8) !important;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2) !important;
    outline: none;
}

.filter-select-modern:hover {
    border-color: rgba(255, 255, 255, 0.3);
}

.filter-select-modern option {
    background: #1a1a2e;
    color: #ffffff;
    padding: 12px;
}

.filter-box-wrapper:hover .filter-icon {
    color: rgba(102, 126, 234, 1);
}

/* Chips de Categorias */
.category-chips-container {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
    padding: 0 20px;
}

.category-chip {
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.8);
    padding: 10px 18px;
    border-radius: 25px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
    user-select: none;
}

.category-chip:hover {
    background: rgba(102, 126, 234, 0.2);
    border-color: rgba(102, 126, 234, 0.5);
    color: #ffffff;
    transform: translateY(-2px);
}

.category-chip.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.category-chip.active:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.category-chip i {
    font-size: 1rem;
}

/* Botão de Busca */
.btn-search-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: #ffffff;
    padding: 18px 32px;
    border-radius: 25px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: none;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-search-modern:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
}

.btn-search-modern:active {
    transform: scale(0.98);
}

/* Barra de Informações dos Filtros */
.filter-info-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    margin-bottom: 0;
    padding: 12px 20px;
    background: rgba(102, 126, 234, 0.08);
    border-radius: 12px;
    flex-wrap: wrap;
    gap: 15px;
}

.filter-tags {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
}

.filter-tag {
    background: rgba(102, 126, 234, 0.25);
    border: 1px solid rgba(102, 126, 234, 0.5);
    color: #ffffff;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
}

.results-count {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: rgba(255, 255, 255, 0.85);
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
}

.btn-clear-filters {
    background: linear-gradient(135deg, rgba(255, 77, 77, 0.2) 0%, rgba(255, 77, 77, 0.3) 100%);
    border: 2px solid rgba(255, 77, 77, 0.4);
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 600;
}

.btn-clear-filters:hover {
    background: linear-gradient(135deg, rgba(255, 77, 77, 0.4) 0%, rgba(255, 77, 77, 0.5) 100%);
    border-color: rgba(255, 77, 77, 0.6);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 77, 77, 0.3);
}

/* ========== CARDS DE VÍDEOS MODERNOS ========== */
/* Grid fixo - sempre 3 colunas no desktop */
.videos-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    width: 100%;
}

.video-card-modern {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
    min-height: 500px;
}

.video-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
    z-index: 1;
}

.video-card-modern:hover::before {
    opacity: 1;
}

.video-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.4),
        0 0 0 2px rgba(102, 126, 234, 0.3);
    border-color: rgba(102, 126, 234, 0.5);
}

.video-thumbnail-container {
    position: relative;
    z-index: 2;
}

.video-thumbnail {
    width: 100%;
    height: 300px;
    border-radius: 20px 20px 0 0;
    border: none;
    background: #000;
    object-fit: cover;
}

.video-content {
    padding: 30px;
    position: relative;
    z-index: 2;
    background: rgba(0, 0, 0, 0.3);
    flex: 1;
    display: flex;
    flex-direction: column;
}

.video-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 15px;
}

.video-actions {
    display: flex;
    gap: 8px;
}

.edit-video-btn,
.delete-video-btn {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.edit-video-btn:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.delete-video-btn:hover {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-color: #f5576c;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4);
}

.video-title {
    color: #ffffff;
    font-weight: 700;
    font-size: 1.5rem;
    margin: 0 0 15px 0;
    line-height: 1.4;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.delete-video-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(220, 53, 69, 0.9);
    border: 2px solid rgba(255, 255, 255, 0.8);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    flex-shrink: 0;
}

.delete-video-btn:hover {
    background: #dc3545;
    transform: scale(1.1);
    border-color: white;
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
}

.delete-video-btn i {
    font-size: 14px;
}

.video-description {
    color: rgba(184, 198, 219, 0.9);
    line-height: 1.6;
    margin: 0 0 20px 0;
    font-size: 1.05rem;
    background: rgba(255, 255, 255, 0.05);
    padding: 20px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
}

.video-category-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.video-category-badge i {
    font-size: 0.9rem;
}

/* ========== BOTÕES DE ALTERNÂNCIA ========== */
.btn-check:checked + .btn-outline-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border-color: #667eea !important;
    color: #fff !important;
}

.btn-outline-primary {
    border-color: rgba(102, 126, 234, 0.5);
    color: rgba(102, 126, 234, 1);
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: rgba(102, 126, 234, 0.1);
    border-color: #667eea;
    color: #667eea;
}

/* ========== MODAL TEMA ESCURO COM CAMPOS VISÍVEIS ========== */
.modal-dark {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
}

.modal-dark .modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.3);
    border-radius: 16px 16px 0 0;
    padding: 1.5rem;
}

.modal-dark .modal-body {
    padding: 1.5rem;
}

.modal-dark .modal-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.3);
    border-radius: 0 0 16px 16px;
    padding: 1.5rem;
}

/* CAMPOS PERFEITAMENTE VISÍVEIS */
.modal input,
.modal select,
.modal textarea {
    background-color: #ffffff !important;
    color: #000000 !important;
    border: 1px solid #ced4da !important;
    border-radius: 8px !important;
    padding: 0.75rem 1rem !important;
    font-size: 1rem !important;
    font-family: 'Poppins', sans-serif !important;
    transition: all 0.3s ease !important;
}

.modal input:focus,
.modal select:focus,
.modal textarea:focus {
    background-color: #ffffff !important;
    color: #000000 !important;
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
    outline: none !important;
}

.modal input::placeholder,
.modal textarea::placeholder {
    color: #6c757d !important;
    opacity: 1 !important;
}

.modal select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.75rem center !important;
    background-size: 16px 12px !important;
    appearance: none !important;
}

.modal select option {
    background-color: #ffffff !important;
    color: #000000 !important;
    padding: 0.5rem !important;
}

.modal .form-label {
    color: #ffffff !important;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.modal .text-muted {
    color: rgba(255, 255, 255, 0.7) !important;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.modal textarea {
    resize: vertical;
    min-height: 100px;
}

/* Preview do vídeo */
#videoPreview {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.ratio-16x9 {
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* ========== RESPONSIVIDADE ========== */
/* Tablets e mobile (até 992px) */
@media (max-width: 992px) {
    .search-filter-section {
        padding: 25px 30px;
    }
    
    .search-filter-container {
        grid-template-columns: 1fr 250px 120px;
        gap: 15px;
    }
    
    .btn-search-modern span {
        display: none;
    }
    
    .btn-search-modern {
        padding: 16px 20px;
    }
}

/* Mobile (até 768px) - 1 coluna */
@media (max-width: 768px) {
    .videos-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .search-filter-section {
        padding: 20px;
    }
    
    .search-filter-container {
        grid-template-columns: 1fr;
        gap: 12px;
        max-width: 100%;
    }
    
    .search-input-modern,
    .filter-select-modern,
    .btn-search-modern {
        height: 52px;
        font-size: 0.95rem;
    }
    
    .btn-search-modern {
        width: 100%;
    }
    
    .btn-search-modern span {
        display: inline;
    }
    
    .filter-info-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .filter-tags {
        width: 100%;
    }
    
    .filter-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .video-thumbnail {
        height: 200px;
    }
    
    .video-content {
        padding: 20px;
    }
    
    .video-title {
        font-size: 1.1rem;
    }
    
    .video-card-modern {
        min-height: 450px;
    }
}

/* Tablet (769px a 1024px) - 2 colunas */
@media (min-width: 769px) and (max-width: 1024px) {
    .videos-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
}

/* Desktop grande - mantém 3 colunas */
@media (min-width: 1400px) {
    .videos-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
    }
}

@media (max-width: 480px) {
    .search-filter-section {
        padding: 15px;
        border-radius: 15px;
    }
    
    .search-input-modern,
    .filter-select-modern,
    .btn-search-modern {
        height: 48px;
        font-size: 0.9rem;
    }
    
    .search-input-modern {
        padding: 14px 16px 14px 50px !important;
    }
    
    .filter-select-modern {
        padding: 14px 40px 14px 50px !important;
    }
    
    .search-icon,
    .filter-icon {
        font-size: 1rem;
        left: 20px;
    }
    
    .btn-search-modern {
        padding: 14px 20px;
        font-size: 0.95rem;
    }
    
    .filter-tag {
        font-size: 0.85rem;
        padding: 6px 12px;
    }
    
    .results-count {
        font-size: 0.85rem;
    }
    
    .btn-clear-filters {
        font-size: 0.85rem;
        padding: 6px 14px;
    }
    
    .video-thumbnail {
        height: 180px;
    }
    
    .video-content {
        padding: 15px;
    }
    
    .video-title {
        font-size: 1rem;
    }
    
    .video-description {
        font-size: 0.9rem;
        padding: 12px;
    }
}
</style>

<!-- Barra de Pesquisa e Filtros (SEPARADA NO TOPO) -->
<div class="search-filter-section mb-5">
    <form method="GET">
        <div class="search-filter-container">
            <div class="search-box-wrapper">
                <div class="search-icon">
                    <i class="bi bi-search"></i>
                </div>
                <input 
                    type="text" 
                    name="busca" 
                    class="search-input-modern" 
                    placeholder="Buscar vídeos..." 
                    value="<?php echo htmlspecialchars($filtro_busca); ?>"
                >
            </div>
            
            <button type="submit" class="btn-search-modern">
                <i class="bi bi-search me-2"></i>
                <span>Buscar</span>
            </button>
        </div>
        
        <!-- Chips de Categorias -->
        <div class="category-chips-container">
            <?php 
            $categorias_disponiveis = [
                'Receitas' => '🍽️',
                'Dicas' => '💡',
                'Exercícios' => '🏃‍♂️',
                'Nutrição' => '🥗',
                'Bem-estar' => '🧘‍♀️',
                'Suplementação' => '💊',
                'Dietas' => '📋',
                'Perda de Peso' => '⚖️',
                'Ganho de Massa' => '💪'
            ];
            
            foreach ($categorias_disponiveis as $cat => $emoji): 
                $is_active = in_array($cat, $filtro_categorias);
                
                // Monta a URL para toggle
                if ($is_active) {
                    // Remove esta categoria
                    $new_cats = array_filter($filtro_categorias, fn($c) => $c !== $cat);
                } else {
                    // Adiciona esta categoria
                    $new_cats = array_merge($filtro_categorias, [$cat]);
                }
                
                $cat_param = !empty($new_cats) ? '&categorias=' . urlencode(implode(',', $new_cats)) : '';
                $busca_param = $filtro_busca ? '&busca=' . urlencode($filtro_busca) : '';
                $url = '?' . ltrim($cat_param . $busca_param, '&');
            ?>
                <a href="<?php echo $url; ?>" class="category-chip <?php echo $is_active ? 'active' : ''; ?>">
                    <span><?php echo $emoji; ?></span>
                    <span><?php echo htmlspecialchars($cat); ?></span>
                    <?php if ($is_active): ?>
                        <i class="bi bi-x-lg" style="font-size: 0.75rem; margin-left: 4px;"></i>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </form>
    
    <?php if (!empty($filtro_categorias) || $filtro_busca): ?>
        <div class="filter-info-bar">
            <div class="filter-tags">
                <span class="results-count">
                    <i class="bi bi-film me-2"></i>
                    <?php echo count($videos); ?> resultado(s)
                </span>
                
                <?php if ($filtro_busca): ?>
                    <span class="filter-tag">
                        <i class="bi bi-search me-1"></i>
                        "<?php echo htmlspecialchars($filtro_busca); ?>"
                    </span>
                <?php endif; ?>
                <?php foreach ($filtro_categorias as $cat): ?>
                    <span class="filter-tag">
                        <i class="bi bi-tag-fill me-1"></i>
                        <?php echo htmlspecialchars($cat); ?>
                    </span>
                <?php endforeach; ?>
                
                <a href="?" class="btn-clear-filters">
                    <i class="bi bi-x-circle me-1"></i>Limpar Filtros
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Conteúdo dos Vídeos -->
<div class="mef-card">
    <?php if ($msg): ?>
        <div class="alert alert-<?php echo strpos($msg, 'sucesso') !== false ? 'success' : 'danger'; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if ($is_admin): ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">
                <i class="bi bi-play-circle-fill me-3"></i>VÍDEOS DE APOIO
            </h1>
            <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#videoModal">
                <i class="bi bi-plus-circle me-2"></i>Adicionar Vídeo
            </button>
        </div>
    <?php else: ?>
        <h1 class="text-center mb-4">
            <i class="bi bi-play-circle-fill me-3"></i>VÍDEOS DE APOIO
        </h1>
        <p class="text-center lead mb-5">Aprenda sobre alimentação saudável com nossos vídeos!</p>
    <?php endif; ?>

    <?php if (empty($videos)): ?>
        <div class="text-center py-5">
            <i class="bi bi-play-circle display-1 text-muted mb-4"></i>
            <h3 class="mt-3 mb-3">Nenhum vídeo disponível</h3>
            <p class="text-muted mb-4">Os vídeos educativos aparecerão aqui em breve!</p>
            <?php if ($is_admin): ?>
                <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#videoModal">
                    <i class="bi bi-plus-circle me-2"></i>Adicionar Primeiro Vídeo
                </button>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Grid responsivo de vídeos - adapta automaticamente -->
        <div class="videos-grid">
            <?php foreach ($videos as $video): ?>
            <div class="col">
                <div class="video-card-modern">
                    <!-- Thumbnail do Vídeo -->
                    <div class="video-thumbnail-container">
                        <?php if ($video['tipo_video'] === 'arquivo' && $video['arquivo_video']): ?>
                            <!-- Vídeo enviado do PC -->
                            <video class="video-thumbnail" controls controlsList="nodownload" 
                                <?php if ($video['thumbnail_video']): ?>
                                    poster="<?php echo BASE_URL; ?>/uploads/videos/thumbnails/<?php echo htmlspecialchars($video['thumbnail_video']); ?>"
                                <?php endif; ?>>
                                <source src="<?php echo BASE_URL; ?>/uploads/videos/<?php echo htmlspecialchars($video['arquivo_video']); ?>" type="video/mp4">
                                Seu navegador não suporta o elemento de vídeo.
                            </video>
                        <?php else: ?>
                            <!-- Vídeo do YouTube -->
                            <iframe src="<?php echo htmlspecialchars($video['url']); ?>" 
                                    class="video-thumbnail"
                                    title="<?php echo htmlspecialchars($video['titulo']); ?>" 
                                    allowfullscreen
                                    loading="lazy"></iframe>
                        <?php endif; ?>
                    </div>
                
                <!-- Conteúdo do Card -->
                <div class="video-content">
                    <!-- Header com Título e Botões -->
                    <div class="video-header">
                        <h4 class="video-title"><?php echo htmlspecialchars($video['titulo']); ?></h4>
                        
                        <?php if ($is_admin): ?>
                        <div class="video-actions">
                            <button class="edit-video-btn" onclick="editarVideo(<?php echo $video['id_video']; ?>, '<?php echo htmlspecialchars($video['titulo'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($video['descricao'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($video['categorias'] ?? '', ENT_QUOTES); ?>')" title="Editar vídeo">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="delete-video-btn" onclick="excluirVideo(<?php echo $video['id_video']; ?>)" title="Excluir vídeo">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Descrição -->
                    <p class="video-description">
                        <?php echo htmlspecialchars($video['descricao']); ?>
                    </p>
                    
                    <!-- Categoria -->
                    <?php if (isset($video['categorias']) && $video['categorias']): ?>
                    <span class="video-category-badge">
                        <i class="bi bi-tag"></i>
                        <?php echo htmlspecialchars($video['categorias']); ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($is_admin): ?>
<!-- Modal para adicionar vídeo -->
<div class="modal fade" id="videoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-dark">
            <form method="POST" id="videoForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-plus-circle me-2"></i>Adicionar Vídeo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="acao" value="adicionar">
                    
                    <!-- Escolha do tipo de vídeo -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label text-white">
                                <i class="bi bi-gear me-1"></i>Origem do Vídeo *
                            </label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tipo_video" id="tipo_url" value="url" checked>
                                <label class="btn btn-outline-primary" for="tipo_url">
                                    <i class="bi bi-youtube me-2"></i>Link do YouTube
                                </label>
                                
                                <input type="radio" class="btn-check" name="tipo_video" id="tipo_arquivo" value="arquivo">
                                <label class="btn btn-outline-primary" for="tipo_arquivo">
                                    <i class="bi bi-cloud-upload me-2"></i>Enviar Arquivo
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-white">
                                <i class="bi bi-film me-1"></i>Título do Vídeo *
                            </label>
                            <input type="text" class="form-control" name="titulo" 
                                   placeholder="Digite o título do vídeo" required>
                        </div>
                    </div>
                    
                    <!-- Campo URL do YouTube (mostrado apenas se tipo=url) -->
                    <div class="row" id="campoUrl">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-white">
                                <i class="bi bi-link-45deg me-1"></i>Link do YouTube *
                            </label>
                            <input type="url" class="form-control" name="url" id="inputUrl"
                                   placeholder="https://youtube.com/watch?v=...">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Insira a URL completa do vídeo no YouTube
                            </small>
                        </div>
                    </div>
                    
                    <!-- Campo Upload de Arquivo (mostrado apenas se tipo=arquivo) -->
                    <div class="row" id="campoArquivo" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-white">
                                <i class="bi bi-cloud-upload me-1"></i>Selecionar Arquivo de Vídeo *
                            </label>
                            <input type="file" class="form-control" name="video_file" id="inputArquivo"
                                   accept="video/*">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Formatos suportados: MP4, AVI, MOV, WMV, FLV, WEBM, MKV • Tamanho máximo: 500MB
                            </small>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-white">
                                <i class="bi bi-image me-1"></i>Capa do Vídeo (Opcional)
                            </label>
                            <input type="file" class="form-control" name="thumbnail_file" id="inputThumbnail"
                                   accept="image/jpeg,image/png,image/gif,image/webp">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Imagem que aparece antes de reproduzir o vídeo • Formatos: JPG, PNG, GIF, WEBP • Tamanho máximo: 5MB
                            </small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label text-white">
                                <i class="bi bi-card-text me-1"></i>Descrição do Vídeo *
                            </label>
                            <textarea class="form-control" name="descricao" rows="4" 
                                      placeholder="Descreva o conteúdo do vídeo e seus benefícios..." required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-white">
                                <i class="bi bi-tags me-1"></i>Categoria *
                            </label>
                            <select class="form-select" name="categoria" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="Receitas">🍽️ Receitas</option>
                                <option value="Dicas">💡 Dicas</option>
                                <option value="Exercícios">🏃‍♂️ Exercícios</option>
                                <option value="Nutrição">🥗 Nutrição</option>
                                <option value="Bem-estar">🧘‍♀️ Bem-estar</option>
                                <option value="Suplementação">💊 Suplementação</option>
                                <option value="Dietas">📋 Dietas</option>
                                <option value="Perda de Peso">⚖️ Perda de Peso</option>
                                <option value="Ganho de Massa">💪 Ganho de Massa</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Preview do Vídeo -->
                    <div class="row" id="videoPreview" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-white">
                                <i class="bi bi-play-circle me-1"></i>Pré-visualização do Vídeo
                            </label>
                            <div class="ratio ratio-16x9">
                                <iframe id="videoIframe" src="" frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Adicionar Vídeo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar vídeo -->
<div class="modal fade" id="editarVideoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-dark">
            <form method="POST" id="editarVideoForm">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="bi bi-pencil me-2"></i>Editar Vídeo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="acao" value="editar">
                    <input type="hidden" name="video_id" id="editVideoId">
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Informação:</strong> É possível editar título, descrição e categoria. O arquivo/link do vídeo não pode ser alterado.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-white">
                                <i class="bi bi-film me-1"></i>Título do Vídeo *
                            </label>
                            <input type="text" class="form-control" name="titulo" id="editTitulo"
                                   placeholder="Digite o título do vídeo" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label text-white">
                                <i class="bi bi-card-text me-1"></i>Descrição do Vídeo *
                            </label>
                            <textarea class="form-control" name="descricao" id="editDescricao" rows="4"
                                      placeholder="Descreva o conteúdo do vídeo e seus benefícios..." required></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-white">
                                <i class="bi bi-tags me-1"></i>Categoria *
                            </label>
                            <select class="form-select" name="categoria" id="editCategoria" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="Receitas">🍽️ Receitas</option>
                                <option value="Dicas">💡 Dicas</option>
                                <option value="Exercícios">🏃‍♂️ Exercícios</option>
                                <option value="Nutrição">🥗 Nutrição</option>
                                <option value="Bem-estar">🧘‍♀️ Bem-estar</option>
                                <option value="Suplementação">💊 Suplementação</option>
                                <option value="Dietas">📋 Dietas</option>
                                <option value="Perda de Peso">⚖️ Perda de Peso</option>
                                <option value="Ganho de Massa">💪 Ganho de Massa</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Função para editar vídeo
function editarVideo(id, titulo, descricao, categoria) {
    document.getElementById('editVideoId').value = id;
    document.getElementById('editTitulo').value = titulo;
    document.getElementById('editDescricao').value = descricao;
    document.getElementById('editCategoria').value = categoria;
    
    const modal = new bootstrap.Modal(document.getElementById('editarVideoModal'));
    modal.show();
}

// Função para excluir vídeo
function excluirVideo(id) {
    if (confirm('Tem certeza que deseja excluir este vídeo?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = window.location.href;
        form.style.display = 'none';
        
        const acaoInput = document.createElement('input');
        acaoInput.type = 'hidden';
        acaoInput.name = 'acao';
        acaoInput.value = 'excluir';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'video_id';
        idInput.value = id;
        
        form.appendChild(acaoInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const tipoUrl = document.getElementById('tipo_url');
    const tipoArquivo = document.getElementById('tipo_arquivo');
    const campoUrl = document.getElementById('campoUrl');
    const campoArquivo = document.getElementById('campoArquivo');
    const inputUrl = document.getElementById('inputUrl');
    const inputArquivo = document.getElementById('inputArquivo');
    const videoPreview = document.getElementById('videoPreview');
    const videoIframe = document.getElementById('videoIframe');
    
    // Alternar entre URL e Arquivo
    tipoUrl.addEventListener('change', function() {
        if (this.checked) {
            campoUrl.style.display = 'block';
            campoArquivo.style.display = 'none';
            inputUrl.required = true;
            inputArquivo.required = false;
        }
    });
    
    tipoArquivo.addEventListener('change', function() {
        if (this.checked) {
            campoUrl.style.display = 'none';
            campoArquivo.style.display = 'block';
            inputUrl.required = false;
            inputArquivo.required = true;
            videoPreview.style.display = 'none';
        }
    });
    
    // Função para extrair ID do YouTube
    function getYouTubeId(url) {
        const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[7].length === 11) ? match[7] : null;
    }
    
    // Preview automático ao digitar URL
    inputUrl.addEventListener('input', function() {
        const url = this.value.trim();
        const videoId = getYouTubeId(url);
        
        if (videoId) {
            const embedUrl = `https://www.youtube.com/embed/${videoId}`;
            videoIframe.src = embedUrl;
            videoPreview.style.display = 'block';
        } else {
            videoPreview.style.display = 'none';
            videoIframe.src = '';
        }
    });
    
    // Validação do formulário
    document.getElementById('videoForm').addEventListener('submit', function(e) {
        const titulo = this.querySelector('input[name="titulo"]').value.trim();
        const descricao = this.querySelector('textarea[name="descricao"]').value.trim();
        const categoria = this.querySelector('select[name="categoria"]').value;
        const tipoVideo = this.querySelector('input[name="tipo_video"]:checked').value;
        
        if (!titulo || !descricao || !categoria) {
            e.preventDefault();
            alert('Por favor, preencha todos os campos obrigatórios!');
            return false;
        }
        
        if (titulo.length < 3) {
            e.preventDefault();
            alert('O título deve ter pelo menos 3 caracteres!');
            return false;
        }
        
        if (tipoVideo === 'url') {
            const url = this.querySelector('input[name="url"]').value.trim();
            if (!url) {
                e.preventDefault();
                alert('Por favor, insira a URL do YouTube!');
                return false;
            }
            if (!getYouTubeId(url)) {
                e.preventDefault();
                alert('Por favor, insira uma URL válida do YouTube!');
                return false;
            }
        } else if (tipoVideo === 'arquivo') {
            const arquivo = this.querySelector('input[name="video_file"]').files[0];
            if (!arquivo) {
                e.preventDefault();
                alert('Por favor, selecione um arquivo de vídeo!');
                return false;
            }
            // Verificar tamanho (500MB)
            if (arquivo.size > 500 * 1024 * 1024) {
                e.preventDefault();
                alert('O vídeo deve ter no máximo 500MB!');
                return false;
            }
        }
        
        if (descricao.length < 10) {
            e.preventDefault();
            alert('A descrição deve ter pelo menos 10 caracteres!');
            return false;
        }
    });
    
    // Limpar preview ao fechar modal
    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function() {
        videoPreview.style.display = 'none';
        videoIframe.src = '';
        document.getElementById('videoForm').reset();
    });
});
</script>
<?php endif; ?>