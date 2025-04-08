-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.24-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para canalti
CREATE DATABASE IF NOT EXISTS `canalti` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `canalti`;

-- Copiando estrutura para tabela canalti.estoque
CREATE TABLE IF NOT EXISTS `estoque` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome_produto` varchar(100) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `preco_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela canalti.estoque: ~1 rows (aproximadamente)
INSERT INTO `estoque` (`id`, `nome_produto`, `quantidade`, `preco_unitario`) VALUES
	(1, 'teste01', 1001, 1.50);

-- Copiando estrutura para tabela canalti.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `data_nascimento` date NOT NULL,
  `senha` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- Copiando dados para a tabela canalti.usuarios: ~3 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nome`, `email`, `data_nascimento`, `senha`, `is_admin`) VALUES
	(9, 'Guilherme Henrique de Souza Silva', 'guilherme.souza7@gmail.com', '1997-12-27', '$2y$10$yhg4mHXNNVJ5TJPqRh91XOvmxQBZPXmIKnvCOxleuSEdraMiJW2KC', 1),
	(10, 'Aline', 'aline@gmail.com', '2002-12-12', '$2y$10$fALaY3xLNw.NBW6Jv71jwuKxE.wB7/ij5tYwCLLewkylMQHkU47/y', 0),
	(11, 'Teste', 'teste@gmail.com', '2022-12-12', '$2y$10$Xnh4hY09KwjFBoAhZO.3uezxYXB84vXXE8tCclIw9AW/MwyWYLqMG', 0);

-- Copiando estrutura para procedure canalti.sp_adicionar_usuario
DELIMITER //
CREATE PROCEDURE `sp_adicionar_usuario`(
    IN p_nome VARCHAR(100),
    IN p_email VARCHAR(100),
    IN p_data_nascimento DATE,
    IN p_senha VARCHAR(255)
)
BEGIN
    IF p_nome = '' OR p_email = '' OR p_senha = '' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Nome, e-mail e senha não podem ser vazios.';
    ELSE
        INSERT INTO usuarios (nome, email, data_nascimento, senha)
        VALUES (p_nome, p_email, p_data_nascimento, p_senha);
    END IF;
END//
DELIMITER ;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
