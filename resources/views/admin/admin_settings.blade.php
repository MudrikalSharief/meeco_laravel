<x-admin_layout>
    <main>
        <div class="max-w-3xl mx-auto p-12 bg-white rounded-lg shadow-sm h-lscreen min-w-full container">
            <h1 class="text-2xl font-bold text-gray-900 mb-8">Settings</h1>
            <section class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Security</h2>
                <div class="space-y-2">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-700">Two-Factor Authentication</h3>
                            <p class="text-sm text-gray-500">Enable two-factor authentication for admins</p>
                        </div>
                        <button id = 'authBtn' class="relative inline-flex h-6 w-11 items-center rounded-full">
                            <span class="sr-only">Enable two-factor authentication</span>
                            <span id = 'toggle' class="inline-block h-4 w-4 transform rounded-full bg-white"></span>
                        </button>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class = 'flex w-full justify-between'>
                        <h3 class="font-medium text-gray-700">Account Passwords</h3>
                            <button id = 'editAccountPasswordsBtn' class="p-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                                Edit
                            </button>
                    </div>
                   <!--  Account Passwords Modal -->
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 text-gray-600 font-medium"></th>
                                <th class="text-center py-3 text-gray-600 font-medium">Users</th>
                                <th class="text-center py-3 text-gray-600 font-medium">Admin</th>
                            </tr>
                        </thead>
                        <tbody id = 'APtable'>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-600">Minimum Characters</td>
                                <td class="text-center py-3">
                                    <p>8</p>
                                    <form class = 'flex justify-center account-passwords hidden'>
                                        <div class="w-auto max-w-24">
                                            <input 
                                                type="number" 
                                                class="w-full text-center focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            >
                                        </div>
                                        <button id = 'UserMinCharBtn' type = 'submit' class = 'hidden'></button>
                                    </form>
                                </td>
                                <td class="text-center py-3">
                                    <p>8</p>
                                    <form class = 'flex justify-center account-passwords hidden'>
                                        <div class="w-auto max-w-24">
                                            <input 
                                                type="number" 
                                                class="text-center focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            >
                                        </div>
                                        <button id = 'AdminMinCharBtn' type = 'submit' class = 'hidden'></button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-600">Minimum Special Characters</td>
                                <td class="text-center py-3">
                                    <p>2</p>
                                    <form class = 'flex justify-center account-passwords hidden'>
                                        <div class="w-auto max-w-24">
                                            <input 
                                                type="number" 
                                                class="text-center focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            >
                                        </div>
                                        <button id = 'UserMinSpCharBtn' type = 'submit' class = 'hidden'></button>
                                    </form>
                                </td>
                                <td class="text-center py-3">
                                    <p>1</p>
                                    <form class = 'flex justify-center account-passwords hidden'>
                                        <div class="w-auto max-w-24">
                                            <input 
                                                type="number" 
                                                class="text-center focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            >
                                        </div>
                                        <button id = 'AdminMinSpCharBtn' type = 'submit' class = 'hidden'></button>
                                    </form>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-600">Minimum Numbers</td>
                                <td class="text-center py-3">
                                    <p>2</p>
                                    <form class = 'flex justify-center account-passwords hidden'>
                                        <div class="w-auto max-w-24">
                                            <input 
                                                type="number" 
                                                class="text-center focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            >
                                        </div>  
                                        <button id = 'UserMinNumBtn' type = 'submit' class = 'hidden'></button>
                                    </form>
                                </td>
                                <td class="text-center py-3">
                                    <p>1</p>
                                    <form class = 'flex justify-center account-passwords hidden'>
                                        <div class="w-auto max-w-24">
                                            <input 
                                                type="number" 
                                                class="text-center focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            >
                                        </div>
                                        <button id = 'AdminMinNumBtn' type = 'submit' class = 'hidden'></button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <hr class="my-8 border-gray-200">
            <section class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Application</h2>
    
                <div class="space-y-4">
                    <div id = 'themesButton' class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg cursor-pointer">
                         <div class="w-2 h-2 bg-green-500 !bg-green-500 dark:bg-green-500 rounded-full mt-2 mix-blend-normal"></div>
                        <div>
                            <h3 class="font-medium text-gray-700">Themes</h3>
                            <p class="text-sm text-gray-500">Available themes for the application</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
              
        <div id="themesModal" class="hidden fixed inset-0 z-50 settings-modal-backdrop">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50"></div>
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 w-96 max-w-[95%] bg-white rounded-lg shadow-lg">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Theme Settings</h3>
                    <form>
                        <div class="mt-2 px-7 py-3">
                            <select name="theme" id="themesSelect" class="mt-2 w-full p-2 border rounded-md">
                                <option>---</option>
                                <option value="dark">Dark Theme</option>
                                <option value="system">Light</option>
                            </select>
                        </div>
                        <div class="items-center px-4 py-3 flex gap-2 justify-end">
                            <button id="themesModalExit" type="button" 
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                Cancel
                            </button>
                            <button type="submit" 
                                    id="saveThemeSettings"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
        </div>
        </div>
    </main>
</x-admin_layout>
