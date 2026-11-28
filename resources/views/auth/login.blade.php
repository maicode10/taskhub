@extends('layouts.app')

@section('content')

<style>
    /* faint ruled lines, like a paper pad */
    body {
        background-color: #f4f2ee;
        background-image: repeating-linear-gradient(
            to bottom,
            transparent 0,
            transparent 27px,
            #e8e4dc 28px
        );
    }

    /* the whole login slip */
    .job-slip {
        background: #fff;
        border: 1px solid #d6d0c4;
        box-shadow: 0 1px 0 #d6d0c4, 0 6px 14px rgba(0,0,0,.06);
    }

    /* the 4 ink colors of printing: cyan, magenta, yellow, black */
    .ink-strip { display: flex; height: 7px; }
    .ink-strip span { flex: 1; }
    .ink-c { background: #00aeef; }
    .ink-m { background: #ec008c; }
    .ink-y { background: #ffd200; }
    .ink-k { background: #2b2b2b; }

    /* the shop name on the letterhead */
    .shop-name {
        font-size: 26px;
        font-weight: 800;
        color: #2b2b2b;
        letter-spacing: -0.3px;
        line-height: 1;
        margin: 0;
    }

    /* the thin line under the shop name */
    .rule-purple {
        height: 3px;
        background: var(--purple);
        width: 58px;
        margin: 10px 0 8px 0;
    }

    /* the dotted tear line at the bottom of the slip */
    .perforation { border-top: 2px dashed #d6d0c4; }

    /* labels on the form */
    .form-label-slip {
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #444;
    }

    /* inputs that look like blanks on a paper form */
    .input-slip {
        border: none;
        border-bottom: 1.5px solid #bfb8a9;
        border-radius: 0;
        background: transparent;
        padding-left: 2px;
        font-size: 16px;
    }
    .input-slip:focus {
        border-bottom: 2px solid var(--purple);
        box-shadow: none;
        background: #faf8ff;
    }

    /* small stamp text, used only on the date line */
    .stamp {
        font-family: 'Courier New', monospace;
        font-size: 12px;
        letter-spacing: 1px;
        color: #555;
    }
</style>

<div class="row justify-content-center mt-4 mb-5">
    <div class="col-md-6 col-lg-5">

        <div class="job-slip">

            {{-- the 4 ink colors across the top --}}
            <div class="ink-strip">
                <span class="ink-c"></span>
                <span class="ink-m"></span>
                <span class="ink-y"></span>
                <span class="ink-k"></span>
            </div>

            {{-- letterhead: name, short rule, then the contact line --}}
            <div class="px-4 pt-4 pb-3">
                <p class="shop-name">MEDJ Print Shop</p>
                <div class="rule-purple"></div>
                <div style="font-size:12.5px; color:#777;">
                    Tarpaulin · Layout · Photocopy · Binding<br>
                    Carmen, Cagayan de Oro · 0917 555 0143
                </div>
            </div>

            {{-- thin band under the letterhead, shows today's date --}}
            <div class="d-flex justify-content-between px-4 py-2 stamp"
                 style="background:#faf9f6; border-top:1px solid #ece8e0; border-bottom:1px solid #ece8e0;">
                <span>{{ now()->format('F d, Y') }}</span>
                <span>{{ now()->format('l') }}</span>
            </div>

            <div class="px-4 py-4">

                <h5 class="text-purple fw-semibold mb-4">Sign in to TaskHub</h5>

                {{-- this shows the red message when login fails or the account is locked --}}
                @if (session('error'))
                    <div class="alert alert-danger py-2">{{ session('error') }}</div>
                @endif

                {{-- this shows the green message after logout --}}
                @if (session('success'))
                    <div class="alert alert-success py-2">{{ session('success') }}</div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    {{-- @csrf protects the form from fake requests from other websites --}}
                    @csrf

                    <div class="mb-4">
                        <label class="form-label-slip d-block mb-1">Email address</label>
                        <input type="email" name="email" class="form-control input-slip"
                               value="{{ old('email') }}" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label-slip d-block mb-1">Password</label>
                        <input type="password" name="password" class="form-control input-slip" required>
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="btn btn-purple w-100 py-2 fw-semibold"
                            style="letter-spacing:1px; border-radius:2px;">
                        SIGN IN
                    </button>
                </form>

            </div>

            {{-- the tear-off part at the bottom --}}
            <div class="perforation"></div>
            <div class="px-4 py-3 text-center" style="font-size:13px; color:#666;">
                No account? Ask the owner to create one for you.
            </div>

        </div>

    </div>
</div>
@endsection
