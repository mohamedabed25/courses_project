<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="register_logic.php" method="POST" name="signup" enctype="multipart/form-data">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="input-group">
                <label for="age">Age</label>
                <input type="number" name="age" id="age" min="1" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div class="input-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" required>
            </div>
            <div class="input-group">
                <label for="country">Country</label>
                <select name="country" id="country" required>
                    <option value="">Select your country</option>
                    <option value="US">United States</option>
                    <option value="CA">Canada</option>
                    <option value="UK">United Kingdom</option>
                    <!-- Add more countries as needed -->
                </select>
            </div>
            <div class="input-group">
                <label for="photo">Profile Photo</label>
                <input type="file" name="photo" id="photo" accept="image/*" required>
            </div>
            <button type="submit" name="signup" class="submit-btn">Register</button>
        </form>
    </div>
</body>
</html>
