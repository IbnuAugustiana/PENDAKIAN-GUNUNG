<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        <script>
            (function () {
                function isValidEmail(value) {
                    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                }

                document.addEventListener('submit', function (e) {
                    const form = e.target;
                    if (!(form instanceof HTMLFormElement)) return;

                    const email = form.querySelector('input[type="email"]');
                    if (email) {
                        email.value = String(email.value || '').replace(/\s+/g, '');
                        if (!email.value || !isValidEmail(email.value)) {
                            e.preventDefault();
                            Swal.fire({ icon: 'warning', title: 'Format email tidak valid.', confirmButtonColor: '#28a745' });
                            email.focus();
                            return;
                        }
                    }

                    if (form.dataset.confirmed === 'true') {
                        delete form.dataset.confirmed;
                        return;
                    }

                    if (form.dataset.confirmMessage) {
                        e.preventDefault();
                        Swal.fire({
                            icon: form.dataset.confirmIcon || 'question',
                            title: form.dataset.confirmTitle || 'Konfirmasi',
                            text: form.dataset.confirmMessage,
                            showCancelButton: true,
                            confirmButtonText: form.dataset.confirmOk || 'Ya, lanjutkan',
                            cancelButtonText: form.dataset.confirmCancel || 'Batal',
                            confirmButtonColor: '#28a745'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.dataset.confirmed = 'true';
                                form.requestSubmit ? form.requestSubmit() : form.submit();
                            }
                        });
                    }
                }, true);
            })();
        </script>
    </body>
</html>
