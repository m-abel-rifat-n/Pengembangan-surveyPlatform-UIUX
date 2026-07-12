<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UIX-Probe</title>
    <link rel="shortcut icon" href="{{ asset('/assets/images/logo.png') }}" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* Hide ALL existing page content (navbar, body content, etc.) */
        body {
            visibility: hidden !important;
            background: transparent !important;
            overflow: hidden;
        }

        /* Only our overlay is visible */
        .overlay {
            visibility: visible !important;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2147483647;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .popup {
            visibility: visible !important;
            background: #fff;
            border-radius: 1rem;
            padding: 2.5rem 2rem 2rem;
            width: 22rem;
            text-align: center;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.2);
        }

        .icon-wrap {
            width: 5rem;
            height: 5rem;
            border-radius: 50%;
            border: 3px solid #e74c3c;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .icon-wrap svg {
            width: 2.2rem;
            height: 2.2rem;
            stroke: #e74c3c;
            fill: none;
            stroke-width: 3;
            stroke-linecap: round;
        }

        h5 {
            font-size: 1.4rem;
            font-weight: 700;
            color: #222;
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 1.75rem;
            line-height: 1.5;
        }

        a.btn-ok {
            display: inline-block;
            background: #3085d6;
            color: #fff;
            border-radius: 0.4rem;
            padding: 0.55rem 2.5rem;
            font-size: 0.95rem;
            text-decoration: none;
        }

        a.btn-ok:hover { background: #2574c4; }
    </style>
</head>
<body>
    <div class="overlay">
        <div class="popup">
            <div class="icon-wrap">
                <svg viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </div>
            <h5>Oops!</h5>
            <p>{{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}</p>
            <a href="{{ url('/surveys') }}" class="btn-ok">OK</a>
        </div>
    </div>
</body>
</html>
