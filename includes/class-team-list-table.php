<?php

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class RUL_Team_List_Table extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct([
            'singular' => __('Team Member', 'rul-teams'),
            'plural' => __('Team Members', 'rul-teams'),
            'ajax' => true
        ]);
    }

    function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />',
            'name' => __('Name', 'rul-teams'),
            'designation' => __('Designation', 'rul-teams'),
            'member_id' => __('ID', 'rul-teams'),
            'email' => __('Email', 'rul-teams')
        ];
        return $columns;
    }

    protected function get_sortable_columns()
    {
        return [
            'name' => ['name', true],
            'email' => ['email', false]
        ];
    }

    function get_bulk_actions()
    {
        return [
            'delete' => __('Delete', 'rul-teams')
        ];
    }

    function process_bulk_action()
    {

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : [];
            if (is_array($ids)) {
                foreach ($ids as $id) {
                    RUL_Team_CRUD::delete_team_member($id);
                }
            }
        }
    }

    function prepare_items()
    { 
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        $primary = 'name';
        $this->_column_headers = [$columns, $hidden, $sortable, $primary];

        $this->process_bulk_action();

        $per_page = 10;
        $current_page = $this->get_pagenum();

        $search = isset($_REQUEST['s']) ? esc_sql($_REQUEST['s']) : '';

        $total_items = RUL_Team_CRUD::get_total_team_members($search);

        $this->items = RUL_Team_CRUD::get_team_members($per_page, $current_page, $search);

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);
    }

    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="id[]" value="%s" />', $item['id']);
    }

    function column_default($item, $column_name)
    {

        return $item[$column_name];
    }

    function column_name($item)
    {
        $delete_nonce = wp_create_nonce('rul_delete_team_member');
        $title = '<strong>' . $item['name'] . '</strong>';
        $actions = [
            'edit' => sprintf('<a href="?page=add-team-member&action=edit&id=%s">%s</a>', $item['id'], __('Edit', 'rul-teams')),
            'delete' => sprintf('<a class="delete-team-member" data-id="' . $item['id'] . '" href="?page=%s&action=delete&id=%s&_wpnonce=%s">%s</a>', $_REQUEST['page'], $item['id'], $delete_nonce, __('Delete', 'rul-teams'))
        ];
        return $title . $this->row_actions($actions);
    }
}
?>