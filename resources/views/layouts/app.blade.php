<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TaskHub — MEDJ Print Shop</title>

    <!-- Bootstrap, so we don't write a lot of CSS by hand -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* our colors, written once and reused everywhere */
        :root {
            --purple:      #6d28d9;
            --purple-dark: #563098;
            --paper:       #f7f6f3;   /* light paper color for the background */
            --ink:         #2b2b2b;   /* dark gray, softer than pure black */
        }

        body {
            background-color: var(--paper);
            color: var(--ink);
        }

        .bg-purple   { background-color: var(--purple); }
        .text-purple { color: var(--purple); }

        .btn-purple       { background-color: var(--purple); color: #fff; border: none; }
        .btn-purple:hover { background-color: var(--purple-dark); color: #fff; }

        /* squarer corners look more like a printed form than a modern app */
        .card { border: 1px solid #ddd; border-radius: 4px; }

        .card-top {
            background-color: var(--purple);
            color: #fff;
            border-radius: 4px 4px 0 0;
        }

        /* dashed line, like a cut line on a print job */
        .cut-line { border-bottom: 1px dashed #cbbfe6; }
    </style>
</head>
<body>

{{-- the navbar only shows when somebody is logged in --}}
@auth
<nav class="navbar navbar-expand-lg bg-purple navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="/dashboard">
            <span class="fw-bold">MEDJ PRINT SHOP</span>
            <span class="small opacity-75">· TaskHub</span>
        </a>

        <div class="ms-auto d-flex align-items-center gap-3">
            <span class="text-white small">
                {{ auth()->user()->name }} ({{ auth()->user()->role }})
            </span>

            {{-- logout must be a form, not a link, because it uses POST --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-light btn-sm">Logout</button>
            </form>
        </div>
    </div>
</nav>
@endauth

<div class="container">
    @yield('content')
</div>

</body>
</html>