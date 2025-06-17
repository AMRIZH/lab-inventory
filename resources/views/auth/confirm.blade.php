<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Confirmed - Inventaris Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2F3185',
                        secondary: '#F5C23E',
                        light: '#FEFEFE',
                        surface: '#F2F2F2',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface min-h-screen flex items-center justify-center">
    <div class="bg-light p-8 rounded-lg shadow-lg max-w-md w-full mx-4">
        <div class="text-center" id="loading-state">
            <!-- Loading Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Memproses Konfirmasi Email...</h1>
            <p class="text-gray-600 mb-6">
                Mohon tunggu sebentar, kami sedang memproses konfirmasi email Anda.
            </p>
        </div>

        <div class="text-center hidden" id="success-state">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Email Terkonfirmasi!</h1>
            <p class="text-gray-600 mb-6">
                Selamat! Email Anda telah berhasil dikonfirmasi. Anda akan diarahkan ke dashboard dalam beberapa detik.
            </p>
            
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" 
                   class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/90 transition-colors block text-center">
                    Masuk ke Dashboard
                </a>
            </div>
        </div>

        <div class="text-center hidden" id="error-state">
            <!-- Error Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Konfirmasi Gagal</h1>
            <p class="text-gray-600 mb-6" id="error-message">
                Terjadi kesalahan saat konfirmasi email. Silakan coba lagi.
            </p>
            
            <div class="space-y-3">
                <a href="{{ route('login') }}" 
                   class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-primary/90 transition-colors block text-center">
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>    <script>
        // Parse URL hash fragments for Supabase tokens
        function parseHashParams() {
            const hash = window.location.hash.substring(1);
            const params = new URLSearchParams(hash);
            return {
                access_token: params.get('access_token'),
                refresh_token: params.get('refresh_token'),
                token_type: params.get('token_type'),
                type: params.get('type'),
                error: params.get('error'),
                error_description: params.get('error_description')
            };
        }

        // Process confirmation
        function processConfirmation() {
            const params = parseHashParams();
            
            // Check for errors
            if (params.error) {
                showError(params.error_description || params.error);
                return;
            }

            // Check for access token and signup type
            if (params.access_token && params.type === 'signup') {
                // Send tokens to backend
                fetch('{{ route("auth.confirm") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        access_token: params.access_token,
                        refresh_token: params.refresh_token,
                        token_type: params.token_type,
                        type: params.type
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccess();
                        // Redirect to dashboard after 2 seconds
                        setTimeout(() => {
                            window.location.href = '{{ route("dashboard") }}';
                        }, 2000);
                    } else {
                        showError(data.message || 'Konfirmasi gagal');
                    }
                })
                .catch(error => {
                    showError('Terjadi kesalahan jaringan');
                });
            } else {
                showError('Token konfirmasi tidak valid atau sudah kedaluwarsa');
            }
        }

        function showSuccess() {
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('success-state').classList.remove('hidden');
            document.getElementById('error-state').classList.add('hidden');
        }

        function showError(message) {
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('success-state').classList.add('hidden');
            document.getElementById('error-state').classList.remove('hidden');
            document.getElementById('error-message').textContent = message;
        }

        // Start processing when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a moment for any redirects, then process
            setTimeout(processConfirmation, 500);
        });
    </script>
</body>

</html>
