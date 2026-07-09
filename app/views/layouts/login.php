<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title ?? 'Admin Login'; ?>
    </title>
    <meta name="description" content="<?php echo $description ?? 'Admin Login'; ?>">

    <base href="<?php echo BASE_URL; ?>">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <?php
    if (isset($headerResources)) {
        if (isset($headerResources['css'])) {
            foreach ($headerResources['css'] as $cssFile) {
                echo '<link rel="stylesheet" href="' . htmlspecialchars($cssFile) . '">' . PHP_EOL;
            }
        }
    }
    ?>
</head>

<body>
    <?php echo $content; ?>

    <?php
    if (isset($footerResources)) {
        if (isset($footerResources['js'])) {
            foreach ($footerResources['js'] as $jsFile) {
                echo '<script src="' . htmlspecialchars($jsFile) . '"></script>' . PHP_EOL;
            }
        }
    }
    ?>
</body>

</html>