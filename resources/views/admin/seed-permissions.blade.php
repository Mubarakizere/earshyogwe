<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seed Permissions - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .success {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .error {
            background: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        button {
            background: #c81e1e;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover:not(:disabled) {
            background: #9b1c1c;
        }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            max-height: 400px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #c81e1e;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Seed Permissions</h1>
        <p>This tool updates permissions and roles in the database.</p>
        
        <div class="warning">
            <strong>⚠️ Important:</strong> This will run the RoleAndPermissionSeeder. Any new permissions added to the seeder will be created.
        </div>

        <div id="result"></div>

        <button onclick="seedPermissions()" id="seedBtn">
            Run Permission Seeder
        </button>

        <a href="{{ route('dashboard') }}" class="back-link">← Back to Dashboard</a>
    </div>

    <script>
        async function seedPermissions() {
            const btn = document.getElementById('seedBtn');
            const result = document.getElementById('result');
            
            btn.disabled = true;
            btn.textContent = 'Running seeder...';
            result.innerHTML = '<div class="warning">⏳ Please wait, this may take a few seconds...</div>';

            try {
                const response = await fetch('{{ route("admin.seed-permissions") }}?key={{ urlencode(config("app.key")) }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    result.innerHTML = `
                        <div class="success">
                            <strong>✅ Success!</strong> Permissions seeded successfully.
                            <pre>${data.output || ''}</pre>
                        </div>
                    `;
                } else {
                    result.innerHTML = `
                        <div class="error">
                            <strong>❌ Error:</strong> ${data.message}
                        </div>
                    `;
                }
            } catch (error) {
                result.innerHTML = `
                    <div class="error">
                        <strong>❌ Error:</strong> ${error.message}
                    </div>
                `;
            } finally {
                btn.disabled = false;
                btn.textContent = 'Run Permission Seeder';
            }
        }
    </script>
</body>
</html>
