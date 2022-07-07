export const formatTime = (time) => {
    if (time <= 0) {
        return '00:00:00';
    }
    let sec = time % 60;
    time = Math.floor(time / 60);
    let min = time % 60;
    time = Math.floor(time / 60);
    if (sec < 10) {
        sec = `0${sec}`;
    }
    if (min < 10) {
        min = `0${min}`;
    }
    if (time < 10) {
        time = `0${time}`;
    }
    return time + ":" + min + ":" + sec;
}