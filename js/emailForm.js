const emailForm = document.querySelector('.email-form');

function removeAllMessagesFromForm(form) {
    form.querySelectorAll('.email-form__err-msg, .email-form__top-msg').forEach(msg => {
        msg.remove();
    });
}

emailForm.onsubmit = async e => {
    e.preventDefault();

    // Lấy các input từ form
    let toInput = emailForm.querySelector('input[name="to"]');
    let subjectInput = emailForm.querySelector('input[name="subject"]');
    let messageInput = emailForm.querySelector('textarea[name="message"]');

    // Xóa các thông báo cũ
    removeAllMessagesFromForm(emailForm);

    try {
        // Gửi dữ liệu đến máy chủ
        let response = await fetch('libraries/sendEmailForm.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            mode: 'same-origin',
            credentials: 'same-origin',
            body: JSON.stringify({
                to: toInput.value,
                subject: subjectInput.value,
                message: messageInput.value
            })
        });

        if (!response.ok) {
            throw new Error('Failed to connect to the server. Please try again later.');
        }

        let res = await response.json(); // Chuyển đổi JSON

        // Hiển thị lỗi nếu có
        if (res['to_err']) {
            let errorMessage = document.createElement('p');
            errorMessage.classList.add('email-form__err-msg');
            errorMessage.textContent = res['to_err'];
            toInput.insertAdjacentElement('beforebegin', errorMessage);
        }

        if (res['subject_err']) {
            let errorMessage = document.createElement('p');
            errorMessage.classList.add('email-form__err-msg');
            errorMessage.textContent = res['subject_err'];
            subjectInput.insertAdjacentElement('beforebegin', errorMessage);
        }

        if (res['message_err']) {
            let errorMessage = document.createElement('p');
            errorMessage.classList.add('email-form__err-msg');
            errorMessage.textContent = res['message_err'];
            messageInput.insertAdjacentElement('beforebegin', errorMessage);
        }

        if (res['top_err']) {
            let errorMessage = document.createElement('p');
            errorMessage.classList.add('email-form__top-msg', 'email-form__top-msg--err');
            errorMessage.textContent = res['top_err'];
            emailForm.insertAdjacentElement('beforebegin', errorMessage);
        }

        // Nếu có bất kỳ lỗi nào, dừng lại
        if (res['top_err'] || res['to_err'] || res['subject_err'] || res['message_err']) return;

        // Hiển thị thông báo thành công
        if (res['top_success']) {
            let successMessage = document.createElement('p');
            successMessage.classList.add('email-form__top-msg', 'email-form__top-msg--success');
            successMessage.textContent = res['top_success'];
            emailForm.insertAdjacentElement('beforebegin', successMessage);
            emailForm.reset(); // Reset form sau khi gửi thành công
        }

    } catch (error) {
        // Xử lý lỗi kết nối hoặc lỗi mạng
        let errorMessage = document.createElement('p');
        errorMessage.classList.add('email-form__top-msg', 'email-form__top-msg--err');
        errorMessage.textContent = error.message;
        emailForm.insertAdjacentElement('beforebegin', errorMessage);
    }
};
