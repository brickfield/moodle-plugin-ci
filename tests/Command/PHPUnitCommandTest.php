<?php

/*
 * This file is part of the Moodle Plugin CI package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * License http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace MoodlePluginCI\Tests\Command;

use MoodlePluginCI\Command\PHPUnitCommand;
use MoodlePluginCI\Tests\Fake\Process\DummyExecute;
use MoodlePluginCI\Tests\MoodleTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class PHPUnitCommandTest extends MoodleTestCase
{
    protected function executeCommand($pluginDir = null, $moodleDir = null): CommandTester
    {
        if ($pluginDir === null) {
            $pluginDir = $this->pluginDir;
        }
        if ($moodleDir === null) {
            $moodleDir = $this->moodleDir;
        }

        $command          = new PHPUnitCommand();
        $command->execute = new DummyExecute();

        $application = new Application();
        $application->add($command);

        $commandTester = new CommandTester($application->find('phpunit'));
        $commandTester->execute([
            'plugin'   => $pluginDir,
            '--moodle' => $moodleDir,
        ]);

        return $commandTester;
    }

    public function testExecute()
    {
        $commandTester = $this->executeCommand();
        $this->assertSame(0, $commandTester->getStatusCode());
    }

    public function testExecuteNoTests()
    {
        $fs = new Filesystem();
        $fs->remove($this->pluginDir.'/tests/lib_test.php');

        $commandTester = $this->executeCommand();
        $this->assertSame(0, $commandTester->getStatusCode());
        $this->assertMatchesRegularExpression('/No PHPUnit tests to run, free pass!/', $commandTester->getDisplay());
    }

    public function testExecuteNoPlugin()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->executeCommand($this->moodleDir.'/no/plugin');
    }

    public function testExecuteNoMoodle()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->executeCommand($this->moodleDir.'/no/moodle');
    }
}
