-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14/10/2026 às 22:26
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_especialistas_v3`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `auditoria`
--

CREATE TABLE `auditoria` (
  `id_auditoria` bigint(20) NOT NULL,
  `id_utilizador` int(11) DEFAULT NULL,
  `categoria` varchar(80) NOT NULL,
  `evento` varchar(120) NOT NULL,
  `modulo` varchar(100) DEFAULT NULL,
  `entidade` varchar(100) DEFAULT NULL,
  `id_registo` int(11) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `dados_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_anteriores`)),
  `dados_novos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_novos`)),
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `localizacao` varchar(255) DEFAULT NULL,
  `resultado` varchar(20) NOT NULL DEFAULT 'SUCESSO',
  `data_evento` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `auditoria`
--

INSERT INTO `auditoria` (`id_auditoria`, `id_utilizador`, `categoria`, `evento`, `modulo`, `entidade`, `id_registo`, `descricao`, `dados_anteriores`, `dados_novos`, `ip`, `user_agent`, `localizacao`, `resultado`, `data_evento`) VALUES
(1, 1, 'UTILIZADORES', 'CRIACAO', 'UTILIZADORES', 'utilizadores', 2, 'Utilizador criado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 16:58:54'),
(2, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 16:59:10'),
(3, 2, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 2, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 16:59:21'),
(4, 2, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 2, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 16:59:56'),
(5, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 16:59:59'),
(6, 1, 'CONFIGURACAO', 'ALTERACAO', 'CONFIGURACOES', 'situacoes_servico', 5, 'Alteração de configuração: situacoes_servico #5', '{\"id_situacao_servico\":5,\"nome\":\"DOENTE\",\"grupo\":\"ATIVO\",\"descricao\":\"Estado funcional para consulta e acompanhamento.\",\"estado\":\"ATIVO\"}', '{\"id_situacao_servico\":5,\"nome\":\"DOENTE\",\"grupo\":\"INATIVO\",\"descricao\":\"Estado funcional para consulta e acompanhamento.\",\"estado\":\"ATIVO\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 20:35:11'),
(7, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 20:56:02'),
(8, 2, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 2, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 20:56:15'),
(9, 2, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 2, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 20:57:25'),
(10, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 20:57:27'),
(11, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 20:57:37'),
(12, 2, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 2, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 20:57:50'),
(13, 2, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 2, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 20:57:59'),
(14, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 20:58:02'),
(15, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 21:14:03'),
(16, 2, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 2, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 21:14:20'),
(17, 2, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 2, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 21:15:08'),
(18, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-08 21:15:11'),
(19, 1, 'ESPECIALISTAS', 'HISTORICO_CRIADO', 'ESPECIALISTAS', 'especialistas', 3, 'Registo de histórico: OUTRO', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 08:26:44'),
(20, 1, 'ESPECIALISTAS', 'CRIACAO', 'ESPECIALISTAS', 'especialistas', 3, 'Novo especialista criado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 08:26:44'),
(21, 1, 'ESPECIALISTAS', 'ALTERACAO', 'ESPECIALISTAS', 'especialistas', 3, 'Ficha do especialista atualizada.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 10:36:08'),
(22, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 10:46:28'),
(23, 2, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 2, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 10:46:45'),
(24, 2, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 2, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:27:32'),
(25, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:27:34'),
(26, 1, 'UTILIZADORES', 'CRIACAO', 'UTILIZADORES', 'utilizadores', 3, 'Utilizador criado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:28:35'),
(27, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:28:55'),
(28, 3, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 3, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:29:07'),
(29, 3, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 3, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:29:28'),
(30, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:29:31'),
(31, 1, 'UTILIZADORES', 'CRIACAO', 'UTILIZADORES', 'utilizadores', 4, 'Utilizador criado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:31:09'),
(32, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:31:21'),
(33, NULL, 'AUTENTICACAO', 'LOGIN_FALHADO', 'LOGIN', NULL, NULL, 'Tentativa de login: adminrh', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'FALHA', '2026-10-10 11:31:36'),
(34, 4, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 4, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:31:51'),
(35, 4, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 4, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:32:26'),
(36, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:32:29'),
(37, 1, 'CONFIGURACAO', 'CRIACAO', 'CONFIGURACOES', 'patentes', 1, 'Alteração de configuração: patentes #1', NULL, '{\"id_patente\":1,\"id_ramo\":3,\"nome\":\"TENENTE\",\"sigla\":\"TTE\",\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-10 11:33:53\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:33:53'),
(38, 1, 'ESPECIALISTAS', 'HISTORICO_CRIADO', 'ESPECIALISTAS', 'especialistas', 3, 'Registo de histórico: PROMOCAO', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:35:57'),
(39, 1, 'ESPECIALISTAS', 'ALTERACAO', 'ESPECIALISTAS', 'especialistas', 3, 'Ficha do especialista atualizada.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:35:57'),
(40, 1, 'CONFIGURACAO', 'ALTERACAO', 'CONFIGURACOES', 'patentes', 1, 'Alteração de configuração: patentes #1', '{\"id_patente\":1,\"id_ramo\":3,\"nome\":\"TENENTE\",\"sigla\":\"TTE\",\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-10 11:33:53\"}', '{\"id_patente\":1,\"id_ramo\":3,\"nome\":\"TENENTE\",\"sigla\":null,\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-10 11:33:53\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 11:36:37'),
(41, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 12:09:59'),
(42, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 12:13:36'),
(43, 1, 'UTILIZADORES', 'ALTERACAO', 'UTILIZADORES', 'utilizadores', 2, 'Utilizador atualizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 12:22:04'),
(44, 1, 'UTILIZADORES', 'ALTERACAO', 'UTILIZADORES', 'utilizadores', 2, 'Utilizador atualizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 12:22:08'),
(45, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 12:31:31'),
(46, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 12:31:45'),
(47, 1, 'ESPECIALISTAS', 'ALTERACAO', 'ESPECIALISTAS', 'especialistas', 3, 'Ficha do especialista atualizada.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 12:32:07'),
(48, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 14:07:43'),
(49, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 14:07:51'),
(50, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 14:07:54'),
(51, 2, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 2, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 14:08:08'),
(52, 2, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 2, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 14:08:41'),
(53, 2, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 2, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 14:08:53'),
(54, 2, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 2, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 14:09:37'),
(55, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 14:09:39'),
(56, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 14:48:46'),
(57, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-10 14:48:49'),
(58, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 06:53:19'),
(59, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 06:59:10'),
(60, 4, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 4, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 06:59:21'),
(61, 4, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 4, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 07:04:03'),
(62, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 07:04:06'),
(63, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:23:25'),
(64, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:23:58'),
(65, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:26:32'),
(66, 4, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 4, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:26:50'),
(67, 4, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 4, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:41:17'),
(68, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:41:19'),
(69, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:41:23'),
(70, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:41:32'),
(71, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:44:02'),
(72, 4, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 4, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:44:14'),
(73, 4, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 4, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:45:17'),
(74, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-11 09:45:19'),
(75, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 00:01:45'),
(76, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 00:01:48'),
(77, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 00:20:50'),
(78, 2, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 2, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 00:21:04'),
(79, 2, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 2, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 05:55:39'),
(80, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 05:55:41'),
(81, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 06:14:09'),
(82, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 06:16:58'),
(83, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 07:47:03'),
(84, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 07:52:40'),
(85, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 08:02:33'),
(86, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 08:25:01'),
(87, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 08:25:07'),
(88, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 08:25:30'),
(89, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 08:27:35'),
(90, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 08:27:46'),
(91, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 08:53:45'),
(92, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 08:54:21'),
(93, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:09:06'),
(94, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:09:18'),
(95, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:09:28'),
(96, 1, 'ESPECIALISTAS', 'HISTORICO_CRIADO', 'ESPECIALISTAS', 'especialistas', 4, 'Registo de histórico: OUTRO', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:14:26'),
(97, 1, 'ESPECIALISTAS', 'CRIACAO', 'ESPECIALISTAS', 'especialistas', 4, 'Novo especialista criado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:14:26'),
(98, 1, 'CONFIGURACAO', 'CRIACAO', 'CONFIGURACOES', 'patentes', 2, 'Alteração de configuração: patentes #2', NULL, '{\"id_patente\":2,\"id_ramo\":2,\"nome\":\"CAPITÃO\",\"sigla\":\"CAP\",\"ordem\":8,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 11:16:00\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:16:00'),
(99, 1, 'CONFIGURACAO', 'CRIACAO', 'CONFIGURACOES', 'patentes', 3, 'Alteração de configuração: patentes #3', NULL, '{\"id_patente\":3,\"id_ramo\":4,\"nome\":\"TENENTE DE FRAGATA\",\"sigla\":\"TTF\",\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 11:16:49\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:16:49'),
(100, 1, 'CONFIGURACAO', 'ALTERACAO', 'CONFIGURACOES', 'patentes', 1, 'Alteração de configuração: patentes #1', '{\"id_patente\":1,\"id_ramo\":3,\"nome\":\"TENENTE\",\"sigla\":null,\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-10 11:33:53\"}', '{\"id_patente\":1,\"id_ramo\":3,\"nome\":\"TENENTE\",\"sigla\":\"TTE\",\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-10 11:33:53\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:17:03'),
(101, 1, 'ESPECIALISTAS', 'HISTORICO_CRIADO', 'ESPECIALISTAS', 'especialistas', 4, 'Registo de histórico: ALTERACAO_DADOS', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:18:08'),
(102, 1, 'ESPECIALISTAS', 'HISTORICO_CRIADO', 'ESPECIALISTAS', 'especialistas', 4, 'Registo de histórico: PROMOCAO', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:18:08'),
(103, 1, 'ESPECIALISTAS', 'ALTERACAO', 'ESPECIALISTAS', 'especialistas', 4, 'Ficha do especialista atualizada.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:18:08'),
(104, 1, 'ESPECIALISTAS', 'HISTORICO_CRIADO', 'ESPECIALISTAS', 'especialistas', 5, 'Registo de histórico: OUTRO', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:34:43'),
(105, 1, 'ESPECIALISTAS', 'CRIACAO', 'ESPECIALISTAS', 'especialistas', 5, 'Novo especialista criado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:34:43'),
(106, 1, 'ESPECIALISTAS', 'HISTORICO_CRIADO', 'ESPECIALISTAS', 'especialistas', 6, 'Registo de histórico: OUTRO', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:42:27'),
(107, 1, 'ESPECIALISTAS', 'CRIACAO', 'ESPECIALISTAS', 'especialistas', 6, 'Novo especialista criado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:42:27'),
(108, 1, 'CONFIGURACAO', 'CRIACAO', 'CONFIGURACOES', 'patentes', 4, 'Alteração de configuração: patentes #4', NULL, '{\"id_patente\":4,\"id_ramo\":1,\"nome\":\"MAJOR\",\"sigla\":\"MJ\",\"ordem\":7,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 11:43:42\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:43:42'),
(109, 1, 'ESPECIALISTAS', 'HISTORICO_CRIADO', 'ESPECIALISTAS', 'especialistas', 6, 'Registo de histórico: PROMOCAO', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:45:43'),
(110, 1, 'ESPECIALISTAS', 'ALTERACAO', 'ESPECIALISTAS', 'especialistas', 6, 'Ficha do especialista atualizada.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:45:43'),
(111, 1, 'ESPECIALISTAS', 'ALTERACAO', 'ESPECIALISTAS', 'especialistas', 6, 'Ficha do especialista atualizada.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:50:22'),
(112, 1, 'ESPECIALISTAS', 'ALTERACAO', 'ESPECIALISTAS', 'especialistas', 4, 'Ficha do especialista atualizada.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:51:03'),
(113, 1, 'ESPECIALISTAS', 'ALTERACAO', 'ESPECIALISTAS', 'especialistas', 5, 'Ficha do especialista atualizada.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:51:55'),
(114, 1, 'ESPECIALISTAS', 'ALTERACAO', 'ESPECIALISTAS', 'especialistas', 4, 'Ficha do especialista atualizada.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:54:54'),
(115, 1, 'ESPECIALISTAS', 'ALTERACAO', 'ESPECIALISTAS', 'especialistas', 5, 'Ficha do especialista atualizada.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:55:30'),
(116, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 11:59:50'),
(117, 4, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 4, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:00:00'),
(118, 4, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 4, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:00:43'),
(119, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:00:51'),
(120, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:01:31'),
(121, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:01:47'),
(122, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:02:33'),
(123, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:02:41'),
(124, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:10:04'),
(125, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:10:06'),
(126, 1, 'CONFIGURACAO', 'CRIACAO', 'CONFIGURACOES', 'tipos_formacao', 1, 'Alteração de configuração: tipos_formacao #1', NULL, '{\"id_tipo_formacao\":1,\"nome\":\"CPC M\",\"categoria\":\"MILITAR\",\"descricao\":\"\",\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 12:19:45\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:19:45'),
(127, 1, 'CONFIGURACAO', 'CRIACAO', 'CONFIGURACOES', 'tipos_formacao', 2, 'Alteração de configuração: tipos_formacao #2', NULL, '{\"id_tipo_formacao\":2,\"nome\":\"CPO\",\"categoria\":\"MILITAR\",\"descricao\":\"\",\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 12:21:54\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:21:54'),
(128, 1, 'CONFIGURACAO', 'ELIMINACAO', 'CONFIGURACOES', 'tipos_formacao', 1, 'Alteração de configuração: tipos_formacao #1', '{\"id_tipo_formacao\":1,\"nome\":\"CPC M\",\"categoria\":\"MILITAR\",\"descricao\":\"\",\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 12:19:45\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:24:12'),
(129, 1, 'CONFIGURACAO', 'ELIMINACAO', 'CONFIGURACOES', 'tipos_formacao', 1, 'Registo eliminado pela interface de configurações.', '{\"id_tipo_formacao\":1,\"nome\":\"CPC M\",\"categoria\":\"MILITAR\",\"descricao\":\"\",\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 12:19:45\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:24:12'),
(130, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:54:26'),
(131, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 12:54:43'),
(132, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:19:00'),
(133, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:19:22'),
(134, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:24:29'),
(135, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:26:06'),
(136, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:38:02'),
(137, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:40:16'),
(138, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:40:23'),
(139, 2, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 2, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:44:01'),
(140, 2, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 2, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:44:31'),
(141, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:44:35'),
(142, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:56:58'),
(143, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 13:57:02'),
(144, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 14:00:22'),
(145, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 14:00:40'),
(146, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 14:12:39'),
(147, 4, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 4, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 14:13:30'),
(148, 4, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 4, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 14:13:34'),
(149, 4, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 4, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 14:14:40'),
(150, 4, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 4, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 14:19:16'),
(151, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 14:19:18'),
(152, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 15:35:53'),
(153, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 15:36:08'),
(154, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 15:36:13'),
(155, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-12 15:38:13'),
(156, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-13 15:13:32'),
(157, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-13 15:15:32'),
(158, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-14 00:34:47'),
(159, 1, 'ESPECIALISTAS', 'HISTORICO_CRIADO', 'ESPECIALISTAS', 'especialistas', 6, 'Registo de histórico: PROMOCAO', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-14 12:45:21'),
(160, 1, 'ESPECIALISTAS', 'HISTORICO_CRIADO', 'ESPECIALISTAS', 'especialistas', 6, 'Registo de histórico: MOVIMENTACAO_UNIDADE', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-14 12:45:21'),
(161, 1, 'ESPECIALISTAS', 'HISTORICO_CRIADO', 'ESPECIALISTAS', 'especialistas', 6, 'Registo de histórico: ALTERACAO_RAMO', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-14 12:45:21'),
(162, 1, 'ESPECIALISTAS', 'ALTERACAO', 'ESPECIALISTAS', 'especialistas', 6, 'Ficha do especialista atualizada.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-14 12:45:21'),
(163, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-14 12:52:44'),
(164, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-14 12:53:24'),
(165, 1, 'UTILIZADORES', 'ALTERACAO', 'UTILIZADORES', 'utilizadores', 3, 'Utilizador atualizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-14 18:15:51'),
(166, 1, 'UTILIZADORES', 'ALTERACAO', 'UTILIZADORES', 'utilizadores', 3, 'Utilizador atualizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-14 18:15:57'),
(167, 1, 'AUTENTICACAO', 'LOGOUT', 'LOGIN', 'utilizadores', 1, 'Logout realizado.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-14 18:16:27'),
(168, 1, 'AUTENTICACAO', 'LOGIN_REALIZADO', 'LOGIN', 'utilizadores', 1, 'Login realizado com sucesso.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'localhost', 'SUCESSO', '2026-10-14 18:16:51');

-- --------------------------------------------------------

--
-- Estrutura para tabela `bancos`
--

CREATE TABLE `bancos` (
  `id_banco` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `sigla` varchar(30) DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `bancos`
--

INSERT INTO `bancos` (`id_banco`, `nome`, `sigla`, `estado`, `data_criacao`) VALUES
(1, 'Banco de Fomento Angola', 'BFA', 'ATIVO', '2026-10-08 20:30:42'),
(2, 'Banco BIC, S.A.', 'BIC', 'ATIVO', '2026-10-08 20:30:42'),
(3, 'Banco Angolano de Investimentos', 'BAI', 'ATIVO', '2026-10-08 20:30:42'),
(4, 'Banco de Poupança e Crédito', 'BPC', 'ATIVO', '2026-10-08 20:30:42'),
(5, 'Banco Sol', 'SOL', 'ATIVO', '2026-10-08 20:30:42'),
(6, 'Banco de Comércio e Indústria', 'BCI', 'ATIVO', '2026-10-08 20:30:42'),
(7, 'Standard Bank Angola', 'SBA', 'ATIVO', '2026-10-08 20:30:42'),
(8, 'Banco Millennium Atlântico', 'ATLANTICO', 'ATIVO', '2026-10-08 20:30:42'),
(9, 'Banco Caixa Geral Angola', 'BCGA', 'ATIVO', '2026-10-08 20:30:42'),
(10, 'Banco Keve', 'KEVE', 'ATIVO', '2026-10-08 20:30:42'),
(11, 'Banco Prestígio', 'PRESTIGIO', 'ATIVO', '2026-10-08 20:30:42');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cargos`
--

