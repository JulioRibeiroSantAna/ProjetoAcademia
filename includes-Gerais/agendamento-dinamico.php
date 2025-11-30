<?php
/**
 * Sistema de agendamento de consultas
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_connection.php';

$is_admin = (isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin');
$id_usuario = $_SESSION['id_usuario'] ?? null;
$msg = '';

if (!$id_usuario) {
    header('Location: ../Autenticacao/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profissional = $_POST['profissional'] ?? '';
    $data = $_POST['data'] ?? '';
    $hora = $_POST['hora'] ?? '';
    
    if ($profissional && $data && $hora) {
        $data_completa = $data . ' ' . $hora . ':00';
        
        try {
            $pdo->beginTransaction();
            
            // Verifica se já existe agendamento neste slot
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM agendamentos WHERE id_nutricionista = ? AND data_hora = ?");
            $stmt->execute([$profissional, $data_completa]);
            
            if ($stmt->fetchColumn() == 0) {
                // Insere o agendamento
                $stmt = $pdo->prepare("INSERT INTO agendamentos (id_nutricionista, id_usuario, data_hora) VALUES (?, ?, ?)");
                $stmt->execute([$profissional, $id_usuario, $data_completa]);
                
                $pdo->commit();
                $msg = 'Consulta agendada com sucesso!';
            } else {
                $pdo->rollBack();
                $msg = 'Horário já ocupado! Escolha outro horário.';
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $msg = 'Erro no sistema. Tente novamente.';
            error_log("Erro agendamento: " . $e->getMessage());
        }
    } else {
        $msg = 'Preencha todos os campos!';
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM profissionais ORDER BY nome");
    $profissionais = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $profissionais = [];
}

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
            <select class="form-select" name="profissional" id="select_profissional" onchange="carregarHorariosProfissional()" required>
                <option value="">Escolha um profissional</option>
                <?php foreach ($profissionais as $prof): ?>
                    <option value="<?php echo $prof['id']; ?>">
                        <?php echo htmlspecialchars($prof['nome']); ?> - <?php echo htmlspecialchars($prof['especialidade']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Data da Consulta</label>
            <input type="hidden" name="data" id="select_data_hidden">
            <div id="calendario_container" style="display: none;">
                <div id="calendario_custom" class="border rounded p-3" style="background: #f8f9fa;"></div>
                <small id="data_selecionada" class="text-muted mt-2 d-block"></small>
            </div>
            <div id="msg_sem_profissional" class="alert alert-info">
                <i class="bi bi-info-circle"></i> Selecione um profissional primeiro
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label text-dark">Horário</label>
            <select class="form-select" name="hora" id="select_hora" required>
                <option value="">Primeiro selecione o profissional e a data</option>
            </select>
            <small class="text-dark" id="info_horarios"></small>
        </div>
        
        <button type="submit" class="btn mef-btn-primary w-100">AGENDAR CONSULTA</button>
    </form>
</div>

<script>
let horariosProfissional = [];
let datasDisponiveis = [];
let slotsAgendados = []; // Slots já ocupados

async function carregarHorariosProfissional() {
    const profId = document.getElementById('select_profissional').value;
    const calendarioContainer = document.getElementById('calendario_container');
    const msgSemProf = document.getElementById('msg_sem_profissional');
    const selectHora = document.getElementById('select_hora');
    const infoHorarios = document.getElementById('info_horarios');
    
    if (!profId) {
        calendarioContainer.style.display = 'none';
        msgSemProf.style.display = 'block';
        selectHora.innerHTML = '<option value="">Primeiro selecione o profissional</option>';
        infoHorarios.textContent = '';
        return;
    }
    
    try {
        // Busca blocos de horários
        const responseHorarios = await fetch(`../includes-Gerais/profissionais-gerenciamento.php?get_horarios=${profId}`);
        horariosProfissional = await responseHorarios.json();
        
        // Busca slots já agendados
        const responseAgendados = await fetch(`../includes-Gerais/profissionais-gerenciamento.php?get_agendados=${profId}`);
        slotsAgendados = await responseAgendados.json();
        
        if (horariosProfissional.length === 0) {
            calendarioContainer.style.display = 'none';
            msgSemProf.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Este profissional não tem horários disponíveis no momento';
            msgSemProf.className = 'alert alert-warning';
            msgSemProf.style.display = 'block';
            selectHora.innerHTML = '<option value="">Sem horários disponíveis</option>';
            infoHorarios.textContent = '';
        } else {
            // Filtra apenas datas que têm horários disponíveis
            datasDisponiveis = [...new Set(horariosProfissional.map(h => h.data_atendimento))].sort();
            
            if (datasDisponiveis.length === 0) {
                calendarioContainer.style.display = 'none';
                msgSemProf.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Todos os horários estão ocupados';
                msgSemProf.className = 'alert alert-warning';
                msgSemProf.style.display = 'block';
                selectHora.innerHTML = '<option value="">Sem horários disponíveis</option>';
                infoHorarios.textContent = '';
            } else {
                msgSemProf.style.display = 'none';
                calendarioContainer.style.display = 'block';
                gerarCalendario();
                selectHora.innerHTML = '<option value="">Selecione uma data no calendário</option>';
                
                const totalHorarios = horariosProfissional.length;
                infoHorarios.textContent = `${datasDisponiveis.length} data(s) com horários disponíveis (${totalHorarios} horário(s) no total)`;
                infoHorarios.className = 'text-success';
            }
        }
    } catch (error) {
        console.error('Erro ao carregar horários:', error);
        msgSemProf.innerHTML = '<i class="bi bi-x-circle"></i> Erro ao carregar horários';
        msgSemProf.className = 'alert alert-danger';
        msgSemProf.style.display = 'block';
    }
}

function gerarCalendario() {
    const container = document.getElementById('calendario_custom');
    container.innerHTML = '<h6 class="mb-3 text-dark">Datas Disponíveis (clique para selecionar)</h6>';
    
    const grid = document.createElement('div');
    grid.style.display = 'grid';
    grid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(150px, 1fr))';
    grid.style.gap = '10px';
    
    datasDisponiveis.forEach(data => {
        // Conta quantos horários disponíveis tem nesta data
        const horariosNaData = horariosProfissional.filter(h => h.data_atendimento === data).length;
        
        const dataObj = new Date(data + 'T00:00:00');
        const dataFormatada = dataObj.toLocaleDateString('pt-BR', { 
            weekday: 'short', 
            day: '2-digit', 
            month: 'short' 
        });
        
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-primary data-btn';
        btn.innerHTML = `${dataFormatada}<br><small style="font-size: 0.75rem;">(${horariosNaData} disponível${horariosNaData > 1 ? 'is' : ''})</small>`;
        btn.dataset.data = data;
        btn.style.textTransform = 'capitalize';
        
        btn.onclick = function() {
            document.querySelectorAll('.data-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            selecionarData(data);
        };
        
        grid.appendChild(btn);
    });
    
    container.appendChild(grid);
}

function selecionarData(data) {
    document.getElementById('select_data_hidden').value = data;
    
    const dataObj = new Date(data + 'T00:00:00');
    const dataFormatada = dataObj.toLocaleDateString('pt-BR', { 
        weekday: 'long', 
        day: '2-digit', 
        month: 'long', 
        year: 'numeric' 
    });
    
    document.getElementById('data_selecionada').textContent = `Data selecionada: ${dataFormatada}`;
    document.getElementById('data_selecionada').className = 'text-success fw-bold mt-2 d-block';
    
    atualizarHorariosDisponiveis(data);
}

function atualizarHorariosDisponiveis(data) {
    const selectHora = document.getElementById('select_hora');
    const infoHorarios = document.getElementById('info_horarios');
    
    if (!data || !horariosProfissional || horariosProfissional.length === 0) {
        return;
    }
    
    const horariosDisponiveis = horariosProfissional.filter(h => h.data_atendimento === data);
    
    if (horariosDisponiveis.length === 0) {
        selectHora.innerHTML = '<option value="">Nenhum horário disponível</option>';
        return;
    }
    
    selectHora.innerHTML = '<option value="">Escolha um horário</option>';
    
    let slotsLivres = 0;
    
    horariosDisponiveis.forEach(horario => {
        const inicio = horario.hora_inicio.substring(0, 5);
        const fim = horario.hora_fim.substring(0, 5);
        
        const [horaInicio, minInicio] = inicio.split(':').map(Number);
        const [horaFim, minFim] = fim.split(':').map(Number);
        
        let minutos = horaInicio * 60 + minInicio;
        const minutosFim = horaFim * 60 + minFim;
        const duracaoTotal = minutosFim - minutos;
        
        // Se a duração for menor que 1 hora, mostra o horário completo sem dividir
        if (duracaoTotal <= 60) {
            // Verifica se este slot já está agendado
            const jaAgendado = slotsAgendados.some(slot => slot.data === data && slot.hora === inicio);
            
            if (!jaAgendado) {
                const option = document.createElement('option');
                option.value = inicio;
                option.textContent = `${inicio} - ${fim}`;
                selectHora.appendChild(option);
                slotsLivres++;
            }
        } else {
            // Se for maior que 1 hora, divide em slots de 30 minutos
            while (minutos < minutosFim) {
                const h = Math.floor(minutos / 60);
                const m = minutos % 60;
                const horaInicioSlot = String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
                
                // Verifica se este slot já está agendado
                const jaAgendado = slotsAgendados.some(slot => slot.data === data && slot.hora === horaInicioSlot);
                
                if (!jaAgendado) {
                    // Calcula o horário final do slot (30 minutos depois ou o fim do período)
                    const minutosProximo = Math.min(minutos + 30, minutosFim);
                    const hFim = Math.floor(minutosProximo / 60);
                    const mFim = minutosProximo % 60;
                    const horaFimSlot = String(hFim).padStart(2, '0') + ':' + String(mFim).padStart(2, '0');
                    
                    const option = document.createElement('option');
                    option.value = horaInicioSlot;
                    option.textContent = `${horaInicioSlot} - ${horaFimSlot}`;
                    selectHora.appendChild(option);
                    slotsLivres++;
                }
                
                minutos += 30;
            }
        }
    });
    
    if (slotsLivres === 0) {
        selectHora.innerHTML = '<option value="">Todos os horários estão ocupados</option>';
        infoHorarios.textContent = 'Todos os horários desta data já foram agendados';
        infoHorarios.className = 'text-danger mt-1';
    } else {
        infoHorarios.textContent = `${slotsLivres} horário(s) disponível(is) nesta data`;
        infoHorarios.className = 'text-success mt-1';
    }
}
</script>

<style>
.data-btn.active {
    background-color: #667eea !important;
    color: white !important;
    border-color: #667eea !important;
}
</style>