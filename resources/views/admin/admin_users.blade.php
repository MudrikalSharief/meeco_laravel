<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<x-admin_layout>
    <main>
          
        <div class="container mt-3">
            <table id="myTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Date Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->firstname }}</td>
                            <td>{{ $user->middlename ??" " }}</td>
                            <td>{{ $user->lastname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>
                                <!-- Edit User Button -->
                                <button type="button" class="btn btn-warning btn-sm editUserBtn" data-id="{{ $user->user_id }}" data-firstname="{{ $user->firstname }}" data-middlename="{{ $user->middlename }}" data-lastname="{{ $user->lastname }}" data-email="{{ $user->email }}">Edit</button>
                                <!-- Delete User Form -->
                                <form action="{{ route('admin.users.delete', $user->user_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center">
                <div class="pagination">
                    {{ $users->links() }}
                </div>
                <button id="addUserBtn" class="btn btn-primary">Add User</button>
            </div>
        </div>

        <!-- Create User Modal -->
        <div id="addUserModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.users.create') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="firstname">First Name:</label>
                                <input type="text" id="firstname" name="firstname" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="middlename">MiddleName:</label>
                                <input type="text" id="middlename" name="middlename" class="form-control" optional>
                            </div>
                            <div class="form-group">
                                <label for="lastname">Last Name:</label>
                                <input type="text" id="lastname" name="lastname" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password:</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div id="editUserModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm" action="{{ route('admin.users.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="edit_user_id" name="id">
                            <div class="form-group">
                                <label for="edit_firstname">First Name:</label>
                                <input type="text" id="edit_firstname" name="firstname" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_middlename">Middle Name:</label>
                                <input type="text" id="edit_middlename" name="middlename" class="form-control" optional>
                            </div>
                            <div class="form-group">
                                <label for="edit_lastname">Last Name:</label>
                                <input type="text" id="edit_lastname" name="lastname" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_email">Email:</label>
                                <input type="email" id="edit_email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable({
                    "paging": false,
                    "info": false
                });

                // Get the modals
                var addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
                var editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));

                // When the user clicks the button, open the add user modal 
                $('#addUserBtn').on('click', function() {
                    addUserModal.show();
                });

                // When the user clicks the button, open the edit user modal 
                $('.editUserBtn').on('click', function() {
                    var firstname = $(this).data('firstname');
                    var middlename = $(this).data('middlename');
                    var lastname = $(this).data('lastname');
                    var email = $(this).data('email');
                    var user_id = $(this).data('id'); // Fixed: changed from user_Id

                    $('#edit_user_id').val(user_id);
                    $('#edit_firstname').val(firstname);
                    $('#edit_middlename').val(middlename);
                    $('#edit_lastname').val(lastname);
                    $('#edit_email').val(email);

                    editUserModal.show();
                });

                // When the user clicks on <span> (x), close the modals
                $('.close').on('click', function() {
                    addUserModal.hide();
                    editUserModal.hide();
                });

                // When the user clicks anywhere outside of the modals, close them
                $(window).on('click', function(event) {
                    if ($(event.target).hasClass('modal')) {
                        addUserModal.hide();
                        editUserModal.hide();
                    }
                });
            });
        </script>
    </main>
</x-admin_layout>