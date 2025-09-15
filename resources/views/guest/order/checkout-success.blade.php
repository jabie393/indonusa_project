<x-front-layout>
    <div class="max-w-xl mx-auto p-6 text-center">
        <h1 class="mb-4 text-2xl font-bold">Checkout Berhasil!</h1>
        <p class="mb-4">Pesanan Anda akan diproses melalui WhatsApp.</p>
        <a id="wa-link" href="{{ $waUrl }}" target="_blank"
            class="inline-block rounded bg-green-600 px-6 py-2 text-white font-bold hover:bg-green-700 mb-4">
            Buka WhatsApp Sekarang
        </a>
        <p>Anda akan diarahkan ke halaman order setelah membuka WhatsApp...</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const waLink = document.getElementById('wa-link');

            function openWaAndRedirect() {
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

                if (isMobile) {
                    window.location.href = waLink.href;
                } else {
                    window.open(waLink.href, '_blank');
                }

                waLink.classList.add('pointer-events-none', 'opacity-50');

                setTimeout(function () {
                    window.location.href = "{{ route('order') }}";
                }, 2000);
            }

            // Event klik
            waLink.addEventListener('click', function (event) {
                event.preventDefault();
                openWaAndRedirect();
            });
        });
    </script>
</x-front-layout>
