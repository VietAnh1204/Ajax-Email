<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX Email PHP Test</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <h1 class="title">Send An Email Without An Email Address</h1>

    <!-- Phần thông báo lỗi hoặc thành công sẽ được hiển thị ở đây -->
    <div class="email-form__top-msg-container">
        <!-- Thông báo lỗi và thành công sẽ được chèn vào đây thông qua JS -->
    </div>

    <form action="" class="email-form">
        <label for="to">To:</label>
        <input type="email" name="to" id="to" class="email-form__text">
        <p class="email-form__err-msg" id="to-error"></p> <!-- Hiển thị lỗi email -->

        <label for="subject">Subject:</label>
        <input type="text" name="subject" id="subject" class="email-form__text">
        <p class="email-form__err-msg" id="subject-error"></p> <!-- Hiển thị lỗi subject -->

        <label for="message">Message:</label>
        <textarea name="message" id="message" class="email-form__textarea"></textarea>
        <p class="email-form__err-msg" id="message-error"></p> <!-- Hiển thị lỗi message -->

        <button type="submit" class="email-form__submit">Send</button>
    </form>

    <script src="js/emailForm.js"></script>
</body>
</html>
