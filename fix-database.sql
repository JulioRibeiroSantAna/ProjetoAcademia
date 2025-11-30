-- Script para corrigir a estrutura atual
-- Remove a coluna status (não é mais necessária)
-- A disponibilidade é verificada consultando a tabela agendamentos

ALTER TABLE horarios_profissionais DROP COLUMN IF EXISTS status;

-- Garante que não há duplicatas
ALTER TABLE horarios_profissionais 
ADD UNIQUE KEY unique_horario (id_profissional, data_atendimento, hora_inicio, hora_fim);
