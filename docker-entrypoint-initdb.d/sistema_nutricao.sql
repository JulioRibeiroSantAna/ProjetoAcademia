DROP TABLE IF EXISTS `agendamentos`;
CREATE TABLE `agendamentos` (
  `id_agendamento` int NOT NULL AUTO_INCREMENT,
  `id_nutricionista` int NOT NULL,
  `id_usuario` int NOT NULL,
  `data_hora` datetime NOT NULL,
  PRIMARY KEY (`id_agendamento`),
  KEY `id_nutricionista` (`id_nutricionista`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `agendamentos` WRITE;
/*!40000 ALTER TABLE `agendamentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `agendamentos` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `horarios_profissionais`;
CREATE TABLE `horarios_profissionais` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_profissional` int NOT NULL,
  `data_atendimento` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `status` enum('disponivel','reservado') DEFAULT 'disponivel',
  PRIMARY KEY (`id`),
  KEY `id_profissional` (`id_profissional`),
  KEY `idx_profissional_data` (`id_profissional`,`data_atendimento`),
  CONSTRAINT `horarios_profissionais_ibfk_1` FOREIGN KEY (`id_profissional`) REFERENCES `profissionais` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `horarios_profissionais` WRITE;
/*!40000 ALTER TABLE `horarios_profissionais` DISABLE KEYS */;
INSERT INTO `horarios_profissionais` VALUES 
(1,1,'2025-12-02','08:00:00','10:00:00','disponivel'),
(2,1,'2025-12-02','14:00:00','16:00:00','disponivel'),
(3,1,'2025-12-04','08:00:00','10:00:00','disponivel'),
(4,1,'2025-12-04','14:00:00','16:00:00','disponivel'),
(5,1,'2025-12-06','08:00:00','10:00:00','disponivel'),
(6,1,'2025-12-06','14:00:00','16:00:00','disponivel'),
(7,2,'2025-12-02','09:00:00','11:00:00','disponivel'),
(8,2,'2025-12-03','09:00:00','11:00:00','disponivel'),
(9,2,'2025-12-03','15:00:00','17:00:00','disponivel'),
(10,2,'2025-12-05','09:00:00','11:00:00','disponivel'),
(11,2,'2025-12-05','15:00:00','17:00:00','disponivel'),
(12,2,'2025-12-06','09:00:00','11:00:00','disponivel'),
(13,3,'2025-12-02','07:00:00','09:00:00','disponivel'),
(14,3,'2025-12-02','13:00:00','15:00:00','disponivel'),
(15,3,'2025-12-03','07:00:00','09:00:00','disponivel'),
(16,3,'2025-12-04','07:00:00','09:00:00','disponivel'),
(17,3,'2025-12-04','13:00:00','15:00:00','disponivel'),
(18,3,'2025-12-05','07:00:00','09:00:00','disponivel'),
(19,3,'2025-12-06','07:00:00','09:00:00','disponivel'),
(20,4,'2025-12-02','10:00:00','12:00:00','disponivel'),
(21,4,'2025-12-02','15:00:00','17:00:00','disponivel'),
(22,4,'2025-12-03','10:00:00','12:00:00','disponivel'),
(23,4,'2025-12-04','10:00:00','12:00:00','disponivel'),
(24,4,'2025-12-04','15:00:00','17:00:00','disponivel'),
(25,4,'2025-12-05','10:00:00','12:00:00','disponivel'),
(26,4,'2025-12-05','15:00:00','17:00:00','disponivel'),
(27,4,'2025-12-06','10:00:00','12:00:00','disponivel'),
(28,4,'2025-12-07','09:00:00','11:00:00','disponivel');
/*!40000 ALTER TABLE `horarios_profissionais` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `profissionais`;
CREATE TABLE `profissionais` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `especialidade` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `descricao` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `profissionais` WRITE;
/*!40000 ALTER TABLE `profissionais` DISABLE KEYS */;
INSERT INTO `profissionais` VALUES (1,'Neymar Jr','Nutricionista','profissional1@gmail.com','(51) 99999-9999','O homi é bom!','uploads/profissionais/prof_692c6ef174578_1764519665.png','2025-11-30 16:21:05'),(2,'Luis Suárez','Médico Endocrinologista','profissional2@gmail.com','(51) 99999-9998','Beije a pistola!','uploads/profissionais/prof_692c6f2b4e16b_1764519723.png','2025-11-30 16:22:03'),(3,'Cristiano Ronaldo','Educador Físico','profissional3@gmail.com','(51) 99999-9997','SIUUUUUUUUUUUUUUUUUUU!','uploads/profissionais/prof_692c6f524cd1c_1764519762.png','2025-11-30 16:22:42'),(4,'kylian mbappé','Psicólogo','profissional4@gmail.com','(51) 99999-9996','É o tartarugão!','uploads/profissionais/prof_692c6f7d682c1_1764519805.png','2025-11-30 16:23:25');
/*!40000 ALTER TABLE `profissionais` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `topicos`;
CREATE TABLE `topicos` (
  `id_topico` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_topico`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `topicos` WRITE;
/*!40000 ALTER TABLE `topicos` DISABLE KEYS */;
INSERT INTO `topicos` VALUES (1,'Receitas'),(2,'Dicas'),(3,'Exercícios'),(4,'Nutrição'),(5,'Bem-estar'),(6,'Ganho de Massa');
/*!40000 ALTER TABLE `topicos` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `apelido` varchar(30) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','usuario','nutricionista') NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Administrador','Admin','admin@mef.com','$2y$10$nqsItPRPFUrI4BR3dcV/PeTMgwVTbQbUdLZB7EwJsltJnJWukf13O','admin','(51) 99329-7009','uploads/usuarios/user_1_1764520312.png'),(2,'User1','User','teste1@gmail.com','$2y$10$c3RxyY3z78oONTkwZb9vTuyWxeg.AFKqlVzkPYFX26dgGOFIXai7O','usuario','(51) 99999-9999','uploads/usuarios/user_2_1764520509.png');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id_video` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `descricao` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `arquivo_video` varchar(255) DEFAULT NULL,
  `thumbnail_video` varchar(255) DEFAULT NULL,
  `tipo_video` enum('url','arquivo') DEFAULT 'url',
  `data_upload` datetime DEFAULT CURRENT_TIMESTAMP,
  `id_nutricionista` int DEFAULT NULL,
  PRIMARY KEY (`id_video`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
INSERT INTO `videos` VALUES (1,'Como NÃO jogar futebol – versão Inter','Como NÃO jogar futebol – versão Inter','https://www.youtube.com/embed/lehWosjgnKg',NULL,NULL,'url','2025-11-30 16:25:10',1),(2,'Inter sendo Inter: a arte de sofrer','Inter sendo Inter: a arte de sofrer','https://www.youtube.com/embed/mweFW0uiH_o',NULL,NULL,'url','2025-11-30 16:25:42',1),(3,'Melhores momentos? Só para o adversário…','Melhores momentos? Só para o adversário…','https://www.youtube.com/embed/pCtSq9XaBQo',NULL,NULL,'url','2025-11-30 16:26:31',1),(4,'Inter: especialista em entregar o ouro','Inter: especialista em entregar o ouro','https://www.youtube.com/embed/bqDZnSap9ao',NULL,NULL,'url','2025-11-30 16:27:17',1),(5,'Como estragar um jogo em 3 passos — Tutorial do Inter','Como estragar um jogo em 3 passos — Tutorial do Inter','https://www.youtube.com/embed/HIZ9rk8RkdE',NULL,NULL,'url','2025-11-30 16:28:31',1),(6,'Grêmio: o gigante que o Inter nunca alcança','Grêmio: onde nasce a raça, não a desculpa','','692c71331286b_1764520243.mp4','692c71330a584_1764520243.png','arquivo','2025-11-30 16:30:43',1);
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `videos_topicos`;
CREATE TABLE `videos_topicos` (
  `videos_id` int NOT NULL,
  `topicos_id` int NOT NULL,
  PRIMARY KEY (`videos_id`,`topicos_id`),
  KEY `topicos_id` (`topicos_id`),
  CONSTRAINT `videos_topicos_ibfk_1` FOREIGN KEY (`videos_id`) REFERENCES `videos` (`id_video`),
  CONSTRAINT `videos_topicos_ibfk_2` FOREIGN KEY (`topicos_id`) REFERENCES `topicos` (`id_topico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `videos_topicos` WRITE;
/*!40000 ALTER TABLE `videos_topicos` DISABLE KEYS */;
INSERT INTO `videos_topicos` VALUES (1,1),(2,2),(3,3),(4,4),(5,5),(6,6);
/*!40000 ALTER TABLE `videos_topicos` ENABLE KEYS */;
UNLOCK TABLES;
