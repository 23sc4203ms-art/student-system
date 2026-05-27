<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #f3f6fb;
            color: #1f2937;
        }

        .page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .container {
            width: 100%;
            max-width: 920px;
            text-align: center;
        }

        .content {
            max-width: 720px;
            margin: 0 auto;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: #111827;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .04em;
            margin-bottom: 22px;
        }

        .badge-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #fbbf24;
            box-shadow: 0 0 0 4px rgba(251, 191, 36, .2);
        }

        h1 {
            margin: 0;
            font-size: clamp(2rem, 4vw, 3.2rem);
            line-height: 1.15;
            color: #0f172a;
        }

        .lead {
            margin: 12px 0 0;
            font-size: 1.05rem;
            color: #4b5563;
        }

        .illustration {
            width: min(100%, 380px);
            height: 240px;
            margin: 28px auto 0;
            border-radius: 24px;
            background:
                radial-gradient(circle at 50% 30%, rgba(59, 130, 246, .18), transparent 38%),
                linear-gradient(180deg, #ffffff, #eaf2ff);
            border: 1px solid #dbe4f0;
            box-shadow: 0 18px 40px rgba(15, 23, 42, .08);
            position: relative;
            overflow: hidden;
        }

        .illustration::before {
            content: "";
            position: absolute;
            inset: auto 50% 26px auto;
            width: 170px;
            height: 110px;
            transform: translateX(50%);
            border-radius: 22px;
            background: linear-gradient(180deg, #94a3b8, #64748b);
            box-shadow: inset 0 0 0 10px rgba(255,255,255,.18);
        }

        .illustration::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 22px;
            width: 110px;
            height: 14px;
            transform: translateX(-50%);
            border-radius: 999px;
            background: rgba(15, 23, 42, .12);
            filter: blur(2px);
        }

        .gear {
            position: absolute;
            border-radius: 50%;
            background: #fbbf24;
            box-shadow: 0 0 0 10px rgba(251, 191, 36, .18);
        }

        .gear.one {
            width: 54px;
            height: 54px;
            left: 64px;
            top: 58px;
        }

        .gear.two {
            width: 36px;
            height: 36px;
            right: 72px;
            top: 76px;
            background: #60a5fa;
            box-shadow: 0 0 0 10px rgba(96, 165, 250, .18);
        }

        .footnote {
            margin-top: 20px;
            font-size: 0.92rem;
            color: #6b7280;
        }

        @media (max-width: 640px) {
            .page {
                padding: 16px;
            }

            .badge {
                font-size: 12px;
            }

            .illustration {
                height: 200px;
                border-radius: 20px;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="container">
            <div class="content">
                <div class="badge"><span class="badge-dot" aria-hidden="true"></span>Limited-time maintenance</div>
                <h1>We’re down for maintenance</h1>
                <p class="lead">We’re performing scheduled maintenance. Please check back shortly.</p>

                <div class="illustration" aria-hidden="true">
                    <span class="gear one"></span>
                    <span class="gear two"></span>
                </div>

                <p class="footnote">Thanks for your patience while we improve the system.</p>
            </div>
        </section>
    </main>
</body>
</html>
