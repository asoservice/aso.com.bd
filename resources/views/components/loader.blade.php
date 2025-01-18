<div class="spinner-box">
    @if (isset($defaultLoader))
        @php
            if(is_string($defaultLoader)){
                $loader = file_exists($defaultLoader) ? asset($defaultLoader) : $defaultLoader;
            } else {
                $loader = asset('frontend/images/gif/loader.gif');
            }
        @endphp
        <img src="{{ $loader }}" alt="loader">
    @else
        <div class="pulse-container">
            <div class="pulse-bubble pulse-bubble-1"></div>
            <div class="pulse-bubble pulse-bubble-2"></div>
            <div class="pulse-bubble pulse-bubble-3"></div>
        </div>
    @endif
</div>

<script>
    function showLoader() {
        $('.spinner-box').addClass('active');
    }

    function hideLoader() {
        $('.spinner-box').removeClass('active');
    }

    function toggleLoader() {
        $('.spinner-box').toggleClass('active');
    }
</script>

<style>
    /* PULSE BUBBLES */
    .spinner-box {
        display: none;
    }

    .spinner-box.active {
        display: flex;
    }

    .spinner-box {
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        background-color: rgba(255, 255, 255, 0.3);
        z-index: 998;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        overflow: hidden;
        backdrop-filter: blur(4px);
    }

    .pulse-container {
        /* width: 120px; */
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        position: relative;
        z-index: 999;
    }

    .pulse-bubble {
        width: 40px !important;
        height: 40px !important;
        border-radius: 50%;
        background-color: var(--bs-blue, #0d6efd);
    }

    .pulse-bubble-1 {
        animation: pulse 0.4s ease 0s infinite alternate;
    }
    .pulse-bubble-2 {
        animation: pulse 0.4s ease 0.2s infinite alternate;
    }
    .pulse-bubble-3 {
        animation: pulse 0.4s ease 0.4s infinite alternate;
    }

    @keyframes pulse {
        from {
            opacity: 1;
            transform: scale(1);
        }
        to {
            opacity: 0.25;
            transform: scale(0.75);
        }
    }
</style>