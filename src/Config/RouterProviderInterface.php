<?php

namespace App\Config;

interface RouterProviderInterface
{

  public function getRoutes(): array;
}
