<x-app-layout>
    <x-slot name="title">Kirim Notifikasi - StockMaster</x-slot>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-[#1F8F6A] to-[#166B50] pt-20 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-semibold leading-tight text-white">Kirim Notifikasi</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('super_admin.notifications.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="recipient_id" class="block text-gray-700 text-sm font-bold mb-2">Penerima:</label>
                        <select name="recipient_id" id="recipient_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Pilih Admin Perusahaan</option>
                            @foreach($companies as $company)
                                @if($company->company)
                                    <option value="{{ $company->id }}" @selected($selectedRecipient && $selectedRecipient->id === $company->id)>
                                        {{ $company->company->name }} - {{ $company->name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="template" class="block text-gray-700 text-sm font-bold mb-2">Template:</label>
                        @php
                            $templateLabels = [
                                'maintenance' => 'Pemeliharaan',
                                'update' => 'Pembaruan Sistem',
                                'reminder' => 'Pengingat',
                                'subscription_expiry' => 'Masa Langganan Hampir Habis',
                                'announcement' => 'Pengumuman',
                            ];
                        @endphp
                        <select name="template" id="template" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Pilih Template (Opsional)</option>
                            @foreach($templates as $key => $template)
                                <option value="{{ $key }}">{{ $templateLabels[$key] ?? ucwords(str_replace('_', ' ', (string) $key)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Pesan:</label>
                        <textarea name="message" id="message" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <button type="submit" class="bg-[#1F8F6A] hover:bg-[#166B50] text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-[#1F8F6A] focus:ring-offset-1">
                            Kirim Notifikasi
                        </button>
                        <a href="{{ route('super_admin.dashboard') }}" class="inline-block align-baseline font-bold text-sm text-[#1F8F6A] hover:text-[#0F4C37]">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('template').addEventListener('change', function() {
            const selectedTemplate = this.value;
            const messageTextarea = document.getElementById('message');
            const templates = @json($templates);

            if (selectedTemplate && templates[selectedTemplate]) {
                messageTextarea.value = templates[selectedTemplate];
            }
        });
    </script>
</x-app-layout>






