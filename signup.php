<?php include 'includes/header.php'; ?>

<?php include 'includes/navbar.php'; ?>

<main>

    <section class="signup-hero-section">
        <div class="signup-hero-container">

            <div class="signup-hero-content">
                <h1>Create Student Account</h1>
                <p>
                    Register using your university information to access the QUEST Complaint Portal, submit complaints, and track their progress.
                </p>
            </div>
        </div>
    </section>

    <section class="signup-form-section">
        <div class="signup-form-container">

            <div class="signup-form-content">
                <h2>Student Registration</h2>
                <p>
                    Fill in the required information below to create your student account.
                </p>
                <div class="signup-form-wrapper">

                    <form action="authenticate_signup.php" method="POST">

                        <div class="full-name">
                            <label for="full-name">
                                Full Name
                            </label>
                            <input
                                type="text"
                                name="full-name"
                                id="full-name"
                                placeholder="Enter Your Full Name"
                                required>
                        </div>

                        <div class="university-email">
                            <label for="university-email">
                                University Email
                            </label>
                            <input
                                type="email"
                                name="university-email"
                                id="university-email"
                                placeholder="Enter University Email"
                                required>
                        </div>

                        <div class="department">
                            <label for="department">
                                Department
                            </label>
                            <input
                                type="text"
                                name="department"
                                id="department"
                                placeholder="Enter Department"
                                required>
                        </div>

                        <div class="roll-number">
                            <label for="roll-number">
                                Roll Number
                            </label>
                            <input
                                type="text"
                                name="roll-number"
                                id="roll-number"
                                placeholder="Example: 24SWE55"
                                required>
                        </div>

                        <div class="password">
                            <label for="password">
                                Password
                            </label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Enter Password"
                                required>
                        </div>

                        <div class="confirm-password">
                            <label for="confirm-password">
                                Confirm Password
                            </label>
                            <input
                                type="password"
                                name="confirm-password"
                                id="confirm-password"
                                placeholder="Confirm Password"
                                required>
                        </div>

                        <div class="signup-button">
                            <button type="submit">
                                Create Account
                            </button>
                        </div>
                    </form>
                </div>

                <div class="login-link">
                    <p>
                        Already have an account?
                    </p>
                    <a href="login.php">
                        Login Here
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>