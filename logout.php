<?php require_once 'db.php'; log_activity(current_user()['id']??null,'Logged out'); session_destroy(); header('Location: index.php'); exit; ?>
