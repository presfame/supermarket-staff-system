<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Manimo Supermarket - @yield('title', 'Login')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
        }
        .split-container { display: flex; width: 100%; min-height: 100vh; }
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #1e40af 0%, #0f172a 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1604719312566-8912e9227c6a?w=1200') center/cover;
            opacity: 0.12;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 1;
        }
        .brand-icon {
            width: 50px; height: 50px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        .brand-text h1 {
            color: white;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .brand-text p {
            color: rgba(255,255,255,0.7);
            font-size: 12px;
        }
        .testimonial {
            position: relative;
            z-index: 1;
        }
        .testimonial-text {
            color: white;
            font-size: 20px;
            line-height: 1.8;
            margin-bottom: 20px;
            font-weight: 300;
        }
        .testimonial-author {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
            font-weight: 500;
        }
        .student-info {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            padding: 16px 20px;
            border-radius: 12px;
            margin-top: 30px;
        }
        .student-info p {
            color: rgba(255,255,255,0.9);
            font-size: 11px;
            margin-bottom: 4px;
        }
        .student-info p:last-child { margin-bottom: 0; }
        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background: #fff;
        }
        .login-container { width: 100%; max-width: 380px; }
        .login-header { text-align: center; margin-bottom: 36px; }
        .login-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .login-header p { color: #64748b; font-size: 14px; }
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 14px 16px;
            font-size: 14px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
            color: #0f172a;
            transition: all 0.2s;
            font-family: inherit;
        }
        .form-control:focus {
            outline: none;
            border-color: #1e40af;
            box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
        }
        .form-control.is-invalid { border-color: #ef4444; }
        .invalid-feedback { color: #ef4444; font-size: 12px; margin-top: 6px; }
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-check-input {
            width: 18px; height: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
        }
        .form-check-label { font-size: 13px; color: #64748b; }
        .btn-primary {
            width: 100%;
            padding: 14px 24px;
            font-size: 15px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            box-shadow: 0 4px 14px rgba(30, 64, 175, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 64, 175, 0.4);
        }
        .footer-text {
            text-align: center;
            margin-top: 28px;
            font-size: 12px;
            color: #94a3b8;
        }
        .footer-text a { color: #1e40af; text-decoration: none; font-weight: 500; }
        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { padding: 24px; }
        }
    </style>
</head>
<body>
    <div class="split-container">
        <div class="left-panel">
            <div class="brand">
                <div class="brand-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="brand-text">
                    <h1>Manimo Supermarket</h1>
                    <p>Employee & Payroll System</p>
                </div>
            </div>
            <div class="testimonial">
                <p class="testimonial-text">
                    "Streamlining our HR operations with automated payroll processing, attendance tracking, and comprehensive employee management."
                </p>
                <p class="testimonial-author">— Management Team, Manimo Supermarket</p>
                
                <div class="student-info">
                    <p><strong>FINAL YEAR PROJECT</strong></p>
                    <p>Student: PRESLEY OLENDO | Reg No: 22/05796</p>
                    <p>Supervisor: GLADYS MANGE</p>
                    <p>Course: BBIT | Unit Code: BBIT 04105</p>
                </div>
            </div>
        </div>
        <div class="right-panel">
            <div class="login-container">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
