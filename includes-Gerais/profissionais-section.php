<?php
// includes-Gerais/profissionais-section.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
}

require_once __DIR__ . '/../db_connection.php';

$url_agendamento = BASE_URL . "/Autenticacao/login.php";
$texto_botao = "Conhecer";
$texto_descricao = "Cada corpo é diferente, por isso nos certificamos de que você possa escolher um plano que funcione melhor para você.";

if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] !== '') {
    $texto_botao = "Agendar Consulta";
    $texto_descricao = "Conheça nossa equipe de especialistas";
    
    if ($_SESSION['tipo_usuario'] === 'admin') {
        $url_agendamento = BASE_URL . "/AdmLogado/agendamento-Adm.php";
    } else if ($_SESSION['tipo_usuario'] === 'usuario') {
        $url_agendamento = BASE_URL . "/UsuarioLogado/agendamento.php";
    }
}

$url_com_parametro = $url_agendamento;
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] === '') {
    $url_com_parametro .= '?from=profissionais';
}

// Buscar profissionais do banco
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

            <div class="symmetric-grid">
                <?php if (!empty($profissionais)): ?>
                    <?php foreach ($profissionais as $profissional): ?>
                    <div class="scale-in">
                        <div class="professional-card">
                            <div class="professional-image-container">
                                <img src="<?php echo $profissional['foto'] ? $profissional['foto'] : 'imagens-teste/goku.jpg'; ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($profissional['nome']); ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($profissional['nome']); ?></h5>
                                <p class="card-text text-muted"><?php echo htmlspecialchars($profissional['descricao']); ?></p>
                                <a href="<?php echo htmlspecialchars($url_com_parametro); ?>" class="btn mef-btn-primary">
                                    <?php echo htmlspecialchars($texto_botao); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>