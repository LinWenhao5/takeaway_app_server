window.addEventListener('DOMContentLoaded', () => {
    const audio = document.getElementById('confirm-audio');
    if (!audio) return;
    audio.preload = "auto"; 

    function tryUnlock() {
        audio.play().then(() => {
            console.log("静默解锁成功");
        }).catch(() => {
            document.addEventListener('click', quietUnlock, { once: true });
        });
    }

    function quietUnlock() {
        audio.play().then(() => {
            console.log("通过点击静默解锁成功");
        }).catch(err => {
            document.addEventListener('click', quietUnlock, { once: true });
        });
    }

    if (audio.readyState >= 4) {
        tryUnlock();
    } else {
        audio.addEventListener('canplaythrough', () => {
            tryUnlock();
        }, { once: true });
    }
});