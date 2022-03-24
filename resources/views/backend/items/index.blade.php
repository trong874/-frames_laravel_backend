@extends('backend.layout.default')
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
    {{--    begin modal--}}
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
                            onclick="deleteMultiRecord('{{route('delete_multi_item')}}')">Xóa
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
                    timer: 750
                });
            })
        </script>
    @endif
    <!-- App scripts -->
    <script>
        $(function () {
            $('#kt_datatable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: '{!! route('ajax_get_item',$module) !!}',
                columns: [
                    {
                        data: null,
                        title: '<label class="checkbox"><input type="checkbox"  id="master_chk" onclick="selectAllItem()"><span></span></label>',
                        orderable: false, searchable: false,
                        width: "20px",
                        class: "ckb_item",
                        render: function (data, type, row) {
                            return '<label class="checkbox"><input type="checkbox" class="sub_chk" data-id="' + row.id + '">&nbsp<span></span></label>';

                        }
                    },
                    {data: 'id', title: 'ID'},
                    {data: 'title', title: 'Tiêu đề'},
                    {
                        data: null, title: 'Danh mục',
                        render: function (data, type, row) {
                            var temp = "";
                            $.each(row.groups, function (index, value) {
                                if (value.name == 'admin') {
                                    temp += "<span class=\"label label-pill label-inline label-center mr-2  label-primary \">" + value.title + "</span><br />";
                                } else {
                                    temp += "<span class=\"label label-pill label-inline label-center mr-2  label-success \">" + value.title + "</span><br />";
                                }

                                // console.log( value.title);
                            });
                            return temp;
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
                        data: null, title: 'Thao tác', orderable: false, searchable: false,
                        render: function (data, type, row) {
                            return '<a href="/admin/{{$module}}/' + row.id + '/edit" class="btn btn-sm btn-clean btn-icon mr-2"><span class="svg-icon svg-icon-md"><i class="far fa-edit\n"></i></span></a>' +
                                '<button class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="modal" data-target="#confirm_delete" onclick="deleteItem(' + row.id + ')"><span class="svg-icon svg-icon-md"><i class="fas fa-trash\n"></i></span></button>' +
                                '<a href="/admin/duplicate-item/'+row.id+'" class="btn btn-sm btn-clean btn-icon" title="Replicate">\n' +
                                '<i class="far fa-copy"></i>\n' +
                                '</a>';
                        }
                    }
                ]
            });
        });

        function deleteItem(item_id) {
            document.getElementById('form_delete').action = '/admin/{{$module}}/' + item_id;
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
        var token_jwt = localStorage.getItem('token_jwt')

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
