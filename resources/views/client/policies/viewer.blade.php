@php
$user = Auth::guard('client')->user();
if(!$user){
return redirect()->route('login');
}
@endphp
<!DOCTYPE html>
<html lang="en" class="select-none">

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

            <!-- Toolbar -->
            <div class="bg-brand-black text-white px-4 py-2 flex justify-between items-center text-sm">
                <span id="page_num">Page 1 / --</span>
                <div class="flex gap-2">
                    <button id="prev" class="w-8 h-8 rounded hover:bg-white/10 flex items-center justify-center"><i class="fa-solid fa-chevron-left"></i></button>
                    <button id="next" class="w-8 h-8 rounded hover:bg-white/10 flex items-center justify-center"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
                <div class="flex gap-2">
                    <button id="zoom_out" class="w-8 h-8 rounded hover:bg-white/10"><i class="fa-solid fa-minus"></i></button>
                    <button id="zoom_in" class="w-8 h-8 rounded hover:bg-white/10"><i class="fa-solid fa-plus"></i></button>
                </div>
            </div>

            <!-- Canvas Container -->
            <div id="canvas-container" class="flex-grow overflow-auto flex justify-center p-8 relative bg-gray-500">
                <!-- The PDF Pages render here as canvases -->
                <canvas id="the-canvas" class="shadow-2xl"></canvas>

                <!-- Digital Watermark (Repeated) -->
                <div class="watermark pointer-events-none select-none">
                    CONFIDENTIAL - Codleo consulting - {{ \Illuminate\Support\Facades\Request::ip() }}
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
        // 1. PDF.js CONFIGURATION
        // Using a generic sample PDF for demonstration. Replace 'url' with your actual PDF path.
        const url = `{{ route('client.policies.stream', $policy->id) }}`;

        let pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 1.5,
            canvas = document.getElementById('the-canvas'),
            ctx = canvas.getContext('2d');

        /**
         * Get page info from document, resize canvas accordingly, and render page.
         */
        function renderPage(num) {
            pageRendering = true;
            // Fetch page
            pdfDoc.getPage(num).then(function(page) {
                var viewport = page.getViewport({
                    scale: scale
                });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Render PDF page into canvas context
                var renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                var renderTask = page.render(renderContext);

                // Wait for render to finish
                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            // Update page counters
            document.getElementById('page_num').textContent = `Page ${num} / ${pdfDoc.numPages}`;
        }

        /**
         * If another page rendering in progress, waits until the rendering is
         * finised. Otherwise, executes rendering immediately.
         */
        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        /**
         * Displays previous page.
         */
        function onPrevPage() {
            if (pageNum <= 1) return;
            pageNum--;
            queueRenderPage(pageNum);
        }
        document.getElementById('prev').addEventListener('click', onPrevPage);

        /**
         * Displays next page.
         */
        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) return;
            pageNum++;
            queueRenderPage(pageNum);
        }
        document.getElementById('next').addEventListener('click', onNextPage);

        /**
         * Zoom Controls
         */
        document.getElementById('zoom_in').addEventListener('click', () => {
            scale += 0.2;
            renderPage(pageNum);
        });
        document.getElementById('zoom_out').addEventListener('click', () => {
            if (scale > 0.6) {
                scale -= 0.2;
                renderPage(pageNum);
            }
        });

        /**
         * Asynchronously downloads PDF.
         */
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