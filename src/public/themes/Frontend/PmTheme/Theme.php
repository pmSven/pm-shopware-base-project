<?php declare(strict_types=1);

namespace Shopware\Themes\PmTheme;

use Shopware\Components\Form\Container\TabContainer;

class Theme extends \Shopware\Components\Theme
{
    protected $extend = 'Responsive';

    protected $name = <<<'SHOPWARE_EOD'
Foo Theme From PM
SHOPWARE_EOD;

    protected $description = <<<'SHOPWARE_EOD'
Foo Theme From PM
SHOPWARE_EOD;

    protected $author = <<<'SHOPWARE_EOD'
P&M Agentur Software + Consulting GmbH
SHOPWARE_EOD;

    protected $license = <<<'SHOPWARE_EOD'

SHOPWARE_EOD;

    protected $javascript = [
        'src/js/jquery.pm-responsive.js',
    ];

    protected $css = [
    ];

    public function createConfig(TabContainer $container): void
    {
    }
}
