<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use SplFileInfo;
use Webmozart\Assert\Assert;

final class CodebaseDirectory
{
    private SplFileInfo $directory;

    private string $glob;

    public function __construct(string $path, string $glob = '')
    {
        $this->setDirectory($path);
        $this->glob = $glob;
    }

    public function absolutePath(): string
    {
        return (string) $this->directory->getRealPath();
    }

    public function getGlob(): string
    {
      return $this->glob;
    }

    private function setDirectory(string $path): void
    {
        Assert::stringNotEmpty(
            $path,
            'The directory with the code to be scanned cannot be empty'
        );
        $directory = new SplFileInfo($path);
        if (! $directory->isDir()) {
            throw InvalidDirectory::notFoundAt($directory);
        }
        $this->directory = $directory;
    }
}
