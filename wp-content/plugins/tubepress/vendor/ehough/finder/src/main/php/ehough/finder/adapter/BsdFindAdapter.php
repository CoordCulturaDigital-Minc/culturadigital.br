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
 * Shell engine implementation using BSD find command.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ehough_finder_adapter_BsdFindAdapter extends ehough_finder_adapter_AbstractFindAdapter
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bsd_find';
    }

    /**
     * {@inheritdoc}
     */
    protected function canBeUsed()
    {
        return in_array($this->shell->getType(), array(ehough_finder_shell_Shell::TYPE_BSD, ehough_finder_shell_Shell::TYPE_DARWIN)) && parent::canBeUsed();
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
                $format = '%HT';
                break;
            case ehough_finder_iterator_SortableIterator::SORT_BY_ACCESSED_TIME:
                $format = '%a';
                break;
            case ehough_finder_iterator_SortableIterator::SORT_BY_CHANGED_TIME:
                $format = '%c';
                break;
            case ehough_finder_iterator_SortableIterator::SORT_BY_MODIFIED_TIME:
                $format = '%m';
                break;
            default:
                throw new InvalidArgumentException(sprintf('Unknown sort options: %s.', $sort));
        }

        $command
            ->add('-print0 | xargs -0 stat -f')
            ->arg($format.'%t%N')
            ->add('| sort | cut -f 2');
    }

    /**
     * {@inheritdoc}
     */
    protected function buildFindCommand(ehough_finder_shell_Command $command, $dir)
    {
        parent::buildFindCommand($command, $dir)->addAtIndex('-E', 1);

        return $command;
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
                ->add('| grep -v \'^$\'')
                ->add('| xargs -I{} grep -I')
                ->add($expr->isCaseSensitive() ? null : '-i')
                ->add($not ? '-L' : '-l')
                ->add('-Ee')->arg($expr->renderPattern())
                ->add('{}')
            ;
        }
    }
}
