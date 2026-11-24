<?php

require_once __DIR__ . '/config/Database.php';

require_once __DIR__ . '/helpers/Session.php';
require_once __DIR__ . '/helpers/Auth.php';

require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/JournalEntry.php';
require_once __DIR__ . '/models/Gratitude.php';
require_once __DIR__ . '/models/JournalPrompt.php';

require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/JournalController.php';
require_once __DIR__ . '/controllers/AnalyticsController.php';
require_once __DIR__ . '/controllers/AdminController.php';

Session::start();

$database = new Database();
$db = $database->connect();

$homeController = new HomeController();
$authController = new AuthController($db);
$dashboardController = new DashboardController($db);
$journalController = new JournalController($db);
$analyticsController = new AnalyticsController($db);
$adminController = new AdminController($db);

$page = $_GET['page'] ?? 'home';

switch ($page) {

    // =========================
    // HOME & LANDING
    // =========================
    case 'home':
        $homeController->index();
        break;

    // =========================
    // AUTHENTICATION & PIN
    // =========================
    case 'register':
        $authController->showRegister();
        break;

    case 'register-process':
        $authController->register();
        break;

    case 'login':
        $authController->showLogin();
        break;

    case 'login-process':
        $authController->login();
        break;

    case 'pin-lock':
        $authController->showPinLock();
        break;

    case 'pin-verify':
        $authController->verifyPin();
        break;

    case 'pin-lock-action':
        $authController->lock();
        break;

    case 'logout':
        $authController->logout();
        break;

    // =========================
    // USER DASHBOARD
    // =========================
    case 'dashboard':
        $dashboardController->index();
        break;

    // =========================
    // JOURNAL CRUD
    // =========================
    case 'journals':
        $journalController->index();
        break;

    case 'journal-create':
        $journalController->create();
        break;

    case 'journal-store':
        $journalController->store();
        break;

    case 'journal-detail':
        $journalController->detail();
        break;

    case 'journal-edit':
        $journalController->edit();
        break;

    case 'journal-update':
        $journalController->update();
        break;

    case 'journal-delete':
        $journalController->delete();
        break;

    // =========================
    // ANALYTICS & MEMORY LANE
    // =========================
    case 'mood-analytics':
        $analyticsController->mood();
        break;

    case 'memory-lane':
        $analyticsController->memoryLane();
        break;

    // =========================
    // ADMIN PANEL
    // =========================
    case 'admin-dashboard':
        $adminController->dashboard();
        break;

    // Admin Users CRUD
    case 'admin-users':
        $adminController->users();
        break;

    case 'admin-user-edit':
        $adminController->editUser();
        break;

    case 'admin-user-store':
        $adminController->storeUser();
        break;

    case 'admin-user-update':
        $adminController->updateUser();
        break;

    case 'admin-user-delete':
        $adminController->deleteUser();
        break;

    // Admin Prompts CRUD
    case 'admin-prompts':
        $adminController->prompts();
        break;

    case 'admin-prompt-store':
        $adminController->storePrompt();
        break;

    case 'admin-prompt-update':
        $adminController->updatePrompt();
        break;

    case 'admin-prompt-toggle':
        $adminController->togglePrompt();
        break;

    case 'admin-prompt-delete':
        $adminController->deletePrompt();
        break;

    // =========================
    // 404
    // =========================
    default:
        http_response_code(404);
        require __DIR__ . '/views/layout/header.php';
        echo '<div class="min-h-[60vh] flex flex-col items-center justify-center text-center px-4">
                <span class="text-6xl font-bold text-amber-600 mb-2">404</span>
                <h1 class="text-2xl font-semibold text-stone-800 dark:text-stone-100 mb-2">Halaman Tidak Ditemukan</h1>
                <p class="text-stone-500 dark:text-stone-400 mb-6 max-w-md">Halaman yang Anda tuju tidak ditemukan atau telah dipindahkan.</p>
                <a href="index.php" class="px-5 py-2.5 bg-stone-900 dark:bg-amber-600 hover:bg-stone-800 text-white rounded-xl text-sm font-medium transition shadow-sm">Kembali ke Beranda</a>
              </div>';
        require __DIR__ . '/views/layout/footer.php';
        break;
}
