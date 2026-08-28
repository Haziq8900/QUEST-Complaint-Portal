<?php include 'includes/header.php'; ?>

<?php include 'includes/navbar.php'; ?>

<main>
    <section class="contact-hero-section">
        <div class="contact-hero-container">

            <div class="contact-hero-content">
                <h1>Contact Us</h1>
                <p>Have a question, need assistance, or want to report an issue? We're here to help. Reach out to us through
                    the contact information below or send us a message using the contact form.</p>
            </div>
        </div>
    </section>

    <section class="contact-info-section">
        <div class="contact-info-container">

            <div class="contact-info-content">
                <h2>Contact Information</h2>
                <p>
                    If you have any queries or having any problem related to the portal, feel free to contact us through the
                    below given contact info.
                </p>

                <div class="contact-grid">

                    <div class="contact-info-card">
                        <i class="fa-solid fa-location-dot"></i>
                        <h3>Address</h3>
                        <p>ICT Centre, QUEST University, Nawabshah</p>
                    </div>

                    <div class="contact-info-card">
                        <i class="fa-solid fa-envelope"></i>
                        <h3>Email</h3>
                        <p>Email us at: <a href="mailto:qcp@quest.edu.pk">qcp@quest.edu.pk</a></p>
                    </div>

                    <div class="contact-info-card">
                        <i class="fa-solid fa-phone"></i>
                        <h3>Phone</h3>
                        <p>Call us at: <a href="tel:+922449370367">+92 244 9370367</a></p>
                    </div>

                    <div class="contact-info-card">
                        <i class="fa-solid fa-clock"></i>
                        <h3>Office Hours</h3>
                        <p>Monday - Friday</p>
                        <p>Timing 8:30 AM - 1:30 PM</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="contact-form-section">
        <div class="contact-form-container">

            <div class="contact-form-content">
                <h2>Send us a Message</h2>
                <p>If you have a complaint about the portal, send us a message thorugh the form below.</p>

                <div class="contact-form-wrapper">
                    <form action="#" method="POST">
                        <div class="name">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" placeholder="Enter your Full Name" required>
                        </div>

                        <div class="email">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Enter your Email" required>
                        </div>

                        <div class="subject">
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" id="subject" placeholder="Enter the subject" required>
                        </div>

                        <div class="message">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" placeholder="Enter your Message:"></textarea>
                        </div>

                        <div class="button">
                            <button type="submit">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="university-info-section">
        <div class="university-info-container">
            
            <div class="university-info-content">
                <h2>Visit QUEST University</h2>
                <p>
                    Learn more about Quaid-e-Awam University of Engineering, Science, and Technology (QUEST), Nawabshah. 
                    Visit the official university website for academic programs, admissions, campus information, and the 
                    latest announcements.
                </p>

                <div class="university-image">
                    <img src="assets/images/QUEST-.webp" alt="Central Library of QUEST University">
                </div>

                <div class="link-button">
                    <a href="https://quest.edu.pk" target="_blank">Visit Official Website</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>