@forelse ($positions ?? [] as $item)
    <tr>
        <td class="department-name">{{ optional($item->department)->name ?? 'Tanpa Departemen' }}</td>
        <td>{{ $item->name }}</td>
        <td>
            <div class="actions" style="justify-content: flex-end;">
                <a class="icon-button edit" href="{{ route('departments.edit', $item) }}" title="Edit">
                    <img src="{{ asset('images/edit-icon.png') }}" alt="Edit department">
                </a>
                <form class="inline-form" action="{{ route('departments.destroy', $item) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                    @csrf
                    @method('DELETE')
                    <button class="icon-button delete" type="submit" title="Delete">
                        <img src="{{ asset('images/delete-icon.png') }}" alt="Delete department">
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" style="text-align: center; padding: 24px; color: var(--text-muted);">
            Belum ada data departemen dan jabatan yang tersedia.
        </td>
    </tr>
@endforelse
