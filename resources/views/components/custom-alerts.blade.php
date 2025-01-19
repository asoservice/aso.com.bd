@if (!session()->has('undefined'))    
    @php
        $messageTypes = ['success', 'error', 'info', 'warning', 'message'];
        $alerts = ['type' => '', 'texts' => [], 'text'=> ''];
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
    @endphp

    <script>
        const sessionAlert = @json($alerts);
         
        $(document).ready(function() {
            toastr.options = {
                closeButton: true,
                progressBar: false,
            };
            
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
        });
    </script>

    
    @if(session()->has('multiple'))
        <script>
            const multipleAlerts = @json(session('multiple'));

            $(document).ready(function() {
                multipleAlerts?.forEach(({ type, text }) => {
                    if(type in toastr) {
                        toastr[type](text || 'Alert text undefined');
                    } else toastr.warning(text || `Invalid toastr type: [${type}]`);
                });
            })
        </script>

        @php
            session()->forget('multiple');
        @endphp
    @endif
@endif

