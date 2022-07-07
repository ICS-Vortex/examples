export default class Clock {
    constructor(to) {
        this.to = to;
        this.calculate();
    }

    calculate() {
        let nowDate = new Date();
        let countTo = new Date(this.to);
        let difference = (nowDate - countTo);

        let days = Math.floor(difference / (60 * 60 * 1000 * 24));
        let hours = Math.floor((difference % (60 * 60 * 1000 * 24)) / (60 * 60 * 1000));
        let minutes = Math.floor(((difference % (60 * 60 * 1000 * 24)) % (60 * 60 * 1000)) / (60 * 1000));
        let seconds = Math.floor((((difference % (60 * 60 * 1000 * 24)) % (60 * 60 * 1000)) % (60 * 1000)) / 1000);
        let totalHours = hours + (days * 24);

        return {
            hrs: totalHours < 10 ? '0' + totalHours : totalHours,
            min: minutes < 10 ? '0' + minutes : minutes,
            sec: seconds < 10 ? '0' + seconds : seconds
        };
    }

    start() {

    }
}