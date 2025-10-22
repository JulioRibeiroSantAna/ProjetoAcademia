<?php
/**
 * ARQUIVO: videos-apoio-dinamico.php
 * LOCALIZAÇÃO: includes-Gerais/
 * 
 * PROPÓSITO:
 * Este é o sistema completo de gerenciamento de vídeos educativos.
 * Permite adicionar, editar, excluir e visualizar vídeos de duas formas:
 * 1. URL do YouTube (embed)
 * 2. Upload de arquivo do computador (MP4, AVI, MOV, etc)
 * 
 * FUNCIONALIDADES:
 * - USUÁRIO COMUM: Apenas visualiza os vídeos
 * - ADMIN: Pode adicionar, editar e excluir vídeos
 * 
 * CATEGORIAS DISPONÍVEIS:
 * 🍽️ Receitas | 💡 Dicas | 🏃‍♂️ Exercícios | 🥗 Nutrição
 * 🧘‍♀️ Bem-estar | 💊 Suplementação | 📋 Dietas
 * ⚖️ Perda de Peso | 💪 Ganho de Massa
 * 
 * TECNOLOGIAS:
 * - PHP para lógica de backend
 * - PDO para banco de dados
 * - Bootstrap 5 para UI
 * - JavaScript para modals e validações
 * - HTML5 <video> tag para arquivos locais
 * - YouTube iframe para vídeos externos
 * 
 * ESTRUTURA DO BANCO:
 * - Tabela: videos (id, titulo, descricao, url, arquivo_video, tipo_video)
 * - Tabela: topicos (id, nome)
 * - Tabela: videos_topicos (relacionamento N:N)
 */

// Garante que a sessão está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclui a conexão com o banco de dados
require_once __DIR__ . '/../db_connection.php';

/**
 * VERIFICAÇÃO DE PERMISSÃO
 * 
 * Determina se o usuário logado é administrador.
 * Apenas admins podem adicionar, editar e excluir vídeos.
 * Usuários comuns só visualizam.
 */
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');

// Variável para mensagens de feedback (sucesso ou erro)
$msg = '';

/**
 * CRIAÇÃO AUTOMÁTICA DAS TABELAS
 * 
 * Estas queries criam as tabelas necessárias se elas não existirem.
 * Isso é útil quando rodamos o sistema pela primeira vez.
 * 
 * CREATE TABLE IF NOT EXISTS: só cria se não existir
 * 
 * POR QUE 3 TABELAS?
 * Um vídeo pode ter várias categorias e uma categoria pode ter vários vídeos.
 * Isso se chama relacionamento N:N (muitos pra muitos).
 * A tabela videos_topicos faz essa ponte.
 */

/**
 * TABELA: videos
 * 
 * Armazena as informações principais de cada vídeo.
 * 
 * CAMPOS:
 * - id_video: Identificador único (chave primária)
 * - titulo: Nome do vídeo (ex: "Como fazer suco detox")
 * - descricao: Explicação do conteúdo
 * - url: Link do YouTube embed (vazio se for arquivo)
 * - arquivo_video: Nome do arquivo local (NULL se for YouTube)
 * - tipo_video: 'url' ou 'arquivo'
 * - data_upload: Data/hora que foi adicionado
 * - id_nutricionista: Quem adicionou (por enquanto sempre 1)
 */
$pdo->exec("CREATE TABLE IF NOT EXISTS videos (
    id_video INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT NOT NULL,
    url VARCHAR(255) NOT NULL,
    arquivo_video VARCHAR(255) DEFAULT NULL,
    tipo_video ENUM('url', 'arquivo') DEFAULT 'url',
    data_upload DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_nutricionista INT DEFAULT NULL
)");

/**
 * TABELA: topicos
 * 
 * Armazena as categorias dos vídeos.
 * 
 * CAMPOS:
 * - id_topico: Identificador único
 * - nome: Nome da categoria (ex: "Receitas", "Dicas")
 * 
 * EXEMPLO DE DADOS:
 * 1 | Receitas
 * 2 | Dicas
 * 3 | Exercícios
 */
$pdo->exec("CREATE TABLE IF NOT EXISTS topicos (
    id_topico INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
)");

