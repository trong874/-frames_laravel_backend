@extends('backend.layout.default')
@section('meta-data')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('styles')
    <script src="{{asset('backend/ckfinder/ckfinder.js')}}"></script>
    <script src="{{asset('backend/ckeditor/ckeditor.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/nestable.css')}}">
    <link rel="stylesheet" href="{{asset('css/product-form.css')}}">
@endsection
@section('content')
    <!--begin::Card-->
    <!--begin::Form-->
    <?php
    if (isset($item)) {
        $action = route("$module.update", $item);
    } else {
        $action = route("$module.store");
    }
    ?>

    <form method="POST" action="{{$action}}" id="formMain">
        @if(isset($item))
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
                                   value="{{@$item->title}}" onkeyup="changeTitleToSlug()" required/>
                            <span class="form-text text-muted">Please enter your Title</span>
                        </div>
                        <input type="hidden" id="slug" name="slug" value="{{@$item->slug}}">
                        <input type="hidden" name="module" value="{{$item->module??$module}}"/>
                    </div>
                    <div class="separator separator-dashed my-10"></div>
                    <div class="form-group row mt-3">
                        <label class="col-lg-2 col-form-label text-right">Danh mục</label>
                        <div class="col-lg-5">
                            <select class="form-control select2 select2-basic" name="group_id[]" multiple="multiple">
                                <optgroup label="Chọn danh mục">
                                    @if(isset($item))
                                        {{ get_option_old_categories($categories,$item)}}
                                    @else
                                        {{ get_option_categories($categories)}}
                                    @endif
                                </optgroup>
                            </select>
                        </div>
                        <label class="col-lg-1 col-form-label text-right">Vị trí</label>
                        <div class="col-lg-4">
                            <input type="text" name="position" class="form-control" placeholder="Vị trí..." value="{{isset($item['position']) ? $item['position'] : ''}}">
                        </div>
                    </div>
                    <div class="separator separator-dashed my-10"></div>

                    <div class="form-group row mt-3">
                        <label class="col-lg-1 col-form-label text-right">Mô tả</label>
                        <div class="col-lg-11">
                        <textarea name="description" id="description_input"
                                  required>{{@$item->description}}</textarea>
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
                            <textarea name="content" id="content_input">{{@$item->content}}</textarea>
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
                    <input type="hidden" name="author_id" value="{{ @Auth::user()->id}}">
                    <div class="form-group row mt-3">
                        <label class="col-lg-1 col-form-label text-right">URL</label>
                        <div class="col-lg-11">
                            <input type="text" name="url" class="form-control" id="url" placeholder="URL"
                                   value="{{$item->url??''}}"/>
                            <span class="form-text text-muted">Please enter URL</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-10"></div>
                    <div class="form-group row mt-3">
                        <div class="col-md-4">
                            <label for="locale">Ảnh đại diện:</label>
                            <div class="position-relative">
                                <div class="fileinput ck-parent" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px"  onclick="selectFileWithCKFinder('.image_avatar')">
                                        <img class="ck-thumb image_avatar" src="{{(isset($item->image))? $item->image : asset('/media/demos/empty.jpg')}}" alt="">
                                        <input class="ck-input image_avatar" type="hidden" name="image" value="{{@$item->image}}">

                                    </div>
                                    <div class="button-action">
                                        <span class="" onclick="deleteImage('.image_avatar')"><i class="icon-xl la la-times-circle-o"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <label for="locale">Ảnh mở rộng:</label>
                            <div class="card">
                                <div class="card-body p-3 ck-parent" style="min-height: 148px">
                                    <input class="image_input_text" type="hidden" name="image_extension"
                                           value="{{@$item->image_extension}}">
                                    <div class="sortable grid">
                                        @if(@$item->image_extension)
                                            @foreach(explode('|',$item->image_extension) as $item)
                                                <div class="image-preview-box">
                                                    <img src="{{$item}}" alt="" data-input="{{$item}}">
                                                    <a rel="8" class="btn btn-xs  btn-icon btn-danger btn_delete_image"
                                                       data-toggle="modal" data-target="#deleteModal"><i
                                                            class="la la-close"></i></a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <style>

                                    </style>
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
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px" onclick="selectFileWithCKFinder('.image_banner')">

                                        <img class="ck-thumb image_banner" src="{{(isset($item->image_banner))? $item->image_banner : asset('/media/demos/empty.jpg')}}"
                                             alt="">
                                        <input class="ck-input image_banner" type="hidden" name="image_banner" value="{{@$item->image_banner}}">
                                    </div>
                                    <div class="button-action">
                                        <span class="" onclick="deleteImage('.image_banner')"><i class="icon-xl la la-times-circle-o"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="locale">Ảnh icon:</label>
                            <div class="position-relative">
                                <div class="fileinput ck-parent" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 100px; height: 100px" onclick="selectFileWithCKFinder('.image_icon')">
                                        <img class="ck-thumb image_icon" src="{{(isset($item->image_icon))? $item->image_icon : asset('/media/demos/empty.jpg')}}"
                                             alt="">
                                        <input class="ck-input image_icon" id="image_icon_input" type="hidden"
                                               name="image_icon" value="{{@$item->image_icon}}">

                                    </div>
                                    <div class="button-action">
                                        <span class="" onclick="deleteImage('.image_icon')"><i class="icon-xl la la-times-circle-o"></i></span>
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
                                <option value="1" {{(isset($item) && $item->status == 1) ? 'selected' : '' }}> Hoạt động</option>
                                <option value="0" {{(isset($item) && $item->status == 0) ? 'selected' : '' }}>Ngừng hoạt động</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ngày hết hạn</label>
                            <div class="input-group date" id="kt_datetimepicker_2" data-target-input="nearest">
                                <input type="text" name="ended_at" class="form-control datetimepicker-input"
                                       data-target="#kt_datetimepicker_2" value="{{ @$item->ended_at }}"
                                       placeholder="Ngày hết hạn" data-toggle="datetimepicker">
                                <div class="input-group-append" data-target="#kt_datetimepicker_2"
                                     data-toggle="datetimepicker">
															<span class="input-group-text">
																<i class="ki ki-calendar"></i>
															</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Thứ tự</label>
                            <input type="text" name="order" class="form-control" placeholder="Thứ tự" value="{{(isset($item)) ? $item['order'] : ''}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="row mt-3">
                <div class="card card-custom gutter-b col-9">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">
                                Cấu hình giá bán <i class="mr-2"></i>
                                <span
                                    class="d-block text-muted pt-2 font-size-sm">Thiết lập giá bán và % giảm giá</span>
                            </h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-12 col-md-4">
                                <label class="form-control-label">Giá bán (đ)</label>
                                <input type="text" id="price_old" name="price_old" value="{{$item->price_old ?? 0}}"
                                       placeholder="Giá bán (đ)"
                                       class="form-control input-price " maxlength="19"
                                       onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-control-label">Giảm giá (%)</label>
                                <input type="text" id="percent_sale" name="percent_sale"
                                       value="{{$item->percent_sale ?? 0}}"
                                       placeholder="Giảm giá (%)" maxlength="3" max="100" class="form-control "
                                       onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-control-label">Giá khuyến mãi (giá bán còn lại) (đ)</label>
                                <input type="text" id="price" name="price" value="{{$item->price ?? 0}}" placeholder="Giá khuyến mãi (giá bán còn lại) (đ)" class="form-control input-price " maxlength="19" onkeypress="return false;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-4"><span class="font-weight-boldest">Hiển thị: <p class="text-danger"><s
                                            id="display_price_old">0</s></p></span></div>
                            <div class="col-12 col-md-4"><span class="font-weight-boldest">Hiển thị: <p class="text-danger"
                                                                                                        id="display_percent_sale">0</p></span>
                            </div>
                            <div class="col-12 col-md-4"><span class="font-weight-boldest">Hiển thị: <p class="text-danger"
                                                                                                        id="display_price">0</p></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div class="row mt-3">
            <div class="card card-custom gutter-b col-9">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">
                            Thông tin bán hàng <i class="mr-2"></i>
                            <span class="d-block text-muted pt-2 font-size-sm">Thiết lập màu sắc , kích thước.</span>
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="container">
                        <button type="button" class="btn btn-primary" onclick="addOptionItem()">Thêm option</button>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-table-bordered table-list" id="table_attribute_item">
                                    <thead>
                                    <tr>
                                        <th>Tên Option</th>
                                        <th>Thuộc tính</th>
                                        <th>Giá tiền</th>
                                        <th>Số lượng trong kho</th>
                                        <th>Thao Tác</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    @if(isset($item))
                                        @if(isset($item['item_attributes']))
                                            @foreach($item['item_attributes'] as $key => $attribute)
                                                <tbody id="row_{{$key}}">
                                                <tr data-index_el="0">
                                                    <th rowspan="{{count($attribute->subitems)}}"><input type="text"
                                                                                                         value="{{$attribute['title']}}"
                                                                                                         class="form-control attribute_{{$key}}"
                                                                                                         placeholder="Màu sắc, Size..."
                                                                                                         name="attribute[{{$attribute['title']}}]"
                                                                                                         onkeyup="setNameInputSub(this.value,{{$key}});this.name = `attribute[${this.value}]`"
                                                                                                         required></th>
                                                @for($i = 0 ; $i < count($attribute->subitems) ; $i++)
                                                    @if($i !== 0)
                                                        <tr data-index_el="{{$i}}">
                                                            @endif
                                                            <td><input type="text"
                                                                       class="form-control sub_item_{{$key}}_{{$i}}"
                                                                       placeholder="Xanh, vàng đỏ..."
                                                                       name="attribute[{{$attribute['title']}}][{{$attribute->subitems[$i]['title']}}]"
                                                                       value="{{$attribute->subitems[$i]['title']}}"
                                                                       onkeyup="setNameInputOption(this.value,{{$key}},{{$i}});">
                                                            </td>

                                                            <td><input type="text"
                                                                       value="{{$attribute->subitems[$i]['price']}}"
                                                                       class="form-control price_subitem_{{$key}}_{{$i}}"
                                                                name="attribute[{{$attribute['title']}}][{{$attribute->subitems[$i]['title']}}][price]">
                                                            </td>
                                                            <td><input type="text"
                                                                       value="{{$attribute->subitems[$i]['quantity']}}"
                                                                       class="form-control qty_subitem_{{$key}}_{{$i}}"
                                                                name="attribute[{{$attribute['title']}}][{{$attribute->subitems[$i]['title']}}][qty]">
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-danger"  @if($i==0) disabled @else onclick="deleteRowInOption(this)" @endif>
                                                                    Xoá
                                                                </button>
                                                            </td>
                                                            @if($i==0)
                                                                <td rowspan="{{count($attribute->subitems)}}" style="height: 0"
                                                                    class="action_option">
                                                                    <button type="button" class="btn btn-sm btn-danger"
                                                                            style="height: 50%"
                                                                            onclick="deleteOption({{$key}})"><i
                                                                            class="far fa-trash-alt"></i></button>
                                                                    <br>
                                                                    <button type="button"
                                                                            class="btn btn-sm btn-success add_row_in_option"
                                                                            style="height: 50%"
                                                                            onclick="addRowInOption({{$key}})"><i
                                                                            class="fas fa-plus"></i></button>
                                                                </td>
                                                            @endif
                                                            @if($i !== 0)
                                                        </tr>
                                                    @endif
                                                @endfor
                                                </tbody>
                                            @endforeach
                                        @endif
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card card-custom gutter-b col-lg-9">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">
                            Tối ưu SEO <i class="mr-2"></i>
                            <span class="d-block text-muted pt-2 font-size-sm">Thiết lập các thẻ mô tả tối ưu nội dung tìm kiếm trên Google.</span>
                        </h3>
                    </div>

                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-12 col-md-12">
                            <label>Tiêu đề Trang (&lt;title&gt;)</label>
                            <input type="text" id="seo_title" name="seo_title"
                                   value="{{$item->seo_title ?? 'Áo thun trơn đẹp'}}" placeholder=""
                                   class="form-control ">
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="col-12 col-md-12">

                            <label>Mô Tả Trang ( &lt;meta Description&gt; )</label>
                            <input type="text" id="seo_description" name="seo_description"
                                   value="{{@$item->seo_description}}" placeholder=""
                                   class="form-control ">
                        </div>

                    </div>

                    <fieldset class="content-group">
                        <legend class="text-bold"
                                style="border-bottom: 1px solid #e5e5e5;font-size: 15px;padding-bottom: 10px;margin-bottom: 10px">
                            Khi lên top, page này sẽ hiển thị như sau:
                        </legend>
                        <div class="form-group">
                            <h3 id="google_title" class="title_google"
                                style="color:#1a0dab;font-size: 18px;font-family: arial,sans-serif;padding:0;margin: 0;">
                                Áo thun trơn đẹp</h3>
                            <div style="color:#006621;font-size: 14px;font-family: arial,sans-serif;">
                                <span class="prefix_url">{{ "https://$_SERVER[HTTP_HOST]/"}}</span><span
                                    id="google_slug" class="google_slug">ao-thun-tron-dep</span>
                            </div>
                            <div id="google_description" class="google_description"
                                 style="color: #545454;font-size: small;font-family: arial,sans-serif;"></div>
                        </div>
                    </fieldset>
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
    <script src="{{asset('js\pages\crud\forms\widgets\bootstrap-datetimepicker.js')}}"></script>
    <script src="{{asset('plugins/custom/jQuery-Mask-Plugin-master/disk/jquery.mask.js')}}"></script>
    <script src="{{asset('js/price_sale.js')}}"></script>
    <script src="{{ asset('js/sub-item.js') }}"></script>
    @if(Session::has('message'))
        <script>
            toastr.success("{{session()->pull('message')}}");
        </script>
    @endif
    <script>
        $(document).ready(function () {
            $('#submit_form').html(
                '<button type="button" class="btn-shadow-hover font-weight-bold mr-2 btn btn-light-success"> <i class="flaticon2-checkmark"></i>' + '{{@$item ? "Chỉnh sửa" : "Thêm mới"}}' + '</button>'
            )
        })
        $('#submit_form').on('click', function () {
            $('#formMain').submit();
        })
    </script>
    <script>
    </script>
@endsection
