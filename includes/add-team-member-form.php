<div class="wrap">
    <h1><?php echo isset($_GET['id']) ? 'Edit' : 'Add'; ?> Team Member</h1>
    <form id="rul-team-member-form" method="post" action="admin-post.php">
        <?php 
        $member = null;
        if (isset($_GET['id'])) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'rul_teams';
            $id = intval($_GET['id']);
            $member = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
        }
        ?>

        <?php wp_nonce_field('rul_save_team_member_action', 'rul_save_team_member_nonce'); ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="name">Name</label></th>
                    <td><input type="text" id="name" name="name" placeholder="Name" value="<?php echo isset($member['name']) ? esc_attr($member['name']) : ''; ?>" class="regular-text" required /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="designation">Designation</label></th>
                    <td><input type="text" id="designation" name="designation" placeholder="Designation" value="<?php echo isset($member['designation']) ? esc_attr($member['designation']) : ''; ?>" class="regular-text" required /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="member_id">ID</label></th>
                    <td><input type="number" id="member_id" name="member_id" placeholder="ID" value="<?php echo isset($member['member_id']) ? esc_attr($member['member_id']) : ''; ?>" class="regular-text" required /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="email">Email</label></th>
                    <td><input type="email" id="email" name="email" placeholder="Email" value="<?php echo isset($member['email']) ? esc_attr($member['email']) : ''; ?>" class="regular-text" required /></td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" name="action" value="rul_save_team_member">
        <?php if (isset($member['id'])): ?>
            <input type="hidden" name="id" value="<?php echo esc_attr($member['id']); ?>">
        <?php endif; ?>

        <p class="submit">
            <button type="submit" class="button-primary"><?php echo isset($_GET['id']) ? 'Update Member' : 'Add Member'; ?></button>
        </p>
    </form>
</div>
