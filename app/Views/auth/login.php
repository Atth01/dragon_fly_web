<?php
$error = $error ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DragonFly Swimming Club</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #F3F7FA;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            background: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
        }

        .brand {
            color: #173D5B;
            font-weight: 700;
        }

        .btn-login {
            background: #146B9C;
            color: white;
        }

        .btn-login:hover {
            background: #104F75;
            color: white;
        }
    </style>
</head>

<body>

<div class="login-card">

    <div class="text-center mb-4">
        <h3 class="brand">DRAGONFLY</h3>
        <p class="text-muted mb-0">Swimming Club Management</p>
        <h5 class="mt-4">Login Dashboard</h5>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="login.php">

        <div class="mb-3">
            <label class="form-label">Username atau Email</label>
            <input
                type="text"
                name="identity"
                class="form-control"
                placeholder="Masukkan username atau email"
                required
                autocomplete="username"
            >
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>
            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Masukkan password"
                required
                autocomplete="current-password"
            >
        </div>

        <button type="submit" class="btn btn-login w-100">
            Login
        </button>

    </form>

    <p class="text-center text-muted small mt-4 mb-0">
        &copy; <?= date('Y') ?> DragonFly Swimming Club
    </p>

</div>

</body>
</html>