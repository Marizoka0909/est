# SOS-Estudante 🎓

> Sistema web de organização e gestão acadêmica desenvolvido com arquitetura modular em PHP estruturado, banco de dados MySQL (PDO), Tailwind CSS e sessões nativas com foco em segurança, rastreabilidade e responsividade **Mobile-First**.

---

## 📌 1. Visão Geral e Objetivos

O **SOS-Estudante** centraliza a rotina escolar e universitária do estudante em um único ambiente intuitivo e seguro.

### Funcionalidades Principais (Core):
- **Painel Geral (Dashboard)**: Métricas em tempo real de tarefas pendentes, coeficiente de rendimento escolar, próximas provas e progresso por disciplina.
- **Disciplinas & Matérias**: Cadastro de matérias, código, professor, sala, cor temática, carga horária e média mínima para aprovação.
- **Tarefas & Atividades**: Controle de entregas escolares com prioridades (Baixa, Média, Alta, Urgente), status dinâmico e alertas de atraso.
- **Agenda Acadêmica**: Calendário e agendamento de exames, provas, seminários, entregas de trabalhos e aulas extras.
- **Boletim & Notas**: Lançamento de notas por avaliação com cálculo automático da média ponderada e status de aprovação.
- **Histórico & Auditoria (AuditLog)**: Rastreabilidade completa de todas as operações (CRIAR, ATUALIZAR, EXCLUIR, LOGIN) com valores anteriores e novos, IP e data/hora, sem armazenar dados sensíveis.
- **Gestão de Usuários & Perfil**: Autenticação segura com `password_hash()` e `password_verify()`, controle de sessão nativa e proteção CSRF.
- **Mensagens & Modais**: Toasts flutuantes para feedback de erros/sucessos e modais customizados que substituem 100% dos diálogos nativos (`alert()` e `confirm()`).

---

## 🛠️ 2. Stack Tecnológica

| Camada | Tecnologia / Padrão |
| :--- | :--- |
| **Backend** | PHP 7.4+ / PHP 8.x estruturado modular |
| **Persistência** | MySQL 5.7+ / 8.0+ utilizando PDO com Prepared Statements |
| **Sessões** | Sessões nativas do PHP com regeneração de ID contra session fixation |
| **Frontend** | HTML5 semântico, PHP para renderização, CSS3 |
| **Estilização** | Tailwind CSS (CDN oficial) priorizando **Mobile-First** |
| **Interatividade** | JavaScript Vanilla modular (Toasts, Modais acessíveis, Drawer mobile) |

---

## 📁 3. Estrutura do Projeto

O sistema adota uma arquitetura modular por responsabilidades, em conformidade com o item 4.2 da especificação:

```text
SOS-Estudante/
├── config/
│   ├── app.php                # Configurações globais, fuso horário e carregador .env
│   └── bd.php                 # Conexão PDO segura (Singleton) com MySQL
├── controllers/
│   ├── AuthController.php     # Login, cadastro e encerramento de sessão
│   ├── DashboardController.php# Agregação de métricas e indicadores
│   ├── SubjectController.php  # CRUD de disciplinas
│   ├── TaskController.php     # CRUD e toggle de tarefas
│   ├── EventController.php    # CRUD de provas e eventos
│   ├── GradeController.php    # CRUD de avaliações e boletim
│   ├── HistoryController.php  # Filtros e visualização de auditoria
│   └── ProfileController.php  # Perfil do estudante e troca de senha
├── services/
│   ├── AuthService.php        # Regras de autenticação e criptografia
│   ├── SubjectService.php     # Regras de disciplinas e médias
│   ├── TaskService.php        # Regras de tarefas e prazos
│   ├── EventService.php       # Regras da agenda acadêmica
│   ├── GradeService.php       # Cálculo ponderado de notas e aprovação
│   └── AuditService.php       # Higienização de dados e auditoria
├── models/
│   ├── User.php               # Acesso seguro a usuários
│   ├── Subject.php            # Acesso a disciplinas
│   ├── Task.php               # Acesso a tarefas
│   ├── Event.php              # Acesso a eventos
│   ├── Grade.php              # Acesso a notas
│   └── AuditLog.php           # Armazenamento e consulta de logs
├── middlewares/
│   ├── AuthMiddleware.php     # Proteção de rotas autenticadas e mensagens flash
│   └── CsrfMiddleware.php     # Geração e validação de tokens CSRF
├── views/
│   ├── layouts/
│   │   ├── header.php         # Head, Tailwind CSS e meta tags responsivas
│   │   ├── navbar.php         # Barra de navegação superior
│   │   ├── sidebar.php        # Menu lateral desktop e drawer mobile
│   │   └── footer.php         # Scripts e disparo automático de toasts
│   ├── auth/                  # Telas de login e cadastro
│   ├── dashboard/             # Painel geral
│   ├── subjects/              # Gestão de disciplinas e modais
│   ├── tasks/                 # Gestão de tarefas e prazos
│   ├── events/                # Agenda de provas e compromissos
│   ├── grades/                # Boletim escolar e avaliações
│   ├── history/               # Histórico e auditoria com diff de dados
│   ├── profile/               # Perfil e segurança
│   └── errors/                # Páginas 404 e 500
├── assets/
│   ├── css/custom.css         # Animações de toasts e modais
│   └── js/
│       ├── toast.js           # Gerenciador de notificações Toast
│       ├── modal.js           # Modais que substituem alert/confirm nativos
│       └── app.js             # Interatividade e drawer mobile
├── database/
│   ├── schema.sql             # DDL completo das tabelas e chaves estrangeiras
│   └── seed.sql               # Dados de demonstração realistas
├── routes/
│   └── web.php                # Despachante de rotas
├── logs/
│   ├── app.log                # Registro de erros internos do servidor
│   └── .gitkeep
├── storage/
│   └── .gitkeep
├── tests/
│   └── test_db_and_services.php # Suíte de testes automatizados
├── .env.example
├── .gitignore
├── PROJECT_SPEC.md
├── requisitos.md
└── index.php                  # Front Controller central
```

