@extends('backend.layout.default')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/custom/nestable/nestable.css')}}">
@endsection
@section('content')
    <div class="card">
        <div class="row card-body">
            <div class="col-sm-8">
                <div class="well">
                    <menu id="nestable-menu">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row" style="margin: 20px;float: right">
                                    <button type="button" class="btn btn-primary action-collapse mr-4" data-action="expand-all" id="expand-all" style="display: none">{{__('Mở rộng')}} <i class="flaticon2-next"></i></button>
                                    <button type="button" class="btn btn-primary action-collapse mr-4" data-action="collapse-all" id="collapse-all">{{__('Thu gọn')}} <i class="flaticon2-down"></i></button>
                                    <button type="button" class="btn btn-danger mr-4" data-toggle="modal"
                                            data-target="#confirm_delete_all">
                                        <i class="flaticon2-trash"></i>
                                        {{__('Xoá lựa chọn')}}
                                    </button>
                                    <a href="{{route("$module.create")}}" class="btn btn-success mr-4"><i class="flaticon2-add"></i> {{__('Thêm mới')}}</a>
                                </div>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="confirm_delete_all" data-backdrop="static" tabindex="-1"
                                 role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Thông báo</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Xác nhận vĩnh viễn các danh mục ?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light-primary font-weight-bold"
                                                    data-dismiss="modal">Close
                                            </button>
                                            <button type="button" class="btn btn-danger font-weight-bold" id="delete_all" data-url="{{route('delete_multi_category')}}">Xóa</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </menu>
                    {!! $nestable !!}
                </div>
            </div>
            <div class="col-sm-4">
            <div class="well">
                <div class="m-demo-icon">
                    <i class="flaticon-light icon-lg"></i> Kéo thả để sắp xếp danh mục
                </div>
            </div>
            </div>
        </div>
    </div>

{{--    Modal Confirem Delete Category--}}
    <div class="modal fade" id="confirm_delete" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
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
                    Xác nhận thao tác xóa danh mục
                </div>
                <div class="modal-footer">
                    <form action="" method="POST" id="form_delete">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger font-weight-bold">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
{{--    End Modal--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
@endsection

@section('scripts')
    <script src="{{asset('js/pages/features/miscellaneous/sweetalert2.js')}}"></script>
    <script src="{{asset('js/my.js')}}"></script>
    <script src="{{asset('js/jquery.nestable.js')}}"></script>
    <script>
        @if(session()->has('message'))
            toastr.info('{{ session()->pull('message') }}')
         @endif
        var token_jwt = localStorage.getItem('token_jwt');
        $(document).ready(function () {
            let first_unit = true;
            function updateOutput(e) {
                var list = e.length ? e : $(e.target),
                    output = list.data('output');
                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
                $.ajax({
                    method: "POST",
                    url: "{{route('change_order_category')}}",
                    headers: {
                        'Authorization': 'Bearer ' + token_jwt,
                    },
                    data: {
                        list: list.nestable('serialize'),
                    },
                    success:function (res) {
                        if(first_unit){
                            first_unit = !first_unit;
                        }else {
                            toastr.success(res);
                        }
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown)
                });
            }
            // activate Nestable for list 1
            $('#nestable').nestable()
                .on('change', function (e) {
                    if(e.target.tagName !== 'INPUT'){
                        updateOutput(e)
                    }
                });

            updateOutput($('#nestable').data('output', $('#nestable-output')));


            $('#nestable-menu').on('click', function (e) {
                var target = $(e.target), action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
                if(action === 'expand-all' || action === 'collapse-all'){
                    $('#expand-all').toggle();
                    $('#collapse-all').toggle();
                }
            });

        });
    </script>
    <script>
        $('.delete-category').on('click',function () {
            $('#form_delete').attr('action',$(this).data('url'));
        });
        function selectAllChild(id) {
            if ($(`#master-${id}`).is(':checked', true)) {
                $(`.sub_chk-${id}`).prop('checked', true);
            } else {
                $(`.sub_chk-${id}`).prop('checked', false);
            }
        }

        $('#delete_all').on('click', function (e) {
            var allVals = [];
            $(".checkbox_category:checked").each(function () {
                allVals.push($(this).attr('data-id'));
            });

            if (allVals.length <= 0) {
                alert("Chưa chọn mục nào !");
            } else {
                var join_selected_values = allVals.join(",");
                $.ajax({
                    url: $(this).data('url'),
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token_jwt,
                    },
                    data: 'ids=' + join_selected_values,
                    success: function (res) {
                        if(res.status == 'success'){
                            $(document).ready(function () {
                                Swal.fire({
                                    icon: "success",
                                    title: res.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            })
                            setTimeout(function () {
                                location.reload();
                            },1500)
                        }
                    },
                    error: function (data) {
                        alert(data.responseText);
                    }
                });
                $.each(allVals, function (index, value) {
                    $('table tr').filter("[data-row-id='" + value + "']").remove();
                });
            }
        });
    </script>
@endsection

