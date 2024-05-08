<?php
defined( 'ABSPATH' ) || exit;

$add_url = home_url( add_query_arg( NULL, NULL ) ) . '&sub_page=add';
?>
<div class="row">
    <div class="col-4 ">
        <div class="first-box">
            <h2>Rule Title</h2>
            <p>Lorem ipsum and more about the details of the rules</p>
            <a href="">Edit</a>
        </div>
    </div>
    <div class="col-4 ">
        <div class="add-box">
            <a href="<?php echo $add_url ?>">ADD</a>
        </div>
    </div>
</div>
