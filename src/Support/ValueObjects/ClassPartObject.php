<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\ValueObjects;

use Lumen\TwMerge\Support\Contracts\ClassValidator;

class ClassPartObject
{
    /**
     * @param  array<array-key, ClassPartObject>  $nextParts
     * @param  array<array-key, ClassValidatorObject>  $validators
     */
    public function __construct(
        protected(set) ?string $classGroupId = null,
        protected(set) array $nextParts = [],
        protected(set) array $validators = [],
    ) {}

    public function withClassGroupId(string $classGroupId): self
    {
        $this->classGroupId = $classGroupId;

        return $this;
    }

    public function putNonExistingPart(string $pathPart, ClassPartObject $nextPart): self
    {
        $this->nextParts[$pathPart] ??= $nextPart;

        return $this;
    }

    public function pushClassValidator(string $classGroupId, ClassValidator $validator): self
    {
        $this->validators[] = new ClassValidatorObject(
            classGroupId: $classGroupId,
            validator: $validator,
        );

        return $this;
    }
}
