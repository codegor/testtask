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
    $present = $this->db->fetchOne(
      "SELECT id, view_count as count FROM {$this->table} WHERE ip_address = :ip and user_agent = :agent and image_id = :id",
      ['ip' => $ip, 'agent' => $agent, 'id' => $id]
    );

    if ($present) {
      $this->db->exec(
        "UPDATE {$this->table} SET view_count = :count WHERE id = :id",
        ['count' => (int)$present->count + 1, 'id' => $present->id]
      );
    } else {
      $this->db->exec(
        "INSERT INTO {$this->table} SET ip_address = :ip, user_agent = :agent, image_id = :id, view_count= 1",
        ['ip' => $ip, 'agent' => $agent, 'id' => $id]
      );
    }
  }
}