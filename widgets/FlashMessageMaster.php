<?php
namespace app\widgets;

use yii\base\Widget;
use yii\bootstrap5\Alert;

class FlashMessage extends Widget
{
// Options to customize behavior
public $useDismiss = true;      // Whether to include a dismiss button
public $autoFade = false;       // Whether to auto-fade the alert after a timeout
public $fadeTimeout = 3000;     // Timeout in milliseconds for auto-fade
public $alertTypes = ['success', 'danger', 'info', 'warning', 'secondary', 'primary', 'light', 'dark']; // Alert types supported by Bootstrap

public function run()
{
$html = '';
$flashes = \Yii::$app->session->getAllFlashes();

// Loop through all the flash messages
foreach ($flashes as $type => $messages) {
$messages = (array)$messages;

foreach ($messages as $message) {
// Determine the Bootstrap alert class based on the flash type
$alertClass = $this->getAlertClass($type);

// Prepare the options for the alert widget
$options = [
'class' => 'alert ' . $alertClass,
];

// If we want a dismiss button, add relevant classes and attributes
if ($this->useDismiss) {
$options['class'] .= ' alert-dismissible fade show';
$options['role'] = 'alert';
}

// Render the alert widget
$html .= Alert::widget([
'body' => $message,
'options' => $options,
'closeButton' => $this->useDismiss
? ['class' => 'btn-close', 'data-bs-dismiss' => 'alert', 'aria-label' => 'Close']
: false,
]);
}
}

// If auto-fade is enabled, add JavaScript for fading alerts
if ($this->autoFade) {
$html .= "<script>
    setTimeout(function () {
        $('.alert').fadeOut('slow');
    }, {$this->fadeTimeout});
</script>";
}

return $html;
}

/**
* Returns the correct Bootstrap alert class based on the message type.
*
* @param string $type Flash message type
* @return string Bootstrap alert class
*/
protected function getAlertClass($type)
{
// Map the flash message type to a Bootstrap alert class
switch ($type) {
case 'success':    return 'alert-success';
case 'danger':     return 'alert-danger';
case 'info':       return 'alert-info';
case 'warning':    return 'alert-warning';
case 'secondary':  return 'alert-secondary';
case 'primary':    return 'alert-primary';
case 'light':      return 'alert-light';
case 'dark':       return 'alert-dark';
default:           return 'alert-warning'; // Default to warning if no type matches
}
}
}
