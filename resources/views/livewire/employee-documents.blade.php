<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">My Documents</h2>

    <div class="mb-4">
        <input type="text" wire:model.live="search" placeholder="Search documents..."
            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-gray-50 shadow-md rounded-lg">
            <thead>
                <tr class="bg-blue-600 text-white text-left">
                    <th class="px-4 py-2 cursor-pointer" wire:click.prevent="sortByField('document_name')">Document Name</th>
                    <th class="px-4 py-2 cursor-pointer" wire:click.prevent="sortByField('category_id')">Category</th>
                    <th class="px-4 py-2 cursor-pointer" wire:click.prevent="sortByField('expiry_date')">Expiry Date</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $document)
                    <tr class="border-b hover:bg-gray-100 transition">
                        <td class="px-4 py-2">{{ $document->document_name }}</td>
                        <td class="px-4 py-2">{{ $document->category->category_name }}</td>
                        <td class="px-4 py-2">{{ $document->expiry_date ?? 'No Expiry' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                                class="px-3 py-1 bg-transparent text-theme-blue font-bold rounded hover:text-blue-600 transition">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-600">No documents found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $documents->links() }}
    </div>
</div>
