<?php

namespace Controllers;

class Id {
  public function create(array $params): array {
    return ['id' => rand(1, 4)];
  }
}