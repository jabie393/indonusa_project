<dialog id="guide" class="modal">
    @php
        // Map each role to its own guide assets.
        // Update these URLs/paths based on your real files and videos.
        $roleGuideMap = [
            'sales' => [
                'pdf' => asset('file/docs/manual-guide-sales.pdf'),
                'video' => 'https://www.youtube.com/watch?v=uV9koQm__fI',
            ],
            'supervisor' => [
                'pdf' => asset('docs/manual-guide-supervisor.pdf'),
                'video' => 'https://www.youtube.com/watch?v=uV9koQm__fI',
            ],
            'warehouse' => [
                'pdf' => asset('docs/manual-guide-warehouse.pdf'),
                'video' => 'https://www.youtube.com/watch?v=uV9koQm__fI',
            ],
            'general affair' => [
                'pdf' => asset('file/docs/PEDOMAN.pdf'),
                'video' => 'https://www.youtube.com/watch?v=uV9koQm__fI',
            ],
            // Fallback when role is missing or not mapped
            'default' => [
                'pdf' => asset('docs/manual-guide.pdf'),
                'video' => 'https://www.youtube.com/watch?v=uV9koQm__fI',
            ],
        ];
    @endphp

    <div
        class="modal-box inset-shadow-none dark:inset-shadow-gray-500 dark:inset-shadow-sm relative flex max-w-4xl flex-col overflow-hidden rounded-2xl bg-white p-0 shadow dark:bg-gray-700 sm:max-h-[90vh]">
        {{-- Header --}}
        <div class="flex items-center justify-between rounded-t border-b bg-gradient-to-r from-[#225A97] to-[#0D223A] p-4 dark:border-gray-600">
            <h3 class="text-lg font-semibold text-white">Panduan Manual</h3>
            <div class="modal-action m-0">
                <form method="dialog">
                    <button
                        class="ml-auto inline-flex items-center rounded-lg bg-transparent p-1.5 text-sm text-gray-300 hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg aria-hidden="true" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Content --}}
        <div id="guideContent" class="bg-slate-50 px-6 py-6 dark:border-gray-700 dark:bg-gray-800 sm:px-7">
            {{-- Guide selector --}}
            <div id="guideSelector" class="grid gap-4 md:grid-cols-2">
                <div class="col-span-2 mb-2">
                    <p class="text-sm text-slate-600 dark:text-gray-300">
                        Pilih salah satu tombol di bawah untuk membuka panduan dalam bentuk PDF atau video.
                    </p>
                </div>
                <button type="button"
                    class="guide-option group rounded-2xl border border-slate-200 bg-white p-5 text-left transition duration-200 hover:-translate-y-0.5 hover:border-[#225A97] hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#225A97] dark:border-gray-600 dark:bg-gray-700"
                    data-guide-type="pdf">
                    <div
                        class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-slate-900 dark:text-white">File PDF</h4>
                    <p class="mt-1 text-sm text-slate-600 dark:text-gray-300">Baca panduan tertulis secara lengkap.</p>
                </button>

                <button type="button"
                    class="guide-option group rounded-2xl border border-slate-200 bg-white p-5 text-left transition duration-200 hover:-translate-y-0.5 hover:border-[#225A97] hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#225A97] dark:border-gray-600 dark:bg-gray-700"
                    data-guide-type="video">
                    <div
                        class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-r from-[#225A97] to-[#0D223A] text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14.752 11.168l-4.586-2.65A1 1 0 008.667 9.4v5.2a1 1 0 001.499.866l4.586-2.65a1 1 0 000-1.732z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold text-slate-900 dark:text-white">Panduan Video</h4>
                    <p class="mt-1 text-sm text-slate-600 dark:text-gray-300">Tonton langkah-langkah penggunaan panduan.</p>
                </button>
            </div>

            {{-- Dynamic viewer --}}
            <div id="guideViewer" class="hidden">
                <div class="mb-4 flex items-center justify-between">
                    <h4 id="guideViewerTitle" class="text-lg font-semibold text-slate-900 dark:text-white"></h4>
                </div>
                <div id="guideViewerContent" class="h-[60vh] overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-gray-600 dark:bg-gray-900"></div>
                <footer class="flex items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 pt-5 dark:bg-gray-800 dark:border-gray-700">
                    <button id="backToGuideSelector" class="px-6 py-2 text-sm font-medium text-slate-600 transition ring-1 ring-slate-200 hover:bg-slate-200 active:scale-95 rounded-xl dark:text-gray-300 dark:hover:bg-gray-700" type="submit">
                        Kembali
                    </button>
                </footer>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    (function() {
        const guideModal = document.getElementById('guide');
        const selector = document.getElementById('guideSelector');
        const viewer = document.getElementById('guideViewer');
        const viewerTitle = document.getElementById('guideViewerTitle');
        const viewerContent = document.getElementById('guideViewerContent');
        const backButton = document.getElementById('backToGuideSelector');

        if (!guideModal || !selector || !viewer || !viewerTitle || !viewerContent || !backButton) return;

        const userRole = @json(strtolower(trim(auth()->user()->role ?? '')));
        const roleGuideMap = @json($roleGuideMap);
        const guideForRole = roleGuideMap[userRole] || roleGuideMap.default;

        const getYoutubeVideoId = (url) => {
            if (!url) return '';
            const trimmed = String(url).trim();
            const directId = trimmed.match(/^[a-zA-Z0-9_-]{11}$/);
            if (directId) return trimmed;

            try {
                const parsed = new URL(trimmed);

                if (parsed.hostname.includes('youtu.be')) {
                    return parsed.pathname.replace('/', '').slice(0, 11);
                }

                if (parsed.searchParams.get('v')) {
                    return parsed.searchParams.get('v').slice(0, 11);
                }

                const parts = parsed.pathname.split('/').filter(Boolean);
                const embedIndex = parts.findIndex((part) => ['embed', 'shorts', 'live'].includes(part));
                if (embedIndex !== -1 && parts[embedIndex + 1]) {
                    return parts[embedIndex + 1].slice(0, 11);
                }
            } catch (error) {
                return '';
            }

            return '';
        };

        const buildYoutubeEmbedUrl = (rawUrl) => {
            const videoId = getYoutubeVideoId(rawUrl);
            if (!videoId) return '';
            return `https://www.youtube-nocookie.com/embed/${videoId}?rel=0&modestbranding=1&enablejsapi=1`;
        };

        const stopYoutubePlayback = () => {
            const iframe = viewerContent.querySelector('iframe');
            if (!iframe || !iframe.src.includes('youtube')) return;
            try {
                iframe.contentWindow?.postMessage(
                    JSON.stringify({
                        event: 'command',
                        func: 'pauseVideo',
                        args: [],
                    }),
                    '*'
                );
            } catch (error) {
                // no-op
            }
        };

        const renderViewer = (type) => {
            if (type === 'pdf') {
                viewerTitle.textContent = 'Panduan PDF';
                viewerContent.innerHTML =
                    `<iframe src="${guideForRole.pdf}" class="h-full w-full" title="Panduan PDF"></iframe>`;
            } else {
                viewerTitle.textContent = 'Panduan Video';
                const embedUrl = buildYoutubeEmbedUrl(guideForRole.video);
                if (!embedUrl) {
                    viewerContent.innerHTML = `
                        <div class="flex h-full flex-col items-center justify-center gap-3 p-6 text-center">
                            <p class="text-sm text-slate-600 dark:text-gray-300">Tautan video untuk role ini tidak valid.</p>
                        </div>`;
                } else {
                    viewerContent.innerHTML =
                        `<iframe src="${embedUrl}" class="h-full w-full" title="Panduan Video" referrerpolicy="strict-origin-when-cross-origin" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
                }
            }

            selector.classList.add('hidden');
            viewer.classList.remove('hidden');
        };

        selector.querySelectorAll('.guide-option').forEach((button) => {
            button.addEventListener('click', function() {
                renderViewer(this.dataset.guideType);
            });
        });

        backButton.addEventListener('click', function() {
            viewer.classList.add('hidden');
            selector.classList.remove('hidden');
            viewerContent.innerHTML = '';
        });

        // Stop video only whenever modal is closed, without changing current view.
        guideModal.addEventListener('close', function() {
            stopYoutubePlayback();
        });
    })();
</script>
