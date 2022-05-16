@extends('backend.layout.default')
@section('meta-data')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('plugins/custom/datatables/datatables.bundle.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/custom/nestable/nestable.css')}}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
@endsection
@section('content')
    <div class="card card-custom" id="kt_page_sticky_card">
        <div class="card-body">
            <!--begin: Datatable-->
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-hover table-checkable dataTable no-footer dtr-inline"
                               id="kt_datatable" style="margin-top: 13px !important; width: 1236px;" role="grid"
                               aria-describedby="kt_datatable_info">
                        </table>
                    </div>
                </div>
            </div>
            <!--end: Datatable-->
        </div>
    </div>
    {{--    begin modal confirm delete--}}
    <div class="modal fade" id="confirm_delete" data-backdrop="static"
         tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thông báo</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    Xác nhận thao tác xóa nhóm
                </div>
                <div class="modal-footer">
                    <form action="" method="POST" id="form_delete">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                                data-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-danger font-weight-bold">Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{--    end: modal confirm delete--}}
    {{--    modal list item in group--}}
    <div class="modal fade" id="items_in_group" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalSizeSm" aria-hidden="true">
        <div id="kakaa" class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="title_modal">* Kéo thả để sắp xếp vị trí</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tìm kiếm</label>
                        <input type="search" class="form-control" data-group_id="" id="input_search_item_group"
                               placeholder="Từ khoá...">
                    </div>
                    <div id="result_data"></div>
                    <div class="card">
                        <div class="card-body">
                            <div class="dd" id="nestable">
                                <ol class="dd-list">

                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    end: modal item in group--}}
    {{--    modal confirm delete multi record--}}
    <div class="modal fade" id="confirm_delete_muti" data-backdrop="static"
         tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thông báo</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    Xác nhận xoá những item đã chọn
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-danger font-weight-bold"
                            onclick="deleteMultiRecord('{{route('delete_multi_group')}}')">Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{--    end modal modal confirm delete multi record--}}

