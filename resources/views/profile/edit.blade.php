<x-admin-layout>
    <x-slot name="header">
        <h1 class="fw-semibold fs-4 text-dark">{{ __('Profile') }}</h1>
        <p class="small text-muted mt-1">
            {{ __("Manage your account's profile details, password, and deletion settings.") }}
        </p>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="row g-4">

                <!-- Profile Information -->
                <div class="col-12 col-lg-8">
                    <div class="card mb-4 shadow-sm border-0 bg-light">
                        <div class="card-body">
                            <h3 class="h5 fw-bold text-primary mb-3">👤 {{ __('Profile Information') }}</h3>
                            <p class="small text-muted mb-4">
                                {{ __("Update your account’s name and email address.") }}
                            </p>

                            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                @csrf
                            </form>

                            <form method="post" action="{{ route('profile.update') }}" class="row g-3">
                                @csrf
                                @method('patch')

                                <div class="col-md-6">
                                    <x-input-label for="name" :value="__('Name')" class="form-label" />
                                    <x-text-input
                                        id="name"
                                        name="name"
                                        type="text"
                                        class="form-control form-control-lg"
                                        :value="old('name', $user->name)"
                                        required
                                        autofocus
                                        autocomplete="name"
                                    />
                                    <x-input-error class="mt-2 text-danger" :messages="$errors->get('name')" />
                                </div>

                                <div class="col-md-6">
                                    <x-input-label for="email" :value="__('Email')" class="form-label" />
                                    <x-text-input
                                        id="email"
                                        name="email"
                                        type="email"
                                        class="form-control form-control-lg"
                                        :value="old('email', $user->email)"
                                        required
                                        autocomplete="username"
                                    />
                                    <x-input-error class="mt-2 text-danger" :messages="$errors->get('email')" />

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                        <div class="mt-2">
                                            <p class="small text-warning mb-1">
                                                {{ __('Your email address is unverified.') }}
                                                <button
                                                    form="send-verification"
                                                    class="btn btn-sm btn-link text-decoration-underline p-0"
                                                >
                                                    {{ __('Click here to re-send the verification email.') }}
                                                </button>
                                            </p>
                                            @if (session('status') === 'verification-link-sent')
                                                <p class="small text-success mb-0">
                                                    {{ __('A new verification link has been sent to your email address.') }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12 d-flex align-items-center gap-3 mt-4">
                                    <x-primary-button class="btn btn-primary btn-lg">
                                        {{ __('Save Changes') }}
                                    </x-primary-button>

                                    @if (session('status') === 'profile-updated')
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-success mb-0"
                                        >
                                            {{ __('Saved.') }}
                                        </p>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="col-12 col-lg-8">
                    <div class="card mb-4 shadow-sm border-0 bg-light">
                        <div class="card-body">
                            <h3 class="h5 fw-bold text-primary mb-3">🔒 {{ __('Update Password') }}</h3>
                            <p class="small text-muted mb-4">
                                {{ __('Ensure your account is using a long, random password to stay secure.') }}
                            </p>

                            <form method="post" action="{{ route('password.update') }}" class="row g-3">
                                @csrf
                                @method('put')

                                <div class="col-md-12">
                                    <x-input-label
                                        for="update_password_current_password"
                                        :value="__('Current Password')"
                                        class="form-label"
                                    />
                                    <x-text-input
                                        id="update_password_current_password"
                                        name="current_password"
                                        type="password"
                                        class="form-control form-control-lg"
                                        autocomplete="current-password"
                                    />
                                    <x-input-error
                                        class="mt-2 text-danger"
                                        :messages="$errors->updatePassword->get('current_password')"
                                    />
                                </div>

                                <div class="col-md-6">
                                    <x-input-label
                                        for="update_password_password"
                                        :value="__('New Password')"
                                        class="form-label"
                                    />
                                    <x-text-input
                                        id="update_password_password"
                                        name="password"
                                        type="password"
                                        class="form-control form-control-lg"
                                        autocomplete="new-password"
                                    />
                                    <x-input-error
                                        class="mt-2 text-danger"
                                        :messages="$errors->updatePassword->get('password')"
                                    />
                                </div>

                                <div class="col-md-6">
                                    <x-input-label
                                        for="update_password_password_confirmation"
                                        :value="__('Confirm Password')"
                                        class="form-label"
                                    />
                                    <x-text-input
                                        id="update_password_password_confirmation"
                                        name="password_confirmation"
                                        type="password"
                                        class="form-control form-control-lg"
                                        autocomplete="new-password"
                                    />
                                    <x-input-error
                                        class="mt-2 text-danger"
                                        :messages="$errors->updatePassword->get('password_confirmation')"
                                    />
                                </div>

                                <div class="col-12 d-flex align-items-center gap-3 mt-4">
                                    <x-primary-button class="btn btn-primary btn-lg">
                                        {{ __('Change Password') }}
                                    </x-primary-button>

                                    @if (session('status') === 'password-updated')
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-success mb-0"
                                        >
                                            {{ __('Saved.') }}
                                        </p>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete User Account -->
                <div class="col-12 col-lg-8">
                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-body">
                            <h3 class="h5 fw-bold text-danger mb-3">🗑️ {{ __('Delete Account') }}</h3>
                            <p class="small text-muted mb-4">
                                {{ __('Once your account is deleted, all of its resources and data will be permanently removed. Please download any data you wish to keep before proceeding.') }}
                            </p>

                            <x-danger-button
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                                class="btn btn-danger btn-lg"
                            >
                                {{ __('Delete Account') }}
                            </x-danger-button>

                            <x-modal
                                name="confirm-user-deletion"
                                :show="$errors->userDeletion->isNotEmpty()"
                                focusable
                            >
                                <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
                                    @csrf
                                    @method('delete')

                                    <h4 class="fw-semibold text-danger mb-2">
                                        {{ __('Are you sure you want to delete your account?') }}
                                    </h4>
                                    <p class="small text-muted mb-4">
                                        {{ __('This action is irreversible. Enter your password to confirm you want to permanently delete your account.') }}
                                    </p>

                                    <div class="mb-3">
                                        <x-input-label for="password" :value="__('Password')" class="form-label" />
                                        <x-text-input
                                            id="password"
                                            name="password"
                                            type="password"
                                            class="form-control form-control-lg"
                                            placeholder="{{ __('Password') }}"
                                        />
                                        <x-input-error
                                            :messages="$errors->userDeletion->get('password')"
                                            class="mt-2 text-danger"
                                        />
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <x-secondary-button
                                            x-on:click="$dispatch('close')"
                                            class="btn btn-outline-secondary"
                                        >
                                            {{ __('Cancel') }}
                                        </x-secondary-button>

                                        <x-danger-button class="btn btn-danger">
                                            {{ __('Delete Account') }}
                                        </x-danger-button>
                                    </div>
                                </form>
                            </x-modal>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>
