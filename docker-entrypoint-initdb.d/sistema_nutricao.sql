/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `usuarios` VALUES (1,'Administrador','Admin','admin@mef.com','$2y$10$nqsItPRPFUrI4BR3dcV/PeTMgwVTbQbUdLZB7EwJsltJnJWukf13O','admin','(51) 99329-7009','uploads/usuarios/user_1_1764520312.png'),(2,'User1','User','teste1@gmail.com','$2y$10$c3RxyY3z78oONTkwZb9vTuyWxeg.AFKqlVzkPYFX26dgGOFIXai7O','usuario','(51) 99999-9999','uploads/usuarios/user_2_1764520509.png'),(3,'user2','user','teste2@gmail.com','$2y$10$sYy5oIABURCUTmOlcDsr.uaVjC5W.a2bXe6J7eR9hJd6KnvkDvTZu','usuario','(51) 99999-9998',NULL);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `profissionais` VALUES (3,'Luis Suárez','Nutricionista','teste1@gmail.com','(51) 99999-9999','Beije a PISTOLA!','uploads/profissionais/prof_692ca24888436_1764532808.png','2025-11-30 20:00:08'),(4,'kylian mbappé','Nutricionista','teste2@gmail.com','(51) 99999-9998','Tartarugaaaaaaaaaaaaa','uploads/profissionais/prof_692ca2954b33e_1764532885.png','2025-11-30 20:01:25'),(5,'Neymar Jr','Médico Endocrinologista','teste3@gmail.com','(51) 99999-9997','10 do Hexa','uploads/profissionais/prof_692ca2fe97066_1764532990.png','2025-11-30 20:03:10'),(6,'Cristiano Ronaldo','Fisioterapeuta','teste4@gmail.com','(51) 99999-9996','SIUUUUUUUUUUU','uploads/profissionais/prof_692ca35034154_1764533072.png','2025-11-30 20:04:32');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `topicos` (
  `id_topico` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_topico`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `topicos` VALUES (1,'Receitas'),(2,'Dicas'),(3,'Exercícios'),(4,'Nutrição'),(5,'Bem-estar'),(6,'Ganho de Massa');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `videos` VALUES (1,'Como NÃO jogar futebol – versão Inter','Como NÃO jogar futebol – versão Inter','https://www.youtube.com/embed/lehWosjgnKg',NULL,NULL,'url','2025-11-30 16:25:10',1),(2,'Inter sendo Inter: a arte de sofrer','Inter sendo Inter: a arte de sofrer','https://www.youtube.com/embed/mweFW0uiH_o',NULL,NULL,'url','2025-11-30 16:25:42',1),(3,'Melhores momentos? Só para o adversário…','Melhores momentos? Só para o adversário…','https://www.youtube.com/embed/pCtSq9XaBQo',NULL,NULL,'url','2025-11-30 16:26:31',1),(4,'Inter: especialista em entregar o ouro','Inter: especialista em entregar o ouro','https://www.youtube.com/embed/bqDZnSap9ao',NULL,NULL,'url','2025-11-30 16:27:17',1),(5,'Como estragar um jogo em 3 passos — Tutorial do Inter','Como estragar um jogo em 3 passos — Tutorial do Inter','https://www.youtube.com/embed/HIZ9rk8RkdE',NULL,NULL,'url','2025-11-30 16:28:31',1),(6,'Grêmio: o gigante que o Inter nunca alcança','Grêmio: onde nasce a raça, não a desculpa','','692c71331286b_1764520243.mp4','692c71330a584_1764520243.png','arquivo','2025-11-30 16:30:43',1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `videos_topicos` (
  `videos_id` int NOT NULL,
  `topicos_id` int NOT NULL,
  PRIMARY KEY (`videos_id`,`topicos_id`),
  KEY `topicos_id` (`topicos_id`),
  CONSTRAINT `videos_topicos_ibfk_1` FOREIGN KEY (`videos_id`) REFERENCES `videos` (`id_video`),
  CONSTRAINT `videos_topicos_ibfk_2` FOREIGN KEY (`topicos_id`) REFERENCES `topicos` (`id_topico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `videos_topicos` VALUES (1,1),(2,2),(3,3),(4,4),(5,5),(6,6);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agendamentos` (
  `id_agendamento` int NOT NULL AUTO_INCREMENT,
  `id_nutricionista` int NOT NULL,
  `id_usuario` int NOT NULL,
  `data_hora` datetime NOT NULL,
  PRIMARY KEY (`id_agendamento`),
  KEY `id_nutricionista` (`id_nutricionista`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `agendamentos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `agendamentos` VALUES (6,4,1,'2025-12-06 21:00:00'),(7,3,2,'2025-12-25 22:05:00');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horarios_profissionais` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_profissional` int NOT NULL,
  `data_atendimento` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_profissional_data` (`id_profissional`,`data_atendimento`),
  CONSTRAINT `horarios_profissionais_ibfk_1` FOREIGN KEY (`id_profissional`) REFERENCES `profissionais` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `horarios_profissionais` VALUES (6,4,'2025-12-06','21:00:00','21:40:00'),(7,5,'2025-12-12','17:59:00','18:08:00'),(9,3,'2025-12-25','22:05:00','23:05:00');

