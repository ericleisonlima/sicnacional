-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 17-Maio-2017 às 15:50
-- Versão do servidor: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sicnacional`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `acessos`
--

CREATE TABLE `acessos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sessionid` varchar(100) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `acessos`
--

INSERT INTO `acessos` (`id`, `sessionid`, `login`, `login_time`, `logout_time`) VALUES
(1, 'grfork5afi82mml4l8kd43gls5', 'dev', '2017-05-17 12:13:03', '2017-05-17 12:28:21'),
(2, 'gfkthg6rka7ksui4gt1gush550', 'dev', '2017-05-17 12:29:03', '2017-05-17 12:31:31'),
(3, 'eueum1dfnec04t9rmf7jblcj73', 'dev', '2017-05-17 12:45:31', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `administracaonutricao`
--

CREATE TABLE `administracaonutricao` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `anamnese`
--

CREATE TABLE `anamnese` (
  `id` int(10) UNSIGNED NOT NULL,
  `estabelecimento_medico_id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `dataregistro` date NOT NULL,
  `peso` decimal(12,2) DEFAULT NULL,
  `altura` decimal(12,2) DEFAULT NULL,
  `fumante` char(1) COLLATE latin1_general_ci DEFAULT NULL,
  `datacirurgia` date DEFAULT NULL,
  `comprimentointestinodelgado` decimal(12,2) DEFAULT NULL,
  `larguraintestinodelgado` decimal(12,2) DEFAULT NULL,
  `valvulaileocecal` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `colonemcontinuidade` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `colonremanescente` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `estomia` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `transplantado` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `datatransplante` date DEFAULT NULL,
  `tipotrasnplante` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `desfechotransplante` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `diagnosticonutricional` varchar(300) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `causa_obito`
--

