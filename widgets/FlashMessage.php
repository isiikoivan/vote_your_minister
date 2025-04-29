<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap5\Alert;

class FlashMessage extends Widget
{
    public $useDismiss = true;
    public $autoFade = false;
    public $fadeTimeout = 3000;

    public function run()
    {
        $html = '';
        $flashes = Yii::$app->session->getAllFlashes();

        foreach ($flashes as $type => $messages) {
            $messages = (array)$messages;

            foreach ($messages as $message) {
                $options = [
                    'class' => 'alert-' . $type,
                ];

                if ($this->useDismiss) {
                    $options['class'] .= ' alert-dismissible fade show';
                    $options['role'] = 'alert';
                }

                $html .= Alert::widget([
                    'body' => $message,
                    'options' => $options,
                    'closeButton' => $this->useDismiss
                        ? ['class' => 'btn-close', 'data-bs-dismiss' => 'alert', 'aria-label' => 'Close']
                        : false,
                ]);
            }
        }

// Add auto-fade JS if enabled
        if ($this->autoFade) {
            $html .= "<script>
    setTimeout(function () {
        $('.alert').fadeOut('slow');
    }, {$this->fadeTimeout})
</script>";
        }

        return $html;
    }
}