---

## 🚀 4. Instalação e Execução

### Pré-requisitos
- **PHP 7.4+** ou **PHP 8.x** com extensões `pdo`, `pdo_mysql`, `mbstring`, `json`, `session`.
- **MySQL 5.7+** ou **MySQL 8.0+** (pode ser via XAMPP, Laragon, WampServer ou Docker).

### Passo 1: Configurar o Banco de Dados
1. Inicie o serviço MySQL no seu ambiente local (ex: Painel de Controle do XAMPP).
2. Abra o phpMyAdmin ou terminal MySQL e execute o script de criação:
   ```sql
   -- No terminal MySQL ou phpMyAdmin:
   SOURCE database/schema.sql;
   ```
3. Para carregar os dados de demonstração (usuário de teste, matérias e tarefas):
   ```sql
   SOURCE database/seed.sql;
   ```

### Passo 2: Configurar o arquivo `.env`
Copie o arquivo `.env.example` para `.env`:
```bash
cp .env.example .env
```
Se necessário, altere as credenciais de conexão do MySQL:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=sos_estudante
DB_USER=root
DB_PASS=
```

### Passo 3: Iniciar o Servidor PHP
Você pode usar o servidor embutido do PHP:
```bash
php -S localhost:8000
```
Ou colocar a pasta dentro de `htdocs` (XAMPP) ou `www` (WampServer).

Acesse no navegador:
👉 **`http://localhost:8000`**

---

## 🔑 5. Credenciais de Teste Pré-configuradas

Caso importe o arquivo `database/seed.sql`, você pode acessar imediatamente:
- **E-mail**: `aluno@exemplo.com`
- **Senha**: `password123`

---

## 🧪 6. Execução de Testes Automatizados

Para validar os componentes de segurança (hash, CSRF, sanitização de auditoria e conexão):
```bash
php tests/test_db_and_services.php
```
Ou acesse via navegador:
👉 `http://localhost:8000/tests/test_db_and_services.php`

---

## 🔒 7. Políticas de Segurança e Auditoria

1. **Prevenção contra SQL Injection**: Uso estrito de PDO e Prepared Statements em todas as operações de banco.
2. **Criptografia de Senhas**: Utilização de `password_hash($senha, PASSWORD_DEFAULT)` e `password_verify()`. Senhas nunca são expostas em texto puro.
3. **Proteção CSRF**: Validação rigorosa de tokens em todos os formulários com método POST (`CsrfMiddleware`).
4. **Higienização de Auditoria**: O `AuditLog` filtra automaticamente campos sensíveis (senhas, tokens) antes de gravar em banco.
5. **Acessibilidade e Feedback**: Erros e confirmações não utilizam `alert()` ou `confirm()` nativos; utilizam Toasts e Modais personalizados responsivos.