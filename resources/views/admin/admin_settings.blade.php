<x-admin_layout>
<<<<<<< HEAD
    <main data-page="settings" class= "h-screen w-full">
        <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-sm h-screen w-full">
=======
>>>>>>> parent of 89214c4 (das)
    <main>
        <div class="max-w-3xl mx-auto p-6 bg-white rounded-lg shadow-sm">
            <!-- Settings Header -->
            <h1 class="text-2xl font-bold text-gray-900 mb-8">Settings</h1>

            <!-- Security Section -->
<<<<<<< HEAD
            
            <!-- Security Section -->
            <section class="space-y-6">
                <!-- <h2 class="text-xl font-semibold text-gray-800 mb-4">Security</h2>

                <!-- Two-Factor Auth -->
                <!-- <div class="space-y-2">
=======
            <section class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Security</h2>

                <!-- Two-Factor Auth -->
                <div class="space-y-2">
>>>>>>> parent of 89214c4 (das)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="font-medium text-gray-700">Two-Factor Authentication</h3>
                            <p class="text-sm text-gray-500">Enable two-factor authentication for admins</p>
                        </div>
                        <button class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200">
                            <span class="sr-only">Enable two-factor authentication</span>
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white translate-x-1 transition-transform"></span>
                        </button>
                    </div>
<<<<<<< HEAD
                </div> -->
=======
                </div>
>>>>>>> parent of 89214c4 (das)

                <!-- Password Requirements Table -->
                <div class="space-y-4">
                    <h3 class="font-medium text-gray-700">Account Passwords</h3>
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 text-gray-600 font-medium"></th>
                                <th class="text-center py-3 text-gray-600 font-medium">Users</th>
                                <th class="text-center py-3 text-gray-600 font-medium">Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-600">Minimum Characters</td>
                                <td class="text-center py-3">8</td>
                                <td class="text-center py-3">8</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-600">Minimum Special Characters</td>
                                <td class="text-center py-3">2</td>
                                <td class="text-center py-3">1</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 text-gray-600">Minimum Numbers</td>
                                <td class="text-center py-3">2</td>
                                <td class="text-center py-3">1</td>
                            </tr>
                            <tr>
                                <td class="py-3 text-gray-600">Mix of Upper and Lower Case</td>
                                <td class="text-center py-3">
                                    <svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                                <td class="text-center py-3">
                                    <svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <hr class="my-8 border-gray-200">

            <!-- Application Section -->
            <section class="space-y-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Application</h2>
                
                <div class="space-y-4">
<<<<<<< HEAD
                    <!-- Updated modal triggers -->
                    <!-- <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg cursor-pointer" 
                         data-settings-modal="logoModal">
=======
                    <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg cursor-pointer" onclick="openModal('logoModal')">
>>>>>>> parent of 89214c4 (das)
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <h3 class="font-medium text-gray-700">Logo</h3>
                            <p class="text-sm text-gray-500">Update the logo image</p>
                        </div>
<<<<<<< HEAD
                    </div> -->
                    
                    <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg cursor-pointer" 
                         data-settings-modal="themesModal">
                         <div class="w-2 h-2 bg-green-500 !bg-green-500 dark:bg-green-500 rounded-full mt-2 mix-blend-normal"></div>
=======
                    </div>
                    
                    <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg cursor-pointer" onclick="openModal('themesModal')">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
>>>>>>> parent of 89214c4 (das)
                        <div>
                            <h3 class="font-medium text-gray-700">Themes</h3>
                            <p class="text-sm text-gray-500">Available themes for the application</p>
                        </div>
                    </div>
                    
<<<<<<< HEAD
                    <!-- <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg cursor-pointer" 
                         data-settings-modal="lightspeedModal">
=======
                    <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg cursor-pointer" onclick="openModal('lightspeedModal')">
>>>>>>> parent of 89214c4 (das)
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div>
                            <h3 class="font-medium text-gray-700">Lightspeed</h3>
                            <p class="text-sm text-gray-500">Drafts to the Lightspeed</p>
                        </div>
