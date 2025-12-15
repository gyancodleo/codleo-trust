@extends('admin.layouts.main')

@section('main-content')

<div class="dvanimation animate__animated p-6" :class="[$store.app.animation]">
    <div x-data="basic">
        <div class="panel items-center overflow-x-auto whitespace-nowrap p-3 flex justify-between mb-4">
            <h1 class="text-xl font-bold">{{ $policy->title }}</h1>
            <a href="{{ route('admin.policy') }}" class="px-4 py-2 btn btn-outline-primary">Back</a>
        </div>
        <div class="panel mt-6">
            <div id="viewerContainer" class="relative border h-[90vh]">

                <!-- Watermark -->
                <!-- <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-10">
                    <div class="text-3xl rotate-[-25deg]">
                        Confidential • {{ auth('admin')->user()->name }} • {{ now() }}
                    </div>
                </div> -->

                <canvas id="pdfCanvas" class="w-full"></canvas>

            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.5.141/pdf.min.js"></script>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc =
            "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.5.141/pdf.worker.min.js";
        const loadingTask = pdfjsLib.getDocument({
            url: "{{ route('admin.policy.stream', $policy->id) }}",
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

        // Security: block download / print / right-click
        document.addEventListener("contextmenu", e => e.preventDefault());

        document.addEventListener("keydown", e => {
            if (e.ctrlKey && ["p", "s", "u"].includes(e.key.toLowerCase())) {
                e.preventDefault();
            }
        });
    </script>

    <script>
        /** SCREENSHOT DETECTION SYSTEM **/

        // Detect PrintScreen key
    //     document.addEventListener('keyup', function(e) {
    //         if (e.key === 'PrintScreen') {
    //             alert("Screenshots are not allowed.");
    //             // Replace canvas content temporarily
    //             blurDocumentViewer();
    //         }
    //     });

    //     document.addEventListener('keydown', async function(e) {
    //         if (e.ctrlKey && e.key.toLowerCase() === 'v') {
    //             try {
    //                 let clipboardItems = await navigator.clipboard.read();
    //                 for (let item of clipboardItems) {
    //                     if (item.types.includes("image/png")) {
    //                         alert("Image pasting detected — screenshots are not allowed.");
    //                         blurDocumentViewer();
    //                     }
    //                 }
    //             } catch (err) {}
    //         }
    //     });

    //     document.addEventListener("visibilitychange", () => {
    //         if (document.hidden) {
    //             blurDocumentViewer();
    //         }
    //     });

    //     function blurDocumentViewer() {
    //         let viewer = document.getElementById("viewerContainer");
    //         viewer.style.filter = "blur(12px)";
    //         viewer.innerHTML += `
    //     <div id="warningOverlay"
    //          class="absolute inset-0 bg-black/70 flex items-center justify-center text-white text-xl">
    //         Secure viewer disabled due to suspicious activity.
    //     </div>
    // `;
    //     }

    //     /** DEVTOOLS DETECTION **/

    //     let devtoolsOpen = false;

    //     const threshold = 160; // px threshold for panels

    //     setInterval(function() {
    //         if (
    //             window.outerHeight - window.innerHeight > threshold ||
    //             window.outerWidth - window.innerWidth > threshold
    //         ) {
    //             if (!devtoolsOpen) {
    //                 devtoolsOpen = true;
    //                 disableSecureViewer();
    //             }
    //         } else {
    //             devtoolsOpen = false;
    //         }
    //     }, 500);

    //     function disableSecureViewer() {
    //         let viewer = document.getElementById("viewerContainer");
    //         viewer.innerHTML = `
    //     <div class="absolute inset-0 bg-black/90 flex items-center justify-center text-white text-2xl">
    //         Developer tools detected. Viewer disabled.
    //     </div>
    // `;
    //     }
    </script>
    @endsection