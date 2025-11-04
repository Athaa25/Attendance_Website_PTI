@php
    $greetingName = $user?->name ?? 'Administrator';
    $displayTitle = $title ?? 'Dashboard Admin';
    $displaySubtitle = $subtitle ?? 'Halo, selamat datang ' . $greetingName;
    $showDate = $showDate ?? true;
@endphp

<header class="top-header">
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
    <div class="profile-info">
        <div class="avatar">{{ $userInitials }}</div>
        <span class="profile-name">{{ $user?->name ?? 'Administrator' }}</span>
    </div>
</header>
