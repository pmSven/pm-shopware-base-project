<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: pmSven
 * Date: 02.05.20
 * Time: 11:43
 */

namespace PmAbstract\Commands;

use PmLogging\Components\Logging\LoggingServiceInterface;
use PmLogging\Models\Logging\Helper\LoggingEntity;
use PmLogging\Models\Logging\Helper\LoggingEvent;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Just a test command.
 *
 * Class PmAbstractTest
 * @package PmAbstract\Commands
 */
class PmAbstractTest extends ShopwareCommand
{
    /**
     * PmAbstractTest constructor.
     */
    public function __construct()
    {
        parent::__construct(NULL);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('pm:abstract:test')
            ->setDescription('Just a command to test some code')
            ->setHelp('The <info>%command.name%</info> is a command to test some code.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $output->writeln('Nothing to do!');
    }
}
