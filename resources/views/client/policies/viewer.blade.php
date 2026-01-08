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
    <title>Secure Policy Viewer | {{ $policy->title }}</title>

    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- PDF.js Library (Required to render PDF as images) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    </script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Branding Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            black: '#0e003f',
                            amaranth: '#ec2e5b',
                            purple: '#460073',
                            light: '#f8fafc',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        @media print {
            body * {
                display: none !important;
            }

            body::after {
                content: "CONFIDENTIAL: PRINTING & SAVING DISABLED";
                display: flex !important;
                justify-content: center;
                align-items: center;
                height: 100vh;
                font-size: 24pt;
                color: red;
                font-weight: bold;
            }
        }

        /* DISABLING SELECTION & DRAGGING */
        body {
            -webkit-user-select: none;
            /* Safari */
            -ms-user-select: none;
            /* IE 10 and IE 11 */
            user-select: none;
            /* Standard syntax */
            -webkit-touch-callout: none;
            /* iOS Safari */
        }

        /* SECURITY OVERLAY FOR WATERMARK */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 4rem;
            color: rgba(236, 46, 91, 0.08);
            /* Brand Amaranth with low opacity */
            font-weight: 800;
            white-space: nowrap;
            pointer-events: none;
            z-index: 50;
            width: 100%;
            text-align: center;
        }

        /* BLUR EFFECT FOR SCREENSHOT ATTEMPTS */
        .blur-screen {
            filter: blur(20px) grayscale(100%);
        }

        /* CUSTOM SCROLLBAR */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            bg: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #ec2e5b;
        }
    </style>
</head>

