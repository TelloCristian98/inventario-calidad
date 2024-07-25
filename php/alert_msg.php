<?php
function alertMsg($msg)
{
?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><?php echo $msg; ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
