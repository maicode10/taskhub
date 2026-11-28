{{-- Shows the flash message sent by the controller with ->with('success', ...) --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Shows the friendly error sent with ->with('error', ...) --}}
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif