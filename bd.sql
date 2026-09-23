-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23/09/2026 às 19:56
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
-- Banco de dados: `bd`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `lab`
--

CREATE TABLE `lab` (
  `id` int(11) NOT NULL,
  `numero_lab` int(11) NOT NULL,
  `status` enum('Liberado','Reservado') NOT NULL DEFAULT 'Liberado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `lab`
--

INSERT INTO `lab` (`id`, `numero_lab`, `status`) VALUES
(4, 4, 'Liberado'),
(5, 5, 'Liberado'),
(6, 6, 'Liberado'),
(7, 7, 'Liberado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `login`
--

INSERT INTO `login` (`id`, `email`, `senha`) VALUES
(1, 'teste@gmail.com', 'teste');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores`
--

CREATE TABLE `professores` (
  `id` int(11) NOT NULL,
  `nm_professor` varchar(200) NOT NULL,
  `matricula` int(11) NOT NULL,
  `status` enum('ativo','inativo') NOT NULL,
  `ds_email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professores`
--

INSERT INTO `professores` (`id`, `nm_professor`, `matricula`, `status`, `ds_email`) VALUES
(1, 'a', 223, 'inativo', ''),
(4, 's', 3333, 'inativo', ''),
(6, 's', 3333, 'ativo', 'aa@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `cd_professor` int(11) NOT NULL,
  `cd_turma` int(11) NOT NULL,
  `cd_lab` int(11) NOT NULL,
  `horario_inicio` datetime NOT NULL,
  `horario_termino` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `turma`
--

CREATE TABLE `turma` (
  `id` int(11) NOT NULL,
  `ds_curso` varchar(200) NOT NULL,
  `status` enum('ativo','inativo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `turma`
--

INSERT INTO `turma` (`id`, `ds_curso`, `status`) VALUES
(1, 'bbbbbb', 'inativo'),
(2, 'aaaidfihujoiudsl', 'inativo'),
(3, 'oooo', 'ativo'),
(4, 'aaaaaaaaaaaasl', 'inativo'),
(5, '6', '');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `lab`
--
ALTER TABLE `lab`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_lab` (`numero_lab`);

--
-- Índices de tabela `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `professores`
--
ALTER TABLE `professores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cd_professor` (`cd_professor`),
  ADD KEY `cd_turma` (`cd_turma`),
  ADD KEY `cd_lab` (`cd_lab`);

--
-- Índices de tabela `turma`
--
ALTER TABLE `turma`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `lab`
--
ALTER TABLE `lab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `professores`
--
ALTER TABLE `professores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `turma`
--
ALTER TABLE `turma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`cd_professor`) REFERENCES `professores` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`cd_turma`) REFERENCES `turma` (`id`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`cd_lab`) REFERENCES `lab` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
