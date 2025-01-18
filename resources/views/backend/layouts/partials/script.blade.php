<script src="{{ asset('admin/js/password-hide-show.js') }}"></script>
<script>
    function initTinyMce(selector = '.summary-ckeditor') {
        if (tinymce.editors.length > 0) {
            tinymce.editors.forEach(editor => editor.remove());
        }
        
        tinymce.init({
            selector,
            image_class_list: [{
                title: 'Responsive',
                value: 'img-fluid'
            }, ],
            width: '100%',
            height: 350,
            setup: function(editor) {
                editor.on('init change', function() {
                    editor.save();
                });
            },
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste imagetools"
            ],
            toolbar: [
                'newdocument | print preview | searchreplace | undo redo  | alignleft aligncenter alignright alignjustify | code',
                'formatselect fontselect fontsizeselect | bold italic underline strikethrough | forecolor backcolor',
                'removeformat | hr pagebreak | charmap subscript superscript insertdatetime | bullist numlist | outdent indent blockquote | table'
            ],
            menubar: false,
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            branding: false,
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function() {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = function() {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    };
                };
                input.click();
            },
            placeholder: 'Enter your text here...',
        });
    }

    (function($) {
        "use strict";
        $(document).ready(function() {
            $(".select-2").select2();

            $('.btn.spinner-btn').click(function() {
                $('.invalid-feedback').removeClass('d-block'); // Hide Server side error
                if ($(this).parents('form').valid()) {
                    $(this).prop('disabled', true);
                    $(this).append('<span class="spinner"></span>');
                }

                $(this).parents('form').submit();
            });

            $('.select-country').on('change', function() {
                var idCountry = $(this).val();
                populateStates(idCountry);
            });

            function populateStates(countryId) {
                $(".select-state").html('');
                $.ajax({
                    url: "{{ url('/states') }}",
                    type: "POST",
                    data: {
                        country_id: countryId,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('.select-state').html('<option value="">Select State</option>');
                        $.each(result.states, function(key, value) {
                            $(".select-state").append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                        var defaultStateId = $(".select-state").data("default-state-id");
                        if (defaultStateId !== '') {
                            $('.select-state').val(defaultStateId);
                        }
                    }
                });
            }
        });

        // Select Permission
        $(document).on('click', '.select-all-permission', function() {
            $('.module_' + this.value).prop('checked', this.checked ? true : false);
        });


        // Init TinyMCE Editor
        initTinyMce();

        $(document).ready(function() {
            // Show All Country Flag beside Country Code in Select Box
            var defaultCountryCode = $('.select-country-code option:selected').data('default');
            $('.select-country-code').select2({
                templateResult: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    var $result = $('<span><img src="' + $(data.element).data('image') +
                        '" class="flag-img" />  +' + data.text + '</span>');
                    return $result;
                },
                templateSelection: function(selection) {
                    if (selection.text == '') {
                        return selection.text;
                    }
                    return selection.id ? '+ ' + selection.text : '';
                }
            });
        });


        // Remove unnecessary Title in Table Checkbox
        if (document.querySelector(".title")) {
            document.querySelector(".title").removeAttribute("title")
        }

        // Select All Rows btn
        let rowIds = [];
        $(document).on('click', '#select-all-rows', function(e) {
            if ($(this).is(':checked')) {
                // Select all rows
                rowIds = [];
                $(".rowClass").prop('checked', true).each(function() {
                    rowIds.push($(this).val());
                });
            } else {
                // Unselect all rows
                $(".rowClass").prop('checked', false);
                rowIds = [];
            }
            deleteConfirmationBtn();
        });

        function selectAllRows() {
            $("input:checkbox[name=row]:checked").each(function() {
                rowIds.push($(this).val());
            });
        }

        function unselectAllRows() {
            $("input:checkbox[name=row]").each(function() {
                var val = $(this).val();
                rowIds.splice(rowIds.indexOf(val), 1);
            });
            rowIds = [];
        }

        function deleteConfirmationBtn() {
            if (rowIds.length > 0) {
                $('.deleteConfirmationBtn').show();
                $('#count-selected-rows').html(' ' + rowIds.length);
            } else {
                $('.deleteConfirmationBtn').hide();
            }
        }

        // Row Checkbox Change event
        $(document).on('change', '.rowClass', function(e) {
            let id = $(this).val();
            if ($(this).is(':checked')) {
                // Add to selected rows
                if (rowIds.indexOf(id) === -1) {
                    rowIds.push(id);
                }
            } else {
                // Remove from selected rows
                rowIds = rowIds.filter(function(value) {
                    return value !== id;
                });
            }
            deleteConfirmationBtn();
        });


        function removeA(array) {
            var element, argument = arguments,
                length = argument.length,
                index;
            while (length > 1 && array.length) {
                element = argument[--length];
                while ((index = array.indexOf(element)) !== -1) {
                    array.splice(index, 1);
                }
            }
            return array;
        }

        function unselectRows() {
            var totalSelectedRows = $('input:checkbox[name=row]:checked').length;
            var totalRows = $('input:checkbox[name=row]').length;
            $('#select-all-rows').prop('checked', '');

        }

        function selectRows() {
            $.each(rowIds, function(index, value) {
                $('#rowId' + value).prop("checked", true);
            });

            var totalSelectedRows = $('input:checkbox[name=row]:checked').length;
            var totalRows = $('input:checkbox[name=row]').length;

            if (totalSelectedRows === totalRows) {
                if (totalSelectedRows === 0 && totalRows === 0) {
                    $('#selectAllRows').html(``);
                } else {
                    $('input[type=checkbox]').prop('checked', 'checked');
                }
            }
            $('#disable-select').prop('checked', '');
        }


        $(document).on('click', '#cancelModalBtn', function(e) {
            $("#deleteConfirmationModal").modal("hide");
        })

        $(document).on('click', '#confirm-DeleteRows', function(e) {
            e.preventDefault();

            let url = $('.deleteConfirmationBtn').data('url');
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _method: 'DELETE',
                    id: rowIds,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $("#deleteConfirmationModal").modal("hide");
                    window.location.reload();
                },
            });
        });

        // Delete Image
        $(document).on('click', '.close-icon', function(e) {
            e.preventDefault();
            $('#file-input').val('');
            var id = $(this).closest('.image-list-detail').find('.image-list-item').attr("id");

            $('#myModal').modal('show');
            let url = "{{ route('backend.media.delete', '') }}" + "/" + id;
            $('#myModal').on('click', '.btn-primary', function() {
                $.ajax({
                    type: 'delete',
                    url: url,
                    data: {
                        _method: 'DELETE',
                        data: id,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#myModal').modal('hide');
                        // toastr.success(data.message);
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        $('#myModal').modal('hide');
                        toastr.error('An error occurred while changing status.');
                    }
                });
            });
        });

        $('.select-2').on('select2:close', function(e) {
            $(this).valid();
        });

        // Delete Confirmation Btn
        $(document).on('click', '.deleteConfirmationBtn', function(e) {
            e.preventDefault();
            if (rowIds.length > 0) {
                $("#deleteConfirmationModal").modal("show");
            } else {
                alert("Please select atleast one checkbox");
            }
        });

        $(document).ready(function() {
            // Event listener for status change
            $(document).on('change', '.toggle-status', function() {
                let status = $(this).prop('checked') ? 1 : 0;
                let url = $(this).data('route');
                let clickedToggle = $(this);
                $.ajax({
                    type: "PUT",
                    url: url,
                    data: {
                        status: status,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(data) {
                        clickedToggle.prop('checked', status);
                        toastr.success("Status Updated Successfully");
                    },
                    error: function(xhr, status, error) {
                        console.log(error)
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.nextBtn, .submitBtn').on('click', function() {
                if (!$(this).parents('form').valid()) {
                    // If the form is not valid, find the first input element with an error
                    var ele = $("#" + $(this).parents('form').attr('id') + " :input.error:first");
                    // Find the tab containing the element with an error
                    var tabToShow = ele.closest('.tab-pane');
                    // Show the tab containing the error
                    $('.nav-tabs a[href="#' + tabToShow.attr('id') + '"]').tab('show');
                } else {
                    // If the form is valid, find the last tab
                    if ($(this).attr('type') == 'button')
                        showNextVisibleTab($('.nav-tabs .active'));
                }

                // Remove 'error' class from elements that don't have any text
                $(".error").each(function() {
                    if (!$(this).text()) {
                        $(this).removeClass('error');
                    }
                });
            });
            $('.previousBtn').on('click', function() {
                showPreviousVisibleTab($('.nav-tabs .active'));
            });
        });

        function showPreviousVisibleTab(currentTab) {
            var prevTab = currentTab.parent().prev().find('.nav-link');
            if (prevTab.is(':visible')) {
                // If the previous tab is visible, show it
                $('.nav-tabs a[href="' + prevTab.attr('href') + '"]').tab('show');
            } else {
                // If the previous tab is not visible, recursively check the previous tab
                showPreviousVisibleTab(prevTab);
            }
        }

        function showNextVisibleTab(currentTab) {
            var nextTab = currentTab.parent().next().find('.nav-link');
            if (nextTab.is(':visible')) {
                // If the next tab is visible, show it
                $('.nav-tabs a[href="' + nextTab.attr('href') + '"]').tab('show');
            } else {
                // If the next tab is not visible, recursively check the next tab
                showNextVisibleTab(nextTab);
            }
        }

        $(document).ready(function() {
            const optionFormat = (item) => {
                if (!item.id) {
                    return item.text;
                }

                const imageUrl = item.element.getAttribute('image');
                const subTitle = item.element.getAttribute('sub-title');
                const text = item.text.trim();
                const initialLetter = text.charAt(0).toUpperCase();
              
                let html = `
            <div class="selected-item d-flex align-items-center">
        `;

                if (imageUrl) {
                    html += `
                <img src="${imageUrl}" class="rounded-circle mr-2" style="width: 40px; height: 40px; object-fit: cover;" alt="${text}"/>
            `;
                } else {
                    html += `
                <div class="initial-letter rounded-circle d-flex align-items-center justify-content-center mr-2">
                    ${initialLetter}
                </div>
            `;
                }

                html += `
                <div class="detail">
                    <h6 class="">${text}</h6>
                    <p class=" small">${subTitle || ''}</p>
                </div>
            </div>
        `;

                const span = document.createElement('span');
                span.innerHTML = html;
                return span;
            };

            $('.user-dropdown').select2({
                placeholder: "Select an option",
                templateSelection: optionFormat,
                templateResult: optionFormat
            });
        });

    })
    (jQuery);
</script>
