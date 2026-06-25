<?php

namespace App\Shared\Infrastructure\Doctrine\Mysql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class GroupConcat extends FunctionNode
{
    protected bool $isDistinct = false;
    protected mixed $expression = null;

    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf('GROUP_CONCAT(%s%s)',
            $this->isDistinct ? 'DISTINCT ' : '',
            $this->expression->dispatch($sqlWalker)
        );
    }

    /**
     * @throws QueryException
     */
    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $lexer = $parser->getLexer();
        if ($lexer->isNextToken(TokenType::T_DISTINCT)) {
            $parser->match(TokenType::T_DISTINCT);
            $this->isDistinct = true;
        }

        $this->expression = $parser->SingleValuedPathExpression();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }
}
