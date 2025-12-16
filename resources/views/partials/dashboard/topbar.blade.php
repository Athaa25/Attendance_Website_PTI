@php
    $greetingName = $user?->name ?? 'Administrator';
    $displayTitle = $title ?? 'Dashboard Admin';
    $displaySubtitle = $subtitle ?? 'Halo, selamat datang ' . $greetingName;
    $showDate = $showDate ?? true;
@endphp

<header class="top-header">
    <div class="top-header-left">
        <button class="topbar-logo" type="button" aria-label="Toggle sidebar" aria-expanded="true" data-sidebar-toggle>
            <img src="{{ asset('images/RMDOO_logo.png') }}" alt="RMDOO Logo" class="topbar-logo-img">
        </button>
        <div>
            <h1 class="top-header-title">{{ $displayTitle }}</h1>
            <p class="top-header-subtitle">
                {{ $displaySubtitle }}
                @if ($showDate)
                    &middot;
                    <span>{{ ($now ?? now())->translatedFormat('d F Y') }}</span>
                @endif
            </p>
        </div>
    </div>
    <div class="profile-info">
        <div class="avatar">{{ $userInitials }}</div>
        <span class="profile-name">{{ $user?->name ?? 'Administrator' }}</span>
    </div>
</header>
