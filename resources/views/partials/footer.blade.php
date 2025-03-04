<style>
    @keyframes wave {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        25% {
            transform: translateY(-12px) rotate(-5deg);
        }
        50% {
            transform: translateY(-18px) rotate(5deg);
        }
        75% {
            transform: translateY(-12px) rotate(-5deg);
        }
    }
    
    .wave-text span {
        display: inline-block;
        animation: wave 1s ease-in-out;
    }
    
    .wave-text span:nth-child(odd) {
        animation-delay: 0s;
    }
    
    .wave-text span:nth-child(even) {
        animation-delay: 0.2s;
    }
    
    .wave-text span:nth-child(3n) {
        animation-delay: 0.4s;
    }
    
    .wave-text span:nth-child(4n) {
        animation-delay: 0.6s;
    }
    
    .wave-icon {
        display: inline-block;
        animation: wave 1s ease-in-out;
    }
    
    .wave-text {
        display: inline-flex;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .social-icon {
        font-size: 1.6em;
        margin-left: 10px;
        margin-right: 15px;
        animation: wave 1s ease-in-out;
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
    <div class="d-sm-flex justify-content-center justify-content-sm-between container">
        <span class="float-none float-sm-end d-block mt-1 mt-sm-0 text-center">
            <small>EduTech buatan I - Teenagers, dikembangkan oleh 
                <a href="https://www.inovindodigital.com" target="_blank" class="font-weight-bold text-primary"> 
                    <span class="wave-icon"><i class="fas fa-briefcase social-icon"></i></span>
                    Staff Inovindo</a>
            </small>
        </span>
    </div>
</footer>
