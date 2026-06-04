<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Local Cloud Storage
        </h2>
    </x-slot>

    <div class="py-4 px-4 sm:py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Sidebar - Collapsible on mobile -->
            <div x-data="{ open: false }" class="md:col-span-1">
                <button @click="open = !open" class="md:hidden w-full bg-blue-600 text-white p-3 rounded mb-4 font-semibold">
                    <span x-show="!open">📁 Show Folders</span>
                    <span x-show="open">📁 Hide Folders</span>
                </button>
                <div x-show="open" @click.outside="open = false" class="md:block bg-white p-4 shadow rounded">
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
            </div>

            <div class="md:col-span-3 bg-white p-4 shadow rounded">

                <!-- Create Folder Form -->
                <div class="mb-4">
                    <form action="{{ route('folders.store') }}" 
                     method="POST"
                     class="flex flex-col sm:flex-row gap-2">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $currentFolder?->id }}">
                        <input name="name" placeholder="New folder name" class="flex-1 border rounded p-2 text-sm sm:text-base">
                        <button class="text-blue-600 px-3 py-2 rounded bg-blue-100 whitespace-nowrap">
                            Create Folder
                        </button>
                    </form>
                </div>

                <!-- Upload Form -->
                <div class="mb-4">
                    <form action="{{ route('files.upload') }}"
                     method="POST"
                     enctype="multipart/form-data"
                     class="flex flex-col sm:flex-row gap-2">
                        @csrf
                        <input type="hidden" name="folder_id" value="{{ $currentFolder?->id }}">
                        <input type="file" name="file" required class="flex-1 text-sm">
                        <button class="bg-green-600 text-white px-3 py-2 rounded whitespace-nowrap">
                            Upload
                        </button>
                    </form>
                </div>

                <h3 class="font-bold mb-4 text-lg">
                    Current: {{ $currentFolder?->name ?? 'Root' }}
                </h3>

                <!-- Responsive Table Container -->
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <table class="w-full border text-sm sm:text-base">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 text-left">Name</th>
                                <th class="p-2 hidden sm:table-cell">Type</th>
                                <th class="p-2 hidden sm:table-cell">Size</th>
                                <th class="p-2 hidden lg:table-cell">Version</th>
                                <th class="p-2">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach ($folders as $folder)
                            <tr class="border-t">
                                <td class="p-2">
                                    <a href="{{ route('cloud.index', ['folder' => $folder->id]) }}" class="text-blue-600 hover:underline">
                                        📁 {{ $folder->name }}
                                    </a>
                                </td>
                                <td class="p-2 text-center hidden sm:table-cell text-xs">Folder</td>
                                <td class="p-2 text-center hidden sm:table-cell text-xs">-</td>
                                <td class="p-2 text-center hidden lg:table-cell text-xs">-</td>
                                <td class="p-2 text-center">
                                    <form action="{{ route('folders.destroy', $folder->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this folder?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800 text-sm sm:text-base">🗑</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        @foreach ($files as $file)
                            <tr class="border-t">
                                <td class="p-2">
                                    <div class="break-words">
                                        📄 <span class="font-medium">{{ $file->original_name }}</span>
                                        <div class="text-xs text-gray-500 sm:hidden">
                                            {{ $file->mime_type }} • {{ number_format($file->size / 1024, 2) }} KB
                                        </div>
                                    </div>
                                </td>
                                <td class="p-2 text-center hidden sm:table-cell text-xs">
                                    {{ $file->mime_type }}
                                </td>
                                <td class="p-2 text-center hidden sm:table-cell text-xs">
                                    {{ number_format($file->size / 1024, 2) }} KB
                                </td>
                                <td class="p-2 text-center hidden lg:table-cell text-xs">
                                    v{{ $file->version }}
                                </td>
                                <td class="p-2">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:gap-1 items-center justify-center">
                                        <a 
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           href="{{ route('files.preview', $file->id) }}"
                                           class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm">
                                            👁
                                        </a>
                                        <a href="{{ route('files.download', $file->id) }}"
                                           class="text-green-600 hover:text-green-800 text-xs sm:text-sm">
                                            ⬇
                                        </a>
                                        <a href="{{ route('files.versions', $file->id) }}"
                                           class="text-purple-600 hover:text-purple-800 text-xs sm:text-sm hidden lg:inline">
                                            📋
                                        </a>
                                        <form action="{{ route('files.destroy', $file->id) }}" 
                                             method="POST"
                                             class="inline"
                                             onsubmit="return confirm('Delete this file?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-800 text-xs sm:text-sm">🗑</button>
                                        </form>
                                        <div class="hidden lg:block">
                                            <form action="{{ route('files.replace', $file->id) }}" 
                                                 enctype="multipart/form-data" 
                                                 class="inline"
                                                 x-data="{ 
                                                     selectedFile: null,
                                                     handleFileSelect(e) {
                                                         this.selectedFile = e.target.files[0]?.name;
                                                         if (confirm('Replace ' + this.selectedFile + '?')) {
                                                             e.target.form.submit();
                                                         }
                                                     }
                                                 }">
                                                @csrf
                                                <input type="file" 
                                                       name="file" 
                                                       required
                                                       class="hidden"
                                                       @change="handleFileSelect($event)"
                                                       id="replace-{{ $file->id }}">
                                                <label for="replace-{{ $file->id }}" class="text-orange-600 hover:text-orange-800 text-xs sm:text-sm cursor-pointer">
                                                    🔄
                                                </label>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>