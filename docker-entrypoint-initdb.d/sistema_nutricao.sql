-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09/10/2025 às 00:40
-- Versão do servidor: 8.0.41
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_nutricao`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos`
--

CREATE TABLE `agendamentos` (
  `id_agendamento` int NOT NULL,
  `id_nutricionista` int NOT NULL,
  `id_usuario` int NOT NULL,
  `data_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidade` int NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `horarios_nutricionistas`
--

CREATE TABLE `horarios_nutricionistas` (
  `id_horario` int NOT NULL,
  `id_nutricionista` int NOT NULL,
  `dia_semana` enum('segunda','terça','quarta','quinta','sexta','sábado','domingo') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id_mensagem` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_nutricionista` int NOT NULL,
  `conteudo` text NOT NULL,
  `data_envio` datetime DEFAULT CURRENT_TIMESTAMP,
  `editada` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `nutricionistas`
--

CREATE TABLE `nutricionistas` (
  `id_nutricionista` int NOT NULL,
  `id_especialidade` int NOT NULL,
  `descricao` text,
  `foto_perfil` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `topicos`
--

CREATE TABLE `topicos` (
  `id_topico` int NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','usuario','nutricionista') NOT NULL,
  `telefone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `videos`
--

CREATE TABLE `videos` (
  `id_video` int NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `data_upload` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_nutricionista` int DEFAULT NULL,
  `capa_video` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `videos_topicos`
--

CREATE TABLE `videos_topicos` (
  `videos_id` int NOT NULL,
  `topicos_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD PRIMARY KEY (`id_agendamento`),
  ADD KEY `id_nutricionista` (`id_nutricionista`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidade`);

--
-- Índices de tabela `horarios_nutricionistas`
--
ALTER TABLE `horarios_nutricionistas`
  ADD PRIMARY KEY (`id_horario`),
  ADD KEY `id_nutricionista` (`id_nutricionista`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id_mensagem`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_nutricionista` (`id_nutricionista`);

--
-- Índices de tabela `nutricionistas`
--
ALTER TABLE `nutricionistas`
  ADD PRIMARY KEY (`id_nutricionista`),
  ADD KEY `id_especialidade` (`id_especialidade`);

--
-- Índices de tabela `topicos`
--
ALTER TABLE `topicos`
  ADD PRIMARY KEY (`id_topico`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id_video`),
  ADD KEY `id_nutricionista` (`id_nutricionista`);

--
-- Índices de tabela `videos_topicos`
--
ALTER TABLE `videos_topicos`
  ADD PRIMARY KEY (`videos_id`,`topicos_id`),
  ADD KEY `topicos_id` (`topicos_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos`
--
ALTER TABLE `agendamentos`
  MODIFY `id_agendamento` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidade` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `horarios_nutricionistas`
--
ALTER TABLE `horarios_nutricionistas`
  MODIFY `id_horario` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id_mensagem` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `topicos`
--
ALTER TABLE `topicos`
  MODIFY `id_topico` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `videos`
--
ALTER TABLE `videos`
  MODIFY `id_video` int NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `agendamentos`
--
ALTER TABLE `agendamentos`
  ADD CONSTRAINT `agendamentos_ibfk_1` FOREIGN KEY (`id_nutricionista`) REFERENCES `nutricionistas` (`id_nutricionista`),
  ADD CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Restrições para tabelas `horarios_nutricionistas`
--
ALTER TABLE `horarios_nutricionistas`
  ADD CONSTRAINT `horarios_nutricionistas_ibfk_1` FOREIGN KEY (`id_nutricionista`) REFERENCES `nutricionistas` (`id_nutricionista`);

--
-- Restrições para tabelas `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `mensagens_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `mensagens_ibfk_2` FOREIGN KEY (`id_nutricionista`) REFERENCES `nutricionistas` (`id_nutricionista`);

--
-- Restrições para tabelas `nutricionistas`
--
ALTER TABLE `nutricionistas`
  ADD CONSTRAINT `nutricionistas_ibfk_1` FOREIGN KEY (`id_nutricionista`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `nutricionistas_ibfk_2` FOREIGN KEY (`id_especialidade`) REFERENCES `especialidades` (`id_especialidade`);

--
-- Restrições para tabelas `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`id_nutricionista`) REFERENCES `nutricionistas` (`id_nutricionista`);

--
-- Restrições para tabelas `videos_topicos`
--
ALTER TABLE `videos_topicos`
  ADD CONSTRAINT `videos_topicos_ibfk_1` FOREIGN KEY (`videos_id`) REFERENCES `videos` (`id_video`),
  ADD CONSTRAINT `videos_topicos_ibfk_2` FOREIGN KEY (`topicos_id`) REFERENCES `topicos` (`id_topico`);

--
-- Inserindo dados padrão
--

-- Inserindo especialidade padrão
INSERT INTO `especialidades` (`id_especialidade`, `nome`) VALUES
(1, 'Nutrição Clínica');

-- Inserindo usuário padrão
INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `senha`, `tipo`, `telefone`) VALUES
(1, 'Nutricionista Padrão', 'nutricionista@exemplo.com', MD5('senha123'), 'nutricionista', '123456789');

-- Inserindo nutricionista vinculado ao usuário padrão
INSERT INTO `nutricionistas` (`id_nutricionista`, `id_especialidade`, `descricao`, `foto_perfil`) VALUES
(1, 1, 'Nutricionista especializado em dietas personalizadas.', NULL);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
