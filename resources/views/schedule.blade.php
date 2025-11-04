<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        :root {
            --blue-primary: #112B69;
            --text-dark: #1F1F1F;
            --text-muted: #6F6F6F;
            --background: #F9FBFF;
            --card-background: #FFFFFF;
            --border-color: #E4E7EC;
            --danger: #EF4444;
            --warning: #F59E0B;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Poppins", "Segoe UI", sans-serif;
            background-color: var(--background);
            color: var(--text-dark);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .page-wrapper {
            min-height: 100vh;
            padding: 48px 64px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .schedule-card {
            width: 100%;
            max-width: 1100px;
            background-color: var(--card-background);
            border-radius: 32px;
            padding: 40px 48px;
            box-shadow: 0 20px 60px rgba(17, 43, 105, 0.08);
        }

        .schedule-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }

        .schedule-title {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--blue-primary);
        }

        .schedule-subtitle {
            margin: 6px 0 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .schedule-actions {
            display: flex;
            gap: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:focus {
            outline: none;
        }

        .btn-primary {
            background-color: var(--blue-primary);
            color: #FFFFFF;
            box-shadow: 0 10px 25px rgba(17, 43, 105, 0.15);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(17, 43, 105, 0.2);
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 24px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        thead {
            background-color: rgba(17, 43, 105, 0.04);
        }

        th {
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            color: var(--text-muted);
            padding: 16px 24px;
            letter-spacing: 0.01em;
        }

        td {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            font-weight: 500;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background-color: rgba(17, 43, 105, 0.04);
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-button {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            background-color: rgba(17, 43, 105, 0.08);
            color: var(--blue-primary);
        }

        .icon-button.edit {
            background-color: rgba(17, 43, 105, 0.1);
        }

        .icon-button.delete {
            background-color: rgba(239, 68, 68, 0.12);
            color: var(--danger);
        }

        .icon-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(17, 43, 105, 0.15);
        }

        .icon-button.delete:hover {
            box-shadow: 0 8px 18px rgba(239, 68, 68, 0.15);
        }

        .shift-id {
            color: var(--blue-primary);
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .page-wrapper {
                padding: 32px 24px;
            }

            .schedule-card {
                padding: 32px 24px;
            }

            .schedule-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .schedule-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <section class="schedule-card">
            <header class="schedule-header">
                <div>
                    <h1 class="schedule-title">Schedule</h1>
                    <p class="schedule-subtitle">Daftar shift dan jam masuk/keluar</p>
                </div>
                <div class="schedule-actions">
                    <button class="btn btn-primary" type="button">
                        Add
                    </button>
                </div>
            </header>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Id Shift</th>
                            <th>Shift Name</th>
                            <th>Check in</th>
                            <th>Check out</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $schedules = [
                                ['id' => '01', 'name' => 'Pagi', 'check_in' => '08.00', 'check_out' => '16.00'],
                                ['id' => '02', 'name' => 'Siang', 'check_in' => '12.00', 'check_out' => '20.00'],
                                ['id' => '03', 'name' => 'Malam', 'check_in' => '20.00', 'check_out' => '04.00'],
                                ['id' => '04', 'name' => 'Pagi', 'check_in' => '08.00', 'check_out' => '16.00'],
                                ['id' => '05', 'name' => 'Siang', 'check_in' => '12.00', 'check_out' => '20.00'],
                                ['id' => '06', 'name' => 'Malam', 'check_in' => '20.00', 'check_out' => '04.00'],
                                ['id' => '07', 'name' => 'Pagi', 'check_in' => '08.00', 'check_out' => '16.00'],
                                ['id' => '08', 'name' => 'Siang', 'check_in' => '12.00', 'check_out' => '20.00'],
                                ['id' => '09', 'name' => 'Malam', 'check_in' => '20.00', 'check_out' => '04.00'],
                                ['id' => '10', 'name' => 'Pagi', 'check_in' => '08.00', 'check_out' => '16.00'],
                            ];
                        @endphp

                        @foreach ($schedules as $schedule)
                            <tr>
                                <td class="shift-id">{{ $schedule['id'] }}</td>
                                <td>{{ $schedule['name'] }}</td>
                                <td>{{ $schedule['check_in'] }}</td>
                                <td>{{ $schedule['check_out'] }}</td>
                                <td>
                                    <div class="actions" style="justify-content: flex-end;">
                                        <button class="icon-button edit" type="button" title="Edit">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4 21H9L20.5 9.5C20.8978 9.10218 21.1213 8.56227 21.1213 7.9975C21.1213 7.43273 20.8978 6.89282 20.5 6.495L17.505 3.5C17.1072 3.10223 16.5673 2.87872 16.0025 2.87872C15.4377 2.87872 14.8978 3.10223 14.5 3.5L3 15V20C3 20.2652 3.10536 20.5196 3.29289 20.7071C3.48043 20.8946 3.73478 21 4 21Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <button class="icon-button delete delete-btn" type="button" title="Delete">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4 7H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M10 11V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M14 11V17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M5 7L6 19C6 19.5304 6.21071 20.0391 6.58579 20.4142C6.96086 20.7893 7.46957 21 8 21H16C16.5304 21 17.0391 20.7893 17.4142 20.4142C17.7893 20.0391 18 19.5304 18 19L19 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M9 7V4C9 3.73478 9.10536 3.48043 9.29289 3.29289C9.48043 3.10536 9.73478 3 10 3H14C14.2652 3 14.5196 3.10536 14.7071 3.29289C14.8946 3.48043 15 3.73478 15 4V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        document.querySelectorAll('.delete-btn').forEach((button) => {
            button.addEventListener('click', () => {
                const confirmed = window.confirm('Apakah Anda yakin ingin menghapus data shift ini?');
                if (confirmed) {
                    // Placeholder untuk aksi hapus
                    alert('Data shift telah dihapus (simulasi).');
                }
            });
        });
    </script>
</body>
</html>
