<?php
/** @noinspection PhpUnused */

namespace App;

class TreeItem
{
    const TREE_ITEM_TYPE_DIRECTORY = 'directory';
    const TREE_ITEM_TYPE_FILE = 'file';

    public function __construct(
        protected string $name = '',
        protected string $path = '',
        protected string $content = '',
        protected array $children = [],
        protected string $type = ''
    ){}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TreeItem
     */
    public function setName(string $name): TreeItem
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return TreeItem
     */
    public function setPath(string $path): TreeItem
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return TreeItem
     */
    public function setContent(string $content): TreeItem
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param array $children
     * @return TreeItem
     */
    public function setChildren(array $children): TreeItem
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @param TreeItem $child
     * @return $this
     */
    public function addChild(TreeItem $child): TreeItem
    {
        $this->children[] = $child;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return TreeItem
     */
    public function setType(string $type): TreeItem
    {
        $this->type = $type;
        return $this;
    }

    public function toArray(): array
    {
        $array = get_object_vars($this);

        if ($this->getType() === self::TREE_ITEM_TYPE_DIRECTORY) {
            $childrenArray = [];

            foreach ($this->getChildren() as $child) {
                $childrenArray[] = $child->toArray();
            }

            $array['children'] = $childrenArray;

            //clean the content if it's a directory so the array looks cleaner
            $contentIndex = array_search("content",array_keys($array));
            array_splice($array, $contentIndex, 1);
        }

        if ($this->getType() === self::TREE_ITEM_TYPE_FILE) {
            //clean the children if it's a file so the array looks cleaner
            $childrenIndex = array_search("children",array_keys($array));
            array_splice($array, $childrenIndex, 1);
        }

        return $array;
    }

}