<x-app-layout>
    <x-slot name="title">Riwayat Audit - StockMaster</x-slot>
    @php
        // Check demo mode
        $isDemo = session('is_demo', false) || session('demo_mode', false);

        // Helper function untuk ambil nama entity (hanya untuk real mode)
        function getEntityName($log)
        {
            $entityType = class_basename($log->entity_type);
            $entityId = $log->entity_id;

            try {
                // Map class ke model
                $modelClass = 'App\\Models\\' . $entityType;
                if (class_exists($modelClass)) {
                    $entity = $modelClass::find($entityId);
                    if ($entity) {
                        // Return nama/identitas entity
                        if (isset($entity->name)) {
                            return $entity->name;
                        } elseif (isset($entity->title)) {
                            return $entity->title;
                        } elseif (in_array($entityType, ['InventoryIn','InventoryOut'])) {
                            try {
                                return optional($entity->product)->name ?? (\App\Models\Product::find($entity->product_id)->name ?? null);
                            } catch (\Exception $e) {
                                // ignore
                            }
                        } elseif ($entityType === 'User') {
                            return $entity->name . ' (' . $entity->email . ')';
                        }
                    } else {
                        // Entity sudah dihapus
                        return '[Deleted ' . $entityType . ']';
                    }
                }
            } catch (\Exception $e) {
                // Silent fail
            }

            return '[Unknown]';
        }
    @endphp
    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-2xl text-white leading-tight">
                    {{ __('Riwayat Audit') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Filter Section --}}
                    <form method="GET" action="{{ route('audit-logs.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 mb-4">
                            {{-- Entity Type Filter --}}
                            <div>
                                <label for="entity_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe
                                    Entitas</label>
                                <select name="entity_type" id="entity_type"
                                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#1F8F6A] focus:ring-[#1F8F6A] sm:text-sm px-3 py-2">
                                    <option value="">Semua Tipe</option>
                                    @foreach ($entityTypes as $type)
                                        <option value="{{ $type }}"
                                            {{ request('entity_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Action Filter --}}
                            <div>
                                <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
                                <select name="action" id="action"
                                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#1F8F6A] focus:ring-[#1F8F6A] sm:text-sm px-3 py-2">
                                    <option value="">Semua Aksi</option>
                                    @foreach ($actions as $action)
                                        <option value="{{ $action }}"
                                            {{ request('action') == $action ? 'selected' : '' }}>
                                            {{ ucfirst($action) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Right Column: User (admin) or Company (super admin) --}}
                            @if(!$isDemo && Auth::check() && Auth::user()->role === 'super_admin' && isset($companies) && count($companies) > 0)
                                <div>
                                    <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Perusahaan</label>
                                    <select name="company_id" id="company_id" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#1F8F6A] focus:ring-[#1F8F6A] sm:text-sm px-3 py-2">
                                        <option value="">Semua Perusahaan</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Pengguna</label>
                                    <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#1F8F6A] focus:ring-[#1F8F6A] sm:text-sm px-3 py-2">
                                        <option value="">Semua Pengguna</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4 mb-4">
                            {{-- Date From --}}
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                    Mulai</label>
                                <input type="date" name="date_from" id="date_from"
                                    value="{{ request('date_from') }}"
                                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#1F8F6A] focus:ring-[#1F8F6A] sm:text-sm px-3 py-2">
                            </div>

                            {{-- Date To --}}
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Tanggal
                                    Akhir</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#1F8F6A] focus:ring-[#1F8F6A] sm:text-sm px-3 py-2">
                            </div>

                            {{-- Search --}}
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    placeholder="Cari..."
                                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:border-[#1F8F6A] focus:ring-[#1F8F6A] sm:text-sm px-3 py-2">
                            </div>
                        </div>



                        <div class="flex gap-6 mb-6">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-[#1F8F6A] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#166B50] focus:bg-[#166B50] active:bg-[#145237] focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-2 transition ease-in-out duration-150">
                                Terapkan
                            </button>
                            <a href="{{ route('audit-logs.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-[#E9F6F1] border border-[#1F8F6A] rounded-md font-semibold text-xs text-[#1F8F6A] uppercase tracking-widest shadow-sm hover:bg-[#D1EDE5] focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Atur Ulang
                                </a>
                        </div>
                    </form>

                    {{-- Tabel Riwayat Audit --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                            <!-- TABLE HEADER -->
                            <thead class="bg-[#E9F6F1] border-b border-[#1F8F6A]/20">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wide">Waktu</th>
                                    @if(Auth::check() && Auth::user()->role === 'super_admin')
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wide">Perusahaan</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wide">Entitas</th>
                                    @else
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wide">Pengguna</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wide">Entitas</th>
                                    @endif
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wide">Aksi</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wide">Perubahan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-[#1F8F6A] uppercase tracking-wide">Alamat IP</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-[#1F8F6A] uppercase tracking-wide">Tindakan</th>
                                </tr>
                            </thead>

                            <!-- TABLE BODY -->
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50 transition">
                                        <!-- WAKTU -->
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $log->created_at->setTimezone(config('app.timezone'))->format('d/m/Y H:i') }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $log->created_at->setTimezone(config('app.timezone'))->locale(config('app.locale'))->diffForHumans() }}
                                            </div>
                                        </td>
                                        @if(Auth::check() && Auth::user()->role === 'super_admin')
                                            <!-- PERUSAHAAN (super admin) -->
                                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->company?->name ?? '-' }}</td>
                                            <!-- ENTITAS (super admin) -->
                                            <td class="px-6 py-5">
                                                <div class="text-sm font-semibold text-gray-900">{{ class_basename($log->entity_type) }}</div>
                                                <div class="text-xs text-gray-500 mt-1">{{ $log->entity_name ?? getEntityName($log) ?? '[Unknown]' }}</div>
                                            </td>
                                        @else
                                            <!-- PENGGUNA (admin) -->
                                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $log->user?->name ?? '-' }}
                                            </td>
                                            <!-- ENTITAS (admin) -->
                                            <td class="px-6 py-5">
                                                <div class="text-sm font-semibold text-gray-900">{{ class_basename($log->entity_type) }}</div>
                                                <div class="text-xs text-gray-500 mt-1">{{ $log->entity_name ?? getEntityName($log) ?? '[Unknown]' }}</div>
                                            </td>
                                        @endif

                                        <!-- AKSI -->
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            @php
                                                $actionStyles = [
                                                    'created' => 'bg-green-100 text-green-700 border border-green-200',
                                                    'updated' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                                    'deleted' => 'bg-red-100 text-red-700 border border-red-200',
                                                    'viewed' => 'bg-gray-100 text-gray-700 border border-gray-200',
                                                ];
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $actionStyles[$log->action] ?? 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                                {{ strtoupper($log->action) }}
                                            </span>
                                        </td>

                                        <!-- PERUBAHAN -->
                                        <td class="px-6 py-5 text-sm">
                                            @if ($log->changes)
                                                <button onclick="showChanges({{ json_encode($log->changes) }})"
                                                    class="text-[#1F8F6A] font-medium hover:underline">
                                                    Lihat Perubahan
                                                </button>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>

                                        <!-- IP -->
                                        <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-600">
                                            {{ $log->ip_address ?? '-' }}
                                        </td>

                                        <!-- TINDAKAN -->
                                        <td class="px-6 py-5 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('audit-logs.show', $log->id) }}"
                                                class="text-[#1F8F6A] hover:text-[#0F4C37] font-semibold">
                                                DETAIL
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                                            Tidak ada riwayat audit.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>


                    {{-- Pagination --}}
                    @if (!$isDemo)
                        <div class="mt-4">
                            {{ $logs->links('pagination::tailwind') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk menampilkan changes --}}
    <div id="changesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detail Perubahan</h3>
                <button onclick="closeChangesModal()" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            <div id="changesContent" class="mt-2">
                <!-- Changes will be inserted here -->
            </div>
            <div class="mt-4 flex justify-end">
                <button onclick="closeChangesModal()"
                    class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showChanges(changes) {
                const modal = document.getElementById('changesModal');
                const content = document.getElementById('changesContent');

                let html = '<div class="space-y-3">';

                for (const [key, value] of Object.entries(changes)) {
                    html += '<div class="border-b pb-3">';
                    html += `<div class="font-semibold text-gray-700">${key}</div>`;

                    if (value.old !== undefined && value.new !== undefined) {
                        html += '<div class="grid grid-cols-2 gap-4 mt-2">';
                        html += '<div><span class="text-xs text-gray-500">Lama:</span><div class="text-sm text-red-600">' + (
                            value.old ?? 'null') + '</div></div>';
                        html += '<div><span class="text-xs text-gray-500">Baru:</span><div class="text-sm text-green-600">' + (
                            value.new ?? 'null') + '</div></div>';
                        html += '</div>';
                    } else {
                        html += '<pre class="text-sm bg-gray-50 p-2 rounded mt-2 overflow-auto">' + JSON.stringify(value, null,
                            2) + '</pre>';
                    }

                    html += '</div>';
                }

                html += '</div>';
                content.innerHTML = html;
                modal.classList.remove('hidden');
            }

            function closeChangesModal() {
                document.getElementById('changesModal').classList.add('hidden');
            }
        </script>
    @endpush
</x-app-layout>
