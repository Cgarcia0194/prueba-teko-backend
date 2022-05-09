-- MySQL dump 10.13  Distrib 8.0.28, for Win64 (x86_64)
--
-- Host: localhost    Database: prueba_teko
-- ------------------------------------------------------
-- Server version	8.0.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `aplicacion`
--

DROP TABLE IF EXISTS `aplicacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aplicacion` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(500) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aplicacion`
--

LOCK TABLES `aplicacion` WRITE;
/*!40000 ALTER TABLE `aplicacion` DISABLE KEYS */;
INSERT INTO `aplicacion` VALUES (1,'Prueba');
/*!40000 ALTER TABLE `aplicacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `archivo`
--

DROP TABLE IF EXISTS `archivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archivo` (
  `num_archivo` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_archivo` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `path` varchar(600) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `estatus` enum('Pendiente','Autorizado','Rechazado') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_autoriza` int(10) unsigned DEFAULT NULL,
  `fecha_autoriza` datetime DEFAULT NULL,
  PRIMARY KEY (`num_archivo`) USING BTREE,
  UNIQUE KEY `id_archivo` (`id_archivo`) USING BTREE,
  KEY `id_autoriza` (`id_autoriza`) USING BTREE,
  KEY `estatus` (`estatus`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archivo`
--

LOCK TABLES `archivo` WRITE;
/*!40000 ALTER TABLE `archivo` DISABLE KEYS */;
INSERT INTO `archivo` VALUES (1,'7a0c8429-113d-49aa-bb34-3851d6d26737','/uploads/7a0c8429-113d-49aa-bb34-3851d6d26737.jpg','7a0c8429-113d-49aa-bb34-3851d6d26737.jpg','2021-04-07 16:04:34','Pendiente',1,1,NULL);
/*!40000 ALTER TABLE `archivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `apellido_paterno` varchar(255) NOT NULL,
  `apellido_materno` varchar(255) NOT NULL,
  `genero` enum('Hombre','Mujer','Sin especificar') NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `fecha_egreso` date DEFAULT NULL,
  `servicio` int(11) NOT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colaborador`
--

DROP TABLE IF EXISTS `colaborador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colaborador` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `telefono_oficina` text NOT NULL,
  `ext` text NOT NULL,
  `horario_oficina` text NOT NULL,
  `user` int(11) NOT NULL DEFAULT '0',
  `rol` int(11) NOT NULL,
  `relevancia` int(11) NOT NULL,
  `persona` int(11) NOT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colaborador`
--

LOCK TABLES `colaborador` WRITE;
/*!40000 ALTER TABLE `colaborador` DISABLE KEYS */;
INSERT INTO `colaborador` VALUES (1,'+52 (477) 710-00-20','129','09:00 a 17:00',1,1,1,1,'Activo','2022-02-02 15:31:33'),(2,'+52 (477) 710-00-20','129','09:00 a 17:00',2,1,1,2,'Activo','2022-02-02 15:31:33');
/*!40000 ALTER TABLE `colaborador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion`
--

DROP TABLE IF EXISTS `configuracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracion` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `color` varchar(255) NOT NULL,
  `complemento` varchar(255) NOT NULL,
  `modo_oscuro` enum('Si','No') NOT NULL,
  `modo_input` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `modo_pantalla_completa` enum('Si','No') NOT NULL,
  `user` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion`
--

LOCK TABLES `configuracion` WRITE;
/*!40000 ALTER TABLE `configuracion` DISABLE KEYS */;
INSERT INTO `configuracion` VALUES (1,'cyan','darken-2','No','filled','No',1,'2022-02-22 14:02:08'),(2,'teal','darken-3','No','outlined','No',2,'2022-02-22 14:40:14');
/*!40000 ALTER TABLE `configuracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_seguridad`
--

DROP TABLE IF EXISTS `grupo_seguridad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo_seguridad` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(1000) NOT NULL,
  `descripcion` text NOT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL,
  `responsable_actualizacion` int(11) NOT NULL,
  `fecha_actualizacion` datetime NOT NULL,
  `responsable_registro` int(11) DEFAULT NULL,
  `fecha_registro` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`_id`),
  UNIQUE KEY `_id_UNIQUE` (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_seguridad`
--

LOCK TABLES `grupo_seguridad` WRITE;
/*!40000 ALTER TABLE `grupo_seguridad` DISABLE KEYS */;
INSERT INTO `grupo_seguridad` VALUES (1,'Desarrollador','Desarrolladores.','Activo',1,'2022-02-15 15:11:16',1,'2017-07-25 15:15:05'),(2,'Administrador','Administradores general','Activo',1,'2020-04-21 12:51:48',1,'2020-04-21 12:51:48');
/*!40000 ALTER TABLE `grupo_seguridad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_seguridad_plantilla`
--

DROP TABLE IF EXISTS `grupo_seguridad_plantilla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo_seguridad_plantilla` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_seguridad` int(11) NOT NULL,
  `plantilla` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`_id`),
  UNIQUE KEY `_id_UNIQUE` (`_id`),
  KEY `fk_grupo_seguridad_GRSEPL` (`grupo_seguridad`),
  KEY `fk_plantilla_GRSEPL` (`plantilla`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_seguridad_plantilla`
--

LOCK TABLES `grupo_seguridad_plantilla` WRITE;
/*!40000 ALTER TABLE `grupo_seguridad_plantilla` DISABLE KEYS */;
INSERT INTO `grupo_seguridad_plantilla` VALUES (7,1,4,'2022-01-25 13:34:29'),(13,2,9,'2022-01-28 12:07:32'),(14,2,8,'2022-01-28 12:07:33'),(15,2,10,'2022-01-28 12:07:33'),(16,2,11,'2022-01-28 12:07:34'),(17,2,12,'2022-01-28 12:07:34'),(27,1,18,'2022-02-02 01:23:34'),(33,1,27,'2022-05-05 18:34:24'),(34,1,31,'2022-05-09 22:08:16'),(35,1,32,'2022-05-09 22:08:18'),(37,1,3,'2022-05-09 22:25:42'),(38,1,29,'2022-05-09 22:31:04');
/*!40000 ALTER TABLE `grupo_seguridad_plantilla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_seguridad_user`
--

DROP TABLE IF EXISTS `grupo_seguridad_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo_seguridad_user` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_seguridad` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`_id`),
  UNIQUE KEY `_id_UNIQUE` (`_id`),
  KEY `fk_usuario_GRSEUS` (`user`),
  KEY `fk_grupo_seguridad_GRSEUS` (`grupo_seguridad`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_seguridad_user`
--

LOCK TABLES `grupo_seguridad_user` WRITE;
/*!40000 ALTER TABLE `grupo_seguridad_user` DISABLE KEYS */;
INSERT INTO `grupo_seguridad_user` VALUES (1,1,1,'2020-04-15 12:22:05'),(2,1,2,'2021-08-31 08:43:19'),(5,1,9,'2022-05-03 23:53:07'),(6,1,10,'2022-05-03 23:53:10');
/*!40000 ALTER TABLE `grupo_seguridad_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `total` float(11,2) NOT NULL,
  `recargo` float(11,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `cliente` int(11) NOT NULL,
  `estatus` enum('Cancelado','Pagado') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido_paterno` varchar(100) DEFAULT NULL,
  `apellido_materno` varchar(100) NOT NULL,
  `rfc` varchar(20) NOT NULL,
  `curp` varchar(50) NOT NULL,
  `sexo` enum('Hombre','Mujer','Sin especificar') NOT NULL,
  `telefono` text NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `correo_electronico` varchar(250) NOT NULL,
  `estado_civil` int(11) NOT NULL,
  `municipio` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `f_perfil` char(36) DEFAULT NULL,
  `f_portada` char(36) DEFAULT NULL,
  PRIMARY KEY (`_id`),
  KEY `FK_IdMunicipio_P` (`municipio`),
  KEY `FK_municipio_persona` (`municipio`),
  KEY `Fk_estado_civil_persona` (`estado_civil`),
  KEY `Idx_curp` (`curp`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (1,'Carlos','Rodríguez','García','xxxx000000000','xxxx000000000','Hombre','+42 (816) 945-55-','1994-01-22','carlos_garciarodriguez@gmail.com',1,355,'2022-02-02 15:08:01','7a0c8429-113d-49aa-bb34-3851d6d26737',NULL),(2,'Usuario','García','Rodríguez','xxxx000000000','xxxx000000xxxxxxxx','Hombre','+42 (816) 945-55-','1994-01-22','carlos@gmail.com',1,345,'2022-02-02 15:08:01',NULL,NULL);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plantilla`
--

DROP TABLE IF EXISTS `plantilla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plantilla` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `name` varchar(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `component` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `icono` varchar(100) NOT NULL,
  `es_externo` enum('Si','No') NOT NULL,
  `es_menu` enum('Si','No') NOT NULL,
  `orden` int(11) NOT NULL DEFAULT '1',
  `descripcion` text NOT NULL,
  `padre` int(11) NOT NULL,
  `tipo_plantilla` enum('Catálogo','Proceso','Reporte','Público','Otro') NOT NULL,
  `estatus` enum('Activa','Inactiva') NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `responsable` int(11) NOT NULL,
  PRIMARY KEY (`_id`),
  UNIQUE KEY `_id_UNIQUE` (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plantilla`
--

LOCK TABLES `plantilla` WRITE;
/*!40000 ALTER TABLE `plantilla` DISABLE KEYS */;
INSERT INTO `plantilla` VALUES (1,'/','Sistema & configuraciones','-','vpn_lock','No','Si',3,'-',0,'Otro','Activa','2021-12-08 11:34:30',1),(2,'/','Permisos','-','lock','No','Si',1,'-',1,'Otro','Activa','2021-12-08 11:34:30',1),(3,'/control-de-plantillas','Ctrl. de plantillas & directorios','views/procesos/ControlPlantillas','assignment','No','No',1,'¡Hola, _NOMBRE_PERSONA_! en esta plantilla podrás llevar el control de las plantillas del sistema.',2,'Proceso','Activa','2019-02-22 06:38:33',1),(4,'/control-de-grupos-de-seguridad','Ctrl. de grupos de seguridad','views/procesos/ControlGrupoSeguridad','security','No','No',2,'¡Hola, _NOMBRE_PERSONA_! en esta plantilla podrás llevar el control de los grupos de seguridad, así como, las plantillas a las que tendrán acceso.',2,'Proceso','Activa','2019-02-23 15:45:19',1),(18,'/ctrl-de-permisos-a-personas','Ctrl. de permisos a personas','views/procesos/ControlPermisosUsuarios','screen_lock_portrait','No','No',1,'-',2,'Proceso','Activa','2022-02-02 01:16:27',1),(24,'/informacion-y-ayuda','Información y ayuda','views/procesos/ControlCalendarioProyectos','info','No','No',4,'-',0,'Público','Activa','2022-03-01 02:33:08',1),(25,'/','Prueba','-','quiz','No','Si',1,'-',0,'Otro','Activa','2021-12-08 11:34:30',1),(26,'/','Catálogos','-','list','No','Si',1,'-',25,'Otro','Activa','2021-12-08 11:34:30',1),(27,'/ctrl-de-servicios','Ctrl. de servicios','views/catalogos/ControlServicios','support_agent','No','No',1,'-',26,'Catálogo','Activa','2022-02-02 01:16:27',1),(28,'/','Procesos','-','account_tree','No','Si',1,'-',25,'Otro','Activa','2021-12-08 11:34:30',1),(29,'/ctrl-de-clientes','Ctrl. de clientes','views/catalogos/ControlClientes','person','No','No',2,'-',26,'Catálogo','Activa','2022-02-02 01:16:27',1),(30,'/','Reportes','-','ssid_chart','No','Si',1,'-',25,'Otro','Activa','2021-12-08 11:34:30',1),(31,'/ctrl-de-pagos','Ctrl. de pagos','views/procesos/ControlPagos','shopping_cart','No','No',1,'-',28,'Proceso','Activa','2022-02-02 01:16:27',1),(32,'/reporte-de-pagos','Reporte de pagos','views/reportes/ReportePagos','pie_chart','No','No',1,'-',30,'Reporte','Activa','2022-02-02 01:16:27',1);
/*!40000 ALTER TABLE `plantilla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicios`
--

DROP TABLE IF EXISTS `servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicios` (
  `_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `costo` float(11,2) NOT NULL,
  `descripcion` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `periodicidad` enum('Anual','Mensual','Quincenal','Semestral') NOT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios`
--

LOCK TABLES `servicios` WRITE;
/*!40000 ALTER TABLE `servicios` DISABLE KEYS */;
INSERT INTO `servicios` VALUES (1,'Netflix mensual familiar',299.00,'cuenta familiar, hasta 4 integrantes por solo $299/mes','Mensual','Activo','2022-05-09 23:21:35'),(2,'Netflix familiar anualidad',2990.00,'Netflix plan familiar anualidad, ahorras 2 meses','Anual','Activo','2022-05-09 23:22:51'),(3,'Deezer mensualidad plan familiar',179.00,'Deezer mensual hasta 5 integrantes más la cuenta principal','Mensual','Activo','2022-05-09 23:25:44'),(4,'Hbo max plan familiar',150.00,'Hbo plan familiar, deisfruta de hasta 5 cuentas','Mensual','Activo','2022-05-09 23:27:52'),(5,'HBO plan individual mensual',69.00,'HBO plan individual disfruta de series, etc a definición 4k...','Mensual','Activo','2022-05-09 23:28:35');
/*!40000 ALTER TABLE `servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(249) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `verified` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `resettable` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `roles_mask` int(10) unsigned NOT NULL DEFAULT '0',
  `registered` int(10) unsigned NOT NULL,
  `last_login` int(10) unsigned DEFAULT NULL,
  `force_logout` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `role` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `email` (`email`) USING BTREE,
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'carlos_garciarodriguez@gmail.com','$2y$10$tEgiG5Ky8/aRyQVXJHKzMOWmiwRxqZgMo1hn6CfpFCfEyx6AQPnW2','usuario 1',0,1,1,0,1640108670,1650557748,0,'Líder de Proyecto','Carlos Alberto','User'),(2,'carlos@gmail.com','$2y$10$tKxp7VNlaI5xrydDEdDME.a4.Ahgb/HKYJCsUF1G1Oy1UTAkz.dlm','usuario 2',0,1,1,0,1641352221,1646731885,0,'Líder de Proyecto','Carlos','García');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-09 13:19:58
