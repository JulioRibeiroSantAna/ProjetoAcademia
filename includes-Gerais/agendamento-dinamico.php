<?php
// includes-Gerais/agendamento-dinamico.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar se é admin
$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
?>

<div class="mef-card">
    <h1 class="text-center mb-4">AGENDAMENTO</h1>
    <p class="text-center lead mb-4">
        <strong>Preencha os campos para Agendar</strong>
    </p>
    
    <form id="formAgendamento">
        <div class="mb-4">
            <h3 class="border-bottom pb-2 mb-3">Dados Pessoais</h3>
            <div class="mb-3">
                <label for="nome" class="mef-form-label">Nome Completo</label>
                <input type="text" class="form-control mef-form-control" id="nome" required>
            </div>
            <div class="mb-3">
                <label for="email" class="mef-form-label">E-mail</label>
                <input type="email" class="form-control mef-form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="telefone" class="mef-form-label">Telefone</label>
                <input type="tel" class="form-control mef-form-control" id="telefone" required>
            </div>
        </div>
        
        <div class="mb-4">
            <h3 class="border-bottom pb-2 mb-3">Agendamento</h3>
            <div class="mb-3">
                <label for="profissional" class="mef-form-label">Profissional</label>
                <select class="form-select mef-form-control" id="profissional" required>
                    <option value="" selected disabled>Selecione um profissional</option>
                    <option value="Dr. Gabriel">Dr. Gabriel - Nutricionista</option>
                    <option value="Dra. Ana">Dra. Ana - Nutricionista</option>
                    <option value="Dr. Carlos">Dr. Carlos - Psicólogo</option>
                    <?php if ($is_admin): ?>
                    <option value="Dr. Silva">Dr. Silva - Administrador</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="data" class="mef-form-label">Data</label>
                <select class="form-select mef-form-control" id="data" required>
                    <option value="" selected disabled>Selecione uma data</option>
                    <option value="2023-06-15">15/06/2023</option>
                    <option value="2023-06-20">20/06/2023</option>
                    <?php if ($is_admin): ?>
                    <option value="2023-06-25">25/06/2023 (Exclusivo Admin)</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="hora" class="mef-form-label">Hora</label>
                <select class="form-select mef-form-control" id="hora" required>
                    <option value="" selected disabled>Selecione um horário</option>
                    <option value="10:00">10:00</option>
                    <option value="14:00">14:00</option>
                    <?php if ($is_admin): ?>
                    <option value="16:00">16:00 (Exclusivo Admin)</option>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        
        <button type="submit" class="btn btn-save w-100">AGENDAR CONSULTA</button>
    </form>
</div>

<script>
document.getElementById('formAgendamento').addEventListener('submit', function(e) {
    e.preventDefault();
    
    <?php if ($is_admin): ?>
    alert('Consulta agendada com sucesso! (Modo Administrador)');
    <?php else: ?>
    alert('Consulta agendada com sucesso!');
    <?php endif; ?>
});
</script>