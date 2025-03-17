<x-layout>
    <div class="px-4 py-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between bg-gradient-to-r from-indigo-600 to-blue-500 text-white shadow-md rounded-lg px-6 py-4 mb-6">
            <div class="flex items-center gap-2">
                <button class="text-xl hover:text-gray-200 transition-colors duration-300">&larr;</button>
                <span class="flex items-center text-xl font-semibold">
                    Recently Deleted
                </span>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="flex items-center justify-between mb-4">
            <select class="bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded leading-tight focus:outline-none focus:shadow-outline">
                <option value="subject">Subject</option>
                <option value="date">Topic</option>
            </select>
            <button class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                Select All
            </button>
        </div>

        <!-- Deleted Items Section -->
        <div class="bg-white shadow-md rounded-lg">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 text-left text-sm leading-normal">
                        <th class="py-3 px-4 font-semibold">Subject</th>
                        <th class="py-3 px-4 font-semibold">Date deleted</th>
                        <th class="py-3 px-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-4">English</td>
                        <td class="py-3 px-4">1 min ago</td>
                        <td class="py-3 px-4">
                            <input type="checkbox" class="form-checkbox">
                        </td>
                    </tr>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-4">Science</td>
                        <td class="py-3 px-4">1 min ago</td>
                        <td class="py-3 px-4">
                            <input type="checkbox" class="form-checkbox">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Restore Button -->
        <div class="flex justify-end mt-4">
            <button class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                Restore
            </button>
        </div>
    </div>
</x-layout>
