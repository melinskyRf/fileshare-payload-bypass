<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FileSharePayloadeBypass</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script>
        // Tema inicial baseado no localStorage
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
    <style>
        /* Ajuste para Tailwind dark mode */
        .dark .bg-white { background-color: #1a202c !important; }
        .dark .text-gray-800 { color: #f7fafc !important; }
        .dark .text-gray-700 { color: #e2e8f0 !important; }
        .dark .text-gray-600 { color: #cbd5e1 !important; }
        .dark .bg-gray-100 { background-color: #111827 !important; }
        .dark .bg-gray-50 { background-color: #22223b !important; }
        .dark input, .dark select, .dark textarea {
            background-color: #2d3748 !important;
            color: #f7fafc !important;
            border-color: #4a5568 !important;
        }
        .dark .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0,0,0,0.7), 0 4px 6px -2px rgba(0,0,0,0.6) !important; }

        /* Tema colorido */
        .colorful {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 50%, #21d4fd 100%) !important;
        }
        .colorful .bg-white { background: rgba(255,255,255,0.85) !important; }
        .colorful .text-gray-800 { color: #1a202c !important; }
        .colorful .text-gray-700 { color: #3b0764 !important; }
        .colorful .text-gray-600 { color: #7c3aed !important; }
        .colorful .bg-gray-100 { background: rgba(255,255,255,0.7) !important; }
        .colorful .bg-gray-50 { background: rgba(255,255,255,0.5) !important; }
        .colorful input, .colorful select, .colorful textarea {
            background-color: #fffbe7 !important;
            color: #3b0764 !important;
            border-color: #f7971e !important;
        }
        .colorful .shadow-lg { box-shadow: 0 10px 15px -3px rgba(33,212,253,0.3), 0 4px 6px -2px rgba(247,151,30,0.2) !important; }
        .colorful .gradient-bg {
            background: linear-gradient(90deg, #f7971e 0%, #ffd200 50%, #21d4fd 100%) !important;
        }

        /* Card login para cada tema */
        .theme-card-light {
            background-color: #fff !important;
            color: #1a202c !important;
        }
        .theme-card-dark {
            background-color: #1a202c !important;
            color: #f7fafc !important;
        }
        .theme-card-colorful {
            background: linear-gradient(135deg, #f7971e 0%, #ffd200 50%, #21d4fd 100%) !important;
            color: #3b0764 !important;
            box-shadow: 0 10px 15px -3px rgba(33,212,253,0.3), 0 4px 6px -2px rgba(247,151,30,0.2) !important;
        }
        .theme-card-colorful input,
        .theme-card-colorful select,
        .theme-card-colorful textarea {
            background-color: #fffbe7 !important;
            color: #3b0764 !important;
            border-color: #f7971e !important;
        }
        .theme-card-colorful .gradient-bg {
            background: linear-gradient(90deg, #f7971e 0%, #ffd200 50%, #21d4fd 100%) !important;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex items-center justify-center dark:bg-gray-900 transition-colors duration-300">
    <!-- Seletor de tema centralizado -->
    <div class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50">
        <div class="relative inline-block">
            <button onclick="toggleThemeDropdown()" type="button"
                class="flex items-center gap-2 px-6 py-2 rounded-full shadow-lg bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 text-white font-semibold text-base transition-all duration-300 hover:scale-105 hover:from-blue-600 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:from-gray-700 dark:via-gray-900 dark:to-gray-700 dark:hover:from-gray-800 dark:hover:to-gray-900"
                id="theme-toggle-btn">
                <span id="theme-btn-label"><i class="fas fa-sun"></i> Claro</span>
                <i class="fas fa-chevron-down ml-2"></i>
            </button>
            <div id="theme-dropdown" class="hidden absolute left-1/2 -translate-x-1/2 mt-2 w-44 rounded-lg shadow-lg bg-white dark:bg-gray-800 colorful:bg-white ring-1 ring-black ring-opacity-5 z-50">
                <button onclick="setTheme('light')" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition rounded-t-lg">
                    <i class="fas fa-sun"></i> Claro
                </button>
                <button onclick="setTheme('dark')" class="w-full flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-moon"></i> Escuro
                </button>
                <button onclick="setTheme('colorful')" class="w-full flex items-center gap-2 px-4 py-2 text-yellow-700 hover:bg-yellow-100 transition rounded-b-lg">
                    <i class="fas fa-palette"></i> Colorido
                </button>
            </div>
        </div>
    </div>
    <div id="login-card" class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md mx-4 dark:bg-gray-800 theme-card-light">
        <div class="text-center mb-8">
            <div class="flex justify-center items-center space-x-2 mb-4">
                <i class="fas fa-cloud-upload-alt text-3xl text-blue-600"></i>
                <h1 class="text-3xl font-bold text-gray-800">FileSharePayloade</h1>
            </div>
            <p class="text-gray-600">Faça login para acessar o sistema</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="auth.php">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    Nome de Usuário
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                       id="username" name="username" type="text" placeholder="usuário" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Senha
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" 
                       id="password" name="password" type="password" placeholder="******************" required>
            </div>
            <div class="flex items-center justify-between">
                <button class="gradient-bg hover:opacity-90 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" 
                        type="submit">
                    Entrar
                </button>
            </div>
            <div class="mt-6 p-4 bg-gray-50 rounded-lg dark:bg-gray-700">
                <p class="text-center text-gray-600 text-sm font-medium mb-2">Contas de Teste:</p>
                <div class="text-center text-gray-500 text-xs space-y-1">
                    <p><strong>Admin:</strong> admin | senha: admin123</p>
                    <p><strong>Convidado:</strong> guest | senha: guest123</p>
                </div>
            </div>
        </form>
    </div>
    <script>
        // Sincroniza o tema entre abas
        window.addEventListener('storage', function(e) {
            if (e.key === 'theme') {
                applyTheme(e.newValue);
            }
        });

        // Dropdown de tema
        function toggleThemeDropdown() {
            const dd = document.getElementById('theme-dropdown');
            dd.classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const btn = document.getElementById('theme-toggle-btn');
            const dd = document.getElementById('theme-dropdown');
            if (!btn.contains(e.target) && !dd.contains(e.target)) {
                dd.classList.add('hidden');
            }
        });

        // Aplica o tema
        function applyTheme(theme) {
            document.documentElement.classList.remove('dark', 'colorful');
            document.body.classList.remove('dark:bg-gray-900', 'colorful');
            // Card
            const card = document.getElementById('login-card');
            card.classList.remove('theme-card-light', 'theme-card-dark', 'theme-card-colorful', 'dark:bg-gray-800', 'bg-white');
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark:bg-gray-900');
                card.classList.add('theme-card-dark');
            } else if (theme === 'colorful') {
                document.documentElement.classList.add('colorful');
                document.body.classList.add('colorful');
                card.classList.add('theme-card-colorful');
            } else {
                card.classList.add('theme-card-light');
            }
            // Botão label
            if (theme === 'dark') {
                document.getElementById('theme-btn-label').innerHTML = '<i class="fas fa-moon"></i> Escuro';
            } else if (theme === 'colorful') {
                document.getElementById('theme-btn_label').innerHTML = '<i class="fas fa-palette"></i> Colorido';
            } else {
                document.getElementById('theme-btn-label').innerHTML = '<i class="fas fa-sun"></i> Claro';
            }
        }

        // Define o tema e salva
        function setTheme(theme) {
            localStorage.setItem('theme', theme);
            applyTheme(theme);
            document.getElementById('theme-dropdown').classList.add('hidden');
        }

        // Inicialização do tema ao carregar
        (function() {
            let theme = localStorage.getItem('theme');
            if (!theme) {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            applyTheme(theme);
        })();
    </script>
</body>
</html>