-- =============================================================================
-- SOS-Estudante - Estrutura do Banco de Dados MySQL
-- Compatível com MySQL 5.7+ e MySQL 8.0+
-- =============================================================================

CREATE DATABASE IF NOT EXISTS `sos_estudante` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `sos_estudante`;

-- -----------------------------------------------------------------------------
-- Tabela: usuarios
-- Armazena os dados dos estudantes com credenciais criptografadas
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `senha` VARCHAR(255) NOT NULL,
  `instituicao` VARCHAR(150) NULL,
  `curso` VARCHAR(150) NULL,
  `foto` VARCHAR(255) NULL,
  `status` ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabela: disciplinas (Matérias)
-- Armazena as matérias/cadeiras do estudante
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `disciplinas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NOT NULL,
  `nome` VARCHAR(120) NOT NULL,
  `codigo` VARCHAR(30) NULL,
  `professor` VARCHAR(120) NULL,
  `sala` VARCHAR(60) NULL,
  `cor` VARCHAR(20) NOT NULL DEFAULT '#4F46E5',
  `semestre` VARCHAR(30) NULL,
  `carga_horaria` INT NULL,
  `media_minima` DECIMAL(4,2) NOT NULL DEFAULT 7.00,
  `status` ENUM('em_andamento', 'aprovado', 'reprovado') NOT NULL DEFAULT 'em_andamento',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_disciplinas_usuario` FOREIGN KEY (`usuario_id`) 
    REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabela: tarefas
-- Centraliza atividades escolares, trabalhos e listas de exercícios
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tarefas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NOT NULL,
  `disciplina_id` INT NULL,
  `titulo` VARCHAR(180) NOT NULL,
  `descricao` TEXT NULL,
  `data_entrega` DATETIME NULL,
  `prioridade` ENUM('baixa', 'media', 'alta', 'urgente') NOT NULL DEFAULT 'media',
  `status` ENUM('pendente', 'em_andamento', 'concluida') NOT NULL DEFAULT 'pendente',
  `concluida_em` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_tarefas_usuario` FOREIGN KEY (`usuario_id`) 
    REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tarefas_disciplina` FOREIGN KEY (`disciplina_id`) 
    REFERENCES `disciplinas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabela: eventos (Agenda Acadêmica)
-- Provas, exames, apresentações de seminários e reuniões de grupo
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `eventos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NOT NULL,
  `disciplina_id` INT NULL,
  `titulo` VARCHAR(180) NOT NULL,
  `tipo` ENUM('prova', 'trabalho', 'apresentacao', 'aula', 'reuniao', 'outro') NOT NULL DEFAULT 'prova',
  `data_inicio` DATETIME NOT NULL,
  `data_fim` DATETIME NULL,
  `local` VARCHAR(120) NULL,
  `descricao` TEXT NULL,
  `lembrete_ativo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_eventos_usuario` FOREIGN KEY (`usuario_id`) 
    REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_eventos_disciplina` FOREIGN KEY (`disciplina_id`) 
    REFERENCES `disciplinas` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabela: notas (Boletim e Avaliações)
-- Registro de notas, pesos e avaliações por matéria
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notas` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NOT NULL,
  `disciplina_id` INT NOT NULL,
  `titulo` VARCHAR(120) NOT NULL,
  `peso` DECIMAL(4,2) NOT NULL DEFAULT 1.00,
  `nota_obtida` DECIMAL(4,2) NOT NULL,
  `nota_maxima` DECIMAL(4,2) NOT NULL DEFAULT 10.00,
  `data_avaliacao` DATE NULL,
  `observacoes` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_notas_usuario` FOREIGN KEY (`usuario_id`) 
    REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_notas_disciplina` FOREIGN KEY (`disciplina_id`) 
    REFERENCES `disciplinas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Tabela: auditoria (Histórico de Operações e Auditoria)
-- Registra operações (CRIAR, ATUALIZAR, EXCLUIR, LOGIN) com valores anteriores
-- e posteriores para rastreabilidade, sem expor senhas/tokens.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `auditoria` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario_id` INT NULL,
  `operacao` VARCHAR(30) NOT NULL,
  `recurso` VARCHAR(50) NOT NULL,
  `recurso_id` INT NULL,
  `descricao` VARCHAR(255) NOT NULL,
  `valores_anteriores` JSON NULL,
  `valores_posteriores` JSON NULL,
  `status_resultado` ENUM('SUCESSO', 'FALHA') NOT NULL DEFAULT 'SUCESSO',
  `ip_address` VARCHAR(45) NULL,
  `user_agent` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_auditoria_usuario` FOREIGN KEY (`usuario_id`) 
    REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices para otimização de consultas por usuário e data
CREATE INDEX `idx_tarefas_usuario_status` ON `tarefas` (`usuario_id`, `status`);
CREATE INDEX `idx_eventos_usuario_data` ON `eventos` (`usuario_id`, `data_inicio`);
CREATE INDEX `idx_notas_disciplina` ON `notas` (`disciplina_id`);
CREATE INDEX `idx_auditoria_usuario_data` ON `auditoria` (`usuario_id`, `created_at`);
