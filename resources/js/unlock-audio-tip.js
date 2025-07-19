window.unlockAudioAndHideTip = function () {
    const audio = document.getElementById('confirm-audio');
    if (audio) {
        audio.muted = false;
        audio.currentTime = 0;
        audio.play();
    }
    const btn = document.getElementById('audio-btn');
    if (btn) btn.style.display = 'none';
};

window.addEventListener('DOMContentLoaded', () => {
    const audio = document.getElementById('confirm-audio');
    const btn = document.getElementById('audio-btn');
    if (audio && btn) {
        audio.play().then(() => {
            audio.pause();
            audio.currentTime = 0;
        }).catch(() => {
            btn.style.display = '';
        });
    }
});