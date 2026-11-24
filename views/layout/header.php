<?php
$currentPage = $_GET['page'] ?? 'home';
$pageTitle = $pageTitle ?? 'MyJournal — Personal Digital Diary & Mood Tracker';
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,600;1,400;1,600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        stone: {
                            850: '#1f1d1b',
                            950: '#0c0a09',
                        },
                        amber: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                        mood: {
                            vhappy: '#10b981', // emerald
                            happy: '#0ea5e9',  // sky
                            neutral: '#f59e0b', // amber
                            sad: '#6366f1',     // indigo
                            stressed: '#f43f5e' // rose
                        }
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Dark Mode Init Script to avoid FOUC -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        /* Smooth transitions for theme switching */
        * {
            -webkit-tap-highlight-color: transparent;
        }
        body {
            font-feature-settings: "cv02", "cv03", "cv04", "cv11";
        }
        /* Custom scrollbar for soothing feel */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #44403c;
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-stone-50 text-stone-800 dark:bg-[#121110] dark:text-stone-200 antialiased selection:bg-amber-100 selection:text-amber-900 dark:selection:bg-amber-900/40 dark:selection:text-amber-200 transition-colors duration-200">

    <?php require __DIR__ . '/navbar.php'; ?>

    <!-- Flash Notifications -->
    <?php
    $flashErrors = Session::flash('errors', []);
    $flashSuccess = Session::flash('success');
    $flashInfo = Session::flash('info');
    ?>

    <?php if (!empty($flashSuccess)): ?>
        <div id="flash-success" class="max-w-4xl mx-auto px-4 sm:px-6 w-full pt-4">
            <div class="flex items-center gap-3 p-4 bg-emerald-50/90 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-200 rounded-2xl shadow-sm text-sm">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                <span class="flex-1 font-medium"><?= htmlspecialchars($flashSuccess) ?></span>
                <button type="button" onclick="document.getElementById('flash-success').remove()" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashInfo)): ?>
        <div id="flash-info" class="max-w-4xl mx-auto px-4 sm:px-6 w-full pt-4">
            <div class="flex items-center gap-3 p-4 bg-sky-50/90 dark:bg-sky-950/40 border border-sky-200 dark:border-sky-800/60 text-sky-800 dark:text-sky-200 rounded-2xl shadow-sm text-sm">
                <i data-lucide="shield-check" class="w-5 h-5 text-sky-600 dark:text-sky-400 shrink-0"></i>
                <span class="flex-1 font-medium"><?= htmlspecialchars($flashInfo) ?></span>
                <button type="button" onclick="document.getElementById('flash-info').remove()" class="text-sky-600 hover:text-sky-800 dark:text-sky-400">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashErrors)): ?>
        <div id="flash-errors" class="max-w-4xl mx-auto px-4 sm:px-6 w-full pt-4">
            <div class="p-4 bg-rose-50/90 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-200 rounded-2xl shadow-sm text-sm">
                <div class="flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0 mt-0.5"></i>
                    <div class="flex-1">
                        <?php if (count($flashErrors) === 1): ?>
                            <p class="font-medium"><?= htmlspecialchars($flashErrors[0]) ?></p>
                        <?php else: ?>
                            <p class="font-medium mb-1">Please correct the following:</p>
                            <ul class="list-disc list-inside space-y-0.5 text-xs opacity-90">
                                <?php foreach ($flashErrors as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <button type="button" onclick="document.getElementById('flash-errors').remove()" class="text-rose-600 hover:text-rose-800 dark:text-rose-400">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content wrapper -->
    <main class="flex-1">
