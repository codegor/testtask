<?php

namespace Entities;

class Log extends Entity {
  protected string $table = 'logs';

  public function count(string $id): int|null {
    return $this->db->fetchOne(
      "SELECT SUM(view_count) as count FROM {$this->table} WHERE image_id = :id",
      ['id' => $id]
    )->count;
  }

  public function increment(string $id, string $ip, string $agent): void {
    $this->db->exec(
      "INSERT INTO {$this->table} SET ip_address = :ip, user_agent = :agent, image_id = :id, view_count= 1 ON DUPLICATE KEY UPDATE view_count= {$this->table}.view_count + 1",
      ['ip' => $ip, 'agent' => $agent, 'id' => $id]
    );
  }
}