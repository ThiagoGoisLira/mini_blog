# 🚀 Mini-Blog PRO

Um sistema de blog completo desenvolvido como projeto prático para consolidar conhecimentos em desenvolvimento Full-Stack (Frontend e Backend). Este projeto apresenta uma arquitetura clássica web, utilizando PHP puro e MySQL.

## 📌 Visão Geral

O Mini-Blog PRO permite a criação, leitura, edição e exclusão (CRUD) de postagens, além de um sistema público de comentários. O painel administrativo é protegido por um sistema de autenticação de usuários, garantindo que apenas administradores possam gerenciar o conteúdo.

## 🛠️ Tecnologias Utilizadas

**Frontend:**
* HTML5 & CSS3
* JavaScript
* [Bootstrap 5](https://getbootstrap.com/) (Para um design responsivo e moderno)
* Bootstrap Icons

**Backend & Dados:**
* PHP 8 (Lógica de negócios e gerenciamento de sessões)
* PDO (PHP Data Objects) para conexão segura com o banco
* MySQL (Banco de dados relacional)

## ✨ Funcionalidades

* **Sistema de Autenticação:** Login e Logout seguros com senhas criptografadas (`bcrypt`).
* **Proteção de Rotas:** Bloqueio de acesso a páginas administrativas para usuários não logados.
* **CRUD de Postagens:** O administrador pode criar, visualizar, editar e excluir posts.
* **Sistema de Comentários:** Visitantes podem ler as postagens e deixar comentários vinculados via Chave Estrangeira (Relacionamento 1:N).
* **Prevenção de Ataques:** Proteção contra SQL Injection (usando *Prepared Statements* do PDO) e XSS (usando `htmlspecialchars`).

## ⚙️ Como executar o projeto localmente

### Pré-requisitos
* Ter um servidor local instalado (Recomendo o [XAMPP](https://www.apachefriends.org/pt_br/index.html)).