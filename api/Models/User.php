<?php

namespace Nihar\WpApi\Models;

class User
{
    public $table = '';


    public function __construct()
    {
        $this->table = SMART_TABLE;
    }


    public function get_all_users($params = [])
    {
        global $wpdb;
        $sql = "SELECT * FROM $this->table";
        $condition = '';
        if (count($params) > 0) {
            $condition = 'WHERE  ';
        }
        if (isset($params['id']) && $params['id'] != '') {
            $condition .= "id=" . $params['id'] . " AND ";
        }
        if (isset($params['name']) && $params['name'] != '') {
            $condition .= "name LIKE '%" . $params['name'] . "%' AND ";
        }
        if (isset($params['email']) && $params['email'] != '') {
            $condition .= "email LIKE '%" . $params['email'] . "%' AND ";
        }
        if (isset($params['role']) && $params['role'] != '') {
            $condition .= "role='" . $params['role'] . "' AND ";
        }
        if (isset($params['status']) && $params['status'] != '') {
            $condition .= "status=" . $params['status'] . " AND ";
        }

        $condition = rtrim($condition, " AND ");
        $sql .= " $condition";
        $sql .= " ORDER BY id DESC";

        // dd($params);

        return $wpdb->get_results($sql);
    }


    public function get_filter_users($params = [])
    {
        global $wpdb;
        $sql = "SELECT * FROM $this->table";
        $condition = '';
        if (isset($params['id']) && $params['id'] != '') {
            $condition .= "id=" . $params['id'] . " AND ";
        }
        if (isset($params['name']) && $params['name'] != '') {
            $condition .= "name LIKE '%" . $params['name'] . "%' AND ";
        }
        if (isset($params['email']) && $params['email'] != '') {
            $condition .= "email LIKE '%" . $params['email'] . "%' AND ";
        }
        if (isset($params['role']) && $params['role'] != '') {
            $condition .= "role='" . $params['role'] . "' AND ";
        }
        if (isset($params['status']) && $params['status'] != '') {
            $condition .= "status=" . $params['status'] . " AND ";
        }

        if ($condition != '') {
            $condition = " WHERE $condition ";
        }

        $condition = rtrim($condition, " AND ");
        $sql .= $condition;
        $sql .= " ORDER BY id DESC";

        return $wpdb->get_results($sql);
    }


    public function store($data)
    {
        global $wpdb;
        $result = $wpdb->insert($this->table, $data);

        if ($result) {
            $last_id = $wpdb->insert_id;
            return $this->find($last_id);
        }
        return null;
    }

    public function update($data, $id)
    {
        global $wpdb;
        $result = $wpdb->update($this->table, $data, array("id" => $id));

        if ($result) {
            return $this->find($id);
        }
        return null;
    }

    public function find($id)
    {
        global $wpdb;
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->table WHERE id=%d", $id));
    }

    public function destroy_many($ids = [])
    {
        global $wpdb;
        $ids = implode(",", array_filter($ids, fn ($id) => !is_null($id) && $id != ''));
        return $wpdb->query("DELETE FROM $this->table WHERE id IN($ids)");
    }

    public function destroy($id)
    {
        global $wpdb;
        return $wpdb->delete($this->table, ['id' => $id]);
    }
}
