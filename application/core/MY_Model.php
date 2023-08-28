<?php

defined("BASEPATH") or exit("No direct script access allowed");

class MY_Model extends CI_Model
{
    protected $table;

    public function __construct(string $table)
    {
        parent::__construct();
        $this->table= $table;
    }

    public function select($data = [], bool $multi = false, array $selects = [], string $order = "DESC"): ?array
    {
        foreach ($selects as $select) {
            $this->db->select($select);
        }
        if (is_array($data)) {
            $this->db->where($data);
        } else {
            $this->db->where("id", $data);
        }
        $this->db->order_by("id", $order);
        if (!$multi) {
            $this->db->limit(1);
            return $this->db->get($this->table)->row_array();
        } else {
            return $this->db->get($this->table)->result_array();
        }
    }

    public function insert(array $data, bool $batch = false): ?int
    {
        if (!$batch) {
            $insert = $this->db->insert($this->table, $data);
        } else {
            $insert = $this->db->insert_batch($this->table, $data);
        }

        if ($insert) {
            return $this->db->insert_id();
        } else {
            return null;
        }
    }

    public function update(array $data, array $where = [], string $batch = null): bool
    {
        if (!$batch) {
            return $this->db->where($where)->update($this->table, $data);
        } else {
            return $this->db->where_in($batch, $where)->update($this->table, $data);
        }
    }

    public function delete(array $where, string $batch = null, int $limit = 1000)
    {
        $this->db->limit($limit);
        if (!$batch) {
            return $this->db->delete($this->table, $where);
        } else {
            return $this->db->where_in($batch, $where)->delete($this->table);
        }
    }

    public function count(array $where = [], array $search = []): int
    {
        foreach ($search as $key => $val)
            $this->db->like($key, $val);
        $this->db->limit(1);
        $this->db->where($where);
        $this->db->select("count(id) as 'result,'");
        $row = $this->db->get($this->table)->row_array();
        return $row["result"] ?? 0;
    }

    public function sum(string $column, array $where = []): int
    {
        $this->db->limit(1);
        $this->db->where($where);
        $this->db->select("sum($column) as 'result'");
        $row = $this->db->get($this->table)->row_array();
        return $row["result"] ?? 0;
    }

    public function pagination(int $limit, int $offset, array $where = [], array $search = []): array
    {
        foreach ($search as $key => $val)
            $this->db->like($key, $val);
        $this->db->where($where);
        $this->db->order_by("id", "DESC");
        $this->db->limit($limit, $offset);
        $get = $this->db->get($this->table);
        return $get->result_array();
    }

}