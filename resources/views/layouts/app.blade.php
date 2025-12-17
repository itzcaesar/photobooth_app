<!doctype html>
<html lang="en">
    <style>
    /* Warna dasar pagination */
      .pagination .page-link {
        color: #547792;
        background-color: #FEF9F2;
        border-color: #94B4C1;
      }
    /* Hover */
      .pagination .page-link:hover {
        color: #FFFFFF;
        background-color: #547792;
        border-color: #547792;
    }

/* Active (halaman sekarang) */
      .pagination .page-item.active .page-link {
        color: #FFFFFF;
        background-color: #213448;
        border-color: #213448;
    }

/* Disabled */
      .pagination .page-item.disabled .page-link {
        color: #B2BEC3;
        background-color: #F4F4F4;
        border-color: #E0E0E0;
    }

</style>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Poselab</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    .capture-wrapper { position: relative; width: 100%; max-width: 720px; margin: 0 auto; }
    #camera, #preview { width: 100%; height: auto; display: block; }
    .overlay-frame { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; pointer-events: none; }
    .frame-thumb { width: 80px; height: 60px; object-fit: cover; cursor: pointer; border: 2px solid transparent; }
    .frame-thumb.selected { border-color: #0d6efd; }
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #FFFFFF;
    }
    .navbar-custom {
    background-color: #213448; /* warna navbar */
    }

    .navbar-custom .navbar-brand,
    .navbar-custom .nav-link {
      color: #FFFFFF;
    }
    /* ========== PRIMARY ========== */
    .btn-primary {
      --bs-btn-bg: #547792;
      --bs-btn-border-color: #547792;
      --bs-btn-hover-bg: #547792;
      --bs-btn-hover-border-color: #547792;
      --bs-btn-active-bg: #547792;
      --bs-btn-active-border-color: #547792;
      --bs-btn-focus-shadow-rgb: 84, 119, 146;
    }

    .btn-primary:hover {
      background-color: #4A6A82;
      border-color: #4A6A82;
    }

/* ========== SECONDARY ========== */
    .btn-secondary {
      --bs-btn-bg: #94B4C1;
      --bs-btn-border-color: #94B4C1;
      --bs-btn-hover-bg: #94B4C1;
      --bs-btn-hover-border-color: #94B4C1;
      --bs-btn-active-bg: #94B4C1;
      --bs-btn-active-border-color: #94B4C1;
      --bs-btn-focus-shadow-rgb: 148, 180, 193;
    }

    .btn-secondary:hover {
      background-color: #85A6B2;
      border-color: #85A6B2;
    }

/* ========== SUCCESS ========== */
    .btn-success {
      --bs-btn-bg: #213448;
      --bs-btn-border-color: #213448;
      --bs-btn-hover-bg: #213448;
      --bs-btn-hover-border-color: #213448;
      --bs-btn-active-bg: #213448;
      --bs-btn-active-border-color: #213448;
      --bs-btn-focus-shadow-rgb: 33, 52, 72;
    }

    .btn-success:hover {
      background-color: #1C2C3D;
      border-color: #1C2C3D;
    }

/* ========== OUTLINE PRIMARY ========== */
    .btn-outline-primary {
      color: #94B4C1;
      border-color: #94B4C1;
    }

    .btn-outline-primary:hover {
      background-color: #94B4C1;
      color: #FFFFFF;
    }

/* ========== OUTLINE WARNING ========== */
    .btn-outline-warning {
      color: #547792;
      border-color: #547792;
    }

    .btn-outline-warning:hover {
      background-color: #547792;
      color: #FFFFFF;
    }

/* ========== OUTLINE DANGER ========== */
    .btn-outline-danger {
      color: #213448;
      border-color: #213448;
    }

    .btn-outline-danger:hover {
      background-color: #213448;
      color: #FFFFFF;
    }

/* ========== SMALL PRIMARY (btn-sm btn-primary) ========== */
    .btn-sm.btn-primary {
      background-color: #94B4C1;
      border-color: #94B4C1;
    }

    .btn-sm.btn-primary:hover {
      background-color: #85A6B2;
      border-color: #85A6B2;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom mb-4">
  <div class="container">
    <a class="navbar-brand" href="/">Poselab</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/">Start Photo</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/capture') }}">Capture</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ url('/photos') }}">Gallery</a></li>
      </ul>
      <ul class="navbar-nav">
        @if(session('user_id'))
          <li class="nav-item"><span class="nav-link">Hi, {{ session('user_name') }}</span></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/logout') }}">Logout</a></li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">Register</a></li>
        @endif
      </ul>
    </div>
  </div>
</nav>

<div class="container">
  @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>
