<?php
require_once __DIR__ . '/define.php';

function secure_session(): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    if (!empty($_SERVER['HTTPS'])) ini_set('session.cookie_secure', '1');
    session_start();
}
secure_session();

function pdo(bool $withDb = true): PDO {
    static $db = null, $server = null;
    if ($withDb && $db) return $db;
    if (!$withDb && $server) return $server;
    $dsn = 'mysql:host=' . DB_HOST . ($withDb ? ';dbname=' . DB_NAME : '') . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $withDb ? ($db = $pdo) : ($server = $pdo);
}
function e(?string $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">'; }
function verify_csrf(): void { if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Invalid CSRF token.'); } }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function require_login(?string $role = null): array { $u = current_user(); if (!$u || ($role && $u['role'] !== $role)) { header('Location: login.php'); exit; } return $u; }
function redirect_by_role(string $role): void { header('Location: ' . ($role === 'admin' ? 'admin_dashboard.php' : ($role === 'librarian' ? 'librarian_dashboard.php' : 'student_dashboard.php'))); exit; }
function log_activity(int $userId, string $action): void { try { pdo()->prepare('INSERT INTO activity_logs(user_id, action, ip_address) VALUES(?,?,?)')->execute([$userId, $action, $_SERVER['REMOTE_ADDR'] ?? 'cli']); } catch(Throwable $e) {} }
function app_header(string $title): void { $u=current_user(); ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($title)?> | Library Table Booking</title><link rel="stylesheet" href="assets/css/style.css"></head><body><div class="space"><span></span><span></span><span></span><span></span></div><header class="nav glass"><a class="brand" href="index.php">📚 Library 3D</a><nav><?php if($u): ?><a href="<?=e($u['role'])?>_dashboard.php">Dashboard</a><a href="logout.php">Logout</a><?php else: ?><a href="login.php">Login</a><a class="btn small" href="register.php">Register</a><?php endif; ?><select id="themeSwitcher" aria-label="Theme switcher"></select></nav></header><main class="container">
<?php }
function app_footer(): void { ?></main><script src="assets/js/themes.js"></script><script src="assets/js/main.js"></script></body></html><?php }
?>
