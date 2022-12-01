<?php

namespace Controllers;

class View extends ControllerWithLogsEntity {
  public function show(array $params): array {
    ['id' => $id] = $params;
    return ['image_id' => $id, 'count' => $this->log->count($id)];
  }

  public function update(array $params): int {
    ['id' => $id, 'agent' => $agent, 'ip' => $ip] = $params;
    $this->log->increment($id, $ip, $agent);
    return 201;
  }
}