<div align="center">

# 💅 NailBook

### Sistema de Agendamento para Manicures

<p>
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white" alt="Vue.js" />
  <img src="https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL" />
  <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap" />
</p>

**NailBook** é um sistema de gerenciamento de agendamentos desenvolvido especialmente para profissionais de manicure e pedicure, com interface intuitiva e fácil de usar.

[Demonstração](#) • [Instalação](#-instalação) • [Documentação](#-funcionalidades)

</div>

---

## 📋 Sobre o Projeto

O NailBook foi criado para facilitar o dia a dia de profissionais de estética, oferecendo uma solução simples e eficiente para:

- ✅ Gerenciar agendamentos de clientes
- ✅ Cadastrar e organizar informações de clientes
- ✅ Controlar serviços oferecidos
- ✅ Visualizar agenda diária
- ✅ Interface amigável e intuitiva

## 🎨 Design

O sistema utiliza uma paleta de cores elegante e profissional:

- **Cor Principal:** `#55165e` (Roxo escuro)
- **Cor Secundária:** Branco
- **Interface:** Limpa, moderna e fácil de navegar

## ✨ Funcionalidades

### 🔐 Autenticação
- Login seguro com email e senha
- Controle de acesso ao sistema

### 📊 Dashboard
- Visualização dos agendamentos do dia
- Resumo de clientes cadastrados
- Acesso rápido às principais funcionalidades

### 👥 Gerenciamento de Clientes
- Cadastro completo de clientes
- Lista de clientes cadastrados
- Histórico de atendimentos

### 📅 Agendamentos
- Criar novos agendamentos
- Visualizar agendamentos por data
- Editar e cancelar agendamentos

### 💼 Serviços
- Cadastro de serviços oferecidos
- Definição de preços e duração
- Organização de categorias

## 🛠️ Tecnologias Utilizadas

### Backend
- **Laravel 11** - Framework PHP
- **PostgreSQL** - Banco de dados
- **PHP 8.2+** - Linguagem de programação

### Frontend
- **Vue.js 3** - Framework JavaScript
- **Bootstrap 5** - Framework CSS
- **Axios** - Cliente HTTP
- **Vite** - Build tool

## 📦 Instalação

### Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Node.js 18+ e NPM
- PostgreSQL 14+

### Passo a passo

1. **Clone o repositório**
```bash
git clone https://github.com/soariix/NailBook.git
cd NailBook
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

6. **Execute as migrations**
```bash
php artisan migrate
```

7. **Compile os assets**
```bash
npm run dev
```

8. **Inicie o servidor**
```bash
php artisan serve
```

Acesse: `http://localhost:8000`

## 🚀 Uso em Produção

Para compilar os assets para produção:

```bash
npm run build
```

## 📱 Screenshots

*Em breve*

## 🤝 Contribuindo

Contribuições são sempre bem-vindas! Sinta-se à vontade para:

1. Fazer um Fork do projeto
2. Criar uma branch para sua feature (`git checkout -b feature/MinhaFeature`)
3. Commit suas mudanças (`git commit -m 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/MinhaFeature`)
5. Abrir um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Autor

Desenvolvido com 💜 por [soariix](https://github.com/soariix)

---

## 📞 Suporte

Se você tiver alguma dúvida ou sugestão, entre em contato:

- 📧 Email: [seu-email@exemplo.com]
- 🐛 Issues: [GitHub Issues](https://github.com/soariix/NailBook/issues)

---

<div align="center">
  
**NailBook** - Gerenciamento de Agendamentos Simplificado 💅

</div>

