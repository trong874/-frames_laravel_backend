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

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear(),
        hours = d.getHours(),
        minutes = d.getMinutes();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return `${year}-${month}-${day}  ${hours}:${minutes}`;
}

function getSegment(index, array = window.location.pathname.split('/')) {
    array.splice(0, 1);
    return array[index];
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
    const module = getSegment(1);
    $('#kt_datatable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        destroy: true,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, 'Tất cả'],
        ],
        ajax: route,
        language: {
            url: '/plugins/custom/datatables/i18n/vietnam.json',
        },
        columns: [
            {
                data: null,
                title: '<label class="checkbox"><input type="checkbox"  id="master_chk" onclick="selectAllItem()"><span></span></label>',
                orderable: false,
                searchable: false,
                width: "10px",
                class: "ckb_item",
                render: function (data, type, row) {
                    return '<label class="checkbox"><input type="checkbox" class="sub_chk" data-id="' + row.id + '">&nbsp<span></span></label>';
                }
            },
            {data: 'id', title: 'ID'},
            {
                data: 'title',
                title: 'Tiêu đề',
                render: function (data, type, row) {
                    return `<p class="row-title">${row.title}</p>`
                }
            },
            {
                data: null, title: 'Danh mục',
                render: function (data, type, row) {
                    var temp = "";
                    $.each(row.groups, function (index, value) {
                        temp += "<span class=\"label label-pill label-inline label-center mr-2  label-primary \">" + value.title + "</span><br />";
                    });
                    return temp;
                }
            },
            {
                data: 'image', title: 'Hình ảnh', orderable: false, searchable: false,
                render: function (data, type, row) {
                    return `<img class="image-item" src="${row.image || '/media/demos/empty.jpg'}" >`;
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
                    return `<p class="row-time">${formatDate(data)}</p>`;
                }
            },
            {
                data: null,
                title: 'Thao tác',
                orderable: false,
                searchable: false,
                class: 'row-action',
                render: function (data, type, row) {
                    let html = '';
                    let module_is_group = module.indexOf('-group') > 0;
                    if (module_is_group) {
                        // là group
                        html += `<a href="/admin/${module}/${row.id}/edit" class="btn btn-sm btn-clean btn-icon mr-2"><span class="svg-icon svg-icon-md"><i class="far fa-edit"></i></span></a>`;
                        html += `<button class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="modal" data-target="#items_in_group" onclick="setItemInGroupModal(${row.id})"><span class="svg-icon svg-icon-md"><i class="fas fa-list-ul"></i></span></button>`;
                        html += `<button class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="modal" data-target="#confirm_delete" onclick="deleteGroup(${row.id})"><span class="svg-icon svg-icon-md"><i class="fas fa-trash"></i></span></button>`;
                    } else {
                        // là item
                        html += '<a href="/admin/' + module + '/' + row.id + '/edit" class="btn btn-sm btn-clean btn-icon mr-2" target="_blank"><span class="svg-icon svg-icon-md"><i class="far fa-edit\n"></i></span></a>';
                        html += '<button class="btn btn-sm btn-clean btn-icon mr-2" data-toggle="modal" data-target="#confirm_delete" onclick="deleteItem(' + row.id + ')"><span class="svg-icon svg-icon-md"><i class="fas fa-trash\n"></i></span></button>';
                        html += '<a href="/admin/duplicate-item/' + row.id + '" class="btn btn-sm btn-clean btn-icon" title="Replicate">';
                        html += '<i class="far fa-copy"></i>';
                        html += '</a>';
                    }
                    return html;
                }
            }
        ],
    });
    $(document).ready(function () {
        $('.sorting:first').trigger('click');
    });
}

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

function convertToSlug(title) {
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

function changeTitleToSlug() {
    var title, slug;

    //Lấy text từ thẻ input title
    title = document.getElementById("title").value;

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
    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
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
    document.getElementById('slug').value = slug;
}

function collectTextNodes(element, texts) {
    for (var child = element.firstChild; child !== null; child = child.nextSibling) {
        if (child.nodeType === 3)
            texts.push(child);
        else if (child.nodeType === 1)
            collectTextNodes(child, texts);
    }
}

function getTextWithSpaces(element) {
    let texts = [];
    collectTextNodes(element, texts);
    for (let i = texts.length; i-- > 0;)
        texts[i] = texts[i].data;
    return texts.join('>');
}

$('#keyword').on('input', function () {
    const KEYWORD = convertToSlug($(this).val());
    $('#kt_aside_menu_result_search').toggle(!!KEYWORD);

    $('#kt_aside_menu').toggle(!KEYWORD);

    let index = 0;
    $("#kt_aside_menu_result_search .menu-nav li").each(function () {
        let textone = getTextWithSpaces(this);
        textone = convertToSlug(textone);
        let toggle_status = textone.indexOf(KEYWORD) > -1;
        $(this).toggle(toggle_status);
        if (toggle_status) {
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

    $("#kt_aside_menu_result_search .menu-nav .menu-item-submenu").each(function () {
        var textone = $(this).children('a').find('.menu-text').text().toLowerCase();
        textone = convertToSlug(textone);
        if (textone.indexOf(KEYWORD) > -1) {
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

    $("#kt_aside_menu_result_search .menu-nav .menu-section").each(function () {
        var textone = $(this).find('.menu-text').text().toLowerCase();
        textone = convertToSlug(textone);
        if (textone.indexOf(KEYWORD) > -1) {
            let next_el = $(this).next();
            while (next_el.hasClass('menu-item')) {
                next_el.show();
                next_el.find('.menu-subnav').children().show();
                next_el = next_el.next();
            }
        }
    })

    $("#kt_aside_menu_result_search .menu-nav li.menu-item-parent").hide()

    $('#empty-result-search').toggle(!$('#kt_aside_menu_result_search .menu-nav').children(':visible').length);
});

