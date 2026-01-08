<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <h3 class="text-center text-lg text-bold">Verify OTP</h3>
    <p class="text-sm text-muted text:grey text-center">(OTP has been send to your register email address.)</p>
    <form method="POST" action="{{ route('admin.otp.verify') }}">
        @csrf
        <!-- otp -->
        <div class="mt-4">
            <x-input-label for="otp" :value="__('OTP')" />

            <x-text-input id="otp" class="block mt-1 w-full"
                type="number"
                name="otp"
                required autocomplete="off" />

            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <!-- <span><button type="button" class="text-sm text-primary mt-3" id="resendOtpBtn">Resend Otp </button>&nbsp;<span id="otpTimer" class="text-xs text-gray-500 mt-1"></span></span> -->
            <x-primary-button class="ms-3">
                {{ __('submit') }}
            </x-primary-button>
        </div>
    </form>
    @push('script')
    <script>
        let resendInterval = null;

        function resendOtp() {
            const btn = document.getElementById('resendOtpBtn');
            btn.disabled = true;

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
                        icon: data.status,
                        title: data.message,
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    if (data.cooldown_seconds) {
                        startCooldown(data.cooldown_seconds);
                    }

                    if (data.remaining_seconds) {
                        startCooldown(data.remaining_seconds);
                    }
                })
                .catch(() => {
                    btn.disabled = false;
                });
        }

        function startCooldown(seconds) {
            const btn = document.getElementById('resendOtpBtn');
            const timerEl = document.getElementById('otpTimer');

            clearInterval(resendInterval);

            btn.disabled = true;
            timerEl.innerText = `Resend available in ${seconds}s`;

            resendInterval = setInterval(() => {
                seconds--;
                timerEl.innerText = `Resend available in ${seconds}s`;

                if (seconds <= 0) {
                    clearInterval(resendInterval);
                    btn.disabled = false;
                    timerEl.innerText = '';
                }
            }, 1000);
        }
    </script>


    @endpush
</x-guest-layout>