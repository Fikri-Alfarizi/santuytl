@extends('layouts.app')

@section('title', 'Beranda')

@section('styles')
<style>
    /* ==================== LANDING PAGE STYLES ==================== */
    
    /* Hero Section */
    .hero-section {
        position: relative;
        min-height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
        z-index: 1;
    }
    
    .hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        padding: 2rem;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.8s ease-out;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 2.5rem;
        line-height: 1.8;
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }
    
    .hero-buttons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }
    
    .btn-hero {
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-hero-primary {
        background: white;
        color: #667eea;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    
    .btn-hero-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        color: #667eea;
    }
    
    .btn-hero-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 2px solid white;
        backdrop-filter: blur(10px);
    }
    
    .btn-hero-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-3px);
        color: white;
    }
    
    /* Stats Section */
    .stats-section {
        padding: 5rem 0;
        background: linear-gradient(180deg, #1a1a1a 0%, #0f0f0f 100%);
    }
    
    .section-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 3rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border: 1px solid rgba(102, 126, 234, 0.2);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        border-color: rgba(102, 126, 234, 0.5);
    }
    
    .stat-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: var(--text-muted);
        font-size: 1rem;
    }
    
    /* Features Section */
    .features-section {
        padding: 5rem 0;
        background: #0f0f0f;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2.5rem;
        margin-top: 3rem;
    }
    
    .feature-card {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        border: 1px solid rgba(102, 126, 234, 0.1);
        border-radius: 20px;
        padding: 2.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .feature-card:hover::before {
        opacity: 1;
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        border-color: rgba(102, 126, 234, 0.3);
    }
    
    .feature-icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
    }
    
    .feature-icon {
        font-size: 2.5rem;
        color: white;
    }
    
    .feature-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }
    
    .feature-description {
        color: var(--text-muted);
        line-height: 1.8;
        position: relative;
        z-index: 1;
    }
    
    /* VIP Section */
    .vip-section {
        padding: 5rem 0;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    }
    
    .vip-content {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }
    
    .vip-badge {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        color: #1a1a1a;
        border-radius: 50px;
        font-weight: 700;
        margin-bottom: 2rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .vip-benefits {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 3rem;
    }
    
    .vip-benefit {
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        border: 1px solid rgba(255, 215, 0, 0.2);
        transition: all 0.3s ease;
    }
    
    .vip-benefit:hover {
        background: rgba(255, 215, 0, 0.1);
        border-color: rgba(255, 215, 0, 0.4);
        transform: scale(1.05);
    }
    
    .vip-benefit i {
        font-size: 2rem;
        color: #ffd700;
        margin-bottom: 1rem;
    }
    
    /* Server Selection Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }
    
    .modal.active {
        display: flex;
    }
    
    .modal-content {
        background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
        border-radius: 20px;
        padding: 2.5rem;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(102, 126, 234, 0.3);
        animation: slideUp 0.3s ease;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .modal-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: white;
    }
    
    .modal-close {
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 1.5rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }
    
    .modal-close:hover {
        color: white;
    }
    
    .server-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .server-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .server-item:hover {
        background: rgba(102, 126, 234, 0.2);
        border-color: rgba(102, 126, 234, 0.5);
        transform: translateX(5px);
    }
    
    .server-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
    }
    
    .server-icon img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .server-info {
        flex: 1;
    }
    
    .server-name {
        font-weight: 600;
        color: white;
        margin-bottom: 0.25rem;
    }
    
    .server-badge {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        background: rgba(102, 126, 234, 0.3);
        border-radius: 5px;
        font-size: 0.75rem;
        color: #667eea;
    }
    
    .loading-spinner {
        text-align: center;
        padding: 2rem;
    }
    
    .spinner {
        border: 3px solid rgba(255, 255, 255, 0.1);
        border-top: 3px solid #667eea;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-title { font-size: 2.5rem; }
        .hero-subtitle { font-size: 1.1rem; }
        .hero-buttons { flex-direction: column; }
        .section-title { font-size: 2rem; }
        .stats-grid, .features-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">
    <div class="container">
        <h2 class="section-title">Digunakan Oleh Banyak Server</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-server"></i></div>
                <div class="stat-number">441+</div>
                <div class="stat-label">Server Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-number">10K+</div>
                <div class="stat-label">Member Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-gamepad"></i></div>
                <div class="stat-number">500+</div>
                <div class="stat-label">Game Tersedia</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-download"></i></div>
                <div class="stat-number">50K+</div>
                <div class="stat-label">Total Download</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section" id="features">
    <div class="container">
        <h2 class="section-title">Fitur Unggulan</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <i class="feature-icon fas fa-gamepad"></i>
                </div>
                <h3 class="feature-title">Download Game Gratis</h3>
                <p class="feature-description">
                    Akses ribuan game premium tanpa biaya. Semua game tersedia untuk member komunitas kami.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <i class="feature-icon fas fa-ban"></i>
                </div>
                <h3 class="feature-title">Tanpa Iklan</h3>
                <p class="feature-description">
                    Nikmati pengalaman download tanpa gangguan iklan. Fokus pada game, bukan iklan.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <i class="feature-icon fas fa-users"></i>
                </div>
                <h3 class="feature-title">Komunitas Aktif</h3>
                <p class="feature-description">
                    Bergabung dengan ribuan gamer lainnya. Diskusi, tips, dan teman baru menanti Anda.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <i class="feature-icon fas fa-bolt"></i>
                </div>
                <h3 class="feature-title">Download Cepat</h3>
                <p class="feature-description">
                    Server berkecepatan tinggi memastikan download game Anda selesai dalam hitungan menit.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <i class="feature-icon fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title">Aman & Terpercaya</h3>
                <p class="feature-description">
                    Semua file telah diverifikasi dan aman. Keamanan data Anda adalah prioritas kami.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <i class="feature-icon fas fa-sync"></i>
                </div>
                <h3 class="feature-title">Update Rutin</h3>
                <p class="feature-description">
                    Game baru ditambahkan setiap minggu. Selalu ada sesuatu yang baru untuk dimainkan.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- VIP Section -->
<section class="vip-section">
    <div class="container">
        <div class="vip-content">
            <span class="vip-badge">
                <i class="fas fa-crown"></i> VIP MEMBERSHIP
            </span>
            <h2 class="section-title">Tingkatkan Pengalaman Anda</h2>
            <p class="hero-subtitle" style="animation: none;">
                Dapatkan akses eksklusif ke fitur premium dan nikmati pengalaman gaming yang lebih baik.
            </p>
            
            <div class="vip-benefits">
                <div class="vip-benefit">
                    <i class="fas fa-star"></i>
                    <h4 style="color: white; margin-bottom: 0.5rem;">Download Prioritas</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Kecepatan download maksimal</p>
                </div>
                <div class="vip-benefit">
                    <i class="fas fa-gift"></i>
                    <h4 style="color: white; margin-bottom: 0.5rem;">Game Eksklusif</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Akses game VIP only</p>
                </div>
                <div class="vip-benefit">
                    <i class="fas fa-headset"></i>
                    <h4 style="color: white; margin-bottom: 0.5rem;">Support Premium</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Bantuan prioritas 24/7</p>
                </div>
                <div class="vip-benefit">
                    <i class="fas fa-badge-check"></i>
                    <h4 style="color: white; margin-bottom: 0.5rem;">Badge Eksklusif</h4>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Tampilkan status VIP Anda</p>
                </div>
            </div>
            
            <div style="margin-top: 2.5rem;">
                <a href="{{ route('vip.index') }}" class="btn-hero btn-hero-primary">
                    <i class="fas fa-crown"></i>
                    Upgrade ke VIP
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Server Selection Modal -->
@auth
<div class="modal" id="serverModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Pilih Server Discord</h3>
            <button class="modal-close" id="closeModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="serverListContainer">
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p style="color: var(--text-muted); margin-top: 1rem;">Memuat server...</p>
            </div>
        </div>
    </div>
</div>
@endauth
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @auth
    const selectServerBtn = document.getElementById('selectServerBtn');
    const serverModal = document.getElementById('serverModal');
    const closeModal = document.getElementById('closeModal');
    const serverListContainer = document.getElementById('serverListContainer');
    
    if (selectServerBtn) {
        selectServerBtn.addEventListener('click', function() {
            serverModal.classList.add('active');
            loadUserGuilds();
        });
    }
    
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            serverModal.classList.remove('active');
        });
    }
    
    serverModal.addEventListener('click', function(e) {
        if (e.target === serverModal) {
            serverModal.classList.remove('active');
        }
    });
    
    function loadUserGuilds() {
        serverListContainer.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p style="color: var(--text-muted); margin-top: 1rem;">Memuat server...</p>
            </div>
        `;
        
        fetch('{{ route("discord.guilds") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.guilds && data.guilds.length > 0) {
                    displayGuilds(data.guilds);
                } else {
                    serverListContainer.innerHTML = `
                        <div style="text-align: center; padding: 2rem;">
                            <i class="fas fa-exclamation-circle" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                            <p style="color: var(--text-muted);">Tidak ada server yang tersedia.</p>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">
                                Anda harus memiliki permission "Manage Server" untuk menambahkan bot.
                            </p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading guilds:', error);
                serverListContainer.innerHTML = `
                    <div style="text-align: center; padding: 2rem;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: var(--accent-color); margin-bottom: 1rem;"></i>
                        <p style="color: var(--text-muted);">Gagal memuat server. Silakan coba lagi.</p>
                    </div>
                `;
            });
    }
    
    function displayGuilds(guilds) {
        const serverList = document.createElement('div');
        serverList.className = 'server-list';
        
        guilds.forEach(guild => {
            const serverItem = document.createElement('div');
            serverItem.className = 'server-item';
            
            const iconHtml = guild.icon 
                ? `<img src="${guild.icon}" alt="${guild.name}">`
                : guild.name.charAt(0).toUpperCase();
            
            const ownerBadge = guild.is_owner 
                ? '<span class="server-badge"><i class="fas fa-crown"></i> Owner</span>'
                : '';
            
            serverItem.innerHTML = `
                <div class="server-icon">${iconHtml}</div>
                <div class="server-info">
                    <div class="server-name">${guild.name}</div>
                    ${ownerBadge}
                </div>
                <i class="fas fa-chevron-right" style="color: var(--text-muted);"></i>
            `;
            
            serverItem.addEventListener('click', function() {
                inviteBot(guild.id);
            });
            
            serverList.appendChild(serverItem);
        });
        
        serverListContainer.innerHTML = '';
        serverListContainer.appendChild(serverList);
    }
    
    function inviteBot(guildId) {
        fetch('{{ route("discord.invite") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ guild_id: guildId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.invite_url) {
                window.location.href = data.invite_url;
            } else {
                alert('Gagal membuat link invite. Silakan coba lagi.');
            }
        })
        .catch(error => {
            console.error('Error generating invite:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    }
    @endauth
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
</script>
@endsection