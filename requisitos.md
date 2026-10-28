# SOS-Estudante

## Objetivos


### Stack Tecnlógico
- Backend: PHP estruturado com sessões nativas
- Banco de dados: MySQL (PDO para segurança)
- Frontend: HTML5, PHP, CSS, Tailwind CSS

#### Regras de negócio (CORE)
## 1. Objetivos do Projeto

O **SOS-Estudante** é um sistema web de organização acadêmica desenvolvido para centralizar informações, facilitar o acompanhamento das atividades escolares e ajudar estudantes a gerenciar sua rotina de estudos.

O sistema deverá oferecer uma interface simples, intuitiva, responsiva e acessível, permitindo que os usuários organizem suas atividades acadêmicas em um único ambiente.

### 1.1 Objetivos específicos

- Facilitar a organização da rotina acadêmica.
- Centralizar tarefas, compromissos e informações dos estudantes.
- Permitir o gerenciamento de contas de usuários com autenticação segura.
- Registrar alterações realizadas no sistema para possibilitar auditorias futuras.
- Fornecer histórico das atividades e operações realizadas.
- Apresentar mensagens claras de sucesso e erro.
- Garantir uma interface responsiva, priorizando dispositivos móveis.
- Manter uma arquitetura modular que permita manutenção e expansão futuras.
- Facilitar a inclusão de novas funcionalidades sem exigir a reestruturação completa do sistema.

## 2. Stack Tecnológica

### Backend
- PHP estruturado.
- Sessões nativas do PHP para autenticação e gerenciamento de sessões.
- Organização modular dos scripts e das regras de negócio.

### Banco de dados
- MySQL.
- PDO para conexão e execução segura de consultas SQL.

### Frontend
- HTML5.
- PHP para renderização das páginas.
- CSS.
- Tailwind CSS para estilização e responsividade.
- JavaScript para interações da interface.

## 3. Regras de Negócio (Core)

### 3.1 Gerenciamento de usuários

- O sistema deverá permitir o gerenciamento das contas de usuários.
- As funcionalidades privadas deverão exigir autenticação.
- Cada usuário deverá possuir uma identificação única.
- O acesso às funcionalidades deverá respeitar as permissões atribuídas ao usuário.
- O sistema deverá validar os dados recebidos antes de processá-los.
- Operações que envolvam dados privados deverão verificar se o usuário possui autorização para executá-las.

### 3.2 Segurança de senhas

- As senhas deverão ser armazenadas utilizando hash criptográfico apropriado para senhas.
- Em PHP, utilizar `password_hash()` para gerar o hash e `password_verify()` para validar a senha informada.
- Nunca armazenar senhas em texto puro.
- Nunca registrar senhas em logs ou históricos.
- As credenciais deverão ser verificadas no backend.

### 3.3 Histórico e auditoria

O sistema deverá possuir uma página de histórico que permita consultar as operações realizadas pelos usuários.

Deverá registrar, conforme aplicável:

- Usuário responsável pela operação.
- Tipo de operação realizada.
- Recurso ou registro afetado.
- Data e horário da operação.
- Valores anteriores e posteriores, quando necessários para auditoria.
- Resultado da operação.
- Informações adicionais relevantes para investigação de alterações.

As operações relevantes deverão ser registradas de maneira consistente, incluindo criações, atualizações e exclusões de registros.

**Regras obrigatórias:**

- Os registros de auditoria não deverão armazenar senhas, tokens ou outras credenciais.
- O histórico deverá possuir controle de acesso.
- Os usuários somente poderão consultar os históricos autorizados.
- Os registros de auditoria não deverão ser alteráveis por usuários comuns.
- Falhas no registro de operações críticas deverão ser tratadas adequadamente.
- As informações registradas deverão ser suficientes para identificar quem realizou a alteração e quando ela ocorreu.

### 3.4 Integridade dos dados

