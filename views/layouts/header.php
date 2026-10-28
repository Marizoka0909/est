<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= htmlspecialchars($pageTitle ?? 'SOS Estudante') ?> | Gestão Acadêmica</title>
    
    <!-- Meta tags de acessibilidade e SEO -->
    <meta name="description" content="SOS Estudante - Sistema web para centralização de tarefas, eventos, notas e rotina acadêmica.">
    <meta name="theme-color" content="#4f46e5">

    <!-- Tailwind CSS (CDN oficial) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos Customizados -->
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body class="h-full antialiased text-slate-800 flex flex-col font-['Plus_Jakarta_Sans',sans-serif]">
