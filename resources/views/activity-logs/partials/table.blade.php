<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Pengguna</th>
                <th>Aksi</th>
                <th>Target</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td>{{ $log->created_at->translatedFormat('d M Y H:i') }}</td>
                    <td>{{ $log->actor_name }}</td>
                    <td>{{ ucfirst($log->action) }}</td>
                    <td>{{ $log->entity_label }}</td>
                    <td>{{ $log->description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 24px; color: var(--text-muted);">
                        Belum ada aktivitas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="display: flex; justify-content: flex-end; margin-top: 16px;">
    {{ $logs->links() }}
</div>
