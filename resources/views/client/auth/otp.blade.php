<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <h3 class="text-center text-lg text-bold">Verify OTP</h3>
    <p class="text-sm text-muted text:grey text-center">(OTP has been send to your register email address.)</p>
    <form method="POST" action="{{ route('otp.verify') }}">
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

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('submit') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
