<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link rel="stylesheet" href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
<x-admin_layout>
    <main>
        <div class="container mt-5">
            <table id="myTable" class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Date Created</th>
                        <th>Last Login</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td>{{ $admin->admin_id }}</td>
                            <td>{{ $admin->firstname }}</td>
                            <td>{{ $admin->lastname }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->created_at }}</td>
                            <td>{{ $admin->last_login }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button id="addAdminBtn" class="btn btn-primary mt-3">Add Admin</button>
        </div>

        <!-- Add Admin Modal -->
        <div id="addAdminModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Admin</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.admins.create') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="firstname">First Name:</label>
                                <input type="text" id="firstname" name="firstname" class="form-control" required>
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
                            <button type="submit" class="btn btn-primary">Add Admin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#myTable');

            // Get the modal
            var modal = $('#addAdminModal');

            // Get the button that opens the modal
            var btn = $('#addAdminBtn');

            // Get the <span> element that closes the modal
            var span = $('.close');

            // When the user clicks the button, open the modal 
            btn.on('click', function() {
                modal.show();
            });

            // When the user clicks on <span> (x), close the modal
            span.on('click', function() {
                modal.hide();
            });

            // When the user clicks anywhere outside of the modal, close it
            $(window).on('click', function(event) {
                if ($(event.target).is(modal)) {
                    modal.hide();
                }
            });
        });
    </script>
</x-admin_layout>