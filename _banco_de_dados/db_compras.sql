-- db_compras::MySQL 5.7

USE mysql;

CREATE DATABASE db_compras;

USE db_compras;

-- Table clientes

CREATE TABLE `clientes`
(
  `id` Bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` Varchar(100),
  `cpf` Varchar(100),
  `rg` Varchar(100),
  `genero` Varchar(100),
  `nascimento` Date,
  `rua` Varchar(100),
  `bairro` Varchar(100),
  `cidade` Varchar(100),
  `email` Varchar(100),
  `dataalteracao` Datetime,
  `usuarioalteracao` Varchar(100),
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
AUTO_INCREMENT = 1
ROW_FORMAT = Dynamic;

-- Table produtos

CREATE TABLE `produtos`
(
  `id` Bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` Varchar(100),
  `preco` Decimal(12,2) UNSIGNED,
  `quantidade` Int UNSIGNED,
  `dataalteracao` Datetime,
  `usuarioalteracao` Varchar(100),
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
AUTO_INCREMENT = 1
ROW_FORMAT = Dynamic;

-- Table vendas

CREATE TABLE `vendas`
(
  `id` Bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `clientes_id` Bigint UNSIGNED,
  `dataalteracao` Datetime,
  `usuarioalteracao` Varchar(100),
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
AUTO_INCREMENT = 1
ROW_FORMAT = Dynamic;

CREATE INDEX `IX_fk_clientes_vendas`
ON `vendas` (`clientes_id`);

-- Table itens_vendas

CREATE TABLE `itens_vendas`
(
  `id` Bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendas_id` Bigint UNSIGNED,
  `produtos_id` Bigint UNSIGNED,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB
AUTO_INCREMENT = 1
ROW_FORMAT = Dynamic;

CREATE INDEX `IX_fk_vendas_itens_vendas`
ON `itens_vendas` (`vendas_id`);

CREATE INDEX `IX_fk_produtos_itens_vendas`
ON `itens_vendas` (`produtos_id`);

-- Create relationships section -------------------------------------------------

ALTER TABLE `itens_vendas`
ADD CONSTRAINT `fk_produtos_itens_vendas`
FOREIGN KEY (`produtos_id`)
REFERENCES `produtos` (`id`)
ON DELETE RESTRICT
ON UPDATE RESTRICT;

ALTER TABLE `itens_vendas`
ADD CONSTRAINT `fk_vendas_itens_vendas`
FOREIGN KEY (`vendas_id`)
REFERENCES `vendas` (`id`)
ON DELETE RESTRICT
ON UPDATE RESTRICT;

ALTER TABLE `vendas`
ADD CONSTRAINT `fk_clientes_vendas`
FOREIGN KEY (`clientes_id`)
REFERENCES `clientes` (`id`)
ON DELETE RESTRICT
ON UPDATE RESTRICT;
