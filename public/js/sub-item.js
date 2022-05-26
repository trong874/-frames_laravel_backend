function addOptionItem() {
    let countRow = document.getElementById('table_attribute_item').childElementCount - 1;

    let table_attribute = document.getElementById('table_attribute_item');
    let html = '';
    html += `<tbody id="row_${countRow}">`;
    html += `<tr data-index_el="0">`;
    html += `<th rowspan="1" class="td__options">`;
    html += `<input type="text" value="" class="form-control attribute_${countRow}" placeholder="Màu sắc, Size..." onkeyup="setNameInputSub(this.value,${countRow});this.name = 'attribute[${this.value}]'" required>`;
    html += `</th>`;
    html += `<td><input type="text" class="form-control sub_item_${countRow}_0" placeholder="Xanh, vàng đỏ..." onkeyup="setNameInputOption(this.value,${countRow},0);"></td>`;
    html += `<td><input type="text" class="form-control price_subitem_${countRow}_0"></td>`;
    html += `<td><input type="text" class="form-control qty_subitem_${countRow}_0"></td>`;
    html += `<td><button type="button" class="btn btn-sm btn-danger" disabled>Xoá</button></td>`;
    html += `<td rowspan="1" style="height: 0" class="action_option">`;
    html += `<button type="button" class="btn btn-sm btn-danger" onclick="deleteOption(${countRow})"><i class="far fa-trash-alt"></i></button>`;
    html += `<br>`;
    html += `<button type="button" class="btn btn-sm btn-success add_row_in_option" onclick="addRowInOption(${countRow})"><i class="fas fa-plus"></i></button>`;
    html += `</td>`;
    html += `</tr>`;
    html += `</tbody>`;

    table_attribute.insertAdjacentHTML('beforeend', html);
}

function setNameInputSub(value, count_row) {
    $(`input.sub_item_${count_row}`).attr('name', `attribute[${value}]`);
}

function setNameInputOption(value, count_row, count_row_in_option) {
    let name_attribute = $(`input.attribute_${count_row}`).attr('name')
    $(`input.sub_item_${count_row}_${count_row_in_option}`).attr('name', name_attribute + '[' + value + ']')
    $(`input.price_subitem_${count_row}_${count_row_in_option}`).attr('name', name_attribute + '[' + value + '][price]')
    $(`input.qty_subitem_${count_row}_${count_row_in_option}`).attr('name', name_attribute + '[' + value + '][qty]')
}

function addRowInOption(row_id) {
    let row = document.getElementById(`row_${row_id}`)
    let last_index = $(`#row_${row_id}`).children().last().attr('data-index_el');
    let latest_index = ++last_index;
    let html = '';
    html += `<tr data-index_el="${latest_index}">`;
    html += `<td><input type="text" class="form-control sub_item_${row_id}${latest_index}" placeholder="Xanh,vàng,đỏ..." onkeyup="setNameInputOption(this.value,${row_id},${latest_index})"></td>`;
    html += `<td><input type="text" class="form-control price_subitem_${row_id}_${latest_index}"></td>`;
    html += `<td><input type="text" class="form-control qty_subitem_${row_id}_${latest_index}"></td>`;
    html += `<td><button type="button" class="btn btn-sm btn-danger" onclick="deleteRowInOption(this)">Xoá</button></td>`;
    html += `</tr>`;
    row.insertAdjacentHTML('beforeend', html);
    let th = $(`#row_${row_id} tr th`);
    let rowspan = th.attr('rowspan');
    th.attr('rowspan', ++rowspan)
    $(`#row_${row_id} tr .action_option`).attr('rowspan', rowspan)
}

function deleteOption(row_id) {
    document.getElementById(`row_${row_id}`).remove();
}

function deleteRowInOption(btn_delete) {
    let row_in_option = btn_delete.parentElement.parentElement
    row_in_option.remove();
}
