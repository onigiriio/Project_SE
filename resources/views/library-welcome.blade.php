<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>IIUM Library Management System - The Future of Library Management</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|space-mono:400,700" rel="stylesheet" />
        <!-- Styles -->
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Instrument Sans', sans-serif;
                background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f1629 100%);
                color: #e0e6ff;
                min-height: 100vh;
                overflow-x: hidden;
            }

            /* Animated background elements */
            .bg-animation {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                overflow: hidden;
            }

            .floating-orb {
                position: absolute;
                border-radius: 50%;
                opacity: 0.1;
                animation: float 20s infinite ease-in-out;
            }

            .orb-1 {
                width: 300px;
                height: 300px;
                background: radial-gradient(circle, #00d4ff, transparent);
                top: -50px;
                left: -50px;
                animation-delay: 0s;
            }

            .orb-2 {
                width: 250px;
                height: 250px;
                background: radial-gradient(circle, #6366f1, transparent);
                bottom: 100px;
                right: -50px;
                animation-delay: 5s;
            }

            .orb-3 {
                width: 200px;
                height: 200px;
                background: radial-gradient(circle, #a855f7, transparent);
                bottom: -50px;
                left: 50%;
                animation-delay: 10s;
            }

            @keyframes float {
                0%, 100% { transform: translate(0, 0); }
                50% { transform: translate(30px, 30px); }
            }

            /* Grid lines effect */
            .grid-pattern {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: 
                    linear-gradient(0deg, transparent 24%, rgba(0, 212, 255, 0.05) 25%, rgba(0, 212, 255, 0.05) 26%, transparent 27%, transparent 74%, rgba(0, 212, 255, 0.05) 75%, rgba(0, 212, 255, 0.05) 76%, transparent 77%, transparent),
                    linear-gradient(90deg, transparent 24%, rgba(0, 212, 255, 0.05) 25%, rgba(0, 212, 255, 0.05) 26%, transparent 27%, transparent 74%, rgba(0, 212, 255, 0.05) 75%, rgba(0, 212, 255, 0.05) 76%, transparent 77%, transparent);
                background-size: 50px 50px;
                z-index: -1;
            }

            /* Header */
            header {
                padding: 30px 20px;
                text-align: center;
                border-bottom: 1px solid rgba(0, 212, 255, 0.2);
                backdrop-filter: blur(10px);
                background: rgba(10, 14, 39, 0.7);
            }

            .logo {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                font-size: 1.8rem;
                font-weight: 700;
                margin-bottom: 10px;
                background: linear-gradient(135deg, #00d4ff, #a855f7);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .tagline {
                font-size: 0.9rem;
                color: #8892b0;
                letter-spacing: 2px;
                text-transform: uppercase;
            }

            /* Container */
            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 60px 20px;
            }

            /* Hero Section */
            .hero {
                text-align: center;
                margin-bottom: 80px;
                animation: slideDown 0.8s ease-out;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .hero h1 {
                font-size: 3.5rem;
                font-weight: 700;
                margin-bottom: 20px;
                line-height: 1.2;
                background: linear-gradient(135deg, #00d4ff, #6366f1, #a855f7);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .hero-subtitle {
                font-size: 1.3rem;
                color: #a0aec0;
                margin-bottom: 40px;
                max-width: 700px;
                margin-left: auto;
                margin-right: auto;
            }

            .cta-buttons {
                display: flex;
                gap: 20px;
                justify-content: center;
                flex-wrap: wrap;
                margin-bottom: 60px;
            }

            .btn {
                padding: 14px 40px;
                border-radius: 8px;
                font-size: 1rem;
                font-weight: 600;
                text-decoration: none;
                transition: all 0.3s ease;
                border: 2px solid;
                cursor: pointer;
                display: inline-block;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .btn-primary {
                background: linear-gradient(135deg, #00d4ff, #6366f1);
                border-color: #00d4ff;
                color: #0a0e27;
                box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
            }

            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 0 40px rgba(0, 212, 255, 0.6);
            }

            .btn-secondary {
                background: transparent;
                border-color: #6366f1;
                color: #6366f1;
                box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
            }

            .btn-secondary:hover {
                background: rgba(99, 102, 241, 0.1);
                transform: translateY(-3px);
                box-shadow: 0 0 40px rgba(99, 102, 241, 0.4);
            }

            /* Features Grid */
            .features-section {
                margin-bottom: 80px;
            }

            .section-title {
                text-align: center;
                font-size: 2.5rem;
                margin-bottom: 50px;
                color: #e0e6ff;
            }

            .section-title span {
                background: linear-gradient(135deg, #00d4ff, #a855f7);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .features-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 30px;
                margin-bottom: 60px;
            }

            .feature-card {
                background: linear-gradient(135deg, rgba(0, 212, 255, 0.05), rgba(99, 102, 241, 0.05));
                border: 2px solid rgba(0, 212, 255, 0.2);
                border-radius: 12px;
                padding: 40px 30px;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .feature-card::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(0, 212, 255, 0.1), transparent);
                animation: shine 20s infinite;
            }

            @keyframes shine {
                0% { transform: translate(0, 0); }
                50% { transform: translate(50%, 50%); }
                100% { transform: translate(0, 0); }
            }

            .feature-card:hover {
                border-color: #00d4ff;
                box-shadow: 0 0 30px rgba(0, 212, 255, 0.2);
                transform: translateY(-5px);
            }

            .feature-icon {
                font-size: 3rem;
                margin-bottom: 20px;
            }

            .feature-card h3 {
                font-size: 1.4rem;
                margin-bottom: 15px;
                color: #00d4ff;
            }

            .feature-card p {
                color: #a0aec0;
                line-height: 1.6;
                position: relative;
                z-index: 1;
            }

            /* Stats Section */
            .stats-section {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 30px;
                margin-bottom: 80px;
                text-align: center;
            }

            .stat-card {
                background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1));
                border: 1px solid rgba(168, 85, 247, 0.3);
                border-radius: 12px;
                padding: 30px;
                transition: all 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-5px);
                border-color: #a855f7;
                box-shadow: 0 0 20px rgba(168, 85, 247, 0.2);
            }

            .stat-number {
                font-size: 2.5rem;
                font-weight: 700;
                background: linear-gradient(135deg, #00d4ff, #a855f7);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin-bottom: 10px;
            }

            .stat-label {
                color: #8892b0;
                font-size: 0.95rem;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            /* CTA Section */
            .cta-section {
                background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(99, 102, 241, 0.1));
                border: 2px solid rgba(0, 212, 255, 0.3);
                border-radius: 16px;
                padding: 60px 40px;
                text-align: center;
                margin-bottom: 60px;
            }

            .cta-section h2 {
                font-size: 2.2rem;
                margin-bottom: 20px;
                color: #e0e6ff;
            }

            .cta-section p {
                color: #a0aec0;
                margin-bottom: 30px;
                font-size: 1.1rem;
            }

            /* Footer */
            footer {
                border-top: 1px solid rgba(0, 212, 255, 0.2);
                padding: 40px 20px;
                text-align: center;
                color: #8892b0;
                backdrop-filter: blur(10px);
                background: rgba(10, 14, 39, 0.7);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .hero h1 {
                    font-size: 2.2rem;
                }

                .hero-subtitle {
                    font-size: 1.1rem;
                }

                .section-title {
                    font-size: 1.8rem;
                }

                .cta-buttons {
                    flex-direction: column;
                    align-items: center;
                }

                .btn {
                    width: 100%;
                    max-width: 300px;
                }
            }
        </style>
    </head>
    <body>
        <div class="bg-animation">
            <div class="floating-orb orb-1"></div>
            <div class="floating-orb orb-2"></div>
            <div class="floating-orb orb-3"></div>
            <div class="grid-pattern"></div>
        </div>

        <!-- Header -->
        <header>
            <div class="logo">📚 IIUM Library Management System</div>
            <div class="tagline">The Future of Knowledge Management</div>
        </header>

        <!-- Main Content -->
        <div class="container">
            <!-- Hero Section -->
            <section class="hero">
                <h1>Unlock the World of Knowledge</h1>
                <p class="hero-subtitle">
                    Step into the next generation of library management. Seamlessly discover, organize, and manage your intellectual resources in one futuristic platform.
                </p>
                
                <div class="cta-buttons">
                    <a href="{{ route('login') }}" class="btn btn-primary">↳ Enter the System</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Create Account</a>
                </div>
            </section>

            <!-- Features Section -->
            <section class="features-section">
                <h2 class="section-title">Explore Our <span>Capabilities</span></h2>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">🔍</div>
                        <h3>Intelligent Search</h3>
                        <p>Discover exactly what you need with our advanced AI-powered search engine. Find resources across thousands of titles instantly.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">📖</div>
                        <h3>Vast Digital Library</h3>
                        <p>Access an expansive collection of books, journals, and academic resources. Knowledge at your fingertips, 24/7.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">👤</div>
                        <h3>Personal Dashboard</h3>
                        <p>Manage your borrowings, reservations, and reading history. Customize your library experience to match your preferences.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">🎯</div>
                        <h3>Smart Recommendations</h3>
                        <p>Get personalized book suggestions based on your reading patterns and interests. Discover your next favorite read.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h3>Lightning Fast Access</h3>
                        <p>Experience ultra-fast loading times and seamless navigation. Performance optimized for every device.</p>
                    </div>

                    <div class="feature-card">
                        <div class="feature-icon">🔐</div>
                        <h3>Secure & Private</h3>
                        <p>Your data is protected with enterprise-grade encryption. Privacy and security are our top priorities.</p>
                    </div>
                </div>

                <!-- Stats -->
                <div class="stats-section">
                    <div class="stat-card">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Active Resources</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">5K+</div>
                        <div class="stat-label">Happy Users</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Uptime</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Available</div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="cta-section">
                <h2>Ready to Transform Your Reading Experience?</h2>
                <p>Join thousands of users who are revolutionizing how they access and manage knowledge.</p>
                <a href="{{ route('register') }}" class="btn btn-primary">Start Your Journey Now</a>
            </section>
        </div>

        <!-- Footer -->
        <footer>
            <p>&copy; 2026 IIUM Library Management System - The Future of Knowledge Management. All rights reserved.</p>
        </footer>
    </body>
</html>
