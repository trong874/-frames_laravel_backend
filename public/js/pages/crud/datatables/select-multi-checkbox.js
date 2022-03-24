$('#master_chk').on('click', function (e) {
    if ($('#master').is(':checked', true)) {
        $(".sub_chk").prop('checked', true);
    } else {
        $(".sub_chk").prop('checked', false);
    }
});
$('#delete_all').on('click', function (e) {
    var allVals = [];
    $(".sub_chk:checked").each(function () {
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
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: 'ids=' + join_selected_values,
            success: function (data) {
                location.reload();
                if (data['error']) {
                    alert(data['error']);
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
