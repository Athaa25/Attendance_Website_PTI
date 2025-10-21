<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin - Edit Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100 font-sans">
    <!-- Container utama -->
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-center h-20 border-b">
                    <img src="https://dummyimage.com/100x40/000/fff&text=RMD" alt="Logo" />
                </div>
                <nav class="p-4">
                    <ul class="space-y-2 text-gray-700">
                        <li class="font-semibold text-sm uppercase text-gray-500 mb-2">Menu</li>
                        <li>
                            <a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">Dashboard</a>
                        </li>

                        <li class="font-semibold text-sm uppercase text-gray-500 mt-4">Users Management</li>
                        <li>
                            <a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">Manage User</a>
                        </li>
                        <li>
                            <a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">User Setting</a>
                        </li>

                        <li class="font-semibold text-sm uppercase text-gray-500 mt-4">Attendance</li>
                        <li>
                            <a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">Schedule</a>
                        </li>
                        <li>
                            <a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">Daily Attendance</a>
                        </li>
                        <li>
                            <a href="#" class="block px-3 py-2 rounded hover:bg-blue-50">Sheet Report</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="p-4 border-t">
                <a href="#" class="block px-3 py-2 text-red-500 font-medium hover:bg-red-50 rounded">Keluar</a>
            </div>
        </aside>

        <!-- Konten utama -->
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

            <!-- Form Edit Absensi -->
            <section class="flex-1 overflow-y-auto p-8">
                <div class="bg-white rounded-lg shadow p-6 max-w-4xl mx-auto">
                    <h2 class="text-lg font-semibold text-gray-700 mb-6">Edit Absensi</h2>

                    <form class="space-y-6">
                        <div x-data="{ status: 'izin', showSuccess: false }" class="flex-1 overflow-y-auto p-8">
                            <div class="bg-white rounded-lg shadow p-6 max-w-4xl mx-auto">
                                <h2 class="text-lg font-semibold text-gray-700 mb-6">Edit Absensi</h2>

                                <form @submit.prevent="showSuccess = true; setTimeout(()=>showSuccess=false, 4000)"
                                    class="space-y-6"></form>
                                <!-- Nama -->
                                <div>
                                    <label class="block text-gray-600 font-medium mb-2">Name *</label>
                                    <select
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400">
                                        <option>Fefe Fifi Fufu Fafa</option>
                                    </select>
                                </div>

                                <!-- Tanggal, Clock In, Clock Out -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-gray-600 font-medium mb-2">Tanggal *</label>
                                        <input type="date" value="30 September 2025"
                                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400" />
                                    </div>
                                    <div>
                                        <label class="block text-gray-600 font-medium mb-2">Clock in *</label>
                                        <input type="time" value="09:46"
                                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400" />
                                    </div>
                                    <div>
                                        <label class="block text-gray-600 font-medium mb-2">Clock out *</label>
                                        <input type="time" value="16:19"
                                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400" />
                                    </div>
                                </div>

                                <!-- Shift -->
                                <div>
                                    <label class="block text-gray-600 font-medium mb-2">Shift *</label>
                                    <select
                                        class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400">
                                        <option>Shift-1</option>
                                        <option>Shift-2</option>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-gray-600 font-medium mb-2">Status *</label>
                                    <div class="flex items-center space-x-6">
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="status" value="hadir" x-model="status"
                                                class="text-blue-600" />
                                            <span>Hadir</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="status" value="izin" x-model="status"
                                                class="text-blue-600" />
                                            <span>Izin</span>
                                        </label>
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" name="status" value="absen" x-model="status"
                                                class="text-blue-600" />
                                            <span>Absen</span>
                                        </label>
                                    </div>

                                    <!-- Form izin -->
                                    <textarea x-show="status === 'izin'" placeholder="Deskripsi izin..."
                                        class="w-full mt-3 border border-gray-300 rounded-md p-2 h-24 focus:ring-2 focus:ring-blue-400"></textarea>

                                    <!-- Form absen tambahan -->
                                    <div x-show="status === 'absen'" class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-gray-600 font-medium mb-1">Upload file alasan
                                                *</label>
                                            <input type="file"
                                                class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400" />
                                        </div>

                                        <div>
                                            <label class="block text-gray-600 font-medium mb-1">Jenis Absen *</label>
                                            <select
                                                class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-400">
                                                <option value="">-- Pilih Jenis Absen --</option>
                                                <option value="alfa">Alfa</option>
                                                <option value="sakit">Sakit</option>
                                                <option value="dinas">Dinas Luar</option>
                                            </select>
                                        </div>

                                        <div
                                            class="p-3 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-md">
                                            ⏳ Data absen Anda akan menunggu **approval HRD** sebelum tercatat resmi.
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="flex justify-end space-x-3">
                                    <button type="button"
                                        class="bg-red-700 text-white px-5 py-2 rounded hover:bg-red-800">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="bg-blue-900 text-white px-5 py-2 rounded hover:bg-blue-800">
                                        Edit
                                    </button>
                                </div>

                                <!-- Notifikasi sukses -->
                                <div x-show="showSuccess" x-transition
                                    class="fixed bottom-6 right-6 bg-green-600 text-white px-5 py-3 rounded shadow-lg">
                                    ✅ Data absensi berhasil disimpan! Menunggu approval HRD.
                                </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
