<?php 

require_once  'UserFunction/MysqlFunctions.php';
$USER_FUN = new MysqlFunctions();

function pagi_get($page_key, $last_page){

    $page_number = 1;
    $USER_FUN = new MysqlFunctions();

    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        if(isset($_GET[$page_key]) && is_numeric($_GET[$page_key]) && !empty(trim($_GET[$page_key]))){
            $page_number = $USER_FUN->validation($_GET[$page_key]);
            if($page_number <= 0){
                $page_number = 1;
            }
            elseif($page_number > $last_page){
                $page_number = $last_page;
            }
        }
        else{
            $page_number = 1;
        }
    }
    else{
        $page_number = 1;
    }
    return $page_number;

}

function pagi_buttons($buttons){

    if(is_numeric($buttons)){
        if($buttons >= 3){
            if($buttons%2){
                return $buttons; //Odd
            }
            else{
                return false;  //Even
            }
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }

}

function get_records($offset, $limit){

    $USER_FUN = new MysqlFunctions();

    $tbl_structure .= <<<END
        <div class="container">
        <div class="ins-box ins-box-set">
            <table class="table table-hover">
                <thead class="bg-primary" style="color: white !important;">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Name</th>
                        <th scope="col">CountryCode</th>
                        <th scope="col">District</th>
                        <th scope="col">Population</th>
                    </tr>
                </thead>
                <tbody>
    END;

    $fetch_rec = $USER_FUN->show_rec('city', $offset, $limit);
    if($fetch_rec){
        foreach($fetch_rec as $rec_data){
            $tbl_structure .= '<tr class="v-set"><th scope="row">'.$rec_data['ID'].'</th><td scope="row">'.$rec_data['Name'].'</td><td scope="row">'.$rec_data['CountryCode'].'</td><td scope="row">'.$rec_data['District'].'</td><td scope="row">'.$rec_data['Population'].'</td></tr>';
        }
    }
    else{
        $tbl_structure .= '<tr><td colspan="5"><h3>Record not Found</h3></td></tr>';
    }

    $tbl_structure .= '</tbody></table></div></div>';

    echo $tbl_structure;

}

function pagination(){

    $USER_FUN = new MysqlFunctions();

    $total_buttons = pagi_buttons('7');
    $per_page_records = 15;
    $total_records = $USER_FUN->rec_count('city');
    $last_page = ceil($total_records/$per_page_records);
    $page_number = pagi_get('page', $last_page);
    $half = floor($total_buttons/2);    

    $show_page_info = '<div class="container"><div class="ins-box"><h5>Showing Result '.$page_number.' / '.$last_page.' </h5></div></div>';

    echo $show_page_info;

    get_records(($page_number * $per_page_records - $per_page_records), $per_page_records);

    $pagination .= '<div class="container"><nav><ul class="pagination pagination-lg pagination-cen">';

    if($page_number < $total_buttons && ($last_page == $total_buttons || $last_page > $total_buttons)){

        if($page_number >= 2){
            $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page=1">&lt;&lt;</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.($page_number - 1).'">Previous</a></li>';
        }

        if($page_number >= ($half + 2)){ 

            for($j=($page_number-$half); $j<=($page_number+$half); $j++){
    
                if($j == $page_number){
                    $pagination .= '<li class="page-item active"><span class="page-link">'.$j.'<span class="sr-only">(current)</span></span></li>';
                }
                else{
                    $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$j.'">'.$j.'</a></li>';
                }
    
            }
        }
        else{
            for($j=1; $j<=$total_buttons; $j++){

                if($j == $page_number){
                    $pagination .= '<li class="page-item active "><span class="page-link">'.$j.'<span class="sr-only">(current)</span></span></li>';
                }
                else{
                    $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$j.'">'.$j.'</a></li>';
                }
    
            }
        }

        $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.($page_number + 1).'">Next</a></li>';
        $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$last_page.'">&gt;&gt;</a></li>';
    }
    elseif($page_number >= $total_buttons && $last_page > $total_buttons){

        $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page=1">&lt;&lt;</a></li>';
        $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.($page_number - 1).'">Previous</a></li>';

        if(($page_number+$half) >= $last_page){

            for($j=($last_page-$total_buttons+1); $j<=$last_page; $j++){

                if($j == $page_number){
                    $pagination .= '<li class="page-item active"><span class="page-link">'.$j.'<span class="sr-only">(current)</span></span></li>';
                }
                else{
                    $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$j.'">'.$j.'</a></li>';
                }

            }
            
        }
        elseif(($page_number+$half) < $last_page){

            for($j=($page_number-$half); $j<=($page_number+$half); $j++){

                if($j == $page_number){
                    $pagination .= '<li class="page-item active"><span class="page-link">'.$j.'<span class="sr-only">(current)</span></span></li>';
                }
                else{
                    $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$j.'">'.$j.'</a></li>';
                }

            }
        }

        if($page_number != $last_page){
            $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.($page_number + 1).'">Next</a></li>';
            $pagination .= '<li class="page-item"><a class="page-link" href="index.php?page='.$last_page.'">&gt;&gt;</a></li>';
        }
        
    }

    $pagination .= '</ul></nav></div>';

    echo $pagination;

}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pagination Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="Stylesheet/stylesheet.css">
</head>

<body>

    <div class="container-fluid">

        <div class="container">
            <ul class="nav justify-content-center bg-primary">
                <li class="nav-item">
                    <div class="nav-link heading">PHP Pagination</div>
                </li>
            </ul>
        </div>

        <?php

            pagination();

        ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>

</html>