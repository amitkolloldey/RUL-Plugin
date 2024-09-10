<?php

class RUL_Team_AJAX_Handler
{

    public static function register_ajax_actions()
    {
        add_action('wp_ajax_rul_save_team_member', [__CLASS__, 'ajax_save_team_member']);
        add_action('wp_ajax_rul_delete_team_member', [__CLASS__, 'ajax_delete_team_member']);
    }

    public static function ajax_save_team_member()
    {
        check_ajax_referer('rul_teams_nonce', 'nonce');

        $name = sanitize_text_field($_POST['name']);
        $designation = sanitize_text_field($_POST['designation']);
        $member_id = intval($_POST['member_id']);
        $email = sanitize_email($_POST['email']);

        if (!$name || !$designation || !$member_id || !$email) {
            wp_send_json_error('Invalid input data');
        }

        $data = [
            'name' => $name,
            'designation' => $designation,
            'member_id' => $member_id,
            'email' => $email
        ];

        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $id = intval($_POST['id']);
            $result = RUL_Team_CRUD::update_team_member($id, $data);
        } else {

            $result = RUL_Team_CRUD::add_team_member($data);
        }

        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Failed to save team member');
        }
    }

    public static function ajax_delete_team_member()
    {
        check_ajax_referer('rul_teams_nonce', 'nonce');

        if (!isset($_POST['id'])) {
            wp_send_json_error('Invalid ID');
        }

        $id = intval($_POST['id']);

        if (RUL_Team_CRUD::delete_team_member($id)) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Delete failed');
        }
    }
}

RUL_Team_AJAX_Handler::register_ajax_actions();
