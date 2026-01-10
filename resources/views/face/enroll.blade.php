@extends('layouts.dashboard')

@section('title', 'Face Enrollment')
@section('page-title', 'Face Enrollment')
@section('page-subtitle', 'Daftarkan wajah ke sistem pengenalan wajah')

@push('styles')
    <style>
        .face-search-wrapper {
            position: relative;
            width: 100%;
        }

        .face-suggestions {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            box-shadow: 0 18px 48px rgba(17, 43, 105, 0.16);
            padding: 8px 0;
            max-height: 280px;
            overflow-y: auto;
            display: none;
            z-index: 12;
        }

        .face-suggestions.open {
            display: block;
        }

        .face-suggestion-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
            width: 100%;
            text-align: left;
            padding: 10px 14px;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .face-suggestion-item:hover,
        .face-suggestion-item:focus-visible {
            background: rgba(17, 43, 105, 0.06);
        }

        .face-suggestion-title {
            font-weight: 600;
            color: var(--blue-primary);
        }

        .face-suggestion-subtitle {
            font-size: 12px;
            color: var(--text-muted);
        }

        .face-suggestion-empty {
            padding: 10px 14px;
            color: var(--text-muted);
            font-size: 13px;
        }
    </style>
@endpush

@section('content')
    <section class="content-wrapper">
        @if (session('status'))
            <div class="status-banner">
                <img src="{{ asset('images/user-setting-icon.png') }}" alt="Info" width="20" height="20">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <strong>Gagal memproses permintaan.</strong>
                <ul class="alert-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <div class="form-header">
                <div>
                    <h2 class="form-title">Enroll Wajah Baru</h2>
                    <p class="form-subtitle">Upload satu atau lebih foto wajah untuk dibuat embedding.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('face.enroll.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <div class="face-search-wrapper" data-face-search-wrapper>
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" autocomplete="off" required data-face-name>
                            <div class="face-suggestions" data-face-suggestions></div>
                        </div>
                        <p class="helper-text">Ketik nama pegawai, pilih dari saran yang muncul.</p>
                    </div>
                    <div class="form-group">
                        <label for="image">Foto Wajah</label>
                        <input id="image" name="images[]" type="file" class="form-control" accept="image/*" multiple required>
                        <p class="helper-text">Bisa pilih banyak foto sekaligus (format apa pun akan dicoba dikonversi).</p>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enroll Wajah</button>
                </div>
            </form>
        </div>

        <div>
            <div class="schedule-header">
                <div>
                    <h2 class="schedule-title">Reload dari Folder faces/</h2>
                    <p class="schedule-subtitle">Gunakan jika menambah foto manual ke folder faces/.</p>
                </div>
                <div class="schedule-actions">
                    <form method="POST" action="{{ route('face.enroll.reload') }}" class="inline-form" onsubmit="return confirm('Reload embeddings dari folder faces/?');">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Reload Faces</button>
                    </form>
                </div>
            </div>
            <p class="helper-text">Contoh struktur: faces/Nama/1.jpg, faces/Nama/2.jpg.</p>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (() => {
            const input = document.querySelector('[data-face-name]');
            const suggestions = document.querySelector('[data-face-suggestions]');
            const wrapper = document.querySelector('[data-face-search-wrapper]');
            if (!input || !suggestions || !wrapper) return;

            let timer;
            let controller;

            const closeSuggestions = () => {
                suggestions.classList.remove('open');
                suggestions.innerHTML = '';
            };

            const renderSuggestions = (items) => {
                if (!items.length) {
                    suggestions.innerHTML = '<div class="face-suggestion-empty">Tidak ada hasil.</div>';
                    suggestions.classList.add('open');
                    return;
                }

                suggestions.innerHTML = items.map((item) => {
                    const subtitle = [item.department, item.position].filter(Boolean).join(' · ');
                    return `
                        <button type="button" class="face-suggestion-item" data-name="${item.label}">
                            <span class="face-suggestion-title">${item.label}</span>
                            ${subtitle ? `<span class="face-suggestion-subtitle">${subtitle}</span>` : ''}
                        </button>
                    `;
                }).join('');
                suggestions.classList.add('open');
            };

            const fetchSuggestions = async (term) => {
                if (controller) controller.abort();
                controller = new AbortController();

                const url = `/manage-users/search?search=${encodeURIComponent(term)}`;
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: controller.signal,
                });
                const data = await response.json();
                renderSuggestions(data.suggestions || []);
            };

            input.addEventListener('input', () => {
                const term = input.value.trim();
                if (term.length < 2) {
                    closeSuggestions();
                    return;
                }

                clearTimeout(timer);
                timer = setTimeout(() => {
                    fetchSuggestions(term).catch(() => closeSuggestions());
                }, 250);
            });

            suggestions.addEventListener('click', (event) => {
                const target = event.target.closest('[data-name]');
                if (!target) return;
                input.value = target.getAttribute('data-name') || '';
                closeSuggestions();
            });

            document.addEventListener('click', (event) => {
                if (!wrapper.contains(event.target)) {
                    closeSuggestions();
                }
            });
        })();
    </script>
@endpush
