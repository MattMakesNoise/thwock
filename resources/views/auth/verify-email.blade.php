<x-guest-layout>
    <div class="mb-4 text-sm text-thwock-dark dark:text-thwock-light-muted">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-thwock-primary dark:text-thwock-secondary">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-thwock-dark dark:text-thwock-light-muted hover:text-thwock-darker dark:hover:text-thwock-light rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-thwock-primary dark:focus:ring-offset-thwock-dark">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