@endsection
@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('plugins/custom/datatables/datatables.bundle.js')}}"></script>

    {{--    Notify--}}
    <script src="{{asset('js/pages/features/miscellaneous/sweetalert2.js')}}"></script>
    <script src="{{asset('js/jquery.nestable.js')}}"></script>
    <script src="{{ asset('js/pages/features/miscellaneous/toastr.js') }}"></script>
    <script src="{{asset('js/my.js')}}"></script>
    @if(Session::has('message'))
        <script>
            $(document).ready(function () {
                toastr.success('{{ session()->pull('message') }}')
            })
        </script>
    @endif
    <!-- App scripts -->
    <script>
        setDataTable({url: '{!! route('ajax_get_group',$module) !!}'})
        let keyword_input = $('#input_search_item_group');
        let first_open_modal = false;
        function updateOutput(e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }

            $.ajax({
                url: "{{ route('index.change-order-in-group') }}",
                type: 'POST',
                data: {
                    list: list.nestable('serialize'),
                    group_id: keyword_input.attr('data-group_id'),
                },
                success: function (res) {
                    if(!first_open_modal && res.status){
                        toastr.success(res.message);
                    }
                    if(!res.status && !first_open_modal) {
                        toastr.error(res.message);
                    }
                    first_open_modal = false;
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }
        // Sự kiện khi kéo thả vị trí
        $(document).ready(function () {
            $('#nestable').nestable().on('change', updateOutput);
        });

        keyword_input.on('input', function () {
            clearTimeout(this.delay);
            this.delay = setTimeout(function () {
                if( !$(this).val() ) {
                    $('#result_data').html('');
                }else {
                    const GROUP_ID = $(this).data('group_id');
                    $.ajax({
                        url: "/api/search-item",
                        method: 'GET',
                        data: {
                            search_query: $(this).val(),
                            module: "{{$module}}",
                        },
                        success: function (res) {
                            let html = '';
                            html += `<div class="card">`;
                            html += `<div class="card-body" id="result-list-item">`;
                            html += `</div>`;
                            html += `</div>`;
                            if (res.length) {
                                $('#result_data').html(html);
                            }
                            res.forEach(function (item) {
                                let html = '<div class="alert alert-custom alert-white shadow-lg fade show">';
                                html += '<div class="symbol symbol-60 symbol-2by3 flex-shrink-0 mr-4">';
                                html += `<div class="symbol-label" style="background-image: url( ${item.image || '/media/demos/empty.jpg'})"></div>`;
                                html += '</div>';
                                html += '<div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">';
                                html += `<p class="text-dark-75 font-weight-bold text-hover-primary font-size-lg title-item-search mb-1">${item.title}</p>`;
                                html += `<span class="text-muted font-weight-bold">ID : ${item.id}</span>`;
                                html += '</div>';
                                html += '<div class="d-flex align-items-center mt-lg-0 mt-3">';
                                html += '<button class="btn btn-icon btn-light btn-sm" onclick="insertItemToGroup(' + item.id + ',' + GROUP_ID + ')">';
                                html += '<span class="svg-icon svg-icon-success">';
                                html += '<span class="svg-icon svg-icon-md">';
                                html += '<i class="flaticon-download"></i>';
                                html += '</span>';
                                html += '</span>';
                                html += '</button>';
                                html += '</div>';
                                html += '</div>';
                                $('#result-list-item').append(html)
                            })
                        }
                    })
                }
            }.bind(this), 300);
        });

        function insertItemToGroup(item_id, group_id) {
            $(document).ready(function () {
                $.ajax({
                    url: "{{route('insert_item_to_group')}}",
                    type: "POST",
                    data: {
                        item_id: item_id,
                        group_id: group_id,
                    },
                    success: function (res) {
                        if (res.error_message) {
                            toastr.success(res.message);
                        } else {
                            $('#nestable .dd-list').html('')
                            res.items.forEach(function (item) {
                                appenDataTable(item, group_id)
                            });
                            // output initial serialised data
                            updateOutput($('#nestable').data('output', $('#nestable-output')));
                            toastr.success(res.message);
                        }
                    }
                })
            })
        }

        function deleteGroup(group_id) {
            document.getElementById('form_delete').action = '/admin/{{$module}}/' + group_id;
        }

        function setItemInGroupModal(group_id) {
            keyword_input.attr('data-group_id', group_id);
            $('#result_data').html('')
            $(document).ready(function () {
                $.ajax({
                    url: "{{route('get_item_in_group')}}",
                    type: "POST",
                    data: {
                        group_id: group_id,
                    },
                    success: function (res) {
                        $('#nestable .dd-list').html('')
                        res.forEach(function (item) {
                            appenDataTable(item, group_id)
                        });
                        first_open_modal = true;
                        // output initial serialised data
                        updateOutput($('#nestable').data('output', $('#nestable-output')));
                    }
                })
            })
        }

        function deleteItemInGroup(item_id, group_id) {
            $(document).ready(function () {
                $.ajax({
                    url: "{{route('delete_item_in_group')}}",
                    type: "POST",
                    data: {
                        group_id: group_id,
                        item_id: item_id,
                    },
                    success: function (res) {
                        $('#nestable .dd-list').html('')
                        res.items.forEach(function (item) {
                            appenDataTable(item, group_id)
                        })
                        toastr.success(res.message)
                    }
                })
            })
        }

        function appenDataTable(item, group_id) {
            let html = '';
            html += `<li class="dd-item" data-id="${item.id}">`;
            html += `<div class="dd-handle"><p class="title-item">${item.title}</p>`;
            html += `<div style="position:absolute;right:3rem;top:.3rem">`;
            html += `<button class="btn btn-sm btn-danger" onclick="if (confirm('Xác nhận xoá item?')){deleteItemInGroup(${item.id},${group_id})}">Xoá</button>`;
            html += `</div>`;
            html += `</div>`;
            html += `</li>`;
            $('#nestable .dd-list').append(html);
        }
    </script>
    {{--    render button add--}}
    <script>
        $(document).ready(function () {
            let html = '<button type="button" class="btn-shadow-hover font-weight-bold mr-2 btn btn-light-danger" data-toggle="modal" data-target="#confirm_delete_muti"> <i class="flaticon2-trash"></i>Xoá đã chọn</button>';
            html += '<a href="{{route("$module.create")}}" class="btn-shadow-hover font-weight-bold mr-2 btn btn-light-success"> <i class="flaticon2-add"></i>Tạo mới</a>';
            $('#submit_form').html(html)
        })
    </script>
    <script>
        function selectAllItem() {
            if ($('#master_chk').is(':checked', true)) {
                $(".sub_chk").prop('checked', true);
            } else {
                $(".sub_chk").prop('checked', false);
            }
        }

        function deleteMultiRecord(url_action) {
            var allVals = [];
            $(".sub_chk:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });


            if (allVals.length <= 0) {
                alert("Chưa chọn mục nào !");
            } else {
                var join_selected_values = allVals.join(",");
                $.ajax({
                    url: url_action,
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token_jwt,
                    },
                    data: {
                        ids: join_selected_values,
                    },
                    success: function (data) {
                        showToastSuccess(data.message)
                        setTimeout("location.reload(true);", 1000);
                    },
                    error: function (data) {
                        alert(data.responseText);
                    }
                });
                $.each(allVals, function (index, value) {
                    $('table tr').filter("[data-row-id='" + value + "']").remove();
                });
            }
        }
    </script>

@endsection
