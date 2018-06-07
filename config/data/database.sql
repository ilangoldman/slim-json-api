-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema upcash
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `upcash` ;

-- -----------------------------------------------------
-- Schema upcash
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `upcash` DEFAULT CHARACTER SET utf8 ;
USE `upcash` ;

-- -----------------------------------------------------
-- Table `upcash`.`empresa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`empresa` ;

CREATE TABLE IF NOT EXISTS `upcash`.`empresa` (
  `empresa` INT NOT NULL AUTO_INCREMENT,
  `user` VARBINARY(32) NOT NULL,
  `criado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email` VARCHAR(50) NULL,
  `nome` VARCHAR(45) NULL,
  `sobrenome` VARCHAR(45) NULL,
  `razao_social` VARCHAR(45) NULL,
  `nome_fantasia` VARCHAR(45) NULL,
  `cnpj` VARCHAR(15) NULL,
  `telefone_comercial` VARCHAR(15) NULL,
  `fundacao` DATE NULL,
  `funcionarios` INT NULL,
  `grupo_economico` VARCHAR(45) NULL,
  `pagina_web` VARCHAR(45) NULL,
  `rede_social` VARCHAR(45) NULL,
  `resumo_descricao` VARCHAR(144) NULL,
  `descricao` VARCHAR(500) NULL,
  `setor` VARCHAR(45) NULL,
  PRIMARY KEY (`empresa`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `eid_UNIQUE` ON `upcash`.`empresa` (`empresa` ASC);


-- -----------------------------------------------------
-- Table `upcash`.`pontuacao`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`pontuacao` ;

CREATE TABLE IF NOT EXISTS `upcash`.`pontuacao` (
  `pontuacao` INT NOT NULL AUTO_INCREMENT,
  `nivel` INT NULL,
  `pontos` INT NULL,
  `titulo` VARCHAR(45) NULL,
  `simbolo` VARCHAR(45) NULL,
  PRIMARY KEY (`pontuacao`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `upcash`.`investidor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`investidor` ;

