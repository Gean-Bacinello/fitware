<h1 align="center">Fitware</h1>

<p align="center">
  <img src="https://img.shields.io/static/v1?label=STATUS&message=MVP%20FINALIZADO&color=GREEN&style=for-the-badge"/>
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white"/>
</p>

<p align="center">
  <b>Sistema de Gerenciamento para Academias com Foco em Usabilidade e IA</b>
</p>

<p align="center">
 <a href="#-sobre-o-projeto">Sobre</a> •
 <a href="#-funcionalidades">Funcionalidades</a> •
 <a href="#-layout">Layout</a> • 
 <a href="#-tecnologias">Tecnologias</a> • 
 <a href="#-como-executar">Como Executar</a> • 
 <a href="#-autores">Autores</a>
</p>

---

## Sobre o Projeto

O **Fitware** é um sistema web de gerenciamento voltado para academias de pequeno e médio porte. O projeto foi desenvolvido para solucionar a desorganização administrativa e a comunicação ineficaz sobre treinos, problemas comuns no setor.

Diferente de sistemas complexos focados apenas no financeiro, o Fitware prioriza a **experiência do usuário** e o **acompanhamento de treinos**. O sistema elimina o uso de fichas de papel e introduz inovações tecnológicas, como um **Assistente Virtual** baseado em Inteligência Artificial para tirar dúvidas dos alunos.

## Funcionalidades

O sistema foi desenvolvido em módulos para atender Gestores, Instrutores e Alunos:

- [x] **Autenticação e Perfis:** Login seguro e controle de acesso (Admin, Instrutor, Aluno).
- [x] **Painel Administrativo (Dashboard):** Visualização de métricas como total de alunos e exercícios cadastrados.
- [x] **Gestão de Cadastros:** CRUD completo para gerenciamento de alunos e instrutores.
- [x] **Gestão de Exercícios:** Banco de dados de exercícios com categorização por grupo muscular.
- [x] **Criação de Treinos:** Interface para instrutores montarem e atribuírem fichas personalizadas.
- [x] **Visualização do Aluno:** Interface responsiva para o aluno consultar seu treino no celular durante a execução.
- [x] **Personal Trainer Virtual (IA):** Chat integrado com a API do **Google Gemini**, permitindo que o aluno tire dúvidas sobre execução e saúde em tempo real.

## Layout

O design foi pensado para ser limpo e intuitivo (Design Minimalista). Abaixo, algumas telas do protótipo funcional:

> **Nota:** As imagens abaixo ilustram o funcionamento do sistema.

### Visão do Gestor
<p align="center">
  <img src="assets/dashboard.png" alt="Dashboard Administrativo" width="400">
  <img src="assets/cadastro.png" alt="Tela de Cadastro" width="400">
</p>

### Visão do Aluno e IA
<p align="center">
  <img src="assets/treino_mobile.png" alt="Visualização de Treino Mobile" width="250">
  <img src="assets/chat_ia.png" alt="Assistente Virtual com IA Gemini" width="250">
</p>

## Tecnologias Utilizadas

O projeto utiliza uma arquitetura MVC moderna para garantir estabilidade e facilidade de manutenção:

* **Linguagem:** [PHP 8+](https://www.php.net/)
* **Framework:** [Laravel](https://laravel.com/) (MVC, Eloquent ORM, Blade Templates)
* **Banco de Dados:** [MySQL](https://www.mysql.com/)
* **Front-end:** HTML5, CSS3, JavaScript
* **Bootstrap:** [Bootstrap](https://getbootstrap.com/) Framework para css
* **API Externa:** Integração com [Google Gemini](https://ai.google.dev/) (Inteligência Artificial)

## Como Executar

Siga as etapas abaixo.

### Pré-requisitos
* [Git](https://git-scm.com)
* [PHP](https://www.php.net/downloads.php)
* [Composer](https://getcomposer.org/doc/00-intro.md)
* [Mysql](https://dev.mysql.com/downloads/)
* [API Gemini](https://aistudio.google.com/api-keys)
* [mailtrap](https://mailtrap.io/)

### Passo a Passo

1.  **Clone o repositório**
    ```bash
    git clone [https://github.com/Gean-Bacinello/fitware.git](https://github.com/Gean-Bacinello/fitware.git)
    cd fitware
    ```

2.  **Configure as variáveis de ambiente**
    Duplique o arquivo de exemplo e configure suas chaves (mailtrap e API Gemini).
    ```bash
    cp .env.example .env
    ```
    *Edite o arquivo `.env` para inserir sua `GEMINI_API_KEY`.*


3.  **Recuperação de senha**
    Acesse o site * [mailtrap](https://mailtrap.io/) crie uma conta, depois em sandboxes acesse my sandbox, copie suas informações para o .env
    ```bash
    MAIL_MAILER=smtp
    MAIL_HOST=sandbox.smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME= "usuario do mailtrap"
    MAIL_PASSWORD="senah do mailtrap"  
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=no-reply@Fitware.com
    MAIL_FROM_NAME="${APP_NAME}"
    ```

4.  **Altere o .env**
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=db_fitware
    DB_USERNAME="Seu usuario do mysql"
    DB_PASSWORD="sua senha do mysql"
    ```

5.  **Crie o banco dados no Mysql**
    ```bash
    CREATE database db_fitware;
    ```


6.  **Instale as dependências do projeto**
    Acesse a pasta do projeto para rodar o Composer:
    ```bash
    composer install
    ```

7.  **Gere a chave da aplicação e rode as migrações**
    ```bash
    php artisan key:generate
    php artisan migrate
    ```
8.  **Inicie o Sistema**
    ```bash
    php artisan serve
    ```

9.  **Acesse o sistema**
    Abra o navegador e acesse: `http://localhost:8000`



## Autores

Projeto desenvolvido pelos discentes do Curso Superior de Tecnologia em Análise e Desenvolvimento de Sistemas da **Fatec Ourinhos**.


| [<img src="https://avatars.githubusercontent.com/u/placeholder?v=4" width=100><br><sub>Gean Corrêa Bacinello</sub>](#) | [<img src="https://avatars.githubusercontent.com/u/placeholder?v=4" width=100><br><sub>Rafael A. J. de Oliveira</sub>](#) | [<img src="https://avatars.githubusercontent.com/u/placeholder?v=4" width=100><br><sub>Rafael Kendi Uchida</sub>](#) |
| :---: | :---: | :---: |

**Orientador:** Prof. Me. Wellington Eufrasio Camargo

## Licença

Este projeto está sob a licença MIT. Consulte o arquivo [LICENSE](LICENSE) para mais detalhes.

---
<p align="center">
  Desenvolvido com 💙 para modernizar a gestão fitness.
</p>