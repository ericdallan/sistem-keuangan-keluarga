<div class="py-4">
    <div class="container-fluid" style="max-width:860px">

        {{-- Header --}}
        <div class="mb-4 p-4 text-white rounded-3 shadow-sm" style="background:var(--sk-primary-gradient)">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25 flex-shrink-0"
                    style="width:52px;height:52px;font-size:1.4rem">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ auth()->user()->name }}</h4>
                    <p class="mb-0 opacity-75 small">
                        {{ ucfirst(auth()->user()->role) }} &mdash; {{ auth()->user()->email }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Email verification sent --}}
        @if (session('verification-sent'))
            <div
                class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3 border-start border-4 border-info bg-info bg-opacity-10">
                <i class="bi bi-envelope-check text-info fs-5"></i>
                <span class="fw-semibold small text-info-emphasis">
                    Link verifikasi telah dikirim ke email kamu.
                </span>
            </div>
        @endif

        <div class="sk-card mt-3">
            @include('livewire.profile.update-profile-information-form')
        </div>

        <div class="sk-card mt-3">
            @include('livewire.profile.update-password-form')
        </div>

    </div>
</div>
