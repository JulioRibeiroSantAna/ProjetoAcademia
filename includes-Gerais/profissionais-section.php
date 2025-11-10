<?php
/**
 * ARQUIVO: profissionais-section.php
 * Cards dos profissionais com foto, especialidade e contatos
 * Busca 4 profissionais do banco e exibe
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
}

require_once __DIR__ . '/../db_connection.php';

// Valores padrão (visitante)
$url_agendamento = BASE_URL . "/Autenticacao/login.php";
$texto_botao = "Conhecer";
$texto_descricao = "Cada corpo é diferente, por isso nos certificamos de que você possa escolher um plano que funcione melhor para você.";

// Se está logado, muda texto e URL
if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] !== '') {
    $texto_botao = "Agendar Consulta";
    $texto_descricao = "Conheça nossa equipe de especialistas";
    
    if ($_SESSION['tipo_usuario'] === 'admin') {
        $url_agendamento = BASE_URL . "/AdmLogado/agendamento-Adm.php";
    } else if ($_SESSION['tipo_usuario'] === 'usuario') {
        $url_agendamento = BASE_URL . "/UsuarioLogado/agendamento.php";
    }
}

// Se visitante tentar agendar, adiciona parâmetro na URL
$url_com_parametro = $url_agendamento;
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] === '') {
    $url_com_parametro .= '?from=profissionais';
}

// Busca 4 profissionais do banco
try {
    $stmt = $pdo->query("SELECT * FROM profissionais ORDER BY nome LIMIT 4");
    $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $profissionais = [];
}
?>

<!-- Profissionais -->
<section id="profissionais" class="section-spacing">
    <div class="container-symmetric">
        <div class="mef-card">
            <h2 class="text-center mb-4 fade-in-up">NOSSOS PROFISSIONAIS</h2>
            <p class="text-center lead mb-5 fade-in-up"><?php echo htmlspecialchars($texto_descricao); ?></p>

            <div class="professionals-grid-modern">
                <?php if (!empty($profissionais)): ?>
                    <?php foreach ($profissionais as $profissional): ?>
                    <div class="prof-card scale-in">
                        <div class="prof-card-header">
                            <?php if ($profissional['foto']): ?>
                                <img src="<?php echo BASE_URL . '/' . htmlspecialchars($profissional['foto']); ?>" 
                                     alt="<?php echo htmlspecialchars($profissional['nome']); ?>"
                                     class="prof-image">
                            <?php else: ?>
                                <div class="prof-placeholder">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="prof-card-body">
                            <h3 class="prof-name"><?php echo htmlspecialchars($profissional['nome']); ?></h3>
                            <span class="prof-badge"><?php echo htmlspecialchars($profissional['especialidade']); ?></span>
                            <p class="prof-description"><?php echo htmlspecialchars($profissional['descricao']); ?></p>
                            
                            <div class="prof-contact">
                                <?php if (!empty($profissional['email'])): ?>
                                <div class="prof-contact-item">
                                    <i class="bi bi-envelope"></i>
                                    <span><?php echo htmlspecialchars($profissional['email']); ?></span>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($profissional['telefone'])): ?>
                                <div class="prof-contact-item">
                                    <i class="bi bi-telephone"></i>
                                    <span><?php echo htmlspecialchars($profissional['telefone']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="prof-card-footer">
                            <a href="<?php echo htmlspecialchars($url_com_parametro); ?>" class="prof-btn">
                                <i class="bi bi-calendar-check"></i>
                                <?php echo htmlspecialchars($texto_botao); ?>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>