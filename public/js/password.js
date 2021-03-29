const pass = document.getElementById('pass');
const checkbox = document.getElementById('checkbox');
checkbox.addEventListener('change', function () {
    if (checkbox.checked) {
        pass.setAttribute('type', 'text');
    } else {
        pass.setAttribute('type', 'password');
    }
})