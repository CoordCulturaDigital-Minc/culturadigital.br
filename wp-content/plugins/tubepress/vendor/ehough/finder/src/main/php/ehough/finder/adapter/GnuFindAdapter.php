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
class ehough_finder_adapter_GnuFindAdapter extends ehough_finder_adapter_AbstractFindAdapter
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'gnu_find';
    }

    /**
     * {@inheritdoc}
     */
    protected function buildFormatSorting(ehough_finder_shell_Command $command, $sort)
    {
        switch ($sort) {
            case ehough_finder_iterator_SortableIterator::SORT_BY_NAME:
                $command->ins('sort')->add('| sort');

                return;
            case ehough_finder_iterator_SortableIterator::SORT_BY_TYPE:
                $format = '%y';
                break;
            case ehough_finder_iterator_SortableIterator::SORT_BY_ACCESSED_TIME:
                $format = '%A@';
                break;
            case ehough_finder_iterator_SortableIterator::SORT_BY_CHANGED_TIME:
                $format = '%C@';
                break;
            case ehough_finder_iterator_SortableIterator::SORT_BY_MODIFIED_TIME:
                $format = '%T@';
                break;
            default:
                throw new InvalidArgumentException(sprintf('Unknown sort options: %s.', $sort));
        }

        $command
            ->get('find')
            ->add('-printf')
            ->arg($format.' %h/%f\\n')
            ->add('| sort | cut')
            ->arg('-d ')
            ->arg('-f2-')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function canBeUsed()
    {
        return $this->shell->getType() === ehough_finder_shell_Shell::TYPE_UNIX && parent::canBeUsed();
    }

    /**
     * {@inheritdoc}
     */
    protected function buildFindCommand(ehough_finder_shell_Command $command, $dir)
    {
      return parent::buildFindCommand($command, $dir)->add('-regextype posix-extended');
    }

    /**
     * {@inheritdoc}
     */
    protected function buildContentFiltering(ehough_finder_shell_Command $command, array $contains, $not = false)
    {
        foreach ($contains as $contain) {
            $expr = ehough_finder_expression_Expression::create($contain);

            // todo: avoid forking process for each $pattern by using multiple -e options
            $command
                ->add('| xargs -I{} -r grep -I')
                ->add($expr->isCaseSensitive() ? null : '-i')
                ->add($not ? '-L' : '-l')
                ->add('-Ee')->arg($expr->renderPattern())
                ->add('{}')
            ;
        }
    }
}
