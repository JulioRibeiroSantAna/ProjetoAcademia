<?php
// includes-Gerais/profissionais-section.php

// Verificar se config.php já foi incluído
if (!isset($base_url)) {
    require_once __DIR__ . '/../config.php';
}

// Definir URLs e textos padrão para usuários NÃO logados
$url_agendamento = $base_url . "/Autenticacao/login.php";
$texto_botao = "Conhecer";
$texto_descricao = "Cada corpo é diferente, por isso nos certificamos de que você possa escolher um plano que funcione melhor para você.";

// Verificar tipo de usuário - APENAS se estiver logado
if (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] !== '') {
    // Mudar para "Agendar Consulta" independente de ser user ou admin
    $texto_botao = "Agendar Consulta";
    $texto_descricao = "Conheça nossa equipe de especialistas";
    
    // Definir URL correta baseada no tipo de usuário
    if ($_SESSION['tipo_usuario'] === 'admin') {
        $url_agendamento = $base_url . "/AdmLogado/agendamento-Adm.php";
    } else if ($_SESSION['tipo_usuario'] === 'usuario') {
        $url_agendamento = $base_url . "/UsuarioLogado/agendamento.php";
    }
}

// Preparar URL com parâmetro se não estiver logado
$url_com_parametro = $url_agendamento;
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] === '') {
    $url_com_parametro .= '?from=profissionais';
}
?>

<!-- Profissionais -->
<section id="profissionais" class="gradient-card py-5">
    <div class="container">
        <h2 class="text-center mb-5">NOSSOS PROFISSIONAIS</h2>
        <p class="text-center lead mb-5"><?php echo $texto_descricao; ?></p>

        <div class="row g-4">
            <!-- Profissional 1 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Gabriel da Vila">
                    <div class="card-body text-center">
                        <h5 class="card-title">Gabriel da Vila</h5>
                        <p class="card-text text-muted">Nutricionista Esportivo</p>
                        <a href="<?php echo $url_com_parametro; ?>" class="btn btn-sm btn-primary"><?php echo $texto_botao; ?></a>
                    </div>
                </div>
            </div>
            
            <!-- Profissional 2 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Gustavo Silva">
                    <div class="card-body text-center">
                        <h5 class="card-title">Gustavo Silva</h5>
                        <p class="card-text text-muted">Personal Trainer</p>
                        <a href="<?php echo $url_com_parametro; ?>" class="btn btn-sm btn-primary"><?php echo $texto_botao; ?></a>
                    </div>
                </div>
            </div>
            
            <!-- Profissional 3 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Julio Ribeiro">
                    <div class="card-body text-center">
                        <h5 class="card-title">Julio Ribeiro</h5>
                        <p class="card-text text-muted">Endocrinologista</p>
                        <a href="<?php echo $url_com_parametro; ?>" class="btn btn-sm btn-primary"><?php echo $texto_botao; ?></a>
                    </div>
                </div>
            </div>
            
            <!-- Profissional 4 -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Miqueias">
                    <div class="card-body text-center">
                        <h5 class="card-title">Miqueias</h5>
                        <p class="card-text text-muted">Psicólogo</p>
                        <a href="<?php echo $url_com_parametro; ?>" class="btn btn-sm btn-primary"><?php echo $texto_botao; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>