<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Kirim Notifikasi</h1>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                        <select name="template" id="template" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Pilih Template (Opsional)</option>
                            @foreach($templates as $key => $template)
                                <option value="{{ $key }}">{{ ucfirst($key) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-gray-700 text-sm font-bold mb-2">Pesan:</label>
                        <textarea name="message" id="message" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Kirim Notifikasi
                        </button>
                        <a href="{{ route('super_admin.dashboard') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
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
