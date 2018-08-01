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
-- Estrutura para tabela `usuarios`
--

  CREATE TABLE `usuarios` (
    `user_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `user_nome` LONGTEXT NOT NULL,
    `user_cpf` VARCHAR(11) NOT NULL,
    `user_telefone` VARCHAR(11) NOT NULL,
    `user_data` DATE NOT NULL,
    `user_status` TINYINT(1) NOT NULL,
    `user_cadastro` DATETIME NOT NULL,
    `tipo_id` INT(1) NOT NULL
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
    `motivo_nome` LONGTEXT NOT NULL,
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  INSERT INTO `motivos` (`ocup_nome`) VALUES
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
    `cred_nome` LONGTEXT NOT NULL,
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
-- Estrutura para tabela `usuario_ocupacao`
--

  CREATE TABLE `usuario_ocupacao` (
    `user_ocup_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `user_id` INT(1) NOT NULL,
    `ocup_id` INT(1) NOT NULL,
    `user_estado` DATE DEFAULT NULL,
    `user_cidade` DATE DEFAULT NULL,
    `user_empresa` DATE DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_motivo`
--
  CREATE TABLE `usuario_motivo` (
    `user_motivo_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `user_id` INT(1) NOT NULL,
    `motivo_id` INT(1) NOT NULL,
    `motivo_tratamento` LONGTEXT DEFAULT NULL,
    `data_festa` DATE DEFAULT NULL,
    `limite_cartao` DOUBLE DEFAULT NULL,
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_credito`
--

  CREATE TABLE `usuario_motivo` (
    `user_credito_id` INT(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `user_id` INT(1) NOT NULL,
    `cred_id` INT(1) NOT NULL,
    `limite_cartao` DOUBLE DEFAULT NULL,
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;