CREATE TABLE `cargos` (
  `id_cargo` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `condecoracoes`
--

CREATE TABLE `condecoracoes` (
  `id_condecoracao` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `id_tipo_condecoracao` int(11) DEFAULT NULL,
  `entidade` varchar(200) DEFAULT NULL,
  `data_atribuicao` date DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `numero_documento` varchar(100) DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracoes_alertas`
--

CREATE TABLE `configuracoes_alertas` (
  `id_configuracao_alerta` int(11) NOT NULL,
  `id_tipo_alerta` int(11) NOT NULL,
  `id_ramo` int(11) DEFAULT NULL,
  `dias_antecedencia` int(11) DEFAULT 0,
  `ativo` tinyint(1) DEFAULT 1,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos_habilitacao`
--

CREATE TABLE `cursos_habilitacao` (
  `id_curso` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `estado` varchar(12) NOT NULL DEFAULT 'ATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `cursos_habilitacao`
--

INSERT INTO `cursos_habilitacao` (`id_curso`, `nome`, `estado`) VALUES
(1, 'Engenharia Informática', 'ATIVO'),
(2, 'Engenharia de Telecomunicações', 'ATIVO'),
(3, 'Gestão', 'ATIVO'),
(4, 'Administração Pública', 'ATIVO'),
(5, 'Direito', 'ATIVO'),
(6, 'Medicina', 'ATIVO'),
(7, 'Enfermagem', 'ATIVO'),
(8, 'Contabilidade', 'ATIVO'),
(9, 'Informática', 'ATIVO'),
(10, 'Logística', 'ATIVO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `dados_bancarios`
--

CREATE TABLE `dados_bancarios` (
  `id_dados_bancarios` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `id_banco` int(11) DEFAULT NULL,
  `numero_conta` varchar(80) DEFAULT NULL,
  `iban` varchar(80) DEFAULT NULL,
  `numero_agente` varchar(80) DEFAULT NULL,
  `data_atualizacao` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `dados_bancarios`
--

INSERT INTO `dados_bancarios` (`id_dados_bancarios`, `id_especialista`, `id_banco`, `numero_conta`, `iban`, `numero_agente`, `data_atualizacao`) VALUES
(1, 3, 4, NULL, NULL, NULL, '2026-10-10 08:26:44'),
(5, 4, NULL, NULL, NULL, NULL, '2026-10-12 11:14:26'),
(7, 5, NULL, NULL, NULL, NULL, '2026-10-12 11:34:43'),
(8, 6, NULL, NULL, NULL, NULL, '2026-10-12 11:42:27');

-- --------------------------------------------------------

--
-- Estrutura para tabela `dados_pessoais`
--

CREATE TABLE `dados_pessoais` (
  `id_dados_pessoais` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `nome_completo` varchar(200) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `id_sexo` int(11) DEFAULT NULL,
  `id_pais_nascimento` int(11) DEFAULT NULL,
  `id_provincia_nascimento` int(11) DEFAULT NULL,
  `id_municipio_nascimento` int(11) DEFAULT NULL,
  `id_pais_nacionalidade` int(11) DEFAULT NULL,
  `id_estado_civil` int(11) DEFAULT NULL,
  `numero_bi` varchar(50) DEFAULT NULL,
  `data_emissao_bi` date DEFAULT NULL,
  `data_validade_bi` date DEFAULT NULL,
  `local_emissao_bi` varchar(150) DEFAULT NULL,
  `id_grupo_sanguineo` int(11) DEFAULT NULL,
  `peso_kg` decimal(6,2) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `telefone_alternativo` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `morada` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `dados_pessoais`
--

INSERT INTO `dados_pessoais` (`id_dados_pessoais`, `id_especialista`, `nome_completo`, `data_nascimento`, `id_sexo`, `id_pais_nascimento`, `id_provincia_nascimento`, `id_municipio_nascimento`, `id_pais_nacionalidade`, `id_estado_civil`, `numero_bi`, `data_emissao_bi`, `data_validade_bi`, `local_emissao_bi`, `id_grupo_sanguineo`, `peso_kg`, `telefone`, `telefone_alternativo`, `email`, `morada`) VALUES
(3, 3, 'JUSTINO JOEL', NULL, 1, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, '', NULL, NULL, '', '', '', NULL),
(4, 4, 'ROBERTO MANUEL', '1992-10-24', 1, 6, 6, 115, 6, 2, '005467986LA057', '2022-10-12', '2030-11-13', 'Cuanza Norte', 8, 74.00, '956852245', '956742890', 'robertomanuel@gmail.com', NULL),
(5, 5, 'JUDITH JOSÉ', '2000-10-20', 2, 6, 11, 241, 6, 1, '008754561HL053', '2025-12-31', '2038-12-30', 'Huíla', 3, 65.00, '976452415', '976862550', 'jjose4@gmail.com', NULL),
(6, 6, 'ANTÓNIO JUNIOR', '1991-04-12', 1, 6, 12, 271, 6, 3, '006458519LA048', '2024-06-05', '2032-05-05', '', 6, 78.00, '925554622', '944567892', '', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `dados_residencia`
--

CREATE TABLE `dados_residencia` (
  `id_residencia` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `id_provincia` int(11) DEFAULT NULL,
  `id_municipio` int(11) DEFAULT NULL,
  `bairro` varchar(150) DEFAULT NULL,
  `rua` varchar(200) DEFAULT NULL,
  `casa` varchar(80) DEFAULT NULL,
  `ponto_referencia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `dados_residencia`
--

INSERT INTO `dados_residencia` (`id_residencia`, `id_especialista`, `id_provincia`, `id_municipio`, `bairro`, `rua`, `casa`, `ponto_referencia`) VALUES
(2, 3, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 4, 6, 115, 'Miradouro', '4', '43', 'Hotel Miradouro'),
(8, 5, 13, 281, NULL, NULL, NULL, NULL),
(9, 6, 10, 216, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `dados_servico`
--

CREATE TABLE `dados_servico` (
  `id_dados_servico` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `numero_processo` varchar(50) DEFAULT NULL,
  `data_incorporacao` date DEFAULT NULL,
  `local_incorporacao` varchar(200) DEFAULT NULL,
  `data_promocao` date DEFAULT NULL,
  `id_funcao` int(11) DEFAULT NULL,
  `id_unidade` int(11) DEFAULT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  `id_situacao_servico` int(11) DEFAULT NULL,
  `id_regiao` int(11) DEFAULT NULL,
  `local_servico` enum('EMG','EXE','FAN','MGA') DEFAULT NULL,
  `brigada` varchar(150) DEFAULT NULL,
  `batalhao` varchar(150) DEFAULT NULL,
  `pelotao` varchar(150) DEFAULT NULL,
  `seccao` varchar(150) DEFAULT NULL,
  `data_inicio_funcao` date DEFAULT NULL,
  `data_fim_funcao` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `dados_servico`
--

INSERT INTO `dados_servico` (`id_dados_servico`, `id_especialista`, `nip`, `numero_processo`, `data_incorporacao`, `local_incorporacao`, `data_promocao`, `id_funcao`, `id_unidade`, `id_departamento`, `id_situacao_servico`, `id_regiao`, `local_servico`, `brigada`, `batalhao`, `pelotao`, `seccao`, `data_inicio_funcao`, `data_fim_funcao`, `observacoes`) VALUES
(3, 3, '10101010', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(4, 4, '10987654', 'R67', '2014-10-13', NULL, '2021-11-10', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(5, 5, '101002411', 'F14', '2019-06-12', NULL, '2022-12-24', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, ''),
(6, 6, '101054768', 'TJ67', '1994-04-12', NULL, '2015-01-15', NULL, NULL, NULL, 1, NULL, 'EMG', NULL, NULL, NULL, NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `departamentos`
--

CREATE TABLE `departamentos` (
  `id_departamento` int(11) NOT NULL,
  `id_unidade` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `sigla` varchar(50) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `documentos`
--

CREATE TABLE `documentos` (
  `id_documento` int(11) NOT NULL,
  `id_especialista` int(11) DEFAULT NULL,
  `id_equipamento` int(11) DEFAULT NULL,
  `id_tipo_documento` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `nome_ficheiro` varchar(255) NOT NULL,
  `caminho_ficheiro` varchar(500) NOT NULL,
  `data_documento` date DEFAULT NULL,
  `data_validade` date DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `data_registo` datetime DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `equipamentos`
--

CREATE TABLE `equipamentos` (
  `id_equipamento` int(11) NOT NULL,
  `id_ramo` int(11) NOT NULL,
  `id_regiao` int(11) DEFAULT NULL,
  `id_unidade` int(11) DEFAULT NULL,
  `id_especialista_responsavel` int(11) DEFAULT NULL,
  `id_tipo_equipamento` int(11) DEFAULT NULL,
  `designacao` varchar(200) NOT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `numero_serie` varchar(100) DEFAULT NULL,
  `numero_patrimonio` varchar(100) DEFAULT NULL,
  `data_aquisicao` date DEFAULT NULL,
  `quantidade_total` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `quantidade_distribuida` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `id_estado_equipamento` int(11) DEFAULT NULL,
  `localizacao` varchar(200) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `especialistas`
--

CREATE TABLE `especialistas` (
  `id_especialista` int(11) NOT NULL,
  `id_ramo` int(11) NOT NULL,
  `id_quadro` int(11) NOT NULL,
  `id_patente` int(11) DEFAULT NULL,
  `tipo_especialista` enum('MILITAR','CIVIL') NOT NULL DEFAULT 'MILITAR',
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_registo` datetime NOT NULL DEFAULT current_timestamp(),
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `especialistas`
--

INSERT INTO `especialistas` (`id_especialista`, `id_ramo`, `id_quadro`, `id_patente`, `tipo_especialista`, `estado`, `data_registo`, `observacoes`) VALUES
(3, 3, 1, 1, 'MILITAR', 'ATIVO', '2026-10-10 08:26:44', ''),
(4, 2, 1, 2, 'MILITAR', 'ATIVO', '2026-10-12 11:14:26', ''),
(5, 4, 1, 3, 'MILITAR', 'ATIVO', '2026-10-12 11:34:43', ''),
(6, 2, 1, NULL, 'MILITAR', 'ATIVO', '2026-10-12 11:42:27', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estados_civis`
--

CREATE TABLE `estados_civis` (
  `id_estado_civil` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `estados_civis`
--

INSERT INTO `estados_civis` (`id_estado_civil`, `nome`, `estado`, `data_criacao`) VALUES
(1, 'Solteiro', 'ATIVO', '2026-10-07 14:47:58'),
(2, 'Casado', 'ATIVO', '2026-10-07 14:47:58'),
(3, 'Divorciado', 'ATIVO', '2026-10-07 14:47:58'),
(4, 'Viúvo', 'ATIVO', '2026-10-07 14:47:58'),
(5, 'União de facto', 'ATIVO', '2026-10-07 14:47:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estados_equipamento`
--

CREATE TABLE `estados_equipamento` (
  `id_estado_equipamento` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `familiares`
--

CREATE TABLE `familiares` (
  `id_familiar` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `id_grau_parentesco` int(11) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `profissao` varchar(150) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `morada` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `feed_anexos`
--

CREATE TABLE `feed_anexos` (
  `id_anexo` bigint(20) UNSIGNED NOT NULL,
  `id_publicacao` int(10) UNSIGNED NOT NULL,
  `nome_original` varchar(255) NOT NULL,
  `nome_guardado` varchar(80) NOT NULL,
  `mime_type` varchar(120) NOT NULL,
  `tamanho_bytes` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `feed_avaliacoes`
--

CREATE TABLE `feed_avaliacoes` (
  `id_publicacao` int(11) NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `estrelas` tinyint(3) UNSIGNED NOT NULL,
  `atualizado_em` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `feed_candidaturas`
--

CREATE TABLE `feed_candidaturas` (
  `id_candidatura` bigint(20) UNSIGNED NOT NULL,
  `id_publicacao` int(10) UNSIGNED NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `estado` varchar(30) NOT NULL DEFAULT 'SUBMETIDA',
  `submetido_por` int(11) DEFAULT NULL,
  `submetido_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `feed_comentarios`
--

CREATE TABLE `feed_comentarios` (
  `id_comentario` bigint(20) UNSIGNED NOT NULL,
  `id_publicacao` int(10) UNSIGNED NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `feed_leituras`
--

CREATE TABLE `feed_leituras` (
  `id_publicacao` int(10) UNSIGNED NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `lido_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `feed_leituras`
--

INSERT INTO `feed_leituras` (`id_publicacao`, `id_utilizador`, `lido_em`) VALUES
(1, 1, '2026-10-10 13:53:16'),
(2, 1, '2026-10-11 09:45:33');

-- --------------------------------------------------------

--
-- Estrutura para tabela `feed_oportunidade_config`
--

CREATE TABLE `feed_oportunidade_config` (
  `id_publicacao` int(10) UNSIGNED NOT NULL,
  `id_ramo_criterio` int(11) DEFAULT NULL,
  `id_quadro_criterio` int(11) DEFAULT NULL,
  `id_patente_criterio` int(11) DEFAULT NULL,
  `id_nivel_habilitacao` int(11) DEFAULT NULL,
  `criterios_texto` text DEFAULT NULL,
  `atualizado_por` int(11) DEFAULT NULL,
  `atualizado_em` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idade_minima` int(11) DEFAULT NULL,
  `idade_maxima` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `feed_permissoes_perfil`
--

CREATE TABLE `feed_permissoes_perfil` (
  `id_perfil` int(11) NOT NULL,
  `pode_ler` tinyint(1) NOT NULL DEFAULT 1,
  `pode_publicar` tinyint(1) NOT NULL DEFAULT 0,
  `pode_submeter` tinyint(1) NOT NULL DEFAULT 1,
  `pode_gerir` tinyint(1) NOT NULL DEFAULT 0,
  `atualizado_em` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `feed_permissoes_perfil`
--

INSERT INTO `feed_permissoes_perfil` (`id_perfil`, `pode_ler`, `pode_publicar`, `pode_submeter`, `pode_gerir`, `atualizado_em`) VALUES
(1, 1, 1, 1, 1, '2026-10-10 14:13:51'),
(2, 1, 1, 1, 1, '2026-10-10 14:13:51'),
(3, 1, 0, 1, 0, '2026-10-10 14:13:51'),
(4, 1, 1, 1, 1, '2026-10-10 14:13:51'),
(5, 1, 1, 1, 1, '2026-10-10 14:13:51'),
(6, 1, 0, 1, 0, '2026-10-10 14:13:51'),
(7, 1, 0, 1, 0, '2026-10-10 14:13:51'),
(8, 1, 0, 1, 0, '2026-10-10 14:13:51'),
(9, 1, 0, 1, 0, '2026-10-10 14:13:51'),
(10, 1, 1, 1, 1, '2026-10-10 14:13:51');

-- --------------------------------------------------------

--
-- Estrutura para tabela `feed_publicacoes`
--

CREATE TABLE `feed_publicacoes` (
  `id_publicacao` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(180) NOT NULL,
  `conteudo` text NOT NULL,
  `area` enum('RH','RM','TODOS') NOT NULL DEFAULT 'TODOS',
  `tipo` varchar(30) NOT NULL DEFAULT 'COMUNICADO',
  `data_limite` date DEFAULT NULL,
  `id_autor` int(11) DEFAULT NULL,
  `id_ramo` int(11) DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `feed_publicacoes`
--

INSERT INTO `feed_publicacoes` (`id_publicacao`, `titulo`, `conteudo`, `area`, `tipo`, `data_limite`, `id_autor`, `id_ramo`, `criado_em`, `estado`) VALUES
(1, 'RÁDIO HF', 'O rádio HF (High Frequency ou Alta Frequência) opera na faixa de frequência de 3 MHz a 30 MHz (também conhecida como faixa de ondas curtas).', 'TODOS', 'COMUNICADO', NULL, 1, NULL, '2026-10-10 14:52:15', 'ATIVO'),
(2, 'RÁDIO HF', 'O rádio HF (High Frequency ou Alta Frequência) opera na faixa de frequência de 3 MHz a 30 MHz (também conhecida como faixa de ondas curtas).', 'TODOS', 'COMUNICADO', NULL, 1, NULL, '2026-10-10 14:52:41', 'INATIVO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `feed_reacoes`
--

CREATE TABLE `feed_reacoes` (
  `id_publicacao` int(10) UNSIGNED NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ferias`
--

CREATE TABLE `ferias` (
  `id_ferias` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `ano` year(4) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `numero_dias` int(11) NOT NULL,
  `estado` enum('PROGRAMADA','EM_CURSO','CONCLUIDA','CANCELADA') DEFAULT 'PROGRAMADA',
  `observacoes` text DEFAULT NULL,
  `data_registo` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `formacoes`
--

CREATE TABLE `formacoes` (
  `id_formacao` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `categoria` enum('MILITAR','GERAL') NOT NULL,
  `id_tipo_formacao` int(11) DEFAULT NULL,
  `designacao` varchar(200) NOT NULL,
  `id_instituicao` int(11) DEFAULT NULL,
  `id_pais` int(11) DEFAULT NULL,
  `id_provincia` int(11) DEFAULT NULL,
  `local` varchar(150) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `duracao` varchar(100) DEFAULT NULL,
  `certificado` varchar(100) DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fotografias`
--

CREATE TABLE `fotografias` (
  `id_fotografia` int(11) NOT NULL,
  `id_especialista` int(11) DEFAULT NULL,
  `id_equipamento` int(11) DEFAULT NULL,
  `nome_ficheiro` varchar(255) NOT NULL,
  `caminho_ficheiro` varchar(500) NOT NULL,
  `data_registo` datetime DEFAULT current_timestamp(),
  `principal` tinyint(1) DEFAULT 0,
  `descricao` text DEFAULT NULL
) ;

--
-- Despejando dados para a tabela `fotografias`
--

INSERT INTO `fotografias` (`id_fotografia`, `id_especialista`, `id_equipamento`, `nome_ficheiro`, `caminho_ficheiro`, `data_registo`, `principal`, `descricao`) VALUES
(1, 3, NULL, 'jtLinkedin.png', 'uploads/especialistas/fotografias/2989c1053e1bbfe8119c60ba.png', '2026-10-10 11:37:27', 0, NULL),
(2, 3, NULL, 'jtLinkedin.png', 'uploads/especialistas/fotografias/4cdf890a4483213d65c641f7.png', '2026-10-10 12:20:32', 0, NULL),
(3, 3, NULL, 'jtLinkedin.png', 'uploads/especialistas/fotografias/06afc209106a95253d1829ba.png', '2026-10-10 14:21:49', 0, NULL),
(4, 5, NULL, 'images (13).jpg', 'uploads/especialistas/fotografias/d1fe080c505ad60660a9db5c.jpg', '2026-10-12 11:36:04', 0, NULL),
(5, 3, NULL, 'jtLinkedin.png', 'uploads/especialistas/fotografias/cabfe7ebbab6a6c6b324d7aa.png', '2026-10-12 11:36:32', 0, NULL),
(6, 4, NULL, 'jtLinkedin.png', 'uploads/especialistas/fotografias/382daf61317d476f6832568e.png', '2026-10-12 11:36:52', 0, NULL),
(7, 4, NULL, 'FOTO6.png', 'uploads/especialistas/fotografias/90a9eeac25adeecc9e1b3e52.png', '2026-10-12 11:37:39', 0, NULL),
(8, 6, NULL, 'FOTO8.png', 'uploads/especialistas/fotografias/93f005f9749e07f47cdd7d8d.png', '2026-10-12 11:45:51', 0, NULL),
(9, 3, NULL, 'jtLinkedin.png', 'uploads/especialistas/fotografias/70356db8d0afb1894f15b4d3.png', '2026-10-12 13:57:42', 0, NULL),
(10, 3, NULL, 'jtLinkedin.png', 'uploads/especialistas/fotografias/f610ae347af43d84f0338323.png', '2026-10-12 14:00:04', 0, NULL),
(11, 3, NULL, 'jtLinkedin.png', 'uploads/especialistas/fotografias/9aa83451441ef97af6b44720.png', '2026-10-12 14:01:17', 0, NULL),
(12, 3, NULL, 'jtLinkedin.png', 'uploads/especialistas/fotografias/4096cf7d0de4427959870fe3.png', '2026-10-12 14:03:28', 0, NULL),
(13, 3, NULL, 'jtLinkedin.png', 'uploads/especialistas/fotografias/08b410c88ef9d7cf72782ba0.png', '2026-10-12 14:07:35', 1, NULL),
(14, 5, NULL, 'images (13).jpg', 'uploads/especialistas/fotografias/35fd3de1b86965ccb5acfb79.jpg', '2026-10-12 14:08:20', 1, NULL),
(15, 4, NULL, 'FOTO7.png', 'uploads/especialistas/fotografias/a3c3f26794421e929eb9e204.png', '2026-10-12 14:08:45', 1, NULL),
(16, 6, NULL, 'FOTO8.png', 'uploads/especialistas/fotografias/e9dd49b8d72185350458a0c4.png', '2026-10-12 14:08:58', 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcoes`
--

CREATE TABLE `funcoes` (
  `id_funcao` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `graus_parentesco`
--

CREATE TABLE `graus_parentesco` (
  `id_grau_parentesco` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `grupos_sanguineos`
--

CREATE TABLE `grupos_sanguineos` (
  `id_grupo_sanguineo` int(11) NOT NULL,
  `nome` varchar(10) NOT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `grupos_sanguineos`
--

INSERT INTO `grupos_sanguineos` (`id_grupo_sanguineo`, `nome`, `estado`) VALUES
(1, 'A+', 'ATIVO'),
(2, 'A-', 'ATIVO'),
(3, 'B+', 'ATIVO'),
(4, 'B-', 'ATIVO'),
(5, 'AB+', 'ATIVO'),
(6, 'AB-', 'ATIVO'),
(7, 'O+', 'ATIVO'),
(8, 'O-', 'ATIVO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `habilitacoes_literarias`
--

CREATE TABLE `habilitacoes_literarias` (
  `id_habilitacao` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `categoria` enum('MILITAR','GERAL') NOT NULL,
  `id_nivel_habilitacao` int(11) DEFAULT NULL,
  `curso` varchar(200) DEFAULT NULL,
  `id_instituicao` int(11) DEFAULT NULL,
  `id_pais` int(11) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_conclusao` date DEFAULT NULL,
  `numero_certificado` varchar(100) DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_configuracoes`
--

CREATE TABLE `historico_configuracoes` (
  `id_historico` bigint(20) NOT NULL,
  `tabela` varchar(100) NOT NULL,
  `id_registo` int(11) NOT NULL,
  `acao` varchar(50) NOT NULL,
  `dados_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_anteriores`)),
  `dados_novos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dados_novos`)),
  `id_utilizador` int(11) DEFAULT NULL,
  `data_registo` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `historico_configuracoes`
--

INSERT INTO `historico_configuracoes` (`id_historico`, `tabela`, `id_registo`, `acao`, `dados_anteriores`, `dados_novos`, `id_utilizador`, `data_registo`) VALUES
(1, 'situacoes_servico', 5, 'ALTERACAO', '{\"id_situacao_servico\":5,\"nome\":\"DOENTE\",\"grupo\":\"ATIVO\",\"descricao\":\"Estado funcional para consulta e acompanhamento.\",\"estado\":\"ATIVO\"}', '{\"id_situacao_servico\":5,\"nome\":\"DOENTE\",\"grupo\":\"INATIVO\",\"descricao\":\"Estado funcional para consulta e acompanhamento.\",\"estado\":\"ATIVO\"}', 1, '2026-10-08 20:35:11'),
(2, 'patentes', 1, 'CRIACAO', NULL, '{\"id_patente\":1,\"id_ramo\":3,\"nome\":\"TENENTE\",\"sigla\":\"TTE\",\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-10 11:33:53\"}', 1, '2026-10-10 11:33:53'),
(3, 'patentes', 1, 'ALTERACAO', '{\"id_patente\":1,\"id_ramo\":3,\"nome\":\"TENENTE\",\"sigla\":\"TTE\",\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-10 11:33:53\"}', '{\"id_patente\":1,\"id_ramo\":3,\"nome\":\"TENENTE\",\"sigla\":null,\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-10 11:33:53\"}', 1, '2026-10-10 11:36:37'),
(4, 'patentes', 2, 'CRIACAO', NULL, '{\"id_patente\":2,\"id_ramo\":2,\"nome\":\"CAPITÃO\",\"sigla\":\"CAP\",\"ordem\":8,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 11:16:00\"}', 1, '2026-10-12 11:16:00'),
(5, 'patentes', 3, 'CRIACAO', NULL, '{\"id_patente\":3,\"id_ramo\":4,\"nome\":\"TENENTE DE FRAGATA\",\"sigla\":\"TTF\",\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 11:16:49\"}', 1, '2026-10-12 11:16:49'),
(6, 'patentes', 1, 'ALTERACAO', '{\"id_patente\":1,\"id_ramo\":3,\"nome\":\"TENENTE\",\"sigla\":null,\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-10 11:33:53\"}', '{\"id_patente\":1,\"id_ramo\":3,\"nome\":\"TENENTE\",\"sigla\":\"TTE\",\"ordem\":9,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-10 11:33:53\"}', 1, '2026-10-12 11:17:03'),
(7, 'patentes', 4, 'CRIACAO', NULL, '{\"id_patente\":4,\"id_ramo\":1,\"nome\":\"MAJOR\",\"sigla\":\"MJ\",\"ordem\":7,\"descricao\":null,\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 11:43:42\"}', 1, '2026-10-12 11:43:42'),
(8, 'tipos_formacao', 1, 'CRIACAO', NULL, '{\"id_tipo_formacao\":1,\"nome\":\"CPC M\",\"categoria\":\"MILITAR\",\"descricao\":\"\",\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 12:19:45\"}', 1, '2026-10-12 12:19:45'),
(9, 'tipos_formacao', 2, 'CRIACAO', NULL, '{\"id_tipo_formacao\":2,\"nome\":\"CPO\",\"categoria\":\"MILITAR\",\"descricao\":\"\",\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 12:21:54\"}', 1, '2026-10-12 12:21:54'),
(10, 'tipos_formacao', 1, 'ELIMINACAO', '{\"id_tipo_formacao\":1,\"nome\":\"CPC M\",\"categoria\":\"MILITAR\",\"descricao\":\"\",\"estado\":\"ATIVO\",\"data_criacao\":\"2026-10-12 12:19:45\"}', NULL, 1, '2026-10-12 12:24:12');

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_equipamentos`
--

CREATE TABLE `historico_equipamentos` (
  `id_historico` bigint(20) NOT NULL,
  `id_equipamento` int(11) DEFAULT NULL,
  `evento` varchar(80) NOT NULL,
  `campo` varchar(100) DEFAULT NULL,
  `valor_anterior` varchar(500) DEFAULT NULL,
  `valor_novo` varchar(500) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `id_utilizador` int(11) DEFAULT NULL,
  `data_registo` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_especialistas`
--

CREATE TABLE `historico_especialistas` (
  `id_historico` bigint(20) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `tipo_evento` varchar(80) NOT NULL,
  `campo` varchar(100) DEFAULT NULL,
  `valor_anterior` varchar(500) DEFAULT NULL,
  `valor_novo` varchar(500) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL,
  `id_documento` int(11) DEFAULT NULL,
  `motivo` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `id_utilizador` int(11) DEFAULT NULL,
  `data_registo` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `historico_especialistas`
--

INSERT INTO `historico_especialistas` (`id_historico`, `id_especialista`, `tipo_evento`, `campo`, `valor_anterior`, `valor_novo`, `data_inicio`, `data_fim`, `id_documento`, `motivo`, `observacoes`, `id_utilizador`, `data_registo`) VALUES
(1, 3, 'OUTRO', 'CRIACAO', NULL, 'JUSTINO JOEL', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-10 08:26:44'),
(2, 3, 'PROMOCAO', 'patente', '', '1', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-10 11:35:57'),
(3, 4, 'OUTRO', 'CRIACAO', NULL, 'Roberto Manuel', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-12 11:14:26'),
(4, 4, 'ALTERACAO_DADOS', 'nome_completo', 'Roberto Manuel', 'ROBERTO MANUEL', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-12 11:18:08'),
(5, 4, 'PROMOCAO', 'patente', '', '2', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-12 11:18:08'),
(6, 5, 'OUTRO', 'CRIACAO', NULL, 'JUDITH JOSÉ', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-12 11:34:43'),
(7, 6, 'OUTRO', 'CRIACAO', NULL, 'ANTÓNIO JUNIOR', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-12 11:42:27'),
(8, 6, 'PROMOCAO', 'patente', '', '4', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-12 11:45:43'),
(9, 6, 'PROMOCAO', 'patente', '4', '', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-14 12:45:21'),
(10, 6, 'MOVIMENTACAO_UNIDADE', 'local_servico', '', 'EMG', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-14 12:45:21'),
(11, 6, 'ALTERACAO_RAMO', 'ramo', '1', '2', NULL, NULL, NULL, NULL, NULL, 1, '2026-10-14 12:45:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `instituicoes`
--

CREATE TABLE `instituicoes` (
  `id_instituicao` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `sigla` varchar(50) DEFAULT NULL,
  `id_pais` int(11) DEFAULT NULL,
  `localizacao` varchar(200) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `licencas`
--

CREATE TABLE `licencas` (
  `id_licenca` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `id_tipo_licenca` int(11) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date DEFAULT NULL,
  `numero_dias` int(11) DEFAULT NULL,
  `estado` enum('PROGRAMADA','EM_CURSO','CONCLUIDA','CANCELADA') NOT NULL DEFAULT 'PROGRAMADA',
  `observacoes` text DEFAULT NULL,
  `data_registo` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `licencas`
--

INSERT INTO `licencas` (`id_licenca`, `id_especialista`, `id_tipo_licenca`, `data_inicio`, `data_fim`, `numero_dias`, `estado`, `observacoes`, `data_registo`) VALUES
(1, 3, 1, '2026-11-01', '2026-12-01', 31, 'PROGRAMADA', NULL, '2026-10-12 00:13:21');

-- --------------------------------------------------------

--
-- Estrutura para tabela `modelos_relatorio`
--

CREATE TABLE `modelos_relatorio` (
  `id_modelo_relatorio` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `entidade` varchar(50) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `orientacao` varchar(20) DEFAULT 'PAISAGEM',
  `campos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`campos`)),
  `filtros` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`filtros`)),
  `estado` enum('ATIVO','INATIVO') DEFAULT 'ATIVO',
  `data_criacao` datetime DEFAULT current_timestamp(),
  `data_atualizacao` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `modelos_solicitacao`
--

CREATE TABLE `modelos_solicitacao` (
  `id_modelo` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `assunto` varchar(255) DEFAULT NULL,
  `conteudo` longtext DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') DEFAULT 'ATIVO',
  `data_criacao` datetime DEFAULT current_timestamp(),
  `data_atualizacao` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `modelos_solicitacao`
--

INSERT INTO `modelos_solicitacao` (`id_modelo`, `nome`, `codigo`, `assunto`, `conteudo`, `categoria`, `estado`, `data_criacao`, `data_atualizacao`) VALUES
(1, 'Declaração de Efetividade', 'DECL_EFETIVIDADE', 'Declaração de Efetividade', 'Declara-se que {{NOME}}, NIP {{NIP}}, {{PATENTE}}, pertence à unidade {{UNIDADE}} e exerce funções de {{FUNCAO}} no {{RAMO}}.', 'Declaração', 'ATIVO', '2026-10-07 14:47:58', NULL),
(2, 'Adiantamento Salarial', 'ADIANTAMENTO_SALARIAL', 'Pedido de Adiantamento Salarial', 'Eu, {{NOME}}, NIP {{NIP}}, venho solicitar adiantamento salarial.', 'Pedido', 'ATIVO', '2026-10-07 14:47:58', NULL),
(3, 'Declaração de Serviço', 'DECL_SERVICO', 'Declaração de Serviço', 'Declara-se que {{NOME}}, NIP {{NIP}}, presta serviço na unidade {{UNIDADE}} do {{RAMO}}.', 'Declaração', 'ATIVO', '2026-10-07 14:47:58', NULL),
(4, 'Declaração de Vínculo', 'DECL_VINCULO', 'Declaração de Vínculo', 'Declara-se que {{NOME}}, NIP {{NIP}}, mantém vínculo com o {{RAMO}}.', 'Declaração', 'ATIVO', '2026-10-07 14:47:58', NULL),
(5, 'Pedido de Férias', 'PEDIDO_FERIAS', 'Pedido de Férias', 'Eu, {{NOME}}, NIP {{NIP}}, venho solicitar férias.', 'Pedido', 'ATIVO', '2026-10-07 14:47:58', NULL),
(6, 'Pedido de Dispensa', 'PEDIDO_DISPENSA', 'Pedido de Dispensa', 'Eu, {{NOME}}, NIP {{NIP}}, venho solicitar dispensa.', 'Pedido', 'ATIVO', '2026-10-07 14:47:58', NULL),
(7, 'Pedido de Transferência', 'PEDIDO_TRANSFERENCIA', 'Pedido de Transferência', 'Eu, {{NOME}}, NIP {{NIP}}, venho solicitar transferência.', 'Pedido', 'ATIVO', '2026-10-07 14:47:58', NULL),
(8, 'Declaração de Tempo de Serviço', 'DECL_TEMPO_SERVICO', 'Declaração de Tempo de Serviço', 'Declara-se o tempo de serviço de {{NOME}}, NIP {{NIP}}.', 'Declaração', 'ATIVO', '2026-10-07 14:47:58', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `municipios`
--

CREATE TABLE `municipios` (
  `id_municipio` int(11) NOT NULL,
  `id_provincia` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `municipios`
--

INSERT INTO `municipios` (`id_municipio`, `id_provincia`, `nome`, `estado`, `data_criacao`) VALUES
(1, 1, 'Ambriz', 'ATIVO', '2026-10-08 21:27:58'),
(2, 1, 'Bula Atumba', 'ATIVO', '2026-10-08 21:27:58'),
(3, 1, 'Dande', 'ATIVO', '2026-10-08 21:27:58'),
(4, 1, 'Nambuangongo', 'ATIVO', '2026-10-08 21:27:58'),
(5, 1, 'Pango Aluquém', 'ATIVO', '2026-10-08 21:27:58'),
(6, 1, 'Quibaxe', 'ATIVO', '2026-10-08 21:27:58'),
(7, 1, 'Muxaluando', 'ATIVO', '2026-10-08 21:27:58'),
(8, 1, 'Piri', 'ATIVO', '2026-10-08 21:27:58'),
(9, 1, 'Quicunzo', 'ATIVO', '2026-10-08 21:27:58'),
(10, 1, 'Úcua', 'ATIVO', '2026-10-08 21:27:58'),
(11, 1, 'Panguila', 'ATIVO', '2026-10-08 21:27:58'),
(12, 1, 'Barra do Dande', 'ATIVO', '2026-10-08 21:27:58'),
(17, 2, 'Baía Farta', 'ATIVO', '2026-10-08 21:30:17'),
(18, 2, 'Balombo', 'ATIVO', '2026-10-08 21:30:17'),
(19, 2, 'Benguela', 'ATIVO', '2026-10-08 21:30:17'),
(20, 2, 'Bocoio', 'ATIVO', '2026-10-08 21:30:17'),
(21, 2, 'Caimbambo', 'ATIVO', '2026-10-08 21:30:17'),
(22, 2, 'Catumbela', 'ATIVO', '2026-10-08 21:30:17'),
(23, 2, 'Chongorói', 'ATIVO', '2026-10-08 21:30:17'),
(24, 2, 'Cubal', 'ATIVO', '2026-10-08 21:30:17'),
(25, 2, 'Ganda', 'ATIVO', '2026-10-08 21:30:17'),
(26, 2, 'Lobito', 'ATIVO', '2026-10-08 21:30:17'),
(27, 2, 'Egito Praia', 'ATIVO', '2026-10-08 21:30:17'),
(28, 2, 'Chindumbo', 'ATIVO', '2026-10-08 21:30:17'),
(29, 2, 'Dombe Grande', 'ATIVO', '2026-10-08 21:30:17'),
(30, 2, 'Capupa', 'ATIVO', '2026-10-08 21:30:17'),
(31, 2, 'Biópio', 'ATIVO', '2026-10-08 21:30:17'),
(32, 2, 'Chila', 'ATIVO', '2026-10-08 21:30:17'),
(33, 2, 'Chicuma', 'ATIVO', '2026-10-08 21:30:17'),
(34, 2, 'Babaera', 'ATIVO', '2026-10-08 21:30:17'),
(35, 2, 'Iambala', 'ATIVO', '2026-10-08 21:30:17'),
(36, 2, 'Catengue', 'ATIVO', '2026-10-08 21:30:17'),
(37, 2, 'Bolonguera', 'ATIVO', '2026-10-08 21:30:17'),
(38, 2, 'Canhamela', 'ATIVO', '2026-10-08 21:30:17'),
(39, 2, 'Navegantes', 'ATIVO', '2026-10-08 21:30:17'),
(50, 3, 'Andulo', 'ATIVO', '2026-10-08 21:33:11'),
(51, 3, 'Camacupa', 'ATIVO', '2026-10-08 21:33:11'),
(52, 3, 'Catabola', 'ATIVO', '2026-10-08 21:33:11'),
(53, 3, 'Chinguar', 'ATIVO', '2026-10-08 21:33:11'),
(54, 3, 'Chitembo', 'ATIVO', '2026-10-08 21:33:11'),
(55, 3, 'Cuemba', 'ATIVO', '2026-10-08 21:33:11'),
(56, 3, 'Cunhinga', 'ATIVO', '2026-10-08 21:33:11'),
(57, 3, 'Cuito', 'ATIVO', '2026-10-08 21:33:11'),
(58, 3, 'Nharêa', 'ATIVO', '2026-10-08 21:33:11'),
(59, 3, 'Luando', 'ATIVO', '2026-10-08 21:33:11'),
(60, 3, 'Ringoma', 'ATIVO', '2026-10-08 21:33:11'),
(61, 3, 'Mumbuê', 'ATIVO', '2026-10-08 21:33:11'),
(62, 3, 'Calucinga', 'ATIVO', '2026-10-08 21:33:11'),
(63, 3, 'Chicala', 'ATIVO', '2026-10-08 21:33:11'),
(64, 3, 'Chipeta', 'ATIVO', '2026-10-08 21:33:11'),
(65, 3, 'Umpulo', 'ATIVO', '2026-10-08 21:33:11'),
(66, 3, 'Lúbia', 'ATIVO', '2026-10-08 21:33:11'),
(67, 3, 'Cambândua', 'ATIVO', '2026-10-08 21:33:11'),
(68, 3, 'Belo Horizonte', 'ATIVO', '2026-10-08 21:33:11'),
(81, 4, 'Cabinda', 'ATIVO', '2026-10-08 21:33:11'),
(82, 4, 'Cacongo', 'ATIVO', '2026-10-08 21:33:11'),
(83, 4, 'Buco-Zau', 'ATIVO', '2026-10-08 21:33:11'),
(84, 4, 'Belize', 'ATIVO', '2026-10-08 21:33:11'),
(85, 4, 'Miconje', 'ATIVO', '2026-10-08 21:33:11'),
(86, 4, 'Massabi', 'ATIVO', '2026-10-08 21:33:11'),
(87, 4, 'Necuto', 'ATIVO', '2026-10-08 21:33:11'),
(88, 4, 'Tando Zinze', 'ATIVO', '2026-10-08 21:33:11'),
(89, 4, 'Liambo', 'ATIVO', '2026-10-08 21:33:11'),
(90, 4, 'Ngoio', 'ATIVO', '2026-10-08 21:33:11'),
(96, 5, 'Cuito Cuanavale', 'ATIVO', '2026-10-08 21:33:11'),
(97, 5, 'Dirico', 'ATIVO', '2026-10-08 21:33:11'),
(98, 5, 'Mavinga', 'ATIVO', '2026-10-08 21:33:11'),
(99, 5, 'Rivungo', 'ATIVO', '2026-10-08 21:33:11'),
(100, 5, 'Xipundo', 'ATIVO', '2026-10-08 21:33:11'),
(101, 5, 'Dima', 'ATIVO', '2026-10-08 21:33:11'),
(102, 5, 'Luiana', 'ATIVO', '2026-10-08 21:33:11'),
(103, 5, 'Mucusso', 'ATIVO', '2026-10-08 21:33:11'),
(104, 5, 'Luengue', 'ATIVO', '2026-10-08 21:33:11'),
(111, 6, 'Ambaca', 'ATIVO', '2026-10-08 21:33:11'),
(112, 6, 'Banga', 'ATIVO', '2026-10-08 21:33:11'),
(113, 6, 'Bolongongo', 'ATIVO', '2026-10-08 21:33:11'),
(114, 6, 'Cambambe', 'ATIVO', '2026-10-08 21:33:11'),
(115, 6, 'Cazengo', 'ATIVO', '2026-10-08 21:33:11'),
(116, 6, 'Golungo Alto', 'ATIVO', '2026-10-08 21:33:11'),
(117, 6, 'Ngonguembo', 'ATIVO', '2026-10-08 21:33:11'),
(118, 6, 'Lucala', 'ATIVO', '2026-10-08 21:33:11'),
(119, 6, 'Quiculungo', 'ATIVO', '2026-10-08 21:33:11'),
(120, 6, 'Samba Cajú', 'ATIVO', '2026-10-08 21:33:11'),
(121, 6, 'Massangano', 'ATIVO', '2026-10-08 21:33:11'),
(122, 6, 'Cêrca', 'ATIVO', '2026-10-08 21:33:11'),
(123, 6, 'Tango', 'ATIVO', '2026-10-08 21:33:11'),
(124, 6, 'Terreiro', 'ATIVO', '2026-10-08 21:33:11'),
(125, 6, 'Aldeia Nova', 'ATIVO', '2026-10-08 21:33:11'),
(126, 6, 'Caculo Cabaça', 'ATIVO', '2026-10-08 21:33:11'),
(127, 6, 'Luinga', 'ATIVO', '2026-10-08 21:33:11'),
(142, 7, 'Gabela', 'ATIVO', '2026-10-08 21:33:11'),
(143, 7, 'Cassongue', 'ATIVO', '2026-10-08 21:33:11'),
(144, 7, 'Conda', 'ATIVO', '2026-10-08 21:33:11'),
(145, 7, 'Ebo', 'ATIVO', '2026-10-08 21:33:11'),
(146, 7, 'Mussende', 'ATIVO', '2026-10-08 21:33:11'),
(147, 7, 'Porto Amboim', 'ATIVO', '2026-10-08 21:33:11'),
(148, 7, 'Quilenda', 'ATIVO', '2026-10-08 21:33:11'),
(149, 7, 'Quibala', 'ATIVO', '2026-10-08 21:33:11'),
(150, 7, 'Seles', 'ATIVO', '2026-10-08 21:33:11'),
(151, 7, 'Sumbe', 'ATIVO', '2026-10-08 21:33:11'),
(152, 7, 'Waku Kungo', 'ATIVO', '2026-10-08 21:33:11'),
(153, 7, 'Calulo', 'ATIVO', '2026-10-08 21:33:11'),
(154, 7, 'Quirimbo', 'ATIVO', '2026-10-08 21:33:11'),
(155, 7, 'Munenga', 'ATIVO', '2026-10-08 21:33:11'),
(156, 7, 'Quissongo', 'ATIVO', '2026-10-08 21:33:11'),
(157, 7, 'Gungo', 'ATIVO', '2026-10-08 21:33:11'),
(158, 7, 'Sanga', 'ATIVO', '2026-10-08 21:33:11'),
(159, 7, 'Gangula', 'ATIVO', '2026-10-08 21:33:11'),
(160, 7, 'Pambangala', 'ATIVO', '2026-10-08 21:33:11'),
(161, 7, 'Condé', 'ATIVO', '2026-10-08 21:33:11'),
(162, 7, 'Amboiva', 'ATIVO', '2026-10-08 21:33:11'),
(163, 7, 'Lonhe', 'ATIVO', '2026-10-08 21:33:11'),
(164, 7, 'Quenha', 'ATIVO', '2026-10-08 21:33:11'),
(165, 7, 'Boa Entrada', 'ATIVO', '2026-10-08 21:33:11'),
(173, 8, 'Menongue', 'ATIVO', '2026-10-08 21:33:11'),
(174, 8, 'Cutato', 'ATIVO', '2026-10-08 21:33:11'),
(175, 8, 'Cuchi', 'ATIVO', '2026-10-08 21:33:11'),
(176, 8, 'Longa', 'ATIVO', '2026-10-08 21:33:11'),
(177, 8, 'Chinguanja', 'ATIVO', '2026-10-08 21:33:11'),
(178, 8, 'Caiundo', 'ATIVO', '2026-10-08 21:33:11'),
(179, 8, 'Savate', 'ATIVO', '2026-10-08 21:33:11'),
(180, 8, 'Cuangar', 'ATIVO', '2026-10-08 21:33:11'),
(181, 8, 'Calai', 'ATIVO', '2026-10-08 21:33:11'),
(182, 8, 'Mavengue', 'ATIVO', '2026-10-08 21:33:11'),
(183, 8, 'Nancova', 'ATIVO', '2026-10-08 21:33:11'),
(188, 9, 'Cahama', 'ATIVO', '2026-10-08 21:33:11'),
(189, 9, 'Cuanhama', 'ATIVO', '2026-10-08 21:33:11'),
(190, 9, 'Curoca', 'ATIVO', '2026-10-08 21:33:11'),
(191, 9, 'Cuvelai', 'ATIVO', '2026-10-08 21:33:11'),
(192, 9, 'Namacunde', 'ATIVO', '2026-10-08 21:33:11'),
(193, 9, 'Ombadja', 'ATIVO', '2026-10-08 21:33:11'),
(194, 9, 'Chiéde', 'ATIVO', '2026-10-08 21:33:11'),
(195, 9, 'Nehone', 'ATIVO', '2026-10-08 21:33:11'),
(196, 9, 'Humbe', 'ATIVO', '2026-10-08 21:33:11'),
(197, 9, 'Mupa', 'ATIVO', '2026-10-08 21:33:11'),
(198, 9, 'Naulila', 'ATIVO', '2026-10-08 21:33:11'),
(199, 9, 'Chitado', 'ATIVO', '2026-10-08 21:33:11'),
(200, 9, 'Cafima', 'ATIVO', '2026-10-08 21:33:11'),
(201, 9, 'Chissuata', 'ATIVO', '2026-10-08 21:33:11'),
(203, 10, 'Longonjo', 'ATIVO', '2026-10-08 21:33:11'),
(204, 10, 'Bailundo', 'ATIVO', '2026-10-08 21:33:11'),
(205, 10, 'Chicala Choloanga', 'ATIVO', '2026-10-08 21:33:11'),
(206, 10, 'Mungo', 'ATIVO', '2026-10-08 21:33:11'),
(207, 10, 'Chinjenje', 'ATIVO', '2026-10-08 21:33:11'),
(208, 10, 'Ucuma', 'ATIVO', '2026-10-08 21:33:11'),
(209, 10, 'Cachiungo', 'ATIVO', '2026-10-08 21:33:11'),
(210, 10, 'Caála', 'ATIVO', '2026-10-08 21:33:11'),
(211, 10, 'Ecunha', 'ATIVO', '2026-10-08 21:33:11'),
(212, 10, 'Huambo', 'ATIVO', '2026-10-08 21:33:11'),
(213, 10, 'Londuimbali', 'ATIVO', '2026-10-08 21:33:11'),
(214, 10, 'Bimbe', 'ATIVO', '2026-10-08 21:33:11'),
(215, 10, 'Sambo', 'ATIVO', '2026-10-08 21:33:11'),
(216, 10, 'Galanga', 'ATIVO', '2026-10-08 21:33:11'),
(217, 10, 'Alto Hama', 'ATIVO', '2026-10-08 21:33:11'),
(218, 10, 'Chilata', 'ATIVO', '2026-10-08 21:33:11'),
(219, 10, 'Cuima', 'ATIVO', '2026-10-08 21:33:11'),
(234, 11, 'Caconda', 'ATIVO', '2026-10-08 21:33:11'),
(235, 11, 'Cacula', 'ATIVO', '2026-10-08 21:33:11'),
(236, 11, 'Caluquembe', 'ATIVO', '2026-10-08 21:33:11'),
(237, 11, 'Gambos', 'ATIVO', '2026-10-08 21:33:11'),
(238, 11, 'Chibia', 'ATIVO', '2026-10-08 21:33:11'),
(239, 11, 'Chicomba', 'ATIVO', '2026-10-08 21:33:11'),
(240, 11, 'Chipindo', 'ATIVO', '2026-10-08 21:33:11'),
(241, 11, 'Cuvango', 'ATIVO', '2026-10-08 21:33:11'),
(242, 11, 'Humpata', 'ATIVO', '2026-10-08 21:33:11'),
(243, 11, 'Lubango', 'ATIVO', '2026-10-08 21:33:11'),
(244, 11, 'Matala', 'ATIVO', '2026-10-08 21:33:11'),
(245, 11, 'Quilengues', 'ATIVO', '2026-10-08 21:33:11'),
(246, 11, 'Quipungo', 'ATIVO', '2026-10-08 21:33:11'),
(247, 11, 'Jamba Mineira', 'ATIVO', '2026-10-08 21:33:11'),
(248, 11, 'Dongo', 'ATIVO', '2026-10-08 21:33:11'),
(249, 11, 'Hoque', 'ATIVO', '2026-10-08 21:33:11'),
(250, 11, 'Capelongo', 'ATIVO', '2026-10-08 21:33:11'),
(251, 11, 'Chituto', 'ATIVO', '2026-10-08 21:33:11'),
(252, 11, 'Capunda Cavilongo', 'ATIVO', '2026-10-08 21:33:11'),
(253, 11, 'Viti Vivali', 'ATIVO', '2026-10-08 21:33:11'),
(254, 11, 'Galangue', 'ATIVO', '2026-10-08 21:33:11'),
(255, 11, 'Palanca', 'ATIVO', '2026-10-08 21:33:11'),
(256, 11, 'Chicungo', 'ATIVO', '2026-10-08 21:33:11'),
(265, 12, 'Sequele', 'ATIVO', '2026-10-08 21:33:11'),
(266, 12, 'Cabo Ledo', 'ATIVO', '2026-10-08 21:33:11'),
(267, 12, 'Bom Jesus', 'ATIVO', '2026-10-08 21:33:11'),
(268, 12, 'Cabiri', 'ATIVO', '2026-10-08 21:33:11'),
(269, 12, 'Catete', 'ATIVO', '2026-10-08 21:33:11'),
(270, 12, 'Calumbo', 'ATIVO', '2026-10-08 21:33:11'),
(271, 12, 'Quiçama', 'ATIVO', '2026-10-08 21:33:11'),
(272, 13, 'Luanda', 'ATIVO', '2026-10-08 21:33:11'),
(273, 13, 'Cacuaco', 'ATIVO', '2026-10-08 21:33:11'),
(274, 13, 'Cazenga', 'ATIVO', '2026-10-08 21:33:11'),
(275, 13, 'Viana', 'ATIVO', '2026-10-08 21:33:11'),
(276, 13, 'Belas', 'ATIVO', '2026-10-08 21:33:11'),
(277, 13, 'Talatona', 'ATIVO', '2026-10-08 21:33:11'),
(278, 13, 'Mussulo', 'ATIVO', '2026-10-08 21:33:11'),
(279, 13, 'Sambizanga', 'ATIVO', '2026-10-08 21:33:11'),
(280, 13, 'Rangel', 'ATIVO', '2026-10-08 21:33:11'),
(281, 13, 'Maianga', 'ATIVO', '2026-10-08 21:33:11'),
(282, 13, 'Samba', 'ATIVO', '2026-10-08 21:33:11'),
(283, 13, 'Camama', 'ATIVO', '2026-10-08 21:33:11'),
(284, 13, 'Mulenvos', 'ATIVO', '2026-10-08 21:33:11'),
(285, 13, 'Kilamba', 'ATIVO', '2026-10-08 21:33:11'),
(286, 13, 'Hoji Ya Henda', 'ATIVO', '2026-10-08 21:33:11'),
(287, 13, 'Ingombota', 'ATIVO', '2026-10-08 21:33:11'),
(303, 14, 'Cambulo', 'ATIVO', '2026-10-08 21:33:11'),
(304, 14, 'Capenda Camulemba', 'ATIVO', '2026-10-08 21:33:11'),
(305, 14, 'Caungula', 'ATIVO', '2026-10-08 21:33:11'),
(306, 14, 'Chitato', 'ATIVO', '2026-10-08 21:33:11'),
(307, 14, 'Cuango', 'ATIVO', '2026-10-08 21:33:11'),
(308, 14, 'Cuílo', 'ATIVO', '2026-10-08 21:33:11'),
(309, 14, 'Lubalo', 'ATIVO', '2026-10-08 21:33:11'),
(310, 14, 'Lucapa', 'ATIVO', '2026-10-08 21:33:11'),
(311, 14, 'Lóvua', 'ATIVO', '2026-10-08 21:33:11'),
(312, 14, 'Xá-Muteba', 'ATIVO', '2026-10-08 21:33:11'),
(313, 14, 'Dundo', 'ATIVO', '2026-10-08 21:33:11'),
(314, 14, 'Xá Cassau', 'ATIVO', '2026-10-08 21:33:11'),
(315, 14, 'Camaxilo', 'ATIVO', '2026-10-08 21:33:11'),
(316, 14, 'Luangue', 'ATIVO', '2026-10-08 21:33:11'),
(317, 14, 'Luremo', 'ATIVO', '2026-10-08 21:33:11'),
(318, 14, 'Canzar', 'ATIVO', '2026-10-08 21:33:11'),
(319, 14, 'Cassanje Calucala', 'ATIVO', '2026-10-08 21:33:11'),
(320, 14, 'Mussungue', 'ATIVO', '2026-10-08 21:33:11'),
(321, 14, 'Cafunfu', 'ATIVO', '2026-10-08 21:33:11'),
(334, 15, 'Cacolo', 'ATIVO', '2026-10-08 21:33:11'),
(335, 15, 'Dala', 'ATIVO', '2026-10-08 21:33:11'),
(336, 15, 'Muconda', 'ATIVO', '2026-10-08 21:33:11'),
(337, 15, 'Saurimo', 'ATIVO', '2026-10-08 21:33:11'),
(338, 15, 'Chiluage', 'ATIVO', '2026-10-08 21:33:11'),
(339, 15, 'Cassai-Sul', 'ATIVO', '2026-10-08 21:33:11'),
(340, 15, 'Xassengue', 'ATIVO', '2026-10-08 21:33:11'),
(341, 15, 'Alto Chicapa', 'ATIVO', '2026-10-08 21:33:11'),
(342, 15, 'Sombo', 'ATIVO', '2026-10-08 21:33:11'),
(343, 15, 'Muriege', 'ATIVO', '2026-10-08 21:33:11'),
(344, 15, 'Luma Cassai', 'ATIVO', '2026-10-08 21:33:11'),
(345, 15, 'Cazage', 'ATIVO', '2026-10-08 21:33:11'),
(346, 15, 'Muangueji', 'ATIVO', '2026-10-08 21:33:11'),
(347, 15, 'Cassengo', 'ATIVO', '2026-10-08 21:33:11'),
(349, 16, 'Cacuso', 'ATIVO', '2026-10-08 21:33:11'),
(350, 16, 'Cahombo', 'ATIVO', '2026-10-08 21:33:11'),
(351, 16, 'Calandula', 'ATIVO', '2026-10-08 21:33:11'),
(352, 16, 'Cambundi Catembo', 'ATIVO', '2026-10-08 21:33:11'),
(353, 16, 'Cangandala', 'ATIVO', '2026-10-08 21:33:11'),
(354, 16, 'Kunda dya Baze', 'ATIVO', '2026-10-08 21:33:11'),
(355, 16, 'Luquembo', 'ATIVO', '2026-10-08 21:33:11'),
(356, 16, 'Malanje', 'ATIVO', '2026-10-08 21:33:11'),
(357, 16, 'Marimba', 'ATIVO', '2026-10-08 21:33:11'),
(358, 16, 'Massango', 'ATIVO', '2026-10-08 21:33:11'),
(359, 16, 'Quela', 'ATIVO', '2026-10-08 21:33:11'),
(360, 16, 'Quirima', 'ATIVO', '2026-10-08 21:33:11'),
(361, 16, 'Kiwaba Nzoji', 'ATIVO', '2026-10-08 21:33:11'),
(362, 16, 'Cateco Cangola', 'ATIVO', '2026-10-08 21:33:11'),
(363, 16, 'Cuale', 'ATIVO', '2026-10-08 21:33:11'),
(364, 16, 'Pungo A Ndongo', 'ATIVO', '2026-10-08 21:33:11'),
(365, 16, 'Ngola Luiji', 'ATIVO', '2026-10-08 21:33:11'),
(366, 16, 'Quihuhu', 'ATIVO', '2026-10-08 21:33:11'),
(367, 16, 'Xandel', 'ATIVO', '2026-10-08 21:33:11'),
(368, 16, 'Cambo Suinginge', 'ATIVO', '2026-10-08 21:33:11'),
(369, 16, 'Milando', 'ATIVO', '2026-10-08 21:33:11'),
(370, 16, 'Quitapa', 'ATIVO', '2026-10-08 21:33:11'),
(371, 16, 'Capunda', 'ATIVO', '2026-10-08 21:33:11'),
(372, 16, 'Muquixe', 'ATIVO', '2026-10-08 21:33:11'),
(373, 16, 'Quêssua', 'ATIVO', '2026-10-08 21:33:11'),
(374, 16, 'Caculama', 'ATIVO', '2026-10-08 21:33:11'),
(375, 16, 'Mbanji Ya Ngola', 'ATIVO', '2026-10-08 21:33:11'),
(380, 17, 'Camanongue', 'ATIVO', '2026-10-08 21:33:11'),
(381, 17, 'Léua', 'ATIVO', '2026-10-08 21:33:11'),
(382, 17, 'Chiúme', 'ATIVO', '2026-10-08 21:33:11'),
(383, 17, 'Lumbala Nguimbo', 'ATIVO', '2026-10-08 21:33:11'),
(384, 17, 'Alto Cuito', 'ATIVO', '2026-10-08 21:33:11'),
(385, 17, 'Lutembo', 'ATIVO', '2026-10-08 21:33:11'),
(386, 17, 'Cangumbe', 'ATIVO', '2026-10-08 21:33:11'),
(387, 17, 'Luena', 'ATIVO', '2026-10-08 21:33:11'),
(388, 17, 'Cangamba', 'ATIVO', '2026-10-08 21:33:11'),
(389, 17, 'Lucusse', 'ATIVO', '2026-10-08 21:33:11'),
(390, 17, 'Ninda', 'ATIVO', '2026-10-08 21:33:11'),
(391, 17, 'Lutuai', 'ATIVO', '2026-10-08 21:33:11'),
(395, 18, 'Cazombo', 'ATIVO', '2026-10-08 21:33:11'),
(396, 18, 'Luacano', 'ATIVO', '2026-10-08 21:33:11'),
(397, 18, 'Cameia', 'ATIVO', '2026-10-08 21:33:11'),
(398, 18, 'Luau', 'ATIVO', '2026-10-08 21:33:11'),
(399, 18, 'Nana Candundo', 'ATIVO', '2026-10-08 21:33:11'),
(400, 18, 'Macondo', 'ATIVO', '2026-10-08 21:33:11'),
(401, 18, 'Caianda', 'ATIVO', '2026-10-08 21:33:11'),
(402, 18, 'Lóvua do Zambeze', 'ATIVO', '2026-10-08 21:33:11'),
(403, 18, 'Lago Dilolo', 'ATIVO', '2026-10-08 21:33:11'),
(410, 19, 'Moçâmedes', 'ATIVO', '2026-10-08 21:33:11'),
(411, 19, 'Camucuio', 'ATIVO', '2026-10-08 21:33:11'),
(412, 19, 'Bibala', 'ATIVO', '2026-10-08 21:33:11'),
(413, 19, 'Virei', 'ATIVO', '2026-10-08 21:33:11'),
(414, 19, 'Tômbua', 'ATIVO', '2026-10-08 21:33:11'),
(415, 19, 'Lucira', 'ATIVO', '2026-10-08 21:33:11'),
(416, 19, 'Iona', 'ATIVO', '2026-10-08 21:33:11'),
(417, 19, 'Sacomar', 'ATIVO', '2026-10-08 21:33:11'),
(418, 19, 'Cacimbas', 'ATIVO', '2026-10-08 21:33:11'),
(425, 20, 'Uíge', 'ATIVO', '2026-10-08 21:33:11'),
(426, 20, 'Ambuíla', 'ATIVO', '2026-10-08 21:33:11'),
(427, 20, 'Bembe', 'ATIVO', '2026-10-08 21:33:11'),
(428, 20, 'Bungo', 'ATIVO', '2026-10-08 21:33:11'),
(429, 20, 'Milunga', 'ATIVO', '2026-10-08 21:33:11'),
(430, 20, 'Damba', 'ATIVO', '2026-10-08 21:33:11'),
(431, 20, 'Maquela do Zombo', 'ATIVO', '2026-10-08 21:33:11'),
(432, 20, 'Mucaba', 'ATIVO', '2026-10-08 21:33:11'),
(433, 20, 'Negage', 'ATIVO', '2026-10-08 21:33:11'),
(434, 20, 'Puri', 'ATIVO', '2026-10-08 21:33:11'),
(435, 20, 'Quimbele', 'ATIVO', '2026-10-08 21:33:11'),
(436, 20, 'Sanza Pombo', 'ATIVO', '2026-10-08 21:33:11'),
(437, 20, 'Songo', 'ATIVO', '2026-10-08 21:33:11'),
(438, 20, 'Cangola', 'ATIVO', '2026-10-08 21:33:11'),
(439, 20, 'Nova Esperança', 'ATIVO', '2026-10-08 21:33:11'),
(440, 20, 'Dange Quitexe', 'ATIVO', '2026-10-08 21:33:11'),
(441, 20, 'Sacandica', 'ATIVO', '2026-10-08 21:33:11'),
(442, 20, 'Nsosso', 'ATIVO', '2026-10-08 21:33:11'),
(443, 20, 'Lucunga', 'ATIVO', '2026-10-08 21:33:11'),
(444, 20, 'Quipedro', 'ATIVO', '2026-10-08 21:33:11'),
(445, 20, 'Massau', 'ATIVO', '2026-10-08 21:33:11'),
(446, 20, 'Vista Alegre', 'ATIVO', '2026-10-08 21:33:11'),
(447, 20, 'Alto Zaza', 'ATIVO', '2026-10-08 21:33:11'),
(456, 21, 'Mbanza Kongo', 'ATIVO', '2026-10-08 21:33:11'),
(457, 21, 'Soyo', 'ATIVO', '2026-10-08 21:33:11'),
(458, 21, 'Nzeto', 'ATIVO', '2026-10-08 21:33:11'),
(459, 21, 'Cuimba', 'ATIVO', '2026-10-08 21:33:11'),
(460, 21, 'Nóqui', 'ATIVO', '2026-10-08 21:33:11'),
(461, 21, 'Tomboco', 'ATIVO', '2026-10-08 21:33:11'),
(462, 21, 'Luvo', 'ATIVO', '2026-10-08 21:33:11'),
(463, 21, 'Lufico', 'ATIVO', '2026-10-08 21:33:11'),
(464, 21, 'Quêlo', 'ATIVO', '2026-10-08 21:33:11'),
(465, 21, 'Serra de Canda', 'ATIVO', '2026-10-08 21:33:11'),
(466, 21, 'Quindeje', 'ATIVO', '2026-10-08 21:33:11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `niveis_habilitacao`
--

CREATE TABLE `niveis_habilitacao` (
  `id_nivel_habilitacao` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `ordem` int(11) NOT NULL DEFAULT 0,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `niveis_habilitacao`
--

INSERT INTO `niveis_habilitacao` (`id_nivel_habilitacao`, `nome`, `ordem`, `descricao`, `estado`, `data_criacao`) VALUES
(1, 'Alfabetização', 1, NULL, 'ATIVO', '2026-10-12 12:45:45'),
(2, 'Ensino Primário', 2, NULL, 'ATIVO', '2026-10-12 12:45:45'),
(3, 'I Ciclo do Ensino Secundário', 3, NULL, 'ATIVO', '2026-10-12 12:45:45'),
(4, 'II Ciclo do Ensino Secundário', 4, NULL, 'ATIVO', '2026-10-12 12:45:45'),
(5, 'Ensino Médio', 5, NULL, 'ATIVO', '2026-10-12 12:45:45'),
(6, 'Ensino Médio Técnico', 6, NULL, 'ATIVO', '2026-10-12 12:45:45'),
(7, 'Bacharelato', 7, NULL, 'ATIVO', '2026-10-12 12:45:45'),
(8, 'Licenciatura', 8, NULL, 'ATIVO', '2026-10-12 12:45:45'),
(9, 'Pós-Graduação', 9, NULL, 'ATIVO', '2026-10-12 12:45:45'),
(10, 'Mestrado', 10, NULL, 'ATIVO', '2026-10-12 12:45:45'),
(11, 'Doutoramento', 11, NULL, 'ATIVO', '2026-10-12 12:45:45'),
(12, 'Pós-Doutoramento', 12, NULL, 'ATIVO', '2026-10-12 12:45:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `nomes_formacao`
--

CREATE TABLE `nomes_formacao` (
  `id_nome_formacao` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `categoria` enum('MILITAR','GERAL') NOT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id_notificacao` int(11) NOT NULL,
  `id_ramo` int(11) NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `id_especialista` int(11) DEFAULT NULL,
  `id_equipamento` int(11) DEFAULT NULL,
  `id_tipo_alerta` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `mensagem` text DEFAULT NULL,
  `nivel` enum('INFO','ATENCAO','URGENTE') DEFAULT 'INFO',
  `data_referencia` datetime DEFAULT NULL,
  `lida` tinyint(1) DEFAULT 0,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `data_leitura` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `paises`
--

CREATE TABLE `paises` (
  `id_pais` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `nacionalidade` varchar(100) DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `paises`
--

INSERT INTO `paises` (`id_pais`, `nome`, `codigo`, `nacionalidade`, `estado`, `data_criacao`) VALUES
(1, 'Afeganistão', 'AF', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(2, 'Alanda', 'AX', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(3, 'Albânia', 'AL', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(4, 'Alemanha', 'DE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(5, 'Andorra', 'AD', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(6, 'Angola', 'AO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(7, 'Anguila', 'AI', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(8, 'Antártida', 'AQ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(9, 'Antígua e Barbuda', 'AG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(10, 'Argentina', 'AR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(11, 'Argélia', 'DZ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(12, 'Arménia', 'AM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(13, 'Aruba', 'AW', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(14, 'Arábia Saudita', 'SA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(15, 'Austrália', 'AU', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(16, 'Azerbaijão', 'AZ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(17, 'Baamas', 'BS', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(18, 'Bangladeche', 'BD', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(19, 'Barbados', 'BB', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(20, 'Barém', 'BH', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(21, 'Belize', 'BZ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(22, 'Benim', 'BJ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(23, 'Bermudas', 'BM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(24, 'Bielorrússia', 'BY', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(25, 'Bolívia', 'BO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(26, 'Botsuana', 'BW', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(27, 'Brasil', 'BR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(28, 'Brunei', 'BN', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(29, 'Bulgária', 'BG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(30, 'Burquina Faso', 'BF', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(31, 'Burundi', 'BI', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(32, 'Butão', 'BT', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(33, 'Bélgica', 'BE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(34, 'Bósnia e Herzegovina', 'BA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(35, 'Cabo Verde', 'CV', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(36, 'Camarões', 'CM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(37, 'Camboja', 'KH', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(38, 'Canadá', 'CA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(39, 'Catar', 'QA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(40, 'Cazaquistão', 'KZ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(41, 'Chade', 'TD', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(42, 'Chile', 'CL', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(43, 'China', 'CN', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(44, 'Chipre', 'CY', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(45, 'Chéquia', 'CZ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(46, 'Cidade do Vaticano', 'VA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(47, 'Colômbia', 'CO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(48, 'Comores', 'KM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(49, 'Congo-Brazzaville', 'CG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(50, 'Congo-Kinshasa', 'CD', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(51, 'Coreia do Norte', 'KP', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(52, 'Coreia do Sul', 'KR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(53, 'Costa Rica', 'CR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(54, 'Croácia', 'HR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(55, 'Cuba', 'CU', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(56, 'Curaçau', 'CW', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(57, 'Côte d’Ivoire (Costa do Marfim)', 'CI', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(58, 'Dinamarca', 'DK', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(59, 'Domínica', 'DM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(60, 'Egito', 'EG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(61, 'Emirados Árabes Unidos', 'AE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(62, 'Equador', 'EC', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(63, 'Eritreia', 'ER', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(64, 'Eslováquia', 'SK', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(65, 'Eslovénia', 'SI', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(66, 'Espanha', 'ES', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(67, 'Essuatíni', 'SZ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(68, 'Estados Unidos', 'US', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(69, 'Estónia', 'EE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(70, 'Etiópia', 'ET', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(71, 'Fiji', 'FJ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(72, 'Filipinas', 'PH', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(73, 'Finlândia', 'FI', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(74, 'França', 'FR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(75, 'Gabão', 'GA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(76, 'Gana', 'GH', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(77, 'Geórgia', 'GE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(78, 'Gibraltar', 'GI', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(79, 'Granada', 'GD', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(80, 'Gronelândia', 'GL', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(81, 'Grécia', 'GR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(82, 'Guadalupe', 'GP', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(83, 'Guame', 'GU', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(84, 'Guatemala', 'GT', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(85, 'Guernesey', 'GG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(86, 'Guiana', 'GY', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(87, 'Guiana Francesa', 'GF', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(88, 'Guiné', 'GN', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(89, 'Guiné Equatorial', 'GQ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(90, 'Guiné-Bissau', 'GW', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(91, 'Gâmbia', 'GM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(92, 'Haiti', 'HT', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(93, 'Honduras', 'HN', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(94, 'Hong Kong, RAE da China', 'HK', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(95, 'Hungria', 'HU', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(96, 'Ilha Bouvet', 'BV', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(97, 'Ilha Norfolk', 'NF', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(98, 'Ilha de Man', 'IM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(99, 'Ilha do Natal', 'CX', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(100, 'Ilhas Caimão', 'KY', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(101, 'Ilhas Cook', 'CK', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(102, 'Ilhas Falkland', 'FK', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(103, 'Ilhas Faroé', 'FO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(104, 'Ilhas Geórgia do Sul e Sandwich do Sul', 'GS', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(105, 'Ilhas Heard e McDonald', 'HM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(106, 'Ilhas Marianas do Norte', 'MP', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(107, 'Ilhas Marshall', 'MH', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(108, 'Ilhas Menores Afastadas dos EUA', 'UM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(109, 'Ilhas Pitcairn', 'PN', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(110, 'Ilhas Salomão', 'SB', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(111, 'Ilhas Turcas e Caicos', 'TC', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(112, 'Ilhas Virgens Britânicas', 'VG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(113, 'Ilhas Virgens dos EUA', 'VI', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(114, 'Ilhas dos Cocos (Keeling)', 'CC', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(115, 'Indonésia', 'ID', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(116, 'Iraque', 'IQ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(117, 'Irlanda', 'IE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(118, 'Irão', 'IR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(119, 'Islândia', 'IS', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(120, 'Israel', 'IL', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(121, 'Itália', 'IT', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(122, 'Iémen', 'YE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(123, 'Jamaica', 'JM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(124, 'Japão', 'JP', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(125, 'Jersey', 'JE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(126, 'Jibuti', 'DJ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(127, 'Jordânia', 'JO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(128, 'Koweit', 'KW', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(129, 'Laos', 'LA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(130, 'Lesoto', 'LS', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(131, 'Letónia', 'LV', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(132, 'Libéria', 'LR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(133, 'Listenstaine', 'LI', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(134, 'Lituânia', 'LT', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(135, 'Luxemburgo', 'LU', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(136, 'Líbano', 'LB', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(137, 'Líbia', 'LY', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(138, 'Macau, RAE da China', 'MO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(139, 'Macedónia do Norte', 'MK', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(140, 'Madagáscar', 'MG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(141, 'Maiote', 'YT', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(142, 'Maldivas', 'MV', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(143, 'Mali', 'ML', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(144, 'Malta', 'MT', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(145, 'Malásia', 'MY', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(146, 'Maláui', 'MW', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(147, 'Marrocos', 'MA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(148, 'Martinica', 'MQ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(149, 'Mauritânia', 'MR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(150, 'Maurícia', 'MU', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(151, 'Mianmar (Birmânia)', 'MM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(152, 'Micronésia', 'FM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(153, 'Moldávia', 'MD', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(154, 'Mongólia', 'MN', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(155, 'Monserrate', 'MS', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(156, 'Montenegro', 'ME', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(157, 'Moçambique', 'MZ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(158, 'México', 'MX', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(159, 'Mónaco', 'MC', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(160, 'Namíbia', 'NA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(161, 'Nauru', 'NR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(162, 'Nepal', 'NP', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(163, 'Nicarágua', 'NI', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(164, 'Nigéria', 'NG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(165, 'Niuê', 'NU', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(166, 'Noruega', 'NO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(167, 'Nova Caledónia', 'NC', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(168, 'Nova Zelândia', 'NZ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(169, 'Níger', 'NE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(170, 'Omã', 'OM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(171, 'Palau', 'PW', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(172, 'Panamá', 'PA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(173, 'Papua-Nova Guiné', 'PG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(174, 'Paquistão', 'PK', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(175, 'Paraguai', 'PY', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(176, 'Países Baixos', 'NL', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(177, 'Países Baixos Caribenhos', 'BQ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(178, 'Peru', 'PE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(179, 'Polinésia Francesa', 'PF', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(180, 'Polónia', 'PL', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(181, 'Porto Rico', 'PR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(182, 'Portugal', 'PT', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(183, 'Quirguistão', 'KG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(184, 'Quiribáti', 'KI', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(185, 'Quénia', 'KE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(186, 'Reino Unido', 'GB', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(187, 'República Centro-Africana', 'CF', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(188, 'República Dominicana', 'DO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(189, 'Reunião', 'RE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(190, 'Roménia', 'RO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(191, 'Ruanda', 'RW', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(192, 'Rússia', 'RU', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(193, 'Salvador', 'SV', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(194, 'Samoa', 'WS', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(195, 'Samoa Americana', 'AS', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(196, 'Santa Helena', 'SH', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(197, 'Santa Lúcia', 'LC', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(198, 'Sara Ocidental', 'EH', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(199, 'Seicheles', 'SC', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(200, 'Senegal', 'SN', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(201, 'Serra Leoa', 'SL', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(202, 'Singapura', 'SG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(203, 'Somália', 'SO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(204, 'Sri Lanca', 'LK', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(205, 'Sudão', 'SD', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(206, 'Sudão do Sul', 'SS', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(207, 'Suriname', 'SR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(208, 'Suécia', 'SE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(209, 'Suíça', 'CH', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(210, 'Svalbard e Jan Mayen', 'SJ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(211, 'São Bartolomeu', 'BL', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(212, 'São Cristóvão e Neves', 'KN', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(213, 'São Marinho', 'SM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(214, 'São Martinho (Saint-Martin)', 'MF', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(215, 'São Martinho (Sint Maarten)', 'SX', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(216, 'São Pedro e Miquelão', 'PM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(217, 'São Tomé e Príncipe', 'ST', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(218, 'São Vicente e Granadinas', 'VC', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(219, 'Sérvia', 'RS', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(220, 'Síria', 'SY', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(221, 'Tailândia', 'TH', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(222, 'Taiwan', 'TW', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(223, 'Tajiquistão', 'TJ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(224, 'Tanzânia', 'TZ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(225, 'Território Britânico do Oceano Índico', 'IO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(226, 'Territórios Austrais Franceses', 'TF', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(227, 'Territórios palestinianos', 'PS', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(228, 'Timor-Leste', 'TL', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(229, 'Togo', 'TG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(230, 'Tonga', 'TO', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(231, 'Toquelau', 'TK', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(232, 'Trindade e Tobago', 'TT', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(233, 'Tunísia', 'TN', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(234, 'Turquemenistão', 'TM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(235, 'Turquia', 'TR', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(236, 'Tuvalu', 'TV', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(237, 'Ucrânia', 'UA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(238, 'Uganda', 'UG', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(239, 'Uruguai', 'UY', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(240, 'Usbequistão', 'UZ', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(241, 'Vanuatu', 'VU', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(242, 'Venezuela', 'VE', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(243, 'Vietname', 'VN', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(244, 'Wallis e Futuna', 'WF', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(245, 'Zimbabué', 'ZW', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(246, 'Zâmbia', 'ZM', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(247, 'África do Sul', 'ZA', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(248, 'Áustria', 'AT', NULL, 'ATIVO', '2026-10-08 21:23:35'),
(249, 'Índia', 'IN', NULL, 'ATIVO', '2026-10-08 21:23:35');

-- --------------------------------------------------------

--
-- Estrutura para tabela `patentes`
--

CREATE TABLE `patentes` (
  `id_patente` int(11) NOT NULL,
  `id_ramo` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `sigla` varchar(50) DEFAULT NULL,
  `ordem` int(11) NOT NULL DEFAULT 0,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `patentes`
--

INSERT INTO `patentes` (`id_patente`, `id_ramo`, `nome`, `sigla`, `ordem`, `descricao`, `estado`, `data_criacao`) VALUES
(1, 3, 'TENENTE', 'TTE', 9, NULL, 'ATIVO', '2026-10-10 11:33:53'),
(2, 2, 'CAPITÃO', 'CAP', 8, NULL, 'ATIVO', '2026-10-12 11:16:00'),
(3, 4, 'TENENTE DE FRAGATA', 'TTF', 9, NULL, 'ATIVO', '2026-10-12 11:16:49'),
(4, 1, 'MAJOR', 'MJ', 7, NULL, 'ATIVO', '2026-10-12 11:43:42');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil_modulos`
--

CREATE TABLE `perfil_modulos` (
  `id_perfil` int(11) NOT NULL,
  `modulo` varchar(100) NOT NULL,
  `pode_visualizar` tinyint(1) NOT NULL DEFAULT 0,
  `pode_configurar` tinyint(1) NOT NULL DEFAULT 0,
  `atualizado_em` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `perfil_modulos`
--

INSERT INTO `perfil_modulos` (`id_perfil`, `modulo`, `pode_visualizar`, `pode_configurar`, `atualizado_em`) VALUES
(2, 'AUDITORIA', 1, 0, '2026-10-12 14:19:50'),
(2, 'CONFIGURACOES', 1, 0, '2026-10-12 14:19:50'),
(2, 'DASHBOARD', 1, 0, '2026-10-12 14:19:50'),
(2, 'FEED', 1, 0, '2026-10-12 14:19:50'),
(2, 'NOTIFICACOES', 1, 0, '2026-10-12 14:19:50'),
(2, 'RELATORIOS', 1, 0, '2026-10-12 14:19:50'),
(2, 'RH', 1, 1, '2026-10-12 14:19:50'),
(2, 'RM', 0, 0, '2026-10-12 14:19:50'),
(2, 'UTILIZADORES', 0, 0, '2026-10-12 14:19:50');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil_permissoes`
--

CREATE TABLE `perfil_permissoes` (
  `id_perfil` int(11) NOT NULL,
  `id_permissao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `perfil_permissoes`
--

INSERT INTO `perfil_permissoes` (`id_perfil`, `id_permissao`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37),
(1, 38),
(1, 39),
(1, 40),
(1, 41),
(1, 42),
(1, 43),
(1, 44),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 6),
(2, 10),
(2, 11),
(2, 12),
(2, 14),
(2, 15),
(2, 16),
(2, 18),
(2, 19),
(2, 20),
(2, 33),
(2, 34),
(2, 35),
(2, 36),
(2, 37),
(2, 42),
(3, 1),
(3, 2),
(3, 4),
(3, 10),
(3, 11),
(3, 33),
(4, 1),
(4, 6),
(4, 7),
(4, 8),
(4, 9),
(4, 10),
(4, 11),
(4, 12),
(4, 33),
(4, 34),
(4, 42),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(5, 9),
(5, 10),
(5, 11),
(5, 12),
(5, 14),
(5, 15),
(5, 16),
(5, 17),
(5, 18),
(5, 19),
(5, 20),
(5, 21),
(5, 22),
(5, 23),
(5, 24),
(5, 25),
(5, 26),
(5, 27),
(5, 29),
(5, 30),
(5, 31),
(5, 32),
(5, 33),
(5, 34),
(5, 35),
(5, 36),
(5, 37),
(5, 38),
(5, 39),
(5, 40),
(5, 42),
(6, 1),
(6, 2),
(6, 6),
(6, 14),
(6, 18),
(6, 33),
(7, 1),
(7, 2),
(7, 3),
(7, 4),
(7, 6),
(7, 7),
(7, 8),
(7, 14),
(7, 15),
(7, 16),
(7, 18),
(7, 19),
(7, 20),
(7, 33),
(7, 34),
(8, 1),
(8, 2),
(8, 6),
(8, 14),
(8, 18),
(8, 33),
(8, 34),
(8, 39),
(8, 40),
(8, 42),
(8, 44),
(9, 1),
(9, 2),
(9, 6),
(9, 14),
(9, 18),
(9, 33),
(10, 42);

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfis`
--

CREATE TABLE `perfis` (
  `id_perfil` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `nivel_acesso` enum('GLOBAL','RAMO','REGIAO','UNIDADE','PROPRIO') NOT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `perfis`
--

INSERT INTO `perfis` (`id_perfil`, `nome`, `descricao`, `nivel_acesso`, `estado`, `data_criacao`) VALUES
(1, 'ADMINISTRADOR_GLOBAL', 'Acesso global ao sistema', 'GLOBAL', 'ATIVO', '2026-10-07 14:47:58'),
(2, 'ADMINISTRADOR_RH', 'Administração de recursos humanos no ramo atribuído', 'RAMO', 'ATIVO', '2026-10-07 14:47:58'),
(3, 'OPERADOR_RH', 'Operação de recursos humanos no ramo atribuído', 'RAMO', 'ATIVO', '2026-10-07 14:47:58'),
(4, 'ADMINISTRADOR_RM', 'Administração de recursos materiais no ramo atribuído', 'RAMO', 'ATIVO', '2026-10-07 14:47:58'),
(5, 'ADMINISTRADOR_RAMO', 'Administração integral do ramo atribuído', 'RAMO', 'ATIVO', '2026-10-07 14:47:58'),
(6, 'OPERADOR_REGIAO', 'Operação limitada à região atribuída', 'REGIAO', 'ATIVO', '2026-10-07 14:47:58'),
(7, 'GESTOR_LOCAL', 'Gestão limitada à unidade atribuída', 'UNIDADE', 'ATIVO', '2026-10-07 14:47:58'),
(8, 'PRIVILEGIADO', 'Acesso privilegiado conforme permissões atribuídas', 'RAMO', 'ATIVO', '2026-10-07 14:47:58'),
(9, 'CONSULTOR', 'Consulta conforme âmbito atribuído', 'PROPRIO', 'ATIVO', '2026-10-07 14:47:58'),
(10, 'ADMINISTRADOR_REGIAO', 'Administração no âmbito regional atribuído', 'REGIAO', 'ATIVO', '2026-10-07 20:50:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissoes`
--

CREATE TABLE `permissoes` (
  `id_permissao` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `modulo` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `permissoes`
--

INSERT INTO `permissoes` (`id_permissao`, `nome`, `modulo`, `descricao`, `estado`, `data_criacao`) VALUES
(1, 'DASHBOARD_VISUALIZAR', 'DASHBOARD', 'Dashboard', 'ATIVO', '2026-10-07 14:47:58'),
(2, 'ESPECIALISTAS_VISUALIZAR', 'ESPECIALISTAS', 'Ver especialistas', 'ATIVO', '2026-10-07 14:47:58'),
(3, 'ESPECIALISTAS_CRIAR', 'ESPECIALISTAS', 'Criar especialistas', 'ATIVO', '2026-10-07 14:47:58'),
(4, 'ESPECIALISTAS_EDITAR', 'ESPECIALISTAS', 'Editar especialistas', 'ATIVO', '2026-10-07 14:47:58'),
(5, 'ESPECIALISTAS_ELIMINAR', 'ESPECIALISTAS', 'Eliminar especialistas', 'ATIVO', '2026-10-07 14:47:58'),
(6, 'EQUIPAMENTOS_VISUALIZAR', 'EQUIPAMENTOS', 'Ver equipamentos', 'ATIVO', '2026-10-07 14:47:58'),
(7, 'EQUIPAMENTOS_CRIAR', 'EQUIPAMENTOS', 'Criar equipamentos', 'ATIVO', '2026-10-07 14:47:58'),
(8, 'EQUIPAMENTOS_EDITAR', 'EQUIPAMENTOS', 'Editar equipamentos', 'ATIVO', '2026-10-07 14:47:58'),
(9, 'EQUIPAMENTOS_ELIMINAR', 'EQUIPAMENTOS', 'Eliminar equipamentos', 'ATIVO', '2026-10-07 14:47:58'),
(10, 'UTILIZADORES_VISUALIZAR', 'UTILIZADORES', 'Ver utilizadores', 'ATIVO', '2026-10-07 14:47:58'),
(11, 'UTILIZADORES_CRIAR', 'UTILIZADORES', 'Criar utilizadores', 'ATIVO', '2026-10-07 14:47:58'),
(12, 'UTILIZADORES_EDITAR', 'UTILIZADORES', 'Editar utilizadores', 'ATIVO', '2026-10-07 14:47:58'),
(13, 'UTILIZADORES_ELIMINAR', 'UTILIZADORES', 'Eliminar utilizadores', 'ATIVO', '2026-10-07 14:47:58'),
(14, 'FOTOGRAFIAS_VISUALIZAR', 'FOTOGRAFIAS', 'Ver fotografias', 'ATIVO', '2026-10-07 14:47:58'),
(15, 'FOTOGRAFIAS_CRIAR', 'FOTOGRAFIAS', 'Adicionar fotografias', 'ATIVO', '2026-10-07 14:47:58'),
(16, 'FOTOGRAFIAS_EDITAR', 'FOTOGRAFIAS', 'Editar fotografias', 'ATIVO', '2026-10-07 14:47:58'),
(17, 'FOTOGRAFIAS_ELIMINAR', 'FOTOGRAFIAS', 'Eliminar fotografias', 'ATIVO', '2026-10-07 14:47:58'),
(18, 'DOCUMENTOS_VISUALIZAR', 'DOCUMENTOS', 'Ver documentos', 'ATIVO', '2026-10-07 14:47:58'),
(19, 'DOCUMENTOS_CRIAR', 'DOCUMENTOS', 'Adicionar documentos', 'ATIVO', '2026-10-07 14:47:58'),
(20, 'DOCUMENTOS_EDITAR', 'DOCUMENTOS', 'Editar documentos', 'ATIVO', '2026-10-07 14:47:58'),
(21, 'DOCUMENTOS_ELIMINAR', 'DOCUMENTOS', 'Eliminar documentos', 'ATIVO', '2026-10-07 14:47:58'),
(22, 'SOLICITACOES_VISUALIZAR', 'SOLICITACOES', 'Ver solicitações', 'ATIVO', '2026-10-07 14:47:58'),
(23, 'SOLICITACOES_CRIAR', 'SOLICITACOES', 'Criar solicitações', 'ATIVO', '2026-10-07 14:47:58'),
(24, 'SOLICITACOES_IMPRIMIR', 'SOLICITACOES', 'Imprimir solicitações', 'ATIVO', '2026-10-07 14:47:58'),
(25, 'CONFIGURACOES_VISUALIZAR', 'CONFIGURACOES', 'Ver configurações', 'ATIVO', '2026-10-07 14:47:58'),
(26, 'CONFIGURACOES_GERIR', 'CONFIGURACOES', 'Gerir configurações', 'ATIVO', '2026-10-07 14:47:58'),
(27, 'RAMOS_VISUALIZAR', 'CONFIGURACOES', 'Ver ramos', 'ATIVO', '2026-10-07 14:47:58'),
(28, 'RAMOS_GERIR', 'CONFIGURACOES', 'Gerir ramos', 'ATIVO', '2026-10-07 14:47:58'),
(29, 'UNIDADES_VISUALIZAR', 'CONFIGURACOES', 'Ver unidades', 'ATIVO', '2026-10-07 14:47:58'),
(30, 'UNIDADES_GERIR', 'CONFIGURACOES', 'Gerir unidades', 'ATIVO', '2026-10-07 14:47:58'),
(31, 'ALERTAS_VISUALIZAR', 'ALERTAS', 'Ver alertas', 'ATIVO', '2026-10-07 14:47:58'),
(32, 'ALERTAS_GERIR', 'ALERTAS', 'Gerir alertas', 'ATIVO', '2026-10-07 14:47:58'),
(33, 'RELATORIOS_VISUALIZAR', 'RELATORIOS', 'Ver relatórios', 'ATIVO', '2026-10-07 14:47:58'),
(34, 'RELATORIOS_IMPRIMIR', 'RELATORIOS', 'Imprimir relatórios', 'ATIVO', '2026-10-07 14:47:58'),
(35, 'FERIAS_VISUALIZAR', 'FERIAS', 'Ver férias', 'ATIVO', '2026-10-07 14:47:58'),
(36, 'FERIAS_CRIAR', 'FERIAS', 'Criar férias', 'ATIVO', '2026-10-07 14:47:58'),
(37, 'FERIAS_EDITAR', 'FERIAS', 'Editar férias', 'ATIVO', '2026-10-07 14:47:58'),
(38, 'FERIAS_ELIMINAR', 'FERIAS', 'Eliminar férias', 'ATIVO', '2026-10-07 14:47:58'),
(39, 'CONSULTA_FALECIDOS', 'CONSULTA', 'Consultar falecidos', 'ATIVO', '2026-10-07 14:47:58'),
(40, 'CONSULTA_DOENTES', 'CONSULTA', 'Consultar doentes', 'ATIVO', '2026-10-07 14:47:58'),
(41, 'REGIOES_GERIR', 'CONFIGURACOES', 'Gerir regiões', 'ATIVO', '2026-10-07 20:50:00'),
(42, 'AUDITORIA_VISUALIZAR', 'AUDITORIA', 'Consultar auditoria dentro do âmbito autorizado', 'ATIVO', '2026-10-07 20:50:00'),
(43, 'AUDITORIA_GERIR', 'AUDITORIA', 'Gerir auditoria', 'ATIVO', '2026-10-07 20:50:00'),
(44, 'HISTORICO_VISUALIZAR', 'HISTORICO', 'Consultar histórico', 'ATIVO', '2026-10-07 20:50:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `provincias`
--

CREATE TABLE `provincias` (
  `id_provincia` int(11) NOT NULL,
  `id_pais` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `provincias`
--

INSERT INTO `provincias` (`id_provincia`, `id_pais`, `nome`, `estado`, `data_criacao`) VALUES
(1, 6, 'Bengo', 'ATIVO', '2026-10-08 21:23:35'),
(2, 6, 'Benguela', 'ATIVO', '2026-10-08 21:23:35'),
(3, 6, 'Bié', 'ATIVO', '2026-10-08 21:23:35'),
(4, 6, 'Cabinda', 'ATIVO', '2026-10-08 21:23:35'),
(5, 6, 'Cuando', 'ATIVO', '2026-10-08 21:23:35'),
(6, 6, 'Cuanza Norte', 'ATIVO', '2026-10-08 21:23:35'),
(7, 6, 'Cuanza Sul', 'ATIVO', '2026-10-08 21:23:35'),
(8, 6, 'Cubango', 'ATIVO', '2026-10-08 21:23:35'),
(9, 6, 'Cunene', 'ATIVO', '2026-10-08 21:23:35'),
(10, 6, 'Huambo', 'ATIVO', '2026-10-08 21:23:35'),
(11, 6, 'Huíla', 'ATIVO', '2026-10-08 21:23:35'),
(12, 6, 'Icolo e Bengo', 'ATIVO', '2026-10-08 21:23:35'),
(13, 6, 'Luanda', 'ATIVO', '2026-10-08 21:23:35'),
(14, 6, 'Lunda Norte', 'ATIVO', '2026-10-08 21:23:35'),
(15, 6, 'Lunda Sul', 'ATIVO', '2026-10-08 21:23:35'),
(16, 6, 'Malanje', 'ATIVO', '2026-10-08 21:23:35'),
(17, 6, 'Moxico', 'ATIVO', '2026-10-08 21:23:35'),
(18, 6, 'Moxico Leste', 'ATIVO', '2026-10-08 21:23:35'),
(19, 6, 'Namibe', 'ATIVO', '2026-10-08 21:23:35'),
(20, 6, 'Uíge', 'ATIVO', '2026-10-08 21:23:35'),
(21, 6, 'Zaire', 'ATIVO', '2026-10-08 21:23:35');

-- --------------------------------------------------------

--
-- Estrutura para tabela `quadros_servico`
--

CREATE TABLE `quadros_servico` (
  `id_quadro` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `sigla` varchar(30) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `quadros_servico`
--

INSERT INTO `quadros_servico` (`id_quadro`, `nome`, `sigla`, `descricao`, `estado`, `data_criacao`) VALUES
(1, 'Quadro Permanente', 'QP', NULL, 'ATIVO', '2026-10-07 14:47:58'),
(2, 'Quadro Miliciano', 'QM', NULL, 'ATIVO', '2026-10-07 14:47:58'),
(3, 'Quadro Especial', 'QE', NULL, 'ATIVO', '2026-10-07 14:47:58'),
(4, 'Civil', 'CIV', NULL, 'INATIVO', '2026-10-07 14:47:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ramo`
--

CREATE TABLE `ramo` (
  `id_ramo` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `sigla` varchar(30) NOT NULL,
  `descricao` text DEFAULT NULL,
  `logo` varchar(500) DEFAULT NULL,
  `imagem_fundo` varchar(500) DEFAULT NULL,
  `cor_principal` varchar(20) DEFAULT NULL,
  `ordem` int(11) NOT NULL DEFAULT 99,
  `cor_secundaria` varchar(20) DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `ramo`
--

INSERT INTO `ramo` (`id_ramo`, `nome`, `sigla`, `descricao`, `logo`, `imagem_fundo`, `cor_principal`, `ordem`, `cor_secundaria`, `estado`, `data_criacao`) VALUES
(1, 'Estado Maior', 'EMG', NULL, NULL, NULL, '#E58A22', 1, NULL, 'INATIVO', '2026-10-07 20:19:59'),
(2, 'Exército', 'EXE', NULL, NULL, NULL, '#788B3A', 2, NULL, 'ATIVO', '2026-10-07 20:19:59'),
(3, 'Força Aérea Nacional', 'FAN', NULL, NULL, NULL, '#55AEE0', 3, NULL, 'ATIVO', '2026-10-07 20:19:59'),
(4, 'Marinha de Guerra de Angola', 'MGA', NULL, NULL, NULL, '#FFFFFF', 4, NULL, 'ATIVO', '2026-10-07 20:19:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `regiao_provincias`
--

CREATE TABLE `regiao_provincias` (
  `id_regiao` int(11) NOT NULL,
  `id_provincia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `regioes`
--

CREATE TABLE `regioes` (
  `id_regiao` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `sigla` varchar(30) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `regioes`
--

INSERT INTO `regioes` (`id_regiao`, `nome`, `sigla`, `descricao`, `estado`, `data_criacao`) VALUES
(1, 'Região Norte', 'NORTE', 'Agrupamento inicial editável.', 'ATIVO', '2026-10-07 20:52:52'),
(2, 'Região Centro', 'CENTRO', 'Agrupamento inicial editável.', 'ATIVO', '2026-10-07 20:52:52'),
(3, 'Região Leste', 'LESTE', 'Agrupamento inicial editável.', 'ATIVO', '2026-10-07 20:52:52'),
(4, 'Região Sul', 'SUL', 'Agrupamento inicial editável.', 'ATIVO', '2026-10-07 20:52:52');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sexos`
--

CREATE TABLE `sexos` (
  `id_sexo` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `sexos`
--

INSERT INTO `sexos` (`id_sexo`, `nome`, `estado`, `data_criacao`) VALUES
(1, 'Masculino', 'ATIVO', '2026-10-07 14:47:58'),
(2, 'Feminino', 'ATIVO', '2026-10-07 14:47:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `situacoes_servico`
--

CREATE TABLE `situacoes_servico` (
  `id_situacao_servico` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `grupo` enum('ATIVO','INATIVO') NOT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') DEFAULT 'ATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `situacoes_servico`
--

INSERT INTO `situacoes_servico` (`id_situacao_servico`, `nome`, `grupo`, `descricao`, `estado`) VALUES
(1, 'ATIVO', 'ATIVO', 'Em efetividade de serviço', 'ATIVO'),
(2, 'FALECIDO', 'INATIVO', 'Especialista falecido', 'ATIVO'),
(3, 'REFORMADO', 'INATIVO', 'Reformado', 'ATIVO'),
(4, 'LICENÇA', 'INATIVO', 'Em licença', 'ATIVO'),
(5, 'DOENTE', 'INATIVO', 'Estado funcional para consulta e acompanhamento.', 'ATIVO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacoes`
--

CREATE TABLE `solicitacoes` (
  `id_solicitacao` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `id_modelo` int(11) NOT NULL,
  `id_utilizador` int(11) NOT NULL,
  `id_ramo` int(11) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `data_solicitacao` datetime DEFAULT current_timestamp(),
  `estado` enum('RASCUNHO','PENDENTE','EMITIDA','CANCELADA') DEFAULT 'RASCUNHO',
  `conteudo_gerado` longtext DEFAULT NULL,
  `observacoes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_alerta`
--

CREATE TABLE `tipos_alerta` (
  `id_tipo_alerta` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `nivel_padrao` enum('INFO','ATENCAO','URGENTE') DEFAULT 'INFO',
  `dias_antecedencia` int(11) DEFAULT 0,
  `estado` enum('ATIVO','INATIVO') DEFAULT 'ATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tipos_alerta`
--

INSERT INTO `tipos_alerta` (`id_tipo_alerta`, `nome`, `codigo`, `descricao`, `nivel_padrao`, `dias_antecedencia`, `estado`) VALUES
(1, 'Documento expirado', 'DOCUMENTO_EXPIRADO', NULL, 'URGENTE', 0, 'ATIVO'),
(2, 'Documento a expirar', 'DOCUMENTO_A_EXPIRAR', NULL, 'ATENCAO', 30, 'ATIVO'),
(3, 'BI expirado', 'BI_EXPIRADO', NULL, 'URGENTE', 0, 'ATIVO'),
(4, 'BI a expirar', 'BI_A_EXPIRAR', NULL, 'ATENCAO', 60, 'ATIVO'),
(5, 'Férias próximas', 'FERIAS_PROXIMAS', NULL, 'INFO', 15, 'ATIVO'),
(6, 'Férias em curso', 'FERIAS_EM_CURSO', NULL, 'INFO', 0, 'ATIVO'),
(7, 'Formação a terminar', 'FORMACAO_A_EXPIRAR', NULL, 'ATENCAO', 30, 'ATIVO'),
(8, 'Fim de função', 'FIM_FUNCAO', NULL, 'ATENCAO', 30, 'ATIVO'),
(9, 'Manutenção de equipamento', 'MANUTENCAO_EQUIPAMENTO', NULL, 'ATENCAO', 0, 'ATIVO'),
(10, 'Inspeção de equipamento', 'INSPECAO_EQUIPAMENTO', NULL, 'ATENCAO', 0, 'ATIVO');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_condecoracao`
--

CREATE TABLE `tipos_condecoracao` (
  `id_tipo_condecoracao` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_documento`
--

CREATE TABLE `tipos_documento` (
  `id_tipo_documento` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tipos_documento`
--

INSERT INTO `tipos_documento` (`id_tipo_documento`, `nome`, `descricao`, `estado`, `data_criacao`) VALUES
(1, 'BI', NULL, 'ATIVO', '2026-10-07 14:47:58'),
(2, 'Certificado', NULL, 'ATIVO', '2026-10-07 14:47:58'),
(3, 'Diploma', NULL, 'ATIVO', '2026-10-07 14:47:58'),
(4, 'Despacho', NULL, 'ATIVO', '2026-10-07 14:47:58'),
(5, 'Declaração', NULL, 'ATIVO', '2026-10-07 14:47:58'),
(6, 'Contrato', NULL, 'ATIVO', '2026-10-07 14:47:58'),
(7, 'Relatório', NULL, 'ATIVO', '2026-10-07 14:47:58');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_equipamento`
--

CREATE TABLE `tipos_equipamento` (
  `id_tipo_equipamento` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_formacao`
--

CREATE TABLE `tipos_formacao` (
  `id_tipo_formacao` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `categoria` enum('MILITAR','GERAL') NOT NULL DEFAULT 'GERAL',
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tipos_formacao`
--

INSERT INTO `tipos_formacao` (`id_tipo_formacao`, `nome`, `categoria`, `descricao`, `estado`, `data_criacao`) VALUES
(2, 'CPO', 'MILITAR', '', 'ATIVO', '2026-10-12 12:21:54');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipos_licenca`
--

CREATE TABLE `tipos_licenca` (
  `id_tipo_licenca` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `codigo` varchar(80) NOT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `tipos_licenca`
--

INSERT INTO `tipos_licenca` (`id_tipo_licenca`, `nome`, `codigo`, `descricao`, `estado`, `data_criacao`) VALUES
(1, 'Licença de Férias', 'FERIAS', 'Licença de férias', 'ATIVO', '2026-09-27 23:46:38'),
(2, 'Licença de Formação', 'FORMACAO', 'Licença de formação', 'ATIVO', '2026-09-27 23:46:38'),
(3, 'Licença por Motivo de Saúde', 'SAUDE', 'Licença por motivo de saúde', 'ATIVO', '2026-09-27 23:46:38'),
(4, 'Licença Especial', 'ESPECIAL', 'Licença especial', 'ATIVO', '2026-09-27 23:46:38'),
(5, 'Outra Licença', 'OUTRA', 'Outro tipo de licença', 'ATIVO', '2026-09-27 23:46:38'),
(6, 'Outras licenças', 'OUTRAS', 'Outras licenças', 'ATIVO', '2026-10-07 20:52:52');

-- --------------------------------------------------------

--
-- Estrutura para tabela `unidades`
--

CREATE TABLE `unidades` (
  `id_unidade` int(11) NOT NULL,
  `id_ramo` int(11) NOT NULL,
  `id_regiao` int(11) DEFAULT NULL,
  `nome` varchar(200) NOT NULL,
  `sigla` varchar(50) DEFAULT NULL,
  `localizacao` varchar(200) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id_utilizador` int(11) NOT NULL,
  `nome_utilizador` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `id_perfil` int(11) NOT NULL,
  `id_especialista` int(11) DEFAULT NULL,
  `id_regiao_responsabilidade` int(11) DEFAULT NULL,
  `tipo_responsabilidade` enum('REGIAO','RAMO','UNIDADE','ESPECIALISTA') DEFAULT NULL,
  `responsabilidade_tipo` enum('RAMO','REGIAO','UNIDADE','ESPECIALISTA') DEFAULT NULL,
  `responsabilidade_id` int(11) DEFAULT NULL,
  `foto` varchar(500) DEFAULT NULL,
  `tema` enum('CLARO','ESCURO') NOT NULL DEFAULT 'CLARO',
  `estado` enum('ATIVO','INATIVO') NOT NULL DEFAULT 'ATIVO',
  `data_criacao` datetime NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `ultimo_acesso` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id_utilizador`, `nome_utilizador`, `senha`, `email`, `id_perfil`, `id_especialista`, `id_regiao_responsabilidade`, `tipo_responsabilidade`, `responsabilidade_tipo`, `responsabilidade_id`, `foto`, `tema`, `estado`, `data_criacao`, `data_atualizacao`, `ultimo_acesso`) VALUES
(1, 'admin', '$2y$12$zT61wl/yCPKNQfdyLASxTO5Q6/opvKHH8a7Yu4EeWU.b7W/Cy7Ay.', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'CLARO', 'ATIVO', '2026-10-07 15:30:28', '2026-10-14 18:16:57', '2026-10-14 18:16:51'),
(2, 'adminfan', '$2y$10$5ke9gvryFhVX2dFk3kbCxeqV/eaViQZsWFhsgGNndYERKbbuqY55u', NULL, 5, NULL, NULL, NULL, NULL, NULL, 'uploads/utilizadores/utilizador_2_16c86af299.png', 'CLARO', 'ATIVO', '2026-10-08 16:58:54', '2026-10-12 13:44:01', '2026-10-12 13:44:01'),
(3, 'adminemg', '$2y$10$HmSau5DQmVwrLRCvngNZYOI8B66RLHhamAWdPIovLRs9J.VOMRQxu', NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, 'CLARO', 'ATIVO', '2026-10-10 11:28:35', '2026-10-10 11:29:07', '2026-10-10 11:29:07'),
(4, 'adminrh', '$2y$10$YmWQPpvoBFeajTzsuklpduMQibnFSFUibbJ0nyx9sdtfM51ZRpBSe', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, 'CLARO', 'ATIVO', '2026-10-10 11:31:09', '2026-10-12 14:16:42', '2026-10-12 14:14:40');

-- --------------------------------------------------------

--
-- Estrutura para tabela `utilizador_ramo`
--

CREATE TABLE `utilizador_ramo` (
  `id_utilizador` int(11) NOT NULL,
  `id_ramo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `utilizador_ramo`
--

INSERT INTO `utilizador_ramo` (`id_utilizador`, `id_ramo`) VALUES
(2, 3),
(3, 2),
(4, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `utilizador_regiao`
--

CREATE TABLE `utilizador_regiao` (
  `id_utilizador` int(11) NOT NULL,
  `id_regiao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `utilizador_unidade`
--

CREATE TABLE `utilizador_unidade` (
  `id_utilizador` int(11) NOT NULL,
  `id_unidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vestuario`
--

CREATE TABLE `vestuario` (
  `id_vestuario` int(11) NOT NULL,
  `id_especialista` int(11) NOT NULL,
  `numero_calca` varchar(30) DEFAULT NULL,
  `numero_camisa` varchar(30) DEFAULT NULL,
  `numero_sapato` varchar(30) DEFAULT NULL,
  `numero_bota` varchar(30) DEFAULT NULL,
  `numero_boina` varchar(30) DEFAULT NULL,
  `numero_quiko` varchar(30) DEFAULT NULL,
  `numero_casaco` varchar(30) DEFAULT NULL,
  `outros` varchar(255) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `data_atualizacao` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `vestuario`
--

INSERT INTO `vestuario` (`id_vestuario`, `id_especialista`, `numero_calca`, `numero_camisa`, `numero_sapato`, `numero_bota`, `numero_boina`, `numero_quiko`, `numero_casaco`, `outros`, `observacoes`, `data_atualizacao`) VALUES
(1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-28 09:01:24'),
(5, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-28 09:21:40'),
(13, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-10 08:26:44'),
(17, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-12 11:14:26'),
(19, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-12 11:34:43'),
(20, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-12 11:42:27');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `vw_especialistas_dashboard`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `vw_especialistas_dashboard` (
`id_especialista` int(11)
,`id_ramo` int(11)
,`id_quadro` int(11)
,`id_patente` int(11)
,`estado_cadastro` enum('ATIVO','INATIVO')
,`nome_completo` varchar(200)
,`sexo` varchar(50)
,`nip` varchar(50)
,`id_unidade` int(11)
,`id_regiao` int(11)
,`quadro` varchar(150)
,`patente` varchar(150)
,`ordem_patente` int(11)
,`situacao_servico` varchar(150)
,`grupo_situacao` enum('ATIVO','INATIVO')
,`ramo_nome` varchar(150)
,`ramo_sigla` varchar(30)
,`ramo_cor` varchar(20)
,`regiao_nome` varchar(150)
,`numero_bi` varchar(50)
,`data_validade_bi` date
);

-- --------------------------------------------------------

--
-- Estrutura para view `vw_especialistas_dashboard`
--
DROP TABLE IF EXISTS `vw_especialistas_dashboard`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_especialistas_dashboard`  AS SELECT `e`.`id_especialista` AS `id_especialista`, `e`.`id_ramo` AS `id_ramo`, `e`.`id_quadro` AS `id_quadro`, `e`.`id_patente` AS `id_patente`, `e`.`estado` AS `estado_cadastro`, `dp`.`nome_completo` AS `nome_completo`, `sx`.`nome` AS `sexo`, `ds`.`nip` AS `nip`, `ds`.`id_unidade` AS `id_unidade`, `ds`.`id_regiao` AS `id_regiao`, `q`.`nome` AS `quadro`, `p`.`nome` AS `patente`, `p`.`ordem` AS `ordem_patente`, `ss`.`nome` AS `situacao_servico`, `ss`.`grupo` AS `grupo_situacao`, `r`.`nome` AS `ramo_nome`, `r`.`sigla` AS `ramo_sigla`, `r`.`cor_principal` AS `ramo_cor`, `rg`.`nome` AS `regiao_nome`, `dc`.`numero_bi` AS `numero_bi`, `dc`.`data_validade_bi` AS `data_validade_bi` FROM (((((((((`especialistas` `e` left join `dados_pessoais` `dp` on(`dp`.`id_especialista` = `e`.`id_especialista`)) left join `dados_pessoais` `dc` on(`dc`.`id_especialista` = `e`.`id_especialista`)) left join `quadros_servico` `q` on(`q`.`id_quadro` = `e`.`id_quadro`)) left join `patentes` `p` on(`p`.`id_patente` = `e`.`id_patente`)) left join `sexos` `sx` on(`sx`.`id_sexo` = `dp`.`id_sexo`)) left join `dados_servico` `ds` on(`ds`.`id_especialista` = `e`.`id_especialista`)) left join `situacoes_servico` `ss` on(`ss`.`id_situacao_servico` = `ds`.`id_situacao_servico`)) left join `ramo` `r` on(`r`.`id_ramo` = `e`.`id_ramo`)) left join `regioes` `rg` on(`rg`.`id_regiao` = `ds`.`id_regiao`)) ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id_auditoria`),
  ADD KEY `idx_aud_data` (`data_evento`),
  ADD KEY `idx_aud_user` (`id_utilizador`),
  ADD KEY `idx_aud_categoria` (`categoria`),
  ADD KEY `idx_aud_evento` (`evento`),
  ADD KEY `idx_aud_modulo` (`modulo`);

--
-- Índices de tabela `bancos`
--
ALTER TABLE `bancos`
  ADD PRIMARY KEY (`id_banco`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `cargos`
--
ALTER TABLE `cargos`
  ADD PRIMARY KEY (`id_cargo`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `condecoracoes`
--
ALTER TABLE `condecoracoes`
  ADD PRIMARY KEY (`id_condecoracao`),
  ADD KEY `id_especialista` (`id_especialista`),
  ADD KEY `id_tipo_condecoracao` (`id_tipo_condecoracao`);

--
-- Índices de tabela `configuracoes_alertas`
--
ALTER TABLE `configuracoes_alertas`
  ADD PRIMARY KEY (`id_configuracao_alerta`),
  ADD UNIQUE KEY `id_tipo_alerta` (`id_tipo_alerta`,`id_ramo`),
  ADD KEY `id_ramo` (`id_ramo`);

--
-- Índices de tabela `cursos_habilitacao`
--
ALTER TABLE `cursos_habilitacao`
  ADD PRIMARY KEY (`id_curso`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `dados_bancarios`
--
ALTER TABLE `dados_bancarios`
  ADD PRIMARY KEY (`id_dados_bancarios`),
  ADD UNIQUE KEY `id_especialista` (`id_especialista`),
  ADD KEY `idx_db_banco` (`id_banco`);

--
-- Índices de tabela `dados_pessoais`
--
ALTER TABLE `dados_pessoais`
  ADD PRIMARY KEY (`id_dados_pessoais`),
  ADD UNIQUE KEY `id_especialista` (`id_especialista`),
  ADD UNIQUE KEY `numero_bi` (`numero_bi`),
  ADD KEY `id_sexo` (`id_sexo`),
  ADD KEY `id_pais_nascimento` (`id_pais_nascimento`),
  ADD KEY `id_provincia_nascimento` (`id_provincia_nascimento`),
  ADD KEY `id_municipio_nascimento` (`id_municipio_nascimento`),
  ADD KEY `id_pais_nacionalidade` (`id_pais_nacionalidade`),
  ADD KEY `id_estado_civil` (`id_estado_civil`),
  ADD KEY `nome_completo` (`nome_completo`);

--
-- Índices de tabela `dados_residencia`
--
ALTER TABLE `dados_residencia`
  ADD PRIMARY KEY (`id_residencia`),
  ADD UNIQUE KEY `id_especialista` (`id_especialista`),
  ADD KEY `id_provincia` (`id_provincia`),
  ADD KEY `id_municipio` (`id_municipio`);

--
-- Índices de tabela `dados_servico`
--
ALTER TABLE `dados_servico`
  ADD PRIMARY KEY (`id_dados_servico`),
  ADD UNIQUE KEY `id_especialista` (`id_especialista`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `idx_dados_servico_unidade` (`id_unidade`),
  ADD KEY `idx_dados_servico_departamento` (`id_departamento`),
  ADD KEY `idx_dados_servico_funcao` (`id_funcao`),
  ADD KEY `idx_dados_servico_situacao` (`id_situacao_servico`),
  ADD KEY `fk_ds_regiao` (`id_regiao`);

--
-- Índices de tabela `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id_departamento`),
  ADD UNIQUE KEY `id_unidade` (`id_unidade`,`nome`),
  ADD KEY `idx_departamentos_unidade` (`id_unidade`);

--
-- Índices de tabela `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_especialista` (`id_especialista`),
  ADD KEY `id_equipamento` (`id_equipamento`),
  ADD KEY `id_tipo_documento` (`id_tipo_documento`),
  ADD KEY `data_validade` (`data_validade`);

--
-- Índices de tabela `equipamentos`
--
ALTER TABLE `equipamentos`
  ADD PRIMARY KEY (`id_equipamento`),
  ADD UNIQUE KEY `numero_serie` (`numero_serie`),
  ADD KEY `id_unidade` (`id_unidade`),
  ADD KEY `id_tipo_equipamento` (`id_tipo_equipamento`),
  ADD KEY `id_estado_equipamento` (`id_estado_equipamento`),
  ADD KEY `id_ramo` (`id_ramo`,`id_unidade`),
  ADD KEY `fk_eq_regiao` (`id_regiao`),
  ADD KEY `fk_eq_responsavel` (`id_especialista_responsavel`);

--
-- Índices de tabela `especialistas`
--
ALTER TABLE `especialistas`
  ADD PRIMARY KEY (`id_especialista`),
  ADD KEY `id_ramo` (`id_ramo`,`id_patente`),
  ADD KEY `idx_especialistas_ramo` (`id_ramo`),
  ADD KEY `idx_especialistas_quadro` (`id_quadro`),
  ADD KEY `idx_especialistas_patente` (`id_patente`),
  ADD KEY `idx_especialistas_estado` (`estado`);

--
-- Índices de tabela `estados_civis`
--
ALTER TABLE `estados_civis`
  ADD PRIMARY KEY (`id_estado_civil`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `estados_equipamento`
--
ALTER TABLE `estados_equipamento`
  ADD PRIMARY KEY (`id_estado_equipamento`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `familiares`
--
ALTER TABLE `familiares`
  ADD PRIMARY KEY (`id_familiar`),
  ADD KEY `id_especialista` (`id_especialista`),
  ADD KEY `id_grau_parentesco` (`id_grau_parentesco`);

--
-- Índices de tabela `feed_anexos`
--
ALTER TABLE `feed_anexos`
  ADD PRIMARY KEY (`id_anexo`),
  ADD KEY `idx_feed_anexos_publicacao` (`id_publicacao`);

--
-- Índices de tabela `feed_avaliacoes`
--
ALTER TABLE `feed_avaliacoes`
  ADD PRIMARY KEY (`id_publicacao`,`id_utilizador`),
  ADD KEY `fk_feed_avaliacoes_utilizador` (`id_utilizador`);

--
-- Índices de tabela `feed_candidaturas`
--
ALTER TABLE `feed_candidaturas`
  ADD PRIMARY KEY (`id_candidatura`),
  ADD UNIQUE KEY `uq_feed_candidatura` (`id_publicacao`,`id_especialista`),
  ADD KEY `idx_feed_candidatura_especialista` (`id_especialista`),
  ADD KEY `fk_feed_candidatura_autor` (`submetido_por`);

--
-- Índices de tabela `feed_comentarios`
--
ALTER TABLE `feed_comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `idx_feed_comentarios_publicacao` (`id_publicacao`),
  ADD KEY `idx_feed_comentarios_utilizador` (`id_utilizador`);

--
-- Índices de tabela `feed_leituras`
--
ALTER TABLE `feed_leituras`
  ADD PRIMARY KEY (`id_publicacao`,`id_utilizador`),
  ADD KEY `idx_feed_leitura_utilizador` (`id_utilizador`);

--
-- Índices de tabela `feed_oportunidade_config`
--
ALTER TABLE `feed_oportunidade_config`
  ADD PRIMARY KEY (`id_publicacao`),
  ADD KEY `fk_feed_opp_config_ramo` (`id_ramo_criterio`),
  ADD KEY `fk_feed_opp_config_quadro` (`id_quadro_criterio`),
  ADD KEY `fk_feed_opp_config_patente` (`id_patente_criterio`),
  ADD KEY `fk_feed_opp_config_nivel` (`id_nivel_habilitacao`),
  ADD KEY `fk_feed_opp_config_utilizador` (`atualizado_por`);

--
-- Índices de tabela `feed_permissoes_perfil`
--
ALTER TABLE `feed_permissoes_perfil`
  ADD PRIMARY KEY (`id_perfil`);

--
-- Índices de tabela `feed_publicacoes`
--
ALTER TABLE `feed_publicacoes`
  ADD PRIMARY KEY (`id_publicacao`),
  ADD KEY `idx_feed_area_estado` (`area`,`estado`),
  ADD KEY `idx_feed_ramo` (`id_ramo`),
  ADD KEY `idx_feed_data` (`criado_em`),
  ADD KEY `fk_feed_autor` (`id_autor`);

--
-- Índices de tabela `feed_reacoes`
--
ALTER TABLE `feed_reacoes`
  ADD PRIMARY KEY (`id_publicacao`,`id_utilizador`),
  ADD KEY `idx_feed_reacao_utilizador` (`id_utilizador`);

--
-- Índices de tabela `ferias`
--
ALTER TABLE `ferias`
  ADD PRIMARY KEY (`id_ferias`),
  ADD KEY `id_especialista` (`id_especialista`),
  ADD KEY `data_inicio` (`data_inicio`,`data_fim`,`estado`);

--
-- Índices de tabela `formacoes`
--
ALTER TABLE `formacoes`
  ADD PRIMARY KEY (`id_formacao`),
  ADD KEY `id_especialista` (`id_especialista`),
  ADD KEY `id_tipo_formacao` (`id_tipo_formacao`),
  ADD KEY `id_instituicao` (`id_instituicao`),
  ADD KEY `id_pais` (`id_pais`),
  ADD KEY `idx_formacoes_provincia` (`id_provincia`);

--
-- Índices de tabela `fotografias`
--
ALTER TABLE `fotografias`
  ADD PRIMARY KEY (`id_fotografia`),
  ADD KEY `idx_fotografias_especialista` (`id_especialista`),
  ADD KEY `idx_fotografias_equipamento` (`id_equipamento`);

--
-- Índices de tabela `funcoes`
--
ALTER TABLE `funcoes`
  ADD PRIMARY KEY (`id_funcao`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `graus_parentesco`
--
ALTER TABLE `graus_parentesco`
  ADD PRIMARY KEY (`id_grau_parentesco`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `grupos_sanguineos`
--
ALTER TABLE `grupos_sanguineos`
  ADD PRIMARY KEY (`id_grupo_sanguineo`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `habilitacoes_literarias`
--
ALTER TABLE `habilitacoes_literarias`
  ADD PRIMARY KEY (`id_habilitacao`),
  ADD KEY `id_especialista` (`id_especialista`),
  ADD KEY `id_nivel_habilitacao` (`id_nivel_habilitacao`),
  ADD KEY `id_instituicao` (`id_instituicao`),
  ADD KEY `id_pais` (`id_pais`);

--
-- Índices de tabela `historico_configuracoes`
--
ALTER TABLE `historico_configuracoes`
  ADD PRIMARY KEY (`id_historico`),
  ADD KEY `id_utilizador` (`id_utilizador`);

--
-- Índices de tabela `historico_equipamentos`
--
ALTER TABLE `historico_equipamentos`
  ADD PRIMARY KEY (`id_historico`),
  ADD KEY `id_utilizador` (`id_utilizador`),
  ADD KEY `idx_heequip` (`id_equipamento`),
  ADD KEY `idx_heeq_data` (`data_registo`);

--
-- Índices de tabela `historico_especialistas`
--
ALTER TABLE `historico_especialistas`
  ADD PRIMARY KEY (`id_historico`),
  ADD KEY `id_utilizador` (`id_utilizador`),
  ADD KEY `idx_he_especialista` (`id_especialista`),
  ADD KEY `idx_he_tipo` (`tipo_evento`),
  ADD KEY `idx_he_data` (`data_registo`);

--
-- Índices de tabela `instituicoes`
--
ALTER TABLE `instituicoes`
  ADD PRIMARY KEY (`id_instituicao`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `idx_instituicoes_pais` (`id_pais`);

--
-- Índices de tabela `licencas`
--
ALTER TABLE `licencas`
  ADD PRIMARY KEY (`id_licenca`),
  ADD KEY `id_tipo_licenca` (`id_tipo_licenca`),
  ADD KEY `idx_licencas_especialista` (`id_especialista`),
  ADD KEY `idx_licencas_periodo` (`data_inicio`,`data_fim`,`estado`);

--
-- Índices de tabela `modelos_relatorio`
--
ALTER TABLE `modelos_relatorio`
  ADD PRIMARY KEY (`id_modelo_relatorio`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `modelos_solicitacao`
--
ALTER TABLE `modelos_solicitacao`
  ADD PRIMARY KEY (`id_modelo`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Índices de tabela `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id_municipio`),
  ADD UNIQUE KEY `id_provincia` (`id_provincia`,`nome`);

--
-- Índices de tabela `niveis_habilitacao`
--
ALTER TABLE `niveis_habilitacao`
  ADD PRIMARY KEY (`id_nivel_habilitacao`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `nomes_formacao`
--
ALTER TABLE `nomes_formacao`
  ADD PRIMARY KEY (`id_nome_formacao`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `idx_nomes_formacao_categoria` (`categoria`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id_notificacao`),
  ADD KEY `id_ramo` (`id_ramo`),
  ADD KEY `id_especialista` (`id_especialista`),
  ADD KEY `id_equipamento` (`id_equipamento`),
  ADD KEY `id_tipo_alerta` (`id_tipo_alerta`),
  ADD KEY `id_utilizador` (`id_utilizador`,`lida`,`data_criacao`);

--
-- Índices de tabela `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`id_pais`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Índices de tabela `patentes`
--
ALTER TABLE `patentes`
  ADD PRIMARY KEY (`id_patente`),
  ADD UNIQUE KEY `id_ramo` (`id_ramo`,`nome`),
  ADD UNIQUE KEY `uq_patente_ramo_id` (`id_ramo`,`id_patente`),
  ADD KEY `idx_patentes_ramo_ordem` (`id_ramo`,`ordem`);

--
-- Índices de tabela `perfil_modulos`
--
ALTER TABLE `perfil_modulos`
  ADD PRIMARY KEY (`id_perfil`,`modulo`);

--
-- Índices de tabela `perfil_permissoes`
--
ALTER TABLE `perfil_permissoes`
  ADD PRIMARY KEY (`id_perfil`,`id_permissao`),
  ADD KEY `id_permissao` (`id_permissao`);

--
-- Índices de tabela `perfis`
--
ALTER TABLE `perfis`
  ADD PRIMARY KEY (`id_perfil`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `permissoes`
--
ALTER TABLE `permissoes`
  ADD PRIMARY KEY (`id_permissao`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`id_provincia`),
  ADD UNIQUE KEY `id_pais` (`id_pais`,`nome`);

--
-- Índices de tabela `quadros_servico`
--
ALTER TABLE `quadros_servico`
  ADD PRIMARY KEY (`id_quadro`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `ramo`
--
ALTER TABLE `ramo`
  ADD PRIMARY KEY (`id_ramo`),
  ADD UNIQUE KEY `sigla` (`sigla`),
  ADD KEY `idx_ramo_estado` (`estado`);

--
-- Índices de tabela `regiao_provincias`
--
ALTER TABLE `regiao_provincias`
  ADD PRIMARY KEY (`id_regiao`,`id_provincia`),
  ADD KEY `id_provincia` (`id_provincia`);

--
-- Índices de tabela `regioes`
--
ALTER TABLE `regioes`
  ADD PRIMARY KEY (`id_regiao`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD UNIQUE KEY `sigla` (`sigla`);

--
-- Índices de tabela `sexos`
--
ALTER TABLE `sexos`
  ADD PRIMARY KEY (`id_sexo`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `situacoes_servico`
--
ALTER TABLE `situacoes_servico`
  ADD PRIMARY KEY (`id_situacao_servico`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  ADD PRIMARY KEY (`id_solicitacao`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `id_especialista` (`id_especialista`),
  ADD KEY `id_modelo` (`id_modelo`),
  ADD KEY `id_utilizador` (`id_utilizador`),
  ADD KEY `id_ramo` (`id_ramo`);

--
-- Índices de tabela `tipos_alerta`
--
ALTER TABLE `tipos_alerta`
  ADD PRIMARY KEY (`id_tipo_alerta`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Índices de tabela `tipos_condecoracao`
--
ALTER TABLE `tipos_condecoracao`
  ADD PRIMARY KEY (`id_tipo_condecoracao`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `tipos_documento`
--
ALTER TABLE `tipos_documento`
  ADD PRIMARY KEY (`id_tipo_documento`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `tipos_equipamento`
--
ALTER TABLE `tipos_equipamento`
  ADD PRIMARY KEY (`id_tipo_equipamento`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `tipos_formacao`
--
ALTER TABLE `tipos_formacao`
  ADD PRIMARY KEY (`id_tipo_formacao`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `tipos_licenca`
--
ALTER TABLE `tipos_licenca`
  ADD PRIMARY KEY (`id_tipo_licenca`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Índices de tabela `unidades`
--
ALTER TABLE `unidades`
  ADD PRIMARY KEY (`id_unidade`),
  ADD UNIQUE KEY `id_ramo` (`id_ramo`,`nome`),
  ADD KEY `idx_unidades_ramo` (`id_ramo`),
  ADD KEY `fk_unidade_regiao` (`id_regiao`);

--
-- Índices de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id_utilizador`),
  ADD UNIQUE KEY `nome_utilizador` (`nome_utilizador`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `uq_utilizador_especialista` (`id_especialista`),
  ADD KEY `idx_utilizadores_perfil` (`id_perfil`),
  ADD KEY `idx_utilizadores_especialista` (`id_especialista`),
  ADD KEY `idx_utilizadores_responsabilidade` (`responsabilidade_tipo`,`responsabilidade_id`),
  ADD KEY `idx_utilizadores_estado` (`estado`),
  ADD KEY `fk_user_regiao_resp` (`id_regiao_responsabilidade`);

--
-- Índices de tabela `utilizador_ramo`
--
ALTER TABLE `utilizador_ramo`
  ADD PRIMARY KEY (`id_utilizador`,`id_ramo`),
  ADD KEY `id_ramo` (`id_ramo`);

--
-- Índices de tabela `utilizador_regiao`
--
ALTER TABLE `utilizador_regiao`
  ADD PRIMARY KEY (`id_utilizador`,`id_regiao`),
  ADD KEY `id_regiao` (`id_regiao`);

--
-- Índices de tabela `utilizador_unidade`
--
ALTER TABLE `utilizador_unidade`
  ADD PRIMARY KEY (`id_utilizador`,`id_unidade`),
  ADD KEY `id_unidade` (`id_unidade`);

--
-- Índices de tabela `vestuario`
--
ALTER TABLE `vestuario`
  ADD PRIMARY KEY (`id_vestuario`),
  ADD UNIQUE KEY `id_especialista` (`id_especialista`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id_auditoria` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT de tabela `bancos`
--
ALTER TABLE `bancos`
  MODIFY `id_banco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `cargos`
--
ALTER TABLE `cargos`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `condecoracoes`
--
ALTER TABLE `condecoracoes`
  MODIFY `id_condecoracao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `configuracoes_alertas`
--
ALTER TABLE `configuracoes_alertas`
  MODIFY `id_configuracao_alerta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cursos_habilitacao`
--
ALTER TABLE `cursos_habilitacao`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `dados_bancarios`
--
ALTER TABLE `dados_bancarios`
  MODIFY `id_dados_bancarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `dados_pessoais`
--
ALTER TABLE `dados_pessoais`
  MODIFY `id_dados_pessoais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `dados_residencia`
--
ALTER TABLE `dados_residencia`
  MODIFY `id_residencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `dados_servico`
--
ALTER TABLE `dados_servico`
  MODIFY `id_dados_servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `equipamentos`
--
ALTER TABLE `equipamentos`
  MODIFY `id_equipamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `especialistas`
--
ALTER TABLE `especialistas`
  MODIFY `id_especialista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `estados_civis`
--
ALTER TABLE `estados_civis`
  MODIFY `id_estado_civil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `estados_equipamento`
--
ALTER TABLE `estados_equipamento`
  MODIFY `id_estado_equipamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `familiares`
--
ALTER TABLE `familiares`
  MODIFY `id_familiar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `feed_anexos`
--
ALTER TABLE `feed_anexos`
  MODIFY `id_anexo` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `feed_candidaturas`
--
ALTER TABLE `feed_candidaturas`
  MODIFY `id_candidatura` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `feed_comentarios`
--
ALTER TABLE `feed_comentarios`
  MODIFY `id_comentario` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `feed_publicacoes`
--
ALTER TABLE `feed_publicacoes`
  MODIFY `id_publicacao` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `ferias`
--
ALTER TABLE `ferias`
  MODIFY `id_ferias` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `formacoes`
--
ALTER TABLE `formacoes`
  MODIFY `id_formacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fotografias`
--
ALTER TABLE `fotografias`
  MODIFY `id_fotografia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcoes`
--
ALTER TABLE `funcoes`
  MODIFY `id_funcao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `graus_parentesco`
--
ALTER TABLE `graus_parentesco`
  MODIFY `id_grau_parentesco` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `grupos_sanguineos`
--
ALTER TABLE `grupos_sanguineos`
  MODIFY `id_grupo_sanguineo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `habilitacoes_literarias`
--
ALTER TABLE `habilitacoes_literarias`
  MODIFY `id_habilitacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_configuracoes`
--
ALTER TABLE `historico_configuracoes`
  MODIFY `id_historico` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `historico_equipamentos`
--
ALTER TABLE `historico_equipamentos`
  MODIFY `id_historico` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_especialistas`
--
ALTER TABLE `historico_especialistas`
  MODIFY `id_historico` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `instituicoes`
--
ALTER TABLE `instituicoes`
  MODIFY `id_instituicao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `licencas`
--
ALTER TABLE `licencas`
  MODIFY `id_licenca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `modelos_relatorio`
--
ALTER TABLE `modelos_relatorio`
  MODIFY `id_modelo_relatorio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `modelos_solicitacao`
--
ALTER TABLE `modelos_solicitacao`
  MODIFY `id_modelo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=492;

--
-- AUTO_INCREMENT de tabela `niveis_habilitacao`
--
ALTER TABLE `niveis_habilitacao`
  MODIFY `id_nivel_habilitacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `nomes_formacao`
--
ALTER TABLE `nomes_formacao`
  MODIFY `id_nome_formacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id_notificacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `paises`
--
ALTER TABLE `paises`
  MODIFY `id_pais` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=749;

--
-- AUTO_INCREMENT de tabela `patentes`
--
ALTER TABLE `patentes`
  MODIFY `id_patente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `perfis`
--
ALTER TABLE `perfis`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `permissoes`
--
ALTER TABLE `permissoes`
  MODIFY `id_permissao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de tabela `provincias`
--
ALTER TABLE `provincias`
  MODIFY `id_provincia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT de tabela `quadros_servico`
--
ALTER TABLE `quadros_servico`
  MODIFY `id_quadro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `ramo`
--
ALTER TABLE `ramo`
  MODIFY `id_ramo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `regioes`
--
ALTER TABLE `regioes`
  MODIFY `id_regiao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `sexos`
--
ALTER TABLE `sexos`
  MODIFY `id_sexo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `situacoes_servico`
--
ALTER TABLE `situacoes_servico`
  MODIFY `id_situacao_servico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  MODIFY `id_solicitacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos_alerta`
--
ALTER TABLE `tipos_alerta`
  MODIFY `id_tipo_alerta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `tipos_condecoracao`
--
ALTER TABLE `tipos_condecoracao`
  MODIFY `id_tipo_condecoracao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos_documento`
--
ALTER TABLE `tipos_documento`
  MODIFY `id_tipo_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tipos_equipamento`
--
ALTER TABLE `tipos_equipamento`
  MODIFY `id_tipo_equipamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tipos_formacao`
--
ALTER TABLE `tipos_formacao`
  MODIFY `id_tipo_formacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tipos_licenca`
--
ALTER TABLE `tipos_licenca`
  MODIFY `id_tipo_licenca` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `unidades`
--
ALTER TABLE `unidades`
  MODIFY `id_unidade` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id_utilizador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `vestuario`
--
ALTER TABLE `vestuario`
  MODIFY `id_vestuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE SET NULL;

--
-- Restrições para tabelas `condecoracoes`
--
ALTER TABLE `condecoracoes`
  ADD CONSTRAINT `condecoracoes_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `condecoracoes_ibfk_2` FOREIGN KEY (`id_tipo_condecoracao`) REFERENCES `tipos_condecoracao` (`id_tipo_condecoracao`);

--
-- Restrições para tabelas `configuracoes_alertas`
--
ALTER TABLE `configuracoes_alertas`
  ADD CONSTRAINT `configuracoes_alertas_ibfk_1` FOREIGN KEY (`id_tipo_alerta`) REFERENCES `tipos_alerta` (`id_tipo_alerta`) ON DELETE CASCADE,
  ADD CONSTRAINT `configuracoes_alertas_ibfk_2` FOREIGN KEY (`id_ramo`) REFERENCES `ramo` (`id_ramo`) ON DELETE CASCADE;

--
-- Restrições para tabelas `dados_bancarios`
--
ALTER TABLE `dados_bancarios`
  ADD CONSTRAINT `fk_db_banco` FOREIGN KEY (`id_banco`) REFERENCES `bancos` (`id_banco`),
  ADD CONSTRAINT `fk_db_especialista` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE;

--
-- Restrições para tabelas `dados_pessoais`
--
ALTER TABLE `dados_pessoais`
  ADD CONSTRAINT `dados_pessoais_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `dados_pessoais_ibfk_2` FOREIGN KEY (`id_sexo`) REFERENCES `sexos` (`id_sexo`),
  ADD CONSTRAINT `dados_pessoais_ibfk_3` FOREIGN KEY (`id_pais_nascimento`) REFERENCES `paises` (`id_pais`),
  ADD CONSTRAINT `dados_pessoais_ibfk_4` FOREIGN KEY (`id_provincia_nascimento`) REFERENCES `provincias` (`id_provincia`),
  ADD CONSTRAINT `dados_pessoais_ibfk_5` FOREIGN KEY (`id_municipio_nascimento`) REFERENCES `municipios` (`id_municipio`),
  ADD CONSTRAINT `dados_pessoais_ibfk_6` FOREIGN KEY (`id_pais_nacionalidade`) REFERENCES `paises` (`id_pais`),
  ADD CONSTRAINT `dados_pessoais_ibfk_7` FOREIGN KEY (`id_estado_civil`) REFERENCES `estados_civis` (`id_estado_civil`);

--
-- Restrições para tabelas `dados_residencia`
--
ALTER TABLE `dados_residencia`
  ADD CONSTRAINT `dados_residencia_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `dados_residencia_ibfk_2` FOREIGN KEY (`id_provincia`) REFERENCES `provincias` (`id_provincia`),
  ADD CONSTRAINT `dados_residencia_ibfk_3` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`);

--
-- Restrições para tabelas `dados_servico`
--
ALTER TABLE `dados_servico`
  ADD CONSTRAINT `dados_servico_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `dados_servico_ibfk_2` FOREIGN KEY (`id_funcao`) REFERENCES `funcoes` (`id_funcao`),
  ADD CONSTRAINT `dados_servico_ibfk_3` FOREIGN KEY (`id_unidade`) REFERENCES `unidades` (`id_unidade`),
  ADD CONSTRAINT `dados_servico_ibfk_4` FOREIGN KEY (`id_departamento`) REFERENCES `departamentos` (`id_departamento`),
  ADD CONSTRAINT `dados_servico_ibfk_5` FOREIGN KEY (`id_situacao_servico`) REFERENCES `situacoes_servico` (`id_situacao_servico`),
  ADD CONSTRAINT `fk_ds_regiao` FOREIGN KEY (`id_regiao`) REFERENCES `regioes` (`id_regiao`) ON DELETE NO ACTION;

--
-- Restrições para tabelas `departamentos`
--
ALTER TABLE `departamentos`
  ADD CONSTRAINT `departamentos_ibfk_1` FOREIGN KEY (`id_unidade`) REFERENCES `unidades` (`id_unidade`);

--
-- Restrições para tabelas `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `documentos_ibfk_2` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamentos` (`id_equipamento`) ON DELETE CASCADE,
  ADD CONSTRAINT `documentos_ibfk_3` FOREIGN KEY (`id_tipo_documento`) REFERENCES `tipos_documento` (`id_tipo_documento`);

--
-- Restrições para tabelas `equipamentos`
--
ALTER TABLE `equipamentos`
  ADD CONSTRAINT `equipamentos_ibfk_1` FOREIGN KEY (`id_ramo`) REFERENCES `ramo` (`id_ramo`),
  ADD CONSTRAINT `equipamentos_ibfk_2` FOREIGN KEY (`id_unidade`) REFERENCES `unidades` (`id_unidade`),
  ADD CONSTRAINT `equipamentos_ibfk_3` FOREIGN KEY (`id_tipo_equipamento`) REFERENCES `tipos_equipamento` (`id_tipo_equipamento`),
  ADD CONSTRAINT `equipamentos_ibfk_4` FOREIGN KEY (`id_estado_equipamento`) REFERENCES `estados_equipamento` (`id_estado_equipamento`),
  ADD CONSTRAINT `fk_eq_regiao` FOREIGN KEY (`id_regiao`) REFERENCES `regioes` (`id_regiao`) ON DELETE NO ACTION,
  ADD CONSTRAINT `fk_eq_responsavel` FOREIGN KEY (`id_especialista_responsavel`) REFERENCES `especialistas` (`id_especialista`) ON DELETE SET NULL;

--
-- Restrições para tabelas `especialistas`
--
ALTER TABLE `especialistas`
  ADD CONSTRAINT `especialistas_ibfk_1` FOREIGN KEY (`id_ramo`) REFERENCES `ramo` (`id_ramo`),
  ADD CONSTRAINT `especialistas_ibfk_2` FOREIGN KEY (`id_quadro`) REFERENCES `quadros_servico` (`id_quadro`),
  ADD CONSTRAINT `especialistas_ibfk_3` FOREIGN KEY (`id_patente`) REFERENCES `patentes` (`id_patente`),
  ADD CONSTRAINT `especialistas_ibfk_4` FOREIGN KEY (`id_ramo`,`id_patente`) REFERENCES `patentes` (`id_ramo`, `id_patente`);

--
-- Restrições para tabelas `familiares`
--
ALTER TABLE `familiares`
  ADD CONSTRAINT `familiares_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `familiares_ibfk_2` FOREIGN KEY (`id_grau_parentesco`) REFERENCES `graus_parentesco` (`id_grau_parentesco`);

--
-- Restrições para tabelas `feed_anexos`
--
ALTER TABLE `feed_anexos`
  ADD CONSTRAINT `fk_feed_anexo_publicacao` FOREIGN KEY (`id_publicacao`) REFERENCES `feed_publicacoes` (`id_publicacao`) ON DELETE CASCADE;

--
-- Restrições para tabelas `feed_avaliacoes`
--
ALTER TABLE `feed_avaliacoes`
  ADD CONSTRAINT `fk_feed_avaliacoes_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE CASCADE;

--
-- Restrições para tabelas `feed_candidaturas`
--
ALTER TABLE `feed_candidaturas`
  ADD CONSTRAINT `fk_feed_candidatura_autor` FOREIGN KEY (`submetido_por`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_feed_candidatura_especialista` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_feed_candidatura_publicacao` FOREIGN KEY (`id_publicacao`) REFERENCES `feed_publicacoes` (`id_publicacao`) ON DELETE CASCADE;

--
-- Restrições para tabelas `feed_comentarios`
--
ALTER TABLE `feed_comentarios`
  ADD CONSTRAINT `fk_feed_comentario_publicacao` FOREIGN KEY (`id_publicacao`) REFERENCES `feed_publicacoes` (`id_publicacao`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_feed_comentario_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE CASCADE;

--
-- Restrições para tabelas `feed_leituras`
--
ALTER TABLE `feed_leituras`
  ADD CONSTRAINT `fk_feed_leitura_publicacao` FOREIGN KEY (`id_publicacao`) REFERENCES `feed_publicacoes` (`id_publicacao`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_feed_leitura_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE CASCADE;

--
-- Restrições para tabelas `feed_oportunidade_config`
--
ALTER TABLE `feed_oportunidade_config`
  ADD CONSTRAINT `fk_feed_opp_config_nivel` FOREIGN KEY (`id_nivel_habilitacao`) REFERENCES `niveis_habilitacao` (`id_nivel_habilitacao`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_feed_opp_config_patente` FOREIGN KEY (`id_patente_criterio`) REFERENCES `patentes` (`id_patente`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_feed_opp_config_publicacao` FOREIGN KEY (`id_publicacao`) REFERENCES `feed_publicacoes` (`id_publicacao`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_feed_opp_config_quadro` FOREIGN KEY (`id_quadro_criterio`) REFERENCES `quadros_servico` (`id_quadro`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_feed_opp_config_ramo` FOREIGN KEY (`id_ramo_criterio`) REFERENCES `ramo` (`id_ramo`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_feed_opp_config_utilizador` FOREIGN KEY (`atualizado_por`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE SET NULL;

--
-- Restrições para tabelas `feed_publicacoes`
--
ALTER TABLE `feed_publicacoes`
  ADD CONSTRAINT `fk_feed_autor` FOREIGN KEY (`id_autor`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_feed_ramo` FOREIGN KEY (`id_ramo`) REFERENCES `ramo` (`id_ramo`) ON DELETE SET NULL;

--
-- Restrições para tabelas `feed_reacoes`
--
ALTER TABLE `feed_reacoes`
  ADD CONSTRAINT `fk_feed_reacao_publicacao` FOREIGN KEY (`id_publicacao`) REFERENCES `feed_publicacoes` (`id_publicacao`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_feed_reacao_utilizador` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE CASCADE;

--
-- Restrições para tabelas `ferias`
--
ALTER TABLE `ferias`
  ADD CONSTRAINT `ferias_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE;

--
-- Restrições para tabelas `formacoes`
--
ALTER TABLE `formacoes`
  ADD CONSTRAINT `formacoes_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `formacoes_ibfk_2` FOREIGN KEY (`id_tipo_formacao`) REFERENCES `tipos_formacao` (`id_tipo_formacao`),
  ADD CONSTRAINT `formacoes_ibfk_3` FOREIGN KEY (`id_instituicao`) REFERENCES `instituicoes` (`id_instituicao`),
  ADD CONSTRAINT `formacoes_ibfk_4` FOREIGN KEY (`id_pais`) REFERENCES `paises` (`id_pais`);

--
-- Restrições para tabelas `fotografias`
--
ALTER TABLE `fotografias`
  ADD CONSTRAINT `fotografias_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `fotografias_ibfk_2` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamentos` (`id_equipamento`) ON DELETE CASCADE;

--
-- Restrições para tabelas `habilitacoes_literarias`
--
ALTER TABLE `habilitacoes_literarias`
  ADD CONSTRAINT `habilitacoes_literarias_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `habilitacoes_literarias_ibfk_2` FOREIGN KEY (`id_nivel_habilitacao`) REFERENCES `niveis_habilitacao` (`id_nivel_habilitacao`),
  ADD CONSTRAINT `habilitacoes_literarias_ibfk_3` FOREIGN KEY (`id_instituicao`) REFERENCES `instituicoes` (`id_instituicao`),
  ADD CONSTRAINT `habilitacoes_literarias_ibfk_4` FOREIGN KEY (`id_pais`) REFERENCES `paises` (`id_pais`);

--
-- Restrições para tabelas `historico_configuracoes`
--
ALTER TABLE `historico_configuracoes`
  ADD CONSTRAINT `historico_configuracoes_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE SET NULL;

--
-- Restrições para tabelas `historico_equipamentos`
--
ALTER TABLE `historico_equipamentos`
  ADD CONSTRAINT `historico_equipamentos_ibfk_1` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamentos` (`id_equipamento`) ON DELETE SET NULL,
  ADD CONSTRAINT `historico_equipamentos_ibfk_2` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE SET NULL;

--
-- Restrições para tabelas `historico_especialistas`
--
ALTER TABLE `historico_especialistas`
  ADD CONSTRAINT `historico_especialistas_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `historico_especialistas_ibfk_2` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE SET NULL;

--
-- Restrições para tabelas `instituicoes`
--
ALTER TABLE `instituicoes`
  ADD CONSTRAINT `instituicoes_ibfk_1` FOREIGN KEY (`id_pais`) REFERENCES `paises` (`id_pais`);

--
-- Restrições para tabelas `licencas`
--
ALTER TABLE `licencas`
  ADD CONSTRAINT `licencas_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `licencas_ibfk_2` FOREIGN KEY (`id_tipo_licenca`) REFERENCES `tipos_licenca` (`id_tipo_licenca`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `municipios_ibfk_1` FOREIGN KEY (`id_provincia`) REFERENCES `provincias` (`id_provincia`);

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`id_ramo`) REFERENCES `ramo` (`id_ramo`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificacoes_ibfk_2` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificacoes_ibfk_3` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificacoes_ibfk_4` FOREIGN KEY (`id_equipamento`) REFERENCES `equipamentos` (`id_equipamento`) ON DELETE CASCADE,
  ADD CONSTRAINT `notificacoes_ibfk_5` FOREIGN KEY (`id_tipo_alerta`) REFERENCES `tipos_alerta` (`id_tipo_alerta`);

--
-- Restrições para tabelas `patentes`
--
ALTER TABLE `patentes`
  ADD CONSTRAINT `patentes_ibfk_1` FOREIGN KEY (`id_ramo`) REFERENCES `ramo` (`id_ramo`);

--
-- Restrições para tabelas `perfil_modulos`
--
ALTER TABLE `perfil_modulos`
  ADD CONSTRAINT `fk_perfil_modulos_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `perfis` (`id_perfil`) ON DELETE CASCADE;

--
-- Restrições para tabelas `perfil_permissoes`
--
ALTER TABLE `perfil_permissoes`
  ADD CONSTRAINT `perfil_permissoes_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfis` (`id_perfil`) ON DELETE CASCADE,
  ADD CONSTRAINT `perfil_permissoes_ibfk_2` FOREIGN KEY (`id_permissao`) REFERENCES `permissoes` (`id_permissao`) ON DELETE CASCADE;

--
-- Restrições para tabelas `provincias`
--
ALTER TABLE `provincias`
  ADD CONSTRAINT `provincias_ibfk_1` FOREIGN KEY (`id_pais`) REFERENCES `paises` (`id_pais`);

--
-- Restrições para tabelas `regiao_provincias`
--
ALTER TABLE `regiao_provincias`
  ADD CONSTRAINT `regiao_provincias_ibfk_1` FOREIGN KEY (`id_regiao`) REFERENCES `regioes` (`id_regiao`) ON DELETE CASCADE,
  ADD CONSTRAINT `regiao_provincias_ibfk_2` FOREIGN KEY (`id_provincia`) REFERENCES `provincias` (`id_provincia`) ON DELETE CASCADE;

--
-- Restrições para tabelas `solicitacoes`
--
ALTER TABLE `solicitacoes`
  ADD CONSTRAINT `solicitacoes_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`),
  ADD CONSTRAINT `solicitacoes_ibfk_2` FOREIGN KEY (`id_modelo`) REFERENCES `modelos_solicitacao` (`id_modelo`),
  ADD CONSTRAINT `solicitacoes_ibfk_3` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`),
  ADD CONSTRAINT `solicitacoes_ibfk_4` FOREIGN KEY (`id_ramo`) REFERENCES `ramo` (`id_ramo`);

--
-- Restrições para tabelas `unidades`
--
ALTER TABLE `unidades`
  ADD CONSTRAINT `fk_unidade_regiao` FOREIGN KEY (`id_regiao`) REFERENCES `regioes` (`id_regiao`) ON DELETE NO ACTION,
  ADD CONSTRAINT `unidades_ibfk_1` FOREIGN KEY (`id_ramo`) REFERENCES `ramo` (`id_ramo`);

--
-- Restrições para tabelas `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD CONSTRAINT `fk_user_regiao_resp` FOREIGN KEY (`id_regiao_responsabilidade`) REFERENCES `regioes` (`id_regiao`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_utilizador_especialista` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `utilizadores_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `perfis` (`id_perfil`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `utilizador_ramo`
--
ALTER TABLE `utilizador_ramo`
  ADD CONSTRAINT `utilizador_ramo_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE CASCADE,
  ADD CONSTRAINT `utilizador_ramo_ibfk_2` FOREIGN KEY (`id_ramo`) REFERENCES `ramo` (`id_ramo`) ON DELETE CASCADE;

--
-- Restrições para tabelas `utilizador_regiao`
--
ALTER TABLE `utilizador_regiao`
  ADD CONSTRAINT `utilizador_regiao_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE CASCADE,
  ADD CONSTRAINT `utilizador_regiao_ibfk_2` FOREIGN KEY (`id_regiao`) REFERENCES `regioes` (`id_regiao`) ON DELETE CASCADE;

--
-- Restrições para tabelas `utilizador_unidade`
--
ALTER TABLE `utilizador_unidade`
  ADD CONSTRAINT `utilizador_unidade_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id_utilizador`) ON DELETE CASCADE,
  ADD CONSTRAINT `utilizador_unidade_ibfk_2` FOREIGN KEY (`id_unidade`) REFERENCES `unidades` (`id_unidade`) ON DELETE CASCADE;

--
-- Restrições para tabelas `vestuario`
--
ALTER TABLE `vestuario`
  ADD CONSTRAINT `vestuario_ibfk_1` FOREIGN KEY (`id_especialista`) REFERENCES `especialistas` (`id_especialista`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