/**
 * TABELA: videos_topicos
 * 
 * Faz a ligação entre vídeos e tópicos (categorias).
 * Esta é uma tabela de relacionamento N:N.
 * 
 * EXEMPLO:
 * Se o vídeo 5 tem as categorias "Receitas" e "Nutrição":
 * videos_id | topicos_id
 * 5         | 1
 * 5         | 4
 * 
 * FOREIGN KEY: garante que só existam IDs válidos
 * ON DELETE CASCADE: se deletar o vídeo, deleta os relacionamentos também
 */
$pdo->exec("CREATE TABLE IF NOT EXISTS videos_topicos (
    videos_id INT NOT NULL,
    topicos_id INT NOT NULL,
    PRIMARY KEY (videos_id, topicos_id),
    FOREIGN KEY (videos_id) REFERENCES videos(id_video) ON DELETE CASCADE,
    FOREIGN KEY (topicos_id) REFERENCES topicos(id_topico) ON DELETE CASCADE
)");

/**
 * FUNÇÃO: uploadVideo()
 * 
 * PROPÓSITO:
 * Faz o upload de um arquivo de vídeo do computador do usuário pro servidor.
 * 
 * FLUXO:
 * 1. Verifica se a pasta uploads/videos/ existe (cria se não existir)
 * 2. Valida a extensão do arquivo (só aceita formatos de vídeo)
 * 3. Valida o tamanho (máximo 100MB)
 * 4. Gera um nome único pro arquivo (evita sobrescrever)
 * 5. Move o arquivo da pasta temporária pra uploads/videos/
 * 6. Retorna sucesso (nome do arquivo) ou erro
 * 
 * EXTENSÕES ACEITAS:
 * MP4, AVI, MOV, WMV, FLV, WEBM, MKV
 * 
 * POR QUE NOME ÚNICO?
 * Se 2 usuários fizerem upload de "video.mp4" ao mesmo tempo,
 * um arquivo sobrescreveria o outro. O nome único evita isso.
 * 
 * FORMATO DO NOME: uniqid_timestamp.extensao
 * Exemplo: 61a5f8c3d4e21_1638284547.mp4
 * 
 * @param array $file - Array $_FILES['video_file']
 * @return array - ['sucesso' => 'nome_arquivo.mp4'] ou ['erro' => 'mensagem']
 */
function uploadVideo($file) {
    // Define o diretório onde os vídeos serão salvos
    // __DIR__ retorna o diretório atual (includes-Gerais)
    // ../ volta pra raiz, então fica: raiz/uploads/videos/
    $diretorio = __DIR__ . '/../uploads/videos/';
    
    // Verifica se a pasta existe, se não existir, cria
    // 0777: permissão total (ler, escrever, executar)
    // true: cria subpastas recursivamente se necessário
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0777, true);
    }
    
    // Lista de extensões de vídeo que aceitamos
    $extensoes_permitidas = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'];
    
    // Pega a extensão do arquivo enviado
    // pathinfo extrai partes do caminho/nome do arquivo
    // PATHINFO_EXTENSION retorna só a extensão
    // strtolower converte pra minúscula (MP4 vira mp4)
    $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Verifica se a extensão está na lista permitida
    // in_array retorna true se o valor existe no array
    if (!in_array($extensao, $extensoes_permitidas)) {
        // implode junta array em string: mp4, avi, mov...
        return ['erro' => 'Formato de vídeo não permitido! Use: ' . implode(', ', $extensoes_permitidas)];
    }
    
    // Define o tamanho máximo do arquivo: 100MB
    // 1 MB = 1024 KB
    // 1 KB = 1024 bytes
    // Então: 100 * 1024 * 1024 = 104.857.600 bytes
    $tamanho_maximo = 100 * 1024 * 1024;
    
    // Verifica se o arquivo é maior que o limite
    // $file['size'] retorna o tamanho em bytes
    if ($file['size'] > $tamanho_maximo) {
        return ['erro' => 'O vídeo deve ter no máximo 100MB!'];
    }
    
    // Gera um nome único pro arquivo
    // uniqid() gera um ID único baseado no tempo em microsegundos
    // time() adiciona o timestamp atual (segundos desde 1970)
    // Resultado: 61a5f8c3d4e21_1638284547.mp4
    $nome_unico = uniqid() . '_' . time() . '.' . $extensao;
    
    // Caminho completo onde o arquivo será salvo
    $caminho_completo = $diretorio . $nome_unico;
    
    // Move o arquivo da pasta temporária pro destino final
    // $file['tmp_name']: local temporário onde PHP salvou o upload
    // move_uploaded_file: função segura pra mover uploads
    if (move_uploaded_file($file['tmp_name'], $caminho_completo)) {
        // SUCESSO! Retorna o nome do arquivo
        return ['sucesso' => $nome_unico];
    } else {
        // FALHA ao mover o arquivo
        return ['erro' => 'Falha ao fazer upload do vídeo!'];
    }
}

