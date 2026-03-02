<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>404 - Page Not Found</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #ffffff, #d9dbdf);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .error-container {
      animation: fadeIn 1s ease-in-out;
    }
    .error-code {
      font-size: 10rem;
      font-weight: 900;
      color: red;
      text-shadow: 2px 2px #fff;
    }
    .error-text {
      font-size: 1.5rem;
      color: #444;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .btn-custom {
      padding: 0.75rem 1.5rem;
      font-size: 1rem;
      border-radius: 2rem;
      transition: background 0.3s ease;
    }
    .btn-custom:hover {
      background-color: #0fe20f;
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
  <div class="text-center error-container px-3">
    <div class="error-code">404</div>
    <h2 class="mb-3">Oops! Page Not Found</h2>
    <p class="error-text mb-4">The page you're looking for doesn’t exist or has been moved.</p>
    <a href="{{ url()->previous() }}" class="btn btn-primary rounded rounded-2">Return to Back</a>
  </div>
</body>
</html>
