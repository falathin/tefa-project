<style>
    @keyframes wave {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        20% {
            transform: translateY(-10px) rotate(-3deg);
        }
        40% {
            transform: translateY(-15px) rotate(3deg);
        }
        60% {
            transform: translateY(-10px) rotate(-3deg);
        }
        80% {
            transform: translateY(-5px) rotate(2deg);
        }
    }
    
    .wave-icon {
        display: inline-block;
        animation: wave 1.5s ease-in-out infinite;
    }
    
    .social-icon {
        font-size: 1.8em;
        margin: 0 10px;
        animation: wave 1.5s ease-in-out infinite;
    }
    
    .social-links a {
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        margin-right: 20px;
    }
    
    .footer {
        background-color: #f8f9fa;
        padding: 20px 0;
        font-family: Arial, sans-serif;
    }
    
    .footer .text-muted {
        color: #6c757d;
    }
    
    .footer a {
        text-decoration: none;
    }
    
    .footer .font-weight-bold {
        font-weight: 700;
    }
    
    .footer .text-primary {
        color: #007bff;
    }
    
    .container {
        width: 100%;
        max-width: 1140px;
        margin: 0 auto;
    }
</style>

<footer class="footer">
    <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-sm-between container">
        <div class="text-center mb-2 mb-sm-0">
            <small>
                EduTech karya buatan 
                <a href="https://www.instagram.com/iteenagers_?igsh=cWZkMmU4bm1wdG41" target="_blank" class="font-weight-bold text-primary">
                    <span class="wave-icon"><i class="fas fa-graduation-cap social-icon"></i></span>
                    I-Teens
                </a>
            </small>
        </div>
        <div class="text-center">
            <small>
                Didukung oleh 
                <a href="https://inovindo.co.id/" target="_blank" class="font-weight-bold text-primary">
                    <span class="wave-icon"><i class="fas fa-laptop-code social-icon"></i></span>
                    Inovindo Digital Media
                </a>
                &nbsp;|&nbsp;
                <a href="https://www.facebook.com" target="_blank" class="font-weight-bold text-primary">
                    <span class="wave-icon"><i class="fab fa-facebook-f social-icon"></i></span>
                    Facebook
                </a>
                &nbsp;|&nbsp;
                <a href="https://www.twitter.com" target="_blank" class="font-weight-bold text-primary">
                    <span class="wave-icon"><i class="fab fa-twitter social-icon"></i></span>
                    Twitter
                </a>
                &nbsp;|&nbsp;
                <a href="https://www.linkedin.com" target="_blank" class="font-weight-bold text-primary">
                    <span class="wave-icon"><i class="fab fa-linkedin-in social-icon"></i></span>
                    LinkedIn
                </a>
            </small>
        </div>
    </div>
</footer>
