<?php
require_once __DIR__ . '/src/bootstrap.php';

// redirectCompleted();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classroom Homepage</title>
    <link rel="icon" href="/survey/assets/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/survey/assets/favicon.ico" type="image/x-icon">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> -->
    <link rel="stylesheet" href="/survey/assets/css/swiper-bundle.min.css" />
    <link href="/survey/assets/css/homepage.css" rel="stylesheet">
</head>
<body>

    <nav>
        <div class="nav-links">
            <a href="#members" class="btn">Members</a>
            <a href="#survey" class="btn">Survey</a>
            <a href="#values" class="btn">Visions</a>
        </div>

        <div class="logo-container">
            <img src="/survey/assets/images/SchoolLogo.png" alt="Logo" class="main-logo">
        </div>
    </nav>

    <header>
        <h1>Welcome to the Classroom Feedback Survey</h1>
        <p>Supporting improvement in classroom experiences</p>
    </header>

    <!-- Members Section -->
    <section class="section" id="members">
        <h2 class="section-title">Meet Our Team</h2>
        <div class="swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="card">
                        <img src="/survey/assets/images/member_1.jpg"  alt="Member 1">
                        <h3>Earl John D. Fria</h3>
                        <p>Frontend Developer</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="card">
                        <img src="/survey/assets/images/member_2.jpg"  alt="Member 2">
                        <h3>John Mark C. Arellano</h3>
                        <p>Backend Developer</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="card">
                        <img src="/survey/assets/images/member_3.jpg"  alt="Member 3">
                        <h3>Bryan Matthew Millan</h3>
                        <p>Frontend Developer</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="card">
                        <img src="/survey/assets/images/member_4.jpg"  alt="Member 4">
                        <h3>Alyssa Nicole S. Cuerpo</h3>
                        <p>Frontend Developer</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="card">
                        <img src="/survey/assets/images/member_5.jpg"  alt="Member 5">
                        <h3>Teddy Yron C. Del Monte</h3>
                        <p>Frontend Developer</p>
                    </div>
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>

    <!-- Feedback Survey -->
    <section class="section" id="survey">
        <h2 class="section-title">Feedback Survey</h2>
        <div style="display: flex; justify-content: center;">
            <div class="card">
            <h3>Classroom Survey</h3>
            <p>Help us improve by completing our brief survey</p>
            <br>
            <a href="student_info.php" class="btn" style="display:inline-block; text-decoration:none;">
                Take Survey
            </a>
            </div>
        </div>
    </section>

    <section class="section" id="values">
        <h2 class="section-title">Our Values</h2>
        <div class="triangle-section">
            <div class="value-box center-item">
                <h3>Vision</h3>
                <p>To empower learners by providing knowledge, practical skills, and strong values that will guide them toward a successful and meaningful future. We aim to help individuals become responsible, confident, and productive members of the community who can contribute positively to society and inspire change for a better tomorrow. </p>
            </div>
            <div class="value-box">
                <h3>Core Values</h3>
                <p>We believe in practicing integrity by being honest, respectful, and responsible in all our actions. We strive for excellence by continuously improving our work and encouraging everyone to achieve their full potential. Collaboration is important to us because teamwork, understanding, and cooperation help build stronger relationships and better outcomes. We also value innovation by welcoming new ideas, creative thinking, and modern solutions that can improve learning and community services.</p>
            </div>
            <div class="value-box">
                <h3>Mission</h3>
                <p>Our mission is to provide accessible and quality education and community-based services that help improve the lives of individuals and families. We are committed to creating a supportive environment where learning, personal growth, and community participation are encouraged. Through dedication, service, and continuous improvement, we aim to make a positive impact on both education and community development.</p>
            </div>
        </div>
    </section>

    <footer id="footer">
        <h4>Contact Us</h4>
        <div class="contact-info">
            <p>Email: <a href="mailto:contact@classroom.edu">contact@classroom.edu</a></p>
        </div>
        <div class="social-links">
            <a href="#facebook">Facebook</a>
            <a href="#twitter">Twitter</a>
            <a href="#linkedin">LinkedIn</a>
            <a href="#instagram">Instagram</a>
        </div>
        <p>&copy; 2024 Classroom University. All rights reserved.</p>
    </footer>

    <!-- Swiper JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script> -->
    <script src="/survey/assets/js/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            loop: true,
            grabCursor: true,
            spaceBetween: 20,

            slidesPerView: 1,

            breakpoints: {
                480: {
                    slidesPerView: 2
                },
                768: {
                    slidesPerView: 3
                },
                1024: {
                    slidesPerView: 3
                }
            },

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>
    
</body>
</html>
