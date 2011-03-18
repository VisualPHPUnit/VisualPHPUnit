<?php

//register_shutdown_function('check_for_errors');

function toHTML($type, $e) {
    $left = '<div class="box rounded error">';
    
    $out = '<div class="light rounded">!</div>
                <div class="message">'.$type.': '.$e['message'].'</div>
                <div class="line">'.$e['line'].'</div>
                <div class="file">'.$e['file'].'</div>';
    $right = '</div>';
    
    return $left . $out . $right;
}

function check_for_errors() {

    $results_so_far = '';
    if(@ob_get_contents()) {
            $results_so_far = @ob_get_contents();
            @ob_clean();
            $pu = new PHPUnit(); 
            echo $pu->toHTML($results_so_far);
    }
		

    if ( !is_null($aError = error_get_last()) ) 
    {
        switch ( $aError['type'] ) 
        {
            case E_NOTICE:
                echo toHTML("Notice", $aError);
                break;
            case E_WARNING:
                echo toHTML("Warning", $aError);
                break;
            case E_ERROR:
                echo toHTML("Error", $aError);
                break;
            case E_PARSE:
                echo toHTML("Parse", $aError);
                break;
            default:
                echo toHTML("Unknown", $aError);
                echo "<br/>";
                break;
        }
    }

    if ( $results_so_far ) 
    {
        include('Main/footer.html');
    }

    exit(1);
}

?>
