<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <h1 class="text-center">Register</h1>

                @if(session('referrer'))
                    <p class="text-center text-muted mt-2">
                        🎉 You've been referred by <strong>{{ session('referrer') }}</strong>
                    </p>
                @endif
            </div>
        </div>
    </div>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')" class="form-label" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" class="form-control" />
            <x-input-error :messages="$errors->get('name')" class="invalid-feedback d-block mt-1" />
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" class="form-label" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" class="form-control" />
            <x-input-error :messages="$errors->get('email')" class="invalid-feedback d-block mt-1" />
        </div>

        <!-- Password -->
        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" class="form-label" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" class="form-control" />
            <x-input-error :messages="$errors->get('password')" class="invalid-feedback d-block mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="form-label" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="invalid-feedback d-block mt-1" />
        </div>

        <!-- Plan Selection -->
        <div class="mb-4">
            <label for="plan" class="form-label">Choose Your Plan</label>
            <select id="plan" name="plan" class="form-select" required>
                <option value="monthly">Monthly - $12/mo</option>
                <option value="yearly">Yearly - $99/yr</option>
            </select>
            <x-input-error :messages="$errors->get('plan')" class="invalid-feedback d-block mt-1" />
        </div>

        @if(session('referrer'))
            <div class="mb-3">
                <label class="form-label">Referred by</label>
                <div class="alert alert-secondary mb-0 py-2">
                    {{ session('referrer') }}
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mt-4">
            <a class="small text-decoration-none" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="btn btn-primary ms-3">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
