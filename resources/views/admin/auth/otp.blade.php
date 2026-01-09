<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <h3 class="text-center text-xl font-extrabold dark:text-white">Verify OTP</h3>
    <p class="text-sm text-muted text:grey text-center">(OTP has been send to your register email address.)</p>
    <form method="POST" action="{{ route('admin.otp.verify') }}">
        @csrf
        <!-- otp -->
        <div class="mt-4">
            <x-input-label for="otp" :value="__('OTP')" />

            <x-text-input id="otp" class="block mt-1 w-full"
                type="text"
                name="otp"
                required autocomplete="off" />

            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <span><button type="button" class="text-sm text-primary mt-3 hidden" id="resendOtpBtn" onclick="resendOtp()">Resend Otp </button>&nbsp;<span id="otpTimer" class="text-xs text-gray-500 mt-1"></span></span>
            <x-primary-button class="ms-3">
                {{ __('submit') }}
            </x-primary-button>
        </div>
    </form>
    @push('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const btn = document.getElementById('resendOtpBtn');

            const backendResendAt = `{{ $otpResendAt ?? 'null' }}`;

            const localEnd = localStorage.getItem('otp_resend_end');

            let remaining = 0;

            if (backendResendAt) {
                const now = Math.floor(Date.now() / 1000);
                remaining = backendResendAt - now;
            } else if (localEnd && Date.now() < localEnd) {
                remaining = Math.ceil((localEnd - Date.now()) / 1000);
            }

            if (remaining > 0) {
                btn.classList.add('hidden');
                startCooldown(remaining);
            } else {
                btn.classList.remove('hidden');
                localStorage.removeItem('otp_resend_end');
            }
        });

        const COOLDOWN_SECONDS = 60;
        let resendInterval = null;

        function resendOtp() {
            const btn = document.getElementById('resendOtpBtn');
            if (btn.classList.contains('hidden')) return;

            btn.classList.add('hidden');

            fetch("{{ route('admin.otp.resend') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: data.status === 'success' ? 'success' : 'error',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    if (data.cooldown_seconds || data.remaining_seconds) {
                        const seconds = data.cooldown_seconds ?? data.remaining_seconds;
                        startCooldown(seconds);
                    }
                });
        }

        function startCooldown(seconds) {
            const btn = document.getElementById('resendOtpBtn');
            const timerEl = document.getElementById('otpTimer');

            const endTime = Date.now() + seconds * 1000;
            localStorage.setItem('otp_resend_end', endTime);

            btn.classList.add('hidden');

            resendInterval = setInterval(() => {
                const remaining = Math.max(
                    0,
                    Math.ceil((endTime - Date.now()) / 1000)
                );

                timerEl.innerText = `Resend available in ${remaining}s`;

                if (remaining <= 0) {
                    clearInterval(resendInterval);
                    timerEl.innerText = '';
                    btn.classList.remove('hidden');
                    localStorage.removeItem('otp_resend_end');
                }
            }, 1000);
        }
    </script>
    @endpush
</x-guest-layout>