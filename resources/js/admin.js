//Sidebar toggle
document.getElementById('burger').addEventListener('click', function() {
    document.getElementById('modal_sidebar').style.display = 'block';
});

document.getElementById('burger_modal').addEventListener('click', function() {
    document.getElementById('modal_sidebar').style.display = 'none';
});


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

    // Get the edit modal
    var editModal = $('#editAdminModal');

    // Get the button that opens the edit modal
    var editBtns = $('.editAdminBtn');

    // When the user clicks the edit button, open the edit modal and populate the form
    editBtns.on('click', function() {
        var adminId = $(this).data('id');
        $.ajax({
            url: '/admin/admins/' + adminId + '/edit',
            method: 'GET',
            success: function(data) {
                $('#edit_admin_id').val(data.admin_id);
                $('#edit_firstname').val(data.firstname);
                $('#edit_lastname').val(data.lastname);
                $('#edit_email').val(data.email);
                editModal.show();
            }
        });
    });

    // When the user clicks on <span> (x), close the edit modal
    editModal.find('.close').on('click', function() {
        editModal.hide();
    });

    // When the user clicks anywhere outside of the edit modal, close it
    $(window).on('click', function(event) {
        if ($(event.target).is(editModal)) {
            editModal.hide();
        }
    });

    // Get the delete modal
    var deleteModal = $('#deleteAdminModal');

    // Get the button that opens the delete modal
    var deleteBtns = $('.deleteAdminBtn');

    // When the user clicks the delete button, open the delete modal and set the admin ID
    deleteBtns.on('click', function() {
        var adminId = $(this).data('id');
        $('#delete_admin_id').val(adminId);
        deleteModal.show();
    });

    // When the user clicks on <span> (x), close the delete modal
    deleteModal.find('.close').on('click', function() {
        deleteModal.hide();
    });

    // When the user clicks anywhere outside of the delete modal, close it
    $(window).on('click', function(event) {
        if ($(event.target).is(deleteModal)) {
            deleteModal.hide();
        }
    });
});