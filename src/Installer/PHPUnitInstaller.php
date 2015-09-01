<?php

/*
 * This file is part of the Moodle Plugin CI package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace Moodlerooms\MoodlePluginCI\Installer;

use Moodlerooms\MoodlePluginCI\Bridge\Moodle;
use Moodlerooms\MoodlePluginCI\Process\Execute;
use Symfony\Component\Process\Process;

/**
 * PHPUnit installer.
 *
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class PHPUnitInstaller extends AbstractInstaller
{
    /**
     * @var Moodle
     */
    private $moodle;

    /**
     * @var Execute
     */
    private $execute;

    public function __construct(Moodle $moodle, Execute $execute)
    {
        $this->moodle  = $moodle;
        $this->execute = $execute;
    }

    public function install()
    {
        $this->output->step('Initialize PHPUnit');
        $process = new Process(sprintf('php %s/admin/tool/phpunit/cli/util.php --install', $this->moodle->directory));
        $process->setTimeout(null);

        $this->execute->mustRun($process);

        $this->output->step('Build PHPUnit config');
        $process = new Process(sprintf('php %s/admin/tool/phpunit/cli/util.php --buildconfig', $this->moodle->directory));
        $process->setTimeout(null);

        $this->execute->mustRun($process);
    }

    public function stepCount()
    {
        return 2;
    }
}
