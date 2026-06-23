<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('औषधि व्यवस्थापन') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- खोज, फिल्टर, Add बटन -->
                <div class="flex flex-wrap justify-between items-center mb-4 gap-2">
                    <form method="GET" class="flex flex-wrap gap-2">
                        <input type="text" name="search" placeholder="खोज्नुहोस्..." value="{{ request('search') }}" class="rounded border-gray-300">
                        <select name="category_id" class="rounded border-gray-300">
                            <option value="">सबै श्रेणी</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="rounded border-gray-300">
                            <option value="">सबै स्थिति</option>
                            <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>स्टकमा</option>
                            <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>कम स्टक</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>म्याद सकिएको</option>
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">फिल्टर</button>
                    </form>
                    <div class="flex gap-2">
                        <a href="{{ route('medicines.export') }}" class="bg-green-500 text-white px-4 py-2 rounded">Export</a>
                        <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded">+ नयाँ</button>
                    </div>
                </div>

                <!-- सफलता सन्देश -->
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- तालिका -->
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th>नाम</th>
                            <th>श्रेणी</th>
                            <th>आपूर्तिकर्ता</th>
                            <th>खरिद मूल्य</th>
                            <th>बिक्री मूल्य</th>
                            <th>मात्रा</th>
                            <th>म्याद</th>
                            <th>स्थिति</th>
                            <th>कार्य</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicines as $med)
                        <tr>
                            <td>{{ $med->name }}</td>
                            <td>{{ $med->category?->name }}</td>
                            <td>{{ $med->supplier?->name }}</td>
                            <td>Rs. {{ number_format($med->purchase_price, 2) }}</td>
                            <td>Rs. {{ number_format($med->selling_price, 2) }}</td>
                            <td>{{ $med->quantity }}</td>
                            <td>{{ $med->expiry_date }}</td>
                            <td>
                                @if($med->status == 'in_stock')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">स्टकमा</span>
                                @elseif($med->status == 'low_stock')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">कम स्टक</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded">म्याद सकिएको</span>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="text-blue-600">सम्पादन</a>
                                <form action="{{ route('medicines.destroy', $med) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600" onclick="return confirm('हटाउनुहुन्छ?')">हटाउनु</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $medicines->links() }}
            </div>
        </div>
    </div>
</x-app-layout>