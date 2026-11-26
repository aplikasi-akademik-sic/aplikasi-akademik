const menuButton = document.querySelector('.menu-toggle');
const navigation = document.querySelector('#role-navigation');

if (menuButton && navigation) {
    const mobileViewport = window.matchMedia('(max-width: 760px)');
    const setMenuOpen = (open) => {
        navigation.hidden = !open;
        menuButton.setAttribute('aria-expanded', String(open));
    };
    const syncMenu = () => {
        menuButton.hidden = !mobileViewport.matches;
        setMenuOpen(!mobileViewport.matches);
    };
    syncMenu();
    mobileViewport.addEventListener('change', syncMenu);
    menuButton.addEventListener('click', () => setMenuOpen(navigation.hidden));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && mobileViewport.matches && !navigation.hidden) {
            setMenuOpen(false);
            menuButton.focus();
        }
    });
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
