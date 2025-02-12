<x-admin_layout>
    <main>
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2">Id</th>
                    <th class="py-2">First Name</th>
                    <th class="py-2">Last Name</th>
                    <th class="py-2">Email</th>
                    <th class="py-2">Date Created</th>
                    <th class="py-2">Last Login</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                    <tr>
                        <td class="border px-4 py-2">{{ $admin->admin_id }}</td>
                        <td class="border px-4 py-2">{{ $admin->firstname }}</td>
                        <td class="border px-4 py-2">{{ $admin->lastname }}</td>
                        <td class="border px-4 py-2">{{ $admin->email }}</td>
                        <td class="border px-4 py-2">{{ $admin->created_at }}</td>
                        <td class="border px-4 py-2">{{ $admin->last_login }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>
</x-admin_layout>