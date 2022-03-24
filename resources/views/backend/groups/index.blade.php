@extends('backend.layout.default')
@section('meta-data')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('plugins/custom/datatables/datatables.bundle.css')}}">
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
                    <div id="result_data">
                    </div>
                    <table class="table table-bordered table-hover table-checkable">
                        <thead>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                        </thead>
                        <tbody id="table_item_in_group">
                        </tbody>
                    </table>
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
    @if(Session::has('message'))
        <script>
            $(document).ready(function () {
                Swal.fire({
                    icon: "success",
                    title: "{{Session::pull('message')}}",
                    showConfirmButton: false,
                    timer: 1500
                });
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
                        data: null, title: 'Thao tác', orderable: false,class: 'backend-', searchable: false,
                        render: function (data, type, row) {
                            return '<a href="/admin/{{$module}}/' + row.id + '/edit" class="btn btn-sm btn-clean btn-icon mr-2"><span class="svg-icon svg-icon-md"><i class="far fa-edit\n"></i></span></a>' +
                                '<button class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="modal" data-target="#items_in_group" onclick="setItemInGroupModal(' + row.id + ')"><span class="svg-icon svg-icon-md"><i class="fas fa-list-ul\n"></i></span></button>' +
                                '<button class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="modal" data-target="#confirm_delete" onclick="deleteGroup(' + row.id + ')"><span class="svg-icon svg-icon-md"><i class="fas fa-trash\n"></i></span></button>';
                        }
                    }
                ]
            });
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
                                '            <button class="btn btn-icon btn-light btn-sm" id="btn_add_item" onclick="insertItemToGroup(' + item.id + ',' + group_id + ')">\n' +
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
                            $(document).ready(function () {
                                Swal.fire({
                                    icon: "error",
                                    title: res.error_message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            })
                        } else {
                            $('#table_item_in_group').html('')
                            let items_data = res.items;
                            let status;
                            items_data.forEach(function (item) {
                                status = null;
                                if (item.status == 1) {
                                    status = "<span class=\"label label-pill label-inline label-center mr-2  label-success \">" + "Hoạt động" + "</span>";
                                } else if (item.status == 2) {
                                    status = "<span class=\"label label-pill label-inline label-center mr-2 label-warning \">" + "" + "</span>";
                                } else {
                                    status = "<span class=\"label label-pill label-inline label-center mr-2 label-danger \">" + "Ngừng hoạt động" + "</span>";
                                }
                                appenDataTable(status, item, group_id)
                            })
                            $(document).ready(function () {
                                Swal.fire({
                                    icon: "success",
                                    title: res.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            })
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
                        $('#table_item_in_group').html('')
                        let items_data = res;
                        let status;
                        items_data.forEach(function (item) {
                            status = null;
                            if (item.status == 1) {
                                status = "<span class=\"label label-pill label-inline label-center mr-2  label-success \">" + "Hoạt động" + "</span>";
                            } else if (item.status == 2) {
                                status = "<span class=\"label label-pill label-inline label-center mr-2 label-warning \">" + "" + "</span>";
                            } else {
                                status = "<span class=\"label label-pill label-inline label-center mr-2 label-danger \">" + "Ngừng hoạt động" + "</span>";
                            }
                            appenDataTable(status, item, group_id)
                        })
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
                        $('#table_item_in_group').html('')
                        let status;
                        res.items.forEach(function (item) {
                            status = null;
                            if (item.status == 1) {
                                status = "<span class=\"label label-pill label-inline label-center mr-2  label-success \">" + "Hoạt động" + "</span>";
                            } else if (item.status == 2) {
                                status = "<span class=\"label label-pill label-inline label-center mr-2 label-warning \">" + "" + "</span>";
                            } else {
                                status = "<span class=\"label label-pill label-inline label-center mr-2 label-danger \">" + "Ngừng hoạt động" + "</span>";
                            }
                            //append data
                            appenDataTable(status, item, group_id)
                        })
                        $(document).ready(function () {
                            Swal.fire({
                                icon: "success",
                                title: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        })
                    }
                })
            })
        }

        function appenDataTable(status, item, group_id) {
            $('#table_item_in_group').append(
                '  <tr>\n' +
                '                            <td>' + item.id + '</td>\n' +
                '                            <td>' + item.title + '</td>\n' +
                '                            <td>' + status + '</td>\n' +
                '                            <td>\n' +
                '                                <button class="btn btn-icon btn-light btn-hover-primary btn-sm rachehe"\n' +
                '                                        onclick="if (confirm(\'Xác nhận xoá item?\')){deleteItemInGroup(' + item.id + ',' + group_id + ')}">\n' +
                '\t\t\t\t<span class="svg-icon svg-icon-md svg-icon-primary">\n' +
                '\t\t\t\t\t\t<!--begin::Svg Icon | path:assets/media/svg/icons/General/Trash.svg-->\n' +
                '\t\t\t\t\t\t<svg xmlns="http://www.w3.org/2000/svg"\n' +
                '                             xmlns:xlink="http://www.w3.org/1999/xlink"\n' +
                '                             width="24px" height="24px"\n' +
                '                             viewBox="0 0 24 24"\n' +
                '                             version="1.1">\n' +
                '\t\t\t\t\t\t\t<g stroke="none"\n' +
                '                               stroke-width="1" fill="none"\n' +
                '                               fill-rule="evenodd">\n' +
                '\t\t\t\t\t\t\t\t<rect x="0" y="0" width="24"\n' +
                '                                      height="24"></rect>\n' +
                '\t\t\t\t\t\t\t\t\t\t<path\n' +
                '                                            d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z"\n' +
                '                                            fill="#000000"\n' +
                '                                            fill-rule="nonzero"></path>\n' +
                '\t\t\t\t\t\t\t\t\t\t<path\n' +
                '                                            d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"\n' +
                '                                            fill="#000000"\n' +
                '                                            opacity="0.3"></path>\n' +
                '                            </g>\n' +
                '                        </svg>\n' +
                '                    <!--end::Svg Icon-->\n' +
                '                </span>\n' +
                '                                </button>\n' +
                '                            </td>\n' +
                '                        </tr>'
            )
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
                        Swal.fire({
                            icon: "success",
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
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
