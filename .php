<?php
session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = 'test_user';  // 기본 사용자 이름
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF 공격 방어가 없음
    $new_name = $_POST['name'];
    $_SESSION['user'] = $new_name;  // 세션의 사용자 이름 업데이트
    $success_message = "사용자 이름이 업데이트되었습니다: " . htmlspecialchars($new_name, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF 실습</title>
</head>
<body>
    <h1>CSRF 실습 페이지</h1>
    <p>로그인된 사용자: <?php echo $_SESSION['user']; ?></p>

    <!-- 사용자의 이름을 변경하는 폼 -->
    <form method="POST" action="">
        <label for="name">새로운 이름 입력:</label>
        <input type="text" id="name" name="name" required>
        <input type="submit" value="업데이트">
    </form>

    <!-- CSRF 공격으로 인해 이름이 업데이트된 경우 메시지 표시 -->
    <?php if (isset($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>
</body>
</html>
