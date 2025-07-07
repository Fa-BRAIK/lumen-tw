<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Contracts;

interface ClassValidator
{
    public function __invoke(string $className): bool;
}
