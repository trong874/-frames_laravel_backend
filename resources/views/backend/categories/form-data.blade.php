@extends('backend.layout.default')
@section('meta-data')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('styles')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{asset('backend/ckfinder/ckfinder.js')}}"></script>
    <script src="{{asset('backend/ckeditor/ckeditor.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/nestable.css')}}">
    <link rel="stylesheet" href="{{asset('css/product-form.css')}}">
@endsection
@section('content')
    <!--begin::Card-->
    <!--begin::Form-->
    <?php
    if (isset($category)) {
        $action = route("$module.update", $category);
    } else {
        $action = route("$module.store");
    }
    ?>
    <form method="POST" action="{{$action}}" id="formMain">
        @if(isset($category))
            @method('PUT')
        @endif
        @csrf
        <div class="row">
            <div class="card col-9">
                <div class="card-body">
                    <div class="form-group row mt-3">
                        <label class="col-lg-1 col-form-label text-right">Tiêu đề</label>
                        <div class="col-lg-11">
                            <input type="text" name="title" id="title" class="form-control" placeholder="Title"
                                   value="{{@$category->title}}" onkeyup="changeTitleToSlug()" required/>
                            <span class="form-text text-muted">Please enter your Title</span>
                        </div>
                        <input type="hidden" id="slug" name="slug" value="{{@$category->slug}}">
                        <input type="hidden" name="module" value="{{$category->module??$module}}"/>
                    </div>
                    <div class="separator separator-dashed my-10"></div>
                    <div class="form-group row mt-3">
                        <label class="col-lg-1 col-form-label text-right">Danh mục cha</label>
                        <div class="col-lg-6">
                            <select class="form-control select2" id="category_id" name="group_id">
                                <option label="Label"></option>
                                @if(isset($category))
                                    <?php get_option_old_parent_categories($groups,$category); ?>
                                @else
                                    <?php get_option_categories($groups); ?>
                                @endif
                            </select>
                        </div>
                        <label class="col-lg-1 col-form-label text-right">Vị trí</label>
                        <div class="col-lg-4">
                            <input type="text" name="position" class="form-control" placeholder="Vị trí..." value="{{@$category->position}}">
                        </div>
                    </div>
                    <div class="separator separator-dashed my-10"></div>
                    <div class="form-group row mt-3">
                        <label class="col-lg-1 col-form-label text-right">Mô tả</label>
                        <div class="col-lg-11">
                        <textarea name="description" id="description_input"
                                  required>{{@$category->description}}</textarea>
                            <script>
                                // Replace the <textarea id="editor1"> with a CKEditor 4
                                // instance, using default configuration.
                                var editor = CKEDITOR.replace('description_input', {
                                    filebrowserBrowseUrl: '/backend/ckfinder/ckfinder.html',
                                    filebrowserUploadUrl: '/backend/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                    filebrowserWindowWidth: '1000',
                                    filebrowserWindowHeight: '700'
                                });
                                CKFinder.setupCKEditor(editor);
                            </script>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-10"></div>

                    <div class="form-group row mt-3">
                        <label class="col-lg-1 col-form-label text-right">Nội dung</label>
                        <div class="col-lg-11">
                            <textarea name="content" id="content_input">{{@$category->content}}</textarea>
                            <script>
                                // Replace the <textarea id="editor1"> with a CKEditor 4
                                // instance, using default configuration.
                                var editor = CKEDITOR.replace('content_input', {
                                    filebrowserBrowseUrl: '/backend/ckfinder/ckfinder.html',
                                    filebrowserUploadUrl: '/backend/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                    filebrowserWindowWidth: '1000',
                                    filebrowserWindowHeight: '700'
                                });
                                CKFinder.setupCKEditor(editor);
                            </script>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-10"></div>
                    <input type="hidden" name="author_id" value="{{ $category['author_id'] ?? @Auth::user()->id}}">
                    <div class="form-group row mt-3">
                        <label class="col-lg-1 col-form-label text-right">URL</label>
                        <div class="col-lg-3">
                            <input type="text" name="url" class="form-control" id="url" placeholder="URL"
                                   value="{{@$category->url}}"/>
                            <span class="form-text text-muted">Please enter URL</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-10"></div>
                    <div class="form-group row mt-3">
                        <div class="col-md-4">
                            <label for="locale">Ảnh đại diện:</label>
                            <div class="position-relative">
                                <div class="fileinput ck-parent" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px"  onclick="selectFileWithCKFinder('image_avatar')">
                                        <img class="ck-thumb" id="image_avatar"
                                             src="{{(isset($category->image))? $category->image : asset('/media/demos/empty.jpg')}}"
                                             alt="">
                                        <input class="ck-input" id="image_avatar_input" type="hidden" name="image"
                                               value="{{@$category->image}}">

                                    </div>
                                    <div class="button-action">
                                        <span class="" onclick="deleteImage('image_avatar')"><i class="icon-xl la la-times-circle-o"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label for="locale">Ảnh mở rộng:</label>
                            <div class="card">
                                <div class="card-body p-3 ck-parent" style="min-height: 148px">
                                    <input class="image_input_text" type="hidden" name="image_extension"
                                           value="{{@$category->image_extension}}">
                                    <div class="sortable grid">
                                        @if(@$category->image_extension)
                                            @foreach(explode('|',$category->image_extension) as $item)
                                                <div class="image-preview-box">
                                                    <img src="{{$item}}" alt="" data-input="{{$item}}">
                                                    <a rel="8" class="btn btn-xs  btn-icon btn-danger btn_delete_image"
                                                       data-toggle="modal" data-target="#deleteModal"><i
                                                            class="la la-close"></i></a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <a class="btn btn-success ck-popup-multiply" style="margin-top: 15px;">
                                        <i class="la la-cloud-upload-alt"></i> Chọn hình
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-md-4">
                            <label for="locale">Ảnh Banner:</label>
                            <div class="position-relative">
                                <div class="fileinput ck-parent" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px" onclick="selectFileWithCKFinder('image_banner')">

                                        <img class="ck-thumb" id="image_banner"
                                             src="{{(isset($category->image_banner))? $category->image_banner : asset('/media/demos/empty.jpg')}}"
                                             alt="">
                                        <input class="ck-input image_banner" id="image_banner_input" type="hidden"
                                               name="image_banner" value="{{@$category->image_banner}}">

                                    </div>
                                    <div class="button-action">
                                        <span class="" onclick="deleteImage('image_banner')"><i class="icon-xl la la-times-circle-o"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <label for="locale">Ảnh icon:</label>
                            <div class="position-relative">
                                <div class="fileinput ck-parent" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px" onclick="selectFileWithCKFinder('image_icon')">
                                        <img class="ck-thumb" id="image_icon"
                                             src="{{(isset($category->image_icon))? $category->image_icon : asset('/media/demos/empty.jpg')}}"
                                             alt="">
                                        <input class="ck-input image_icon" id="image_icon_input" type="hidden"
                                               name="image_icon" value="{{@$category->image_icon}}">

                                    </div>
                                    <div class="button-action">
                                        <span class="" onclick="deleteImage('image_icon')"><i class="icon-xl la la-times-circle-o"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Trạng thái</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select name="status" class="form-control">
                                <option value="1" @if(@$category)@if($category->status == 1) selected @endif @endif>Hoạt động</option>
                                <option value="0"  @if(@$category)@if($category->status == 0) selected @endif @endif>Ngừng hoạt động</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ngày hết hạn</label>
                            <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                <input type="text" name="ended_at" class="form-control datetimepicker-input" data-target="#kt_datetimepicker_2" value="{{@$category->ended_at}}" placeholder="Ngày hết hạn" data-toggle="datetimepicker">
                                <div class="input-group-append" data-target="#kt_datetimepicker_2" data-toggle="datetimepicker">
															<span class="input-group-text">
																<i class="ki ki-calendar"></i>
															</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Thứ tự</label>
                            <input type="text" name="order" class="form-control" placeholder="Thứ tự" value="{{@$category->order}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!--end::Form-->
    <!--end::Card-->
@endsection
@section('scripts')
    <script src="{{asset('js/form-data-item.js')}}"></script>
    <script src="{{asset('js/jquery.nestable.js')}}"></script>
    <script src="{{asset('js/jquery.sortable.js')}}"></script>
    <script src="{{asset('js/pages/crud/forms/widgets/select2.js')}}"></script>
    <script src="{{asset('js/pages/features/miscellaneous/sweetalert2.js')}}"></script>
    @if(Session::has('message'))
        <script>
            $(document).ready(function () {
                toastr.success('{{ session()->pull('message') }}')
            })
        </script>
    @endif
    <script>
        $(document).ready(function () {
            $('#submit_form').html(
                '<button type="button" class="btn-shadow-hover font-weight-bold mr-2 btn btn-light-success"> <i class="flaticon2-check-mark"></i>'+'{{@$category ? "Chỉnh sửa" : "Thêm mới"}}'+'</button>'
            )
        })
        $('#submit_form').on('click',function () {
            $('#formMain').submit();
        })
    </script>
@endsection
