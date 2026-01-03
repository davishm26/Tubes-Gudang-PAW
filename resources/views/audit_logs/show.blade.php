<x-app-layout>
    @php
        $isDemo = session('is_demo', false) || session('demo_mode', false);
        $isArray = is_array($log);

        // Helper function untuk ambil nama entity (hanya untuk real mode)
        function getEntityName($log) {
            if (is_array($log)) {
                return $log['entity_name'] ?? '[Unknown]';
            }

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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Riwayat Audit') }}
                @if($isDemo)
                    <span class="ml-2 px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Demo Mode</span>
                @endif
            </h2>
            <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Basic Information --}}
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Informasi Dasar</h3>

                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Waktu</label>
                                    @if($isArray)
                                        <p class="text-sm text-gray-900">{{ $log['created_at'] ?? '-' }}</p>
                                    @else
                                        <p class="text-sm text-gray-900">{{ $log->created_at->format('Y-m-d H:i:s') }}</p>
                                        <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Pengguna</label>
                                    @if($isArray)
                                        <p class="text-sm text-gray-900">{{ $log['user_name'] ?? 'System' }}</p>
                                    @else
                                        <p class="text-sm text-gray-900">{{ $log->user?->name ?? 'System' }}</p>
                                        @if($log->user)
                                            <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                                        @endif
                                    @endif
                                </div>

                                @if(!$isDemo)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Perusahaan</label>
                                    <p class="text-sm text-gray-900">{{ $log->company?->name ?? '-' }}</p>
                                </div>
                                @endif

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Aksi</label>
                                    <p>
                                        @php $action = $isArray ? ($log['action'] ?? '') : $log->action; @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $action === 'created' || $action === 'create' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $action === 'updated' || $action === 'update' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $action === 'deleted' || $action === 'delete' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $action === 'viewed' || $action === 'view' ? 'bg-gray-100 text-gray-800' : '' }}">
                                            {{ ucfirst($action) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Entity Information --}}
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Informasi Entitas</h3>

                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Tipe Entitas</label>
                                    @if($isArray)
                                        <p class="text-sm text-gray-900">{{ $log['entity'] ?? '[Unknown]' }}</p>
                                    @else
                                        <p class="text-sm text-gray-900">{{ class_basename($log->entity_type) }}</p>
                                        <p class="text-xs text-gray-500">{{ $log->entity_type }}</p>
                                    @endif
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nama Entitas</label>
                                    <p class="text-sm text-gray-900">{{ $isArray ? ($log['entity_name'] ?? '[Unknown]') : ($log->entity_name ?? '[Unknown]') }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">ID Entitas</label>
                                    <p class="text-sm text-gray-900">#{{ $isArray ? ($log['entity_id'] ?? '-') : $log->entity_id }}</p>
                                </div>

                                @if(!$isDemo)
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Alamat IP</label>
                                    <p class="text-sm text-gray-900">{{ $log->ip_address ?? '-' }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-500">Agen Pengguna</label>
                                    <p class="text-xs text-gray-900 break-all">{{ $log->user_agent ?? '-' }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Changes Section --}}
                    @php
                        $changes = $isArray ? (isset($log['old_values']) || isset($log['new_values']) ? ['changes' => ['old' => $log['old_values'] ?? null, 'new' => $log['new_values'] ?? null]] : null) : $log->changes;
                    @endphp
                    @if($changes)
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Perubahan</h3>

                            <div class="bg-gray-50 rounded-lg p-4">
                                @if($isArray && isset($log['old_values']) && isset($log['new_values']))
                                    <div class="mb-4 last:mb-0 pb-4 last:pb-0 border-b last:border-b-0">
                                        <div class="font-semibold text-gray-700 mb-2">Perubahan Data</div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="bg-red-50 p-3 rounded border border-red-200">
                                                <div class="text-xs font-medium text-red-700 mb-1">Nilai Lama</div>
                                                <div class="text-sm text-red-900">
                                                    @if(is_null($log['old_values']))
                                                        <span class="italic text-gray-400">null</span>
                                                    @else
                                                        {{ is_array($log['old_values']) ? json_encode($log['old_values']) : $log['old_values'] }}
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="bg-green-50 p-3 rounded border border-green-200">
                                                <div class="text-xs font-medium text-green-700 mb-1">Nilai Baru</div>
                                                <div class="text-sm text-green-900">
                                                    @if(is_null($log['new_values']))
                                                        <span class="italic text-gray-400">null</span>
                                                    @else
                                                        {{ is_array($log['new_values']) ? json_encode($log['new_values']) : $log['new_values'] }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif(!$isArray && $log->changes)
                                @foreach($log->changes as $key => $value)
                                    <div class="mb-4 last:mb-0 pb-4 last:pb-0 border-b last:border-b-0">
                                        <div class="font-semibold text-gray-700 mb-2">{{ ucfirst(str_replace('_', ' ', $key)) }}</div>

                                        @if(is_array($value) && isset($value['old']) && isset($value['new']))
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="bg-red-50 p-3 rounded border border-red-200">
                                                    <div class="text-xs font-medium text-red-700 mb-1">Nilai Lama</div>
                                                    <div class="text-sm text-red-900">
                                                        @if(is_null($value['old']))
                                                            <span class="italic text-gray-400">null</span>
                                                        @else
                                                            {{ is_array($value['old']) ? json_encode($value['old']) : $value['old'] }}
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="bg-green-50 p-3 rounded border border-green-200">
                                                    <div class="text-xs font-medium text-green-700 mb-1">Nilai Baru</div>
                                                    <div class="text-sm text-green-900">
                                                        @if(is_null($value['new']))
                                                            <span class="italic text-gray-400">null</span>
                                                        @else
                                                            {{ is_array($value['new']) ? json_encode($value['new']) : $value['new'] }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-blue-50 p-3 rounded border border-blue-200">
                                                <pre class="text-xs text-blue-900 overflow-auto">{{ is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Perubahan</h3>
                            <p class="text-sm text-gray-500 italic">Tidak ada detail perubahan yang dicatat.</p>
                        </div>
                    @endif

                    {{-- Additional Metadata --}}
                    @if(!$isDemo)
                    <div class="mt-8 pt-6 border-t">
                        <div class="grid grid-cols-2 gap-4 text-xs text-gray-500">
                            <div>
                                <span class="font-medium">ID Log:</span> {{ $log->id }}
                            </div>
                            <div>
                                <span class="font-medium">ID Perusahaan:</span> {{ $log->company_id ?? '-' }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