CREATE TABLE IF NOT EXISTS `upcash`.`investidor` (
  `investidor` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pontuacao` INT NOT NULL,
  `criado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user` VARBINARY(32) NOT NULL,
  `pontos` INT NOT NULL,
  `email` VARCHAR(50) NULL,
  `nome` VARCHAR(45) NULL,
  `sobrenome` VARCHAR(45) NULL,
  `sexo` CHAR(1) NULL,
  `estado_civil` VARCHAR(45) NULL,
  `nome_mae` VARCHAR(80) NULL,
  `nome_pai` VARCHAR(80) NULL,
  `tel1` VARCHAR(15) NULL,
  `tel2` VARCHAR(15) NULL,
  `naturalidade` VARCHAR(45) NULL,
  `nacionalidade` VARCHAR(45) NULL,
  `cpf` VARCHAR(11) NULL,
  `data_nascimento` DATE NULL,
  `rg` VARCHAR(11) NULL,
  `orgao_emissor` VARCHAR(10) NULL,
  `estado_emissor` VARCHAR(10) NULL,
  `data_emissao` DATE NULL,
  `renda_mensal` DOUBLE NULL,
  `patrimonio` DOUBLE NULL,
  `ppe` TINYINT NULL,
  `doc_id` VARCHAR(45) NULL,
  `doc_residencia` VARCHAR(45) NULL,
  PRIMARY KEY (`investidor`, `pontuacao`),
  CONSTRAINT `fk_investidor_pontuacao1`
    FOREIGN KEY (`pontuacao`)
    REFERENCES `upcash`.`pontuacao` (`pontuacao`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `iid_UNIQUE` ON `upcash`.`investidor` (`investidor` ASC);

CREATE INDEX `fk_investidor_pontuacao1_idx` ON `upcash`.`investidor` (`pontuacao` ASC);


-- -----------------------------------------------------
-- Table `upcash`.`emprestimo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`emprestimo` ;

CREATE TABLE IF NOT EXISTS `upcash`.`emprestimo` (
  `emprestimo` INT NOT NULL AUTO_INCREMENT,
  `empresa` INT NOT NULL,
  `criado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `avalista` VARCHAR(45) NULL,
  `valor` INT NULL,
  `taxa` FLOAT NULL,
  `prazo` INT NULL,
  `valor_parcela` DOUBLE NULL,
  `status` INT NULL,
  `motivo` VARCHAR(5000) NULL,
  `faturamento` INT NULL,
  `prazo_medio_receber` INT NULL,
  `prazo_medio_pagar` INT NULL,
  PRIMARY KEY (`emprestimo`, `empresa`),
  CONSTRAINT `fk_emprestimos_empresa1`
    FOREIGN KEY (`empresa`)
    REFERENCES `upcash`.`empresa` (`empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_emprestimos_empresa1_idx` ON `upcash`.`emprestimo` (`empresa` ASC);


-- -----------------------------------------------------
-- Table `upcash`.`investimento`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`investimento` ;

CREATE TABLE IF NOT EXISTS `upcash`.`investimento` (
  `investimento` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `investidor` INT UNSIGNED NOT NULL,
  `emprestimo` INT NOT NULL,
  `criado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `taxa` FLOAT NULL,
  `valor` INT NULL,
  `status` INT NULL,
  `prazo` INT NULL,
  `valor_parcela` DOUBLE NULL,
  `contrato` VARCHAR(45) NULL,
  `comprovante` VARCHAR(45) NULL,
  PRIMARY KEY (`investimento`, `investidor`, `emprestimo`),
  CONSTRAINT `fk_investimentos_emprestimos1`
    FOREIGN KEY (`emprestimo`)
    REFERENCES `upcash`.`emprestimo` (`emprestimo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_investimentos_investidor1`
    FOREIGN KEY (`investidor`)
    REFERENCES `upcash`.`investidor` (`investidor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `invid_UNIQUE` ON `upcash`.`investimento` (`investimento` ASC);

CREATE INDEX `fk_investimentos_emprestimos1_idx` ON `upcash`.`investimento` (`emprestimo` ASC);

CREATE INDEX `fk_investimentos_investidor1_idx` ON `upcash`.`investimento` (`investidor` ASC);


-- -----------------------------------------------------
-- Table `upcash`.`amigo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`amigo` ;

CREATE TABLE IF NOT EXISTS `upcash`.`amigo` (
  `amigo` INT NOT NULL AUTO_INCREMENT,
  `investidor` INT UNSIGNED NULL,
  `empresa` INT NULL,
  `nome` VARCHAR(45) NULL,
  `email` VARCHAR(50) NULL,
  `status` INT NULL,
  `user` VARBINARY(32) NULL,
  PRIMARY KEY (`amigo`),
  CONSTRAINT `fk_amigo_investidor1`
    FOREIGN KEY (`investidor`)
    REFERENCES `upcash`.`investidor` (`investidor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_amigo_empresa1`
    FOREIGN KEY (`empresa`)
    REFERENCES `upcash`.`empresa` (`empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_amigo_investidor1_idx` ON `upcash`.`amigo` (`investidor` ASC);

CREATE INDEX `fk_amigo_empresa1_idx` ON `upcash`.`amigo` (`empresa` ASC);


-- -----------------------------------------------------
-- Table `upcash`.`beneficio`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`beneficio` ;

CREATE TABLE IF NOT EXISTS `upcash`.`beneficio` (
  `beneficio` INT NOT NULL AUTO_INCREMENT,
  `pontuacao` INT NOT NULL,
  `titulo` VARCHAR(45) NULL,
  `descricao` VARCHAR(250) NULL,
  PRIMARY KEY (`beneficio`, `pontuacao`),
  CONSTRAINT `fk_BENEFICIOS_CONQUISTAS1`
    FOREIGN KEY (`pontuacao`)
    REFERENCES `upcash`.`pontuacao` (`pontuacao`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_BENEFICIOS_CONQUISTAS1_idx` ON `upcash`.`beneficio` (`pontuacao` ASC);


-- -----------------------------------------------------
-- Table `upcash`.`mensagem`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`mensagem` ;

CREATE TABLE IF NOT EXISTS `upcash`.`mensagem` (
  `mensagem` INT NOT NULL AUTO_INCREMENT,
  `criado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `titulo` VARCHAR(45) NULL,
  `descricao` VARCHAR(1000) NULL,
  `data` DATETIME NULL,
  `status` TINYINT NULL,
  `categoria` INT NULL,
  PRIMARY KEY (`mensagem`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `upcash`.`parcela`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`parcela` ;

CREATE TABLE IF NOT EXISTS `upcash`.`parcela` (
  `parcela` INT NOT NULL AUTO_INCREMENT,
  `emprestimo` INT NOT NULL,
  `investimento` INT UNSIGNED NOT NULL,
  `parcela_empresa` INT NOT NULL,
  `criado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valor` DOUBLE NULL,
  `principal` DOUBLE NULL,
  `rendimentos` DOUBLE NULL,
  `multa` DOUBLE NULL,
  `ir` DOUBLE NULL,
  PRIMARY KEY (`parcela`, `emprestimo`, `investimento`, `parcela_empresa`),
  CONSTRAINT `fk_parcelas_emprestimos2`
    FOREIGN KEY (`emprestimo`)
    REFERENCES `upcash`.`emprestimo` (`emprestimo`)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_parcelas_investimentos1`
    FOREIGN KEY (`investimento`)
    REFERENCES `upcash`.`investimento` (`investimento`)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_parcelas_parcelas1`
    FOREIGN KEY (`parcela_empresa`)
    REFERENCES `upcash`.`parcela` (`parcela`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_parcelas_emprestimos2_idx` ON `upcash`.`parcela` (`emprestimo` ASC);

CREATE INDEX `fk_parcelas_investimentos1_idx` ON `upcash`.`parcela` (`investimento` ASC);

CREATE INDEX `fk_parcelas_parcelas1_idx` ON `upcash`.`parcela` (`parcela_empresa` ASC);


-- -----------------------------------------------------
-- Table `upcash`.`atividade`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`atividade` ;

CREATE TABLE IF NOT EXISTS `upcash`.`atividade` (
  `atividade` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(45) NULL,
  `descricao` VARCHAR(45) NULL,
  `pontos` INT NULL,
  PRIMARY KEY (`atividade`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `upcash`.`conquista`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`conquista` ;

CREATE TABLE IF NOT EXISTS `upcash`.`conquista` (
  `investidor` INT UNSIGNED NOT NULL,
  `atividade` INT NOT NULL,
  `criado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`investidor`, `atividade`),
  CONSTRAINT `fk_CONQUISTAS_INVESTIDOR_INVESTIDOR1`
    FOREIGN KEY (`investidor`)
    REFERENCES `upcash`.`investidor` (`investidor`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_CONQUISTAS_INVESTIDOR_CONQUISTAS1`
    FOREIGN KEY (`atividade`)
    REFERENCES `upcash`.`atividade` (`atividade`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_CONQUISTAS_INVESTIDOR_CONQUISTAS1_idx` ON `upcash`.`conquista` (`atividade` ASC);


-- -----------------------------------------------------
-- Table `upcash`.`endereco`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`endereco` ;

CREATE TABLE IF NOT EXISTS `upcash`.`endereco` (
  `endereco` INT NOT NULL AUTO_INCREMENT,
  `empresa` INT NULL,
  `investidor` INT UNSIGNED NULL,
  `criado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cep` INT NULL,
  `tipo` VARCHAR(10) NULL,
  `logradouro` VARCHAR(100) NULL,
  `numero` INT NULL,
  `complemento` VARCHAR(10) NULL,
  `bairro` VARCHAR(20) NULL,
  `cidade` VARCHAR(20) NULL,
  `estado` VARCHAR(20) NULL,
  `pais` VARCHAR(20) NULL,
  PRIMARY KEY (`endereco`),
  CONSTRAINT `fk_endereco_empresa1`
    FOREIGN KEY (`empresa`)
    REFERENCES `upcash`.`empresa` (`empresa`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_endereco_investidor1`
    FOREIGN KEY (`investidor`)
    REFERENCES `upcash`.`investidor` (`investidor`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `endereco_UNIQUE` ON `upcash`.`endereco` (`endereco` ASC);

CREATE INDEX `fk_endereco_empresa1_idx` ON `upcash`.`endereco` (`empresa` ASC);

CREATE INDEX `fk_endereco_investidor1_idx` ON `upcash`.`endereco` (`investidor` ASC);


-- -----------------------------------------------------
-- Table `upcash`.`conta_bancaria`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`conta_bancaria` ;

CREATE TABLE IF NOT EXISTS `upcash`.`conta_bancaria` (
  `conta_bancaria` INT NOT NULL AUTO_INCREMENT,
  `empresa` INT NULL,
  `investidor` INT UNSIGNED NULL,
  `criado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modificado` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `titular` VARCHAR(45) NULL,
  `banco` INT NULL,
  `tipo` INT NULL,
  `agencia` INT NULL,
  `conta` INT NULL,
  PRIMARY KEY (`conta_bancaria`),
  CONSTRAINT `fk_conta_bancaria_empresa1`
    FOREIGN KEY (`empresa`)
    REFERENCES `upcash`.`empresa` (`empresa`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_conta_bancaria_investidor1`
    FOREIGN KEY (`investidor`)
    REFERENCES `upcash`.`investidor` (`investidor`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_conta_bancaria_empresa1_idx` ON `upcash`.`conta_bancaria` (`empresa` ASC);

CREATE INDEX `fk_conta_bancaria_investidor1_idx` ON `upcash`.`conta_bancaria` (`investidor` ASC);


-- -----------------------------------------------------
-- Table `upcash`.`detalhe`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`detalhe` ;

CREATE TABLE IF NOT EXISTS `upcash`.`detalhe` (
  `detalhe` INT NOT NULL AUTO_INCREMENT,
  `emprestimo` INT NOT NULL,
  `tipo` INT NULL,
  `info` VARCHAR(45) NULL,
  `valor` FLOAT NULL,
  `descricao` VARCHAR(100) NULL,
  PRIMARY KEY (`detalhe`, `emprestimo`),
  CONSTRAINT `fk_detalhe_emprestimos1`
    FOREIGN KEY (`emprestimo`)
    REFERENCES `upcash`.`emprestimo` (`emprestimo`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_detalhe_emprestimos1_idx` ON `upcash`.`detalhe` (`emprestimo` ASC);


-- -----------------------------------------------------
-- Table `upcash`.`notificacao`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `upcash`.`notificacao` ;

CREATE TABLE IF NOT EXISTS `upcash`.`notificacao` (
  `notificacao` INT NOT NULL,
  `investidor` INT UNSIGNED NULL,
  `empresa` INT NULL,
  PRIMARY KEY (`notificacao`),
  CONSTRAINT `fk_user_notificacao_notificacao1`
    FOREIGN KEY (`notificacao`)
    REFERENCES `upcash`.`mensagem` (`mensagem`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_notificacao_investidor1`
    FOREIGN KEY (`investidor`)
    REFERENCES `upcash`.`investidor` (`investidor`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_notificacao_empresa1`
    FOREIGN KEY (`empresa`)
    REFERENCES `upcash`.`empresa` (`empresa`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_user_notificacao_notificacao1_idx` ON `upcash`.`notificacao` (`notificacao` ASC);

CREATE INDEX `fk_user_notificacao_investidor1_idx` ON `upcash`.`notificacao` (`investidor` ASC);

CREATE INDEX `fk_user_notificacao_empresa1_idx` ON `upcash`.`notificacao` (`empresa` ASC);

USE `upcash` ;

-- -----------------------------------------------------
-- Placeholder table for view `upcash`.`oportunidade`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `upcash`.`oportunidade` (`nome` INT, `emprestimo` INT, `empresa` INT, `criado` INT, `modificado` INT, `avalista` INT, `valor` INT, `taxa` INT, `prazo` INT, `valor_parcela` INT, `status` INT, `motivo` INT, `faturamento` INT, `prazo_medio_receber` INT, `prazo_medio_pagar` INT);

-- -----------------------------------------------------
-- Placeholder table for view `upcash`.`carteira`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `upcash`.`carteira` (`principal` INT, `rendimentos` INT, `total` INT, `num_investimentos` INT);

-- -----------------------------------------------------
-- View `upcash`.`oportunidade`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `upcash`.`oportunidade` ;
DROP TABLE IF EXISTS `upcash`.`oportunidade`;
USE `upcash`;
CREATE  OR REPLACE VIEW `oportunidade` AS
SELECT empresa.nome_fantasia as nome, e.* 
FROM emprestimo as e
LEFT JOIN empresa USING (empresa);

-- -----------------------------------------------------
-- View `upcash`.`carteira`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `upcash`.`carteira` ;
DROP TABLE IF EXISTS `upcash`.`carteira`;
USE `upcash`;
CREATE  OR REPLACE VIEW `carteira` AS
SELECT SUM(principal) as principal, sum(rendimentos) as rendimentos, sum(principal+rendimentos) as total, count(investimento) as num_investimentos
FROM parcela
WHERE emprestimo is null
GROUP BY investimento;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
