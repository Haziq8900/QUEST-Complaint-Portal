<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main>
    <section class="login-hero-section">
        <div class="login-hero-container">

            <div class="login-hero-content">
                <h1>Welcome Back</h1>
                <p>
                    Access your QUEST Complaint Portal account to submit new complaints, track existing requests, and stay updated on their resolution status.
                </p>
            </div>
        </div>
    </section>

    <section class="login-form-section">
        <div class="login-form-container">
            <div class="login-form-content">
                <h2>Sign In</h2>
                <p>
                    Enter your university email address and password to access your account.
                </p>

                <div class="login-form-wrapper">
                    <form action="authenticate.php" method="POST">
                        <div class="account-type">
                            <label>Login As</label>
                            <div>
                                <input type="radio" name="account-type" id="student" value="student" required>
                                <label for="student">
                                    Student
                                </label>
                            </div>

                            <div>
                                <input type="radio" name="account-type" id="admin" value="admin">
                                <label for="admin">
                                    Administrator
                                </label>
                            </div>
                        </div>

                        <div class="university-email">
                            <label for="university-email">
                                University Email
                            </label>
                            <input type="email"
                                name="university-email"
                                id="university-email"
                                placeholder="Enter University Email"
                                required>
                        </div>

                        <div class="password">
                            <label for="password">
                                Password
                            </label>
                            <input type="password"
                                name="password"
                                id="password"
                                placeholder="Enter Your Password"
                                required>
                        </div>

                        <div class="remember-me">
                            <input type="checkbox"
                                name="remember-me"
                                id="remember-me"
                                value="remember">
                            <label for="remember-me">
                                Keep me signed in.
                            </label>
                        </div>

                        <div class="login-button">
                            <button type="submit">
                                Login
                            </button>
                            <p>
                                Use your university credentials provided by the administration to access the complaint portal.
                            </p>
                        </div>
                    </form>
                </div>

                <div class="sign-up">
                    <p>
                        Don't have a student account?
                    </p>
                    <a href="signup.php">
                        Create Student Account
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>