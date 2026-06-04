<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Local Cloud Storage
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-4 gap-4">

            <div class="bg-white p-4 shadow rounded">
                <h3 class="font-bold mb-3">Folders</h3>
                <a href="{{ route('cloud.index')

                 }}" class="block mb-2 text-blue-600">
                    Root
                </a>

                @foreach ($tree as $folder)
                    <div>
                        <a href="{{ route('cloud.index', ['folder' => $folder->id]) }}" class="block mb-2 {{ $currentFolder && $currentFolder->id == $folder->id ? 'font-bold text-blue-800' : 'text-blue-600' }}">
                            {{ str_repeat('📁', $folder->depth) }} {{ $folder->name }}
                        </a>
                    </div>
                @endforeach

                <hr class="my-4">
                <a href="{{ route('trash.index')

                 }}" class="text-red-600">
                    🗑 Trash
                </a>

                @if(auth()->user()->is_admin)
                    <br>
                    <a href="{{ route('dashboard')
                     }}" class="text-purple-600">
                        ⚙ Admin
                    </a>
                @endif
            </div>

            <div class="col-span-3 bg-white p-4 shadow rounded">

                <div class="mb-4">
                    <form action="{{ route('folders.store')

                    
                     }}" 
                     method="POST"
                     class="inline-block">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $currentFolder?->id }}">
                        <input name="name" placeholder="New folder name" class="border rounded p-2">
                        <button class="text-blue-600 px-3 py-2 rounded bg-blue-100">
                            Create Folder
                        </button>
                    </form>
                </div>

                <div class="mb-4">
                    <form action="{{ route('files.upload')
                    
                     }}"
                     method="POST"
                     enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="folder_id" value="{{ $currentFolder?->id }}">
                        <input type="file" name="file" required>
                        <button class="bg-green-600 text-white px-3 py-2 rounded">
                            Upload
                        </button>
                    </form>
                </div>

                <h3 class="font-bold mb-2">
                    Current:
                    {{ $currentFolder?->name ?? 'Root' }}
                </h3>

                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 text-left">Name</th>
                            <th class="p-2">Type</th>
                            <th class="p-2">Size</th>
                            <th class="p-2">Version</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($folders as $folder)
                            <tr class="border-t">
                                <td class="p-2">
                                    <a href="{{ route('cloud.index', ['folder' => $folder->id]) }}" class="text-blue-600">
                                        📁 {{ $folder->name }}
                                    </a>
                                </td>
                                <td class="p-2 text-center">Folder</td>
                                <td class="p-2 text-center">-</td>
                                <td class="p-2 text-center">-</td>
                                <td class="p-2 text-center">
                                    <form action="{{ route('folders.destroy', $folder->id)
                                     }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600">Trash</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        @foreach ($files as $file)
                            <tr class="border-t">
                                <td class="p-2">
                                    📄 {{ $file->original_name }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ $file->mime_type }}
                                </td>
                                <td class="p-2 text-center">
                                    {{ number_format($file->size / 1024, 2) }} KB
                                </td>
                                <td class="p-2 text-center">
                                    v{{ $file->version }}
                                </td>
                                <td class="p-2 text-center space-x-2">
                                    <a 
                                       target="_blank"
   rel="noopener noreferrer"
                                    href="{{ route('files.preview', $file->id)
                                     }}">
                                        Preview
                                    </a>
                                    <a href="{{ route('files.download', $file->id)
                                     }}">
                                        Download
                                    </a>
                                    <a href="{{ route('files.versions', $file->id)

                                     }}">
                                        Versions
                                    </a>
                                    <form  action="{{ route('files.destroy', $file->id)

                                     }}" 
                                     method="POST"
                                     class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600" >Trash</button>
                                    </form>
                                    <form action="{{ route('files.replace', $file->id)

                                     }}" enctype="multipart/form-data" class="mt-2">
                                        @csrf
                                        <input type="file" name="file" required>
                                        <button class="text-orange-600">Replace</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>