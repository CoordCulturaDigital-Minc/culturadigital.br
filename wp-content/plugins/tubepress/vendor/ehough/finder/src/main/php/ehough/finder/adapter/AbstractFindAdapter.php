<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Shell engine implementation using GNU find command.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
abstract class ehough_finder_adapter_AbstractFindAdapter extends ehough_finder_adapter_AbstractAdapter
{
    /**
     * @var ehough_finder_shell_Shell
     */
    protected $shell;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->shell = new ehough_finder_shell_Shell();
    }

    /**
     * {@inheritdoc}
     */
    public function searchInDirectory($dir)
    {
        // having "/../" in path make find fail
        $dir = realpath($dir);

        // searching directories containing or not containing strings leads to no result
        if (ehough_finder_iterator_FileTypeFilterIterator::ONLY_DIRECTORIES === $this->mode && ($this->contains || $this->notContains)) {
            return new ehough_finder_iterator_FilePathsIterator(array(), $dir);
        }

        $command = ehough_finder_shell_Command::create();
        $find = $this->buildFindCommand($command, $dir);

        if ($this->followLinks) {
            $find->add('-follow');
        }

        $find->add('-mindepth')->add($this->minDepth + 1);

        if (PHP_INT_MAX !== $this->maxDepth) {
            $find->add('-maxdepth')->add($this->maxDepth + 1);
        }

        if (ehough_finder_iterator_FileTypeFilterIterator::ONLY_DIRECTORIES === $this->mode) {
            $find->add('-type d');
        } elseif (ehough_finder_iterator_FileTypeFilterIterator::ONLY_FILES === $this->mode) {
            $find->add('-type f');
        }

        $this->buildNamesFiltering($find, $this->names);
        $this->buildNamesFiltering($find, $this->notNames, true);
        $this->buildPathsFiltering($find, $dir, $this->paths);
        $this->buildPathsFiltering($find, $dir, $this->notPaths, true);
        $this->buildSizesFiltering($find, $this->sizes);
        $this->buildDatesFiltering($find, $this->dates);

        $useGrep = $this->shell->testCommand('grep') && $this->shell->testCommand('xargs');
        $useSort = is_int($this->sort) && $this->shell->testCommand('sort') && $this->shell->testCommand('cut');

        if ($useGrep && ($this->contains || $this->notContains)) {
            $grep = $command->ins('grep');
            $this->buildContentFiltering($grep, $this->contains);
            $this->buildContentFiltering($grep, $this->notContains, true);
        }

        if ($useSort) {
            $this->buildSorting($command, $this->sort);
        }

        $command->setErrorHandler(array($this, 'callbackAccessDeniedThrower'));

        $paths = $this->shell->testCommand('uniq') ? $command->add('| uniq')->execute() : array_unique($command->execute());
        $iterator = new ehough_finder_iterator_FilePathsIterator($paths, $dir);

        if ($this->exclude) {
            $iterator = new ehough_finder_iterator_ExcludeDirectoryFilterIterator($iterator, $this->exclude);
        }

        if (!$useGrep && ($this->contains || $this->notContains)) {
            $iterator = new ehough_finder_iterator_FilecontentFilterIterator($iterator, $this->contains, $this->notContains);
        }

        if ($this->filters) {
            $iterator = new ehough_finder_iterator_CustomFilterIterator($iterator, $this->filters);
        }

        if (!$useSort && $this->sort) {
            $iteratorAggregate = new ehough_finder_iterator_SortableIterator($iterator, $this->sort);
            $iterator = $iteratorAggregate->getIterator();
        }

        return $iterator;
    }

    public function callbackAccessDeniedThrower($stderr)
    {
        if (!$this->ignoreUnreadableDirs) {

            throw new ehough_finder_exception_AccessDeniedException($stderr);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function canBeUsed()
    {
        return $this->shell->testCommand('find');
    }

    /**
     * @param ehough_finder_shell_Command $command
     * @param string  $dir
     *
     * @return ehough_finder_shell_Command
     */
    protected function buildFindCommand(ehough_finder_shell_Command $command, $dir)
    {
        return $command
            ->ins('find')
            ->add('find ')
            ->arg($dir)
            ->add('-noleaf'); // the -noleaf option is required for filesystems that don't follow the '.' and '..' conventions
    }

    /**
     * @param ehough_finder_shell_Command  $command
     * @param string[] $names
     * @param Boolean  $not
     */
    private function buildNamesFiltering(ehough_finder_shell_Command $command, array $names, $not = false)
    {
        if (0 === count($names)) {
            return;
        }

        $command->add($not ? '-not' : null)->cmd('(');

        foreach ($names as $i => $name) {
            $expr = ehough_finder_expression_Expression::create($name);

            // Find does not support expandable globs ("*.{a,b}" syntax).
            if ($expr->isGlob() && $expr->getGlob()->isExpandable()) {
                $expr = ehough_finder_expression_Expression::create($expr->getGlob()->toRegex(false));
            }

            // Fixes 'not search' and 'full path matching' regex problems.
            // - Jokers '.' are replaced by [^/].
            // - We add '[^/]*' before and after regex (if no ^|$ flags are present).
            if ($expr->isRegex()) {
                $regex = $expr->getRegex();
                $regex->prepend($regex->hasStartFlag() ? '/' : '/[^/]*')
                    ->setStartFlag(false)
                    ->setStartJoker(true)
                    ->replaceJokers('[^/]');
                if (!$regex->hasEndFlag() || $regex->hasEndJoker()) {
                    $regex->setEndJoker(false)->append('[^/]*');
                }
            }

            $command
                ->add($i > 0 ? '-or' : null)
                ->add($expr->isRegex()
                    ? ($expr->isCaseSensitive() ? '-regex' : '-iregex')
                    : ($expr->isCaseSensitive() ? '-name' : '-iname')
                )
                ->arg($expr->renderPattern());
        }

        $command->cmd(')');
    }

    /**
     * @param ehough_finder_shell_Command  $command
     * @param string   $dir
     * @param string[] $paths
     * @param Boolean  $not
     */
    private function buildPathsFiltering(ehough_finder_shell_Command $command, $dir, array $paths, $not = false)
    {
        if (0 === count($paths)) {
            return;
        }

        $command->add($not ? '-not' : null)->cmd('(');

        foreach ($paths as $i => $path) {
            $expr = ehough_finder_expression_Expression::create($path);

            // Find does not support expandable globs ("*.{a,b}" syntax).
            if ($expr->isGlob() && $expr->getGlob()->isExpandable()) {
                $expr = ehough_finder_expression_Expression::create($expr->getGlob()->toRegex(false));
            }

            // Fixes 'not search' regex problems.
            if ($expr->isRegex()) {
                $regex = $expr->getRegex();
                $regex->prepend($regex->hasStartFlag() ? $dir.DIRECTORY_SEPARATOR : '.*')->setEndJoker(!$regex->hasEndFlag());
            } else {
                $expr->prepend('*')->append('*');
            }

            $command
                ->add($i > 0 ? '-or' : null)
                ->add($expr->isRegex()
                    ? ($expr->isCaseSensitive() ? '-regex' : '-iregex')
                    : ($expr->isCaseSensitive() ? '-path' : '-ipath')
                )
                ->arg($expr->renderPattern());
        }

        $command->cmd(')');
    }

    /**
     * @param ehough_finder_shell_Command            $command
     * @param ehough_finder_comparator_NumberComparator[] $sizes
     */
    private function buildSizesFiltering(ehough_finder_shell_Command $command, array $sizes)
    {
        foreach ($sizes as $i => $size) {
            $command->add($i > 0 ? '-and' : null);

            switch ($size->getOperator()) {
                case '<=':
                    $command->add('-size -'.($size->getTarget() + 1).'c');
                    break;
                case '>=':
                    $command->add('-size +'. ($size->getTarget() - 1).'c');
                    break;
                case '>':
                    $command->add('-size +'.$size->getTarget().'c');
                    break;
                case '!=':
                    $command->add('-size -'.$size->getTarget().'c');
                    $command->add('-size +'.$size->getTarget().'c');
                case '<':
                default:
                    $command->add('-size -'.$size->getTarget().'c');
            }
        }
    }

    /**
     * @param ehough_finder_shell_Command          $command
     * @param ehough_finder_comparator_DateComparator[] $dates
     */
    private function buildDatesFiltering(ehough_finder_shell_Command $command, array $dates)
    {
        foreach ($dates as $i => $date) {
            $command->add($i > 0 ? '-and' : null);

            $mins = (int) round((time()-$date->getTarget()) / 60);

            if (0 > $mins) {
                // mtime is in the future
                $command->add(' -mmin -0');
                // we will have no result so we don't need to continue
                return;
            }

            switch ($date->getOperator()) {
                case '<=':
                    $command->add('-mmin +'.($mins - 1));
                    break;
                case '>=':
                    $command->add('-mmin -'.($mins + 1));
                    break;
                case '>':
                    $command->add('-mmin -'.$mins);
                    break;
                case '!=':
                    $command->add('-mmin +'.$mins.' -or -mmin -'.$mins);
                    break;
                case '<':
                default:
                    $command->add('-mmin +'.$mins);
            }
        }
    }

    /**
     * @param ehough_finder_shell_Command $command
     * @param string  $sort
     *
     * @throws InvalidArgumentException
     */
    private function buildSorting(ehough_finder_shell_Command $command, $sort)
    {
        $this->buildFormatSorting($command, $sort);
    }

    /**
     * @param ehough_finder_shell_Command $command
     * @param string  $sort
     */
    abstract protected function buildFormatSorting(ehough_finder_shell_Command $command, $sort);

    /**
     * @param ehough_finder_shell_Command $command
     * @param array   $contains
     * @param Boolean $not
     */
    abstract protected function buildContentFiltering(ehough_finder_shell_Command $command, array $contains, $not = false);
}
