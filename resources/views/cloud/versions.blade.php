<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Versions: {{ $file->original_name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8 bg-white p-4 shadow rounded">
        <a href="{{ route('cloud.index', ['folder' => 
        $file->folder_id]) }}" class="text-blue-600">
            ← Back
        </a>

        <h3 class="font-bold mt-4 mb-2">Current Version</h3>
        <p>Version: v{{ $file->version }}</p>
        <p>Size: {{ number_format($file->size / 1024, 2) }} KB</p>

        <h3 class="font-bold mt-4 mb-2">Old Versions</h3>

        @foreach ($versions as $version)
            <div class="border p-2 mb-2">
                v{{ $version->version }}
                —
                {{ number_format($version->size / 1024, 2) }} KB
                —
                {{ $version->created_at }}
            </div>
        @endforeach
    </div>
</x-app-layout>