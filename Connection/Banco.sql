-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 01/08/2018 às 17:55
-- Versão do servidor: 10.1.24-MariaDB-cll-lve
-- Versão do PHP: 5.6.30


--
-- Banco de dados: `Cred_Steps`
--

-- --------------------------- Estrutura -----------------------------

--
-- Estrutura para tabela `bancos`
--

  CREATE TABLE `bancos` (
    `banco_id` INT(10) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cod` INT(4) NOT NULL,
    `banco` VARCHAR(150) NOT NULL
  ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura para tabela `enderecos`
--

CREATE TABLE `enderecos` (
  `id` int(11) NOT NULL,
  `cep` varchar(11) NOT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `cidade` varchar(120) DEFAULT NULL,
  `bairro` varchar(120) DEFAULT NULL,
  `logradouro` varchar(255) DEFAULT NULL,
  `latitude` varchar(30) DEFAULT NULL,
  `longitude` varchar(30) DEFAULT NULL,
  `ibge_cod_uf` varchar(5) DEFAULT NULL,
  `ibge_cod_cidade` varchar(10) DEFAULT NULL,
  `area_cidade_km2` varchar(20) DEFAULT NULL,
  `ddd` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ocupacao`
--

  CREATE TABLE `ocupacao` (
    `ocup_id` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `ocup_nome` longtext NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  INSERT INTO `ocupacao` (`ocup_nome`) VALUES
  ('Aposentado'),
  ('Pensionista'),
  ('Forças armadas'),
  ('Funcionário público federal'),
  ('Funcionário público estadual'),
  ('Funcionário público municipal'),
  ('Funcionário empresa privada'),
  ('Autônomo/Liberal'),
  ('Não trabalha/Desempregado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `motivos`
--

  CREATE TABLE `motivos` (
    `motivo_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `motivo_nome` LONGTEXT NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  INSERT INTO `motivos` (`motivo_nome`) VALUES
  ('Tratamento médico'),
  ('Tratamento odontológico'),
  ('Construção/Reforma ou decoração'),
  ('Viagem'),
  ('Festa/Casamento'),
  ('Pagar uma dívida'),
  ('Empréstimo pessoal'),
  ('Pagar cartão de crédito'),
  ('Pagar cheque especial'),
  ('Curso ou intercâmbio'),
  ('Outro');

-- --------------------------------------------------------

--
-- Estrutura para tabela `conseguir_credito`
--

  CREATE TABLE `conseguir_credito` (
    `cred_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cred_nome` LONGTEXT NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 INSERT INTO `conseguir_credito` (`cred_nome`) VALUES
  ('Crédito consignado para aposentado, pensionista, funcionário público ou forças armadas'),
  ('Pelo limite do cartão de crédito'),
  ('Desconto em folha de pagamento'),
  ('Cheque'),
  ('Boleto'),
  ('Débito em conta');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente_status`
--

  CREATE TABLE `cliente_status` (
    `cli_status` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cli_status_nome` INT(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

   INSERT INTO `cliente_status` (`cli_status_nome`) VALUES
  ('Pendente'),
  ('E-Mail enviado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

  CREATE TABLE `clientes` (
    `cli_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cli_nome` LONGTEXT NOT NULL,
    `cli_indicacao` LONGTEXT NOT NULL,
    `cli_cpf` VARCHAR(11) NOT NULL,
    `cli_telefone` VARCHAR(11) NOT NULL,
    `cli_nascimento` DATE NOT NULL,
    `cli_email` LONGTEXT DEFAULT NULL,
    `cli_emprestimo` DOUBLE NOT NULL,
    `cli_parcelas` INT(11) NOT NULL,
    `cli_status` TINYINT(1) NOT NULL,
    `cli_cadastro` DATETIME NOT NULL,
    `tipo_id` INT(1) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente_ocupacao`
--

  CREATE TABLE `cliente_ocupacao` (
    `cli_ocup_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cli_id` INT(11) NOT NULL,
    `ocup_id` INT(11) NOT NULL,
    `cli_estado` LONGTEXT DEFAULT NULL,
    `cli_cidade` INT(11) DEFAULT NULL,
    `cli_empresa` LONGTEXT DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente_motivo`
--
  CREATE TABLE `cliente_motivo` (
    `cli_motivo_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cli_id` INT(11) NOT NULL,
    `motivo_id` INT(11) NOT NULL,
    `motivo_tratamento` LONGTEXT DEFAULT NULL,
    `data_festa` DATE DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente_credito`
--

  CREATE TABLE `cliente_credito` (
    `cli_credito_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cli_id` INT(11) NOT NULL,
    `cred_id` INT(11) NOT NULL,
    `limite_cartao` DOUBLE DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente_financeiro`
--

  CREATE TABLE `cliente_financeiro` (
    `cli_financeiro_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cli_id` INT(11) NOT NULL,
    `spc` TINYINT(1) NOT NULL,
    `cheque` TINYINT(1) NOT NULL,
    `chequeDev` TINYINT(1) DEFAULT NULL,
    `emprego` INT(1) NOT NULL,
    `rendaComprovada` INT(1) NOT NULL,
    `bank_possui` INT(1) NOT NULL,
    `bank_id` INT(11) DEFAULT NULL,
    `bank_tempo_conta` INT(1) DEFAULT NULL,
    `bank_agencia` LONGTEXT DEFAULT NULL,
    `bank_conta` LONGTEXT DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente_parentesco`
--

  CREATE TABLE `cliente_parentesco` (
    `cli_parentesco_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cli_id` INT(11) DEFAULT NULL,
    `grau` INT(1) DEFAULT NULL,
    `proximidade` LONGTEXT DEFAULT NULL,
    `nome` LONGTEXT DEFAULT NULL,
    `cpf` VARCHAR(11) DEFAULT NULL,
    `telefone` VARCHAR(1) DEFAULT NULL,
    `nascimento` DATE DEFAULT NULL,
    `ocupacao` INT(11) DEFAULT NULL,
    `estado` LONGTEXT DEFAULT NULL,
    `cidade` INT(11) DEFAULT NULL,
    `empresa` LONGTEXT DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente_estadual_municipal`
--

  CREATE TABLE `cliente_estadual_municipal` (
    `cli_estadual_mun_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cli_id` INT(11) NOT NULL,
    `margemOption` INT(1) NOT NULL,
    `margem` LONGTEXT DEFAULT NULL,
    `matricula` LONGTEXT DEFAULT NULL,
    `password` LONGTEXT DEFAULT NULL,
    `imageName` LONGTEXT DEFAULT NULL,
    `imageUrl` LONGTEXT DEFAULT NULL,
    `imageFile` LONGTEXT DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente_estadual_municipal`
--

  CREATE TABLE `cliente_finalize` (
    `cli_finalize_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `cli_id` INT(11) NOT NULL,
    `rg` LONGTEXT DEFAULT NULL,
    `cpf` LONGTEXT DEFAULT NULL,
    `compResidencia` LONGTEXT DEFAULT NULL,
    `contraCheque` LONGTEXT DEFAULT NULL,
    `carteiraTrabalho` LONGTEXT DEFAULT NULL,
    `impostoRenda` LONGTEXT DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;