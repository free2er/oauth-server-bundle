<?php

declare(strict_types = 1);

namespace Free2er\OAuth\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Абстрактная команда
 */
abstract class AbstractCommand extends Command
{
    /**
     * Выводит таблицу
     *
     * @param array           $data
     * @param string[]        $headers
     * @param callable        $renderer
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function table(
        array $data,
        array $headers,
        callable $renderer,
        InputInterface $input,
        OutputInterface $output
    ): int {
        $io = new SymfonyStyle($input, $output);
        $io->table($headers, array_map($renderer, $data));

        return 0;
    }

    /**
     * Выводит сообщение об успешном выполнении команды
     *
     * @param string          $message
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function ok(string $message, InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->success($message);

        return 0;
    }

    /**
     * Выводит сообщение об ошибке
     *
     * @param string          $message
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function fail(string $message, InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->error($message);

        return 1;
    }
}
