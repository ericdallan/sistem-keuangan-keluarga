<nav class="d-flex justify-content-end gap-2">
    @auth
        <a href="{{ url('/dashboard') }}"
            class="btn-sk-primary px-4 rounded-pill fw-bold shadow-sm text-decoration-none d-inline-flex align-items-center">
            Dashboard <i class="bi bi-speedometer2 ms-1"></i>
        </a>
    @else
        <a href="{{ route('login') }}"
            class="btn-sk-outline px-4 rounded-pill fw-bold text-decoration-none d-inline-flex align-items-center">
            Log in
        </a>

        {{-- @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn-sk-primary px-4 rounded-pill fw-bold shadow-sm text-decoration-none d-inline-flex align-items-center">
                Register
            </a>
        @endif --}}
    @endauth
</nav>
