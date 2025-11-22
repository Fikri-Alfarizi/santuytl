@extends('layouts.admin')

@section('title', 'Pilih Server Discord')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fab fa-discord"></i> Pilih Server Discord</h2>
    </div>
    <div class="card-body">
        @if(session('selected_guild_name'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Server saat ini: <strong>{{ session('selected_guild_name') }}</strong>
            </div>
        @endif

        <p>Pilih server Discord yang ingin Anda kelola:</p>

        <form action="{{ route('admin.discord.set-server') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="guild_id">Server Discord</label>
                <select name="guild_id" id="guild_id" class="form-control" required>
                    <option value="">-- Pilih Server --</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check"></i> Pilih Server
            </button>
        </form>
    </div>
</div>

<style>
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--border-color);
    border-radius: 5px;
    background-color: var(--lighter-bg);
    color: var(--text-color);
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
}

.alert {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-info {
    background-color: rgba(52, 152, 219, 0.1);
    border: 1px solid #3498db;
    color: #3498db;
}

.guild-option {
    padding: 10px;
    display: flex;
    align-items: center;
}

.guild-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    margin-right: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    const select = document.getElementById('guild_id');
    
    console.log('Fetching guilds from bot API...');
    
    try {
        const response = await fetch('http://localhost:3001/guilds');
        
        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Guilds data:', data);
        
        if (data.guilds && data.guilds.length > 0) {
            // Clear the select first
            select.innerHTML = '<option value="">-- Pilih Server --</option>';
            
            data.guilds.forEach(guild => {
                const option = document.createElement('option');
                option.value = guild.id;
                option.textContent = `${guild.name} (${guild.memberCount} members)`;
                select.appendChild(option);
                console.log('Added guild:', guild.name);
            });

            // Select current guild if exists
            const currentGuildId = '{{ session("selected_guild_id") }}';
            if (currentGuildId) {
                select.value = currentGuildId;
                console.log('Selected current guild:', currentGuildId);
            }
        } else {
            console.warn('No guilds found in response');
            select.innerHTML = '<option value="">Tidak ada server ditemukan</option>';
        }
    } catch (error) {
        console.error('Error fetching guilds:', error);
        console.error('Error details:', error.message);
        select.innerHTML = `<option value="">Error: ${error.message}</option>`;
        
        // Show alert to user
        alert('Gagal memuat daftar server. Pastikan bot Discord sudah running di port 3001.\n\nError: ' + error.message);
    }
});
</script>
@endsection
