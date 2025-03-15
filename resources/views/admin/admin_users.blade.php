<x-admin_layout>
  <main class="p-5">
    <div class="min-w-full mx-auto bg-white rounded-lg p-5 shadow-sm border border-gray-150">
      <div class="flex justify-between items-center mb-5">
        <h2 class="text-xl font-medium text-gray-800">User Management</h2>
      </div>

      <form method="GET" action="{{ route('admin.users') }}">
        <div class="flex flex-wrap gap-4 mb-5 items-center">
          <div class="ml-auto flex gap-2 flex-1 max-w-md">
            <input type="text" name="search" value="{{ request('search') }}" class="flex-1 p-2 border border-gray-200 rounded-md text-sm" placeholder="Search by email">
            <button type="submit" class="p-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">Search</button>
          </div>
        </div>
      </form>

      <table class="w-full border-collapse mb-5">
        <thead>
          <tr>
            <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">First Name</th>
            <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Middle Name</th>
            <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Last Name</th>
            <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Email</th>
            <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Date Joined</th>
            <th class="text-left p-3 border-b border-gray-200 text-gray-800 font-medium text-sm">Actions</th>
          </tr>
        </thead>
        <tbody>
          @if($users->isEmpty())
            <tr>
              <td colspan="6" class="text-center text-gray-600 py-5">No users available</td>
            </tr>
          @else
            @foreach($users as $user)
              <tr>
                <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $user->firstname }}</td>
                <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $user->middlename ?? 'N/A' }}</td>
                <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $user->lastname }}</td>
                <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $user->email }}</td>
                <td class="p-3 border-b border-gray-200 text-sm text-gray-600">{{ $user->created_at->format('Y-m-d') }}</td>
                <td class="p-3 border-b border-gray-200 text-sm flex gap-2">
                  <!-- Edit User Button -->
                  <button type="button" class="bg-amber-100 border-none p-2 rounded-md cursor-pointer text-amber-600 hover:bg-amber-200 inline-block text-center editUserBtn" 
                    data-id="{{ $user->user_id }}" 
                    data-firstname="{{ $user->firstname }}" 
                    data-middlename="{{ $user->middlename }}" 
                    data-lastname="{{ $user->lastname }}" 
                    data-email="{{ $user->email }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3Z"/>
                    </svg>
                  </button>
                  <!-- Delete User Form -->
                  <form action="{{ route('admin.users.delete', $user->user_id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-100 border-none p-2 rounded-md cursor-pointer text-red-600 hover:bg-red-200 inline-block text-center">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18"></path>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                      </svg>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
        
      </table>
    <div class="flex justify-end mb-4">
        <button id="addUserBtn" class="p-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">Add User</button>
    </div>
      

      <!-- Pagination -->
      <div class="flex justify-end items-center gap-3">
        @if($users->currentPage() > 1)
          <a href="{{ $users->appends(['search' => request('search')])->previousPageUrl() }}" class="p-2 border border-gray-200 bg-white rounded-lg cursor-pointer hover:bg-gray-100 w-10 text-center no-underline">←</a>
        @else
          <button class="p-2 border border-gray-200 bg-white rounded-lg cursor-not-allowed opacity-50 w-10">←</button>
        @endif
        
        <span class="text-sm text-gray-600 font-medium">{{ $users->currentPage() }} / {{ max(1, $users->lastPage()) }}</span>
        
        @if($users->hasMorePages())
          <a href="{{ $users->appends(['search' => request('search')])->nextPageUrl() }}" class="p-2 border border-gray-200 bg-white rounded-lg cursor-pointer hover:bg-gray-100 w-10 text-center no-underline">→</a>
        @else
          <button class="p-2 border border-gray-200 bg-white rounded-lg cursor-not-allowed opacity-50 w-10">→</button>
        @endif
      </div>
      
    </div>
    

    <!-- Create User Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="flex justify-between items-center border-b border-gray-200 p-4">
          <h5 class="text-lg font-medium text-gray-800">Create User</h5>
          <button type="button" class="close text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-4">
          <form action="{{ route('admin.users.create') }}" method="POST">
            @csrf
            <div class="mb-4">
              <label for="firstname" class="block text-sm font-medium text-gray-700 mb-1">First Name:</label>
              <input type="text" id="firstname" name="firstname" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
              <label for="middlename" class="block text-sm font-medium text-gray-700 mb-1">Middle Name:</label>
              <input type="text" id="middlename" name="middlename" class="w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
              <label for="lastname" class="block text-sm font-medium text-gray-700 mb-1">Last Name:</label>
              <input type="text" id="lastname" name="lastname" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
              <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
              <input type="email" id="email" name="email" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
              <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password:</label>
              <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password:</label>
              <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <button type="submit" class="w-full p-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create User</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden z-50">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="flex justify-between items-center border-b border-gray-200 p-4">
          <h5 class="text-lg font-medium text-gray-800">Edit User</h5>
          <button type="button" class="close text-gray-500 hover:text-gray-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-4">
          <form id="editUserForm" action="{{ route('admin.users.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_user_id" name="id">
            <div class="mb-4">
              <label for="edit_firstname" class="block text-sm font-medium text-gray-700 mb-1">First Name:</label>
              <input type="text" id="edit_firstname" name="firstname" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
              <label for="edit_middlename" class="block text-sm font-medium text-gray-700 mb-1">Middle Name:</label>
              <input type="text" id="edit_middlename" name="middlename" class="w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
              <label for="edit_lastname" class="block text-sm font-medium text-gray-700 mb-1">Last Name:</label>
              <input type="text" id="edit_lastname" name="lastname" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
              <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email:</label>
              <input type="email" id="edit_email" name="email" class="w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <button type="submit" class="w-full p-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update User</button>
          </form>
        </div>
      </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
      $(document).ready(function() {
        // Modal functionality
        const addUserModal = document.getElementById('addUserModal');
        const editUserModal = document.getElementById('editUserModal');
        const closeButtons = document.querySelectorAll('.close');

        // Open add user modal
        $('#addUserBtn').on('click', function() {
          addUserModal.classList.remove('hidden');
        });

        // Open edit user modal
        $('.editUserBtn').on('click', function() {
          const firstName = $(this).data('firstname');
          const middleName = $(this).data('middlename');
          const lastName = $(this).data('lastname');
          const email = $(this).data('email');
          const userId = $(this).data('id');

          $('#edit_user_id').val(userId);
          $('#edit_firstname').val(firstName);
          $('#edit_middlename').val(middleName);
          $('#edit_lastname').val(lastName);
          $('#edit_email').val(email);

          editUserModal.classList.remove('hidden');
        });

        // Close modals when clicking X button
        closeButtons.forEach(button => {
          button.addEventListener('click', function() {
            addUserModal.classList.add('hidden');
            editUserModal.classList.add('hidden');
          });
        });

        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
          if (event.target === addUserModal) {
            addUserModal.classList.add('hidden');
          }
          if (event.target === editUserModal) {
            editUserModal.classList.add('hidden');
          }
        });
      });
    </script>
  </main>
</x-admin_layout>