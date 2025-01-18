
@push('js')    
    @isset($type)   
        @if ($type == 'create' || $type == 'edit')
            <script>
                $(document).ready(function() {
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
                    })
                })
            </script>
        @endif 
        
        @if($type == 'index')
            <script>
                $(document).ready(function () {
                    let isCreateAvailableForm = false;
                    
                    $('#createNewCategory').click(function () {
                        $('#createModalWindow').css('display', 'flex');
                        if(isCreateAvailableForm) return;

                        $.ajax({
                            url: "{{ $routes['create'] }}",
                            type: 'GET',
                            success: function (response) {
                                isCreateAvailableForm = true;
                                $('#createModalWindow .container').html(response);
                                
                                initTinyMce('.description-ckeditor');
                                $(".custom-select-2").select2();
                                $('.custom-select-2').on('select2:close', function(e) {
                                    $(this).valid();
                                });                    
                            },
                            error: function (xhr, status, error) {
                                isCreateAvailableForm = false;
                                console.log(error);
                            }
                        });
                    });

                    $('#createModalWindow').on('click', '#cancelBtn', function () {
                        $('#createModalWindow').css('display', 'none');
                    });

                    const table = $('#faqCategoryTable').DataTable({
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
                });
            </script>
        @endif
    @endisset
@endpush
