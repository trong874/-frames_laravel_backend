$('#add_taxonomy_group').on('click', function () {
    let count_child = document.getElementById('form_taxonomy_group').childElementCount;
    $('#form_taxonomy_group').prepend('<div class="container col-lg-6">\n' +
        '                        <div\n' +
        '                            class="card card-custom gutter-b example example-compact alert alert-custom alert-default col-lg-12">\n' +
        '                            <div class="card-body">\n' +
        '                                <div class="card-toolbar">\n' +
        '                                    <div class="example-tools justify-content-right" onclick="closeTaxonomyGroup(this)">\n' +
        '                                        <i class="la la-close"></i>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                                <div class="form-group">\n' +
        '                                    <label class="form-label">Tên nhóm phân loại</label>\n' +
        '                                    <input type="text" class="form-control" onkeyup="changeValueNameGroup(this)">\n' +
        '                                </div>\n' +
        '                                <div id="phan_loai_' + count_child + '">\n' +
        '                                    <div class="form-group">\n' +
        '                                        <label class="form-label">Phân loại hàng</label>\n' +
        '                                        <div class="input-group">\n' +
        '                                            <input type="text" class="form-control" \n' +
        '                                                   onkeyup="changeValueTaxonomyGroup(this)">\n' +
        '                                            <div class="input-group-append" onclick="removeTexonomy(this)"><span\n' +
        '                                                    class="input-group-text"> <i class="la flaticon-delete"></i> </span>\n' +
        '                                            </div>\n' +
        '                                        </div>\n' +
        '                                    </div>\n' +
        '                                </div>\n' +
        '                                <div class="form-group">\n' +
        '                                    <button type="button" class="btn btn-success"\n' +
        '                                            onclick="addTexonomy(this)">Thêm phân loại\n' +
        '                                    </button>\n' +
        '                                </div>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>')

    if (count_child >= 1) {
        $('#btn_taxonomy_group').hide();
    }
    $('#theader_group tr').prepend('<th>Tên</th>');
    $('#tbody_group tr').prepend('<td>Loại</td>');
    if (count_child === 0) {
        $('#theader_group tr').append('<th class="text-danger">Giá</th>\n' +
            '                <th class="text-success">Số Trong Kho hàng</th>')
    }
    setValueForTable();
})

function closeTaxonomyGroup(el) {
    let this_elment = el.parentNode.parentNode.parentNode.parentNode;
    let current_index = Array.from(this_elment.parentNode.children).indexOf(this_elment);

    this_elment.remove();
    $('#tbody_group tr td').eq(current_index).remove();
    $('#theader_group tr th').eq(current_index).remove();
    let count_element = document.getElementById('form_taxonomy_group').childElementCount
    if (count_element < 2) {
        $('#btn_taxonomy_group').show();
    }
    if (count_element === 0){
        $('#theader_group tr').html('');
    }
    if (current_index === 1 && count_element === 1) {
        document.getElementById('phan_loai_1').id = 'phan_loai_0';
    }
    setValueForTable();
}

function changeValueNameGroup(input) {
    let phan_loai_id = input.parentElement.nextElementSibling.id.split('')[10];
    $('#theader_group tr th').eq(phan_loai_id).html(input.value);
}

function changeValueTaxonomyGroup(input) {
    let count_child_form_group = document.getElementById('form_taxonomy_group').childElementCount;
    if (count_child_form_group === 1) {
        let parent_el = document.getElementById('phan_loai_0');
        let count = parent_el.childElementCount;
        for (let i = 0; i < count; i++) {
            $(`.th_${i}`).html(input.value);
        }
    }
    if (count_child_form_group === 2) {
        let parent_el_1 = document.getElementById('phan_loai_0');
        let parent_el_2 = document.getElementById('phan_loai_1');
        let count_1 = parent_el_1.childElementCount;
        let count_2 = parent_el_2.childElementCount;
        let child_1 = parent_el_1.children;
        let child_2 = parent_el_2.children;
        for (let i = 0; i < count_1; i++) {
            $(`.th_${i}`).html(child_1[i].children[1].children[0].value);
        }
        for (let j = 0; j < count_2; j++) {
            $(`.td_${j}`).html(child_2[j].children[1].children[0].value);
        }
    }
}

