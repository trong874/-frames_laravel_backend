@extends('backend.layout.default')
@section('styles')
    <script src="{{asset('backend/ckfinder/ckfinder.js')}}"></script>
    <script src="{{asset('backend/ckeditor/ckeditor.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/nestable.css')}}">
    <link rel="stylesheet" href="{{asset('css/product-form.css')}}">
@endsection
@section('content')
    <form action="{{route("setting.update",'-')}}" method="POST" id="formMain">
        @method('PUT')
        @csrf
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">{{__('Cấu hình chung')}}</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="example-preview">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home">
																	<span class="nav-icon">
																		<i class="flaticon2-chat-1"></i>
																	</span>
                                <span class="nav-text">{{__('Thông tin website')}}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
                               aria-controls="profile">
																	<span class="nav-icon">
																		<i class="flaticon2-layers-1"></i>
																	</span>
                                <span class="nav-text">Email</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact"
                               aria-controls="contact">
																	<span class="nav-icon">
																		<i class="flaticon2-rocket-1"></i>
																	</span>
                                <span class="nav-text">Mã nhúng script</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-5" id="myTabContent">
                        <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                            @foreach(config('setting.website-info') as $key => $setting)
                                <div class="form-group">
                                    <label>{{$setting['label']}}</label>
                                    @if($setting['type'] == 'text')
                                        <input type="text" class="form-control" name="{{$key}}"
                                               value="{{@$configs[$key]}}">
                                    @endif
                                    @if($setting['type'] == 'img')
                                        <div class="select-img">
                                            <div class="fileinput ck-parent" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 100px; height: 100px"
                                                     onclick="selectFileWithCKFinder('{{$key}}')">
                                                    <img class="ck-thumb" id="{{$key}}"
                                                         src="{{@$configs[$key] ?? '/media/demos/empty.jpg'}}" alt="">
                                                    <input class="ck-input {{$key}}" id="{{$key}}_input" type="hidden"
                                                           name="{{$key}}" value="{{@$configs[$key]}}">
                                                </div>
                                                <div class="button-action">
                                                    <span class="" onclick="deleteImage('{{$key}}')"><i
                                                            class="icon-xl la la-times-circle-o"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($setting['type'] == 'html')
                                        <textarea name="{{$key}}" id="{{$key}}">{{@$configs[$key]}}</textarea>
                                        <script>
                                            // Replace the <textarea id="editor1"> with a CKEditor 4
                                            // instance, using default configuration.
                                            var editor = CKEDITOR.replace('{{$key}}', {
                                                filebrowserBrowseUrl: '/backend/ckfinder/ckfinder.html',
                                                filebrowserUploadUrl: '/backend/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                                filebrowserWindowWidth: '1000',
                                                filebrowserWindowHeight: '700'
                                            });
                                            CKFinder.setupCKEditor(editor);
                                        </script>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Tab
                            content
                            2
                        </div>
                        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Tab
                            content
                            3
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script src="{{asset('js/form-data-item.js')}}"></script>
    <script src="{{asset('js/jquery.nestable.js')}}"></script>
    <script src="{{asset('js/jquery.sortable.js')}}"></script>
    <script src="{{asset('js/pages/crud/forms/widgets/select2.js')}}"></script>
    <script src="{{asset('js/pages/features/miscellaneous/sweetalert2.js')}}"></script>
    <script src="{{asset('js\pages\crud\forms\widgets\bootstrap-datetimepicker.js')}}"></script>
    <script src="{{asset('plugins/custom/jQuery-Mask-Plugin-master/disk/jquery.mask.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('#submit_form').html(
                '<button type="button" class="btn-shadow-hover font-weight-bold mr-2 btn btn-light-success"> <i class="flaticon2-checkmark"></i>Cập nhật</button>'
            )
        })
        $('#submit_form').on('click', function () {
            $('#formMain').submit();
        })
        @if(session()->has('message'))
        $(document).ready(function () {
            toastr.success("{{ session()->pull('message') }}")
        })
        @endif
    </script>

@endsection
