function sortable(){
    return $('.sortable').sortable().bind('sortupdate', function (e, ui) {
        var parent = $(this).closest('.ck-parent');
        var allImageChoose = parent.find(".image-preview-box img");
        var elemInput = parent.find('.image_input_text');
        var allPath = "";
        var len = allImageChoose.length;
        allImageChoose.each(function (index, obj) {
            allPath += $(this).attr('src');

            if (index != len - 1) {
                allPath += "|";
            }
        });
        elemInput.val(allPath);
    });
}

function removeImageExtension(){
   return $('.btn_delete_image').click(function (e) {

        var parent = $(this).closest('.ck-parent');
        var elemInput = parent.find('.image_input_text');
        $(this).closest('.image-preview-box').remove();
        var allImageChoose = parent.find(".image-preview-box img");

        var allPath = "";
        var len = allImageChoose.length;
        allImageChoose.each(function (index, obj) {
            allPath += $(this).attr('src');

            if (index != len - 1) {
                allPath += "|";
            }
        });
        elemInput.val(allPath);
    });
}
$(document).ready(function () {
    sortable();
    removeImageExtension();
})

// Image extenstion choose item
$(".ck-popup-multiply").click(function (e) {
    e.preventDefault();
    var parent = $(this).closest('.ck-parent');
    var elemBoxSort = parent.find('.sortable');
    var elemInput = parent.find('.image_input_text');
    CKFinder.modal({
        chooseFiles: true,
        width: 900,
        height: 600,
        onInit: function (finder) {
            finder.on('files:choose', function (evt) {
                var allFiles = evt.data.files;
                var chosenFiles = '';
                var len = allFiles.length;
                allFiles.forEach(function (file, i) {
                    chosenFiles += file.get('url');
                    if (i != len - 1) {
                        chosenFiles += "|";
                    }

                    elemBoxSort.append(`<div class="image-preview-box">
                                            <img src="${file.get('url')}" alt="" data-input="${file.get('url')}">
                                            <a rel="8" class="btn btn-xs  btn-icon btn-danger btn_delete_image" data-toggle="modal" data-target="#deleteModal"><i class="la la-close"></i></a>
                                        </div>`);
                });
                var allImageChoose = parent.find(".image-preview-box img");
                var allPath = "";
                var len = allImageChoose.length;
                allImageChoose.each(function (index, obj) {
                    allPath += $(this).attr('data-input');

                    if (index != len - 1) {
                        allPath += "|";
                    }
                });
                elemInput.val(allPath);

                //set lại event cho các nút xóa đã được thêm
                //remove image extension each item
                removeImageExtension()
                // khoi tao lại sortable sau khi append phần tử mới
                sortable();

            });
        }
    });
});
function deleteImage(selector) {
    document.querySelector(`img${selector}`).src = '/media/demos/empty.jpg';
    document.querySelector(`input${selector}`).value = '';
}
function selectFileWithCKFinder(selector) {
    CKFinder.modal({
        chooseFiles: true,
        width: 800,
        height: 600,
        onInit: function (finder) {
            finder.on('files:choose', function (evt) {
                var file = evt.data.files.first();
                var img = document.querySelector(`img${selector}`);
                var input = document.querySelector(`input${selector}`);
                img.src = file.getUrl();
                input.value = file.getUrl();
            });

            finder.on('file:choose:resizedImage', function (evt) {
                var output = document.querySelector(selector);
                output.value = evt.data.resizedUrl;
            });
        }
    });
}
