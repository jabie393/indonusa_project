<x-front-layout>
    <div class="max-w-xl mx-auto p-6 text-center">
        <h1 class="mb-4 text-2xl font-bold">Checkout Berhasil!</h1>
        <p class="mb-4">Pesanan Anda akan diproses melalui WhatsApp.</p>
        <a id="wa-link" href="{{ $waUrl }}" target="_blank" class="inline-block rounded bg-green-600 px-6 py-2 text-white font-bold hover:bg-green-700 mb-4">
            Buka WhatsApp Sekarang
        </a>
        <p>Anda akan diarahkan ke halaman order...</p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.open(document.getElementById('wa-link').href, '_blank');
            setTimeout(function() {
                window.location.href = "{{ route('order') }}";
            }, 2000);
        });
    </script>
</x-front-layout>
