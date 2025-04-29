<?php

namespace app\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\LinkPager;

class TableGeneratorWidget extends Widget
{
    public $model;
    public $data = [];
    public $provider = null;

    public function run()
    {

        if ($this->provider!=null)
        {
            $this->data = $this->provider->getModels();
        }
        if (!$this->model) {
            return '';
        }

        $attributes = $this->model->TableColumns();
        $hasActions = false;
        $actionColumn = null;



        foreach ($attributes as $key => $headerInfo) {
            if (is_array($headerInfo) && isset($headerInfo['actions'])) {
                $hasActions = true;
                $actionColumn = [
                    'key' => $key,
                    'actions' => $headerInfo['actions'],
                ];
                unset($attributes[$key]);
                break;
            }
        }

        ob_start();
        ?>

        <div class="table-responsive">
            <table class="table table-hover mb-3">
                <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <?php foreach ($attributes as $key => $headerInfo): ?>
                        <th ><?= Html::encode(is_array($headerInfo) ? $headerInfo['label'] : $headerInfo) ?></th>
                    <?php endforeach; ?>
                    <?php if ($hasActions): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($this->data as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <?php foreach ($attributes as $key => $header): ?>
                            <td>
                                <?php
                                if (is_array($header) && isset($header['format']) && is_callable($header['format'])) {
                                    echo $header['format']($row[$key]);
                                } else {
                                    $rawKeys = ['is_active', 'locked', 'active', 'status', 'archived'];
                                    echo in_array($key, $rawKeys) ? $row[$key] : Html::encode($row[$key]);
//                                    echo  Html::encode($row[$key]);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>

                        <?php if ($hasActions && $actionColumn): ?>
                            <td>
                                <?php foreach ($actionColumn['actions'] as $action): ?>
                                    <?= is_callable($action) ? $action($row) . ' ' : '' ?>
                                <?php endforeach; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($this->provider !== null): ?>
                <div class="mt-3">
                    <?= LinkPager::widget(['pagination' => $this->provider->pagination]) ?>
                </div>
            <?php endif; ?>
        </div>

        <?php
        return ob_get_clean();
    }
}
