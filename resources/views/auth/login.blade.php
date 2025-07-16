@section('title', 'Login')
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Heading -->
    <div class="custom-login-heading">
        <h1>Masukkan PIN Kasir</h1>
        <p>Hanya untuk operator terdaftar</p>
    </div>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="hidden">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="admin@zpscan.test" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- PIN Component -->
        <div class="mt-4">
            <div class="pin-container">
                <input type="number" 
                       class="pin-input" 
                       id="pin-1" 
                       name="pin-1" 
                       min="0" 
                       max="9" 
                       maxlength="1"
                       autocomplete="off"
                       required>
                <input type="number" 
                       class="pin-input" 
                       id="pin-2" 
                       name="pin-2" 
                       min="0" 
                       max="9" 
                       maxlength="1"
                       autocomplete="off"
                       required>
                <input type="number" 
                       class="pin-input" 
                       id="pin-3" 
                       name="pin-3" 
                       min="0" 
                       max="9" 
                       maxlength="1"
                       autocomplete="off"
                       required>
                <input type="number" 
                       class="pin-input" 
                       id="pin-4" 
                       name="pin-4" 
                       min="0" 
                       max="9" 
                       maxlength="1"
                       autocomplete="off"
                       required>
            </div>
            
            <!-- Visual indicator dots -->
            <div class="pin-dots">
                <div class="pin-dot" id="dot-1"></div>
                <div class="pin-dot" id="dot-2"></div>
                <div class="pin-dot" id="dot-3"></div>
                <div class="pin-dot" id="dot-4"></div>
            </div>
            
            <!-- Hidden input untuk mengirim ke database -->
            <input type="hidden" id="password" name="password" value="">
            
            <!-- Error message placeholder -->
            <div class="input-error" id="pin-error" style="display: none;">
                PIN harus terdiri dari 4 digit angka
            </div>
        </div>

        <!-- Remember Me
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div> -->

        <div class="center text-center mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Lupa PIN?') }}
                </a>
            @endif
        </div>

        <div class="mt-4">
            <x-primary-button>
                {{ __('Login') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pinInputs = document.querySelectorAll('.pin-input');
            const pinDots = document.querySelectorAll('.pin-dot');
            const hiddenPassword = document.getElementById('password');
            const pinError = document.getElementById('pin-error');
            
            // Function to update hidden password field
            function updatePassword() {
                let pin = '';
                pinInputs.forEach(input => {
                    pin += input.value || '';
                });
                hiddenPassword.value = pin;
                
                // Update visual indicators
                updateDots();
                
                // Validate PIN
                if (pin.length === 4) {
                    pinError.style.display = 'none';
                    pinInputs.forEach(input => {
                        input.classList.remove('error');
                        input.classList.add('filled');
                    });
                } else if (pin.length > 0) {
                    pinInputs.forEach((input, index) => {
                        if (input.value) {
                            input.classList.add('filled');
                            input.classList.remove('error');
                        } else {
                            input.classList.remove('filled', 'error');
                        }
                    });
                }
            }
            
            // Function to update dots
            function updateDots() {
                pinDots.forEach((dot, index) => {
                    const input = pinInputs[index];
                    dot.classList.remove('active', 'filled');
                    
                    if (input.value) {
                        dot.classList.add('filled');
                    } else if (document.activeElement === input) {
                        dot.classList.add('active');
                    }
                });
            }
            
            // Add event listeners to each PIN input
            pinInputs.forEach((input, index) => {
                // Input event
                input.addEventListener('input', function(e) {
                    // Only allow single digit
                    if (this.value.length > 1) {
                        this.value = this.value.slice(0, 1);
                    }
                    
                    // Only allow numbers 0-9
                    if (this.value && (this.value < '0' || this.value > '9')) {
                        this.value = '';
                        return;
                    }
                    
                    updatePassword();
                    
                    // Auto-focus next input
                    if (this.value && index < pinInputs.length - 1) {
                        pinInputs[index + 1].focus();
                    }
                });
                
                // Keydown event for backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        pinInputs[index - 1].focus();
                    }
                    
                    // Prevent non-numeric input
                    if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                        e.preventDefault();
                    }
                });
                
                // Focus event
                input.addEventListener('focus', function() {
                    updateDots();
                });
                
                // Blur event
                input.addEventListener('blur', function() {
                    updateDots();
                });
                
                // Paste event
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text');
                    
                    if (/^\d{4}$/.test(pasteData)) {
                        // Valid 4-digit PIN pasted
                        for (let i = 0; i < 4; i++) {
                            if (pinInputs[i]) {
                                pinInputs[i].value = pasteData[i];
                            }
                        }
                        updatePassword();
                        pinInputs[3].focus();
                    }
                });
            });
            
            // Initialize
            updatePassword();
        });
    </script>
</x-guest-layout>
