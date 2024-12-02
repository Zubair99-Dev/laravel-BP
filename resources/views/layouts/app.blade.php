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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <script>
            function showCaptchaModal() {
                const modal = document.getElementById('captchaModal');
                modal.classList.remove('hidden'); // Remove the 'hidden' class to show the modal
            }

            function hideCaptchaModal() {
                const modal = document.getElementById('captchaModal');
                modal.classList.add('hidden'); // Add the 'hidden' class to hide the modal
            }

            
            document.addEventListener('DOMContentLoaded', function () {
                // Get the image element
                const captchaStatusImg = document.getElementById('captcha-status');
        
                // Check if the element exists on the page
                if (captchaStatusImg) {
                    // Add a click event listener
                    captchaStatusImg.addEventListener('click', function () {
                        // Get current status from the data-status attribute
                        let currentStatus = captchaStatusImg.getAttribute('data-status');
                        let newStatus = (currentStatus === '1') ? '0' : '1'; // Toggle the status
                        
                        // Make an AJAX request to update the captcha status in the database
                        fetch('{{ route('admin.updateCaptchaSetting') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Include CSRF token
                            },
                            body: JSON.stringify({
                                status: newStatus
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            // If the status is successfully updated in the database
                            if (data.success) {
                                // Update the image source based on the new status
                                captchaStatusImg.setAttribute('src', `{{ asset('images/') }}/${newStatus === '1' ? 'Enabled' : 'Disabled'}.png`);
                                captchaStatusImg.setAttribute('data-status', newStatus); // Update the status attribute
                            } else {
                                alert('Failed to update Captcha status.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while updating Captcha status.');
                        });
                    });
                }
            });
        </script>        
    </body>
</html>

