<?php

namespace Entities;

use DB;

class Entity {
  public function __construct(protected DB $db) { }
}