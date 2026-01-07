
<h1 class="text-xl font-semibold mb-4 text-center" style="text-align:center;">{{ $policy->title }}</h1>
<p class="text-sm dark pt-4 my-8" style="text-align: center;">{{ $policy->description }}</p>
<div class="relative border shadow-sm">

    <div id="viewerContainer" class="relative border h-[90vh]" style="text-align: center;">

        <!-- Watermark -->
        <!-- <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-10">
                    <div class="text-3xl rotate-[-25deg]">
                        Confidential • {{ auth('client')->user()->name }} • {{ now() }}
                    </div>
                </div> -->

        <canvas id="pdfCanvas" class="w-full"></canvas>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.5.141/pdf.min.js"></script>

<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.5.141/pdf.worker.min.js";
    const loadingTask = pdfjsLib.getDocument({
        url: "{{ route('client.policies.stream', $policy->id) }}",
        withCredentials: true,
    });

    loadingTask.promise.then(function(pdf) {
        console.log("PDF loaded");

        const totalPages = pdf.numPages;
        const container = document.getElementById("viewerContainer");

        container.innerHTML = "";

        for (let pageNumber = 1; pageNumber <= totalPages; pageNumber++) {
            pdf.getPage(pageNumber).then(function(page) {
                let viewport = page.getViewport({
                    scale: 1.5
                });

                let canvas = document.createElement("canvas");
                let context = canvas.getContext("2d");

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                container.appendChild(canvas);

                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
            });
        }
    });
    document.addEventListener("contextmenu", e => e.preventDefault());
    document.addEventListener("keydown", e => {
        if (e.ctrlKey && (e.key === "s" || e.key === "p")) e.preventDefault();
    });
    /** SCREENSHOT DETECTION SYSTEM **/

    // Detect PrintScreen key
    document.addEventListener('keyup', function(e) {
        if (e.key === 'PrintScreen') {
            alert("Screenshots are not allowed.");
            // Replace canvas content temporarily
            autoLogout();
        }
    });

    document.addEventListener('keydown', async function(e) {
        if (e.ctrlKey && e.key.toLowerCase() === 'v') {
            try {
                let clipboardItems = await navigator.clipboard.read();
                for (let item of clipboardItems) {
                    if (item.types.includes("image/png")) {
                        alert("Image pasting detected — screenshots are not allowed.");
                        autoLogout();
                    }
                }
            } catch (err) {}
        }
    });

    document.addEventListener("visibilitychange", () => {
        if (document.hidden) {
            blurDocumentViewer();
        }
    });

    function blurDocumentViewer() {
        let viewer = document.getElementById("pdfCanvas");
        viewer.style.filter = "blur(12px)";
        viewer.innerHTML += `
        <div id="warningOverlay"
             class="absolute inset-0 bg-black/70 flex items-center justify-center text-white text-xl">
            Secure viewer disabled due to suspicious activity.
        </div>
    `;
    }

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

    /** DEVTOOLS DETECTION **/

    let devtoolsOpen = false;

    const threshold = 160; // px threshold for panels

    setInterval(function() {
        if (
            window.outerHeight - window.innerHeight > threshold ||
            window.outerWidth - window.innerWidth > threshold
        ) {
            if (!devtoolsOpen) {
                devtoolsOpen = true;
                autoLogout();
            }
        } else {
            devtoolsOpen = false;
        }
    }, 500);
</script>