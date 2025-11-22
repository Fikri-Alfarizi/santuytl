<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - GameHub</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #e74c3c;
            --secondary-color: #2c3e50;
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

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-color);
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 250px;
            background-color: var(--secondary-color);
            padding: 20px 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.2);
        }

        .admin-sidebar .brand {
            text-align: center;
            margin-bottom: 30px;
            color: var(--text-color);
            font-size: 1.5rem;
            font-weight: 700;
        }

        .admin-sidebar .brand i {
            color: var(--primary-color);
            margin-right: 10px;
        }

        .admin-sidebar .menu {
            list-style: none;
        }

        .admin-sidebar .menu-item {
            padding: 12px 20px;
            color: var(--text-muted);
            text-decoration: none;
            display: block;
            transition: all 0.3s;
        }

        .admin-sidebar .menu-item:hover, .admin-sidebar .menu-item.active {
            background-color: var(--primary-color);
            color: white;
        }

        .admin-sidebar .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .admin-content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .card {
            background-color: var(--light-bg);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }
        
        .stat-card {
            text-align: center;
        }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--light-bg);
        }

        .table th, .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .table th {
            background-color: var(--lighter-bg);
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: var(--darker-bg);
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .btn-success { background-color: #2ecc71; color: white; }
        .btn-danger { background-color: var(--accent-color); color: white; }
        .btn-primary { background-color: var(--primary-color); color: white; }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .admin-sidebar { width: 100%; padding: 10px 0; }
            .admin-sidebar .menu { display: flex; overflow-x: auto; }
            .admin-sidebar .menu-item { white-space: nowrap; }
        }
        
        @yield('styles')
    </style>
</head>
<body>
    <div class="admin-sidebar">
        <div class="brand">
            <i class="fas fa-cogs"></i> Admin Panel
        </div>
        <ul class="menu">
            <li><a href="{{ route('admin.dashboard') }}" class="menu-item @if(request()->routeIs('admin.dashboard')) active @endif"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="{{ route('admin.users') }}" class="menu-item @if(request()->routeIs('admin.users*')) active @endif"><i class="fas fa-users"></i> Pengguna</a></li>
            <li><a href="{{ route('admin.requests') }}" class="menu-item @if(request()->routeIs('admin.requests*')) active @endif"><i class="fas fa-list"></i> Permintaan Game</a></li>
            
            @if(Auth::user()->isOwner())
            <li class="menu-item" style="font-weight:bold; color:var(--primary-color); margin-top:10px;">Bot Discord</li>
            
            @if(session('selected_guild_name'))
            <li class="menu-item" style="font-size:0.85rem; color:var(--text-muted); padding:8px 20px;">
                <i class="fas fa-server"></i> Server: <strong>{{ session('selected_guild_name') }}</strong>
            </li>
            @else
            <li class="menu-item" style="font-size:0.85rem; color:#f39c12; padding:8px 20px;">
                <i class="fas fa-exclamation-triangle"></i> Belum pilih server
            </li>
            @endif
            
            <li><a href="{{ route('admin.discord.select-server') }}" class="menu-item @if(request()->routeIs('admin.discord.select-server')) active @endif"><i class="fas fa-server"></i> Pilih Server</a></li>
            <li><a href="{{ route('admin.discord.status') }}" class="menu-item @if(request()->routeIs('admin.discord.status')) active @endif"><i class="fab fa-discord"></i> Status Bot</a></li>
            <li><a href="{{ route('admin.discord.send-message.form') }}" class="menu-item @if(request()->routeIs('admin.discord.send-message.form')) active @endif"><i class="fas fa-envelope"></i> Kirim Pesan</a></li>
            <li><a href="{{ route('admin.discord.send-dm.form') }}" class="menu-item @if(request()->routeIs('admin.discord.send-dm.form')) active @endif"><i class="fas fa-paper-plane"></i> Kirim DM</a></li>
            <li><a href="{{ route('admin.discord.assign-role.form') }}" class="menu-item @if(request()->routeIs('admin.discord.assign-role.form')) active @endif"><i class="fas fa-user-plus"></i> Assign Role</a></li>
            <li><a href="{{ route('admin.discord.remove-role.form') }}" class="menu-item @if(request()->routeIs('admin.discord.remove-role.form')) active @endif"><i class="fas fa-user-minus"></i> Remove Role</a></li>
            <li><a href="{{ route('admin.discord.kick.form') }}" class="menu-item @if(request()->routeIs('admin.discord.kick.form')) active @endif"><i class="fas fa-user-slash"></i> Kick User</a></li>
            <li><a href="{{ route('admin.discord.voice.form') }}" class="menu-item @if(request()->routeIs('admin.discord.voice.form')) active @endif"><i class="fa-solid fa-microphone"></i> Voice 24/7</a></li>
            <li><a href="{{ route('admin.discord.ban.form') }}" class="menu-item @if(request()->routeIs('admin.discord.ban.form')) active @endif"><i class="fas fa-ban"></i> Ban User</a></li>
            @endif
            
            <li><a href="{{ route('dashboard') }}" class="menu-item"><i class="fas fa-home"></i> Kembali ke Beranda</a></li>
        </ul>
    </div>

    <main class="admin-content">
        @include('partials.messages')
        @yield('content')
    </main>
</body>
</html>