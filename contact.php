<?php
declare(strict_types=1);

header('Content-Type: text/html; charset=UTF-8');

function h(?string $v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$isPost = ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';

if ($isPost) {
    $webhookUrl = 'https://hooks.zapier.com/hooks/catch/8389196/u70o7ot/';
    $payload = [
        'name' => trim((string)($_POST['name'] ?? '')),
        'phone' => trim((string)($_POST['phone'] ?? '')),
        'email' => trim((string)($_POST['email'] ?? '')),
        'subject' => trim((string)($_POST['subject'] ?? '')),
        'message' => trim((string)($_POST['message'] ?? '')),
        'privacy_approval' => isset($_POST['privacy_approval']) ? 'yes' : 'no',
        'source' => 'shaer-finance.co.il',
        'submitted_at' => date('c'),
        'ip' => (string)($_SERVER['REMOTE_ADDR'] ?? ''),
        'user_agent' => (string)($_SERVER['HTTP_USER_AGENT'] ?? ''),
    ];

    $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($json !== false) {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
                'content' => $json,
                'timeout' => 5,
                'ignore_errors' => true,
            ],
        ]);
        @file_get_contents($webhookUrl, false, $context);
    }

    header('Location: thank-you.html', true, 303);
    exit;
}

?>
<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>הטופס התקבל</title>
    <style>
        body{font-family:system-ui,Segoe UI,Arial; margin:0; background:#f6f7fb; color:#111;}
        .wrap{max-width:720px; margin:40px auto; padding:24px;}
        .card{background:#fff; border-radius:14px; padding:22px 20px; box-shadow:0 8px 30px rgba(0,0,0,.08);}
        h1{font-size:22px; margin:0 0 8px;}
        p{margin:10px 0; line-height:1.6;}
        a.btn{display:inline-block; margin-top:14px; padding:10px 14px; background:#1a3cff; color:#fff; border-radius:10px; text-decoration:none;}
        .muted{color:#555; font-size:14px;}
        .grid{display:grid; grid-template-columns:1fr; gap:8px; margin-top:10px;}
        .row{background:#f3f5ff; border-radius:10px; padding:10px 12px;}
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <?php if (!$isPost): ?>
                <h1>העמוד הזה מיועד לשליחת הטופס</h1>
                <p class="muted">חזרו לדף הנחיתה ושלחו את הטופס במקטע יצירת הקשר.</p>
                <a class="btn" href="index.html#contact">חזרה לדף הנחיתה</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>