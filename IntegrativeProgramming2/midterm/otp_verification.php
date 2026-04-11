<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page otp-page">
    <main class="app-shell">
        <section class="visual-panel">
            <div class="visual-copy">
                <p class="eyebrow">Two-Step Security</p>
                <h1>Verify your one-time code</h1>
                <p class="lead">A consistent verification page helps the secure flow feel intentional from start to finish.</p>
            </div>
        </section>

        <section class="content-panel">
            <div class="content-card">
                <p class="brand">Verification</p>
                <h2>Confirm OTP</h2>
                <p class="lead">Enter the code sent to your email to finish signing in.</p>

                <form action="otp_verification_process.php" method="POST" class="stack-form">
                    <div class="field">
                        <label for="otp">One-Time Password</label>
                        <input id="otp" type="text" name="otp" required placeholder="Enter your OTP">
                    </div>

                    <button type="submit">Verify Code</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
