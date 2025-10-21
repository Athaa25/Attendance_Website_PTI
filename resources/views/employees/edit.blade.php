<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit User / Pegawai</title>
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
                        <li><a href="#"
                                class="block px-3 py-2 rounded bg-blue-100 text-blue-700 font-semibold">Manage User</a>
                        </li>
                        <li><a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">User Setting</a></li>

                        <li class="font-semibold text-sm uppercase text-gray-500 mt-4">Attendance</li>
                        <li><a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">Schedule</a></li>
                        <li><a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">Daily Attendance</a></li>
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
                    <p class="text-sm text-gray-400">Halo Selamat Datang, Probo</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-gray-300"></div>
                    <span class="text-gray-700 font-medium">Akbar Probo</span>
                </div>
            </header>

            <!-- Form Edit -->
            <div class="flex-1 p-8">
                <div class="bg-white rounded-lg shadow p-8 max-w-6xl mx-auto">
                    <h2 class="text-lg font-semibold text-gray-800 mb-6">Edit User / Pegawai</h2>

                    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Grid 2 Kolom -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label class="block text-gray-600 font-medium mb-2">Name *</label>
                                <input type="text" name="name" 
                                       value="{{ old('name', $employee->name) }}" 
                                       class="w-full border border-blue-200 rounded-md p-2 focus:ring-2 focus:ring-blue-400" required>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-gray-600 font-medium mb-2">Phone *</label>
                                <input type="text" name="telepon" placeholder="+62..." 
                                       class="w-full border border-blue-200 rounded-md p-2 focus:ring-2 focus:ring-blue-400">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-gray-600 font-medium mb-2">Email *</label>
                                <input type="email" name="email" placeholder="Masukkan email"
                                       class="w-full border border-blue-200 rounded-md p-2 focus:ring-2 focus:ring-blue-400">
                            </div>

                            <!-- NIP -->
                            <div>
                                <label class="block text-gray-600 font-medium mb-2">NIP *</label>
                                <input type="text" name="nip" placeholder="Masukkan NIP"
                                       class="w-full border border-blue-200 rounded-md p-2 focus:ring-2 focus:ring-blue-400">
                            </div>

                            <!-- NIK -->
                            <div>
                                <label class="block text-gray-600 font-medium mb-2">NIK *</label>
                                <input type="text" name="nik" placeholder="Masukkan NIK"
                                       class="w-full border border-blue-200 rounded-md p-2 focus:ring-2 focus:ring-blue-400">
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label class="block text-gray-600 font-medium mb-2">Date of Birth *</label>
                                <input type="date" name="tanggal_lahir"
                                       class="w-full border border-blue-200 rounded-md p-2 focus:ring-2 focus:ring-blue-400 cursor-pointer">
                            </div>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="mt-6">
                            <label class="block text-gray-600 font-medium mb-2">Jenis Kelamin *</label>
                            <div class="flex items-center space-x-8">
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="jenis_kelamin" value="L" class="text-blue-600">
                                    <span>Laki-laki</span>
                                </label>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="jenis_kelamin" value="P" class="text-blue-600">
                                    <span>Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <!-- Departemen (Placeholder Dropdown) -->
                        <div class="mt-6">
                            <label class="block text-gray-600 font-medium mb-2">Departemen *</label>
                            <select name="departemen"
                                class="w-full border border-blue-200 rounded-md p-2 focus:ring-2 focus:ring-blue-400 cursor-pointer">
                                <option value="">-- Pilih Departemen --</option>
                                <option value="finance">Finance</option>
                                <option value="hrd">HRD</option>
                                <option value="it">IT</option>
                                <option value="marketing">Marketing</option>
                                <option value="produksi">Produksi</option>
                            </select>
                        </div>

                        <!-- Address -->
                        <div class="mt-6">
                            <label class="block text-gray-600 font-medium mb-2">Address *</label>
                            <textarea name="alamat" rows="3" placeholder="Masukkan alamat karyawan"
                                class="w-full border border-blue-200 rounded-md p-2 focus:ring-2 focus:ring-blue-400"></textarea>
                        </div>

                        <!-- Tombol -->
                        <div class="flex justify-end space-x-3 mt-8">
                            <a href="{{ route('employees.index') }}"
                                class="bg-red-700 text-white px-5 py-2 rounded hover:bg-red-800 transition">Cancel</a>
                            <button type="submit"
                                class="bg-blue-900 text-white px-5 py-2 rounded hover:bg-blue-800 transition">Edit</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
