function applyDarkMode() {
    const isDark = localStorage.getItem('darkMode') === 'true';
    document.body.classList.toggle('dark-mode', isDark);
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.textContent = isDark ? '🌞' : '🌙';
    }
}

function toggleDarkMode() {
    const isDark = !document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDark);
    applyDarkMode();
    window.dispatchEvent(new Event('storage'));
}

window.addEventListener('storage', () => {
    applyDarkMode();
});

document.addEventListener('DOMContentLoaded', () => {
    applyDarkMode();
    
    if (!document.getElementById('themeToggle')) {
        const toggleBtn = document.createElement('button');
        toggleBtn.id = 'themeToggle';
        toggleBtn.className = 'theme-toggle';
        toggleBtn.textContent = '🌙';
        toggleBtn.onclick = toggleDarkMode;
        document.body.appendChild(toggleBtn);
    } else {
        document.getElementById('themeToggle').onclick = toggleDarkMode;
    }
});