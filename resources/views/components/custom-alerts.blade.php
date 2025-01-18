@if (!session()->has('undefined'))    
    @php
        function prepareAlerts(){
            $validAlertTypes = ['success', 'error', 'info', 'warning'];
            $alerts = ['type' => '', 'texts' => [], 'text'=> ''];

            if(session()->has('multiple')) {
                $alerts = [];

                $multipleAlerts = session('multiple');
                if(is_array($multipleAlerts)) {
                    foreach ($multipleAlerts as $alert) {
                        $type = isset($alert['type']) ? $alert['type'] : 'warning';
                        $text = isset($alert['text']) ? $alert['text'] : 'Undefined Alert Message!';
                        
                        if(in_array($type, $validAlertTypes)) {
                            $alerts[] = ['type' => $type, 'text' => $text];
                        } else {
                            $alerts[] = ['type' => 'warning', 'text' => $text];
                            $alerts[] = ['type' => 'warning', 'text' => "Invalid alert [Type: '{$type}'], supported types are: success, error, info, warning"]; 
                        }
                    }    
                }

                session()->forget('multiple');
                return $alerts;
            }

            $messageTypes = ['success', 'error', 'info', 'warning', 'message'];
            foreach ($messageTypes as $type) {
                if(session()->has($type)) {
                    if (in_array($type, $messageTypes)) {
                        $alerts['type'] = $type;
                    } else if ($type == 'message' || $type == 'success') {
                        $alerts['type'] = 'success';
                    } else {
                        $alerts['type'] = 'info';
                        $alerts['texts'] = 'Invalid message type, supported types are: message, success, error, info, warning';
                    }
                    
                    if(is_array(session($type))) {
                        $alerts['texts'] = session($type);
                    } else if (is_string(session($type))) {
                        $alerts['text'] = session($type);
                    } else {
                        $alerts['text'] = 'Invalid message type [string/array], supported types are: message, success, error, info, warning';
                    }
            
                    session()->forget($type);
                }
            }

            return $alerts;
        }

    @endphp

    <script>
        const sessionAlert = @json(prepareAlerts());
         
        $(document).ready(function() {
            toastr.options = {
                closeButton: true,
                progressBar: false,
            };
            if(Array.isArray(sessionAlert) && sessionAlert.length > 0) {
                sessionAlert.forEach(({ type, text }, index) => {
                    toastr[type](text)
                });
            } else {
                let texts = sessionAlert.texts;
                if(Object.isExtensible(sessionAlert.texts)) {
                    texts = Object.values(sessionAlert.texts);
                }

                if(sessionAlert.text) {
                    texts.push(sessionAlert.text);
                }

                if(sessionAlert.type && texts.length > 0) {
                    texts.forEach(text => {
                        toastr[sessionAlert.type](text);
                    });
                }
            }
        });
    </script>
@endif