// Processa formulário (só admin pode)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin) {
    $acao = $_POST['acao'] ?? '';
    
    // AÇÃO: Adicionar novo vídeo
    if ($acao === 'adicionar') {
        $titulo = trim($_POST['titulo'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $categoria = $_POST['categoria'] ?? '';
        $tipo_video = $_POST['tipo_video'] ?? 'url'; // 'url' ou 'arquivo'
        
        // Valida campos obrigatórios
        if (!$titulo || !$descricao || !$categoria) {
            $msg = '❌ Título, descrição e categoria são obrigatórios!';
        } else {
            $arquivo_video = null;
            $url_final = $url;
            
            // OPÇÃO 1: Upload de arquivo do PC
            if ($tipo_video === 'arquivo' && isset($_FILES['video_file']) && $_FILES['video_file']['error'] === UPLOAD_ERR_OK) {
                $resultado = uploadVideo($_FILES['video_file']);
                if (isset($resultado['erro'])) {
                    $msg = '❌ ' . $resultado['erro'];
                } else {
                    $arquivo_video = $resultado['sucesso'];
                    $url_final = ''; // URL vazia para vídeos locais
                }
            } 
            // OPÇÃO 2: URL do YouTube
            elseif ($tipo_video === 'url') {
                if (!$url) {
                    $msg = '❌ URL do YouTube é obrigatória!';
                } elseif (!filter_var($url, FILTER_VALIDATE_URL)) {
                    $msg = '❌ URL inválida!';
                } elseif (strpos($url, 'youtube.com') === false && strpos($url, 'youtu.be') === false) {
                    $msg = '❌ Por favor, use apenas links do YouTube!';
                } else {
                    // Converte URL normal do YouTube pra formato embed
                    // youtube.com/watch?v=ABC123 → youtube.com/embed/ABC123
                    if (strpos($url, 'youtube.com/watch') !== false) {
                        preg_match('/[?&]v=([^&]+)/', $url, $matches);
                        if (isset($matches[1])) {
                            $url_final = 'https://www.youtube.com/embed/' . $matches[1];
                        }
                    } elseif (strpos($url, 'youtu.be/') !== false) {
                        // youtu.be/ABC123 → youtube.com/embed/ABC123
                        $video_id = substr($url, strpos($url, 'youtu.be/') + 9);
                        $video_id = strtok($video_id, '?');
                        $url_final = 'https://www.youtube.com/embed/' . $video_id;
                    }
                }
            }
            
            // Se passou nas validações, salva no banco
            if (!isset($msg) || strpos($msg, '❌') === false) {
                try {
                    // Insere vídeo na tabela videos
                    $stmt = $pdo->prepare("INSERT INTO videos (titulo, descricao, url, arquivo_video, tipo_video, id_nutricionista) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$titulo, $descricao, $url_final, $arquivo_video, $tipo_video, 1]);
                    $video_id = $pdo->lastInsertId();
                
                // Verifica se a categoria já existe
                $stmt = $pdo->prepare("SELECT id_topico FROM topicos WHERE nome = ?");
                $stmt->execute([$categoria]);
                $topico = $stmt->fetch();
                
                if (!$topico) {
                    // Categoria nova, cria ela
                    $stmt = $pdo->prepare("INSERT INTO topicos (nome) VALUES (?)");
                    $stmt->execute([$categoria]);
                    $topico_id = $pdo->lastInsertId();
                } else {
                    // Categoria já existe, usa ela
                    $topico_id = $topico['id_topico'];
                }
                
                // Liga o vídeo com a categoria (tabela ponte)
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
                // Atualizar informações do vídeo (sem alterar url/arquivo)
                $stmt = $pdo->prepare("UPDATE videos SET titulo = ?, descricao = ? WHERE id_video = ?");
                $stmt->execute([$titulo, $descricao, $video_id]);
                
                // Atualizar categoria
                // Buscar tópico atual
                $stmt = $pdo->prepare("SELECT topicos_id FROM videos_topicos WHERE videos_id = ?");
                $stmt->execute([$video_id]);
                $topico_atual = $stmt->fetch();
                
                // Buscar ou criar novo tópico
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
                
                // Atualizar associação
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
            // Buscar dados do vídeo antes de excluir
            $stmt = $pdo->prepare("SELECT arquivo_video FROM videos WHERE id_video = ?");
            $stmt->execute([$id]);
            $video = $stmt->fetch();
            
            // Excluir associações primeiro
            $stmt = $pdo->prepare("DELETE FROM videos_topicos WHERE videos_id = ?");
            $stmt->execute([$id]);
            
            // Excluir vídeo
            $stmt = $pdo->prepare("DELETE FROM videos WHERE id_video = ?");
            $stmt->execute([$id]);
            
            // Se tinha arquivo físico, excluir também
            if ($video && $video['arquivo_video']) {
                $caminho_arquivo = __DIR__ . '/../uploads/videos/' . $video['arquivo_video'];
                if (file_exists($caminho_arquivo)) {
                    unlink($caminho_arquivo);
                }
            }
            
            $msg = '✅ Vídeo excluído com sucesso!';
        } catch (PDOException $e) {
            $msg = '❌ Erro ao excluir vídeo: ' . $e->getMessage();
        }
    }
}

// Pega filtros da URL (pesquisa e categoria)
$filtro_categoria = $_GET['categoria'] ?? '';
$filtro_busca = $_GET['busca'] ?? '';

// Buscar vídeos com filtros aplicados
try {
    // Monta query SQL base
    $sql = "
        SELECT v.*, t.nome as categoria 
        FROM videos v 
        LEFT JOIN videos_topicos vt ON v.id_video = vt.videos_id
        LEFT JOIN topicos t ON vt.topicos_id = t.id_topico
        WHERE 1=1
    ";
    
    $params = [];
    
    // Se escolheu uma categoria, filtra por ela
    if ($filtro_categoria) {
        $sql .= " AND t.nome = ?";
        $params[] = $filtro_categoria;
    }
    
    // Se digitou algo na pesquisa, busca no título ou descrição
    if ($filtro_busca) {
        $sql .= " AND (v.titulo LIKE ? OR v.descricao LIKE ?)";
        $params[] = "%$filtro_busca%";
        $params[] = "%$filtro_busca%";
    }
    
    $sql .= " ORDER BY v.data_upload DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $videos = [];
}

// Busca todas as categorias para montar o select
try {
    $stmt = $pdo->query("SELECT DISTINCT nome FROM topicos ORDER BY nome");
    $categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $categorias = [];
}
?>

<!-- CSS para tema escuro -->
<style>
/* ========== LAYOUT AMPLO ========== */
.search-filter-section,
.mef-card {
    max-width: 100%;
    width: 100%;
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding-left: 10px;
    padding-right: 10px;
}

/* ========== BARRA DE PESQUISA MODERNA ========== */
.search-filter-section {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    padding: 35px 40px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    max-width: 100%;
    margin-bottom: 50px !important;
}

.search-filter-container {
    display: grid;
    grid-template-columns: 1fr 280px 140px;
    gap: 20px;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
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
    background: rgba(255, 255, 255, 0.08) !important;
    border: 2px solid rgba(255, 255, 255, 0.15) !important;
    color: #ffffff !important;
    padding: 18px 20px 18px 60px !important;
    border-radius: 15px;
    font-size: 1.1rem;
    font-weight: 500;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 60px;
    letter-spacing: 0.3px;
}

.search-input-modern:focus {
    background: rgba(255, 255, 255, 0.12) !important;
    border-color: rgba(102, 126, 234, 0.6) !important;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15) !important;
    outline: none;
}

.search-input-modern:focus + .search-icon,
.search-box-wrapper:hover .search-icon {
    color: rgba(102, 126, 234, 0.9);
    transform: translateY(-50%) scale(1.1);
}

.search-input-modern::placeholder {
    color: rgba(255, 255, 255, 0.4);
    font-size: 1.05rem;
}

/* Select de Filtro */
.filter-box-wrapper {
    position: relative;
}

.filter-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.6);
    font-size: 1.2rem;
    z-index: 10;
    transition: all 0.3s ease;
}

.filter-select-modern {
    width: 100%;
    background: rgba(255, 255, 255, 0.08) !important;
    border: 2px solid rgba(255, 255, 255, 0.15) !important;
    color: #ffffff !important;
    padding: 18px 20px 18px 55px !important;
    border-radius: 15px;
    font-size: 1.1rem;
    font-weight: 500;
    height: 60px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23ffffff' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right 20px center !important;
    letter-spacing: 0.3px;
}

.filter-select-modern:focus {
    background-color: rgba(255, 255, 255, 0.12) !important;
    border-color: rgba(102, 126, 234, 0.6) !important;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15) !important;
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
    color: rgba(102, 126, 234, 0.9);
    transform: translateY(-50%) scale(1.1);
}

