<?php
namespace app\components;
use yii\widgets\LinkSorter;
use yii\helpers\Html;

class LinkSorterExtends extends LinkSorter{
/**
     * Renders the sort links.
     * @return string the rendering result
     */
    protected function renderSortLinks()
    {
        $attributes = empty($this->attributes) ? array_keys($this->sort->attributes) : $this->attributes;
        $links = '';
        foreach ($attributes as $name) {
            $links .= $this->sort->link($name, $this->linkOptions);
        }

        return Html::tag("div", $links, $this->options);
    }
}