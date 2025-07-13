<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Validators;

use Illuminate\Support\Str;

final class IsPercentValidator extends Validator
{
    public function __invoke(string $className): bool
    {
        return Str::endsWith($className, '%')
            && resolve(IsNumberValidator::class)(Str::replace('%', '', $className));
    }
}
