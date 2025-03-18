<x-admin_layout>
  <main class="p-5">
    <div class="min-w-full mx-auto bg-white rounded-lg p-5 shadow-sm border border-gray-150">
      <!-- Settings Header -->
      <h1 class="text-2xl font-bold text-gray-900 mb-6">Settings</h1>

      <!-- Security Section -->
      <section class="space-y-6 mb-8">
        <!-- ... (previous security section content remains the same) ... -->
      </section>

      <!-- Application Section -->
      <section class="space-y-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Application</h2>
        
        <div class="border border-gray-100 rounded-md overflow-hidden">
          <!-- Logo Section -->
          <div class="flex items-center justify-between p-4 bg-gray-50 border-b border-gray-100">
            <div>
              <h3 class="text-sm font-medium text-gray-700">Logo</h3>
              <p class="text-sm text-gray-500 mt-1">Update the logo image</p>
            </div>
            <button onclick="openModal('logoModal')" class="text-sm text-blue-600 hover:text-blue-700">Change</button>
          </div>
          
          <!-- Themes Section -->
          <div class="flex items-center justify-between p-4 bg-white border-b border-gray-100">
            <div>
              <h3 class="text-sm font-medium text-gray-700">Themes</h3>
              <p class="text-sm text-gray-500 mt-1">Available themes for the application</p>
            </div>
            <button onclick="openModal('themeModal')" class="text-sm text-blue-600 hover:text-blue-700">Manage</button>
          </div>
          
          <!-- Lightspeed Section -->
          <div class="flex items-center justify-between p-4 bg-white">
            <div>
              <h3 class="text-sm font-medium text-gray-700">Lightspeed</h3>
              <p class="text-sm text-gray-500 mt-1">Drafts to the Lightspeed</p>
            </div>
            <button onclick="openModal('lightspeedModal')" class="text-sm text-blue-600 hover:text-blue-700">Configure</button>
          </div>
        </div>
      </section>
    </div>

    <!-- Modal Backdrop -->
    <div id="modalBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50"></div>

    <!-- Logo Upload Modal -->
    <div id="logoModal" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg p-6 z-50 w-96">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Update Logo</h3>
        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
      </div>
      <div class="space-y-4">
        <input type="file" class="w-full p-2 border rounded">
        <div class="flex justify-end gap-2">
          <button onclick="closeModal()" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Cancel</button>
          <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload</button>
        </div>
      </div>
    </div>

    <!-- Theme Management Modal -->
    <div id="themeModal" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg p-6 z-50 w-96">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Manage Themes</h3>
        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
      </div>
      <div class="space-y-4">
        <div class="p-3 border rounded hover:bg-gray-50 cursor-pointer">Light Theme</div>
        <div class="p-3 border rounded hover:bg-gray-50 cursor-pointer">Dark Theme</div>
        <div class="p-3 border rounded hover:bg-gray-50 cursor-pointer">Custom Theme</div>
        <div class="flex justify-end">
          <button onclick="closeModal()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Close</button>
        </div>
      </div>
    </div>

    <!-- Lightspeed Configuration Modal -->
    <div id="lightspeedModal" class="hidden fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-lg p-6 z-50 w-96">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">Lightspeed Configuration</h3>
        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
      </div>
      <div class="space-y-4">
        <div class="space-y-2">
          <label class="text-sm font-medium">Draft Frequency</label>
          <select class="w-full p-2 border rounded">
            <option>Daily</option>
            <option>Weekly</option>
            <option>Monthly</option>
          </select>
        </div>
        <div class="flex justify-end gap-2">
          <button onclick="closeModal()" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Cancel</button>
          <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
        </div>
      </div>
    </div>
  </main>

  <script>
    // Modal handling functions
    function openModal(modalId) {
      document.getElementById('modalBackdrop').classList.remove('hidden');
      document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('modalBackdrop').classList.add('hidden');
      document.querySelectorAll('[id$="Modal"]').forEach(modal => {
        modal.classList.add('hidden');
      });
    }

    // Close modal when clicking on backdrop
    document.getElementById('modalBackdrop').addEventListener('click', closeModal);

    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeModal();
    });

    // Toggle switch functionality for 2FA
    const toggleSwitch = document.querySelector('button[aria-label="Enable two-factor authentication"]');
    if (toggleSwitch) {
      toggleSwitch.addEventListener('click', () => {
        const enabled = toggleSwitch.classList.toggle('bg-blue-600');
        toggleSwitch.querySelector('span').style.transform = enabled 
          ? 'translateX(calc(1.5rem - 0.25rem))' 
          : 'translateX(0.25rem)';
        
        // Optional: Add confirmation dialog
        if (enabled) {
          const confirmed = confirm('Enabling two-factor authentication will require all admins to set up 2FA. Continue?');
          if (!confirmed) {
            toggleSwitch.classList.remove('bg-blue-600');
            toggleSwitch.querySelector('span').style.transform = 'translateX(0.25rem)';
          }
        }
      });
    }
  </script>

  <style>
    /* Custom transition for modals */
    [id$="Modal"] {
      transition: opacity 0.3s ease, transform 0.3s ease;
    }
    
    /* Toggle switch transitions */
    button[aria-label="Enable two-factor authentication"] span {
      transition: transform 0.3s ease;
    }
  </style>
</x-admin_layout>