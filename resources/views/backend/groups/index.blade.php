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
                    Xác nhận thao tác xóa item
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
                    <h5 class="modal-title" id="title_modal"></h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Tìm kiếm</label>
                        <input type="text" class="form-control" data-group_id="" id="input_search_item_group"
                               oninput="searchItem(this.value,this.getAttribute('data-group_id'))">
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
        var token_jwt = localStorage.getItem('token_jwt');
        $(function () {
            $('#kt_datatable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('ajax_get_group',$module) !!}',
                columns: [
                    {
                        data: null,
                        title: '<label class="checkbox"><input type="checkbox" id="master_chk" onclick="selectAllItem()"><span></span></label>',
                        orderable: false, searchable: false,
                        width: "20px",
                        class: "ckb_item",
                        render: function (data, type, row) {
                            return '<label class="checkbox"><input type="checkbox" class="sub_chk" data-id="' + row.id + '">&nbsp<span></span></label>';

                        }
                    },
                    {data: 'id', title: 'ID'},
                    {
                        data: 'title', title: 'Tiêu đề', render: function (data, type, row) {
                            return '<span class="backend-title">' + data + '</span>'
                        }
                    },
                    {
                        data: null,
                        title: 'Danh mục',
                        render: function (data, type, row) {
                            if (row.group) {
                                return "<span class=\"label label-pill label-inline label-center mr-2  label-primary \">" + row.group.title + "</span><br />";
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'image', title: 'Hình ảnh', orderable: false, searchable: false,
                        render: function (data, type, row) {
                            if (row.image == "" || row.image == null) {
                                return "<img class=\"image-item\" src=\"/media/demos/empty.jpg\" style=\"max-width: 40px;max-height: 40px\">";
                            } else {
                                return "<img class=\"image-item\" src=\"" + row.image + "\" style=\"max-width: 40px;max-height: 40px\">";
                            }
                        }
                    },
                    {data: 'order', title: 'Thứ tự'},
                    {data: 'position', title: 'Vị trí'},
                    {
                        data: 'status', title: 'Trạng thái', render: function (data, type, row) {

                            if (row.status == 1) {
                                return "<span class=\"label label-pill label-inline label-center mr-2  label-success \">" + "Hoạt động" + "</span>";
                            } else if (row.status == 2) {
                                return "<span class=\"label label-pill label-inline label-center mr-2 label-warning \">" + "" + "</span>";
                            } else {
                                return "<span class=\"label label-pill label-inline label-center mr-2 label-danger \">" + "Ngừng hoạt động" + "</span>";
                            }
                        }
                    },
                    {data: 'created_at', title: 'Thời gian'},
                    {
                        data: null, title: 'Thao tác', orderable: false, class: 'backend-', searchable: false,
                        render: function (data, type, row) {
                            return '<a href="/admin/{{$module}}/' + row.id + '/edit" class="btn btn-sm btn-clean btn-icon mr-2"><span class="svg-icon svg-icon-md"><i class="far fa-edit\n"></i></span></a>' +
                                '<button class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="modal" data-target="#items_in_group" onclick="setItemInGroupModal(' + row.id + ')"><span class="svg-icon svg-icon-md"><i class="fas fa-list-ul\n"></i></span></button>' +
                                '<button class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="modal" data-target="#confirm_delete" onclick="deleteGroup(' + row.id + ')"><span class="svg-icon svg-icon-md"><i class="fas fa-trash\n"></i></span></button>';
                        }
                    }
                ]
            });
        });
        function updateOutput(e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }

            $.ajax({
                url:"{{ route('index.change-order-in-group') }}",
                type:'POST',
                data: {
                    list:list.nestable('serialize'),
                    group_id: $('#input_search_item_group').attr('data-group_id'),
                },
                success: function (res) {
                    toastr.success(res.message)
                },
                error: function (request, status, error) {
                    console.log(request.responseText);
                }
            });
        }

        // Bấm modal lên phát set lại vị trí luôn
        $(document).ready(function () {
            $('#nestable').nestable().on('change', updateOutput);
        });
        function searchItem(value, group_id) {
            $.ajax({
                url: "{{route('items.search')}}",
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token_jwt,
                },
                data: {
                    search_query: value,
                    module: "{{$module}}",
                },
                success: function (res) {
                    if (res.message_error) {
                        $('#result_data').html('')
                    } else {
                        $('#result_data').html(' <div class="card">\n' +
                            '                            <div class="card-body" id="result-list-item">\n' +
                            '\n' +
                            '                            </div>\n' +
                            '                        </div>')
                        res.forEach(function (item) {
                            $('#result-list-item').append('<div class="alert alert-custom alert-notice alert-light-primary fade show mb-5">' +
                                '        <div class="symbol symbol-60 symbol-2by3 flex-shrink-0 mr-4">\n' +
                                '            <div class="symbol-label" style="background-image: url(' + item.image + ')"></div>\n' +
                                '        </div>\n' +
                                '        <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 mr-2">\n' +
                                '            <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1">' + item.title + '</a>\n' +
                                '            <span class="text-muted font-weight-bold">100k</span>\n' +
                                '        </div>\n' +
                                '        <div class="d-flex align-items-center mt-lg-0 mt-3">\n' +
                                '            <!--begin::Btn-->\n' +
                                '            <button class="btn btn-icon btn-light btn-sm" onclick="insertItemToGroup(' + item.id + ',' + group_id + ')">\n' +
                                '\t\t\t\t\t<span class="svg-icon svg-icon-success">\n' +
                                '\t\t\t\t\t<span class="svg-icon svg-icon-md">\n' +
                                '\t\t\t\t\t\t<img src="/media/svg/icons/Files/Import.svg"/>\n' +
                                '\t\t\t\t\t\t</span>\n' +
                                '\t\t\t\t\t</span>\n' +
                                '            </button>\n' +
                                '        </div>\n' +
                                '    </div>')
                        })
                    }
                }
            })
        }

        function insertItemToGroup(item_id, group_id) {
            $(document).ready(function () {
                $.ajax({
                    url: "{{route('insert_item_to_group')}}",
                    type: "POST",
                    headers: {
                        'Authorization': 'Bearer ' + token_jwt,
                    },
                    data: {
                        item_id: item_id,
                        group_id: group_id
                    },
                    success: function (res) {
                        if (res.error_message) {
                            showToastError(res.message)
                        } else {
                            $('#nestable .dd-list').html('')
                            res.items.forEach(function (item) {
                                appenDataTable(item, group_id)
                            })
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
            $('#input_search_item_group').attr('data-group_id', group_id);
            $('#result_data').html('')
            $(document).ready(function () {
                $.ajax({
                    url: "{{route('get_item_in_group')}}",
                    type: "POST",
                    headers: {
                        'Authorization': 'Bearer ' + token_jwt,
                    },
                    data: {
                        group_id: group_id,
                    },
                    success: function (res) {
                        $('#nestable .dd-list').html('')
                        res.forEach(function (item) {
                            appenDataTable(item, group_id)
                        });
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
                    headers: {
                        'Authorization': 'Bearer ' + token_jwt,
                    },
                    data: {
                        group_id: group_id,
                        item_id: item_id
                    },
                    success: function (res) {
                        $('#nestable .dd-list').html('')
                        res.items.forEach(function (item) {
                            //append data
                            appenDataTable(item, group_id)
                        })
                        showToastSuccess(res.message)
                    }
                })
            })
        }

        function appenDataTable(item, group_id) {
            let html = '';
            html += `<li class="dd-item" data-id="${item.id}">`;
            html += `<div class="dd-handle">${item.title}`;
            html += `<div style="position:absolute;right:2rem;top:.3rem">`;
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
            $('#submit_form').html(
                '<button type="button" class="btn-shadow-hover font-weight-bold mr-2 btn btn-light-danger" data-toggle="modal" data-target="#confirm_delete_muti"> <i class="flaticon2-delete"></i>Xoá đã chọn</button><a href="{{route("$module.create")}}" class="btn-shadow-hover font-weight-bold mr-2 btn btn-light-success"> <i class="flaticon2-plus"></i>Tạo mới</a>'
            )
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
