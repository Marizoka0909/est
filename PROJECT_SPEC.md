# SOS-Estudante - Especificação Técnica e Arquitetura

Este documento estabelece as diretrizes de desenvolvimento, regras de negócio e arquitetura do **SOS-Estudante**.

---

## 1. Visão Geral
O **SOS-Estudante** é um sistema web moderno e responsivo voltado para a organização da rotina acadêmica de estudantes.
Ele centraliza:
- Gestão de Matérias/Disciplinas
- Tarefas acadêmicas (Kanban/Lista com prioridades e prazos)
- Eventos e Provas (Agenda acadêmica)
- Lançamento e acompanhamento de Notas e Médias com cálculo de aprovação
- Histórico e Auditoria completa de operações (quem fez, o que fez, quando, valores anteriores e novos)
- Gestão de Usuários e Perfil com autenticação segura e sessões nativas

---

## 2. Padrões de Segurança
- **Banco de Dados**: PDO com Prepared Statements em 100% das operações SQL.
- **Senhas**: Hashes criptográficos gerados com `password_hash()` e validados com `password_verify()`. Nenhuma senha em texto puro.
- **CSRF**: Verificação de token CSRF em todas as requisições que alteram dados (POST/PUT/DELETE).
- **Auditoria**: Logs detalhados em banco de dados sem expor senhas ou tokens.
- **Sanitização**: Filtros e escape HTML em todas as saídas de dados (`htmlspecialchars`).

---

## 3. UI/UX
- **Design System**: Tailwind CSS (Mobile-First).
- **Interação**: Notificações flutuantes tipo **Toast** para sucessos, avisos e erros.
- **Modais Customizados**: Substituição completa dos diálogos nativos (`alert`, `confirm`, `prompt`) por modais HTML acessíveis.

---

## 4. Estrutura Modular
- `config/`: Conexão com banco (`bd.php`) e configurações da aplicação (`app.php`).
- `controllers/`: Recebimento e orquestração de requisições.
- `services/`: Regras de negócio, cálculos acadêmicos e auditoria.
- `models/`: Camada de persistência e acesso a dados via PDO.
- `middlewares/`: Autenticação e validação CSRF.
- `views/`: Telas e componentes em HTML5/PHP.
- `assets/`: Estilos CSS adicionais e scripts JavaScript (Toast, Modal, App).
- `routes/`: Roteamento limpo das requisições.
- `logs/`: Logs de erro do servidor.
- `database/`: Scripts DDL (`schema.sql`) e dados iniciais (`seed.sql`).
