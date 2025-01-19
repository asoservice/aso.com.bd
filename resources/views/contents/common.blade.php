@push('js') 
<script>
    $(document).ready(function() {
        const error = (e) => {
            console.log('Response Error: ', e)
            const res = e && Object.isExtensible(e) && 'responseJSON' in e ? e.responseJSON : null;
            if(res && Object.isExtensible(res)){ 
                if('message' in res) loader?.error(res.message);
                if('error' in res) loader?.error(res.error);
            } else loader?.error();
            return loader.hide();
        }

        $(document).on('click', '.delete-btn', function () {
            const url = $(this).attr('delete-btn');

            $('#deleteModalWindow').css('display', 'block');
            $('#deleteModalWindow .modal').show();
            $('#deleteModalWindow .modal').css('opacity', '1');
            $('#deleteModalWindow .modal #confirm-delete').attr('confirm-delete', url);
        });

        $(document).on('click', '#confirm-delete', function () {
            loader.show();
            const url = $(this).attr('confirm-delete');

            $.ajax({
                url,
                type: 'DELETE',
                success: function (response) {
                    loader?.responseMessages(response)
                    $('#deleteModalWindow').css('display', 'none');
                    $('#content-data-table').DataTable().ajax.reload();
                    return loader.hide();
                },
                error,
            });
        });

        $(document).on('click', '#cancel-delete', function () {
            $('#deleteModalWindow').css('display', 'none');
        });

        $(document).on('submit', '#contents-form', function(e) {
            e.preventDefault();
            loader.show();
            const url = $(this).attr('action');

            $.ajax({
                url,
                data: new FormData(this),
                type: 'POST',
                contentType: false,
                processData: false,
                success: function (response) {
                    // console.log('Update: ',response);
                    loader?.responseMessages(response);
                    $('#createModalWindow').css('display', 'none');
                    $('#editModalWindow').css('display', 'none');
                    $('#content-data-table').DataTable().ajax.reload();
                    return loader.hide();
                },
                error,
            });
        })

        const c = 'preview-file-input';
        $(document).on('change', `.${c}`, function() {
            const previewContainer = $('#' + $(`.${c}`).attr(c));
            const detail = previewContainer.find('.image-list-detail');
            detail.empty();

            let a = true;

            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                if(file.type.startsWith('image/')) {
                    const src = URL.createObjectURL(file);

                    detail.append(`
                        <div class="position-relative">
                            <img src="${src}" alt="User Image" class="image-list-item">
                            <!-- <div class="close-icon"> <i data-feather="x"></i> </div> -->
                        </div>
                    `);
                } else a = false;
            }

            if(a) previewContainer.removeClass('d-none'); 
        });


        // ------------ Create Modal ------------ //
        let isCreateAvailableForm = false;
        
        $('#createNewCategory').click(function () {
            loader.show();
            if(isCreateAvailableForm) {
                $('#createModalWindow').css('display', 'flex');
                return loader.hide();  
            }

            $.ajax({
                url: "{{ $routes['create'] }}",
                type: 'GET',
                success: function (response) {
                    loader?.responseMessages(response)
                    $('#createModalWindow .container-child').html(response);
                    
                    utility();
                    isCreateAvailableForm = true;
                    
                    $('#createModalWindow').css('display', 'flex');
                    return loader.hide();
                },
                error: function (e) {
                    isCreateAvailableForm = false;
                    return error(e);
                }
            });
        });

        $('#content-data-table').on('click', '.edit-btn', function () {
            loader.show();   
            const url = $(this).attr('edit-btn');

            $.ajax({
                url,
                data: { editForm: true },
                type: 'GET',
                success: function (response) {
                    loader?.responseMessages(response)
                    $('#editModalWindow .container-child').html(response);
                    
                    utility();
                    $('#editModalWindow').css('display', 'flex');
                    return loader.hide();
                },
                error,
            });
        });

        function utility() {
            initTinyMce('.description-ckeditor');
            $(".custom-select-2").select2();
            $('.custom-select-2').on('select2:close', function(e) {
                $(this).valid();
            });                    
        }

        $('#createModalWindow, #editModalWindow').on('click', '#cancelBtn', function () {
            $('#createModalWindow').css('display', 'none');
            $('#editModalWindow').css('display', 'none');
        });

        const table = $('#content-data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ $routes['index'] }}", // Replace with the actual route
            columns: @json($columns),
        });

        // Handle select all
        $('#select-all').on('change', function () {
            const checked = $(this).is(':checked');
            $('.rowClass').prop('checked', checked);
        });

        $(document).off('click', '.table-switch').on('change', '.table-switch input', function (e) {
            loader.show();
            const url = $(this).attr('table-switch');
 
            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    loader?.responseMessages(response)
                    
                    toastr.success('Status Updated Request Successful!');
                    return loader.hide();
                },
                error,
            })
        });

        // Data Table Select
        $(document).off('click', '.table-select').on('change', function (e) {
            loader.show();
            const url = $(this).attr('table-select');
            const key = $(this).attr('table-select-key');
 
            $.ajax({
                url: url,
                type: 'GET',
                data: { key, value: $(this).val() },
                success: function (response) {
                    loader?.responseMessages(response)
                    
                    toastr.success('Status Updated Request Successful!');
                    return loader.hide();
                },
                error,
            })
        });
    });
</script>
@endpush
