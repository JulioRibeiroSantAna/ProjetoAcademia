<?php
require_once 'db_connection.php';

echo "<h2>Status dos Horários</h2>";

// Busca todos os horários
$stmt = $pdo->query("SELECT hp.*, p.nome as profissional_nome 
                     FROM horarios_profissionais hp 
                     JOIN profissionais p ON hp.id_profissional = p.id 
                     ORDER BY hp.data_atendimento, hp.hora_inicio");
$horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<table border='1' style='border-collapse: collapse; padding: 10px;'>";
echo "<tr>
        <th>ID</th>
        <th>Profissional</th>
        <th>Data</th>
        <th>Horário</th>
        <th>Status</th>
        <th>Agendamentos</th>
      </tr>";

foreach ($horarios as $h) {
    // Verifica se tem agendamentos neste horário
    $stmt = $pdo->prepare("SELECT COUNT(*), GROUP_CONCAT(DATE_FORMAT(data_hora, '%H:%i') SEPARATOR ', ') as horas
                           FROM agendamentos 
                           WHERE id_nutricionista = ? 
                           AND DATE(data_hora) = ? 
                           AND TIME(data_hora) >= ? 
                           AND TIME(data_hora) < ?");
    $stmt->execute([$h['id_profissional'], $h['data_atendimento'], $h['hora_inicio'], $h['hora_fim']]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $cor = $h['status'] === 'reservado' ? '#ffcccc' : '#ccffcc';
    
    echo "<tr style='background-color: {$cor};'>
            <td>{$h['id']}</td>
            <td>{$h['profissional_nome']}</td>
            <td>{$h['data_atendimento']}</td>
            <td>{$h['hora_inicio']} - {$h['hora_fim']}</td>
            <td><strong>{$h['status']}</strong></td>
            <td>Qtd: {$resultado['COUNT(*)']} | Horários: {$resultado['horas']}</td>
          </tr>";
}

echo "</table>";

echo "<hr><h2>Script de Correção</h2>";
echo "<p><strong>1. Marcar como RESERVADO</strong> horários que têm agendamento mas estão como 'disponível':</p>";

// Busca e corrige - marca como reservado
$stmt = $pdo->query("
    UPDATE horarios_profissionais hp
    SET hp.status = 'reservado'
    WHERE hp.status = 'disponivel'
    AND EXISTS (
        SELECT 1 FROM agendamentos a
        WHERE a.id_nutricionista = hp.id_profissional
        AND DATE(a.data_hora) = hp.data_atendimento
        AND TIME(a.data_hora) >= hp.hora_inicio
        AND TIME(a.data_hora) < hp.hora_fim
    )
");

$corrigidos = $stmt->rowCount();
echo "<p><strong style='color: green;'>✓ {$corrigidos} horário(s) foram marcados como reservados!</strong></p>";

echo "<p><strong>2. Marcar como DISPONÍVEL</strong> horários que estão reservados mas NÃO têm agendamentos:</p>";

// Libera horários sem agendamentos
$stmt = $pdo->query("
    UPDATE horarios_profissionais hp
    SET hp.status = 'disponivel'
    WHERE hp.status = 'reservado'
    AND NOT EXISTS (
        SELECT 1 FROM agendamentos a
        WHERE a.id_nutricionista = hp.id_profissional
        AND DATE(a.data_hora) = hp.data_atendimento
        AND TIME(a.data_hora) >= hp.hora_inicio
        AND TIME(a.data_hora) < hp.hora_fim
    )
");

$liberados = $stmt->rowCount();
echo "<p><strong style='color: blue;'>✓ {$liberados} horário(s) foram liberados!</strong></p>";

echo "<p><a href='test-horarios-status.php'>Atualizar</a></p>";
?>
