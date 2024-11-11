<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="./bootstrap-3.4.1-dist/css/bootstrap.min.css">
    <style>
        h2 {
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        ul {
            font-size: large;
        }

        .right-btn {
            display: flex;
            flex-direction: row;
            justify-content: right;
        }

        .play-center {
            display: flex;
            flex-direction: row;
            justify-content: center;
        }

        .li-flex {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .folder {
            display: flex;
            flex-direction: row;
            justify-content: center;
        }
    </style>
    <script src="jquery-3.7.1.min.js"></script>
    <script>
        function changeItem(id, num) {
            files_array = localStorage.getItem("files_array");
            $.ajax({
                url: 'process.php',
                data: {
                    id: id,
                    action: num,
                    files_array: files_array,
                },
                type: "POST",
                success: function(response) {
                    // var response = JSON.parse(response);
                    var status = response.status
                    console.log(response.status);
                    if (status.status == "success") {
                        alertify.success("New item has been added successfully");
                    } else if (status.status == "error") {
                        alertify.error("Error while adding the item");
                    }
                    $("ul").load("list.php"); // to reload the todo list from database
                    localStorage.setItem("files_array", JSON.stringify(response.files_array));

                    //$("#spinner").remove(); //remove spinner as task is completed
                },
                error: function() {
                    //$('#output').html('Bummer: there was an error!');
                    //alertify.error("Error while adding the item");
                },

            })
        }

        function saveFile() {
            files_array = localStorage.getItem("files_array");
            $.ajax({
                url: 'process.php',
                data: {
                    save: 1,
                    files_array: files_array,
                    dict: localStorage.getItem('dict'),
                },
                type: "POST",
                success: function(response) {
                    // var response = JSON.parse(response);
                    var status = response.status
                    console.log(response.status);
                    if (status.status == "success") {
                        alertify.success("New item has been added successfully");
                    } else if (status.status == "error") {
                        alertify.error("Error while adding the item");
                    }
                    $("ul").load("list.php"); // to reload the todo list from database
                    localStorage.setItem("files_array", JSON.stringify(response.files_array));
                    alert("修改成功");
                    //$("#spinner").remove(); //remove spinner as task is completed
                },
                error: function() {
                    alert("修改失败");

                    //$('#output').html('Bummer: there was an error!');
                    //alertify.error("Error while adding the item");
                },

            })
        }
    </script>
</head>

<body>

    <h2>
        <p>文件编号管理器</p>
    </h2>
    <h2>
    <div class="input-group">
      <input type="text" class="form-control" placeholder="输入文件夹">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button" id="file-btn">确定</button>
      </span>
    </div>
    </h2>
    <div class="play-center">
        <ul class="list-group liflex">
            <?php
            include("list.php");
            ?>
        </ul>
        <div class="list-group liflex" style="margin-left: 30px;">
            <p>
                <button type="button" class="btn btn-success" onclick="saveFile()">
                    确认修改
                </button>
            </p>
        </div>
    </div>



</body>
<script>
    
    localStorage.setItem("files_array", <?php echo json_encode(file_get_contents("new_list.txt")) ?>);
    $('#file-btn').click(function(){
        console.log( );
        localStorage.setItem("dict",$('input').val());
        $.ajax({
                url: 'list.php',
                data: {
                    dict: $('input').val(),
                },
                type: "POST",
                complete: function(response) {
                    // var response = JSON.parse(response);
                    // location.reload();
                    $("ul").load("list.php"); // to reload the todo list from database
                    //localStorage.setItem("files_array", <?php echo json_encode(file_get_contents("new_list.txt")) ?>);
                    //$("#spinner").remove(); //remove spinner as task is completed
                },
                error: function() {
                    //$('#output').html('Bummer: there was an error!');
                    //alertify.error("Error while adding the item");
                },

            })
    })
    
</script>

</html>