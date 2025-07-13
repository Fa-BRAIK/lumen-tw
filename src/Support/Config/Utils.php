<?php

declare(strict_types=1);

namespace Lumen\TwMerge\Support\Config;

use Closure;
use Lumen\TwMerge\Support\ClassPartObject;
use Lumen\TwMerge\Support\Contracts\Config;
use Lumen\TwMerge\Support\ParsedClassName;

/**
 * @phpstan-import-type AnyClassGroupIds from Config
 * @phpstan-import-type AnyConfig from Config
 * @phpstan-import-type GetClassGroupIdClosure from ClassPartObject
 * @phpstan-import-type GetConflictingClassGroupIdsClosure from ClassPartObject
 * @phpstan-import-type CreateParseClassNameClosure from ParsedClassName
 * @phpstan-import-type CreateSortModifiersClosure from ParsedClassName
 */
readonly class Utils
{
    /**
     * @var GetClassGroupIdClosure
     */
    public Closure $getClassGroupId;

    /**
     * @var GetConflictingClassGroupIdsClosure
     */
    public Closure $getConflictingClassGroupIds;

    /**
     * @var CreateParseClassNameClosure
     */
    public Closure $parseClassName;

    /**
     * @var CreateSortModifiersClosure
     */
    public Closure $sortModifiers;

    /**
     * @param  AnyConfig  $config
     */
    public function __construct(Config $config)
    {
        $this->parseClassName = ParsedClassName::createParseClassName($config);
        $this->sortModifiers = ParsedClassName::createSortModifiers($config);
        [
            'getClassGroupId' => $this->getClassGroupId,
            'getConflictingClassGroupIds' => $this->getConflictingClassGroupIds,
        ] = ClassPartObject::createClassGroupUtils($config);
    }

    /**
     * @return array{
     *     getClassGroupId: GetClassGroupIdClosure,
     *     getConflictingClassGroupIds: GetConflictingClassGroupIdsClosure,
     *     parseClassName: CreateParseClassNameClosure,
     *     sortModifiers: CreateSortModifiersClosure
     * }
     */
    public function destructureUtils(): array
    {
        return [
            'getClassGroupId' => $this->getClassGroupId,
            'getConflictingClassGroupIds' => $this->getConflictingClassGroupIds,
            'parseClassName' => $this->parseClassName,
            'sortModifiers' => $this->sortModifiers,
        ];
    }
}
