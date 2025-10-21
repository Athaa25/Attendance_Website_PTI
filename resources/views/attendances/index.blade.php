<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kehadiran</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex flex-col justify-between">
      <div>
        <div class="flex items-center justify-center h-20 border-b">
          <img src="https://dummyimage.com/100x40/000/fff&text=RMD" alt="Logo" />
        </div>

        <nav class="p-4">
          <ul class="space-y-2 text-gray-700">
            <li class="font-semibold text-sm uppercase text-gray-500 mb-2">Menu</li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">Dashboard</a></li>

            <li class="font-semibold text-sm uppercase text-gray-500 mt-4">Users Management</li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">Manage User</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">User Setting</a></li>

            <li class="font-semibold text-sm uppercase text-gray-500 mt-4">Attendance</li>
            <li><a href="#" class="block px-3 py-2 rounded bg-blue-100 text-blue-700 font-semibold">Daily Attendance</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">Schedule</a></li>
            <li><a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">Sheet Report</a></li>
          </ul>
        </nav>
      </div>

      <div class="p-4 border-t">
        <a href="#" class="block px-3 py-2 text-red-500 font-medium hover:bg-red-50 rounded">Keluar</a>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
      <!-- Header -->
      <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <div>
          <h1 class="text-gray-600 font-semibold">Dashboard Admin</h1>
          <p class="text-sm text-gray-400">Halo Selamat Datang Probo</p>
        </div>
        <div class="flex items-center space-x-2">
          <div class="w-8 h-8 rounded-full bg-gray-300"></div>
          <span class="text-gray-700 font-medium">Akbar Probo</span>
        </div>
      </header>

      <!-- Content -->
      <section class="flex-1 p-8">
        <div class="bg-white rounded-lg shadow p-8">
          <!-- Title -->
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Kehadiran</h2>
            <a href="{{ route('attendances.create') }}"
              class="bg-blue-900 text-white px-5 py-2 rounded hover:bg-blue-800 transition">
              Tambah Data
            </a>
          </div>

          <!-- Tabel Kehadiran -->
          <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 text-sm text-gray-700">
              <thead class="bg-gray-50 border-b">
                <tr>
                  <th class="px-4 py-3 text-left font-semibold">No</th>
                  <th class="px-4 py-3 text-left font-semibold">Nama</th>
                  <th class="px-4 py-3 text-left font-semibold">Status</th>
                  <th class="px-4 py-3 text-left font-semibold">Waktu</th>
                  <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                  <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                </tr>
              </thead>

              <tbody>
                @forelse ($attendances as $index => $attendance)
                <tr class="border-b hover:bg-gray-50">
                  <td class="px-4 py-3">{{ $index + 1 }}</td>

                  <!-- Nama + Tombol Edit Individu -->
                  <td class="px-4 py-3 flex items-center justify-between">
                    <span>{{ $attendance->employee->name ?? '-' }}</span>
                    <a href="{{ route('attendances.editpresensi', $attendance->id) }}"
                      class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded hover:bg-blue-200 transition">
                      Edit
                    </a>
                  </td>

                  <!-- Status -->
                  <td class="px-4 py-3 capitalize">{{ $attendance->status }}</td>

                  <!-- Waktu -->
                  <td class="px-4 py-3">
                    @if($attendance->checkin_time)
                      {{ \Carbon\Carbon::parse($attendance->checkin_time)->format('H:i') }}
                    @else
                      --:--
                    @endif
                  </td>

                  <!-- Tanggal -->
                  <td class="px-4 py-3">
                    {{ \Carbon\Carbon::parse($attendance->attendance_date)->translatedFormat('d F Y') }}
                  </td>

                  <!-- Keterangan -->
                  <td class="px-4 py-3">
                    @if ($attendance->status == 'izin')
                      Tidak hadir
                    @else
                      @php
                        $check = \Carbon\Carbon::parse($attendance->checkin_time);
                        $onTime = \Carbon\Carbon::createFromTime(9, 0, 0);
                      @endphp
                      {{ $check->greaterThan($onTime) ? 'Terlambat' : 'Tepat waktu' }}
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada data kehadiran.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
