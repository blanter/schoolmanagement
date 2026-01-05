@section('title', 'Login - Management App')
<x-guest-layout>
    <div class="login-container">
        <div class="image-section">
            <div class="image-placeholder">
                <img src="{{asset('/img/classwork.webp')}}" />
            </div>
        </div>

        <div class="form-section">
            <div class="brand">LIFEBOOK ACADEMY</div>
            <h1 class="welcome-title">Hi Teacher</h1>
            <p class="welcome-subtitle">Welcome to School Management</p>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="form-group">
                    <input type="email" name="email" class="form-input" placeholder="Email" value="{{ old('email') }}"
                        required autofocus autocomplete="username">
                    @error('email')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <input type="password" name="password" class="form-input" placeholder="Password" required
                        autocomplete="current-password">
                    @error('password')
                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </div>
    <script>
        // Add smooth focus effects
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', function () {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</x-guest-layout>