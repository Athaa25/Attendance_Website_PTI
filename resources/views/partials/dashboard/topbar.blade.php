<header class="top-header">
    <div>
        <h1 class="top-header-title">Dashboard Admin</h1>
        <p class="top-header-subtitle">
            Halo, selamat datang {{ $user?->name ?? 'Administrator' }} &middot;
            <span>{{ ($now ?? now())->translatedFormat('d F Y') }}</span>
        </p>
    </div>
    <div class="profile-info">
        <div class="avatar">{{ $userInitials }}</div>
        <span class="profile-name">{{ $user?->name ?? 'Administrator' }}</span>
    </div>
</header>
