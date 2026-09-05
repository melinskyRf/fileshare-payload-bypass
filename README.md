# FileSharePayloadBypass

> **Conteúdo Educativo / Versão Anterior** - Primeira versão do sistema de compartilhamento de arquivos com tokens. Utilizado para estudo de autenticação, gerenciamento de arquivos e sistemas de tokens no PHP.

[![PHP](https://img.shields.io/badge/PHP-8%2B-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](#)

## Sobre o Projeto

O **FileSharePayloadBypass** é a versão inicial do sistema de compartilhamento de arquivos que evoluiu para o [Bacuk v5 Final](https://github.com/melinskyRf/Bacuk). Este repositório é mantido para fins educacionais e de referência.

### Objetivo Educacional

Este projeto foi desenvolvido para estudar:
- **Autenticação por sessão** em PHP
- **Controle de acesso** por roles (admin/convidado)
- **Sistema de tokens** temporários para downloads
- **Upload e gerenciamento** de arquivos
- **Armazenamento em JSON** como alternativa a banco de dados
- **Interface responsiva** com Tailwind CSS
- **Tema toggle** (Claro/Escuro/Colorido)

### Versão Final

> 📌 Para a versão completa e production-ready, acesse: **[Bacuk v5 Final](https://github.com/melinskyRf/Bacuk)**

## Funcionalidades

### Autenticação
- Login com dois níveis: Admin e Convidado
- Sessão PHP com validação em todas as páginas
- Mock de usuários no código (auth.php)

### Sistema de Tokens
- Geração de tokens únicos com `bin2hex(random_bytes(16))`
- Tokens de uso único com expiração configurável
- Validação em tempo real
- Histórico de uso

### Gerenciamento de Arquivos
- Upload de arquivos `.exe` e `.zip`
- Limite de 3GB por arquivo
- Visualização em tabela e grid
- Busca e ordenação
- Renomear e excluir (admin)

### Interface
- 3 temas: Claro, Escuro, Colorido
- Tailwind CSS para estilização
- Modais para ações
- Design responsivo

## Tecnologias Utilizadas

| Tecnologia | Versão | Uso |
|------------|--------|-----|
| PHP | 8+ | Backend e lógica |
| HTML5 | - | Estrutura |
| CSS3 | - | Estilos |
| JavaScript | ES6+ | Interação |
| Tailwind CSS | 3.x | Framework CSS |
| Font Awesome | 6.x | Ícones |
| JSON | - | Armazenamento |

## Como Rodar

### Pré-requisitos
- PHP 8.0+
- Servidor com PHP (XAMPP, WAMP, Laragon)

### Instalação

1. Clone o repositório:
```bash
git clone https://github.com/melinskyRf/fileshare-payload-bypass.git
```

2. Coloque a pasta `project` no diretório do servidor:
```
XAMPP: C:\xampp\htdocs\fileshare\
WAMP:  C:\wamp64\www\fileshare\
```

3. Acesse no navegador:
```
http://localhost/fileshare/
```

### Credenciais

| Usuário | Senha | Permissão |
|---------|-------|-----------|
| `admin` | `admin123` | Administrador |
| `guest` | `guest123` | Convidado |

## Estrutura do Projeto

```
project/
├── index.php              # Página principal
├── auth.php               # Autenticação (mock de usuários)
├── login.php              # Formulário de login
├── config.php             # Configurações e funções auxiliares
├── actions.php            # Ações de gerenciamento
├── upload.php             # Upload de arquivos
├── download.php           # Download via token
├── token_manager.php      # Gerenciamento de tokens
├── files_table.php        # Visualização em tabela
├── files_grid.php         # Visualização em grid
├── styles.css             # Estilos customizados
├── files.json             # Banco de arquivos
├── tokens.json            # Banco de tokens
├── notas.json             # Notas administrativas
├── README.md              # Documentação
└── uploads/               # Arquivos enviados
```

## Diferenças Versão Anterior vs v5 Final

| Funcionalidade | Esta Versão | v5 Final |
|----------------|-------------|----------|
| Autenticação | Mock (hardcoded) | Mock (hardcoded) |
| Tokens | Básico | Exporação configurável |
| Notas | Não | Sim |
| UI | Completa | Melhorada |
| README | Básico | Detalhado |

## Variáveis de Ambiente / Configuração

As configurações estão no arquivo `config.php`:

```php
define('FILES_DB', 'files.json');      // Banco de arquivos
define('TOKENS_DB', 'tokens.json');    // Banco de tokens
define('UPLOAD_DIR', 'uploads/');      // Pasta de uploads
define('MAX_FILE_SIZE', 3 * 1024 * 1024 * 1024); // 3GB
define('ALLOWED_EXTENSIONS', ['exe', 'zip']);      // Formatos aceitos
```

## Autor

**Rafael Melinsky** - Desenvolvedor Full Stack

- GitHub: [@melinskyRf](https://github.com/melinskyRf)
- Site: [devmelinsky.site](https://devmelinsky.site)

---

> 📌 Esta é a versão anterior. Para a versão final, acesse: [Bacuk v5 Final](https://github.com/melinskyRf/Bacuk)
