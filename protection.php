<?php
session_start();

// CSRF 토큰 생성 (세션에 저장)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // 32바이트의 랜덤 바이트를 생성하여 토큰으로 사용
}

// CSRF 성공 여부와 입력된 이름을 저장할 변수를 초기화합니다.
$csrf_success = false;
$username = '';
$error_message = ''; // 에러 메시지 초기화

// POST 요청이 있을 경우 CSRF 공격을 처리합니다.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // CSRF 토큰이 없거나 유효하지 않을 경우 에러 메시지
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_message = "CSRF 공격 실패!";
    } else {
        // CSRF 토큰이 유효할 경우
        $csrf_success = true;
        // 사용자가 입력한 이름을 저장합니다.
        $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Protection Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }
        .username-message {
            margin-top: 20px;
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>CSRF 방어 실습</h1>
    <form method="POST" action="">
        <label for="username">이름 입력:</label>
        <input type="text" id="username" name="username" required>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"> <!-- CSRF 토큰 -->
        <input type="submit" name="submit" value="제출">
    </form>

    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if ($csrf_success): ?>
        <div class="success-message">CSRF 공격이 성공했습니다!</div>
        <div class="username-message">입력한 이름: <?php echo $username; ?></div>
    <?php endif; ?>
</body>
</html>
