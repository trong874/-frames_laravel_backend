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
    <div class="card card-custom mb-4">
        <div class="card-header">
            <h2 class="card-title">
                Tìm kiếm
            </h2>
        </div>
        <div class="card-body">
            <form action="" id="form-search">
                <input type="hidden" name="module" value="{{$module}}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group container">
                            <span class="form-label">ID</span>
                            <input type="text" name="id" class="form-control" placeholder="Nhập ID" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group container">
                            <span class="form-label">Tiêu đề</span>
                            <input type="text" name="title" class="form-control" placeholder="Nhập tiêu đề">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group container">
                            <span class="form-label">Danh mục</span>
                            <select class="form-control select2" id="category_id" name="category_id">
                                <option label="Label"></option>
                                <?php get_option_categories($categories)  ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group container">
                            <span class="form-label">Vị trí</span>
                            <input name="position" class="form-control" type="text" placeholder="Vị trí ( Position )">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group container">
                            <span class="form-label">Trạng thái</span>
                            <select class="form-control" name="status">
                                <option value="">-- Trạng thái --</option>
                                <option value="1">Hoạt động</option>
                                <option value="0">Ngưng hoạt động</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group container">
                            <span class="form-label">Khoảng thời gian</span>
                            <div class="input-daterange input-group" id="kt_datepicker_5">
                                <input type="text" class="form-control datepicker-custom" name="date_from" autocomplete="off" placeholder="Từ ngày">
                                <div class="input-group-append"><span class="input-group-text"><i class="la la-ellipsis-h"></i></span></div>
                                <input type="text" class="form-control datepicker-custom" name="date_to" autocomplete="off" placeholder="Đến ngày">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="container">
                            <button type="button" class="btn btn-success" id="submit-form-search">Tìm</button>
                        </div>
                    </div>
                    <div class="col-md-19"></div>
                </div>
            </form>
        </div>
    </div>
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
    <script src="{{asset('js/pages/crud/forms/widgets/select2.js')}}"></script>
    <script src="{{asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js')}}"></script>

    @if(Session::has('message'))
        <script>
            toastr.success("{{session()->pull('message')}}");
        </script>
    @endif
    <!-- App scripts -->
    <script>
        setDataTable({url:'{!! route('ajax_get_item',$module) !!}'})

        function deleteItem(item_id) {
            document.getElementById('form_delete').action = '/admin/{{$module}}/' + item_id;
        }
    </script>
    {{--    render button add--}}
    <script>
        $(document).ready(function () {
            let html =  '<button type="button" class="btn-shadow-hover font-weight-bold mr-2 btn btn-light-danger" data-toggle="modal" data-target="#confirm_delete_muti"> <i class="flaticon2-trash"></i>Xoá đã chọn</button>';
                html +=  '<a href="{{route("$module.create")}}" class="btn-shadow-hover font-weight-bold mr-2 btn btn-light-success"> <i class="flaticon2-add"></i>Tạo mới</a>';
            $('#submit_form').html(html)
        })
    </script>
    <script>
    </script>
@endsection
