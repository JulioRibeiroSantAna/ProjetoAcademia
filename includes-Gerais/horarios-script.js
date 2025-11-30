// Sistema de Horários por Data Específica

let horariosData = { add: [], edit: [] };

function adicionarHorario(tipo) {
    const data = document.getElementById(`${tipo}_nova_data`).value;
    const inicio = document.getElementById(`${tipo}_novo_inicio`).value;
    const fim = document.getElementById(`${tipo}_novo_fim`).value;
    
    if (!data || !inicio || !fim) {
        alert('Preencha data, horário de início e fim!');
        return;
    }
    
    if (inicio >= fim) {
        alert('Horário de início deve ser menor que o fim!');
        return;
    }
    
    const dataObj = new Date(data + 'T00:00:00');
    const hoje = new Date();
    hoje.setHours(0, 0, 0, 0);
    
    if (dataObj < hoje) {
        alert('Não é possível adicionar horários para datas passadas!');
        return;
    }
    
    const existe = horariosData[tipo].find(h => 
        h.data === data && h.hora_inicio === inicio && h.hora_fim === fim
    );
    
    if (existe) {
        alert('Este horário já foi adicionado!');
        return;
    }
    
    horariosData[tipo].push({ data, hora_inicio: inicio, hora_fim: fim });
    renderizarHorarios(tipo);
    
    document.getElementById(`${tipo}_nova_data`).value = '';
    document.getElementById(`${tipo}_novo_inicio`).value = '';
    document.getElementById(`${tipo}_novo_fim`).value = '';
}

function removerHorario(tipo, index) {
    if (confirm('Deseja remover este horário?')) {
        horariosData[tipo].splice(index, 1);
        renderizarHorarios(tipo);
    }
}

function renderizarHorarios(tipo) {
    const container = document.getElementById(`horarios_${tipo}_container`);
    
    if (horariosData[tipo].length === 0) {
        container.innerHTML = '<p class="text-dark text-center py-3"><i class="bi bi-calendar-x me-2"></i>Nenhum horário adicionado</p>';
    } else {
        horariosData[tipo].sort((a, b) => {
            if (a.data !== b.data) return a.data.localeCompare(b.data);
            return a.hora_inicio.localeCompare(b.hora_inicio);
        });
        
        let html = '<div class="list-group">';
        horariosData[tipo].forEach((h, index) => {
            const dataFormatada = new Date(h.data + 'T00:00:00').toLocaleDateString('pt-BR', { 
                weekday: 'short', day: '2-digit', month: 'short', year: 'numeric'
            });
            
            html += `
                <div class="list-group-item d-flex justify-content-between align-items-center" style="border-left: 4px solid #667eea; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                    <div>
                        <strong style="color: #667eea; font-size: 0.95rem;">${dataFormatada}</strong>
                        <br>
                        <small class="text-muted">
                            <i class="bi bi-clock-fill" style="color: #10b981;"></i> ${h.hora_inicio} - ${h.hora_fim}
                        </small>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removerHorario('${tipo}', ${index})" title="Remover">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    }
    
    document.getElementById(`horarios_${tipo}_json`).value = JSON.stringify(horariosData[tipo]);
}

function carregarHorariosExistentes(tipo, horarios) {
    horariosData[tipo] = horarios.map(h => ({
        data: h.data_atendimento,
        hora_inicio: h.hora_inicio.substring(0, 5),
        hora_fim: h.hora_fim.substring(0, 5)
    }));
    renderizarHorarios(tipo);
}

document.addEventListener('DOMContentLoaded', function() {
    renderizarHorarios('add');
    
    const addModal = document.getElementById('addModal');
    if (addModal) {
        addModal.addEventListener('show.bs.modal', function() {
            horariosData.add = [];
            renderizarHorarios('add');
        });
    }
});

async function verHorarios(id, nome) {
    try {
        const response = await fetch(`../includes-Gerais/profissionais-gerenciamento.php?get_horarios=${id}`);
        const horarios = await response.json();
        
        document.getElementById('horarios_modal_title').textContent = `Horários - ${nome}`;
        
        const container = document.getElementById('horarios_modal_body');
        container.innerHTML = '';
        
        if (horarios.length === 0) {
            container.innerHTML = '<p class="text-muted">Nenhum horário cadastrado.</p>';
        } else {
            const lista = document.createElement('div');
            lista.className = 'list-group';
            
            horarios.forEach(h => {
                const dataFormatada = new Date(h.data_atendimento + 'T00:00:00').toLocaleDateString('pt-BR', { 
                    weekday: 'long', day: '2-digit', month: 'long', year: 'numeric'
                });
                
                const item = document.createElement('div');
                item.className = 'list-group-item d-flex justify-content-between align-items-center';
                item.style.borderLeft = '4px solid #667eea';
                item.innerHTML = `
                    <div>
                        <strong style="color: #667eea;">${dataFormatada}</strong>
                        <br>
                        <small class="text-dark">
                            <i class="bi bi-clock"></i> ${h.hora_inicio.substring(0,5)} - ${h.hora_fim.substring(0,5)}
                        </small>
                    </div>
                `;
                lista.appendChild(item);
            });
            
            container.appendChild(lista);
        }
        
        new bootstrap.Modal(document.getElementById('horariosModal')).show();
    } catch (error) {
        console.error('Erro ao carregar horários:', error);
        alert('Erro ao carregar horários!');
    }
}
