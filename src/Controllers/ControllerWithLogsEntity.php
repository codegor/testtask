<?php

namespace Controllers;

use Entities\Log;

class ControllerWithLogsEntity {
  public function __construct(protected Log $log) { }
}