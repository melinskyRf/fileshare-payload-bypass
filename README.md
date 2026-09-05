# FileSharePayloadBypass

Sistema de compartilhamento de arquivos com autenticação, controle de acesso por tokens e gerenciamento de uploads.

## Funcionalidades

- Login com autenticação (admin/convidado)
- Upload de arquivos (.exe, .zip) com limite de 3GB
- Download seguro via tokens temporários (expiração configurável)
- Visualização em tabela ou grid
- Busca e ordenação de arquivos
- Sistema de notas administrativas
- Temas (Claro, Escuro, Colorido)
- Histórico de tokens com status (ativo/expirado/usado)

## Tecnologias

- PHP 8+
- HTML/CSS/JavaScript
- Tailwind CSS
- Font Awesome
- Armazenamento via arquivos JSON

## Como Rodar

1. Coloque a pasta `project` em um servidor com PHP (ex: XAMPP, WAMP)
2. Acesse `index.php` no navegador
3. Login padrão:
   - **Admin:** `admin` / `admin123`
   - **Convidado:** `guest` / `guest123`

## Estrutura

```
project/
├── index.php          # Página principal
├── auth.php           # Autenticação
├── config.php         # Configurações e helpers
├── actions.php        # Ações (renomear, excluir)
├── upload.php         # Upload de arquivos
├── download.php       # Download via token
├── token_manager.php  # Gerenciamento de tokens
├── login.php          # Formulário de login
├── files_table.php    # Visualização em tabela
├── files_grid.php     # Visualização em grid
├── styles.css         # Estilos customizados
├── files.json         # Banco de dados de arquivos
├── tokens.json        # Banco de dados de tokens
└── uploads/           # Pasta de uploads
```

## Autor

**Rafael Melinsky** - [GitHub](https://github.com/melinskyRf)
