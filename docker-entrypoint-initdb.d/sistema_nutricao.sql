-- MySQL dump 10.13  Distrib 8.0.44, for Linux (x86_64)
--
-- Host: localhost    Database: sistema_nutricao
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `agendamentos`
--

DROP TABLE IF EXISTS `agendamentos`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agendamentos`
--

LOCK TABLES `agendamentos` WRITE;
/*!40000 ALTER TABLE `agendamentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `agendamentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profissionais`
--

DROP TABLE IF EXISTS `profissionais`;
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profissionais`
--

LOCK TABLES `profissionais` WRITE;
/*!40000 ALTER TABLE `profissionais` DISABLE KEYS */;
INSERT INTO `profissionais` VALUES (1,'Neymar Jr','Nutricionista','profissional1@gmail.com','(51) 99999-9999','O homi é bom!','uploads/profissionais/prof_692c6ef174578_1764519665.png','2025-11-30 16:21:05'),(2,'Luis Suárez','Médico Endocrinologista','profissional2@gmail.com','(51) 99999-9998','Beije a pistola!','uploads/profissionais/prof_692c6f2b4e16b_1764519723.png','2025-11-30 16:22:03'),(3,'Cristiano Ronaldo','Educador Físico','profissional3@gmail.com','(51) 99999-9997','SIUUUUUUUUUUUUUUUUUUU!','uploads/profissionais/prof_692c6f524cd1c_1764519762.png','2025-11-30 16:22:42'),(4,'kylian mbappé','Psicólogo','profissional4@gmail.com','(51) 99999-9996','É o tartarugão!','uploads/profissionais/prof_692c6f7d682c1_1764519805.png','2025-11-30 16:23:25');
/*!40000 ALTER TABLE `profissionais` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topicos`
--

DROP TABLE IF EXISTS `topicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `topicos` (
  `id_topico` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`id_topico`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topicos`
--

LOCK TABLES `topicos` WRITE;
/*!40000 ALTER TABLE `topicos` DISABLE KEYS */;
INSERT INTO `topicos` VALUES (1,'Receitas'),(2,'Dicas'),(3,'Exercícios'),(4,'Nutrição'),(5,'Bem-estar'),(6,'Ganho de Massa');
/*!40000 ALTER TABLE `topicos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Administrador','Admin','admin@mef.com','$2y$10$nqsItPRPFUrI4BR3dcV/PeTMgwVTbQbUdLZB7EwJsltJnJWukf13O','admin','(51) 99329-7009','uploads/usuarios/user_1_1764520312.png'),(2,'User1','User','teste1@gmail.com','$2y$10$c3RxyY3z78oONTkwZb9vTuyWxeg.AFKqlVzkPYFX26dgGOFIXai7O','usuario','(51) 99999-9999','uploads/usuarios/user_2_1764520509.png');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
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

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
INSERT INTO `videos` VALUES (1,'Como NÃO jogar futebol – versão Inter','Como NÃO jogar futebol – versão Inter','https://www.youtube.com/embed/lehWosjgnKg',NULL,NULL,'url','2025-11-30 16:25:10',1),(2,'Inter sendo Inter: a arte de sofrer','Inter sendo Inter: a arte de sofrer','https://www.youtube.com/embed/mweFW0uiH_o',NULL,NULL,'url','2025-11-30 16:25:42',1),(3,'Melhores momentos? Só para o adversário…','Melhores momentos? Só para o adversário…','https://www.youtube.com/embed/pCtSq9XaBQo',NULL,NULL,'url','2025-11-30 16:26:31',1),(4,'Inter: especialista em entregar o ouro','Inter: especialista em entregar o ouro','https://www.youtube.com/embed/bqDZnSap9ao',NULL,NULL,'url','2025-11-30 16:27:17',1),(5,'Como estragar um jogo em 3 passos — Tutorial do Inter','Como estragar um jogo em 3 passos — Tutorial do Inter','https://www.youtube.com/embed/HIZ9rk8RkdE',NULL,NULL,'url','2025-11-30 16:28:31',1),(6,'Grêmio: o gigante que o Inter nunca alcança','Grêmio: onde nasce a raça, não a desculpa','','692c71331286b_1764520243.mp4','692c71330a584_1764520243.png','arquivo','2025-11-30 16:30:43',1);
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos_topicos`
--

DROP TABLE IF EXISTS `videos_topicos`;
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

--
-- Dumping data for table `videos_topicos`
--

LOCK TABLES `videos_topicos` WRITE;
/*!40000 ALTER TABLE `videos_topicos` DISABLE KEYS */;
INSERT INTO `videos_topicos` VALUES (1,1),(2,2),(3,3),(4,4),(5,5),(6,6);
/*!40000 ALTER TABLE `videos_topicos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-30 16:38:22
