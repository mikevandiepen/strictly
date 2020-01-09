<?php

include('vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;

$code = <<<'CODE'
<?php

class Test
{
    public string $__string;

    private array $__array;
    
    public function method(string $parameter_1, string $parameter_2): void
    {
        return array();
    }
}
CODE;

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
try {
    $ast = $parser->parse($code);
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$dumper = new NodeDumper;
echo $dumper->dump($ast) . "\n";