/* Botão de Busca */
.btn-search-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: #ffffff;
    padding: 18px 32px;
    border-radius: 15px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    letter-spacing: 0.5px;
}

.btn-search-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
}

.btn-search-modern:active {
    transform: translateY(0);
}

/* Barra de Informações dos Filtros */
.filter-info-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    flex-wrap: wrap;
    gap: 15px;
}

.filter-tags {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-tag {
    background: rgba(102, 126, 234, 0.2);
    border: 1px solid rgba(102, 126, 234, 0.4);
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.filter-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

.results-count {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    font-weight: 500;
}

.btn-clear-filters {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.8);
    padding: 8px 20px;
    border-radius: 12px;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
}

.btn-clear-filters:hover {
    background: rgba(255, 77, 77, 0.2);
    border-color: rgba(255, 77, 77, 0.4);
    color: #ff4d4d;
    transform: translateY(-2px);
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
        padding: 14px 16px 14px 45px !important;
    }
    
    .search-icon,
    .filter-icon {
        font-size: 1rem;
        left: 16px;
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
            
            <div class="filter-box-wrapper">
                <div class="filter-icon">
                    <i class="bi bi-funnel"></i>
                </div>
                <select name="categoria" class="filter-select-modern">
                    <option value="">Categorias</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat); ?>" 
                                <?php echo $filtro_categoria === $cat ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn-search-modern">
                <i class="bi bi-search me-2"></i>
                <span>Buscar</span>
            </button>
        </div>
    </form>
    
    <?php if ($filtro_categoria || $filtro_busca): ?>
        <div class="filter-info-bar">
            <div class="filter-tags">
                <?php if ($filtro_busca): ?>
                    <span class="filter-tag">
                        <i class="bi bi-search me-1"></i>
                        "<?php echo htmlspecialchars($filtro_busca); ?>"
                    </span>
                <?php endif; ?>
                <?php if ($filtro_categoria): ?>
                    <span class="filter-tag">
                        <i class="bi bi-tag-fill me-1"></i>
                        <?php echo htmlspecialchars($filtro_categoria); ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="filter-actions">
                <span class="results-count">
                    <i class="bi bi-film me-2"></i>
                    <?php echo count($videos); ?> resultado(s)
                </span>
                <a href="?" class="btn-clear-filters">
                    <i class="bi bi-x-circle me-1"></i>Limpar
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
                            <video class="video-thumbnail" controls controlsList="nodownload">
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
                            <button class="edit-video-btn" onclick="editarVideo(<?php echo $video['id_video']; ?>, '<?php echo htmlspecialchars($video['titulo'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($video['descricao'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($video['categoria'] ?? '', ENT_QUOTES); ?>')" title="Editar vídeo">
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
                    <?php if ($video['categoria']): ?>
                    <span class="video-category-badge">
                        <i class="bi bi-tag"></i>
                        <?php echo htmlspecialchars($video['categoria']); ?>
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
                                Formatos suportados: MP4, AVI, MOV, WMV, FLV, WEBM, MKV • Tamanho máximo: 100MB
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
            // Verificar tamanho (100MB)
            if (arquivo.size > 100 * 1024 * 1024) {
                e.preventDefault();
                alert('O vídeo deve ter no máximo 100MB!');
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