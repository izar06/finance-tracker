import './bootstrap';

// Auto-dismiss flash messages after 4 seconds
document.addEventListener('livewire:navigated', () => {
    initFlashMessages();
});

document.addEventListener('DOMContentLoaded', () => {
    initFlashMessages();
});

function initFlashMessages() {
    const flashes = document.querySelectorAll('[data-flash-message]');
    flashes.forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.4s, transform 0.4s';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });
}

// Format number as Rupiah (used in JS context if needed)
window.formatRupiah = (number) => {
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
};
