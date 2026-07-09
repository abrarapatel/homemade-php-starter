<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Starter Project'; ?></title>
    <meta name="description" content="<?php echo $description ?? 'A starter project template.'; ?>">
    <link rel="canonical" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

    <base href="<?php echo BASE_URL; ?>">

    <!-- Favicon - Keep as requested -->
    <link rel="icon" type="image/png" href="assets/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="assets/favicon/favicon.svg" />
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="assets/favicon/site.webmanifest" />

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

    <?php
    if (isset($headerResources)) {
        if (isset($headerResources['css'])) {
            foreach ($headerResources['css'] as $cssFile) {
                echo '<link rel="stylesheet" href="' . htmlspecialchars($cssFile) . '">' . PHP_EOL;
            }
        }

        if (isset($headerResources['js'])) {
            foreach ($headerResources['js'] as $jsFile) {
                echo '<script src="' . htmlspecialchars($jsFile) . '"></script>' . PHP_EOL;
            }
        }
    }
    ?>
</head>

<body>
    <header class="main-header">
        <div class="navbar-inner">
            <div class="logo-container">
                <a href="./" class="logo">Starter</a>
            </div>

            <div class="nav-container" id="nav-container">
                <nav class="nav-links">
                    <a href="./" class="nav-link">Home</a>
                </nav>
            </div>

            <div class="menu-btn-container">
                <button class="menu-toggle" id="menu-toggle" aria-label="Toggle navigation">
                    <div class="menu-icon"></div>
                    <div class="menu-icon"></div>
                    <div class="menu-icon"></div>
                    <div class="menu-icon"></div>
                </button>
            </div>
        </div>
    </header>

    <main>
        <?php echo $content; ?>
    </main>

    <script src="js/app.js"></script>
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