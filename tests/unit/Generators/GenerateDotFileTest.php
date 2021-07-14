<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PHPUnit\Framework\TestCase;
use PhUml\Console\ConsoleProgressDisplay;
use PhUml\Fakes\WithDotLanguageAssertions;
use PhUml\Parser\CodebaseDirectory;
use PhUml\Parser\CodeParser;
use PhUml\Parser\SourceCodeFinder;
use PhUml\TestBuilders\A;
use Symfony\Component\Console\Output\NullOutput;

final class GenerateDotFileTest extends TestCase
{
    use WithDotLanguageAssertions;

    /** @test */
    function it_creates_the_dot_file_of_a_directory()
    {
        $finder = SourceCodeFinder::nonRecursive();
        $sourceCode = $finder->find($this->codebaseDirectory);
        $codebase = $this->parser->parse($sourceCode);

        $digraph = $this->generator->generateDigraph($codebase, $this->display);

        $this->assertNode(A::classNamed('plBase'), $digraph->value());
        $this->assertNode(A::classNamed('plPhuml'), $digraph->value());
    }

    /** @test */
    function it_creates_the_dot_file_of_a_directory_using_a_recursive_finder()
    {
        $finder = SourceCodeFinder::recursive();
        $sourceCode = $finder->find($this->codebaseDirectory);
        $codebase = $this->parser->parse($sourceCode);

        $digraph = $this->generator->generateDigraph($codebase, $this->display);

        $base = A::classNamed('plBase');
        $tokenParser = A::classNamed('plStructureTokenparserGenerator');
        $attribute = A::classNamed('plPhpAttribute');
        $class = A::classNamed('plPhpClass');
        $function = A::classNamed('plPhpFunction');
        $parameter = A::classNamed('plPhpFunctionParameter');
        $interface = A::classNamed('plPhpInterface');
        $uml = A::classNamed('plPhuml');
        $dotProcessor = A::classNamed('plDotProcessor');
        $graphvizProcessor = A::classNamed('plGraphvizProcessor');
        $styleName = A::classNamed('plStyleName');
        $graphvizOptions = A::classNamed('plGraphvizProcessorOptions');
        $defaultStyle = A::classNamed('plGraphvizProcessorDefaultStyle');
        $neatoProcessor = A::classNamed('plNeatoProcessor');
        $options = A::classNamed('plProcessorOptions');
        $statisticsProcessor = A::classNamed('plStatisticsProcessor');
        $structureGenerator = A::classNamed('plStructureGenerator');
        $externalCommand = A::classNamed('plExternalCommandProcessor');
        $processor = A::classNamed('plProcessor');
        $style = A::classNamed('plGraphvizProcessorStyle');
        $this->assertNode($base, $digraph->value());
        $this->assertNode($structureGenerator, $digraph->value());
        $this->assertNode($styleName, $digraph->value());
        $this->assertNode($tokenParser, $digraph->value());
        $this->assertInheritance($tokenParser, $structureGenerator, $digraph->value());
        $this->assertNode($attribute, $digraph->value());
        $this->assertNode($class, $digraph->value());
        $this->assertNode($function, $digraph->value());
        $this->assertNode($parameter, $digraph->value());
        $this->assertNode($interface, $digraph->value());
        $this->assertNode($uml, $digraph->value());
        $this->assertNode($externalCommand, $digraph->value());
        $this->assertNode($dotProcessor, $digraph->value());
        $this->assertInheritance($dotProcessor, $externalCommand, $digraph->value());
        $this->assertNode($processor, $digraph->value());
        $this->assertNode($graphvizProcessor, $digraph->value());
        $this->assertInheritance($graphvizProcessor, $processor, $digraph->value());
        $this->assertNode($options, $digraph->value());
        $this->assertNode($graphvizOptions, $digraph->value());
        $this->assertInheritance($graphvizOptions, $options, $digraph->value());
        $this->assertNode($style, $digraph->value());
        $this->assertNode($defaultStyle, $digraph->value());
        $this->assertInheritance($defaultStyle, $style, $digraph->value());
        $this->assertNode($neatoProcessor, $digraph->value());
        $this->assertInheritance($neatoProcessor, $externalCommand, $digraph->value());
        $this->assertNode($statisticsProcessor, $digraph->value());
        $this->assertInheritance($statisticsProcessor, $processor, $digraph->value());
    }

    /** @before */
    function let()
    {
        $this->codebaseDirectory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/classes');
        $this->generator = new DigraphGenerator(A::graphvizProcessor()->build());
        $this->parser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $this->display = new ConsoleProgressDisplay(new NullOutput());
    }

    private DigraphGenerator $generator;

    private ProgressDisplay $display;

    private CodebaseDirectory $codebaseDirectory;

    private CodeParser $parser;
}
