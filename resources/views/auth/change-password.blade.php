@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card">
            <div class="card-top py-2 px-3">
                <span class="fw-semibold">Change Password</span>
            </div>

            <div class="card-body p-4">

                @if (session('error'))
                    <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                @endif

                {{-- remind the user of the rules before he types --}}
                <div class="small text-muted mb-3">
                    Password must be at least 8 characters, with one capital letter,
                    one small letter, one number, and one symbol.
                </div>

                <form action="{{ route('password.change') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Current password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">New password</label>
                        <input type="password" name="new_password" id="newPassword" class="form-control" required>
                        @error('new_password') <small class="text-danger">{{ $message }}</small> @enderror

                        {{-- THIS PART IS THE PASSWORD STRENGTH METER --}}
                        <div class="progress mt-2" style="height: 5px;">
                            <div id="strengthBar" class="progress-bar" style="width: 0%"></div>
                        </div>
                        <small id="strengthText" class="text-muted"></small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Confirm new password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>

                    <button class="btn btn-purple w-100">Change Password</button>
                </form>

            </div>
        </div>

    </div>
</div>

<script>
// this counts how many rules the password passed, then colors the bar
document.getElementById('newPassword').addEventListener('keyup', function () {
    let pass  = this.value;
    let score = 0;

    if (pass.length >= 8)   score++;   // long enough
    if (/[a-z]/.test(pass)) score++;   // has small letter
    if (/[A-Z]/.test(pass)) score++;   // has capital letter
    if (/[0-9]/.test(pass)) score++;   // has number
    if (/[\W]/.test(pass))  score++;   // has symbol

    let bar  = document.getElementById('strengthBar');
    let text = document.getElementById('strengthText');

    bar.style.width = (score * 20) + '%';

    if (score <= 2) {
        bar.className = 'progress-bar bg-danger';
        text.textContent = 'Weak';
    } else if (score <= 4) {
        bar.className = 'progress-bar bg-warning';
        text.textContent = 'Medium';
    } else {
        bar.className = 'progress-bar bg-success';
        text.textContent = 'Strong';
    }
});
</script>
@endsection
