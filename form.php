<?php
$success = false;
$error = '';
if (isset($_GET['success']) && $_GET['success'] == '1') {
    $success = true;
}
if (isset($_GET['error']) && $_GET['error'] !== '') {
    $error = htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }

        .form-title {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
            font-family: inherit;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 20px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Success Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .modal-content {
            background-color: white;
            margin: auto;
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 350px;
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                transform: translate(-50%, -60%);
                opacity: 0;
            }
            to {
                transform: translate(-50%, -50%);
                opacity: 1;
            }
        }

        .error-box {
            background: #ffe6e6;
            border: 1px solid #ff4d4d;
            color: #b30000;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .success-icon {
            width: 70px;
            height: 70px;
            background-color: #4CAF50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: scaleIn 0.4s;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .checkmark {
            color: white;
            font-size: 40px;
        }

        .success-title {
            color: #333;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .success-message {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .close-btn {
            background-color: #667eea;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .close-btn:hover {
            background-color: #764ba2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="form-title">Event Registration Form</h1>
        <?php if ($error): ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" action="connect.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="event">Event</label>
                <select id="event" name="event" required>
                    <option value="" disabled selected>Select an event...</option>
                    <option value="Tech Symposium 2026">Tech Symposium 2026</option>
                    <option value="Cultural Fest - Aurora">Cultural Fest - Aurora</option>
                    <option value="Hackathon: CodeSprint">Hackathon: CodeSprint</option>
                    <option value="Alumni Meetup">Alumni Meetup</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Submit</button>
        </form>
        <p style="text-align: center; margin-top: 16px; font-size: 14px; color: #555;">
            Already registered? <a href="view.php" style="color: #667eea; text-decoration: none; font-weight: 600;">View registration</a>
        </p>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="success-icon">
                <span class="checkmark">✓</span>
            </div>
            <h2 class="success-title">Successfully Registered!</h2>
            <p class="success-message">Your form has been submitted successfully.</p>
            <button class="close-btn" onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        const modal = document.getElementById('successModal');

        function closeModal() {
            modal.style.display = 'none';
        }

        // Close modal if user clicks outside of it
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                closeModal();
            }
        });

        // Show modal if form was successfully submitted
        <?php if (isset($success) && $success): ?>
        window.addEventListener('load', function() {
            modal.style.display = 'block';
        });
        <?php endif; ?>
    </script>
</body>
</html>
</html>
