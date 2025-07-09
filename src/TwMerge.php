<?php

declare(strict_types=1);

namespace Lumen\TwMerge;

use Lumen\TwMerge\Support\Validators;
use Lumen\TwMerge\Support\ValueObjects\ThemeGetter;

class TwMerge
{
    /**
     * @return array<string, mixed>
     */
    public function getDefaultConfig(): array
    {
        $themeColor = ThemeGetter::fromTheme('color');
        $themeFont = ThemeGetter::fromTheme('font');
        $themeText = ThemeGetter::fromTheme('text');
        $themeFontWeight = ThemeGetter::fromTheme('font-weight');
        $themeTracking = ThemeGetter::fromTheme('tracking');
        $themeLeading = ThemeGetter::fromTheme('leading');
        $themeBreakPoint = ThemeGetter::fromTheme('breakpoint');
        $themeContainer = ThemeGetter::fromTheme('container');
        $themeSpacing = ThemeGetter::fromTheme('spacing');
        $themeRadius = ThemeGetter::fromTheme('radius');
        $themeShadow = ThemeGetter::fromTheme('shadow');
        $themeInsetShadow = ThemeGetter::fromTheme('inset-shadow');
        $themeDropShadow = ThemeGetter::fromTheme('drop-shadow');
        $themeBlur = ThemeGetter::fromTheme('blur');
        $themePerspective = ThemeGetter::fromTheme('perspective');
        $themeAspect = ThemeGetter::fromTheme('aspect');
        $themeEase = ThemeGetter::fromTheme('ease');
        $themeAnimate = ThemeGetter::fromTheme('animate');

        $isAnyValidator = resolve(Validators\IsAnyValidator::class);
        $isTshirtSizeValidator = resolve(Validators\IsTshirtSizeValidator::class);
        $isNonArbitraryValidator = resolve(Validators\IsAnyNonArbitrary::class);
        $isNumberValidator = resolve(Validators\IsNumberValidator::class);
        $isFractionValidator = resolve(Validators\IsFractionValidator::class);
        $isPercentValidator = resolve(Validators\IsPercentValidator::class);
        $isIntegerValidator = resolve(Validators\IsIntegerValidator::class);
        $isArbitraryValueValidator = resolve(Validators\IsArbitraryValueValidator::class);
        $isArbitraryVariableValidator = resolve(Validators\IsArbitraryVariableValidator::class);
        $isArbitraryVariablePositionValidator = resolve(Validators\IsArbitraryVariablePositionValidator::class);
        $isArbitraryPositionValidator = resolve(Validators\IsArbitraryPositionValidator::class);
        $isArbitrarySizeValidator = resolve(Validators\IsArbitrarySizeValidator::class);
        $isArbitraryVariableLengthValidator = resolve(Validators\IsArbitraryVariableLengthValidator::class);
        $isArbitraryVariableFamilyNameValidator = resolve(Validators\IsArbitraryVariableFamilyNameValidator::class);
        $isArbitraryLengthValidator = resolve(Validators\IsArbitraryLengthValidator::class);

        $scaleBreak = static fn () => ['auto', 'hidden', 'clip', 'visible', 'scroll'];
        $scalePosition = static fn () => [
            'center',
            'top',
            'bottom',
            'left',
            'right',
            'top-left',
            // Deprecated since Tailwind CSS v4.1.0, see https://github.com/tailwindlabs/tailwindcss/pull/17378
            'left-top',
            'top-right',
            // Deprecated since Tailwind CSS v4.1.0, see https://github.com/tailwindlabs/tailwindcss/pull/17378
            'right-top',
            'bottom-right',
            // Deprecated since Tailwind CSS v4.1.0, see https://github.com/tailwindlabs/tailwindcss/pull/17378
            'right-bottom',
            'bottom-left',
            // Deprecated since Tailwind CSS v4.1.0, see https://github.com/tailwindlabs/tailwindcss/pull/17378
            'left-bottom',
        ];
        $scalePositionWithArbitrary = static fn () => [
            ...$scalePosition(),
            $isArbitraryVariableValidator(...),
            $isArbitraryValueValidator(...),
        ];
        $scaleOverflow = static fn () => ['auto', 'hidden', 'clip', 'visible', 'scroll'];
        $scaleOverscroll = static fn () => ['auto', 'contain', 'none'];
        $scaleUnambiguousSpacing = static fn () => [
            $isArbitraryVariableValidator(...),
            $isArbitraryValueValidator(...),
            $themeSpacing
        ];
        $scaleInset = static fn () => [$isFractionValidator(...),  'full', 'auto', ...$scaleUnambiguousSpacing()];
        $scaleGridTemplateColsRows = static fn () => [
            $isIntegerValidator(...),
            'none',
            'subgrid',
            $isArbitraryVariableValidator(...),
            $isArbitraryValueValidator(...),
        ];
        $scaleGridColRowStartAndEnd = static fn () => [
            'auto',
            [
                'span' => [
                    $isIntegerValidator(...),
                    $isArbitraryVariableValidator(...),
                    $isArbitraryValueValidator(...),
                ]
            ],
            $isIntegerValidator(...),
            $isArbitraryVariableValidator(...),
            $isArbitraryValueValidator(...),
        ];
        $scaleGridColRowStartOrEnd = static fn () => [
            $isIntegerValidator(...),
            'auto',
            $isArbitraryVariableValidator(...),
            $isArbitraryValueValidator(...),
        ];
        $scaleGridAutoColsRows = static fn () => [
            'auto',
            'min',
            'max',
            'fr',
            $isArbitraryVariableValidator(...),
            $isArbitraryValueValidator(...),
        ];
        $scaleAlignPrimaryAxis = static fn () => [
            'start',
            'end',
            'center',
            'between',
            'around',
            'evenly',
            'stretch',
            'baseline',
            'center-safe',
            'end-safe',
        ];
        $scaleAlignSecondaryAxis = static fn () => ['start', 'end', 'center', 'stretch', 'center-safe', 'end-safe'];
        $scaleMargin = static fn () => ['auto', ...$scaleUnambiguousSpacing()];
        $scaleSizing = static fn () => [
            $isFractionValidator(...),
            'auto',
            'full',
            'dvw',
            'dvh',
            'lvw',
            'lvh',
            'svw',
            'svh',
            'min',
            'max',
            'fit',
            ...$scaleUnambiguousSpacing(),
        ];
        $scaleColor = static fn () => [
            $themeColor,
            $isArbitraryVariableValidator(...),
            $isArbitraryValueValidator(...),
        ];
        $scaleBgPosition = static fn () => [
            ...$scalePosition(),
            $isArbitraryVariablePositionValidator(...),
            $isArbitraryPositionValidator(...),
            [
                'position' => [
                    $isArbitraryVariableValidator(...),
                    $isArbitraryValueValidator(...),
                ]
            ]
        ];
        $scaleBgRepeat = static fn () => [
            'no-repeat',
            [
                'repeat' => ['', 'x', 'y', 'space', 'round']
            ]
        ];
        $scaleBgSize = static fn () => [
            'auto',
            'cover',
            'contain',
            $isArbitraryVariableValidator(...),
            $isArbitrarySizeValidator(...),
            [
                'size' => [
                    $isArbitraryVariableValidator(...),
                    $isArbitraryValueValidator(...),
                ]
            ]
        ];
        $scaleGradientStopPosition = static fn () => [
            $isPercentValidator(...),
            $isArbitraryVariableLengthValidator(...),
        ];
        $scaleRadius = static fn () => [
            // Deprecated since Tailwind CSS v4.0.0
            '',
            'none',
            'full',
            $themeRadius,
            $isArbitraryVariableValidator(...),
            $isArbitraryValueValidator(...),
        ];
        $scaleBorderWidth = static fn () => [
            '',
            $isNumberValidator(...),
            $isArbitraryVariableLengthValidator(...),
            $isArbitraryLengthValidator(...),
        ];
        $scaleLineStyle = static fn () => ['solid', 'dashed', 'dotted', 'double'];
        $scaleBlendMode = static fn () => [
            'normal',
            'multiply',
            'screen',
            'overlay',
            'darken',
            'lighten',
            'color-dodge',
            'color-burn',
            'hard-light',
            'soft-light',
            'difference',
            'exclusion',
            'hue',
            'saturation',
            'color',
            'luminosity',
        ];
        $scaleMaskImagePosition = static fn () => [
            // Deprecated since Tailwind CSS v4.0.0
            '',
            'none',
            $themeBlur,
            $isArbitraryVariableValidator(...),
            $isArbitraryValueValidator(...),
        ];
        $scaleRotate = static fn () => [
            'none',
            $isNumberValidator(...),
            $isArbitraryValueValidator(...),
            $isArbitraryVariableValidator(...)
        ];
        $scaleScale = static fn () => [
            'none',
            $isNumberValidator(...),
            $isArbitraryValueValidator(...),
            $isArbitraryVariableValidator(...)
        ];
        $scaleSkew = static fn () => [
            $isNumberValidator(...),
            $isArbitraryValueValidator(...),
            $isArbitraryVariableValidator(...)
        ];
        $scaleTranslate = static fn () => [
            $isFractionValidator(...),
            'full',
            ...$scaleUnambiguousSpacing()
        ];

        return [
            'cacheSize' => config('lumen-tw.cache_size'),
            'prefix' => config('lumen-tw.prefix'),
            'animate' => [
                'animate' => ['spin', 'ping', 'pulse', 'bounce'],
                'aspect' => ['video'],
                'blur' => [$isTshirtSizeValidator(...)],
                'breakpoint' => [$isTshirtSizeValidator(...)],
                'color' => [$isAnyValidator(...)],
                'container' => [$isTshirtSizeValidator(...)],
                'drop-shadow' => [$isTshirtSizeValidator(...)],
                'ease' => ['in', 'out', 'in-out'],
                'font' => [$isNonArbitraryValidator(...)],
                'font-weight' => [
                    'thin',
                    'extralight',
                    'light',
                    'normal',
                    'medium',
                    'semibold',
                    'bold',
                    'extrabold',
                    'black',
                ],
                'inset-shadow' => [$isTshirtSizeValidator(...)],
                'leading' => ['none', 'tight', 'snug', 'normal', 'relaxed', 'loose'],
                'perspective' => ['dramatic', 'near', 'normal', 'midrange', 'distant', 'none'],
                'radius' => [$isTshirtSizeValidator(...)],
                'shadow' => [$isTshirtSizeValidator(...)],
                'spacing' => ['px', $isNumberValidator(...)],
                'text' => [$isTshirtSizeValidator(...)],
                'tracking' => ['tighter', 'tight', 'normal', 'wide', 'wider', 'widest'],
            ],
            'classGroups' => [
                // --------------
                // --- Layout ---
                // --------------

                /**
                 * Aspect Ratio
                 *
                 * @see https://tailwindcss.com/docs/aspect-ratio
                 */
                'aspect' => [
                    [
                        'aspect' => [
                            'auto',
                            'square',
                            $isFractionValidator(...),
                            $isArbitraryValueValidator(...),
                            $isArbitraryVariableValidator(...),
                            $themeAspect,
                        ],
                    ],
                ],

                /**
                 * Container
                 *
                 * @see https://tailwindcss.com/docs/container
                 * @deprecated since Tailwind CSS v4.0.0
                 */
                'container' => ['container'],

                /**
                 * Columns
                 *
                 * @see https://tailwindcss.com/docs/columns
                 */
                'columns' => [
                    [
                        'columns' => [
                            $isNumberValidator(...),
                            $isArbitraryValueValidator(...),
                            $isArbitraryVariableValidator(...),
                            $themeContainer
                        ],
                    ],
                ],

                /**
                 * Break After
                 * @see https://tailwindcss.com/docs/break-after
                 */
                'break-after' => [
                    [
                        'break-after' => $scaleBreak()
                    ]
                ],

                /**
                 * Break Before
                 * @see https://tailwindcss.com/docs/break-before
                 */
                'break-before' => [
                    [
                        'break-after' => $scaleBreak()
                    ]
                ],

                /**
                 * Break Inside
                 * @see https://tailwindcss.com/docs/break-inside
                 */
                'break-inside' => [
                    [
                        'break-inside' => ['auto', 'avoid', 'avoid-page', 'avoid-column']
                    ]
                ],

                /**
                 * Box Decoration Break
                 * @see https://tailwindcss.com/docs/box-decoration-break
                 */
                'box-decoration' => [
                    [
                        'box-decoration' => ['slice', 'clone']
                    ]
                ],

                /**
                 * Box Sizing
                 * @see https://tailwindcss.com/docs/box-sizing
                 */
                'box' => [
                    [
                        'box' => ['border', 'content']
                    ]
                ],

                /**
                 * Display
                 * @see https://tailwindcss.com/docs/display
                 */
                'display' => [
                    'block',
                    'inline-block',
                    'inline',
                    'flex',
                    'inline-flex',
                    'table',
                    'inline-table',
                    'table-caption',
                    'table-cell',
                    'table-column',
                    'table-column-group',
                    'table-footer-group',
                    'table-header-group',
                    'table-row-group',
                    'table-row',
                    'flow-root',
                    'grid',
                    'inline-grid',
                    'contents',
                    'list-item',
                    'hidden',
                ],

                /**
                 * Screen Reader Only
                 * @see https://tailwindcss.com/docs/display#screen-reader-only
                 */
                'sr-only' => ['sr-only', 'not-sr-only'],

                /**
                 * Floats
                 * @see https://tailwindcss.com/docs/float
                 */
                'float' => [
                    [
                        'float' => ['right', 'left', 'none', 'start', 'end']
                    ]
                ],

                /**
                 * Clear
                 * @see https://tailwindcss.com/docs/clear
                 */
                'clear' => [
                    [
                        'clear' => ['left', 'right', 'both', 'none', 'start', 'end'],
                    ]
                ],

                /**
                 * Isolation
                 * @see https://tailwindcss.com/docs/isolation
                 */
                'isolation' => [
                    [
                        'isolation' => ['isolate', 'isolation-auto']
                    ]
                ],

                /**
                 * Object Fit
                 * @see https://tailwindcss.com/docs/object-fit
                 */
                'object-fit' => [
                    [
                        'object' => ['contain', 'cover', 'fill', 'none', 'scale-down']
                    ]
                ],

                /**
                 * Object Position
                 * @see https://tailwindcss.com/docs/object-position
                 */
                'object-position' => [
                    [
                        'object' => $scalePositionWithArbitrary()
                    ]
                ],

                /**
                 * Overflow
                 * @see https://tailwindcss.com/docs/overflow
                 */
                'overflow' => [
                    [
                        'overflow' => $scaleOverflow()
                    ]
                ],

                /**
                 * Overflow X
                 * @see https://tailwindcss.com/docs/overflow
                 */
                'overflow-x' => [
                    [
                        'overflow-x' => $scaleOverflow()
                    ]
                ],

                /**
                 * Overflow Y
                 * @see https://tailwindcss.com/docs/overflow
                 */
                'overflow-y' => [
                    [
                        'overflow-y' => $scaleOverflow()
                    ]
                ],

                /**
                 * Overscroll Behavior
                 * @see https://tailwindcss.com/docs/overscroll-behavior
                 */
                'overscroll' => [
                    [
                        'overscroll' => $scaleOverscroll()
                    ]
                ],

                /**
                 * Overscroll Behavior X
                 * @see https://tailwindcss.com/docs/overscroll-behavior
                 */
                'overscroll-x' => [
                    [
                        'overscroll-x' => $scaleOverscroll()
                    ]
                ],

                /**
                 * Overscroll Behavior Y
                 * @see https://tailwindcss.com/docs/overscroll-behavior
                 */
                'overscroll-y' => [
                    [
                        'overscroll-y' => $scaleOverscroll()
                    ]
                ],

                /**
                 * Position
                 * @see https://tailwindcss.com/docs/position
                 */
                'position' => ['static', 'fixed', 'absolute', 'relative', 'sticky'],

                /**
                 * Top / Right / Bottom / Left
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'inset' => [
                    [
                        'inset' => $scaleInset()
                    ]
                ],

                /**
                 * Right / Left
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'inset-x' => [
                    [
                        'inset-x' => $scaleInset()
                    ]
                ],

                /**
                 * Top / Bottom
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'inset-y' => [
                    [
                        'inset-y' => $scaleInset()
                    ]
                ],

                /**
                 * Start
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'start' => [
                    [
                        'start' => $scaleInset()
                    ]
                ],

                /**
                 * End
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'end' => [
                    [
                        'end' => $scaleInset()
                    ]
                ],

                /**
                 * Top
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'top' => [
                    [
                        'top' => $scaleInset()
                    ]
                ],

                /**
                 * Right
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'right' => [
                    [
                        'right' => $scaleInset()
                    ]
                ],

                /**
                 * Top
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'bottom' => [
                    [
                        'bottom' => $scaleInset()
                    ]
                ],

                /**
                 * Left
                 * @see https://tailwindcss.com/docs/top-right-bottom-left
                 */
                'left' => [
                    [
                        'left' => $scaleInset()
                    ]
                ],

                /**
                 * Visibility
                 * @see https://tailwindcss.com/docs/visibility
                 */
                'visibility' => ['visible', 'invisible', 'collapse'],

                /**
                 * Z-Index
                 * @see https://tailwindcss.com/docs/z-index
                 */
                'z' => [
                    [
                        'z' => [
                            $isIntegerValidator(...),
                            'auto',
                            $isArbitraryVariableValidator(...),
                            $isArbitraryValueValidator(...),
                        ]
                    ]
                ],

                // ------------------------
                // --- Flexbox and Grid ---
                // ------------------------

                /**
                 * Flex Basis
                 * @see https://tailwindcss.com/docs/flex-basis
                 */
                'basis' => [
                    [
                        'basis' => [
                            $isFractionValidator(...),
                            'full',
                            'auto',
                            $themeContainer,
                            ...$scaleUnambiguousSpacing(),
                        ]
                    ]
                ],

                /**
                 * Flex Direction
                 * @see https://tailwindcss.com/docs/flex-direction
                 */
                'flex-direction' => [
                    [
                        'flex' => ['row', 'row-reverse', 'col', 'col-reverse']
                    ]
                ],

                /**
                 * Flex Wrap
                 * @see https://tailwindcss.com/docs/flex-wrap
                 */
                'flex-wrap' => [
                    [
                        'flex' => [
                            $isNumberValidator(...),
                            $isFractionValidator(...),
                            'auto',
                            'initial',
                            'none',
                            $isArbitraryValueValidator(...),
                        ]
                    ]
                ],

                /**
                 * Flex Grow
                 * @see https://tailwindcss.com/docs/flex-grow
                 */
                'grow' => [
                    [
                        'grow' => [
                            '',
                            $isNumberValidator(...),
                            $isArbitraryVariableValidator(...),
                            $isArbitraryValueValidator(...),
                        ]
                    ]
                ],

                /**
                 * Flex Shrink
                 * @see https://tailwindcss.com/docs/flex-shrink
                 */
                'shrink' => [
                    [
                        'shrink' => [
                            '',
                            $isNumberValidator(...),
                            $isArbitraryVariableValidator(...),
                            $isArbitraryValueValidator(...),
                        ]
                    ]
                ],

                /**
                 * Order
                 * @see https://tailwindcss.com/docs/order
                 */
                'order' => [
                    [
                        'order' => [
                            $isIntegerValidator(...),
                            'first',
                            'last',
                            'none',
                            $isArbitraryVariableValidator(...),
                            $isArbitraryValueValidator(...),
                        ]
                    ]
                ],

                /**
                 * Grid Template Columns
                 * @see https://tailwindcss.com/docs/grid-template-columns
                 */
                'grid-cols' => [
                    [
                        'grid-cols' => $scaleGridTemplateColsRows(),
                    ]
                ],

                /**
                 * Grid Column Start / End
                 * @see https://tailwindcss.com/docs/grid-column
                 */
                'col-start-end' => [
                    [
                        'col' => $scaleGridColRowStartAndEnd(),
                    ]
                ],

                /**
                 * Grid Column Start
                 * @see https://tailwindcss.com/docs/grid-column
                 */
                'col-start' => [
                    [
                        'col-start' => $scaleGridColRowStartOrEnd(),
                    ]
                ],

                /**
                 * Grid Column End
                 * @see https://tailwindcss.com/docs/grid-column
                 */
                'col-end' => [
                    [
                        'col-end' => $scaleGridColRowStartOrEnd(),
                    ]
                ],

                /**
                 * Grid Template Rows
                 * @see https://tailwindcss.com/docs/grid-template-rows
                 */
                'grid-rows' => [
                    [
                        'grid-rows' => $scaleGridTemplateColsRows()
                    ]
                ],

                /**
                 * Grid Row Start / End
                 * @see https://tailwindcss.com/docs/grid-row
                 */
                'row-start-end' => [
                    [
                        'row' => $scaleGridColRowStartAndEnd()
                    ]
                ],

                /**
                 * Grid Row Start
                 * @see https://tailwindcss.com/docs/grid-row
                 */
                'row-start' => [
                    [
                        'row-start' => $scaleGridColRowStartOrEnd()
                    ]
                ],

                /**
                 * Grid Row End
                 * @see https://tailwindcss.com/docs/grid-row
                 */
                'row-end' => [
                    [
                        'row-end' => $scaleGridColRowStartOrEnd()
                    ]
                ],

                /**
                 * Grid Auto Flow
                 * @see https://tailwindcss.com/docs/grid-auto-flow
                 */
                'grid-flow' => [
                    [
                        'grid-flow' => ['row', 'col', 'dense', 'row-dense', 'col-dense']
                    ]
                ],

                /**
                 * Grid Auto Columns
                 * @see https://tailwindcss.com/docs/grid-auto-columns
                 */
                'auto-cols' => [
                    [
                        'auto-cols' => $scaleGridAutoColsRows()
                    ]
                ],

                /**
                 * Gap
                 * @see https://tailwindcss.com/docs/gap
                 */
                'gap' => [
                    [
                        'gap' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Gap X
                 * @see https://tailwindcss.com/docs/gap
                 */
                'gap-x' => [
                    [
                        'gap-x' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Gap Y
                 * @see https://tailwindcss.com/docs/gap
                 */
                'gap-y' => [
                    [
                        'gap-y' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Justify Content
                 * @see https://tailwindcss.com/docs/justify-content
                 */
                'justify-content' => [
                    [
                        'justify' => [...$scaleAlignPrimaryAxis(), 'normal']
                    ]
                ],

                /**
                 * Justify Items
                 * @see https://tailwindcss.com/docs/justify-items
                 */
                'justify-items' => [
                    [
                        'justify-items' => [...$scaleAlignSecondaryAxis(), 'normal']
                    ]
                ],

                /**
                 * Justify Self
                 * @see https://tailwindcss.com/docs/justify-self
                 */
                'justify-self' => [
                    [
                        'justify-self' => ['auto', ...$scaleAlignSecondaryAxis()]
                    ]
                ],

                /**
                 * Align Content
                 * @see https://tailwindcss.com/docs/align-content
                 */
                'align-content' => [
                    [
                        'content' => ['normal', ...$scaleAlignPrimaryAxis()]
                    ]
                ],

                /**
                 * Align Items
                 * @see https://tailwindcss.com/docs/align-items
                 */
                'align-items' => [
                    [
                        'items' => [
                            ...$scaleAlignSecondaryAxis(),
                            [
                                'baseline' => ['', 'last']
                            ]
                        ]
                    ]
                ],

                /**
                 * Align Self
                 * @see https://tailwindcss.com/docs/align-self
                 */
                'align-self' => [
                    [
                        'self' => [
                            'auto',
                            ...$scaleAlignSecondaryAxis(),
                            [
                                'baseline' => ['', 'last']
                            ]
                        ]
                    ]
                ],

                /**
                 * Place Content
                 * @see https://tailwindcss.com/docs/place-content
                 */
                'place-content' => [
                    [
                        'place-content' => $scaleAlignPrimaryAxis()
                    ]
                ],

                /**
                 * Place Items
                 * @see https://tailwindcss.com/docs/place-items
                 */
                'place-items' => [
                    [
                        'place-items' => [
                            ...$scaleAlignSecondaryAxis(),
                            'baseline'
                        ]
                    ]
                ],

                /**
                 * Place Self
                 * @see https://tailwindcss.com/docs/place-self
                 */
                'place-self' => [
                    [
                        'place-self' => [
                            'auto',
                            ...$scaleAlignSecondaryAxis()
                        ]
                    ]
                ],

                // Spacing
                /**
                 * Padding
                 * @see https://tailwindcss.com/docs/padding
                 */
                'p' => [
                    [
                        'p' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Padding X
                 * @see https://tailwindcss.com/docs/padding
                 */
                'px' => [
                    [
                        'px' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Padding Y
                 * @see https://tailwindcss.com/docs/padding
                 */
                'py' => [
                    [
                        'py' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Padding Start
                 * @see https://tailwindcss.com/docs/padding
                 */
                'ps' => [
                    [
                        'ps' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Padding End
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pe' => [
                    [
                        'pe' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Padding Top
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pt' => [
                    [
                        'pt' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Padding Right
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pr' => [
                    [
                        'pr' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Padding Bottom
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pb' => [
                    [
                        'pb' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Padding Left
                 * @see https://tailwindcss.com/docs/padding
                 */
                'pl' => [
                    [
                        'pl' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Margin
                 * @see https://tailwindcss.com/docs/margin
                 */
                'm' => [
                    [
                        'm' => $scaleMargin()
                    ]
                ],

                /**
                 * Margin X
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mx' => [
                    [
                        'mx' => $scaleMargin()
                    ]
                ],

                /**
                 * Margin Y
                 * @see https://tailwindcss.com/docs/margin
                 */
                'my' => [
                    [
                        'my' => $scaleMargin()
                    ]
                ],

                /**
                 * Margin Start
                 * @see https://tailwindcss.com/docs/margin
                 */
                'ms' => [
                    [
                        'ms' => $scaleMargin()
                    ]
                ],

                /**
                 * Margin End
                 * @see https://tailwindcss.com/docs/margin
                 */
                'me' => [
                    [
                        'me' => $scaleMargin()
                    ]
                ],

                /**
                 * Margin Top
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mt' => [
                    [
                        'mt' => $scaleMargin()
                    ]
                ],

                /**
                 * Margin Right
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mr' => [
                    [
                        'mr' => $scaleMargin()
                    ]
                ],

                /**
                 * Margin Bottom
                 * @see https://tailwindcss.com/docs/margin
                 */
                'mb' => [
                    [
                        'mb' => $scaleMargin()
                    ]
                ],

                /**
                 * Margin Left
                 * @see https://tailwindcss.com/docs/margin
                 */
                'ml' => [
                    [
                        'ml' => $scaleMargin()
                    ]
                ],

                /**
                 * Space Between X
                 * @see https://tailwindcss.com/docs/margin#adding-space-between-children
                 */
                'space-x' => [
                    [
                        'space-x' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Space Between X Reverse
                 * @see https://tailwindcss.com/docs/margin#adding-space-between-children
                 */
                'space-x-reverse' => ['space-x-reverse'],

                /**
                 * Space Between Y
                 * @see https://tailwindcss.com/docs/margin#adding-space-between-children
                 */
                'space-y' => [
                    [
                        'space-y' => $scaleUnambiguousSpacing()
                    ]
                ],

                /**
                 * Space Between Y Reverse
                 * @see https://tailwindcss.com/docs/margin#adding-space-between-children
                 */
                'space-y-reverse' => ['space-y-reverse'],

                // --------------
                // --- Sizing ---
                // --------------

                /**
                 * Size
                 * @see https://tailwindcss.com/docs/width#setting-both-width-and-height
                 */
                'size' => [
                    [
                        'size' => $scaleSizing(),
                    ]
                ],

                /**
                 * Width
                 * @see https://tailwindcss.com/docs/width
                 */
                'w' => [
                    [
                        'w' => [
                            $themeContainer,
                            'screen',
                            ...$scaleSizing()
                        ]
                    ]
                ],

                /**
                 * Min-Width
                 * @see https://tailwindcss.com/docs/min-width
                 */
                'min-w' => [
                    [
                        'min-w' => [
                            $themeContainer,
                            'screen',
                            /** Deprecated. @see https://github.com/tailwindlabs/tailwindcss.com/issues/2027#issuecomment-2620152757 */
                            'none',
                            ...$scaleSizing(),
                        ]
                    ]
                ],

                /**
                 * Max-Width
                 * @see https://tailwindcss.com/docs/max-width
                 */
                'max-w' => [
                    [
                        'max-w' => [
                            $themeContainer,
                            'screen',
                            'none',
                            /** Deprecated since Tailwind CSS v4.0.0. @see https://github.com/tailwindlabs/tailwindcss.com/issues/2027#issuecomment-2620152757 */
                            'prose',
                            /** Deprecated since Tailwind CSS v4.0.0. @see https://github.com/tailwindlabs/tailwindcss.com/issues/2027#issuecomment-2620152757 */
                            [
                                'screen' => [$themeBreakPoint]
                            ],
                            ...$scaleSizing(),
                        ]
                    ]
                ],

                /**
                 * Height
                 * @see https://tailwindcss.com/docs/height
                 */
                'h' => [
                    [
                        'h' => ['screen', 'lh', ...$scaleSizing()]
                    ]
                ],

                /**
                 * Min-Height
                 * @see https://tailwindcss.com/docs/min-height
                 */
                'min-h' => [
                    [
                        'min-h' => ['screen', 'lh', 'none', ...$scaleSizing()]
                    ]
                ],

                /**
                 * Max-Height
                 * @see https://tailwindcss.com/docs/max-height
                 */
                'max-h' => [
                    [
                        'max-h' => ['screen', 'lh', ...$scaleSizing()]
                    ]
                ],

                // ------------------
                // --- Typography ---
                // ------------------

                /**
                 * Font Size
                 * @see https://tailwindcss.com/docs/font-size
                 */
                'font-size' => [
                    [
                        'text' => [
                            'base',
                            $themeText,
                            $isArbitraryVariableLengthValidator(...),
                            $isArbitraryLengthValidator(...)
                        ]
                    ]
                ],

                /**
                 * Font Smoothing
                 * @see https://tailwindcss.com/docs/font-smoothing
                 */
                'font-smoothing' => ['antialiased', 'subpixel-antialiased'],

                /**
                 * Font Style
                 * @see https://tailwindcss.com/docs/font-style
                 */
                'font-style' => ['italic', 'not-italic'],

                /**
                 * Font Weight
                 * @see https://tailwindcss.com/docs/font-weight
                 */
                'font-weight' => [
                    [
                        'font' => [
                            $themeFontWeight,
                            $isArbitraryVariableValidator(...),
                            $isArbitraryNumberValidator(...)
                        ]
                    ]
                ],

                /**
                 * Font Stretch
                 * @see https://tailwindcss.com/docs/font-stretch
                 */
                'font-stretch' => [
                    [
                        'font-stretch' => [
                            'ultra-condensed',
                            'extra-condensed',
                            'condensed',
                            'semi-condensed',
                            'normal',
                            'semi-expanded',
                            'expanded',
                            'extra-expanded',
                            'ultra-expanded',
                            $isPercentValidator(...),
                            $isArbitraryValueValidator(...),
                        ]
                    ]
                ],

                /**
                 * Font Family
                 * @see https://tailwindcss.com/docs/font-family
                 */
                'font-family' => [
                    [
                        'font' => [
                            $isArbitraryVariableFamilyNameValidator(...),
                            $isArbitraryValueValidator(...),
                            $themeFont
                        ]
                    ]
                ],

                /**
                 * Font Variant Numeric
                 * @see https://tailwindcss.com/docs/font-variant-numeric
                 */
                'fvn-normal' => ['normal-nums'],


            ],
        ];
    }
}
