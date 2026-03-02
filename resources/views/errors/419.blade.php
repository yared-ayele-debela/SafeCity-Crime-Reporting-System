<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Expired - 419</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffffff;
        }
        .error-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            animation: fadeIn 0.8s ease-in;
        }
        .error-box {
        }
        .error-code {
            font-size: 10rem;
            font-weight: bold;
            color: #111010;
        }
        .error-message {
            font-size: 1.25rem;
            margin-top: 1rem;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
.btn-outline-primary {
    --bs-btn-color: #17BE18 !important;
    --bs-btn-border-color: #17BE18 !important;
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: #17BE18 !important;
    --bs-btn-hover-border-color: #17BE18 !important;
    --bs-btn-focus-shadow-rgb: 13,110,253;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #17BE18 !important;
    --bs-btn-active-border-color: #17BE18 !important;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #17BE18 !important;
    --bs-btn-disabled-bg: transparent;
    --bs-btn-disabled-border-color: #17BE18 !important;
    --bs-gradient: none
}

.btn-primary {
    --bs-btn-color: #fff;
    --bs-btn-bg: #17BE18 !important;
    --bs-btn-border-color: #17BE18 !important;
    --bs-btn-hover-color: #fff;
    --bs-btn-hover-bg: #149e14 !important;
    --bs-btn-hover-border-color: #149e14 !important;
    --bs-btn-focus-shadow-rgb: 49,132,253;
    --bs-btn-active-color: #fff;
    --bs-btn-active-bg: #149e14 !important;
    --bs-btn-active-border-color: #0a53be;
    --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
    --bs-btn-disabled-color: #fff;
    --bs-btn-disabled-bg: #17BE18 !important;
    --bs-btn-disabled-border-color: #17BE18 !important
}
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-box">
            <div class="error-code">419</div>
            <div class="error-message">Page expired. Please refresh and try again.</div>
            <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">Go Back</a>
        </div>
    </div>
</body>
</html>
