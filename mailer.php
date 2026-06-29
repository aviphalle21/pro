<?php
require_once __DIR__ . '/db.php';
function email_template(string $type, array $data): array {
    $name = e($data['name'] ?? 'Student');
    $reason = e($data['reason'] ?? '');
    $link = e($data['link'] ?? BASE_URL);
    $templates = [
        'accepted' => ['Booking Accepted', "<h2>Congratulations, {$name}!</h2><p>Your table booking has been accepted. Your study table is active from {$data['start']} to {$data['expiry']}.</p>"],
        'rejected' => ['Booking Rejected', "<h2>Hello {$name}</h2><p>Your booking was rejected.</p><p><b>Reason:</b> {$reason}</p>"],
        'forgot' => ['Password Reset Request', "<h2>Password reset</h2><p>Use this secure link to reset your password: <a href='{$link}'>Reset Password</a></p><p>OTP: <b>" . e($data['otp'] ?? '') . "</b></p>"],
        'pending' => ['Payment Received - Pending Approval', "<h2>Payment submitted</h2><p>Hi {$name}, your payment transaction ID has been recorded and is pending librarian approval.</p>"],
    ];
    return $templates[$type];
}
function send_library_mail(?int $userId, string $to, string $subject, string $html): bool {
    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: " . PAYEE_NAME . " <" . LIBRARY_EMAIL . ">\r\n";
    $ok = @mail($to, $subject, $html, $headers);
    pdo()->prepare('INSERT INTO email_logs(user_id, recipient, subject, body, status, error) VALUES(?,?,?,?,?,?)')->execute([$userId, $to, $subject, $html, $ok?'sent':'queued', $ok?null:'mail() unavailable; configure SMTP in production']);
    return $ok;
}
?>
