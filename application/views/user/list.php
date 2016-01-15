<?php

/**
    @project : SISKA
    @date    : Jan 6, 2016, 11:51:58 AM
    @author  : Yusuf N. Mambrasar, S.Kom
    @email   : yusuf_mambrasar@yahoo.com
    @company : CV. Uchupx Solution
*/

?>
<html>
    <title>User List</title>
    <body>
        <div class="contents">
            <div class="nav">
                <?php echo $link_create; ?>
                <form action="<?php echo $form_action; ?>" method="post">
                    <input type="text" name="search" value="<?php echo $search; ?>" />
                    <input type="submit" value="Cari" name="submit_search" />
                </form>
            </div>
            <div class="table">
                <?php echo $table; ?>
            </div>
            <div class="navigation">
                <?php echo $pagination; ?>
            </div>
        </div>
    </body>
</html>