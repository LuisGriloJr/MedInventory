-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/07/2026 às 14:06
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
-- Banco de dados: `inventario_clinico`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `anexos_manutencao`
--

CREATE TABLE `anexos_manutencao` (
  `id` int(11) NOT NULL,
  `manutencao_id` int(11) NOT NULL,
  `nome_original` varchar(255) NOT NULL,
  `nome_arquivo` varchar(255) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `tamanho` int(11) DEFAULT NULL,
  `data_upload` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `anexos_manutencao`
--

INSERT INTO `anexos_manutencao` (`id`, `manutencao_id`, `nome_original`, `nome_arquivo`, `tipo`, `descricao`, `tamanho`, `data_upload`) VALUES
(2, 3, 'CELIA CAZISSI.pdf', '6a552bbb7da39.pdf', 'pdf', '', 111253, '2026-07-13 15:17:31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cabos`
--

CREATE TABLE `cabos` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `contato` varchar(100) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresas`
--

INSERT INTO `empresas` (`id`, `nome`, `contato`, `telefone`, `email`, `observacoes`, `criado_em`) VALUES
(1, 'manutencao equipamentos ltda', 'luis', '19 981004403', 'contato@gmail.com', '', '2026-07-10 18:17:06');

-- --------------------------------------------------------

--
-- Estrutura para tabela `equipamentos`
--

CREATE TABLE `equipamentos` (
  `id` int(11) NOT NULL,
  `patrimonio` varchar(50) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `fabricante` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `numero_serie` varchar(100) DEFAULT NULL,
  `setor` varchar(100) DEFAULT NULL,
  `localizacao` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Em uso',
  `foto` varchar(255) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `equipamentos`
--

INSERT INTO `equipamentos` (`id`, `patrimonio`, `nome`, `fabricante`, `modelo`, `numero_serie`, `setor`, `localizacao`, `status`, `foto`, `observacoes`, `criado_em`) VALUES
(2, NULL, 'Bomba de infusão 2', 'terumo', 'modelo TE-LM 830', '4001', 'centro cirurgico', 'santa casa cosmopolis', 'Em uso', 'uploads/equipamentos/6a4e6c8265a7b.webp', '', '2026-07-08 15:11:26'),
(3, NULL, 'monitor multiparametros', 'Nihon Kohden', 'vismo', '4000', 'centro cirurgico', 'santa casa cosmopolis', 'Manutenção', 'uploads/equipamentos/6a4e8daea4bf0.webp', '', '2026-07-08 17:49:34'),
(4, NULL, 'monitor multiparametros 2', 'mindray', 'modelo TE-LM 830', '40003', 'centro cirurgico', 'santa casa cosmopolis', 'Em uso', NULL, '', '2026-07-10 12:33:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `manutencoes`
--

CREATE TABLE `manutencoes` (
  `id` int(11) NOT NULL,
  `equipamento_id` int(11) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('Corretiva','Preventiva','Calibração','Inspeção','Outro') NOT NULL,
  `status` enum('Aberta','Em andamento','Concluída') NOT NULL DEFAULT 'Aberta',
  `data_abertura` date NOT NULL,
  `data_conclusao` date DEFAULT NULL,
  `numero_os` varchar(100) DEFAULT NULL,
  `descricao_problema` text NOT NULL,
  `solucao_aplicada` text DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `garantia_ate` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_em` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `manutencoes`
--

INSERT INTO `manutencoes` (`id`, `equipamento_id`, `empresa_id`, `usuario_id`, `tipo`, `status`, `data_abertura`, `data_conclusao`, `numero_os`, `descricao_problema`, `solucao_aplicada`, `valor`, `garantia_ate`, `observacoes`, `criado_em`, `atualizado_em`) VALUES
(1, 2, 1, 1, 'Preventiva', 'Concluída', '2026-07-10', '2026-07-10', '1', 'falha no equipamento', 'eu fiz funcionar', 1000.00, '2026-07-11', '', '2026-07-10 19:00:06', '2026-07-10 19:40:47'),
(2, 2, 1, 1, 'Corretiva', 'Concluída', '2026-07-13', '2026-07-13', '', 'deu ruim', 'coisa boa', NULL, '2026-07-14', '', '2026-07-13 11:11:27', '2026-07-13 17:40:18'),
(3, 3, 1, 1, 'Corretiva', 'Concluída', '2026-07-13', '2026-07-14', '1', 'deu bo', 'corrigiu', 1500.00, '2026-07-14', '', '2026-07-13 18:17:24', '2026-07-14 13:28:44'),
(4, 3, 1, 1, 'Corretiva', 'Aberta', '2026-07-14', NULL, '5', 'deu ruim', NULL, 1500.00, NULL, '', '2026-07-14 13:42:56', '2026-07-14 13:42:56');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel` varchar(50) DEFAULT 'usuario',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `nivel`, `criado_em`) VALUES
(1, 'Administrador', 'admin@hospital.com', '$2y$10$dO4caPpPqHAaaPacftcYHe1arabXy/W7HuxM600cIIwrDPXlbKADm', 'admin', '2026-07-08 13:49:46');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `anexos_manutencao`
--
ALTER TABLE `anexos_manutencao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `manutencao_id` (`manutencao_id`);

--
-- Índices de tabela `cabos`
--
ALTER TABLE `cabos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `equipamentos`
--
ALTER TABLE `equipamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `manutencoes`
--
ALTER TABLE `manutencoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_manutencao_equipamento` (`equipamento_id`),
  ADD KEY `fk_manutencao_empresa` (`empresa_id`),
  ADD KEY `fk_manutencao_usuario` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `anexos_manutencao`
--
ALTER TABLE `anexos_manutencao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `cabos`
--
ALTER TABLE `cabos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `equipamentos`
--
ALTER TABLE `equipamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `manutencoes`
--
ALTER TABLE `manutencoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `anexos_manutencao`
--
ALTER TABLE `anexos_manutencao`
  ADD CONSTRAINT `anexos_manutencao_ibfk_1` FOREIGN KEY (`manutencao_id`) REFERENCES `manutencoes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `manutencoes`
--
ALTER TABLE `manutencoes`
  ADD CONSTRAINT `fk_manutencao_empresa` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`),
  ADD CONSTRAINT `fk_manutencao_equipamento` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`),
  ADD CONSTRAINT `fk_manutencao_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
