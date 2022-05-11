<div class="dd card-body" id="nestable">
    <ol class="dd-list">
        {{showCategory($groups,$module)}}
    </ol>
</div>
<?php

function showCategory($groups, $module, $parent_id = null)
{
    foreach ($groups as $key => $item) {
        // Nếu là chuyên mục con thì hiển thị
        if ($item->parent_id == $parent_id) {
            // Xử lý hiển thị chuyên mục
            echo '<li class="dd-item" data-id="' . $item->id . '">
                 <div class="dd-handle">
                 <input type="checkbox"
                        id="master-' . $item->id . '"
                         class="sub_chk-' . $parent_id . ' checkbox_category"
                           onclick="selectAllChild('. $item->id . ')"
                            data-id="' . $item->id . '"
                            style="margin-right:10px">' . $item->title . '
                    <div style="position:absolute;right:2rem;top:.3rem" id="button_acction-' . $item->id . '">
                    <button type="button"  class="btn btn-sm btn-danger delete-category" data-toggle="modal" data-target="#confirm_delete" data-url="' . route("$module.destroy", $item) . '">Xóa</button>
                    <a href="' . route("$module.edit", $item->id) . '" class="btn btn-sm btn-warning">Sửa</a>
                    </div>
                 </div>';

            if (isset($groups[$key]->groups[0])) {
                echo ' <ol class="dd-list">';
                showCategory($groups, $module, $item->id);
                echo '</ol>';
            }
            echo '</li>';
        }
        }
    }
    ?>