CREATE TABLE `causa_obito` (
  `id` int(10) UNSIGNED NOT NULL,
  `descricao` varchar(100) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cid`
--

CREATE TABLE `cid` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `codigocid` varchar(20) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `comorbidades`
--

CREATE TABLE `comorbidades` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(50) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `condicoes_diagnostico`
--

CREATE TABLE `condicoes_diagnostico` (
  `id` int(10) UNSIGNED NOT NULL,
  `descricao` varchar(100) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `doencabase`
--

CREATE TABLE `doencabase` (
  `id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `cid_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `escolaridade`
--

CREATE TABLE `escolaridade` (
  `id` int(10) UNSIGNED NOT NULL,
  `descricao` varchar(100) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `estabelecimento`
--

CREATE TABLE `estabelecimento` (
  `id` int(10) UNSIGNED NOT NULL,
  `municipio_id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `endereco` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `bairro` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `cep` varchar(9) COLLATE latin1_general_ci DEFAULT NULL,
  `latitude` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `longitude` varchar(20) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `estabelecimento_medico`
--

CREATE TABLE `estabelecimento_medico` (
  `id` int(10) UNSIGNED NOT NULL,
  `estabelecimento_id` int(10) UNSIGNED NOT NULL,
  `medico_id` int(10) UNSIGNED NOT NULL,
  `responsavel` char(1) COLLATE latin1_general_ci NOT NULL COMMENT 'S ou N',
  `datainicio` date DEFAULT NULL,
  `datafim` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `examepaciente`
--

CREATE TABLE `examepaciente` (
  `id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `tipoexame_id` int(10) UNSIGNED NOT NULL,
  `dataexame` date NOT NULL,
  `valor` decimal(12,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos`
--

CREATE TABLE `grupos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `grupos`
--

INSERT INTO `grupos` (`id`, `name`) VALUES
(1, 'Desenvolvedores');

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos_programas`
--

CREATE TABLE `grupos_programas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_group_id` bigint(20) UNSIGNED DEFAULT NULL,
  `system_program_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `grupos_programas`
--

INSERT INTO `grupos_programas` (`id`, `system_group_id`, `system_program_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(17, 1, 17),
(18, 1, 18);

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico_medico_paciente`
--

CREATE TABLE `historico_medico_paciente` (
  `id` int(10) UNSIGNED NOT NULL,
  `estabelecimento_medico_id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `datainicio` date NOT NULL,
  `datafim` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `medicamento`
--

CREATE TABLE `medicamento` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipomedicamento_id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `medico`
--

CREATE TABLE `medico` (
  `id` int(10) UNSIGNED NOT NULL,
  `municipio_id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `crm` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `celular` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `telefone` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `email` varchar(150) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `system_user_to_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` varchar(500) DEFAULT NULL,
  `dt_message` datetime DEFAULT NULL,
  `checked` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `municipio`
--

CREATE TABLE `municipio` (
  `id` int(10) UNSIGNED NOT NULL,
  `codibge` int(10) UNSIGNED NOT NULL,
  `nome` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `uf` char(2) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `system_user_to_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` varchar(500) DEFAULT NULL,
  `dt_message` datetime DEFAULT NULL,
  `action_url` varchar(500) DEFAULT NULL,
  `action_label` varchar(500) DEFAULT NULL,
  `icon` varchar(500) DEFAULT NULL,
  `checked` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `nutricaoenteral`
--

CREATE TABLE `nutricaoenteral` (
  `id` int(10) UNSIGNED NOT NULL,
  `administracaonutricao_id` int(10) UNSIGNED NOT NULL,
  `tiponutricao_id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `datainicio` date NOT NULL,
  `datafim` date DEFAULT NULL,
  `totalcalorias` decimal(12,2) DEFAULT NULL,
  `percentualdiario` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `nutricaoparenteral`
--

CREATE TABLE `nutricaoparenteral` (
  `id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `datainicio` date NOT NULL,
  `datafim` date DEFAULT NULL,
  `tipoparenteral` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `tipoparenteraloutros` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `totalcalorias` decimal(12,2) DEFAULT NULL,
  `percentualdiario` decimal(12,2) DEFAULT NULL,
  `volumenpt` decimal(12,2) DEFAULT NULL,
  `tempoinfusao` decimal(12,2) DEFAULT NULL,
  `frequencia` decimal(12,2) DEFAULT NULL,
  `acessovenosolp` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `acessovenosolpqual` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `numerodeacessovenoso` int(10) UNSIGNED DEFAULT NULL,
  `apresentouinfeccaoacessovenoso` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `vezesinfeccaoacessovenoso` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `paciente`
--

CREATE TABLE `paciente` (
  `id` int(10) UNSIGNED NOT NULL,
  `municipio_id` int(10) UNSIGNED NOT NULL,
  `causa_obito_id` int(10) UNSIGNED NOT NULL,
  `condicoes_diagnostico_id` int(10) UNSIGNED NOT NULL,
  `escolaridade_id` int(10) UNSIGNED NOT NULL,
  `estabelecimento_medico_id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `datanascimento` date DEFAULT NULL,
  `tiposanguineo` varchar(10) COLLATE latin1_general_ci DEFAULT NULL,
  `fatorsanguineo` char(1) COLLATE latin1_general_ci DEFAULT NULL,
  `datadiagnostico` date DEFAULT NULL,
  `dataobito` date DEFAULT NULL,
  `telefone` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `email` varchar(150) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `paciente_comorbidades`
--

CREATE TABLE `paciente_comorbidades` (
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `comorbidades_id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(20) COLLATE latin1_general_ci NOT NULL COMMENT 'Patogencia, Diagnostica, Prognostico',
  `dataocorrencia` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pesagempaciente`
--

CREATE TABLE `pesagempaciente` (
  `id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `peso` decimal(12,2) NOT NULL,
  `datapesagem` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `programas`
--

CREATE TABLE `programas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `controller` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `programas`
--

INSERT INTO `programas` (`id`, `name`, `controller`) VALUES
(1, 'System Group Form', 'SystemGroupForm'),
(2, 'System Group List', 'SystemGroupList'),
(3, 'System Program Form', 'SystemProgramForm'),
(4, 'System Program List', 'SystemProgramList'),
(5, 'System User Form', 'SystemUserForm'),
(6, 'System User List', 'SystemUserList'),
(7, 'System Unit Form', 'SystemUnitForm'),
(8, 'System Unit List', 'SystemUnitList'),
(9, 'System Profile View', 'SystemProfileView'),
(10, 'System Profile Form', 'SystemProfileForm'),
(11, 'System Message Form', 'SystemMessageForm'),
(12, 'System Message List', 'SystemMessageList'),
(13, 'System Message Form View', 'SystemMessageFormView'),
(14, 'System Notification List', 'SystemNotificationList'),
(15, 'System Notification Form View', 'SystemNotificationFormView'),
(16, 'System Access Log', 'SystemAccessLogList'),
(17, 'System Access stats', 'SystemAccessLogStats'),
(18, 'System Support form', 'SystemSupportForm');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipoadministracaomedicamento`
--

CREATE TABLE `tipoadministracaomedicamento` (
  `id` int(10) UNSIGNED NOT NULL,
  `descricao` varchar(50) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipoexame`
--

CREATE TABLE `tipoexame` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `unidademedida` varchar(20) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipomedicamento`
--

CREATE TABLE `tipomedicamento` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(50) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tiponutricao`
--

CREATE TABLE `tiponutricao` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `unidades`
--

CREATE TABLE `unidades` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usomedicamento`
--

CREATE TABLE `usomedicamento` (
  `id` int(10) UNSIGNED NOT NULL,
  `tipoadministracaomedicamento_id` int(10) UNSIGNED NOT NULL,
  `paciente_id` int(10) UNSIGNED NOT NULL,
  `medicamento_id` int(10) UNSIGNED NOT NULL,
  `datainicio` date DEFAULT NULL,
  `datafim` date DEFAULT NULL,
  `posologia` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `observacao` varchar(250) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `frontpage_id` bigint(20) UNSIGNED DEFAULT NULL,
  `system_unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `active` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `name`, `login`, `password`, `email`, `frontpage_id`, `system_unit_id`, `active`) VALUES
(1, 'Desenvolvedor', 'dev', 'e77989ed21758e78331b20e477fc5582', NULL, NULL, NULL, 'Y');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios_grupos`
--

CREATE TABLE `usuarios_grupos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `system_group_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `usuarios_grupos`
--

INSERT INTO `usuarios_grupos` (`id`, `system_user_id`, `system_group_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios_programas`
--

CREATE TABLE `usuarios_programas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `system_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `system_program_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acessos`
--
ALTER TABLE `acessos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `administracaonutricao`
--
ALTER TABLE `administracaonutricao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `anamnese`
--
ALTER TABLE `anamnese`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anaminese_FKIndex1` (`paciente_id`),
  ADD KEY `anaminese_FKIndex2` (`estabelecimento_medico_id`);

--
-- Indexes for table `causa_obito`
--
ALTER TABLE `causa_obito`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cid`
--
ALTER TABLE `cid`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comorbidades`
--
ALTER TABLE `comorbidades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `condicoes_diagnostico`
--
ALTER TABLE `condicoes_diagnostico`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doencabase`
--
ALTER TABLE `doencabase`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doencabase_FKIndex1` (`cid_id`),
  ADD KEY `doencabase_FKIndex2` (`paciente_id`);

--
-- Indexes for table `escolaridade`
--
ALTER TABLE `escolaridade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estabelecimento`
--
ALTER TABLE `estabelecimento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estabelecimento_FKIndex1` (`municipio_id`);

--
-- Indexes for table `estabelecimento_medico`
--
ALTER TABLE `estabelecimento_medico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estabelecimento_medico_FKIndex1` (`medico_id`),
  ADD KEY `estabelecimento_medico_FKIndex2` (`estabelecimento_id`),
  ADD KEY `estabelecimento_medico_unico` (`estabelecimento_id`,`medico_id`,`datainicio`);

--
-- Indexes for table `examepaciente`
--
ALTER TABLE `examepaciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examepaciente_FKIndex1` (`tipoexame_id`),
  ADD KEY `examepaciente_FKIndex2` (`paciente_id`);

--
-- Indexes for table `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grupos_programas`
--
ALTER TABLE `grupos_programas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_group_program_program_idx` (`system_program_id`),
  ADD KEY `system_group_program_group_idx` (`system_group_id`);

--
-- Indexes for table `historico_medico_paciente`
--
ALTER TABLE `historico_medico_paciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historico_medico_FKIndex1` (`paciente_id`),
  ADD KEY `historico_medico_FKIndex2` (`estabelecimento_medico_id`);

--
-- Indexes for table `medicamento`
--
ALTER TABLE `medicamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicamento_FKIndex1` (`tipomedicamento_id`);

--
-- Indexes for table `medico`
--
ALTER TABLE `medico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medico_FKIndex1` (`municipio_id`);

--
-- Indexes for table `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mensagens_with_system_user_id` (`system_user_id`) USING BTREE,
  ADD KEY `idx_mensagens_with_system_user_to_id` (`system_user_to_id`) USING BTREE;

--
-- Indexes for table `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notificacoes_with_system_user_id` (`system_user_id`) USING BTREE,
  ADD KEY `idx_notificacoes_with_system_user_to_id` (`system_user_to_id`) USING BTREE;

--
-- Indexes for table `nutricaoenteral`
--
ALTER TABLE `nutricaoenteral`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nutricaoenteral_FKIndex1` (`paciente_id`),
  ADD KEY `nutricaoenteral_FKIndex2` (`tiponutricao_id`),
  ADD KEY `nutricaoenteral_FKIndex3` (`administracaonutricao_id`);

--
-- Indexes for table `nutricaoparenteral`
--
ALTER TABLE `nutricaoparenteral`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nutricaoparenteral_FKIndex1` (`paciente_id`);

--
-- Indexes for table `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente_FKIndex1` (`estabelecimento_medico_id`),
  ADD KEY `paciente_FKIndex2` (`escolaridade_id`),
  ADD KEY `paciente_FKIndex3` (`condicoes_diagnostico_id`),
  ADD KEY `paciente_FKIndex4` (`causa_obito_id`),
  ADD KEY `paciente_FKIndex5` (`municipio_id`);

--
-- Indexes for table `paciente_comorbidades`
--
ALTER TABLE `paciente_comorbidades`
  ADD PRIMARY KEY (`paciente_id`,`comorbidades_id`),
  ADD KEY `paciente_has_comorbidades_FKIndex1` (`paciente_id`),
  ADD KEY `paciente_has_comorbidades_FKIndex2` (`comorbidades_id`);

--
-- Indexes for table `pesagempaciente`
--
ALTER TABLE `pesagempaciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesopaciente_FKIndex1` (`paciente_id`);

--
-- Indexes for table `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipoadministracaomedicamento`
--
ALTER TABLE `tipoadministracaomedicamento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipoexame`
--
ALTER TABLE `tipoexame`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipomedicamento`
--
ALTER TABLE `tipomedicamento`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tiponutricao`
--
ALTER TABLE `tiponutricao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unidades`
--
ALTER TABLE `unidades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usomedicamento`
--
ALTER TABLE `usomedicamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usomedicamento_FKIndex1` (`medicamento_id`),
  ADD KEY `usomedicamento_FKIndex2` (`paciente_id`),
  ADD KEY `usomedicamento_FKIndex3` (`tipoadministracaomedicamento_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_user_program_idx` (`frontpage_id`),
  ADD KEY `system_user_system_unit_id_idx` (`system_unit_id`);

--
-- Indexes for table `usuarios_grupos`
--
ALTER TABLE `usuarios_grupos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_user_group_group_idx` (`system_group_id`),
  ADD KEY `system_user_group_user_idx` (`system_user_id`);

--
-- Indexes for table `usuarios_programas`
--
ALTER TABLE `usuarios_programas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_user_program_program_idx` (`system_program_id`),
  ADD KEY `system_user_program_user_idx` (`system_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acessos`
--
ALTER TABLE `acessos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `administracaonutricao`
--
ALTER TABLE `administracaonutricao`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `anamnese`
--
ALTER TABLE `anamnese`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `causa_obito`
--
ALTER TABLE `causa_obito`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cid`
--
ALTER TABLE `cid`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `comorbidades`
--
ALTER TABLE `comorbidades`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `condicoes_diagnostico`
--
ALTER TABLE `condicoes_diagnostico`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `doencabase`
--
ALTER TABLE `doencabase`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `escolaridade`
--
ALTER TABLE `escolaridade`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `estabelecimento`
--
ALTER TABLE `estabelecimento`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `estabelecimento_medico`
--
ALTER TABLE `estabelecimento_medico`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `examepaciente`
--
ALTER TABLE `examepaciente`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `grupos_programas`
--
ALTER TABLE `grupos_programas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `historico_medico_paciente`
--
ALTER TABLE `historico_medico_paciente`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `medicamento`
--
ALTER TABLE `medicamento`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `medico`
--
ALTER TABLE `medico`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `municipio`
--
ALTER TABLE `municipio`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nutricaoenteral`
--
ALTER TABLE `nutricaoenteral`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nutricaoparenteral`
--
ALTER TABLE `nutricaoparenteral`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pesagempaciente`
--
ALTER TABLE `pesagempaciente`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `programas`
--
ALTER TABLE `programas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `tipoadministracaomedicamento`
--
ALTER TABLE `tipoadministracaomedicamento`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tipoexame`
--
ALTER TABLE `tipoexame`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tipomedicamento`
--
ALTER TABLE `tipomedicamento`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tiponutricao`
--
ALTER TABLE `tiponutricao`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unidades`
--
ALTER TABLE `unidades`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usomedicamento`
--
ALTER TABLE `usomedicamento`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `usuarios_grupos`
--
ALTER TABLE `usuarios_grupos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `usuarios_programas`
--
ALTER TABLE `usuarios_programas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `anamnese`
--
ALTER TABLE `anamnese`
  ADD CONSTRAINT `anamnese_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `anamnese_ibfk_2` FOREIGN KEY (`estabelecimento_medico_id`) REFERENCES `estabelecimento_medico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `doencabase`
--
ALTER TABLE `doencabase`
  ADD CONSTRAINT `doencabase_ibfk_1` FOREIGN KEY (`cid_id`) REFERENCES `cid` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `doencabase_ibfk_2` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `estabelecimento`
--
ALTER TABLE `estabelecimento`
  ADD CONSTRAINT `estabelecimento_ibfk_1` FOREIGN KEY (`municipio_id`) REFERENCES `municipio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `estabelecimento_medico`
--
ALTER TABLE `estabelecimento_medico`
  ADD CONSTRAINT `estabelecimento_medico_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `medico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `estabelecimento_medico_ibfk_2` FOREIGN KEY (`estabelecimento_id`) REFERENCES `estabelecimento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `examepaciente`
--
ALTER TABLE `examepaciente`
  ADD CONSTRAINT `examepaciente_ibfk_1` FOREIGN KEY (`tipoexame_id`) REFERENCES `tipoexame` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `examepaciente_ibfk_2` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `grupos_programas`
--
ALTER TABLE `grupos_programas`
  ADD CONSTRAINT `system_group_program_system_group_id_fkey` FOREIGN KEY (`system_group_id`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `system_group_program_system_program_id_fkey` FOREIGN KEY (`system_program_id`) REFERENCES `programas` (`id`);

--
-- Limitadores para a tabela `historico_medico_paciente`
--
ALTER TABLE `historico_medico_paciente`
  ADD CONSTRAINT `historico_medico_paciente_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `historico_medico_paciente_ibfk_2` FOREIGN KEY (`estabelecimento_medico_id`) REFERENCES `estabelecimento_medico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `medicamento`
--
ALTER TABLE `medicamento`
  ADD CONSTRAINT `medicamento_ibfk_1` FOREIGN KEY (`tipomedicamento_id`) REFERENCES `tipomedicamento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `medico`
--
ALTER TABLE `medico`
  ADD CONSTRAINT `medico_ibfk_1` FOREIGN KEY (`municipio_id`) REFERENCES `municipio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD CONSTRAINT `fk_system_user_id_on_mensagens` FOREIGN KEY (`system_user_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_system_user_to_id_on_mensagens` FOREIGN KEY (`system_user_to_id`) REFERENCES `usuarios` (`id`);

--
-- Limitadores para a tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `fk_system_user_id_on_notificacoes` FOREIGN KEY (`system_user_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_system_user_to_id_on_notificacoes` FOREIGN KEY (`system_user_to_id`) REFERENCES `usuarios` (`id`);

--
-- Limitadores para a tabela `nutricaoenteral`
--
ALTER TABLE `nutricaoenteral`
  ADD CONSTRAINT `nutricaoenteral_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `nutricaoenteral_ibfk_2` FOREIGN KEY (`tiponutricao_id`) REFERENCES `tiponutricao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `nutricaoenteral_ibfk_3` FOREIGN KEY (`administracaonutricao_id`) REFERENCES `administracaonutricao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `nutricaoparenteral`
--
ALTER TABLE `nutricaoparenteral`
  ADD CONSTRAINT `nutricaoparenteral_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `paciente_ibfk_1` FOREIGN KEY (`estabelecimento_medico_id`) REFERENCES `estabelecimento_medico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `paciente_ibfk_2` FOREIGN KEY (`escolaridade_id`) REFERENCES `escolaridade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `paciente_ibfk_3` FOREIGN KEY (`condicoes_diagnostico_id`) REFERENCES `condicoes_diagnostico` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `paciente_ibfk_4` FOREIGN KEY (`causa_obito_id`) REFERENCES `causa_obito` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `paciente_ibfk_5` FOREIGN KEY (`municipio_id`) REFERENCES `municipio` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `paciente_comorbidades`
--
ALTER TABLE `paciente_comorbidades`
  ADD CONSTRAINT `paciente_comorbidades_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `paciente_comorbidades_ibfk_2` FOREIGN KEY (`comorbidades_id`) REFERENCES `comorbidades` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pesagempaciente`
--
ALTER TABLE `pesagempaciente`
  ADD CONSTRAINT `pesagempaciente_ibfk_1` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `usomedicamento`
--
ALTER TABLE `usomedicamento`
  ADD CONSTRAINT `usomedicamento_ibfk_1` FOREIGN KEY (`medicamento_id`) REFERENCES `medicamento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usomedicamento_ibfk_2` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `usomedicamento_ibfk_3` FOREIGN KEY (`tipoadministracaomedicamento_id`) REFERENCES `tipoadministracaomedicamento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `system_user_frontpage_id_fkey` FOREIGN KEY (`frontpage_id`) REFERENCES `programas` (`id`),
  ADD CONSTRAINT `system_user_system_unit_id_fkey` FOREIGN KEY (`system_unit_id`) REFERENCES `unidades` (`id`);

--
-- Limitadores para a tabela `usuarios_grupos`
--
ALTER TABLE `usuarios_grupos`
  ADD CONSTRAINT `system_user_group_system_group_id_fkey` FOREIGN KEY (`system_group_id`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `system_user_group_system_user_id_fkey` FOREIGN KEY (`system_user_id`) REFERENCES `usuarios` (`id`);

--
-- Limitadores para a tabela `usuarios_programas`
--
ALTER TABLE `usuarios_programas`
  ADD CONSTRAINT `system_user_program_system_program_id_fkey` FOREIGN KEY (`system_program_id`) REFERENCES `programas` (`id`),
  ADD CONSTRAINT `system_user_program_system_user_id_fkey` FOREIGN KEY (`system_user_id`) REFERENCES `usuarios` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
