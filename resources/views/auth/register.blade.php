<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Inventaris Lab</title>
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
    <style>
        .input-field:focus {
            box-shadow: 0 0 0 2px #F5C23E;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen bg-gradient-to-br from-primary to-[#1a1b4b] p-4">
    <div class="bg-light p-8 rounded-lg shadow-xl w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-primary mb-2">Register</h1>
            <p class="text-gray-600">Create your account</p>
        </div>

        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ url('/register') }}" class="space-y-6">
            @csrf
            <div>
                <label class="block text-gray-700 font-medium mb-2">Email Address</label>
                <input type="email" name="email" required 
                    class="input-field w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none transition-all duration-200"
                    placeholder="Enter your email">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" required 
                    class="input-field w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none transition-all duration-200"
                    placeholder="Enter your password">
            </div>

            <button type="submit" 
                class="w-full bg-primary hover:bg-primary/90 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-secondary focus:ring-offset-2">
                Register
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" 
                class="text-primary hover:text-secondary font-medium transition-colors duration-200">
                Already have an account? Sign in
            </a>
        </div>

        <div class="mt-6 text-center text-sm text-gray-500">
            By registering, you agree to our Terms of Service and Privacy Policy
        </div>
    </div>
</body>

</html>