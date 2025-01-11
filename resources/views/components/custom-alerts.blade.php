@php
    $alert = ['type' => '', 'texts' => [], 'text'=> ''];
    $messageTypes = ['success', 'error', 'info', 'warning'];
    foreach ($messageTypes as $type) {
        if(session()->has($type)) {
            if($type == 'message' || $type == 'success') {
                $alert['type'] = 'success';
            } if (in_array($type, $messageTypes)) {
                $alert['type'] = $type;
            } else {
                $alert['type'] = 'info';
                $alert['texts'] = 'Invalid message type, supported types are: message, success, error, info, warning';
            }
            
            if(is_array(session($type))) {
                $alert['texts'] = session($type);
            } else if (is_string(session($type))) {
                $alert['text'] = session($type);
            } else {
                $alert['text'] = 'Invalid message type [string/array], supported types are: message, success, error, info, warning';
            }
        }
    }
@endphp

<script>
    const alert = @json($alert);
    
    $(document).ready(function() {
        let texts = alert.texts;
        if(Object.isExtensible(alert.texts)) {
            texts = Object.values(alert.texts);
        }

        if(alert.text) {
            texts.push(alert.text);
        }

        if(alert.type && texts.length > 0) {
            texts.forEach(text => {
                toastr.options = {
                    closeButton: false,
                    progressBar: true,
                }
                toastr[alert.type](text);
            });
        }
    });
</script>