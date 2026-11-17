<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Forbidden</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container min-vh-100 d-flex align-items-center justify-content-center">

    <div class="text-center">

        <h1 class="display-1 fw-bold text-danger">403</h1>

        <h3>Akses Ditolak</h3>

        <p class="text-muted">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
        </p>

        <a href="<?= APP_URL ?>/dashboard.php" class="btn btn-primary">
            Kembali ke Dashboard
        </a>

    </div>

</div>

</body>
</html>