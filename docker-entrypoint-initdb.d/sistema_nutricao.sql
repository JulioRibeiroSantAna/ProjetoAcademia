-- Atualizar sistema_nutricao.sql
-- phpMyAdmin SQL Dump
-- version 5.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Banco de dados: `sistema_nutricao`

-- Estrutura para tabela `usuarios`
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `apelido` varchar(30) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','usuario','nutricionista') NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Estrutura SIMPLIFICADA para tabela `profissionais` (sem login)
CREATE TABLE `profissionais` (
  `id` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `especialidade` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `descricao` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Estrutura para tabela `agendamentos` (CORRIGIDA)
CREATE TABLE `agendamentos` (
  `id_agendamento` int NOT NULL,
  `id_nutricionista` int NOT NULL,
  `id_usuario` int NOT NULL,
  `data_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Estrutura para tabela `videos`
CREATE TABLE `videos` (
  `id_video` int NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `arquivo_video` varchar(255) DEFAULT NULL,
  `tipo_video` enum('url','arquivo') DEFAULT 'url',
  `data_upload` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_nutricionista` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Estrutura para tabela `topicos`
CREATE TABLE `topicos` (
  `id_topico` int NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Estrutura para tabela `videos_topicos`
CREATE TABLE `videos_topicos` (
  `videos_id` int NOT NULL,
  `topicos_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Índices das tabelas
ALTER TABLE `usuarios` ADD PRIMARY KEY (`id_usuario`), ADD UNIQUE KEY `email` (`email`);
ALTER TABLE `profissionais` ADD PRIMARY KEY (`id`);
ALTER TABLE `agendamentos` ADD PRIMARY KEY (`id_agendamento`), ADD KEY `id_nutricionista` (`id_nutricionista`), ADD KEY `id_usuario` (`id_usuario`);
ALTER TABLE `videos` ADD PRIMARY KEY (`id_video`);
ALTER TABLE `topicos` ADD PRIMARY KEY (`id_topico`);
ALTER TABLE `videos_topicos` ADD PRIMARY KEY (`videos_id`,`topicos_id`), ADD KEY `topicos_id` (`topicos_id`);

-- AUTO_INCREMENT das tabelas
ALTER TABLE `usuarios` MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `profissionais` MODIFY `id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `agendamentos` MODIFY `id_agendamento` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `videos` MODIFY `id_video` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `topicos` MODIFY `id_topico` int NOT NULL AUTO_INCREMENT;

-- Restrições para tabelas (SIMPLIFICADAS)
ALTER TABLE `agendamentos` ADD CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
ALTER TABLE `videos_topicos` ADD CONSTRAINT `videos_topicos_ibfk_1` FOREIGN KEY (`videos_id`) REFERENCES `videos` (`id_video`);
ALTER TABLE `videos_topicos` ADD CONSTRAINT `videos_topicos_ibfk_2` FOREIGN KEY (`topicos_id`) REFERENCES `topicos` (`id_topico`);

-- INSERIR APENAS ADMIN - SEM PROFISSIONAL PRÉ-CADASTRADO
INSERT INTO `usuarios` (`id_usuario`, `nome`, `apelido`, `email`, `senha`, `tipo`, `telefone`) VALUES
(1, 'Administrador', 'Admin', 'admin@mef.com', 'admin123', 'admin', '11999999999');

-- INSERIR APENAS TÓPICOS PADRÃO
INSERT INTO `topicos` (`id_topico`, `nome`) VALUES
(1, 'Receitas'),
(2, 'Dicas'),
(3, 'Exercícios'),
(4, 'Nutrição');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;1 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
