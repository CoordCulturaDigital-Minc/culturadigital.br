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
 * @author Jean-François Simon <contact@jfsimon.fr>
 */
class ehough_finder_expression_Expression implements ehough_finder_expression_ValueInterface
{
    const TYPE_REGEX = 1;
    const TYPE_GLOB  = 2;

    /**
     * @var ehough_finder_expression_ValueInterface
     */
    private $value;

    /**
     * @param string $expr
     *
     * @return ehough_finder_expression_Expression
     */
    public static function create($expr)
    {
        return new self($expr);
    }

    /**
     * @param string $expr
     */
    public function __construct($expr)
    {
        try {
            $this->value = ehough_finder_expression_Regex::create($expr);
        } catch (InvalidArgumentException $e) {
            $this->value = new ehough_finder_expression_Glob($expr);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        return $this->value->render();
    }

    /**
     * {@inheritdoc}
     */
    public function renderPattern()
    {
        return $this->value->renderPattern();
    }

    /**
     * @return bool
     */
    public function isCaseSensitive()
    {
        return $this->value->isCaseSensitive();
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->value->getType();
    }

    /**
     * {@inheritdoc}
     */
    public function prepend($expr)
    {
        $this->value->prepend($expr);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function append($expr)
    {
        $this->value->append($expr);

        return $this;
    }

    /**
     * @return bool
     */
    public function isRegex()
    {
        return self::TYPE_REGEX === $this->value->getType();
    }

    /**
     * @return bool
     */
    public function isGlob()
    {
        return self::TYPE_GLOB === $this->value->getType();
    }

    /**
     * @throws LogicException
     *
     * @return ehough_finder_expression_Glob
     */
    public function getGlob()
    {
        if (self::TYPE_GLOB !== $this->value->getType()) {
            throw new LogicException('Regex can\'t be transformed to glob.');
        }

        return $this->value;
    }

    /**
     * @return ehough_finder_expression_Regex
     */
    public function getRegex()
    {
        return self::TYPE_REGEX === $this->value->getType() ? $this->value : $this->value->toRegex();
    }
}
