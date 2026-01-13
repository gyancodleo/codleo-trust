@php
$user = Auth::guard('client')->user();
if(!$user){
return redirect()->route('login');
}
@endphp
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codleo Policies Portal</title>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Branding Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#0e003f',
                            /* Vampire Black */
                            accent: '#ec2e5b',
                            /* accent */
                            purple: '#460073',
                            /* Pinkish Purple */
                            light: '#f8fafc',
                            /* Ultra light gray bg */
                        }
                    },
                    boxShadow: {
                        'glow': '0 0 20px rgba(236, 46, 91, 0.3)',
                        'card': '0 10px 40px -10px rgba(0,0,0,0.08)'
                    },
                    backgroundImage: {
                        'hero-pattern': "url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\")",
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Animations */
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</head>

<body class="font-poppins bg-brand-light text-slate-800 flex flex-col min-h-screen selection:bg-brand-accent selection:text-white" oncontextmenu="return false;">

    <!-- Modern Glass Header -->
    <header class="fixed w-full top-0 z-50 transition-all duration-300 backdrop-blur-md bg-white/80 border-b border-gray-100">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">

            <!-- Logo -->
            <div class="flex items-center gap-3 cursor-pointer group">
                <!-- <div class="w-10 h-10 bg-gradient-to-br from-brand-dark to-brand-purple rounded-xl flex items-center justify-center text-white shadow-lg group-hover:shadow-brand-purple/30 transition-all">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <span class="text-xl font-bold text-brand-dark tracking-tight">Cod<span class="text-brand-accent">leo</span></span> -->
                <img src="{{  asset('images/Codleo-Logo-Black.png') }}" class="w-32" alt="codleo consulting">
            </div>

            <!-- User Profile -->
            <div class="relative">
                <button id="userMenuBtn" class="flex items-center gap-3 focus:outline-none p-1 pr-3 rounded-full hover:bg-gray-100 transition duration-300 border border-transparent hover:border-gray-200">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-r from-brand-purple to-brand-accent p-[2px]">
                        <div class="w-full h-full bg-white rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    </div>
                    <div class="hidden md:block text-left leading-tight">
                        <span class="block text-sm font-bold text-brand-dark">{{ 'Hi '. Auth::guard('client')->user()->name ?? 'Hi User' }}</span>
                        <!-- <span class="block text-[10px] text-gray-500 font-medium uppercase tracking-wider">Design Dept</span> -->
                    </div>
                    <i class="fa-solid fa-chevron-down text-gray-400 text-xs ml-1 transition-transform duration-300" id="chevronIcon"></i>
                </button>

                <!-- Floating Dropdown -->
                <div id="userDropdown" class="hidden absolute right-0 mt-4 w-56 bg-white rounded-2xl shadow-2xl py-3 border border-gray-100 transform origin-top-right transition-all duration-200 z-50">
                    <div class="px-5 py-3 border-b border-gray-50 md:hidden">
                        <p class="text-sm font-bold text-brand-dark">{{ Auth::guard('client')->user()->name ?? 'Hi User' }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::guard('client')->user()->email ?? 'user@example.com' }}</p>
                    </div>
                    <a href="#" class="flex items-center px-5 py-3 text-sm text-gray-600 hover:bg-gray-50 hover:text-brand-purple transition-colors sm:hidden">
                        <i class="fa-regular fa-envelope"></i>&nbsp;{{ Auth::guard('client')->user()->email ?? 'user@example.com' }}
                    </a>
                    <form action="{{ route('client.force.logout') }}" class="inline" method="post">
                        @csrf
                        <button type="submit" class="flex items-center px-5 py-3 text-sm text-brand-accent hover:bg-red-50 font-medium transition-colors w-full">
                            <i class="fa-solid fa-arrow-right-from-bracket w-6"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-20">

        <!-- Modern Hero Section with Ambient Glow -->
        <!-- <section class="relative bg-brand-dark overflow-hidden rounded-b-[3rem] lg:rounded-b-[4rem] shadow-2xl z-10">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-brand-purple rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-brand-accent rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>

            <div class="relative container mx-auto px-6 py-20 lg:py-32 flex flex-col items-center text-center">
                <span class="inline-block py-1 px-3 rounded-full bg-white/10 border border-white/10 backdrop-blur-sm text-brand-accent text-xs font-bold tracking-widest uppercase mb-6 animate-pulse">
                    Internal Portal v2.0
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight tracking-tight">
                    Corporate <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-accent to-purple-400">Governance</span>
                    <br class="hidden md:block" /> & Standards
                </h1>
                <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto mb-10 font-light leading-relaxed">
                    Access the definitive source of truth for organization protocols, employee benefits, and compliance standards.
                </p>
                
                <div class="flex gap-4">
                    <a href="#policies" class="bg-brand-accent hover:bg-pink-600 text-white px-8 py-4 rounded-full font-semibold shadow-glow transition-all transform hover:-translate-y-1 flex items-center gap-2">
                        Explore Policies <i class="fa-solid fa-arrow-down"></i>
                    </a>
                </div>
            </div>
        </section> -->

        <!-- BRAND ALIGNED HERO SECTION -->
        <section class="relative bg-gradient-to-br from-brand-dark to-brand-purple text-white overflow-hidden">
            <!-- Geometric Pattern Overlay (Subtle Texture) -->
            <div class="absolute inset-0 bg-hero-pattern opacity-30"></div>

            <!-- Modern Gradient Glow (Subtle) -->
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-brand-accent/10 to-transparent pointer-events-none"></div>

            <div class="container mx-auto px-6 py-20 lg:py-28 relative z-10">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-12">

                    <!-- Content -->
                    <div class="lg:w-1/2 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-xs font-semibold tracking-wide uppercase mb-6 text-brand-accent bg-opacity-10 backdrop-blur-sm">
                            <span class="w-2 h-2 rounded-full bg-brand-accent animate-pulse"></span>
                            Policies Dashboard
                        </div>

                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                            Trusted Policies. <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-white to-gray-300">
                                Clear. Secure.
                            </span>
                        </h1>

                        <p class="text-lg text-gray-200 mb-10 font-light leading-relaxed max-w-lg mx-auto lg:mx-0">
                            Access all Codleo policies in one centralized, secure hub.
                            View updated guidelines, compliance documents, and regulatory information assigned to your organization — anytime, anywhere.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="#policies" class="bg-brand-accent hover:bg-[#d61e4b] text-white px-8 py-3.5 rounded-lg font-semibold shadow-glow transition-all transform hover:-translate-y-0.5 text-center">
                                Browse Policies
                            </a>
                            <a href="https://www.codleo.com/contact" target="_blank" class="px-8 py-3.5 rounded-lg font-semibold text-white border border-white/30 hover:bg-white/10 transition-colors text-center backdrop-blur-sm">
                                Contact Support
                            </a>
                        </div>
                    </div>

                    <!-- Visual / Abstract Graphic -->
                    <div class="lg:w-5/12 hidden lg:block relative">
                        <!-- Abstract Card Representation -->
                        <div class="relative bg-white/5 backdrop-blur-sm border border-white/10 p-6 rounded-2xl shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-500">
                            <div class="flex items-center gap-3 mb-4 border-b border-white/10 pb-4">
                                <div class="w-3 h-3 rounded-full bg-brand-accent"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-2 bg-white/20 rounded w-3/4"></div>
                                <div class="h-2 bg-white/20 rounded w-1/2"></div>
                                <div class="h-24 bg-gradient-to-r from-brand-dark/50 to-brand-purple/50 rounded-lg mt-4 border border-white/5 flex items-center justify-center">
                                    <i class="fa-solid fa-shield-halved text-4xl text-white/20"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Badge -->
                        <div class="absolute -bottom-6 -left-6 bg-white text-brand-dark p-4 rounded-xl shadow-lg border-l-4 border-brand-accent animate-bounce" style="animation-duration: 3s;">
                            <div class="flex items-center gap-3">
                                <div class="bg-green-100 p-2 rounded-full text-green-600">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 font-bold uppercase">Status</p>
                                    <p class="text-sm font-bold">Up to Date</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Curve Divider -->
            <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
                <svg class="relative block w-full h-[60px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                    <path d="M985.66,92.83C906.67,72,823.78,31,432.84,28.5,331.14,27.87,130.63,44,0,94.94V120H1200V0C1164.76,33.58,1064.6,113.73,985.66,92.83Z" class="fill-brand-light"></path>
                </svg>
            </div>
        </section>

        <!-- Policies Section -->
        <section id="policies" class="container mx-auto px-6 py-20 -mt-10">
            <!-- Category Header -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-gray-200 pb-4">
                <div>
                    <h2 class="text-3xl font-bold text-brand-dark">Codleo Policies</h2>
                    <p class="text-gray-500 mt-2">Access all policies that your organization is required to review. Each policy is kept updated to ensure alignment with compliance, security standards, and operational best practices.</p>
                </div>

                <!-- Search (Visual Only) -->
                <!-- <div class="relative mt-4 md:mt-0 w-full md:w-72">
                    <input type="text" placeholder="Search policies..."
                        class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-brand-purple focus:ring-1 focus:ring-brand-purple transition-all shadow-sm">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                </div> -->
            </div>
            <!-- Category 1: HR -->
            <div class="mb-16">
                @if ($policiesByCategory == null || $policiesByCategory->isEmpty())
                <p class="text-center text-dark-500">No Policies Assigned.</p>
                <p class="text-center text-gray-500">Please log out and then log back in. If the issue persists, kindly reach out to Codleo for further assistance.</p>
                @else
                @foreach ($policiesByCategory as $categoryName=>$policies)
                <div class="flex items-center gap-4 mb-8 pl-2 mt-12">
                    <div class="h-8 w-1 bg-gradient-to-b from-brand-purple to-brand-accent rounded-full"></div>
                    <h2 class="text-2xl md:text-3xl font-bold text-brand-dark">{{ $categoryName }}</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($policies as $policy)
                    <!-- Modern Card 1 -->
                    <a href="{{ route('client.policies.viewer', $policy->policy_id) }}">
                        <div class="group bg-white rounded-3xl p-8 shadow-card border border-white hover:border-brand-purple/20 transition-all duration-300 hover:-translate-y-2 relative overflow-hidden">
                            <div class="absolute top-0 right-0 bg-brand-light w-24 h-24 rounded-bl-full -mr-4 -mt-4 transition-colors group-hover:bg-brand-purple/5"></div>

                            <div class="w-14 h-14 bg-brand-light rounded-2xl flex items-center justify-center text-brand-purple text-2xl mb-6 shadow-inner group-hover:bg-brand-purple group-hover:text-white transition-colors duration-300">
                                <i class="fa-solid fa-book-open-reader"></i>
                            </div>

                            <h3 class="text-xl font-bold text-slate-800 mb-3 group-hover:text-brand-purple transition-colors">{{ $policy->policy->title }}</h3>
                            <p class="text-gray-500 text-sm mb-6 leading-relaxed">{{ Illuminate\Support\str::limit($policy->policy->description, 50 ?? '-' ) }}</p>

                            <div class="flex justify-between items-center border-t border-gray-100 pt-4">
                                <span class="text-xs font-medium text-gray-400 bg-gray-50 px-2 py-1 rounded">PDF • 2.4 MB</span>
                                <button class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-brand-accent group-hover:text-white transition-all">
                                    <i class="fa-solid fa-arrow-right -rotate-45 group-hover:rotate-0 transition-transform"></i>
                                </button>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endforeach
                @endif
            </div>
        </section>
    </main>

    <!-- Clean Modern Footer -->
    <footer class="bg-white border-t border-gray-200 pt-16 pb-8">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-10 mb-12">

                <!-- Brand Area -->
                <div class="max-w-md">
                    <div class="flex items-center gap-3 mb-4">
                        <!-- <div class="w-8 h-8 bg-brand-dark rounded-lg flex items-center justify-center text-white text-sm">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <span class="text-lg font-bold text-brand-dark">Cod<span class="text-brand-accent">leo</span></span> -->
                        <img src="{{  asset('images/Codleo-Logo-Black.png') }}" class="w-32" alt="codleo consulting">
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        <strong>Confidentiality Notice:</strong> The documents listed on this portal are for internal organizational use only. Distribution to external parties without written consent is strictly prohibited and monitored.
                    </p>
                </div>

                <!-- CTA -->
                <div class="bg-brand-light p-6 rounded-2xl w-full lg:w-auto text-center lg:text-left">
                    <h4 class="text-brand-dark font-bold mb-2">Need Help?</h4>
                    <p class="text-sm text-gray-500 mb-4">Can't find the policy you're looking for?</p>
                    <button onclick="window.open('https://www.codleo.com/contact', '_blank')" class="w-full bg-white border border-gray-200 text-brand-dark font-semibold py-2 px-6 rounded-xl hover:bg-brand-accent hover:text-white hover:border-brand-accent transition-all shadow-sm">
                        Contact Us
                    </button>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-400">
                <p>&copy; {{ date('Y') }} All rights reserved by Codleo Consulting.</p>
                <div class="flex gap-6">
                    <a href="https://www.codleo.com/privacy-policy" class="hover:text-brand-purple transition">Privacy Policy</a>
                    <a href="https://www.codleo.com/terms-and-conditions" class="hover:text-brand-purple transition">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userBtn = document.getElementById('userMenuBtn');
            const userDropdown = document.getElementById('userDropdown');
            const chevron = document.getElementById('chevronIcon');

            // Toggle Dropdown with Animation
            userBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (userDropdown.classList.contains('hidden')) {
                    userDropdown.classList.remove('hidden');
                    // Small delay to allow display:block to apply before opacity transition
                    setTimeout(() => {
                        userDropdown.classList.add('opacity-100', 'translate-y-0');
                        userDropdown.classList.remove('opacity-0', '-translate-y-2');
                    }, 10);
                    chevron.classList.add('rotate-180');
                } else {
                    closeDropdown();
                }
            });

            function closeDropdown() {
                userDropdown.classList.remove('opacity-100', 'translate-y-0');
                userDropdown.classList.add('opacity-0', '-translate-y-2');
                chevron.classList.remove('rotate-180');

                // Wait for animation to finish before hiding
                setTimeout(() => {
                    userDropdown.classList.add('hidden');
                }, 200);
            }

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    closeDropdown();
                }
            });
        });

        const securityAlert = document.getElementById('security-alert');

        function triggerSecurityAlert() {
            securityAlert.classList.remove('hidden');
            securityAlert.classList.add('flex');
        }

        function dismissAlert() {
            securityAlert.classList.add('hidden');
            securityAlert.classList.remove('flex');
        }

        // A. Disable Right Click
        document.addEventListener('contextmenu', function() {
            event => event.preventDefault()
            autoLogout();
        });

        // B. Disable Keyboard Shortcuts (Inspect, Save, Print)
        document.addEventListener('keydown', function(e) {
            // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
            if (
                e.key === 'F12' ||
                (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'J' || e.key === 'C')) ||
                (e.ctrlKey && e.key === 'U')
            ) {
                autoLogout();
            }

            // Disable Ctrl+S (Save), Ctrl+P (Print)
            if (e.ctrlKey && (e.key === 's' || e.key === 'p')) {
                autoLogout();
            }
        });

        // C. Screenshot Deterrent (PrintScreen Key)
        // Note: Browsers cannot strictly Block OS screenshots, but we can detect the key 
        // and blur the screen or clear the clipboard content immediately.
        document.addEventListener('keyup', (e) => {
            if (e.key === 'PrintScreen') {
                navigator.clipboard.writeText(''); // Clear clipboard
                document.body.classList.add('blur-screen');
                autoLogout();
            }
        });

        // D. Developer Tools Detection (Debugger Trap)
        // This slows down the script execution if DevTools are open
        setInterval(() => {
            const start = new Date();
            debugger; // This pauses execution if DevTools is open
            const end = new Date();
            if (end - start > 100) {
                document.body.innerHTML = '<h1 style="color:red; text-align:center; margin-top:50px;">Developer Tools Detected. Access Denied.</h1>';
                autoLogout();
            }
        }, 1000);

        function autoLogout() {
            fetch("{{ route('client.force.logout') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            }).then(() => {
                window.location.href = "/login"; // or client login route
            });
        }
    </script>
</body>

</html>