<?php

class RUL_Team_CRUD
{

    public static function get_team_members($per_page = 10, $page_number = 1, $search = '')
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rul_teams';

        $sql = "SELECT * FROM $table_name";

        if ($search) {
            $search = esc_sql($search);
            $sql .= $wpdb->prepare(
                " WHERE name LIKE %s OR designation LIKE %s OR email LIKE %s",
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%'
            );
        }

        $orderby = !empty($_REQUEST['orderby']) ? esc_sql($_REQUEST['orderby']) : 'id';
        $order = !empty($_REQUEST['order']) ? esc_sql($_REQUEST['order']) : 'DESC';

        $sql .= $wpdb->prepare(" ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, ($page_number - 1) * $per_page);

        return $wpdb->get_results($sql, 'ARRAY_A');
    }


    public static function get_total_team_members($search = '')
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rul_teams';

        $sql = "SELECT COUNT(*) FROM $table_name";

        if ($search) {
            $search = esc_sql($search);
            $sql .= $wpdb->prepare(
                " WHERE name LIKE %s OR designation LIKE %s OR email LIKE %s",
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%',
                '%' . $wpdb->esc_like($search) . '%'
            );
        }

        return $wpdb->get_var($sql);
    }

    public static function add_team_member($data)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rul_teams';

        return $wpdb->insert($table_name, $data);
    }
    public static function update_team_member($id, $data)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rul_teams';

        $result = $wpdb->update(
            $table_name,
            $data,
            ['id' => $id],
            ['%s', '%s', '%d', '%s'],
            ['%d']
        );

        return $result !== false;
    }
    public static function delete_team_member($id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rul_teams';

        return $wpdb->delete($table_name, ['id' => $id]);
    }
}
 