- Os dados deverão ser validados antes da gravação no banco.
- Operações de atualização e exclusão deverão verificar a existência do registro.
- O sistema deverá apresentar mensagens adequadas quando um recurso não existir.
- As regras de negócio deverão ser aplicadas no backend, independentemente das validações existentes na interface.
- Operações relacionadas que precisem manter consistência deverão utilizar transações SQL quando apropriado.

## 4. Regras Globais de Desenvolvimento

### 4.1 Banco de dados e segurança SQL

- Utilizar PDO para todas as conexões e consultas ao MySQL.
- Utilizar prepared statements para consultas que recebam dados externos.
- Nunca concatenar diretamente dados fornecidos pelo usuário em comandos SQL.
- Validar os dados antes de executar operações no banco.
- Utilizar transações quando necessário para preservar a integridade dos dados.
- Manter as credenciais do banco fora do código-fonte público.

### 4.2 Organização do código

O sistema não deverá ser desenvolvido como um monólito desorganizado.

Os arquivos deverão ser separados por responsabilidade, mantendo distintas as camadas de acesso a dados, regras de negócio, controle de requisições e apresentação.

Estrutura inicial sugerida:

```text
SOS-Estudante/
├── config/
│   └── bd.php
├── controllers/
├── services/
├── models/
├── middlewares/
├── views/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── routes/
├── logs/
├── storage/
├── tests/
├── .env.example
├── .gitignore
├── PROJECT_SPEC.md
└── README.md
```

Responsabilidades:

- `config/bd.php`: configuração e conexão com o banco de dados.
- `controllers/`: recebimento e tratamento das requisições.
- `services/`: implementação das regras de negócio.
- `models/`: acesso e manipulação dos dados.
- `middlewares/`: autenticação, autorização e verificações compartilhadas.
- `views/`: páginas e componentes de interface em HTML5/PHP.
- `assets/`: arquivos CSS, JavaScript, imagens e outros recursos estáticos.
- `routes/`: organização das rotas, quando aplicável.
- `logs/`: registros operacionais, com acesso restrito.
- `storage/`: arquivos e dados que não devem ficar diretamente expostos ao público.
- `tests/`: testes automatizados.

A estrutura poderá ser adaptada conforme a evolução do projeto, desde que a separação de responsabilidades seja preservada.

### 4.3 Qualidade do código

- Manter o código limpo, legível e consistente.
- Utilizar nomes claros para arquivos, funções, variáveis e classes.
- Evitar duplicação de código.
- Criar componentes e funções reutilizáveis quando apropriado.
- Comentar apenas as regras ou lógicas que realmente precisem de explicação adicional.
- Evitar arquivos excessivamente grandes que concentrem responsabilidades distintas.
- Preservar as funcionalidades existentes durante novas implementações.

### 4.4 Interface e responsividade

- Utilizar Tailwind CSS para estilização.
- Adotar a abordagem **Mobile First**.
- Garantir compatibilidade com smartphones, tablets e computadores.
- Evitar rolagem horizontal desnecessária.
- Utilizar elementos acessíveis, com labels, contraste adequado e navegação por teclado.
- Apresentar estados de carregamento, sucesso, erro e ausência de dados quando aplicáveis.
- Manter uma identidade visual consistente em todas as páginas.

### 4.5 Mensagens e feedback

As mensagens apresentadas ao usuário deverão ser claras, objetivas e adequadas à situação.

- Utilizar notificações do tipo **Toast** para informar sucessos, erros e avisos.
- Utilizar modais personalizados para confirmações e interações que exijam atenção do usuário.
- Não utilizar caixas de diálogo nativas do navegador, como `alert()`, `confirm()` e `prompt()`, como mecanismo padrão de interação.
- Substituir essas caixas por componentes personalizados quando necessário.
- Informar erros de validação próximos aos campos correspondentes.
- Nunca apresentar detalhes internos do servidor, consultas SQL, stack traces ou informações sensíveis ao usuário final.
- Registrar detalhes técnicos de erros nos logs apropriados, sem expor credenciais.

