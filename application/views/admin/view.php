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
            <form action="<?php echo $form_action; ?>" method="post">
                <table>
                    <tr>
                        <th>ID</th>
                        <td>
                            <?php echo $person['admin_id'];?>
                            <input type="hidden" name="user_id" value="<?php echo $person['user_id']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th>PIN</th>
                        <td><input type="text" name="pin" 
                                   value="<?php echo $person['pin']; ?>" />
                            <?php form_error('pin'); ?></td>
                    </tr>
                    <tr>
                        <th>Nama Depan</th>
                        <td><input type="text" name="name_first" 
                                   value="<?php echo $person['name_first']; ?>" />
                            <?php form_error('name_first'); ?></td>
                    </tr>
                    <tr>
                        <th>Nama Belakang</th>
                        <td><input type="text" name="name_last" 
                                   value="<?php echo $person['name_last']; ?>" />
                            <?php form_error('name_last'); ?></td>
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
                            <?php form_error('password_confirm'); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><input type="text" name="email" 
                                   value="<?php echo $person['email']; ?>" />
                            <?php form_error('email'); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                            <td>
                            <select name="status">
                                <option value="1" <?php echo ($person['status']==1 ? 'selected="selected"' : ''); ?>>Aktif</option>
                                <option value="2" <?php echo ($person['status']==2 ? 'selected="selected"' : ''); ?>>Non Aktif</option>
                                <option value="3" <?php echo ($person['status']==3 ? 'selected="selected"' : ''); ?>>Mutasi</option>
                                <option value="4" <?php echo ($person['status']==4 ? 'selected="selected"' : ''); ?>>Keluar</option>
                            </select>
                            </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <?php if($person['admin_id']==='[auto]'): ?>
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

