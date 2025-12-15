<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transform Your Business with Salesforce & Agentforce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Poppins, sans-serif;
            color: #2c3e50;
            line-height: 1.6;
            background: #fafafa;
        }

        /* Color palette inspired by beminimalist.co */
        :root {
            --primary: #ec2e5b;
            --primary-dark: #460073;
            --secondary: #ff6086;
            --text-dark: #0e003f;
            --text-light: #333;
            --bg-light: #fafafa;
            --white: #ffffff;
        }

        header {
            background: var(--white);
            padding: 1.5rem 5%;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .hero {
            background: linear-gradient(135deg, var(--secondary) 0%, #fff5f2 100%);
            padding: 6rem 5% 4rem;
            text-align: center;
        }

        .hero-content {
            max-width: 900px;
            margin: 0 auto;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
            font-weight: 700;
        }

        .hero p {
            font-size: 1.3rem;
            color: var(--text-light);
            margin-bottom: 2.5rem;
        }

        .cta-button {
            background: var(--primary);
            color: white;
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .cta-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 155, 130, 0.3);
        }

        .section {
            padding: 5rem 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: var(--text-light);
            margin-bottom: 4rem;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .card {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 15px;
            transition: all 0.3s;
            border: 1px solid #f0f0f0;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            background: #ffeaea;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        .card-icon img {
            width: 50px;
            height: auto;
        }

        .card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--text-dark);
        }

        .card p {
            color: var(--text-light);
            line-height: 1.7;
        }

        .policies {
            background: var(--white);
        }

        .departments {
            background: var(--bg-light);
        }

        .industries {
            background: var(--white);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-item {
            text-align: center;
            padding: 2rem;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--secondary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
        }

        .agentforce-section {
            background: linear-gradient(135deg, #f8f9fa 0%, var(--secondary) 100%);
            padding: 5rem 5%;
            text-align: center;
        }

        .agentforce-content {
            max-width: 1000px;
            margin: 0 auto;
        }

        .benefits-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .benefit-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 12px;
            text-align: left;
        }

        .benefit-card h4 {
            color: var(--primary);
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
        }

        footer {
            background: var(--text-dark);
            color: white;
            padding: 3rem 5%;
            text-align: center;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .nav-links {
                display: none;
            }

            .section {
                padding: 3rem 5%;
            }
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <x-application-logo style="width: 10rem;" />
            </div>
            <ul class="nav-links">
                <li><a href="#Policies">Policies</a></li>
                <li><a href="#industries">Industries</a></li>
                <li><a href="#agentforce">Agentforce</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Empowering Your Organization With Transparent & Secure Policies</h1>
            <p>Access all Codleo policies in one centralized, secure hub.
                View updated guidelines, compliance documents, and regulatory information assigned to your organization — anytime, anywhere.</p>
            <button class="cta-button">View Your Policies</button>
        </div>
    </section>

    <section class="section Policies" id="Policies">
        <h2 class="section-title">Your Assigned Codleo Policies</h2>
        <p class="section-subtitle">Access all policies that your organization is required to review. Each policy is kept updated to ensure alignment with compliance, security standards, and operational best practices.</p>
        @if ($policies->isEmpty())
        <p style="text-align: center;">No Policies found.</p>
        @else
        @foreach ($policies as $policy)
        <div class="cards-grid">
            <a href="{{ route('client.policies.viewer', $policy->id) }}" style="text-decoration:none;">
                <div class="card">
                    <div class="card-icon">
                        <img src="{{ asset('assets/images/policy-logo.png') }}" alt="logo" />
                    </div>
                    <h3>{{ $policy->title }}</h3>
                    <p>{{ $policy->description }}</p>
                </div>
            </a>
        </div>
        @endforeach
        @endif
    </section>

    <!-- <section class="section">
        <div style="text-align: center;">
            <h2 class="section-title">Why Choose Us as Your Salesforce Partner</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">🏆</div>
                    <h3>Certified Experts</h3>
                    <p>Team of Salesforce-certified consultants with proven track records</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">🎯</div>
                    <h3>Custom Solutions</h3>
                    <p>Tailored implementations designed for your unique business needs</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">📞</div>
                    <h3>24/7 Support</h3>
                    <p>Ongoing maintenance and support to keep your systems running smoothly</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">💡</div>
                    <h3>Innovation First</h3>
                    <p>Stay ahead with latest Salesforce features and AI capabilities</p>
                </div>
            </div>

            <div style="margin-top: 3rem;">
                <button class="cta-button">Schedule a Consultation</button>
            </div>
        </div>
    </section> -->

    <footer id="contact">
        <div class="footer-content">
            <h3 style="margin-bottom: 1rem;">Ready to Transform Your Business?</h3>
            <p style="margin-bottom: 2rem;">Let's discuss how Salesforce and Agentforce can drive your growth</p>
            <button class="cta-button">Contact Us Today</button>
            <p style="margin-top: 3rem; color: #95a5a6;">© 2025 Codleo. Salesforce Partner. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>