<<<<<<< HEAD
                    </div> -->
                </div>
            </section>
        </div>

        <!-- Modals
        <!-- Logo Modal -->
        <!-- <div id="logoModal" class="hidden fixed inset-0 z-50 settings-modal-backdrop">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50"></div>
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 w-96 max-w-[95%] bg-white rounded-lg shadow-lg">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Logo Settings</h3>
                    <form>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500">Upload a new logo here.</p>
                            <input type="file" 
                                   class="mt-4 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        </div>
                        <div class="items-center px-4 py-3 flex gap-2 justify-end">
                            <button type="button" 
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
                                    data-settings-modal-close>
                                Cancel
                            </button>
                            <button type="submit" 
                                    id="saveLogoSettings"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->

        <!-- Themes Modal -->
        <div id="themesModal" class="hidden fixed inset-0 z-50 settings-modal-backdrop">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50"></div>
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 w-96 max-w-[95%] bg-white rounded-lg shadow-lg">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Theme Settings</h3>
                    <form>
                        <div class="mt-2 px-7 py-3">
                            <select name="theme" id="themeSelect" class="mt-2 w-full p-2 border rounded-md">
                                <option value="dark">Dark Theme</option>
                                <option value="system">System Default</option>
                            </select>
                        </div>
                        <div class="items-center px-4 py-3 flex gap-2 justify-end">
                            <button type="button" 
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
                                    data-settings-modal-close>
                                Cancel
                            </button>
                            <button type="submit" 
                                    id="saveThemeSettings"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Save
                            </button>
                        </div>
                    </form>
=======
                    </div>
                </div>
            </section>
        </div>
<!-- Modals -->
        <!-- Logo Modal -->
        <div id="logoModal" class="hidden fixed inset-0 z-50">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50" onclick="closeModal('logoModal')"></div>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Logo Settings</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">Upload a new logo here.</p>
                        <input type="file" class="mt-4" />
                    </div>
                    <div class="items-center px-4 py-3">
                        <button class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300" onclick="closeModal('logoModal')">
                            Save
                        </button>
                    </div>
>>>>>>> parent of 89214c4 (das)
                </div>
            </div>
        </div>

<<<<<<< HEAD
        <!-- Lightspeed Modal
        <!-- <div id="lightspeedModal" class="hidden fixed inset-0 z-50 settings-modal-backdrop">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50"></div>
            <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 p-5 w-96 max-w-[95%] bg-white rounded-lg shadow-lg">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Lightspeed Settings</h3>
                    <form>
                        <div class="mt-2 px-7 py-3">
                            <p class="text-sm text-gray-500">Configure Lightspeed integration:</p>
                            <input type="text" 
                                   class="mt-2 w-full p-2 border rounded-md" 
                                   placeholder="API Key" />
                        </div>
                        <div class="items-center px-4 py-3 flex gap-2 justify-end">
                            <button type="button" 
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300"
                                    data-settings-modal-close>
                                Cancel
                            </button>
                            <button type="submit" 
                                    id="saveLightspeedSettings"
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> 
=======
        <h1>
            I'm in the Settings page
        </h1>
>>>>>>> parent of f14f267 (Update admin_settings.blade.php)
=======
        <!-- Themes Modal -->
        <div id="themesModal" class="hidden fixed inset-0 z-50">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50" onclick="closeModal('themesModal')"></div>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Themes Settings</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">Select a theme for the application.</p>
                        <select class="mt-4 block w-full p-2 border border-gray-300 rounded-md">
                            <option>Theme 1</option>
                            <option>Theme 2</option>
                            <option>Theme 3</option>
                        </select>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300" onclick="closeModal('themesModal')">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lightspeed Modal -->
        <div id="lightspeedModal" class="hidden fixed inset-0 z-50">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50" onclick="closeModal('lightspeedModal')"></div>
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Lightspeed Settings</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">Drafts to the Lightspeed settings here.</p>
                        <input type="text" class="mt-4 block w-full p-2 border border-gray-300 rounded-md" placeholder="Enter Lightspeed settings" />
                    </div>
                    <div class="items-center px-4 py-3">
                        <button class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300" onclick="closeModal('lightspeedModal')">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openModal(modalId) {
                document.getElementById(modalId).classList.remove('hidden');
            }

            function closeModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }

            // Close modal with Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeModal('logoModal');
                    closeModal('themesModal');
                    closeModal('lightspeedModal');
                }
            });
        </script>
>>>>>>> parent of 89214c4 (das)
    </main>
</x-admin_layout>
