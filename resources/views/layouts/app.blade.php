<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Komunitas Game') - GameHub</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ==================== GLOBAL STYLES ==================== */
        :root {
            --primary-color: #7289da; /* Discord blurple */
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --dark-bg: #1a1a1a;
            --darker-bg: #0f0f0f;
            --light-bg: #2c2c2c;
            --lighter-bg: #333333;
            --text-color: #ecf0f1;
            --text-muted: #bdc3c7;
            --border-color: #444444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-color);
            line-height: 1.6;
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: var(--accent-color);
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ==================== NAVIGATION ==================== */
        .navbar {
            background-color: var(--secondary-color);
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            color: var(--text-color);
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .navbar-brand i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .navbar-nav {
            display: flex;
            list-style: none;
            align-items: center;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            margin-left: 25px;
        }

        .nav-link {
            color: var(--text-muted);
            font-weight: 500;
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
        }

        .nav-link i {
            margin-right: 5px;
        }

        .nav-link:hover {
            color: var(--text-color);
        }

        .navbar-toggler {
            display: none;
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.5rem;
            cursor: pointer;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: var(--lighter-bg);
            min-width: 200px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            display: none;
            overflow: hidden;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            display: block;
            padding: 10px 20px;
            color: var(--text-color);
            transition: background-color 0.3s;
        }

        .dropdown-item:hover {
            background-color: var(--primary-color);
        }

        .dropdown-divider {
            height: 1px;
            background-color: var(--border-color);
            margin: 5px 0;
        }
        
        /* ==================== BUTTONS ==================== */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn i {
            margin-right: 8px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #5a6fa8;
            transform: translateY(-2px);
        }

        .btn-lg {
            padding: 15px 30px;
            font-size: 1.1rem;
        }
        
        /* ==================== ALERTS ==================== */
        .alert {
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .alert i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .alert-danger {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--accent-color);
            border: 1px solid var(--accent-color);
        }
        
        /* ==================== MAIN CONTENT ==================== */
        main {
            min-height: calc(100vh - 200px);
            padding: 2rem 0;
        }
        
        /* ==================== FOOTER ==================== */
        footer {
            background-color: var(--secondary-color);
            color: var(--text-muted);
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        footer h5 {
            color: var(--text-color);
            margin-bottom: 1rem;
        }

        footer ul {
            list-style: none;
            padding: 0;
        }

        footer ul li {
            margin-bottom: 0.5rem;
        }

        footer a {
            color: var(--text-muted);
        }

        footer a:hover {
            color: var(--primary-color);
        }

        footer hr {
            border: none;
            height: 1px;
            background-color: var(--border-color);
            margin: 2rem 0;
        }
        
        /* ==================== LOGIN PAGE ==================== */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 200px);
        }
        
        .login-card {
            background-color: var(--light-bg);
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            background-color: var(--primary-color);
            padding: 2rem;
            text-align: center;
        }
        
        .login-header h1 {
            color: white;
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .discord-btn {
            background-color: #7289da;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .discord-btn:hover {
            background-color: #5a6fa8;
        }
        
        .discord-btn i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        /* ==================== RESPONSIVE DESIGN ==================== */
        @media (max-width: 768px) {
            .navbar .container {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar-toggler {
                display: block;
                position: absolute;
                top: 15px;
                right: 20px;
            }

            .navbar-collapse {
                display: none !important;
                width: 100%;
                margin-top: 1rem;
            }

            .navbar-collapse.active {
                display: block !important;
            }

            .navbar-nav {
                flex-direction: column;
                width: 100%;
                align-items: flex-start;
            }

            .nav-item {
                margin: 5px 0;
                width: 100%;
            }

            .nav-link {
                width: 100%;
                padding: 10px 0;
            }
        }
        
        @yield('styles')
    </style>
</head>
<body>

<header>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-gamepad"></i> GameHub
            </a>
            
            <ul class="navbar-nav">
                <!-- Dropdown Jelajahi -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#">
                        <i class="fas fa-compass"></i> Jelajahi
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('home') }}" class="dropdown-item"><i class="fas fa-home"></i> Beranda</a>
                        <a href="{{ route('games.index') }}" class="dropdown-item"><i class="fas fa-gamepad"></i> Game</a>
                        <a href="{{ route('blog.index') }}" class="dropdown-item"><i class="fas fa-newspaper"></i> Blog</a>
                        <a href="{{ route('events.index') }}" class="dropdown-item"><i class="fas fa-calendar-alt"></i> Event</a>
                        <a href="{{ route('forum.index') }}" class="dropdown-item"><i class="fas fa-comments"></i> Forum</a>
                        <a href="{{ route('leaderboard.index') }}" class="dropdown-item"><i class="fas fa-trophy"></i> Papan Peringkat</a>
                        <a href="{{ route('teams.index') }}" class="dropdown-item"><i class="fas fa-users"></i> Tim</a>
                        <a href="{{ route('competitions.index') }}" class="dropdown-item"><i class="fas fa-medal"></i> Kompetisi</a>
                        <a href="{{ route('creators.index') }}" class="dropdown-item"><i class="fas fa-paint-brush"></i> Kreator</a>
                        @auth
                            <a href="{{ route('requests.index') }}" class="dropdown-item"><i class="fas fa-list"></i> Permintaan Game</a>
                            <a href="{{ route('courses.index') }}" class="dropdown-item"><i class="fas fa-book"></i> Kursus</a>
                            <a href="{{ route('analytics.index') }}" class="dropdown-item"><i class="fas fa-chart-line"></i> Analitik</a>
                        @endauth
                    </div>
                </li>

                <!-- Dropdown Permainan -->
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#">
                        <i class="fas fa-dice"></i> Permainan
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('gacha.index') }}" class="dropdown-item"><i class="fas fa-box-open"></i> Gacha</a>
                        <a href="{{ route('lucky-wheel.index') }}" class="dropdown-item"><i class="fas fa-wheel-alt"></i> Roda Keberuntungan</a>
                        <a href="{{ route('quiz.index') }}" class="dropdown-item"><i class="fas fa-question-circle"></i> Kuis</a>
                        <a href="{{ route('reaction-test.index') }}" class="dropdown-item"><i class="fas fa-tachometer-alt"></i> Tes Reaksi</a>
                    </div>
                </li>
                @endauth

                <!-- Dropdown Sistem -->
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#">
                        <i class="fas fa-cogs"></i> Sistem
                    </a>
                    <div class="dropdown-menu">
                        <a href="{{ route('prestige.index') }}" class="dropdown-item"><i class="fas fa-star"></i> Prestige</a>
                        <a href="{{ route('jobs.index') }}" class="dropdown-item"><i class="fas fa-briefcase"></i> Pekerjaan</a>
                        <a href="{{ route('inventory.index') }}" class="dropdown-item"><i class="fas fa-box"></i> Inventori</a>
                        <a href="{{ route('market.index') }}" class="dropdown-item"><i class="fas fa-store"></i> Pasar</a>
                        <a href="{{ route('bank.index') }}" class="dropdown-item"><i class="fas fa-money-check-alt"></i> Bank</a>
                        <a href="{{ route('trades.index') }}" class="dropdown-item"><i class="fas fa-exchange-alt"></i> Perdagangan</a>
                        @if(Auth::user()->isVip())
                            <a href="{{ route('vip.content') }}" class="dropdown-item">
                                <i class="fas fa-crown text-warning"></i> Konten VIP
                            </a>
                        @endif
                    </div>
                </li>
                @endauth

                @auth
                    @if(Auth::user()->isVip())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('vip.content') }}">
                                <i class="fas fa-crown text-warning"></i> Konten VIP
                            </a>
                        </li>
                    @endif
                    <!-- Menu untuk Staff/Admin -->
                    @if(Auth::user()->isStaff())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <i class="fas fa-shield-alt"></i> Panel Staff
                        </a>
                        <div class="dropdown-menu">
                            <a href="{{ route('staff.index') }}" class="dropdown-item">
                                <i class="fas fa-users"></i> Dashboard Staff
                            </a>
                            <a href="{{ route('tickets.index') }}" class="dropdown-item">
                                <i class="fas fa-ticket-alt"></i> Tiket Dukungan
                            </a>
                        </div>
                    </li>
                    @endif
                    
                    <!-- Menu untuk Admin/Moderator/Owner -->
                    @if(Auth::user()->isAdminOrOwner())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <i class="fas fa-cogs"></i> Panel Admin
                        </a>
                        <div class="dropdown-menu">
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                            </a>
                            <a href="{{ route('admin.requests') }}" class="dropdown-item">
                                <i class="fas fa-list"></i> Permintaan Game
                            </a>
                            <a href="{{ route('admin.users') }}" class="dropdown-item">
                                <i class="fas fa-users"></i> Manajemen Pengguna
                            </a>
                            <a href="{{ route('admin.panel') }}" class="dropdown-item">
                                <i class="fas fa-tools"></i> Panel Kontrol
                            </a>
                            
                            <!-- Discord Bot Controls (Owner Only) -->
                            @if(Auth::user()->isOwner())
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Kontrol Bot Discord</h6>
                            <a href="{{ route('admin.discord.status') }}" class="dropdown-item">
                                <i class="fas fa-robot"></i> Status Bot
                            </a>
                            @endif
                        </div>
                    </li>
                    @endif
                    
                    <!-- Menu untuk profil untuk pengguna yang sudah login -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#">
                            <img src="{{ Auth::user()->avatar ?? Auth::user()->discord_avatar_url ?? 'https://cdn.discordapp.com/embed/avatars/0.png' }}"
                                alt="{{ Auth::user()->username }}"
                                style="width: 24px; height: 24px; border-radius: 50%; margin-right: 8px;">

                            {{ Auth::user()->username }}

                            @if(Auth::user()->isVip())
                                <i class="fas fa-crown text-warning"></i>
                            @endif
                        </a>

                        <div class="dropdown-menu">
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="{{ route('profile.show', Auth::id()) }}" class="dropdown-item">
                                <i class="fas fa-user"></i> Profil
                            </a>
                            <a href="{{ route('dashboard.user') }}" class="dropdown-item">
                                <i class="fas fa-user-circle"></i> Dashboard Pribadi
                            </a>

                            @if(Auth::user()->isVip())
                                <a href="{{ route('vip.index') }}" class="dropdown-item">
                                    <i class="fas fa-crown"></i> Status VIP
                                </a>
                            @endif

                            <a href="{{ route('requests.index') }}" class="dropdown-item">
                                <i class="fas fa-list"></i> Permintaan Saya
                            </a>

                            <div class="dropdown-divider"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item" style="background:none;border:none;width:100%;text-align:left;">
                                    <i class="fas fa-sign-out-alt"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </li>
                @endauth
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('auth.discord') }}">
                            <i class="fab fa-discord"></i> Masuk dengan Discord
                        </a>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>
</header>
    
    <main>
        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-gamepad"></i> GameHub</h5>
                    <p>Platform komunitas game dengan download gratis tanpa iklan untuk member VIP.</p>
                </div>
                <div class="col-md-4">
                    <h5>Tautan Cepat</h5>
                    <ul>
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="#">Game</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Event</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Hubungi Kami</h5>
                    <div>
                        <a href="#" class="text-light me-3"><i class="fab fa-discord fa-2x"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-youtube fa-2x"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-twitter fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; {{ date('Y') }} GameHub. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS for Navbar Toggle -->
    <script>
        $(document).ready(function() {
            $('.navbar-toggler').on('click', function(e) {
                e.preventDefault();
                $('.navbar-collapse').toggleClass('active');
            });
        });
    </script>

    <!-- Page-specific scripts can be yielded here -->
    @yield('scripts')
</script>
<!-- Laravel Echo & Reverb -->
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/socket.io-client@4.7.5/dist/socket.io.min.js"></script>
<script>
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: '{{ config('reverb.apps')[0]['key'] ?? null }}',
        wsHost: window.location.hostname,
        wsPort: 8080,
        forceTLS: false,
        enabledTransports: ['ws'],
    });
</script>
</body>
</html>