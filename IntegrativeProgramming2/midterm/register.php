<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OreXis - Account Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page register-page">
    <main class="app-shell">
        <section class="visual-panel">
            <div class="visual-copy">
                <p class="eyebrow">Create Account</p>
                <h1>Join the OreXis network</h1>
                <p class="lead">A refined registration experience with the same cool-toned, premium feel as the sign-in reference.</p>
            </div>
        </section>

        <section class="content-panel">
            <div class="content-card">
                <p class="brand">New Profile</p>
                <h2>Register your account</h2>
                <p class="lead">Fill in your details to set up secure access.</p>

                <form action="process_register.php" method="POST" class="stack-form">
                    <div class="field">
                        <label for="fullname">Full Name</label>
                        <input id="fullname" type="text" name="fullname" required placeholder="Firstname, MI, Surname">
                    </div>

                    <div class="field">
                        <label for="phonenumber">Phone Number</label>
                        <input id="phonenumber" type="text" name="phonenumber" required placeholder="(+63) 9xx xxx xxxx">
                    </div>

                    <div class="field">
                        <label for="civilstatus">Civil Status</label>
                        <input id="civilstatus" type="text" name="civilstatus" required placeholder="Single, Married, etc.">
                    </div>

                    <div class="field">
                        <label for="username">Username</label>
                        <input id="username" type="text" name="username" required placeholder="Choose a username">
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" required placeholder="name@example.com">
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required placeholder="Create a password">
                    </div>

                    <div class="field">
                        <label for="confirm_password">Confirm Password</label>
                        <input id="confirm_password" type="password" name="confirm_password" required placeholder="Re-enter your password">
                    </div>

                    <button type="submit">Register</button>
                </form>

                <p class="support-copy">Already have an account? <a href="login.php">Sign in instead</a></p>
            </div>
        </section>
    </main>
</body>
</html>
