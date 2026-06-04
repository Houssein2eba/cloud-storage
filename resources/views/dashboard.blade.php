<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 shadow rounded">
                Users: {{ $usersCount }}
            </div>

            <div class="bg-white p-4 shadow rounded">
                Files: {{ $filesCount }}
            </div>

            <div class="bg-white p-4 shadow rounded">
                Folders: {{ $foldersCount }}
            </div>

            <div class="bg-white p-4 shadow rounded">
                Storage:
                {{ number_format($storageUsed / 1024 / 1024, 2) }} MB
            </div>
        </div>

        <div class="bg-white p-4 shadow rounded">
            <h3 class="font-bold mb-3">Users</h3>

            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 text-left">Name</th>
                        <th class="p-2 text-left">Email</th>
                        <th class="p-2">Admin</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr class="border-t">
                            <td class="p-2">{{ $user->name }}</td>
                            <td class="p-2">{{ $user->email }}</td>
                            <td class="p-2 text-center">
                                {{ $user->is_admin ? 'Yes' : 'No' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>