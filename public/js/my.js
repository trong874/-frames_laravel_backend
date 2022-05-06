window.dataLayer = window.dataLayer || [];

function gtag() {
    dataLayer.push(arguments)
}

gtag('js', new Date());

gtag('config', 'UA-153115189-2');

window.dataLayer = window.dataLayer || [];

function gtag() {
    dataLayer.push(arguments);
}

gtag('js', new Date());

gtag('config', 'AW-607164289');

function setShowBtnAction() {
        $('#expand-all').toggle();
        $('#collapse-all').toggle();
}

toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-center",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

function setDataTable(route) {
    // const newURL =
    //     window.location.protocol +
    //     "://" +
    //     window.location.host +
    //     "/" +
    //     window.location.pathname;
    const pathArray = window.location.pathname.split("/");
    const module = pathArray[2];
    $('#kt_datatable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: route,
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
            {
                data: 'created_at', title: 'Thời gian', render: function (data) {
                    return new Date(data).toLocaleDateString();
                }
            },
            {
                data: null, title: 'Thao tác', orderable: false, searchable: false,
                render: function (data, type, row) {
                    let html = '';
                    html += '<a href="/admin/' + module + '/' + row.id + '/edit" class="btn btn-sm btn-clean btn-icon mr-2" target="_blank"><span class="svg-icon svg-icon-md"><i class="far fa-edit\n"></i></span></a>';
                    html += '<button class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="modal" data-target="#confirm_delete" onclick="deleteItem(' + row.id + ')"><span class="svg-icon svg-icon-md"><i class="fas fa-trash\n"></i></span></button>';
                    html += '<a href="/admin/duplicate-item/' + row.id + '" class="btn btn-sm btn-clean btn-icon" title="Replicate">'
                    html += '<i class="far fa-copy"></i>'
                    html += '</a>'
                    return html;
                }
            }
        ]
    });
}

$('#submit-form-search').on('click', function () {
    let data = $('#form-search').serializeArray().reduce(function (obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});

    setDataTable({
        url: '/admin/filter-item',
        data: {data},
    })
});

function changeTitleToSlug(title) {
    var slug;

    //Đổi chữ hoa thành chữ thường
    slug = title.toLowerCase();

    //Đổi ký tự có dấu thành không dấu
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');
    //Xóa các ký tự đặt biệt
    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\<|\'|\"|\:|\;|_/gi, '');
    //Đổi khoảng trắng thành ký tự gạch ngang
    slug = slug.replace(/ /gi, "-");
    //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
    //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
    slug = slug.replace(/\-\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-/gi, '-');
    slug = slug.replace(/\-\-/gi, '-');
    //Xóa các ký tự gạch ngang ở đầu và cuối
    slug = '@' + slug + '@';
    slug = slug.replace(/\@\-|\-\@|\@/gi, '');
    //In slug ra textbox có id “slug”
    return slug;
}


function collectTextNodes(element, texts) {
    for (var child= element.firstChild; child!==null; child= child.nextSibling) {
        if (child.nodeType===3)
            texts.push(child);
        else if (child.nodeType===1)
            collectTextNodes(child, texts);
    }
}
function getTextWithSpaces(element) {
    var texts= [];
    collectTextNodes(element, texts);
    for (var i= texts.length; i-->0;)
        texts[i]= texts[i].data;
    return texts.join('>');
}

$('#keyword').on('input', function () {
    const KEYWORD = changeTitleToSlug($(this).val());
    let index = 0;
    $("#kt_aside_menu .menu-nav li").each(function () {
        var textone = getTextWithSpaces(this);
        textone = changeTitleToSlug(textone);
        $(this).toggle(textone.indexOf(KEYWORD) > -1);
        if (textone.indexOf(KEYWORD) > -1) {
            let pre_el = $(this).prev();
            let i = $(this).index();
            while (i) {
                i--;
                if (pre_el.hasClass('menu-section') && !$(this).hasClass('menu-section')) {
                    pre_el.show();
                    break;
                }
                pre_el = pre_el.prev();
            }
            index++;
        }
    });

    $("#kt_aside_menu .menu-nav .menu-item-submenu").each(function () {
        var textone = $(this).children('a').find('.menu-text').text().toLowerCase();
        textone = changeTitleToSlug(textone);
        if (textone.indexOf(KEYWORD) > -1 ) {
            $(this).find('.menu-subnav').children().show();
            let pre_el = $(this).prev();
            let i = $(this).index();
            while (i) {
                i--;
                if (pre_el.hasClass('menu-section')) {
                    pre_el.show();
                    break;
                }
                pre_el = pre_el.prev();
            }
        }
    });

    $("#kt_aside_menu .menu-nav .menu-section").each(function () {
        var textone = $(this).find('.menu-text').text().toLowerCase();
        textone = changeTitleToSlug(textone);
        if (textone.indexOf(KEYWORD) > -1) {
            let next_el = $(this).next();
            while (next_el.hasClass('menu-item')){
                next_el.show();
                next_el.find('.menu-subnav').children().show();
                next_el = next_el.next();
            }
        }
    })

    $("#kt_aside_menu .menu-nav li.menu-item-parent").hide()
});

