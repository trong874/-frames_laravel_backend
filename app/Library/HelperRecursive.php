<?php
if (!function_exists('get_option_categories')) {

    function get_option_categories($categories, $parent_id = null, $char = ' ')
    {
        foreach ($categories as $key => $item) {
            // Nếu là chuyên mục con thì hiển thị
            if ($item->parent_id == $parent_id) {
                echo '<option value="' . $item->id . '">' . $char . $item->title . '</option>';
                // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
                get_option_categories($categories, $item->id, $char . '__');
            }
        }
    }
}

if (!function_exists('get_option_old_categories')) {

    function get_option_old_categories($categories, $current_data, $parent_id = null, $char = ' ')
    {
        $item_is_selected = false;
        foreach ($categories as $key => $item) {
            // Nếu là chuyên mục con thì hiển thị
            if ($item->parent_id == $parent_id) {
                foreach ($current_data->groups as $group) {
                    if ($item->id === $group->id) {
                        $item_is_selected = true;
                        break;
                    }
                }
                if ($item_is_selected) {
                    echo '<option value="' . $item->id . '" selected>' . $char . $item->title . '</option>';

                    // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
                } else {
                    echo '<option value="' . $item->id . '">' . $char . $item->title . '</option>';
                }
                $item_is_selected = false;
                get_option_old_categories($categories, $current_data, $item->id, $char . '__');
            }
        }
    }
}

if (!function_exists('get_option_old_parent_categories')) {

    function get_option_old_parent_categories($categories, $current_data, $parent_id = null, $char = ' ')
    {
        foreach ($categories as $key => $item) {
            // Nếu là chuyên mục con thì hiển thị
            if ($item->parent_id == $parent_id) {
                if ($current_data['parent_id'] == $item['id']) {
                    echo '<option value="' . $item->id . '" selected>' . $char . $item->title . '</option>';
                } else if ($current_data['id'] == $item['id']) {
                    echo '<option value="' . $item->id . '" disabled>' . $char . $item->title . '(đang ở đây)</option>';
                } else {
                    echo '<option value="' . $item->id . '">' . $char . $item->title . '</option>';
                }
                // Tiếp tục đệ quy để tìm chuyên mục con của chuyên mục đang lặp
                get_option_old_parent_categories($categories, $current_data, $item->id, $char . '__');
            }
        }
    }
}

if (!function_exists('find_key')) {
    function find_key($arr, $key)
    {
        if (array_key_exists($key, $arr)) {
            return true;
        }
        foreach ($arr as $element) {
            if (is_array($element)) {
                if (find_key($element, $key)) {
                    return true;
                }
            }

        }
        return false;
    }
}
