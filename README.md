<div align="center">

# 💅 NailBook

### Sistema Completo de Gerenciamento para Salões de Beleza

<p>
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white" alt="Vue.js" />
  <img src="https://img.shields.io/badge/PostgreSQL-14-316192?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL" />
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP" />
  <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap" />
</p>

**Sistema profissional desenvolvido para otimizar a gestão de salões de beleza, com foco em agendamentos, clientes e serviços.**

[📸 Screenshots](#-screenshots) • [🚀 Funcionalidades](#-funcionalidades) • [🛠️ Tecnologias](#️-tecnologias-utilizadas) • [💻 Instalação](#-instalação)

</div>

---

## 📋 Sobre o Projeto

O **NailBook** nasceu de uma necessidade real: facilitar o dia a dia de profissionais de estética e beleza. Desenvolvido com as melhores práticas de mercado, o sistema oferece uma solução completa e intuitiva para gestão de agendamentos, clientes e serviços.

### 🎯 Problema Resolvido

Antes do NailBook, profissionais de estética enfrentavam dificuldades com:
- ❌ Agendamentos em cadernos ou planilhas desorganizadas
- ❌ Conflitos de horários
- ❌ Dificuldade em acompanhar histórico de clientes
- ❌ Falta de controle sobre serviços prestados

### ✅ Solução Implementada

O NailBook oferece:
- ✨ **Interface intuitiva** e fácil de usar
- ⚡ **Detecção automática** de conflitos de horário
- 📊 **Dashboard com estatísticas** em tempo real
- 🔒 **Sistema seguro** com autenticação Laravel Sanctum
- 📱 **Design responsivo** que funciona em qualquer dispositivo
- 🎨 **Visual profissional** com identidade própria

---

## 🚀 Funcionalidades

### 🔐 Sistema de Autenticação
- Login seguro com Laravel Sanctum
- Autenticação baseada em tokens API
- Proteção de rotas no frontend e backend
- Tratamento automático de sessões expiradas

### 📊 Dashboard Interativo
- Visualização de agendamentos do dia em tempo real
- Contadores dinâmicos (clientes, agendamentos, serviços)
- Navegação rápida para principais funcionalidades
- Design moderno com gradientes e animações

### 📅 Gestão de Agendamentos
#### Novo Agendamento (Wizard Multi-Etapas)
- **Passo 1:** Seleção de cliente com busca
- **Passo 2:** Escolha múltipla de serviços com cálculo automático de valores
- **Passo 3:** Seleção de data e horário
  - ⚠️ **Detecção inteligente de conflitos** de horário
  - 💡 **Sugestão automática** de horários disponíveis
  - ✅ Validação de horários ocupados

#### Listagem de Agendamentos
- **Filtros Avançados:**
  - Por período (Hoje, Esta Semana, Este Mês, Próximo Mês, Todos, Personalizado)
  - Por status (Pendente, Confirmado, Concluído, Cancelado)
  - Busca por nome ou telefone do cliente
- **Visualização Agrupada:** Por data com labels contextuais ("Hoje", "Amanhã", dia da semana)
- **Gestão de Status:**
  - Confirmar agendamento (Pendente → Confirmado)
  - Concluir atendimento (Confirmado → Concluído)
  - Cancelar com modal de confirmação profissional
- **Contador dinâmico** de resultados filtrados

### 👥 Gerenciamento de Clientes
- CRUD completo (Create, Read, Update, Delete)
- Máscara automática para telefone brasileiro
- Busca e filtros
- Histórico de atendimentos
- Validação de dados

### 💅 Catálogo de Serviços
- 10 serviços pré-cadastrados via Seeder
- CRUD completo com modal profissional de exclusão
- Gestão de preços e durações
- Cards visuais organizados em grid

**Serviços pré-cadastrados:**
- Manicure (R$ 30,00 - 45min)
- Pedicure (R$ 35,00 - 60min)
- Manicure + Pedicure (R$ 60,00 - 90min)
- Esmaltação em Gel (R$ 50,00 - 60min)
- Unhas em Gel (R$ 80,00 - 120min)
- Unhas em Fibra (R$ 90,00 - 120min)
- Alongamento (R$ 100,00 - 150min)
- Blindagem (R$ 45,00 - 60min)
- Spa dos Pés (R$ 70,00 - 90min)
- Design de Unhas (R$ 40,00 - 30min)

### 👤 Perfil de Usuário
- Edição de informações pessoais (nome, email)
- Alteração segura de senha
- Interface com abas e design moderno
- Painel de informações do sistema

---

## 🛠️ Tecnologias Utilizadas

### Backend
- **Laravel 11** - Framework PHP moderno
- **PHP 8.3** - Última versão estável
- **PostgreSQL 14+** - Banco de dados relacional robusto
- **Laravel Sanctum** - Autenticação API baseada em tokens
- **Eloquent ORM** - Mapeamento objeto-relacional

### Frontend
- **Vue.js 3** - Framework JavaScript progressivo
- **Vue Router 4** - Gerenciamento de rotas SPA
- **Axios** - Cliente HTTP com interceptors
- **Bootstrap 5** - Framework CSS responsivo
- **Bootstrap Icons** - Biblioteca de ícones
- **Vite** - Build tool ultrarrápido

### Arquitetura
- **SPA (Single Page Application)** - Experiência fluida sem recarregamento
- **API RESTful** - Comunicação padronizada
- **Component-based** - Componentes reutilizáveis Vue
- **MVC Pattern** - Arquitetura Laravel

### DevOps & Ferramentas
- **Git** - Controle de versão
- **Composer** - Gerenciador de dependências PHP
- **NPM** - Gerenciador de pacotes Node.js
- **Migrations** - Versionamento de banco de dados
- **Seeders** - População de dados iniciais

---

## 🎨 Design & UX

### Identidade Visual
- **Cor Principal:** `#55165e` (Roxo elegante)
- **Gradientes:** Transições suaves de roxo
- **Tipografia:** Moderna e legível
- **Ícones:** Bootstrap Icons para consistência

### Experiência do Usuário
- ✨ **Animações fluidas** em transições
- 🎯 **Feedback visual** em todas as ações
- ⚡ **Carregamento otimizado** com loading states
- 📱 **Mobile-first** design responsivo
- 🎭 **Modais elegantes** para confirmações
- 🌈 **Status coloridos** para identificação rápida
  - 🟡 Pendente
  - 🔵 Confirmado
  - 🟢 Concluído
  - 🔴 Cancelado

---

## 📸 Screenshots

### Dashboard
Interface principal com estatísticas em tempo real e navegação rápida.

### Novo Agendamento
Wizard intuitivo de 3 passos com seleção múltipla de serviços e detecção de conflitos.

### Listagem de Agendamentos
Visualização completa com filtros avançados, agrupamento por data e gestão de status.

### Gestão de Clientes
CRUD completo com máscara de telefone e interface limpa.

### Catálogo de Serviços
Cards visuais com modal profissional de exclusão.

---

## 💻 Instalação

### Pré-requisitos

Certifique-se de ter instalado:
- **PHP** 8.3 ou superior
- **Composer** 2.x
- **Node.js** 18+ e NPM
- **PostgreSQL** 14+
- **Git**

### Passo a Passo

1. **Clone o repositório**
```bash
git clone https://github.com/soariix/NailBook.git
cd NailBook/nailbook
```

2. **Instale as dependências do PHP**
```bash
composer install
```

3. **Instale as dependências do Node.js**
```bash
npm install
```

4. **Configure o arquivo .env**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure o banco de dados no .env**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nailbook
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

6. **Execute as migrations e seeders**
```bash
php artisan migrate --seed
```
> Isso criará todas as tabelas e populará o sistema com 10 serviços pré-cadastrados.

7. **Crie um usuário administrador**
```bash
php artisan tinker
```
```php
User::create([
    'name' => 'Admin',
    'email' => 'admin@nailbook.com',
    'password' => bcrypt('senha123')
]);
exit
```

8. **Inicie o servidor Laravel**
```bash
php artisan serve
```

9. **Em outro terminal, compile os assets**
```bash
npm run dev
```

10. **Acesse o sistema**
```
http://localhost:8000
```

**Credenciais padrão:**
- Email: `admin@nailbook.com`
- Senha: `senha123`

---

## 🚀 Uso em Produção

Para compilar os assets para produção:

```bash
npm run build
```

Configure o `.env` para produção:
```env
APP_ENV=production
APP_DEBUG=false
```

---

## 🏗️ Estrutura do Projeto

```
nailbook/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php         # Autenticação e perfil
│   │   │   ├── AgendamentoController.php  # CRUD agendamentos
│   │   │   ├── ClienteController.php      # CRUD clientes
│   │   │   └── ServicoController.php      # CRUD serviços
│   │   └── Middleware/
│   └── Models/
│       ├── User.php
│       ├── Agendamento.php
│       ├── Cliente.php
│       └── Servico.php
├── database/
│   ├── migrations/                         # Versionamento do BD
│   └── seeders/
│       └── ServicosSeeder.php             # 10 serviços iniciais
├── resources/
│   ├── js/
│   │   ├── components/
│   │   │   ├── Login.vue                  # Tela de login
│   │   │   ├── Dashboard.vue              # Dashboard principal
│   │   │   ├── Layout.vue                 # Sidebar navigation
│   │   │   ├── NovoAgendamento.vue        # Wizard 3 passos
│   │   │   ├── Agendamentos.vue           # Listagem com filtros
│   │   │   ├── Clientes.vue               # CRUD clientes
│   │   │   ├── Servicos.vue               # CRUD serviços
│   │   │   └── Perfil.vue                 # Perfil do usuário
│   │   ├── router/
│   │   │   └── index.js                   # Rotas Vue + guards
│   │   ├── App.vue                        # Root component
│   │   └── app.js                         # Bootstrap Vue
│   └── views/
│       └── app.blade.php                  # SPA entry point
├── routes/
│   ├── api.php                            # API REST routes
│   └── web.php                            # Web routes
└── public/
    └── favicon.svg                        # Ícone personalizado
```

---

## 🔒 Segurança

- ✅ **Laravel Sanctum** para autenticação API
- ✅ **Tokens** armazenados em localStorage
- ✅ **Middleware** de autenticação em todas as rotas protegidas
- ✅ **Validação** de dados em todos os formulários
- ✅ **Proteção CSRF** nativa do Laravel
- ✅ **Senhas** criptografadas com bcrypt
- ✅ **Tratamento** de sessões expiradas (401 redirect)

---

## 📈 Melhorias Futuras

- [ ] Sistema de notificações por email/SMS
- [ ] Relatórios e gráficos de faturamento
- [ ] Integração com WhatsApp para lembretes
- [ ] Sistema de avaliação de serviços
- [ ] Controle de estoque de produtos
- [ ] Multi-tenancy para múltiplos salões
- [ ] App mobile (React Native)
- [ ] Modo escuro

---

## 🎓 Aprendizados Técnicos

Este projeto me permitiu aprofundar conhecimentos em:

### Backend
- Arquitetura RESTful API
- Autenticação stateless com Sanctum
- Relacionamentos Eloquent (hasMany, belongsTo)
- Migrations e Seeders
- Validação de requisições
- Resource Controllers

### Frontend
- Arquitetura de SPAs
- Gerenciamento de estado no Vue 3
- Vue Router com navigation guards
- Composição de componentes reutilizáveis
- Interceptors Axios para tratamento de erros
- Formulários multi-etapas (wizards)

### Boas Práticas
- Código limpo e organizado
- Componentização
- Separação de responsabilidades
- Validação client-side e server-side
- Feedback visual para o usuário
- Design responsivo mobile-first

---

## 💡 Diferenciais do Projeto

✨ **Solução Real**: Desenvolvido para resolver um problema real de um negócio real

🎯 **User-Centric**: Focado na experiência do usuário final

⚡ **Performance**: Otimizado com lazy loading e computed properties

🔧 **Manutenível**: Código organizado e bem documentado

🎨 **Visual**: Design profissional e identidade visual própria

🛡️ **Seguro**: Implementação correta de autenticação e proteção de rotas

📱 **Responsivo**: Funciona perfeitamente em desktop, tablet e mobile

---

## 🤝 Contato

Desenvolvido por **Sara Soares**

- 💼 LinkedIn: [linkedin.com/in/seu-perfil](https://linkedin.com/in/seu-perfil)
- 📧 Email: sarinhapachecosoares@gmail.com
- 💻 GitHub: [@soariix](https://github.com/soariix)
- 🐛 Issues: [GitHub Issues](https://github.com/soariix/NailBook/issues)

---

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

<div align="center">

### ⭐ Se este projeto foi útil para você, considere dar uma estrela!

**NailBook** - Transformando a gestão de salões de beleza 💅✨

---

**Stack:** Laravel 11 • Vue.js 3 • PostgreSQL • Bootstrap 5 • Vite

</div>
 
