<?php
/** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */
/** @noinspection PhpPureAttributeCanBeAddedInspection */

namespace App;

class Tree
{
    const FILE_NAME_BLACKLIST = [
        '.',
        '..',
    ];

    /**
     * Store directory structure.
     * Set arg $storeContent to false when the filesystem has big files to prevent running out of memory.
     *
     * @param string $directory
     * @param bool $storeContent
     */
    public function __construct(
        protected string $directory,
        protected bool $storeContent = true
    )
    {
        $this->directory = rtrim($this->directory, '/');
    }

    public function toArray(): array
    {
        $directory = $this->handleDir($this->directory);

        $treeArray = [];

        /** @var TreeItem $treeItem */
        foreach ($directory->getChildren() as $treeItem) {
            $treeArray[] = $treeItem->toArray();
        }

        return $treeArray;
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    protected function handleDir(string $dirPath, string $path = '/'): TreeItem
    {
        $filePathFromStartingDir = preg_replace('/' . str_replace('/', '\\/', $this->directory) . '/', '', $dirPath);
        $filePathInfo = pathinfo($filePathFromStartingDir);


        $directoryTreeItem = new TreeItem(
            name: $filePathInfo['basename'],
            path: key_exists('dirname', $filePathInfo) ? rtrim($filePathInfo['dirname'], '/') . '/' : "",
            type: TreeItem::TREE_ITEM_TYPE_DIRECTORY,
        );

        foreach (scandir($dirPath) as $item) {
            if ($this->isBlacklisted($item)) {
                continue;
            }

            $itemPath = "{$dirPath}/{$item}";

            if (is_dir($itemPath)) {
                $childItem = $this->handleDir($itemPath, "{$path}{$item}/");
            } else {
                $childItem = $this->handleFile($itemPath, $path);
            }

            $directoryTreeItem->addChild($childItem);
        }

        return $directoryTreeItem;
    }

    protected function handleFile(string $filePath, string $path = '/'): TreeItem
    {
        $fileInfo = pathinfo($filePath);
        return new TreeItem(
            name: $fileInfo['basename'],
            path: $path,
            content: $this->storeContent ? file_get_contents($filePath) : '',
            type: TreeItem::TREE_ITEM_TYPE_FILE
        );
    }

    protected function isBlacklisted(string $item): bool
    {
        return in_array($item, self::FILE_NAME_BLACKLIST);
    }
}
