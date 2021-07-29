<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\ClassMethod;
use PhUml\Code\Methods\Method;

/**
 * It builds an array with `Method`s for a `ClassDefinition`, an `InterfaceDefinition` or a
 * `TraitDefinition`
 *
 * It can run one or more `VisibilityFilter`s
 *
 * @see PrivateVisibilityFilter
 * @see ProtectedVisibilityFilter
 */
final class FilteredMethodsBuilder implements MethodsBuilder
{
    public function __construct(
        private ParametersBuilder $parametersBuilder,
        private TypeBuilder $typeBuilder,
        private VisibilityBuilder $visibilityBuilder,
        private VisibilityFilters $visibilityFilters
    ) {
    }

    /**
     * @param ClassMethod[] $methods
     * @return Method[]
     */
    public function build(array $methods): array
    {
        return array_map(
            fn (ClassMethod $method): Method => $this->buildMethod($method),
            $this->visibilityFilters->apply($methods)
        );
    }

    private function buildMethod(ClassMethod $method): Method
    {
        $name = $method->name->name;
        $visibility = $this->visibilityBuilder->build($method);
        $docBlock = $method->getDocComment();
        $returnType = $this->typeBuilder->fromMethodReturnType($method->returnType, $docBlock);
        $parameters = $this->parametersBuilder->build($method->params, $docBlock);
        return match (true) {
            $method->isAbstract() => new Method($name, $visibility, $returnType, $parameters, isAbstract: true),
            $method->isStatic() => new Method($name, $visibility, $returnType, $parameters, isStatic: true),
            default => new Method($name, $visibility, $returnType, $parameters),
        };
    }
}
