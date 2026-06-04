<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Trash
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8 bg-white p-4 shadow rounded">
    <a href="{{ route('cloud.index')     
    }}" class="text-blue-600">
            ← Back to cloud
        </a>

        <h3 class="font-bold mt-4 mb-2">Deleted Files</h3>

        @foreach ($files as $file)
            <div class="border p-2 mb-2">
                📄 {{ $file->original_name }}
                <form action="{{ route('trash.files.restore', $file->id) }}" class="inline">
                    @csrf
                    <button class="text-green-600 ml-3">Restore</button>
                </form>
                <form action="{{ route('trash.files.force-delete', $file->

                id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 ml-3">Delete forever</button>
                </form>
            </div>
        @endforeach

        <h3 class="font-bold mt-4 mb-2">Deleted Folders</h3>

        @foreach ($folders as $folder)
            <div class="border p-2 mb-2">
                📁 {{ $folder->name }}
                <form action="{{ route('trash.folders.restore', $folder->
                id) }}" class="inline">
                    @csrf
                    <button class="text-green-600 ml-3">Restore</button>
                </form>
            </div>
        @endforeach
    </div>
</x-app-layout>