@push('js') 
<script>
    $(document).ready(function() {
        $(document).on('click', '#submitBtn', function() {
            loader.show();
            $('#submitBtn').closest('form').submit();
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
    });

    $(document).ready(function () {
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
                    $('#createModalWindow .container-child').html(response);
                    
                    utility();
                    isCreateAvailableForm = true;
                    
                    $('#createModalWindow').css('display', 'flex');
                    return loader.hide();
                },
                error: function (xhr, status, error) {
                    isCreateAvailableForm = false;
                    console.log(error);
                    return loader.hide();
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
                    $('#editModalWindow .container-child').html(response);
                    
                    utility();
                    $('#editModalWindow').css('display', 'flex');
                    return loader.hide();
                },
                error: function (xhr, status, error) {
                    console.log(error);
                    return loader.hide();
                }
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
                    if(Array.isArray(response?.message) ) {
                        const keys = Object.keys(response.message);
                        Object.values(response.message).forEach((element,i) => {
                            if(keys[i] in toastr) {
                                toastr[keys[i]](element);
                            }
                        });

                        return loader.hide();
                    }
                    
                    toastr.success('Status Updated Request Successful!');
                    return loader.hide();
                },
                error: function (xhr, status, error) {
                    console.log(error);
                    toastr.error('Status Updated Request Failed!');
                    return loader.hide();
                },
            })
            console.log(url);
        });
    });
</script>
@endpush
{{--
@push('js')    
    @isset($type)   
        @if ($type == 'create' || $type == 'edit')
            <script>
                $(document).ready(function() {
                    $(document).on('click', '#submitBtn', function() {
                        console.log($('#submitBtn').closest('form'))
                        $('#submitBtn').closest('form').submit();
                    })

                    const c = 'preview-file-input';
                    $(`.${c}`).on('change', function() {
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
                });
            </script>
        @endif 
        
        @if($type == 'index')
            <script>
                $(document).ready(function () {
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
                                $('#createModalWindow .container-child').html(response);
                                
                                utility();
                                isCreateAvailableForm = true;
                                
                                $('#createModalWindow').css('display', 'flex');
                                return loader.hide();
                            },
                            error: function (xhr, status, error) {
                                isCreateAvailableForm = false;
                                console.log(error);
                                return loader.hide();
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
                                $('#editModalWindow .container-child').html(response);
                                
                                utility();
                                $('#editModalWindow').css('display', 'flex');
                                return loader.hide();
                            },
                            error: function (xhr, status, error) {
                                console.log(error);
                                return loader.hide();
                            }
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
                                if(Array.isArray(response?.message) ) {
                                    const keys = Object.keys(response.message);
                                    Object.values(response.message).forEach((element,i) => {
                                        if(keys[i] in toastr) {
                                            toastr[keys[i]](element);
                                        }
                                    });

                                    return loader.hide();
                                }
                                
                                toastr.success('Status Updated Request Successful!');
                                return loader.hide();
                            },
                            error: function (xhr, status, error) {
                                console.log(error);
                                toastr.error('Status Updated Request Failed!');
                                return loader.hide();
                            },
                        })
                        console.log(url);
                    });
                });
            </script>
        @endif
    @endisset
@endpush
--}}
