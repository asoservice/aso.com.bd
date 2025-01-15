@php
    $alert = ['type' => '', 'texts' => [], 'text'=> ''];
    $messageTypes = ['success', 'error', 'info', 'warning', 'message'];
    foreach ($messageTypes as $type) {
        if(session()->has($type)) {
            if (in_array($type, $messageTypes)) {
                $alert['type'] = 'success';
            } else if ($type == 'message' || $type == 'success') {
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
    const sessionAlert = @json($alert);
    
    $(document).ready(function() {
        let texts = sessionAlert.texts;
        if(Object.isExtensible(sessionAlert.texts)) {
            texts = Object.values(sessionAlert.texts);
        }

        if(sessionAlert.text) {
            texts.push(sessionAlert.text);
        }

        if(sessionAlert.type && texts.length > 0) {
            texts.forEach(text => {
                toastr.options = {
                    closeButton: false,
                    progressBar: true,
                }
                toastr[sessionAlert.type](text);
            });
        }
    });
</script>