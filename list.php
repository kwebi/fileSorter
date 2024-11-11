<?php
function getfiles($dir)
{
    if ($dir != '' && substr($dir, strlen($dir) - 1) != '') {
        $dir .= '/';
    }
    $row = array();
    if (is_dir($dir)) {
        if ($di = opendir($dir)) {
            while (($file = readdir($di)) !== false) {
                if (is_file($dir . $file) && $file != '.' && $file != '..') {
                    $row[] = $file;
                }
            }
            closedir($di);
        }
    }
    return $row;
}
//var_dump(getfiles('D:\\code'));
//print_r(getfiles('D:\\code'))

class MyFile
{
    var $idx;
    var $old_name;
    var $new_name;
    var $changed;
    function get_name()
    {
        if ($this->changed) {
            return $this->new_name;
        } else {
            return $this->old_name;
        }
    }
    function __construct($index, $old_name)
    {
        $this->idx = $index;
        $this->old_name = $old_name;
        $this->new_name =  str_pad($index, 3, '0', STR_PAD_LEFT) . "--" . $this->old_name;
        // $this->changed = true; 本地文件是否已经改名
        if (strlen($this->old_name) > 3 && is_numeric(substr($this->old_name[0], 0, 3))) {
            $this->changed = true; //本地文件是否已经改名
        }
    }

    function change_index($index)
    {
        $this->idx = $index;
        $this->new_name = str_pad($index, 3, '0', STR_PAD_LEFT) . "--" . $this->old_name;
    }
}

$files_array = getfiles(file_get_contents('dict.txt'));
// var_dump($files_array);
$new_flies_array = [];
$new_list = "new_list.txt";
$current_list = json_decode(file_get_contents($new_list));

if (empty($current_list)) {
    for ($i = 0; $i < count($files_array); $i++) {
        $name = $files_array[$i];
        $sfile = new MyFile($i, $name);
        $new_files_array[$i] = $sfile;
    }
    file_put_contents($new_list, json_encode($new_files_array));
    $files_array = $new_files_array;
} else {
    // file_put_contents($new_list, json_encode($new_files_array));
    $files_array = $current_list;
}


for ($i = 0; $i < count($files_array); $i++) {
?>
    <li class="list-group-item li-flex" style="order=<? echo $files_array[$i]->idx  ?>" list-id=<? echo $files_array[$i]->idx ?>>
        <span class="badge" style="padding-top: 8px;font-size:larger"><? echo $i ?></span>

        <?php echo $files_array[$i]->old_name;

        ?>
        <div class="right-btn">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default" onclick="changeItem($(this).closest('li').attr('list-id'),-1)">
                    <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
                </button>
            </div>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-default" onclick="changeItem($(this).closest('li').attr('list-id'),1)">
                    <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
                </button>
            </div>
            <div class="input-group" style="flex-basis:43%">
                <input type="text" class="form-control" placeholder="输入序号">
                <span class="input-group-btn">
                    <button class="btn" type="button">确认</button>
                </span>
            </div>
        </div>

        <?php echo $files_array[$i]->new_name ?>
    </li>
<?php } ?>