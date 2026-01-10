@extends('layouts.dashboard')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')
@section('page-subtitle', 'Catatan aksi tambah, ubah, dan hapus data')

@section('content')
    <div class="content-wrapper">
        <div class="section-header">
            <div>
                <h2 class="section-title">Log Aktivitas</h2>
                <p class="section-subtitle">Pantau siapa yang terakhir mengubah data.</p>
            </div>
        </div>

        <div data-activity-results>
            @include('activity-logs.partials.table', ['logs' => $logs])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const resultsContainer = document.querySelector('[data-activity-results]');
            if (!resultsContainer) return;

            let controller = null;

            const fetchResults = (url = null) => {
                const targetUrl = url
                    ? new URL(url, window.location.origin)
                    : new URL('{{ route('activity-logs.search') }}', window.location.origin);

                if (!url) {
                    const current = new URL(window.location.href);
                    current.searchParams.forEach((value, key) => targetUrl.searchParams.set(key, value));
                }

                if (controller) controller.abort();
                controller = new AbortController();

                fetch(targetUrl.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    signal: controller.signal,
                })
                    .then((response) => (response.ok ? response.json() : Promise.reject(response)))
                    .then((payload) => {
                        if (typeof payload?.html === 'string') {
                            resultsContainer.innerHTML = payload.html;
                        }
                    })
                    .catch((error) => {
                        if (error.name === 'AbortError') return;
                        console.error('Gagal memuat log aktivitas', error);
                    });
            };

            resultsContainer.addEventListener('click', (event) => {
                const link = event.target.closest('a');
                if (!link) return;
                const href = link.getAttribute('href');
                if (!href || !href.includes('page=')) return;
                event.preventDefault();
                fetchResults(href);
                window.history.replaceState({}, '', link.href);
            });

            const AUTO_REFRESH_MS = 10000;
            setInterval(() => {
                if (document.hidden) return;
                fetchResults();
            }, AUTO_REFRESH_MS);
        });
    </script>
@endpush
