<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 fw-semibold text-dark">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row g-4">

                <!-- Update Profile Information -->
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>

                <!-- Delete User Account -->
                <div class="col-12 col-lg-8">
                    <div class="card border-danger shadow-sm">
                        <div class="card-body">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>
