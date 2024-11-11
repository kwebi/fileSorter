<?php

function change_index($obj, $index)
{
    $obj->idx = $index;
    $obj->new_name = str_pad($index, 3, '0', STR_PAD_LEFT) . "--" . $obj->old_name;
}


if (isset($_POST['id'])) {
    $id = $_POST['id'];

    header('Content-type: application/json'); //preparing correct format for json_encode

    if ($id > 0) {
        $response_array['status'] = "success";
    } else {
        $response_array['status'] = "error";
    }
    $files_array = json_decode($_POST['files_array']);
    $idx = $_POST['id'];
    $action = $_POST['action'];

    $tmp_file = clone $files_array[$idx + $action];
    $files_array[$idx+$action] = clone $files_array[$idx];
    $files_array[$idx] = clone $tmp_file;
    change_index($files_array[$idx],$idx);
    change_index($files_array[$idx + $action], $idx + $action);
    file_put_contents("new_list.txt", json_encode($files_array));

    $response_array['files_array'] = $files_array;
    echo json_encode($response_array); //sending response to ajax
}



if(isset($_POST['save'])){

    $base_dir = file_get_contents('dict.txt') . DIRECTORY_SEPARATOR;

    header('Content-type: application/json'); //preparing correct format for json_encode
    $files_array = json_decode($_POST['files_array']);
    for($i = 0;$i < count($files_array);$i++){
        if($files_array[$i]->changed) continue;
        if(!rename($base_dir . $files_array[$i]->old_name,$base_dir . $files_array[$i]->new_name)){//更改文件序号
            $response_array['status'] = "error";
            break;
        } else {
            $files_array[$i]->changed = true;
            $response_array['status'] = "success";
        }
    }

    file_put_contents("new_list.txt", json_encode($files_array));//将最新文件列表写入文件
    $response_array['files_array'] = $files_array;
    echo json_encode($response_array); //sending response to ajax
}