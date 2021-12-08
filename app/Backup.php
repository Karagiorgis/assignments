<?php
/** @noinspection PhpUnused */

namespace App;

use Exception;

class Backup
{
    public static function backup(Tree $tree, string $backupDestination, string $backupFilename): void
    {
        $backupDestination = rtrim($backupDestination . '/');

        if (!file_exists($backupDestination)) {
            mkdir($backupDestination, 0755);
        }

        file_put_contents($backupDestination . '/' .$backupFilename, $tree->toJson());
    }

    /**
     * @throws Exception
     */
    public static function restore(string $backupDestination, string $outputFolder): void
    {
        if (!file_exists($backupDestination)) {
            throw new Exception('Backup file not found');
        }

        $outputFolder = rtrim($outputFolder, '/');

        $json = file_get_contents($backupDestination);
        $tree = json_decode($json, true);

        foreach ($tree as $treeItem) {
            self::restoreTreeItem($treeItem, $outputFolder);
        }
    }

    protected static function restoreTreeItem(array $treeItem, string $outputFolder): void
    {
        $itemPath = $outputFolder . $treeItem['path'] . $treeItem['name'];

        if ($treeItem['type'] === TreeItem::TREE_ITEM_TYPE_DIRECTORY) {
            if (!file_exists($itemPath)) {
                mkdir($itemPath, 0755);
            }

            foreach ($treeItem['children'] as $childItem) {
                self::restoreTreeItem($childItem, $outputFolder);
            }
        }

        if ($treeItem['type'] === TreeItem::TREE_ITEM_TYPE_FILE) {
            file_put_contents($itemPath, $treeItem['content']);
        }
    }
}
