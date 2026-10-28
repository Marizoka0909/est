-- =============================================================================
-- SOS-Estudante - Dados Iniciais de Demonstração (Seed)
-- Usuário de Teste:
-- Email: aluno@exemplo.com
-- Senha: password123
-- =============================================================================

USE `sos_estudante`;

-- Inserir usuário demo (senha: password123)
-- Hash gerado com password_hash('password123', PASSWORD_BCRYPT)
INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `instituicao`, `curso`, `status`) 
VALUES (
  1, 
  'Carlos Eduardo Silva', 
  'aluno@exemplo.com', 
  '$2y$10$tM3NqT/sHkWlF7k0G7yPCO3M5t2yJ4o0pA0d8u1s4e2n1v5a3c9Oe', 
  'Universidade Federal de Tecnologia', 
  'Ciência da Computação', 
  'ativo'
) ON DUPLICATE KEY UPDATE `nome` = VALUES(`nome`);

-- Inserir Disciplinas
INSERT INTO `disciplinas` (`id`, `usuario_id`, `nome`, `codigo`, `professor`, `sala`, `cor`, `semestre`, `carga_horaria`, `media_minima`, `status`) VALUES
(1, 1, 'Algoritmos e Estruturas de Dados', 'CC101', 'Prof. Dr. Marcos Lima', 'Lab 03', '#4F46E5', '2026.2', 80, 7.00, 'em_andamento'),
(2, 1, 'Banco de Dados Relacionais', 'CC204', 'Profa. Dra. Ana Paula', 'Sala 204', '#059669', '2026.2', 60, 7.00, 'em_andamento'),
(3, 1, 'Engenharia de Software', 'CC302', 'Prof. Roberto Santos', 'Auditório B', '#D97706', '2026.2', 60, 7.00, 'em_andamento'),
(4, 1, 'Cálculo Diferencial e Integral I', 'MAT101', 'Prof. Hamilton Souza', 'Sala 102', '#DC2626', '2026.1', 90, 6.00, 'aprovado')
ON DUPLICATE KEY UPDATE `nome` = VALUES(`nome`);

-- Inserir Tarefas
INSERT INTO `tarefas` (`id`, `usuario_id`, `disciplina_id`, `titulo`, `descricao`, `data_entrega`, `prioridade`, `status`) VALUES
(1, 1, 1, 'Implementar Árvore Binária de Busca', 'Desenvolver em C/C++ os métodos de inserção, remoção e percursos em ordem, pré-ordem e pós-ordem.', DATE_ADD(NOW(), INTERVAL 3 DAY), 'alta', 'em_andamento'),
(2, 1, 2, 'Modelagem do Esquema Conceitual MER', 'Criar o diagrama entidade-relacionamento do sistema SOS Estudante no brModelo.', DATE_ADD(NOW(), INTERVAL 5 DAY), 'urgente', 'pendente'),
(3, 1, 3, 'Documento de Requisitos de Software', 'Escrever a especificação de casos de uso e matriz de rastreabilidade.', DATE_ADD(NOW(), INTERVAL 8 DAY), 'media', 'pendente'),
(4, 1, 1, 'Lista 01 de Complexidade Assintótica', 'Exercícios de Notação Big-O da página 45 a 52.', DATE_SUB(NOW(), INTERVAL 2 DAY), 'baixa', 'concluida')
ON DUPLICATE KEY UPDATE `titulo` = VALUES(`titulo`);

-- Inserir Eventos / Agenda
INSERT INTO `eventos` (`id`, `usuario_id`, `disciplina_id`, `titulo`, `tipo`, `data_inicio`, `data_fim`, `local`, `descricao`) VALUES
(1, 1, 1, 'Prova 1 - Estruturas Lineares', 'prova', DATE_ADD(NOW(), INTERVAL 7 DAY), DATE_ADD(NOW(), INTERVAL 7 DAY) + INTERVAL 2 HOUR, 'Lab 03', 'Conteúdo: Listas encadeadas, pilhas, filas e vetores dinâmicos.'),
(2, 1, 2, 'Apresentação do Projeto de Banco de Dados', 'apresentacao', DATE_ADD(NOW(), INTERVAL 12 DAY), DATE_ADD(NOW(), INTERVAL 12 DAY) + INTERVAL 1 HOUR, 'Sala 204', 'Apresentação do esquema relacional normalizado.'),
(3, 1, 3, 'Entrega da Sprint 1', 'trabalho', DATE_ADD(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 10 DAY) + INTERVAL 3 HOUR, 'Online / Teams', 'Envio do repositório Git com os primeiros módulos.')
ON DUPLICATE KEY UPDATE `titulo` = VALUES(`titulo`);

-- Inserir Notas
INSERT INTO `notas` (`id`, `usuario_id`, `disciplina_id`, `titulo`, `peso`, `nota_obtida`, `nota_maxima`, `data_avaliacao`, `observacoes`) VALUES
(1, 1, 1, 'Trabalho Prático 1', 1.00, 9.50, 10.00, DATE_SUB(NOW(), INTERVAL 10 DAY), 'Ótima estruturação do código'),
(2, 1, 1, 'Mini-teste Teórico', 0.50, 8.00, 10.00, DATE_SUB(NOW(), INTERVAL 5 DAY), 'Conceitos de filas circulares'),
(3, 1, 2, 'Laboratório SQL DDL', 1.00, 10.00, 10.00, DATE_SUB(NOW(), INTERVAL 8 DAY), 'Scripts executados sem erros'),
(4, 1, 4, 'Avaliação Final de Cálculo', 2.00, 7.80, 10.00, DATE_SUB(NOW(), INTERVAL 60 DAY), 'Nota final do semestre anterior')
ON DUPLICATE KEY UPDATE `titulo` = VALUES(`titulo`);

-- Inserir Auditoria inicial
INSERT INTO `auditoria` (`usuario_id`, `operacao`, `recurso`, `recurso_id`, `descricao`, `valores_anteriores`, `valores_posteriores`, `status_resultado`, `ip_address`) VALUES
(1, 'LOGIN', 'usuario', 1, 'Login realizado com sucesso no sistema', NULL, '{"email": "aluno@exemplo.com"}', 'SUCESSO', '127.0.0.1'),
(1, 'CRIAR', 'disciplina', 1, 'Criou a disciplina Algoritmos e Estruturas de Dados', NULL, '{"nome": "Algoritmos e Estruturas de Dados", "codigo": "CC101"}', 'SUCESSO', '127.0.0.1'),
(1, 'CRIAR', 'tarefa', 1, 'Criou a tarefa Implementar Árvore Binária de Busca', NULL, '{"titulo": "Implementar Árvore Binária de Busca", "prioridade": "alta"}', 'SUCESSO', '127.0.0.1');
