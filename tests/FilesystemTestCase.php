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

namespace MoodlePluginCI\Tests;

use Symfony\Component\Filesystem\Filesystem;

class FilesystemTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    protected $tempDir;

    /**
     * @var Filesystem
     */
    protected $fs;

    protected function setUp()
    {
        $this->tempDir = sys_get_temp_dir().'/moodle-plugin-ci/FilesystemTestCase'.time();

        $this->fs = new Filesystem();
        $this->fs->mkdir($this->tempDir);

        $this->tempDir = realpath($this->tempDir);
    }

    protected function tearDown()
    {
        $this->fs->remove($this->tempDir);
    }

    protected function dumpFile($relativePath, $content)
    {
        $path = $this->tempDir.'/'.$relativePath;
        $this->fs->dumpFile($path, $content);

        return $path;
    }
}
