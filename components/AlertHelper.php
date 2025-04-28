<?php

namespace components;

class AlertHelper
{

   public static function displayFlashMessage()
    {
        // Initialize a variable to hold the alert message HTML
        $flag = '';

        // Get all flash messages from the session
        $flashMessages = \Yii::$app->session->getAllFlashes();

        // Check if there are any flash messages
        if (!empty($flashMessages)) {
            // Loop through all flash messages and build the HTML
            foreach ($flashMessages as $type => $message) {
                // Determine the alert class based on the type
//                $alertClass = ($type == 'success') ? 'alert-success' : 'alert-danger';
                $message = is_array($message) ? implode('<br>', $message) : $message;

                // Determine Bootstrap alert class
                switch ($type) {
                    case 'success': $alertClass = 'alert-success'; break;
                    case 'danger':  $alertClass = 'alert-danger';  break;
                    case 'info':    $alertClass = 'alert-info';    break;
                    case 'warning': $alertClass = 'alert-warning'; break;
                    case 'secondary': $alertClass = 'alert-secondary'; break;
                    case 'primary': $alertClass = 'alert-primary'; break;
                    case 'light':   $alertClass = 'alert-light';   break;
                    case 'dark':    $alertClass = 'alert-dark';    break; // Fixed here
                    default:        $alertClass = 'alert-warning';
                }
                // Append the alert message HTML to $flag
                $flag .= "<div class=\"alert $alertClass\">$message</div>";
            }
        }

        // Return the HTML for all flash messages (or an empty string if no messages exist)
        return $flag;
    }

//with dimisal button
    public static function displayFlashMessageWithDismissButton()
    {
        // Initialize a variable to hold the alert message HTML
        $flag = '';

        // Get all flash messages from the session
        $flashMessages = \Yii::$app->session->getAllFlashes();

        // Check if there are any flash messages
        if (!empty($flashMessages)) {
            // Loop through all flash messages and build the HTML
            foreach ($flashMessages as $type => $message) {
                // Determine the alert class based on the type
//                $alertClass = ($type == 'success') ? 'alert-success' : 'alert-danger';
                $message = is_array($message) ? implode('<br>', $message) : $message;

                // Determine Bootstrap alert class
                switch ($type) {
                    case 'success': $alertClass = 'alert-success'; break;
                    case 'danger':  $alertClass = 'alert-danger';  break;
                    case 'info':    $alertClass = 'alert-info';    break;
                    case 'warning': $alertClass = 'alert-warning'; break;
                    case 'secondary': $alertClass = 'alert-secondary'; break;
                    case 'primary': $alertClass = 'alert-primary'; break;
                    case 'light':   $alertClass = 'alert-light';   break;
                    case 'dark':    $alertClass = 'alert-dark';    break; // Fixed here
                    default:        $alertClass = 'alert-warning';
                }
                // Create the close button HTML
                $closeButton = '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>';

                // Append the alert message HTML with the close button to $flag
                $flag .= "<div class=\"alert $alertClass alert-dismissible fade show\" role=\"alert\">
                        $message
                        $closeButton
                      </div>";
            }
        }

        // Return the HTML for all flash messages (or an empty string if no messages exist)
        return $flag;
    }

    public static function displayFlashMessagesWithTimeoutIntergrated()
{
    // Initialize a variable to hold the alert message HTML
    $flag = '';

    // Get all flash messages from the session
    $flashMessages = \Yii::$app->session->getAllFlashes();

    // Check if there are any flash messages
    if (!empty($flashMessages)) {
        // Loop through all flash messages and build the HTML
        foreach ($flashMessages as $type => $message) {
            // Determine the alert class based on the type
//            $alertClass = ($type == 'success') ? 'alert-success' : 'alert-danger';
//            $alertClass = ($type == 'success') ? 'alert-success' : 'alert-danger';
            // Convert array messages to string
            $message = is_array($message) ? implode('<br>', $message) : $message;

            // Determine Bootstrap alert class
            switch ($type) {
                case 'success': $alertClass = 'alert-success'; break;
                case 'danger':  $alertClass = 'alert-danger';  break;
                case 'info':    $alertClass = 'alert-info';    break;
                case 'warning': $alertClass = 'alert-warning'; break;
                case 'secondary': $alertClass = 'alert-secondary'; break;
                case 'primary': $alertClass = 'alert-primary'; break;
                case 'light':   $alertClass = 'alert-light';   break;
                case 'dark':    $alertClass = 'alert-dark';    break; // Fixed here
                default:        $alertClass = 'alert-warning';
            }

            // Append the alert message HTML with the close button to $flag
            $flag .= "<div class=\"alert $alertClass alert-dismissible fade show\" role=\"alert\">
                        $message
                      </div>";
        }
        // Add the JavaScript to automatically fade out the alerts after 3 seconds
        $flag .= '<script type="text/javascript">
                    setTimeout(function () {
                        // Hide error message after 3 seconds
                        $(".alert").fadeOut("slow");
                    }, 3000);
                  </script>';
    }

    // Return the HTML for all flash messages (or an empty string if no messages exist)
    return $flag;
}


    public  static function displayAutoFadeOutScript($timeout = 3000)
    {
        // Return the JavaScript code that will fade out the alerts after the specified timeout
        return "<script type=\"text/javascript\">
                setTimeout(function () {
                    // Hide all alerts after $timeout milliseconds
                    $('.alert').fadeOut('slow');
                }, $timeout);
            </script>";
    }

}