<?php
// includes-Gerais/profissionais-section.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
}

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
?>

<!-- Profissionais -->
<section id="profissionais" class="section-spacing">
    <div class="container-symmetric">
        <div class="mef-card">
            <h2 class="text-center mb-4 fade-in-up">NOSSOS PROFISSIONAIS</h2>
            <p class="text-center lead mb-5 fade-in-up"><?php echo htmlspecialchars($texto_descricao); ?></p>

            <div class="symmetric-grid">
                <!-- Profissional 1 -->
                <div class="scale-in">
                    <div class="professional-card">
                        <div class="professional-image-container">
                            <img src="../imagens-teste/goku.jpg" class="card-img-top" alt="Nutricionista Esportivo">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Gabriel da Vila</h5>
                            <p class="card-text text-muted">Nutricionista Esportivo com especialização em alimentação funcional e performance atlética.</p>
                            <a href="<?php echo htmlspecialchars($url_com_parametro); ?>" class="btn mef-btn-primary"><?php echo htmlspecialchars($texto_botao); ?></a>
                        </div>
                    </div>
                </div>
                
                <!-- Profissional 2 -->
                <div class="scale-in">
                    <div class="professional-card">
                        <div class="professional-image-container">
                            <img src="../imagens-teste/goku.jpg" class="card-img-top" alt="Personal Trainer">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Gustavo Silva</h5>
                            <p class="card-text text-muted">Personal Trainer especializado em treinamento funcional e reabilitação física.</p>
                            <a href="<?php echo htmlspecialchars($url_com_parametro); ?>" class="btn mef-btn-primary"><?php echo htmlspecialchars($texto_botao); ?></a>
                        </div>
                    </div>
                </div>
                
                <!-- Profissional 3 -->
                <div class="scale-in">
                    <div class="professional-card">
                        <div class="professional-image-container">
                            <img src="../imagens-teste/goku.jpg" class="card-img-top" alt="Endocrinologista">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Julio Ribeiro</h5>
                            <p class="card-text text-muted">Endocrinologista com foco em hormonios, metabolismo e saúde integral.</p>
                            <a href="<?php echo htmlspecialchars($url_com_parametro); ?>" class="btn mef-btn-primary"><?php echo htmlspecialchars($texto_botao); ?></a>
                        </div>
                    </div>
                </div>
                
                <!-- Profissional 4 -->
                <div class="scale-in">
                    <div class="professional-card">
                        <div class="professional-image-container">
                            <img src="../imagens-teste/goku.jpg" class="card-img-top" alt="Psicólogo">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Miqueias</h5>
                            <p class="card-text text-muted">Psicólogo especializado em saúde mental e desenvolvimento pessoal.</p>
                            <a href="<?php echo htmlspecialchars($url_com_parametro); ?>" class="btn mef-btn-primary"><?php echo htmlspecialchars($texto_botao); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>