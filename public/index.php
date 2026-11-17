<?php

declare(strict_types=1);

// Load database connection
require_once __DIR__ . '/../config/database.php';

// Application information
$appName = 'DragonFly Swimming Club';

// Temporary database connection check
$dbStatus = 'Connected';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($appName) ?></title>
</head>
<body>

    <h1><?= htmlspecialchars($appName) ?></h1>

    <p>Website application is running.</p>

    <p>Database status: <?= htmlspecialchars($dbStatus) ?></p>

</body>
</html>