function addTexonomy(btn_add) {
    let el = btn_add.parentElement.previousElementSibling.id
    $(`#${el}`).append('<div class="form-group">\n' +
        '                                        <label class="form-label">Phân loại hàng</label>\n' +
        '                                        <div class="input-group">\n' +
        '                                            <input type="text" class="form-control"\n' +
        '                                                   onkeyup="changeValueTaxonomyGroup(this)">\n' +
        '                                            <div class="input-group-append" onclick="removeTexonomy(this)"><span class="input-group-text"> <i\n' +
        '                                                        class="la flaticon-delete"></i> </span></div>\n' +
        '                                        </div>\n' +
        '                                    </div>');
    setValueForTable();
    changeValueTaxonomyGroup('')
}

function setValueForTable() {
    let count_child_form_group = document.getElementById('form_taxonomy_group').childElementCount;
    if (count_child_form_group === 0) {
        $('#tbody_group').html('');
    }
    if (count_child_form_group === 1) {
        let count_1 = document.getElementById('phan_loai_0').childElementCount;
        $('#tbody_group').html('');
        for (let i = 0; i < count_1; i++) {
            $('#tbody_group').append('<tr>');
            $('#tbody_group').append('<th class="th_' + i + '">Loại</th>');
            $('#tbody_group').append('<td><input type="text" class="form-control price_input" onkeyup="setDataItemAttribute()"></td><td><input type="text" class="form-control qty_input" onkeyup="setDataItemAttribute()"></td>');
        }
    }
    if (count_child_form_group === 2) {
        let count_1 = document.getElementById('phan_loai_0').childElementCount;
        let count_2 = document.getElementById('phan_loai_1').childElementCount;
        $('#tbody_group').html('');
        for (let i = 0; i < count_1; i++) {
            $('#tbody_group').append('<tr>');
            $('#tbody_group').append('<th rowspan="' + count_2 + '" class="th_' + i + '">Loại</th>');
            $('#tbody_group').append('<td class="td_0">Loại</td><td><input type="text" class="form-control price_input" placeholder="Giá" onkeyup="setDataItemAttribute()"></td><td><input type="text" class="form-control qty_input" placeholder="Số lượng" onkeyup="setDataItemAttribute()"></td>');
            $('#tbody_group').append('</tr>');
            for (let j = 1; j < count_2; j++) {
                $('#tbody_group').append('<tr><td class="td_' + j + '">Loại</td><td><input type="text" class="form-control price_input"  placeholder="Giá" onkeyup="setDataItemAttribute()"></td><td><input type="text" class="form-control qty_input" placeholder="Số lượng" onkeyup="setDataItemAttribute()"></td></tr>');
            }
        }
    }
}

function removeTexonomy(el) {
    let this_form = el.parentNode.parentNode.parentNode;
    let count_el = this_form.childElementCount;
    if (count_el > 1) {
        el.parentNode.parentNode.remove();
    }
    setValueForTable();
    changeValueTaxonomyGroup('')
}

function setDataItemAttribute() {
    let count_child_form_group = document.getElementById('form_taxonomy_group').childElementCount;
    let data = [];
    if (count_child_form_group === 1) {
        let parent_el = document.getElementById('phan_loai_0');
        let option = parent_el.previousElementSibling.lastElementChild.value;
        let count = parent_el.childElementCount;
        let child = parent_el.children;
        let input_price = document.getElementsByClassName('price_input')
        let input_qty = document.getElementsByClassName('qty_input')

        for (let i = 0; i < count; i++) {
            let key = option;
            let obj = {};
            obj[key] = child[i].children[1].children[0].value
            console.log(i)
            obj['price'] = input_price[i].value
            obj['qty'] = input_qty[i].value
            data.push(obj);
        }
    }
    if (count_child_form_group === 2) {
        let parent_el_1 = document.getElementById('phan_loai_0');
        let parent_el_2 = document.getElementById('phan_loai_1');
        let option1 = parent_el_1.previousElementSibling.lastElementChild.value;
        let option2 = parent_el_2.previousElementSibling.lastElementChild.value;
        let count_1 = parent_el_1.childElementCount;
        let count_2 = parent_el_2.childElementCount;
        let child_1 = parent_el_1.children;
        let child_2 = parent_el_2.children;
        let input_price = document.getElementsByClassName('price_input')
        let input_qty = document.getElementsByClassName('qty_input')
        let count = 0;
        for (let i = 0; i < count_1; i++) {
            for (let j = 0; j < count_2; j++) {
                let key1 = option1;
                let key2 = option2;
                let obj = {};
                obj[key1] = child_1[i].children[1].children[0].value
                obj[key2] = child_2[j].children[1].children[0].value
                obj['price'] = input_price[count].value
                obj['qty'] = input_qty[count].value
                data.push(obj);
                count++;
            }
        }
    }
    document.getElementById('attribute_items').value = JSON.stringify(data);
}
