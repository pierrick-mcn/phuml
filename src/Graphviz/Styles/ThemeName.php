<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Styles;

final class ThemeName
{
    /** @var string[] */
    private static $validNames = ['phuml', 'php', 'classic'];

    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        if (! \in_array($name, self::$validNames, true)) {
            throw UnknownTheme::named($name, self::$validNames);
        }
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }
}
