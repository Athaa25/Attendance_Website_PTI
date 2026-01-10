@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
    <section class="content-wrapper">
        <div data-dashboard-metrics>
            @include('dashboard.partials.metrics')
        </div>

    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('[data-dashboard-metrics]');
            if (!container) return;

            let controller = null;

            const fetchMetrics = () => {
                const url = new URL('{{ route('dashboard.metrics') }}', window.location.origin);
                if (controller) controller.abort();
                controller = new AbortController();

                fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    signal: controller.signal,
                })
                    .then((response) => (response.ok ? response.json() : Promise.reject(response)))
                    .then((payload) => {
                        if (typeof payload?.html === 'string') {
                            container.innerHTML = payload.html;
                        }
                    })
                    .catch((error) => {
                        if (error.name === 'AbortError') return;
                        console.error('Gagal memuat data dashboard', error);
                    });
            };

            const AUTO_REFRESH_MS = 10000;
            setInterval(() => {
                if (document.hidden) return;
                fetchMetrics();
            }, AUTO_REFRESH_MS);
        });
    </script>
@endpush
