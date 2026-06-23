<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- स्ट्याटिस्टिक्स कार्डहरू -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600">कुल औषधिहरू</div>
                    <div class="text-2xl font-bold">{{ $totalMedicines }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600">श्रेणीहरू</div>
                    <div class="text-2xl font-bold">{{ $totalCategories }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600">आपूर्तिकर्ताहरू</div>
                    <div class="text-2xl font-bold">{{ $totalSuppliers }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600">कम स्टक ( < ३० )</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $lowStockMedicines }}</div>
                </div>
            </div>

            <!-- म्याद सकिएको चेतावनी -->
            @if($expiredMedicines > 0)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">चेतावनी!</p>
                    <p>{{ $expiredMedicines }} वटा औषधिको म्याद सकिएको छ। कृपया तुरुन्त कारबाही गर्नुहोस्।</p>
                </div>
            @endif

            <!-- द्रुत पहुँच लिङ्कहरू -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('medicines.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded text-center">
                    औषधि व्यवस्थापन
                </a>
                <a href="#" class="bg-green-500 hover:bg-green-700 text-white font-bold py-4 px-6 rounded text-center">
                    बिक्री (POS)
                </a>
                <a href="#" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded text-center">
                    रिपोर्टहरू
                </a>
            </div>
        </div>
    </div>
</x-app-layout>