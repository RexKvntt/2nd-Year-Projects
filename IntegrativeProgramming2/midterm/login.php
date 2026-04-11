<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OreXis - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page login-page">
    <main class="app-shell">
        <section class="visual-panel">
            <div class="visual-copy">
                <p class="eyebrow">Sign In</p>
                <h1>Welcome back</h1>
                <p class="lead">Access your OreXis workspace with the same sleek, focused layout across every authentication screen.</p>
            </div>
        </section>

        <section class="content-panel">
            <div class="content-card">
                <p class="brand">OreXis</p>
                <h2>Log in to continue</h2>
                <p class="lead">Enter your credentials to open your role-based dashboard.</p>

                <form action="process_login.php" method="POST" class="stack-form">
                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" type="text" name="username" required autocomplete="username" placeholder="Enter your username">
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
                    </div>

                    <button type="submit">Sign In</button>
                </form>

                <p class="support-copy">Don&apos;t have an account? <a href="register.php">Register here</a></p>
            </div>
        </section>
    </main>
</body>
</html>
