<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use PhUml\Code\Codebase;
use PhUml\Code\Summary;
use PhUml\Templates\TemplateEngine;
use PhUml\Templates\TemplateFailure;

/**
 * It takes a code `Structure` and extracts a `Summary` of its contents as text
 */
final class StatisticsProcessor implements Processor
{
    private TemplateEngine $engine;

    public function __construct(TemplateEngine $engine = null)
    {
        $this->engine = $engine ?? new TemplateEngine();
    }

    public function name(): string
    {
        return 'Statistics';
    }

    /**
     * @throws TemplateFailure
     */
    public function process(Codebase $codebase): OutputContent
    {
        $summary = new Summary();
        $summary->from($codebase);

        return new OutputContent($this->engine->render('statistics.txt.twig', ['summary' => $summary]));
    }
}