### 4.6 Tratamento de erros

- Tratar exceções no backend.
- Utilizar respostas e mensagens consistentes.
- Diferenciar erros de validação, autenticação, autorização e falhas internas.
- Evitar que erros inesperados interrompam a aplicação sem tratamento.
- Não considerar uma operação concluída até que sua execução tenha sido confirmada.
- Registrar falhas relevantes para facilitar a investigação e a manutenção.

## 5. Autenticação e Sessões

- Utilizar sessões nativas do PHP.
- Regenerar o identificador da sessão após a autenticação bem-sucedida.
- Implementar encerramento seguro de sessão no logout.
- Validar a autenticação em todas as páginas privadas.
- Configurar cookies de sessão com opções de segurança adequadas ao ambiente.
- Implementar proteção contra CSRF nas operações que alterem dados.
- Validar as permissões no servidor.
- Não confiar exclusivamente na interface para restringir o acesso.

## 6. Configuração e Ambiente

- Criar um arquivo `.env.example` com as variáveis necessárias.
- Não enviar senhas, chaves ou credenciais reais ao GitHub.
- Separar as configurações de desenvolvimento e produção.
- Utilizar configurações seguras para a conexão com o MySQL.
- Documentar as dependências e os requisitos de execução.
- Evitar caminhos absolutos específicos de uma máquina.
- Preparar o sistema para implantação em servidor compatível com PHP e MySQL.

## 7. Testes e Critérios de Aceitação

Uma funcionalidade será considerada concluída quando:

1. A interface estiver implementada, quando aplicável.
2. As regras de negócio estiverem implementadas no backend.
3. Os dados forem tratados de forma segura.
4. A autenticação e as permissões estiverem funcionando.
5. Os erros relevantes estiverem tratados.
6. As mensagens apresentadas ao usuário estiverem adequadas.
7. A interface estiver responsiva.
8. Os testes relevantes forem executados.
9. A documentação estiver atualizada.
10. A implementação não comprometer funcionalidades existentes.

## 8. Diretrizes para agentes de IA

Qualquer agente de programação que trabalhar no SOS-Estudante deverá:

1. Ler este `PROJECT_SPEC.md` antes de realizar alterações.
2. Inspecionar a estrutura e o código existentes.
3. Identificar funcionalidades já implementadas.
4. Planejar alterações antes de modificar arquivos importantes.
5. Respeitar a stack PHP, MySQL, PDO e sessões nativas.
6. Não introduzir frameworks ou tecnologias incompatíveis sem justificativa.
7. Não substituir funcionalidades existentes sem autorização ou necessidade documentada.
8. Executar testes relevantes após as alterações.
9. Atualizar a documentação quando necessário.
10. Informar claramente o que foi implementado, o que falta e quais decisões estão pendentes.

## 9. Princípio Final

O SOS-Estudante deverá ser desenvolvido como um sistema **seguro, modular, responsivo, auditável e preparado para evolução**.

A prioridade é entregar funcionalidades reais, com regras de negócio aplicadas no backend e dados protegidos, evitando soluções superficiais que funcionem apenas visualmente.

Toda implementação deverá respeitar os objetivos do projeto, a segurança das informações, a integridade dos dados e a facilidade de manutenção futura.




##### Regras Globais
- Use sempre PDO para conexão e queries no MySQL para evitar SQL Injections
- Mantenha o código limpo e comente apenas logicas complexas.
- Separe os arquivos de forma lógica: um arquivo para conexão com a base (bd.php) e scripts de backend isolados e views em HTML5/PHP, nunca faça o sistema como um monolito, deixe sempre separados todas as regras para facilitar os futuros upgrades.
 - Estilize as telas em Tailwind de forma responsiva priorizando o MobileFrist.
 - Retorne sempre as mensagens de erros de forma claras na interface para o usuário (TOAST)
 - sempre trate as mensagens de caixa de mensagens nativas do navegador em um MODAL
