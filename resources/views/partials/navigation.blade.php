<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-gamepad me-2"></i>Game Hub
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Menu Kiri (Untuk Semua Orang) -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('games.index') }}">
                        <i class="fas fa-download me-1"></i>Game
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blog.index') }}">
                        <i class="fas fa-newspaper me-1"></i>Blog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('events.index') }}">
                        <i class="fas fa-calendar me-1"></i>Event
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('leaderboard.index') }}">
                        <i class="fas fa-trophy me-1"></i>Papan Peringkat
                    </a>
                </li>

                <!-- Menu Khusus yang Sudah Login -->
                @auth
                    <!-- Dropdown Fitur Game -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="gameFeaturesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-gamepad me-1"></i>Fitur Game
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="gameFeaturesDropdown">
                            <li><a class="dropdown-item" href="{{ route('prestige.index') }}"><i class="fas fa-star me-2"></i>Prestige</a></li>
                            <li><a class="dropdown-item" href="{{ route('jobs.index') }}"><i class="fas fa-briefcase me-2"></i>Pekerjaan</a></li>
                            <li><a class="dropdown-item" href="{{ route('inventory.index') }}"><i class="fas fa-box me-2"></i>Inventaris</a></li>
                            <li><a class="dropdown-item" href="{{ route('market.index') }}"><i class="fas fa-shopping-cart me-2"></i>Pasar</a></li>
                            <li><a class="dropdown-item" href="{{ route('bank.index') }}"><i class="fas fa-university me-2"></i>Bank</a></li>
                            <li><a class="dropdown-item" href="{{ route('trades.index') }}"><i class="fas fa-exchange-alt me-2"></i>Perdagangan</a></li>
                        </ul>
                    </li>

                    <!-- Dropdown Mini Game -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="miniGamesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-dice me-1"></i>Mini Game
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="miniGamesDropdown">
                            <li><a class="dropdown-item" href="{{ route('gacha.index') }}"><i class="fas fa-random me-2"></i>Gacha</a></li>
                            <li><a class="dropdown-item" href="{{ route('lucky-wheel.index') }}"><i class="fas fa-circle-notch me-2"></i>Roda Keberuntungan</a></li>
                            <li><a class="dropdown-item" href="{{ route('quiz.index') }}"><i class="fas fa-question-circle me-2"></i>Kuis</a></li>
                            <li><a class="dropdown-item" href="{{ route('reaction-test.index') }}"><i class="fas fa-bolt me-2"></i>Tes Reaksi</a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('community.dashboard') }}">
                            <i class="fas fa-users me-1"></i>Komunitas
                        </a>
                    </li>

                    <!-- Menu Khusus VIP -->
                    @if(Auth::user()->isVip())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vip.content') }}">
                            <i class="fas fa-gem me-1"></i>Konten VIP
                        </a>
                    </li>
                    @endif

                    <!-- Menu Khusus Staff -->
                    @if(Auth::user()->isStaff())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('staff.index') }}">
                            <i class="fas fa-users-cog me-1"></i>Dashboard Staff
                        </a>
                    </li>
                    @endif

                    <!-- Menu Khusus Admin/Owner -->
                    @if(Auth::user()->isAdminOrOwner())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminPanelDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cogs me-1"></i>Panel Admin
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="adminPanelDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.users') }}"><i class="fas fa-users me-2"></i>Manajemen Pengguna</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.requests') }}"><i class="fas fa-list me-2"></i>Permintaan Game</a></li>
                            <li><a class="dropdown-item" href="{{ route('tickets.index') }}"><i class="fas fa-ticket-alt me-2"></i>Tiket Dukungan</a></li>
                            
                            @if(Auth::user()->isOwner())
                            <li><hr class="dropdown-divider"></li>
                            <li><span class="dropdown-header">Kontrol Bot Discord</span></li>
                            <li><a class="dropdown-item" href="{{ route('admin.discord.status') }}"><i class="fas fa-robot me-2"></i>Status Bot</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.discord.send-message.form') }}"><i class="fas fa-comment me-2"></i>Kirim Pesan</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.discord.voice.form') }}"><i class="fas fa-microphone me-2"></i>Kontrol Suara</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                @endauth
            </ul>

            <!-- Menu Kanan (Login/Profil) -->
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('auth.discord') }}">
                            <i class="fab fa-discord me-1"></i>Masuk dengan Discord
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->discord_avatar_url }}" alt="{{ Auth::user()->username }}" class="rounded-circle me-1" style="width: 24px; height: 24px; object-fit: cover;">
                            {{ Auth::user()->username }}
                            @if(Auth::user()->isVip())
                                <i class="fas fa-crown text-warning"></i>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('requests.index') }}"><i class="fas fa-list me-2"></i>Permintaan Saya</a></li>
                            
                            @if(Auth::user()->isVip())
                                <li><a class="dropdown-item" href="{{ route('vip.index') }}"><i class="fas fa-crown me-2"></i>Status VIP</a></li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left;">
                                        <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>