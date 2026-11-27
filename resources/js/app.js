const menuButton = document.querySelector('.menu-toggle');
const navigation = document.querySelector('#role-navigation');
const passwordToggle = document.querySelector('[data-password-toggle]');
if (passwordToggle) {
    const password = document.getElementById(passwordToggle.getAttribute('aria-controls'));
    passwordToggle.hidden = false;
    passwordToggle.addEventListener('click', () => {
        const reveal = password.type === 'password';
        password.type = reveal ? 'text' : 'password';
        passwordToggle.textContent = reveal ? 'Sembunyikan password' : 'Lihat password';
    });
}
const portalHeader = document.querySelector('.portal-header');

if (portalHeader && 'ResizeObserver' in window) {
    new ResizeObserver(() => {
        document.documentElement.style.setProperty('--portal-header-height', portalHeader.offsetHeight + 'px');
    }).observe(portalHeader);
}

const drawer = document.querySelector('#module-drawer');
if (menuButton && navigation && drawer) {
    const sidebar = navigation.parentElement;
    const mobileViewport = window.matchMedia('(max-width: 760px)');
    const syncMenu = () => {
        drawer.close();
        menuButton.hidden = !mobileViewport.matches;
        sidebar.hidden = mobileViewport.matches;
        (mobileViewport.matches ? drawer : sidebar).append(navigation);
    };
    menuButton.addEventListener('click', () => {
        drawer.showModal();
        menuButton.setAttribute('aria-expanded', 'true');
    });
    drawer.addEventListener('close', () => menuButton.setAttribute('aria-expanded', 'false'));
    drawer.addEventListener('click', (event) => {
        if (event.target.closest('a') || (event.target === drawer && event.clientX >= drawer.getBoundingClientRect().right)) {
            drawer.close();
        }
    });
    syncMenu();
    mobileViewport.addEventListener('change', syncMenu);
}

document.querySelectorAll('[data-required-message]').forEach((input) => {
    input.addEventListener('input', () => input.setCustomValidity(''));
    input.addEventListener('invalid', () => {
        input.setCustomValidity('');
        if (input.validity.valueMissing) {
            input.setCustomValidity(input.dataset.requiredMessage);
        } else if (input.validity.typeMismatch) {
            input.setCustomValidity(input.dataset.typeMessage || 'Periksa kembali isian ini.');
        }
    });
});

document.querySelectorAll('[data-demo-email]').forEach((button) => {
    button.addEventListener('click', () => {
        const email = document.querySelector('#email');
        const password = document.querySelector('#password');
        email.value = button.dataset.demoEmail;
        password.value = 'password';
        [email, password].forEach((input) => input.setCustomValidity(''));
        document.querySelector('.login-form [type="submit"]').focus();
    });
});

document.querySelectorAll('[data-schedule-form]').forEach((form) => {
    const start = form.elements.start_time;
    const end = form.elements.end_time;
    const validateTimes = () => {
        const invalid = end.value && start.value && end.value <= start.value;
        end.setCustomValidity(invalid ? 'Jam selesai harus setelah jam mulai.' : '');
    };
    form.addEventListener('input', validateTimes);
    window.addEventListener('pageshow', validateTimes);
    validateTimes();
});

document.querySelectorAll('[data-submit-feedback]').forEach((form) => {
    const button = form.querySelector('[type="submit"]');
    const initialLabel = button.textContent;
    const resetSubmit = () => {
        button.disabled = false;
        button.textContent = initialLabel;
        form.removeAttribute('aria-busy');
        const status = form.querySelector('.submit-status');
        if (status) status.textContent = '';
    };
    let recoveryTimer;
    form.addEventListener('submit', () => {
        button.disabled = true;
        button.textContent = button.dataset.busyLabel;
        form.setAttribute('aria-busy', 'true');
        const status = form.querySelector('.submit-status');
        if (status) status.textContent = 'Permintaan sedang diproses.';
        recoveryTimer = window.setTimeout(resetSubmit, 15000);
    });
    window.addEventListener('pageshow', () => {
        window.clearTimeout(recoveryTimer);
        resetSubmit();
    });
});
