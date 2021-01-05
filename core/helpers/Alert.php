<?php

function display_notifications() {
    $types = ['error', 'success', 'info'];

    foreach($types as $type) {
        if(isset($_SESSION[$type]) && !empty($_SESSION[$type])) {
            if(!is_array($_SESSION[$type])) $_SESSION[$type] = [$_SESSION[$type]];

            foreach($_SESSION[$type] as $message) {
                $csstype = ($type == 'error') ? 'error' : $type;

                echo '
                    <script>
					const Toast = Swal.mixin({
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener("mouseenter", Swal.stopTimer)
                        toast.addEventListener("mouseleave", Swal.resumeTimer)
                    }
                    })

                    Toast.fire({
                    icon: "'. $csstype .'",
                    title: "'. $message .'"
                    })
                    </script>
				';
            }
            unset($_SESSION[$type]);
        }
    }

}

?>