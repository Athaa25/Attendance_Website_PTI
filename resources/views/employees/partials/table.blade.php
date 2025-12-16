<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Departemen</th>
                <th>Jadwal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $employee)
                <tr>
                    <td>{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                    <td>
                        <a class="table-name-link" href="{{ route('manage-users.show', $employee) }}">
                            {{ $employee->full_name }}
                        </a>
                        <div style="font-size: 12px; color: var(--text-muted);">
                            {{ $employee->user->email }}
                        </div>
                    </td>
                    <td>{{ $employee->department->name ?? 'ƒ?"' }}</td>
                    <td>{{ $employee->schedule->name ?? 'ƒ?"' }}</td>
                    <td>
                        <span class="status-badge status-{{ $employee->employment_status }}">
                            {{ $employee->employment_status_label }}
                        </span>
                    </td>
                    <td>
                        <div class="table-actions">
                            <a class="action-link detail" href="{{ route('manage-users.show', $employee) }}">Detail</a>
                            <a class="action-link edit" href="{{ route('manage-users.edit', $employee) }}">Edit</a>
                            <form method="POST" action="{{ route('manage-users.destroy', $employee) }}" onsubmit="return confirm('Hapus pegawai ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-link delete">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 24px; color: var(--text-muted);">
                        Belum ada data pegawai yang sesuai filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($employees->hasPages())
        <div class="pagination">
            <span>Halaman {{ $employees->currentPage() }} dari {{ $employees->lastPage() }}</span>
            <div class="pagination-controls">
                <a
                    class="pagination-button {{ $employees->onFirstPage() ? 'disabled' : '' }}"
                    href="{{ $employees->previousPageUrl() ?? '#' }}"
                    data-pagination-link
                >
                    Sebelumnya
                </a>
                <a
                    class="pagination-button {{ $employees->hasMorePages() ? '' : 'disabled' }}"
                    href="{{ $employees->nextPageUrl() ?? '#' }}"
                    data-pagination-link
                >
                    Selanjutnya
                </a>
            </div>
        </div>
    @endif
</div>