<body class="font-poppins bg-brand-light text-slate-800 min-h-screen flex flex-col" oncontextmenu="return false;">

    <!-- SECURITY WARNING OVERLAY (Hidden by default) -->
    <div id="security-alert" class="fixed inset-0 bg-brand-black z-[100] hidden flex-col items-center justify-center text-center p-6 text-white">
        <i class="fa-solid fa-triangle-exclamation text-6xl text-brand-amaranth mb-4"></i>
        <h2 class="text-3xl font-bold mb-2">Security Alert</h2>
        <p class="text-gray-300">Screenshots and Developer Tools are disabled on this page.<br>This action has been logged.</p>
        <button onclick="dismissAlert()" class="mt-6 bg-brand-amaranth px-6 py-2 rounded-lg font-bold hover:bg-white hover:text-brand-black transition">Return to Viewer</button>
    </div>

    <!-- HEADER -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
        <div class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="{{ route('client.dashboard') }}" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-brand-black hover:bg-brand-purple hover:text-white transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-brand-black leading-tight">{{ $policy->title }}</h1>
                    <span class="text-xs text-brand-amaranth font-semibold uppercase tracking-wider">Confidential Document</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex flex-col text-right">
                    <span class="text-xs font-bold text-gray-400 uppercase">Viewing as</span>
                    <span class="text-sm font-bold text-brand-black">{{ Auth::guard('client')->user()->name ?? 'Hi User' }}</span>
                </div>
                <div class="w-9 h-9 bg-brand-black rounded-full text-white flex items-center justify-center text-sm font-bold"><i class="fa-regular fa-user" style="color: #ffffff;"></i></div>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow container mx-auto px-4 py-8 md:flex gap-8 h-[calc(100vh-70px)]">

        <!-- LEFT PANEL: DETAILS -->
        <div class="md:w-1/3 lg:w-1/4 mb-6 md:mb-0 overflow-y-auto">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-2 mb-4">
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-0.5 rounded border border-green-200">Active</span>
                    <span class="text-gray-400 text-xs">Updated: {{$policy->updated_at->format('M d, Y') }}</span>
                </div>

                <h2 class="text-2xl font-bold text-brand-black mb-4">Policy Description</h2>
                <p class="text-sm text-gray-600 leading-relaxed mb-6">
                    {{ $policy->description }}
                </p>

                <div class="space-y-4">
                    <div class="bg-brand-light p-4 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Department</p>
                        <p class="font-semibold text-brand-purple"><i class="fa-solid fa-shield-halved mr-2"></i>{{ $policy->title }}</p>
                    </div>
                    <div class="bg-brand-light p-4 rounded-xl border border-gray-100">
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Policy Owner</p>
                        <p class="font-semibold text-brand-black">Codleo Consulting</p>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-100 pt-4">
                    <p class="text-xs text-brand-amaranth font-bold"><i class="fa-solid fa-lock mr-1"></i> Read-Only Mode</p>
                    <p class="text-[10px] text-gray-400 mt-1">Download and printing are disabled for security compliance.</p>
                </div>
            </div>
        </div>

        <!-- RIGHT PANEL: PDF VIEWER (SECURE CANVAS) -->
        <div class="md:w-2/3 lg:w-3/4 bg-gray-600 rounded-2xl shadow-inner overflow-hidden flex flex-col relative">

            <!-- TOOLBAR -->
            <div class="bg-gray-900 text-gray-300 h-14 flex items-center justify-between px-4 shadow-md z-30 flex-none border-b border-gray-700">
                <div class="flex items-center gap-2">
                    <button id="prev" class="hover:bg-gray-700 hover:text-white px-3 py-1.5 rounded transition"><i class="fa-solid fa-chevron-left"></i></button>
                    <span id="page_info" class="text-xs font-mono w-24 text-center">Loading...</span>
                    <button id="next" class="hover:bg-gray-700 hover:text-white px-3 py-1.5 rounded transition"><i class="fa-solid fa-chevron-right"></i></button>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center bg-gray-800 rounded-lg overflow-hidden border border-gray-600">
                        <button id="zoom_out" class="px-3 py-1.5 hover:bg-gray-700 hover:text-white transition border-r border-gray-600"><i class="fa-solid fa-minus"></i></button>
                        <span id="zoom_level" class="text-xs font-mono px-3 w-16 text-center text-white">50%</span>
                        <button id="zoom_in" class="px-3 py-1.5 hover:bg-gray-700 hover:text-white transition border-l border-gray-600"><i class="fa-solid fa-plus"></i></button>
                    </div>
                </div>
            </div>

            <!-- CANVAS CONTAINER -->
            <!-- Note: overflow-auto here handles the scrollbars when canvas gets big -->
            <!-- Note: "flex justify-center" keeps it centered when small, but allows scroll when big -->
            <div id="pdf-container" class="flex-1 overflow-auto relative bg-gray-700 p-8 text-center">

                <div class="inline-block relative shadow-2xl">
                    <!-- The PDF Canvas -->
                    <!-- Removed conflicting CSS width classes -->
                    <canvas id="the-canvas" class="block bg-white mx-auto"></canvas>

                    <!-- Watermark Layer (Attached to the canvas container) -->
                    <div id="watermark" class="watermark"></div>
                </div>

            </div>
        </div>
    </main>

    <footer class="my-4 px-4">
        <div class="border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-400">
            <p>&copy; {{ date('Y') }} All rights reserved by Codleo Consulting.</p>
            <div class="flex gap-6">
                <a href="https://www.codleo.com/privacy-policy" class="hover:text-brand-purple transition">Privacy Policy</a>
                <a href="https://www.codleo.com/terms-and-conditions" class="hover:text-brand-purple transition">Terms & Conditions</a>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        const url = `{{ route('client.policies.stream', $policy->id) }}`;

        let pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 0.5,
            canvas = document.getElementById('the-canvas'),
            ctx = canvas.getContext('2d'),
            renderTask = null;

        const zoomLevelDisplay = document.getElementById('zoom_level');
        const pdfContainer = document.getElementById('pdf-container');

        function renderPage(num) {
            pageRendering = true;

            pdfDoc.getPage(num).then(function(page) {

                const viewport = page.getViewport({
                    scale: scale
                });

                canvas.height = viewport.height;
                canvas.width = viewport.width;
                canvas.style.height = viewport.height + 'px';
                canvas.style.width = viewport.width + 'px';

                if (renderTask) {
                    renderTask.cancel();
                }

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    pageRendering = false;
                    renderTask = null;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }

                    updateWatermark(viewport.width, viewport.height);
                }).catch(function(error) {
                    // Ignore zoom cancellation errors
                });
            });

            document.getElementById('page_info').textContent = `Page ${num} / ${pdfDoc.numPages}`;
            zoomLevelDisplay.textContent = `${Math.round(scale * 100)}%`;
        }

        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        // 3. ZOOM CONTROLS
        document.getElementById('zoom_in').addEventListener('click', () => {
            if (scale >= 4.0) return;
            scale += 0.25; // Increase scale factor
            renderPage(pageNum);
        });

        document.getElementById('zoom_out').addEventListener('click', () => {
            if (scale <= 0.5) return;
            scale -= 0.25; // Decrease scale factor
            renderPage(pageNum);
        });

        document.getElementById('prev').addEventListener('click', () => {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        });

        document.getElementById('next').addEventListener('click', () => {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        });

        // Initialize PDF
        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            renderPage(pageNum);
        });

        // ==========================================
        // 2. SECURITY MEASURES IMPLEMENTATION
        // ==========================================

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
        document.addEventListener('contextmenu', event => event.preventDefault());

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

        // 1. BLOCK THE SPECIFIC SHORTCUT (Ctrl+Shift+S)
        document.addEventListener('keydown', function(e) {
            // Detect Ctrl + Shift + S (Standard Browser Screenshot shortcut)
            if (e.ctrlKey && e.shiftKey && (e.key === 's' || e.key === 'S')) {
                e.preventDefault();
                e.stopPropagation();
                alert('Security Restriction: Screenshot tool disabled.');
                return false;
            }
        });

        // 2. THE BLUR TRAP (Hides content if they click the Browser Menu)
        const contentContainer = document.getElementById('pdf-container');

        // When window loses focus (User clicks browser menu or switches tabs)
        window.addEventListener('blur', () => {
            contentContainer.style.filter = 'blur(20px) grayscale(100%)';
            contentContainer.style.opacity = '0.1';
            document.body.style.backgroundColor = '#000';
        });

        // When window regains focus (User clicks back on the document)
        window.addEventListener('focus', () => {
            contentContainer.style.filter = 'none';
            contentContainer.style.opacity = '1';
            document.body.style.backgroundColor = '#f8fafc'; // Restore brand color
        });

        // 3. VISIBILITY API (Hides content if they minimize or switch tabs)
        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                contentContainer.style.opacity = '0';
            } else {
                contentContainer.style.opacity = '1';
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