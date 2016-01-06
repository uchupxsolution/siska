<?php

/**
    @project : SISKA
    @date    : Jan 6, 2016, 3:54:12 PM
    @author  : Yusuf N. Mambrasar, S.Kom
    @email   : yusuf_mambrasar@yahoo.com
    @company : CV. Uchupx Solution
*/

?>
<html>
    <title>User List</title>
    <body>
        <div class="contents">
            <div class="nav"><?php echo $link_back; ?></div>
            <?php echo $message; ?>
            <?php echo validation_errors(); ?>
            <form action="<?php echo $action; ?>" method="post">
                <table>
                    <tr>
                        <th>ID</th>
                        <td><?php echo $user['user_id'];?></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td><input type="text" name="name" 
                                   value="<?php echo $user['name']; ?>" />
                            <?php form_error('name'); ?></td>
                    </tr>
                    <tr>
                        <th>PIN</th>
                        <td><input type="text" name="pin" 
                                   value="<?php echo $user['pin']; ?>" />
                            <?php form_error('pin'); ?></td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><input type="password" name="password" 
                                   value="" />
                            <?php form_error('password'); ?></td>
                    </tr>
                    <tr>
                        <th>Konfirmasi Password</th>
                        <td><input type="password" name="password_confirm" 
                                   value="" />
                            <?php form_error('password'); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                            <td>
                            <select name="status">
                                <option value="0" <?php echo ($user['status']==0 ? 'selected="selected"' : ''); ?>>Terdaftar</option>
                                <option value="1" <?php echo ($user['status']==1 ? 'selected="selected"' : ''); ?>>Aktif</option>
                                <option value="2" <?php echo ($user['status']==2 ? 'selected="selected"' : ''); ?>>Non Aktif</option>
                                <option value="3" <?php echo ($user['status']==3 ? 'selected="selected"' : ''); ?>>Keluar</option>
                            </select>
                            </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <?php if($user['user_id']==='[auto]'): ?>
                            <input type="submit" name="submit_create" value="Tambah" />
                            <?php else: ?>
                            <input type="submit" name="submit_update" value="Ubah" />
                            <?php echo $link_delete; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>

