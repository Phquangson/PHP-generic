const SANITIZE_URL = '/user/sanitize';
const CSRF_TOKEN = '{{ csrf_token() }}';
const MESSAGE_REQUEST_FAILED = 'Có lỗi xảy ra khi xử lý dữ liệu';

function getInputValue(elementId) {
    return document.getElementById(elementId).value;
}

function collectFormData() {
    return {
        name: getInputValue('name'),
        age: getInputValue('age'),
        email: getInputValue('email'),
        description: getInputValue('description'),
    };
}

function renderJson(elementId, payload) {
    document.getElementById(elementId).textContent = JSON.stringify(payload, null, 4);
}

async function requestSanitize(formData) {
    const response = await fetch(SANITIZE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        body: JSON.stringify(formData),
    });

    return response.json();
}

async function testUser() {
    const formData = collectFormData();
    renderJson('before', formData);

    try {
        const result = await requestSanitize(formData);

        if (!result.success) {
            renderJson('after', { error: result.message });
            return;
        }

        renderJson('after', result.after);
    } catch (error) {
        renderJson('after', { error: MESSAGE_REQUEST_FAILED });
    }
}